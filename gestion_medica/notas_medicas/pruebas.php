<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli('localhost', 'root', '', 'u542863078_ineo');
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Recoger todos los datos
    $tipo_prueba = $_POST['tipo_prueba'] ?? '';
    $resultado = $_POST['resultado'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $observaciones = $_POST['observaciones'] ?? '';
    $estrabismo = $_POST['estrabismo'] ?? '';
    $ojo_preferente = $_POST['ojo_preferente'] ?? '';
    $mov_oculares = $_POST['mov_oculares'] ?? '';
    $convergencia = $_POST['convergencia'] ?? '';
    $prueba_cover = $_POST['prueba_cover'] ?? '';
    $vision_estereo = $_POST['vision_estereo'] ?? '';
    $worth = $_POST['worth'] ?? '';
    $schirmer = $_POST['schirmer'] ?? '';
    $trpl = $_POST['trpl'] ?? '';
    $fluoresceina = $_POST['fluoresceina'] ?? '';
    $contraste = $_POST['contraste'] ?? '';
    $ishihara = $_POST['ishihara'] ?? '';
    $farnsworth = $_POST['farnsworth'] ?? '';
    $amsler1 = $_POST['amsler1'] ?? '';
    $amsler2 = $_POST['amsler2'] ?? '';

    $sql = "INSERT INTO pruebas_medicas (
        tipo_prueba, resultado, fecha, observaciones, estrabismo, ojo_preferente, mov_oculares, convergencia,
        prueba_cover, vision_estereo, worth, schirmer, trpl, fluoresceina, contraste, ishihara, farnsworth, amsler1, amsler2
    ) VALUES (
        '$tipo_prueba', '$resultado', '$fecha', '$observaciones', '$estrabismo', '$ojo_preferente', '$mov_oculares', '$convergencia',
        '$prueba_cover', '$vision_estereo', '$worth', '$schirmer', '$trpl', '$fluoresceina', '$contraste', '$ishihara', '$farnsworth', '$amsler1', '$amsler2'
    )";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Prueba médica registrada correctamente');</script>";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro Clínico de Pruebas Médicas</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

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

        .field-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 700;
            margin-bottom: 6px;
            color: #34495e;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px 14px;
            font-size: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: border-color 0.3s;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #3498db;
            outline: none;
        }

        textarea {
            resize: vertical;
        }

        .inline-radio {
            display: flex;
            gap: 20px;
            margin-top: 5px;
        }

        .button-group {
            text-align: center;
            margin-top: 30px;
        }

        button {
            background-color: #1abc9c;
            color: white;
            padding: 12px 28px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #16a085;
        }

        .btn-secondary {
            background-color: #e74c3c;
        }

        .btn-secondary:hover {
            background-color: #c0392b;
        }

        .row {
            display: flex;
            gap: 20px;
        }

        .row .field-group {
            flex: 1;
        }

        @media (max-width: 768px) {
            .row {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<h2>Formulario Clínico de Pruebas Médicas y Oftalmológicas</h2>

<form method="post">
    <!-- PRUEBAS GENERALES -->
    <div class="field-group">
        <label>Tipo de Prueba</label>
        <select name="tipo_prueba" required>
            <option value="">Seleccione una prueba</option>
            <option value="Hemoglobina">Hemoglobina</option>
            <option value="Glucosa">Glucosa</option>
            <option value="Examen de orina">Examen de orina</option>
            <option value="Prueba visual">Prueba visual</option>
            <option value="Otras">Otras</option>
        </select>
    </div>

    <div class="row">
        <div class="field-group">
            <label>Resultado</label>
            <input type="text" name="resultado" placeholder="Ej. 120 mg/dL" required>
        </div>
        <div class="field-group">
            <label>Fecha de la prueba</label>
            <input type="date" name="fecha" required>
        </div>
    </div>

    <div class="field-group">
        <label>Observaciones</label>
        <textarea name="observaciones" rows="3" placeholder="Observaciones clínicas relevantes..."></textarea>
    </div>

    <!-- CAMPOS OFTALMOLÓGICOS -->
    <div class="field-group"><label>Estrabismo</label><input type="text" name="estrabismo"></div>

    <div class="field-group">
        <label>Ojo Preferente</label>
        <div class="inline-radio">
            <label><input type="radio" name="ojo_preferente" value="OD"> OD</label>
            <label><input type="radio" name="ojo_preferente" value="OI"> OI</label>
        </div>
    </div>

    <div class="row">
        <div class="field-group"><label>Movimientos Oculares</label><input type="text" name="mov_oculares"></div>
        <div class="field-group"><label>Convergencia Ocular</label><input type="text" name="convergencia"></div>
    </div>

    <div class="row">
        <div class="field-group"><label>Prueba Cover</label><input type="text" name="prueba_cover"></div>
        <div class="field-group"><label>Visión Estereoscópica</label><input type="text" name="vision_estereo"></div>
    </div>

    <div class="row">
        <div class="field-group"><label>Puntos de Worth</label><input type="text" name="worth"></div>
        <div class="field-group"><label>Prueba de Schirmer (mm)</label><input type="text" name="schirmer"></div>
    </div>

    <div class="row">
        <div class="field-group"><label>TRPL (segundos)</label><input type="text" name="trpl"></div>
        <div class="field-group"><label>Tinción con Fluoresceína</label><input type="text" name="fluoresceina"></div>
    </div>

    <div class="row">
        <div class="field-group"><label>Sensibilidad al Contraste</label><input type="text" name="contraste"></div>
        <div class="field-group"><label>Prueba Ishihara</label><input type="text" name="ishihara"></div>
    </div>

    <div class="field-group"><label>Prueba Farnsworth-Munsell</label><input type="text" name="farnsworth"></div>

    <div class="field-group">
        <label>Prueba Amsler</label>
        <div class="row">
            <select name="amsler1">
                <option value="">Resultado</option>
                <option value="Normal">Normal</option>
                <option value="Anormal">Anormal</option>
            </select>
            <select name="amsler2">
                <option value="">Ojo</option>
                <option value="OD">OD</option>
                <option value="OI">OI</option>
            </select>
        </div>
    </div>

    <div class="button-group">
        <button type="submit">Guardar Prueba</button>
        <a href="menu_medico.php"><button type="button" class="btn-secondary">Regresar</button></a>
    </div>
</form>

</body>
</html>
