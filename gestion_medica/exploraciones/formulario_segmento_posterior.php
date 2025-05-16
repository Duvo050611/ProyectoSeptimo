<?php include 'conexion.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Segmento Posterior del Ojo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Exploración - Segmento Posterior del Ojo</h2>

    <form action="guardar_segmento_posterior.php" method="POST">
        <div class="mb-3">
            <label for="papila" class="form-label">¿La papila óptica se observa normal?</label>
            <select name="papila" id="papila" class="form-select" required>
                <option value="">Seleccione</option>
                <option value="Normal">Normal</option>
                <option value="Edematosa">Edematosa</option>
                <option value="Pálida">Pálida</option>
                <option value="Excavación aumentada">Excavación aumentada</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="retina" class="form-label">¿Se observa alguna alteración en la retina?</label>
            <select name="retina" id="retina" class="form-select" required>
                <option value="">Seleccione</option>
                <option value="Normal">Normal</option>
                <option value="Desprendimiento">Desprendimiento</option>
                <option value="Hemorragias">Hemorragias</option>
                <option value="Exudados">Exudados</option>
                <option value="Degeneración">Degeneración</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="macula" class="form-label">¿Cuál es el estado de la mácula?</label>
            <select name="macula" id="macula" class="form-select" required>
                <option value="">Seleccione</option>
                <option value="Normal">Normal</option>
                <option value="Edema">Edema</option>
                <option value="Drusas">Drusas</option>
                <option value="Hemorragia">Hemorragia</option>
                <option value="Cicatriz">Cicatriz</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="vasos_retinianos" class="form-label">¿Cómo se observan los vasos retinianos?</label>
            <select name="vasos_retinianos" id="vasos_retinianos" class="form-select" required>
                <option value="">Seleccione</option>
                <option value="Normales">Normales</option>
                <option value="Tortuosos">Tortuosos</option>
                <option value="Engrosados">Engrosados</option>
                <option value="Microaneurismas">Microaneurismas</option>
                <option value="Neovasos">Neovasos</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="observaciones" class="form-label">Observaciones adicionales</label>
            <textarea name="observaciones" id="observaciones" rows="4" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="listar_segmento_posterior.php" class="btn btn-secondary">Ver registros</a>
    </form>
</div>
</body>
</html>
