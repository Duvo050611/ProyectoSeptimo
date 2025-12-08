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

</head>

<body>
  <section class="content container-fluid">
    <div class="container box">
      <div class="container-fluid">

        <div class="row">
          <div class="col-md-2"></div>
          <div class="col-md-8">
            <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
                <tr><strong><center>EDITAR DIAGNÓSTICO</center></strong>
            </div>

            <!-- CSS personalizado para el formulario -->
            <style>
              .custom-card {
                background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
                border: none;
                border-radius: 15px;
                box-shadow: 0 8px 25px rgba(43, 45, 127, 0.15);
                overflow: hidden;
                margin-top: 20px;
              }

              .form-section {
                background: white;
                padding: 30px;
                border-radius: 15px;
                margin: 20px 0;
                box-shadow: 0 4px 15px rgba(43, 45, 127, 0.1);
                border-left: 5px solid #2b2d7f;
              }

              .form-section h5 {
                color: #2b2d7f;
                font-weight: bold;
                margin-bottom: 20px;
                border-bottom: 2px solid #e9ecef;
                padding-bottom: 10px;
              }

              .modern-form-group {
                margin-bottom: 25px;
                position: relative;
              }

              .modern-form-group label {
                color: #2b2d7f;
                font-weight: 600;
                margin-bottom: 8px;
                display: block;
                font-size: 14px;
              }

              .modern-form-control {
                border: 4px solid #e9ecef;
                border-radius: 10px;
                padding: 15px;
                font-size: 16px;
                transition: all 0.3s ease;
                background-color: #ffffff;
                box-shadow: 0 2px 4px rgba(0,0,0,0.02);
              }

              .modern-form-control:focus {
                border-color: #2b2d7f;
                box-shadow: 0 0 0 0.2rem rgba(43, 45, 127, 0.25);
                outline: none;
                background-color: #f8f9ff;
              }

              .modern-form-control:hover {
                border-color: #2b2d7f;
                background-color: #f8f9ff;
              }

              .modern-btn {
                padding: 12px 30px;
                border-radius: 25px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                transition: all 0.3s ease;
                border: none;
                margin: 0 10px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
              }

              .modern-btn-success {
                background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
                color: white;
              }

              .modern-btn-success:hover {
                background: linear-gradient(135deg, #218838 0%, #1e9f8a 100%);
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
                color: white;
              }

              .modern-btn-danger {
                background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%);
                color: white;
                text-decoration: none;
                display: inline-block;
              }

              .modern-btn-danger:hover {
                background: linear-gradient(135deg, #c82333 0%, #d63384 100%);
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
                color: white;
                text-decoration: none;
              }

              .button-container {
                text-align: center;
                margin-top: 30px;
                padding: 20px;
                background: #f8f9fa;
                border-radius: 10px;
              }

              .icon-input {
                position: relative;
              }

              .icon-input i {
                position: absolute;
                left: 15px;
                top: 50%;
                transform: translateY(-50%);
                color: #2b2d7f;
                z-index: 3;
              }

              .icon-input .modern-form-control {
                padding-left: 45px;
              }
            </style>

            <div class="custom-card">
              <?php
              $id = $_GET['id'];

              $sql = "SELECT * from cat_diag where id_diag = $id";
              $result = $conexion->query($sql);
              while ($row_datos = $result->fetch_assoc()) {
              ?>
              <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">

                <div class="form-section">
                  <h5><i class="fas fa-stethoscope"></i> Información del Diagnóstico</h5>

                  <div class="modern-form-group">
                    <label for="diag">
                      <i class="fas fa-clipboard-list"></i> Diagnóstico:
                    </label>
                    <div class="icon-input">
                      <i class="fas fa-stethoscope"></i>
                      <input type="text"
                             name="diag"
                             id="diag"
                             class="form-control modern-form-control"
                             value="<?php echo $row_datos['diagnostico']; ?>"
                             required
                             placeholder="Ingrese el diagnóstico">
                    </div>
                  </div>

                  <div class="modern-form-group">
                    <label for="id_cie10">
                      <i class="fas fa-code"></i> Clave CIE10:
                    </label>
                    <div class="icon-input">
                      <i class="fas fa-code"></i>
                      <input type="text"
                             name="id_cie10"
                             id="id_cie10"
                             class="form-control modern-form-control"
                             value="<?php echo $row_datos['id_cie10']; ?>"
                             required
                             placeholder="Ingrese la clave CIE10">
                    </div>
                  </div>
                </div>

                <div class="button-container">
                  <input type="submit" name="edit" class="btn modern-btn modern-btn-success" value="GUARDAR">
                  <a href="cat_diagnosticos.php" class="btn modern-btn modern-btn-danger">CANCELAR</a>
                </div>

              </form>
              <?php } ?>
            </div>
          </div>
          <div class="col-md-2"></div>
        </div>
      <?php

      if (isset($_POST['edit'])) {

        $diag    = mysqli_real_escape_string($conexion, (strip_tags($_POST["diag"], ENT_QUOTES))); //Escanpando caracteres
        $id_cie10    = mysqli_real_escape_string($conexion, (strip_tags($_POST["id_cie10"], ENT_QUOTES))); //Escanpando caracteres

        $sql2 = "UPDATE cat_diag SET diagnostico = '$diag', id_cie10 = '$id_cie10' WHERE id_diag = $id";
        $result = $conexion->query($sql2);

        echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> Dato insertado correctamente...</p>";
        echo '<script type="text/javascript">window.location.href = "cat_diagnosticos.php";</script>';
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
