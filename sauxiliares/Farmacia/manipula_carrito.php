<?php
include '../../conexionbd.php';
//date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

if (@$_GET['q'] == 'del_car') {
  $cart_stock_id = $_GET['cart_stock_id'];
  $cart_qty = $_GET['cart_qty'];
  $paciente = $_GET['paciente'];
  $cart_id = $_GET['cart_id'];
  $existe = "NO";


  $sql2 = "SELECT * FROM stock where stock_id = $cart_stock_id";
  $result = $conexion->query($sql2);

  while ($row_stock = $result->fetch_assoc()) {
    $stock = $row_stock['stock_qty'];
    $salidas = $row_stock['stock_salidas'];
    $existe = "SI";
  }

  $stock_final = $stock + $cart_qty;
  $stock_sali = $salidas + $cart_qty;


  $sql1 = "DELETE FROM cart WHERE cart_id = $cart_id";
  $result1 = $conexion->query($sql1);


  $sql2 = "UPDATE stock set stock_qty=$stock_final, stock_salidas=$stock_sali where stock_id = $cart_stock_id";
  $result2 = $conexion->query($sql2);

 
  echo '<script type="text/javascript">window.location.href = "order.php?paciente=' . $paciente . '";</script>';
} 

if (@$_GET['q'] == 'comf_cart') {
  $paciente = $_GET['paciente'];
  $id_usua = $_GET['id_usua'];
  $id_cart = $_GET['id_cart'];

  $sql2 = "SELECT * FROM cart c, item i, item_type it where c.cart_id = $id_cart and c.item_id = i.item_id and it.item_type_id=i.item_type_id";
  $result = $conexion->query($sql2);

//date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

  while ($row_stock = $result->fetch_assoc()) {
    $item_id = $row_stock['item_id'];
    $item_code = $row_stock['item_code'];
    $item_name = $row_stock['item_name'];
    $item_brand = $row_stock['item_brand'];
    $item_grams = $row_stock['item_grams'];
    $item_type = $row_stock['item_type_desc'];
    $cart_qty = $row_stock['cart_qty'];
    $cart_price = $row_stock['cart_price'];
    $grupo = $row_stock['grupo'];
    $qty=$cart_qty;
    $surtido=$cart_qty;
    $qty_salida=$cart_qty;
   
    $solicitante = $row_stock['id_usua'];
    $cart_fecha = $row_stock['cart_fecha'];
    $existef = "NO";
    $tipo = $row_stock['tipo'];
}
    $sql4 = "SELECT * FROM reg_usuarios where id_usua = $solicitante ";
    $result4 = $conexion->query($sql4);
    while ($row_usua = $result4->fetch_assoc()) {
    $sol = $row_usua['papell'];
}

//date_default_timezone_set('America/Guatemala');
$fecha_actual=date("Y-m-d H:i:s");

    $ingresar = mysqli_query($conexion, 'INSERT INTO sales(item_id,item_code,generic_name,brand,gram,type,qty,surtido,price,date_sold,paciente,id_usua,solicita,fecha_solicitud) values (' . $item_id . ',"'.$item_code.'","' . $item_name .'","' . $item_brand . '","' . $item_grams . '","'.$item_type.'",' . $cart_qty . ',' . $surtido . ','.$cart_price.',"'.$fecha_actual.'",'.$paciente.',' . $id_usua . ',"'.$sol.'","'.$cart_fecha.'") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

if($grupo=="MEDICAMENTOS"){
    $ingresar2 = mysqli_query($conexion, 'INSERT INTO dat_ctapac (id_atencion,prod_serv,insumo,cta_fec,cta_cant,cta_tot,id_usua,cta_activo,centro_cto,medt,vdesc) values ('.$paciente.',"P","' . $item_id .'","'.$fecha_actual.'",' . $cart_qty . ',' . $cart_price . ',' . $id_usua . ',"SI","' . $tipo . '","Medicamento","MEDICAMENTO") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));
}else if($grupo!="MEDICAMENTOS"){
     $ingresar2 = mysqli_query($conexion, 'INSERT INTO dat_ctapac (id_atencion,prod_serv,insumo,cta_fec,cta_cant,cta_tot,id_usua,cta_activo,centro_cto,vdesc) values ('.$paciente.',"P","' . $item_id .'","'.$fecha_actual.'",' . $cart_qty . ',' . $cart_price . ',' . $id_usua . ',"SI","' . $tipo . '","'.$grupo.'") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));
}

    
    
    $existenciasf=0;
    $salidasf=0;
    $sql2f = $conexion->query("SELECT * FROM stock where stock.item_id=$item_id") or die('<p>Error al encontrar stock FARMACIA</p><br>' . mysqli_error($conexion));
        while ($row_stockf = $sql2f->fetch_assoc()) {
          $existenciasf = $row_stockf['stock_qty'];
          $salidasf = $row_stockf['stock_salidas'];
          $existef = "SI";
        }
    
        $stock_finalf = 0;
        $salidas_finalf = 0;
        if ($existef === "SI") {
            $stock_finalf = $existenciasf - $qty;
            $salidas_finalf = $salidasf + $qty_salida;
            $sql3f = "UPDATE stock set stock_qty=$stock_finalf, stock_salidas=$salidas_finalf, stock_added='$fecha_actual',id_usua=$id_usua where stock.item_id = $item_id";
            $result3f = $conexion->query($sql3f);
        }
        else {
            $ingresar = mysqli_query($conexion, 'insert into stock (item_id,stock_qty,stock_salidas,stock_expiry,stock_added,id_usua) values(' . $item_id . ',' . $qty . ',' . $qty_salida . ',"' . $caduca . '","' . $fecha_actual . '",' . $id_usua . ')') or die('<p>Error al registrar stock FARMACIA</p><br>' . mysqli_error($conexion));
        }
  

  $sql_delCart = "DELETE FROM cart WHERE cart_id = $id_cart";
  $result_delCart = $conexion->query($sql_delCart);

  if ($result_delCart && $ingresar) {

   /* echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
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
                                window.location.href = "surtir_med.php?id_atencion=' . $paciente . '";
                            }
                        });
                    });
                </script>';*/
                echo '<script type="text/javascript">window.location.href = "surtir_med.php?id_atencion=' . $paciente . '";</script>';
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
                                    window.location.href = "surtir_med.php?id_atencion=' . $paciente . '";
                                }
                            });
                        });
                    </script>';
  }
}
