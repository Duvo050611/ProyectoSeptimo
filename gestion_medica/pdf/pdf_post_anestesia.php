<?php
session_start();
include "../../conexionbd.php";
require '../../fpdf/fpdf.php';

if (!isset($_SESSION['login']) || !isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

$id_nota = isset($_GET['id_nota']) ? (int)$_GET['id_nota'] : 0;
$id_exp = isset($_GET['id_exp']) ? (int)$_GET['id_exp'] : 0;
$id_atencion = isset($_GET['id_atencion']) ? (int)$_GET['id_atencion'] : 0;

if ($id_nota <= 0 || $id_exp <= 0 || $id_atencion <= 0) {
    die("ID de nota post anestésica, expediente o atención no válido.");
}

// Fetch post-anesthesia note data
$sql_postanest = "SELECT * FROM nota_post_anestesia_oftalmologia WHERE id_nota = ? AND id_atencion = ? AND Id_exp = ?";
$stmt_postanest = $conexion->prepare($sql_postanest);
if (!$stmt_postanest) {
    die("Error preparando consulta de nota post anestésica: " . $conexion->error);
}
$stmt_postanest->bind_param("iii", $id_nota, $id_atencion, $id_exp);
$stmt_postanest->execute();
$result_postanest = $stmt_postanest->get_result();
if (!$postanest = $result_postanest->fetch_assoc()) {
    die("Nota post anestésica no encontrada.");
}
$stmt_postanest->close();

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
$stmt_doc->bind_param("i", $postanest['id_usua']);
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
        $this->Cell(0, 10, utf8_decode('NOTA POST ANESTÉSICA OFTALMOLÓGICA'), 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 6, utf8_decode('Fecha: ') . date('d/m/Y H:i'), 0, 1, 'R');
        $this->Ln(3);
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
$pdf->Ln(3);

// Post-Anesthesia Note Section
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 8, utf8_decode('Nota Post Anestésica Oftalmológica'), 0, 1, 'C', true);
$pdf->Ln(5);

// General Information (Full Width)
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 8, utf8_decode('Información General'), 1, 1, 'C', true);
$pdf->SetFont('Arial', '', 10);
$general_fields = [
    'Ojo Operado' => $postanest['ojo_operado'] ?? 'N/A',
    'Técnica Anestésica' => $postanest['tecnica_anestesica'] ?? 'N/A',
    'Sangre y Líquidos' => $postanest['sangre_liquidos'] ?? 'N/A',
    'Incidentes' => $postanest['incidentes'] . ($postanest['detalle_incidentes'] ? ': ' . $postanest['detalle_incidentes'] : ''),
    'Plan de Manejo' => $postanest['plan_manejo'] ?? 'N/A',
];
foreach ($general_fields as $label => $value) {
    if ($value !== 'No' && $value !== 'N/A') {
        $pdf->MultiCell(0, 6, utf8_decode($label . ': ' . $value), 1, 'J');
    }
}
$pdf->Ln(3);

// Vital Signs (Two Columns)
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 8, utf8_decode('Signos Vitales'), 0, 1, 'C', true);
$pdf->Ln(5);

// Vital Signs at Admission (Left Column)
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->SetX(15);
$pdf->Cell(90, 8, utf8_decode('Signos Vitales al Ingreso'), 1, 1, 'C', true);
$pdf->SetFont('Arial', '', 10);
$y_left = $pdf->GetY();
$vital_signs_ingreso = [
    'TA' => $postanest['ta_ingreso'] ?? 'N/A',
    'FC' => $postanest['fc_ingreso'] ? $postanest['fc_ingreso'] . ' lpm' : 'N/A',
    'FR' => $postanest['fr_ingreso'] ? $postanest['fr_ingreso'] . ' rpm' : 'N/A',
    'Saturación O2' => $postanest['sato2_ingreso'] ? $postanest['sato2_ingreso'] . ' %' : 'N/A',
    'PIO' => $postanest['pio_ingreso'] ?? 'N/A',
];
foreach ($vital_signs_ingreso as $label => $value) {
    if ($value !== 'N/A') {
        $pdf->SetX(15);
        $pdf->MultiCell(90, 6, utf8_decode($label . ': ' . $value), 1, 'J');
        $y_left = $pdf->GetY();
    }
}

// Vital Signs at Discharge (Right Column)
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->SetXY(110, 150); // Align with left column
$pdf->Cell(90, 8, utf8_decode('Signos Vitales al Alta'), 1, 1, 'C', true);
$pdf->SetFont('Arial', '', 10);
$y_right = $pdf->GetY();
$vital_signs_alta = [
    'TA' => $postanest['ta_alta'] ?? 'N/A',
    'FC' => $postanest['fc_alta'] ? $postanest['fc_alta'] . ' lpm' : 'N/A',
    'FR' => $postanest['fr_alta'] ? $postanest['fr_alta'] . ' rpm' : 'N/A',
    'Saturación O2' => $postanest['sato2_alta'] ? $postanest['sato2_alta'] . ' %' : 'N/A',
    'PIO' => $postanest['pio_alta'] ?? 'N/A',
];
foreach ($vital_signs_alta as $label => $value) {
    if ($value !== 'N/A') {
        $pdf->SetX(110);
        $pdf->MultiCell(90, 6, utf8_decode($label . ': ' . $value), 1, 'J');
        $y_right = $pdf->GetY();
    }
}
$pdf->SetY(max($y_left, $y_right));
$pdf->Ln(3);

// Aldrete Score (Full Width)
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 8, utf8_decode('Escala de Aldrete'), 0, 1, 'C', true);
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->SetX(15);
$pdf->Cell(30, 8, '', 1, 0, 'C', true);
$pdf->Cell(39, 8, utf8_decode('Ingreso'), 1, 0, 'C', true);
$pdf->Cell(39, 8, utf8_decode('Hora Ingreso'), 1, 0, 'C', true);
$pdf->Cell(39, 8, utf8_decode('Alta'), 1, 0, 'C', true);
$pdf->Cell(39, 8, utf8_decode('Hora Alta'), 1, 1, 'C', true);
$pdf->SetFont('Arial', '', 10);
$aldrete_fields = [
    'Actividad' => [
        'Ingreso' => $postanest['actividad_ingreso'] ?? 'N/A',
        'Hora Ingreso' => $postanest['actividad_hora_ingreso'] ? date('H:i', strtotime($postanest['actividad_hora_ingreso'])) : 'N/A',
        'Alta' => $postanest['actividad_alta'] ?? 'N/A',
        'Hora Alta' => $postanest['actividad_hora_alta'] ? date('H:i', strtotime($postanest['actividad_hora_alta'])) : 'N/A',
    ],
    'Respiración' => [
        'Ingreso' => $postanest['respiracion_ingreso'] ?? 'N/A',
        'Hora Ingreso' => $postanest['respiracion_hora_ingreso'] ? date('H:i', strtotime($postanest['respiracion_hora_ingreso'])) : 'N/A',
        'Alta' => $postanest['respiracion_alta'] ?? 'N/A',
        'Hora Alta' => $postanest['respiracion_hora_alta'] ? date('H:i', strtotime($postanest['respiracion_hora_alta'])) : 'N/A',
    ],
    'Circulación' => [
        'Ingreso' => $postanest['circulacion_ingreso'] ?? 'N/A',
        'Hora Ingreso' => $postanest['circulacion_hora_ingreso'] ? date('H:i', strtotime($postanest['circulacion_hora_ingreso'])) : 'N/A',
        'Alta' => $postanest['circulacion_alta'] ?? 'N/A',
        'Hora Alta' => $postanest['circulacion_hora_alta'] ? date('H:i', strtotime($postanest['circulacion_hora_alta'])) : 'N/A',
    ],
    'Conciencia' => [
        'Ingreso' => $postanest['conciencia_ingreso'] ?? 'N/A',
        'Hora Ingreso' => $postanest['conciencia_hora_ingreso'] ? date('H:i', strtotime($postanest['conciencia_hora_ingreso'])) : 'N/A',
        'Alta' => $postanest['conciencia_alta'] ?? 'N/A',
        'Hora Alta' => $postanest['conciencia_hora_alta'] ? date('H:i', strtotime($postanest['conciencia_hora_alta'])) : 'N/A',
    ],
    'Saturación' => [
        'Ingreso' => $postanest['saturacion_ingreso'] ?? 'N/A',
        'Hora Ingreso' => $postanest['saturacion_hora_ingreso'] ? date('H:i', strtotime($postanest['saturacion_hora_ingreso'])) : 'N/A',
        'Alta' => $postanest['saturacion_alta'] ?? 'N/A',
        'Hora Alta' => $postanest['saturacion_hora_alta'] ? date('H:i', strtotime($postanest['saturacion_hora_alta'])) : 'N/A',
    ],
];
foreach ($aldrete_fields as $label => $values) {
    if ($values['Ingreso'] !== 'N/A' || $values['Alta'] !== 'N/A') {
        $pdf->SetX(15);
        $pdf->Cell(30, 6, utf8_decode($label), 1, 0, 'L');
        $pdf->Cell(39, 6, utf8_decode($values['Ingreso']), 1, 0, 'C');
        $pdf->Cell(39, 6, utf8_decode($values['Hora Ingreso']), 1, 0, 'C');
        $pdf->Cell(39, 6, utf8_decode($values['Alta']), 1, 0, 'C');
        $pdf->Cell(39, 6, utf8_decode($values['Hora Alta']), 1, 1, 'C');
    }
}
$pdf->SetX(15);
$pdf->Cell(30, 6, utf8_decode('Total'), 1, 0, 'L');
$pdf->Cell(39, 6, utf8_decode($postanest['total_ingreso'] ?? 'N/A'), 1, 0, 'C');
$pdf->Cell(39, 6, '', 1, 0, 'C');
$pdf->Cell(39, 6, utf8_decode($postanest['total_alta'] ?? 'N/A'), 1, 0, 'C');
$pdf->Cell(39, 6, '', 1, 1, 'C');
$pdf->Ln(8);

// Timestamps (Horizontal Table)
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 8, utf8_decode('Tiempos'), 0, 1, 'C', true);
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->SetX(15);
$pdf->Cell(92, 8, utf8_decode('Hora de Ingreso'), 1, 0, 'C', true);
$pdf->Cell(92, 8, utf8_decode('Hora de Alta'), 1, 1, 'C', true);
$pdf->SetFont('Arial', '', 10);
$pdf->SetX(15);
$pdf->Cell(92, 6, $postanest['hora_ingreso'] ? date('H:i', strtotime($postanest['hora_ingreso'])) : 'N/A', 1, 0, 'C');
$pdf->Cell(92, 6, $postanest['hora_alta'] ? date('H:i', strtotime($postanest['hora_alta'])) : 'N/A', 1, 1, 'C');
$pdf->Ln(3);

// Final Evaluation (Full Width)
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 8, utf8_decode('Evaluación Final'), 0, 1, 'C', true);
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$final_fields = [
    'Control de Dolor' => $postanest['control_dolor'] ?? 'N/A',
    'Horas Post Anestesia' => $postanest['horas_post_anestesia'] ? $postanest['horas_post_anestesia'] . ' horas' : 'N/A',
    'TA Final' => $postanest['ta_final'] ?? 'N/A',
    'Pulso Final' => $postanest['pulso_final'] ? $postanest['pulso_final'] . ' lpm' : 'N/A',
    'Respiración Final' => $postanest['resp_final'] ? $postanest['resp_final'] . ' rpm' : 'N/A',
    'Estado de Conciencia' => $postanest['estado_conciencia'] ?? 'N/A',
    'Náuseas' => $postanest['nauseas'] ?: 'No',
    'Vómito' => $postanest['vomito'] ?: 'No',
    'Cefalea' => $postanest['cefalea'] ?: 'No',
    'Diuresis' => $postanest['diuresis'] ?: 'No',
    'Dolor Ocular' => $postanest['dolor_ocular'] ?: 'No',
    'Visión Borrosa' => $postanest['vision_borrosa'] ?: 'No',
    'Evolución Final' => $postanest['evolucion_final'] ?? 'N/A',
    'Deambulación' => $postanest['deambulacion'] ?? 'N/A',
    'Indicaciones al Alta' => $postanest['indicaciones_alta'] ?? 'N/A',
];
foreach ($final_fields as $label => $value) {
    if ($value !== 'No' && $value !== 'N/A') {
        $pdf->MultiCell(0, 6, utf8_decode($label . ': ' . $value), 1, 'J');
    }
}
$pdf->Ln(8);

// Discharge Evolution (Full Width)
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 8, utf8_decode('Evolución al Alta'), 0, 1, 'C', true);
$pdf->Ln(5);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, 6, utf8_decode('Evolución: ' . ($postanest['evolucion_alta'] ?? 'N/A')), 1, 'J');
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
header('Content-Disposition: inline; filename="nota_post_anestesia.pdf"');
$pdf->Output('I', 'nota_post_anestesia.pdf');
exit();
?>