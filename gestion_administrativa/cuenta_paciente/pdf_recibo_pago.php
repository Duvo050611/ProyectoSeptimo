<?php

//use PDF as GlobalPDF;

require '../../../fpdf/fpdf.php';
include '../../../conexionbd.php';
$id_atencion = @$_GET['id_atencion'];
$id_exp = @$_GET['id_exp'];
$id_datfin=@$_GET['id_datfin'];
//$id_usua=@$_GET['id_usua'];


mysqli_set_charset($conexion, "utf8");

class PDF extends FPDF
{
  function Header()
  {

   
    $this->Image('../../../imagenes/logo PDF.jpg', 10, 10, 28, 30);
    $this->SetDrawColor(0, 0, 0);
    $this->Line(2, 8, 209, 8 );
    $this->Line(2, 8, 1, 130);
    $this->Line(1, 130, 209, 130);
    $this->Line(209, 8, 209, 130);
    $this->SetFont('Arial', 'B', 14);
    $this->Cell(200, 8, 'Sanatorio Venecia', 0, 0, 'C');
    $this->Ln(10);
    $this->SetDrawColor(0, 0, 0);
    $this->Line(50, 18, 170, 18);
    $this->Line(50, 19, 170, 19);
    $this->SetFont('Arial', '', 10);
    $this->Cell(200, 8, 'PASEO TOLLOCAN NO. 113 COL. UNIVERSIDAD', 0, 0, 'C');
    $this->Ln(5);
    $this->Cell(200, 8, utf8_decode('C.P. 50130 TOLUCA, MÉX'), 0, 0, 'C');
    $this->Ln(5);
    $this->Cell(200, 8, 'TEL.: (01 722) 280 5672', 0, 0, 'C');
    $this->Ln(5);
    $this->Cell(200, 8, 'www.sanatoriovenecia.com.mx', 0, 0, 'C');
    $this->Ln(10);
    $this->Image('../../../imagenes/logo PDF 2.jpg', 165, 20, 40, 20);
  }
  function Footer()
  {
    $this->Ln(8);
    $this->SetY(-15);
    $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
  }
}

$sql_dat_ing = "SELECT * from dat_ingreso where id_atencion = $id_atencion";
$result_dat_ing = $conexion->query($sql_dat_ing);

while ($row_dat_ing = $result_dat_ing->fetch_assoc()) {
  $fecha = $row_dat_ing['fecha'];
  $motivo_atn = $row_dat_ing['motivo_atn'];
  $especialidad = $row_dat_ing['especialidad'];
  $id_usua = $row_dat_ing['id_usua'];
  $cajero = $row_dat_ing['cajero'];
}

$sql_pac = "SELECT p.papell, p.nom_pac,p.sapell,p.edad,p.sexo,p.Id_exp,p.dir,p.id_edo,p.id_mun,p.tel,p.ocup,p.resp,p.paren,p.tel_resp, p.fecnac FROM paciente p where p.Id_exp = $id_exp";
$result_pac = $conexion->query($sql_pac);

while ($row_pac = $result_pac->fetch_assoc()) {
  $papell = $row_pac['papell'];
  $nom_pac = $row_pac['nom_pac'];
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
  $fecnac = $row_pac['fecnac'];
}

$sql_hab = "SELECT * from cat_camas where id_atencion=$id_atencion";
$result_hab = $conexion->query($sql_hab);

while ($row_hab = $result_hab->fetch_assoc()) {
  $numcam = $row_hab['num_cama'];
  $tipo = $row_hab['tipo'];
}
if(isset($numcam)){
 $numcam=$numcam;
}else{
  $numcam='SIN HABITACIÓN';
}

$sql_fin = "SELECT * from cta_pagada where id_atencion=$id_atencion ";
$result_fin = $conexion->query($sql_fin);

while ($row_fin = $result_fin->fetch_assoc()) {
  $total = $row_fin['total'];
  $usuario=$row_fin['id_usua'];
}

$sql_reg_usrs = "SELECT * from reg_usuarios where id_usua=$usuario";
$result_reg_usrs = $conexion->query($sql_reg_usrs);

while ($row_reg_usrs = $result_reg_usrs->fetch_assoc()) {
  $user_pre = $row_reg_usrs['pre'];
  $user_papell = $row_reg_usrs['papell'];
  $user_sapell = $row_reg_usrs['sapell'];
  $user_nombre = $row_reg_usrs['nombre'];
}

date_default_timezone_set('America/Guatemala');
$fecha_actual = date("d-m-Y H:i:s");

 explode("-", $fecha_actual);
 $ano  = date("Y") ;
  $mes= date("m") ;
  $dia  = date("d") ;

   if ($mes==1) {
    $mes='ENERO';
  }

 if ($mes==2) {
    $mes='FEBRERO';
  }
   if ($mes==3) {
    $mes='MARZO';
  }
 if ($mes==4) {
    $mes='ABRIL';
  }
  if ($mes==5) {
    $mes='MAYO';
  }
   if ($mes==6) {
    $mes='JUNIO';
  }
   if ($mes==7) {
    $mes='JULIO';
  }
   if ($mes==8) {
    $mes='AGOSTO';
  }
   if ($mes==9) {
    $mes='SEPTIEMBRE';
  }
   if ($mes==10) {
    $mes='OCTUBRE';
  }
   if ($mes==11) {
    $mes='NOVIEMBRE';
  }
   if ($mes==12) {
    $mes='DICIEMBRE';
  }

$pdf = new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);
$pdf->SetX(30);
$pdf->MultiCell(150, 6, utf8_decode('RECIBO DE PAGO'),0, 'C');


$pdf->SetFont('Arial', '', 8);


$pdf->Ln(2);
$pdf->Cell(40, 6, 'NOMBRE DEL PACIENTE: ', 0, 'L');
$pdf->Cell(90, 6, utf8_decode($papell . ' ' . $sapell . ' ' . $nom_pac),'B', 'L');
$pdf->Cell(40, 6, 'FECHA DE NACIMIENTO: ', 0, 'L');
$fecnac=date_create($fecnac);
$pdf->Cell(20, 6, utf8_decode(date_format($fecnac,"d-m-Y")),'B', 'L');

$sql_reg_usrs = "SELECT * from reg_usuarios where id_usua=$id_usua";
$result_reg_usrs = $conexion->query($sql_reg_usrs);

while ($row_reg_usrs = $result_reg_usrs->fetch_assoc()) {
  $user_pre = $row_reg_usrs['pre'];
  $user_papell = $row_reg_usrs['papell'];
  $user_sapell = $row_reg_usrs['sapell'];
  $user_nombre = $row_reg_usrs['nombre'];
}

$pdf->Ln(6);
$pdf->Cell(20, 6, utf8_decode('HABITACIÓN: '), 0, 'L');
$pdf->Cell(25, 6, utf8_decode($numcam), 'B', 'L');
$pdf->SetX(60);
$pdf->Cell(25, 6, utf8_decode('DIAGNÓSTICO: '), 0, 'L');
$pdf->Cell(115, 6,utf8_decode($motivo_atn), 'B', 'L');
$pdf->Ln(8);
$pdf->Cell(32, 6, 'LA CANTIDAD DE :', 0, 'L');
$pdf->Cell(32, 6,utf8_decode("$ ".number_format($total,2)) , 'B', 'L');
$pdf->SetX(90);
$date=date_create($fecha);
$pdf->Cell(32, 6, 'FECHA DE INGRESO : ', 0, 'L');
$pdf->Cell(78, 6, date_format($date,"d-m-Y H:i"), 'B', 'L');
$pdf->Ln(8);
$pdf->Cell(32, 6, 'MEDICO TRATANTE', 0, 'L');
$pdf->Cell(159, 6,utf8_decode($user_pre.". ".$user_nombre." ".$user_papell." ".$user_sapell), 'B', 'L');

$pdf->Ln(8);
$pdf->Cell(0, 5, 'TOLUCA, MEXICO A, '. $dia.' '.$mes.' '.$ano , 0, 1, 'C');
$sql_reg_usrs = "SELECT * from reg_usuarios where id_usua=$cajero";
$result_reg_usrs = $conexion->query($sql_reg_usrs);

while ($row_reg_usrs = $result_reg_usrs->fetch_assoc()) {
  $user_pre = $row_reg_usrs['pre'];
  $user_papell = $row_reg_usrs['papell'];
  $user_sapell = $row_reg_usrs['sapell'];
  $user_nombre = $row_reg_usrs['nombre'];
}
$pdf->Ln(10);
$pdf->SetX(25);
$pdf->Cell(32, 6, 'PAGO ', 0, 'L');
$pdf->SetX(155);
$pdf->Cell(32, 6,utf8_decode("AUTORIZÁ"), 0, 'L');
$pdf->Ln(20);
$pdf->Cell(50, 4, '', 'B', 0, 'L');
$pdf->SetX(140);
$pdf->Cell(50, 4, '', 'B', 0, 'L');
$pdf->Ln(6);
$pdf->SetX(12);
$pdf->Cell(100, 4,utf8_decode("NOMBRE COMPLETO Y FIRMA"), 0, 0, 'L');
$pdf->SetX(142);
$pdf->Cell(32, 6, utf8_decode(' '.$user_pre.' '.$user_papell.' '.$user_sapell.' '.$user_nombre), 0, 'L');
$pdf->Ln(250);
$pdf->Output();
