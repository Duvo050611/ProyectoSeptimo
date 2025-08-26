  <?php
  require '../../fpdf/fpdf.php';
  include '../../conexionbd.php';
  $id_med = @$_GET['id_med'];
  $id_exp = @$_GET['id_exp'];
  $id_atencion = @$_GET['id_atencion'];
  /*
  $sql_dat_eg = "SELECT * from dat_egreso where id_atencion = $id_atencion";
  $result_dat_eg = $conexion->query($sql_dat_eg);

  while ($row_dat_eg = $result_dat_eg->fetch_assoc()) {
    $id_egreso = $row_dat_eg['id_egreso'];
  }
  if(isset($id_egreso)){
      $id_egreso = $id_egreso;
    }else{
      $id_egreso ='sin doc';
    }

  if($id_egreso=="sin doc"){
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
      echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
      echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
      echo '<script>
                      $(document).ready(function() {
                          swal({
                              title: "NO EXISTE NOTA DE EGRESO PARA ESTE PACIENTE", 
                              type: "error",
                              confirmButtonText: "ACEPTAR"
                          }, function(isConfirm) { 
                              if (isConfirm) {
                                  window.close();
                              }
                          });
                      });
                  </script>';
  }else{*/

  mysqli_set_charset($conexion, "utf8");

  class PDF extends FPDF
  {
    function Header()
  {
    $this->Image('../../imagenes/logo_pdf.jpg', 10, 8, 30, 33);
    $this->Image('../../imagenes/encabezado.jpg', 40, 8, 135, 35);
    $this->Image('../../imagenes/logo_pdf2.jpg', 167, 22, 39, 17);
    $this->Ln(35);
  }
     function Footer()
  {
    $this->Ln(8);
    $this->SetFont('Arial', '', 7);
    $this->SetY(-15);
    $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}' ), 0, 0, 'C');
    
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

  $sql_dat_ing = "SELECT * from dat_ingreso where id_atencion = $id_atencion";
  $result_dat_ing = $conexion->query($sql_dat_ing);

  while ($row_dat_ing = $result_dat_ing->fetch_assoc()) {
    $motivo_atn = $row_dat_ing['motivo_atn'];
    $especialidad = $row_dat_ing['especialidad'];
    $id_usua = $row_dat_ing['id_usua'];
    $fecha_ing=$row_dat_ing['fecha'];
    $tipo_a=$row_dat_ing['tipo_a'];
  }
  $pdf = new PDF('P');
  $pdf->AliasNbPages();
 


/************** aqui empieza la nota de egreso ***************
  $pdf->AddPage();
  $pdf->SetFont('Arial', 'B', 10);
  $pdf->SetX(50);
  $pdf->Cell(120, 5, utf8_decode('NOTA DE EGRESO'), 0, 0, 'C');
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
$pdf->Cell(19, 3, 'SERVICIO: ', 0, 'L');  
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(175, 3, utf8_decode($tipo_a) , 'B', 'L');
$pdf->SetFont('Arial', 'B', 6);

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
 $sql_dat_ing = "SELECT * from dat_ingreso where id_atencion = $id_atencion";
  $result_dat_ing = $conexion->query($sql_dat_ing);

  while ($row_dat_ing = $result_dat_ing->fetch_assoc()) {
    $motivo_atn = $row_dat_ing['motivo_atn'];
    $especialidad = $row_dat_ing['especialidad'];
    $id_usua = $row_dat_ing['id_usua'];
    $fecha_ing=$row_dat_ing['fecha'];
  }

  $sql_dat_eg = "SELECT * from dat_egreso where id_atencion = $id_atencion";
  $result_dat_eg = $conexion->query($sql_dat_eg);

  while ($row_dat_eg = $result_dat_eg->fetch_assoc()) {
    $reingreso = $row_dat_eg['reingreso'];
    $diag_eg = $row_dat_eg['diag_eg'];
    $fech_egreso = $row_dat_eg['fech_egreso'];
    $res_clin = $row_dat_eg['res_clin'];
    $diagfinal=$row_dat_eg['diagfinal'];
    $reingreso=$row_dat_eg['reingreso'];
    $cond=$row_dat_eg['cond'];
    $dias=$row_dat_eg['dias'];
    $manejodur=$row_dat_eg['manejodur'];
    $probclip=$row_dat_eg['probclip'];
    $cuid=$row_dat_eg['cuid'];
    $trat=$row_dat_eg['trat'];
    $exes=$row_dat_eg['exes'];
    $pcita=$row_dat_eg['pcita'];
    $hcita=$row_dat_eg['hcita'];
  }

$pdf->SetFont('Arial', 'B', 6);
$fecha=date_create($fecha_ing);
$pdf->Cell(24, 4, utf8_decode('FECHA DE INGRESO: '),1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(23, 4, utf8_decode(date_format($fecha,'d-m-Y H:i:s')),1, 'L');
$fecha_egreso=date_create($fech_egreso);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(23, 4, utf8_decode('FECHA DE EGRESO: '),1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(13, 4, utf8_decode(date_format($fecha_egreso,"d-m-Y")),1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(20, 4, utf8_decode('DIAS ESTANCIA: '),1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(5, 4, utf8_decode($dias),1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(22, 4, utf8_decode('MOTIVO EGRESO: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(40, 4, utf8_decode($cond) , 1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(15, 4, utf8_decode('REINGRESO: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(10, 4, utf8_decode($reingreso),1, 'C');

$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(195, 3, utf8_decode('SIGNOS VITALES'), 0,0, 'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(32, 3, utf8_decode('PRESIÓN ARTERIAL: ' .$p_sistolica.'/'.$p_diastolica), 1, 'L');
$pdf->Cell(25, 3, utf8_decode('FREC. CARDIACA: ' .$f_card), 1, 'L');
$pdf->Cell(30, 3, utf8_decode('FREC. RESPIRATORIA: ' .$f_resp), 1, 'L');
$pdf->Cell(26, 3, utf8_decode('TEMPERATURA: ' .$temp), 1, 'L');
$pdf->Cell(32, 3, utf8_decode('SATURACIÓN OXIGENO: ' .$sat_oxigeno), 1, 'L');
$pdf->Cell(15, 3, utf8_decode('PESO: ' .$peso), 1, 'L');
$pdf->Cell(15, 3, utf8_decode('TALLA: ' .$talla), 1, 'L');
$pdf->Cell(20, 3, utf8_decode('ESCALA EVA: ' .$niv_dolor), 1, 'L');
$pdf->Ln(5);


$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(32, 3, utf8_decode('DIAGNÓSTICO DE INGRESO: '), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(163, 3, utf8_decode($diagfinal),1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(32, 3, utf8_decode('DIAGNÓSTICO EGRESO: '), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(163, 3, utf8_decode($diag_eg),1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(32, 3, utf8_decode('RESUMEN EVOLUCIÓN: '), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(163, 3, utf8_decode($res_clin),1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 5.6);
$pdf->Cell(32, 3, utf8_decode('MANEJO DURANTE ESTANCIA: '), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(163, 3, utf8_decode($manejodur),1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(32, 3, utf8_decode('CUIDADOS EN EL HOGAR: '), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(163, 3, utf8_decode($cuid),1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(32, 3, utf8_decode('PROBLEMAS CLÍNICOS PEND.'), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(163, 3, utf8_decode($probclip),1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(32, 3, utf8_decode('ESTUDIOS DE SEGUIMIENTO: '), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(163, 3, utf8_decode($exes),1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 5.8);
$pdf->Cell(32, 3, utf8_decode('TRATAMIENTO A SU EGRESO: '), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(163, 3, utf8_decode($trat),1, 'L');
$pdf->Ln(1);

if ($pcita <> 0000-00-00) 
{
  $pcita=date_create($pcita);
  $pcita = date_format($pcita,"d-m-Y");
}
else { 
  $pcita = " ";
}

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(32, 3, utf8_decode('FECHA PROXIMA CITA: '), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(163, 3, utf8_decode($pcita. ' ' . $hcita),1, 'L');

if ($cond == "VOLUNTARIA") {
  $pdf->Ln(2);
  $pdf->SetFont('Arial', 'B', 6);
  $pdf->Cell(99, 6, utf8_decode('NOTA DE ALTA VOLUNTARIA DEL FAMILIAR: '), 0, 'L');
  $pdf->Ln(5);
  $pdf->MultiCell(195, 16, ' ',1, 'L');
  $pdf->Ln(1);
}

  $sql_med_id = "SELECT id_usua FROM dat_egreso WHERE id_atencion = $id_atencion ORDER by  fech_egreso DESC LIMIT 1";
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


      $pdf->SetY(-65);
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->Image('../../imgfirma/' . $firma, 95, 245, 30);
    
      $pdf->SetY(260);
      $pdf->Cell(200, 4, utf8_decode($pre . '. ' . $app . ' ' . $apm . ' ' . $nom), 0, 0, 'C');
      $pdf->Ln(4);
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->Cell(200, 4, utf8_decode($cargp . ' ' . 'CÉD. PROF. ' . $ced_p), 0, 0, 'C');
      $pdf->Ln(4);
      $pdf->Cell(200, 4, utf8_decode('NOMBRE Y FIRMA DEL MÉDICO'), 0, 0, 'C');
      $pdf->Cell(0, 4, utf8_decode('SIMA-024'), 0, 1, 'R');


*********** AQUI INICIA LA HOJA DE EGRESO PARA EL PACIENTE    ***************
  $pdf->AddPage();
  
  $pdf->SetFont('Arial', 'B', 10);
  $pdf->SetX(50);
  $pdf->Cell(120, 5, utf8_decode('HOJA DE EGRESO'), 0, 0, 'C');
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
$pdf->Cell(19, 3, 'SERVICIO: ', 0, 'L');  
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(175, 3, utf8_decode($tipo_a) , 'B', 'L');
$pdf->SetFont('Arial', 'B', 6);

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

$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 6);
$fecha=date_create($fecha_ing);
$pdf->Cell(24, 4, utf8_decode('FECHA DE INGRESO: '),1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(23, 4, utf8_decode(date_format($fecha,'d-m-Y H:i:s')),1, 'L');
$fecha_egreso=date_create($fech_egreso);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(23, 4, utf8_decode('FECHA DE EGRESO: '),1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(17, 4, utf8_decode(date_format($fecha_egreso,"d-m-Y")),1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(22, 4, utf8_decode('DIAS ESTANCIA: '),1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(6, 4, utf8_decode($dias),1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(25, 4, utf8_decode('MOTIVO DE EGRESO: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(55, 4, utf8_decode($cond) , 1, 'L');

$pdf->Ln(1);

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
$pdf->Ln(4);


$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(32, 3, utf8_decode('DIAGNÓSTICO INGRESO: '), 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->MultiCell(163, 4, utf8_decode($diagfinal),1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(32, 3, utf8_decode('DIAGNÓSTICO EGRESO: '), 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->MultiCell(163, 3, utf8_decode($diag_eg),1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(32, 3, utf8_decode('RESUMEN EVOLUCIÓN: '), 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->MultiCell(163, 3, utf8_decode($res_clin),1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(32, 3, utf8_decode('CONDICIONES DE ALTA:'), 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->MultiCell(163, 3, utf8_decode($cond),1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 6.8);
$pdf->Cell(32, 3, utf8_decode('CUIDADOS EN EL HOGAR: '), 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(163, 4, utf8_decode($cuid),1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 8);
$pdf->MultiCell(32, 4, utf8_decode('TRATAMIENTO: '), 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(195, 4, utf8_decode($trat),1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(32, 3, utf8_decode('ESTUDIOS DE SEGUIMIENTO: '), 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->MultiCell(163, 3, utf8_decode($exes),1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(32, 3, utf8_decode('FECHA PROXIMA CITA: '), 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(163, 4, utf8_decode($pcita . ' ' . $hcita),1, 'L');

$pdf->Ln(20);


  $sql_med_id = "SELECT id_usua FROM dat_egreso WHERE id_atencion = $id_atencion ORDER by  fech_egreso DESC LIMIT 1";
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
       
  $pdf->SetY(-80);
  $pdf->Image('../../imgfirma/' . $firma, 22, 245, 30);
    
  $pdf->SetY(260);
  $pdf->SetX(5);
  $pdf->SetFont('Arial', 'B', 7);

  $pdf->Cell(65, 4, utf8_decode(''.$pre . '. ' . $app . ' ' . $apm . ' ' . $nom), 0, 0, 'C');
  $pdf->Ln(4);
  $pdf->SetX(5);  
  $pdf->Cell(65, 4, utf8_decode($cargp . ' ' . 'CÉD. PROF. ' . $ced_p), 0, 0, 'C');
  $pdf->Ln(4);
  $pdf->SetX(5);
  $pdf->Cell(65, 4, utf8_decode('NOMBRE Y FIRMA DEL MÉDICO'), 0, 0, 'C');
  $pdf->SetX(130);
  $pdf->Cell(50, 4, utf8_decode('NOMBRE Y FIRMA DEL FAMILIAR QUE RECIBE HOJA DE EGRESO'), 0, 0, 'C');  
  $pdf->Ln(4);
      
  $pdf->SetX(140);
  $pdf->Cell(60, 4, utf8_decode('COPIA PARA EL PACIENTE                            SIMA-025'), 0, 1, 'R');
      
$pdf->Ln(20);
  

************* INICIA IMPRESION DE RECETA 


$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetY(44);
$pdf->SetX(50);
$pdf->Cell(120, 6, utf8_decode('RECETA MÉDICA'), 0, 0, 'C');

$pdf->SetFont('Arial', '', 6);
  date_default_timezone_set('America/Guatemala');
  $fecha_actual = date("d/m/Y");
  $pdf->Cell(35, 5, 'Toluca, Mex, ' . $fecha_actual, 0, 1, 'R');

$pdf->Ln(3);
$pdf->SetDrawColor(0, 0, 0);
$pdf->Line(8, 50, 205, 50);
$pdf->Line(8, 50, 8, 280);
$pdf->Line(205, 50, 205, 280);
$pdf->Line(8, 280, 205, 280);


if($especialidad=="OTROS"){
   $pdf->SetFont('Arial', 'B', 8);
   $pdf->Cell(25, 5, utf8_decode('SERVICIO: '), 0, 'L'); 
   $pdf->SetFont('Arial', '', 8);
   $pdf->Cell(110, 5, utf8_decode($tipo_a) , 'B', 'L');
}else{
   $pdf->SetFont('Arial', 'B', 8);
   $pdf->Cell(25, 5, utf8_decode('SERVICIO: '), 0, 'L'); 
   $pdf->SetFont('Arial', '', 8);
   $pdf->Cell(110, 5, utf8_decode($tipo_a) , 'B', 'L');
}

 $pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(20, 5, utf8_decode('EXPEDIENTE: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(8, 5, utf8_decode($Id_exp), 'B', 0, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(40, 5, ' NOMBRE DEL PACIENTE: ', 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(126, 5, utf8_decode($papell . ' ' . $sapell . ' ' . $nom_pac), 'B', 'L');
$pdf->SetFont('Arial', 'B', 8);

$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 8);
$date=date_create($fecnac);
$pdf->Cell(37, 5, 'FECHA DE NACIMIENTO: ', 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(20, 5, date_format($date,"d/m/Y"), 'B', 'C');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(12, 5, ' EDAD: ', 0, 'L');

$edad=calculaedad($fecnac);

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(20, 5, utf8_decode($edad), 'B', 'C');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(21, 5, utf8_decode(' OCUPACIÓN: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(31, 5,  utf8_decode($ocup), 'B', 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(28, 5, utf8_decode('TELÉFONO: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(25, 5,  utf8_decode($tel), 'B', 'L');

$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(13, 5, 'SEXO: ', 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(22, 5,  $sexo, 'B', 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(18, 5, utf8_decode(' DOMICILIO: '), 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(141, 5, utf8_decode($dir), 'B', 'L');

$pdf->Ln(6);

$pdf->SetFont('Arial', 'B', 6);
$fecha=date_create($fecha_ing);
$pdf->Cell(24, 4, utf8_decode('FECHA DE INGRESO: '),1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(23, 4, utf8_decode(date_format($fecha,'d-m-Y H:i:s')),1, 'L');
$fecha_egreso=date_create($fech_egreso);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(23, 4, utf8_decode('FECHA DE EGRESO: '),1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(17, 4, utf8_decode(date_format($fecha_egreso,"d-m-Y")),1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(22, 4, utf8_decode('DIAS ESTANCIA: '),1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(6, 4, utf8_decode($dias),1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(25, 4, utf8_decode('MOTIVO DE EGRESO: '), 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(55, 4, utf8_decode($cond) , 1, 'L');

$pdf->Ln(1);

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
$pdf->Ln(5);


$pdf->SetDrawColor(0, 0, 0);
$pdf->Line(10, 94, 204, 94);
$pdf->Line(10, 94, 10, 238);
$pdf->Line(204, 94, 204, 238);
$pdf->Line(10, 238, 204, 238);
$pdf->SetFont('Arial', 'B', 8);
$pdf->MultiCell(32, 5, utf8_decode('TRATAMIENTO: '), 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(195, 5, utf8_decode($trat),0, 'L');
$pdf->Ln(1);


$pdf->SetY(240);
$pdf->SetX(10);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(34, 4, utf8_decode('FECHA PRÓXIMA CITA: '), 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(158, 4, utf8_decode($pcita . ' ' . $hcita),1, 'L');
      
 
      $pdf->SetY(-65);
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->Image('../../imgfirma/' . $firma, 95, 245, 30);
    
      $pdf->SetY(260);
      $pdf->Cell(200, 4, utf8_decode($pre . '. ' . $app . ' ' . $apm . ' ' . $nom), 0, 0, 'C');
      $pdf->Ln(4);
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->Cell(200, 4, utf8_decode($cargp . ' ' . 'CÉD. PROF. ' . $ced_p), 0, 0, 'C');
      $pdf->Ln(4);
      $pdf->Cell(200, 4, utf8_decode('NOMBRE Y FIRMA DEL MÉDICO'), 0, 0, 'C');
      $pdf->Cell(0, 4, utf8_decode('SIMA-010'), 0, 1, 'R');

*/
      
/*********** AQUI INICIA LA DE ENCUESTA    ***************/

$pdf->AddPage();
$pdf->SetX(45);

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(125, 6, utf8_decode('ENCUESTA DE SATISFACCIÓN DEL CLIENTE'),0, 0, 'C');
$pdf->SetFont('Arial', '', 6);
  date_default_timezone_set('America/Guatemala');
$fecha_actual = date("d/m/Y");
$pdf->Cell(35, 5, 'Toluca, Mex, ' . $fecha_actual, 0, 1, 'R');
 $pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(195, 6, utf8_decode('FOLIO DE ATENCIÓN: ' . $id_atencion. ' EXPEDIENTE: '. $Id_exp . ' PACIENTE: '. $papell . ' ' . $sapell . ' ' . $nom_pac), 1,0, 6);
$pdf->Ln(7);
 
$pdf->SetFont('Arial', 'B', 8);

$pdf->MultiCell(195, 10, utf8_decode('1. ELIJA UNA CALIFICACIÓN SI FUE ATENDIDO CON RESPETO POR PARTE DEL PERSONAL DE:'),0, 'L');

$pdf->Image('../../img/encuesta_3.jpg', 175, 60, 28);

$pdf->SetFont('Arial', '', 7);
$pdf->Cell(165, 6, utf8_decode('    RECEPCIÓN'),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(165, 6, utf8_decode('    ENFERMERÍA'),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(165, 6, utf8_decode('    MÉDICO'),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(165, 6, utf8_decode('    OTRO PERSONAL DEL SANATORIO'),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Ln(6);

$pdf->SetFont('Arial', 'B', 8);
$pdf->MultiCell(195, 11, utf8_decode('2. ELIJA UNA CALIFICACIÓN SI FUE ATENDIDO AL MOMENTO DE SOLICITARLO POR EL PERSONAL DE:'), 0, 'L');
$pdf->Image('../../img/encuesta_3.jpg', 175, 95, 28);

$pdf->SetFont('Arial', '', 7);
$pdf->Cell(165, 6, utf8_decode('    RECEPCIÓN'),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(165, 6, utf8_decode('    ENFERMERÍA'),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(165, 6, utf8_decode('    MÉDICO'),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(165, 6, utf8_decode('    OTRO PERSONAL DEL SANATORIO'),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Ln(6);

$pdf->SetFont('Arial', 'B', 8);
$pdf->MultiCell(195, 11, utf8_decode('3. BRINDE SU GRADO DE SATISFACCIÓN CON LA CALIDAD DE LA ATENCIÓN RECIBIDA:'), 0, 'L');
  
$pdf->Image('../../img/encuesta_3.jpg', 175, 130, 28);

$pdf->SetFont('Arial', '', 7);
$pdf->Cell(165, 6, utf8_decode('    RECEPCIÓN'),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(165, 6, utf8_decode('    ENFERMERÍA'),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(165, 6, utf8_decode('    MÉDICO'),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(165, 6, utf8_decode('    OTRO PERSONAL DEL SANATORIO'),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Ln(6);

$pdf->SetFont('Arial', 'B', 8);
$pdf->MultiCell(195, 11, utf8_decode('4. ELIJA UNA CALIFICACIÓN DE LOS SERVICIOS RECIBIDOS DE:'), 0, 'L');
 
$pdf->Image('../../img/encuesta_3.jpg', 175, 165, 28);


$pdf->SetFont('Arial', '', 7);
$pdf->Cell(165, 6, utf8_decode('    INSTALACIONES DEL HOSPITAL'),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(165, 6, utf8_decode('    LIMPIEZA DE HABITACIÓN'),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Ln(6);

$pdf->SetFont('Arial', '', 7);
$pdf->Cell(165, 6, utf8_decode('    ROPA DE LA HABITACIÓN'),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Ln(6);

$pdf->SetFont('Arial', '', 7);
$pdf->Cell(165, 6, utf8_decode('    SERVICIO DE ALIMENTACIÓN'),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Ln(6);

$pdf->SetFont('Arial', '', 7);
$pdf->Cell(165, 6, utf8_decode('    LABORATORIO'),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Ln(6);

$pdf->SetFont('Arial', '', 7);
$pdf->Cell(165, 6, utf8_decode('    IMAGENOLOGÍA'),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Ln(6);

$pdf->SetFont('Arial', '', 7);
$pdf->Cell(165, 6, utf8_decode('    VIGILANCIA'),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Ln(6);


$pdf->SetFont('Arial', 'B', 8);
$pdf->MultiCell(195, 11, utf8_decode('5. ELIJA UNA ESCALA DE RECOMENDACIÓN:'), 0, 'L');

$pdf->Image('../../img/encuesta_3.jpg', 175, 218, 28);
 
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(165, 6, utf8_decode('    RECOMENDARÍA USTED AL SANATORIO VENECIA A OTRAS PERSONAS'),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');
$pdf->Cell(10, 6, utf8_decode(' '),1, 0, 'L');

$pdf->Ln(7);
$pdf->SetFont('Arial', 'B', 7);
$pdf->MultiCell(195, 6, utf8_decode('6. OBSERVACIONES:'), 0, 'L');
$pdf->MultiCell(195, 22, utf8_decode(' '), 1, 'L');
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(80, 10, utf8_decode('FECHA: ___________/____________/____________'), 0, 'L');
$pdf->Cell(80, 10, utf8_decode('HORA: _____________________'), 0, 'L');
$pdf->Ln(2);

 $pdf->SetY(270);
 $pdf->Cell(0, 4, utf8_decode('SIMA-026'), 0, 1, 'R');

 $pdf->Output();
 // }