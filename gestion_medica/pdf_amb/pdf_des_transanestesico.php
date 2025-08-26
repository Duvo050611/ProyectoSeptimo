<?php
require '../../fpdf/fpdf.php';
include '../../conexionbd.php';
$id_med = @$_GET['id_med'];
$id_transanest_amb = @$_GET['id_trans_anest_amb'];
$id_exp = @$_GET['id_exp'];
$id_atencion = @$_GET['id_atencion'];

$sql_trans = "SELECT * FROM dat_trans_anest_amb  where id_atencion = $id_atencion";
$result_trans = $conexion->query($sql_trans);

while ($row_trans = $result_trans->fetch_assoc()) {
  $id_trans_anest_amb = $row_trans['id_trans_anest_amb'];
}

if(isset($id_trans_anest_amb)){
    $id_trans_anest_amb = $id_trans_anest_amb;
  }else{
    $id_trans_anest_amb ='sin doc';
  }

if($id_trans_anest_amb=="sin doc"){
  echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
                    $(document).ready(function() {
                        swal({
                            title: "NO EXISTE REGISTRO DESCRIPTIVO TRANS-ANESTÉSICO PARA ESTE PACIENTE", 
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
    $this->Cell(0, 10, utf8_decode('SIMA-036'), 0, 1, 'R');
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


$sql_inquir = "SELECT * FROM dat_not_inquir_amb  where id_atencion = $id_atencion
order by id_not_inquir_amb DESC LIMIT 1";
$result_inquir = $conexion->query($sql_inquir);
while ($row_inquir = $result_inquir->fetch_assoc()) {
 $opreal = $row_inquir['cir_realizada']; 
 $diagposto = $row_inquir['diag_postop'];
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
$pdf->Cell(120, 5, utf8_decode('REGISTRO DESCRIPTIVO TRANS-ANESTÉSICO'), 0, 0, 'C');
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


/***********************/

//consulta TRANS ANESTESICA
$sql_trans = "SELECT * FROM dat_trans_anest_amb  where id_trans_anest_amb = $id_transanest_amb";
$result_trans = $conexion->query($sql_trans);

while ($row_trans = $result_trans->fetch_assoc()) {
  $anest = $row_trans['anest'];
  
  
  $anestreal = $row_trans['anestreal'];  
  $poscui = $row_trans['poscui'];
  $ind = $row_trans['ind']; 
  $hora = $row_trans['hora']; 
  $agdo = $row_trans['agdo']; 
  $tin = $row_trans['tin'];  

 $masc = $row_trans['masc'];
  $can = $row_trans['can'];
  $otro = $row_trans['otro'];  
  $larin = $row_trans['larin'];  
  $venti = $row_trans['venti'];
  $cir = $row_trans['cir']; 
  $esasme = $row_trans['esasme']; 
  $mec = $row_trans['mec']; 
  $modo = $row_trans['modo'];  

 $fl = $row_trans['fl'];
  $vcor = $row_trans['vcor'];
  $fr = $row_trans['fr'];  
  $rel = $row_trans['rel'];  
  $peep = $row_trans['peep'];
  $com = $row_trans['com']; 
  $mant = $row_trans['mant']; 
  $relaj = $row_trans['relaj']; 
  $agent = $row_trans['agent'];  

  $dosis = $row_trans['dosis'];
  $ultdosis = $row_trans['ultdosis'];
  $ant = $row_trans['ant'];  
  $agdos = $row_trans['agdos'];  
  $monit = $row_trans['monit'];
  $comen = $row_trans['comen']; 
  $bloq = $row_trans['bloq']; 
  $agdosi = $row_trans['agdosi']; 
  $tec = $row_trans['tec'];  

  $cate = $row_trans['cate'];
  $posi = $row_trans['posi'];
  $lat = $row_trans['lat'];  
  $asep = $row_trans['asep'];  
  $dif = $row_trans['dif'];
  $aguja = $row_trans['aguja']; 
  $bsim = $row_trans['bsim']; 
  $bmotor = $row_trans['bmotor']; 
  $bsen = $row_trans['bsen']; 

    $coment = $row_trans['coment']; 
  $caso = $row_trans['caso']; 
  $emer = $row_trans['emer']; 

}
//termino

$pdf->SetFont('Arial', '', 6);
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('ANESTESIÓLOGO: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(61, 3, utf8_decode($anest), 1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(37, 3, utf8_decode('DIAGNÓSTICO POSOPERATORIO: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(70, 3, utf8_decode($diagposto), 1, 'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('CIRUGÍA REALIZADA: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(61, 3, utf8_decode($opreal), 1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(37, 3, utf8_decode('ANESTESIA REALIZADA: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(70, 3, utf8_decode($anestreal), 1, 'L');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('POSICIÓN Y CUIDADOS: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(168, 3, utf8_decode($poscui), 1, 'L');
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('INDUCCIÓN: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(120, 3, utf8_decode($ind), 1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(16, 3, utf8_decode('HORA: ' ), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(32, 3, utf8_decode($hora), 1, 'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('AGENTES Y DOSIS: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(168, 3, utf8_decode($agdo), 1, 'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(190, 3, utf8_decode('VÍA AEREA'), 0,0, 'L');

$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('INTUBACIÓN: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(23, 3, utf8_decode($tin), 1, 'L');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(18, 3, utf8_decode('MASCARILLA: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(30, 3, utf8_decode($masc), 1, 'L');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(15, 3, utf8_decode('CÁNULA: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(34, 3, utf8_decode($can), 1, 'L');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(16, 3, utf8_decode('OTRO: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(32, 3, utf8_decode($otro), 1, 'L');

$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('LARINGOSCOPÍA: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(168, 3, utf8_decode($larin), 1, 'L');

$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('VENTILACIÓN: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(23, 3, utf8_decode($venti), 1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(18, 3, utf8_decode('CIRCUITO: ' ), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(79, 3, utf8_decode($cir), 1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(16, 3, utf8_decode('TIPO: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(32, 3, utf8_decode($esasme), 1, 'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('MECÁNICA: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(23, 3, utf8_decode($mec), 1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(18, 3, utf8_decode('MODO: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(30, 3, utf8_decode($modo), 1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(15, 3, utf8_decode('FI O2:'), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(34, 3, utf8_decode($fl), 1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(16, 3, utf8_decode('FR: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(32, 3, utf8_decode($fr), 1, 'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('V.CORRIENTE: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(23, 3, utf8_decode($vcor), 1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(18, 3, utf8_decode('REL. I:E: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(30, 3, utf8_decode($rel), 1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(15, 3, utf8_decode('PEEP: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(34, 3, utf8_decode($peep), 1, 'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('COMENTARIOS: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(168, 3, utf8_decode($com), 1, 'L');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('MANTENIMIENTO: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(168, 3, utf8_decode($mant), 1, 'L');

$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 5.5);
$pdf->Cell(27, 3, utf8_decode('RELAJACIÓN MUSCULAR:'), 1, 'J');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(23, 3, utf8_decode($relaj), 1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(18, 3, utf8_decode('AGENTES: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(30, 3, utf8_decode($agent), 1, 'L');
$pdf->SetFont('Arial', 'B', 5.5);
$pdf->Cell(15, 3, utf8_decode('DOSIS TOTAL:'), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(34, 3, utf8_decode($dosis), 1, 'L');
$pdf->SetFont('Arial', 'B', 5.5);
$pdf->Cell(16, 3, utf8_decode('ÚLTIMA DOSIS:'), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(32, 3, utf8_decode($ultdosis), 1, 'L');
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('ANTAGONISMO:'), 1, 'J');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(23, 3, utf8_decode($ant), 1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(18, 3, utf8_decode('AGENTE/DOSIS:'), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(30, 3, utf8_decode($agdos), 1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(29, 3, utf8_decode('MONITOREO (OPCIONAL):'), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(68, 3, utf8_decode($monit), 1, 'L');
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('COMENTARIOS: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(168, 3, utf8_decode($ultdosis), 1, 'L');
$pdf->Ln(8);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(95, 3, utf8_decode('ANESTESIA REGIONAL: '),0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('BLOQUEO: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(71, 3, utf8_decode($bloq), 1,0, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('TÉCNICA: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(70, 3, utf8_decode($tec), 1,0, 'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('POSICIÓN: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(71, 3, utf8_decode($posi), 1,0, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('ASEP Y ANTISEP: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(70, 3, utf8_decode($asep), 1,0, 'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('AGUJA: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(71, 3, utf8_decode($aguja), 1,0, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('AGENTES Y DOSIS: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(70, 3, utf8_decode($agdosi), 1,0, 'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('CATETER: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(71, 3, utf8_decode($cate), 1,0, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('LATENCIA: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(70, 3, utf8_decode($lat), 1,0, 'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('DIFUSIÓN: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(71, 3, utf8_decode($dif), 1,0, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('B. SIMPÁTICO: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(70, 3, utf8_decode($bsim), 1,0, 'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('B. SENSITIVO: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(71, 3, utf8_decode($bsen), 1,0, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('B. MOTOR: '), 1,0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(70, 3, utf8_decode($bmotor), 1,0, 'L');
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('COMENTARIOS: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(168, 3, utf8_decode($coment), 1, 'L');
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(27, 3, utf8_decode('EMERSIÓN: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(168, 3, utf8_decode($emer), 1, 'L');

$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->MultiCell(100, 3, utf8_decode('DATOS DEL PRODUCTO / CASO OBSTÉTRICO: '),0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(195, 3, utf8_decode($caso), 1, 'L');

$pdf->SetY(165);

$g = "SELECT * FROM dat_trans_grafico  where id_atencion = $id_atencion order by id_trans_graf DESC limit 1";
$resg = $conexion->query($g);

while ($row_g = $resg->fetch_assoc()) {
  $a = $row_g['a'];
  $b = $row_g['b'];
  $c = $row_g['c'];
  $d = $row_g['d'];
  $e = $row_g['e'];
  $f = $row_g['f'];
  $g = $row_g['g'];
  $h = $row_g['h'];
  $i = $row_g['i'];
  $j = $row_g['j'];
  $k = $row_g['k'];
  $l = $row_g['l'];
  $m = $row_g['m'];
  $n = $row_g['n'];
  $o = $row_g['o'];
  $p = $row_g['p'];
  $q = $row_g['q'];
  $r = $row_g['r'];
  $s = $row_g['s'];
  $t = $row_g['t'];  
}
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(190, 3, utf8_decode('MEDICAMENTOS, DOSIS TOTAL Y VÍAS: '), 0,0, 'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 5);
$pdf->Cell(49, 3, utf8_decode('A: '.$a), 1,0, 'L');
$pdf->Cell(49, 3, utf8_decode('F: '.$f), 1,0, 'L');
$pdf->Cell(49, 3, utf8_decode('K: '.$k), 1,0, 'L');
$pdf->Cell(48, 3, utf8_decode('P: '.$p), 1,0, 'L');
$pdf->Ln(3);
$pdf->Cell(49, 3, utf8_decode('B: '.$b), 1,0, 'L');
$pdf->Cell(49, 3, utf8_decode('G: '.$g), 1,0, 'L');
$pdf->Cell(49, 3, utf8_decode('L: '.$l), 1,0, 'L');
$pdf->Cell(48, 3, utf8_decode('Q: '.$q), 1,0, 'L');
$pdf->Ln(3);
$pdf->Cell(49, 3, utf8_decode('C: '.$c), 1,0, 'L');
$pdf->Cell(49, 3, utf8_decode('H: '.$h), 1,0, 'L');
$pdf->Cell(49, 3, utf8_decode('M: '.$m), 1,0, 'L');
$pdf->Cell(48, 3, utf8_decode('R: '.$r), 1,0, 'L');
$pdf->Ln(3);
$pdf->Cell(49, 3, utf8_decode('D: '.$d), 1,0, 'L');
$pdf->Cell(49, 3, utf8_decode('I: '.$i), 1,0, 'L');
$pdf->Cell(49, 3, utf8_decode('N: '.$n), 1,0, 'L');
$pdf->Cell(48, 3, utf8_decode('S: '.$s), 1,0, 'L');
$pdf->Ln(3);
$pdf->Cell(49, 3, utf8_decode('E: '.$e), 1,0, 'L');
$pdf->Cell(49, 3, utf8_decode('J: '.$j), 1,0, 'L');
$pdf->Cell(49, 3, utf8_decode('O: '.$o), 1,0, 'L');
$pdf->Cell(48, 3, utf8_decode('T: '.$t), 1,0, 'L');




$sql_med_id = "SELECT id_usua FROM dat_trans_anest_amb WHERE id_trans_anest_amb = $id_transanest_amb";
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