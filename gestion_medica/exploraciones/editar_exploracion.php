<?php
require 'conexion.php';

if (!isset($_GET['id'])) {
    die("ID no válido.");
}

$id = $_GET['id'];
$sql = "SELECT * FROM exploraciones WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$datos = $resultado->fetch_assoc();

if (!$datos) {
    die("Registro no encontrado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Exploración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Editar Exploración</h2>
    <form method="POST" action="actualizar_exploracion.php">
        <input type="hidden" name="id" value="<?= $datos['id'] ?>">

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Apertura Palpebral</label>
                <input type="number" step="0.01" name="apertura_palpebral" class="form-control" value="<?= htmlspecialchars($datos['apertura_palpebral']) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Hendidura Palpebral</label>
                <input type="number" step="0.01" name="hendidura_palpebral" class="form-control" value="<?= htmlspecialchars($datos['hendidura_palpebral']) ?>">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Función Músculo Elevador</label>
                <input type="number" step="0.01" name="funcion_musculo_elevador" class="form-control" value="<?= htmlspecialchars($datos['funcion_musculo_elevador']) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Fenómeno de Bell</label>
                <select name="fenomeno_bell" class="form-select">
                    <option value="Normal" <?= $datos['fenomeno_bell'] == 'Normal' ? 'selected' : '' ?>>Normal</option>
                    <option value="Patológico" <?= $datos['fenomeno_bell'] == 'Patológico' ? 'selected' : '' ?>>Patológico</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Laxitud Horizontal</label>
                <select name="laxitud_horizontal" class="form-select">
                    <?php foreach (['Normal', 'Leve', 'Moderada', 'Severa'] as $op): ?>
                        <option value="<?= $op ?>" <?= $datos['laxitud_horizontal'] == $op ? 'selected' : '' ?>><?= $op ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Laxitud Vertical</label>
                <select name="laxitud_vertical" class="form-select">
                    <?php foreach (['Normal', 'Leve', 'Moderada', 'Severa'] as $op): ?>
                        <option value="<?= $op ?>" <?= $datos['laxitud_vertical'] == $op ? 'selected' : '' ?>><?= $op ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Desplazamiento Ocular</label>
                <select name="desplazamiento_ocular" class="form-select">
                    <option value="Enoftalmos" <?= $datos['desplazamiento_ocular'] == 'Enoftalmos' ? 'selected' : '' ?>>Enoftalmos</option>
                    <option value="Exoftalmos" <?= $datos['desplazamiento_ocular'] == 'Exoftalmos' ? 'selected' : '' ?>>Exoftalmos</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Maniobra de Valsalva</label>
                <select name="maniobra_vatsaha" class="form-select">
                    <option value="Sí" <?= $datos['maniobra_vatsaha'] == 'Sí' ? 'selected' : '' ?>>Sí</option>
                    <option value="No" <?= $datos['maniobra_vatsaha'] == 'No' ? 'selected' : '' ?>>No</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Observaciones</label>
            <textarea name="observaciones" class="form-control"><?= htmlspecialchars($datos['observaciones']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="listar_exploraciones.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
