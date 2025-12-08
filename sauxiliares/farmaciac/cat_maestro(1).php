<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "../../conexionbd.php";
include "../header_farmaciac.php";

if (!$conexion) {
    die("Connection failed: " . mysqli_connect_error());
}

$usuario = $_SESSION['login'];

if (!in_array($usuario['id_rol'], [4, 5, 11])) {
    echo "<script>window.Location='../../index.php';</script>";
    exit();
}

// Variables para la paginación
$items_per_page = 500; // Número de registros por página
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

// Obtener el término de búsqueda (si existe)
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Consulta para obtener el total de registros con filtro de búsqueda (si aplica)
$total_items_query = "SELECT COUNT(*) as total FROM item_almacen WHERE
    item_code LIKE '%$search%' OR
    item_name LIKE '%$search%'";
$total_items_result = $conexion->query($total_items_query);
$total_items_row = $total_items_result->fetch_assoc();
$total_items = $total_items_row['total'];

// Calcular el número total de páginas
$total_pages = ceil($total_items / $items_per_page);

// Consulta para obtener los datos de la página actual con filtro de búsqueda
$paginated_query = "SELECT i.*, t.item_type_desc FROM item_almacen i
    LEFT JOIN item_type t ON t.item_type_id = i.item_type_id
    WHERE item_code LIKE '%$search%' OR item_name LIKE '%$search%'
    LIMIT $items_per_page OFFSET $offset";
$resultado2 = $conexion->query($paginated_query);

// Lógica para mostrar solo 5 páginas en la navegación
$max_links = 5; // Número máximo de enlaces de paginación visibles
$start_page = max(1, $current_page - floor($max_links / 2));
$end_page = min($total_pages, $start_page + $max_links - 1);
$start_page = max(1, $end_page - $max_links + 1); // Ajustar si llegamos al límite superior
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMA Venecia Metepec</title>
    <link rel="icon" href="../imagenes/SIF.PNG">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .header {
            background-color: #0c675e;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 24px;
        }

        .btn-danger {
            margin: 10px 0;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .table thead th {
            background-color: #0c675e;
            color: white;
        }

        .table tbody tr {
            background-color: #ffffff;
        }

        .table tbody tr:hover {
            background-color: #e9ecef;
        }

        .modal-header {
            background-color: #0c675e;
            color: white;
        }

        .modal-footer {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="header">PRODUCTOS DE FARMACIA CENTRAL</div>
        <div class="responsive">
            <div class="row mt-3 mb-3">
                <div class="col-sm-4">
                    <a class="btn btn-danger" href="../../template/menu_farmaciacentral.php">Regresar</a>
                </div>
            </div>
            <div class="form-group">
                <!-- Añadido: Formulario para búsqueda global -->
                <form method="GET" action="">
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" name="search" placeholder="Buscar por código o descripción" value="<?= htmlspecialchars($search) ?>">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Buscar</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="form-group mb-3">
                <a href="excel_item_almacen.php" class="btn btn-warning btn-lg btn-custom-size">
                    <img src="https://img.icons8.com/color/48/000000/ms-excel.png" width="30" /> Exportar a Excel
                </a>
                <button type="button" class="btn btn-primary btn-lg btn-custom-size" data-toggle="modal" data-target="#exampleModal">
                    <i class="fa fa-plus"></i> Nuevo Producto
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="mytable">
                    <thead>
                        <tr>
                            <th>Editar</th>
                            <th>Id</th>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Nombre Comercial</th>
                            <th>Presentación (g)</th>
                            <th>Proveedor</th>
                            <th>Factor</th>
                            <th>Surte</th>
                            <th>Máximo</th>
                            <th>Reorden</th>
                            <th>Mínimo</th>
                            <th>Costo</th>
                            <th>Precio</th>
                            <th>Subfamilia</th>
                            <th>Grupo</th>
                            <th>Tipo</th>
                            <th>Activo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = $resultado2->fetch_assoc()) {
                            $eid = $row['item_id'];
                            echo '<tr>'
                                . '<td><a href="edit_items.php?id=' . $eid . '" class="btn btn-warning btn-sm"><span class="fa fa-edit"></span></a></td>'
                                . '<td>' . $row['item_id'] . '</td>'
                                . '<td>' . $row['item_code'] . '</td>'
                                . '<td>' . $row['item_name'] . '</td>'
                                . '<td>' . $row['item_comercial'] . '</td>'
                                . '<td>' . $row['item_grams'] . '</td>'
                                . '<td>' . $row['id_prov'] . '</td>'
                                . '<td>' . $row['factor'] . '</td>'
                                . '<td>' . $row['item_type_desc'] . '</td>'
                                . '<td>' . $row['item_max'] . '</td>'
                                . '<td>' . $row['reorden'] . '</td>'
                                . '<td>' . $row['item_min'] . '</td>'
                                . '<td>$' . number_format($row['item_costs'], 2) . '</td>'
                                . '<td>$' . number_format($row['item_price'], 2) . '</td>'
                                . '<td>' . $row['subfamilia'] . '</td>'
                                . '<td>' . $row['grupo'] . '</td>'
                                . '<td>' . $row['tipo'] . '</td>'
                                . '<td>' . ($row['activo'] == 'SI' ? '<span class="text-success">SI</span>' : '<span class="text-danger">No</span>') . '</td>'
                                . '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- Paginación -->
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($current_page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $current_page - 1 ?>&search=<?= urlencode($search) ?>">Anterior</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                        <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($current_page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $current_page + 1 ?>&search=<?= urlencode($search) ?>">Siguiente</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</body>

</html>
