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
    $riesgo_quirurgico = $conexion->real_escape_string($_POST['riesgo_quirurgico'] ?? '');
    $info_riesgo = $conexion->real_escape_string($_POST['info_riesgo'] ?? '');
    $analisis_sangre = $conexion->real_escape_string($_POST['analisis_sangre'] ?? '');
    $cv = $conexion->real_escape_string($_POST['cv'] ?? '');
    $ecografia = $conexion->real_escape_string($_POST['ecografia'] ?? '');
    $oct_hrt = $conexion->real_escape_string($_POST['oct_hrt'] ?? '');
    $fag = $conexion->real_escape_string($_POST['fag'] ?? '');
    $ubm = $conexion->real_escape_string($_POST['ubm'] ?? '');
    $constante = $conexion->real_escape_string($_POST['constante'] ?? '');
    
    // Right Eye
    $estudio_derecho = isset($_POST['estudio_derecho']) ? 1 : 0;
    $hallazgos_derecho = $conexion->real_escape_string($_POST['hallazgos_derecho'] ?? '');
    $constante_derecho = $conexion->real_escape_string($_POST['constante_derecho'] ?? '');
    
    // Left Eye
    $estudio_izquierdo = isset($_POST['estudio_izquierdo']) ? 1 : 0;
    $hallazgos_izquierdo = $conexion->real_escape_string($_POST['hallazgos_izquierdo'] ?? '');
    $constante_izquierdo = $conexion->real_escape_string($_POST['constante_izquierdo'] ?? '');

    $sql = "INSERT INTO ocular_estudios (
        id_atencion, Id_exp, riesgo_quirurgico, info_riesgo, analisis_sangre, cv, ecografia, oct_hrt, fag, ubm, constante,
        estudio_derecho, hallazgos_derecho, constante_derecho,
        estudio_izquierdo, hallazgos_izquierdo, constante_izquierdo
    ) VALUES (
        '$id_atencion', '$id_exp', '$riesgo_quirurgico', '$info_riesgo', '$analisis_sangre', '$cv', '$ecografia', '$oct_hrt', '$fag', '$ubm', '$constante',
        '$estudio_derecho', '$hallazgos_derecho', '$constante_derecho',
        '$estudio_izquierdo', '$hallazgos_izquierdo', '$constante_izquierdo'
    )";
    
    if ($conexion->query($sql) === TRUE) {
        echo "<script>alert('Estudios guardados exitosamente'); window.location.href='estudios.php';</script>";
    } else {
        echo "<script>alert('Error al guardar: " . $conexion->error . "'); window.history.back();</script>";
    }
}

$conexion->close();
?>