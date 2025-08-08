<?php
session_start();
include "../../conexionbd.php";
ob_start();

$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];

if (isset($usuario['id_rol'])) {
    if ($usuario['id_rol'] == 11 || $usuario['id_rol'] == 4 || $usuario['id_rol'] == 5) {
        include "../header_farmaciah.php";
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
    FROM salidas_almacenh s
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
        s.id_usua,
        s.motivo,
        s.tipo,
        s.salio
        FROM 
        salidas_almacenh s
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
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salidas - Farmacia Hospitalaria</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    
    <style>
        /* ===== VARIABLES CSS ===== */
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
</head>

<body>
    <div class="container-fluid">
        <div class="container-moderno">
            
            <!-- Botón de regreso modernizado -->
            <div class="d-flex justify-content-start mb-4">
                <a href="kardexh.php" class="btn-moderno btn-regresar">
                    <i class="fas fa-arrow-left"></i> Regresar
                </a>
            </div>

            <!-- Header principal modernizado -->
            <div class="header-principal">
                <div class="contenido-header">
                    <div class="icono-header">
                        <i class="fas fa-arrow-up icono-principal"></i>
                    </div>
                    <h1>SALIDAS - FARMACIA HOSPITALARIA</h1>
                </div>
                <a href="salidasPorAjusteh.php" class="btn-moderno btn-especial btn-ajuste">
                    <i class="fas fa-cog"></i> Salidas por Ajuste
                </a>
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
                            <input type="date" class="form-control" name="inicial" value="<?= $inicial ?>">
                        </div>

                        <!-- Input Fecha Final -->
                        <div class="form-group col-md-2">
                            <label class="form-label">
                                <i class="fas fa-calendar-check"></i> Fecha Final:
                            </label>
                            <input type="date" class="form-control" name="final" value="<?= $final ?>">
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
                            <a href="salidas_almacenh_historial.php" class="btn-moderno btn-borrar w-100">
                                <i class="fas fa-eraser"></i> Borrar Filtros
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabla modernizada o mensaje sin resultados -->
            <?php if ($resultado->num_rows > 0): ?>
                <div class="tabla-contenedor">
                    <div class="table-responsive">
                        <table class="table table-moderna">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-calendar-alt"></i> FECHA</th>
                                    <th><i class="fas fa-hashtag"></i> IDITEM</th>
                                    <th><i class="fas fa-pills"></i> MEDICAMENTO</th>
                                    <th><i class="fas fa-barcode"></i> LOTE</th>
                                    <th><i class="fas fa-clock"></i> CADUCIDAD</th>
                                    <th><i class="fas fa-cubes"></i> CANTIDAD</th>
                                    <th><i class="fas fa-comment"></i> MOTIVO</th>
                                    <th><i class="fas fa-tag"></i> TIPO</th>
                                    <th><i class="fas fa-location-arrow"></i> SALIÓ DE</th>
                                    <th><i class="fas fa-user"></i> IDUSUARIO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $resultado->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= date('d/m/Y H:i', strtotime($row['salida_fecha'])); ?></td>
                                        <td><?= $row['item_id']; ?></td>
                                        <td><?= $row['item_name'] . ', ' . $row['item_grams']; ?></td>
                                        <td><?= $row['salida_lote']; ?></td>
                                        <td><?= date('d/m/Y', strtotime($row['salida_caducidad'])); ?></td>
                                        <td><?= $row['salida_qty']; ?></td>
                                        <td><?= $row['motivo']; ?></td>
                                        <td><?= $row['tipo']; ?></td>
                                        <td><?= $row['salio']; ?></td>
                                        <td><?= $row['id_usua']; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Paginación modernizada -->
                <div class="contenedor-paginacion">
                    <div class="paginacion-moderna">
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
                            echo "<a href='?pagina=1$query_string' class='btn-paginacion'><i class='fas fa-angle-double-left'></i></a>";
                        }

                        // Mostrar páginas cercanas a la actual
                        $pagina_inicio = max(1, $pagina - 3);
                        $pagina_fin = min($total_paginas, $pagina + 3);

                        for ($i = $pagina_inicio; $i < $pagina; $i++) {
                            echo "<a href='?pagina=$i$query_string' class='btn-paginacion'>$i</a>";
                        }

                        // Página actual
                        echo "<a href='?pagina=$pagina$query_string' class='btn-paginacion active'>$pagina</a>";

                        for ($i = $pagina + 1; $i <= $pagina_fin; $i++) {
                            echo "<a href='?pagina=$i$query_string' class='btn-paginacion'>$i</a>";
                        }

                        // Mostrar enlace para la última página
                        if ($pagina < $total_paginas) {
                            echo "<a href='?pagina=$total_paginas$query_string' class='btn-paginacion'><i class='fas fa-angle-double-right'></i></a>";
                        }
                        ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="mensaje-sin-resultados">
                    <i class="fas fa-search"></i>
                    <div>No se encontraron registros con los filtros aplicados.</div>
                    <small>Intente modificar los criterios de búsqueda.</small>
                </div>
            <?php endif; ?>

        </div> <!-- Cerrar container-moderno -->
    </div> <!-- Cerrar container-fluid -->

    <script>
        $(document).ready(function() {
            // Inicializar Select2 con estilos personalizados
            $('#mibuscador').select2({
                placeholder: "Seleccione un medicamento",
                allowClear: true,
                width: '100%',
                theme: 'default'
            });

            // Mejorar la apariencia del Select2
            $('.select2-container').css('width', '100%');
            
            // Agregar animaciones a las filas de la tabla
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
</body>

</html>