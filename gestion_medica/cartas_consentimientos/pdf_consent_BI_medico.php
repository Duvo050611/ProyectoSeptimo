<?php
require '../../fpdf/fpdf.php';
include '../../conexionbd.php';

$id_exp = $_GET['id_exp'] ?? '';
$id_atencion = $_GET['id_atencion'] ?? '';
$medico = $_POST['medico_servicio'] ?? '';
$estudios = $_POST['estudios'] ?? '';
$diagnostico_pdf = $_POST['diagnostico_pdf'] ?? '';
$actos = $_POST['actos'] ?? '';
$trat = $_POST['trat'] ?? '';
$tratquir = $_POST['tratquir'] ?? '';
$ries = $_POST['ries'] ?? '';


mysqli_set_charset($conexion, "utf8");

// Obtener datos del paciente y médico
$pac = $conexion->query("SELECT * FROM paciente WHERE Id_exp = $id_exp")->fetch_assoc();
$ing = $conexion->query("SELECT * FROM dat_ingreso WHERE id_atencion = $id_atencion")->fetch_assoc();
$med = $conexion->query("SELECT * FROM reg_usuarios WHERE id_usua = {$ing['id_usua']}")->fetch_assoc();
$edo = $conexion->query("SELECT nombre FROM estados WHERE id_edo = {$pac['id_edo']}")->fetch_assoc();
$mun = $conexion->query("SELECT nombre_m FROM municipios WHERE id_mun = {$pac['id_mun']}")->fetch_assoc();

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
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, utf8_decode('CARTA DE CONSENTIMIENTO BAJO INFORMACIÓN'), 0, 1, 'C');
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
$pdf->SetMargins(15, 10, 15); // márgenes más compactos
$pdf->SetAutoPageBreak(true, 30);
$pdf->SetFont('Arial', '', 12);

// Función para escribir párrafos con margen vertical
function writeMultiline($pdf, $text, $height = 5) {
    $pdf->MultiCell(0, $height, utf8_decode($text), 0, 'J');
    $pdf->Ln(2); // menos separación entre párrafos
}


// Datos que se rellenan automáticamente
$nombrePaciente = $pac['papell'] . ' ' . $pac['sapell'] . ' ' . $pac['nom_pac'];
$nombreResponsable = $pac['resp'];
$parentesco = $pac['paren'];
$personaDesignada = $pac['resp'];
$nombreMedico = $med ? ($med['pre'] . ' ' . $med['papell'] . ' ' . $med['sapell'] . ' ' . $med['nombre']) : '________________________';

$pdf->Ln(5);
$pdf->Cell(0, 7, 'Metepec, Mexico a ______ de __________ del ______', 0, 1, 'R');
$pdf->Ln(5);

writeMultiline($pdf, "CARTA DE CONSENTIMIENTO BAJO INFORMACIÓN.");
$pdf->Ln(2);

// Datos autocompletados
$pdf->Cell(0, 7, "Nombre del paciente: " . utf8_decode($nombrePaciente), 0, 1);
$pdf->Cell(0, 7, "Nombre del responsable del paciente: " . utf8_decode($nombreResponsable), 0, 1);
$pdf->Cell(0, 7, "Parentesco: " . utf8_decode($parentesco), 0, 1);
$pdf->Ln(5);

writeMultiline($pdf, "Bajo protesta de decir la verdad declaro que el Dr. " . utf8_decode($nombreMedico));
$diag_derecho = isset($diag['diagnostico_principal_derecho']) ? $diag['diagnostico_principal_derecho'] : '';
$diag_izquierdo = isset($diag['diagnostico_principal_izquierdo']) ? $diag['diagnostico_principal_izquierdo'] : '';


// Cuerpo del consentimiento
$contenido = <<<EOT
Los médicos del servicio de:$medico me han informado de mi(s) padecimiento(s), por lo que necesito someterme a estudios de laboratorio, gabinete, histopatológicos, y procedimientos anestésicos, así como tratamiento(s) médicos y/o quirúrgicos considerados como indispensables para recuperar mi salud.

Los médicos me informaron de los riesgos y de las posibles complicaciones de los medios de diagnóstico y tratamientos médicos y/o quirúrgicos, por lo que por este medio, libremente y sin presión alguna acepto a someterme a:

Diagnóstico(s) clínico(s): $diagnostico_pdf

Estudios de laboratorio, gabinete e histopatológicos: $estudios

Actos anestésicos: $actos

Tratamiento(s) médicos: $trat

Tratamiento(s) quirúrgicos: $tratquir

Riesgos y complicaciones: $ries

He sido informado de los riesgos que entraña el procedimiento, por lo que acepto los riesgos que implica el mismo. Autorizo a los médicos de este hospital para que realicen los estudios y tratamientos convenientes. En igual sentido, autorizo ante cualquier complicación o efecto adverso durante el procedimiento, especialmente ante una urgencia médica, que se apliquen las técnicas y procedimientos necesarios.

Tengo la plena libertad de revocar la autorización de los estudios y tratamientos en cualquier momento, antes de realizarse.

En caso de ser menor de edad o con capacidades diferentes, se informó y autorizó al responsable del paciente.
EOT;

$pdf->MultiCell(0, 5.5, utf8_decode($contenido), 0, 'J');
$pdf->Ln(8);

// Firmas
$pdf->Cell(0, 6, 'NOMBRE Y FIRMA DEL PACIENTE:', 0, 1, 'C');
$pdf->Cell(0, 6, utf8_decode("{$pac['papell']} {$pac['sapell']} {$pac['nom_pac']}"), 0, 1, 'C');
$pdf->Ln(10);

$pdf->Cell(90, 6, utf8_decode("{$pac['resp']}"), 0, 0, 'C');
$pdf->Cell(0, 6, utf8_decode("{$med['pre']} {$med['papell']} {$med['sapell']} {$med['nombre']}"), 0, 1, 'C');
$pdf->Cell(90, 6, 'NOMBRE Y FIRMA DEL RESPONSABLE', 0, 0, 'C');
$pdf->Cell(0, 6, utf8_decode("{$med['cargp']} - CÉD. PROF. {$med['cedp']}"), 0, 1, 'C');

$pdf->Output();
