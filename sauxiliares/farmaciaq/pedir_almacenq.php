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

// Procesar inserción desde formulario
if (isset($_POST['item_id']) && isset($_POST['qty'])) {
    $item_id = $_POST['item_id'];
    $qty = $_POST['qty'];

    // Inserta los datos en la tabla `cart_recib`
    $ingresar2 = mysqli_query($conexion, "INSERT INTO cart_recib(item_id, solicita, almacen,id_usua) VALUES ($item_id, $qty, 'QUIROFANO',$id_usua)")
        or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

    // Redirige al usuario después de insertar los datos
    echo '<script type="text/javascript">window.location.href = "pedir_almacenq.php";</script>';
    exit(); // Termina el script después de la redirección
}

// Otras acciones: confirmar, eliminar, consultar...
if (isset($_GET['q']) && $_GET['q'] == 'conf' && isset($_GET['cart_id'])) {
    $cart_id = $_GET['cart_id'];
    $updateQuery = "UPDATE cart_recib SET confirma = 'SI' WHERE id_recib = ?";
    $stmt = $conexion->prepare($updateQuery);
    $stmt->bind_param('i', $cart_id);

    if ($stmt->execute()) {
        header("Location: pedir_almacenq.php?success_confirm=true");
        exit();
    } else {
        echo "<script>alert('Error al confirmar');</script>";
    }
    $stmt->close();
}

if (isset($_GET['q']) && $_GET['q'] == 'eliminar' && isset($_GET['cart_id'])) {
    $cart_id = $_GET['cart_id'];
    $deleteQuery = "DELETE FROM cart_recib WHERE id_recib = ?";
    $stmt = $conexion->prepare($deleteQuery);
    $stmt->bind_param('i', $cart_id);
    if ($stmt->execute()) {
        header("Location: pedir_almacenq.php?success_delete=true");
        exit();
    } else {
        echo "<script>alert('Error al eliminar el registro');</script>";
    }
    $stmt->close();
}

$resultado = $conexion->query("SELECT * FROM cart_recib c, item_almacen i WHERE i.item_id = c.item_id AND c.almacen = 'QUIROFANO'") or die($conexion->error);

if (isset($_GET['success']) && $_GET['success'] == 'true') {
    echo "<script>alert('Datos insertados correctamente');</script>";
}

if (isset($_GET['success_confirm']) && $_GET['success_confirm'] == 'true') {
    echo "<script>
       alert('Surtido confirmado');
       window.location.href = 'pedir_almacenq.php';
     </script>";
    exit();
}

if (isset($_GET['success_delete']) && $_GET['success_delete'] == 'true') {
    echo "<script>
        alert('Registro eliminado exitosamente');
        window.location.href = 'pedir_almacenq.php';
    </script>";
    exit();
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Productos - Farmacia Quirófano</title>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

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
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
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
        }

        .btn-success-custom:hover {
            background: linear-gradient(45deg, #1e7e34, #155724);
            color: white;
        }

        .btn-warning-custom {
            background: linear-gradient(45deg, #ffc107, #e0a800);
            color: #333;
        }

        .btn-warning-custom:hover {
            background: linear-gradient(45deg, #e0a800, #d39e00);
            color: #333;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 8px 32px rgba(43, 45, 127, 0.3);
            text-align: center;
        }

        .page-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .form-container {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .form-control-custom {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control-custom:focus {
            box-shadow: 0 0 0 0.2rem rgba(43, 45, 127, 0.25);
            border-color: var(--primary-light);
        }

        .select2-container--default .select2-selection--single {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            height: 48px;
            padding: 8px 12px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 32px;
            padding-left: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 12px;
        }

        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            max-height: 70vh;
            overflow-y: auto;
            overflow-x: auto;
            width: 100%;
            min-width: 100%;
        }

        .table thead th {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            padding: 18px 15px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-size: 14px;
            position: sticky;
            top: 0;
            z-index: 10;
            text-align: center;
            white-space: nowrap;
        }

        .table thead th i {
            margin-right: 8px;
            font-size: 16px;
        }

        /* Permitir que Bootstrap table-striped funcione correctamente */
        .table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #e9ecef;
        }

                .table-striped > tbody > tr:nth-of-type(odd),
            .table-striped > tbody > tr:nth-of-type(even) {
                background-color: white !important;
            }

       .table tbody tr:hover {
            background-color: inherit;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .table tbody td {
            padding: 16px 15px;
            vertical-align: middle;
            font-size: 15px;
            font-weight: 500;
            border: none;
            text-align: center;
            line-height: 1.4;
        }

        .table tbody td:first-child {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 16px;
        }

        .table tbody td:nth-child(2) {
            text-align: left;
            font-weight: 600;
            color: #2c3e50;
            max-width: 300px;
            word-wrap: break-word;
        }

        .container-main {
            max-width: 98%;
            margin: 0 auto;
        }

        .stats-container {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-card {
            text-align: center;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .badge-status {
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status-confirmed {
            background: linear-gradient(45deg, #28a745, #1e7e34);
            color: white;
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
        }

        .status-pending {
            background: linear-gradient(45deg, #ffc107, #e0a800);
            color: #333;
            box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
        }

        .badge-info {
            background: linear-gradient(45deg, #17a2b8, #138496);
            color: white;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(23, 162, 184, 0.3);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .action-buttons .btn {
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            min-width: 100px;
        }

        .action-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .action-buttons .btn i {
            margin-right: 6px;
            font-size: 14px;
        }

        .no-data-message {
            background: #f8f9fa;
            padding: 50px 40px;
            border-radius: 15px;
            text-align: center;
            color: #6c757d;
            border: 2px dashed #dee2e6;
        }

        .no-data-message h4 {
            color: #495057;
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .no-data-message p {
            font-size: 15px;
            margin-bottom: 0;
            line-height: 1.5;
        }

        .label-custom {
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 8px;
        }

        .info-card {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border: none;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="container-main">
            <div class="d-flex">
                <!-- Botón Regresar -->
                <a href="../../template/menu_farmaciaq.php"
                    style="color: white; background: linear-gradient(135deg, #2b2d7f 0%, #1a1c5a 100%);
                    border: none; border-radius: 8px; padding: 10px 16px; cursor: pointer; display: inline-block; 
                    text-decoration: none; box-shadow: 0 2px 8px rgba(43, 45, 127, 0.3); 
                    transition: all 0.3s ease; margin-right: 10px;">
                            ← Regresar
                </a>
            </div>
            <!-- Encabezado -->
            <div class="page-header">
                <h1><i class="fas fa-shopping-cart"></i> SOLICITAR PRODUCTOS A ALMACÉN</h1>
                <p class="mb-0">Sistema de solicitudes desde quirófano al almacén principal</p>
            </div>



            <!-- Estadísticas rápidas -->
            <div class="stats-container">
                <h5><i class="fas fa-chart-bar"></i> Resumen de Solicitudes</h5>
                <div class="row">
                    <div class="col-md-4">
                        <div class="stat-card" style="background: linear-gradient(45deg, var(--primary-color), var(--primary-dark)); color: white;">
                            <h3 id="stat-total">0</h3>
                            <small>Total Solicitudes</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card" style="background: linear-gradient(45deg, #28a745, #1e7e34); color: white;">
                            <h3 id="stat-confirmed">0</h3>
                            <small>Confirmadas</small>
                        </div>
                    </div>
                    <div class="col-md-4">
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
                    <li>Seleccione el producto que necesita del catálogo disponible</li>
                    <li>Indique la cantidad requerida</li>
                    <li>Confirme su solicitud para enviarla al almacén principal</li>
                    <li>Puede eliminar solicitudes antes de confirmarlas</li>
                </ul>
            </div>

            <!-- Formulario de solicitud -->
            <div class="form-container">
                <h5><i class="fas fa-plus-circle"></i> Nueva Solicitud</h5>
                <form action="" method="POST" id="medicamentos">
                    <div class="row align-items-end">
                        <div class="col-md-5">
                            <label class="label-custom"><i class="fas fa-pills"></i> Producto:</label>
                            <select name="item_id" class="form-control form-control-custom" id="productSelect" required>
                                <option value="">Seleccionar producto...</option>
                                <?php
                                // Consulta para obtener productos con stock disponible
                                $sql = "
                        SELECT 
                            ia.item_id, 
                            ia.item_code,
                            ia.item_name, 
                            ia.item_grams,
                            COALESCE(SUM(ea.existe_qty), 0) as stock_total
                        FROM item_almacen ia
                        LEFT JOIN existencias_almacenq ea ON ia.item_id = ea.item_id
                        GROUP BY ia.item_id, ia.item_code, ia.item_name, ia.item_grams
                        ORDER BY ia.item_name";
                                $result = $conexion->query($sql);
                                while ($row_datos = $result->fetch_assoc()) {
                                    $stockDisplay = $row_datos['stock_total'] > 0 ? $row_datos['stock_total'] : '0';
                                    echo "<option value='" . $row_datos['item_id'] . "'>" .
                                        $row_datos['item_name'] . " - " . $row_datos['item_grams'] . " (Stock: " . $stockDisplay . ")</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="label-custom"><i class="fas fa-sort-numeric-up"></i> Cantidad:</label>
                            <input type="number" name="qty" class="form-control form-control-custom" min="1" required placeholder="Cantidad">
                        </div>
                        <div class="col-md-4">
                            <label class="label-custom d-block">&nbsp;</label>
                            <button type="submit" class="btn btn-custom btn-success-custom btn-block">
                                <i class="fas fa-plus"></i> Agregar Solicitud
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabla de solicitudes -->
            <div class="table-container">
                <div class="d-flex justify-content-between align-items-center p-3" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); color: white;">

                    <table class="table table-striped table-hover mb-0" id="solicitudesTable">
                        <thead>
                            <tr>
                                <th><i class="fas fa-pills"></i> Producto</th>
                                <th><i class="fas fa-sort-numeric-up"></i> Cantidad</th>
                                <th><i class="fas fa-info-circle"></i> Estado</th>
                                <th><i class="fas fa-cogs"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($resultado->num_rows > 0) {
                                $no = 1;
                                while ($row_lista = $resultado->fetch_assoc()) {
                                    $isConfirmed = ($row_lista['confirma'] == 'SI');
                                    $statusClass = $isConfirmed ? 'status-confirmed' : 'status-pending';
                                    $statusText = $isConfirmed ? 'Confirmado' : 'Pendiente';
                                    $statusIcon = $isConfirmed ? 'fas fa-check-circle' : 'fas fa-clock';

                                    echo '<tr>
                            <td>
                                <div style="text-align: left;">
                                    <strong>' . htmlspecialchars($row_lista['item_name']) . '</strong><br>
                                    <small style="color: #6c757d; font-size: 12px;">
                                        <i class="fas fa-barcode"></i> ID: ' . $row_lista['item_id'] . '
                                    </small>
                                </div>
                            </td>
                            <td><span class="badge badge-info">' . $row_lista['solicita'] . ' unidades</span></td>
                            <td>
                                <span class="badge-status ' . $statusClass . '">
                                    <i class="' . $statusIcon . '"></i> ' . $statusText . '
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">';

                                    if (!$isConfirmed) {
                                        echo '<a class="btn btn-success btn-sm" href="?q=conf&cart_id=' . $row_lista['id_recib'] . '" title="Confirmar solicitud">
                                        <i class="fas fa-check"></i> Confirmar
                                    </a>';
                                    }

                                    echo '<a class="btn btn-danger btn-sm" href="?q=eliminar&cart_id=' . $row_lista['id_recib'] . '" title="Eliminar solicitud" onclick="return confirm(\'¿Está seguro de eliminar esta solicitud?\')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </a>
                                </div>
                            </td>
                        </tr>';
                                    $no++;
                                }
                            } else {
                                echo '<tr>
                    <td colspan="5">
                        <div class="no-data-message">
                            <i class="fas fa-inbox fa-3x mb-3" style="color: #6c757d;"></i>
                            <h4>No hay solicitudes registradas</h4>
                            <p>Agregue productos para crear nuevas solicitudes al almacén.</p>
                        </div>
                    </td>
                </tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                // Inicializar Select2 para búsqueda de productos
                $('#productSelect').select2({
                    placeholder: 'Buscar producto...',
                    allowClear: true,
                    width: '100%'
                });

                // Función para actualizar estadísticas
                function updateStats() {
                    var totalRows = $("#solicitudesTable tbody tr").length;
                    var confirmedRows = 0;
                    var pendingRows = 0;

                    if (totalRows === 1 && $("#solicitudesTable tbody tr").first().find('.no-data-message').length > 0) {
                        totalRows = 0;
                    }

                    $("#solicitudesTable tbody tr").each(function() {
                        var row = $(this);
                        if (row.find('.status-confirmed').length > 0) {
                            confirmedRows++;
                        } else if (row.find('.status-pending').length > 0) {
                            pendingRows++;
                        }
                    });

                    $("#stat-total").text(totalRows);
                    $("#stat-confirmed").text(confirmedRows);
                    $("#stat-pending").text(pendingRows);
                }

                // Validación del formulario
                $('#medicamentos').on('submit', function(e) {
                    var producto = $('#productSelect').val();
                    var cantidad = $('input[name="qty"]').val();

                    if (!producto) {
                        e.preventDefault();
                        alert('Por favor seleccione un producto.');
                        return false;
                    }

                    if (!cantidad || cantidad <= 0) {
                        e.preventDefault();
                        alert('Por favor ingrese una cantidad válida mayor a 0.');
                        return false;
                    }
                });

                // Inicializar estadísticas
                updateStats();
            });
        </script>
</body>

</html>