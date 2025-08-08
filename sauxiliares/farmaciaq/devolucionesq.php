<?php
session_start();
include "../../conexionbd.php";

// Verifica si la sesión está activa
if (!isset($_SESSION['login'])) {
echo "<script>
    window.location = '../../index.php';
</script>";
exit;
}

$id_usua = $_SESSION['login']['id_usua']; // Obtiene el ID del usuario en sesión
include "../header_farmaciaq.php";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devoluciones Pendientes - Farmacia Quirófano</title>
    
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
        
        .btn-success-custom {
            background: linear-gradient(45deg, #28a745, #1e7e34);
            color: white;
        }
        
        .btn-success-custom:hover {
            background: linear-gradient(45deg, #1e7e34, #155724);
            color: white;
        }
        
        .btn-warning-custom {
            background: linear-gradient(45deg, #ffc107, #e0a800);
            color: #333;
        }
        
        .btn-warning-custom:hover {
            background: linear-gradient(45deg, #e0a800, #d39e00);
            color: #333;
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
        
        .actions-container {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
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
            background-color: rgba(43,45,127,0.1);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .table tbody td {
            padding: 12px 8px;
            vertical-align: middle;
            font-size: 0.9rem;
            border: none;
        }
        
        .container-main {
            max-width: 98%;
            margin: 0 auto;
        }
        
        .no-data-message {
            background: white;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            color: #6c757d;
        }
        
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            border-bottom: none;
        }
        
        .modal-body {
            padding: 30px;
        }
        
        .modal-footer {
            border-top: 1px solid #e9ecef;
            padding: 20px 30px;
        }
        
        .badge-pending {
            background: linear-gradient(45deg, #ffc107, #e0a800);
            color: #333;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
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
        
        .action-buttons {
            display: flex;
            gap: 5px;
            justify-content: center;
        }
        
        .action-buttons .btn {
            padding: 5px 10px;
            font-size: 0.8rem;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="container-main">
            <!-- Encabezado -->
            <div class="page-header">
                <h1><i class="fas fa-undo-alt"></i> DEVOLUCIONES PENDIENTES</h1>
            </div>

            <!-- Estadísticas rápidas -->
            <div class="stats-container">
                <h5><i class="fas fa-chart-bar"></i> Resumen de Devoluciones</h5>
                <div class="row">
                    <div class="col-md-4">
                        <div class="stat-card" style="background: linear-gradient(45deg, var(--primary-color), var(--primary-dark)); color: white;">
                            <h3 id="stat-total">0</h3>
                            <small>Total Pendientes</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card" style="background: linear-gradient(45deg, #28a745, #1e7e34); color: white;">
                            <h3 id="stat-inventory">0</h3>
                            <small>Para Inventario</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card" style="background: linear-gradient(45deg, #dc3545, #c82333); color: white;">
                            <h3 id="stat-waste">0</h3>
                            <small>Para Merma</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones principales -->
            <div class="actions-container">
                <h5><i class="fas fa-tools"></i> Acciones</h5>
                <div class="row">
                    <div class="col-md-3">
                        <a href="../../template/menu_farmaciaq.php" class="btn btn-custom btn-danger-custom btn-block">
                            <i class="fas fa-arrow-left"></i> Regresar
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="pdf_devoluciones.php" class="btn btn-custom btn-warning-custom btn-block" target="_blank">
                            <i class="fas fa-file-pdf"></i> Imprimir Reporte
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="exceldevoluciones.php" class="btn btn-custom btn-success-custom btn-block">
                            <i class="fas fa-file-excel"></i> Exportar Excel
                        </a>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-custom btn-block" style="background: linear-gradient(45deg, var(--primary-color), var(--primary-dark)); color: white;" onclick="refreshTable()">
                            <i class="fas fa-sync-alt"></i> Actualizar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Buscador -->
            <div class="search-container">
                <div class="position-relative">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="form-control search-input" id="search" placeholder="Buscar por ID, nombre, código, lote...">
                </div>
            </div>
            <!-- Tabla de devoluciones -->
            <div class="table-container">
                <table class="table table-striped table-hover" id="devolucionesTable">
                    <thead>
                        <tr>
                            <th><i class="fas fa-user"></i> ID Paciente</th>
                            <th><i class="fas fa-arrow-up"></i> Salida</th>
                            <th><i class="fas fa-pills"></i> Nombre</th>
                            <th><i class="fas fa-hashtag"></i> ID Devolución</th>
                            <th><i class="fas fa-calendar"></i> Fecha</th>
                            <th><i class="fas fa-tag"></i> ID Ítem</th>
                            <th><i class="fas fa-barcode"></i> Código</th>
                            <th><i class="fas fa-box"></i> Cantidad Devuelta</th>
                            <th><i class="fas fa-warehouse"></i> Cantidad Inventario</th>
                            <th><i class="fas fa-exclamation-triangle"></i> Cantidad Merma</th>
                            <th><i class="fas fa-clipboard-list"></i> Motivo Inventario</th>
                            <th><i class="fas fa-times-circle"></i> Motivo Merma</th>
                            <th><i class="fas fa-flask"></i> Lote</th>
                            <th><i class="fas fa-calendar-times"></i> Caducidad</th>
                            <th><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $resultado = $conexion->query("SELECT d.*, i.* FROM devoluciones_almacenq d JOIN item_almacen i ON d.item_id = i.item_id");

                        if ($resultado && $resultado->num_rows > 0) {
                            while ($row = $resultado->fetch_assoc()) {
                                // Validación: si dev_qty es igual a cero, omitir esta fila
                                if ($row['dev_qty'] == 0) {
                                    continue; // Salta a la siguiente iteración
                                }

                                $fecha = date_create($row['fecha']);
                                $fecha = date_format($fecha, 'd/m/Y H:i');

                                echo "<tr>";
                                echo "<td><strong>" . $row['id_atencion'] . "</strong></td>";
                                echo "<td><span class='badge badge-secondary'>" . $row['salida_id'] . "</span></td>";
                                echo "<td>" . $row['item_name'] . "</td>";
                                echo "<td><strong>" . $row['dev_id'] . "</strong></td>";
                                echo "<td>" . $fecha . "</td>";
                                echo "<td>" . $row['item_id'] . "</td>";
                                echo "<td><code>" . $row['item_code'] . "</code></td>";
                                echo "<td><strong class='text-warning'>" . $row['dev_qty'] . "</strong></td>";
                                echo "<td>" . ($row['cant_inv'] ?: '-') . "</td>";
                                echo "<td>" . ($row['cant_mer'] ?: '-') . "</td>";
                                echo "<td>" . ($row['motivoi'] ?: '-') . "</td>";
                                echo "<td>" . ($row['motivom'] ?: '-') . "</td>";
                                echo "<td><span class='badge badge-info'>" . $row['existe_lote'] . "</span></td>";
                                echo "<td>" . date('d/m/Y', strtotime($row['existe_caducidad'])) . "</td>";
                                echo "<td>";
                                echo "<div class='action-buttons'>";
                                echo "<button class='btn btn-success btn-sm' data-toggle='modal' data-target='#inventarioModal" . $row['dev_id'] . "' title='Marcar para Inventario'><i class='fas fa-check'></i></button>";
                                echo "<button class='btn btn-danger btn-sm' data-toggle='modal' data-target='#mermaModal" . $row['dev_id'] . "' title='Marcar para Merma'><i class='fas fa-times'></i></button>";
                                echo "</div>";
                                echo "</td>";
                                echo "</tr>";

                                // Modal para inventario
                                echo "<div class='modal fade' id='inventarioModal" . $row['dev_id'] . "' tabindex='-1' aria-labelledby='inventarioLabel' aria-hidden='true'>
                        <div class='modal-dialog modal-dialog-centered'>
                            <form action='valida_dev.php' method='POST'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title'><i class='fas fa-check-circle'></i> Confirmar para Inventario</h5>
                                        <button type='button' class='close text-white' data-dismiss='modal'>&times;</button>
                                    </div>
                                    <div class='modal-body'>
                                        <div class='alert alert-info'>
                                            <i class='fas fa-info-circle'></i> 
                                            <strong>Producto:</strong> " . $row['item_name'] . "<br>
                                            <strong>Cantidad a devolver:</strong> " . $row['dev_qty'] . "
                                        </div>
                                        <input type='hidden' name='id_dev' value='" . $row['dev_id'] . "'>
                                        <input type='hidden' name='item_id' value='" . $row['item_id'] . "'>
                                        <input type='hidden' name='dev_qty' value='" . $row['dev_qty'] . "'>
                                        <input type='hidden' name='existe_lote' value='" . $row['existe_lote'] . "'>
                                        <input type='hidden' name='existe_caducidad' value='" . $row['existe_caducidad'] . "'>
                                        <input type='hidden' name='id_usua' value='" . $row['id_usua'] . "'>
                                        <div class='form-group'>
                                            <label class='font-weight-bold'><i class='fas fa-sort-numeric-up'></i> Cantidad:</label>
                                            <input type='number' name='cant_inv' class='form-control' min='1' max='" . $row['dev_qty'] . "' required>
                                            <small class='form-text text-muted'>Máximo: " . $row['dev_qty'] . "</small>
                                        </div>
                                        <div class='form-group'>
                                            <label class='font-weight-bold'><i class='fas fa-comment'></i> Motivo:</label>
                                            <input type='text' name='motivoi' class='form-control' placeholder='Describe el motivo...' required>
                                        </div>
                                    </div>
                                    <div class='modal-footer'>
                                        <button type='button' class='btn btn-secondary' data-dismiss='modal'>
                                            <i class='fas fa-times'></i> Cancelar
                                        </button>
                                        <button type='submit' class='btn btn-success'>
                                            <i class='fas fa-check'></i> Confirmar para Inventario
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>";

                                // Modal para merma
                                echo "<div class='modal fade' id='mermaModal" . $row['dev_id'] . "' tabindex='-1' aria-labelledby='mermaLabel' aria-hidden='true'>
                        <div class='modal-dialog modal-dialog-centered'>
                            <form action='registrar_merma.php' method='POST'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title'><i class='fas fa-exclamation-triangle'></i> Confirmar para Merma</h5>
                                        <button type='button' class='close text-white' data-dismiss='modal'>&times;</button>
                                    </div>
                                    <div class='modal-body'>
                                        <div class='alert alert-warning'>
                                            <i class='fas fa-exclamation-triangle'></i> 
                                            <strong>Producto:</strong> " . $row['item_name'] . "<br>
                                            <strong>Cantidad a procesar:</strong> " . $row['dev_qty'] . "
                                        </div>
                                        <input type='hidden' name='id_dev' value='" . $row['dev_id'] . "'>
                                        <input type='hidden' name='item_id' value='" . $row['item_id'] . "'>
                                        <input type='hidden' name='dev_qty' value='" . $row['dev_qty'] . "'>
                                        <input type='hidden' name='existe_lote' value='" . $row['existe_lote'] . "'>
                                        <input type='hidden' name='existe_caducidad' value='" . $row['existe_caducidad'] . "'>
                                        <input type='hidden' name='id_usua' value='" . $row['id_usua'] . "'>
                                        <div class='form-group'>
                                            <label class='font-weight-bold'><i class='fas fa-sort-numeric-up'></i> Cantidad:</label>
                                            <input type='number' name='merma_qty' class='form-control' min='1' max='" . $row['dev_qty'] . "' required>
                                            <small class='form-text text-muted'>Máximo: " . $row['dev_qty'] . "</small>
                                        </div>
                                        <div class='form-group'>
                                            <label class='font-weight-bold'><i class='fas fa-comment'></i> Motivo de la merma:</label>
                                            <select name='merma_motivo' class='form-control' required>
                                                <option value=''>Seleccionar motivo...</option>
                                                <option value='Producto dañado'>Producto dañado</option>
                                                <option value='Producto caducado'>Producto caducado</option>
                                                <option value='Envase roto'>Envase roto</option>
                                                <option value='Contaminación'>Contaminación</option>
                                                <option value='Deterioro'>Deterioro</option>
                                                <option value='Otro'>Otro</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class='modal-footer'>
                                        <button type='button' class='btn btn-secondary' data-dismiss='modal'>
                                            <i class='fas fa-times'></i> Cancelar
                                        </button>
                                        <button type='submit' class='btn btn-danger'>
                                            <i class='fas fa-exclamation-triangle'></i> Confirmar Merma
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>";
                            }
                        } else {
                            echo "<tr><td colspan='15' class='text-center'>";
                            echo "<div class='no-data-message'>";
                            echo "<i class='fas fa-inbox fa-3x mb-3' style='color: #6c757d;'></i>";
                            echo "<h4>No hay devoluciones pendientes</h4>";
                            echo "<p>Todas las devoluciones han sido procesadas.</p>";
                            echo "</div>";
                            echo "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Función de búsqueda mejorada
            $("#search").keyup(function() {
                var _this = this;
                $.each($("#devolucionesTable tbody tr"), function() {
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
                var totalVisible = $("#devolucionesTable tbody tr:visible").length;
                var inventoryItems = 0;
                var wasteItems = 0;
                
                $("#devolucionesTable tbody tr:visible").each(function() {
                    var row = $(this);
                    // Contar elementos que pueden ir a inventario o merma
                    inventoryItems++;
                    wasteItems++;
                });
                
                $("#stat-total").text(totalVisible);
                $("#stat-inventory").text(inventoryItems);
                $("#stat-waste").text(wasteItems);
            }
            
            // Función para actualizar la tabla
            function refreshTable() {
                location.reload();
            }
            
            // Hacer la función global
            window.refreshTable = refreshTable;
            
            // Inicializar estadísticas
            updateStats();
        });
    </script>
</body>

</html>