<?php
require '../../fpdf/fpdf.php';
include '../../conexionbd.php';
$id_med = @$_GET['id_med'];
$id_unidcuid_amb = @$_GET['id_unid_cuid_amb'];
$id_exp = @$_GET['id_exp'];
$id_atencion = @$_GET['id_atencion'];

$sql_post = "SELECT * FROM dat_unid_cuid_amb  where id_atencion = $id_atencion";
$result_post = $conexion->query($sql_post);
while ($row_post = $result_post->fetch_assoc()) {
  $id_unid_cuid_amb = $row_post['id_unid_cuid_amb'];
}
if(isset($id_unid_cuid_amb)){
    $id_unid_cuid_amb = $id_unid_cuid_amb;
  }else{
    $id_unid_cuid_amb ='sin doc';
  }

if($id_unid_cuid_amb=="sin doc"){
  echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
                    $(document).ready(function() {
                        swal({
                            title: "NO EXISTE REGISTRO NOTA DE RECUPERACIÓN PARA ESTE PACIENTE", 
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
    $this->Cell(0, 10, utf8_decode('SIMA-038'), 0, 1, 'R');
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
  $fecha = $row_ing['fecha'];
}

$sql_camas = "SELECT * FROM cat_camas  where id_atencion = $id_atencion";
$result_camas = $conexion->query($sql_camas);
while ($row_camas = $result_camas->fetch_assoc()) {

  $num_cama = $row_camas['num_cama'];
}

$sql_ing = "SELECT * FROM dat_ingreso  where Id_exp = $id_exp";
$result_ing = $conexion->query($sql_ing);
while ($row_ing = $result_ing->fetch_assoc()) {
$especialidad = $row_ing['especialidad'];
  $fecha_ing = $row_ing['fecha'];
  $tipo_a= $row_ing['tipo_a'];
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

$pdf->SetFont('Arial', 'B', 9.5);
$pdf->SetX(50);
$pdf->Cell(120, 5, utf8_decode('UNIDAD DE CUIDADOS POST-ANESTÉSICOS (UCPA) - NOTA DE RECUPERACIÓN'), 0, 0, 'C');
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
$pdf->Cell(28, 3, utf8_decode('FECHA DE INGRESO:'),0 , 0, 'L');
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



$sql_post = "SELECT * FROM dat_unid_cuid_amb  where id_unid_cuid_amb = $id_unidcuid_amb";
$result_post = $conexion->query($sql_post);
while ($row_post = $result_post->fetch_assoc()) {

  $t_sisto = $row_post['t_sisto'];
  $t_diasto = $row_post['t_diasto'];
  $fc_un = $row_post['fc_un'];
  $fr_un = $row_post['fr_un'];
  $temp_un = $row_post['temp_un'];
  $pul_un = $row_post['pul_un'];
  $sp_un = $row_post['sp_un'];
  $est_un = $row_post['est_un'];
  $con_un = $row_post['con_un'];
  $via_un = $row_post['via_un'];
  $veni_un = $row_post['veni_un'];
  $son_un = $row_post['son_un'];
  $man_un = $row_post['man_un'];
  $trat_un = $row_post['trat_un'];
  $fun_un = $row_post['fun_un'];
  $mot_un = $row_post['mot_un'];
  $tera_un = $row_post['tera_un'];
  $H1 = $row_post['h1'];
  $H2 = $row_post['h2'];
  $H3 = $row_post['h3'];
  $H4 = $row_post['h4'];
  $H5 = $row_post['h5'];
  $H6 = $row_post['h6'];
  $H7 = $row_post['h7'];
  $H8 = $row_post['h8'];
  $reg_un = $row_post['reg_un'];
  $a = $row_post['a'];
  $b = $row_post['b'];
  $c = $row_post['c'];
  $d = $row_post['d'];
  $e = $row_post['e'];
  $f = $row_post['f'];
  $g = $row_post['g'];
  $h = $row_post['h'];
  $i = $row_post['i'];
  $j = $row_post['j'];
  $k = $row_post['k'];
  $l = $row_post['l'];
  $fecha_un = $row_post['fecha_un'];
  $hora_un = $row_post['hora_un'];
  $rec_un = $row_post['rec_un'];
  $esp_un = $row_post['esp_un'];
  $unirec_un = $row_post['unirec_un'];
  $fecha2_un = $row_post['fecha2_un'];
  $hora2_un = $row_post['hora2_un'];
  $notevo_un = $row_post['notevo_un'];
  $t01 = $row_post['01t'];
  $t02 = $row_post['02t'];
  $t03 = $row_post['03t'];
  $t04 = $row_post['04t'];
  $t05 = $row_post['05t'];
  $t0 = $row_post['0t'];

  $t51 = $row_post['51t'];
  $t52 = $row_post['52t'];
  $t53 = $row_post['53t'];
  $t54 = $row_post['54t'];
  $t55 = $row_post['55t'];
  $t5 = $row_post['5t'];

  $t101= $row_post['101t'];
  $t102 = $row_post['102t'];
  $t103 = $row_post['103t'];
  $t104 = $row_post['104t'];
  $t105 = $row_post['105t'];
  $t10 = $row_post['10t'];

  $t151 = $row_post['151t'];
  $t152 = $row_post['152t'];
  $t153 = $row_post['153t'];
  $t154 = $row_post['154t'];
  $t155 = $row_post['155t'];
  $t15 = $row_post['15t'];

  $t201 = $row_post['201t'];
  $t202 = $row_post['202t'];
  $t203 = $row_post['203t'];
  $t204 = $row_post['204t'];
  $t205 = $row_post['205t'];
  $t20 = $row_post['20t'];

  $t251 = $row_post['251t'];
  $t252 = $row_post['252t'];
  $t253 = $row_post['253t'];
  $t254 = $row_post['254t'];
  $t255 = $row_post['255t'];
  $t25 = $row_post['25t'];

  $t301 = $row_post['301t'];
  $t302 = $row_post['302t'];
  $t303 = $row_post['303t'];
  $t304 = $row_post['304t'];
  $t305 = $row_post['305t'];
  $t30 = $row_post['30t'];
}

$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(195, 3, utf8_decode('SIGNOS VITALES'), 0,0, 'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(35, 3, utf8_decode('PRESIÓN ARTERIAL: ' .$t_sisto.'/'.$t_diasto), 1, 'L');
$pdf->Cell(31, 3, utf8_decode('FREC. CARDIACA: ' .$fc_un), 1, 'L');
$pdf->Cell(32, 3, utf8_decode('FREC. RESPIRATORIA: ' .$fr_un), 1, 'L');
$pdf->Cell(32, 3, utf8_decode('TEMPERATURA: ' .$temp_un), 1, 'L');
$pdf->Cell(33, 3, utf8_decode('SATURACIÓN OXIGENO: ' .$sp_un), 1, 'L');
$pdf->Cell(32, 3, utf8_decode('ESCALA EVA: ' .$pul_un), 1, 'L');
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(43, 3, utf8_decode('ESTADO GENERAL: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(152, 3, utf8_decode($est_un), 1, 'L');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(43, 3, utf8_decode('ESTADO DE CONCIENCIA: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(55, 3, utf8_decode($con_un), 1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(43, 3, utf8_decode('PERMEABILIDAD DE LA VÍA ÁREA:'), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(54, 3, utf8_decode($via_un), 1, 'L');
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(43, 3, utf8_decode('PERMEBAILIDAD DE VENOCLISIS: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(55, 3, utf8_decode($veni_un), 1, 'L');
$pdf->SetFont('Arial', 'B', 5.5);
$pdf->Cell(43,3, utf8_decode('PERMEABILIDAD DE SONDAS Y DRENAJES: '),1,'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(54, 3, utf8_decode($son_un), 1, 'L');

$pdf->SetFont('Arial', 'B', 6);
$pdf->MultiCell(85,3, utf8_decode('PROBLEMAS CLÍNICOS PENDIENTES Y EL PLAN TERAPÉUTICO DETALLADO:'),0,'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(195,6, utf8_decode($son_un),1,'L');

$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(43,3, utf8_decode('MOTIVO DEL EGRESO: '),1,'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(152,3, utf8_decode($veni_un),1,'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(98,3, utf8_decode('EGRESO DE LA UNIDAD DE RECUPERACIÓN A:'),0,'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->SetFont('Arial', 'B', 6);
$fecha_un=date_create($fecha_un);
$fecha_un=date_format($fecha_un,"d/m/Y");
$pdf->Cell(10,3, utf8_decode('FECHA: '),1,'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(33,3, utf8_decode($fecha_un),1,'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(10,3, utf8_decode('HORA: '),1,'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(44,3, utf8_decode($hora_un),1,'L');

$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(25,3, utf8_decode('HABITACIÓN:'),0,'L');
$pdf->Cell(5,3, utf8_decode(''),1,'L');

$pdf->setx(45);
$pdf->Cell(20,3, utf8_decode('DOMICILIO:'),0,'L');
$pdf->Cell(5,3, utf8_decode(''),1,'L');

$pdf->setx(74);
$pdf->Cell(15,3, utf8_decode('OTRO:'),0,'L');
$pdf->Cell(5,3, utf8_decode(''),1,'L');

$pdf->setx(108);
$pdf->Cell(25,3, utf8_decode('ESPECIFICAR:'),0,'L');
$pdf->Cell(72,3, utf8_decode(''),1,'L');

$pdf->SetFont('Arial', 'B',6);
if($rec_un=="HABITACIÓN" || $rec_un=="HABITACION" ){

$pdf->setx(35);
$pdf->Cell(25,3, utf8_decode('X'),0,'L');
}
if($rec_un=="DOMICILIO"){

$pdf->setx(65);
$pdf->Cell(25,3, utf8_decode('X'),0,'L');
}
if($rec_un=="OTRO"){

$pdf->setx(90);
$pdf->Cell(25,3, utf8_decode('X'),0,'L');

$pdf->setx(138);
$pdf->Cell(65,3, utf8_decode($esp_un),1,'L');
}


$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 6);
$pdf->MultiCell(100,3, utf8_decode('NOTA DE EVOLUCIÓN POST-ANESTÉSICA DE 24 HRS. Y 48 HRS. (SI ES NECESARIO)'),0,'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(195,3, utf8_decode($notevo_un),1,'L');


$pdf->setY(132);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(195,8, utf8_decode('ALDRETE'),0,1,'C');
$pdf->Ln(5);

$pdf->setY(140);
$pdf->setx(10);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(90,7, utf8_decode('TIEMPO'),1,0,'C');
$pdf->setY(140);
$pdf->setx(100);
$pdf->Cell(15,7, utf8_decode('    0'),1,0,'C');
$pdf->setY(140);
$pdf->setx(115);
$pdf->Cell(15,7, utf8_decode('    5'),1,0,'C');
$pdf->setY(140);
$pdf->setx(130);
$pdf->Cell(15,7, utf8_decode('    10'),1,0,'C');
$pdf->setY(140);
$pdf->setx(145);
$pdf->Cell(15,7, utf8_decode('    15'),1,0,'C');
$pdf->setY(140);
$pdf->setx(160);
$pdf->Cell(15,7, utf8_decode('    20'),1,0,'C');
$pdf->setY(140);
$pdf->setx(175);
$pdf->Cell(15,7, utf8_decode('    25'),1,0,'C');
$pdf->setY(140);
$pdf->setx(190);
$pdf->Cell(15,7, utf8_decode('    30'),1,0,'C');

$pdf->setY(147);
$pdf->setx(10);
$pdf->SetFont('Arial', 'B',7);
$pdf->MultiCell(25,6, utf8_decode('ACTIVIDAD MUSCULAR'),1,'C');
$pdf->setY(147);
$pdf->setx(35);
$pdf->SetFont('Arial', '',6);
$pdf->MultiCell(65,4, utf8_decode('MOVS. VOLUNTARIOS AL LLAMADO (4 EXTREMIDADES) MOVS. VOLUNTARIOS AL LLAMADO (2 EXTREMIDADES) COMPLETAMENTE INMÓVIL'),1,'L');
$pdf->setY(147);
$pdf->setx(100);
$pdf->MultiCell(15,12, utf8_decode($t01),1,'C');
$pdf->setY(147);
$pdf->setx(115);
$pdf->MultiCell(15,12, utf8_decode($t51),1,'C');
$pdf->setY(147);
$pdf->setx(130);
$pdf->MultiCell(15,12, utf8_decode($t101),1,'C');
$pdf->setY(147);
$pdf->setx(145);
$pdf->MultiCell(15,12, utf8_decode($t151),1,'C');
$pdf->setY(147);
$pdf->setx(160);
$pdf->MultiCell(15,12, utf8_decode($t201),1,'C'); 
$pdf->setY(147);
$pdf->setx(175);
$pdf->MultiCell(15,12, utf8_decode($t251),1,'C');
$pdf->setY(147);
$pdf->setx(190);
$pdf->MultiCell(15,12, utf8_decode($t301),1,'C');

$pdf->setY(159);
$pdf->setx(10);
$pdf->SetFont('Arial', 'B',7);
$pdf->MultiCell(25,12, utf8_decode('RESPIRACIÓN'),1,'C');
$pdf->setY(159);
$pdf->setx(35);
$pdf->SetFont('Arial', '',6);
$pdf->MultiCell(65,4, utf8_decode('RESPIRACIONES AMPLIAS Y CAPAZ DE TOSER RESPIRACIONES LIMITADAS
APNEA'),1,'L');
$pdf->setY(159);
$pdf->setx(100);
$pdf->MultiCell(15,12, utf8_decode($t02),1,'C');
$pdf->setY(159);
$pdf->setx(115);
$pdf->MultiCell(15,12, utf8_decode($t52),1,'C');
$pdf->setY(159);
$pdf->setx(130);
$pdf->MultiCell(15,12, utf8_decode($t102),1,'C');
$pdf->setY(159);
$pdf->setx(145);
$pdf->MultiCell(15,12, utf8_decode($t152),1,'C');
$pdf->setY(159);
$pdf->setx(160);
$pdf->MultiCell(15,12, utf8_decode($t202),1,'C'); 
$pdf->setY(159);
$pdf->setx(175);
$pdf->MultiCell(15,12, utf8_decode($t252),1,'C');
$pdf->setY(159);
$pdf->setx(190);
$pdf->MultiCell(15,12, utf8_decode($t302),1,'C');

$pdf->setY(171);
$pdf->setx(10);
$pdf->SetFont('Arial', 'B',7);
$pdf->MultiCell(25,12, utf8_decode('CIRCULACIÓN'),1,'C');
$pdf->setY(171);
$pdf->setx(35);
$pdf->SetFont('Arial', '',6);
$pdf->MultiCell(65,4, utf8_decode('PRESIÓN ARTERIAL + -20% DEL NIVEL BASAL PRESIÓN ARTERIAL + -21 - 49% DEL NIVEL BASAL PRESIÓN ARTERIAL + -50% DEL NIVEL BASAL'),1,'L');
$pdf->setY(171);
$pdf->setx(100);
$pdf->MultiCell(15,12, utf8_decode($t03),1,'C');
$pdf->setY(171);
$pdf->setx(115);
$pdf->MultiCell(15,12, utf8_decode($t53),1,'C');
$pdf->setY(171);
$pdf->setx(130);
$pdf->MultiCell(15,12, utf8_decode($t103),1,'C');
$pdf->setY(171);
$pdf->setx(145);
$pdf->MultiCell(15,12, utf8_decode($t153),1,'C');
$pdf->setY(171);
$pdf->setx(160);
$pdf->MultiCell(15,12, utf8_decode($t203),1,'C'); 
$pdf->setY(171);
$pdf->setx(175);
$pdf->MultiCell(15,12, utf8_decode($t253),1,'C');
$pdf->setY(171);
$pdf->setx(190);
$pdf->MultiCell(15,12, utf8_decode($t303),1,'C');

$pdf->setY(183);
$pdf->setx(10);
$pdf->SetFont('Arial', 'B',7);
$pdf->MultiCell(25,6, utf8_decode('ESTADO DE CONTINGENCIA'),1,'C');
$pdf->setY(183);
$pdf->setx(35);
$pdf->SetFont('Arial', '',6);
$pdf->MultiCell(65,4, utf8_decode('COMPLETAMENTE DESPIERTO
RESPONDE AL SER LLAMADO
NO RESPONDE'),1,'L');
$pdf->setY(183);
$pdf->setx(100);
$pdf->MultiCell(15,12, utf8_decode($t04),1,'C');
$pdf->setY(183);
$pdf->setx(115);
$pdf->MultiCell(15,12, utf8_decode($t54),1,'C');
$pdf->setY(183);
$pdf->setx(130);
$pdf->MultiCell(15,12, utf8_decode($t104),1,'C');
$pdf->setY(183);
$pdf->setx(145);
$pdf->MultiCell(15,12, utf8_decode($t154),1,'C');
$pdf->setY(183);
$pdf->setx(160);
$pdf->MultiCell(15,12, utf8_decode($t204),1,'C'); 
$pdf->setY(183);
$pdf->setx(175);
$pdf->MultiCell(15,12, utf8_decode($t254),1,'C');
$pdf->setY(183);
$pdf->setx(190);
$pdf->MultiCell(15,12, utf8_decode($t304),1,'C');

$pdf->setY(195);
$pdf->setx(10);
$pdf->SetFont('Arial', 'B',7);
$pdf->MultiCell(25,6, utf8_decode('SATURACIÓN DE OXIGENO'),1,'C');
$pdf->setY(195);
$pdf->setx(35);
$pdf->SetFont('Arial', '',6);
$pdf->MultiCell(65,4, utf8_decode('MANTIENE SAT. DE O2 > 92% CON AIRE AMBIENTE NECESITA O2 PARA MANTENER LA SAT DE O2 > 90% SATURACIÓN DE O2 < 90% CON SUPLEMENTO DE OXÍGENO'),1,'L');
$pdf->setY(195);
$pdf->setx(100);
$pdf->MultiCell(15,12, utf8_decode($t05),1,'C');
$pdf->setY(195);
$pdf->setx(115);
$pdf->MultiCell(15,12, utf8_decode($t55),1,'C');
$pdf->setY(195);
$pdf->setx(130);
$pdf->MultiCell(15,12, utf8_decode($t105),1,'C');
$pdf->setY(195);
$pdf->setx(145);
$pdf->MultiCell(15,12, utf8_decode($t155),1,'C');
$pdf->setY(195);
$pdf->setx(160);
$pdf->MultiCell(15,12, utf8_decode($t205),1,'C'); 
$pdf->setY(195);
$pdf->setx(175);
$pdf->MultiCell(15,12, utf8_decode($t255),1,'C');
$pdf->setY(195);
$pdf->setx(190);
$pdf->MultiCell(15,12, utf8_decode($t305),1,'C');

$pdf->setY(207);
$pdf->setx(10);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(90,10, utf8_decode('TOTAL DE ALDRETE'),1,0,'C');
$pdf->SetFont('Arial', '',6);
$pdf->setY(207);
$pdf->setx(100);
$pdf->MultiCell(15,10, utf8_decode($t0),1,'C');
$pdf->setY(207);
$pdf->setx(115);
$pdf->MultiCell(15,10, utf8_decode($t5),1,'C');
$pdf->setY(207);
$pdf->setx(130);
$pdf->MultiCell(15,10, utf8_decode($t10),1,'C');
$pdf->setY(207);
$pdf->setx(145);
$pdf->MultiCell(15,10, utf8_decode($t15),1,'C');
$pdf->setY(207);
$pdf->setx(160);
$pdf->MultiCell(15,10, utf8_decode($t20),1,'C'); 
$pdf->setY(207);
$pdf->setx(175);
$pdf->MultiCell(15,10, utf8_decode($t25),1,'C');
$pdf->setY(207);
$pdf->setx(190);
$pdf->MultiCell(15,10, utf8_decode($t30),1,'C');

///////////////////FIRMA DE MEDICO 

$sql_med_id = "SELECT id_usua FROM dat_unid_cuid_amb  where id_unid_cuid_amb = $id_unidcuid_amb";
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


      
      $pdf->SetY(-58);
      $pdf->SetFont('Arial', 'B', 7);
      $pdf->Image('../../imgfirma/' . $firma, 95, 250, 30);
    
      $pdf->SetY(264);
      $pdf->Cell(200, 3, utf8_decode($pre . '. ' . $app . ' ' . $apm . ' ' . $nom), 0, 0, 'C');
      $pdf->Ln(4);
      $pdf->SetFont('Arial', 'B', 7);
      $pdf->Cell(200, 3, utf8_decode($cargp . ' ' . 'CÉD. PROF. ' . $ced_p), 0, 0, 'C');
      $pdf->Ln(4);
      $pdf->Cell(200, 3, utf8_decode('NOMBRE Y FIRMA DEL MÉDICO'), 0, 0, 'C');


 $pdf->Output();
}