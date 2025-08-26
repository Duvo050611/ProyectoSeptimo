<?php
session_start();
include '../../conexionbd.php';
$usuario = $_SESSION['login'];
$id_usua= $usuario['id_usua'];
$id_atencion =$_GET['id_atencion'];

date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

if (isset($_POST['tipo_cirugia_preop'])) {
	if($tipo_cirugia_preop="URGENCIAS"){
$tipo_cirugia_preop= $_POST['tipo_cirugia_preop'];
	}

	if($tipo_cirugia_preop="ELECTIVA"){
$tipo_cirugia_preop= $_POST['tipo_cirugia_preop'];
	}

}

//$cama_preop=$_POST['cama_preop'];
$ta_sist=$_POST['ta_sist'];
$ta_diast=$_POST['ta_diast'];
$frec_card=$_POST['frec_card'];
$frec_resp=$_POST['frec_resp'];
$sat_oxi=$_POST['sat_oxi'];
$preop_temp=$_POST['preop_temp'];
$preop_peso=$_POST['preop_peso'];
$preop_talla=$_POST['preop_talla'];
$cabeza=$_POST['cabeza'];
$cuello=$_POST['cuello'];

$torax=$_POST['torax'];
$abdomen=$_POST['abdomen'];
$extrem=$_POST['extrem'];
$colum_vert=$_POST['colum_vert'];

$resumen_clin=$_POST['resumen_clin'];
$beneficios=$_POST['beneficios'];
$result_lab_gab=$_POST['result_lab_gab'];
$diag_preop=$_POST['diag_preop'];
$pronostico=$_POST['pronostico'];
$riesgos=$_POST['riesgos'];
$cuidados=$_POST['cuidados'];
$tipo_inter_plan=$_POST['tipo_inter_plan'];
$fecha_cir=$_POST['fecha_cir'];
$hora_cir=$_POST['hora_cir'];
$tiempo_estimado=$_POST['tiempo_estimado'];
$nom_medi_cir=$_POST['nom_medi_cir'];
$anestesia_sug=$_POST['anestesia_sug'];
$sangrado_esp=$_POST['sangrado_esp'];
$observ=$_POST['observ'];

$fecha_preop=$_POST['fecha_preop'];


$resultado5=$conexion->query("select * from signos_vitales WHERE id_atencion=$id_atencion ORDER by id_sig DESC LIMIT 1") or die($conexion->error);

     while ($f5 = mysqli_fetch_array($resultado5)) {
    $p_sistol=$f5['p_sistol'];
    $p_diastol=$f5['p_diastol'];
    $fcard=$f5['fcard'];
    $fresp=$f5['fresp'];
    $temper=$f5['temper'];
    $satoxi=$f5['satoxi'];
    $peso=$f5['peso'];
    $talla=$f5['talla'];
    }

$result=mysqli_query($conexion,'INSERT INTO dat_not_preop_amb (id_atencion,id_usua,tipo_cirugia_preop,ta_sist,ta_diast,frec_card,frec_resp,sat_oxi,preop_temp,preop_peso,preop_talla,cabeza,cuello,torax,abdomen,extrem,colum_vert,observ,diag_preop,fecha_cir,hora_cir,tipo_inter_plan,beneficios,riesgos,cuidados,pronostico,anestesia_sug,nom_medi_cir,tiempo_estimado,sangrado_esp,resumen_clin,result_lab_gab,fecha_preop) 
	VALUES('.$id_atencion.','.$id_usua.',"'.$tipo_cirugia_preop.'","'.$p_sistol.'","'.$p_diastol.'","'.$fcard.'","'.$fresp.'","'.$satoxi.'","'.$temper.'","'.$peso.'","'.$talla.'","'.$cabeza.'","'.$cuello.'","'.$torax.'","'.$abdomen.'","'.$extrem.'","'.$colum_vert.'","'.$observ.'","'.$diag_preop.'","'.$fecha_cir.'","'.$hora_cir.'","'.$tipo_inter_plan.'","'.$beneficios.'","'.$riesgos.'","'.$cuidados.'","'.$pronostico.'","'.$anestesia_sug.'","'.$nom_medi_cir.'","'.$tiempo_estimado.'","'.$sangrado_esp.'","'.$resumen_clin.'","'.$result_lab_gab.'","'.$fecha_actual.'")')or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

header('location: ../../template/menu_medico.php');


?>