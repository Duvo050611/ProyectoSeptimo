<?php
include 'conexion.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID no proporcionado.");
}

// Obtener datos actuales
$sql = "SELECT * FROM segmento_anterior WHERE id = $id";
$result = $conn->query($sql);
$registro = $result->fetch_assoc();

if (!$registro) {
    die("Registro no encontrado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Segmento Anterior</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2 class="mb-4">Editar Exploración - Segmento Anterior</h2>

  <form action="actualizar_segmento.php" method="POST">
    <input type="hidden" name="id" value="<?= $registro['id'] ?>">

    <div class="row">
      <!-- CORNEA -->
      <div class="col-md-6 mb-3">
        <label>Córnea</label>
        <select name="cornea" class="form-control" required>
          <option value="Transparente" <?= $registro['cornea'] == 'Transparente' ? 'selected' : '' ?>>Transparente</option>
          <option value="Edematosa" <?= $registro['cornea'] == 'Edematosa' ? 'selected' : '' ?>>Edematosa</option>
          <option value="Opaca" <?= $registro['cornea'] == 'Opaca' ? 'selected' : '' ?>>Opaca</option>
          <option value="Úlcera" <?= $registro['cornea'] == 'Úlcera' ? 'selected' : '' ?>>Úlcera</option>
        </select>
      </div>

      <!-- CONJUNTIVA -->
      <div class="col-md-6 mb-3">
        <label>Conjuntiva</label>
        <select name="conjuntiva" class="form-control" required>
          <option value="Normal" <?= $registro['conjuntiva'] == 'Normal' ? 'selected' : '' ?>>Normal</option>
          <option value="Hiperemia" <?= $registro['conjuntiva'] == 'Hiperemia' ? 'selected' : '' ?>>Hiperemia</option>
          <option value="Hemorragia" <?= $registro['conjuntiva'] == 'Hemorragia' ? 'selected' : '' ?>>Hemorragia</option>
          <option value="Papilas" <?= $registro['conjuntiva'] == 'Papilas' ? 'selected' : '' ?>>Papilas</option>
        </select>
      </div>

      <!-- CAMARA ANTERIOR -->
      <div class="col-md-6 mb-3">
        <label>Cámara anterior</label>
        <select name="camara_anterior" class="form-control" required>
          <option value="Profunda" <?= $registro['camara_anterior'] == 'Profunda' ? 'selected' : '' ?>>Profunda</option>
          <option value="Media" <?= $registro['camara_anterior'] == 'Media' ? 'selected' : '' ?>>Media</option>
          <option value="Plana" <?= $registro['camara_anterior'] == 'Plana' ? 'selected' : '' ?>>Plana</option>
          <option value="Con células" <?= $registro['camara_anterior'] == 'Con células' ? 'selected' : '' ?>>Con células</option>
          <option value="Con flare" <?= $registro['camara_anterior'] == 'Con flare' ? 'selected' : '' ?>>Con flare</option>
        </select>
      </div>

      <!-- PUPILA -->
      <div class="col-md-6 mb-3">
        <label>Pupila</label>
        <select name="pupila" class="form-control" required>
          <option value="Isocórica" <?= $registro['pupila'] == 'Isocórica' ? 'selected' : '' ?>>Isocórica</option>
          <option value="Anisocórica" <?= $registro['pupila'] == 'Anisocórica' ? 'selected' : '' ?>>Anisocórica</option>
          <option value="Reactiva a la luz" <?= $registro['pupila'] == 'Reactiva a la luz' ? 'selected' : '' ?>>Reactiva a la luz</option>
          <option value="No reactiva" <?= $registro['pupila'] == 'No reactiva' ? 'selected' : '' ?>>No reactiva</option>
          <option value="Midriática" <?= $registro['pupila'] == 'Midriática' ? 'selected' : '' ?>>Midriática</option>
          <option value="Miótica" <?= $registro['pupila'] == 'Miótica' ? 'selected' : '' ?>>Miótica</option>
        </select>
      </div>

      <!-- IRIS -->
      <div class="col-md-6 mb-3">
        <label>Iris</label>
        <select name="iris" class="form-control" required>
          <option value="Normal" <?= $registro['iris'] == 'Normal' ? 'selected' : '' ?>>Normal</option>
          <option value="Atrófico" <?= $registro['iris'] == 'Atrófico' ? 'selected' : '' ?>>Atrófico</option>
          <option value="Sinequias" <?= $registro['iris'] == 'Sinequias' ? 'selected' : '' ?>>Sinequias</option>
          <option value="Neovascularización" <?= $registro['iris'] == 'Neovascularización' ? 'selected' : '' ?>>Neovascularización</option>
        </select>
      </div>

      <!-- OBSERVACIONES -->
      <div class="col-md-12 mb-3">
        <label>Observaciones adicionales</label>
        <textarea name="observaciones" class="form-control" rows="3"><?= htmlspecialchars($registro['observaciones']) ?></textarea>
      </div>

      <div class="col-md-12 text-end">
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="listar_segmento.php" class="btn btn-secondary">Cancelar</a>
      </div>
    </div>
  </form>
</div>
</body>
</html>
