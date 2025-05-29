<?php
ob_start();
session_start();
include "../../conexionbd.php";
require '../../fpdf/fpdf.php';

if (!isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

$id_atencion = $_SESSION['hospital'];

// Fetch Id_exp from dat_ingreso
$sql_exp = "SELECT Id_exp FROM dat_ingreso WHERE id_atencion = ?";
$stmt_exp = $conexion->prepare($sql_exp);
$stmt_exp->bind_param("i", $id_atencion);
$stmt_exp->execute();
$result_exp = $stmt_exp->get_result();
$row_exp = $result_exp->fetch_assoc();
$Id_exp = $row_exp['Id_exp'];
$stmt_exp->close();

// Get the doctor's name from the session
$usuario = $_SESSION['login'];
$medico = isset($usuario['papell']) ? $usuario['papell'] : "Médico no asignado";
$id_usua = $usuario['id_usua'] ?? null;

if ($id_usua) {
    $sql_doc = "SELECT pre, papell, sapell FROM reg_usuarios WHERE id_usua = ?";
    $stmt_doc = $conexion->prepare($sql_doc);
    $stmt_doc->bind_param("i", $id_usua);
    $stmt_doc->execute();
    $result_doc = $stmt_doc->get_result();
    $row_doc = $result_doc->fetch_assoc();
    $medico = ($row_doc['pre'] ? $row_doc['pre'] . ". " : "") . $row_doc['papell'] . " " . ($row_doc['sapell'] ?? "");
    $stmt_doc->close();
}

$doctor = $medico;

// Fetch room number
$sql_hab = "SELECT num_cama FROM cat_camas WHERE id_atencion = ?";
$stmt_hab = $conexion->prepare($sql_hab);
$stmt_hab->bind_param("i", $id_atencion);
$stmt_hab->execute();
$result_hab = $stmt_hab->get_result();
$habitacion = $result_hab->fetch_assoc()['num_cama'] ?? 'N/A';
$stmt_hab->close();

// Fetch all exams with their prices
$sql_prices = "SELECT id_examen, nombre_examen, precio FROM cat_examenes_gabinete WHERE activo = 'SI'";
$result_prices = $conexion->query($sql_prices);
$prices = [];
$exam_names = [];
while ($row = $result_prices->fetch_assoc()) {
    $prices[$row['id_examen']] = $row['precio'];
    $exam_names[$row['id_examen']] = $row['nombre_examen'];
}

// Process selected exams
$selected_exams = isset($_POST['examenes']) ? $_POST['examenes'] : [];
$otros_gabinete = isset($_POST['otros_gabinete']) ? trim($_POST['otros_gabinete']) : null;

// Calculate total price and compile studies list
$total_price = 0.0;
$studies = [];
foreach ($selected_exams as $exam_id => $value) {
    if ($value == 1) {
        $studies[] = $exam_names[$exam_id];
        $total_price += $prices[$exam_id];
    }
}
if ($otros_gabinete) {
    $studies[] = $otros_gabinete;
    // Add a price for custom exams if needed (currently $0)
}

$sol_estudios = implode(", ", $studies);
$fecha_ord = date("Y-m-d H:i:s");
$det_gab = $otros_gabinete ?? "Consulta médica";

// Insert selected exams into ocular_examenes_gabinete
$success = true;
if (!empty($selected_exams) || $otros_gabinete) {
    $sql_insert = "INSERT INTO ocular_examenes_gabinete (id_atencion, Id_exp, id_examen, otros_gabinete, precio_total) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = $conexion->prepare($sql_insert);

    foreach ($selected_exams as $exam_id => $value) {
        if ($value == 1) {
            $stmt_insert->bind_param("iiisd", $id_atencion, $Id_exp, $exam_id, $otros_gabinete, $prices[$exam_id]);
            if (!$stmt_insert->execute()) {
                $success = false;
                error_log("Insert failed for exam $exam_id: " . $stmt_insert->error);
                break;
            }
        }
    }

    // Insert custom exam if provided
    if ($otros_gabinete && $success) {
        $custom_exam_id = 0; // NULL or 0 to indicate no catalog exam
        $custom_price = 0.00; // Adjust if you add a custom price input
        $stmt_insert->bind_param("iiisd", $id_atencion, $Id_exp, $custom_exam_id, $otros_gabinete, $custom_price);
        if (!$stmt_insert->execute()) {
            $success = false;
            error_log("Insert failed for custom exam: " . $stmt_insert->error);
        }
    }

    $stmt_insert->close();
}

if ($success) {
    // Fetch patient data for PDF
    $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.fecnac, p.Id_exp, p.folio, di.fecha, p.sexo, di.alergias FROM paciente p, dat_ingreso di WHERE p.Id_exp=di.Id_exp AND di.id_atencion = ?";
    $stmt_pac = $conexion->prepare($sql_pac);
    $stmt_pac->bind_param("i", $id_atencion);
    $stmt_pac->execute();
    $result_pac = $stmt_pac->get_result();
    $row_pac = $result_pac->fetch_assoc();
    $pac_papell = $row_pac['papell'];
    $pac_sapell = $row_pac['sapell'];
    $pac_nom_pac = $row_pac['nom_pac'];
    $pac_fecnac = $row_pac['fecnac'];
    $folio = $row_pac['folio'];
    $pac_fecing = $row_pac['fecha'];
    $pac_sexo = $row_pac['sexo'];
    $pac_alergias = $row_pac['alergias'] ?? 'No especificado';
    $stmt_pac->close();

    // Fetch signs vitales
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
        list($ano, $mes, $dia) = explode("-", $fechanacimiento);
        $ano_diferencia = date("Y") - $ano;
        $mes_diferencia = date("m") - $mes;
        $dia_diferencia = date("d") - $dia;
        if ($ano_diferencia > 0) {
            return $ano_diferencia . ' AÑOS';
        } else if ($mes_diferencia > 0 || $ano_diferencia < 0) {
            return $mes_diferencia . ' MESES';
        } else if ($dia_diferencia > 0 || $mes_diferencia < 0 || $ano_diferencia < 0) {
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

    // Current date and time
    $fecha_actual = date("d/m/Y H:i:s");

    // Create PDF class
    class PDF extends FPDF
    {
        function Header()
        {
            $base_path = $_SERVER['DOCUMENT_ROOT'] . '/INEO/imagenes/';
            $left_image = $base_path . 'INEOizquierda.png';
            $center_image = $base_path . 'INEOcentral.png';
            $right_image = $base_path . 'INEOderecha.png';

            if (file_exists($left_image)) {
                $this->Image($left_image, 7, 9, 40, 25);
            } else {
                error_log("Image not found: $left_image");
            }

            if (file_exists($center_image)) {
                $this->Image($center_image, 58, 15, 109, 24);
            } else {
                error_log("Image not found: $center_image");
            }

            if (file_exists($right_image)) {
                $this->Image($right_image, 168, 16, 38, 14);
            } else {
                error_log("Image not found: $right_image");
            }

            $this->Ln(25);
        }

        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial', '', 8);
            $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
            $this->Cell(0, 10, utf8_decode('MAC-010'), 0, 1, 'R');
        }
    }

    // Generate PDF
    $pdf = new PDF('P');
    $pdf->AliasNbPages();
    $pdf->AddPage();

    $pdf->SetDrawColor(43, 45, 127);
    $pdf->Line(1, 8, 209, 8);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 10, utf8_decode('SOLICITUD DE ESTUDIOS DE GABINETE'), 0, 1, 'C');
    $pdf->Ln(2);

    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(0, 5, utf8_decode("Paciente: $folio - $pac_papell $pac_sapell $pac_nom_pac"), 0, 1, 'L');
    $pdf->Cell(0, 5, utf8_decode("Signos vitales:"), 0, 1, 'L');
    $pdf->Cell(0, 5, utf8_decode("Presión arterial:        $p_sistolica/$p_diastolica       mmHG      Frecuencia respiratoria:       $f_resp Resp/min       Temperatura:       $temp °C      Saturación oxígeno:     $sat_oxigeno %"), 0, 1, 'L');
    $pdf->Cell(0, 5, utf8_decode("Edad: $edad                                 Sexo: $pac_sexo                           Fecha de ingreso: $pac_fecing"), 0, 1, 'L');
    $pdf->Cell(0, 5, utf8_decode("Fecha de solicitud: $fecha_actual                              Fecha y hora de solicitud: $fecha_actual"), 0, 1, 'L');
    $pdf->Cell(0, 5, utf8_decode("Médico tratante: $doctor"), 0, 1, 'L');
    $pdf->Cell(0, 5, utf8_decode("Estudio(s) solicitado(s):"), 0, 1, 'L');
    $pdf->MultiCell(0, 5, utf8_decode($studies_list), 0, 'L');
    $pdf->Cell(0, 5, utf8_decode("Costo total: $$total_price MXN"), 0, 1, 'L');
    $pdf->Cell(0, 5, utf8_decode("Detalle de estudio:"), 0, 1, 'L');
    $pdf->Cell(0, 5, utf8_decode("Diagnóstico probable: Consulta"), 0, 1, 'L');
    $pdf->Cell(0, 10, utf8_decode("Solicita: $medico"), 0, 1, 'C');
    $pdf->Ln(15);

    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(0, 5, utf8_decode("_____________________"), 0, 1, 'C');
    $pdf->Cell(0, 5, utf8_decode("Firma"), 0, 1, 'C');

    $bottom_y = $pdf->GetY() + 10;
    $pdf->Line(1, $bottom_y, 209, $bottom_y);
    $pdf->Line(1, 8, 1, $bottom_y);
    $pdf->Line(209, 8, 209, $bottom_y);

    // Save PDF to the new Gabinete-specific directory
    $carpeta = $_SERVER['DOCUMENT_ROOT'] . '/gestion_medica/notas_medicas/solicitudes_gabinete/';
    if (!file_exists($carpeta)) {
        if (!mkdir($carpeta, 0777, true)) {
            error_log("Failed to create directory: $carpeta");
        }
    }
    $nombre_pdf = "solicitud_gabinete_{$folio}_" . date('Ymd_His') . ".pdf";
    $nombreFinal = $carpeta . $nombre_pdf;
    if (!is_writable($carpeta)) {
        error_log("Directory not writable: $carpeta");
    }

    // Insert into notificaciones_gabinete with PDF filename
    $sql_gab = "INSERT INTO notificaciones_gabinete (id_atencion, habitacion, fecha_ord, id_usua, sol_estudios, det_gab, activo, realizado, pdf_solicitud) 
                VALUES (?, ?, ?, ?, ?, ?, 'SI', 'NO', ?)";
    $stmt_gab = $conexion->prepare($sql_gab);
    if (!$stmt_gab) {
        error_log("Prepare failed for notificaciones_gabinete: " . $conexion->error);
        ob_end_clean();
        header("Location: examenes_gab.php?error=" . urlencode("Error en la consulta."));
        exit();
    }
    $stmt_gab->bind_param("ississs", $id_atencion, $habitacion, $fecha_ord, $id_usua, $sol_estudios, $det_gab, $nombre_pdf);

    if ($stmt_gab->execute()) {
        if ($pdf->Output('F', $nombreFinal) === false) {
            error_log("Failed to save PDF: $nombreFinal");
        }
        ob_end_clean();
        $pdf->Output('D', $nombre_pdf); // Send to browser for download
        exit();
    } else {
        error_log("Insert failed for notificaciones_gabinete: " . $stmt_gab->error);
        ob_end_clean();
        header("Location: examenes_gab.php?error=" . urlencode("Error al registrar notificación."));
        exit();
    }
} else {
    error_log("Insert failed for ocular_examenes_gabinete: " . $conexion->error);
    header("Location: examenes_gab.php?error=" . urlencode("Error al registrar exámenes."));
    $conexion->close();
    exit();
}

$conexion->close();
?>