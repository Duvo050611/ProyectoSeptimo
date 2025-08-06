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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .form-container {
            width: 80%;
            margin: 20px auto;
            background-color: #fdfdfd;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2,
        h3 {
            color: #2b2d7f;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #2b2d7f;
            color: white;
        }

        td {
            background-color: #e8f6f4;
        }

        .btn-calcular,
        .btn-pdf,
        .btn-enviar,
        .btn-autorizar,
        .btn-cancelar {
            background-color: #2b2d7f;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            margin: 5px;
        }

        .btn-calcular:hover,
        .btn-pdf:hover {
            background-color: #2b2d7f;
        }

        .btn-pdf {
            background-color: #007BFF;
        }

        .btn-enviar {
            background-color: #2b2d7f;
        }

        .btn-autorizar {
            background-color: green;
        }

        .btn-cancelar {
            background-color: #dc3545;
        }

        .btn-autorizar:disabled,
        .btn-cancelar:disabled {
            background-color: #ccc !important;
            cursor: not-allowed !important;
            opacity: 0.6;
        }

        .btn-pdf:hover {
            background-color: #0056b3;
        }

        .btn-cancelar:hover {
            background-color: #c82333;
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
    <div class="mx-4">
        <div class="row">
            <div class="mb-5">
                <a type="submit" class="btn btn-danger" href="ordenes_compra.php">Regresar</a>
            </div>
        </div>
    </div>

    <div class="form-container">
        <h2>Detalles de la Orden de Compra #<?php echo htmlspecialchars($id_compra); ?></h2>

        <h3>Información de la Orden</h3>
        <table>
            <tr>
                <th>Proveedor</th>
                <th>Fecha de Solicitud</th>
                <th>Monto Total</th>
                <th>Factura</th>
                <th>Estatus</th>
            </tr>
            <tr>
                <td><?php echo htmlspecialchars($orden['id_prov']) . " - " . htmlspecialchars($orden['nom_prov']); ?></td>
                <td><?php echo htmlspecialchars($orden['fecha_solicitud']); ?></td>
                <td><?php echo htmlspecialchars($orden['monto']); ?></td>
                <td><?php echo htmlspecialchars($orden['factura']); ?></td>
                <td><?php echo htmlspecialchars($orden['estatus']); ?></td>
            </tr>
        </table>

        <h3>Productos en la Orden</h3>
        <table>
            <tr>
                <th>ID Ítem</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Código</th>
                <th>Costo</th>
                <th>Solicita</th>
                <th>Entregado</th>
                <th>Fecha Entrega</th>

            </tr>

            <?php
            if ($result_detalles->num_rows > 0) {
                while ($detalle = $result_detalles->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . (isset($detalle['item_id']) ? htmlspecialchars($detalle['item_id']) : 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($detalle['item_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($detalle['item_grams']) . "</td>";
                    echo "<td>" . htmlspecialchars($detalle['item_code']) . "</td>";
                    echo "<td>$" . htmlspecialchars(number_format($detalle['item_costs'], 2)) . "</td>";
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
                echo "<tr><td colspan='8'>No se encontraron productos en esta orden de compra.</td></tr>";
            }
            ?>
        </table>

        <div style="text-align: center; margin-top: 20px;">
            <a class="btn-pdf" href="genera_pdf_detalle.php?id_compra=<?php echo $id_compra; ?>">Ver PDF</a>
            <a class="btn-enviar" href="enviar_pdf.php?id_compra=<?php echo $id_compra; ?>">Enviar PDF</a>
            <?php if ($orden['estatus'] == 'PENDIENTE'): ?>
                <button type="button" class="btn-autorizar" onclick="confirmarAutorizacion()">Autorizar</button>
                <button type="button" class="btn-cancelar" onclick="confirmarCancelacion()">Cancelar</button>
            <?php elseif ($orden['estatus'] == 'AUTORIZADO'): ?>
                <button type="button" class="btn-autorizar" disabled style="background-color: #ccc; cursor: not-allowed;">
                    Autorizado
                </button>
                <button type="button" class="btn-cancelar" onclick="confirmarCancelacion()">Cancelar</button>
            <?php elseif ($orden['estatus'] == 'CANCELADO'): ?>
                <button type="button" class="btn-autorizar" disabled style="background-color: #ccc; cursor: not-allowed;">
                    Autorizar (Cancelado)
                </button>
                <button type="button" class="btn-cancelar" disabled style="background-color: #ccc; cursor: not-allowed;">
                    Cancelado
                </button>
            <?php else: ?>
                <button type="button" class="btn-autorizar" disabled style="background-color: #ccc; cursor: not-allowed;">
                    Autorizar (<?php echo htmlspecialchars($orden['estatus']); ?>)
                </button>
                <button type="button" class="btn-cancelar" disabled style="background-color: #ccc; cursor: not-allowed;">
                    Cancelar (<?php echo htmlspecialchars($orden['estatus']); ?>)
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

</body>

</html>