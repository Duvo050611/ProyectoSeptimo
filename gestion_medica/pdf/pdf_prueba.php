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

$id_prueba = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_prueba > 0) {
    $sql_prueba = "SELECT * FROM pruebas_oftalmologicas WHERE id = $id_prueba LIMIT 1";
} else {
    $sql_prueba = "SELECT * FROM pruebas_oftalmologicas WHERE id_atencion = $id_atencion ORDER BY id DESC LIMIT 1";
}
$result_prueba = $conexion->query($sql_prueba);
$row_prueba = $result_prueba->fetch_assoc();

if (!$row_prueba) {
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
        $(document).ready(function() {
            swal({
                title: "NO EXISTE PRUEBA PARA ESTE PACIENTE", 
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
$tipo_prueba = $row_prueba['tipo_prueba'] ?? '';
$resultado = $row_prueba['resultado'] ?? '';
$fecha_consulta = $row_prueba['fecha_consulta'] ?? '';
$observaciones = $row_prueba['observaciones'] ?? '';

$estrabismo_od = $row_prueba['estrabismo_od'] ?? '';
$movimientos_od = $row_prueba['movimientos_od'] ?? '';
$convergencia_od = $row_prueba['convergencia_od'] ?? '';
$prueba_cover_od = $row_prueba['prueba_cover_od'] ?? '';
$vision_estereo_od = $row_prueba['vision_estereo_od'] ?? '';
$worth_od = $row_prueba['worth_od'] ?? '';
$schirmer_od = $row_prueba['schirmer_od'] ?? '';
$trpl_od = $row_prueba['trpl_od'] ?? '';
$fluoresceina_od = $row_prueba['fluoresceina_od'] ?? '';
$contraste_od = $row_prueba['contraste_od'] ?? '';
$ishihara_od = $row_prueba['ishihara_od'] ?? '';
$farnsworth_od = $row_prueba['farnsworth_od'] ?? '';
$amsler_od = $row_prueba['amsler_od'] ?? '';

$estrabismo_oi = $row_prueba['estrabismo_oi'] ?? '';
$movimientos_oi = $row_prueba['movimientos_oi'] ?? '';
$convergencia_oi = $row_prueba['convergencia_oi'] ?? '';
$prueba_cover_oi = $row_prueba['prueba_cover_oi'] ?? '';
$vision_estereo_oi = $row_prueba['vision_estereo_oi'] ?? '';
$worth_oi = $row_prueba['worth_oi'] ?? '';
$schirmer_oi = $row_prueba['schirmer_oi'] ?? '';
$trpl_oi = $row_prueba['trpl_oi'] ?? '';
$fluoresceina_oi = $row_prueba['fluoresceina_oi'] ?? '';
$contraste_oi = $row_prueba['contraste_oi'] ?? '';
$ishihara_oi = $row_prueba['ishihara_oi'] ?? '';
$farnsworth_oi = $row_prueba['farnsworth_oi'] ?? '';
$amsler_oi = $row_prueba['amsler_oi'] ?? '';

$ojo_preferente = $row_prueba['ojo_preferente'] ?? '';
$detalle_prueba = $row_prueba['detalle_prueba'] ?? '';
$fecha_prueba = $row_hist['fecha'] ?? date('Y-m-d H:i:s');
$GLOBALS['fecha_prueba'] = $fecha_prueba;

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
        $this->Cell(0, 12, utf8_decode('NOTA DE PRUEBAS OFTALMOLÓGICAS'), 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 6, utf8_decode('Fecha: ') . date('d/m/Y H:i', strtotime($GLOBALS['fecha_prueba'])), 0, 1, 'R');
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
    function SetWidths($w)
{
    // Set the array of column widths
    $this->widths = $w;
}
function Row($data)
{
    $nb = 0;
    for($i=0;$i<count($data);$i++)
        $nb = max($nb, $this->NbLines($this->widths[$i],$data[$i]));
    $h = 7 * $nb;
    $this->CheckPageBreak($h);
    for($i=0;$i<count($data);$i++)
    {
        $w = $this->widths[$i];
        $a = 'L';
        $x = $this->GetX();
        $y = $this->GetY();
        $this->Rect($x,$y,$w,$h);
        $this->MultiCell($w,7,$data[$i],0,$a);
        $this->SetXY($x+$w,$y);
    }
    $this->Ln($h);
}
function CheckPageBreak($h)
{
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}
function NbLines($w,$txt)
{
    $cw = &$this->CurrentFont['cw'];
    if($w==0)
        $w = $this->w-$this->rMargin-$this->x;
    $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
    $s = str_replace("\r",'',$txt);
    $nb = strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep = -1;
    $i = 0;
    $j = 0;
    $l = 0;
    $nl = 1;
    while($i<$nb)
    {
        $c = $s[$i];
        if($c=="\n")
        {
            $i++;
            $sep = -1;
            $j = $i;
            $l = 0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep = $i;
        $l += $cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i = $sep+1;
            $sep = -1;
            $j = $i;
            $l = 0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
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
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 8, utf8_decode('Datos de la Prueba Oftalmológica'), 0, 1, 'L', true);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(50, 7, 'Tipo de Prueba:', 1, 0, 'L');
$pdf->Cell(60, 7, utf8_decode($row_prueba['tipo_prueba']), 1, 0, 'L');
$pdf->Cell(30, 7, 'Resultado:', 1, 0, 'L');
$pdf->Cell(0, 7, utf8_decode($row_prueba['resultado']), 1, 1, 'L');

$pdf->Cell(50, 7, 'Fecha de Consulta:', 1, 0, 'L');
$pdf->Cell(60, 7, date('d/m/Y H:i', strtotime($row_prueba['fecha_consulta'])), 1, 0, 'L');
$pdf->Cell(30, 7, 'Ojo Preferente:', 1, 0, 'L');
$pdf->Cell(0, 7, utf8_decode($row_prueba['ojo_preferente']), 1, 1, 'L');

$pdf->Cell(50, 7, 'Observaciones:', 1, 0, 'L');
$pdf->Cell(0, 7, utf8_decode($row_prueba['observaciones']), 1, 1, 'L');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 8, utf8_decode('Resultados OD (Ojo Derecho)'), 0, 1, 'C', true); // Cambia 'L' por 'C'

$pdf->SetFont('Arial', '', 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->SetWidths([60, 80]); 
$pdf->SetX(($pdf->GetPageWidth() - 140) / 2); 
$pdf->Row([utf8_decode('Parámetro'), utf8_decode('Valor')]);$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row(['Estrabismo', utf8_decode($row_prueba['estrabismo_od'])]);
$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row(['Movimientos', utf8_decode($row_prueba['movimientos_od'])]);
$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row(['Convergencia', utf8_decode($row_prueba['convergencia_od'])]);
$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row(['Prueba Cover', utf8_decode($row_prueba['prueba_cover_od'])]);
$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row([utf8_decode('Visión Estéreo'), utf8_decode($row_prueba['vision_estereo_od'])]);
$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row(['Worth', utf8_decode($row_prueba['worth_od'])]);
$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row(['Schirmer', utf8_decode($row_prueba['schirmer_od'])]);
$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row(['TRPL', utf8_decode($row_prueba['trpl_od'])]);
$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row([utf8_decode('Fluoresceína'), utf8_decode($row_prueba['fluoresceina_od'])]);
$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row(['Contraste', utf8_decode($row_prueba['contraste_od'])]);
$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row(['Ishihara', utf8_decode($row_prueba['ishihara_od'])]);
$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row(['Farnsworth', utf8_decode($row_prueba['farnsworth_od'])]);
$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row(['Amsler', utf8_decode($row_prueba['amsler_od'])]);

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 8, utf8_decode('Resultados OI (Ojo Izquierdo)'), 0, 1, 'C', true); // Cambia 'L' por 'C'

$pdf->SetFont('Arial', '', 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->SetWidths([60, 80]);
$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row([utf8_decode('Parámetro'), utf8_decode('Valor')]);$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row(['Estrabismo', utf8_decode($row_prueba['estrabismo_oi'])]);
$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row(['Movimientos', utf8_decode($row_prueba['movimientos_oi'])]);
$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row(['Convergencia', utf8_decode($row_prueba['convergencia_oi'])]);
$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row(['Prueba Cover', utf8_decode($row_prueba['prueba_cover_oi'])]);
$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row([utf8_decode('Visión Estéreo'), utf8_decode($row_prueba['vision_estereo_oi'])]);
$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row(['Worth', utf8_decode($row_prueba['worth_oi'])]);
$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row(['Schirmer', utf8_decode($row_prueba['schirmer_oi'])]);
$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row(['TRPL', utf8_decode($row_prueba['trpl_oi'])]);
$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row([utf8_decode('Fluoresceína'), utf8_decode($row_prueba['fluoresceina_oi'])]);
$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row(['Contraste', utf8_decode($row_prueba['contraste_oi'])]);
$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row(['Ishihara', utf8_decode($row_prueba['ishihara_oi'])]);
$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row(['Farnsworth', utf8_decode($row_prueba['farnsworth_oi'])]);
$pdf->SetX(($pdf->GetPageWidth() - 140) / 2);
$pdf->Row(['Amsler', utf8_decode($row_prueba['amsler_oi'])]);
// --- DETALLE ---
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 8, utf8_decode('Detalle de la Prueba'), 0, 1, 'L', true);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, 7, utf8_decode($row_prueba['detalle_prueba']), 1, 'J', false);

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