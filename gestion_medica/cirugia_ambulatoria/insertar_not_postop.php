<?php
session_start();
include '../../conexionbd.php';
$usuario = $_SESSION['login'];
$id_usua= $usuario['id_usua'];
$id_atencion = $_GET['id_atencion'];

date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

//$diag_preop=$_POST['diag_preop'];
$diag_postop=$_POST['diag_postop'];
$cir_progra=$_POST['cir_progra'];

$sang=$_POST['sang'];
$complic=$_POST['complic'];
$in_ac=$_POST['in_ac'];
$cuent_tex=$_POST['cuent_tex'];

$hallazgos=$_POST['hallazgos'];
$estado_post=$_POST['estado_post'];

$ten_sist=$_POST['ten_sist'];
$ten_diast=$_POST['ten_diast'];
$frec=$_POST['frec'];
$frecresp=$_POST['frecresp'];
$tempera=$_POST['tempera'];
//$cirujano=$_POST['cirujano'];
$tec=$_POST['tec'];
$plan_tera=$_POST['plan_tera'];
$pron=$_POST['pron'];
$resum_clin=$_POST['resum_clin'];
$fecha_post=$_POST['fecha_post'];

$resultado1 = $conexion->query("select dat_not_preop_amb.*,dat_ingreso.*
from dat_ingreso
inner join dat_not_preop_amb on dat_ingreso.id_atencion=dat_not_preop_amb.id_atencion
" ) or die($conexion->error);
   while ($f1 = mysqli_fetch_array($resultado1)) {
$diag_preop_amb=$f1['diag_preop_amb'];
$cirujano=$f1['nom_medi_cir'];
   }

   $resultado2 = $conexion->query("select dat_not_inquir_amb.*,dat_ingreso.*
from dat_ingreso
inner join dat_not_inquir_amb on dat_ingreso.id_atencion=dat_not_inquir_amb.id_atencion" ) or die($conexion->error);
   while ($f2 = mysqli_fetch_array($resultado2)) {

$cir_real=$f2['cir_realizada'];
$ayud1=$f2['prim_ayudante'];
$ayud2=$f2['seg_ayudante'];
$ayud3=$f2['ter_ayudante'];
$anest=$f2['anestesiologo'];
$inst=$f2['instrumentista'];
$circu=$f2['circulante'];
$biops=$f2['trans'];
$envio=$f2['posto'];

   }

$result=mysqli_query($conexion,'INSERT INTO dat_not_postop_amb (id_atencion,id_usua,diag_preop,diag_postop,cir_progra,cir_real,cirujano,ayud1,ayud2,ayud3,anest,inst,circu,sang,complic,in_ac,cuent_tex,biops,envio,hallazgos,estado_post,exp_fis,ten_sist,ten_diast,frec,frecresp,tempera,lab,tec,plan_tera,pron,resum_clin,fecha_post) 
	VALUES('.$id_atencion.','.$id_usua.',"'.$diag_preop.'","'.$diag_postop.'","'.$cir_progra.'","'.$cir_real.'","'.$cirujano.'","'.$ayud1.'","'.$ayud2.'","'.$ayud3.'","'.$anest.'","'.$inst.'","'.$circu.'","'.$sang.'","'.$complic.'","'.$in_ac.'","'.$cuent_tex.'","'.$biops.'","'.$envio.'","'.$hallazgos.'","'.$estado_post.'","'.$exp_fis.'","'.$ten_sist.'","'.$ten_diast.'","'.$frec.'","'.$frecresp.'","'.$tempera.'","'.$lab.'","'.$tec.'","'.$plan_tera.'","'.$pron.'","'.$resum_clin.'","'.$fecha_actual.'")')or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));


header('location: ../../template/menu_medico.php');

?>