<?php
session_start();
require_once '../../conexionbd.php';

// Verificar sesión y método
if (!isset($_SESSION['login']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    echo json_encode(['error' => 'Acceso no autorizado']);
    exit;
}

// Validar parámetros
if (!isset($_POST['id_atencion']) || !is_numeric($_POST['id_atencion'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de atención inválido']);
    exit;
}

$id_atencion = intval($_POST['id_atencion']);

try {
    // Consultar el último registro de signos vitales
    $sql = "SELECT sistg, diastg, fcardg, frespg, satg, tempg, hora 
            FROM dat_trans_grafico 
            WHERE id_atencion = ? 
            ORDER BY fecha_g DESC, hora DESC 
            LIMIT 1";
    
    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta: " . $conexion->error);
    }
    
    $stmt->bind_param("i", $id_atencion);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // Datos encontrados
        echo json_encode([
            'sistg' => $row['sistg'],
            'diastg' => $row['diastg'],
            'fcardg' => $row['fcardg'],
            'frespg' => $row['frespg'],
            'satg' => $row['satg'],
            'tempg' => $row['tempg'],
            'hora' => $row['hora']
        ]);
    } else {
        // No hay datos
        echo json_encode([
            'sistg' => '--',
            'diastg' => '--',
            'fcardg' => '--',
            'frespg' => '--',
            'satg' => '--',
            'tempg' => '--',
            'hora' => '--'
        ]);
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    error_log("Error en get_ultimo_signos.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error interno del servidor']);
}

$conexion->close();
?>
