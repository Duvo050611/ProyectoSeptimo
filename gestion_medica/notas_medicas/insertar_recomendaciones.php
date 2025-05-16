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
    $tipo_recomendacion = $conexion->real_escape_string($_POST['tipo_recomendacion']);
    $diagnostico_relacionado = $conexion->real_escape_string($_POST['diagnostico_relacionado']);
    $recomendaciones_generales = $conexion->real_escape_string($_POST['recomendaciones_generales'] ?? '');
    $plan_seguimiento = $conexion->real_escape_string($_POST['plan_seguimiento'] ?? '');
    
    // Right Eye
    $recomendacion_derecho = isset($_POST['recomendacion_derecho']) ? 1 : 0;
    $detalles_derecho = $conexion->real_escape_string($_POST['detalles_derecho'] ?? '');
    $notas_derecho = $conexion->real_escape_string($_POST['notas_derecho'] ?? '');
    
    // Left Eye
    $recomendacion_izquierdo = isset($_POST['recomendacion_izquierdo']) ? 1 : 0;
    $detalles_izquierdo = $conexion->real_escape_string($_POST['detalles_izquierdo'] ?? '');
    $notas_izquierdo = $conexion->real_escape_string($_POST['notas_izquierdo'] ?? '');

    $sql = "INSERT INTO ocular_recomendaciones (
        id_atencion, Id_exp, tipo_recomendacion, diagnostico_relacionado, recomendaciones_generales, plan_seguimiento,
        recomendacion_derecho, detalles_derecho, notas_derecho,
        recomendacion_izquierdo, detalles_izquierdo, notas_izquierdo
    ) VALUES (
        '$id_atencion', '$id_exp', '$tipo_recomendacion', '$diagnostico_relacionado', '$recomendaciones_generales', '$plan_seguimiento',
        '$recomendacion_derecho', '$detalles_derecho', '$notas_derecho',
        '$recomendacion_izquierdo', '$detalles_izquierdo', '$notas_izquierdo'
    )";
    
    if ($conexion->query($sql) === TRUE) {
        echo "<script>alert('Recomendaciones guardadas exitosamente'); window.location.href='recomendaciones.php';</script>";
    } else {
        echo "<script>alert('Error al guardar: " . $conexion->error . "'); window.history.back();</script>";
    }
}

$conexion->close();
?>