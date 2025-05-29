<?php
include '../../conexionbd.php';
mysqli_set_charset($conexion, "utf8");

$id_atencion = isset($_GET['id_atencion']) ? (int)$_GET['id_atencion'] : 0;
// Check for both not_id and ¬_id to handle typo
$not_id = isset($_GET['not_id']) ? (int)$_GET['not_id'] : (isset($_GET['¬_id']) ? (int)$_GET['¬_id'] : 0);

if ($not_id === 0 || $id_atencion === 0) {
    error_log("Invalid parameters: not_id=$not_id, id_atencion=$id_atencion");
    header("Location: sol_laboratorio.php?error=" . urlencode("Parámetros inválidos."));
    exit();
}

// Fetch stored PDF filename
$sql = "SELECT pdf_solicitud FROM notificaciones_labo WHERE not_id = ? AND id_atencion = ?";
$stmt = $conexion->prepare($sql);
if (!$stmt) {
    error_log("Prepare failed: " . $conexion->error);
    header("Location: sol_laboratorio.php?error=" . urlencode("Error en la consulta."));
    exit();
}
$stmt->bind_param("ii", $not_id, $id_atencion);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if ($row && !empty($row['pdf_solicitud'])) {
    $pdf_filename = $row['pdf_solicitud'];
    $pdf_path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'gestion_medica' . DIRECTORY_SEPARATOR . 'notas_medicas' . DIRECTORY_SEPARATOR . 'solicitudes' . DIRECTORY_SEPARATOR . $pdf_filename;
    
    if (file_exists($pdf_path)) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($pdf_path) . '"');
        header('Content-Length: ' . filesize($pdf_path));
        readfile($pdf_path);
        exit();
    } else {
        error_log("PDF file not found: $pdf_path (filename: $pdf_filename), document_root: {$_SERVER['DOCUMENT_ROOT']}");
        header("Location: sol_laboratorio.php?error=" . urlencode("PDF no encontrado o inaccesible."));
        exit();
    }
} else {
    error_log("No PDF filename found for not_id=$not_id, id_atencion=$id_atencion");
    header("Location: sol_laboratorio.php?error=" . urlencode("PDF no encontrado en la base de datos."));
    exit();
}

$conexion->close();
?>