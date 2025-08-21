<?php
include "../../conexionbd.php";

session_start();
// Iniciar el buffer de salida para prevenir errores de encabezado
ob_start();

$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];
date_default_timezone_set('America/Guatemala');

if (isset($usuario['id_rol'])) {
    if ($usuario['id_rol'] == 11 || $usuario['id_rol'] == 4 || $usuario['id_rol'] == 5) {
        include "../header_farmaciac.php";
    } else {
        // Si el usuario no tiene un rol permitido, destruir la sesión y redirigir
        session_unset();
        session_destroy();
        echo "<script>window.location='../../index.php';</script>";
        exit();
    }
}

// Consulta para obtener las órdenes de compra desde `ordenes_compra` donde 'activo' sea igual a 'SI'
// También obtener información de si tiene entregas parciales
$ordenes = $conexion->query("
    SELECT DISTINCT 
        oc.id_compra, 
        oc.activo, 
        oc.estatus,
        CASE 
            WHEN EXISTS (
                SELECT 1 FROM transacciones t 
                WHERE t.id_compra = oc.id_compra AND t.parcial = 'SI'
            ) THEN 'SI'
            ELSE 'NO'
        END as tiene_parciales
    FROM ordenes_compra oc 
    WHERE oc.activo = 'SI' 
    AND (oc.estatus = 'PARCIAL' OR oc.estatus = 'AUTORIZADO')
    ORDER BY oc.id_compra
") or die("Error al obtener órdenes de compra: " . $conexion->error);

// Verificar si se seleccionó un id_compra
$ordenSeleccionada = isset($_POST['id_compra']) ? $_POST['id_compra'] : '';
$mostrarFormularioCarga = false;

// Inicializar variables
$detallesOrden = [];
$proveedorNombre = '';
$total = 0;

if ($ordenSeleccionada) {
    // Consulta para obtener los detalles de la orden y el proveedor desde la columna nom_prov de la tabla proveedores
    $detallesOrden = $conexion->query("
        SELECT oc.*, p.nom_prov 
        FROM orden_compra oc 
        INNER JOIN ordenes_compra o ON oc.id_compra = o.id_compra 
        INNER JOIN item_almacen ia ON oc.item_id = ia.item_id
        INNER JOIN proveedores p ON o.id_prov = p.id_prov
        WHERE oc.id_compra = '$ordenSeleccionada'
    ") or die("Error en la consulta de detalles de la orden: " . $conexion->error);

    if ($detallesOrden->num_rows > 0) {
        $detalle = $detallesOrden->fetch_assoc();
        $proveedorNombre = $detalle['nom_prov']; // Asignar el valor de nom_prov como el nombre del proveedor
        $mostrarFormularioCarga = true;
    }


    // Consulta para obtener los ítems relacionados a la orden seleccionada con sus detalles
    $itemsOrdenCompra = $conexion->query("
        SELECT oc.item_id, oc.solicita, oc.entrega, ia.item_name, ia.item_grams, ia.factor, ia.item_costs
        FROM orden_compra oc 
        INNER JOIN item_almacen ia ON oc.item_id = ia.item_id 
        WHERE oc.id_compra = '$ordenSeleccionada'
    ") or die("Error al obtener ítems de la orden: " . $conexion->error);

    // Consulta para obtener las ubicaciones
    $ubicaciones = $conexion->query("SELECT ubicacion_id, nombre_ubicacion FROM ubicaciones_almacen") or die("Error al obtener ubicaciones: " . $conexion->error);
}




// Consulta para obtener los ítems que no coinciden en 'entrega' y 'solicita'
$itemsOrdenCompra = $conexion->query("
    SELECT oc.item_id, oc.solicita, oc.entrega, ia.item_name, ia.item_grams, ia.factor, ia.item_costs,
           (oc.solicita - oc.entrega) as cantidad_pendiente
    FROM orden_compra oc 
    INNER JOIN item_almacen ia ON oc.item_id = ia.item_id
    WHERE oc.id_compra = '$ordenSeleccionada' AND oc.entrega <> oc.solicita
") or die("Error al obtener ítems de la orden: " . $conexion->error);






if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $todo_correcto = true;
    $id_compra = $ordenSeleccionada;
    $id_usua = $_SESSION['login']['id_usua'];
    $factura = isset($_POST['facturaOculta']) ? $_POST['facturaOculta'] : '';
    $fecha_actual = date('Y-m-d H:i:s');

    // Capturar arrays del formulario
    $entrada_lotes = isset($_POST['entrada_lote']) ? $_POST['entrada_lote'] : [];
    $entrada_caducidades = isset($_POST['entrada_caducidad']) ? $_POST['entrada_caducidad'] : [];
    $entrada_unidosis = isset($_POST['entrada_unidosis']) ? $_POST['entrada_unidosis'] : [];
    $item_costos = isset($_POST['item_costo']) ? $_POST['item_costo'] : [];
    $ubicacion_ids = isset($_POST['ubicacion_id']) ? $_POST['ubicacion_id'] : [];
    $item_ids = isset($_POST['item_ids']) ? $_POST['item_ids'] : [];

    // Debug: Mostrar arrays recibidos
    echo "<script>console.log('Arrays recibidos:');</script>";
    echo "<script>console.log('item_ids:', " . json_encode($item_ids) . ");</script>";
    echo "<script>console.log('entrada_lotes:', " . json_encode($entrada_lotes) . ");</script>";
    echo "<script>console.log('entrada_unidosis:', " . json_encode($entrada_unidosis) . ");</script>";
    echo "<script>console.log('ubicacion_ids:', " . json_encode($ubicacion_ids) . ");</script>";

    // Capturar totales del formulario
    $totalDescuento = isset($_POST['totalDescuento']) ? $_POST['totalDescuento'] : 0;
    $TotalIva = isset($_POST['TotalIva2']) ? $_POST['TotalIva2'] : 0;
    $totalGeneral = isset($_POST['totalGeneral2']) ? $_POST['totalGeneral2'] : 0;
    $totalMontos = isset($_POST['totalMonto2']) ? $_POST['totalMonto2'] : 0;
    $ivas = isset($_POST['iva']) ? $_POST['iva'] : 0;
    $descuentos = isset($_POST['descuento']) ? $_POST['descuento'] : 0;
    $total = isset($_POST['total']) ? $_POST['total'] : 0;

    // Recorrer cada item para insertar en la tabla `carrito_entradas`
    if (!empty($item_ids)) {
        for ($i = 0; $i < count($item_ids); $i++) {
            $item_id = isset($item_ids[$i]) ? $item_ids[$i] : null;
            $entrada_lote = isset($entrada_lotes[$i]) ? $entrada_lotes[$i] : '';
            $entrada_caducidad = isset($entrada_caducidades[$i]) ? $entrada_caducidades[$i] : '';
            $entrada_qty = isset($entrada_unidosis[$i]) ? $entrada_unidosis[$i] : 0;
            $entrada_unidosis_valor = isset($entrada_unidosis[$i]) ? $entrada_unidosis[$i] : 0;
            $entrada_costo = isset($item_costos[$i]) ? $item_costos[$i] : 0;
            $ubicacion_id = isset($ubicacion_ids[$i]) ? $ubicacion_ids[$i] : null;
            $Ivas = isset($ivas[$i]) ? $ivas[$i] : 0;
            $Descuentos = isset($descuentos[$i]) ? $descuentos[$i] : 0;
            $Totales = isset($total[$i]) ? $total[$i] : 0;

            // Solo procesar si entrada_qty (entrada_unidosis) es mayor que 0
            if ($entrada_qty > 0) {
                // Insertar en `carrito_entradas`
                $query_carrito_entradas = "INSERT INTO carrito_entradas 
                    (id_compra, entrada_fecha, item_id, entrada_lote, entrada_caducidad, 
                        entrada_qty, entrada_unidosis, entrada_costo, ubicacion_id, entrada_iva, entrada_descuento, total,factura)
                    VALUES 
                    ('$id_compra', '$fecha_actual', '$item_id', '$entrada_lote', '$entrada_caducidad', 
                     '$entrada_qty', '$entrada_unidosis_valor', '$entrada_costo', '$ubicacion_id', '$Ivas', '$Descuentos', '$Totales', '$factura')";

                if ($conexion->query($query_carrito_entradas) === TRUE) {
                    echo "<script>console.log('Registro insertado correctamente en carrito_entradas para el item_id: $item_id, lote: $entrada_lote, unidosis: $entrada_qty');</script>";
                } else {
                    $todo_correcto = false;
                    echo "<script>console.error('Error al insertar en carrito_entradas: " . $conexion->error . "');</script>";
                }

                // Actualizar `orden_compra` con el campo `entrega`
                $query_orden_compra = "UPDATE orden_compra SET entrega = entrega + ? WHERE id_compra = ? AND item_id = ?";
                $stmt_orden_compra = $conexion->prepare($query_orden_compra);
                $stmt_orden_compra->bind_param('iis', $entrada_qty, $id_compra, $item_id);

                if (!$stmt_orden_compra->execute()) {
                    $todo_correcto = false;
                    echo "<script>console.error('Error en la actualización de orden_compra: " . $stmt_orden_compra->error . "');</script>";
                }
            } else {
                // Log para registros omitidos por tener entrada_unidosis = 0
                echo "<script>console.log('Registro omitido para item_id: $item_id (entrada_unidosis = 0)');</script>";
            }
        }

        // Insertar en `transacciones` con la nueva estructura
        // Primero eliminar registros existentes en transacciones para este id_compra
        $query_eliminar_transacciones = "DELETE FROM transacciones WHERE id_compra = ?";
        $stmt_eliminar_transacciones = $conexion->prepare($query_eliminar_transacciones);
        $stmt_eliminar_transacciones->bind_param('i', $id_compra);
        $stmt_eliminar_transacciones->execute();
        
        // Obtener items únicos del carrito para determinar si son parciales
        $query_items_carrito = "SELECT DISTINCT item_id FROM carrito_entradas WHERE id_compra = ?";
        $stmt_items_carrito = $conexion->prepare($query_items_carrito);
        $stmt_items_carrito->bind_param('i', $id_compra);
        $stmt_items_carrito->execute();
        $result_items_carrito = $stmt_items_carrito->get_result();

        while ($item_row = $result_items_carrito->fetch_assoc()) {
            $current_item_id = $item_row['item_id'];
            
            // Obtener cantidad solicitada desde orden_compra DESPUÉS de la actualización
            $query_solicitada = "SELECT solicita, entrega FROM orden_compra WHERE id_compra = ? AND item_id = ? LIMIT 1";
            $stmt_solicitada = $conexion->prepare($query_solicitada);
            $stmt_solicitada->bind_param('ii', $id_compra, $current_item_id);
            $stmt_solicitada->execute();
            $result_solicitada = $stmt_solicitada->get_result();
            $solicitada_row = $result_solicitada->fetch_assoc();
            $cantidad_solicitada = $solicitada_row['solicita'] ?? 0;
            $cantidad_entregada_actual = $solicitada_row['entrega'] ?? 0;
            
            // Determinar si este item específico es parcial basado en la comparación de entrega vs solicita
            // PARCIAL = 'SI' cuando: entrega < solicita (aún falta por entregar)
            // PARCIAL = 'NO' cuando: entrega >= solicita (item completamente entregado)
            $es_parcial_item = ($cantidad_entregada_actual < $cantidad_solicitada) ? 'SI' : 'NO';
            
            // Log para depuración del estado parcial
            echo "<script>console.log('Item $current_item_id: Solicitado=$cantidad_solicitada, Entregado=$cantidad_entregada_actual, Parcial=$es_parcial_item');</script>";
            
            // Obtener todos los registros de carrito_entradas para este item_id
            $query_registros_item = "SELECT * FROM carrito_entradas WHERE id_compra = ? AND item_id = ?";
            $stmt_registros_item = $conexion->prepare($query_registros_item);
            $stmt_registros_item->bind_param('ii', $id_compra, $current_item_id);
            $stmt_registros_item->execute();
            $result_registros_item = $stmt_registros_item->get_result();
            
            // Insertar cada registro en transacciones con la nueva estructura
            while ($registro = $result_registros_item->fetch_assoc()) {
                $query_insert_transaccion = "INSERT INTO transacciones (
                    id_compra, entrada_fecha, item_id, entrada_lote, entrada_caducidad, 
                    entrada_qty, entrada_unidosis, entrada_costo, ubicacion_id, 
                    entrada_iva, entrada_descuento, Total, parcial, id_carrito_entrada
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $stmt_insert_transaccion = $conexion->prepare($query_insert_transaccion);
                $stmt_insert_transaccion->bind_param(
                    'isissiididddsi',
                    $registro['id_compra'],
                    $fecha_actual,
                    $registro['item_id'],
                    $registro['entrada_lote'],
                    $registro['entrada_caducidad'],
                    $registro['entrada_qty'],
                    $registro['entrada_unidosis'],
                    $registro['entrada_costo'],
                    $registro['ubicacion_id'],
                    $registro['entrada_iva'],
                    $registro['entrada_descuento'],
                    $registro['Total'],
                    $es_parcial_item,  // Usar el estado parcial del item específico
                    $registro['id']  // ID del registro de carrito_entradas
                );
                
                if (!$stmt_insert_transaccion->execute()) {
                    $todo_correcto = false;
                    echo "<script>console.error('Error al insertar en transacciones: " . $stmt_insert_transaccion->error . "');</script>";
                } else {
                    echo "<script>console.log('✓ Transacción insertada - Item: $current_item_id, Lote: " . $registro['entrada_lote'] . ", Qty: " . $registro['entrada_qty'] . ", Parcial: $es_parcial_item');</script>";
                }
            }
        }

        // Determinar el estado de activo basado en si todos los items están completos
        // Verificar si todos los items de la orden tienen entrega = solicita
        $query_verificar_completitud = "SELECT 
            COUNT(*) as total_items,
            COUNT(CASE WHEN entrega = solicita THEN 1 END) as items_completos
        FROM orden_compra 
        WHERE id_compra = ?";
        
        $stmt_verificar_completitud = $conexion->prepare($query_verificar_completitud);
        $stmt_verificar_completitud->bind_param('i', $id_compra);
        $stmt_verificar_completitud->execute();
        $result_verificar_completitud = $stmt_verificar_completitud->get_result();
        $completitud_row = $result_verificar_completitud->fetch_assoc();
        
        $total_items = $completitud_row['total_items'];
        $items_completos = $completitud_row['items_completos'];
        $todos_completos = ($total_items > 0 && $total_items == $items_completos);
        
        // Determinar estados basado en completitud
        if ($todos_completos) {
            // Si todos los items están completos (entrega = solicita)
            $estado_activo = 'NO';
            $estatus = 'ENTREGADO';
            $parcial_global = 'NO';
        } else {
            // Si hay items pendientes (entrega < solicita)
            $estado_activo = 'SI';
            $estatus = 'PARCIAL';
            $parcial_global = 'SI';
        }
        
        echo "<script>console.log('Estado de la orden $id_compra: Items completos: $items_completos/$total_items, Todos completos: " . ($todos_completos ? 'SI' : 'NO') . ", Activo: $estado_activo, Estatus: $estatus, Parcial: $parcial_global');</script>";
        
        // Obtener el valor actual de factura antes de actualizar
        $query_factura_actual = "SELECT factura FROM ordenes_compra WHERE id_compra = ?";
        $stmt_factura_actual = $conexion->prepare($query_factura_actual);
        $stmt_factura_actual->bind_param('i', $id_compra);
        $stmt_factura_actual->execute();
        $result_factura_actual = $stmt_factura_actual->get_result();
        $factura_actual_row = $result_factura_actual->fetch_assoc();
        $factura_actual = $factura_actual_row['factura'] ?? '';
        
        // Concatenar nueva factura con la existente
        $nueva_factura = '';
        if (!empty($factura_actual) && !empty($factura)) {
            // Si ya hay una factura y se envía una nueva, concatenar con coma
            $nueva_factura = $factura_actual . ', ' . $factura;
        } elseif (!empty($factura)) {
            // Si no hay factura anterior pero se envía una nueva
            $nueva_factura = $factura;
        } else {
            // Si no se envía factura nueva, mantener la actual
            $nueva_factura = $factura_actual;
        }
        
        // Actualizar ordenes_compra con el estado correcto y la factura concatenada
        $query_ordenes_compra = "INSERT INTO ordenes_compra (id_compra, activo, estatus, factura, parcial) 
            VALUES (?, ?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE activo = ?, estatus = ?, factura = ?, parcial = ?";
        
        $stmt_ordenes_compra = $conexion->prepare($query_ordenes_compra);
        $stmt_ordenes_compra->bind_param('issssssss', $id_compra, $estado_activo, $estatus, $nueva_factura, $parcial_global, $estado_activo, $estatus, $nueva_factura, $parcial_global);
        $stmt_ordenes_compra->execute();

        // Si todas las operaciones fueron correctas, redirigir a entradas.php
        if ($todo_correcto) {
            header('Location: entradas.php');
            exit(); // Asegurarse de que el script se detenga después de la redirección
        }
    }

    // Enviar el buffer de salida y limpiar
    ob_end_flush();
}


?>


<!DOCTYPE html>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<div class="form-container">
    <!-- Ícono del Carrito de Compras -->
    <div class="shopping-cart-icon">
        <a href="carrito_entradas.php" title="Ir al carrito de compras">
            <span class="material-icons">shopping_cart</span>
        </a>
    </div>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
              integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="icon" href="../imagenes/SIF.PNG">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Formulario de Orden</title>

        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
            }

            .form-container {
                width: 95%;
                margin: 20px auto;
                background-color: #fdfdfd;
                border-radius: 10px;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            h2,
            h3 {
                color: #2b2d7f;
                text-align: center;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            table,
            th,
            td {
                border: 1px solid #ddd;
            }

            th,
            td {
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #2b2d7f;
                color: white;
            }

            td {
                background-color: rgba(179, 206, 247, 0.77);
            }

            input[type="text"],
            input[type="date"],
            select {
                width: calc(100% - 32px);
                padding: 15px;
                margin: 5px 0;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-sizing: border-box;
            }

            input[type="submit"] {
                background-color: #2b2d7f;
                color: white;
                border: none;
                padding: 10px 20px;
                font-size: 16px;
                cursor: pointer;
                border-radius: 5px;
                margin-top: 10px;
            }

            input[type="submit"]:hover {
                background-color: #2b2d7f;
            }

            .scrollable-area {
                overflow-x: auto;
                border: 1px solid #ddd;
                border-radius: 5px;
                padding: 10px;
                background-color: #ffffff;
                white-space: nowrap;
            }

            .scrollable-area table {
                width: 100%;
                table-layout: auto;
            }

            .scrollable-area th:nth-child(1),
            .scrollable-area th:nth-child(2),
            .scrollable-area th:nth-child(3) {
                width: 120px;
            }

            .scrollable-area th:nth-child(4),
            .scrollable-area th:nth-child(5),
            .scrollable-area th:nth-child(6),
            .scrollable-area th:nth-child(7),
            .scrollable-area th:nth-child(8),
            .scrollable-area th:nth-child(9),
            .scrollable-area th:nth-child(10),
            .scrollable-area th:nth-child(11),
            .scrollable-area th:nth-child(12),
            .scrollable-area th:nth-child(13),
            .scrollable-area th:nth-child(14),
            .scrollable-area th:nth-child(15),
            .scrollable-area th:nth-child(16) {
                width: 105px;
            }

            .scrollable-area input[type="text"] {
                width: 150px;
                padding: 10px;
            }


            .btn-calcular {
                background-color: #2b2d7f;
                color: white;
                border: none;
                padding: 10px 20px;
                font-size: 16px;
                cursor: pointer;
                border-radius: 5px;
            }

            .btn-calcular:hover {
                background-color: #2b2d7f;
            }

            .btn-calcular:disabled {
                background-color: #cccccc !important;
                color: #666666 !important;
                cursor: not-allowed !important;
                opacity: 0.6;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            td {
                padding: 3px;
                vertical-align: middle;
            }

            .btn-calcular {
                margin-right: 10px;
            }

            .form-container {
                position: relative;
                /* Para que el ícono se posicione relativo a este contenedor */
                width: 95%;
                max-width: 1400px;
                margin: auto;
                padding: 20px;
                border-radius: 10px;
                background-color: #fdfdfd;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            /* Icono del carrito de compras */
            .shopping-cart-icon {
                position: absolute;
                top: 10px;
                /* Ajusta la posición según sea necesario */
                right: 20px;
                /* Mantiene la distancia del borde derecho */
                font-size: 24px;
                /* Tamaño del ícono */
                color: #2b2d7f;
                /* Color azul */
                display: inline-block;
                cursor: pointer;
                transition: color 0.3s;
                z-index: 1000;
            }

            /* Botón para agregar lotes */
            .btn-agregar-lote {
                background-color: #2b2d7f;
                color: white;
                border: none;
                padding: 8px 12px;
                font-size: 16px;
                cursor: pointer;
                border-radius: 50%;
                transition: background-color 0.3s;
                width: 35px;
                height: 35px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .btn-agregar-lote:hover {
                background-color: #2b2d7f;
            }

            /* Botón para eliminar filas de lotes */
            .btn-eliminar-lote {
                background-color: #dc3545;
                color: white;
                border: none;
                padding: 8px 12px;
                font-size: 16px;
                cursor: pointer;
                border-radius: 50%;
                transition: background-color 0.3s;
                width: 35px;
                height: 35px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .btn-eliminar-lote:hover {
                background-color: #c82333;
            }
        </style>

        <head>
            <title>Seleccionar Número de Orden</title>
        </head>

    <body>


<div class="d-flex justify-content-start mb-3">
    <a href="insertar_kardex.php" class="btn btn-primary" style="margin-top: 10px;">
        ➕ Agregar entrada directa
    </a>

</div>

        <?php if (!$mostrarFormularioCarga) { ?>

            <h2>Seleccionar Número de Orden</h2>
            

            <!-- Formulario para seleccionar número de orden -->
            <form method="POST" action="">
                <table>
                    <tr>
                        <th>No. Orden</th>
                        <td>
                            <select name="id_compra" id="id_compra" required>
                                <option value="">Seleccionar de la lista</option>
                                <?php while ($row = $ordenes->fetch_assoc()) { 
                                    $displayText = $row['id_compra'];
                                    if ($row['tiene_parciales'] === 'SI') {
                                        $displayText .= ' - PARCIAL';
                                    }
                                ?>
                                    <option value="<?php echo $row['id_compra']; ?>" <?php echo $row['id_compra'] == $ordenSeleccionada ? 'selected' : ''; ?>>
                                        <?php echo $displayText; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align:center;">
                            <input type="submit" value="Cargar Orden">

                        </td>
                    </tr>
                </table>
            </form>
        <?php } ?>


        <?php if ($mostrarFormularioCarga) { ?>
            <div class="form-container" style="width: 95%; max-width: 1400px; margin: auto;">


                <h3 style="text-align: center;">Detalles de la Orden</h3>
                <form method="POST" action="" onsubmit="return guardarTotal(event);">
                    <div style="display: flex; justify-content: center;">
                        <!-- Contenedor principal de la tabla con display flex -->

                        <!-- Proveedor -->
                        <div style="padding: 10px;">
                            <label style="display: block; color: #2b2d7f;">Proveedor</label>
                            <input type="text" value="<?php echo $proveedorNombre; ?>" readonly style="width: 200px; height: 40px;">
                            <!-- Ajusta el ancho -->
                        </div>

                        <div style="padding: 10px;">
                            <label style="display: block; color: #2b2d7f;">Factura</label>
                            <input type="text" name="Cfactura" id="facturaInput" value="" required style="width: 120px;">
                        </div>

                        <!-- Monto -->
                        <div style="padding: 10px;">
                            <label style="display: block; color: #2b2d7f;">Monto</label>
                            <input type="text" name="Cmonto[]" value="" placeholder="0.00" required style="width: 120px;" id="Cmonto">
                        </div>

                        <!-- Descuento -->
                        <div style="padding: 10px;">
                            <label style="display: block; color: #2b2d7f;">Descuento</label>
                            <input type="text" name="Cdescuento[]" value="" placeholder="0.00" style="width: 120px;" id="Cdescuento">
                        </div>

                        <!-- IVA -->
                        <div style="padding: 10px;">
                            <label style="display: block; color: #2b2d7f;">IVA</label>
                            <input type="text" name="Civa[]" value="" placeholder="0.00" style="width: 120px;" id="Civa">
                        </div>

                        <!-- Total -->
                        <div style="padding: 10px;">
                            <label style="display: block; color: #2b2d7f;">Total</label>
                            <input type="text" name="Ctotal[]" id="Ctotal" value="" readonly style="width: 120px;">
                        </div>
                    </div>
                    <div style="text-align: center; margin-top: 20px;"> <!-- Centra el botón -->
                        <button type="submit" onclick="calcularTotales()" style="background-color: #2b2d7f; color: #fff; padding: 10px 20px; border: none; border-radius: 5px;">Calcular</button>
                    </div>
                </form>
            </div>


            <script>
                let totalGlobal = 0; // Variable global para almacenar el total

                function guardarTotal(event) {
                    event.preventDefault(); // Evitar que el formulario se envíe

                    // Obtener valores de los inputs
                    const monto = parseFloat(document.getElementById('Cmonto').value) || 0;
                    const descuento = parseFloat(document.getElementById('Cdescuento').value) || 0;
                    const ivaGeneral = parseFloat(document.getElementById('Civa').value) || 0;

                    // Calcular el total: monto - descuento + iva
                    // El IVA aquí es un monto fijo, no un porcentaje
                    const total = monto - descuento + ivaGeneral;

                    // Asignar el total al input correspondiente
                    document.getElementById('Ctotal').value = total.toFixed(2); // Formatear a 2 decimales

                    // Guardar el total en la variable global
                    totalGlobal = total;

                    // Habilitar el botón "Enviar al Carrito" solo si se ha calculado un total válido
                    const btnEnviarCarrito = document.getElementById('btnAfectarEntrada');
                    if (total > 0 && monto > 0) {
                        // Solo habilitar si hay un total válido
                        btnEnviarCarrito.disabled = false;
                        btnEnviarCarrito.style.backgroundColor = '#2b2d7f';
                        btnEnviarCarrito.style.cursor = 'pointer';
                        console.log("Botón 'Enviar al Carrito' habilitado. Total calculado:", total);
                    } else {
                        // Mantener deshabilitado si no hay total válido
                        btnEnviarCarrito.disabled = true;
                        btnEnviarCarrito.style.backgroundColor = '#cccccc';
                        btnEnviarCarrito.style.cursor = 'not-allowed';
                        console.log("Botón 'Enviar al Carrito' deshabilitado. Total inválido:", total);
                    }

                    console.log("Total guardado:", totalGlobal); // Mostrar el total en la consola (opcional)

                    return false; // Evitar que el formulario se envíe
                }

                function calcularTotales() {
                    guardarTotal(event);
                }

                document.addEventListener('DOMContentLoaded', (event) => {
                    // Asegurar que el botón esté deshabilitado al cargar la página
                    const btnEnviarCarrito = document.getElementById('btnAfectarEntrada');
                    if (btnEnviarCarrito) {
                        btnEnviarCarrito.disabled = true;
                        btnEnviarCarrito.style.backgroundColor = '#cccccc';
                        btnEnviarCarrito.style.color = '#666666';
                        btnEnviarCarrito.style.cursor = 'not-allowed';
                    }

                    // Si hay valores previos, calcular automáticamente
                    if (document.getElementById('Cmonto').value || document.getElementById('Cdescuento').value || document.getElementById('Civa').value) {
                        calcularTotales();
                    }
                });
            </script>





            <div>
                <h3>Cargar Ítems de la Orden</h3>
                <div class="scrollable-area">
                    <form method="POST" action="" id="segundoFormulario"> <!-- Añadir acción si es necesario -->
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Lote</th>
                                <th>Caducidad</th>
                                <th>Cantidad Solicitada</th>
                                <th>Ya Entregado</th>
                                <th>Entregado Unidosis</th>
                                <th>Costo</th>
                                <th>Descuento</th>
                                <th>Costo*Cantidad</th>
                                <th>IVA %</th>
                                <th>IVA Calculado</th>
                                <th>Total</th>
                                <th>Ubicación</th>
                                <th>Acciones</th>
                            </tr>

                            <?php 
                            $index = 0;
                            while ($item = $itemsOrdenCompra->fetch_assoc()) {
                            ?>
                                <tr>
                                    <input type="hidden" name="item_ids[]" value="<?php echo $item['item_id']; ?>">
                                    <input type="hidden" name="id_compra" value="<?php echo $ordenSeleccionada; ?>">

                                    <td><?php echo $item['item_id']; ?></td>
                                    <td><?php echo $item['item_name'].', '.  $item['item_grams']; ?></td>
                                    <td>
                                        <input type="text" name="entrada_lote[]" class="campo-condicional">
                                    </td>
                                    <td>
                                        <input type="date" name="entrada_caducidad[]" class="campo-condicional">
                                    </td>
                                    <td>
                                        <input type="text" name="entrada_qty[]" value="<?php echo $item['solicita']; ?>" readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="ya_entregado[]" value="<?php echo $item['entrega']; ?>" readonly style="background-color: #f0f8ff; color: #0066cc; font-weight: bold;">
                                    </td>
                                    <td>
                                        <input type="text" name="entrada_unidosis[]" required data-max="<?php echo $item['cantidad_pendiente']; ?>" data-solicitada="<?php echo $item['solicita']; ?>" data-entregada="<?php echo $item['entrega']; ?>" oninput="validarEntradaEnTiempoReal(this);" onblur="validarEntradaFinal(this);">
                                    </td>
                                    <td>
                                        <input type="text" name="item_costo[]" value="<?php echo $item['item_costs']; ?>" readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="descuento[]" class="campo-condicional" placeholder="0.00">
                                    </td>
                                    <td>
                                        <input type="text" name="monto[]" readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="iva[]" class="campo-condicional" placeholder="0.00">
                                    </td>
                                    <td>
                                        <input type="text" name="iva_calculado[]" readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="total[]" class="total" readonly>
                                    </td>
                                    <td>
                                        <select name="ubicacion_id[]" style="width: 180px;" class="campo-condicional">
                                            <option value="">Selecciona una ubicación</option>
                                            <?php
                                            // Reiniciar el puntero de la consulta de ubicaciones
                                            $ubicaciones->data_seek(0);
                                            $primera_ubicacion = true;
                                            while ($ubicacion = $ubicaciones->fetch_assoc()) { ?>
                                                <option value="<?php echo $ubicacion['ubicacion_id']; ?>" <?php echo $primera_ubicacion ? 'selected' : ''; ?>>
                                                    <?php echo $ubicacion['nombre_ubicacion']; ?>
                                                </option>
                                            <?php 
                                                $primera_ubicacion = false;
                                            } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <button type="button" class="btn-agregar-lote" onclick="agregarFilaLote(this)" title="Agregar otro lote">
                                            ➕
                                        </button>
                                    </td>
                                </tr>
                            <?php 
                            $index++;
                            } ?>
                            <input type="hidden" name="totalDescuento" id="totalDescuento" value="0">
                            <input type="hidden" name="TotalIva2" id="TotalIva2" value="0">
                            <input type="hidden" name="totalGeneral2" id="totalGeneral2" value="0">
                            <input type="hidden" name="totalMonto2" id="totalMonto2" value="0">

                            <input type="hidden" name="facturaOculta" id="facturaOculta" value="">

                            <tr>
                                <td colspan="13" style="text-align: right; padding-top: 10px;"> <!-- Ajuste del colspan -->
                                    <input type="button" id="btnCalcular" value="Calcular Totales" class="btn-calcular"
                                        onclick="calcularTotales()" style="margin-right: 10px;">
                                </td>
                                <td style="text-align: right; font-weight: bold; padding-top: 10px;">Monto Total:</td>
                                <td>
                                    <input type="text" id="totalMonto" readonly style="width: 100px;">
                                </td> <!-- Campo para mostrar el monto total -->
                            </tr>

                            <tr>
                                <td colspan="13" style="text-align: right; padding-top: 5px;"></td>
                                <td style="text-align: right; font-weight: bold; padding-top: 5px;">Descuento Total:</td>
                                <td>
                                    <input type="text" id="totalDescuentoCalculado" readonly style="width: 100px;">
                                </td> <!-- Campo para mostrar el descuento total -->
                            </tr>

                            <tr>
                                <td colspan="13" style="text-align: right; padding-top: 5px;"></td>
                                <td style="text-align: right; font-weight: bold; padding-top: 5px;">IVA Total:</td>
                                <td>
                                    <input type="text" id="totalIvaCalculado" readonly style="width: 100px;">
                                </td> <!-- Campo para mostrar el IVA total -->
                            </tr>

                            <tr>
                                <td colspan="13" style="text-align: right; padding-top: 5px;"></td>
                                <td style="text-align: right; font-weight: bold; padding-top: 5px;">Total General:</td>
                                <td>
                                    <input type="text" id="totalGeneral" readonly style="width: 100px;">
                                </td> <!-- Campo para mostrar el total general -->
                            </tr>

                            <tr>
                                <td colspan="17" style="text-align: right; padding-top: 10px;"> <!-- Ajuste del colspan -->
                                    <div id="mensajeError" style="color: red; display: none;">Los totales no coinciden.
                                    </div>

                                    <button type="submit" style="margin-top: 10px; background-color: #cccccc; color: #666666; cursor: not-allowed;" id="btnAfectarEntrada" disabled
                                        class="btn-calcular">Enviar al Carrito</button>

                            </tr>



                        </table>
                    </form>
                </div>
            </div>
        <?php } ?>
</div>

</body>



<script>
    // Función para validación en tiempo real (mientras escribe)
    function validarEntradaEnTiempoReal(input) {
        const valorEntradaUnidosis = parseInt(input.value);
        
        // Solo validar números negativos en tiempo real
        if (valorEntradaUnidosis < 0) {
            alert("El valor no puede ser negativo.");
            input.value = ""; // Solo limpiar si es negativo
            return false;
        }

        // Manejar campos condicionales solo si el valor es válido
        if (!isNaN(valorEntradaUnidosis)) {
            manejarCamposCondicionales(input);
        }

        return true;
    }

    // Función para validación final (cuando termina de editar)
    function validarEntradaFinal(input) {
        const valorEntradaUnidosis = parseInt(input.value) || 0;
        
        // Validar el total por item_id solo al final
        if (!validarTotalPorItem(input)) {
            // No mantener el foco aquí para evitar loops infinitos
            // El valor ya fue corregido automáticamente en validarTotalPorItem
            return false;
        }

        // Manejar campos condicionales basándose en el valor final
        manejarCamposCondicionales(input);
        return true;
    }

    function validarEntrada(input) {
        // Obtener el valor de entrada_unidosis
        const valorEntradaUnidosis = parseInt(input.value) || 0;
        // Obtener el valor máximo desde el atributo data-max
        const maximoPermitido = parseInt(input.getAttribute('data-max'));

        // Verificar si el valor es negativo
        if (valorEntradaUnidosis < 0) {
            alert("El valor no puede ser negativo.");
            input.value = ""; // Limpiar el campo
            input.focus(); // Mantener el foco para que el usuario corrija
            return false;
        }

        // Validar el total por item_id
        if (!validarTotalPorItem(input)) {
            // No mantener el foco aquí para evitar loops, 
            // ya que validarTotalPorItem corrige automáticamente el valor
            return false;
        }

        return true;
    }

    function manejarCamposCondicionales(inputUnidosis) {
        const fila = inputUnidosis.closest('tr');
        const valorUnidosis = parseInt(inputUnidosis.value);
        const camposCondicionales = fila.querySelectorAll('.campo-condicional');
        
        camposCondicionales.forEach(campo => {
            // Si entrada_unidosis es exactamente 0 o está vacío, manejar campos condicionales
            if (valorUnidosis === 0 || isNaN(valorUnidosis) || inputUnidosis.value === '') {
                // Remover required y limpiar el campo
                campo.removeAttribute('required');
                if (campo.tagName === 'SELECT') {
                    campo.selectedIndex = 0; // Seleccionar la primera opción (vacía)
                } else if (campo.name !== 'descuento[]' && campo.name !== 'iva[]') {
                    // Solo limpiar campos que no son descuento o IVA (estos pueden quedar con placeholder)
                    campo.value = '';
                }
                // Agregar estilo visual para indicar que está deshabilitado
                campo.style.backgroundColor = '#f5f5f5';
                campo.style.color = '#666';
            } else if (valorUnidosis > 0) {
                // Si entrada_unidosis > 0, manejar required solo para campos obligatorios
                if (campo.name === 'entrada_lote[]' || 
                    campo.name === 'entrada_caducidad[]' || 
                    campo.name === 'ubicacion_id[]') {
                    campo.setAttribute('required', 'required');
                }
                // Los campos descuento[] e iva[] NO son required - pueden estar vacíos
                
                // Restaurar estilo visual normal
                campo.style.backgroundColor = '';
                campo.style.color = '';
            }
        });
    }

    function validarTotalPorItem(inputModificado) {
        // Obtener la fila del input modificado
        const filaModificada = inputModificado.closest('tr');
        const itemId = filaModificada.querySelector('input[name="item_ids[]"]').value;
        const cantidadPendiente = parseInt(inputModificado.getAttribute('data-max')); // Ahora es la cantidad pendiente
        const cantidadSolicitada = parseInt(inputModificado.getAttribute('data-solicitada'));
        const cantidadYaEntregada = parseInt(inputModificado.getAttribute('data-entregada'));
        
        // Buscar todas las filas con el mismo item_id
        const todasLasFilas = document.querySelectorAll('tr');
        let totalUnidosisNuevas = 0;
        
        todasLasFilas.forEach(fila => {
            const inputItemId = fila.querySelector('input[name="item_ids[]"]');
            if (inputItemId && inputItemId.value === itemId) {
                const inputUnidosis = fila.querySelector('input[name="entrada_unidosis[]"]');
                if (inputUnidosis) {
                    totalUnidosisNuevas += parseInt(inputUnidosis.value) || 0;
                }
            }
        });
        
        // Verificar si el total de nuevas entregas excede lo pendiente
        if (totalUnidosisNuevas > cantidadPendiente) {
            const valorActual = parseInt(inputModificado.value) || 0;
            const totalAnterior = totalUnidosisNuevas - valorActual;
            const disponible = cantidadPendiente - totalAnterior;
            
            alert(`El total de unidosis para este item (${totalUnidosisNuevas}) excede la cantidad pendiente (${cantidadPendiente}).\n` +
                  `Cantidad solicitada: ${cantidadSolicitada}\n` +
                  `Ya entregado: ${cantidadYaEntregada}\n` +
                  `Pendiente: ${cantidadPendiente}\n` +
                  `Total sin este campo: ${totalAnterior}\n` +
                  `Disponible: ${disponible}\n\n` +
                  `Se ajustará automáticamente al máximo disponible.`);
            
            // Corregir automáticamente el valor al máximo disponible
            const valorCorregido = Math.max(0, disponible);
            inputModificado.value = valorCorregido;
            
            // Trigger change event para actualizar otros campos si es necesario
            inputModificado.dispatchEvent(new Event('change'));
            
            return false;
        }
        
        // Mostrar información útil al usuario
        const disponible = cantidadPendiente - totalUnidosisNuevas;
        if (disponible <= 5 && disponible > 0) {
            console.log(`Item ${itemId}: Quedan ${disponible} unidosis disponibles de ${cantidadPendiente} pendientes (Ya entregado: ${cantidadYaEntregada})`);
        }
        
        return true;
    }
    // Para evitar el envío del formulario si hay entradas inválidas
    document.getElementById("segundoFormulario").addEventListener("submit", function(event) {
        // Validar totales por item antes del envío
        if (!validarTotalesAntesDelEnvio()) {
            event.preventDefault();
            return;
        }
        
        const entradasUnidosis = document.getElementsByName("entrada_unidosis[]");
        for (const entradaUnidosis of entradasUnidosis) {
            const valorEntradaUnidosis = parseInt(entradaUnidosis.value) || 0;
            if (valorEntradaUnidosis < 0) {
                event.preventDefault(); // Evitar el envío del formulario
                alert("Por favor, corrige los valores de entrada. No se permiten valores negativos.");
                break; // Salir del ciclo si hay un error
            }
        }
    });

    function validarTotalesAntesDelEnvio() {
        // Crear un objeto para almacenar los totales por item_id
        const totalesPorItem = {};
        const pendientesPorItem = {};
        
        // Recorrer todas las filas para calcular totales por item
        const todasLasFilas = document.querySelectorAll('tr');
        
        todasLasFilas.forEach(fila => {
            const inputItemId = fila.querySelector('input[name="item_ids[]"]');
            const inputUnidosis = fila.querySelector('input[name="entrada_unidosis[]"]');
            
            if (inputItemId && inputUnidosis) {
                const itemId = inputItemId.value;
                const unidosis = parseInt(inputUnidosis.value) || 0;
                const pendiente = parseInt(inputUnidosis.getAttribute('data-max')) || 0; // Cantidad pendiente
                
                if (!totalesPorItem[itemId]) {
                    totalesPorItem[itemId] = 0;
                    pendientesPorItem[itemId] = pendiente;
                }
                
                totalesPorItem[itemId] += unidosis;
            }
        });
        
        // Verificar que ningún item exceda su pendiente
        for (const itemId in totalesPorItem) {
            if (totalesPorItem[itemId] > pendientesPorItem[itemId]) {
                alert(`El item ${itemId} tiene un total de ${totalesPorItem[itemId]} unidosis nuevas, pero solo quedan ${pendientesPorItem[itemId]} pendientes. Por favor, corrija las cantidades.`);
                return false;
            }
        }
        
        return true;
    }


    function calcularTotales() {
        var montos = document.getElementsByName('monto[]');
        var descuentos = document.getElementsByName('descuento[]');
        var ivas = document.getElementsByName('iva[]');
        var ivasCalculados = document.getElementsByName('iva_calculado[]');
        var totales = document.getElementsByName('total[]');
        var entradasUnidosis = document.getElementsByName('entrada_unidosis[]'); // Obtén el valor de entrada_unidosis[]
        var precios = document.getElementsByName('item_costo[]'); // Obtén los costos introducidos por el usuario

        var totalGeneral = 0;
        var totalMonto = 0;
        var totalIva = 0;
        var totalDescuento = 0;
        var TotalIva = 0;

        for (var i = 0; i < montos.length; i++) {
            var cantidadUnidosis = parseInt(entradasUnidosis[i].value) || 0; // Cantidad de unidosis
            var precioIntroducido = parseFloat(precios[i].value) || 0; // Precio introducido por el usuario

            // Calcular el monto directamente sin factor
            var monto = cantidadUnidosis * precioIntroducido;
            montos[i].value = monto.toFixed(2); // Asignar el valor calculado a 'monto[]'

            // Calcular descuento e IVA
            var descuento = parseFloat(descuentos[i].value) || 0;
            var iva = parseFloat(ivas[i].value) || 0;

            // Calcular el IVA por registro: monto * iva (ANTES del descuento)
            var ivaCalculadoPorRegistro = monto * iva;
            ivasCalculados[i].value = ivaCalculadoPorRegistro.toFixed(2); // Asignar el IVA calculado

            // Total del ítem: monto - descuento + iva
            var totalItem = monto - descuento + ivaCalculadoPorRegistro;
            totales[i].value = totalItem.toFixed(2);

            // Acumular en los totales generales
            totalGeneral += totalItem;
            totalMonto += monto;
            totalDescuento += descuento;

            // Sumar el IVA de este ítem al total de IVA
            TotalIva += ivaCalculadoPorRegistro;
        }

        // Mostrar el total general de la columna monto
        document.getElementById('totalMonto2').value = totalMonto.toFixed(2);
        document.getElementById('totalMonto').value = totalMonto.toFixed(2); // Mostrar en la tabla

        // Mostrar el total de descuentos
        document.getElementById('totalDescuento').value = totalDescuento.toFixed(2);
        document.getElementById('totalDescuentoCalculado').value = totalDescuento.toFixed(2); // Mostrar en la tabla

        // Mostrar el total de IVA
        document.getElementById('TotalIva2').value = TotalIva.toFixed(2);
        document.getElementById('totalIvaCalculado').value = TotalIva.toFixed(2); // Mostrar en la tabla
        
        document.getElementById('totalGeneral2').value = totalGeneral.toFixed(2); // Actualiza el campo oculto
        document.getElementById('totalGeneral').value = totalGeneral.toFixed(2); // Actualiza el campo visible

        // Mostrar el total de descuentos
        document.getElementById('totalDescuento').value = totalDescuento.toFixed(2);

        // Captura el valor del campo de factura del primer formulario
        const facturaInput = document.getElementById('facturaInput').value;

        let facturaGuardada = facturaInput;
        document.getElementById('facturaOculta').value = facturaGuardada;

        verificarTotales(); // Verifica si los totales coinciden
    }



    function verificarTotales() {
        // Obtener el valor de totalGeneral del segundo formulario
        const totalGeneral3 = parseFloat(document.getElementById('totalGeneral').value) || 0;

        // Verificar si se ha calculado el total en "Detalles de la Orden"
        const totalOrdenCalculado = totalGlobal > 0;

        // Comparar totalGlobal con totalGeneral usando una pequeña tolerancia para decimales
        const totalesCoinciden = Math.abs(totalGlobal - totalGeneral3) < 0.01;

        if (totalesCoinciden && totalOrdenCalculado) {
            document.getElementById('btnAfectarEntrada').disabled = false; // Habilitar el botón
            document.getElementById('btnAfectarEntrada').style.backgroundColor = '#2b2d7f';
            document.getElementById('btnAfectarEntrada').style.color = 'white';
            document.getElementById('btnAfectarEntrada').style.cursor = 'pointer';
            document.getElementById('mensajeError').style.display = 'none'; // Ocultar el mensaje de error
        } else {
            document.getElementById('btnAfectarEntrada').disabled = true; // Deshabilitar el botón
            document.getElementById('btnAfectarEntrada').style.backgroundColor = '#cccccc';
            document.getElementById('btnAfectarEntrada').style.color = '#666666';
            document.getElementById('btnAfectarEntrada').style.cursor = 'not-allowed';
            
            // Mostrar mensaje específico según el problema
            const mensajeError = document.getElementById('mensajeError');
            if (!totalOrdenCalculado) {
                mensajeError.textContent = 'Debe calcular primero el total en "Detalles de la Orden".';
                mensajeError.style.display = 'block';
            } else if (!totalesCoinciden) {
                mensajeError.textContent = 'Los totales no coinciden.';
                mensajeError.style.display = 'block';
            }
        }
    }

    // Función para agregar una nueva fila de lote
    function agregarFilaLote(boton) {
        // Obtener la fila actual
        const filaActual = boton.closest('tr');
        
        // Clonar la fila actual
        const nuevaFila = filaActual.cloneNode(true);
        
        // Limpiar los valores de los inputs en la nueva fila (excepto los readonly)
        const inputs = nuevaFila.querySelectorAll('input[type="text"], input[type="date"]');
        inputs.forEach(input => {
            if (!input.hasAttribute('readonly')) {
                input.value = '';
                // Si es un campo condicional, remover required y aplicar estilo deshabilitado
                if (input.classList.contains('campo-condicional')) {
                    input.removeAttribute('required');
                    input.style.backgroundColor = '#f5f5f5';
                    input.style.color = '#666';
                }
            }
        });
        
        // Configurar el select de ubicación para que tenga la primera ubicación seleccionada por defecto
        const selectUbicacion = nuevaFila.querySelector('select[name="ubicacion_id[]"]');
        if (selectUbicacion) {
            selectUbicacion.selectedIndex = 1; // Seleccionar la primera ubicación real (índice 1, ya que índice 0 es el placeholder)
            // Si es un campo condicional, remover required y aplicar estilo deshabilitado
            if (selectUbicacion.classList.contains('campo-condicional')) {
                selectUbicacion.removeAttribute('required');
                selectUbicacion.style.backgroundColor = '#f5f5f5';
                selectUbicacion.style.color = '#666';
            }
        }
        
        // Asegurar que el nuevo input de unidosis tenga la validación y atributos correctos
        const inputUnidosis = nuevaFila.querySelector('input[name="entrada_unidosis[]"]');
        const inputUnidosisOriginal = filaActual.querySelector('input[name="entrada_unidosis[]"]');
        if (inputUnidosis && inputUnidosisOriginal) {
            inputUnidosis.setAttribute('oninput', 'validarEntradaEnTiempoReal(this);');
            inputUnidosis.setAttribute('onblur', 'validarEntradaFinal(this);');
            inputUnidosis.setAttribute('data-max', inputUnidosisOriginal.getAttribute('data-max'));
            inputUnidosis.setAttribute('data-solicitada', inputUnidosisOriginal.getAttribute('data-solicitada'));
            inputUnidosis.setAttribute('data-entregada', inputUnidosisOriginal.getAttribute('data-entregada'));
        }
        
        // Cambiar el texto del botón de agregar por eliminar en la nueva fila
        const botonAccion = nuevaFila.querySelector('.btn-agregar-lote');
        botonAccion.className = 'btn-eliminar-lote';
        botonAccion.innerHTML = '➖';
        botonAccion.title = 'Eliminar este lote';
        botonAccion.setAttribute('onclick', 'eliminarFilaLote(this)');
        
        // Insertar la nueva fila después de la fila actual
        filaActual.parentNode.insertBefore(nuevaFila, filaActual.nextSibling);
    }

    // Función para eliminar una fila de lote
    function eliminarFilaLote(boton) {
        const fila = boton.closest('tr');
        fila.remove();
        
        // Recalcular totales después de eliminar la fila
        calcularTotales();
    }
</script>



</body>


</html>

<?php
// ===== NUEVA FUNCIONALIDAD: CONSULTA DE TRANSACCIONES CON ENTREGAS PARCIALES =====

// Función para obtener transacciones mostrando entregas parciales agrupadas
function obtenerTransaccionesConEntregasParciales($conexion, $id_compra = null) {
    $where_clause = $id_compra ? "WHERE t.id_compra = ?" : "";
    
    // Query para obtener todos los items y determinar si son parciales o completos
    $query = "SELECT 
        t.item_id,
        t.id_compra,
        ia.item_name,
        SUM(t.entrada_qty) as cantidad_entregada_total,
        oc.solicita as cantidad_solicitada,
        t.parcial,
        COUNT(*) as num_registros
    FROM transacciones t
    JOIN item_almacen ia ON t.item_id = ia.item_id
    LEFT JOIN orden_compra oc ON t.id_compra = oc.id_compra AND t.item_id = oc.item_id
    $where_clause
    GROUP BY t.item_id, t.id_compra, ia.item_name, oc.solicita, t.parcial
    ORDER BY t.id_compra, t.item_id";
    
    $stmt = $conexion->prepare($query);
    if ($id_compra) {
        $stmt->bind_param('i', $id_compra);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    $transacciones = [];
    while ($row = $result->fetch_assoc()) {
        if ($row['parcial'] === 'SI') {
            // Para entregas parciales: solo mostrar un registro agrupado sumando cantidades
            $transacciones[] = [
                'tipo' => 'agrupado',
                'item_id' => $row['item_id'],
                'item_name' => $row['item_name'],
                'cantidad_entregada' => $row['cantidad_entregada_total'],
                'cantidad_solicitada' => $row['cantidad_solicitada'],
                'parcial' => $row['parcial'],
                'id_compra' => $row['id_compra'],
                'fecha' => 'AGRUPADO'
            ];
        } else {
            // Para entregas completas: mostrar todos los registros individuales
            $query_individuales = "SELECT 
                t.*,
                ia.item_name,
                oc.solicita as cantidad_solicitada
            FROM transacciones t
            JOIN item_almacen ia ON t.item_id = ia.item_id
            LEFT JOIN orden_compra oc ON t.id_compra = oc.id_compra AND t.item_id = oc.item_id
            WHERE t.item_id = ? AND t.id_compra = ? AND t.parcial = 'NO'
            ORDER BY t.entrada_lote";
            
            $stmt_ind = $conexion->prepare($query_individuales);
            $stmt_ind->bind_param('ii', $row['item_id'], $row['id_compra']);
            $stmt_ind->execute();
            $result_ind = $stmt_ind->get_result();
            
            while ($row_ind = $result_ind->fetch_assoc()) {
                $transacciones[] = [
                    'tipo' => 'individual',
                    'registro' => $row_ind
                ];
            }
        }
    }
    
    return $transacciones;
}

// Verificar si se solicita ver transacciones
if (isset($_GET['ver_transacciones'])) {
    $id_compra_consulta = isset($_GET['id_compra_transacciones']) ? (int)$_GET['id_compra_transacciones'] : null;
    $transacciones = obtenerTransaccionesConEntregasParciales($conexion, $id_compra_consulta);
    
    echo "<div style='background-color: white; padding: 50px; border-radius: 30px;margin-top: 15px;'>";
    echo "<h2 style='margin-top: 40px;color: #2b2d7f;text-align: center;'>Historial de Transacciones</h2>";
    
    // Formulario de filtrado
    echo "<div style='text-align: center; margin: 20px 0; padding: 20px; background-color: #f8f9fa; border-radius: 10px;'
        <form method='GET' style='display: inline-flex; align-items: center; gap: 10px;'>
            <input type='hidden' name='ver_transacciones' value='1'>
            <label for='id_compra_transacciones' style='font-weight: bold;'>Filtrar por ID Compra:</label>
            <input type='number' name='id_compra_transacciones' id='id_compra_transacciones' 
                   value='" . ($id_compra_consulta ?? '') . "' 
                   placeholder='Ingrese ID de compra' 
                   style='padding: 5px 10px; border: 1px solid #ccc; border-radius: 4px;'>
            <button type='submit' style='color: white; background-color: #2b2d7f; border: none; border-radius: 5px; padding: 5px 15px; cursor: pointer;'>
                Filtrar
            </button>
            <a href='" . $_SERVER['PHP_SELF'] . "?ver_transacciones=1' style='color: white; background-color: #6c757d; border: none; border-radius: 5px; padding: 5px 15px; text-decoration: none;'>
                Ver Todas
            </a>
        </form>
    </div>";
    
    if (!empty($transacciones)) {
        echo "<table class='carrito-table' style='width: 100%; margin-top: 20px; border-collapse: collapse;'>

        <thead>
            <tr style='background-color: #2b2d7f; color: white;'>
                <th style='padding: 10px; border: 1px solid #ddd;'>ID Compra</th>
                <th style='padding: 10px; border: 1px solid #ddd;'>Ítem</th>
                <th style='padding: 10px; border: 1px solid #ddd;'>Lote</th>
                <th style='padding: 10px; border: 1px solid #ddd;'>Cantidad Entregada</th>
                <th style='padding: 10px; border: 1px solid #ddd;'>Cantidad Solicitada</th>
                <th style='padding: 10px; border: 1px solid #ddd;'>Estado de Entrega</th>
                <th style='padding: 10px; border: 1px solid #ddd;'>Fecha</th>
                <th style='padding: 10px; border: 1px solid #ddd;'>Ubicación</th>
                <th style='padding: 10px; border: 1px solid #ddd;'>Costo Total</th>
            </tr>
        </thead>
        <tbody>";
        
        foreach ($transacciones as $transaccion) {
            if ($transaccion['tipo'] === 'agrupado') {
                echo "<tr style='background-color: #fff3cd;'>
                    <td style='padding: 8px; border: 1px solid #ddd; text-align: center;'>{$transaccion['id_compra']}</td>
                    <td style='padding: 8px; border: 1px solid #ddd;'><strong>{$transaccion['item_name']}</strong></td>
                    <td style='padding: 8px; border: 1px solid #ddd; text-align: center;'><span style='color: orange; font-weight: bold;'>MÚLTIPLES LOTES</span></td>
                    <td style='padding: 8px; border: 1px solid #ddd; text-align: center;'><strong style='color: orange;'>{$transaccion['cantidad_entregada']}</strong></td>
                    <td style='padding: 8px; border: 1px solid #ddd; text-align: center;'><strong>{$transaccion['cantidad_solicitada']}</strong></td>
                    <td style='padding: 8px; border: 1px solid #ddd; text-align: center;'><span style='color: orange; font-weight: bold; padding: 3px 6px; background-color: #fff3cd; border: 1px solid orange; border-radius: 3px;'>PARCIAL</span></td>
                    <td style='padding: 8px; border: 1px solid #ddd; text-align: center;'>AGRUPADO</td>
                    <td style='padding: 8px; border: 1px solid #ddd; text-align: center;'>--</td>
                    <td style='padding: 8px; border: 1px solid #ddd; text-align: center;'>--</td>
                </tr>";
            } else {
                $reg = $transaccion['registro'];
                echo "<tr style='background-color: #d4edda;'>
                    <td style='padding: 8px; border: 1px solid #ddd; text-align: center;'>{$reg['id_compra']}</td>
                    <td style='padding: 8px; border: 1px solid #ddd;'>{$reg['item_name']}</td>
                    <td style='padding: 8px; border: 1px solid #ddd; text-align: center;'>{$reg['entrada_lote']}</td>
                    <td style='padding: 8px; border: 1px solid #ddd; text-align: center;'>{$reg['entrada_qty']}</td>
                    <td style='padding: 8px; border: 1px solid #ddd; text-align: center;'>{$reg['cantidad_solicitada']}</td>
                    <td style='padding: 8px; border: 1px solid #ddd; text-align: center;'><span style='color: green; font-weight: bold; padding: 3px 6px; background-color: #d4edda; border: 1px solid green; border-radius: 3px;'>COMPLETO</span></td>
                    <td style='padding: 8px; border: 1px solid #ddd; text-align: center;'>{$reg['entrada_fecha']}</td>
                    <td style='padding: 8px; border: 1px solid #ddd; text-align: center;'>{$reg['ubicacion_id']}</td>
                    <td style='padding: 8px; border: 1px solid #ddd; text-align: center;'>\${$reg['Total']}</td>
                </tr>";
            }
        }
        
        echo "</tbody></table>";
    } else {
        echo "<p style='text-align: center; color: #666;'>No se encontraron transacciones.</p>";
    }
    
    echo "<div style='text-align: center; margin-top: 20px;'>
        <a href='" . $_SERVER['PHP_SELF'] . "' style='color: white; background-color: #2b2d7f; border: none; border-radius: 5px; padding: 10px 20px; text-decoration: none;'>Volver al Formulario</a>
    </div>";
    echo "</div>";
    exit();
}

// ===== FIN DE NUEVA FUNCIONALIDAD =====
?>