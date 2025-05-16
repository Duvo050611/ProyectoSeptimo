<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli('localhost', 'root', '', 'u542863078_ineo');

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO exploracion_fisica (
        peso, talla, imc, cintura, presion_sistolica, presion_diastolica, frecuencia_cardiaca, 
        frecuencia_respiratoria, temperatura, spo2, glucemia, glucosa_ayunas, dificultad, 
        tipo_dificultad, grado_dificultad, origen_dificultad, tuberculosis_probable, habito_exterior
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "ddddiiiiddidisssss",
        $_POST['peso'],
        $_POST['talla'],
        $_POST['imc'],
        $_POST['cintura'],
        $_POST['presion_sistolica'],
        $_POST['presion_diastolica'],
        $_POST['frecuencia_cardiaca'],
        $_POST['frecuencia_respiratoria'],
        $_POST['temperatura'],
        $_POST['spo2'],
        $_POST['glucemia'],
        $glucosa_ayunas = isset($_POST['glucosa_ayunas']) ? 1 : 0,
        $dificultad = isset($_POST['dificultad']) ? 1 : 0,
        $_POST['tipo_dificultad'],
        $_POST['grado_dificultad'],
        $_POST['origen_dificultad'],
        $_POST['tuberculosis_probable'],
        $_POST['habito_exterior']
    );

    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo "<script>alert('Exploración física guardada correctamente');</script>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Exploración Física</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #ecf0f3;
            margin: 0;
            padding: 0;
        }
        h2 {
            text-align: center;
            padding: 20px;
            background-color: #2d3e50;
            color: white;
            margin: 0;
            border-bottom: 3px solid #1abc9c;
        }
        form {
            background: white;
            max-width: 1000px;
            margin: 40px auto;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 12px 28px rgba(0,0,0,0.1);
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }
        .field {
            flex: 1;
            min-width: 180px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #2d3e50;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        .radio-group {
            display: flex;
            gap: 10px;
            margin-top: 5px;
        }
        .actions {
            text-align: center;
            margin-top: 30px;
        }
        button {
            padding: 14px 30px;
            background: #1abc9c;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #17a88b;
        }
    </style>
</head>
<body>

<h2>Formulario de Exploración Física</h2>
<form method="post">

    <div class="row">
        <div class="field"><label>Peso (kg)</label><input type="number" step="0.01" name="peso" required></div>
        <div class="field"><label>Talla (cm)</label><input type="number" step="0.01" name="talla" required></div>
        <div class="field"><label>IMC</label><input type="number" step="0.01" name="imc"></div>
    </div>

    <div class="row">
        <div class="field"><label>Circunferencia de cintura (cm)</label><input type="number" step="0.01" name="cintura"></div>
        <div class="field"><label>Presión sistólica (mm Hg)</label><input type="number" name="presion_sistolica"></div>
        <div class="field"><label>Presión diastólica (mm Hg)</label><input type="number" name="presion_diastolica"></div>
    </div>

    <div class="row">
        <div class="field"><label>Frecuencia cardíaca (x')</label><input type="number" name="frecuencia_cardiaca"></div>
        <div class="field"><label>Frecuencia respiratoria (x')</label><input type="number" name="frecuencia_respiratoria"></div>
        <div class="field"><label>Temperatura (°C)</label><input type="number" step="0.1" name="temperatura"></div>
    </div>

    <div class="row">
        <div class="field"><label>SpO₂ (%)</label><input type="number" name="spo2"></div>
        <div class="field"><label>Glucemia (mg/dL)</label><input type="number" step="0.01" name="glucemia"></div>
    </div>

    <div class="row">
        <div class="field">
            <label>¿Glucosa medida en ayunas?</label>
            <div class="radio-group">
                <label><input type="radio" name="glucosa_ayunas" value="1"> Sí</label>
                <label><input type="radio" name="glucosa_ayunas" value="0" checked> No</label>
            </div>
        </div>

        <div class="field">
            <label>¿El paciente tiene alguna dificultad?</label>
            <div class="radio-group">
                <label><input type="radio" name="dificultad" value="1"> Sí</label>
                <label><input type="radio" name="dificultad" value="0" checked> No</label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="field"><label>Tipo de dificultad</label><input name="tipo_dificultad"></div>
        <div class="field"><label>Grado</label><input name="grado_dificultad"></div>
        <div class="field"><label>Origen</label><input name="origen_dificultad"></div>
    </div>

    <div class="row">
        <div class="field">
            <label>Tuberculosis Pulmonar probable</label>
            <select name="tuberculosis_probable">
                <option value="SI">SI</option>
                <option value="NO" selected>NO</option>
                <option value="SE DESCONOCE">SE DESCONOCE</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="field" style="flex: 100%;">
            <label>Hábito Exterior</label>
            <textarea name="habito_exterior" rows="3"></textarea>
        </div>
    </div>

    <div class="actions">
        <button type="submit">Guardar</button>
    </div>
</form>

</body>
</html>
