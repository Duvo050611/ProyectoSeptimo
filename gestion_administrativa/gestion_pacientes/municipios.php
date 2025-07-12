<?php
include "../../conexionbd.php";

header('Content-Type: application/json');

if (isset($_GET['estado_id']) && is_numeric($_GET['estado_id'])) {
    $estado_id = intval($_GET['estado_id']);
    $query = "SELECT id_mun, nombre_m FROM municipios WHERE estado_id = ? AND activo = 1 ORDER BY nombre_m ASC";
    
    if ($stmt = $conexion->prepare($query)) {
        $stmt->bind_param("i", $estado_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $municipios = [];
        while ($row = $result->fetch_assoc()) {
            $municipios[] = [
                'id_mun' => $row['id_mun'],
                'nombre_m' => $row['nombre_m']
            ];
        }
        
        $stmt->close();
        echo json_encode($municipios);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}

$conexion->close();
?>