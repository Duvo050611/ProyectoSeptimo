<?php
session_start();
include "../../conexionbd.php";

if (!isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

$id_atencion = $_SESSION['hospital'] ?? null;
$peso = $_POST['peso'] ?? null;
$talla = $_POST['talla'] ?? null;
$imc = $_POST['imc'] ?? null;
$cintura = $_POST['cintura'] ?? null;
$presion_sistolica = $_POST['presion_sistolica'] ?? null;
$presion_diastolica = $_POST['presion_diastolica'] ?? null;
$frecuencia_cardiaca = $_POST['frecuencia_cardiaca'] ?? null;
$frecuencia_respiratoria = $_POST['frecuencia_respiratoria'] ?? null;
$temperatura = $_POST['temperatura'] ?? null;
$spo2 = $_POST['spo2'] ?? null;
$glucemia = $_POST['glucemia'] ?? null;
$glucosa_ayunas = $_POST['glucosa_ayunas'] ?? 0;
$dificultad = $_POST['dificultad'] ?? 0;
$dificultad_especifica = $_POST['dificultad_especifica'] ?? '';
$tipo_dificultad = $_POST['tipo_dificultad'] ?? '';
$grado_dificultad = $_POST['grado_dificultad'] ?? '';
$origen_dificultad = $_POST['origen_dificultad'] ?? '';
$tuberculosis_probable = $_POST['tuberculosis_probable'] ?? '';
$habito_exterior = $_POST['habito_exterior'] ?? '';
$exploracion = $_POST['exploracion'] ?? '';

$stmt = $conexion->prepare("
    INSERT INTO exploracion_fisica (
        id_atencion, peso, talla, imc, cintura, presion_sistolica, presion_diastolica,
        frecuencia_cardiaca, frecuencia_respiratoria, temperatura, spo2, glucemia, glucosa_ayunas,
        dificultad, dificultad_especifica, tipo_dificultad, grado_dificultad, origen_dificultad,
        tuberculosis_probable, habito_exterior, exploracion
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?,
        ?, ?, ?
    )
");

$stmt->bind_param(
    "iddddiiiididiiissssss",
    $id_atencion, $peso, $talla, $imc, $cintura, $presion_sistolica, $presion_diastolica,
    $frecuencia_cardiaca, $frecuencia_respiratoria, $temperatura, $spo2, $glucemia, $glucosa_ayunas,
    $dificultad, $dificultad_especifica, $tipo_dificultad, $grado_dificultad, $origen_dificultad,
    $tuberculosis_probable, $habito_exterior, $exploracion
);

if ($stmt->execute()) {
    $_SESSION['message'] = "Exploración física guardada correctamente.";
    $_SESSION['message_type'] = "success";
} else {
    $_SESSION['message'] = "Error al guardar la exploración: " . $stmt->error;
    $_SESSION['message_type'] = "danger";
}
$stmt->close();
header("Location: exploracion_fisica.php");
exit;
?>