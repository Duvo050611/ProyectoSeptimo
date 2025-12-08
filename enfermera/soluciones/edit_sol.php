<?php
session_start();

if (!isset($_SESSION['login'])) {
  header("Location: ../index.php");
}
include '../../conexionbd.php';
$conexion = ConexionBD::getInstancia()->getConexion();
include "../header_enfermera.php";
?>

<head>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/fedist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
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

    <div class="container box">
      <div class="container">

        <div class="row">

          <div>
              <div class="thead" style="background-color:#2b2d7f;color:white;font-size:20px;">
                  <strong><center>EDITAR SOLUCIONES</center></strong>
              </div>
              <br>
            <?php
            $id = $_GET['id_sol_enf'];

            $sql = "SELECT * from sol_enf where id_sol_enf = $id";
            $result = $conexion->query($sql);
            while ($row_datos = $result->fetch_assoc()) {
            ?>
              <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">

<div class="container">
  <div class="row">
    <div class="col-sm-2">
     <div class="form-group">
                  <label for="hori">Inicio:</label>
                  <input type="time" class="form-control" value="<?php echo $row_datos['hora_i']?>" id="hori" name="hora_i" required="">


                </div>
    </div>
     <div class="col-sm-2">
     <div class="form-group">
                  <label for="pcv">Ml/hrs:</label>
                  <input type="text" class="form-control" value="<?php echo $row_datos['pvc']?>" id="pcv" name="pvc" required="">


                </div>
    </div>
    <div class="col-sm-2">
     <div class="form-group">
                  <label for="hort">Termino:</label>
                  <input type="time" class="form-control" value="<?php echo $row_datos['hora_t']?>" id="hort" name="hora_t" required="">


                </div>
    </div>
    <div class="col-sm-3">
      <div class="form-group">
                  <label for="sol">Soluciones:</label>
                  <input type="text" size="30" name="sol"  id="sol" class="form-control"
                  value="<?php echo $row_datos['sol']; ?>" required>
                </div>
    </div>
     <div class="col-sm-2">
      <div class="form-group">
                  <label for="tcate">Tipo catéter:</label>
                  <input type="text" size="30" name="tcate"  id="tcate" class="form-control"
                  value="<?php echo $row_datos['tcate']; ?>" required>
                </div>
    </div>

   <div class="col-sm-2">
      <div class="form-group">
                  <label for="vol">Volume total:</label>
                  <input type="text" size="30" name="vol"  id="vol" class="form-control"
                  value="<?php echo $row_datos['vol']; ?>" required>
                </div>
    </div>
     <div class="col-sm-3">
      <div class="form-group">
                  <label for="fecha">Fecha termino:</label>
                  <input type="date" size="30" name="fecha_termino" id="fecha_termino" class="form-control" value="<?php echo $row_datos['fecha_termino']; ?>" required>
      </div>
    </div>
    <div class="col-sm-3">
      <div class="form-group">
                  <label for="fecha">Fecha reporte:</label>
                  <input type="date" size="30" name="fecha" id="fecha" class="form-control"
                  value="<?php echo $row_datos['sol_fecha']; ?>" required>
      </div>
    </div>
    <div class="col-sm-3">
      <div class="form-group">
                  <label for="tipo">Tipo:</label>
                  <select name="tipo" id="tipo" class="form-control">
                      <option value="<?php echo $row_datos['tipo']; ?>"><?php echo $row_datos['tipo']; ?></option>
                      <option value="TERAPIA INTENSIVA">TERAPIA INTENSIVA</option>
                      <option value="HOSPITALIZACIÓN">HOSPITALIZACIÓN</option>
                      <option value="QUIROFANO">QUIROFANO</option>
                      <option value="OBSERVACIÓN">OBSERVACIÓN</option>
                  </select>

      </div>
    </div>
  </div>
</div>
                <center><input type="submit" name="edit" class="btn btn-success btn-sm" value="Guardar">


<button type="button" class="btn btn-danger btn-sm" onclick="history.back()">Regresar...</button>


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

$hora_i    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hora_i"], ENT_QUOTES)));
$hora_t    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hora_t"], ENT_QUOTES)));
$sol       = mysqli_real_escape_string($conexion, (strip_tags($_POST["sol"], ENT_QUOTES)));
$tcate     = mysqli_real_escape_string($conexion, (strip_tags($_POST["tcate"], ENT_QUOTES)));
//$sitio   = mysqli_real_escape_string($conexion, (strip_tags($_POST["sitio"], ENT_QUOTES)));
$vol       = mysqli_real_escape_string($conexion, (strip_tags($_POST["vol"], ENT_QUOTES)));
  $tipo       = mysqli_real_escape_string($conexion, (strip_tags($_POST["tipo"], ENT_QUOTES)));
$pvc       = mysqli_real_escape_string($conexion, (strip_tags($_POST["pvc"], ENT_QUOTES)));
$sol_fecha = mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha"], ENT_QUOTES)));
$fecha_termino = mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha_termino"], ENT_QUOTES)));
$fecha_actual = date("Y-m-d H:i");

        $sql2 = "UPDATE sol_enf SET tipo = '$tipo',hora_i = '$hora_i', hora_t = '$hora_t',sol = '$sol',tcate = '$tcate', sitio = '$sitio',vol = '$vol',sol_fecha = '$sol_fecha',pvc = '$pvc',fecha_registro='$fecha_actual',fecha_termino='$fecha_termino' WHERE id_sol_enf = $id";
        $result = $conexion->query($sql2);

        echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> Dato insertado correctamente...</p>";
        echo '<script type="text/javascript">window.location.href = "soluciones.php";</script>';
      }
      ?>
    </div>
  <footer class="main-footer">
    <?php
    include("../../template/footer.php");
    ?>
  </footer>
</body>
</html>