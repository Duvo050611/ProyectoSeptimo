<?php
session_start();
include "../../conexionbd.php";

// Consulta para obtener los medicamentos desde la tabla `item_almacen`
$resultado = $conexion->query("
    SELECT * FROM item_almacen
") or die($conexion->error);

$usuario = $_SESSION['login'];

// Incluye el encabezado correspondiente según el rol del usuario
if ($usuario['id_rol'] == 8) {
    include "../header_farmaciaq.php";
} else if ($usuario['id_rol'] == 3) {
    include "../../enfermera/header_enfermera.php";
} else if ($usuario['id_rol'] == 4 || $usuario['id_rol'] == 5) {
    include "../header_farmaciaq.php";
} else {
    echo "<script>window.Location='../../index.php';</script>";
    exit;
}

// Variables para fechas y medicamento
$fecha_inicial = isset($_POST['inicial']) ? $_POST['inicial'] : null;
$fecha_final = isset($_POST['final']) ? $_POST['final'] : null;
$item_id = isset($_POST['item_id']) ? mysqli_real_escape_string($conexion, $_POST['item_id']) : null;


// Determinar si se presionó el botón "ULT.REGISTROS"
if (isset($_POST['ult_registros'])) {
    // Si se presionó "ULT.REGISTROS", obtener los últimos 20 registros
    $query = "
        SELECT 
            ka.kardex_fecha AS fecha,
            ia.item_name AS item_name,
            ka.kardex_lote AS lote,
            ka.kardex_caducidad AS caducidad,
            ka.kardex_inicial,
            ka.kardex_entradas,
            ka.kardex_salidas,
            ka.kardex_qty,
            ka.kardex_dev_stock,
            ka.kardex_dev_merma,
            ka.kardex_movimiento,
            ua.nombre_ubicacion AS kardex_ubicacion,
            ka.kardex_destino,
            ka.id_usua,
            ka.id_surte
        FROM kardex_almacenq ka
        INNER JOIN item_almacen ia ON ka.item_id = ia.item_id
        LEFT JOIN ubicaciones_almacen ua ON ka.kardex_ubicacion = ua.ubicacion_id
        ORDER BY ka.kardex_fecha DESC LIMIT 20
    ";
} else {
    // Consulta general con filtros si no se presionó "ULT.REGISTROS"
    $query = "
        SELECT 
            ka.kardex_fecha AS fecha,
            ia.item_name AS item_name,
            ka.kardex_lote AS lote,
            ka.kardex_caducidad AS caducidad,
            ka.kardex_inicial,
            ka.kardex_entradas,
            ka.kardex_salidas,
            ka.kardex_qty,
            ka.kardex_dev_stock,
            ka.kardex_dev_merma,
            ka.kardex_movimiento,
            ua.nombre_ubicacion AS kardex_ubicacion,
            ka.kardex_destino,
            ka.id_usua,
            ka.id_surte
        FROM kardex_almacenq ka
        INNER JOIN item_almacen ia ON ka.item_id = ia.item_id
        LEFT JOIN ubicaciones_almacen ua ON ka.kardex_ubicacion = ua.ubicacion_id
    ";

    // Aplicar filtros de fechas y medicamento
    if ($fecha_inicial && $fecha_final && $item_id) {
        $query .= " WHERE ka.kardex_fecha BETWEEN '$fecha_inicial' AND '$fecha_final' AND ia.item_id = '$item_id'";
    } elseif ($item_id) {
        $query .= " WHERE ia.item_id = '$item_id'";
    } elseif ($fecha_inicial && $fecha_final) {
        $query .= " WHERE ka.kardex_fecha BETWEEN '$fecha_inicial' AND '$fecha_final'";
    }
}

$resultado2 = $conexion->query($query) or die($conexion->error);

$totalExistencia = 0;
if ($item_id) {
    $query_existencia = "
        SELECT SUM(existe_qty) AS totalExistencia 
        FROM existencias_almacenQ 
        WHERE item_id = '$item_id'
    ";
    $resultado_existencia = $conexion->query($query_existencia) or die($conexion->error);

    if ($row_existencia = $resultado_existencia->fetch_assoc()) {
        $totalExistencia = $row_existencia['totalExistencia'] ?? 0;
    }
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kardex Farmacia Central</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
    <style>
        :root {
            --primary-color: #2b2d7f;
            --primary-dark: #1f2166;
            --primary-light: #3f418a;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .btn-custom {
            border-radius: 25px;
            padding: 10px 20px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin: 5px;
        }
        
        .btn-danger-custom {
            background: linear-gradient(45deg, #dc3545, #c82333);
            border: none;
            color: white;
        }
        
        .btn-danger-custom:hover {
            background: linear-gradient(45deg, #c82333, #bd2130);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220,53,69,0.3);
            color: white;
        }
        
        .btn-success-custom {
            background: linear-gradient(45deg, #28a745, #1e7e34);
            border: none;
            color: white;
        }
        
        .btn-success-custom:hover {
            background: linear-gradient(45deg, #1e7e34, #155724);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40,167,69,0.3);
            color: white;
        }
        
        .btn-info-custom {
            background: linear-gradient(45deg, #17a2b8, #138496);
            border: none;
            color: white;
        }
        
        .btn-info-custom:hover {
            background: linear-gradient(45deg, #138496, #117a8b);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(23,162,184,0.3);
            color: white;
        }
        
        .btn-warning-custom {
            background: linear-gradient(45deg, #ffc107, #e0a800);
            border: none;
            color: #212529;
        }
        
        .btn-warning-custom:hover {
            background: linear-gradient(45deg, #e0a800, #d39e00);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255,193,7,0.3);
            color: #212529;
        }
        
        .btn-purple-custom {
            background: linear-gradient(45deg, #6f42c1, #5a35a0);
            border: none;
            color: white;
        }
        
        .btn-purple-custom:hover {
            background: linear-gradient(45deg, #5a35a0, #4c2a85);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(111,66,193,0.3);
            color: white;
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
        
        .form-container {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .form-control {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 10px 15px;
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(43,45,127,0.25);
            border-color: var(--primary-light);
        }
        
        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .table thead th {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            padding: 15px 10px;
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
        }
        
        .table tbody td {
            padding: 12px 8px;
            font-size: 0.9rem;
            vertical-align: middle;
        }
        
        .total-row {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark)) !important;
            color: white;
            font-weight: bold;
        }
        
        .select2-container--default .select2-selection--single {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            height: calc(1.5em + 0.75rem + 2px);
        }
        
        .select2-container--default .select2-selection--single:focus {
            border-color: var(--primary-light);
        }
        
        .container-main {
            max-width: 98%;
            margin: 0 auto;
        }
        
        .btn-group-custom {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
        }
        
        .label-custom {
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 8px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="page-header">
            <h1><i class="fas fa-clipboard-list"></i> KARDEX FARMACIA CENTRAL</h1>
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

        <div class="container-main">
            <!-- Formulario de filtros -->
            <div class="form-container">
                <form method="POST" id="medicamentos">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label class="label-custom"><i class="fas fa-calendar-alt"></i> Fecha Inicial:</label>
                            <input type="date" class="form-control" name="inicial" value="<?= $fecha_inicial ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="label-custom"><i class="fas fa-calendar-check"></i> Fecha Final:</label>
                            <input type="date" class="form-control" name="final" value="<?= $fecha_final ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="label-custom"><i class="fas fa-pills"></i> Medicamento:</label>
                            <select name="item_id" class="form-control" id="mibuscador">
                                <option value="">Seleccione un medicamento</option>
                                <?php
                                $sql = "SELECT * FROM item_almacen ORDER BY item_name";
                                $result = $conexion->query($sql);
                                while ($row_datos = $result->fetch_assoc()) {
                                    $selected = ($item_id == $row_datos['item_id']) ? 'selected' : '';
                                    echo "<option value='" . $row_datos['item_id'] . "' $selected>" . 
                                         $row_datos['item_name'] . " - " . $row_datos['item_grams'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-custom btn-success-custom" style="width: 100%;">
                                <i class="fas fa-search"></i> BUSCAR
                            </button>
                        </div>
                    </div>
                    
                    <!-- Botones de acción -->
                    <div class="btn-group-custom">
                        <button type="submit" name="ult_registros" class="btn btn-custom btn-info-custom">
                            <i class="fas fa-history"></i> ULT.REGISTROS
                        </button>
                        <a href="entradas_almacenq_historial.php" class="btn btn-custom btn-success-custom">
                            <i class="fas fa-arrow-down"></i> RESURTIMIENTO
                        </a>
                        <a href="salidas_almacenq_historial.php" class="btn btn-custom btn-warning-custom">
                            <i class="fas fa-arrow-up"></i> SALIDAS
                        </a>
                        <a href="devoluciones_almacenq_historial.php" class="btn btn-custom btn-info-custom">
                            <i class="fas fa-undo"></i> DEVOLUCIONES
                        </a>
                        <a href="mermas_almacenq_historial.php" class="btn btn-custom btn-purple-custom">
                            <i class="fas fa-exclamation-triangle"></i> MERMAS
                        </a>
                    </div>
                </form>
            </div>
            <!-- Tabla de resultados -->
            <div class="table-container">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                        <tr>
                            <th><i class="fas fa-calendar"></i> FECHA</th>
                            <th><i class="fas fa-pills"></i> MEDICAMENTO</th>
                            <th><i class="fas fa-tag"></i> LOTE</th>
                            <th><i class="fas fa-calendar-times"></i> CADUCIDAD</th>
                            <th><i class="fas fa-sort-numeric-up"></i> INICIAL</th>
                            <th><i class="fas fa-arrow-down text-success"></i> RESURTIMIENTO</th>
                            <th><i class="fas fa-arrow-up text-warning"></i> SALIDA</th>
                            <th><i class="fas fa-undo text-info"></i> DEV. STOCK</th>
                            <th><i class="fas fa-exclamation-triangle text-danger"></i> DEV. MERMA</th>
                            <th><i class="fas fa-exchange-alt"></i> MOVIMIENTO</th>
                            <th><i class="fas fa-map-marker-alt"></i> UBICACIÓN</th>
                            <th><i class="fas fa-shipping-fast"></i> DESTINO</th>
                            <th><i class="fas fa-user"></i> U.RECIBE</th>
                            <th><i class="fas fa-user-check"></i> U.SURTE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultado2->fetch_assoc()) { ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($row['fecha'])) ?></td>
                                <td><?= $row['item_name'] ?></td>
                                <td><?= $row['lote'] ?></td>
                                <td><?= date('d/m/Y', strtotime($row['caducidad'])) ?></td>
                                <td><?= $row['kardex_inicial'] ?></td>
                                <td><?= $row['kardex_entradas'] ?></td>
                                <td><?= $row['kardex_salidas'] ?></td>
                                <td><?= $row['kardex_dev_stock'] ?></td>
                                <td><?= $row['kardex_dev_merma'] ?></td>
                                <td><?= $row['kardex_movimiento'] ?></td>
                                <td><?= $row['kardex_ubicacion'] ?></td>
                                <td><?= $row['kardex_destino'] ?></td>
                                <td><?= $row['id_usua'] ?></td>
                                <td><?= $row['id_surte'] ?></td>
                            </tr>
                            <?php $totalExistencia += $row['kardex_qty']; ?>
                        <?php } ?>
                    </tbody>
                    <?php if ($item_id) { ?>
                        <tfoot>
                            <tr class="total-row">
                                <td colspan="7" style="text-align: right;"><strong><i class="fas fa-calculator"></i> Total Existencia:</strong></td>
                                <td><strong><?= $totalExistencia ?></strong></td>
                                <td colspan="7"></td>
                            </tr>
                        </tfoot>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
    
    <footer class="main-footer mt-4">
        <?php include("../../template/footer.php"); ?>
    </footer>

    <!-- Scripts -->
    <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- FastClick -->
    <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
    
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#mibuscador').select2({
                placeholder: "🔍 Seleccione un medicamento...",
                allowClear: true,
                width: '100%'
            });
            
            // Efecto hover en las filas de la tabla
            $('.table tbody tr').hover(
                function() {
                    $(this).css('background-color', 'rgba(43,45,127,0.1)');
                },
                function() {
                    $(this).css('background-color', '');
                }
            );
        });
    </script>
</body>
</html>