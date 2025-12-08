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
  <!--  Bootstrap  -->
  <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

  <script src="../../js/jquery-3.3.1.min.js"></script>
  <script src="../../js/jquery-ui.js"></script>
  <script src="../../js/popper.min.js"></script>
  <script src="../../js/bootstrap.min.js"></script>
  <script src="../../js/jquery.magnific-popup.min.js"></script>
  <script src="../../js/aos.js"></script>
  <script src="../../js/main.js"></script>

  <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
  <!-- FastClick -->
  <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
  <!-- AdminLTE App -->
  <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

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

    .container-fluid, .container {
      position: relative;
      z-index: 1;
    }

    /* Header de título */
    .thead {
      background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
      color: #ffffff !important;
      font-size: 20px !important;
      padding: 20px !important;
      border-radius: 15px 15px 0 0 !important;
      border: 2px solid #40E0FF !important;
      border-bottom: none !important;
      box-shadow: 0 5px 20px rgba(64, 224, 255, 0.3);
      text-shadow: 0 0 15px rgba(64, 224, 255, 0.5);
      letter-spacing: 2px;
    }

    /* Card principal */
    .modern-card {
      background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
      border: 2px solid #40E0FF !important;
      border-top: none !important;
      border-radius: 0 0 20px 20px !important;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5),
                  0 0 30px rgba(64, 224, 255, 0.2) !important;
      padding: 40px 30px !important;
      margin-top: 0 !important;
      position: relative;
      overflow: hidden;
    }

    .modern-card::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(
        45deg,
        transparent,
        rgba(64, 224, 255, 0.1),
        transparent
      );
      transform: rotate(45deg);
      pointer-events: none;
    }

    /* Sección del formulario */
    .form-section {
      background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
      padding: 35px !important;
      border-radius: 20px !important;
      margin: 20px 0 !important;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4),
                  inset 0 0 20px rgba(64, 224, 255, 0.1) !important;
      border: 2px solid #40E0FF !important;
      border-left: 5px solid #40E0FF !important;
      position: relative;
    }

    .form-section h5 {
      color: #ffffff !important;
      font-weight: 700 !important;
      margin-bottom: 25px !important;
      border-bottom: 2px solid #40E0FF !important;
      padding-bottom: 15px !important;
      font-size: 1.3rem !important;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      text-shadow: 0 0 15px rgba(64, 224, 255, 0.5);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .form-section h5 i {
      color: #40E0FF !important;
      text-shadow: 0 0 20px rgba(64, 224, 255, 0.8);
    }

    /* Grupos de formulario */
    .modern-form-group {
      margin-bottom: 30px !important;
      position: relative;
    }

    .modern-form-group label {
      color: #40E0FF !important;
      font-weight: 600 !important;
      margin-bottom: 10px !important;
      display: block;
      font-size: 1rem !important;
      letter-spacing: 0.5px;
      text-shadow: 0 0 10px rgba(64, 224, 255, 0.3);
    }

    .modern-form-group label i {
      margin-right: 8px;
      color: #40E0FF !important;
    }

    /* Inputs del formulario */
    .modern-form-control {
      background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
      border: 2px solid #40E0FF !important;
      border-radius: 15px !important;
      padding: 15px 20px !important;
      font-size: 1rem !important;
      transition: all 0.3s ease !important;
      color: #ffffff !important;
      box-shadow: 0 4px 15px rgba(64, 224, 255, 0.2) !important;
    }

    .modern-form-control::placeholder {
      color: rgba(255, 255, 255, 0.5) !important;
    }

    .modern-form-control:focus {
      border-color: #00D9FF !important;
      box-shadow: 0 6px 25px rgba(64, 224, 255, 0.4) !important;
      outline: none !important;
      background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
      color: #ffffff !important;
      transform: translateY(-2px);
    }

    .modern-form-control:hover {
      border-color: #00D9FF !important;
      background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
      box-shadow: 0 5px 20px rgba(64, 224, 255, 0.3) !important;
    }

    /* Contenedor de botones */
    .button-container {
      text-align: center !important;
      margin-top: 35px !important;
      padding: 25px !important;
      background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
      border-radius: 15px !important;
      border: 2px solid #40E0FF !important;
      box-shadow: 0 5px 20px rgba(64, 224, 255, 0.2);
    }

    /* Botones */
    .modern-btn {
      padding: 14px 40px !important;
      border-radius: 25px !important;
      font-weight: 600 !important;
      text-transform: uppercase !important;
      letter-spacing: 1.5px !important;
      transition: all 0.3s ease !important;
      border: 2px solid !important;
      margin: 0 10px !important;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3) !important;
      font-size: 1rem !important;
    }

    .modern-btn-success {
      background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
      color: #ffffff !important;
      border-color: #28a745 !important;
    }

    .modern-btn-success:hover {
      background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
      color: #28a745 !important;
      border-color: #20c997 !important;
      transform: translateY(-3px) !important;
      box-shadow: 0 8px 30px rgba(40, 167, 69, 0.5) !important;
    }

    .modern-btn-danger {
      background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
      color: #ffffff !important;
      border-color: #dc3545 !important;
      text-decoration: none !important;
      display: inline-block !important;
    }

    .modern-btn-danger:hover {
      background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
      color: #dc3545 !important;
      border-color: #e74c3c !important;
      transform: translateY(-3px) !important;
      box-shadow: 0 8px 30px rgba(220, 53, 69, 0.5) !important;
      text-decoration: none !important;
    }

    /* Alertas */
    .alert-success {
      background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
      border: 2px solid #28a745 !important;
      color: #ffffff !important;
      border-radius: 15px !important;
      padding: 20px !important;
      box-shadow: 0 5px 20px rgba(40, 167, 69, 0.3) !important;
      margin: 20px 0 !important;
    }

    .alert-success i {
      color: #28a745 !important;
      margin-right: 10px;
    }

    /* Footer */
    .main-footer {
      background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
      border-top: 2px solid #40E0FF !important;
      color: #ffffff !important;
      box-shadow: 0 -4px 20px rgba(64, 224, 255, 0.2);
      margin-top: 50px;
    }

    /* Content box */
    .box {
      background: transparent !important;
      border: none !important;
      box-shadow: none !important;
    }

    /* Animaciones */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .modern-card {
      animation: fadeInUp 0.6s ease-out;
    }

    .form-section {
      animation: fadeInUp 0.8s ease-out;
    }

    /* Efecto de brillo en hover */
    .form-section::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(
        45deg,
        transparent,
        rgba(64, 224, 255, 0.05),
        transparent
      );
      transform: rotate(45deg);
      transition: all 0.6s ease;
      pointer-events: none;
    }

    .form-section:hover::before {
      left: 100%;
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
    @media screen and (max-width: 768px) {
      .thead {
        font-size: 16px !important;
        padding: 15px !important;
      }

      .modern-card {
        padding: 20px 15px !important;
      }

      .form-section {
        padding: 20px !important;
      }

      .form-section h5 {
        font-size: 1.1rem !important;
      }

      .modern-btn {
        padding: 12px 25px !important;
        font-size: 0.9rem !important;
        margin: 5px !important;
      }

      .button-container {
        padding: 15px !important;
      }
    }

    @media screen and (max-width: 576px) {
      .modern-btn {
        display: block !important;
        width: 100% !important;
        margin: 10px 0 !important;
      }
    }
  </style>

</head>

<body>
  <section class="content container-fluid">
    <div class="container box">
      <div class="container-fluid">

        <div class="row">
          <div class="col-md-2"></div>
          <div class="col-md-8">
            <div class="thead">
              <tr><strong><center>EDITAR ASEGURADORA</center></strong>
            </div>
            <div class="modern-card">
              <?php
              $id = $_GET['id'];
              $sql = "SELECT * from cat_aseg where id_aseg = $id";
              $result = $conexion->query($sql);
              while ($row_datos = $result->fetch_assoc()) {
              ?>
              <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                <div class="form-section">
                  <h5><i class="fas fa-building"></i> Información de la aseguradora</h5>
                  <div class="modern-form-group">
                    <label for="aseg"><i class="fas fa-building"></i> Aseguradora:</label>
                    <input type="text" size="30" name="aseg" placeholder="Aseguradora" id="aseg" class="form-control modern-form-control" value="<?php echo $row_datos['aseg']; ?>" required>
                  </div>
                  <div class="modern-form-group">
                    <label for="tip_precio"><i class="fas fa-dollar-sign"></i> Tipo de precio:</label>
                    <input type="text" size="30" name="tip_precio" placeholder="Tipo de precio" id="tip_precio" class="form-control modern-form-control" value="<?php echo $row_datos['tip_precio']; ?>" required>
                  </div>
                  <div class="button-container">
                    <input type="submit" name="edit" class="btn modern-btn modern-btn-success" value="GUARDAR">
                    <a href="../aseguradoras/aseguradora.php" class="btn modern-btn modern-btn-danger">CANCELAR</a>
                  </div>
                </div>
              </form>
              <?php } ?>
            </div>
          </div>
          <div class="col-md-2"></div>
      </div>
      <?php

      if (isset($_POST['edit'])) {

        $aseg    = mysqli_real_escape_string($conexion, (strip_tags($_POST["aseg"], ENT_QUOTES))); //Escanpando caracteres
        $tip_precio    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tip_precio"], ENT_QUOTES))); //Escanpando caracteres

        $sql2 = "UPDATE cat_aseg SET aseg = '$aseg', tip_precio = $tip_precio  WHERE id_aseg = $id";
        $result = $conexion->query($sql2);

        echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> Dato insertado correctamente...</p>";
        echo '<script type="text/javascript">window.location.href="aseguradora.php";</script>';
      }
      ?>
    </div>
  </section>
  </div>
  <footer class="main-footer">
    <?php
    include("../../template/footer.php");
    ?>
  </footer>


</body>

</html>
