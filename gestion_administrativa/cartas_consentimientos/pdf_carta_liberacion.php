<?php
session_start();
include "../../conexionbd.php";
require '../../fpdf/fpdf.php';

if (!isset($_SESSION['login']) || !isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

$id_atencion = $_SESSION['hospital'];

// Obtener datos del paciente
$sql_pac = "SELECT p.papell, p.sapell, p.nom_pac, p.Id_exp, p.resp, p.paren, di.id_usua 
            FROM paciente p 
            JOIN dat_ingreso di ON p.Id_exp = di.Id_exp 
            WHERE di.id_atencion = ? LIMIT 1";
$stmt = $conexion->prepare($sql_pac);
$stmt->bind_param("i", $id_atencion);
$stmt->execute();
$res_pac = $stmt->get_result();
$pac = $res_pac->fetch_assoc();
$stmt->close();

if (!$pac) {
    die("Paciente no encontrado.");
}

$sql_doc = "SELECT pre, papell, sapell, nombre, firma 
            FROM reg_usuarios 
            WHERE id_usua = ? LIMIT 1";
$stmt = $conexion->prepare($sql_doc);
$stmt->bind_param("i", $pac['id_usua']);
$stmt->execute();
$res_doc = $stmt->get_result();
$med = $res_doc->fetch_assoc();
$stmt->close();

class PDF extends FPDF {
    function Header() {
        include "../../conexionbd.php";
        $res = $conexion->query("SELECT * FROM img_sistema ORDER BY id_simg DESC LIMIT 1");
        while ($f = $res->fetch_assoc()) {
            $this->Image("../../configuracion/admin/img2/{$f['img_ipdf']}", 7, 11, 40, 25);
            $this->Image("../../configuracion/admin/img3/{$f['img_cpdf']}", 58, 15, 109, 24);
            $this->Image("../../configuracion/admin/img4/{$f['img_dpdf']}", 168, 16, 38, 14);
        }
        $this->SetY(45);
        $this->SetFont('Arial', 'B', 13);
        $this->Cell(0, 10, utf8_decode('CARTA DE LIBERACIÓN DE RESPONSABILIDADES'), 0, 1, 'C');
    }

    function Footer() {
    $this->SetY(-25);
    $this->SetFont('Arial', '', 12);
    
    $this->MultiCell(0, 4, utf8_decode(
        "Av. Tecnológico 1020, Col. Bellavista, C.P. 52172, Metepec, Edo. de México\n" .
        "Teléfonos: (722) 232.8086 / (722) 238.6901   \nEmail: inst.enfermedadesoculares@gmail.com"
    ), 0, 'C');

    $this->Ln(2);
    $this->SetFont('Arial', 'I', 10);
    $this->Cell(0, 5, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
}

}

$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(20, 15, 20);
$pdf->SetAutoPageBreak(true, 30);
$pdf->SetFont('Arial', '', 11);

function writeText($pdf, $text) {
    $pdf->MultiCell(0, 7, utf8_decode($text), 0, 'J');
    $pdf->Ln(3);
}

// Fecha inicial
$pdf->Ln(5);
$pdf->Cell(0, 7, 'Metepec, Mexico a ______ de __________ del ______', 0, 1, 'R');
$pdf->Ln(10);

// Cuerpo de la carta
writeText($pdf, 'CARTA DE LIBERACIÓN DE RESPONSABILIDADES.');
$pdf->Ln(3);
writeText($pdf, 'Por medio de la presente DESLINDO de TODA RESPONSABILIDAD a el INSTITUTO DE ENFERMEDADES OCULARES de todos los procedimientos MÉDICOS, QUIRÚRGICOS Y ANESTÉSICOS que se realicen en el instituto. ENTIENDO que toda decisión médica es responsabilidad de mi médico tratante.');
$pdf->Ln(15);

// Firmas
// Preparar nombres
$nombrePaciente = utf8_decode($pac['papell'] . ' ' . $pac['sapell'] . ' ' . $pac['nom_pac']);
$nombreResponsable = utf8_decode($pac['resp']);
$nombreMedico = $med ? utf8_decode(trim($med['pre'] . ' ' . $med['papell'] . ' ' . $med['sapell'] . ' ' . $med['nombre'])) : '_______________________';

// Firma del paciente
$pdf->Cell(0, 7, "______________________________________________________________", 0, 1, 'L');
$pdf->Cell(0, 7, "NOMBRE Y FIRMA DEL PACIENTE.", 0, 1, 'L');
$pdf->Cell(0, 7, $nombrePaciente, 0, 1, 'L');
$pdf->Ln(10);

// Firma del responsable
$pdf->Cell(0, 7, "______________________________________________________________", 0, 1, 'L');
$pdf->Cell(0, 7, "NOMBRE Y FIRMA DEL RESPONSABLE.", 0, 1, 'L');
$pdf->Cell(0, 7, $nombreResponsable, 0, 1, 'L');
$pdf->Ln(10);

if (!empty($med['firma']) && file_exists('../../imgfirma/' . $med['firma'])) {
    $imgWidth = 40;
    $imgX = ($pdf->GetPageWidth() - $imgWidth) / 2;
    $pdf->Image('../../imgfirma/' . $med['firma'], $imgX, $pdf->GetY(), $imgWidth);
    $pdf->Ln(22);
} else {
    $pdf->Cell(0, 7, "______________________________________________________________", 0, 1, 'L');
}

$pdf->Cell(0, 7, "MEDICO TRATANTE.", 0, 1, 'C');
$pdf->Cell(0, 7, $nombreMedico, 0, 1, 'C');

// Salida PDF
ob_clean(); // Limpia salida previa
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="carta_liberacion.pdf"');
$pdf->Output('I', 'carta_liberacion.pdf');
exit();
