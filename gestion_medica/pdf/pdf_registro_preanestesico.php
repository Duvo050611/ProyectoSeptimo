<?php
session_start();
include "../../conexionbd.php";
require '../../fpdf/fpdf.php';

if (!isset($_SESSION['login']) || !isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

$id_pre_anest = isset($_GET['id_pre_anest']) ? (int)$_GET['id_pre_anest'] : 0;
$id_exp = isset($_GET['id_exp']) ? (int)$_GET['id_exp'] : 0;
$id_atencion = isset($_GET['id_atencion']) ? (int)$_GET['id_atencion'] : 0;

if ($id_pre_anest <= 0 || $id_exp <= 0 || $id_atencion <= 0) {
    die("ID de evaluación preanestésica, expediente o atención no válido.");
}

// Fetch pre-anesthetic evaluation data
$sql_preanest = "SELECT * FROM preanesthetic_evaluation WHERE id_pre_anest = ? AND id_atencion = ? AND Id_exp = ?";
$stmt_preanest = $conexion->prepare($sql_preanest);
if (!$stmt_preanest) {
    die("Error preparando consulta de evaluación preanestésica: " . $conexion->error);
}
$stmt_preanest->bind_param("iii", $id_pre_anest, $id_atencion, $id_exp);
$stmt_preanest->execute();
$result_preanest = $stmt_preanest->get_result();
if (!$preanest = $result_preanest->fetch_assoc()) {
    die("Evaluación preanestésica no encontrada.");
}
$stmt_preanest->close();

// Fetch patient data
$sql_pac = "SELECT p.papell, p.sapell, p.nom_pac, p.fecnac, p.folio, p.sexo, p.tel, p.ocup, p.dir, di.fecha, di.tipo_a 
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
$tipo_a = $row_pac['tipo_a'];
$pac_papell = $row_pac['papell'];
$pac_sapell = $row_pac['sapell'];
$pac_nom_pac = $row_pac['nom_pac'];
$pac_fecnac = $row_pac['fecnac'] ?? null;
$folio = $row_pac['folio'];
$pac_fecing = $row_pac['fecha'];
$pac_sexo = $row_pac['sexo'];
$pac_tel = $row_pac['tel'] ?? 'No especificado';
$pac_ocup = $row_pac['ocup'] ?? 'No especificado';
$pac_dir = $row_pac['dir'] ?? 'No especificado';
$stmt_pac->close();

// Fetch doctor data (anesthesiologist)
$sql_doc = "SELECT pre, papell, sapell, nombre, firma, cedp, cargp FROM reg_usuarios WHERE id_usua = ?";
$stmt_doc = $conexion->prepare($sql_doc);
if (!$stmt_doc) {
    die("Error preparando consulta de usuario: " . $conexion->error);
}
$stmt_doc->bind_param("i", $preanest['id_usua']);
$stmt_doc->execute();
$result_doc = $stmt_doc->get_result();
$medico = "Anestesiólogo no asignado";
$pre_med = '';
$app_med = '';
$apm_med = '';
$nom_med = '';
$firma = '';
$ced_p = '';
$cargp = '';
if ($row_doc = $result_doc->fetch_assoc()) {
    $pre_med = $row_doc['pre'] ?? '';
    $app_med = $row_doc['papell'] ?? '';
    $apm_med = $row_doc['sapell'] ?? '';
    $nom_med = $row_doc['nombre'] ?? '';
    $firma = $row_doc['firma'] ?? '';
    $ced_p = $row_doc['cedp'] ?? '';
    $cargp = $row_doc['cargp'] ?? '';
    $medico = ($pre_med ? $pre_med . ". " : "") . $app_med . " " . $apm_med . " " . $nom_med;
}
$stmt_doc->close();

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
$fecha_actual = date("d/m/Y H:i");

// Create PDF class
class PDF extends FPDF {
    function Header() {
        global $conexion;
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(40, 40, 40);
        $resultado = $conexion->query("SELECT * FROM img_sistema ORDER BY id_simg DESC LIMIT 1");
        if ($resultado && $f = mysqli_fetch_assoc($resultado)) {
            $this->Image("../../configuracion/admin/img2/{$f['img_ipdf']}", 7, 10, 35, 20);
            $this->Image("../../configuracion/admin/img3/{$f['img_cpdf']}", 50, 10, 100, 20);
            $this->Image("../../configuracion/admin/img4/{$f['img_dpdf']}", 160, 10, 35, 12);
        }
        $this->SetY(35);
        $this->Cell(0, 10, utf8_decode('EVALUACIÓN PREANESTÉSICA'), 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 6, utf8_decode('Fecha: ') . date('d/m/Y H:i'), 0, 1, 'R');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(120, 120, 120);
        $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
        $this->Cell(0, 10, utf8_decode('INEO-000'), 0, 1, 'R');
    }
}

// Generate PDF
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(true, 25);

// Patient Data Section (Full Width)
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(230, 240, 255);
$pdf->Cell(0, 8, 'Datos del Paciente:', 0, 1, 'L', true);
$pdf->SetFont('Arial', '', 10);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(35, 6, 'Servicio:', 0, 0, 'L');
$pdf->Cell(55, 6, utf8_decode($tipo_a), 0, 0, 'L');
$pdf->Cell(35, 6, 'Fecha de registro:', 0, 0, 'L');
$pdf->Cell(0, 6, $pac_fecing ? date('d/m/Y H:i', strtotime($pac_fecing)) : 'N/A', 0, 1, 'L');
$pdf->Cell(35, 6, 'Paciente:', 0, 0, 'L');
$pdf->Cell(55, 6, utf8_decode($folio . ' - ' . $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac), 0, 0, 'L');
$pdf->Cell(35, 6, utf8_decode('Teléfono:'), 0, 0, 'L');
$pdf->Cell(0, 6, utf8_decode($pac_tel), 0, 1, 'L');
$pdf->Cell(35, 6, utf8_decode('Nacimiento:'), 0, 0, 'L');
$pdf->Cell(30, 6, $pac_fecnac ? date('d/m/Y', strtotime($pac_fecnac)) : 'N/A', 0, 0, 'L');
$pdf->Cell(10, 6, utf8_decode('Edad:'), 0, 0, 'L');
$pdf->Cell(15, 6, utf8_decode($edad), 0, 0, 'L');
$pdf->Cell(15, 6, utf8_decode('Género:'), 0, 0, 'L');
$pdf->Cell(20, 6, utf8_decode($pac_sexo), 0, 0, 'L');
$pdf->Cell(20, 6, utf8_decode('Ocupación:'), 0, 0, 'L');
$pdf->Cell(0, 6, utf8_decode($pac_ocup), 0, 1, 'L');
$pdf->Cell(20, 6, utf8_decode('Domicilio:'), 0, 0, 'L');
$pdf->Cell(0, 6, utf8_decode($pac_dir), 0, 1, 'L');
$pdf->Ln(8);

// Pre-anesthetic Evaluation Section
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 8, utf8_decode('Evaluación Preanestésica'), 0, 1, 'C', true);
$pdf->Ln(5);

// General Information (Left Column)
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->SetX(15);
$pdf->Cell(90, 8, utf8_decode('Información General'), 1, 1, 'C', true);
$pdf->SetFont('Arial', '', 10);
$y_left = $pdf->GetY();
$general_fields = [
    'Anestesiólogo' => $preanest['anestesiologo'] ?? 'N/A',
    'Urgencia' => $preanest['urgencia'] ?? 'N/A',
    'Cirujano' => $preanest['cirujano'] ?? 'N/A',
    'Diagnóstico Preoperatorio' => $preanest['diagnostico_preoperatorio'] ?? 'N/A',
    'Cirugía Programada' => $preanest['cirugia_programada'] ?? 'N/A',
    'Padecimiento Actual' => $preanest['padecimiento_actual'] ?? 'N/A',
];
foreach ($general_fields as $label => $value) {
    if ($value !== 'N/A') {
        $pdf->SetX(15);
        $pdf->MultiCell(90, 6, utf8_decode($label . ': ' . $value), 1, 'J');
        $y_left = $pdf->GetY();
    }
}

// Medical History (Right Column)
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->SetXY(110, 109); // Align with left column
$pdf->Cell(90, 8, utf8_decode('Antecedentes Médicos'), 1, 1, 'C', true);
$pdf->SetFont('Arial', '', 10);
$y_right = $pdf->GetY();
$medical_history = [
    'Tabaquismo' => $preanest['tabaquismo'] . ($preanest['tabaquismo_detalle'] ? ': ' . $preanest['tabaquismo_detalle'] : ''),
    'Asma' => $preanest['asma'] . ($preanest['asma_detalle'] ? ': ' . $preanest['asma_detalle'] : ''),
    'Alcoholismo' => $preanest['alcoholismo'] . ($preanest['alcoholismo_detalle'] ? ': ' . $preanest['alcoholismo_detalle'] : ''),
    'Alergias' => $preanest['alergias'] . ($preanest['alergias_detalle'] ? ': ' . $preanest['alergias_detalle'] : ''),
    'Toxicomanías' => $preanest['toxicomanias'] . ($preanest['toxicomanias_detalle'] ? ': ' . $preanest['toxicomanias_detalle'] : ''),
    'Diabetes' => $preanest['diabetes'] . ($preanest['diabetes_detalle'] ? ': ' . $preanest['diabetes_detalle'] : ''),
    'Hepatopatías' => $preanest['hepatopatias'] . ($preanest['hepatopatias_detalle'] ? ': ' . $preanest['hepatopatias_detalle'] : ''),
    'Enfermedades Tiroideas' => $preanest['enf_tiroideas'] . ($preanest['enf_tiroideas_detalle'] ? ': ' . $preanest['enf_tiroideas_detalle'] : ''),
    'Neumopatías' => $preanest['neumopatias'] . ($preanest['neumopatias_detalle'] ? ': ' . $preanest['neumopatias_detalle'] : ''),
    'Hipertensión' => $preanest['hipertension'] . ($preanest['hipertension_detalle'] ? ': ' . $preanest['hipertension_detalle'] : ''),
    'Nefropatías' => $preanest['nefropatias'] . ($preanest['nefropatias_detalle'] ? ': ' . $preanest['nefropatias_detalle'] : ''),
    'Cáncer' => $preanest['cancer'] . ($preanest['cancer_detalle'] ? ': ' . $preanest['cancer_detalle'] : ''),
    'Transfusiones' => $preanest['transfusiones'] . ($preanest['transfusiones_detalle'] ? ': ' . $preanest['transfusiones_detalle'] : ''),
    'Artritis' => $preanest['artritis'] . ($preanest['artritis_detalle'] ? ': ' . $preanest['artritis_detalle'] : ''),
    'Cardiopatías' => $preanest['cardiopatias'] . ($preanest['cardiopatias_detalle'] ? ': ' . $preanest['cardiopatias_detalle'] : ''),
    'Medicamentos Actuales' => $preanest['medicamentos_actuales'] ?? 'N/A',
    'Anestesias Previas' => $preanest['anestesias_previas'] ?? 'N/A',
    'Otros Antecedentes' => $preanest['otros_antecedentes'] ?? 'N/A',
];
foreach ($medical_history as $label => $value) {
    if ($value !== 'No' && $value !== 'N/A') {
        $pdf->SetX(110);
        $pdf->MultiCell(90, 6, utf8_decode($label . ': ' . $value), 1, 'J');
        $y_right = $pdf->GetY();
    }
}
$pdf->SetY(max($y_left, $y_right));
$pdf->Ln(8);

// Vital Signs and Physical Exam (Horizontal Table)
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 8, utf8_decode('Signos Vitales y Exploración Física'), 0, 1, 'C', true);
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$vital_signs = [
    'Peso' => $preanest['peso'] ? $preanest['peso'] . ' kg' : 'N/A',
    'Talla' => $preanest['talla'] ? $preanest['talla'] . ' m' : 'N/A',
    'TA' => ($preanest['ta_sistolica'] && $preanest['ta_diastolica']) ? $preanest['ta_sistolica'] . '/' . $preanest['ta_diastolica'] . ' mmHg' : 'N/A',
    'FC' => $preanest['fc'] ? $preanest['fc'] . ' lpm' : 'N/A',
    'FR' => $preanest['fr'] ? $preanest['fr'] . ' rpm' : 'N/A',
    'Temperatura' => $preanest['temperatura'] ? $preanest['temperatura'] . ' °C' : 'N/A',
    'Estado de Conciencia' => $preanest['edo_conciencia'] ?? 'N/A',
    'Cabeza y Cuello' => $preanest['cabeza_cuello'] ?? 'N/A',
    'Vía Aérea' => $preanest['via_aerea'] ?? 'N/A',
    'Cardiopulmonar' => $preanest['cardiopulmonar'] ?? 'N/A',
    'Abdomen' => $preanest['abdomen'] ?? 'N/A',
    'Columna' => $preanest['columna'] ?? 'N/A',
    'Extremidades' => $preanest['extremidades'] ?? 'N/A',
    'Otros (Exploración)' => $preanest['otros_exploracion'] ?? 'N/A',
];
$filtered_vitals = array_filter($vital_signs, fn($value) => $value !== 'N/A');
$columns = 4; // Number of columns per row
$col_widths = [46, 46, 46, 46]; // Fixed widths for each column
$row_fields = array_chunk(array_keys($filtered_vitals), $columns);
$row_values = array_chunk(array_values($filtered_vitals), $columns);
$pdf->SetFont('Arial', 'B', 10);
foreach ($row_fields as $index => $fields_row) {
    $pdf->SetX(15);
    foreach ($fields_row as $i => $label) {
        $pdf->Cell($col_widths[$i], 8, utf8_decode($label), 1, 0, 'L', true);
    }
    $pdf->Ln();
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetX(15);
    foreach ($row_values[$index] as $i => $value) {
        $pdf->Cell($col_widths[$i], 6, utf8_decode($value), 1, 0, 'L');
    }
    $pdf->Ln();
}
$pdf->Ln(8);

// Force page break for second page
$pdf->AddPage();

// Laboratory and Studies (Horizontal Table)
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 8, utf8_decode('Estudios de Laboratorio y Gabinete'), 0, 1, 'C', true);
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$lab_fields = [
    'Hemoglobina (Hb)' => $preanest['hb'] ? $preanest['hb'] . ' g/dL' : 'N/A',
    'Hematocrito (Hto)' => $preanest['hto'] ? $preanest['hto'] . ' %' : 'N/A',
    'Tiempo de Prot. (TP)' => $preanest['tp'] ? $preanest['tp'] . ' seg' : 'N/A',
    'Tiempo Parcial de T. (TPT)' => $preanest['tpt'] ? $preanest['tpt'] . ' seg' : 'N/A',
    'Tipo y Rh' => $preanest['tipo_rh'] ?? 'N/A',
    'Glucosa' => $preanest['glucosa'] ? $preanest['glucosa'] . ' mg/dL' : 'N/A',
    'Urea' => $preanest['urea'] ? $preanest['urea'] . ' mg/dL' : 'N/A',
    'Creatinina' => $preanest['creatinina'] ? $preanest['creatinina'] . ' mg/dL' : 'N/A',
    'Otros (Laboratorio)' => $preanest['otros_laboratorio'] ?? 'N/A',
    'Gabinete' => $preanest['gabinete'] ?? 'N/A',
];
$filtered_labs = array_filter($lab_fields, fn($value) => $value !== 'N/A');
$row_fields = array_chunk(array_keys($filtered_labs), $columns);
$row_values = array_chunk(array_values($filtered_labs), $columns);
$pdf->SetFont('Arial', 'B', 10);
foreach ($row_fields as $index => $fields_row) {
    $pdf->SetX(15);
    foreach ($fields_row as $i => $label) {
        $pdf->Cell($col_widths[$i], 8, utf8_decode($label), 1, 0, 'L', true);
    }
    $pdf->Ln();
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetX(15);
    foreach ($row_values[$index] as $i => $value) {
        $pdf->Cell($col_widths[$i], 6, utf8_decode($value), 1, 0, 'L');
    }
    $pdf->Ln();
}
$pdf->Ln(8);

// Anesthetic Plan (Full Width)
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 8, utf8_decode('Plan Anestésico'), 0, 1, 'C', true);
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$plan_fields = [
    'Clasificación ASA' => $preanest['asa'] ?? 'N/A',
    'Plan Anestésico' => $preanest['plan_anestesico'] ?? 'N/A',
    'Indicaciones Preanestésicas' => $preanest['indicaciones_preanestesicas'] ?? 'N/A',
];
foreach ($plan_fields as $label => $value) {
    if ($value !== 'N/A') {
        $pdf->MultiCell(0, 6, utf8_decode($label . ': ' . $value), 1, 'J');
    }
}
$pdf->Ln(8);

// Signature (Full Width)
$pdf->SetY(-50);
if (!empty($firma) && file_exists('../../imgfirma/' . $firma)) {
    $imgWidth = 40;
    $imgX = ($pdf->GetPageWidth() - $imgWidth) / 2;
    $pdf->Image('../../imgfirma/' . $firma, $imgX, $pdf->GetY(), $imgWidth);
    $pdf->Ln(25);
}
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 6, utf8_decode(trim($pre_med . ' ' . $app_med . ' ' . $apm_med . ' ' . $nom_med)), 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, utf8_decode($cargp), 0, 1, 'C');
$pdf->Cell(0, 6, utf8_decode('Céd. Prof. ' . $ced_p), 0, 1, 'C');

// Output PDF
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="evaluacion_preanestesica.pdf"');
$pdf->Output('I', 'evaluacion_preanestesica.pdf');
exit();
?>