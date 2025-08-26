<?php
session_start();
include "../../conexionbd.php";


$resultado = $conexion->query("select paciente.*, dat_ingreso.id_atencion, triage.id_triage
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp
inner join triage on dat_ingreso.id_atencion=triage.id_atencion where id_triage=id_triage
") or die($conexion->error);

$usuario = $_SESSION['login'];


if ($usuario['id_rol'] == 7) {
    include "../header_farmacia.php";

} else if ($usuario['id_rol'] == 4 or 5) {
    include "../header_farmacia.php";
} else{
    //session_unset();
    // session_destroy();
    echo "<script>window.Location='../../index.php';</script>";

}


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
         <tr><strong><center>AGREGAR PRODUCTOS AL STOCK</center></strong>
      </div><br>
            <?php
            $id = $_GET['id'];

            $sql = "SELECT * FROM item, stock, item_type where item_type.item_type_id=item.item_type_id and item.item_id = stock.item_id and stock.stock_qty != 0 and stock_id = $id";
            $result = $conexion->query($sql);
            while ($row_datos = $result->fetch_assoc()) {
            ?>
              <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                  <label class="control-label col-sm-3" for="">Producto:</label>
                  <div class="col-sm-9">
                    <input disabled value="<?php echo $row_datos['item_name'] ?>">

                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-3" for="">Cantidad:</label>
                  <div class="col-sm-9">
                    <input type="number" min="1" step="1" class="form-control" name="qty" placeholder="Ingresa la cantidad" required="">
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-sm-3" for="">Vence:</label>
                  <div class="col-sm-9">
                    <input type="date" class="form-control" name="xDate" required="">
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-sm-3" for="">Lote:</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="manu" required="">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-3 col-sm-9">
                      <a href="../Farmacia/perfilmedicamento.php" class="btn btn-danger">Cancelar</a>
                    <button type="submit" class="btn btn-success" name="edit">Guardar
                      <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    </button>
                  </div>
                </div>
              </form>
          </div>
        <?php } ?>
        <div class="col-md-2"></div>
        </div>
      </div>
      <?php
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");
      if (isset($_POST['edit'])) {
        $stock_cantidad = mysqli_real_escape_string($conexion, (strip_tags($_POST["qty"], ENT_QUOTES))); //Escanpando caracteres
        $stock_caducidad = mysqli_real_escape_string($conexion, (strip_tags($_POST["xDate"], ENT_QUOTES))); //Escanpando caracteres
        $stock_lote = mysqli_real_escape_string($conexion, (strip_tags($_POST["manu"], ENT_QUOTES))); //Escanpando caracteres

        $sql2 = "SELECT * FROM stock where stock_id = $id";
        $result = $conexion->query($sql2);

        while ($row_stock = $result->fetch_assoc()) {
          $stock = $row_stock['stock_qty'];
          $item_id = $row_stock['item_id'];
        }

        $stock_final = $stock + $stock_cantidad;


        $sql3 = "UPDATE stock set stock_qty=$stock_final where stock_id = $id";

        $result3 = $conexion->query($sql3);

        $sql4 = 'insert into entradas (entrada_item,entrada_qty,entrada_expiry,entrada_purchased,fecha)values(' . $item_id . ',' . $stock_cantidad . ', "' . $stock_caducidad . '","' . $stock_lote . '","' . $fech_actual . '")';
        // echo $sql4;
        $result4 = $conexion->query($sql4);
        echo '<script type="text/javascript">window.location ="perfilmedicamento.php"</script>';
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