<?php
session_start();
include "../../conexionbd.php";
require '../../fpdf/fpdf.php';

if (!isset($_SESSION['login']) || !isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die("ID no válido.");
}

// Obtener exploración
$sql = "SELECT * FROM exploraciones WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if (!$data = $res->fetch_assoc()) {
    die("Exploración no encontrada.");
}
$stmt->close();

// Datos del paciente (modificado: añadimos di.id_usua)
$sql_pac = "SELECT p.papell, p.sapell, p.nom_pac, p.fecnac, p.folio, p.sexo, p.tel, p.ocup, p.dir, di.fecha, di.tipo_a, di.id_usua 
            FROM paciente p 
            JOIN dat_ingreso di ON p.Id_exp = di.Id_exp 
            WHERE di.id_atencion = ?";
$stmt = $conexion->prepare($sql_pac);
$stmt->bind_param("i", $data['id_atencion']);
$stmt->execute();
$res_pac = $stmt->get_result();
if (!$pac = $res_pac->fetch_assoc()) {
    die("Paciente no encontrado.");
}

// Corrección: usamos $pac, no $row_preop
$tipo_a = $pac['tipo_a'] ?? '';
$fecha_ing = $pac['fecha'] ?? '';
$id_usua = $pac['id_usua'] ?? 0;
$stmt->close();

// Médico
$sql_doc = "SELECT pre, papell, sapell, nombre, firma, cedp, cargp FROM reg_usuarios WHERE id_usua = ?";
$stmt = $conexion->prepare($sql_doc);
$stmt->bind_param("i", $data['id_usua']);
$stmt->execute();
$res_doc = $stmt->get_result();
$med = $res_doc->fetch_assoc();
$stmt->close();

$sql_med = "SELECT * FROM reg_usuarios WHERE id_usua = $id_usua";
$result_med = $conexion->query($sql_med);
$row_med = $result_med->fetch_assoc();
$nom_med = $row_med['nombre'] ?? '';
$app_med = $row_med['papell'] ?? '';
$apm_med = $row_med['sapell'] ?? '';
$pre_med = $row_med['pre'] ?? '';
$firma = $row_med['firma'] ?? '';
$ced_p = $row_med['cedp'] ?? '';
$cargp = $row_med['cargp'] ?? '';

// Edad
function calculaedad($fecha) {
    if (!$fecha) return "No disponible";
    $nac = new DateTime($fecha);
    $hoy = new DateTime();
    $edad = $hoy->diff($nac);
    return $edad->y . " años";
}
$edad = calculaedad($pac['fecnac']);

// PDF
class PDF extends FPDF {
    function Header() {
        include "../../conexionbd.php";
        $res = $conexion->query("SELECT * FROM img_sistema ORDER BY id_simg DESC LIMIT 1");
        while ($f = $res->fetch_assoc()) {
            $this->Image("../../configuracion/admin/img2/{$f['img_ipdf']}", 7, 11, 40, 25);
            $this->Image("../../configuracion/admin/img3/{$f['img_cpdf']}", 58, 15, 109, 24);
            $this->Image("../../configuracion/admin/img4/{$f['img_dpdf']}", 168, 16, 38, 14);
        }
        $this->SetY(40);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, utf8_decode('Exploración Oftálmica'), 0, 1, 'C');
    }

   function Footer()
    {
        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(120, 120, 120);
        $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
        $this->Cell(0, 10, utf8_decode('INEO-000'), 0, 1, 'R');
    }
}

$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(true, 32);

// Datos del paciente en formato compacto
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(230, 240, 255);
$pdf->Cell(0, 6, 'Datos del Paciente:', 0, 1, 'L', true);

$pdf->SetFont('Arial', '', 8);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(35, 5, 'Servicio:', 0, 0, 'L');
$pdf->Cell(55, 5, utf8_decode($tipo_a), 0, 0, 'L');
$pdf->Cell(35, 5, 'Fecha de registro:', 0, 0, 'L');
$pdf->Cell(0, 5, date('d/m/Y H:i', strtotime($fecha_ing)), 0, 1, 'L');

$pdf->Cell(35, 5, 'Paciente:', 0, 0, 'L');
$pdf->Cell(55, 5, utf8_decode($pac['folio'] . ' - ' . $pac['papell'] . ' ' . $pac['sapell'] . ' ' . $pac['nom_pac']), 0, 0, 'L');
$pdf->Cell(35, 5, utf8_decode('Teléfono:'), 0, 0, 'L');
$pdf->Cell(0, 5, utf8_decode($pac['tel']), 0, 1, 'L');

$pdf->Cell(35, 5, 'Fecha de nacimiento:', 0, 0, 'L');
$pdf->Cell(30, 5, date('d/m/Y', strtotime($pac['fecnac'])), 0, 0, 'L');
$pdf->Cell(10, 5, 'Edad:', 0, 0, 'L');
$pdf->Cell(15, 5, utf8_decode($edad), 0, 0, 'L');
$pdf->Cell(15, 5, utf8_decode('Género:'), 0, 0, 'L');
$pdf->Cell(20, 5, utf8_decode($pac['sexo']), 0, 1, 'L');

$pdf->Cell(20, 5, 'Domicilio:', 0, 0, 'L');
$pdf->Cell(0, 5, utf8_decode($pac['dir']), 0, 1, 'L');

$pdf->Ln(3);
// Datos exploratorios
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 8,utf8_decode( 'Resultados de Exploración'), 0, 1, 'C', true);
$pdf->Ln(5);

// Tabla OD / OI
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(60, 8, '', 1, 0, 'C', true);
$pdf->Cell(65, 8, 'Ojo Derecho (OD)', 1, 0, 'C', true);
$pdf->Cell(65, 8, 'Ojo Izquierdo (OI)', 1, 1, 'C', true);

function row($pdf, $label, $od, $oi) {
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(60, 7, utf8_decode($label), 1, 0);
    $pdf->Cell(65, 7, utf8_decode($od), 1, 0);
    $pdf->Cell(65, 7, utf8_decode($oi), 1, 1);
}

row($pdf, 'Apertura Palpebral', $data['apertura_palpebral'], $data['apertura_palpebral_oi']);
row($pdf, 'Hendidura Palpebral', $data['hendidura_palpebral'], $data['hendidura_palpebral_oi']);
row($pdf, 'Función M. Elevador', $data['funcion_musculo_elevador'], $data['funcion_musculo_elevador_oi']);
row($pdf, 'Dist. Margen Reflejo 1', $data['distancia_margen_reflejo_1'], '-');
row($pdf, 'Dist. Margen Reflejo 2', $data['distancia_margen_reflejo_2'], '-');
row($pdf, 'Exposición Escleral Superior', $data['exposicion_escleral_superior'], '-');
row($pdf, 'Exposición Escleral Inferior', $data['exposicion_escleral_inferior'], '-');
row($pdf, 'Altura del Surco', $data['altura_surco'], '-');
row($pdf, 'Dist. Ceja-Pestaña', $data['distancia_ceja_pestana'], '-');
row($pdf, 'Fenómeno de Bell', $data['fenomeno_bell'], $data['fenomeno_bell_oi']);
row($pdf, 'Laxitud Horizontal', $data['laxitud_horizontal'], $data['laxitud_horizontal_oi']);
row($pdf, 'Laxitud Vertical', $data['laxitud_vertical'], $data['laxitud_vertical_oi']);
row($pdf, 'Exoftalmometría', $data['exoftalmometria'], '-');
row($pdf, 'Base Exoftalmométrica', $data['exoftalmometria_base'], '-');
row($pdf, 'Desplazamiento Ocular', $data['desplazamiento_ocular'], $data['desplazamiento_ocular_oi']);
row($pdf, 'Maniobra Vatsaha', $data['maniobra_vatsaha'], $data['maniobra_vatsaha_oi']);

// Observaciones
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 7, 'Observaciones:', 0, 1);
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(0, 6, utf8_decode($data['observaciones']));

// Firma
$pdf->SetY(-56);
if (!empty($firma) && file_exists('../../imgfirma/' . $firma)) {
    $imgWidth = 30;
    $imgX = ($pdf->GetPageWidth() - $imgWidth) / 2;
    $pdf->Image('../../imgfirma/' . $firma, $imgX, $pdf->GetY(), $imgWidth);
    $pdf->Ln(12);
}
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(0, 3, utf8_decode(trim($pre_med . ' ' . $app_med . ' ' . $apm_med . ' ' . $nom_med)), 0, 1, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(0, 3, utf8_decode($cargp), 0, 1, 'C');
$pdf->Cell(0, 3, utf8_decode('Céd. Prof. ' . $ced_p), 0, 1, 'C');
$pdf->Cell(0, 3, utf8_decode('Nombre y firma del médico'), 0, 1, 'C');

$pdf->Output('I', 'exploracion.pdf');
exit();
