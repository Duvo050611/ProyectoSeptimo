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
    
    $oftalmologicamente_sano = isset($_POST['oftalmologicamente_sano']) ? 1 : 0;
    $sin_diagnostico_cie10 = isset($_POST['sin_diagnostico_cie10']) ? 1 : 0;
    $diagnostico_previo = $conexion->real_escape_string($_POST['diagnostico_previo'] ?? '');
    
    // Right Eye
    $diagnostico_principal_derecho = $conexion->real_escape_string($_POST['diagnostico_principal_derecho'] ?? '');
    $codigo_cie_derecho = $conexion->real_escape_string($_POST['codigo_cie_derecho'] ?? '');
    $desc_cie_derecho = $conexion->real_escape_string($_POST['desc_cie_derecho'] ?? '');
    $tipo_diagnostico_derecho = $conexion->real_escape_string($_POST['tipo_diagnostico_derecho'] ?? '');
    $otros_diagnosticos_derecho = $conexion->real_escape_string($_POST['otros_diagnosticos_derecho'] ?? '');
    
    // Left Eye
    $diagnostico_principal_izquierdo = $conexion->real_escape_string($_POST['diagnostico_principal_izquierdo'] ?? '');
    $codigo_cie_izquierdo = $conexion->real_escape_string($_POST['codigo_cie_izquierdo'] ?? '');
    $desc_cie_izquierdo = $conexion->real_escape_string($_POST['desc_cie_izquierdo'] ?? '');
    $tipo_diagnostico_izquierdo = $conexion->real_escape_string($_POST['tipo_diagnostico_izquierdo'] ?? '');
    $otros_diagnosticos_izquierdo = $conexion->real_escape_string($_POST['otros_diagnosticos_izquierdo'] ?? '');

    $sql = "INSERT INTO ocular_diagnostico (
        id_atencion, Id_exp, oftalmologicamente_sano, sin_diagnostico_cie10, diagnostico_previo,
        diagnostico_principal_derecho, codigo_cie_derecho, desc_cie_derecho, tipo_diagnostico_derecho, otros_diagnosticos_derecho,
        diagnostico_principal_izquierdo, codigo_cie_izquierdo, desc_cie_izquierdo, tipo_diagnostico_izquierdo, otros_diagnosticos_izquierdo
    ) VALUES (
        '$id_atencion', '$id_exp', '$oftalmologicamente_sano', '$sin_diagnostico_cie10', '$diagnostico_previo',
        '$diagnostico_principal_derecho', '$codigo_cie_derecho', '$desc_cie_derecho', '$tipo_diagnostico_derecho', '$otros_diagnosticos_derecho',
        '$diagnostico_principal_izquierdo', '$codigo_cie_izquierdo', '$desc_cie_izquierdo', '$tipo_diagnostico_izquierdo', '$otros_diagnosticos_izquierdo'
    )";
    
    if ($conexion->query($sql) === TRUE) {
        echo "<script>alert('Diagnóstico guardado exitosamente'); window.location.href='diagnostico.php';</script>";
    } else {
        echo "<script>alert('Error al guardar: " . $conexion->error . "'); window.history.back();</script>";
    }
}

$conexion->close();
?>