<?php
require '../../fpdf/fpdf.php';
include '../../conexionbd.php';
$id_atencion = @$_GET['id_atencion'];
$id_irrigacion = @$_GET['id_irrigacion'];
$fechar = @$_GET['fechar'];
$id_exp = @$_GET['id_exp'];
mysqli_set_charset($conexion, "utf8");

class PDF extends FPDF
{
  function Header()
  {
    $id = @$_GET['id_atencion'];
    $id_med = @$_GET['id_med'];
    include '../../conexionbd.php';

    $id = @$_GET['id_atencion'];;
       include '../../conexionbd.php';
$resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
while($f = mysqli_fetch_array($resultado)){
       $bas=$f['img_ipdf'];

    $this->Image("../../configuracion/admin/img2/".$bas, 7, 15, 50, 20);
    $this->Image("../../configuracion/admin/img3/".$f['img_cpdf'],60,15, 100, 25);
    $this->Image("../../configuracion/admin/img4/".$f['img_dpdf'], 161, 18, 45, 20);
}
    $this->Ln(32);
   
  }
  function Footer()
  {
    $this->Ln(8);
    $this->SetY(-15);
    $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}' ), 0, 0, 'C');
    $this->Cell(0, 10, utf8_decode('SIMA-027'), 0, 1, 'R');
  }
 
}



$sql_pac = "SELECT p.papell, p.nom_pac,p.fecnac,p.sapell,p.edad,p.sexo,p.Id_exp,p.dir,p.id_edo,p.id_mun,p.tel,p.ocup,p.resp,p.paren,p.tel_resp, p.religion, p.folio FROM paciente p, dat_ingreso di where di.id_atencion = $id_atencion and di.Id_exp=p.Id_exp";
$result_pac = $conexion->query($sql_pac);

while ($row_pac = $result_pac->fetch_assoc()) {
  $papell = $row_pac['papell'];
  $nom_pac = $row_pac['nom_pac'];
  $fecnac = $row_pac['fecnac'];
  $sapell = $row_pac['sapell'];
  $edad = $row_pac['edad'];
  $sexo = $row_pac['sexo'];
  $Id_exp = $row_pac['Id_exp'];
  $dir = $row_pac['dir'];
  $id_edo = $row_pac['id_edo'];
  $id_mun = $row_pac['id_mun'];
  $tel = $row_pac['tel'];
  $ocup = $row_pac['ocup'];
  $resp = $row_pac['resp'];
  $paren = $row_pac['paren'];
  $tel_resp = $row_pac['tel_resp'];
  $religion = $row_pac['religion'];
    $folio = $row_pac['folio'];
}



$sql_dati = "SELECT * FROM dat_ingreso where id_atencion = $id_atencion";
$result_dati = $conexion->query($sql_dati);

while ($row_dati = $result_dati->fetch_assoc()) {
    $motivo_atn = $row_dati['motivo_atn'];
    $tipo_a = $row_dati['tipo_a'];
    $fecha_ing = $row_dati['fecha'];
}



$pdf = new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetTextColor(43, 45, 127);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetX(50);
$pdf->Cell(120, 5, utf8_decode('HOJA DE IRRIGACIÓN'), 0, 0, 'C');
$pdf->SetFont('Arial', '', 8);
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("d/m/Y H:i");
$pdf->Cell(35, 5, 'FECHA: ' . $fecha_actual, 0, 1, 'R');
$pdf->Ln(5);

$pdf->SetDrawColor(43, 45, 127);
$pdf->Line(8, 50, 207, 50);
$pdf->Line(8, 50, 8, 280);
$pdf->Line(207, 50, 207, 280);
$pdf->Line(8, 280, 207, 280);


$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(17, 3, 'Servicio: ', 0, 'L');  
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(124, 3, utf8_decode($tipo_a) , 'B', 'L');
$pdf->SetFont('Arial', 'B', 8);
$date=date_create($fecha_ing);
$pdf->Cell(28, 3, utf8_decode('Fecha de ingreso:'),0 , 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(25, 3, date_format($date,'d-m-Y H:i:s'), 'B', 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(20, 3, utf8_decode('Expediente: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(8, 3, utf8_decode($folio), 'B', 0, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(40, 3, 'Paciente: ', 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(126, 3, utf8_decode($papell . ' ' . $sapell . ' ' . $nom_pac), 'B', 'L');
$pdf->SetFont('Arial', 'B', 8);

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 8);
$date=date_create($fecnac);
$pdf->Cell(31, 3, 'Fecha de nacimiento: ', 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(20, 3, date_format($date,"d/m/Y"), 'B', 'C');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(10, 3, 'Edad: ', 0, 'L');



$pdf->SetFont('Arial', '', 8);
$pdf->Cell(12, 3, utf8_decode($edad),'B', 'C');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(20, 3, utf8_decode(' Ocupación: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(51, 3,  utf8_decode($ocup), 'B', 'L');

$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(16, 3, utf8_decode('Teléfono: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(34, 3,  utf8_decode($tel), 'B', 'L');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(13, 3, utf8_decode('Género: '), 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(22, 3,  $sexo, 'B', 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(18, 3, utf8_decode('Domicilio: '), 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(141, 3, utf8_decode($dir), 'B', 'L');
$pdf->Ln(4);

$sql_diag = "SELECT * from diag_pac where id_exp=$id_atencion ORDER by id_diag DESC LIMIT 1";

$result_diag = $conexion->query($sql_diag);

while ($row_diag = $result_diag->fetch_assoc()) {
   $diag_paciente = $row_diag['diag_paciente'];
}
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(20, 4, utf8_decode('Diagnóstico: '), 0, 'L');
$pdf->Cell(175, 4, utf8_decode($diag_paciente), 'B', 0, 'L');


$pdf->Ln(6);

$pdf->SetFont('Arial', 'B', 9);
$pdf->MultiCell(195, 6, utf8_decode('Hoja de irrigación'), 0, 'C');

$pdf->SetFont('Arial', 'B', 7.5);
$pdf->Cell(14,5, utf8_decode('No. Bolsa'),1,0,'C');
$pdf->Cell(65,5, utf8_decode('Solución'),1,0,'C');
$pdf->Cell(18,5, utf8_decode('Hora entrada'),1,0,'C');
$pdf->Cell(17,5, utf8_decode('Hora salida'),1,0,'C');
$pdf->Cell(23,5, utf8_decode('Volumen entrada'),1,0,'C');
$pdf->Cell(22,5, utf8_decode('Volumen salida'),1,0,'C');
$pdf->Cell(14,5, utf8_decode('Balance'),1,0,'C');
$pdf->Cell(22,5, utf8_decode('Turno'),1,0,'C');

$cis = $conexion->query("select * from irrigacion where id_atencion=$id_atencion AND fecha_reporte='$fechar' ORDER BY id_irrigacion DESC") or die($conexion->error);
while ($cis_s = $cis->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 8);
$pdf->Ln(5);
$date=date_create($cis_s['fecha_mat']);
$pdf->Cell(14,5, utf8_decode($cis_s['bolsa']),1,0,'C');
 $pdf->SetFont('Arial', '', 8);
$pdf->Cell(65,5, utf8_decode($cis_s['solucion'].' ML'),1,0,'C');
$pdf->Cell(18,5, utf8_decode($cis_s['hora_entrada']),1,0,'C');
$pdf->Cell(17,5, utf8_decode($cis_s['hora_salida']),1,0,'C');
$pdf->Cell(23,5, utf8_decode($cis_s['vol_entrada']),1,0,'C');
$pdf->Cell(22,5, utf8_decode($cis_s['vol_salida']),1,0,'C');
$pdf->Cell(14,5, utf8_decode($cis_s['balance']),1,0,'C');


if($cis_s['hora_salida']=='8:00:00' ||$cis_s['hora_salida']=='9:00:00' || $cis_s['hora_salida']=='10:00:00' || $cis_s['hora_salida']=='11:00:00'|| $cis_s['hora_salida']=='12:00:00'|| $cis_s['hora_salida']=='13:00:00' || $hora_salida=='14:00:00'){
$turno="MATUTINO";
} else if ($cis_s['hora_salida']=='15:00:00' || $cis_s['hora_salida']=='16:00:00' || $cis_s['hora_salida']=='17:00:00'|| $cis_s['hora_salida']=='18:00:00'|| $cis_s['hora_salida']=='19:00:00' || $cis_s['hora_salida']=='20:00:00' || $cis_s['hora_salida']=='21:00:00') {
  $turno="VESPERTINO";
}else if ($cis_s['hora_salida']=='22:00:00' || $cis_s['hora_salida']=='23:00:00' || $cis_s['hora_salida']=='24:00:00'|| $cis_s['hora_salida']=='1:00:00'|| $cis_s['hora_salida']=='2:00:00' || $cis_s['hora_salida']=='3:00:00' || $cis_s['hora_salida']=='4:00:00' || $cis_s['hora_salida']=='5:00:00' || $cis_s['hora_salida']=='6:00:00' || $cis_s['hora_salida']=='7:00:00') {
    $turno="NOCTURNO";
}
$pdf->Cell(22,5, utf8_decode($turno),1,0,'C');
}
$pdf->Ln(5.7);
  $pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(114,5, utf8_decode('Total'),1,0,'R');
$ciss = $conexion->query("select SUM(vol_entrada) as entrada,SUM(vol_salida) as salida from irrigacion where id_atencion=$id_atencion AND fecha_reporte='$fechar' ORDER BY id_irrigacion DESC") or die($conexion->error);
while ($cis_suma = $ciss->fetch_assoc()) {
    $v_e=$cis_suma['entrada'];
    $v_s=$cis_suma['salida'];
}
$pdf->Cell(23,5, utf8_decode($v_e),1,0,'C');
$pdf->Cell(22,5, utf8_decode($v_s),1,0,'C');

$pdf->Cell(14,5, utf8_decode($v_e-$v_s),1,0,'C');
$pdf->Ln(30);


$sql_med_id = "SELECT id_usua FROM irrigacion WHERE id_irrigacion=$id_irrigacion";
    $result_med_id = $conexion->query($sql_med_id);

    while ($row_med_id = $result_med_id->fetch_assoc()) {
      $id_med = $row_med_id['id_usua'];
    }
    $sql_med = "SELECT * FROM reg_usuarios WHERE id_usua = $id_med";
    $result_med = $conexion->query($sql_med);

    while ($row_med = $result_med->fetch_assoc()) {
      $nom = $row_med['nombre'];
      $app = $row_med['papell'];
      $apm = $row_med['sapell'];
      $pre = $row_med['pre'];
      $firma = $row_med['firma'];
      $ced_p = $row_med['cedp'];
      $cargp = $row_med['cargp'];
}
  
      $pdf->SetY(-60);
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->Image('../../imgfirma/' . $firma, 95, 250, 30);
    
      $pdf->SetY(264);
      $pdf->Cell(200, 4, utf8_decode($pre . '. ' . $app . ' ' . $apm . ' ' . $nom), 0, 0, 'C');
      $pdf->Ln(4);
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->Cell(200, 4, utf8_decode($cargp . ' ' . 'CÉD. PROF. ' . $ced_p), 0, 0, 'C');
      $pdf->Ln(4);
      $pdf->Cell(200, 4, utf8_decode('NOMBRE Y FIRMA DEL MÉDICO'), 0, 0, 'C');

 $pdf->Output();