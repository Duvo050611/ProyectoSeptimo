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


$id_auto = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql_auto = "SELECT * FROM autorefractor WHERE id = $id_auto LIMIT 1";$result_auto = $conexion->query($sql_auto);
$row_auto = $result_auto->fetch_assoc();

if (!$row_auto) {
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
        $(document).ready(function() {
            swal({
                title: "NO EXISTE AUTOREFRACCION PARA ESTE PACIENTE", 
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

$previa_tipo1 = $row_auto['previa_tipo1'] ?? '';
$previa1_od_esf = $row_auto['previa1_od_esf'] ?? '';
$previa1_od_cil = $row_auto['previa1_od_cil'] ?? '';
$previa1_od_eje = $row_auto['previa1_od_eje'] ?? '';
$previa1_od_dip = $row_auto['previa1_od_dip'] ?? '';
$previa1_oi_esf = $row_auto['previa1_oi_esf'] ?? '';
$previa1_oi_cil = $row_auto['previa1_oi_cil'] ?? '';
$previa1_oi_eje = $row_auto['previa1_oi_eje'] ?? '';
$previa1_oi_dip = $row_auto['previa1_oi_dip'] ?? '';

$previa_tipo2 = $row_auto['previa_tipo2'] ?? '';
$previa2_od_esf = $row_auto['previa2_od_esf'] ?? '';
$previa2_od_cil = $row_auto['previa2_od_cil'] ?? '';
$previa2_od_eje = $row_auto['previa2_od_eje'] ?? '';
$previa2_od_dip = $row_auto['previa2_od_dip'] ?? '';
$previa2_oi_esf = $row_auto['previa2_oi_esf'] ?? '';
$previa2_oi_cil = $row_auto['previa2_oi_cil'] ?? '';
$previa2_oi_eje = $row_auto['previa2_oi_eje'] ?? '';
$previa2_oi_dip = $row_auto['previa2_oi_dip'] ?? '';

$nueva_sin_od_esf = $row_auto['nueva_sin_od_esf'] ?? '';
$nueva_sin_od_cil = $row_auto['nueva_sin_od_cil'] ?? '';
$nueva_sin_od_eje = $row_auto['nueva_sin_od_eje'] ?? '';
$nueva_sin_oi_esf = $row_auto['nueva_sin_oi_esf'] ?? '';
$nueva_sin_oi_cil = $row_auto['nueva_sin_oi_cil'] ?? '';
$nueva_sin_oi_eje = $row_auto['nueva_sin_oi_eje'] ?? '';

$ciclo_tipo = $row_auto['ciclo_tipo'] ?? '';
$nueva_con_od_esf = $row_auto['nueva_con_od_esf'] ?? '';
$nueva_con_od_cil = $row_auto['nueva_con_od_cil'] ?? '';
$nueva_con_od_eje = $row_auto['nueva_con_od_eje'] ?? '';
$nueva_con_od_dip = $row_auto['nueva_con_od_dip'] ?? '';
$nueva_con_oi_esf = $row_auto['nueva_con_oi_esf'] ?? '';
$nueva_con_oi_cil = $row_auto['nueva_con_oi_cil'] ?? '';
$nueva_con_oi_eje = $row_auto['nueva_con_oi_eje'] ?? '';
$nueva_con_oi_dip = $row_auto['nueva_con_oi_dip'] ?? '';

$ret_ref_od_esf = $row_auto['ret_ref_od_esf'] ?? '';
$ret_ref_od_cil = $row_auto['ret_ref_od_cil'] ?? '';
$ret_ref_od_eje = $row_auto['ret_ref_od_eje'] ?? '';
$ret_ref_oi_esf = $row_auto['ret_ref_oi_esf'] ?? '';
$ret_ref_oi_cil = $row_auto['ret_ref_oi_cil'] ?? '';
$ret_ref_oi_eje = $row_auto['ret_ref_oi_eje'] ?? '';

$q_od_k1 = $row_auto['q_od_k1'] ?? '';
$q_od_k1_eje = $row_auto['q_od_k1_eje'] ?? '';
$q_od_k2 = $row_auto['q_od_k2'] ?? '';
$q_od_k2_eje = $row_auto['q_od_k2_eje'] ?? '';
$q_od_cyl = $row_auto['q_od_cyl'] ?? '';
$q_od_cyl_eje = $row_auto['q_od_cyl_eje'] ?? '';
$q_oi_k1 = $row_auto['q_oi_k1'] ?? '';
$q_oi_k1_eje = $row_auto['q_oi_k1_eje'] ?? '';
$q_oi_k2 = $row_auto['q_oi_k2'] ?? '';
$q_oi_k2_eje = $row_auto['q_oi_k2_eje'] ?? '';
$q_oi_cyl = $row_auto['q_oi_cyl'] ?? '';
$q_oi_cyl_eje = $row_auto['q_oi_cyl_eje'] ?? '';
$fecha_auto = $row_hist['fecha'] ?? date('Y-m-d H:i:s');
$GLOBALS['fecha_auto'] = $fecha_auto;

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
        $this->Cell(0, 12, utf8_decode('NOTA DE AUTORREFRACCIÓN / QUERATOCONO'), 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 6, utf8_decode('Fecha: ') . date('d/m/Y H:i', strtotime($GLOBALS['fecha_auto'])), 0, 1, 'R');
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
$pdf->Cell(0, 12, utf8_decode('AUTORREFRACCIÓN  / QUERATOCONO'), 0, 1, 'C', true);
$pdf->Ln(2);

function tablaCentralizada($pdf, $titulo, $tipo, $od, $oi) {
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->SetFillColor(230, 230, 230);
    $pdf->Cell(0, 9, utf8_decode($titulo . ($tipo ? ": $tipo" : "")), 0, 1, 'C', true);
    $pdf->SetFont('Arial', '', 10);

    $ancho_col1 = 30;
    $ancho_col2 = 30;
    $ancho_col3 = 30;

    $totalWidth = $ancho_col1 + $ancho_col2 + $ancho_col3;
    $startX = ($pdf->GetPageWidth() - $totalWidth) / 2;
    $pdf->SetX($startX);
    $pdf->Cell($ancho_col1, 6, '', 1, 0, 'C', true);
    $pdf->Cell($ancho_col2, 6, 'OD', 1, 0, 'C', true);
    $pdf->Cell($ancho_col3, 6, 'OI', 1, 1, 'C', true);

    foreach ($od as $i => $val) {
        $pdf->SetX($startX);
        $pdf->Cell($ancho_col1, 7, $val[0], 1, 0, 'C');
        $pdf->Cell($ancho_col2, 7, $val[1], 1, 0, 'C');
        $pdf->Cell($ancho_col3, 7, $oi[$i][1], 1, 1, 'C');
    }
    $pdf->Ln(2);
}
tablaCentralizada($pdf, 'Refracción Previa 1', $previa_tipo1, [
    ['ESF', $previa1_od_esf],
    ['CIL', $previa1_od_cil],
    ['EJE', $previa1_od_eje],
    ['DIP', $previa1_od_dip]
], [
    ['ESF', $previa1_oi_esf],
    ['CIL', $previa1_oi_cil],
    ['EJE', $previa1_oi_eje],
    ['DIP', $previa1_oi_dip]
]);

tablaCentralizada($pdf, 'Refracción Previa 2', $previa_tipo2, [
    ['ESF', $previa2_od_esf],
    ['CIL', $previa2_od_cil],
    ['EJE', $previa2_od_eje],
    ['DIP', $previa2_od_dip]
], [
    ['ESF', $previa2_oi_esf],
    ['CIL', $previa2_oi_cil],
    ['EJE', $previa2_oi_eje],
    ['DIP', $previa2_oi_dip]
]);

tablaCentralizada($pdf, 'Nueva Sin Cicloplégico', '', [
    ['ESF', $nueva_sin_od_esf],
    ['CIL', $nueva_sin_od_cil],
    ['EJE', $nueva_sin_od_eje]
], [
    ['ESF', $nueva_sin_oi_esf],
    ['CIL', $nueva_sin_oi_cil],
    ['EJE', $nueva_sin_oi_eje]
]);

tablaCentralizada($pdf, 'Nueva Con Cicloplégico', $ciclo_tipo, [
    ['ESF', $nueva_con_od_esf],
    ['CIL', $nueva_con_od_cil],
    ['EJE', $nueva_con_od_eje],
    ['DIP', $nueva_con_od_dip]
], [
    ['ESF', $nueva_con_oi_esf],
    ['CIL', $nueva_con_oi_cil],
    ['EJE', $nueva_con_oi_eje],
    ['DIP', $nueva_con_oi_dip]
]);

tablaCentralizada($pdf, 'Retinoscopía / Reflejo', '', [
    ['ESF', $ret_ref_od_esf],
    ['CIL', $ret_ref_od_cil],
    ['EJE', $ret_ref_od_eje]
], [
    ['ESF', $ret_ref_oi_esf],
    ['CIL', $ret_ref_oi_cil],
    ['EJE', $ret_ref_oi_eje]
]);

tablaCentralizada($pdf, 'Queratometría', '', [
    ['K1', $q_od_k1 . ' / ' . $q_od_k1_eje],
    ['K2', $q_od_k2 . ' / ' . $q_od_k2_eje],
    ['CYL', $q_od_cyl . ' / ' . $q_od_cyl_eje]
], [
    ['K1', $q_oi_k1 . ' / ' . $q_oi_k1_eje],
    ['K2', $q_oi_k2 . ' / ' . $q_oi_k2_eje],
    ['CYL', $q_oi_cyl . ' / ' . $q_oi_cyl_eje]
]);

$pdf->Ln(10);
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