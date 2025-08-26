<?php
session_start();
include "../../conexionbd.php";
include "../header_ceye.php";


?>

<!DOCTYPE html>
<html>

<head>

  <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″ />


  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

  <!-- Latest compiled and minified JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>


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


        <div class="row">
          <div class="col-sm-4"></div>
          <div class="col-sm-4"></div>
          <div class="col-sm-4"><a type="submit" class="btn btn-primary btn-block" href="../../template/menu_ceye.php">Regresar</a>
          </div>
        </div>
        <br>
        <center>
          <h3>BUSCAR PACIENTE</h3>
        </center>
        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label class="col-sm-3 control-label">Paciente: </label>
            <div class="col-md-6">
              <!--  <select name="serv" class="selectpicker" data-live-search="true"> -->
              <select class="selectpicker" data-live-search="true" name="paciente" required>
                <?php

                $query = "SELECT * from paciente p, dat_ingreso di where p.Id_exp = di.Id_exp and di.activo='SI'";
                $result = $conexion->query($query);

                while ($row = $result->fetch_assoc()) {
                  echo "<option value='" . $row['id_atencion'] . "'>" . $row['nom_pac'] . " " . $row['papell'] . " " . $row['sapell'] . "</option>";
                }
                ?>
              </select>

            </div>
          </div>
          <div class="col-md-6">
            <input type="submit" name="btnpaciente" class="btn btn-block btn-success" value="Buscar Paciente">
          </div>

        </form>
      </div>
    </div>
    <?php

    include "../../conexionbd.php";
    //    $resultado2 = $conexion->query("SELECT id_serv, serv_cve, serv_costo, serv_umed,serv_activo FROM cat_servicios") or die($conexion->error);

    if (isset($_POST['btnpaciente'])) {
      $paciente = mysqli_real_escape_string($conexion, (strip_tags($_POST["paciente"], ENT_QUOTES))); //Escanpando caracteres

      echo '<script type="text/javascript"> window.location.href="order_enf_ceye.php?paciente=' . $paciente . '";</script>';
    }

    if ((isset($_GET['paciente']))) {
      $paciente1 = $_GET['paciente'];
      $usuario = $_SESSION['login'];
      $usuario2 = $usuario['id_usua'];
      $sql_paciente = "SELECT p.nom_pac, p.papell, p.sapell FROM paciente p, dat_ingreso di WHERE p.Id_exp = di.Id_exp and di.id_atencion = $paciente1";

      $result_pac = $conexion->query($sql_paciente);
      while ($row_pac = $result_pac->fetch_assoc()) {
        $pac = $row_pac['nom_pac'] . ' ' . $row_pac['papell'] . ' ' . $row_pac['sapell'];
      }

    ?>

      <div class="container box">
        <div class="content">

          <center>
            <h3>PACIENTE: <?php echo $pac ?></h3>
          </center>
          <br>

          <center>
            <h3>AGREGAR MATERIALES Y MEDICAMENTOS</h3>
          </center>

          <hr>
         <!--<div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label class="col-sm-3 control-label">Selecciona un páquete: </label>
                  <div class="col-md-6">-->
                    <!--  <select name="serv" class="selectpicker" data-live-search="true"> -->
                   <!-- <select class="selectpicker" data-live-search="true" name="paquete" required>-->
                      <?php

                      $query = "SELECT DISTINCt nombre FROM `paquetes_ceye` ";
                      $result = $conexion->query($query);

                      while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['nombre'] . "'>" . $row['nombre'] . "</option>";
                      }
                      ?>
                    <!--</select>

                  </div>
                </div>
                <div class="col-md-6">
                  <input type="submit" name="btnpaquete" class="btn btn-block btn-success" value="Seleccionar">
                </div>
              </form>
            </div>
          </div>-->

          <?php

          if (isset($_POST['btnpaquete'])) {
            $paquete = mysqli_real_escape_string($conexion, (strip_tags($_POST["paquete"], ENT_QUOTES))); //Escanpando caracteres
            $id_usua = $usuario1['id_usua'];
            echo '<script type="text/javascript">window.location.href = "cargar_paquete.php?q=cargar&paquete=' . $paquete . '&id_usua=' . $id_usua . '&paciente=' . $paciente1 . '";</script>';
          }

          ?>
          <hr>
            <div class="row">
                <div class="col-md-6">
                    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="control-label col-sm-3" for="">Materiales y Medicamento:</label>
                            <div class="col-md-9">
                                <select class="selectpicker" data-live-search="true" name="med" required>
                                    <?php
                                    $sql = "SELECT * FROM material_ceye, stock_ceye where material_ceye.material_controlado = 'NO' AND material_ceye.material_id = stock_ceye.material_id and stock_ceye.stock_qty != 0";
                                    $result = $conexion->query($sql);
                                    while ($row_datos = $result->fetch_assoc()) {
                                        echo "<option value='" . $row_datos['material_id'] . "'>" . $row_datos['material_codigo'] . ' ' . $row_datos['material_nombre'] . "</option>";
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
                            <input type="submit" name="btnagregar" class="btn btn-block btn-success" value="Agregar">
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="control-label col-sm-3" for="">Servicios CEyE:</label>
                            <div class="col-md-9">
                                <select class="selectpicker" data-live-search="true" name="serv" required>
                                    <?php
                                    $sql_serv = "SELECT * FROM cat_servicios where tipo =4 and serv_activo = 'SI'";
                                    $result_serv = $conexion->query($sql_serv);
                                    while ($row_serv = $result_serv->fetch_assoc()) {
                                        echo "<option value='" . $row_serv['id_serv'] . "'>" . $row_serv['serv_desc'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3" for="">Cantidad:</label>
                            <div class="col-sm-3">
                                <input type="number" min="1" step="1" class="form-control" name="qty_serv" placeholder="Ingresa la cantidad" required="">
                            </div>
                        </div>


                        <div class="col-sm-4">
                            <input type="submit" name="btnserv" class="btn btn-block btn-success" value="Agregar">
                        </div>
                    </form>
                </div>
            </div>

<hr>
          <?php

          if (isset($_POST['btnagregar'])) {
            $id_usua = $usuario1['id_usua'];
            $item_id =  mysqli_real_escape_string($conexion, (strip_tags($_POST["med"], ENT_QUOTES))); //Escanpando caracteres
            $cart_uniquid = uniqid();
            $qty =  mysqli_real_escape_string($conexion, (strip_tags($_POST["qty"], ENT_QUOTES))); //Escanpando caracteres

            $sql_stock = "SELECT * FROM stock_ceye s where $item_id = s.material_id ";
            //echo $sql_stock;
            $result_stock = $conexion->query($sql_stock);

            while ($row_stock = $result_stock->fetch_assoc()) {
              $stock_id = $row_stock['stock_id'];
              $stock_qty = $row_stock['stock_qty'];
            }
             date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");
            // echo $stock_qty - $qty;
            if (($stock_qty - $qty) >= 0) {
              $sql2 = "INSERT INTO cart_ceye(material_id,cart_qty,cart_stock_id,id_usua,cart_uniqid, paciente,cart_fecha)VALUES($item_id,$qty, $stock_id,$id_usua,'$cart_uniquid', $paciente1, $fecha_actual);";
              echo $sql2;
              $result_cart = $conexion->query($sql2);
              $stock = $stock_qty - $qty;
              $sql3 = "UPDATE stock_ceye set stock_qty=$stock where stock_id = $stock_id";
              $result3 = $conexion->query($sql3);
            }

            echo '<script type="text/javascript">window.location.href = "order_enf_ceye.php?paciente=' . $paciente1 . '";</script>';
          }

          if (isset($_POST['btnserv'])) {
            $id_usua = $usuario1['id_usua'];
            $serv_id =  mysqli_real_escape_string($conexion, (strip_tags($_POST["serv"], ENT_QUOTES))); //Escanpando caracteres
            $cart_uniquid = uniqid();
            $qty_serv =  mysqli_real_escape_string($conexion, (strip_tags($_POST["qty_serv"], ENT_QUOTES))); //Escanpando caracteres

            $sql_in_serv = "INSERT INTO cart_serv(servicio_id,cart_qty,id_usua,cart_uniqid, paciente,cart_fecha)VALUES($serv_id,$qty_serv,$id_usua,'$cart_uniquid', $paciente1, $fecha_actual);";
            // echo $sql2;
            $result_cart_serv = $conexion->query($sql_in_serv);


            echo '<script type="text/javascript">window.location.href = "order_enf_ceye.php?paciente=' . $paciente1 . '";</script>';
          }

          ?>
          <div class="table-responsive">
            <!--<table id="myTable" class="table table-striped table-hover">-->

            <table class="table table-bordered table-striped" id="mytable">

              <thead class="thead" style="background-color: #0c675e">
                <tr>
                  <th>NO.</th>
                  <th>Material</th>
                  <th>Cantidad</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php
                $paciente1 = $_GET['paciente'];
                $resultado2 = $conexion->query("SELECT * from cart_ceye c, material_ceye i where $paciente1 = c.paciente and i.material_id = c.material_id") or die($conexion->error);
                $no = 1;
                while ($row_lista = $resultado2->fetch_assoc()) {
                  $cart_id = $row_lista['cart_id'];
                  $cart_stock_id = $row_lista['cart_stock_id'];
                  $cart_qty = $row_lista['cart_qty'];
                  echo '<tr>'
                    . '<td>' . $no . '</td>'
                    . '<td>' . $row_lista['material_nombre'] . '</td>'
                    . '<td>' . $cart_qty . '</td>'
                    . '<td> <a type="submit" class="btn btn-danger btn-sm" href="cargar_paquete.php?q=eliminar&paciente= ' . $paciente1 . '&cart_id=' . $cart_id . '&cart_stock_id=' . $cart_stock_id . '&cart_qty=' . $cart_qty . '"><span class = "fa fa-trash"></span></a></td>';
                  echo '</tr>';
                  $no++;
                }

                $resultado3 = $conexion->query("SELECT * from cart_serv c, cat_servicios i where $paciente1 = c.paciente and i.id_serv = c.servicio_id") or die($conexion->error);
            
                while ($row_lista_serv = $resultado3->fetch_assoc()) {
                  $cart_id = $row_lista_serv['cart_id'];
                  $cart_qty = $row_lista_serv['cart_qty'];
                  echo '<tr>'
                    . '<td>' . $no . '</td>'
                    . '<td>' . $row_lista_serv['serv_desc'] . '</td>'
                    . '<td>' . $cart_qty . '</td>'
                    . '<td> <a type="submit" class="btn btn-danger btn-sm" href="cargar_paquete.php?q=eliminar_serv&paciente= ' . $paciente1 . '&cart_id=' . $cart_id . '&cart_qty=' . $cart_qty . '"><span class = "fa fa-trash"></span></a></td>';
                  echo '</tr>';
                  $no++;
                }
                ?>
              </tbody>
            </table>

            <div class="col-md-12">
              <br>
              <br>
              <center>
                <?php
                echo '<a type="submit" class="btn btn-success btn-block" href="manipulacarritoenf.php?q=comf_cart&paciente=' . $paciente1 . '&id_usua=' . $usuario2 . '"><span>Confirmar</span></a>';
                ?>
              </center>
            </div>
          </div>
        </div>
      </div>
    <?php
    }
    ?>
  </section>
  </div>

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