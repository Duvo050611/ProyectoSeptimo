<?php
session_start();

if (!isset($_SESSION['login'])) {
  header("Location: ../index.php");
}

include("../header_configuracion.php");
?>

<head>
  <style>
    .elimina-cama-box {
      /* ...existing code... */
    }
    .elimina-cama-box .form-group {
      margin-bottom: 22px;
    }
    .elimina-cama-box .botones-row {
      display: flex;
      justify-content: center;
      gap: 18px;
      margin-top: 18px;
    }
    .elimina-cama-box .btn-warning {
      /* ...existing code... */
      min-width: 160px;
      margin-bottom: 0;
    }
    .elimina-cama-box .btn-danger {
      /* ...existing code... */
      min-width: 160px;
      margin-bottom: 0;
    }
  </style>
  <style>
    body {
      background: #f4f6fb;
    }
    .elimina-cama-box {
      background: #fff;
      border: 2px solid #e3e6f3;
      border-radius: 20px;
      box-shadow: 0 8px 32px rgba(43,45,127,0.10);
      padding: 48px 38px 36px 38px;
      margin-top: 60px;
      max-width: 520px;
      margin-left: auto;
      margin-right: auto;
    }
    .elimina-cama-box h1 {
      color: #2b2d7f;
      font-weight: 800;
      margin-bottom: 36px;
      text-align: center;
      letter-spacing: 1.5px;
      font-size: 2.2rem;
    }
    .elimina-cama-box .form-control[disabled] {
      background: #f4f6fb;
      color: #2b2d7f;
      font-weight: 700;
      border: 1.5px solid #bfc8e6;
      border-radius: 10px;
      font-size: 1.1rem;
      box-shadow: none;
    }
    .elimina-cama-box .form-group label {
      color: #2b2d7f;
      font-weight: 700;
      font-size: 17px;
      margin-bottom: 6px;
    }
    .elimina-cama-box .btn-warning {
      background: #2b2d7f;
      color: #fff;
      border: none;
      font-weight: 700;
      border-radius: 10px;
      padding: 12px 28px;
      box-shadow: 0 2px 8px rgba(43,45,127,0.10);
      transition: background 0.2s;
      font-size: 1.1rem;
      margin-bottom: 10px;
    }
    .elimina-cama-box .btn-warning:hover {
      background: #1a1c4c;
    }
    .elimina-cama-box .btn-danger {
      font-weight: 700;
      border-radius: 10px;
      padding: 12px 28px;
      font-size: 1.1rem;
      margin-bottom: 10px;
    }
    #mensaje {
      font-size: 18px;
      font-weight: 700;
      color: #fff;
      background: #2b2d7f;
      border-radius: 10px;
      padding: 14px 22px;
      margin-top: 24px;
      text-align: center;
      box-shadow: 0 2px 8px rgba(43,45,127,0.10);
    }
  </style>
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
    <div class="elimina-cama-box">
      <h1>¿Estás seguro de que deseas eliminar la cama?</h1>
            <?php
            $id = $_GET['id'];

            $sql = "SELECT id,estatus,tipo, num_cama, habitacion from cat_camas where id = $id";
            $result = $conexion->query($sql);
            while ($row_datos = $result->fetch_assoc()) {
            ?>
              <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label>Número de cama:</label>
                  <input type="text" name="num_cama" class="form-control" value="<?php echo $row_datos['num_cama'] ?>" disabled>
                </div>
                <div class="form-group">
                  <label>Estatus:</label>
                  <input type="text" name="estatus" class="form-control" value="<?php echo $row_datos['estatus'] ?>" disabled>
                </div>
                <div class="form-group">
                  <label>Tipo:</label>
                  <input type="text" name="tipo" class="form-control" value="<?php echo $row_datos['tipo'] ?>" disabled>
                </div>
                <div class="form-group">
                  <label>Habitación:</label>
                  <input type="text" name="tipo" class="form-control" value="<?php echo $row_datos['habitacion'] ?>" disabled>
                </div>
              <?php
            }
              ?>
              <div class="form-group">
                <div class="botones-row">
                  <input type="submit" name="del" class="btn btn-warning" value="Eliminar Datos">
                  <a href="../../template/menu_configuracion.php" class="btn btn-danger">Cancelar</a>
                </div>
              </div>
              </form>
          </div>
          <div class="col-md-2"></div>
        </div>
        <?php

        if (isset($_POST['del'])) {
          /*  $num_cama    = mysqli_real_escape_string($conexion, (strip_tags($_POST["num_cama"], ENT_QUOTES))); //Escanpando caracteres 
  $estatus    = mysqli_real_escape_string($conexion, (strip_tags($_POST["estatus"], ENT_QUOTES))); //Escanpando caracteres 
  $tipo    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tipo"], ENT_QUOTES))); //Escanpando caracteres 
*/
          $sql2 = "DELETE FROM cat_camas WHERE id = $id";
          $result = $conexion->query($sql2);

          echo "<p class='alert alert-danger' id='mensaje'> <i class=' fa fa-check'></i> Dato eliminado correctamente...</p>";
        }
        ?>
      </div>
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