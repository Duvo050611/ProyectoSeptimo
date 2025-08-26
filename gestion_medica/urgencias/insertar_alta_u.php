<?php
//se establece una conexion a la base de datos
include '../../conexionbd.php';
//si se han enviado datos
$id_usua = $_GET['id_usua'];
$id_atencion = $_GET['id_atencion'];

$alta_por_u=$_POST['alta_por_u'];
$fech_alta_u=$_POST['fech_alta_u'];
$hor_alta_u=$_POST['hor_alta_u'];

date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

$insert=mysqli_query($conexion,'INSERT INTO alta_urgencias (id_atencion,id_usua,alta_por_u,fech_alta_u,hor_alta_u,fecha_altamed_u) VALUES ('.$id_atencion.','.$id_usua.',"'.$alta_por_u.'","'.$fech_alta_u.'","'.$hor_alta_u.'","'.$fecha_actual.'")') or die ('<p>Error al registrar</p><br>'.mysqli_error($conexion));


$sql2 = "UPDATE dat_ingreso SET alta_med = 'SI', fecha_alt_med = '$fech_alta_u', hora_alt_med = '$hor_alta_u' WHERE id_atencion = $id_atencion";
      $result = $conexion->query($sql2);



header ('location: ../urgencias/vista_alta_urgencias.php');