<?php
session_start();
include "../../conexionbd.php";
require '../../fpdf/fpdf.php';

if (!isset($_SESSION['login']) || !isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

$id_registro_anestesico = isset($_GET['id_registro_anestesico']) ? (int)$_GET['id_registro_anestesico'] : 0;
$id_exp = isset($_GET['id_exp']) ? (int)$_GET['id_exp'] : 0;
$id_atencion = isset($_GET['id_atencion']) ? (int)$_GET['id_atencion'] : 0;

if ($id_registro_anestesico <= 0 || $id_exp <= 0 || $id_atencion <= 0) {
    die("ID de registro anestésico, expediente o atención no válido.");
}

// Fetch anesthetic record data
$sql_anest = "SELECT * FROM registro_anestesico WHERE id_registro_anestesico = ? AND id_atencion = ? AND Id_exp = ?";
$stmt_anest = $conexion->prepare($sql_anest);
if (!$stmt_anest) {
    die("Error preparando consulta de registro anestésico: " . $conexion->error);
}
$stmt_anest->bind_param("iii", $id_registro_anestesico, $id_atencion, $id_exp);
$stmt_anest->execute();
$result_anest = $stmt_anest->get_result();
if (!$anest = $result_anest->fetch_assoc()) {
    die("Registro anestésico no encontrado.");
}
$stmt_anest->close();

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

// Prepare statement for fetching user data
$sql_doc = "SELECT pre, papell, sapell, nombre, firma, cedp, cargp FROM reg_usuarios WHERE id_usua = ?";
$stmt_doc = $conexion->prepare($sql_doc);
if (!$stmt_doc) {
    die("Error preparando consulta de usuario: " . $conexion->error);
}

// Fetch anesthesiologist data
$stmt_doc->bind_param("i", $anest['anestesiologo_id']);
$stmt_doc->execute();
$result_doc = $stmt_doc->get_result();
$anestesiologo = "Anestesiólogo no asignado";
$pre_anest = '';
$app_anest = '';
$apm_anest = '';
$nom_anest = '';
$firma_anest = '';
$ced_anest = '';
$carg_anest = '';
if ($row_doc = $result_doc->fetch_assoc()) {
    $pre_anest = $row_doc['pre'] ?? '';
    $app_anest = $row_doc['papell'] ?? '';
    $apm_anest = $row_doc['sapell'] ?? '';
    $nom_anest = $row_doc['nombre'] ?? '';
    $firma_anest = $row_doc['firma'] ?? '';
    $ced_anest = $row_doc['cedp'] ?? '';
    $carg_anest = $row_doc['cargp'] ?? '';
    $anestesiologo = ($pre_anest ? $pre_anest . ". " : "") . $app_anest . " " . $apm_anest . " " . $nom_anest;
}

// Fetch surgeon data
$stmt_doc->bind_param("i", $anest['cirujano_id']);
$stmt_doc->execute();
$result_doc = $stmt_doc->get_result();
$cirujano = "Cirujano no asignado";
if ($row_doc = $result_doc->fetch_assoc()) {
    $cirujano = ($row_doc['pre'] ? $row_doc['pre'] . ". " : "") . ($row_doc['papell'] ?? '') . " " . ($row_doc['sapell'] ?? '') . " " . ($row_doc['nombre'] ?? '');
}

// Fetch assistants data (if any)
$ayudantes = "No especificado";
if (!empty($anest['ayudantes_ids'])) {
    $ayudantes_ids = explode(',', $anest['ayudantes_ids']);
    $ayudantes_list = [];
    foreach ($ayudantes_ids as $id) {
        $stmt_doc->bind_param("i", $id);
        $stmt_doc->execute();
        $result_doc = $stmt_doc->get_result();
        if ($row_doc = $result_doc->fetch_assoc()) {
            $ayudantes_list[] = ($row_doc['pre'] ? $row_doc['pre'] . ". " : "") . ($row_doc['papell'] ?? '') . " " . ($row_doc['sapell'] ?? '') . " " . ($row_doc['nombre'] ?? '');
        }
    }
    $ayudantes = !empty($ayudantes_list) ? implode(', ', $ayudantes_list) : "No especificado";
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
            $this->Image("../../configuracion/admin/img2/{$f['img_ipdf']}", 7, 11, 40, 25);
            $this->Image("../../configuracion/admin/img3/{$f['img_cpdf']}", 58, 15, 109, 24);
            $this->Image("../../configuracion/admin/img4/{$f['img_dpdf']}", 168, 16, 38, 14);
        }
        $this->SetY(40);
        $this->Cell(0, 10, utf8_decode('REGISTRO ANESTÉSICO'), 0, 1, 'C');
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
        $this->Cell(0, 10, utf8_decode('ANEST-000'), 0, 1, 'R');
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

// Anesthetic Record Section (Two Columns, Part 1)
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 8, utf8_decode('Registro Anestésico'), 0, 1, 'C', true);
$pdf->Ln(5);

// Define fields
$fields = [
    'Tipo de Anestesia' => $anest['tipo_anestesia'] ?? 'N/A',
    'Diagnóstico Preoperatorio' => $anest['diagnostico_preoperatorio'] ?? 'N/A',
    'Cirugía Programada' => $anest['cirugia_programada'] ?? 'N/A',
    'Diagnóstico Postoperatorio' => $anest['diagnostico_postoperatorio'] ?? 'N/A',
    'Cirugía Realizada' => $anest['cirugia_realizada'] ?? 'N/A',
    'Anestesiólogo' => $anestesiologo,
    'Cirujano' => $cirujano,
    'Ayudantes' => $ayudantes,
    'T.A. (Presión Arterial)' => $anest['ta'] ?? 'N/A',
    'F.C. (Frecuencia Cardíaca)' => $anest['fc'] ? $anest['fc'] . ' lpm' : 'N/A',
    'F.R. (Frecuencia Resp.)' => $anest['fr'] ? $anest['fr'] . ' rpm' : 'N/A',
    'Temperatura' => $anest['temp'] ? $anest['temp'] . ' °C' : 'N/A',
    'SpO2' => $anest['spo2'] ? $anest['spo2'] . '%' : 'N/A',
    'Otros Signos' => $anest['otros_signos'] ?? 'N/A',
    'Hoja/Gráfico' => $anest['hoja_grafico'] ?? 'N/A',
    'Revisión de Equipo' => $anest['revision_equipo'] ?? 'N/A',
    'O2 Hora' => $anest['o2_hora'] ? date('H:i', strtotime($anest['o2_hora'])) : 'N/A',
    'Agente Inhalado' => $anest['agente_inhalado'] ?? 'N/A',
    'Fármacos y Dosis Total' => $anest['farmacos_dosis_total'] ?? 'N/A',
    'ECG Continua' => $anest['ecg_continua'] ? 'Sí' : 'No',
    'Pulsioximetría' => $anest['pulsoximetria'] ? 'Sí' : 'No',
    'Capnografía' => $anest['capnografia'] ? 'Sí' : 'No',
    'Intubación' => $anest['intubacion'] ?? 'N/A',
    'Incidentes' => $anest['incidentes'] ?? 'N/A',
    'Cánula' => $anest['canula'] ?? 'N/A',
    'Dificultad Técnica' => $anest['dificultad_tecnica'] ?? 'N/A',
    'Ventilación' => $anest['ventilacion'] ?? 'N/A',
    'Llega a Quirófano' => $anest['llega_quirofano'] ? date('d/m/Y H:i', strtotime($anest['llega_quirofano'])) : 'N/A',
    'Inicia Anestesia' => $anest['inicia_anestesia'] ? date('d/m/Y H:i', strtotime($anest['inicia_anestesia'])) : 'N/A',
    'Inicia Cirugía' => $anest['inicia_cirugia'] ? date('d/m/Y H:i', strtotime($anest['inicia_cirugia'])) : 'N/A',
    'Termina Cirugía' => $anest['termina_cirugia'] ? date('d/m/Y H:i', strtotime($anest['termina_cirugia'])) : 'N/A',
    'Termina Anestesia' => $anest['termina_anestesia'] ? date('d/m/Y H:i', strtotime($anest['termina_anestesia'])) : 'N/A',
    'Pasa a Recuperación' => $anest['pasa_recuperacion'] ? date('d/m/Y H:i', strtotime($anest['pasa_recuperacion'])) : 'N/A',
    'Tiempo Anestésico' => $anest['tiempo_anestesico'] ? $anest['tiempo_anestesico'] . ' min' : 'N/A',
];

// Split fields into two parts
$fields_left = array_slice($fields, 0, ceil(count($fields) / 2));
$fields_right = array_slice($fields, ceil(count($fields) / 2));

// Left Column (First Part)
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->SetX(15);
$pdf->Cell(45, 8, 'Campo', 1, 0, 'C', true);
$pdf->Cell(45, 8, 'Valor', 1, 1, 'C', true);
$pdf->SetFont('Arial', '', 10);
$y_left = $pdf->GetY();
foreach ($fields_left as $label => $value) {
    if ($value !== 'N/A') {
        $pdf->SetX(15);
        $pdf->Cell(45, 6, utf8_decode($label), 1, 0, 'L');
        $pdf->MultiCell(45, 6, utf8_decode($value), 1, 'L');
        $y_left = $pdf->GetY();
    }
}

// Right Column (First Part)
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->SetXY(110, 114); // Start at same Y as left column
$pdf->Cell(45, 8, 'Campo', 1, 0, 'C', true);
$pdf->Cell(45, 8, 'Valor', 1, 1, 'C', true);
$pdf->SetFont('Arial', '', 10);
$y_right = $pdf->GetY();
foreach ($fields_right as $label => $value) {
    if ($value !== 'N/A') {
        $pdf->SetX(110);
        $pdf->Cell(45, 6, utf8_decode($label), 1, 0, 'L');
        $pdf->MultiCell(45, 6, utf8_decode($value), 1, 'L');
        $y_right = $pdf->GetY();
    }
}
$pdf->SetY(max($y_left, $y_right));
$pdf->Ln(8);

// Force page break for second page
$pdf->AddPage();

/* // Anesthetic Record Section (Two Columns, Part 2)
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 8, utf8_decode('Registro Anestésico (Continuación)'), 0, 1, 'C', true);
$pdf->Ln(5); */

/* // Left Column (Second Part)
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->SetX(15);
$pdf->Cell(45, 8, 'Campo', 1, 0, 'C', true);
$pdf->Cell(45, 8, 'Valor', 1, 1, 'C', true);
$pdf->SetFont('Arial', '', 10); */
/* $y_left = $pdf->GetY();
foreach ($fields_left as $label => $value) {
    if ($value !== 'N/A' && !in_array($label, array_keys($fields_left))) { // Placeholder for additional fields if needed
        $pdf->SetX(15);
        $pdf->Cell(45, 6, utf8_decode($label), 1, 0, 'L');
        $pdf->MultiCell(45, 6, utf8_decode($value), 1, 'L');
        $y_left = $pdf->GetY();
    }
}

// Right Column (Second Part)
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->SetXY(110, 65);
$pdf->Cell(45, 8, 'Campo', 1, 0, 'C', true);
$pdf->Cell(45, 8, 'Valor', 1, 1, 'C', true);
$pdf->SetFont('Arial', '', 10);
$y_right = $pdf->GetY();
foreach ($fields_right as $label => $value) {
    if ($value !== 'N/A' && !in_array($label, array_keys($fields_right))) { // Placeholder for additional fields if needed
        $pdf->SetX(110);
        $pdf->Cell(45, 6, utf8_decode($label), 1, 0, 'L');
        $pdf->MultiCell(45, 6, utf8_decode($value), 1, 'L');
        $y_right = $pdf->GetY();
    }
}
$pdf->SetY(max($y_left, $y_right));
$pdf->Ln(8); */

// Fluid Balance Section (Two Columns)
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 8, utf8_decode('Balance de Líquidos'), 0, 1, 'C', true);
$pdf->Ln(5);

// Left Column (Ingresos)
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->SetX(15);
$pdf->Cell(45, 8, 'Ingresos', 1, 0, 'C', true);
$pdf->Cell(45, 8, 'Valor (mL)', 1, 1, 'C', true);
$pdf->SetFont('Arial', '', 10);
$y_left = $pdf->GetY();
$ingresos = [
    'Hartmann' => $anest['hartmann'] ?? 'N/A',
    'Glucosa' => $anest['glucosa'] ?? 'N/A',
    'NaCl' => $anest['nacl'] ?? 'N/A',
    'Total Ingresos' => $anest['total_ingresos'] ?? 'N/A',
];
foreach ($ingresos as $label => $value) {
    if ($value !== 'N/A') {
        $pdf->SetX(15);
        $pdf->Cell(45, 6, utf8_decode($label), 1, 0, 'L');
        $pdf->Cell(45, 6, $value, 1, 1, 'C');
        $y_left = $pdf->GetY();
    }
}

// Right Column (Egresos and Balance)
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->SetXY(110, $pdf->GetY() - (count(array_filter($ingresos, fn($v) => $v !== 'N/A')) * 6 + 8));
$pdf->Cell(45, 8, 'Egresos', 1, 0, 'C', true);
$pdf->Cell(45, 8, 'Valor (mL)', 1, 1, 'C', true);
$pdf->SetFont('Arial', '', 10);
$y_right = $pdf->GetY();
$egresos = [
    'Diuresis' => $anest['diuresis'] ?? 'N/A',
    'Sangrado' => $anest['sangrado'] ?? 'N/A',
    'Pérdidas Insensibles' => $anest['perdidas_insensibles'] ?? 'N/A',
    'Total Egresos' => $anest['total_egresos'] ?? 'N/A',
    'Balance' => $anest['balance'] ?? 'N/A',
];
foreach ($egresos as $label => $value) {
    if ($value !== 'N/A') {
        $pdf->SetX(110);
        $pdf->Cell(45, 6, utf8_decode($label), 1, 0, 'L');
        $pdf->Cell(45, 6, $value, 1, 1, 'C');
        $y_right = $pdf->GetY();
    }
}
$pdf->SetY(max($y_left, $y_right));
$pdf->Ln(8);

// Aldrete and Regional Anesthesia Section (Two Columns)
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 8, utf8_decode('Puntuación Aldrete y Anestesia Regional'), 0, 1, 'C', true);
$pdf->Ln(5);

// Left Column (Aldrete)
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->SetX(15);
$pdf->Cell(45, 8, 'Criterio', 1, 0, 'C', true);
$pdf->Cell(45, 8, utf8_decode('Puntuación'), 1, 1, 'C', true);
$pdf->SetFont('Arial', '', 10);
$y_left = $pdf->GetY();
$aldrete = [
    'Actividad' => $anest['aldrete_actividad'] ?? 'N/A',
    'Respiración' => $anest['aldrete_respiracion'] ?? 'N/A',
    'Circulación' => $anest['aldrete_circulacion'] ?? 'N/A',
    'Conciencia' => $anest['aldrete_conciencia'] ?? 'N/A',
    'Saturación' => $anest['aldrete_saturacion'] ?? 'N/A',
    'Total' => $anest['aldrete_total'] ?? 'N/A',
];
foreach ($aldrete as $label => $value) {
    if ($value !== 'N/A') {
        $pdf->SetX(15);
        $pdf->Cell(45, 6, utf8_decode($label), 1, 0, 'L');
        $pdf->Cell(45, 6, $value, 1, 1, 'C');
        $y_left = $pdf->GetY();
    }
}

// Right Column (Regional Anesthesia)
if ($anest['anestesia_regional_tipo']) {
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetFillColor(245, 245, 245);
    $pdf->SetXY(110, $pdf->GetY() - (count(array_filter($aldrete, fn($v) => $v !== 'N/A')) * 6 + 8));
    $pdf->Cell(45, 8, 'Campo', 1, 0, 'C', true);
    $pdf->Cell(45, 8, 'Valor', 1, 1, 'C', true);
    $pdf->SetFont('Arial', '', 10);
    $y_right = $pdf->GetY();
    $regional = [
        'Tipo de Anestesia Regional' => $anest['anestesia_regional_tipo'] ?? 'N/A',
        'Aguja' => $anest['aguja'] ?? 'N/A',
        'Nivel de Punción' => $anest['nivel_puncion'] ?? 'N/A',
        'Catéter' => $anest['cateter'] ?? 'N/A',
        'Agentes Administrados' => $anest['agentes_administrados'] ?? 'N/A',
    ];
    foreach ($regional as $label => $value) {
        if ($value !== 'N/A') {
            $pdf->SetX(110);
            $pdf->Cell(45, 6, utf8_decode($label), 1, 0, 'L');
            $pdf->MultiCell(45, 6, utf8_decode($value), 1, 'L');
            $y_right = $pdf->GetY();
        }
    }
} else {
    $y_right = $y_left;
}
$pdf->SetY(max($y_left, $y_right));
$pdf->Ln(8);

// Signature Section (Full Width)
$pdf->SetY(-50);
if (!empty($firma_anest) && file_exists('../../imgfirma/' . $firma_anest)) {
    $imgWidth = 40;
    $imgX = ($pdf->GetPageWidth() - $imgWidth) / 2;
    $pdf->Image('../../imgfirma/' . $firma_anest, $imgX, $pdf->GetY(), $imgWidth);
    $pdf->Ln(25);
}
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 6, utf8_decode(trim($pre_anest . ' ' . $app_anest . ' ' . $apm_anest . ' ' . $nom_anest)), 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, utf8_decode($carg_anest), 0, 1, 'C');
$pdf->Cell(0, 6, utf8_decode('Céd. Prof. ' . $ced_anest), 0, 1, 'C');

// Output PDF
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="registro_anestesico.pdf"');
$pdf->Output('I', 'registro_anestesico.pdf');
exit();
?>