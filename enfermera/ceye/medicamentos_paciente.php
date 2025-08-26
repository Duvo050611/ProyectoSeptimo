<?php
session_start();
include "../../conexionbd.php";
include "../header_ceye.php";
?>

<!DOCTYPE html>
<html>

<head>

  <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″/>
             <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
              integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk"
              crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
                integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
                crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
                integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
                crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
                integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
                crossorigin="anonymous"></script>
        <!--  Bootstrap  -->
        <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
              integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ"
              crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <script src="jquery-3.1.1.min.js"></script>
    <script src="js/select2.js"></script>



   
        <script>
            // Write on keyup event of keyword input element
            $(document).ready(function () {
                $("#search").keyup(function () {
                    _this = this;
                    // Show only matching TR, hide rest of them
                    $.each($("#mytable tbody tr"), function () {
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
<center><button type="button" class="btn btn-danger" onclick="history.back()">CANCELAR</button></center><br>
      <div class="container">
          
            <h3>PACIENTE: <?php 
       $paciente = $_GET['id_exp'];
$query = "SELECT * from paciente where Id_exp = $paciente";
                $result = $conexion->query($query);
                while ($row = $result->fetch_assoc()) {
                  $id_exp=$row['Id_exp'];
                  $nombre=$row['nom_pac'];
                  $papell=$row['papell'];
                  $sapell=$row['sapell'];
                }
            echo $nombre.' '.$papell.' '.$sapell ?></h3>
          
        </div><hr>
<div class="container">
  <form action="" method="POST" id="medicamentos">
     <div class="row">
        <table class="table table-bordered table-striped" id="mytable">
       <thead class="thead">
         <tr class="table-warning">
            <th colspan="7"><center><h5><strong>MEDICAMENTOS</strong></h5></center></th>
         </tr>
    <tr class="table-active">
      <th scope="col"><center>MEDICAMENTO</center></th>
      <th scope="col"><center>DOSIS</center></th>
      <th scope="col"><center>VIA</center></th>
      <th scope="col"><center>FRECUENCIA</center></th>
      <th><center>CANTIDAD CEYE</center></th>
      <th scope="col"><center></center></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><select data-live-search="true" class="form-control" name="med" data-live-search="true" id="mibuscador" style="width : 100%; heigth : 100%">
         <?php
         $sql = "SELECT * FROM material_ceye, stock_ceye where material_ceye.material_controlado = 'NO' AND material_ceye.material_id = stock_ceye.material_id and stock_ceye.stock_qty != 0 ORDER BY material_ceye.material_nombre ASC";
              $result = $conexion->query($sql);
               while ($row_datos = $result->fetch_assoc()) {
                 echo "<option value='" . $row_datos['material_id'] . "'>" . $row_datos['material_nombre'] . "</option>";
                }
          ?></select></td>
      <td><input type="text" name="dosis" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></td>
      <td><input type="text" name="via" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></td>
      <td><input type="text" name="frec" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></td>
      <td><input type="number" min="1" step="1" class="form-control" name="qty" placeholder="Ingresa la cantidad" required=""></td>
      <td><input type="submit" name="btnagregar" class="btn btn-block btn-success" value="AGREGAR"></td>
    </tr>
  </tbody>
</table>
     </div>
  </form>

  <!-- termino seccion de medicamentos-->

  <!-- inicio ceye servicios ceye-->

<form action="" method="POST" id="medicamentos">
      <div class="row">
        <table class="table table-bordered table-striped" id="mytable">
       <thead class="thead">
         <tr class="table-warning">
            <th colspan="5"><center><h5><strong>EQUIPOS</strong></h5></center></th>
         </tr>
    <tr class="table-active">
      <th scope="col"><center>EQUIPO</center></th>
      <th scope="col"><center>HORAS</center></th>
      <th scope="col"><center></center></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><select data-live-search="true" name="serv" class="form-control" data-live-search="true" id="mibuscador2" style="width : 100%; heigth : 100%">
                                    <?php
                                    $sql_serv = "SELECT * FROM cat_servicios where tipo =4 and serv_activo = 'SI' ORDER BY cat_servicios.serv_desc ASC";
                                    $result_serv = $conexion->query($sql_serv);
                                    while ($row_serv = $result_serv->fetch_assoc()) {
                                        echo "<option value='" . $row_serv['id_serv'] . "'>" . $row_serv['serv_desc'] . "</option>";
                                    }
                                    ?>
                                </select></td>
      <td><input type="number" name="qty_serv" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></td>
      <td><input type="submit" name="btnserv" class="btn btn-block btn-success" value="AGREGAR"></td>
    </tr>
  </tbody>
</table>
     </div>
    </form>  
  
</div>
   <!-- termino de servicios ceye-->         
<?php
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");
          if (isset($_POST['btnagregar'])) {
            include "../../conexionbd.php";
            $usuario = $_SESSION['login'];
            $id_usua= $usuario['id_usua'];
            $paciente1 = $_GET['id_exp'];
               
            $item_id =  mysqli_real_escape_string($conexion, (strip_tags($_POST["med"], ENT_QUOTES)));
            $dosis =  mysqli_real_escape_string($conexion, (strip_tags($_POST["dosis"], ENT_QUOTES)));
            $via =  mysqli_real_escape_string($conexion, (strip_tags($_POST["via"], ENT_QUOTES)));
            $frec =  mysqli_real_escape_string($conexion, (strip_tags($_POST["frec"], ENT_QUOTES)));
            //$hora =  mysqli_real_escape_string($conexion, (strip_tags($_POST["hora"], ENT_QUOTES))); //Escanpando caracteres
            $cart_uniquid = uniqid();
            $qty =  mysqli_real_escape_string($conexion, (strip_tags($_POST["qty"], ENT_QUOTES))); //Escanpando caracteres
            $sql_pac = "SELECT id_atencion FROM dat_ingreso WHERE Id_exp=$paciente1";
         $result_pac = $conexion->query($sql_pac);
         while ($row_pac = $result_pac->fetch_assoc()) {
          $id_atencion = $row_pac['id_atencion'];
            }
    
            $sql_stock = "SELECT * FROM stock_ceye s where $item_id = s.material_id ";
            //echo $sql_stock;
            $result_stock = $conexion->query($sql_stock);
            while ($row_stock = $result_stock->fetch_assoc()) {
              $stock_id = $row_stock['stock_id'];
              $stock_qty = $row_stock['stock_qty'];
            }

            $sql_stock = "SELECT * FROM material_ceye where material_id=$item_id ";
            //echo $sql_stock;
            $result_stock = $conexion->query($sql_stock);
            while ($row_stock = $result_stock->fetch_assoc()) {
              $mat_nom = $row_stock['material_nombre'];
            }
            // echo $stock_qty - $qty;
            if (($stock_qty - $qty) >= 0) {

$sql2 = "INSERT INTO cart_ceye(material_id,cart_qty,cart_stock_id,id_usua,cart_uniqid,paciente,cart_fecha)VALUES($item_id,$qty, $stock_id,$id_usua,'$cart_uniquid',$paciente1,'$fecha_actual');";
            //  echo $sql2;
              $result_cart = $conexion->query($sql2);

              $stock = $stock_qty - $qty;
              $sql3 = "UPDATE stock_ceye set stock_qty=$stock where stock_id = $stock_id";
              $result3 = $conexion->query($sql3);

          $sql_cartid = "SELECT * FROM cart_ceye where paciente=$paciente1 ORDER BY cart_id DESC LIMIT 1 ";
            //echo $sql_cartid;
            $result_cartid = $conexion->query($sql_cartid);
            while ($row_cartid = $result_cartid->fetch_assoc()) {
              $cart_id = $row_cartid['cart_id'];
                                }
              $ingresar2 = mysqli_query($conexion, 'INSERT INTO medicamentos_ceye (cart_id,id_atencion,id_usua,dosis,material_id,mat_nom,via,frecuencia,cantidad) values ('.$cart_id.',' . $id_atencion . ',' . $id_usua .',"' . $dosis . '",' . $item_id . ',"' . $mat_nom . '","' . $via . '","' . $frec . '",' . $qty .') ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

            }


           echo '<script type="text/javascript">window.location.href = "medicamentos_paciente.php?id_exp='.$id_exp.'";</script>';
          }

           if (isset($_POST['btnserv'])) {
            include "../../conexionbd.php";
            $usuario = $_SESSION['login'];
            $id_usua= $usuario['id_usua'];
            $paciente1 = $_GET['id_exp'];
            $serv_id =  mysqli_real_escape_string($conexion, (strip_tags($_POST["serv"], ENT_QUOTES))); //Escanpando caracteres
            $cart_uniquid = uniqid();
            $qty_serv =  mysqli_real_escape_string($conexion, (strip_tags($_POST["qty_serv"], ENT_QUOTES))); //Escanpando caracteres
            $sql_pac = "SELECT id_atencion FROM dat_ingreso WHERE Id_exp=$paciente1";
         $result_pac = $conexion->query($sql_pac);
         while ($row_pac = $result_pac->fetch_assoc()) {
          $id_atencion = $row_pac['id_atencion'];
            }
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");
            $sql_in_serv = "INSERT INTO cart_serv(servicio_id,cart_qty,id_usua,cart_uniqid, paciente,cart_fecha)VALUES($serv_id,$qty_serv,$id_usua,'$cart_uniquid', $paciente1, '$fecha_actual');";
            // echo $sql2;
            $result_cart_serv = $conexion->query($sql_in_serv);

             $sql_stock = "SELECT * FROM cat_servicios where id_serv=$serv_id ";
            //echo $sql_stock;
            $result_stock = $conexion->query($sql_stock);
            while ($row_stock = $result_stock->fetch_assoc()) {
              $serv_desc = $row_stock['serv_desc'];
            }

$sql_cartid = "SELECT * FROM cart_serv where paciente=$paciente1 ORDER BY cart_id DESC LIMIT 1 ";
            //echo $sql_cartid;
            $result_cartid = $conexion->query($sql_cartid);
            while ($row_cartid = $result_cartid->fetch_assoc()) {
              $cart_id = $row_cartid['cart_id'];
            }

      $ingresar2 = mysqli_query($conexion, 'INSERT INTO equipos_ceye (cart_id,id_atencion,id_usua,serv_id,nombre,tiempo) values ('.$cart_id.',' . $id_atencion . ',' . $id_usua .',"' . $serv_id . '","'.$serv_desc.'",' . $qty_serv .') ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));
            echo '<script type="text/javascript">window.location.href = "medicamentos_paciente.php?id_exp='.$id_exp.'";</script>';
          }

          ?>
<div class="container">
           <div class="table-responsive">
            <!--<table id="myTable" class="table table-striped table-hover">-->

            <table class="table table-bordered table-striped" id="mytable">

              <thead class="thead" style="background-color: #0c675e">
                <tr>
                  <th><font color="white"> #</th>
                  <th><font color="white">MATERIAL</font></th>
                  <th><font color="white">CANTIDAD</font></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php
                include "../../conexionbd.php";
            $usuario = $_SESSION['login'];
            $id_usua= $usuario['id_usua'];
            $paciente1 = $_GET['id_exp'];
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
                    . '<td> <a type="submit" class="btn btn-danger btn-sm" href="../../sauxiliares/Ceye/cargar_paquete.php?q=eliminarquir&paciente= ' . $paciente1 . '&cart_id=' . $cart_id . '&cart_stock_id=' . $cart_stock_id . '&cart_qty=' . $cart_qty . '"><span class = "fa fa-trash"></span></a></td>';
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
                    . '<td> 
                    <a type="submit" class="btn btn-danger btn-sm" href="cargar_paquete.php?q=eliminar_serv&paciente= ' . $paciente1 . '&cart_id=' . $cart_id . '&cart_qty=' . $cart_qty . '"><span class = "fa fa-trash"></span></a></td>';
                  echo '</tr>';
                  $no++;
                }
                ?>
              </tbody>
            </table>
         </div> 
  </div>
   
  </section>
  </div>

  <footer class="main-footer">
    <?php
    include("../../template/footer.php");
    ?>
</footer>
<!-- FastClick -->
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
<script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/main.js"></script>
<script>
    document.oncontextmenu = function () {
        return false;
    }
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#mibuscador').select2();
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#mibuscador2').select2();
    });
</script>

</body>

</html>