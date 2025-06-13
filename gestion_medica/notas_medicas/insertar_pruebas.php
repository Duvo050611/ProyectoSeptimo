<?php
session_start();
include "../../conexionbd.php";

if (!isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}
$id_atencion = $_SESSION['hospital'] ?? null;

$tipo_prueba = $_POST['tipo_prueba_oftalmologica'] ?? '';
$resultado = $_POST['resultado'] ?? '';
$fecha_consulta = $_POST['fecha_consulta'] ?? '';
$observaciones = $_POST['observaciones'] ?? '';
$estrabismo_od = $_POST['estrabismo_od'] ?? '';
$movimientos_od = $_POST['mov_oculares_od'] ?? '';
$convergencia_od = $_POST['convergencia_od'] ?? '';
$prueba_cover_od = $_POST['prueba_cover_od'] ?? '';
$vision_estereo_od = $_POST['vision_estereo_od'] ?? '';
$worth_od = $_POST['worth_od'] ?? '';
$schirmer_od = $_POST['schirmer_od'] ?? '';
$trpl_od = $_POST['trpl_od'] ?? '';
$fluoresceina_od = $_POST['fluoresceina_od'] ?? '';
$contraste_od = $_POST['contraste_od'] ?? '';
$ishihara_od = $_POST['ishihara_od'] ?? '';
$farnsworth_od = $_POST['farnsworth_od'] ?? '';
$amsler_od = $_POST['amsler_od'] ?? '';
$estrabismo_oi = $_POST['estrabismo_oi'] ?? '';
$movimientos_oi = $_POST['mov_oculares_oi'] ?? '';
$convergencia_oi = $_POST['convergencia_oi'] ?? '';
$prueba_cover_oi = $_POST['prueba_cover_oi'] ?? '';
$vision_estereo_oi = $_POST['vision_estereo_oi'] ?? '';
$worth_oi = $_POST['worth_oi'] ?? '';
$schirmer_oi = $_POST['schirmer_oi'] ?? '';
$trpl_oi = $_POST['trpl_oi'] ?? '';
$fluoresceina_oi = $_POST['fluoresceina_oi'] ?? '';
$contraste_oi = $_POST['contraste_oi'] ?? '';
$ishihara_oi = $_POST['ishihara_oi'] ?? '';
$farnsworth_oi = $_POST['farnsworth_oi'] ?? '';
$amsler_oi = $_POST['amsler_oi'] ?? '';
$ojo_preferente = $_POST['ojo_preferente'] ?? '';
$detalle_prueba = $_POST['detalle_prueba'] ?? '';

$stmt = $conexion->prepare("
    INSERT INTO pruebas_oftalmologicas (
        id_atencion, tipo_prueba, resultado, fecha_consulta, observaciones,
        estrabismo_od, movimientos_od, convergencia_od, prueba_cover_od, vision_estereo_od, worth_od, schirmer_od, trpl_od, fluoresceina_od, contraste_od, ishihara_od, farnsworth_od, amsler_od,
        estrabismo_oi, movimientos_oi, convergencia_oi, prueba_cover_oi, vision_estereo_oi, worth_oi, schirmer_oi, trpl_oi, fluoresceina_oi, contraste_oi, ishihara_oi, farnsworth_oi, amsler_oi,
        ojo_preferente, detalle_prueba
    ) VALUES (
        ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
        ?, ?
    )
");

$stmt->bind_param(
    "issssssssssssssssssssssssssssssss",
    $id_atencion, $tipo_prueba, $resultado, $fecha_consulta, $observaciones,
    $estrabismo_od, $movimientos_od, $convergencia_od, $prueba_cover_od, $vision_estereo_od, $worth_od, $schirmer_od, $trpl_od, $fluoresceina_od, $contraste_od, $ishihara_od, $farnsworth_od, $amsler_od,
    $estrabismo_oi, $movimientos_oi, $convergencia_oi, $prueba_cover_oi, $vision_estereo_oi, $worth_oi, $schirmer_oi, $trpl_oi, $fluoresceina_oi, $contraste_oi, $ishihara_oi, $farnsworth_oi, $amsler_oi,
    $ojo_preferente, $detalle_prueba
);

if (!$stmt->execute()) {
    die("Error SQL: " . $stmt->error);
}

$_SESSION['message'] = "Prueba oftalmológica guardada correctamente.";
$_SESSION['message_type'] = "success";

$stmt->close();
header("Location: pruebas.php");
exit;
?>