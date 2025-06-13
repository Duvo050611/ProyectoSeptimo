<?php
include "../../conexionbd.php";
session_start();
if (!isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}
$id_atencion = $_SESSION['hospital'] ?? null;

$observaciones = $_POST['observaciones'] ?? '';
$sinto = isset($_POST['sinto']) ? implode(',', $_POST['sinto']) : '';
$sinto_otros = $_POST['sinto_otros'] ?? '';
$heredo = isset($_POST['heredo']) ? implode(',', $_POST['heredo']) : '';
$heredo_otros = $_POST['heredo_otros'] ?? '';
$nopat = isset($_POST['nopat']) ? implode(',', $_POST['nopat']) : '';
$nopat_otros = $_POST['nopat_otros'] ?? '';
$pat_interrogados = $_POST['pat_interrogados'] ?? '';
$pat_enf = isset($_POST['pat_enf']) ? implode(',', $_POST['pat_enf']) : '';
$pat_medicamentos = isset($_POST['pat_medicamentos']) ? implode(',', $_POST['pat_medicamentos']) : '';
$pat_otras_alergias = isset($_POST['pat_otras_alergias']) ? implode(',', $_POST['pat_otras_alergias']) : '';
$pat_oculares = isset($_POST['pat_oculares']) ? implode(',', $_POST['pat_oculares']) : '';
$pat_otras_cirugias = isset($_POST['pat_otras_cirugias']) ? implode(',', $_POST['pat_otras_cirugias']) : '';

$stmt = $conexion->prepare("INSERT INTO historia_clinica (
    id_atencion, observaciones, sinto, sinto_otros, heredo, heredo_otros, nopat, nopat_otros,
    pat_interrogados, pat_enf, pat_medicamentos, pat_otras_alergias, pat_oculares, pat_otras_cirugias
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param(
    "isssssssssssss",
    $id_atencion, $observaciones, $sinto, $sinto_otros, $heredo, $heredo_otros, $nopat, $nopat_otros,
    $pat_interrogados, $pat_enf, $pat_medicamentos, $pat_otras_alergias, $pat_oculares, $pat_otras_cirugias
);
if ($stmt->execute()) {
    $_SESSION['message'] = "Historial clinico guardada correctamente.";
    $_SESSION['message_type'] = "success";
} else {
    $_SESSION['message'] = "Error al guardar la exploración: " . $stmt->error;
    $_SESSION['message_type'] = "danger";
}
$stmt->close();
header("Location: his_clinica.php");
exit;
?>