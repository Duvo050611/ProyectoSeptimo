<?php
// Limpiar cualquier output buffer previo
if (ob_get_length()) ob_clean();

// Configurar error reporting para capturar errores
error_reporting(E_ALL);
ini_set('display_errors', 0); // No mostrar errores en pantalla
ini_set('log_errors', 1);

include "../../conexionbd.php";

session_start();
date_default_timezone_set('America/Guatemala');

$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];

// Verificar roles primero
if (!isset($usuario['id_rol']) || !in_array($usuario['id_rol'], [11, 4, 5])) {
    session_unset();
    session_destroy();
    echo "<script>window.location='../../index.php';</script>";
    exit();
}

// Manejar AJAX ANTES de cualquier output HTML
$input = json_decode(file_get_contents('php://input'), true);

if (!empty($input)) {
    // Debug temporal para identificar el problema de JSON
    error_log("=== REQUEST POST RECIBIDO ===");
    error_log("Input JSON: " . print_r($input, true));
    error_log("Headers: " . print_r(getallheaders(), true));
    error_log("Buffer level antes: " . ob_get_level());
    
    // Limpiar cualquier output buffer antes de enviar JSON
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    // Asegurar que la respuesta sea JSON
    header('Content-Type: application/json');
    
    // Capturar cualquier error y convertirlo en respuesta JSON
    try {
    
    // ACTUALIZAR - Obtiene valores recalculados de JavaScript y actualiza carrito_entradas y transacciones
    $carrito_id = $input['carrito_id'] ?? null;
    $item_id = $input['item_id'] ?? null;
    $id_compra = $input['id_compra'] ?? null;
    
    error_log("DEBUG ACTUALIZAR - Iniciando actualización para carrito_id: " . $carrito_id);
    error_log("DEBUG ACTUALIZAR - Datos recibidos del frontend: " . print_r($input, true));
    
    $updates = [];
    $params = [];

    // Campos simples que el usuario puede modificar directamente
    if (isset($input['entrada_lote'])) {
        $updates[] = "entrada_lote = ?";
        $params[] = $input['entrada_lote'];
    }
    if (isset($input['entrada_caducidad'])) {
        $updates[] = "entrada_caducidad = ?";
        $params[] = date('Y-m-d', strtotime($input['entrada_caducidad']));
    }
    if (isset($input['ubicacion_id'])) {
        $updates[] = "ubicacion_id = ?";
        $params[] = $input['ubicacion_id'];
    }
    
    // Campos calculados: usar valores recalculados de JavaScript
    if (isset($input['entrada_qty'])) {
        // Validación de cantidad
        $nueva_cantidad = $input['entrada_qty'];
        
        // Obtener cantidad actual del registro específico
        $query_cantidad_actual = "SELECT entrada_qty FROM carrito_entradas WHERE id = ? LIMIT 1";
        $stmt_cantidad_actual = $conexion->prepare($query_cantidad_actual);
        $stmt_cantidad_actual->bind_param('i', $carrito_id);
        $stmt_cantidad_actual->execute();
        $result_cantidad_actual = $stmt_cantidad_actual->get_result();
        $cantidad_actual_row = $result_cantidad_actual->fetch_assoc();
        $cantidad_actual = $cantidad_actual_row['entrada_qty'] ?? 0;
        
        // Obtener estado de completitud del item
        $query_completitud = "SELECT solicita, entrega FROM orden_compra WHERE id_compra = ? AND item_id = ? LIMIT 1";
        $stmt_completitud = $conexion->prepare($query_completitud);
        $stmt_completitud->bind_param('ii', $id_compra, $item_id);
        $stmt_completitud->execute();
        $result_completitud = $stmt_completitud->get_result();
        $completitud_row = $result_completitud->fetch_assoc();
        $solicita = $completitud_row['solicita'] ?? 0;
        $entrega_actual = $completitud_row['entrega'] ?? 0;
        
        // Verificar si el item está completo (solicita = entrega)
        $item_completo = ($solicita > 0 && $solicita == $entrega_actual);
        $diferencia_reducida = null; // Inicializar para uso posterior en mensaje de éxito
        
        if ($item_completo) {
            // Si el item está completo, solo permitir reducción de cantidad
            if ($nueva_cantidad > $cantidad_actual) {
                echo json_encode([
                    "status" => "error",
                    "message" => "Error: El ítem está completo (solicita = entrega). Solo se permite reducir la cantidad. Cantidad actual: {$cantidad_actual}, cantidad máxima permitida: {$cantidad_actual}"
                ]);
                exit;
            }
        } else {
            // Si el item no está completo, aplicar validación normal
            // Obtener la suma actual de cantidades para este item_id (excluyendo el registro actual)
            $query_suma_actual = "SELECT SUM(entrada_qty) as suma_actual FROM carrito_entradas WHERE id_compra = ? AND item_id = ? AND id != ?";
            $stmt_suma_actual = $conexion->prepare($query_suma_actual);
            $stmt_suma_actual->bind_param('iii', $id_compra, $item_id, $carrito_id);
            $stmt_suma_actual->execute();
            $result_suma_actual = $stmt_suma_actual->get_result();
            $suma_actual_row = $result_suma_actual->fetch_assoc();
            $suma_actual = $suma_actual_row['suma_actual'] ?? 0;
            
            // Validar que la nueva suma no exceda lo solicitado
            $nueva_suma_total = $suma_actual + $nueva_cantidad;
            if ($nueva_suma_total > $solicita) {
                echo json_encode([
                    "status" => "error",
                    "message" => "Error: La suma total de cantidades ({$nueva_suma_total}) excede lo solicitado ({$solicita}) para este ítem. Cantidad máxima permitida: " . ($solicita - $suma_actual)
                ]);
                exit;
            }
        }
        
        // LÓGICA UNIVERSAL: SIEMPRE calcular diferencia y restar del entrega actual para TODOS los casos
        if ($nueva_cantidad != $cantidad_actual) {
            // Calcular diferencia: cantidad_anterior - cantidad_nueva
            // Ejemplo: 50 - 40 = 10 (diferencia a restar del entrega)
            $diferencia_cantidad = $cantidad_actual - $nueva_cantidad;
            
            // Obtener el valor actual de entrega desde orden_compra
            $query_entrega_actual = "SELECT entrega FROM orden_compra WHERE id_compra = ? AND item_id = ? LIMIT 1";
            $stmt_entrega_actual = $conexion->prepare($query_entrega_actual);
            $stmt_entrega_actual->bind_param('ii', $id_compra, $item_id);
            $stmt_entrega_actual->execute();
            $result_entrega_actual = $stmt_entrega_actual->get_result();
            $entrega_row = $result_entrega_actual->fetch_assoc();
            $entrega_actual_real = $entrega_row['entrega'] ?? 0;
            
            // APLICAR FÓRMULA UNIVERSAL: entrega_actual - diferencia
            // Ejemplo: 200 - 10 = 190
            $nueva_entrega = $entrega_actual_real - $diferencia_cantidad;
            
            // Validación de seguridad: no permitir valores negativos
            if ($nueva_entrega >= 0) {
                // Actualizar el campo entrega en orden_compra
                $query_actualizar_entrega = "UPDATE orden_compra SET entrega = ? WHERE id_compra = ? AND item_id = ?";
                $stmt_actualizar_entrega = $conexion->prepare($query_actualizar_entrega);
                $stmt_actualizar_entrega->bind_param('iii', $nueva_entrega, $id_compra, $item_id);
                
                if (!$stmt_actualizar_entrega->execute()) {
                    echo json_encode([
                        "status" => "error", 
                        "message" => "Error al actualizar el campo entrega: " . $stmt_actualizar_entrega->error
                    ]);
                    exit;
                }
                
                $diferencia_reducida = $diferencia_cantidad; // Guardar para el mensaje
                error_log("DEBUG UNIVERSAL - Cantidad: {$cantidad_actual} → {$nueva_cantidad}. Diferencia: {$diferencia_cantidad}. Entrega: {$entrega_actual_real} → {$nueva_entrega}");
            } else {
                echo json_encode([
                    "status" => "error", 
                    "message" => "Error: La actualización resultaría en un valor de entrega negativo ({$nueva_entrega}). Cantidad anterior: {$cantidad_actual}, nueva cantidad: {$nueva_cantidad}, diferencia: {$diferencia_cantidad}, entrega actual: {$entrega_actual_real}."
                ]);
                exit;
            }
        }
        
        $updates[] = "entrada_qty = ?";
        $params[] = $input['entrada_qty'];
    }
    if (isset($input['entrada_unidosis'])) {
        $updates[] = "entrada_unidosis = ?";
        $params[] = $input['entrada_unidosis'];
    }
    if (isset($input['entrada_costo'])) {
        $updates[] = "entrada_costo = ?";
        $params[] = $input['entrada_costo'];
    }
    if (isset($input['entrada_iva'])) {
        $updates[] = "entrada_iva = ?";
        $params[] = $input['entrada_iva'];
    }
    if (isset($input['entrada_descuento'])) {
        $updates[] = "entrada_descuento = ?";
        $params[] = $input['entrada_descuento'];
    }
    if (isset($input['Total'])) {
        $updates[] = "Total = ?";
        $params[] = $input['Total'];
    }

    error_log("DEBUG ACTUALIZAR - Campos a actualizar en carrito_entradas: " . print_r($updates, true));
    error_log("DEBUG ACTUALIZAR - Valores a actualizar: " . print_r($params, true));

    // Solo procede si hay campos para actualizar
    if (!empty($updates)) {
        $params[] = $carrito_id;

        // 1. Actualizar carrito_entradas
        $sql = "UPDATE carrito_entradas SET " . implode(", ", $updates) . " WHERE id = ?";
        
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            die(json_encode(["status" => "error", "message" => "Error al preparar la consulta: " . $conexion->error]));
        }

        $stmt->bind_param(str_repeat("s", count($params)), ...$params);

        // Ejecutar la consulta en carrito_entradas
        if ($stmt->execute()) {
            error_log("DEBUG ACTUALIZAR - Actualización en carrito_entradas exitosa");
            
            // 2. Sincronizar transacciones con los mismos valores
            $updates_transacciones = [];
            $params_transacciones = [];
            
            // Mapear todos los campos del input a transacciones
            foreach ($input as $campo => $valor) {
                if ($campo === 'action' || $campo === 'carrito_id' || $campo === 'item_id' || $campo === 'id_compra') {
                    continue;
                }
                
                // Mapear los campos de entrada a los campos de transacciones
                if ($campo === 'entrada_lote') {
                    $updates_transacciones[] = "entrada_lote = ?";
                    $params_transacciones[] = $valor;
                } elseif ($campo === 'entrada_caducidad') {
                    $updates_transacciones[] = "entrada_caducidad = ?";
                    $params_transacciones[] = date('Y-m-d', strtotime($valor));
                } elseif ($campo === 'entrada_qty') {
                    $updates_transacciones[] = "entrada_qty = ?";
                    $params_transacciones[] = $valor;
                } elseif ($campo === 'entrada_unidosis') {
                    $updates_transacciones[] = "entrada_unidosis = ?";
                    $params_transacciones[] = $valor;
                } elseif ($campo === 'entrada_costo') {
                    $updates_transacciones[] = "entrada_costo = ?";
                    $params_transacciones[] = $valor;
                } elseif ($campo === 'ubicacion_id') {
                    $updates_transacciones[] = "ubicacion_id = ?";
                    $params_transacciones[] = $valor;
                } elseif ($campo === 'entrada_iva') {
                    $updates_transacciones[] = "entrada_iva = ?";
                    $params_transacciones[] = $valor;
                } elseif ($campo === 'entrada_descuento') {
                    $updates_transacciones[] = "entrada_descuento = ?";
                    $params_transacciones[] = $valor;
                } elseif ($campo === 'Total') {
                    $updates_transacciones[] = "Total = ?";
                    $params_transacciones[] = $valor;
                }
            }
            
            // 3. Actualizar transacciones si hay campos para actualizar
            if (!empty($updates_transacciones)) {
                $params_transacciones[] = $carrito_id; // ID del carrito como referencia
                
                $sql_transacciones = "UPDATE transacciones SET " . implode(", ", $updates_transacciones) . " WHERE id_carrito_entrada = ?";
                $stmt_transacciones_sync = $conexion->prepare($sql_transacciones);
                
                if ($stmt_transacciones_sync) {
                    $stmt_transacciones_sync->bind_param(str_repeat("s", count($params_transacciones)), ...$params_transacciones);
                    
                    if (!$stmt_transacciones_sync->execute()) {
                        error_log("DEBUG ACTUALIZAR - Error al sincronizar transacciones: " . $stmt_transacciones_sync->error);
                    } else {
                        error_log("DEBUG ACTUALIZAR - Sincronización de transacciones exitosa");
                    }
                } else {
                    error_log("DEBUG ACTUALIZAR - Error al preparar sincronización de transacciones: " . $conexion->error);
                }
            }

            // 4. Actualizar estados en ordenes_compra si se está actualizando la cantidad
            if (isset($input['entrada_qty'])) {
                // NOTA: El campo 'entrega' ya fue actualizado con la lógica universal en las líneas anteriores
                // Aquí solo actualizamos los estados basados en comparaciones
                
                // Obtener la suma total actual de cantidades para validaciones de estado
                $query_suma_total = "SELECT SUM(entrada_qty) as suma_total FROM carrito_entradas WHERE id_compra = ? AND item_id = ?";
                $stmt_suma_total = $conexion->prepare($query_suma_total);
                $stmt_suma_total->bind_param('ii', $id_compra, $item_id);
                $stmt_suma_total->execute();
                $result_suma_total = $stmt_suma_total->get_result();
                $suma_total_row = $result_suma_total->fetch_assoc();
                $suma_total = $suma_total_row['suma_total'] ?? 0;
                
                // Obtener los valores actuales de solicita y entrega desde orden_compra
                $query_solicita_ordenes = "SELECT solicita, entrega FROM orden_compra WHERE id_compra = ? AND item_id = ? LIMIT 1";
                $stmt_solicita_ordenes = $conexion->prepare($query_solicita_ordenes);
                $stmt_solicita_ordenes->bind_param('ii', $id_compra, $item_id);
                $stmt_solicita_ordenes->execute();
                $result_solicita_ordenes = $stmt_solicita_ordenes->get_result();
                $solicita_ordenes_row = $result_solicita_ordenes->fetch_assoc();
                $cantidad_solicitada_ordenes = $solicita_ordenes_row['solicita'] ?? 0;
                $entrega_actual_ordenes = $solicita_ordenes_row['entrega'] ?? 0;
                
                // Determinar estado basado en la comparación entre solicita y entrega (NO usar suma del carrito)
                if ($entrega_actual_ordenes >= $cantidad_solicitada_ordenes) {
                    // Si entrega coincide o supera lo solicitado: parcial=NO, activo=NO
                    $parcial_estado = 'NO';
                    $activo_estado = 'NO';
                } else {
                    // Si entrega es menor a lo solicitado: parcial=SI, activo=SI
                    $parcial_estado = 'SI';
                    $activo_estado = 'SI';
                }
                
                // *** NUEVA LÓGICA: Verificar si TODOS los items de la orden están completos ***
                // Verificar el estado global de todos los items en la orden
                $query_verificar_orden_completa = "SELECT 
                    COUNT(*) as total_items,
                    COUNT(CASE WHEN entrega >= solicita THEN 1 END) as items_completos
                FROM orden_compra 
                WHERE id_compra = ?";
                
                $stmt_verificar_orden_completa = $conexion->prepare($query_verificar_orden_completa);
                $stmt_verificar_orden_completa->bind_param('i', $id_compra);
                $stmt_verificar_orden_completa->execute();
                $result_verificar_orden_completa = $stmt_verificar_orden_completa->get_result();
                $completitud_row = $result_verificar_orden_completa->fetch_assoc();
                
                $total_items = $completitud_row['total_items'];
                $items_completos = $completitud_row['items_completos'];
                $orden_completamente_entregada = ($total_items > 0 && $total_items == $items_completos);
                
                // Determinar el estatus basado en la completitud de toda la orden
                if ($orden_completamente_entregada) {
                    // Si TODOS los items están completos (entrega = solicita)
                    $estatus_final = 'ENTREGADO';
                    $activo_final = 'NO';
                    $parcial_final = 'NO';
                } else {
                    // Si hay items pendientes (algún entrega != solicita)
                    $estatus_final = 'PARCIAL';
                    $activo_final = 'SI';
                    $parcial_final = 'SI';
                }
                
                // *** ACTUALIZAR ORDENES_COMPRA CON LOS NUEVOS ESTADOS ***
                $query_actualizar_ordenes_compra = "UPDATE ordenes_compra SET 
                    activo = ?, 
                    parcial = ?, 
                    estatus = ? 
                    WHERE id_compra = ?";
                $stmt_actualizar_ordenes_compra = $conexion->prepare($query_actualizar_ordenes_compra);
                
                if (!$stmt_actualizar_ordenes_compra) {
                    echo json_encode(["status" => "error", "message" => "Error al preparar la consulta de actualización de ordenes_compra: " . $conexion->error]);
                    exit;
                }

                $stmt_actualizar_ordenes_compra->bind_param('sssi', $activo_final, $parcial_final, $estatus_final, $id_compra);

                if (!$stmt_actualizar_ordenes_compra->execute()) {
                    echo json_encode(["status" => "error", "message" => "Error en la actualización de ordenes_compra: " . $stmt_actualizar_ordenes_compra->error]);
                    exit;
                }
                
                error_log("DEBUG - Actualizado ordenes_compra para orden {$id_compra}: activo={$activo_final}, parcial={$parcial_final}, estatus={$estatus_final} (todos completos: " . ($orden_completamente_entregada ? 'SI' : 'NO') . ", items completos: {$items_completos}/{$total_items})");
                
                // Actualizar el campo parcial en transacciones para este item_id específico
                // Usar la comparación entre entrega y solicita (NO la suma del carrito)
                $estado_parcial_transacciones = ($entrega_actual_ordenes >= $cantidad_solicitada_ordenes) ? 'NO' : 'SI';
                $query_actualizar_parcial_transacciones = "UPDATE transacciones SET parcial = ? WHERE id_compra = ? AND item_id = ?";
                $stmt_actualizar_parcial_transacciones = $conexion->prepare($query_actualizar_parcial_transacciones);
                if (!$stmt_actualizar_parcial_transacciones) {
                    echo json_encode(["status" => "error", "message" => "Error al preparar la consulta de actualización parcial en transacciones: " . $conexion->error]);
                    exit;
                }

                $stmt_actualizar_parcial_transacciones->bind_param('sii', $estado_parcial_transacciones, $id_compra, $item_id);

                if (!$stmt_actualizar_parcial_transacciones->execute()) {
                    echo json_encode(["status" => "error", "message" => "Error en la actualización de parcial en transacciones: " . $stmt_actualizar_parcial_transacciones->error]);
                    exit;
                }
                
                error_log("DEBUG - Actualizado parcial en transacciones para item_id {$item_id}: {$estado_parcial_transacciones} (entrega: {$entrega_actual_ordenes}, solicita: {$cantidad_solicitada_ordenes})");
            }

            // Preparar mensaje de éxito más informativo
            $mensaje_exito = "Registro actualizado correctamente con valores recalculados.";
            
            // Si se actualizó la cantidad, agregar información adicional
            if (isset($input['entrada_qty']) && isset($diferencia_reducida) && $diferencia_reducida !== null) {
                $direccion_cambio = ($diferencia_reducida > 0) ? "reducida" : "aumentada";
                $valor_absoluto = abs($diferencia_reducida);
                
                // Obtener el nuevo valor de entrega desde la base de datos
                $query_entrega_final = "SELECT entrega FROM orden_compra WHERE id_compra = ? AND item_id = ? LIMIT 1";
                $stmt_entrega_final = $conexion->prepare($query_entrega_final);
                $stmt_entrega_final->bind_param('ii', $id_compra, $item_id);
                $stmt_entrega_final->execute();
                $result_entrega_final = $stmt_entrega_final->get_result();
                $entrega_final_row = $result_entrega_final->fetch_assoc();
                $entrega_final = $entrega_final_row['entrega'] ?? 0;
                
                $entrega_anterior = $entrega_final + $diferencia_reducida; // Calcular el valor anterior
                $mensaje_exito .= " Cantidad {$direccion_cambio} (diferencia: {$valor_absoluto}). Campo entrega actualizado de {$entrega_anterior} a {$entrega_final}.";
                
                // Agregar información sobre el estado de la orden si se actualizó
                if (isset($estatus_final)) {
                    $mensaje_exito .= " Estado de la orden: {$estatus_final}.";
                }
            }

            echo json_encode(["status" => "success", "message" => $mensaje_exito]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error en la actualización: " . $stmt->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No hay campos válidos para actualizar."]);
    }
    
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Error interno del servidor: " . $e->getMessage()]);
    } catch (Error $e) {
        echo json_encode(["status" => "error", "message" => "Error fatal: " . $e->getMessage()]);
    }
    
    exit; // Importante: terminar el script aquí para evitar que se ejecute el HTML
}

// Incluir header después del manejo AJAX
include "../header_farmaciac.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Permitir envío siempre, independientemente del estado parcial
    if (isset($_POST['enviar_datos'])) {
        $id_compra = isset($_POST['id_compra']) ? $_POST['id_compra'] : '';
        $fecha_actual = date('Y-m-d H:i:s');            // Verificar si todos los items están completos (no parciales)
            $query_verificar_parciales = "SELECT 
                item_id,
                SUM(entrada_qty) as total_entregado,
                (SELECT solicita FROM orden_compra WHERE id_compra = ? AND item_id = t.item_id LIMIT 1) as solicitado
                FROM transacciones t 
                WHERE id_compra = ? 
                GROUP BY item_id";
            $stmt_verificar_parciales = $conexion->prepare($query_verificar_parciales);
            $stmt_verificar_parciales->bind_param('ii', $id_compra, $id_compra);
            $stmt_verificar_parciales->execute();
            $result_verificar_parciales = $stmt_verificar_parciales->get_result();
            
            $todos_completos = true;
            while ($item_row = $result_verificar_parciales->fetch_assoc()) {
                if ($item_row['total_entregado'] < $item_row['solicitado']) {
                    $todos_completos = false;
                    break;
                }
            }
            
            // Actualizar el campo parcial en transacciones basado en la validación
            $query_items_carrito = "SELECT DISTINCT item_id FROM carrito_entradas WHERE id_compra = ?";
            $stmt_items_carrito = $conexion->prepare($query_items_carrito);
            $stmt_items_carrito->bind_param('i', $id_compra);
            $stmt_items_carrito->execute();
            $result_items_carrito = $stmt_items_carrito->get_result();

            while ($item_row = $result_items_carrito->fetch_assoc()) {
                $current_item_id = $item_row['item_id'];
                
                // Obtener cantidad solicitada
                $query_solicitada = "SELECT solicita FROM orden_compra WHERE id_compra = ? AND item_id = ? LIMIT 1";
                $stmt_solicitada = $conexion->prepare($query_solicitada);
                $stmt_solicitada->bind_param('ii', $id_compra, $current_item_id);
                $stmt_solicitada->execute();
                $result_solicitada = $stmt_solicitada->get_result();
                $solicitada_row = $result_solicitada->fetch_assoc();
                $cantidad_solicitada = $solicitada_row['solicita'] ?? 0;
                
                // Obtener suma de cantidades entregadas para este item_id
                $query_suma_entregada = "SELECT SUM(entrada_qty) as suma_entregada FROM transacciones WHERE id_compra = ? AND item_id = ?";
                $stmt_suma_entregada = $conexion->prepare($query_suma_entregada);
                $stmt_suma_entregada->bind_param('ii', $id_compra, $current_item_id);
                $stmt_suma_entregada->execute();
                $result_suma_entregada = $stmt_suma_entregada->get_result();
                $suma_entregada_row = $result_suma_entregada->fetch_assoc();
                $suma_entregada = $suma_entregada_row['suma_entregada'] ?? 0;
                
                // Determinar si es parcial
                $es_parcial = ($suma_entregada < $cantidad_solicitada) ? 'SI' : 'NO';
                
                // Actualizar el campo parcial en transacciones
                $query_update_parcial = "UPDATE transacciones SET parcial = ? WHERE id_compra = ? AND item_id = ?";
                $stmt_update_parcial = $conexion->prepare($query_update_parcial);
                $stmt_update_parcial->bind_param('sii', $es_parcial, $id_compra, $current_item_id);
                $stmt_update_parcial->execute();
            }
            
            // 3. Obtener datos de carrito_entradas filtrando por id_compra
            $query_carrito = "SELECT entrada_fecha, id_compra, item_id, entrada_lote, entrada_caducidad, entrada_qty, entrada_unidosis, entrada_costo, ubicacion_id,factura FROM carrito_entradas WHERE id_compra = ?";
            $stmt_carrito = $conexion->prepare($query_carrito);
            $stmt_carrito->bind_param('i', $id_compra);
            $stmt_carrito->execute();
            $result_carrito = $stmt_carrito->get_result();

            if ($result_carrito->num_rows > 0) {
                while ($row = $result_carrito->fetch_assoc()) {
                    // Asigna variables desde carrito_entradas
                    $entrada_fecha =  $fecha_actual;
                    $Entrega_Recibido = $row['entrada_fecha'];
                    $item_id = $row['item_id'];
                    $entrada_lote = $row['entrada_lote'];
                    $entrada_caducidad = $row['entrada_caducidad'];
                    $entrada_qty = $row['entrada_qty'];
                    $entrada_unidosis = $row['entrada_unidosis'];
                    $entrada_costo = $row['entrada_costo'];
                    $ubicacion_id = $row['ubicacion_id'];
                    $factura = $row['factura'];

                
                    $id_usuario = $id_usua; // Usar el usuario de la sesión

                    $query_entrada = "INSERT INTO entradas_almacen (
                        entrada_fecha, id_compra, item_id, entrada_lote, entrada_caducidad, 
                        entrada_qty, entrada_unidosis, entrada_costo, ubicacion_id, 
                        entrada_recibido,factura, id_usua
                    ) VALUES (
                        ?, ?, ?, ?, ?, ?, ?, ?,?, '$Entrega_Recibido',?,?)";

                    $stmt_entrada = $conexion->prepare($query_entrada);

                    // Ajuste de tipos y parámetros para bind_param
                    $stmt_entrada->bind_param(
                        'siissiisisi', // Ocho tipos, uno por cada parámetro que realmente estamos pasando
                        $entrada_fecha,
                        $id_compra,
                        $item_id,
                        $entrada_lote,
                        $entrada_caducidad,
                        $entrada_qty,
                        $entrada_unidosis,
                        $entrada_costo,
                        $ubicacion_id,
                        $factura,
                        $id_usuario
                    );

                    if (!$stmt_entrada->execute()) {
                        die("Error en la inserción en entradas_almacen: " . $stmt_entrada->error);
                    }
                }

                // 5. Actualizar ordenes_compra según el estado de los items
                if ($todos_completos) {
                    // Si todos los items están completos, marcar como completado
                    $query_actualizar_ordenes = "UPDATE ordenes_compra SET 
                        fecha_entrega = ?, 
                        id_usua = ?
                        WHERE id_compra = ?";
                    $stmt_actualizar_ordenes = $conexion->prepare($query_actualizar_ordenes);
                    $stmt_actualizar_ordenes->bind_param(
                        'sii',
                        $fecha_actual,
                        $id_usuario,
                        $id_compra
                    );
                } else {
                    // Si hay items parciales, mantener activo y marcar como parcial
                    $query_actualizar_ordenes = "UPDATE ordenes_compra SET 
                        fecha_entrega = ?, 
                        id_usua = ?
                        WHERE id_compra = ?";
                    $stmt_actualizar_ordenes = $conexion->prepare($query_actualizar_ordenes);
                    $stmt_actualizar_ordenes->bind_param(
                        'sii',
                        $fecha_actual,
                        $id_usuario,
                        $id_compra
                    );
                }

                if (!$stmt_actualizar_ordenes->execute()) {
                    die("Error en la actualización de ordenes_compra: " . $stmt_actualizar_ordenes->error);
                }

                // Actualizar el campo fecha_entrega en la tabla orden_compra para el id_compra específico
                $query_actualizar_orden = "UPDATE orden_compra SET fecha_entrega = ? WHERE id_compra = ?";
                $stmt_actualizar_orden = $conexion->prepare($query_actualizar_orden);
                $stmt_actualizar_orden->bind_param("si", $fecha_actual, $id_compra);

                if (!$stmt_actualizar_orden->execute()) {
                    die("Error al actualizar en orden_compra: " . $stmt_actualizar_orden->error);
                }

                // 6. Borrado selectivo según coincidencia de solicita = entrega en TODA la orden
                // Verificar si TODOS los items en orden_compra tienen solicita = entrega
                $query_verificar_coincidencia = "SELECT 
                    COUNT(*) as total_items,
                    COUNT(CASE WHEN solicita = entrega THEN 1 END) as items_coinciden
                FROM orden_compra 
                WHERE id_compra = ?";
                
                $stmt_verificar_coincidencia = $conexion->prepare($query_verificar_coincidencia);
                $stmt_verificar_coincidencia->bind_param('i', $id_compra);
                $stmt_verificar_coincidencia->execute();
                $result_verificar_coincidencia = $stmt_verificar_coincidencia->get_result();
                $coincidencia_row = $result_verificar_coincidencia->fetch_assoc();
                
                $total_items_orden = $coincidencia_row['total_items'];
                $items_que_coinciden = $coincidencia_row['items_coinciden'];
                $todos_coinciden = ($total_items_orden > 0 && $total_items_orden == $items_que_coinciden);
                
                // SIEMPRE eliminar carrito_entradas
                $query_eliminar_carrito = "DELETE FROM carrito_entradas WHERE id_compra = ?";
                $stmt_eliminar_carrito = $conexion->prepare($query_eliminar_carrito);
                $stmt_eliminar_carrito->bind_param('i', $id_compra);
                $stmt_eliminar_carrito->execute();
                
                // TRANSACCIONES: Solo eliminar si TODOS los items están completos (solicita = entrega)
                if ($todos_coinciden) {
                    $query_eliminar_transacciones = "DELETE FROM transacciones WHERE id_compra = ?";
                    $stmt_eliminar_transacciones = $conexion->prepare($query_eliminar_transacciones);
                    $stmt_eliminar_transacciones->bind_param('i', $id_compra);
                    $stmt_eliminar_transacciones->execute();
                    
                    error_log("BORRADO COMPLETO - Orden {$id_compra}: Todos los items completos ({$items_que_coinciden}/{$total_items_orden}). Eliminadas transacciones y carrito.");
                } else {
                    error_log("BORRADO PARCIAL - Orden {$id_compra}: Items pendientes ({$items_que_coinciden}/{$total_items_orden}). Solo eliminado carrito, transacciones conservadas.");
                }

                // Mostrar mensaje de éxito inmediatamente y luego redirigir
                echo "<script>
                    alert('Los datos se enviaron correctamente a Entradas de Almacén');
                    setTimeout(function() {
                        window.location.href = '" . $_SERVER['PHP_SELF'] . "';
                    }, 1000);
                </script>";
                exit(); // Termina el script
            }
        }
    }


// Muestra el mensaje de error si existe
if (isset($_SESSION['mensaje_error'])) {
    echo "<script>alert('" . $_SESSION['mensaje_error'] . "');</script>";
    unset($_SESSION['mensaje_error']); // Elimina el mensaje después de mostrarlo
}





// Parámetros de configuración de paginación
$registros_por_pagina = 6; // Número de órdenes por página (ajustar aquí para pruebas)
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Verificar si se ha recibido un ID de compra para eliminar
if (isset($_GET['id_compra'])) {
    $id_compra = $_GET['id_compra'];

    // Comenzar la transacción
    $conexion->begin_transaction();

    try {
        // *** PASO 1: OBTENER FACTURA DEL CARRITO ANTES DE ELIMINARLO ***
        $query_obtener_factura_carrito = "SELECT factura FROM carrito_entradas WHERE id_compra = ? LIMIT 1";
        $stmt_obtener_factura_carrito = $conexion->prepare($query_obtener_factura_carrito);
        $stmt_obtener_factura_carrito->bind_param("i", $id_compra);
        $stmt_obtener_factura_carrito->execute();
        $result_factura_carrito = $stmt_obtener_factura_carrito->get_result();
        
        $factura_a_eliminar = '';
        if ($result_factura_carrito->num_rows > 0) {
            $factura_carrito_row = $result_factura_carrito->fetch_assoc();
            $factura_a_eliminar = trim($factura_carrito_row['factura']);
            error_log("ELIMINACIÓN CARRITO - Factura obtenida del carrito: '{$factura_a_eliminar}' para orden {$id_compra}");
        } else {
            error_log("ELIMINACIÓN CARRITO - No se encontró factura en carrito_entradas para orden {$id_compra}");
        }

        // *** PASO 2: RESTAR CANTIDADES DEL CAMPO ENTREGA EN ORDEN_COMPRA ***
        // Obtener todas las cantidades por item_id desde carrito_entradas antes de eliminar
        $query_obtener_cantidades = "SELECT item_id, SUM(entrada_qty) as cantidad_total 
                                     FROM carrito_entradas 
                                     WHERE id_compra = ? 
                                     GROUP BY item_id";
        $stmt_obtener_cantidades = $conexion->prepare($query_obtener_cantidades);
        $stmt_obtener_cantidades->bind_param("i", $id_compra);
        $stmt_obtener_cantidades->execute();
        $result_cantidades = $stmt_obtener_cantidades->get_result();
        
        // Restar cada cantidad del campo entrega correspondiente
        while ($row_cantidad = $result_cantidades->fetch_assoc()) {
            $item_id = $row_cantidad['item_id'];
            $cantidad_a_restar = $row_cantidad['cantidad_total'];
            
            // Actualizar el campo entrega restando la cantidad eliminada
            $query_restar_entrega = "UPDATE orden_compra 
                                    SET entrega = GREATEST(entrega - ?, 0) 
                                    WHERE id_compra = ? AND item_id = ?";
            $stmt_restar_entrega = $conexion->prepare($query_restar_entrega);
            $stmt_restar_entrega->bind_param("iii", $cantidad_a_restar, $id_compra, $item_id);
            $stmt_restar_entrega->execute();
            
            error_log("ELIMINACIÓN CARRITO - Restando {$cantidad_a_restar} del campo entrega para item_id {$item_id} en orden {$id_compra}");
        }

        // *** PASO 3: ELIMINAR REGISTROS DEL CARRITO ***
        // Eliminar de carrito_entradas
        $sql1 = "DELETE FROM carrito_entradas WHERE id_compra = ?";
        $stmt1 = $conexion->prepare($sql1);
        $stmt1->bind_param("i", $id_compra);
        $stmt1->execute();

        // Eliminar de transacciones
        $sql2 = "DELETE FROM transacciones WHERE id_compra = ?";
        $stmt2 = $conexion->prepare($sql2);
        $stmt2->bind_param("i", $id_compra);
        $stmt2->execute();

        // *** PASO 4: EVALUAR ESTADO DE LOS ITEMS Y ACTUALIZAR ORDENES_COMPRA ***
        // Verificar el estado de todos los items después de la eliminación
        $query_evaluar_estado = "SELECT 
            COUNT(*) as total_items,
            COUNT(CASE WHEN solicita != entrega AND entrega > 0 THEN 1 END) as items_parciales,
            COUNT(CASE WHEN entrega = 0 THEN 1 END) as items_sin_entrega,
            COUNT(CASE WHEN solicita = entrega AND entrega > 0 THEN 1 END) as items_completos
        FROM orden_compra 
        WHERE id_compra = ?";
        
        $stmt_evaluar_estado = $conexion->prepare($query_evaluar_estado);
        $stmt_evaluar_estado->bind_param("i", $id_compra);
        $stmt_evaluar_estado->execute();
        $result_evaluar_estado = $stmt_evaluar_estado->get_result();
        $estado_row = $result_evaluar_estado->fetch_assoc();
        
        $total_items = $estado_row['total_items'];
        $items_parciales = $estado_row['items_parciales'];
        $items_sin_entrega = $estado_row['items_sin_entrega'];
        $items_completos = $estado_row['items_completos'];
        
        // Determinar el estado final basado en las reglas especificadas
        if ($items_parciales > 0 || $items_completos > 0) {
            // Si hay items parciales o completos (algún item tiene entrega > 0 y diferente de solicita)
            $parcial_final = 'SI';
            $activo_final = 'SI';
            $estatus_final = 'PARCIAL';
            $tipo_estado = "PARCIAL (hay items con entregas pendientes)";
        } elseif ($items_sin_entrega == $total_items) {
            // Si TODOS los items quedaron con entrega = 0
            $parcial_final = ''; // VACÍO según nueva especificación
            $activo_final = 'SI';
            $estatus_final = 'AUTORIZADO';
            $tipo_estado = "AUTORIZADO (todos los items sin entrega)";
        } else {
            // Caso por defecto (no debería ocurrir, pero como respaldo)
            $parcial_final = 'SI';
            $activo_final = 'SI';
            $estatus_final = 'PARCIAL';
            $tipo_estado = "PARCIAL (estado por defecto)";
        }
        
        error_log("EVALUACIÓN DE ESTADO POST-ELIMINACIÓN - Orden {$id_compra}: Total items: {$total_items}, Parciales: {$items_parciales}, Sin entrega: {$items_sin_entrega}, Completos: {$items_completos}");

        // *** PASO 5: ACTUALIZAR CAMPO FACTURA EN ORDENES_COMPRA ***
        // Usar la factura obtenida anteriormente
        if (!empty($factura_a_eliminar)) {
            // Obtener las facturas actuales de ordenes_compra
            $query_facturas_actuales = "SELECT factura FROM ordenes_compra WHERE id_compra = ? LIMIT 1";
            $stmt_facturas_actuales = $conexion->prepare($query_facturas_actuales);
            $stmt_facturas_actuales->bind_param("i", $id_compra);
            $stmt_facturas_actuales->execute();
            $result_facturas_actuales = $stmt_facturas_actuales->get_result();
            
            if ($result_facturas_actuales->num_rows > 0) {
                $facturas_actuales_row = $result_facturas_actuales->fetch_assoc();
                $facturas_actuales = $facturas_actuales_row['factura'];
                
                // Procesar la eliminación de factura considerando múltiples facturas separadas por comas
                if (!empty($facturas_actuales)) {
                    // Convertir a array, eliminar espacios y filtrar vacíos
                    $array_facturas = array_filter(array_map('trim', explode(',', $facturas_actuales)));
                    
                    error_log("ELIMINACIÓN FACTURA - Facturas actuales en ordenes_compra: " . print_r($array_facturas, true));
                    error_log("ELIMINACIÓN FACTURA - Factura a eliminar: '{$factura_a_eliminar}'");
                    
                    // Eliminar la factura específica del array
                    $array_facturas_filtrado = array_filter($array_facturas, function($factura) use ($factura_a_eliminar) {
                        return $factura !== $factura_a_eliminar;
                    });
                    
                    // Reconstruir el string de facturas
                    $nuevas_facturas = implode(',', $array_facturas_filtrado);
                    
                    // Actualizar el campo factura en ordenes_compra
                    $query_actualizar_factura = "UPDATE ordenes_compra SET factura = ? WHERE id_compra = ?";
                    $stmt_actualizar_factura = $conexion->prepare($query_actualizar_factura);
                    $stmt_actualizar_factura->bind_param("si", $nuevas_facturas, $id_compra);
                    
                    if ($stmt_actualizar_factura->execute()) {
                        if ($items_sin_entrega == $total_items) {
                            error_log("ACTUALIZACIÓN FACTURA COMPLETA - Orden {$id_compra}: Todos los items en entrega=0. Factura '{$factura_a_eliminar}' eliminada. Facturas anteriores: '{$facturas_actuales}' → Facturas actuales: '{$nuevas_facturas}'");
                        } else {
                            error_log("ACTUALIZACIÓN FACTURA PARCIAL - Orden {$id_compra}: Factura '{$factura_a_eliminar}' eliminada del carrito. Facturas anteriores: '{$facturas_actuales}' → Facturas actuales: '{$nuevas_facturas}'");
                        }
                    } else {
                        error_log("ERROR ACTUALIZACIÓN FACTURA - Orden {$id_compra}: No se pudo actualizar el campo factura: " . $stmt_actualizar_factura->error);
                    }
                } else {
                    error_log("ACTUALIZACIÓN FACTURA - Orden {$id_compra}: Campo factura en ordenes_compra está vacío");
                }
            } else {
                error_log("ACTUALIZACIÓN FACTURA - Orden {$id_compra}: No se encontró registro en ordenes_compra");
            }
        } else {
            error_log("ACTUALIZACIÓN FACTURA - Orden {$id_compra}: No hay factura para eliminar (factura del carrito estaba vacía)");
        }

        // Confirmar la transacción
        $conexion->commit();
        
        error_log("ELIMINACIÓN CARRITO COMPLETA - Orden {$id_compra}: Cantidades restadas del campo entrega, registros eliminados y orden reactivada");

        // Redirigir con JavaScript para mejor compatibilidad
        echo "<script>
            alert('Carrito eliminado correctamente. Las cantidades han sido restadas del campo entrega.');
            window.location.href = '" . $_SERVER['PHP_SELF'] . "';
        </script>";
        exit();
    } catch (Exception $e) {
        // Si hay un error, revertir la transacción
        $conexion->rollback();
        error_log("ERROR EN ELIMINACIÓN DE CARRITO: " . $e->getMessage());
        echo "<script>
            alert('Error al eliminar la compra: " . addslashes($e->getMessage()) . "');
            window.location.href = '" . $_SERVER['PHP_SELF'] . "';
        </script>";
        exit();
    }
}
// Consulta para obtener las ubicaciones desde la base de datos
$ubicaciones = [];
$query = "SELECT ubicacion_id, nombre_ubicacion FROM ubicaciones_almacen";
$result = mysqli_query($conexion, $query); // Asegúrate de que $conexion esté correctamente configurado

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $ubicaciones[] = $row; // Guardar cada fila de resultado en el array $ubicaciones
    }
} else {
    echo "Error al obtener las ubicaciones: " . mysqli_error($conexion);
}

// Contar el número total de órdenes para la paginación
$query_total = "SELECT COUNT(DISTINCT id_compra) AS total FROM transacciones";
$result_total = $conexion->query($query_total);
$total_registros = $result_total->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Consulta para obtener los IDs de las órdenes con paginación
$query_ordenes = "SELECT DISTINCT id_compra FROM transacciones LIMIT $offset, $registros_por_pagina";
$result_ordenes = $conexion->query($query_ordenes);
echo "<a href='entradas.php'  style='color: white;  margin-left: 20px;background-color: #d9534f; border: none; border-radius: 5px; padding: 5px 10px; cursor: pointer;'>Regresar</a>";

// Verificar si hay órdenes
if ($result_ordenes->num_rows > 0) {
    echo "<div style='background-color: white; padding: 50px; border-radius: 30px;margin-top: 15px;'>";

    echo "<h2 style='margin-top: 40px;color: #2b2d7f;text-align: center;'>Carrito de Compras</h2>";
    echo "<div style='display: flex; flex-direction: column;'>"; // Cambié a flexbox para un mejor alineamiento vertical

    while ($row_orden = $result_ordenes->fetch_assoc()) {
        $id_compra = $row_orden['id_compra'];

        // Consulta para obtener todos los ítems de la orden actual (incluyendo el ID único de carrito_entradas)
        // Primero verificar si existen registros en transacciones para mostrar información de resumen
        $query_info_resumen = "SELECT 
            COUNT(*) as total_registros,
            SUM(entrada_qty * entrada_costo) as monto_bruto,
            SUM(entrada_descuento) as descuento_total,
            SUM(entrada_qty * entrada_costo * entrada_iva) as iva_calculado_total
        FROM transacciones WHERE id_compra = ?";
        
        $stmt_info_resumen = $conexion->prepare($query_info_resumen);
        $stmt_info_resumen->bind_param("i", $id_compra);
        $stmt_info_resumen->execute();
        $result_info_resumen = $stmt_info_resumen->get_result();
        $info_resumen = $result_info_resumen->fetch_assoc();
        
        $monto_bruto = $info_resumen['monto_bruto'] ?? 0;
        $descuento_total = $info_resumen['descuento_total'] ?? 0;
        $iva_calculado_total = $info_resumen['iva_calculado_total'] ?? 0;
        $monto_neto = $monto_bruto - $descuento_total; // Subtotal después de descuentos
        $total_final = $monto_neto + $iva_calculado_total; // Total final: neto + IVA
        
        $query_items = "SELECT 
        ce.`id` as carrito_id,
        ce.`entrada_fecha`, 
        ce.`item_id`, 
        CONCAT(ia.item_name, ' ', ia.item_grams) AS item_completo,
        ce.`entrada_lote`, 
        ce.`entrada_caducidad`, 
        ce.`entrada_qty`, 
        ce.`entrada_unidosis`, 
        ce.`entrada_iva`, 
        ce.`entrada_descuento`, 
        ce.`Total`, 
        ce.`entrada_costo`, 
        ce.`ubicacion_id`, 
        ia.`factor`, 
        oc.`solicita`
    FROM 
        `carrito_entradas` AS ce
    JOIN 
        `item_almacen` AS ia ON ce.`item_id` = ia.`item_id`
    LEFT JOIN 
        `orden_compra` AS oc ON ce.`id_compra` = oc.`id_compra` AND ce.`item_id` = oc.`item_id`
    WHERE 
        ce.`id_compra` = ?
    ORDER BY 
        ce.`item_id`, ce.`entrada_lote`";



        $stmt_items = $conexion->prepare($query_items);
        $stmt_items->bind_param("i", $id_compra);
        $stmt_items->execute();
        $result_items = $stmt_items->get_result();

        // Verificar si hay resultados antes de mostrar la orden
        if ($result_items->num_rows > 0) {
            // Obtener el primer registro para mostrar los detalles de la orden
            $first_row = $result_items->fetch_assoc();
            
            // Consultar si la orden es parcial
            $query_parcial = "SELECT parcial FROM ordenes_compra WHERE id_compra = ? LIMIT 1";
            $stmt_parcial = $conexion->prepare($query_parcial);
            $stmt_parcial->bind_param("i", $id_compra);
            $stmt_parcial->execute();
            $result_parcial = $stmt_parcial->get_result();
            $parcial_row = $result_parcial->fetch_assoc();
            $es_parcial = ($parcial_row['parcial'] ?? '') === 'SI';
            
            // Obtener la factura de ordenes_compra
            $query_factura = "SELECT factura FROM ordenes_compra WHERE id_compra = ? LIMIT 1";
            $stmt_factura = $conexion->prepare($query_factura);
            $stmt_factura->bind_param("i", $id_compra);
            $stmt_factura->execute();
            $result_factura = $stmt_factura->get_result();
            $factura_row = $result_factura->fetch_assoc();
            $factura_numero = $factura_row['factura'] ?? ''; // Usar solo la factura de la BD
            
            // Mostrar información de la orden usando los datos calculados
            echo "<div class='compra-item'>
            <h3 onclick='toggleDetails(\"compra_{$id_compra}\")' style='cursor: pointer; color: #007bff;'>
                Compra ID: {$id_compra} - Click para ver detalles";
            
            // Mostrar etiqueta de PARCIAL si corresponde
            if ($es_parcial) {
                echo " <span class='status-label status-parcial'>PARCIAL</span>";
            }
            
            echo "</h3>
            <p>
                <strong>Factura:</strong> {$factura_numero} | 
                <strong>Monto Bruto:</strong> \$" . number_format($monto_bruto, 2) . " | 
                <strong>Descuento Total:</strong> \$" . number_format($descuento_total, 2) . " | 
                <strong>Monto Neto:</strong> \$" . number_format($monto_neto, 2) . " | 
                <strong>IVA Calculado:</strong> \$" . number_format($iva_calculado_total, 2) . " | 
                <strong>Total Final:</strong> \$" . number_format($total_final, 2) . " | 
                <strong>ID Usuario:</strong> {$id_usua}
            </p>
            <button onclick='confirmDelete($id_compra)' style='color: white; background-color: #d9534f; border: none; border-radius: 5px; padding: 5px 10px; cursor: pointer;'>Eliminar</button>

            <div id='compra_{$id_compra}' class='compra-details' style='display: none;'>
            <form id='formActualizar_{$id_compra}' method='POST' >
                        <div class='table-responsive'>
                    <table class='carrito-table'>
                        <thead>
                            <tr>
                                <th>Fecha Entrada</th>
                                <th>Ítem</th>
                                <th>Lote</th>
                                <th>Caducidad</th>
                                <th>Solicitado</th>
                                <th>Cantidad</th>
                                <th>Factor</th>
                                <th>Unidosis</th>
                                <th>Costo Unitario</th>
                               <th>Costo*Cantidad</th>
                                <th>Ubicación</th>
                                <th>IVA (Tasa)</th> 
                                <th>IVA Calculado</th>
                                 <th>Descuento</th> 
                                <th>Costo Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>";

            // Reiniciar el puntero del resultado para mostrar TODOS los registros
            $result_items->data_seek(0);
            
            while ($item_row = $result_items->fetch_assoc()) {
            $unique_id = $item_row['carrito_id']; // Usar el ID único de carrito_entradas
            $costoTotalItem = $item_row['entrada_qty'] * $item_row['entrada_costo'];
            $factor = isset($item_row['factor']) ? $item_row['factor'] : 1;
            $Totales = isset($item_row['Total']) ? $item_row['Total'] : 0;
            $Ivas = isset($item_row['entrada_iva']) ? $item_row['entrada_iva'] : 0;
            $Descuentos = isset($item_row['entrada_descuento']) ? $item_row['entrada_descuento'] : 0;
            $solicita = isset($item_row['solicita']) ? $item_row['solicita'] : 0;
            
            // Verificar si el item está completo (solicita = entrega en orden_compra)
            $query_verificar_completo = "SELECT solicita, entrega FROM orden_compra WHERE id_compra = ? AND item_id = ? LIMIT 1";
            $stmt_verificar_completo = $conexion->prepare($query_verificar_completo);
            $stmt_verificar_completo->bind_param('ii', $id_compra, $item_row['item_id']);
            $stmt_verificar_completo->execute();
            $result_verificar_completo = $stmt_verificar_completo->get_result();
            $completo_row = $result_verificar_completo->fetch_assoc();
            $item_solicita = $completo_row['solicita'] ?? 0;
            $item_entrega = $completo_row['entrega'] ?? 0;
            $item_completo = ($item_solicita > 0 && $item_solicita == $item_entrega);
            
            // Calcular IVA sobre el subtotal ANTES del descuento
            $iva_calculado = $costoTotalItem * $Ivas;

            echo "<tr style='height: 50px;'" . ($item_completo ? " data-item-completo='true'" : " data-item-completo='false'") . ">
    <td>{$item_row['entrada_fecha']}</td>
    <td>{$item_row['item_completo']}" . ($item_completo ? " <span style='color: green; font-weight: bold;'>[COMPLETO]</span>" : "") . "</td>
    <td><input type='text' id='lote_{$unique_id}' value='{$item_row['entrada_lote']}' data-original='{$item_row['entrada_lote']}' style='width: 80px;'></td>
    <td><input type='date' id='caducidad_{$unique_id}' value='{$item_row['entrada_caducidad']}' data-original='{$item_row['entrada_caducidad']}' style='width: 100px;'></td>
    <td><input type='number' id='solicitado_{$unique_id}' value='{$solicita}' style='width: 70px;' readonly></td>
    <td><input type='number' id='cantidad_{$unique_id}' value='{$item_row['entrada_qty']}' data-original='{$item_row['entrada_qty']}' data-item-id='{$item_row['item_id']}' data-item-completo='{$item_completo}' style='width: 70px;' onchange='validarCantidadCompleta(\"{$unique_id}\")'></td>
    <td><input type='number' id='factor_{$unique_id}' name='factor[]' value='{$factor}' readonly style='width: 50px;'></td>
    <td><input type='number' id='unidosis_{$unique_id}' value='{$item_row['entrada_unidosis']}' style='width: 80px;'></td>
    <td><input type='number' id='costo_{$unique_id}' value='{$item_row['entrada_costo']}' data-original='{$item_row['entrada_costo']}' style='width: 80px;'></td>
    <td id='costo_cantidad_{$unique_id}'>\$" . number_format($costoTotalItem, 2) . "</td>
    <td>
        <select id='ubicacion_{$unique_id}' data-original='{$item_row['ubicacion_id']}' style='width: 100px;'>";

            foreach ($ubicaciones as $ubicacion) {
                $selected = ($ubicacion['ubicacion_id'] == $item_row['ubicacion_id']) ? 'selected' : '';
                echo "<option value='{$ubicacion['ubicacion_id']}' $selected>{$ubicacion['nombre_ubicacion']}</option>";
            }

            echo "</select>
    </td>
    <td><input type='number' id='iva_{$unique_id}' name='iva[]' value='{$Ivas}' data-original='{$Ivas}' style='width: 80px;' step='0.01'></td>
    <td id='iva_calculado_{$unique_id}' style='font-weight: bold; color: #2b2d7f;'>\$" . number_format($iva_calculado, 2) . "</td>
    <td><input type='number' id='descuento_{$unique_id}' name='descuento[]' value='{$Descuentos}' data-original='{$Descuentos}' style='width: 80px;'></td> 
    <td><input type='number' id='costo_total_{$unique_id}' name='costo_total[]' value='{$Totales}' style='width: 95px;'></td> 
    <td>
        <button type='button' id='btnCalcular_{$unique_id}' style='color: white; background-color: #2b2d7f; border: none; border-radius: 5px; padding: 5px 10px; cursor: pointer;' onclick='calcularTotales(\"{$unique_id}\", \"{$item_row['item_id']}\")' disabled>Calcular</button>
        <button type='button' id='btnActualizar_{$unique_id}' style='color: white; background-color: #2b2d7f; border: none; border-radius: 5px; padding: 5px 10px; cursor: pointer;' onclick='updateItemWithAlert(\"{$unique_id}\", \"{$item_row['item_id']}\", \"formActualizar_{$id_compra}\")' disabled>Actualizar</button>
    </td>
</tr>";
            }
            echo "</tbody>
    <tfoot>
        <tr>
            <td colspan='10'></td> <!-- Espacios hasta la columna de IVA Calculado -->
            <td colspan='3' style='text-align: right; padding-top: 15px;'>
         <input type='hidden' name='id_compra' value='{$id_compra}'> 
        <button type='submit' name='enviar_datos' style='color: white; background-color: #2b2d7f; border: none; border-radius: 5px; padding: 5px 10px; cursor: pointer;'>Enviar a Entradas</button>
                      </td>
                        </tr>
                    </tfoot>
                </table>
            </div></form></div></div>";
        } else {
            echo "<div class='compra-item'>
            <h3>Compra ID: {$id_compra} - Sin elementos</h3>
            <p>No se encontraron elementos para esta compra.</p>
            </div>";
        }
    }

    echo "</div>"; // Cierra el contenedor de órdenes


    // Navegación de páginas
    echo "<div style='text-align: center; margin-top: 20px;'>";

    // Botón de página anterior
    if ($pagina_actual > 1) {
        $pagina_anterior = $pagina_actual - 1;
        echo "<a href='?pagina=$pagina_anterior' style='margin-right: 10px;'>Página Anterior</a>";
    }

    // Mostrar los enlaces de número de página
    for ($i = 1; $i <= $total_paginas; $i++) {
        if ($i == $pagina_actual) {
            echo "<strong style='margin: 0 5px;'>$i</strong>";
        } else {
            echo "<a href='?pagina=$i' style='margin: 0 5px;'>$i</a>";
        }
    }

    // Botón de página siguiente
    if ($pagina_actual < $total_paginas) {
        $pagina_siguiente = $pagina_actual + 1;
        echo "<a href='?pagina=$pagina_siguiente' style='margin-left: 10px;'>Página Siguiente</a>";
    }

    echo "</div>";
} else {
    // Mensaje cuando no hay registros
    echo "<div style='display: flex; justify-content: center; align-items: center; height: 100vh;'>
    <p style='color: #2b2d7f; font-size: 50px; text-align: center;'>No hay registros en el carrito de compras.</p>
          </div>";
    echo "</div>"; // Cerrar el contenedor

}
// Cierra el contenedor principal
echo "</div>";
?>

<script>
    function toggleDetails(id) {
        var element = document.getElementById(id);
        if (element.style.display === "none") {
            element.style.display = "block";
        } else {
            element.style.display = "none";
        }
    }

    function confirmDelete(id_compra) {
        if (confirm("¿Estás seguro de que deseas eliminar esta compra?")) {
            window.location.href = "<?php echo $_SERVER['PHP_SELF']; ?>?id_compra=" + id_compra;
        }
    }
</script>
<script>
/*
=== NUEVO FLUJO DE RECÁLCULO LOCAL ===

El sistema ahora funciona en dos pasos separados:

1. CALCULAR (JavaScript Local):
   - Se ejecuta cuando el usuario presiona "Calcular"
   - Recalcula todos los valores (unidosis, totales, IVA, descuentos) de manera LOCAL en JavaScript
   - Solo actualiza la interfaz (campos visibles) - NO envía datos al servidor
   - Marca los campos como calculados y habilita el botón "Actualizar"

2. ACTUALIZAR (Envío al Servidor):
   - Se ejecuta cuando el usuario presiona "Actualizar"
   - Envía TODOS los valores recalculados (incluidos los calculados localmente) al servidor
   - Actualiza tanto carrito_entradas como transacciones con los valores recalculados
   - Recarga la página para mostrar los cambios guardados

Este enfoque permite al usuario:
- Ver los resultados del cálculo inmediatamente sin esperar al servidor
- Revisar los valores calculados antes de guardarlos
- Tener control total sobre cuándo se guardan los cambios
*/

    function updateItem(uniqueId, itemId, formId) {
        const form = document.getElementById(formId);
        if (!form) {
            console.error(`Formulario con ID ${formId} no encontrado.`);
            return;
        }

        // Capturar valores de los inputs cuando se presiona el botón de actualizar
        // ESTOS VALORES YA INCLUYEN LOS CÁLCULOS LOCALES DE JAVASCRIPT
        const lote = document.getElementById(`lote_${uniqueId}`).value;
        const caducidad = document.getElementById(`caducidad_${uniqueId}`).value;
        const cantidad = parseFloat(document.getElementById(`cantidad_${uniqueId}`).value) || 0;
        const unidosis = document.getElementById(`unidosis_${uniqueId}`).value;
        const costo = document.getElementById(`costo_${uniqueId}`).value;
        const ubicacion = document.getElementById(`ubicacion_${uniqueId}`).value;
        const ivaa = document.getElementById(`iva_${uniqueId}`).value;
        const descuentoo = document.getElementById(`descuento_${uniqueId}`).value;
        const costo_total = document.getElementById(`costo_total_${uniqueId}`).value;
        
        // Nueva validación para items completos
        const cantidadInput = document.getElementById(`cantidad_${uniqueId}`);
        const itemCompleto = cantidadInput.getAttribute('data-item-completo') === 'true';
        const cantidadOriginal = parseFloat(cantidadInput.getAttribute('data-original')) || 0;
        
        if (itemCompleto && cantidad > cantidadOriginal) {
            alert(`Error: Este ítem está completo (solicita = entrega). Solo se permite reducir la cantidad.\nCantidad actual: ${cantidadOriginal}\nCantidad ingresada: ${cantidad}\nCantidad máxima permitida: ${cantidadOriginal}`);
            return;
        }
        
        // Validación para cantidad: verificar que la suma no exceda lo solicitado
        if (document.getElementById(`cantidad_${uniqueId}`).dataset.original !== cantidad.toString()) {
            const cantidadSolicitada = parseFloat(document.getElementById(`solicitado_${uniqueId}`).value) || 0;
            
            // Obtener todas las cantidades del mismo item_id (excluyendo el registro actual)
            const filas = document.querySelectorAll(`tr`);
            let sumaOtrasCantidades = 0;
            
            filas.forEach(fila => {
                const cantidadInput = fila.querySelector(`input[id^="cantidad_"]`);
                const solicitadoInput = fila.querySelector(`input[id^="solicitado_"]`);
                const filaUniqueId = cantidadInput ? cantidadInput.id.split('_')[1] : null;
                
                if (cantidadInput && solicitadoInput && filaUniqueId !== uniqueId) {
                    const solicitadoFila = parseFloat(solicitadoInput.value) || 0;
                    // Solo sumar si es el mismo item (mismo valor solicitado)
                    if (solicitadoFila === cantidadSolicitada) {
                        sumaOtrasCantidades += parseFloat(cantidadInput.value) || 0;
                    }
                }
            });
            
            const nuevaSumaTotal = sumaOtrasCantidades + cantidad;
            if (nuevaSumaTotal > cantidadSolicitada) {
                alert(`Error: La suma total de cantidades (${nuevaSumaTotal}) excede lo solicitado (${cantidadSolicitada}) para este ítem. Cantidad máxima permitida: ${cantidadSolicitada - sumaOtrasCantidades}`);
                return;
            }
        }
        
        // Preparar datos para enviar a la base de datos
        // INCLUYE TODOS LOS VALORES RECALCULADOS LOCALMENTE
        let data = {
            action: 'update_item',
            carrito_id: uniqueId, // Usar el ID único de carrito_entradas
            item_id: itemId,
            id_compra: formId.split('_')[1],
            entrada_lote: lote,
            entrada_caducidad: caducidad,
            entrada_qty: cantidad,
            entrada_unidosis: unidosis,
            entrada_costo: costo,
            ubicacion_id: ubicacion,
            entrada_iva: ivaa,
            entrada_descuento: descuentoo,
            Total: costo_total
        };

        console.log("Enviando datos de actualización con valores recalculados:", data);

        // Hacer la solicitud de actualización
        fetch("carrito_entradas.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                console.log("Status de respuesta ACTUALIZAR:", response.status);
                
                // Verificar si la respuesta es exitosa
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                // Verificar si el contenido es JSON
                const contentType = response.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                    return response.text().then(text => {
                        console.error("Respuesta ACTUALIZAR no es JSON:", text.substring(0, 500));
                        throw new Error("La respuesta no es JSON válido: " + text.substring(0, 100));
                    });
                }
                
                return response.json();
            })
            .then(result => {
                console.log("Resultado ACTUALIZAR procesado:", result);
                
                if (result.status === "success") {
                    // Resetear el estado de los botones después de la actualización exitosa
                    const btnCalcular = document.getElementById(`btnCalcular_${uniqueId}`);
                    const btnActualizar = document.getElementById(`btnActualizar_${uniqueId}`);
                    
                    // Resetear botones a estado inicial
                    btnCalcular.disabled = true;
                    btnCalcular.textContent = "Calcular";
                    btnCalcular.style.backgroundColor = "#2b2d7f";
                    
                    btnActualizar.disabled = true;
                    btnActualizar.style.backgroundColor = "#ccc";
                    
                    // Limpiar marcadores de cálculo local
                    document.getElementById(`costo_total_${uniqueId}`).removeAttribute('data-calculado-local');
                    document.getElementById(`unidosis_${uniqueId}`).removeAttribute('data-calculado-local');
                    
                    alert("Actualización exitosa.");
                    // Recargar la página para mostrar los cambios
                    window.location.reload();
                } else {
                    alert("Error en la actualización: " + result.message);
                }
            })
            .catch(error => {
                console.error("Error completo:", error);
                console.error("Tipo de error:", error.name);
                console.error("Mensaje de error:", error.message);
                alert("Error de conexión: " + error.message);
            });

    }

    // Función para calcular totales localmente (solo JavaScript)
    function calcularTotales(uniqueId, itemId) {
        // Obtener la fila correspondiente al unique_id
        const row = document.querySelector(`#costo_total_${uniqueId}`).closest('tr');

        // Obtener elementos relevantes de la fila
        const cantidad = parseFloat(row.querySelector(`#cantidad_${uniqueId}`).value) || 0;
        const costoUnitario = parseFloat(row.querySelector(`#costo_${uniqueId}`).value) || 0;
        const ivaRate = parseFloat(row.querySelector(`#iva_${uniqueId}`).value) || 0;
        const descuentoMonto = parseFloat(row.querySelector(`#descuento_${uniqueId}`).value) || 0;
        const factor = parseFloat(row.querySelector(`#factor_${uniqueId}`).value) || 1;

        // Verificar si se han realizado cambios relevantes
        if (cantidad > 0 && costoUnitario > 0) {
            // CÁLCULO LOCAL EN JAVASCRIPT - NO SE ENVÍA A LA BASE DE DATOS AÚN
            
            // Calcular Costo * Cantidad (subtotal antes de descuentos e IVA)
            const subtotalSinDescuento = cantidad * costoUnitario;
            row.querySelector(`#costo_cantidad_${uniqueId}`).textContent = `$${subtotalSinDescuento.toFixed(2)}`;

            // Aplicar descuento al subtotal
            const subtotalConDescuento = subtotalSinDescuento - descuentoMonto;

            // Calcular IVA sobre el subtotal ANTES del descuento
            const montoIva = subtotalSinDescuento * ivaRate;
            
            // Actualizar IVA calculado en la tabla
            const ivaCalculadoCell = row.querySelector(`#iva_calculado_${uniqueId}`);
            if (ivaCalculadoCell) {
                ivaCalculadoCell.innerHTML = `$${montoIva.toFixed(2)}`;
            }

            // Calcular costo total final (subtotal - descuento + IVA)
            const costoTotalFinal = subtotalConDescuento + montoIva;

            // Calcular unidosis (cantidad * factor)
            const unidosis = cantidad * factor;

            // ACTUALIZAR SOLO LA INTERFAZ (CAMPOS VISIBLES)
            row.querySelector(`#costo_total_${uniqueId}`).value = costoTotalFinal.toFixed(2);

            // Actualizar el campo de unidosis
            const unidosisField = row.querySelector(`#unidosis_${uniqueId}`);
            if (unidosisField) {
                unidosisField.value = unidosis.toFixed(0);
            }

            // Marcar los campos como calculados localmente
            row.querySelector(`#costo_total_${uniqueId}`).setAttribute('data-calculado-local', 'true');
            row.querySelector(`#unidosis_${uniqueId}`).setAttribute('data-calculado-local', 'true');

            // Cambiar estado de los botones después del cálculo local
            const btnCalcular = document.getElementById(`btnCalcular_${uniqueId}`);
            const btnActualizar = document.getElementById(`btnActualizar_${uniqueId}`);
            
            // Marcar calcular como completado y habilitar actualizar
            btnCalcular.disabled = true;
            btnCalcular.textContent = "Calculado";
            btnCalcular.style.backgroundColor = "#4CAF50"; // Color verde para indicar éxito
            
            // Habilitar el botón actualizar
            btnActualizar.disabled = false;
            btnActualizar.style.backgroundColor = "#2b2d7f";
            
            console.log(`Cálculo LOCAL para registro ${uniqueId}:`, {
                cantidad,
                costoUnitario,
                subtotalSinDescuento,
                descuentoMonto,
                subtotalConDescuento,
                ivaRate,
                montoIva,
                costoTotalFinal,
                unidosis
            });

                   
        } else {
            alert("Debes ingresar cantidad y costo unitario válidos para calcular.");
        }
    }
    // Nueva función para validar cantidad cuando el item está completo
    function validarCantidadCompleta(uniqueId) {
        const cantidadInput = document.getElementById(`cantidad_${uniqueId}`);
        const itemCompleto = cantidadInput.getAttribute('data-item-completo') === 'true';
        
        if (itemCompleto) {
            const cantidadOriginal = parseFloat(cantidadInput.getAttribute('data-original')) || 0;
            const cantidadActual = parseFloat(cantidadInput.value) || 0;
            
            // Solo permitir reducir la cantidad si el item está completo
            if (cantidadActual > cantidadOriginal) {
                alert(`Este ítem ya está completo (solicita = entrega). Solo se permite reducir la cantidad.\n\nCantidad original: ${cantidadOriginal}\nCantidad ingresada: ${cantidadActual}\nCantidad máxima permitida: ${cantidadOriginal}`);
                cantidadInput.value = cantidadOriginal; // Restaurar valor original
                return false;
            }
        }
        
        // NUEVA LÓGICA: Mostrar información para cualquier cambio de cantidad
        const cantidadOriginal = parseFloat(cantidadInput.getAttribute('data-original')) || 0;
        const cantidadActual = parseFloat(cantidadInput.value) || 0;
        
        if (cantidadActual !== cantidadOriginal) {
            const diferencia = cantidadOriginal - cantidadActual;
            const direccion = (diferencia > 0) ? "reducción" : "aumento";
            const valorAbsoluto = Math.abs(diferencia);
            
            console.log(`Cambio de cantidad detectado: ${direccion} de ${valorAbsoluto} unidades (de ${cantidadOriginal} a ${cantidadActual}). Esta diferencia se aplicará al campo entrega en la base de datos.`);
        }
        
        // Llamar a la función de detección de cambios normal
        detectarCambios(uniqueId);
        return true;
    }

    // Modificar detectarCambios para considerar items completos
    function detectarCambios(uniqueId) {
        // Verificar si el item está completo
        const cantidadInput = document.getElementById(`cantidad_${uniqueId}`);
        const itemCompleto = cantidadInput.getAttribute('data-item-completo') === 'true';
        
        // Obtener valores originales (los que vienen de la base de datos)
        const loteOriginal = document.getElementById(`lote_${uniqueId}`).getAttribute('data-original');
        const caducidadOriginal = document.getElementById(`caducidad_${uniqueId}`).getAttribute('data-original');
        const ubicacionOriginal = document.getElementById(`ubicacion_${uniqueId}`).getAttribute('data-original');
        const cantidadOriginal = document.getElementById(`cantidad_${uniqueId}`).getAttribute('data-original');
        const costoOriginal = document.getElementById(`costo_${uniqueId}`).getAttribute('data-original');
        const ivaOriginal = document.getElementById(`iva_${uniqueId}`).getAttribute('data-original');
        const descuentoOriginal = document.getElementById(`descuento_${uniqueId}`).getAttribute('data-original');

        // Obtener valores actuales
        const loteActual = document.getElementById(`lote_${uniqueId}`).value;
        const caducidadActual = document.getElementById(`caducidad_${uniqueId}`).value;
        const ubicacionActual = document.getElementById(`ubicacion_${uniqueId}`).value;
        const cantidadActual = document.getElementById(`cantidad_${uniqueId}`).value;
        const costoActual = document.getElementById(`costo_${uniqueId}`).value;
        const ivaActual = document.getElementById(`iva_${uniqueId}`).value;
        const descuentoActual = document.getElementById(`descuento_${uniqueId}`).value;

        // Verificar cambios en campos simples
        const cambiosSimples = (loteActual !== loteOriginal) || 
                              (caducidadActual !== caducidadOriginal) || 
                              (ubicacionActual !== ubicacionOriginal);

        // Verificar cambios en campos numéricos
        let cambiosNumericos = (cantidadActual !== cantidadOriginal) || 
                              (costoActual !== costoOriginal) || 
                              (ivaActual !== ivaOriginal) || 
                              (descuentoActual !== descuentoOriginal);

        // Si el item está completo, restringir cambios numéricos
        if (itemCompleto) {
            // Para items completos, solo permitir cambios simples y reducción de cantidad
            const cantidadReducida = parseFloat(cantidadActual) < parseFloat(cantidadOriginal);
            
            if (cambiosNumericos && !cantidadReducida) {
                // Si hay cambios numéricos pero no es reducción de cantidad, deshabilitar botones
                const btnCalcular = document.getElementById(`btnCalcular_${uniqueId}`);
                const btnActualizar = document.getElementById(`btnActualizar_${uniqueId}`);
                
                btnCalcular.disabled = true;
                btnCalcular.style.backgroundColor = "#ccc";
                btnCalcular.title = "Item completo: no se permiten cálculos";
                
                btnActualizar.disabled = true;
                btnActualizar.style.backgroundColor = "#ccc";
                btnActualizar.title = "Item completo: cambios restringidos";
                return;
            }
            
            // Si es reducción de cantidad, permitir solo actualización directa (sin cálculo)
            if (cantidadReducida) {
                cambiosNumericos = false; // Tratar como cambio simple
                cambiosSimples = true;
            }
        }

        const btnCalcular = document.getElementById(`btnCalcular_${uniqueId}`);
        const btnActualizar = document.getElementById(`btnActualizar_${uniqueId}`);

        if (cambiosNumericos && !itemCompleto) {
            // Si hay cambios numéricos y el item NO está completo, habilitar CALCULAR
            btnCalcular.disabled = false;
            btnCalcular.style.backgroundColor = "#2b2d7f";
            btnCalcular.textContent = "Calcular";
            btnCalcular.title = "";
            
            btnActualizar.disabled = true;
            btnActualizar.style.backgroundColor = "#ccc";
            
        } else if (cambiosSimples) {
            // Si solo hay cambios simples (o reducción de cantidad en item completo), habilitar ACTUALIZAR directamente
            btnActualizar.disabled = false;
            btnActualizar.style.backgroundColor = "#2b2d7f";
            btnActualizar.title = "";
            
            // No tocar el botón calcular si ya está calculado
            if (btnCalcular.textContent !== "Calculado") {
                btnCalcular.disabled = true;
                btnCalcular.style.backgroundColor = "#ccc";
            }
            
        } else {
            // Si no hay cambios válidos, deshabilitar ambos botones
            if (btnCalcular.textContent !== "Calculado") {
                btnCalcular.disabled = true;
                btnCalcular.style.backgroundColor = "#ccc";
            }
            
            btnActualizar.disabled = true;
            btnActualizar.style.backgroundColor = "#ccc";
        }
    }

    // Nueva función para actualizar con validación
    function updateItemWithAlert(uniqueId, itemId, formId) {
        // Verificar si el botón de actualizar está habilitado
        const btnActualizar = document.getElementById(`btnActualizar_${uniqueId}`);
        
        if (btnActualizar.disabled) {
            alert("No hay cambios para actualizar o debes calcular primero los campos numéricos.");
            return;
        }

        // Proceder con la actualización
        updateItem(uniqueId, itemId, formId);
    }

    // Función para inicializar el estado de todos los botones
    function inicializarEstadoBotones() {
        // Obtener todos los botones de calcular y actualizar
        document.querySelectorAll('[id^="btnCalcular_"], [id^="btnActualizar_"]').forEach((button) => {
            button.disabled = true;
            
            // Resetear el texto y estilo de los botones de calcular
            if (button.id.startsWith('btnCalcular_')) {
                button.textContent = "Calcular";
                button.style.backgroundColor = "#2b2d7f";
                button.style.color = "white";
            }
        });
    }

    // Agregar listeners de eventos a todos los campos
    document.addEventListener('DOMContentLoaded', function() {
        // Primero inicializar todos los botones en estado deshabilitado
        inicializarEstadoBotones();
        
        // Campos simples (lote, caducidad, ubicación) - solo requieren actualizar
        document.querySelectorAll('[id^="lote_"], [id^="caducidad_"], [id^="ubicacion_"]').forEach((input) => {
            const uniqueId = input.id.split('_')[1];
            
            input.addEventListener('input', function() {
                detectarCambios(uniqueId);
            });
            
            input.addEventListener('change', function() {
                detectarCambios(uniqueId);
            });
        });

        // Campos numéricos (cantidad, costo, IVA, descuento) - requieren calcular primero
        document.querySelectorAll('[id^="cantidad_"], [id^="costo_"], [id^="iva_"], [id^="descuento_"]').forEach((input) => {
            const uniqueId = input.id.split('_')[1];
            
            input.addEventListener('input', function() {
                detectarCambios(uniqueId);
            });
            
            input.addEventListener('change', function() {
                detectarCambios(uniqueId);
            });
        });

        // Campo costo_total - solo para visualización, se actualiza con calcular
        document.querySelectorAll('[id^="costo_total_"]').forEach((input) => {
            const uniqueId = input.id.split('_')[2];
            
            input.addEventListener('input', function() {
                detectarCambios(uniqueId);
            });
            
            input.addEventListener('change', function() {
                detectarCambios(uniqueId);
            });
        });
    });
</script>

<meta charset="UTF-8">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
      integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


<style>
    .compra-item {
        margin-bottom: 20px;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 5px;
    }

    .compra-details {
        margin-top: 10px;
    }

    .carrito-table {
        width: 100%;
        border-collapse: collapse;
    }

    .carrito-table th {
        background-color: #007bff;
        color: white;
        padding: 10px;
    }

    .carrito-table td {
        border: 1px solid #ddd;
        padding: 8px;
    }
</style>
<style>
    .compra-item {
        margin-bottom: 20px;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .compra-details {
        margin-top: 10px;
    }

    .carrito-table {
        width: 100%;
        border-collapse: collapse;
        background-color: #ffffff;
    }

    .carrito-table th {
        background-color: #2b2d7f;
        color: #ffffff;
        padding: 10px;
        border: 1px solid #2b2d7f;
    }

    .carrito-table td {
        border: 1px solid #ddd;
        padding: 8px;
        color: #333;
        text-align: center;
    }

    .carrito-table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    /* Añadir el estilo para la paginación */
    a {
        color: #2b2d7f;
        text-decoration: none;
        /* Elimina el subrayado */
    }

    a:hover {
        text-decoration: underline;
        /* Agrega subrayado al pasar el mouse */
    }

    strong {
        color: #2b2d7f;
        /* Color para el número de página actual */
    }

    .table-responsive {
        overflow-x: auto;
        /* Permitir desplazamiento horizontal */
        width: 100%;
        /* Ancho del contenedor */
    }

    .carrito-table {
        width: 100%;
        /* Ancho de la tabla */
        border-collapse: collapse;
        /* Para que las bordes no se dupliquen */
    }

    th,
    td {
        padding: 8px;
        /* Espaciado interno */
        text-align: left;
        /* Alinear texto a la izquierda */
        border: 1px solid #ddd;
        /* Bordes de las celdas */
    }

    /* Estilo para botones deshabilitados */
    button:disabled {
        background-color: #ccc !important;
        color: #666 !important;
        cursor: not-allowed !important;
        opacity: 0.6;
    }

    button:enabled {
        cursor: pointer;
    }

    /* Estilos para etiquetas de estado */
    .status-label {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: bold;
        margin-left: 10px;
        text-transform: uppercase;
    }

    .status-parcial {
        background-color: #ff9800;
        color: white;
    }

    .status-completo {
        background-color: #4CAF50;
        color: white;
    }
</style>
