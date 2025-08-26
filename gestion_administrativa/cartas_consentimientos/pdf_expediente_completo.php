<?php

use PDF as GlobalPDF;

require '../../fpdf/fpdf.php';
include '../../conexionbd.php';
//require('../../fpdf/MultiCellBlt.php');
$id_exp = @$_GET['id_exp'];
$id_atencion = @$_GET['id_atencion'];

mysqli_set_charset($conexion, "utf8");

class PDF extends FPDF
{
    
  function Header()
  {
      include '../../conexionbd.php';
$resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
while($f = mysqli_fetch_array($resultado)){
       $bas=$f['img_ipdf'];

    $this->Image("../../configuracion/admin/img2/".$bas, 8, 8, 53, 20);
    $this->Image("../../configuracion/admin/img3/".$f['img_cpdf'],59,6, 100, 23);
    $this->Image("../../configuracion/admin/img4/".$f['img_dpdf'], 163, 11, 39, 15);
}
  
    $this->SetFont('Arial', 'B', 25);
    $this->SetTextColor(43, 45, 127);
    $this->Ln(10);
  
    $this->Ln(10);
    $this->SetDrawColor(43, 45, 127);

    $this->SetFont('Arial', '', 8);
   /* $this->Cell(200, 8, utf8_decode('CALLE JOSEFA ORTIZ DE DOMÍNGUEZ #444'), 0, 0, 'C');
    $this->Ln(4);
    $this->Cell(200, 8, utf8_decode('BARRIO COAXUSTENCO, METEPEC, ESTADO DE MÉXICO'), 0, 0, 'C');
    $this->Ln(4);
    $this->Cell(200, 8, 'TEL: (01722) 235-01-75 / 235-02-12 / 902-03-90 / C.P. 52140', 0, 0, 'C');
    $this->Ln(4);
    $this->Cell(200, 8, 'https://medicasanisidro.com/', 0, 0, 'C');
    $this->Ln(10);
    */
    //$this->Image('../../imagenes/en.png', 159, 22, 45, 15);
  }
  function Footer()
  {
    $this->Ln(8);
    $this->SetY(-15);
    $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}' ), 0, 0, 'C');
   
  }
}

$sql_dat_ing = "SELECT * from dat_ingreso where id_atencion = $id_atencion";
$result_dat_ing = $conexion->query($sql_dat_ing);

while ($row_dat_ing = $result_dat_ing->fetch_assoc()) {
  $motivo_atn = $row_dat_ing['motivo_atn'];
  $fecha_ing = $row_dat_ing['fecha'];
  $especialidad = $row_dat_ing['especialidad'];
  $id_usua = $row_dat_ing['id_usua'];
  $tipo_a = $row_dat_ing['tipo_a'];
}



$sql_reg_usrs = "SELECT * from reg_usuarios where id_usua=$id_usua and id_rol=2";
$result_reg_usrs = $conexion->query($sql_reg_usrs);

while ($row_reg_usrs = $result_reg_usrs->fetch_assoc()) {
  $user_pre = $row_reg_usrs['pre'];
  $user_papell = $row_reg_usrs['papell'];
  $user_sapell = $row_reg_usrs['sapell'];
  $user_nombre = $row_reg_usrs['nombre'];
  $user_cedula = $row_reg_usrs['cedp'];
}

$sql_pac = "SELECT p.papell, p.nom_pac,p.sapell,p.sexo,p.Id_exp,p.folio,p.dir,p.id_edo,p.id_mun,p.tel,p.ocup,p.resp,p.paren,p.tel_resp, p.fecnac, p.edad FROM paciente p where p.Id_exp = $id_exp";
$result_pac = $conexion->query($sql_pac);

while ($row_pac = $result_pac->fetch_assoc()) {
  $papell = $row_pac['papell'];
  $nom_pac = $row_pac['nom_pac'];
  $sapell = $row_pac['sapell'];
  $sexo = $row_pac['sexo'];
  $Id_exp = $row_pac['Id_exp'];
  $folio = $row_pac['folio'];
  $dir = $row_pac['dir'];
  $id_edo = $row_pac['id_edo'];
  $id_mun = $row_pac['id_mun'];
  $tel = $row_pac['tel'];
  $ocup = $row_pac['ocup'];
  $resp = $row_pac['resp'];
  $paren = $row_pac['paren'];
  $tel_resp = $row_pac['tel_resp'];
  $fecnac = $row_pac['fecnac'];
  $edad  = $row_pac['edad'];
}



$sql_cam = "SELECT * FROM cat_camas WHERE id_atencion= $id_atencion";
$result_cam = $conexion->query($sql_cam);

while ($row_cam = $result_cam->fetch_assoc()) {
  $num_cam = $row_cam['num_cama'];
}


$sql_mun = "SELECT nombre_m FROM municipios WHERE id_mun = $id_mun";
$result_mun = $conexion->query($sql_mun);

while ($row_mun = $result_mun->fetch_assoc()) {
  $nom_mun = $row_mun['nombre_m'];
}

$pdf = new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();

  
$pdf->SetFont('Arial', 'B', 12);
  $pdf->Ln(5);
$pdf->SetTextColor(43, 45, 127);
$pdf->Cell(196, 9, utf8_decode(' HOJA INICIAL'), 0, 0, 'C');
  $pdf->Ln(5);
  $pdf->SetX(30);
  $pdf->SetFont('Arial', 'B', 8);
$pdf->MultiCell(158, 9.5, utf8_decode('DATOS DEL PACIENTE'), 0, 'C');


$pdf->SetDrawColor(43, 45, 127);
$pdf->Line(8, 5, 205, 5);

$pdf->Line(8, 5, 8, 280);
$pdf->Line(205, 5, 205, 280);
$pdf->Line(8, 280, 205, 280);

$pdf->SetFont('Arial', '', 10);
$pdf->Ln(5);
$pdf->Cell(20, 6, 'PACIENTE: ', 0, 'L');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(172, 5.5, utf8_decode($papell. ' ' .$sapell . ' ' . $nom_pac ), 'B', 'L');
$pdf->Ln(15);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(27, 6, utf8_decode(' EXPEDIENTE: '), 0,0,'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(165, 5, utf8_decode($folio), 'B', 0, 'L');
$pdf->Ln(15);
$pdf->SetFont('Arial', '', 10);
$date=date_create($fecnac);
$pdf->Cell(45, 6, ' FECHA DE NACIMIENTO: ', 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(85, 5.5, date_format($date,"d/m/Y"), 'B', 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(14, 6, ' EDAD: ', 0, 'L');


  $pdf->SetFont('Arial', '', 10);
$pdf->Cell(48, 5, utf8_decode($edad), 'B', 'C');


$pdf->Ln(15);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(21, 6, utf8_decode('DOMICILIO: '), 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(171.5, 5.5, utf8_decode($dir), 'B', 'L');
$pdf->Ln(15);


$pdf->SetFont('Arial', '', 10);
$pdf->Cell(40, 6, utf8_decode('MOTIVO DE INGRESO: '), 0, 'L');  
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(151.5, 5, utf8_decode($motivo_atn) , 'B', 'L');
$pdf->Ln(15);

$d = "SELECT DISTINCT(diag_paciente) as diag_paciente, fecha,id_usua,Id_exp from diag_pac where Id_exp=$id_atencion order by fecha DESC LIMIT 1";
$res = $conexion->query($d);

while ($dr = $res->fetch_assoc()) {
  $diag_paciente = $dr['diag_paciente'];
  $fecha = $dr['fecha'];
  $id_usua1 = $dr['id_usua'];
 $id_exp = $dr['Id_exp'];


$sql_reg_usrs = "SELECT * from reg_usuarios where id_usua=$id_usua1";
$result_reg_usrs = $conexion->query($sql_reg_usrs);

while ($row_reg_usrs = $result_reg_usrs->fetch_assoc()) {
  $user_pred = $row_reg_usrs['pre'];
  $user_papelld = $row_reg_usrs['papell'];
  $user_sapelld = $row_reg_usrs['sapell'];
  $user_nombred = $row_reg_usrs['nombre'];
  $user_cedulad = $row_reg_usrs['cedp'];
}

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(20, 6, utf8_decode(' MÉDICOS:'),0 , 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(172.5, 5.5, utf8_decode(' '.$user_pred.'. '.$user_papelld ), 'B','C');
}

$pdf->Ln(15);
$pdf->SetFont('Arial', '', 10);
$date=date_create($fecha_ing);
$pdf->Cell(37.5, 6, utf8_decode('FECHA DE INGRESO:'),0 , 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(84.5, 5.5, date_format($date,'d/m/Y'), 'B', 0, 'L');

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(37.5, 6, utf8_decode(' HORA DE INGRESO: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(33, 5.5,  date_format($date,'H:m'), 'B', 'L');
$pdf->Ln(20);

$pdf->Cell(35, 6, utf8_decode(' OBSERVACIONES: '), 0, 0, 'L');
$pdf->Cell(157, 5.5, utf8_decode(''), 'B', 'L');
/*if(isset($num_cam)){
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(21, 6, utf8_decode(' HABITACIÓN: '),  0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(7, 6,  $num_cam, 'B', 'C');
}else{
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(20, 6, utf8_decode(' HABITACIÓN: '),  0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(7, 6, 'S/H ', 'B', 'L');
}
*/
$pdf->Ln(10);


$sql_reg_usrs = "SELECT * from reg_usuarios where id_usua=$id_usua";
$result_reg_usrs = $conexion->query($sql_reg_usrs);

while ($row_reg_usrs = $result_reg_usrs->fetch_assoc()) {
  $pre = $row_reg_usrs['pre'];
  $app = $row_reg_usrs['papell'];
  $apm = $row_reg_usrs['sapell'];
  $nom = $row_reg_usrs['nombre'];
    $firma= $row_reg_usrs['firma'];
      $cargp = $row_reg_usrs['cargp'];
      $ced_p = $row_reg_usrs['cedp'];

}

      $pdf->SetY(-60);
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->Image('../../imgfirma/' . $firma, 95, 240, 30);
    
       $pdf->SetY(264);
      $pdf->Cell(200, 4, utf8_decode($pre . '. ' . $app), 0, 0, 'C');
      $pdf->Ln(4);
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->Cell(200, 4, utf8_decode($cargp . ' ' . 'Céd. Prof. ' . $ced_p), 0, 0, 'C');
      $pdf->Ln(4);
      $pdf->Cell(200, 4, utf8_decode('Nombre y firma del médico'), 0, 0, 'C');
      
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetY(-31);
 
    $pdf->Cell(0, 10, utf8_decode('CMSI-001'), 0, 1, 'R');
  
 

//HOJA FRONTAL //HOJA FRONTAL //HOJA FRONTAL //HOJA FRONTAL //HOJA FRONTAL v //HOJA FRONTA //HOJA FRONTAL //HOJA FRONTAL //HOJA FRONTAL //HOJA FRONTAL //HOJA FRONTAL v
$sql_dat_ing = "SELECT * from dat_ingreso where id_atencion = $id_atencion";
$result_dat_ing = $conexion->query($sql_dat_ing);

while ($row_dat_ing = $result_dat_ing->fetch_assoc()) {
  $motivo_atn = $row_dat_ing['motivo_atn'];
  $fecha_ing = $row_dat_ing['fecha'];
  $especialidad = $row_dat_ing['especialidad'];
  $id_usua = $row_dat_ing['id_usua'];
  $tipo_a = $row_dat_ing['tipo_a'];
   $alergias = $row_dat_ing['alergias'];
}


$sqlh = "SELECT * from dat_hclinica where Id_exp = $id_exp";
$rh = $conexion->query($sqlh);

while ($rowh = $rh->fetch_assoc()) {
  $diag_prev = $rowh['diag_prev'];
 
}




$sql_reg_usrs = "SELECT * from reg_usuarios where id_usua=$id_usua and id_rol=2";
$result_reg_usrs = $conexion->query($sql_reg_usrs);

while ($row_reg_usrs = $result_reg_usrs->fetch_assoc()) {
  $user_pre = $row_reg_usrs['pre'];
  $user_papell = $row_reg_usrs['papell'];
  $user_sapell = $row_reg_usrs['sapell'];
  $user_nombre = $row_reg_usrs['nombre'];
  $user_cedula = $row_reg_usrs['cedp'];
}

$sql_pac = "SELECT p.papell, p.nom_pac,p.sapell,p.sexo,p.Id_exp,p.dir,p.id_edo,p.id_mun,p.tel,p.ocup,p.resp,p.paren,p.tel_resp, p.fecnac,p.tip_san, p.folio,p.edad FROM paciente p where p.Id_exp = $id_exp";
$result_pac = $conexion->query($sql_pac);

while ($row_pac = $result_pac->fetch_assoc()) {
  $papell = $row_pac['papell'];
  $nom_pac = $row_pac['nom_pac'];
  $sapell = $row_pac['sapell'];
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
  $tip_san = $row_pac['tip_san'];
  $folio = $row_pac['folio'];
    $edad = $row_pac['edad'];
}




$sql_cam = "SELECT * FROM cat_camas WHERE id_atencion= $id_atencion";
$result_cam = $conexion->query($sql_cam);

while ($row_cam = $result_cam->fetch_assoc()) {
  $num_cam = $row_cam['num_cama'];
}


$sql_mun = "SELECT nombre_m FROM municipios WHERE id_mun = $id_mun";
$result_mun = $conexion->query($sql_mun);

while ($row_mun = $result_mun->fetch_assoc()) {
  $nom_mun = $row_mun['nombre_m'];
}


$pdf->AddPage();

  
  $pdf->Ln(12);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetX(30);
$pdf->SetTextColor(43, 45, 127);
$pdf->MultiCell(165, 9.5, utf8_decode('HOJA FRONTAL'), 0, 'C');


$pdf->SetDrawColor(43, 45, 127);
$pdf->Line(48, 52, 172, 52);
$pdf->Line(48, 41, 48, 52);
$pdf->Line(172, 41, 172, 52);
$pdf->Line(48, 41, 172, 41);

$pdf->SetDrawColor(43, 45, 127);
$pdf->Line(8, 55, 205, 55);
$pdf->Line(8, 55, 8, 280);
$pdf->Line(205, 55, 205, 280);
$pdf->Line(8, 280, 205, 280);

$pdf->SetFont('Arial', '', 8);
$pdf->Ln(6);

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(17, 6, 'PACIENTE: ', 0, 'L');  
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(115, 5.5, utf8_decode($papell . ' ' . $sapell . ' ' . $nom_pac ) , 'B', 'L');
$pdf->SetFont('Arial', '', 8);
$date=date_create($fecnac);
$pdf->Cell(37, 6, ' FECHA DE NACIMIENTO: ', 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(23, 5.5, date_format($date,"d/m/Y"), 'B', 'C');
$pdf->Ln(7.5);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(15, 6, 'GENERO: ', 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(30, 5.5,  $sexo, 'B', 'L');

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(12, 6, ' EDAD: ', 0, 'L');

$pdf->SetFont('Arial', '', 8);

$pdf->Cell(20, 5, utf8_decode($edad), 'B', 'C');


if(isset($num_cam)){
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(20.5, 6, utf8_decode(' HABITACIÓN: '),  0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(9, 5.5,  $num_cam, 'B', 'C');
}else{
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(20.5, 6, utf8_decode(' HABITACIÓN: '),  0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(9, 5.5, 'S/H ', 'B', 'L');
}
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(22, 6, utf8_decode('EXPEDIENTE: '), 0, 0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(11, 5.5, utf8_decode($folio), 'B', 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(22, 6, utf8_decode('TELÉFONO: '), 0, 0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(30.5, 5.5, utf8_decode($tel), 'B', 0, 'L');
$pdf->Ln(7.5);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(17, 6, utf8_decode('ALERGIAS: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(115, 5.5, utf8_decode($alergias), 'B', 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(30, 6, utf8_decode('GRUPO SANGUINEO: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(30.5, 5.5, utf8_decode($tip_san), 'B', 0, 'L');
$pdf->Ln(7.5);

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(18, 6, utf8_decode('DIRECCIÓN: '), 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(175, 5.5, utf8_decode($dir), 'B', 'L');
$pdf->Ln(7.5);

$pdf->SetFont('Arial', '', 8);
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("d/m/Y");
$pdf->Cell(13, 6, utf8_decode('FECHA:'),0 , 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(30, 5.5, $fecha_actual, 'B', 'C');
$pdf->SetFont('Arial', '', 8);
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("H:m");
$pdf->Cell(13, 6, utf8_decode('HORA:'),0 , 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(30, 5.5, $fecha_actual, 'B', 'C');
$pdf->Ln(7.5);

$d = "SELECT DISTINCT(diag_paciente) as diag_paciente, fecha,id_usua,Id_exp from diag_pac where Id_exp=$id_atencion order by fecha DESC LIMIT 1";
$res = $conexion->query($d);

while ($dr = $res->fetch_assoc()) {
  $diag_paciente = $dr['diag_paciente'];
  $fecha = $dr['fecha'];
  $id_usua1 = $dr['id_usua'];
 $id_exp = $dr['Id_exp'];
$sql_reg_usrs = "SELECT * from reg_usuarios where id_usua=$id_usua1";
$result_reg_usrs = $conexion->query($sql_reg_usrs);

while ($row_reg_usrs = $result_reg_usrs->fetch_assoc()) {
  $user_pred = $row_reg_usrs['pre'];
  $user_papelld = $row_reg_usrs['papell'];
  $user_sapelld = $row_reg_usrs['sapell'];
  $user_nombred = $row_reg_usrs['nombre'];
  $user_cedulad = $row_reg_usrs['cedp'];
}



}
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(30, 6, utf8_decode('MÉDICO TRATANTE:'),0 , 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(162.5, 5.5, utf8_decode(' '.$user_pred.'. '.$user_papelld), 'B', 'C');
$pdf->Ln(7.5);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(50, 6, utf8_decode('MÉDICO QUE REALIZA EL INGRESO:'),0 , 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(142.5, 5.5, utf8_decode(' '.$user_pred.' '.$user_papelld), 'B', 'C');
$pdf->Ln(7.5);

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(40, 6, utf8_decode('DIAGNÓSTICO DE INGRESO: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(153, 5.5, utf8_decode($diag_paciente), 'B', 'C');
$pdf->Ln(6.5);
$pdf->Cell(193, 5.5, utf8_decode(''), 'B', 'L');
$pdf->Ln(7.5);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(37, 6, utf8_decode('DIAGNÓSTICO PREVIOS: '), 0, 0, 'L');

if(isset ($diag_prev)){
$pdf->Cell(156, 5.5, utf8_decode($diag_prev), 'B', 'L');
$pdf->Ln(6.5);
$pdf->Cell(193, 5.5, utf8_decode(''), 'B', 'L');
}else{

$diag_prev = ' ' ;

$pdf->Cell(156, 5.5, utf8_decode($diag_prev), 'B', 'L');
$pdf->Ln(6.5);
$pdf->Cell(193, 5.5, utf8_decode(''), 'B', 'L');
}

$pdf->Ln(9);
$pdf->SetFont('Arial', '', 9);
$pdf->SetX(23);
$pdf->SetTextColor(43, 45, 127);
$pdf->MultiCell(165, 9.5, utf8_decode('ORDEN DEL EXPEDIENTE CLINICO'), 0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(73, 9.5, utf8_decode('1.- HOJA INICIAL.'), 0, 'C');
$pdf->Cell(66, 9.5, utf8_decode('9.- CONSENTIMIENTOS.'), 0, 'C');
$pdf->Cell(68.5, 9.5, utf8_decode('12.- REPORTE MINISTERIO PÚBLICO'), 0, 'C');
$pdf->Ln(4.5);
$pdf->Cell(73, 9.5, utf8_decode('2.- HOJA FRONTAL.'), 0, 'C');
$pdf->Cell(66, 9.5, utf8_decode('   - C. QUIRÚRGICA.'), 0, 'C');
$pdf->Cell(68.5, 9.5, utf8_decode('13.- HOJA DE AMBULANCIA'), 0, 'C');
$pdf->Ln(4.5);
$pdf->Cell(73, 9.5, utf8_decode('3.- HISTORIA CLÍNICA.'), 0, 'C');
$pdf->Cell(66, 9.5, utf8_decode('   - C. ANESTESIA.'), 0, 'C');
$pdf->Cell(68.5, 9.5, utf8_decode('14.- HOJA DE INTERNAMIENTO'), 0, 'C');
$pdf->Ln(4.5);
$pdf->Cell(73, 9.5, utf8_decode('4.- NOTAS DE EVOLUCIÓN.'), 0, 'C');
$pdf->Cell(66, 9.5, utf8_decode('   - C. INFORMADO.'), 0, 'C');
$pdf->Cell(68.5, 9.5, utf8_decode('15.- ALTA VOLUNTARIA'), 0, 'C');
$pdf->Ln(4.5);
$pdf->Cell(73, 9.5, utf8_decode('   - NOTAS DE INGRESO.'), 0, 'C');
$pdf->Cell(66, 9.5, utf8_decode('   - C. TRANSFUSIÓN SANGUINEA.'), 0, 'C');
$pdf->Cell(68.5, 9.5, utf8_decode('16.- OTROS DOCUMENTOS'), 0, 'C');
$pdf->Ln(4.5);
$pdf->Cell(73, 9.5, utf8_decode('   - NOTA DE URGENCIA.'), 0, 'C');
$pdf->Cell(66, 9.5, utf8_decode('   - C. ALTO RIESGO.'), 0, 'C');
$pdf->Cell(68.5, 9.5, utf8_decode('17.- PASE DE SALIDA'), 0, 'C');
$pdf->Ln(4.5);
$pdf->Cell(73, 9.5, utf8_decode('   - NOTAS PREOPERATORIAS.'), 0, 'C');
$pdf->Cell(66, 9.5, utf8_decode('   - C. INTERNAMIENTO.'), 0, 'C');
$pdf->Ln(4.5);
$pdf->Cell(71.5, 9.5, utf8_decode('   - NOTAS POSTOPERATORIAS.'), 0, 'C');
$pdf->Cell(66, 9.5, utf8_decode('10.- INDICACIONES MÉDICAS.'), 0, 'C');
$pdf->Ln(4.5);
$pdf->Cell(71.5, 9.5, utf8_decode('   - NOTA PREANESTÉSICA.'), 0, 'C');
$pdf->Cell(66, 9.5, utf8_decode('11.- HOJAS DE ENFERMERÍA.'), 0, 'C');
$pdf->Ln(4.5);
$pdf->Cell(73, 9.5, utf8_decode('   - NOTA POSTOANESTÉSICA.'), 0, 'C');
$pdf->Cell(66, 9.5, utf8_decode('   - H. CURVA TÉRMICA.'), 0, 'C');
$pdf->Cell(68.5, 9.5, utf8_decode('*TODOS LOS DOCUMENTOS VAN'), 0, 'C');
$pdf->Ln(4.5);
$pdf->Cell(73, 9.5, utf8_decode('5.- ESTUDIOS.'), 0, 'C');
$pdf->Cell(66, 9.5, utf8_decode('   - H. DIÁLISIS PERITÓNEAL.'), 0, 'C');
$pdf->Cell(68.5, 9.5, utf8_decode('ORDENADOS DEL MÁS RECIENTE'), 0, 'C');
$pdf->Ln(4.5);
$pdf->Cell(73, 9.5, utf8_decode('   - LABORATORIO.'), 0, 'C');
$pdf->Cell(66, 9.5, utf8_decode('   - H. IRRIGACIÓN.'), 0, 'C');
$pdf->Cell(68.5, 9.5, utf8_decode('AL MÁS ANTIGUO'), 0, 'C');
$pdf->Ln(4.5);
$pdf->Cell(73, 9.5, utf8_decode('   - GABINETE.'), 0, 'C');
$pdf->Cell(66, 9.5, utf8_decode('   - H. TENSIÓN ARTERIAL.'), 0, 'C');
$pdf->Ln(4.5);
$pdf->Cell(73, 9.5, utf8_decode('6.- HOJA DE TRANSFUSIÓN SANGUÍNEO.'), 0, 'C');
$pdf->Cell(66, 9.5, utf8_decode('   - H. PIEZA QUIRÚRGICA.'), 0, 'C');
$pdf->Ln(4.5);
$pdf->Cell(73, 9.5, utf8_decode('7.- HOJA DE REGISTRO DE ANESTESIA.'), 0, 'C');
$pdf->Cell(66, 9.5, utf8_decode('   - H. REGISTRO QUIRÚRGICO.'), 0, 'C');
$pdf->Ln(4.5);
$pdf->Cell(73, 9.5, utf8_decode('8.- HOJA QUIRÚRGICA.'), 0, 'C');
$pdf->Cell(66, 9.5, utf8_decode('   - H. HOJA HOSPITALIZACIÓN.'), 0, 'C');
$pdf->Ln(4.5);
$pdf->Cell(73, 9.5, utf8_decode(''), 0, 'C');
$pdf->Cell(66, 9.5, utf8_decode('   - H. UCI.'), 0, 'C');
$pdf->Ln(4.5);
$pdf->Cell(73, 9.5, utf8_decode(''), 0, 'C');
$pdf->Cell(66, 9.5, utf8_decode('   - H. URGENCIAS.'), 0, 'C');

$pdf->SetDrawColor(43, 45, 127);
$pdf->Line(203, 235, 203, 141); //derecha
$pdf->Line(10, 141, 10, 235);//isqierda
$pdf->Line(10, 141, 203, 141); //ARRIBA
$pdf->Line(10, 235, 203, 235); //Abajo




$sql_reg_usrs = "SELECT * from reg_usuarios where id_usua=$id_usua";
$result_reg_usrs = $conexion->query($sql_reg_usrs);

while ($row_reg_usrs = $result_reg_usrs->fetch_assoc()) {
  $pre = $row_reg_usrs['pre'];
  $app = $row_reg_usrs['papell'];
  $apm = $row_reg_usrs['sapell'];
  $nom = $row_reg_usrs['nombre'];
  $firma= $row_reg_usrs['firma'];
  $cargp = $row_reg_usrs['cargp'];
  $ced_p = $row_reg_usrs['cedp'];

}

      $pdf->SetY(-60);
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->Image('../../imgfirma/' . $firma, 94, 240 , 20);
    
       $pdf->SetY(264);
      $pdf->Cell(189, 4, utf8_decode($pre . '. ' . $app ), 0, 0, 'C');
      $pdf->Ln(4);
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->Cell(190, 4, utf8_decode($cargp . ' ' . 'Céd. Prof. ' . $ced_p), 0, 0, 'C');
      $pdf->Ln(4);
      $pdf->Cell(190, 4, utf8_decode('Nombre y firma del médico'), 0, 0, 'C');

    $pdf->SetY(-31);
       $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(0, 10, utf8_decode('CMSI-002'), 0, 1, 'R');
    
     $pdf->Ln(4);
     
    //FICHA DE IDENTIFICACION FICAH IDENTIFICACION FICHA IDENTIFICACION FIHCA IDENTIFICACION
  

$sql_reg_usu = "SELECT ru.pre, ru.papell, ru.sapell, ru.nombre, di.Id_exp, di.tipo_a FROM dat_ingreso di, reg_usuarios ru where id_atencion=$id_atencion and ru.id_usua=di.id_usua";

$result_reg_usu = $conexion->query($sql_reg_usu);
while ($row_reg_usu = $result_reg_usu->fetch_assoc()) {
  $pre = $row_reg_usu['pre'];
  $papell = $row_reg_usu['papell'];
  $sapell = $row_reg_usu['sapell'];
  $nombre = $row_reg_usu['nombre'];
  $id_exp = $row_reg_usu['Id_exp'];
   $tipo_a = $row_reg_usu['tipo_a'];
}

$sql_pac = "SELECT p.*, di.*  FROM paciente p, dat_ingreso di WHERE p.Id_exp=di.Id_exp and di.id_atencion =$id_atencion";

$result_pac = $conexion->query($sql_pac);

while ($row_pac = $result_pac->fetch_assoc()) {
  $folio = $row_pac['folio'];
  $pac_papell = $row_pac['papell'];
  $pac_sapell = $row_pac['sapell'];
  $pac_nom_pac = $row_pac['nom_pac'];
  $pac_dir = $row_pac['dir'];
  $pac_id_edo = $row_pac['id_edo'];
  $pac_id_mun = $row_pac['id_mun'];
  $pac_tel = $row_pac['tel'];
  $pac_fecnac = $row_pac['fecnac'];
  $alergias = $row_pac['alergias'];
  $motivo_atn = $row_pac['motivo_atn'];
  $fecha = $row_pac['fecha'];
  $tip_san = $row_pac['tip_san'];
  $religion = $row_pac['religion'];
    $edad = $row_pac['edad'];
}



$pdf->AddPage();
$pdf->Ln(5);
 $pdf->SetFont('Arial', 'B', 18);
    $pdf->SetTextColor(43, 45, 127);
    $pdf->Cell(0, 20, utf8_decode('FICHA DE IDENTIFICACIÓN'), 0, 0, 'C');
    $pdf->SetDrawColor(43, 45, 127);
  

$pdf->Ln(20);
$pdf->SetTextColor(43, 45, 127);
$pdf->SetFont('Arial', '', 15);

$pdf->SetX(20);
$pdf->Cell(76, 12, utf8_decode('EXPEDIENTE: ' . ' ' . $folio . ' ' .'                  NOMBRE:   ' .    $pac_papell . ' ' . $pac_sapell  . ' ' . $pac_nom_pac), 0, 1, 'L');

$pdf->SetX(20);
$pdf->Cell(75, 14, utf8_decode('FECHA DE NACIMIENTO:'), 0, 0, 'L');
$fecnac=date_create($pac_fecnac);
$pdf->MultiCell(150, 14, utf8_decode(date_format($fecnac,"d/m/Y")), 0, 'L');

$pdf->SetX(20);
$pdf->Cell(75, 15, utf8_decode('EDAD:'), 0, 0, 'L');


  $pdf->Cell(48, 15, utf8_decode($edad), 0, 'C');


$pdf->Ln(12);
$pdf->SetX(20);
$pdf->Cell(75, 16, utf8_decode('MÉDICO:'), 0, 0, 'L');
$pdf->MultiCell(150, 15, utf8_decode($pre . '. ' . $papell), 0, 'L');

$pdf->SetX(20);
$pdf->Cell(75, 16, utf8_decode('SERVICIO:'), 0, 0, 'L');
$pdf->MultiCell(150, 15, utf8_decode($tipo_a), 0, 'L');

$pdf->SetX(20);
$pdf->Cell(75, 16, utf8_decode('DX:'), 0, 0, 'L');
$pdf->MultiCell(200, 6, utf8_decode($motivo_atn), 0, 'L');

$pdf->SetX(20);
$pdf->Cell(75, 16, utf8_decode('ALERGIAS:'), 0, 0, 'L');
$pdf->MultiCell(150, 15, utf8_decode($alergias), 0, 'L');

$pdf->SetX(20);
$pdf->Cell(75, 16, utf8_decode('FECHA DE INGRESO:'), 0, 0, 'L');
$fecha_ing=date_create($fecha);
$pdf->MultiCell(150, 15, utf8_decode(date_format($fecha_ing,"d/m/Y")), 0, 'L');

$pdf->SetX(20);
$pdf->Cell(75, 16, utf8_decode('TIPO DE SANGRE:'), 0, 0, 'L');
$pdf->MultiCell(150, 15, utf8_decode($tip_san), 0, 'L');

$pdf->SetX(20);
$pdf->Cell(75, 16, utf8_decode('RELIGIÓN:'), 0, 0, 'L');
$pdf->MultiCell(150, 15, utf8_decode($religion), 0, 'L');
$pdf->SetFont('Arial', 'B', 7);

    //reg clin
    
    

$id_med = @$_GET['id_med'];
$id_exp = @$_GET['id_exp'];
$id_atencion = @$_GET['id_atencion'];
$fechar = @$_GET['fechar'];
$hora_mat = @$_GET['hora_mat'];




$sql_pac = "SELECT * FROM paciente  where Id_exp = $id_exp";
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
   $tip_san = $row_pac['tip_san'];
      $folio = $row_pac['folio'];
}


$sql_ing = "SELECT * FROM dat_ingreso  where Id_exp = $id_exp";
$result_ing = $conexion->query($sql_ing);
while ($row_ing = $result_ing->fetch_assoc()) {
$especialidad = $row_ing['especialidad'];
  $fecha = $row_ing['fecha'];
  $area= $row_ing['area'];
    $tipo_a= $row_ing['tipo_a'];
  

}

$sql_f = "SELECT enf_fecha FROM enf_reg_clin  where id_atencion = $id_atencion";
$result_f = $conexion->query($sql_f);
while ($row_f = $result_f->fetch_assoc()) {
$enf_fecha = $row_f['enf_fecha'];

  

}

$sql_camas = "SELECT * FROM cat_camas  where id_atencion = $id_atencion";
$result_camas = $conexion->query($sql_camas);
while ($row_camas = $result_camas->fetch_assoc()) {

  $num_cama = $row_camas['num_cama'];
}



      $sql_now = "SELECT DATE_ADD(NOW(), INTERVAL 12 HOUR) as dat_now FROM dat_ingreso WHERE id_atencion = $id_atencion";

      $result_now = $conexion->query($sql_now);

      while ($row_now = $result_now->fetch_assoc()) {
        $dat_now = $row_now['dat_now'];
      }

      $sql_est = "SELECT DATEDIFF( '$dat_now' , fecha) as estancia FROM dat_ingreso WHERE id_atencion = $id_atencion";

      $result_est = $conexion->query($sql_est);

      while ($row_est = $result_est->fetch_assoc()) {
        $estancia = $row_est['estancia'];
      }

//$pdf = new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();





$enff = $conexion->query("select Distinct (fecha_mat) as fecha_mat, id_atencion from enf_reg_clin where id_atencion=$id_atencion") or die($conexion->error);
while ($resp_re = $enff->fetch_assoc()) {
    
    
$pdf->Ln(5);
    $pdf->SetTextColor(43, 45, 127);

  $pdf->SetDrawColor(43, 45, 180);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetX(55);
$pdf->Cell(110, 5, utf8_decode('REGISTRO CLÍNICO DE ENFERMERÍA DE HOSPITALIZACIÓN'), 0, 0, 'C');

date_default_timezone_set('America/Guatemala');
$fecha_quir = date("d/m/Y H:i:s");
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(25, 5, utf8_decode('Fecha: '.$fecha_quir), 0, 1, 'L');

$pdf->SetFont('Arial', '', 6.5);
$pdf->Ln(-1);
/*$date = date_create($fecha);
$pdf->Cell(110, 5, utf8_decode('Fecha de ingreso al hospital: '.date_format($date, "d-m-Y H:i:s")),0, 'L');
$sql_q = "SELECT * from enf_quirurgico where id_atencion=$id_atencion";
$result_q = $conexion->query($sql_q); 
while ($row_q = $result_q->fetch_assoc()) {
  $fecha_quir = $row_q['fecha_quir'];
    
} */

//$date2 = date_create($fecha_quir);
//$pdf->Cell(80, 5, utf8_decode('Fecha de registro de hoja: '.date_format($date2, "d-m-Y")),0, 'L');
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15, 5, 'Servicio: ', 0, 'L');  
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(120, 5, utf8_decode($tipo_a) , 'B', 'L');
$pdf->SetFont('Arial', 'B', 8);
$date2=date_create($fecha);
$pdf->Cell(28, 5, utf8_decode(' Fecha de Ingreso:'),0 , 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(32, 5, date_format($date2,'d/m/Y H:i:s'), 'B', 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(18, 5, utf8_decode('Expediente: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(15, 5, utf8_decode($folio), 'B', 0, 'C');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(16, 5, ' Paciente: ', 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(146, 5, utf8_decode($papell . ' ' . $sapell . ' ' . $nom_pac), 'B', 'L');
$pdf->SetFont('Arial', 'B', 8);

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 8);
$date1=date_create($fecnac);
$pdf->Cell(31, 5, 'Fecha de nacimiento: ', 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(21, 5, date_format($date1,"d/m/Y"), 'B', 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(11, 5, ' Edad: ', 0, 'L');

$pdf->SetFont('Arial', '', 9);
$pdf->Cell(13, 5, utf8_decode($edad),'B', 'C');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(19, 5, utf8_decode(' Ocupación: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(36, 5,  utf8_decode($ocup), 'B', 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(16, 5, utf8_decode(' Teléfono: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(21, 5,  utf8_decode($tel), 'B', 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(13, 5, utf8_decode('Género: '), 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(14, 5,  $sexo, 'B', 'L');
$pdf->Ln(5);
$pesoh="";
$tallah="";
$sql_sig ="select * from dat_hclinica WHERE Id_exp=$id_atencion ORDER by id_hc DESC LIMIT 1";
$result_sig = $conexion->query($sql_sig);
while ($row_hc = $result_sig->fetch_assoc()) {
$pesoh=$row_hc['peso'];
$tallah=$row_hc['talla'];
}

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(10, 5, utf8_decode('Peso: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(14, 5,  utf8_decode($pesoh.' Kilos'), 'B', 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(11, 5, utf8_decode(' Talla: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(18, 5,  utf8_decode($tallah.' Metros'), 'B', 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(17, 5, utf8_decode(' Domicilio: '), 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(125, 5, utf8_decode($dir), 'B', 'L');

$pdf->Ln(5);

$d="";
    $sql_motd = "SELECT diagprob_i from dat_not_ingreso where id_atencion=$id_atencion ORDER by id_not_ingreso DESC LIMIT 1";
    $result_motd = $conexion->query($sql_motd);
    while ($row_motd = $result_motd->fetch_assoc()) {
        $d=$row_motd['diagprob_i'];
    } 
    $sql_motd = "SELECT diagprob_i from dat_nevol where id_atencion=$id_atencion ORDER by diagprob_i DESC LIMIT 1";
    $result_motd = $conexion->query($sql_motd);
    while ($row_motd = $result_motd->fetch_assoc()) {
        $d=$row_motd['diagprob_i'];
    } ?>
    <?php $sql_mot = "SELECT motivo_atn from dat_ingreso where id_atencion=$id_atencion ORDER by motivo_atn ASC LIMIT 1";
    $result_mot = $conexion->query($sql_mot);
    while ($row_mot = $result_mot->fetch_assoc()) {
    $m=$row_mot['motivo_atn'];
    } 
if ($d!=null) {
    $pdf->SetFont('Arial', 'B', 8);
  $pdf->Cell(20, 5, utf8_decode('Diagnóstico:') , 0, 'C');
  $pdf->SetFont('Arial', '', 9);
     $pdf->Cell(175, 5, utf8_decode($d) , 'B', 'C');
    } else{
        $pdf->SetFont('Arial', 'B',8);
         $pdf->Cell(29, 5, utf8_decode('Motivo de atención:') , 0, 'C');
         $pdf->SetFont('Arial', '', 9);
         $pdf->Cell(166, 5, utf8_decode($m) , 'B', 'C');
    }

$pdf->Ln(6);

$sql_edo = "SELECT edo_salud,alergias from dat_ingreso where id_atencion=$id_atencion ORDER by edo_salud DESC LIMIT 1";
$result_edo = $conexion->query($sql_edo);                                                                                    while ($row_edo = $result_edo->fetch_assoc()) {
  $edo_salud=$row_edo['edo_salud'];
  $alergias=$row_edo['alergias'];
}
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(23,3, utf8_decode('Tipo de sangre:'),0,'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(22,3, utf8_decode($tip_san),'B','L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(17,3, utf8_decode('Habitación: '),0,'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(7,3, utf8_decode($num_cama),'B','L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(25, 3, 'Tiempo estancia: ', 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(12, 3, $estancia . ' dias', 'B', 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(18,3, utf8_decode('Expediente: '),0,'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(12,3, utf8_decode($folio),'B','L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(25,3, utf8_decode('Estado de salud: '),0,'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(34,3, utf8_decode($edo_salud),'B','L');
$pdf->Ln(3);


$sql_aseg = "SELECT aseg from dat_financieros where id_atencion =$id_atencion ORDER BY fecha DESC limit 1";
  $result_aseg = $conexion->query($sql_aseg);
  while ($row_aseg = $result_aseg->fetch_assoc()) {
 $aseg= $row_aseg['aseg'];
}                      
$pdf->SetFont('Arial', 'B', 8);                                               
$pdf->Cell(20,5, utf8_decode('Aseguradora: '),0,'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(60,4, utf8_decode($aseg),'B','L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15,5, utf8_decode('Alergias: '),0,'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(100,4, utf8_decode($alergias),'B','L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Ln(9);

$pdf->SetFont('Arial', 'B', 6);
$pdf->SetX(40);
$pdf->Cell(42,5, 'MATUTINO',1,0,'C');
$pdf->Cell(49,5, 'VESPERTINO',1,0,'C');
$pdf->Cell(77,5, 'NOCTURNO ',1,0,'C');

$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(30,6, utf8_decode('Sigos vitales / Hora'),1,0,'C');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(7,6, utf8_decode('8'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('9'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('10'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('11'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('12'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('13'),1,0,'C');
//ves
$pdf->Cell(7,6, utf8_decode('14'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('15'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('16'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('17'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('18'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('19'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('20'),1,0,'C');
//noc
$pdf->Cell(7,6, utf8_decode('21'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('22'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('23'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('24'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('1'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('2'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('3'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('4'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('5'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('6'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('7'),1,0,'C');

$pdf->SetFont('Arial', 'B', 6.5);
$pdf->SetY(90);
$pdf->SetX(10);
$pdf->Cell(30,6, utf8_decode('T/A (Sistolica / Diastlica)'),1,0,'L');
$pdf->Ln(6);
$pdf->Cell(30,6, utf8_decode('Temperatura'),1,0,'L');
$pdf->Ln(6);
$pdf->Cell(30,6, utf8_decode('Frecuencia cardiaca'),1,0,'L');
$pdf->Ln(6);
$pdf->Cell(30,6, utf8_decode('Frecuencia respiratoria'),1,0,'L');
$pdf->Ln(6);
$pdf->Cell(30,6, utf8_decode('Saturación oxigeno'),1,0,'L');

$pdf->Ln(7);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(30,6, utf8_decode('Presión venosa central'),1,0,'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(30,6, utf8_decode('Perímetro abdominal'),1,0,'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(30,6, utf8_decode('Glicemia capilar'),1,0,'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(30,6, utf8_decode('Insulina'),1,0,'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(30,6, utf8_decode('Oxigenoterapía'),1,0,'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(30,6, utf8_decode('Micronebulizaciones'),1,0,'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(30,6, utf8_decode('Neurológico'),1,0,'L');

$pdf->Ln(7);
$pdf->Cell(5,6, utf8_decode('I'),1,0,'C');
$pdf->Cell(25,6, utf8_decode('Tipo de dieta'),1,0,'L');
$pdf->Ln(6);
$pdf->Cell(5,6, utf8_decode('N'),1,0,'C');
$pdf->Cell(25,6, utf8_decode('Solución base'),1,0,'L');
$pdf->Ln(6);
$pdf->Cell(5,6, utf8_decode('G'),1,0,'C');
$pdf->Cell(25,6, utf8_decode('Medicamentos'),1,0,'L');
$pdf->Ln(6);
$pdf->Cell(5,6, utf8_decode('R'),1,0,'C');
$pdf->Cell(25,6, utf8_decode('Vía oral'),1,0,'L');
$pdf->Ln(6);
$pdf->Cell(5,6, utf8_decode('E'),1,0,'C');
$pdf->Cell(25,6, utf8_decode('Cargas'),1,0,'L');
$pdf->Ln(6);
$pdf->Cell(5,6, utf8_decode('S'),1,0,'C');
$pdf->Cell(25,6, utf8_decode('Infusiones'),1,0,'L');
$pdf->Ln(6);
$pdf->Cell(5,6, utf8_decode('O'),1,0,'C');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(25,3, utf8_decode('Transfusión sanguínea'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,3, utf8_decode(''),0,0,'C');
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(25,3, utf8_decode('Nutrición parenteral'),1,0,'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,6, utf8_decode('S'),1,0,'C');
$pdf->Cell(25,6, utf8_decode('Ingreso parcial total'),1,0,'L');
$pdf->Ln(7);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,3, utf8_decode(''),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Orina'),1,0,'C');
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,3, utf8_decode(''),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Saratoga'),1,0,'C');
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,3, utf8_decode(''),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Estomas'),1,0,'C');
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,3, utf8_decode('E'),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Vomito'),1,0,'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,6, utf8_decode('G'),1,0,'C');
$pdf->Cell(25,6, utf8_decode('Sangrado'),1,0,'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,6, utf8_decode('R'),1,0,'C');
$pdf->Cell(25,6, utf8_decode('Sonda nasogástrica'),1,0,'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,6, utf8_decode('E'),1,0,'C');
$pdf->Cell(25,6, utf8_decode('Herida quirúrgica'),1,0,'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,6, utf8_decode('S'),1,0,'C');
$pdf->Cell(25,6, utf8_decode('Evacuaciones'),1,0,'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,6, utf8_decode('O'),1,0,'C');
$pdf->Cell(25,6, utf8_decode('Drenajes'),1,0,'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,6, utf8_decode('S'),1,0,'C');
$pdf->Cell(25,6, utf8_decode('Biovac'),1,0,'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,4, utf8_decode(''),1,0,'C');
$pdf->Cell(25,4, utf8_decode('Drenovac'),1,0,'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,4, utf8_decode(''),1,0,'C');
$pdf->Cell(25,4, utf8_decode('Penrose'),1,0,'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,3, utf8_decode(''),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Egreso parcial total'),1,0,'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(30,3, utf8_decode('Balance total'),1,0,'C');
$pdf->Ln(8);
    //T/A SISTOLICA Y DIASTOLICA SIGNOS VITALES TA TA TA SIGNOS VITALES TA TA SIGNOS VITALES TA SIGNOS VITALES TA
$resp = $conexion->query("select * from signos_vitales where fecha='".$resp_re['fecha_mat']."' and id_atencion=$id_atencion  HAVING fecha='".$resp_re['fecha_mat']."' order by hora=8 desc, hora=9 desc,hora=10 desc ,hora=11 desc,hora=12 desc ,hora=13 desc,hora=14 desc,hora=15 desc,hora=16 desc,hora=17 desc,hora=18 desc,hora=19 desc,hora=20 desc,hora=21 desc,hora=22 desc,hora=23 desc,hora=24 desc,hora=1 desc,hora=2 desc,hora=3 desc,hora=4 desc,hora=5 desc,hora=6 desc,hora=7 desc") or die($conexion->error);

while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 4.4);
  
  if($resp_r['fecha']==$resp_re['fecha_mat'] and $resp_r['hora']=='8'){
$pdf->SetY(90);
$pdf->SetX(40);
$pdf->Cell(7,6, $resp_r['p_sistol'] . ' / ' .$resp_r['p_diastol'].':'.$resp_r['hora'],1,0,'C');

$pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(40);
$pdf->Cell(7,6, $resp_r['temper'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(40);
$pdf->Cell(7,6, $resp_r['fcard'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(40);
$pdf->Cell(7,6, $resp_r['fresp'],1,0,'C');
}else{
$pdf->SetY(90);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
 
$pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(40);
$pdf->Cell(7,6,'',1,0,'C');
  
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(40);
$pdf->Cell(7,6,'',1,0,'C');

$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(40);
$pdf->Cell(7,6,'',1,0,'C');
}


if($resp_r['fecha']==$resp_re['fecha_mat'] and $resp_r['hora']=='9'){
$pdf->SetY(90);
$pdf->SetX(47);
$pdf->Cell(7,6,   $resp_r['p_sistol'] . ' / ' .$resp_r['p_diastol']. ':' .$resp_r['hora'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(47);
$pdf->Cell(7,6, $resp_r['temper'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(47);
$pdf->Cell(7,6, $resp_r['fcard'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(47);
$pdf->Cell(7,6, $resp_r['fresp'],1,0,'C');

}else{
    $pdf->SetY(90);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
  $pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(47);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(47);
$pdf->Cell(7,6,'',1,0,'C');
  $pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(47);
$pdf->Cell(7,6,'',1,0,'C');
}


if($resp_r['fecha']==$resp_re['fecha_mat'] and $resp_r['hora']=='10'){
$pdf->SetY(90);
$pdf->SetX(54);
$pdf->Cell(7,6,   $resp_r['p_sistol'] . ' / ' .$resp_r['p_diastol']. ':' .$resp_r['hora'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(54);
$pdf->Cell(7,6, $resp_r['temper'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(54);
$pdf->Cell(7,6, $resp_r['fcard'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(54);
$pdf->Cell(7,6, $resp_r['fresp'],1,0,'C');
}else{
    $pdf->SetY(90);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
   $pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(54);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(54);
$pdf->Cell(7,6,'',1,0,'C');
  $pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(54);
$pdf->Cell(7,6,'',1,0,'C');
}

if($resp_r['fecha']==$resp_re['fecha_mat'] and $resp_r['hora']=='11'){
$pdf->SetY(90);
$pdf->SetX(61);
$pdf->Cell(7,6,   $resp_r['p_sistol'] . ' / ' .$resp_r['p_diastol']. ':' .$resp_r['hora'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(61);
$pdf->Cell(7,6, $resp_r['temper'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(61);
$pdf->Cell(7,6, $resp_r['fcard'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(61);
$pdf->Cell(7,6, $resp_r['fresp'],1,0,'C');
}else{
 $pdf->SetY(90);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
    $pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(61);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(61);
$pdf->Cell(7,6,'',1,0,'C');
  $pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(61);
$pdf->Cell(7,6,'',1,0,'C');
}

if($resp_r['fecha']==$resp_re['fecha_mat'] and $resp_r['hora']=='12'){
$pdf->SetY(90);
$pdf->SetX(68);
$pdf->Cell(7,6,   $resp_r['p_sistol'] . ' / ' .$resp_r['p_diastol']. ':' .$resp_r['hora'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(68);
$pdf->Cell(7,6, $resp_r['temper'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(68);
$pdf->Cell(7,6, $resp_r['fcard'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(68);
$pdf->Cell(7,6, $resp_r['fresp'],1,0,'C');

}else{
$pdf->SetY(90);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
    $pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(68);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(68);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(68);
$pdf->Cell(7,6,'',1,0,'C');
}

if($resp_r['fecha']==$resp_re['fecha_mat'] and $resp_r['hora']=='13'){
$pdf->SetY(90);
$pdf->SetX(75);
$pdf->Cell(7,6,   $resp_r['p_sistol'] . ' / ' .$resp_r['p_diastol']. ':' .$resp_r['hora'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(75);
$pdf->Cell(7,6, $resp_r['temper'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(75);
$pdf->Cell(7,6, $resp_r['fcard'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(75);
$pdf->Cell(7,6, $resp_r['fresp'],1,0,'C');
}else{
$pdf->SetY(90);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
$pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(75);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(75);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(75);
$pdf->Cell(7,6,'',1,0,'C');
}

if($resp_r['fecha']==$resp_re['fecha_mat'] and $resp_r['hora']=='14'){
$pdf->SetY(90);
$pdf->SetX(82);
$pdf->Cell(7,6,   $resp_r['p_sistol'] . ' / ' .$resp_r['p_diastol']. ':' .$resp_r['hora'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(82);
$pdf->Cell(7,6, $resp_r['temper'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(82);
$pdf->Cell(7,6, $resp_r['fcard'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(82);
$pdf->Cell(7,6, $resp_r['fresp'],1,0,'C');
}else{
$pdf->SetY(90);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
  $pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(82);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(82);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(82);
$pdf->Cell(7,6,'',1,0,'C');
}

if($resp_r['fecha']==$resp_re['fecha_mat'] and $resp_r['hora']=='15'){
$pdf->SetY(90);
$pdf->SetX(89);
$pdf->Cell(7,6,   $resp_r['p_sistol'] . ' / ' .$resp_r['p_diastol']. ':' .$resp_r['hora'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(89);
$pdf->Cell(7,6, $resp_r['temper'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(89);
$pdf->Cell(7,6, $resp_r['fcard'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(89);
$pdf->Cell(7,6, $resp_r['fresp'],1,0,'C');
}else{
$pdf->SetY(90);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
    $pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(89);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(89);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(89);
$pdf->Cell(7,6,'',1,0,'C');
}

if($resp_r['fecha']==$resp_re['fecha_mat'] and $resp_r['hora']=='16'){
$pdf->SetY(90);
$pdf->SetX(96);
$pdf->Cell(7,6,   $resp_r['p_sistol'] . ' / ' .$resp_r['p_diastol']. ':' .$resp_r['hora'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(96);
$pdf->Cell(7,6, $resp_r['temper'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(96);
$pdf->Cell(7,6, $resp_r['fcard'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(96);
$pdf->Cell(7,6, $resp_r['fresp'],1,0,'C');
}else{
$pdf->SetY(90);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
     $pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(96);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(96);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(96);
$pdf->Cell(7,6,'',1,0,'C');
}

if($resp_r['fecha']==$resp_re['fecha_mat'] and $resp_r['hora']=='17'){
$pdf->SetY(90);
$pdf->SetX(103);
$pdf->Cell(7,6,   $resp_r['p_sistol'] . ' / ' .$resp_r['p_diastol']. ':' .$resp_r['hora'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(103);
$pdf->Cell(7,6, $resp_r['temper'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(103);
$pdf->Cell(7,6, $resp_r['fcard'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(103);
$pdf->Cell(7,6, $resp_r['fresp'],1,0,'C');
}else{
$pdf->SetY(90);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
       $pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(103);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(103);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(103);
$pdf->Cell(7,6,'',1,0,'C');
}

if($resp_r['fecha']==$resp_re['fecha_mat'] and $resp_r['hora']=='18'){
$pdf->SetY(90);
$pdf->SetX(110);
$pdf->Cell(7,6,   $resp_r['p_sistol'] . ' / ' .$resp_r['p_diastol']. ':' .$resp_r['hora'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(110);
$pdf->Cell(7,6, $resp_r['temper'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(110);
$pdf->Cell(7,6, $resp_r['fcard'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(110);
$pdf->Cell(7,6, $resp_r['fresp'],1,0,'C');
}else{
$pdf->SetY(90);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
    $pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(110);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(110);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(110);
$pdf->Cell(7,6,'',1,0,'C');
}

if($resp_r['fecha']==$resp_re['fecha_mat'] and $resp_r['hora']=='19'){
$pdf->SetY(90);
$pdf->SetX(117);
$pdf->Cell(7,6,   $resp_r['p_sistol'] . ' / ' .$resp_r['p_diastol']. ':' .$resp_r['hora'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(117);
$pdf->Cell(7,6, $resp_r['temper'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(117);
$pdf->Cell(7,6, $resp_r['fcard'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(117);
$pdf->Cell(7,6, $resp_r['fresp'],1,0,'C');
}else{
$pdf->SetY(90);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
    $pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(117);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(117);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(117);
$pdf->Cell(7,6,'',1,0,'C');
}

if($resp_r['fecha']==$resp_re['fecha_mat'] and $resp_r['hora']=='20'){
$pdf->SetY(90);
$pdf->SetX(124);
$pdf->Cell(7,6,   $resp_r['p_sistol'] . ' / ' .$resp_r['p_diastol']. ':' .$resp_r['hora'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(124);
$pdf->Cell(7,6, $resp_r['temper'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(124);
$pdf->Cell(7,6, $resp_r['fcard'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(124);
$pdf->Cell(7,6, $resp_r['fresp'],1,0,'C');
}else{
$pdf->SetY(90);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
  $pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(124);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(124);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(124);
$pdf->Cell(7,6,'',1,0,'C');
    
}

if($resp_r['fecha']==$resp_re['fecha_mat'] and $resp_r['hora']=='21'){
$pdf->SetY(90);
$pdf->SetX(131);
$pdf->Cell(7,6,   $resp_r['p_sistol'] . ' / ' .$resp_r['p_diastol']. ':' .$resp_r['hora'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(131);
$pdf->Cell(7,6, $resp_r['temper'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(131);
$pdf->Cell(7,6, $resp_r['fcard'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(131);
$pdf->Cell(7,6, $resp_r['fresp'],1,0,'C');
}else{
$pdf->SetY(90);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
    $pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(131);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(131);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(131);
$pdf->Cell(7,6,'',1,0,'C');
}


if($resp_r['fecha']==$resp_re['fecha_mat'] and $resp_r['hora']=='22'){
$pdf->SetY(90);
$pdf->SetX(138);
$pdf->Cell(7,6,   $resp_r['p_sistol'] . ' / ' .$resp_r['p_diastol']. ':' .$resp_r['hora'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(138);
$pdf->Cell(7,6, $resp_r['temper'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(138);
$pdf->Cell(7,6, $resp_r['fcard'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(138);
$pdf->Cell(7,6, $resp_r['fresp'],1,0,'C');
}else{
$pdf->SetY(90);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
  $pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(138);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(138);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(138);
$pdf->Cell(7,6,'',1,0,'C');
}

if($resp_r['fecha']==$resp_re['fecha_mat'] and $resp_r['hora']=='23'){
$pdf->SetY(90);
$pdf->SetX(145);
$pdf->Cell(7,6,   $resp_r['p_sistol'] . ' / ' .$resp_r['p_diastol']. ':' .$resp_r['hora'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(145);
$pdf->Cell(7,6, $resp_r['temper'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(145);
$pdf->Cell(7,6, $resp_r['fcard'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(145);
$pdf->Cell(7,6, $resp_r['fresp'],1,0,'C');
}else{
$pdf->SetY(90);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
  $pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(145);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(145);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(145);
$pdf->Cell(7,6,'',1,0,'C');
}

if($resp_r['fecha']==$resp_re['fecha_mat'] and $resp_r['hora']=='24'){
$pdf->SetY(90);
$pdf->SetX(152);
$pdf->Cell(7,6,   $resp_r['p_sistol'] . ' / ' .$resp_r['p_diastol']. ':' .$resp_r['hora'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(152);
$pdf->Cell(7,6, $resp_r['temper'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(152);
$pdf->Cell(7,6, $resp_r['fcard'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(152);
$pdf->Cell(7,6, $resp_r['fresp'],1,0,'C');
}else{
$pdf->SetY(90);
$pdf->SetX(152);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
  $pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(152);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(152);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(152);
$pdf->Cell(7,6,'',1,0,'C');
}

if($resp_r['fecha']==$resp_re['fecha_mat'] and $resp_r['hora']=='1'){
$pdf->SetY(90);
$pdf->SetX(159);
$pdf->Cell(7,6,$resp_r['p_sistol'] . ' / ' .$resp_r['p_diastol']. ':' .$resp_r['hora'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(159);
$pdf->Cell(7,6, $resp_r['temper'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(159);
$pdf->Cell(7,6, $resp_r['fcard'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(159);
$pdf->Cell(7,6, $resp_r['fresp'],1,0,'C');
}else{
$pdf->SetY(90);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
   $pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(159);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(159);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(159);
$pdf->Cell(7,6,'',1,0,'C');
}

if($resp_r['fecha']==$resp_re['fecha_mat'] and $resp_r['hora']=='2'){
$pdf->SetY(90);
$pdf->SetX(166);
$pdf->Cell(7,6,$resp_r['p_sistol'] . ' / ' .$resp_r['p_diastol']. ':' .$resp_r['hora'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(166);
$pdf->Cell(7,6, $resp_r['temper'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(166);
$pdf->Cell(7,6, $resp_r['fcard'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(166);
$pdf->Cell(7,6, $resp_r['fresp'],1,0,'C');
}else{
$pdf->SetY(90);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
     $pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(166);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(166);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(166);
$pdf->Cell(7,6,'',1,0,'C');
}

if($resp_r['fecha']==$resp_re['fecha_mat'] and $resp_r['hora']=='3'){
$pdf->SetY(90);
$pdf->SetX(173);
$pdf->Cell(7,6,$resp_r['p_sistol'] . ' / ' .$resp_r['p_diastol']. ':' .$resp_r['hora'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(173);
$pdf->Cell(7,6, $resp_r['temper'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(173);
$pdf->Cell(7,6, $resp_r['fcard'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(173);
$pdf->Cell(7,6, $resp_r['fresp'],1,0,'C');
}else{
$pdf->SetY(90);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
     $pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(173);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(173);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(173);
$pdf->Cell(7,6,'',1,0,'C');
}

if($resp_r['fecha']==$resp_re['fecha_mat'] and $resp_r['hora']=='4'){
$pdf->SetY(90);
$pdf->SetX(180);
$pdf->Cell(7,6,$resp_r['p_sistol'] . ' / ' .$resp_r['p_diastol']. ':' .$resp_r['hora'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(180);
$pdf->Cell(7,6, $resp_r['temper'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(180);
$pdf->Cell(7,6, $resp_r['fcard'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(180);
$pdf->Cell(7,6, $resp_r['fresp'],1,0,'C');
}else{
$pdf->SetY(90);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
   $pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(180);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(180);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(180);
$pdf->Cell(7,6,'',1,0,'C');
}

if($resp_r['fecha']==$resp_re['fecha_mat'] and $resp_r['hora']=='5'){
$pdf->SetY(90);
$pdf->SetX(187);
$pdf->Cell(7,6,$resp_r['p_sistol'] . ' / ' .$resp_r['p_diastol']. ':' .$resp_r['hora'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(187);
$pdf->Cell(7,6, $resp_r['temper'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(187);
$pdf->Cell(7,6, $resp_r['fcard'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(187);
$pdf->Cell(7,6, $resp_r['fresp'],1,0,'C');
}else{
$pdf->SetY(90);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
  $pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(187);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(187);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(187);
$pdf->Cell(7,6,'',1,0,'C');
}

if($resp_r['fecha']==$resp_re['fecha_mat'] and $resp_r['hora']=='6'){
$pdf->SetY(90);
$pdf->SetX(194);
$pdf->Cell(7,6,$resp_r['p_sistol'] . ' / ' .$resp_r['p_diastol']. ':' .$resp_r['hora'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(194);
$pdf->Cell(7,6, $resp_r['temper'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(194);
$pdf->Cell(7,6, $resp_r['fcard'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(194);
$pdf->Cell(7,6, $resp_r['fresp'],1,0,'C');
}else{
$pdf->SetY(90);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
    $pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(194);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(194);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(194);
$pdf->Cell(7,6,'',1,0,'C');
}

if($resp_r['fecha']==$resp_re['fecha_mat'] and $resp_r['hora']=='7'){
$pdf->SetY(90);
$pdf->SetX(201);
$pdf->Cell(7,6,$resp_r['p_sistol'] . ' / ' .$resp_r['p_diastol']. ':' .$resp_r['hora'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(201);
$pdf->Cell(7,6, $resp_r['temper'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(201);
$pdf->Cell(7,6, $resp_r['fcard'],1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(201);
$pdf->Cell(7,6, $resp_r['fresp'],1,0,'C');
}else{
$pdf->SetY(90);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
    $pdf->Ln(4);
$pdf->SetY(96);
$pdf->SetX(201);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(102);
$pdf->SetX(201);
$pdf->Cell(7,6,'',1,0,'C');
$pdf->Ln(4);
$pdf->SetY(108);
$pdf->SetX(201);
$pdf->Cell(7,6,'',1,0,'C');
}





}
$pdf->AliasNbPages();
$pdf->AddPage();

}





///PVC CM PVC CM PVC CM PVC CM PVC CM PVC CM PVC CM PVC CM PVC CM PCMV
$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='8' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $goct=$resp_r['cantidad'];
}
if(isset($goct)){
$pdf->SetY(121);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($goct),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='9' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g=$resp_r['cantidad'];
}
if(isset($g)){
$pdf->SetY(121);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($g),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='10' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g9=$resp_r9['cantidad'];
}
if(isset($g9)){
$pdf->SetY(121);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($g9),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='11' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g10=$resp_r['cantidad'];
}
if(isset($g10)){
$pdf->SetY(121);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($g10),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='12' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g11=$resp_r['cantidad'];
}
if(isset($g11)){
$pdf->SetY(121);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($g11),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='13' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g12=$resp_r['cantidad'];
}
if(isset($g12)){
$pdf->SetY(121);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($g12),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='14' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g13=$resp_r['cantidad'];
}
if(isset($g13)){
$pdf->SetY(121);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($g13),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='15' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g14=$resp_r['cantidad'];
}
if(isset($g14)){
$pdf->SetY(121);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($g14),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='16' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g15=$resp_r['cantidad'];
}
if(isset($g15)){
$pdf->SetY(121);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($g15),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='17' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g16=$resp_r['cantidad'];
}
if(isset($g16)){
$pdf->SetY(121);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($g16),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='18' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g17=$resp_r['cantidad'];
}
if(isset($g17)){
$pdf->SetY(121);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($g17),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='19' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g18=$resp_r['cantidad'];
}
if(isset($g18)){
$pdf->SetY(121);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($g18),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='20' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g19=$resp_r['cantidad'];
}
if(isset($g19)){
$pdf->SetY(121);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($g19),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='21' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g20=$resp_r['cantidad'];
}
if(isset($g20)){
$pdf->SetY(121);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($g20),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='22' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g21=$resp_r['cantidad'];
}
if(isset($g21)){
$pdf->SetY(121);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($g21),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='23' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g22=$resp_r['cantidad'];
}
if(isset($g22)){
$pdf->SetY(121);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($g22),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='24' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g23=$resp_r['cantidad'];
}
if(isset($g23)){
$pdf->SetY(121);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($g23),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='1' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g24=$resp_r['cantidad'];
}
if(isset($g24)){
$pdf->SetY(121);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($g24),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='2' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g01=$resp_r['cantidad'];
}
if(isset($g01)){
$pdf->SetY(121);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($g01),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='3' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g02=$resp_r['cantidad'];
}
if(isset($g02)){
$pdf->SetY(121);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($g02),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='4' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g03=$resp_r['cantidad'];
}
if(isset($g03)){
$pdf->SetY(121);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($g03),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='5' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g04=$resp_r['cantidad'];
}
if(isset($g04)){
$pdf->SetY(121);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($g04),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='6' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g05=$resp_r['cantidad'];
}
if(isset($g05)){
$pdf->SetY(121);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($g05),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='7' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g06=$resp_r['cantidad'];
}
if(isset($g06)){
$pdf->SetY(121);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($g06),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


//PERIMETROS PERIMETROS PERIMETROS PERIMETROS PERIMETROS PERIMETROS PERIMETROS

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='8' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $peroc=$resp_r['cantidad'];
}
if(isset($peroc)){
$pdf->SetY(127);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($peroc),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='9' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nuevp=$resp_r['cantidad'];
}
if(isset($nuevp)){
$pdf->SetY(127);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($nuevp),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='10' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diezper=$resp_r9['cantidad'];
}
if(isset($diezper)){
$pdf->SetY(127);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($diezper),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='11' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $paon=$resp_r['cantidad'];
}
if(isset($paon)){
$pdf->SetY(127);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($paon),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='12' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $padoc=$resp_r['cantidad'];
}
if(isset($padoc)){
$pdf->SetY(127);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($padoc),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='13' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $patre=$resp_r['cantidad'];
}
if(isset($patre)){
$pdf->SetY(127);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($patre),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='14' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $padatc=$resp_r['cantidad'];
}
if(isset($padatc)){
$pdf->SetY(127);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($padatc),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='15' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pabquin=$resp_r['cantidad'];
}
if(isset($pabquin)){
$pdf->SetY(127);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($pabquin),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='16' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pabdsei=$resp_r['cantidad'];
}
if(isset($pabdsei)){
$pdf->SetY(127);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($pabdsei),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='17' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pabdsiete=$resp_r['cantidad'];
}
if(isset($pabdsiete)){
$pdf->SetY(127);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($pabdsiete),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='18' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pabdoch=$resp_r['cantidad'];
}
if(isset($pabdoch)){
$pdf->SetY(127);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($pabdoch),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='19' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pabnueveeeee=$resp_r['cantidad'];
}
if(isset($pabnueveeeee)){
$pdf->SetY(127);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($pabnueveeeee),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='20' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pabvvvv=$resp_r['cantidad'];
}
if(isset($pabvvvv)){
$pdf->SetY(127);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($pabvvvv),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='21' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pavvvvun=$resp_r['cantidad'];
}
if(isset($pavvvvun)){
$pdf->SetY(127);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($pavvvvun),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='22' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pabmila=$resp_r['cantidad'];
}
if(isset($pabmila)){
$pdf->SetY(127);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($pabmila),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='23' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pabveintreee=$resp_r['cantidad'];
}
if(isset($pabveintreee)){
$pdf->SetY(127);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($pabveintreee),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='24' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pabvtr=$resp_r['cantidad'];
}
if(isset($pabvtr)){
$pdf->SetY(127);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($pabvtr),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='1' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $perabdl=$resp_r['cantidad'];
}
if(isset($perabdl)){
$pdf->SetY(127);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($perabdl),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='2' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $al1=$resp_r['cantidad'];
}
if(isset($al1)){
$pdf->SetY(127);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($al1),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='3' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $al2=$resp_r['cantidad'];
}
if(isset($al2)){
$pdf->SetY(127);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($al2),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='4' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $altres=$resp_r['cantidad'];
}
if(isset($altres)){
$pdf->SetY(127);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($altres),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='5' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $alcuatro=$resp_r['cantidad'];
}
if(isset($alcuatro)){
$pdf->SetY(127);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($alcuatro),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='6' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $persssss=$resp_r['cantidad'];
}
if(isset($persssss)){
$pdf->SetY(127);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($persssss),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='7' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pssssis=$resp_r['cantidad'];
}
if(isset($pssssis)){
$pdf->SetY(127);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($pssssis),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//GLICEMIA CAPILAR GLICEMIA CAPILAR GLICEMIA CAPÍLAR

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='8' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glicoct=$resp_r['cantidad'];
}
if(isset($glicoct)){
$pdf->SetY(133);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($glicoct),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='9' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g91=$resp_r['cantidad'];
}
if(isset($g91)){
$pdf->SetY(133);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($g91),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='10' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g92=$resp_r9['cantidad'];
}
if(isset($g92)){
$pdf->SetY(133);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($g92),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='11' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g93=$resp_r['cantidad'];
}
if(isset($g93)){
$pdf->SetY(133);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($g93),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='12' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g194=$resp_r['cantidad'];
}
if(isset($g194)){
$pdf->SetY(133);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($g194),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='13' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g126=$resp_r['cantidad'];
}
if(isset($g126)){
$pdf->SetY(133);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($g126),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='14' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g134=$resp_r['cantidad'];
}
if(isset($g134)){
$pdf->SetY(133);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($g134),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='15' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g140d=$resp_r['cantidad'];
}
if(isset($g140d)){
$pdf->SetY(133);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($g140d),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='16' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $caglic=$resp_r['cantidad'];
}
if(isset($caglic)){
$pdf->SetY(133);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($caglic),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='17' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cg01=$resp_r['cantidad'];
}
if(isset($cg01)){
$pdf->SetY(133);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($cg01),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='18' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cg02=$resp_r['cantidad'];
}
if(isset($cg02)){
$pdf->SetY(133);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($cg02),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='19' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cgl03=$resp_r['cantidad'];
}
if(isset($cgl03)){
$pdf->SetY(133);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($cgl03),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='20' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ch04=$resp_r['cantidad'];
}
if(isset($ch04)){
$pdf->SetY(133);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($ch04),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='21' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cgl05=$resp_r['cantidad'];
}
if(isset($cgl05)){
$pdf->SetY(133);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($cgl05),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='22' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cgl06=$resp_r['cantidad'];
}
if(isset($cgl06)){
$pdf->SetY(133);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($cgl06),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='23' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $c94554=$resp_r['cantidad'];
}
if(isset($c94554)){
$pdf->SetY(133);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($c94554),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='24' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g_l=$resp_r['cantidad'];
}
if(isset($g_l)){
$pdf->SetY(133);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($g_l),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='1' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cgli777=$resp_r['cantidad'];
}
if(isset($cgli777)){
$pdf->SetY(133);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($cgli777),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='2' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ge01=$resp_r['cantidad'];
}
if(isset($ge01)){
$pdf->SetY(133);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($ge01),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='3' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gg02=$resp_r['cantidad'];
}
if(isset($gg02)){
$pdf->SetY(133);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($gg02),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='4' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gt03=$resp_r['cantidad'];
}
if(isset($gt03)){
$pdf->SetY(133);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($gt03),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='5' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g04cglcin=$resp_r['cantidad'];
}
if(isset($g04cglcin)){
$pdf->SetY(133);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($g04cglcin),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='6' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cantidikdfdg=$resp_r['cantidad'];
}
if(isset($cantidikdfdg)){
$pdf->SetY(133);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($cantidikdfdg),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='7' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rrrglc=$resp_r['cantidad'];
}
if(isset($rrrglc)){
$pdf->SetY(133);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($rrrglc),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//INSULINA INSULINA INSULINA INSULINAINSULINA INSULINA INSULINA INSULINA INSLINA

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='8' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins8=$resp_r['cantidad'];
}
if(isset($ins8)){
$pdf->SetY(139);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($ins8),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='9' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins9=$resp_r['cantidad'];
}
if(isset($ins9)){
$pdf->SetY(139);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($ins9),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='10' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins10=$resp_r9['cantidad'];
}
if(isset($ins10)){
$pdf->SetY(139);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($ins10),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='11' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins11=$resp_r['cantidad'];
}
if(isset($ins11)){
$pdf->SetY(139);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($ins11),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='12' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins12=$resp_r['cantidad'];
}
if(isset($ins12)){
$pdf->SetY(139);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($ins12),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='13' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins13=$resp_r['cantidad'];
}
if(isset($ins13)){
$pdf->SetY(139);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($ins13),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='14' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins14=$resp_r['cantidad'];
}
if(isset($ins14)){
$pdf->SetY(139);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($ins14),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='15' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins15=$resp_r['cantidad'];
}
if(isset($ins15)){
$pdf->SetY(139);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($ins15),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='16' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins16=$resp_r['cantidad'];
}
if(isset($ins16)){
$pdf->SetY(139);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($ins16),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='17' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins17=$resp_r['cantidad'];
}
if(isset($ins17)){
$pdf->SetY(139);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($ins17),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='18' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins18=$resp_r['cantidad'];
}
if(isset($ins18)){
$pdf->SetY(139);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($ins18),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='19' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins19=$resp_r['cantidad'];
}
if(isset($ins19)){
$pdf->SetY(139);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($ins19),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='20' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins20=$resp_r['cantidad'];
}
if(isset($ins20)){
$pdf->SetY(139);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($ins20),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='21' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins21=$resp_r['cantidad'];
}
if(isset($ins21)){
$pdf->SetY(139);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($ins21),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='22' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins22=$resp_r['cantidad'];
}
if(isset($ins22)){
$pdf->SetY(139);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($ins22),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='23' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins23=$resp_r['cantidad'];
}
if(isset($ins23)){
$pdf->SetY(139);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($ins23),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='24' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins24=$resp_r['cantidad'];
}
if(isset($ins24)){
$pdf->SetY(139);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($ins24),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='1' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins1=$resp_r['cantidad'];
}
if(isset($ins1)){
$pdf->SetY(139);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($ins1),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='2' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins2=$resp_r['cantidad'];
}
if(isset($ins2)){
$pdf->SetY(139);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($ins2),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='3' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins3=$resp_r['cantidad'];
}
if(isset($ins3)){
$pdf->SetY(139);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($ins3),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='4' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins4=$resp_r['cantidad'];
}
if(isset($ins4)){
$pdf->SetY(139);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($ins4),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='5' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins5=$resp_r['cantidad'];
}
if(isset($ins5)){
$pdf->SetY(139);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($ins5),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='6' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins6=$resp_r['cantidad'];
}
if(isset($ins6)){
$pdf->SetY(139);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($ins6),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='7' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins7=$resp_r['cantidad'];
}
if(isset($ins7)){
$pdf->SetY(139);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($ins7),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


//OXIGEOTERAPIA OXIGENOTERAPIA OXIGENO TERAPIA OXIGENO TERAPIA OXIGENO TERAPIA

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='8' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox8=$resp_r['cantidad'];
}
if(isset($ox8)){
$pdf->SetY(145);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($ox8),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='9' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox9=$resp_r['cantidad'];
}
if(isset($ox9)){
$pdf->SetY(145);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($ox9),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='10' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox10=$resp_r9['cantidad'];
}
if(isset($ox10)){
$pdf->SetY(145);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($ox10),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='11' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox11=$resp_r['cantidad'];
}
if(isset($ox11)){
$pdf->SetY(145);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($ox11),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='12' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox12=$resp_r['cantidad'];
}
if(isset($ox12)){
$pdf->SetY(145);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($ox12),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='13' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox13=$resp_r['cantidad'];
}
if(isset($ox13)){
$pdf->SetY(145);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($ox13),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='14' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox14=$resp_r['cantidad'];
}
if(isset($ox14)){
$pdf->SetY(145);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($ox14),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='15' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox15=$resp_r['cantidad'];
}
if(isset($ox15)){
$pdf->SetY(145);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($ox15),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='16' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox16=$resp_r['cantidad'];
}
if(isset($ox16)){
$pdf->SetY(145);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($ox16),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='17' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox17=$resp_r['cantidad'];
}
if(isset($ox17)){
$pdf->SetY(145);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($ox17),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='18' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox18=$resp_r['cantidad'];
}
if(isset($ox18)){
$pdf->SetY(145);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($ox18),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='19' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox19=$resp_r['cantidad'];
}
if(isset($ox19)){
$pdf->SetY(145);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($ox19),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='20' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox20=$resp_r['cantidad'];
}
if(isset($ox20)){
$pdf->SetY(145);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($ox20),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='21' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox21=$resp_r['cantidad'];
}
if(isset($ox21)){
$pdf->SetY(145);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($ox21),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='22' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox22=$resp_r['cantidad'];
}
if(isset($ox22)){
$pdf->SetY(145);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($ox22),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='23' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox23=$resp_r['cantidad'];
}
if(isset($ox23)){
$pdf->SetY(145);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($ox23),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='24' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox24=$resp_r['cantidad'];
}
if(isset($ox24)){
$pdf->SetY(145);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($ox24),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='1' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox1=$resp_r['cantidad'];
}
if(isset($ox1)){
$pdf->SetY(145);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($ox1),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='2' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox2=$resp_r['cantidad'];
}
if(isset($ox2)){
$pdf->SetY(145);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($ox2),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='3' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox3=$resp_r['cantidad'];
}
if(isset($ox3)){
$pdf->SetY(145);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($ox3),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='4' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox4=$resp_r['cantidad'];
}
if(isset($ox4)){
$pdf->SetY(145);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($ox4),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='5' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox5=$resp_r['cantidad'];
}
if(isset($ox5)){
$pdf->SetY(145);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($ox5),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='6' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox6=$resp_r['cantidad'];
}
if(isset($ox6)){
$pdf->SetY(145);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($ox6),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='7' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox7=$resp_r['cantidad'];
}
if(isset($ox7)){
$pdf->SetY(145);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($ox7),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//micronebulizaciones micronebulizaciones micro nebulizaciones

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='8' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul8=$resp_r['cantidad'];
}
if(isset($bul8)){
$pdf->SetY(151);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($bul8),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='9' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul9=$resp_r['cantidad'];
}
if(isset($bul9)){
$pdf->SetY(151);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($bul9),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='10' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul10=$resp_r9['cantidad'];
}
if(isset($bul10)){
$pdf->SetY(151);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($bul10),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='11' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul11=$resp_r['cantidad'];
}
if(isset($bul11)){
$pdf->SetY(151);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($bul11),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='12' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul12=$resp_r['cantidad'];
}
if(isset($bul12)){
$pdf->SetY(151);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($bul12),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='13' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul13=$resp_r['cantidad'];
}
if(isset($bul13)){
$pdf->SetY(151);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($bul13),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='14' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul14=$resp_r['cantidad'];
}
if(isset($bul14)){
$pdf->SetY(151);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($bul14),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='15' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul15=$resp_r['cantidad'];
}
if(isset($bul15)){
$pdf->SetY(151);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($bul15),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='16' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul16=$resp_r['cantidad'];
}
if(isset($bul16)){
$pdf->SetY(151);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($bul16),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='17' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul17=$resp_r['cantidad'];
}
if(isset($bul17)){
$pdf->SetY(151);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($bul17),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='18' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul18=$resp_r['cantidad'];
}
if(isset($bul18)){
$pdf->SetY(151);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($bul18),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='19' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul19=$resp_r['cantidad'];
}
if(isset($bul19)){
$pdf->SetY(151);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($bul19),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='20' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul20=$resp_r['cantidad'];
}
if(isset($bul20)){
$pdf->SetY(151);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($bul20),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='21' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul21=$resp_r['cantidad'];
}
if(isset($bul21)){
$pdf->SetY(151);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($bul21),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='22' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul22=$resp_r['cantidad'];
}
if(isset($bul22)){
$pdf->SetY(151);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($bul22),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='23' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul23=$resp_r['cantidad'];
}
if(isset($bul23)){
$pdf->SetY(151);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($bul23),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='24' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul24=$resp_r['cantidad'];
}
if(isset($bul24)){
$pdf->SetY(151);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($bul24),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='1' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul1=$resp_r['cantidad'];
}
if(isset($bul1)){
$pdf->SetY(151);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($bul1),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='2' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul2=$resp_r['cantidad'];
}
if(isset($bul2)){
$pdf->SetY(151);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($bul2),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='3' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul3=$resp_r['cantidad'];
}
if(isset($bul3)){
$pdf->SetY(151);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($bul3),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='4' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul4=$resp_r['cantidad'];
}
if(isset($bul4)){
$pdf->SetY(151);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($bul4),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='5' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul5=$resp_r['cantidad'];
}
if(isset($bul5)){
$pdf->SetY(151);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($bul5),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='6' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul6=$resp_r['cantidad'];
}
if(isset($bul6)){
$pdf->SetY(151);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($bul6),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='7' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul7=$resp_r['cantidad'];
}
if(isset($bul7)){
$pdf->SetY(151);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($bul7),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//neurlogico neurologico neurologico neurokogico neurlogico neurologico

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='8' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur8=$resp_r['cantidad'];
}
if(isset($neur8)){
$pdf->SetY(157);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($neur8),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='9' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu9=$resp_r['cantidad'];
}
if(isset($neu9)){
$pdf->SetY(157);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($neu9),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='10' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu10=$resp_r9['cantidad'];
}
if(isset($neu10)){
$pdf->SetY(157);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($neu10),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='11' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu11=$resp_r['cantidad'];
}
if(isset($neu11)){
$pdf->SetY(157);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($neu11),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='12' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu12=$resp_r['cantidad'];
}
if(isset($neu12)){
$pdf->SetY(157);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($neu12),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='13' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu13=$resp_r['cantidad'];
}
if(isset($neu13)){
$pdf->SetY(157);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($neu13),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='14' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu14=$resp_r['cantidad'];
}
if(isset($neu14)){
$pdf->SetY(157);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($neu14),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='15' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu15=$resp_r['cantidad'];
}
if(isset($neu15)){
$pdf->SetY(157);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($neu15),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='16' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu16=$resp_r['cantidad'];
}
if(isset($neu16)){
$pdf->SetY(157);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($neu16),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='17' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu17=$resp_r['cantidad'];
}
if(isset($neu17)){
$pdf->SetY(157);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($neu17),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='18' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu18=$resp_r['cantidad'];
}
if(isset($neu18)){
$pdf->SetY(157);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($neu18),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='19' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu19=$resp_r['cantidad'];
}
if(isset($neu19)){
$pdf->SetY(157);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($neu19),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='20' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu20=$resp_r['cantidad'];
}
if(isset($neu20)){
$pdf->SetY(157);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($neu20),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='21' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu21=$resp_r['cantidad'];
}
if(isset($neu21)){
$pdf->SetY(157);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($neu21),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='22' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu22=$resp_r['cantidad'];
}
if(isset($neu22)){
$pdf->SetY(157);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($neu22),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='23' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu23=$resp_r['cantidad'];
}
if(isset($neu23)){
$pdf->SetY(157);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($neu23),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='24' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu24=$resp_r['cantidad'];
}
if(isset($neu24)){
$pdf->SetY(157);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($neu24),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='1' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu1=$resp_r['cantidad'];
}
if(isset($neu1)){
$pdf->SetY(157);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($neu1),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='2' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu2=$resp_r['cantidad'];
}
if(isset($neu2)){
$pdf->SetY(157);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($neu2),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='3' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu3=$resp_r['cantidad'];
}
if(isset($neu3)){
$pdf->SetY(157);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($neu3),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='4' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu4=$resp_r['cantidad'];
}
if(isset($neu4)){
$pdf->SetY(157);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($neu4),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='5' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu5=$resp_r['cantidad'];
}
if(isset($neu5)){
$pdf->SetY(157);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($neu5),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='6' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu6=$resp_r['cantidad'];
}
if(isset($neu6)){
$pdf->SetY(157);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($neu6),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='7' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu7=$resp_r['cantidad'];
}
if(isset($neu7)){
$pdf->SetY(157);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($neu7),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


//tipo de dieta TIPO DE DIETA TIPO DE DIETA TIPO DE DIETA TIPO DE DIETA
$tipodieta_mat=" ";

$pdf->SetY(164);
$pdf->SetX(40);
$sat = $conexion->query("select * from enf_reg_clin where turno='MATUTINO' AND id_atencion=$id_atencion ORDER by tipodieta_mat DESC LIMIT 1") or die($conexion->error);
while ($sat_r = $sat->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
    $tipodieta_mat=$sat_r['tipodieta_mat'];
   


}
$pdf->Cell(42,6, utf8_decode('MATUTINO: '.$tipodieta_mat),1,0,'C');
$pdf->SetY(164);
$pdf->SetX(82);
$sat = $conexion->query("select * from enf_reg_clin where turno='VESPERTINO' AND id_atencion=$id_atencion ORDER by tipodieta_mat DESC LIMIT 1") or die($conexion->error);
while ($sat_r = $sat->fetch_assoc()) {
  $tipodieta_mat=$sat_r['tipodieta_mat'];
  $pdf->SetFont('Arial', '', 6);
}
$pdf->Cell(49,6, utf8_decode('VESPERTINO: '.$tipodieta_mat),1,0,'C');

$pdf->SetY(164);
$pdf->SetX(131);
$sat = $conexion->query("select * from enf_reg_clin where turno='NOCTURNO' AND id_atencion=$id_atencion ORDER by tipodieta_mat DESC LIMIT 1") or die($conexion->error);
while ($sat_r = $sat->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
 $tipodieta_mat=$sat_r['tipodieta_mat'];

}
$pdf->Cell(77,6, utf8_decode('NOCTURNO: '.$tipodieta_mat),1,0,'C');

//via ORAL VIA ORAL VIA ORAL VIA ORAL VIA ORAL VIA ORAL VIA ORAL VIA ORAL VIA ORAL VIA ORAL VIA ORAL VIA ORAL
$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='8' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $goct=$resp_r['cantidad'];
}
if(isset($goct)){
$pdf->SetY(170);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($goct),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='9' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g=$resp_r['cantidad'];
}
if(isset($g)){
$pdf->SetY(170);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($g),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='10' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gg=$resp_r9['cantidad'];
}
if(isset($gg)){
$pdf->SetY(170);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($gg),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='11' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ggg=$resp_r['cantidad'];
}
if(isset($ggg)){
$pdf->SetY(170);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($ggg),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='12' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gd=$resp_r['cantidad'];
}
if(isset($gd)){
$pdf->SetY(170);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($gd),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='13' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gtr=$resp_r['cantidad'];
}
if(isset($gtr)){
$pdf->SetY(170);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($gtr),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='14' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gca=$resp_r['cantidad'];
}
if(isset($gca)){
$pdf->SetY(170);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($gca),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='15' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $qu=$resp_r['cantidad'];
}
if(isset($qu)){
$pdf->SetY(170);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($qu),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='16' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diess=$resp_r['cantidad'];
}
if(isset($diess)){
$pdf->SetY(170);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($diess),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='17' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ds=$resp_r['cantidad'];
}
if(isset($ds)){
$pdf->SetY(170);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($ds),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='18' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dc=$resp_r['cantidad'];
}
if(isset($dc)){
$pdf->SetY(170);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($dc),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='19' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dn=$resp_r['cantidad'];
}
if(isset($dn)){
$pdf->SetY(170);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($dn),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='20' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ve=$resp_r['cantidad'];
}
if(isset($ve)){
$pdf->SetY(170);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($ve),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='21' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vu=$resp_r['cantidad'];
}
if(isset($vu)){
$pdf->SetY(170);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($vu),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='22' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vd=$resp_r['cantidad'];
}
if(isset($vd)){
$pdf->SetY(170);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($vd),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='23' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vt=$resp_r['cantidad'];
}
if(isset($vt)){
$pdf->SetY(170);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($vt),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='24' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vc=$resp_r['cantidad'];
}
if(isset($vc)){
$pdf->SetY(170);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($vc),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='1' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $un=$resp_r['cantidad'];
}
if(isset($un)){
$pdf->SetY(170);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($un),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='2' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dos=$resp_r['cantidad'];
}
if(isset($dos)){
$pdf->SetY(170);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($dos),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='3' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $tres=$resp_r['cantidad'];
}
if(isset($tres)){
$pdf->SetY(170);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($tres),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='4' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cuat=$resp_r['cantidad'];
}
if(isset($cuat)){
$pdf->SetY(170);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($cuat),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='5' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cin=$resp_r['cantidad'];
}
if(isset($cin)){
$pdf->SetY(170);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($cin),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='6' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $seis=$resp_r['cantidad'];
}
if(isset($seis)){
$pdf->SetY(170);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($seis),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='7' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $siet=$resp_r['cantidad'];
}
if(isset($siet)){
$pdf->SetY(170);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($siet),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


//VIA ENTERAL VIA ENTERAL VIA ENTERAL VIA ENTERAL VIA ENTERAL VIA ENTERAL VIA ENTERAL VIA ENTERAL VIA ENTERAL

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='8' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nmoct=$resp_r['cantidad'];
}
if(isset($nmoct)){
$pdf->SetY(176);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($nmoct),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='9' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nm=$resp_r['cantidad'];
}
if(isset($nm)){
$pdf->SetY(176);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($nm),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='10' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dm=$resp_r9['cantidad'];
}
if(isset($dm)){
$pdf->SetY(176);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($dm),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='11' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $om=$resp_r['cantidad'];
}
if(isset($om)){
$pdf->SetY(176);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($om),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='12' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dom=$resp_r['cantidad'];
}
if(isset($dom)){
$pdf->SetY(176);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($dom),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='13' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $trm=$resp_r['cantidad'];
}
if(isset($trm)){
$pdf->SetY(176);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($trm),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='14' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $catm=$resp_r['cantidad'];
}
if(isset($catm)){
$pdf->SetY(176);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($catm),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='15' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $quim=$resp_r['cantidad'];
}
if(isset($quim)){
$pdf->SetY(176);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($quim),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='16' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dsm=$resp_r['cantidad'];
}
if(isset($dsm)){
$pdf->SetY(176);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($dsm),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='17' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dsim=$resp_r['cantidad'];
}
if(isset($dsim)){
$pdf->SetY(176);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($dsim),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='18' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dsom=$resp_r['cantidad'];
}
if(isset($dsom)){
$pdf->SetY(176);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($dsom),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='19' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dnm=$resp_r['cantidad'];
}
if(isset($dnm)){
$pdf->SetY(176);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($dnm),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='20' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $veim=$resp_r['cantidad'];
}
if(isset($veim)){
$pdf->SetY(176);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($veim),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='21' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vum=$resp_r['cantidad'];
}
if(isset($vum)){
$pdf->SetY(176);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($vum),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='22' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vdm=$resp_r['cantidad'];
}
if(isset($vdm)){
$pdf->SetY(176);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($vdm),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='23' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vtm=$resp_r['cantidad'];
}
if(isset($vtm)){
$pdf->SetY(176);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($vtm),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='24' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vcum=$resp_r['cantidad'];
}
if(isset($vcum)){
$pdf->SetY(176);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($vcum),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='1' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vcim=$resp_r['cantidad'];
}
if(isset($vcim)){
$pdf->SetY(176);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($vcim),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='2' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vesm=$resp_r['cantidad'];
}
if(isset($vesm)){
$pdf->SetY(176);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($vesm),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='3' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vesim=$resp_r['cantidad'];
}
if(isset($vesim)){
$pdf->SetY(176);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($vesim),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='4' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cuatrom=$resp_r['cantidad'];
}
if(isset($cuatrom)){
$pdf->SetY(176);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($cuatrom),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='5' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cincom=$resp_r['cantidad'];
}
if(isset($cincom)){
$pdf->SetY(176);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($cincom),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='6' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sesism=$resp_r['cantidad'];
}
if(isset($sesism)){
$pdf->SetY(176);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($sesism),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='7' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sietem=$resp_r['cantidad'];
}
if(isset($sietem)){
$pdf->SetY(176);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($sietem),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


//HEMODERIVADOS HEMODERIVADOS HEMODERIVADOS HEMODERIVADOS HEMODERIVADOS HEMODERIVADOS HEMODERIVADOS HEMODERIVADOS

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='8' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vnoc=$resp_r['cantidad'];
}
if(isset($vnoc)){
$pdf->SetY(182);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($vnoc),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='9' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vn=$resp_r['cantidad'];
}
if(isset($vn)){
$pdf->SetY(182);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($vn),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='10' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vd=$resp_r9['cantidad'];
}
if(isset($vd)){
$pdf->SetY(182);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($vd),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='11' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vo=$resp_r['cantidad'];
}
if(isset($vo)){
$pdf->SetY(182);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($vo),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='12' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vdo=$resp_r['cantidad'];
}
if(isset($vdo)){
$pdf->SetY(182);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($vdo),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='13' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vtr=$resp_r['cantidad'];
}
if(isset($vtr)){
$pdf->SetY(182);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($vtr),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='14' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vca=$resp_r['cantidad'];
}
if(isset($vca)){
$pdf->SetY(182);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($vca),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='15' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vqu=$resp_r['cantidad'];
}
if(isset($vqu)){
$pdf->SetY(182);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($vqu),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='16' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vds=$resp_r['cantidad'];
}
if(isset($vds)){
$pdf->SetY(182);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($vds),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='17' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vdss=$resp_r['cantidad'];
}
if(isset($vdss)){
$pdf->SetY(182);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($vdss),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='18' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vdoc=$resp_r['cantidad'];
}
if(isset($vdoc)){
$pdf->SetY(182);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($vdoc),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='19' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vdnn=$resp_r['cantidad'];
}
if(isset($vdnn)){
$pdf->SetY(182);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($vdnn),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='20' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vvei=$resp_r['cantidad'];
}
if(isset($vvei)){
$pdf->SetY(182);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($vvei),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='21' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vvun=$resp_r['cantidad'];
}
if(isset($vvun)){
$pdf->SetY(182);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($vvun),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='22' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vvdos=$resp_r['cantidad'];
}
if(isset($vvdos)){
$pdf->SetY(182);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($vvdos),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='23' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vvtres=$resp_r['cantidad'];
}
if(isset($vvtres)){
$pdf->SetY(182);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($vvtres),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='24' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vvcua=$resp_r['cantidad'];
}
if(isset($vvcua)){
$pdf->SetY(182);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($vvcua),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='1' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vuno=$resp_r['cantidad'];
}
if(isset($vuno)){
$pdf->SetY(182);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($vuno),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='2' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vpdos=$resp_r['cantidad'];
}
if(isset($vpdos)){
$pdf->SetY(182);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($vpdos),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='3' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vptres=$resp_r['cantidad'];
}
if(isset($vptres)){
$pdf->SetY(182);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($vptres),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='4' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vcuatro=$resp_r['cantidad'];
}
if(isset($vcuatro)){
$pdf->SetY(182);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($vcuatro),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='5' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vcinco=$resp_r['cantidad'];
}
if(isset($vcinco)){
$pdf->SetY(182);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($vcinco),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='6' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vsesis=$resp_r['cantidad'];
}
if(isset($vsesis)){
$pdf->SetY(182);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($vsesis),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='7' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vsiete=$resp_r['cantidad'];
}
if(isset($vsiete)){
$pdf->SetY(182);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($vsiete),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


//VIA PARENTERAL VIA PARENTERAL VIA PARENTERAL VIA PARENTERAL VIA PARENTERAL VIA PARENTERAL VIA PARENTERAL

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='8' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $canoctt=$resp_r['cantidad'];
}
if(isset($canoctt)){
$pdf->SetY(188);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($canoctt),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='9' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $can=$resp_r['cantidad'];
}
if(isset($can)){
$pdf->SetY(188);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($can),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='10' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cardie=$resp_r9['cantidad'];
}
if(isset($cardie)){
$pdf->SetY(188);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($cardie),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='11' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cargaso=$resp_r['cantidad'];
}
if(isset($cargaso)){
$pdf->SetY(188);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($cargaso),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='12' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cargasdo=$resp_r['cantidad'];
}
if(isset($cargasdo)){
$pdf->SetY(188);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($cargasdo),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='13' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cargastr=$resp_r['cantidad'];
}
if(isset($cargastr)){
$pdf->SetY(188);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($cargastr),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='14' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cargascat=$resp_r['cantidad'];
}
if(isset($cargascat)){
$pdf->SetY(188);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($cargascat),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='15' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cargasquince=$resp_r['cantidad'];
}
if(isset($cargasquince)){
$pdf->SetY(188);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($cargasquince),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='16' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cargasds=$resp_r['cantidad'];
}
if(isset($cargasds)){
$pdf->SetY(188);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($cargasds),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='17' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cargasdss=$resp_r['cantidad'];
}
if(isset($cargasdss)){
$pdf->SetY(188);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($cargasdss),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='18' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cargasdoc=$resp_r['cantidad'];
}
if(isset($cargasdoc)){
$pdf->SetY(188);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($cargasdoc),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='19' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cargasdn=$resp_r['cantidad'];
}
if(isset($cargasdn)){
$pdf->SetY(188);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($cargasdn),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='20' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cargasvei=$resp_r['cantidad'];
}
if(isset($cargasvei)){
$pdf->SetY(188);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($cargasvei),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='21' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carvuno=$resp_r['cantidad'];
}
if(isset($carvuno)){
$pdf->SetY(188);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($carvuno),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='22' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carvdos=$resp_r['cantidad'];
}
if(isset($carvdos)){
$pdf->SetY(188);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($carvdos),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='23' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carvtres=$resp_r['cantidad'];
}
if(isset($carvtres)){
$pdf->SetY(188);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($carvtres),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='24' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carvcu=$resp_r['cantidad'];
}
if(isset($carvcu)){
$pdf->SetY(188);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($carvcu),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='1' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $caru=$resp_r['cantidad'];
}
if(isset($caru)){
$pdf->SetY(188);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($caru),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='2' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cardos=$resp_r['cantidad'];
}
if(isset($cardos)){
$pdf->SetY(188);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($cardos),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='3' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carres=$resp_r['cantidad'];
}
if(isset($carres)){
$pdf->SetY(188);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($carres),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='4' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carc=$resp_r['cantidad'];
}
if(isset($carc)){
$pdf->SetY(188);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($carc),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='5' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carcin=$resp_r['cantidad'];
}
if(isset($carcin)){
$pdf->SetY(188);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($carcin),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='6' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carse=$resp_r['cantidad'];
}
if(isset($carse)){
$pdf->SetY(188);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($carse),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='7' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carsiete=$resp_r['cantidad'];
}
if(isset($carsiete)){
$pdf->SetY(188);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($carsiete),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


//INFUSIONES INFUSIONES INFUSIONES INFUSIONES INFUSIONES INFUSIONES INFUSIONES INFUSIONES INFUSIONES
$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='8' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $innoct=$resp_r['cantidad'];
}
if(isset($innoct)){
$pdf->SetY(194);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($innoct),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='9' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $inn=$resp_r['cantidad'];
}
if(isset($inn)){
$pdf->SetY(194);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($inn),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='10' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ind=$resp_r9['cantidad'];
}
if(isset($ind)){
$pdf->SetY(194);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($ind),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='11' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ino=$resp_r['cantidad'];
}
if(isset($ino)){
$pdf->SetY(194);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($ino),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='12' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $indo=$resp_r['cantidad'];
}
if(isset($indo)){
$pdf->SetY(194);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($indo),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='13' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intrece=$resp_r['cantidad'];
}
if(isset($intrece)){
$pdf->SetY(194);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($intrece),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='14' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $incat=$resp_r['cantidad'];
}
if(isset($incat)){
$pdf->SetY(194);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($incat),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='15' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $inquin=$resp_r['cantidad'];
}
if(isset($inquin)){
$pdf->SetY(194);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($inquin),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='16' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $inds=$resp_r['cantidad'];
}
if(isset($inds)){
$pdf->SetY(194);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($inds),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='17' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $indss=$resp_r['cantidad'];
}
if(isset($indss)){
$pdf->SetY(194);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($indss),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='18' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $indoch=$resp_r['cantidad'];
}
if(isset($indoch)){
$pdf->SetY(194);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($indoch),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='19' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $indn=$resp_r['cantidad'];
}
if(isset($indn)){
$pdf->SetY(194);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($indn),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='20' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $inveinte=$resp_r['cantidad'];
}
if(isset($inveinte)){
$pdf->SetY(194);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($inveinte),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='21' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $inveun=$resp_r['cantidad'];
}
if(isset($inveun)){
$pdf->SetY(194);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($inveun),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='22' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $inved=$resp_r['cantidad'];
}
if(isset($inved)){
$pdf->SetY(194);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($inved),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='23' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $inveitr=$resp_r['cantidad'];
}
if(isset($inveitr)){
$pdf->SetY(194);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($inveitr),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='24' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $invecuat=$resp_r['cantidad'];
}
if(isset($invecuat)){
$pdf->SetY(194);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($invecuat),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='1' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $inuno=$resp_r['cantidad'];
}
if(isset($inuno)){
$pdf->SetY(194);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($inuno),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='2' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $indos=$resp_r['cantidad'];
}
if(isset($indos)){
$pdf->SetY(194);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($indos),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='3' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intres=$resp_r['cantidad'];
}
if(isset($intres)){
$pdf->SetY(194);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($intres),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='4' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $incuatro=$resp_r['cantidad'];
}
if(isset($incuatro)){
$pdf->SetY(194);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($incuatro),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='5' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $incinco=$resp_r['cantidad'];
}
if(isset($incinco)){
$pdf->SetY(194);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($incinco),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='6' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $inseis=$resp_r['cantidad'];
}
if(isset($inseis)){
$pdf->SetY(194);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($inseis),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='7' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insiete=$resp_r['cantidad'];
}
if(isset($insiete)){
$pdf->SetY(194);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($insiete),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


//OTROS INGRESO OTROS INGRESOS OTROS INGRESOS OTROS INGRESOS OTROS INGRESOS OTROS INGRESOS OTROS INGRESOS

$resp = $conexion->query("select * from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and hora='8' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $onoc=$resp_r['cantidad'];
}
if(isset($onoc)){
$pdf->SetY(200);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($onoc),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and hora='9' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $on=$resp_r['cantidad'];
}
if(isset($on)){
$pdf->SetY(200);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($on),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and hora='10' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $odie=$resp_r9['cantidad'];
}
if(isset($odie)){
$pdf->SetY(200);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($odie),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and hora='11' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oonce=$resp_r['cantidad'];
}
if(isset($oonce)){
$pdf->SetY(200);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($oonce),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and hora='12' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $odoce=$resp_r['cantidad'];
}
if(isset($odoce)){
$pdf->SetY(200);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($odoce),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and hora='13' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $otrece=$resp_r['cantidad'];
}
if(isset($otrece)){
$pdf->SetY(200);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($otrece),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and hora='14' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ocat=$resp_r['cantidad'];
}
if(isset($ocat)){
$pdf->SetY(200);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($ocat),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and hora='15' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oquin=$resp_r['cantidad'];
}
if(isset($oquin)){
$pdf->SetY(200);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($oquin),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and hora='16' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $odseis=$resp_r['cantidad'];
}
if(isset($odseis)){
$pdf->SetY(200);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($odseis),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and hora='17' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $odsiete=$resp_r['cantidad'];
}
if(isset($odsiete)){
$pdf->SetY(200);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($odsiete),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and hora='18' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $odocho=$resp_r['cantidad'];
}
if(isset($odocho)){
$pdf->SetY(200);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($odocho),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and hora='19' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $odn=$resp_r['cantidad'];
}
if(isset($odn)){
$pdf->SetY(200);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($odn),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and hora='20' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oveinte=$resp_r['cantidad'];
}
if(isset($oveinte)){
$pdf->SetY(200);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($oveinte),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and hora='21' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ovuno=$resp_r['cantidad'];
}
if(isset($ovuno)){
$pdf->SetY(200);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($ovuno),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and hora='22' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ovdos=$resp_r['cantidad'];
}
if(isset($ovdos)){
$pdf->SetY(200);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($ovdos),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and hora='23' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ovtres=$resp_r['cantidad'];
}
if(isset($ovtres)){
$pdf->SetY(200);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($ovtres),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and hora='24' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ovcuat=$resp_r['cantidad'];
}
if(isset($ovcuat)){
$pdf->SetY(200);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($ovcuat),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and hora='1' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $otuno=$resp_r['cantidad'];
}
if(isset($otuno)){
$pdf->SetY(200);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($otuno),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and hora='2' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $otdos=$resp_r['cantidad'];
}
if(isset($otdos)){
$pdf->SetY(200);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($otdos),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and hora='3' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ottres=$resp_r['cantidad'];
}
if(isset($ottres)){
$pdf->SetY(200);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($ottres),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and hora='4' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $otcua=$resp_r['cantidad'];
}
if(isset($otcua)){
$pdf->SetY(200);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($otcua),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and hora='5' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $otcin=$resp_r['cantidad'];
}
if(isset($otcin)){
$pdf->SetY(200);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($otcin),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and hora='6' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $otseis=$resp_r['cantidad'];
}
if(isset($otseis)){
$pdf->SetY(200);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($otseis),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and hora='7' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $otsiete=$resp_r['cantidad'];
}
if(isset($otsiete)){
$pdf->SetY(200);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($otsiete),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//NUTRICION PARENERA UTRICION PARENTERAL NUTRICION PARENTERAL NUTRICION PARENTERAL NUTRICON PARENTERAL

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='8' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutrio=$resp_r['cantidad'];
}
if(isset($nutrio)){
$pdf->SetY(203);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($nutrio),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='9' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutrinuev=$resp_r['cantidad'];
}
if(isset($nutrinuev)){
$pdf->SetY(203);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($nutrinuev),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='10' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutridiez=$resp_r9['cantidad'];
}
if(isset($nutridiez)){
$pdf->SetY(203);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($nutridiez),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='11' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutrionce=$resp_r['cantidad'];
}
if(isset($nutrionce)){
$pdf->SetY(203);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($nutrionce),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='12' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutridoce=$resp_r['cantidad'];
}
if(isset($nutridoce)){
$pdf->SetY(203);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($nutridoce),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='13' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutritrece=$resp_r['cantidad'];
}
if(isset($nutritrece)){
$pdf->SetY(203);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($nutritrece),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='14' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutricat=$resp_r['cantidad'];
}
if(isset($nutricat)){
$pdf->SetY(203);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($nutricat),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='15' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutriquince=$resp_r['cantidad'];
}
if(isset($nutriquince)){
$pdf->SetY(203);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($nutriquince),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='16' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutridesseis=$resp_r['cantidad'];
}
if(isset($nutridesseis)){
$pdf->SetY(203);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($nutridesseis),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='17' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutridesie=$resp_r['cantidad'];
}
if(isset($nutridesie)){
$pdf->SetY(203);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($nutridesie),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='18' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutridocho=$resp_r['cantidad'];
}
if(isset($nutridocho)){
$pdf->SetY(203);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($nutridocho),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='19' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutridnueveee=$resp_r['cantidad'];
}
if(isset($nutridnueveee)){
$pdf->SetY(203);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($nutridnueveee),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='20' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutriveinte=$resp_r['cantidad'];
}
if(isset($nutriveinte)){
$pdf->SetY(203);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($nutriveinte),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='21' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutrivuno=$resp_r['cantidad'];
}
if(isset($nutrivuno)){
$pdf->SetY(203);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($nutrivuno),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='22' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nurivdos=$resp_r['cantidad'];
}
if(isset($nurivdos)){
$pdf->SetY(203);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($nurivdos),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='23' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutrivcuat=$resp_r['cantidad'];
}
if(isset($nutrivcuat)){
$pdf->SetY(203);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($nutrivcuat),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='24' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutrivcdd=$resp_r['cantidad'];
}
if(isset($nutrivcdd)){
$pdf->SetY(203);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($nutrivcdd),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='1' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutrino=$resp_r['cantidad'];
}
if(isset($nutrino)){
$pdf->SetY(203);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($nutrino),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='2' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutridosss=$resp_r['cantidad'];
}
if(isset($nutridosss)){
$pdf->SetY(203);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($nutridosss),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='3' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutritres=$resp_r['cantidad'];
}
if(isset($nutritres)){
$pdf->SetY(203);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($nutritres),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='4' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutricc=$resp_r['cantidad'];
}
if(isset($nutricc)){
$pdf->SetY(203);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($nutricc),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='5' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutricinc=$resp_r['cantidad'];
}
if(isset($nutricinc)){
$pdf->SetY(203);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($nutricinc),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='6' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutriseiss=$resp_r['cantidad'];
}
if(isset($nutriseiss)){
$pdf->SetY(203);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($nutriseiss),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='7' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutrisiete=$resp_r['cantidad'];
}
if(isset($nutrisiete)){
$pdf->SetY(203);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($nutrisiete),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


//INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL MATUTINO

$resp = $conexion->query("select SUM(cantidad) as sumbase from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and id_atencion=$id_atencion and (hora='8' or hora='9' or hora='10' or hora='11' or hora='12' or hora='13')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumbase=$resp_r['sumbase'];
}

$resp = $conexion->query("select SUM(cantidad) as summed from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and id_atencion=$id_atencion and (hora='8' or hora='9' or hora='10' or hora='11' or hora='12' or hora='13')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $summed=$resp_r['summed'];
}

$resp = $conexion->query("select SUM(cantidad) as via from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and id_atencion=$id_atencion and (hora='8' or hora='9' or hora='10' or hora='11' or hora='12' or hora='13')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $via=$resp_r['via'];
}

$resp = $conexion->query("select SUM(cantidad) as sumcargas from ing_enf_quir where des='CARGAS' and fecha='$fechar' and id_atencion=$id_atencion and (hora='8' or hora='9' or hora='10' or hora='11' or hora='12' or hora='13')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumcargas=$resp_r['sumcargas'];
}


$resp = $conexion->query("select SUM(cantidad) as sumcantidad from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and id_atencion=$id_atencion and (hora='8' or hora='9' or hora='10' or hora='11' or hora='12' or hora='13')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumcantidad=$resp_r['sumcantidad'];
}

$resp = $conexion->query("select SUM(cantidad) as sum from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and id_atencion=$id_atencion and (hora='8' or hora='9' or hora='10' or hora='11' or hora='12' or hora='13')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sum=$resp_r['sum'];
}

$resp = $conexion->query("select SUM(cantidad) as sumnut from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and id_atencion=$id_atencion and (hora='8' or hora='9' or hora='10' or hora='11' or hora='12' or hora='13')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumnut=$resp_r['sumnut'];
}


$sumatotal=$sumbase+$summed+$via+$sumcargas+$sumcantidad+$sum+$sumnut;

/*if(isset($sum)){
*/
$pdf->SetY(206);
$pdf->SetX(40);
$pdf->Cell(42,6, utf8_decode($sumatotal . ' ML'),1,0,'C');
/*
}else{
  $pdf->SetY(206);
$pdf->SetX(40);
  $pdf->Cell(42,6, utf8_decode(''),1,0,'C');
}*/

//INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL VESPERTINO

$resp = $conexion->query("select SUM(cantidad) as sumbasev from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and id_atencion=$id_atencion and (hora='14' or hora='15' or hora='16' or hora='17' or hora='18' or hora='19' or hora='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumbasev=$resp_r['sumbasev'];
}

$resp = $conexion->query("select SUM(cantidad) as summedv from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and id_atencion=$id_atencion and (hora='14' or hora='15' or hora='16' or hora='17' or hora='18' or hora='19' or hora='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $summedv=$resp_r['summedv'];
}

$resp = $conexion->query("select SUM(cantidad) as viav from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and id_atencion=$id_atencion and (hora='14' or hora='15' or hora='16' or hora='17' or hora='18' or hora='19' or hora='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $viav=$resp_r['viav'];
}

$resp = $conexion->query("select SUM(cantidad) as sumcargasv from ing_enf_quir where des='CARGAS' and fecha='$fechar' and id_atencion=$id_atencion and (hora='14' or hora='15' or hora='16' or hora='17' or hora='18' or hora='19' or hora='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumcargasv=$resp_r['sumcargasv'];
}


$resp = $conexion->query("select SUM(cantidad) as sumcantidadv from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and id_atencion=$id_atencion and (hora='14' or hora='15' or hora='16' or hora='17' or hora='18' or hora='19' or hora='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumcantidadv=$resp_r['sumcantidadv'];
}

$resp = $conexion->query("select SUM(cantidad) as sumv from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and id_atencion=$id_atencion and (hora='14' or hora='15' or hora='16' or hora='17' or hora='18' or hora='19' or hora='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumv=$resp_r['sumv'];
}

$resp = $conexion->query("select SUM(cantidad) as sumnutrv from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and id_atencion=$id_atencion and (hora='14' or hora='15' or hora='16' or hora='17' or hora='18' or hora='19' or hora='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumnutrv=$resp_r['sumnutrv'];
}


$sumatotalv=$sumbasev+$summedv+$viav+$sumcargasv+$sumcantidadv+$sumv+$sumnutrv;


/*if(isset($g9)){
*/
$pdf->SetY(206);
$pdf->SetX(82);
$pdf->Cell(49,6, utf8_decode($sumatotalv . ' ML'),1,0,'C');
/*
}else{
  $pdf->SetY(206);
$pdf->SetX(82);
 $pdf->Cell(49,6, utf8_decode(''),1,0,'C');
}*/

//INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL NOCTURNO NOCTURNO

$resp = $conexion->query("select SUM(cantidad) as sumbasen from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and id_atencion=$id_atencion and (hora='21' or hora='22' or hora='23' or hora='24' or hora='1' or hora='2' or hora='3' or hora='4' or hora='5' or hora='6' or hora='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumbasen=$resp_r['sumbasen'];
}

$resp = $conexion->query("select SUM(cantidad) as summedn from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and id_atencion=$id_atencion and (hora='21' or hora='22' or hora='23' or hora='24' or hora='1' or hora='2' or hora='3' or hora='4' or hora='5' or hora='6' or hora='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $summedn=$resp_r['summedn'];
}

$resp = $conexion->query("select SUM(cantidad) as vian from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and id_atencion=$id_atencion and (hora='21' or hora='22' or hora='23' or hora='24' or hora='1' or hora='2' or hora='3' or hora='4' or hora='5' or hora='6' or hora='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vian=$resp_r['vian'];
}

$resp = $conexion->query("select SUM(cantidad) as sumcargasn from ing_enf_quir where des='CARGAS' and fecha='$fechar' and id_atencion=$id_atencion and (hora='21' or hora='22' or hora='23' or hora='24' or hora='1' or hora='2' or hora='3' or hora='4' or hora='5' or hora='6' or hora='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumcargasn=$resp_r['sumcargasn'];
}


$resp = $conexion->query("select SUM(cantidad) as sumcantidadn from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and id_atencion=$id_atencion and (hora='21' or hora='22' or hora='23' or hora='24' or hora='1' or hora='2' or hora='3' or hora='4' or hora='5' or hora='6' or hora='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumcantidadn=$resp_r['sumcantidadn'];
}

$resp = $conexion->query("select SUM(cantidad) as sumn from ing_enf_quir where des='TRANSFUSION SANGUINEA' and fecha='$fechar' and id_atencion=$id_atencion and (hora='21' or hora='22' or hora='23' or hora='24' or hora='1' or hora='2' or hora='3' or hora='4' or hora='5' or hora='6' or hora='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumn=$resp_r['sumn'];
}

$resp = $conexion->query("select SUM(cantidad) as sumnutno from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and id_atencion=$id_atencion and (hora='21' or hora='22' or hora='23' or hora='24' or hora='1' or hora='2' or hora='3' or hora='4' or hora='5' or hora='6' or hora='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumnutno=$resp_r['sumnutno'];
}


$sumatotaln=$sumbasen+$summedn+$vian+$sumcargasn+$sumcantidadn+$sumn+$sumnutno;



/*if(isset($g10)){
  */
$pdf->SetY(206);
$pdf->SetX(131);
$pdf->Cell(77,6, utf8_decode($sumatotaln . ' ML'),1,0,'C');
/*
}else{

  $pdf->SetY(206);
$pdf->SetX(131);
  $pdf->Cell(77,6, utf8_decode(''),1,0,'C');
}
*/


//DIURESIS DIURESIS DIURESIS DIURESIS DIURESIS DIURESUS DIURESIS DIURESIS DIURESIS DIURESIS DIURESIS

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $onoctt=$resp_r['cant_eg'];
}
if(isset($onoctt)){
$pdf->SetY(213);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($onoctt),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $on=$resp_r['cant_eg'];
}
if(isset($on)){
$pdf->SetY(213);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($on),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ordies=$resp_r9['cant_eg'];
}
if(isset($ordies)){
$pdf->SetY(213);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($ordies),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oro=$resp_r['cant_eg'];
}
if(isset($oro)){
$pdf->SetY(213);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($oro),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ordoce=$resp_r['cant_eg'];
}
if(isset($ordoce)){
$pdf->SetY(213);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($ordoce),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ortrece=$resp_r['cant_eg'];
}
if(isset($ortrece)){
$pdf->SetY(213);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($ortrece),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orcat=$resp_r['cant_eg'];
}
if(isset($orcat)){
$pdf->SetY(213);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($orcat),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orquin=$resp_r['cant_eg'];
}
if(isset($orquin)){
$pdf->SetY(213);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($orquin),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ords=$resp_r['cant_eg'];
}
if(isset($ords)){
$pdf->SetY(213);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($ords),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ordss=$resp_r['cant_eg'];
}
if(isset($ordss)){
$pdf->SetY(213);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($ordss),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ordsocho=$resp_r['cant_eg'];
}
if(isset($ordsocho)){
$pdf->SetY(213);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($ordsocho),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ordn=$resp_r['cant_eg'];
}
if(isset($ordn)){
$pdf->SetY(213);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($ordn),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orvei=$resp_r['cant_eg'];
}
if(isset($orvei)){
$pdf->SetY(213);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($orvei),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orvuno=$resp_r['cant_eg'];
}
if(isset($orvuno)){
$pdf->SetY(213);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($orvuno),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orvdos=$resp_r['cant_eg'];
}
if(isset($orvdos)){
$pdf->SetY(213);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($orvdos),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orveintres=$resp_r['cant_eg'];
}
if(isset($orveintres)){
$pdf->SetY(213);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($orveintres),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orvecua=$resp_r['cant_eg'];
}
if(isset($orvecua)){
$pdf->SetY(213);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($orvecua),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oruno=$resp_r['cant_eg'];
}
if(isset($oruno)){
$pdf->SetY(213);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($oruno),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ordos=$resp_r['cant_eg'];
}
if(isset($ordos)){
$pdf->SetY(213);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($ordos),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ortres=$resp_r['cant_eg'];
}
if(isset($ortres)){
$pdf->SetY(213);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($ortres),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orcuatro=$resp_r['cant_eg'];
}
if(isset($orcuatro)){
$pdf->SetY(213);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($orcuatro),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orcinco=$resp_r['cant_eg'];
}
if(isset($orcinco)){
$pdf->SetY(213);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($orcinco),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orsesis=$resp_r['cant_eg'];
}
if(isset($orsesis)){
$pdf->SetY(213);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($orsesis),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orsiete=$resp_r['cant_eg'];
}
if(isset($orsiete)){
$pdf->SetY(213);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($orsiete),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//SARATOGA SARATOGA SARATOGA SARATOGA SARATOGA SARATOGA SARATOGA SARATOGA

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $onoctt=$resp_r['cant_eg'];
}
if(isset($onoctt)){
$pdf->SetY(216);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($onoctt),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $on=$resp_r['cant_eg'];
}
if(isset($on)){
$pdf->SetY(216);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($on),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ordies=$resp_r9['cant_eg'];
}
if(isset($ordies)){
$pdf->SetY(216);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($ordies),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oro=$resp_r['cant_eg'];
}
if(isset($oro)){
$pdf->SetY(216);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($oro),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ordoce=$resp_r['cant_eg'];
}
if(isset($ordoce)){
$pdf->SetY(216);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($ordoce),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ortrece=$resp_r['cant_eg'];
}
if(isset($ortrece)){
$pdf->SetY(216);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($ortrece),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orcat=$resp_r['cant_eg'];
}
if(isset($orcat)){
$pdf->SetY(216);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($orcat),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orquin=$resp_r['cant_eg'];
}
if(isset($orquin)){
$pdf->SetY(216);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($orquin),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ords=$resp_r['cant_eg'];
}
if(isset($ords)){
$pdf->SetY(216);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($ords),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ordss=$resp_r['cant_eg'];
}
if(isset($ordss)){
$pdf->SetY(216);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($ordss),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ordsocho=$resp_r['cant_eg'];
}
if(isset($ordsocho)){
$pdf->SetY(216);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($ordsocho),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ordn=$resp_r['cant_eg'];
}
if(isset($ordn)){
$pdf->SetY(216);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($ordn),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orvei=$resp_r['cant_eg'];
}
if(isset($orvei)){
$pdf->SetY(216);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($orvei),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orvuno=$resp_r['cant_eg'];
}
if(isset($orvuno)){
$pdf->SetY(216);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($orvuno),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orvdos=$resp_r['cant_eg'];
}
if(isset($orvdos)){
$pdf->SetY(216);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($orvdos),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orveintres=$resp_r['cant_eg'];
}
if(isset($orveintres)){
$pdf->SetY(216);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($orveintres),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orvecua=$resp_r['cant_eg'];
}
if(isset($orvecua)){
$pdf->SetY(216);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($orvecua),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oruno=$resp_r['cant_eg'];
}
if(isset($oruno)){
$pdf->SetY(216);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($oruno),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ordos=$resp_r['cant_eg'];
}
if(isset($ordos)){
$pdf->SetY(216);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($ordos),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ortres=$resp_r['cant_eg'];
}
if(isset($ortres)){
$pdf->SetY(216);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($ortres),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orcuatro=$resp_r['cant_eg'];
}
if(isset($orcuatro)){
$pdf->SetY(216);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($orcuatro),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orcinco=$resp_r['cant_eg'];
}
if(isset($orcinco)){
$pdf->SetY(216);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($orcinco),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orsesis=$resp_r['cant_eg'];
}
if(isset($orsesis)){
$pdf->SetY(216);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($orsesis),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orsiete=$resp_r['cant_eg'];
}
if(isset($orsiete)){
$pdf->SetY(216);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($orsiete),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}



//ESTOMAS ESTOMAS ESTOMAS ESTOMAS ESTOMAS ESTOMAS ESTOMAS ESTOMAS ESTOMAS ESTOMAS

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomnoc=$resp_r['cant_eg'];
}
if(isset($vomnoc)){
$pdf->SetY(219);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($vomnoc),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomn=$resp_r['cant_eg'];
}
if(isset($vomn)){
$pdf->SetY(219);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($vomn),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomd=$resp_r9['cant_eg'];
}
if(isset($vomd)){
$pdf->SetY(219);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($vomd),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomonce=$resp_r['cant_eg'];
}
if(isset($vomonce)){
$pdf->SetY(219);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($vomonce),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomdoce=$resp_r['cant_eg'];
}
if(isset($vomdoce)){
$pdf->SetY(219);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($vomdoce),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomtrece=$resp_r['cant_eg'];
}
if(isset($vomtrece)){
$pdf->SetY(219);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($vomtrece),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomcato=$resp_r['cant_eg'];
}
if(isset($vomcato)){
$pdf->SetY(219);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($vomcato),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomquin=$resp_r['cant_eg'];
}
if(isset($vomquin)){
$pdf->SetY(219);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($vomquin),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomdiseis=$resp_r['cant_eg'];
}
if(isset($vomdiseis)){
$pdf->SetY(219);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($vomdiseis),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomdisiete=$resp_r['cant_eg'];
}
if(isset($vomdisiete)){
$pdf->SetY(219);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($vomdisiete),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomdioch=$resp_r['cant_eg'];
}
if(isset($vomdioch)){
$pdf->SetY(219);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($vomdioch),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomdin=$resp_r['cant_eg'];
}
if(isset($vomdin)){
$pdf->SetY(219);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($vomdin),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomveinte=$resp_r['cant_eg'];
}
if(isset($vomveinte)){
$pdf->SetY(219);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($vomveinte),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomveuno=$resp_r['cant_eg'];
}
if(isset($vomveuno)){
$pdf->SetY(219);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($vomveuno),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomvdos=$resp_r['cant_eg'];
}
if(isset($vomvdos)){
$pdf->SetY(219);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($vomvdos),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomvtres=$resp_r['cant_eg'];
}
if(isset($vomvtres)){
$pdf->SetY(219);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($vomvtres),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomvcuatro=$resp_r['cant_eg'];
}
if(isset($vomvcuatro)){
$pdf->SetY(219);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($vomvcuatro),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomuno=$resp_r['cant_eg'];
}
if(isset($vomuno)){
$pdf->SetY(219);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($vomuno),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomdos=$resp_r['cant_eg'];
}
if(isset($vomdos)){
$pdf->SetY(219);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($vomdos),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomtres=$resp_r['cant_eg'];
}
if(isset($vomtres)){
$pdf->SetY(219);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($vomtres),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomcua=$resp_r['cant_eg'];
}
if(isset($vomcua)){
$pdf->SetY(219);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($vomcua),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomcin=$resp_r['cant_eg'];
}
if(isset($vomcin)){
$pdf->SetY(219);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($vomcin),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomse=$resp_r['cant_eg'];
}
if(isset($vomse)){
$pdf->SetY(219);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($vomse),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomsiete=$resp_r['cant_eg'];
}
if(isset($vomsiete)){
$pdf->SetY(219);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($vomsiete),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


//VOMITO VOMITO VOMITO VOMITO EG

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomnoc=$resp_r['cant_eg'];
}
if(isset($vomnoc)){
$pdf->SetY(222);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($vomnoc),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomn=$resp_r['cant_eg'];
}
if(isset($vomn)){
$pdf->SetY(222);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($vomn),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomd=$resp_r9['cant_eg'];
}
if(isset($vomd)){
$pdf->SetY(222);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($vomd),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomonce=$resp_r['cant_eg'];
}
if(isset($vomonce)){
$pdf->SetY(222);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($vomonce),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomdoce=$resp_r['cant_eg'];
}
if(isset($vomdoce)){
$pdf->SetY(222);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($vomdoce),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomtrece=$resp_r['cant_eg'];
}
if(isset($vomtrece)){
$pdf->SetY(222);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($vomtrece),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomcato=$resp_r['cant_eg'];
}
if(isset($vomcato)){
$pdf->SetY(222);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($vomcato),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomquin=$resp_r['cant_eg'];
}
if(isset($vomquin)){
$pdf->SetY(222);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($vomquin),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomdiseis=$resp_r['cant_eg'];
}
if(isset($vomdiseis)){
$pdf->SetY(222);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($vomdiseis),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomdisiete=$resp_r['cant_eg'];
}
if(isset($vomdisiete)){
$pdf->SetY(222);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($vomdisiete),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomdioch=$resp_r['cant_eg'];
}
if(isset($vomdioch)){
$pdf->SetY(222);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($vomdioch),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomdin=$resp_r['cant_eg'];
}
if(isset($vomdin)){
$pdf->SetY(222);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($vomdin),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomveinte=$resp_r['cant_eg'];
}
if(isset($vomveinte)){
$pdf->SetY(222);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($vomveinte),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomveuno=$resp_r['cant_eg'];
}
if(isset($vomveuno)){
$pdf->SetY(222);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($vomveuno),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomvdos=$resp_r['cant_eg'];
}
if(isset($vomvdos)){
$pdf->SetY(222);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($vomvdos),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomvtres=$resp_r['cant_eg'];
}
if(isset($vomvtres)){
$pdf->SetY(222);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($vomvtres),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomvcuatro=$resp_r['cant_eg'];
}
if(isset($vomvcuatro)){
$pdf->SetY(222);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($vomvcuatro),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomuno=$resp_r['cant_eg'];
}
if(isset($vomuno)){
$pdf->SetY(222);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($vomuno),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomdos=$resp_r['cant_eg'];
}
if(isset($vomdos)){
$pdf->SetY(222);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($vomdos),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomtres=$resp_r['cant_eg'];
}
if(isset($vomtres)){
$pdf->SetY(222);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($vomtres),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomcua=$resp_r['cant_eg'];
}
if(isset($vomcua)){
$pdf->SetY(222);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($vomcua),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomcin=$resp_r['cant_eg'];
}
if(isset($vomcin)){
$pdf->SetY(222);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($vomcin),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomse=$resp_r['cant_eg'];
}
if(isset($vomse)){
$pdf->SetY(222);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($vomse),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomsiete=$resp_r['cant_eg'];
}
if(isset($vomsiete)){
$pdf->SetY(222);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($vomsiete),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


//SANGRADO SANGRADO SANGRADO SAGRADO SANGRADO SANGRADO SANGRADO SANGRADO SANGRADO SANGRADO SANGRADO SANGRADO

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sannoct=$resp_r['cant_eg'];
}
if(isset($sannoct)){
$pdf->SetY(225);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($sannoct),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sann=$resp_r['cant_eg'];
}
if(isset($sann)){
$pdf->SetY(225);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($sann),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sand=$resp_r9['cant_eg'];
}
if(isset($sand)){
$pdf->SetY(225);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($sand),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sano=$resp_r['cant_eg'];
}
if(isset($sano)){
$pdf->SetY(225);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($sano),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sando=$resp_r['cant_eg'];
}
if(isset($sando)){
$pdf->SetY(225);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($sando),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $santre=$resp_r['cant_eg'];
}
if(isset($santre)){
$pdf->SetY(225);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($santre),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sanc=$resp_r['cant_eg'];
}
if(isset($sanc)){
$pdf->SetY(225);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($sanc),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sanq=$resp_r['cant_eg'];
}
if(isset($sanq)){
$pdf->SetY(225);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($sanq),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sands=$resp_r['cant_eg'];
}
if(isset($sands)){
$pdf->SetY(225);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($sands),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sandss=$resp_r['cant_eg'];
}
if(isset($sandss)){
$pdf->SetY(225);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($sandss),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sandocho=$resp_r['cant_eg'];
}
if(isset($sandocho)){
$pdf->SetY(225);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($sandocho),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sandn=$resp_r['cant_eg'];
}
if(isset($sandn)){
$pdf->SetY(225);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($sandn),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sanv=$resp_r['cant_eg'];
}
if(isset($sanv)){
$pdf->SetY(225);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($sanv),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sanvun=$resp_r['cant_eg'];
}
if(isset($sanvun)){
$pdf->SetY(225);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($sanvun),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sanvdos=$resp_r['cant_eg'];
}
if(isset($sanvdos)){
$pdf->SetY(225);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($sanvdos),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sanvtr=$resp_r['cant_eg'];
}
if(isset($sanvtr)){
$pdf->SetY(225);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($sanvtr),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sanvcu=$resp_r['cant_eg'];
}
if(isset($sanvcu)){
$pdf->SetY(225);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($sanvcu),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sanun=$resp_r['cant_eg'];
}
if(isset($sanun)){
$pdf->SetY(225);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($sanun),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sandos=$resp_r['cant_eg'];
}
if(isset($sandos)){
$pdf->SetY(225);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($sandos),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $santres=$resp_r['cant_eg'];
}
if(isset($santres)){
$pdf->SetY(225);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($santres),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sancuatro=$resp_r['cant_eg'];
}
if(isset($sancuatro)){
$pdf->SetY(225);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($sancuatro),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sancin=$resp_r['cant_eg'];
}
if(isset($sancin)){
$pdf->SetY(225);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($sancin),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sanseis=$resp_r['cant_eg'];
}
if(isset($sanseis)){
$pdf->SetY(225);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($sanseis),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sansiete=$resp_r['cant_eg'];
}
if(isset($sansiete)){
$pdf->SetY(225);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($sansiete),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


//SONDA SONDA SONDA SONDA SONDA SONDA SONDA SONDA NASOGASTRICA SONDA NASO EG

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sondanoct=$resp_r['cant_eg'];
}
if(isset($sondanoct)){
$pdf->SetY(231);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($sondanoct),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sondan=$resp_r['cant_eg'];
}
if(isset($sondan)){
$pdf->SetY(231);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($sondan),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sd=$resp_r9['cant_eg'];
}
if(isset($sd)){
$pdf->SetY(231);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($sd),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $so=$resp_r['cant_eg'];
}
if(isset($so)){
$pdf->SetY(231);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($so),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sdo=$resp_r['cant_eg'];
}
if(isset($sdo)){
$pdf->SetY(231);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($sdo),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $strece=$resp_r['cant_eg'];
}
if(isset($strece)){
$pdf->SetY(231);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($strece),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $scat=$resp_r['cant_eg'];
}
if(isset($scat)){
$pdf->SetY(231);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($scat),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $squin=$resp_r['cant_eg'];
}
if(isset($squin)){
$pdf->SetY(231);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($squin),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sdseis=$resp_r['cant_eg'];
}
if(isset($sdseis)){
$pdf->SetY(231);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($sdseis),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sdsiete=$resp_r['cant_eg'];
}
if(isset($sdsiete)){
$pdf->SetY(231);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($sdsiete),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sdocho=$resp_r['cant_eg'];
}
if(isset($sdocho)){
$pdf->SetY(231);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($sdocho),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sdn=$resp_r['cant_eg'];
}
if(isset($sdn)){
$pdf->SetY(231);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($sdn),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sv=$resp_r['cant_eg'];
}
if(isset($sv)){
$pdf->SetY(231);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($sv),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $svu=$resp_r['cant_eg'];
}
if(isset($svu)){
$pdf->SetY(231);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($svu),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $svdos=$resp_r['cant_eg'];
}
if(isset($svdos)){
$pdf->SetY(231);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($svdos),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $svtres=$resp_r['cant_eg'];
}
if(isset($svtres)){
$pdf->SetY(231);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($svtres),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $svcuatro=$resp_r['cant_eg'];
}
if(isset($svcuatro)){
$pdf->SetY(231);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($svcuatro),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $suno=$resp_r['cant_eg'];
}
if(isset($suno)){
$pdf->SetY(231);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($suno),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sdos=$resp_r['cant_eg'];
}
if(isset($sdos)){
$pdf->SetY(231);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($sdos),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $stres=$resp_r['cant_eg'];
}
if(isset($stres)){
$pdf->SetY(231);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($stres),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $scuatro=$resp_r['cant_eg'];
}
if(isset($scuatro)){
$pdf->SetY(231);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($scuatro),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $scin=$resp_r['cant_eg'];
}
if(isset($scin)){
$pdf->SetY(231);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($scin),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sseis=$resp_r['cant_eg'];
}
if(isset($sseis)){
$pdf->SetY(231);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($sseis),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ssiete=$resp_r['cant_eg'];
}
if(isset($ssiete)){
$pdf->SetY(231);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($ssiete),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


//HERIDA QUIR HERIDA QUIR HERIDA QUIR HERIDA QUIR HERIDA QUIR HERIDA QUIR HERIDA QUIR

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hnoct=$resp_r['cant_eg'];
}
if(isset($hnoct)){
$pdf->SetY(237);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($hnoct),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hn=$resp_r['cant_eg'];
}
if(isset($hn)){
$pdf->SetY(237);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($hn),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hdies=$resp_r9['cant_eg'];
}
if(isset($hdies)){
$pdf->SetY(237);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($hdies),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $honce=$resp_r['cant_eg'];
}
if(isset($honce)){
$pdf->SetY(237);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($honce),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hdoce=$resp_r['cant_eg'];
}
if(isset($hdoce)){
$pdf->SetY(237);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($hdoce),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $htrece=$resp_r['cant_eg'];
}
if(isset($htrece)){
$pdf->SetY(237);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($htrece),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hcato=$resp_r['cant_eg'];
}
if(isset($hcato)){
$pdf->SetY(237);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($hcato),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hquin=$resp_r['cant_eg'];
}
if(isset($hquin)){
$pdf->SetY(237);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($hquin),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hdseis=$resp_r['cant_eg'];
}
if(isset($hdseis)){
$pdf->SetY(237);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($hdseis),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hdsiete=$resp_r['cant_eg'];
}
if(isset($hdsiete)){
$pdf->SetY(237);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($hdsiete),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hdocho=$resp_r['cant_eg'];
}
if(isset($hdocho)){
$pdf->SetY(237);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($hdocho),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hdn=$resp_r['cant_eg'];
}
if(isset($hdn)){
$pdf->SetY(237);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($hdn),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dve=$resp_r['cant_eg'];
}
if(isset($dve)){
$pdf->SetY(237);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($dve),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hvuno=$resp_r['cant_eg'];
}
if(isset($hvuno)){
$pdf->SetY(237);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($hvuno),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hvdos=$resp_r['cant_eg'];
}
if(isset($hvdos)){
$pdf->SetY(237);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($hvdos),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hvtres=$resp_r['cant_eg'];
}
if(isset($hvtres)){
$pdf->SetY(237);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($hvtres),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hvcuatro=$resp_r['cant_eg'];
}
if(isset($hvcuatro)){
$pdf->SetY(237);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($hvcuatro),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $huno=$resp_r['cant_eg'];
}
if(isset($huno)){
$pdf->SetY(237);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($huno),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hdos=$resp_r['cant_eg'];
}
if(isset($hdos)){
$pdf->SetY(237);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($hdos),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $htres=$resp_r['cant_eg'];
}
if(isset($htres)){
$pdf->SetY(237);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($htres),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hcuatro=$resp_r['cant_eg'];
}
if(isset($hcuatro)){
$pdf->SetY(237);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($hcuatro),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hcinco=$resp_r['cant_eg'];
}
if(isset($hcinco)){
$pdf->SetY(237);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($hcinco),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hseis=$resp_r['cant_eg'];
}
if(isset($hseis)){
$pdf->SetY(237);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($hseis),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hsiete=$resp_r['cant_eg'];
}
if(isset($hsiete)){
$pdf->SetY(237);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($hsiete),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


//EVACUACIONES EVACUACIONES EVACUACIONES EVACUACIONES EVACUACIONES EVACUACUONES EG

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evnoct=$resp_r['cant_eg'];
}
if(isset($evnoct)){
$pdf->SetY(243);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($evnoct),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evn=$resp_r['cant_eg'];
}
if(isset($evn)){
$pdf->SetY(243);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($evn),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evdies=$resp_r9['cant_eg'];
}
if(isset($evdies)){
$pdf->SetY(243);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($evdies),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evonce=$resp_r['cant_eg'];
}
if(isset($evonce)){
$pdf->SetY(243);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($evonce),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evdoce=$resp_r['cant_eg'];
}
if(isset($evdoce)){
$pdf->SetY(243);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($evdoce),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evtrece=$resp_r['cant_eg'];
}
if(isset($evtrece)){
$pdf->SetY(243);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($evtrece),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evcato=$resp_r['cant_eg'];
}
if(isset($evcato)){
$pdf->SetY(243);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($evcato),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evquin=$resp_r['cant_eg'];
}
if(isset($evquin)){
$pdf->SetY(243);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($evquin),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evdse=$resp_r['cant_eg'];
}
if(isset($evdse)){
$pdf->SetY(243);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($evdse),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evdsiete=$resp_r['cant_eg'];
}
if(isset($evdsiete)){
$pdf->SetY(243);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($evdsiete),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evdocho=$resp_r['cant_eg'];
}
if(isset($evdocho)){
$pdf->SetY(243);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($evdocho),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evdn=$resp_r['cant_eg'];
}
if(isset($evdn)){
$pdf->SetY(243);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($evdn),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evvei=$resp_r['cant_eg'];
}
if(isset($evvei)){
$pdf->SetY(243);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($evvei),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evvuno=$resp_r['cant_eg'];
}
if(isset($evvuno)){
$pdf->SetY(243);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($evvuno),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evvdos=$resp_r['cant_eg'];
}
if(isset($evvdos)){
$pdf->SetY(243);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($evvdos),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evvtres=$resp_r['cant_eg'];
}
if(isset($evvtres)){
$pdf->SetY(243);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($evvtres),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evvc=$resp_r['cant_eg'];
}
if(isset($evvc)){
$pdf->SetY(243);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($evvc),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evuno=$resp_r['cant_eg'];
}
if(isset($evuno)){
$pdf->SetY(243);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($evuno),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evdos=$resp_r['cant_eg'];
}
if(isset($evdos)){
$pdf->SetY(243);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($evdos),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evtres=$resp_r['cant_eg'];
}
if(isset($evtres)){
$pdf->SetY(243);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($evtres),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evcuatro=$resp_r['cant_eg'];
}
if(isset($evcuatro)){
$pdf->SetY(243);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($evcuatro),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evcin=$resp_r['cant_eg'];
}
if(isset($evcin)){
$pdf->SetY(243);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($evcin),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evseis=$resp_r['cant_eg'];
}
if(isset($evseis)){
$pdf->SetY(243);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($evseis),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evsiete=$resp_r['cant_eg'];
}
if(isset($evsiete)){
$pdf->SetY(243);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($evsiete),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


//DRENAJE DRENAJE DRENAJE DRENAJE DRENAJE DRENAJE DRENAJE DRENAJE DRENAJE DRENAJE DRENAJE DRENAJE DRENAJE



$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dnoctt=$resp_r['cant_eg'];
}
if(isset($dnoctt)){
$pdf->SetY(249);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($dnoctt),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dn=$resp_r['cant_eg'];
}
if(isset($dn)){
$pdf->SetY(249);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($dn),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ddi=$resp_r9['cant_eg'];
}
if(isset($ddi)){
$pdf->SetY(249);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($ddi),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $donce=$resp_r['cant_eg'];
}
if(isset($donce)){
$pdf->SetY(249);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($donce),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ddoce=$resp_r['cant_eg'];
}
if(isset($ddoce)){
$pdf->SetY(249);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($ddoce),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dtrece=$resp_r['cant_eg'];
}
if(isset($dtrece)){
$pdf->SetY(249);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($dtrece),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dcat=$resp_r['cant_eg'];
}
if(isset($dcat)){
$pdf->SetY(249);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($dcat),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dquince=$resp_r['cant_eg'];
}
if(isset($dquince)){
$pdf->SetY(249);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($dquince),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ddseis=$resp_r['cant_eg'];
}
if(isset($ddseis)){
$pdf->SetY(249);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($ddseis),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ddsiete=$resp_r['cant_eg'];
}
if(isset($ddsiete)){
$pdf->SetY(249);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($ddsiete),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ddocho=$resp_r['cant_eg'];
}
if(isset($ddocho)){
$pdf->SetY(249);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($ddocho),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ddnue=$resp_r['cant_eg'];
}
if(isset($ddnue)){
$pdf->SetY(249);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($ddnue),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dvein=$resp_r['cant_eg'];
}
if(isset($dvein)){
$pdf->SetY(249);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($dvein),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dvuno=$resp_r['cant_eg'];
}
if(isset($dvuno)){
$pdf->SetY(249);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($dvuno),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dvdos=$resp_r['cant_eg'];
}
if(isset($dvdos)){
$pdf->SetY(249);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($dvdos),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dvtres=$resp_r['cant_eg'];
}
if(isset($dvtres)){
$pdf->SetY(249);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($dvtres),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dvcuatro=$resp_r['cant_eg'];
}
if(isset($dvcuatro)){
$pdf->SetY(249);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($dvcuatro),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $duno=$resp_r['cant_eg'];
}
if(isset($duno)){
$pdf->SetY(249);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($duno),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ddos=$resp_r['cant_eg'];
}
if(isset($ddos)){
$pdf->SetY(249);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($ddos),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dtres=$resp_r['cant_eg'];
}
if(isset($dtres)){
$pdf->SetY(249);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($dtres),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dcuatro=$resp_r['cant_eg'];
}
if(isset($dcuatro)){
$pdf->SetY(249);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($dcuatro),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dcinco=$resp_r['cant_eg'];
}
if(isset($dcinco)){
$pdf->SetY(249);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($dcinco),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dseis=$resp_r['cant_eg'];
}
if(isset($dseis)){
$pdf->SetY(249);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($dseis),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dsiete=$resp_r['cant_eg'];
}
if(isset($dsiete)){
$pdf->SetY(249);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($dsiete),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


//BOIVACK BIO VACK VIOBACK BIOVACK BIOVACK BIOVACK BIOVACK BIOBACK

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bionoct=$resp_r['cant_eg'];
}
if(isset($bionoct)){
$pdf->SetY(255);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($bionoct),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bion=$resp_r['cant_eg'];
}
if(isset($bion)){
$pdf->SetY(255);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($bion),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biod=$resp_r9['cant_eg'];
}
if(isset($biod)){
$pdf->SetY(255);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($biod),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioo=$resp_r['cant_eg'];
}
if(isset($bioo)){
$pdf->SetY(255);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($bioo),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biodoce=$resp_r['cant_eg'];
}
if(isset($biodoce)){
$pdf->SetY(255);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($biodoce),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biotrece=$resp_r['cant_eg'];
}
if(isset($biotrece)){
$pdf->SetY(255);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($biotrece),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biocat=$resp_r['cant_eg'];
}
if(isset($biocat)){
$pdf->SetY(255);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($biocat),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioquin=$resp_r['cant_eg'];
}
if(isset($bioquin)){
$pdf->SetY(255);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($bioquin),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biodseis=$resp_r['cant_eg'];
}
if(isset($biodseis)){
$pdf->SetY(255);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($biodseis),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biodsiete=$resp_r['cant_eg'];
}
if(isset($biodsiete)){
$pdf->SetY(255);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($biodsiete),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioocho=$resp_r['cant_eg'];
}
if(isset($bioocho)){
$pdf->SetY(255);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($bioocho),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biodnue=$resp_r['cant_eg'];
}
if(isset($biodnue)){
$pdf->SetY(255);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($biodnue),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioveinte=$resp_r['cant_eg'];
}
if(isset($bioveinte)){
$pdf->SetY(255);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($bioveinte),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biovuno=$resp_r['cant_eg'];
}
if(isset($biovuno)){
$pdf->SetY(255);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($biovuno),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biovdos=$resp_r['cant_eg'];
}
if(isset($biovdos)){
$pdf->SetY(255);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($biovdos),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biovtres=$resp_r['cant_eg'];
}
if(isset($biovtres)){
$pdf->SetY(255);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($biovtres),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biovcuatro=$resp_r['cant_eg'];
}
if(isset($biovcuatro)){
$pdf->SetY(255);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($biovcuatro),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biouno=$resp_r['cant_eg'];
}
if(isset($biouno)){
$pdf->SetY(255);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($biouno),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biodos=$resp_r['cant_eg'];
}
if(isset($biodos)){
$pdf->SetY(255);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($biodos),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biotres=$resp_r['cant_eg'];
}
if(isset($biotres)){
$pdf->SetY(255);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($biotres),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biocuatro=$resp_r['cant_eg'];
}
if(isset($biocuatro)){
$pdf->SetY(255);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($biocuatro),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biocinco=$resp_r['cant_eg'];
}
if(isset($biocinco)){
$pdf->SetY(255);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($biocinco),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioseis=$resp_r['cant_eg'];
}
if(isset($bioseis)){
$pdf->SetY(255);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($bioseis),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biosiete=$resp_r['cant_eg'];
}
if(isset($biosiete)){
$pdf->SetY(255);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($biosiete),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


//DRENOVACK DRENOVACK DRENOVACK DRENOVACK DRENOVACK DRENOVACK DRENOVAVK EG

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drnoct=$resp_r['cant_eg'];
}
if(isset($drnoct)){
$pdf->SetY(261);
$pdf->SetX(40);
$pdf->Cell(7,4, utf8_decode($drnoct),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(40);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drn=$resp_r['cant_eg'];
}
if(isset($drn)){
$pdf->SetY(261);
$pdf->SetX(47);
$pdf->Cell(7,4, utf8_decode($drn),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(47);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drd=$resp_r9['cant_eg'];
}
if(isset($drd)){
$pdf->SetY(261);
$pdf->SetX(54);
$pdf->Cell(7,4, utf8_decode($drd),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(54);
 $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dron=$resp_r['cant_eg'];
}
if(isset($dron)){
$pdf->SetY(261);
$pdf->SetX(61);
$pdf->Cell(7,4, utf8_decode($dron),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(61);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drdoce=$resp_r['cant_eg'];
}
if(isset($drdoce)){
$pdf->SetY(261);
$pdf->SetX(68);
$pdf->Cell(7,4, utf8_decode($drdoce),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(68);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drtrece=$resp_r['cant_eg'];
}
if(isset($drtrece)){
$pdf->SetY(261);
$pdf->SetX(75);
$pdf->Cell(7,4, utf8_decode($drtrece),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(75);
      $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drcat=$resp_r['cant_eg'];
}
if(isset($drcat)){
$pdf->SetY(261);
$pdf->SetX(82);
$pdf->Cell(7,4, utf8_decode($drcat),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(82);
    $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drquin=$resp_r['cant_eg'];
}
if(isset($drquin)){
$pdf->SetY(261);
$pdf->SetX(89);
$pdf->Cell(7,4, utf8_decode($drquin),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(89);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drdseis=$resp_r['cant_eg'];
}
if(isset($drdseis)){
$pdf->SetY(261);
$pdf->SetX(96);
$pdf->Cell(7,4, utf8_decode($drdseis),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(96);
    $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drdsiete=$resp_r['cant_eg'];
}
if(isset($drdsiete)){
$pdf->SetY(261);
$pdf->SetX(103);
$pdf->Cell(7,4, utf8_decode($drdsiete),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(103);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drdocho=$resp_r['cant_eg'];
}
if(isset($drdocho)){
$pdf->SetY(261);
$pdf->SetX(110);
$pdf->Cell(7,4, utf8_decode($drdocho),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(110);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drdnueve=$resp_r['cant_eg'];
}
if(isset($drdnueve)){
$pdf->SetY(261);
$pdf->SetX(117);
$pdf->Cell(7,4, utf8_decode($drdnueve),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(117);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drveinte=$resp_r['cant_eg'];
}
if(isset($drveinte)){
$pdf->SetY(261);
$pdf->SetX(124);
$pdf->Cell(7,4, utf8_decode($drveinte),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(124);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drveintuno=$resp_r['cant_eg'];
}
if(isset($drveintuno)){
$pdf->SetY(261);
$pdf->SetX(131);
$pdf->Cell(7,4, utf8_decode($drveintuno),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(131);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drvdos=$resp_r['cant_eg'];
}
if(isset($drvdos)){
$pdf->SetY(261);
$pdf->SetX(138);
$pdf->Cell(7,4, utf8_decode($drvdos),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(138);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drvtres=$resp_r['cant_eg'];
}
if(isset($drvtres)){
$pdf->SetY(261);
$pdf->SetX(145);
$pdf->Cell(7,4, utf8_decode($drvtres),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(145);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) { 
  $pdf->SetFont('Arial', '', 6);
  $drvcuatro=$resp_r['cant_eg'];
}
if(isset($drvcuatro)){
$pdf->SetY(261);
$pdf->SetX(152);
$pdf->Cell(7,4, utf8_decode($drvcuatro),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(152);
   $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drun=$resp_r['cant_eg'];
}
if(isset($drun)){
$pdf->SetY(261);
$pdf->SetX(159);
$pdf->Cell(7,4, utf8_decode($drun),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(159);
   $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drdos=$resp_r['cant_eg'];
}
if(isset($drdos)){
$pdf->SetY(261);
$pdf->SetX(166);
$pdf->Cell(7,4, utf8_decode($drdos),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(166);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drtres=$resp_r['cant_eg'];
}
if(isset($drtres)){
$pdf->SetY(261);
$pdf->SetX(173);
$pdf->Cell(7,4, utf8_decode($drtres),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(173);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drcua=$resp_r['cant_eg'];
}
if(isset($drcua)){
$pdf->SetY(261);
$pdf->SetX(180);
$pdf->Cell(7,4, utf8_decode($drcua),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(180);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drcin=$resp_r['cant_eg'];
}
if(isset($drcin)){
$pdf->SetY(261);
$pdf->SetX(187);
$pdf->Cell(7,4, utf8_decode($drcin),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(187);
   $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drseis=$resp_r['cant_eg'];
}
if(isset($drseis)){
$pdf->SetY(261);
$pdf->SetX(194);
$pdf->Cell(7,4, utf8_decode($drseis),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(194);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drsiete=$resp_r['cant_eg'];
}
if(isset($drsiete)){
$pdf->SetY(261);
$pdf->SetX(201);
$pdf->Cell(7,4, utf8_decode($drsiete),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(201);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}


//RIOBACK RIO BACK RIOBACXK RIO BACK RIOBACK RIO BACK
$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rionoct=$resp_r['cant_eg'];
}
if(isset($rionoct)){
$pdf->SetY(265);
$pdf->SetX(40);
$pdf->Cell(7,4, utf8_decode($rionoct),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(40);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rion=$resp_r['cant_eg'];
}
if(isset($rion)){
$pdf->SetY(265);
$pdf->SetX(47);
$pdf->Cell(7,4, utf8_decode($rion),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(47);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riodies=$resp_r9['cant_eg'];
}
if(isset($riodies)){
$pdf->SetY(265);
$pdf->SetX(54);
$pdf->Cell(7,4, utf8_decode($riodies),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(54);
 $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rioonce=$resp_r['cant_eg'];
}
if(isset($rioonce)){
$pdf->SetY(265);
$pdf->SetX(61);
$pdf->Cell(7,4, utf8_decode($rioonce),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(61);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riodoce=$resp_r['cant_eg'];
}
if(isset($riodoce)){
$pdf->SetY(265);
$pdf->SetX(68);
$pdf->Cell(7,4, utf8_decode($riodoce),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(68);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riotrece=$resp_r['cant_eg'];
}
if(isset($riotrece)){
$pdf->SetY(265);
$pdf->SetX(75);
$pdf->Cell(7,4, utf8_decode($riotrece),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(75);
      $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riocat=$resp_r['cant_eg'];
}
if(isset($riocat)){
$pdf->SetY(265);
$pdf->SetX(82);
$pdf->Cell(7,4, utf8_decode($riocat),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(82);
    $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rioquin=$resp_r['cant_eg'];
}
if(isset($rioquin)){
$pdf->SetY(265);
$pdf->SetX(89);
$pdf->Cell(7,4, utf8_decode($rioquin),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(89);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riodsesis=$resp_r['cant_eg'];
}
if(isset($riodsesis)){
$pdf->SetY(265);
$pdf->SetX(96);
$pdf->Cell(7,4, utf8_decode($riodsesis),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(96);
    $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riodsiete=$resp_r['cant_eg'];
}
if(isset($riodsiete)){
$pdf->SetY(265);
$pdf->SetX(103);
$pdf->Cell(7,4, utf8_decode($riodsiete),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(103);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riodocho=$resp_r['cant_eg'];
}
if(isset($riodocho)){
$pdf->SetY(265);
$pdf->SetX(110);
$pdf->Cell(7,4, utf8_decode($riodocho),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(110);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riodnueve=$resp_r['cant_eg'];
}
if(isset($riodnueve)){
$pdf->SetY(265);
$pdf->SetX(117);
$pdf->Cell(7,4, utf8_decode($riodnueve),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(117);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rioveinte=$resp_r['cant_eg'];
}
if(isset($rioveinte)){
$pdf->SetY(265);
$pdf->SetX(124);
$pdf->Cell(7,4, utf8_decode($rioveinte),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(124);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riovuno=$resp_r['cant_eg'];
}
if(isset($riovuno)){
$pdf->SetY(265);
$pdf->SetX(131);
$pdf->Cell(7,4, utf8_decode($riovuno),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(131);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riovdos=$resp_r['cant_eg'];
}
if(isset($riovdos)){
$pdf->SetY(265);
$pdf->SetX(138);
$pdf->Cell(7,4, utf8_decode($riovdos),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(138);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riovtres=$resp_r['cant_eg'];
}
if(isset($riovtres)){
$pdf->SetY(265);
$pdf->SetX(145);
$pdf->Cell(7,4, utf8_decode($riovtres),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(145);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riovcuatro=$resp_r['cant_eg'];
}
if(isset($riovcuatro)){
$pdf->SetY(265);
$pdf->SetX(152);
$pdf->Cell(7,4, utf8_decode($riovcuatro),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(152);
   $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riouno=$resp_r['cant_eg'];
}
if(isset($riouno)){
$pdf->SetY(265);
$pdf->SetX(159);
$pdf->Cell(7,4, utf8_decode($riouno),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(159);
   $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riodoos=$resp_r['cant_eg'];
}
if(isset($riodoos)){
$pdf->SetY(265);
$pdf->SetX(166);
$pdf->Cell(7,4, utf8_decode($riodoos),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(166);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riotres=$resp_r['cant_eg'];
}
if(isset($riotres)){
$pdf->SetY(265);
$pdf->SetX(173);
$pdf->Cell(7,4, utf8_decode($riotres),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(173);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riocuat=$resp_r['cant_eg'];
}
if(isset($riocuat)){
$pdf->SetY(265);
$pdf->SetX(180);
$pdf->Cell(7,4, utf8_decode($riocuat),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(180);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riocin=$resp_r['cant_eg'];
}
if(isset($riocin)){
$pdf->SetY(265);
$pdf->SetX(187);
$pdf->Cell(7,4, utf8_decode($riocin),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(187);
   $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rioseis=$resp_r['cant_eg'];
}
if(isset($rioseis)){
$pdf->SetY(265);
$pdf->SetX(194);
$pdf->Cell(7,4, utf8_decode($rioseis),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(194);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riosiete=$resp_r['cant_eg'];
}
if(isset($riosiete)){
$pdf->SetY(265);
$pdf->SetX(201);
$pdf->Cell(7,4, utf8_decode($riosiete),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(201);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}



//FIN CONSULTAS FIN CONSULTAS POR HORAS FIN CONSULTAS POR HORAS


//EGRESO PARCIAL TOTAL EGRESO PARCIAL TOTAL EGRESO PARCIAL TOTAL EGRESO PARCIAL TOTAL EGRESO PARCIAL TOTAL MATUTINO

$resp = $conexion->query("select SUM(cant_eg) as sumo from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumo=$resp_r['sumo'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumvom from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumvom=$resp_r['sumvom'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumsan from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumsan=$resp_r['sumsan'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumsonda from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumsonda=$resp_r['sumsonda'];
}


$resp = $conexion->query("select SUM(cant_eg) as sumher from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumher=$resp_r['sumher'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumeva from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumeva=$resp_r['sumeva'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumdren from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumdren=$resp_r['sumdren'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumbio from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumbio=$resp_r['sumbio'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumdreno from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumdreno=$resp_r['sumdreno'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumrio from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumrio=$resp_r['sumrio'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumsara from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumsara=$resp_r['sumsara'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumestomas from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumestomas=$resp_r['sumestomas'];
}

$sumatotalegr=$sumo+$sumvom+$sumsan+$sumsonda+$sumher+$sumeva+$sumdren+$sumbio+$sumdreno+$sumrio+$sumsara+$sumestomas;

/*if(isset($sum)){
*/
$pdf->SetY(269);
$pdf->SetX(40);
$pdf->Cell(42,3, utf8_decode($sumatotalegr . ' ML'),1,0,'C');
/*
}else{
  $pdf->SetY(206);
$pdf->SetX(40);
  $pdf->Cell(42,6, utf8_decode(''),1,0,'C');
}*/

//EGRESO PARCIAL TOTAL EGRESO PARCIAL TOTAL EGRESO PARCIAL TOTAL EGRESO PARCIAL TOTAL EGRESO PARCIAL TOTAL VESPERTINO

$resp = $conexion->query("select SUM(cant_eg) as sumov from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='14' or hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumov=$resp_r['sumov'];
}

$resp = $conexion->query("select SUM(cant_eg) as summedveg from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='14' or hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $summedveg=$resp_r['summedveg'];
}

$resp = $conexion->query("select SUM(cant_eg) as viaveg from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='14' or hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $viaveg=$resp_r['viaveg'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumsondaeg from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='14' or hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumsondaeg=$resp_r['sumsondaeg'];
}


$resp = $conexion->query("select SUM(cant_eg) as sumhereg from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='14' or hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumhereg=$resp_r['sumhereg'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumevaeg from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='14' or hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumevaeg=$resp_r['sumevaeg'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumdreneg from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='14' or hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumdreneg=$resp_r['sumdreneg'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumbioeg from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='14' or hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumbioeg=$resp_r['sumbioeg'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumdrenoeg from eg_enf_quir where des_eg='DRENOVACK' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='14' or hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumdrenoeg=$resp_r['sumdrenoeg'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumrioeg from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='14' or hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumrioeg=$resp_r['sumrioeg'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumtoga from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='14' or hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumtoga=$resp_r['sumtoga'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumtomas from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='14' or hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumtomas=$resp_r['sumtomas'];
}

$sumatotalveg=$sumov+$summedveg+$viaveg+$sumsondaeg+$sumhereg+$sumevaeg+$sumdreneg+$sumbioeg+$sumdrenoeg+$sumrioeg+$sumtoga+$sumtomas;


/*if(isset($g9)){
*/
$pdf->SetY(269);
$pdf->SetX(82);
$pdf->Cell(49,3, utf8_decode($sumatotalveg . ' ML'),1,0,'C');
/*
}else{
  $pdf->SetY(206);
$pdf->SetX(82);
 $pdf->Cell(49,6, utf8_decode(''),1,0,'C');
}*/

//INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL NOCTURNO NOCTURNO

$resp = $conexion->query("select SUM(cant_eg) as sumbasen from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumbasen=$resp_r['sumbasen'];
}

$resp = $conexion->query("select SUM(cant_eg) as summedn from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $summedn=$resp_r['summedn'];
}

$resp = $conexion->query("select SUM(cant_eg) as vian from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vian=$resp_r['vian'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumcargasn from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumcargasn=$resp_r['sumcargasn'];
}


$resp = $conexion->query("select SUM(cant_eg) as sumcantidadn from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumcantidadn=$resp_r['sumcantidadn'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumn from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumn=$resp_r['sumn'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumdrenajesegn from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumdrenajesegn=$resp_r['sumdrenajesegn'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumbioegn from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumbioegn=$resp_r['sumbioegn'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumdrenoegn from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumdrenoegn=$resp_r['sumdrenoegn'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumrioegn from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumrioegn=$resp_r['sumrioegn'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumns from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumns=$resp_r['sumns'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumnoesto from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumnoesto=$resp_r['sumnoesto'];
}

$sumaegresosn=$sumbasen+$summedn+$vian+$sumcargasn+$sumcantidadn+$sumn+$sumdrenajesegn+$sumbioegn+$sumdrenoegn+$sumrioegn+$sumns+$sumnoesto;



/*if(isset($g10)){
  */
$pdf->SetY(269);
$pdf->SetX(131);
$pdf->Cell(77,3, utf8_decode($sumaegresosn . ' ML'),1,0,'C');
/*
}else{

  $pdf->SetY(206);
$pdf->SetX(131);
  $pdf->Cell(77,6, utf8_decode(''),1,0,'C');
}
*/

//BALANCE TOTAL INGRESOS - EGRESOS DE LOS 3 TURNOS SUMATORIA FINAL
$sumaingresos=$sumatotal+$sumatotalv+$sumatotaln;
$sumaegresos=$sumatotalegr+$sumatotalveg+$sumaegresosn;
$TOTALRESTA=$sumaingresos-$sumaegresos;

$TOTRESTAMAT=$sumatotal-$sumatotalegr;
$TOTRESTAVESP=$sumatotalv-$sumatotalveg;
$TOTRESTANOCT=$sumatotaln-$sumaegresosn;

 $pdf->SetFont('Arial', 'B', 6);
$pdf->SetY(272);
$pdf->SetX(40);
$pdf->Cell(42,3, utf8_decode($TOTRESTAMAT . ' ML'),1,0,'C');

$pdf->SetY(272);
$pdf->SetX(82);
$pdf->Cell(49,3, utf8_decode($TOTRESTAVESP . ' ML'),1,0,'C');

$pdf->SetY(272);
$pdf->SetX(131);
$pdf->Cell(77,3, utf8_decode($TOTRESTANOCT . ' ML'),1,0,'C');

//CONSULTA A TABLA PRINCIPAL


$val = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
/*$quemadura_mat=$val_res['quemadura_mat'];
$heridap_mat=$val_res['heridap_mat'];
$enfisema_mat=$val_res['enfisema_mat'];
$ulcpre_mat=$val_res['ulcpre_mat'];
$dermoabra_mat=$val_res['dermoabra_mat'];
$hematoma_mat=$val_res['hematoma_mat'];
$ciano_mat=$val_res['ciano_mat'];
$rash_mat=$val_res['rash_mat'];
$fracturas_mat=$val_res['fracturas_mat'];
$herquir_mat=$val_res['herquir_mat'];
$equimosis_mat=$val_res['equimosis_mat'];
$funprev_mat=$val_res['funprev_mat']; */

$fron_mat=$val_res['fron_mat'];
$malar_mat=$val_res['malar_mat'];
$mandi_mat=$val_res['mandi_mat'];
$delto_mat=$val_res['delto_mat'];
$pecto_mat=$val_res['pecto_mat'];
$esternal_mat=$val_res['esternal_mat'];
$antebrazo_mat=$val_res['antebrazo_mat'];
$muñeca_mat=$val_res['muñeca_mat'];
$manopal_mat=$val_res['manopal_mat'];
$muslo_mat=$val_res['muslo_mat'];
$rodilla_mat=$val_res['rodilla_mat'];
$pierna_mat=$val_res['pierna_mat'];
$pirnasal_mat=$val_res['pirnasal_mat'];
$maxsup_mat=$val_res['maxsup_mat'];
$menton_mat=$val_res['menton_mat'];
$acromial_mat=$val_res['acromial_mat'];
$brazo_mat=$val_res['brazo_mat'];
$plicodo_mat=$val_res['plicodo_mat'];
$abdomen_mat=$val_res['abdomen_mat'];
$regingui_mat=$val_res['regingui_mat'];
$regpub_mat=$val_res['regpub_mat'];
$pdedo_mat=$val_res['pdedo_mat'];
$sdedo_mat=$val_res['sdedo_mat'];
$tdedo_mat=$val_res['tdedo_mat'];
$cdedo_mat=$val_res['cdedo_mat'];
$qdedo_mat=$val_res['qdedo_mat'];
$tobi_mat=$val_res['tobi_mat'];
$piedor_mat=$val_res['piedor_mat'];
$parie_mat=$val_res['parie_mat'];
$occi_mat=$val_res['occi_mat'];
$nuca_mat=$val_res['nuca_mat'];
$brazo2_mat=$val_res['brazo2_mat'];
$codo2_mat=$val_res['codo2_mat'];
$antebrazo2_mat=$val_res['antebrazo2_mat'];
$muñeca2_mat=$val_res['muñeca2_mat'];

$manodor_mat=$val_res['manodor_mat'];
$plipop_mat=$val_res['plipop_mat'];
$pierna2_mat=$val_res['pierna2_mat'];
$pieplan_mat=$val_res['pieplan_mat'];
$cuellopos_mat=$val_res['cuellopos_mat'];
$reginter_mat=$val_res['reginter_mat'];
$regesca_mat=$val_res['regesca_mat'];
$reginfra_mat=$val_res['reginfra_mat'];
$lumbar_mat=$val_res['lumbar_mat'];
$gluteo_mat=$val_res['gluteo_mat'];
$muslo2_mat=$val_res['muslo2_mat'];
$talon2_mat=$val_res['talon2_mat'];
$antebrazo2_mat=$val_res['antebrazo2_mat'];
$muñeca2_mat=$val_res['muñeca2_mat'];
  
$ramsay_mat=$val_res['ramsay_mat'];
//$escaladolor_mat=$val_res['escaladolor_mat'];  
$apecular_mat=$val_res['apecular_mat'];  
$respmot_mat=$val_res['respmot_mat']; 
$respver_mat=$val_res['respver_mat']; 

$tamder_mat=$val_res['tamder_mat'];  
$tamizq_mat=$val_res['tamizq_mat'];  
$simder_mat=$val_res['simder_mat']; 
$simizq_mat=$val_res['simizq_mat']; 


$caidprev_mat=$val_res['caidprev_mat'];  
$medcaidas_mat=$val_res['medcaidas_mat'];  
$defsens_mat=$val_res['defsens_mat']; 
$edomental_mat=$val_res['edomental_mat']; 
$deambula_mat=$val_res['deambula_mat']; 
$totalcaidas_mat=$val_res['totalcaidas_mat'];
$clasriesg_mat=$val_res['clasriesg_mat']; 
$nomenfermera_mat=$val_res['nomenfermera_mat']; 
$riesgcaida_mat=$val_res['riesgcaida_mat'];

$edofisico_mat=$val_res['edofisico_mat'];  
$edomentalnor_mat=$val_res['edomentalnor_mat'];  
$actividad_mat=$val_res['actividad_mat']; 
$movilidad_mat=$val_res['movilidad_mat']; 
$incont_mat=$val_res['incont_mat']; 
$totnorton_mat=$val_res['totnorton_mat'];
$clasriesgnor_mat=$val_res['clasriesgnor_mat']; 
$nomenfnorton_mat=$val_res['nomenfnorton_mat']; 
$acriesg_mat=$val_res['acriesg_mat'];

//dispositivos
$ccalibre_mat=$val_res['ccalibre_mat'];  
$ctipo_mat=$val_res['ctipo_mat'];  
$cnomreal_mat=$val_res['cnomreal_mat']; 
$cdias_mat=$val_res['cdias_mat']; 
$cobserv_mat=$val_res['cobserv_mat']; 

$percali_mat=$val_res['percali_mat'];
$pertipo_mat=$val_res['pertipo_mat']; 
$pernomreal_mat=$val_res['pernomreal_mat']; 
$perdias_mat=$val_res['perdias_mat'];
$perobserv_mat=$val_res['perobserv_mat'];  

$per2cali_mat=$val_res['per2cali_mat'];  
$per2tipo_mat=$val_res['per2tipo_mat']; 
$per2nomreal_mat=$val_res['per2nomreal_mat']; 
$per2dias_mat=$val_res['per2dias_mat']; 
$per2observ_mat=$val_res['per2observ_mat'];

$tcali_mat=$val_res['tcali_mat']; 
$ttipo_mat=$val_res['ttipo_mat']; 
$tnomreal_mat=$val_res['tnomreal_mat'];
$tdias_mat=$val_res['tdias_mat'];  
$tobserv_mat=$val_res['tobserv_mat'];

$soncali_mat=$val_res['soncali_mat']; 
$sontipo_mat=$val_res['sontipo_mat']; 
$sonnomreal_mat=$val_res['sonnomreal_mat']; 
$sondias_mat=$val_res['sondias_mat'];
$sonobserv_mat=$val_res['sonobserv_mat']; 

$nascali_mat=$val_res['nascali_mat']; 
$nastipo_mat=$val_res['nastipo_mat']; 
$nasnomreal_mat=$val_res['nasnomreal_mat']; 
$nasdias_mat=$val_res['nasdias_mat'];
$nasobserv_mat=$val_res['nasobserv_mat']; 

}
$pdf->Ln(7);
$pdf->SetFont('Arial', 'B', 8);
$pdf->MultiCell(195, 6, utf8_decode('SOLUCIONES PARENTERALES'), 0, 'C');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(124,5, utf8_decode('Solución'),1,0,'C');
$pdf->Cell(20,5, utf8_decode('ML / Hrs'),1,0,'C');
$pdf->Cell(18,5, utf8_decode('Inicio'),1,0,'C');
$pdf->Cell(18,5, utf8_decode('Término'),1,0,'C');
$pdf->Cell(15,5, utf8_decode('Fecha'),1,0,'C');

$cis = $conexion->query("select * from sol_enf where sol_fecha='$fechar' and id_atencion=$id_atencion AND tipo='HOSPITALIZACION' ORDER BY sol_fecha DESC") or die($conexion->error);
while ($cis_s = $cis->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
$pdf->Ln(5);
$pdf->Cell(124,5, utf8_decode($cis_s['sol']),1,0,'C');
$pdf->Cell(20,5, utf8_decode($cis_s['vol']),1,0,'C');
$pdf->Cell(18,5, utf8_decode($cis_s['hora_i']),1,0,'C');
$pdf->Cell(18,5, utf8_decode($cis_s['hora_t']),1,0,'C');
$date=date_create($cis_s['sol_fecha']);
$pdf->Cell(15,5, date_format($date,"d/m/Y"),1,0,'C');

}
$pdf->Ln(11);
$pdf->SetFont('Arial', 'B', 8);
$pdf->MultiCell(195, 6, utf8_decode('MEDICAMENTOS'), 0, 'C');

$pdf->Cell(105,4, utf8_decode('Medicamentos'),1,0,'C');
$pdf->Cell(20,4, utf8_decode('Dósis'),1,0,'C');
$pdf->Cell(20,4, utf8_decode('Vía'),1,0,'C');
$pdf->Cell(20,4, utf8_decode('Horario'),1,0,'C');
$pdf->Cell(30,4, utf8_decode('Turno'),1,0,'C');

$pdf->Ln(4);
$medica = $conexion->query("select * from medica_enf WHERE fecha_mat='$fechar' and id_atencion=$id_atencion and tipo='hospitalizacion' ORDER BY id_med_reg DESC") or die($conexion->error);
while ($row_m = $medica->fetch_assoc()) {

if($hora_mat=='9' || $hora_mat=='10' || $hora_mat=='11'|| $hora_mat=='12'|| $hora_mat=='13' || $hora_mat=='14'){
$turno="MATUTINO";
}else if ($hora_mat=='15' || $hora_mat=='16' || $hora_mat=='17'|| $hora_mat=='18'|| $hora_mat=='19' || $hora_mat=='20' || $hora_mat=='21') {
  $turno="VESPERTINO";
}else if ($hora_mat=='22' || $hora_mat=='23' || $hora_mat=='24'|| $hora_mat=='1'|| $hora_mat=='2' || $hora_mat=='3' || $hora_mat=='4' || $hora_mat=='5' || $hora_mat=='6' || $hora_mat=='7' || $hora_mat=='8') {
    $turno="NOCTURNO";
}
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(105,4, $row_m['medicam_mat'],1,0,'C');
$pdf->Cell(20,4, $row_m['dosis_mat'],1,0,'C');
$pdf->Cell(20,4, $row_m['via_mat'],1,0,'C');
$pdf->Cell(20,4, $row_m['hora_mat'],1,0,'C');
$pdf->Cell(30,4, $row_m['turno'],1,1,'C');

}

$val = $conexion->query("select * from enf_reg_clin where fecha_mat='$fechar' AND id_atencion=$id_atencion order by fecha_mat DESC LIMIT 1") or die($conexion->error);
while ($val_r = $val->fetch_assoc()) {
  //MARCAJE
$id_clinreg=$val_r['id_clinreg'];

$mara=$val_r['mara'];
$marb=$val_r['marb'];
$marc=$val_r['marc'];
$mard=$val_r['mard'];
$mare=$val_r['mare'];
$marf=$val_r['marf'];
$marg=$val_r['marg'];
$marh=$val_r['marh'];

$frenteizquierda=$val_r['frenteizquierda'];
$frentederecha=$val_r['frentederecha'];
$narizc=$val_r['narizc'];
$mejderecha=$val_r['mejderecha'];
$mandiizqui=$val_r['mandiizqui'];
$mandiderr=$val_r['mandiderr'];
$mandicentroo=$val_r['mandicentroo'];
$cvi=$val_r['cvi'];
$homi=$val_r['homi'];
$hombrod=$val_r['hombrod'];
$pecti=$val_r['pecti'];
$pectd=$val_r['pectd'];
$peci=$val_r['peci'];
$brazci=$val_r['brazci'];
$cconder=$val_r['cconder'];
$brazi=$val_r['brazi'];
$annbraz=$val_r['annbraz'];
$derbraz=$val_r['derbraz'];
$muñei=$val_r['muñei'];
$muñecad=$val_r['muñecad'];
$palmai=$val_r['palmai'];
$palmad=$val_r['palmad'];
$ddi=$val_r['ddi'];
$ddoderu=$val_r['ddoderu'];
$ddidos=$val_r['ddidos'];
$dedodos=$val_r['dedodos'];
$dditres=$val_r['dditres'];
$dedotres=$val_r['dedotres'];
$dedocuatro=$val_r['dedocuatro'];
$ddicuatro=$val_r['ddicuatro'];
$ddicinco=$val_r['ddicinco'];
$dedocincoo=$val_r['dedocincoo'];
$iabdomen=$val_r['iabdomen'];
$inglei=$val_r['inglei'];
$musloi=$val_r['musloi'];
$muslod=$val_r['muslod'];
$rodd=$val_r['rodd'];
$rodi=$val_r['rodi'];
$tod=$val_r['tod'];
$toi=$val_r['toi'];
$pied=$val_r['pied'];
$pie=$val_r['pie'];
$plantapiea=$val_r['plantapiea'];
$plantapieader=$val_r['plantapieader'];
$tobilloatd=$val_r['tobilloatd'];
$tobilloati=$val_r['tobilloati'];
$ptiatras=$val_r['ptiatras'];
$ptdatras=$val_r['ptdatras'];
$pierespaldad=$val_r['pierespaldad'];
$pierespaldai=$val_r['pierespaldai'];
$musloatrasiz=$val_r['musloatrasiz'];
$musloatrasder=$val_r['musloatrasder'];
$dorsaliz=$val_r['dorsaliz'];
$dorsalder=$val_r['dorsalder'];
$munecaatrasiz=$val_r['munecaatrasiz'];
$munecaatrasder=$val_r['munecaatrasder'];
$antebdesp=$val_r['antebdesp'];
$antebiesp=$val_r['antebiesp'];
$casicodoi=$val_r['casicodoi'];
$casicododer=$val_r['casicododer'];
$brazaltder=$val_r['brazaltder'];
$brazalti=$val_r['brazalti'];
$glutiz=$val_r['glutiz'];
$glutder=$val_r['glutder'];
$cinturader=$val_r['cinturader'];
$cinturaiz=$val_r['cinturaiz'];
$costilliz=$val_r['costilliz'];
$costillder=$val_r['costillder'];
$espaldaarribader=$val_r['espaldaarribader'];
$espaldarribaiz=$val_r['espaldarribaiz'];
$espaldaalta=$val_r['espaldaalta'];
$cuellatrasb=$val_r['cuellatrasb'];
$cuellatrasmedio=$val_r['cuellatrasmedio'];
$cabedorsalm=$val_r['cabedorsalm'];
$cabealtaizqu=$val_r['cabealtaizqu'];
$cabezaaltader=$val_r['cabezaaltader'];

$nuevo=$val_r['nuevo'];
$nuevo1=$val_r['nuevo1'];
$nuevo2=$val_r['nuevo2'];
$nuevo3=$val_r['nuevo3'];
$nuevo4=$val_r['nuevo4'];
$nuevo5=$val_r['nuevo5'];
$nuevo6=$val_r['nuevo6'];
$nuevo7=$val_r['nuevo7'];
}

$pdf->SetFont('Arial', 'B', 8);
$pdf->Ln(8);
$pdf->Cell(195, 6, utf8_decode('VALORACIÓN DE LA PIEL'), 0, 0, 'C');



$pdf->Image('../../img/cuerpof.jpg' , 79, 103, 56);


if($nuevo!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(142);
$pdf->SetX(105);
$pdf->Cell(25, 6, utf8_decode($nuevo), 0,0, 'C');
$pdf->Image('../registro_clinico/linea.png', 111, 146, 25, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(142);
$pdf->SetX(99);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}


if($nuevo1!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(142);
$pdf->SetX(82);
$pdf->Cell(25, 6, utf8_decode($nuevo1), 0,0, 'C');
$pdf->Image('../registro_clinico/linea.png', 77, 146, 25, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(142);
$pdf->SetX(89);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($nuevo2!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(138);
$pdf->SetX(82);
$pdf->Cell(25, 6, utf8_decode($nuevo2), 0,0, 'C');
$pdf->Image('../registro_clinico/linea.png', 77, 142, 25, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(138);
$pdf->SetX(89);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($nuevo3!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(135.5);
$pdf->SetX(99);
$pdf->Cell(25, 6, utf8_decode($nuevo3), 0,0, 'C');
$pdf->Image('../registro_clinico/linea.png', 107, 139.5, 25, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(135.5);
$pdf->SetX(94.8);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($nuevo4!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(138);
$pdf->SetX(102);
$pdf->Cell(25, 6, utf8_decode($nuevo4), 0,0, 'C');
$pdf->Image('../registro_clinico/linea.png', 111, 142, 25, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(138.5);
$pdf->SetX(99);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}


if($nuevo5!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(132);
$pdf->SetX(102);
$pdf->Cell(25, 6, utf8_decode($nuevo5), 0,0, 'C');
$pdf->Image('../registro_clinico/linea.png', 111, 136, 25, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(132);
$pdf->SetX(99);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($nuevo6!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(129.9);
$pdf->SetX(97);
$pdf->Cell(25, 6, utf8_decode($nuevo6), 0,0, 'C');
$pdf->Image('../registro_clinico/linea.png', 107, 133.5, 25, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(130);
$pdf->SetX(95);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}


if($nuevo7!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(132);
$pdf->SetX(66);
$pdf->Cell(25, 6, utf8_decode($nuevo7), 0,0, 'C');
$pdf->Image('../registro_clinico/linea.png', 77, 136, 25, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(132);
$pdf->SetX(89);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}


if($frenteizquierda!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(102);
$pdf->SetX(68);
$pdf->Cell(25, 6, utf8_decode($frenteizquierda), 0,0, 'C');
$pdf->Line(78, 106.2, 105.5, 106.2);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(103.2);
$pdf->SetX(93);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($frentederecha!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(102);
$pdf->SetX(100.7);
$pdf->Cell(25, 6, utf8_decode($frentederecha), 0,0, 'C');
$pdf->Line(108.5, 106.2, 137, 106.2);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(103.2);
$pdf->SetX(96);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($narizc!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(104.7);
$pdf->SetX(98);
$pdf->Cell(25, 6, utf8_decode($narizc), 0,0, 'C');
$pdf->Line(107.4, 108.6, 137, 108.6);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(105.7);
$pdf->SetX(94.7);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($mejderecha!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(107.3);
$pdf->SetX(101);
$pdf->Cell(25, 6, utf8_decode($mejderecha), 0,0, 'C');
$pdf->Line(110, 111.3, 137, 111.3);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(108.4);
$pdf->SetX(97.5);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($marf!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(107);
$pdf->SetX(68.6);
$pdf->Cell(25, 6, utf8_decode($marf), 0,0, 'C');
$pdf->Line(78, 111.3, 104.5, 111.3);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(108.5);
$pdf->SetX(92.1);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($mare!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(109.3);
$pdf->SetX(68.6);
$pdf->Cell(25, 6, utf8_decode($mare), 0,0, 'C');
$pdf->Line(78, 113.3, 107, 113.3);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(110.4);
$pdf->SetX(94.7);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($mandiizqui!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(111.5);
$pdf->SetX(67.7);
$pdf->Cell(25, 6, utf8_decode($mandiizqui), 0,0, 'C');
$pdf->Line(78, 115.4, 104.7, 115.4);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(112.4);
$pdf->SetX(92.5);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($mandiderr!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(110.1);
$pdf->SetX(100.5);
$pdf->Cell(25, 6, utf8_decode($mandiderr), 0,0, 'C');
$pdf->Line(110, 114.3, 137, 114.3);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(111.4);
$pdf->SetX(97.5);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($mandicentroo!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(112.6);
$pdf->SetX(100.5);
$pdf->Cell(25, 6, utf8_decode($mandicentroo), 0,0, 'C');
$pdf->Line(107.3, 116.5, 137, 116.5);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(113.6);
$pdf->SetX(94.9);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cvi!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(117);
$pdf->SetX(67.7);
$pdf->Cell(25, 6, utf8_decode($cvi), 0,0, 'C');
$pdf->Line(78, 121.3, 103.1, 121.3);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(118.3);
$pdf->SetX(90.5);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($mard!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(117);
$pdf->SetX(102);
$pdf->Cell(25, 6, utf8_decode($mard), 0,0, 'C');
$pdf->Line(111.5, 121.3, 137, 121.3);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(118.3);
$pdf->SetX(98.7);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($homi!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(121);
$pdf->SetX(60);
$pdf->Cell(25, 6, utf8_decode($homi), 0,0, 'C');
$pdf->Line(70, 125, 94, 125);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(122.1);
$pdf->SetX(81.8);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($hombrod!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(121);
$pdf->SetX(109.5);
$pdf->Cell(25, 6, utf8_decode($hombrod), 0,0, 'C');
$pdf->Line(120, 125, 144, 125);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(122.1);
$pdf->SetX(107.3);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($pecti!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(124);
$pdf->SetX(60);
$pdf->Cell(25, 6, utf8_decode($pecti), 0,0, 'C');
$pdf->Line(70, 128, 102, 128);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(125.1);
$pdf->SetX(89.8);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($pectd!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(124);
$pdf->SetX(102);
$pdf->Cell(25, 6, utf8_decode($pectd), 0,0, 'C');
$pdf->Line(113, 128, 144, 128);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(125.1);
$pdf->SetX(100.2);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($peci!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(127);
$pdf->SetX(60);
$pdf->Cell(25, 6, utf8_decode($peci), 0,0, 'C');
$pdf->Line(70, 131, 105, 131);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(128);
$pdf->SetX(92.9);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($marc!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(127);
$pdf->SetX(105);
$pdf->Cell(25, 6, utf8_decode($marc), 0,0, 'C');
$pdf->Line(110, 131, 144, 131);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(128);
$pdf->SetX(97.6);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($brazci!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(129.1);
$pdf->SetX(60);
$pdf->Cell(25, 6, utf8_decode($brazci), 0,0, 'C');
$pdf->Line(70, 133, 93, 133);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(130);
$pdf->SetX(80.9);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cconder!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(129.1);
$pdf->SetX(113);
$pdf->Cell(25, 6, utf8_decode($cconder), 0,0, 'C');
$pdf->Line(122, 133, 144, 133);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(130);
$pdf->SetX(109.1);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($brazi!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(132);
$pdf->SetX(60);
$pdf->Cell(25, 6, utf8_decode($brazi), 0,0, 'C');
$pdf->Line(70, 136, 92, 136);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(133);
$pdf->SetX(80);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($annbraz!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(132);
$pdf->SetX(113);
$pdf->Cell(25, 6, utf8_decode($annbraz), 0,0, 'C');
$pdf->Line(123, 136, 144, 136);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(133);
$pdf->SetX(110);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($marg!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(140.7);
$pdf->SetX(55);
$pdf->Cell(25, 6, utf8_decode($marg), 0,0, 'C');
$pdf->Line(60, 145, 88, 145);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(142.1);
$pdf->SetX(76);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($derbraz!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(140.7);
$pdf->SetX(116);
$pdf->Cell(25, 6, utf8_decode($derbraz), 0,0, 'C');
$pdf->Line(126, 145, 153, 145);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(142.1);
$pdf->SetX(113.1);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($muñei!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(144.4);
$pdf->SetX(50);
$pdf->Cell(25, 6, utf8_decode($muñei), 0,0, 'C');
$pdf->Line(60, 148.5, 87, 148.5);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(145.6);
$pdf->SetX(74.9);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($muñecad!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(144.4);
$pdf->SetX(119);
$pdf->Cell(25, 6, utf8_decode($muñecad), 0,0, 'C');
$pdf->Line(127.7, 148.5, 153, 148.5);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(145.6);
$pdf->SetX(114.7);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($palmai!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(146.5);
$pdf->SetX(50);
$pdf->Cell(25, 6, utf8_decode($palmai), 0,0, 'C');
$pdf->Line(60, 150.8, 85.8, 150.8);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(148);
$pdf->SetX(73.5);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($palmad!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(146.8);
$pdf->SetX(118);
$pdf->Cell(25, 6, utf8_decode($palmad), 0,0, 'C');
$pdf->Line(128, 150.8, 153, 150.8);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(148);
$pdf->SetX(115.3);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($ddi!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(148.8);
$pdf->SetX(40);
$pdf->Cell(25, 6, utf8_decode($ddi), 0,0, 'C');
$pdf->Line(50, 152.6, 80, 152.6);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(149.7);
$pdf->SetX(67.8);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($ddoderu!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(148.5);
$pdf->SetX(124.7);
$pdf->Cell(25, 6, utf8_decode($ddoderu), 0,0, 'C');
$pdf->Line(134.6, 152.6, 163, 152.6);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(149.7);
$pdf->SetX(121.7);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($ddidos!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(152);
$pdf->SetX(40);
$pdf->Cell(25, 6, utf8_decode($ddidos), 0,0, 'C');
$pdf->Line(50, 156, 82, 156);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(153);
$pdf->SetX(69.8);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($dedodos!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(152);
$pdf->SetX(122);
$pdf->Cell(25, 6, utf8_decode($dedodos), 0,0, 'C');
$pdf->Line(132.5, 156, 163.2, 156);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(153);
$pdf->SetX(119.6);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($dditres!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(154);
$pdf->SetX(40);
$pdf->Cell(25, 6, utf8_decode($dditres), 0,0, 'C');
$pdf->Line(50, 158, 83.2, 158);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(155);
$pdf->SetX(70.9);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($dedotres!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(154);
$pdf->SetX(122);
$pdf->Cell(25, 6, utf8_decode($dedotres), 0,0, 'C');
$pdf->Line(131, 158, 163.5, 158);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(155);
$pdf->SetX(118.5);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($dedocuatro!=NULL){
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(155.8);
$pdf->SetX(122);
$pdf->Cell(25, 6, utf8_decode($dedocuatro), 0,0, 'C');
$pdf->Line(130, 159.5, 163.5, 159.5);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(156.5);
$pdf->SetX(117.3);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($ddicuatro!=NULL){
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(155.8);
$pdf->SetX(40);
$pdf->Cell(25, 6, utf8_decode($ddicuatro), 0,0, 'C');
$pdf->Line(50, 159.5, 84.3, 159.5);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(156.5);
$pdf->SetX(72);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($ddicinco!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(154.4);
$pdf->SetX(75.5);
$pdf->Cell(25, 6, utf8_decode($ddicinco), 0,0, 'C');
$pdf->Line(86.5, 158.3, 96.5, 158.3);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(155.3);
$pdf->SetX(73.8);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($dedocincoo!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(154.4);
$pdf->SetX(107);
$pdf->Cell(25, 6, utf8_decode($dedocincoo), 0,0, 'C');
$pdf->Line(118, 158.3, 128, 158.3);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(155.3);
$pdf->SetX(115.7);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($iabdomen!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(138.5);
$pdf->SetX(97);
$pdf->Cell(25, 6, utf8_decode($iabdomen), 0,0, 'C');
$pdf->Line(107.5, 143, 127, 143);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(140.1);
$pdf->SetX(95);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($marb!=NULL){
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(148);
$pdf->SetX(104);
$pdf->Cell(25, 6, utf8_decode($marb), 0,0, 'C');
$pdf->Line(110, 152, 127, 152);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(149);
$pdf->SetX(97.5);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}


if($inglei!=NULL){
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(148);
$pdf->SetX(76.5);
$pdf->Cell(25, 6, utf8_decode($inglei), 0,0, 'C');
$pdf->Line(88.2, 152, 105.2, 152);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(149);
$pdf->SetX(93);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($mara!=NULL){
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(151.3);
$pdf->SetX(99);
$pdf->Cell(25, 6, utf8_decode($mara), 0,0, 'C');
$pdf->Line(107, 155.3, 127, 155.3);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(152.3);
$pdf->SetX(94.7);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($musloi!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(160.7);
$pdf->SetX(60);
$pdf->Cell(25, 6, utf8_decode($musloi), 0,0, 'C');
$pdf->Line(70, 165, 100, 165);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(162.1);
$pdf->SetX(87.8);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($muslod!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(160.8);
$pdf->SetX(105);
$pdf->Cell(25, 6, utf8_decode($muslod), 0,0, 'C');
$pdf->Line(115, 165, 143, 165);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(162.1);
$pdf->SetX(102.2);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($rodd!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(170.8);
$pdf->SetX(105);
$pdf->Cell(25, 6, utf8_decode($rodd), 0,0, 'C');
$pdf->Line(115, 175, 143, 175);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(172.1);
$pdf->SetX(102.2);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($rodi!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(170.8);
$pdf->SetX(60);
$pdf->Cell(25, 6, utf8_decode($rodi), 0,0, 'C');
$pdf->Line(70, 175, 100, 175);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(172.1);
$pdf->SetX(88);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($tod!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(190.8);
$pdf->SetX(60);
$pdf->Cell(25, 6, utf8_decode($tod), 0,0, 'C');
$pdf->Line(70, 195, 101, 195);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(192.1);
$pdf->SetX(89);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($toi!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(190.8);
$pdf->SetX(103);
$pdf->Cell(25, 6, utf8_decode($toi), 0,0, 'C');
$pdf->Line(113, 195, 143, 195);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(192.1);
$pdf->SetX(100);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($pied!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(195.8);
$pdf->SetX(104);
$pdf->Cell(25, 6, utf8_decode($pied), 0,0, 'C');
$pdf->Line(115, 200, 143, 200);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(197);
$pdf->SetX(102);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($pie!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(195.8);
$pdf->SetX(60);
$pdf->Cell(25, 6, utf8_decode($pie), 0,0, 'C');
$pdf->Line(70, 200, 100, 200);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(197);
$pdf->SetX(88);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

$pdf->Ln(200);

//terminomarcaje frontal

$pdf->Cell(189, 6, utf8_decode(''), 0,0, 'C');

$pdf->Image('../../img/cuerpot.jpg' , 79, 42, 56);

//marcaje trasero
if($plantapiea!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(140.5);
$pdf->SetX(55);
$pdf->Cell(25, 6, utf8_decode($plantapiea), 0,0, 'C');
$pdf->Line(65, 145, 98.5, 145);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(142);
$pdf->SetX(86.5);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($plantapieader!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(140.5);
$pdf->SetX(104);
$pdf->Cell(25, 6, utf8_decode($plantapieader), 0,0, 'C');
$pdf->Line(113.5, 145, 145, 145);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(142);
$pdf->SetX(101);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($tobilloatd!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(135.5);
$pdf->SetX(104);
$pdf->Cell(25, 6, utf8_decode($tobilloatd), 0,0, 'C');
$pdf->Line(113.5, 140, 145, 140);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(137);
$pdf->SetX(101);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($tobilloati!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(135.5);
$pdf->SetX(55);
$pdf->Cell(25, 6, utf8_decode($tobilloati), 0,0, 'C');
$pdf->Line(65, 140, 100, 140);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(137);
$pdf->SetX(88);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($ptiatras!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(124.5);
$pdf->SetX(55);
$pdf->Cell(25, 6, utf8_decode($ptiatras), 0,0, 'C');
$pdf->Line(65, 129, 100, 129);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(126);
$pdf->SetX(88);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($ptdatras!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(124.5);
$pdf->SetX(104);
$pdf->Cell(25, 6, utf8_decode($ptdatras), 0,0, 'C');
$pdf->Line(113.5, 129, 145, 129);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(126);
$pdf->SetX(100.6);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($pierespaldad!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(112.5);
$pdf->SetX(104);
$pdf->Cell(25, 6, utf8_decode($pierespaldad), 0,0, 'C');
$pdf->Line(113.5, 117, 145, 117);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(114);
$pdf->SetX(100.6);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($pierespaldai!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(112.5);
$pdf->SetX(55);
$pdf->Cell(25, 6, utf8_decode($pierespaldai), 0,0, 'C');
$pdf->Line(65, 117, 100, 117);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(114);
$pdf->SetX(88);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($musloatrasiz!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(105.5);
$pdf->SetX(55);
$pdf->Cell(25, 6, utf8_decode($musloatrasiz), 0,0, 'C');
$pdf->Line(65, 110, 100, 110);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(107);
$pdf->SetX(88);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($musloatrasder!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(105.5);
$pdf->SetX(103.9);
$pdf->Cell(25, 6, utf8_decode($musloatrasder), 0,0, 'C');
$pdf->Line(113.5, 110, 145, 110);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(107);
$pdf->SetX(100.3);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($dorsalder!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(89.5);
$pdf->SetX(121);
$pdf->Cell(25, 6, utf8_decode($dorsalder), 0,0, 'C');
$pdf->Line(130, 94, 165, 94);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(91);
$pdf->SetX(117.5);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($dorsaliz!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(89.5);
$pdf->SetX(41);
$pdf->Cell(25, 6, utf8_decode($dorsaliz), 0,0, 'C');
$pdf->Line(50, 94, 82, 94);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(91);
$pdf->SetX(70);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($munecaatrasiz!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(85.5);
$pdf->SetX(41);
$pdf->Cell(25, 6, utf8_decode($munecaatrasiz), 0,0, 'C');
$pdf->Line(50, 90, 84, 90);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(87);
$pdf->SetX(72);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($munecaatrasder!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(85.5);
$pdf->SetX(118);
$pdf->Cell(25, 6, utf8_decode($munecaatrasder), 0,0, 'C');
$pdf->Line(128, 90, 165, 90);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(87);
$pdf->SetX(115);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($antebdesp!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(81.5);
$pdf->SetX(116);
$pdf->Cell(25, 6, utf8_decode($antebdesp), 0,0, 'C');
$pdf->Line(126, 86, 165, 86);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(83);
$pdf->SetX(113);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($antebiesp!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(81.5);
$pdf->SetX(41);
$pdf->Cell(25, 6, utf8_decode($antebiesp), 0,0, 'C');
$pdf->Line(50, 86, 86, 86);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(83);
$pdf->SetX(74);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($casicodoi!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(75.5);
$pdf->SetX(41);
$pdf->Cell(25, 6, utf8_decode($casicodoi), 0,0, 'C');
$pdf->Line(50, 80, 88.5, 80);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(77);
$pdf->SetX(76);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($casicododer!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(75.5);
$pdf->SetX(114);
$pdf->Cell(25, 6, utf8_decode($casicododer), 0,0, 'C');
$pdf->Line(123.5, 80, 165, 80);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(77);
$pdf->SetX(111);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($brazaltder!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(69.5);
$pdf->SetX(114);
$pdf->Cell(25, 6, utf8_decode($brazaltder), 0,0, 'C');
$pdf->Line(123, 74, 165, 74);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(71);
$pdf->SetX(110);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($brazalti!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(69.5);
$pdf->SetX(41);
$pdf->Cell(25, 6, utf8_decode($brazalti), 0,0, 'C');
$pdf->Line(50, 74, 89.3, 74);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(71);
$pdf->SetX(77);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($glutiz!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(93.2);
$pdf->SetX(65);
$pdf->Cell(25, 6, utf8_decode($glutiz), 0,0, 'C');
$pdf->Line(73, 97.4, 102, 97.4);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(94.5);
$pdf->SetX(90);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($glutder!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(93.2);
$pdf->SetX(101);
$pdf->Cell(25, 6, utf8_decode($glutder), 0,0, 'C');
$pdf->Line(110, 97.4, 137, 97.4);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(94.5);
$pdf->SetX(97.2);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cinturader!=NULL){
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(83.5);
$pdf->SetX(99);
$pdf->Cell(25, 6, utf8_decode($cinturader), 0,0, 'C');
$pdf->Line(109, 87.4, 126, 87.4);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(84.5);
$pdf->SetX(96.6);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cinturaiz!=NULL){
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(83.5);
$pdf->SetX(77);
$pdf->Cell(25, 6, utf8_decode($cinturaiz), 0,0, 'C');
$pdf->Line(87, 87.4, 103.5, 87.4);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(84.5);
$pdf->SetX(91);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($marh!=NULL){
  $pdf->SetFont('Arial', 'B',5);
$pdf->SetY(79);
$pdf->SetX(65);
$pdf->Cell(25, 6, utf8_decode($marh), 0,0, 'C');
$pdf->Line(73, 83, 106, 83);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(80);
$pdf->SetX(94);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($costilliz!=NULL){
  $pdf->SetFont('Arial', 'B',5);
$pdf->SetY(72.2);
$pdf->SetX(63.5);
$pdf->Cell(25, 6, utf8_decode($costilliz), 0,0, 'C');
$pdf->Line(73, 76, 102, 76);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(73);
$pdf->SetX(90);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($costillder!=NULL){
  $pdf->SetFont('Arial', 'B',5);
$pdf->SetY(72.2);
$pdf->SetX(100);
$pdf->Cell(25, 6, utf8_decode($costillder), 0,0, 'C');
$pdf->Line(110, 76, 135, 76);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(73);
$pdf->SetX(97);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($espaldaarribader!=NULL){
  $pdf->SetFont('Arial', 'B',6);
$pdf->SetY(62.7);
$pdf->SetX(103);
$pdf->Cell(25, 6, utf8_decode($espaldaarribader), 0,0, 'C');
$pdf->Line(113, 67, 143, 67);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(64);
$pdf->SetX(100);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($espaldarribaiz!=NULL){
  $pdf->SetFont('Arial', 'B',6);
$pdf->SetY(62.7);
$pdf->SetX(63.5);
$pdf->Cell(25, 6, utf8_decode($espaldarribaiz), 0,0, 'C');
$pdf->Line(73, 67, 100, 67);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(64);
$pdf->SetX(88);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($espaldaalta!=NULL){
  $pdf->SetFont('Arial', 'B',6);
$pdf->SetY(55.6);
$pdf->SetX(63.5);
$pdf->Cell(25, 6, utf8_decode($espaldaalta), 0,0, 'C');
$pdf->Line(73, 60, 105.5, 60);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(57);
$pdf->SetX(93.5);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cuellatrasb!=NULL){
  $pdf->SetFont('Arial', 'B',6);
$pdf->SetY(50.6);
$pdf->SetX(63.5);
$pdf->Cell(25, 6, utf8_decode($cuellatrasb), 0,0, 'C');
$pdf->Line(73, 55, 105.5, 55);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(52);
$pdf->SetX(93.5);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cuellatrasmedio!=NULL){
  $pdf->SetFont('Arial', 'B',6);
$pdf->SetY(47.4);
$pdf->SetX(63.5);
$pdf->Cell(25, 6, utf8_decode($cuellatrasmedio), 0,0, 'C');
$pdf->Line(73, 51.5, 105.5, 51.5);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(48.5);
$pdf->SetX(93.5);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cabedorsalm!=NULL){
  $pdf->SetFont('Arial', 'B',6);
$pdf->SetY(44.4);
$pdf->SetX(63.5);
$pdf->Cell(25, 6, utf8_decode($cabedorsalm), 0,0, 'C');
$pdf->Line(73, 48.5, 105.5, 48.5);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(45.5);
$pdf->SetX(93.5);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cabealtaizqu!=NULL){
  $pdf->SetFont('Arial', 'B',6);
$pdf->SetY(41.4);
$pdf->SetX(63.3);
$pdf->Cell(25, 6, utf8_decode($cabealtaizqu), 0,0, 'C');
$pdf->Line(73, 45.5, 103, 45.5);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(42.5);
$pdf->SetX(91);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cabezaaltader!=NULL){
  $pdf->SetFont('Arial', 'B',6);
$pdf->SetY(41.4);
$pdf->SetX(99.5);
$pdf->Cell(25, 6, utf8_decode($cabezaaltader), 0,0, 'C');
$pdf->Line(109, 45.5, 133, 45.5);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(42.5);
$pdf->SetX(96);
$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}


//termino marcaje atras

$pdf->Ln(130);


$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(195, 6, utf8_decode('VALORACIÓN NEUROLÓGICA'), 0, 0, 'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 6, utf8_decode('Variable'), 1, 0, 'C');
$pdf->Cell(95, 6, utf8_decode('Respuesta'), 1, 0, 'C');
$pdf->Cell(20, 6, utf8_decode('Valor'), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode('Resultado'), 1, 0, 'C');

$pdf->Ln(6);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 6, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 6, utf8_decode('Espontáneamente'), 1, 0, 'L');
$pdf->Cell(20, 6, utf8_decode('4'), 1, 0, 'C');
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(25, 24, utf8_decode($apecular_mat), 1, 0, 'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 6, utf8_decode('Apertura ocular'), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 6, utf8_decode('A una orden verbal'), 1, 0, 'L');
$pdf->Cell(20, 6, utf8_decode('3'), 1, 0, 'C');

$pdf->Ln(6);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 6, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 6, utf8_decode('Al dolor'), 1, 0, 'L');
$pdf->Cell(20, 6, utf8_decode('2'), 1, 0, 'C');

$pdf->Ln(6);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 6, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 6, utf8_decode('Sin respuesta'), 1, 0, 'L');
$pdf->Cell(20, 6, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(6);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 6, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 6, utf8_decode('A una orden verbal obedece'), 1, 0, 'L');
$pdf->Cell(20, 6, utf8_decode('6'), 1, 0, 'C');
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(25, 36, utf8_decode($respmot_mat), 1, 0, 'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 6, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 6, utf8_decode('Localiza el dolor'), 1, 0, 'L');
$pdf->Cell(20, 6, utf8_decode('5'), 1, 0, 'C');

$pdf->Ln(6);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 6, utf8_decode('Mejor respuesta motora'), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 6, utf8_decode('Retirada y flexión al dolor'), 1, 0, 'L');
$pdf->Cell(20, 6, utf8_decode('4'), 1, 0, 'C');

$pdf->Ln(6);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 6, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 6, utf8_decode('Flexión anormal (rigidez de descorticación)'), 1, 0, 'L');
$pdf->Cell(20, 6, utf8_decode('3'), 1, 0, 'C');

$pdf->Ln(6);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 6, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 6, utf8_decode('Extensión (rigidez de descerebración)'), 1, 0, 'L');
$pdf->Cell(20, 6, utf8_decode('2'), 1, 0, 'C');

$pdf->Ln(6);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 6, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 6, utf8_decode('No responde'), 1, 0, 'L');
$pdf->Cell(20, 6, utf8_decode('1'), 1, 0, 'C');


$pdf->Ln(6);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 6, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 6, utf8_decode('Orientado y conversando'), 1, 0, 'L');
$pdf->Cell(20, 6, utf8_decode('5'), 1, 0, 'C');
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(25, 30, utf8_decode($respver_mat), 1, 0, 'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 6, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 6, utf8_decode('Desorientado y hablando'), 1, 0, 'L');
$pdf->Cell(20, 6, utf8_decode('4'), 1, 0, 'C');

$pdf->Ln(6);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 6, utf8_decode('Mejor respuesta verbal'), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 6, utf8_decode('Palabras inapropiadas'), 1, 0, 'L');
$pdf->Cell(20, 6, utf8_decode('3'), 1, 0, 'C');

$pdf->Ln(6);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 6, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 6, utf8_decode('Sonidos incomprensibles'), 1, 0, 'L');
$pdf->Cell(20, 6, utf8_decode('2'), 1, 0, 'C');

$pdf->Ln(6);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 6, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 6, utf8_decode('Ninguna respuesta'), 1, 0, 'L');
$pdf->Cell(20, 6, utf8_decode('1'), 1, 0, 'C');
$pdf->Ln(6);
$pdf->Cell(55, 6, utf8_decode(''), 1, 0, 'C');
$pdf->Cell(95, 6, utf8_decode(''), 1, 0, 'L');
$pdf->Cell(20, 6, utf8_decode('Total:'), 1, 0, 'C');
$totgl=$respver_mat+$apecular_mat+$respmot_mat;
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(25, 6, utf8_decode($totgl), 1, 0, 'C');

$pdf->Ln(50);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(195, 4, utf8_decode('CONTROL DE CATÉTERES'), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(30, 4, utf8_decode('Dispositivo'), 1, 0, 'C');
$pdf->Cell(10, 4, utf8_decode('Tipo'), 1, 0, 'C');
$pdf->Cell(30, 4, utf8_decode('Fecha de instalación'), 1, 0, 'C');
$pdf->Cell(35, 4, utf8_decode('Instalo'), 1, 0, 'C');
$pdf->Cell(25, 4, utf8_decode('Dias instalado'), 1, 0, 'C');
$pdf->Cell(25, 4, utf8_decode('Fecha de cambio'), 1, 0, 'C');
$pdf->Cell(40, 4, utf8_decode('Observaciones'), 1, 0, 'C');
$pdf->Ln(4);
$medica = $conexion->query("select * from cate_enf_hosp WHERE id_atencion=$id_atencion ORDER BY id_cateh DESC") or die($conexion->error);
while ($row_m = $medica->fetch_assoc()) {

$pdf->SetFont('Arial', '', 6);
$pdf->Cell(30,4, $row_m['dispositivos'],1,0,'C');
$pdf->Cell(10,4, $row_m['tipo'],1,0,'C');
$pdf->Cell(30,4, $row_m['fecha_inst'],1,0,'C');
$pdf->Cell(35,4, $row_m['instalo'],1,0,'C');
$pdf->Cell(25,4, $row_m['dias_inst'],1,0,'C');
$pdf->Cell(25,4, $row_m['fecha_cambio'],1,0,'C');
$pdf->Cell(40,4, $row_m['cultivo'],1,1,'C');
}

//caidas
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(195, 4, utf8_decode('VALORACIÓN DE RIESGO DE CAIDAS ESCALA DE DOWNTON'), 0, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode('Variable'), 1, 0, 'C');
$pdf->Cell(95, 4, utf8_decode('Observación'), 1, 0, 'C');
$pdf->Cell(20, 4, utf8_decode('Calificación'), 1, 0, 'C');
$pdf->Cell(25, 4, utf8_decode('Resultado'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode('Caidas previas'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('No'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('0'), 1, 0, 'C');
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(25, 8, utf8_decode($caidprev_mat), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Si'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Ninguno'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('0'), 1, 0, 'C');
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(25, 28, utf8_decode($medcaidas_mat), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Tranquilizantes-Sedantes'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Diuréticos'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode('Medicamentos'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Hipotensores (no diuréticos)'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Antiparksonianos'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Antidepresivos'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Otros medicamentos'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Ninguno'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('0'), 1, 0, 'C');
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(25, 16, utf8_decode($defsens_mat), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode('Déficits sensoriales'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Alteraciones visuales'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Alteraciones auditivas'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Extremidades (ictus..)'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode('Estado mental'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Orientado'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('0'), 1, 0, 'C');
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(25, 8, utf8_decode($edomental_mat), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Confuso'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Normal'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('0'), 1, 0, 'C');
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(25, 16, utf8_decode($deambula_mat), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode('Deambulación'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Segura con ayuda'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Insegura con ayuda / sin ayuda'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Imposible'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode(''), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('Total:'), 1, 0, 'C');
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(25, 4, utf8_decode($totalcaidas_mat), 1, 0, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(85, 4, utf8_decode('clasificación del riesgo'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
if($totalcaidas_mat>2){
  $pdf->Cell(110, 4, utf8_decode('Alto riesgo para caída'), 1, 0, 'L');
}else{
   $pdf->Cell(110, 4, utf8_decode('No hay riesgo para caída'), 1, 0, 'L');
}




$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(105, 4, utf8_decode('Intervenciones / recomendaciones para prevención de riesgo de caída'), 1, 0, 'C');
$pdf->SetFont('Arial', '',6);
$pdf->Cell(90, 4, utf8_decode($riesgcaida_mat), 1, 0, 'L');
$pdf->SetFont('Arial', 'B',7);
$pdf->Ln(4);
$pdf->Cell(195, 4, utf8_decode('Interpretación: Todos los pacientes con 3 o más puntos en esta calificación se consideran de alto riesgo para caida'), 1, 0, 'L');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(85, 4, utf8_decode('Nombre de enfermera (o) que valora'), 1, 0, 'C');
$pdf->SetFont('Arial', '',6);
$pdf->Cell(110, 4, utf8_decode($nomenfermera_mat), 1, 0, 'L');
//nortton tabla

$pdf->Ln(8);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(140, 4, utf8_decode('VALORACIÓN DE ULCERAS POR PRESIÓN NORTON'), 1, 0, 'C');
$pdf->Cell(55, 4, utf8_decode('RESULTADO'), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);

//$pdf->Image('../../imagenes/escala_norton.jpg', 49, 163.5, 100);
$pdf->Cell(30, 4, utf8_decode(''), 0, 0, 'C');
$pdf->Cell(139,69, $pdf->Image('../../imagenes/escala_norton.jpg', $pdf->GetX(), $pdf->GetY(),102),0);

$pdf->Ln(1.5);
$pdf->SetFont('Arial', 'B',7);
$pdf->SetX(150);
$pdf->Cell(55, 12, utf8_decode($edofisico_mat), 1, 0, 'C');
$pdf->Ln(12.5);
$pdf->SetX(150);
$pdf->Cell(55, 12, utf8_decode($edomentalnor_mat), 1, 0, 'C');
$pdf->Ln(12.5);
$pdf->SetX(150);
$pdf->Cell(55, 12, utf8_decode($actividad_mat), 1, 0, 'C');
$pdf->Ln(12.5);
$pdf->SetX(150);
$pdf->Cell(55, 12, utf8_decode($movilidad_mat), 1, 0, 'C');
$pdf->Ln(12.5);
$pdf->SetX(150);
$pdf->Cell(55, 12, utf8_decode($incont_mat), 1, 0, 'C');
$pdf->Ln(12.5);
$pdf->SetX(150);
$pdf->Cell(55, 4, utf8_decode($totnorton_mat), 1, 0, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(85, 4, utf8_decode('Clasificación del riesgo'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
if($totnorton_mat >14){
$pdf->Cell(110, 4, utf8_decode('Risgo minimo'), 1, 0, 'L');
}else if($totnorton_mat >11 && $totnorton_mat <15){
$pdf->Cell(110, 4, utf8_decode('Riesgo evidente'), 1, 0, 'L');
}else if($totnorton_mat >4 && $totnorton_mat <12){
$pdf->Cell(110, 4, utf8_decode('Muy alto riesgo'), 1, 0, 'L');
}else if($totnorton_mat <=4){
$pdf->Cell(110, 4, utf8_decode('No hay suficientes datos para dar una clasificación'), 1, 0, 'L');
}


$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(85, 4, utf8_decode('Nombre de enfermera (o) que valora'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(110, 4, utf8_decode($nomenfnorton_mat), 1, 0, 'L');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(85, 4, utf8_decode('Intervenciones de acuerdo al riesgo'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(110, 4, utf8_decode($acriesg_mat), 1, 0, 'L');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(195, 4, utf8_decode('Interpretación: 5-11 puntos: Muy alto riesgo | 12-14 puntos: Riesgo evidente | mayor de 14 puntos: Riesgo minimo'), 1, 0, 'L');

$pdf->Ln(85);
/*
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(195, 4, utf8_decode('DIAGNÓSTICOS DE ENFERMERIA (NANDA)'),0,0, 'C');
$pdf->SetX(187);
$pdf->SetFont('Arial', 'B',6);
$pdf->Cell(18, 4, utf8_decode('TURNO'), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',6);
$pdf->Cell(80, 4, utf8_decode('DOMINIO 1.PROMOCIÓN DE LA SALUD'), 1, 0, 'C');
$pdf->Cell(6, 4, utf8_decode('M'), 1, 0, 'C');
$pdf->Cell(6, 4, utf8_decode('V'), 1, 0, 'C');
$pdf->Cell(6, 4, utf8_decode('N'), 1, 0, 'C');
$pdf->Cell(79, 4, utf8_decode('DOMINIO 8.SEXUALIDAD'), 1, 0, 'C');
$pdf->SetFont('Arial', 'B',6);
$pdf->Cell(6, 4, utf8_decode('M'), 1, 0, 'C');
$pdf->Cell(6, 4, utf8_decode('V'), 1, 0, 'C');
$pdf->Cell(6, 4, utf8_decode('N'), 1, 0, 'C');


$manregtera_mat=" ";
$val = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$manregtera_mat=$val_res['manregtera_mat'];
}
if($manregtera_mat=="SI"){$manregtera_mat="X";}

$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('MANEJO INEFECTIVO DEL REGIMEN TERAPÉUTICO'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($manregtera_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$manregtera_mat=$val_resv['manregtera_mat'];
}
if($manregtera_mat=="SI"){$manregtera_mat="X";}
$pdf->Cell(6, 4, utf8_decode($manregtera_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$manregtera_mat=$val_resv['manregtera_mat'];
}
if($manregtera_mat=="SI"){$manregtera_mat="X";}
$pdf->Cell(6, 4, utf8_decode($manregtera_mat), 1, 0, 'C');

$patsexual_mat=" ";
$pdf->Cell(79, 4, utf8_decode('PATRÓN SEXUAL INEFECTIVO'), 1, 0, 'L');
$val = "SELECT patsexual_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$patsexual_mat=$val_res['patsexual_mat'];
}
if($patsexual_mat=="SI"){$patsexual_mat="X";}
$pdf->Cell(6, 4, utf8_decode($patsexual_mat), 1, 0, 'C');
$val = "SELECT patsexual_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$patsexual_mat=$val_res['patsexual_mat'];
}
if($patsexual_mat=="SI"){$patsexual_mat="X";}
$pdf->Cell(6, 4, utf8_decode($patsexual_mat), 1, 0, 'C');
$val = "SELECT patsexual_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$patsexual_mat=$val_res['patsexual_mat'];
}
if($patsexual_mat=="SI"){$patsexual_mat="X";}
$pdf->Cell(6, 4, utf8_decode($patsexual_mat), 1, 0, 'C');
//2 y 9
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',6);
$pdf->Cell(80, 4, utf8_decode('DOMINIO 2.NUTRICIÓN'), 1, 0, 'C');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'C');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'C');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'C');
$pdf->Cell(79, 4, utf8_decode('DOMINIO 9. AFRONTAMIENTO / TOLERANCIA AL ESTRÉS'), 1, 0, 'C');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'C');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'C');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'C');

$detglucion_mat=" ";

$val = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$detglucion_mat=$val_res['detglucion_mat'];
}
if($detglucion_mat=="SI"){$detglucion_mat="X";}

$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('DETERÍORO DE LA DEGLUCIÓN'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($detglucion_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$detglucion_mat=$val_resv['detglucion_mat'];
}
if($detglucion_mat=="SI"){$detglucion_mat="X";}
$pdf->Cell(6, 4, utf8_decode($detglucion_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$detglucion_mat=$val_resv['detglucion_mat'];
}
if($detglucion_mat=="SI"){$detglucion_mat="X";}
$pdf->Cell(6, 4, utf8_decode($detglucion_mat), 1, 0, 'C');

$sinpostra_mat=" ";
$pdf->Cell(79, 4, utf8_decode('SINDROME POSTRAUMÁTICO'), 1, 0, 'L');
$val = "SELECT sinpostra_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$sinpostra_mat=$val_res['sinpostra_mat'];
}
if($sinpostra_mat=="SI"){$sinpostra_mat="X";}
$pdf->Cell(6, 4, utf8_decode($sinpostra_mat), 1, 0, 'C');
$val = "SELECT sinpostra_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$sinpostra_mat=$val_res['sinpostra_mat'];
}
if($sinpostra_mat=="SI"){$sinpostra_mat="X";}
$pdf->Cell(6, 4, utf8_decode($sinpostra_mat), 1, 0, 'C');
$val = "SELECT sinpostra_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$sinpostra_mat=$val_res['sinpostra_mat'];
}
if($sinpostra_mat=="SI"){$sinpostra_mat="X";}
$pdf->Cell(6, 4, utf8_decode($sinpostra_mat), 1, 0, 'C');

//desnutri
$nutridefecto_mat=" ";
$val = "SELECT nutridefecto_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$nutridefecto_mat=$val_res['nutridefecto_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('DESEQUILIBRIO NUTRICIONAL POR DEFECTO'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($nutridefecto_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$nutridefecto_mat=$val_resv['nutridefecto_mat'];
}
if($nutridefecto_mat=="SI"){$nutridefecto_mat="X";}
$pdf->Cell(6, 4, utf8_decode($nutridefecto_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$nutridefecto_mat=$val_resv['nutridefecto_mat'];
}
if($nutridefecto_mat=="SI"){$nutridefecto_mat="X";}
$pdf->Cell(6, 4, utf8_decode($nutridefecto_mat), 1, 0, 'C');
$risinpostra_mat=" ";
$pdf->Cell(79, 4, utf8_decode('RIESGO DE SINDROME POSTRAUMÁTICO'), 1, 0, 'L');
$val = "SELECT risinpostra_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$risinpostra_mat=$val_res['risinpostra_mat'];
}
if($risinpostra_mat=="SI"){$risinpostra_mat="X";}
$pdf->Cell(6, 4, utf8_decode($risinpostra_mat), 1, 0, 'C');
$val = "SELECT risinpostra_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$risinpostra_mat=$val_res['risinpostra_mat'];
}
if($risinpostra_mat=="SI"){$risinpostra_mat="X";}
$pdf->Cell(6, 4, utf8_decode($risinpostra_mat), 1, 0, 'C');
$val = "SELECT risinpostra_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$risinpostra_mat=$val_res['risinpostra_mat'];
}
if($risinpostra_mat=="SI"){$risinpostra_mat="X";}
$pdf->Cell(6, 4, utf8_decode($risinpostra_mat), 1, 0, 'C');

//EXCESO
$nutriexc_mat =" ";
$val = "SELECT nutriexc_mat  FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$nutriexc_mat =$val_res['nutriexc_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('DESEQUILIBRIO NUTRICIONAL POR EXCESO'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($nutriexc_mat  ), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$nutriexc_mat =$val_resv['nutriexc_mat'];
}
if($nutriexc_mat  =="SI"){$nutriexc_mat ="X";}
$pdf->Cell(6, 4, utf8_decode($nutriexc_mat  ), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$nutriexc_mat =$val_resv['nutriexc_mat'];
}
if($nutriexc_mat  =="SI"){$nutriexc_mat ="X";}
$pdf->Cell(6, 4, utf8_decode($nutriexc_mat  ), 1, 0, 'C');

$temor_mat=" ";
$pdf->Cell(79, 4, utf8_decode('TEMOR'), 1, 0, 'L');
$val = "SELECT temor_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$temor_mat=$val_res['temor_mat'];
}
if($temor_mat=="SI"){$temor_mat="X";}
$pdf->Cell(6, 4, utf8_decode($temor_mat), 1, 0, 'C');
$val = "SELECT temor_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$temor_mat=$val_res['temor_mat'];
}
if($temor_mat=="SI"){$temor_mat="X";}
$pdf->Cell(6, 4, utf8_decode($temor_mat), 1, 0, 'C');
$val = "SELECT temor_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$temor_mat=$val_res['temor_mat'];
}
if($temor_mat=="SI"){$temor_mat="X";}
$pdf->Cell(6, 4, utf8_decode($temor_mat), 1, 0, 'C');

//DETOT
$voliqui_mat =" ";
$val = "SELECT voliqui_mat  FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$voliqui_mat =$val_res['voliqui_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('DÉFICIT DE VOLUMEN DE LÍQUIDOS'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($voliqui_mat  ), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$voliqui_mat =$val_resv['voliqui_mat'];
}
if($voliqui_mat  =="SI"){$voliqui_mat ="X";}
$pdf->Cell(6, 4, utf8_decode($voliqui_mat  ), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$voliqui_mat =$val_resv['voliqui_mat'];
}
if($voliqui_mat  =="SI"){$voliqui_mat ="X";}
$pdf->Cell(6, 4, utf8_decode($voliqui_mat  ), 1, 0, 'C');

$ansiedad_mat=" ";
$pdf->Cell(79, 4, utf8_decode('ANSIEDAD'), 1, 0, 'L');
$val = "SELECT ansiedad_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$ansiedad_mat=$val_res['ansiedad_mat'];
}
if($ansiedad_mat=="SI"){$ansiedad_mat="X";}
$pdf->Cell(6, 4, utf8_decode($ansiedad_mat), 1, 0, 'C');
$val = "SELECT ansiedad_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$ansiedad_mat=$val_res['ansiedad_mat'];
}
if($ansiedad_mat=="SI"){$ansiedad_mat="X";}
$pdf->Cell(6, 4, utf8_decode($ansiedad_mat), 1, 0, 'C');
$val = "SELECT ansiedad_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$ansiedad_mat=$val_res['ansiedad_mat'];
}
if($ansiedad_mat=="SI"){$ansiedad_mat="X";}
$pdf->Cell(6, 4, utf8_decode($ansiedad_mat), 1, 0, 'C');

// RIESGO DE DEFICIT
$defivoliq_mat =" ";
$val = "SELECT defivoliq_mat  FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$defivoliq_mat =$val_res['defivoliq_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('RIESGO DE DÉFICIT DE VOLUMEN DE LÍQUIDOS'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($defivoliq_mat  ), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$defivoliq_mat =$val_resv['defivoliq_mat'];
}
if($defivoliq_mat  =="SI"){$defivoliq_mat ="X";}
$pdf->Cell(6, 4, utf8_decode($defivoliq_mat  ), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$defivoliq_mat =$val_resv['defivoliq_mat'];
}
if($defivoliq_mat  =="SI"){$defivoliq_mat ="X";}
$pdf->Cell(6, 4, utf8_decode($defivoliq_mat  ), 1, 0, 'C');

$ansmuerte_mat=" ";
$pdf->Cell(79, 4, utf8_decode('ANSIEDAD ANTE LA MUERTE'), 1, 0, 'L');
$val = "SELECT ansmuerte_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$ansmuerte_mat=$val_res['ansmuerte_mat'];
}
if($ansmuerte_mat=="SI"){$ansmuerte_mat="X";}
$pdf->Cell(6, 4, utf8_decode($ansmuerte_mat), 1, 0, 'C');
$val = "SELECT ansmuerte_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$ansmuerte_mat=$val_res['ansmuerte_mat'];
}
if($ansmuerte_mat=="SI"){$ansmuerte_mat="X";}
$pdf->Cell(6, 4, utf8_decode($ansmuerte_mat), 1, 0, 'C');
$val = "SELECT ansmuerte_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$ansmuerte_mat=$val_res['ansmuerte_mat'];
}
if($ansmuerte_mat=="SI"){$ansmuerte_mat="X";}
$pdf->Cell(6, 4, utf8_decode($ansmuerte_mat), 1, 0, 'C');

//EXCESO DE VOL LIQUI
$exvoliqui_mat =" ";
$val = "SELECT exvoliqui_mat  FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$exvoliqui_mat =$val_res['exvoliqui_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('EXCESO DE VOLUMEN DE LÍQUIDOS'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($exvoliqui_mat  ), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$exvoliqui_mat =$val_resv['exvoliqui_mat'];
}
if($exvoliqui_mat  =="SI"){$exvoliqui_mat ="X";}
$pdf->Cell(6, 4, utf8_decode($exvoliqui_mat  ), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$exvoliqui_mat =$val_resv['exvoliqui_mat'];
}
if($exvoliqui_mat  =="SI"){$exvoliqui_mat ="X";}
$pdf->Cell(6, 4, utf8_decode($exvoliqui_mat  ), 1, 0, 'C');

$afronine_mat=" ";
$pdf->Cell(79, 4, utf8_decode('AFRONTAMIENTO INEFECTIVO'), 1, 0, 'L');
$val = "SELECT afronine_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$afronine_mat=$val_res['afronine_mat'];
}
if($afronine_mat=="SI"){$afronine_mat="X";}
$pdf->Cell(6, 4, utf8_decode($afronine_mat), 1, 0, 'C');
$val = "SELECT afronine_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$afronine_mat=$val_res['afronine_mat'];
}
if($afronine_mat=="SI"){$afronine_mat="X";}
$pdf->Cell(6, 4, utf8_decode($afronine_mat), 1, 0, 'C');
$val = "SELECT afronine_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$afronine_mat=$val_res['afronine_mat'];
}
if($afronine_mat=="SI"){$afronine_mat="X";}
$pdf->Cell(6, 4, utf8_decode($afronine_mat), 1, 0, 'C');

//RIESG DES
$desliqui_mat =" ";
$val = "SELECT desliqui_mat  FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$desliqui_mat =$val_res['desliqui_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('RIESGO DE DESEQUILIBRIO DE VOLUMEN DE LÍQUIDOS'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($desliqui_mat  ), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$desliqui_mat =$val_resv['desliqui_mat'];
}
if($desliqui_mat  =="SI"){$desliqui_mat ="X";}
$pdf->Cell(6, 4, utf8_decode($desliqui_mat  ), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$desliqui_mat =$val_resv['desliqui_mat'];
}
if($desliqui_mat  =="SI"){$desliqui_mat ="X";}
$pdf->Cell(6, 4, utf8_decode($desliqui_mat  ), 1, 0, 'C');

$disre_mat=" ";
$pdf->Cell(79, 4, utf8_decode('DISREFLEXIA AUTÓNOMA'), 1, 0, 'L');
$val = "SELECT disre_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$disre_mat=$val_res['disre_mat'];
}
if($disre_mat=="SI"){$disre_mat="X";}
$pdf->Cell(6, 4, utf8_decode($disre_mat), 1, 0, 'C');
$val = "SELECT disre_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$disre_mat=$val_res['disre_mat'];
}
if($disre_mat=="SI"){$disre_mat="X";}
$pdf->Cell(6, 4, utf8_decode($disre_mat), 1, 0, 'C');
$val = "SELECT disre_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$disre_mat=$val_res['disre_mat'];
}
if($disre_mat=="SI"){$disre_mat="X";}
$pdf->Cell(6, 4, utf8_decode($disre_mat), 1, 0, 'C');

// dominio 3 y disminucion
$disma_mat=" ";
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',6);
$pdf->Cell(80, 4, utf8_decode('DOMINIO 3. ELIMINACIÓN'), 1, 0, 'C');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'C');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'C');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',6);
$pdf->Cell(79, 4, utf8_decode('DISMINUCIÓN DE LA CAPACIDAD ADAPTATIVA INTRACRANEAL'), 1, 0, 'L');
$val = "SELECT disma_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$disma_mat=$val_res['disma_mat'];
}
if($disma_mat=="SI"){$disma_mat="X";}
$pdf->Cell(6, 4, utf8_decode($disma_mat), 1, 0, 'C');
$val = "SELECT disma_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$disma_mat=$val_res['disma_mat'];
}
if($disma_mat=="SI"){$disma_mat="X";}
$pdf->Cell(6, 4, utf8_decode($disma_mat), 1, 0, 'C');
$val = "SELECT disma_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$disma_mat=$val_res['disma_mat'];
}
if($disma_mat=="SI"){$disma_mat="X";}
$pdf->Cell(6, 4, utf8_decode($disma_mat), 1, 0, 'C');

//DET URI Y DOMINIO 10
$eliuri_mat =" ";
$val = "SELECT eliuri_mat  FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$eliuri_mat =$val_res['eliuri_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('DETERIORO DE LA ELIMINACIÓN URINARIA'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($eliuri_mat  ), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$eliuri_mat =$val_resv['eliuri_mat'];
}
if($eliuri_mat  =="SI"){$eliuri_mat ="X";}
$pdf->Cell(6, 4, utf8_decode($eliuri_mat  ), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$eliuri_mat =$val_resv['eliuri_mat'];
}
if($eliuri_mat  =="SI"){$eliuri_mat ="X";}
$pdf->Cell(6, 4, utf8_decode($eliuri_mat  ), 1, 0, 'C');
$pdf->SetFont('Arial', 'B',6);
$pdf->Cell(79, 4, utf8_decode('DOMINIO 10. PRINCIPIOS VITALES'), 1, 0, 'C');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'C');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'C');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'C');

//RET URI Y SUFRI ESP
$returi_mat =" ";
$val = "SELECT returi_mat  FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$returi_mat =$val_res['returi_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('RETENCIÓN URINARIA'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($returi_mat  ), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$returi_mat =$val_resv['returi_mat'];
}
if($returi_mat  =="SI"){$returi_mat ="X";}
$pdf->Cell(6, 4, utf8_decode($returi_mat  ), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$returi_mat =$val_resv['returi_mat'];
}
if($returi_mat  =="SI"){$returi_mat ="X";}
$pdf->Cell(6, 4, utf8_decode($returi_mat  ), 1, 0, 'C');

$sufesp_mat=" ";
$pdf->Cell(79, 4, utf8_decode('SUFRIMIENTO ESPIRITUAL'), 1, 0, 'L');
$val = "SELECT sufesp_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$sufesp_mat=$val_res['sufesp_mat'];
}
if($sufesp_mat=="SI"){$sufesp_mat="X";}
$pdf->Cell(6, 4, utf8_decode($sufesp_mat), 1, 0, 'C');
$val = "SELECT sufesp_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$sufesp_mat=$val_res['sufesp_mat'];
}
if($sufesp_mat=="SI"){$sufesp_mat="X";}
$pdf->Cell(6, 4, utf8_decode($sufesp_mat), 1, 0, 'C');
$val = "SELECT sufesp_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$sufesp_mat=$val_res['sufesp_mat'];
}
if($sufesp_mat=="SI"){$sufesp_mat="X";}
$pdf->Cell(6, 4, utf8_decode($sufesp_mat), 1, 0, 'C');

//INCONTINGENCIA URINARIA TOTAL E INCUM DEL TRAR
$inconuri_mat =" ";
$val = "SELECT inconuri_mat  FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$inconuri_mat =$val_res['inconuri_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('INCONTINGENCIA URINARIA TOTAL'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($inconuri_mat  ), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$inconuri_mat =$val_resv['inconuri_mat'];
}
if($inconuri_mat  =="SI"){$inconuri_mat ="X";}
$pdf->Cell(6, 4, utf8_decode($inconuri_mat  ), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$inconuri_mat =$val_resv['inconuri_mat'];
}
if($inconuri_mat  =="SI"){$inconuri_mat ="X";}
$pdf->Cell(6, 4, utf8_decode($inconuri_mat  ), 1, 0, 'C');

$pdf->Cell(79, 4, utf8_decode('INCUMPLIMIENTO DEL TRATAMIENTO'), 1, 0, 'L');
$intrat_mat =" ";
$val = "SELECT intrat_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$intrat_mat=$val_res['intrat_mat'];
}
if($intrat_mat=="SI"){$intrat_mat="X";}
$pdf->Cell(6, 4, utf8_decode($intrat_mat), 1, 0, 'C');
$val = "SELECT intrat_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$intrat_mat=$val_res['intrat_mat'];
}
if($intrat_mat=="SI"){$intrat_mat="X";}
$pdf->Cell(6, 4, utf8_decode($intrat_mat), 1, 0, 'C');
$val = "SELECT intrat_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$intrat_mat=$val_res['intrat_mat'];
}
if($intrat_mat=="SI"){$intrat_mat="X";}
$pdf->Cell(6, 4, utf8_decode($intrat_mat), 1, 0, 'C');

//urinaria funcional y 11
$inconurifun_mat=" ";
$val = "SELECT inconurifun_mat  FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$inconurifun_mat =$val_res['inconurifun_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('INCONTINGENCIA URINARIA FUNCIONAL'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($inconurifun_mat  ), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$inconurifun_mat =$val_resv['inconurifun_mat'];
}
if($inconurifun_mat  =="SI"){$inconurifun_mat ="X";}
$pdf->Cell(6, 4, utf8_decode($inconurifun_mat  ), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$inconurifun_mat =$val_resv['inconurifun_mat'];
}
if($inconurifun_mat  =="SI"){$inconurifun_mat ="X";}
$pdf->Cell(6, 4, utf8_decode($inconurifun_mat  ), 1, 0, 'C');
$pdf->SetFont('Arial', 'B',6);
$pdf->Cell(79, 4, utf8_decode('DOMINIO 11. SEGURIDAD / PROTECCIÓN'), 1, 0, 'C');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'L');
 

 //inconfec y riesginfec
$inconfecal_mat =" ";
$val = "SELECT inconfecal_mat  FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$inconfecal_mat =$val_res['inconfecal_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('INCONTINGENCIA FECAL'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($inconfecal_mat  ), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$inconfecal_mat =$val_resv['inconfecal_mat'];
}
if($inconfecal_mat  =="SI"){$inconfecal_mat ="X";}
$pdf->Cell(6, 4, utf8_decode($inconfecal_mat  ), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$inconfecal_mat =$val_resv['inconfecal_mat'];
}
if($inconfecal_mat  =="SI"){$inconfecal_mat ="X";}
$pdf->Cell(6, 4, utf8_decode($inconfecal_mat  ), 1, 0, 'C');

$riesinfecc_mat=" ";
$pdf->Cell(79, 4, utf8_decode('RIESGO DE INFECCIÓN'), 1, 0, 'L');
$val = "SELECT riesinfecc_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$riesinfecc_mat=$val_res['riesinfecc_mat'];
}
if($riesinfecc_mat=="SI"){$riesinfecc_mat="X";}
$pdf->Cell(6, 4, utf8_decode($riesinfecc_mat), 1, 0, 'C');
$val = "SELECT riesinfecc_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$riesinfecc_mat=$val_res['riesinfecc_mat'];
}
if($riesinfecc_mat=="SI"){$riesinfecc_mat="X";}
$pdf->Cell(6, 4, utf8_decode($riesinfecc_mat), 1, 0, 'C');
$val = "SELECT riesinfecc_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$riesinfecc_mat=$val_res['riesinfecc_mat'];
}
if($riesinfecc_mat=="SI"){$riesinfecc_mat="X";}
$pdf->Cell(6, 4, utf8_decode($riesinfecc_mat), 1, 0, 'C');

// diarrea y deterio mucosa oral
$diarrea_mat =" ";
$val = "SELECT diarrea_mat  FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$diarrea_mat =$val_res['diarrea_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('DIARREA'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($diarrea_mat  ), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$diarrea_mat =$val_resv['diarrea_mat'];
}
if($diarrea_mat  =="SI"){$diarrea_mat ="X";}
$pdf->Cell(6, 4, utf8_decode($diarrea_mat  ), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$diarrea_mat =$val_resv['diarrea_mat'];
}
if($diarrea_mat  =="SI"){$diarrea_mat ="X";}
$pdf->Cell(6, 4, utf8_decode($diarrea_mat  ), 1, 0, 'C');

$mucoral_mat=" ";
$pdf->Cell(79, 4, utf8_decode('DETERÍORO DE LA MUCOSA ORAL'), 1, 0, 'L');
$val = "SELECT mucoral_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$mucoral_mat=$val_res['mucoral_mat'];
}
if($mucoral_mat=="SI"){$mucoral_mat="X";}
$pdf->Cell(6, 4, utf8_decode($mucoral_mat), 1, 0, 'C');
$val = "SELECT mucoral_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$mucoral_mat=$val_res['mucoral_mat'];
}
if($mucoral_mat=="SI"){$mucoral_mat="X";}
$pdf->Cell(6, 4, utf8_decode($mucoral_mat), 1, 0, 'C');
$val = "SELECT mucoral_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$mucoral_mat=$val_res['mucoral_mat'];
}
if($mucoral_mat=="SI"){$mucoral_mat="X";}
$pdf->Cell(6, 4, utf8_decode($mucoral_mat), 1, 0, 'C');

//estreñ y riesgo lesiom
$estreñ_mat  =" ";
$val = "SELECT estreñ_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$estreñ_mat  =$val_res['estreñ_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('ESTREÑIMIENTO'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($estreñ_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$estreñ_mat  =$val_resv['estreñ_mat'];
}
if($estreñ_mat=="SI"){$estreñ_mat="X";}
$pdf->Cell(6, 4, utf8_decode($estreñ_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$estreñ_mat=$val_resv['estreñ_mat'];
}
if($estreñ_mat=="SI"){$estreñ_mat="X";}
$pdf->Cell(6, 4, utf8_decode($estreñ_mat), 1, 0, 'C');
$rieslesion_mat=" ";
$pdf->Cell(79, 4, utf8_decode('RIESGO DE LESIÓN'), 1, 0, 'L');
$val = "SELECT rieslesion_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$rieslesion_mat=$val_res['rieslesion_mat'];
}
if($rieslesion_mat=="SI"){$rieslesion_mat="X";}
$pdf->Cell(6, 4, utf8_decode($rieslesion_mat), 1, 0, 'C');
$val = "SELECT rieslesion_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$rieslesion_mat=$val_res['rieslesion_mat'];
}
if($rieslesion_mat=="SI"){$rieslesion_mat="X";}
$pdf->Cell(6, 4, utf8_decode($rieslesion_mat), 1, 0, 'C');
$val = "SELECT rieslesion_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$rieslesion_mat=$val_res['rieslesion_mat'];
}
if($rieslesion_mat=="SI"){$rieslesion_mat="X";}
$pdf->Cell(6, 4, utf8_decode($rieslesion_mat), 1, 0, 'C');

//RIES ESTREÑ Y RIESLESIONPERIO
$riestreñ_mat  =" ";
$val = "SELECT riestreñ_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$riestreñ_mat  =$val_res['riestreñ_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('RIESGO DE ESTREÑIMIENTO'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($riestreñ_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$riestreñ_mat  =$val_resv['riestreñ_mat'];
}
if($riestreñ_mat=="SI"){$riestreñ_mat="X";}
$pdf->Cell(6, 4, utf8_decode($riestreñ_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$riestreñ_mat=$val_resv['riestreñ_mat'];
}
if($riestreñ_mat=="SI"){$riestreñ_mat="X";}
$pdf->Cell(6, 4, utf8_decode($riestreñ_mat), 1, 0, 'C');

$lesperio_mat=" ";
$pdf->Cell(79, 4, utf8_decode('RIESGO DE LESIÓN PERIOPERATORIA'), 1, 0, 'L');
$val = "SELECT lesperio_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$lesperio_mat=$val_res['lesperio_mat'];
}
if($lesperio_mat=="SI"){$lesperio_mat="X";}
$pdf->Cell(6, 4, utf8_decode($lesperio_mat), 1, 0, 'C');
$val = "SELECT lesperio_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$lesperio_mat=$val_res['lesperio_mat'];
}
if($lesperio_mat=="SI"){$lesperio_mat="X";}
$pdf->Cell(6, 4, utf8_decode($lesperio_mat), 1, 0, 'C');
$val = "SELECT lesperio_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$lesperio_mat=$val_res['lesperio_mat'];
}
if($lesperio_mat=="SI"){$lesperio_mat="X";}
$pdf->Cell(6, 4, utf8_decode($lesperio_mat), 1, 0, 'C');

//DEL INER GASEOSO Y RISG CAI
$detgas_mat  =" ";
$val = "SELECT detgas_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$detgas_mat  =$val_res['detgas_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('DETERIORO DEL INTERCAMBIO GASEOSO'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($detgas_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$detgas_mat  =$val_resv['detgas_mat'];
}
if($detgas_mat=="SI"){$detgas_mat="X";}
$pdf->Cell(6, 4, utf8_decode($detgas_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$detgas_mat=$val_resv['detgas_mat'];
}
if($detgas_mat=="SI"){$detgas_mat="X";}
$pdf->Cell(6, 4, utf8_decode($detgas_mat), 1, 0, 'C');

$riesgocai_mat=" ";
$pdf->Cell(79, 4, utf8_decode('RIESGO DE CAÍDAS'), 1, 0, 'L');
$val = "SELECT riesgocai_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$riesgocai_mat=$val_res['riesgocai_mat'];
}
if($riesgocai_mat=="SI"){$riesgocai_mat="X";}
$pdf->Cell(6, 4, utf8_decode($riesgocai_mat), 1, 0, 'C');
$val = "SELECT riesgocai_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$riesgocai_mat=$val_res['riesgocai_mat'];
}
if($riesgocai_mat=="SI"){$riesgocai_mat="X";}
$pdf->Cell(6, 4, utf8_decode($riesgocai_mat), 1, 0, 'C');
$val = "SELECT riesgocai_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$riesgocai_mat=$val_res['riesgocai_mat'];
}
if($riesgocai_mat=="SI"){$riesgocai_mat="X";}
$pdf->Cell(6, 4, utf8_decode($riesgocai_mat), 1, 0, 'C');

//DOMINIO 4 Y RIESGO DE TRAUMATISMO
$riestrau_mat=" ";
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',6);
$pdf->Cell(80, 4, utf8_decode('DOMINIO 4. ACTIVIDAD / REPOSO'), 1, 0, 'C');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'C');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'C');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',6);
$pdf->Cell(79, 4, utf8_decode('RIESGO DE TRAUMATISMO'), 1, 0, 'L');
$val = "SELECT riestrau_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$riestrau_mat=$val_res['riestrau_mat'];
}
if($riestrau_mat=="SI"){$riestrau_mat="X";}
$pdf->Cell(6, 4, utf8_decode($riestrau_mat), 1, 0, 'C');
$val = "SELECT riestrau_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$riestrau_mat=$val_res['riestrau_mat'];
}
if($riestrau_mat=="SI"){$riestrau_mat="X";}
$pdf->Cell(6, 4, utf8_decode($riestrau_mat), 1, 0, 'C');
$val = "SELECT riestrau_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$riestrau_mat=$val_res['riestrau_mat'];
}
if($riestrau_mat=="SI"){$riestrau_mat="X";}
$pdf->Cell(6, 4, utf8_decode($riestrau_mat), 1, 0, 'C');

//patron sueño y integridad cutanea
$detpasue_mat =" ";
$val = "SELECT detpasue_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$detpasue_mat  =$val_res['detpasue_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('DETERIORO DEL PATRÓN DE SUEÑO'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($detpasue_mat), 1, 0, 'C');

$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$detpasue_mat  =$val_resv['detpasue_mat'];
}
if($detpasue_mat=="SI"){$detpasue_mat="X";}
$pdf->Cell(6, 4, utf8_decode($detpasue_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$detpasue_mat=$val_resv['detpasue_mat'];
}
if($detpasue_mat=="SI"){$detpasue_mat="X";}
$pdf->Cell(6, 4, utf8_decode($detpasue_mat), 1, 0, 'C');

$intecutanea_mat=" ";
$pdf->Cell(79, 4, utf8_decode('RIESGO DE DETERÍORO DE LA INTEGRIDAD CUTÁNEA'), 1, 0, 'L');
$val = "SELECT intecutanea_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$intecutanea_mat=$val_res['intecutanea_mat'];
}
if($intecutanea_mat=="SI"){$intecutanea_mat="X";}
$pdf->Cell(6, 4, utf8_decode($intecutanea_mat), 1, 0, 'C');
$val = "SELECT intecutanea_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$intecutanea_mat=$val_res['intecutanea_mat'];
}
if($intecutanea_mat=="SI"){$intecutanea_mat="X";}
$pdf->Cell(6, 4, utf8_decode($intecutanea_mat), 1, 0, 'C');
$val = "SELECT intecutanea_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$intecutanea_mat=$val_res['intecutanea_mat'];
}
if($intecutanea_mat=="SI"){$intecutanea_mat="X";}
$pdf->Cell(6, 4, utf8_decode($intecutanea_mat), 1, 0, 'C');

//depri sueño y intregi tisular
$depri_mat  =" ";
$val = "SELECT depri_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$depri_mat  =$val_res['depri_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('DEPRIVACIÓN DEL SUEÑO / INSOMNIO'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($depri_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$depri_mat  =$val_resv['depri_mat'];
}
if($depri_mat=="SI"){$depri_mat="X";}
$pdf->Cell(6, 4, utf8_decode($depri_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$depri_mat=$val_resv['depri_mat'];
}
if($depri_mat=="SI"){$depri_mat="X";}
$pdf->Cell(6, 4, utf8_decode($depri_mat), 1, 0, 'C');

$intetis_mat=" ";
$pdf->Cell(79, 4, utf8_decode('DETERÍORO DE LA INTEGRIDAD TISULAR'), 1, 0, 'L');
$val = "SELECT intetis_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$intetis_mat=$val_res['intetis_mat'];
}
if($intetis_mat=="SI"){$intetis_mat="X";}
$pdf->Cell(6, 4, utf8_decode($intetis_mat), 1, 0, 'C');
$val = "SELECT intetis_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$intetis_mat=$val_res['intetis_mat'];
}
if($intetis_mat=="SI"){$intetis_mat="X";}
$pdf->Cell(6, 4, utf8_decode($intetis_mat), 1, 0, 'C');
$val = "SELECT intetis_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$intetis_mat=$val_res['intetis_mat'];
}
if($intetis_mat=="SI"){$intetis_mat="X";}
$pdf->Cell(6, 4, utf8_decode($intetis_mat), 1, 0, 'C');

//mov fisica y dentincion
$detmov_mat  =" ";
$val = "SELECT detmov_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$detmov_mat  =$val_res['detmov_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('DETERIORO DE LA MOVILIDAD FÍSICA'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($detmov_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$detmov_mat  =$val_resv['detmov_mat'];
}
if($detmov_mat=="SI"){$detmov_mat="X";}
$pdf->Cell(6, 4, utf8_decode($detmov_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$detmov_mat=$val_resv['detmov_mat'];
}
if($detmov_mat=="SI"){$detmov_mat="X";}
$pdf->Cell(6, 4, utf8_decode($detmov_mat), 1, 0, 'C');

$dentincion_mat=" ";
$pdf->Cell(79, 4, utf8_decode('DETERÍORO DE LA DENTICIÓN'), 1, 0, 'L');
$val = "SELECT dentincion_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$dentincion_mat=$val_res['dentincion_mat'];
}
if($dentincion_mat=="SI"){$dentincion_mat="X";}
$pdf->Cell(6, 4, utf8_decode($dentincion_mat), 1, 0, 'C');
$val = "SELECT dentincion_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$dentincion_mat=$val_res['dentincion_mat'];
}
if($dentincion_mat=="SI"){$dentincion_mat="X";}
$pdf->Cell(6, 4, utf8_decode($dentincion_mat), 1, 0, 'C');
$val = "SELECT dentincion_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$dentincion_mat=$val_res['dentincion_mat'];
}
if($dentincion_mat=="SI"){$dentincion_mat="X";}
$pdf->Cell(6, 4, utf8_decode($dentincion_mat), 1, 0, 'C');

//mov en cama y asfixia
$detmovcama_mat  =" ";
$val = "SELECT detmovcama_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$detmovcama_mat  =$val_res['detmovcama_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('DETERIORO DE LA MOVILIDAD EN LA CAMA'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($detmovcama_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$detmovcama_mat  =$val_resv['detmovcama_mat'];
}
if($detmovcama_mat=="SI"){$detmovcama_mat="X";}
$pdf->Cell(6, 4, utf8_decode($detmovcama_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$detmovcama_mat=$val_resv['detmovcama_mat'];
}
if($detmovcama_mat=="SI"){$detmovcama_mat="X";}
$pdf->Cell(6, 4, utf8_decode($detmovcama_mat), 1, 0, 'C');

$asfix_mat=$val_res['asfix_mat'];
$pdf->Cell(79, 4, utf8_decode('RIESGO DE ASFIXIA'), 1, 0, 'L');
$val = "SELECT asfix_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$asfix_mat=$val_res['asfix_mat'];
}
if($asfix_mat=="SI"){$asfix_mat="X";}
$pdf->Cell(6, 4, utf8_decode($asfix_mat), 1, 0, 'C');
$val = "SELECT asfix_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$asfix_mat=$val_res['asfix_mat'];
}
if($asfix_mat=="SI"){$asfix_mat="X";}
$pdf->Cell(6, 4, utf8_decode($asfix_mat), 1, 0, 'C');
$val = "SELECT asfix_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$asfix_mat=$val_res['asfix_mat'];
}
if($asfix_mat=="SI"){$asfix_mat="X";}
$pdf->Cell(6, 4, utf8_decode($asfix_mat), 1, 0, 'C');

//deambula y aspiracion
$detdeam_mat  =" ";
$val = "SELECT detdeam_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$detdeam_mat  =$val_res['detdeam_mat'];
}
if($detdeam_mat=="SI"){$detdeam_mat="X";}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('DETERIORO DE LA DEAMBULACIÓN'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($detdeam_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$detdeam_mat  =$val_resv['detdeam_mat'];
}
if($detdeam_mat=="SI"){$detdeam_mat="X";}
$pdf->Cell(6, 4, utf8_decode($detdeam_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$detdeam_mat=$val_resv['detdeam_mat'];
}
if($detdeam_mat=="SI"){$detdeam_mat="X";}
$pdf->Cell(6, 4, utf8_decode($detdeam_mat), 1, 0, 'C');

$riesasp_mat=" ";
$pdf->Cell(79, 4, utf8_decode('RIESGO DE ASPIRACIÓN'), 1, 0, 'L');
$val = "SELECT riesasp_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$riesasp_mat=$val_res['riesasp_mat'];
}
if($riesasp_mat=="SI"){$riesasp_mat="X";}
$pdf->Cell(6, 4, utf8_decode($riesasp_mat), 1, 0, 'C');
$val = "SELECT riesasp_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$riesasp_mat=$val_res['riesasp_mat'];
}
if($riesasp_mat=="SI"){$riesasp_mat="X";}
$pdf->Cell(6, 4, utf8_decode($riesasp_mat), 1, 0, 'C');
$val = "SELECT riesasp_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$riesasp_mat=$val_res['riesasp_mat'];
}
if($riesasp_mat=="SI"){$riesasp_mat="X";}
$pdf->Cell(6, 4, utf8_decode($riesasp_mat), 1, 0, 'C');

//acica y limpieza
$defaut_mat  =" ";
$val = "SELECT defaut_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$defaut_mat  =$val_res['defaut_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('DÉFICIT DE AUTOCUIDADO VESTIDO/ACICALAMIENTO'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($defaut_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$defaut_mat  =$val_resv['defaut_mat'];
}
if($defaut_mat=="SI"){$defaut_mat="X";}
$pdf->Cell(6, 4, utf8_decode($defaut_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$defaut_mat=$val_resv['defaut_mat'];
}
if($defaut_mat=="SI"){$defaut_mat="X";}
$pdf->Cell(6, 4, utf8_decode($defaut_mat), 1, 0, 'C');

$limvias_mat=" ";
$pdf->Cell(79, 4, utf8_decode('LIMPIEZA INEFECTIVA DE LAS VÍAS AÉREAS'), 1, 0, 'L');
$val = "SELECT limvias_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$limvias_mat=$val_res['limvias_mat'];
}
if($limvias_mat=="SI"){$limvias_mat="X";}
$pdf->Cell(6, 4, utf8_decode($limvias_mat), 1, 0, 'C');
$val = "SELECT limvias_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$limvias_mat=$val_res['limvias_mat'];
}
if($limvias_mat=="SI"){$limvias_mat="X";}
$pdf->Cell(6, 4, utf8_decode($limvias_mat), 1, 0, 'C');
$val = "SELECT limvias_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$limvias_mat=$val_res['limvias_mat'];
}
if($limvias_mat=="SI"){$limvias_mat="X";}
$pdf->Cell(6, 4, utf8_decode($limvias_mat), 1, 0, 'C');

//bañohigiene y neuro
$deficitbaño_mat  =" ";
$val = "SELECT deficitbaño_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$deficitbaño_mat  =$val_res['deficitbaño_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('DÉFICIT DE AUTOCUIDADO: BAÑO / HIGIENE'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($deficitbaño_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$deficitbaño_mat  =$val_resv['deficitbaño_mat'];
}
if($deficitbaño_mat=="SI"){$deficitbaño_mat="X";}
$pdf->Cell(6, 4, utf8_decode($deficitbaño_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$deficitbaño_mat=$val_resv['deficitbaño_mat'];
}
if($deficitbaño_mat=="SI"){$deficitbaño_mat="X";}
$pdf->Cell(6, 4, utf8_decode($deficitbaño_mat), 1, 0, 'C');

$neuroperi_mat=" ";
$pdf->Cell(79, 4, utf8_decode('RIESGO DE DISFUNCIÓN NEUROVASCULAR PERIFERICA'), 1, 0, 'L');
$val = "SELECT neuroperi_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$neuroperi_mat=$val_res['neuroperi_mat'];
}
if($neuroperi_mat=="SI"){$neuroperi_mat="X";}
$pdf->Cell(6, 4, utf8_decode($neuroperi_mat), 1, 0, 'C');
$val = "SELECT neuroperi_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$neuroperi_mat=$val_res['neuroperi_mat'];
}
if($neuroperi_mat=="SI"){$neuroperi_mat="X";}
$pdf->Cell(6, 4, utf8_decode($neuroperi_mat), 1, 0, 'C');
$val = "SELECT neuroperi_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$neuroperi_mat=$val_res['neuroperi_mat'];
}
if($neuroperi_mat=="SI"){$neuroperi_mat="X";}
$pdf->Cell(6, 4, utf8_decode($neuroperi_mat), 1, 0, 'C');

//ALIMEN Y VIOL DIRIGIDA A OTROS
$defialim_mat  =" ";
$val = "SELECT defialim_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$defialim_mat  =$val_res['defialim_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('DÉFICIT DE AUTOCUIDADO: ALIMENTACIÓN'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($defialim_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$defialim_mat  =$val_resv['defialim_mat'];
}
if($defialim_mat=="SI"){$defialim_mat="X";}
$pdf->Cell(6, 4, utf8_decode($defialim_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$defialim_mat=$val_resv['defialim_mat'];
}
if($defialim_mat=="SI"){$defialim_mat="X";}
$pdf->Cell(6, 4, utf8_decode($defialim_mat), 1, 0, 'C');

$violdir_mat=" ";
$pdf->Cell(79, 4, utf8_decode('RIESGO DE VIOLENCIA DIRIGIDA A OTROS'), 1, 0, 'L');
$val = "SELECT violdir_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$violdir_mat=$val_res['violdir_mat'];
}
if($violdir_mat=="SI"){$violdir_mat="X";}
$pdf->Cell(6, 4, utf8_decode($violdir_mat), 1, 0, 'C');
$val = "SELECT violdir_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$violdir_mat=$val_res['violdir_mat'];
}
if($violdir_mat=="SI"){$violdir_mat="X";}
$pdf->Cell(6, 4, utf8_decode($violdir_mat), 1, 0, 'C');
$val = "SELECT violdir_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$violdir_mat=$val_res['violdir_mat'];
}
if($violdir_mat=="SI"){$violdir_mat="X";}
$pdf->Cell(6, 4, utf8_decode($violdir_mat), 1, 0, 'C');

//WC Y AUTODIRIGIDA
$defiwc_mat  =" ";
$val = "SELECT defiwc_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$defiwc_mat  =$val_res['defiwc_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('DÉFICIT DE AUTOCUIDADO: USO DEL WC'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($defiwc_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$defiwc_mat  =$val_resv['defiwc_mat'];
}
if($defiwc_mat=="SI"){$defiwc_mat="X";}
$pdf->Cell(6, 4, utf8_decode($defiwc_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$defiwc_mat=$val_resv['defiwc_mat'];
}
if($defiwc_mat=="SI"){$defiwc_mat="X";}
$pdf->Cell(6, 4, utf8_decode($defiwc_mat), 1, 0, 'C');

$violauto_mat=" ";
$pdf->Cell(79, 4, utf8_decode('RIESGO DE VIOLENCIA AUTO DIRIGIDA'), 1, 0, 'L');
$val = "SELECT violauto_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$violauto_mat=$val_res['violauto_mat'];
}
if($violauto_mat=="SI"){$violauto_mat="X";}
$pdf->Cell(6, 4, utf8_decode($violauto_mat), 1, 0, 'C');
$val = "SELECT violauto_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$violauto_mat=$val_res['violauto_mat'];
}
if($violauto_mat=="SI"){$violauto_mat="X";}
$pdf->Cell(6, 4, utf8_decode($violauto_mat), 1, 0, 'C');
$val = "SELECT violauto_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$violauto_mat=$val_res['violauto_mat'];
}
if($violauto_mat=="SI"){$violauto_mat="X";}
$pdf->Cell(6, 4, utf8_decode($violauto_mat), 1, 0, 'C');

//FAT Y SUICIDIO
$fatiga_mat  =" ";
$val = "SELECT fatiga_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$fatiga_mat  =$val_res['fatiga_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('FATIGA'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($fatiga_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$fatiga_mat  =$val_resv['fatiga_mat'];
}
if($fatiga_mat=="SI"){$fatiga_mat="X";}
$pdf->Cell(6, 4, utf8_decode($fatiga_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$fatiga_mat=$val_resv['fatiga_mat'];
}
if($fatiga_mat=="SI"){$fatiga_mat="X";}
$pdf->Cell(6, 4, utf8_decode($fatiga_mat), 1, 0, 'C');

$riesuic_mat=" ";
$pdf->Cell(79, 4, utf8_decode('RIESGO DE SUICIDIO'), 1, 0, 'L');
$val = "SELECT riesuic_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$riesuic_mat=$val_res['riesuic_mat'];
}
if($riesuic_mat=="SI"){$riesuic_mat="X";}
$pdf->Cell(6, 4, utf8_decode($riesuic_mat), 1, 0, 'C');
$val = "SELECT riesuic_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$riesuic_mat=$val_res['riesuic_mat'];
}
if($riesuic_mat=="SI"){$riesuic_mat="X";}
$pdf->Cell(6, 4, utf8_decode($riesuic_mat), 1, 0, 'C');
$val = "SELECT riesuic_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$riesuic_mat=$val_res['riesuic_mat'];
}
if($riesuic_mat=="SI"){$riesuic_mat="X";}
$pdf->Cell(6, 4, utf8_decode($riesuic_mat), 1, 0, 'C');

//GASTO CAR Y INTOX
$discardiaco_mat  =" ";
$val = "SELECT discardiaco_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$discardiaco_mat  =$val_res['discardiaco_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('DISMINUCIÓN DEL GASTO CARDÍACO'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($discardiaco_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$discardiaco_mat  =$val_resv['discardiaco_mat'];
}
if($discardiaco_mat=="SI"){$discardiaco_mat="X";}
$pdf->Cell(6, 4, utf8_decode($discardiaco_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$discardiaco_mat=$val_resv['discardiaco_mat'];
}
if($discardiaco_mat=="SI"){$discardiaco_mat="X";}
$pdf->Cell(6, 4, utf8_decode($discardiaco_mat), 1, 0, 'C');

$pdf->Cell(79, 4, utf8_decode('RIESGO DE INTOXICACIÓN'), 1, 0, 'L');
$riesintox_mat =" ";
$val = "SELECT riesintox_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$riesintox_mat=$val_res['riesintox_mat'];
}
if($riesintox_mat=="SI"){$riesintox_mat="X";}
$pdf->Cell(6, 4, utf8_decode($riesintox_mat), 1, 0, 'C');
$val = "SELECT riesintox_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$riesintox_mat=$val_res['riesintox_mat'];
}
if($riesintox_mat=="SI"){$riesintox_mat="X";}
$pdf->Cell(6, 4, utf8_decode($riesintox_mat), 1, 0, 'C');
$val = "SELECT riesintox_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$riesintox_mat=$val_res['riesintox_mat'];
}
if($riesintox_mat=="SI"){$riesintox_mat="X";}
$pdf->Cell(6, 4, utf8_decode($riesintox_mat), 1, 0, 'C');

//RES ESP Y TEM CORP
$detres_mat =" ";
$val = "SELECT detres_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$detres_mat  =$val_res['detres_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('DETERIORO DE LA RESPIRACIÓN ESPONTÁNEA'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($detres_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$detres_mat  =$val_resv['detres_mat'];
}
if($detres_mat=="SI"){$detres_mat="X";}
$pdf->Cell(6, 4, utf8_decode($detres_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$detres_mat=$val_resv['detres_mat'];
}
if($detres_mat=="SI"){$detres_mat="X";}
$pdf->Cell(6, 4, utf8_decode($detres_mat), 1, 0, 'C');

$pdf->Cell(79, 4, utf8_decode('RIESGO DE DESEQUILIBRIO DE LA TEMPERATURA CORPORAL'), 1, 0, 'L');
$tempercorp_mat =" ";
$val = "SELECT tempercorp_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$tempercorp_mat=$val_res['tempercorp_mat'];
}
if($tempercorp_mat=="SI"){$tempercorp_mat="X";}
$pdf->Cell(6, 4, utf8_decode($tempercorp_mat), 1, 0, 'C');
$val = "SELECT tempercorp_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$tempercorp_mat=$val_res['tempercorp_mat'];
}
if($tempercorp_mat=="SI"){$tempercorp_mat="X";}
$pdf->Cell(6, 4, utf8_decode($tempercorp_mat), 1, 0, 'C');
$val = "SELECT tempercorp_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$tempercorp_mat=$val_res['tempercorp_mat'];
}
if($tempercorp_mat=="SI"){$tempercorp_mat="X";}
$pdf->Cell(6, 4, utf8_decode($tempercorp_mat), 1, 0, 'C');

//INEFICAZ Y INEFECTIVA
$patresine_mat =" ";
$val = "SELECT patresine_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$patresine_mat  =$val_res['patresine_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('PATRÓN RESPIRATORIO INEFICAZ'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($patresine_mat), 1, 0, 'C');

$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$patresine_mat  =$val_resv['patresine_mat'];
}
if($patresine_mat=="SI"){$patresine_mat="X";}
$pdf->Cell(6, 4, utf8_decode($patresine_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$patresine_mat=$val_resv['patresine_mat'];
}
if($patresine_mat=="SI"){$patresine_mat="X";}
$pdf->Cell(6, 4, utf8_decode($patresine_mat), 1, 0, 'C');

$pdf->Cell(79, 4, utf8_decode('TERMOREGULACIÓN INEFECTIVA'), 1, 0, 'L');
$termoine_mat =" ";
$val = "SELECT termoine_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$termoine_mat=$val_res['termoine_mat'];
}
if($termoine_mat=="SI"){$termoine_mat="X";}
$pdf->Cell(6, 4, utf8_decode($termoine_mat), 1, 0, 'C');
$val = "SELECT termoine_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$termoine_mat=$val_res['termoine_mat'];
}
if($termoine_mat=="SI"){$termoine_mat="X";}
$pdf->Cell(6, 4, utf8_decode($termoine_mat), 1, 0, 'C');
$val = "SELECT termoine_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$termoine_mat=$val_res['termoine_mat'];
}
if($termoine_mat=="SI"){$termoine_mat="X";}
$pdf->Cell(6, 4, utf8_decode($termoine_mat), 1, 0, 'C');

//DESTETE Y HIPOTERMIA
$resventi_mat =" ";
$val = "SELECT resventi_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$resventi_mat  =$val_res['resventi_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('RESPUESTA DISFUNCIONAL AL DESTETE DEL VENTILADOR'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($resventi_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$resventi_mat  =$val_resv['resventi_mat'];
}
if($resventi_mat=="SI"){$resventi_mat="X";}
$pdf->Cell(6, 4, utf8_decode($resventi_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$resventi_mat=$val_resv['resventi_mat'];
}
if($resventi_mat=="SI"){$resventi_mat="X";}
$pdf->Cell(6, 4, utf8_decode($resventi_mat), 1, 0, 'C');

$pdf->Cell(79, 4, utf8_decode('HIPOTERMIA'), 1, 0, 'L');
$hipo_mat =" ";
$val = "SELECT hipo_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$hipo_mat=$val_res['hipo_mat'];
}
if($hipo_mat=="SI"){$hipo_mat="X";}
$pdf->Cell(6, 4, utf8_decode($hipo_mat), 1, 0, 'C');
$val = "SELECT hipo_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$hipo_mat=$val_res['hipo_mat'];
}
if($hipo_mat=="SI"){$hipo_mat="X";}
$pdf->Cell(6, 4, utf8_decode($hipo_mat), 1, 0, 'C');
$val = "SELECT hipo_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$hipo_mat=$val_res['hipo_mat'];
}
if($hipo_mat=="SI"){$hipo_mat="X";}
$pdf->Cell(6, 4, utf8_decode($hipo_mat), 1, 0, 'C');

//PERFU E HIPERTERMIA
$pertinular_mat =" ";
$val = "SELECT pertinular_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$pertinular_mat  =$val_res['pertinular_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->MultiCell(80, 3, utf8_decode('PERFUSIÓN TINULAR INEFECTIVA (ESPEFICICAR EL TIPO: RENAL(R), CEREBRAL(C), CARDIOPULMONAR(CAR), GASTROINTESTINAL(G), PERIFÉRICA(P))'), 1, 'L');
$pdf->SetY(189);
$pdf->SetX(90);
$pdf->Cell(6, 9, utf8_decode($pertinular_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$pertinular_mat  =$val_resv['pertinular_mat'];
}
if($pertinular_mat=="SI"){$pertinular_mat="X";}
$pdf->Cell(6, 9, utf8_decode($pertinular_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$pertinular_mat=$val_resv['pertinular_mat'];
}
if($pertinular_mat=="SI"){$pertinular_mat="X";}
$pdf->Cell(6, 9, utf8_decode($pertinular_mat), 1, 0, 'C');


$pdf->Cell(79, 9, utf8_decode('HIPERTERMIA'), 1, 0, 'L');
$hiper_mat =" ";
$val = "SELECT hiper_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$hiper_mat=$val_res['hiper_mat'];
}
if($hiper_mat=="SI"){$hiper_mat="X";}
$pdf->Cell(6, 9, utf8_decode($hiper_mat), 1, 0, 'C');
$val = "SELECT hiper_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$hiper_mat=$val_res['hiper_mat'];
}
if($hiper_mat=="SI"){$hiper_mat="X";}
$pdf->Cell(6, 9, utf8_decode($hiper_mat), 1, 0, 'C');
$val = "SELECT hiper_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$hiper_mat=$val_res['hiper_mat'];
}
if($hiper_mat=="SI"){$hiper_mat="X";}
$pdf->Cell(6, 9, utf8_decode($hiper_mat), 1, 0, 'C');

//5 Y 12
$val = "SELECT resventi_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$resventi_mat  =$val_res['resventi_mat'];
}
$pdf->Ln(9);
$pdf->SetFont('Arial', 'B',6);
$pdf->Cell(80, 4, utf8_decode('DOMINIO 5. PERCEPCIÓN / COGNICIÓN'), 1, 0, 'C');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'L');
$pdf->Cell(79, 4, utf8_decode('DOMINIO 12. CONFORT'), 1, 0, 'C');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'L');


//trans y dolor
$transens_mat =" ";
$val = "SELECT transens_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$transens_mat  =$val_res['transens_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->MultiCell(80, 3, utf8_decode('TRANSTORNO DE LA PERCEPCIÓN SENSORIAL (ESPEFICICAR EL TIPO:
VISUAL(V), AUDITIVA(A), CINESTÉSICA(C), GUSTATIVA(G), TÁCTIL(T), OLFATORIA(O))'), 1, 'L');
$pdf->SetY(202);
$pdf->SetX(90);
$pdf->Cell(6, 9, utf8_decode($transens_mat), 1, 0, 'C');

$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$transens_mat  =$val_resv['transens_mat'];
}
if($transens_mat=="SI"){$transens_mat="X";}
$pdf->Cell(6, 9, utf8_decode($transens_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$transens_mat=$val_resv['transens_mat'];
}
if($transens_mat=="SI"){$transens_mat="X";}
$pdf->Cell(6, 9, utf8_decode($transens_mat), 1, 0, 'C');

$pdf->Cell(79, 9, utf8_decode('DOLOR AGUDO'), 1, 0, 'L');
$dolagudo_mat =" ";
$val = "SELECT dolagudo_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$dolagudo_mat=$val_res['dolagudo_mat'];
}
if($dolagudo_mat=="SI"){$dolagudo_mat="X";}
$pdf->Cell(6, 9, utf8_decode($dolagudo_mat), 1, 0, 'C');
$val = "SELECT dolagudo_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$dolagudo_mat=$val_res['dolagudo_mat'];
}
if($dolagudo_mat=="SI"){$dolagudo_mat="X";}
$pdf->Cell(6, 9, utf8_decode($dolagudo_mat), 1, 0, 'C');
$val = "SELECT dolagudo_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$dolagudo_mat=$val_res['dolagudo_mat'];
}
if($dolagudo_mat=="SI"){$dolagudo_mat="X";}
$pdf->Cell(6, 9, utf8_decode($dolagudo_mat), 1, 0, 'C');

//con defi y  cronico
$condef_mat =" ";
$val = "SELECT condef_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$condef_mat  =$val_res['condef_mat'];
}
$pdf->Ln(9);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('CONOCIMIENTOS DEFICIENTES'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($condef_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$condef_mat  =$val_resv['condef_mat'];
}
if($condef_mat=="SI"){$condef_mat="X";}
$pdf->Cell(6, 4, utf8_decode($condef_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$condef_mat=$val_resv['condef_mat'];
}
if($condef_mat=="SI"){$condef_mat="X";}
$pdf->Cell(6, 4, utf8_decode($condef_mat), 1, 0, 'C');

$pdf->Cell(79, 4, utf8_decode('DOLOR CRÓNICO'), 1, 0, 'L');
$dolcronico_mat =" ";
$val = "SELECT dolcronico_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$dolcronico_mat=$val_res['dolcronico_mat'];
}
if($dolcronico_mat=="SI"){$dolcronico_mat="X";}
$pdf->Cell(6, 4, utf8_decode($dolcronico_mat), 1, 0, 'C');
$val = "SELECT dolcronico_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$dolcronico_mat=$val_res['dolcronico_mat'];
}
if($dolcronico_mat=="SI"){$dolcronico_mat="X";}
$pdf->Cell(6, 4, utf8_decode($dolcronico_mat), 1, 0, 'C');
$val = "SELECT dolcronico_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$dolcronico_mat=$val_res['dolcronico_mat'];
}
if($dolcronico_mat=="SI"){$dolcronico_mat="X";}
$pdf->Cell(6, 4, utf8_decode($dolcronico_mat), 1, 0, 'C');

//conf aguda y nauseas
$confagu_mat =" ";
$val = "SELECT confagu_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$confagu_mat  =$val_res['confagu_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('CONFUSIÓN AGUDA'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($confagu_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$confagu_mat  =$val_resv['confagu_mat'];
}
if($confagu_mat=="SI"){$confagu_mat="X";}
$pdf->Cell(6, 4, utf8_decode($confagu_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$confagu_mat=$val_resv['confagu_mat'];
}
if($confagu_mat=="SI"){$confagu_mat="X";}
$pdf->Cell(6, 4, utf8_decode($confagu_mat), 1, 0, 'C');

$pdf->Cell(79, 4, utf8_decode('NÁUSEAS'), 1, 0, 'L');
$nauseas_mat =" ";
$val = "SELECT nauseas_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$nauseas_mat=$val_res['nauseas_mat'];
}
if($nauseas_mat=="SI"){$nauseas_mat="X";}
$pdf->Cell(6, 4, utf8_decode($nauseas_mat), 1, 0, 'C');
$val = "SELECT nauseas_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$nauseas_mat=$val_res['nauseas_mat'];
}
if($nauseas_mat=="SI"){$nauseas_mat="X";}
$pdf->Cell(6, 4, utf8_decode($nauseas_mat), 1, 0, 'C');
$val = "SELECT nauseas_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$nauseas_mat=$val_res['nauseas_mat'];
}
if($nauseas_mat=="SI"){$nauseas_mat="X";}
$pdf->Cell(6, 4, utf8_decode($nauseas_mat), 1, 0, 'C');

//conf cronica y aislamiento soc
$confcro_mat =" ";
$val = "SELECT confcro_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$confcro_mat  =$val_res['confcro_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('CONFUSIÓN CRÓNICA'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($confcro_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$confcro_mat  =$val_resv['confcro_mat'];
}
if($confcro_mat=="SI"){$confcro_mat="X";}
$pdf->Cell(6, 4, utf8_decode($confcro_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$confcro_mat=$val_resv['confcro_mat'];
}
if($confcro_mat=="SI"){$confcro_mat="X";}
$pdf->Cell(6, 4, utf8_decode($confcro_mat), 1, 0, 'C');

$pdf->Cell(79, 4, utf8_decode('AISLAMIENTO SOCIAL'), 1, 0, 'L');
$aislasoc_mat =" ";
$val = "SELECT aislasoc_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$aislasoc_mat=$val_res['aislasoc_mat'];
}
if($aislasoc_mat=="SI"){$aislasoc_mat="X";}
$pdf->Cell(6, 4, utf8_decode($aislasoc_mat), 1, 0, 'C');
$val = "SELECT aislasoc_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$aislasoc_mat=$val_res['aislasoc_mat'];
}
if($aislasoc_mat=="SI"){$aislasoc_mat="X";}
$pdf->Cell(6, 4, utf8_decode($aislasoc_mat), 1, 0, 'C');
$val = "SELECT aislasoc_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$aislasoc_mat=$val_res['aislasoc_mat'];
}
if($aislasoc_mat=="SI"){$aislasoc_mat="X";}
$pdf->Cell(6, 4, utf8_decode($aislasoc_mat), 1, 0, 'C');

//det memoria y dom 13
$detmem_mat =" ";
$val = "SELECT detmem_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$detmem_mat  =$val_res['detmem_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('DETERIORO DE LA MEMORIA'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($detmem_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$detmem_mat  =$val_resv['detmem_mat'];
}
if($detmem_mat=="SI"){$detmem_mat="X";}
$pdf->Cell(6, 4, utf8_decode($detmem_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$detmem_mat=$val_resv['detmem_mat'];
}
if($detmem_mat=="SI"){$detmem_mat="X";}
$pdf->Cell(6, 4, utf8_decode($detmem_mat), 1, 0, 'C');
$pdf->SetFont('Arial', 'B',6);
$pdf->Cell(79, 4, utf8_decode('DOMINIO 13. CRECIMIENTO / DESARROLLO'), 1, 0, 'C');
$pdf->SetFont('Arial', '',6);
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'L');

//PENSAMIENTO Y INCAPACIDAD
$propen_mat =" ";
$val = "SELECT propen_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$propen_mat  =$val_res['propen_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('TRANSTORNO DE LOS PROCESOS DE PENSAMIENTO'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($propen_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$propen_mat  =$val_resv['propen_mat'];
}
if($propen_mat=="SI"){$propen_mat="X";}
$pdf->Cell(6, 4, utf8_decode($propen_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$propen_mat=$val_resv['propen_mat'];
}
if($propen_mat=="SI"){$propen_mat="X";}
$pdf->Cell(6, 4, utf8_decode($propen_mat), 1, 0, 'C');

$pdf->Cell(79, 4, utf8_decode('INCAPACIDAD DEL ADULTO PARA MANTENER SU DESARROLLO'), 1, 0, 'L');
$incapadulto_mat =" ";
$val = "SELECT incapadulto_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$incapadulto_mat=$val_res['incapadulto_mat'];
}
if($incapadulto_mat=="SI"){$incapadulto_mat="X";}
$pdf->Cell(6, 4, utf8_decode($incapadulto_mat), 1, 0, 'C');
$val = "SELECT incapadulto_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$incapadulto_mat=$val_res['incapadulto_mat'];
}
if($incapadulto_mat=="SI"){$incapadulto_mat="X";}
$pdf->Cell(6, 4, utf8_decode($incapadulto_mat), 1, 0, 'C');
$val = "SELECT incapadulto_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$incapadulto_mat=$val_res['incapadulto_mat'];
}
if($incapadulto_mat=="SI"){$incapadulto_mat="X";}
$pdf->Cell(6, 4, utf8_decode($incapadulto_mat), 1, 0, 'C');

//VERBAL 
$comver_mat =" ";
$val = "SELECT comver_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$comver_mat  =$val_res['comver_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('DETERIORO DE LA COMUNICACIÓN VERBAL'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($comver_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$comver_mat  =$val_resv['comver_mat'];
}
if($comver_mat=="SI"){$comver_mat="X";}
$pdf->Cell(6, 4, utf8_decode($comver_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$comver_mat=$val_resv['comver_mat'];
}
if($comver_mat=="SI"){$comver_mat="X";}
$pdf->Cell(6, 4, utf8_decode($comver_mat), 1, 0, 'C');

//DOM 6
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',6);
$pdf->Cell(80, 4, utf8_decode('DOMINIO 6. AUTOPERCEPCIÓN'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'L');
$otrodiag =" ";
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$otrodiag=$val_resv['otrodiag'];
}
$pdf->Cell(28, 6, utf8_decode('OTROS DIAGNÓSTICOS'), 1, 0, 'L');
$pdf->SetFont('Arial', '',6);
$pdf->Cell(23, 6, utf8_decode($otrodiag), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$otrodiag=$val_resv['otrodiag'];
}
$pdf->Cell(23, 6, utf8_decode($otrodiag), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$otrodiag=$val_resv['otrodiag'];
}
$pdf->Cell(23, 6, utf8_decode($otrodiag), 1, 0, 'C');

//iden per
$idenper_mat =" ";
$val = "SELECT idenper_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$idenper_mat  =$val_res['idenper_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('TRANSTORNO DE LA IDENTIDAD PERSONAL'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($idenper_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$idenper_mat  =$val_resv['idenper_mat'];
}
if($idenper_mat=="SI"){$idenper_mat="X";}
$pdf->Cell(6, 4, utf8_decode($idenper_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$idenper_mat=$val_resv['idenper_mat'];
}
if($idenper_mat=="SI"){$idenper_mat="X";}
$pdf->Cell(6, 4, utf8_decode($idenper_mat), 1, 0, 'C');

//desesperanza
$dperanza_mat =" ";
$val = "SELECT dperanza_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$dperanza_mat  =$val_res['dperanza_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('DESESPERANZA'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($dperanza_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$dperanza_mat  =$val_resv['dperanza_mat'];
}
if($dperanza_mat=="SI"){$dperanza_mat="X";}
$pdf->Cell(6, 4, utf8_decode($dperanza_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$dperanza_mat=$val_resv['dperanza_mat'];
}
if($dperanza_mat=="SI"){$dperanza_mat="X";}
$pdf->Cell(6, 4, utf8_decode($dperanza_mat), 1, 0, 'C');

//riesgo de soledad
$riesgsol_mat =" ";
$val = "SELECT riesgsol_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$riesgsol_mat  =$val_res['riesgsol_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('RIESGO DE SOLEDAD'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($riesgsol_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$riesgsol_mat  =$val_resv['riesgsol_mat'];
}
if($riesgsol_mat=="SI"){$riesgsol_mat="X";}
$pdf->Cell(6, 4, utf8_decode($riesgsol_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$riesgsol_mat=$val_resv['riesgsol_mat'];
}
if($riesgsol_mat=="SI"){$riesgsol_mat="X";}
$pdf->Cell(6, 4, utf8_decode($riesgsol_mat), 1, 0, 'C');

//situacional
$bajaauto_mat =" ";
$val = "SELECT bajaauto_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$bajaauto_mat  =$val_res['bajaauto_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('BAJA AUTOESTIMA SITUACIONAL'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($bajaauto_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$bajaauto_mat  =$val_resv['bajaauto_mat'];
}
if($bajaauto_mat=="SI"){$bajaauto_mat="X";}
$pdf->Cell(6, 4, utf8_decode($bajaauto_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$bajaauto_mat=$val_resv['bajaauto_mat'];
}
if($bajaauto_mat=="SI"){$bajaauto_mat="X";}
$pdf->Cell(6, 4, utf8_decode($bajaauto_mat), 1, 0, 'C');

//img corp
$imgcor_mat =" ";
$val = "SELECT imgcor_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$imgcor_mat  =$val_res['imgcor_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('TRANSTORNO DE LA IMAGEN CORPORAL'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($imgcor_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$imgcor_mat  =$val_resv['imgcor_mat'];
}
if($imgcor_mat=="SI"){$imgcor_mat="X";}
$pdf->Cell(6, 4, utf8_decode($imgcor_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$imgcor_mat=$val_resv['imgcor_mat'];
}
if($imgcor_mat=="SI"){$imgcor_mat="X";}
$pdf->Cell(6, 4, utf8_decode($imgcor_mat), 1, 0, 'C');

//dom 7
$val = "SELECT imgcor_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$imgcor_mat  =$val_res['imgcor_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',6);
$pdf->Cell(80, 4, utf8_decode('DOMINIO 7. ROL / RELACIONES'), 1, 0, 'L');
$pdf->SetFont('Arial', '',6);
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode(''), 1, 0, 'L');

//cansancio
$desemrol_mat =" ";
$val = "SELECT desemrol_mat FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='MATUTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
$desemrol_mat  =$val_res['desemrol_mat'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6);
$pdf->Cell(80, 4, utf8_decode('CANSANCIO EN EL DESEMPEÑO DEL ROL DE CUIDADOR'), 1, 0, 'L');
$pdf->Cell(6, 4, utf8_decode($desemrol_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='VESPERTINO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$desemrol_mat  =$val_resv['desemrol_mat'];
}
if($desemrol_mat=="SI"){$desemrol_mat="X";}
$pdf->Cell(6, 4, utf8_decode($desemrol_mat), 1, 0, 'C');
$valv = "SELECT * FROM enf_reg_clin  WHERE id_atencion=$id_atencion AND turno='NOCTURNO' ORDER BY id_clinreg DESC LIMIT 1 ";
$val_rv = $conexion->query($valv);
while ($val_resv = $val_rv->fetch_assoc()) {
$desemrol_mat=$val_resv['desemrol_mat'];
}
if($desemrol_mat=="SI"){$desemrol_mat="X";}
$pdf->Cell(6, 4, utf8_decode($desemrol_mat), 1, 0, 'C');
*/


$pdf->Ln(29);

$notaenf =" ";

$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(195, 5, utf8_decode('Nota de enfermería (Turno matutino)'), 0, 0, 'L');
$pdf->Ln(4);
$pdf->SetFont('Arial', '',8);
$valv = "SELECT * FROM nota_enf_hosp  WHERE turno='MATUTINO' and id_atencion=$id_atencion and fecha='$fechar' order by id_nota_enf ASC limit 6";
$val_rv = $conexion->query($valv);

  
while ($val_resv = $val_rv->fetch_assoc()) {
   
$notaenf=$val_resv['notaenf'];
$pdf->MultiCell(195, 5, utf8_decode($notaenf), 0,'J');

}

$notaenf =" ";
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(195, 5, utf8_decode('Nota de enfermería (Turno vespertino)'), 0, 0, 'L');
$pdf->Ln(4);
$pdf->SetFont('Arial', '',8);
$ve = "SELECT * FROM nota_enf_hosp WHERE turno='VESPERTINO' and id_atencion=$id_atencion and fecha='$fechar' order by id_nota_enf ASC limit 6";
$v = $conexion->query($ve);
while ($resv = $v->fetch_assoc()) {
$notaenf=$resv['notaenf'];
$pdf->MultiCell(195, 5, utf8_decode($notaenf), 0,'J');
}

$notaenf =" ";
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(195, 5, utf8_decode('Nota de enfermería (Turno nocturno)'), 0, 0, 'L');
$pdf->Ln(4);
$pdf->SetFont('Arial', '',8);
$noc = "SELECT * FROM nota_enf_hosp  WHERE turno='NOCTURNO' and id_atencion=$id_atencion and fecha='$fechar' order by id_nota_enf ASC limit 6";
$nocturn = $conexion->query($noc);
while ($res_nocturno = $nocturn->fetch_assoc()) {
$notaenf=$res_nocturno['notaenf'];
$pdf->MultiCell(195, 5, utf8_decode($notaenf), 0,'J');
}


    $id_med = " ";
    $nom = " ";
    $app = " ";
    $apm = " ";
    $pre = " ";
    $ced_p = " ";
    $cargp = " ";
   $firma ="";
    $sql_med_id = "SELECT * FROM enf_reg_clin WHERE turno='MATUTINO' AND id_atencion = $id_atencion  ORDER by id_clinreg DESC LIMIT 1";


    $result_med_id = $conexion->query($sql_med_id);

    while ($row_med_id = $result_med_id->fetch_assoc()) {
      $id_med = $row_med_id['id_usua'];
      $id_reg_clin=$row_med_id['id_clinreg'];  // TIENE QUE ENCONTRAR EL id_clinreg ya que si no encuentra no pasara NO SIRVE CON EL id_med
    }

/* validacion para que si encuentra la firma la ponga */
    if (isset($id_reg_clin)) {
      $sql_enf = "SELECT * FROM reg_usuarios WHERE id_usua = $id_med";
    $result_enf = $conexion->query($sql_enf);

    while ($row_enf = $result_enf->fetch_assoc()) {
      $nom = $row_enf['nombre'];
      $app = $row_enf['papell'];
      $apm = $row_enf['sapell'];
      $pre = $row_enf['pre'];
  $firma = $row_enf['firma'];
      $ced_p = $row_enf['cedp'];
      $cargp = $row_enf['cargp'];
    }
      $pdf->SetY(-53);

      $pdf->SetFont('Arial', 'B', 8);
     $pdf->Image('../../imgfirma/' . $firma, 25, 248, 15);
      $pdf->Ln(12);
      $pdf->SetX(10);
      $pdf->Cell(190, 4, utf8_decode($pre . '. ' . $app . ' ' . $apm . ' ' . $nom), 0, 0, 'L');
      $pdf->Ln(4);
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->SetX(10);
      $pdf->Cell(190, 4, utf8_decode($cargp . ' ' .'CÉD. PROF. ' . $ced_p), 0, 0, 'L');

      $pdf->Ln(4);
      $pdf->SetX(10);
      $pdf->Cell(190, 4, utf8_decode('Nombre y firma de enfermería matutino'), 0, 0, 'L');
    }else{/* si no encuentra la firma los pone en vacio */
$firma =null;
$nom = " ";
      $app =" ";
      $apm =" ";
      $pre =" ";
  
      $ced_p =" ";
      $cargp =" ";

$pdf->SetY(-53);

      $pdf->SetFont('Arial', 'B', 8);
     //$pdf->Image('../../imgfirma/' . $firma, 25, 240, 15);
      $pdf->Ln(12);
      $pdf->SetX(10);
      $pdf->Cell(190, 4, utf8_decode($pre . '. ' . $app . ' ' . $apm . ' ' . $nom), 0, 0, 'L');
      $pdf->Ln(4);
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->SetX(10);
      $pdf->Cell(190, 4, utf8_decode($cargp . ' ' .'CÉD. PROF. ' . $ced_p), 0, 0, 'L');

      $pdf->Ln(4);
      $pdf->SetX(10);
      $pdf->Cell(190, 4, utf8_decode('Nombre y firma de enfermería matutino'), 0, 0, 'L');
    }

   /* termina la validacion */ 

$firma =" ";
//VESPERTINO FIRMA
      $sql_med_id = "SELECT * FROM enf_reg_clin WHERE turno='VESPERTINO' AND id_atencion = $id_atencion  ORDER by id_clinreg DESC LIMIT 1";

    $result_med_id = $conexion->query($sql_med_id);

    while ($row_med_id = $result_med_id->fetch_assoc()) {
      $id_med = $row_med_id['id_usua'];
      $id_reg_clin=$row_med_id['id_clinreg'];
    }
    /* validacion para que si encuentra la firma la ponga */
    if (isset($id_reg_clin)) {
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

  $pdf->SetY(-53);

      $pdf->SetFont('Arial', 'B', 8);
     $pdf->Image('../../imgfirma/' . $firma, 95, 248, 15);
      $pdf->Ln(12);
      $pdf->SetX(80);
      $pdf->Cell(190, 4, utf8_decode($pre . '. ' . $app . ' ' . $apm . ' ' . $nom), 0, 0, 'L');
      $pdf->Ln(4);
      $pdf->SetFont('Arial', 'B', 8);
       $pdf->SetX(80);
      $pdf->Cell(190, 4, utf8_decode($cargp . ' ' .'CÉD. PROF. ' . $ced_p), 0, 0, 'L');

      $pdf->Ln(4);
          $pdf->SetX(80);
      $pdf->Cell(190, 4, utf8_decode('Nombre y firma de enfermería vespertino'), 0, 0, 'L');
}else{
    
  $nom = " ";
      $app =" ";
      $apm =" ";
      $pre =" ";
$firma= null;
      $ced_p =" ";
      $cargp =" ";

$pdf->SetY(-53);

      $pdf->SetFont('Arial', 'B', 8);
      //$pdf->Image('../../imgfirma/' . $firma, 98, 228, 15);
      $pdf->Ln(12);
      $pdf->SetX(80);
      $pdf->Cell(190, 4, utf8_decode($pre . '. ' . $app . ' ' . $apm), 0, 0, 'L');
      $pdf->Ln(4);
      $pdf->SetFont('Arial', 'B', 8);
       $pdf->SetX(80);
      $pdf->Cell(190, 4, utf8_decode($cargp . ' ' .'CÉD. PROF. ' . $ced_p), 0, 0, 'L');

      $pdf->Ln(4);
          $pdf->SetX(80);
      $pdf->Cell(190, 4, utf8_decode('Nombre y firma de enfermería vespertino'), 0, 0, 'L');
}
//turno nocturno
$firma =" ";
      //VESPERTINO FIRMA
      $sql_med_id = "SELECT * FROM enf_reg_clin WHERE turno='NOCTURNO' AND id_atencion = $id_atencion  ORDER by id_clinreg DESC LIMIT 1";

    $result_med_id = $conexion->query($sql_med_id);

    while ($row_med_id = $result_med_id->fetch_assoc()) {
      $id_med = $row_med_id['id_usua'];
      $id_reg_clin=$row_med_id['id_clinreg'];
    }

if (isset($id_reg_clin)) {
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

  $pdf->SetY(-53);

      $pdf->SetFont('Arial', 'B', 8);
 $pdf->Image('../../imgfirma/' . $firma, 165, 248, 15);
      $pdf->Ln(12);
      $pdf->SetX(150);
      $pdf->Cell(190, 4, utf8_decode($pre . '. ' . $app . ' ' . $apm), 0, 0, 'L');
      $pdf->Ln(4);
      $pdf->SetFont('Arial', 'B', 8);
       $pdf->SetX(150);
      $pdf->Cell(190, 4, utf8_decode($cargp . ' ' .'CÉD. PROF. ' . $ced_p), 0, 0, 'L');

      $pdf->Ln(4);
          $pdf->SetX(150);
      $pdf->Cell(190, 4, utf8_decode('Nombre y firma de enfermería nocturno'), 0, 0, 'L');
}else{
$firma =null;
   $nom = " ";
      $app =" ";
      $apm =" ";
      $pre =" ";
     
      $ced_p =" ";
      $cargp =" ";

$pdf->SetY(-53);

      $pdf->SetFont('Arial', 'B', 8);
     $pdf->Image('../../imgfirma/' . $firma, 165, 240, 15);

      $pdf->Ln(12);
      $pdf->SetX(150);
      $pdf->Cell(190, 4, utf8_decode($pre . '. ' . $app . ' ' . $apm), 0, 0, 'L');
      $pdf->Ln(4);
      $pdf->SetFont('Arial', 'B', 6);
       $pdf->SetX(150);
      $pdf->Cell(190, 4, utf8_decode($cargp . ' ' .'CÉD. PROF. ' . $ced_p), 0, 0, 'L');

      $pdf->Ln(4);
          $pdf->SetX(150);
      $pdf->Cell(190, 4, utf8_decode('Nombre y firma de enfermería nocturno'), 0, 0, 'L');
}






      /*$pdf->Ln(160);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(195, 6, utf8_decode('ESCALAS DE VALORACIÓN PARA CUIDADOS ESPECÍFICOS DE SEGURIDAD Y PROTECCIÓN'), 0, 0, 'C');
$pdf->Ln(8);

//MEDICAMENTOS MEDICAMENTOS MEDICAMENTOS MEDICAMENTOS MEDICAMENTOS MEDICAMENTOS


$pdf->Cell(93,5, utf8_decode('MEDICAMENTOS'),1,0,'C');
$pdf->Cell(20,5, utf8_decode('DOSIS'),1,0,'C');
$pdf->Cell(20,5, utf8_decode('VIA'),1,0,'C');
$pdf->Cell(35,5, utf8_decode('FRECUENCIA'),1,0,'C');
$pdf->Cell(27,5, utf8_decode('HORARIO'),1,0,'C');
$cis = $conexion->query("select * from medica_enf where id_atencion=$id_atencion ORDER BY id_med_reg") or die($conexion->error);
while ($cis_s = $cis->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 8);
$pdf->Ln(5);
$pdf->Cell(93,5, utf8_decode($cis_s['medicam_mat']),1,0,'C');
$pdf->Cell(20,5, utf8_decode($cis_s['dosis_mat']),1,0,'C');
$pdf->Cell(20,5, utf8_decode($cis_s['via_mat']),1,0,'C');
$pdf->Cell(35,5, utf8_decode($cis_s['frec_mat']),1,0,'C');
$pdf->Cell(27,5, utf8_decode($cis_s['hora_mat']),1,0,'C');
}*/



    
 $pdf->Output();
