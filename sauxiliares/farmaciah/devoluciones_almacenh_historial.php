<?php
session_start();
include "../../conexionbd.php";

// Iniciar el buffer de salida para prevenir errores de encabezado
ob_start();

$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];

if (isset($usuario['id_rol'])) {
    if ($usuario['id_rol'] == 11 || $usuario['id_rol'] == 4 || $usuario['id_rol'] == 5) {
        include "../header_farmaciah.php";
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

    // Consulta para obtener los datos de la tabla `devoluciones_almacenh` con JOIN y filtro de fechas
    $resultado = $conexion->query("
        SELECT 
            d.dev_id,
            d.fecha,
            d.item_code,
            i.item_name,
            d.existe_lote,
            d.existe_caducidad,
            d.dev_qty,
            d.cant_inv,
            d.cant_mer,
            d.motivoi,
            d.motivom,
            d.dev_estatus,
            u.nombre_ubicacion,
            d.id_usua
        FROM 
            devoluciones_almacenh d
        JOIN 
            item_almacen i ON d.item_id = i.item_id
        JOIN 
            ubicaciones_almacen u ON d.id_usua = u.ubicacion_id
        WHERE 
            d.fecha >= '$inicial' AND d.fecha <= '$final'
    ") or die($conexion->error);
} else {
    // Consulta sin filtro de fechas si no se han enviado las fechas
    $resultado = $conexion->query("
        SELECT 
            d.dev_id,
            d.fecha,
            d.item_code,
            i.item_name,
            d.existe_lote,
            d.existe_caducidad,
            d.dev_qty,
            d.cant_inv,
            d.cant_mer,
            d.motivoi,
            d.motivom,
            d.dev_estatus,
            u.nombre_ubicacion,
            d.id_usua
        FROM 
            devoluciones_almacenh d
        JOIN 
            item_almacen i ON d.item_id = i.item_id
        JOIN 
            ubicaciones_almacen u ON d.id_usua = u.ubicacion_id
        ORDER BY 
            d.fecha DESC
        LIMIT 50
    ") or die($conexion->error);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Devoluciones - Farmacia Hospitalaria</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    
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

        .titulo-filtros {
            color: var(--color-primario);
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-control {
            border: 2px solid var(--color-borde);
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
            font-size: 14px;
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
            margin: 30px 0;
        }

        .tabla-moderna {
            margin: 0;
            font-size: 12px;
            width: 100%;
        }

        .tabla-moderna thead th {
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

        .tabla-moderna thead th i {
            margin-right: 5px;
        }

        .tabla-moderna tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f1f3f4;
        }

        .tabla-moderna tbody tr:hover {
            background-color: var(--color-fondo);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .tabla-moderna tbody td {
            padding: 12px 10px;
            vertical-align: middle;
            border: none;
            text-align: center;
            white-space: nowrap;
            font-size: 11px;
        }

        /* ===== BADGES Y ESTADOS ===== */
        .badge-estado {
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-activo {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .badge-inactivo {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }

        .badge-pendiente {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #212529;
        }

        .badge-fecha {
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            color: white;
            padding: 4px 8px;
            border-radius: 8px;
            font-size: 10px;
        }

        .badge-cantidad {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            padding: 4px 8px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 600;
        }

        /* ===== MENSAJE SIN RESULTADOS ===== */
        .mensaje-sin-resultados {
            text-align: center;
            padding: 60px 20px;
            color: var(--color-primario);
            font-size: 18px;
            font-weight: 600;
        }

        .mensaje-sin-resultados i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.5;
            display: block;
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

            .tabla-moderna {
                font-size: 10px;
            }

            .tabla-moderna thead th,
            .tabla-moderna tbody td {
                padding: 8px 6px;
            }

            .tabla-contenedor {
                overflow-x: auto;
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

        /* ===== SCROLL PERSONALIZADO ===== */
        .tabla-contenedor::-webkit-scrollbar {
            height: 8px;
        }

        .tabla-contenedor::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .tabla-contenedor::-webkit-scrollbar-thumb {
            background: var(--color-primario);
            border-radius: 10px;
        }

        .tabla-contenedor::-webkit-scrollbar-thumb:hover {
            background: var(--color-secundario);
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
                    <i class="fas fa-undo-alt icono-principal"></i>
                    <h1>HISTORIAL DE DEVOLUCIONES - FARMACIA HOSPITALARIA</h1>
                </div>
            </div>

            <!-- Formulario de filtros modernizado -->
            <div class="contenedor-filtros">
                <div class="titulo-filtros">
                    <i class="fas fa-filter"></i> Filtros de Búsqueda
                </div>
                
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-calendar-alt"></i> Fecha Inicial:
                                </label>
                                <input type="date" class="form-control" name="inicial" 
                                       value="<?= isset($_POST['inicial']) ? $_POST['inicial'] : date('Y-m-01') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-calendar-alt"></i> Fecha Final:
                                </label>
                                <input type="date" class="form-control" name="final" 
                                       value="<?= isset($_POST['final']) ? $_POST['final'] : date('Y-m-d') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group d-flex align-items-end">
                                <button type="submit" class="btn-moderno btn-filtrar w-100">
                                    <i class="fas fa-search"></i> Filtrar Resultados
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabla de resultados modernizada -->
            <?php if ($resultado->num_rows > 0): ?>
                <div class="tabla-contenedor">
                    <table class="tabla-moderna table table-striped">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <th><i class="fas fa-calendar"></i> Fecha</th>
                                <th><i class="fas fa-barcode"></i> Código</th>
                                <th><i class="fas fa-pills"></i> Medicamento</th>
                                <th><i class="fas fa-tag"></i> Lote</th>
                                <th><i class="fas fa-calendar-check"></i> Caducidad</th>
                                <th><i class="fas fa-undo"></i> Cantidad Devuelta</th>
                                <th><i class="fas fa-warehouse"></i> Cantidad Inventario</th>
                                <th><i class="fas fa-times-circle"></i> Cantidad Merma</th>
                                <th><i class="fas fa-comment"></i> Motivo Interno</th>
                                <th><i class="fas fa-exclamation-triangle"></i> Motivo Merma</th>
                                <th><i class="fas fa-info-circle"></i> Estatus</th>
                                <th><i class="fas fa-map-marker-alt"></i> Ubicación</th>
                                <th><i class="fas fa-user"></i> Usuario</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $resultado->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <span class="badge badge-fecha"><?= $row['dev_id'] ?></span>
                                    </td>
                                    <td>
                                        <span class="badge badge-fecha">
                                            <?= date('d/m/Y', strtotime($row['fecha'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($row['item_code']) ?></strong>
                                    </td>
                                    <td>
                                        <div class="medicamento-info">
                                            <strong><?= htmlspecialchars($row['item_name']) ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">
                                            <?= htmlspecialchars($row['existe_lote']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-warning">
                                            <?= date('d/m/Y', strtotime($row['existe_caducidad'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-cantidad">
                                            <i class="fas fa-undo"></i> <?= number_format($row['dev_qty']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-cantidad">
                                            <i class="fas fa-warehouse"></i> <?= number_format($row['cant_inv']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-cantidad">
                                            <i class="fas fa-times-circle"></i> <?= number_format($row['cant_mer']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small><?= htmlspecialchars($row['motivoi']) ?></small>
                                    </td>
                                    <td>
                                        <small><?= htmlspecialchars($row['motivom']) ?></small>
                                    </td>
                                    <td>
                                        <?php
                                        $estatus = strtolower($row['dev_estatus']);
                                        $clase_estatus = '';
                                        $icono_estatus = '';
                                        
                                        if (strpos($estatus, 'activo') !== false || strpos($estatus, 'completado') !== false) {
                                            $clase_estatus = 'badge-activo';
                                            $icono_estatus = 'fas fa-check-circle';
                                        } elseif (strpos($estatus, 'pendiente') !== false) {
                                            $clase_estatus = 'badge-pendiente';
                                            $icono_estatus = 'fas fa-clock';
                                        } else {
                                            $clase_estatus = 'badge-inactivo';
                                            $icono_estatus = 'fas fa-times-circle';
                                        }
                                        ?>
                                        <span class="badge-estado <?= $clase_estatus ?>">
                                            <i class="<?= $icono_estatus ?>"></i>
                                            <?= htmlspecialchars($row['dev_estatus']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?= htmlspecialchars($row['nombre_ubicacion']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-user"></i> <?= $row['id_usua'] ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Resumen de resultados -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Total de registros encontrados: <?= $resultado->num_rows ?></strong>
                        </div>
                    </div>
                </div>
                
            <?php else: ?>
                <div class="tabla-contenedor">
                    <div class="mensaje-sin-resultados">
                        <i class="fas fa-search"></i>
                        <p>No se encontraron registros en el rango de fechas especificado.</p>
                        <small>Intente con un rango de fechas diferente o verifique los filtros aplicados.</small>
                    </div>
                </div>
            <?php endif; ?>
            
        </div>
    </div>
    <!-- JavaScript para funcionalidades adicionales -->
    <script>
        $(document).ready(function() {
            // Animaciones de entrada
            $('.container-moderno').hide().fadeIn(800);
            $('.contenedor-filtros, .tabla-contenedor').hide().slideDown(600);
            
            // Efectos hover para las filas de la tabla
            $('.tabla-moderna tbody tr').hover(
                function() {
                    $(this).addClass('table-row-hover');
                },
                function() {
                    $(this).removeClass('table-row-hover');
                }
            );
            
            // Función de búsqueda en la tabla
            function agregarBusqueda() {
                if ($('.tabla-moderna tbody tr').length > 10) {
                    var searchHTML = `
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="background: var(--color-primario); color: white;">
                                            <i class="fas fa-search"></i>
                                        </span>
                                    </div>
                                    <input type="text" id="buscarTabla" class="form-control" 
                                           placeholder="Buscar en la tabla...">
                                </div>
                            </div>
                        </div>
                    `;
                    $('.tabla-contenedor').before(searchHTML);
                    
                    // Función de búsqueda
                    $('#buscarTabla').on('keyup', function() {
                        var value = $(this).val().toLowerCase();
                        $('.tabla-moderna tbody tr').filter(function() {
                            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                        });
                    });
                }
            }
            
            // Agregar búsqueda si hay muchos registros
            agregarBusqueda();
            
            // Tooltips para badges
            $('[data-toggle="tooltip"]').tooltip();
            
            // Validación de fechas
            $('input[type="date"]').on('change', function() {
                var fechaInicial = $('input[name="inicial"]').val();
                var fechaFinal = $('input[name="final"]').val();
                
                if (fechaInicial && fechaFinal) {
                    if (new Date(fechaInicial) > new Date(fechaFinal)) {
                        alert('La fecha inicial no puede ser mayor que la fecha final.');
                        $(this).val('');
                    }
                }
            });
            
            // Estadísticas rápidas
            function calcularEstadisticas() {
                var totalDevoluciones = 0;
                var totalInventario = 0;
                var totalMerma = 0;
                
                $('.tabla-moderna tbody tr').each(function() {
                    var devQty = parseInt($(this).find('td:nth-child(7)').text().replace(/\D/g, '')) || 0;
                    var cantInv = parseInt($(this).find('td:nth-child(8)').text().replace(/\D/g, '')) || 0;
                    var cantMer = parseInt($(this).find('td:nth-child(9)').text().replace(/\D/g, '')) || 0;
                    
                    totalDevoluciones += devQty;
                    totalInventario += cantInv;
                    totalMerma += cantMer;
                });
                
                if (totalDevoluciones > 0) {
                    var estadisticasHTML = `
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="card text-center" style="border: 2px solid var(--color-primario);">
                                    <div class="card-body">
                                        <h5 style="color: var(--color-primario);">
                                            <i class="fas fa-undo"></i> ${totalDevoluciones.toLocaleString()}
                                        </h5>
                                        <p class="card-text">Total Devoluciones</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center" style="border: 2px solid #28a745;">
                                    <div class="card-body">
                                        <h5 style="color: #28a745;">
                                            <i class="fas fa-warehouse"></i> ${totalInventario.toLocaleString()}
                                        </h5>
                                        <p class="card-text">Total Inventario</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center" style="border: 2px solid #dc3545;">
                                    <div class="card-body">
                                        <h5 style="color: #dc3545;">
                                            <i class="fas fa-times-circle"></i> ${totalMerma.toLocaleString()}
                                        </h5>
                                        <p class="card-text">Total Merma</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    $('.tabla-contenedor').after(estadisticasHTML);
                }
            }
            
            // Calcular estadísticas si hay datos
            if ($('.tabla-moderna tbody tr').length > 0) {
                calcularEstadisticas();
            }
        });
    </script>

    <!-- Estilos adicionales -->
    <style>
        .table-row-hover {
            background-color: var(--color-fondo) !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .medicamento-info {
            text-align: left;
            max-width: 200px;
        }
        
        .medicamento-info strong {
            color: var(--color-primario);
            font-size: 11px;
            display: block;
            line-height: 1.2;
        }
        
        .badge-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
        }
        
        .badge-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #212529;
        }
        
        .badge-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }
        
        .input-group-text {
            border: 2px solid var(--color-borde);
        }
        
        .alert-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            border: 2px solid #17a2b8;
            border-radius: 12px;
        }
        
        .card {
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
    </style>

</body>
</html>