<?php
session_start();
include "../../conexionbd.php";

// Iniciar el buffer de salida para prevenir errores de encabezado
ob_start();

$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];

if (isset($usuario['id_rol'])) {
    if ($usuario['id_rol'] == 11 || $usuario['id_rol'] == 4 || $usuario['id_rol'] == 5) {
        include "../header_farmaciaq.php";
    } else {
        // Si el usuario no tiene un rol permitido, destruir la sesión y redirigir
        session_unset();
        session_destroy();
        echo "<script>window.location='../../index.php';</script>";
        exit();
    }
}
// Verificar si se han enviado las fechas inicial y final
if (isset($_POST['inicial']) && isset($_POST['final'])) {
    $inicial = mysqli_real_escape_string($conexion, $_POST['inicial']);
    $final = mysqli_real_escape_string($conexion, $_POST['final']);

    // Añadir un día a la fecha final para incluirla en el filtro
    $final = date("Y-m-d H:i:s", strtotime($final . " + 1 day"));

    // Consulta para obtener los datos de la tabla `entradas_almacenq` con JOIN y filtro de fechas
    $resultado = $conexion->query("
        SELECT 
            e.entrada_id, 
            e.entrada_fecha, 
           
            i.item_name, 
            e.entrada_lote, 
            e.entrada_caducidad, 
            e.entrada_qty, 
            e.entrada_unidosis, 
            e.entrada_costo, 
            
          
            e.id_usua, 
            u.nombre_ubicacion 
        FROM 
            entradas_almacenq e
        JOIN 
            item_almacen i ON e.item_id = i.item_id
        JOIN 
            ubicaciones_almacen u ON e.ubicacion_id = u.ubicacion_id
        WHERE 
            e.entrada_fecha >= '$inicial' AND e.entrada_fecha <= '$final'
    ") or die($conexion->error);
} else {
    // Consulta sin filtro de fechas si no se han enviado las fechas
    $resultado = $conexion->query("
            SELECT 
                e.entrada_id, 
                e.entrada_fecha, 
                i.item_name, 
                e.entrada_lote, 
                e.entrada_caducidad, 
                e.entrada_qty, 
                e.entrada_unidosis, 
                e.entrada_costo, 
                e.id_usua, 
                u.nombre_ubicacion 
            FROM 
                entradas_almacenq e
            JOIN 
                item_almacen i ON e.item_id = i.item_id
            JOIN 
                ubicaciones_almacen u ON e.ubicacion_id = u.ubicacion_id
            ORDER BY 
                e.entrada_fecha DESC -- Ordenar por fecha descendente (más reciente primero)
            LIMIT 50 -- Mostrar los últimos 50 registros (puedes ajustar el límite)
            ") or die($conexion->error);
}






?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Resurtimiento</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
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
        
        .form-container {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .form-control {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 10px 15px;
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(43,45,127,0.25);
            border-color: var(--primary-light);
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
            padding: 15px 10px;
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
        }
        
        .table tbody tr:hover {
            background-color: rgba(43,45,127,0.1);
        }
        
        .table tbody td {
            padding: 12px 10px;
            vertical-align: middle;
        }
        
        .container-main {
            max-width: 98%;
            margin: 0 auto;
        }
        
        .label-custom {
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 8px;
        }
        
        .no-data-message {
            background: white;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="page-header">
            <h1><i class="fas fa-arrow-down"></i> HISTORIAL DE RESURTIMIENTO</h1>
        </div>
        
        <!-- Botón de regreso -->
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex justify-content-center mb-3">
                    <a href='kardexq.php' class="btn btn-custom btn-danger-custom">
                        <i class="fas fa-arrow-left"></i> Regresar
                    </a>
                </div>
            </div>
        </div>

        <div class="container-main">
            <!-- Formulario de filtros -->
            <div class="form-container">
                <form method="POST" action="">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label class="label-custom"><i class="fas fa-calendar-alt"></i> Fecha Inicial:</label>
                            <input type="date" class="form-control" name="inicial" required>
                        </div>
                        <div class="col-md-4">
                            <label class="label-custom"><i class="fas fa-calendar-check"></i> Fecha Final:</label>
                            <input type="date" class="form-control" name="final" required>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-custom btn-success-custom" style="width: 100%;">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabla de resultados -->
            <?php if ($resultado->num_rows > 0): ?>
                <div class="table-container">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <th><i class="fas fa-calendar"></i> FECHA</th>
                                <th><i class="fas fa-pills"></i> NOMBRE ITEM</th>
                                <th><i class="fas fa-tag"></i> LOTE</th>
                                <th><i class="fas fa-calendar-times"></i> CADUCIDAD</th>
                                <th><i class="fas fa-capsules"></i> UNIDOSIS</th>
                                <th><i class="fas fa-dollar-sign"></i> COSTO</th>
                                <th><i class="fas fa-user"></i> ID USUARIO</th>
                                <th><i class="fas fa-map-marker-alt"></i> UBICACIÓN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $resultado->fetch_assoc()): ?>
                                <tr>
                                    <td class="disabled-field"><?php echo $row['entrada_id']; ?></td>
                                    <td class="disabled-field"><?php echo date('d/m/Y', strtotime($row['entrada_fecha'])); ?></td>
                                    <td class="disabled-field"><strong><?php echo $row['item_name']; ?></strong></td>
                                    <td class="disabled-field"><?php echo $row['entrada_lote']; ?></td>
                                    <td class="disabled-field"><?php echo date('d/m/Y', strtotime($row['entrada_caducidad'])); ?></td>
                                    <td class="disabled-field text-center"><?php echo $row['entrada_unidosis']; ?></td>
                                    <td class="disabled-field text-right">$<?php echo number_format($row['entrada_costo'], 2); ?></td>
                                    <td class="disabled-field text-center"><?php echo $row['id_usua']; ?></td>
                                    <td class="disabled-field"><?php echo $row['nombre_ubicacion']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-data-message">
                    <i class="fas fa-info-circle fa-3x mb-3" style="color: var(--primary-color);"></i>
                    <h4>No se encontraron registros</h4>
                    <p>No se encontraron registros en el rango de fechas especificado.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <footer class="main-footer mt-4">
        <?php include("../../template/footer.php"); ?>
    </footer>

    <!-- Scripts -->
    <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- FastClick -->
    <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
</body>
</html>


