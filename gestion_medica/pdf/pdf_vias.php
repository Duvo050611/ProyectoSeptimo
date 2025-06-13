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

// Datos del paciente
$sql_pac = "SELECT p.papell, p.sapell, p.nom_pac, p.fecnac, p.folio, p.sexo, p.tel, p.ocup, p.dir, di.fecha, di.tipo_a 
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
$stmt->close();

// Médico
$sql_doc = "SELECT pre, papell, sapell, nombre, firma, cedp, cargp FROM reg_usuarios WHERE id_usua = ?";
$stmt = $conexion->prepare($sql_doc);
$stmt->bind_param("i", $data['id_usua']);
$stmt->execute();
$res_doc = $stmt->get_result();
$med = $res_doc->fetch_assoc();
$stmt->close();

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

    function Footer() {
        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
    }
}

$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(true, 30);

// Datos del paciente
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(230, 240, 255);
$pdf->Cell(0, 8, 'Datos del Paciente', 0, 1, 'L', true);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(45, 7, 'Nombre:', 0, 0);
$pdf->Cell(0, 7, utf8_decode($pac['papell'] . ' ' . $pac['sapell'] . ' ' . $pac['nom_pac']), 0, 1);
$pdf->Cell(45, 7, 'Edad:', 0, 0);
$pdf->Cell(50, 7, utf8_decode($edad), 0, 0);
$pdf->Cell(30, 7, 'Género:', 0, 0);
$pdf->Cell(0, 7, utf8_decode($pac['sexo']), 0, 1);
$pdf->Cell(45, 7, 'Ocupación:', 0, 0);
$pdf->Cell(50, 7, utf8_decode($pac['ocup']), 0, 0);
$pdf->Cell(30, 7, 'Teléfono:', 0, 0);
$pdf->Cell(0, 7, utf8_decode($pac['tel']), 0, 1);
$pdf->Cell(45, 7, 'Domicilio:', 0, 0);
$pdf->MultiCell(0, 7, utf8_decode($pac['dir']));

// Datos exploratorios
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 8, 'Resultados de Exploración', 0, 1, 'C', true);
$pdf->Ln(5);


// Tabla con OD y OI
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
$pdf->Ln(15);
if (!empty($med['firma']) && file_exists('../../imgfirma/' . $med['firma'])) {
    $imgWidth = 40;
    $imgX = ($pdf->GetPageWidth() - $imgWidth) / 2;
    $pdf->Image('../../imgfirma/' . $med['firma'], $imgX, $pdf->GetY(), $imgWidth);
    $pdf->Ln(22);
}
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 6, utf8_decode(trim($med['pre'] . ' ' . $med['papell'] . ' ' . $med['sapell'] . ' ' . $med['nombre'])), 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, utf8_decode($med['cargp']), 0, 1, 'C');
$pdf->Cell(0, 6, utf8_decode('Céd. Prof. ' . $med['cedp']), 0, 1, 'C');

// Salida
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="exploracion.pdf"');
$pdf->Output('I', 'exploracion.pdf');
exit();
?>
