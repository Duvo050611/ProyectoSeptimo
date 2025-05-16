<?php include 'conexion.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Exploración - Segmento Anterior</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2 class="mb-4">Registrar Segmento Anterior</h2>
  <form action="guardar_segmento.php" method="POST">
    <div class="row">
      <div class="col-md-6 mb-3">
        <label>Córnea</label>
        <select name="cornea" class="form-control" required>
          <option value="">Seleccione</option>
          <option value="Transparente">Transparente</option>
          <option value="Edematosa">Edematosa</option>
          <option value="Opaca">Opaca</option>
          <option value="Úlcera">Úlcera</option>
        </select>
      </div>

      <div class="col-md-6 mb-3">
        <label>Conjuntiva</label>
        <select name="conjuntiva" class="form-control" required>
          <option value="">Seleccione</option>
          <option value="Normal">Normal</option>
          <option value="Hiperemia">Hiperemia</option>
          <option value="Hemorragia">Hemorragia</option>
          <option value="Papilas">Papilas</option>
        </select>
      </div>

      <div class="col-md-6 mb-3">
        <label>Cámara anterior</label>
        <select name="camara_anterior" class="form-control" required>
          <option value="">Seleccione</option>
          <option value="Profunda">Profunda</option>
          <option value="Media">Media</option>
          <option value="Plana">Plana</option>
          <option value="Con células">Con células</option>
          <option value="Con flare">Con flare</option>
        </select>
      </div>

      <div class="col-md-6 mb-3">
        <label>Pupila</label>
        <select name="pupila" class="form-control" required>
          <option value="">Seleccione</option>
          <option value="Isocórica">Isocórica</option>
          <option value="Anisocórica">Anisocórica</option>
          <option value="Reactiva a la luz">Reactiva a la luz</option>
          <option value="No reactiva">No reactiva</option>
          <option value="Midriática">Midriática</option>
          <option value="Miótica">Miótica</option>
        </select>
      </div>

      <div class="col-md-6 mb-3">
        <label>Iris</label>
        <select name="iris" class="form-control" required>
          <option value="">Seleccione</option>
          <option value="Normal">Normal</option>
          <option value="Atrófico">Atrófico</option>
          <option value="Sinequias">Sinequias</option>
          <option value="Neovascularización">Neovascularización</option>
        </select>
      </div>

      <div class="col-md-12 mb-3">
        <label>Observaciones adicionales</label>
        <textarea name="observaciones" class="form-control" rows="3" placeholder="Observaciones clínicas..."></textarea>
      </div>

      <div class="col-md-12 text-end">
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="listar_segmento.php" class="btn btn-secondary">Cancelar</a>
      </div>
    </div>
  </form>
</div>
</body>
</html>
