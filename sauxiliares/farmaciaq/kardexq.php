<?php
session_start();
include "../../conexionbd.php";

// Consulta para obtener los medicamentos desde la tabla `item_almacen`
$resultado = $conexion->query("SELECT * FROM item_almacen") or die($conexion->error);

$usuario = $_SESSION['login'];

// Incluye el encabezado correspondiente según el rol del usuario
if ($usuario['id_rol'] == 7) {
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
$fecha_inicial = isset($_POST['inicial']) ? $_POST['inicial'] : (isset($_GET['inicial']) ? $_GET['inicial'] : null);
$fecha_final = isset($_POST['final']) ? $_POST['final'] : (isset($_GET['final']) ? $_GET['final'] : null);
$item_id = isset($_POST['item_id']) ? mysqli_real_escape_string($conexion, $_POST['item_id']) : (isset($_GET['item_id']) ? mysqli_real_escape_string($conexion, $_GET['item_id']) : null);
$lote = isset($_POST['lote']) ? mysqli_real_escape_string($conexion, $_POST['lote']) : (isset($_GET['lote']) ? mysqli_real_escape_string($conexion, $_GET['lote']) : '');

// Paginación
$registros_por_pagina = 20;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) {
    $pagina = 1;
}

// Calcular el número total de registros
$query_total = "
    SELECT COUNT(*) AS total 
    FROM kardex_almacenh ka
    INNER JOIN item_almacen ia ON ka.item_id = ia.item_id
    LEFT JOIN ubicaciones_almacen ua ON ka.kardex_ubicacion = ua.ubicacion_id
    WHERE 1 ";

if ($fecha_inicial && $fecha_final && $item_id && $lote) {
    $query_total .= " AND ka.kardex_fecha BETWEEN '$fecha_inicial' AND '$fecha_final' AND ia.item_id = '$item_id' AND ka.kardex_lote LIKE '%$lote%'";
} elseif ($fecha_inicial && $fecha_final && $item_id) {
    $query_total .= " AND ka.kardex_fecha BETWEEN '$fecha_inicial' AND '$fecha_final' AND ia.item_id = '$item_id'";
} elseif ($fecha_inicial && $fecha_final && $lote) {
    $query_total .= " AND ka.kardex_fecha BETWEEN '$fecha_inicial' AND '$fecha_final' AND ka.kardex_lote LIKE '%$lote%'";
} elseif ($item_id && $lote) {
    $query_total .= " AND ia.item_id = '$item_id' AND ka.kardex_lote LIKE '%$lote%'";
} elseif ($item_id) {
    $query_total .= " AND ia.item_id = '$item_id'";
} elseif ($fecha_inicial && $fecha_final) {
    $query_total .= " AND ka.kardex_fecha BETWEEN '$fecha_inicial' AND '$fecha_final'";
} elseif ($lote) {
    $query_total .= " AND ka.kardex_lote LIKE '%$lote%'";
}

$resultado_total = $conexion->query($query_total) or die($conexion->error);
$total_registros = $resultado_total->fetch_assoc()['total'];

// Calcular el total de páginas
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Asegurar que la página esté dentro del rango
if ($pagina < 1) {
    $pagina = 1;
}

// Calcular el desplazamiento (OFFSET)
$offset = ($pagina - 1) * $registros_por_pagina;
if ($offset < 0) {
    $offset = 0; // Aseguramos que el offset no sea negativo
}

// Consulta con LIMIT y OFFSET para obtener los registros paginados
$query = "
    SELECT 
        ka.item_id,
        ka.kardex_fecha AS fecha,
        ia.item_name,
        ia.item_grams,
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
        ka.id_surte,
    ka.motivo
    FROM kardex_almacenh ka
    INNER JOIN item_almacen ia ON ka.item_id = ia.item_id
    LEFT JOIN ubicaciones_almacen ua ON ka.kardex_ubicacion = ua.ubicacion_id
";

if ($fecha_inicial && $fecha_final && $item_id && $lote) {
    $query .= " WHERE ka.kardex_fecha BETWEEN '$fecha_inicial' AND '$fecha_final' AND ia.item_id = '$item_id' AND ka.kardex_lote LIKE '%$lote%'";
} elseif ($fecha_inicial && $fecha_final && $item_id) {
    $query .= " WHERE ka.kardex_fecha BETWEEN '$fecha_inicial' AND '$fecha_final' AND ia.item_id = '$item_id'";
} elseif ($fecha_inicial && $fecha_final && $lote) {
    $query .= " WHERE ka.kardex_fecha BETWEEN '$fecha_inicial' AND '$fecha_final' AND ka.kardex_lote LIKE '%$lote%'";
} elseif ($item_id && $lote) {
    $query .= " WHERE ia.item_id = '$item_id' AND ka.kardex_lote LIKE '%$lote%'";
} elseif ($item_id) {
    $query .= " WHERE ia.item_id = '$item_id'";
} elseif ($fecha_inicial && $fecha_final) {
    $query .= " WHERE ka.kardex_fecha BETWEEN '$fecha_inicial' AND '$fecha_final'";
} elseif ($lote) {
    $query .= " WHERE ka.kardex_lote LIKE '%$lote%'";
}

$query .= " ORDER BY ka.kardex_fecha DESC LIMIT $registros_por_pagina OFFSET $offset";

$resultado2 = $conexion->query($query) or die($conexion->error);

$totalExistencia = 0;
if ($item_id) {
    $query_existencia = "
        SELECT SUM(existe_qty) AS totalExistencia 
        FROM existencias_almacen 
        WHERE item_id = '$item_id'
    ";
    $resultado_existencia = $conexion->query($query_existencia) or die($conexion->error);

    if ($row_existencia = $resultado_existencia->fetch_assoc()) {
        $totalExistencia = $row_existencia['totalExistencia'] ?? 0;
    }
}
?>

<!DOCTYPE html>
<!-- Bootstrap CSS -->
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kardex Farmacia Central</title>


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
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.3);
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
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.3);
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
            box-shadow: 0 6px 20px rgba(23, 162, 184, 0.3);
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
            box-shadow: 0 6px 20px rgba(255, 193, 7, 0.3);
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
            box-shadow: 0 6px 20px rgba(111, 66, 193, 0.3);
            color: white;
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

        .form-control {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 10px 15px;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(43, 45, 127, 0.25);
            border-color: var(--primary-light);
        }

        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            max-height: 80vh;
            overflow-x: auto;
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
            background-color: rgba(43, 45, 127, 0.1);
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
            height: 56px !important;
            padding: 0 15px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 52px !important;
            padding: 0 !important;
            color: #495057 !important;
            font-size: 1rem !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 52px !important;
            right: 10px !important;
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

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            padding: 8px 12px;
            text-decoration: none;
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            color: white;
            border-radius: 8px;
            margin: 0 5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .pagination a:hover {
            background: linear-gradient(45deg, var(--primary-dark), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(43, 45, 127, 0.3);
            color: white;
        }

        .pagination .current {
            background: linear-gradient(45deg, #ffc107, #e0a800);
            color: #212529;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="page-header">
            <h1><i class="fas fa-clipboard-list"></i> KARDEX QUIROFANO</h1>
        </div>

        <!-- Botón de regreso -->
        <div class="col-12 d-flex justify-content-start mb-3">
            <a href="../../template/menu_farmaciaq.php" class="btn btn-custom btn-danger-custom">
                <i class="fas fa-arrow-left"></i> Regresar
            </a>
        </div>


        <div class="container-main">
            <!-- Formulario de filtros -->
            <div class="form-container">
                <form method="POST" action="">
                    <div class="row align-items-end">
                        <div class="col-md-2">
                            <label class="label-custom"><i class="fas fa-calendar-alt"></i> Fecha Inicial:</label>
                            <input type="date" class="form-control" name="inicial" value="<?= $fecha_inicial ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="label-custom"><i class="fas fa-calendar-check"></i> Fecha Final:</label>
                            <input type="date" class="form-control" name="final" value="<?= $fecha_final ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="label-custom"><i class="fas fa-pills"></i> Medicamento / Insumo:</label>
                            <select name="item_id" class="form-control" id="mibuscador">
                                <option value="">Seleccione un medicamento o insumo</option>
                                <?php
                                $sql = "SELECT * FROM item_almacen ORDER BY item_name";
                                $result = $conexion->query($sql);
                                while ($row_datos = $result->fetch_assoc()) {
                                    $selected = ($item_id == $row_datos['item_id']) ? 'selected' : '';
                                    echo "<option value='" . $row_datos['item_id'] . "' $selected>" . $row_datos['item_name'] . ', ' . $row_datos['item_grams'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="label-custom"><i class="fas fa-tag"></i> Lote:</label>
                            <input type="text" class="form-control" name="lote" placeholder="Ej. ABC123" value="<?= $lote ?>">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-custom btn-success-custom mr-2" style="width: 48%;">
                                <i class="fas fa-search"></i> FILTRAR
                            </button>
                            <a href="kardexh.php" class="btn btn-custom btn-danger-custom" style="width: 48%;">
                                <i class="fas fa-times"></i> BORRAR
                            </a>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="btn-group-custom">
                        <a href="entradas_almacenh_historial.php" class="btn btn-custom btn-success-custom">
                            <i class="fas fa-arrow-down"></i> RESURTIMIENTO
                        </a>
                        <a href="salidas_almacenh_historial.php" class="btn btn-custom btn-warning-custom">
                            <i class="fas fa-arrow-up"></i> SALIDAS
                        </a>
                        <a href="devoluciones_almacenh_historial.php" class="btn btn-custom btn-info-custom">
                            <i class="fas fa-undo"></i> DEVOLUCIONES
                        </a>
                        <a href="mermas_almacenh_historial.php" class="btn btn-custom btn-purple-custom">
                            <i class="fas fa-exclamation-triangle"></i> MERMAS
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tabla de resultados -->
            <div class="table-container">
                <div style="overflow-x: auto; width: 100%;">
                    <table class="table table-bordered table-striped mb-0" style="min-width: 1200px;">
                        <thead>
                            <tr>
                                <th><i class="fas fa-calendar"></i> FECHA</th>
                                <th><i class="fas fa-hashtag"></i> ITEMID</th>
                                <th><i class="fas fa-pills"></i> MEDICAMENTO</th>
                                <th><i class="fas fa-tag"></i> LOTE</th>
                                <th><i class="fas fa-calendar-times"></i> CADUCIDAD</th>
                                <th><i class="fas fa-arrow-down text-success"></i> ENTRADA</th>
                                <th><i class="fas fa-arrow-up text-warning"></i> SALIDA</th>
                                <th><i class="fas fa-exchange-alt"></i> MOVIMIENTO</th>
                                <th><i class="fas fa-map-marker-alt"></i> UBICACIÓN</th>
                                <th><i class="fas fa-shipping-fast"></i> DESTINO</th>
                                <th><i class="fas fa-clipboard-list"></i> MOTIVO</th>
                                <th><i class="fas fa-user"></i> U.RECIBE</th>
                                <th><i class="fas fa-user-check"></i> U.SURTE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $resultado2->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($row['fecha'])) ?></td>
                                    <td><?= $row['item_id'] ?></td>
                                    <td><?= $row['item_name'] . ', ' . $row['item_grams'] ?> g</td>
                                    <td><?= $row['lote'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($row['caducidad'])) ?></td>
                                    <td><?= $row['kardex_entradas'] ?></td>
                                    <td><?= $row['kardex_salidas'] ?></td>
                                    <td><?= $row['kardex_movimiento'] ?></td>
                                    <td><?= $row['kardex_ubicacion'] ?></td>
                                    <td><?= $row['kardex_destino'] ?></td>
                                    <td><?= $row['motivo'] ?></td>
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
                                    <td colspan="6"></td>
                                </tr>
                            </tfoot>
                        <?php } ?>
                    </table>
                </div>
            </div>

            <!-- Paginación -->
            <div class="pagination">
                <?php
                // Establecer el rango de páginas a mostrar
                $rango = 5;

                // Determinar el inicio y fin del rango de páginas a mostrar
                $inicio = max(1, $pagina - $rango);
                $fin = min($total_paginas, $pagina + $rango);

                // Mostrar el enlace a la página anterior
                if ($pagina > 1) {
                    echo '<a href="?pagina=1&inicial=' . urlencode($fecha_inicial) . '&final=' . urlencode($fecha_final) . '&item_id=' . urlencode($item_id) . '&lote=' . urlencode($lote) . '">&laquo; Primero</a>';
                    echo '<a href="?pagina=' . ($pagina - 1) . '&inicial=' . urlencode($fecha_inicial) . '&final=' . urlencode($fecha_final) . '&item_id=' . urlencode($item_id) . '&lote=' . urlencode($lote) . '">&lt; Anterior</a>';
                }

                // Mostrar las páginas del rango
                for ($i = $inicio; $i <= $fin; $i++) {
                    echo '<a href="?pagina=' . $i . '&inicial=' . urlencode($fecha_inicial) . '&final=' . urlencode($fecha_final) . '&item_id=' . urlencode($item_id) . '&lote=' . urlencode($lote) . '" class="' . ($i == $pagina ? 'current' : '') . '">' . $i . '</a>';
                }

                // Mostrar el enlace a la página siguiente
                if ($pagina < $total_paginas) {
                    echo '<a href="?pagina=' . ($pagina + 1) . '&inicial=' . urlencode($fecha_inicial) . '&final=' . urlencode($fecha_final) . '&item_id=' . urlencode($item_id) . '&lote=' . urlencode($lote) . '">Siguiente &gt;</a>';
                    echo '<a href="?pagina=' . $total_paginas . '&inicial=' . urlencode($fecha_inicial) . '&final=' . urlencode($fecha_final) . '&item_id=' . urlencode($item_id) . '&lote=' . urlencode($lote) . '">Último &raquo;</a>';
                }
                ?>
            </div>
        </div>
    </div>



    <!-- Scripts -->
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#mibuscador').select2({
                placeholder: "🔍 Seleccione un medicamento...",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
</body>

</html>