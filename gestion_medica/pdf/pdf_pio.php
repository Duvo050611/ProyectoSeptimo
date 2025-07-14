<?php
session_start();
include "../../conexionbd.php";
require '../../fpdf/fpdf.php';

if (!isset($_SESSION['login']) || !isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) die("ID no válido.");

// Obtener datos del examen PIO
$sql = "SELECT * FROM pio_examen WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if (!$data = $res->fetch_assoc()) {
    die("Examen PIO no encontrado.");
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
        $this->Cell(0, 10, utf8_decode('Examen de Presión Intraocular (PIO)'), 0, 1, 'C');
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
$pdf->SetAutoPageBreak(true, 30);
// Datos del paciente (formato compacto y alineado)
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(230, 240, 255);
$pdf->Cell(0, 6, 'Datos del Paciente:', 0, 1, 'L', true);

$pdf->SetFont('Arial', '', 9);
$pdf->SetFillColor(255, 255, 255);

// Línea 1: Servicio y Fecha de Registro
$pdf->Cell(35, 5, 'Servicio:', 0, 0, 'L');
$pdf->Cell(55, 5, utf8_decode($pac['tipo_a']), 0, 0, 'L');
$pdf->Cell(35, 5, 'Fecha de registro:', 0, 0, 'L');
$pdf->Cell(0, 5, date('d/m/Y H:i', strtotime($pac['fecha'])), 0, 1, 'L');

// Línea 2: Paciente y Teléfono
$pdf->Cell(35, 5, 'Paciente:', 0, 0, 'L');
$pdf->Cell(55, 5, utf8_decode($pac['folio'] . ' - ' . $pac['papell'] . ' ' . $pac['sapell'] . ' ' . $pac['nom_pac']), 0, 0, 'L');
$pdf->Cell(35, 5, utf8_decode('Teléfono:'), 0, 0, 'L');
$pdf->Cell(0, 5, utf8_decode($pac['tel']), 0, 1, 'L');

// Línea 3: Fecha de nacimiento, Edad y Género
$pdf->Cell(35, 5, 'Fecha de nacimiento:', 0, 0, 'L');
$pdf->Cell(30, 5, date('d/m/Y', strtotime($pac['fecnac'])), 0, 0, 'L');
$pdf->Cell(10, 5, 'Edad:', 0, 0, 'L');
$pdf->Cell(15, 5, utf8_decode($edad), 0, 0, 'L');
$pdf->Cell(15, 5, utf8_decode('Género:'), 0, 0, 'L');
$pdf->Cell(0, 5, utf8_decode($pac['sexo']), 0, 1, 'L');

// Línea 4: Domicilio
$pdf->Cell(20, 5, 'Domicilio:', 0, 0, 'L');
$pdf->Cell(0, 5, utf8_decode($pac['dir']), 0, 1, 'L');

// Datos del examen
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 8, utf8_decode('Resultados del Examen'), 0, 1, 'C', true);
$pdf->Ln(5);


// Centrar tabla (70 + 65 + 65 = 200 mm de ancho total)
$tablaAncho = 200;
$paginaAncho = $pdf->GetPageWidth();
$margenIzquierdo = ($paginaAncho - $tablaAncho) / 2;

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->SetX($margenIzquierdo);
$pdf->Cell(70, 8, '', 1, 0, 'C', true);
$pdf->Cell(65, 8, 'Ojo Derecho (OD)', 1, 0, 'C', true);
$pdf->Cell(65, 8, 'Ojo Izquierdo (OI)', 1, 1, 'C', true);

// Nueva función para dibujar filas centradas
function row($pdf, $label, $od, $oi, $margenIzquierdo) {
    $pdf->SetX($margenIzquierdo);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(70, 7, utf8_decode($label), 1, 0);
    $pdf->Cell(65, 7, utf8_decode($od), 1, 0);
    $pdf->Cell(65, 7, utf8_decode($oi), 1, 1);
}


row($pdf, 'PIO Aplanación Previa', $data['pio_aplanacion_previa_OD'], $data['pio_aplanacion_previa_OI'], $margenIzquierdo);
row($pdf, 'PIO TNG Previa', $data['pio_tng_previa_OD'], $data['pio_tng_previa_OI'], $margenIzquierdo);
row($pdf, 'PIO Aplanación Actual', $data['pio_aplanacion_OD'], $data['pio_aplanacion_OI'], $margenIzquierdo);
row($pdf, 'Tipo TNC', $data['pio_tnc_tipo_OD'], $data['pio_tnc_tipo_OI'], $margenIzquierdo);
row($pdf, 'PIO TNC', $data['pio_tnc_OD'], $data['pio_tnc_OI'], $margenIzquierdo);


// Tratamientos y correlaciones
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 7, 'Tratamiento PIO:', 0, 1);
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(0, 6, 'OD: ' . utf8_decode($data['tratamiento_pio_OD']));
$pdf->MultiCell(0, 6, 'OI: ' . utf8_decode($data['tratamiento_pio_OI']));

$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 7, utf8_decode('Correlación Paquimétrica:'), 0, 1);
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(0, 6, 'OD: ' . utf8_decode($data['correlacion_paquimetrica_OD']));
$pdf->MultiCell(0, 6, 'OI: ' . utf8_decode($data['correlacion_paquimetrica_OI']));

// Firma del médico
$pdf->SetY(-60);
if (!empty($med['firma']) && file_exists('../../imgfirma/' . $med['firma'])) {
    $imgWidth = 30;
    $imgX = ($pdf->GetPageWidth() - $imgWidth) / 2;
    $pdf->Image('../../imgfirma/' . $med['firma'], $imgX, $pdf->GetY(), $imgWidth);
    $pdf->Ln(12);
}
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(0, 6, utf8_decode(trim($med['pre'] . ' ' . $med['papell'] . ' ' . $med['sapell'] . ' ' . $med['nombre'])), 0, 1, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(0, 6, utf8_decode($med['cargp']), 0, 1, 'C');
$pdf->Cell(0, 6, utf8_decode('Céd. Prof. ' . $med['cedp']), 0, 1, 'C');

// Salida
$pdf->Output('I', 'pio.pdf');
exit();
?>
