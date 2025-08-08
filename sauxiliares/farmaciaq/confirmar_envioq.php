<?php
include "../../conexionbd.php";
session_start();
ob_start();

$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];
date_default_timezone_set('America/Guatemala');

if (isset($usuario['id_rol'])) {
    if ($usuario['id_rol'] == 11 || $usuario['id_rol'] == 4 || $usuario['id_rol'] == 5) {
        include "../header_farmaciaq.php";
    } else {
        session_unset();
        session_destroy();
        echo "<script>window.location='../../index.php';</script>";
        exit();
    }
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
    c.almacen = 'QUIROFANO' 
GROUP BY 
    c.id_recib, c.item_id, i.item_name, c.fecha, c.solicita
ORDER BY 
    c.id_recib ASC;

";



$result = $conexion->query($query);
if (!$result) {
    die("Error en la consulta: " . $conexion->error);
}

$ubicaciones_query = "SELECT ubicacion_id, nombre_ubicacion FROM ubicaciones_almacen";
$ubicaciones_result = $conexion->query($ubicaciones_query);
$ubicaciones = [];
if ($ubicaciones_result && $ubicaciones_result->num_rows > 0) {
    while ($ubicacion = $ubicaciones_result->fetch_assoc()) {
        $ubicaciones[] = $ubicacion;
    }
} else {
    die("Error al consultar ubicaciones: " . $conexion->error);
}


if (isset($_POST['confirmar'])) {
    $id_recib = isset($_POST['id_recib']) ? intval($_POST['id_recib']) : null;
    $ubicacion_id = isset($_POST['ubicacion_id']) ? intval($_POST['ubicacion_id']) : null;
    $fecha_actual = date('Y-m-d H:i:s');

    if (is_null($id_recib) || is_null($ubicacion_id)) {
        echo "<script>alert('Error: Faltan datos obligatorios.');</script>";
        echo "<script>window.location.href = 'confirmar_envio.php';</script>";
        exit();
    }

    $query_costo = "SELECT item_costs FROM item_almacen WHERE item_id = ?";
    $stmt_costo = $conexion->prepare($query_costo);
    if (!$stmt_costo) {
        echo "<script>alert('Error al preparar la consulta de costo.');</script>";
        exit();
    }

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
        $insert_entrada = "
            INSERT INTO entradas_almacenq (
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

        $insert_kardex = "
                INSERT INTO kardex_almacenq (
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
                ) VALUES (NOW(), ?, ?, ?, 0, ?, 0, 0, 0, 0, 'Resurtimiento', ?, 'QUIROFANO', ?,?)
                ";
        $stmt_kardex = $conexion->prepare($insert_kardex);


        $select_existencia = "
        SELECT existe_entradas, existe_qty 
        FROM existencias_almacenq 
        WHERE item_id = ? AND existe_lote = ? AND existe_caducidad = ?
        ";
        $stmt_select_existencia = $conexion->prepare($select_existencia);

        $update_existencia = "
        UPDATE existencias_almacenq 
        SET existe_entradas = existe_entradas + ?, 
            existe_qty = existe_qty + ?,
            existe_fecha = NOW()
        WHERE item_id = ? AND existe_lote = ? AND existe_caducidad = ?
        ";
        $stmt_update_existencia = $conexion->prepare($update_existencia);

        $insert_existencia = "
        INSERT INTO existencias_almacenq (
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
        ) VALUES (NOW(), ?, ?, ?, 'QUIROFANO', ?, ?, ?)
        ";
        $stmt_salida = $conexion->prepare($insert_salida);



        while ($row = $result_insercion->fetch_assoc()) {
            $item_id = $row['item_id'];
            $entrada_lote = $row['existe_lote'];
            $entrada_caducidad = $row['existe_caducidad'];
            $entrada_unidosis = $row['entrega'];
            $Surte = $row['Surte'];

            // Obtener el costo del ítem
            $stmt_costo->bind_param("i", $item_id);
            $stmt_costo->execute();
            $result_costo = $stmt_costo->get_result();
            $row_costo = $result_costo->fetch_assoc();
            $entrada_costo = $row_costo['item_costs'];

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
            if ($stmt_entrada->execute()) {
            } else {
                exit('Error al insertar en entradas_almacenq: ' . $stmt_entrada->error);
            }


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
            if ($stmt_kardex->execute()) {
            } else {
                exit('Error al insertar en kardex_almacenq: ' . $stmt_kardex->error);
            }


            $insertKardexQuery = "INSERT INTO kardex_almacen (
                    kardex_fecha, item_id, kardex_lote, kardex_caducidad, kardex_inicial, kardex_entradas,
                    kardex_salidas, kardex_qty, kardex_dev_stock, kardex_dev_merma, kardex_movimiento,
                    kardex_ubicacion, kardex_destino, id_usua
                ) VALUES (NOW(), ?, ?, ?, 0, 0, ?, 0, 0, 0, 'Salida', ?,'QUIROFANO',?)";
            $stmtInsertKardex = $conexion->prepare($insertKardexQuery);
            $stmtInsertKardex->bind_param(
                'issiii',
                $item_id,
                $entrada_lote,
                $entrada_caducidad,
                $entrada_unidosis,
                $ubicacion_id,
                $id_usua


            );
            if ($stmtInsertKardex->execute()) {
            } else {
                exit('Error al insertar en kardex_almacen: ' . $stmtInsertKardex->error);
            }


            $stmt_salida->bind_param(
                "ssiiii",
                $entrada_lote,
                $entrada_caducidad,
                $entrada_unidosis,
                $id_usua,
                $item_id,
                $ubicacion_id
            );

            if ($stmt_salida->execute()) {
            } else {
                exit('Error al insertar en salidas_almacen: ' . $stmt_salida->error);
            }




            // Asociar los parámetros
            if (!$stmt_select_existencia->bind_param("iss", $item_id, $entrada_lote, $entrada_caducidad)) {
                exit("Error al asociar los parámetros de la consulta SELECT en existencias_almacen: " . $stmt_select_existencia->error);
            }

            // Ejecutar la consulta
            if (!$stmt_select_existencia->execute()) {
                exit("Error al ejecutar la consulta SELECT en existencias_almacen: " . $stmt_select_existencia->error);
            }

            // Obtener el resultado
            $result_existencia = $stmt_select_existencia->get_result();

            // Validar si la obtención del resultado fue exitosa
            if (!$result_existencia) {
                exit("Error al obtener el resultado de la consulta SELECT en existencias_almacen: " . $stmt_select_existencia->error);
            }




            if ($result_existencia->num_rows > 0) {
                $stmt_update_existencia->bind_param("iiiss", $entrada_unidosis, $entrada_unidosis, $item_id, $entrada_lote, $entrada_caducidad);
                $stmt_update_existencia->execute();

                if ($stmt_update_existencia->execute()) {
                } else {
                    exit('Error al actualizar existencias_almacenq ' . $stmt_update_existencia->error);
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
                if ($stmt_insert_existencia->execute()) {
                } else {
                    exit('Error al insertar en existencias_almacenq: ' . $stmt_insert_existencia->error);
                }
            }



            $delete_cart_recib = "DELETE FROM cart_recib WHERE id_recib = ?";
            $stmt_delete_cart_recib = $conexion->prepare($delete_cart_recib);
            $stmt_delete_cart_recib->bind_param("i", $id_recib);
            $stmt_delete_cart_recib->execute();
            $delete_carrito_entradash = "DELETE FROM carrito_entradash WHERE id_recib = ?";
            $stmt_delete_carrito_entradash = $conexion->prepare($delete_carrito_entradash);
            $stmt_delete_carrito_entradash->bind_param("i", $id_recib);
            $stmt_delete_carrito_entradash->execute();
        }

        $stmt_costo->close();
        $stmt_entrada->close();
        $stmt_kardex->close();
        $stmt_select_existencia->close();
        $stmt_update_existencia->close();
        $stmt_insert_existencia->close();
        $stmt_salida->close();


        echo "<script>
        alert('Surtido Confirmado Correctamente');
        window.location.href = 'confirmar_envioq.php';
      </script>";
        exit();
    } else {
        echo "<script>alert('No hay registros para insertar.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Recibido - Farmacia Quirófano</title>
    
    <!-- Bootstrap 4.0.0 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    
    <!-- Font Awesome 6.0.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- jQuery y Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        :root {
            --primary-color: #2b2d7f;
            --primary-dark: #1e1f5a;
            --primary-light: #4a4db8;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 20px 0;
        }
        
        .btn-custom {
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
        
        .btn-danger-custom {
            background: linear-gradient(45deg, #dc3545, #c82333);
            color: white;
        }
        
        .btn-danger-custom:hover {
            background: linear-gradient(45deg, #c82333, #bd2130);
            color: white;
        }
        
        .btn-success-custom {
            background: linear-gradient(45deg, #28a745, #1e7e34);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        
        .btn-success-custom:hover {
            background: linear-gradient(45deg, #1e7e34, #155724);
            color: white;
            transform: translateY(-1px);
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 8px 32px rgba(43,45,127,0.3);
            text-align: center;
        }
        
        .page-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .stats-container {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .stat-card {
            text-align: center;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        
        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            max-height: 70vh;
            overflow-y: auto;
        }
        
        .table thead th {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            padding: 15px 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover {
            background-color: rgba(43,45,127,0.1);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .table tbody td {
            padding: 12px 8px;
            vertical-align: middle;
            font-size: 0.9rem;
            border: none;
        }
        
        .container-main {
            max-width: 98%;
            margin: 0 auto;
        }
        
        .no-data-message {
            background: white;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            color: #6c757d;
        }
        
        .form-select-custom {
            border-radius: 20px;
            border: 2px solid #e9ecef;
            padding: 8px 15px;
            transition: all 0.3s ease;
            min-width: 150px;
        }
        
        .form-select-custom:focus {
            box-shadow: 0 0 0 0.2rem rgba(43,45,127,0.25);
            border-color: var(--primary-light);
        }
        
        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-pending {
            background: linear-gradient(45deg, #ffc107, #e0a800);
            color: #333;
        }
        
        .status-complete {
            background: linear-gradient(45deg, #28a745, #1e7e34);
            color: white;
        }
        
        .status-partial {
            background: linear-gradient(45deg, #fd7e14, #e55a00);
            color: white;
        }
        
        .info-card {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border: none;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .lote-badge {
            background: linear-gradient(45deg, #17a2b8, #117a8b);
            color: white;
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 0.7rem;
            margin: 2px;
            display: inline-block;
        }
        
        .caducidad-info {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 5px;
        }
        
        .action-form {
            display: inline-block;
            width: 100%;
        }
        
        .quantity-match {
            color: #28a745;
            font-weight: bold;
        }
        
        .quantity-mismatch {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="container-main">
            <!-- Encabezado -->
            <div class="page-header">
                <h1><i class="fas fa-clipboard-check"></i> CONFIRMAR RECIBIDO</h1>
                <p class="mb-0">Confirmación de productos enviados desde almacén principal</p>
            </div>

            <!-- Botón de regreso -->
            <div class="container">
                <div class="row">
                    <div class="col-12 d-flex justify-content-center mb-3">
                        <a href="../../template/menu_farmaciaq.php" class="btn btn-custom btn-danger-custom">
                            <i class="fas fa-arrow-left"></i> Regresar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Estadísticas rápidas -->
            <div class="stats-container">
                <h5><i class="fas fa-chart-bar"></i> Resumen de Envíos</h5>
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-card" style="background: linear-gradient(45deg, var(--primary-color), var(--primary-dark)); color: white;">
                            <h3 id="stat-total">0</h3>
                            <small>Total Envíos</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card" style="background: linear-gradient(45deg, #28a745, #1e7e34); color: white;">
                            <h3 id="stat-complete">0</h3>
                            <small>Completos</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card" style="background: linear-gradient(45deg, #fd7e14, #e55a00); color: white;">
                            <h3 id="stat-partial">0</h3>
                            <small>Parciales</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card" style="background: linear-gradient(45deg, #ffc107, #e0a800); color: #333;">
                            <h3 id="stat-pending">0</h3>
                            <small>Pendientes</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información importante -->
            <div class="info-card">
                <h6><i class="fas fa-info-circle"></i> Instrucciones</h6>
                <ul class="mb-0">
                    <li>Verifique que las cantidades solicitadas coincidan con las entregadas</li>
                    <li>Seleccione la ubicación donde se almacenarán los productos</li>
                    <li>Solo se pueden confirmar envíos completos (sin entregas parciales)</li>
                </ul>
            </div>
            <!-- Tabla de envíos pendientes -->
            <div class="table-container">
                <table class="table table-striped table-hover" id="enviosTable">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID Recib</th>
                            <th><i class="fas fa-calendar"></i> Fecha Envío</th>
                            <th><i class="fas fa-pills"></i> Medicamento</th>
                            <th><i class="fas fa-arrow-down"></i> Solicitado</th>
                            <th><i class="fas fa-box"></i> Entregado</th>
                            <th><i class="fas fa-flask"></i> Lotes</th>
                            <th><i class="fas fa-calendar-times"></i> Caducidades</th>
                            <th><i class="fas fa-map-marker-alt"></i> Ubicación</th>
                            <th><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Determinar el estado de la entrega
                                $isComplete = ($row['solicita'] == $row['total_entrega']);
                                $statusClass = $isComplete ? 'status-complete' : 'status-partial';
                                $statusText = $isComplete ? 'Completo' : 'Parcial';
                                $quantityClass = $isComplete ? 'quantity-match' : 'quantity-mismatch';
                                
                                echo "<tr>
                        <td><strong>" . $row['id_recib'] . "</strong></td>
                        <td>" . date('d/m/Y H:i', strtotime($row['fecha'])) . "</td>
                        <td>
                            <strong>" . $row['item_name'] . "</strong>
                        </td>
                        <td><span class='badge badge-info'>" . $row['solicita'] . "</span></td>
                        <td>
                            <span class='badge $statusClass'>" . $row['total_entrega'] . "</span>
                            <div class='$quantityClass' style='font-size: 0.8rem;'>$statusText</div>
                        </td>
                        <td>";
                                
                                // Mostrar lotes como badges
                                $lotes = explode(',', $row['lotes']);
                                foreach ($lotes as $lote) {
                                    if (trim($lote)) {
                                        echo "<span class='lote-badge'>" . trim($lote) . "</span>";
                                    }
                                }
                                
                                echo "</td>
                        <td>
                            <div class='caducidad-info'>";
                                
                                // Mostrar caducidades formateadas
                                $caducidades = explode(',', $row['caducidades']);
                                foreach ($caducidades as $caducidad) {
                                    if (trim($caducidad)) {
                                        $parts = explode(':', trim($caducidad));
                                        if (count($parts) == 2) {
                                            $lote = trim($parts[0]);
                                            $fecha = trim($parts[1]);
                                            echo "<small><strong>$lote:</strong> " . date('d/m/Y', strtotime($fecha)) . "</small><br>";
                                        }
                                    }
                                }
                                
                                echo "</div>
                        </td>
                        <td>
                            <form action='' method='POST' class='action-form'>
                                <input type='hidden' name='id_recib' value='" . $row['id_recib'] . "'>
                                <select name='ubicacion_id' class='form-select-custom' required>
                                    <option value=''>Seleccionar ubicación...</option>";
                                
                                foreach ($ubicaciones as $ubicacion) {
                                    echo "<option value='" . $ubicacion['ubicacion_id'] . "'>" . $ubicacion['nombre_ubicacion'] . "</option>";
                                }
                                
                                echo "</select>
                        </td>
                        <td>";
                                
                                if ($isComplete) {
                                    echo "<button type='submit' name='confirmar' class='btn btn-success-custom' title='Confirmar recepción'>
                                        <i class='fas fa-check'></i> Confirmar
                                    </button>";
                                } else {
                                    echo "<button type='button' class='btn btn-secondary btn-sm' disabled title='No se puede confirmar envío parcial'>
                                        <i class='fas fa-times'></i> Parcial
                                    </button>";
                                }
                                
                                echo "</form>
                        </td>
                    </tr>";
                            }
                        } else {
                            echo "<tr>
                    <td colspan='9'>
                        <div class='no-data-message'>
                            <i class='fas fa-inbox fa-3x mb-3' style='color: #6c757d;'></i>
                            <h4>No hay envíos pendientes</h4>
                            <p>Todos los envíos han sido procesados o no hay envíos por confirmar.</p>
                        </div>
                    </td>
                </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Función para actualizar estadísticas
            function updateStats() {
                var totalRows = $("#enviosTable tbody tr").length;
                var completeRows = 0;
                var partialRows = 0;
                var pendingRows = 0;
                
                $("#enviosTable tbody tr").each(function() {
                    var row = $(this);
                    if (row.find('.status-complete').length > 0) {
                        completeRows++;
                    } else if (row.find('.status-partial').length > 0) {
                        partialRows++;
                    }
                    
                    if (row.find('button[name="confirmar"]:not(:disabled)').length > 0) {
                        pendingRows++;
                    }
                });
                
                $("#stat-total").text(totalRows);
                $("#stat-complete").text(completeRows);
                $("#stat-partial").text(partialRows);
                $("#stat-pending").text(pendingRows);
            }
            
            // Confirmación antes de enviar
            $('form').on('submit', function(e) {
                var ubicacion = $(this).find('select[name="ubicacion_id"]').val();
                if (!ubicacion) {
                    e.preventDefault();
                    alert('Por favor seleccione una ubicación antes de confirmar.');
                    return false;
                }
                
                var confirmMsg = '¿Está seguro de confirmar la recepción de este envío?';
                if (!confirm(confirmMsg)) {
                    e.preventDefault();
                    return false;
                }
            });
            
            // Inicializar estadísticas
            updateStats();
        });
    </script>
</body>

</html>