<?php
require 'conexion.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: listar_segmento_posterior.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM segmento_posterior WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$registro = $resultado->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $papila = $_POST['papila'];
    $retina = $_POST['retina'];
    $macula = $_POST['macula'];
    $vasos_retinianos = $_POST['vasos_retinianos'];
    $observaciones = $_POST['observaciones'];

    $stmt = $conn->prepare("UPDATE segmento_posterior SET papila=?, retina=?, macula=?, vasos_retinianos=?, observaciones=? WHERE id=?");
    $stmt->bind_param("sssssi", $papila, $retina, $macula, $vasos_retinianos, $observaciones, $id);

    if ($stmt->execute()) {
        header("Location: listar_segmento_posterior.php?mensaje=editado");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Segmento Posterior</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Editar Exploración - Segmento Posterior</h2>
    <form method="POST" class="mt-4">
        <div class="mb-3">
            <label>Papila</label>
            <input type="text" name="papila" class="form-control" value="<?= htmlspecialchars($registro['papila']) ?>">
        </div>
        <div class="mb-3">
            <label>Retina</label>
            <input type="text" name="retina" class="form-control" value="<?= htmlspecialchars($registro['retina']) ?>">
        </div>
        <div class="mb-3">
            <label>Mácula</label>
            <input type="text" name="macula" class="form-control" value="<?= htmlspecialchars($registro['macula']) ?>">
        </div>
        <div class="mb-3">
            <label>Vasos Retinianos</label>
            <input type="text" name="vasos_retinianos" class="form-control" value="<?= htmlspecialchars($registro['vasos_retinianos']) ?>">
        </div>
        <div class="mb-3">
            <label>Observaciones</label>
            <textarea name="observaciones" class="form-control"><?= htmlspecialchars($registro['observaciones']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="listar_segmento_posterior.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
