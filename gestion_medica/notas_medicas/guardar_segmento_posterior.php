<?php
session_start();
include "../../conexionbd.php";

$id_exp = isset($_POST['id_exp']) && $_POST['id_exp'] !== '' ? intval($_POST['id_exp']) : null;
$id_usua = isset($_POST['id_usua']) && $_POST['id_usua'] !== '' ? intval($_POST['id_usua']) : null;
$id_atencion = isset($_POST['id_atencion']) && $_POST['id_atencion'] !== '' ? intval($_POST['id_atencion']) : null;

// Datos binarios
$no_valorable_od = isset($_POST['no_valorable_od']) ? 1 : 0;
$no_valorable_oi = isset($_POST['no_valorable_oi']) ? 1 : 0;

// Campos de texto (pueden ser NULL si vacíos)
$vitreo_od = $_POST['vitreo_od'] ?? null;
$vitreo_oi = $_POST['vitreo_oi'] ?? null;
$nervio_optico_od = $_POST['nervio_optico_od'] ?? null;
$nervio_optico_oi = $_POST['nervio_optico_oi'] ?? null;
$retina_periferica_od = $_POST['retina_periferica_od'] ?? null;
$retina_periferica_oi = $_POST['retina_periferica_oi'] ?? null;
$macula_od = $_POST['macula_od'] ?? null;
$macula_oi = $_POST['macula_oi'] ?? null;
$observaciones_dibujo = $_POST['observaciones_dibujo'] ?? null;

// Campos opcionales adicionales (si deseas agregarlos en el futuro)
$bajo_dilatacion = $_POST['bajo_dilatacion'] ?? 'si'; // Valor por defecto
$bajo_do = isset($_POST['bajo_do']) ? 1 : 0;
$bajo_dx = isset($_POST['bajo_dx']) ? 1 : 0;
$bajo_total = isset($_POST['bajo_total']) ? 1 : 0;
$bajo_media = isset($_POST['bajo_media']) ? 1 : 0;
$bajo_no_dilata = isset($_POST['bajo_no_dilata']) ? 1 : 0;

if ($conexion) {
    $sql = "INSERT INTO segmento_post (
                id_exp, id_atencion, id_usua, bajo_dilatacion, bajo_do, bajo_dx, bajo_total, bajo_media, bajo_no_dilata,
                no_valorable_od, no_valorable_oi, vitreo_od, vitreo_oi, nervio_optico_od, nervio_optico_oi,
                retina_periferica_od, retina_periferica_oi, macula_od, macula_oi, observaciones_dibujo
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);
    $stmt = $conexion->prepare("
    INSERT INTO segmento_post (
        id_exp, id_atencion, id_usua,
        bajo_dilatacion, bajo_do, bajo_dx, bajo_total, bajo_media, bajo_no_dilata,
        no_valorable_od, no_valorable_oi,
        vitreo_od, vitreo_oi,
        nervio_optico_od, nervio_optico_oi,
        retina_periferica_od, retina_periferica_oi,
        macula_od, macula_oi,
        observaciones_dibujo
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "iiisiiiiiissssssssss",
    $id_exp,
    $id_atencion,
    $id_usua,
    $bajo_dilatacion, // string ENUM 'si' o 'no'
    $bajo_do,
    $bajo_dx,
    $bajo_total,
    $bajo_media,
    $bajo_no_dilata,
    $no_valorable_od,
    $no_valorable_oi,
    $vitreo_od,
    $vitreo_oi,
    $nervio_optico_od,
    $nervio_optico_oi,
    $retina_periferica_od,
    $retina_periferica_oi,
    $macula_od,
    $macula_oi,
    $observaciones_dibujo
);



    if ($stmt->execute()) {
        echo "<script>alert('✅ Segmento posterior guardado correctamente.'); window.location.href='formulario_segmento_posterior.php';</script>";
    } else {
        echo "❌ Error al guardar: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
} else {
    echo "❌ Error de conexión a la base de datos.";
}
?>
