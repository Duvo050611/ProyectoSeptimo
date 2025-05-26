<?php
date_default_timezone_set('America/Guatemala');

$servidor="localhost";
$nombreBd="u542863078_ineo";
$usuario="u542863078_usu_ineo";
$pass="Us3r.INEO#2025";
$conexion=new mysqli($servidor,$usuario,$pass,$nombreBd);
$conexion -> set_charset("utf8");
if($conexion-> connect_error){
die("No se pudo conectar a INEO");
}
?>