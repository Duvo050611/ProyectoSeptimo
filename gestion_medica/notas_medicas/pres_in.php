<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli('localhost', 'root', '', 'u542863078_ineo');
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $campos = array_merge(
        ['esf_lejana_od', 'cil_lejana_od', 'eje_lejana_od', 'add_lejana_od', 'dip_lejana_od', 'prisma_lejana_od',
         'esf_lejana_oi', 'cil_lejana_oi', 'eje_lejana_oi', 'add_lejana_oi', 'dip_lejana_oi', 'prisma_lejana_oi'],
        ['esf_cercana_od', 'cil_cercana_od', 'eje_cercana_od', 'dip_cercana_od', 'prisma_cercana_od',
         'esf_cercana_oi', 'cil_cercana_oi', 'eje_cercana_oi', 'dip_cercana_oi', 'prisma_cercana_oi'],
        ['esf_intermedia_od', 'cil_intermedia_od', 'eje_intermedia_od', 'dip_intermedia_od',
         'esf_intermedia_oi', 'cil_intermedia_oi', 'eje_intermedia_oi', 'dip_intermedia_oi'],
        ['tipo_lente_od', 'tipo_lente_oi', 'observaciones']
    );

    foreach ($campos as $campo) {
        $$campo = $_POST[$campo] ?? '';
    }

    $sql = "INSERT INTO receta_anteojos (" . implode(", ", $campos) . ") VALUES ('" .
        implode("', '", array_map(function($c) use ($conn) {
            return $conn->real_escape_string($_POST[$c] ?? '');
        }, $campos)) . "')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Receta guardada correctamente');</script>";
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
    <title>Receta de Anteojos</title>
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
        h3.section-title {
            margin-top: 40px;
            margin-bottom: 10px;
            font-size: 22px;
            color: #1abc9c;
            border-bottom: 2px solid #ddd;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 12px 10px;
            margin-bottom: 30px;
        }
        th, td {
            text-align: left;
            vertical-align: middle;
            font-weight: 500;
            color: #2d3e50;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
            font-family: 'Roboto', sans-serif;
            background-color: #f9f9f9;
        }
        input[type="checkbox"] {
            transform: scale(1.2);
            cursor: pointer;
        }
        textarea {
            resize: none;
            height: 100px;
        }
        .submit-btn {
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
            transition: background-color 0.3s ease;
        }
        button:hover {
            background: #17a88b;
        }
    </style>
</head>
<body>

<h2>Receta de Anteojos</h2>

<form method="post">

    <h3 class="section-title">Refracción Lejana</h3>
    <table>
        <thead>
            <tr>
                <th></th><th>Esf</th><th>Cil</th><th>Eje</th><th>Add</th><th>DIP</th><th>Prisma</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>OD</td>
                <td><input name="esf_lejana_od" type="text"></td>
                <td><input name="cil_lejana_od" type="text"></td>
                <td><input name="eje_lejana_od" type="text"></td>
                <td><input name="add_lejana_od" type="text"></td>
                <td><input name="dip_lejana_od" type="text"></td>
                <td style="text-align: center;"><input name="prisma_lejana_od" type="checkbox" value="1"></td>
            </tr>
            <tr>
                <td>OI</td>
                <td><input name="esf_lejana_oi" type="text"></td>
                <td><input name="cil_lejana_oi" type="text"></td>
                <td><input name="eje_lejana_oi" type="text"></td>
                <td><input name="add_lejana_oi" type="text"></td>
                <td><input name="dip_lejana_oi" type="text"></td>
                <td style="text-align: center;"><input name="prisma_lejana_oi" type="checkbox" value="1"></td>
            </tr>
        </tbody>
    </table>

    <h3 class="section-title">Refracción Cercana</h3>
    <table>
        <tbody>
            <tr>
                <td>OD</td>
                <td><input name="esf_cercana_od" type="text"></td>
                <td><input name="cil_cercana_od" type="text"></td>
                <td><input name="eje_cercana_od" type="text"></td>
                <td><input name="dip_cercana_od" type="text" placeholder="DIP"></td>
                <td style="text-align: center;"><input name="prisma_cercana_od" type="checkbox" value="1"></td>
            </tr>
            <tr>
                <td>OI</td>
                <td><input name="esf_cercana_oi" type="text"></td>
                <td><input name="cil_cercana_oi" type="text"></td>
                <td><input name="eje_cercana_oi" type="text"></td>
                <td><input name="dip_cercana_oi" type="text" placeholder="DIP"></td>
                <td style="text-align: center;"><input name="prisma_cercana_oi" type="checkbox" value="1"></td>
            </tr>
        </tbody>
    </table>

    <h3 class="section-title">Refracción Intermedia</h3>
    <table>
        <tbody>
            <tr>
                <td>OD</td>
                <td><input name="esf_intermedia_od" type="text"></td>
                <td><input name="cil_intermedia_od" type="text"></td>
                <td><input name="eje_intermedia_od" type="text"></td>
                <td><input name="dip_intermedia_od" type="text" placeholder="DIP"></td>
            </tr>
            <tr>
                <td>OI</td>
                <td><input name="esf_intermedia_oi" type="text"></td>
                <td><input name="cil_intermedia_oi" type="text"></td>
                <td><input name="eje_intermedia_oi" type="text"></td>
                <td><input name="dip_intermedia_oi" type="text" placeholder="DIP"></td>
            </tr>
        </tbody>
    </table>

    <h3 class="section-title">Tipo de Lentes</h3>
    <table>
        <tbody>
            <tr>
                <td>OD: <input name="tipo_lente_od" type="text"></td>
                <td>OI: <input name="tipo_lente_oi" type="text"></td>
            </tr>
        </tbody>
    </table>

    <h3 class="section-title">Observaciones</h3>
    <textarea name="observaciones"></textarea>

    <div class="submit-btn">
        <button type="submit">Guardar Receta</button>
    </div>

</form>

</body>
</html>
