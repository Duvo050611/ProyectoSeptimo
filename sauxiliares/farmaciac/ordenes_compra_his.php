<?php
session_start();
include "../../conexionbd.php";

$usuario = $_SESSION['login'];

if ($usuario['id_rol'] == 11 || $usuario['id_rol'] == 4 || $usuario['id_rol'] == 5) {
    include "../header_farmaciac.php";
} else {
    echo "<script>window.location='../../index.php';</script>";
}

// Variables para la búsqueda y filtros
$id_compra = isset($_GET['id_compra']) ? mysqli_real_escape_string($conexion, $_GET['id_compra']) : '';

// Paginación
$registros_por_pagina = 20;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) {
    $pagina = 1;
}

// Calcular el número total de registros
$query_total = "SELECT COUNT(*) AS total FROM ordenes_compra oc
                INNER JOIN proveedores p ON oc.id_prov = p.id_prov
                WHERE oc.estatus = 'ENTREGADO'";

if ($id_compra) {
    $query_total .= " AND oc.id_compra LIKE '%$id_compra%'";
}
$resultado_total = $conexion->query($query_total) or die($conexion->error);
$total_registros = $resultado_total->fetch_assoc()['total'];

// Calcular el total de páginas
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Asegurar que la página esté dentro del rango
if ($pagina < 1) {
    $pagina = 1;
}

// Calcular el desplazamiento (OFFSET)
$offset = ($pagina - 1) * $registros_por_pagina;
if ($offset < 0) {
    $offset = 0; // Aseguramos que el offset no sea negativo
}

// Consulta con LIMIT y OFFSET para obtener los registros paginados
$query = "SELECT oc.id_compra, oc.id_prov, oc.fecha_solicitud, oc.monto, oc.descuento, oc.iva, oc.total, oc.activo, oc.estatus, p.nom_prov
          FROM ordenes_compra oc
          INNER JOIN proveedores p ON oc.id_prov = p.id_prov
          WHERE oc.estatus = 'ENTREGADO'";

if ($id_compra) {
    $query .= " AND oc.id_compra LIKE '%$id_compra%'";
}

$query .= " ORDER BY oc.fecha_solicitud DESC LIMIT $registros_por_pagina OFFSET $offset";
$result = $conexion->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Histórico de Órdenes de Compra</title>
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

        .btn-buscar {
            background: linear-gradient(135deg, #0c675e 0%, #28a745 100%);
            color: white !important;
            padding: 8px 20px;
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

        /* ===== FORMULARIO DE BÚSQUEDA ===== */
        .contenedor-busqueda {
            background: white;
            border: 2px solid var(--color-borde);
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            box-shadow: var(--sombra);
        }

        .form-control {
            border: 2px solid var(--color-borde);
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
            margin-right: 15px;
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
            display: block;
        }

        .form-busqueda {
            display: flex;
            align-items: end;
            gap: 15px;
            flex-wrap: wrap;
        }

        .form-group-busqueda {
            flex: 1;
            min-width: 250px;
        }

        /* ===== TABLA MODERNIZADA ===== */
        .tabla-contenedor {
            background: white;
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
            border-bottom: 1px solid #f1f3f4;
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

            .contenedor-busqueda {
                padding: 15px;
            }

            .form-busqueda {
                flex-direction: column;
                align-items: stretch;
            }

            .form-group-busqueda {
                min-width: 100%;
                margin-bottom: 15px;
            }

            .btn-buscar {
                align-self: flex-start;
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

        .contenedor-busqueda,
        .tabla-contenedor {
            animation: fadeInUp 0.6s ease-out 0.1s both;
        }

        /* ===== ESTILOS ESPECÍFICOS PARA ESTATUS ENTREGADO ===== */
        .estatus-entregado {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
<div class="container-fluid">
    <div class="container-moderno">
        <!-- Botón de regreso -->
        <div class="d-flex justify-content-start mb-4">
            <a href="ordenes_compra.php" class="btn-moderno btn-regresar">
                <i class="fas fa-arrow-left"></i> Regresar
            </a>
        </div>

        <!-- Header principal -->
        <div class="header-principal">
            <div class="contenido-header">
                <i class="fas fa-history icono-principal"></i>
                <h1>Histórico de órdenes de Compra</h1>
            </div>
        </div>

        <!-- Formulario de búsqueda -->
        <div class="contenedor-busqueda">
            <form method="GET" action="">
                <div class="form-busqueda">
                    <div class="form-group-busqueda">
                        <label for="id_compra" class="form-label">
                            <i class="fas fa-search"></i> Buscar por ID de Compra:
                        </label>
                        <input type="text"
                               name="id_compra"
                               id="id_compra"
                               class="form-control"
                               value="<?= htmlspecialchars($id_compra) ?>"
                               placeholder="Ingrese el ID de compra a buscar">
                    </div>
                    <button type="submit" class="btn-moderno btn-buscar">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </form>
        </div>

        <!-- Info de paginación -->
        <div class="info-paginacion">
            Mostrando <?php echo (($pagina - 1) * $registros_por_pagina) + 1; ?> -
            <?php echo min($pagina * $registros_por_pagina, $total_registros); ?>
            de <?php echo $total_registros; ?> órdenes entregadas
            <?php if ($id_compra): ?>
                <strong>(Filtrado por ID: "<?php echo htmlspecialchars($id_compra); ?>")</strong>
            <?php endif; ?>
        </div>

        <!-- Tabla con los resultados -->
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
                    <th><i class="fas fa-check-circle"></i> Estatus</th>
                    <th><i class="fas fa-eye"></i> Detalles</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id_compra']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nom_prov']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fecha_solicitud']) . "</td>";
                        echo "<td>$" . number_format($row['monto'], 2) . "</td>";
                        echo "<td>$" . number_format($row['descuento'], 2) . "</td>";
                        echo "<td>$" . number_format($row['iva'], 2) . "</td>";
                        echo "<td><strong>$" . number_format($row['total'], 2) . "</strong></td>";
                        echo "<td>" . ($row['activo'] ? 'Sí' : 'No') . "</td>";
                        echo "<td><span class='estatus-entregado'>" . htmlspecialchars($row['estatus']) . "</span></td>";
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
                        <a href='?pagina=1&id_compra=<?php echo urlencode($id_compra); ?>' class="btn-paginacion">
                            <i class="fas fa-angle-double-left"></i> Primera
                        </a>
                        <a href='?pagina=<?php echo ($pagina - 1); ?>&id_compra=<?php echo urlencode($id_compra); ?>' class="btn-paginacion">
                            <i class="fas fa-chevron-left"></i> Anterior
                        </a>
                    <?php endif; ?>

                    <!-- Páginas del rango -->
                    <?php
                    $rango = 5;
                    $inicio = max(1, $pagina - $rango);
                    $fin = min($total_paginas, $pagina + $rango);

                    // Mostrar primera página si no está en el rango
                    if ($inicio > 1): ?>
                        <a href='?pagina=1&id_compra=<?php echo urlencode($id_compra); ?>' class="btn-paginacion">1</a>
                        <?php if ($inicio > 2): ?>
                            <span class="btn-paginacion" style="background: transparent; border: none; cursor: default;">...</span>
                        <?php endif;
                    endif;

                    // Mostrar páginas del rango
                    for ($i = $inicio; $i <= $fin; $i++): ?>
                        <a href='?pagina=<?php echo $i; ?>&id_compra=<?php echo urlencode($id_compra); ?>'
                           class="btn-paginacion <?php echo ($i == $pagina ? 'current' : ''); ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor;

                    // Mostrar última página si no está en el rango
                    if ($fin < $total_paginas): ?>
                        <?php if ($fin < $total_paginas - 1): ?>
                            <span class="btn-paginacion" style="background: transparent; border: none; cursor: default;">...</span>
                        <?php endif; ?>
                        <a href='?pagina=<?php echo $total_paginas; ?>&id_compra=<?php echo urlencode($id_compra); ?>' class="btn-paginacion"><?php echo $total_paginas; ?></a>
                    <?php endif; ?>

                    <!-- Enlace a la página siguiente -->
                    <?php if ($pagina < $total_paginas): ?>
                        <a href='?pagina=<?php echo ($pagina + 1); ?>&id_compra=<?php echo urlencode($id_compra); ?>' class="btn-paginacion">
                            Siguiente <i class="fas fa-chevron-right"></i>
                        </a>
                        <a href='?pagina=<?php echo $total_paginas; ?>&id_compra=<?php echo urlencode($id_compra); ?>' class="btn-paginacion">
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