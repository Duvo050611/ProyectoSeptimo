<?php
// Debug activado (desactiva en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json; charset=utf-8');

include('../../conexionbd.php');
if ($conexion->connect_error) {
    echo json_encode(['status' => 'false', 'message' => 'Conexión fallida: ' . $conexion->connect_error]);
    exit();
}

// CSRF
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['status' => 'false', 'message' => 'Error de validación CSRF']);
    exit();
}

/**
 * Obtiene el id de usuario desde la sesión intentando varias claves comunes.
 * Ajusta/añade claves si en tu login usas otro nombre.
 */
function obtenerIdUsuarioDeSesion(): int {
    $posiblesClaves = [
        'id_usuario', 'id_usua', 'user_id', 'usuario_id', 'id', 'uid'
    ];
    foreach ($posiblesClaves as $k) {
        if (isset($_SESSION[$k]) && intval($_SESSION[$k]) > 0) {
            return intval($_SESSION[$k]);
        }
    }
    return 0;
}

// Datos del POST (sin id_usua)
$id_exp = isset($_POST['id_exp']) ? intval($_POST['id_exp']) : 0;
$id_atencion = isset($_POST['id_atencion']) ? intval($_POST['id_atencion']) : 0;
$nota_recuperacion = isset($_POST['nota_recuperacion']) ? trim($_POST['nota_recuperacion']) : '';
$enfermera_responsable = isset($_POST['enfermera_responsable']) ? trim($_POST['enfermera_responsable']) : '';

// ID de usuario exclusivamente desde sesión
$id_usua = obtenerIdUsuarioDeSesion();

// Validaciones
$errors = [];
if ($id_exp <= 0) $errors[] = 'ID de expediente inválido';
if ($id_usua <= 0) $errors[] = 'ID de usuario inválido';
if ($id_atencion <= 0) $errors[] = 'ID de atención inválido';
if ($nota_recuperacion === '') $errors[] = 'Nota de recuperación vacía';
if ($enfermera_responsable === '') $errors[] = 'Enfermera responsable no especificada';

if (!empty($errors)) {
    // Información de depuración mínima (no exponer valores sensibles)
    $debug = [
        'session_keys_present' => array_keys($_SESSION),
        'resolved_user_id' => $id_usua
    ];
    echo json_encode(['status' => 'false', 'message' => 'Errores: ' . implode(', ', $errors), 'debug' => $debug]);
    exit();
}

// Insert
$stmt = $conexion->prepare("INSERT INTO notas_recuperacion (id_exp, id_usua, id_atencion, nota_recuperacion, enfermera_responsable) VALUES (?, ?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(['status' => 'false', 'message' => 'Error en la preparación: ' . $conexion->error]);
    exit();
}

$stmt->bind_param("iiiss", $id_exp, $id_usua, $id_atencion, $nota_recuperacion, $enfermera_responsable);

if ($stmt->execute()) {
    echo json_encode(['status' => 'true', 'message' => '✅ Nota guardada correctamente']);
} else {
    echo json_encode(['status' => 'false', 'message' => 'Error al guardar: ' . $stmt->error]);
}

$stmt->close();
$conexion->close();
