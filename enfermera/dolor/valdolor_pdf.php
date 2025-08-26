<?php
require '../../fpdf/fpdf.php';
include '../../conexionbd.php';
$id_atencion = @$_GET['id_atencion'];
$id_ord = @$_GET['id_ord'];
$id_med = @$_GET['id_med'];
$fecha = @$_GET['fecha'];
$id_exp = @$_GET['id_exp'];

mysqli_set_charset($conexion, "utf8");

class PDF extends FPDF
{
  function Header()
  {
    $id = @$_GET['id_atencion'];
    $id_med = @$_GET['id_med'];
    include '../../conexionbd.php';

    $id = @$_GET['id_atencion'];;
       $this->Image('../../imagenes/SI.PNG', 5, 15, 65, 21);
    $this->SetFont('Arial', 'B', 15);
    $this->SetTextColor(43, 45, 127);
    $this->Cell(196, 9, utf8_decode(' Médica San Isidro'), 0, 0, 'C');
    $this->Ln(10);
    $this->SetDrawColor(43, 45, 180);
    $this->Line(50, 18, 170, 18);
    $this->SetFont('Arial', '', 8);
    $this->Cell(200, 8, utf8_decode('CALLE JOSEFA ORTIZ DE DOMÍNGUEZ #444'), 0, 0, 'C');
    $this->Ln(4);
    $this->Cell(200, 8, utf8_decode('BARRIO COAXUSTENCO, METEPEC, ESTADO DE MÉXICO'), 0, 0, 'C');
    $this->Ln(4);
    $this->Cell(200, 8, 'TEL: (01722) 235-01-75 / 235-02-12 / 902-03-90 / C.P. 52140', 0, 0, 'C');
    $this->Ln(4);
    $this->Cell(200, 8, 'https://medicasanisidro.com/', 0, 0, 'C');
    $this->Ln(10);
    $this->Image('../../imagenes/en.png', 159, 22, 45, 15);
  }
  function Footer()
  {
    $this->Ln(8);
    $this->SetY(-15);
    $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}' ), 0, 0, 'C');
    $this->Cell(0, 10, utf8_decode('SIMA-027'), 0, 1, 'R');
  }
 
}



$sql_pac = "SELECT p.papell, p.nom_pac,p.fecnac,p.sapell,p.edad,p.sexo,p.Id_exp,p.dir,p.id_edo,p.id_mun,p.tel,p.ocup,p.resp,p.paren,p.tel_resp, p.religion FROM paciente p, dat_ingreso di where di.id_atencion = $id_atencion and di.Id_exp=p.Id_exp";
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
  $religion = $row_pac['religion'];
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

$sql_dati = "SELECT * FROM dat_ingreso where id_atencion = $id_atencion";
$result_dati = $conexion->query($sql_dati);

while ($row_dati = $result_dati->fetch_assoc()) {
    $motivo_atn = $row_dati['motivo_atn'];
    $tipo_a = $row_dati['tipo_a'];
    $fecha_ing = $row_dati['fecha'];
}

//consulta ordenes
$sql_ormed = "SELECT * FROM dat_ordenes_med  where id_ord_med=$id_ord";
$result_ormed = $conexion->query($sql_ormed);

while ($row_ormed = $result_ormed->fetch_assoc()) {
  $fecha_ord = $row_ormed['fecha_ord'];
  $hora_ord = $row_ormed['hora_ord'];
  $dieta = $row_ormed['dieta'];  
  $signos = $row_ormed['signos'];  
  $monitoreo = $row_ormed['monitoreo'];  
  $diuresis = $row_ormed['diuresis'];  
  $dex = $row_ormed['dex'];  
  $semif = $row_ormed['semif'];  
  $vigilar = $row_ormed['vigilar'];  
  $oxigeno = $row_ormed['oxigeno'];  
  $nebulizacion = $row_ormed['nebulizacion']; 
  $bar = $row_ormed['bar']; 
  $baño = $row_ormed['baño']; 
  $foley = $row_ormed['foley']; 
  $ej = $row_ormed['ej']; 
  $datsan = $row_ormed['datsan']; 

  $detsignos = $row_ormed['detsignos'];  
  $detmonitoreo = $row_ormed['detmonitoreo'];  
  $detdiuresis = $row_ormed['detdiuresis'];  
  $detdex = $row_ormed['detdex'];  
  $detsemif = $row_ormed['detsemif'];  
  $detvigilar = $row_ormed['detvigilar'];  
  $detoxigeno = $row_ormed['detoxigeno'];  
  $detnebu = $row_ormed['detnebu']; 
  $detbar = $row_ormed['detbar']; 
  $detbaño = $row_ormed['detbaño']; 
  $detfoley = $row_ormed['detfoley']; 
  $detej = $row_ormed['detej']; 
  $detsan = $row_ormed['detsan']; 

  $cuid_gen = $row_ormed['cuid_gen'];  
  $med_med = $row_ormed['med_med'];  
  $soluciones = $row_ormed['soluciones']; 
  $perfillab = $row_ormed['perfillab'];  
  $sol_estudios = $row_ormed['sol_estudios'];
  $solicitud_sang = $row_ormed['solicitud_sang'];
  $observ_be = $row_ormed['observ_be'];     
}
//termino ordenes

$pdf = new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetTextColor(43, 45, 127);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetX(50);
$pdf->Cell(120, 5, utf8_decode('VALORACIÓN DE DOLOR'), 0, 0, 'C');
$pdf->SetFont('Arial', '', 8);
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("d/m/Y H:i");
$pdf->Cell(35, 5, 'FECHA: ' . $fecha_actual, 0, 1, 'R');
$pdf->Ln(5);

$pdf->SetDrawColor(43, 45, 127);
$pdf->Line(8, 50, 207, 50);
$pdf->Line(8, 50, 8, 280);
$pdf->Line(207, 50, 207, 280);
$pdf->Line(8, 280, 207, 280);


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
$pdf->Cell(13, 3, 'SEXO: ', 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(22, 3,  $sexo, 'B', 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(18, 3, utf8_decode(' DOMICILIO: '), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(141, 3, utf8_decode($dir), 'B', 'L');
$pdf->Ln(4);

$sql_diag = "SELECT * from diag_pac where id_exp=$id_atencion ORDER by id_diag DESC LIMIT 1";

$result_diag = $conexion->query($sql_diag);

while ($row_diag = $result_diag->fetch_assoc()) {
   $diag_paciente = $row_diag['diag_paciente'];
}
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(195, 4, utf8_decode('DIAGNÓSTICO: '.$diag_paciente), 1, 0, 'L');

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

$pesoh="";
 $tallah="";
$sql_sig ="select * from dat_hclinica WHERE Id_exp=$id_exp ORDER by id_hc DESC LIMIT 1";
$result_sig = $conexion->query($sql_sig);

while ($row_hc = $result_sig->fetch_assoc()) {
 $pesoh=$row_hc['peso'];
 $tallah=$row_hc['talla'];

}

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(195, 3, utf8_decode('SIGNOS VITALES'), 0,0, 'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(31, 3, utf8_decode('PRESIÓN ARTERIAL: ' .$p_sistolica.'/'.$p_diastolica), 1, 'L');
$pdf->Cell(26, 3, utf8_decode('FREC. CARDIACA: ' .$f_card), 1, 'L');
$pdf->Cell(30, 3, utf8_decode('FREC. RESPIRATORIA: ' .$f_resp), 1, 'L');
$pdf->Cell(26, 3, utf8_decode('TEMPERATURA: ' .$temp), 1, 'L');
$pdf->Cell(32, 3, utf8_decode('SATURACIÓN OXIGENO: ' .$sat_oxigeno), 1, 'L');
$pdf->Cell(15, 3, utf8_decode('PESO: ' .$pesoh), 1, 'L');
$pdf->Cell(15, 3, utf8_decode('TALLA: ' .$tallah), 1, 'L');
$pdf->Cell(20, 3, utf8_decode('ESCALA EVA: ' .$niv_dolor), 1, 'L');
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 6);
$pdf->MultiCell(195, 6, utf8_decode('VALORACIÓN DE DOLOR'), 0, 'C');

$pdf->Cell(14,5, utf8_decode('FECHA'),1,0,'C');
$pdf->Cell(23,5, utf8_decode('TIPO'),1,0,'C');
$pdf->Cell(9,5, utf8_decode('HORA'),1,0,'C');
$pdf->Cell(15,5, utf8_decode('TURNO'),1,0,'C');
$pdf->Cell(48,5, utf8_decode('ESCALA UTLIZADA'),1,0,'C');
$pdf->Cell(22,5, utf8_decode('CADA 30 MINUTOS'),1,0,'C');
$pdf->Cell(18,5, utf8_decode('CADA 2 HORAS'),1,0,'C');
$pdf->Cell(25,5, utf8_decode('2 VECES POR TURNO'),1,0,'C');
$pdf->Cell(21,5, utf8_decode('1 VEZ POR TURNO'),1,0,'C');
$cis = $conexion->query("select * from val_dolor where id_atencion=$id_atencion AND fecha='$fecha' ORDER BY val_fecha DESC") or die($conexion->error);
while ($cis_s = $cis->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
$pdf->Ln(5);
$date=date_create($cis_s['fecha']);
$pdf->Cell(14,5, date_format($date,"d/m/Y"),1,0,'C');
$pdf->Cell(23,5, utf8_decode($cis_s['tipo']),1,0,'C');
$pdf->Cell(9,5, utf8_decode($cis_s['hora_v']),1,0,'C');
$pdf->Cell(15,5, utf8_decode($cis_s['turno']),1,0,'C');
$pdf->Cell(48,5, utf8_decode($cis_s['escu']),1,0,'C');
$pdf->Cell(22,5, utf8_decode($cis_s['treinta']),1,0,'C');
$pdf->Cell(18,5, utf8_decode($cis_s['dosh']),1,0,'C');
$pdf->Cell(25,5, utf8_decode($cis_s['dosvpt']),1,0,'C');
$pdf->Cell(21,5, utf8_decode($cis_s['unavez']),1,0,'C');

}


$pdf->Ln(30);


$sql_med_id = "SELECT id_usua FROM val_dolor WHERE id_val=$id_ord";
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