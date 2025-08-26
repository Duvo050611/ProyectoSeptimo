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
        :root {
            --color-primario: #2b2d7f;
            --color-secundario: #1a1c5a;
            --color-fondo: #f8f9ff;
            --color-borde: #e8ebff;
            --color-exito: #28a745;
            --color-peligro: #dc3545;
            --color-advertencia: #ffc107;
            --color-info: #17a2b8;
            --sombra: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* ===== ESTILOS GENERALES ===== */
        body {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8ebff 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .container-moderno {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin: 20px auto;
            max-width: 98%;
            box-shadow: var(--sombra);
            border: 2px solid var(--color-borde);
        }

        /* ===== BOTONES MODERNOS ===== */
        .btn-moderno {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: var(--sombra);
        }

        .btn-regresar {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white !important;
        }

        .btn-confirmar {
            background: linear-gradient(135deg, var(--color-exito) 0%, #20c997 100%);
            color: white;
            font-size: 18px;
            padding: 15px 30px;
        }

        .btn-moderno:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            text-decoration: none;
        }

        /* ===== HEADER SECTION ===== */
        .header-principal {
            text-align: center;
            margin-bottom: 30px;
            padding: 30px 0;
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            border-radius: 20px;
            color: white;
            box-shadow: var(--sombra);
        }

        .header-principal .icono-principal {
            font-size: 48px;
            margin-bottom: 15px;
            display: block;
        }

        .header-principal h1 {
            font-size: 32px;
            font-weight: 700;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* ===== ALERTAS DE ESTADO ===== */
        .alerta-exito {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 2px solid var(--color-exito);
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            color: #155724;
            text-align: center;
            font-weight: 600;
        }

        /* ===== TABLA MODERNIZADA ===== */
        .tabla-contenedor {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--sombra);
            border: 2px solid var(--color-borde);
            max-height: 75vh;
            overflow-y: auto;
        }

        .table-moderna {
            margin: 0;
            font-size: 13px;
            width: 100%;
            border-collapse: collapse;
        }

        /* Encabezados */
        .table-moderna thead th {
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            color: white;
            border: none;
            padding: 15px 10px;
            font-weight: 600;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 10;
            font-size: 12px;
            white-space: nowrap;
        }

        /* Filas */
        .table-moderna tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f1f3f4;
        }

        .table-moderna tbody tr:nth-child(even) {
            background-color: #f8f9ff;
        }

        .table-moderna tbody tr:hover {
            background-color: var(--color-fondo);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Celdas */
        .table-moderna tbody td {
            padding: 12px 8px;
            vertical-align: middle;
            border: none;
            text-align: center;
            font-size: 12px;
            word-wrap: break-word;
        }

        /* ===== CHECKBOX Y SELECT MODERNOS ===== */
        .checkbox-moderno {
            transform: scale(1.2);
            accent-color: var(--color-primario);
        }

        .select-moderno {
            border: 2px solid var(--color-borde);
            border-radius: 8px;
            padding: 8px 12px;
            background: white;
            transition: all 0.3s ease;
            width: 100%;
        }

        .select-moderno:focus {
            border-color: var(--color-primario);
            box-shadow: 0 0 0 3px rgba(43, 45, 127, 0.1);
            outline: none;
        }

        .select-moderno:disabled {
            background-color: #f8f9fa;
            opacity: 0.6;
        }

        /* ===== BADGES Y ETIQUETAS ===== */
        .badge-custom {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
        }

        .badge-recib {
            background: linear-gradient(135deg, var(--color-info) 0%, #138496 100%);
            color: white;
        }

        .badge-lote {
            background: linear-gradient(135deg, #6f42c1 0%, #5a2c91 100%);
            color: white;
        }

        .badge-cantidad {
            background: linear-gradient(135deg, var(--color-exito) 0%, #20c997 100%);
            color: white;
        }

        /* ===== ESTADOS DE FILA ===== */
        .fila-pendiente {
            border-left: 4px solid var(--color-advertencia);
            background: linear-gradient(135deg, #fff8e1 0%, #fff9c4 100%);
        }

        .fila-seleccionada {
            border-left: 4px solid var(--color-exito);
            background: linear-gradient(135deg, #e8f5e8 0%, #d4edda 100%);
        }

        /* ===== MENSAJE SIN RESULTADOS ===== */
        .mensaje-sin-resultados {
            text-align: center;
            padding: 50px 20px;
            color: var(--color-primario);
            font-size: 18px;
            font-weight: 600;
        }

        .mensaje-sin-resultados i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        /* ===== INFORMACIÓN ADICIONAL ===== */
        .info-panel {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border: 2px solid var(--color-info);
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
        }

        .info-panel h5 {
            color: var(--color-info);
            font-weight: 700;
            margin-bottom: 15px;
        }

        .info-panel ul {
            margin: 0;
            padding-left: 20px;
        }

        .info-panel li {
            margin-bottom: 8px;
            color: #0277bd;
        }

        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 768px) {
            .container-moderno {
                margin: 10px;
                padding: 20px;
                border-radius: 15px;
            }

            .header-principal h1 {
                font-size: 24px;
            }

            .btn-moderno {
                padding: 10px 16px;
                font-size: 14px;
            }

            .table-moderna {
                font-size: 11px;
            }

            .table-moderna thead th,
            .table-moderna tbody td {
                padding: 8px 6px;
            }
        }

        /* ===== ANIMACIONES ===== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .container-moderno {
            animation: fadeInUp 0.6s ease-out;
        }

        .tabla-contenedor,
        .info-panel {
            animation: fadeInUp 0.6s ease-out 0.1s both;
        }

        /* ===== TOOLTIP PERSONALIZADO ===== */
        .tooltip-custom {
            position: relative;
            cursor: help;
        }

        .tooltip-custom::before {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
            z-index: 1000;
        }

        .tooltip-custom:hover::before {
            opacity: 1;
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