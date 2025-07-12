<?php
session_start();
include "../../conexionbd.php";

// Verificar que la sesión del paciente esté activa
if (!isset($_SESSION['pac'])) {
    http_response_code(400);
    echo "Error: No hay paciente seleccionado";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Obtener los datos del formulario
        $id_atencion = $_SESSION['pac'];
        $id_usua = $_SESSION['id_usua'] ?? 1; // Usuario actual
        $id_tratamiento = $_POST['id_tratamiento'] ?? null;
        $sistg = $_POST['sistg'] ?? '';
        $diastg = $_POST['diastg'] ?? '';
        $fcardg = $_POST['fcardg'] ?? '';
        $frespg = $_POST['frespg'] ?? '';
        $satg = $_POST['satg'] ?? '';
        $tempg = $_POST['tempg'] ?? '';
        $hora = $_POST['hora_signos'] ?? '';
        
        // Validar campos requeridos
        if (empty($sistg) || empty($diastg) || empty($fcardg) || empty($frespg) || empty($satg) || empty($tempg) || empty($hora)) {
            http_response_code(400);
            echo "Error: Todos los campos de signos vitales son requeridos";
            exit;
        }
        
        // Limpiar datos de entrada (remover caracteres no numéricos excepto punto decimal)
        $sistg = preg_replace('/[^0-9.]/', '', $sistg);
        $diastg = preg_replace('/[^0-9.]/', '', $diastg);
        $fcardg = preg_replace('/[^0-9.]/', '', $fcardg);
        $frespg = preg_replace('/[^0-9.]/', '', $frespg);
        $satg = str_replace('%', '', preg_replace('/[^0-9.]/', '', $satg));
        $tempg = preg_replace('/[^0-9.]/', '', $tempg);
        
        // Obtener la fecha actual
        $fecha_g = date('Y-m-d');
        
        // Obtener el siguiente número de cuenta para este paciente y tratamiento
        $sql_cuenta = "SELECT COALESCE(MAX(cuenta), 0) + 1 as siguiente_cuenta 
                       FROM dat_trans_grafico 
                       WHERE id_atencion = ? AND id_tratamiento = ?";
        
        $stmt_cuenta = $conexion->prepare($sql_cuenta);
        $stmt_cuenta->bind_param("ii", $id_atencion, $id_tratamiento);
        $stmt_cuenta->execute();
        $result_cuenta = $stmt_cuenta->get_result();
        $cuenta = $result_cuenta->fetch_assoc()['siguiente_cuenta'];
        $stmt_cuenta->close();
        
        // Insertar los signos vitales en la base de datos
        $sql_insert = "INSERT INTO dat_trans_grafico 
                       (id_atencion, id_usua, id_tratamiento, hora, sistg, diastg, fcardg, frespg, satg, tempg, fecha_g, cuenta) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conexion->prepare($sql_insert);
        
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $conexion->error);
        }
        
        $stmt->bind_param("iiissssssssi", 
            $id_atencion, 
            $id_usua, 
            $id_tratamiento, 
            $hora, 
            $sistg, 
            $diastg, 
            $fcardg, 
            $frespg, 
            $satg, 
            $tempg, 
            $fecha_g, 
            $cuenta
        );
        
        if ($stmt->execute()) {
            $stmt->close();
            
            // Respuesta exitosa
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Signos vitales guardados correctamente',
                'id_registro' => $conexion->insert_id
            ]);
        } else {
            throw new Exception("Error al insertar los datos: " . $stmt->error);
        }
        
    } catch (Exception $e) {
        error_log("Error en insertar_trans_grafico.php: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error interno del servidor: ' . $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo "Método no permitido";
}
?>
