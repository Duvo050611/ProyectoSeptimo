<?php
session_start();
include '../../conexionbd.php';

$usuario = $_SESSION['login'];
$id_usua= $usuario['id_usua'];

$id_atencion = $_SESSION['pac'];

$fec_tras=$_POST['fec_tras'];
$num_tras=$_POST['num_tras'];

if(isset($_POST['cont_tras'])){$cont_tras=$_POST['cont_tras'];}else{ $cont_tras='';}

//$cont_tras=$_POST['cont_tras'];
$fol_tras=$_POST['fol_tras'];
$glob_tras=$_POST['comp_sang'];
//$pla_tras=$_POST['pla_tras'];
//$crio_tras=$_POST['crio_tras'];
$hb_tras=$_POST['hb_tras'];
$hto_tras=$_POST['hto_tras'];
$san_tras=$_POST['san_tras'];

if(isset($_POST['sisto_pre'])){$sisto_pre=$_POST['sisto_pre'];}else{ $sisto_pre=0;}
if(isset($_POST['diasto_pre'])){$diasto_pre=$_POST['diasto_pre'];}else{ $diasto_pre=0;}
if(isset($_POST['fc_pre'])){$fc_pre=$_POST['fc_pre'];}else{ $fc_pre=0;}
if(isset($_POST['temp_pre'])){$temp_pre=$_POST['temp_pre'];}else{ $temp_pre=0;}



$inicio_tras=$_POST['inicio_tras'];
$med_tras=$_POST['med_tras'];
$medi_tras=$_POST['medi_tras'];
$ev_tras=$_POST['ev_tras'];
$com_tras=$_POST['com_tras'];

$vol_tras=$_POST['vol_tras'];
$fin_tras=$_POST['fin_tras'];
$edo_tras=$_POST['edo_tras'];
$ob_tras=$_POST['ob_tras'];
$sisto_tras=$_POST['sisto_tras'];
$diasto_tras=$_POST['diasto_tras'];
$fc_tras=$_POST['fc_tras'];
//$fr_tras=$_POST['fr_tras'];
$temp_tras=$_POST['temp_tras'];
//$sat_tras=$_POST['sat_tras'];
//$peso_tras=$_POST['peso_tras'];
//$talla_tras=$_POST['talla_tras'];
$com_traspost=$_POST['com_traspost'];
$ev_traspost=$_POST['ev_traspost'];


 $p_sistol = ($_POST['p_sistol']);
    $p_diastol = ($_POST['p_diastol']);
    $fcard = ($_POST['fcard']);
    //$fresp = ($_POST['fresp']);
    $temper = ($_POST['temper']);
 //   $satoxi = ($_POST['satoxi']);
  //  $peso = ($_POST['peso']);
    //$talla = ($_POST['talla']);
    

//date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

$insert=mysqli_query($conexion,'INSERT INTO dat_trasfucion(id_atencion,id_usua,fec_tras,num_tras,cont_tras,fol_tras,glob_tras,hb_tras,hto_tras,san_tras,sisto_pre,diasto_pre,fc_pre,temp_pre,inicio_tras,med_tras,medi_tras,ev_tras,com_tras,vol_tras,fin_tras,com_traspost,ev_traspost,edo_tras,ob_tras,sisto_tras,diasto_tras,fc_tras,temp_tras,p_sistol,p_diastol,fcard,temper,fecha_act) VALUES ('.$id_atencion.','.$id_usua.',"'.$fec_tras.'","'.$num_tras.'","'.$cont_tras.'","'.$fol_tras.'","'.$glob_tras.'","'.$hb_tras.'","'.$hto_tras.'","'.$san_tras.'","'.$sisto_pre.'","'.$diasto_pre.'","'.$fc_pre.'","'.$temp_pre.'","'.$inicio_tras.'","'.$med_tras.'","'.$medi_tras.'","'.$ev_tras.'","'.$com_tras.'","'.$vol_tras.'","'.$fin_tras.'","'.$com_traspost.'","'.$ev_traspost.'","'.$edo_tras.'","'.$ob_tras.'", " ' . $sisto_tras . '" , "' . $diasto_tras . '" , "' .$fc_tras. '", "' . $temp_tras . ' ", " ' . $p_sistol . '" , "' . $p_diastol . '" , "' .$fcard . '" ,"' . $temper . ' ","' . $fecha_actual . ' ")') or die ('<p>Error al registrar</p><br>'.mysqli_error($conexion));



$insert=mysqli_query($conexion,'INSERT INTO dat_trans_sangre(id_atencion,id_usua,fecht,numt,cont,hor_in,t,td,tde,a,ad,ade,fc,fcd,fcde,temp_t,temp_td,temp_tde,hor_t,vol,nom,estgen,fecha_act) VALUES ('.$id_atencion.','.$id_usua.',"'.$fec_tras.'","'.$num_tras.'","'.$cont_tras.'","'.$inicio_tras.'","'.$sisto_pre.'","'.$sisto_tras.'","'.$p_sistol.'","'.$diasto_pre.'","'.$diasto_tras.'","'.$p_diastol.'","'.$fc_pre.'","'.$fc_tras.'","'.$fcard.'","'.$temp_pre.'","'.$temp_tras.'","'.$temper.'","'.$fin_tras.'","'.$vol_tras.'","'.$medi_tras.'","'.$ob_tras.'","' . $fecha_actual . ' ")') or die ('<p>Error al registrar</p><br>'.mysqli_error($conexion));  

header('location: ../lista_pacientes/vista_pac_enf.php');