<?php
require '../../fpdf/fpdf.php';
include '../../conexionbd.php';
$id_atencion = @$_GET['id'];
$id_inquir_amb = @$_GET['id_not_inquir_amb'];
$id_med = @$_GET['id_med'];

$sql_inter = "SELECT * FROM dat_not_inquir_amb where id_atencion = $id_atencion";
$result_inter = $conexion->query($sql_inter);

while ($row_inter = $result_inter->fetch_assoc()) {
$id_not_inquir_amb = $row_inter['id_not_inquir_amb'];
}

if(isset($id_not_inquir_amb)){
    $id_not_inquir_amb = $id_not_inquir_amb;
  }else{
    $id_not_inquir_amb ='sin doc';
  }

if($id_not_inquir_amb=="sin doc"){
  echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
                    $(document).ready(function() {
                        swal({
                            title: "NO EXISTE NOTA DE INTERVENCIÓN QUIRÚRGICA PARA ESTE PACIENTE", 
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

$sql_pac = "SELECT * FROM paciente p, dat_ingreso di where di.id_atencion = $id_atencion and di.Id_exp=p.Id_exp";
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
  $tipo_sangre = $row_pac['tip_san'];
  $tipo_a = $row_pac['tipo_a'];
}


$sql_inter = "SELECT * FROM dat_not_inquir_amb iq, dat_ingreso din where iq.id_not_inquir_amb=$id_inquir_amb and din.id_atencion = iq.id_atencion";
$result_inter = $conexion->query($sql_inter);

while ($row_inter = $result_inter->fetch_assoc()) {
$tipo_intervencion = $row_inter['tipo_intervencion'];
$fecha = $row_inter['fecha'];
$hora_solicitud = $row_inter['hora_solicitud'];
$intervencion_sol = $row_inter['intervencion_sol'];

$quirofano = $row_inter['quirofano'];
$reserva = $row_inter['reserva'];
$local = $row_inter['local'];
$regional = $row_inter['regional'];
$general = $row_inter['general'];

  $hb = $row_inter['hb'];
  $hto = $row_inter['hto'];
  $peso = $row_inter['peso'];
  
  $inst_necesario = $row_inter['inst_necesario'];
  $medmat_necesario = $row_inter['medmat_necesario'];
  $nom_jefe_serv = $row_inter['nom_jefe_serv'];
 
  $sala = $row_inter['sala'];
  $jefe_cirugia = $row_inter['jefe_cirugia'];
  $fecha_cir = $row_inter['fecha'];
  $inicio = $row_inter['inicio'];
  $termino = $row_inter['termino'];
  $diag_postop = $row_inter['diag_postop'];
  $cir_realizada = $row_inter['cir_realizada'];
  $trans = $row_inter['trans'];
  $posto = $row_inter['posto'];
  $perd_hema = $row_inter['perd_hema'];
  $gasas = $row_inter['gasas'];
  $compresas = $row_inter['compresas'];
  $accident_incident = $row_inter['accident_incident'];
  $anestesia_admin = $row_inter['anestesia_admin'];
  $anestesia_dur = $row_inter['anestesia_dur'];
  $realizada_por = $row_inter['realizada_por'];
  $cirujano = $row_inter['cirujano'];
  $prim_ayudante = $row_inter['prim_ayudante'];
  $seg_ayudante = $row_inter['seg_ayudante'];
  $ter_ayudante = $row_inter['ter_ayudante'];
  $anestesiologo = $row_inter['anestesiologo'];
  $resid_anest = $row_inter['resid_anest'];
  $circulante = $row_inter['circulante'];
  $instrumentista = $row_inter['instrumentista'];
  $quir = $row_inter['quir'];
  $hora_llegada_quir = $row_inter['hora_llegada_quir'];


  $hora_salida_quir = $row_inter['hora_salida_quir'];
  $hora_entrada_recup = $row_inter['hora_entrada_recup'];
  $hora_salida_recup = $row_inter['hora_salida_recup'];
  $nota_preop = $row_inter['nota_preop'];
  $estado_postop = $row_inter['estado_postop'];
  $comentario_final = $row_inter['comentario_final'];
  $descripcion_op = $row_inter['descripcion_op'];
  $nombre_med_cir = $row_inter['nombre_med_cir'];
  $plan_ter = $row_inter['plan_ter'];
  $fecha_ing = $row_inter['fecha'];
}

$pdf = new PDF('P');
  $pdf->AliasNbPages();
  $pdf->AddPage();


$pdf->SetFont('Arial', 'B', 10);
$pdf->SetX(50);
$pdf->Cell(120, 5, utf8_decode('DESCRIPCIÓN DE INTERVENCIÓN QUIRÚRGICA'), 0, 0, 'C');
$pdf->SetFont('Arial', '', 6);
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("d/m/Y");
$pdf->Cell(35, 5, 'Toluca, Mex, ' . $fecha_actual, 0, 1, 'R');

$pdf->SetDrawColor(0, 0, 0);
$pdf->Line(8, 50, 207, 50);
$pdf->Line(8, 50, 8, 280);
$pdf->Line(207, 50, 207, 280);
$pdf->Line(8, 280, 207, 280);
$pdf->Ln(2);
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
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(29, 4, utf8_decode(' TIPO DE CIRUGÍA: '), 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(25, 4, utf8_decode($tipo_intervencion), 'B',0,'C');

$sql_sig1 ="SELECT * FROM dat_not_preop_amb WHERE id_atencion=$id_atencion order by id_not_preop_amb DESC LIMIT 1";
$result_sig1 = $conexion->query($sql_sig1);
while ($row_sig1 = $result_sig1->fetch_assoc()) {
 $p_sistolica=$row_sig1['ta_sist'];
 $p_diastolica=$row_sig1['ta_diast'];
 $f_card=$row_sig1['frec_card'];
 $f_resp=$row_sig1['frec_resp'];
 $temp=$row_sig1['preop_temp'];
 $sat_oxigeno=$row_sig1['sat_oxi'];
 $peso=$row_sig1['preop_peso'];
 $talla=$row_sig1['preop_talla'];
 $diag_preop = $row_sig1['diag_preop'];
 $cirugia_prog = $row_sig1['tipo_inter_plan'];
 $fecha_progra = $row_sig1['fecha_cir'];
 $hora_progra = $row_sig1['hora_cir'];

}

$sql_sig ="select * from signos_vitales WHERE id_atencion=$id_atencion ORDER by id_sig DESC LIMIT 1";
$result_sig = $conexion->query($sql_sig);

while ($row_sig = $result_sig->fetch_assoc()) {
 $peso=$row_sig['peso'];
 $talla=$row_sig['talla'];
 $niv_dolor=$row_sig['niv_dolor'];
}

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(195, 3, utf8_decode('SIGNOS VITALES'), 0,0, 'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(31, 3, utf8_decode('PRESIÓN ARTERIAL: ' .$p_sistolica.'/'.$p_diastolica), 1, 'L');
$pdf->Cell(26, 3, utf8_decode('FREC. CARDIACA: ' .$f_card), 1, 'L');
$pdf->Cell(30, 3, utf8_decode('FREC. RESPIRATORIA: ' .$f_resp), 1, 'L');
$pdf->Cell(26, 3, utf8_decode('TEMPERATURA: ' .$temp), 1, 'L');
$pdf->Cell(32, 3, utf8_decode('SATURACIÓN OXIGENO: ' .$sat_oxigeno), 1, 'L');
$pdf->Cell(15, 3, utf8_decode('PESO: ' .$peso), 1, 'L');
$pdf->Cell(15, 3, utf8_decode('TALLA: ' .$talla), 1, 'L');

if(isset($niv_dolor)){
$pdf->Cell(20, 3, utf8_decode('ESCALA EVA: ' .$niv_dolor), 1, 'L');
}else{
  $pdf->Cell(20, 3, utf8_decode('ESCALA EVA: '), 1, 'L');
}
//$pdf->Cell(20, 3, utf8_decode('ESCALA EVA: ' .$niv_dolor), 1, 'L');
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(40, 3, utf8_decode('DIAGNÓSTICO PREOPERATORIO: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(155, 3, utf8_decode($diag_preop), 1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(15, 3, utf8_decode('SANGRE EN: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(25, 3, utf8_decode('QUIRÓFANO: ' .$quirofano . ' ML'), 1,'C');
$pdf->Cell(21, 3, utf8_decode('RESERVA: ' .$reserva. ' ML'), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(15, 3, utf8_decode('ANESTESIA: '), 1, 'C');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(21, 3, utf8_decode('LOCAL: '. $local), 1, 'C');
$pdf->Cell(19, 3, utf8_decode('REGIONAL: ' . $regional), 1, 'C');
$pdf->Cell(19, 3, utf8_decode('GENERAL: '. $general), 1,'C');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(24, 3, utf8_decode('GRUPO SANGUÍNEO:'), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(36, 3, utf8_decode($tipo_sangre), 1, 'L');
$pdf->Ln(1);



$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(195, 3, utf8_decode('PROGRAMACIÓN EN QUIRÓFANO: '), 0,0, 'L');
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(40, 3, utf8_decode('CIRUGÍA PROGRAMADA'), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(95, 3, utf8_decode($cirugia_prog), 1, 'L');

$fecha_progra=date_create($fecha_progra);
$fecha_progra=date_format($fecha_progra,"d/m/Y");
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(18, 3, utf8_decode('PROGRAMADA:'), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(42, 3, utf8_decode($fecha_progra. ' HORA:' .$hora_progra), 1, 0, 'L');
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(40, 3, utf8_decode('DIAGNÓSTICO POSTOPERATORIO: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(155, 3, utf8_decode($diag_postop), 1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(40, 3, utf8_decode('CIRUGÍA REALIZADA: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(95, 3, utf8_decode($cir_realizada), 1, 'L');
$fecha_cir=date_create($fecha_cir);
$fecha_cir=date_format($fecha_cir,"d/m/Y");
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(18, 3, utf8_decode('REALIZADA:'), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(42, 3, utf8_decode($fecha_cir.' HORARIO:'. $inicio.' - '.$termino), 1, 0, 'L');
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(40, 3, utf8_decode('ESTUDIOS DE PATOLOGÍA: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(78, 3, utf8_decode('TRANSOPERATORIOS: '.$trans), 1, 'L');
$pdf->Cell(77, 3, utf8_decode('POSTOPERATORIOS: '.$posto), 1, 'L');
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(40, 3, utf8_decode('CUENTA DE GASAS Y COMPRESAS: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(25, 3, utf8_decode($inst_necesario), 1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(22, 3, utf8_decode('OBSERVACIONES: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(108, 3, utf8_decode($medmat_necesario), 1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(40, 3, utf8_decode('ACCIDENTES O INCIDENTES: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(155, 3, utf8_decode($accident_incident), 1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(40, 3, utf8_decode('COMPLICACIONES: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(155, 3, utf8_decode($realizada_por), 1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(40, 3, utf8_decode('PÉRDIDA HEMÁTICA (ML): '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(12, 3, utf8_decode($perd_hema.' ML'), 1, 'L');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(33, 3, utf8_decode('ANESTESIA ADMINISTRADA: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(85, 3, utf8_decode($anestesia_admin), 1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(14, 3, utf8_decode('DURACIÓN: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(11, 3, utf8_decode($anestesia_dur), 1,0, 'L');
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(40, 3, utf8_decode('CIRUJANO'), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(155, 3, utf8_decode($cirujano), 1, 'L');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(65, 3, utf8_decode('PRIMER AYUDANTE '), 1,0, 'C');
$pdf->Cell(65, 3, utf8_decode('SEGUNDO AYUDANTE'), 1,0, 'C');
$pdf->Cell(65, 3, utf8_decode('TERCER AYUDANTE'), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(65, 3, $prim_ayudante, 1,0, 'C');
$pdf->Cell(65, 3, $seg_ayudante, 1,0, 'C');
$pdf->Cell(65, 3, $ter_ayudante, 1,0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(65, 3, utf8_decode('ANESTESIÓLOGO '), 1,0, 'C');
$pdf->Cell(65, 3, utf8_decode('CIRCULANE '), 1,0, 'C');
$pdf->Cell(65, 3, utf8_decode('INSTRUMENTISTA'), 1,0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(65, 3, $anestesiologo, 1,0, 'C');
$pdf->Cell(65, 3, $circulante, 1,0, 'C');
$pdf->Cell(65, 3, $instrumentista, 1,0, 'C');
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(99, 3, utf8_decode('QUIRÓFANO '), 0, 0, 'C');
$pdf->Cell(99, 3, utf8_decode('SALA DE RECUPERACIÓN '), 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(39, 3, utf8_decode('SALA '), 1,0, 'C');
$pdf->Cell(39, 3, utf8_decode('HORA LLEGADA '), 1,0, 'C');
$pdf->Cell(39, 3, utf8_decode('HORA SALIDA '), 1,0, 'C');
$pdf->Cell(39, 3, utf8_decode('HORA LLEGADA'), 1,0, 'C');
$pdf->Cell(39, 3, utf8_decode('HORA SALIDA '), 1,1, 'C');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(39, 3, $quir, 1,0, 'C');
$pdf->Cell(39, 3, $hora_llegada_quir, 1,0, 'C');
$pdf->Cell(39, 3, $hora_salida_quir, 1,0, 'C');
$pdf->Cell(39, 3, $hora_entrada_recup, 1,0, 'C');
$pdf->Cell(39, 3, $hora_salida_recup, 1,0, 'C');
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 6);
$pdf->MultiCell(200, 5, utf8_decode('HALLAZGOS-TÉCNICA (DESCRIPCIÓN QUIRÚRGICA)-COMPLICACIONES Y OBSERVACIONES: '), 0, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(40, 3, utf8_decode('NOTA OPERATORIA:'), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(155, 3, utf8_decode($nota_preop), 1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 5.5);
$pdf->Cell(40, 3, utf8_decode('ESTADO POSTOPERATORIO INMEDIATO '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(155, 3, utf8_decode($estado_postop), 1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 5.5);
$pdf->Cell(40, 3, utf8_decode('COMENTARIO FINAL Y PRONÓSTICO: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(155, 3, utf8_decode($comentario_final), 1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 5.5);
$pdf->Cell(40, 3, utf8_decode('DESCRIBIÓ LA OPERACIÓN: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(58, 3, utf8_decode($descripcion_op), 1, 'L');

$pdf->SetFont('Arial', 'B', 5.5);
$pdf->Cell(40, 3, utf8_decode('NOMBRE DEL MÉDICO CIRUJANO:'), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(57, 3, utf8_decode($nombre_med_cir), 1, 'L');
$pdf->Ln(3);


$sql_med_id = "SELECT id_usua FROM dat_not_inquir_amb WHERE id_not_inquir_amb = $id_inquir_amb ";

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
      $pdf->SetY(-80);
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->Image('../../imgfirma/' . $firma, 95, 245, 30);
    
       $pdf->SetY(258);
      $pdf->Cell(200, 4, utf8_decode($pre . '. ' . $app . ' ' . $apm . ' ' . $nom), 0, 0, 'C');
      $pdf->Ln(4);
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->Cell(200, 4, utf8_decode($cargp . ' ' . 'CÉD. PROF. ' . $ced_p), 0, 0, 'C');
      $pdf->Ln(4);
      $pdf->Cell(200, 4, utf8_decode('NOMBRE Y FIRMA DEL MÉDICO'), 0, 0,  'C');
      $pdf->SetX(170);
      $pdf->Cell(20, 10, utf8_decode('SIMA-032'), 0, 0, 'R');
      $pdf->ln(10);

$pdf->Ln(20);

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetX(50);
$pdf->Cell(120, 5, utf8_decode('NOTA POSTOPERATORIA'), 0, 0, 'C');
$pdf->SetFont('Arial', '', 6);
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("d/m/Y");
$pdf->Cell(35, 5, 'Toluca, Mex, ' . $fecha_actual, 0, 1, 'R');

$pdf->SetDrawColor(0, 0, 0);
$pdf->Line(8, 50, 207, 50);
$pdf->Line(8, 50, 8, 280);
$pdf->Line(207, 50, 207, 280);
$pdf->Line(8, 280, 207, 280);
$pdf->Ln(2);
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
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(29, 4, utf8_decode(' TIPO DE CIRUGÍA: '), 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(25, 4, utf8_decode($tipo_intervencion), 'B',0,'C');

$sql_sig1 ="SELECT * FROM dat_not_preop_amb WHERE id_atencion=$id_atencion order by id_not_preop_amb DESC LIMIT 1";
$result_sig1 = $conexion->query($sql_sig1);
while ($row_sig1 = $result_sig1->fetch_assoc()) {
 $p_sistolica=$row_sig1['ta_sist'];
 $p_diastolica=$row_sig1['ta_diast'];
 $f_card=$row_sig1['frec_card'];
 $f_resp=$row_sig1['frec_resp'];
 $temp=$row_sig1['preop_temp'];
 $sat_oxigeno=$row_sig1['sat_oxi'];
 $peso=$row_sig1['preop_peso'];
 $talla=$row_sig1['preop_talla'];
 $diag_preop = $row_sig1['diag_preop'];
 $cirugia_prog = $row_sig1['tipo_inter_plan'];
 $fecha_progra = $row_sig1['fecha_cir'];
 $hora_progra = $row_sig1['hora_cir'];
}

$sql_sig ="select * from signos_vitales WHERE id_atencion=$id_atencion ORDER by id_sig DESC LIMIT 1";
$result_sig = $conexion->query($sql_sig);

while ($row_sig = $result_sig->fetch_assoc()) {
 $peso=$row_sig['peso'];
 $talla=$row_sig['talla'];
 $niv_dolor=$row_sig['niv_dolor'];
}

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(195, 3, utf8_decode('SIGNOS VITALES'), 0,0, 'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(31, 3, utf8_decode('PRESIÓN ARTERIAL: ' .$p_sistolica.'/'.$p_diastolica), 1, 'L');
$pdf->Cell(26, 3, utf8_decode('FREC. CARDIACA: ' .$f_card), 1, 'L');
$pdf->Cell(30, 3, utf8_decode('FREC. RESPIRATORIA: ' .$f_resp), 1, 'L');
$pdf->Cell(26, 3, utf8_decode('TEMPERATURA: ' .$temp), 1, 'L');
$pdf->Cell(32, 3, utf8_decode('SATURACIÓN OXIGENO: ' .$sat_oxigeno), 1, 'L');
$pdf->Cell(15, 3, utf8_decode('PESO: ' .$peso), 1, 'L');
$pdf->Cell(15, 3, utf8_decode('TALLA: ' .$talla), 1, 'L');
if(isset($niv_dolor)){
$pdf->Cell(20, 3, utf8_decode('ESCALA EVA: ' .$niv_dolor), 1, 'L');
}else{
  $pdf->Cell(20, 3, utf8_decode('ESCALA EVA: '), 1, 'L');
}
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(40, 3, utf8_decode('DIAGNÓSTICO PREOPERATORIO: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(155, 3, utf8_decode($diag_preop), 1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(40, 3, utf8_decode('DIAGNÓSTICO POSTOPERATORIO: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(155, 3, utf8_decode($diag_postop), 1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(40, 3, utf8_decode('CIRUGÍA PROGRAMADA: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(155, 3, utf8_decode($cirugia_prog), 1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(40, 3, utf8_decode('CIRUGÍA REALIZADA: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(155, 3, utf8_decode($cir_realizada), 1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(40, 3, utf8_decode('CIRUJANO'), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(155, 3, utf8_decode($cirujano), 1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(65, 3, utf8_decode('PRIMER AYUDANTE '), 1,0, 'C');
$pdf->Cell(65, 3, utf8_decode('SEGUNDO AYUDANTE'), 1,0, 'C');
$pdf->Cell(65, 3, utf8_decode('TERCER AYUDANTE'), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(65, 3, $prim_ayudante, 1,0, 'C');
$pdf->Cell(65, 3, $seg_ayudante, 1,0, 'C');
$pdf->Cell(65, 3, $ter_ayudante, 1,0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(65, 3, utf8_decode('ANESTESIÓLOGO '), 1,0, 'C');
$pdf->Cell(65, 3, utf8_decode('CIRCULANE '), 1,0, 'C');
$pdf->Cell(65, 3, utf8_decode('INSTRUMENTISTA'), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(65, 3, $anestesiologo, 1,0, 'C');
$pdf->Cell(65, 3, $circulante, 1,0, 'C');
$pdf->Cell(65, 3, $instrumentista, 1,0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(40, 3, utf8_decode('PÉRDIDA HEMÁTICA (ML): '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(12, 3, utf8_decode($perd_hema.' ML'), 1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(32, 3, utf8_decode('ACCIDENTES O INCIDENTES: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(111, 3, utf8_decode($accident_incident), 1, 'L');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(40, 3, utf8_decode('CUENTA DE GASAS Y COMPRESAS: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(25, 3, utf8_decode($inst_necesario), 1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(22, 3, utf8_decode('OBSERVACIONES: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(108, 3, utf8_decode($medmat_necesario), 1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->MultiCell(200, 5, utf8_decode('HALLAZGOS-TÉCNICA (DESCRIPCIÓN QUIRÚRGICA)-COMPLICACIONES Y OBSERVACIONES:'), 0, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(40, 3, utf8_decode('NOTA OPERATORIA:'), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(155, 3, utf8_decode($nota_preop), 1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(40, 3, utf8_decode('PLAN TERAPÉUTICO: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(155, 3, utf8_decode($plan_ter), 1, 'L');

$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(40, 3, utf8_decode('ESTUDIOS DE PATOLOGÍA: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(78, 3, utf8_decode('TRANSOPERATORIOS: '.$trans), 1, 'L');
$pdf->Cell(77, 3, utf8_decode('POSTOPERATORIOS: '.$posto), 1, 'L');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 5.5);
$pdf->Cell(40, 3, utf8_decode('ESTADO POSTOPERATORIO INMEDIATO '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(155, 3, utf8_decode($estado_postop), 1, 'L');

$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(40, 3, utf8_decode('COMENTARIO FINAL Y PRONÓSTICO: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(155, 3, utf8_decode($comentario_final), 1, 'L');

$sql_med_id = "SELECT id_usua FROM dat_not_inquir_amb WHERE id_not_inquir_amb = $id_inquir_amb ";

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
       $pdf->SetY(-80);
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->Image('../../imgfirma/' . $firma, 95, 245, 30);
    
       $pdf->SetY(258);
      $pdf->Cell(200, 4, utf8_decode($pre . '. ' . $app . ' ' . $apm . ' ' . $nom), 0, 0, 'C');
      $pdf->Ln(4);
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->Cell(200, 4, utf8_decode($cargp . ' ' . 'CÉD. PROF. ' . $ced_p), 0, 0, 'C');
      $pdf->Ln(4);
      $pdf->Cell(200, 4, utf8_decode('NOMBRE Y FIRMA DEL MÉDICO'), 0, 0,  'C');
      $pdf->SetX(170);
      $pdf->Cell(20, 10, utf8_decode('SIMA-033'), 0, 0, 'R');
      $pdf->ln(10);

 $pdf->Output();
}


