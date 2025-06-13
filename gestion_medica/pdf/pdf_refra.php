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
$id_refra = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql_refra = "SELECT * FROM refraccion_actual WHERE id = $id_refra LIMIT 1";$result_refra = $conexion->query($sql_refra);
$row_refra = $result_refra->fetch_assoc();

if (!$row_refra) {
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
        $(document).ready(function() {
            swal({
                title: "NO EXISTE REFRACCION ACTUAL PARA ESTE PACIENTE", 
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

$av_binocular = $row_refra['av_binocular'] ?? '';
$av_lejana_sin_correc = $row_refra['av_lejana_sin_correc'] ?? '';
$av_estenopico = $row_refra['av_estenopico'] ?? '';
$av_lejana_con_correc_prop = $row_refra['av_lejana_con_correc_prop'] ?? '';
$av_lejana_mejor_corregida = $row_refra['av_lejana_mejor_corregida'] ?? '';
$av_potencial = $row_refra['av_potencial'] ?? '';
$oi_lejana_sin_correc = $row_refra['oi_lejana_sin_correc'] ?? '';
$oi_estenopico = $row_refra['oi_estenopico'] ?? '';
$oi_lejana_con_correc_prop = $row_refra['oi_lejana_con_correc_prop'] ?? '';
$oi_lejana_mejor_corregida = $row_refra['oi_lejana_mejor_corregida'] ?? '';
$oi_potencial = $row_refra['oi_potencial'] ?? '';
$detalle_refra = $row_refra['detalle_refra'] ?? '';
$fecha_re_a = $row_refra['fecha'] ?? date('Y-m-d H:i:s');
$GLOBALS['fecha_re_a'] = $fecha_re_a;
class PDF extends FPDF
{
    function Header()
    {
        include '../../conexionbd.php';
        $resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
        while ($f = mysqli_fetch_array($resultado)) {
            $this->Image("../../configuracion/admin/img2/" . $f['img_ipdf'], 5, 11, 40, 25);
            $this->Image("../../configuracion/admin/img3/" . $f['img_cpdf'], 58, 15, 109, 24);
            $this->Image("../../configuracion/admin/img4/" . $f['img_dpdf'], 168, 16, 38, 14);

        }
        $this->SetY(40);
        $this->SetFont('Arial', 'B', 15);
        $this->SetTextColor(40, 40, 40);
        $this->Cell(0, 12, utf8_decode('NOTA DE REFRACCION ACTUAL'), 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 6, utf8_decode('Fecha: ') . date('d/m/Y H:i', strtotime($GLOBALS['fecha_re_a'])), 0, 1, 'R');
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


$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 13);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 12, utf8_decode('REFRACCIÓN ACTUAL'), 0, 1, 'C', true);
$pdf->Ln(2);

// Agudeza Visual Lejana
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 9, utf8_decode('Agudeza Visual Lejana'), 0, 1, 'C', true);
$pdf->SetFont('Arial', '', 10);
$startX = ($pdf->GetPageWidth() - 120) / 2;
$pdf->SetX($startX);
$pdf->Cell(40, 5, '', 1, 0, 'C', true);
$pdf->Cell(40, 5, 'OD', 1, 0, 'C', true);
$pdf->Cell(40, 5, 'OI', 1, 1, 'C', true);

$pdf->SetX($startX);
$pdf->Cell(40, 5, utf8_decode('Sin corrección'), 1, 0, 'C');
$pdf->Cell(40, 5, $av_lejana_sin_correc, 1, 0, 'C');
$pdf->Cell(40, 5, $oi_lejana_sin_correc, 1, 1, 'C');

$pdf->SetX($startX);
$pdf->Cell(40, 5, utf8_decode('Estenópico'), 1, 0, 'C');
$pdf->Cell(40, 5, $av_estenopico, 1, 0, 'C');
$pdf->Cell(40, 5, $oi_estenopico, 1, 1, 'C');

$pdf->SetX($startX);
$pdf->Cell(40, 5, 'Con correc. propia', 1, 0, 'C');
$pdf->Cell(40, 5, $av_lejana_con_correc_prop, 1, 0, 'C');
$pdf->Cell(40, 5, $oi_lejana_con_correc_prop, 1, 1, 'C');

$pdf->SetX($startX);
$pdf->Cell(40, 5, 'Mejor corregida', 1, 0, 'C');
$pdf->Cell(40, 5, $av_lejana_mejor_corregida, 1, 0, 'C');
$pdf->Cell(40, 5, $oi_lejana_mejor_corregida, 1, 1, 'C');

$pdf->SetX($startX);
$pdf->Cell(40, 5, 'Potencial', 1, 0, 'C');
$pdf->Cell(40, 5, $av_potencial, 1, 0, 'C');
$pdf->Cell(40, 5, $oi_potencial, 1, 1, 'C');

$pdf->SetX($startX);
$pdf->Cell(40, 5, 'Binocular', 1, 0, 'C');
$pdf->Cell(80, 5, $av_binocular, 1, 1, 'C');

$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 9, utf8_decode('Detalle de Refracción'), 0, 1, 'C', true);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, 5, utf8_decode($detalle_refra), 1, 'J', false);

$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 9, utf8_decode('Subjetiva Sin Cicloplégico'), 0, 1, 'C', true);

$esferas_sin_ciclo_od = $row_refra['esferas_sin_ciclo_od'] ?? '';
$cilindros_sin_ciclo_od = $row_refra['cilindros_sin_ciclo_od'] ?? '';
$eje_sin_ciclo_od = $row_refra['eje_sin_ciclo_od'] ?? '';
$add_sin_ciclo_od = $row_refra['add_sin_ciclo_od'] ?? '';
$dip_sin_ciclo_od = $row_refra['dip_sin_ciclo_od'] ?? '';
$prisma_sin_ciclo_od = !empty($row_refra['prisma_sin_ciclo_od']) ? utf8_decode('Sí') : 'No';

$esferas_sin_ciclo_oi = $row_refra['esferas_sin_ciclo_oi'] ?? '';
$cilindros_sin_ciclo_oi = $row_refra['cilindros_sin_ciclo_oi'] ?? '';
$eje_sin_ciclo_oi = $row_refra['eje_sin_ciclo_oi'] ?? '';
$add_sin_ciclo_oi = $row_refra['add_sin_ciclo_oi'] ?? '';
$dip_sin_ciclo_oi = $row_refra['dip_sin_ciclo_oi'] ?? '';
$prisma_sin_ciclo_oi = !empty($row_refra['prisma_sin_ciclo_oi']) ? utf8_decode('Sí') : 'No';

$detalle_ref_subjetiv_sin = $row_refra['detalle_ref_subjetiv_sin'] ?? '';

$pdf->SetFont('Arial', '', 10);
$pdf->SetX($startX);
$pdf->Cell(40, 5, '', 1, 0, 'C', true);
$pdf->Cell(40, 5, 'OD', 1, 0, 'C', true);
$pdf->Cell(40, 5, 'OI', 1, 1, 'C', true);

$pdf->SetX($startX);
$pdf->Cell(40, 5, 'Esfera', 1, 0, 'C');
$pdf->Cell(40, 5, $esferas_sin_ciclo_od, 1, 0, 'C');
$pdf->Cell(40, 5, $esferas_sin_ciclo_oi, 1, 1, 'C');

$pdf->SetX($startX);
$pdf->Cell(40, 5, 'Cilindro', 1, 0, 'C');
$pdf->Cell(40, 5, $cilindros_sin_ciclo_od, 1, 0, 'C');
$pdf->Cell(40, 5, $cilindros_sin_ciclo_oi, 1, 1, 'C');

$pdf->SetX($startX);
$pdf->Cell(40, 5, 'Eje', 1, 0, 'C');
$pdf->Cell(40, 5, $eje_sin_ciclo_od, 1, 0, 'C');
$pdf->Cell(40, 5, $eje_sin_ciclo_oi, 1, 1, 'C');

$pdf->SetX($startX);
$pdf->Cell(40, 5, 'ADD', 1, 0, 'C');
$pdf->Cell(40, 5, $add_sin_ciclo_od, 1, 0, 'C');
$pdf->Cell(40, 5, $add_sin_ciclo_oi, 1, 1, 'C');

$pdf->SetX($startX);
$pdf->Cell(40, 5, 'DIP', 1, 0, 'C');
$pdf->Cell(40, 5, $dip_sin_ciclo_od, 1, 0, 'C');
$pdf->Cell(40, 5, $dip_sin_ciclo_oi, 1, 1, 'C');

$pdf->SetX($startX);
$pdf->Cell(40, 5, 'Prisma', 1, 0, 'C');
$pdf->Cell(40, 5, $prisma_sin_ciclo_od, 1, 0, 'C');
$pdf->Cell(40, 5, $prisma_sin_ciclo_oi, 1, 1, 'C');

$pdf->Ln(2);
$pdf->Cell(0, 9, utf8_decode('Detalle de Subjetiva Sin Cicloplégico'), 0, 1, 'C', true);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, 5, utf8_decode($detalle_ref_subjetiv_sin), 1, 'J', false);

$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 9, utf8_decode('Subjetiva Con Cicloplégico'), 0, 1, 'C', true);

$esferas_con_ciclo_od = $row_refra['esferas_con_ciclo_od'] ?? '';
$cilindros_con_ciclo_od = $row_refra['cilindros_con_ciclo_od'] ?? '';
$eje_con_ciclo_od = $row_refra['eje_con_ciclo_od'] ?? '';
$add_con_ciclo_od = $row_refra['add_con_ciclo_od'] ?? '';
$dip_con_ciclo_od = $row_refra['dip_con_ciclo_od'] ?? '';
$prisma_con_ciclo_od = !empty($row_refra['prisma_con_ciclo_od']) ? utf8_decode('Sí') : 'No';

$esferas_con_ciclo_oi = $row_refra['esferas_con_ciclo_oi'] ?? '';
$cilindros_con_ciclo_oi = $row_refra['cilindros_con_ciclo_oi'] ?? '';
$eje_con_ciclo_oi = $row_refra['eje_con_ciclo_oi'] ?? '';
$add_con_ciclo_oi = $row_refra['add_con_ciclo_oi'] ?? '';
$dip_con_ciclo_oi = $row_refra['dip_con_ciclo_oi'] ?? '';
$prisma_con_ciclo_oi = !empty($row_refra['prisma_con_ciclo_oi']) ? utf8_decode('Sí') : 'No';

$pdf->SetFont('Arial', '', 10);
$pdf->SetX($startX);
$pdf->Cell(40, 5, '', 1, 0, 'C', true);
$pdf->Cell(40, 5, 'OD', 1, 0, 'C', true);
$pdf->Cell(40, 5, 'OI', 1, 1, 'C', true);

$pdf->SetX($startX);
$pdf->Cell(40, 5, 'Esfera', 1, 0, 'C');
$pdf->Cell(40, 5, $esferas_con_ciclo_od, 1, 0, 'C');
$pdf->Cell(40, 5, $esferas_con_ciclo_oi, 1, 1, 'C');

$pdf->SetX($startX);
$pdf->Cell(40, 5, 'Cilindro', 1, 0, 'C');
$pdf->Cell(40, 5, $cilindros_con_ciclo_od, 1, 0, 'C');
$pdf->Cell(40, 5, $cilindros_con_ciclo_oi, 1, 1, 'C');

$pdf->SetX($startX);
$pdf->Cell(40, 5, 'Eje', 1, 0, 'C');
$pdf->Cell(40, 5, $eje_con_ciclo_od, 1, 0, 'C');
$pdf->Cell(40, 5, $eje_con_ciclo_oi, 1, 1, 'C');

$pdf->SetX($startX);
$pdf->Cell(40, 5, 'ADD', 1, 0, 'C');
$pdf->Cell(40, 5, $add_con_ciclo_od, 1, 0, 'C');
$pdf->Cell(40, 5, $add_con_ciclo_oi, 1, 1, 'C');

$pdf->SetX($startX);
$pdf->Cell(40, 5, 'DIP', 1, 0, 'C');
$pdf->Cell(40, 5, $dip_con_ciclo_od, 1, 0, 'C');
$pdf->Cell(40, 5, $dip_con_ciclo_oi, 1, 1, 'C');

$pdf->SetX($startX);
$pdf->Cell(40, 5, 'Prisma', 1, 0, 'C');
$pdf->Cell(40, 5, $prisma_con_ciclo_od, 1, 0, 'C');
$pdf->Cell(40, 5, $prisma_con_ciclo_oi, 1, 1, 'C');

$pdf->Ln(2);

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 9, utf8_decode('Agudeza Visual Intermedia y Cercana'), 0, 1, 'C', true);

$av_intermedia_od = $row_refra['av_intermedia_od'] ?? '';
$av_cercana_sin_corr_od = $row_refra['av_cercana_sin_corr_od'] ?? '';
$av_cercana_con_corr_od = $row_refra['av_cercana_con_corr_od'] ?? '';
$av_intermedia_oi = $row_refra['av_intermedia_oi'] ?? '';
$av_cercana_sin_corr_oi = $row_refra['av_cercana_sin_corr_oi'] ?? '';
$av_cercana_con_corr_oi = $row_refra['av_cercana_con_corr_oi'] ?? '';

$pdf->SetFont('Arial', '', 10);
$pdf->SetX($startX);
$pdf->Cell(40, 5, '', 1, 0, 'C', true);
$pdf->Cell(40, 5, 'OD', 1, 0, 'C', true);
$pdf->Cell(40, 5, 'OI', 1, 1, 'C', true);

$pdf->SetX($startX);
$pdf->Cell(40, 5, 'Intermedia', 1, 0, 'C');
$pdf->Cell(40, 5, $av_intermedia_od, 1, 0, 'C');
$pdf->Cell(40, 5, $av_intermedia_oi, 1, 1, 'C');

$pdf->SetX($startX);
$pdf->Cell(40, 5, 'Cercana sin correc.', 1, 0, 'C');
$pdf->Cell(40, 5, $av_cercana_sin_corr_od, 1, 0, 'C');
$pdf->Cell(40, 5, $av_cercana_sin_corr_oi, 1, 1, 'C');

$pdf->SetX($startX);
$pdf->Cell(40, 5, 'Cercana con correc.', 1, 0, 'C');
$pdf->Cell(40, 5, $av_cercana_con_corr_od, 1, 0, 'C');
$pdf->Cell(40, 5, $av_cercana_con_corr_oi, 1, 1, 'C');

$pdf->Ln(2);

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 9, utf8_decode('Refracción de Cerca'), 0, 1, 'C', true);

$esf_cerca_od = $row_refra['esf_cerca_od'] ?? '';
$cil_cerca_od = $row_refra['cil_cerca_od'] ?? '';
$eje_cerca_od = $row_refra['eje_cerca_od'] ?? '';
$prisma_cerca_od = !empty($row_refra['prisma_cerca_od']) ? utf8_decode('Sí') : 'No';
$esf_cerca_oi = $row_refra['esf_cerca_oi'] ?? '';
$cil_cerca_oi = $row_refra['cil_cerca_oi'] ?? '';
$eje_cerca_oi = $row_refra['eje_cerca_oi'] ?? '';
$prisma_cerca_oi = !empty($row_refra['prisma_cerca_oi']) ? utf8_decode('Sí') : 'No';
$dip_cerca_od = $row_refra['dip_cerca_od'] ?? '';
$dip_cerca_oi = $row_refra['dip_cerca_oi'] ?? '';

$pdf->SetFont('Arial', '', 10);
$pdf->SetX($startX);
$pdf->Cell(40, 5, '', 1, 0, 'C', true);
$pdf->Cell(40, 5, 'OD', 1, 0, 'C', true);
$pdf->Cell(40, 5, 'OI', 1, 1, 'C', true);

$pdf->SetX($startX);
$pdf->Cell(40, 5, 'Esfera', 1, 0, 'C');
$pdf->Cell(40, 5, $esf_cerca_od, 1, 0, 'C');
$pdf->Cell(40, 5, $esf_cerca_oi, 1, 1, 'C');

$pdf->SetX($startX);
$pdf->Cell(40, 5, 'Cilindro', 1, 0, 'C');
$pdf->Cell(40, 5, $cil_cerca_od, 1, 0, 'C');
$pdf->Cell(40, 5, $cil_cerca_oi, 1, 1, 'C');

$pdf->SetX($startX);
$pdf->Cell(40, 5, 'Eje', 1, 0, 'C');
$pdf->Cell(40, 5, $eje_cerca_od, 1, 0, 'C');
$pdf->Cell(40, 5, $eje_cerca_oi, 1, 1, 'C');

$pdf->SetX($startX);
$pdf->Cell(40, 5, 'DIP', 1, 0, 'C');
$pdf->Cell(40, 5, $dip_cerca_od, 1, 0, 'C'); 
$pdf->Cell(40, 5, $dip_cerca_oi, 1, 1, 'C');

$pdf->SetX($startX);
$pdf->Cell(40, 5, 'Prisma', 1, 0, 'C');
$pdf->Cell(40, 5, $prisma_cerca_od, 1, 0, 'C');
$pdf->Cell(40, 5, $prisma_cerca_oi, 1, 1, 'C');

$pdf->Ln(2);
$pdf->Cell(0, 9, utf8_decode('Detalle de Refracción de Cerca'), 0, 1, 'C', true);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, 5, utf8_decode($row_refra['detalle_ref_subjetiv'] ?? ''), 1, 'J', false);

$pdf->Ln(10);

$pdf->Ln(12);
if (!empty($firma) && file_exists('../../imgfirma/' . $firma)) {
    $imgWidth = 40;
    $imgX = ($pdf->GetPageWidth() - $imgWidth) / 2;
    $pdf->Image('../../imgfirma/' . $firma, $imgX, $pdf->GetY(), $imgWidth);
    $pdf->Ln(22);
}
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 5, utf8_decode(trim($pre_med . ' ' . $app_med . ' ' . $apm_med . ' ' . $nom_med)), 0, 1, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(0, 5, utf8_decode($cargp .' Céd. Prof. ' . $ced_p), 0, 1, 'C');

$pdf->Output();