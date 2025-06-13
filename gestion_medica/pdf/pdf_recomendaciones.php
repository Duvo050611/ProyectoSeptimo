<?php
session_start();
include "../../conexionbd.php";
require '../../fpdf/fpdf.php';

if (!isset($_SESSION['login']) || !isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

// Initialize variables explicitly
$id_recomendacion = isset($_GET['id_recomendacion']) ? (int)$_GET['id_recomendacion'] : 0;
$id_exp = isset($_GET['id_exp']) ? (int)$_GET['id_exp'] : 0;
$id_atencion = isset($_GET['id_atencion']) ? (int)$_GET['id_atencion'] : 0;

// Debug: Log variables to error log
error_log("id_recomendacion: $id_recomendacion, id_exp: $id_exp, id_atencion: $id_atencion");

if ($id_recomendacion <= 0 || $id_exp <= 0 || $id_atencion <= 0) {
    die("ID de recomendación, expediente o atención no válido.");
}

// Fetch recommendation data
$sql_rec = "SELECT * FROM ocular_recomendaciones WHERE id_recomendacion = ? AND id_atencion = ? AND Id_exp = ?";
$stmt_rec = $conexion->prepare($sql_rec);
if (!$stmt_rec) {
    error_log("Error preparando consulta de recomendación: " . $conexion->error);
    die("Error preparando consulta de recomendación: " . $conexion->error);
}

// Explicitly assign variables to ensure they are references
$bind_id_recomendacion = $id_recomendacion;
$bind_id_atencion = $id_atencion;
$bind_id_exp = $id_exp;

// Bind parameters
if (!$stmt_rec->bind_param("iii", $bind_id_recomendacion, $bind_id_atencion, $bind_id_exp)) {
    error_log("Error en bind_param: " . $stmt_rec->error);
    die("Error en bind_param: " . $stmt_rec->error);
}

$stmt_rec->execute();
$result_rec = $stmt_rec->get_result();
if (!$rec = $result_rec->fetch_assoc()) {
    error_log("Recomendación no encontrada para id_recomendacion: $id_recomendacion");
    die("Recomendación no encontrada.");
}
$stmt_rec->close();

// Fetch patient data
$sql_pac = "SELECT p.papell, p.sapell, p.nom_pac, p.fecnac, p.folio, di.fecha, p.sexo, di.alergias 
            FROM paciente p 
            JOIN dat_ingreso di ON p.Id_exp = di.Id_exp 
            WHERE di.id_atencion = ?";
$stmt_pac = $conexion->prepare($sql_pac);
if (!$stmt_pac) {
    error_log("Error preparando consulta de paciente: " . $conexion->error);
    die("Error preparando consulta de paciente: " . $conexion->error);
}
$stmt_pac->bind_param("i", $id_atencion);
$stmt_pac->execute();
$result_pac = $stmt_pac->get_result();
if (!$row_pac = $result_pac->fetch_assoc()) {
    error_log("Paciente no encontrado para id_atencion: $id_atencion");
    die("Paciente no encontrado.");
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

// Fetch doctor data
$doctor_id = $rec['doctor'] ?? $rec['id_usua'];
$sql_doc = "SELECT pre, papell, sapell FROM reg_usuarios WHERE id_usua = ?";
$stmt_doc = $conexion->prepare($sql_doc);
if (!$stmt_doc) {
    error_log("Error preparando consulta de médico: " . $conexion->error);
    die("Error preparando consulta de médico: " . $conexion->error);
}
$stmt_doc->bind_param("i", $doctor_id);
$stmt_doc->execute();
$result_doc = $stmt_doc->get_result();
$medico = "Médico no asignado";
$doctor = $medico;
if ($row_doc = $result_doc->fetch_assoc()) {
    $medico = ($row_doc['pre'] ? $row_doc['pre'] . ". " : "") . $row_doc['papell'] . " " . ($row_doc['sapell'] ?? "");
    $doctor = $row_doc['papell'];
}
$stmt_doc->close();

// Fetch specialist role
$sql_rol = "SELECT rol FROM rol WHERE id_rol = ?";
$stmt_rol = $conexion->prepare($sql_rol);
$especialista = "No especificado";
if ($rec['especialista']) {
    if (!$stmt_rol) {
        error_log("Error preparando consulta de rol: " . $conexion->error);
        die("Error preparando consulta de rol: " . $conexion->error);
    }
    $stmt_rol->bind_param("i", $rec['especialista']);
    $stmt_rol->execute();
    $result_rol = $stmt_rol->get_result();
    if ($row_rol = $result_rol->fetch_assoc()) {
        $especialista = $row_rol['rol'];
    }
    $stmt_rol->close();
}

// Fetch vital signs from exploracion_fisica
$sql_signs = "SELECT presion_sistolica, presion_diastolica, frecuencia_respiratoria, temperatura, spo2 
              FROM exploracion_fisica 
              WHERE id_atencion = ? 
              ORDER BY fecha DESC LIMIT 1";
$stmt_signs = $conexion->prepare($sql_signs);
$stmt_signs->bind_param("i", $id_atencion);
$stmt_signs->execute();
$result_signs = $stmt_signs->get_result();
$row_signs = $result_signs->fetch_assoc();
$p_sistolica = $row_signs['presion_sistolica'] ?? '';
$p_diastolica = $row_signs['presion_diastolica'] ?? '';
$f_resp = $row_signs['frecuencia_respiratoria'] ?? '';
$temp = $row_signs['temperatura'] ?? '';
$sat_oxigeno = $row_signs['spo2'] ?? '';
$stmt_signs->close();

// Calculate age
function calculaedad($fechanacimiento) {
    if (!$fechanacimiento || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechanacimiento)) {
        return 'Edad no disponible';
    }
    $fecha_actual = date("Y-m-d");
    $arr_nac = explode("-", $fechanacimiento);
    $arr_act = explode("-", $fecha_actual);
    $anos = $arr_act[0] - $arr_nac[0];
    $meses = $arr_act[1] - $arr_nac[1];
    $dias = $arr_act[2] - $arr_nac[2];

    if ($dias < 0) {
        $meses--;
        $dias += date("t", strtotime("$arr_act[0]-$arr_act[1]-01"));
    }
    if ($meses < 0) {
        $anos--;
        $meses += 12;
    }

    if ($anos > 0) {
        return $anos . " años";
    } elseif ($meses > 0) {
        return $meses . " meses";
    } else {
        return $dias . " días";
    }
}
$edad = calculaedad($pac_fecnac);

// Current date and time
$fecha_actual = date("d/m/Y H:i:s");

// Create PDF class
class PDF extends FPDF {
    function Header() {
        include "../../conexionbd.php";
        $resultado = $conexion->query("SELECT * FROM img_sistema ORDER BY id_simg DESC LIMIT 1") or die($conexion->error);
        while ($f = mysqli_fetch_assoc($resultado)) {
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
$pdf = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetDrawColor(43, 45, 127);
$pdf->Line(1,8, 209, 8);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('REPORTE DE RECOMENDACIONES OCULARES'), 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, utf8_decode("Paciente: {$folio} - {$pac_papell} {$pac_sapell} {$pac_nom_pac}"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Signos vitales:"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Presión arterial: {$p_sistolica}/{$p_diastolica} mmHg    Frecuencia: {$f_resp} Resp/min    Temperatura: {$temp} °C    Saturación: {$sat_oxigeno}%"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Edad: {$edad}    Sexo: {$pac_sexo}    Fecha de ingreso: {$pac_fecing}"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Fecha de registro: {$fecha_actual}"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Médico tratante: {$doctor}"), 0, 1, 'L');
$pdf->Ln(5);

// Ojo Derecho and Ojo Izquierdo side by side
$fields_od = [
    'Observaciones' => $rec['observaciones_od'] ?? 'N/A',
    'Recomendaciones' => $rec['recomendaciones_od'] ?? 'N/A',
];

$fields_oi = [
    'Observaciones' => $rec['observaciones_oi'] ?? 'N/A',
    'Recomendaciones' => $rec['recomendaciones_oi'] ?? 'N/A',
];

// Set column widths
$col_width = 95;
$label_width = 45;
$content_width = 45;

// Ojo Derecho and Ojo Izquierdo
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell($col_width, 6, utf8_decode('Ojo Derecho (OD)'), 0, 0, 'L');
$pdf->Cell($col_width, 6, utf8_decode('Ojo Izquierdo (OI)'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 9);

// Track maximum height for alignment
$max_height = 0;
$start_y = $pdf->GetY();

foreach ($fields_od as $label => $value) {
    $pdf->SetX(10);
    $pdf->MultiCell($label_width, 5, utf8_decode($label . ':'), 0, 'L');
    $y_after_label = $pdf->GetY();
    $pdf->SetXY(55, $pdf->GetY() - ($y_after_label - $start_y));
    $pdf->MultiCell($content_width, 5, utf8_decode($value), 0, 'L');
    $y_after_content = $pdf->GetY();
    $max_height = max($max_height, $y_after_content - $start_y);
    $pdf->SetXY(10, $y_after_content);
}

$pdf->SetXY(105, $start_y);
foreach ($fields_oi as $label => $value) {
    $pdf->SetX(105);
    $pdf->MultiCell($label_width, 5, utf8_decode($label . ':'), 0, 'L');
    $y_after_label = $pdf->GetY();
    $pdf->SetXY(150, $pdf->GetY() - ($y_after_label - $start_y));
    $pdf->MultiCell($content_width, 5, utf8_decode($value), 0, 'L');
}
$pdf->SetY($start_y + $max_height);
$pdf->Ln(8);

// Generales in a single column
$fields_gen = [
    'Recomendaciones Generales' => $rec['recomendaciones_general'] ?? 'N/A',
    'Educación al paciente' => $rec['educacion_paciente'] ?? 'N/A',
    'Pronóstico' => $rec['pronostico'] ?? 'N/A',
    'Detalles del pronóstico' => $rec['pronostico_text'] ?? 'N/A',
    'Interconsultas' => $rec['interconsultas_text'] ?? 'N/A',
    'Notas internas' => $rec['notas_internas'] ?? 'N/A',
    'Observaciones para justificante' => $rec['observaciones_justificante'] ?? 'N/A',
];

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 6, utf8_decode('Generales'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Ln(2); // Extra spacing before starting

foreach ($fields_gen as $label => $value) {
    $pdf->SetX(10);
    $pdf->MultiCell($label_width, 5, utf8_decode($label . ':'), 0, 'L');
    $y = $pdf->GetY();
    $pdf->SetXY(55, $y - 5);
    $pdf->MultiCell(140, 5, utf8_decode($value), 0, 'L');
    $pdf->Ln(3); // Spacing between items
}
$pdf->Ln(3); // Increased spacing after Generales

// Otros Datos
$fields_otros = [
    'Próxima cita' => $rec['proxima_cita'] ? date_format(date_create($rec['proxima_cita']), "d/m/Y H:i") : 'No especificada',
    'Con resultados' => $rec['con_resultados'] ? 'Sí' : 'No',
    'Cirugía' => $rec['cirugia'] ? 'Sí' : 'No',
    'Especialista' => $especialista,
    'Usuario registro' => $rec['usuario_registro'] ?? 'No especificado',
];

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 6, utf8_decode('Otros Datos'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 9);
foreach ($fields_otros as $label => $value) {
    $pdf->SetX(10);
    $pdf->MultiCell($label_width, 5, utf8_decode($label . ':'), 0, 'L');
    $y = $pdf->GetY();
    $pdf->SetXY(55, $y - 5);
    $pdf->MultiCell(140, 5, utf8_decode($value), 0, 'L');
    $pdf->Ln(3); // Spacing between items
}
$pdf->Ln(8);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, utf8_decode("Solicitante: {$medico}"), 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(0, 5, utf8_decode("_____________________"), 0, 1, 'C');
$pdf->Cell(0, 5, utf8_decode("Firma"), 0, 1, 'C');

$bottom_y = $pdf->GetY() + 10;
$pdf->Line(1, $bottom_y, 209, $bottom_y);
$pdf->Line(1, 8, 1, $bottom_y);
$pdf->Line(209, 8, 209, $bottom_y);

// Output PDF
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="recomendaciones_ocular.pdf"');
$pdf->Output('I', 'recomendaciones_ocular.pdf');
exit();
?>