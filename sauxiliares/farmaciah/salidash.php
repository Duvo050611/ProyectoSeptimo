<?php
session_start();
include "../../conexionbd.php";

// Configuración de paginación
$filasPorPagina = 15; // Número de filas por página
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1; // Página actual
$inicio = ($paginaActual > 1) ? ($paginaActual * $filasPorPagina) - $filasPorPagina : 0;

// Obtener el término de búsqueda
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Contar el total de filas con el filtro de búsqueda
$totalFilasQuery = $conexion->query("SELECT COUNT(*) as total FROM dat_ingreso 
    INNER JOIN paciente ON dat_ingreso.Id_exp = paciente.Id_exp
    WHERE paciente.nom_pac LIKE '%$searchTerm%' OR paciente.papell LIKE '%$searchTerm%' OR paciente.sapell LIKE '%$searchTerm%'");
$totalFilas = $totalFilasQuery->fetch_assoc()['total'];

// Calcular el número total de páginas
$totalPaginas = ceil($totalFilas / $filasPorPagina);

// Calcular el rango de páginas para mostrar de a 5 páginas
$inicioRango = max(1, $paginaActual - 2);
$finRango = min($totalPaginas, $paginaActual + 2);

// Si el total de páginas es menor a 5, mostramos todas
if ($totalPaginas < 5) {
    $inicioRango = 1;
    $finRango = $totalPaginas;
}

// Consulta con filtro de búsqueda y límite para paginación
$query = "SELECT DISTINCT s.id_atencion=di.id_atencion, di.*, p.*
    FROM salidas_almacenh s
    INNER JOIN dat_ingreso di ON s.id_atencion = di.id_atencion
    INNER JOIN paciente p ON di.Id_exp = p.Id_exp
    WHERE p.nom_pac LIKE '%$searchTerm%' OR p.papell LIKE '%$searchTerm%' OR p.sapell LIKE '%$searchTerm%'
    ORDER BY di.id_atencion DESC
    LIMIT $inicio, $filasPorPagina";

$result = $conexion->query($query) or die($conexion->error);

// Verifica el rol del usuario para incluir la cabecera adecuada
$usuario = $_SESSION['login'];

if ($usuario['id_rol'] == 7) {
    include "../header_farmaciah.php";
} elseif ($usuario['id_rol'] == 3) {
    include "../../enfermera/header_enfermera.php";
} elseif ($usuario['id_rol'] == 1) {
    include "../../gestion_administrativa/header_administrador.php";
} elseif ($usuario['id_rol'] == 4 || $usuario['id_rol'] == 5) {
    include "../header_farmaciah.php";
} else {
    echo "<script>window.Location='../../index.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salidas Hospitalaria</title>
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

        .btn-expediente {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white !important;
            padding: 8px 16px;
            font-size: 14px;
            min-width: 120px;
            justify-content: center;
        }

        .btn-moderno:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            text-decoration: none;
        }

        /* ===== HEADER SECTION ===== */
        .header-principal {
            text-align: center;
            margin-bottom: 30px;
            padding: 25px 0;
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            border-radius: 20px;
            color: white;
            box-shadow: var(--sombra);
        }

        .header-principal .icono-principal {
            font-size: 36px;
            margin-bottom: 10px;
            display: block;
        }

        .header-principal h2 {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            line-height: 1.3;
        }

        /* ===== CONTENEDOR DE FILTROS ===== */
        .contenedor-filtros {
            background: white;
            border: 2px solid var(--color-borde);
            border-radius: 15px;
            padding: 25px;
            margin: 25px 0;
            box-shadow: var(--sombra);
        }

        .form-control {
            border: 2px solid var(--color-borde);
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
            height: 48px;
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

        /* ===== BARRA DE BÚSQUEDA ESPECIAL ===== */
        .search-container {
            position: relative;
            max-width: 400px;
        }

        .search-container .form-control {
            padding-left: 45px;
            background: white;
            border: 2px solid var(--color-borde);
        }

        .search-container .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--color-primario);
            font-size: 16px;
            z-index: 2;
        }

        /* ===== TABLA MODERNIZADA ===== */
        .tabla-contenedor {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--sombra);
            border: 2px solid var(--color-borde);
            margin: 25px 0;
        }

        .table-moderna {
            margin: 0;
            font-size: 14px;
            width: 100%;
            border-collapse: collapse;
        }

        .table-moderna thead th {
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            color: white;
            border: none;
            padding: 18px 15px;
            font-weight: 600;
            text-align: center;
            font-size: 14px;
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
            padding: 15px;
            vertical-align: middle;
            border: none;
            text-align: center;
            font-size: 14px;
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
            margin: 30px 0;
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

        /* ===== TÍTULO DE SECCIÓN ===== */
        .titulo-seccion {
            color: var(--color-primario);
            font-weight: 700;
            font-size: 20px;
            margin: 25px 0 15px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .titulo-seccion i {
            font-size: 22px;
        }

        /* ===== STATS CARDS ===== */
        .stats-container {
            display: flex;
            gap: 20px;
            margin: 20px 0;
            flex-wrap: wrap;
        }

        .stats-card {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            flex: 1;
            min-width: 200px;
            box-shadow: var(--sombra);
            text-align: center;
        }

        .stats-card i {
            font-size: 32px;
            margin-bottom: 10px;
            opacity: 0.9;
        }

        .stats-card .stats-number {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stats-card .stats-label {
            font-size: 14px;
            opacity: 0.9;
        }

        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 768px) {
            .container-moderno {
                margin: 10px;
                padding: 20px;
                border-radius: 15px;
            }

            .header-principal h2 {
                font-size: 20px;
            }

            .btn-moderno {
                padding: 10px 16px;
                font-size: 14px;
            }

            .table-moderna {
                font-size: 12px;
            }

            .table-moderna thead th,
            .table-moderna tbody td {
                padding: 10px 8px;
            }

            .search-container {
                max-width: 100%;
            }

            .stats-container {
                flex-direction: column;
                gap: 15px;
            }

            .btn-expediente {
                min-width: 100px;
                font-size: 12px;
                padding: 6px 12px;
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

        /* ===== LOADING EFFECT ===== */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid var(--color-primario);
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<body>
<!-- Container Principal -->
<div class="container-moderno">
    <!-- Header Principal -->
    <div class="header-principal">
        <i class="fas fa-hospital-user icono-principal"></i>
        <h2>PACIENTES CON SALIDAS DE<br>MEDICAMENTOS E INSUMOS</h2>
    </div>

    <!-- Contenedor de Controles -->
    <div class="contenedor-filtros">
        <div class="row align-items-center">
            <!-- Botón Regresar -->
            <div class="col-md-6 mb-3 mb-md-0">
                <a href="../../template/menu_farmaciahosp.php" class="btn-moderno btn-regresar">
                    <i class="fas fa-arrow-left"></i>
                    Regresar
                </a>
            </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container">
        <div class="stats-card">
            <i class="fas fa-users"></i>
            <div class="stats-number"><?php echo $totalFilas; ?></div>
            <div class="stats-label">Total de Pacientes</div>
        </div>
        <div class="stats-card">
            <i class="fas fa-file-medical"></i>
            <div class="stats-number"><?php echo $totalPaginas; ?></div>
            <div class="stats-label">Páginas Total</div>
        </div>
        <div class="stats-card">
            <i class="fas fa-eye"></i>
            <div class="stats-number"><?php echo $paginaActual; ?></div>
            <div class="stats-label">Página Actual</div>
        </div>
    </div>

    <!-- Título de Tabla -->
    <div class="titulo-seccion">
        <i class="fas fa-list-alt"></i>
        Lista de Pacientes
        <?php if ($searchTerm): ?>
            <small style="font-size: 14px; font-weight: normal; opacity: 0.8;">
                - Resultados para: "<?php echo htmlspecialchars($searchTerm); ?>"
            </small>
        <?php endif; ?>
    </div>

    <!-- Tabla de Pacientes -->
    <div class="tabla-contenedor">
        <table class="table table-moderna">
            <thead>
            <tr>
                <th><i class="fas fa-id-card"></i> EXPEDIENTE</th>
                <th><i class="fas fa-user"></i> PACIENTE</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $hay_datos = false;
            while ($row = $result->fetch_assoc()):
                $hay_datos = true;
                ?>
                <tr>
                    <td>
                        <a href="select_fecha_vista.php?id_atencion=<?php echo $row['id_atencion']; ?>"
                           class="btn-moderno btn-expediente">
                            <i class="fas fa-folder-open"></i>
                            <?php echo htmlspecialchars($row['Id_exp']); ?>
                        </a>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: var(--color-primario);">
                            <?php echo htmlspecialchars($row['nom_pac'] . " " . $row['papell'] . " " . $row['sapell']); ?>
                        </div>
                        <small style="color: #666; font-size: 12px;">
                            <i class="fas fa-calendar-alt"></i>
                            ID Atención: <?php echo htmlspecialchars($row['id_atencion']); ?>
                        </small>
                    </td>
                </tr>
            <?php endwhile; ?>

            <?php if (!$hay_datos): ?>
                <tr>
                    <td colspan="2" class="mensaje-sin-resultados">
                        <i class="fas fa-search"></i>
                        <div>
                            <?php if ($searchTerm): ?>
                                No se encontraron pacientes que coincidan con: <strong>"<?php echo htmlspecialchars($searchTerm); ?>"</strong>
                            <?php else: ?>
                                No hay pacientes con salidas registradas
                            <?php endif; ?>
                        </div>
                        <small style="font-size: 14px; opacity: 0.7; margin-top: 10px; display: block;">
                            <?php if ($searchTerm): ?>
                                Intente con otros términos de búsqueda
                            <?php else: ?>
                                Los pacientes aparecerán aquí cuando tengan salidas de medicamentos
                            <?php endif; ?>
                        </small>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <?php if ($totalPaginas > 1): ?>
        <div class="contenedor-paginacion">
            <nav aria-label="Navegación de páginas">
                <div class="paginacion-moderna">
                    <!-- Botón Anterior -->
                    <?php if ($paginaActual > 1): ?>
                        <a class="btn-paginacion" href="?pagina=<?php echo $paginaActual - 1; ?>&search=<?php echo urlencode($searchTerm); ?>"
                           aria-label="Página anterior" title="Página anterior">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php endif; ?>

                    <!-- Números de página -->
                    <?php for ($i = $inicioRango; $i <= $finRango; $i++): ?>
                        <a class="btn-paginacion <?php echo ($paginaActual == $i) ? 'active' : ''; ?>"
                           href="?pagina=<?php echo $i; ?>&search=<?php echo urlencode($searchTerm); ?>"
                           title="Página <?php echo $i; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <!-- Botón Siguiente -->
                    <?php if ($paginaActual < $totalPaginas): ?>
                        <a class="btn-paginacion" href="?pagina=<?php echo $paginaActual + 1; ?>&search=<?php echo urlencode($searchTerm); ?>"
                           aria-label="Página siguiente" title="Página siguiente">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </nav>
        </div>

        <!-- Info de paginación -->
        <div class="text-center" style="color: #666; font-size: 14px; margin-top: 10px;">
            Mostrando <?php echo ($inicio + 1); ?> - <?php echo min($inicio + $filasPorPagina, $totalFilas); ?>
            de <?php echo $totalFilas; ?> registros
        </div>
    <?php endif; ?>
</div>

<!-- Footer -->
<footer class="main-footer" style="margin-top: 40px;">
    <?php include("../../template/footer.php"); ?>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        let searchTimeout;

        $("#search").on('input', function() {
            clearTimeout(searchTimeout);
            const searchTerm = $(this).val();
            const $tableContainer = $('.tabla-contenedor');

            // Añadir efecto de loading
            $tableContainer.addClass('loading');

            searchTimeout = setTimeout(function() {
                window.location.href = "?pagina=1&search=" + encodeURIComponent(searchTerm);
            }, 500); // Esperar 500ms antes de buscar
        });

        // Efecto hover mejorado para las filas
        $('.table-moderna tbody tr').hover(
            function() {
                $(this).find('.btn-expediente').addClass('shadow-lg');
            },
            function() {
                $(this).find('.btn-expediente').removeClass('shadow-lg');
            }
        );

        // Animación al cargar la página
        $('.container-moderno').hide().fadeIn(800);
    });
</script>
</body>

</html>