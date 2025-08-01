<?php
ob_start();
session_start();
include '../../conexionbd.php';

// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Helper function to log errors to a file
function logError($message) {
    // Asegúrate de que esta ruta sea ABSOLUTA y que el usuario del servidor web tenga permisos de escritura.
    // Por ejemplo: /var/www/html/tu_proyecto/logs/services_handler_errors.log
    error_log(date('[Y-m-d H:i:s] ') . $message . PHP_EOL);
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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    logError("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    ob_end_flush();
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['csrf_token']) || $data['csrf_token'] !== $_SESSION['csrf_token']) {
    logError("CSRF token validation failed. Received: " . ($data['csrf_token'] ?? 'none') . ", Expected: " . ($_SESSION['csrf_token'] ?? 'none'));
    echo json_encode(['success' => false, 'message' => 'Token CSRF no válido']);
    ob_end_flush();
    exit();
}

$action = $data['action'] ?? '';
$id_usua = (int)$_SESSION['login']['id_usua'];
$id_atencion = (int)$_SESSION['pac'];

logError("Processing action: $action, id_usua: $id_usua, id_atencion: $id_atencion");

switch ($action) {
    case 'get':
        // Fetch registered services
        // MODIFICADO:
        // 1. Se eliminó la condición 'AND c.prod_serv = "S"'
        // 2. Se añadió la condición 'AND s.tip_insumo = "CEYE"' para filtrar por el tip_insumo correcto de servicios.
        $sql = "SELECT c.id_ctapac, c.insumo, c.cta_tot, c.cta_fec, s.serv_desc 
                FROM dat_ctapac c 
                LEFT JOIN cat_servicios s ON c.insumo = s.id_serv 
                WHERE c.id_usua = ? AND c.id_atencion = ? AND c.cta_activo = 'SI' AND s.tip_insumo = 'CEYE'";
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            logError("Prepare failed for get services: " . $conexion->error);
            echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta']);
            ob_end_flush();
            exit();
        }
        $stmt->bind_param("ii", $id_usua, $id_atencion);
        if (!$stmt->execute()) {
            logError("Execute failed for get services: id_usua=$id_usua, id_atencion=$id_atencion, error=" . $stmt->error);
            echo json_encode(['success' => false, 'message' => 'Error al obtener servicios']);
            $stmt->close();
            ob_end_flush();
            exit();
        }
        $result = $stmt->get_result();
        $services = [];
        while ($row = $result->fetch_assoc()) {
            $services[] = [
                'id_ctapac' => $row['id_ctapac'],
                'serv_desc' => $row['serv_desc'] ?? 'Desconocido', // Usa serv_desc de cat_servicios
                'cta_tot' => $row['cta_tot'],
                'cta_fec' => $row['cta_fec'],
                'insumo' => $row['insumo']
            ];
        }
        $stmt->close();
        logError("Fetched " . count($services) . " services for id_usua=$id_usua, id_atencion=$id_atencion");
        echo json_encode(['success' => true, 'services' => $services]);
        break;

    case 'delete':
        // Delete a service
        $id_ctapac = (int)($data['id_ctapac'] ?? 0);
        if ($id_ctapac <= 0) {
            logError("Invalid id_ctapac: $id_ctapac");
            echo json_encode(['success' => false, 'message' => 'ID de servicio inválido']);
            ob_end_flush();
            exit();
        }
        // Agregamos la verificación de tip_insumo para asegurar que solo se eliminen los del tip_insumo CEYE.
        $sql = "DELETE c FROM dat_ctapac c
                INNER JOIN cat_servicios s ON c.insumo = s.id_serv
                WHERE c.id_ctapac = ? AND c.id_usua = ? AND c.id_atencion = ? AND s.tip_insumo = 'CEYE'";
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            logError("Prepare failed for delete: " . $conexion->error);
            echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta']);
            ob_end_flush();
            exit();
        }
        $stmt->bind_param("iii", $id_ctapac, $id_usua, $id_atencion);
        if ($stmt->execute()) {
            // Verificar si se afectó alguna fila para confirmar la eliminación.
            if ($stmt->affected_rows > 0) {
                logError("Service deleted: id_ctapac=$id_ctapac");
                echo json_encode(['success' => true]);
            } else {
                logError("Delete failed: No service found with id_ctapac=$id_ctapac for user/attention/group CEYE or already deleted.");
                echo json_encode(['success' => false, 'message' => 'No se encontró el servicio para eliminar o ya fue eliminado.']);
            }
        } else {
            logError("Delete failed: id_ctapac=$id_ctapac, error=" . $stmt->error);
            echo json_encode(['success' => false, 'message' => 'Error al eliminar servicio']);
        }
        $stmt->close();
        break;

    case 'update':
        // Update a service
        $id_ctapac = (int)($data['id_ctapac'] ?? 0);
        $cta_tot = (float)($data['cta_tot'] ?? 0);
        $insumo = (int)($data['insumo'] ?? 0);
        if ($id_ctapac <= 0 || $cta_tot <= 0 || $insumo <= 0) {
            logError("Invalid update data: id_ctapac=$id_ctapac, cta_tot=$cta_tot, insumo=$insumo");
            echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
            ob_end_flush();
            exit();
        }
        // Agregamos el JOIN y la verificación de tip_insumo para asegurar que solo se actualicen los del tip_insumo CEYE.
        // También podemos actualizar prod_serv si es necesario, obteniendo la nueva descripción.
        $sql = "UPDATE dat_ctapac c
                INNER JOIN cat_servicios s ON c.insumo = s.id_serv
                SET c.cta_tot = ?, c.insumo = ?
                WHERE c.id_ctapac = ? AND c.id_usua = ? AND c.id_atencion = ? AND s.tip_insumo = 'CEYE'";
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            logError("Prepare failed for update: " . $conexion->error);
            echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta']);
            ob_end_flush();
            exit();
        }
        $stmt->bind_param("diiii", $cta_tot, $insumo, $id_ctapac, $id_usua, $id_atencion);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                logError("Service updated: id_ctapac=$id_ctapac, cta_tot=$cta_tot, insumo=$insumo");
                echo json_encode(['success' => true]);
            } else {
                logError("Update failed: No service found with id_ctapac=$id_ctapac for user/attention/group CEYE or no changes made.");
                echo json_encode(['success' => false, 'message' => 'No se encontró el servicio para actualizar o no hubo cambios.']);
            }
        } else {
            logError("Update failed: id_ctapac=$id_ctapac, error=" . $stmt->error);
            echo json_encode(['success' => false, 'message' => 'Error al actualizar servicio']);
        }
        $stmt->close();
        break;

    default:
        logError("Invalid action: $action");
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        break;
}

ob_end_flush();
$conexion->close();
?>