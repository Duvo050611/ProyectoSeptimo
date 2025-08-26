<?php
session_start();

if (!isset($_SESSION['login'])) {
  header("Location: ../index.php");
}

include "../header_enfermera.php";
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
        
          <div>
            <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
                <tr><strong><center>EDITAR INGRESOS</center></strong>
            </div>
            <br>
            <?php
            $id = $_GET['id_dialisis'];

            $sql = "SELECT * from dialisis_p where id_dialisis = $id";
            $result = $conexion->query($sql);
            while ($row_datos = $result->fetch_assoc()) {
            ?>  
              <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">

<div class="container">
  <div class="row">
    <div class="col-sm">
     <div class="form-group">
                  <label for="nobañ">No. de baño:</label>
                  <input type="text" name="baño" id="nobañ" class="form-control" 
                  value="<?php echo $row_datos['baño']; ?>" required>
                </div>
    </div>
    <div class="col-sm">
   <div class="form-group">
                  <label for="tipo">Tipo de solución:</label>
                   <input type="text" size="30" name="tiposol"  id="tipo" class="form-control" 
                  value="<?php echo $row_datos['tiposol']; ?>" required>
                </div>
    </div>
    <div class="col-sm">
      <div class="form-group">
                  <label for="hen">Hrs de entrada:</label>
                  <input type="time" size="30" name="hrentrada"  id="hen" class="form-control" 
                  value="<?php echo $row_datos['hrentrada']; ?>" required>
                </div>
    </div>
    <div class="col-sm">
      <div class="form-group">
                  <label for="inst">Hrs de salida:</label>
                  <input type="time" size="30" name="hrsalida"  id="inst" class="form-control" 
                  value="<?php echo $row_datos['hrsalida']; ?>" required>
                </div>
    </div>
    <div class="col-sm">
      <div class="form-group">
                  <label for="dii">Cantidad de entrada:</label>
                  <input type="text" size="30" name="centrada"  id="dii" class="form-control" 
                  value="<?php echo $row_datos['centrada']; ?>" required>
                </div>
    </div>
<div class="col-sm-2">
      <div class="form-group">
                  <label for="fechcam">Cantidad de salida:</label>
                  <input type="text" size="30" name="csalida"  id="fechcam" class="form-control" 
                  value="<?php echo $row_datos['csalida']; ?>" required>
                </div>
    </div>
  </div>
</div>
<div class="container">
  <div class="row">
    <!--<div class="col-sm">
      <div class="form-group">
                  <label for="balpar">Balance parcial</label>
                  <input type="text" size="30" name="balparcial"  id="balpar" class="form-control" 
                  value="<?php echo $row_datos['balparcial']; ?>" required>
                </div>
    </div>-->
    <div class="col-sm">
      <div class="form-group">
                  <label for="balto">Balance total</label>
                  <input type="text" size="30" name="baltot"  id="balto" class="form-control" 
                  value="<?php echo $row_datos['baltot']; ?>" required>
                </div>
    </div>
    <div class="col-sm-8">
      <div class="form-group">
                  <label for="obs">Observaciones</label>
                <textarea class="form-control" name="obs" id="obs"><?php echo $row_datos['obs']; ?></textarea>
                </div>
    </div>
  </div>
</div>



                <center><input type="submit" name="edit" class="btn btn-success" value="Guardar">
                  <a href="nota_dialisis.php" class="btn btn-danger">Cancelar</a>
                </center>
<br>
          </div>
        <?php } ?>
        </form>
        </div>
        <div class="col-md-2"></div>
      </div>
      <?php

      if (isset($_POST['edit'])) {

$baño    = mysqli_real_escape_string($conexion, (strip_tags($_POST["baño"], ENT_QUOTES)));
$tiposol    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tiposol"], ENT_QUOTES))); 
$hrentrada    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hrentrada"], ENT_QUOTES)));
$hrsalida    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hrsalida"], ENT_QUOTES)));
$centrada    = mysqli_real_escape_string($conexion, (strip_tags($_POST["centrada"], ENT_QUOTES)));
$csalida    = mysqli_real_escape_string($conexion, (strip_tags($_POST["csalida"], ENT_QUOTES)));
$balparcial    = mysqli_real_escape_string($conexion, (strip_tags($_POST["balparcial"], ENT_QUOTES))); 
$baltot    = mysqli_real_escape_string($conexion, (strip_tags($_POST["baltot"], ENT_QUOTES)));
$obs    = mysqli_real_escape_string($conexion, (strip_tags($_POST["obs"], ENT_QUOTES)));
date_default_timezone_set('America/Guatemala');
$fech = date("Y-m-d");

$balparcial=$csalida-$centrada;

$sql2 = "UPDATE dialisis_p SET fecha_registro='$fech', baño = '$baño', tiposol = '$tiposol',hrentrada = '$hrentrada',hrsalida = '$hrsalida',centrada = '$centrada',csalida = '$csalida',balparcial = '$balparcial', baltot = '$baltot', obs = '$obs' WHERE id_dialisis = $id";
$result = $conexion->query($sql2);

        echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> Dato insertado correctamente...</p>";
        echo '<script type="text/javascript">window.location.href = "nota_dialisis.php";</script>';
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