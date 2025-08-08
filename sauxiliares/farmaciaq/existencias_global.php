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
    session_destroy();
    session_start();
    echo "<script>alert('Sesión inválida. Por favor, inicie sesión nuevamente.'); window.location='../../index.php';</script>";
    exit();
}

$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];

// Verificar permisos de rol
$roles_permitidos = array(4, 5, 7, 11);
if (!in_array($usuario['id_rol'], $roles_permitidos)) {
    echo "<script>alert('No tiene permisos para acceder a esta página.'); window.location='../../index.php';</script>";
    exit();
}

if (in_array($usuario['id_rol'], [4, 5, 7, 11])) {
    include "../header_farmaciaq.php";
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Existencias Globales Farmacia Central</title>
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
            <h1><i class="fas fa-globe"></i> EXISTENCIAS GLOBALES DE FARMACIA CENTRAL</h1>
        </div>
        
        <!-- Botón de regreso -->
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex justify-content-center mb-4">
                    <a class="btn btn-custom btn-danger-custom" href="existenciasq.php">
                        <i class="fas fa-arrow-left"></i> Regresar
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
                    <input type="text" class="form-control" id="search" placeholder="Buscar medicamento, código...">
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
                            <th><i class="fas fa-arrow-up"></i> Máximo</th>
                            <th><i class="fas fa-exclamation-triangle"></i> P.reorden</th>
                            <th><i class="fas fa-arrow-down"></i> Mínimo</th>
                            <th><i class="fas fa-boxes"></i> Existencias</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            // Consulta mejorada con SUM para obtener existencias globales
                            $resultado2 = $conexion->query("
                                SELECT 
                                    ia.item_id,
                                    ia.item_code,
                                    ia.item_name,
                                    ia.item_grams,
                                    ia.item_max,
                                    ia.item_min,
                                    ia.reorden,
                                    SUM(ea.existe_qty) as total_existencias
                                FROM item_almacen ia 
                                LEFT JOIN existencias_almacen ea ON ia.item_id = ea.item_id 
                                WHERE ea.existe_qty > 0
                                GROUP BY ia.item_id, ia.item_code, ia.item_name, ia.item_grams, ia.item_max, ia.item_min, ia.reorden
                                ORDER BY ia.item_code
                            ");
                            
                            if (!$resultado2) {
                                echo '<tr><td colspan="6" class="text-center text-danger">Error en consulta: ' . htmlspecialchars($conexion->error) . '</td></tr>';
                            } else if ($resultado2->num_rows == 0) {
                                echo '<tr><td colspan="6" class="text-center">No hay existencias registradas</td></tr>';
                            } else {
                                while ($row = $resultado2->fetch_assoc()) {
                                    $existencias = intval($row['total_existencias'] ?? 0);
                                    $maximo = intval($row['item_max'] ?? 0);
                                    $minimo = intval($row['item_min'] ?? 0);
                                    $reordena = intval($row['reorden'] ?? 0);
                                    
                                    // Nombre completo del medicamento
                                    $nombre_completo = ($row['item_name'] ?? '') . 
                                                     (!empty($row['item_grams']) ? ', ' . $row['item_grams'] : '');
                                    
                                    // Determinar clase de estado
                                    $status_class = 'status-normal';
                                    if ($existencias >= $maximo) {
                                        $status_class = 'status-high';
                                    } else if ($existencias <= $minimo) {
                                        $status_class = 'status-low';
                                    } else if ($existencias <= $reordena) {
                                        $status_class = 'status-reorder';
                                    }
                                    
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($row['item_code'] ?? '') . '</td>';
                                    echo '<td>' . htmlspecialchars($nombre_completo) . '</td>';
                                    echo '<td>' . $maximo . '</td>';
                                    echo '<td>' . $reordena . '</td>';
                                    echo '<td>' . $minimo . '</td>';
                                    echo '<td><span class="' . $status_class . '">' . $existencias . '</span></td>';
                                    echo '</tr>';
                                }
                            }
                        } catch (Exception $e) {
                            echo '<tr><td colspan="6" class="text-center text-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
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
