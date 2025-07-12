<?php
session_start();
include "../../conexionbd.php";
require '../../fpdf/fpdf.php';

if (!isset($_SESSION['login']) || !isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

$id_diagnostico = isset($_GET['id_diagnostico']) ? (int)$_GET['id_diagnostico'] : 0;
$id_exp = isset($_GET['id_exp']) ? (int)$_GET['id_exp'] : 0;
$id_atencion = isset($_GET['id_atencion']) ? (int)$_GET['id_atencion'] : 0;

if ($id_diagnostico <= 0 || $id_exp <= 0 || $id_atencion <= 0) {
    die("ID de diagnóstico, expediente o atención no válido.");
}

// Fetch diagnostic data
$sql_diag = "SELECT * FROM ocular_diagnostico WHERE id_diagnostico = ? AND id_atencion = ? AND Id_exp = ?";
$stmt_diag = $conexion->prepare($sql_diag);
if (!$stmt_diag) {
    die("Error preparando consulta de diagnóstico: " . $conexion->error);
}
$stmt_diag->bind_param("iii", $id_diagnostico, $id_atencion, $id_exp);
$stmt_diag->execute();
$result_diag = $stmt_diag->get_result();
if (!$diag = $result_diag->fetch_assoc()) {
    die("Diagnóstico no encontrado.");
}
$stmt_diag->close();

// Fetch previous diagnostic data
$sql_prev_diag = "SELECT diagnostico_principal_derecho, diagnostico_principal_izquierdo, codigo_cie_derecho, codigo_cie_izquierdo 
                  FROM ocular_diagnostico 
                  WHERE Id_exp = ? AND id_atencion = ? AND id_diagnostico < ? 
                  ORDER BY id_diagnostico DESC LIMIT 1";
$stmt_prev_diag = $conexion->prepare($sql_prev_diag);
$stmt_prev_diag->bind_param("iii", $id_exp, $id_atencion, $id_diagnostico);
$stmt_prev_diag->execute();
$result_prev_diag = $stmt_prev_diag->get_result();
$prev_diag = $result_prev_diag->fetch_assoc();
$prev_diag_text = 'N/A';
if ($prev_diag) {
    $details = [];
    if ($prev_diag['diagnostico_principal_derecho']) {
        $details[] = "Ojo Derecho: {$prev_diag['diagnostico_principal_derecho']} (CIE-10: {$prev_diag['codigo_cie_derecho']})";
    }
    if ($prev_diag['diagnostico_principal_izquierdo']) {
        $details[] = "Ojo Izquierdo: {$prev_diag['diagnostico_principal_izquierdo']} (CIE-10: {$prev_diag['codigo_cie_izquierdo']})";
    }
    $prev_diag_text = implode('; ', $details) ?: 'Sin detalles relevantes';
}
$stmt_prev_diag->close();

// Fetch patient data
$sql_pac = "SELECT p.papell, p.sapell, p.nom_pac, p.fecnac, p.folio, p.sexo, p.tel, p.ocup, p.dir, di.fecha, di.tipo_a 
            FROM paciente p 
            JOIN dat_ingreso di ON p.Id_exp = di.Id_exp 
            WHERE di.id_atencion = ?";
$stmt_pac = $conexion->prepare($sql_pac);
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

// Fetch doctor data
$sql_doc = "SELECT pre, papell, sapell, nombre, firma, cedp, cargp FROM reg_usuarios WHERE id_usua = ?";
$stmt_doc = $conexion->prepare($sql_doc);
$stmt_doc->bind_param("i", $diag['id_usua']);
$stmt_doc->execute();
$result_doc = $stmt_doc->get_result();
$medico = "Médico no asignado";
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
        include "../../conexionbd.php";
        $resultado = $conexion->query("SELECT * FROM img_sistema ORDER BY id_simg DESC LIMIT 1") or die($conexion->error);
        while ($f = mysqli_fetch_assoc($resultado)) {
            $this->Image("../../configuracion/admin/img2/{$f['img_ipdf']}", 7, 11, 40, 25);
            $this->Image("../../configuracion/admin/img3/{$f['img_cpdf']}", 58, 15, 109, 24);
            $this->Image("../../configuracion/admin/img4/{$f['img_dpdf']}", 168, 16, 38, 14);
        }
        $this->SetY(40);
        $this->SetFont('Arial', 'B', 15);
        $this->SetTextColor(40, 40, 40);
        $this->Cell(0, 12, utf8_decode('NOTA DE DIAGNÓSTICO'), 0, 1, 'C');
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
$pdf->SetAutoPageBreak(true, 30);
// Datos del paciente (formato compacto y alineado)
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(230, 240, 255);
$pdf->Cell(0, 6, 'Datos del Paciente:', 0, 1, 'L', true);

$pdf->SetFont('Arial', '', 9);
$pdf->SetFillColor(255, 255, 255);

// Línea 1: Servicio y Fecha de Registro
$pdf->Cell(35, 5, 'Servicio:', 0, 0, 'L');
$pdf->Cell(55, 5, utf8_decode($tipo_a), 0, 0, 'L');
$pdf->Cell(35, 5, 'Fecha de registro:', 0, 0, 'L');
$pdf->Cell(0, 5, date('d/m/Y H:i', strtotime($pac_fecing)), 0, 1, 'L');

// Línea 2: Paciente y Teléfono
$pdf->Cell(35, 5, 'Paciente:', 0, 0, 'L');
$pdf->Cell(55, 5, utf8_decode($folio . ' - ' . $pac_papell . ' ' . $pac_sapell. ' ' . $pac_nom_pac), 0, 0, 'L');
$pdf->Cell(35, 5, utf8_decode('Teléfono:'), 0, 0, 'L');
$pdf->Cell(0, 5, utf8_decode($pac_tel), 0, 1, 'L');

// Línea 3: Fecha de nacimiento, Edad y Género
$pdf->Cell(35, 5, 'Fecha de nacimiento:', 0, 0, 'L');
$pdf->Cell(30, 5, date('d/m/Y', strtotime($pac_fecnac)), 0, 0, 'L');
$pdf->Cell(10, 5, 'Edad:', 0, 0, 'L');
$pdf->Cell(15, 5, utf8_decode($edad), 0, 0, 'L');
$pdf->Cell(15, 5, utf8_decode('Género:'), 0, 0, 'L');
$pdf->Cell(0, 5, utf8_decode($pac_sexo), 0, 1, 'L');

// Línea 4: Domicilio
$pdf->Cell(20, 5, 'Domicilio:', 0, 0, 'L');
$pdf->Cell(0, 5, utf8_decode($pac_dir), 0, 1, 'L');

// Diagnostic Section
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(230, 240, 255);
$pdf->Cell(0, 10, utf8_decode('DIAGNÓSTICO'), 0, 1, 'C', true);
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(45, 8, '', 1, 0, 'C', true);
$pdf->Cell(70, 8, utf8_decode('Ojo Derecho'), 1, 0, 'C', true);
$pdf->Cell(70, 8, utf8_decode('Ojo Izquierdo'), 1, 1, 'C', true);

$left_eye_fields = [
    'Diagnóstico principal' => $diag['diagnostico_principal_izquierdo'] ?? 'N/A',
    'Código CIE-10' => $diag['codigo_cie_izquierdo'] ?? 'N/A',
    'Descripción CIE-10' => $diag['desc_cie_izquierdo'] ?? 'N/A',
    'Tipo de diagnóstico' => $diag['tipo_diagnostico_izquierdo'] ?? 'N/A',
    'Otros diagnósticos' => $diag['otros_diagnosticos_izquierdo'] ?? 'N/A',
];

$right_eye_fields = [
    'Diagnóstico principal' => $diag['diagnostico_principal_derecho'] ?? 'N/A',
    'Código CIE-10' => $diag['codigo_cie_derecho'] ?? 'N/A',
    'Descripción CIE-10' => $diag['desc_cie_derecho'] ?? 'N/A',
    'Tipo de diagnóstico' => $diag['tipo_diagnostico_derecho'] ?? 'N/A',
    'Otros diagnósticos' => $diag['otros_diagnosticos_derecho'] ?? 'N/A',
];

$pdf->SetFont('Arial', '', 10);
foreach ($left_eye_fields as $label => $left_value) {
    $right_value = $right_eye_fields[$label] ?? 'N/A';
    if ($left_value !== 'N/A' || $right_value !== 'N/A') {
        $pdf->Cell(45, 5, utf8_decode($label), 1, 0, 'L');
        $pdf->Cell(70, 5, utf8_decode($right_value), 1, 0, 'C');
        $pdf->Cell(70, 5, utf8_decode($left_value), 1, 1, 'C');
    }
}

$pdf->Ln(5);

// General Diagnostic Fields
$general_fields = [
    'Oftalmológicamente sano' => $diag['oftalmologicamente_sano'] ? 'Sí' : 'No',
    'Diagnóstico previo' => $prev_diag_text,
];

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 8, utf8_decode('Detalles Generales'), 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);
foreach ($general_fields as $label => $value) {
    $pdf->MultiCell(0, 5, utf8_decode($label . ': ' . $value), 1, 'J', false);
}

$pdf->SetY(-60);
if (!empty($firma) && file_exists('../../imgfirma/' . $firma)) {
    $imgWidth = 30;
    $imgX = ($pdf->GetPageWidth() - $imgWidth) / 2;
    $pdf->Image('../../imgfirma/' . $firma, $imgX, $pdf->GetY(), $imgWidth);
    $pdf->Ln(12);
}
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(0, 6, utf8_decode(trim($pre_med . ' ' . $app_med . ' ' . $apm_med . ' ' . $nom_med)), 0, 1, 'C');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(0, 6, utf8_decode($cargp), 0, 1, 'C');
$pdf->Cell(0, 6, utf8_decode('Céd. Prof. ' . $ced_p), 0, 1, 'C');

// Output PDF
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="diagnostico_ocular.pdf"');
$pdf->Output('I', 'diagnostico_ocular.pdf');
exit();
?>