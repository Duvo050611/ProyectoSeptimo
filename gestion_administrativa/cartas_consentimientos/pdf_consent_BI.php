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

// Obtener datos del médico tratante
$sql_doc = "SELECT pre, papell, sapell, nombre, firma FROM reg_usuarios WHERE id_usua = ? LIMIT 1";
$stmt = $conexion->prepare($sql_doc);
$stmt->bind_param("i", $pac['id_usua']);
$stmt->execute();
$res_doc = $stmt->get_result();
$med = $res_doc->fetch_assoc();
$stmt->close();

// Obtener diagnóstico ocular
$sql_diag = "SELECT diagnostico_principal_derecho, diagnostico_principal_izquierdo, otros_diagnosticos_derecho, otros_diagnosticos_izquierdo
             FROM ocular_diagnostico
             WHERE id_atencion = ? LIMIT 1";
$stmt = $conexion->prepare($sql_diag);
$stmt->bind_param("i", $id_atencion);
$stmt->execute();
$res_diag = $stmt->get_result();
$diag = $res_diag->fetch_assoc();
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

$textoDiagnostico = "Me ha explicado que mi diagnóstico es:";
if ($diag_derecho || $diag_izquierdo) {
    $textoDiagnostico .= "\n- Ojo Derecho: " . utf8_decode($diag_derecho);
    $textoDiagnostico .= "\n- Ojo Izquierdo: " . utf8_decode($diag_izquierdo);
} else {
    $textoDiagnostico .= " _____________________________________________";
}

writeMultiline($pdf, $textoDiagnostico);

writeMultiline($pdf, "Y por tal motivo debo someterme al(os) siguientes procedimientos con fines diagnósticos y/o terapéuticos: ");
$pdf->Cell(0, 7, "____________________________________________________________________________________", 0, 1);
$pdf->Cell(0, 7, "____________________________________________________________________________________", 0, 1);
$pdf->Ln(2);

writeMultiline($pdf, "Entiendo que todo acto médico diagnóstico de tratamiento, sea quirúrgico o no quirúrgico, puede ocasionar una serie de complicaciones mayores o menores, a veces potencialmente serias que incluyen cierto riesgo de muerte y puede requerir tratamientos complementarios médicos y/o quirúrgicos, que aumenten la estancia hospitalaria. Dichas complicaciones a veces son derivadas directamente de la propia técnica, pero otras dependerán del procedimiento, del estado del paciente y de los tratamientos que ha recibido y de las posibles anomalías anatómicas y/o de la utilización de los equipos médicos. Reconozco que entre los posibles riesgos y complicaciones que pueden surgir, mi médico tratante me ha explicado detalladamente cuáles son los riesgos y acepto mi intervención quirúrgica.");

writeMultiline($pdf, "Los probables beneficios esperados son:");
writeMultiline($pdf, "Mejorar mi estado de salud visual o conservar la misma.");
$pdf->Ln(2);

writeMultiline($pdf, "Declaro que he comprendido las explicaciones que se me han facilitado en un lenguaje claro y sencillo, y el médico que me atiende me ha permitido realizar todas mis observaciones y me ha aclarado todas las dudas que le he planteado. También comprendo que, por escrito, en cualquier momento puedo revocar el consentimiento que ahora otorgo. Por ello manifiesto que estoy satisfecho(a) con la información recibida y que comprendo el alcance y los riesgos del procedimiento.");


$pdf->Cell(0, 7, "Del mismo modo designo a: " . utf8_decode($personaDesignada), 0, 1);

$pdf->Ln(2);

writeMultiline($pdf, "Para que exclusivamente reciba información sobre mi estado de salud, diagnóstico, tratamiento y/o pronóstico.");
writeMultiline($pdf, "En tales condiciones CONSIENTO en forma libre y sin ningún tipo de presión en que se me realice la: ");
$pdf->Cell(0, 7, "____________________________________________________________________________________", 0, 1);
$pdf->Cell(0, 7, "____________________________________________________________________________________", 0, 1);
$pdf->Cell(0, 7, "____________________________________________________________________________________", 0, 1);
$pdf->Ln(10);

// Firma del paciente
$pdf->Cell(0, 7, "______________________________________________________________", 0, 1, 'L');
$pdf->Cell(0, 7, "NOMBRE Y FIRMA DEL PACIENTE.", 0, 1, 'L');
$pdf->Cell(0, 7, utf8_decode($nombrePaciente), 0, 1, 'L');
$pdf->Ln(5);

// Firma del responsable
$pdf->Cell(0, 7, "______________________________________________________________", 0, 1, 'L');
$pdf->Cell(0, 7, "NOMBRE Y FIRMA DEL RESPONSABLE.", 0, 1, 'L');
$pdf->Cell(0, 7, utf8_decode($nombreResponsable), 0, 1, 'L');
$pdf->Ln(5);

// Firma del médico (con firma si está disponible)
if (!empty($med['firma']) && file_exists('../../imgfirma/' . $med['firma'])) {
    $imgWidth = 40;
    $imgX = ($pdf->GetPageWidth() - $imgWidth) / 2;
    $pdf->Image('../../imgfirma/' . $med['firma'], $imgX, $pdf->GetY(), $imgWidth);
    $pdf->Ln(22);
} else {
    $pdf->Cell(0, 7, "______________________________________________________________", 0, 1, 'C');
}

$pdf->Cell(0, 7, "NOMBRE Y FIRMA DEL MEDICO TRATANTE.", 0, 1, 'C');
$pdf->Cell(0, 7, utf8_decode($nombreMedico), 0, 1, 'C');

$pdf->Ln(10);
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="carta_consentimiento.pdf"');
$pdf->Output('I', 'carta_consentimiento.pdf');
exit();
