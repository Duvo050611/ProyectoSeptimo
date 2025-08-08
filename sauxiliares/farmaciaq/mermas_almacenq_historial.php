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

    // Consulta para obtener los datos de la tabla `merma_almacenq` con JOIN y filtro de fechas
    $resultado = $conexion->query("
        SELECT 
            m.merma_id,
            m.merma_fecha,
            i.item_code,
            i.item_name,
            m.merma_lote,
            m.merma_caducidad,
            m.merma_qty,
            m.merma_motivo,
            u.nombre_ubicacion,
            m.id_usua
        FROM 
            merma_almacenq m
        JOIN 
            item_almacen i ON m.item_id = i.item_id
        JOIN 
            ubicaciones_almacen u ON m.ubicacion_id = u.ubicacion_id
        WHERE 
            m.merma_fecha >= '$inicial' AND m.merma_fecha <= '$final'
    ") or die($conexion->error);
} else {
    // Consulta sin filtro de fechas si no se han enviado las fechas
    $resultado = $conexion->query("
        SELECT 
            m.merma_id,
            m.merma_fecha,
            i.item_code,
            i.item_name,
            m.merma_lote,
            m.merma_caducidad,
            m.merma_qty,
            m.merma_motivo,
            u.nombre_ubicacion,
            m.id_usua
        FROM 
            merma_almacenq m
        JOIN 
            item_almacen i ON m.item_id = i.item_id
        JOIN 
            ubicaciones_almacen u ON m.ubicacion_id = u.ubicacion_id
        ORDER BY 
            m.merma_fecha DESC
        LIMIT 50
    ") or die($conexion->error);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Mermas - Farmacia Quirófano</title>
    
    <!-- Bootstrap 4.0.0 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    
    <!-- Font Awesome 6.0.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
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
        }
        
        .table tbody td {
            padding: 12px 8px;
            vertical-align: middle;
            font-size: 0.9rem;
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
        
        .badge-motivo {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .motivo-caducado {
            background-color: #dc3545;
            color: white;
        }
        
        .motivo-dañado {
            background-color: #fd7e14;
            color: white;
        }
        
        .motivo-deterioro {
            background-color: #6f42c1;
            color: white;
        }
        
        .motivo-otro {
            background-color: #6c757d;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="page-header">
            <h1><i class="fas fa-exclamation-triangle"></i> HISTORIAL DE MERMAS</h1>
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
                            <input type="date" class="form-control" name="inicial">
                        </div>
                        <div class="col-md-4">
                            <label class="label-custom"><i class="fas fa-calendar-check"></i> Fecha Final:</label>
                            <input type="date" class="form-control" name="final">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-custom btn-success-custom" style="width: 100%;">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        <?php if ($resultado->num_rows > 0): ?>
            <!-- Tabla de resultados -->
            <div class="table-container">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID Merma</th>
                            <th><i class="fas fa-calendar"></i> Fecha</th>
                            <th><i class="fas fa-barcode"></i> Código Item</th>
                            <th><i class="fas fa-pills"></i> Nombre Item</th>
                            <th><i class="fas fa-flask"></i> Lote</th>
                            <th><i class="fas fa-calendar-times"></i> Caducidad</th>
                            <th><i class="fas fa-box"></i> Cantidad</th>
                            <th><i class="fas fa-exclamation-circle"></i> Motivo</th>
                            <th><i class="fas fa-map-marker-alt"></i> Ubicación</th>
                            <th><i class="fas fa-user"></i> Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['merma_id']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['merma_fecha'])); ?></td>
                                <td><?php echo $row['item_code']; ?></td>
                                <td><?php echo $row['item_name']; ?></td>
                                <td><?php echo $row['merma_lote']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['merma_caducidad'])); ?></td>
                                <td><strong><?php echo $row['merma_qty']; ?></strong></td>
                                <td>
                                    <?php 
                                    $motivo = strtolower($row['merma_motivo']);
                                    $clase_motivo = 'motivo-otro';
                                    if (strpos($motivo, 'caducado') !== false || strpos($motivo, 'vencido') !== false) {
                                        $clase_motivo = 'motivo-caducado';
                                    } elseif (strpos($motivo, 'dañado') !== false || strpos($motivo, 'roto') !== false) {
                                        $clase_motivo = 'motivo-dañado';
                                    } elseif (strpos($motivo, 'deterioro') !== false) {
                                        $clase_motivo = 'motivo-deterioro';
                                    }
                                    ?>
                                    <span class="badge-motivo <?php echo $clase_motivo; ?>">
                                        <?php echo $row['merma_motivo']; ?>
                                    </span>
                                </td>
                                <td><?php echo $row['nombre_ubicacion']; ?></td>
                                <td><?php echo $row['id_usua']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="no-data-message">
                <i class="fas fa-inbox fa-3x mb-3" style="color: #6c757d;"></i>
                <h4>No se encontraron registros</h4>
                <p>No hay mermas registradas en el período seleccionado.</p>
            </div>
        <?php endif; ?>
        </div>
    </div>
</body>

</html>