<?php
use PDF as GlobalPDF;
ob_start();
session_start();
include "../../conexionbd.php";
require '../../fpdf/fpdf.php';

if (!isset($_SESSION['hospital']) || !isset($_SESSION['login'])) {
    ob_end_clean();
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_atencion = $_SESSION['hospital'];
    $id_usua = isset($_POST['id_usua']) ? (int)$_POST['id_usua'] : 0;

    // Validate id_usua
    if ($id_usua === 0) {
        ob_end_clean();
        $_SESSION['message'] = "Error: ID de usuario no válido.";
        $_SESSION['message_type'] = "danger";
        header("Location: examenes_gab.php");
        exit();
    }

    // Fetch Id_exp and area from dat_ingreso
    $sql_exp = "SELECT Id_exp, area FROM dat_ingreso WHERE id_atencion = ?";
    $stmt_exp = $conexion->prepare($sql_exp);
    $stmt_exp->bind_param("i", $id_atencion);
    $stmt_exp->execute();
    $result_exp = $stmt_exp->get_result();
    if (!$row_exp = $result_exp->fetch_assoc()) {
        error_log("No dat_ingreso record found for id_atencion: $id_atencion");
        ob_end_clean();
        $_SESSION['message'] = "Atención no encontrada.";
        $_SESSION['message_type'] = "danger";
        header("Location: examenes_gab.php");
        exit();
    }
    $Id_exp = $row_exp['Id_exp'];
    $area = $row_exp['area'] ?? 'No asignada';
    $stmt_exp->close();

    // Fetch doctor details
    $sql_doc = "SELECT pre, papell, sapell FROM reg_usuarios WHERE id_usua = ?";
    $stmt_doc = $conexion->prepare($sql_doc);
    $stmt_doc->bind_param("i", $id_usua);
    $stmt_doc->execute();
    $result_doc = $stmt_doc->get_result();
    $medico = "Médico no asignado";
    $doctor = $medico;
    if ($row_doc = $result_doc->fetch_assoc()) {
        $medico = ($row_doc['pre'] ? $row_doc['pre'] . ". " : "") . $row_doc['papell'] . " " . ($row_doc['sapell'] ?? "");
        $doctor = $row_doc['papell'];
    }
    $stmt_doc->close();

    // Fetch room number
    $sql_hab = "SELECT num_cama FROM cat_camas WHERE id_atencion = ?";
    $stmt_hab = $conexion->prepare($sql_hab);
    $stmt_hab->bind_param("i", $id_atencion);
    $stmt_hab->execute();
    $result_hab = $stmt_hab->get_result();
    $habitacion = $result_hab->fetch_assoc()['num_cama'] ?? 'N/A';
    $stmt_hab->close();

    // Fetch all services with their prices from cat_servicios
    $sql_prices = "SELECT id_serv, serv_desc, serv_costo FROM cat_servicios WHERE serv_activo = 'SI'";
    $result_prices = $conexion->query($sql_prices);
    if (!$result_prices) {
        error_log("Failed to fetch services: " . $conexion->error);
        ob_end_clean();
        $_SESSION['message'] = "Error al cargar servicios.";
        $_SESSION['message_type'] = "danger";
        header("Location: examenes_gab.php");
        exit();
    }
    $prices = [];
    $service_names = [];
    while ($row = $result_prices->fetch_assoc()) {
        $prices[$row['id_serv']] = $row['serv_costo'];
        $service_names[$row['id_serv']] = $row['serv_desc'];
    }

    // Process selected services
    $selected_services = isset($_POST['services']) && is_array($_POST['services']) ? $_POST['services'] : [];
    $otros_gabinete = isset($_POST['otros_gabinete']) ? trim($_POST['otros_gabinete']) : null;
    $custom_price = isset($_POST['custom_price']) && is_numeric($_POST['custom_price']) ? floatval($_POST['custom_price']) : 0.00;

    // Validate form submission
    if (empty($selected_services) && empty($otros_gabinete)) {
        error_log("No services or custom service provided for id_atencion: $id_atencion");
        ob_end_clean();
        $_SESSION['message'] = "Seleccione al menos un servicio o especifique un estudio personalizado.";
        $_SESSION['message_type'] = "danger";
        header("Location: examenes_gab.php");
        exit();
    }

    // Calculate total price and compile studies list
    $total_price = 0.0;
    $studies = [];
    foreach ($selected_services as $serv_id => $value) {
        if ($value == 1 && isset($prices[$serv_id])) {
            $studies[] = $service_names[$serv_id];
            $total_price += $prices[$serv_id];
        }
    }
    if ($otros_gabinete) {
        $studies[] = $otros_gabinete;
        $total_price += $custom_price;
    }

    $sol_estudios = implode(", ", $studies);
    $fecha_ord = date("Y-m-d H:i:s");
    $det_gab = $otros_gabinete ?? "Consulta médica";
    $fecha_actual_sql = date("Y-m-d H:i:s");
    $cant = 1;

    // Insert selected services into ocular_examenes_gabinete and dat_ctapac
    $success = true;
    if (!empty($selected_services) || $otros_gabinete) {
        // Prepare insert for ocular_examenes_gabinete
        $sql_insert = "INSERT INTO ocular_examenes_gabinete (id_atencion, Id_exp, id_usua, id_serv, otros_gabinete, precio_total) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conexion->prepare($sql_insert);
        if (!$stmt_insert) {
            error_log("Prepare failed for ocular_examenes_gabinete: " . $conexion->error);
            ob_end_clean();
            $_SESSION['message'] = "Error preparando la consulta de datos.";
            $_SESSION['message_type'] = "danger";
            header("Location: examenes_gab.php");
            exit();
        }

        // Prepare insert for dat_ctapac
        $sql_ctapac = "INSERT INTO dat_ctapac (id_atencion, prod_serv, insumo, cta_fec, cta_cant, cta_tot, id_usua, cta_activo, centro_cto) VALUES (?, 'S', ?, ?, ?, ?, ?, 'SI', ?)";
        $stmt_ctapac = $conexion->prepare($sql_ctapac);
        if (!$stmt_ctapac) {
            error_log("Prepare failed for dat_ctapac: " . $conexion->error);
            ob_end_clean();
            $_SESSION['message'] = "Error preparando la consulta de cuenta.";
            $_SESSION['message_type'] = "danger";
            header("Location: examenes_gab.php");
            exit();
        }

        foreach ($selected_services as $serv_id => $value) {
            if ($value == 1 && isset($prices[$serv_id])) {
                // Insert into ocular_examenes_gabinete
                $service_desc = $service_names[$serv_id];
                $stmt_insert->bind_param("iiiisd", $id_atencion, $Id_exp, $id_usua, $serv_id, $service_desc, $prices[$serv_id]);
                if (!$stmt_insert->execute()) {
                    $success = false;
                    error_log("Insert failed for service $serv_id in ocular_examenes_gabinete: " . $stmt_insert->error);
                    break;
                }

                // Insert into dat_ctapac
                $stmt_ctapac->bind_param("iisdisi", $id_atencion, $serv_id, $fecha_actual_sql, $cant, $prices[$serv_id], $id_usua, $area);
                if (!$stmt_ctapac->execute()) {
                    $success = false;
                    error_log("Insert failed for service $serv_id in dat_ctapac: " . $stmt_ctapac->error);
                    break;
                }
            }
        }

        // Insert custom service if provided
        if ($otros_gabinete && $success) {
            $custom_serv_id = null; // NULL for custom services
            $stmt_insert->bind_param("iiissd", $id_atencion, $Id_exp, $id_usua, $custom_serv_id, $otros_gabinete, $custom_price);
            if (!$stmt_insert->execute()) {
                $success = false;
                error_log("Insert failed for custom service in ocular_examenes_gabinete: " . $stmt_insert->error);
            }

            // Insert custom service into dat_ctapac
            $custom_insumo = 0;
            $stmt_ctapac->bind_param("iisdisi", $id_atencion, $custom_insumo, $fecha_actual_sql, $cant, $custom_price, $id_usua, $area);
            if (!$stmt_ctapac->execute()) {
                $success = false;
                error_log("Insert failed for custom service in dat_ctapac: " . $stmt_ctapac->error);
            }
        }

        $stmt_insert->close();
        $stmt_ctapac->close();
    }

    if ($success) {
        // Fetch patient data for PDF
        $sql_pac = "SELECT p.papell, p.sapell, p.nom_pac, p.fecnac, p.Id_exp, p.folio, di.fecha, p.sexo, di.alergias FROM paciente p, dat_ingreso di WHERE p.Id_exp = di.Id_exp AND di.id_atencion = ?";
        $stmt_pac = $conexion->prepare($sql_pac);
        $stmt_pac->bind_param("i", $id_atencion);
        $stmt_pac->execute();
        $result_pac = $stmt_pac->get_result();
        if (!$row_pac = $result_pac->fetch_assoc()) {
            error_log("No patient data found for id_atencion: $id_atencion");
            ob_end_clean();
            $_SESSION['message'] = "Paciente no encontrado.";
            $_SESSION['message_type'] = "danger";
            header("Location: examenes_gab.php");
            exit();
        }
        $pac_papell = $row_pac['papell'];
        $pac_sapell = $row_pac['sapell'];
        $pac_nom_pac = $row_pac['nom_pac'];
        $pac_fecnac = $row_pac['fecnac'] ?? null;
        $folio = $row_pac['folio'];
        $pac_fecing = $row_pac['fecha'];
        $pac_sexo = $row_pac['sexo'];
        $pac_alergias = $row_pac['alergias'] ?? 'No especificado';
        $stmt_pac->close();

        // Fetch vital signs
        $sql_signs = "SELECT p_sistol, p_diastol, fresp, temper, satoxi FROM signos_vitales WHERE id_atencion = ? ORDER BY id_sig DESC LIMIT 1";
        $stmt_signs = $conexion->prepare($sql_signs);
        $stmt_signs->bind_param("i", $id_atencion);
        $stmt_signs->execute();
        $result_signs = $stmt_signs->get_result();
        $row_signs = $result_signs->fetch_assoc();
        $p_sistolica = $row_signs['p_sistol'] ?? '';
        $p_diastolica = $row_signs['p_diastol'] ?? '';
        $f_resp = $row_signs['fresp'] ?? '';
        $temp = $row_signs['temper'] ?? '';
        $sat_oxigeno = $row_signs['satoxi'] ?? '';
        $stmt_signs->close();

        // Calculate age
        function calculaedad($fechanacimiento) {
            if (!$fechanacimiento || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechanacimiento)) {
                return 'Edad no disponible';
            }
            list($ano, $mes, $dia) = explode("-", $fechanacimiento);
            $ano_diferencia = date("Y") - $ano;
            $mes_diferencia = date("m") - $mes;
            $dia_diferencia = date("d") - $dia;
            if ($dia_diferencia < 0) {
                $mes_diferencia--;
                $dia_diferencia += date("t", strtotime("$ano-$mes-01"));
            }
            if ($mes_diferencia < 0) {
                $ano_diferencia--;
                $mes_diferencia += 12;
            }
            if ($ano_diferencia > 0) {
                return $ano_diferencia . ' AÑOS';
            } elseif ($mes_diferencia > 0) {
                return $mes_diferencia . ' MESES';
            } else {
                return $dia_diferencia . ' DÍAS';
            }
        }
        $edad = calculaedad($pac_fecnac);

        // Compile studies list for PDF
        $numbered_studies = [];
        foreach ($studies as $index => $study) {
            $numbered_studies[] = ($index + 1) . ". " . $study;
        }
        $studies_list = implode("\n", $numbered_studies);

        // Current date and time for PDF
        $fecha_actual = date("d/m/Y H:i:s");

        // Create PDF class
        class PDF extends FPDF
        {
            function Header()
            {
                include '../../conexionbd.php';
                $resultado = $conexion->query("SELECT * FROM img_sistema ORDER BY id_simg DESC LIMIT 1") or die($conexion->error);
                while ($f = mysqli_fetch_array($resultado)) {
                    $bas = $f['img_ipdf'];
                    $this->Image("../../configuracion/admin/img2/{$bas}", 7, 9, 40, 25);
                    $this->Image("../../configuracion/admin/img3/{$f['img_cpdf']}", 58, 15, 109, 24);
                    $this->Image("../../configuracion/admin/img4/{$f['img_dpdf']}", 168, 16, 38, 14);
                }
                $this->Ln(32);
            }

            function Footer()
            {
                $this->SetY(-15);
                $this->SetFont('Arial', '', 8);
                $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
                $this->Cell(0, 10, utf8_decode('INEO-000'), 0, 1, 'R');
            }
        }

        // Generate PDF
        $pdf = new PDF('P');
        $pdf->AliasNbPages();
        $pdf->AddPage();

        $pdf->SetDrawColor(43, 45, 127);
        $pdf->Line(1, 8, 209, 8);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 10, utf8_decode('SOLICITUD DE ESTUDIOS'), 0, 1, 'C');
        $pdf->Ln(2);

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(0, 5, utf8_decode("Paciente: {$folio} - {$pac_papell} {$pac_sapell} {$pac_nom_pac}"), 0, 1, 'L');
        $pdf->Cell(0, 5, utf8_decode("Signos vitales:"), 0, 1, 'L');
        $pdf->Cell(0, 5, utf8_decode("Presión arterial: {$p_sistolica}/{$p_diastolica} mmHG                            Frecuencia: {$f_resp} Resp/min                       Temperatura: {$temp} °C                       Saturación: {$sat_oxigeno}%"), 0, 1, 'L');
        $pdf->Cell(0, 5, utf8_decode("Edad: {$edad}                                          Sexo: {$pac_sexo}                                     Fecha de ingreso: {$pac_fecing}"), 0, 1, 'L');
        $pdf->Cell(0, 5, utf8_decode("Fecha de solicitud: {$fecha_actual}                                                               Fecha y hora de solicitud: {$fecha_actual}"), 0, 1, 'L');
        $pdf->Cell(0, 5, utf8_decode("Médico tratante: {$doctor}"), 0, 1, 'L');
        $pdf->Cell(0, 5, utf8_decode("Estudio(s) solicitado(s):"), 0, 1, 'L');
        $pdf->MultiCell(0, 5, utf8_decode($studies_list), 0, 'L');
        $pdf->Cell(0, 5, utf8_decode("Costo total: \${$total_price} MXN"), 0, 1, 'L');
        $pdf->Cell(0, 5, utf8_decode("Detalle de estudio:"), 0, 1, 'L');
        $pdf->Cell(0, 5, utf8_decode("Diagnóstico probable: Consulta médica"), 0, 1, 'L');
        $pdf->Cell(0, 10, utf8_decode("Solicita: {$medico}"), 0, 1, 'C');
        $pdf->Ln(15);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(0, 5, utf8_decode("_____________________"), 0, 1, 'C');
        $pdf->Cell(0, 5, utf8_decode("Firma"), 0, 1, 'C');

        $bottom_y = $pdf->GetY() + 10;
        $pdf->Line(1, $bottom_y, 209, $bottom_y);
        $pdf->Line(1, 8, 1, $bottom_y);
        $pdf->Line(209, 8, 209, $bottom_y);

        // Save PDF to file
        $carpeta = $_SERVER['DOCUMENT_ROOT'] . '/gestion_medica/notas_medicas/solicitudes_gabinete/';
        if (!file_exists($carpeta) && !mkdir($carpeta, 0777, true)) {
            error_log("Failed to create directory: {$carpeta}");
            ob_end_clean();
            $_SESSION['message'] = "Error al crear directorio.";
            $_SESSION['message_type'] = "danger";
            header("Location: examenes_gab.php");
            exit();
        }
        $nombre_pdf = "solicitud_estudios_{$folio}_" . date('Ymd_His') . ".pdf";
        $nombre_final = $carpeta . $nombre_pdf;
        if (!is_writable($carpeta)) {
            error_log("Directory not writable: {$carpeta}");
            ob_end_clean();
            $_SESSION['message'] = "Directorio no escribible.";
            $_SESSION['message_type'] = "danger";
            header("Location: examenes_gab.php");
            exit();
        }

        // Insert into notificaciones_gabinete
        $sql_gab = "INSERT INTO notificaciones_gabinete (id_atencion, habitacion, fecha_ord, id_usua, sol_estudios, det_gab, activo, realizado, pdf_solicitud) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_gab = $conexion->prepare($sql_gab);
        if (!$stmt_gab) {
            error_log("Prepare failed for notificaciones_gabinete: " . $conexion->error);
            ob_end_clean();
            $_SESSION['message'] = "Error preparando notificación.";
            $_SESSION['message_type'] = "danger";
            header("Location: examenes_gab.php");
            exit();
        }
        $activo = 'SI';
        $realizado = 'NO';
        $stmt_gab->bind_param("ississsss", $id_atencion, $habitacion, $fecha_ord, $id_usua, $sol_estudios, $det_gab, $activo, $realizado, $nombre_pdf);

        if ($stmt_gab->execute()) {
            if ($pdf->Output('F', $nombre_final) === false) {
                error_log("Failed to generate PDF: {$nombre_final}");
                ob_end_clean();
                $_SESSION['message'] = "Error al generar PDF.";
                $_SESSION['message_type'] = "danger";
                header("Location: examenes_gab.php");
                exit();
            }

            $_SESSION['message'] = "Solicitud registrada y PDF generado exitosamente.";
            $_SESSION['message_type'] = "success";

            ob_end_clean();
            $pdf_url = "/gestion_medica/notas_medicas/solicitudes_gabinete/{$nombre_pdf}";

            echo "<script>
                window.open('$pdf_url', '_blank');
                window.location.href = 'examenes_gab.php';
            </script>";
            exit();
        } else {
            error_log("Insert failed for notificaciones_gabinete: " . $stmt_gab->error);
            ob_end_clean();
            $_SESSION['message'] = "Error al registrar notificación.";
            $_SESSION['message_type'] = "danger";
            header("Location: examenes_gab.php");
            exit();
        }
    } else {
        error_log("Insert failed for ocular_examenes_gabinete or dat_ctapac");
        ob_end_clean();
        $_SESSION['message'] = "Error al registrar.";
        $_SESSION['message_type'] = "danger";
        header("Location: examenes_gab.php");
        exit();
    }
}

ob_end_clean();
$_SESSION['message'] = "Método no permitido.";
$_SESSION['message_type'] = "danger";
header("Location: examenes_gab.php");
exit();
?>