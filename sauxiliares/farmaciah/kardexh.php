<?php
session_start();
include "../../conexionbd.php";

// Consulta para obtener los medicamentos desde la tabla `item_almacen`
$resultado = $conexion->query("SELECT * FROM item_almacen") or die($conexion->error);

$usuario = $_SESSION['login'];

// Incluye el encabezado correspondiente según el rol del usuario
if ($usuario['id_rol'] == 7) {
    include "../header_farmaciah.php";
} else if ($usuario['id_rol'] == 3) {
    include "../../enfermera/header_enfermera.php";
} else if ($usuario['id_rol'] == 4 || $usuario['id_rol'] == 5) {
    include "../header_farmaciah.php";
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
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kardex - Farmacia Hospitalaria</title>
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

        .btn-accion {
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            color: white !important;
            margin: 5px;
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

        /* ===== BOTONES DE ACCIONES ===== */
        .contenedor-acciones {
            text-align: center;
            margin: 25px 0;
            padding: 20px;
            background: var(--color-fondo);
            border-radius: 15px;
            border: 2px solid var(--color-borde);
        }

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

        .table-moderna {
            margin: 0;
            font-size: 12px;
            min-width: 100%;
        }

        .table-moderna thead th {
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            color: white;
            border: none;
            padding: 15px 10px;
            font-weight: 600;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 10;
            font-size: 11px;
        }

        .table-moderna thead th i {
            margin-right: 5px;
        }

        .table-moderna tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f1f3f4;
        }

        .table-moderna tbody tr:hover {
            background-color: var(--color-fondo);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .table-moderna tbody td {
            padding: 10px 8px;
            vertical-align: middle;
            border: none;
            text-align: center;
            white-space: nowrap;
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
        .contenedor-acciones,
        .tabla-contenedor {
            animation: fadeInUp 0.6s ease-out 0.1s both;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="container-moderno">
            
            <!-- Botón de regreso modernizado -->
            <div class="d-flex justify-content-start mb-4">
                <a href="../../template/menu_farmaciahosp.php" class="btn-moderno btn-regresar">
                    <i class="fas fa-arrow-left"></i> Regresar
                </a>
            </div>

            <!-- Header principal modernizado -->
            <div class="header-principal">
                <div class="contenido-header">
                    <div class="icono-header">
                        <i class="fas fa-clipboard-list icono-principal"></i>
                    </div>
                    <h1>KARDEX - FARMACIA HOSPITALARIA</h1>
                </div>
            </div>

            <!-- Formulario de filtros modernizado -->
            <div class="contenedor-filtros">
                <form method="POST" action="">
                    <div class="form-row align-items-end">
                        <!-- Input Fecha Inicial -->
                        <div class="form-group col-md-2">
                            <label class="form-label">
                                <i class="fas fa-calendar-alt"></i> Fecha Inicial:
                            </label>
                            <input type="date" class="form-control" name="inicial" value="<?= $fecha_inicial ?>">
                        </div>

                        <!-- Input Fecha Final -->
                        <div class="form-group col-md-2">
                            <label class="form-label">
                                <i class="fas fa-calendar-check"></i> Fecha Final:
                            </label>
                            <input type="date" class="form-control" name="final" value="<?= $fecha_final ?>">
                        </div>

                        <!-- Input Medicamento / Insumo -->
                        <div class="form-group col-md-3">
                            <label class="form-label">
                                <i class="fas fa-pills"></i> Medicamento / Insumo:
                            </label>
                            <select name="item_id" class="form-control" id="mibuscador">
                                <option value="">Seleccione un medicamento</option>
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
                            <label class="form-label">
                                <i class="fas fa-barcode"></i> Lote:
                            </label>
                            <input type="text" class="form-control" name="lote" placeholder="Ej. ABC123" value="<?= $lote ?>">
                        </div>

                        <!-- Contenedor de Botones -->
                        <div class="form-group col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn-moderno btn-filtrar w-100 mr-2">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                            <a href="kardexh.php" class="btn-moderno btn-borrar w-100">
                                <i class="fas fa-eraser"></i> Borrar Filtros
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Botones de acciones modernizados -->
            <div class="contenedor-acciones">
                <a href="entradas_almacenh_historial.php" class="btn-moderno btn-accion">
                    <i class="fas fa-arrow-down"></i> RESURTIMIENTO
                </a>
                <a href="salidas_almacenh_historial.php" class="btn-moderno btn-accion">
                    <i class="fas fa-arrow-up"></i> SALIDAS
                </a>
                <a href="devoluciones_almacenh_historial.php" class="btn-moderno btn-accion">
                    <i class="fas fa-undo"></i> DEVOLUCIONES
                </a>
                <a href="mermas_almacenh_historial.php" class="btn-moderno btn-accion">
                    <i class="fas fa-exclamation-triangle"></i> MERMAS
                </a>
            </div>

            <!-- Tabla modernizada -->
            <div class="tabla-contenedor">
                <div class="table-responsive">
                    <table class="table table-moderna">
                        <thead>
                            <tr>
                                <th><i class="fas fa-calendar-alt"></i> FECHA</th>
                                <th><i class="fas fa-hashtag"></i> ITEMID</th>
                                <th><i class="fas fa-pills"></i> MEDICAMENTO</th>
                                <th><i class="fas fa-barcode"></i> LOTE</th>
                                <th><i class="fas fa-clock"></i> CADUCIDAD</th>
                                <th><i class="fas fa-arrow-down"></i> ENTRADA</th>
                                <th><i class="fas fa-arrow-up"></i> SALIDA</th>
                                <th><i class="fas fa-exchange-alt"></i> MOVIMIENTO</th>
                                <th><i class="fas fa-map-marker-alt"></i> UBICACIÓN</th>
                                <th><i class="fas fa-location-arrow"></i> DESTINO</th>
                                <th><i class="fas fa-comment"></i> MOTIVO</th>
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
                    </table>
                </div>
            </div>

            <!-- Paginación modernizada -->
            <div class="contenedor-paginacion">
                <div class="paginacion-moderna">
                    <?php
                    // Establecer el rango de páginas a mostrar
                    $rango = 3;

                    // Determinar el inicio y fin del rango de páginas a mostrar
                    $inicio = max(1, $pagina - $rango);
                    $fin = min($total_paginas, $pagina + $rango);

                    // Mostrar el enlace a la página anterior
                    if ($pagina > 1) {
                        echo '<a href="?pagina=1&inicial=' . urlencode($fecha_inicial) . '&final=' . urlencode($fecha_final) . '&item_id=' . urlencode($item_id) . '&lote=' . urlencode($lote) . '" class="btn-paginacion"><i class="fas fa-angle-double-left"></i></a>';
                        echo '<a href="?pagina=' . ($pagina - 1) . '&inicial=' . urlencode($fecha_inicial) . '&final=' . urlencode($fecha_final) . '&item_id=' . urlencode($item_id) . '&lote=' . urlencode($lote) . '" class="btn-paginacion"><i class="fas fa-angle-left"></i></a>';
                    }

                    // Mostrar las páginas del rango
                    for ($i = $inicio; $i <= $fin; $i++) {
                        $activeClass = ($i == $pagina) ? 'active' : '';
                        echo '<a href="?pagina=' . $i . '&inicial=' . urlencode($fecha_inicial) . '&final=' . urlencode($fecha_final) . '&item_id=' . urlencode($item_id) . '&lote=' . urlencode($lote) . '" class="btn-paginacion ' . $activeClass . '">' . $i . '</a>';
                    }

                    // Mostrar el enlace a la página siguiente
                    if ($pagina < $total_paginas) {
                        echo '<a href="?pagina=' . ($pagina + 1) . '&inicial=' . urlencode($fecha_inicial) . '&final=' . urlencode($fecha_final) . '&item_id=' . urlencode($item_id) . '&lote=' . urlencode($lote) . '" class="btn-paginacion"><i class="fas fa-angle-right"></i></a>';
                        echo '<a href="?pagina=' . $total_paginas . '&inicial=' . urlencode($fecha_inicial) . '&final=' . urlencode($fecha_final) . '&item_id=' . urlencode($item_id) . '&lote=' . urlencode($lote) . '" class="btn-paginacion"><i class="fas fa-angle-double-right"></i></a>';
                    }
                    ?>
                </div>
            </div>

        </div> 
    </div> 
</body>

<script>
    $(document).ready(function() {
        $('#mibuscador').select2({
            placeholder: "Seleccione un medicamento",
            allowClear: true,
            theme: 'default'
        });

        $('.select2-container').css('width', '100%');
        
        $('.table-moderna tbody tr').hover(
            function() {
                $(this).addClass('table-hover-effect');
            },
            function() {
                $(this).removeClass('table-hover-effect');
            }
        );
    });
</script>

</html>