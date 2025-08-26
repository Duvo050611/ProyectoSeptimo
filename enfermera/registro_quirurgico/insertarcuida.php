<?php 
session_start();
include "../../conexionbd.php";
$resultado = $conexion->query("select * from reg_usuarios") or die($conexion->error);
$usuario = $_SESSION['login'];

            $usuario = $_SESSION['login'];
            $id_usua= $usuario['id_usua'];
            $id_atencion = $_SESSION['pac'];

$observ_be = mysqli_real_escape_string($conexion, (strip_tags($_POST['observ_be'], ENT_QUOTES)));
$gca = mysqli_real_escape_string($conexion, (strip_tags($_POST['gca'], ENT_QUOTES)));


if($_POST['gcat']){$gcat=$_POST['gcat'];}else{ $gcat='';}
if($_POST['sont']){$son=$_POST['sont'];}else{ $sont='';}

$son = mysqli_real_escape_string($conexion, (strip_tags($_POST['son'], ENT_QUOTES)));
$dieta = mysqli_real_escape_string($conexion, (strip_tags($_POST['dieta'], ENT_QUOTES)));
   

//date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

$resultado=$conexion->query("SELECT * from reg_usuarios WHERE id_usua= $id_usua") or die($conexion->error);
    while ($r = mysqli_fetch_array($resultado)) {
 $nombre=$r['nombre'];
 $papell=$r['papell'];
 $sapell=$r['sapell'];
}

$nombre_medico=$nombre.' '.$papell.' '.$sapell;
//date_default_timezone_set('America/Guatemala');
$hora_ord = date("H:i:s");
$insertar = mysqli_query($conexion, 'INSERT INTO dat_ordenes_med(id_atencion,id_usua,fecha_ord,hora_ord,dieta,observ_be,medico,tipo,gca,gcat,son,sont) values (' . $id_atencion . ',' . $id_usua . ',"' . $fecha_actual . '","' . $hora_ord . '","'.$dieta.'","' . $observ_be . '","'.$nombre_medico.'","QUIRURGICO","'.$gca.'","'.$gcat.'","'.$son.'","'.$sont.'")') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

       echo mysqli_query($conexion,$ingresar);
       // echo '<script type="text/javascript">window.location ="../lista_pacientes/vista_pac_enf.php"</script>';
      

 ?>