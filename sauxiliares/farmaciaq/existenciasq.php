<?php
session_start();
include "../../conexionbd.php";

// Función para verificar sesión válida
function verificarSesion() {
    if (!isset($_SESSION['login'])) return false;
    if (!is_array($_SESSION['login'])) return false;
    if (!isset($_SESSION['login']['id_rol']) || !isset($_SESSION['login']['id_usua'])) return false;
    return true;
}

// Verificar sesión
if (!verificarSesion()) {
    // Limpiar sesión corrupta
    session_destroy();
    session_start();
    echo "<script>alert('Sesión inválida. Por favor, inicie sesión nuevamente.'); window.location='../../index.php';</script>";
    exit();
}

// Verificar que la sesión contenga datos válidos
if (!isset($_SESSION['login']) || !is_array($_SESSION['login'])) {
    session_destroy();
    session_start();
    echo "<script>alert('Sesión inválida. Por favor, inicie sesión nuevamente.'); window.location='../../index.php';</script>";
    exit();
}

$usuario = $_SESSION['login'];

// Verificar que existan los campos necesarios
if (!isset($usuario['id_usua']) || !isset($usuario['id_rol'])) {
    session_destroy();
    session_start();
    echo "<script>alert('Datos de sesión incompletos. Por favor, inicie sesión nuevamente.'); window.location='../../index.php';</script>";
    exit();
}

$id_usua = $usuario['id_usua'];

// Verificar permisos de rol
$roles_permitidos = array(4, 5, 7);
if (!in_array($usuario['id_rol'], $roles_permitidos)) {
    echo "<script>alert('No tiene permisos para acceder a esta página.'); window.location='../../index.php';</script>";
    exit();
}

if ($usuario['id_rol'] == 7) {
  include "../header_farmaciaq.php";
}  else if ($usuario['id_rol'] == 4 || $usuario['id_rol'] == 5) {
  include "../header_farmaciaq.php";
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Existencias Farmacia Central</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    
    <style>
        :root {
            --primary-color: #2b2d7f;
            --primary-dark: #1f2166;
            --primary-light: #3f418a;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .btn-custom {
            border-radius: 25px;
            padding: 10px 20px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .btn-danger-custom {
            background: linear-gradient(45deg, #dc3545, #c82333);
            border: none;
            color: white;
        }
        
        .btn-danger-custom:hover {
            background: linear-gradient(45deg, #c82333, #bd2130);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220,53,69,0.3);
            color: white;
        }
        
        .btn-warning-custom {
            background: linear-gradient(45deg, #ffc107, #e0a800);
            border: none;
            color: #212529;
        }
        
        .btn-warning-custom:hover {
            background: linear-gradient(45deg, #e0a800, #d39e00);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255,193,7,0.3);
            color: #212529;
        }
        
        .btn-success-custom {
            background: linear-gradient(45deg, #28a745, #1e7e34);
            border: none;
            color: white;
        }
        
        .btn-success-custom:hover {
            background: linear-gradient(45deg, #1e7e34, #155724);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40,167,69,0.3);
            color: white;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 8px 32px rgba(43,45,127,0.3);
            text-align: center;
        }
        
        .page-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .search-container {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .search-container input {
            border-radius: 25px;
            border: 2px solid var(--primary-color);
            padding: 12px 20px;
            font-size: 16px;
        }
        
        .search-container input:focus {
            box-shadow: 0 0 0 0.2rem rgba(43,45,127,0.25);
            border-color: var(--primary-light);
        }
        
        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }
        
        .table thead th {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            padding: 15px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover {
            background-color: rgba(43,45,127,0.1);
            transform: scale(1.01);
        }
        
        .status-high {
            background: linear-gradient(45deg, #28a745, #20c997) !important;
            color: white;
            font-weight: 600;
            text-align: center;
            border-radius: 8px;
            padding: 5px;
        }
        
        .status-low {
            background: linear-gradient(45deg, #dc3545, #fd7e14) !important;
            color: white;
            font-weight: 600;
            text-align: center;
            border-radius: 8px;
            padding: 5px;
        }
        
        .status-reorder {
            background: linear-gradient(45deg, #ffc107, #fd7e14) !important;
            color: #212529;
            font-weight: 600;
            text-align: center;
            border-radius: 8px;
            padding: 5px;
        }
        
        .status-normal {
            background: linear-gradient(45deg, #17a2b8, #6f42c1) !important;
            color: white;
            font-weight: 600;
            text-align: center;
            border-radius: 8px;
            padding: 5px;
        }
    </style>
    
    <script>
      // Write on keyup event of keyword input element
      $(document).ready(function() {
        $("#search").keyup(function() {
          _this = this;
          // Show only matching TR, hide rest of them
          $.each($("#mytable tbody tr"), function() {
            if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
              $(this).hide();
            else
              $(this).show();
          });
        });
      });
    </script>
</head>

<body>
    <div class="container-fluid">
        <div class="page-header">
            <h1><i class="fas fa-pills"></i> EXISTENCIAS DE FARMACIA CENTRAL</h1>
        </div>
        
        <!-- Botones de navegación -->
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex justify-content-center mb-4" style="z-index: 1000; position: relative;">
                    <a class="btn btn-custom btn-danger-custom mx-2" href="../../template/menu_farmaciaq.php" style="display: inline-block !important;">
                        <i class="fas fa-arrow-left"></i> Regresar
                    </a>
                    <a class="btn btn-custom btn-warning-custom mx-2" href="existencias_global.php" style="display: inline-block !important;">
                        <i class="fas fa-globe"></i> Existencias Globales
                    </a>
                    <a class="btn btn-custom btn-success-custom mx-2" href="excelexistenciasq.php" style="display: inline-block !important;">
                        <i class="fas fa-file-excel"></i> Exportar a Excel
                    </a>
                </div>
            </div>
        </div>
        
        <!--Inicio de búsqueda-->
        <div class="search-container">
            <div class="form-group mb-0">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" style="background: var(--primary-color); color: white; border: 2px solid var(--primary-color);">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" id="search" placeholder="Buscar medicamento, lote, código...">
                </div>
            </div>
        </div>
        
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="mytable">
                    <thead>
                        <tr>
                            <th><i class="fas fa-barcode"></i> Código</th>
                            <th><i class="fas fa-pills"></i> Medicamento / Insumo</th>
                            <th><i class="fas fa-tags"></i> Lote</th>
                            <th><i class="fas fa-calendar-alt"></i> Caducidad</th>
                            <th><i class="fas fa-arrow-up"></i> Máximo</th>
                            <th><i class="fas fa-exclamation-triangle"></i> P.reorden</th>
                            <th><i class="fas fa-arrow-down"></i> Mínimo</th>
                            <th><i class="fas fa-boxes"></i> Existencias</th>
                            <th><i class="fas fa-map-marker-alt"></i> Ubicación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            // Configuración de paginación
                            $registros_por_pagina = 200;
                            $pagina_actual = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
                            $offset = ($pagina_actual - 1) * $registros_por_pagina;
                            
                            // Primero verificar cada tabla por separado para debug
                            echo "<!-- DEBUG: Verificando tablas -->";
                            
                            // Verificar tabla item_almacen
                            $check1 = $conexion->query("SELECT COUNT(*) as count FROM item_almacen");
                            if ($check1) {
                                $count1 = $check1->fetch_assoc();
                                echo "<!-- DEBUG: item_almacen tiene " . $count1['count'] . " registros -->";
                            }
                            
                            // Verificar tabla existencias_almacenh
                            $check2 = $conexion->query("SELECT COUNT(*) as count FROM existencias_almacenh");
                            if ($check2) {
                                $count2 = $check2->fetch_assoc();
                                echo "<!-- DEBUG: existencias_almacenh tiene " . $count2['count'] . " registros -->";
                            }
                            
                            // Verificar tabla item_type
                            $check3 = $conexion->query("SELECT COUNT(*) as count FROM item_type");
                            if ($check3) {
                                $count3 = $check3->fetch_assoc();
                                echo "<!-- DEBUG: item_type tiene " . $count3['count'] . " registros -->";
                            }
                            
                            // Contar total de registros para paginación
                            $count_sql = "SELECT COUNT(*) as total 
                                         FROM item_almacen ia 
                                         LEFT JOIN existencias_almacenh ea ON ia.item_id = ea.item_id 
                                         LEFT JOIN item_type it ON it.item_type_id = ia.item_type_id 
                                         WHERE ea.existe_qty IS NOT NULL AND ea.existe_qty > 0";
                            
                            $count_result = $conexion->query($count_sql);
                            $total_registros = 0;
                            if ($count_result) {
                                $count_row = $count_result->fetch_assoc();
                                $total_registros = $count_row['total'];
                            }
                            
                            $total_paginas = ceil($total_registros / $registros_por_pagina);
                            echo "<!-- DEBUG: Total registros: $total_registros, Página actual: $pagina_actual, Total páginas: $total_paginas -->";
                            
                            // Consulta principal con paginación
                            $sql = "SELECT ia.*, ea.*, it.item_type_desc 
                                   FROM item_almacen ia 
                                   LEFT JOIN existencias_almacenh ea ON ia.item_id = ea.item_id 
                                   LEFT JOIN item_type it ON it.item_type_id = ia.item_type_id 
                                   WHERE ea.existe_qty IS NOT NULL AND ea.existe_qty > 0
                                   ORDER BY ia.item_id 
                                   LIMIT $registros_por_pagina OFFSET $offset";
                            
                            echo "<!-- DEBUG: Ejecutando consulta con LEFT JOIN y paginación -->";
                            $resultado2 = $conexion->query($sql);
                            
                            if (!$resultado2) {
                                echo '<tr><td colspan="9" class="text-center text-danger">Error en consulta: ' . htmlspecialchars($conexion->error) . '</td></tr>';
                            } else {
                                echo "<!-- DEBUG: Consulta exitosa, filas encontradas: " . $resultado2->num_rows . " -->";
                                
                                if ($resultado2->num_rows == 0) {
                                    echo '<tr><td colspan="9" class="text-center">No hay existencias registradas en la página actual</td></tr>';
                                } else {
                                    while ($row = $resultado2->fetch_assoc()) {
                                        // Extraer datos de forma segura
                                        $existencias = intval($row['existe_qty'] ?? 0);
                                        $maximo = intval($row['item_max'] ?? 0);
                                        $minimo = intval($row['item_min'] ?? 0);
                                        $reordena = intval($row['reorden'] ?? 0);
                                        
                                        // Manejar fecha
                                        $caduca_str = "N/A";
                                        if (!empty($row['existe_caducidad'])) {
                                            $caduca = date_create($row['existe_caducidad']);
                                            if ($caduca) {
                                                $caduca_str = date_format($caduca, "d/m/Y");
                                            }
                                        }
                                        
                                        // Obtener ubicación
                                        $ubicacion = "Sin ubicación";
                                        if (!empty($row['ubicacion_id'])) {
                                            $result3 = $conexion->query("SELECT nombre_ubicacion FROM ubicaciones_almacen WHERE ubicacion_id = " . intval($row['ubicacion_id']));
                                            if ($result3 && $result3->num_rows > 0) {
                                                $row3 = $result3->fetch_assoc();
                                                $ubicacion = $row3['nombre_ubicacion'] ?? 'Sin ubicación';
                                            }
                                        }
                                        
                                        // Nombre completo del medicamento
                                        $nombre_completo = ($row['item_name'] ?? '') . 
                                                         (!empty($row['item_grams']) ? ', ' . $row['item_grams'] : '') . 
                                                         (!empty($row['item_type_desc']) ? ', ' . $row['item_type_desc'] : '');
                                        
                                        // Determinar clase de estado
                                        $status_class = 'status-normal';
                                        if ($existencias >= $maximo) {
                                            $status_class = 'status-high';
                                        } else if ($existencias <= $minimo) {
                                            $status_class = 'status-low';
                                        } else if ($existencias <= $reordena) {
                                            $status_class = 'status-reorder';
                                        }
                                        
                                        // Mostrar fila
                                        echo '<tr>';
                                        echo '<td>' . htmlspecialchars($row['item_code'] ?? '') . '</td>';
                                        echo '<td>' . htmlspecialchars($nombre_completo) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['existe_lote'] ?? '') . '</td>';
                                        echo '<td>' . $caduca_str . '</td>';
                                        echo '<td>' . $maximo . '</td>';
                                        echo '<td>' . $reordena . '</td>';
                                        echo '<td>' . $minimo . '</td>';
                                        echo '<td><span class="' . $status_class . '">' . $existencias . '</span></td>';
                                        echo '<td>' . htmlspecialchars($ubicacion) . '</td>';
                                        echo '</tr>';
                                    }
                                }
                            }
                        } catch (Exception $e) {
                            echo '<tr><td colspan="9" class="text-center text-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Paginación -->
        <?php if (isset($total_paginas) && $total_paginas > 1): ?>
        <div class="d-flex justify-content-center mt-4">
            <nav aria-label="Paginación de existencias">
                <ul class="pagination">
                    <!-- Página anterior -->
                    <?php if ($pagina_actual > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $pagina_actual - 1 ?>" style="color: var(--primary-color);">
                                <i class="fas fa-chevron-left"></i> Anterior
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="page-item disabled">
                            <span class="page-link"><i class="fas fa-chevron-left"></i> Anterior</span>
                        </li>
                    <?php endif; ?>
                    
                    <!-- Números de página -->
                    <?php 
                    $inicio = max(1, $pagina_actual - 2);
                    $fin = min($total_paginas, $pagina_actual + 2);
                    
                    for ($i = $inicio; $i <= $fin; $i++): ?>
                        <?php if ($i == $pagina_actual): ?>
                            <li class="page-item active">
                                <span class="page-link" style="background-color: var(--primary-color); border-color: var(--primary-color);"><?= $i ?></span>
                            </li>
                        <?php else: ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $i ?>" style="color: var(--primary-color);"><?= $i ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <!-- Página siguiente -->
                    <?php if ($pagina_actual < $total_paginas): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $pagina_actual + 1 ?>" style="color: var(--primary-color);">
                                Siguiente <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="page-item disabled">
                            <span class="page-link">Siguiente <i class="fas fa-chevron-right"></i></span>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
        
        <!-- Información de paginación -->
        <div class="text-center mt-2">
            <small class="text-muted">
                Mostrando página <?= $pagina_actual ?> de <?= $total_paginas ?> 
                (<?= $total_registros ?> registros en total, <?= $registros_por_pagina ?> por página)
            </small>
        </div>
        <?php endif; ?>
    </div>

    <footer class="main-footer">
        <?php
        include("../../template/footer.php");
        ?>
    </footer>

    <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- FastClick -->
    <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

</body>
</html>
