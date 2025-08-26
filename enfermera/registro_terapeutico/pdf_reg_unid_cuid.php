<?php
require '../../fpdf/fpdf.php';
include '../../conexionbd.php';
$id_med = @$_GET['id_med'];
$id_exp = @$_GET['id_exp'];
$id_atencion = @$_GET['id_atencion'];
$fechar = @$_GET['fechar'];
$hora_m = @$_GET['hora_m'];


$sql_clin = "SELECT * FROM enf_ter  where fecha_m='$fechar' and id_atencion = $id_atencion";
$result_clin = $conexion->query($sql_clin);

while ($row_clinreg = $result_clin->fetch_assoc()) {
  $id_enf_mat = $row_clinreg['id_enf_mat'];
}

if(isset($id_enf_mat)){
    $id_enf_mat = $id_enf_mat;
  }else{
    $id_enf_mat ='sin doc';
  }

if($id_enf_mat=="sin doc"){
  echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
                    $(document).ready(function() {
                        swal({
                            title: "NO EXISTE REGISTRO CLÍNICO DE ENFERMERÍA DE UNIDAD DE CUIDADOS INTENSIVOS PARA ESTE PACIENTE", 
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
    $id_exp = @$_GET['id_exp'];
$fechar = @$_GET['fechar'];
$hora_m = @$_GET['hora_m'];
    include '../../conexionbd.php';

    $id_atencion = @$_GET['id_atencion'];
include '../../conexionbd.php';

$sql_pac = "SELECT * FROM paciente  where Id_exp = $id_exp";
$result_pac = $conexion->query($sql_pac);

while ($row_pac = $result_pac->fetch_assoc()) {
  $papell = $row_pac['papell'];
  $nom_pac = $row_pac['nom_pac'];
  $sapell = $row_pac['sapell'];
  $Id_exp = $row_pac['Id_exp'];
 
}

$resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
while($f = mysqli_fetch_array($resultado)){
       $bas=$f['img_ipdf'];

    $this->Image("../../configuracion/admin/img2/".$bas, 9, 15, 46, 25);
    $this->Image("../../configuracion/admin/img3/".$f['img_cpdf'],57,15, 100, 25);
    $this->Image("../../configuracion/admin/img4/".$f['img_dpdf'], 159, 18, 50, 20);
}
    $this->SetFont('Arial', 'B', 15);
    $this->SetTextColor(43, 45, 127);
   
    $this->Ln(10);
  
    $this->Ln(4);

    $this->Ln(4);
   
    $this->Ln(4);
   
   
    $this->Ln(6);
    $this->SetFont('Arial', 'B', 7);
    $this->SetTextColor(43, 45, 127);
    $this->Cell(160, 5, utf8_decode($Id_exp . '-' . $papell . ' ' . $sapell . ' ' . $nom_pac), 0, 'L');
   include '../../conexionbd.php';
  
    $sat = $conexion->query("select * from enf_ter where hora_m='$hora_m' and fecha_m='$fechar' AND id_atencion=$id_atencion ORDER by id_enf_mat DESC LIMIT 1") or die($conexion->error);
    while ($sat_r = $sat->fetch_assoc()) {
        $fec=$sat_r['fecha_m'];
        $fech=date_create($fec);
    }
    //$date2 = date_create($fecha_quir);
    $this->Cell(44,6, 'Fecha de registro: '. date_format($fech, "d/m/Y"),0,0,'C');
    $this->Ln(4);
  }
  function Footer()
  {
    $this->Ln(8);
    $this->SetY(-15);
    $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}' ), 0, 0, 'C');
    $this->Cell(0, 10, utf8_decode('CMSI-15.04'), 0, 1, 'R');
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
    $fechai = $row_ing['fecha'];
    $area= $row_ing['area'];
    $tipo_a= $row_ing['tipo_a'];
}

$sql_f = "SELECT fecha_m FROM enf_ter  where id_enf_mat = $id_atencion";
$result_f = $conexion->query($sql_f);
while ($row_f = $result_f->fetch_assoc()) {
$enf_fecha = $row_f['fecha_m'];
}

$sql_camas = "SELECT * FROM cat_camas  where id_atencion = $id_atencion";
$result_camas = $conexion->query($sql_camas);
while ($row_camas = $result_camas->fetch_assoc()) {
  $num_cama = $row_camas['num_cama'];
}

    $sql_est = "SELECT DATEDIFF(fecha_m, '$fechai') as estancia FROM enf_ter where id_atencion = $id_atencion and fecha_m='$fechar'";
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
    $pdf->Cell(110, 5, utf8_decode('REGISTRO CLÍNICO DE ENFERMERÍA DE TERAPIA INTENSIVA'), 0, 0, 'C');

//date_default_timezone_set('America/Guatemala');
    $fecha_quir = date("d/m/Y H:i a");
    $pdf->SetFont('Arial', '', 6.5);
//$pdf->Cell(25, 5, utf8_decode('Fecha de impresión: '.$fecha_quir), 0, 1, 'L');

    $pdf->SetFont('Arial', '', 8);
    $pdf->Ln(3);
    $date = date_create($fechai);
//$pdf->Cell(110, 5, utf8_decode('Fecha de ingreso al hospital: '.date_format($date, "d/m/Y H:i a")),0, 'L');
    $sql_q = "SELECT * from enf_ter where hora_m='$hora_m' and fecha_m='$fechar' AND id_atencion=$id_atencion";
    $result_q = $conexion->query($sql_q); 
    while ($row_q = $result_q->fetch_assoc()) {
        $fecha_quir = $row_q['fecha_quir'];
} 

$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15, 5, 'Servicio: ', 0, 'L');  
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(120, 5, utf8_decode($tipo_a) , 'B', 'L');
$pdf->SetFont('Arial', 'B', 8);
//$date=date_create($fecha_ing);
$pdf->Cell(28, 5, utf8_decode(' Fecha de Ingreso:'),0 , 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(32, 5, date_format($date, "d/m/Y H:i a"), 'B', 0, 'C');
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
$pdf->Cell(25,3, utf8_decode('Grupo sanguineo: '),0,'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(22,3, utf8_decode($tip_san),'B','L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(17,3, utf8_decode('Habitación: '),0,'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(10,3, utf8_decode($num_cama),'B','L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(25, 3, 'Tiempo estancia: ', 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(12, 3, $estancia . ' dias', 'B', 'L');
$pdf->SetFont('Arial', 'B', 8);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(25,3, utf8_decode('Estado de salud: '),0,'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60,3, utf8_decode($edo_salud),'B','L');
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
$pdf->SetFont('Arial', 'B', 6);
$pdf->Ln(9);

$pdf->SetFont('Arial', 'B', 9);
$pdf->Ln(1);


$sat = $conexion->query("select * from enf_ter where hora_m='$hora_m' and fecha_m='$fechar' AND id_atencion=$id_atencion ORDER by id_enf_mat") or die($conexion->error);
while ($sat_r = $sat->fetch_assoc()) {
  $pdf->SetFont('Arial', 'B', 9);
$fec=$sat_r['fecha_m'];
$fech=date_create($fec);
$hora_m=$sat_r['hora_m'];
$medlegal_m=$sat_r['medlegal_m'];
$codigomater_m=$sat_r['codigomater_m'];
$asc_m=$sat_r['asc_m'];
$imc_m=$sat_r['imc_m'];
$resval_m=$sat_r['resval_m'];
$labdecontrol_m=$sat_r['labdecontrol_m'];

//dispositivos

$calv_m=$sat_r['calv_m']; 
$sitv_m=$sat_r['sitv_m']; 
$nomv_m =$sat_r['nomv_m']; 
$datv_m=$sat_r['datv_m'];

$calp_m=$sat_r['calp_m'];  
$sitp_m=$sat_r['sitp_m'];  
$nomp_m=$sat_r['nomp_m']; 
$datp_m=$sat_r['datp_m']; 

$calp2_m=$sat_r['calp2_m'];
$sitp2_m=$sat_r['sitp2_m']; 
$nomp2_m=$sat_r['nomp2_m']; 
$datp2_m=$sat_r['datp2_m'];

$cale_m=$sat_r['cale_m'];  
$site_m=$sat_r['site_m']; 
$nome_m=$sat_r['nome_m']; 
$date_m=$sat_r['date_m']; 

$cals_m=$sat_r['cals_m']; 
$sits_m=$sat_r['sits_m']; 
$noms_m=$sat_r['noms_m'];
$dats_m=$sat_r['dats_m'];  

$caln_m=$sat_r['caln_m']; 
$sitn_m=$sat_r['sitn_m']; 
$nomn_m=$sat_r['nomn_m']; 
$datn_m=$sat_r['datn_m'];

//chechbox VALORACION PIEL VALORACION PIEL
$quem_m=$sat_r['quem_m'];
$uls_m=$sat_r['uls_m'];
$nec_m=$sat_r['nec_m'];
$her_m=$sat_r['her_m'];
$tub_m=$sat_r['tub_m'];
$der_m=$sat_r['der_m'];
$ras_m=$sat_r['ras_m'];
$eq_m=$sat_r['eq_m'];
$enf_m=$sat_r['enf_m'];
$ema_m=$sat_r['ema_m'];
$frac_m=$sat_r['frac_m'];
$acc_m=$sat_r['acc_m'];
$pete_m=$sat_r['pete_m'];
$ede_m=$sat_r['ede_m'];

$fron_m=$sat_r['fron_m'];
$mal_m=$sat_r['mal_m'];
$man_m=$sat_r['man_m'];
$del_m=$sat_r['del_m'];
$pec_m=$sat_r['pec_m'];
$est_m=$sat_r['est_m'];
$ant_m=$sat_r['ant_m'];
$mu_m=$sat_r['mu_m'];
$mano_m=$sat_r['mano_m'];
$mus_m=$sat_r['mus_m'];
$rod_m=$sat_r['rod_m'];
$pier_m=$sat_r['pier_m'];
$pri_m=$sat_r['pri_m'];
$max_m=$sat_r['max_m'];
$men_m=$sat_r['men_m'];
$ac_m=$sat_r['ac_m'];
$bra_m=$sat_r['bra_m'];
$pli_m=$sat_r['pli_m'];
$abd_m=$sat_r['abd_m'];
$reg_m=$sat_r['reg_m'];
$pub_m=$sat_r['pub_m'];
$pde_m=$sat_r['pde_m'];
$sde_m=$sat_r['sde_m'];
$tde_m=$sat_r['tde_m'];
$cde_m=$sat_r['cde_m'];
$qde_m=$sat_r['qde_m'];
$tob_m=$sat_r['tob_m'];
$pie_m=$sat_r['pie_m'];
$par_m=$sat_r['par_m'];
$occ_m=$sat_r['occ_m'];
$nuca_m=$sat_r['nuca_m'];
$braz_m=$sat_r['braz_m'];
$codo_m=$sat_r['codo_m'];
$ante_m=$sat_r['ante_m'];
$mune_m=$sat_r['mune_m'];
$mane_m=$sat_r['mane_m'];
$plieg_m=$sat_r['plieg_m'];
$piern_m=$sat_r['piern_m'];
$piep_m=$sat_r['piep_m'];
$cuello_m=$sat_r['cuello_m'];
$regin_m=$sat_r['regin_m'];
$regesc_m=$sat_r['regesc_m'];
$reginf_m=$sat_r['reginf_m'];
$lum_m=$sat_r['lum_m'];
$glut_m=$sat_r['glut_m'];
$musl_m=$sat_r['musl_m'];
$talon_m=$sat_r['talon_m'];

//val pupilar
$tamd_m=$sat_r['tamd_m'];
$tami_m=$sat_r['tami_m'];
$simd_m=$sat_r['simd_m'];
$simi_m=$sat_r['simi_m'];

//norton
$estfis_m=$sat_r['estfis_m'];
$estmen_m=$sat_r['estmen_m'];
$act_m=$sat_r['act_m'];
$mov_m=$sat_r['mov_m'];
$inc_m=$sat_r['inc_m'];
$tot_m=$sat_r['tot_m'];
$clasriesg_m=$sat_r['clasriesg_m'];
$nomenf_m=$sat_r['nomenf_m'];

//escala riesgo de caidas downtown
$caidas_m=$sat_r['caidas_m'];
$classresg_m=$sat_r['classresg_m'];
$medi_m=$sat_r['medi_m'];
$defic_m=$sat_r['defic_m'];
$estement_m=$sat_r['estement_m'];
$deamb_m=$sat_r['deamb_m'];
$total_m=$sat_r['total_m'];
$nom_enf_m=$sat_r['nom_enf_m'];
$interv_m=$sat_r['interv_m'];
$cuidenf=$sat_r['cuidenf'];

//Escala de Agitación Sedación RASS
$agit_m=$sat_r['agit_m'];


//MARCAJE
$mara=$sat_r['mara'];
$marb=$sat_r['marb'];
$marc=$sat_r['marc'];
$mard=$sat_r['mard'];
$mare=$sat_r['mare'];
$marf=$sat_r['marf'];
$marg=$sat_r['marg'];
$marh=$sat_r['marh'];

$frenteizquierda=$sat_r['frenteizquierda'];
$frentederecha=$sat_r['frentederecha'];
$narizc=$sat_r['narizc'];
$mejderecha=$sat_r['mejderecha'];
$mandiizqui=$sat_r['mandiizqui'];
$mandiderr=$sat_r['mandiderr'];
$mandicentroo=$sat_r['mandicentroo'];
$cvi=$sat_r['cvi'];
$homi=$sat_r['homi'];
$hombrod=$sat_r['hombrod'];
$pecti=$sat_r['pecti'];
$pectd=$sat_r['pectd'];
$peci=$sat_r['peci'];
$brazci=$sat_r['brazci'];
$cconder=$sat_r['cconder'];
$brazi=$sat_r['brazi'];
$annbraz=$sat_r['annbraz'];
$derbraz=$sat_r['derbraz'];
$muñei=$sat_r['muñei'];
$muñecad=$sat_r['muñecad'];
$palmai=$sat_r['palmai'];
$palmad=$sat_r['palmad'];
$ddi=$sat_r['ddi'];
$ddoderu=$sat_r['ddoderu'];
$ddidos=$sat_r['ddidos'];
$dedodos=$sat_r['dedodos'];
$dditres=$sat_r['dditres'];
$dedotres=$sat_r['dedotres'];
$dedocuatro=$sat_r['dedocuatro'];
$ddicuatro=$sat_r['ddicuatro'];
$ddicinco=$sat_r['ddicinco'];
$dedocincoo=$sat_r['dedocincoo'];
$iabdomen=$sat_r['iabdomen'];
$inglei=$sat_r['inglei'];
$musloi=$sat_r['musloi'];
$muslod=$sat_r['muslod'];
$rodd=$sat_r['rodd'];
$rodi=$sat_r['rodi'];
$tod=$sat_r['tod'];
$toi=$sat_r['toi'];
$pied=$sat_r['pied'];
$pie=$sat_r['pie'];
$plantapiea=$sat_r['plantapiea'];
$plantapieader=$sat_r['plantapieader'];
$tobilloatd=$sat_r['tobilloatd'];
$tobilloati=$sat_r['tobilloati'];
$ptiatras=$sat_r['ptiatras'];
$ptdatras=$sat_r['ptdatras'];
$pierespaldad=$sat_r['pierespaldad'];
$pierespaldai=$sat_r['pierespaldai'];
$musloatrasiz=$sat_r['musloatrasiz'];
$musloatrasder=$sat_r['musloatrasder'];
$dorsaliz=$sat_r['dorsaliz'];
$dorsalder=$sat_r['dorsalder'];
$munecaatrasiz=$sat_r['munecaatrasiz'];
$munecaatrasder=$sat_r['munecaatrasder'];
$antebdesp=$sat_r['antebdesp'];
$antebiesp=$sat_r['antebiesp'];
$casicodoi=$sat_r['casicodoi'];
$casicododer=$sat_r['casicododer'];
$brazaltder=$sat_r['brazaltder'];
$brazalti=$sat_r['brazalti'];
$glutiz=$sat_r['glutiz'];
$glutder=$sat_r['glutder'];
$cinturader=$sat_r['cinturader'];
$cinturaiz=$sat_r['cinturaiz'];
$costilliz=$sat_r['costilliz'];
$costillder=$sat_r['costillder'];
$espaldaarribader=$sat_r['espaldaarribader'];
$espaldarribaiz=$sat_r['espaldarribaiz'];
$espaldaalta=$sat_r['espaldaalta'];
$cuellatrasb=$sat_r['cuellatrasb'];
$cuellatrasmedio=$sat_r['cuellatrasmedio'];
$cabedorsalm=$sat_r['cabedorsalm'];
$cabealtaizqu=$sat_r['cabealtaizqu'];
$cabezaaltader=$sat_r['cabezaaltader'];

$espizq=$sat_r['espizq'];
$espder=$sat_r['espder'];
$coxis=$sat_r['coxis'];

$nuevo=$sat_r['nuevo'];
$nuevo1=$sat_r['nuevo1'];
$nuevo2=$sat_r['nuevo2'];
$nuevo3=$sat_r['nuevo3'];
$nuevo4=$sat_r['nuevo4'];
$nuevo5=$sat_r['nuevo5'];
$nuevo6=$sat_r['nuevo6'];
$nuevo7=$sat_r['nuevo7'];

}
$pdf->Cell(50,6, 'Fecha: '. date_format($fech, "d-m-Y"),1,0,'C');
$pdf->Cell(50,6, 'Hora: ' .$hora_m,1,0,'C');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(50,6, utf8_decode('Nivel RCP: '.$medlegal_m),1,0,'C');
$pdf->Cell(45,6, utf8_decode('Apache: '.$codigomater_m),1,0,'C');

$pdf->Ln(8);
$pdf->SetFont('Arial', 'B',9);
$pdf->Cell(195, 6, utf8_decode('ESCALA DE AGITACIÓN SEDACIÓN (RASS)'), 0, 0, 'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(65, 5, utf8_decode('Puntos'), 1, 0, 'C');
$pdf->Cell(65, 5, utf8_decode('Categoría'), 1, 0, 'C');
$pdf->Cell(65, 5, utf8_decode('Resultado'), 1, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(65, 5, utf8_decode('+4'), 1, 0, 'C');
$pdf->Cell(65, 5, utf8_decode('Combativo'), 1, 0, 'C');
$pdf->SetFont('Arial', '',10);
$pdf->Cell(65, 50, utf8_decode($agit_m), 1, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(65, 5, utf8_decode('+3'), 1, 0, 'C');
$pdf->Cell(65, 5, utf8_decode('Muy agitado'), 1, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(65, 5, utf8_decode('+2'), 1, 0, 'C');
$pdf->Cell(65, 5, utf8_decode('Agitado'), 1, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(65, 5, utf8_decode('+1'), 1, 0, 'C');
$pdf->Cell(65, 5, utf8_decode('Inquieto'), 1, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(65, 5, utf8_decode('0'), 1, 0, 'C');
$pdf->Cell(65, 5, utf8_decode('Alerta y tranquilo'), 1, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(65, 5, utf8_decode('-1'), 1, 0, 'C');
$pdf->Cell(65, 5, utf8_decode('Somnoliento'), 1, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(65, 5, utf8_decode('-2'), 1, 0, 'C');
$pdf->Cell(65, 5, utf8_decode('Sedación ligera'), 1, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(65, 5, utf8_decode('-3'), 1, 0, 'C');
$pdf->Cell(65, 5, utf8_decode('Sedación moderada'), 1, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(65, 5, utf8_decode('-4'), 1, 0, 'C');
$pdf->Cell(65, 5, utf8_decode('Sedación profunda'), 1, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(65, 5, utf8_decode('-5'), 1, 0, 'C');
$pdf->Cell(65, 5, utf8_decode('No estimulable'), 1, 0, 'C');
$pdf->Ln(6);

//MEDICAMENTOS MEDICAMENTOS MEDICAMENTOS MEDICAMENTOS MEDICAMENTOS MEDICAMENTOS


$pdf->Cell(105,5, utf8_decode('Medicamento'),1,0,'C');
$pdf->Cell(30,5, utf8_decode('Frecuencia'),1,0,'C');
$pdf->Cell(20,5, utf8_decode('Dosis'),1,0,'C');
$pdf->Cell(20,5, utf8_decode('Vía'),1,0,'C');
$pdf->Cell(20,5, utf8_decode('Hora'),1,0,'C');
$cis = $conexion->query("select * from medica_enf where fecha_mat='$fechar' and id_atencion=$id_atencion and material != 'SI'  ORDER BY id_med_reg") or die($conexion->error);
while ($cis_s = $cis->fetch_assoc()) {
  $id_med_reg=$cis_s['id_med_reg'];
  $pdf->SetFont('Arial', '', 8);
$pdf->Ln(5);
$pdf->Cell(105,5,utf8_decode($cis_s['medicam_mat']),1,0,'C');
$pdf->Cell(30,5, utf8_decode($cis_s['frec_mat']),1,0,'C');
$pdf->Cell(20,5, utf8_decode($cis_s['dosis_mat']),1,0,'C');
$pdf->Cell(20,5, utf8_decode($cis_s['via_mat']),1,0,'C');
$pdf->Cell(20,5, utf8_decode($cis_s['hora_mat']),1,0,'C');
}


if ($id_med_reg==null) {
 $pdf->Cell(93,5, utf8_decode(''),1,0,'C');
$pdf->Cell(20,5, utf8_decode(''),1,0,'C');
$pdf->Cell(20,5, utf8_decode(''),1,0,'C');
$pdf->Cell(35,5, utf8_decode(''),1,0,'C');
$pdf->Cell(27,5, utf8_decode(''),1,0,'C');
$pdf->Ln(5);
 $pdf->Cell(93,5, utf8_decode(''),1,0,'C');
$pdf->Cell(20,5, utf8_decode(''),1,0,'C');
$pdf->Cell(20,5, utf8_decode(''),1,0,'C');
$pdf->Cell(35,5, utf8_decode(''),1,0,'C');
$pdf->Cell(27,5, utf8_decode(''),1,0,'C');
$pdf->Ln(5);
 $pdf->Cell(93,5, utf8_decode(''),1,0,'C');
$pdf->Cell(20,5, utf8_decode(''),1,0,'C');
$pdf->Cell(20,5, utf8_decode(''),1,0,'C');
$pdf->Cell(35,5, utf8_decode(''),1,0,'C');
$pdf->Cell(27,5, utf8_decode(''),1,0,'C');
}


$pdf->Ln(10);




//VALORACION PIEL VALORACION PIEL VALORACION PIEL VALORACION PIEL VALORACION PIEL VALORACION PIEL VALORACION PIEL
$pdf->Ln(35);


$pdf->Ln(200);
  //inicio de 2da sección



$pdf->SetFont('Arial', 'B', 5);
$pdf->Ln(135);
$pdf->Cell(30,6, utf8_decode('MEDICIONES CLÍNICAS / HORA'),1,0,'C');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(7,6, utf8_decode('8'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('9'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('10'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('11'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('12'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('13'),1,0,'C');
//ves
$pdf->Cell(7,6, utf8_decode('14'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('15'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('16'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('17'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('18'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('19'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('20'),1,0,'C');
//noc
$pdf->Cell(7,6, utf8_decode('21'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('22'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('23'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('24'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('1'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('2'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('3'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('4'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('5'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('6'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('7'),1,0,'C');
$pdf->Ln(8);
$pdf->SetY(51);
$pdf->SetX(60);
$pdf->SetFont('Arial', 'B', 6);
$pdf->SetY(51);
$pdf->SetX(10);
$pdf->Cell(30,6, utf8_decode('Glasgow'),1,0,'L');
$pdf->Ln(6);
$pdf->Cell(30,6, utf8_decode('Neurológico'),1,0,'L');
$pdf->Ln(6);
$pdf->Cell(30,6, utf8_decode('Glicemia capilar'),1,0,'L');
$pdf->Ln(6);
$pdf->Cell(30,6, utf8_decode('Presión intracraneal'),1,0,'L');
$pdf->Ln(6);
$pdf->Cell(30,6, utf8_decode('Presión perfusión cerebral'),1,0,'L');
$pdf->Ln(6);
$pdf->Cell(30,6, utf8_decode('Presión intraabdominal'),1,0,'L');
$pdf->Ln(6);
$pdf->Cell(30,6, utf8_decode('Perímetro abdominal'),1,0,'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 5.8);
$pdf->Cell(30,6, utf8_decode('Presión perfusión abdominal'),1,0,'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(30,6, utf8_decode('Presion venosa central'),1,0,'L');
$pdf->Ln(6);
$pdf->Cell(30,6, utf8_decode('Insulina'),1,0,'L');
$pdf->Ln(6);
$pdf->Cell(30,6, utf8_decode('Dextrosa al 50 %'),1,0,'L');
$pdf->Ln(6);
$pdf->Cell(30,6, utf8_decode('Oxigenoterapia'),1,0,'L');
$pdf->Ln(6);
$pdf->Cell(30,6, utf8_decode('Micronebulizaciones'),1,0,'L');
$pdf->Ln(6);
$pdf->Cell(30,6, utf8_decode('Enema'),1,0,'L');
$pdf->Ln(8);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(30,6, utf8_decode('Signos vitales'),1,0,'C');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(7,6, utf8_decode('8'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('9'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('10'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('11'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('12'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('13'),1,0,'C');
//ves
$pdf->Cell(7,6, utf8_decode('14'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('15'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('16'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('17'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('18'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('19'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('20'),1,0,'C');
//noc
$pdf->Cell(7,6, utf8_decode('21'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('22'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('23'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('24'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('1'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('2'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('3'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('4'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('5'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('6'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('7'),1,0,'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(30,6, utf8_decode('Presión arterial'),1,0,'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(30,6, utf8_decode('Presión arterial media (TAM)'),1,0,'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(30,6, utf8_decode('Temperatura'),1,0,'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(30,6, utf8_decode('Frecuencia cardiaca'),1,0,'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(30,6, utf8_decode('Frecuencia respiratoria'),1,0,'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(30,6, utf8_decode('Saturación oxígeno'),1,0,'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(30,6, utf8_decode('Nivel de dolor'),1,0,'C');

$pdf->Ln(8);
$pdf->SetFont('Arial', 'B', 5.3);
$pdf->Cell(30,6, utf8_decode('PARÁMETROS VENTILATORIOS'),1,0,'C');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(7,6, utf8_decode('8'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('9'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('10'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('11'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('12'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('13'),1,0,'C');
//ves
$pdf->Cell(7,6, utf8_decode('14'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('15'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('16'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('17'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('18'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('19'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('20'),1,0,'C');
//noc
$pdf->Cell(7,6, utf8_decode('21'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('22'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('23'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('24'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('1'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('2'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('3'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('4'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('5'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('6'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('7'),1,0,'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(30,6, utf8_decode('Modo ventilatorio'),1,0,'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(30,6, utf8_decode('Volumen corriente'),1,0,'C');

$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(30,6, utf8_decode('Frecuencia respiratoria'),1,0,'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 5.7);
$pdf->Cell(30,6, utf8_decode('Fracción inspirada de oxígeno'),1,0,'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(30,6, utf8_decode('PEEP'),1,0,'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(30,6, utf8_decode('Presión inspiratoria'),1,0,'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(30,6, utf8_decode('Presión pico'),1,0,'C');

// GLASGOW / GLASGOW / GLASGOW / GLASGOW / GLASGOW / GLASGOW

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND  tipo = 'Glasgow' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glas=$resp_r['cantidad'];
}
if(isset($glas)){
$pdf->SetY(51);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($glas),1,0,'C');
}else{
  $pdf->SetY(51);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND  tipo = 'Glasgow' AND ter = 'Si'") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glas9=$resp_r9['cantidad'];
}
if(isset($glas9)){
$pdf->SetY(51);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($glas9),1,0,'C');
}else{
  $pdf->SetY(51);
$pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND  tipo = 'Glasgow' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glas10=$resp_r['cantidad'];
}
if(isset($glas10)){
$pdf->SetY(51);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($glas10),1,0,'C');
}else{
  $pdf->SetY(51);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND  tipo = 'Glasgow' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glas11=$resp_r['cantidad'];
}
if(isset($glas11)){
$pdf->SetY(51);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($glas11),1,0,'C');
}else{
  $pdf->SetY(51);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND  tipo = 'Glasgow' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glas12=$resp_r['cantidad'];
}
if(isset($glas12)){
$pdf->SetY(51);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($glas12),1,0,'C');
}else{
  $pdf->SetY(51);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND  tipo = 'Glasgow' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glas13=$resp_r['cantidad'];
}
if(isset($glas13)){
$pdf->SetY(51);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($glas13),1,0,'C');
}else{
  $pdf->SetY(51);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND  tipo = 'Glasgow' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glas14=$resp_r['cantidad'];
}
if(isset($glas14)){
$pdf->SetY(51);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($glas14),1,0,'C');
}else{
  $pdf->SetY(51);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND  tipo = 'Glasgow' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glas15=$resp_r['cantidad'];
}
if(isset($glas15)){
$pdf->SetY(51);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($glas15),1,0,'C');
}else{
  $pdf->SetY(51);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND  tipo = 'Glasgow' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glas16=$resp_r['cantidad'];
}
if(isset($glas16)){
$pdf->SetY(51);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($glas16),1,0,'C');
}else{
  $pdf->SetY(51);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND  tipo = 'Glasgow' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glas17=$resp_r['cantidad'];
}
if(isset($glas17)){
$pdf->SetY(51);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($glas17),1,0,'C');
}else{
  $pdf->SetY(51);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND  tipo = 'Glasgow' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glas18=$resp_r['cantidad'];
}
if(isset($glas18)){
$pdf->SetY(51);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($glas18),1,0,'C');
}else{
  $pdf->SetY(51);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND  tipo = 'Glasgow' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glas19=$resp_r['cantidad'];
}
if(isset($glas19)){
$pdf->SetY(51);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($glas19),1,0,'C');
}else{
  $pdf->SetY(51);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND  tipo = 'Glasgow' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glas20=$resp_r['cantidad'];
}
if(isset($glas20)){
$pdf->SetY(51);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($glas20),1,0,'C');
}else{
  $pdf->SetY(51);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//inicio NOC glas inicio noc inicio noc glas inicio noc glas noc glas noc glas noc glas noc glas noc glas noc glas

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND  tipo = 'Glasgow' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glas21=$resp_r['cantidad'];
}
if(isset($glas21)){
$pdf->SetY(51);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($glas21),1,0,'C');
}else{
  $pdf->SetY(51);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND  tipo = 'Glasgow' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glas22=$resp_r['cantidad'];
}
if(isset($glas22)){
$pdf->SetY(51);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($glas22),1,0,'C');
}else{
  $pdf->SetY(51);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND  tipo = 'Glasgow' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glas23=$resp_r['cantidad'];
}
if(isset($glas23)){
$pdf->SetY(51);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($glas23),1,0,'C');
}else{
  $pdf->SetY(51);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND  tipo = 'Glasgow' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glas24=$resp_r['cantidad'];
}
if(isset($glas24)){
$pdf->SetY(51);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($glas24),1,0,'C');
}else{
  $pdf->SetY(51);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND  tipo = 'Glasgow' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glas01=$resp_r['cantidad'];
}
if(isset($glas01)){
$pdf->SetY(51);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($glas01),1,0,'C');
}else{
  $pdf->SetY(51);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND  tipo = 'Glasgow' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glas02=$resp_r['cantidad'];
}
if(isset($glas02)){
$pdf->SetY(51);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($glas02),1,0,'C');
}else{
  $pdf->SetY(51);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND  tipo = 'Glasgow' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glas03=$resp_r['cantidad'];
}
if(isset($glas03)){
$pdf->SetY(51);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($glas03),1,0,'C');
}else{
  $pdf->SetY(51);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND  tipo = 'Glasgow' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glas04=$resp_r['cantidad'];
}
if(isset($glas04)){
$pdf->SetY(51);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($glas04),1,0,'C');
}else{
  $pdf->SetY(51);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND  tipo = 'Glasgow' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glas05=$resp_r['cantidad'];
}
if(isset($glas05)){
$pdf->SetY(51);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($glas05),1,0,'C');
}else{
  $pdf->SetY(51);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND  tipo = 'Glasgow' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glas06=$resp_r['cantidad'];
}
if(isset($glas06)){
$pdf->SetY(51);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($glas06),1,0,'C');
}else{
  $pdf->SetY(51);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND  tipo = 'Glasgow' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glas07=$resp_r['cantidad'];
}
if(isset($glas07)){
$pdf->SetY(51);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($glas07),1,0,'C');
}else{
  $pdf->SetY(51);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND  tipo = 'Neurologico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur=$resp_r['cantidad'];
}
if(isset($neur)){
$pdf->SetY(57);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($neur),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND  tipo = 'Neurologico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur9=$resp_r9['cantidad'];
}
if(isset($neur9)){
$pdf->SetY(57);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($neur9),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND  tipo = 'Neurologico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur10=$resp_r['cantidad'];
}
if(isset($neur10)){
$pdf->SetY(57);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($neur10),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND  tipo = 'Neurologico' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur11=$resp_r['cantidad'];
}
if(isset($neur11)){
$pdf->SetY(57);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($neur11),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND  tipo = 'Neurologico' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur12=$resp_r['cantidad'];
}
if(isset($neur12)){
$pdf->SetY(57);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($neur12),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND  tipo = 'Neurologico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur13=$resp_r['cantidad'];
}
if(isset($neur13)){
$pdf->SetY(57);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($neur13),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND  tipo = 'Neurologico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur14=$resp_r['cantidad'];
}
if(isset($neur14)){
$pdf->SetY(57);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($neur14),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND  tipo = 'Neurologico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur15=$resp_r['cantidad'];
}
if(isset($neur15)){
$pdf->SetY(57);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($neur15),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND  tipo = 'Neurologico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur16=$resp_r['cantidad'];
}
if(isset($neur16)){
$pdf->SetY(57);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($neur16),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND  tipo = 'Neurologico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur17=$resp_r['cantidad'];
}
if(isset($neur17)){
$pdf->SetY(57);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($neur17),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND  tipo = 'Neurologico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur18=$resp_r['cantidad'];
}
if(isset($neur18)){
$pdf->SetY(57);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($neur18),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND  tipo = 'Neurologico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur19=$resp_r['cantidad'];
}
if(isset($neur19)){
$pdf->SetY(57);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($neur19),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND  tipo = 'Neurologico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur20=$resp_r['cantidad'];
}
if(isset($neur20)){
$pdf->SetY(57);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($neur20),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//inicio NOC glas inicio noc inicio noc glas inicio noc glas noc glas noc glas noc glas noc glas noc glas noc glas

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND  tipo = 'Neurologico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur21=$resp_r['cantidad'];
}
if(isset($neur21)){
$pdf->SetY(57);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($neur21),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND  tipo = 'Neurologico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur22=$resp_r['cantidad'];
}
if(isset($neur22)){
$pdf->SetY(57);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($neur22),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND  tipo = 'Neurologico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur23=$resp_r['cantidad'];
}
if(isset($neur23)){
$pdf->SetY(57);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($neur23),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND  tipo = 'Neurologico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur24=$resp_r['cantidad'];
}
if(isset($neur24)){
$pdf->SetY(57);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($neur24),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND  tipo = 'Neurologico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur01=$resp_r['cantidad'];
}
if(isset($neur01)){
$pdf->SetY(57);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($neur01),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND  tipo = 'Neurologico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur02=$resp_r['cantidad'];
}
if(isset($neur02)){
$pdf->SetY(57);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($neur02),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND  tipo = 'Neurologico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur03=$resp_r['cantidad'];
}
if(isset($neur03)){
$pdf->SetY(57);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($neur03),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND  tipo = 'Neurologico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur04=$resp_r['cantidad'];
}
if(isset($neur04)){
$pdf->SetY(57);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($neur04),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND  tipo = 'Neurologico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur05=$resp_r['cantidad'];
}
if(isset($neur05)){
$pdf->SetY(57);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($neur05),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND  tipo = 'Neurologico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur06=$resp_r['cantidad'];
}
if(isset($neur06)){
$pdf->SetY(57);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($neur06),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND  tipo = 'Neurologico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur07=$resp_r['cantidad'];
}
if(isset($neur07)){
$pdf->SetY(57);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($neur07),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


// INICIO GLICEMIA CAPILAR INICIO GLICEMIA CAPILAR INICIO GLICEMIA CAPILAR INICIO GLICEMIA CAPILAR INICIO

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND  tipo = 'Glicemia capilar' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glic=$resp_r['cantidad'];
}
if(isset($glic)){
$pdf->SetY(63);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($glic),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND  tipo = 'Glicemia capilar' AND ter = 'Si'") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glic9=$resp_r9['cantidad'];
}
if(isset($glic9)){
$pdf->SetY(63);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($glic9),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND  tipo = 'Glicemia capilar' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glic10=$resp_r['cantidad'];
}
if(isset($glic10)){
$pdf->SetY(63);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($glic10),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND  tipo = 'Glicemia capilar' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glic11=$resp_r['cantidad'];
}
if(isset($glic11)){
$pdf->SetY(63);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($glic11),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND  tipo = 'Glicemia capilar' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glic12=$resp_r['cantidad'];
}
if(isset($glic12)){
$pdf->SetY(63);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($glic12),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND  tipo = 'Glicemia capilar' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glic13=$resp_r['cantidad'];
}
if(isset($glic13)){
$pdf->SetY(63);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($glic13),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND  tipo = 'Glicemia capilar' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glic14=$resp_r['cantidad'];
}
if(isset($glic14)){
$pdf->SetY(63);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($glic14),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND  tipo = 'Glicemia capilar' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glic15=$resp_r['cantidad'];
}
if(isset($glic15)){
$pdf->SetY(63);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($glic15),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND  tipo = 'Glicemia capilar' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glic16=$resp_r['cantidad'];
}
if(isset($glic16)){
$pdf->SetY(63);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($glic16),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND  tipo = 'Glicemia capilar' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glic17=$resp_r['cantidad'];
}
if(isset($glic17)){
$pdf->SetY(63);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($glic17),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND  tipo = 'Glicemia capilar' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glic18=$resp_r['cantidad'];
}
if(isset($glic18)){
$pdf->SetY(63);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($glic18),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND  tipo = 'Glicemia capilar' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glic19=$resp_r['cantidad'];
}
if(isset($glic19)){
$pdf->SetY(63);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($glic19),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND  tipo = 'Glicemia capilar' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glic20=$resp_r['cantidad'];
}
if(isset($glic20)){
$pdf->SetY(63);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($glic20),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//inicio NOC glas inicio noc inicio noc glas inicio noc glas noc glas noc glas noc glas noc glas noc glas noc glas

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND  tipo = 'Glicemia capilar' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glic21=$resp_r['cantidad'];
}
if(isset($glic21)){
$pdf->SetY(63);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($glic21),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND  tipo = 'Glicemia capilar' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glic22=$resp_r['cantidad'];
}
if(isset($glic22)){
$pdf->SetY(63);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($glic22),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND  tipo = 'Glicemia capilar' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glic23=$resp_r['cantidad'];
}
if(isset($glic23)){
$pdf->SetY(63);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($glic23),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND  tipo = 'Glicemia capilar' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glic24=$resp_r['cantidad'];
}
if(isset($glic24)){
$pdf->SetY(63);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($glic24),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND  tipo = 'Glicemia capilar' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glic01=$resp_r['cantidad'];
}
if(isset($glic01)){
$pdf->SetY(63);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($glic01),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND  tipo = 'Glicemia capilar' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glic02=$resp_r['cantidad'];
}
if(isset($glic02)){
$pdf->SetY(63);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($glic02),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND  tipo = 'Glicemia capilar' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glic03=$resp_r['cantidad'];
}
if(isset($glic03)){
$pdf->SetY(63);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($glic03),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND  tipo = 'Glicemia capilar' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glic04=$resp_r['cantidad'];
}
if(isset($glic04)){
$pdf->SetY(63);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($glic04),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND  tipo = 'Glicemia capilar' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glic05=$resp_r['cantidad'];
}
if(isset($glic05)){
$pdf->SetY(63);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($glic05),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND  tipo = 'Glicemia capilar' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glic06=$resp_r['cantidad'];
}
if(isset($glic06)){
$pdf->SetY(63);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($glic06),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND  tipo = 'Glicemia capilar' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glic07=$resp_r['cantidad'];
}
if(isset($glic07)){
$pdf->SetY(63);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($glic07),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

// PRESION INTRACRANEAL / PRESION INTRACRANEAL / PRESION INTRACRANEAL / PRESION INTRACRANEAL / PRESION INTRACRANEAL / PRESION INTRACRANEAL

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND  tipo = 'Presion intracraneal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pintabdo=$resp_r['cantidad'];
}
if(isset($pintabdo)){
$pdf->SetY(69);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($pintabdo),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND  tipo = 'Presion intracraneal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pintabdo9=$resp_r9['cantidad'];
}
if(isset($pintabdo9)){
$pdf->SetY(69);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($pintabdo9),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND  tipo = 'Presion intracraneal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pintabdo10=$resp_r['cantidad'];
}
if(isset($pintabdo10)){
$pdf->SetY(69);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($pintabdo10),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND  tipo = 'Presion intracraneal' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pintabdo11=$resp_r['cantidad'];
}
if(isset($pintabdo11)){
$pdf->SetY(69);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($pintabdo11),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND  tipo = 'Presion intracraneal' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pintabdo12=$resp_r['cantidad'];
}
if(isset($pintabdo12)){
$pdf->SetY(69);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($pintabdo12),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND  tipo = 'Presion intracraneal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pintabdo13=$resp_r['cantidad'];
}
if(isset($pintabdo13)){
$pdf->SetY(69);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($pintabdo13),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND  tipo = 'Presion intracraneal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pintabdo14=$resp_r['cantidad'];
}
if(isset($pintabdo14)){
$pdf->SetY(69);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($pintabdo14),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND  tipo = 'Presion intracraneal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pintabdo15=$resp_r['cantidad'];
}
if(isset($pintabdo15)){
$pdf->SetY(69);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($pintabdo15),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND  tipo = 'Presion intracraneal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pintabdo16=$resp_r['cantidad'];
}
if(isset($pintabdo16)){
$pdf->SetY(69);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($pintabdo16),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND  tipo = 'Presion intracraneal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pintabdo17=$resp_r['cantidad'];
}
if(isset($pintabdo17)){
$pdf->SetY(69);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($pintabdo17),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND  tipo = 'Presion intracraneal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pintabdo18=$resp_r['cantidad'];
}
if(isset($pintabdo18)){
$pdf->SetY(69);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($pintabdo18),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND  tipo = 'Presion intracraneal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pintabdo19=$resp_r['cantidad'];
}
if(isset($pintabdo19)){
$pdf->SetY(69);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($pintabdo19),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND  tipo = 'Presion intracraneal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pintabdo20=$resp_r['cantidad'];
}
if(isset($pintabdo20)){
$pdf->SetY(69);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($pintabdo20),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//inicio NOC glas inicio noc inicio noc glas inicio noc glas noc glas noc glas noc glas noc glas noc glas noc glas

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND  tipo = 'Presion intracraneal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pintabdo21=$resp_r['cantidad'];
}
if(isset($pintabdo21)){
$pdf->SetY(69);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($pintabdo21),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND  tipo = 'Presion intracraneal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pintabdo22=$resp_r['cantidad'];
}
if(isset($pintabdo22)){
$pdf->SetY(69);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($pintabdo22),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND  tipo = 'Presion intracraneal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pintabdo23=$resp_r['cantidad'];
}
if(isset($pintabdo23)){
$pdf->SetY(69);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($pintabdo23),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND  tipo = 'Presion intracraneal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pintabdo24=$resp_r['cantidad'];
}
if(isset($pintabdo24)){
$pdf->SetY(69);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($pintabdo24),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND  tipo = 'Presion intracraneal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pintabdo01=$resp_r['cantidad'];
}
if(isset($pintabdo01)){
$pdf->SetY(69);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($pintabdo01),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND  tipo = 'Presion intracraneal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pintabdo02=$resp_r['cantidad'];
}
if(isset($pintabdo02)){
$pdf->SetY(69);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($pintabdo02),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND  tipo = 'Presion intracraneal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pintabdo03=$resp_r['cantidad'];
}
if(isset($pintabdo03)){
$pdf->SetY(69);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($pintabdo03),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND  tipo = 'Presion intracraneal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pintabdo04=$resp_r['cantidad'];
}
if(isset($pintabdo04)){
$pdf->SetY(69);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($pintabdo04),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND  tipo = 'Presion intracraneal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pintabdo05=$resp_r['cantidad'];
}
if(isset($pintabdo05)){
$pdf->SetY(69);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($pintabdo05),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND  tipo = 'Presion intracraneal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pintabdo06=$resp_r['cantidad'];
}
if(isset($pintabdo06)){
$pdf->SetY(69);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($pintabdo06),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND  tipo = 'Presion intracraneal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pintabdo07=$resp_r['cantidad'];
}
if(isset($pintabdo07)){
$pdf->SetY(69);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($pintabdo07),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

// PRESION PERFUSION CFEREBRAL  PRESION PERFUSION CFEREBRAL  PRESION PERFUSION CFEREBRAL  PRESION PERFUSION CFEREBRAL

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion cerebral' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppercer=$resp_r['cantidad'];
}
if(isset($ppercer)){
$pdf->SetY(75);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($ppercer),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion cerebral' AND ter = 'Si'") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppercer9=$resp_r9['cantidad'];
}
if(isset($ppercer9)){
$pdf->SetY(75);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($ppercer9),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion cerebral' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppercer10=$resp_r['cantidad'];
}
if(isset($ppercer10)){
$pdf->SetY(75);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($ppercer10),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion cerebral' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppercer11=$resp_r['cantidad'];
}
if(isset($ppercer11)){
$pdf->SetY(75);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($ppercer11),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion cerebral' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppercer12=$resp_r['cantidad'];
}
if(isset($ppercer12)){
$pdf->SetY(75);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($ppercer12),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion cerebral' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppercer13=$resp_r['cantidad'];
}
if(isset($ppercer13)){
$pdf->SetY(75);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($ppercer13),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion cerebral' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppercer14=$resp_r['cantidad'];
}
if(isset($ppercer14)){
$pdf->SetY(75);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($ppercer14),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion cerebral' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppercer15=$resp_r['cantidad'];
}
if(isset($ppercer15)){
$pdf->SetY(75);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($ppercer15),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion cerebral' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppercer16=$resp_r['cantidad'];
}
if(isset($ppercer16)){
$pdf->SetY(75);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($ppercer16),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion cerebral' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppercer17=$resp_r['cantidad'];
}
if(isset($ppercer17)){
$pdf->SetY(75);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($ppercer17),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion cerebral' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppercer18=$resp_r['cantidad'];
}
if(isset($ppercer18)){
$pdf->SetY(75);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($ppercer18),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion cerebral' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppercer19=$resp_r['cantidad'];
}
if(isset($ppercer19)){
$pdf->SetY(75);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($ppercer19),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion cerebral' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppercer20=$resp_r['cantidad'];
}
if(isset($ppercer20)){
$pdf->SetY(75);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($ppercer20),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//inicio NOC glas inicio noc inicio noc glas inicio noc glas noc glas noc glas noc glas noc glas noc glas noc glas

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion cerebral' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppercer21=$resp_r['cantidad'];
}
if(isset($ppercer21)){
$pdf->SetY(75);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($ppercer21),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion cerebral' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppercer22=$resp_r['cantidad'];
}
if(isset($ppercer22)){
$pdf->SetY(75);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($ppercer22),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion cerebral' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppercer23=$resp_r['cantidad'];
}
if(isset($ppercer23)){
$pdf->SetY(75);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($ppercer23),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion cerebral' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppercer24=$resp_r['cantidad'];
}
if(isset($ppercer24)){
$pdf->SetY(75);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($ppercer24),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion cerebral' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppercer01=$resp_r['cantidad'];
}
if(isset($ppercer01)){
$pdf->SetY(75);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($ppercer01),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion cerebral' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppercer02=$resp_r['cantidad'];
}
if(isset($ppercer02)){
$pdf->SetY(75);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($ppercer02),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion cerebral' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppercer03=$resp_r['cantidad'];
}
if(isset($ppercer03)){
$pdf->SetY(75);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($ppercer03),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion cerebral' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppercer04=$resp_r['cantidad'];
}
if(isset($ppercer04)){
$pdf->SetY(75);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($ppercer04),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion cerebral' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppercer05=$resp_r['cantidad'];
}
if(isset($ppercer05)){
$pdf->SetY(75);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($ppercer05),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion cerebral' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppercer06=$resp_r['cantidad'];
}
if(isset($ppercer06)){
$pdf->SetY(75);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($ppercer06),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion cerebral' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppercer07=$resp_r['cantidad'];
}
if(isset($ppercer07)){
$pdf->SetY(75);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($ppercer07),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


// INICIO Presion intraabdominal INICIO Presion intraabdominal INICIO Presion intraabdominal INICIO Presion intraabdominal INICIO

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND  tipo = 'Presion intraabdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intabdo=$resp_r['cantidad'];
}
if(isset($intabdo)){
$pdf->SetY(81);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($intabdo),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND  tipo = 'Presion intraabdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intabdo9=$resp_r9['cantidad'];
}
if(isset($intabdo9)){
$pdf->SetY(81);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($intabdo9),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND  tipo = 'Presion intraabdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intabdo10=$resp_r['cantidad'];
}
if(isset($intabdo10)){
$pdf->SetY(81);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($intabdo10),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND  tipo = 'Presion intraabdominal' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intabdo11=$resp_r['cantidad'];
}
if(isset($intabdo11)){
$pdf->SetY(81);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($intabdo11),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND  tipo = 'Presion intraabdominal' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intabdo12=$resp_r['cantidad'];
}
if(isset($intabdo12)){
$pdf->SetY(81);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($intabdo12),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND  tipo = 'Presion intraabdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intabdo13=$resp_r['cantidad'];
}
if(isset($intabdo13)){
$pdf->SetY(81);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($intabdo13),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND  tipo = 'Presion intraabdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intabdo14=$resp_r['cantidad'];
}
if(isset($intabdo14)){
$pdf->SetY(81);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($intabdo14),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND  tipo = 'Presion intraabdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intabdo15=$resp_r['cantidad'];
}
if(isset($intabdo15)){
$pdf->SetY(81);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($intabdo15),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND  tipo = 'Presion intraabdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intabdo16=$resp_r['cantidad'];
}
if(isset($intabdo16)){
$pdf->SetY(81);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($intabdo16),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND  tipo = 'Presion intraabdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intabdo17=$resp_r['cantidad'];
}
if(isset($intabdo17)){
$pdf->SetY(81);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($intabdo17),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND  tipo = 'Presion intraabdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intabdo18=$resp_r['cantidad'];
}
if(isset($intabdo18)){
$pdf->SetY(81);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($intabdo18),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND  tipo = 'Presion intraabdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intabdo19=$resp_r['cantidad'];
}
if(isset($intabdo19)){
$pdf->SetY(81);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($intabdo19),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND  tipo = 'Presion intraabdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intabdo20=$resp_r['cantidad'];
}
if(isset($intabdo20)){
$pdf->SetY(81);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($intabdo20),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//inicio NOC glas inicio noc inicio noc glas inicio noc glas noc glas noc glas noc glas noc glas noc glas noc glas

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND  tipo = 'Presion intraabdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intabdo21=$resp_r['cantidad'];
}
if(isset($intabdo21)){
$pdf->SetY(81);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($intabdo21),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND  tipo = 'Presion intraabdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intabdo22=$resp_r['cantidad'];
}
if(isset($intabdo22)){
$pdf->SetY(81);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($intabdo22),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND  tipo = 'Presion intraabdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intabdo23=$resp_r['cantidad'];
}
if(isset($intabdo23)){
$pdf->SetY(81);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($intabdo23),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND  tipo = 'Presion intraabdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intabdo24=$resp_r['cantidad'];
}
if(isset($intabdo24)){
$pdf->SetY(81);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($intabdo24),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND  tipo = 'Presion intraabdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intabdo01=$resp_r['cantidad'];
}
if(isset($intabdo01)){
$pdf->SetY(81);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($intabdo01),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND  tipo = 'Presion intraabdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intabdo02=$resp_r['cantidad'];
}
if(isset($intabdo02)){
$pdf->SetY(81);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($intabdo02),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND  tipo = 'Presion intraabdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intabdo03=$resp_r['cantidad'];
}
if(isset($intabdo03)){
$pdf->SetY(81);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($intabdo03),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND  tipo = 'Presion intraabdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intabdo04=$resp_r['cantidad'];
}
if(isset($intabdo04)){
$pdf->SetY(81);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($intabdo04),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND  tipo = 'Presion intraabdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intabdo05=$resp_r['cantidad'];
}
if(isset($intabdo05)){
$pdf->SetY(81);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($intabdo05),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND  tipo = 'Presion intraabdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intabdo06=$resp_r['cantidad'];
}
if(isset($intabdo06)){
$pdf->SetY(81);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($intabdo06),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND  tipo = 'Presion intraabdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intabdo07=$resp_r['cantidad'];
}
if(isset($intabdo07)){
$pdf->SetY(81);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($intabdo07),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

// Perimetro abdominal / Perimetro abdominal / Perimetro abdominal / Perimetro abdominal / Perimetro abdominal / Perimetro abdominal

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND  tipo = 'Perimetro abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $periabdo=$resp_r['cantidad'];
}
if(isset($periabdo)){
$pdf->SetY(87);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($periabdo),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND  tipo = 'Perimetro abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $periabdo9=$resp_r9['cantidad'];
}
if(isset($periabdo9)){
$pdf->SetY(87);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($periabdo9),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND  tipo = 'Perimetro abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $periabdo10=$resp_r['cantidad'];
}
if(isset($periabdo10)){
$pdf->SetY(87);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($periabdo10),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND  tipo = 'Perimetro abdominal' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $periabdo11=$resp_r['cantidad'];
}
if(isset($periabdo11)){
$pdf->SetY(87);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($periabdo11),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND  tipo = 'Perimetro abdominal' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $periabdo12=$resp_r['cantidad'];
}
if(isset($periabdo12)){
$pdf->SetY(87);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($periabdo12),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND  tipo = 'Perimetro abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $periabdo13=$resp_r['cantidad'];
}
if(isset($periabdo13)){
$pdf->SetY(87);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($periabdo13),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND  tipo = 'Perimetro abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $periabdo14=$resp_r['cantidad'];
}
if(isset($periabdo14)){
$pdf->SetY(87);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($periabdo14),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND  tipo = 'Perimetro abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $periabdo15=$resp_r['cantidad'];
}
if(isset($periabdo15)){
$pdf->SetY(87);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($periabdo15),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND  tipo = 'Perimetro abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $periabdo16=$resp_r['cantidad'];
}
if(isset($periabdo16)){
$pdf->SetY(87);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($periabdo16),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND  tipo = 'Perimetro abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $periabdo17=$resp_r['cantidad'];
}
if(isset($periabdo17)){
$pdf->SetY(87);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($periabdo17),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND  tipo = 'Perimetro abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $periabdo18=$resp_r['cantidad'];
}
if(isset($periabdo18)){
$pdf->SetY(87);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($periabdo18),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND  tipo = 'Perimetro abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $periabdo19=$resp_r['cantidad'];
}
if(isset($periabdo19)){
$pdf->SetY(87);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($periabdo19),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND  tipo = 'Perimetro abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $periabdo20=$resp_r['cantidad'];
}
if(isset($periabdo20)){
$pdf->SetY(87);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($periabdo20),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//inicio NOC glas inicio noc inicio noc glas inicio noc glas noc glas noc glas noc glas noc glas noc glas noc glas

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND  tipo = 'Perimetro abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $periabdo21=$resp_r['cantidad'];
}
if(isset($periabdo21)){
$pdf->SetY(87);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($periabdo21),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND  tipo = 'Perimetro abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $periabdo22=$resp_r['cantidad'];
}
if(isset($periabdo22)){
$pdf->SetY(87);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($periabdo22),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND  tipo = 'Perimetro abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $periabdo23=$resp_r['cantidad'];
}
if(isset($periabdo23)){
$pdf->SetY(87);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($periabdo23),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND  tipo = 'Perimetro abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $periabdo24=$resp_r['cantidad'];
}
if(isset($periabdo24)){
$pdf->SetY(87);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($periabdo24),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND  tipo = 'Perimetro abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $periabdo01=$resp_r['cantidad'];
}
if(isset($periabdo01)){
$pdf->SetY(87);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($periabdo01),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND  tipo = 'Perimetro abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $periabdo02=$resp_r['cantidad'];
}
if(isset($periabdo02)){
$pdf->SetY(87);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($periabdo02),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND  tipo = 'Perimetro abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $periabdo03=$resp_r['cantidad'];
}
if(isset($periabdo03)){
$pdf->SetY(87);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($periabdo03),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND  tipo = 'Perimetro abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $periabdo04=$resp_r['cantidad'];
}
if(isset($periabdo04)){
$pdf->SetY(87);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($periabdo04),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND  tipo = 'Perimetro abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $periabdo05=$resp_r['cantidad'];
}
if(isset($periabdo05)){
$pdf->SetY(87);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($periabdo05),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND  tipo = 'Perimetro abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $periabdo06=$resp_r['cantidad'];
}
if(isset($periabdo06)){
$pdf->SetY(87);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($periabdo06),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND  tipo = 'Perimetro abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $periabdo07=$resp_r['cantidad'];
}
if(isset($periabdo07)){
$pdf->SetY(87);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($periabdo07),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

// Presion perfusion abdominal / Presion perfusion abdominal / Presion perfusion abdominal / Presion perfusion abdominal / Presion perfusion abdominal / Presion perfusion abdominal

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppa=$resp_r['cantidad'];
}
if(isset($ppa)){
$pdf->SetY(93);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($ppa),1,0,'C');
}else{
  $pdf->SetY(93);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppa9=$resp_r9['cantidad'];
}
if(isset($ppa9)){
$pdf->SetY(93);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($ppa9),1,0,'C');
}else{
  $pdf->SetY(93);
$pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppa10=$resp_r['cantidad'];
}
if(isset($ppa10)){
$pdf->SetY(93);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($ppa10),1,0,'C');
}else{
  $pdf->SetY(93);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion abdominal' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppa11=$resp_r['cantidad'];
}
if(isset($ppa11)){
$pdf->SetY(93);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($ppa11),1,0,'C');
}else{
  $pdf->SetY(93);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion abdominal' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppa12=$resp_r['cantidad'];
}
if(isset($ppa12)){
$pdf->SetY(93);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($ppa12),1,0,'C');
}else{
  $pdf->SetY(93);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppa13=$resp_r['cantidad'];
}
if(isset($ppa13)){
$pdf->SetY(93);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($ppa13),1,0,'C');
}else{
  $pdf->SetY(93);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppa14=$resp_r['cantidad'];
}
if(isset($ppa14)){
$pdf->SetY(93);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($ppa14),1,0,'C');
}else{
  $pdf->SetY(93);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppa15=$resp_r['cantidad'];
}
if(isset($ppa15)){
$pdf->SetY(93);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($ppa15),1,0,'C');
}else{
  $pdf->SetY(93);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppa16=$resp_r['cantidad'];
}
if(isset($ppa16)){
$pdf->SetY(93);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($ppa16),1,0,'C');
}else{
  $pdf->SetY(93);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppa17=$resp_r['cantidad'];
}
if(isset($ppa17)){
$pdf->SetY(93);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($ppa17),1,0,'C');
}else{
  $pdf->SetY(93);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppa18=$resp_r['cantidad'];
}
if(isset($ppa18)){
$pdf->SetY(93);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($ppa18),1,0,'C');
}else{
  $pdf->SetY(93);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppa19=$resp_r['cantidad'];
}
if(isset($ppa19)){
$pdf->SetY(93);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($ppa19),1,0,'C');
}else{
  $pdf->SetY(93);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppa20=$resp_r['cantidad'];
}
if(isset($ppa20)){
$pdf->SetY(93);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($ppa20),1,0,'C');
}else{
  $pdf->SetY(93);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//inicio NOC glas inicio noc inicio noc glas inicio noc glas noc glas noc glas noc glas noc glas noc glas noc glas

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppa21=$resp_r['cantidad'];
}
if(isset($ppa21)){
$pdf->SetY(93);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($ppa21),1,0,'C');
}else{
  $pdf->SetY(93);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppa22=$resp_r['cantidad'];
}
if(isset($ppa22)){
$pdf->SetY(93);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($ppa22),1,0,'C');
}else{
  $pdf->SetY(93);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppa23=$resp_r['cantidad'];
}
if(isset($ppa23)){
$pdf->SetY(93);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($ppa23),1,0,'C');
}else{
  $pdf->SetY(93);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppa24=$resp_r['cantidad'];
}
if(isset($ppa24)){
$pdf->SetY(93);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($ppa24),1,0,'C');
}else{
  $pdf->SetY(93);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppa01=$resp_r['cantidad'];
}
if(isset($ppa01)){
$pdf->SetY(93);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($ppa01),1,0,'C');
}else{
  $pdf->SetY(93);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppa02=$resp_r['cantidad'];
}
if(isset($ppa02)){
$pdf->SetY(93);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($ppa02),1,0,'C');
}else{
  $pdf->SetY(93);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppa03=$resp_r['cantidad'];
}
if(isset($ppa03)){
$pdf->SetY(93);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($ppa03),1,0,'C');
}else{
  $pdf->SetY(93);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppa04=$resp_r['cantidad'];
}
if(isset($ppa04)){
$pdf->SetY(93);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($ppa04),1,0,'C');
}else{
  $pdf->SetY(93);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppa05=$resp_r['cantidad'];
}
if(isset($ppa05)){
$pdf->SetY(93);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($ppa05),1,0,'C');
}else{
  $pdf->SetY(93);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppa06=$resp_r['cantidad'];
}
if(isset($ppa06)){
$pdf->SetY(93);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($ppa06),1,0,'C');
}else{
  $pdf->SetY(93);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND  tipo = 'Presion perfusion abdominal' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppa07=$resp_r['cantidad'];
}
if(isset($ppa07)){
$pdf->SetY(93);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($ppa07),1,0,'C');
}else{
  $pdf->SetY(93);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

// Presion venosa central / Presion venosa central / Presion venosa central / Presion venosa central / Presion venosa central / Presion venosa central

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND  tipo = 'Presion venosa central' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvc=$resp_r['cantidad'];
}
if(isset($pvc)){
$pdf->SetY(99);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($pvc),1,0,'C');
}else{
  $pdf->SetY(99);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND  tipo = 'Presion venosa central' AND ter = 'Si'") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvc9=$resp_r9['cantidad'];
}
if(isset($pvc9)){
$pdf->SetY(99);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($pvc9),1,0,'C');
}else{
  $pdf->SetY(99);
$pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND  tipo = 'Presion venosa central' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvc10=$resp_r['cantidad'];
}
if(isset($pvc10)){
$pdf->SetY(99);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($pvc10),1,0,'C');
}else{
  $pdf->SetY(99);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND  tipo = 'Presion venosa central' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvc11=$resp_r['cantidad'];
}
if(isset($pvc11)){
$pdf->SetY(99);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($pvc11),1,0,'C');
}else{
  $pdf->SetY(99);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND  tipo = 'Presion venosa central' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvc12=$resp_r['cantidad'];
}
if(isset($pvc12)){
$pdf->SetY(99);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($pvc12),1,0,'C');
}else{
  $pdf->SetY(99);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND  tipo = 'Presion venosa central' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvc13=$resp_r['cantidad'];
}
if(isset($pvc13)){
$pdf->SetY(99);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($pvc13),1,0,'C');
}else{
  $pdf->SetY(99);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND  tipo = 'Presion venosa central' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvc14=$resp_r['cantidad'];
}
if(isset($pvc14)){
$pdf->SetY(99);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($pvc14),1,0,'C');
}else{
  $pdf->SetY(99);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND  tipo = 'Presion venosa central' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvc15=$resp_r['cantidad'];
}
if(isset($pvc15)){
$pdf->SetY(99);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($pvc15),1,0,'C');
}else{
  $pdf->SetY(99);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND  tipo = 'Presion venosa central' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvc16=$resp_r['cantidad'];
}
if(isset($pvc16)){
$pdf->SetY(99);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($pvc16),1,0,'C');
}else{
  $pdf->SetY(99);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND  tipo = 'Presion venosa central' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvc17=$resp_r['cantidad'];
}
if(isset($pvc17)){
$pdf->SetY(99);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($pvc17),1,0,'C');
}else{
  $pdf->SetY(99);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND  tipo = 'Presion venosa central' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvc18=$resp_r['cantidad'];
}
if(isset($pvc18)){
$pdf->SetY(99);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($pvc18),1,0,'C');
}else{
  $pdf->SetY(99);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND  tipo = 'Presion venosa central' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvc19=$resp_r['cantidad'];
}
if(isset($pvc19)){
$pdf->SetY(99);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($pvc19),1,0,'C');
}else{
  $pdf->SetY(99);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND  tipo = 'Presion venosa central' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvc20=$resp_r['cantidad'];
}
if(isset($pvc20)){
$pdf->SetY(99);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($pvc20),1,0,'C');
}else{
  $pdf->SetY(99);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//inicio NOC glas inicio noc inicio noc glas inicio noc glas noc glas noc glas noc glas noc glas noc glas noc glas

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND  tipo = 'Presion venosa central' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvc21=$resp_r['cantidad'];
}
if(isset($pvc21)){
$pdf->SetY(99);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($pvc21),1,0,'C');
}else{
  $pdf->SetY(99);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND  tipo = 'Presion venosa central' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvc22=$resp_r['cantidad'];
}
if(isset($pvc22)){
$pdf->SetY(99);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($pvc22),1,0,'C');
}else{
  $pdf->SetY(99);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND  tipo = 'Presion venosa central' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvc23=$resp_r['cantidad'];
}
if(isset($pvc23)){
$pdf->SetY(99);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($pvc23),1,0,'C');
}else{
  $pdf->SetY(99);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND  tipo = 'Presion venosa central' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvc24=$resp_r['cantidad'];
}
if(isset($pvc24)){
$pdf->SetY(99);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($pvc24),1,0,'C');
}else{
  $pdf->SetY(99);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND  tipo = 'Presion venosa central' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvc01=$resp_r['cantidad'];
}
if(isset($pvc01)){
$pdf->SetY(99);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($pvc01),1,0,'C');
}else{
  $pdf->SetY(99);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND  tipo = 'Presion venosa central' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvc02=$resp_r['cantidad'];
}
if(isset($pvc02)){
$pdf->SetY(99);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($pvc02),1,0,'C');
}else{
  $pdf->SetY(99);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND  tipo = 'Presion venosa central' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvc03=$resp_r['cantidad'];
}
if(isset($pvc03)){
$pdf->SetY(99);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($pvc03),1,0,'C');
}else{
  $pdf->SetY(99);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND  tipo = 'Presion venosa central' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvc04=$resp_r['cantidad'];
}
if(isset($pvc04)){
$pdf->SetY(99);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($pvc04),1,0,'C');
}else{
  $pdf->SetY(99);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND  tipo = 'Presion venosa central' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvc05=$resp_r['cantidad'];
}
if(isset($pvc05)){
$pdf->SetY(99);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($pvc05),1,0,'C');
}else{
  $pdf->SetY(99);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND  tipo = 'Presion venosa central' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvc06=$resp_r['cantidad'];
}
if(isset($pvc06)){
$pdf->SetY(99);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($pvc06),1,0,'C');
}else{
  $pdf->SetY(99);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND  tipo = 'Presion venosa central' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvc07=$resp_r['cantidad'];
}
if(isset($pvc07)){
$pdf->SetY(99);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($pvc07),1,0,'C');
}else{
  $pdf->SetY(99);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

// Insulina / Insulina / Insulina / Insulina / Insulina / Insulina

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND  tipo = 'Insulina' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insulin=$resp_r['cantidad'];
}
if(isset($insulin)){
$pdf->SetY(105);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($insulin),1,0,'C');
}else{
  $pdf->SetY(105);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND  tipo = 'Insulina' AND ter = 'Si'") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insulin9=$resp_r9['cantidad'];
}
if(isset($insulin9)){
$pdf->SetY(105);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($insulin9),1,0,'C');
}else{
  $pdf->SetY(105);
$pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND  tipo = 'Insulina' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insulin10=$resp_r['cantidad'];
}
if(isset($insulin10)){
$pdf->SetY(105);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($insulin10),1,0,'C');
}else{
  $pdf->SetY(105);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND  tipo = 'Insulina' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insulin11=$resp_r['cantidad'];
}
if(isset($insulin11)){
$pdf->SetY(105);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($insulin11),1,0,'C');
}else{
  $pdf->SetY(105);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND  tipo = 'Insulina' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insulin12=$resp_r['cantidad'];
}
if(isset($insulin12)){
$pdf->SetY(105);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($insulin12),1,0,'C');
}else{
  $pdf->SetY(105);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND  tipo = 'Insulina' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insulin13=$resp_r['cantidad'];
}
if(isset($insulin13)){
$pdf->SetY(105);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($insulin13),1,0,'C');
}else{
  $pdf->SetY(105);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND  tipo = 'Insulina' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insulin14=$resp_r['cantidad'];
}
if(isset($insulin14)){
$pdf->SetY(105);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($insulin14),1,0,'C');
}else{
  $pdf->SetY(105);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND  tipo = 'Insulina' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insulin15=$resp_r['cantidad'];
}
if(isset($insulin15)){
$pdf->SetY(105);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($insulin15),1,0,'C');
}else{
  $pdf->SetY(105);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND  tipo = 'Insulina' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insulin16=$resp_r['cantidad'];
}
if(isset($insulin16)){
$pdf->SetY(105);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($insulin16),1,0,'C');
}else{
  $pdf->SetY(105);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND  tipo = 'Insulina' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insulin17=$resp_r['cantidad'];
}
if(isset($insulin17)){
$pdf->SetY(105);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($insulin17),1,0,'C');
}else{
  $pdf->SetY(105);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND  tipo = 'Insulina' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insulin18=$resp_r['cantidad'];
}
if(isset($insulin18)){
$pdf->SetY(105);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($insulin18),1,0,'C');
}else{
  $pdf->SetY(105);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND  tipo = 'Insulina' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insulin19=$resp_r['cantidad'];
}
if(isset($insulin19)){
$pdf->SetY(105);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($insulin19),1,0,'C');
}else{
  $pdf->SetY(105);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND  tipo = 'Insulina' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insulin20=$resp_r['cantidad'];
}
if(isset($insulin20)){
$pdf->SetY(105);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($insulin20),1,0,'C');
}else{
  $pdf->SetY(105);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//inicio NOC glas inicio noc inicio noc glas inicio noc glas noc glas noc glas noc glas noc glas noc glas noc glas

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND  tipo = 'Insulina' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insulin21=$resp_r['cantidad'];
}
if(isset($insulin21)){
$pdf->SetY(105);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($insulin21),1,0,'C');
}else{
  $pdf->SetY(105);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND  tipo = 'Insulina' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insulin22=$resp_r['cantidad'];
}
if(isset($insulin22)){
$pdf->SetY(105);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($insulin22),1,0,'C');
}else{
  $pdf->SetY(105);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND  tipo = 'Insulina' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insulin23=$resp_r['cantidad'];
}
if(isset($insulin23)){
$pdf->SetY(105);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($insulin23),1,0,'C');
}else{
  $pdf->SetY(105);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND  tipo = 'Insulina' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insulin24=$resp_r['cantidad'];
}
if(isset($insulin24)){
$pdf->SetY(105);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($insulin24),1,0,'C');
}else{
  $pdf->SetY(105);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND  tipo = 'Insulina' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insulin01=$resp_r['cantidad'];
}
if(isset($insulin01)){
$pdf->SetY(105);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($insulin01),1,0,'C');
}else{
  $pdf->SetY(105);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND  tipo = 'Insulina' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insulin02=$resp_r['cantidad'];
}
if(isset($insulin02)){
$pdf->SetY(105);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($insulin02),1,0,'C');
}else{
  $pdf->SetY(105);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND  tipo = 'Insulina' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insulin03=$resp_r['cantidad'];
}
if(isset($insulin03)){
$pdf->SetY(105);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($insulin03),1,0,'C');
}else{
  $pdf->SetY(105);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND  tipo = 'Insulina' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insulin04=$resp_r['cantidad'];
}
if(isset($insulin04)){
$pdf->SetY(105);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($insulin04),1,0,'C');
}else{
  $pdf->SetY(105);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND  tipo = 'Insulina' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insulin05=$resp_r['cantidad'];
}
if(isset($insulin05)){
$pdf->SetY(105);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($insulin05),1,0,'C');
}else{
  $pdf->SetY(105);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND  tipo = 'Insulina' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insulin06=$resp_r['cantidad'];
}
if(isset($insulin06)){
$pdf->SetY(105);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($insulin06),1,0,'C');
}else{
  $pdf->SetY(105);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND  tipo = 'Insulina' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insulin07=$resp_r['cantidad'];
}
if(isset($insulin07)){
$pdf->SetY(105);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($insulin07),1,0,'C');
}else{
  $pdf->SetY(105);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


// Dextrosa / Dextrosa / Dextrosa / Dextrosa / Dextrosa / Dextrosa

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND  tipo = 'Dextrosa' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dex=$resp_r['cantidad'];
}
if(isset($dex)){
$pdf->SetY(111);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($dex),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND  tipo = 'Dextrosa' AND ter = 'Si'") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dex9=$resp_r9['cantidad'];
}
if(isset($dex9)){
$pdf->SetY(111);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($dex9),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND  tipo = 'Dextrosa' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dex10=$resp_r['cantidad'];
}
if(isset($dex10)){
$pdf->SetY(111);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($dex10),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND  tipo = 'Dextrosa' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dex11=$resp_r['cantidad'];
}
if(isset($dex11)){
$pdf->SetY(111);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($dex11),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND  tipo = 'Dextrosa' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dex12=$resp_r['cantidad'];
}
if(isset($dex12)){
$pdf->SetY(111);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($dex12),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND  tipo = 'Dextrosa' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dex13=$resp_r['cantidad'];
}
if(isset($dex13)){
$pdf->SetY(111);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($dex13),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND  tipo = 'Dextrosa' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dex14=$resp_r['cantidad'];
}
if(isset($dex14)){
$pdf->SetY(111);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($dex14),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND  tipo = 'Dextrosa' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dex15=$resp_r['cantidad'];
}
if(isset($dex15)){
$pdf->SetY(111);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($dex15),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND  tipo = 'Dextrosa' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dex16=$resp_r['cantidad'];
}
if(isset($dex16)){
$pdf->SetY(111);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($dex16),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND  tipo = 'Dextrosa' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dex17=$resp_r['cantidad'];
}
if(isset($dex17)){
$pdf->SetY(111);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($dex17),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND  tipo = 'Dextrosa' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dex18=$resp_r['cantidad'];
}
if(isset($dex18)){
$pdf->SetY(111);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($dex18),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND  tipo = 'Dextrosa' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dex19=$resp_r['cantidad'];
}
if(isset($dex19)){
$pdf->SetY(111);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($dex19),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND  tipo = 'Dextrosa' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dex20=$resp_r['cantidad'];
}
if(isset($dex20)){
$pdf->SetY(111);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($dex20),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//inicio NOC glas inicio noc inicio noc glas inicio noc glas noc glas noc glas noc glas noc glas noc glas noc glas

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND  tipo = 'Dextrosa' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dex21=$resp_r['cantidad'];
}
if(isset($dex21)){
$pdf->SetY(111);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($dex21),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND  tipo = 'Dextrosa' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dex22=$resp_r['cantidad'];
}
if(isset($dex22)){
$pdf->SetY(111);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($dex22),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND  tipo = 'Dextrosa' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dex23=$resp_r['cantidad'];
}
if(isset($dex23)){
$pdf->SetY(111);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($dex23),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND  tipo = 'Dextrosa' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dex24=$resp_r['cantidad'];
}
if(isset($dex24)){
$pdf->SetY(111);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($dex24),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND  tipo = 'Dextrosa' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dex01=$resp_r['cantidad'];
}
if(isset($dex01)){
$pdf->SetY(111);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($dex01),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND  tipo = 'Dextrosa' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dex02=$resp_r['cantidad'];
}
if(isset($dex02)){
$pdf->SetY(111);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($dex02),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND  tipo = 'Dextrosa' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dex03=$resp_r['cantidad'];
}
if(isset($dex03)){
$pdf->SetY(111);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($dex03),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND  tipo = 'Dextrosa' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dex04=$resp_r['cantidad'];
}
if(isset($dex04)){
$pdf->SetY(111);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($dex04),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND  tipo = 'Dextrosa' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dex05=$resp_r['cantidad'];
}
if(isset($dex05)){
$pdf->SetY(111);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($dex05),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND  tipo = 'Dextrosa' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dex06=$resp_r['cantidad'];
}
if(isset($dex06)){
$pdf->SetY(111);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($dex06),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND  tipo = 'Dextrosa' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dex07=$resp_r['cantidad'];
}
if(isset($dex07)){
$pdf->SetY(111);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($dex07),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


// Oxigenoterapia / Oxigenoterapia / Oxigenoterapia / Oxigenoterapia / Oxigenoterapia / Oxigenoterapia

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND  tipo = 'Oxigenoterapia' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oxiter=$resp_r['cantidad'];
}
if(isset($oxiter)){
$pdf->SetY(117);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($oxiter),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND  tipo = 'Oxigenoterapia' AND ter = 'Si'") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oxiter9=$resp_r9['cantidad'];
}
if(isset($oxiter9)){
$pdf->SetY(117);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($oxiter9),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND  tipo = 'Oxigenoterapia' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oxiter10=$resp_r['cantidad'];
}
if(isset($oxiter10)){
$pdf->SetY(117);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($oxiter10),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND  tipo = 'Oxigenoterapia' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oxiter11=$resp_r['cantidad'];
}
if(isset($oxiter11)){
$pdf->SetY(117);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($oxiter11),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND  tipo = 'Oxigenoterapia' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oxiter12=$resp_r['cantidad'];
}
if(isset($oxiter12)){
$pdf->SetY(117);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($oxiter12),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND  tipo = 'Oxigenoterapia' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oxiter13=$resp_r['cantidad'];
}
if(isset($oxiter13)){
$pdf->SetY(117);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($oxiter13),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND  tipo = 'Oxigenoterapia' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oxiter14=$resp_r['cantidad'];
}
if(isset($oxiter14)){
$pdf->SetY(117);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($oxiter14),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND  tipo = 'Oxigenoterapia' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oxiter15=$resp_r['cantidad'];
}
if(isset($oxiter15)){
$pdf->SetY(117);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($oxiter15),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND  tipo = 'Oxigenoterapia' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oxiter16=$resp_r['cantidad'];
}
if(isset($oxiter16)){
$pdf->SetY(117);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($oxiter16),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND  tipo = 'Oxigenoterapia' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oxiter17=$resp_r['cantidad'];
}
if(isset($oxiter17)){
$pdf->SetY(117);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($oxiter17),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND  tipo = 'Oxigenoterapia' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oxiter18=$resp_r['cantidad'];
}
if(isset($oxiter18)){
$pdf->SetY(117);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($oxiter18),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND  tipo = 'Oxigenoterapia' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oxiter19=$resp_r['cantidad'];
}
if(isset($oxiter19)){
$pdf->SetY(117);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($oxiter19),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND  tipo = 'Oxigenoterapia' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oxiter20=$resp_r['cantidad'];
}
if(isset($oxiter20)){
$pdf->SetY(117);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($oxiter20),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//inicio NOC glas inicio noc inicio noc glas inicio noc glas noc glas noc glas noc glas noc glas noc glas noc glas

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND  tipo = 'Oxigenoterapia' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oxiter21=$resp_r['cantidad'];
}
if(isset($oxiter21)){
$pdf->SetY(117);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($oxiter21),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND  tipo = 'Oxigenoterapia' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oxiter22=$resp_r['cantidad'];
}
if(isset($oxiter22)){
$pdf->SetY(117);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($oxiter22),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND  tipo = 'Oxigenoterapia' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oxiter23=$resp_r['cantidad'];
}
if(isset($oxiter23)){
$pdf->SetY(117);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($oxiter23),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND  tipo = 'Oxigenoterapia' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oxiter24=$resp_r['cantidad'];
}
if(isset($oxiter24)){
$pdf->SetY(117);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($oxiter24),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND  tipo = 'Oxigenoterapia' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oxiter01=$resp_r['cantidad'];
}
if(isset($oxiter01)){
$pdf->SetY(117);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($oxiter01),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND  tipo = 'Oxigenoterapia' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oxiter02=$resp_r['cantidad'];
}
if(isset($oxiter02)){
$pdf->SetY(117);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($oxiter02),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND  tipo = 'Oxigenoterapia' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oxiter03=$resp_r['cantidad'];
}
if(isset($oxiter03)){
$pdf->SetY(117);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($oxiter03),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND  tipo = 'Oxigenoterapia' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oxiter04=$resp_r['cantidad'];
}
if(isset($oxiter04)){
$pdf->SetY(117);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($oxiter04),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND  tipo = 'Oxigenoterapia' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oxiter05=$resp_r['cantidad'];
}
if(isset($oxiter05)){
$pdf->SetY(117);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($oxiter05),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND  tipo = 'Oxigenoterapia' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oxiter06=$resp_r['cantidad'];
}
if(isset($oxiter06)){
$pdf->SetY(117);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($oxiter06),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND  tipo = 'Oxigenoterapia' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oxiter07=$resp_r['cantidad'];
}
if(isset($oxiter07)){
$pdf->SetY(117);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($oxiter07),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


// Micronebulizaciones / Micronebulizaciones / Micronebulizaciones / Micronebulizaciones / Micronebulizaciones / Micronebulizaciones

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND  tipo = 'Micronebulizaciones' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $micron=$resp_r['cantidad'];
}
if(isset($micron)){
$pdf->SetY(123);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($micron),1,0,'C');
}else{
  $pdf->SetY(123);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND  tipo = 'Micronebulizaciones' AND ter = 'Si'") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $micron9=$resp_r9['cantidad'];
}
if(isset($micron9)){
$pdf->SetY(123);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($micron9),1,0,'C');
}else{
  $pdf->SetY(123);
$pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND  tipo = 'Micronebulizaciones' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $micron10=$resp_r['cantidad'];
}
if(isset($micron10)){
$pdf->SetY(123);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($micron10),1,0,'C');
}else{
  $pdf->SetY(123);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND  tipo = 'Micronebulizaciones' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $micron11=$resp_r['cantidad'];
}
if(isset($micron11)){
$pdf->SetY(123);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($micron11),1,0,'C');
}else{
  $pdf->SetY(123);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND  tipo = 'Micronebulizaciones' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $micron12=$resp_r['cantidad'];
}
if(isset($micron12)){
$pdf->SetY(123);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($micron12),1,0,'C');
}else{
  $pdf->SetY(123);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND  tipo = 'Micronebulizaciones' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $micron13=$resp_r['cantidad'];
}
if(isset($micron13)){
$pdf->SetY(123);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($micron13),1,0,'C');
}else{
  $pdf->SetY(123);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND  tipo = 'Micronebulizaciones' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $micron14=$resp_r['cantidad'];
}
if(isset($micron14)){
$pdf->SetY(123);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($micron14),1,0,'C');
}else{
  $pdf->SetY(123);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND  tipo = 'Micronebulizaciones' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $micron15=$resp_r['cantidad'];
}
if(isset($micron15)){
$pdf->SetY(123);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($micron15),1,0,'C');
}else{
  $pdf->SetY(123);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND  tipo = 'Micronebulizaciones' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $micron16=$resp_r['cantidad'];
}
if(isset($micron16)){
$pdf->SetY(123);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($micron16),1,0,'C');
}else{
  $pdf->SetY(123);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND  tipo = 'Micronebulizaciones' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $micron17=$resp_r['cantidad'];
}
if(isset($micron17)){
$pdf->SetY(123);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($micron17),1,0,'C');
}else{
  $pdf->SetY(123);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND  tipo = 'Micronebulizaciones' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $micron18=$resp_r['cantidad'];
}
if(isset($micron18)){
$pdf->SetY(123);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($micron18),1,0,'C');
}else{
  $pdf->SetY(123);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND  tipo = 'Micronebulizaciones' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $micron19=$resp_r['cantidad'];
}
if(isset($micron19)){
$pdf->SetY(123);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($micron19),1,0,'C');
}else{
  $pdf->SetY(123);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND  tipo = 'Micronebulizaciones' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $micron20=$resp_r['cantidad'];
}
if(isset($micron20)){
$pdf->SetY(123);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($micron20),1,0,'C');
}else{
  $pdf->SetY(123);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//inicio NOC glas inicio noc inicio noc glas inicio noc glas noc glas noc glas noc glas noc glas noc glas noc glas

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND  tipo = 'Micronebulizaciones' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $micron21=$resp_r['cantidad'];
}
if(isset($micron21)){
$pdf->SetY(123);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($micron21),1,0,'C');
}else{
  $pdf->SetY(123);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND  tipo = 'Micronebulizaciones' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $micron22=$resp_r['cantidad'];
}
if(isset($micron22)){
$pdf->SetY(123);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($micron22),1,0,'C');
}else{
  $pdf->SetY(123);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND  tipo = 'Micronebulizaciones' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $micron23=$resp_r['cantidad'];
}
if(isset($micron23)){
$pdf->SetY(123);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($micron23),1,0,'C');
}else{
  $pdf->SetY(123);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND  tipo = 'Micronebulizaciones' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $micron24=$resp_r['cantidad'];
}
if(isset($micron24)){
$pdf->SetY(123);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($micron24),1,0,'C');
}else{
  $pdf->SetY(123);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND  tipo = 'Micronebulizaciones' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $micron01=$resp_r['cantidad'];
}
if(isset($micron01)){
$pdf->SetY(123);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($micron01),1,0,'C');
}else{
  $pdf->SetY(123);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND  tipo = 'Micronebulizaciones' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $micron02=$resp_r['cantidad'];
}
if(isset($micron02)){
$pdf->SetY(123);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($micron02),1,0,'C');
}else{
  $pdf->SetY(123);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND  tipo = 'Micronebulizaciones' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $micron03=$resp_r['cantidad'];
}
if(isset($micron03)){
$pdf->SetY(123);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($micron03),1,0,'C');
}else{
  $pdf->SetY(123);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND  tipo = 'Micronebulizaciones' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $micron04=$resp_r['cantidad'];
}
if(isset($micron04)){
$pdf->SetY(123);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($micron04),1,0,'C');
}else{
  $pdf->SetY(123);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND  tipo = 'Micronebulizaciones' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $micron05=$resp_r['cantidad'];
}
if(isset($micron05)){
$pdf->SetY(123);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($micron05),1,0,'C');
}else{
  $pdf->SetY(123);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND  tipo = 'Micronebulizaciones' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $micron06=$resp_r['cantidad'];
}
if(isset($micron06)){
$pdf->SetY(123);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($micron06),1,0,'C');
}else{
  $pdf->SetY(123);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND  tipo = 'Micronebulizaciones' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $micron07=$resp_r['cantidad'];
}
if(isset($micron07)){
$pdf->SetY(123);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($micron07),1,0,'C');
}else{
  $pdf->SetY(123);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


// Enema / Enema / Enema / Enema / Enema / Enema

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND  tipo = 'Enema' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $enema=$resp_r['cantidad'];
}
if(isset($enema)){
$pdf->SetY(129);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($enema),1,0,'C');
}else{
  $pdf->SetY(129);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND  tipo = 'Enema' AND ter = 'Si'") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $enema9=$resp_r9['cantidad'];
}
if(isset($enema9)){
$pdf->SetY(129);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($enema9),1,0,'C');
}else{
  $pdf->SetY(129);
$pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND  tipo = 'Enema' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $enema10=$resp_r['cantidad'];
}
if(isset($enema10)){
$pdf->SetY(129);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($enema10),1,0,'C');
}else{
  $pdf->SetY(129);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND  tipo = 'Enema' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $enema11=$resp_r['cantidad'];
}
if(isset($enema11)){
$pdf->SetY(129);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($enema11),1,0,'C');
}else{
  $pdf->SetY(129);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND  tipo = 'Enema' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $enema12=$resp_r['cantidad'];
}
if(isset($enema12)){
$pdf->SetY(129);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($enema12),1,0,'C');
}else{
  $pdf->SetY(129);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND  tipo = 'Enema' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $enema13=$resp_r['cantidad'];
}
if(isset($enema13)){
$pdf->SetY(129);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($enema13),1,0,'C');
}else{
  $pdf->SetY(129);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND  tipo = 'Enema' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $enema14=$resp_r['cantidad'];
}
if(isset($enema14)){
$pdf->SetY(129);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($enema14),1,0,'C');
}else{
  $pdf->SetY(129);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND  tipo = 'Enema' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $enema15=$resp_r['cantidad'];
}
if(isset($enema15)){
$pdf->SetY(129);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($enema15),1,0,'C');
}else{
  $pdf->SetY(129);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND  tipo = 'Enema' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $enema16=$resp_r['cantidad'];
}
if(isset($enema16)){
$pdf->SetY(129);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($enema16),1,0,'C');
}else{
  $pdf->SetY(129);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND  tipo = 'Enema' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $enema17=$resp_r['cantidad'];
}
if(isset($enema17)){
$pdf->SetY(129);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($enema17),1,0,'C');
}else{
  $pdf->SetY(129);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND  tipo = 'Enema' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $enema18=$resp_r['cantidad'];
}
if(isset($enema18)){
$pdf->SetY(129);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($enema18),1,0,'C');
}else{
  $pdf->SetY(129);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND  tipo = 'Enema' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $enema19=$resp_r['cantidad'];
}
if(isset($enema19)){
$pdf->SetY(129);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($enema19),1,0,'C');
}else{
  $pdf->SetY(129);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND  tipo = 'Enema' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $enema20=$resp_r['cantidad'];
}
if(isset($enema20)){
$pdf->SetY(129);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($enema20),1,0,'C');
}else{
  $pdf->SetY(129);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//inicio NOC glas inicio noc inicio noc glas inicio noc glas noc glas noc glas noc glas noc glas noc glas noc glas

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND  tipo = 'Enema' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $enema21=$resp_r['cantidad'];
}
if(isset($enema21)){
$pdf->SetY(129);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($enema21),1,0,'C');
}else{
  $pdf->SetY(129);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND  tipo = 'Enema' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $enema22=$resp_r['cantidad'];
}
if(isset($enema22)){
$pdf->SetY(129);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($enema22),1,0,'C');
}else{
  $pdf->SetY(129);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND  tipo = 'Enema' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $enema23=$resp_r['cantidad'];
}
if(isset($enema23)){
$pdf->SetY(129);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($enema23),1,0,'C');
}else{
  $pdf->SetY(129);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND  tipo = 'Enema' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $enema24=$resp_r['cantidad'];
}
if(isset($enema24)){
$pdf->SetY(129);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($enema24),1,0,'C');
}else{
  $pdf->SetY(129);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND  tipo = 'Enema' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $enema01=$resp_r['cantidad'];
}
if(isset($enema01)){
$pdf->SetY(129);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($enema01),1,0,'C');
}else{
  $pdf->SetY(129);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND  tipo = 'Enema' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $enema02=$resp_r['cantidad'];
}
if(isset($enema02)){
$pdf->SetY(129);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($enema02),1,0,'C');
}else{
  $pdf->SetY(129);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND  tipo = 'Enema' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $enema03=$resp_r['cantidad'];
}
if(isset($enema03)){
$pdf->SetY(129);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($enema03),1,0,'C');
}else{
  $pdf->SetY(129);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND  tipo = 'Enema' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $enema04=$resp_r['cantidad'];
}
if(isset($enema04)){
$pdf->SetY(129);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($enema04),1,0,'C');
}else{
  $pdf->SetY(129);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND  tipo = 'Enema' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $enema05=$resp_r['cantidad'];
}
if(isset($enema05)){
$pdf->SetY(129);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($enema05),1,0,'C');
}else{
  $pdf->SetY(129);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND  tipo = 'Enema' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $enema06=$resp_r['cantidad'];
}
if(isset($enema06)){
$pdf->SetY(129);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($enema06),1,0,'C');
}else{
  $pdf->SetY(129);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND  tipo = 'Enema' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $enema07=$resp_r['cantidad'];
}
if(isset($enema07)){
$pdf->SetY(129);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($enema07),1,0,'C');
}else{
  $pdf->SetY(129);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


//SIGNOS VITALES SIGNOS VITALES T/A SIGNOS VITALES T/A SIGNOS VITALES T/A SIGNOS VITALES T/A SIGNOS VITALES T/A SIGNOS VITALES
//presion arterial sistolica y diastolica
$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $p_s8=$resp_r['p_sistol'];
  $p_d8=$resp_r['p_diastol'];
}
if(isset($p_s8)){
$pdf->SetY(143);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($p_s8 . '/' . $p_d8),1,0,'C');
}else{
  $pdf->SetY(143);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $p_s9=$resp_r['p_sistol'];
  $p_d9=$resp_r['p_diastol'];
}
if(isset($p_s9)){
$pdf->SetY(143);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($p_s9 . '/' . $p_d9),1,0,'C');
}else{
  $pdf->SetY(143);
  $pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $p_s10=$resp_r['p_sistol'];
  $p_d10=$resp_r['p_diastol'];
}
if(isset($p_s10)){
$pdf->SetY(143);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($p_s10 . '/' . $p_d10),1,0,'C');
}else{
  $pdf->SetY(143);
  $pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $p_s11=$resp_r['p_sistol'];
  $p_d11=$resp_r['p_diastol'];
}
if(isset($p_s11)){
$pdf->SetY(143);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($p_s11 . '/' . $p_d11),1,0,'C');
}else{
  $pdf->SetY(143);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $p_s12=$resp_r['p_sistol'];
  $p_d12=$resp_r['p_diastol'];
}
if(isset($p_s12)){
$pdf->SetY(143);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($p_s12 . '/' . $p_d12),1,0,'C');
}else{
  $pdf->SetY(143);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $p_s13=$resp_r['p_sistol'];
  $p_d13=$resp_r['p_diastol'];
}
if(isset($p_s13)){
$pdf->SetY(143);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($p_s13 . '/' . $p_d13),1,0,'C');
}else{
  $pdf->SetY(143);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $p_s14=$resp_r['p_sistol'];
  $p_d14=$resp_r['p_diastol'];
}
if(isset($p_s14)){
$pdf->SetY(143);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($p_s14 . '/' . $p_d14),1,0,'C');
}else{
  $pdf->SetY(143);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $p_s15=$resp_r['p_sistol'];
  $p_d15=$resp_r['p_diastol'];
}
if(isset($p_s15)){
$pdf->SetY(143);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($p_s15 . '/' . $p_d15),1,0,'C');
}else{
  $pdf->SetY(143);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $p_s16=$resp_r['p_sistol'];
  $p_d16=$resp_r['p_diastol'];
}
if(isset($p_s16)){
$pdf->SetY(143);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($p_s16 . '/' . $p_d16),1,0,'C');
}else{
  $pdf->SetY(143);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
 $p_s17=$resp_r['p_sistol'];
  $p_d17=$resp_r['p_diastol'];
}
if(isset($p_s17)){
$pdf->SetY(143);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($p_s17 . '/' . $p_d17),1,0,'C');
}else{
  $pdf->SetY(143);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $p_s18=$resp_r['p_sistol'];
  $p_d18=$resp_r['p_diastol'];
}
if(isset($p_s18)){
$pdf->SetY(143);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($p_s18 . '/' . $p_d18),1,0,'C');
}else{
  $pdf->SetY(143);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $p_s19=$resp_r['p_sistol'];
  $p_d19=$resp_r['p_diastol'];
}
if(isset($p_s19)){
$pdf->SetY(143);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($p_s19 . '/' . $p_d19),1,0,'C');
}else{
  $pdf->SetY(143);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $p_s20=$resp_r['p_sistol'];
  $p_d20=$resp_r['p_diastol'];
}
if(isset($p_s20)){
$pdf->SetY(143);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($p_s20 . '/' . $p_d20),1,0,'C');
}else{
  $pdf->SetY(143);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
   $p_s21=$resp_r['p_sistol'];
  $p_d21=$resp_r['p_diastol'];
}
if(isset($p_s21)){
$pdf->SetY(143);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($p_s21 . '/' . $p_d21),1,0,'C');
}else{
  $pdf->SetY(143);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $p_s22=$resp_r['p_sistol'];
  $p_d22=$resp_r['p_diastol'];
}
if(isset($p_s22)){
$pdf->SetY(143);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($p_s22 . '/' . $p_d22),1,0,'C');
}else{
  $pdf->SetY(143);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $p_s23=$resp_r['p_sistol'];
  $p_d23=$resp_r['p_diastol'];
}
if(isset($p_s23)){
$pdf->SetY(143);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($p_s23 . '/' . $p_d23),1,0,'C');
}else{
  $pdf->SetY(143);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $p_s24=$resp_r['p_sistol'];
  $p_d24=$resp_r['p_diastol'];
}
if(isset($p_s24)){
$pdf->SetY(143);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($p_s24 . '/' . $p_d24),1,0,'C');
}else{
  $pdf->SetY(143);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $p_s1=$resp_r['p_sistol'];
  $p_d1=$resp_r['p_diastol'];
}
if(isset($p_s1)){
$pdf->SetY(143);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($p_s1 . '/' . $p_d1),1,0,'C');
}else{
  $pdf->SetY(143);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
$p_s2=$resp_r['p_sistol'];
  $p_d2=$resp_r['p_diastol'];
}
if(isset($p_s2)){
$pdf->SetY(143);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($p_s2 . '/' . $p_d2),1,0,'C');
}else{
  $pdf->SetY(143);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
 $p_s3=$resp_r['p_sistol'];
  $p_d3=$resp_r['p_diastol'];
}
if(isset($p_s3)){
$pdf->SetY(143);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($p_s3 . '/' . $p_d3),1,0,'C');
}else{
  $pdf->SetY(143);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $p_s4=$resp_r['p_sistol'];
  $p_d4=$resp_r['p_diastol'];
}
if(isset($p_s4)){
$pdf->SetY(143);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($p_s4 . '/' . $p_d4),1,0,'C');
}else{
  $pdf->SetY(143);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
 $p_s5=$resp_r['p_sistol'];
  $p_d5=$resp_r['p_diastol'];
}
if(isset($p_s5)){
$pdf->SetY(143);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($p_s5 . '/' . $p_d5),1,0,'C');
}else{
  $pdf->SetY(143);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
 $p_s6=$resp_r['p_sistol'];
  $p_d6=$resp_r['p_diastol'];
}
if(isset($p_s6)){
$pdf->SetY(143);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($p_s6 . '/' . $p_d6),1,0,'C');
}else{
  $pdf->SetY(143);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
   $p_s7=$resp_r['p_sistol'];
  $p_d7=$resp_r['p_diastol'];
}
if(isset($p_s7)){
$pdf->SetY(143);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($p_s7 . '/' . $p_d7),1,0,'C');
}else{
  $pdf->SetY(143);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//TAM  TAM  TAM  TAM  TAM  TAM
$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $t_oc=$resp_r['tam'];
}
if(isset($t_oc)){
$pdf->SetY(149);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($t_oc),1,0,'C');
}else{
  $pdf->SetY(149);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $t_am=$resp_r9['tam'];
}
if(isset($t_am)){
$pdf->SetY(149);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($t_am),1,0,'C');
}else{
 $pdf->SetY(149);
 $pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $t_diez=$resp_r['tam'];
}
if(isset($t_diez)){
$pdf->SetY(149);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($t_diez),1,0,'C');
}else{
  $pdf->SetY(149);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $t_once=$resp_r['tam'];
}
if(isset($t_once)){
$pdf->SetY(149);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($t_once),1,0,'C');
}else{
  $pdf->SetY(149);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $t_doce=$resp_r['tam'];
}
if(isset($t_doce)){
$pdf->SetY(149);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($t_doce),1,0,'C');
}else{
  $pdf->SetY(149);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $t_trec=$resp_r['tam'];
}
if(isset($t_trec)){
$pdf->SetY(149);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($t_trec),1,0,'C');
}else{
  $pdf->SetY(149);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $t_cato=$resp_r['tam'];
}
if(isset($t_cato)){
$pdf->SetY(149);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($t_cato),1,0,'C');
}else{
  $pdf->SetY(149);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $t_qince=$resp_r['tam'];
}
if(isset($t_qince)){
$pdf->SetY(149);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($t_qince),1,0,'C');
}else{
  $pdf->SetY(149);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $t_diezseis=$resp_r['tam'];
}
if(isset($t_diezseis)){
$pdf->SetY(149);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($t_diezseis),1,0,'C');
}else{
  $pdf->SetY(149);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $t_diezsiete=$resp_r['tam'];
}
if(isset($t_diezsiete)){
$pdf->SetY(149);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($t_diezsiete),1,0,'C');
}else{
  $pdf->SetY(149);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $t_diezocho=$resp_r['tam'];
}
if(isset($t_diezocho)){
$pdf->SetY(149);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($t_diezocho),1,0,'C');
}else{
  $pdf->SetY(149);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dieznueve=$resp_r['tam'];
}
if(isset($dieznueve)){
$pdf->SetY(149);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($dieznueve),1,0,'C');
}else{
  $pdf->SetY(149);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $t_veincte=$resp_r['tam'];
}
if(isset($t_veincte)){
$pdf->SetY(149);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($t_veincte),1,0,'C');
}else{
  $pdf->SetY(149);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//inicio NOC glas inicio noc inicio noc glas inicio noc glas noc glas noc glas noc glas noc glas noc glas noc glas

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $t_vunod=$resp_r['tam'];
}
if(isset($t_vunod)){
$pdf->SetY(149);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($t_vunod),1,0,'C');
}else{
  $pdf->SetY(149);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $t_vvdos=$resp_r['tam'];
}
if(isset($t_vvdos)){
$pdf->SetY(149);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($t_vvdos),1,0,'C');
}else{
  $pdf->SetY(149);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $t_vvtres=$resp_r['tam'];
}
if(isset($t_vvtres)){
$pdf->SetY(149);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($t_vvtres),1,0,'C');
}else{
  $pdf->SetY(149);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $t_vvcu=$resp_r['tam'];
}
if(isset($t_vvcu)){
$pdf->SetY(149);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($t_vvcu),1,0,'C');
}else{
  $pdf->SetY(149);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $t_unooo=$resp_r['tam'];
}
if(isset($t_unooo)){
$pdf->SetY(149);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($t_unooo),1,0,'C');
}else{
  $pdf->SetY(149);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $t_dodos=$resp_r['tam'];
}
if(isset($t_dodos)){
$pdf->SetY(149);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($t_dodos),1,0,'C');
}else{
  $pdf->SetY(149);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $t_trest=$resp_r['tam'];
}
if(isset($t_trest)){
$pdf->SetY(149);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($t_trest),1,0,'C');
}else{
  $pdf->SetY(149);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $t_cuatt=$resp_r['tam'];
}
if(isset($t_cuatt)){
$pdf->SetY(149);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($t_cuatt),1,0,'C');
}else{
  $pdf->SetY(149);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $t_cint=$resp_r['tam'];
}
if(isset($t_cint)){
$pdf->SetY(149);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($t_cint),1,0,'C');
}else{
  $pdf->SetY(149);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ta_sesis=$resp_r['tam'];
}
if(isset($ta_sesis)){
$pdf->SetY(149);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($ta_sesis),1,0,'C');
}else{
  $pdf->SetY(149);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $tam_siete=$resp_r['tam'];
}
if(isset($tam_siete)){
$pdf->SetY(149);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($tam_siete),1,0,'C');
}else{
  $pdf->SetY(149);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//TEMPERATURA TEMPERATURA TEMPERATURA TEMPERATURA TEMPERATURA TEMPTERATURA TEMPERATUR TEMPERATUR

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $tem_och=$resp_r['temper'];
}
if(isset($tem_och)){
$pdf->SetY(155);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($tem_och . 'º'),1,0,'C');
}else{
  $pdf->SetY(155);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $tem_nuve=$resp_r9['temper'];
}
if(isset($tem_nuve)){
$pdf->SetY(155);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($tem_nuve . 'º'),1,0,'C');
}else{
  $pdf->SetY(155);
$pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $tem_diez=$resp_r['temper'];
}
if(isset($tem_diez)){
$pdf->SetY(155);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($tem_diez . 'º'),1,0,'C');
}else{
  $pdf->SetY(155);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $tem_once=$resp_r['temper'];
}
if(isset($tem_once)){
$pdf->SetY(155);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($tem_once . 'º'),1,0,'C');
}else{
  $pdf->SetY(155);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $tem_doce=$resp_r['temper'];
}
if(isset($tem_doce)){
$pdf->SetY(155);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($tem_doce . 'º'),1,0,'C');
}else{
  $pdf->SetY(155);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $tempr_trece=$resp_r['temper'];
}
if(isset($tempr_trece)){
$pdf->SetY(155);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($tempr_trece . 'º'),1,0,'C');
}else{
  $pdf->SetY(155);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temp_cat=$resp_r['temper'];
}
if(isset($temp_cat)){
$pdf->SetY(155);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($temp_cat . 'º'),1,0,'C');
}else{
  $pdf->SetY(155);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temp_qinc=$resp_r['temper'];
}
if(isset($temp_qinc)){
$pdf->SetY(155);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($temp_qinc . 'º'),1,0,'C');
}else{
  $pdf->SetY(155);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $tem_dseis=$resp_r['temper'];
}
if(isset($tem_dseis)){
$pdf->SetY(155);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($tem_dseis . 'º'),1,0,'C');
}else{
  $pdf->SetY(155);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $tem_disiete=$resp_r['temper'];
}
if(isset($tem_disiete)){
$pdf->SetY(155);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($tem_disiete . 'º'),1,0,'C');
}else{
  $pdf->SetY(155);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $tem_diocho=$resp_r['temper'];
}
if(isset($tem_diocho)){
$pdf->SetY(155);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($tem_diocho . 'º'),1,0,'C');
}else{
  $pdf->SetY(155);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $tem_dnue=$resp_r['temper'];
}
if(isset($tem_dnue)){
$pdf->SetY(155);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($tem_dnue . 'º'),1,0,'C');
}else{
  $pdf->SetY(155);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $tempe_veinte=$resp_r['temper'];
}
if(isset($tempe_veinte)){
$pdf->SetY(155);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($tempe_veinte . 'º'),1,0,'C');
}else{
  $pdf->SetY(155);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//inicio NOC glas inicio noc inicio noc glas inicio noc glas noc glas noc glas noc glas noc glas noc glas noc glas

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temper_veuno=$resp_r['temper'];
}
if(isset($temper_veuno)){
$pdf->SetY(155);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($temper_veuno . 'º'),1,0,'C');
}else{
  $pdf->SetY(155);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temper_vvdos=$resp_r['temper'];
}
if(isset($temper_vvdos)){
$pdf->SetY(155);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($temper_vvdos . 'º'),1,0,'C');
}else{
  $pdf->SetY(155);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temper_veitres=$resp_r['temper'];
}
if(isset($temper_veitres)){
$pdf->SetY(155);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($temper_veitres . 'º'),1,0,'C');
}else{
  $pdf->SetY(155);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $tmper_vcu=$resp_r['temper'];
}
if(isset($tmper_vcu)){
$pdf->SetY(155);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($tmper_vcu . 'º'),1,0,'C');
}else{
  $pdf->SetY(155);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temper_no=$resp_r['temper'];
}
if(isset($temper_no)){
$pdf->SetY(155);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($temper_no . 'º'),1,0,'C');
}else{
  $pdf->SetY(155);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $tem_dos=$resp_r['temper'];
}
if(isset($tem_dos)){
$pdf->SetY(155);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($tem_dos . 'º'),1,0,'C');
}else{
  $pdf->SetY(155);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $tem_tres=$resp_r['temper'];
}
if(isset($tem_tres)){
$pdf->SetY(155);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($tem_tres . 'º'),1,0,'C');
}else{
  $pdf->SetY(155);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $tem_cuuu=$resp_r['temper'];
}
if(isset($tem_cuuu)){
$pdf->SetY(155);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($tem_cuuu . 'º'),1,0,'C');
}else{
  $pdf->SetY(155);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $te_cincoo=$resp_r['temper'];
}
if(isset($te_cincoo)){
$pdf->SetY(155);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($te_cincoo . 'º'),1,0,'C');
}else{
  $pdf->SetY(155);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $tem_seisss=$resp_r['temper'];
}
if(isset($tem_seisss)){
$pdf->SetY(155);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($tem_seisss . 'º'),1,0,'C');
}else{
  $pdf->SetY(155);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $tem_siette=$resp_r['temper'];
}
if(isset($tem_siette)){
$pdf->SetY(155);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($tem_siette . 'º'),1,0,'C');
}else{
  $pdf->SetY(155);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


//frecuencia cardiaca frec cardiaca frec cardiaca frec cardiaca frec cardiaca frec cardiaca frec cardiaca frec cardiaca

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cardoch=$resp_r['fcard'];
}
if(isset($cardoch)){
$pdf->SetY(161);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($cardoch),1,0,'C');
}else{
  $pdf->SetY(161);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cardnueve=$resp_r9['fcard'];
}
if(isset($cardnueve)){
$pdf->SetY(161);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($cardnueve),1,0,'C');
}else{
  $pdf->SetY(161);
$pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cariez=$resp_r['fcard'];
}
if(isset($cariez)){
$pdf->SetY(161);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($cariez),1,0,'C');
}else{
  $pdf->SetY(161);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fconce=$resp_r['fcard'];
}
if(isset($fconce)){
$pdf->SetY(161);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($fconce),1,0,'C');
}else{
  $pdf->SetY(161);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cardoce=$resp_r['fcard'];
}
if(isset($cardoce)){
$pdf->SetY(161);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($cardoce),1,0,'C');
}else{
  $pdf->SetY(161);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cartrece=$resp_r['fcard'];
}
if(isset($cartrece)){
$pdf->SetY(161);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($cartrece),1,0,'C');
}else{
  $pdf->SetY(161);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cardcat=$resp_r['fcard'];
}
if(isset($cardcat)){
$pdf->SetY(161);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($cardcat),1,0,'C');
}else{
  $pdf->SetY(161);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carquinc=$resp_r['fcard'];
}
if(isset($carquinc)){
$pdf->SetY(161);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($carquinc),1,0,'C');
}else{
  $pdf->SetY(161);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cardiseis=$resp_r['fcard'];
}
if(isset($cardiseis)){
$pdf->SetY(161);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($cardiseis),1,0,'C');
}else{
  $pdf->SetY(161);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cardiesietee=$resp_r['fcard'];
}
if(isset($cardiesietee)){
$pdf->SetY(161);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($cardiesietee),1,0,'C');
}else{
  $pdf->SetY(161);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cardocho=$resp_r['fcard'];
}
if(isset($cardocho)){
$pdf->SetY(161);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($cardocho),1,0,'C');
}else{
  $pdf->SetY(161);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cardieznueve=$resp_r['fcard'];
}
if(isset($cardieznueve)){
$pdf->SetY(161);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($cardieznueve),1,0,'C');
}else{
  $pdf->SetY(161);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcarvente=$resp_r['fcard'];
}
if(isset($fcarvente)){
$pdf->SetY(161);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($fcarvente),1,0,'C');
}else{
  $pdf->SetY(161);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//inicio NOC glas inicio noc inicio noc glas inicio noc glas noc glas noc glas noc glas noc glas noc glas noc glas

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carveinuno=$resp_r['fcard'];
}
if(isset($carveinuno)){
$pdf->SetY(161);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($carveinuno),1,0,'C');
}else{
  $pdf->SetY(161);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carveidd=$resp_r['fcard'];
}
if(isset($carveidd)){
$pdf->SetY(161);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($carveidd),1,0,'C');
}else{
  $pdf->SetY(161);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carvetres=$resp_r['fcard'];
}
if(isset($carvetres)){
$pdf->SetY(161);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($carvetres),1,0,'C');
}else{
  $pdf->SetY(161);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vcarcuat=$resp_r['fcard'];
}
if(isset($vcarcuat)){
$pdf->SetY(161);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($vcarcuat),1,0,'C');
}else{
  $pdf->SetY(161);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carunoo=$resp_r['fcard'];
}
if(isset($carunoo)){
$pdf->SetY(161);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($carunoo),1,0,'C');
}else{
  $pdf->SetY(161);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cardosss=$resp_r['fcard'];
}
if(isset($cardosss)){
$pdf->SetY(161);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($cardosss),1,0,'C');
}else{
  $pdf->SetY(161);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cartress=$resp_r['fcard'];
}
if(isset($cartress)){
$pdf->SetY(161);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($cartress),1,0,'C');
}else{
  $pdf->SetY(161);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carcuatt=$resp_r['fcard'];
}
if(isset($carcuatt)){
$pdf->SetY(161);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($carcuatt),1,0,'C');
}else{
  $pdf->SetY(161);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carcincc=$resp_r['fcard'];
}
if(isset($carcincc)){
$pdf->SetY(161);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($carcincc),1,0,'C');
}else{
  $pdf->SetY(161);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carseisss=$resp_r['fcard'];
}
if(isset($carseisss)){
$pdf->SetY(161);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($carseisss),1,0,'C');
}else{
  $pdf->SetY(161);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carsiett=$resp_r['fcard'];
}
if(isset($carsiett)){
$pdf->SetY(161);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($carsiett),1,0,'C');
}else{
  $pdf->SetY(161);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//ref resp frec resp frec resp frec resp frec resp frec resp frec resp frec resp frec resp frec resp frec resp frec resp

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $reocho=$resp_r['fresp'];
}
if(isset($reocho)){
$pdf->SetY(167);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($reocho),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $renuev=$resp_r9['fresp'];
}
if(isset($renuev)){
$pdf->SetY(167);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($renuev),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fediez=$resp_r['fresp'];
}
if(isset($fediez)){
$pdf->SetY(167);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($fediez),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $reonce=$resp_r['fresp'];
}
if(isset($reonce)){
$pdf->SetY(167);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($reonce),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fedoce=$resp_r['fresp'];
}
if(isset($fedoce)){
$pdf->SetY(167);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($fedoce),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fetrece=$resp_r['fresp'];
}
if(isset($fetrece)){
$pdf->SetY(167);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($fetrece),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $recartt=$resp_r['fresp'];
}
if(isset($recartt)){
$pdf->SetY(167);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($recartt),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $requin=$resp_r['fresp'];
}
if(isset($requin)){
$pdf->SetY(167);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($requin),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $redieseis=$resp_r['fresp'];
}
if(isset($redieseis)){
$pdf->SetY(167);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($redieseis),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rediesiete=$resp_r['fresp'];
}
if(isset($rediesiete)){
$pdf->SetY(167);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($rediesiete),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $redieocho=$resp_r['fresp'];
}
if(isset($redieocho)){
$pdf->SetY(167);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($redieocho),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $redinueve=$resp_r['fresp'];
}
if(isset($redinueve)){
$pdf->SetY(167);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($redinueve),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $reveint=$resp_r['fresp'];
}
if(isset($reveint)){
$pdf->SetY(167);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($reveint),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//inicio NOC glas inicio noc inicio noc glas inicio noc glas noc glas noc glas noc glas noc glas noc glas noc glas

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $reveuno=$resp_r['fresp'];
}
if(isset($reveuno)){
$pdf->SetY(167);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($reveuno),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $reveidis=$resp_r['fresp'];
}
if(isset($reveidis)){
$pdf->SetY(167);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($reveidis),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $revecuato=$resp_r['fresp'];
}
if(isset($revecuato)){
$pdf->SetY(167);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($revecuato),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $revecuatro=$resp_r['fresp'];
}
if(isset($revecuatro)){
$pdf->SetY(167);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($revecuatro),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $reuni=$resp_r['fresp'];
}
if(isset($reuni)){
$pdf->SetY(167);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($reuni),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $redoooos=$resp_r['fresp'];
}
if(isset($redoooos)){
$pdf->SetY(167);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($redoooos),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rertes=$resp_r['fresp'];
}
if(isset($rertes)){
$pdf->SetY(167);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($rertes),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $recincautro=$resp_r['fresp'];
}
if(isset($recincautro)){
$pdf->SetY(167);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($recincautro),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $reequinco=$resp_r['fresp'];
}
if(isset($reequinco)){
$pdf->SetY(167);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($reequinco),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $reseisss=$resp_r['fresp'];
}
if(isset($reseisss)){
$pdf->SetY(167);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($reseisss),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $resiiiete=$resp_r['fresp'];
}
if(isset($resiiiete)){
$pdf->SetY(167);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($resiiiete),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//sat oxigeno sat oxigeno sat oxigeno sat oxigeno sat oxigeno SAT OXIGENO SAT OXIGENO SAT OXIGENO SAT OXIGENO SAT OXIGENO

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $satoc=$resp_r['satoxi'];
}
if(isset($satoc)){
$pdf->SetY(173);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($satoc),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $satnuev=$resp_r9['satoxi'];
}
if(isset($satnuev)){
$pdf->SetY(173);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($satnuev),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $satdiez=$resp_r['satoxi'];
}
if(isset($satdiez)){
$pdf->SetY(173);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($satdiez),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $satonce=$resp_r['satoxi'];
}
if(isset($satonce)){
$pdf->SetY(173);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($satonce),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $satdocee=$resp_r['satoxi'];
}
if(isset($satdocee)){
$pdf->SetY(173);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($satdocee),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sattrece=$resp_r['satoxi'];
}
if(isset($sattrece)){
$pdf->SetY(173);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($sattrece),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $satcat=$resp_r['satoxi'];
}
if(isset($satcat)){
$pdf->SetY(173);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($satcat),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $satquin=$resp_r['satoxi'];
}
if(isset($satquin)){
$pdf->SetY(173);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($satquin),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $satdiesseis=$resp_r['satoxi'];
}
if(isset($satdiesseis)){
$pdf->SetY(173);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($satdiesseis),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $satdiesiete=$resp_r['satoxi'];
}
if(isset($satdiesiete)){
$pdf->SetY(173);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($satdiesiete),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $satdiocho=$resp_r['satoxi'];
}
if(isset($satdiocho)){
$pdf->SetY(173);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($satdiocho),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $satdiznuev=$resp_r['satoxi'];
}
if(isset($satdiznuev)){
$pdf->SetY(173);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($satdiznuev),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $satveint=$resp_r['satoxi'];
}
if(isset($satveint)){
$pdf->SetY(173);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($satveint),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//SATURACION OXIGENO

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $satveuno=$resp_r['satoxi'];
}
if(isset($satveuno)){
$pdf->SetY(173);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($satveuno),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $satvddos=$resp_r['satoxi'];
}
if(isset($satvddos)){
$pdf->SetY(173);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($satvddos),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $satvetres=$resp_r['satoxi'];
}
if(isset($satvetres)){
$pdf->SetY(173);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($satvetres),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $satvcuatro=$resp_r['satoxi'];
}
if(isset($satvcuatro)){
$pdf->SetY(173);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($satvcuatro),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $satunoos=$resp_r['satoxi'];
}
if(isset($satunoos)){
$pdf->SetY(173);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($satunoos),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $satdooos=$resp_r['satoxi'];
}
if(isset($satdooos)){
$pdf->SetY(173);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($satdooos),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sattrees=$resp_r['satoxi'];
}
if(isset($sattrees)){
$pdf->SetY(173);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($sattrees),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $satcuatt=$resp_r['satoxi'];
}
if(isset($satcuatt)){
$pdf->SetY(173);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($satcuatt),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $satcincooo=$resp_r['satoxi'];
}
if(isset($satcincooo)){
$pdf->SetY(173);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($satcincooo),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $satseis=$resp_r['satoxi'];
}
if(isset($satseis)){
$pdf->SetY(173);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($satseis),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $satsieete=$resp_r['satoxi'];
}
if(isset($satsieete)){
$pdf->SetY(173);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($satsieete),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//NIVEL DEL DOLOR  NIVEL DEL DOLOR  NIVEL DEL DOLOR  NIVEL DEL DOLOR  NIVEL DEL DOLOR

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nivd=$resp_r['niv_dolor'];
}
if(isset($nivd)){
$pdf->SetY(179);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($nivd),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nivd9=$resp_r9['niv_dolor'];
}
if(isset($nivd9)){
$pdf->SetY(179);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($nivd9),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nivd10=$resp_r['niv_dolor'];
}
if(isset($nivd10)){
$pdf->SetY(179);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($nivd10),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nivd11=$resp_r['niv_dolor'];
}
if(isset($nivd11)){
$pdf->SetY(179);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($nivd11),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nivd12=$resp_r['niv_dolor'];
}
if(isset($nivd12)){
$pdf->SetY(179);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($nivd12),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nivd13=$resp_r['niv_dolor'];
}
if(isset($nivd13)){
$pdf->SetY(179);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($nivd13),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nivd14=$resp_r['niv_dolor'];
}
if(isset($nivd14)){
$pdf->SetY(179);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($nivd14),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nivd15=$resp_r['niv_dolor'];
}
if(isset($nivd15)){
$pdf->SetY(179);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($nivd15),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nivd16=$resp_r['niv_dolor'];
}
if(isset($nivd16)){
$pdf->SetY(179);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($nivd16),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nivd17=$resp_r['niv_dolor'];
}
if(isset($nivd17)){
$pdf->SetY(179);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($nivd17),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nivd18=$resp_r['niv_dolor'];
}
if(isset($nivd18)){
$pdf->SetY(179);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($nivd18),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nivd19=$resp_r['niv_dolor'];
}
if(isset($nivd19)){
$pdf->SetY(179);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($nivd19),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nivd20=$resp_r['niv_dolor'];
}
if(isset($nivd20)){
$pdf->SetY(179);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($nivd20),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nivd21=$resp_r['niv_dolor'];
}
if(isset($nivd21)){
$pdf->SetY(179);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($nivd21),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nivd22=$resp_r['niv_dolor'];
}
if(isset($nivd22)){
$pdf->SetY(179);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($nivd22),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nivd23=$resp_r['niv_dolor'];
}
if(isset($nivd23)){
$pdf->SetY(179);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($nivd23),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nivd24=$resp_r['niv_dolor'];
}
if(isset($nivd24)){
$pdf->SetY(179);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($nivd24),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nivd01=$resp_r['niv_dolor'];
}
if(isset($nivd01)){
$pdf->SetY(179);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($nivd01),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nivd02=$resp_r['niv_dolor'];
}
if(isset($nivd02)){
$pdf->SetY(179);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($nivd02),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nivd03=$resp_r['niv_dolor'];
}
if(isset($nivd03)){
$pdf->SetY(179);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($nivd03),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nivd04=$resp_r['niv_dolor'];
}
if(isset($nivd04)){
$pdf->SetY(179);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($nivd04),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nivd05=$resp_r['niv_dolor'];
}
if(isset($nivd05)){
$pdf->SetY(179);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($nivd05),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nivd06=$resp_r['niv_dolor'];
}
if(isset($nivd06)){
$pdf->SetY(179);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($nivd06),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from signos_vitales where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nivd07=$resp_r['niv_dolor'];
}
if(isset($nivd07)){
$pdf->SetY(179);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($nivd07),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//MODO VENTILATORIO MODO VENTILATORIO MODO VENTILATORIO MODO VENTILATORIO MODO VENTILATORIO MODO VENTILATORIO MODO VENTILATORIO
// Modo ventilatorio / Modo ventilatorio / Modo ventilatorio / Modo ventilatorio / Modo ventilatorio / Modo ventilatorio

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND  tipo = 'Modo ventilatorio' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $mventila=$resp_r['cantidad'];
}
if(isset($mventila)){
$pdf->SetY(193);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($mventila),1,0,'C');
}else{
  $pdf->SetY(193);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND  tipo = 'Modo ventilatorio' AND ter = 'Si'") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $mventila9=$resp_r9['cantidad'];
}
if(isset($mventila9)){
$pdf->SetY(193);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($mventila9),1,0,'C');
}else{
  $pdf->SetY(193);
$pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND  tipo = 'Modo ventilatorio' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $mventila10=$resp_r['cantidad'];
}
if(isset($mventila10)){
$pdf->SetY(193);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($mventila10),1,0,'C');
}else{
  $pdf->SetY(193);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND  tipo = 'Modo ventilatorio' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $mventila11=$resp_r['cantidad'];
}
if(isset($mventila11)){
$pdf->SetY(193);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($mventila11),1,0,'C');
}else{
  $pdf->SetY(193);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND  tipo = 'Modo ventilatorio' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $mventila12=$resp_r['cantidad'];
}
if(isset($mventila12)){
$pdf->SetY(193);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($mventila12),1,0,'C');
}else{
  $pdf->SetY(193);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND  tipo = 'Modo ventilatorio' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $mventila13=$resp_r['cantidad'];
}
if(isset($mventila13)){
$pdf->SetY(193);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($mventila13),1,0,'C');
}else{
  $pdf->SetY(193);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND  tipo = 'Modo ventilatorio' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $mventila14=$resp_r['cantidad'];
}
if(isset($mventila14)){
$pdf->SetY(193);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($mventila14),1,0,'C');
}else{
  $pdf->SetY(193);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND  tipo = 'Modo ventilatorio' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $mventila15=$resp_r['cantidad'];
}
if(isset($mventila15)){
$pdf->SetY(193);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($mventila15),1,0,'C');
}else{
  $pdf->SetY(193);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND  tipo = 'Modo ventilatorio' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $mventila16=$resp_r['cantidad'];
}
if(isset($mventila16)){
$pdf->SetY(193);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($mventila16),1,0,'C');
}else{
  $pdf->SetY(193);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND  tipo = 'Modo ventilatorio' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $mventila17=$resp_r['cantidad'];
}
if(isset($mventila17)){
$pdf->SetY(193);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($mventila17),1,0,'C');
}else{
  $pdf->SetY(193);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND  tipo = 'Modo ventilatorio' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $mventila18=$resp_r['cantidad'];
}
if(isset($mventila18)){
$pdf->SetY(193);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($mventila18),1,0,'C');
}else{
  $pdf->SetY(193);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND  tipo = 'Modo ventilatorio' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $mventila19=$resp_r['cantidad'];
}
if(isset($mventila19)){
$pdf->SetY(193);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($mventila19),1,0,'C');
}else{
  $pdf->SetY(193);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND  tipo = 'Modo ventilatorio' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $mventila20=$resp_r['cantidad'];
}
if(isset($mventila20)){
$pdf->SetY(193);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($mventila20),1,0,'C');
}else{
  $pdf->SetY(193);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//inicio NOC glas inicio noc inicio noc glas inicio noc glas noc glas noc glas noc glas noc glas noc glas noc glas

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND  tipo = 'Modo ventilatorio' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $mventila21=$resp_r['cantidad'];
}
if(isset($mventila21)){
$pdf->SetY(193);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($mventila21),1,0,'C');
}else{
  $pdf->SetY(193);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND  tipo = 'Modo ventilatorio' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $mventila22=$resp_r['cantidad'];
}
if(isset($mventila22)){
$pdf->SetY(193);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($mventila22),1,0,'C');
}else{
  $pdf->SetY(193);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND  tipo = 'Modo ventilatorio' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $mventila23=$resp_r['cantidad'];
}
if(isset($mventila23)){
$pdf->SetY(193);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($mventila23),1,0,'C');
}else{
  $pdf->SetY(193);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND  tipo = 'Modo ventilatorio' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $mventila24=$resp_r['cantidad'];
}
if(isset($mventila24)){
$pdf->SetY(193);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($mventila24),1,0,'C');
}else{
  $pdf->SetY(193);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND  tipo = 'Modo ventilatorio' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $mventila01=$resp_r['cantidad'];
}
if(isset($mventila01)){
$pdf->SetY(193);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($mventila01),1,0,'C');
}else{
  $pdf->SetY(193);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND  tipo = 'Modo ventilatorio' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $mventila02=$resp_r['cantidad'];
}
if(isset($mventila02)){
$pdf->SetY(193);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($mventila02),1,0,'C');
}else{
  $pdf->SetY(193);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND  tipo = 'Modo ventilatorio' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $mventila03=$resp_r['cantidad'];
}
if(isset($mventila03)){
$pdf->SetY(193);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($mventila03),1,0,'C');
}else{
  $pdf->SetY(193);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND  tipo = 'Modo ventilatorio' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $mventila04=$resp_r['cantidad'];
}
if(isset($mventila04)){
$pdf->SetY(193);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($mventila04),1,0,'C');
}else{
  $pdf->SetY(193);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND  tipo = 'Modo ventilatorio' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $mventila05=$resp_r['cantidad'];
}
if(isset($mventila05)){
$pdf->SetY(193);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($mventila05),1,0,'C');
}else{
  $pdf->SetY(193);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND  tipo = 'Modo ventilatorio' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $mventila06=$resp_r['cantidad'];
}
if(isset($mventila06)){
$pdf->SetY(193);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($mventila06),1,0,'C');
}else{
  $pdf->SetY(193);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND  tipo = 'Modo ventilatorio' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $mventila07=$resp_r['cantidad'];
}
if(isset($mventila07)){
$pdf->SetY(193);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($mventila07),1,0,'C');
}else{
  $pdf->SetY(193);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//VOLUMEN CORRIENTE VOLUMEN CORRIENTE VOLUMEN CORRIENTE VOLUMEN CORRIENTE VOLUMEN CORRIENTE VOLUMEN CORRIENTE VOLUMEN CORRIENTE
// Volumen corriente / Volumen corriente / Volumen corriente / Volumen corriente / Volumen corriente / Volumen corriente

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND  tipo = 'Volumen corriente' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $volcorr=$resp_r['cantidad'];
}
if(isset($volcorr)){
$pdf->SetY(199);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($volcorr),1,0,'C');
}else{
  $pdf->SetY(199);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND  tipo = 'Volumen corriente' AND ter = 'Si'") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $volcorr9=$resp_r9['cantidad'];
}
if(isset($volcorr9)){
$pdf->SetY(199);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($volcorr9),1,0,'C');
}else{
  $pdf->SetY(199);
$pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND  tipo = 'Volumen corriente' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $volcorr10=$resp_r['cantidad'];
}
if(isset($volcorr10)){
$pdf->SetY(199);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($volcorr10),1,0,'C');
}else{
  $pdf->SetY(199);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND  tipo = 'Volumen corriente' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $volcorr11=$resp_r['cantidad'];
}
if(isset($volcorr11)){
$pdf->SetY(199);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($volcorr11),1,0,'C');
}else{
  $pdf->SetY(199);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND  tipo = 'Volumen corriente' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $volcorr12=$resp_r['cantidad'];
}
if(isset($volcorr12)){
$pdf->SetY(199);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($volcorr12),1,0,'C');
}else{
  $pdf->SetY(199);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND  tipo = 'Volumen corriente' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $volcorr13=$resp_r['cantidad'];
}
if(isset($volcorr13)){
$pdf->SetY(199);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($volcorr13),1,0,'C');
}else{
  $pdf->SetY(199);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND  tipo = 'Volumen corriente' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $volcorr14=$resp_r['cantidad'];
}
if(isset($volcorr14)){
$pdf->SetY(199);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($volcorr14),1,0,'C');
}else{
  $pdf->SetY(199);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND  tipo = 'Volumen corriente' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $volcorr15=$resp_r['cantidad'];
}
if(isset($volcorr15)){
$pdf->SetY(199);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($volcorr15),1,0,'C');
}else{
  $pdf->SetY(199);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND  tipo = 'Volumen corriente' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $volcorr16=$resp_r['cantidad'];
}
if(isset($volcorr16)){
$pdf->SetY(199);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($volcorr16),1,0,'C');
}else{
  $pdf->SetY(199);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND  tipo = 'Volumen corriente' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $volcorr17=$resp_r['cantidad'];
}
if(isset($volcorr17)){
$pdf->SetY(199);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($volcorr17),1,0,'C');
}else{
  $pdf->SetY(199);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND  tipo = 'Volumen corriente' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $volcorr18=$resp_r['cantidad'];
}
if(isset($volcorr18)){
$pdf->SetY(199);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($volcorr18),1,0,'C');
}else{
  $pdf->SetY(199);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND  tipo = 'Volumen corriente' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $volcorr19=$resp_r['cantidad'];
}
if(isset($volcorr19)){
$pdf->SetY(199);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($volcorr19),1,0,'C');
}else{
  $pdf->SetY(199);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND  tipo = 'Volumen corriente' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $volcorr20=$resp_r['cantidad'];
}
if(isset($volcorr20)){
$pdf->SetY(199);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($volcorr20),1,0,'C');
}else{
  $pdf->SetY(199);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//inicio NOC glas inicio noc inicio noc glas inicio noc glas noc glas noc glas noc glas noc glas noc glas noc glas

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND  tipo = 'Volumen corriente' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $volcorr21=$resp_r['cantidad'];
}
if(isset($volcorr21)){
$pdf->SetY(199);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($volcorr21),1,0,'C');
}else{
  $pdf->SetY(199);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND  tipo = 'Volumen corriente' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $volcorr22=$resp_r['cantidad'];
}
if(isset($volcorr22)){
$pdf->SetY(199);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($volcorr22),1,0,'C');
}else{
  $pdf->SetY(199);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND  tipo = 'Volumen corriente' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $volcorr23=$resp_r['cantidad'];
}
if(isset($volcorr23)){
$pdf->SetY(199);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($volcorr23),1,0,'C');
}else{
  $pdf->SetY(199);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND  tipo = 'Volumen corriente' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $volcorr24=$resp_r['cantidad'];
}
if(isset($volcorr24)){
$pdf->SetY(199);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($volcorr24),1,0,'C');
}else{
  $pdf->SetY(199);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND  tipo = 'Volumen corriente' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $volcorr01=$resp_r['cantidad'];
}
if(isset($volcorr01)){
$pdf->SetY(199);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($volcorr01),1,0,'C');
}else{
  $pdf->SetY(199);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND  tipo = 'Volumen corriente' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $volcorr02=$resp_r['cantidad'];
}
if(isset($volcorr02)){
$pdf->SetY(199);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($volcorr02),1,0,'C');
}else{
  $pdf->SetY(199);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND  tipo = 'Volumen corriente' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $volcorr03=$resp_r['cantidad'];
}
if(isset($volcorr03)){
$pdf->SetY(199);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($volcorr03),1,0,'C');
}else{
  $pdf->SetY(199);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND  tipo = 'Volumen corriente' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $volcorr04=$resp_r['cantidad'];
}
if(isset($volcorr04)){
$pdf->SetY(199);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($volcorr04),1,0,'C');
}else{
  $pdf->SetY(199);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND  tipo = 'Volumen corriente' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $volcorr05=$resp_r['cantidad'];
}
if(isset($volcorr05)){
$pdf->SetY(199);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($volcorr05),1,0,'C');
}else{
  $pdf->SetY(199);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND  tipo = 'Volumen corriente' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $volcorr06=$resp_r['cantidad'];
}
if(isset($volcorr06)){
$pdf->SetY(199);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($volcorr06),1,0,'C');
}else{
  $pdf->SetY(199);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND  tipo = 'Volumen corriente' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $volcorr07=$resp_r['cantidad'];
}
if(isset($volcorr07)){
$pdf->SetY(199);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($volcorr07),1,0,'C');
}else{
  $pdf->SetY(199);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//FRECUENCIA RESPIRATORIA FRECUENCIA RESPIRATORIA FRECUENCIA RESPIRATORIA FRECUENCIA RESPIRATORIA FRECUENCIA RESPIRATORIA
// Frecuencia respiratoria / Frecuencia respiratoria / Frecuencia respiratoria / Frecuencia respiratoria / Frecuencia respiratoria / Frecuencia respiratoria

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND  tipo = 'Frecuencia respiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvfres=$resp_r['cantidad'];
}
if(isset($pvfres)){
$pdf->SetY(205);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($pvfres),1,0,'C');
}else{
  $pdf->SetY(205);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND  tipo = 'Frecuencia respiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvfres9=$resp_r9['cantidad'];
}
if(isset($pvfres9)){
$pdf->SetY(205);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($pvfres9),1,0,'C');
}else{
  $pdf->SetY(205);
$pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND  tipo = 'Frecuencia respiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvfres10=$resp_r['cantidad'];
}
if(isset($pvfres10)){
$pdf->SetY(205);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($pvfres10),1,0,'C');
}else{
  $pdf->SetY(205);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND  tipo = 'Frecuencia respiratoria' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvfres11=$resp_r['cantidad'];
}
if(isset($pvfres11)){
$pdf->SetY(205);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($pvfres11),1,0,'C');
}else{
  $pdf->SetY(205);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND  tipo = 'Frecuencia respiratoria' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvfres12=$resp_r['cantidad'];
}
if(isset($pvfres12)){
$pdf->SetY(205);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($pvfres12),1,0,'C');
}else{
  $pdf->SetY(205);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND  tipo = 'Frecuencia respiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvfres13=$resp_r['cantidad'];
}
if(isset($pvfres13)){
$pdf->SetY(205);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($pvfres13),1,0,'C');
}else{
  $pdf->SetY(205);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND  tipo = 'Frecuencia respiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvfres14=$resp_r['cantidad'];
}
if(isset($pvfres14)){
$pdf->SetY(205);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($pvfres14),1,0,'C');
}else{
  $pdf->SetY(205);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND  tipo = 'Frecuencia respiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvfres15=$resp_r['cantidad'];
}
if(isset($pvfres15)){
$pdf->SetY(205);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($pvfres15),1,0,'C');
}else{
  $pdf->SetY(205);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND  tipo = 'Frecuencia respiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvfres16=$resp_r['cantidad'];
}
if(isset($pvfres16)){
$pdf->SetY(205);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($pvfres16),1,0,'C');
}else{
  $pdf->SetY(205);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND  tipo = 'Frecuencia respiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvfres17=$resp_r['cantidad'];
}
if(isset($pvfres17)){
$pdf->SetY(205);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($pvfres17),1,0,'C');
}else{
  $pdf->SetY(205);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND  tipo = 'Frecuencia respiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvfres18=$resp_r['cantidad'];
}
if(isset($pvfres18)){
$pdf->SetY(205);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($pvfres18),1,0,'C');
}else{
  $pdf->SetY(205);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND  tipo = 'Frecuencia respiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvfres19=$resp_r['cantidad'];
}
if(isset($pvfres19)){
$pdf->SetY(205);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($pvfres19),1,0,'C');
}else{
  $pdf->SetY(205);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND  tipo = 'Frecuencia respiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvfres20=$resp_r['cantidad'];
}
if(isset($pvfres20)){
$pdf->SetY(205);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($pvfres20),1,0,'C');
}else{
  $pdf->SetY(205);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//inicio NOC glas inicio noc inicio noc glas inicio noc glas noc glas noc glas noc glas noc glas noc glas noc glas

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND  tipo = 'Frecuencia respiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvfres21=$resp_r['cantidad'];
}
if(isset($pvfres21)){
$pdf->SetY(205);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($pvfres21),1,0,'C');
}else{
  $pdf->SetY(205);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND  tipo = 'Frecuencia respiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvfres22=$resp_r['cantidad'];
}
if(isset($pvfres22)){
$pdf->SetY(205);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($pvfres22),1,0,'C');
}else{
  $pdf->SetY(205);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND  tipo = 'Frecuencia respiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvfres23=$resp_r['cantidad'];
}
if(isset($pvfres23)){
$pdf->SetY(205);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($pvfres23),1,0,'C');
}else{
  $pdf->SetY(205);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND  tipo = 'Frecuencia respiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvfres24=$resp_r['cantidad'];
}
if(isset($pvfres24)){
$pdf->SetY(205);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($pvfres24),1,0,'C');
}else{
  $pdf->SetY(205);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND  tipo = 'Frecuencia respiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvfres01=$resp_r['cantidad'];
}
if(isset($pvfres01)){
$pdf->SetY(205);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($pvfres01),1,0,'C');
}else{
  $pdf->SetY(205);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND  tipo = 'Frecuencia respiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvfres02=$resp_r['cantidad'];
}
if(isset($pvfres02)){
$pdf->SetY(205);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($pvfres02),1,0,'C');
}else{
  $pdf->SetY(205);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND  tipo = 'Frecuencia respiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvfres03=$resp_r['cantidad'];
}
if(isset($pvfres03)){
$pdf->SetY(205);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($pvfres03),1,0,'C');
}else{
  $pdf->SetY(205);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND  tipo = 'Frecuencia respiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvfres04=$resp_r['cantidad'];
}
if(isset($pvfres04)){
$pdf->SetY(205);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($pvfres04),1,0,'C');
}else{
  $pdf->SetY(205);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND  tipo = 'Frecuencia respiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvfres05=$resp_r['cantidad'];
}
if(isset($pvfres05)){
$pdf->SetY(205);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($pvfres05),1,0,'C');
}else{
  $pdf->SetY(205);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND  tipo = 'Frecuencia respiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvfres06=$resp_r['cantidad'];
}
if(isset($pvfres06)){
$pdf->SetY(205);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($pvfres06),1,0,'C');
}else{
  $pdf->SetY(205);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND  tipo = 'Frecuencia respiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pvfres07=$resp_r['cantidad'];
}
if(isset($pvfres07)){
$pdf->SetY(205);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($pvfres07),1,0,'C');
}else{
  $pdf->SetY(205);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//FIO 2 FIO 2 FIO 2 FIO 2 FIO 2 FIO 2 FIO 2 FIO2 FIO2 FIO2 FIO2 FIO2 FIO2 FIO2 FIO2 FIO2 FIO2 FIO2 FIO2 FIO2 FIO2 FIO2 FIO2
// Fraccion inspirada de oxigeno / Fraccion inspirada de oxigeno / Fraccion inspirada de oxigeno / Fraccion inspirada de oxigeno / Fraccion inspirada de oxigeno / Fraccion inspirada de oxigeno

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND  tipo = 'Fraccion inspirada de oxigeno' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fio=$resp_r['cantidad'];
}
if(isset($fio)){
$pdf->SetY(211);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($fio),1,0,'C');
}else{
  $pdf->SetY(211);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND  tipo = 'Fraccion inspirada de oxigeno' AND ter = 'Si'") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fio9=$resp_r9['cantidad'];
}
if(isset($fio9)){
$pdf->SetY(211);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($fio9),1,0,'C');
}else{
  $pdf->SetY(211);
$pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND  tipo = 'Fraccion inspirada de oxigeno' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fio10=$resp_r['cantidad'];
}
if(isset($fio10)){
$pdf->SetY(211);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($fio10),1,0,'C');
}else{
  $pdf->SetY(211);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND  tipo = 'Fraccion inspirada de oxigeno' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fio11=$resp_r['cantidad'];
}
if(isset($fio11)){
$pdf->SetY(211);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($fio11),1,0,'C');
}else{
  $pdf->SetY(211);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND  tipo = 'Fraccion inspirada de oxigeno' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fio12=$resp_r['cantidad'];
}
if(isset($fio12)){
$pdf->SetY(211);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($fio12),1,0,'C');
}else{
  $pdf->SetY(211);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND  tipo = 'Fraccion inspirada de oxigeno' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fio13=$resp_r['cantidad'];
}
if(isset($fio13)){
$pdf->SetY(211);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($fio13),1,0,'C');
}else{
  $pdf->SetY(211);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND  tipo = 'Fraccion inspirada de oxigeno' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fio14=$resp_r['cantidad'];
}
if(isset($fio14)){
$pdf->SetY(211);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($fio14),1,0,'C');
}else{
  $pdf->SetY(211);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND  tipo = 'Fraccion inspirada de oxigeno' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fio15=$resp_r['cantidad'];
}
if(isset($fio15)){
$pdf->SetY(211);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($fio15),1,0,'C');
}else{
  $pdf->SetY(211);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND  tipo = 'Fraccion inspirada de oxigeno' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fio16=$resp_r['cantidad'];
}
if(isset($fio16)){
$pdf->SetY(211);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($fio16),1,0,'C');
}else{
  $pdf->SetY(211);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND  tipo = 'Fraccion inspirada de oxigeno' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fio17=$resp_r['cantidad'];
}
if(isset($fio17)){
$pdf->SetY(211);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($fio17),1,0,'C');
}else{
  $pdf->SetY(211);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND  tipo = 'Fraccion inspirada de oxigeno' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fio18=$resp_r['cantidad'];
}
if(isset($fio18)){
$pdf->SetY(211);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($fio18),1,0,'C');
}else{
  $pdf->SetY(211);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND  tipo = 'Fraccion inspirada de oxigeno' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fio19=$resp_r['cantidad'];
}
if(isset($fio19)){
$pdf->SetY(211);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($fio19),1,0,'C');
}else{
  $pdf->SetY(211);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND  tipo = 'Fraccion inspirada de oxigeno' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fio20=$resp_r['cantidad'];
}
if(isset($fio20)){
$pdf->SetY(211);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($fio20),1,0,'C');
}else{
  $pdf->SetY(211);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//inicio NOC glas inicio noc inicio noc glas inicio noc glas noc glas noc glas noc glas noc glas noc glas noc glas

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND  tipo = 'Fraccion inspirada de oxigeno' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fio21=$resp_r['cantidad'];
}
if(isset($fio21)){
$pdf->SetY(211);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($fio21),1,0,'C');
}else{
  $pdf->SetY(211);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND  tipo = 'Fraccion inspirada de oxigeno' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fio22=$resp_r['cantidad'];
}
if(isset($fio22)){
$pdf->SetY(211);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($fio22),1,0,'C');
}else{
  $pdf->SetY(211);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND  tipo = 'Fraccion inspirada de oxigeno' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fio23=$resp_r['cantidad'];
}
if(isset($fio23)){
$pdf->SetY(211);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($fio23),1,0,'C');
}else{
  $pdf->SetY(211);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND  tipo = 'Fraccion inspirada de oxigeno' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fio24=$resp_r['cantidad'];
}
if(isset($fio24)){
$pdf->SetY(211);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($fio24),1,0,'C');
}else{
  $pdf->SetY(211);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND  tipo = 'Fraccion inspirada de oxigeno' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fio01=$resp_r['cantidad'];
}
if(isset($fio01)){
$pdf->SetY(211);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($fio01),1,0,'C');
}else{
  $pdf->SetY(211);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND  tipo = 'Fraccion inspirada de oxigeno' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fio02=$resp_r['cantidad'];
}
if(isset($fio02)){
$pdf->SetY(211);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($fio02),1,0,'C');
}else{
  $pdf->SetY(211);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND  tipo = 'Fraccion inspirada de oxigeno' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fio03=$resp_r['cantidad'];
}
if(isset($fio03)){
$pdf->SetY(211);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($fio03),1,0,'C');
}else{
  $pdf->SetY(211);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND  tipo = 'Fraccion inspirada de oxigeno' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fio04=$resp_r['cantidad'];
}
if(isset($fio04)){
$pdf->SetY(211);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($fio04),1,0,'C');
}else{
  $pdf->SetY(211);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND  tipo = 'Fraccion inspirada de oxigeno' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fio05=$resp_r['cantidad'];
}
if(isset($fio05)){
$pdf->SetY(211);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($fio05),1,0,'C');
}else{
  $pdf->SetY(211);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND  tipo = 'Fraccion inspirada de oxigeno' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fio06=$resp_r['cantidad'];
}
if(isset($fio06)){
$pdf->SetY(211);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($fio06),1,0,'C');
}else{
  $pdf->SetY(211);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND  tipo = 'Fraccion inspirada de oxigeno' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fio07=$resp_r['cantidad'];
}
if(isset($fio07)){
$pdf->SetY(211);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($fio07),1,0,'C');
}else{
  $pdf->SetY(211);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//PEEP PEEP PEEP PEEP PEEP PEEP PEEP PEEP PEEP PEEP PEEP PEEP PEEP PEEP PEEP PEEP PEEP PEEP PEEP PEEP PEEP PEEP PEEP PEEP PEEP PEEP
// PEEP / PEEP / PEEP / PEEP / PEEP / PEEP

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND  tipo = 'PEEP' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $peep=$resp_r['cantidad'];
}
if(isset($peep)){
$pdf->SetY(217);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($peep),1,0,'C');
}else{
  $pdf->SetY(217);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND  tipo = 'PEEP' AND ter = 'Si'") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $peep9=$resp_r9['cantidad'];
}
if(isset($peep9)){
$pdf->SetY(217);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($peep9),1,0,'C');
}else{
  $pdf->SetY(217);
$pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND  tipo = 'PEEP' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $peep10=$resp_r['cantidad'];
}
if(isset($peep10)){
$pdf->SetY(217);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($peep10),1,0,'C');
}else{
  $pdf->SetY(217);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND  tipo = 'PEEP' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $peep11=$resp_r['cantidad'];
}
if(isset($peep11)){
$pdf->SetY(217);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($peep11),1,0,'C');
}else{
  $pdf->SetY(217);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND  tipo = 'PEEP' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $peep12=$resp_r['cantidad'];
}
if(isset($peep12)){
$pdf->SetY(217);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($peep12),1,0,'C');
}else{
  $pdf->SetY(217);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND  tipo = 'PEEP' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $peep13=$resp_r['cantidad'];
}
if(isset($peep13)){
$pdf->SetY(217);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($peep13),1,0,'C');
}else{
  $pdf->SetY(217);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND  tipo = 'PEEP' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $peep14=$resp_r['cantidad'];
}
if(isset($peep14)){
$pdf->SetY(217);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($peep14),1,0,'C');
}else{
  $pdf->SetY(217);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND  tipo = 'PEEP' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $peep15=$resp_r['cantidad'];
}
if(isset($peep15)){
$pdf->SetY(217);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($peep15),1,0,'C');
}else{
  $pdf->SetY(217);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND  tipo = 'PEEP' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $peep16=$resp_r['cantidad'];
}
if(isset($peep16)){
$pdf->SetY(217);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($peep16),1,0,'C');
}else{
  $pdf->SetY(217);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND  tipo = 'PEEP' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $peep17=$resp_r['cantidad'];
}
if(isset($peep17)){
$pdf->SetY(217);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($peep17),1,0,'C');
}else{
  $pdf->SetY(217);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND  tipo = 'PEEP' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $peep18=$resp_r['cantidad'];
}
if(isset($peep18)){
$pdf->SetY(217);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($peep18),1,0,'C');
}else{
  $pdf->SetY(217);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND  tipo = 'PEEP' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $peep19=$resp_r['cantidad'];
}
if(isset($peep19)){
$pdf->SetY(217);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($peep19),1,0,'C');
}else{
  $pdf->SetY(217);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND  tipo = 'PEEP' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $peep20=$resp_r['cantidad'];
}
if(isset($peep20)){
$pdf->SetY(217);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($peep20),1,0,'C');
}else{
  $pdf->SetY(217);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//inicio NOC glas inicio noc inicio noc glas inicio noc glas noc glas noc glas noc glas noc glas noc glas noc glas

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND  tipo = 'PEEP' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $peep21=$resp_r['cantidad'];
}
if(isset($peep21)){
$pdf->SetY(217);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($peep21),1,0,'C');
}else{
  $pdf->SetY(217);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND  tipo = 'PEEP' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $peep22=$resp_r['cantidad'];
}
if(isset($peep22)){
$pdf->SetY(217);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($peep22),1,0,'C');
}else{
  $pdf->SetY(217);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND  tipo = 'PEEP' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $peep23=$resp_r['cantidad'];
}
if(isset($peep23)){
$pdf->SetY(217);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($peep23),1,0,'C');
}else{
  $pdf->SetY(217);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND  tipo = 'PEEP' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $peep24=$resp_r['cantidad'];
}
if(isset($peep24)){
$pdf->SetY(217);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($peep24),1,0,'C');
}else{
  $pdf->SetY(217);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND  tipo = 'PEEP' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $peep01=$resp_r['cantidad'];
}
if(isset($peep01)){
$pdf->SetY(217);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($peep01),1,0,'C');
}else{
  $pdf->SetY(217);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND  tipo = 'PEEP' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $peep02=$resp_r['cantidad'];
}
if(isset($peep02)){
$pdf->SetY(217);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($peep02),1,0,'C');
}else{
  $pdf->SetY(217);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND  tipo = 'PEEP' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $peep03=$resp_r['cantidad'];
}
if(isset($peep03)){
$pdf->SetY(217);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($peep03),1,0,'C');
}else{
  $pdf->SetY(217);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND  tipo = 'PEEP' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $peep04=$resp_r['cantidad'];
}
if(isset($peep04)){
$pdf->SetY(217);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($peep04),1,0,'C');
}else{
  $pdf->SetY(217);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND  tipo = 'PEEP' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $peep05=$resp_r['cantidad'];
}
if(isset($peep05)){
$pdf->SetY(217);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($peep05),1,0,'C');
}else{
  $pdf->SetY(217);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND  tipo = 'PEEP' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $peep06=$resp_r['cantidad'];
}
if(isset($peep06)){
$pdf->SetY(217);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($peep06),1,0,'C');
}else{
  $pdf->SetY(217);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND  tipo = 'PEEP' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $peep07=$resp_r['cantidad'];
}
if(isset($peep07)){
$pdf->SetY(217);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($peep07),1,0,'C');
}else{
  $pdf->SetY(217);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//PRESION INSPIRATORIA PRESION INSPIRATORIA PRESION INSPIRATORIA PRESION INSPIRATORIA PRESION INSPIRATORIA PRESION INSPIRATORIA


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND  tipo = 'Presion inspiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pinsp=$resp_r['cantidad'];
}
if(isset($pinsp)){
$pdf->SetY(223);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($pinsp),1,0,'C');
}else{
  $pdf->SetY(223);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND  tipo = 'Presion inspiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pinsp9=$resp_r['cantidad'];
}
if(isset($pinsp9)){
 $pdf->SetY(223);
 $pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode($pinsp9),1,0,'C');
}else{
  $pdf->SetY(223);
  $pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND  tipo = 'Presion inspiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pinsp10=$resp_r['cantidad'];
}
if(isset($pinsp10)){
$pdf->SetY(223);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($pinsp10),1,0,'C');
}else{
  $pdf->SetY(223);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND  tipo = 'Presion inspiratoria' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pinsp11=$resp_r['cantidad'];
}
if(isset($pinsp11)){
$pdf->SetY(223);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($pinsp11),1,0,'C');
}else{
  $pdf->SetY(223);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND  tipo = 'Presion inspiratoria' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pinsp12=$resp_r['cantidad'];
}
if(isset($pinsp12)){
$pdf->SetY(223);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($pinsp12),1,0,'C');
}else{
  $pdf->SetY(223);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND  tipo = 'Presion inspiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pinsp13=$resp_r['cantidad'];
}
if(isset($pinsp13)){
$pdf->SetY(223);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($pinsp13),1,0,'C');
}else{
  $pdf->SetY(223);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND  tipo = 'Presion inspiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pinsp14=$resp_r['cantidad'];
}
if(isset($pinsp14)){
$pdf->SetY(223);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($pinsp14),1,0,'C');
}else{
  $pdf->SetY(223);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND  tipo = 'Presion inspiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pinsp15=$resp_r['cantidad'];
}
if(isset($pinsp15)){
$pdf->SetY(223);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($pinsp15),1,0,'C');
}else{
  $pdf->SetY(223);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND  tipo = 'Presion inspiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pinsp16=$resp_r['cantidad'];
}
if(isset($pinsp16)){
$pdf->SetY(223);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($pinsp16),1,0,'C');
}else{
  $pdf->SetY(223);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND  tipo = 'Presion inspiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pinsp17=$resp_r['cantidad'];
}
if(isset($pinsp17)){
$pdf->SetY(223);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($pinsp17),1,0,'C');
}else{
  $pdf->SetY(223);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND  tipo = 'Presion inspiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pinsp18=$resp_r['cantidad'];
}
if(isset($pinsp18)){
$pdf->SetY(223);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($pinsp18),1,0,'C');
}else{
  $pdf->SetY(223);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND  tipo = 'Presion inspiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pinsp19=$resp_r['cantidad'];
}
if(isset($pinsp19)){
$pdf->SetY(223);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($pinsp19),1,0,'C');
}else{
  $pdf->SetY(223);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND  tipo = 'Presion inspiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pinsp20=$resp_r['cantidad'];
}
if(isset($pinsp20)){
$pdf->SetY(223);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($pinsp20),1,0,'C');
}else{
  $pdf->SetY(223);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//inicio NOC glas inicio noc inicio noc glas inicio noc glas noc glas noc glas noc glas noc glas noc glas noc glas

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND  tipo = 'Presion inspiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pinsp21=$resp_r['cantidad'];
}
if(isset($pinsp21)){
$pdf->SetY(223);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($pinsp21),1,0,'C');
}else{
  $pdf->SetY(223);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND  tipo = 'Presion inspiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pinsp22=$resp_r['cantidad'];
}
if(isset($pinsp22)){
$pdf->SetY(223);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($pinsp22),1,0,'C');
}else{
  $pdf->SetY(223);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND  tipo = 'Presion inspiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pinsp23=$resp_r['cantidad'];
}
if(isset($pinsp23)){
$pdf->SetY(223);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($pinsp23),1,0,'C');
}else{
  $pdf->SetY(223);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND  tipo = 'Presion inspiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pinsp24=$resp_r['cantidad'];
}
if(isset($pinsp24)){
$pdf->SetY(223);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($pinsp24),1,0,'C');
}else{
  $pdf->SetY(223);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND  tipo = 'Presion inspiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pinsp01=$resp_r['cantidad'];
}
if(isset($pinsp01)){
$pdf->SetY(223);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($pinsp01),1,0,'C');
}else{
  $pdf->SetY(223);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND  tipo = 'Presion inspiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pinsp02=$resp_r['cantidad'];
}
if(isset($pinsp02)){
$pdf->SetY(223);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($pinsp02),1,0,'C');
}else{
  $pdf->SetY(223);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND  tipo = 'Presion inspiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pinsp03=$resp_r['cantidad'];
}
if(isset($pinsp03)){
$pdf->SetY(223);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($pinsp03),1,0,'C');
}else{
  $pdf->SetY(223);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND  tipo = 'Presion inspiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pinsp04=$resp_r['cantidad'];
}
if(isset($pinsp04)){
$pdf->SetY(223);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($pinsp04),1,0,'C');
}else{
  $pdf->SetY(223);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND  tipo = 'Presion inspiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pinsp05=$resp_r['cantidad'];
}
if(isset($pinsp05)){
$pdf->SetY(223);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($pinsp05),1,0,'C');
}else{
  $pdf->SetY(223);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND  tipo = 'Presion inspiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pinsp06=$resp_r['cantidad'];
}
if(isset($pinsp06)){
$pdf->SetY(223);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($pinsp06),1,0,'C');
}else{
  $pdf->SetY(223);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND  tipo = 'Presion inspiratoria' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pinsp07=$resp_r['cantidad'];
}
if(isset($pinsp07)){
$pdf->SetY(223);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($pinsp07),1,0,'C');
}else{
  $pdf->SetY(223);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//PRESION PICO PRESION PICO PRESION PICO PRESION PICO PRESION PICO PRESION PICO PRESION PICO PRESION PICO PRESION PICO PICO PICO

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='8' AND id_atencion=$id_atencion AND  tipo = 'Presion pico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppico=$resp_r['cantidad'];
}
if(isset($ppico)){
$pdf->SetY(229);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($ppico),1,0,'C');
}else{
  $pdf->SetY(229);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='9' AND id_atencion=$id_atencion AND  tipo = 'Presion pico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppico9=$resp_r9['cantidad'];
}
if(isset($ppico9)){
$pdf->SetY(229);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($ppico9),1,0,'C');
}else{
  $pdf->SetY(229);
$pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='10' AND id_atencion=$id_atencion AND  tipo = 'Presion pico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppico10=$resp_r['cantidad'];
}
if(isset($ppico10)){
$pdf->SetY(229);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($ppico10),1,0,'C');
}else{
  $pdf->SetY(229);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='11' AND id_atencion=$id_atencion AND  tipo = 'Presion pico' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppico11=$resp_r['cantidad'];
}
if(isset($ppico11)){
$pdf->SetY(229);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($ppico11),1,0,'C');
}else{
  $pdf->SetY(229);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='12' AND id_atencion=$id_atencion AND  tipo = 'Presion pico' AND ter = 'Si' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppico12=$resp_r['cantidad'];
}
if(isset($ppico12)){
$pdf->SetY(229);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($ppico12),1,0,'C');
}else{
  $pdf->SetY(229);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='13' AND id_atencion=$id_atencion AND  tipo = 'Presion pico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppico13=$resp_r['cantidad'];
}
if(isset($ppico13)){
$pdf->SetY(229);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($ppico13),1,0,'C');
}else{
  $pdf->SetY(229);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='14' AND id_atencion=$id_atencion AND  tipo = 'Presion pico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppico14=$resp_r['cantidad'];
}
if(isset($ppico14)){
$pdf->SetY(229);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($ppico14),1,0,'C');
}else{
  $pdf->SetY(229);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='15' AND id_atencion=$id_atencion AND  tipo = 'Presion pico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppico15=$resp_r['cantidad'];
}
if(isset($ppico15)){
$pdf->SetY(229);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($ppico15),1,0,'C');
}else{
  $pdf->SetY(229);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='16' AND id_atencion=$id_atencion AND  tipo = 'Presion pico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppico16=$resp_r['cantidad'];
}
if(isset($ppico16)){
$pdf->SetY(229);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($ppico16),1,0,'C');
}else{
  $pdf->SetY(229);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='17' AND id_atencion=$id_atencion AND  tipo = 'Presion pico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppico17=$resp_r['cantidad'];
}
if(isset($ppico17)){
$pdf->SetY(229);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($ppico17),1,0,'C');
}else{
  $pdf->SetY(229);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='18' AND id_atencion=$id_atencion AND  tipo = 'Presion pico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppico18=$resp_r['cantidad'];
}
if(isset($ppico18)){
$pdf->SetY(229);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($ppico18),1,0,'C');
}else{
  $pdf->SetY(229);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='19' AND id_atencion=$id_atencion AND  tipo = 'Presion pico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppico19=$resp_r['cantidad'];
}
if(isset($ppico19)){
$pdf->SetY(229);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($ppico19),1,0,'C');
}else{
  $pdf->SetY(229);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='20' AND id_atencion=$id_atencion AND  tipo = 'Presion pico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppico20=$resp_r['cantidad'];
}
if(isset($ppico20)){
$pdf->SetY(229);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($ppico20),1,0,'C');
}else{
  $pdf->SetY(229);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//inicio NOC glas inicio noc inicio noc glas inicio noc glas noc glas noc glas noc glas noc glas noc glas noc glas

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='21' AND id_atencion=$id_atencion AND  tipo = 'Presion pico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppico21=$resp_r['cantidad'];
}
if(isset($ppico21)){
$pdf->SetY(229);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($ppico21),1,0,'C');
}else{
  $pdf->SetY(229);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='22' AND id_atencion=$id_atencion AND  tipo = 'Presion pico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppico22=$resp_r['cantidad'];
}
if(isset($ppico22)){
$pdf->SetY(229);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($ppico22),1,0,'C');
}else{
  $pdf->SetY(229);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='23' AND id_atencion=$id_atencion AND  tipo = 'Presion pico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppico23=$resp_r['cantidad'];
}
if(isset($ppico23)){
$pdf->SetY(229);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($ppico23),1,0,'C');
}else{
  $pdf->SetY(229);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='24' AND id_atencion=$id_atencion AND  tipo = 'Presion pico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppico24=$resp_r['cantidad'];
}
if(isset($ppico24)){
$pdf->SetY(229);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($ppico24),1,0,'C');
}else{
  $pdf->SetY(229);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='1' AND id_atencion=$id_atencion AND  tipo = 'Presion pico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppico01=$resp_r['cantidad'];
}
if(isset($ppico01)){
$pdf->SetY(229);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($ppico01),1,0,'C');
}else{
  $pdf->SetY(229);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='2' AND id_atencion=$id_atencion AND  tipo = 'Presion pico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppico02=$resp_r['cantidad'];
}
if(isset($ppico02)){
$pdf->SetY(229);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($ppico02),1,0,'C');
}else{
  $pdf->SetY(229);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='3' AND id_atencion=$id_atencion AND  tipo = 'Presion pico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppico03=$resp_r['cantidad'];
}
if(isset($ppico03)){
$pdf->SetY(229);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($ppico03),1,0,'C');
}else{
  $pdf->SetY(229);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='4' AND id_atencion=$id_atencion AND  tipo = 'Presion pico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppico04=$resp_r['cantidad'];
}
if(isset($ppico04)){
$pdf->SetY(229);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($ppico04),1,0,'C');
}else{
  $pdf->SetY(229);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='5' AND id_atencion=$id_atencion AND  tipo = 'Presion pico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppico05=$resp_r['cantidad'];
}
if(isset($ppico05)){
$pdf->SetY(229);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($ppico05),1,0,'C');
}else{
  $pdf->SetY(229);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='6' AND id_atencion=$id_atencion AND  tipo = 'Presion pico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppico06=$resp_r['cantidad'];
}
if(isset($ppico06)){
$pdf->SetY(229);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($ppico06),1,0,'C');
}else{
  $pdf->SetY(229);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from monitoreo_sust where fecha='$fechar' and hora='7' AND id_atencion=$id_atencion AND  tipo = 'Presion pico' AND ter = 'Si'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ppico07=$resp_r['cantidad'];
}
if(isset($ppico07)){
$pdf->SetY(229);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($ppico07),1,0,'C');
}else{
  $pdf->SetY(229);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$pdf->Ln(90);


$pdf->Ln(36);



$pdf->Ln(-4);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(195, 6, utf8_decode('ESCALAS DE VALORACIÓN PARA CUIDADOS ESPECÍFICOS DE SEGURIDAD Y PROTECCIÓN'), 0, 0, 'C');

$pdf->SetFont('Arial', 'B', 7);
$pdf->Ln(5);
$pdf->Cell(30,4, utf8_decode('Concepto'),1,0,'C');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(7,4, utf8_decode('8'),1,0,'C');
$pdf->Cell(7,4, utf8_decode('9'),1,0,'C');
$pdf->Cell(7,4, utf8_decode('10'),1,0,'C');
$pdf->Cell(7,4, utf8_decode('11'),1,0,'C');
$pdf->Cell(7,4, utf8_decode('12'),1,0,'C');
$pdf->Cell(7,4, utf8_decode('13'),1,0,'C');
//ves
$pdf->Cell(7,4, utf8_decode('14'),1,0,'C');
$pdf->Cell(7,4, utf8_decode('15'),1,0,'C');
$pdf->Cell(7,4, utf8_decode('16'),1,0,'C');
$pdf->Cell(7,4, utf8_decode('17'),1,0,'C');
$pdf->Cell(7,4, utf8_decode('18'),1,0,'C');
$pdf->Cell(7,4, utf8_decode('19'),1,0,'C');
$pdf->Cell(7,4, utf8_decode('20'),1,0,'C');
//noc
$pdf->Cell(7,4, utf8_decode('21'),1,0,'C');
$pdf->Cell(7,4, utf8_decode('22'),1,0,'C');
$pdf->Cell(7,4, utf8_decode('23'),1,0,'C');
$pdf->Cell(7,4, utf8_decode('24'),1,0,'C');
$pdf->Cell(7,4, utf8_decode('1'),1,0,'C');
$pdf->Cell(7,4, utf8_decode('2'),1,0,'C');
$pdf->Cell(7,4, utf8_decode('3'),1,0,'C');
$pdf->Cell(7,4, utf8_decode('4'),1,0,'C');
$pdf->Cell(7,4, utf8_decode('5'),1,0,'C');
$pdf->Cell(7,4, utf8_decode('6'),1,0,'C');
$pdf->Cell(7,4, utf8_decode('7'),1,0,'C');



$pdf->SetFont('Arial', 'B', 6);
$pdf->SetY(51);
$pdf->SetX(10);
$pdf->Cell(5,6, utf8_decode('I'),1,0,'C');
$pdf->Cell(25,6, utf8_decode('Tipo de dieta'),1,0,'L');
$pdf->Ln(6);
$pdf->Cell(5,6, utf8_decode('N'),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Vía oral'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,3, ' ',0,'C');
$pdf->Cell(25,3, utf8_decode('Hemoderivados'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,6, utf8_decode('G'),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Solución Base'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,3, ' ',0,'C');
$pdf->Cell(25,3, utf8_decode('Cargas'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,6, utf8_decode('R'),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Infusiones 1'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,3, ' ',0,'C');
$pdf->Cell(25,3, utf8_decode('Infusiones 2'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,6, utf8_decode('E'),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Vía enteral'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,3, ' ',0,'C');
$pdf->Cell(25,3, utf8_decode('Vía parenteral'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,6, utf8_decode('S'),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Medicamentos'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,3, ' ',0,'C');
$pdf->Cell(25,3, utf8_decode('Aminas'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,6, utf8_decode('O'),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Infusiones 3'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,3, ' ',0,'C');
$pdf->Cell(25,3, utf8_decode('Infusiones 4'),1,0,'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(5,6, utf8_decode('S'),1,0,'C');
$pdf->Cell(25,6, utf8_decode('Ingreso parcial total'),1,0,'L');
$pdf->Ln(7);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(5,6, utf8_decode(''),1,0,'C');
$pdf->Cell(25,6, utf8_decode('Diurésis'),1,0,'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(5,6, utf8_decode('E'),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Vómito'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,6, ' ',0,'C');
$pdf->Cell(25,3, utf8_decode('Evacuaciones'),1,0,'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(5,6, utf8_decode('G'),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Sangrado'),1,0,'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 5.6);
$pdf->Cell(5,3, ' ',0,'C');
$pdf->Cell(25,3, utf8_decode('Pleurovac'),1,0,'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(5,6, utf8_decode('R'),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Sonda nasogastrica'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,3, ' ',0,'C');
$pdf->Cell(25,3, utf8_decode('Sonda T'),1,0,'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 5.6);
$pdf->Cell(5,6, utf8_decode('E'),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Biovac Izq'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,3, ' ',0,'C');
$pdf->Cell(25,3, utf8_decode('Biovac Der'),1,0,'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(5,6, utf8_decode('S'),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Penrose Izq'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,3, ' ',0,'C');
$pdf->Cell(25,3, utf8_decode('Penrose Der'),1,0,'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(5,6, utf8_decode('O'),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Drenovac'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,3, ' ',0,'C');
$pdf->Cell(25,3, utf8_decode('Colostomia'),1,0,'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(5,6, utf8_decode('S'),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Ileostomia'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,3, ' ',0,'C');
$pdf->Cell(25,3, utf8_decode('Saratoga'),1,0,'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(5,6, utf8_decode(''),1,0,'C');
$pdf->Cell(25,6, utf8_decode('Egreso parcial total'),1,0,'L');

$pdf->Ln(7);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(30,6, utf8_decode('Balance total'),1,0,'C');

$pdf->SetY(51);
$pdf->SetX(40);
$sat = $conexion->query("select * from enf_ter where fecha_m='$fechar' and turno='MATUTINO' AND id_atencion=$id_atencion ORDER by diet_m DESC LIMIT 1") or die($conexion->error);
while ($sat_r = $sat->fetch_assoc()) {
 
}
 $pdf->SetFont('Arial', '', 6);
$pdf->Cell(49,6, utf8_decode('MATUTINO: '.$sat_r['diet_m']),1,0,'C');

$pdf->SetY(51);
$pdf->SetX(89);
$sat = $conexion->query("select * from enf_ter where fecha_m='$fechar' and turno='VESPERTINO' AND id_atencion=$id_atencion ORDER by diet_m DESC LIMIT 1") or die($conexion->error);
while ($sat_r = $sat->fetch_assoc()) {

}
$pdf->SetFont('Arial', '', 6);

$pdf->Cell(42,6, utf8_decode('VESPERTINO: '.$sat_r['diet_m']),1,0,'C');

$pdf->SetY(51);
$pdf->SetX(131);
$sat = $conexion->query("select * from enf_ter where fecha_m='$fechar' and turno='NOCTURNO' AND id_atencion=$id_atencion ORDER by diet_m DESC LIMIT 1") or die($conexion->error);
while ($sat_r = $sat->fetch_assoc()) {

}
  $pdf->SetFont('Arial', '', 6);
$pdf->Cell(77,6, utf8_decode('NOCTURNO: '.$sat_r['diet_m']),1,0,'C');

//VIA ORAL VIA ORAL VIA ORAL VIA ORAL VIA ORAL VIA ORAL VIA ORAL VIA ORAL VIA ORAL VIA ORAL


$resp8 = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='8' and id_atencion=$id_atencion and des='VIA ORAL'") or die($conexion->error);
while ($resp_r8 = $resp8->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vor8=$resp_r8['cantidad'];
}
if(isset($vor8)){
$pdf->SetY(57);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($vor8),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='9' and id_atencion=$id_atencion and des='VIA ORAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vor9=$resp_r['cantidad'];
}
if(isset($vor9)){
$pdf->SetY(57);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($vor9),1,0,'C');
}else{
  $pdf->SetY(57);
  $pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='10' and id_atencion=$id_atencion and des='VIA ORAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vor10=$resp_r['cantidad'];
}
if(isset($vor10)){
$pdf->SetY(57);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($vor10),1,0,'C');
}else{
  $pdf->SetY(57);
  $pdf->SetX(54);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='11' and id_atencion=$id_atencion and des='VIA ORAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vor11=$resp_r['cantidad'];
}
if(isset($vor11)){
$pdf->SetY(57);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($vor11),1,0,'C');
}else{
  $pdf->SetY(57);
  $pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='12' and id_atencion=$id_atencion and des='VIA ORAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vor12=$resp_r['cantidad'];
}
if(isset($vor12)){
$pdf->SetY(57);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($vor12),1,0,'C');
}else{
  $pdf->SetY(57);
  $pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='13' and id_atencion=$id_atencion and des='VIA ORAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vor13=$resp_r['cantidad'];
}
if(isset($vor13)){
$pdf->SetY(57);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($vor13),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(75);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='14' and id_atencion=$id_atencion and des='VIA ORAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vor14=$resp_r['cantidad'];
}
if(isset($vor14)){
$pdf->SetY(57);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($vor14),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(82);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='15' and id_atencion=$id_atencion and des='VIA ORAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vor15=$resp_r['cantidad'];
}
if(isset($vor15)){
$pdf->SetY(57);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($vor15),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(89);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='16' and id_atencion=$id_atencion and des='VIA ORAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vor16=$resp_r['cantidad'];
}
if(isset($vor16)){
$pdf->SetY(57);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($vor16),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(96);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='17' and id_atencion=$id_atencion and des='VIA ORAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vor17=$resp_r['cantidad'];
}
if(isset($vor17)){
$pdf->SetY(57);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($vor17),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='18' and id_atencion=$id_atencion and des='VIA ORAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vor18=$resp_r['cantidad'];
}
if(isset($vor18)){
$pdf->SetY(57);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($vor18),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='19' and id_atencion=$id_atencion and des='VIA ORAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vor19=$resp_r['cantidad'];
}
if(isset($vor19)){
$pdf->SetY(57);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($vor19),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='20' and id_atencion=$id_atencion and des='VIA ORAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vor20=$resp_r['cantidad'];
}
if(isset($vor20)){
$pdf->SetY(57);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($vor20),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='21' and id_atencion=$id_atencion and des='VIA ORAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vor21=$resp_r['cantidad'];
}
if(isset($vor21)){
$pdf->SetY(57);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($vor21),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='22' and id_atencion=$id_atencion and des='VIA ORAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vor22=$resp_r['cantidad'];
}
if(isset($vor22)){
$pdf->SetY(57);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($vor22),1,0,'C');
}else{
  $pdf->SetY(57);
  $pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_ter  where fecha='$fechar' and hora='23' and id_atencion=$id_atencion and des='VIA ORAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vor23=$resp_r['cantidad'];
}
if(isset($vor23)){
$pdf->SetY(57);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($vor23),1,0,'C');
}else{
  $pdf->SetY(57);
  $pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='24' and id_atencion=$id_atencion and des='VIA ORAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vor24=$resp_r['cantidad'];
}
if(isset($vor24)){
$pdf->SetY(57);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($vor24),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='1' and id_atencion=$id_atencion and des='VIA ORAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vor01=$resp_r['cantidad'];
}
if(isset($vor01)){
$pdf->SetY(57);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($vor01),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(159);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='2' and id_atencion=$id_atencion and des='VIA ORAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vor02=$resp_r['cantidad'];
}
if(isset($vor02)){
$pdf->SetY(57);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($vor02),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='3' and id_atencion=$id_atencion and des='VIA ORAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vor03=$resp_r['cantidad'];
}
if(isset($vor03)){
$pdf->SetY(57);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($vor03),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='4' and id_atencion=$id_atencion and des='VIA ORAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vor04=$resp_r['cantidad'];
}
if(isset($vor04)){
$pdf->SetY(57);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($vor04),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(180);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='5' and id_atencion=$id_atencion and des='VIA ORAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vor05=$resp_r['cantidad'];
}
if(isset($vor05)){
$pdf->SetY(57);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($vor05),1,0,'C');
}else{
  $pdf->SetY(57);
  $pdf->SetX(187);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='6' and id_atencion=$id_atencion and des='VIA ORAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vor06=$resp_r['cantidad'];
}
if(isset($vor06)){
$pdf->SetY(57);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($vor06),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='7' and id_atencion=$id_atencion and des='VIA ORAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $s7=$resp_r['cantidad'];
}
if(isset($vor07)){
$pdf->SetY(57);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($s7),1,0,'C');
}else{
  $pdf->SetY(57);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//SOLUCION BASE     SOLUCION BASE   SOLUCION BASE   SOLUCION BASE   SOLUCION BASE   SOLUCION BASE

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='8' and id_atencion=$id_atencion and des='SOLUCION BASE'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $s8=$resp_r['cantidad'];
}
if(isset($s8)){
$pdf->SetY(63);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($s8),1,0,'C');
}else{
  $pdf->SetY(63);
  $pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='9' and id_atencion=$id_atencion and des='SOLUCION BASE'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $s9=$resp_r['cantidad'];
}
if(isset($s9)){
$pdf->SetY(63);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($s9),1,0,'C');
}else{
  $pdf->SetY(63);
  $pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='10' and id_atencion=$id_atencion and des='SOLUCION BASE'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $s10=$resp_r['cantidad'];
}
if(isset($s10)){
$pdf->SetY(63);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($s10),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(54);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='11' and id_atencion=$id_atencion and des='SOLUCION BASE' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $s11=$resp_r['cantidad'];
}
if(isset($s11)){
$pdf->SetY(63);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($s11),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='12' and id_atencion=$id_atencion and des='SOLUCION BASE' ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $s12=$resp_r['cantidad'];
}
if(isset($s12)){
$pdf->SetY(63);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($s12),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(68);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='13' and id_atencion=$id_atencion and des='SOLUCION BASE'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $s13=$resp_r['cantidad'];
}
if(isset($s13)){
$pdf->SetY(63);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($s13),1,0,'C');
}else{
  $pdf->SetY(63);
  $pdf->SetX(75);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='14' and id_atencion=$id_atencion and des='SOLUCION BASE'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $s14=$resp_r['cantidad'];
}
if(isset($s14)){
$pdf->SetY(63);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($s14),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(82);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='15' and id_atencion=$id_atencion and des='SOLUCION BASE'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $s15=$resp_r['cantidad'];
}
if(isset($s15)){
$pdf->SetY(63);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($s15),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(89);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='16' and id_atencion=$id_atencion and des='SOLUCION BASE'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $s16=$resp_r['cantidad'];
}
if(isset($s16)){
$pdf->SetY(63);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($s16),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(96);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='17' and id_atencion=$id_atencion and des='SOLUCION BASE'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $s17=$resp_r['cantidad'];
}
if(isset($s17)){
$pdf->SetY(63);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($s17),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='18' and id_atencion=$id_atencion and des='SOLUCION BASE'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $s18=$resp_r['cantidad'];
}
if(isset($s18)){
$pdf->SetY(63);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($s18),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='19' and id_atencion=$id_atencion and des='SOLUCION BASE'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $s19=$resp_r['cantidad'];
}
if(isset($s19)){
$pdf->SetY(63);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($s19),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='20' and id_atencion=$id_atencion and des='SOLUCION BASE'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $s20=$resp_r['cantidad'];
}
if(isset($s20)){
$pdf->SetY(63);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($s20),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='21' and id_atencion=$id_atencion and des='SOLUCION BASE'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $s21=$resp_r['cantidad'];
}
if(isset($s21)){
$pdf->SetY(63);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($s21),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='22' and id_atencion=$id_atencion and des='SOLUCION BASE'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $s22=$resp_r['cantidad'];
}
if(isset($s22)){
$pdf->SetY(63);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($s22),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='23' and id_atencion=$id_atencion and des='SOLUCION BASE'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $s23=$resp_r['cantidad'];
}
if(isset($s23)){
$pdf->SetY(63);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($s23),1,0,'C');
}else{
  $pdf->SetY(63);
  $pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='24' and id_atencion=$id_atencion and des='SOLUCION BASE'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $s24=$resp_r['cantidad'];
}
if(isset($s24)){
$pdf->SetY(63);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($s24),1,0,'C');
}else{
  $pdf->SetY(63);
  $pdf->SetX(152);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='1' and id_atencion=$id_atencion and des='SOLUCION BASE'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $s01=$resp_r['cantidad'];
}
if(isset($s01)){
$pdf->SetY(63);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($s01),1,0,'C');
}else{
  $pdf->SetY(63);
  $pdf->SetX(159);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='2' and id_atencion=$id_atencion and des='SOLUCION BASE'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $s02=$resp_r['cantidad'];
}
if(isset($s02)){
$pdf->SetY(63);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($s02),1,0,'C');
}else{
  $pdf->SetY(63);
  $pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='3' and id_atencion=$id_atencion and des='SOLUCION BASE'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $s03=$resp_r['cantidad'];
}
if(isset($s03)){
$pdf->SetY(63);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($s03),1,0,'C');
}else{
  $pdf->SetY(63);
  $pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='4' and id_atencion=$id_atencion and des='SOLUCION BASE'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $s04=$resp_r['cantidad'];
}
if(isset($s04)){
$pdf->SetY(63);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($s04),1,0,'C');
}else{
  $pdf->SetY(63);
  $pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='5' and id_atencion=$id_atencion and des='SOLUCION BASE'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $s05=$resp_r['cantidad'];
}
if(isset($s05)){
$pdf->SetY(63);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($s05),1,0,'C');
}else{
  $pdf->SetY(63);
  $pdf->SetX(187);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='6' and id_atencion=$id_atencion and des='SOLUCION BASE'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $s06=$resp_r['cantidad'];
}
if(isset($s06)){
$pdf->SetY(63);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($s06),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='7' and id_atencion=$id_atencion and des='SOLUCION BASE'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $s07=$resp_r['cantidad'];
}
if(isset($s07)){
$pdf->SetY(63);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($s07),1,0,'C');
}else{
  $pdf->SetY(63);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


//CARGAS CARGAS CARGAS CARGAS CARGAS CARGAS CARGAS POR HORAS

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='8' and id_atencion=$id_atencion and des='CARGAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $car8=$resp_r['cantidad'];
}
if(isset($car8)){
$pdf->SetY(66);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($car8),1,0,'C');
}else{
  $pdf->SetY(66);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='9' and id_atencion=$id_atencion and des='CARGAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $car9=$resp_r['cantidad'];
}
if(isset($car9)){
$pdf->SetY(66);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($car9),1,0,'C');
}else{
  $pdf->SetY(66);
$pdf->SetX(47);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='10' and id_atencion=$id_atencion and des='CARGAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $car10=$resp_r['cantidad'];
}
if(isset($car10)){
$pdf->SetY(66);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($car10),1,0,'C');
}else{
  $pdf->SetY(66);
$pdf->SetX(54);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='11' and id_atencion=$id_atencion and des='CARGAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $car11=$resp_r['cantidad'];
}
if(isset($car11)){
$pdf->SetY(66);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($car11),1,0,'C');
}else{
  $pdf->SetY(66);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='12' and id_atencion=$id_atencion and des='CARGAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $car12=$resp_r['cantidad'];
}
if(isset($car12)){
$pdf->SetY(66);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($car12),1,0,'C');
}else{
  $pdf->SetY(66);
$pdf->SetX(68);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='13' and id_atencion=$id_atencion and des='CARGAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $car13=$resp_r['cantidad'];
}
if(isset($car13)){
$pdf->SetY(66);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($car13),1,0,'C');
}else{
  $pdf->SetY(66);
$pdf->SetX(75);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='14' and id_atencion=$id_atencion and des='CARGAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $car14=$resp_r['cantidad'];
}
if(isset($car14)){
$pdf->SetY(66);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($car14),1,0,'C');
}else{
  $pdf->SetY(66);
$pdf->SetX(82);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='15' and id_atencion=$id_atencion and des='CARGAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $car15=$resp_r['cantidad'];
}
if(isset($car15)){
$pdf->SetY(66);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($car15),1,0,'C');
}else{
  $pdf->SetY(66);
$pdf->SetX(89);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='16' and id_atencion=$id_atencion and des='CARGAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $car16=$resp_r['cantidad'];
}
if(isset($car16)){
$pdf->SetY(66);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($car16),1,0,'C');
}else{
  $pdf->SetY(66);
$pdf->SetX(96);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='17' and id_atencion=$id_atencion and des='CARGAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $car17=$resp_r['cantidad'];
}
if(isset($car17)){
$pdf->SetY(66);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($car17),1,0,'C');
}else{
  $pdf->SetY(66);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='18' and id_atencion=$id_atencion and des='CARGAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $car18=$resp_r['cantidad'];
}
if(isset($car18)){
$pdf->SetY(66);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($car18),1,0,'C');
}else{
  $pdf->SetY(66);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='19' and id_atencion=$id_atencion and des='CARGAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $car19=$resp_r['cantidad'];
}
if(isset($car19)){
$pdf->SetY(66);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($car19),1,0,'C');
}else{
  $pdf->SetY(66);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='20' and id_atencion=$id_atencion and des='CARGAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $car20=$resp_r['cantidad'];
}
if(isset($car20)){
$pdf->SetY(66);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($car20),1,0,'C');
}else{
  $pdf->SetY(66);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='21' and id_atencion=$id_atencion and des='CARGAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $car21=$resp_r['cantidad'];
}
if(isset($car21)){
$pdf->SetY(66);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($car21),1,0,'C');
}else{
  $pdf->SetY(66);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='22' and id_atencion=$id_atencion and des='CARGAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $car22=$resp_r['cantidad'];
}
if(isset($car22)){
$pdf->SetY(66);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($car22),1,0,'C');
}else{
  $pdf->SetY(66);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='23' and id_atencion=$id_atencion and des='CARGAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $car23=$resp_r['cantidad'];
}
if(isset($car23)){
$pdf->SetY(66);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($car23),1,0,'C');
}else{
  $pdf->SetY(66);
$pdf->SetX(145);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='24' and id_atencion=$id_atencion and des='CARGAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $car24=$resp_r['cantidad'];
}
if(isset($car24)){
$pdf->SetY(66);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($car24),1,0,'C');
}else{
  $pdf->SetY(66);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='1' and id_atencion=$id_atencion and des='CARGAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $car01=$resp_r['cantidad'];
}
if(isset($car01)){
$pdf->SetY(66);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($car01),1,0,'C');
}else{
  $pdf->SetY(66);
$pdf->SetX(159);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='2' and id_atencion=$id_atencion and des='CARGAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $car02=$resp_r['cantidad'];
}
if(isset($car02)){
$pdf->SetY(66);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($car02),1,0,'C');
}else{
  $pdf->SetY(66);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='3' and id_atencion=$id_atencion and des='CARGAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $car03=$resp_r['cantidad'];
}
if(isset($car03)){
$pdf->SetY(66);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($car03),1,0,'C');
}else{
  $pdf->SetY(66);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='4' and id_atencion=$id_atencion and des='CARGAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $car04=$resp_r['cantidad'];
}
if(isset($car04)){
$pdf->SetY(66);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($car04),1,0,'C');
}else{
  $pdf->SetY(66);
$pdf->SetX(180);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='5' and id_atencion=$id_atencion and des='CARGAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $car05=$resp_r['cantidad'];
}
if(isset($car05)){
$pdf->SetY(66);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($car05),1,0,'C');
}else{
  $pdf->SetY(66);
$pdf->SetX(187);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='6' and id_atencion=$id_atencion and des='CARGAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $car06=$resp_r['cantidad'];
}
if(isset($car06)){
$pdf->SetY(66);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($car06),1,0,'C');
}else{
  $pdf->SetY(66);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='7' and id_atencion=$id_atencion and des='CARGAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $car07=$resp_r['cantidad'];
}
if(isset($car07)){
$pdf->SetY(66);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($car07),1,0,'C');
}else{
  $pdf->SetY(66);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


//HEMODERIVADOS HEMO DERIVADOS HEMODERIVADOS HEMODERIVADOS HEMODERIVADOS HEMODERIVADOS HEMODERIVAOD

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='8' and id_atencion=$id_atencion and des='HEMODERIVADOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $h8=$resp_r['cantidad'];
}
if(isset($h8)){
$pdf->SetY(60);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($h8),1,0,'C');
}else{
  $pdf->SetY(60);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='9' and id_atencion=$id_atencion and des='HEMODERIVADOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $h9=$resp_r['hemo_m'];
}
if(isset($h9)){
$pdf->SetY(60);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($h9),1,0,'C');
}else{
  $pdf->SetY(60);
$pdf->SetX(47);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='10' and id_atencion=$id_atencion and des='HEMODERIVADOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $h10=$resp_r['cantidad'];
}
if(isset($h10)){
$pdf->SetY(60);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($h10),1,0,'C');
}else{
  $pdf->SetY(60);
$pdf->SetX(54);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='11' and id_atencion=$id_atencion and des='HEMODERIVADOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $h11=$resp_r['cantidad'];
}
if(isset($h11)){
$pdf->SetY(60);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($h11),1,0,'C');
}else{
  $pdf->SetY(60);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='12' and id_atencion=$id_atencion and des='HEMODERIVADOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $h12=$resp_r['cantidad'];
}
if(isset($h12)){
$pdf->SetY(60);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($h12),1,0,'C');
}else{
  $pdf->SetY(60);
$pdf->SetX(68);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='13' and id_atencion=$id_atencion and des='HEMODERIVADOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $h13=$resp_r['cantidad'];
}
if(isset($h13)){
$pdf->SetY(60);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($h13),1,0,'C');
}else{
  $pdf->SetY(60);
$pdf->SetX(75);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='14' and id_atencion=$id_atencion and des='HEMODERIVADOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $h14=$resp_r['cantidad'];
}
if(isset($h14)){
$pdf->SetY(60);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($h14),1,0,'C');
}else{
  $pdf->SetY(60);
$pdf->SetX(82);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  ing_enf_ter where fecha='$fechar' and hora='15' and id_atencion=$id_atencion and des='HEMODERIVADOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $h15=$resp_r['cantidad'];
}
if(isset($h15)){
$pdf->SetY(60);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($h15),1,0,'C');
}else{
  $pdf->SetY(60);
$pdf->SetX(89);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  ing_enf_ter where fecha='$fechar' and hora='16' and id_atencion=$id_atencion and des='HEMODERIVADOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $h16=$resp_r['cantidad'];
}
if(isset($h16)){
$pdf->SetY(60);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($h16),1,0,'C');
}else{
  $pdf->SetY(60);
$pdf->SetX(96);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  ing_enf_ter where fecha='$fechar' and hora='17' and id_atencion=$id_atencion and des='HEMODERIVADOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $h17=$resp_r['cantidad'];
}
if(isset($h17)){
$pdf->SetY(60);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($h17),1,0,'C');
}else{
  $pdf->SetY(60);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  ing_enf_ter where fecha='$fechar' and hora='18' and id_atencion=$id_atencion and des='HEMODERIVADOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $h18=$resp_r['cantidad'];
}
if(isset($h18)){
$pdf->SetY(60);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($h18),1,0,'C');
}else{
  $pdf->SetY(60);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  ing_enf_ter where fecha='$fechar' and hora='19' and id_atencion=$id_atencion and des='HEMODERIVADOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $h19=$resp_r['cantidad'];
}
if(isset($h19)){
$pdf->SetY(60);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($h19),1,0,'C');
}else{
  $pdf->SetY(60);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  ing_enf_ter where fecha='$fechar' and hora='20' and id_atencion=$id_atencion and des='HEMODERIVADOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $h20=$resp_r['cantidad'];
}
if(isset($h20)){
$pdf->SetY(60);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($h20),1,0,'C');
}else{
  $pdf->SetY(60);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  ing_enf_ter where fecha='$fechar' and hora='21' and id_atencion=$id_atencion and des='HEMODERIVADOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $h21=$resp_r['cantidad'];
}
if(isset($h21)){
$pdf->SetY(60);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($h21),1,0,'C');
}else{
  $pdf->SetY(60);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='22' and id_atencion=$id_atencion and des='HEMODERIVADOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $h22=$resp_r['cantidad'];
}
if(isset($h22)){
$pdf->SetY(60);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($h22),1,0,'C');
}else{
  $pdf->SetY(60);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='23' and id_atencion=$id_atencion and des='HEMODERIVADOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $h23=$resp_r['cantidad'];
}
if(isset($h23)){
$pdf->SetY(60);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($h23),1,0,'C');
}else{
  $pdf->SetY(60);
$pdf->SetX(145);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  ing_enf_ter where fecha='$fechar' and hora='24' and id_atencion=$id_atencion and des='HEMODERIVADOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $h24=$resp_r['cantidad'];
}
if(isset($h24)){
$pdf->SetY(60);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($h24),1,0,'C');
}else{
  $pdf->SetY(60);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  ing_enf_ter where fecha='$fechar' and hora='1' and id_atencion=$id_atencion and des='HEMODERIVADOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $h01=$resp_r['cantidad'];
}
if(isset($h01)){
$pdf->SetY(60);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($h01),1,0,'C');
}else{
  $pdf->SetY(60);
$pdf->SetX(159);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  ing_enf_ter where fecha='$fechar' and hora='2' and id_atencion=$id_atencion and des='HEMODERIVADOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $h02=$resp_r['cantidad'];
}
if(isset($h02)){
$pdf->SetY(60);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($h02),1,0,'C');
}else{
  $pdf->SetY(60);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  ing_enf_ter where fecha='$fechar' and hora='3' and id_atencion=$id_atencion and des='HEMODERIVADOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g03=$resp_r['cantidad'];
}
if(isset($h03)){
$pdf->SetY(60);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($h03),1,0,'C');
}else{
  $pdf->SetY(60);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  ing_enf_ter where fecha='$fechar' and hora='4' and id_atencion=$id_atencion and des='HEMODERIVADOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $h04=$resp_r['cantidad'];
}
if(isset($h04)){
$pdf->SetY(60);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($h04),1,0,'C');
}else{
  $pdf->SetY(60);
$pdf->SetX(180);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  ing_enf_ter where fecha='$fechar' and hora='5' and id_atencion=$id_atencion and des='HEMODERIVADOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $h05=$resp_r['cantidad'];
}
if(isset($h05)){
$pdf->SetY(60);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($h05),1,0,'C');
}else{
  $pdf->SetY(60);
$pdf->SetX(187);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from  ing_enf_ter where fecha='$fechar' and hora='6' and id_atencion=$id_atencion and des='HEMODERIVADOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $h06=$resp_r['cantidad'];
}
if(isset($h06)){
$pdf->SetY(60);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($h06),1,0,'C');
}else{
  $pdf->SetY(60);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from  ing_enf_ter where fecha='$fechar' and hora='7' and id_atencion=$id_atencion and des='HEMODERIVADOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $h07=$resp_r['cantidad'];
}
if(isset($h07)){
$pdf->SetY(60);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($h07),1,0,'C');
}else{
  $pdf->SetY(60);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


//INFUSIONES 1 INFUSIONES 1 INFUSIONES 1 INFUSIONES 1 INFUSIONES 1 INFUSIONES 1 INFUSIONES 1 POR HORAS

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='8' and id_atencion=$id_atencion and des='INFUSIONES 1'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu18=$resp_r['cantidad'];
}
if(isset($infu18)){
$pdf->SetY(69);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($infu18),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='9' and id_atencion=$id_atencion and des='INFUSIONES 1'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu19=$resp_r['cantidad'];
}
if(isset($infu19)){
$pdf->SetY(69);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($infu19),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(47);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='10' and id_atencion=$id_atencion and des='INFUSIONES 1'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu110=$resp_r['cantidad'];
}
if(isset($infu110)){
$pdf->SetY(69);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($infu110),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(54);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='11' and id_atencion=$id_atencion and des='INFUSIONES 1'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu111=$resp_r['cantidad'];
}
if(isset($infu111)){
$pdf->SetY(69);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($infu111),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='12' and id_atencion=$id_atencion and des='INFUSIONES 1'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu112=$resp_r['cantidad'];
}
if(isset($infu112)){
$pdf->SetY(69);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($infu112),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(68);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='13' and id_atencion=$id_atencion and des='INFUSIONES 1'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu113=$resp_r['cantidad'];
}
if(isset($infu113)){
$pdf->SetY(69);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($infu113),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(75);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='14' and id_atencion=$id_atencion and des='INFUSIONES 1'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu114=$resp_r['cantidad'];
}
if(isset($infu114)){
$pdf->SetY(69);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($infu114),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(82);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='15' and id_atencion=$id_atencion and des='INFUSIONES 1'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu115=$resp_r['cantidad'];
}
if(isset($infu115)){
$pdf->SetY(69);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($infu115),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(89);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='16' and id_atencion=$id_atencion and des='INFUSIONES 1'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu116=$resp_r['cantidad'];
}
if(isset($infu116)){
$pdf->SetY(69);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($infu116),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(96);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='17' and id_atencion=$id_atencion and des='INFUSIONES 1'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu117=$resp_r['cantidad'];
}
if(isset($infu117)){
$pdf->SetY(69);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($infu117),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='18' and id_atencion=$id_atencion and des='INFUSIONES 1'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu118=$resp_r['cantidad'];
}
if(isset($infu118)){
$pdf->SetY(69);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($infu118),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='19' and id_atencion=$id_atencion and des='INFUSIONES 1'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu119=$resp_r['cantidad'];
}
if(isset($infu119)){
$pdf->SetY(69);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($infu119),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='20' and id_atencion=$id_atencion and des='INFUSIONES 1'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu120=$resp_r['cantidad'];
}
if(isset($infu120)){
$pdf->SetY(69);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($infu120),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='21' and id_atencion=$id_atencion and des='INFUSIONES 1'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu121=$resp_r['cantidad'];
}
if(isset($infu121)){
$pdf->SetY(69);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($infu121),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='22' and id_atencion=$id_atencion and des='INFUSIONES 1'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu122=$resp_r['cantidad'];
}
if(isset($infu122)){
$pdf->SetY(69);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($infu122),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='23' and id_atencion=$id_atencion and des='INFUSIONES 1'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu123=$resp_r['cantidad'];
}
if(isset($infu123)){
$pdf->SetY(69);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($infu123),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(145);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='24' and id_atencion=$id_atencion and des='INFUSIONES 1'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu124=$resp_r['cantidad'];
}
if(isset($infu124)){
$pdf->SetY(69);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($infu124),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='1' and id_atencion=$id_atencion and des='INFUSIONES 1'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu101=$resp_r['cantidad'];
}
if(isset($infu101)){
$pdf->SetY(69);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($infu101),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(159);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='2' and id_atencion=$id_atencion and des='INFUSIONES 1'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu102=$resp_r['cantidad'];
}
if(isset($infu102)){
$pdf->SetY(69);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($infu102),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='3' and id_atencion=$id_atencion and des='INFUSIONES 1'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu103=$resp_r['cantidad'];
}
if(isset($infu103)){
$pdf->SetY(69);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($infu103),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='4' and id_atencion=$id_atencion and des='INFUSIONES 1'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu104=$resp_r['cantidad'];
}
if(isset($infu104)){
$pdf->SetY(69);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($infu104),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(180);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='5' and id_atencion=$id_atencion and des='INFUSIONES 1'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu105=$resp_r['cantidad'];
}
if(isset($infu105)){
$pdf->SetY(69);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($infu105),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(187);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='6' and id_atencion=$id_atencion and des='INFUSIONES 1'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu106=$resp_r['cantidad'];
}
if(isset($infu106)){
$pdf->SetY(69);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($infu106),1,0,'C');
}else{
  $pdf->SetY(69);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='7' and id_atencion=$id_atencion and des='INFUSIONES 1'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu107=$resp_r['cantidad'];
}
if(isset($infu107)){
$pdf->SetY(69);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($infu107),1,0,'C');
}else{
$pdf->SetY(69);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//INFUSIONES 2 INFUSIONES 2 INFUSIONES 2 INFUSIONES 2 INFUSIONES 2 INFUSIONES 2 INFUSIONES 2 POR HORAS

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='8' and id_atencion=$id_atencion and des='INFUSIONES 2'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu28=$resp_r['cantidad'];
}
if(isset($infu28)){
$pdf->SetY(72);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($infu28),1,0,'C');
}else{
  $pdf->SetY(72);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='9' and id_atencion=$id_atencion and des='INFUSIONES 2'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu29=$resp_r['cantidad'];
}
if(isset($infu29)){
$pdf->SetY(72);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($infu29),1,0,'C');
}else{
  $pdf->SetY(72);
$pdf->SetX(47);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='10' and id_atencion=$id_atencion and des='INFUSIONES 2'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu210=$resp_r['cantidad'];
}
if(isset($infu210)){
$pdf->SetY(72);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($infu210),1,0,'C');
}else{
  $pdf->SetY(72);
$pdf->SetX(54);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='11' and id_atencion=$id_atencion and des='INFUSIONES 2'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu211=$resp_r['cantidad'];
}
if(isset($infu211)){
$pdf->SetY(72);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($infu211),1,0,'C');
}else{
  $pdf->SetY(72);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='12' and id_atencion=$id_atencion and des='INFUSIONES 2'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu212=$resp_r['cantidad'];
}
if(isset($infu212)){
$pdf->SetY(72);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($infu212),1,0,'C');
}else{
  $pdf->SetY(72);
$pdf->SetX(68);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='13' and id_atencion=$id_atencion and des='INFUSIONES 2'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu213=$resp_r['cantidad'];
}
if(isset($infu213)){
$pdf->SetY(72);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($infu213),1,0,'C');
}else{
  $pdf->SetY(72);
$pdf->SetX(75);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='14' and id_atencion=$id_atencion and des='INFUSIONES 2'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu214=$resp_r['cantidad'];
}
if(isset($infu214)){
$pdf->SetY(72);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($infu214),1,0,'C');
}else{
  $pdf->SetY(72);
$pdf->SetX(82);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='15' and id_atencion=$id_atencion and des='INFUSIONES 2'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu215=$resp_r['cantidad'];
}
if(isset($infu215)){
$pdf->SetY(72);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($infu215),1,0,'C');
}else{
  $pdf->SetY(72);
$pdf->SetX(89);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='16' and id_atencion=$id_atencion and des='INFUSIONES 2'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu216=$resp_r['cantidad'];
}
if(isset($infu216)){
$pdf->SetY(72);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($infu216),1,0,'C');
}else{
  $pdf->SetY(72);
$pdf->SetX(96);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='17' and id_atencion=$id_atencion and des='INFUSIONES 2'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu217=$resp_r['cantidad'];
}
if(isset($infu217)){
$pdf->SetY(72);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($infu217),1,0,'C');
}else{
  $pdf->SetY(72);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='18' and id_atencion=$id_atencion and des='INFUSIONES 2'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu218=$resp_r['cantidad'];
}
if(isset($infu218)){
$pdf->SetY(72);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($infu218),1,0,'C');
}else{
  $pdf->SetY(72);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='19' and id_atencion=$id_atencion and des='INFUSIONES 2'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu219=$resp_r['cantidad'];
}
if(isset($infu219)){
$pdf->SetY(72);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($infu219),1,0,'C');
}else{
  $pdf->SetY(72);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='20' and id_atencion=$id_atencion and des='INFUSIONES 2'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu220=$resp_r['cantidad'];
}
if(isset($infu220)){
$pdf->SetY(72);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($infu220),1,0,'C');
}else{
  $pdf->SetY(72);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='21' and id_atencion=$id_atencion and des='INFUSIONES 2'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu221=$resp_r['cantidad'];
}
if(isset($infu221)){
$pdf->SetY(72);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($infu221),1,0,'C');
}else{
  $pdf->SetY(72);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='22' and id_atencion=$id_atencion and des='INFUSIONES 2'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu222=$resp_r['cantidad'];
}
if(isset($infu222)){
$pdf->SetY(72);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($infu222),1,0,'C');
}else{
  $pdf->SetY(72);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='23' and id_atencion=$id_atencion and des='INFUSIONES 2'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu223=$resp_r['cantidad'];
}
if(isset($infu223)){
$pdf->SetY(72);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($infu223),1,0,'C');
}else{
  $pdf->SetY(72);
$pdf->SetX(145);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='24' and id_atencion=$id_atencion and des='INFUSIONES 2'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu224=$resp_r['cantidad'];
}
if(isset($infu224)){
$pdf->SetY(72);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($infu224),1,0,'C');
}else{
  $pdf->SetY(72);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='1' and id_atencion=$id_atencion and des='INFUSIONES 2'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu201=$resp_r['cantidad'];
}
if(isset($infu201)){
$pdf->SetY(72);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($infu201),1,0,'C');
}else{
  $pdf->SetY(72);
$pdf->SetX(159);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='2' and id_atencion=$id_atencion and des='INFUSIONES 2'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu202=$resp_r['cantidad'];
}
if(isset($infu202)){
$pdf->SetY(72);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($infu202),1,0,'C');
}else{
  $pdf->SetY(72);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='3' and id_atencion=$id_atencion and des='INFUSIONES 2'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu203=$resp_r['cantidad'];
}
if(isset($infu203)){
$pdf->SetY(72);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($infu203),1,0,'C');
}else{
  $pdf->SetY(72);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='4' and id_atencion=$id_atencion and des='INFUSIONES 2'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu204=$resp_r['cantidad'];
}
if(isset($infu204)){
$pdf->SetY(72);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($infu204),1,0,'C');
}else{
  $pdf->SetY(72);
$pdf->SetX(180);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='5' and id_atencion=$id_atencion and des='INFUSIONES 2'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu205=$resp_r['cantidad'];
}
if(isset($infu205)){
$pdf->SetY(72);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($infu205),1,0,'C');
}else{
  $pdf->SetY(72);
$pdf->SetX(187);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='6' and id_atencion=$id_atencion and des='INFUSIONES 2'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu206=$resp_r['cantidad'];
}
if(isset($infu206)){
$pdf->SetY(72);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($infu206),1,0,'C');
}else{
  $pdf->SetY(72);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='7' and id_atencion=$id_atencion and des='INFUSIONES 2'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu207=$resp_r['cantidad'];
}
if(isset($infu207)){
$pdf->SetY(72);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($infu207),1,0,'C');
}else{
$pdf->SetY(72);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//VIA ENTERAL VIA ENTERAL VIA ENTERAL VIA ENTERAL VIA ENTERAL VIA ENTERAL VIA ENTERAL

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='8' and id_atencion=$id_atencion and des='NUTRICION ENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ne8=$resp_r['cantidad'];
}
if(isset($ne8)){
$pdf->SetY(75);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($ne8),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='9' and id_atencion=$id_atencion and des='NUTRICION ENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ne9=$resp_r['parent_m'];
}
if(isset($ne9)){
$pdf->SetY(75);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($ne9),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(47);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='10' and id_atencion=$id_atencion and des='NUTRICION ENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ne10=$resp_r['cantidad'];
}
if(isset($ne10)){
$pdf->SetY(75);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($ne10),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(54);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='11' and id_atencion=$id_atencion and des='NUTRICION ENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ne11=$resp_r['cantidad'];
}
if(isset($ne11)){
$pdf->SetY(75);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($ne11),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='12' and id_atencion=$id_atencion and des='NUTRICION ENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ne12=$resp_r['cantidad'];
}
if(isset($ne12)){
$pdf->SetY(75);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($ne12),1,0,'C');
}else{
    $pdf->SetY(75);
    $pdf->SetX(68);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='13' and id_atencion=$id_atencion and des='NUTRICION ENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ne13=$resp_r['cantidad'];
}
if(isset($ne13)){
$pdf->SetY(75);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($ne13),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(75);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='14' and id_atencion=$id_atencion and des='NUTRICION ENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ne14=$resp_r['cantidad'];
}
if(isset($ne14)){
$pdf->SetY(75);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($ne14),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(82);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='15' and id_atencion=$id_atencion and des='NUTRICION ENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ne15=$resp_r['cantidad'];
}
if(isset($ne15)){
$pdf->SetY(75);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($ne15),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(89);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='16' and id_atencion=$id_atencion and des='NUTRICION ENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ne16=$resp_r['cantidad'];
}
if(isset($ne16)){
$pdf->SetY(75);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($ne16),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(96);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='17' and id_atencion=$id_atencion and des='NUTRICION ENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ne17=$resp_r['cantidad'];
}
if(isset($ne17)){
$pdf->SetY(75);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($ne17),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='18' and id_atencion=$id_atencion and des='NUTRICION ENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ne18=$resp_r['cantidad'];
}
if(isset($ne18)){
$pdf->SetY(75);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($ne18),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='19' and id_atencion=$id_atencion and des='NUTRICION ENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ne19=$resp_r['cantidad'];
}
if(isset($ne19)){
$pdf->SetY(75);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($ne19),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='20' and id_atencion=$id_atencion and des='NUTRICION ENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ne20=$resp_r['cantidad'];
}
if(isset($ne20)){
$pdf->SetY(75);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($ne20),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='21' and id_atencion=$id_atencion and des='NUTRICION ENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ne21=$resp_r['cantidad'];
}
if(isset($ne21)){
$pdf->SetY(75);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($ne21),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='22' and id_atencion=$id_atencion and des='NUTRICION ENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ne22=$resp_r['cantidad'];
}
if(isset($ne22)){
$pdf->SetY(75);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($ne22),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='23' and id_atencion=$id_atencion and des='NUTRICION ENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ne23=$resp_r['cantidad'];
}
if(isset($ne23)){
$pdf->SetY(75);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($ne23),1,0,'C');
}else{
  $pdf->SetY(75);
  $pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='24' and id_atencion=$id_atencion and des='NUTRICION ENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ne24=$resp_r['cantidad'];
}
if(isset($ne24)){
$pdf->SetY(75);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($ne24),1,0,'C');
}else{
  $pdf->SetY(75);
  $pdf->SetX(152);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='1' and id_atencion=$id_atencion and des='NUTRICION ENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ne01=$resp_r['cantidad'];
}
if(isset($ne01)){
$pdf->SetY(75);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($ne01),1,0,'C');
}else{
  $pdf->SetY(75);
  $pdf->SetX(159);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='2' and id_atencion=$id_atencion and des='NUTRICION ENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ne02=$resp_r['cantidad'];
}
if(isset($ne02)){
$pdf->SetY(75);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($ne02),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='3' and id_atencion=$id_atencion and des='NUTRICION ENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ne03=$resp_r['cantidad'];
}
if(isset($ne03)){
$pdf->SetY(75);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($ne03),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='4' and id_atencion=$id_atencion and des='NUTRICION ENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ne04=$resp_r['cantidad'];
}
if(isset($ne04)){
$pdf->SetY(75);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($ne04),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(180);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='5' and id_atencion=$id_atencion and des='NUTRICION ENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ne05=$resp_r['cantidad'];
}
if(isset($ne05)){
$pdf->SetY(75);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($ne05),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(187);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='6' and id_atencion=$id_atencion and des='NUTRICION ENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ne06=$resp_r['cantidad'];
}
if(isset($ne06)){
$pdf->SetY(75);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($ne06),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='7' and id_atencion=$id_atencion and des='NUTRICION ENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ne07=$resp_r['cantidad'];
}
if(isset($ne07)){
$pdf->SetY(75);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($ne07),1,0,'C');
}else{
  $pdf->SetY(75);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//VIA PARENTERAL VIA PARENTERAL VIA PARENTERAL VIA PARENTERAL VIA PARENTERAL VIA PARENTERAL VIA PARENTERAL

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='8' and id_atencion=$id_atencion and des='NUTRICION PARENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $np8=$resp_r['cantidad'];
}
if(isset($np8)){
$pdf->SetY(78);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($np8),1,0,'C');
}else{
  $pdf->SetY(78);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='9' and id_atencion=$id_atencion and des='NUTRICION PARENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $np9=$resp_r['cantidad'];
}
if(isset($np9)){
$pdf->SetY(78);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($np9),1,0,'C');
}else{
  $pdf->SetY(78);
$pdf->SetX(47);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='10' and id_atencion=$id_atencion and des='NUTRICION PARENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $np10=$resp_r['cantidad'];
}
if(isset($np10)){
$pdf->SetY(78);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($np10),1,0,'C');
}else{
  $pdf->SetY(78);
 $pdf->SetX(54);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='11' and id_atencion=$id_atencion and des='NUTRICION PARENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $np11=$resp_r['cantidad'];
}
if(isset($np11)){
$pdf->SetY(78);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($np11),1,0,'C');
}else{
  $pdf->SetY(78);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='12' and id_atencion=$id_atencion and des='NUTRICION PARENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $np12=$resp_r['cantidad'];
}
if(isset($np12)){
$pdf->SetY(78);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($np12),1,0,'C');
}else{
    $pdf->SetY(78);
    $pdf->SetX(68);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='13' and id_atencion=$id_atencion and des='NUTRICION PARENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $np13=$resp_r['cantidad'];
}
if(isset($np13)){
$pdf->SetY(78);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($np13),1,0,'C');
}else{
  $pdf->SetY(78);
$pdf->SetX(75);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='14' and id_atencion=$id_atencion and des='NUTRICION PARENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $np14=$resp_r['cantidad'];
}
if(isset($np14)){
$pdf->SetY(78);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($np14),1,0,'C');
}else{
  $pdf->SetY(78);
$pdf->SetX(82);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='15' and id_atencion=$id_atencion and des='NUTRICION PARENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $np15=$resp_r['cantidad'];
}
if(isset($np15)){
$pdf->SetY(78);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($np15),1,0,'C');
}else{
  $pdf->SetY(78);
$pdf->SetX(89);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='16' and id_atencion=$id_atencion and des='NUTRICION PARENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $np16=$resp_r['cantidad'];
}
if(isset($np16)){
$pdf->SetY(78);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($np16),1,0,'C');
}else{
  $pdf->SetY(78);
$pdf->SetX(96);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='17' and id_atencion=$id_atencion and des='NUTRICION PARENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $np17=$resp_r['cantidad'];
}
if(isset($np17)){
$pdf->SetY(78);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($np17),1,0,'C');
}else{
  $pdf->SetY(78);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='18' and id_atencion=$id_atencion and des='NUTRICION PARENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $np18=$resp_r['cantidad'];
}
if(isset($np18)){
$pdf->SetY(78);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($np18),1,0,'C');
}else{
  $pdf->SetY(78);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='19' and id_atencion=$id_atencion and des='NUTRICION PARENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $np19=$resp_r['cantidad'];
}
if(isset($np19)){
$pdf->SetY(78);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($np19),1,0,'C');
}else{
  $pdf->SetY(78);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='20' and id_atencion=$id_atencion and des='NUTRICION PARENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $np20=$resp_r['cantidad'];
}
if(isset($np20)){
$pdf->SetY(78);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($np20),1,0,'C');
}else{
  $pdf->SetY(78);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='21' and id_atencion=$id_atencion and des='NUTRICION PARENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $np21=$resp_r['cantidad'];
}
if(isset($np21)){
$pdf->SetY(78);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($np21),1,0,'C');
}else{
  $pdf->SetY(78);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='22' and id_atencion=$id_atencion and des='NUTRICION PARENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $np22=$resp_r['cantidad'];
}
if(isset($np22)){
$pdf->SetY(78);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($np22),1,0,'C');
}else{
  $pdf->SetY(78);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='23' and id_atencion=$id_atencion and des='NUTRICION PARENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $np23=$resp_r['cantidad'];
}
if(isset($np23)){
$pdf->SetY(78);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($np23),1,0,'C');
}else{
  $pdf->SetY(78);
  $pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='24' and id_atencion=$id_atencion and des='NUTRICION PARENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $np24=$resp_r['cantidad'];
}
if(isset($np24)){
$pdf->SetY(78);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($np24),1,0,'C');
}else{
  $pdf->SetY(78);
  $pdf->SetX(152);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='1' and id_atencion=$id_atencion and des='NUTRICION PARENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $np01=$resp_r['cantidad'];
}
if(isset($np01)){
$pdf->SetY(78);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($np01),1,0,'C');
}else{
  $pdf->SetY(78);
  $pdf->SetX(159);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='2' and id_atencion=$id_atencion and des='NUTRICION PARENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $np02=$resp_r['cantidad'];
}
if(isset($np02)){
$pdf->SetY(78);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($np02),1,0,'C');
}else{
  $pdf->SetY(78);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='3' and id_atencion=$id_atencion and des='NUTRICION PARENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $np03=$resp_r['cantidad'];
}
if(isset($np03)){
$pdf->SetY(78);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($np03),1,0,'C');
}else{
  $pdf->SetY(78);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='4' and id_atencion=$id_atencion and des='NUTRICION PARENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $np04=$resp_r['cantidad'];
}
if(isset($np04)){
$pdf->SetY(78);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($np04),1,0,'C');
}else{
  $pdf->SetY(78);
  $pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='5' and id_atencion=$id_atencion and des='NUTRICION PARENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $np05=$resp_r['cantidad'];
}
if(isset($np05)){
$pdf->SetY(78);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($np05),1,0,'C');
}else{
  $pdf->SetY(78);
  $pdf->SetX(187);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='6' and id_atencion=$id_atencion and des='NUTRICION PARENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $np06=$resp_r['cantidad'];
}
if(isset($np06)){
$pdf->SetY(78);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($np06),1,0,'C');
}else{
  $pdf->SetY(78);
  $pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='7' and id_atencion=$id_atencion and des='NUTRICION PARENTERAL'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $np07=$resp_r['cantidad'];
}
if(isset($np07)){
$pdf->SetY(78);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($np07),1,0,'C');
}else{
  $pdf->SetY(78);
  $pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//MEDICAMENTOS MEDICAMENTOS MEDICAMENTOS MEDICAMENTOS MEDICAMENTOS MEDICAMENTOS MEDICAMENTOS POR HORAS

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='8' and id_atencion=$id_atencion and des='MEDICAMENTOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $m8=$resp_r['cantidad'];
}
if(isset($m8)){
$pdf->SetY(81);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($m8),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='9' and id_atencion=$id_atencion and des='MEDICAMENTOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $m9=$resp_r['cantidad'];
}
if(isset($m9)){
$pdf->SetY(81);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($m9),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(47);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='10' and id_atencion=$id_atencion and des='MEDICAMENTOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $m10=$resp_r['cantidad'];
}
if(isset($m10)){
$pdf->SetY(81);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($m10),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(54);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='11' and id_atencion=$id_atencion and des='MEDICAMENTOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $m11=$resp_r['cantidad'];
}
if(isset($m11)){
$pdf->SetY(81);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($m11),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='12' and id_atencion=$id_atencion and des='MEDICAMENTOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $m12=$resp_r['cantidad'];
}
if(isset($m12)){
$pdf->SetY(81);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($m12),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(68);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='13' and id_atencion=$id_atencion and des='MEDICAMENTOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $m13=$resp_r['cantidad'];
}
if(isset($m13)){
$pdf->SetY(81);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($m13),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(75);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='14' and id_atencion=$id_atencion and des='MEDICAMENTOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $m14=$resp_r['cantidad'];
}
if(isset($m14)){
$pdf->SetY(81);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($m14),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(82);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='15' and id_atencion=$id_atencion and des='MEDICAMENTOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $m15=$resp_r['cantidad'];
}
if(isset($m15)){
$pdf->SetY(81);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($m15),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(89);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='16' and id_atencion=$id_atencion and des='MEDICAMENTOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $m16=$resp_r['cantidad'];
}
if(isset($m16)){
$pdf->SetY(81);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($m16),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(96);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='17' and id_atencion=$id_atencion and des='MEDICAMENTOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $m17=$resp_r['cantidad'];
}
if(isset($m17)){
$pdf->SetY(81);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($m17),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='18' and id_atencion=$id_atencion and des='MEDICAMENTOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $m18=$resp_r['cantidad'];
}
if(isset($m18)){
$pdf->SetY(81);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($m18),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='19' and id_atencion=$id_atencion and des='MEDICAMENTOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $m19=$resp_r['cantidad'];
}
if(isset($m19)){
$pdf->SetY(81);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($m19),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='20' and id_atencion=$id_atencion and des='MEDICAMENTOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $m20=$resp_r['cantidad'];
}
if(isset($m20)){
$pdf->SetY(81);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($m20),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='21' and id_atencion=$id_atencion and des='MEDICAMENTOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $m21=$resp_r['cantidad'];
}
if(isset($m21)){
$pdf->SetY(81);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($m21),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='22' and id_atencion=$id_atencion and des='MEDICAMENTOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $m22=$resp_r['cantidad'];
}
if(isset($m22)){
$pdf->SetY(81);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($m22),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='23' and id_atencion=$id_atencion and des='MEDICAMENTOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $m23=$resp_r['cantidad'];
}
if(isset($m23)){
$pdf->SetY(81);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($m23),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(145);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='24' and id_atencion=$id_atencion and des='MEDICAMENTOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $m24=$resp_r['cantidad'];
}
if(isset($m24)){
$pdf->SetY(81);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($m24),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='1' and id_atencion=$id_atencion and des='MEDICAMENTOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $m01=$resp_r['cantidad'];
}
if(isset($m01)){
$pdf->SetY(81);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($m01),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(159);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='2' and id_atencion=$id_atencion and des='MEDICAMENTOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $m02=$resp_r['cantidad'];
}
if(isset($m02)){
$pdf->SetY(81);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($m02),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='3' and id_atencion=$id_atencion and des='MEDICAMENTOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $m03=$resp_r['cantidad'];
}
if(isset($m03)){
$pdf->SetY(81);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($m03),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='4' and id_atencion=$id_atencion and des='MEDICAMENTOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $m04=$resp_r['cantidad'];
}
if(isset($m04)){
$pdf->SetY(81);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($m04),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(180);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='5' and id_atencion=$id_atencion and des='MEDICAMENTOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $m05=$resp_r['cantidad'];
}
if(isset($m05)){
$pdf->SetY(81);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($m05),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(187);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='6' and id_atencion=$id_atencion and des='MEDICAMENTOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $m06=$resp_r['cantidad'];
}
if(isset($m06)){
$pdf->SetY(81);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($m06),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='7' and id_atencion=$id_atencion and des='MEDICAMENTOS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $m07=$resp_r['cantidad'];
}
if(isset($m07)){
$pdf->SetY(81);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($m07),1,0,'C');
}else{
  $pdf->SetY(81);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//AMINAS AMINAS AMINAS AMINAS AMINAS AMINAS AMINAS POR HORAS

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='8' and id_atencion=$id_atencion and des='AMINAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ami8=$resp_r['cantidad'];
}
if(isset($ami8)){
$pdf->SetY(84);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($ami8),1,0,'C');
}else{
  $pdf->SetY(84);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='9' and id_atencion=$id_atencion and des='AMINAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ami9=$resp_r['cantidad'];
}
if(isset($ami9)){
$pdf->SetY(84);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($ami9),1,0,'C');
}else{
  $pdf->SetY(84);
$pdf->SetX(47);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='10' and id_atencion=$id_atencion and des='AMINAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ami10=$resp_r['cantidad'];
}
if(isset($ami10)){
$pdf->SetY(84);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($ami10),1,0,'C');
}else{
  $pdf->SetY(84);
$pdf->SetX(54);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='11' and id_atencion=$id_atencion and des='AMINAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ami11=$resp_r['cantidad'];
}
if(isset($ami11)){
$pdf->SetY(84);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($ami11),1,0,'C');
}else{
  $pdf->SetY(84);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='12' and id_atencion=$id_atencion and des='AMINAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ami12=$resp_r['cantidad'];
}
if(isset($ami12)){
$pdf->SetY(84);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($ami12),1,0,'C');
}else{
  $pdf->SetY(84);
$pdf->SetX(68);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='13' and id_atencion=$id_atencion and des='AMINAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ami13=$resp_r['cantidad'];
}
if(isset($ami13)){
$pdf->SetY(84);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($ami13),1,0,'C');
}else{
  $pdf->SetY(84);
$pdf->SetX(75);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='14' and id_atencion=$id_atencion and des='AMINAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ami14=$resp_r['cantidad'];
}
if(isset($ami14)){
$pdf->SetY(84);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($ami14),1,0,'C');
}else{
  $pdf->SetY(84);
$pdf->SetX(82);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='15' and id_atencion=$id_atencion and des='AMINAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ami15=$resp_r['cantidad'];
}
if(isset($ami15)){
$pdf->SetY(84);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($ami15),1,0,'C');
}else{
  $pdf->SetY(84);
$pdf->SetX(89);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='16' and id_atencion=$id_atencion and des='AMINAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ami16=$resp_r['cantidad'];
}
if(isset($ami16)){
$pdf->SetY(84);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($ami16),1,0,'C');
}else{
  $pdf->SetY(84);
$pdf->SetX(96);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='17' and id_atencion=$id_atencion and des='AMINAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ami17=$resp_r['cantidad'];
}
if(isset($ami17)){
$pdf->SetY(84);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($ami17),1,0,'C');
}else{
  $pdf->SetY(84);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='18' and id_atencion=$id_atencion and des='AMINAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ami18=$resp_r['cantidad'];
}
if(isset($ami18)){
$pdf->SetY(84);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($ami18),1,0,'C');
}else{
  $pdf->SetY(84);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='19' and id_atencion=$id_atencion and des='AMINAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ami19=$resp_r['cantidad'];
}
if(isset($ami19)){
$pdf->SetY(84);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($ami19),1,0,'C');
}else{
  $pdf->SetY(84);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='20' and id_atencion=$id_atencion and des='AMINAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ami20=$resp_r['cantidad'];
}
if(isset($ami20)){
$pdf->SetY(84);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($ami20),1,0,'C');
}else{
  $pdf->SetY(84);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='21' and id_atencion=$id_atencion and des='AMINAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ami21=$resp_r['cantidad'];
}
if(isset($ami21)){
$pdf->SetY(84);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($ami21),1,0,'C');
}else{
  $pdf->SetY(84);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='22' and id_atencion=$id_atencion and des='AMINAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ami22=$resp_r['cantidad'];
}
if(isset($ami22)){
$pdf->SetY(84);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($ami22),1,0,'C');
}else{
  $pdf->SetY(84);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='23' and id_atencion=$id_atencion and des='AMINAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ami23=$resp_r['cantidad'];
}
if(isset($ami23)){
$pdf->SetY(84);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($ami23),1,0,'C');
}else{
  $pdf->SetY(84);
$pdf->SetX(145);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='24' and id_atencion=$id_atencion and des='AMINAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ami24=$resp_r['cantidad'];
}
if(isset($ami24)){
$pdf->SetY(84);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($ami24),1,0,'C');
}else{
  $pdf->SetY(84);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='1' and id_atencion=$id_atencion and des='AMINAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ami01=$resp_r['cantidad'];
}
if(isset($ami01)){
$pdf->SetY(84);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($ami01),1,0,'C');
}else{
  $pdf->SetY(84);
$pdf->SetX(159);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='2' and id_atencion=$id_atencion and des='AMINAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ami02=$resp_r['cantidad'];
}
if(isset($ami02)){
$pdf->SetY(84);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($ami02),1,0,'C');
}else{
  $pdf->SetY(84);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='3' and id_atencion=$id_atencion and des='AMINAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ami03=$resp_r['cantidad'];
}
if(isset($ami03)){
$pdf->SetY(84);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($ami03),1,0,'C');
}else{
  $pdf->SetY(84);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='4' and id_atencion=$id_atencion and des='AMINAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ami04=$resp_r['cantidad'];
}
if(isset($ami04)){
$pdf->SetY(84);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($ami04),1,0,'C');
}else{
  $pdf->SetY(84);
$pdf->SetX(180);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='5' and id_atencion=$id_atencion and des='AMINAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ami05=$resp_r['cantidad'];
}
if(isset($ami05)){
$pdf->SetY(84);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($ami05),1,0,'C');
}else{
  $pdf->SetY(84);
$pdf->SetX(187);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='6' and id_atencion=$id_atencion and des='AMINAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ami06=$resp_r['cantidad'];
}
if(isset($ami06)){
$pdf->SetY(84);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($ami06),1,0,'C');
}else{
  $pdf->SetY(84);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='7' and id_atencion=$id_atencion and des='AMINAS'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ami07=$resp_r['cantidad'];
}
if(isset($ami07)){
$pdf->SetY(84);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($ami07),1,0,'C');
}else{
  $pdf->SetY(84);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


//INFUSIONES 3 INFUSIONES 3 INFUSIONES 3 INFUSIONES 3 INFUSIONES 3 INFUSIONES 3 INFUSIONES 3 POR HORAS

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='8' and id_atencion=$id_atencion and des='INFUSIONES 3'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu38=$resp_r['cantidad'];
}
if(isset($infu38)){
$pdf->SetY(87);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($infu38),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='9' and id_atencion=$id_atencion and des='INFUSIONES 3'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu39=$resp_r['cantidad'];
}
if(isset($infu39)){
$pdf->SetY(87);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($infu39),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(47);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='10' and id_atencion=$id_atencion and des='INFUSIONES 3'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu310=$resp_r['cantidad'];
}
if(isset($infu310)){
$pdf->SetY(87);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($infu310),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(54);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='11' and id_atencion=$id_atencion and des='INFUSIONES 3'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu311=$resp_r['cantidad'];
}
if(isset($infu311)){
$pdf->SetY(87);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($infu311),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='12' and id_atencion=$id_atencion and des='INFUSIONES 3'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu312=$resp_r['cantidad'];
}
if(isset($infu312)){
$pdf->SetY(87);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($infu312),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(68);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='13' and id_atencion=$id_atencion and des='INFUSIONES 3'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu313=$resp_r['cantidad'];
}
if(isset($infu313)){
$pdf->SetY(87);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($infu313),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(75);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='14' and id_atencion=$id_atencion and des='INFUSIONES 3'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu314=$resp_r['cantidad'];
}
if(isset($infu314)){
$pdf->SetY(87);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($infu314),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(82);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='15' and id_atencion=$id_atencion and des='INFUSIONES 3'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu315=$resp_r['cantidad'];
}
if(isset($infu315)){
$pdf->SetY(87);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($infu315),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(89);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='16' and id_atencion=$id_atencion and des='INFUSIONES 3'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu316=$resp_r['cantidad'];
}
if(isset($infu316)){
$pdf->SetY(87);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($infu316),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(96);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='17' and id_atencion=$id_atencion and des='INFUSIONES 3'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu317=$resp_r['cantidad'];
}
if(isset($infu317)){
$pdf->SetY(87);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($infu317),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='18' and id_atencion=$id_atencion and des='INFUSIONES 3'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu318=$resp_r['cantidad'];
}
if(isset($infu318)){
$pdf->SetY(87);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($infu318),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='19' and id_atencion=$id_atencion and des='INFUSIONES 3'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu319=$resp_r['cantidad'];
}
if(isset($infu319)){
$pdf->SetY(87);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($infu319),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='20' and id_atencion=$id_atencion and des='INFUSIONES 3'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu320=$resp_r['cantidad'];
}
if(isset($infu320)){
$pdf->SetY(87);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($infu320),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='21' and id_atencion=$id_atencion and des='INFUSIONES 3'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu321=$resp_r['cantidad'];
}
if(isset($infu321)){
$pdf->SetY(87);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($infu321),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='22' and id_atencion=$id_atencion and des='INFUSIONES 3'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu322=$resp_r['cantidad'];
}
if(isset($infu322)){
$pdf->SetY(87);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($infu322),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='23' and id_atencion=$id_atencion and des='INFUSIONES 3'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu323=$resp_r['cantidad'];
}
if(isset($infu323)){
$pdf->SetY(87);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($infu323),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(145);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='24' and id_atencion=$id_atencion and des='INFUSIONES 3'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu324=$resp_r['cantidad'];
}
if(isset($infu324)){
$pdf->SetY(87);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($infu324),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='1' and id_atencion=$id_atencion and des='INFUSIONES 3'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu301=$resp_r['cantidad'];
}
if(isset($infu301)){
$pdf->SetY(87);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($infu301),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(159);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='2' and id_atencion=$id_atencion and des='INFUSIONES 3'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu302=$resp_r['cantidad'];
}
if(isset($infu302)){
$pdf->SetY(87);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($infu302),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='3' and id_atencion=$id_atencion and des='INFUSIONES 3'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu303=$resp_r['cantidad'];
}
if(isset($infu303)){
$pdf->SetY(87);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($infu303),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='4' and id_atencion=$id_atencion and des='INFUSIONES 3'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu304=$resp_r['cantidad'];
}
if(isset($infu304)){
$pdf->SetY(87);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($infu304),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(180);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='5' and id_atencion=$id_atencion and des='INFUSIONES 3'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu305=$resp_r['cantidad'];
}
if(isset($infu305)){
$pdf->SetY(87);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($infu305),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(187);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='6' and id_atencion=$id_atencion and des='INFUSIONES 3'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu306=$resp_r['cantidad'];
}
if(isset($infu306)){
$pdf->SetY(87);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($infu306),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='7' and id_atencion=$id_atencion and des='INFUSIONES 3'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu307=$resp_r['cantidad'];
}
if(isset($infu307)){
$pdf->SetY(87);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($infu307),1,0,'C');
}else{
  $pdf->SetY(87);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


//INFUSIONES 4 INFUSIONES 4 INFUSIONES 4 INFUSIONES 4 INFUSIONES 4 INFUSIONES 4 INFUSIONES 4 POR HORAS

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='8' and id_atencion=$id_atencion and des='INFUSIONES 4'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu48=$resp_r['cantidad'];
}
if(isset($infu48)){
$pdf->SetY(90);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($infu48),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='9' and id_atencion=$id_atencion and des='INFUSIONES 4'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu49=$resp_r['cantidad'];
}
if(isset($infu49)){
$pdf->SetY(90);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($infu49),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(47);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='10' and id_atencion=$id_atencion and des='INFUSIONES 4'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu410=$resp_r['cantidad'];
}
if(isset($infu410)){
$pdf->SetY(90);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($infu410),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(54);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='11' and id_atencion=$id_atencion and des='INFUSIONES 4'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu411=$resp_r['cantidad'];
}
if(isset($infu411)){
$pdf->SetY(90);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($infu411),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='12' and id_atencion=$id_atencion and des='INFUSIONES 4'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu412=$resp_r['cantidad'];
}
if(isset($infu412)){
$pdf->SetY(90);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($infu412),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(68);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='13' and id_atencion=$id_atencion and des='INFUSIONES 4'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu413=$resp_r['cantidad'];
}
if(isset($infu413)){
$pdf->SetY(90);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($infu413),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(75);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='14' and id_atencion=$id_atencion and des='INFUSIONES 4'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu414=$resp_r['cantidad'];
}
if(isset($infu414)){
$pdf->SetY(90);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($infu414),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(82);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='15' and id_atencion=$id_atencion and des='INFUSIONES 4'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu415=$resp_r['cantidad'];
}
if(isset($infu415)){
$pdf->SetY(90);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($infu415),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(89);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='16' and id_atencion=$id_atencion and des='INFUSIONES 4'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu416=$resp_r['cantidad'];
}
if(isset($infu416)){
$pdf->SetY(90);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($infu416),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(96);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='17' and id_atencion=$id_atencion and des='INFUSIONES 4'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu417=$resp_r['cantidad'];
}
if(isset($infu417)){
$pdf->SetY(90);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($infu417),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='18' and id_atencion=$id_atencion and des='INFUSIONES 4'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu418=$resp_r['cantidad'];
}
if(isset($infu418)){
$pdf->SetY(90);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($infu418),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='19' and id_atencion=$id_atencion and des='INFUSIONES 4'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu419=$resp_r['cantidad'];
}
if(isset($infu419)){
$pdf->SetY(90);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($infu419),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='20' and id_atencion=$id_atencion and des='INFUSIONES 4'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu420=$resp_r['cantidad'];
}
if(isset($infu420)){
$pdf->SetY(90);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($infu420),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='21' and id_atencion=$id_atencion and des='INFUSIONES 4'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu421=$resp_r['cantidad'];
}
if(isset($infu421)){
$pdf->SetY(90);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($infu421),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='22' and id_atencion=$id_atencion and des='INFUSIONES 4'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu422=$resp_r['cantidad'];
}
if(isset($infu422)){
$pdf->SetY(90);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($infu422),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='23' and id_atencion=$id_atencion and des='INFUSIONES 4'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu423=$resp_r['cantidad'];
}
if(isset($infu423)){
$pdf->SetY(90);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($infu423),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(145);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='24' and id_atencion=$id_atencion and des='INFUSIONES 4'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu424=$resp_r['cantidad'];
}
if(isset($infu424)){
$pdf->SetY(90);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($infu424),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='1' and id_atencion=$id_atencion and des='INFUSIONES 4'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu401=$resp_r['cantidad'];
}
if(isset($infu401)){
$pdf->SetY(90);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($infu401),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(159);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='2' and id_atencion=$id_atencion and des='INFUSIONES 4'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu402=$resp_r['cantidad'];
}
if(isset($infu402)){
$pdf->SetY(90);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($infu402),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='3' and id_atencion=$id_atencion and des='INFUSIONES 4'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu403=$resp_r['cantidad'];
}
if(isset($infu403)){
$pdf->SetY(90);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($infu403),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='4' and id_atencion=$id_atencion and des='INFUSIONES 4'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu404=$resp_r['cantidad'];
}
if(isset($infu404)){
$pdf->SetY(90);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($infu404),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(180);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='5' and id_atencion=$id_atencion and des='INFUSIONES 4'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu405=$resp_r['cantidad'];
}
if(isset($infu405)){
$pdf->SetY(90);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($infu405),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(187);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='6' and id_atencion=$id_atencion and des='INFUSIONES 4'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu406=$resp_r['cantidad'];
}
if(isset($infu406)){
$pdf->SetY(90);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($infu406),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from ing_enf_ter where fecha='$fechar' and hora='7' and id_atencion=$id_atencion and des='INFUSIONES 4'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $infu407=$resp_r['cantidad'];
}
if(isset($infu407)){
$pdf->SetY(90);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($infu407),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL

//INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL MATUTINO

$resps = $conexion->query("select SUM(cantidad) as sumbase from ing_enf_ter where des='SOLUCION BASE' and fecha='$fechar'   and id_atencion=$id_atencion and (hora='8' or hora='9' or hora='10' or hora='11' or hora='12' or hora='13' or hora='14' )") or die($conexion->error);
while ($resp_rso = $resps->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumbases=$resp_rso['sumbase'];
}

$resp = $conexion->query("select SUM(cantidad) as summed from ing_enf_ter where des='MEDICAMENTOS' and fecha='$fechar'   and id_atencion=$id_atencion and (hora='8' or hora='9' or hora='10' or hora='11' or hora='12' or hora='13' or hora='14')") or die($conexion->error);
while ($resp_rme = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $summed=$resp_rme['summed'];
}

$respor = $conexion->query("select SUM(cantidad) as vias from ing_enf_ter where des='VIA ORAL' and fecha='$fechar'   and id_atencion=$id_atencion and (hora='8' or hora='9' or hora='10' or hora='11' or hora='12' or hora='13' or hora='14')") or die($conexion->error);
while ($resp_rvvi = $respor->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vias=$resp_rvvi['vias'];
}

$resp = $conexion->query("select SUM(cantidad) as sumAMINAS from ing_enf_ter where des='AMINAS' and fecha='$fechar'   and id_atencion=$id_atencion and (hora='8' or hora='9' or hora='10' or hora='11' or hora='12' or hora='13' or hora='14')") or die($conexion->error);
while ($resp_rcc = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumAMINAS=$resp_rcc['sumAMINAS'];
}

$resp = $conexion->query("select SUM(cantidad) as sumcargas from ing_enf_ter where des='CARGAS' and fecha='$fechar'   and id_atencion=$id_atencion and (hora='8' or hora='9' or hora='10' or hora='11' or hora='12' or hora='13' or hora='14')") or die($conexion->error);
while ($resp_rcc = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumcargas=$resp_rcc['sumcargas'];
}

$resp = $conexion->query("select SUM(cantidad) as sumcantidad from ing_enf_ter where (des='INFUSIONES 1' || des='INFUSIONES 2' || des='INFUSIONES 3' || des='INFUSIONES 4') and fecha='$fechar'   and id_atencion=$id_atencion and (hora='8' or hora='9' or hora='10' or hora='11' or hora='12' or hora='13' or hora='14')") or die($conexion->error);
while ($resp_rin = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumcantidad=$resp_rin['sumcantidad'];
}


$resp = $conexion->query("select SUM(cantidad) as sumne from ing_enf_ter where des='NUTRICION ENTERAL' and fecha='$fechar'   and id_atencion=$id_atencion and (hora='8' or hora='9' or hora='10' or hora='11' or hora='12' or hora='13' or hora='14')") or die($conexion->error);
while ($resp_rin = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumne=$resp_rin['sumne'];
}

$resp = $conexion->query("select SUM(cantidad) as sum from ing_enf_ter where des='HEMODERIVADOS' and fecha='$fechar'   and id_atencion=$id_atencion and (hora='8' or hora='9' or hora='10' or hora='11' or hora='12' or hora='13' or hora='14')") or die($conexion->error);
while ($resp_rtr = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sum=$resp_rtr['sum'];
}

$resp = $conexion->query("select SUM(cantidad) as sumnut from ing_enf_ter where (des='NUTRICION PARENTERAL') and fecha='$fechar'   and id_atencion=$id_atencion and (hora='8' or hora='9' or hora='10' or hora='11' or hora='12' or hora='13' or hora='14')") or die($conexion->error);
while ($resp_run = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumnut=$resp_run['sumnut'];
}

$sumatotalm=$sumbases+$summed+$vias+$sumAMINAS+$sumcantidad+$sum+$sumnut+$sumne+$sumcargas;

$pdf->SetY(93);
$pdf->SetX(40);
$pdf->Cell(49,6, utf8_decode($sumatotalm . ' ML'),1,0,'C');

//INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL VESPERTINO

$resp = $conexion->query("select SUM(cantidad) as sumbasev from ing_enf_ter where des='SOLUCION BASE' and fecha='$fechar'   and id_atencion=$id_atencion and (hora='15' or hora='16' or hora='17' or hora='18' or hora='19' or hora='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumbasev=$resp_r['sumbasev'];
}

$resp = $conexion->query("select SUM(cantidad) as summedv from ing_enf_ter where des='MEDICAMENTOS' and fecha='$fechar'   and id_atencion=$id_atencion and (hora='15' or hora='16' or hora='17' or hora='18' or hora='19' or hora='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $summedv=$resp_r['summedv'];
}

$resp = $conexion->query("select SUM(cantidad) as viav from ing_enf_ter where des='VIA ORAL' and fecha='$fechar'   and id_atencion=$id_atencion and (hora='15' or hora='16' or hora='17' or hora='18' or hora='19' or hora='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $viav=$resp_r['viav'];
}

$resp = $conexion->query("select SUM(cantidad) as sumcasgasv from ing_enf_ter where (des='CARGAS') and fecha='$fechar'   and id_atencion=$id_atencion and (hora='15' or hora='16' or hora='17' or hora='18' or hora='19' or hora='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumAMINASv=$resp_r['sumcargasv'];
}
$resp = $conexion->query("select SUM(cantidad) as sumAMINASv from ing_enf_ter where (des='AMINAS') and fecha='$fechar'   and id_atencion=$id_atencion and (hora='15' or hora='16' or hora='17' or hora='18' or hora='19' or hora='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumAMINASv=$resp_r['sumAMINASv'];
}

$resp = $conexion->query("select SUM(cantidad) as sumcantidadv from ing_enf_ter where (des='INFUSIONES 1' || des='INFUSIONES 2' || des='INFUSIONES 3' || des='INFUSIONES 4') and fecha='$fechar'   and id_atencion=$id_atencion and (hora='15' or hora='16' or hora='17' or hora='18' or hora='19' or hora='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumcantidadv=$resp_r['sumcantidadv'];
}

$resp = $conexion->query("select SUM(cantidad) as sumv from ing_enf_ter where des='HEMODERIVADOS' and fecha='$fechar'   and id_atencion=$id_atencion and (hora='15' or hora='16' or hora='17' or hora='18' or hora='19' or hora='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumv=$resp_r['sumv'];
}

$resp = $conexion->query("select SUM(cantidad) as sumnev from ing_enf_ter where (des='NUTRICION ENTERAL') and fecha='$fechar'   and id_atencion=$id_atencion and (hora='15' or hora='16' or hora='17' or hora='18' or hora='19' or hora='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumnutrv=$resp_r['sumnev'];
}

$resp = $conexion->query("select SUM(cantidad) as sumnutv from ing_enf_ter where (des='NUTRICION PARENTERAL') and fecha='$fechar'   and id_atencion=$id_atencion and (hora='15' or hora='16' or hora='17' or hora='18' or hora='19' or hora='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumnutrv=$resp_r['sumnutv'];
}

$sumatotalv=$sumbasev+$summedv+$viav+$sumAMINASv+$sumcargas+$sumcantidadv+$sumv+$sumnutv+$sumnev;

$pdf->SetY(93);
$pdf->SetX(89);
$pdf->Cell(42,6, utf8_decode($sumatotalv . ' ML'),1,0,'C');

//INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL NOCTURNO NOCTURNO

$resp = $conexion->query("select SUM(cantidad) as sumbasen from ing_enf_ter where des='SOLUCION BASE' and fecha='$fechar'   and id_atencion=$id_atencion and (hora='21' or hora='22' or hora='23' or hora='24' or hora='1' or hora='2' or hora='3' or hora='4' or hora='5' or hora='6' or hora='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumbasen=$resp_r['sumbasen'];
}

$resp = $conexion->query("select SUM(cantidad) as summedn from ing_enf_ter where des='MEDICAMENTOS' and fecha='$fechar'   and id_atencion=$id_atencion and (hora='21' or hora='22' or hora='23' or hora='24' or hora='1' or hora='2' or hora='3' or hora='4' or hora='5' or hora='6' or hora='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $summedn=$resp_r['summedn'];
}

$resp = $conexion->query("select SUM(cantidad) as vian from ing_enf_ter where des='VIA ORAL' and fecha='$fechar'   and id_atencion=$id_atencion and (hora='21' or hora='22' or hora='23' or hora='24' or hora='1' or hora='2' or hora='3' or hora='4' or hora='5' or hora='6' or hora='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vian=$resp_r['vian'];
}

$resp = $conexion->query("select SUM(cantidad) as sumAMINASn from ing_enf_ter where (des='AMINAS') and fecha='$fechar'   and id_atencion=$id_atencion and (hora='21' or hora='22' or hora='23' or hora='24' or hora='1' or hora='2' or hora='3' or hora='4' or hora='5' or hora='6' or hora='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumAMINASn=$resp_r['sumAMINASn'];
}
$resp = $conexion->query("select SUM(cantidad) as sumcargasn from ing_enf_ter where (des='CARGAS') and fecha='$fechar'   and id_atencion=$id_atencion and (hora='21' or hora='22' or hora='23' or hora='24' or hora='1' or hora='2' or hora='3' or hora='4' or hora='5' or hora='6' or hora='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumAMINASn=$resp_r['sumcargasn'];
}

$resp = $conexion->query("select SUM(cantidad) as sumcantidadn from ing_enf_ter where (des='INFUSIONES 1' || des='INFUSIONES 2' || des='INFUSIONES 3' || des='INFUSIONES 4') and fecha='$fechar'   and id_atencion=$id_atencion and (hora='21' or hora='22' or hora='23' or hora='24' or hora='1' or hora='2' or hora='3' or hora='4' or hora='5' or hora='6' or hora='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumcantidadn=$resp_r['sumcantidadn'];
}

$resp = $conexion->query("select SUM(cantidad) as sumn from ing_enf_ter where des='HEMODERIVADOS' and fecha='$fechar'   and id_atencion=$id_atencion and (hora='21' or hora='22' or hora='23' or hora='24' or hora='1' or hora='2' or hora='3' or hora='4' or hora='5' or hora='6' or hora='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumn=$resp_r['sumn'];
}

$resp = $conexion->query("select SUM(cantidad) as sumnutn from ing_enf_ter where (des='NUTRICION PARENTERAL') and fecha='$fechar' and id_atencion=$id_atencion and (hora='21' or hora='22' or hora='23' or hora='24' or hora='1' or hora='2' or hora='3' or hora='4' or hora='5' or hora='6' or hora='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumnutno=$resp_r['sumnutn'];
}

$resp = $conexion->query("select SUM(cantidad) as sumnen from ing_enf_ter where (des='NUTRICION ENTERAL') and fecha='$fechar'   and id_atencion=$id_atencion and (hora='21' or hora='22' or hora='23' or hora='24' or hora='1' or hora='2' or hora='3' or hora='4' or hora='5' or hora='6' or hora='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumnutno=$resp_r['sumnen'];
}

$sumatotaln=$sumbasen+$summedn+$vian+$sumAMINASn+$sumcargasn+$sumcantidadn+$sumn+$sumnutn+$sumnen;

//total global
$totalingresos=$sumatotal+$sumatotalv+$sumatotaln;

$pdf->SetY(93);
$pdf->SetX(131);
$pdf->Cell(77,6, utf8_decode($sumatotaln . ' ML ' . ' Total ingresos: ' . $totalingresos . 'ML'),1,0,'C');


//DIURESIS DIURESIS DIURESIS DIURESIS DIURESIS DIURESIS DIURESIS DIURESIS DIURESIS DIURESIS DIURESIS

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion and des_eg='ORINA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diu8=$resp_r['cant_eg'];
}
if(isset($diu8)){
$pdf->SetY(100);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($diu8),1,0,'C');
}else{
  $pdf->SetY(100);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion and des_eg='ORINA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diu9=$resp_r['cant_eg'];
}
if(isset($diu9)){
$pdf->SetY(100);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($diu9),1,0,'C');
}else{
  $pdf->SetY(100);
$pdf->SetX(47);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion and des_eg='ORINA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diu10=$resp_r['cant_eg'];
}
if(isset($diu10)){
$pdf->SetY(100);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($diu10),1,0,'C');
}else{
  $pdf->SetY(100);
$pdf->SetX(54);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion and des_eg='ORINA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diu11=$resp_r['cant_eg'];
}
if(isset($diu11)){
$pdf->SetY(100);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($diu11),1,0,'C');
}else{
  $pdf->SetY(100);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion and des_eg='ORINA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diu12=$resp_r['cant_eg'];
}
if(isset($diu12)){
$pdf->SetY(100);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($diu12),1,0,'C');
}else{
  $pdf->SetY(100);
$pdf->SetX(68);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion and des_eg='ORINA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diu13=$resp_r['cant_eg'];
}
if(isset($diu13)){
$pdf->SetY(100);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($diu13),1,0,'C');
}else{
  $pdf->SetY(100);
$pdf->SetX(75);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion and des_eg='ORINA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diu14=$resp_r['cant_eg'];
}
if(isset($diu14)){
$pdf->SetY(100);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($diu14),1,0,'C');
}else{
  $pdf->SetY(100);
$pdf->SetX(82);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion and des_eg='ORINA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diu15=$resp_r['cant_eg'];
}
if(isset($diu15)){
$pdf->SetY(100);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($diu15),1,0,'C');
}else{
  $pdf->SetY(100);
$pdf->SetX(89);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion and des_eg='ORINA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diu16=$resp_r['cant_eg'];
}
if(isset($diu16)){
$pdf->SetY(100);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($diu16),1,0,'C');
}else{
  $pdf->SetY(100);
$pdf->SetX(96);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion and des_eg='ORINA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diu17=$resp_r['cant_eg'];
}
if(isset($diu17)){
$pdf->SetY(100);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($diu17),1,0,'C');
}else{
  $pdf->SetY(100);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion and des_eg='ORINA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diu18=$resp_r['cant_eg'];
}
if(isset($diu18)){
$pdf->SetY(100);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($diu18),1,0,'C');
}else{
  $pdf->SetY(100);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion and des_eg='ORINA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diu19=$resp_r['cant_eg'];
}
if(isset($diu19)){
$pdf->SetY(100);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($diu19),1,0,'C');
}else{
  $pdf->SetY(100);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion and des_eg='ORINA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diu20=$resp_r['cant_eg'];
}
if(isset($diu20)){
$pdf->SetY(100);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($diu20),1,0,'C');
}else{
  $pdf->SetY(100);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion and des_eg='ORINA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diu21=$resp_r['cant_eg'];
}
if(isset($diu21)){
$pdf->SetY(100);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($diu21),1,0,'C');
}else{
  $pdf->SetY(100);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion and des_eg='ORINA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diu22=$resp_r['cant_eg'];
}
if(isset($diu22)){
$pdf->SetY(100);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($diu22),1,0,'C');
}else{
  $pdf->SetY(100);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion and des_eg='ORINA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diu23=$resp_r['cant_eg'];
}
if(isset($diu23)){
$pdf->SetY(100);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($diu23),1,0,'C');
}else{
  $pdf->SetY(100);
$pdf->SetX(145);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion and des_eg='ORINA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diu24=$resp_r['cant_eg'];
}
if(isset($diu24)){
$pdf->SetY(100);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($diu24),1,0,'C');
}else{
  $pdf->SetY(100);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion and des_eg='ORINA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diu01=$resp_r['cant_eg'];
}
if(isset($diu01)){
$pdf->SetY(100);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($diu01),1,0,'C');
}else{
  $pdf->SetY(100);
$pdf->SetX(159);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  eg_enf_ter where fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion and des_eg='ORINA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diu02=$resp_r['cant_eg'];
}
if(isset($diu02)){
$pdf->SetY(100);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($diu02),1,0,'C');
}else{
  $pdf->SetY(100);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion and des_eg='ORINA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diu03=$resp_r['cant_eg'];
}
if(isset($diu03)){
$pdf->SetY(100);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($diu03),1,0,'C');
}else{
  $pdf->SetY(100);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion and des_eg='ORINA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diu04=$resp_r['cant_eg'];
}
if(isset($diu04)){
$pdf->SetY(100);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($diu04),1,0,'C');
}else{
  $pdf->SetY(100);
$pdf->SetX(180);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion and des_eg='ORINA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diu05=$resp_r['cant_eg'];
}
if(isset($diu05)){
$pdf->SetY(100);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($diu05),1,0,'C');
}else{
  $pdf->SetY(100);
$pdf->SetX(187);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion and des_eg='ORINA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diu06=$resp_r['cant_eg'];
}
if(isset($diu06)){
$pdf->SetY(100);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($diu06),1,0,'C');
}else{
  $pdf->SetY(100);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion and des_eg='ORINA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diu07=$resp_r['cant_eg'];
}
if(isset($diu07)){
$pdf->SetY(100);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($diu07),1,0,'C');
}else{
  $pdf->SetY(100);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//VOMITO VOMITO VOMITO VOMITO VOMITO VOMITO VOMITO VOMITO VOMITO VOMITO VOMITO

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion and des_eg='VOMITO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evac8=$resp_r['cant_eg'];
}
if(isset($evac8)){
$pdf->SetY(106);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($evac8),1,0,'C');
}else{
  $pdf->SetY(106);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion and des_eg='VOMITO'") or die($conexion->error);
while ($resp_r = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evac9=$resp_r['cant_eg'];
}
if(isset($evac9)){
$pdf->SetY(106);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($evac99),1,0,'C');
}else{
  $pdf->SetY(106);
$pdf->SetX(47);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion and des_eg='VOMITO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evac10=$resp_r['cant_eg'];
}
if(isset($evac10)){
$pdf->SetY(106);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($evac10),1,0,'C');
}else{
  $pdf->SetY(106);
$pdf->SetX(54);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion and des_eg='VOMITO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evac11=$resp_r['cant_eg'];
}
if(isset($evac11)){
$pdf->SetY(106);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($evac11),1,0,'C');
}else{
  $pdf->SetY(106);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion and des_eg='VOMITO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evac12=$resp_r['cant_eg'];
}
if(isset($evac12)){
$pdf->SetY(106);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($evac12),1,0,'C');
}else{
  $pdf->SetY(106);
$pdf->SetX(68);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion and des_eg='VOMITO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evac13=$resp_r['cant_eg'];
}
if(isset($evac13)){
$pdf->SetY(106);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($evac13),1,0,'C');
}else{
  $pdf->SetY(106);
$pdf->SetX(75);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion and des_eg='VOMITO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evac14=$resp_r['cant_eg'];
}
if(isset($evac14)){
$pdf->SetY(106);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($evac14),1,0,'C');
}else{
  $pdf->SetY(106);
$pdf->SetX(82);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion and des_eg='VOMITO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evac15=$resp_r['cant_eg'];
}
if(isset($evac15)){
$pdf->SetY(106);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($evac15),1,0,'C');
}else{
  $pdf->SetY(106);
$pdf->SetX(89);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion and des_eg='VOMITO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evac16=$resp_r['cant_eg'];
}
if(isset($evac16)){
$pdf->SetY(106);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($evac16),1,0,'C');
}else{
  $pdf->SetY(106);
$pdf->SetX(96);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion and des_eg='VOMITO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evac17=$resp_r['cant_eg'];
}
if(isset($evac17)){
$pdf->SetY(106);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($evac17),1,0,'C');
}else{
  $pdf->SetY(106);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion and des_eg='VOMITO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evac18=$resp_r['cant_eg'];
}
if(isset($evac18)){
$pdf->SetY(106);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($evac18),1,0,'C');
}else{
  $pdf->SetY(106);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion and des_eg='VOMITO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evac19=$resp_r['cant_eg'];
}
if(isset($evac19)){
$pdf->SetY(106);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($evac19),1,0,'C');
}else{
  $pdf->SetY(106);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion and des_eg='VOMITO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evac20=$resp_r['cant_eg'];
}
if(isset($evac20)){
$pdf->SetY(106);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($evac20),1,0,'C');
}else{
  $pdf->SetY(106);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion and des_eg='VOMITO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evac21=$resp_r['cant_eg'];
}
if(isset($evac21)){
$pdf->SetY(106);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($evac21),1,0,'C');
}else{
  $pdf->SetY(106);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion and des_eg='VOMITO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evac22=$resp_r['cant_eg'];
}
if(isset($evac22)){
$pdf->SetY(106);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($evac22),1,0,'C');
}else{
  $pdf->SetY(106);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion and des_eg='VOMITO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evac23=$resp_r['cant_eg'];
}
if(isset($evac23)){
$pdf->SetY(106);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($evac23),1,0,'C');
}else{
  $pdf->SetY(106);
$pdf->SetX(145);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion and des_eg='VOMITO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evac24=$resp_r['cant_eg'];
}
if(isset($evac24)){
$pdf->SetY(106);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($evac24),1,0,'C');
}else{
  $pdf->SetY(106);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion and des_eg='VOMITO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evac01=$resp_r['cant_eg'];
}
if(isset($evac01)){
$pdf->SetY(106);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($evac01),1,0,'C');
}else{
  $pdf->SetY(106);
$pdf->SetX(159);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  eg_enf_ter where fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion and des_eg='VOMITO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evac02=$resp_r['cant_eg'];
}
if(isset($evac02)){
$pdf->SetY(106);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($evac02),1,0,'C');
}else{
  $pdf->SetY(106);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion and des_eg='VOMITO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evac03=$resp_r['cant_eg'];
}
if(isset($evac03)){
$pdf->SetY(106);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($evac03),1,0,'C');
}else{
  $pdf->SetY(106);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion and des_eg='VOMITO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evac04=$resp_r['cant_eg'];
}
if(isset($evac04)){
$pdf->SetY(106);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($evac04),1,0,'C');
}else{
  $pdf->SetY(106);
$pdf->SetX(180);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion and des_eg='VOMITO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evac05=$resp_r['cant_eg'];
}
if(isset($evac05)){
$pdf->SetY(106);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($evac05),1,0,'C');
}else{
  $pdf->SetY(106);
$pdf->SetX(187);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion and des_eg='VOMITO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evac06=$resp_r['cant_eg'];
}
if(isset($evac06)){
$pdf->SetY(106);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($evac06),1,0,'C');
}else{
  $pdf->SetY(106);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion and des_eg='VOMITO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evac07=$resp_r['cant_eg'];
}
if(isset($evac07)){
$pdf->SetY(106);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($evac07),1,0,'C');
}else{
  $pdf->SetY(106);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


//EVACUACIONES EVACUACIONES EVACUACIONES EVACUACIONES EVACUACIONES EVACUACIONES EVACUACIONES EVACUACIONES EVACUACIONES EVACUACIONES EVACUACIONES

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion and des_eg='EVACUACIONES'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ev8=$resp_r['cant_eg'];
}
if(isset($ev8)){
$pdf->SetY(109);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($ev8),1,0,'C');
}else{
  $pdf->SetY(109);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion and des_eg='EVACUACIONES'") or die($conexion->error);
while ($resp_r = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ev9=$resp_r['cant_eg'];
}
if(isset($ev9)){
$pdf->SetY(109);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($ev99),1,0,'C');
}else{
  $pdf->SetY(109);
$pdf->SetX(47);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion and des_eg='EVACUACIONES'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ev10=$resp_r['cant_eg'];
}
if(isset($ev10)){
$pdf->SetY(109);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($ev10),1,0,'C');
}else{
  $pdf->SetY(109);
$pdf->SetX(54);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion and des_eg='EVACUACIONES'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ev11=$resp_r['cant_eg'];
}
if(isset($ev11)){
$pdf->SetY(109);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($ev11),1,0,'C');
}else{
  $pdf->SetY(109);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion and des_eg='EVACUACIONES'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ev12=$resp_r['cant_eg'];
}
if(isset($ev12)){
$pdf->SetY(109);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($ev12),1,0,'C');
}else{
  $pdf->SetY(109);
$pdf->SetX(68);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion and des_eg='EVACUACIONES'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ev13=$resp_r['cant_eg'];
}
if(isset($ev13)){
$pdf->SetY(109);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($ev13),1,0,'C');
}else{
  $pdf->SetY(109);
$pdf->SetX(75);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion and des_eg='EVACUACIONES'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ev14=$resp_r['cant_eg'];
}
if(isset($ev14)){
$pdf->SetY(109);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($ev14),1,0,'C');
}else{
  $pdf->SetY(109);
$pdf->SetX(82);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion and des_eg='EVACUACIONES'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ev15=$resp_r['cant_eg'];
}
if(isset($ev15)){
$pdf->SetY(109);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($ev15),1,0,'C');
}else{
  $pdf->SetY(109);
$pdf->SetX(89);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion and des_eg='EVACUACIONES'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ev16=$resp_r['cant_eg'];
}
if(isset($ev16)){
$pdf->SetY(109);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($ev16),1,0,'C');
}else{
  $pdf->SetY(109);
$pdf->SetX(96);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion and des_eg='EVACUACIONES'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ev17=$resp_r['cant_eg'];
}
if(isset($ev17)){
$pdf->SetY(109);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($ev17),1,0,'C');
}else{
  $pdf->SetY(109);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion and des_eg='EVACUACIONES'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ev18=$resp_r['cant_eg'];
}
if(isset($ev18)){
$pdf->SetY(109);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($ev18),1,0,'C');
}else{
  $pdf->SetY(109);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion and des_eg='EVACUACIONES'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ev19=$resp_r['cant_eg'];
}
if(isset($ev19)){
$pdf->SetY(109);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($ev19),1,0,'C');
}else{
  $pdf->SetY(109);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion and des_eg='EVACUACIONES'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ev20=$resp_r['cant_eg'];
}
if(isset($ev20)){
$pdf->SetY(109);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($ev20),1,0,'C');
}else{
  $pdf->SetY(109);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion and des_eg='EVACUACIONES'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ev21=$resp_r['cant_eg'];
}
if(isset($ev21)){
$pdf->SetY(109);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($ev21),1,0,'C');
}else{
  $pdf->SetY(109);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion and des_eg='EVACUACIONES'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ev22=$resp_r['cant_eg'];
}
if(isset($ev22)){
$pdf->SetY(109);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($ev22),1,0,'C');
}else{
  $pdf->SetY(109);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion and des_eg='EVACUACIONES'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ev23=$resp_r['cant_eg'];
}
if(isset($ev23)){
$pdf->SetY(109);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($ev23),1,0,'C');
}else{
  $pdf->SetY(109);
$pdf->SetX(145);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion and des_eg='EVACUACIONES'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ev24=$resp_r['cant_eg'];
}
if(isset($ev24)){
$pdf->SetY(109);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($ev24),1,0,'C');
}else{
  $pdf->SetY(109);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion and des_eg='EVACUACIONES'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ev01=$resp_r['cant_eg'];
}
if(isset($ev01)){
$pdf->SetY(109);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($ev01),1,0,'C');
}else{
  $pdf->SetY(109);
$pdf->SetX(159);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  eg_enf_ter where fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion and des_eg='EVACUACIONES'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ev02=$resp_r['cant_eg'];
}
if(isset($ev02)){
$pdf->SetY(109);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($ev02),1,0,'C');
}else{
  $pdf->SetY(109);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion and des_eg='EVACUACIONES'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ev03=$resp_r['cant_eg'];
}
if(isset($ev03)){
$pdf->SetY(109);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($ev03),1,0,'C');
}else{
  $pdf->SetY(109);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion and des_eg='EVACUACIONES'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ev04=$resp_r['cant_eg'];
}
if(isset($ev04)){
$pdf->SetY(109);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($ev04),1,0,'C');
}else{
  $pdf->SetY(109);
$pdf->SetX(180);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion and des_eg='EVACUACIONES'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ev05=$resp_r['cant_eg'];
}
if(isset($ev05)){
$pdf->SetY(109);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($ev05),1,0,'C');
}else{
  $pdf->SetY(109);
$pdf->SetX(187);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion and des_eg='EVACUACIONES'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ev06=$resp_r['cant_eg'];
}
if(isset($ev06)){
$pdf->SetY(109);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($ev06),1,0,'C');
}else{
  $pdf->SetY(109);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion and des_eg='EVACUACIONES'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ev07=$resp_r['cant_eg'];
}
if(isset($ev07)){
$pdf->SetY(109);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($ev07),1,0,'C');
}else{
  $pdf->SetY(109);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//SANGRADO SANGRADO SANGRADO SANGRADO SANGRADO SANGRADO SANGRADO SANGRADO SANGRADO SANGRADO SANGRADO

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion and des_eg='SANGRADO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang8=$resp_r['cant_eg'];
}
if(isset($sang8)){
$pdf->SetY(112);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($sang8),1,0,'C');
}else{
  $pdf->SetY(112);
  $pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion and des_eg='SANGRADO'") or die($conexion->error);
while ($resp_r = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang9=$resp_r['cant_eg'];
}
if(isset($sang9)){
$pdf->SetY(112);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($sang99),1,0,'C');
}else{
  $pdf->SetY(112);
$pdf->SetX(47);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion and des_eg='SANGRADO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang10=$resp_r['cant_eg'];
}
if(isset($sang10)){
$pdf->SetY(112);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($sang10),1,0,'C');
}else{
  $pdf->SetY(112);
$pdf->SetX(54);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion and des_eg='SANGRADO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang11=$resp_r['cant_eg'];
}
if(isset($sang11)){
$pdf->SetY(112);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($sang11),1,0,'C');
}else{
  $pdf->SetY(112);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion and des_eg='SANGRADO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang12=$resp_r['cant_eg'];
}
if(isset($sang12)){
$pdf->SetY(112);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($sang12),1,0,'C');
}else{
  $pdf->SetY(112);
$pdf->SetX(68);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion and des_eg='SANGRADO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang13=$resp_r['cant_eg'];
}
if(isset($sang13)){
$pdf->SetY(112);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($sang13),1,0,'C');
}else{
  $pdf->SetY(112);
$pdf->SetX(75);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion and des_eg='SANGRADO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang14=$resp_r['cant_eg'];
}
if(isset($sang14)){
$pdf->SetY(112);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($sang14),1,0,'C');
}else{
  $pdf->SetY(112);
$pdf->SetX(82);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion and des_eg='SANGRADO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang15=$resp_r['cant_eg'];
}
if(isset($sang15)){
$pdf->SetY(112);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($sang15),1,0,'C');
}else{
  $pdf->SetY(112);
$pdf->SetX(89);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion and des_eg='SANGRADO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang16=$resp_r['cant_eg'];
}
if(isset($sang16)){
$pdf->SetY(112);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($sang16),1,0,'C');
}else{
  $pdf->SetY(112);
$pdf->SetX(96);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion and des_eg='SANGRADO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang17=$resp_r['cant_eg'];
}
if(isset($sang17)){
$pdf->SetY(112);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($sang17),1,0,'C');
}else{
  $pdf->SetY(112);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion and des_eg='SANGRADO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang18=$resp_r['cant_eg'];
}
if(isset($sang18)){
$pdf->SetY(112);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($sang18),1,0,'C');
}else{
  $pdf->SetY(112);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion and des_eg='SANGRADO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang19=$resp_r['cant_eg'];
}
if(isset($sang19)){
$pdf->SetY(112);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($sang19),1,0,'C');
}else{
  $pdf->SetY(112);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion and des_eg='SANGRADO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang20=$resp_r['cant_eg'];
}
if(isset($sang20)){
$pdf->SetY(112);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($sang20),1,0,'C');
}else{
  $pdf->SetY(112);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion and des_eg='SANGRADO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang21=$resp_r['cant_eg'];
}
if(isset($sang21)){
$pdf->SetY(112);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($sang21),1,0,'C');
}else{
  $pdf->SetY(112);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion and des_eg='SANGRADO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang22=$resp_r['cant_eg'];
}
if(isset($sang22)){
$pdf->SetY(112);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($sang22),1,0,'C');
}else{
  $pdf->SetY(112);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion and des_eg='SANGRADO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang23=$resp_r['cant_eg'];
}
if(isset($sang23)){
$pdf->SetY(112);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($sang23),1,0,'C');
}else{
  $pdf->SetY(112);
$pdf->SetX(145);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion and des_eg='SANGRADO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang24=$resp_r['cant_eg'];
}
if(isset($sang24)){
$pdf->SetY(112);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($sang24),1,0,'C');
}else{
  $pdf->SetY(112);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion and des_eg='SANGRADO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang01=$resp_r['cant_eg'];
}
if(isset($sang01)){
$pdf->SetY(112);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($sang01),1,0,'C');
}else{
  $pdf->SetY(112);
$pdf->SetX(159);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  eg_enf_ter where fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion and des_eg='SANGRADO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang02=$resp_r['cant_eg'];
}
if(isset($sang02)){
$pdf->SetY(112);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($sang02),1,0,'C');
}else{
  $pdf->SetY(112);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion and des_eg='SANGRADO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang03=$resp_r['cant_eg'];
}
if(isset($sang03)){
$pdf->SetY(112);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($sang03),1,0,'C');
}else{
  $pdf->SetY(112);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion and des_eg='SANGRADO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang04=$resp_r['cant_eg'];
}
if(isset($sang04)){
$pdf->SetY(112);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($sang04),1,0,'C');
}else{
  $pdf->SetY(112);
$pdf->SetX(180);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion and des_eg='SANGRADO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang05=$resp_r['cant_eg'];
}
if(isset($sang05)){
$pdf->SetY(112);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($sang05),1,0,'C');
}else{
  $pdf->SetY(112);
$pdf->SetX(187);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion and des_eg='SANGRADO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang06=$resp_r['cant_eg'];
}
if(isset($sang06)){
$pdf->SetY(112);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($sang06),1,0,'C');
}else{
  $pdf->SetY(112);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion and des_eg='SANGRADO'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang07=$resp_r['cant_eg'];
}
if(isset($sang07)){
$pdf->SetY(112);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($sang07),1,0,'C');
}else{
  $pdf->SetY(112);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//PLEUROVAC PLEUROVAC PLEUROVAC PLEUROVAC PLEUROVAC PLEUROVAC PLEUROVAC PLEUROVAC PLEUROVAC PLEUROVAC PLEUROVAC

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion and des_eg='PLEUROVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang8=$resp_r['cant_eg'];
}
if(isset($sang8)){
$pdf->SetY(115);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($sang8),1,0,'C');
}else{
  $pdf->SetY(115);
  $pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion and des_eg='PLEUROVAC'") or die($conexion->error);
while ($resp_r = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang9=$resp_r['cant_eg'];
}
if(isset($sang9)){
$pdf->SetY(115);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($sang99),1,0,'C');
}else{
  $pdf->SetY(115);
$pdf->SetX(47);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion and des_eg='PLEUROVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang10=$resp_r['cant_eg'];
}
if(isset($sang10)){
$pdf->SetY(115);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($sang10),1,0,'C');
}else{
  $pdf->SetY(115);
$pdf->SetX(54);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion and des_eg='PLEUROVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang11=$resp_r['cant_eg'];
}
if(isset($sang11)){
$pdf->SetY(115);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($sang11),1,0,'C');
}else{
  $pdf->SetY(115);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion and des_eg='PLEUROVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang12=$resp_r['cant_eg'];
}
if(isset($sang12)){
$pdf->SetY(115);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($sang12),1,0,'C');
}else{
  $pdf->SetY(115);
$pdf->SetX(68);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion and des_eg='PLEUROVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang13=$resp_r['cant_eg'];
}
if(isset($sang13)){
$pdf->SetY(115);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($sang13),1,0,'C');
}else{
  $pdf->SetY(115);
$pdf->SetX(75);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion and des_eg='PLEUROVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang14=$resp_r['cant_eg'];
}
if(isset($sang14)){
$pdf->SetY(115);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($sang14),1,0,'C');
}else{
  $pdf->SetY(115);
$pdf->SetX(82);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion and des_eg='PLEUROVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang15=$resp_r['cant_eg'];
}
if(isset($sang15)){
$pdf->SetY(115);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($sang15),1,0,'C');
}else{
  $pdf->SetY(115);
$pdf->SetX(89);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion and des_eg='PLEUROVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang16=$resp_r['cant_eg'];
}
if(isset($sang16)){
$pdf->SetY(115);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($sang16),1,0,'C');
}else{
  $pdf->SetY(115);
$pdf->SetX(96);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion and des_eg='PLEUROVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang17=$resp_r['cant_eg'];
}
if(isset($sang17)){
$pdf->SetY(115);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($sang17),1,0,'C');
}else{
  $pdf->SetY(115);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion and des_eg='PLEUROVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang18=$resp_r['cant_eg'];
}
if(isset($sang18)){
$pdf->SetY(115);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($sang18),1,0,'C');
}else{
  $pdf->SetY(115);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion and des_eg='PLEUROVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang19=$resp_r['cant_eg'];
}
if(isset($sang19)){
$pdf->SetY(115);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($sang19),1,0,'C');
}else{
  $pdf->SetY(115);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion and des_eg='PLEUROVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang20=$resp_r['cant_eg'];
}
if(isset($sang20)){
$pdf->SetY(115);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($sang20),1,0,'C');
}else{
  $pdf->SetY(115);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion and des_eg='PLEUROVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang21=$resp_r['cant_eg'];
}
if(isset($sang21)){
$pdf->SetY(115);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($sang21),1,0,'C');
}else{
  $pdf->SetY(115);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion and des_eg='PLEUROVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang22=$resp_r['cant_eg'];
}
if(isset($sang22)){
$pdf->SetY(115);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($sang22),1,0,'C');
}else{
  $pdf->SetY(115);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion and des_eg='PLEUROVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang23=$resp_r['cant_eg'];
}
if(isset($sang23)){
$pdf->SetY(115);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($sang23),1,0,'C');
}else{
  $pdf->SetY(115);
$pdf->SetX(145);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion and des_eg='PLEUROVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang24=$resp_r['cant_eg'];
}
if(isset($sang24)){
$pdf->SetY(115);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($sang24),1,0,'C');
}else{
  $pdf->SetY(115);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion and des_eg='PLEUROVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang01=$resp_r['cant_eg'];
}
if(isset($sang01)){
$pdf->SetY(115);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($sang01),1,0,'C');
}else{
  $pdf->SetY(115);
$pdf->SetX(159);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  eg_enf_ter where fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion and des_eg='PLEUROVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang02=$resp_r['cant_eg'];
}
if(isset($sang02)){
$pdf->SetY(115);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($sang02),1,0,'C');
}else{
  $pdf->SetY(115);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion and des_eg='PLEUROVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang03=$resp_r['cant_eg'];
}
if(isset($sang03)){
$pdf->SetY(115);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($sang03),1,0,'C');
}else{
  $pdf->SetY(115);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion and des_eg='PLEUROVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang04=$resp_r['cant_eg'];
}
if(isset($sang04)){
$pdf->SetY(115);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($sang04),1,0,'C');
}else{
  $pdf->SetY(115);
$pdf->SetX(180);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion and des_eg='PLEUROVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang05=$resp_r['cant_eg'];
}
if(isset($sang05)){
$pdf->SetY(115);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($sang05),1,0,'C');
}else{
  $pdf->SetY(115);
$pdf->SetX(187);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion and des_eg='PLEUROVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang06=$resp_r['cant_eg'];
}
if(isset($sang06)){
$pdf->SetY(115);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($sang06),1,0,'C');
}else{
  $pdf->SetY(115);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion and des_eg='PLEUROVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sang07=$resp_r['cant_eg'];
}
if(isset($sang07)){
$pdf->SetY(115);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($sang07),1,0,'C');
}else{
  $pdf->SetY(115);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}




//SONDA NASOGASTRICA SONDA NASOGASTRICA SONDA NASOGASTRICA SONDA NASOGASTRICA SONDA NASOGASTRICA SONDA NASOGASTRICA SONDA NASOGASTRICA SONDA NASOGASTRICA 

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion and des_eg='SONDA NASOGASTRICA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sonn8=$resp_r['cant_eg'];
}
if(isset($sonn8)){
$pdf->SetY(118);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($sonn8),1,0,'C');
}else{
  $pdf->SetY(118);
  $pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion and des_eg='SONDA NASOGASTRICA'") or die($conexion->error);
while ($resp_r = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sonn9=$resp_r['cant_eg'];
}
if(isset($sonn9)){
$pdf->SetY(118);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($sonn99),1,0,'C');
}else{
  $pdf->SetY(118);
$pdf->SetX(47);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion and des_eg='SONDA NASOGASTRICA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sonn10=$resp_r['cant_eg'];
}
if(isset($sonn10)){
$pdf->SetY(118);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($sonn10),1,0,'C');
}else{
  $pdf->SetY(118);
$pdf->SetX(54);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion and des_eg='SONDA NASOGASTRICA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sonn11=$resp_r['cant_eg'];
}
if(isset($sonn11)){
$pdf->SetY(118);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($sonn11),1,0,'C');
}else{
  $pdf->SetY(118);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion and des_eg='SONDA NASOGASTRICA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sonn12=$resp_r['cant_eg'];
}
if(isset($sonn12)){
$pdf->SetY(118);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($sonn12),1,0,'C');
}else{
  $pdf->SetY(118);
$pdf->SetX(68);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion and des_eg='SONDA NASOGASTRICA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sonn13=$resp_r['cant_eg'];
}
if(isset($sonn13)){
$pdf->SetY(118);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($sonn13),1,0,'C');
}else{
  $pdf->SetY(118);
$pdf->SetX(75);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion and des_eg='SONDA NASOGASTRICA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sonn14=$resp_r['cant_eg'];
}
if(isset($sonn14)){
$pdf->SetY(118);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($sonn14),1,0,'C');
}else{
  $pdf->SetY(118);
$pdf->SetX(82);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion and des_eg='SONDA NASOGASTRICA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sonn15=$resp_r['cant_eg'];
}
if(isset($sonn15)){
$pdf->SetY(118);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($sonn15),1,0,'C');
}else{
  $pdf->SetY(118);
$pdf->SetX(89);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion and des_eg='SONDA NASOGASTRICA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sonn16=$resp_r['cant_eg'];
}
if(isset($sonn16)){
$pdf->SetY(118);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($sonn16),1,0,'C');
}else{
  $pdf->SetY(118);
$pdf->SetX(96);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion and des_eg='SONDA NASOGASTRICA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sonn17=$resp_r['cant_eg'];
}
if(isset($sonn17)){
$pdf->SetY(118);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($sonn17),1,0,'C');
}else{
  $pdf->SetY(118);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion and des_eg='SONDA NASOGASTRICA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sonn18=$resp_r['cant_eg'];
}
if(isset($sonn18)){
$pdf->SetY(118);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($sonn18),1,0,'C');
}else{
  $pdf->SetY(118);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion and des_eg='SONDA NASOGASTRICA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sonn19=$resp_r['cant_eg'];
}
if(isset($sonn19)){
$pdf->SetY(118);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($sonn19),1,0,'C');
}else{
  $pdf->SetY(118);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion and des_eg='SONDA NASOGASTRICA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sonn20=$resp_r['cant_eg'];
}
if(isset($sonn20)){
$pdf->SetY(118);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($sonn20),1,0,'C');
}else{
  $pdf->SetY(118);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion and des_eg='SONDA NASOGASTRICA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sonn21=$resp_r['cant_eg'];
}
if(isset($sonn21)){
$pdf->SetY(118);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($sonn21),1,0,'C');
}else{
  $pdf->SetY(118);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion and des_eg='SONDA NASOGASTRICA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sonn22=$resp_r['cant_eg'];
}
if(isset($sonn22)){
$pdf->SetY(118);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($sonn22),1,0,'C');
}else{
  $pdf->SetY(118);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion and des_eg='SONDA NASOGASTRICA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sonn23=$resp_r['cant_eg'];
}
if(isset($sonn23)){
$pdf->SetY(118);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($sonn23),1,0,'C');
}else{
  $pdf->SetY(118);
$pdf->SetX(145);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion and des_eg='SONDA NASOGASTRICA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sonn24=$resp_r['cant_eg'];
}
if(isset($sonn24)){
$pdf->SetY(118);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($sonn24),1,0,'C');
}else{
  $pdf->SetY(118);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion and des_eg='SONDA NASOGASTRICA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sonn01=$resp_r['cant_eg'];
}
if(isset($sonn01)){
$pdf->SetY(118);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($sonn01),1,0,'C');
}else{
  $pdf->SetY(118);
$pdf->SetX(159);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  eg_enf_ter where fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion and des_eg='SONDA NASOGASTRICA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sonn02=$resp_r['cant_eg'];
}
if(isset($sonn02)){
$pdf->SetY(118);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($sonn02),1,0,'C');
}else{
  $pdf->SetY(118);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion and des_eg='SONDA NASOGASTRICA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sonn03=$resp_r['cant_eg'];
}
if(isset($sonn03)){
$pdf->SetY(118);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($sonn03),1,0,'C');
}else{
  $pdf->SetY(118);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion and des_eg='SONDA NASOGASTRICA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sonn04=$resp_r['cant_eg'];
}
if(isset($sonn04)){
$pdf->SetY(118);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($sonn04),1,0,'C');
}else{
  $pdf->SetY(118);
$pdf->SetX(180);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion and des_eg='SONDA NASOGASTRICA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sonn05=$resp_r['cant_eg'];
}
if(isset($sonn05)){
$pdf->SetY(118);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($sonn05),1,0,'C');
}else{
  $pdf->SetY(118);
$pdf->SetX(187);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion and des_eg='SONDA NASOGASTRICA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sonn06=$resp_r['cant_eg'];
}
if(isset($sonn06)){
$pdf->SetY(118);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($sonn06),1,0,'C');
}else{
  $pdf->SetY(118);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion and des_eg='SONDA NASOGASTRICA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sonn07=$resp_r['cant_eg'];
}
if(isset($sonn07)){
$pdf->SetY(118);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($sonn07),1,0,'C');
}else{
  $pdf->SetY(118);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//SONDA T SONDA T SONDA T SONDA T SONDA T SONDA T SONDA T SONDA T SONDA T SONDA T SONDA T

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion and des_eg='SONDA T'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sont8=$resp_r['cant_eg'];
}
if(isset($sont8)){
$pdf->SetY(121);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($sont8),1,0,'C');
}else{
  $pdf->SetY(121);
  $pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion and des_eg='SONDA T'") or die($conexion->error);
while ($resp_r = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sont9=$resp_r['cant_eg'];
}
if(isset($sont9)){
$pdf->SetY(121);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($sont99),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(47);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion and des_eg='SONDA T'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sont10=$resp_r['cant_eg'];
}
if(isset($sont10)){
$pdf->SetY(121);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($sont10),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(54);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion and des_eg='SONDA T'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sont11=$resp_r['cant_eg'];
}
if(isset($sont11)){
$pdf->SetY(121);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($sont11),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion and des_eg='SONDA T'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sont12=$resp_r['cant_eg'];
}
if(isset($sont12)){
$pdf->SetY(121);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($sont12),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(68);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion and des_eg='SONDA T'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sont13=$resp_r['cant_eg'];
}
if(isset($sont13)){
$pdf->SetY(121);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($sont13),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(75);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion and des_eg='SONDA T'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sont14=$resp_r['cant_eg'];
}
if(isset($sont14)){
$pdf->SetY(121);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($sont14),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(82);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion and des_eg='SONDA T'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sont15=$resp_r['cant_eg'];
}
if(isset($sont15)){
$pdf->SetY(121);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($sont15),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(89);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion and des_eg='SONDA T'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sont16=$resp_r['cant_eg'];
}
if(isset($sont16)){
$pdf->SetY(121);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($sont16),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(96);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion and des_eg='SONDA T'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sont17=$resp_r['cant_eg'];
}
if(isset($sont17)){
$pdf->SetY(121);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($sont17),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion and des_eg='SONDA T'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sont18=$resp_r['cant_eg'];
}
if(isset($sont18)){
$pdf->SetY(121);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($sont18),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion and des_eg='SONDA T'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sont19=$resp_r['cant_eg'];
}
if(isset($sont19)){
$pdf->SetY(121);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($sont19),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion and des_eg='SONDA T'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sont20=$resp_r['cant_eg'];
}
if(isset($sont20)){
$pdf->SetY(121);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($sont20),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion and des_eg='SONDA T'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sont21=$resp_r['cant_eg'];
}
if(isset($sont21)){
$pdf->SetY(121);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($sont21),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion and des_eg='SONDA T'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sont22=$resp_r['cant_eg'];
}
if(isset($sont22)){
$pdf->SetY(121);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($sont22),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion and des_eg='SONDA T'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sont23=$resp_r['cant_eg'];
}
if(isset($sont23)){
$pdf->SetY(121);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($sont23),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(145);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion and des_eg='SONDA T'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sont24=$resp_r['cant_eg'];
}
if(isset($sont24)){
$pdf->SetY(121);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($sont24),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion and des_eg='SONDA T'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sont01=$resp_r['cant_eg'];
}
if(isset($sont01)){
$pdf->SetY(121);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($sont01),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(159);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  eg_enf_ter where fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion and des_eg='SONDA T'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sont02=$resp_r['cant_eg'];
}
if(isset($sont02)){
$pdf->SetY(121);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($sont02),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion and des_eg='SONDA T'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sont03=$resp_r['cant_eg'];
}
if(isset($sont03)){
$pdf->SetY(121);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($sont03),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion and des_eg='SONDA T'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sont04=$resp_r['cant_eg'];
}
if(isset($sont04)){
$pdf->SetY(121);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($sont04),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(180);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion and des_eg='SONDA T'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sont05=$resp_r['cant_eg'];
}
if(isset($sont05)){
$pdf->SetY(121);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($sont05),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(187);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion and des_eg='SONDA T'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sont06=$resp_r['cant_eg'];
}
if(isset($sont06)){
$pdf->SetY(121);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($sont06),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion and des_eg='SONDA T'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sont07=$resp_r['cant_eg'];
}
if(isset($sont07)){
$pdf->SetY(121);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($sont07),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


//BIOVAC IZQ BIOVAC IZQ BIOVAC IZQ BIOVAC IZQ BIOVAC IZQ BIOVAC IZQ BIOVAC IZQ BIOVAC IZQ 

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion and des_eg='BIOVAC IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioizq8=$resp_r['cant_eg'];
}
if(isset($bioizq8)){
$pdf->SetY(124);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($bioizq8),1,0,'C');
}else{
  $pdf->SetY(124);
  $pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion and des_eg='BIOVAC IZQ'") or die($conexion->error);
while ($resp_r = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioizq9=$resp_r['cant_eg'];
}
if(isset($bioizq9)){
$pdf->SetY(124);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($bioizq99),1,0,'C');
}else{
  $pdf->SetY(124);
$pdf->SetX(47);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion and des_eg='BIOVAC IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioizq10=$resp_r['cant_eg'];
}
if(isset($bioizq10)){
$pdf->SetY(124);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($bioizq10),1,0,'C');
}else{
  $pdf->SetY(124);
$pdf->SetX(54);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion and des_eg='BIOVAC IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioizq11=$resp_r['cant_eg'];
}
if(isset($bioizq11)){
$pdf->SetY(124);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($bioizq11),1,0,'C');
}else{
  $pdf->SetY(124);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion and des_eg='BIOVAC IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioizq12=$resp_r['cant_eg'];
}
if(isset($bioizq12)){
$pdf->SetY(124);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($bioizq12),1,0,'C');
}else{
  $pdf->SetY(124);
$pdf->SetX(68);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion and des_eg='BIOVAC IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioizq13=$resp_r['cant_eg'];
}
if(isset($bioizq13)){
$pdf->SetY(124);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($bioizq13),1,0,'C');
}else{
  $pdf->SetY(124);
$pdf->SetX(75);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion and des_eg='BIOVAC IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioizq14=$resp_r['cant_eg'];
}
if(isset($bioizq14)){
$pdf->SetY(124);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($bioizq14),1,0,'C');
}else{
  $pdf->SetY(124);
$pdf->SetX(82);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion and des_eg='BIOVAC IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioizq15=$resp_r['cant_eg'];
}
if(isset($bioizq15)){
$pdf->SetY(124);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($bioizq15),1,0,'C');
}else{
  $pdf->SetY(124);
$pdf->SetX(89);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion and des_eg='BIOVAC IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioizq16=$resp_r['cant_eg'];
}
if(isset($bioizq16)){
$pdf->SetY(124);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($bioizq16),1,0,'C');
}else{
  $pdf->SetY(124);
$pdf->SetX(96);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion and des_eg='BIOVAC IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioizq17=$resp_r['cant_eg'];
}
if(isset($bioizq17)){
$pdf->SetY(124);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($bioizq17),1,0,'C');
}else{
  $pdf->SetY(124);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion and des_eg='BIOVAC IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioizq18=$resp_r['cant_eg'];
}
if(isset($bioizq18)){
$pdf->SetY(124);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($bioizq18),1,0,'C');
}else{
  $pdf->SetY(124);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion and des_eg='BIOVAC IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioizq19=$resp_r['cant_eg'];
}
if(isset($bioizq19)){
$pdf->SetY(124);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($bioizq19),1,0,'C');
}else{
  $pdf->SetY(124);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion and des_eg='BIOVAC IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioizq20=$resp_r['cant_eg'];
}
if(isset($bioizq20)){
$pdf->SetY(124);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($bioizq20),1,0,'C');
}else{
  $pdf->SetY(124);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion and des_eg='BIOVAC IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioizq21=$resp_r['cant_eg'];
}
if(isset($bioizq21)){
$pdf->SetY(124);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($bioizq21),1,0,'C');
}else{
  $pdf->SetY(124);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion and des_eg='BIOVAC IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioizq22=$resp_r['cant_eg'];
}
if(isset($bioizq22)){
$pdf->SetY(124);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($bioizq22),1,0,'C');
}else{
  $pdf->SetY(124);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion and des_eg='BIOVAC IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioizq23=$resp_r['cant_eg'];
}
if(isset($bioizq23)){
$pdf->SetY(124);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($bioizq23),1,0,'C');
}else{
  $pdf->SetY(124);
$pdf->SetX(145);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion and des_eg='BIOVAC IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioizq24=$resp_r['cant_eg'];
}
if(isset($bioizq24)){
$pdf->SetY(124);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($bioizq24),1,0,'C');
}else{
  $pdf->SetY(124);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion and des_eg='BIOVAC IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioizq01=$resp_r['cant_eg'];
}
if(isset($bioizq01)){
$pdf->SetY(124);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($bioizq01),1,0,'C');
}else{
  $pdf->SetY(124);
$pdf->SetX(159);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  eg_enf_ter where fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion and des_eg='BIOVAC IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioizq02=$resp_r['cant_eg'];
}
if(isset($bioizq02)){
$pdf->SetY(124);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($bioizq02),1,0,'C');
}else{
  $pdf->SetY(124);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion and des_eg='BIOVAC IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioizq03=$resp_r['cant_eg'];
}
if(isset($bioizq03)){
$pdf->SetY(124);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($bioizq03),1,0,'C');
}else{
  $pdf->SetY(124);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion and des_eg='BIOVAC IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioizq04=$resp_r['cant_eg'];
}
if(isset($bioizq04)){
$pdf->SetY(124);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($bioizq04),1,0,'C');
}else{
  $pdf->SetY(124);
$pdf->SetX(180);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion and des_eg='BIOVAC IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioizq05=$resp_r['cant_eg'];
}
if(isset($bioizq05)){
$pdf->SetY(124);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($bioizq05),1,0,'C');
}else{
  $pdf->SetY(124);
$pdf->SetX(187);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion and des_eg='BIOVAC IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioizq06=$resp_r['cant_eg'];
}
if(isset($bioizq06)){
$pdf->SetY(124);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($bioizq06),1,0,'C');
}else{
  $pdf->SetY(124);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion and des_eg='BIOVAC IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioizq07=$resp_r['cant_eg'];
}
if(isset($bioizq07)){
$pdf->SetY(124);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($bioizq07),1,0,'C');
}else{
  $pdf->SetY(124);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


//BIOVAC DER BIOVAC DER BIOVAC DER BIOVAC DER BIOVAC DER BIOVAC DER BIOVAC DER BIOVAC DER 

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion and des_eg='BIOVAC DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioder8=$resp_r['cant_eg'];
}
if(isset($bioder8)){
$pdf->SetY(127);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($bioder8),1,0,'C');
}else{
  $pdf->SetY(127);
  $pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion and des_eg='BIOVAC DER'") or die($conexion->error);
while ($resp_r = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioder9=$resp_r['cant_eg'];
}
if(isset($bioder9)){
$pdf->SetY(127);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($bioder99),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(47);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion and des_eg='BIOVAC DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioder10=$resp_r['cant_eg'];
}
if(isset($bioder10)){
$pdf->SetY(127);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($bioder10),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(54);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion and des_eg='BIOVAC DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioder11=$resp_r['cant_eg'];
}
if(isset($bioder11)){
$pdf->SetY(127);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($bioder11),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion and des_eg='BIOVAC DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioder12=$resp_r['cant_eg'];
}
if(isset($bioder12)){
$pdf->SetY(127);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($bioder12),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(68);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion and des_eg='BIOVAC DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioder13=$resp_r['cant_eg'];
}
if(isset($bioder13)){
$pdf->SetY(127);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($bioder13),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(75);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion and des_eg='BIOVAC DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioder14=$resp_r['cant_eg'];
}
if(isset($bioder14)){
$pdf->SetY(127);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($bioder14),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(82);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion and des_eg='BIOVAC DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioder15=$resp_r['cant_eg'];
}
if(isset($bioder15)){
$pdf->SetY(127);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($bioder15),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(89);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion and des_eg='BIOVAC DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioder16=$resp_r['cant_eg'];
}
if(isset($bioder16)){
$pdf->SetY(127);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($bioder16),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(96);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion and des_eg='BIOVAC DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioder17=$resp_r['cant_eg'];
}
if(isset($bioder17)){
$pdf->SetY(127);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($bioder17),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion and des_eg='BIOVAC DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioder18=$resp_r['cant_eg'];
}
if(isset($bioder18)){
$pdf->SetY(127);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($bioder18),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion and des_eg='BIOVAC DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioder19=$resp_r['cant_eg'];
}
if(isset($bioder19)){
$pdf->SetY(127);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($bioder19),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion and des_eg='BIOVAC DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioder20=$resp_r['cant_eg'];
}
if(isset($bioder20)){
$pdf->SetY(127);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($bioder20),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion and des_eg='BIOVAC DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioder21=$resp_r['cant_eg'];
}
if(isset($bioder21)){
$pdf->SetY(127);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($bioder21),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion and des_eg='BIOVAC DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioder22=$resp_r['cant_eg'];
}
if(isset($bioder22)){
$pdf->SetY(127);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($bioder22),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion and des_eg='BIOVAC DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioder23=$resp_r['cant_eg'];
}
if(isset($bioder23)){
$pdf->SetY(127);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($bioder23),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(145);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion and des_eg='BIOVAC DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioder24=$resp_r['cant_eg'];
}
if(isset($bioder24)){
$pdf->SetY(127);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($bioder24),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion and des_eg='BIOVAC DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioder01=$resp_r['cant_eg'];
}
if(isset($bioder01)){
$pdf->SetY(127);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($bioder01),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(159);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  eg_enf_ter where fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion and des_eg='BIOVAC DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioder02=$resp_r['cant_eg'];
}
if(isset($bioder02)){
$pdf->SetY(127);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($bioder02),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion and des_eg='BIOVAC DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioder03=$resp_r['cant_eg'];
}
if(isset($bioder03)){
$pdf->SetY(127);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($bioder03),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion and des_eg='BIOVAC DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioder04=$resp_r['cant_eg'];
}
if(isset($bioder04)){
$pdf->SetY(127);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($bioder04),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(180);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion and des_eg='BIOVAC DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioder05=$resp_r['cant_eg'];
}
if(isset($bioder05)){
$pdf->SetY(127);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($bioder05),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(187);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion and des_eg='BIOVAC DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioder06=$resp_r['cant_eg'];
}
if(isset($bioder06)){
$pdf->SetY(127);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($bioder06),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion and des_eg='BIOVAC DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioder07=$resp_r['cant_eg'];
}
if(isset($bioder07)){
$pdf->SetY(127);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($bioder07),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}



//PENROSE IZQ PENROSE IZQ PENROSE IZQ PENROSE IZQ PENROSE IZQ PENROSE IZQ PENROSE IZQ PENROSE IZQ 

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion and des_eg='PENROSE IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $penizq8=$resp_r['cant_eg'];
}
if(isset($penizq8)){
$pdf->SetY(130);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($penizq8),1,0,'C');
}else{
  $pdf->SetY(130);
  $pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion and des_eg='PENROSE IZQ'") or die($conexion->error);
while ($resp_r = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $penizq9=$resp_r['cant_eg'];
}
if(isset($penizq9)){
$pdf->SetY(130);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($penizq99),1,0,'C');
}else{
  $pdf->SetY(130);
$pdf->SetX(47);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion and des_eg='PENROSE IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $penizq10=$resp_r['cant_eg'];
}
if(isset($penizq10)){
$pdf->SetY(130);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($penizq10),1,0,'C');
}else{
  $pdf->SetY(130);
$pdf->SetX(54);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion and des_eg='PENROSE IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $penizq11=$resp_r['cant_eg'];
}
if(isset($penizq11)){
$pdf->SetY(130);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($penizq11),1,0,'C');
}else{
  $pdf->SetY(130);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion and des_eg='PENROSE IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $penizq12=$resp_r['cant_eg'];
}
if(isset($penizq12)){
$pdf->SetY(130);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($penizq12),1,0,'C');
}else{
  $pdf->SetY(130);
$pdf->SetX(68);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion and des_eg='PENROSE IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $penizq13=$resp_r['cant_eg'];
}
if(isset($penizq13)){
$pdf->SetY(130);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($penizq13),1,0,'C');
}else{
  $pdf->SetY(130);
$pdf->SetX(75);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion and des_eg='PENROSE IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $penizq14=$resp_r['cant_eg'];
}
if(isset($penizq14)){
$pdf->SetY(130);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($penizq14),1,0,'C');
}else{
  $pdf->SetY(130);
$pdf->SetX(82);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion and des_eg='PENROSE IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $penizq15=$resp_r['cant_eg'];
}
if(isset($penizq15)){
$pdf->SetY(130);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($penizq15),1,0,'C');
}else{
  $pdf->SetY(130);
$pdf->SetX(89);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion and des_eg='PENROSE IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $penizq16=$resp_r['cant_eg'];
}
if(isset($penizq16)){
$pdf->SetY(130);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($penizq16),1,0,'C');
}else{
  $pdf->SetY(130);
$pdf->SetX(96);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion and des_eg='PENROSE IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $penizq17=$resp_r['cant_eg'];
}
if(isset($penizq17)){
$pdf->SetY(130);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($penizq17),1,0,'C');
}else{
  $pdf->SetY(130);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion and des_eg='PENROSE IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $penizq18=$resp_r['cant_eg'];
}
if(isset($penizq18)){
$pdf->SetY(130);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($penizq18),1,0,'C');
}else{
  $pdf->SetY(130);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion and des_eg='PENROSE IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $penizq19=$resp_r['cant_eg'];
}
if(isset($penizq19)){
$pdf->SetY(130);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($penizq19),1,0,'C');
}else{
  $pdf->SetY(130);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion and des_eg='PENROSE IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $penizq20=$resp_r['cant_eg'];
}
if(isset($penizq20)){
$pdf->SetY(130);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($penizq20),1,0,'C');
}else{
  $pdf->SetY(130);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion and des_eg='PENROSE IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $penizq21=$resp_r['cant_eg'];
}
if(isset($penizq21)){
$pdf->SetY(130);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($penizq21),1,0,'C');
}else{
  $pdf->SetY(130);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion and des_eg='PENROSE IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $penizq22=$resp_r['cant_eg'];
}
if(isset($penizq22)){
$pdf->SetY(130);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($penizq22),1,0,'C');
}else{
  $pdf->SetY(130);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion and des_eg='PENROSE IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $penizq23=$resp_r['cant_eg'];
}
if(isset($penizq23)){
$pdf->SetY(130);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($penizq23),1,0,'C');
}else{
  $pdf->SetY(130);
$pdf->SetX(145);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion and des_eg='PENROSE IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $penizq24=$resp_r['cant_eg'];
}
if(isset($penizq24)){
$pdf->SetY(130);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($penizq24),1,0,'C');
}else{
  $pdf->SetY(130);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion and des_eg='PENROSE IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $penizq01=$resp_r['cant_eg'];
}
if(isset($penizq01)){
$pdf->SetY(130);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($penizq01),1,0,'C');
}else{
  $pdf->SetY(130);
$pdf->SetX(159);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  eg_enf_ter where fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion and des_eg='PENROSE IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $penizq02=$resp_r['cant_eg'];
}
if(isset($penizq02)){
$pdf->SetY(130);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($penizq02),1,0,'C');
}else{
  $pdf->SetY(130);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion and des_eg='PENROSE IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $penizq03=$resp_r['cant_eg'];
}
if(isset($penizq03)){
$pdf->SetY(130);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($penizq03),1,0,'C');
}else{
  $pdf->SetY(130);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion and des_eg='PENROSE IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $penizq04=$resp_r['cant_eg'];
}
if(isset($penizq04)){
$pdf->SetY(130);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($penizq04),1,0,'C');
}else{
  $pdf->SetY(130);
$pdf->SetX(180);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion and des_eg='PENROSE IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $penizq05=$resp_r['cant_eg'];
}
if(isset($penizq05)){
$pdf->SetY(130);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($penizq05),1,0,'C');
}else{
  $pdf->SetY(130);
$pdf->SetX(187);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion and des_eg='PENROSE IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $penizq06=$resp_r['cant_eg'];
}
if(isset($penizq06)){
$pdf->SetY(130);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($penizq06),1,0,'C');
}else{
  $pdf->SetY(130);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion and des_eg='PENROSE IZQ'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $penizq07=$resp_r['cant_eg'];
}
if(isset($penizq07)){
$pdf->SetY(130);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($penizq07),1,0,'C');
}else{
  $pdf->SetY(130);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


//PENROSE DER PENROSE DER PENROSE DER PENROSE DER PENROSE DER PENROSE DER PENROSE DER PENROSE DER 

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion and des_eg='PENROSE DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pender8=$resp_r['cant_eg'];
}
if(isset($pender8)){
$pdf->SetY(133);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($pender8),1,0,'C');
}else{
  $pdf->SetY(133);
  $pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion and des_eg='PENROSE DER'") or die($conexion->error);
while ($resp_r = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pender9=$resp_r['cant_eg'];
}
if(isset($pender9)){
$pdf->SetY(133);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($pender99),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(47);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion and des_eg='PENROSE DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pender10=$resp_r['cant_eg'];
}
if(isset($pender10)){
$pdf->SetY(133);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($pender10),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(54);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion and des_eg='PENROSE DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pender11=$resp_r['cant_eg'];
}
if(isset($pender11)){
$pdf->SetY(133);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($pender11),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion and des_eg='PENROSE DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pender12=$resp_r['cant_eg'];
}
if(isset($pender12)){
$pdf->SetY(133);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($pender12),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(68);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion and des_eg='PENROSE DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pender13=$resp_r['cant_eg'];
}
if(isset($pender13)){
$pdf->SetY(133);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($pender13),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(75);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion and des_eg='PENROSE DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pender14=$resp_r['cant_eg'];
}
if(isset($pender14)){
$pdf->SetY(133);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($pender14),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(82);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion and des_eg='PENROSE DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pender15=$resp_r['cant_eg'];
}
if(isset($pender15)){
$pdf->SetY(133);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($pender15),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(89);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion and des_eg='PENROSE DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pender16=$resp_r['cant_eg'];
}
if(isset($pender16)){
$pdf->SetY(133);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($pender16),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(96);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion and des_eg='PENROSE DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pender17=$resp_r['cant_eg'];
}
if(isset($pender17)){
$pdf->SetY(133);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($pender17),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion and des_eg='PENROSE DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pender18=$resp_r['cant_eg'];
}
if(isset($pender18)){
$pdf->SetY(133);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($pender18),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion and des_eg='PENROSE DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pender19=$resp_r['cant_eg'];
}
if(isset($pender19)){
$pdf->SetY(133);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($pender19),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion and des_eg='PENROSE DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pender20=$resp_r['cant_eg'];
}
if(isset($pender20)){
$pdf->SetY(133);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($pender20),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion and des_eg='PENROSE DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pender21=$resp_r['cant_eg'];
}
if(isset($pender21)){
$pdf->SetY(133);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($pender21),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion and des_eg='PENROSE DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pender22=$resp_r['cant_eg'];
}
if(isset($pender22)){
$pdf->SetY(133);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($pender22),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion and des_eg='PENROSE DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pender23=$resp_r['cant_eg'];
}
if(isset($pender23)){
$pdf->SetY(133);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($pender23),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(145);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion and des_eg='PENROSE DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pender24=$resp_r['cant_eg'];
}
if(isset($pender24)){
$pdf->SetY(133);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($pender24),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion and des_eg='PENROSE DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pender01=$resp_r['cant_eg'];
}
if(isset($pender01)){
$pdf->SetY(133);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($pender01),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(159);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  eg_enf_ter where fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion and des_eg='PENROSE DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pender02=$resp_r['cant_eg'];
}
if(isset($pender02)){
$pdf->SetY(133);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($pender02),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion and des_eg='PENROSE DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pender03=$resp_r['cant_eg'];
}
if(isset($pender03)){
$pdf->SetY(133);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($pender03),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion and des_eg='PENROSE DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pender04=$resp_r['cant_eg'];
}
if(isset($pender04)){
$pdf->SetY(133);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($pender04),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(180);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion and des_eg='PENROSE DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pender05=$resp_r['cant_eg'];
}
if(isset($pender05)){
$pdf->SetY(133);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($pender05),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(187);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion and des_eg='PENROSE DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pender06=$resp_r['cant_eg'];
}
if(isset($pender06)){
$pdf->SetY(133);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($pender06),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion and des_eg='PENROSE DER'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pender07=$resp_r['cant_eg'];
}
if(isset($pender07)){
$pdf->SetY(133);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($pender07),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


//DRENOVAC DRENOVAC DRENOVAC DRENOVAC DRENOVAC DRENOVAC DRENOVAC DRENOVAC 

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion and des_eg='DRENOVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dren8=$resp_r['cant_eg'];
}
if(isset($dren8)){
$pdf->SetY(136);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($dren8),1,0,'C');
}else{
  $pdf->SetY(136);
  $pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion and des_eg='DRENOVAC'") or die($conexion->error);
while ($resp_r = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dren9=$resp_r['cant_eg'];
}
if(isset($dren9)){
$pdf->SetY(136);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($dren99),1,0,'C');
}else{
  $pdf->SetY(136);
$pdf->SetX(47);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion and des_eg='DRENOVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dren10=$resp_r['cant_eg'];
}
if(isset($dren10)){
$pdf->SetY(136);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($dren10),1,0,'C');
}else{
  $pdf->SetY(136);
$pdf->SetX(54);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion and des_eg='DRENOVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dren11=$resp_r['cant_eg'];
}
if(isset($dren11)){
$pdf->SetY(136);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($dren11),1,0,'C');
}else{
  $pdf->SetY(136);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion and des_eg='DRENOVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dren12=$resp_r['cant_eg'];
}
if(isset($dren12)){
$pdf->SetY(136);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($dren12),1,0,'C');
}else{
  $pdf->SetY(136);
$pdf->SetX(68);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion and des_eg='DRENOVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dren13=$resp_r['cant_eg'];
}
if(isset($dren13)){
$pdf->SetY(136);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($dren13),1,0,'C');
}else{
  $pdf->SetY(136);
$pdf->SetX(75);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion and des_eg='DRENOVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dren14=$resp_r['cant_eg'];
}
if(isset($dren14)){
$pdf->SetY(136);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($dren14),1,0,'C');
}else{
  $pdf->SetY(136);
$pdf->SetX(82);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion and des_eg='DRENOVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dren15=$resp_r['cant_eg'];
}
if(isset($dren15)){
$pdf->SetY(136);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($dren15),1,0,'C');
}else{
  $pdf->SetY(136);
$pdf->SetX(89);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion and des_eg='DRENOVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dren16=$resp_r['cant_eg'];
}
if(isset($dren16)){
$pdf->SetY(136);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($dren16),1,0,'C');
}else{
  $pdf->SetY(136);
$pdf->SetX(96);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion and des_eg='DRENOVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dren17=$resp_r['cant_eg'];
}
if(isset($dren17)){
$pdf->SetY(136);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($dren17),1,0,'C');
}else{
  $pdf->SetY(136);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion and des_eg='DRENOVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dren18=$resp_r['cant_eg'];
}
if(isset($dren18)){
$pdf->SetY(136);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($dren18),1,0,'C');
}else{
  $pdf->SetY(136);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion and des_eg='DRENOVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dren19=$resp_r['cant_eg'];
}
if(isset($dren19)){
$pdf->SetY(136);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($dren19),1,0,'C');
}else{
  $pdf->SetY(136);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion and des_eg='DRENOVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dren20=$resp_r['cant_eg'];
}
if(isset($dren20)){
$pdf->SetY(136);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($dren20),1,0,'C');
}else{
  $pdf->SetY(136);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion and des_eg='DRENOVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dren21=$resp_r['cant_eg'];
}
if(isset($dren21)){
$pdf->SetY(136);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($dren21),1,0,'C');
}else{
  $pdf->SetY(136);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion and des_eg='DRENOVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dren22=$resp_r['cant_eg'];
}
if(isset($dren22)){
$pdf->SetY(136);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($dren22),1,0,'C');
}else{
  $pdf->SetY(136);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion and des_eg='DRENOVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dren23=$resp_r['cant_eg'];
}
if(isset($dren23)){
$pdf->SetY(136);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($dren23),1,0,'C');
}else{
  $pdf->SetY(136);
$pdf->SetX(145);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion and des_eg='DRENOVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dren24=$resp_r['cant_eg'];
}
if(isset($dren24)){
$pdf->SetY(136);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($dren24),1,0,'C');
}else{
  $pdf->SetY(136);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion and des_eg='DRENOVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dren01=$resp_r['cant_eg'];
}
if(isset($dren01)){
$pdf->SetY(136);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($dren01),1,0,'C');
}else{
  $pdf->SetY(136);
$pdf->SetX(159);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  eg_enf_ter where fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion and des_eg='DRENOVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dren02=$resp_r['cant_eg'];
}
if(isset($dren02)){
$pdf->SetY(136);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($dren02),1,0,'C');
}else{
  $pdf->SetY(136);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion and des_eg='DRENOVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dren03=$resp_r['cant_eg'];
}
if(isset($dren03)){
$pdf->SetY(136);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($dren03),1,0,'C');
}else{
  $pdf->SetY(136);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion and des_eg='DRENOVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dren04=$resp_r['cant_eg'];
}
if(isset($dren04)){
$pdf->SetY(136);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($dren04),1,0,'C');
}else{
  $pdf->SetY(136);
$pdf->SetX(180);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion and des_eg='DRENOVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dren05=$resp_r['cant_eg'];
}
if(isset($dren05)){
$pdf->SetY(136);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($dren05),1,0,'C');
}else{
  $pdf->SetY(136);
$pdf->SetX(187);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion and des_eg='DRENOVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dren06=$resp_r['cant_eg'];
}
if(isset($dren06)){
$pdf->SetY(136);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($dren06),1,0,'C');
}else{
  $pdf->SetY(136);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion and des_eg='DRENOVAC'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dren07=$resp_r['cant_eg'];
}
if(isset($dren07)){
$pdf->SetY(136);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($dren07),1,0,'C');
}else{
  $pdf->SetY(136);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


//COLOSTOMIA COLOSTOMIA COLOSTOMIA COLOSTOMIA COLOSTOMIA COLOSTOMIA COLOSTOMIA COLOSTOMIA

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion and des_eg='COLOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $col8=$resp_r['cant_eg'];
}
if(isset($col8)){
$pdf->SetY(139);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($col8),1,0,'C');
}else{
  $pdf->SetY(139);
  $pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion and des_eg='COLOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $col9=$resp_r['cant_eg'];
}
if(isset($col9)){
$pdf->SetY(139);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($col99),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(47);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion and des_eg='COLOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $col10=$resp_r['cant_eg'];
}
if(isset($col10)){
$pdf->SetY(139);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($col10),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(54);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion and des_eg='COLOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $col11=$resp_r['cant_eg'];
}
if(isset($col11)){
$pdf->SetY(139);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($col11),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion and des_eg='COLOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $col12=$resp_r['cant_eg'];
}
if(isset($col12)){
$pdf->SetY(139);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($col12),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(68);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion and des_eg='COLOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $col13=$resp_r['cant_eg'];
}
if(isset($col13)){
$pdf->SetY(139);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($col13),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(75);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion and des_eg='COLOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $col14=$resp_r['cant_eg'];
}
if(isset($col14)){
$pdf->SetY(139);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($col14),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(82);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion and des_eg='COLOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $col15=$resp_r['cant_eg'];
}
if(isset($col15)){
$pdf->SetY(139);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($col15),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(89);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion and des_eg='COLOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $col16=$resp_r['cant_eg'];
}
if(isset($col16)){
$pdf->SetY(139);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($col16),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(96);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion and des_eg='COLOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $col17=$resp_r['cant_eg'];
}
if(isset($col17)){
$pdf->SetY(139);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($col17),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion and des_eg='COLOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $col18=$resp_r['cant_eg'];
}
if(isset($col18)){
$pdf->SetY(139);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($col18),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion and des_eg='COLOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $col19=$resp_r['cant_eg'];
}
if(isset($col19)){
$pdf->SetY(139);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($col19),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion and des_eg='COLOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $col20=$resp_r['cant_eg'];
}
if(isset($col20)){
$pdf->SetY(139);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($col20),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion and des_eg='COLOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $col21=$resp_r['cant_eg'];
}
if(isset($col21)){
$pdf->SetY(139);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($col21),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion and des_eg='COLOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $col22=$resp_r['cant_eg'];
}
if(isset($col22)){
$pdf->SetY(139);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($col22),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion and des_eg='COLOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $col23=$resp_r['cant_eg'];
}
if(isset($col23)){
$pdf->SetY(139);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($col23),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(145);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion and des_eg='COLOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $col24=$resp_r['cant_eg'];
}
if(isset($col24)){
$pdf->SetY(139);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($col24),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion and des_eg='COLOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $col01=$resp_r['cant_eg'];
}
if(isset($col01)){
$pdf->SetY(139);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($col01),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(159);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  eg_enf_ter where fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion and des_eg='COLOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $col02=$resp_r['cant_eg'];
}
if(isset($col02)){
$pdf->SetY(139);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($col02),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion and des_eg='COLOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $col03=$resp_r['cant_eg'];
}
if(isset($col03)){
$pdf->SetY(139);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($col03),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion and des_eg='COLOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $col04=$resp_r['cant_eg'];
}
if(isset($col04)){
$pdf->SetY(139);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($col04),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(180);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion and des_eg='COLOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $col05=$resp_r['cant_eg'];
}
if(isset($col05)){
$pdf->SetY(139);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($col05),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(187);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion and des_eg='COLOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $col06=$resp_r['cant_eg'];
}
if(isset($col06)){
$pdf->SetY(139);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($col06),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion and des_eg='COLOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $col07=$resp_r['cant_eg'];
}
if(isset($col07)){
$pdf->SetY(139);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($col07),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//ILEOSTOMIA ILEOSTOMIA ILEOSTOMIA ILEOSTOMIA ILEOSTOMIA ILEOSTOMIA ILEOSTOMIA ILEOSTOMIA

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion and des_eg='ILEOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ile8=$resp_r['cant_eg'];
}
if(isset($ile8)){
$pdf->SetY(142);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($ile8),1,0,'C');
}else{
  $pdf->SetY(142);
  $pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion and des_eg='ILEOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ile9=$resp_r['cant_eg'];
}
if(isset($ile9)){
$pdf->SetY(142);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($ile99),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(47);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion and des_eg='ILEOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ile10=$resp_r['cant_eg'];
}
if(isset($ile10)){
$pdf->SetY(142);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($ile10),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(54);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion and des_eg='ILEOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ile11=$resp_r['cant_eg'];
}
if(isset($ile11)){
$pdf->SetY(142);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($ile11),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion and des_eg='ILEOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ile12=$resp_r['cant_eg'];
}
if(isset($ile12)){
$pdf->SetY(142);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($ile12),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(68);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion and des_eg='ILEOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ile13=$resp_r['cant_eg'];
}
if(isset($ile13)){
$pdf->SetY(142);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($ile13),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(75);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion and des_eg='ILEOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ile14=$resp_r['cant_eg'];
}
if(isset($ile14)){
$pdf->SetY(142);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($ile14),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(82);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion and des_eg='ILEOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ile15=$resp_r['cant_eg'];
}
if(isset($ile15)){
$pdf->SetY(142);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($ile15),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(89);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion and des_eg='ILEOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ile16=$resp_r['cant_eg'];
}
if(isset($ile16)){
$pdf->SetY(142);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($ile16),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(96);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion and des_eg='ILEOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ile17=$resp_r['cant_eg'];
}
if(isset($ile17)){
$pdf->SetY(142);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($ile17),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion and des_eg='ILEOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ile18=$resp_r['cant_eg'];
}
if(isset($ile18)){
$pdf->SetY(142);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($ile18),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion and des_eg='ILEOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ile19=$resp_r['cant_eg'];
}
if(isset($ile19)){
$pdf->SetY(142);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($ile19),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion and des_eg='ILEOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ile20=$resp_r['cant_eg'];
}
if(isset($ile20)){
$pdf->SetY(142);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($ile20),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion and des_eg='ILEOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ile21=$resp_r['cant_eg'];
}
if(isset($ile21)){
$pdf->SetY(142);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($ile21),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion and des_eg='ILEOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ile22=$resp_r['cant_eg'];
}
if(isset($ile22)){
$pdf->SetY(142);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($ile22),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion and des_eg='ILEOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ile23=$resp_r['cant_eg'];
}
if(isset($ile23)){
$pdf->SetY(142);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($ile23),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(145);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion and des_eg='ILEOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ile24=$resp_r['cant_eg'];
}
if(isset($ile24)){
$pdf->SetY(142);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($ile24),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion and des_eg='ILEOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ile01=$resp_r['cant_eg'];
}
if(isset($ile01)){
$pdf->SetY(142);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($ile01),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(159);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  eg_enf_ter where fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion and des_eg='ILEOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ile02=$resp_r['cant_eg'];
}
if(isset($ile02)){
$pdf->SetY(142);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($ile02),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion and des_eg='ILEOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ile03=$resp_r['cant_eg'];
}
if(isset($ile03)){
$pdf->SetY(142);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($ile03),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion and des_eg='ILEOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ile04=$resp_r['cant_eg'];
}
if(isset($ile04)){
$pdf->SetY(142);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($ile04),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(180);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion and des_eg='ILEOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ile05=$resp_r['cant_eg'];
}
if(isset($ile05)){
$pdf->SetY(142);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($ile05),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(187);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion and des_eg='ILEOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ile06=$resp_r['cant_eg'];
}
if(isset($ile06)){
$pdf->SetY(142);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($ile06),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion and des_eg='ILEOSTOMIA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ile07=$resp_r['cant_eg'];
}
if(isset($ile07)){
$pdf->SetY(142);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($ile07),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//SARATOGA SARATOGA SARATOGA SARATOGA SARATOGA SARATOGA SARATOGA SARATOGA

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='8' and id_atencion=$id_atencion and des_eg='SARATOGA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sara8=$resp_r['cant_eg'];
}
if(isset($sara8)){
$pdf->SetY(145);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($sara8),1,0,'C');
}else{
  $pdf->SetY(145);
  $pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='9' and id_atencion=$id_atencion and des_eg='SARATOGA'") or die($conexion->error);
while ($resp_r = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sara9=$resp_r['cant_eg'];
}
if(isset($sara9)){
$pdf->SetY(145);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($sara99),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(47);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='10' and id_atencion=$id_atencion and des_eg='SARATOGA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sara10=$resp_r['cant_eg'];
}
if(isset($sara10)){
$pdf->SetY(145);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($sara10),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(54);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='11' and id_atencion=$id_atencion and des_eg='SARATOGA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sara11=$resp_r['cant_eg'];
}
if(isset($sara11)){
$pdf->SetY(145);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($sara11),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='12' and id_atencion=$id_atencion and des_eg='SARATOGA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sara12=$resp_r['cant_eg'];
}
if(isset($sara12)){
$pdf->SetY(145);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($sara12),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(68);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='13' and id_atencion=$id_atencion and des_eg='SARATOGA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sara13=$resp_r['cant_eg'];
}
if(isset($sara13)){
$pdf->SetY(145);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($sara13),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(75);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='14' and id_atencion=$id_atencion and des_eg='SARATOGA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sara14=$resp_r['cant_eg'];
}
if(isset($sara14)){
$pdf->SetY(145);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($sara14),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(82);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='15' and id_atencion=$id_atencion and des_eg='SARATOGA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sara15=$resp_r['cant_eg'];
}
if(isset($sara15)){
$pdf->SetY(145);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($sara15),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(89);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='16' and id_atencion=$id_atencion and des_eg='SARATOGA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sara16=$resp_r['cant_eg'];
}
if(isset($sara16)){
$pdf->SetY(145);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($sara16),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(96);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='17' and id_atencion=$id_atencion and des_eg='SARATOGA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sara17=$resp_r['cant_eg'];
}
if(isset($sara17)){
$pdf->SetY(145);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($sara17),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='18' and id_atencion=$id_atencion and des_eg='SARATOGA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sara18=$resp_r['cant_eg'];
}
if(isset($sara18)){
$pdf->SetY(145);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($sara18),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='19' and id_atencion=$id_atencion and des_eg='SARATOGA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sara19=$resp_r['cant_eg'];
}
if(isset($sara19)){
$pdf->SetY(145);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($sara19),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='20' and id_atencion=$id_atencion and des_eg='SARATOGA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sara20=$resp_r['cant_eg'];
}
if(isset($sara20)){
$pdf->SetY(145);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($sara20),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='21' and id_atencion=$id_atencion and des_eg='SARATOGA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sara21=$resp_r['cant_eg'];
}
if(isset($sara21)){
$pdf->SetY(145);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($sara21),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='22' and id_atencion=$id_atencion and des_eg='SARATOGA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sara22=$resp_r['cant_eg'];
}
if(isset($sara22)){
$pdf->SetY(145);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($sara22),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='23' and id_atencion=$id_atencion and des_eg='SARATOGA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sara23=$resp_r['cant_eg'];
}
if(isset($sara23)){
$pdf->SetY(145);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($sara23),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(145);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='24' and id_atencion=$id_atencion and des_eg='SARATOGA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sara24=$resp_r['cant_eg'];
}
if(isset($sara24)){
$pdf->SetY(145);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($sara24),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='1' and id_atencion=$id_atencion and des_eg='SARATOGA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sara01=$resp_r['cant_eg'];
}
if(isset($sara01)){
$pdf->SetY(145);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($sara01),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(159);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from  eg_enf_ter where fecha_eg='$fechar' and hora_eg='2' and id_atencion=$id_atencion and des_eg='SARATOGA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sara02=$resp_r['cant_eg'];
}
if(isset($sara02)){
$pdf->SetY(145);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($sara02),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='3' and id_atencion=$id_atencion and des_eg='SARATOGA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sara03=$resp_r['cant_eg'];
}
if(isset($sara03)){
$pdf->SetY(145);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($sara03),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='4' and id_atencion=$id_atencion and des_eg='SARATOGA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sara04=$resp_r['cant_eg'];
}
if(isset($sara04)){
$pdf->SetY(145);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($sara04),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(180);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='5' and id_atencion=$id_atencion and des_eg='SARATOGA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sara05=$resp_r['cant_eg'];
}
if(isset($sara05)){
$pdf->SetY(145);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($sara05),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(187);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='6' and id_atencion=$id_atencion and des_eg='SARATOGA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sara06=$resp_r['cant_eg'];
}
if(isset($sara06)){
$pdf->SetY(145);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($sara06),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
$resp = $conexion->query("select * from eg_enf_ter where fecha_eg='$fechar' and hora_eg='7' and id_atencion=$id_atencion and des_eg='SARATOGA'") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sara07=$resp_r['cant_eg'];
}
if(isset($sara07)){
$pdf->SetY(145);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($sara07),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//EGRESO PARCIAL TOTAL EGRESO PARCIAL TOTAL EGRESO PARCIAL TOTAL EGRESO PARCIAL TOTAL EGRESO OPARCIAL MATUTINO


$resp = $conexion->query("select SUM(cant_eg) as sumom from eg_enf_ter where des_eg='ORINA' and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13' or hora_eg='14' )") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumom=$resp_r['sumom'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumvomm from eg_enf_ter where des_eg='VOMITO' and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13' or hora_eg='14' )") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumvomm=$resp_r['sumvomm'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumevam from eg_enf_ter where des_eg='EVACUACIONES' and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13' or hora_eg='14' )") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumevam=$resp_r['sumevam'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumsanm from eg_enf_ter where des_eg='SANGRADO' and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13' or hora_eg='14' )") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumsanm=$resp_r['sumsanm'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumsonnm from eg_enf_ter where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13' or hora_eg='14' )") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumsonnm=$resp_r['sumsonnm'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumsontm from eg_enf_ter where des_eg='SONDA T' and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13' or hora_eg='14' )") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumsontm=$resp_r['sumsontm'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumcolm from eg_enf_ter where des_eg='COLOSTOMIA' and fecha_eg='$fechar' and id_atencion=$id_atencion and 
(hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13' or hora_eg='14' )") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumcolm=$resp_r['sumcolm'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumbioim from eg_enf_ter where (des_eg='BIOVAC IZQ') and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13' or hora_eg='14' )") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumbioim=$resp_r['sumbioim'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumbiodm from eg_enf_ter where (des_eg='BIOVAC DER') and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13' or hora_eg='14' )") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumbiodv=$resp_r['sumbiodm'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumdrenom from eg_enf_ter where des_eg='DRENOVAC' and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13' or hora_eg='14' )") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumdrenom=$resp_r['sumdrenom'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumpenim from eg_enf_ter where (des_eg='PENROSE IZQ') and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13' or hora_eg='14' )") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumpenim=$resp_r['sumpenim'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumpendm from eg_enf_ter where (des_eg='PENROSE DER' || des_eg='PENROSE DER') and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13' or hora_eg='14' )") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumpendm=$resp_r['sumpendm'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumsaram from eg_enf_ter where (des_eg='SARATOGA' || des_eg='PLEUROVAC') and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13' or hora_eg='14' )") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumsaram=$resp_r['sumsaram'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumilem from eg_enf_ter where des_eg='ILEOSTOMIAS' and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13' or hora_eg='14' )") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumilem=$resp_r['sumilem'];
}

$sumatotalem=$sumom+$sumvomm+$sumevam+$sumsanm+$sumsonnm+$sumsontm+$suncolm+$sumbioim+$sumbiodm+$sumdrenom+$sumpenim+$sumpendm+$sumsaram+$sumilem;

$pdf->SetY(148);
$pdf->SetX(40);
$pdf->Cell(49,6, utf8_decode($sumatotalem . ' ML'),1,0,'C');


//EGRESO PARCIAL TOTAL EGRESO PARCIAL TOTAL EGRESO PARCIAL TOTAL EGRESO PARCIAL TOTAL EGRESO OPARCIAL VESPERTINO


$resp = $conexion->query("select SUM(cant_eg) as sumov from eg_enf_ter where des_eg='ORINA' and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumov=$resp_r['sumov'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumvomv from eg_enf_ter where des_eg='VOMITO' and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumvomv=$resp_r['sumvomv'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumevav from eg_enf_ter where des_eg='EVACUACIONES' and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumevav=$resp_r['sumevav'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumsanv from eg_enf_ter where des_eg='SANGRADO' and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumsanv=$resp_r['sumsanv'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumsonnv from eg_enf_ter where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumsonnv=$resp_r['sumsonnv'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumsontv from eg_enf_ter where des_eg='SONDA T' and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumsontv=$resp_r['sumsontv'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumcolv from eg_enf_ter where des_eg='COLOSTOMIA' and fecha_eg='$fechar' and id_atencion=$id_atencion and 
(hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumcolv=$resp_r['sumcolv'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumbioiv from eg_enf_ter where (des_eg='BIOVAC IZQ') and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumbioiv=$resp_r['sumbioiv'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumbiodv from eg_enf_ter where (des_eg='BIOVAC DER') and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumbiodv=$resp_r['sumbiodv'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumdrenov from eg_enf_ter where des_eg='DRENOVAC' and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumdrenov=$resp_r['sumdrenov'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumpeniv from eg_enf_ter where (des_eg='PENROSE IZQ') and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumpeniv=$resp_r['sumpeniv'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumpendv from eg_enf_ter where (des_eg='PENROSE DER' || des_eg='PENROSE DER') and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumpendv=$resp_r['sumpendv'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumsarav from eg_enf_ter where (des_eg='SARATOGA' || des_eg='PLEUROVAC') and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumsarav=$resp_r['sumsarav'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumilev from eg_enf_ter where des_eg='ILEOSTOMIAS' and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumilev=$resp_r['sumilev'];
}

$sumatotalev=$sumov+$sumvomv+$sumevav+$sumsanv+$sumsonnv+$sumsontv+$suncolv+$sumbioiv+$sumbiodv+$sumdrenov+$sumpeniv+$sumpendv+$sumsarav+$sumilev;

$pdf->SetY(148);
$pdf->SetX(89);
$pdf->Cell(42,6, utf8_decode($sumatotalev . ' ML'),1,0,'C');

//EGRESO PARCIAL TOTAL EGRESO PARCIAL TOTAL EGRESO PARCIAL TOTAL EGRESO PARCIAL TOTAL EGRESO OPARCIAL NOCTURNO


$resp = $conexion->query("select SUM(cant_eg) as sumon from eg_enf_ter where des_eg='ORINA' and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumon=$resp_r['sumon'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumvomn from eg_enf_ter where des_eg='VOMITO' and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumvomn=$resp_r['sumvomn'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumevan from eg_enf_ter where des_eg='EVACUACIONES' and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumevan=$resp_r['sumevan'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumsann from eg_enf_ter where des_eg='SANGRADO' and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumsann=$resp_r['sumsann'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumsonnn from eg_enf_ter where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumsonnn=$resp_r['sumsonnn'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumsontn from eg_enf_ter where des_eg='SONDA T' and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumsontn=$resp_r['sumsontn'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumcoln from eg_enf_ter where des_eg='COLOSTOMIA' and fecha_eg='$fechar' and id_atencion=$id_atencion and 
(hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumcoln=$resp_r['sumcoln'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumbioin from eg_enf_ter where (des_eg='BIOVAC IZQ') and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumbioin=$resp_r['sumbioin'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumbiodn from eg_enf_ter where (des_eg='BIOVAC DER') and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumbiodn=$resp_r['sumbiodn'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumdrenon from eg_enf_ter where des_eg='DRENOVAC' and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumdrenon=$resp_r['sumdrenon'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumpenin from eg_enf_ter where (des_eg='PENROSE IZQ') and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumpenin=$resp_r['sumpenin'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumpendn from eg_enf_ter where (des_eg='PENROSE DER' || des_eg='PENROSE DER') and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumpendn=$resp_r['sumpendn'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumsaran from eg_enf_ter where (des_eg='SARATOGA' || des_eg='PLEUROVAC') and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumsaran=$resp_r['sumsaran'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumilen from eg_enf_ter where des_eg='ILEOSTOMIAS' and fecha_eg='$fechar'  and id_atencion=$id_atencion and 
(hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumilen=$resp_r['sumilen'];
}

$sumatotalen=$sumon+$sumvomn+$sumevan+$sumsann+$sumsonnn+$sumsontn+$suncoln+$sumbioin+$sumbiodn+$sumdrenon+$sumpenin+$sumpendn+$sumsaran+$sumilen;

$totalegresos=$sumatotalem+$sumatotalev+$sumatotalen;

$pdf->SetY(148);
$pdf->SetX(131);
$pdf->Cell(77,6, utf8_decode($sumatotalen . ' ML ' . ' Total egresos: ' . $totalegresos . 'ML'),1,0,'C');

//BALANCE TOTAL BALANCE TOTAL BALANCE TOTAL BALANCE TOTAL BALANCE TOTAL BALANCE TOTAL BALANCE TOTAL BALANCE TOTAL

$totalbalm=$sumatotalm - $sumatotalem;
$totalbalv=$sumatotalv - $sumatotalev;
$totalbaln=$sumatotaln - $sumatotalen;

$grantotal = $totalbalm + $totalbalv + $totalbaln;

$pdf->SetY(155);
$pdf->SetX(40);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(49,6, utf8_decode($totalbalm . 'ML'),1,0,'C');

$pdf->SetY(155);
$pdf->SetX(89);
$pdf->SetFont('Arial', '', 6);

$pdf->Cell(42,6, utf8_decode($totalbalv . 'ML'),1,0,'C');

$pdf->SetY(155);
$pdf->SetX(131);

$pdf->SetFont('Arial', '', 6);
$pdf->Cell(77,6, utf8_decode($totalbaln . 'ML    Balance Total: ' . $grantotal . 'ML'),1,0,'C');

//ESCALA NORTON TABLA ESCALA NORTON ESCALA NORTON ESCALA NORTON ESCALA NORTON ESCALA NORTON

$pdf->SetY(160);
$pdf->SetX(10);
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(198, 4, utf8_decode('ESCALA DE RIESGO DE ULCERAS, PRESIÓN (NORTON)'), 0, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode('Escala'), 1, 0, 'C');
$pdf->Cell(95, 4, utf8_decode('Parámetro'), 1, 0, 'C');
$pdf->Cell(20, 4, utf8_decode('Calificación'), 1, 0, 'C');
$pdf->Cell(28, 4, utf8_decode('Valor'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Bueno: relleno capilar rápido'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('4'), 1, 0, 'C');
$pdf->Cell(28, 16, utf8_decode($estfis_m), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode('Estado físico general'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Mediano: relleno capilar lento'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('3'), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Regular: Ligero edema'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('2'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Muy malo: edema generalizado'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');



$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Alerta'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('4'), 1, 0, 'C');
$pdf->Cell(28, 16, utf8_decode($estmen_m), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode('Estado mental'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Apático'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('3'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Confuso'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('2'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Estuporoso y comatoso'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Ambulante'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('4'), 1, 0, 'C');
$pdf->Cell(28, 16, utf8_decode($act_m), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode('Actividad'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Camina con ayuda'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('3'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Sentado'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('2'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Encamado'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');


$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Total'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('4'), 1, 0, 'C');
$pdf->Cell(28, 16, utf8_decode($mov_m), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode('Movilidad'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Disminuida'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('3'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Muy limitada'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('2'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Inmóvil'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Ninguna'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('4'), 1, 0, 'C');
$pdf->Cell(28, 16, utf8_decode($inc_m), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Ocasional'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('3'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode('Incontinencia'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Urinaria o fecal'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('2'), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Urinaria y fecal'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode(''), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('Total:'), 1, 0, 'C');
$pdf->Cell(28, 4, utf8_decode($tot_m), 1, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(85, 4, utf8_decode('Clasificación del riesgo'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
if($tot_m >14){
$pdf->Cell(113, 4, utf8_decode('Riesgo mínimo'), 1, 0, 'L');
}else if($tot_m >11 && $tot_m <15){
$pdf->Cell(113, 4, utf8_decode('Riesgo evidente'), 1, 0, 'L');
}else if($tot_m >4 && $tot_m <12){
$pdf->Cell(113, 4, utf8_decode('Muy alto riesgo'), 1, 0, 'L');
}else if($tot_m <=4){
$pdf->Cell(113, 4, utf8_decode('No hay suficientes datos para dar una Clasificación'), 1, 0, 'L');
}
$pdf->Ln(4);


$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(198, 4, utf8_decode('Interpretación: 5-11 puntos: muy alto riesgo | 12-14 puntos: riesgo evidente | más de 14 puntos: riesgo mínimo'), 1, 0, 'L');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(85, 4, utf8_decode('Nombre de enfermera (o) que valora'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(113, 4, utf8_decode($nomenf_m), 1, 0, 'L');
$pdf->Ln(4);
$pdf->Ln(15);

$pdf->Ln(150);
$pdf->SetFont('Arial', 'B', 8);
$pdf->MultiCell(195, 6, utf8_decode('DIÁMETRO PUPILAR'), 0, 'C');
$pdf->Cell(45,4, utf8_decode('Hora'),1,0,'C');
$pdf->Cell(50,4, utf8_decode('Lado'),1,0,'C');
$pdf->Cell(50,4, utf8_decode('Tamaño'),1,0,'C');
$pdf->Cell(50,4, utf8_decode('Turno'),1,0,'C');

$pdf->Ln(4);
$medica = $conexion->query("select * from d_pupilar WHERE fecha_reporte='$fechar' and id_atencion=$id_atencion and ter='Si' ORDER BY id_pupilar DESC") or die($conexion->error);
while ($row_m = $medica->fetch_assoc()) {
$hora=$row_m['hora'];
if($hora=='8' || $hora=='9' || $hora=='10'|| $hora=='11'|| $hora=='12' || $hora=='13'|| $hora=='14'){
$turno="MATUTINO";
}else if ($hora=='15' || $hora=='16' || $hora=='17'|| $hora=='18'|| $hora=='19' || $hora=='20') {
  $turno="VESPERTINO";
}else if ($hora=='21' || $hora=='22' || $hora=='23' || $hora=='24'|| $hora=='1'|| $hora=='2' || $hora=='3' || $hora=='4' || $hora=='5' || $hora=='6' || $hora=='7') {
    $turno="NOCTURNO";
}
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(45,4, utf8_decode($row_m['hora']),1,0,'C');
$pdf->Cell(50,4, $row_m['lado'],1,0,'C');
$pdf->Cell(50,4, $row_m['tamano'],1,0,'C');
$pdf->Cell(50,4, $turno,1,1,'C');
}

//$pdf->Image('../../imagenes/val_pupilar.jpg', 45, 35, 120);
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(195, 4, utf8_decode('CONTROL DE CATÉTERES'),0, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(30, 4, utf8_decode('Dispositivo'), 1, 0, 'C');
$pdf->Cell(15, 4, utf8_decode('Tipo'), 1, 0, 'C');
$pdf->Cell(17, 4, utf8_decode('Instalado'), 1, 0, 'C');
$pdf->Cell(20, 4, utf8_decode('Instaló'), 1, 0, 'C');
$pdf->Cell(15, 4, utf8_decode('Dias inst'), 1, 0, 'C');
$pdf->Cell(17, 4, utf8_decode('Se cambió'), 1, 0, 'C');
$pdf->Cell(81, 4, utf8_decode('Observaciones'), 1, 0, 'C');
$pdf->Ln(4);
$medica = $conexion->query("select * from cate_enf_ter WHERE id_atencion=$id_atencion and fecha_inst='$fechar' ORDER BY id DESC") or die($conexion->error);
while ($row_m = $medica->fetch_assoc()) {
$fec_i=date_create($row_m['fecha_inst']);
$fec_i=date_format($fec_i,"d/m/Y");
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(30,4, $row_m['dispositivos'],1,0,'C');
$pdf->Cell(15,4, $row_m['tipo'],1,0,'C');
$pdf->Cell(17,4, $fec_i,1,0,'C');
$pdf->Cell(20,4, $row_m['instalo'],1,0,'C');
$pdf->Cell(15,4, $row_m['dias_inst'],1,0,'C');
$pdf->Cell(17,4, $row_m['fecha_cambio'],1,0,'C');
$pdf->Cell(81,4, $row_m['cultivo'],1,1,'L');
}

if($espizq!=NULL || $espder!=NULL || $nuevo!=NULL ||$nuevo1!=NULL ||$nuevo2!=NULL ||$nuevo3!=NULL ||$nuevo4!=NULL ||$nuevo5!=NULL ||$nuevo6!=NULL ||$nuevo7!=NULL ||$frenteizquierda!=NULL ||$frentederecha!=NULL ||$narizc!=NULL ||$mejderecha!=NULL ||$marf!=NULL ||$mare!=NULL ||$mandiizqui!=NULL ||$mandiderr!=NULL ||$mandicentroo!=NULL ||$cvi!=NULL ||$mard!=NULL ||$homi!=NULL ||$hombrod!=NULL ||$pecti!=NULL ||$pectd!=NULL ||$peci!=NULL ||$marc!=NULL ||$brazci!=NULL ||$cconder!=NULL ||$brazi!=NULL ||$annbraz!=NULL ||$marg!=NULL ||$derbraz!=NULL ||$muñei!=NULL ||$muñecad!=NULL ||$palmai!=NULL ||$palmad!=NULL ||$ddi!=NULL ||$ddoderu!=NULL ||$ddidos!=NULL ||$dedodos!=NULL ||$dditres!=NULL ||$dedotres!=NULL ||$dedocuatro!=NULL ||$ddicuatro!=NULL ||$ddicinco!=NULL ||$dedocincoo!=NULL ||$iabdomen!=NULL ||$marb!=NULL ||$inglei!=NULL ||$mara!=NULL ||$musloi!=NULL ||$muslod!=NULL ||$rodd!=NULL ||$rodi!=NULL ||$tod!=NULL ||$toi!=NULL ||$pied!=NULL ||$pie!=NULL || $coxis!=NULL || $plantapiea!=NULL || $plantapieader!=NULL || $tobilloatd!=NULL ||$tobilloati!=NULL ||$ptiatras!=NULL ||$ptdatras!=NULL ||$pierespaldad!=NULL ||$pierespaldai!=NULL ||$musloatrasiz!=NULL ||$musloatrasder!=NULL ||$dorsalder!=NULL ||$dorsaliz!=NULL ||$munecaatrasiz!=NULL ||$munecaatrasder!=NULL ||$antebdesp!=NULL ||$antebiesp!=NULL ||$casicodoi!=NULL ||$casicododer!=NULL ||$brazaltder!=NULL ||$brazalti!=NULL ||$glutiz!=NULL ||$glutder!=NULL ||$cinturader!=NULL ||$cinturaiz!=NULL ||$marh!=NULL ||$costilliz!=NULL ||$costillder!=NULL ||$espaldaarribader!=NULL ||$espaldarribaiz!=NULL ||$espaldaalta!=NULL ||$cuellatrasb!=NULL ||$cuellatrasmedio!=NULL ||$cabedorsalm!=NULL ||$cabealtaizqu!=NULL || $cabezaaltader!=NULL){
    $pdf->SetY(133);
    $pdf->SetX(25);
    
    $pdf->SetFont('Arial', 'B',8);
$pdf->Cell(190, 5, utf8_decode('VALORACIÓN DE LA PIEL INICIAL'), 0, 0, 'C');
$pdf->Ln(12);
$pdf->Cell(20,5, utf8_decode(''),0,0,'L');
$pdf->Cell(100,10, $pdf->Image('../../img/cuerpof.jpg', $pdf->GetX(), $pdf->GetY(),45),0);

//IMAGEN TRASERA IMAGEN TARSERA TRASERA TRASEA TRASERA TRASERA TRASERA TRASERA TRASERA TRASERA TRASERA TRASERA TRASEA
$pdf->Cell(15,5, utf8_decode(''),0,0,'L');
$pdf->Cell(100,10, $pdf->Image('../../img/cuerpot.jpg', $pdf->GetX(), $pdf->GetY(),41),0);
//$pdf->Image('../../img/cuerpof.jpg' , 79, 103, 56);
if($espizq!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(205);
$pdf->SetX(13);
$pdf->Cell(25, 6, utf8_decode($espizq), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 13, 209, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(183);
$pdf->SetX(88);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($espder!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(205);
$pdf->SetX(58);
$pdf->Cell(25, 6, utf8_decode($espder), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 58, 209, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(183);
$pdf->SetX(101);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($nuevo1!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(173);
$pdf->SetX(56);
$pdf->Cell(25, 6, utf8_decode($nuevo1), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 57, 176.6, 28, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(142);
$pdf->SetX(99);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}


if($nuevo2!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(173);
$pdf->SetX(19);
$pdf->Cell(25, 6, utf8_decode($nuevo2), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 20, 176.7, 28, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(142);
$pdf->SetX(89);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($nuevo3!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(167.7);
$pdf->SetX(10);
$pdf->Cell(25, 6, utf8_decode($nuevo3), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 10, 171.5, 38, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(138);
$pdf->SetX(89);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($nuevo4!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(167.4);
$pdf->SetX(28.3);
$pdf->Cell(25, 6, utf8_decode($nuevo4), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 20, 170.8, 33, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(135.5);
$pdf->SetX(94.8);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($nuevo5!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(167);
$pdf->SetX(56.5);
$pdf->Cell(25, 6, utf8_decode($nuevo5), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 57, 170.8, 37, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(138.5);
$pdf->SetX(99);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}


if($nuevo6!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(164);
$pdf->SetX(56.2);
$pdf->Cell(25, 6, utf8_decode($nuevo6), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 57, 168, 20, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(132);
$pdf->SetX(99);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($nuevo7!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(165);
$pdf->SetX(28);
$pdf->Cell(25, 6, utf8_decode($nuevo7), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 32, 169, 20, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(130);
$pdf->SetX(95);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}


if($nuevo8!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(164);
$pdf->SetX(23.5);
$pdf->Cell(25, 6, utf8_decode($nuevo8), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 28, 168, 20, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(132);
$pdf->SetX(89);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}


if($frenteizquierda!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(140);
$pdf->SetX(16);
$pdf->Cell(25, 6, utf8_decode($frenteizquierda), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 16.5, 144.5, 35, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(141.4);
$pdf->SetX(39);
//$pdf->Cell(25, 6, utf8_decode('x'), 0,0, 'C');
}

if($frentederecha!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(140);
$pdf->SetX(53);
$pdf->Cell(25, 6, utf8_decode($frentederecha), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 53.5, 144.5, 35, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(103.2);
$pdf->SetX(96);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($narizc!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(143);
$pdf->SetX(52);
$pdf->Cell(25, 6, utf8_decode($narizc), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 52.5, 147, 35, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(105.7);
$pdf->SetX(94.7);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($mejderecha!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(145);
$pdf->SetX(54);
$pdf->Cell(25, 6, utf8_decode($mejderecha), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 54.5, 148.8, 33.5, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(108.4);
$pdf->SetX(97.5);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($marf!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(144);
$pdf->SetX(16);
$pdf->Cell(25, 6, utf8_decode($marf), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 16, 148, 35, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(108.5);
$pdf->SetX(92.1);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($mare!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(146.6);
$pdf->SetX(16);
$pdf->Cell(25, 6, utf8_decode($mare), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 16, 150.5, 37, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(110.4);
$pdf->SetX(94.7);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($mandiizqui!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(148.3);
$pdf->SetX(16);
$pdf->Cell(25, 6, utf8_decode($mandiizqui), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 16, 151.9, 35.5, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(112.4);
$pdf->SetX(92.5);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($mandiderr!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(147.5);
$pdf->SetX(53.5);
$pdf->Cell(25, 6, utf8_decode($mandiderr), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 54, 151.5, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(111.4);
$pdf->SetX(97.5);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($mandicentroo!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(149.5);
$pdf->SetX(52);
$pdf->Cell(25, 6, utf8_decode($mandicentroo), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 52.5, 153.2, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(113.6);
$pdf->SetX(94.9);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cvi!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(152.5);
$pdf->SetX(14.5);
$pdf->Cell(25, 6, utf8_decode($cvi), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 15, 156.5, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(118.3);
$pdf->SetX(90.5);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($mard!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(152.5);
$pdf->SetX(58);
$pdf->Cell(25, 6, utf8_decode($mard), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 56, 156.5, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(118.3);
$pdf->SetX(98.7);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($homi!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(155);
$pdf->SetX(14.5);
$pdf->Cell(25, 6, utf8_decode($homi), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 15, 159, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(122.1);
$pdf->SetX(81.8);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($hombrod!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(155);
$pdf->SetX(60.8);
$pdf->Cell(25, 6, utf8_decode($hombrod), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 61.5, 159, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(122.1);
$pdf->SetX(107.3);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($pecti!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(158);
$pdf->SetX(14.5);
$pdf->Cell(25, 6, utf8_decode($pecti), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 15, 162, 33, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(125.1);
$pdf->SetX(89.8);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($pectd!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(158);
$pdf->SetX(57);
$pdf->Cell(25, 6, utf8_decode($pectd), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 56, 162, 33, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(125.1);
$pdf->SetX(100.2);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($peci!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(161);
$pdf->SetX(10);
$pdf->Cell(25, 6, utf8_decode($peci), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 10, 165.5, 41, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(128);
$pdf->SetX(92.9);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($marc!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(161);
$pdf->SetX(70);
$pdf->Cell(25, 6, utf8_decode($marc), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 53.5, 165.5, 41, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(128);
$pdf->SetX(97.6);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($brazci!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(160.2);
$pdf->SetX(10);
$pdf->Cell(25, 6, utf8_decode($brazci), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 10, 164.5, 33, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(130);
$pdf->SetX(80.9);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cconder!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(160.5);
$pdf->SetX(62.5);
$pdf->Cell(25, 6, utf8_decode($cconder), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 63, 164.5, 33, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(130);
$pdf->SetX(109.1);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($brazi!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(165);
$pdf->SetX(10);
$pdf->Cell(25, 6, utf8_decode($brazi), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 10, 169.5, 31, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(133);
$pdf->SetX(80);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($annbraz!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(165);
$pdf->SetX(70);
$pdf->Cell(25, 6, utf8_decode($annbraz), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 65, 169, 29, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(133);
$pdf->SetX(110);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($marg!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(170.5);
$pdf->SetX(9.5);
$pdf->Cell(25, 6, utf8_decode($marg), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 10, 174.5, 29, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(142.1);
$pdf->SetX(76);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($derbraz!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(170);
$pdf->SetX(55);
$pdf->Cell(25, 6, utf8_decode($derbraz), 0,0, 'C');
$pdf->Image('../registro_clinico/linea.png', 65, 174, 28, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(142.1);
$pdf->SetX(113.1);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($muñei!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(174.4);
$pdf->SetX(9.5);
$pdf->Cell(25, 6, utf8_decode($muñei), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 10, 178.5, 27, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(145.6);
$pdf->SetX(74.9);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($muñecad!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(174.4);
$pdf->SetX(68);
$pdf->Cell(25, 6, utf8_decode($muñecad), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 68.5, 178.5, 27, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(145.6);
$pdf->SetX(114.7);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($palmai!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(179.2);
$pdf->SetX(4);
$pdf->Cell(25, 6, utf8_decode($palmai), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 5, 183, 30.2, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(148);
$pdf->SetX(73.5);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($palmad!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(179.2);
$pdf->SetX(69.5);
$pdf->Cell(25, 6, utf8_decode($palmad), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 70.2, 183, 30.2, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(148);
$pdf->SetX(115.3);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($ddi!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(177.4);
$pdf->SetX(4.5);
$pdf->Cell(25, 6, utf8_decode($ddi), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 5, 181.1, 27, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(149.7);
$pdf->SetX(67.8);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($ddoderu!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(177.3);
$pdf->SetX(72.5);
$pdf->Cell(25, 6, utf8_decode($ddoderu), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 73.5, 181.1, 30.2, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(149.7);
$pdf->SetX(121.7);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($ddidos!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(181);
$pdf->SetX(2);
$pdf->Cell(25, 6, utf8_decode($ddidos), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 2.5, 185.1, 30.2, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(153);
$pdf->SetX(69.8);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($dedodos!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(181.7);
$pdf->SetX(72);
$pdf->Cell(25, 6, utf8_decode($dedodos), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 72.8, 185.5, 19, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(153);
$pdf->SetX(119.6);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($dditres!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(183.2);
$pdf->SetX(1.7);
$pdf->Cell(25, 6, utf8_decode($dditres), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 2.5, 186.9, 30.9, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(155);
$pdf->SetX(70.9);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($dedotres!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(183.7);
$pdf->SetX(65.5);
$pdf->Cell(25, 6, utf8_decode($dedotres), 0,0, 'C');
$pdf->Image('../registro_clinico/linea.png', 72, 187.4, 19, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(155);
$pdf->SetX(118.5);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($dedocuatro!=NULL){
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(183.7);
$pdf->SetX(54);
$pdf->Cell(25, 6, utf8_decode($dedocuatro), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 55.1, 187.3, 16, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(156.5);
$pdf->SetX(117.3);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($ddicuatro!=NULL){
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(183.7);
$pdf->SetX(34);
$pdf->Cell(25, 6, utf8_decode($ddicuatro), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 34.1, 187.3, 20, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(156.5);
$pdf->SetX(72);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($ddicinco!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(181);
$pdf->SetX(35.5);
$pdf->Cell(25, 6, utf8_decode($ddicinco), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 36.1, 184.9, 20, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(155.3);
$pdf->SetX(73.8);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($dedocincoo!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(180.7);
$pdf->SetX(52);
$pdf->Cell(25, 6, utf8_decode($dedocincoo), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 53, 184.6, 16, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(155.3);
$pdf->SetX(115.7);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($iabdomen!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(171.9);
$pdf->SetX(24.8);
$pdf->Cell(25, 6, utf8_decode($iabdomen), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 25, 175.6, 28, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(140.1);
$pdf->SetX(95);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($marb!=NULL){
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(177);
$pdf->SetX(53.5);
$pdf->Cell(25, 6, utf8_decode($marb), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 54.5, 180.6, 28, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(149);
$pdf->SetX(97.5);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}


if($inglei!=NULL){
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(177);
$pdf->SetX(26.8);
$pdf->Cell(25, 6, utf8_decode($inglei), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 23, 180.6, 28, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(149);
$pdf->SetX(93);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($mara!=NULL){
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(180);
$pdf->SetX(29);
$pdf->Cell(25, 6, utf8_decode($mara), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 25, 183.5, 28, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(152.3);
$pdf->SetX(94.7);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($musloi!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(189.5);
$pdf->SetX(13);
$pdf->Cell(25, 6, utf8_decode($musloi), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 13, 194, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(162.1);
$pdf->SetX(87.8);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($muslod!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(189);
$pdf->SetX(58);
$pdf->Cell(25, 6, utf8_decode($muslod), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 58, 194, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(162.1);
$pdf->SetX(102.2);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($rodd!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(196);
$pdf->SetX(58);
$pdf->Cell(25, 6, utf8_decode($rodd), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 58, 200, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(172.1);
$pdf->SetX(102.2);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($rodi!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(196);
$pdf->SetX(13);
$pdf->Cell(25, 6, utf8_decode($rodi), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 13, 200, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(172.1);
$pdf->SetX(88);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($tod!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(212);
$pdf->SetX(13);
$pdf->Cell(25, 6, utf8_decode($tod), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 13, 216, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(192.1);
$pdf->SetX(89);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($toi!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(212);
$pdf->SetX(58);
$pdf->Cell(25, 6, utf8_decode($toi), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 58, 216, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(192.1);
$pdf->SetX(100);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($pied!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(216);
$pdf->SetX(58);
$pdf->Cell(25, 6, utf8_decode($pied), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 58, 220, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(197);
$pdf->SetX(102);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($pie!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(216);
$pdf->SetX(13);
$pdf->Cell(25, 6, utf8_decode($pie), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 13, 220, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(197);
$pdf->SetX(88);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

//$pdf->Ln(110);

//terminomarcaje frontal

//$pdf->Cell(189, 6, utf8_decode(''), 0,0, 'C');

//$pdf->Image('../../img/cuerpot.jpg' , 79, 42, 56);



if($coxis!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(176.3);
$pdf->SetX(135);
$pdf->Cell(25, 6, utf8_decode($coxis), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 135.6, 180, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(88);
$pdf->SetX(94);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

//marcaje trasero
if($plantapiea!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(215);
$pdf->SetX(128);
$pdf->Cell(25, 6, utf8_decode($plantapiea), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 129, 219, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(142);
$pdf->SetX(86.5);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($plantapieader!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(215);
$pdf->SetX(176);
$pdf->Cell(25, 6, utf8_decode($plantapieader), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 170, 219, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(142);
$pdf->SetX(101);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($tobilloatd!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(210);
$pdf->SetX(176);
$pdf->Cell(25, 6, utf8_decode($tobilloatd), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 170, 214, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(137);
$pdf->SetX(101);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($tobilloati!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(210);
$pdf->SetX(128);
$pdf->Cell(25, 6, utf8_decode($tobilloati), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 129, 214, 31, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(137);
$pdf->SetX(88);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($ptiatras!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(203);
$pdf->SetX(128.5);
$pdf->Cell(25, 6, utf8_decode($ptiatras), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 129, 207, 31, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(126);
$pdf->SetX(88);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($ptdatras!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(203);
$pdf->SetX(176);
$pdf->Cell(25, 6, utf8_decode($ptdatras), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 170, 207, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(126);
$pdf->SetX(100.6);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($pierespaldad!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(196);
$pdf->SetX(176);
$pdf->Cell(25, 6, utf8_decode($pierespaldad), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 170, 200, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(114);
$pdf->SetX(100.6);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($pierespaldai!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(196);
$pdf->SetX(129);
$pdf->Cell(25, 6, utf8_decode($pierespaldai), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 130, 200, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(114);
$pdf->SetX(88);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($musloatrasiz!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(187);
$pdf->SetX(129);
$pdf->Cell(25, 6, utf8_decode($musloatrasiz), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 130, 191, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(107);
$pdf->SetX(88);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($musloatrasder!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(187);
$pdf->SetX(176);
$pdf->Cell(25, 6, utf8_decode($musloatrasder), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 170, 191, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(107);
$pdf->SetX(100.3);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($dorsalder!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(177.5);
$pdf->SetX(183.6);
$pdf->Cell(25, 6, utf8_decode($dorsalder), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 181.5, 181.4, 26, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(91);
$pdf->SetX(117.5);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($dorsaliz!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(177.7);
$pdf->SetX(117);
$pdf->Cell(25, 6, utf8_decode($dorsaliz), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 118, 181.4, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(91);
$pdf->SetX(70);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($munecaatrasiz!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(174.2);
$pdf->SetX(119);
$pdf->Cell(25, 6, utf8_decode($munecaatrasiz), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 119.6, 177.9, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(87);
$pdf->SetX(72);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($munecaatrasder!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(174.2);
$pdf->SetX(183.5);
$pdf->Cell(25, 6, utf8_decode($munecaatrasder), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 179.6, 177.9, 28, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(87);
$pdf->SetX(115);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($antebdesp!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(171.4);
$pdf->SetX(183);
$pdf->Cell(25, 6, utf8_decode($antebdesp), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 179, 175, 28, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(83);
$pdf->SetX(113);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($antebiesp!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(171);
$pdf->SetX(120);
$pdf->Cell(25, 6, utf8_decode($antebiesp), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 120, 175, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(83);
$pdf->SetX(74);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($casicodoi!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(167);
$pdf->SetX(120.5);
$pdf->Cell(25, 6, utf8_decode($casicodoi), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 121, 171.1, 31, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(77);
$pdf->SetX(76);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($casicododer!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(166);
$pdf->SetX(182);
$pdf->Cell(25, 6, utf8_decode($casicododer), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 177, 170, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(77);
$pdf->SetX(111);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($brazaltder!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(162);
$pdf->SetX(179.5);
$pdf->Cell(25, 6, utf8_decode($brazaltder), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 176, 166.1, 28, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(71);
$pdf->SetX(110);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($brazalti!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(162);
$pdf->SetX(120);
$pdf->Cell(25, 6, utf8_decode($brazalti), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 120, 166, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(71);
$pdf->SetX(77);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($glutiz!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(179.6);
$pdf->SetX(130);
$pdf->Cell(25, 6, utf8_decode($glutiz), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 131, 183.3, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(94.5);
$pdf->SetX(90);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($glutder!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(179.6);
$pdf->SetX(174);
$pdf->Cell(25, 6, utf8_decode($glutder), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 168, 183.3, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(94.5);
$pdf->SetX(97.2);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cinturader!=NULL){
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(169.4);
$pdf->SetX(174);
$pdf->Cell(25, 6, utf8_decode($cinturader), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 168, 173, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(84.5);
$pdf->SetX(96.6);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cinturaiz!=NULL){
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(169.4);
$pdf->SetX(131);
$pdf->Cell(25, 6, utf8_decode($cinturaiz), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 132, 173, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(84.5);
$pdf->SetX(91);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($marh!=NULL){
  $pdf->SetFont('Arial', 'B',5);
$pdf->SetY(165);
$pdf->SetX(133.5);
$pdf->Cell(25, 6, utf8_decode($marh), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 134, 169, 31, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(80);
$pdf->SetX(94);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($costilliz!=NULL){
  $pdf->SetFont('Arial', 'B',5);
$pdf->SetY(164.2);
$pdf->SetX(127.7);
$pdf->Cell(25, 6, utf8_decode($costilliz), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 128, 168.1, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(73);
$pdf->SetX(90);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($costillder!=NULL){
  $pdf->SetFont('Arial', 'B',5);
$pdf->SetY(164.2);
$pdf->SetX(177.7);
$pdf->Cell(25, 6, utf8_decode($costillder), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 168, 168.1, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(73);
$pdf->SetX(97);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($espaldaarribader!=NULL){
  $pdf->SetFont('Arial', 'B',6);
$pdf->SetY(157);
$pdf->SetX(177);
$pdf->Cell(25, 6, utf8_decode($espaldaarribader), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 168, 161, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(64);
$pdf->SetX(100);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($espaldarribaiz!=NULL){
  $pdf->SetFont('Arial', 'B',6);
$pdf->SetY(156.9);
$pdf->SetX(126);
$pdf->Cell(25, 6, utf8_decode($espaldarribaiz), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 127, 161, 34, 0.1);

$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(64);
$pdf->SetX(88);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($espaldaalta!=NULL){
  $pdf->SetFont('Arial', 'B',6);
$pdf->SetY(152);
$pdf->SetX(175);
$pdf->Cell(25, 6, utf8_decode($espaldaalta), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 165, 155.8, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(57);
$pdf->SetX(93.5);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cuellatrasb!=NULL){
  $pdf->SetFont('Arial', 'B',6);
$pdf->SetY(148.5);
$pdf->SetX(175);
$pdf->Cell(25, 6, utf8_decode($cuellatrasb), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 165, 152.6, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(52);
$pdf->SetX(93.5);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cuellatrasmedio!=NULL){
  $pdf->SetFont('Arial', 'B',6);
$pdf->SetY(146.4);
$pdf->SetX(175);
$pdf->Cell(25, 6, utf8_decode($cuellatrasmedio), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 165, 150.1, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(48.5);
$pdf->SetX(93.5);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cabedorsalm!=NULL){
  $pdf->SetFont('Arial', 'B',6);
$pdf->SetY(144);
$pdf->SetX(175);
$pdf->Cell(25, 6, utf8_decode($cabedorsalm), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 165, 148, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(45.5);
$pdf->SetX(93.5);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cabealtaizqu!=NULL){
  $pdf->SetFont('Arial', 'B',6);
$pdf->SetY(142.1);
$pdf->SetX(129);
$pdf->Cell(25, 6, utf8_decode($cabealtaizqu), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 129, 146, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(42.5);
$pdf->SetX(91);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cabezaaltader!=NULL){
  $pdf->SetFont('Arial', 'B',6);
$pdf->SetY(142.1);
$pdf->SetX(177);
$pdf->Cell(25, 6, utf8_decode($cabezaaltader), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 167, 146, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(42.5);
$pdf->SetX(96);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
  
}

$pdf->Ln(130); 
}else{
  $pdf->Ln(10);  
}


//caidas CAIDAS CAIDAS CAIDAS CAIDAS CAIDAS CAIDAS CAIDAS CAIDAS CAIDAS CAIDAS CAIDAS CAIDAS CAIDAS CAIDAS
$pdf->Ln(32);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(195, 4, utf8_decode('ESCALA DE RIESGO DE CAIDAS (I.H. DOWNTON)'), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode('Variable'), 1, 0, 'C');
$pdf->Cell(95, 4, utf8_decode('Observación'), 1, 0, 'C');
$pdf->Cell(20, 4, utf8_decode('Calificación'), 1, 0, 'C');
$pdf->Cell(25, 4, utf8_decode('Valor'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode('Caidas previas'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('No'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('0'), 1, 0, 'C');
$pdf->Cell(25, 8, utf8_decode($caidas_m), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Si'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Ninguno'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('0'), 1, 0, 'C');
$pdf->Cell(25, 28, utf8_decode($medi_m), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Tranquilizantes-sedante'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Diuréticos'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode('Medicamentos'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Hipotensores(no diuréticos)'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Antiparksonianos'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Antidepresivos'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Otros medicamentos'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Ninguno'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('0'), 1, 0, 'C');
$pdf->Cell(25, 16, utf8_decode($defic_m), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode('Déficits sensoriales'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Alteraciones visuales'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Alteraciones auditivas'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Extremidades (Ictus..)'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode('Estado mental'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Orientado'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('0'), 1, 0, 'C');
$pdf->Cell(25, 8, utf8_decode($estement_m), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Confuso'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Normal'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('0'), 1, 0, 'C');
$pdf->Cell(25, 16, utf8_decode($deamb_m), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode('Deambulación'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Segura con ayuda'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Insegura con ayuda / sin ayuda'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Imposible'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode(''), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('Total:'), 1, 0, 'C');
$pdf->Cell(25, 4, utf8_decode($total_m), 1, 0, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(85, 4, utf8_decode('Clasificación del riesgo'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
if($total_m>2){
  $pdf->Cell(110, 4, utf8_decode('Alto riesgo para caída'), 1, 0, 'L');
}else{
   $pdf->Cell(110, 4, utf8_decode('No hay riesgo para caída'), 1, 0, 'L');
}

$pdf->Ln(4);

$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(105, 4, utf8_decode('Intervenciones / recomendaciones para prevención de riesgo de caída'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(90, 4, utf8_decode($interv_m), 1, 0, 'L');
$pdf->SetFont('Arial', 'B',7);
$pdf->Ln(4);
$pdf->Cell(195, 4, utf8_decode('Interpretación: Todos los pacientes con 3 o más puntos en esta calificación se consideran de Alto riesgo para caída'), 1, 0, 'L');
$pdf->SetFont('Arial', 'B',8);
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(85, 4, utf8_decode('Nombre de enfermera (o) que valora'), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(110, 4, utf8_decode($nom_enf_m), 1, 0, 'L');
$pdf->Ln(6);

$pdf->SetFont('Arial', 'B', 8);
$pdf->MultiCell(195, 6, utf8_decode('SOLUCIONES / AMINAS'), 0, 'C');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(118,5, utf8_decode('Solución / Amina'),1,0,'C');
$pdf->Cell(20,5, utf8_decode('Volumen total'),1,0,'C');
$pdf->Cell(10,5, utf8_decode('Inicio'),1,0,'C');
$pdf->Cell(18,5, utf8_decode('ml/hrs'),1,0,'C');
$pdf->Cell(11,5, utf8_decode('Término'),1,0,'C');
$pdf->Cell(18,5, utf8_decode('Fecha término'),1,0,'C');

$cis = $conexion->query("select * from sol_enf where sol_fecha='$fechar' and id_atencion=$id_atencion AND tipo='TERAPIA INTENSIVA' ORDER BY sol_fecha DESC") or die($conexion->error);
while ($cis_s = $cis->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
$pdf->Ln(5);
$pdf->Cell(118,5, utf8_decode($cis_s['sol']),1,0,'L');
$pdf->Cell(20,5, utf8_decode($cis_s['vol']),1,0,'L');
$pdf->Cell(10,5, utf8_decode($cis_s['hora_i']),1,0,'L');
$pdf->Cell(18,5, utf8_decode($cis_s['pvc']),1,0,'L');
$pdf->Cell(11,5, utf8_decode($cis_s['hora_t']),1,0,'L');
$date=date_create($cis_s['fecha_termino']);
$pdf->Cell(18,5, date_format($date,"d/m/Y"),1,0,'L');
}

$pdf->Ln(5);
$id_usuam=" ";

$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(195, 6, utf8_decode('Notas de enfermería turno Matutino:'), 0, 'L');
$pdf->Ln(5);
$medica = $conexion->query("select * from nota_enf_ter WHERE fecha='$fechar' and id_atencion=$id_atencion and turno = 'MATUTINO' ORDER BY id_nota_ter DESC") or die($conexion->error);
while ($row_m = $medica->fetch_assoc()) {
//$id_usuam = $row_m['id_usua'];
$hora=$row_m['hora'];
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(195,4, utf8_decode('Hora: '.$hora.' '.$row_m['nota']),0,'J');
}
$id_usuav=" ";
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(195, 6, utf8_decode('Notas de enfermería turno Vespertino:'), 0, 'L');
$pdf->Ln(5);
$medica = $conexion->query("select * from nota_enf_ter WHERE fecha='$fechar' and id_atencion=$id_atencion and turno = 'VESPERTINO' ORDER BY id_nota_ter DESC") or die($conexion->error);
while ($row_m = $medica->fetch_assoc()) {
//$id_usuav = $row_m['id_usua'];
$hora=$row_m['hora'];
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(195,4, utf8_decode('Hora: '.$hora.' '.$row_m['nota']),0,'J');
}
$id_usuan=" ";
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(195, 6, utf8_decode('Notas de enfermería turno Nocturno:'), 0, 'L');
$pdf->Ln(5);
$medica = $conexion->query("select * from nota_enf_ter WHERE fecha='$fechar' and id_atencion=$id_atencion and turno = 'NOCTURNO' ORDER BY id_nota_ter DESC") or die($conexion->error);
while ($row_m = $medica->fetch_assoc()) {
//$id_usuan = $row_m['id_usua'];
$hora=$row_m['hora'];
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(195,4, utf8_decode('Hora: '.$hora.' '.$row_m['nota']),0,'J');
}

$appm = "";
$prem = "";
$firmam = "";
$ced_pm = "";














$sql_med_id = "SELECT id_usua FROM enf_ter WHERE fecha_m='$fechar' and turno='MATUTINO' AND id_atencion = $id_atencion ORDER by id_enf_mat DESC LIMIT 1";
    $result_med_id = $conexion->query($sql_med_id);
    while ($row_med_id = $result_med_id->fetch_assoc()) {
      $id_usuam = $row_med_id['id_usua'];
    }

if ($id_usuam != " "){
    $sql_medm = "SELECT * FROM reg_usuarios WHERE id_usua = $id_usuam";
    $result_medm = $conexion->query($sql_medm);
    while ($row_medm = $result_medm->fetch_assoc()) {
      $appm = $row_medm['papell'];
      $prem = $row_medm['pre'];
      $firmam = $row_medm['firma'];
      $ced_pm = $row_medm['cedp'];
    }
}
    $pdf->SetY(-58);
    $pdf->SetFont('Arial', 'B', 8);
      
    if ($firmam==null) { $pdf->Image('../../imgfirma/FIRMA.jpg', 23, 245 , 25);
    } else {$pdf->Image('../../imgfirma/' . $firmam, 23, 245 , 15);}
    
    $pdf->SetY(256);
    $pdf->SetX(10);
    $pdf->Cell(50, 1, utf8_decode($prem . '. ' . $appm), 0, 0, 'C');
    $pdf->Ln(3);
    $pdf->SetX(10);
    $pdf->Cell(50, 4, utf8_decode('Nombre y firma de enfermería'), 0, 0, 'C');

$appv = "";
$prev = "";
$firmav = "";
$ced_pv = "";
$sql_med_id = "SELECT id_usua FROM enf_ter WHERE fecha_m='$fechar' and turno='VESPERTINO' AND id_atencion = $id_atencion ORDER by id_enf_mat DESC LIMIT 1";
    $result_med_id = $conexion->query($sql_med_id);
    while ($row_med_id = $result_med_id->fetch_assoc()) {
      $id_usuav = $row_med_id['id_usua'];
    }
if ($id_usuav != " "){
    $sql_medv = "SELECT * FROM reg_usuarios WHERE id_usua = $id_usuav";
    $result_medv = $conexion->query($sql_medv);
    while ($row_medv = $result_medv->fetch_assoc()) {
      $appv = $row_medv['papell'];
      $prev = $row_medv['pre'];
      $firmav = $row_medv['firma'];
      $ced_pv = $row_medv['cedp'];
    }
}
    $pdf->SetY(-58);
    $pdf->SetFont('Arial', 'B', 8);
      
    if ($firmav==null) { $pdf->Image('../../imgfirma/FIRMA.jpg', 98, 245 , 25);
    } else {$pdf->Image('../../imgfirma/' . $firmav, 98, 245 , 15);}
    $pdf->SetY(256);
    $pdf->SetX(80);
    $pdf->Cell(50, 1, utf8_decode($prev . '. ' . $appv), 0, 0, 'C');
    $pdf->Ln(3);
    $pdf->SetX(80);
    $pdf->Cell(50, 4, utf8_decode('Nombre y firma de enfermería'), 0, 0, 'C');

$appn = "";
$pren = "";
$firman = "";
$ced_pn = "";
$sql_med_id = "SELECT id_usua FROM enf_ter WHERE fecha_m='$fechar' and turno='NOCTURNO' AND id_atencion = $id_atencion ORDER by id_enf_mat DESC LIMIT 1";
    $result_med_id = $conexion->query($sql_med_id);
    while ($row_med_id = $result_med_id->fetch_assoc()) {
      $id_usuan = $row_med_id['id_usua'];
    }
if ($id_usuan != " "){
    $sql_medn = "SELECT * FROM reg_usuarios WHERE id_usua = $id_usuan";
    $result_medn = $conexion->query($sql_medn);
    while ($row_medn = $result_medn->fetch_assoc()) {
      $appn = $row_medn['papell'];
      $pren = $row_medn['pre'];
      $firman = $row_medn['firma'];
      $ced_pn = $row_medn['cedp'];
    }
}
    $pdf->SetY(-58);
    $pdf->SetFont('Arial', 'B', 8);
      
    if ($firman==null) { $pdf->Image('../../imgfirma/FIRMA.jpg', 163, 245 , 25);
    } else {$pdf->Image('../../imgfirma/' . $firman, 163, 245 , 15);}
    $pdf->SetY(256);
    $pdf->SetX(150);
    $pdf->Cell(50, 1, utf8_decode($pren . '. ' . $appn), 0, 0, 'C');
    $pdf->Ln(3);
    $pdf->SetX(150);
    $pdf->Cell(50, 4, utf8_decode('Nombre y firma de enfermería'), 0, 0, 'C');



 $pdf->Output(); 
}