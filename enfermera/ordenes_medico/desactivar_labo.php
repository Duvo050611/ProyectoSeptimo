<?php
session_start();
include '../../conexionbd.php';
$conexion = ConexionBD::getInstancia()->getConexion();
$id = $_GET['not_id'];

$resultado = $conexion->query("UPDATE `notificaciones_labo` SET ACTIVO = 'NO' WHERE not_id = $id") or die($conexion->error);

header('location: sol_laboratorio.php');
?>