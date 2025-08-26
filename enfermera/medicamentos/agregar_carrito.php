<?php
session_start();
include "../../conexionbd.php";
include("../header_enfermera.php");

$resultado = $conexion->query("select paciente.*, dat_ingreso.id_atencion, triage.id_triage
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp
inner join triage on dat_ingreso.id_atencion=triage.id_atencion where id_triage=id_triage
") or die($conexion->error);

$usuario = $_SESSION['login'];

?>

<!DOCTYPE html>
<html>

<head>

  <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″ />
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


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script>
    // Write on keyup event of keyword input element
    $(document).ready(function() {
      $("#search").keyup(function() {
        _this = this;
        // Show only matching TR, hide rest of them
        $.each($("#mytable tbody tr"), function() {
          if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
            $(this).hide();
          else
            $(this).show();
        });
      });
    });
  </script>


</head>

<body>
  <section class="content container-fluid">

    <!--------------------------
| Your Page Content Here |
-------------------------->


    <div class="container box">
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col  col-12">
              <h2>
                <a href="" data-target="#sidebar" data-toggle="collapse" class="d-md-none"><i class="fa fa-bars" id="side"></i></a>
                <center>
                  <font id="letra"><i class="fa fa-plus-square"></i>Productos de Farmacia</font>
              </h2>
              </center>
              <hr>
            </div>
          </div>
          <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
              <label class="control-label col-sm-3" for="">Paciente:</label>
              <div class="col-md-9">
                <select class="btn btn-default" name="paciente" required>
                  <?php
                  $sql = "SELECT * from paciente p, dat_ingreso di where p.Id_exp = di.Id_exp and di.activo='SI'";
                  $result = $conexion->query($sql);
                  while ($row_datos = $result->fetch_assoc()) {
                    echo "<option value='" . $row_datos['id_atencion'] . "'>" . $row_datos['Id_exp'] . ' ' . $row_datos['nom_pac'] . ' ' . $row_datos['papell'] . ' ' . $row_datos['sapell'] . "</option>";
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-3" for="">Cantidad:</label>
              <div class="col-sm-3">
                <input type="number" min="1" step="1" class="form-control" name="qty" placeholder="Ingresa la cantidad" required="">
              </div>
            </div>
            <div class="col-sm-4">
              <input type="submit" name="btnserv" class="btn btn-block btn-success" value="Agregar">
            </div>
          </form>
        </div>
  </section>
  </div>
  <?php

  if (isset($_POST['btnserv'])) {
    $stock_id = $_GET['stock_id'];
    $stock_qty = $_GET['stock_qty'];
    $item_id = $_GET['item_id'];

    $paciente    = mysqli_real_escape_string($conexion, (strip_tags($_POST["paciente"], ENT_QUOTES))); //Escanpando caracteres 
    $qty    = mysqli_real_escape_string($conexion, (strip_tags($_POST["qty"], ENT_QUOTES))); //Escanpando  
    $cart_uniquid = uniqid();
    $stock = $stock_qty - $qty;
    $usuario1 = $usuario['id_usua'];
    date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");
    
    if (!($stock < 10)) {
      $sql2 = "INSERT INTO cart(item_id,cart_qty,cart_stock_id,id_usua,cart_uniqid,paciente,cart_fecha)VALUES($item_id,$qty, $stock_id,$usuario1,'$cart_uniquid', $paciente,'$fecha_actual')";


      $result = $conexion->query($sql2);

      $sql2 = "UPDATE stock set stock_qty=$stock where stock_id = $stock_id";
      $result = $conexion->query($sql2);

      echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
      echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
      echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
      echo '<script>
                  $(document).ready(function() {
                      swal({
                          title: "Medicamento agregado correctamente", 
                          type: "success",
                          confirmButtonText: "ACEPTAR"
                      }, function(isConfirm) { 
                          if (isConfirm) {
                              window.location.href = "order.php";
                          }
                      });
                  });
              </script>';
    } else {
      echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
      echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
      echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
      echo '<script>
                          $(document).ready(function() {
                              swal({
                                  title: "Error al agregar medicamento, stock insuficiente, por favor valida la cantidad", 
                                  type: "error",
                                  confirmButtonText: "ACEPTAR"
                              }, function(isConfirm) { 
                                  if (isConfirm) {
                                      window.location.href = "agregar_carrito.php?stock_id=' . $stock_id . '&stock_qty=' . $stock_qty . '&item_id=' . $item_id . '";
                                  }
                              });
                          });
                      </script>';
    }
  }
  ?>

  <footer class="main-footer">
    <?php
    include("../../template/footer.php");
    ?>
  </footer>


  <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
  <!-- FastClick -->
  <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
  <!-- AdminLTE App -->
  <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

</body>

</html>