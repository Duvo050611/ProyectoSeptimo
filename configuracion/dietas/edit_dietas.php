<?php
session_start();

if (!isset($_SESSION['login'])) {
  header("Location: ../index.php");
}

include("../header_configuracion.php");
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
      max-width: 700px;
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
    }

    .form-header i {
      margin-right: 15px;
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

    /* Sección del formulario con tema cyberpunk */
    .form-section {
      background: linear-gradient(135deg, rgba(22, 33, 62, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%);
      border-radius: 16px;
      padding: 30px;
      margin-bottom: 25px;
      border: 2px solid rgba(64, 224, 255, 0.3);
      border-left: 4px solid #40E0FF;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3),
                  0 0 20px rgba(64, 224, 255, 0.1);
      position: relative;
      overflow: hidden;
    }

    .form-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(
        45deg,
        transparent,
        rgba(64, 224, 255, 0.05),
        transparent
      );
      transform: translateX(-100%);
      transition: transform 0.6s ease;
    }

    .form-section:hover::before {
      transform: translateX(100%);
    }

    .form-section:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4),
                  0 0 30px rgba(64, 224, 255, 0.2);
      border-color: #40E0FF;
    }

    .section-title {
      color: #40E0FF;
      font-weight: 700;
      margin-bottom: 25px;
      font-size: 18px;
      display: flex;
      align-items: center;
      padding-bottom: 12px;
      border-bottom: 2px solid rgba(64, 224, 255, 0.3);
      text-transform: uppercase;
      letter-spacing: 1px;
      text-shadow: 0 0 15px rgba(64, 224, 255, 0.5);
    }

    .section-title i {
      margin-right: 12px;
      font-size: 20px;
      color: #40E0FF;
      text-shadow: 0 0 10px rgba(64, 224, 255, 0.8);
    }

    /* Form group */
    .form-group {
      margin-bottom: 30px;
    }

    .form-group label {
      color: #ffffff;
      font-weight: 600;
      margin-bottom: 12px;
      font-size: 15px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      display: block;
    }

    .form-group label i {
      margin-right: 10px;
      color: #40E0FF;
      font-size: 16px;
      text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
    }

    /* Input moderno con estilo cyberpunk */
    .form-control {
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
    }

    .form-control:focus {
      border-color: #40E0FF !important;
      box-shadow: 0 0 20px rgba(64, 224, 255, 0.4),
                  inset 0 0 10px rgba(64, 224, 255, 0.1) !important;
      outline: none !important;
      background: linear-gradient(135deg, rgba(22, 33, 62, 0.6) 0%, rgba(15, 52, 96, 0.6) 100%) !important;
      transform: translateY(-2px) !important;
      color: #ffffff !important;
    }

    .form-control:hover {
      border-color: #40E0FF !important;
      background: linear-gradient(135deg, rgba(22, 33, 62, 0.6) 0%, rgba(15, 52, 96, 0.6) 100%) !important;
    }

    .form-control::placeholder {
      color: rgba(255, 255, 255, 0.5) !important;
    }

    /* Botones modernos con tema cyberpunk */
    .btn-actions {
      text-align: center;
      padding: 30px;
      background: linear-gradient(135deg, rgba(15, 52, 96, 0.5) 0%, rgba(22, 33, 62, 0.5) 100%);
      border-radius: 0 0 14px 14px;
      margin: 30px -30px -30px -30px;
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
      display: inline-block;
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
      margin: 20px auto;
      max-width: 700px;
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

      .form-section {
        padding: 20px;
      }

      .form-header h3 {
        font-size: 20px;
      }

      .btn-actions {
        padding: 20px;
        margin: 20px -20px -20px -20px;
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
        <h3><i class="fas fa-utensils"></i>Editar Dieta</h3>
      </div>

      <!-- Cuerpo del formulario -->
      <div class="form-body">
        <?php
        $id = $_GET['id'];
        $sql = "SELECT * from cat_dietas where id_dieta = $id";
        $result = $conexion->query($sql);
        while ($row_datos = $result->fetch_assoc()) {
        ?>
          <form action="" method="post" enctype="multipart/form-data">
            <div class="form-section">
              <div class="section-title">
                <i class="fas fa-utensils"></i>
                Información de la Dieta
              </div>

              <div class="form-group">
                <label for="dieta">
                  <i class="fas fa-utensils"></i> Nombre de la Dieta:
                </label>
                <input type="text"
                       name="dieta"
                       id="dieta"
                       class="form-control"
                       value="<?php echo htmlspecialchars($row_datos['dieta']); ?>"
                       placeholder="Nombre de la dieta"
                       required>
              </div>

              <!-- Botones de acción -->
              <div class="btn-actions">
                <button type="submit" name="edit" class="btn btn-save-modern">
                  <i class="fas fa-save mr-2"></i>Guardar
                </button>
                <a href="../dietas/cat_dietas.php" class="btn btn-cancel-modern">
                  <i class="fas fa-times mr-2"></i>Cancelar
                </a>
              </div>
            </div>
          </form>
        <?php } ?>
      </div>
    </div>

    <?php
    if (isset($_POST['edit'])) {
      $dieta = mysqli_real_escape_string($conexion, (strip_tags($_POST["dieta"], ENT_QUOTES)));
      $sql2 = "UPDATE cat_dietas SET dieta = '$dieta' WHERE id_dieta = $id";
      $result = $conexion->query($sql2);

      echo "<p class='alert alert-success' id='mensaje'><i class='fa fa-check'></i> Dato actualizado correctamente...</p>";
      echo '<script type="text/javascript">window.location.href="cat_dietas.php";</script>';
    }
    ?>
  </section>

  <footer class="main-footer">
    <?php include("../../template/footer.php"); ?>
  </footer>
</body>

</html>
