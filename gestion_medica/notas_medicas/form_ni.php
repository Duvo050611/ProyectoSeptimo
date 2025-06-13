<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli('localhost', 'root', '', 'u542863078_ineo');

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $ojos = ['Derecho', 'Izquierdo'];
    foreach ($ojos as $ojo) {
        $data = [
            'av_lejana' => $_POST["av_lejana_$ojo"] ?? null,
            'av_lejana_lentes' => $_POST["av_lejana_lentes_$ojo"] ?? null,
            'esf_lejana' => $_POST["esf_lejana_$ojo"] ?? null,
            'cil_lejana' => $_POST["cil_lejana_$ojo"] ?? null,
            'eje_lejana' => $_POST["eje_lejana_$ojo"] ?? null,
            'add_lejana' => $_POST["add_lejana_$ojo"] ?? null,
            'prisma_lejana' => isset($_POST["prisma_lejana_$ojo"]) ? 1 : 0,
            'av_cercana' => $_POST["av_cercana_$ojo"] ?? null,
            'esf_cercana' => $_POST["esf_cercana_$ojo"] ?? null,
            'cil_cercana' => $_POST["cil_cercana_$ojo"] ?? null,
            'eje_cercana' => $_POST["eje_cercana_$ojo"] ?? null,
            'add_cercana' => $_POST["add_cercana_$ojo"] ?? null,
            'prisma_cercana' => isset($_POST["prisma_cercana_$ojo"]) ? 1 : 0,
        ];

        $extras = [
            'av_binocular' => $_POST['av_binocular'] ?? null,
            'av_sin_correccion' => $_POST['av_sin_correccion'] ?? null,
            'av_estenopeico' => $_POST['av_estenopeico'] ?? null,
            'av_corr_propia' => $_POST['av_corr_propia'] ?? null,
            'av_mejor_corr' => $_POST['av_mejor_corr'] ?? null,
            'av_potencial' => $_POST['av_potencial'] ?? null,
        ];

        $sql = "INSERT INTO refraccion_visual (ojo, av_lejana, av_lejana_lentes, esf_lejana, cil_lejana, eje_lejana, add_lejana, prisma_lejana,
                 av_cercana, esf_cercana, cil_cercana, eje_cercana, add_cercana, prisma_cercana,
                 av_binocular, av_sin_correccion, av_estenopeico, av_corr_propia, av_mejor_corr, av_potencial)
                VALUES (
                    '$ojo', '{$data['av_lejana']}', '{$data['av_lejana_lentes']}', '{$data['esf_lejana']}', '{$data['cil_lejana']}',
                    '{$data['eje_lejana']}', '{$data['add_lejana']}', '{$data['prisma_lejana']}',
                    '{$data['av_cercana']}', '{$data['esf_cercana']}', '{$data['cil_cercana']}', '{$data['eje_cercana']}',
                    '{$data['add_cercana']}', '{$data['prisma_cercana']}',
                    '{$extras['av_binocular']}', '{$extras['av_sin_correccion']}', '{$extras['av_estenopeico']}',
                    '{$extras['av_corr_propia']}', '{$extras['av_mejor_corr']}', '{$extras['av_potencial']}'
                )";

        $conn->query($sql);
    }

    $conn->close();
    echo "<script>alert('Datos guardados correctamente');</script>";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Refracción Visual</title>
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
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.1);
        }

        h3 {
            margin-top: 40px;
            color: #1abc9c;
            border-bottom: 2px solid #ddd;
            padding-bottom: 5px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }

        .field {
            flex: 1;
            min-width: 150px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #2d3e50;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: bold;
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

    <h2>Formulario de Refracción Visual</h2>
    <form method="post">

        <?php foreach (['Derecho', 'Izquierdo'] as $ojo): ?>
            <h3>Ojo <?= $ojo ?></h3>

            <div class="row">
                <div class="field">
                    <label>AV Lejana</label>
                    <input name="av_lejana_<?= $ojo ?>">
                </div>
                <div class="field">
                    <label>AV con Lentes</label>
                    <input name="av_lejana_lentes_<?= $ojo ?>">
                </div>
            </div>

            <div class="row">
                <div class="field"><label>Esf</label><input name="esf_lejana_<?= $ojo ?>"></div>
                <div class="field"><label>Cil</label><input name="cil_lejana_<?= $ojo ?>"></div>
                <div class="field"><label>Eje</label><input name="eje_lejana_<?= $ojo ?>"></div>
                <div class="field"><label>Add</label><input name="add_lejana_<?= $ojo ?>"></div>
                <div class="field checkbox-label">
                    <input type="checkbox" name="prisma_lejana_<?= $ojo ?>">
                    <label>Prisma</label>
                </div>
            </div>

            <div class="row">
                <div class="field">
                    <label>AV Cercana</label>
                    <input name="av_cercana_<?= $ojo ?>">
                </div>
            </div>

            <div class="row">
                <div class="field"><label>Esf</label><input name="esf_cercana_<?= $ojo ?>"></div>
                <div class="field"><label>Cil</label><input name="cil_cercana_<?= $ojo ?>"></div>
                <div class="field"><label>Eje</label><input name="eje_cercana_<?= $ojo ?>"></div>
                <div class="field"><label>Add</label><input name="add_cercana_<?= $ojo ?>"></div>
                <div class="field checkbox-label">
                    <input type="checkbox" name="prisma_cercana_<?= $ojo ?>">
                    <label>Prisma</label>
                </div>
            </div>
        <?php endforeach; ?>

        <h3>Refracción Actual</h3>
        <div class="row">
            <div class="field"><label>AV Binocular</label><input name="av_binocular"></div>
            <div class="field"><label>AV sin Corrección</label><input name="av_sin_correccion"></div>
            <div class="field"><label>AV Estenopeico</label><input name="av_estenopeico"></div>
        </div>
        <div class="row">
            <div class="field"><label>AV Corrección Propia</label><input name="av_corr_propia"></div>
            <div class="field"><label>AV Mejor Corregida</label><input name="av_mejor_corr"></div>
            <div class="field"><label>AV Potencial</label><input name="av_potencial"></div>
        </div>

        <div class="actions">
            <button type="submit">Guardar Refracción</button>
        </div>
    </form>

</body>

</html>