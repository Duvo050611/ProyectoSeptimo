<?php
include '../../conexionbd.php';

if (@$_GET['q'] == 'del_car') {
  $cart_stock_id = $_GET['cart_stock_id'];
  $cart_qty = $_GET['cart_qty'];
  $paciente = $_GET['paciente'];
  $cart_id = $_GET['cart_id'];


  $sql2 = "SELECT * FROM stock where stock_id = $cart_stock_id";
  $result = $conexion->query($sql2);

  while ($row_stock = $result->fetch_assoc()) {
    $stock = $row_stock['stock_qty'];
  }

  $stock_final = $stock + $cart_qty;


  $sql1 = "DELETE FROM cart_enf WHERE cart_id = $cart_id";
  $result1 = $conexion->query($sql1);


  $sql2 = "UPDATE stock set stock_qty=$stock_final where stock_id = $cart_stock_id";
  $result2 = $conexion->query($sql2);

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
                              window.location.href = "order_copia_enf.php?paciente=' . $paciente . '";
                          }
                      });
                  });
              </script>';
}

if (@$_GET['q'] == 'add_cart') {

  echo $stock_id = $_POST['stock_id'];
  echo $stock_qty = $_POST['stock_qty'];
  echo  $item_id = $_POST['item_id'];

  echo $paciente    = $_POST['paciente1'];  //pendiente
  echo  $qty = (int) $_POST['qty']; //Escanpando
  echo  $cart_uniquid = uniqid();
  echo  $stock = $stock_qty - $qty;
  echo  $usuario1 = $_POST['id_usua']; //penidnte
  /*
        if (!($stock <= 10  )) {//falta el stock min

            $sql2 = "INSERT INTO cart_enf(item_id,cart_qty,cart_stock_id,id_usua,cart_uniqid, paciente)VALUES($item_id,$qty, $stock_id,$usuario1,'$cart_uniquid', $paciente)";


            $result = $conexion->query($sql2);
            $sql2 = "UPDATE stock set stock_qty=$stock where stock_id = $stock_id";
            $result = $conexion->query($sql2);

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
                                    window.location.href = "../../template/menu_sauxiliares.php";
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
                                        window.location.href = "order.php?paciente=' . $paciente . '";
                                    }
                                });
                            });
                        </script>';
      }*/
}




if (@$_GET['q'] == 'comf_cart') {
  $paciente = $_GET['paciente'];
  $id_usua = $_GET['id_usua'];

  $sql2 = "SELECT * FROM cart_enf c, item i, item_type it where c.item_id = i.item_id and it.item_type_id=i.item_type_id and paciente = $paciente and id_usua = $id_usua";
  $result = $conexion->query($sql2);
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");
  while ($row_stock = $result->fetch_assoc()) {
    $item_id = $row_stock['item_id'];
    $cart_qty = $row_stock['cart_qty'];
    $cart_stock_id = $row_stock['cart_stock_id'];
    $cart_uniquid = uniqid();


    $sql2 = "INSERT INTO cart(item_id,cart_qty,cart_stock_id,id_usua,cart_uniqid,paciente,cart_fecha)VALUES($item_id,$cart_qty,$cart_stock_id,$id_usua,'$cart_uniquid',$paciente,'$fecha_actual')";
    $result_insert = $conexion->query($sql2);
  }

  $sql_delCart = "DELETE FROM cart_enf WHERE paciente = $paciente and id_usua = $id_usua";
  $result_delCart = $conexion->query($sql_delCart);

  if ($result_delCart && $result_insert) {

    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
                      $(document).ready(function() {
                          swal({
                              title: "Vale de medicamentos confirmado correctamente", 
                              type: "success",
                              confirmButtonText: "ACEPTAR"
                          }, function(isConfirm) { 
                              if (isConfirm) {
                                window.location.href = "order_copia_enf.php?paciente=' . $paciente . '";
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
                                  title: "Error al confirmar el vale de medicamentos, te sugerimos volver a intentar", 
                                  type: "error",
                                  confirmButtonText: "ACEPTAR"
                              }, function(isConfirm) { 
                                  if (isConfirm) {
                                    window.location.href = "order_copia_enf.php?paciente=' . $paciente . '";
                                  }
                              });
                          });
                      </script>';
  }
}
