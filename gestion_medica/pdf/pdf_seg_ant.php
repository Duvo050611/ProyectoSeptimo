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

// Obtener datos del examen
$sql = "SELECT * FROM seg_ant WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if (!$data = $res->fetch_assoc()) {
    die("Examen Segmento Anterior no encontrado.");
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
$pac = $res_pac->fetch_assoc();
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
        $this->Cell(0, 10, utf8_decode('Examen de Segmento Anterior'), 0, 1, 'C');
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

// Sección Segmento Anterior
function filaDobles($pdf, $label, $od, $oi) {
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(55, 6, utf8_decode($label), 1, 0);
    $pdf->Cell(65, 6, utf8_decode($od), 1, 0);
    $pdf->Cell(65, 6, utf8_decode($oi), 1, 1);
}

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 8, 'Evaluacion del Segmento Anterior', 0, 1, 'C', true);
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(55, 6, '', 1, 0, 'C', true);
$pdf->Cell(65, 6, 'Ojo Derecho (OD)', 1, 0, 'C', true);
$pdf->Cell(65, 6, 'Ojo Izquierdo (OI)', 1, 1, 'C', true);

foreach ([
    'Párpados' => ['parpados_od', 'parpados_oi'],
    'Conj. Tarsal' => ['conj_tarsal_od', 'conj_tarsal_oi'],
    'Conj. Bulbar' => ['conj_bulbar_od', 'conj_bulbar_oi'],
    'Córnea' => ['cornea_od', 'cornea_oi'],
    'Cámara Anterior' => ['camara_anterior_od', 'camara_anterior_oi'],
    'Iris' => ['iris_od', 'iris_oi'],
    'Pupila' => ['pupila_od', 'pupila_oi'],
    'Cristalino' => ['cristalino_od', 'cristalino_oi'],
    'Gonioscopia' => ['gonioscopia_od', 'gonioscopia_oi'],
    'LOCS NO' => ['locs_no_od', 'locs_no_oi'],
    'LOCS NC' => ['locs_nc_od', 'locs_nc_oi'],
    'LOCS C' => ['locs_c_od', 'locs_c_oi'],
    'LOCS P' => ['locs_p_od', 'locs_p_oi'],
] as $label => [$odKey, $oiKey]) {
    filaDobles($pdf, $label, $data[$odKey], $data[$oiKey]);
}

// Observaciones
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 7, 'Observaciones:', 0, 1);
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(0, 6, utf8_decode($data['observaciones']));
$pdf->AddPage();

$pdf->Ln(5); // Espacio antes de las imágenes
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 7, 'Dibujo Clinico:', 0, 1, 'L');

// Coordenadas y tamaños
$posY = $pdf->GetY();
$posX_OD = 30;
$posX_OI = 120;
$imgWidth = 80;
$imgHeight = 100;

// Imagen OD
if (!empty($data['dibujo_od'])) {
    $ruta_od = $_SERVER['DOCUMENT_ROOT'] . "/INEOUpdate/" . $data['dibujo_od'];
    if (file_exists($ruta_od)) {
        $pdf->Image($ruta_od, $posX_OD, $posY, $imgWidth, $imgHeight);
    } else {
        $pdf->SetXY($posX_OD, $posY);
        $pdf->Cell($imgWidth, 7, 'Imagen OD no encontrada', 1, 0, 'C');
    }
}

// Imagen OI
if (!empty($data['dibujo_oi'])) {
    $ruta_oi = $_SERVER['DOCUMENT_ROOT'] . "/INEOUpdate/" . $data['dibujo_oi'];
    if (file_exists($ruta_oi)) {
        $pdf->Image($ruta_oi, $posX_OI, $posY, $imgWidth, $imgHeight);
    } else {
        $pdf->SetXY($posX_OI, $posY);
        $pdf->Cell($imgWidth, 7, 'Imagen OI no encontrada', 1, 0, 'C');
    }
}

// Texto centrado debajo de cada imagen
$pdf->SetY($posY + $imgHeight + 2);
$pdf->SetFont('Arial', 'I', 9);

// Etiqueta OD
$pdf->SetX($posX_OD);
$pdf->Cell($imgWidth, 5, 'Ojo Derecho', 0, 0, 'C');

// Etiqueta OI
$pdf->SetX($posX_OI);
$pdf->Cell($imgWidth, 5, 'Ojo Izquierdo', 0, 1, 'C');

// Espacio final para continuar el contenido
$pdf->Ln(10);


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
$pdf->Output('I', 'seg_anterior.pdf');
exit();
?>
