<?php
include "../../conexionbd.php";
session_start();

$id_atencion = $_SESSION['pac'] ?? null;
$medico_tratante = $_POST['medico_tratante'];
$anestesiologo = $_POST['anestesiologo'];
$anestesia = $_POST['anestesia'];
$pa_pre = $_POST['pa_pre'];
$fr_pre = $_POST['fr_pre'];
$temp_pre = $_POST['temp_pre'];
$oxi_pre = $_POST['oxi_pre'];
$gluco_pre = $_POST['gluco_pre'];
$fc_pre = $_POST['fc_pre'];
$hora_pre = $_POST['hora_pre'];

$pa_dur = json_encode($_POST['pa_dur']);
$fr_dur = json_encode($_POST['fr_dur']);
$temp_dur = json_encode($_POST['temp_dur']);
$oxi_dur = json_encode($_POST['oxi_dur']);
$gluco_dur = json_encode($_POST['gluco_dur']);
$fc_dur = json_encode($_POST['fc_dur']);
$hora_dur = json_encode($_POST['hora_dur']);

$pa_post = $_POST['pa_post'];
$fr_post = $_POST['fr_post'];
$temp_post = $_POST['temp_post'];
$oxi_post = $_POST['oxi_post'];
$gluco_post = $_POST['gluco_post'];
$fc_post = $_POST['fc_post'];
$hora_post = $_POST['hora_post'];

$nota_enfermeria = $_POST['nota_enfermeria'];
$enfermera_responsable = $_POST['enfermera_responsable'];
$fecha_registro = date('Y-m-d H:i:s');

$sql = "INSERT INTO valvula_ahmed (
    id_atencion, medico_tratante, anestesiologo, anestesia,
    pa_pre, fr_pre, temp_pre, oxi_pre, gluco_pre, fc_pre, hora_pre,
    pa_dur, fr_dur, temp_dur, oxi_dur, gluco_dur, fc_dur, hora_dur,
    pa_post, fr_post, temp_post, oxi_post, gluco_post, fc_post, hora_post,
    nota_enfermeria, enfermera_responsable, fecha_registro
) VALUES (
    ?,?,?,?,?,?,?,?,?,?,?,
    ?,?,?,?,?,?,?,
    ?,?,?,?,?,?,?,
    ?,?,?
)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param(
    "isssssssssssssssssssssssssss", 
    $id_atencion, $medico_tratante, $anestesiologo, $anestesia,
    $pa_pre, $fr_pre, $temp_pre, $oxi_pre, $gluco_pre, $fc_pre, $hora_pre,
    $pa_dur, $fr_dur, $temp_dur, $oxi_dur, $gluco_dur, $fc_dur, $hora_dur,
    $pa_post, $fr_post, $temp_post, $oxi_post, $gluco_post, $fc_post, $hora_post,
    $nota_enfermeria, $enfermera_responsable, $fecha_registro
);

if (!$stmt->execute()) {
    die("Error SQL: " . $stmt->error);
}

$_SESSION['message'] = "Hoja de válvula de Ahmed guardada correctamente.";
$_SESSION['message_type'] = "success";

$stmt->close();
header("Location: reg_pro.php?tratamiento_exito=VÁLVULA DE AHMED");
exit;
?>
