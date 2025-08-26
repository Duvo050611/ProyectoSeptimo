<?php
require '../../fpdf/fpdf.php';
include '../../conexionbd.php';
$id_med = @$_GET['id_med'];
$id_segevol_amb= @$_GET['id_seg_evol_amb'];
$id_exp = @$_GET['id_exp'];
$id_atencion = @$_GET['id_atencion'];
$sql_seg = "SELECT * FROM dat_seg_evol_amb  where id_atencion = $id_atencion";
$result_seg = $conexion->query($sql_seg);

while ($row_seg = $result_seg->fetch_assoc()) {
  $id_seg_evol_amb = $row_seg['id_seg_evol_amb'];
}

if(isset($id_seg_evol_amb)){
    $id_seg_evol_amb = $id_seg_evol_amb;
  }else{
    $id_seg_evol_amb ='sin doc';
  }

if($id_seg_evol_amb=="sin doc"){
  echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
                    $(document).ready(function() {
                        swal({
                            title: "NO EXISTE EVALUACIÓN ANTES DEL PROCEDIMIENTO ANESTÉSICO PARA ESTE PACIENTE", 
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
    $this->Cell(0, 10, utf8_decode('SIMA-035'), 0, 1, 'R');
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
$pdf->Cell(120, 5, utf8_decode('EVALUACIÓN ANTES DEL PROCEDIMIENTO ANESTÉSICO'), 0, 0, 'C');
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


//consulta observacion
$sql_seg = "SELECT * FROM dat_seg_evol_amb  where id_seg_evol_amb = $id_segevol_amb";
$result_seg = $conexion->query($sql_seg);

while ($row_seg = $result_seg->fetch_assoc()) {
  $fecha2 = $row_seg['fecha2'];
  $hora2 = $row_seg['hora2'];
  $ansed = $row_seg['ansed'];  
  $diaproc = $row_seg['diaproc'];  
  $sist = $row_seg['sist'];  
  $diast = $row_seg['diast']; 
  $freccard = $row_seg['freccard']; 
  $frecresp = $row_seg['frecresp']; 
  $temp = $row_seg['temp'];  
  $spo2 = $row_seg['spo2'];

 $med_pre = $row_seg['med_pre'];
  $dosis = $row_seg['dosis'];
  $via = $row_seg['via'];  
  $fechamedi = $row_seg['fechamedi'];  
  $horamedi = $row_seg['horamedi'];  
  $efect = $row_seg['efect']; 

  $med_pre2 = $row_seg['med_pre2']; 
  $dosis2 = $row_seg['dosis2']; 
  $via2 = $row_seg['via2'];  
  $fechamedi2 = $row_seg['fechamedi2'];
  $horamedi2 = $row_seg['horamedi2'];
  $efect2 = $row_seg['efect2'];
  $hora_ver = $row_seg['hora_ver'];  
  $apan = $row_seg['apan'];  
  $vent = $row_seg['vent'];  
  $fuen = $row_seg['fuen']; 
  $ecg = $row_seg['ecg']; 
  $circ = $row_seg['circ']; 
  $para = $row_seg['para'];  
  $fuent = $row_seg['fuent'];


  $pani = $row_seg['pani']; 
  $fugas = $row_seg['fugas']; 
  $flujo = $row_seg['flujo'];  
  $spo = $row_seg['spo'];
  $cal = $row_seg['cal'];
  $vapo = $row_seg['vapo'];
  $co2 = $row_seg['co2'];  
  $ana = $row_seg['ana'];  
  $indice = $row_seg['indice'];  
  $bomba = $row_seg['bomba']; 
  $moni = $row_seg['moni']; 
  $obser = $row_seg['obser']; 
  $noanest = $row_seg['noanest'];  
 

}
//termino observacion

$pdf->SetFont('Arial', 'B', 6);
$pdf->Ln(5);

$pdf->Cell(95, 4, utf8_decode('FECHA: ' .$fecha2.' HORA: ' .$hora2), 0, 'L');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(180, 4, utf8_decode('SE CORROBORÓ LA IDENTIFICACIÓN DEL PACIENTE, SU ESTADO ACTUAL, EL DIAGNÓSTICO Y EL PROCEDMIENTO PROGRAMADO ANTES DEL INICIO DE LA ANESTESIA: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(15, 4, utf8_decode($diaproc), 1, 'C');
$pdf->Ln(2);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(195, 3, utf8_decode('SIGNOS VITALES'), 0,0, 'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(37, 3, utf8_decode('PRESIÓN ARTERIAL: ' .$sist.'/'.$diast), 1, 'L');
$pdf->Cell(40, 3, utf8_decode('FRECUENCIA CARDIACA: ' .$freccard), 1, 'L');
$pdf->Cell(40, 3, utf8_decode('FRECUENCIA RESPIRATORIA: ' .$frecresp), 1, 'L');
$pdf->Cell(38, 3, utf8_decode('TEMPERATURA: ' .$temp), 1, 'L');
$pdf->Cell(40, 3, utf8_decode('SATURACIÓN DE OXÍGENO: ' .$spo2), 1, 'L');
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(37, 4, utf8_decode('MEDICACIÓN PREANESTÉSICA'), 1,0, 'C');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(25, 4, utf8_decode('DOSIS'), 1,0, 'C');
$pdf->Cell(25, 4, utf8_decode('VÍA' ), 1,0, 'C');
$pdf->Cell(25, 4, utf8_decode('FECHA' ), 1,0, 'C');
$pdf->Cell(25, 4, utf8_decode('HORA' ), 1,0, 'C');
$pdf->Cell(58, 4, utf8_decode('EFECTO'), 1,0, 'C');
$pdf->Ln(4);
$pdf->Cell(37, 4, utf8_decode($med_pre), 1,0, 'C');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(25, 4, utf8_decode($dosis), 1,0, 'C');
$pdf->Cell(25, 4, utf8_decode($via), 1,0, 'C');
$pdf->Cell(25, 4, utf8_decode($fechamedi), 1,0, 'C');
$pdf->Cell(25, 4, utf8_decode($horamedi), 1,0, 'C');
$pdf->Cell(58, 4, utf8_decode($efect), 1,0, 'C');
$pdf->Ln(4);
$pdf->Cell(37, 6, utf8_decode($med_pre2), 1,0, 'C');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(25, 6, utf8_decode($dosis2), 1,0, 'C');
$pdf->Cell(25, 6, utf8_decode($via2), 1,0, 'C');
$pdf->Cell(25, 6, utf8_decode($fechamedi2), 1,0, 'C');
$pdf->Cell(25, 6, utf8_decode($horamedi2), 1,0, 'C');
$pdf->Cell(58, 6, utf8_decode($efect2), 1,0, 'C');

$pdf->Ln(7);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(140, 6, utf8_decode('VERIFICACIÓN DEL EQUIPO Y MONITOREO ANTES DE LA ANESTESIA'), 0,0, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(55, 6, utf8_decode('HORA: '.$hora_ver), 0,0, 'C');

$pdf->Ln(6); 
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(37, 6, utf8_decode('APARATO DE ANESTESIA: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(11, 6, utf8_decode($apan), 1,0, 'C');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 6, utf8_decode('CIRCUITO: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(11, 6, utf8_decode($circ), 1,0, 'C');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 6, utf8_decode('FUGAS: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(11, 6, utf8_decode($fugas), 1,0, 'C');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 6, utf8_decode('CAL SONDA: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(11, 6, utf8_decode($cal), 1,0, 'C');

$pdf->Ln(6); 
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(37, 6, utf8_decode('VENTILADOR: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(11, 6, utf8_decode($vent), 1,0, 'C');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 6, utf8_decode('PARÁMETROS VENTILATORIOS: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(11, 6, utf8_decode($para), 1,0, 'C');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 6, utf8_decode('FLUJÓMETRO:'), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(11, 6, utf8_decode($flujo), 1,0, 'C');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 6, utf8_decode('VAPORIZADORES: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(11, 6, utf8_decode($vapo), 1,0, 'C');

$pdf->Ln(6); 
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(37, 6, utf8_decode('ECG: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(11, 6, utf8_decode($ecg), 1,0, 'C');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 6, utf8_decode('PANI: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(11, 6, utf8_decode($pani), 1,0, 'C');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 6, utf8_decode('SPO2:'), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(11, 6, utf8_decode($spo), 1,0, 'C');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 6, utf8_decode('CO2FE: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(11, 6, utf8_decode($co2), 1,0, 'C');


$pdf->Ln(6); 
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(37, 6, utf8_decode('FUENTE DE O2 Y ALARMAS: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(11, 6, utf8_decode($fuen), 1,0, 'C');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 6, utf8_decode('FUENTE DE ENERGÍA Y ALARMAS: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(11, 6, utf8_decode($fuent), 1,0, 'C');


$pdf->Ln(7);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(140, 6, utf8_decode('OPCIONALES'), 0,0, 'L');
$pdf->Ln(6); 

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(37, 6, utf8_decode('ANALIZADOR DE GASES RESP: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(11, 6, utf8_decode($ana), 1,0, 'C');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 6, utf8_decode('ÍNDICE BIESPECTRAL: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(11, 6, utf8_decode($indice), 1,0, 'C');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 6, utf8_decode('BOMBA DE INFUSIÓN:'), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(11, 6, utf8_decode($bomba), 1,0, 'C');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 6, utf8_decode('MONITOR DE RELAJACIÓN: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(11, 6, utf8_decode($moni), 1,0, 'C');

$pdf->Ln(7);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(190, 6, utf8_decode('OBSERVACIONES:'), 0, 'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', '', 7);
$pdf->MultiCell(195, 6, utf8_decode($obser),1, 'l');



$sql_med_id = "SELECT id_usua FROM dat_seg_evol_amb WHERE id_seg_evol_amb = $id_segevol_amb";

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
}