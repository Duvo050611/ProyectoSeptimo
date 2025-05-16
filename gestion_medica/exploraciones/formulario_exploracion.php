<?php
include("../includes/header.php");
include("../includes/sidebar.php");
require 'conexion.php';
$pacientes = $conn->query("SELECT id_exp, nom_pac, papell FROM paciente");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario - Exploración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Nueva Exploración: Párpados, Órbita y Vías Lagrimales</h4>
        </div>
        <div class="card-body">
            <form action="procesar_formulario.php" method="POST">
                <!-- Selección de paciente -->
                <div class="mb-3">
                    <label for="id_exp" class="form-label">Paciente</label>
                    <select class="form-select" name="id_exp" id="id_exp" required>
                        <option value="">Seleccione un paciente</option>
                        <?php while ($paciente = $pacientes->fetch_assoc()): ?>
                            <option value="<?= $paciente['id_exp'] ?>">
                                <?= htmlspecialchars($paciente['nom_pac']) ?> (ApPat: <?= htmlspecialchars($paciente['papell']) ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Ojo Derecho -->
                <h5>Ojo Derecho</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Apertura Palpebral (mm)</label>
                        <input type="number" step="0.01" name="apertura_palpebral" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Hendidura Palpebral (mm)</label>
                        <input type="number" step="0.01" name="hendidura_palpebral" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Función del Músculo Elevador (mm)</label>
                        <input type="number" step="0.01" name="funcion_musculo_elevador" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Fenómeno de Bell</label>
                        <select name="fenomeno_bell" class="form-select">
                            <option value="">Seleccione</option>
                            <option value="Normal">Normal</option>
                            <option value="Patológico">Patológico</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Laxitud Horizontal</label>
                        <select name="laxitud_horizontal" class="form-select">
                            <option value="">Seleccione</option>
                            <option value="Normal">Normal</option>
                            <option value="Leve">Leve</option>
                            <option value="Moderada">Moderada</option>
                            <option value="Severa">Severa</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Laxitud Vertical</label>
                        <select name="laxitud_vertical" class="form-select">
                            <option value="">Seleccione</option>
                            <option value="Normal">Normal</option>
                            <option value="Leve">Leve</option>
                            <option value="Moderada">Moderada</option>
                            <option value="Severa">Severa</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Desplazamiento Ocular</label>
                        <select name="desplazamiento_ocular" class="form-select">
                            <option value="">Seleccione</option>
                            <option value="Enoftalmos">Enoftalmos</option>
                            <option value="Exoftalmos">Exoftalmos</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Maniobra de Valsalva</label>
                        <select name="maniobra_vatsaha" class="form-select">
                            <option value="">Seleccione</option>
                            <option value="Sí">Sí</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>

                <!-- Ojo Izquierdo -->
                <h5 class="mt-4">Ojo Izquierdo</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Apertura Palpebral (mm)</label>
                        <input type="number" step="0.01" name="apertura_palpebral_oi" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Hendidura Palpebral (mm)</label>
                        <input type="number" step="0.01" name="hendidura_palpebral_oi" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Función del Músculo Elevador (mm)</label>
                        <input type="number" step="0.01" name="funcion_musculo_elevador_oi" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Fenómeno de Bell</label>
                        <select name="fenomeno_bell_oi" class="form-select">
                            <option value="">Seleccione</option>
                            <option value="Normal">Normal</option>
                            <option value="Patológico">Patológico</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Laxitud Horizontal</label>
                        <select name="laxitud_horizontal_oi" class="form-select">
                            <option value="">Seleccione</option>
                            <option value="Normal">Normal</option>
                            <option value="Leve">Leve</option>
                            <option value="Moderada">Moderada</option>
                            <option value="Severa">Severa</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Laxitud Vertical</label>
                        <select name="laxitud_vertical_oi" class="form-select">
                            <option value="">Seleccione</option>
                            <option value="Normal">Normal</option>
                            <option value="Leve">Leve</option>
                            <option value="Moderada">Moderada</option>
                            <option value="Severa">Severa</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Desplazamiento Ocular</label>
                        <select name="desplazamiento_ocular_oi" class="form-select">
                            <option value="">Seleccione</option>
                            <option value="Enoftalmos">Enoftalmos</option>
                            <option value="Exoftalmos">Exoftalmos</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Maniobra de Valsalva</label>
                        <select name="maniobra_vatsaha_oi" class="form-select">
                            <option value="">Seleccione</option>
                            <option value="Sí">Sí</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="mb-3">
                    <label for="observaciones" class="form-label">Observaciones</label>
                    <textarea class="form-control" name="observaciones" rows="4" placeholder="Detalles adicionales del examen..."></textarea>
                </div>

                <!-- Botones -->
                <button type="submit" class="btn btn-success">Guardar</button>
                <a href="listar_exploraciones.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
