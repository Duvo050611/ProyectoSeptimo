<?php
session_start();
include "../../conexionbd.php";
if (!isset($_SESSION['login'])) {
    echo "<script>
        window.location = '../../index.php';
    </script>";
    exit;
}
$id_usua = $_SESSION['login']['id_usua']; // Obtiene el ID del usuario en sesión
include "../header_farmaciah.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devoluciones Pendientes</title>
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
            --color-exito: #28a745;
            --color-peligro: #dc3545;
            --color-advertencia: #fff8e1;
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

        .btn-imprimir {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white !important;
        }

        .btn-excel {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white !important;
        }

        .btn-moderno:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            text-decoration: none;
        }

        .btn-sm-moderno {
            padding: 8px 16px;
            font-size: 12px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-inventario {
            background: linear-gradient(135deg, var(--color-exito) 0%, #20c997 100%);
            color: white;
            border: none;
        }

        .btn-merma {
            background: linear-gradient(135deg, var(--color-peligro) 0%, #c82333 100%);
            color: white;
            border: none;
        }

        .btn-sm-moderno:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
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

        /* ===== BARRA DE ACCIONES ===== */
        .barra-acciones {
            background: white;
            border: 2px solid var(--color-borde);
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: var(--sombra);
        }

        /* ===== BARRA DE BÚSQUEDA ===== */
        .contenedor-busqueda {
            background: white;
            border: 2px solid var(--color-borde);
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: var(--sombra);
        }

        .form-control {
            border: 2px solid var(--color-borde);
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .form-control:focus {
            border-color: var(--color-primario);
            box-shadow: 0 0 0 3px rgba(43, 45, 127, 0.1);
            outline: none;
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
            font-size: 12px;
            width: 100%;
            border-collapse: collapse;
        }

        /* Encabezados */
        .table-moderna thead th {
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            color: white;
            border: none;
            padding: 15px 8px;
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
            padding: 12px 6px;
            vertical-align: middle;
            border: none;
            text-align: center;
            font-size: 12px;
            word-wrap: break-word;
            max-width: 120px;
        }

        /* ===== BADGES Y ETIQUETAS ===== */
        .badge-custom {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
        }

        .badge-paciente {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }

        .badge-lote {
            background: linear-gradient(135deg, #6f42c1 0%, #5a2c91 100%);
            color: white;
        }

        /* ===== MODALES MODERNOS ===== */
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: var(--sombra);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            border-bottom: none;
        }

        .modal-header .modal-title {
            font-weight: 700;
        }

        .modal-header .close {
            color: white;
            opacity: 1;
        }

        .modal-header .close:hover {
            color: #f8f9fa;
        }

        .modal-body {
            padding: 30px;
        }

        .modal-footer {
            border-top: 1px solid var(--color-borde);
            padding: 20px 30px;
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
                margin-bottom: 10px;
            }

            .table-moderna {
                font-size: 10px;
            }

            .table-moderna thead th,
            .table-moderna tbody td {
                padding: 8px 4px;
            }

            .barra-acciones .row > div {
                margin-bottom: 10px;
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

        .barra-acciones,
        .contenedor-busqueda,
        .tabla-contenedor {
            animation: fadeInUp 0.6s ease-out 0.1s both;
        }

        /* ===== COLORES DE ESTADO ===== */
        .estado-pendiente {
            border-left: 4px solid var(--color-advertencia);
            background: whitesmoke;
        }

        .cantidad-alta {
            color: var(--color-exito);
            font-weight: 700;
        }

        .cantidad-baja {
            color: var(--color-peligro);
            font-weight: 700;
        }
    </style>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("table tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
</head>
<body>
<div class="container-fluid">
    <div class="container-moderno">
        <!-- Header principal -->
        <div class="header-principal">
            <i class="fas fa-undo-alt icono-principal"></i>
            <h1>DEVOLUCIONES PENDIENTES</h1>
        </div>

        <!-- Barra de acciones -->
        <div class="barra-acciones">
            <div class="row">
                <div class="col-md-4">
                    <a href="../../template/menu_farmaciahosp.php" class="btn-moderno btn-regresar">
                        <i class="fas fa-arrow-left"></i>
                        Regresar
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="pdf_devoluciones.php" class="btn-moderno btn-imprimir" target="_blank">
                        <i class="fas fa-file-pdf"></i>
                        Imprimir Reporte
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="exceldevoluciones.php" class="btn-moderno btn-excel">
                        <i class="fas fa-file-excel"></i>
                        Exportar a Excel
                    </a>
                </div>
            </div>
        </div>

        <!-- Barra de búsqueda -->
        <div class="contenedor-busqueda">
            <div class="input-group">
                <div class="input-group-prepend">
                        <span class="input-group-text" style="background: var(--color-primario); color: white; border: none;">
                            <i class="fas fa-search"></i>
                        </span>
                </div>
                <input type="text" class="form-control" id="search" placeholder="Buscar por paciente, código, lote, motivo...">
            </div>
        </div>

        <!-- Tabla de datos -->
        <div class="tabla-contenedor">
            <table class="table table-moderna">
                <thead>
                <tr>
                    <th><i class="fas fa-user"></i> ID Paciente</th>
                    <th><i class="fas fa-sign-out-alt"></i> Salida</th>
                    <th><i class="fas fa-pills"></i> Nombre</th>
                    <th><i class="fas fa-hashtag"></i> ID Dev.</th>
                    <th><i class="fas fa-calendar"></i> Fecha</th>
                    <th><i class="fas fa-cube"></i> ID Ítem</th>
                    <th><i class="fas fa-barcode"></i> Código</th>
                    <th><i class="fas fa-undo"></i> Cant. Dev.</th>
                    <th><i class="fas fa-warehouse"></i> Cant. Inv.</th>
                    <th><i class="fas fa-trash-alt"></i> Cant. Merma</th>
                    <th><i class="fas fa-clipboard"></i> Motivo Inv.</th>
                    <th><i class="fas fa-exclamation-triangle"></i> Motivo Merma</th>
                    <th><i class="fas fa-tag"></i> Lote</th>
                    <th><i class="fas fa-calendar-times"></i> Caducidad</th>
                    <th><i class="fas fa-cogs"></i> Acciones</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $resultado = $conexion->query("SELECT d.*, i.* FROM devoluciones_almacenh d JOIN item_almacen i ON d.item_id = i.item_id");

                if ($resultado && $resultado->num_rows > 0) {
                    $hayResultados = false;
                    while ($row = $resultado->fetch_assoc()) {
                        // Validación: si dev_qty es igual a cero, omitir esta fila
                        if ($row['dev_qty'] == 0) {
                            continue; // Salta a la siguiente iteración
                        }

                        $hayResultados = true;
                        $fecha = date_create($row['fecha']);
                        $fecha = date_format($fecha, 'd/m/Y H:i');

                        // Determinar color de cantidad según el valor
                        $claseCantidad = $row['dev_qty'] > 5 ? 'cantidad-alta' : 'cantidad-baja';

                        echo "<tr class='estado-pendiente'>";
                        echo "<td><span class='badge badge-custom badge-paciente'>" . $row['id_atencion'] . "</span></td>";
                        echo "<td><strong>" . $row['salida_id'] . "</strong></td>";
                        echo "<td><strong>" . $row['item_name'] . "</strong></td>";
                        echo "<td>" . $row['dev_id'] . "</td>";
                        echo "<td><small>" . $fecha . "</small></td>";
                        echo "<td>" . $row['item_id'] . "</td>";
                        echo "<td><code>" . $row['item_code'] . "</code></td>";
                        echo "<td><span class='$claseCantidad'><strong>" . $row['dev_qty'] . "</strong></span></td>";
                        echo "<td>" . ($row['cant_inv'] ?: '-') . "</td>";
                        echo "<td>" . ($row['cant_mer'] ?: '-') . "</td>";
                        echo "<td>" . ($row['motivoi'] ?: '-') . "</td>";
                        echo "<td>" . ($row['motivom'] ?: '-') . "</td>";
                        echo "<td><span class='badge badge-custom badge-lote'>" . $row['existe_lote'] . "</span></td>";
                        echo "<td><small>" . $row['existe_caducidad'] . "</small></td>";
                        echo "<td>";
                        echo "<button class='btn btn-sm-moderno btn-inventario' data-toggle='modal' data-target='#inventarioModal" . $row['dev_id'] . "'>";
                        echo "<i class='fas fa-check'></i> Inventario</button><br><br>";
                        echo "<button class='btn btn-sm-moderno btn-merma' data-toggle='modal' data-target='#mermaModal" . $row['dev_id'] . "'>";
                        echo "<i class='fas fa-times'></i> Merma</button>";
                        echo "</td>";
                        echo "</tr>";

                        // Modales para inventario y merma
                        echo "<div class='modal fade' id='inventarioModal" . $row['dev_id'] . "' tabindex='-1' aria-labelledby='inventarioLabel' aria-hidden='true'>
                                    <div class='modal-dialog modal-dialog-centered'>
                                        <form action='valida_dev.php' method='POST'>
                                            <div class='modal-content'>
                                                <div class='modal-header'>
                                                    <h5 class='modal-title'>
                                                        <i class='fas fa-check-circle'></i>
                                                        Confirmar para Inventario
                                                    </h5>
                                                    <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                                </div>
                                                <div class='modal-body'>
                                                    <input type='hidden' name='id_dev' value='" . $row['dev_id'] . "'>
                                                    <input type='hidden' name='item_id' value='" . $row['item_id'] . "'>
                                                    <input type='hidden' name='dev_qty' value='" . $row['dev_qty'] . "'>
                                                    <input type='hidden' name='existe_lote' value='" . $row['existe_lote'] . "'>
                                                    <input type='hidden' name='existe_caducidad' value='" . $row['existe_caducidad'] . "'>
                                                    <input type='hidden' name='id_usua' value='" . $row['id_usua'] . "'>
                                                    
                                                    <div class='form-group'>
                                                        <label class='font-weight-bold'>Cantidad a inventariar:</label>
                                                        <input type='number' name='cant_inv' class='form-control' max='" . $row['dev_qty'] . "' required>
                                                        <small class='text-muted'>Máximo disponible: " . $row['dev_qty'] . "</small>
                                                    </div>
                                                    
                                                    <div class='form-group'>
                                                        <label class='font-weight-bold'>Motivo:</label>
                                                        <input type='text' name='motivoi' class='form-control' placeholder='Describa el motivo...' required>
                                                    </div>
                                                </div>
                                                <div class='modal-footer'>
                                                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>
                                                        <i class='fas fa-times'></i> Cancelar
                                                    </button>
                                                    <button type='submit' class='btn btn-success'>
                                                        <i class='fas fa-check'></i> Confirmar
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>";

                        echo "<div class='modal fade' id='mermaModal" . $row['dev_id'] . "' tabindex='-1' aria-labelledby='mermaLabel' aria-hidden='true'>
                                    <div class='modal-dialog modal-dialog-centered'>
                                        <form action='registrar_merma.php' method='POST'>
                                            <div class='modal-content'>
                                                <div class='modal-header bg-danger'>
                                                    <h5 class='modal-title'>
                                                        <i class='fas fa-exclamation-triangle'></i>
                                                        Confirmar para Merma
                                                    </h5>
                                                    <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                                </div>
                                                <div class='modal-body'>
                                                    <input type='hidden' name='id_dev' value='" . $row['dev_id'] . "'>
                                                    <input type='hidden' name='item_id' value='" . $row['item_id'] . "'>
                                                    <input type='hidden' name='dev_qty' value='" . $row['dev_qty'] . "'>
                                                    <input type='hidden' name='existe_lote' value='" . $row['existe_lote'] . "'>
                                                    <input type='hidden' name='existe_caducidad' value='" . $row['existe_caducidad'] . "'>
                                                    <input type='hidden' name='id_usua' value='" . $row['id_usua'] . "'>
                                                    
                                                    <div class='form-group'>
                                                        <label class='font-weight-bold'>Cantidad para merma:</label>
                                                        <input type='number' name='merma_qty' class='form-control' max='" . $row['dev_qty'] . "' required>
                                                        <small class='text-muted'>Máximo disponible: " . $row['dev_qty'] . "</small>
                                                    </div>
                                                    
                                                    <div class='form-group'>
                                                        <label class='font-weight-bold'>Motivo de la merma:</label>
                                                        <input type='text' name='merma_motivo' class='form-control' placeholder='Describa el motivo de la merma...' required>
                                                    </div>
                                                </div>
                                                <div class='modal-footer'>
                                                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>
                                                        <i class='fas fa-times'></i> Cancelar
                                                    </button>
                                                    <button type='submit' class='btn btn-danger'>
                                                        <i class='fas fa-trash'></i> Confirmar Merma
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>";
                    }

                    if (!$hayResultados) {
                        echo "<tr><td colspan='15' class='mensaje-sin-resultados'>";
                        echo "<i class='fas fa-check-circle'></i><br>";
                        echo "No hay devoluciones pendientes en este momento.";
                        echo "</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='15' class='mensaje-sin-resultados'>";
                    echo "<i class='fas fa-database'></i><br>";
                    echo "No hay datos en la tabla 'devoluciones_almacenh'";
                    echo "</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>