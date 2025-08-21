<?php
session_start();
include "../../conexionbd.php";

$usuario = $_SESSION['login'];

if ($usuario['id_rol'] == 11 || $usuario['id_rol'] == 4 || $usuario['id_rol'] == 5) {
    include "../header_farmaciac.php";
} else {
    echo "<script>window.location='../../index.php';</script>";
}

// Obtener el filtro de estatus seleccionado
$filtro_estatus = isset($_GET['estatus']) ? $_GET['estatus'] : '';

// Establecer el número de registros por página
$registros_por_pagina = 10;

// Obtener el número total de registros
$sql_total = "SELECT COUNT(*) AS total FROM ordenes_compra";
if (!empty($filtro_estatus)) {
    $sql_total .= " WHERE estatus = '$filtro_estatus'";
} else {
    $sql_total .= " WHERE estatus != 'ENTREGADO'";
}

$result_total = $conexion->query($sql_total);
$row_total = $result_total->fetch_assoc();
$total_registros = $row_total['total'];

// Calcular el total de páginas
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Obtener la página actual
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) {
    $pagina = 1;
}
if ($total_paginas > 0 && $pagina > $total_paginas) {
    $pagina = $total_paginas;
}

// Calcular el desplazamiento
$offset = max(0, ($pagina - 1) * $registros_por_pagina);

// Consulta para obtener las órdenes de compra con el estatus
$sql = "SELECT id_compra, id_prov, fecha_solicitud, monto, descuento, iva, total, activo, estatus 
        FROM ordenes_compra";

// Aplicar filtro si se seleccionó un estatus
if (!empty($filtro_estatus)) {
    $sql .= " WHERE estatus = '$filtro_estatus'";
} else {
    $sql .= " WHERE estatus != 'ENTREGADO'";
}

// Agregar ORDER BY y luego LIMIT y OFFSET para la paginación
$sql .= " ORDER BY fecha_solicitud DESC";
$sql .= " LIMIT $registros_por_pagina OFFSET $offset";

$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Órdenes de Compra</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1″/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
    integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- jQuery y Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
            margin: 5px;
        }

        .btn-regresar {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white !important;
        }

        .btn-crear-libre {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white !important;
        }

        .btn-crear-proveedor {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white !important;
        }

        .btn-historico {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: white !important;
        }

        .btn-filtrar {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white !important;
            padding: 8px 15px;
            font-size: 14px;
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
            text-align: center;
        }

        .form-control {
            border: 2px solid var(--color-borde);
            border-radius: 10px;
            padding: 12px 15px;
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

        select {
            border: 2px solid var(--color-borde);
            border-radius: 10px;
            padding: 8px;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        select:focus {
            border-color: var(--color-primario);
            box-shadow: 0 0 0 3px rgba(43, 45, 127, 0.1);
            outline: none;
        }

        /* ===== TABLA MODERNIZADA ===== */
        .tabla-contenedor {
            background: #8a9fd3;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--sombra);
            border: 2px solid var(--color-borde);
            max-height: 80vh;
            overflow-y: auto;
            margin: 20px 0;
        }

        .table-moderna {
            margin: 0;
            font-size: 12px;
            min-width: 100%;
            border-collapse: collapse;
            width: 100%;
        }

        .table-moderna thead th {
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

        .table-moderna thead th i {
            margin-right: 5px;
        }

        .table-moderna tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #478aac;
        }

        .table-moderna tbody tr:hover {
            background-color: var(--color-fondo);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .table-moderna tbody td {
            padding: 10px 8px;
            vertical-align: middle;
            border: none;
            text-align: center;
            white-space: nowrap;
            background-color: whitesmoke;
        }

        /* ===== ENLACES EN LA TABLA ===== */
        .table-moderna a {
            color: var(--color-primario);
            text-decoration: none;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .table-moderna a:hover {
            background: var(--color-primario);
            color: white;
            text-decoration: none;
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
            margin: 20px 0 10px 0;
            padding-bottom: 0;
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

        .btn-paginacion.current {
            background: linear-gradient(135deg, #ff7f50 0%, #ff6347 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 127, 80, 0.4);
        }

        /* ===== INFO PAGINACIÓN ===== */
        .info-paginacion {
            text-align: center;
            margin: 15px 0;
            color: #6c757d;
            font-size: 14px;
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
                margin: 3px;
            }

            .table-moderna {
                font-size: 10px;
            }

            .table-moderna thead th,
            .table-moderna tbody td {
                padding: 8px 6px;
            }

            .paginacion-moderna {
                flex-wrap: wrap;
                gap: 5px;
            }

            .btn-paginacion {
                min-width: 35px;
                height: 35px;
                font-size: 12px;
            }

            .contenedor-filtros {
                padding: 15px;
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

        /* ===== ESTILOS ESPECÍFICOS PARA ESTATUS ===== */
        .estatus-pendiente {
            font-weight: bold;
            color: #e74c3c;
        }

        .estatus-autorizado {
            color: #28a745;
            font-weight: 600;
        }

        .estatus-cancelado {
            color: #dc3545;
            font-weight: 600;
        }

        .estatus-parcial {
            color: #ffc107;
            font-weight: 600;
        }
    </style>
</head>

<body>
<div class="container-fluid">
    <div class="container-moderno">
        <!-- Botones de navegación -->
        <div class="d-flex flex-wrap justify-content-start mb-4">
            <a href="../../template/menu_farmaciacentral.php" class="btn-moderno btn-regresar">
                <i class="fas fa-arrow-left"></i> Regresar
            </a>
            <a href="generar_oc_libre.php" class="btn-moderno btn-crear-libre">
                <i class="fas fa-plus-circle"></i> Crear orden de compra libre
            </a>
            <a href="genera_oc_proveedor.php" class="btn-moderno btn-crear-proveedor">
                <i class="fas fa-truck"></i> Crear orden de compra por proveedor
            </a>
            <a href="ordenes_compra_his.php" class="btn-moderno btn-historico">
                <i class="fas fa-history"></i> Histórico de entregas
            </a>
        </div>

        <!-- Header principal -->
        <div class="header-principal">
            <div class="contenido-header">
                <i class="fas fa-shopping-cart icono-principal"></i>
                <h1>Órdenes de compra<?php echo !empty($filtro_estatus) ? ' - ' . $filtro_estatus : ' (excepto ENTREGADO)'; ?></h1>
            </div>
        </div>

        <!-- Filtro por estatus -->
        <div class="contenedor-filtros">
            <form method="GET" action="">
                <label for="estatus" class="form-label">
                    <i class="fas fa-filter"></i> Filtrar por estatus:
                </label>
                <select name="estatus" id="estatus">
                    <option value="">Todos (excepto ENTREGADO)</option>
                    <option value="PENDIENTE" <?php echo ($filtro_estatus == 'PENDIENTE') ? 'selected' : ''; ?>>PENDIENTE</option>
                    <option value="AUTORIZADO" <?php echo ($filtro_estatus == 'AUTORIZADO') ? 'selected' : ''; ?>>AUTORIZADO</option>
                    <option value="CANCELADO" <?php echo ($filtro_estatus == 'CANCELADO') ? 'selected' : ''; ?>>CANCELADO</option>
                    <option value="PARCIAL" <?php echo ($filtro_estatus == 'PARCIAL') ? 'selected' : ''; ?>>PARCIAL</option>
                </select>
                <button type="submit" class="btn-moderno btn-filtrar">
                    <i class="fas fa-search"></i> Filtrar
                </button>
            </form>
        </div>

        <!-- Info de paginación -->
        <div class="info-paginacion">
            Mostrando <?php echo (($pagina - 1) * $registros_por_pagina) + 1; ?> -
            <?php echo min($pagina * $registros_por_pagina, $total_registros); ?>
            de <?php echo $total_registros; ?> órdenes de compra
        </div>

        <!-- Tabla de órdenes -->
        <div class="tabla-contenedor">
            <table class="table-moderna">
                <thead>
                <tr>
                    <th><i class="fas fa-hashtag"></i> ID Compra</th>
                    <th><i class="fas fa-truck"></i> Proveedor</th>
                    <th><i class="fas fa-calendar"></i> Fecha de Solicitud</th>
                    <th><i class="fas fa-dollar-sign"></i> Monto</th>
                    <th><i class="fas fa-percentage"></i> Descuento</th>
                    <th><i class="fas fa-receipt"></i> IVA</th>
                    <th><i class="fas fa-money-bill"></i> Total</th>
                    <th><i class="fas fa-toggle-on"></i> Activo</th>
                    <th><i class="fas fa-info-circle"></i> Estatus</th>
                    <th><i class="fas fa-eye"></i> Detalles</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $prov = $row['id_prov'];

                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id_compra']) . "</td>";
                        echo "<td>" . htmlspecialchars($prov) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fecha_solicitud']) . "</td>";
                        echo "<td>$" . number_format($row['monto'], 2) . "</td>";
                        echo "<td>$" . number_format($row['descuento'], 2) . "</td>";
                        echo "<td>$" . number_format($row['iva'], 2) . "</td>";
                        echo "<td><strong>$" . number_format($row['total'], 2) . "</strong></td>";
                        echo "<td>" . ($row['activo'] ? 'Sí' : 'No') . "</td>";

                        // Aplicar estilos según el estatus
                        $estatus_class = '';
                        switch ($row['estatus']) {
                            case 'PENDIENTE':
                                $estatus_class = 'estatus-pendiente';
                                break;
                            case 'AUTORIZADO':
                                $estatus_class = 'estatus-autorizado';
                                break;
                            case 'CANCELADO':
                                $estatus_class = 'estatus-cancelado';
                                break;
                            case 'PARCIAL':
                                $estatus_class = 'estatus-parcial';
                                break;
                        }

                        echo "<td><span class='$estatus_class'>" . htmlspecialchars($row['estatus']) . "</span></td>";
                        echo "<td><a href='detalles_orden.php?id_compra=" . $row['id_compra'] . "'>Ver Detalles</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='10' class='mensaje-sin-resultados'><i class='fas fa-info-circle'></i><br>No hay órdenes de compra disponibles.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <?php if ($total_paginas > 1): ?>
            <div class="contenedor-paginacion">
                <div class="paginacion-moderna">
                    <!-- Enlace a la primera página -->
                    <?php if ($pagina > 1): ?>
                        <a href='?pagina=1&estatus=<?php echo $filtro_estatus; ?>' class="btn-paginacion">
                            <i class="fas fa-angle-double-left"></i> Primera
                        </a>
                    <?php endif; ?>

                    <!-- Páginas cercanas a la actual -->
                    <?php
                    $pagina_inicio = max(1, $pagina - 5);
                    $pagina_fin = min($total_paginas, $pagina + 5);

                    // Mostrar primera página si no está en el rango
                    if ($pagina_inicio > 1): ?>
                        <a href='?pagina=1&estatus=<?php echo $filtro_estatus; ?>' class="btn-paginacion">1</a>
                        <?php if ($pagina_inicio > 2): ?>
                            <span class="btn-paginacion" style="background: transparent; border: none; cursor: default;">...</span>
                        <?php endif;
                    endif;

                    // Páginas antes de la actual
                    for ($i = $pagina_inicio; $i < $pagina; $i++): ?>
                        <a href='?pagina=<?php echo $i; ?>&estatus=<?php echo $filtro_estatus; ?>' class="btn-paginacion"><?php echo $i; ?></a>
                    <?php endfor; ?>

                    <!-- Página actual -->
                    <a href='?pagina=<?php echo $pagina; ?>&estatus=<?php echo $filtro_estatus; ?>' class="btn-paginacion current"><?php echo $pagina; ?></a>

                    <!-- Páginas después de la actual -->
                    <?php for ($i = $pagina + 1; $i <= $pagina_fin; $i++): ?>
                        <a href='?pagina=<?php echo $i; ?>&estatus=<?php echo $filtro_estatus; ?>' class="btn-paginacion"><?php echo $i; ?></a>
                    <?php endfor;

                    // Mostrar última página si no está en el rango
                    if ($pagina_fin < $total_paginas): ?>
                        <?php if ($pagina_fin < $total_paginas - 1): ?>
                            <span class="btn-paginacion" style="background: transparent; border: none; cursor: default;">...</span>
                        <?php endif; ?>
                        <a href='?pagina=<?php echo $total_paginas; ?>&estatus=<?php echo $filtro_estatus; ?>' class="btn-paginacion"><?php echo $total_paginas; ?></a>
                    <?php endif; ?>

                    <!-- Enlace a la última página -->
                    <?php if ($pagina < $total_paginas): ?>
                        <a href='?pagina=<?php echo $total_paginas; ?>&estatus=<?php echo $filtro_estatus; ?>' class="btn-paginacion">
                            Última <i class="fas fa-angle-double-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>
</body>
</html>