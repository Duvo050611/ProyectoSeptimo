<?php
require '../../fpdf/fpdf.php';
include '../../conexionbd.php';
$id_med = @$_GET['id_med'];
$id_exp = @$_GET['id_exp'];
$fecha_mat = @$_GET['fechar'];
$id_atencion = @$_GET['id_atencion'];

mysqli_set_charset($conexion, "utf8");

class PDF extends FPDF
{
  function Header()
  {
    $id = @$_GET['id'];
    $id_med = @$_GET['id_med'];
    include '../../conexionbd.php';

    $id_atencion = @$_GET['id_atencion'];;

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
}

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
}


$sql_ing = "SELECT * FROM dat_ingreso  where Id_exp = $id_exp";
$result_ing = $conexion->query($sql_ing);
while ($row_ing = $result_ing->fetch_assoc()) {
$especialidad = $row_ing['especialidad'];
  $fecha = $row_ing['fecha'];
  $area= $row_ing['area'];
  $tipo_a= $row_ing['tipo_a'];

}

$sql_camas = "SELECT * FROM cat_camas  where id_atencion = $id_atencion";
$result_camas = $conexion->query($sql_camas);
while ($row_camas = $result_camas->fetch_assoc()) {

  $num_cama = $row_camas['num_cama'];
}

function calculaedad($fechanacimiento)
{
  list($ano, $mes, $dia) = explode("-", $fechanacimiento);
  $ano_diferencia  = date("Y") - $ano;
  $mes_diferencia = date("m") - $mes;
  $dia_diferencia   = date("d") - $dia;
  if ($dia_diferencia < 0 || $mes_diferencia < 0)
    $ano_diferencia--;
  return $ano_diferencia;
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

$pdf = new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetX(55);
$pdf->Cell(99, 5, utf8_decode('MEDICAMENTOS DE REGISTRO CLÍNICO DE ENFERMERÍA DEL ÁREA QUIRÚRGICA'), 0, 0, 'C');
$pdf->Ln(4);
$pdf->SetX(166);
//date_default_timezone_set('America/Guatemala');
$date = date("d/m/Y H:i:s");
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(39, 5, utf8_decode('FECHA: '.$date), 0, 1, 'L');


$pdf->SetFont('Arial', '', 7);
$pdf->Ln(0);
$date = date_create($fecha);
$pdf->Cell(110, 5, utf8_decode('FECHA DE INGRESO AL HOSPITAL: '.date_format($date, "d-m-Y H:i:s")),1, 'L');
$sql_q = "SELECT * from enf_quirurgico where id_atencion=$id_atencion and fecha = '$fecha_mat'";
$result_q = $conexion->query($sql_q); 
while ($row_q = $result_q->fetch_assoc()) {
  $fecha_quir = $row_q['fecha_quir'];
} 

$date2 = date_create($fecha_quir);
$pdf->Cell(80, 5, utf8_decode('FECHA DE REGISTRO DE HOJA: '.date_format($date2, "d-m-Y")),1, 'L');
$pdf->Ln(5);
$pdf->Cell(110, 5, utf8_decode('NOMBRE DEL PACIENTE: '.$papell . ' ' . $sapell . ' ' . $nom_pac), 1, 'L');
$pdf->Cell(80, 5, utf8_decode('AREA: '.$area),1, 'L');
$pdf->Ln(5);
$nac=date_create($fecnac);
$pdf->Cell(65, 5,'FECHA DE NACIMIENTO: '. date_format($nac,"d-m-Y"), 1, 'L');
$pdf->Cell(75,5, utf8_decode('GRUPO SANGUINEO Y RH: '.$tip_san),1,'L');
$pdf->Cell(50,5, utf8_decode('HABITACIÓN: '.$num_cama),1,'L');
$pdf->Ln(5);

$pdf->Cell(50, 5, 'TIEMPO ESTANCIA: '.$estancia . ' DIAS', 1, 'L');
$pdf->Cell(30, 5, 'EDAD: '. calculaedad($fecnac) .utf8_decode( ' AÑOS'), 1, 'L');

 $peso="";
 
$sql_vit = "SELECT peso from dat_hclinica where Id_exp=$id_exp ORDER by peso DESC LIMIT 1";
$result_vit = $conexion->query($sql_vit); 
while ($row_vit = $result_vit->fetch_assoc()) {
  $peso = $row_vit['peso'];
} 
$talla="";
$sql_vitt = "SELECT talla from dat_hclinica where Id_exp=$id_exp ORDER by talla DESC LIMIT 1";
$result_vitt = $conexion->query($sql_vitt); 
while ($row_vitt = $result_vitt->fetch_assoc()) {
  $talla = $row_vitt['talla'];
} 
$pdf->Cell(30,5,'PESO: '.$peso,1,'L');
$pdf->Cell(30,5,'TALLA: '.$talla,1,'L');
$pdf->Cell(50,5,'GENERO: '.$sexo,1,'L');
$pdf->Ln(5);

$motivo_atn="";
$sql_mott = "SELECT diagprob_i from dat_not_ingreso where id_atencion=$id_atencion ORDER by id_not_ingreso DESC LIMIT 1";
$result_mott = $conexion->query($sql_mott);                                                                                    while ($row_mott = $result_mott->fetch_assoc()) {
  $motivo_atn=$row_mott['diagprob_i'];
}

$pdf->Cell(110,5, utf8_decode('DIAGNÓSTICO MÉDICO: '.$motivo_atn),1,'L');

$sql_edo = "SELECT edo_salud,alergias from dat_ingreso where id_atencion=$id_atencion ORDER by edo_salud DESC LIMIT 1";
$result_edo = $conexion->query($sql_edo);                                                                                    while ($row_edo = $result_edo->fetch_assoc()) {
  $edo_salud=$row_edo['edo_salud'];
  $alergias=$row_edo['alergias'];
}
$pdf->Cell(80,5, utf8_decode('ESTADO DE CONCIENCIA: '.$edo_salud),1,'L');
$pdf->Ln(5);
$pdf->Cell(50,5, utf8_decode('NO. EXPEDIENTE: '.$Id_exp),1,'L');
$sql_aseg = "SELECT aseg from dat_financieros where id_atencion =$id_atencion ORDER BY fecha DESC limit 1";
  $result_aseg = $conexion->query($sql_aseg);
  while ($row_aseg = $result_aseg->fetch_assoc()) {
 $aseg= $row_aseg['aseg'];
}                                                                     
$pdf->Cell(60,5, utf8_decode('SEGURO: '.$aseg),1,'L');
$pdf->Cell(40,5, utf8_decode('ALERGIAS: '. $alergias),1,'L');
$pdf->Cell(40,5, utf8_decode('SERVICIO: '. $tipo_a),1,'L');

$pdf->SetFont('Arial', 'B', 10);
$pdf->Ln(10);
$pdf->Cell(190,5, utf8_decode('MEDICAMENTOS'),1,0,'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(35,5, utf8_decode('FECHA'),1,0,'C');
$pdf->Cell(53,5, utf8_decode('MEDICAMENTOS'),1,0,'C');
$pdf->Cell(20,5, utf8_decode('DOSIS'),1,0,'C');
$pdf->Cell(20,5, utf8_decode('VIA'),1,0,'C');
$pdf->Cell(35,5, utf8_decode('FRECUENCIA'),1,0,'C');
$pdf->Cell(27,5, utf8_decode('CANTIDAD CEYE'),1,0,'C');
$cis = $conexion->query("select * from medica_enf where id_atencion=$id_atencion and material ='No' and fecha_mat = '$fecha_mat' ORDER BY id_med_reg") or die($conexion->error);
while ($cis_s = $cis->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 8);
$pdf->Ln(5);
$fn=$cis_s['fecha'];
$fcis=date_create($fn);
$pdf->Cell(35,5, date_format($fcis,"d-m-Y h:i:s"),1,0,'C');
$pdf->Cell(53,5, utf8_decode($cis_s['mat_nom']),1,0,'C');
$pdf->Cell(20,5, utf8_decode($cis_s['dosis']),1,0,'C');
$pdf->Cell(20,5, utf8_decode($cis_s['via']),1,0,'C');
$pdf->Cell(35,5, utf8_decode($cis_s['frecuencia']),1,0,'C');
$pdf->Cell(27,5, utf8_decode($cis_s['cantidad']),1,0,'C');
}

$pdf->SetFont('Arial', 'B', 10);
$pdf->Ln(10);
$pdf->Cell(190,5, utf8_decode('MATERIALES'),1,0,'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(35,5, utf8_decode('FECHA'),1,0,'C');
$pdf->Cell(53,5, utf8_decode('MEDICAMENTOS'),1,0,'C');
$pdf->Cell(20,5, utf8_decode('DOSIS'),1,0,'C');
$pdf->Cell(20,5, utf8_decode('VIA'),1,0,'C');
$pdf->Cell(35,5, utf8_decode('OTROS'),1,0,'C');
$pdf->Cell(27,5, utf8_decode('CANTIDAD CEYE'),1,0,'C');
$cis = $conexion->query("select * from medica_enf where id_atencion=$id_atencion and material ='Si' and fecha_mat = '$fecha_mat' ORDER BY id_med_reg") or die($conexion->error);
while ($cis_s = $cis->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 8);
$pdf->Ln(5);
$fn=$cis_s['fecha'];
$fcis=date_create($fn);
$pdf->Cell(35,5, date_format($fcis,"d-m-Y h:i:s"),1,0,'C');
$pdf->Cell(53,5, utf8_decode($cis_s['medicam_mat']),1,0,'C');
$pdf->Cell(20,5, utf8_decode($cis_s['dosis_mat']),1,0,'C');
$pdf->Cell(20,5, utf8_decode($cis_s['via_mat']),1,0,'C');
$pdf->Cell(35,5, utf8_decode($cis_s['otro']),1,0,'C');
$pdf->Cell(27,5, utf8_decode($cis_s['cantidad']),1,0,'C');
}

$pdf->SetFont('Arial', 'B', 10);
$pdf->Ln(10);
$pdf->Cell(190,5, utf8_decode('EQUIPOS'),1,0,'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(35,5, utf8_decode('FECHA'),1,0,'C');
$pdf->Cell(120,5, utf8_decode('EQUIPO'),1,0,'C');
$pdf->Cell(35,5, utf8_decode('HORAS'),1,0,'C');
$cis = $conexion->query("select * from equipos_ceye where id_atencion=$id_atencion and fecha_registro = '$fecha_mat' ORDER BY id_equipceye") or die($conexion->error);
while ($cis_s = $cis->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 8);
$pdf->Ln(5);
$fn=$cis_s['fecha'];
$fcis=date_create($fn);
$pdf->Cell(35,5, date_format($fcis,"d-m-Y h:i:s"),1,0,'C');
$pdf->Cell(120,5, utf8_decode($cis_s['nombre']),1,0,'C');
$pdf->Cell(35,5, utf8_decode($cis_s['tiempo']),1,0,'C');

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
 
  $pdf->SetY(-53);

      $pdf->SetFont('Arial', 'B', 8);
      $pdf->Image('../../imgfirma/' . $firma, 140, 228, 35);
      $pdf->Ln(6);
      $pdf->SetX(130);
      $pdf->Cell(50, 4, utf8_decode($pre . '. ' . $app . ' ' . $apm . ' ' . $nom), 0, 0, 'C');
      $pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 8);

      $pdf->SetX(95); 
      $pdf->Cell(59, 4, utf8_decode($cargp), 0, 0, 'C');
      $pdf->Cell(59, 4, utf8_decode('CÉD. PROF. ' . $ced_p), 0, 0, 'C');
      $pdf->SetX(108);
      $pdf->Cell(90, 4, utf8_decode(''), 'B', 'C');
      $pdf->Ln(6);
      $pdf->SetX(65);
      $pdf->Cell(180, 4, utf8_decode('MEDICO QUE AUTORIZA'), 0, 0, 'C');
      $pdf->SetY(-41);
      $pdf->SetX(10);
      $pdf->Cell(70, 4, utf8_decode(''), 'B', 'C');
      $pdf->SetY(-35);
      $pdf->SetX(20);
      $pdf->Cell(180, 4, utf8_decode('NOMBRE Y FIRMA DE ENFERMERA'), 0, 0, 'L');
 $pdf->Output(); 
