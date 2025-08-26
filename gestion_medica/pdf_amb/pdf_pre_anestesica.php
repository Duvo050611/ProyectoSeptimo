<?php
require '../../fpdf/fpdf.php';
include '../../conexionbd.php';
$id_med = @$_GET['id_med'];
$id_pernest_amb = @$_GET['id_peranest_amb'];
$id_exp = @$_GET['id_exp'];
$id_atencion = @$_GET['id_atencion'];

$sql_pre = "SELECT * FROM dat_peri_anest_amb  where id_atencion = $id_atencion";
$result_pre = $conexion->query($sql_pre);

while ($row_pre = $result_pre->fetch_assoc()) {
  $id_peranest_amb = $row_pre['id_peranest_amb'];
}

if(isset($id_peranest_amb)){
    $id_peranest_amb = $id_peranest_amb;
  }else{
    $id_peranest_amb ='sin doc';
  }

if($id_peranest_amb=="sin doc"){
  echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
                    $(document).ready(function() {
                        swal({
                            title: "NO EXISTE NOTA PRE-ANESTÉSICA PARA ESTE PACIENTE", 
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
    $this->Cell(0, 10, utf8_decode('SIMA-034'), 0, 1, 'R');
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

//consulta nota pre anestesica
$sql_pre = "SELECT * FROM dat_peri_anest_amb  where id_peranest_amb = $id_pernest_amb";
$result_pre = $conexion->query($sql_pre);

while ($row_pre = $result_pre->fetch_assoc()) {
  $diagpre = $row_pre['diagpre'];
  $area = $row_pre['area'];
  $tipo_intervencion = $row_pre['urg'];  
  $inter = $row_pre['inter'];  
  $proc_prog = $row_pre['proc_prog'];  
  $med_proc = $row_pre['med_proc']; 
  $anest = $row_pre['anest']; 
  $inmun = $row_pre['inmun']; 
  $tab = $row_pre['tab'];  
  $alc = $row_pre['alc'];

  $trans = $row_pre['trans'];  
  $alerg = $row_pre['alerg'];  
  $toxi = $row_pre['toxi'];  
  $gastro = $row_pre['gastro'];  
  $neuro = $row_pre['neuro'];  
  $neumo = $row_pre['neumo'];  
  $ren = $row_pre['ren'];  
  $card = $row_pre['card'];  
  $tend = $row_pre['tend'];
  $reu = $row_pre['reu'];  
  $neo = $row_pre['neo'];  
  $herma = $row_pre['herma'];  
  $trau = $row_pre['trau'];  
  $psi = $row_pre['psi'];  
  $quir = $row_pre['quir'];  
  $aneste = $row_pre['aneste'];  
  $gin = $row_pre['gin'];  
  $ped = $row_pre['ped'];  
  $valant = $row_pre['valant'];  
  $cons = $row_pre['cons'];  
  $pad_act = $row_pre['pad_act'];  
  $med_act = $row_pre['med_act'];  
  $ayuno = $row_pre['ayuno'];  
  $otro = $row_pre['otro'];  
  $esp = $row_pre['esp'];  
  $ta_sisto = $row_pre['ta_sisto'];  
  $ta_diasto = $row_pre['ta_diasto'];    
  $fc = $row_pre['fc'];  
  $fr = $row_pre['fr']; 

  $tempe = $row_pre['tempe'];  
  $pes = $row_pre['pes']; 
  $tall = $row_pre['tall'];  
  $imc = $row_pre['imc']; 
  $malla = $row_pre['malla'];  
  $patil = $row_pre['patil']; 
  $bell = $row_pre['bell'];  
  $dist = $row_pre['dist']; 
  $buco = $row_pre['buco'];  
  $obserb = $row_pre['obserb']; 
  $fecha = $row_pre['fecha'];  
  $hb = $row_pre['hb']; 
  $hto = $row_pre['hto'];  
  $gb = $row_pre['gb']; 
  $gr = $row_pre['gr'];  
  $plaq = $row_pre['plaq']; 
  $tp = $row_pre['tp'];  
  $tpt = $row_pre['tpt']; 
  $inr = $row_pre['inr'];  
  $gluc = $row_pre['gluc'];
  $cr = $row_pre['cr']; 
  $bun = $row_pre['bun'];  
  $urea = $row_pre['urea'];
  $es = $row_pre['es']; 
  $otros = $row_pre['otros'];  

  $gab = $row_pre['gab']; 
  $valcard = $row_pre['valcard']; 
  $condfis = $row_pre['condfis'];  
  $tipanest = $row_pre['tipanest'];
  $indpre = $row_pre['indpre'];
  $obs = $row_pre['obs'];
    $nomanest = $row_pre['nomanest'];

}
//termino nota pre anestesica

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
$pdf->Cell(120, 5, utf8_decode('VALORACIÓN PRE-ANESTÉSICA'), 0, 0, 'C');
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
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(37, 4, utf8_decode('TIPO DE CIRUGÍA: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(63, 4, utf8_decode($tipo_intervencion), 1,'C');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(30, 4, utf8_decode('INTERROGATORIO: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(65, 4, utf8_decode($inter), 1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(37, 3, utf8_decode('DIAGNÓSTICO PREOPERATORIO: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(158, 3, utf8_decode($diagpre), 1, 'L');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(37, 3, utf8_decode('CIRUGÍA PROGRAMADA: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(158, 3, utf8_decode($proc_prog), 1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(37, 3, utf8_decode('MEDICO RESPONSABLE: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(63, 3, utf8_decode($med_proc), 1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(30, 3, utf8_decode('ANESTESIÓLOGO: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(65, 3, utf8_decode($anest), 1, 'L');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(60, 3, utf8_decode('ANTECEDENTES NO PATOLÓGICOS'), 1,0, 'C');
$pdf->Cell(15, 3, utf8_decode('SI / NO'), 1,0, 'C');

$pdf->Cell(120, 3, utf8_decode('VALORACIÓN DE ANTECEDENTES'), 1,0, 'C');
$pdf->Ln(3);

$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60, 3, utf8_decode('INMUNIZACIONES'), 1,0, 'C');
$pdf->Cell(15, 3, utf8_decode($inmun), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60, 3, utf8_decode('TABAQUISMO'), 1,0, 'C');
$pdf->Cell(15, 3, utf8_decode($tab), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60, 3, utf8_decode('ALCOHOLISMO'), 1,0, 'C');
$pdf->Cell(15, 3, utf8_decode($alc), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60, 3, utf8_decode('TRANSFUSIONES'), 1,0, 'C');
$pdf->Cell(15, 3, utf8_decode($trans), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60, 3, utf8_decode('ALERGIAS'), 1,0, 'C');
$pdf->Cell(15, 3, utf8_decode($alerg), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60, 3, utf8_decode('TOXICOMANIAS'), 1,0, 'C');
$pdf->Cell(15, 3, utf8_decode($toxi), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(60, 3, utf8_decode('ANTECEDENTES PATOLÓGICOS'), 1,0, 'C');
$pdf->Cell(15, 3, utf8_decode('SI / NO'), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60, 3, utf8_decode('GASTRO/HEPÁTICOS'), 1,0, 'C');
$pdf->Cell(15, 3, utf8_decode($gastro), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60, 3, utf8_decode('NEUROLÓGICOS'), 1,0, 'C');
$pdf->Cell(15, 3, utf8_decode($neuro), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60, 3, utf8_decode('NEUMOLÓGICOS'), 1,0, 'C');
$pdf->Cell(15, 3, utf8_decode($neumo), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60, 3, utf8_decode('RENALES'), 1,0, 'C');
$pdf->Cell(15, 3, utf8_decode($ren), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60, 3, utf8_decode('CARDIOLÓGICOS'), 1,0, 'C');
$pdf->Cell(15, 3, utf8_decode($card), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60, 3, utf8_decode('ENDÓCRINOS'), 1,0, 'C');
$pdf->Cell(15, 3, utf8_decode($tend), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60, 3, utf8_decode('REUMÁTICOS'), 1,0, 'C');
$pdf->Cell(15, 3, utf8_decode($reu), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60, 3, utf8_decode('NEOPLÁSICOS'), 1,0, 'C');
$pdf->Cell(15, 3, utf8_decode($neo), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60, 3, utf8_decode('HEMATOLÓGICOS'), 1,0, 'C');
$pdf->Cell(15, 3, utf8_decode($herma), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60, 3, utf8_decode('TRAUMÁTICOS'), 1,0, 'C');
$pdf->Cell(15, 3, utf8_decode($trau), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60, 3, utf8_decode('PSIQUIÁTRICOS'), 1,0, 'C');
$pdf->Cell(15, 3, utf8_decode($psi), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60, 3, utf8_decode('QUIRÚRGICOS'), 1,0, 'C');
$pdf->Cell(15, 3, utf8_decode($quir), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60, 3, utf8_decode('ANESTÉSICOS'), 1,0, 'C');
$pdf->Cell(15, 3, utf8_decode($aneste), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60, 3, utf8_decode('GINECO-OBSTÉTRICOS'), 1,0, 'C');
$pdf->Cell(15, 3, utf8_decode($gin), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60, 3, utf8_decode('PEDIÁTRICOS'), 1,0, 'C');
$pdf->Cell(15, 3, utf8_decode($ped), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(60, 3, utf8_decode('ESTADO NEUROLÓGICO'), 1,0, 'C');
$pdf->Cell(15, 3, utf8_decode('SI / NO'), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60, 3, utf8_decode('CONSCIENTE'), 1,0, 'C');
$pdf->Cell(15, 3, utf8_decode($cons), 1,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60, 3, utf8_decode('OTRO'), 1,0, 'C');
$pdf->Cell(15, 3, utf8_decode($otro), 1,0, 'C');

$pdf->SetY(85);
$pdf->SetX(85);

$pdf->MultiCell(120, 75, utf8_decode($valant),1, 'C');

$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(37, 3, utf8_decode('PADECIMIENTO ACTUAL:'), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(158, 3, utf8_decode($pad_act), 1, 'L');

$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(37, 3, utf8_decode('MEDICACIÓN ACTUAL:'), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(158, 3, utf8_decode($med_act), 1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(37, 4, utf8_decode('AYUNO: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(63, 4, utf8_decode($ayuno), 1,'C');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(30, 4, utf8_decode('ESPECIFICAR (OTRO):'), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(65, 4, utf8_decode($esp), 1, 'L');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(195, 3, utf8_decode('SIGNOS VITALES'), 0,0, 'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(37, 3, utf8_decode('PRESIÓN ARTERIAL: ' .$ta_sisto.'/'.$ta_diasto), 1, 'L');
$pdf->Cell(36, 3, utf8_decode('FRECUENCIA CARDIACA: ' .$fc), 1, 'L');
$pdf->Cell(36, 3, utf8_decode('FRECUENCIA RESPIRATORIA: ' .$fr), 1, 'L');
$pdf->Cell(26, 3, utf8_decode('TEMPERATURA: ' .$tempe), 1, 'L');
$pdf->Cell(35, 3, utf8_decode('PESO: ' .$pes), 1, 'L');
$pdf->Cell(25, 3, utf8_decode('TALLA: ' .$tall), 1, 'L');
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(37, 3, utf8_decode('VÍA ÁREA'), 1,0, 'C');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(36, 3, utf8_decode('MELLAMPATI: ' .$malla), 1,0, 'L');
$pdf->Cell(36, 3, utf8_decode('PATIL ALDRETI: ' .$patil), 1,0, 'L');
$pdf->Cell(26, 3, utf8_decode('BELLHOUSE-DORE: ' .$bell), 1,0, 'L');
$pdf->Cell(35, 3, utf8_decode('DIST ESTERNOMENTONIANA: ' .$dist), 1,0, 'L');
$pdf->Cell(25, 3, utf8_decode('BUCO-DENTAL: ' .$buco), 1,0, 'L');
$pdf->Ln(3);
$pdf->Cell(195, 3, utf8_decode('OBSERVACIONES: ' .$obserb), 1,0, 'L');

$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(190, 3, utf8_decode('LABORATORIO'), 0, 'L');
$pdf->Ln(3);
$pdf->Cell(37, 3, utf8_decode('FECHA'), 1,0, 'C');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(23, 3, utf8_decode('HB: ' .$hb), 1,0, 'L');
$pdf->Cell(23, 3, utf8_decode('HTO: ' .$hto), 1,0, 'L');
$pdf->Cell(23, 3, utf8_decode('GB: ' .$gb), 1,0, 'L');
$pdf->Cell(23, 3, utf8_decode('GR: ' .$gr), 1,0, 'L');
$pdf->Cell(22, 3, utf8_decode('PLAQ: ' .$plaq), 1,0, 'L');
$pdf->Cell(22, 3, utf8_decode('TP: ' .$tp), 1,0, 'L');
$pdf->Cell(22, 3, utf8_decode('TPT: ' .$tpt), 1,0, 'L');
$pdf->Ln(3);

$pdf->Cell(37, 3, utf8_decode($fecha), 1,0, 'C');
$pdf->Cell(23, 3, utf8_decode('INR: ' .$inr), 1,0, 'L');
$pdf->Cell(23, 3, utf8_decode('GLUC: ' .$gluc), 1,0, 'L');
$pdf->Cell(23, 3, utf8_decode('CR: ' .$cr), 1,0, 'L');
$pdf->Cell(23, 3, utf8_decode('BUN: ' .$bun), 1,0, 'L');
$pdf->Cell(22, 3, utf8_decode('UREA: ' .$urea), 1,0, 'L');
$pdf->Cell(22, 3, utf8_decode('E.S. ' .$es), 1,0, 'L');
$pdf->Ln(3);
$pdf->Cell(195, 3, utf8_decode('OTROS: ' .$otros), 1,0, 'L');
$pdf->Ln(4);

$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(37, 3, utf8_decode('GABINETE:'), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(158, 3, utf8_decode($gab), 1, 'L');

$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(37, 3, utf8_decode('VALORACIÓN CARDIOVASCULAR:'), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(158, 3, utf8_decode($valcard), 1, 'L');

$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(37, 3, utf8_decode('CONDICIÓN FÍSICA ASA:'), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(158, 3, utf8_decode($condfis), 1, 'L');

$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(37, 3, utf8_decode('TIPO ANESTESIA PROPUESTA:'), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(158, 3, utf8_decode($tipanest), 1, 'L');

$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(37, 3, utf8_decode('INDICACIÓN PREANESTÉSICA:'), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(158, 3, utf8_decode($indpre), 1, 'L');


$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(37, 3, utf8_decode('OBSERVACIONES:'), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(158, 3, utf8_decode($obs), 1, 'L');


$pdf->SetFont('Arial', 'B', 6);

$sql_med_id = "SELECT id_usua FROM dat_peri_anest_amb WHERE id_peranest_amb=$id_pernest_amb";
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