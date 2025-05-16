<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli('localhost', 'root', '', 'u542863078_ineo');
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Capturar todos los campos
    $fields = [
        'av_binocular', 'av_lejana_sin_correc', 'av_estenopico', 'av_lejana_con_correc_prop',
        'av_lejana_mejor_corregida', 'av_potencial',
        'esferas_sin_ciclo_od', 'cilindros_sin_ciclo_od', 'eje_sin_ciclo_od', 'add_sin_ciclo_od', 'prisma_sin_ciclo_od',
        'esferas_sin_ciclo_oi', 'cilindros_sin_ciclo_oi', 'eje_sin_ciclo_oi', 'add_sin_ciclo_oi', 'dip_sin_ciclo_oi', 'prisma_sin_ciclo_oi',
        'esferas_con_ciclo_od', 'cilindros_con_ciclo_od', 'eje_con_ciclo_od', 'add_con_ciclo_od', 'prisma_con_ciclo_od',
        'esferas_con_ciclo_oi', 'cilindros_con_ciclo_oi', 'eje_con_ciclo_oi', 'add_con_ciclo_oi', 'dip_con_ciclo_oi', 'prisma_con_ciclo_oi',
        'av_intermedia', 'av_cercana_sin_corr', 'av_cercana_con_corr',
        'esf_cerca_od', 'cil_cerca_od', 'eje_cerca_od', 'prisma_cerca_od',
        'esf_cerca_oi', 'cil_cerca_oi', 'eje_cerca_oi', 'dip_cerca_oi', 'prisma_cerca_oi'
    ];

    $data = [];
    foreach ($fields as $field) {
        $data[$field] = isset($_POST[$field]) ? $conn->real_escape_string($_POST[$field]) : '';
    }

    $sql = "INSERT INTO refraccion_actual (" . implode(",", array_keys($data)) . ") VALUES ('" . implode("','", $data) . "')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Refracción guardada exitosamente');</script>";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Refracción Actual</title>
<style>
  /* Reset básico */
  * {
    box-sizing: border-box;
  }

  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f0f4f8;
    padding: 30px;
    color: #333;
  }

  form {
    background: #fff;
    max-width: 1000px;
    margin: auto;
    padding: 30px 40px;
    border-radius: 15px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
  }

  h2 {
    text-align: center;
    margin-bottom: 30px;
    font-weight: 700;
    color: #222;
    letter-spacing: 1px;
  }

  h3 {
    margin-top: 35px;
    margin-bottom: 15px;
    font-weight: 600;
    color: #555;
    border-bottom: 2px solid #2ecc71;
    padding-bottom: 5px;
  }

  .group {
    margin-bottom: 30px;
  }

  .row {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: center;
    margin-bottom: 15px;
  }

  label {
    flex: 0 0 180px;
    font-weight: 600;
    color: #444;
  }

  input[type="text"],
  input[type="number"],
  select {
    flex: 1 1 150px;
    padding: 10px 14px;
    border: 1.8px solid #ccc;
    border-radius: 8px;
    font-size: 15px;
    transition: border-color 0.3s ease;
  }

  input[type="text"]:focus,
  input[type="number"]:focus,
  select:focus {
    border-color: #2ecc71;
    outline: none;
    box-shadow: 0 0 5px rgba(46,204,113,0.5);
  }

  /* Checkbox styling */
  label.checkbox-label {
    flex: 1 1 auto;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    cursor: pointer;
    color: #555;
  }

  input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: #2ecc71;
  }

  .button-container {
    text-align: center;
    margin-top: 40px;
  }

  button {
    background: #2ecc71;
    border: none;
    color: white;
    font-weight: 700;
    padding: 14px 40px;
    font-size: 18px;
    border-radius: 12px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  button:hover {
    background: #27ae60;
  }

  /* Responsive */
  @media (max-width: 700px) {
    label {
      flex: 0 0 100%;
    }
    .row {
      gap: 10px;
    }
  }
</style>
</head>
<body>

<form method="post" autocomplete="off">
  <h2>Refracción Actual</h2>

  <div class="group">
    <div class="row">
      <label for="av_binocular">AV Binocular:</label>
      <select id="av_binocular" name="av_binocular" >
        <option value="">Seleccionar</option>
        <!-- Aquí puedes agregar opciones -->
      </select>

      <label for="av_lejana_sin_correc">AV Lejana sin Corrección:</label>
      <select id="av_lejana_sin_correc" name="av_lejana_sin_correc">
        <option value="">Seleccionar</option>
      </select>
    </div>
    <div class="row">
      <label for="av_estenopico">AV Estenopico:</label>
      <select id="av_estenopico" name="av_estenopico">
        <option value="">Seleccionar</option>
      </select>

      <label for="av_lejana_con_correc_prop">AV Lejana con Corrección Propia:</label>
      <select id="av_lejana_con_correc_prop" name="av_lejana_con_correc_prop">
        <option value="">Seleccionar</option>
      </select>
    </div>
    <div class="row">
      <label for="av_lejana_mejor_corregida">AV Lejana Mejor Corregida:</label>
      <select id="av_lejana_mejor_corregida" name="av_lejana_mejor_corregida">
        <option value="">Seleccionar</option>
      </select>

      <label for="av_potencial">AV Potencial:</label>
      <select id="av_potencial" name="av_potencial">
        <option value="">Seleccionar</option>
      </select>
    </div>
  </div>

  <div class="group">
    <h3>Ref. Subjetiva Lejana Sin Cicloplejía</h3>
    <div class="row">
      <label>OD:</label>
      <input type="text" name="esferas_sin_ciclo_od" placeholder="Esf" />
      <input type="text" name="cilindros_sin_ciclo_od" placeholder="Cil" />
      <input type="text" name="eje_sin_ciclo_od" placeholder="Eje" />
      <input type="text" name="add_sin_ciclo_od" placeholder="Add" />
      <label class="checkbox-label"><input type="checkbox" name="prisma_sin_ciclo_od" value="1" />Prisma</label>
    </div>
    <div class="row">
      <label>OI:</label>
      <input type="text" name="esferas_sin_ciclo_oi" placeholder="Esf" />
      <input type="text" name="cilindros_sin_ciclo_oi" placeholder="Cil" />
      <input type="text" name="eje_sin_ciclo_oi" placeholder="Eje" />
      <input type="text" name="add_sin_ciclo_oi" placeholder="Add" />
      <input type="text" name="dip_sin_ciclo_oi" placeholder="DIP" />
      <label class="checkbox-label"><input type="checkbox" name="prisma_sin_ciclo_oi" value="1" />Prisma</label>
    </div>
  </div>

  <div class="group">
    <h3>Ref. Subjetiva Lejana Con Cicloplejía</h3>
    <div class="row">
      <label>OD:</label>
      <input type="text" name="esferas_con_ciclo_od" placeholder="Esf" />
      <input type="text" name="cilindros_con_ciclo_od" placeholder="Cil" />
      <input type="text" name="eje_con_ciclo_od" placeholder="Eje" />
      <input type="text" name="add_con_ciclo_od" placeholder="Add" />
      <label class="checkbox-label"><input type="checkbox" name="prisma_con_ciclo_od" value="1" />Prisma</label>
    </div>
    <div class="row">
      <label>OI:</label>
      <input type="text" name="esferas_con_ciclo_oi" placeholder="Esf" />
      <input type="text" name="cilindros_con_ciclo_oi" placeholder="Cil" />
      <input type="text" name="eje_con_ciclo_oi" placeholder="Eje" />
      <input type="text" name="add_con_ciclo_oi" placeholder="Add" />
      <input type="text" name="dip_con_ciclo_oi" placeholder="DIP" />
      <label class="checkbox-label"><input type="checkbox" name="prisma_con_ciclo_oi" value="1" />Prisma</label>
    </div>
  </div>

  <div class="group">
    <div class="row">
      <label for="av_intermedia">AV Intermedia:</label>
      <select id="av_intermedia" name="av_intermedia">
        <option value="">Seleccionar</option>
      </select>

      <label for="av_cercana_sin_corr">AV Cercana sin Corrección:</label>
      <select id="av_cercana_sin_corr" name="av_cercana_sin_corr">
        <option value="">Seleccionar</option>
      </select>
    </div>
    <div class="row">
      <label for="av_cercana_con_corr">AV Cercana con Corrección:</label>
      <select id="av_cercana_con_corr" name="av_cercana_con_corr">
        <option value="">Seleccionar</option>
      </select>
    </div>
  </div>

  <div class="group">
    <h3>Ref. Subjetiva Cercana</h3>
    <div class="row">
      <label>OD:</label>
      <input type="text" name="esf_cerca_od" placeholder="Esf" />
      <input type="text" name="cil_cerca_od" placeholder="Cil" />
      <input type="text" name="eje_cerca_od" placeholder="Eje" />
      <label class="checkbox-label"><input type="checkbox" name="prisma_cerca_od" value="1" />Prisma</label>
    </div>
    <div class="row">
      <label>OI:</label>
      <input type="text" name="esf_cerca_oi" placeholder="Esf" />
      <input type="text" name="cil_cerca_oi" placeholder="Cil" />
      <input type="text" name="eje_cerca_oi" placeholder="Eje" />
      <input type="text" name="dip_cerca_oi" placeholder="DIP" />
      <label class="checkbox-label"><input type="checkbox" name="prisma_cerca_oi" value="1" />Prisma</label>
    </div>
  </div>

  <div class="button-container">
    <button type="submit">Guardar Refracción</button>
  </div>
</form>

</body>
</html>
