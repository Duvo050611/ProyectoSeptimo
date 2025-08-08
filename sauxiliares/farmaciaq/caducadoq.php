<?php
session_start();
include "../../conexionbd.php";

$usuario = $_SESSION['login'];

if ($usuario['id_rol'] == 11 || $usuario['id_rol'] == 4 || $usuario['id_rol'] == 5) {
    include "../header_farmaciaq.php";
} else {
    echo "<script>window.location='../../index.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Caducidades - Farmacia Quirófano</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    
    <!-- Font Awesome 6.0.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- jQuery y Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <style>
        :root {
            --primary-color: #2b2d7f;
            --primary-dark: #1e1f5a;
            --primary-light: #4a4db8;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --orange-color: #fd7e14;
            --success-color: #28a745;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 20px 0;
        }
        
        .btn-custom {
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
        
        .btn-danger-custom {
            background: linear-gradient(45deg, #dc3545, #c82333);
            color: white;
        }
        
        .btn-danger-custom:hover {
            background: linear-gradient(45deg, #c82333, #bd2130);
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
        
        .search-input {
            border-radius: 25px;
            border: 2px solid #e9ecef;
            padding: 12px 20px 12px 50px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(43,45,127,0.25);
            border-color: var(--primary-light);
        }
        
        .search-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        
        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            max-height: 70vh;
            overflow-y: auto;
        }
        
        .table thead th {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            padding: 15px 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .table tbody td {
            padding: 12px 8px;
            vertical-align: middle;
            font-size: 0.9rem;
            border: none;
        }
        
        /* Alertas de caducidad con gradientes */
        .danger-row {
            background: linear-gradient(90deg, #ff6b6b 0%, #ff5252 100%) !important;
            color: white;
            font-weight: 600;
        }
        
        .warning-row {
            background: linear-gradient(90deg, #fff176 0%, #ffeb3b 100%) !important;
            color: #333;
            font-weight: 500;
        }
        
        .orange-row {
            background: linear-gradient(90deg, #ffb74d 0%, #ff9800 100%) !important;
            color: white;
            font-weight: 500;
        }
        
        .danger-row:hover {
            background: linear-gradient(90deg, #ff5252 0%, #f44336 100%) !important;
        }
        
        .warning-row:hover {
            background: linear-gradient(90deg, #ffeb3b 0%, #fdd835 100%) !important;
        }
        
        .orange-row:hover {
            background: linear-gradient(90deg, #ff9800 0%, #f57c00 100%) !important;
        }
        
        .container-main {
            max-width: 98%;
            margin: 0 auto;
        }
        
        .alert-legend {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .legend-item {
            display: inline-flex;
            align-items: center;
            margin-right: 20px;
            margin-bottom: 10px;
        }
        
        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 8px;
        }
        
        .legend-danger { background: linear-gradient(45deg, #ff6b6b, #ff5252); }
        .legend-warning { background: linear-gradient(45deg, #fff176, #ffeb3b); }
        .legend-orange { background: linear-gradient(45deg, #ffb74d, #ff9800); }
        
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
        
        .stat-danger { background: linear-gradient(45deg, #ff6b6b, #ff5252); color: white; }
        .stat-warning { background: linear-gradient(45deg, #fff176, #ffeb3b); color: #333; }
        .stat-orange { background: linear-gradient(45deg, #ffb74d, #ff9800); color: white; }
    </style>

    <script>
        $(document).ready(function() {
            // Función de búsqueda mejorada
            $("#search").keyup(function() {
                var _this = this;
                $.each($("#mytable tbody tr"), function() {
                    if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
                        $(this).hide();
                    else
                        $(this).show();
                });
                
                // Actualizar estadísticas después de filtrar
                updateStats();
            });
            
            // Función para actualizar estadísticas
            function updateStats() {
                var totalVisible = $("#mytable tbody tr:visible").length;
                var dangerVisible = $("#mytable tbody tr.danger-row:visible").length;
                var warningVisible = $("#mytable tbody tr.warning-row:visible").length;
                var orangeVisible = $("#mytable tbody tr.orange-row:visible").length;
                
                $("#stat-total").text(totalVisible);
                $("#stat-danger").text(dangerVisible);
                $("#stat-warning").text(warningVisible);
                $("#stat-orange").text(orangeVisible);
            }
            
            // Inicializar estadísticas
            updateStats();
        });
    </script>
</head>

<body>

<div class="container-fluid">
    <div class="container-main">
        <!-- Encabezado -->
        <div class="page-header">
            <h1><i class="fas fa-clock"></i> CONTROL DE CADUCIDADES</h1>
        </div>

        <!-- Botón de regreso -->
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex justify-content-center mb-3">
                    <a class="btn btn-custom btn-danger-custom" href="../../template/menu_farmaciaq.php">
                        <i class="fas fa-arrow-left"></i> Regresar
                    </a>
                </div>
            </div>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="stats-container">
            <h5><i class="fas fa-chart-bar"></i> Resumen de Alertas</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-card stat-danger">
                        <h3 id="stat-danger">0</h3>
                        <small>Crítico (&lt;30 días)</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card stat-warning">
                        <h3 id="stat-warning">0</h3>
                        <small>Advertencia (31-60 días)</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card stat-orange">
                        <h3 id="stat-orange">0</h3>
                        <small>Precaución (61-90 días)</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card" style="background: linear-gradient(45deg, var(--primary-color), var(--primary-dark)); color: white;">
                        <h3 id="stat-total">0</h3>
                        <small>Total Productos</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leyenda de colores -->
        <div class="alert-legend">
            <h5><i class="fas fa-info-circle"></i> Leyenda de Alertas</h5>
            <div class="row">
                <div class="col-12">
                    <div class="legend-item">
                        <div class="legend-color legend-danger"></div>
                        <span><strong>Crítico:</strong> Menos de 30 días para caducar</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color legend-warning"></div>
                        <span><strong>Advertencia:</strong> Entre 31 y 60 días para caducar</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color legend-orange"></div>
                        <span><strong>Precaución:</strong> Entre 61 y 90 días para caducar</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buscador -->
        <div class="search-container">
            <div class="position-relative">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="form-control search-input" id="search" placeholder="Buscar por código, descripción, lote...">
            </div>
        </div>

        <!-- Tabla de productos -->
        <div class="table-container">
            <table class="table table-striped table-hover" id="mytable">
                <thead>
                    <tr>
                        <th><i class="fas fa-barcode"></i> Código</th>
                        <th><i class="fas fa-pills"></i> Descripción</th>
                        <th><i class="fas fa-capsules"></i> Presentación</th>
                        <th><i class="fas fa-flask"></i> Lote</th>
                        <th><i class="fas fa-calendar-times"></i> Caducidad</th>
                        <th><i class="fas fa-arrow-down"></i> Entradas</th>
                        <th><i class="fas fa-arrow-up"></i> Salidas</th>
                        <th><i class="fas fa-undo"></i> Devoluciones</th>
                        <th><i class="fas fa-box"></i> Existencias</th>
                        <th><i class="fas fa-chart-line"></i> Máximo</th>
                        <th><i class="fas fa-exclamation-triangle"></i> Punto de reorden</th>
                        <th><i class="fas fa-chart-line-down"></i> Mínimo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $resultado2 = $conexion->query("SELECT * FROM item_almacen, existencias_almacenq, item_type WHERE item_type.item_type_id=item_almacen.item_type_id AND item_almacen.item_id = existencias_almacenq.item_id ORDER BY item_almacen.item_id") or die($conexion->error);

                    while ($row = $resultado2->fetch_assoc()) {
                        $caducidad = date_create($row['existe_caducidad']);
                        $hoy = new DateTime();
                        $diferencia = $hoy->diff($caducidad);
                        $diasRestantes = $diferencia->days;

                        // Asignar clase de color según los días restantes
                        $colorClase = '';
                        $iconoAlerta = '';
                        if ($diasRestantes < 30) {
                            $colorClase = 'danger-row';
                            $iconoAlerta = '<i class="fas fa-exclamation-triangle"></i>';
                        } elseif ($diasRestantes >= 31 && $diasRestantes <= 60) {
                            $colorClase = 'warning-row';
                            $iconoAlerta = '<i class="fas fa-exclamation-circle"></i>';
                        } elseif ($diasRestantes >= 61 && $diasRestantes <= 90) {
                            $colorClase = 'orange-row';
                            $iconoAlerta = '<i class="fas fa-clock"></i>';
                        } else {
                            continue; // No mostrar elementos con más de 90 días
                        }

                        // Generar la fila de la tabla
                        echo '<tr class="' . $colorClase . '">'
                            . '<td><strong>' . $row['item_code'] . '</strong></td>'
                            . '<td>' . $row['item_name'] . ', ' . $row['item_grams'] . '</td>'
                            . '<td>' . $row['item_type_desc'] . '</td>'
                            . '<td><span class="badge badge-secondary">' . $row['existe_lote'] . '</span></td>'
                            . '<td>' . $iconoAlerta . ' ' . date_format($caducidad, "d/m/Y") . ' <small>(' . $diasRestantes . ' días)</small></td>'
                            . '<td>' . $row['existe_entradas'] . '</td>'
                            . '<td>' . $row['existe_salidas'] . '</td>'
                            . '<td>' . $row['existe_devoluciones'] . '</td>'
                            . '<td><strong>' . $row['existe_qty'] . '</strong></td>'
                            . '<td>' . $row['item_max'] . '</td>'
                            . '<td>' . $row['reorden'] . '</td>'
                            . '<td>' . $row['item_min'] . '</td>'
                            . '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<footer class="main-footer">
    <?php include("../../template/footer.php"); ?>
</footer>

<script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

</body>
</html>
