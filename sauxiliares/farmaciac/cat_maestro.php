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
$items_per_page = 600; // Número de registros por página
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

// Consulta para obtener el total de registros
$total_items_query = "SELECT COUNT(*) as total FROM item_almacen";
$total_items_result = $conexion->query($total_items_query);
$total_items_row = $total_items_result->fetch_assoc();
$total_items = $total_items_row['total'];

// Calcular el número total de páginas
$total_pages = ceil($total_items / $items_per_page);

// Consulta para obtener los datos de la página actual
$paginated_query = "SELECT i.*, t.item_type_desc FROM item_almacen i 
    LEFT JOIN item_type t ON t.item_type_id = i.item_type_id
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
    <style>
        :root {
            --color-primario: #2b2d7f;
            --color-secundario: #1a1c5a;
            --color-fondo: #f8f9ff;
            --color-borde: #e8ebff;
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

        .btn-filtrar {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white !important;
        }

        .btn-borrar {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white !important;
        }

        .btn-especial {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white !important;
        }

        .btn-moderno:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            text-decoration: none;
        }

        /* ===== HEADER SECTION ===== */
        .header-principal {
            text-align: center;
            margin-bottom: 40px;
            padding: 30px 0;
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            border-radius: 20px;
            color: white;
            box-shadow: var(--sombra);
            position: relative;
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

        .btn-ajuste {
            position: absolute;
            top: 50%;
            right: 30px;
            transform: translateY(-50%);
        }

        /* ===== FORMULARIO DE FILTROS ===== */
        .contenedor-filtros {
            background: white;
            border: 2px solid var(--color-borde);
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            box-shadow: var(--sombra);
        }

        .form-control {
            border: 2px solid var(--color-borde);
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--color-primario);
            box-shadow: 0 0 0 3px rgba(43, 45, 127, 0.1);
            outline: none;
        }

        .form-label {
            font-weight: 600;
            color: var(--color-primario);
            margin-bottom: 8px;
        }

        /* ===== TABLA MODERNIZADA ===== */
        /* ===== TABLA MODERNIZADA ===== */
        .tabla-contenedor {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--sombra);
            border: 2px solid var(--color-borde);
            max-height: 80vh;
            overflow-y: auto;
        }

        /* Ajuste de tabla */
        .table-moderna {
            margin: 0;
            font-size: 12px;
            width: 100%;
            table-layout: auto; /* evita que las columnas se expandan de más */
            border-collapse: collapse;
        }

        /* Encabezados */
        .table-moderna thead th {
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            color: white;
            border: none;
            padding: 12px 8px;
            font-weight: 600;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 10;
            font-size: 11px;
            white-space: nowrap;
        }

        /* Filas */
        .table-moderna tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f1f3f4;
        }

        .table-moderna tbody tr:hover {
            background-color: var(--color-fondo);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Celdas */
        .table-moderna tbody td {
            padding: 8px 6px;
            vertical-align: middle;
            border: none;
            text-align: center;
            font-size: 12px;
            white-space: normal;
            word-wrap: break-word;
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
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

        /* ===== PAGINACIÓN MODERNA ===== */
        .contenedor-paginacion {
            display: flex;
            justify-content: center;
            margin: 20px 0 10px 0;
            padding-bottom: 0;
        }

        .paginacion-moderna {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-paginacion {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 45px;
            height: 45px;
            border: 2px solid var(--color-borde);
            background: white;
            color: var(--color-primario);
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 8px 12px;
        }

        .btn-paginacion:hover {
            background: var(--color-primario);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(43, 45, 127, 0.3);
            text-decoration: none;
        }

        .btn-paginacion.active {
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(43, 45, 127, 0.4);
        }

        /* ===== SELECT2 CUSTOM ===== */
        .select2-container--default .select2-selection--single {
            border: 2px solid var(--color-borde) !important;
            border-radius: 10px !important;
            height: 48px !important;
            line-height: 48px !important;
        }

        .select2-container--default .select2-selection--single:focus {
            border-color: var(--color-primario) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 15px !important;
            padding-top: 8px !important;
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
                font-size: 10px;
            }

            .table-moderna thead th,
            .table-moderna tbody td {
                padding: 8px 6px;
            }

            .btn-ajuste {
                position: relative;
                top: auto;
                right: auto;
                transform: none;
                margin-top: 15px;
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

        .contenedor-filtros,
        .tabla-contenedor {
            animation: fadeInUp 0.6s ease-out 0.1s both;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#search").keyup(function() {
                let searchTerm = $(this).val().toLowerCase();
                $("#mytable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(searchTerm) > -1);
                });
            });
        });
    </script>
</head>

<body>
<div class="container-fluid">
    <div class="header-principal">
        <div class="contenido-header">
            <div class="icono-header">
                <i class="fas fa-arrow-up icono-principal"></i>
            </div>
            <h1>PRODUCTOS - FARMACIA CENTRAL</h1>
        </div>
    </div>
            <div class="d-flex justify-content-start mb-4">
                <a href="cat_maestro.php" class="btn-moderno btn-regresar">
                    <i class="fas fa-arrow-left"></i> Regresar
                </a>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="search" placeholder="Buscar..." style="width: 300px;">
            </div>
            <div class="form-group mb-3">
                <a href="excel_item_almacen.php" class="btn btn-regresar">
                    <img src="https://img.icons8.com/color/48/000000/ms-excel.png" width="30" /> Exportar a Excel
                </a>
                <button type="button" class="btn btn-especial" data-toggle="modal" data-target="#exampleModal">
                    <i class="fa fa-plus"></i> Nuevo Producto
                </button>
            </div>
    <div class="tabla-contenedor">
        <div class="table-responsive">
            <table class="table table-moderna">
                <thead>
                <tr>
                    <th><i class="fa fa-edit"></i> Editar</th>
                    <th><i class="fa fa-hashtag"></i> Id</th>
                    <th><i class="fa fa-barcode"></i> Código</th>
                    <th><i class="fa fa-file-text"></i> Descripción</th>
                    <th><i class="fa fa-cubes"></i> Presentación</th>
                    <th><i class="fa fa-box"></i> Contenido</th>
                    <th><i class="fa fa-truck"></i> Proveedor</th>
                    <th><i class="fa fa-balance-scale"></i> Factor</th>
                    <th><i class="fa fa-user-md"></i> Surte</th>
                    <th><i class="fa fa-arrow-up"></i> Máximo</th>
                    <th><i class="fa fa-exclamation-circle"></i> Reorden</th>
                    <th><i class="fa fa-arrow-down"></i> Mínimo</th>
                    <th><i class="fa fa-dollar-sign"></i> Costo</th>
                    <th><i class="fa fa-money-bill"></i> Costo <br>Unitario</th>
                    <th><i class="fa fa-tags"></i> Precio</th>
                    <th><i class="fa fa-sitemap"></i> Subfamilia</th>
                    <th><i class="fa fa-layer-group"></i> Grupo</th>
                    <th><i class="fa fa-list"></i> Tipo</th>
                    <th><i class="fa fa-thermometer-half"></i> Temperatura</th>
                    <th><i class="fa fa-bell"></i> Alerta</th>
                    <th><i class="fa fa-check-circle"></i> Activo</th>
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
                            . '<td>' . $row['item_grams'] . '</td>'
                            . '<td>' . $row['contenido'] . '</td>'
                            . '<td>' . $row['id_prov'] . '</td>'
                            . '<td>' . $row['factor'] . '</td>'
                            . '<td>' . $row['item_type_desc'] . '</td>'
                            . '<td>' . $row['item_max'] . '</td>'
                            . '<td>' . $row['reorden'] . '</td>'
                            . '<td>' . $row['item_min'] . '</td>'
                            . '<td>$' . number_format($row['item_costs'], 2) . '</td>'
                            . '<td>$' . number_format($row['cost_unit'], 2) . '</td>'
                            . '<td>$' . number_format($row['item_price'], 2) . '</td>'
                            . '<td>' . $row['subfamilia'] . '</td>'
                            . '<td>' . $row['grupo'] . '</td>'
                            . '<td>' . $row['tipo'] . '</td>'
                            . '<td>' . $row['temperatura'] . '</td>'
                            . '<td>' . $row['alerta'] . '</td>'
                            . '<td>' . $row['activo'] . '</td>'
                            . '</tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Paginación -->
    <div class="contenedor-paginacion">
        <div class="paginacion-moderna">
            <?php if ($current_page > 1): ?>
                <a class="btn-paginacion" href="?page=<?= $current_page - 1 ?>">
                    <i class="fa fa-chevron-left"></i> Anterior
                </a>
            <?php endif; ?>

            <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                <a class="btn-paginacion <?= $i == $current_page ? 'active' : '' ?>" href="?page=<?= $i ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <a class="btn-paginacion" href="?page=<?= $current_page + 1 ?>">
                    Siguiente <i class="fa fa-chevron-right"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>



    <!-- Modal Insertar -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Nuevo Producto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="insertar_item.php" method="POST" enctype="multipart/form-data">

                            <div class="form-group">
                                <label for="item-code">Código:</label>
                                <input type="text" name="item_code" class="form-control" id="item-code" placeholder="Código del producto" required>
                            </div>
                            <div class="form-group">
                                <label for="item-name">Descripción:</label>
                                <input type="text" name="item_name" class="form-control" id="item-name" placeholder="Descripción del producto" required>
                            </div>
                            <div class="form-group">
                                <label for="item-comercial">Nombre Comercial:</label>
                                <input type="text" name="item_comercial" class="form-control" id="item-comercial" placeholder="Nombre comercial" required>
                            </div>
                            <div class="form-group">
                                <label for="item-grams">Presentación (g):</label>
                                <input type="text" name="item_grams" class="form-control" id="item-grams" placeholder="Presentación en gramos" required>
                            </div>
                            <div class="form-group">
                                <label for="id_prov">Proveedor:</label>
                                <select name="id_prov" class="form-control" id="id-prov" required>
                                    <option value="">Selecciona un proveedor</option>
                                    <?php
                                    // Consulta para obtener los proveedores
                                    $resultado_proveedores = $conexion->query("SELECT id_prov, nom_prov FROM proveedores") or die($conexion->error);
                                    while ($row_prov = $resultado_proveedores->fetch_assoc()) {
                                        echo '<option value="' . $row_prov['id_prov'] . '">' . htmlspecialchars($row_prov['nom_prov']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="factor">Factor:</label>
                                <input type="text" name="factor" class="form-control" id="factor" placeholder="Factor" required>
                            </div>
                            <div class="f orm-group">
                                <label for="item-type">Surte:</label>
                                <select name="item_type_id" class="form-control" id="item-type" required>
                                    <option value="">Selecciona un tipo</option>
                                    <?php
                                    // Consulta para obtener los tipos
                                    $tipo_resultado = $conexion->query("SELECT item_type_id, item_type_desc FROM item_type") or die($conexion->error);
                                    while ($tipo = $tipo_resultado->fetch_assoc()) {
                                        echo '<option value="' . $tipo['item_type_id'] . '">' . $tipo['item_type_desc'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="item-max">Máximo:</label>
                                <input type="number" name="item_max" class="form-control" id="item-max" placeholder="Máximo" required>
                            </div>
                            <div class="form-group">
                                <label for="reorden">Reorden:</label>
                                <input type="number" name="reorden" class="form-control" id="reorden" placeholder="Reorden" required>
                            </div>
                            <div class="form-group">
                                <label for="item-min">Mínimo:</label>
                                <input type="number" name="item_min" class="form-control" id="item-min" placeholder="Mínimo" required>
                            </div>
                            <div class="form-group">
                                <label for="item-price">Costo:</label>
                                <input type="number" step="0.01" name="item_costs" class="form-control" id="item-cost" placeholder="Costo del producto" required>
                            </div>
                            <div class="form-group">
                                <label for="item-price">Precio:</label>
                                <input type="number" step="0.01" name="item_price" class="form-control" id="item-price" placeholder="Precio del producto" required>
                            </div>
                            <div class="form-group">
                                <label for="subfamilia">Subfamilia:</label>
                                <input type="text" name="subfamilia" class="form-control" id="subfamilia" placeholder="Subfamilia" required>
                            </div>
                            <div class="form-group">
                                <label for="grupo">Grupo:</label>
                                <select name="grupo" class="form-control" id="grupo" required>
                                    <option value="">Selecciona un tipo</option>
                                    <option value="MATERIALES">MATERIALES</option>
                                    <option value="MEDICAMENTOS">MEDICAMENTOS</option>
                                    <option value="INSUMOS">INSUMOS</option>
                                    <option value="SOLUCIONES FISIOLÓGICAS">SOLUCIONES FISIOLÓGICAS</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tipo">Tipo:</label>
                                <select name="tipo" class="form-control" id="tipo" required>
                                    <option value="">Selecciona un tipo</option>
                                    <option value="GENÉRICO">GENÉRICO</option>
                                    <option value="PATENTE">PATENTE</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="activo">Activo:</label>
                                <select name="activo" class="form-control" id="activo" required>
                                    <option value="SI">Sí</option>
                                    <option value="NO">No</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Guardar Producto</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- FastClick -->
    <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
</body>

</html>
