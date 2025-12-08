<?php
// Habilitar reporte de errores para debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log de errores personalizado
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log_confirmar_envio.txt');

try {
    include "../../conexionbd.php";
    session_start();
    ob_start();

    if (!isset($_SESSION['login']) || !isset($_SESSION['login']['id_usua'])) {
        error_log("Error: Usuario no logueado o sesión inválida");
        echo "<script>alert('Sesión no válida'); window.location='../../index.php';</script>";
        exit();
    }

    $usuario = $_SESSION['login'];
    $id_usua = $usuario['id_usua'];
    date_default_timezone_set('America/Guatemala');
} catch (Exception $e) {
    error_log("Error en inicialización: " . $e->getMessage());
    echo "<script>alert('Error de inicialización: " . $e->getMessage() . "');</script>";
    exit();
}

if (isset($usuario['id_rol'])) {
    if ($usuario['id_rol'] == 11 || $usuario['id_rol'] == 4 || $usuario['id_rol'] == 5 || $usuario['id_rol'] == 1) {
        try {
            include "../header_farmaciah.php";
        } catch (Exception $e) {
            error_log("Error incluyendo header_farmaciah.php: " . $e->getMessage());
            echo "<script>alert('Error cargando header');</script>";
        }
    } else {
        error_log("Acceso denegado - Rol no autorizado: " . $usuario['id_rol']);
        session_unset();
        session_destroy();
        echo "<script>window.location='../../index.php';</script>";
        exit();
    }
} else {
    error_log("Error: id_rol no está definido en la sesión");
    session_unset();
    session_destroy();
    echo "<script>window.location='../../index.php';</script>";
    exit();
}

$query = "
   SELECT
    c.id_recib,
    c.item_id,
    i.item_name,
    c.fecha,
    c.solicita,
    SUM(c.entrega) AS total_entrega,
    GROUP_CONCAT(c.existe_lote ORDER BY c.existe_caducidad ASC) AS lotes,
    GROUP_CONCAT(CONCAT(c.existe_lote, ': ', c.existe_caducidad) ORDER BY c.existe_caducidad ASC) AS caducidades
FROM
    carrito_entradash AS c
JOIN
    item_almacen AS i ON c.item_id = i.item_id
JOIN
    cart_recib AS cr ON c.id_recib = cr.id_recib AND cr.parcial = 'NO'
WHERE
    c.almacen = 'FARMACIA'
GROUP BY
    c.id_recib, c.item_id, i.item_name, c.fecha, c.solicita
ORDER BY
    c.id_recib ASC;

";

try {
    $result = $conexion->query($query);
    if (!$result) {
        error_log("Error en consulta principal: " . $conexion->error);
        die("Error en la consulta: " . $conexion->error);
    }
} catch (Exception $e) {
    error_log("Excepción en consulta principal: " . $e->getMessage());
    die("Error en la consulta: " . $e->getMessage());
}

$ubicaciones_query = "SELECT ubicacion_id, nombre_ubicacion FROM ubicaciones_almacen";
try {
    $ubicaciones_result = $conexion->query($ubicaciones_query);
    $ubicaciones = [];
    if ($ubicaciones_result && $ubicaciones_result->num_rows > 0) {
        while ($ubicacion = $ubicaciones_result->fetch_assoc()) {
            $ubicaciones[] = $ubicacion;
        }
    }
} catch (Exception $e) {
    error_log("Error consultando ubicaciones: " . $e->getMessage());
    $ubicaciones = [];
}


if (isset($_POST['confirmar'])) {
    try {
        error_log("Iniciando proceso de confirmación");

        $id_recib_array = isset($_POST['seleccionados']) ? $_POST['seleccionados'] : [];
        $ubicaciones_array = isset($_POST['ubicaciones']) ? $_POST['ubicaciones'] : [];

        error_log("IDs recibidos: " . print_r($id_recib_array, true));
        error_log("Ubicaciones: " . print_r($ubicaciones_array, true));

        if (empty($id_recib_array) || empty($ubicaciones_array)) {
            error_log("Error: Faltan datos obligatorios - IDs: " . count($id_recib_array) . ", Ubicaciones: " . count($ubicaciones_array));
            echo "<script>alert('Error: Faltan datos obligatorios.');</script>";
            echo "<script>window.location.href = 'confirmar_envio.php';</script>";
            exit();
        }

        foreach ($id_recib_array as $id_recib) {
            try {
                error_log("Procesando ID recib: " . $id_recib);

                $id_recib = intval($id_recib);
                $ubicacion_id = isset($ubicaciones_array[$id_recib]) ? intval($ubicaciones_array[$id_recib]) : null;

                if (!$ubicacion_id) {
                    error_log("Error: Falta la ubicación para el ID {$id_recib}");
                    echo "<script>alert('Error: Falta la ubicación para el ID {$id_recib}');</script>";
                    continue;
                }

                $query_validacion = "
            SELECT c.item_id, i.item_name, c.solicita, SUM(c.entrega) AS total_entrega
            FROM carrito_entradash c
            JOIN item_almacen i ON c.item_id = i.item_id
            WHERE c.id_recib = ?
            GROUP BY c.item_id, i.item_name, c.solicita
        ";
                $stmt_validacion = $conexion->prepare($query_validacion);
                if (!$stmt_validacion) {
                    echo "<script>alert('Error al preparar la consulta de validación.');</script>";
                    exit();
                }
                $stmt_validacion->bind_param("i", $id_recib);
                $stmt_validacion->execute();
                $result = $stmt_validacion->get_result();

                if ($result->num_rows > 0) {
                    while ($fila = $result->fetch_assoc()) {
                        $solicita = $fila['solicita'];
                        $total_entrega = $fila['total_entrega'];

                        if ($solicita != $total_entrega) {
                            echo "<script>alert('Error: La entrega es parcial para el ítem {$fila['item_name']} (ID: {$fila['item_id']}).');</script>";
                            echo "<script>window.location.href = 'confirmar_envio.php';</script>";
                            exit();
                        }
                    }
                }

                $query_costo = "SELECT item_costs FROM item_almacen WHERE item_id = ?";
                $stmt_costo = $conexion->prepare($query_costo);
                if (!$stmt_costo) {
                    echo "<script>alert('Error al preparar la consulta de costo.');</script>";
                    exit();
                }

                $query_insercion = "
            SELECT
                c.id_recib,
                c.item_id,
                c.existe_lote,
                c.existe_caducidad,
                c.entrega,
                cr.id_usua AS Surte
            FROM
                carrito_entradash AS c
            JOIN
                cart_recib AS cr ON c.id_recib = cr.id_recib AND cr.parcial = 'NO'
            WHERE
                c.id_recib = ?
            ORDER BY
                c.id_recib ASC, c.existe_caducidad ASC;
        ";
                $stmt_insercion = $conexion->prepare($query_insercion);
                $stmt_insercion->bind_param("i", $id_recib);
                $stmt_insercion->execute();
                $result_insercion = $stmt_insercion->get_result();

                if ($result_insercion->num_rows > 0) {
                    while ($row = $result_insercion->fetch_assoc()) {
                        $item_id = $row['item_id'];
                        $entrada_lote = $row['existe_lote'];
                        $entrada_caducidad = $row['existe_caducidad'];
                        $entrada_unidosis = $row['entrega'];
                        $Surte = $row['Surte'];

                        $stmt_costo->bind_param("i", $item_id);
                        $stmt_costo->execute();
                        $result_costo = $stmt_costo->get_result();
                        $row_costo = $result_costo->fetch_assoc();
                        $entrada_costo = $row_costo['item_costs'];

                        $insert_entrada = "
                    INSERT INTO entradas_almacenh (
                        entrada_fecha,
                        item_id,
                        entrada_lote,
                        entrada_caducidad,
                        entrada_unidosis,
                        entrada_costo,
                        id_usua,
                        ubicacion_id,
                        id_surte
                    ) VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)
                ";
                        $stmt_entrada = $conexion->prepare($insert_entrada);
                        $stmt_entrada->bind_param(
                                "issiiiii",
                                $item_id,
                                $entrada_lote,
                                $entrada_caducidad,
                                $entrada_unidosis,
                                $entrada_costo,
                                $id_usua,
                                $ubicacion_id,
                                $Surte
                        );
                        if (!$stmt_entrada->execute()) {
                            exit('Error al insertar en entradas_almacenh: ' . $stmt_entrada->error);
                        }

                        $insert_kardex = "
                    INSERT INTO kardex_almacenh (
                        kardex_fecha,
                        item_id,
                        kardex_lote,
                        kardex_caducidad,
                        kardex_inicial,
                        kardex_entradas,
                        kardex_salidas,
                        kardex_qty,
                        kardex_dev_stock,
                        kardex_dev_merma,
                        kardex_movimiento,
                        kardex_ubicacion,
                        kardex_destino,
                        id_usua,
                        id_surte
                    ) VALUES (NOW(), ?, ?, ?, 0, ?, 0, 0, 0, 0, 'Resurtimiento', ?, 'FARMACIA', ?, ?);
                ";
                        $stmt_kardex = $conexion->prepare($insert_kardex);
                        $stmt_kardex->bind_param(
                                "issisii",
                                $item_id,
                                $entrada_lote,
                                $entrada_caducidad,
                                $entrada_unidosis,
                                $ubicacion_id,
                                $id_usua,
                                $Surte
                        );
                        if (!$stmt_kardex->execute()) {
                            exit('Error al insertar en kardex_almacenh: ' . $stmt_kardex->error);
                        }

                        $insert_kardexc = "
                    INSERT INTO kardex_almacen (
                        kardex_fecha,
                        item_id,
                        kardex_lote,
                        kardex_caducidad,
                        kardex_inicial,
                        kardex_entradas,
                        kardex_salidas,
                        kardex_qty,
                        kardex_dev_stock,
                        kardex_dev_merma,
                        kardex_movimiento,
                        kardex_ubicacion,
                        kardex_destino,
                        id_usua
                    ) VALUES (NOW(), ?, ?, ?, 0, 0, ?, 0, 0,0, 'Salida', ?, 'FARMACIA', ?);
                ";
                        $stmt_kardexc = $conexion->prepare($insert_kardexc);
                        $stmt_kardexc->bind_param(
                                "issiii",
                                $item_id,
                                $entrada_lote,
                                $entrada_caducidad,
                                $entrada_unidosis,
                                $ubicacion_id,
                                $id_usua
                        );
                        if (!$stmt_kardexc->execute()) {
                            error_log('Error al insertar en kardex_almacen: ' . $stmt_kardexc->error);
                            exit('Error al insertar en kardex_almacen: ' . $stmt_kardexc->error);
                        }

                        $select_existencia = "
                    SELECT existe_entradas, existe_qty
                    FROM existencias_almacenh
                    WHERE item_id = ? AND existe_lote = ? AND existe_caducidad = ?
                ";
                        $stmt_select_existencia = $conexion->prepare($select_existencia);

                        $stmt_select_existencia->bind_param("iss", $item_id, $entrada_lote, $entrada_caducidad);
                        $stmt_select_existencia->execute();
                        $result_existencia = $stmt_select_existencia->get_result();

                        $update_existencia = "
                    UPDATE existencias_almacenh
                    SET existe_entradas = existe_entradas + ?,
                        existe_qty = existe_qty + ?,
                        existe_fecha = NOW()
                    WHERE item_id = ? AND existe_lote = ? AND existe_caducidad = ?
                ";
                        $stmt_update_existencia = $conexion->prepare($update_existencia);

                        $insert_existencia = "
                    INSERT INTO existencias_almacenh (
                        item_id,
                        existe_lote,
                        existe_caducidad,
                        existe_inicial,
                        existe_entradas,
                        existe_salidas,
                        existe_qty,
                        existe_devoluciones,
                        existe_fecha,
                        ubicacion_id,
                        id_usua
                    ) VALUES (?, ?, ?, ?, ?, 0, ?, 0, NOW(), ?, ?)
                ";
                        $stmt_insert_existencia = $conexion->prepare($insert_existencia);

                        if ($result_existencia->num_rows > 0) {
                            $stmt_update_existencia->bind_param("iiiss", $entrada_unidosis, $entrada_unidosis, $item_id, $entrada_lote, $entrada_caducidad);
                            if (!$stmt_update_existencia->execute()) {
                                exit('Error al actualizar existencias_almacenh ' . $stmt_update_existencia->error);
                            }
                        } else {
                            $stmt_insert_existencia->bind_param(
                                    "issiiiii",
                                    $item_id,
                                    $entrada_lote,
                                    $entrada_caducidad,
                                    $entrada_unidosis,
                                    $entrada_unidosis,
                                    $entrada_unidosis,
                                    $ubicacion_id,
                                    $id_usua
                            );
                            if (!$stmt_insert_existencia->execute()) {
                                exit('Error al insertar en existencias_almacenh: ' . $stmt_insert_existencia->error);
                            }
                        }

                        $insert_salida = "
                    INSERT INTO salidas_almacen (
                        salida_fecha,
                        salida_lote,
                        salida_caducidad,
                        salida_qty,
                        salida_destino,
                        id_usua,
                        item_id,
                        ubicacion_id
                    ) VALUES (NOW(), ?, ?, ?, 'FARMACIA', ?, ?, ?)
                ";
                        $stmt_salida = $conexion->prepare($insert_salida);
                        $stmt_salida->bind_param(
                                "ssiiii",
                                $entrada_lote,
                                $entrada_caducidad,
                                $entrada_unidosis,
                                $id_usua,
                                $item_id,
                                $ubicacion_id
                        );
                        if (!$stmt_salida->execute()) {
                            exit('Error al insertar en salidas_almacen: ' . $stmt_salida->error);
                        }

                        // Eliminar registros ya procesados
                        $delete_cart_recib = "DELETE FROM cart_recib WHERE id_recib = ?";
                        $stmt_delete_cart_recib = $conexion->prepare($delete_cart_recib);
                        if (!$stmt_delete_cart_recib) {
                            error_log('Error preparando delete cart_recib: ' . $conexion->error);
                            exit('Error preparando delete cart_recib');
                        }
                        $stmt_delete_cart_recib->bind_param("i", $id_recib);
                        if (!$stmt_delete_cart_recib->execute()) {
                            error_log('Error ejecutando delete cart_recib: ' . $stmt_delete_cart_recib->error);
                        }

                        $delete_carrito_entrada = "DELETE FROM carrito_entradash WHERE id_recib = ?";
                        $stmt_delete_carrito_entrada = $conexion->prepare($delete_carrito_entrada);
                        if (!$stmt_delete_carrito_entrada) {
                            error_log('Error preparando delete carrito_entradash: ' . $conexion->error);
                            exit('Error preparando delete carrito_entradash');
                        }
                        $stmt_delete_carrito_entrada->bind_param("i", $id_recib);
                        if (!$stmt_delete_carrito_entrada->execute()) {
                            error_log('Error ejecutando delete carrito_entradash: ' . $stmt_delete_carrito_entrada->error);
                        }
                    }
                }
            } catch (Exception $e) {
                error_log("Error procesando ID recib {$id_recib}: " . $e->getMessage());
                echo "<script>alert('Error procesando registro {$id_recib}: " . $e->getMessage() . "');</script>";
                continue;
            }
        }

        error_log("Proceso de confirmación completado exitosamente");
        header("Location: confirmar_envio.php?success=1");
        exit();

    } catch (Exception $e) {
        error_log("Error general en confirmación: " . $e->getMessage());
        echo "<script>alert('Error en el proceso: " . $e->getMessage() . "');</script>";
        echo "<script>window.location.href = 'confirmar_envio.php';</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Recibido</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INEO Metepec</title>
    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <link href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet"
          integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="js/select2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldLv/Pr4nhuBviF5jGqQK/5i2Q5iZ64dxBl+zOZ" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/main.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container-fluid">
    <div class="container-moderno">
        <!-- Header principal -->
        <div class="header-principal">
            <i class="fas fa-check-double icono-principal"></i>
            <h1>CONFIRMAR RECIBIDO</h1>
        </div>

        <!-- Botón de regresar -->
        <div class="row mb-4">
            <div class="col-sm-4">
                <a href="../../template/menu_farmaciahosp.php" class="btn-moderno btn-regresar">
                    <i class="fas fa-arrow-left"></i>
                    Regresar
                </a>
            </div>
        </div>

        <!-- Mensaje de éxito -->
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alerta-exito">
                <i class="fas fa-check-circle" style="font-size: 24px; margin-right: 10px;"></i>
                ¡Registros confirmados exitosamente!
            </div>
        <?php endif; ?>

        <!-- Formulario principal -->
        <form method="POST" action="" onsubmit="return confirmarEnvio();">
            <div class="tabla-contenedor">
                <table class="table table-moderna" id="mytable">
                    <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="select-all" class="checkbox-moderno" disabled>
                            <i class="fas fa-check-all"></i>
                        </th>
                        <th><i class="fas fa-hashtag"></i> ID Recib</th>
                        <th><i class="fas fa-calendar"></i> Fecha Envío</th>
                        <th><i class="fas fa-pills"></i> Medicamento</th>
                        <th><i class="fas fa-clipboard-list"></i> Solicitado</th>
                        <th><i class="fas fa-box-open"></i> Entregado</th>
                        <th><i class="fas fa-tag"></i> Lote</th>
                        <th><i class="fas fa-calendar-times"></i> Caducidad</th>
                        <th><i class="fas fa-map-marker-alt"></i> Ubicación</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    try {
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Determinar estado de la fila
                                $estadoFila = ($row['solicita'] == $row['total_entrega']) ? 'fila-pendiente' : 'fila-parcial';

                                // Formatear fecha
                                $fechaFormateada = date('d/m/Y H:i', strtotime($row['fecha']));

                                echo "<tr class='$estadoFila' id='row_{$row['id_recib']}'>";
                                echo "<td>";
                                echo "<input type='checkbox' name='seleccionados[]' value='{$row['id_recib']}' ";
                                echo "class='checkbox-moderno' disabled id='chk_{$row['id_recib']}'>";
                                echo "</td>";
                                echo "<td><span class='badge badge-custom badge-recib'>{$row['id_recib']}</span></td>";
                                echo "<td><small>$fechaFormateada</small></td>";
                                echo "<td><strong>{$row['item_name']}</strong></td>";
                                echo "<td><span class='badge badge-custom badge-cantidad'>{$row['solicita']}</span></td>";
                                echo "<td><span class='badge badge-custom badge-cantidad'>{$row['total_entrega']}</span></td>";
                                echo "<td>";

                                // Procesar lotes
                                $lotes = explode(',', $row['lotes']);
                                foreach ($lotes as $lote) {
                                    echo "<span class='badge badge-custom badge-lote mr-1 mb-1'>" . trim($lote) . "</span>";
                                }

                                echo "</td>";
                                echo "<td class='tooltip-custom' data-tooltip='Fechas de caducidad por lote'>";

                                // Procesar caducidades
                                $caducidades = explode(',', $row['caducidades']);
                                foreach ($caducidades as $caducidad) {
                                    echo "<small class='d-block'>" . trim($caducidad) . "</small>";
                                }

                                echo "</td>";
                                echo "<td>";
                                echo "<select name='ubicaciones[{$row['id_recib']}]' class='select-moderno' ";
                                echo "onchange='habilitarCheckbox({$row['id_recib']})' required>";
                                echo "<option value=''>Seleccionar ubicación...</option>";

                                foreach ($ubicaciones as $ubicacion) {
                                    echo "<option value='{$ubicacion['ubicacion_id']}'>";
                                    echo "{$ubicacion['nombre_ubicacion']}</option>";
                                }

                                echo "</select>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9' class='mensaje-sin-resultados'>";
                            echo "<i class='fas fa-inbox'></i><br>";
                            echo "No se encontraron registros para confirmar";
                            echo "</td></tr>";
                        }
                    } catch (Exception $e) {
                        error_log("Error generando tabla HTML: " . $e->getMessage());
                        echo "<tr><td colspan='9' class='mensaje-sin-resultados'>";
                        echo "<i class='fas fa-exclamation-triangle'></i><br>";
                        echo "Error cargando datos: " . htmlspecialchars($e->getMessage());
                        echo "</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>

            <!-- Botón de confirmación -->
            <div class="text-center mt-4">
                <button type="submit" name="confirmar" class="btn-moderno btn-confirmar">
                    <i class="fas fa-check-double"></i>
                    Confirmar Seleccionados
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function habilitarCheckbox(id) {
        const select = document.querySelector(`select[name="ubicaciones[${id}]"]`);
        const checkbox = document.getElementById(`chk_${id}`);
        const selectAll = document.getElementById('select-all');
        const row = document.getElementById(`row_${id}`);

        checkbox.disabled = (select.value === "");
        if (select.value === "") {
            checkbox.checked = false;
            checkbox.required = false;
            select.required = false;
            row.classList.remove('fila-seleccionada');
        } else {
            select.required = checkbox.checked;
            if (checkbox.checked) {
                row.classList.add('fila-seleccionada');
            }
        }

        // Habilitar el select-all si hay al menos un checkbox habilitado
        const checkboxes = document.querySelectorAll('input[name="seleccionados[]"]');
        const hayHabilitado = Array.from(checkboxes).some(cb => !cb.disabled);
        selectAll.disabled = !hayHabilitado;
    }

    // Event listeners para checkboxes individuales
    document.querySelectorAll('input[name="seleccionados[]"]').forEach(cb => {
        cb.addEventListener('change', function() {
            const id = this.value;
            const select = document.querySelector(`select[name="ubicaciones[${id}]"]`);
            const row = document.getElementById(`row_${id}`);

            if (this.checked && select.value === "") {
                alert("Debe seleccionar una ubicación antes de marcar este registro.");
                this.checked = false;
                select.required = false;
                row.classList.remove('fila-seleccionada');
            } else {
                select.required = this.checked;
                if (this.checked) {
                    row.classList.add('fila-seleccionada');
                } else {
                    row.classList.remove('fila-seleccionada');
                }
            }
        });
    });

    // Select all functionality
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('input[name="seleccionados[]"]');
        checkboxes.forEach(cb => {
            if (!cb.disabled) {
                cb.checked = this.checked;
                const id = cb.value;
                const select = document.querySelector(`select[name="ubicaciones[${id}]"]`);
                const row = document.getElementById(`row_${id}`);

                select.required = cb.checked;
                if (cb.checked) {
                    row.classList.add('fila-seleccionada');
                } else {
                    row.classList.remove('fila-seleccionada');
                }
            }
        });
    });

    // Inicialización al cargar la página
    window.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('select[name^="ubicaciones"]').forEach(select => {
            const id = select.name.match(/\[(\d+)\]/)[1];
            habilitarCheckbox(id);
        });
    });

    // Confirmación antes del envío
    function confirmarEnvio() {
        const seleccionados = document.querySelectorAll('input[name="seleccionados[]"]:checked');
        if (seleccionados.length === 0) {
            alert('Debe seleccionar al menos un registro para confirmar.');
            return false;
        }

        return confirm(`¿Está seguro de confirmar ${seleccionados.length} registro(s) seleccionado(s)?`);
    }
</script>
</body>
</html>
