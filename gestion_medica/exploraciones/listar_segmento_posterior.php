<?php
require 'conexion.php';
include("../includes/header.php");
include("../includes/sidebar.php");

$fecha_inicio = $_GET['fecha_inicio'] ?? null;
$fecha_fin = $_GET['fecha_fin'] ?? null;

$sql = "SELECT * FROM segmento_posterior WHERE 1=1";
if ($fecha_inicio && $fecha_fin) {
    $sql .= " AND DATE(fecha_registro) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
}
$sql .= " ORDER BY fecha_registro DESC";

$resultado = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <title>Listado - Segmento Posterior</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap 3.3.2 -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Theme -->
    <link href="dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <link href="dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

    <!-- Tus estilos personalizados -->
    <style>
        .container {
            padding-top: 20px;
        }
        .table th, .table td {
            font-size: 13px;
        }
        .btn {
            margin-right: 4px;
        }
    </style>
</head>

<body class=" hold-transition skin-blue sidebar-mini">


<div class="container mt-5">
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <label>Desde</label>
            <input type="date" name="fecha_inicio" class="form-control" value="<?= htmlspecialchars($fecha_inicio) ?>">
        </div>
        <div class="col-md-4">
            <label>Hasta</label>
            <input type="date" name="fecha_fin" class="form-control" value="<?= htmlspecialchars($fecha_fin) ?>">
        </div>
        <div class="col-md-4 align-self-end">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="listar_segmento_posterior.php" class="btn btn-secondary">Limpiar</a>
        </div>
    </form>

    <h2 class="mb-4">Listado de Exploraciones - Segmento Posterior</h2>
    <a href="formulario_segmento_posterior.php" class="btn btn-primary mb-3">Nueva exploración</a>

    <?php if ($resultado->num_rows > 0): ?>
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Papila</th>
                    <th>Retina</th>
                    <th>Mácula</th>
                    <th>Vasos</th>
                    <th>Observaciones</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($fila = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?= $fila["id"] ?></td>
                        <td><?= htmlspecialchars($fila["papila"]) ?></td>
                        <td><?= htmlspecialchars($fila["retina"]) ?></td>
                        <td><?= htmlspecialchars($fila["macula"]) ?></td>
                        <td><?= htmlspecialchars($fila["vasos_retinianos"]) ?></td>
                        <td><?= nl2br(htmlspecialchars($fila["observaciones"])) ?></td>
                        <td><?= $fila["fecha_registro"] ?></td>
                        <td>
                            <a href="editar_segmento_posterior.php?id=<?= $fila["id"] ?>" class="btn btn-sm btn-warning">Editar</a>
                            <a href="eliminar_segmento_posterior.php?id=<?= $fila["id"] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este registro?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">No hay registros aún.</div>
    <?php endif; ?>
</div>
<!-- scripts necesarios en tu footer o al final del body -->
<script src="plugins/jQuery/jquery-2.1.3.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="dist/js/app.min.js"></script>

</body>
</html>
