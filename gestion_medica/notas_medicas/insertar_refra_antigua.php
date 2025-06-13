<?php
session_start();
include "../../conexionbd.php";

if (!isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

$id_atencion = $_SESSION['hospital'] ?? null;

$tipo_derecho = $_POST['tipo_derecho'] ?? '';
$av_lejana_derecho = $_POST['av_lejana_Derecho'] ?? '';
$av_lejana_lentes_derecho = $_POST['av_lejana_lentes_derecho'] ?? '';
$esf_lejana_derecho = $_POST['esf_lejana_derecho'] ?? '';
$cil_lejana_derecho = $_POST['cil_lejana_derecho'] ?? '';
$eje_lejana_derecho = $_POST['eje_lejana_derecho'] ?? '';
$add_lejana_derecho = $_POST['add_lejana_derecho'] ?? '';
$prisma_lejana_derecho = isset($_POST['prisma_lejana_derecho']) ? 1 : 0;
$av_cercana_derecho = $_POST['av_cercana_derecho'] ?? '';
$esf_cercana_derecho = $_POST['esf_cercana_derecho'] ?? '';
$cil_cercana_derecho = $_POST['cil_cercana_derecho'] ?? '';
$eje_cercana_derecho = $_POST['eje_cercana_derecho'] ?? '';
$add_cercana_derecho = $_POST['add_cercana_derecho'] ?? '';
$prisma_cercana_derecho = isset($_POST['prisma_cercana_derecho']) ? 1 : 0;
$detalle_refra_ojo_dere = $_POST['detalle_refra_ojo_dere'] ?? '';

$tipo_izquierdo = $_POST['tipo_izquierdo'] ?? '';
$av_lejana_izquierdo = $_POST['av_lejana_izquierdo'] ?? '';
$av_lejana_lentes_izquierdo = $_POST['av_lejana_lentes_izquierdo'] ?? '';
$esf_lejana_izquierdo = $_POST['esf_lejana_izquierdo'] ?? '';
$cil_lejana_izquierdo = $_POST['cil_lejana_izquierdo'] ?? '';
$eje_lejana_izquierdo = $_POST['eje_lejana_izquierdo'] ?? '';
$add_lejana_izquierdo = $_POST['add_lejana_izquierdo'] ?? '';
$prisma_lejana_izquierdo = isset($_POST['prisma_lejana_izquierdo']) ? 1 : 0;
$av_cercana_izquierdo = $_POST['av_cercana_izquierdo'] ?? '';
$esf_cercana_izquierdo = $_POST['esf_cercana_izquierdo'] ?? '';
$cil_cercana_izquierdo = $_POST['cil_cercana_izquierdo'] ?? '';
$eje_cercana_izquierdo = $_POST['eje_cercana_izquierdo'] ?? '';
$add_cercana_izquierdo = $_POST['add_cercana_izquierdo'] ?? '';
$prisma_cercana_izquierdo = isset($_POST['prisma_cercana_izquierdo']) ? 1 : 0;
$detalle_refra_ojo_izq = $_POST['detalle_refra_ojo_izq'] ?? '';

$stmt = $conexion->prepare("
    INSERT INTO refraccion_antigua (
        id_atencion, tipo_derecho, av_lejana_derecho, av_lejana_lentes_derecho, esf_lejana_derecho, cil_lejana_derecho, eje_lejana_derecho, add_lejana_derecho, prisma_lejana_derecho,
        av_cercana_derecho, esf_cercana_derecho, cil_cercana_derecho, eje_cercana_derecho, add_cercana_derecho, prisma_cercana_derecho, detalle_refra_ojo_dere,
        tipo_izquierdo, av_lejana_izquierdo, av_lejana_lentes_izquierdo, esf_lejana_izquierdo, cil_lejana_izquierdo, eje_lejana_izquierdo, add_lejana_izquierdo, prisma_lejana_izquierdo,
        av_cercana_izquierdo, esf_cercana_izquierdo, cil_cercana_izquierdo, eje_cercana_izquierdo, add_cercana_izquierdo, prisma_cercana_izquierdo, detalle_refra_ojo_izq
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?, ?
    )
");

$stmt->bind_param(
    "issssssissssssssssssssissssssss",
    $id_atencion, $tipo_derecho, $av_lejana_derecho, $av_lejana_lentes_derecho, $esf_lejana_derecho, $cil_lejana_derecho, $eje_lejana_derecho, $add_lejana_derecho, $prisma_lejana_derecho,
    $av_cercana_derecho, $esf_cercana_derecho, $cil_cercana_derecho, $eje_cercana_derecho, $add_cercana_derecho, $prisma_cercana_derecho, $detalle_refra_ojo_dere,
    $tipo_izquierdo, $av_lejana_izquierdo, $av_lejana_lentes_izquierdo, $esf_lejana_izquierdo, $cil_lejana_izquierdo, $eje_lejana_izquierdo, $add_lejana_izquierdo, $prisma_lejana_izquierdo,
    $av_cercana_izquierdo, $esf_cercana_izquierdo, $cil_cercana_izquierdo, $eje_cercana_izquierdo, $add_cercana_izquierdo, $prisma_cercana_izquierdo, $detalle_refra_ojo_izq
);

if ($stmt->execute()) {
    $_SESSION['message'] = "Refracción antigua guardada correctamente.";
    $_SESSION['message_type'] = "success";
} else {
    $_SESSION['message'] = "Error al guardar la refracción: " . $stmt->error;
    $_SESSION['message_type'] = "danger";
}
$stmt->close();
header("Location: refraccion_antiguas.php");
exit;
?>