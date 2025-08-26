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



$sql_pac = "SELECT p.papell, p.nom_pac,p.fecnac,p.sapell,p.edad,p.sexo,p.Id_exp,p.dir,p.id_edo,p.id_mun,p.tel,p.ocup,p.resp,p.paren,p.tel_resp, p.religion, p.folio FROM paciente p, dat_ingreso di where di.id_atencion = $id_atencion and di.Id_exp=p.Id_exp";
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
    $folio = $row_pac['folio'];
}

function bisiesto($anio_actual){
    $bisiesto=false;
    //probamos si el mes de febrero del año actual tiene 29 días
      if (checkdate(2,29,$anio_actual))
      {
        $bisiesto=true;
    }
    return $bisiesto;
}

date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d");
$fecha_nac=$fecnac;
$fecha_de_nacimiento =strval($fecha_nac);

// separamos en partes las fechas
$array_nacimiento = explode ( "-", $fecha_de_nacimiento );
$array_actual = explode ( "-", $fecha_actual );

$anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos años
$meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses
$dias =  $array_actual[2] - $array_nacimiento[2]; // calculamos días

//ajuste de posible negativo en $días
if ($dias < 0)
{
    --$meses;

    //ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual
    switch ($array_actual[1]) {
           case 1:     $dias_mes_anterior=31; break;
           case 2:     $dias_mes_anterior=31; break;
           case 3:     
               if (bisiesto($array_actual[0]))
                {
                    $dias_mes_anterior=29; break;
                } else {
                    $dias_mes_anterior=28; break;
                }
               
           case 4:     $dias_mes_anterior=31; break;
           case 5:     $dias_mes_anterior=30; break;
           case 6:     $dias_mes_anterior=31; break;
           case 7:     $dias_mes_anterior=30; break;
           case 8:     $dias_mes_anterior=31; break;
           case 9:     $dias_mes_anterior=31; break;
           case 10:     $dias_mes_anterior=30; break;
           case 11:     $dias_mes_anterior=31; break;
           case 12:     $dias_mes_anterior=30; break;
    }

    $dias=$dias + $dias_mes_anterior;
}

//ajuste de posible negativo en $meses
if ($meses < 0)
{
    --$anos;
    $meses=$meses + 12;
}

//echo "<br>Tu edad es: $anos años con $meses meses y $dias días";

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
$pdf->Cell(120, 5, utf8_decode('MEDICAMENTOS'), 0, 0, 'C');
$pdf->SetFont('Arial', '', 8);

$fecha_actual = date("d/m/Y H:i");
$pdf->Cell(35, 5, 'FECHA: ' . $fecha_actual, 0, 1, 'R');
$pdf->Ln(5);

$pdf->SetDrawColor(43, 45, 127);
$pdf->Line(8, 50, 207, 50);
$pdf->Line(8, 50, 8, 280);
$pdf->Line(207, 50, 207, 280);
$pdf->Line(8, 280, 207, 280);


$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(17, 3, 'Servicio: ', 0, 'L');  
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(124, 3, utf8_decode($tipo_a) , 'B', 'L');
$pdf->SetFont('Arial', 'B', 6);
$date=date_create($fecha_ing);
$pdf->Cell(28, 3, utf8_decode('Fecha de ingreso:'),0 , 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(25, 3, date_format($date,'d-m-Y H:i:s'), 'B', 0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(20, 3, utf8_decode('Expediente: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(8, 3, utf8_decode($folio), 'B', 0, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(40, 3, 'Paciente: ', 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(126, 3, utf8_decode($papell . ' ' . $sapell . ' ' . $nom_pac), 'B', 'L');
$pdf->SetFont('Arial', 'B', 6);

$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$date=date_create($fecnac);
$pdf->Cell(37, 3, 'Fecha de nacimiento: ', 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(20, 3, date_format($date,"d/m/Y"), 'B', 'C');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(12, 3, 'Edad: ', 0, 'L');



$pdf->SetFont('Arial', '', 6);
if($anos > "0" ){
  $pdf->SetFont('Arial', '', 6);
$pdf->Cell(12, 3, utf8_decode($anos . ' AÑOS' ),'B', 'C');
}elseif($anos <="0" && $meses>"0"){
     $pdf->SetFont('Arial', '', 6);
$pdf->Cell(12, 3, utf8_decode($meses),'B', 'C');
}elseif($anos <="0" && $meses<="0" && $dias>"0"){
    $pdf->SetFont('Arial', '', 6);
$pdf->Cell(12, 3, utf8_decode($dias),'B', 'C');
}
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(21, 3, utf8_decode(' Ocupación: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(31, 3,  utf8_decode($ocup), 'B', 'L');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(28, 3, utf8_decode('Teléfono: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(25, 3,  utf8_decode($tel), 'B', 'L');

$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(13, 3, utf8_decode('Género: '), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(22, 3,  $sexo, 'B', 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(18, 3, utf8_decode('Domicilio: '), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(141, 3, utf8_decode($dir), 'B', 'L');
$pdf->Ln(4);

$sql_diag = "SELECT * from diag_pac where id_exp=$id_atencion ORDER by id_diag DESC LIMIT 1";

$result_diag = $conexion->query($sql_diag);

while ($row_diag = $result_diag->fetch_assoc()) {
   $diag_paciente = $row_diag['diag_paciente'];
}
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(195, 4, utf8_decode('Diagnóstico: '.$diag_paciente), 1, 0, 'L');

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
$pdf->Cell(31, 3, utf8_decode('Presión arterial: ' .$p_sistolica.'/'.$p_diastolica), 1, 'L');
$pdf->Cell(26, 3, utf8_decode('Frecuencia cardiaca: ' .$f_card), 1, 'L');
$pdf->Cell(30, 3, utf8_decode('Frecuencia respiratoria: ' .$f_resp), 1, 'L');
$pdf->Cell(26, 3, utf8_decode('Temperatura: ' .$temp), 1, 'L');
$pdf->Cell(32, 3, utf8_decode('Saturación oxígeno: ' .$sat_oxigeno), 1, 'L');
$pdf->Cell(15, 3, utf8_decode('Peso: ' .$pesoh), 1, 'L');
$pdf->Cell(15, 3, utf8_decode('Talla: ' .$tallah), 1, 'L');
$pdf->Cell(20, 3, utf8_decode('Escala EVA: ' .$niv_dolor), 1, 'L');
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 6);
$pdf->MultiCell(195, 6, utf8_decode('MEDICAMENTOS'), 0, 'C');

$pdf->Cell(70,5, utf8_decode('Medicamentos'),1,0,'C');
$pdf->Cell(15,5, utf8_decode('Dósis'),1,0,'C');
$pdf->Cell(19,5, utf8_decode('Vía'),1,0,'C');
$pdf->Cell(13,5, utf8_decode('Fecha'),1,0,'C');
$pdf->Cell(8,5, utf8_decode('Hora'),1,0,'C');

$pdf->Cell(40,5, utf8_decode('Otros'),1,0,'C');
$pdf->Cell(30,5, utf8_decode('Turno'),1,0,'C');

$cis = $conexion->query("select * from medica_enf where id_atencion=$id_atencion AND fecha_mat='$fecha' ORDER BY horario_mat DESC") or die($conexion->error);
while ($cis_s = $cis->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
$pdf->Ln(5);
$date=date_create($cis_s['fecha']);
$pdf->Cell(70,5, utf8_decode($cis_s['medicam_mat']),1,0,'C');
$pdf->Cell(15,5, utf8_decode($cis_s['dosis_mat']),1,0,'C');
$pdf->Cell(19,5, utf8_decode($cis_s['via_mat']),1,0,'C');

$pdf->Cell(13,5, date_format($date,"d/m/Y"),1,0,'C');
$pdf->Cell(8,5, utf8_decode($cis_s['hora_mat']),1,0,'C');

$pdf->Cell(40,5, utf8_decode($cis_s['otro']),1,0,'C');
$pdf->Cell(30,5, utf8_decode($cis_s['turno']),1,0,'C');
}


$pdf->Ln(30);


$sql_med_id = "SELECT id_usua FROM medica_enf WHERE id_atencion=$id_atencion";
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
     
     if ($firma==null) {
 $pdf->Image('../../imgfirma/FIRMA.jpg', 97, 253 , 25);
} else {
    $pdf->Image('../../imgfirma/' . $firma, 97, 253 , 25);
}
      $pdf->SetY(264);
      $pdf->Cell(200, 4, utf8_decode($pre . '. ' . $app . ' ' . $apm . ' ' . $nom), 0, 0, 'C');
      $pdf->Ln(4);
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->Cell(200, 4, utf8_decode($cargp . ' ' . 'CÉD. PROF. ' . $ced_p), 0, 0, 'C');
      $pdf->Ln(4);
      $pdf->Cell(200, 4, utf8_decode('NOMBRE Y FIRMA DEL MÉDICO'), 0, 0, 'C');

 $pdf->Output();