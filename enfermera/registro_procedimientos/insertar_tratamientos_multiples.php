<?php
session_start();
include '../../conexionbd.php';

// Validar sesión y usuario
$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];
$id_atencion = $_SESSION['pac'];

if (!$id_atencion || !$id_usua) {
    header('location: reg_pro.php?error=' . urlencode('No hay paciente seleccionado o usuario no válido'));
    exit;
}

// Validar que se recibieron los datos necesarios
if (!isset($_POST['tratamientos_seleccionados']) || !isset($_POST['datos_formularios'])) {
    header('location: reg_pro.php?error=' . urlencode('Datos de tratamientos no recibidos'));
    exit;
}

// Decodificar los datos JSON
$tratamientos_seleccionados = json_decode($_POST['tratamientos_seleccionados'], true);
$datos_formularios = json_decode($_POST['datos_formularios'], true);

if (!$tratamientos_seleccionados || !$datos_formularios) {
    header('location: reg_pro.php?error=' . urlencode('Error al procesar los datos de tratamientos'));
    exit;
}

$fecha_actual = date("Y-m-d H:i:s");
$tratamientos_insertados = [];
$errores = [];

// Iniciar transacción
$conexion->autocommit(false);

try {
    foreach ($tratamientos_seleccionados as $id_tratamiento) {
        if (!isset($datos_formularios[$id_tratamiento])) {
            continue;
        }
        
        $datos = $datos_formularios[$id_tratamiento];
        
        // Obtener el tipo de tratamiento para determinar la tabla de destino
        $stmt_tipo = $conexion->prepare("SELECT tipo FROM tratamientos WHERE id = ?");
        $stmt_tipo->bind_param("i", $id_tratamiento);
        $stmt_tipo->execute();
        $result_tipo = $stmt_tipo->get_result();
        
        if ($row_tipo = $result_tipo->fetch_assoc()) {
            $tipo_tratamiento = $row_tipo['tipo'];
            
            // Insertar según el tipo de tratamiento
            switch (strtoupper($tipo_tratamiento)) {
                case 'BLEFAROPLASTIA':
                    $id_tratamiento_para_signos = insertarBlefaroplastia($conexion, $datos, $id_atencion, $id_usua, $fecha_actual);
                    break;
                    
                case 'FACOEMULSIFICACION':
                    $id_tratamiento_para_signos = insertarFacoemulsificacion($conexion, $datos, $id_atencion, $id_usua, $fecha_actual);
                    break;
                    
                case 'CROSSLINKING':
                    $id_tratamiento_para_signos = insertarCrosslinking($conexion, $datos, $id_atencion, $id_usua, $fecha_actual);
                    break;
                    
                case 'INYECCION':
                case 'INYECCIÓN':
                    $id_tratamiento_para_signos = insertarInyeccion($conexion, $datos, $id_atencion, $id_usua, $fecha_actual);
                    break;
                    
                case 'CHALAZION':
                    $id_tratamiento_para_signos = insertarChalazion($conexion, $datos, $id_atencion, $id_usua, $fecha_actual);
                    break;
                    
                case 'PTERIGIÓN':
                case 'PTERIGION':
                    $id_tratamiento_para_signos = insertarPterigion($conexion, $datos, $id_atencion, $id_usua, $fecha_actual);
                    break;
                    
                case 'CIRUGÍA REFRACTIVA':
                case 'CIRUGIA REFRACTIVA':
                    $id_tratamiento_para_signos = insertarRefractiva($conexion, $datos, $id_atencion, $id_usua, $fecha_actual);
                    break;
                    
                case 'TRANSPLANTE':
                    $id_tratamiento_para_signos = insertarTransplante($conexion, $datos, $id_atencion, $id_usua, $fecha_actual);
                    break;
                    
                case 'VALVULA DE AHMED':
                case 'VÁLVULA DE AHMED':
                    $id_tratamiento_para_signos = insertarValvulaAhmed($conexion, $datos, $id_atencion, $id_usua, $fecha_actual);
                    break;
                    
                case 'VITRECTOMIA':
                    $id_tratamiento_para_signos = insertarVitrectomia($conexion, $datos, $id_atencion, $id_usua, $fecha_actual);
                    break;
                    
                case 'CIRUGÍA LASIK':
                case 'CIRUGIA LASIK':
                    $id_tratamiento_para_signos = insertarLasik($conexion, $datos, $id_atencion, $id_usua, $fecha_actual);
                    break;
                    
                default:
                    // Para tratamientos genéricos, insertar en una tabla general
                    $id_tratamiento_para_signos = insertarTratamientoGenerico($conexion, $datos, $id_atencion, $id_usua, $fecha_actual, $tipo_tratamiento);
                    break;
            }
            
            // Si no se definió un ID específico, usar el original (para compatibilidad)
            if (!isset($id_tratamiento_para_signos)) {
                $id_tratamiento_para_signos = $id_tratamiento;
            }
            
            // Insertar signos vitales si están presentes
            if (isset($datos['signos_vitales_multiples']) && is_array($datos['signos_vitales_multiples'])) {
                // Insertar múltiples registros de signos vitales
                foreach ($datos['signos_vitales_multiples'] as $signos) {
                    if (!empty($signos['sistg']) || !empty($signos['diastg']) || !empty($signos['fcardg']) || 
                        !empty($signos['frespg']) || !empty($signos['satg']) || !empty($signos['tempg'])) {
                        insertarSignosVitales($conexion, $signos, $id_atencion, $id_usua, $fecha_actual, $id_tratamiento_para_signos);
                    }
                }
            } else if (isset($datos['sistg']) || isset($datos['diastg']) || isset($datos['fcardg']) || 
                       isset($datos['frespg']) || isset($datos['satg']) || isset($datos['tempg'])) {
                // Insertar signos vitales individuales (compatibilidad hacia atrás)
                insertarSignosVitales($conexion, $datos, $id_atencion, $id_usua, $fecha_actual, $id_tratamiento_para_signos);
            }
            
            $tratamientos_insertados[] = $tipo_tratamiento;
        }
        
        $stmt_tipo->close();
    }
    
    // Confirmar transacción
    $conexion->commit();
    
    // Redireccionar con éxito
    $mensaje_exito = count($tratamientos_insertados) . ' tratamiento(s) guardado(s) correctamente: ' . implode(', ', $tratamientos_insertados);
    header('location: reg_pro.php?exito_multiples=' . urlencode($mensaje_exito));
    exit;
    
} catch (Exception $e) {
    // Revertir transacción en caso de error
    $conexion->rollback();
    error_log("Error en insertar_tratamientos_multiples.php: " . $e->getMessage());
    header('location: reg_pro.php?error=' . urlencode('Error al guardar los tratamientos: ' . $e->getMessage()));
    exit;
}

// Funciones de inserción específicas por tipo de tratamiento

function insertarSignosVitales($conexion, $datos, $id_atencion, $id_usua, $fecha_actual, $id_tratamiento) {
    $sistg = $datos['sistg'] ?? '';
    $diastg = $datos['diastg'] ?? '';
    $fcardg = $datos['fcardg'] ?? '';
    $frespg = $datos['frespg'] ?? '';
    $satg = $datos['satg'] ?? '';
    $tempg = $datos['tempg'] ?? '';
    $hora_signos = $datos['hora_signos'] ?? date('H:i:s');
    
    // Validar que al menos un campo tenga datos
    if (empty($sistg) && empty($diastg) && empty($fcardg) && empty($frespg) && empty($satg) && empty($tempg)) {
        return; // No insertar si todos los campos están vacíos
    }
    
    // Obtener el siguiente número de cuenta
    $stmt_cuenta = $conexion->prepare("SELECT COALESCE(MAX(cuenta), 0) + 1 as siguiente_cuenta FROM dat_trans_grafico WHERE id_atencion = ?");
    $stmt_cuenta->bind_param("i", $id_atencion);
    $stmt_cuenta->execute();
    $resultado_cuenta = $stmt_cuenta->get_result();
    
    $cuenta = 1;
    if ($fila_cuenta = $resultado_cuenta->fetch_assoc()) {
        $cuenta = $fila_cuenta['siguiente_cuenta'];
    }
    $stmt_cuenta->close();
    
    // SQL corregido para coincidir con la estructura real de la tabla
    $sql_signos = "INSERT INTO dat_trans_grafico (
        id_atencion, id_usua, id_tratamiento, hora, 
        sistg, diastg, fcardg, frespg, satg, tempg, fecha_g, cuenta
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt_signos = $conexion->prepare($sql_signos);
    $stmt_signos->bind_param("iiissssssssi", 
        $id_atencion, $id_usua, $id_tratamiento, $hora_signos,
        $sistg, $diastg, $fcardg, $frespg, $satg, $tempg, $fecha_actual, $cuenta
    );
    
    if (!$stmt_signos->execute()) {
        throw new Exception("Error al insertar signos vitales: " . $stmt_signos->error);
    }
    $stmt_signos->close();
}

function insertarTratamientoGenerico($conexion, $datos, $id_atencion, $id_usua, $fecha_actual, $tipo_tratamiento) {
    $medico_tratante = $datos['medico_tratante'] ?? '';
    $anestesiologo = $datos['anestesiologo'] ?? '';
    $anestesia = $datos['anestesia'] ?? '';
    $nota_enfermeria = $datos['nota_enfermeria'] ?? '';
    $enfermera_responsable = $datos['enfermera_responsable'] ?? '';
    
    $sql_create = "CREATE TABLE IF NOT EXISTS dat_tratamientos_genericos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_atencion INT NOT NULL,
        id_usua INT NOT NULL,
        tipo_tratamiento VARCHAR(255) NOT NULL,
        medico_tratante VARCHAR(255),
        anestesiologo VARCHAR(255),
        anestesia VARCHAR(100),
        nota_enfermeria TEXT,
        enfermera_responsable VARCHAR(255),
        fecha_registro DATETIME NOT NULL,
        FOREIGN KEY (id_atencion) REFERENCES dat_ingreso(id_atencion)
    )";
    
    if (!$conexion->query($sql_create)) {
        throw new Exception("Error al crear tabla de tratamientos genéricos: " . $conexion->error);
    }
    
    $sql_insert = "INSERT INTO dat_tratamientos_genericos (
        id_atencion, id_usua, tipo_tratamiento, medico_tratante, anestesiologo, 
        anestesia, nota_enfermeria, enfermera_responsable, fecha_registro
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($sql_insert);
    $stmt->bind_param("iisssssss", 
        $id_atencion, $id_usua, $tipo_tratamiento, $medico_tratante, 
        $anestesiologo, $anestesia, $nota_enfermeria, $enfermera_responsable, $fecha_actual
    );
    
    if (!$stmt->execute()) {
        throw new Exception("Error al insertar tratamiento genérico: " . $stmt->error);
    }
    
    // Obtener el ID del registro recién insertado
    $id_insertado = $conexion->insert_id;
    $stmt->close();
    
    return $id_insertado;
}

function insertarBlefaroplastia($conexion, $datos, $id_atencion, $id_usua, $fecha_actual) {
    // Implementar inserción específica para blefaroplastia
    return insertarTratamientoGenerico($conexion, $datos, $id_atencion, $id_usua, $fecha_actual, 'BLEFAROPLASTIA');
}

function insertarFacoemulsificacion($conexion, $datos, $id_atencion, $id_usua, $fecha_actual) {
    // Implementar inserción específica para facoemulsificación
    return insertarTratamientoGenerico($conexion, $datos, $id_atencion, $id_usua, $fecha_actual, 'FACOEMULSIFICACION');
}

function insertarCrosslinking($conexion, $datos, $id_atencion, $id_usua, $fecha_actual) {
    // Implementar inserción específica para crosslinking
    return insertarTratamientoGenerico($conexion, $datos, $id_atencion, $id_usua, $fecha_actual, 'CROSSLINKING');
}

function insertarInyeccion($conexion, $datos, $id_atencion, $id_usua, $fecha_actual) {
    // Implementar inserción específica para inyección
    return insertarTratamientoGenerico($conexion, $datos, $id_atencion, $id_usua, $fecha_actual, 'INYECCION');
}

function insertarChalazion($conexion, $datos, $id_atencion, $id_usua, $fecha_actual) {
    // Implementar inserción específica para chalazión
    return insertarTratamientoGenerico($conexion, $datos, $id_atencion, $id_usua, $fecha_actual, 'CHALAZION');
}

function insertarPterigion($conexion, $datos, $id_atencion, $id_usua, $fecha_actual) {
    // Implementar inserción específica para pterigión
    return insertarTratamientoGenerico($conexion, $datos, $id_atencion, $id_usua, $fecha_actual, 'PTERIGION');
}

function insertarRefractiva($conexion, $datos, $id_atencion, $id_usua, $fecha_actual) {
    // Implementar inserción específica para cirugía refractiva
    return insertarTratamientoGenerico($conexion, $datos, $id_atencion, $id_usua, $fecha_actual, 'CIRUGIA REFRACTIVA');
}

function insertarTransplante($conexion, $datos, $id_atencion, $id_usua, $fecha_actual) {
    // Implementar inserción específica para transplante
    return insertarTratamientoGenerico($conexion, $datos, $id_atencion, $id_usua, $fecha_actual, 'TRANSPLANTE');
}

function insertarValvulaAhmed($conexion, $datos, $id_atencion, $id_usua, $fecha_actual) {
    return insertarTratamientoGenerico($conexion, $datos, $id_atencion, $id_usua, $fecha_actual, 'VALVULA DE AHMED');
}

function insertarVitrectomia($conexion, $datos, $id_atencion, $id_usua, $fecha_actual) {
    return insertarTratamientoGenerico($conexion, $datos, $id_atencion, $id_usua, $fecha_actual, 'VITRECTOMIA');
}

function insertarLasik($conexion, $datos, $id_atencion, $id_usua, $fecha_actual) {
    $medico_tratante = $datos['medico_tratante'] ?? '';
    $anestesiologo = $datos['anestesiologo'] ?? '';
    $anestesia = $datos['anestesia'] ?? '';
    $nota_enfermeria = $datos['nota_enfermeria'] ?? '';
    $enfermera_responsable = $datos['enfermera_responsable'] ?? '';
    $medico_responsable = $datos['medico_responsable'] ?? '';
    
    $od_queratometria = $datos['od_queratometria'] ?? '';
    $od_microqueratomo = $datos['od_microqueratomo'] ?? '';
    $od_anillo = $datos['od_anillo'] ?? '';
    $od_tope = $datos['od_tope'] ?? '';
    $oi_queratometria = $datos['oi_queratometria'] ?? '';
    $oi_microqueratomo = $datos['oi_microqueratomo'] ?? '';
    $oi_anillo = $datos['oi_anillo'] ?? '';
    $oi_tope = $datos['oi_tope'] ?? '';
    
    $sql_create = "CREATE TABLE IF NOT EXISTS dat_lasik (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_atencion INT NOT NULL,
        id_usua INT NOT NULL,
        medico_tratante VARCHAR(255),
        anestesiologo VARCHAR(255),
        anestesia VARCHAR(100),
        od_queratometria VARCHAR(100),
        od_microqueratomo VARCHAR(100),
        od_anillo VARCHAR(100),
        od_tope VARCHAR(100),
        oi_queratometria VARCHAR(100),
        oi_microqueratomo VARCHAR(100),
        oi_anillo VARCHAR(100),
        oi_tope VARCHAR(100),
        nota_enfermeria TEXT,
        enfermera_responsable VARCHAR(255),
        medico_responsable VARCHAR(255),
        fecha_registro DATETIME NOT NULL,
        FOREIGN KEY (id_atencion) REFERENCES dat_ingreso(id_atencion)
    )";
    
    if (!$conexion->query($sql_create)) {
        throw new Exception("Error al crear tabla de LASIK: " . $conexion->error);
    }
    
    $sql_insert = "INSERT INTO dat_lasik (
        id_atencion, id_usua, medico_tratante, anestesiologo, anestesia,
        od_queratometria, od_microqueratomo, od_anillo, od_tope,
        oi_queratometria, oi_microqueratomo, oi_anillo, oi_tope,
        nota_enfermeria, enfermera_responsable, medico_responsable, fecha_registro
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($sql_insert);
    $stmt->bind_param("iisssssssssssssss", 
        $id_atencion, $id_usua, $medico_tratante, $anestesiologo, $anestesia,
        $od_queratometria, $od_microqueratomo, $od_anillo, $od_tope,
        $oi_queratometria, $oi_microqueratomo, $oi_anillo, $oi_tope,
        $nota_enfermeria, $enfermera_responsable, $medico_responsable, $fecha_actual
    );
    
    if (!$stmt->execute()) {
        throw new Exception("Error al insertar tratamiento LASIK: " . $stmt->error);
    }
    
    // Obtener el ID del registro recién insertado
    $id_insertado = $conexion->insert_id;
    $stmt->close();
    
    return $id_insertado;
}

?>
