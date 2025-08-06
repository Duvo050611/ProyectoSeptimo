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

        h1 {
            color: #2b2d7f;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #ffffff;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
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

        input[type="submit"] {
            background-color: #2b2d7f;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background-color: #2b2d7f;
        }

        .scrollable-area {
            overflow-x: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            background-color: #ffffff;
            white-space: nowrap;
        }

        .scrollable-area table {
            width: 100%;
            table-layout: auto;
        }

        .pagination {
            display: flex;
            justify-content: center;
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            padding: 8px 12px;
            text-decoration: none;
            background-color: #2b2d7f;
            color: white;
            border-radius: 5px;
            margin: 0 5px;
        }

        .pagination a:hover {
            background-color: #2b2d7f;
        }

        .pagination .current {
            background-color: #ff7f50;
            color: white;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="mx-4">
        <div class="row">
            <div class="mb-5">
                <a type="submit" class="btn btn-danger" href="../../template/menu_farmaciacentral.php">Regresar</a>
                <a type="submit" class="btn btn-success" href="generar_oc_libre.php">Crear orden de compra libre</a>
                <a type="submit" class="btn btn-primary" href="genera_oc_proveedor.php">Crear orden de compra por proveedor</a>
                <a type="submit" class="btn btn-warning" href="ordenes_compra_his.php">Histórico de entregas</a>
            </div>
        </div>
    </div>

    <!-- Filtro por estatus -->
    <div style="text-align: center; margin: 20px 0;">
        <form method="GET" action="">
            <label for="estatus" style="font-weight: bold; color: #2b2d7f; margin-right: 10px;">Filtrar por estatus:</label>
            <select name="estatus" id="estatus" style="padding: 8px; border: 1px solid #ddd; border-radius: 5px; margin-right: 10px;">
                <option value="">Todos (excepto ENTREGADO)</option>
                <option value="PENDIENTE" <?php echo ($filtro_estatus == 'PENDIENTE') ? 'selected' : ''; ?>>PENDIENTE</option>
                <option value="AUTORIZADO" <?php echo ($filtro_estatus == 'AUTORIZADO') ? 'selected' : ''; ?>>AUTORIZADO</option>
                <option value="CANCELADO" <?php echo ($filtro_estatus == 'CANCELADO') ? 'selected' : ''; ?>>CANCELADO</option>
                <option value="PARCIAL" <?php echo ($filtro_estatus == 'PARCIAL') ? 'selected' : ''; ?>>PARCIAL</option>
            </select>
            <input type="submit" value="Filtrar" style="background-color: #2b2d7f; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer;">
        </form>
    </div>

    <h1>Órdenes de compra<?php echo !empty($filtro_estatus) ? ' - ' . $filtro_estatus : ' (excepto ENTREGADO)'; ?></h1>
    <table>
        <tr>
            <th>ID Compra</th>
            <th>Proveedor</th>
            <th>Fecha de Solicitud</th>
            <th>Monto</th>
            <th>Descuento</th>
            <th>IVA</th>
            <th>Total</th>
            <th>Activo</th>
            <th>Estatus</th> <!-- Nueva columna para Estatus -->
            <th>Detalles</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $prov = $row['id_prov'];

                echo "<tr>";
                echo "<td>" . $row['id_compra'] . "</td>";
                echo "<td>" . $prov . "</td>";
                echo "<td>" . $row['fecha_solicitud'] . "</td>";
                echo "<td>" . $row['monto'] . "</td>";
                echo "<td>" . $row['descuento'] . "</td>";
                echo "<td>" . $row['iva'] . "</td>";
                echo "<td>" . $row['total'] . "</td>";
                echo "<td>" . ($row['activo'] ? 'Sí' : 'No') . "</td>";
                if ($row['estatus'] == "PENDIENTE") {
                    echo "<td><strong>" . $row['estatus'] . "</strong></td>";
                } else {
                    echo "<td>" . $row['estatus'] . "</td>";
                }
                echo "<td><a href='detalles_orden.php?id_compra=" . $row['id_compra'] . "'>Ver Detalles</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='11'>No hay órdenes de compra disponibles.</td></tr>";
        }
        ?>
    </table>

    <!-- Paginación -->
    <?php if ($total_paginas > 0): ?>
        <div class="pagination">
            <?php
            // Enlace a la primera página
            if ($pagina > 1) {
                echo "<a href='?pagina=1&estatus=$filtro_estatus'>&laquo; Primera</a>";
            }

            // Páginas cercanas a la actual
            $pagina_inicio = max(1, $pagina - 5);
            $pagina_fin = min($total_paginas, $pagina + 5);

            for ($i = $pagina_inicio; $i < $pagina; $i++) {
                echo "<a href='?pagina=$i&estatus=$filtro_estatus'>$i</a>";
            }

            // Página actual
            echo "<a href='?pagina=$pagina&estatus=$filtro_estatus' class='current'>$pagina</a>";

            for ($i = $pagina + 1; $i <= $pagina_fin; $i++) {
                echo "<a href='?pagina=$i&estatus=$filtro_estatus'>$i</a>";
            }

            // Enlace a la última página
            if ($pagina < $total_paginas) {
                echo "<a href='?pagina=$total_paginas&estatus=$filtro_estatus'>Última &raquo;</a>";
            }
            ?>
        </div>
    <?php endif; ?>

</body>

</html>