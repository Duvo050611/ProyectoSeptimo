<?php
include 'conexion.php';
include("../includes/header.php");
include("../includes/sidebar.php");
$fecha_inicio = $_GET['fecha_inicio'] ?? null;
$fecha_fin = $_GET['fecha_fin'] ?? null;

$sql = "SELECT * FROM segmento_anterior WHERE 1=1";

if ($fecha_inicio && $fecha_fin) {
    $sql .= " AND DATE(fecha_registro) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
}

$sql .= " ORDER BY fecha_registro DESC";

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Segmento Anterior</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Exploraciones del Segmento Anterior</h2>

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
            <a href="listar_segmento.php" class="btn btn-secondary">Limpiar</a>
        </div>
    </form>

    <a href="formulario_segmento.php" class="btn btn-success mb-3">Nueva exploración</a>

    <?php if ($resultado && $resultado->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Córnea</th>
                        <th>Conjuntiva</th>
                        <th>Cámara Anterior</th>
                        <th>Pupila</th>
                        <th>Iris</th>
                        <th>Observaciones</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($fila = $resultado->fetch_assoc()): ?>
<tr>
    <td><?= $fila["id"] ?></td>
    <td><?= $fila["cornea"] ?></td>
    <td><?= $fila["conjuntiva"] ?></td>
    <td><?= $fila["camara_anterior"] ?></td>
    <td><?= $fila["pupila"] ?></td>
    <td><?= $fila["iris"] ?></td>
    <td><?= nl2br(htmlspecialchars($fila["observaciones"])) ?></td>
    <td><?= $fila["fecha_registro"] ?></td>
    <td>
        <a href="editar_segmento.php?id=<?= $fila['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
        <a href="eliminar_segmento.php?id=<?= $fila['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este registro?');">Eliminar</a>
    </td>
</tr>

                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">No hay registros encontrados.</div>
    <?php endif; ?>
</div>
</body>
</html>
