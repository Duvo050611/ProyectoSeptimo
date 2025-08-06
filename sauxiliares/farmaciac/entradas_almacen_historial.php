<?php
session_start();
include "../../conexionbd.php";

ob_start();

$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];

if (isset($usuario['id_rol'])) {
    if ($usuario['id_rol'] == 11 || $usuario['id_rol'] == 4 || $usuario['id_rol'] == 5) {
        include "../header_farmaciac.php";
    } else {
        session_unset();
        session_destroy();
        echo "<script>window.location='../../index.php';</script>";
        exit();
    }
}

// Inicializar variables del formulario
$inicial = isset($_POST['inicial']) ? $_POST['inicial'] : (isset($_GET['inicial']) ? $_GET['inicial'] : '');
$final = isset($_POST['final']) ? $_POST['final'] : (isset($_GET['final']) ? $_GET['final'] : '');
$item_id = isset($_POST['item_id']) ? $_POST['item_id'] : (isset($_GET['item_id']) ? $_GET['item_id'] : '');
$ubicacion_id = isset($_POST['ubicacion_id']) ? $_POST['ubicacion_id'] : (isset($_GET['ubicacion_id']) ? $_GET['ubicacion_id'] : '');
$lote = isset($_POST['lote']) ? $_POST['lote'] : (isset($_GET['lote']) ? $_GET['lote'] : '');

$where = "";
$condiciones = [];

if (!empty($inicial) && !empty($final)) {
    $inicial_sql = mysqli_real_escape_string($conexion, $inicial);
    $final_sql = mysqli_real_escape_string($conexion, $final);
    $final_sql = date("Y-m-d H:i:s", strtotime($final_sql . " + 1 day"));
    $condiciones[] = "e.entrada_fecha >= '$inicial_sql' AND e.entrada_fecha <= '$final_sql'";
}

if (!empty($item_id)) {
    $item_id_sql = intval($item_id);
    $condiciones[] = "e.item_id = $item_id_sql";
}

if (!empty($lote)) {
    $lote_sql = mysqli_real_escape_string($conexion, $lote);
    $condiciones[] = "e.entrada_lote LIKE '%$lote_sql%'";
}

if (!empty($condiciones)) {
    $where = "WHERE " . implode(" AND ", $condiciones);
}

// Paginación
$registros_por_pagina = 20;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) {
    $pagina = 1;  // Asegura que la página no sea menor que 1
}

// Calcular el número total de registros
$query_total = "
    SELECT COUNT(*) AS total 
    FROM entradas_almacen e
    JOIN item_almacen i ON e.item_id = i.item_id
    JOIN ubicaciones_almacen u ON e.ubicacion_id = u.ubicacion_id
    $where
";
$resultado_total = $conexion->query($query_total);
$total_registros = $resultado_total->fetch_assoc()['total'];

// Calcular el total de páginas
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Asegurar que la página esté dentro del rango
if ($pagina > $total_paginas) {
    $pagina = $total_paginas;
}

// Calcular el desplazamiento (OFFSET)
$offset = ($pagina - 1) * $registros_por_pagina;
if ($offset < 0) {
    $offset = 0;  // Asegura que el offset nunca sea negativo
}

// Consulta con LIMIT y OFFSET para obtener los registros paginados
$query = "
    SELECT 
        e.entrada_id, 
        e.entrada_fecha, 
        i.item_id,
        i.item_name,
        i.item_grams,
        e.entrada_lote, 
        e.entrada_caducidad, 
        e.entrada_qty, 
        e.entrada_unidosis, 
        i.item_costs AS entrada_costo,
        e.id_usua, 
        u.nombre_ubicacion 
    FROM 
        entradas_almacen e
    JOIN 
        item_almacen i ON e.item_id = i.item_id
    JOIN 
        ubicaciones_almacen u ON e.ubicacion_id = u.ubicacion_id
    $where
    ORDER BY 
        e.entrada_fecha DESC
    LIMIT $registros_por_pagina OFFSET $offset
";

$resultado = $conexion->query($query) or die($conexion->error);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entradas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
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
    <a href='kardex.php' class="btn btn-danger" style="margin-left: 10px; margin-bottom: 5px;">Regresar</a>
    <div class="container box">
        <div class="content">
            <div class="thead" style="background-color: white;margin-top: 10px; color: black; font-size: 20px;">
                <div class="thead" style="background-color: #2b2d7f; margin: 5px auto; padding: 5px; color: white; width: fit-content; text-align: center; border-radius: 5px;">
                    <h1 style="font-size: 26px; margin: 0; color: white;">ENTRADAS</h1>
                </div>
            </div>

            <br><br>
             <form method="POST" action="">
                    <div class="form-row align-items-end">
                        <!-- Input Fecha Inicial -->
                        <div class="form-group col-md-2">
                            <label>Fecha Inicial:</label>
                            <input type="date" class="form-control" name="inicial" value="<?= $inicial ?>">
                        </div>

                        <!-- Input Fecha Final -->
                        <div class="form-group col-md-2">
                            <label>Fecha Final:</label>
                            <input type="date" class="form-control" name="final" value="<?= $final ?>">
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
                            <a href="entradas_almacen_historial.php" class="btn btn-danger w-100">Borrar Filtros</a>
                        </div>
                    </div>

                </form>

            <style>
                .custom-select-small {
                    font-size: 0.9rem;
                    padding: 5px;
                    height: auto;
                }
            </style>

            <br><br>

            <?php if ($resultado->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead" style="background-color: #2b2d7f; color:white;">
                            <tr>
                                <th>FECHA</th>
                                <th>ITEMID</th>
                                <th>MEDICAMENTO</th>
                                <th>LOTE</th>
                                <th>CADUCIDAD</th>
                                <th>CANTIDAD ENTRADA</th>
                                <th>COSTO</th>
                                <th>IDUSUARIO</th>
                                <th>UBICACIÓN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $resultado->fetch_assoc()): ?>
                                <tr>
                                    <td><?= date('d/m/Y H:i', strtotime($row['entrada_fecha'])); ?></td>
                                    <td><?= $row['item_id']; ?></td>
                                    <td><?= $row['item_name'] . ', ' . $row['item_grams']; ?></td>
                                    <td><?= $row['entrada_lote']; ?></td>
                                    <td><?= date('d/m/Y', strtotime($row['entrada_caducidad'])); ?></td>
                                    <td><?= $row['entrada_unidosis']; ?></td>
                                    <td><?= number_format($row['entrada_costo'], 2); ?></td>
                                    <td><?= $row['id_usua']; ?></td>
                                    <td><?= $row['nombre_ubicacion']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No se encontraron registros con los filtros aplicados.</p>
            <?php endif; ?>

            <!-- Paginación -->
            <div class="pagination">
                <?php
                // Crear parámetros para mantener los filtros
                $params = [];
                if (!empty($inicial)) $params[] = "inicial=" . urlencode($inicial);
                if (!empty($final)) $params[] = "final=" . urlencode($final);
                if (!empty($item_id)) $params[] = "item_id=" . urlencode($item_id);
                if (!empty($lote)) $params[] = "lote=" . urlencode($lote);
                $query_params = !empty($params) ? "&" . implode("&", $params) : "";

                // Mostrar enlaces para la primera, anteriores, página actual, posteriores y última página
                if ($pagina > 1) {
                    echo "<a href='?pagina=1$query_params'>&laquo; Primera</a>";
                }

                // Mostrar páginas cercanas a la actual
                $pagina_inicio = max(1, $pagina - 5);
                $pagina_fin = min($total_paginas, $pagina + 5);

                for ($i = $pagina_inicio; $i < $pagina; $i++) {
                    echo "<a href='?pagina=$i$query_params'>$i</a>";
                }

                // Página actual
                echo "<a href='?pagina=$pagina$query_params' class='current'>$pagina</a>";

                for ($i = $pagina + 1; $i <= $pagina_fin; $i++) {
                    echo "<a href='?pagina=$i$query_params'>$i</a>";
                }

                if ($pagina < $total_paginas) {
                    echo "<a href='?pagina=$total_paginas$query_params'>Última &raquo;</a>";
                }
                ?>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('#mibuscador').select2({
                    placeholder: "Seleccione un medicamento",
                    allowClear: true,
                    width: '100%'
                });
            });
        </script>
</body>

</html>