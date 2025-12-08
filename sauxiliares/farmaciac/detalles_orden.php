<?php
session_start();
include "../../conexionbd.php";
include "../header_farmaciac.php";

if (isset($_GET['id_compra'])) {
    $id_compra = $_GET['id_compra'];

    // Consulta principal para obtener la información de la orden de compra junto con el nombre del proveedor y el comentario
    $query_orden = "SELECT oc.*, p.nom_prov
                    FROM ordenes_compra oc
                    INNER JOIN proveedores p ON oc.id_prov = p.id_prov
                    WHERE oc.id_compra = '$id_compra'";
    $result_orden = $conexion->query($query_orden);
    $orden = $result_orden->fetch_assoc();

    // Consulta para obtener los detalles de los productos de la orden de compra
    $query_detalles = "SELECT orden_compra.solicita,entrega,fecha_entrega, item_almacen.item_id, item_almacen.item_name, item_almacen.item_grams, item_almacen.item_code, item_almacen.item_costs
                        FROM orden_compra
                        INNER JOIN item_almacen ON orden_compra.item_id = item_almacen.item_id
                        WHERE orden_compra.id_compra = '$id_compra'";
    $result_detalles = $conexion->query($query_detalles);

    if (!$orden) {
        echo "<script>alert('No se encontró la orden de compra');</script>";
        echo "<script>window.location='vista_ordenes.php';</script>";
        exit;
    }

    // Autorizar la orden de compra si se ha enviado la solicitud
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['autorizar_orden'])) {
        $query_autorizar = "UPDATE ordenes_compra SET estatus = 'AUTORIZADO', activo = 'SI' WHERE id_compra = '$id_compra'";
        if ($conexion->query($query_autorizar)) {
            echo "<script>alert('Orden de compra autorizada exitosamente');</script>";
            echo "<script>window.location.href = 'detalles_orden.php?id_compra=$id_compra';</script>";
        } else {
            echo "<script>alert('Error al autorizar la orden de compra');</script>";
        }
    }

    // Cancelar la orden de compra si se ha enviado la solicitud
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancelar_orden'])) {
        $query_cancelar = "UPDATE ordenes_compra SET estatus = 'CANCELADO', activo = 'NO' WHERE id_compra = '$id_compra'";
        if ($conexion->query($query_cancelar)) {
            echo "<script>alert('Orden de compra cancelada exitosamente');</script>";
            echo "<script>window.location.href = 'detalles_orden.php?id_compra=$id_compra';</script>";
        } else {
            echo "<script>alert('Error al cancelar la orden de compra');</script>";
        }
    }
} else {
    echo "<script>alert('No se ha seleccionado una orden válida');</script>";
    echo "<script>window.location='vista_ordenes.php';</script>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Orden de Compra</title>
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

        .btn-pdf {
            background: linear-gradient(135deg, #007BFF 0%, #0056b3 100%);
            color: white !important;
        }

        .btn-enviar {
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            color: white !important;
        }

        .btn-autorizar {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white !important;
        }

        .btn-cancelar {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white !important;
        }

        .btn-moderno:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            text-decoration: none;
        }

        .btn-moderno:disabled {
            background: #ccc !important;
            cursor: not-allowed !important;
            opacity: 0.6;
            transform: none !important;
            box-shadow: none !important;
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

        .header-seccion {
            text-align: center;
            margin: 30px 0 20px 0;
            padding: 20px 0;
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            border-radius: 15px;
            color: white;
            box-shadow: var(--sombra);
        }

        .header-seccion h3 {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .header-seccion .icono-seccion {
            font-size: 28px;
            margin-right: 10px;
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
            font-size: 14px;
            min-width: 100%;
            border-collapse: collapse;
            width: 100%;
        }

        .table-moderna thead th {
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            color: white;
            border: none;
            padding: 15px 12px;
            font-weight: 600;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 10;
            font-size: 13px;
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
            padding: 12px 10px;
            vertical-align: middle;
            border: none;
            text-align: center;
            background-color: whitesmoke;
        }

        /* ===== CONTENEDOR DE BOTONES DE ACCIÓN ===== */
        .contenedor-acciones {
            text-align: center;
            margin: 30px 0;
            padding: 25px;
            background: white;
            border: 2px solid var(--color-borde);
            border-radius: 15px;
            box-shadow: var(--sombra);
        }

        /* ===== ESTATUS BADGES ===== */
        .estatus-pendiente {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #333;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .estatus-autorizado {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .estatus-cancelado {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .estatus-entregado {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
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

            .header-seccion h3 {
                font-size: 20px;
            }

            .btn-moderno {
                padding: 10px 16px;
                font-size: 14px;
                margin: 3px;
                flex: 1;
                min-width: calc(50% - 10px);
            }

            .table-moderna {
                font-size: 11px;
            }

            .table-moderna thead th,
            .table-moderna tbody td {
                padding: 8px 6px;
            }

            .contenedor-acciones {
                padding: 15px;
            }

            .contenedor-acciones .btn-moderno {
                width: 100%;
                margin: 5px 0;
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

        .tabla-contenedor,
        .contenedor-acciones {
            animation: fadeInUp 0.6s ease-out 0.1s both;
        }
    </style>

    <script>
        function confirmarAutorizacion() {
            if (confirm('¿Está seguro que desea autorizar esta orden de compra?')) {
                document.getElementById('form-autorizar').submit();
            }
        }

        function confirmarCancelacion() {
            if (confirm('¿Está seguro que desea cancelar esta orden de compra? Esta acción no se puede deshacer.')) {
                document.getElementById('form-cancelar').submit();
            }
        }
    </script>
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
                <i class="fas fa-file-invoice icono-principal"></i>
                <h1>Detalles de la Orden de Compra #<?php echo htmlspecialchars($id_compra); ?></h1>
            </div>
        </div>

        <!-- Información de la Orden -->
        <div class="header-seccion">
            <h3><i class="fas fa-info-circle icono-seccion"></i>Información de la Orden</h3>
        </div>

        <div class="tabla-contenedor">
            <table class="table-moderna">
                <thead>
                <tr>
                    <th><i class="fas fa-truck"></i> Proveedor</th>
                    <th><i class="fas fa-calendar"></i> Fecha de Solicitud</th>
                    <th><i class="fas fa-dollar-sign"></i> Monto Total</th>
                    <th><i class="fas fa-file-invoice-dollar"></i> Factura</th>
                    <th><i class="fas fa-toggle-on"></i> Estatus</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($orden['id_prov']) . " - " . htmlspecialchars($orden['nom_prov']); ?></td>
                    <td><?php echo htmlspecialchars($orden['fecha_solicitud']); ?></td>
                    <td><strong>$<?php echo number_format($orden['monto'], 2); ?></strong></td>
                    <td><?php echo htmlspecialchars($orden['factura']); ?></td>
                    <td>
                        <?php
                        $estatus = strtoupper($orden['estatus']);
                        $clase_estatus = '';
                        switch($estatus) {
                            case 'PENDIENTE':
                                $clase_estatus = 'estatus-pendiente';
                                break;
                            case 'AUTORIZADO':
                                $clase_estatus = 'estatus-autorizado';
                                break;
                            case 'CANCELADO':
                                $clase_estatus = 'estatus-cancelado';
                                break;
                            case 'ENTREGADO':
                                $clase_estatus = 'estatus-entregado';
                                break;
                            default:
                                $clase_estatus = 'estatus-pendiente';
                        }
                        ?>
                        <span class="<?php echo $clase_estatus; ?>"><?php echo htmlspecialchars($estatus); ?></span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Productos en la Orden -->
        <div class="header-seccion">
            <h3><i class="fas fa-boxes icono-seccion"></i>Productos en la Orden</h3>
        </div>

        <div class="tabla-contenedor">
            <table class="table-moderna">
                <thead>
                <tr>
                    <th><i class="fas fa-hashtag"></i> ID Ítem</th>
                    <th><i class="fas fa-tag"></i> Nombre</th>
                    <th><i class="fas fa-align-left"></i> Descripción</th>
                    <th><i class="fas fa-barcode"></i> Código</th>
                    <th><i class="fas fa-dollar-sign"></i> Costo</th>
                    <th><i class="fas fa-clipboard-list"></i> Solicita</th>
                    <th><i class="fas fa-check"></i> Entregado</th>
                    <th><i class="fas fa-calendar-check"></i> Fecha Entrega</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if ($result_detalles->num_rows > 0) {
                    while ($detalle = $result_detalles->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . (isset($detalle['item_id']) ? htmlspecialchars($detalle['item_id']) : 'N/A') . "</td>";
                        echo "<td>" . htmlspecialchars($detalle['item_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($detalle['item_grams']) . "</td>";
                        echo "<td>" . htmlspecialchars($detalle['item_code']) . "</td>";
                        echo "<td><strong>$" . htmlspecialchars(number_format($detalle['item_costs'], 2)) . "</strong></td>";
                        echo "<td>" . htmlspecialchars($detalle['solicita']) . "</td>";

                        // Mostrar entregado y fecha entrega solo si ambos campos contienen datos
                        if (
                                !empty($detalle['entrega']) && !is_null($detalle['entrega']) &&
                                !empty($detalle['fecha_entrega']) && !is_null($detalle['fecha_entrega'])
                        ) {
                            echo "<td>" . htmlspecialchars($detalle['entrega']) . "</td>";
                            echo "<td>" . htmlspecialchars($detalle['fecha_entrega']) . "</td>";
                        } else {
                            echo "<td>-</td>";
                            echo "<td>-</td>";
                        }

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='mensaje-sin-resultados'><i class='fas fa-info-circle'></i><br>No se encontraron productos en esta orden de compra.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>

        <!-- Botones de Acción -->
        <div class="contenedor-acciones">
            <a class="btn-moderno btn-pdf" href="genera_pdf_detalle.php?id_compra=<?php echo $id_compra; ?>">
                <i class="fas fa-file-pdf"></i> Ver PDF
            </a>
            <a class="btn-moderno btn-enviar" href="enviar_pdf.php?id_compra=<?php echo $id_compra; ?>">
                <i class="fas fa-paper-plane"></i> Enviar PDF
            </a>

            <?php if ($orden['estatus'] == 'PENDIENTE'): ?>
                <button type="button" class="btn-moderno btn-autorizar" onclick="confirmarAutorizacion()">
                    <i class="fas fa-check-circle"></i> Autorizar
                </button>
                <button type="button" class="btn-moderno btn-cancelar" onclick="confirmarCancelacion()">
                    <i class="fas fa-times-circle"></i> Cancelar
                </button>
            <?php elseif ($orden['estatus'] == 'AUTORIZADO'): ?>
                <button type="button" class="btn-moderno btn-autorizar" disabled>
                    <i class="fas fa-check-circle"></i> Autorizado
                </button>
                <button type="button" class="btn-moderno btn-cancelar" onclick="confirmarCancelacion()">
                    <i class="fas fa-times-circle"></i> Cancelar
                </button>
            <?php elseif ($orden['estatus'] == 'CANCELADO'): ?>
                <button type="button" class="btn-moderno btn-autorizar" disabled>
                    <i class="fas fa-check-circle"></i> Autorizar (Cancelado)
                </button>
                <button type="button" class="btn-moderno btn-cancelar" disabled>
                    <i class="fas fa-times-circle"></i> Cancelado
                </button>
            <?php else: ?>
                <button type="button" class="btn-moderno btn-autorizar" disabled>
                    <i class="fas fa-check-circle"></i> Autorizar (<?php echo htmlspecialchars($orden['estatus']); ?>)
                </button>
                <button type="button" class="btn-moderno btn-cancelar" disabled>
                    <i class="fas fa-times-circle"></i> Cancelar (<?php echo htmlspecialchars($orden['estatus']); ?>)
                </button>
            <?php endif; ?>
        </div>

        <!-- Formulario oculto para autorización -->
        <form id="form-autorizar" method="post" style="display: none;">
            <input type="hidden" name="autorizar_orden" value="1">
        </form>

        <!-- Formulario oculto para cancelación -->
        <form id="form-cancelar" method="post" style="display: none;">
            <input type="hidden" name="cancelar_orden" value="1">
        </form>

    </div>
</div>
</body>

</html>
