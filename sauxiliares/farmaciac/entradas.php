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
    * {
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #0a0a0a 100%) !important;
        font-family: 'Roboto', sans-serif !important;
        min-height: 100vh;
    }

    /* Efecto de partículas en el fondo */
    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image:
            radial-gradient(circle at 20% 50%, rgba(64, 224, 255, 0.03) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(64, 224, 255, 0.03) 0%, transparent 50%),
            radial-gradient(circle at 40% 20%, rgba(64, 224, 255, 0.02) 0%, transparent 50%);
        pointer-events: none;
        z-index: 0;
    }

    .wrapper {
        position: relative;
        z-index: 1;
    }

    /* ===== VARIABLES CSS ===== */
    :root {
        --color-primario: #40E0FF;
        --color-secundario: #0f3460;
        --color-fondo: rgba(22, 33, 62, 0.9);
        --color-borde: rgba(64, 224, 255, 0.3);
        --sombra: 0 8px 30px rgba(0, 0, 0, 0.5);
    }

    /* Header personalizado */
    .main-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
        border-bottom: 2px solid #40E0FF !important;
        box-shadow: 0 4px 20px rgba(64, 224, 255, 0.2);
    }

    .main-header .logo {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-right: 2px solid #40E0FF !important;
        color: #40E0FF !important;
    }

    .main-header .navbar {
        background: transparent !important;
    }

    /* Header table */
    .headt {
        width: 100%;
    }

    /* Sidebar personalizado */
    .main-sidebar {
        background: linear-gradient(180deg, #16213e 0%, #0f3460 100%) !important;
        border-right: 2px solid #40E0FF !important;
        box-shadow: 4px 0 20px rgba(64, 224, 255, 0.15);
    }

    .sidebar-menu > li > a {
        color: #ffffff !important;
        border-left: 3px solid transparent;
        transition: all 0.3s ease;
    }

    .sidebar-menu > li > a:hover,
    .sidebar-menu > li.active > a {
        background: rgba(64, 224, 255, 0.1) !important;
        border-left: 3px solid #40E0FF !important;
        color: #40E0FF !important;
    }

    /* Treeview - tamaño de fuente */
    .treeview {
        font-size: 13.3px;
    }

    .treeview-menu > li > a {
        color: rgba(255, 255, 255, 0.9) !important;
        transition: all 0.3s ease;
    }

    .treeview-menu > li > a:hover {
        color: #40E0FF !important;
        background: rgba(64, 224, 255, 0.05) !important;
    }

    /* Separador del menú treeview */
    .treeview-menu-separator {
        padding: 10px 15px;
        font-weight: bold;
        color: #40E0FF !important;
        cursor: default;
        background: linear-gradient(135deg, rgba(64, 224, 255, 0.1) 0%, rgba(64, 224, 255, 0.05) 100%) !important;
        border-top: 1px solid rgba(64, 224, 255, 0.3);
        border-bottom: 1px solid rgba(64, 224, 255, 0.3);
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
    }

    .user-panel {
        border-bottom: 1px solid rgba(64, 224, 255, 0.2);
    }

    .user-panel .info {
        color: #ffffff !important;
    }

    /* Content wrapper */
    .content-wrapper {
        background: transparent !important;
        min-height: 100vh;
    }

    /* Dropdown menu */
    .dropdwn {
        float: left;
        overflow: hidden;
    }

    .dropdwn .dropbtn {
        cursor: pointer;
        font-size: 16px;
        border: none;
        outline: none;
        color: white;
        padding: 14px 16px;
        background-color: inherit;
        font-family: inherit;
        margin: 0;
        transition: all 0.3s ease;
    }

    .navbar a:hover,
    .dropdwn:hover .dropbtn,
    .dropbtn:focus {
        background-color: rgba(64, 224, 255, 0.2);
    }

    .dropdwn-content {
        display: none;
        position: absolute;
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(64, 224, 255, 0.3);
        z-index: 1;
        border: 1px solid #40E0FF;
        border-radius: 10px;
    }

    .dropdwn-content a {
        float: none;
        color: #ffffff !important;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        text-align: left;
        transition: all 0.3s ease;
    }

    .dropdwn-content a:hover {
        background: rgba(64, 224, 255, 0.2) !important;
        color: #40E0FF !important;
    }

    .dropdwn:hover .dropdwn-content {
        display: block;
    }

    .show {
        display: block;
    }

    /* Breadcrumb mejorado */
    .breadcrumb {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 20px !important;
        padding: 25px !important;
        margin-bottom: 40px !important;
        box-shadow: 0 8px 30px rgba(64, 224, 255, 0.3);
        position: relative;
        overflow: hidden;
    }

    .breadcrumb::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(64, 224, 255, 0.1) 0%, transparent 70%);
        animation: pulse 3s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }

    .breadcrumb h4 {
        color: #ffffff !important;
        font-weight: 700 !important;
        margin: 0;
        font-size: 28px !important;
        text-transform: uppercase;
        letter-spacing: 2px;
        text-shadow: 0 0 20px rgba(64, 224, 255, 0.5);
        position: relative;
        z-index: 1;
    }

    /* ===== CONTENEDORES MODERNOS ===== */
    .content {
        padding: 20px;
    }

    .container-fluid {
        max-width: 1200px;
        margin: 0 auto;
    }

    .container {
        max-width: 1140px;
        margin: 0 auto;
        padding: 0 15px;
    }

    .container-moderno {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.95) 0%, rgba(15, 52, 96, 0.95) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.4) !important;
        border-radius: 20px;
        padding: 30px;
        margin: 20px auto;
        max-width: 98%;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6), 0 0 30px rgba(64, 224, 255, 0.2);
        color: #ffffff !important;
    }

    /* Contenedor de farmacia */
    .farmacia-container {
        padding: 30px;
        background: transparent !important;
        min-height: 100vh;
        margin: 0;
    }

    /* Row y columnas */
    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }

    .col, .col-1, .col-2, .col-3, .col-4, .col-5, .col-6,
    .col-7, .col-8, .col-9, .col-10, .col-11, .col-12,
    .col-sm, .col-md, .col-lg, .col-xl {
        position: relative;
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
    }

    /* ===== HEADER PRINCIPAL ===== */
    .header-principal {
        text-align: center;
        margin-bottom: 40px;
        padding: 30px 0;
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
        border-radius: 20px;
        color: white;
        box-shadow: 0 8px 30px rgba(64, 224, 255, 0.3);
        position: relative;
        border: 2px solid #40E0FF;
    }

    .header-principal .icono-principal {
        font-size: 48px;
        margin-bottom: 15px;
        display: block;
        color: #40E0FF;
        text-shadow: 0 0 20px rgba(64, 224, 255, 0.8);
    }

    .header-principal h1 {
        font-size: 32px;
        font-weight: 700;
        margin: 0;
        text-shadow: 0 0 15px rgba(64, 224, 255, 0.5);
    }

    .btn-ajuste {
        position: absolute;
        top: 50%;
        right: 30px;
        transform: translateY(-50%);
    }

    /* ===== CONTENEDOR DE FILTROS ===== */
    .contenedor-filtros {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 15px;
        padding: 25px;
        margin: 30px 0;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
    }

    /* ===== TABLAS CYBERPUNK ===== */
    .table-container,
    .tabla-contenedor {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
        border: 2px solid rgba(64, 224, 255, 0.3);
        max-height: 80vh;
        overflow-y: auto;
        width: 100%;
    }

    table,
    .table,
    .table-moderna {
        width: 100%;
        margin-bottom: 1rem;
        background: transparent;
        border-collapse: separate;
        border-spacing: 0;
        color: #ffffff !important;
    }

    .table-bordered {
        border: 2px solid rgba(64, 224, 255, 0.4);
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background: rgba(64, 224, 255, 0.05);
    }

    .table-hover tbody tr:hover,
    .table-moderna tbody tr:hover {
        background: rgba(64, 224, 255, 0.1);
        transform: scale(1.01);
        transition: all 0.3s ease;
    }

    thead,
    .table-moderna thead {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
        border-bottom: 2px solid #40E0FF;
    }

    thead th,
    .table-moderna thead th {
        color: #40E0FF !important;
        font-weight: 700;
        text-transform: uppercase;
        padding: 15px 10px !important;
        border: none !important;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
        font-size: 11px;
        letter-spacing: 1px;
        position: sticky;
        top: 0;
        z-index: 10;
        text-align: center;
    }

    thead th i,
    .table-moderna thead th i {
        margin-right: 5px;
    }

    tbody,
    .table-moderna tbody {
        color: #ffffff !important;
    }

    tbody td,
    .table-moderna tbody td {
        padding: 10px 8px !important;
        border: 1px solid rgba(64, 224, 255, 0.2) !important;
        vertical-align: middle;
        text-align: center;
        white-space: nowrap;
    }

    tbody tr,
    .table-moderna tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid rgba(64, 224, 255, 0.1);
    }

    th, td {
        padding: 12px 15px !important;
        text-align: center;
        border: 1px solid rgba(64, 224, 255, 0.2) !important;
    }

    /* Columnas con anchos específicos */
    .col-seleccionar {
        width: 50px;
        text-align: center;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .col-id {
        width: 60px;
        text-align: center;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .col-itemid {
        width: 60px;
        text-align: center;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .col-medicamentos {
        width: 128px;
        text-align: center;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .col-fecha {
        width: 100px;
        text-align: center;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .col-almacen {
        width: 110px;
        text-align: center;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .col-solicitan {
        width: 90px;
        text-align: center;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .col-lote {
        width: 98px;
        text-align: center;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .col-caducidad {
        width: 100px;
        text-align: center;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .col-existencias {
        width: 150px;
        text-align: center;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .col-surtir {
        width: 100px;
        text-align: center;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .col-parcial {
        width: 83px;
        text-align: center;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    /* Celdas especiales */
    td.fondosan {
        background: linear-gradient(135deg, #5c1a1a 0%, #3a0f0f 100%) !important;
        color: #ffffff !important;
        border: 1px solid rgba(239, 68, 68, 0.5) !important;
        font-weight: 600;
        text-shadow: 0 0 10px rgba(239, 68, 68, 0.5);
    }

    /* ===== INPUTS UNIFORMES ===== */
    .input-uniform {
        width: 100%;
        box-sizing: border-box;
        padding: 5px;
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 8px !important;
        color: #ffffff !important;
        transition: all 0.3s ease !important;
    }

    .input-uniform:focus {
        border-color: #40E0FF !important;
        box-shadow: 0 0 15px rgba(64, 224, 255, 0.4) !important;
        outline: none !important;
    }

    /* ===== FORMULARIOS CYBERPUNK ===== */
    .form-control {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 10px !important;
        color: #ffffff !important;
        padding: 12px 15px !important;
        transition: all 0.3s ease !important;
    }

    .form-control:focus {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border-color: #40E0FF !important;
        box-shadow: 0 0 20px rgba(64, 224, 255, 0.4) !important;
        color: #ffffff !important;
        outline: none !important;
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.5) !important;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label,
    .form-label {
        color: #40E0FF !important;
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="number"],
    input[type="tel"],
    input[type="date"],
    input[type="time"],
    input[type="datetime-local"],
    textarea,
    select {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 10px !important;
        color: #ffffff !important;
        padding: 10px 15px !important;
        transition: all 0.3s ease !important;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus,
    input[type="number"]:focus,
    input[type="tel"]:focus,
    input[type="date"]:focus,
    input[type="time"]:focus,
    input[type="datetime-local"]:focus,
    textarea:focus,
    select:focus {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border-color: #40E0FF !important;
        box-shadow: 0 0 20px rgba(64, 224, 255, 0.4) !important;
        outline: none !important;
    }

    select option {
        background: #16213e !important;
        color: #ffffff !important;
    }

    /* Checkbox y Radio */
    input[type="checkbox"],
    input[type="radio"] {
        width: 18px;
        height: 18px;
        border: 2px solid rgba(64, 224, 255, 0.5);
        accent-color: #40E0FF;
    }

    /* ===== BOXES Y PANELS ===== */
    .box {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 15px !important;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .box-header {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-bottom: 2px solid #40E0FF !important;
        padding: 15px !important;
    }

    .box-header h3,
    .box-header .box-title {
        color: #40E0FF !important;
        font-weight: 700;
        margin: 0;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
    }

    .box-body {
        padding: 20px !important;
        color: #ffffff !important;
    }

    .box-footer {
        background: rgba(15, 52, 96, 0.5) !important;
        border-top: 1px solid rgba(64, 224, 255, 0.3) !important;
        padding: 15px !important;
    }

    /* Panel similar a box */
    .panel {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 15px !important;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
        margin-bottom: 20px;
    }

    .panel-heading {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-bottom: 2px solid #40E0FF !important;
        padding: 15px !important;
        color: #40E0FF !important;
        font-weight: 700;
    }

    .panel-body {
        padding: 20px !important;
        color: #ffffff !important;
    }

    .panel-footer {
        background: rgba(15, 52, 96, 0.5) !important;
        border-top: 1px solid rgba(64, 224, 255, 0.3) !important;
        padding: 15px !important;
    }

    /* WELL */
    .well {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 15px !important;
        padding: 20px !important;
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    /* ===== BADGES Y LABELS ===== */
    .badge,
    .label {
        background: linear-gradient(135deg, #40E0FF 0%, #0f3460 100%) !important;
        color: #ffffff !important;
        padding: 5px 10px;
        border-radius: 12px;
        font-weight: 600;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
        box-shadow: 0 2px 8px rgba(64, 224, 255, 0.3);
    }

    .badge-primary,
    .label-primary {
        background: linear-gradient(135deg, #40E0FF 0%, #0f3460 100%) !important;
    }

    .badge-success,
    .label-success {
        background: linear-gradient(135deg, #4ade80 0%, #1a4a2e 100%) !important;
    }

    .badge-warning,
    .label-warning {
        background: linear-gradient(135deg, #fbbf24 0%, #5c4a1a 100%) !important;
    }

    .badge-danger,
    .label-danger {
        background: linear-gradient(135deg, #ef4444 0%, #5c1a1a 100%) !important;
    }

    .badge-info,
    .label-info {
        background: linear-gradient(135deg, #818cf8 0%, #2e2e5c 100%) !important;
    }

    /* ===== CUADROS DE ESTADO ===== */
    .cuadro {
        width: 15px;
        height: 15px;
        display: inline-block;
        margin-right: 10px;
        border-radius: 5px;
        border: 1px solid rgba(64, 224, 255, 0.3);
    }

    .en-espera {
        background: linear-gradient(135deg, #8eb5f0ff 0%, #6a9dd8 100%);
        box-shadow: 0 0 10px rgba(142, 181, 240, 0.5);
    }

    .entrega-parcial {
        background: linear-gradient(135deg, #b3cef7ff 0%, #91b8f0 100%);
        box-shadow: 0 0 10px rgba(179, 206, 247, 0.5);
    }

    .nuevo-surtimiento {
        background: linear-gradient(135deg, #e6f0ff 0%, #c4dcf7 100%);
        box-shadow: 0 0 10px rgba(230, 240, 255, 0.5);
    }

    .texto {
        display: inline-block;
        font-size: 12px;
        font-weight: bold;
        color: #ffffff;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
    }

    /* ===== PROGRESS BARS ===== */
    .progress {
        background: rgba(22, 33, 62, 0.8) !important;
        border: 1px solid rgba(64, 224, 255, 0.3);
        border-radius: 10px;
        height: 25px;
        overflow: hidden;
        box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.5);
    }

    .progress-bar {
        background: linear-gradient(135deg, #40E0FF 0%, #0f3460 100%) !important;
        box-shadow: 0 0 15px rgba(64, 224, 255, 0.6);
        transition: width 0.6s ease;
        line-height: 25px;
        color: #ffffff;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
    }

    .progress-bar-success {
        background: linear-gradient(135deg, #4ade80 0%, #1a4a2e 100%) !important;
        box-shadow: 0 0 15px rgba(74, 222, 128, 0.6);
    }

    .progress-bar-warning {
        background: linear-gradient(135deg, #fbbf24 0%, #5c4a1a 100%) !important;
        box-shadow: 0 0 15px rgba(251, 191, 36, 0.6);
    }

    .progress-bar-danger {
        background: linear-gradient(135deg, #ef4444 0%, #5c1a1a 100%) !important;
        box-shadow: 0 0 15px rgba(239, 68, 68, 0.6);
    }

    /* ===== PAGINACIÓN MODERNA ===== */
    .pagination,
    .contenedor-paginacion {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
        margin: 20px 0;
    }

    .paginacion-moderna {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .pagination li {
        margin: 0 3px;
    }

    .pagination li a,
    .pagination li span,
    .btn-paginacion {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        color: #ffffff !important;
        padding: 8px 15px;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 45px;
        height: 45px;
        font-weight: 600;
    }

    .pagination li a:hover,
    .btn-paginacion:hover {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-color: #40E0FF !important;
        box-shadow: 0 0 15px rgba(64, 224, 255, 0.5);
        transform: translateY(-2px);
        text-decoration: none;
    }

    .pagination li.active a,
    .pagination li.active span,
    .btn-paginacion.active {
        background: linear-gradient(135deg, #40E0FF 0%, #0f3460 100%) !important;
        border-color: #40E0FF !important;
        box-shadow: 0 0 20px rgba(64, 224, 255, 0.6);
    }

    /* ===== TABS ===== */
    .nav-tabs {
        border-bottom: 2px solid rgba(64, 224, 255, 0.3);
    }

    .nav-tabs > li > a {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-bottom: none !important;
        color: #ffffff !important;
        border-radius: 10px 10px 0 0 !important;
        margin-right: 5px;
        transition: all 0.3s ease;
    }

    .nav-tabs > li > a:hover {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-color: #40E0FF !important;
    }

    .nav-tabs > li.active > a {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-color: #40E0FF !important;
        color: #40E0FF !important;
        box-shadow: 0 -3px 15px rgba(64, 224, 255, 0.3);
    }

    .tab-content {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-top: none !important;
        padding: 20px;
        border-radius: 0 0 10px 10px;
        color: #ffffff !important;
    }

    /* ===== TOOLTIPS ===== */
    .tooltip-inner {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border: 1px solid #40E0FF;
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(64, 224, 255, 0.5);
        padding: 8px 12px;
        border-radius: 8px;
    }

    /* ===== POPOVERS ===== */
    .popover {
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border: 2px solid #40E0FF !important;
        box-shadow: 0 8px 30px rgba(64, 224, 255, 0.4);
        border-radius: 10px;
    }

    .popover-title {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        color: #40E0FF !important;
        border-bottom: 1px solid #40E0FF !important;
    }

    .popover-content {
        color: #ffffff !important;
    }

    /* ===== CARDS PEQUEÑAS INFO ===== */
    .info-box {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 15px;
        padding: 15px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        min-height: 90px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }

    .info-box:hover {
        border-color: #40E0FF !important;
        box-shadow: 0 10px 40px rgba(64, 224, 255, 0.4);
        transform: translateY(-5px);
    }

    .info-box-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 70px;
        height: 70px;
        border-radius: 10px;
        background: rgba(64, 224, 255, 0.2);
        border: 2px solid #40E0FF;
        margin-right: 15px;
    }

    .info-box-icon i {
        font-size: 35px;
        color: #40E0FF;
    }

    .info-box-content {
        flex: 1;
        color: #ffffff;
    }

    .info-box-text {
        text-transform: uppercase;
        font-weight: 600;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.8);
    }

    .info-box-number {
        font-size: 24px;
        font-weight: 700;
        color: #40E0FF;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
    }

    /* ===== SMALL BOX ===== */
    .small-box {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
        position: relative;
        margin-bottom: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .small-box:hover {
        border-color: #40E0FF !important;
        box-shadow: 0 10px 40px rgba(64, 224, 255, 0.4);
        transform: translateY(-5px);
    }

    .small-box h3 {
        color: #40E0FF !important;
        font-size: 38px;
        font-weight: 700;
        margin: 0 0 10px 0;
        text-shadow: 0 0 15px rgba(64, 224, 255, 0.6);
    }

    .small-box p {
        color: #ffffff;
        font-size: 14px;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .small-box .icon {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 70px;
        color: rgba(64, 224, 255, 0.3);
    }

    .small-box .small-box-footer {
        display: block;
        padding: 10px 0;
        margin-top: 10px;
        text-align: center;
        border-top: 1px solid rgba(64, 224, 255, 0.3);
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .small-box .small-box-footer:hover {
        color: #40E0FF;
        background: rgba(64, 224, 255, 0.1);
    }

    /* ===== LISTA DE GRUPOS ===== */
    .list-group {
        border-radius: 10px;
        overflow: hidden;
    }

    .list-group-item {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border: 1px solid rgba(64, 224, 255, 0.3) !important;
        color: #ffffff !important;
        padding: 12px 20px;
        transition: all 0.3s ease;
    }

    .list-group-item:hover {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-color: #40E0FF !important;
        transform: translateX(5px);
    }

    .list-group-item.active {
        background: linear-gradient(135deg, #40E0FF 0%, #0f3460 100%) !important;
        border-color: #40E0FF !important;
        box-shadow: 0 0 20px rgba(64, 224, 255, 0.5);
    }

    /* ===== MENSAJE SIN RESULTADOS ===== */
    .mensaje-sin-resultados {
        text-align: center;
        padding: 50px 20px;
        color: #40E0FF;
        font-size: 18px;
        font-weight: 600;
    }

    .mensaje-sin-resultados i {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
        color: #40E0FF;
    }

    /* Todo Container - Estilo Kanban cyberpunk */
    .todo-container {
        max-width: 15000px;
        height: auto;
        display: flex;
        overflow-y: scroll;
        overflow-x: auto;
        column-gap: 0.5em;
        column-rule: 2px solid rgba(64, 224, 255, 0.3);
        column-width: 140px;
        column-count: 7;
        padding: 10px;
    }

    /* Scrollbar para todo-container */
    .todo-container::-webkit-scrollbar {
        height: 12px;
        width: 12px;
    }

    .todo-container::-webkit-scrollbar-track {
        background: rgba(10, 10, 10, 0.5);
        border-radius: 10px;
    }

    .todo-container::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #40E0FF 0%, #0f3460 100%);
        border-radius: 10px;
    }

    .todo-container::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #00D9FF 0%, #40E0FF 100%);
    }

    .status {
        width: 25%;
        min-width: 250px;
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 15px;
        position: relative;
        padding: 60px 1rem 0.5rem;
        height: 100%;
        box-shadow: 0 8px 30px rgba(64, 224, 255, 0.2);
        margin-right: 10px;
    }

    .status h4 {
        position: absolute;
        top: 0;
        left: 0;
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        color: #ffffff !important;
        margin: 0;
        width: 100%;
        padding: 0.5rem 1rem;
        border-radius: 13px 13px 0 0;
        border-bottom: 2px solid #40E0FF;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
        font-weight: 600;
        font-size: 16px;
        text-align: center;
    }

    /* Estilos para alertas/tarjetas de pacientes */
    .alert {
        padding: 15px 40px 15px 15px;
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
        border: 1px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 10px;
        margin-bottom: 10px;
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
        position: relative;
    }

    .alert:hover {
        border-color: #40E0FF !important;
        box-shadow: 0 6px 20px rgba(64, 224, 255, 0.4);
        transform: translateX(5px);
    }

    .alert-success {
        border-color: rgba(74, 222, 128, 0.5) !important;
        background: linear-gradient(135deg, rgba(26, 74, 46, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
    }

    .alert-warning {
        border-color: rgba(251, 191, 36, 0.5) !important;
        background: linear-gradient(135deg, rgba(92, 74, 26, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
    }

    .alert-danger {
        border-color: rgba(239, 68, 68, 0.5) !important;
        background: linear-gradient(135deg, rgba(92, 26, 26, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
    }

    .alert-info {
        border-color: rgba(129, 140, 248, 0.5) !important;
        background: linear-gradient(135deg, rgba(46, 46, 92, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
    }

    /* Botón de cerrar alert */
    .alert .close {
        color: #40E0FF !important;
        opacity: 1;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.8);
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
    }

    /* Nombre del paciente */
    .nompac {
        font-size: 11.5px;
        position: absolute;
        color: #ffffff !important;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
    }

    .nod {
        font-size: 10.3px;
        color: rgba(255, 255, 255, 0.9) !important;
    }

    /* Tarjetas de contenido */
    .ancholi {
        margin-top: 1px;
        margin-bottom: 10px;
        width: 175px;
        height: 100px;
        display: inline-block;
    }

    .ancholi2 {
        width: 170px;
        height: 97px;
        display: inline-block;
        box-shadow: 0 5px 15px rgba(64, 224, 255, 0.3);
        border: 1px solid rgba(64, 224, 255, 0.2);
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%);
        transition: all 0.3s ease;
    }

    .ancholi2:hover {
        box-shadow: 0 8px 25px rgba(64, 224, 255, 0.5);
        border-color: #40E0FF;
        transform: scale(1.05);
    }

    /* Tarjetas modernas cyberpunk - Estilo base */
    .modern-card,
    .farmacia-card {
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 25px !important;
        padding: 40px 20px;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5),
                    0 0 30px rgba(64, 224, 255, 0.2) !important;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        position: relative;
        overflow: hidden;
        min-height: 280px;
        margin: 20px 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-decoration: none;
    }

    .modern-card::before,
    .farmacia-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            45deg,
            transparent,
            rgba(64, 224, 255, 0.1),
            transparent
        );
        transform: rotate(45deg);
        transition: all 0.6s ease;
    }

    .modern-card:hover::before,
    .farmacia-card:hover::before {
        left: 100%;
    }

    .modern-card:hover,
    .farmacia-card:hover {
        transform: translateY(-15px) scale(1.05) !important;
        border-color: #00D9FF !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7),
                    0 0 50px rgba(64, 224, 255, 0.5),
                    inset 0 0 20px rgba(64, 224, 255, 0.1) !important;
        text-decoration: none;
    }

    .modern-card a,
    .farmacia-card a {
        text-decoration: none !important;
        color: inherit;
        display: block;
    }

    /* Variaciones de color para tarjetas de farmacia */
    .farmacia-card.surtir {
        background: linear-gradient(135deg, #16213e 0%, #1a3a5c 100%) !important;
        border-color: #40E0FF !important;
    }

    .farmacia-card.existencias {
        background: linear-gradient(135deg, #16213e 0%, #2e1a4a 100%) !important;
        border-color: #c084fc !important;
    }

    .farmacia-card.kardex {
        background: linear-gradient(135deg, #16213e 0%, #1a4a2e 100%) !important;
        border-color: #4ade80 !important;
    }

    .farmacia-card.caducidades {
        background: linear-gradient(135deg, #16213e 0%, #5c3a1a 100%) !important;
        border-color: #fb923c !important;
    }

    .farmacia-card.devoluciones {
        background: linear-gradient(135deg, #16213e 0%, #4a1a2e 100%) !important;
        border-color: #f472b6 !important;
    }

    .farmacia-card.confirmar {
        background: linear-gradient(135deg, #16213e 0%, #5c1a1a 100%) !important;
        border-color: #ef4444 !important;
    }

    .farmacia-card.pedir {
        background: linear-gradient(135deg, #16213e 0%, #1a5c5c 100%) !important;
        border-color: #2dd4bf !important;
    }

    .farmacia-card.salidas {
        background: linear-gradient(135deg, #16213e 0%, #2e2e5c 100%) !important;
        border-color: #818cf8 !important;
    }

    .farmacia-card.inventario {
        background: linear-gradient(135deg, #16213e 0%, #5c4a1a 100%) !important;
        border-color: #fbbf24 !important;
    }

    /* Hover para variaciones de color */
    .farmacia-card:hover.surtir {
        border-color: #00D9FF !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(64, 224, 255, 0.6) !important;
    }

    .farmacia-card:hover.existencias {
        border-color: #a855f7 !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(192, 132, 252, 0.6) !important;
    }

    .farmacia-card:hover.kardex {
        border-color: #22c55e !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(74, 222, 128, 0.6) !important;
    }

    .farmacia-card:hover.caducidades {
        border-color: #f97316 !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(251, 146, 60, 0.6) !important;
    }

    .farmacia-card:hover.devoluciones {
        border-color: #ec4899 !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(244, 114, 182, 0.6) !important;
    }

    .farmacia-card:hover.confirmar {
        border-color: #dc2626 !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(239, 68, 68, 0.6) !important;
    }

    .farmacia-card:hover.pedir {
        border-color: #14b8a6 !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(45, 212, 191, 0.6) !important;
    }

    .farmacia-card:hover.salidas {
        border-color: #6366f1 !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(129, 140, 248, 0.6) !important;
    }

    .farmacia-card:hover.inventario {
        border-color: #f59e0b !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(251, 191, 36, 0.6) !important;
    }

    /* Círculo de icono */
    .icon-circle,
    .farmacia-icon-circle {
        background: linear-gradient(135deg, rgba(64, 224, 255, 0.2) 0%, rgba(0, 217, 255, 0.3) 100%) !important;
        width: 140px !important;
        height: 140px !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        margin: 0 auto 20px !important;
        border: 3px solid #40E0FF !important;
        box-shadow: 0 10px 30px rgba(64, 224, 255, 0.3),
                    inset 0 0 20px rgba(64, 224, 255, 0.1) !important;
        transition: all 0.4s ease !important;
        position: relative;
    }

    .icon-circle::after,
    .farmacia-icon-circle::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 2px solid #40E0FF;
        opacity: 0;
        animation: ripple 2s ease-out infinite;
    }

    @keyframes ripple {
        0% {
            transform: scale(1);
            opacity: 0.8;
        }
        100% {
            transform: scale(1.3);
            opacity: 0;
        }
    }

    .modern-card:hover .icon-circle,
    .farmacia-card:hover .farmacia-icon-circle,
    .modern-card:hover .farmacia-icon-circle,
    .farmacia-card:hover .icon-circle {
        transform: scale(1.15) rotate(360deg) !important;
        box-shadow: 0 15px 40px rgba(64, 224, 255, 0.5),
                    inset 0 0 30px rgba(64, 224, 255, 0.2) !important;
        background: linear-gradient(135deg, rgba(64, 224, 255, 0.3) 0%, rgba(0, 217, 255, 0.4) 100%) !important;
    }

    .modern-card .fa,
    .farmacia-card i,
    .modern-card i,
    .farmacia-card .fa {
        font-size: 60px !important;
        color: #40E0FF !important;
        text-shadow: 0 0 20px rgba(64, 224, 255, 0.8);
        transition: all 0.4s ease !important;
    }

    .modern-card:hover .fa,
    .farmacia-card:hover i,
    .modern-card:hover i,
    .farmacia-card:hover .fa {
        transform: scale(1.2) !important;
        text-shadow: 0 0 30px rgba(64, 224, 255, 1),
                     0 0 40px rgba(64, 224, 255, 0.8);
        animation: pulse-icon 1.5s infinite;
    }

    @keyframes pulse-icon {
        0% { transform: scale(1.2); }
        50% { transform: scale(1.25); }
        100% { transform: scale(1.2); }
    }

    /* Títulos */
    .card-title,
    .farmacia-card h4,
    .modern-card h4 {
        color: #ffffff !important;
        font-weight: 700 !important;
        font-size: 1.4rem !important;
        margin: 0 !important;
        text-align: center;
        padding: 20px;
        letter-spacing: 1px;
        text-transform: uppercase;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5),
                     0 0 20px rgba(64, 224, 255, 0.3);
        transition: all 0.3s ease;
        line-height: 1.3;
    }

    .modern-card:hover .card-title,
    .farmacia-card:hover h4,
    .modern-card:hover h4 {
        color: #40E0FF !important;
        text-shadow: 0 0 20px rgba(64, 224, 255, 0.8),
                     0 0 30px rgba(64, 224, 255, 0.5);
    }

    /* Animaciones de entrada */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modern-card,
    .farmacia-card,
    .container-moderno {
        animation: fadeInUp 0.6s ease-out backwards;
    }

    .contenedor-filtros,
    .tabla-contenedor {
        animation: fadeInUp 0.6s ease-out 0.1s both;
    }

    .modern-card:nth-child(1),
    .farmacia-card:nth-child(1) { animation-delay: 0.1s; }
    .modern-card:nth-child(2),
    .farmacia-card:nth-child(2) { animation-delay: 0.2s; }
    .modern-card:nth-child(3),
    .farmacia-card:nth-child(3) { animation-delay: 0.3s; }
    .modern-card:nth-child(4),
    .farmacia-card:nth-child(4) { animation-delay: 0.4s; }
    .modern-card:nth-child(5),
    .farmacia-card:nth-child(5) { animation-delay: 0.5s; }
    .modern-card:nth-child(6),
    .farmacia-card:nth-child(6) { animation-delay: 0.6s; }
    .modern-card:nth-child(7),
    .farmacia-card:nth-child(7) { animation-delay: 0.7s; }
    .modern-card:nth-child(8),
    .farmacia-card:nth-child(8) { animation-delay: 0.8s; }
    .modern-card:nth-child(9),
    .farmacia-card:nth-child(9) { animation-delay: 0.9s; }

    /* Efecto de brillo en hover */
    @keyframes glow {
        0%, 100% {
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5),
                        0 0 30px rgba(64, 224, 255, 0.2);
        }
        50% {
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7),
                        0 0 50px rgba(64, 224, 255, 0.6);
        }
    }

    .modern-card:hover,
    .farmacia-card:hover {
        animation: glow 2s ease-in-out infinite;
    }

    /* Modal */
    .modal-content {
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 20px !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.9),
                    0 0 40px rgba(64, 224, 255, 0.4);
    }

    .modal-header {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-bottom: 2px solid #40E0FF !important;
        border-radius: 20px 20px 0 0 !important;
    }

    .modal-header .close {
        color: #40E0FF !important;
        opacity: 1;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.8);
    }

    .modal-body {
        color: #ffffff !important;
    }

    .modal-footer {
        border-top: 2px solid #40E0FF !important;
        background: rgba(15, 52, 96, 0.5) !important;
    }

    /* ===== BOTONES MODERNOS CYBERPUNK ===== */
    .btn,
    .btn-moderno,
    button.enviar {
        border-radius: 25px !important;
        padding: 12px 30px !important;
        font-weight: 600 !important;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease !important;
        border: 2px solid #40E0FF !important;
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        color: #ffffff !important;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .btn:hover,
    .btn-moderno:hover,
    button.enviar:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 10px 25px rgba(64, 224, 255, 0.4) !important;
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border-color: #00D9FF !important;
        color: #ffffff !important;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #40E0FF 0%, #0f3460 100%) !important;
        border-color: #40E0FF !important;
    }

    .btn-success,
    .btn-filtrar {
        background: linear-gradient(135deg, #4ade80 0%, #1a4a2e 100%) !important;
        border-color: #4ade80 !important;
    }

    .btn-warning {
        background: linear-gradient(135deg, #fbbf24 0%, #5c4a1a 100%) !important;
        border-color: #fbbf24 !important;
    }

    .btn-danger,
    .btn-borrar,
    .btn-regresar {
        background: linear-gradient(135deg, #ef4444 0%, #5c1a1a 100%) !important;
        border-color: #ef4444 !important;
    }

    .btn-info,
    .btn-especial {
        background: linear-gradient(135deg, #818cf8 0%, #2e2e5c 100%) !important;
        border-color: #818cf8 !important;
    }

    .borrar-btn {
        background: linear-gradient(135deg, #ef4444 0%, #5c1a1a 100%) !important;
        color: white;
        border: 2px solid #ef4444 !important;
        padding: 5px 10px;
        font-size: 12px;
        cursor: pointer;
        margin-left: 6px;
        text-align: center;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .borrar-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.5);
        border-color: #dc2626 !important;
    }

    /* ===== SELECT2 CUSTOM ===== */
    .select2-container--default .select2-selection--single {
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 10px !important;
        height: 48px !important;
        line-height: 48px !important;
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
    }

    .select2-container--default .select2-selection--single:focus {
        border-color: #40E0FF !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        padding-left: 15px !important;
        padding-top: 8px !important;
        color: #ffffff !important;
    }

    /* Dropdown menu del usuario */
    .dropdown-menu {
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 10px;
    }

    .dropdown-menu > li > a {
        color: #ffffff !important;
    }

    .dropdown-menu > li > a:hover {
        background: rgba(64, 224, 255, 0.1) !important;
        color: #40E0FF !important;
    }

    .user-header {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
    }

    /* Footer */
    .main-footer {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-top: 2px solid #40E0FF !important;
        color: #ffffff !important;
        box-shadow: 0 -4px 20px rgba(64, 224, 255, 0.2);
    }

    /* Links globales */
    a {
        color: #40E0FF;
        transition: all 0.3s ease;
    }

    a:hover {
        color: #00D9FF;
        text-decoration: none;
    }

    /* Headings globales */
    h1, h2, h3, h4, h5, h6 {
        color: #ffffff;
    }

    /* Párrafos */
    p {
        color: rgba(255, 255, 255, 0.9);
    }

    /* HR */
    hr {
        border-top: 1px solid rgba(64, 224, 255, 0.3);
    }

    /* Scrollbar personalizado */
    ::-webkit-scrollbar {
        width: 12px;
    }

    ::-webkit-scrollbar-track {
        background: #0a0a0a;
        border-left: 1px solid #40E0FF;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #40E0FF 0%, #0f3460 100%);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #00D9FF 0%, #40E0FF 100%);
    }

    /* Scrollbar para contenedores específicos */
    .tabla-contenedor::-webkit-scrollbar,
    .table-container::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }

    .tabla-contenedor::-webkit-scrollbar-track,
    .table-container::-webkit-scrollbar-track {
        background: rgba(10, 10, 10, 0.5);
        border-radius: 10px;
    }

    .tabla-contenedor::-webkit-scrollbar-thumb,
    .table-container::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #40E0FF 0%, #0f3460 100%);
        border-radius: 10px;
    }

    /* Responsividad mejorada */
    @media (max-width: 992px) {
        .icon-circle,
        .farmacia-icon-circle {
            width: 120px !important;
            height: 120px !important;
        }

        .modern-card .fa,
        .farmacia-card i {
            font-size: 50px !important;
        }

        .card-title,
        .farmacia-card h4 {
            font-size: 1.2rem !important;
        }

        .breadcrumb h4 {
            font-size: 24px !important;
        }

        table, .table, .table-moderna {
            font-size: 13px;
        }

        thead th, .table-moderna thead th {
            padding: 10px !important;
        }

        tbody td, .table-moderna tbody td {
            padding: 8px 10px !important;
        }

        .container-moderno {
            margin: 10px;
            padding: 20px;
            border-radius: 15px;
        }

        .header-principal h1 {
            font-size: 24px;
        }

        .btn-moderno, .btn {
            padding: 10px 16px !important;
            font-size: 14px;
        }

        .btn-ajuste {
            position: relative;
            top: auto;
            right: auto;
            transform: none;
            margin-top: 15px;
        }
    }

    @media screen and (max-width: 980px) {
        .alert {
            padding-right: 38px;
            padding-left: 10px;
        }

        .nompac {
            margin-left: -3px;
            font-size: 10px;
        }

        .nod {
            font-size: 7px;
        }
    }

    @media (max-width: 768px) {
        .farmacia-container {
            padding: 15px;
        }

        .modern-card,
        .farmacia-card {
            margin: 15px 0;
            padding: 30px 15px;
            min-height: 220px;
        }

        .icon-circle,
        .farmacia-icon-circle {
            width: 100px !important;
            height: 100px !important;
            margin-bottom: 15px !important;
        }

        .modern-card .fa,
        .farmacia-card i {
            font-size: 40px !important;
        }

        .card-title,
        .farmacia-card h4 {
            font-size: 1.1rem !important;
            padding: 15px;
        }

        .breadcrumb {
            padding: 20px !important;
            margin-bottom: 30px !important;
        }

        .breadcrumb h4 {
            font-size: 20px !important;
        }

        .status {
            min-width: 200px;
        }

        table, .table, .table-moderna {
            font-size: 12px;
        }

        .box, .panel, .well {
            margin-bottom: 15px;
        }

        .info-box {
            flex-direction: column;
            text-align: center;
        }

        .info-box-icon {
            margin-right: 0;
            margin-bottom: 10px;
        }

        .table-moderna thead th,
        .table-moderna tbody td {
            padding: 8px 6px !important;
        }
    }

    @media (max-width: 576px) {
        .modern-card,
        .farmacia-card {
            min-height: 200px;
            padding: 25px 15px;
            margin: 10px 0;
        }

        .icon-circle,
        .farmacia-icon-circle {
            width: 80px !important;
            height: 80px !important;
            margin-bottom: 12px !important;
        }

        .modern-card .fa,
        .farmacia-card i {
            font-size: 32px !important;
        }

        .card-title,
        .farmacia-card h4 {
            font-size: 13px !important;
            padding: 10px;
        }

        .breadcrumb h4 {
            font-size: 18px !important;
            letter-spacing: 1px;
        }

        .status {
            min-width: 180px;
        }

        table, .table, .table-moderna {
            font-size: 10px;
        }

        thead th, .table-moderna thead th {
            padding: 8px 5px !important;
            font-size: 10px;
        }

        tbody td, .table-moderna tbody td {
            padding: 6px 5px !important;
        }

        .btn, .btn-moderno {
            padding: 10px 20px !important;
            font-size: 12px;
        }

        .small-box h3 {
            font-size: 28px;
        }

        .info-box-number {
            font-size: 20px;
        }
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
