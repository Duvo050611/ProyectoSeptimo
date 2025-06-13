<?php
session_start();
include "../../conexionbd.php";

if (!isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

$id_atencion = $_SESSION['hospital'] ?? null;

$av_binocular = $_POST['av_binocular'] ?? '';
$av_lejana_sin_correc = $_POST['av_lejana_sin_correc'] ?? '';
$av_estenopico = $_POST['av_estenopico'] ?? '';
$av_lejana_con_correc_prop = $_POST['av_lejana_con_correc_prop'] ?? '';
$av_lejana_mejor_corregida = $_POST['av_lejana_mejor_corregida'] ?? '';
$av_potencial = $_POST['av_potencial'] ?? '';
$oi_lejana_sin_correc = $_POST['oi_lejana_sin_correc'] ?? '';
$oi_estenopico = $_POST['oi_estenopico'] ?? '';
$oi_lejana_con_correc_prop = $_POST['oi_lejana_con_correc_prop'] ?? '';
$oi_lejana_mejor_corregida = $_POST['oi_lejana_mejor_corregida'] ?? '';
$oi_potencial = $_POST['oi_potencial'] ?? '';
$detalle_refra = $_POST['detalle_refra'] ?? '';

$esferas_sin_ciclo_od = $_POST['esferas_sin_ciclo_od'] ?? '';
$cilindros_sin_ciclo_od = $_POST['cilindros_sin_ciclo_od'] ?? '';
$eje_sin_ciclo_od = $_POST['eje_sin_ciclo_od'] ?? '';
$add_sin_ciclo_od = $_POST['add_sin_ciclo_od'] ?? '';
$dip_sin_ciclo_od = $_POST['dip_sin_ciclo_od'] ?? '';
$prisma_sin_ciclo_od = isset($_POST['prisma_sin_ciclo_od']) ? '1' : '0';

$esferas_sin_ciclo_oi = $_POST['esferas_sin_ciclo_oi'] ?? '';
$cilindros_sin_ciclo_oi = $_POST['cilindros_sin_ciclo_oi'] ?? '';
$eje_sin_ciclo_oi = $_POST['eje_sin_ciclo_oi'] ?? '';
$add_sin_ciclo_oi = $_POST['add_sin_ciclo_oi'] ?? '';
$dip_sin_ciclo_oi = $_POST['dip_sin_ciclo_oi'] ?? '';
$prisma_sin_ciclo_oi = isset($_POST['prisma_sin_ciclo_oi']) ? '1' : '0';
$detalle_ref_subjetiv_sin = $_POST['detalle_ref_subjetiv_sin'] ?? '';

$esferas_con_ciclo_od = $_POST['esferas_con_ciclo_od'] ?? '';
$cilindros_con_ciclo_od = $_POST['cilindros_con_ciclo_od'] ?? '';
$eje_con_ciclo_od = $_POST['eje_con_ciclo_od'] ?? '';
$add_con_ciclo_od = $_POST['add_con_ciclo_od'] ?? '';
$dip_con_ciclo_od = $_POST['dip_con_ciclo_od'] ?? '';
$prisma_con_ciclo_od = isset($_POST['prisma_con_ciclo_od']) ? '1' : '0';

$esferas_con_ciclo_oi = $_POST['esferas_con_ciclo_oi'] ?? '';
$cilindros_con_ciclo_oi = $_POST['cilindros_con_ciclo_oi'] ?? '';
$eje_con_ciclo_oi = $_POST['eje_con_ciclo_oi'] ?? '';
$add_con_ciclo_oi = $_POST['add_con_ciclo_oi'] ?? '';
$dip_con_ciclo_oi = $_POST['dip_con_ciclo_oi'] ?? '';
$prisma_con_ciclo_oi = isset($_POST['prisma_con_ciclo_oi']) ? '1' : '0';

$av_intermedia_od = $_POST['av_intermedia_od'] ?? '';
$av_cercana_sin_corr_od = $_POST['av_cercana_sin_corr_od'] ?? '';
$av_cercana_con_corr_od = $_POST['av_cercana_con_corr_od'] ?? '';

$av_intermedia_oi = $_POST['av_intermedia_oi'] ?? '';
$av_cercana_sin_corr_oi = $_POST['av_cercana_sin_corr_oi'] ?? '';
$av_cercana_con_corr_oi = $_POST['av_cercana_con_corr_oi'] ?? '';

$esf_cerca_od = $_POST['esf_cerca_od'] ?? '';
$cil_cerca_od = $_POST['cil_cerca_od'] ?? '';
$eje_cerca_od = $_POST['eje_cerca_od'] ?? '';
$prisma_cerca_od = isset($_POST['prisma_cerca_od']) ? '1' : '0';

$esf_cerca_oi = $_POST['esf_cerca_oi'] ?? '';
$cil_cerca_oi = $_POST['cil_cerca_oi'] ?? '';
$eje_cerca_oi = $_POST['eje_cerca_oi'] ?? '';
$dip_cerca_oi = $_POST['dip_cerca_oi'] ?? '';
$prisma_cerca_oi = isset($_POST['prisma_cerca_oi']) ? '1' : '0';

$detalle_ref_subjetiv = $_POST['detalle_ref_subjetiv'] ?? '';

$stmt = $conexion->prepare("
INSERT INTO refraccion_actual (
    id_atencion,
    av_binocular, av_lejana_sin_correc, av_estenopico, av_lejana_con_correc_prop, av_lejana_mejor_corregida, av_potencial,
    oi_lejana_sin_correc, oi_estenopico, oi_lejana_con_correc_prop, oi_lejana_mejor_corregida, oi_potencial,
    detalle_refra,
    esferas_sin_ciclo_od, cilindros_sin_ciclo_od, eje_sin_ciclo_od, add_sin_ciclo_od, dip_sin_ciclo_od, prisma_sin_ciclo_od,
    esferas_sin_ciclo_oi, cilindros_sin_ciclo_oi, eje_sin_ciclo_oi, add_sin_ciclo_oi, dip_sin_ciclo_oi, prisma_sin_ciclo_oi,
    detalle_ref_subjetiv_sin,
    esferas_con_ciclo_od, cilindros_con_ciclo_od, eje_con_ciclo_od, add_con_ciclo_od, dip_con_ciclo_od, prisma_con_ciclo_od,
    esferas_con_ciclo_oi, cilindros_con_ciclo_oi, eje_con_ciclo_oi, add_con_ciclo_oi, dip_con_ciclo_oi, prisma_con_ciclo_oi,
    av_intermedia_od, av_cercana_sin_corr_od, av_cercana_con_corr_od,
    av_intermedia_oi, av_cercana_sin_corr_oi, av_cercana_con_corr_oi,
    esf_cerca_od, cil_cerca_od, eje_cerca_od, prisma_cerca_od,
    esf_cerca_oi, cil_cerca_oi, eje_cerca_oi, dip_cerca_oi, prisma_cerca_oi,
    detalle_ref_subjetiv
) VALUES (
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
)");

if ($stmt === false) {
    die("Error en prepare: " . $conexion->error);
}

$types = 'i' . str_repeat('s', 53);

$stmt->bind_param($types,
    $id_atencion,
    $av_binocular, $av_lejana_sin_correc, $av_estenopico, $av_lejana_con_correc_prop, $av_lejana_mejor_corregida, $av_potencial,
    $oi_lejana_sin_correc, $oi_estenopico, $oi_lejana_con_correc_prop, $oi_lejana_mejor_corregida, $oi_potencial,
    $detalle_refra,
    $esferas_sin_ciclo_od, $cilindros_sin_ciclo_od, $eje_sin_ciclo_od, $add_sin_ciclo_od, $dip_sin_ciclo_od, $prisma_sin_ciclo_od,
    $esferas_sin_ciclo_oi, $cilindros_sin_ciclo_oi, $eje_sin_ciclo_oi, $add_sin_ciclo_oi, $dip_sin_ciclo_oi, $prisma_sin_ciclo_oi,
    $detalle_ref_subjetiv_sin,
    $esferas_con_ciclo_od, $cilindros_con_ciclo_od, $eje_con_ciclo_od, $add_con_ciclo_od, $dip_con_ciclo_od, $prisma_con_ciclo_od,
    $esferas_con_ciclo_oi, $cilindros_con_ciclo_oi, $eje_con_ciclo_oi, $add_con_ciclo_oi, $dip_con_ciclo_oi, $prisma_con_ciclo_oi,
    $av_intermedia_od, $av_cercana_sin_corr_od, $av_cercana_con_corr_od,
    $av_intermedia_oi, $av_cercana_sin_corr_oi, $av_cercana_con_corr_oi,
    $esf_cerca_od, $cil_cerca_od, $eje_cerca_od, $prisma_cerca_od,
    $esf_cerca_oi, $cil_cerca_oi, $eje_cerca_oi, $dip_cerca_oi, $prisma_cerca_oi,
    $detalle_ref_subjetiv
);

if ($stmt->execute()) {
    $_SESSION['message'] = "Refracción actual guardada correctamente.";
    $_SESSION['message_type'] = "success";
} else {
    $_SESSION['message'] = "Error al guardar la refracción: " . $stmt->error;
    $_SESSION['message_type'] = "danger";
}

$stmt->close();
header("Location: refraccion_actual.php");
exit;
?>
