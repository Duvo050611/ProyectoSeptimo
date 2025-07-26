<?php
session_start();
include "../../conexionbd.php";

// Configurar cabeceras para JSON
header('Content-Type: application/json');

// Verificar que haya un paciente seleccionado
if (!isset($_SESSION['pac']) || empty($_SESSION['pac'])) {
    echo json_encode(['success' => false, 'message' => 'No hay paciente seleccionado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'terminar_cirugia') {
    try {
        $id_atencion = $_SESSION['pac'];
        
        // Verificación rápida de duplicados
        $check = $conexion->query("SELECT id_trans_graf FROM dat_trans_grafico 
                                  WHERE id_atencion=$id_atencion AND sistg='CIRUGIA' AND diastg='TERMINADA' LIMIT 1");
        
        if ($check && $check->num_rows > 0) {
            echo json_encode([
                'success' => false, 
                'message' => 'La cirugía ya ha sido marcada como terminada anteriormente'
            ]);
            exit;
        }
        
        // Insertar registro de terminación
        $hora_actual = date('H:i:s');
        $insert = $conexion->query("INSERT INTO dat_trans_grafico 
                                   (id_atencion, id_usua, sistg, diastg, fcardg, frespg, satg, tempg, hora) 
                                   VALUES ($id_atencion, 1, 'CIRUGIA', 'TERMINADA', 'BLOQUEADO', 'BLOQUEADO', 'BLOQUEADO', 'BLOQUEADO', '$hora_actual')");
        
        if ($insert) {
            // Marcar en sesión inmediatamente
            $_SESSION['cirugia_terminada_' . $id_atencion] = true;
            
            echo json_encode([
                'success' => true, 
                'message' => 'Cirugía terminada exitosamente'
            ]);
        } else {
            throw new Exception("Error en la base de datos: " . $conexion->error);
        }
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false, 
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

$conexion->close();
?>
