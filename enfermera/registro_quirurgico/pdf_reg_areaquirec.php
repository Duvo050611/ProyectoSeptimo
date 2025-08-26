<?php
require '../../fpdf/fpdf.php';
include '../../conexionbd.php';
$id_med = @$_GET['id_med'];
$id_exp = @$_GET['id_exp'];
$id_atencion = @$_GET['id_atencion'];
$id_recu = @$_GET['id_recu'];
$fec = @$_GET['fec'];
$sql_clin = "SELECT * FROM recu  where id_atencion = $id_atencion";
$result_clin = $conexion->query($sql_clin);
while ($row_clinreg = $result_clin->fetch_assoc()) {
  $id_recu = $row_clinreg['id_recu'];
}
if(isset($id_recu)){
    $id_recu = $id_recu;
  }else{
    $id_recu ='sin doc';
  }
if($id_recu=="sin doc"){
  echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
                    $(document).ready(function() {
                        swal({
                            title: "NO EXISTE NOTA DE RECUPERACIÓN DE ENFERMERÍA DEL ÁREA QUIRÚRGICA PARA ESTE PACIENTE", 
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

    $this->Image("../../configuracion/admin/img2/".$bas, 9, 15, 45, 25);
    $this->Image("../../configuracion/admin/img3/".$f['img_cpdf'],57,15, 100, 25);
    $this->Image("../../configuracion/admin/img4/".$f['img_dpdf'], 159, 18, 50, 20);
}
    $this->SetFont('Arial', 'B', 15);
    $this->SetTextColor(43, 45, 127);
   
    $this->Ln(10);
    $this->SetDrawColor(43, 45, 180);
    
    $this->SetFont('Arial', '', 8);
   
    $this->Ln(4);
   
    $this->Ln(4);
 
    $this->Ln(4);
 
    $this->Ln(10);
   
  }
   function Footer()
  {
    $this->Ln(8);
    $this->SetY(-15);
    $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}' ), 0, 0, 'C');
    $this->Cell(0, 10, utf8_decode('CMSI-15.03'), 0, 1, 'R');
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

$pdf = new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();
    $pdf->SetTextColor(43, 45, 127);

  $pdf->SetDrawColor(43, 45, 180);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetX(55);
$pdf->Cell(110, 5, utf8_decode('REGISTRO CLÍNICO DE ENFERMERÍA DEL ÁREA QUIRÚRGICA'), 0, 0, 'C');

date_default_timezone_set('America/Guatemala');
$fecha_quir = date("d/m/Y H:i:s");
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(25, 5, utf8_decode('Fecha: '.$fecha_quir), 0, 1, 'L');

$pdf->SetFont('Arial', '', 8);
$pdf->Ln(3);
$date = date_create($fecha);
$pdf->Cell(110, 5, utf8_decode('Fecha de ingreso al hospital: '.date_format($date, "d-m-Y H:i:s")),0, 'L');
$sql_q = "SELECT * from enf_quirurgico where id_atencion=$id_atencion";
$result_q = $conexion->query($sql_q); 
while ($row_q = $result_q->fetch_assoc()) {
  $fecha_quir = $row_q['fecha_quir'];
    
} 

$date2 = date_create($fecha_quir);
$pdf->Cell(80, 5, utf8_decode('Fecha de registro de hoja: '.date_format($date2, "d-m-Y")),0, 'L');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15, 5, 'Servicio: ', 0, 'L');  
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(120, 5, utf8_decode($tipo_a) , 'B', 'L');
$pdf->SetFont('Arial', 'B', 8);
$date=date_create($fecha_ing);
$pdf->Cell(28, 5, utf8_decode(' Fecha de Ingreso:'),0 , 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(32, 5, date_format($date,'d/m/Y H:i:s'), 'B', 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(18, 5, utf8_decode('Expediente: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(15, 5, utf8_decode($folio), 'B', 0, 'C');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(16, 5, ' Paciente: ', 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(146, 5, utf8_decode($papell . ' ' . $sapell . ' ' . $nom_pac), 'B', 'L');
$pdf->SetFont('Arial', 'B', 8);

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 8);
$date1=date_create($fecnac);
$pdf->Cell(31, 5, 'Fecha de nacimiento: ', 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(21, 5, date_format($date1,"d/m/Y"), 'B', 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(11, 5, ' Edad: ', 0, 'L');

$pdf->SetFont('Arial', '', 9);
$pdf->Cell(13, 5, utf8_decode($edad),'B', 'C');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(19, 5, utf8_decode(' Ocupación: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(36, 5,  utf8_decode($ocup), 'B', 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(16, 5, utf8_decode(' Teléfono: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(21, 5,  utf8_decode($tel), 'B', 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(13, 5, utf8_decode('Género: '), 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(14, 5,  $sexo, 'B', 'L');
$pdf->Ln(5);
$pesoh="";
$tallah="";
$sql_sig ="select * from dat_hclinica WHERE Id_exp=$id_atencion ORDER by id_hc DESC LIMIT 1";
$result_sig = $conexion->query($sql_sig);
while ($row_hc = $result_sig->fetch_assoc()) {
$pesoh=$row_hc['peso'];
$tallah=$row_hc['talla'];
}

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(10, 5, utf8_decode('Peso: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(14, 5,  utf8_decode($pesoh.' Kilos'), 'B', 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(11, 5, utf8_decode(' Talla: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(18, 5,  utf8_decode($tallah.' Metros'), 'B', 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(17, 5, utf8_decode(' Domicilio: '), 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(125, 5, utf8_decode($dir), 'B', 'L');

$pdf->Ln(5);

$d="";
    $sql_motd = "SELECT diagprob_i from dat_not_ingreso where id_atencion=$id_atencion ORDER by id_not_ingreso DESC LIMIT 1";
    $result_motd = $conexion->query($sql_motd);
    while ($row_motd = $result_motd->fetch_assoc()) {
        $d=$row_motd['diagprob_i'];
    } 
    $sql_motd = "SELECT diagprob_i from dat_nevol where id_atencion=$id_atencion ORDER by diagprob_i DESC LIMIT 1";
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
  $pdf->Cell(20, 5, utf8_decode('Diagnóstico:') , 0, 'C');
  $pdf->SetFont('Arial', '', 9);
     $pdf->Cell(175, 5, utf8_decode($d) , 'B', 'C');
    } else{
        $pdf->SetFont('Arial', 'B',8);
         $pdf->Cell(29, 5, utf8_decode('Motivo de atención:') , 0, 'C');
         $pdf->SetFont('Arial', '', 9);
         $pdf->Cell(166, 5, utf8_decode($m) , 'B', 'C');
    }

$pdf->Ln(6);

$sql_edo = "SELECT edo_salud,alergias from dat_ingreso where id_atencion=$id_atencion ORDER by edo_salud DESC LIMIT 1";
$result_edo = $conexion->query($sql_edo);                                                                                    while ($row_edo = $result_edo->fetch_assoc()) {
  $edo_salud=$row_edo['edo_salud'];
  $alergias=$row_edo['alergias'];
}
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(23,3, utf8_decode('Tipo de sangre: '),0,'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(10,3, utf8_decode($tip_san),'B','L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(17,3, utf8_decode('Habitación: '),0,'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(10,3, utf8_decode($num_cama),'B','L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(25, 3, 'Tiempo estancia: ', 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(12, 3, $estancia . ' dias', 'B', 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(18,3, utf8_decode('Expediente: '),0,'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(12,3, utf8_decode($folio),'B','L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(25,3, utf8_decode('Estado de salud: '),0,'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(43,3, utf8_decode($edo_salud),'B','L');
$pdf->Ln(3);


$sql_aseg = "SELECT aseg from dat_financieros where id_atencion =$id_atencion ORDER BY fecha DESC limit 1";
  $result_aseg = $conexion->query($sql_aseg);
  while ($row_aseg = $result_aseg->fetch_assoc()) {
 $aseg= $row_aseg['aseg'];
}                      
$pdf->SetFont('Arial', 'B', 8);                                               
$pdf->Cell(20,5, utf8_decode('Aseguradora: '),0,'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(60,5, utf8_decode($aseg),'B','L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15,5, utf8_decode('Alergias: '),0,'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(100,5, utf8_decode($alergias),'B','L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Ln(9);


//CONSULA A ENF QUIR CONSULTA A ENF QUIR CONSULTA A ENF QUIR CONSULTA A ENF QUIR CONSULTA ENF QUIR CONSULTA A ENF 
$sql_q = "SELECT * from recu where id_atencion=$id_atencion";
$result_q = $conexion->query($sql_q); 
while ($row_q = $result_q->fetch_assoc()) {
  $text_fecha = $row_q['text_fecha'];
  $not_recu = $row_q['not_recu'];
 
} 

$fechaq=date_create($text_fecha);
$pdf->Cell(50,5, utf8_decode('Fecha: ' .date_format($fechaq, 'd-m-Y H:i')),1,0,'C');

$pdf->Ln(8);
$pdf->Cell(190,5, utf8_decode('NOTA DE RECUPERACIÓN '),0,0,'C');
$pdf->Ln(5);
$pdf->MultiCell(190,5, utf8_decode($not_recu),1,'L');



$sql_med_id = "SELECT id_usua FROM recu WHERE id_atencion = $id_atencion ORDER by id_recu DESC LIMIT 1";

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

  $pdf->SetY(-24.5);
   
$pdf->SetFont('Arial', '', 6.5);
      $pdf->SetX(55);
      $pdf->Cell(100, 4, utf8_decode(''), 'B', 'C');
        $pdf->SetY(-24);
      $pdf->Cell(190, 3, utf8_decode('NOMBRE Y FIRMA DE ENFERMERA'), 0, 0, 'C');
 $pdf->Output(); 
}