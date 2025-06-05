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
        header("Location: examenes_lab.php");
        exit();
    }

    // Fetch Id_exp
    $sql_exp = "SELECT Id_exp FROM dat_ingreso WHERE id_atencion = ?";
    $stmt_exp = $conexion->prepare($sql_exp);
    $stmt_exp->bind_param("i", $id_atencion);
    $stmt_exp->execute();
    $result_exp = $stmt_exp->get_result();
    $row_exp = $result_exp->fetch_assoc();
    $Id_exp = $row_exp['Id_exp'] ?? null;
    $stmt_exp->close();

    if (!$Id_exp) {
        ob_end_clean();
        $_SESSION['message'] = "Error: No se encontró el expediente.";
        $_SESSION['message_type'] = "danger";
        header("Location: examenes_lab.php");
        exit();
    }

    // Fetch doctor details
    $sql_doc = "SELECT pre, nombre, papell, sapell FROM reg_usuarios WHERE id_usua = ?";
    $stmt_doc = $conexion->prepare($sql_doc);
    $stmt_doc->bind_param("i", $id_usua);
    $stmt_doc->execute();
    $result_doc = $stmt_doc->get_result();
    $row_doc = $result_doc->fetch_assoc();
    $medico = $row_doc ? ($row_doc['pre'] ? $row_doc['pre'] . ". " : "") . 
                         $row_doc['nombre'] . " " . 
                         $row_doc['papell'] . " " . 
                         ($row_doc['sapell'] ? $row_doc['sapell'] : "") : "Médico no asignado";
    $doctor = $row_doc['papell'] ?? "Médico no asignado";
    $stmt_doc->close();

    // Fetch room number
    $sql_hab = "SELECT num_cama FROM cat_camas WHERE id_atencion = ?";
    $stmt_hab = $conexion->prepare($sql_hab);
    $stmt_hab->bind_param("i", $id_atencion);
    $stmt_hab->execute();
    $result_hab = $stmt_hab->get_result();
    $habitacion = $result_hab->fetch_assoc()['num_cama'] ?? 'N/A';
    $stmt_hab->close();

    // Prepare form data
    $biometria_hematica = isset($_POST['biometria_hematica']) ? 1 : 0;
    $quimica_sanguinea = isset($_POST['quimica_sanguinea']) ? 1 : 0;
    $quimica_sanguinea_valores = $quimica_sanguinea ? (int)$_POST['quimica_sanguinea_valores'] : 0;
    $tiempos_coagulacion = isset($_POST['tiempos_coagulacion']) ? 1 : 0;
    $hemoglobina_glucosilada = isset($_POST['hemoglobina_glucosilada']) ? 1 : 0;
    $examen_general_orina = isset($_POST['examen_general_orina']) ? 1 : 0;
    $electrocardiograma = isset($_POST['electrocardiograma']) ? 1 : 0;
    $pruebas_funcion_hepatica = isset($_POST['pruebas_funcion_hepatica']) ? 1 : 0;
    $antigeno_sars_cov_2 = isset($_POST['antigeno_sars_cov_2']) ? 1 : 0;
    $pcr_sars_cov_2 = isset($_POST['pcr_sars_cov_2']) ? 1 : 0;
    $electroitos_sericos = isset($_POST['electroitos_sericos']) ? 1 : 0;
    $pruebas_funcion_tiroidea = isset($_POST['pruebas_funcion_tiroidea']) ? 1 : 0;
    $acs_anti_tiroglubolina = isset($_POST['acs_anti_tiroglubolina']) ? 1 : 0;
    $acs_antireceptores_tsh = isset($_POST['acs_antireceptores_tsh']) ? 1 : 0;
    $acs_antiperoxadasa = isset($_POST['acs_antiperoxadasa']) ? 1 : 0;
    $velocidad_sedimentacion_globular = isset($_POST['velocidad_sedimentacion_globular']) ? 1 : 0;
    $proteina_c_reactiva = isset($_POST['proteina_c_reactiva']) ? 1 : 0;
    $vdrl = isset($_POST['vdrl']) ? 1 : 0;
    $fta_abs = isset($_POST['fta_abs']) ? 1 : 0;
    $ppd = isset($_POST['ppd']) ? 1 : 0;
    $elisa_vih_1_y_2 = isset($_POST['elisa_vih_1_y_2']) ? 1 : 0;
    $acs_toxoplasmosis_igg_igm = isset($_POST['acs_toxoplasmosis_igg_igm']) ? 1 : 0;
    $factor_reumatoide = isset($_POST['factor_reumatoide']) ? 1 : 0;
    $acs_anti_ccp = isset($_POST['acs_anti_ccp']) ? 1 : 0;
    $antigeno_hla_b27 = isset($_POST['antigeno_hla_b27']) ? 1 : 0;
    $acs_antinucleares = isset($_POST['acs_antinucleares']) ? 1 : 0;
    $acs_anticardiolipina = isset($_POST['acs_anticardiolipina']) ? 1 : 0;
    $acs_p_ancasy_c_ancas = isset($_POST['acs_p_ancasy_c_ancas']) ? 1 : 0;
    $otros_laboratorio = isset($_POST['otros_laboratorio']) ? $conexion->real_escape_string(trim($_POST['otros_laboratorio'])) : null;

    // Compile studies list
    $studies = [];
    if ($biometria_hematica) $studies[] = "Biometría Hemática";
    if ($quimica_sanguinea) $studies[] = "Química Sanguínea ($quimica_sanguinea_valores elementos)";
    if ($tiempos_coagulacion) $studies[] = "Tiempos de Coagulación (TP/TT)";
    if ($hemoglobina_glucosilada) $studies[] = "Hemoglobina Glucosilada";
    if ($examen_general_orina) $studies[] = "Examen General de Orina";
    if ($electrocardiograma) $studies[] = "Electrocardiograma";
    if ($pruebas_funcion_hepatica) $studies[] = "Pruebas de Función Hepática";
    if ($antigeno_sars_cov_2) $studies[] = "Antígeno SARS-CoV-2";
    if ($pcr_sars_cov_2) $studies[] = "PCR SARS-CoV-2";
    if ($electroitos_sericos) $studies[] = "Electrolitos Séricos";
    if ($pruebas_funcion_tiroidea) $studies[] = "Pruebas de Función Tiroidea";
    if ($acs_anti_tiroglubolina) $studies[] = "AC'S Anti-Tiroglubolina";
    if ($acs_antireceptores_tsh) $studies[] = "ACS Anti-Receptores TSH";
    if ($acs_antiperoxadasa) $studies[] = "ACS Antiperoxadasa";
    if ($velocidad_sedimentacion_globular) $studies[] = "Velocidad de Sedimentación Globular";
    if ($proteina_c_reactiva) $studies[] = "Proteína C Reactiva";
    if ($vdrl) $studies[] = "VDRL";
    if ($fta_abs) $studies[] = "FTA-ABS";
    if ($ppd) $studies[] = "PPD";
    if ($elisa_vih_1_y_2) $studies[] = "ELISA VIH 1 y 2";
    if ($acs_toxoplasmosis_igg_igm) $studies[] = "ACS Toxoplasmosis IgG/IgM";
    if ($factor_reumatoide) $studies[] = "Factor Reumatoide";
    if ($acs_anti_ccp) $studies[] = "ACS Anti-CCP";
    if ($antigeno_hla_b27) $studies[] = "Antígeno HLA-B27";
    if ($acs_antinucleares) $studies[] = "ACS Antinucleares";
    if ($acs_anticardiolipina) $studies[] = "ACS Anticardiolipina";
    if ($acs_p_ancasy_c_ancas) $studies[] = "ACS P-ANCAs y C-ANCAs";
    if ($otros_laboratorio) $studies[] = $otros_laboratorio;

    $sol_estudios = implode(", ", $studies);
    $fecha_ord = date("Y-m-d H:i:s");
    $det_labo = $otros_laboratorio ?? "Consulta médica";

    // Validate form submission
    if (empty($studies)) {
        ob_end_clean();
        $_SESSION['message'] = "Seleccione al menos un estudio de laboratorio.";
        $_SESSION['message_type'] = "danger";
        header("Location: examenes_lab.php");
        exit();
    }

    // Insert into ocular_examenes_laboratorio
    $sql = "INSERT INTO ocular_examenes_laboratorio (
        id_atencion, Id_exp, id_usua, biometria_hematica, quimica_sanguinea, quimica_sanguinea_valores,
        tiempos_coagulacion, hemoglobina_glucosilada, examen_general_orina, electrocardiograma,
        pruebas_funcion_hepatica, antigeno_sars_cov_2, pcr_sars_cov_2, electroitos_sericos,
        pruebas_funcion_tiroidea, acs_anti_tiroglubolina, acs_antireceptores_tsh, acs_antiperoxadasa,
        velocidad_sedimentacion_globular, proteina_c_reactiva, vdrl, fta_abs, ppd, elisa_vih_1_y_2,
        acs_toxoplasmosis_igg_igm, factor_reumatoide, acs_anti_ccp, antigeno_hla_b27,
        acs_antinucleares, acs_anticardiolipina, acs_p_ancasy_c_ancas, otros_laboratorio
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        ob_end_clean();
        $_SESSION['message'] = "Error preparando la consulta: " . $conexion->error;
        $_SESSION['message_type'] = "danger";
        header("Location: examenes_lab.php");
        exit();
    }
    $stmt->bind_param(
        "iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiis",
        $id_atencion,
        $Id_exp,
        $id_usua,
        $biometria_hematica,
        $quimica_sanguinea,
        $quimica_sanguinea_valores,
        $tiempos_coagulacion,
        $hemoglobina_glucosilada,
        $examen_general_orina,
        $electrocardiograma,
        $pruebas_funcion_hepatica,
        $antigeno_sars_cov_2,
        $pcr_sars_cov_2,
        $electroitos_sericos,
        $pruebas_funcion_tiroidea,
        $acs_anti_tiroglubolina,
        $acs_antireceptores_tsh,
        $acs_antiperoxadasa,
        $velocidad_sedimentacion_globular,
        $proteina_c_reactiva,
        $vdrl,
        $fta_abs,
        $ppd,
        $elisa_vih_1_y_2,
        $acs_toxoplasmosis_igg_igm,
        $factor_reumatoide,
        $acs_anti_ccp,
        $antigeno_hla_b27,
        $acs_antinucleares,
        $acs_anticardiolipina,
        $acs_p_ancasy_c_ancas,
        $otros_laboratorio
    );

    if ($stmt->execute()) {
        // Fetch patient data for PDF
        $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.fecnac, p.Id_exp, p.folio, di.fecha, p.sexo, di.alergias 
                    FROM paciente p, dat_ingreso di 
                    WHERE p.Id_exp = di.Id_exp AND di.id_atencion = ?";
        $stmt_pac = $conexion->prepare($sql_pac);
        $stmt_pac->bind_param("i", $id_atencion);
        $stmt_pac->execute();
        $result_pac = $stmt_pac->get_result();
        $row_pac = $result_pac->fetch_assoc();
        $pac_papell = $row_pac['papell'] ?? '';
        $pac_sapell = $row_pac['sapell'] ?? '';
        $pac_nom_pac = $row_pac['nom_pac'] ?? '';
        $pac_fecnac = $row_pac['fecnac'] ?? '';
        $folio = $row_pac['folio'] ?? '';
        $pac_fecing = $row_pac['fecha'] ?? '';
        $pac_sexo = $row_pac['sexo'] ?? '';
        $pac_alergias = $row_pac['alergias'] ?? 'No especificado';
        $stmt_pac->close();

        // Fetch vital signs
        $sql_signs = "SELECT p_sistol, p_diastol, fresp, temper, satoxi 
                      FROM signos_vitales 
                      WHERE id_atencion = ? 
                      ORDER BY id_sig DESC LIMIT 1";
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

        // Current date/time
        $fecha_actual = date("d/m/Y H:i:s");

        // PDF class
        class PDF extends FPDF {
            function Header() {
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
            function Footer() {
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
        $pdf->Cell(0, 10, utf8_decode('SOLICITUD DE ESTUDIOS DE LABORATORIO'), 0, 1, 'C');
        $pdf->Ln(2);

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(0, 5, utf8_decode("Paciente: {$folio} - {$pac_papell} {$pac_sapell} {$pac_nom_pac}"), 0, 1, 'L');
        $pdf->Cell(0, 5, utf8_decode("Signos vitales:"), 0, 1, 'L');
        $pdf->Cell(0, 5, utf8_decode("Presión arterial: {$p_sistolica}/{$p_diastolica} mmHg                            Frecuencia: {$f_resp} Resp/min                       Temperatura: {$temp} °C                       Saturación: {$sat_oxigeno}%"), 0, 1, 'L');
        $pdf->Cell(0, 5, utf8_decode("Edad: {$edad}                                          Sexo: {$pac_sexo}                                     Fecha de ingreso: {$pac_fecing}"), 0, 1, 'L');
        $pdf->Cell(0, 5, utf8_decode("Fecha de solicitud: {$fecha_actual}                                                               Fecha y hora: {$fecha_actual}"), 0, 1, 'L');
        $pdf->Cell(0, 5, utf8_decode("Médico tratante: {$doctor}"), 0, 1, 'L');
        $pdf->Cell(0, 5, utf8_decode("Estudio(s) solicitado(s):"), 0, 1, 'L');
        $pdf->MultiCell(0, 5, utf8_decode($studies_list), 0, 'L');
        $pdf->Cell(0, 5, utf8_decode("Detalle de estudio:"), 0, 1, 'L');
        $pdf->Cell(0, 5, utf8_decode("Diagnóstico probable: Consulta médica"), 0, 1, 'L');
        $pdf->Cell(0, 10, utf8_decode("Solicita: {$medico}"), 0, 1, 'C');
        $pdf->Ln(15);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(0, 5, utf8_decode('_____________________'), 0, 1, 'C');
        $pdf->Cell(0, 5, utf8_decode('Firma'), 0, 1, 'C');

        $bottom_y = $pdf->GetY() + 10;
        $pdf->Line(1, $bottom_y, 209, $bottom_y);
        $pdf->Line(1, 8, 1, $bottom_y);
        $pdf->Line(209, 8, 209, $bottom_y);

        // Save PDF
        $carpeta = $_SERVER['DOCUMENT_ROOT'] . '/gestion_medica/notas_medicas/solicitudes/';
        if (!file_exists($carpeta) && !mkdir($carpeta, 0777, true)) {
            error_log("Failed to create directory: {$carpeta}");
            ob_end_clean();
            $_SESSION['message'] = "Error al crear directorio.";
            $_SESSION['message_type'] = "danger";
            header("Location: examenes_lab.php");
            exit();
        }
        $nombre_pdf = "solicitud_lab_{$folio}_" . date('Ymd_His') . ".pdf";
        $nombre_final = $carpeta . $nombre_pdf;

        // Insert into notificaciones_labo
        $sql_labo = "INSERT INTO notificaciones_labo (
            id_atencion, habitacion, fecha_ord, id_usua, sol_estudios, det_labo, activo, realizado, pdf_solicitud
        ) VALUES (?, ?, ?, ?, ?, ?, 'SI', 'NO', ?)";
        $stmt_labo = $conexion->prepare($sql_labo);
        if (!$stmt_labo) {
            error_log("Prepare failed for notificaciones_labo: " . $conexion->error);
            ob_end_clean();
            $_SESSION['message'] = "Error en la consulta de notificación.";
            $_SESSION['message_type'] = "danger";
            header("Location: examenes_lab.php");
            exit();
        }
        $stmt_labo->bind_param("ississs", $id_atencion, $habitacion, $fecha_ord, $id_usua, $sol_estudios, $det_labo, $nombre_pdf);

        if ($stmt_labo->execute()) {
            if ($pdf->Output('F', $nombre_final) !== false) {
                $_SESSION['message'] = "Solicitud registrada y PDF generado.";
                $_SESSION['message_type'] = "success";
                $pdf_url = "/gestion_medica/notas_medicas/solicitudes/{$nombre_pdf}";
                ob_end_clean();
                echo "<script>
                    window.open('$pdf_url', '_blank');
                    window.location = 'examenes_lab.php';
                </script>";
                exit();
            }
            error_log("Failed to save PDF: {$nombre_final}");
            ob_end_clean();
            $_SESSION['message'] = "Error al generar PDF.";
            $_SESSION['message_type'] = "danger";
            header("Location: examenes_lab.php");
            exit();
        }

        error_log("Insert failed for notificaciones_labo: " . $stmt_labo->error);
        ob_end_clean();
        $_SESSION['message'] = "Error al registrar notificación.";
        $_SESSION['message_type'] = "danger";
        header("Location: examenes_lab.php");
        exit();
    }

    error_log("Insert failed for ocular_examenes_laboratorio: " . $stmt->error);
    ob_end_clean();
    $_SESSION['message'] = "Error al registrar: " . $stmt->error;
    $_SESSION['message_type'] = "danger";
    header("Location: examenes_lab.php");
    exit();
}

ob_end_clean();
$_SESSION['message'] = "Método no permitido.";
$_SESSION['message_type'] = "danger";
header("Location: examenes_lab.php");
exit();
?>