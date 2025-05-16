<?php
date_default_timezone_set('America/Guatemala');

$servidor="localhost";
$nombreBd="ineo";
$usuario="admin";
$pass="01manager1";
$conexion=new mysqli($servidor,$usuario,$pass,$nombreBd);
$conexion -> set_charset("utf8");
if($conexion-> connect_error){
die("No se pudo conectar a INEO");
}
?>