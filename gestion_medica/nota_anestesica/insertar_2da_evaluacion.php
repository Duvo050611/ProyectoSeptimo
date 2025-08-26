<?php
session_start();
include '../../conexionbd.php';
$usuario = $_SESSION['login'];
$id_usua= $usuario['id_usua'];
$id_atencion = $_SESSION['hospital'];

$fecha2	=$_POST['fecha2'];
$hora2=$_POST['hora2'];
//$ansed=$_POST['ansed'];
$diaproc=$_POST['diaproc'];
$sist=$_POST['sist'];
$diast=$_POST['diast'];
$freccard=$_POST['freccard'];
$frecresp=$_POST['frecresp'];
$temp=$_POST['temp'];
$spo2=$_POST['spo2'];
$med_pre=$_POST['med_pre'];
$dosis=$_POST['dosis'];
$via=$_POST['via'];
$fechamedi=$_POST['fechamedi'];
$horamedi=$_POST['horamedi'];
$efect=$_POST['efect'];
$med_pre2=$_POST['med_pre2'];

if (isset($_POST['dosis2'])) {$dosis2=$_POST['dosis2'];}else{$dosis2='NO';}
if (isset($_POST['via2'])) {$via2=$_POST['via2'];}else{$via2='NO';}

if (isset($_POST['fechamedi2'])) {$fechamedi2=$_POST['fechamedi2'];}else{$fechamedi2='';}

if (isset($_POST['horamedi2'])) {$horamedi2=$_POST['horamedi2'];}else{$horamedi2='';}

if (isset($_POST['efect2'])) {$efect2=$_POST['efect2'];}else{$efect2='';}

$hora_ver=$_POST['hora_ver'];

if(isset($_POST['apan'])){$apan=$_POST['apan'];}else{$apan='NO';}

if(isset($_POST['vent'])){$vent=$_POST['vent'];}else{$vent='NO';}

if(isset($_POST['fuen'])){$fuen=$_POST['fuen'];}else{$fuen='NO';}

if(isset($_POST['ecg'])){$ecg=$_POST['ecg'];}else{$ecg='NO';}

if(isset($_POST['circ'])){$circ=$_POST['circ'];}else{$circ='NO';}

if(isset($_POST['para'])){$para=$_POST['para'];}else{$para='NO';}

if(isset($_POST['fuent'])){$fuent=$_POST['fuent'];}else{$fuent='NO';}

if(isset($_POST['pani'])){$pani=$_POST['pani'];}else{$pani='NO';}

if(isset($_POST['fugas'])){$fugas=$_POST['fugas'];}else{$fugas='NO';}

if(isset($_POST['flujo'])){$flujo=$_POST['flujo'];}else{$flujo='NO';}

if(isset($_POST['spo'])){$spo=$_POST['spo'];}else{$spo='NO';}

if(isset($_POST['cal'])){$cal=$_POST['cal'];}else{$cal='NO';}

if(isset($_POST['vapo'])){$vapo=$_POST['vapo'];}else{$vapo='NO';}

if(isset($_POST['co2'])){$co2=$_POST['co2'];}else{$co2='NO';}

if(isset($_POST['ana'])){$ana=$_POST['ana'];}else{$ana='NO';}

if(isset($_POST['indice'])){$indice=$_POST['indice'];}else{$indice='NO';}

if(isset($_POST['bomba'])){$bomba=$_POST['bomba'];}else{$bomba='NO';}

if(isset($_POST['moni'])){$moni=$_POST['moni'];}else{$moni='NO';}

$obser=$_POST['obser'];
$goldman=$_POST['goldman'];
$asa=$_POST['asa'];
date_default_timezone_set('America/Guatemala');
$fecha_actual=date("Y-m-d H:i:s");



$ingresar = mysqli_query($conexion,'INSERT INTO dat_seg_evol (id_atencion,id_usua,fecha2,hora2,diaproc,sist,diast,freccard,frecresp,temp,spo2,med_pre,dosis,via,fechamedi,horamedi,efect,med_pre2,dosis2,via2,fechamedi2,horamedi2,efect2,hora_ver,apan,vent,fuen,ecg,circ,para,fuent,pani,fugas,flujo,spo,cal,vapo,co2,ana,indice,bomba,moni,obser,fecha_nota,goldman,asa) values('.$id_atencion.','.$id_usua.',"'.$fecha2.'","'.$hora2.'","'.$diaproc.'","'.$sist.'","'.$diast.'","'.$freccard.'","'.$frecresp.'","'.$temp.'","'.$spo2.'","'.$med_pre.'","'.$dosis.'","'.$via.'","'.$fechamedi.'","'.$horamedi.'","'.$efect.'","'.$med_pre2.'","'.$dosis2.'","'.$via2.'","'.$fechamedi2.'","'.$horamedi2.'","'.$efect2.'","'.$hora_ver.'","'.$apan.'","'.$vent.'","'.$fuen.'","'.$ecg.'","'.$circ.'","'.$para.'","'.$fuent.'","'.$pani.'","'.$fugas.'","'.$flujo.'","'.$spo.'","'.$cal.'","'.$vapo.'","'.$co2.'","'.$ana.'","'.$indice.'","'.$bomba.'","'.$moni.'","'.$obser.'","'.$fecha_actual.'","'.$goldman.'","'.$asa.'")') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));


header('location: ../hospitalizacion/vista_pac_hosp.php');

?>