<?php
session_start();

//include "../../conexionbd.php";
include "../header_almacenC.php";
$resultado = $conexion->query("select * from reg_usuarios") or die($conexion->error);
$usuario = $_SESSION['login'];

include "../../conn_almacen/Connection.php";

            $usuario = $_SESSION['login'];
            $id_usua= $usuario['id_usua'];
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
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
          integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <!---
    <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css'>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
  -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

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
    <script>
        // Write on keyup event of keyword input element
        $(document).ready(function () {
            $("#search_dep").keyup(function () {
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
    <style>
        hr.new4 {
            border: 1px solid red;
        }
    </style>
</head>

<body>
    <?php
        if ($usuario1['id_rol'] == 11) {

            ?>
            <div class="container">
    <div class="row">
        <div class="col-sm-4">
            <a type="submit" class="btn btn-danger" href="../../template/menu_almacencentral.php">Regresar</a>
</div></div></div>
            <?php
        } else if ($usuario1['id_rol'] == 4 || $usuario1['id_rol'] == 5) {

            ?>
            <div class="container">
    <div class="row">
        <div class="col-sm-4">
            <a type="submit" class="btn btn-danger" href="../../template/menu_almacencentral.php">Regresar</a>
</div></div></div>
            <?php
        }else

        ?>
<section class="content container-fluid">
<hr>
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
         <tr><strong><center>SURTIR PEDIDOS</center></strong>
      </div><br>
 <!--CONSULTA DE MEDICAMENTOS PEDIDOS-->
    <div class="container">
           <div class="table-responsive">
            <!--<table id="myTable" class="table table-striped table-hover">-->

            <table class="table table-bordered table-striped" id="mytable">

              <thead class="thead"  style="background-color: #2b2d7f">
                <tr>
                  <th><font color="white">#</font></th>
                  <th><font color="white">Descripción</th>
                  <th><font color="white">Fecha de solicitud</th>
                  <th><font color="white">Sub almacen</th>
                  <th><font color="white">Solicitan</th>
                  <th><font color="white">Lote</th>
                  <th><font color="white">Existencias</th>
                    <!--<th><font color="white">Expira</th>-->
                  <th><font color="white">Cantidad a surtir</th>
<th></th>
                </tr>

              </thead>
              <tbody>
                <a href=""></a>
                <?php
                 include "../../conn_almacen/Connection.php";
                 
                $item_id=0;
                $item_id2=0;
                $lote = "";
                $no = 0;
                $resultado2 = $conexion_almacen->query("SELECT * from cart_recib c
                 where c.confirma='SI' and c.enviado='NO' ORDER BY id_recib DESC") or die($conexion_almacen->error);
                $no = $no ++;
                while ($row_lista = $resultado2->fetch_assoc()) {
                    $no = $no +1;
                    $item_id=$row_lista['item_id'];
                    $carrito=$row_lista['id_recib'];
                    $almacen=$row_lista['almacen'];
                    $cantidad=$row_lista['cart_qty'];
                    $existencias = 0;
                    $existe_stock="NO";
                    $date=date_create($row_lista['fecha']);
                    date_format($date,"d/m/Y");
                   
                   //saber cuantos hay en stock_almacen



                    $sql_item = $conexion_almacen->query( "SELECT * FROM item_almacen WHERE 
                    item_id = $item_id ") or die($conexion_almacen->error);
                      while ($row_item = $sql_item->fetch_assoc()) {
                         echo '<tr>'
                            . '<td>' . $no . '</td>'
                            . '<td>' . $row_item['item_name'] . '</td>'
                            . '<td>' . date_format($date,"d/m/Y") . '</td>'
                            . '<td >' . $almacen . '</td>'
                            . '<td>' . $cantidad . '</td>' ;
                         } 

                  $sql_alm = $conexion_almacen->query( "SELECT * FROM stock_almacen s WHERE 
                    s.item_id = $item_id and stock_qty!= 0 ") or die($conexion_almacen->error);
               
                   while ($row_stock_alm = $sql_alm->fetch_assoc()) {
                         $item_id2 = $row_stock_alm['item_id'];
                         $existencias = $row_stock_alm['stock_qty'];
                         $lote = $row_stock_alm['stock_lote'];
                         $caduca = date_create($row_stock_alm['stock_expiry']);
                         $caduca = date_format($caduca,"d/m/Y");
                         $existe_stock="SI";
                         
                         echo '<tr>'
                          . '<td>' . " ". '</td>' 
                          . '<td>' . "Caducidad:".'</td>'
                          . '<td>' . $caduca. '</td>'
                          . '<td>' . " ". '</td>'  
                          . '<td>' . " ". '</td>' 
                          . '<td>' . $lote. '</td>'  
                          . '<td>' . $existencias. '</td>'
// . '<td>' . $row_stock_alm['stock_expiry']. '</td>'
.'<td><form action="?ids='.$lote.
'&exp='.$row_stock_alm['stock_expiry'].
'&id='.$item_id2.
'&cars='.$carrito.
'&alma='.$almacen.'
" method="POST">
<input type="number" name="surt" class="form-control"><td>
<input type="submit" name="btnserv" class="btn btn-block btn-success" value="Agregar"></form></td>';
 
}


$sql_alm = $conexion_almacen->query( "SELECT * FROM car_ped p where item_id='$item_id2' and lote='$lote' and p.id_recib ='$carrito' and p.almacen = '$almacen'") or die($conexion_almacen->error);
               
                   while ($row_stock_alm = $sql_alm->fetch_assoc()) {
                    $lotee=$row_stock_alm['lote'];
                    $alma  =$row_stock_alm['almacen'];
                    $carri =$row_stock_alm['id_recib'];
                   }

If (isset($lotee) and $lote===$lotee and $almacen==$alma and $carrito==$carri){
        echo 
          '<td style="background-color: #2b2d7f; color:white;">' . " Agregado ". '</td>';
    } 

    If ($existe_stock == "NO"){
        echo '<tr>'
          . '<td>' . " ". '</td>'  
          . '<td>' . " ". '</td>' 
          . '<td>' . " ". '</td>' 
          . '<td>' . " ". '</td>' 
          . '<td>' . " ". '</td>' 
          . '<td>' . " ". '</td>' 
          . '<td>' . " ". '</td>' 
          . '<td>' . "Sin existencias ". '</td>' 
          .'<td><form action="?idc='.$item_id.
          '&qtyc='.$cantidad.
          '&carsc='.$carrito.
          '&almc='.$almacen.'
          " method="POST">
<input type="submit" name="comprar" class="btn btn-danger" value="Comprar"></form></td>';
    } 
}

// Rutina para registrar las compras
if (isset($_POST['comprar'])) {
           include "../../conn_almacen/Connection.php";
    $usuario = $_SESSION['login'];
    $id_usua= $usuario['id_usua'];           
    $id_i=@$_GET['idc'];
    $cant=@$_GET['qtyc'];
    $carsc=@$_GET['carsc'];
    $alma=@$_GET['almc'];
           
    date_default_timezone_set('America/Guatemala');
    $fecha_actual = date("Y-m-d H:i:s");

    $comprar = mysqli_query($conexion_almacen, 'INSERT INTO compras (item_id,compra_qty,fecha_sol,almacen,id_usua) values ("'.$id_i.'","'.$cant.'","'.$fecha_actual.'","'.$alma.'","'.$id_usua.'")') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion_almacen));
    

    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
                      $(document).ready(function() {
                          swal({
                              title: "Compra registrada correctamente", 
                              type: "success",
                              confirmButtonText: "ACEPTAR"
                          }, function(isConfirm) { 
                              window.location.href = "surtir_almacen.php";
                          });
                      });
        </script>';
   
$sql_delCar = mysqli_query($conexion_almacen,"DELETE FROM cart_recib WHERE id_recib ='$carsc'") or die('<p>Error al borrar</p><br>' . mysqli_error($conexion_almacen));

}

// Termina registro de compras

if (isset($_POST['btnserv'])) {


           include "../../conn_almacen/Connection.php";
            $usuario = $_SESSION['login'];
            $id_usua= $usuario['id_usua'];
            //$id_atencion = $_SESSION['pac'];
$slo=@$_GET['ids'];
$expir=@$_GET['exp'];
$cars=@$_GET['cars'];
$iden=@$_GET['id'];
$alma=@$_GET['alma'];

$surt =  mysqli_real_escape_string($conexion, (strip_tags($_POST["surt"], ENT_QUOTES)));
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

$ingresar2 = mysqli_query($conexion_almacen, 'INSERT INTO car_ped (id_recib,item_id,envio_qty,lote,car_expiry,fecha,almacen) values ('.$cars.',"'.$iden.'","'.$surt.'","'.$slo.'","'.$expir.'","'.$fecha_actual.'","'.$alma.'")') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion_almacen));

            echo '<script type="text/javascript">window.location.href = "surtir_almacen.php";</script>';
                              
                  echo '</tr>';
                  $no++;
               
                }
                ?>
              </tbody>
            </table>
         </div> 
  </div>
  <!--TERMINO CONSULTA DE MEDICAMENTOS PEDIDOS-->

 <div class="container">
           <div class="table-responsive">
            <!--<table id="myTable" class="table table-striped table-hover">-->

            <table class="table table-bordered table-striped" id="mytable">

              <thead class="thead" style="background-color: #2b2d7f; color:white;">
                <tr>
                  <th>#</th>
                  <th>Descripción</th>
                  <th>Sub almacén</th>
                  <th>Lote</th>
                  <th>Cantidad a surtir</th>
                  
                </tr>
              </thead>
              <tbody>
                <?php
                include "../../conn_almacen/Connection.php";
                $usuario = $_SESSION['login'];

                $resultado3 = $conexion_almacen->query("SELECT * FROM car_ped order by id_surp") or die($conexion_almacen->error);
                while ($row_lista_serv = $resultado3->fetch_assoc()) {
                  $item_id2 = $row_lista_serv['item_id'];

                  $sql_solicitados = $conexion_almacen->query( "SELECT * FROM item_almacen s WHERE 
                    item_id = $item_id2") or die($conexion_almacen->error);
                            while ($row_stock_sol = $sql_solicitados->fetch_assoc()) {
                    $nomi=$row_stock_sol['item_name'];
                   }

                  $item_id = $row_lista_serv['item_id'];
                  $envio_qty = $row_lista_serv['envio_qty'];
                  $alm = $row_lista_serv['almacen'];
                  $lote = $row_lista_serv['lote'];
                  $id_surp = $row_lista_serv['id_surp'];
                  $noo=1;

                  echo '<tr>'
                    . '<td>' . $noo . '</td>'
                    . '<td>' . $nomi . '</td>'
                    . '<td>' . $alm . '</td>'
                    . '<td>' . $lote . '</td>'
                    . '<td>' . $envio_qty . '</td>';
                  echo '</tr>';
                  $noo++;
              }

              ?>

              </tbody>
            </table>
          <center>
        <div class="col-md-3">
        <?php
            echo '<form action="?id_l='.$row_lista_serv['lote'].'" method="POST">
            <button type="submit" class="btn btn-success btn-block" name="btnc"><span>
            Confirmar</span></button></form>';

            if (isset($_POST['btnc'])) {
                
                $sql2 = "SELECT * FROM car_ped";
                $result = $conexion_almacen->query($sql2);
                while ($row_stock = $result->fetch_assoc()) {
                    $item_id = $row_stock['item_id'];
                    $envio_qty = $row_stock['envio_qty'];
                    $almi = $row_stock['almacen'];
                    $lote = $row_stock['lote'];
                    date_default_timezone_set('America/Guatemala');
                    $fecha_actual = date("Y-m-d H:i:s");

                    $sql_insert_cuenta = "INSERT INTO cart_recib(item_id,cart_qty,envio_qty,destino,almacen,confirma,enviado,fecha,stock_lote)VALUES('$item_id','$envio_qty','$envio_qty','TOLUCA','$almi','SI','SI','$fecha_actual','$lote')";
                    $result_insert_cuenta = $conexion_almacen->query($sql_insert_cuenta);
                }

                $sql2 = "SELECT * FROM car_ped";
                $result_cart = $conexion_almacen->query($sql2);
                while ($row_stock = $result_cart->fetch_assoc()) {
                    $id_recib = $row_stock['id_recib']; 
                    $item_id=$row_stock['item_id'];
                    $envio_qty=$row_stock['envio_qty'];
                    $lote=$row_stock['lote'];
                    $expirr=$row_stock['car_expiry'];
                    $almacen=$row_stock['almacen'];
                    $existe = "NO";

                    $sales= mysqli_query($conexion_almacen, 'INSERT INTO sales_almacen(item_id,qty,destino,almacen,stock_lote,sales_expiry,id_usua) 
                        values ('.$item_id.','.$envio_qty.',"TOLUCA","'.$almacen.'","'.$lote.'","'.$expirr.'",'.$id_usua.') ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion_almacen));

                    $sql4 = $conexion_almacen->query("SELECT * FROM stock_almacen s where s.item_id=$item_id and s.stock_lote like '%$lote%' ") or die('<p>Error al encontrar stock</p><br>' . mysqli_error($conexion));
     
                    while ($row_stock = $sql4->fetch_assoc()) {
                            $existencias = $row_stock['stock_qty'];
                            $salidas = $row_stock['stock_salidas'];
                            $existe = "SI";
                    }
                    
                    if ($existe === "SI") {
                            $stock_final = $existencias - $envio_qty;
                            $salidas_final = $salidas + $envio_qty;
                            $sql5 = "UPDATE stock_almacen set stock_qty=$stock_final, stock_salidas=$salidas_final, stock_added='$fecha_actual',id_usua=$id_usua where stock_almacen.item_id = $item_id and stock_almacen.stock_lote like '%$lote%'";
                            $result5 = $conexion_almacen->query($sql5);
                    }
                    else {
                            $ingresar = mysqli_query($conexion_almacen, 'insert into stock_almacen (item_id,stock_qty,stock_entradas,stock_expiry,stock_added,stock_lote,id_usua) values(' . $item_id . ',' . $qty . ',' . $qty_entrada . ',"' . $xDate . '","' . $fecha_actual . '","' . $manu . '",' . $id_usu . ')') or die('<p>Error al registrar stock</p><br>' . mysqli_error($conexion));
                    }
                  
                

                   $sql_delCar_rec = "DELETE FROM cart_recib WHERE id_recib = $id_recib";
                   $result_delCartre = $conexion_almacen->query($sql_delCar_rec);
                }

                $sql_delCart = "DELETE FROM car_ped";
                $result_delCart = $conexion_almacen->query($sql_delCart);
   

                echo '<script type="text/javascript">window.location.href = "surtir_almacen.php";</script>';
            }
             
                ?>
            </div>
               </center>
         </div> 
  </div>

</div>
</section>


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