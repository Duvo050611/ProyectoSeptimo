  <?php
  require '../../fpdf/fpdf.php';
  include '../../conexionbd.php';


  $id_atencion = @$_GET['id_atencion'];
  $notid = @$_GET['not_id'];
 // $medico = @$_GET['medico'];
  //$paciente = @$_GET['paciente'];
  //$tipo = @$_GET['tipo'];

  //$id_in = @$_GET['id_in'];
  //$id_med = @$_GET['id_med'];
  //$id_not_nutri = @$_GET['id_not_nutri'];


  //$id_exp = @$_GET['id_exp'];
  //$id_atencion = @$_GET['id_atencion'];


 

  mysqli_set_charset($conexion, "utf8");

  class PDF extends FPDF
  {
    function Header()
    {
     include '../../conexionbd.php';
$resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
while($f = mysqli_fetch_array($resultado)){
       $bas=$f['img_ipdf'];

    $this->Image("../../configuracion/admin/img2/".$bas, 7, 1, 29, 15);
    $this->Image("../../configuracion/admin/img3/".$f['img_cpdf'],45,1, 55, 15);
    $this->Image("../../configuracion/admin/img4/".$f['img_dpdf'], 110, 2, 35, 13);
}
 
   
    }
     function Footer()
  {
    $this->Ln(8);
    $this->SetY(-15);
    $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}' ), 0, 0, 'C');
    $this->Cell(0, 10, utf8_decode('CMSI-071'), 0, 1, 'R');
  }
  }
 
//echo "<br>Tu edad es: $anos años con $meses meses y $dias días";

  $sql_dat_ing = "SELECT * from dat_ingreso where id_atencion = $id_atencion";
  $result_dat_ing = $conexion->query($sql_dat_ing);

  while ($row_dat_ing = $result_dat_ing->fetch_assoc()) {
    $motivo_atn = $row_dat_ing['motivo_atn'];
    $especialidad = $row_dat_ing['especialidad'];
    $id_tratante = $row_dat_ing['id_usua'];
    $fecha_ing=$row_dat_ing['fecha'];
    $tipo_a=$row_dat_ing['tipo_a'];
    $area=$row_dat_ing['area'];
    $id_exp=$row_dat_ing['Id_exp'];
    $id_a=$row_dat_ing['id_atencion'];
  }
  
   $sql_dat_ing = "SELECT * from diag_pac where Id_exp = $id_a order by id_diag DESC limit 1";
   $result_dat_ing = $conexion->query($sql_dat_ing);

  while ($row_dat_ing = $result_dat_ing->fetch_assoc()) {
    $diagp = $row_dat_ing['diag_paciente'];
  }

  $sql_dat_ing = "SELECT * from paciente where Id_exp = $id_exp";
  $result_dat_ing = $conexion->query($sql_dat_ing);

  while ($row_dat_ing = $result_dat_ing->fetch_assoc()) {
    $fecnac = $row_dat_ing['fecnac'];
     $edad = $row_dat_ing['edad'];
       $sexo = $row_dat_ing['sexo'];
  }

  $sql_dat_ingi = "SELECT * from cat_servicios where serv_desc='$tipo'";
  $result_dat_inga = $conexion->query($sql_dat_ingi);

  while ($row_dat_ingu = $result_dat_inga->fetch_assoc()) {
    $desc = $row_dat_ingu['serv_desc'];
    $tipins = $row_dat_ingu['tip_insumo'];
  
  }

///inicio bisiesto
function bisiesto($anio_actual){
    $bisiesto=false;
    //probamos si el mes de febrero del año actual tiene 29 días
      if (checkdate(2,29,$anio_actual))
      {
        $bisiesto=true;
    }
    return $bisiesto;
}

//date_default_timezone_set('America/Mexico_City');
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

  $pdf = new PDF('L', 'mm', array(155,95));
  $pdf->AliasNbPages();
  $pdf->AddPage();
   

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX(1);
$pdf->SetTextColor(43, 45, 127);
$pdf->Ln(7);
$pdf->MultiCell(131, 9, utf8_decode('SOLICITUD DE ESTUDIOS DE LABORATORIO'), 0, 'C');

$sql_med_id = "SELECT * FROM notificaciones_labo WHERE not_id=$notid and id_atencion=$id_atencion order by not_id limit 1";
      $result_med_id = $conexion->query($sql_med_id);
      while ($row_med_id = $result_med_id->fetch_assoc()) {
        $id_med = $row_med_id['id_usua'];
        $fecha_ord = $row_med_id['fecha_ord'];
        $observa = $row_med_id['det_labo'];
        $tipo = $row_med_id['sol_estudios'];
        $habi = $row_med_id['habitacion'];
      }

$pdf->SetDrawColor(43, 45, 127);

$pdf->SetFont('Arial', '', 6);
$pdf->SetDrawColor(43, 45, 127);


$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15, 4, utf8_decode("Paciente:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(95, 4, utf8_decode($atencion),'B', 'C');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(18, 4, utf8_decode("  Habitación:"), 0, 0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(6, 4, $habi,'B',0, 'C');
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
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(200, 3, utf8_decode('Signos vitales: '), 0, 0, 'L');
$pdf->Ln(3);

$pdf->SetFont('Arial', '', 7);
$pdf->Cell(36, 3, utf8_decode('Presión arterial: ' .$p_sistolica.'/'.$p_diastolica.' mmHG'), 1, 'L');
$pdf->Cell(43, 3, utf8_decode('Frecuencia respiratoria: ' .$f_resp.' Resp/min'), 1, 'L');
$pdf->Cell(25, 3, utf8_decode('Temperatura: ' .$temp.'°C'), 1, 'L');
$pdf->Cell(30, 3, utf8_decode('Saturación oxígeno: ' .$sat_oxigeno.'%'), 1, 'L');

$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(12, 3, utf8_decode("Edad:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
if($anos > "0" ){
  $pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(25, 3, utf8_decode($anos . ' años' ), 'B', 'C');
}elseif($anos <="0" && $meses>"0"){
     $pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(25, 3, utf8_decode($meses), 'B', 'C');
}elseif($anos <="0" && $meses<="0" && $dias>"0"){
    $pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(25, 3, utf8_decode($dias), 'B', 'C');
}
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(13, 3, utf8_decode(" Sexo:"), 0, 0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(-1, 3, utf8_decode(""), 0, 0, 'C');
$pdf->Cell(15, 3, utf8_decode($sexo),'B', 'C');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(35, 3, utf8_decode("  Fecha de nacimiento:"), 0, 0, 'C');
$fechaes=date_create($fecnac);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(35, 3, date_format($fechaes,'d/m/Y'),'B', 'C');
$pdf->Ln(4);
      
$fecha_ingreso=date_create($fecha_ing); 
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(26, 3, utf8_decode("Fecha de ingreso:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(32, 3, date_format($fecha_ingreso,'d/m/Y H:i a'),'B', 'C');

$fecha_orden=date_create($fecha_ord);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(40, 3, utf8_decode("    Fecha y hora de solicitud:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(36, 3, date_format($fecha_orden,'d/m/Y H:i a'),'B', 'C');
$pdf->Ln(4);

$sql_reg_usrt = "SELECT * from reg_usuarios where id_usua=$id_tratante";
$result_reg_usrt = $conexion->query($sql_reg_usrt);
while ($row_reg_usrt = $result_reg_usrt->fetch_assoc()) {
  $user_prefijo = $row_reg_usrt['pre'];
  $user_tratante = $row_reg_usrt['papell'];
  $cedula = $row_reg_usrt['cedp'];
}
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(26, 3, utf8_decode('Médico tratante:'),0 , 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(108, 3, utf8_decode(' '.$user_prefijo.'. '.$user_tratante.' Cédula Prof. ' . $cedula), 'B', 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(26, 3, utf8_decode("Solicitate:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(108, 3, utf8_decode($medico),'B', 'C');
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(34, 3, utf8_decode("Estudio(s) solicitado(s):"), 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(100, 3, utf8_decode($tipo . ' ' . $tipins),'B', 'J');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(34, 4, utf8_decode("Detalle de estudio:"), 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(100, 4, utf8_decode($observa),'B', 'J');





  $sql_med_id = "SELECT id_usua FROM notificaciones_labo WHERE not_id=$notid and id_atencion=$id_atencion order by not_id limit 1";
      $result_med_id = $conexion->query($sql_med_id);

      while ($row_med_id = $result_med_id->fetch_assoc()) {
        $id_med = $row_med_id['id_usua'];
        $fecha_ord = $row_med_id['fecha_ord'];
        
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

      $pdf->SetFont('Arial', 'B', 6);
      $pdf->Image('../../imgfirma/' . $firma, 70, 74, 15);
      $pdf->MultiCell(130, 2, utf8_decode('Solicita: '.$pre . '. ' . $app ),0, 'C');


 $pdf->Output();
  