<?php
session_start();
include "../../conexionbd.php";

if (!isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_atencion = $_SESSION['hospital'];
    $id_exp = $conexion->query("SELECT Id_exp FROM dat_ingreso WHERE id_atencion = $id_atencion")->fetch_assoc()['Id_exp'];
    
    // Right Eye
    $lente_derecho = isset($_POST['lente_derecho']) ? 1 : 0;
    $marca_derecho = $conexion->real_escape_string($_POST['marca_derecho'] ?? '');
    $modelo_derecho = $conexion->real_escape_string($_POST['modelo_derecho'] ?? '');
    $otros_derecho = $conexion->real_escape_string($_POST['otros_derecho'] ?? '');
    $dioptrias_derecho = $conexion->real_escape_string($_POST['dioptrias_derecho'] ?? '');
    
    // Left Eye
    $lente_izquierdo = isset($_POST['lente_izquierdo']) ? 1 : 0;
    $marca_izquierdo = $conexion->real_escape_string($_POST['marca_izquierdo'] ?? '');
    $modelo_izquierdo = $conexion->real_escape_string($_POST['modelo_izquierdo'] ?? '');
    $otros_izquierdo = $conexion->real_escape_string($_POST['otros_izquierdo'] ?? '');
    $dioptrias_izquierdo = $conexion->real_escape_string($_POST['dioptrias_izquierdo'] ?? '');

    $sql = "INSERT INTO ocular_lente_intraocular (
        id_atencion, Id_exp,
        lente_derecho, marca_derecho, modelo_derecho, otros_derecho, dioptrias_derecho,
        lente_izquierdo, marca_izquierdo, modelo_izquierdo, otros_izquierdo, dioptrias_izquierdo
    ) VALUES (
        '$id_atencion', '$id_exp',
        '$lente_derecho', '$marca_derecho', '$modelo_derecho', '$otros_derecho', '$dioptrias_derecho',
        '$lente_izquierdo', '$marca_izquierdo', '$modelo_izquierdo', '$otros_izquierdo', '$dioptrias_izquierdo'
    )";
    
    if ($conexion->query($sql) === TRUE) {
        echo "<script>alert('Lente intraocular guardado exitosamente'); window.location.href='lente_intraocular.php';</script>";
    } else {
        echo "<script>alert('Error al guardar: " . $conexion->error . "'); window.history.back();</script>";
    }
}

$conexion->close();
?>