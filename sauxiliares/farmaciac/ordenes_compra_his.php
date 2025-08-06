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
            color: #2b2d7f;
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
            background-color: #0c675e;
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
            <a type="submit" class="btn btn-danger" href="ordenes_compra.php">Regresar</a>
        </div>
    </div>
</div>

<h1>Histórico de órdenes de Compra</h1>

<!-- Formulario de búsqueda -->
<form method="GET" action="">
    <div class="form-container">
        <label for="id_compra">Buscar por ID Compra:</label>
        <input type="text" name="id_compra" id="id_compra" value="<?= $id_compra ?>" placeholder="ID Compra">
        <input type="submit" color="#2b2d7f" value="Buscar">
    </div>
</form>

<!-- Tabla con los resultados -->
<div class="form-container">
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
            <th>Estatus</th>
            <th>Detalles</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id_compra'] . "</td>";
                echo "<td>" . $row['nom_prov'] . "</td>";  // Mostrar el nombre del proveedor
                echo "<td>" . $row['fecha_solicitud'] . "</td>";
                echo "<td>" . $row['monto'] . "</td>";
                echo "<td>" . $row['descuento'] . "</td>";
                echo "<td>" . $row['iva'] . "</td>";
                echo "<td>" . $row['total'] . "</td>";
                echo "<td>" . $row['activo']  . "</td>";
                echo "<td>" . $row['estatus'] . "</td>";
                echo "<td><a href='detalles_orden.php?id_compra=" . $row['id_compra'] . "'>Ver Detalles</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='10'>No hay órdenes de compra disponibles.</td></tr>";
        }
        ?>
    </table>
</div>

<!-- Paginación -->
<div class="pagination">
    <?php
    // Establecer el rango de páginas a mostrar
    $rango = 5;

    // Determinar el inicio y fin del rango de páginas a mostrar
    $inicio = max(1, $pagina - $rango);
    $fin = min($total_paginas, $pagina + $rango);

    // Mostrar el enlace a la página anterior
    if ($pagina > 1) {
        echo '<a href="?pagina=1&id_compra=' . urlencode($id_compra) . '">&laquo; Primero</a>';
        echo '<a href="?pagina=' . ($pagina - 1) . '&id_compra=' . urlencode($id_compra) . '">&lt; Anterior</a>';
    }

    // Mostrar las páginas del rango
    for ($i = $inicio; $i <= $fin; $i++) {
        echo '<a href="?pagina=' . $i . '&id_compra=' . urlencode($id_compra) . '" class="' . ($i == $pagina ? 'current' : '') . '">' . $i . '</a>';
    }

    // Mostrar el enlace a la página siguiente
    if ($pagina < $total_paginas) {
        echo '<a href="?pagina=' . ($pagina + 1) . '&id_compra=' . urlencode($id_compra) . '">Siguiente &gt;</a>';
        echo '<a href="?pagina=' . $total_paginas . '&id_compra=' . urlencode($id_compra) . '">Último &raquo;</a>';
    }
    ?>
</div>

</body>
</html>
