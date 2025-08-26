<?php
require '../../fpdf/fpdf.php';
include '../../conexionbd.php';
$id_atencion = @$_GET['id'];
$id_preop_amb = @$_GET['id_not_preop_amb'];
$id_exp = @$_GET['id_exp'];
$id_med = @$_GET['id_med'];
$sql_preop = "SELECT * FROM dat_not_preop_amb where id_atencion = $id_atencion";
$result_preop = $conexion->query($sql_preop);

while ($row_preop = $result_preop->fetch_assoc()) {
  $id_not_preop_amb = $row_preop['id_not_preop_amb']; 
}
if(isset($id_not_preop_amb)){
    $id_not_preop_amb = $id_not_preop_amb;
  }else{
    $id_not_preop_amb ='sin doc';
  }

if($id_not_preop_amb=="sin doc"){
  echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
                    $(document).ready(function() {
                        swal({
                            title: "NO EXISTE NOTA PREOPERATORIA PARA ESTE PACIENTE", 
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
    $this->Cell(0, 10, utf8_decode('SIMA-030'), 0, 1, 'R');
  }
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



$sql_preop = "SELECT * FROM dat_not_preop_amb pr, dat_ingreso din where pr.id_not_preop_amb=$id_preop_amb and din.id_atencion = pr.id_atencion";
$result_preop = $conexion->query($sql_preop);

while ($row_preop = $result_preop->fetch_assoc()) {
  $tipo_cirugia_preop = $row_preop['tipo_cirugia_preop'];
  $cama_preop = $row_preop['cama_preop'];
  $p_sistolica = $row_preop['ta_sist'];
  $p_diastolica = $row_preop['ta_diast'];
  $f_card = $row_preop['frec_card'];
  $f_resp = $row_preop['frec_resp'];
  $sat_oxigeno = $row_preop['sat_oxi'];
  $temp = $row_preop['preop_temp'];
  $peso = $row_preop['preop_peso'];
  $talla = $row_preop['preop_talla'];

  $cabeza = $row_preop['cabeza'];
  $cuello = $row_preop['cuello'];
  $torax = $row_preop['torax'];
  $abdomen = $row_preop['abdomen'];
  $extrem = $row_preop['extrem'];
  $colum_vert = $row_preop['colum_vert'];
  $resumen_clin = $row_preop['resumen_clin'];
  $diag_preop = $row_preop['diag_preop'];
  $beneficios = $row_preop['beneficios'];
  $result_lab_gab = $row_preop['result_lab_gab'];
  $pronostico = $row_preop['pronostico'];
  $riesgos = $row_preop['riesgos'];
  $cuidados = $row_preop['cuidados'];
  $tipo_inter_plan = $row_preop['tipo_inter_plan'];
  $fecha_cir = $row_preop['fecha_cir'];
  $hora_cir = $row_preop['hora_cir'];
  $tiempo_estimado = $row_preop['tiempo_estimado'];
  $nom_medi_cir = $row_preop['nom_medi_cir'];
  $anestesia_sug = $row_preop['anestesia_sug'];
  $sangrado_esp = $row_preop['sangrado_esp'];
  $observ = $row_preop['observ'];
  $fecha_preop = $row_preop['fecha_preop'];
  $tipo_a = $row_preop['tipo_a'];
  $fecha_ing = $row_preop['fecha'];
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
$pdf->Cell(120, 5, utf8_decode('NOTA PREOPERATORIA'), 0, 0, 'C');
$pdf->SetFont('Arial', '', 8);
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
$pdf->Cell(25, 4, utf8_decode($tipo_cirugia_preop), 'B',0,'C');
$pdf->Ln(5);


$sql_sig ="select * from signos_vitales WHERE id_atencion=$id_atencion ORDER by id_sig DESC LIMIT 1";
$result_sig = $conexion->query($sql_sig);

while ($row_sig = $result_sig->fetch_assoc()) {
 $p_sistolica=$row_sig['p_sistol'];
 $p_diastolica=$row_sig['p_diastol'];
 $f_card=$row_sig['fcard'];
 $f_resp=$row_sig['fresp'];
 $temp=$row_sig['temper'];
 $sat_oxigeno=$row_sig['satoxi'];
 $peso=$row_sig['peso'];
 $talla=$row_sig['talla'];
 $niv_dolor=$row_sig['niv_dolor'];
}


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

$pdf->Cell(20, 3, utf8_decode('ESCALA EVA: ' .$niv_dolor), 1, 'L');
$pdf->Ln(6);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 3, utf8_decode('RESUMEN DE INTERROGATORIO:'), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(158, 3, utf8_decode( $resumen_clin), 1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 3, utf8_decode('EXPLORACIÓN FÍSICA:'), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(158, 3, utf8_decode( $beneficios), 1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 3, utf8_decode('RESULTADOS LAB. Y GABINETE: '), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(158, 3, utf8_decode($result_lab_gab), 1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 3, utf8_decode('DIAGNÓSTICO PREOPERATORIO: '), 0,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(158, 3, utf8_decode($diag_preop), 1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 3, utf8_decode('PRONÓSTICO: '), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(158, 3, utf8_decode($pronostico), 1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 3, utf8_decode('RIESGOS QUIRÚRGICOS: '), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(158, 3, utf8_decode($riesgos), 1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 3, utf8_decode('PLAN TERAPÉUTICO PREOPER: '), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(158, 3, utf8_decode($cuidados), 1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 3, utf8_decode('CIRUGÍA PROGRAMADA: '), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(105, 3, utf8_decode($tipo_inter_plan), 1, 'L');
$pdf->SetFont('Arial', 'B', 6);

$date=date_create($fecha_cir);
$fecha_cir=date_format($date,"d/m/Y");
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(53, 3, utf8_decode('PROGRAMADA: '.$fecha_cir.' HORA: '.$hora_cir), 1, 'L');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 3, utf8_decode('NOMBRE DEL MÉDICO CIRUJANO: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(105, 3, utf8_decode($nom_medi_cir), 1, 'L');

$pdf->Cell(53, 3, utf8_decode('TIEMPO QUIRÚRGICO ESTIMADO: '.$tiempo_estimado), 1, 'L');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 3, utf8_decode('ANESTESIA SUGERIDA: '), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(158, 3, utf8_decode($anestesia_sug), 1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 3, utf8_decode('SANGRADO ESPERADO (ML): '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(158, 3, utf8_decode($sangrado_esp), 1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 3, utf8_decode('OBSERVACIONES: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(158, 3, utf8_decode($observ), 1, 'L');
$pdf->Ln(1);

$sql_med_id = "SELECT id_usua FROM dat_not_preop_amb WHERE id_not_preop_amb = $id_preop_amb ";

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
      $pdf->SetY(190);
      $pdf->SetX(10);
      $pdf->SetFont('Arial', 'B', 7);
      $pdf->Cell(190, 3, utf8_decode('MARCAJE QUIRÚRGICO'), 0,0, 'C');

      $pdf->Image('../../img/marcaje_qx.jpg', 70, 193, 73);

      $pdf->SetFont('Arial', 'B', 7);
      $pdf->Image('../../imgfirma/' . $firma, 95, 255, 30);
    
      $pdf->SetY(266);
      $pdf->Cell(200, 3, utf8_decode($pre . '. ' . $app . ' ' . $apm . ' ' . $nom), 0, 0, 'C');
      $pdf->Ln(3);
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->Cell(200, 3, utf8_decode($cargp . ' ' . 'CÉD. PROF. ' . $ced_p), 0, 0, 'C');
      $pdf->Ln(3);
      $pdf->Cell(200, 3, utf8_decode('NOMBRE Y FIRMA DEL MÉDICO'), 0, 0, 'C');
 $pdf->Output();
}