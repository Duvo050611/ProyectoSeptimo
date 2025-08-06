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

// Inicializar variables de filtro
$inicial = isset($_POST['inicial']) ? $_POST['inicial'] : (isset($_GET['inicial']) ? $_GET['inicial'] : '');
$final = isset($_POST['final']) ? $_POST['final'] : (isset($_GET['final']) ? $_GET['final'] : '');
$item_id = isset($_POST['item_id']) ? $_POST['item_id'] : (isset($_GET['item_id']) ? $_GET['item_id'] : '');
$lote = isset($_POST['lote']) ? $_POST['lote'] : (isset($_GET['lote']) ? $_GET['lote'] : '');
$ubicacion = isset($_POST['ubicacion']) ? $_POST['ubicacion'] : (isset($_GET['ubicacion']) ? $_GET['ubicacion'] : '');

// Verificar filtros
$where = "WHERE 1=1";

if (!empty($inicial)) {
    $inicial_sql = mysqli_real_escape_string($conexion, $inicial);
    $where .= " AND s.salida_fecha >= '$inicial_sql'";
}

if (!empty($final)) {
    $final_sql = mysqli_real_escape_string($conexion, $final);
    $final_sql = date("Y-m-d H:i:s", strtotime($final_sql . " + 1 day"));
    $where .= " AND s.salida_fecha <= '$final_sql'";
}

if (!empty($item_id)) {
    $item_id_sql = intval($item_id);
    $where .= " AND s.item_id = $item_id_sql";
}

if (!empty($lote)) {
    $lote_sql = mysqli_real_escape_string($conexion, $lote);
    $where .= " AND s.salida_lote LIKE '%$lote_sql%'";
}

if (!empty($ubicacion)) {
    $ubicacion_sql = mysqli_real_escape_string($conexion, $ubicacion);
    $where .= " AND s.ubicacion_id = '$ubicacion_sql'";
}

// Paginación
$registros_por_pagina = 20;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) {
    $pagina = 1;
}

// Calcular el número total de registros
$query_total = "
    SELECT COUNT(*) AS total 
    FROM salidas_almacen s
    JOIN item_almacen i ON s.item_id = i.item_id
    $where
";
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
        s.salida_id,
        s.salida_fecha,
        i.item_id,
        i.item_name,
        i.item_grams,
        s.salida_lote,
        s.salida_caducidad,
        s.salida_qty,
        s.id_usua
        FROM 
        salidas_almacen s
    JOIN 
        item_almacen i ON s.item_id = i.item_id
    $where
    ORDER BY 
        s.salida_fecha DESC
    LIMIT $registros_por_pagina OFFSET $offset
";

$resultado = $conexion->query($query) or die($conexion->error);
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
        <a href="kardex.php" class="btn btn-danger" style="margin-left: 10px; margin-bottom: 10px;">Regresar</a>

        <div class="container box">
            <div class="content">
                <div class="thead" style="background-color: #2b2d7f; margin: 5px auto; padding: 5px; color: white; width: fit-content; text-align: center; border-radius: 5px;">
                    <h1 style="font-size: 26px; margin: 2;">SALIDAS</h1>
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
                            <a href="salidas_almacen_historial.php" class="btn btn-danger w-100">Borrar Filtros</a>
                        </div>
                    </div>

                </form>



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
                                    <th>CANTIDAD SALIDA</th>
                                    <th>IDUSUARIO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $resultado->fetch_assoc()): ?>
                                    <tr>
                                        <td class="disabled-field"><?= date('d/m/Y H:i', strtotime($row['salida_fecha'])); ?></td>
                                        <td class="disabled-field"><?= $row['item_id']; ?></td>
                                        <td class="disabled-field"><?= $row['item_name'] . ', ' . $row['item_grams']; ?></td>
                                        <td class="disabled-field"><?= $row['salida_lote']; ?></td>
                                        <td class="disabled-field"><?= date('d/m/Y', strtotime($row['salida_caducidad'])); ?></td>
                                        <td class="disabled-field"><?= $row['salida_qty']; ?></td>
                                        <td class="disabled-field"><?= $row['id_usua']; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p>No se encontraron registros.</p>
                <?php endif; ?>

                <!-- Paginación -->
                <div class="pagination">
                    <?php
                    // Construir parámetros de consulta
                    $query_params = [];
                    if (!empty($inicial)) $query_params[] = "inicial=" . urlencode($inicial);
                    if (!empty($final)) $query_params[] = "final=" . urlencode($final);
                    if (!empty($item_id)) $query_params[] = "item_id=" . urlencode($item_id);
                    if (!empty($lote)) $query_params[] = "lote=" . urlencode($lote);
                    if (!empty($ubicacion)) $query_params[] = "ubicacion=" . urlencode($ubicacion);
                    $query_string = !empty($query_params) ? "&" . implode("&", $query_params) : "";

                    // Mostrar enlaces para la primera página
                    if ($pagina > 1) {
                        echo "<a href='?pagina=1$query_string'>&laquo; Primera</a>";
                    }

                    // Mostrar páginas cercanas a la actual
                    $pagina_inicio = max(1, $pagina - 5);
                    $pagina_fin = min($total_paginas, $pagina + 5);

                    for ($i = $pagina_inicio; $i < $pagina; $i++) {
                        echo "<a href='?pagina=$i$query_string'>$i</a>";
                    }

                    // Página actual
                    echo "<a href='?pagina=$pagina$query_string' class='current'>$pagina</a>";

                    for ($i = $pagina + 1; $i <= $pagina_fin; $i++) {
                        echo "<a href='?pagina=$i$query_string'>$i</a>";
                    }

                    // Mostrar enlace para la última página
                    if ($pagina < $total_paginas) {
                        echo "<a href='?pagina=$total_paginas$query_string'>Última &raquo;</a>";
                    }
                    ?>
                </div>
            </div>
        </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#mibuscador').select2({
            placeholder: "Seleccione un medicamento",
            allowClear: true,
            width: '100%'
        });
    });
</script>

</html>