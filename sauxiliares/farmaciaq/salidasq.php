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
    FROM salidas_almacenq s
    INNER JOIN dat_ingreso di ON s.id_atencion = di.id_atencion
    INNER JOIN paciente p ON di.Id_exp = p.Id_exp
    WHERE p.nom_pac LIKE '%$searchTerm%' OR p.papell LIKE '%$searchTerm%' OR p.sapell LIKE '%$searchTerm%'
    ORDER BY di.id_atencion DESC
    LIMIT $inicio, $filasPorPagina";

$result = $conexion->query($query) or die($conexion->error);

// Verifica el rol del usuario para incluir la cabecera adecuada
$usuario = $_SESSION['login'];

if ($usuario['id_rol'] == 7) {
    include "../header_farmaciaq.php";
} elseif ($usuario['id_rol'] == 3) {
    include "../../enfermera/header_enfermera.php";
} elseif ($usuario['id_rol'] == 4 || $usuario['id_rol'] == 5) {
    include "../header_farmaciaq.php";
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
    <title>Salidas de Medicamentos - Farmacia Quirófano</title>
    
    <!-- Bootstrap 4.0.0 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    
    <!-- Font Awesome 6.0.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- jQuery y Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        :root {
            --primary-color: #2b2d7f;
            --primary-dark: #1e1f5a;
            --primary-light: #4a4db8;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 20px 0;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 8px 32px rgba(43,45,127,0.3);
            text-align: center;
        }
        
        .page-header h1 {
            margin: 0;
            font-size: 2.2rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .page-header p {
            margin: 10px 0 0 0;
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .search-container {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .search-input {
            border-radius: 25px;
            border: 2px solid #e9ecef;
            padding: 12px 20px 12px 45px;
            transition: all 0.3s ease;
            font-size: 16px;
        }
        
        .search-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(43,45,127,0.25);
            border-color: var(--primary-light);
        }
        
        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 18px;
        }
        
        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .table-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 20px;
            margin: 0;
        }
        
        .table-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.2rem;
        }
        
        .table thead th {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            padding: 15px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
            border: none;
        }
        
        .table tbody tr:hover {
            background-color: rgba(43,45,127,0.1);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border: none;
            font-size: 0.95rem;
        }
        
        .btn-expediente {
            background: linear-gradient(45deg, var(--danger-color), #c82333);
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(220,53,69,0.3);
        }
        
        .btn-expediente:hover {
            background: linear-gradient(45deg, #c82333, #bd2130);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220,53,69,0.4);
            color: white;
            text-decoration: none;
        }
        
        .patient-name {
            font-weight: 600;
            color: var(--primary-dark);
        }
        
        .stats-container {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .stat-card {
            text-align: center;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        
        .pagination {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            justify-content: center;
        }
        
        .page-link {
            border-radius: 8px;
            margin: 0 2px;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .page-link:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(43,45,127,0.3);
        }
        
        .page-item.active .page-link {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            border-color: var(--primary-color);
            box-shadow: 0 4px 15px rgba(43,45,127,0.3);
        }
        
        .no-data-message {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        
        .container-main {
            max-width: 95%;
            margin: 0 auto;
        }
        
        .back-button {
            background: linear-gradient(45deg, var(--danger-color), #c82333);
            border: none;
            border-radius: 25px;
            padding: 12px 25px;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(220,53,69,0.3);
        }
        
        .back-button:hover {
            background: linear-gradient(45deg, #c82333, #bd2130);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220,53,69,0.4);
            color: white;
            text-decoration: none;
        }
        
        .search-results-info {
            background: linear-gradient(45deg, #e3f2fd, #bbdefb);
            border: none;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            color: var(--primary-dark);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="container-main">
            <!-- Encabezado de la página -->
            <div class="page-header">
                <h1><i class="fas fa-file-export"></i> SALIDAS DE MEDICAMENTOS</h1>
                <p>Consulta de dispensaciones desde farmacia quirófano</p>
            </div>

            <!-- Botón de regreso -->
            <div class="text-center mb-4">
                <a href="../../template/menu_farmaciaq.php" class="back-button">
                    <i class="fas fa-arrow-left"></i> Regresar 
                </a>
            </div>

            <!-- Estadísticas rápidas -->
            <div class="stats-container">
                <h6><i class="fas fa-chart-bar"></i> Resumen de Consulta</h6>
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-card" style="background: linear-gradient(45deg, var(--primary-color), var(--primary-dark)); color: white;">
                            <h4 id="total-patients"><?php echo $totalFilas; ?></h4>
                            <small>Total Pacientes</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card" style="background: linear-gradient(45deg, #28a745, #1e7e34); color: white;">
                            <h4><?php echo $totalPaginas; ?></h4>
                            <small>Total Páginas</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card" style="background: linear-gradient(45deg, #17a2b8, #117a8b); color: white;">
                            <h4><?php echo $paginaActual; ?></h4>
                            <small>Página Actual</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card" style="background: linear-gradient(45deg, #ffc107, #e0a800); color: #333;">
                            <h4><?php echo min($filasPorPagina, $totalFilas); ?></h4>
                            <small>Registros por Página</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buscador -->
            <div class="search-container">
                <h6><i class="fas fa-search"></i> Búsqueda de Pacientes</h6>
                <div class="position-relative">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" 
                           class="form-control search-input" 
                           id="search" 
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" 
                           placeholder="Buscar por nombre o apellidos del paciente...">
                </div>
                <?php if (!empty($searchTerm)): ?>
                <div class="search-results-info mt-3">
                    <i class="fas fa-info-circle"></i> 
                    Mostrando resultados para: "<strong><?php echo htmlspecialchars($searchTerm); ?></strong>" 
                    (<?php echo $totalFilas; ?> paciente<?php echo $totalFilas != 1 ? 's' : ''; ?> encontrado<?php echo $totalFilas != 1 ? 's' : ''; ?>)
                    <a href="salidasq.php" class="float-right text-primary">
                        <i class="fas fa-times"></i> Limpiar búsqueda
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <!-- Tabla de pacientes -->
            <div class="table-container">
                <div class="table-header">
                    <h5><i class="fas fa-users"></i> Pacientes con Salidas de Medicamentos</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" id="mytable">
                        <thead>
                            <tr>
                                <th width="20%"><i class="fas fa-id-card"></i> EXPEDIENTE</th>
                                <th width="80%"><i class="fas fa-user"></i> PACIENTE</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="text-center">
                                        <a href="select_fecha_vista.php?id_atencion=<?php echo $row['id_atencion']; ?>" 
                                           class="btn-expediente"
                                           title="Ver salidas del expediente <?php echo $row['Id_exp']; ?>">
                                            <i class="fas fa-folder-open"></i> <?php echo $row['Id_exp']; ?>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="patient-name">
                                            <i class="fas fa-user-circle"></i> 
                                            <?php echo $row['nom_pac'] . " " . $row['papell'] . " " . $row['sapell']; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2">
                                    <div class="no-data-message">
                                        <i class="fas fa-search fa-3x mb-3" style="color: #6c757d;"></i>
                                        <h4>No se encontraron pacientes</h4>
                                        <?php if (!empty($searchTerm)): ?>
                                            <p>No hay pacientes que coincidan con la búsqueda "<strong><?php echo htmlspecialchars($searchTerm); ?></strong>"</p>
                                            <a href="salidasq.php" class="btn btn-primary">
                                                <i class="fas fa-redo"></i> Mostrar todos los pacientes
                                            </a>
                                        <?php else: ?>
                                            <p>No hay registros de salidas de medicamentos disponibles.</p>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Paginación -->
            <?php if ($totalPaginas > 1): ?>
            <nav aria-label="Navegación de páginas" class="pagination">
                <ul class="pagination">
                    <?php if ($paginaActual > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?pagina=<?php echo $paginaActual - 1; ?>&search=<?php echo urlencode($searchTerm); ?>" aria-label="Anterior">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = $inicioRango; $i <= $finRango; $i++): ?>
                        <li class="page-item <?php echo ($paginaActual == $i) ? 'active' : ''; ?>">
                            <a class="page-link" href="?pagina=<?php echo $i; ?>&search=<?php echo urlencode($searchTerm); ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($paginaActual < $totalPaginas): ?>
                        <li class="page-item">
                            <a class="page-link" href="?pagina=<?php echo $paginaActual + 1; ?>&search=<?php echo urlencode($searchTerm); ?>" aria-label="Siguiente">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>

    <footer class="main-footer">
        <?php include("../../template/footer.php"); ?>
    </footer>

    <script>
        $(document).ready(function () {
            // Búsqueda en tiempo real mejorada
            let searchTimeout;
            
            $("#search").on('input', function () {
                clearTimeout(searchTimeout);
                var searchTerm = $(this).val();
                
                // Debounce la búsqueda para evitar múltiples requests
                searchTimeout = setTimeout(function() {
                    if (searchTerm.length >= 2 || searchTerm.length === 0) {
                        window.location.href = "?pagina=1&search=" + encodeURIComponent(searchTerm);
                    }
                }, 500);
            });
            
            // Enter key para búsqueda inmediata
            $("#search").keypress(function(e) {
                if (e.which == 13) {
                    clearTimeout(searchTimeout);
                    var searchTerm = $(this).val();
                    window.location.href = "?pagina=1&search=" + encodeURIComponent(searchTerm);
                }
            });
            
            // Animaciones suaves para los botones
            $('.btn-expediente').hover(
                function() {
                    $(this).find('i').addClass('fa-bounce');
                },
                function() {
                    $(this).find('i').removeClass('fa-bounce');
                }
            );
            
            // Efecto de carga para las páginas
            $('.page-link').click(function(e) {
                if (!$(this).parent().hasClass('active')) {
                    $(this).html('<i class="fas fa-spinner fa-spin"></i>');
                }
            });
            
            // Auto-focus en el campo de búsqueda
            $("#search").focus();
        });
    </script>
</body>
</html>
