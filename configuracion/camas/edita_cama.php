<?php
session_start();

if (!isset($_SESSION['login'])) {
  header("Location: ../index.php");
}

include("../header_configuracion.php");
include("../../conexionbd.php");
?>

<head>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

  <script src="../../js/jquery-3.3.1.min.js"></script>
  <script src="../../js/jquery-ui.js"></script>
  <script src="../../js/popper.min.js"></script>
  <script src="../../js/bootstrap.min.js"></script>
  <script src="../../js/jquery.magnific-popup.min.js"></script>
  <script src="../../js/aos.js"></script>
  <script src="../../js/main.js"></script>

  <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
  <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
  <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

  <style>
    * {
      box-sizing: border-box;
    }

    body {
      background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #0a0a0a 100%) !important;
      font-family: 'Roboto', sans-serif !important;
      min-height: 100vh;
    }

    /* Efecto de partículas en el fondo */
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image:
        radial-gradient(circle at 20% 50%, rgba(64, 224, 255, 0.03) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(64, 224, 255, 0.03) 0%, transparent 50%),
        radial-gradient(circle at 40% 20%, rgba(64, 224, 255, 0.02) 0%, transparent 50%);
      pointer-events: none;
      z-index: 0;
    }

    .content-wrapper {
      background: transparent !important;
      min-height: 100vh;
    }

    /* Contenedor principal moderno con tema cyberpunk */
    .form-container {
      background: linear-gradient(135deg, #16213e 0%, #0f3460 100%);
      border: 2px solid #40E0FF;
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5),
                  0 0 30px rgba(64, 224, 255, 0.2);
      margin: 30px auto;
      overflow: hidden;
      max-width: 800px;
      position: relative;
      animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Efecto de brillo en el contenedor */
    .form-container::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(64, 224, 255, 0.1) 0%, transparent 70%);
      animation: pulse 3s ease-in-out infinite;
      pointer-events: none;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); opacity: 0.5; }
      50% { transform: scale(1.1); opacity: 0.8; }
    }

    /* Header del formulario con estilo cyberpunk */
    .form-header {
      background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
      color: #ffffff;
      padding: 30px;
      text-align: center;
      margin-bottom: 0;
      border-bottom: 2px solid #40E0FF;
      position: relative;
      overflow: hidden;
    }

    .form-header::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(64, 224, 255, 0.1) 0%, transparent 70%);
      animation: pulse 3s ease-in-out infinite;
    }

    .form-header h3 {
      margin: 0;
      font-weight: 700;
      font-size: 26px;
      text-transform: uppercase;
      letter-spacing: 2px;
      text-shadow: 0 0 20px rgba(64, 224, 255, 0.5);
      position: relative;
      z-index: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 15px;
    }

    .form-header i {
      font-size: 30px;
      color: #40E0FF;
    }

    /* Cuerpo del formulario */
    .form-body {
      padding: 40px;
      background: transparent;
      position: relative;
      z-index: 1;
    }

    /* Form group */
    .form-group-modern {
      margin-bottom: 30px;
    }

    /* Labels modernos con tema cyberpunk */
    .form-label-modern {
      color: #ffffff;
      font-weight: 600;
      margin-bottom: 12px;
      font-size: 15px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      display: flex;
      align-items: center;
    }

    .form-label-modern i {
      margin-right: 10px;
      color: #40E0FF;
      font-size: 16px;
      text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
    }

    /* Inputs modernos con estilo cyberpunk */
    .form-control-modern {
      border: 2px solid rgba(64, 224, 255, 0.3) !important;
      border-radius: 12px !important;
      padding: 16px 20px !important;
      font-size: 15px !important;
      transition: all 0.3s ease !important;
      background: linear-gradient(135deg, rgba(15, 52, 96, 0.5) 0%, rgba(22, 33, 62, 0.5) 100%) !important;
      color: #ffffff !important;
      font-weight: 500;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2),
                  inset 0 1px 3px rgba(64, 224, 255, 0.1);
      width: 100%;
      height: 50px;
    }

    .form-control-modern:focus {
      border-color: #40E0FF !important;
      box-shadow: 0 0 20px rgba(64, 224, 255, 0.4),
                  inset 0 0 10px rgba(64, 224, 255, 0.1) !important;
      outline: none !important;
      background: linear-gradient(135deg, rgba(22, 33, 62, 0.6) 0%, rgba(15, 52, 96, 0.6) 100%) !important;
      transform: translateY(-2px) !important;
      color: #ffffff !important;
    }

    .form-control-modern:hover {
      border-color: #40E0FF !important;
      background: linear-gradient(135deg, rgba(22, 33, 62, 0.6) 0%, rgba(15, 52, 96, 0.6) 100%) !important;
    }

    .form-control-modern:disabled {
      background: rgba(15, 52, 96, 0.3) !important;
      opacity: 0.6;
      cursor: not-allowed;
      color: rgba(255, 255, 255, 0.5) !important;
    }

    /* Selects modernos con tema cyberpunk */
    .select-modern {
      border: 2px solid rgba(64, 224, 255, 0.3) !important;
      border-radius: 12px !important;
      padding: 16px 20px !important;
      font-size: 15px !important;
      transition: all 0.3s ease !important;
      background: linear-gradient(135deg, rgba(15, 52, 96, 0.5) 0%, rgba(22, 33, 62, 0.5) 100%) !important;
      color: #ffffff !important;
      appearance: none !important;
      -webkit-appearance: none !important;
      -moz-appearance: none !important;
      background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2340E0FF' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e") !important;
      background-position: right 15px center !important;
      background-repeat: no-repeat !important;
      background-size: 20px !important;
      padding-right: 50px !important;
      width: 100% !important;
      font-weight: 500;
      cursor: pointer !important;
      height: 50px !important;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2),
                  inset 0 1px 3px rgba(64, 224, 255, 0.1);
    }

    .select-modern option {
      background: #16213e;
      color: #ffffff;
      padding: 10px;
    }

    .select-modern:focus {
      border-color: #40E0FF !important;
      box-shadow: 0 0 20px rgba(64, 224, 255, 0.4),
                  inset 0 0 10px rgba(64, 224, 255, 0.1) !important;
      outline: none !important;
      background: linear-gradient(135deg, rgba(22, 33, 62, 0.6) 0%, rgba(15, 52, 96, 0.6) 100%) !important;
      transform: translateY(-2px) !important;
      color: #ffffff !important;
    }

    .select-modern:hover {
      border-color: #40E0FF !important;
      background: linear-gradient(135deg, rgba(22, 33, 62, 0.6) 0%, rgba(15, 52, 96, 0.6) 100%) !important;
    }

    /* Campo de estado actual - Estilo Cyberpunk */
    .current-status {
      background: linear-gradient(135deg, rgba(33, 150, 243, 0.2) 0%, rgba(25, 118, 210, 0.3) 100%);
      border: 2px solid #2196f3;
      color: #64B5F6;
      padding: 18px 25px;
      border-radius: 12px;
      font-weight: 700;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      box-shadow: 0 6px 20px rgba(33, 150, 243, 0.3),
                  inset 0 0 15px rgba(33, 150, 243, 0.1);
      transition: all 0.3s ease;
      font-size: 15px;
      text-transform: uppercase;
      letter-spacing: 1px;
      min-height: 55px;
    }

    .current-status:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(33, 150, 243, 0.4),
                  inset 0 0 20px rgba(33, 150, 243, 0.15);
      border-color: #42A5F5;
    }

    .current-status i {
      margin-right: 15px;
      color: #42A5F5;
      font-size: 20px;
      background: rgba(33, 150, 243, 0.2);
      padding: 10px;
      border-radius: 50%;
      box-shadow: 0 0 15px rgba(33, 150, 243, 0.5);
      border: 2px solid #2196f3;
    }

    /* Campo de área actual - Estilo Cyberpunk */
    .area-status {
      background: linear-gradient(135deg, rgba(76, 175, 80, 0.2) 0%, rgba(56, 142, 60, 0.3) 100%);
      border: 2px solid #4caf50;
      color: #81C784;
      padding: 18px 25px;
      border-radius: 12px;
      font-weight: 700;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      box-shadow: 0 6px 20px rgba(76, 175, 80, 0.3),
                  inset 0 0 15px rgba(76, 175, 80, 0.1);
      transition: all 0.3s ease;
      font-size: 15px;
      text-transform: uppercase;
      letter-spacing: 1px;
      min-height: 55px;
    }

    .area-status:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(76, 175, 80, 0.4),
                  inset 0 0 20px rgba(76, 175, 80, 0.15);
      border-color: #66BB6A;
    }

    .area-status i {
      margin-right: 15px;
      color: #66BB6A;
      font-size: 20px;
      background: rgba(76, 175, 80, 0.2);
      padding: 10px;
      border-radius: 50%;
      box-shadow: 0 0 15px rgba(76, 175, 80, 0.5);
      border: 2px solid #4caf50;
    }

    /* Botones modernos con tema cyberpunk */
    .btn-actions {
      text-align: center;
      padding: 30px;
      background: linear-gradient(135deg, rgba(15, 52, 96, 0.5) 0%, rgba(22, 33, 62, 0.5) 100%);
      border-radius: 0 0 18px 18px;
      margin: 30px -40px -40px -40px;
      border-top: 2px solid rgba(64, 224, 255, 0.3);
      position: relative;
      z-index: 1;
    }

    .btn-save-modern {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
      border: 2px solid #28a745;
      border-radius: 25px;
      padding: 12px 35px;
      color: white;
      font-weight: 600;
      font-size: 16px;
      box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
      transition: all 0.3s ease;
      margin-right: 15px;
      text-transform: uppercase;
      letter-spacing: 1px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn-save-modern:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 30px rgba(40, 167, 69, 0.6);
      color: white;
      border-color: #20c997;
    }

    .btn-cancel-modern {
      background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
      border: 2px solid #dc3545;
      border-radius: 25px;
      padding: 12px 35px;
      color: white;
      font-weight: 600;
      font-size: 16px;
      box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .btn-cancel-modern:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 30px rgba(220, 53, 69, 0.6);
      color: white;
      text-decoration: none;
      border-color: #c82333;
    }

    /* Alert con tema cyberpunk */
    .alert-success {
      background: linear-gradient(135deg, rgba(40, 167, 69, 0.2) 0%, rgba(32, 201, 151, 0.2) 100%) !important;
      border: 2px solid #28a745 !important;
      border-radius: 15px !important;
      color: #ffffff !important;
      box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3) !important;
    }

    /* Footer */
    .main-footer {
      background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
      border-top: 2px solid #40E0FF !important;
      color: #ffffff !important;
      box-shadow: 0 -4px 20px rgba(64, 224, 255, 0.2);
    }

    /* Scrollbar personalizado */
    ::-webkit-scrollbar {
      width: 12px;
    }

    ::-webkit-scrollbar-track {
      background: #0a0a0a;
      border-left: 1px solid #40E0FF;
    }

    ::-webkit-scrollbar-thumb {
      background: linear-gradient(180deg, #40E0FF 0%, #0f3460 100%);
      border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: linear-gradient(180deg, #00D9FF 0%, #40E0FF 100%);
    }

    /* Responsive */
    @media (max-width: 768px) {
      .form-container {
        margin: 10px;
        border-radius: 15px;
      }

      .form-body {
        padding: 25px;
      }

      .form-header h3 {
        font-size: 20px;
      }

      .btn-actions {
        padding: 20px;
        margin: 20px -25px -25px -25px;
      }

      .btn-save-modern,
      .btn-cancel-modern {
        margin: 5px;
        width: calc(100% - 10px);
      }
    }
  </style>

</head>

<body>

  <section class="content container-fluid">
    <div class="form-container">
      <!-- Header del formulario -->
      <div class="form-header">
        <h3><i class="fas fa-edit"></i>Editar Habitación</h3>
      </div>

      <!-- Cuerpo del formulario -->
      <div class="form-body">
        <?php
        $id = $_GET['id'];
        $sql = "SELECT id,estatus,tipo, num_cama,piso,seccion from cat_camas where id = $id";
        $result = $conexion->query($sql);
        while ($row_datos = $result->fetch_assoc()) {
        ?>
          <form action="" method="post" enctype="multipart/form-data">

            <!-- Campo: Habitación -->
            <div class="form-group-modern">
              <label class="form-label-modern">
                <i class="fas fa-hashtag"></i>Habitación:
              </label>
              <input type="text"
                     name="num_cama"
                     class="form-control-modern"
                     value="<?php echo $row_datos['num_cama'] ?>"
                     disabled>
            </div>

            <!-- Campo: Estatus -->
            <div class="form-group-modern">
              <label class="form-label-modern">
                <i class="fas fa-flag"></i>Estatus:
              </label>
              <div class="current-status">
                <i class="fas fa-info-circle"></i>
                ESTATUS ACTUAL: <?php
                if ($row_datos['estatus'] == "MANTENIMIENTO") {
                  echo 'MANTENIMIENTO';
                } else {
                  echo $row_datos['estatus'];
                }
                ?>
              </div>
              <select name="estatus" class="select-modern" required>
                <option value="<?php echo $row_datos['estatus'] ?>"><?php echo $row_datos['estatus'] ?> (Mantener actual)</option>
                <option value="">--- Cambiar a nuevo estatus ---</option>
                <option value="LIBRE">LIBRE</option>
                <option value="MANTENIMIENTO">MANTENIMIENTO</option>
              </select>
            </div>

            <!-- Campo: Área -->
            <div class="form-group-modern">
              <label class="form-label-modern">
                <i class="fas fa-hospital"></i>Área:
              </label>
              <div class="area-status">
                <i class="fas fa-map-marker-alt"></i>
                ÁREA ACTUAL: <?php echo $row_datos['tipo'] ?>
              </div>
              <select name="tipo" class="select-modern" required>
                <option value="<?php echo $row_datos['tipo'] ?>"><?php echo $row_datos['tipo'] ?> (Mantener actual)</option>
                <option value="">--- Cambiar a nueva área ---</option>
                <option value="HOSPITALIZACIÓN">HOSPITALIZACIÓN</option>
                <option value="OBSERVACIÓN">OBSERVACIÓN</option>
                <option value="TERAPIA INTENSIVA">TERAPIA INTENSIVA</option>
                <option value="ENDOSCOPÍA">ENDOSCOPÍA</option>
                <option value="CONSULTA">CONSULTA</option>
              </select>
            </div>

            <!-- Campo: Piso -->
            <div class="form-group-modern">
              <label class="form-label-modern">
                <i class="fas fa-layer-group"></i>Piso:
              </label>
              <input type="number"
                     name="piso"
                     class="form-control-modern"
                     id="piso"
                     value="<?php echo $row_datos['piso'] ?>">
            </div>

            <!-- Campo: Sección -->
            <div class="form-group-modern">
              <label class="form-label-modern">
                <i class="fas fa-th-large"></i>Sección:
              </label>
              <input type="number"
                     name="seccion"
                     class="form-control-modern"
                     id="seccion"
                     value="<?php echo $row_datos['seccion'] ?>">
            </div>

          <?php } ?>

          <!-- Botones de acción -->
          <div class="btn-actions">
            <button type="submit" name="del" class="btn-save-modern">
              <i class="fas fa-save"></i>Guardar
            </button>
            <a href="cat_camas.php" class="btn-cancel-modern">
              <i class="fas fa-times"></i>Cancelar
            </a>
          </div>

          </form>
      </div>
    </div>

    <?php
    if (isset($_POST['del'])) {
      $estatus = mysqli_real_escape_string($conexion, (strip_tags($_POST["estatus"], ENT_QUOTES)));
      $tipo = mysqli_real_escape_string($conexion, (strip_tags($_POST["tipo"], ENT_QUOTES)));
      $piso = mysqli_real_escape_string($conexion, (strip_tags($_POST["piso"], ENT_QUOTES)));
      $seccion = mysqli_real_escape_string($conexion, (strip_tags($_POST["seccion"], ENT_QUOTES)));
      $sql2 = "UPDATE cat_camas SET estatus = '$estatus', tipo = '$tipo', piso = '$piso', seccion = '$seccion' WHERE id = $id";
      $result = $conexion->query($sql2);

      echo "<div class='alert alert-success' style='position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;'>
              <i class='fa fa-check'></i> Dato editado correctamente...
            </div>";
      echo '<script type="text/javascript">
              setTimeout(function() {
                window.location.href="cat_camas.php";
              }, 1500);
            </script>';
    }
    ?>

  </section>

  <footer class="main-footer">
    <?php include("../../template/footer.php"); ?>
  </footer>
</body>

</html>
