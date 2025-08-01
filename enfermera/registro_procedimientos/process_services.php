<?php
ob_start();
session_start();
include '../../conexionbd.php';

// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 0); // Mantener en 0 para producción, pero para depurar puedes ponerlo en 1 TEMPORALMENTE
ini_set('display_startup_errors', 0); // Mantener en 0 para producción, pero para depurar puedes ponerlo en 1 TEMPORALMENTE
error_reporting(E_ALL);

// Helper function to log errors to a file
function logError($message) {
    // Asegúrate de que esta ruta sea ABSOLUTA y que el usuario del servidor web tenga permisos de escritura.
    // Por ejemplo: /var/www/html/tu_proyecto/logs/error.log
    error_log(date('[Y-m-d H:i:s] ') . $message . PHP_EOL, 3, dirname(__DIR__, 2) . '/logs/error.log');
}

// Set JSON header
header('Content-Type: application/json; charset=utf-8');

// Security: Validate session
if (!isset($_SESSION['login']) || !is_array($_SESSION['login']) || !isset($_SESSION['login']['id_usua']) || !isset($_SESSION['pac'])) {
    logError("Session validation failed: login or pac not set");
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    ob_end_flush();
    exit();
}

// Validate CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    logError("CSRF token validation failed");
    echo json_encode(['success' => false, 'message' => 'Token CSRF no válido']);
    ob_end_flush();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    logError("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    ob_end_flush();
    exit();
}

$id_atencion = (int)$_SESSION['pac'];
$id_usua = (int)$_SESSION['login']['id_usua']; // Este es el ID NUMÉRICO del usuario

// Fetch area from dat_ingreso
$sql_exp = "SELECT area FROM dat_ingreso WHERE id_atencion = ?";
$stmt_exp = $conexion->prepare($sql_exp);
if (!$stmt_exp) {
    logError("Prepare failed for dat_ingreso: " . $conexion->error);
    echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta de área']);
    ob_end_flush();
    exit();
}
$stmt_exp->bind_param("i", $id_atencion);
if (!$stmt_exp->execute()) {
    logError("Execute failed for dat_ingreso: id_atencion=$id_atencion, error=" . $stmt_exp->error);
    echo json_encode(['success' => false, 'message' => 'Error al obtener el área']);
    $stmt_exp->close();
    ob_end_flush();
    exit();
}
$result_exp = $stmt_exp->get_result();
if (!$result_exp || $result_exp->num_rows === 0) {
    logError("No results for dat_ingreso: id_atencion=$id_atencion");
    echo json_encode(['success' => false, 'message' => 'Área no encontrada']);
    $stmt_exp->close();
    ob_end_flush();
    exit();
}
$row_exp = $result_exp->fetch_assoc();
$area = $row_exp['area'] ?? 'No asignada';
$stmt_exp->close();

// Fetch user name for id_usua (Esta parte ya no es necesaria para el INSERT principal en dat_ctapac,
// ya que ahora se inserta el ID numérico del usuario. Pero se mantiene por si el nombre se usa en otro lugar
// o si la columna 'id_usua' en 'dat_ctapac' realmente almacenara el nombre y no el ID.)
$sql_usua = "SELECT papell, sapell FROM reg_usuarios WHERE id_usua = ?";
$stmt_usua = $conexion->prepare($sql_usua);
if (!$stmt_usua) {
    logError("Prepare failed for reg_usuarios: " . $conexion->error);
    echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta de usuario']);
    ob_end_flush();
    exit();
}
$stmt_usua->bind_param("i", $id_usua);
if (!$stmt_usua->execute()) {
    logError("Execute failed for reg_usuarios: id_usua=$id_usua, error=" . $stmt_usua->error);
    echo json_encode(['success' => false, 'message' => 'Error al obtener el usuario']);
    $stmt_usua->close();
    ob_end_flush();
    exit();
}
$result_usua = $stmt_usua->get_result();
if (!$result_usua || $result_usua->num_rows === 0) {
    logError("No results for reg_usuarios: id_usua=$id_usua");
    $id_u = 'Usuario Desconocido'; // Fallback por si el nombre no se encuentra
} else {
    $row_usua = $result_usua->fetch_assoc();
    $id_u = trim(($row_usua['papell'] ?? '') . ' ' . ($row_usua['sapell'] ?? ''));
    if (empty($id_u)) {
        logError("User name is empty for id_usua=$id_usua");
        $id_u = 'Usuario Desconocido';
    }
}
$stmt_usua->close();

// Process selected services
$selected_services = isset($_POST['services']) && is_array($_POST['services']) ? $_POST['services'] : [];
if (empty($selected_services)) {
    logError("No services provided in POST data");
    echo json_encode(['success' => false, 'message' => 'No se seleccionaron servicios']);
    ob_end_flush();
    exit();
}

$fecha_actual_sql = date("Y-m-d H:i:s"); // Esto está bien para la hora
$cant = 1;
$success = true;
$failed_services = [];

foreach ($selected_services as $serv_id => $cost) {
    $serv_id = (int)$serv_id;
    $cost = (float)$cost;
    // Ya no necesitamos $service_description aquí si prod_serv es 'S' fijo.
    // La validación del servicio se mantiene para asegurar que el ID es válido y el tip_insumo es 'CEYE'.
    $sql_serv = "SELECT serv_desc FROM cat_servicios WHERE id_serv = ? AND serv_activo = 'SI' AND tip_insumo = 'CEYE'";
    $stmt_serv = $conexion->prepare($sql_serv);
    if (!$stmt_serv) {
        logError("Prepare failed for cat_servicios: " . $conexion->error);
        $failed_services[] = $serv_id;
        $success = false;
        continue;
    }
    $stmt_serv->bind_param("i", $serv_id);
    if (!$stmt_serv->execute()) {
        logError("Execute failed for cat_servicios: id_serv=$serv_id, error=" . $stmt_serv->error);
        $failed_services[] = $serv_id;
        $success = false;
        $stmt_serv->close();
        continue;
    }
    $result_serv = $stmt_serv->get_result();
    if (!$result_serv || $result_serv->num_rows === 0) {
        logError("Invalid service ID or not CEYE group: $serv_id");
        $failed_services[] = $serv_id;
        $success = false;
        $stmt_serv->close();
        continue;
    }
    $stmt_serv->close(); // Close the statement after validation

    // Insert into dat_ctapac
    // prod_serv: 'S' (string, valor fijo)
    // insumo: $serv_id (int)
    // cta_fec: $fecha_actual_sql (string)
    // cta_cant: $cant (int)
    // cta_tot: $cost (float)
    // id_usua: $id_usua (int) <--- USAMOS EL ID NUMÉRICO DEL USUARIO
    // centro_cto: $area (string)
    $sql = "INSERT INTO dat_ctapac (id_atencion, prod_serv, insumo, cta_fec, cta_cant, cta_tot, id_usua, cta_activo, centro_cto) 
             VALUES (?, 'S', ?, ?, ?, ?, ?, 'SI', ?)";
    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        logError("Prepare failed for insert: " . $conexion->error);
        $failed_services[] = $serv_id;
        $success = false;
        continue;
    }
    // MODIFICADO:
    // La cadena de tipos ahora es: i (id_atencion), i (serv_id), s (fecha_actual_sql), i (cant), d (cost), i (id_usua), s (area)
    $stmt->bind_param("iisdiss", $id_atencion, $serv_id, $fecha_actual_sql, $cant, $cost, $id_usua, $area); 
    if (!$stmt->execute()) {
        logError("Insert failed for service $serv_id: " . $stmt->error);
        $failed_services[] = $serv_id;
        $success = false;
    }
    $stmt->close();
}

$response = [
    'success' => $success,
    'message' => $success ? 'Equipos registrados con éxito' : 'Error al registrar algunos equipos: ' . implode(', ', $failed_services)
];

echo json_encode($response);
ob_end_flush();
$conexion->close();
?>