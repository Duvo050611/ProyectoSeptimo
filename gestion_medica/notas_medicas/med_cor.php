<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli("localhost", "root", "", "u542863078_ineo");

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $campos = [
        "usa_lentes_suaves_der", "usa_lentes_suaves_izq",
        "av_suaves_der_esf", "av_suaves_der_cil", "av_suaves_der_cb", "av_suaves_der_diam",
        "av_suaves_izq_esf", "av_suaves_izq_cil", "av_suaves_izq_cb", "av_suaves_izq_diam",
        "tipo_suaves_der", "tipo_suaves_izq",
        "av_duros_der_esf", "av_duros_der_cil", "av_duros_der_cb", "av_duros_der_diam",
        "av_duros_izq_esf", "av_duros_izq_cil", "av_duros_izq_cb", "av_duros_izq_diam",
        "receta_duros_der_tangente", "receta_duros_der_altura", "receta_duros_der_el", "receta_duros_der_or",
        "receta_duros_izq_tangente", "receta_duros_izq_altura", "receta_duros_izq_el", "receta_duros_izq_or",
        "receta_lc_duros_der", "receta_lc_duros_izq",
        "receta_hibrido_vlt_cb_der", "receta_hibrido_der_fallida",
        "receta_hibrido_vlt_cb_izq", "receta_hibrido_izq_fallida",
        "receta_escleral_sagita_der", "receta_escleral_limbus_der", "receta_escleral_med_p_der", "receta_escleral_diam_der",
        "receta_escleral_sagita_izq", "receta_escleral_limbus_izq", "receta_escleral_med_p_izq", "receta_escleral_diam_izq"
    ];

    foreach ($campos as $campo) {
        $$campo = $_POST[$campo] ?? '';
    }

    $sql = "INSERT INTO receta_lentes_contacto (" . implode(", ", $campos) . ") VALUES ('" .
        implode("', '", array_map(fn($c) => $conn->real_escape_string($$c), $campos)) . "')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Receta de lentes de contacto guardada correctamente');</script>";
    } else {
        echo "Error al guardar: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Receta de Lentes de Contacto</title>
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
        input, select {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
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

<h2>Receta de Lentes de Contacto</h2>
<form method="POST" action="">
    <h3>Lentes Suaves</h3>
    <div class="row">
        <div class="field">
            <label>Usa Lentes Suaves Der</label>
            <select name="usa_lentes_suaves_der">
                <option value="">Seleccionar</option>
                <option value="Sí">Sí</option>
                <option value="No">No</option>
            </select>
        </div>
        <div class="field">
            <label>Usa Lentes Suaves Izq</label>
            <select name="usa_lentes_suaves_izq">
                <option value="">Seleccionar</option>
                <option value="Sí">Sí</option>
                <option value="No">No</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="field"><label>Esf Der</label><input name="av_suaves_der_esf"></div>
        <div class="field"><label>Cil Der</label><input name="av_suaves_der_cil"></div>
        <div class="field"><label>CB Der</label><input name="av_suaves_der_cb"></div>
        <div class="field"><label>Diam Der</label><input name="av_suaves_der_diam"></div>
    </div>
    <div class="row">
        <div class="field"><label>Esf Izq</label><input name="av_suaves_izq_esf"></div>
        <div class="field"><label>Cil Izq</label><input name="av_suaves_izq_cil"></div>
        <div class="field"><label>CB Izq</label><input name="av_suaves_izq_cb"></div>
        <div class="field"><label>Diam Izq</label><input name="av_suaves_izq_diam"></div>
    </div>
    <div class="row">
        <div class="field"><label>Tipo Suaves Der</label><input name="tipo_suaves_der"></div>
        <div class="field"><label>Tipo Suaves Izq</label><input name="tipo_suaves_izq"></div>
    </div>

    <h3>Lentes Duros</h3>
    <div class="row">
        <div class="field"><label>Esf Der</label><input name="av_duros_der_esf"></div>
        <div class="field"><label>Cil Der</label><input name="av_duros_der_cil"></div>
        <div class="field"><label>CB Der</label><input name="av_duros_der_cb"></div>
        <div class="field"><label>Diam Der</label><input name="av_duros_der_diam"></div>
    </div>
    <div class="row">
        <div class="field"><label>Esf Izq</label><input name="av_duros_izq_esf"></div>
        <div class="field"><label>Cil Izq</label><input name="av_duros_izq_cil"></div>
        <div class="field"><label>CB Izq</label><input name="av_duros_izq_cb"></div>
        <div class="field"><label>Diam Izq</label><input name="av_duros_izq_diam"></div>
    </div>

    <h3>Parámetros Especiales Duros</h3>
    <div class="row">
        <div class="field"><label>Tangente Der</label><input name="receta_duros_der_tangente"></div>
        <div class="field"><label>Altura Der</label><input name="receta_duros_der_altura"></div>
        <div class="field"><label>EL Der</label><input name="receta_duros_der_el"></div>
        <div class="field"><label>OR Der</label><input name="receta_duros_der_or"></div>
    </div>
    <div class="row">
        <div class="field"><label>Tangente Izq</label><input name="receta_duros_izq_tangente"></div>
        <div class="field"><label>Altura Izq</label><input name="receta_duros_izq_altura"></div>
        <div class="field"><label>EL Izq</label><input name="receta_duros_izq_el"></div>
        <div class="field"><label>OR Izq</label><input name="receta_duros_izq_or"></div>
    </div>

    <h3>Otros Tipos de Lentes</h3>
    <div class="row">
        <div class="field"><label>Receta LC Duros Der</label><input name="receta_lc_duros_der"></div>
        <div class="field"><label>Receta LC Duros Izq</label><input name="receta_lc_duros_izq"></div>
    </div>
    <div class="row">
        <div class="field"><label>VLT/CB Der</label><input name="receta_hibrido_vlt_cb_der"></div>
        <div class="field"><label>Fallida Der</label><input name="receta_hibrido_der_fallida"></div>
        <div class="field"><label>VLT/CB Izq</label><input name="receta_hibrido_vlt_cb_izq"></div>
        <div class="field"><label>Fallida Izq</label><input name="receta_hibrido_izq_fallida"></div>
    </div>

    <h3>Lente Escleral</h3>
    <div class="row">
        <div class="field"><label>Sagita Der</label><input name="receta_escleral_sagita_der"></div>
        <div class="field"><label>Limbus Der</label><input name="receta_escleral_limbus_der"></div>
        <div class="field"><label>Med P Der</label><input name="receta_escleral_med_p_der"></div>
        <div class="field"><label>Diam Der</label><input name="receta_escleral_diam_der"></div>
    </div>
    <div class="row">
        <div class="field"><label>Sagita Izq</label><input name="receta_escleral_sagita_izq"></div>
        <div class="field"><label>Limbus Izq</label><input name="receta_escleral_limbus_izq"></div>
        <div class="field"><label>Med P Izq</label><input name="receta_escleral_med_p_izq"></div>
        <div class="field"><label>Diam Izq</label><input name="receta_escleral_diam_izq"></div>
    </div>

    <div class="actions">
        <button type="submit">Guardar Receta</button>
    </div>
</form>

</body>
</html>
