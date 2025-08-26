<?php
session_start();
include "../../conexionbd.php";
$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];

if ($usuario['id_rol'] == 11) {
    include "../header_farmaciah.php";
} else if ($usuario['id_rol'] == 4) {
    include "../header_farmaciah.php";
} else if ($usuario['id_rol'] == 5) {
    include "../header_farmaciah.php";
} else {
    echo "<script>window.Location='../../index.php';</script>";
}

// DEBUG: Agregar para verificar conexión
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// SOLUCIÓN PARA ONLY_FULL_GROUP_BY: Desactivar temporalmente o usar subconsulta
// Opción 1: Intentar desactivar ONLY_FULL_GROUP_BY temporalmente
$conexion->query("SET sql_mode = (SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");

// Opción 2: Obtener estructura de la tabla item_almacen para GROUP BY completo
$columns_query = "SHOW COLUMNS FROM item_almacen";
$columns_result = $conexion->query($columns_query);
$item_columns = [];
if ($columns_result) {
    while ($col = $columns_result->fetch_assoc()) {
        $item_columns[] = 'item_almacen.' . $col['Field'];
    }
}

//echo "<!-- DEBUG: Columnas encontradas: " . implode(', ', $item_columns) . " -->";

// Configuración de paginación
$records_per_page = 50;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Parámetros de búsqueda
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$grupo_filter = isset($_GET['grupo']) ? trim($_GET['grupo']) : '';
$estado_filter = isset($_GET['estado']) ? trim($_GET['estado']) : '';

// DEBUG: Mostrar parámetros de búsqueda
echo "<!-- DEBUG: search='$search', grupo='$grupo_filter', estado='$estado_filter' -->";

// Construir filtros WHERE
$where_conditions = [];
$search_params = [];

if (!empty($search)) {
    $where_conditions[] = "(item_almacen.item_code LIKE ? OR item_almacen.item_name LIKE ?)";
    $search_params[] = '%' . $search . '%';
    $search_params[] = '%' . $search . '%';
}

if (!empty($grupo_filter)) {
    $where_conditions[] = "item_almacen.grupo = ?";
    $search_params[] = $grupo_filter;
}

// Agregar filtro por estado si se especifica
if (!empty($estado_filter)) {
    switch ($estado_filter) {
        case 'critico':
            $where_conditions[] = "SUM(existencias_almacenh.existe_qty) <= item_almacen.item_min";
            break;
        case 'reorden':
            $where_conditions[] = "SUM(existencias_almacenh.existe_qty) <= item_almacen.reorden AND SUM(existencias_almacenh.existe_qty) > item_almacen.item_min";
            break;
        case 'normal':
            $where_conditions[] = "SUM(existencias_almacenh.existe_qty) > item_almacen.reorden";
            break;
    }
}

$where_clause = !empty($where_conditions) ? 'AND ' . implode(' AND ', $where_conditions) : '';

//// CONSULTA SIMPLIFICADA PARA DEBUG
//// Primero verificar si hay datos básicos
//$debug_query = "SELECT COUNT(*) as total FROM existencias_almacenh
//                INNER JOIN item_almacen ON existencias_almacenh.item_id = item_almacen.item_id";
//$debug_result = $conexion->query($debug_query);
//if ($debug_result) {
//    $debug_count = $debug_result->fetch_assoc()['total'];
//    echo "<!-- DEBUG: Total registros en JOIN: $debug_count -->";
//} else {
//    echo "<!-- DEBUG: Error en consulta básica: " . $conexion->error . " -->";
//}

// Consulta principal - VERSIÓN SIMPLIFICADA con subconsulta para evitar GROUP BY
$sql_main = "SELECT 
                subquery.*,
                COALESCE(existencias_data.total_existencias, 0) as total_existencias
             FROM (
                 SELECT DISTINCT item_almacen.*
                 FROM item_almacen 
                 INNER JOIN existencias_almacenh ON item_almacen.item_id = existencias_almacenh.item_id
                 WHERE 1=1 " . str_replace('SUM(existencias_almacenh.existe_qty)', 'existencias_almacenh.existe_qty', $where_clause) . "
             ) as subquery
             LEFT JOIN (
                 SELECT 
                     item_id,
                     SUM(existe_qty) as total_existencias
                 FROM existencias_almacenh 
                 GROUP BY item_id
             ) as existencias_data ON subquery.item_id = existencias_data.item_id
             ORDER BY subquery.item_code 
             LIMIT $records_per_page OFFSET $offset";

// Alternativa más simple si la anterior falla
$sql_main_simple = "SELECT DISTINCT
                        i.*,
                        (SELECT SUM(e.existe_qty) 
                         FROM existencias_almacenh e 
                         WHERE e.item_id = i.item_id) as total_existencias
                    FROM item_almacen i
                    INNER JOIN existencias_almacenh ex ON i.item_id = ex.item_id
                    WHERE 1=1 " . str_replace('SUM(existencias_almacenh.existe_qty)', 'ex.existe_qty', $where_clause) . "
                    ORDER BY i.item_code 
                    LIMIT $records_per_page OFFSET $offset";

// Si hay filtro de estado que requiere HAVING, agregarlo después del GROUP BY
if (!empty($estado_filter)) {
    $having_clause = "";
    switch ($estado_filter) {
        case 'critico':
            $having_clause = " HAVING SUM(existencias_almacenh.existe_qty) <= item_almacen.item_min";
            break;
        case 'reorden':
            $having_clause = " HAVING SUM(existencias_almacenh.existe_qty) <= item_almacen.reorden AND SUM(existencias_almacenh.existe_qty) > item_almacen.item_min";
            break;
        case 'normal':
            $having_clause = " HAVING SUM(existencias_almacenh.existe_qty) > item_almacen.reorden";
            break;
    }

    // Insertar HAVING antes de ORDER BY
    $sql_main = str_replace("ORDER BY", $having_clause . " ORDER BY", $sql_main);
}

// DEBUG: Mostrar consulta final
echo "<!-- DEBUG: SQL FINAL: $sql_main -->";

// Consulta de conteo (para paginación)
$sql_count = "SELECT COUNT(DISTINCT item_almacen.item_id) as total 
              FROM existencias_almacenh 
              INNER JOIN item_almacen ON existencias_almacenh.item_id = item_almacen.item_id 
              WHERE 1=1 " . str_replace('SUM(existencias_almacenh.existe_qty)', 'existencias_almacenh.existe_qty', $where_clause);

// Ejecutar consulta de conteo
if (!empty($search_params)) {
    $stmt_count = $conexion->prepare($sql_count);
    if ($stmt_count) {
        $types = str_repeat('s', count($search_params));
        $stmt_count->bind_param($types, ...$search_params);
        $stmt_count->execute();
        $count_result = $stmt_count->get_result();
        if ($count_result) {
            $total_records = $count_result->fetch_assoc()['total'];
        } else {
            echo "<!-- DEBUG: Error en resultado de conteo -->";
            $total_records = 0;
        }
        $stmt_count->close();
    } else {
        echo "<!-- DEBUG: Error preparando consulta de conteo: " . $conexion->error . " -->";
        $total_records = 0;
    }
} else {
    $count_result = $conexion->query($sql_count);
    if ($count_result) {
        $total_records = $count_result->fetch_assoc()['total'];
    } else {
        echo "<!-- DEBUG: Error en consulta de conteo: " . $conexion->error . " -->";
        $total_records = 0;
    }
}

$total_pages = ceil($total_records / $records_per_page);
echo "<!-- DEBUG: Total records: $total_records, Total pages: $total_pages -->";

// Ejecutar consulta principal - CON MANEJO DE ERRORES MEJORADO
$resultado = false;
$error_message = '';

if (!empty($search_params)) {
    // Intentar primera consulta
    $stmt_main = $conexion->prepare($sql_main);
    if ($stmt_main) {
        $types = str_repeat('s', count($search_params));
        $stmt_main->bind_param($types, ...$search_params);

        if ($stmt_main->execute()) {
            $resultado = $stmt_main->get_result();
            if ($resultado) {
                echo "<!-- DEBUG: Consulta principal exitosa. Filas: " . $resultado->num_rows . " -->";
            } else {
                echo "<!-- DEBUG: Error en get_result: " . $conexion->error . " -->";
            }
        } else {
            $error_message = $conexion->error;
            echo "<!-- DEBUG: Error ejecutando consulta principal: $error_message -->";

            // Intentar consulta alternativa simple
            echo "<!-- DEBUG: Intentando consulta alternativa -->";
            $stmt_simple = $conexion->prepare($sql_main_simple);
            if ($stmt_simple) {
                $stmt_simple->bind_param($types, ...$search_params);
                if ($stmt_simple->execute()) {
                    $resultado = $stmt_simple->get_result();
                    echo "<!-- DEBUG: Consulta alternativa exitosa -->";
                } else {
                    echo "<!-- DEBUG: Error en consulta alternativa: " . $conexion->error . " -->";
                }
            }
        }
    } else {
        echo "<!-- DEBUG: Error preparando consulta principal: " . $conexion->error . " -->";
    }
} else {
    // Sin parámetros de búsqueda
    $resultado = $conexion->query($sql_main);
    if ($resultado) {
        echo "<!-- DEBUG: Consulta sin parámetros exitosa. Filas: " . $resultado->num_rows . " -->";
    } else {
        $error_message = $conexion->error;
        echo "<!-- DEBUG: Error en consulta sin parámetros: $error_message -->";

        // Intentar consulta alternativa
        $resultado = $conexion->query($sql_main_simple);
        if ($resultado) {
            echo "<!-- DEBUG: Consulta alternativa sin parámetros exitosa -->";
        } else {
            echo "<!-- DEBUG: Error en consulta alternativa sin parámetros: " . $conexion->error . " -->";
        }
    }
}

// Obtener grupos únicos para el filtro
$grupos_query = "SELECT DISTINCT grupo FROM item_almacen WHERE grupo IS NOT NULL AND grupo != '' ORDER BY grupo";
$grupos_result = $conexion->query($grupos_query);

if (!$grupos_result) {
    echo "<!-- DEBUG: Error en consulta de grupos: " . $conexion->error . " -->";
}
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
        <script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
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

            /* ===== MENSAJE DE DEBUG ===== */
            .debug-info {
                background: #f8f9fa;
                border: 1px solid #dee2e6;
                border-radius: 8px;
                padding: 15px;
                margin: 20px 0;
                font-family: monospace;
                font-size: 14px;
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
                width: 100%;
                table-layout: auto;
                border-collapse: collapse;
            }

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

            /* ===== ESTADOS DE EXISTENCIAS ===== */
            .estado-excelente {
                background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
                color: white !important;
                font-weight: bold;
                border-radius: 8px;
            }

            .estado-normal {
                background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%) !important;
                color: black !important;
                font-weight: bold;
                border-radius: 8px;
            }

            .estado-critico {
                background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
                color: white !important;
                font-weight: bold;
                border-radius: 8px;
            }

            /* ===== PAGINACIÓN MODERNA ===== */
            .contenedor-paginacion {
                display: flex;
                justify-content: center;
                margin: 30px 0;
                padding: 20px 0;
            }

            .paginacion-moderna {
                display: flex;
                gap: 8px;
                align-items: center;
                flex-wrap: wrap;
                justify-content: center;
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
                font-size: 14px;
                gap: 5px;
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
                transform: translateY(-1px);
            }

            .btn-paginacion.disabled {
                opacity: 0.5;
                cursor: not-allowed;
                background: #f8f9fa;
                color: #6c757d;
            }

            .btn-paginacion.disabled:hover {
                transform: none;
                box-shadow: none;
                background: #f8f9fa;
                color: #6c757d;
            }

            .info-paginacion {
                text-align: center;
                margin-bottom: 15px;
                color: var(--color-primario);
                font-weight: 600;
                font-size: 14px;
            }

            /* ===== INPUT GROUPS MODERNOS ===== */
            .input-group-text {
                border: 2px solid var(--color-borde);
                border-right: none;
                background: var(--color-fondo);
                color: var(--color-primario);
                border-radius: 10px 0 0 10px;
            }

            .input-group .form-control {
                border-left: none;
                border-radius: 0 10px 10px 0;
            }

            /* ===== LEYENDA DE ESTADOS ===== */
            .leyenda-estados {
                background: white;
                border: 2px solid var(--color-borde);
                border-radius: 12px;
                padding: 15px;
                margin: 20px 0;
                box-shadow: var(--sombra);
            }

            .estado-item {
                padding: 8px 12px;
                border-radius: 8px;
                margin: 0 8px 8px 0;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 5px;
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
                    padding: 6px 4px;
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
        <!-- Botón de regreso -->
        <div class="container">
            <div class="row mb-3">
                <div class="col-12">
                    <a href="existenciash.php" class="btn-moderno btn-regresar">
                        <i class="fas fa-arrow-left"></i> Regresar
                    </a>
                </div>
            </div>
        </div>

        <!-- Container principal moderno -->
        <div class="container-moderno">
            <!-- Header principal -->
            <div class="header-principal">
                <i class="fas fa-globe icono-principal"></i>
                <h1>EXISTENCIAS GLOBALES DE FARMACIA HOSPITALARIA</h1>
            </div>

<!--           Información de debug (temporal) -->
<!--            <div class="debug-info">-->
<!--                <strong>DEBUG INFO:</strong><br>-->
<!--                Total registros encontrados: --><?php //echo isset($total_records) ? $total_records : 'No calculado'; ?><!--<br>-->
<!--                Página actual: --><?php //echo $page; ?><!--<br>-->
<!--                Parámetros de búsqueda: --><?php //echo !empty($search_params) ? implode(', ', $search_params) : 'Ninguno'; ?><!--<br>-->
<!--                --><?php //if (isset($resultado) && $resultado): ?>
<!--                    Filas en resultado: --><?php //echo $resultado->num_rows; ?><!--<br>-->
<!--                --><?php //endif; ?>
<!--                --><?php //if (isset($error_message) && !empty($error_message)): ?>
<!--                    <span style="color: red;">Error SQL: --><?php //echo htmlspecialchars($error_message); ?><!--</span><br>-->
<!--                --><?php //endif; ?>
<!--                SQL Mode actual: --><?php
//                $sql_mode_result = $conexion->query("SELECT @@sql_mode");
//                if ($sql_mode_result) {
//                    $sql_mode = $sql_mode_result->fetch_row()[0];
//                    echo htmlspecialchars($sql_mode);
//                }
//                ?>
<!--            </div>-->

            <!-- Contenedor de filtros -->
            <div class="contenedor-filtros">
                <form method="GET" action="">
                    <div class="row">
                        <!-- Campo de búsqueda -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-search"></i> Búsqueda rápida
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    </div>
                                    <input type="text"
                                           name="search"
                                           class="form-control"
                                           placeholder="Buscar por código o nombre..."
                                           value="<?php echo htmlspecialchars($search); ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Filtro por grupo -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-layer-group"></i> Grupo
                                </label>
                                <select name="grupo" class="form-control">
                                    <option value="">Todos los grupos</option>
                                    <?php
                                    if ($grupos_result && $grupos_result->num_rows > 0):
                                        while ($grupo = $grupos_result->fetch_assoc()):
                                            ?>
                                            <option value="<?php echo htmlspecialchars($grupo['grupo']); ?>"
                                                    <?php echo ($grupo_filter == $grupo['grupo']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($grupo['grupo']); ?>
                                            </option>
                                        <?php
                                        endwhile;
                                    endif;
                                    ?>
                                </select>
                            </div>
                        </div>

<!--                         Filtro por estado -->
<!--                        <div class="col-md-3">-->
<!--                            <div class="form-group">-->
<!--                                <label class="form-label">-->
<!--                                    <i class="fas fa-flag"></i> Estado-->
<!--                                </label>-->
<!--                                <select name="estado" class="form-control">-->
<!--                                    <option value="">Todos los estados</option>-->
<!--                                    <option value="critico" --><?php //echo ($estado_filter == 'critico') ? 'selected' : ''; ?><!--Crítico</option>-->
<!--                                    <option value="reorden" --><?php //echo ($estado_filter == 'reorden') ? 'selected' : ''; ?><!--Reordenar</option>-->
<!--                                    <option value="normal" --><?php //echo ($estado_filter == 'normal') ? 'selected' : ''; ?><!--Normal</option>-->
<!--                                </select>-->
<!--                            </div>-->
<!--                        </div>-->

                        <!-- Botones -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2"> <!-- flex-row por defecto -->
                                    <button type="submit" class="btn-moderno btn-filtrar">
                                        <i class="fas fa-filter"></i> Filtrar
                                    </button>
                                    <a href="?" class="btn-moderno btn-borrar">
                                        <i class="fas fa-broom"></i> Limpiar
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>

                <!-- Leyenda de estados -->
                <div class="leyenda-estados">
                    <strong><i class="fas fa-info-circle"></i> Leyenda de Estados:</strong>
                    <span class="estado-item estado-critico">
                        <i class="fas fa-exclamation-triangle"></i> Crítico (≤ Mínimo)
                    </span>
                    <span class="estado-item estado-normal">
                        <i class="fas fa-exclamation-circle"></i> Reordenar (≤ P. Reorden)
                    </span>
                    <span class="estado-item estado-excelente">
                        <i class="fas fa-check-circle"></i> Stock Normal
                    </span>
                </div>
            </div>

            <!-- Tabla de resultados -->
            <div class="tabla-contenedor">
                <table class="table table-moderna">
                    <thead>
                    <tr>
                        <th><i class="fas fa-barcode"></i> Código</th>
                        <th><i class="fas fa-pills"></i> Medicamento / Insumo</th>
                        <th><i class="fas fa-layer-group"></i> Grupo</th>
                        <th><i class="fas fa-arrow-up"></i> Máximo</th>
                        <th><i class="fas fa-refresh"></i> P.reorden</th>
                        <th><i class="fas fa-arrow-down"></i> Mínimo</th>
                        <th><i class="fas fa-boxes"></i> Existencias</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (isset($resultado) && $resultado && $resultado->num_rows > 0):
                        while ($row = $resultado->fetch_assoc()):
                            $existencias = (int)$row['total_existencias'];
                            $maximo = (int)$row['item_max'];
                            $minimo = (int)$row['item_min'];
                            $reordena = (int)$row['reorden'];

                            // Determinar clase de estado
                            $estado_clase = '';
                            if ($existencias <= $minimo) {
                                $estado_clase = 'estado-critico';
                            } elseif ($existencias <= $reordena) {
                                $estado_clase = 'estado-normal';
                            } else {
                                $estado_clase = 'estado-excelente';
                            }
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['item_code']); ?></td>
                                <td><?php echo htmlspecialchars($row['item_name'] . ', ' . $row['item_grams']); ?></td>
                                <td><?php echo htmlspecialchars($row['grupo']); ?></td>
                                <td><?php echo number_format($maximo); ?></td>
                                <td><?php echo number_format($reordena); ?></td>
                                <td><?php echo number_format($minimo); ?></td>
                                <td class="<?php echo $estado_clase; ?>">
                                    <?php echo number_format($existencias); ?>
                                </td>
                            </tr>
                        <?php
                        endwhile;
                    else:
                        ?>
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="mensaje-sin-resultados">
                                    <i class="fas fa-search"></i>
                                    <p>No se encontraron resultados con los filtros aplicados</p>
                                    <?php if (isset($resultado)): ?>
                                        <small>Estado de la consulta: <?php echo $resultado ? 'Exitosa' : 'Error'; ?></small>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <?php if (isset($total_pages) && $total_pages > 1): ?>
                <div class="contenedor-paginacion">
                    <div class="info-paginacion">
<!--                        Mostrando página --><?php //echo $page; ?><!-- de --><?php //echo $total_pages; ?>
<!--                        (--><?php //echo number_format($total_records); ?><!-- registros totales)-->
                    </div>
                    <div class="paginacion-moderna">
                        <?php
                        // Construir parámetros para paginación
                        $params = '';
                        if (!empty($search)) $params .= '&search=' . urlencode($search);
                        if (!empty($grupo_filter)) $params .= '&grupo=' . urlencode($grupo_filter);
                        if (!empty($estado_filter)) $params .= '&estado=' . urlencode($estado_filter);

                        $rango = 3;
                        $inicio = max(1, $page - $rango);
                        $fin = min($total_pages, $page + $rango);

                        // Botones de navegación
                        if ($page > 1): ?>
                            <a href="?page=1<?php echo $params; ?>" class="btn-paginacion">
                                <i class="fas fa-angle-double-left"></i> Primero
                            </a>
                            <a href="?page=<?php echo ($page - 1); ?><?php echo $params; ?>" class="btn-paginacion">
                                <i class="fas fa-angle-left"></i> Anterior
                            </a>
                        <?php else: ?>
                            <span class="btn-paginacion disabled">
                            <i class="fas fa-angle-double-left"></i> Primero
                        </span>
                            <span class="btn-paginacion disabled">
                            <i class="fas fa-angle-left"></i> Anterior
                        </span>
                        <?php endif;

                        // Páginas numeradas
                        for ($i = $inicio; $i <= $fin; $i++):
                            $active = ($i == $page) ? 'active' : '';
                            ?>
                            <a href="?page=<?php echo $i; ?><?php echo $params; ?>"
                               class="btn-paginacion <?php echo $active; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor;

                        // Botones de navegación hacia adelante
                        if ($page < $total_pages): ?>
                            <a href="?page=<?php echo ($page + 1); ?><?php echo $params; ?>" class="btn-paginacion">
                                Siguiente <i class="fas fa-angle-right"></i>
                            </a>
                            <a href="?page=<?php echo $total_pages; ?><?php echo $params; ?>" class="btn-paginacion">
                                Último <i class="fas fa-angle-double-right"></i>
                            </a>
                        <?php else: ?>
                            <span class="btn-paginacion disabled">
                            Siguiente <i class="fas fa-angle-right"></i>
                        </span>
                            <span class="btn-paginacion disabled">
                            Último <i class="fas fa-angle-double-right"></i>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.min.js"></script>

    <script>
        // Búsqueda en tiempo real opcional
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="search"]');
            let searchTimeout;

            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        this.form.submit();
                    }
                });
            }
        });
    </script>
    </body>
    </html>

<?php
// Cerrar conexión
$conexion->close();
include("../../template/footer.php");
?>