<?php
session_start();
include "../../conexionbd.php";
$resultado = $conexion->query("select * from reg_usuarios") or die($conexion->error);
$usuario = $_SESSION['login'];

            $usuario = $_SESSION['login'];
            $id_usua= $usuario['id_usua'];
            $id_atencion = $_SESSION['pac'];

$dispositivos =  mysqli_real_escape_string($conexion, (strip_tags($_POST["dispositivos"], ENT_QUOTES)));
$tipo =  mysqli_real_escape_string($conexion, (strip_tags($_POST["tipo"], ENT_QUOTES)));
$fecha_inst =  mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha_inst"], ENT_QUOTES)));
$instalo =  mysqli_real_escape_string($conexion, (strip_tags($_POST["instalo"], ENT_QUOTES)));
//$dias_inst =  mysqli_real_escape_string($conexion, (strip_tags($_POST["dias_inst"], ENT_QUOTES)));
//$fecha_cambio =  mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha_cambio"], ENT_QUOTES)));
$cultivo =  mysqli_real_escape_string($conexion, (strip_tags($_POST["cultivo"], ENT_QUOTES)));


//date_default_timezone_set('America/Guatemala');
$fr = date("Y-m-d H:i");
   $ingresar4 = mysqli_query($conexion, 'INSERT INTO cate_enf_ter (id_atencion,id_usua,dispositivos,tipo,fecha_inst,instalo,dias_inst,fecha_cambio,cultivo,fecha_registro,tip) values ('.$id_atencion.',' . $id_usua . ',"' . $dispositivos .'","' . $tipo . '","'.$fecha_inst.'","'.$instalo.'","'.$dias_inst.'","'.$fecha_cambio.'","'.$cultivo.'","'.$fr.'","Quirofano") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));
echo mysqli_query($conexion,$ingresar4);
      
    ?>