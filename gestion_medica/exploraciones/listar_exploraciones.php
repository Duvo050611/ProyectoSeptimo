<?php
require 'conexion.php';
include("../includes/header.php");
include("../includes/sidebar.php");

$fecha_inicio = $_GET['fecha_inicio'] ?? null;
$fecha_fin = $_GET['fecha_fin'] ?? null;

$sql = "SELECT * FROM exploraciones WHERE 1=1";

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
    <title>Exploraciones - Órbita y Vías Lagrimales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Exploraciones - Órbita y Vías Lagrimales</h2>

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
            <a href="listar_exploraciones.php" class="btn btn-secondary">Limpiar</a>
        </div>
    </form>

    <a href="formulario_exploracion.php" class="btn btn-success mb-3">Nueva exploración</a>

    <?php if ($resultado && $resultado->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>ID Paciente</th>
                        <th>Apertura Palpebral OD</th>
                        <th>Hendidura Palpebral OD</th>
                        <th>Función Músculo Elevador OD</th>
                        <th>Fenómeno Bell OD</th>
                        <th>Laxitud Horizontal OD</th>
                        <th>Laxitud Vertical OD</th>
                        <th>Desplazamiento Ocular OD</th>
                        <th>Maniobra de Vatsaha OD</th>
                        <th>Apertura Palpebral OI</th>
                        <th>Hendidura Palpebral OI</th>
                        <th>Función Músculo Elevador OI</th>
                        <th>Fenómeno Bell OI</th>
                        <th>Laxitud Horizontal OI</th>
                        <th>Laxitud Vertical OI</th>
                        <th>Desplazamiento Ocular OI</th>
                        <th>Maniobra de Vatsaha OI</th>
                        <th>Observaciones</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($fila = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?= $fila["id"] ?></td>
                        <td><?= $fila["id_exp"] ?></td>
                        <td><?= $fila["apertura_palpebral"] ?></td>
                        <td><?= $fila["hendidura_palpebral"] ?></td>
                        <td><?= $fila["funcion_musculo_elevador"] ?></td>
                        <td><?= htmlspecialchars($fila["fenomeno_bell"]) ?></td>
                        <td><?= htmlspecialchars($fila["laxitud_horizontal"]) ?></td>
                        <td><?= htmlspecialchars($fila["laxitud_vertical"]) ?></td>
                        <td><?= htmlspecialchars($fila["desplazamiento_ocular"]) ?></td>
                        <td><?= htmlspecialchars($fila["maniobra_vatsaha"]) ?></td>
                        <td><?= $fila["apertura_palpebral_oi"] ?></td>
                        <td><?= $fila["hendidura_palpebral_oi"] ?></td>
                        <td><?= $fila["funcion_musculo_elevador_oi"] ?></td>
                        <td><?= htmlspecialchars($fila["fenomeno_bell_oi"]) ?></td>
                        <td><?= htmlspecialchars($fila["laxitud_horizontal_oi"]) ?></td>
                        <td><?= htmlspecialchars($fila["laxitud_vertical_oi"]) ?></td>
                        <td><?= htmlspecialchars($fila["desplazamiento_ocular_oi"]) ?></td>
                        <td><?= htmlspecialchars($fila["maniobra_vatsaha_oi"]) ?></td>
                        <td><?= htmlspecialchars($fila["observaciones"]) ?></td>
                        <td><?= $fila["fecha_registro"] ?></td>
                        <td>
                            <a href="editar_exploracion.php?id=<?= $fila['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                            <a href="eliminar_exploracion.php?id=<?= $fila['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este registro?');">Eliminar</a>
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
