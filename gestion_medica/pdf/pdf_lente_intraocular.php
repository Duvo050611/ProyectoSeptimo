<?php
session_start();
include "../../conexionbd.php";
require '../../fpdf/fpdf.php';

if (!isset($_SESSION['login']) || !isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

$id_lente = isset($_GET['id_lente']) ? (int)$_GET['id_lente'] : 0;
$id_exp = isset($_GET['id_exp']) ? (int)$_GET['id_exp'] : 0;
$id_atencion = isset($_GET['id_atencion']) ? (int)$_GET['id_atencion'] : 0;

if ($id_lente <= 0 || $id_exp <= 0 || $id_atencion <= 0) {
    die("ID de lente, expediente o atención no válido.");
}

// Fetch lens data
$sql_lente = "SELECT * FROM ocular_lente_intraocular WHERE id_lente = ? AND id_atencion = ? AND Id_exp = ?";
$stmt_lente = $conexion->prepare($sql_lente);
if (!$stmt_lente) {
    die("Error preparando consulta de lente: " . $conexion->error);
}
$stmt_lente->bind_param("iii", $id_lente, $id_atencion, $id_exp);
$stmt_lente->execute();
$result_lente = $stmt_lente->get_result();
if (!$lente = $result_lente->fetch_assoc()) {
    die("Lente no encontrado.");
}
$stmt_lente->close();

// Fetch patient data
$sql_pac = "SELECT p.papell, p.sapell, p.nom_pac, p.fecnac, p.folio, di.fecha, p.sexo, di.alergias 
            FROM paciente p 
            JOIN dat_ingreso di ON p.Id_exp = di.Id_exp 
            WHERE di.id_atencion = ?";
$stmt_pac = $conexion->prepare($sql_pac);
if (!$stmt_pac) {
    die("Error preparando consulta de paciente: " . $conexion->error);
}
$stmt_pac->bind_param("i", $id_atencion);
$stmt_pac->execute();
$result_pac = $stmt_pac->get_result();
if (!$row_pac = $result_pac->fetch_assoc()) {
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
$sql_doc = "SELECT pre, papell, sapell FROM reg_usuarios WHERE id_usua = ?";
$stmt_doc = $conexion->prepare($sql_doc);
if (!$stmt_doc) {
    die("Error preparando consulta de médico: " . $conexion->error);
}
$stmt_doc->bind_param("i", $lente['id_usua']);
$stmt_doc->execute();
$result_doc = $stmt_doc->get_result();
$medico = "Médico no asignado";
$doctor = $medico;
if ($row_doc = $result_doc->fetch_assoc()) {
    $medico = ($row_doc['pre'] ? $row_doc['pre'] . ". " : "") . $row_doc['papell'] . " " . ($row_doc['sapell'] ?? "");
    $doctor = $row_doc['papell'];
}
$stmt_doc->close();

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
$pdf->Line(1, 8, 209, 8);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('REPORTE DE LENTE INTRAOCULAR'), 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, utf8_decode("Paciente: {$folio} - {$pac_papell} {$pac_sapell} {$pac_nom_pac}"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Signos vitales:"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Presión arterial: {$p_sistolica}/{$p_diastolica} mmHg    Frecuencia: {$f_resp} Resp/min    Temperatura: {$temp} °C    Saturación: {$sat_oxigeno}%"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Edad: {$edad}    Sexo: {$pac_sexo}    Fecha de ingreso: {$pac_fecing}"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Fecha de registro: {$fecha_actual}"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Médico tratante: {$doctor}"), 0, 1, 'L');
$pdf->Ln(5);

// Lens details in two columns
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(95, 5, utf8_decode("Ojo Izquierdo"), 0, 0, 'L');
$pdf->Cell(95, 5, utf8_decode("Ojo Derecho"), 0, 1, 'L');
$pdf->Ln(2);

$left_eye_fields = [
    'Lente' => $lente['lente_izquierdo'] ? 'Sí' : 'No',
    'Marca' => $lente['marca_izquierdo'] ?? 'N/A',
    'Modelo' => $lente['modelo_izquierdo'] ?? 'N/A',
    'Dioptrias' => $lente['dioptrias_izquierdo'] ?? 'N/A',
    'Otros' => $lente['otros_izquierdo'] ?? 'N/A',
];

$right_eye_fields = [
    'Lente' => $lente['lente_derecho'] ? 'Sí' : 'No',
    'Marca' => $lente['marca_derecho'] ?? 'N/A',
    'Modelo' => $lente['modelo_derecho'] ?? 'N/A',
    'Dioptrias' => $lente['dioptrias_derecho'] ?? 'N/A',
    'Otros' => $lente['otros_derecho'] ?? 'N/A',
];

$max_height = 0;
$y_start = $pdf->GetY();

// Render left eye fields
$x_left = 10;
$y = $y_start;
foreach ($left_eye_fields as $label => $value) {
    if ($value !== 'N/A' || $label === 'Lente') {
        $pdf->SetXY($x_left, $y);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->MultiCell(45, 5, utf8_decode($label . ':'), 0, 'L');
        $height_label = $pdf->GetY() - $y;
        $pdf->SetXY($x_left + 45, $y);
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(50, 5, utf8_decode($value), 0, 'L');
        $height_value = $pdf->GetY() - $y;
        $height = max($height_label, $height_value);
        $y += $height + 2;
        $max_height = max($max_height, $y - $y_start);
    }
}

// Render right eye fields
$x_right = 105;
$y = $y_start;
foreach ($right_eye_fields as $label => $value) {
    if ($value !== 'N/A' || $label === 'Lente') {
        $pdf->SetXY($x_right, $y);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->MultiCell(45, 5, utf8_decode($label . ':'), 0, 'L');
        $height_label = $pdf->GetY() - $y;
        $pdf->SetXY($x_right + 45, $y);
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(50, 5, utf8_decode($value), 0, 'L');
        $height_value = $pdf->GetY() - $y;
        $height = max($height_label, $height_value);
        $y += $height + 2;
        $max_height = max($max_height, $y - $y_start);
    }
}

$pdf->SetY($y_start + $max_height + 10);
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
header('Content-Disposition: inline; filename="lente_intraocular.pdf"');
$pdf->Output('I', 'lente_intraocular.pdf');
exit();
?>