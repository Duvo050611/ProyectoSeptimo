<?php
session_start();
include "../../conexionbd.php";

if (!isset($_SESSION['login']) || !in_array($_SESSION['login']['id_rol'], [4, 5, 10])) {
    header("Location: ../../index.php");
    exit();
}

$not_id = isset($_GET['not_id']) ? (int)$_GET['not_id'] : 0;
$id_atencion = isset($_GET['id_atencion']) ? (int)$_GET['id_atencion'] : 0;

if ($not_id <= 0 || $id_atencion <= 0) {
    die("ID de notificación o atención no válido.");
}

// Fetch PDF filename from notificaciones_gabinete
$sql = "SELECT pdf_solicitud FROM notificaciones_gabinete WHERE id_not_gabinete = ? AND id_atencion = ?";
$stmt = $conexion->prepare($sql);
if ($stmt) {
    $stmt->bind_param("ii", $not_id, $id_atencion);
    $stmt->execute();
    $result = $stmt->get_result();
    $notificacion = $result->fetch_assoc();
    $stmt->close();

    if ($notificacion && !empty($notificacion['pdf_solicitud'])) {
        $pdf_file = $_SERVER['DOCUMENT_ROOT'] . '/gestion_medica/notas_medicas/solicitudes_gabinete/' . $notificacion['pdf_solicitud'];
        if (file_exists($pdf_file) && is_readable($pdf_file)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . basename($pdf_file) . '"');
            readfile($pdf_file);
            exit();
        } else {
            die("Archivo PDF no encontrado o no accesible.");
        }
    } else {
        die("Solicitud de estudio no encontrada o sin PDF asociado.");
    }
} else {
    die("Error al preparar la consulta: " . $conexion->error);
}

$conexion->close();
?>