<?php
require '../../fpdf/fpdf.php';
include '../../conexionbd.php';
$id_atencion = @$_GET['id'];
$id_exp = @$_GET['id_exp'];
$id_cirseg_amb = @$_GET['id_cir_seg_amb'];
$id_med = @$_GET['id_med'];

$sql_seg = "SELECT * FROM dat_cir_seg_amb  where id_atencion = $id_atencion";
$result_seg = $conexion->query($sql_seg);

while ($row_seg = $result_seg->fetch_assoc()) {
$id_cir_seg_amb = $row_seg['id_cir_seg_amb'];
}

if(isset($id_cir_seg_amb)){
    $id_cir_seg_amb = $id_cir_seg_amb;
  }else{
    $id_cir_seg_amb ='sin doc';
  }

if($id_cir_seg_amb=="sin doc"){
  echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
                    $(document).ready(function() {
                        swal({
                            title: "NO EXISTE NOTA DE CIRUGÍA SEGURA QUIRÚRGICA PARA ESTE PACIENTE", 
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

    $id = @$_GET['id'];;
    $this->Image('../../imagenes/logo PDF.jpg', 10, 10, 28, 30);
    $this->SetFont('Arial', 'B', 14);
    $this->Cell(280, 8, 'Sanatorio Venecia', 0, 0, 'C');
    $this->Ln(10);
    $this->SetDrawColor(0, 0, 0);
    $this->Line(78, 18, 220, 18);
    $this->Line(78, 19, 220, 19);
    $this->SetFont('Arial', '', 10);
    $this->Cell(280, 8, 'PASEO TOLLOCAN NO. 113 COL. UNIVERSIDAD', 0, 0, 'C');
    $this->Ln(5);
    $this->Cell(280, 8, utf8_decode('C.P. 50130 TOLUCA, MÉX'), 0, 0, 'C');
    $this->Ln(5);
    $this->Cell(280, 8, 'TEL.: (01 722) 280 5672', 0, 0, 'C');
    $this->Ln(5);
    $this->Cell(280, 8, 'www.sanatoriovenecia.com.mx', 0, 0, 'C');
    $this->Ln(10);
    $this->Image('../../imagenes/logo PDF 2.jpg', 253, 20, 40, 20);
  }
  function Footer()
  {
    $this->Ln(8);
    $this->SetY(-15);
    $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}' ), 0, 0, 'C');
    $this->Cell(0, 10, utf8_decode('SIMA-031'), 0, 1, 'R');
  }
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


$sql_pac = "SELECT * FROM paciente where Id_exp=$id_exp";
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
    $edociv = $row_pac['edociv'];
}

//INICIO CONSULTA CIRUGIA SEGURA
$sql_seg = "SELECT * FROM dat_cir_seg_amb se, dat_ingreso din where se.id_cir_seg_amb=$id_cirseg_amb and din.id_atencion =se.id_atencion";
$result_seg = $conexion->query($sql_seg);

while ($row_seg = $result_seg->fetch_assoc()) {
$entrada_pac_confirm = $row_seg['entrada_pac_confirm'];
$lug_noproc = $row_seg['lug_noproc'];
$verificado = $row_seg['verificado'];
$pulsioximetro = $row_seg['pulsioximetro'];
$ver_inst = $row_seg['ver_inst'];

$alerg_con = $row_seg['alerg_con'];
$profilaxis = $row_seg['profilaxis'];
$dif_via_aerea = $row_seg['dif_via_aerea'];
$con_hematies = $row_seg['con_hematies'];
$confirm_presentes = $row_seg['confirm_presentes'];
$confirm_verb = $row_seg['confirm_verb'];

  $cir_rep = $row_seg['cir_rep'];
  $anest_resp = $row_seg['anest_resp'];
  $enf_rep = $row_seg['enf_rep'];
  $img_diag = $row_seg['img_diag'];
  $proced = $row_seg['proced'];
  $especialidad = $row_seg['especialidad'];
  $fecha = $row_seg['fecha'];
  $enf_confirm = $row_seg['enf_confirm'];

  $cont_comp_inst = $row_seg['cont_comp_inst'];
  $ident_gest = $row_seg['ident_gest'];
  $problema = $row_seg['problema'];
  $rev_cir_enf_anest = $row_seg['rev_cir_enf_anest'];
  $prof_trombo = $row_seg['prof_trombo'];
  $ident_pac = $row_seg['ident_pac'];
  $fir_cir = $row_seg['fir_cir'];
  $fir_anest = $row_seg['fir_anest'];
  $fir_enf = $row_seg['fir_enf'];
}
 
//TERMINO CONSULTA CIRUGIA SEGURA
$sql_pac = "SELECT tipo_a FROM dat_ingreso where id_atencion = $id_atencion and Id_exp=Id_exp";
$result_pac = $conexion->query($sql_pac);
while ($row_pac = $result_pac->fetch_assoc()) {
  $tipo_a = $row_pac['tipo_a'];
}

$pdf = new PDF('L','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 9);
$pdf->SETX(80);
$pdf->Cell(100, 6, utf8_decode('CIRUGÍA SEGURA (LISTADO DE VERIFICACIÓN DE SEGURIDAD QUIRÚRGICA)'), 0, 'C');
$pdf->SETX(255);
$pdf->SetFont('Arial', '', 8);
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("d/m/Y");
$pdf->Cell(35, 5, 'Toluca, Mex, ' . $fecha_actual, 0, 1, 'R');
$pdf->Ln(1);


$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(17, 6, 'SERVICIO: ', 0, 'L');  
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(126, 6, utf8_decode($tipo_a) , 'B', 'L');

$date=date_create($fecha);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(36, 6, utf8_decode(' FECHA DE INGRESO:'),0 , 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(20, 6, date_format($date,'d/m/Y'), 'B', 0, 'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(22, 6, utf8_decode('EXPEDIENTE: '), 0, 0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(8, 6, utf8_decode($id_exp), 'B', 0, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(17, 6, ' NOMBRE: ', 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(96, 6, utf8_decode($papell . ' ' . $sapell . ' ' . $nom_pac), 'B', 'L');
$pdf->SetFont('Arial', 'B', 8);

$date=date_create($fecnac);
$pdf->Cell(37, 6, ' FECHA DE NACIMIENTO: ', 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(17, 6, date_format($date,"d/m/Y"), 'B', 'C');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(14, 6, ' EDAD: ', 0, 'L');
$edad=calculaedad($fecnac);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(18, 6, utf8_decode($edad), 'B', 'C');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(13, 6, ' SEXO: ', 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(30, 6,  $sexo, 'B', 'L');

$pdf->Ln(8);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(94, 6, utf8_decode('ANTES DE LA ADMINISTRACIÓN DE LA ANESTESIA'), 1,0, 'C');
$pdf->Cell(94, 6, utf8_decode('ANTES DE LA INCISIÓN EN LA PIEL') , 1,0, 'C');
$pdf->Cell(94, 6, utf8_decode('ANTES DE QUE EL PACIENTE ABANDONE EL QUIROFANO') , 1,1, 'C');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(94, 6, utf8_decode('ENTRADA (ENFERMERA Y ANESTESIÓLOGO)'), 1,0, 'C');
$pdf->Cell(94, 6, utf8_decode('PAUSA (ENFERMERA, ANESTESIÓLOGO Y CIRUJANO)') , 1,0, 'C');
$pdf->Cell(94, 6, utf8_decode('SALIDA (ENFERMERA, ANESTESIÓLOGO Y CIRUJANO)') , 1,1, 'C');

$pdf->SetY(71);
$pdf->SetX(281.5); /* Inicio */
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(5, 6, utf8_decode('SI '),1, 'C');
$pdf->Cell(5.5, 6, utf8_decode('NO '),1, 'C');
$pdf->SetFont('Arial', '', 7);
//contenido
$pdf->SetY(80); /* Inicio */
$pdf->MultiCell(94, 6, utf8_decode(' * EL PACIENTE HA CONFIRMADO: ' . ' 
     · SU IDENTIDAD
     · LOCALIZACIÓN QUIRÚRGICA
     · LA OPERACIÓN
     · CONSENTIMIENTO INFORMADO'),1, 'L');
$pdf->SetY(71);
$pdf->SetX(93.5); /* Inicio */
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(5, 6, utf8_decode('SI '),1, 'C');
$pdf->Cell(5.5, 6, utf8_decode('NO '),1, 'C');
$pdf->SetY(80);
$pdf->SetX(93.5);

$pdf->SetFont('Arial', '', 7);
if($entrada_pac_confirm=='SI'){
$pdf->SetY(80);
$pdf->SetX(93.5);
$entrada_pac_confirm= ' X ';
$pdf->Cell(5, 30, utf8_decode($entrada_pac_confirm),1, 'C');
}else if ($entrada_pac_confirm=='NO') {
$pdf->SetY(80);
$pdf->SetX(98.7);
$entrada_pac_confirm= ' X ';
$pdf->Cell(5, 30, utf8_decode($entrada_pac_confirm),1, 'C');
}

$pdf->SetY(80); 
$pdf->SetX(104); 
$pdf->MultiCell(94, 5, utf8_decode('CONFIRMADO QUE TODOS LOS MIEMBROS DEL EQUIPO ESTÁN PRESENTES Y PREPARADOS:') , 1, 'L');

$pdf->SetY(71);
$pdf->SetX(187.5); /* Inicio */
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(5, 6, utf8_decode('SI '),1, 'C');
$pdf->Cell(5.5, 6, utf8_decode('NO '),1, 'C');
$pdf->SetFont('Arial', '', 7);

if($confirm_presentes=='SI'){
$pdf->SetY(80);
$pdf->SetX(187.5);
$confirm_presentes= ' X ';
$pdf->Cell(5.2, 10, utf8_decode($confirm_presentes),1, 'C');
}else if ($confirm_presentes=='NO') {
  $pdf->SetY(80);
$pdf->SetX(192.5);
$confirm_presentes= ' X ';
$pdf->Cell(5.2, 10, utf8_decode($confirm_presentes),1, 'C');
}

$pdf->SetY(90); /* segunda column cirujano */
$pdf->SetX(104); 
$pdf->MultiCell(94, 4, utf8_decode(' * CIRUJANO ANESTESIÓLOGO Y ENFERMERA HAN
  CONFIRMADO VERBALMENTE: ' . ' 
       · PACIENTE
       · SITIO QUIRÚRGICO
       · PROCEDIMIENTO
       · POSICIÓN
       · SONDAJE '),1, 'L');

if($confirm_verb=='SI'){
$pdf->SetY(90);
$pdf->SetX(187.5);
$confirm_verb= ' X ';
$pdf->Cell(5, 28, utf8_decode($confirm_verb),1, 'C');
}else if ($confirm_verb=='NO') {
  $pdf->SetY(90);
$pdf->SetX(192.4);
$confirm_verb= ' X ';
$pdf->Cell(5.5, 28, utf8_decode($confirm_verb),1, 'C');
}

$pdf->SetFont('Arial', 'B', 7);
$pdf->SetY(118);
 $pdf->SetX(104); 
$pdf->MultiCell(94, 7, utf8_decode('ANTICIPACIÓN DE SUCESOS CRÍTICOS'), 1, 'C'); 

$pdf->SetY(118);
$pdf->SetX(187.5); /* Inicio */
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(5, 7, utf8_decode('SI '),1, 'C');
$pdf->Cell(5.5, 7, utf8_decode('NO '),1, 'C');

$pdf->SetFont('Arial', '', 7);
$pdf->SetY(125);
 $pdf->SetX(104); 
$pdf->MultiCell(94, 3.9, utf8_decode('CIRUJANO REPASA: ¿CUÁLES SON LOS PASOS CRÍTICOS
O INESPERADOS, LA DURACIÓN DE LA INTERVENCIÓN,
LA PÉRDIDA DE SANGRE ESPERADA?'), 1, 'L'); 

if($cir_rep=='SI'){
$pdf->SetY(125);
$pdf->SetX(187.5);
$cir_rep= ' X ';
$pdf->Cell(5, 12, utf8_decode($cir_rep),1, 'C');
}else if ($cir_rep=='NO') {
  $pdf->SetY(125);
$pdf->SetX(192.4);
$cir_rep= ' X ';
$pdf->Cell(5.5, 12, utf8_decode($cir_rep),1, 'C');
$pdf->SetY(125);
$pdf->SetX(187.5);
$pdf->Cell(5, 12, utf8_decode(''),1, 'C');
}




$pdf->SetY(137);
 $pdf->SetX(104); 
$pdf->MultiCell(94, 4, utf8_decode('ANESTESIÓLOGO REPASA: ¿PRESENTA EL PACIENTE ALGUNA PECULIARIDAD QUE SUSCITE PREOCUPACIÓN?'), 1, 'L'); 

if($anest_resp=='SI'){
$pdf->SetY(137);
$pdf->SetX(187.5);
$anest_resp= ' X ';
$pdf->Cell(5, 8, utf8_decode($anest_resp),1, 'C');
}else if ($anest_resp=='NO') {
  $pdf->SetY(137);
$pdf->SetX(192.4);
$anest_resp= ' X ';
$pdf->Cell(5.5, 8, utf8_decode($anest_resp),1, 'C');
$pdf->SetY(137);
$pdf->SetX(187.5);
$pdf->Cell(5, 8, utf8_decode(''),1, 'C');
}

$pdf->SetY(145);
 $pdf->SetX(104); 
$pdf->MultiCell(94, 4, utf8_decode('EL EQUIPO DE ENFERMERÍA REVISA: SI SE HA CONFIRMADO LA ESTERILIDAD(CON RESULTADOS DE LOS INDICADORES) Y SI
EXISTEN DUDAS O PROBLEMAS RELACIONADOS CON EL 
INSTRUMENTAL Y LOS EQUIPOS'), 1, 'L'); 

if($enf_rep=='SI'){
$pdf->SetY(145);
$pdf->SetX(187.5);
$enf_rep= ' X ';
$pdf->Cell(5,16, utf8_decode($enf_rep),1, 'C');
}else if ($enf_rep=='NO') {
  $pdf->SetY(145);
$pdf->SetX(192.4);
$enf_rep= ' X ';
$pdf->Cell(5.5, 16, utf8_decode($enf_rep),1, 'C');
}

$pdf->SetY(161);
$pdf->SetX(104); 
$pdf->MultiCell(94, 4, utf8_decode('¿SE MUESTRAN LAS IMÁGENES DIAGNOSTICAS ESENCIALES?'), 1, 'L'); 

if($img_diag=='SI'){
$pdf->SetY(161);
$pdf->SetX(187.5);
$img_diag= ' X ';
$pdf->Cell(5,4, utf8_decode($img_diag),1, 'C');
}else if ($img_diag=='NO') {
  $pdf->SetY(161);
$pdf->SetX(192.4);
$img_diag= ' X ';
$pdf->Cell(5.5, 4, utf8_decode($img_diag),1, 'C');
}

$pdf->SetY(165);
$pdf->SetX(104); 
$pdf->MultiCell(94, 4, utf8_decode('PROCEDIMIENTO:' . ' ' . $proced), 1, 'L'); 


$pdf->SetY(169);
$pdf->SetX(104); 
$pdf->MultiCell(94, 4, utf8_decode('ESPECIALIDAD:' . ' ' . $especialidad), 1, 'L');


$pdf->SetY(173);
$pdf->SetX(104); 

$f=date_create($fecha);

$pdf->Cell(94, 4, 'FECHA: ' . date_format($f,"d-m-Y"), 1, 'L');


$pdf->SetY(80); 
$pdf->SetX(198); 
$pdf->MultiCell(94, 5, utf8_decode('LA ENFERMERA CONFIRMA VERBALMENTE CON EL EQUIPO
REGISTRADO EL NOMBRE DEL PROCEDIMIENTO') , 1, 'L');

if($enf_confirm=='SI'){
$pdf->SetY(80);
$pdf->SetX(281.5);
$enf_confirm= ' X ';
$pdf->Cell(5,10, utf8_decode($enf_confirm),1, 'C');
}else if ($enf_confirm=='NO') {
  $pdf->SetY(80);
$pdf->SetX(286.5);
$enf_confirm= ' X ';
$pdf->Cell(5.5, 10, utf8_decode($enf_confirm),1, 'C');
$pdf->SetY(80);
$pdf->SetX(281.5);
$pdf->Cell(5,10, utf8_decode(''),1, 'C');
}


$pdf->SetY(90); 
$pdf->SetX(198); 
$pdf->MultiCell(94, 5, utf8_decode('CONTAJE DE COMPRESAS, AGUJAS E INSTRUMENTAL CORRECTO') , 1, 'L');

if($cont_comp_inst=='SI'){
$pdf->SetY(90);
$pdf->SetX(281.5);
$cont_comp_inst= ' X ';
$pdf->Cell(5,5, utf8_decode($cont_comp_inst),1, 'C');
}else if ($cont_comp_inst=='NO') {
  $pdf->SetY(90);
$pdf->SetX(286.5);
$cont_comp_inst= ' X ';
$pdf->Cell(5.5,5, utf8_decode($cont_comp_inst),1, 'C');
$pdf->SetY(90);
$pdf->SetX(281.5);

$pdf->Cell(5,5, utf8_decode(''),1, 'C');
}

$pdf->SetY(95); 
$pdf->SetX(198); 
$pdf->MultiCell(94, 4, utf8_decode('IDENTIFICACIÓN Y GESTIÓN DE LAS MUESTRAS BIÓLOGICAS
(NOMBRE, NO, HO, FECHA DE NACIMIENTO)') , 1, 'L');


if($ident_gest=='SI'){
$pdf->SetY(95);
$pdf->SetX(281.5);
$ident_gest= ' X ';
$pdf->Cell(5,8, utf8_decode($ident_gest),1, 'C');
}else if ($ident_gest=='NO') {
  $pdf->SetY(95);
$pdf->SetX(286.5);
$ident_gest= ' X ';
$pdf->Cell(5.5,8, utf8_decode($ident_gest),1, 'C');
$pdf->SetY(95);
$pdf->SetX(281.5);
$pdf->Cell(5,8, utf8_decode(''),1, 'C');
}


$pdf->SetY(103); 
$pdf->SetX(198); 
$pdf->MultiCell(94, 4, utf8_decode('¿HAY ALGÚN PROBLEMA EN RELACIÓN CON EL MATERIAL O LOS EQUIPOS?') , 1, 'L');

if($problema=='SI'){
$pdf->SetY(103);
$pdf->SetX(281.5);
$problema= ' X ';
$pdf->Cell(5,8, utf8_decode($problema),1, 'C');
}else if ($problema=='NO') {
  $pdf->SetY(103);
$pdf->SetX(286.5);
$problema= ' X ';
$pdf->Cell(5.5,8, utf8_decode($problema),1, 'C');
$pdf->SetY(103);
$pdf->SetX(281.5);

$pdf->Cell(5,8, utf8_decode(''),1, 'C');
}

$pdf->SetY(111); 
$pdf->SetX(198); 
$pdf->MultiCell(94, 4, utf8_decode('CIRUJANO, ANESTESIÓLOGO Y ENFERMERA REVISARON LAS PREOCUPACIONES CLAVES EN LA RECUPERACIÓN Y ATENCIÓN
DEL PACIENTE.') , 1, 'L');

if($rev_cir_enf_anest=='SI'){
$pdf->SetY(111);
$pdf->SetX(281.5);
$rev_cir_enf_anest= ' X ';
$pdf->Cell(5,12, utf8_decode($rev_cir_enf_anest),1, 'C');
}else if ($rev_cir_enf_anest=='NO') {
  $pdf->SetY(111);
$pdf->SetX(286.5);
$rev_cir_enf_anest= ' X ';
$pdf->Cell(5.5,12, utf8_decode($rev_cir_enf_anest),1, 'C');
$pdf->SetY(111);
$pdf->SetX(281.5);

$pdf->Cell(5,12, utf8_decode(''),1, 'C');
}


$pdf->SetY(123); 
$pdf->SetX(198); 
$pdf->MultiCell(94, 6, utf8_decode('¿NECESITA PROFILAXIS TROMBOEMBÓLICA?') , 1, 'L');

if($prof_trombo=='SI'){
$pdf->SetY(123);
$pdf->SetX(281.5);
$prof_trombo= ' X ';
$pdf->Cell(5,6, utf8_decode($prof_trombo),1, 'C');
}else if ($prof_trombo=='NO') {
  $pdf->SetY(123);
$pdf->SetX(286.5);
$prof_trombo= ' X ';
$pdf->Cell(5.5,6, utf8_decode($prof_trombo),1, 'C');
$pdf->SetY(123);
$pdf->SetX(281.5);

$pdf->Cell(5,6, utf8_decode(''),1, 'C');
}

$pdf->SetY(129); 
$pdf->SetX(198); 
$pdf->MultiCell(94, 8, utf8_decode('ETIQUETA IDENTIFICATIVA DEL PACIENTE' . ' ' . $ident_pac) , 1, 'L');

$pdf->SetY(137); 
$pdf->SetX(198); 
$pdf->MultiCell(94, 5, utf8_decode('CIRUJANO' . ' ' . $fir_cir) , 1, 'L');

$pdf->SetY(142); 
$pdf->SetX(198); 
$pdf->MultiCell(94, 5, utf8_decode('ANESTESIÓLOGO' . ' ' . $fir_anest) , 1, 'L');

$pdf->SetY(147); 
$pdf->SetX(198); 
$pdf->MultiCell(94, 5, utf8_decode('ENFERMERA' . ' ' . $fir_enf) , 1, 'L');





$pdf->SetY(110); 
$pdf->Cell(94, 7, utf8_decode('LUGAR CUERPO MARCADO/NO PROCEDE:'), 1,0, 'L');

if($lug_noproc=='SI'){
$pdf->SetY(110);
$pdf->SetX(93.5);
$lug_noproc= ' X ';
$pdf->Cell(5, 7, utf8_decode($lug_noproc),1, 'C');
}else if ($lug_noproc=='NO PROCEDE') {
  $pdf->SetY(110);
$pdf->SetX(98.7);
$lug_noproc= ' X ';
$pdf->Cell(5, 7, utf8_decode($lug_noproc),1, 'C');
$pdf->SetY(110);
$pdf->SetX(93.5);
$lug_noproc= ' X ';
$pdf->Cell(5, 7, utf8_decode(''),1, 'C');
}



$pdf->SetY(117); 
$pdf->Cell(94, 7, utf8_decode('VERIFICADO EQUIPO Y MEDICACIÓN DE LA ANESTESIA:' ), 1,0, 'L'); 

if($verificado=='SI'){
$pdf->SetY(117);
$pdf->SetX(93.5);
$verificado= ' X ';
$pdf->Cell(5, 7, utf8_decode($verificado),1, 'C');
}else if ($verificado=='NO') {
  $pdf->SetY(117);
$pdf->SetX(98.7);
$verificado= ' X ';
$pdf->Cell(5, 7, utf8_decode($verificado),1, 'C');
}



$pdf->SetY(124); 
$pdf->Cell(94, 7, utf8_decode('PULSIOXIMETRO FUNCIONANDO EN EL PACIENTE:'), 1,0, 'L'); 

if($pulsioximetro=='SI'){
$pdf->SetY(124);
$pdf->SetX(93.5);
$pulsioximetro= ' X ';
$pdf->Cell(5, 7, utf8_decode($pulsioximetro),1, 'C');
}else if ($pulsioximetro=='NO') {
  $pdf->SetY(124);
$pdf->SetX(98.7);
$pulsioximetro= ' X ';
$pdf->Cell(5, 7, utf8_decode($pulsioximetro),1, 'C');

$pdf->SetY(124);
$pdf->SetX(93.5);
$pdf->Cell(5, 7, utf8_decode(''),1, 'C');
}


$pdf->SetY(131); 
$pdf->MultiCell(94, 5, utf8_decode('VERIFICADO INSTRUMENTAL/EQUIPO QUIRÚRGICO/PRÓTESIS:'), 1, 'L'); 


if($ver_inst=='SI'){
$pdf->SetY(131);
$pdf->SetX(93.4);
$ver_inst= ' X ';
$pdf->Cell(5.1, 5, utf8_decode($ver_inst),1, 'C');
}else if ($ver_inst=='NO') {
  $pdf->SetY(131);
$pdf->SetX(98.6);
$ver_inst= ' X ';
$pdf->Cell(5, 5, utf8_decode($ver_inst),1, 'C');
$pdf->SetY(131);
$pdf->SetX(93.4);
$pdf->Cell(5.1, 5, utf8_decode(''),1, 'C');
}


$pdf->SetFont('Arial', 'B', 7);
$pdf->SetY(140); 
$pdf->MultiCell(94, 8, utf8_decode('¿TIENE EL PACIENTE?'), 1, 'C'); 
$pdf->SetY(140);
$pdf->SetX(93.4); /* Inicio */
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(5, 8, utf8_decode('SI '),1, 'C');
$pdf->Cell(5.5, 8, utf8_decode('NO '),1, 'C');



$pdf->SetFont('Arial', '', 7);
$pdf->SetY(148); 
$pdf->MultiCell(94, 6, utf8_decode('¿ALERGIAS CONOCIDAS?'), 1, 'L'); 

if($alerg_con=='SI'){
$pdf->SetY(148);
$pdf->SetX(93.4);
$alerg_con= ' X ';
$pdf->Cell(5, 6, utf8_decode($alerg_con),1, 'C');
}else if ($alerg_con=='NO') {
  $pdf->SetY(148);
$pdf->SetX(98.6);
$alerg_con= ' X ';
$pdf->Cell(5.1, 6, utf8_decode($alerg_con),1, 'C');
}


$pdf->SetY(154); 
$pdf->MultiCell(94, 5, utf8_decode('¿PROFILAXIS ANTIBIÓTICA EN LOS ÚLTIMOS 60 MINUTOS?'), 1, 'L'); 

if($profilaxis=='SI'){
$pdf->SetY(154);
$pdf->SetX(93.4);
$profilaxis= ' X ';
$pdf->Cell(5, 5, utf8_decode($profilaxis),1, 'C');
}else if ($profilaxis=='NO') {
  $pdf->SetY(154);
$pdf->SetX(98.6);
$profilaxis= ' X ';
$pdf->Cell(5.1, 5, utf8_decode($profilaxis),1, 'C');
}

$pdf->SetY(159); 
$pdf->MultiCell(94, 5, utf8_decode('¿DIFICULTAD EN LA VÍA AÉREA/RIESGO DE ASPIRACIÓN?'), 1, 'L'); 

if($dif_via_aerea=='SI'){
$pdf->SetY(159);
$pdf->SetX(93.5);
$dif_via_aerea= ' X ';
$pdf->Cell(5, 5, utf8_decode($dif_via_aerea),1, 'C');
}else if ($dif_via_aerea=='NO') {
  $pdf->SetY(159);
$pdf->SetX(98.7);
$dif_via_aerea=' X ';
$pdf->Cell(5, 5, utf8_decode($dif_via_aerea),1, 'C');
$pdf->SetY(159);
$pdf->SetX(93.5);
$pdf->Cell(5, 5, utf8_decode(''),1, 'C');
}

$pdf->SetY(164); 
$pdf->MultiCell(94, 6.5, utf8_decode('¿PUEDE PRECISAR DE CONCENTRACIÓN DE HEMATÍES? 
¿MENOS DE 500 ML DE SANGRE (7ML/KG EN NIÑOS)?'), 1, 'L'); 

if($con_hematies=='SI'){
$pdf->SetY(164);
$pdf->SetX(93.5);
$con_hematies= ' X ';
$pdf->Cell(5, 13, utf8_decode($con_hematies),1, 'C');
}else if ($con_hematies=='NO') {
  $pdf->SetY(164);
$pdf->SetX(98.7);
$con_hematies= ' X ';
$pdf->Cell(5, 13, utf8_decode($con_hematies),1, 'C');
$pdf->SetY(164);
$pdf->SetX(93.5);

$pdf->Cell(5, 6.5, utf8_decode(''),1, 'C');
}

$sql_med_id = "SELECT id_usua FROM dat_cir_seg_amb WHERE id_cir_seg_amb = $id_cirseg_amb";
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
      $pdf->SetY(-65);
      $pdf->SetFont('Arial', 'B', 6);
      $pdf->Image('../../imgfirma/' . $firma, 230, $pdf->SetY(-55), 30, 10);
      $pdf->Ln(2);
      $pdf->SetX(225);
      $pdf->Cell(50, 3, utf8_decode($pre . '. ' . $app . ' ' . $apm . ' ' . $nom), 0, 0, 'C');
      $pdf->Ln(3);
     $pdf->SetFont('Arial', 'B', 6);
      $pdf->SetX(200);
      $pdf->Cell(100, 3, utf8_decode($cargp . ' ' .'CÉD. PROF. ' . $ced_p), 0, 0, 'C');
      
      $pdf->Ln(3);
       $pdf->SetX(160);
      $pdf->Cell(180, 3, utf8_decode('NOMBRE Y FIRMA DE ENFERMERA'), 0, 0, 'C');
      $pdf->Ln(3);
       $pdf->SetX(160);
      
      $pdf->Cell(180, 3, utf8_decode('COORDINADOR DE LISTA DE VERIFICACIÓN'), 0, 0, 'C');
   

 $pdf->Output();
}