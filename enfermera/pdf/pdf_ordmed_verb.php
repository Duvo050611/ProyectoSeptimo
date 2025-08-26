<?php
require '../../fpdf/fpdf.php';
include '../../conexionbd.php';
$id_atencion = @$_GET['id_atencion'];
$id_ord = @$_GET['id_ord'];
$id_med = @$_GET['id_med'];
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
    $this->Cell(0, 10, utf8_decode('SIMA-028'), 0, 1, 'R');
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
  $Id_exp= $row_pac['Id_exp'];
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

  $medico = $row_ormed['medico'];  
  $enfermera_tes = $row_ormed['enfermera_tes'];  
}
//termino ordenes

$pdf = new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(20, 5, utf8_decode('FECHA '.$fecha_ord), 0, 0, 'C');
$pdf->Cell(50, 5, utf8_decode('HORA '.$hora_ord), 0, 0, 'C');
$pdf->SetFont('Arial', 'B', 11);
$pdf->SETX(50);
$pdf->MultiCell(120, 6, utf8_decode('INDICACIONES MÉDICAS (VERBALES)'), 0, 'C');
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
$pdf->Cell(17, 3, 'Servicio: ', 0, 'L');  
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(124, 3, utf8_decode($tipo_a) , 'B', 'L');
$pdf->SetFont('Arial', 'B', 6);
$date=date_create($fecha_ing);
$pdf->Cell(28, 3, utf8_decode('Fecha Ingreso:'),0 , 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(25, 3, date_format($date,'d/m/Y H:i:s'), 'B', 0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(20, 3, utf8_decode('Expediente: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(8, 3, utf8_decode($folio), 'B', 0, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(40, 3, ' Paciente: ', 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(126, 3, utf8_decode($papell . ' ' . $sapell . ' ' . $nom_pac), 'B', 'L');
$pdf->SetFont('Arial', 'B', 6);

$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$date1=date_create($fecnac);
$pdf->Cell(37, 3, 'Fecha de nacimiento: ', 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(20, 3, date_format($date1,"d/m/Y"), 'B', 'C');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(12, 3, ' Edad: ', 0, 'L');



$pdf->SetFont('Arial', '', 6);
if($anos > "0" ){
  $pdf->SetFont('Arial', '', 6);
$pdf->Cell(20, 3, utf8_decode($anos . ' AÑOS' ),1, 'C');
}elseif($anos <="0" && $meses>"0"){
     $pdf->SetFont('Arial', '', 6);
$pdf->Cell(20, 3, utf8_decode($meses),1, 'C');
}elseif($anos <="0" && $meses<="0" && $dias>"0"){
    $pdf->SetFont('Arial', '', 6);
$pdf->Cell(20, 3, utf8_decode($dias),1, 'C');
}
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
$pdf->MultiCell(195, 6, utf8_decode('INDICACIONES MÉDICAS (VERBALES)'), 0, 'C');


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

/*
$pdf->SetX(12);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(53, 3, utf8_decode('CUIDADOS GENERALES '), 1, 0,'C');
$pdf->Cell(10, 3, utf8_decode('SI / NO' ), 1, 'C');
$pdf->Cell(130, 3, utf8_decode('DESCRIPCIÓN' ), 1, 'C');
$pdf->Ln(3);

$pdf->SetX(12);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(53, 3, utf8_decode('SIGNOS VITALES POR TURNO: '), 1, 'L');
$pdf->Cell(10, 3, utf8_decode($signos ), 1,0, 'C');
$pdf->Cell(130, 3, utf8_decode($detsignos ), 1, 'L');
$pdf->Ln(3);
$pdf->SetX(12);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(53, 3, utf8_decode('MONITOREO CONSTANTE: '), 1, 'L');
$pdf->Cell(10, 3, utf8_decode($monitoreo ), 1,0, 'C');
$pdf->Cell(130, 3, utf8_decode($detmonitoreo ), 1, 'L');
$pdf->Ln(3);
$pdf->SetX(12);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(53, 3, utf8_decode('DIURESIS POR TURNO: '), 1, 'L');
$pdf->Cell(10, 3, utf8_decode($diuresis ), 1,0, 'C');
$pdf->Cell(130, 3, utf8_decode($detdiuresis), 1, 'L');
$pdf->Ln(3);
$pdf->SetX(12);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(53, 3, utf8_decode('DEXTROSTIS POR TURNO: '), 1, 'L');
$pdf->Cell(10, 3, utf8_decode($dex ), 1,0, 'C');
$pdf->Cell(130, 3, utf8_decode($detdex), 1, 'L');
$pdf->Ln(3);
$pdf->SetX(12);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(53, 3, utf8_decode('POSICIÓN SEMIFLOWER: '), 1, 'L');
$pdf->Cell(10, 3, utf8_decode($semif ), 1,0, 'C');
$pdf->Cell(130, 3, utf8_decode($detsemif), 1, 'L');
$pdf->Ln(3);
$pdf->SetX(12);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(53, 3, utf8_decode('VIGILAR DATOS DEL PACIENTE NEUROLÓGICO:'), 1, 'L');
$pdf->Cell(10, 3, utf8_decode($vigilar ), 1,0, 'C');
$pdf->Cell(130, 3, utf8_decode($detvigilar ), 1, 'L');
$pdf->Ln(3);
$pdf->SetX(12);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(53, 3, utf8_decode('OXÍGENO:'), 1, 'L');
$pdf->Cell(10, 3, utf8_decode($oxigeno ), 1,0, 'C');
$pdf->Cell(130, 3, utf8_decode($detoxigeno ), 1, 'L');
$pdf->Ln(3);
$pdf->SetX(12);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(53, 3, utf8_decode('NEBULIZACIONES:'), 1, 'L');
$pdf->Cell(10, 3, utf8_decode($nebulizacion), 1,0, 'C');
$pdf->Cell(130, 3, utf8_decode($detnebu), 1, 'L');
$pdf->Ln(3);
$pdf->SetX(12);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(53, 3, utf8_decode('BARANDALES EN ALTO:'), 1, 'L');
$pdf->Cell(10, 3, utf8_decode($bar), 1,0, 'C');
$pdf->Cell(130, 3, utf8_decode($detbar), 1, 'L');
$pdf->Ln(3);
$pdf->SetX(12);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(53, 3, utf8_decode('BAÑO DIARIO Y DEAMBULACIÓN ASISTIDA:'), 1, 'L');
$pdf->Cell(10, 3, utf8_decode($baño), 1,0, 'C');
$pdf->Cell(130, 3, utf8_decode($detbaño), 1, 'L');
$pdf->Ln(3);
$pdf->SetX(12);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(53, 3, utf8_decode('CUIDADOS SONDA FOLEY:'), 1, 'L');
$pdf->Cell(10, 3, utf8_decode($foley), 1,0, 'C');
$pdf->Cell(130, 3, utf8_decode($detfoley), 1, 'L');
$pdf->Ln(3);
$pdf->SetX(12);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(53, 3, utf8_decode('EJERCICIOS RESPIRATORIOS:'), 1, 'L');
$pdf->Cell(10, 3, utf8_decode($ej), 1,0, 'C');
$pdf->Cell(130, 3, utf8_decode($detej), 1, 'L');
$pdf->Ln(3);
$pdf->SetX(12);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(53, 3, utf8_decode('VIGILAR DATOS DE SANGRADO:'), 1, 'L');
$pdf->Cell(10, 3, utf8_decode($datsan), 1,0, 'C');
$pdf->Cell(130, 3, utf8_decode($detsan), 1, 'L');
$pdf->Ln(5);
*/
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


$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 7);
$pdf->MultiCell(195, 3, utf8_decode('MÉDICO QUE ORDENA INDICACIONES VERBALES: ' . ' ' . $medico ), 1, 'L');
$pdf->Ln(6);

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

}
      $pdf->SetY(-45);
      $pdf->SetFont('Arial', 'B', 7);
      $pdf->Image('../../imgfirma/' . $firma, 35, $pdf->SetY(-55), 30, 10);
      $pdf->Ln(6);
      $pdf->SetX(10);
      $pdf->Cell(70, 4, utf8_decode($pre . '. ' . $app . ' ' . $apm . ' ' . $nom), 0, 0, 'C');
      $pdf->Cell(160, 4, utf8_decode($enfermera_tes), 0, 0, 'C');
      $pdf->Ln(6);
      $pdf->Cell(70, 4, utf8_decode('CÉDULA PROFESIONAL: ' . $ced_p), 0, 0, 'C');
      $pdf->SetX(10);
      $pdf->Ln(6);
      $pdf->Cell(70, 4, utf8_decode('ENFERMERA QUE RECIBE INDICACIONES VERBALES'), 0, 0, 'C');
      $pdf->Cell(160, 4, utf8_decode('NOMBRE Y FIRMA DE LA ENFERMERA TESTIGO'), 0, 0, 'C');

 $pdf->Output();