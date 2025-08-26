<?php
require '../../fpdf/fpdf.php';
include '../../conexionbd.php';
$id_med = @$_GET['id_med'];
$id_postanest_amb= @$_GET['id_post_anest_amb'];
$id_exp = @$_GET['id_exp'];
$id_atencion = @$_GET['id_atencion'];

$sql_post = "SELECT * FROM dat_post_anest_amb  where id_atencion = $id_atencion";
$result_post = $conexion->query($sql_post);
while ($row_post = $result_post->fetch_assoc()) {
  $id_post_anest_amb = $row_post['id_post_anest_amb'];
}

if(isset($id_post_anest_amb)){
    $id_post_anest_amb = $id_post_anest_amb;
  }else{
    $id_post_anest_amb ='sin doc';
  }

if($id_post_anest_amb=="sin doc"){
  echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
                    $(document).ready(function() {
                        swal({
                            title: "NO EXISTE REGISTRO NOTA POST-ANESTÉSICA PARA ESTE PACIENTE", 
                            type: "error",
                            confirmButtonText: "ACEPTAR"
                        }, function(isConfirm) { 
                            if (isConfirm) {
                                window.close();
                            }
                        });
                    });
                </script>';
}else{
mysqli_set_charset($conexion, "utf8");

class PDF extends FPDF
{
  function Header()
  {
    $id = @$_GET['id'];
    $id_med = @$_GET['id_med'];
    include '../../conexionbd.php';

    $id_atencion = @$_GET['id_atencion'];;

    $this->Image('../../imagenes/logo PDF.jpg', 10, 10, 28, 30);
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
    $this->Image('../../imagenes/logo PDF 2.jpg', 165, 20, 40, 20);
  }
  function Footer()
  {
    $this->Ln(8);
    $this->SetY(-15);
    $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}' ), 0, 0, 'C');
    $this->Cell(0, 10, utf8_decode('SIMA-039'), 0, 1, 'R');
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
}


$sql_ing = "SELECT * FROM dat_ingreso  where Id_exp = $id_exp";
$result_ing = $conexion->query($sql_ing);
while ($row_ing = $result_ing->fetch_assoc()) {
  $especialidad = $row_ing['especialidad'];
  $fecha_ing = $row_ing['fecha'];
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
  if ($ano_diferencia > 0 )
      return ($ano_diferencia . ' AÑOS');
  else if ($mes_diferencia > 0 || $ano_diferencia < 0)
      return ($mes_diferencia . ' MESES');
   else if ($dia_diferencia > 0 || $mes_diferencia < 0 || $ano_diferencia < 0)
      return ($dia_diferencia . ' DÍAS');
}

$pdf = new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetX(50);
$pdf->Cell(120, 5, utf8_decode('NOTA POST-ANESTESICA'), 0, 0, 'C');
$pdf->SetFont('Arial', '', 6);
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("d/m/Y");
$pdf->Cell(35, 5, 'Toluca, Mex, ' . $fecha_actual, 0, 1, 'R');

$pdf->SetDrawColor(0, 0, 0);
$pdf->Line(8, 50, 207, 50);
$pdf->Line(8, 50, 8, 280);
$pdf->Line(207, 50, 207, 280);
$pdf->Line(8, 280, 207, 280);
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(17, 3, 'SERVICIO: ', 0, 'L');  
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(124, 3, utf8_decode($tipo_a) , 'B', 'L');
$pdf->SetFont('Arial', 'B', 6);
$date=date_create($fecha_ing);
$pdf->Cell(28, 3, utf8_decode('FECHA INGRESO:'),0 , 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(25, 3, date_format($date,'d-m-Y H:i:s'), 'B', 0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(20, 3, utf8_decode('EXPEDIENTE: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(8, 3, utf8_decode($Id_exp), 'B', 0, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(40, 3, ' NOMBRE DEL PACIENTE: ', 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(126, 3, utf8_decode($papell . ' ' . $sapell . ' ' . $nom_pac), 'B', 'L');
$pdf->SetFont('Arial', 'B', 6);

$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$date=date_create($fecnac);
$pdf->Cell(37, 3, 'FECHA DE NACIMIENTO: ', 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(20, 3, date_format($date,"d/m/Y"), 'B', 'C');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(12, 3, ' EDAD: ', 0, 'L');

$edad=calculaedad($fecnac);

$pdf->SetFont('Arial', '', 6);
$pdf->Cell(20, 3, utf8_decode($edad), 'B', 'C');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(21, 3, utf8_decode(' OCUPACIÓN: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(31, 3,  utf8_decode($ocup), 'B', 'L');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(28, 3, utf8_decode('TELÉFONO: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(25, 3,  utf8_decode($tel), 'B', 'L');

$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(9, 4, 'SEXO: ', 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(14, 4,  $sexo, 'B', 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(15, 4, utf8_decode(' DOMICILIO: '), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(102, 4, utf8_decode($dir), 'B', 'L');


$sql_post = "SELECT * FROM dat_post_anest_amb  where id_post_anest_amb = $id_postanest_amb";
$result_post = $conexion->query($sql_post);
while ($row_post = $result_post->fetch_assoc()) {

  $tecan_pos = $row_post['tecan_pos'];
  $tiem_pos = $row_post['tiem_pos'];
  $an_pos = $row_post['an_pos'];
  $ad_pos = $row_post['ad_pos'];
  $con_pos = $row_post['con_pos'];
  $bal_pos = $row_post['bal_pos'];
  $sist_pos = $row_post['sist_pos'];
  $dias_pos = $row_post['dias_pos'];
  $fc_pos = $row_post['fc_pos'];
  $fr_pos = $row_post['fr_pos'];
  $temp_pos = $row_post['temp_pos'];
  $pul_pos = $row_post['pul_pos'];
  $so_pos = $row_post['so_pos'];
  $ae_pos = $row_post['ae_pos'];
  $san_pos = $row_post['san_pos'];
  $ven_pos = $row_post['ven_pos'];
  $dre_pos = $row_post['dre_pos'];
  $tras_pos = $row_post['tras_pos'];
  $ob_pos = $row_post['ob_pos'];
  $plan_pos = $row_post['plan_pos'];
  $fecha_pos = $row_post['fecha_pos'];
  $hora_pos = $row_post['hora_pos'];
}


$pdf->SetFont('Arial', '', 10);
$pdf->Ln(10);
$pdf->Cell(99,6, utf8_decode('TÉCNICA ANESTÉSICA: '.$tecan_pos),1,'L');
$pdf->Cell(91,6, utf8_decode('TIEMPO ANESTÉSICO: '.$tiem_pos),1,'L');
$pdf->Ln(10);
$pdf->setY(90);
$pdf->setx(10);
$pdf->MultiCell(32,6, utf8_decode('MEDICAMENTOS ADMINISTRADOS'),1,'L');
$pdf->setY(90);
$pdf->setx(42);
$pdf->MultiCell(32,6, utf8_decode('ANESTÉSICOS'),1,'L');
$pdf->setY(90);
$pdf->setx(74);
$pdf->MultiCell(126,6, utf8_decode($an_pos),1,'L');
$pdf->setY(96);
$pdf->setx(42);
$pdf->MultiCell(32,6, utf8_decode('ADYUVANTES'),1,'L');
$pdf->setY(96);
$pdf->setx(74);
$pdf->MultiCell(126,6, utf8_decode($ad_pos),1,'L');

$pdf->setY(105);
$pdf->setx(10);
$pdf->MultiCell(52,6, utf8_decode('CONTINGENCIAS ACCIDENTALES E INCIDENTES ATRIBUIBLES A LA ANESTESIA'),1,'L');
$pdf->setY(105);
$pdf->setx(62);
$pdf->MultiCell(138,24, utf8_decode($con_pos),1,'L');


$pdf->setY(132);
$pdf->setx(10);
$pdf->MultiCell(52,24, utf8_decode('BALANCE HÍDRICO'),1,'L');
$pdf->setY(132);
$pdf->setx(62);
$pdf->MultiCell(138,24, utf8_decode($bal_pos),1,'L');

$pdf->SetFont('Arial', 'B', 10);
$pdf->Ln(6);
$pdf->setx(55);
$pdf->Cell(99,6, utf8_decode('ESTADO CLÍNICO DEL PACIENTE AL EGRESO DEL QUIRÓFANO'),0,'L');
$pdf->SetFont('Arial', '', 10);
$pdf->setY(172);
$pdf->setx(10);
$pdf->MultiCell(35,6, utf8_decode('SIGNOS VITALES'),1,'L');
$pdf->setY(172);
$pdf->setx(45);
$pdf->MultiCell(25,6, utf8_decode('T/A : '.$sist_pos.'/'.$dias_pos),1,'L');
$pdf->setY(172);
$pdf->setx(70);
$pdf->MultiCell(20,6, utf8_decode('F.C : '.$fc_pos),1,'L');
$pdf->setY(172);
$pdf->setx(90);
$pdf->MultiCell(20,6, utf8_decode('F.R : '.$fr_pos),1,'L');
$pdf->setY(172);
$pdf->setx(110);
$pdf->MultiCell(25,6, utf8_decode('TEMP : '.$temp_pos),1,'L');
$pdf->setY(172);
$pdf->setx(135);
$pdf->MultiCell(25,6, utf8_decode('PULSO : '.$pul_pos),1,'L');
$pdf->setY(172);
$pdf->setx(160);
$pdf->MultiCell(40,6, utf8_decode('SAT OXÍGENO : '.$so_pos),1,'L');
$pdf->setY(178);
$pdf->setx(10);
$pdf->MultiCell(99,6, utf8_decode('VÍA ÁEREA : '.$ae_pos),1,'L');
$pdf->setY(178);
$pdf->setx(109);
$pdf->MultiCell(91,6, utf8_decode('SANGRADO ACTIVO ANORMAL : '.$san_pos),1,'L');
$pdf->setY(184);
$pdf->setx(10);
$pdf->MultiCell(99,6, utf8_decode('PERMEABILIDAD DE VENOCLISIS : '.$ven_pos),1,'L');
$pdf->setY(184);
$pdf->setx(109);
$pdf->MultiCell(91,6, utf8_decode('PERMEABILIDAD DE SONDAS Y DRENAJES :'.$dre_pos),1,'L');
$pdf->setY(192);
$pdf->setx(10);
$pdf->MultiCell(190,6, utf8_decode('LUGAR Y CONDICIONES DE TRASLADO (INCLUIR ALDRETE) : '.$tras_pos),1,'L');
$pdf->setY(198);
$pdf->setx(10);
$pdf->MultiCell(80,20, utf8_decode('OBSERVACIONES : '.$ob_pos),1,'L');
$pdf->setY(198);
$pdf->setx(90);
$pdf->MultiCell(110,10, utf8_decode('PLAN DE MANEJO Y TRATAMIENTO, INCLUYENDO PROTOCOLO DE ANALGESIA : '.$plan_pos),1,'L');

$pdf->setY(224);
$pdf->setx(10);
$pdf->MultiCell(99,6, utf8_decode('FECHA '.$fecha_pos),1,'L');
$pdf->setY(224);
$pdf->setx(109);
$pdf->MultiCell(91,6, utf8_decode('HORA '.$hora_pos),1,'L');
///////////////////FIRMA DE MEDICO 

$sql_med_id = "SELECT id_usua FROM dat_post_anest_amb  where id_post_anest_amb = $id_postanest_amb";
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

}


      
      $pdf->SetFont('Arial', 'B', 10);
      $pdf->Image('../../imgfirma/' . $firma, 75, 235, 50);
      $pdf->Ln(30);
      $pdf->SetX(75);
      $pdf->Cell(50, 4, utf8_decode('MEDICO: '.$pre . ' .' . $app . ' ' . $apm . ' ' . $nom), 0, 0, 'C');
      $pdf->Ln(6);
      $pdf->SetX(20);
      $pdf->Cell(150, 4, utf8_decode('Cédula Profesional: ' . $ced_p), 0, 0, 'C');
      $pdf->SetX(55);
      $pdf->Cell(90, 4, utf8_decode(''), 'B', 'C');
      $pdf->Ln(6);
      $pdf->Cell(180, 4, utf8_decode('NOMBRE Y FIRMA DEL MÉDICO'), 0, 0, 'C');
      


 $pdf->Output();
}