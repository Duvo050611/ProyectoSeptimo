<?php
//se establece una conexion a la base de datos
session_start();
include '../../conexionbd.php';

$usuario = $_SESSION['login'];
$id_usua= $usuario['id_usua'];



$id_atencion=$_SESSION['pac'];



	$fecht=$_POST['fecht'];
	$numt=$_POST['numt'];
	$cont=$_POST['cont'];
	$hor_in=$_POST['hor_in'];
	$t=$_POST['t'];
	//$td=$_POST['td'];
	//$tde=$_POST['tde'];
	$a=$_POST['a'];
	//$ad=$_POST['ad'];
	//$ade=$_POST['ade'];
	$fc=$_POST['fc'];
	//$fcd=$_POST['fcd'];
	//$fcde=$_POST['fcde'];
	$temp_t=$_POST['temp_t'];
	//$temp_td=$_POST['temp_td'];
	//$temp_tde=$_POST['temp_tde'];
	$hor_t=$_POST['hor_t'];
	$vol=$_POST['vol'];
	$nom=$_POST['nom'];
	$estgen=$_POST['estgen'];
	//////////////////////////////////////////////
/*	$fecht2=$_POST['fecht2'];
	$numt2=$_POST['numt2'];
	$cont2=$_POST['cont2'];
	$hor_in2=$_POST['hor_in2'];
	$t2=$_POST['t2'];
	//$td2=$_POST['td2'];
	//$tde2=$_POST['tde2'];
	$a2=$_POST['a2'];
	//$ad2=$_POST['ad2'];
	//$ade2=$_POST['ade2'];
	$fc2=$_POST['fc2'];
	//$fcd2=$_POST['fcd2'];
	//$fcde2=$_POST['fcde2'];
	$temp_t2=$_POST['temp_t2'];
	//$temp_td2=$_POST['temp_td2'];
	//$temp_tde2=$_POST['temp_tde2'];
	$hor_t2=$_POST['hor_t2'];
	$vol2=$_POST['vol2'];
	$nom2=$_POST['nom2'];
	$estgen2=$_POST['estgen2'];*/
	/////////////////////////////////////////////
  /*   $fecht3=$_POST['fecht3'];
	$numt3=$_POST['numt3'];
	$cont3=$_POST['cont3'];
	$hor_in3=$_POST['hor_in3'];
	$t3=$_POST['t3'];
	//$td3=$_POST['td3'];
	//$tde3=$_POST['tde3'];
	$a3=$_POST['a3'];
	//$ad3=$_POST['ad3'];
	//$ade3=$_POST['ade3'];
	$fc3=$_POST['fc3'];
	//$fcd3=$_POST['fcd3'];
	//$fcde3=$_POST['fcde3'];
	$temp_t3=$_POST['temp_t3'];
	//$temp_td3=$_POST['temp_td3'];
	//$temp_tde3=$_POST['temp_tde3'];
	$hor_t3=$_POST['hor_t3'];
	$vol3=$_POST['vol3'];
	$nom3=$_POST['nom3'];
	$estgen3=$_POST['estgen3'];*/
	////////////////////////////////////////////////


//date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

$insert=mysqli_query($conexion,'INSERT INTO dat_trans_sangre(id_atencion,id_usua,fecht,numt,cont,hor_in,t,td,tde,a,ad,ade,fc,fcd,fcde,temp_t,temp_td,temp_tde,hor_t,vol,nom,estgen,fecha_act) VALUE('.$id_atencion.','.$id_usua.',"'.$fecht.'","'.$numt.'","'.$cont.'","'.$hor_in.'","'.$t.'","'.$td.'","'.$tde.'","'.$a.'","'.$ad.'","'.$ade.'","'.$fc.'","'.$fcd.'","'.$fcde.'","'.$temp_t.'","'.$temp_td.'","'.$temp_tde.'","'.$hor_t.'","'.$vol.'","'.$nom.'","'.$estgen.'","'.$fecha_actual.'")') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));


if($_POST['fecht2'] != NULL){
	$usuario = $_SESSION['login'];
$id_usua2= $usuario['id_usua'];

$id_atencion2=$_SESSION['pac'];
   
    

	$insert1=mysqli_query($conexion,'INSERT INTO dat_trans_sangre(id_atencion,id_usua,fecht,numt,cont,hor_in,t,td,tde,a,ad,ade,fc,fcd,fcde,temp_t,temp_td,temp_tde,hor_t,vol,nom,estgen,fecha_act) VALUE('.$id_atencion2.','.$id_usua2.',"'.$fecht2.'","'.$numt2.'","'.$cont2.'","'.$hor_in2.'","'.$t2.'","'.$td2.'","'.$tde2.'","'.$a2.'","'.$ad2.'","'.$ade2.'","'.$fc2.'","'.$fcd2.'","'.$fcde2.'","'.$temp_t2.'","'.$temp_td2.'","'.$temp_tde2.'","'.$hor_t2.'","'.$vol2.'","'.$nom2.'","'.$estgen2.'","'.$fecha_actual.'")') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));


}else{

	header('location: ../lista_pacientes/vista_pac_enf.php');
}
	

if($_POST['fecht3'] != NULL){

	$usuario = $_SESSION['login'];
$id_usua3= $usuario['id_usua'];

$id_atencion3=$_SESSION['pac'];


$insert2=mysqli_query($conexion,'INSERT INTO dat_trans_sangre(id_atencion,id_usua,fecht,numt,cont,hor_in,t,td,tde,a,ad,ade,fc,fcd,fcde,temp_t,temp_td,temp_tde,hor_t,vol,nom,estgen,fecha_act) VALUE('.$id_atencion3.','.$id_usua3.',"'.$fecht3.'","'.$numt3.'","'.$cont3.'","'.$hor_in3.'","'.$t3.'","'.$td3.'","'.$tde3.'","'.$a3.'","'.$ad3.'","'.$ade3.'","'.$fc3.'","'.$fcd3.'","'.$fcde3.'","'.$temp_t3.'","'.$temp_td3.'","'.$temp_tde3.'","'.$hor_t3.'","'.$vol3.'","'.$nom3.'","'.$estgen3.'","'.$fecha_actual.'")') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));


}else{

	header('location: ../lista_pacientes/vista_pac_enf.php');
}



header('location: ../lista_pacientes/vista_pac_enf.php');
