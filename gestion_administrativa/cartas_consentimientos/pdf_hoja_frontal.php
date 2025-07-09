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

$grupo_sang = $row_pac['grupo_sang'] ?? '';

$sql_preop = "SELECT * FROM dat_ingreso WHERE id_atencion = $id_atencion";
$result_preop = $conexion->query($sql_preop);
$row_preop = $result_preop->fetch_assoc();

$tipo_a = $row_preop['tipo_a'] ?? '';
$fecha_ing = $row_preop['fecha'] ?? '';
$id_usua = $row_preop['id_usua'] ?? '';

$diag_ing = $row_preop['diagnostico'] ?? '';
$diag_prev = $row_preop['diagnosticos_previos'] ?? '';

$sql_explo = "SELECT * FROM exploracion_fisica WHERE id_atencion = $id_atencion";

if (!empty($id)) {
    $sql_explo = "SELECT * FROM exploracion_fisica WHERE id = $id AND id_atencion = $id_atencion";
} else {
    $sql_explo = "SELECT * FROM exploracion_fisica WHERE id_atencion = $id_atencion";
}

$result_explo = $conexion->query($sql_explo);
$row_explo = $result_explo->fetch_assoc();

if (!$row_explo) {
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
        $(document).ready(function() {
            swal({
                title: "NO EXISTE EXPLORACIÓN FÍSICA PARA ESTE PACIENTE", 
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
$sql_hist = "SELECT * FROM historia_clinica WHERE id_atencion = $id_atencion ORDER BY id DESC LIMIT 1";
$result_hist = $conexion->query($sql_hist);
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
$exploracion = $row_explo['exploracion'];

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
        $this->Cell(0, 12, utf8_decode(' HOJA FRONTAL'), 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 6, utf8_decode('Fecha: ') . date('d/m/Y H:i', strtotime($GLOBALS['fecha_ing'])), 0, 1, 'R');
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

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(230, 240, 255);
$pdf->Cell(0, 8, 'Datos del Paciente:', 0, 1, 'L', true);

$pdf->SetFont('Arial', '', 10);
$pdf->SetFillColor(255,255,255);
$pdf->Cell(35, 7, 'Servicio:', 0, 0, 'L');
$pdf->Cell(55, 7, utf8_decode($tipo_a), 0, 0, 'L');
$pdf->Cell(35, 7, 'Fecha de registro:', 0, 0, 'L');
$pdf->Cell(0, 7, date('d/m/Y H:i', strtotime($fecha_ing)), 0, 1, 'L');
$pdf->Cell(35, 7, 'Paciente:', 0, 0, 'L');
$pdf->Cell(55, 7, utf8_decode($folio . ' - ' . $papell . ' ' . $sapell . ' ' . $nom_pac), 0, 0, 'L');
$pdf->Cell(35, 7, utf8_decode('Teléfono:'), 0, 0, 'L');
$pdf->Cell(0, 7, utf8_decode($tel), 0, 1, 'L');

$pdf->Cell(35, 7, utf8_decode('Fecha de nacimiento:'), 0, 0, 'L');
$pdf->Cell(30, 7, date('d/m/Y', strtotime($fecnac)), 0, 0, 'L');
$pdf->Cell(10, 7, utf8_decode('Edad:'), 0, 0, 'L');
$pdf->Cell(15, 7, utf8_decode($edad), 0, 0, 'L');
$pdf->Cell(15, 7, utf8_decode('Género:'), 0, 0, 'L');
$pdf->Cell(20, 7, utf8_decode($sexo), 0, 0, 'L');
$pdf->Cell(20, 7, utf8_decode('Ocupación:'), 0, 0, 'L');
$pdf->Cell(0, 7, utf8_decode($ocup), 0, 1, 'L');

$pdf->Cell(20, 7, utf8_decode('Domicilio:'), 0, 0, 'L');
$pdf->Cell(0, 7, utf8_decode($dir), 0, 1, 'L');
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 13);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 12, utf8_decode(' '), 0, 1, 'C', true);
$pdf->Ln(2);

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX(18);
$pdf->Cell(40, 6, utf8_decode('MÉDICO TRATANTE:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(60, 6, utf8_decode(trim($pre_med . ' ' . $app_med . ' ' . $apm_med . ' ' . $nom_med)), 0, 1, 'L');
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX(18);
$pdf->Cell(50, 6, utf8_decode('DIAGNÓSTICO DE INGRESO:'), 0, 1, 'L');

$pdf->SetFont('Arial', '', 9);

$sql_observaciones = "SELECT observaciones FROM historia_clinica WHERE id_atencion = $id_atencion";
$result_observaciones = $conexion->query($sql_observaciones);

$hayObservaciones = false;
while ($row_obs = $result_observaciones->fetch_assoc()) {
    if (!empty($row_obs['observaciones'])) {
        $pdf->SetX(18);
        $pdf->MultiCell(175, 6, utf8_decode($row_obs['observaciones']), 0, 'L');
        $pdf->Ln(1);
        $hayObservaciones = true;
    }
}
if (!$hayObservaciones) {
    $pdf->SetX(18);
    $pdf->MultiCell(175, 6, utf8_decode('Sin diagnósticos de ingreso registrados.'), 0, 'L');
}

$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX(18);
$pdf->Cell(50, 6, utf8_decode('DIAGNÓSTICO PREVIOS:'), 0, 1, 'L');

$pdf->SetFont('Arial', '', 9);

$sql_exploraciones = "SELECT exploracion FROM exploracion_fisica WHERE id_atencion = $id_atencion";
$result_exploraciones = $conexion->query($sql_exploraciones);

$lista_exploraciones = [];
while ($row = $result_exploraciones->fetch_assoc()) {
    if (!empty($row['exploracion'])) {
        $lista_exploraciones[] = $row['exploracion'];
    }
}

if (count($lista_exploraciones) > 0) {
    foreach ($lista_exploraciones as $exploracion) {
        $pdf->SetX(18);
        $pdf->MultiCell(175, 6, utf8_decode($exploracion), 0, 'L');
        $pdf->Ln(1);
    }
} else {
    $pdf->SetX(18);
    $pdf->MultiCell(175, 6, utf8_decode('Sin diagnosticos previos registrados.'), 0, 'L');
}

$pdf->SetY(-48);
if (!empty($firma) && file_exists('../../imgfirma/' . $firma)) {
    $imgWidth = 40;
    $imgX = ($pdf->GetPageWidth() - $imgWidth) / 2;
    $pdf->Image('../../imgfirma/' . $firma, $imgX, $pdf->GetY(), $imgWidth);
    $pdf->Ln(22);
}
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 6, utf8_decode(trim($pre_med . ' ' . $app_med . ' ' . $apm_med . ' ' . $nom_med)), 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, utf8_decode($cargp), 0, 1, 'C');
$pdf->Cell(0, 6, utf8_decode('Céd. Prof. ' . $ced_p), 0, 1, 'C');
$pdf->Output();