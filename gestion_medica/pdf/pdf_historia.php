<?php

use PDF as GlobalPDF;

require '../../fpdf/fpdf.php';
include '../../conexionbd.php';

$id = @$_GET['id'];
$id_exp = @$_GET['id_exp'];
$id_atencion = @$_GET['id_atencion'];

$sql_pac = "SELECT p.papell, p.nom_pac, p.sapell, p.edad, p.sexo, p.Id_exp, p.folio, p.dir, p.id_edo, p.id_mun, p.tel, p.ocup, p.resp, p.paren, p.tel_resp, p.fecnac 
            FROM paciente p WHERE p.Id_exp = $id_exp";
$result_pac = $conexion->query($sql_pac);
$row_pac = $result_pac->fetch_assoc();

$papell = $row_pac['papell'];
$nom_pac = $row_pac['nom_pac'];
$sapell = $row_pac['sapell'];
$edad = $row_pac['edad'];
$sexo = $row_pac['sexo'];
$Id_exp = $row_pac['Id_exp'];
$folio = $row_pac['folio'];
$dir = $row_pac['dir'];
$tel = $row_pac['tel'];
$ocup = $row_pac['ocup'];
$fecnac = $row_pac['fecnac'];

$sql_preop = "SELECT * FROM dat_ingreso WHERE id_atencion = $id_atencion";
$result_preop = $conexion->query($sql_preop);
$row_preop = $result_preop->fetch_assoc();

$tipo_a = $row_preop['tipo_a'] ?? '';
$fecha_ing = $row_preop['fecha'] ?? '';
$id_usua = $row_preop['id_usua'] ?? '';

$id_hist = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql_hist = "SELECT * FROM historia_clinica WHERE id = $id_hist LIMIT 1";$result_hist = $conexion->query($sql_hist);
$row_hist = $result_hist->fetch_assoc();


if (!$row_hist) {
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
        $(document).ready(function() {
            swal({
                title: "NO EXISTE HISTORIA CLÍNICA PARA ESTE PACIENTE", 
                type: "error",
                confirmButtonText: "ACEPTAR"
            }, function(isConfirm) { 
                if (isConfirm) {
                    window.close();
                }
            });
        });
    </script>';
    exit;
}

$observaciones = $row_hist['observaciones'] ?? '';
$sinto = $row_hist['sinto'] ?? '';
$sinto_otros = $row_hist['sinto_otros'] ?? '';
$heredo = $row_hist['heredo'] ?? '';
$heredo_otros = $row_hist['heredo_otros'] ?? '';
$nopat = $row_hist['nopat'] ?? '';
$nopat_otros = $row_hist['nopat_otros'] ?? '';
$pat_interrogados = $row_hist['pat_interrogados'] ?? '';
$pat_enf = $row_hist['pat_enf'] ?? '';
$pat_medicamentos = $row_hist['pat_medicamentos'] ?? '';
$pat_otras_alergias = $row_hist['pat_otras_alergias'] ?? '';
$pat_oculares = $row_hist['pat_oculares'] ?? '';
$pat_otras_cirugias = $row_hist['pat_otras_cirugias'] ?? '';
$fecha_his = $row_hist['fecha'] ?? date('Y-m-d H:i:s');
$GLOBALS['fecha_his'] = $fecha_his;
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

class PDF extends FPDF
{
    function Header()
    {
        include '../../conexionbd.php';
        $resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
        while ($f = mysqli_fetch_array($resultado)) {
            $this->Image("../../configuracion/admin/img2/" . $f['img_ipdf'], 7, 11, 40, 25);
            $this->Image("../../configuracion/admin/img3/" . $f['img_cpdf'], 58, 15, 109, 24);
            $this->Image("../../configuracion/admin/img4/" . $f['img_dpdf'], 168, 16, 38, 14);

        }
        $this->SetY(40);
        $this->SetFont('Arial', 'B', 15);
        $this->SetTextColor(40, 40, 40);
        $this->Cell(0, 12, utf8_decode('NOTA DE HISTORIA CLINICA'), 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 6, utf8_decode('Fecha: ') . date('d/m/Y H:i', strtotime($GLOBALS['fecha_his'])), 0, 1, 'R');
        $this->Ln(5);
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

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(230, 240, 255);
$pdf->Cell(0, 6, 'Datos del Paciente:', 0, 1, 'L', true);

$pdf->SetFont('Arial', '', 8);
$pdf->SetFillColor(255,255,255);
$pdf->Cell(35, 5, 'Servicio:', 0, 0, 'L');
$pdf->Cell(55, 5, utf8_decode($tipo_a), 0, 0, 'L');
$pdf->Cell(35, 5, 'Fecha de registro:', 0, 0, 'L');
$pdf->Cell(0, 5, date('d/m/Y H:i', strtotime($fecha_ing)), 0, 1, 'L');
$pdf->Cell(35, 5, 'Paciente:', 0, 0, 'L');
$pdf->Cell(55, 5, utf8_decode($folio . ' - ' . $papell . ' ' . $sapell . ' ' . $nom_pac), 0, 0, 'L');
$pdf->Cell(35, 5, utf8_decode('Teléfono:'), 0, 0, 'L');
$pdf->Cell(0, 5, utf8_decode($tel), 0, 1, 'L');

$pdf->Cell(35, 5, utf8_decode('Fecha de nacimiento:'), 0, 0, 'L');
$pdf->Cell(30, 5, date('d/m/Y', strtotime($fecnac)), 0, 0, 'L');
$pdf->Cell(10, 5, utf8_decode('Edad:'), 0, 0, 'L');
$pdf->Cell(15, 5, utf8_decode($edad), 0, 0, 'L');
$pdf->Cell(15, 5, utf8_decode('Género:'), 0, 0, 'L');
$pdf->Cell(20, 5, utf8_decode($sexo), 0, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('Ocupación:'), 0, 0, 'L');
$pdf->Cell(0, 5, utf8_decode($ocup), 0, 1, 'L');

$pdf->Cell(20, 5, utf8_decode('Domicilio:'), 0, 0, 'L');
$pdf->Cell(0, 5, utf8_decode($dir), 0, 1, 'L');


$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 8, utf8_decode('HISTORIA CLÍNICA'), 0, 1, 'C', true);
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 6, utf8_decode('Observaciones'), 0, 1, 'L', true);
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(0, 4, utf8_decode($observaciones), 1, 'J', false);
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 6, utf8_decode('Síntomas'), 0, 1, 'L', true);
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(0, 4, utf8_decode($sinto . ($sinto_otros ? ', Otros: ' . $sinto_otros : '')), 1, 'J', false);
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 6, utf8_decode('Heredo Familiares'), 0, 1, 'L', true);
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(0, 4, utf8_decode($heredo . ($heredo_otros ? ', Otros: ' . $heredo_otros : '')), 1, 'J', false);
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 6, utf8_decode('No Patológicos'), 0, 1, 'L', true);
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(0, 4, utf8_decode($nopat . ($nopat_otros ? ', Otros: ' . $nopat_otros : '')), 1, 'J', false);
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 6, utf8_decode('Antecedentes Patológicos Interrogados'), 0, 1, 'L', true);
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(0, 4, utf8_decode($pat_interrogados), 1, 'J', false);
$pdf->Ln(1);


$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 6, utf8_decode('Enfermedades'), 0, 1, 'L', true);
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(0, 4, utf8_decode($pat_enf), 1, 'J', false);
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 6, utf8_decode('Medicamentos'), 0, 1, 'L', true);
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(0, 4, utf8_decode($pat_medicamentos), 1, 'J', false);
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 6, utf8_decode('Otras Alergias'), 0, 1, 'L', true);
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(0, 4, utf8_decode($pat_otras_alergias), 1, 'J', false);
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 6, utf8_decode('Antecedentes Oculares'), 0, 1, 'L', true);
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(0, 4, utf8_decode($pat_oculares), 1, 'J', false);
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 6, utf8_decode('Otras Cirugías'), 0, 1, 'L', true);
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(0, 4, utf8_decode($pat_otras_cirugias), 1, 'J', false);

$pdf->Ln(8);

$pdf->SetY(-48);
if (!empty($firma) && file_exists('../../imgfirma/' . $firma)) {
    $imgWidth = 35;
    $imgX = ($pdf->GetPageWidth() - $imgWidth) / 2;
    $pdf->Image('../../imgfirma/' . $firma, $imgX, $pdf->GetY(), $imgWidth);
    $pdf->Ln(18);
}
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(0, 4, utf8_decode(trim($pre_med . ' ' . $app_med . ' ' . $apm_med . ' ' . $nom_med)), 0, 1, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(0, 4, utf8_decode($cargp), 0, 1, 'C');
$pdf->Cell(0, 4, utf8_decode('Céd. Prof. ' . $ced_p), 0, 1, 'C');
$pdf->Output();