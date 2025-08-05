<?php 
session_start();
require_once '../../conexionbd.php';

// Security: Validate session
if (!isset($_SESSION['login']) || !is_array($_SESSION['login']) || !isset($_SESSION['login']['id_usua'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Acceso no autorizado']);
    exit;
}

if (!isset($_SESSION['pac']) || !is_numeric($_SESSION['pac'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ID de atención no válido']);
    exit;
}

$id_atencion = $_SESSION['pac'];
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'ID de registro inválido']);
    exit;
}

// Check if record exists and belongs to current patient - usando tabla correcta
$check_sql = "SELECT id_trans_graf FROM dat_trans_grafico WHERE id_trans_graf = ? AND id_atencion = ?";
$check_stmt = $conexion->prepare($check_sql);
if (!$check_stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Error en la preparación de la consulta']);
    exit;
}

$check_stmt->bind_param("ii", $id, $id_atencion);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Registro no encontrado o no autorizado']);
    $check_stmt->close();
    exit;
}
$check_stmt->close();

// Delete record using prepared statement - usando tabla correcta
$sql = "DELETE FROM dat_trans_grafico WHERE id_trans_graf = ? AND id_atencion = ?";
$stmt = $conexion->prepare($sql);
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Error en la preparación de la consulta']);
    exit;
}

$stmt->bind_param("ii", $id, $id_atencion);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Registro eliminado correctamente']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar el registro']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el registro: ' . $stmt->error]);
}

$stmt->close();
$conexion->close();
?>
