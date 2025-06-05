<?php
// Start session and ensure no output before this
ob_start();
session_start();
include "../../conexionbd.php";

// Validate session and role
if (!isset($_SESSION['login']) || !in_array($_SESSION['login']['id_rol'], [5])) {
    ob_end_clean();
    header("Location: ../../index.php");
    exit();
}

$usuario = $_SESSION['login'];
$id_not_gabinete = isset($_GET['id_not_gabinete']) && is_numeric($_GET['id_not_gabinete']) ? (int)$_GET['id_not_gabinete'] : 0;

// Validate id_not_gabinete
if ($id_not_gabinete <= 0) {
    $_SESSION['message'] = "ID de notificación inválido.";
    $_SESSION['message_type'] = "danger";
    ob_end_clean();
    header("Location: resultados_gab.php");
    exit();
}

// Fetch existing files to delete them
$upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/gestion_medica/notas_medicas/resultados_gabinete/';
$query = "SELECT resultado FROM notificaciones_gabinete WHERE id_not_gabinete = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id_not_gabinete);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if ($row && !empty($row['resultado'])) {
    $file_names = array_map('trim', explode(',', $row['resultado']));
    foreach ($file_names as $file_name) {
        $file_path = $upload_dir . basename($file_name);
        if (file_exists($file_path)) {
            if (!unlink($file_path)) {
               error_log("Failed to delete file: $file_path");
            }
        }
    }
}

// Delete record
$query = "DELETE FROM notificaciones_gabinete WHERE id_not_gabinete = ?";
$stmt = $conexion->prepare($query);
if (!$stmt) {
    $_SESSION['message'] = "Error al preparar la consulta: " . $conexion->error;
    $_SESSION['message_type'] = "danger";
    error_log("SQL Prepare Error: " . $conexion->error);
    ob_end_clean();
    header("Location: resultados_gab.php");
    exit();
}

$stmt->bind_param("i", $id_not_gabinete);
if ($stmt->execute()) {
    $_SESSION['message'] = "Estudio de gabinete eliminado correctamente.";
    $_SESSION['message_type'] = "success";
} else {
    $_SESSION['message'] = "Error al eliminar el estudio: " . $stmt->error;
    $_SESSION['message_type'] = "danger";
    error_log("SQL Execute Error: " . $stmt->error);
}
$stmt->close();

ob_end_clean();
header("Location: resultados_gab.php");
exit();
?>