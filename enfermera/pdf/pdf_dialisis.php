<?php
require '../../fpdf/fpdf.php';
include '../../conexionbd.php';
$id_med = @$_GET['id_med'];
$id_exp = @$_GET['id_exp'];
$id_atencion = @$_GET['id_atencion'];
$fechar = @$_GET['fechar'];
$id_dialisis = @$_GET['id_dialisis'];



$sql_clin = "SELECT * FROM dialisis_p  where id_atencion = $id_atencion";
$result_clin = $conexion->query($sql_clin);
while ($row_clinreg = $result_clin->fetch_assoc()) {
  $id_dialisis = $row_clinreg['id_dialisis'];
}
if(isset($id_dialisis)){
    $id_dialisis = $id_dialisis;
  
  }else{
    $id_dialisis ='sin doc';
  }
if($id_dialisis=="sin doc"){
  echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
                    $(document).ready(function() {
                        swal({
                            title: "NO EXISTE REGISTRO PARA DIÁLISIS PERITONEAL PARA ESTE PACIENTE", 
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
include '../../conexionbd.php';
$resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
while($f = mysqli_fetch_array($resultado)){
       $bas=$f['img_ipdf'];

    $this->Image("../../configuracion/admin/img2/".$bas, 10, 4, 55, 25);
    $this->Image("../../configuracion/admin/img3/".$f['img_cpdf'],95,4, 100, 25);
    $this->Image("../../configuracion/admin/img4/".$f['img_dpdf'], 235, 7, 50, 20);
}
  
    $this->SetFont('Arial', 'B', 15);
    $this->SetTextColor(43, 45, 127);
    
    $this->Ln(13);
   
    $this->Ln(10);
  
  }
  function Footer()
  {
    $this->Ln(8);
    $this->SetY(-15);
    $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}' ), 0, 0, 'C');
    $this->Cell(0, 10, utf8_decode('SIMA-18.00'), 0, 1, 'R');
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
   $tip_san = $row_pac['tip_san'];
      $folio = $row_pac['folio'];
}


$sql_ing = "SELECT * FROM dat_ingreso  where Id_exp = $id_exp";
$result_ing = $conexion->query($sql_ing);
while ($row_ing = $result_ing->fetch_assoc()) {
$especialidad = $row_ing['especialidad'];
  $fecha = $row_ing['fecha'];
  $area= $row_ing['area'];
    $tipo_a= $row_ing['tipo_a'];
  

}

$sql_f = "SELECT enf_fecha FROM enf_reg_clin  where id_atencion = $id_atencion";
$result_f = $conexion->query($sql_f);
while ($row_f = $result_f->fetch_assoc()) {
$enf_fecha = $row_f['enf_fecha'];

  

}

$sql_camas = "SELECT * FROM cat_camas  where id_atencion = $id_atencion";
$result_camas = $conexion->query($sql_camas);
while ($row_camas = $result_camas->fetch_assoc()) {

  $num_cama = $row_camas['num_cama'];
}



      $sql_now = "SELECT DATE_ADD(NOW(), INTERVAL 12 HOUR) as dat_now FROM dat_ingreso WHERE id_atencion = $id_atencion";

      $result_now = $conexion->query($sql_now);

      while ($row_now = $result_now->fetch_assoc()) {
        $dat_now = $row_now['dat_now'];
      }

      $sql_est = "SELECT DATEDIFF( '$dat_now' , fecha) as estancia FROM dat_ingreso WHERE id_atencion = $id_atencion";

      $result_est = $conexion->query($sql_est);

      while ($row_est = $result_est->fetch_assoc()) {
        $estancia = $row_est['estancia'];
      }

$pdf=new PDF('L','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Ln(-1);
    $pdf->SetTextColor(43, 45, 127);

  $pdf->SetDrawColor(43, 45, 180);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetX(55);
$pdf->Cell(183, 5, utf8_decode('REGISTRO PARA DIÁLISIS PERITONEAL - ENFERMERÍA'), 0, 0, 'C');
$pdf->Cell(7, 5, utf8_decode(''), 0, 0, 'C');
date_default_timezone_set('America/Guatemala');
$fecha_quir = date("d/m/Y H:i:s");
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(32, 5, utf8_decode('Fecha: '.$fecha_quir), 0, 1, 'L');

$pdf->SetFont('Arial', '', 9);
$pdf->Ln(3);
$date = date_create($fecha);

$sql_q = "SELECT * from enf_quirurgico where id_atencion=$id_atencion";
$result_q = $conexion->query($sql_q); 
while ($row_q = $result_q->fetch_assoc()) {
  $fecha_quir = $row_q['fecha_quir'];
    
} 

//$date2 = date_create($fecha_quir);
//$pdf->Cell(80, 5, utf8_decode('Fecha de registro de hoja: '.date_format($date2, "d-m-Y")),0, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(32, 3, 'Nombre del paciente: ', 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(148, 3, utf8_decode($papell . ' ' . $sapell . ' ' . $nom_pac), 'B', 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(10.5, 3, ' Edad: ', 0, 'L');
$pdf->SetFont('Arial', '', 8);

$pdf->Cell(13, 3, utf8_decode($edad),'B', 'C');


$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(12.5, 3, utf8_decode('Género: '), 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(25, 3,  $sexo, 'B', 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(17.5,3, utf8_decode('Habitación: '),0,'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(17,3, utf8_decode($num_cama),'B','L');

$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 8);
$date1=date_create($fecnac);
$pdf->Cell(32, 3, 'Fecha de nacimiento: ', 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(35, 3, date_format($date1,"d/m/Y"), 'B', 'C');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(27, 3, utf8_decode('Fecha de ingreso: '),0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(30, 3, utf8_decode(date_format($date, "d-m-Y")),'B', 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(25, 3, utf8_decode('Hora de ingreso: '),0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(32, 3, utf8_decode(date_format($date, "H:i:s")),'B', 'L');

$pesoh="";
$tallah="";
$sql_sig ="select * from dat_hclinica WHERE Id_exp=$id_atencion ORDER by id_hc DESC LIMIT 1";
$result_sig = $conexion->query($sql_sig);
while ($row_hc = $result_sig->fetch_assoc()) {
$pesoh=$row_hc['peso'];
$tallah=$row_hc['talla'];
}
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(21, 3, utf8_decode('Peso ingreso: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(25, 3,  utf8_decode($pesoh.' Kilos'), 'B', 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(20, 3, utf8_decode('Peso Egreso: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(28.5, 3,  utf8_decode($pesoh.' Kilos'), 'B', 'L');

$doctor="";
$sql_med_id = "SELECT * FROM dat_ingreso WHERE id_atencion=$id_atencion";
    $result_med_id = $conexion->query($sql_med_id);

    while ($row_med_id = $result_med_id->fetch_assoc()) {
      $id_med = $row_med_id['id_usua'];
    }

 $sql_med = "SELECT * FROM reg_usuarios WHERE id_usua = $id_med";
    $result_med = $conexion->query($sql_med);

    while ($row_med = $result_med->fetch_assoc()) {
      $doctor = $row_med['nombre'];
}

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(13, 3, utf8_decode('Médico: '), 0, 'L');  
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(80.1, 3, utf8_decode($doctor) , 'B', 'L');

$d="";
      $sql_motd = "SELECT diagprob_i from dat_nevol where id_atencion=$id_atencion ORDER by diagprob_i ASC LIMIT 1";
    $result_motd = $conexion->query($sql_motd);
    while ($row_motd = $result_motd->fetch_assoc()) {
        $d=$row_motd['diagprob_i'];
    } ?>
    <?php $sql_mot = "SELECT motivo_atn from dat_ingreso where id_atencion=$id_atencion ORDER by motivo_atn ASC LIMIT 1";
    $result_mot = $conexion->query($sql_mot);
    while ($row_mot = $result_mot->fetch_assoc()) {
    $m=$row_mot['motivo_atn'];
    } 
if ($d!=null) {
    $pdf->SetFont('Arial', 'B', 8);
  $pdf->Cell(19.5, 3, utf8_decode('Diagnóstico:') , 0, 'C');
  $pdf->SetFont('Arial', '', 8);
     $pdf->Cell(163, 3, utf8_decode($d) , 'B', 'C');
    } else{
        $pdf->SetFont('Arial', 'B', 8);
         $pdf->Cell(28.5, 3, utf8_decode('Motivo de atención:') , 0, 'C');
         $pdf->SetFont('Arial', '', 8);
         $pdf->Cell(154, 3, utf8_decode($m) , 'B', 'C');
    }


$pdf->Ln(8);



$pdf->SetFont('Arial', 'B', 7.5);                                               
$pdf->Cell(17,4, utf8_decode('No. de baño'),1,0,'C');
$pdf->Cell(24,4, utf8_decode('Tipo de solución'),1,0,'C');
$pdf->Cell(22,4, utf8_decode('Hrs de entrada'),1,0,'C');
$pdf->Cell(20,4, utf8_decode('Hrs de salida'),1,0,'C');
$pdf->Cell(25,4, utf8_decode('Cantidad entrada'),1,0,'C');
$pdf->Cell(23,4, utf8_decode('Cantidad salida '),1,0,'C');
$pdf->Cell(22,4, utf8_decode('Balance parcial'),1,0,'C');
$pdf->Cell(20,4, utf8_decode('Balance total'),1,0,'C');
$pdf->Cell(68,4, utf8_decode('Observaciones'),1,0,'C');
$pdf->Cell(35,4, utf8_decode('Enfermera'),1,0,'C');
$sql_dia = "SELECT * from dialisis_p where fecha_registro='$fechar' and id_atencion =$id_atencion ORDER BY id_dialisis ASC";
  $res_dialisis = $conexion->query($sql_dia);
  while ($row_di = $res_dialisis->fetch_assoc()) {
    $id_usua=$row_di["id_usua"];
$pdf->Ln(4);
$pdf->SetFont('Arial', '', 7); 
    $pdf->Cell(17,4, utf8_decode($baño= $row_di['baño']),1,0,'C');
$pdf->Cell(24,4, utf8_decode($tiposol= $row_di['tiposol']),1,0,'C');
$pdf->Cell(22,4, utf8_decode($hrentrada= $row_di['hrentrada']),1,0,'C');
$pdf->Cell(20,4, utf8_decode($hrsalida= $row_di['hrsalida']),1,0,'C');
$pdf->Cell(25,4, utf8_decode($centrada= $row_di['centrada']),1,0,'C');
$pdf->Cell(23,4, utf8_decode($csalida= $row_di['csalida']),1,0,'C');
$pdf->Cell(22,4, utf8_decode($balparcial= $row_di['balparcial']),1,0,'C');
$pdf->Cell(20,4, utf8_decode($baltot= $row_di['baltot']),1,0,'C');
$pdf->Cell(68,4, utf8_decode($obs= $row_di['obs']),1,0,'C');

$resultado_usua = $conexion->query("SELECT * FROM reg_usuarios WHERE id_usua=$id_usua") or die($conexion->error);

        while($row = mysqli_fetch_array($resultado_usua)){

$pdf->Cell(35,4, utf8_decode($row['nombre'] . ' ' . $row['papell']),1,0,'C');
 
}     

}
 $pdf->Output(); 
}