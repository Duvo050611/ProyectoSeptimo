<?php
session_start();
include "../../conexionbd.php";

// Consulta para obtener los medicamentos desde la tabla `item_almacen`
$resultado = $conexion->query("SELECT * FROM item_almacen") or die($conexion->error);

$usuario = $_SESSION['login'];

// Incluye el encabezado correspondiente según el rol del usuario
if ($usuario['id_rol'] == 7) {
    include "../header_farmaciac.php";
} else if ($usuario['id_rol'] == 3) {
    include "../../enfermera/header_enfermera.php";
} else if ($usuario['id_rol'] == 4 || $usuario['id_rol'] == 5) {
    include "../header_farmaciac.php";
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
    FROM kardex_almacen ka
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
        ka.id_compra,
        ka.factura,
        ka.id_usua
           FROM kardex_almacen ka
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
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .total-row {
            background-color: #2b2d7f;
            color: white;
        }

        .ultima-existencia {
            background-color: #2b2d7f;
            color: white;
        }

        .table-responsive {
            max-height: 80vh;
            overflow-x: auto;
            overflow-y: auto;
            width: 100%;
        }

        .container.box {
            max-width: 98%;
            width: 98%;
            margin: 0 auto;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .table {
            font-size: 12px;
            min-width: 100%;
        }

        .table th,
        .table td {
            padding: 4px 6px;
            white-space: nowrap;
            text-align: center;
            vertical-align: middle;
        }

        .table th {
            font-size: 11px;
            font-weight: bold;
        }

        .pagination a {
            padding: 8px 12px;
            text-decoration: none;
            background-color: #2b2d7f;
            color: white;
            border-radius: 5px;
            margin: 0 5px;
        }

        .pagination a:hover {
            background-color: #2b2d7f;
        }

        .pagination .current {
            background-color: #ff7f50;
            color: white;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <section class="content container-fluid" style="padding: 10px;">
        <a href="../../template/menu_farmaciacentral.php" class="btn btn-danger" style="margin-left: 10px; margin-bottom: 10px;">Regresar</a>

        <div class="container box">
            <div class="content">
                <div class="thead" style="background-color: #2b2d7f; margin: 5px auto; padding: 5px; color: white; width: fit-content; text-align: center; border-radius: 5px;">
                    <h1 style="font-size: 26px; margin: 2;">KARDEX</h1>
                </div>
                <br><br>
                <form method="POST" action="">
                    <div class="form-row align-items-end">
                        <!-- Input Fecha Inicial -->
                        <div class="form-group col-md-2">
                            <label>Fecha Inicial:</label>
                            <input type="date" class="form-control" name="inicial" value="<?= $fecha_inicial ?>">
                        </div>

                        <!-- Input Fecha Final -->
                        <div class="form-group col-md-2">
                            <label>Fecha Final:</label>
                            <input type="date" class="form-control" name="final" value="<?= $fecha_final ?>">
                        </div>

                        <!-- Input Medicamento / Insumo -->
                        <div class="form-group col-md-3">
                            <label>Medicamento / Insumo:</label>
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

                        <!-- Input Lote -->
                        <div class="form-group col-md-2">
                            <label>Lote:</label>
                            <input type="text" class="form-control" name="lote" placeholder="Ej. ABC123" value="<?= $lote ?>">
                        </div>

                        <!-- Contenedor de Botones: Filtrar y Borrar Filtros alineados con los inputs -->
                        <div class="form-group col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-success w-100 mr-2">Filtrar</button>
                            <a href="kardex.php" class="btn btn-danger w-100">Borrar Filtros</a>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col text-center">
                            <a href="entradas_almacen_historial.php" class="btn btn-success mr-2 custom-btn">ENTRADAS</a>
                            <a href="salidas_almacen_historial.php" class="btn btn-success mr-2 custom-btn">SALIDAS</a>
                            <a href="devoluciones_almacen_historial.php" class="btn btn-success mr-2 custom-btn">DEVOLUCIONES</a>
                            <a href="mermas_almacen_historial.php" class="btn btn-success mr-2 custom-btn">MERMAS</a>

                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead class="thead" style="background-color: #2b2d7f; color:white;">
                            <tr>
                                <th>FECHA</th>
                                <th>ITEMID</th>
                                <th>MEDICAMENTO</th>
                                <th>LOTE</th>
                                <th>CADUCIDAD</th>
                                <th>ENTRADA</th>
                                <th>SALIDA</th>
                                <th>MOVIMIENTO</th>
                                <th>UBICACIÓN</th>
                                <th>IDCOMPRA</th>
                                <th>FACTURA</th>
                                <th>USUARIO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $resultado2->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= date('d/m/Y H:i', strtotime($row['fecha'])) ?></td>
                                    <td><?= $row['item_id'] ?></td>
                                    <td><?= $row['item_name'] . ', ' . $row['item_grams'] ?> g</td>
                                    <td><?= $row['lote'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($row['caducidad'])) ?></td>
                                    <td><?= $row['kardex_entradas'] ?></td>
                                    <td><?= $row['kardex_salidas'] ?></td>
                                    <td><?= $row['kardex_movimiento'] ?></td>
                                    <td><?= $row['kardex_ubicacion'] ?></td>
                                    <td><?= $row['id_compra'] ?></td>
                                    <td><?= $row['factura'] ?></td>
                                    <td><?= $row['id_usua'] ?></td>
                                </tr>
                                <?php $totalExistencia += $row['kardex_qty']; ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

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
    </section>
</body>

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#mibuscador').select2({
            placeholder: "Seleccione un medicamento",
            allowClear: true
        });
    });
</script>

</html>