<?php
include '../../conexionbd.php';

include '../../conn_almacen/Connection.php';
session_start();


if (@$_GET['q'] == 'del_car') {
  $cart_stock_id = $_GET['cart_stock_id'];
  $cart_qty = $_GET['cart_qty'];
  $cart_id = $_GET['cart_id'];


  $sql2 = "SELECT * FROM stock_almacen where stock_id = $cart_stock_id";
  $result = $conexion_almacen->query($sql2);

  while ($row_stock = $result->fetch_assoc()) {
    $stock = $row_stock['stock_qty'];
  }

  $stock_final = $stock + $cart_qty;


  $sql1 = "DELETE FROM cart_almacen WHERE cart_id = $cart_id";
  $result1 = $conexion_almacen->query($sql1);


  $sql2 = "UPDATE stock_almacen set stock_qty=$stock_final where stock_id = $cart_stock_id";
  $result2 = $conexion_almacen->query($sql2);

  echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
  echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
  echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
  echo '<script>
                  $(document).ready(function() {
                      swal({
                          title: "Medicamento eliminado correctamente", 
                          type: "success",
                          confirmButtonText: "ACEPTAR"
                      }, function(isConfirm) { 
                          if (isConfirm) {
                              window.location.href = "order.php";
                          }
                      });
                  });
              </script>';
}

if (@$_GET['q'] == 'comf_cart') {
  $id_usua = $_GET['id_usua'];

  $sql2 = "SELECT * FROM cart_almacen c, item_almacen i, item_type it where c.item_id = i.item_id and it.item_type_id=i.item_type_id ";
  $result = $conexion_almacen->query($sql2);

  while ($row_stock = $result->fetch_assoc()) {
    $item_id = $row_stock['item_id'];
    $item_code = $row_stock['item_code'];
    $item_name = $row_stock['item_name'];
    $item_brand = $row_stock['item_brand'];
    $item_grams = $row_stock['item_grams'];
    $item_type = $row_stock['item_type_desc'];
    $cart_qty = $row_stock['cart_qty'];
    $destino = $row_stock['destino'];
    $almacen = $row_stock['almacen'];
 date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");
    $sql_insert = 'INSERT INTO sales_almacen(item_code,generic_name,brand,gram,type,qty,destino,date_sold,almacen)
    VALUES("'.$item_code.'","'.$item_name.'", "'.$item_brand.'","'.$item_grams.'","'.$item_type.'",'.$cart_qty.',"'.$destino.'", "'.$fecha_actual.'","'.$almacen.'")';

    $result_insert = $conexion_almacen->query($sql_insert);
  }

  $sql_delCart = "DELETE FROM cart_almacen";
  $result_delCart = $conexion_almacen->query($sql_delCart);

   unset($_SESSION['almacen']);
   unset($_SESSION['destino']);
  if ($result_delCart && $result_insert) {

    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
                    $(document).ready(function() {
                        swal({
                            title: "Medicamento surtido correctamente", 
                            type: "success",
                            confirmButtonText: "ACEPTAR"
                        }, function(isConfirm) { 
                            if (isConfirm) {
                                window.location.href = "../../template/menu_almacencentral.php";
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
                                title: "Error al confirmar el surtido de medicamento te sugerimos volver a intentar", 
                                type: "error",
                                confirmButtonText: "ACEPTAR"
                            }, function(isConfirm) { 
                                if (isConfirm) {
                                window.location.href = "order.php";
                                }
                            });
                        });
                    </script>';
  }
}
