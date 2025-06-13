<?php
session_start();
include "../../conexionbd.php";

if (!isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

$id_atencion = $_SESSION['hospital'] ?? null;

$esf_lejana_od = $_POST['esf_lejana_od'] ?? '';
$cil_lejana_od = $_POST['cil_lejana_od'] ?? '';
$eje_lejana_od = $_POST['eje_lejana_od'] ?? '';
$add_lejana_od = $_POST['add_lejana_od'] ?? '';
$dip_lejana_od = $_POST['dip_lejana_od'] ?? '';
$prisma_lejana_od = isset($_POST['prisma_lejana_od']) ? 1 : 0;

$esf_lejana_oi = $_POST['esf_lejana_oi'] ?? '';
$cil_lejana_oi = $_POST['cil_lejana_oi'] ?? '';
$eje_lejana_oi = $_POST['eje_lejana_oi'] ?? '';
$add_lejana_oi = $_POST['add_lejana_oi'] ?? '';
$dip_lejana_oi = $_POST['dip_lejana_oi'] ?? '';
$prisma_lejana_oi = isset($_POST['prisma_lejana_oi']) ? 1 : 0;

$esf_cercana_od = $_POST['esf_cercana_od'] ?? '';
$cil_cercana_od = $_POST['cil_cercana_od'] ?? '';
$eje_cercana_od = $_POST['eje_cercana_od'] ?? '';
$dip_cercana_od = $_POST['dip_cercana_od'] ?? '';
$prisma_cercana_od = isset($_POST['prisma_cercana_od']) ? 1 : 0;

$esf_cercana_oi = $_POST['esf_cercana_oi'] ?? '';
$cil_cercana_oi = $_POST['cil_cercana_oi'] ?? '';
$eje_cercana_oi = $_POST['eje_cercana_oi'] ?? '';
$dip_cercana_oi = $_POST['dip_cercana_oi'] ?? '';
$prisma_cercana_oi = isset($_POST['prisma_cercana_oi']) ? 1 : 0;

$esf_intermedia_od = $_POST['esf_intermedia_od'] ?? '';
$cil_intermedia_od = $_POST['cil_intermedia_od'] ?? '';
$eje_intermedia_od = $_POST['eje_intermedia_od'] ?? '';
$dip_intermedia_od = $_POST['dip_intermedia_od'] ?? '';

$esf_intermedia_oi = $_POST['esf_intermedia_oi'] ?? '';
$cil_intermedia_oi = $_POST['cil_intermedia_oi'] ?? '';
$eje_intermedia_oi = $_POST['eje_intermedia_oi'] ?? '';
$dip_intermedia_oi = $_POST['dip_intermedia_oi'] ?? '';

$tipo_lente_od = $_POST['tipo_lente_od'] ?? '';
$tipo_lente_oi = $_POST['tipo_lente_oi'] ?? '';
$observaciones = $_POST['observaciones'] ?? '';

$stmt = $conexion->prepare("
    INSERT INTO recetas_anteojos (
        id_atencion, 
        esf_lejana_od, cil_lejana_od, eje_lejana_od, add_lejana_od, dip_lejana_od, prisma_lejana_od,
        esf_lejana_oi, cil_lejana_oi, eje_lejana_oi, add_lejana_oi, dip_lejana_oi, prisma_lejana_oi,
        esf_cercana_od, cil_cercana_od, eje_cercana_od, dip_cercana_od, prisma_cercana_od,
        esf_cercana_oi, cil_cercana_oi, eje_cercana_oi, dip_cercana_oi, prisma_cercana_oi,
        esf_intermedia_od, cil_intermedia_od, eje_intermedia_od, dip_intermedia_od,
        esf_intermedia_oi, cil_intermedia_oi, eje_intermedia_oi, dip_intermedia_oi,
        tipo_lente_od, tipo_lente_oi, observaciones, fecha
    ) VALUES (
        ?,
        ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?,
        ?, ?, ?, ?,
        ?, ?, ?, ?,
        ?, ?, ?, NOW()
    )
");

$stmt->bind_param(
    "isssssssssssssissssissssisssssssss", 
    $id_atencion, 
    $esf_lejana_od, $cil_lejana_od, $eje_lejana_od, $add_lejana_od, $dip_lejana_od, $prisma_lejana_od,
    $esf_lejana_oi, $cil_lejana_oi, $eje_lejana_oi, $add_lejana_oi, $dip_lejana_oi, $prisma_lejana_oi,
    $esf_cercana_od, $cil_cercana_od, $eje_cercana_od, $dip_cercana_od, $prisma_cercana_od,
    $esf_cercana_oi, $cil_cercana_oi, $eje_cercana_oi, $dip_cercana_oi, $prisma_cercana_oi,
    $esf_intermedia_od, $cil_intermedia_od, $eje_intermedia_od, $dip_intermedia_od,
    $esf_intermedia_oi, $cil_intermedia_oi, $eje_intermedia_oi, $dip_intermedia_oi,
    $tipo_lente_od, $tipo_lente_oi, $observaciones
);

if ($stmt->execute()) {
    $_SESSION['message'] = "Receta de anteojos guardada correctamente.";
    $_SESSION['message_type'] = "success";
} else {
    $_SESSION['message'] = "Error al guardar la receta: " . $stmt->error;
    $_SESSION['message_type'] = "danger";
}
$stmt->close();
header("Location: receta_lentes.php");
exit;
?>