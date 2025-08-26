<?php
require '../../fpdf/fpdf.php';
include '../../conexionbd.php';
$id_atencion = @$_GET['id_atencion'];
$id_ord = @$_GET['id_ord'];
$id_med = @$_GET['id_med'];

mysqli_set_charset($conexion, "utf8");

class PDF extends FPDF
{
  function Header()
  {
    $id = @$_GET['id_atencion'];
    $id_med = @$_GET['id_med'];
    include '../../conexionbd.php';

    $id = @$_GET['id_atencion'];;
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
  $det_dieta = $row_ormed['det_dieta'];  
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

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(20, 5, utf8_decode('FECHA '.$fecha_ord), 0, 0, 'C');
$pdf->Cell(50, 5, utf8_decode('HORA '.$hora_ord), 0, 0, 'C');
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetX(50);
$pdf->Cell(120, 5, utf8_decode('ÓRDENES DEL MÉDICO'), 0, 0, 'C');
$pdf->SetFont('Arial', '', 8);
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("d/m/Y");
$pdf->Cell(35, 5, 'Toluca, Mex, ' . $fecha_actual, 0, 1, 'R');
$pdf->Ln(1);

$pdf->SetDrawColor(0, 0, 0);
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

$sql_diag = "SELECT * from diag_pac where id_exp=$Id_exp ORDER by id_diag DESC LIMIT 1";

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

$pesohc="";
$tallahc="";
$sql_hc ="select * from dat_hclinica WHERE Id_exp=$id_exp ORDER by id_hc DESC LIMIT 1";
$res_hc = $conexion->query($sql_hc);
while ($row_hc = $res_hc->fetch_assoc()) {
$pesohc=$row_hc['peso'];
 $tallahc=$row_hc['talla'];

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
$pdf->Cell(15, 3, utf8_decode('PESO: ' .$pesohc), 1, 'L');
$pdf->Cell(15, 3, utf8_decode('TALLA: ' .$tallahc), 1, 'L');
$pdf->Cell(20, 3, utf8_decode('ESCALA EVA: ' .$niv_dolor), 1, 'L');
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 7);
$pdf->MultiCell(195, 6, utf8_decode('INDICACIONES MEDICAS'), 0, 'C');


$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 3, utf8_decode('1. DIETA: '), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(157, 3, utf8_decode( $dieta), 1, 'L');
$pdf->SetX(48);
$pdf->MultiCell(157, 3, utf8_decode( $det_dieta), 1, 'L');

$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 3, utf8_decode('2. CUIDADOS GENERALES: '), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(157, 3, utf8_decode( $observ_be), 1, 'L');
$pdf->Ln(1);


$pdf->SetX(12);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(53, 3, utf8_decode('CUIDADOS GENERALES '), 1, 0,'C');
$pdf->Cell(10, 3, utf8_decode('SI / NO' ), 1, 'C');
$pdf->Cell(130, 3, utf8_decode('DESCRIPCIÓN' ), 1, 'C');
$pdf->Ln(3);

$pdf->SetX(12);
$pdf->SetFont('Arial', '', 6);
if($signos=='SI'){
$pdf->Cell(53, 3, utf8_decode('SIGNOS VITALES POR TURNO: '), 1, 'L');
$pdf->Cell(10, 3, utf8_decode($signos ), 1,0, 'C');
$pdf->Cell(130, 3, utf8_decode($detsignos ), 1, 'L');
$pdf->Ln(3);
$pdf->SetX(12);
}else if ($signos=='NO') {
 
}


if($monitoreo=='SI'){
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(53, 3, utf8_decode('MONITOREO CONSTANTE: '), 1, 'L');
$pdf->Cell(10, 3, utf8_decode($monitoreo ), 1,0, 'C');
$pdf->Cell(130, 3, utf8_decode($detmonitoreo ), 1, 'L');
$pdf->Ln(3);
$pdf->SetX(12);
}else if ($monitoreo=='NO') {
 
}

if($diuresis=='SI'){
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(53, 3, utf8_decode('DIURESIS POR TURNO: '), 1, 'L');
$pdf->Cell(10, 3, utf8_decode($diuresis ), 1,0, 'C');
$pdf->Cell(130, 3, utf8_decode($detdiuresis), 1, 'L');
$pdf->Ln(3);
$pdf->SetX(12);
}else if ($diuresis=='NO') {
 
}

if($dex=='SI'){
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(53, 3, utf8_decode('DEXTROSTIS POR TURNO: '), 1, 'L');
$pdf->Cell(10, 3, utf8_decode($dex ), 1,0, 'C');
$pdf->Cell(130, 3, utf8_decode($detdex), 1, 'L');
$pdf->Ln(3);
$pdf->SetX(12);
}else if ($dex=='NO') {
 
}

if($semif=='SI'){
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(53, 3, utf8_decode('POSICIÓN SEMIFLOWER: '), 1, 'L');
$pdf->Cell(10, 3, utf8_decode($semif ), 1,0, 'C');
$pdf->Cell(130, 3, utf8_decode($detsemif), 1, 'L');
$pdf->Ln(3);
$pdf->SetX(12);
}else if ($semif=='NO') {
 
}

if($vigilar=='SI'){
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(53, 3, utf8_decode('VIGILAR DATOS DEL PACIENTE NEUROLÓGICO:'), 1, 'L');
$pdf->Cell(10, 3, utf8_decode($vigilar ), 1,0, 'C');
$pdf->Cell(130, 3, utf8_decode($detvigilar ), 1, 'L');
$pdf->Ln(3);
$pdf->SetX(12);
}else if ($vigilar=='NO') {
 
}

if($oxigeno=='SI'){
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(53, 3, utf8_decode('OXÍGENO:'), 1, 'L');
$pdf->Cell(10, 3, utf8_decode($oxigeno ), 1,0, 'C');
$pdf->Cell(130, 3, utf8_decode($detoxigeno ), 1, 'L');
$pdf->Ln(3);
$pdf->SetX(12);
}else if ($oxigeno=='NO') {
 
}

if($nebulizacion=='SI'){
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(53, 3, utf8_decode('NEBULIZACIONES:'), 1, 'L');
$pdf->Cell(10, 3, utf8_decode($nebulizacion), 1,0, 'C');
$pdf->Cell(130, 3, utf8_decode($detnebu), 1, 'L');
$pdf->Ln(3);
$pdf->SetX(12);
}else if ($nebulizacion=='NO') {
 
}

if($bar=='SI'){
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(53, 3, utf8_decode('BARANDALES EN ALTO:'), 1, 'L');
$pdf->Cell(10, 3, utf8_decode($bar), 1,0, 'C');
$pdf->Cell(130, 3, utf8_decode($detbar), 1, 'L');
$pdf->Ln(3);
$pdf->SetX(12);
}else if ($bar=='NO') {
 
}

if($baño=='SI'){
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(53, 3, utf8_decode('BAÑO DIARIO Y DEAMBULACIÓN ASISTIDA:'), 1, 'L');
$pdf->Cell(10, 3, utf8_decode($baño), 1,0, 'C');
$pdf->Cell(130, 3, utf8_decode($detbaño), 1, 'L');
$pdf->Ln(3);
$pdf->SetX(12);
}else if ($baño=='NO') {
 
}

if($foley=='SI'){
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(53, 3, utf8_decode('CUIDADOS SONDA FOLEY:'), 1, 'L');
$pdf->Cell(10, 3, utf8_decode($foley), 1,0, 'C');
$pdf->Cell(130, 3, utf8_decode($detfoley), 1, 'L');
$pdf->Ln(3);
$pdf->SetX(12);
}else if ($foley=='NO') {
 
}

if($ej=='SI'){
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(53, 3, utf8_decode('EJERCICIOS RESPIRATORIOS:'), 1, 'L');
$pdf->Cell(10, 3, utf8_decode($ej), 1,0, 'C');
$pdf->Cell(130, 3, utf8_decode($detej), 1, 'L');
$pdf->Ln(3);
$pdf->SetX(12);
}else if ($ej=='NO') {
 
}

if($datsan=='SI'){
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(53, 3, utf8_decode('VIGILAR DATOS DE SANGRADO:'), 1, 'L');
$pdf->Cell(10, 3, utf8_decode($datsan), 1,0, 'C');
$pdf->Cell(130, 3, utf8_decode($detsan), 1, 'L');
$pdf->Ln(5);
}else if ($datsan=='NO') {
 
}
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 3, utf8_decode('3. INHALOTERAPIA: '), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(157, 3, utf8_decode($cuid_gen), 1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 3, utf8_decode('4. MEDICAMENTOS: '), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(157, 3, utf8_decode($med_med), 1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 3, utf8_decode('5. SOLUCIONES: '), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(157, 3, utf8_decode($soluciones), 1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 3, utf8_decode('6. SOLICITAR LABORATORIOS: '), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(157, 3, utf8_decode($perfillab), 1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 3, utf8_decode('7. SOLICITAR IMAGENOLOGIA: '), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(157, 3, utf8_decode($sol_estudios), 1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(38, 3, utf8_decode('8. SOLICITAR BANCO DE SANGRE: '), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(157, 3, utf8_decode($solicitud_sang), 1, 'L');
$pdf->Ln(1);

$pdf->Ln(30);


$sql_med_id = "SELECT id_usua FROM  dat_ordenes_med WHERE id_ord_med=$id_ord ";
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