<?php
session_start();
include "../../conexionbd.php";

if (!isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

$id_atencion = $_SESSION['hospital'] ?? null;

$usuario_lentes_suaves = $_POST['usuario_lentes_suaves'] ?? '';
$usa_lentes_suaves_der = $_POST['usa_lentes_suaves_der'] ?? '';
$usa_lentes_suaves_izq = $_POST['usa_lentes_suaves_izq'] ?? '';
$av_suaves_der_esf = $_POST['av_suaves_der_esf'] ?? '';
$av_suaves_der_cil = $_POST['av_suaves_der_cil'] ?? '';
$av_suaves_der_cb = $_POST['av_suaves_der_cb'] ?? '';
$av_suaves_der_diam = $_POST['av_suaves_der_diam'] ?? '';
$av_suaves_izq_esf = $_POST['av_suaves_izq_esf'] ?? '';
$av_suaves_izq_cil = $_POST['av_suaves_izq_cil'] ?? '';
$av_suaves_izq_cb = $_POST['av_suaves_izq_cb'] ?? '';
$av_suaves_izq_diam = $_POST['av_suaves_izq_diam'] ?? '';
$tipo_suaves_der = $_POST['tipo_suaves_der'] ?? '';
$tipo_suaves_izq = $_POST['tipo_suaves_izq'] ?? '';
$usa_lentes_duros_der = $_POST['usa_lentes_duros_der'] ?? '';
$usa_lentes_duros_izq = $_POST['usa_lentes_duros_izq'] ?? '';
$av_duros_der_esf = $_POST['av_duros_der_esf'] ?? '';
$av_duros_der_cil = $_POST['av_duros_der_cil'] ?? '';
$av_duros_der_cb = $_POST['av_duros_der_cb'] ?? '';
$av_duros_der_diam = $_POST['av_duros_der_diam'] ?? '';
$av_duros_izq_esf = $_POST['av_duros_izq_esf'] ?? '';
$av_duros_izq_cil = $_POST['av_duros_izq_cil'] ?? '';
$av_duros_izq_cb = $_POST['av_duros_izq_cb'] ?? '';
$av_duros_izq_diam = $_POST['av_duros_izq_diam'] ?? '';
$av_con_hibrido_der = $_POST['av_con_hibrido_der'] ?? '';
$av_con_hibrido_izq = $_POST['av_con_hibrido_izq'] ?? '';
$receta_duros_der_tangente = $_POST['receta_duros_der_tangente'] ?? '';
$receta_duros_der_altura = $_POST['receta_duros_der_altura'] ?? '';
$receta_duros_der_el = $_POST['receta_duros_der_el'] ?? '';
$receta_duros_der_or = $_POST['receta_duros_der_or'] ?? '';
$receta_duros_izq_tangente = $_POST['receta_duros_izq_tangente'] ?? '';
$receta_duros_izq_altura = $_POST['receta_duros_izq_altura'] ?? '';
$receta_duros_izq_el = $_POST['receta_duros_izq_el'] ?? '';
$receta_duros_izq_or = $_POST['receta_duros_izq_or'] ?? '';
$tipo_lente = $_POST['tipo_lente'] ?? '';
$opciones_od = $_POST['opciones_od'] ?? '';
$opciones_oi = $_POST['opciones_oi'] ?? '';
$marca_od = $_POST['marca_od'] ?? '';
$marca_oi = $_POST['marca_oi'] ?? '';
$dk_od = $_POST['dk_od'] ?? '';
$av_od = $_POST['av_od'] ?? '';
$dk_oi = $_POST['dk_oi'] ?? '';
$av_oi = $_POST['av_oi'] ?? '';
$detalle_contacto = $_POST['detalle_contacto'] ?? '';

$parametros = [
    $id_atencion,
    $usuario_lentes_suaves, $usa_lentes_suaves_der, $usa_lentes_suaves_izq,
    $av_suaves_der_esf, $av_suaves_der_cil, $av_suaves_der_cb, $av_suaves_der_diam,
    $av_suaves_izq_esf, $av_suaves_izq_cil, $av_suaves_izq_cb, $av_suaves_izq_diam,
    $tipo_suaves_der, $tipo_suaves_izq,
    $usa_lentes_duros_der, $usa_lentes_duros_izq,
    $av_duros_der_esf, $av_duros_der_cil, $av_duros_der_cb, $av_duros_der_diam,
    $av_duros_izq_esf, $av_duros_izq_cil, $av_duros_izq_cb, $av_duros_izq_diam,
    $av_con_hibrido_der, $av_con_hibrido_izq,
    $receta_duros_der_tangente, $receta_duros_der_altura, $receta_duros_der_el, $receta_duros_der_or,
    $receta_duros_izq_tangente, $receta_duros_izq_altura, $receta_duros_izq_el, $receta_duros_izq_or,
    $tipo_lente, $opciones_od, $opciones_oi, $marca_od, $marca_oi,
    $dk_od, $av_od, $dk_oi, $av_oi,
    $detalle_contacto
];

$tipos = "i" . str_repeat("s", count($parametros) - 1);

$placeholders = rtrim(str_repeat("?,", count($parametros)), ",");

$sql = "
INSERT INTO receta_lentes_contacto (
    id_atencion,
    usuario_lentes_suaves, usa_lentes_suaves_der, usa_lentes_suaves_izq,
    av_suaves_der_esf, av_suaves_der_cil, av_suaves_der_cb, av_suaves_der_diam,
    av_suaves_izq_esf, av_suaves_izq_cil, av_suaves_izq_cb, av_suaves_izq_diam,
    tipo_suaves_der, tipo_suaves_izq,
    usa_lentes_duros_der, usa_lentes_duros_izq,
    av_duros_der_esf, av_duros_der_cil, av_duros_der_cb, av_duros_der_diam,
    av_duros_izq_esf, av_duros_izq_cil, av_duros_izq_cb, av_duros_izq_diam,
    av_con_hibrido_der, av_con_hibrido_izq,
    receta_duros_der_tangente, receta_duros_der_altura, receta_duros_der_el, receta_duros_der_or,
    receta_duros_izq_tangente, receta_duros_izq_altura, receta_duros_izq_el, receta_duros_izq_or,
    tipo_lente, opciones_od, opciones_oi, marca_od, marca_oi,
    dk_od, av_od, dk_oi, av_oi,
    detalle_contacto
) VALUES ($placeholders)
";

$stmt = $conexion->prepare($sql);

if (!$stmt) {
    $_SESSION['message'] = "Error en la preparación de la consulta: " . $conexion->error;
    $_SESSION['message_type'] = "danger";
    header("Location: receta_lentes_c.php");
    exit;
}

$stmt->bind_param($tipos, ...$parametros);

if (!$stmt->execute()) {
    $_SESSION['message'] = "Error SQL: " . $stmt->error;
    $_SESSION['message_type'] = "danger";
    $stmt->close();
    header("Location: receta_lentes_c.php");
    exit;
}


$_SESSION['message'] = "Receta de lentes de contacto guardada correctamente.";
$_SESSION['message_type'] = "success";

$stmt->close();
$conexion->close();

header("Location: receta_lentes_c.php");
exit;
?>
