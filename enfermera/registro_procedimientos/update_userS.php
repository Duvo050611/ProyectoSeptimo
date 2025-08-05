<?php 
session_start();
require_once '../../conexionbd.php';

// Security: Validate session
if (!isset($_SESSION['login']) || !is_array($_SESSION['login']) || !isset($_SESSION['login']['id_usua'])) {
    http_response_code(403);
    echo json_encode(['status' => 'false', 'message' => 'Acceso no autorizado']);
    exit;
}

if (!isset($_SESSION['pac']) || !is_numeric($_SESSION['pac'])) {
    http_response_code(400);
    echo json_encode(['status' => 'false', 'message' => 'ID de atención no válido']);
    exit;
}

// Validate CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    echo json_encode(['status' => 'false', 'message' => 'Token CSRF inválido']);
    exit;
}

$id_atencion = $_SESSION['pac'];
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo json_encode(['status' => 'false', 'message' => 'ID de registro inválido']);
    exit;
}

// Validate and sanitize input data
$hora = isset($_POST['hora']) ? trim($_POST['hora']) : '';
$sistg = isset($_POST['sistg']) ? trim($_POST['sistg']) : '';
$diastg = isset($_POST['diastg']) ? trim($_POST['diastg']) : '';
$fcardg = isset($_POST['fcardg']) ? trim($_POST['fcardg']) : '';
$frespg = isset($_POST['frespg']) ? trim($_POST['frespg']) : '';
$satg = isset($_POST['satg']) ? trim($_POST['satg']) : '';
$tempg = isset($_POST['tempg']) ? trim($_POST['tempg']) : '';
$fecha_g = isset($_POST['fecha_g']) ? trim($_POST['fecha_g']) : '';

// Log datos recibidos para debugging
error_log("=== DATOS RECIBIDOS EN update_userS.php ===");
error_log("POST datos: " . print_r($_POST, true));
error_log("id: " . $id);
error_log("id_atencion: " . $id_atencion);

// Validate required fields
if (empty($hora) || empty($sistg) || empty($diastg) || empty($fcardg) || 
    empty($frespg) || empty($satg) || empty($tempg)) {
    echo json_encode(['status' => 'false', 'message' => 'Todos los campos requeridos deben completarse']);
    exit;
}

// Validate numeric fields
if (!is_numeric($sistg) || !is_numeric($diastg) || !is_numeric($fcardg) || 
    !is_numeric($frespg) || !is_numeric($satg) || !is_numeric($tempg)) {
    echo json_encode(['status' => 'false', 'message' => 'Los valores de signos vitales deben ser numéricos']);
    exit;
}

// Comprehensive validation ranges
$sistg_num = (int)$sistg;
$diastg_num = (int)$diastg;
$fcardg_num = (int)$fcardg;
$frespg_num = (int)$frespg;
$satg_num = (int)$satg;
$tempg_num = (float)$tempg;

// Validate ranges
if ($sistg_num < 50 || $sistg_num > 250) {
    echo json_encode(['status' => 'false', 'message' => 'La presión sistólica debe estar entre 50 y 250 mmHg']);
    exit;
}

if ($diastg_num < 30 || $diastg_num > 150) {
    echo json_encode(['status' => 'false', 'message' => 'La presión diastólica debe estar entre 30 y 150 mmHg']);
    exit;
}

if ($fcardg_num < 30 || $fcardg_num > 250) {
    echo json_encode(['status' => 'false', 'message' => 'La frecuencia cardíaca debe estar entre 30 y 250 lpm']);
    exit;
}

if ($frespg_num < 8 || $frespg_num > 50) {
    echo json_encode(['status' => 'false', 'message' => 'La frecuencia respiratoria debe estar entre 8 y 50 rpm']);
    exit;
}

if ($satg_num < 50 || $satg_num > 100) {
    echo json_encode(['status' => 'false', 'message' => 'La saturación de oxígeno debe estar entre 50% y 100%']);
    exit;
}

if ($tempg_num < 34 || $tempg_num > 44) {
    echo json_encode(['status' => 'false', 'message' => 'La temperatura debe estar entre 34°C y 44°C']);
    exit;
}

// Validate time format
if (!DateTime::createFromFormat('H:i', $hora)) {
    echo json_encode(['status' => 'false', 'message' => 'Formato de hora inválido']);
    exit;
}

// Check if record exists and belongs to current patient
$check_sql = "SELECT id_trans_graf FROM dat_trans_grafico WHERE id_trans_graf = ? AND id_atencion = ?";
$check_stmt = $conexion->prepare($check_sql);
$check_stmt->bind_param("ii", $id, $id_atencion);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows === 0) {
    echo json_encode(['status' => 'false', 'message' => 'Registro no encontrado o no autorizado']);
    $check_stmt->close();
    exit;
}
$check_stmt->close();

// Update data using prepared statement - tabla actualizada sin campo cuenta
$sql = "UPDATE dat_trans_grafico 
        SET hora = ?, sistg = ?, diastg = ?, fcardg = ?, frespg = ?, satg = ?, tempg = ?
        WHERE id_trans_graf = ? AND id_atencion = ?";

$stmt = $conexion->prepare($sql);
if (!$stmt) {
    echo json_encode(['status' => 'false', 'message' => 'Error en la preparación de la consulta']);
    exit;
}

$stmt->bind_param("sssssssii", $hora, $sistg, $diastg, $fcardg, $frespg, $satg, $tempg, $id, $id_atencion);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'true', 'message' => 'Registro actualizado correctamente']);
    } else {
        echo json_encode(['status' => 'false', 'message' => 'No se realizaron cambios']);
    }
} else {
    // Log error for debugging
    error_log("Error SQL en update_userS.php: " . $stmt->error);
    error_log("Query ejecutada: UPDATE dat_trans_grafico SET hora='$hora', sistg='$sistg', diastg='$diastg', fcardg='$fcardg', frespg='$frespg', satg='$satg', tempg='$tempg' WHERE id_trans_graf=$id AND id_atencion=$id_atencion");
    echo json_encode(['status' => 'false', 'message' => 'Error al actualizar el registro: ' . $stmt->error]);
}

$stmt->close();
$conexion->close();
?>
