<?php
$host = "localhost";
$usuario = "root";
$contrasena = "1234"; 
$base_datos = "u542863078_ineo";

$conn = new mysqli($host, $usuario, $contrasena, $base_datos);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
