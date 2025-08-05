<?php 
session_start();
require_once '../../conexionbd.php';

// Security: Validate session
if (!isset($_SESSION['login']) || !is_array($_SESSION['login']) || !isset($_SESSION['login']['id_usua'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Acceso no autorizado']);
    exit;
}

if (!isset($_SESSION['pac']) || !is_numeric($_SESSION['pac'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de atención no válido']);
    exit;
}

$id_atencion = $_SESSION['pac'];
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de registro inválido']);
    exit;
}

// Get single record with security check - tabla actualizada sin campo cuenta
$sql = "SELECT id_trans_graf, hora, sistg, diastg, fcardg, frespg, satg, tempg, fecha_g 
        FROM dat_trans_grafico 
        WHERE id_trans_graf = ? AND id_atencion = ?";

$stmt = $conexion->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la preparación de la consulta']);
    exit;
}

$stmt->bind_param("ii", $id, $id_atencion);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Sanitize output data
    $data = array(
        'id' => htmlspecialchars($row['id_trans_graf'], ENT_QUOTES, 'UTF-8'),
        'hora' => htmlspecialchars($row['hora'], ENT_QUOTES, 'UTF-8'),
        'sistg' => htmlspecialchars($row['sistg'], ENT_QUOTES, 'UTF-8'),
        'diastg' => htmlspecialchars($row['diastg'], ENT_QUOTES, 'UTF-8'),
        'fcardg' => htmlspecialchars($row['fcardg'], ENT_QUOTES, 'UTF-8'),
        'frespg' => htmlspecialchars($row['frespg'], ENT_QUOTES, 'UTF-8'),
        'satg' => htmlspecialchars($row['satg'], ENT_QUOTES, 'UTF-8'),
        'tempg' => htmlspecialchars($row['tempg'], ENT_QUOTES, 'UTF-8'),
        'fecha_g' => htmlspecialchars($row['fecha_g'], ENT_QUOTES, 'UTF-8')
    );
    
    echo json_encode($data);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Registro no encontrado']);
}

$stmt->close();
$conexion->close();
?>
