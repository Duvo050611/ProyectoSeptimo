<?php

use PDF as GlobalPDF;

require '../../fpdf/fpdf.php';
include '../../conexionbd.php';
$id_in=@$_GET['id_in'];
$id_exp = @$_GET['id_exp'];
$id_atencion = @$_GET['id_atencion'];
$sql_tras = "SELECT * from dat_traslado where id_atencion=$id_atencion";
$result_tras = $conexion->query($sql_tras);

while ($row_tras = $result_tras->fetch_assoc()) {
  $id_traslado = $row_tras['id_traslado'];
}

if(isset($id_traslado)){
    $id_traslado = $id_traslado;
  }else{
    $id_traslado ='sin doc';
  }

if($id_traslado=="sin doc"){
  echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
                    $(document).ready(function() {
                        swal({
                            title: "NO EXISTE NOTA DE TRASLADO PARA ESTE PACIENTE", 
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
   include '../../conexionbd.php';
    $resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
    while($f = mysqli_fetch_array($resultado)){
       $bas=$f['img_ipdf'];

    $this->Image("../../configuracion/admin/img2/".$bas, 7, 8, 48, 25);
    $this->Image("../../configuracion/admin/img3/".$f['img_cpdf'],58,15, 109, 24);
    $this->Image("../../configuracion/admin/img4/".$f['img_dpdf'], 168, 16, 38, 14);
}
$this->Ln(32);
  }

 function Footer()
  {
    $this->Ln(8);
    $this->SetY(-15);
    $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}' ), 0, 0, 'C');
    $this->Cell(0, 10, utf8_decode('MAC-5.04'), 0, 1, 'R');
  }
}

$sql_pac = "SELECT p.papell, p.nom_pac,p.sapell,p.edad,p.sexo,p.Id_exp,p.folio,p.dir,p.id_edo,p.id_mun,p.tel,p.ocup,p.resp,p.paren,p.tel_resp, p.fecnac FROM paciente p where p.Id_exp = $id_exp";
$result_pac = $conexion->query($sql_pac);

while ($row_pac = $result_pac->fetch_assoc()) {
  $papell = $row_pac['papell'];
  $nom_pac = $row_pac['nom_pac'];
  $sapell = $row_pac['sapell'];
  $edad = $row_pac['edad'];
  $sexo = $row_pac['sexo'];
  $Id_exp = $row_pac['Id_exp'];
  $folio = $row_pac['folio'];
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

  


$sql_preop = "SELECT * FROM dat_ingreso where id_atencion = $id_atencion";
$result_preop = $conexion->query($sql_preop);

while ($row_preop = $result_preop->fetch_assoc()) {
 
    $tipo_a = $row_preop['tipo_a'];
    $fecha_ing = $row_preop['fecha'];
}

$pdf = new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(8, 10, 10);
#Establecemos el margen inferior:
$pdf->SetAutoPageBreak(true,32);
$sql_ing = "SELECT * FROM dat_not_ingreso where id_atencion = $id_atencion";
$result_ing = $conexion->query($sql_ing);

while ($row_ing = $result_ing->fetch_assoc()) {
 $fecha_dat_ingreso=$row_ing['fecha_dat_ingreso'];
 $fecha_actual=$row_ing['fecha_dat_ingreso'];
 $mot_ingreso=$row_ing['mot_ingreso'];
 $resinterr_i=$row_ing['resinterr_i'];
 $expfis_i=$row_ing['expfis_i'];
 $resaux_i=$row_ing['resaux_i'];
 $diagprob_i=$row_ing['diagprob_i'];
 $plan_i=$row_ing['plan_i'];
 $des_diag=$row_ing['des_diag'];
 $pron_i=$row_ing['pron_i'];
  $guia=$row_ing['guia'];

}

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX(30);
$pdf->SetTextColor(43, 45, 127);
$pdf->MultiCell(165, 9.5, utf8_decode('NOTA DE TRASLADO'), 0, 'C');


$pdf->SetDrawColor(43, 45, 127);
$pdf->Line(48, 52, 172, 52);
$pdf->Line(48, 41, 48, 52);
$pdf->Line(172, 41, 172, 52);
$pdf->Line(48, 41, 172, 41);

$pdf->SetX(170);

$pdf->SetFont('Arial', '', 6);
//date_default_timezone_set('America/Guatemala');
$date1=date_create($fecha_actual);
$pdf->Cell(35, -2, 'FECHA: ' . date_format($date1,"d/m/Y H:i"), 0, 1, 'R');

$pdf->SetDrawColor(43, 45, 127);
$pdf->Line(8, 53, 207, 53);
$pdf->Line(8, 53, 8, 280);
$pdf->Line(207, 53, 205, 280);
$pdf->Line(8, 280, 205, 280);
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

$sql_sig ="select * from signos_vitales WHERE id_atencion=$id_atencion ORDER by id_sig DESC LIMIT 1";
$result_sig = $conexion->query($sql_sig);
while ($row_sig = $result_sig->fetch_assoc()) {
 $p_sistolica=$row_sig['p_sistol'];
 $p_diastolica=$row_sig['p_diastol'];
 $f_card=$row_sig['fcard'];
 $f_resp=$row_sig['fresp'];
 $temp=$row_sig['temper'];
 $sat_oxigeno=$row_sig['satoxi'];
 $niv_dolor=$row_sig['niv_dolor'];
}

$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(200, 4, utf8_decode('SIGNOS VITALES: '), 0, 0, 'L');
$pdf->Ln(4);

$pdf->SetFont('Arial', '', 6.5);
$pdf->Cell(41, 3, utf8_decode('Presión arterial: ' .$p_sistolica.'/'.$p_diastolica.' mmHG/mmHG'), 1, 'L');
$pdf->Cell(35, 3, utf8_decode('Frecuencia cardiaca: ' .$f_card.' Lat/min'), 1, 'L');
$pdf->Cell(40, 3, utf8_decode('Frecuencia respiratoria: ' .$f_resp.' Resp/min'), 1, 'L');
$pdf->Cell(23, 3, utf8_decode('Temperatura: ' .$temp.'°C'), 1, 'L');
$pdf->Cell(29, 3, utf8_decode('Saturación oxígeno: ' .$sat_oxigeno.'%'), 1, 'L');
$pdf->Cell(28, 3, utf8_decode('Nivel de dolor (EVA): ' .$niv_dolor), 1, 'L');


$pdf->Ln(4);
$sql_med_id = "SELECT id_usua FROM dat_not_ingreso WHERE id_atencion = $id_atencion ORDER by fecha_dat_ingreso DESC LIMIT 1";
    $result_med_id = $conexion->query($sql_med_id);

    while ($row_med_id = $result_med_id->fetch_assoc()) {
      $id_med = $row_med_id['id_usua'];
    }
$sql_reg_usrs = "SELECT * from reg_usuarios where id_usua=$id_med";
$result_reg_usrs = $conexion->query($sql_reg_usrs);

while ($row_reg_usrs = $result_reg_usrs->fetch_assoc()) {
  $user_pre = $row_reg_usrs['pre'];
  $user_papell = $row_reg_usrs['papell'];
  $user_sapell = $row_reg_usrs['sapell'];
  $user_nombre = $row_reg_usrs['nombre'];
  $user_cedp = $row_reg_usrs['cedp'];
}

$sql_tras = "SELECT * from dat_traslado where id_traslado=$id_in and id_atencion=$id_atencion";
$result_tras = $conexion->query($sql_tras);

while ($row_tras = $result_tras->fetch_assoc()) {
  $horenv = $row_tras['horenv'];
  $env = $row_tras['env'];
  $rec = $row_tras['rec'];
  $docenv = $row_tras['docenv'];
  $docrec = $row_tras['docrec'];
  $resumclin = $row_tras['resumclin'];
  $motenv = $row_tras['motenv'];
  $imdiagn = $row_tras['imdiagn'];
  $ter = $row_tras['ter'];
}

$datenv=date_create($horenv);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(98, 6, utf8_decode('Fecha y hora de traslado : '),1, 'L');
$pdf->Cell(98, 6, utf8_decode('Fecha y hora de recibo : '), 1, 'L');
$pdf->Ln(6);
$pdf->Cell(98, 6, utf8_decode('Unidad que envia : '.$env),1,'L');
$pdf->Cell(98, 6, utf8_decode('Unidad que RECIBE : '. $rec), 1, 'L');

$pdf->Ln(6);
$pdf->Cell(98, 6, utf8_decode('Doctor que envia : '.$docenv),1, 'L');
$pdf->Cell(98, 6, utf8_decode('Doctor que RECIBE : '.$docrec), 1, 'L');

$pdf->Ln(8);
$pdf->SetFont('Arial', 'B', 8);
$pdf->MultiCell(110, 4, utf8_decode('Resumen clínico :    '),0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(196, 4, utf8_decode($resumclin),1, 'J');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(33, 4, utf8_decode('Motivo de envio :    '),1, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(163, 4, utf8_decode($motenv),1, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(33, 4, utf8_decode('Impresión diagnóstica:    '),1, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(163, 4, utf8_decode($imdiagn),1, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(33, 4, utf8_decode('Terapeútica empleada:    '),1, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(163, 4, utf8_decode($ter),1, 'L');

$sql_med_id = "SELECT id_usua FROM dat_traslado WHERE id_atencion = $id_atencion ORDER by id_traslado DESC LIMIT 1";
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

$pdf->Ln(2);

$pdf->Cell(151,4, utf8_decode('Medicamento'),1,0,'C');
$pdf->Cell(20,4, utf8_decode('Dósis'),1,0,'C');
$pdf->Cell(25,4, utf8_decode('Vía'),1,0,'C');



$pdf->Ln(4);
$medica = $conexion->query("select * from medica_enf WHERE id_atencion=$id_atencion and neonato!='Si' and material!='Si' group by medicam_mat ORDER BY id_med_reg DESC") or die($conexion->error);
while ($row_m = $medica->fetch_assoc()) {
$hora_mat=$row_m['hora_mat'];
$fecha_mat=$row_m['fecha_mat'];

$datemed=date_create($fecha_mat);

if($hora_mat=='8:00' || $hora_mat=='9:00' || $hora_mat=='10:00'|| $hora_mat=='11:00'|| $hora_mat=='12:00' || $hora_mat=='13:00'|| $hora_mat=='14:00'){
$turno="MATUTINO";
}else if ($hora_mat=='15:00' || $hora_mat=='16:00' || $hora_mat=='17:00'|| $hora_mat=='18:00'|| $hora_mat=='19:00' || $hora_mat=='20:00') {
  $turno="VESPERTINO";
}else if ($hora_mat=='21:00' || $hora_mat=='22:00' || $hora_mat=='23:00' || $hora_mat=='24:00'|| $hora_mat=='1:00'|| $hora_mat=='2:00' || $hora_mat=='3:00' || $hora_mat=='4:00' || $hora_mat=='5' || $hora_mat=='6:00' || $hora_mat=='7:00') {
    $turno="NOCTURNO";
}
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(151,4, utf8_decode($row_m['medicam_mat']),1,0,'L');
$pdf->Cell(20,4, $row_m['dosis_mat'],1,0,'C');
$pdf->MultiCell(25,4, $row_m['via_mat'],1,'C');

}
   
$pdf->Ln(20);
     
      $pdf->SetFont('Arial', 'B', 7);
      $pdf->Image('../../imgfirma/' . $firma, 95, 246, 25);
 
      $pdf->sety(258);
      $pdf->Cell(200, 1, utf8_decode($pre . '. ' . $app), 0, 0, 'C');
      $pdf->Ln(3);
      $pdf->SetFont('Arial', 'B', 7);
      $pdf->Cell(200, 1, utf8_decode($cargp . ' ' . 'Céd. Prof. ' . $ced_p), 0, 0, 'C');
      $pdf->Ln(3);
      $pdf->Cell(200, 1, utf8_decode('Nombre y firma del médico'), 0, 0, 'C');

$pdf->Output();
}