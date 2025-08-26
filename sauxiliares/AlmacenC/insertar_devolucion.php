<?php
session_start();
include '../../conexionbd.php';
include '../../conn_almacen/Connection.php';
$usuario = $_SESSION['login'];
$id_usu= $usuario['id_usua'];

date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

if (
    isset($_POST['item_id']) and 
    isset($_POST['qty']) and 
    isset($_POST['devol']) and 
    isset($_POST['alma']) and 
    isset($_POST['lote']) and 
    isset($_POST['caduca'])
) {

    $item_id = $_POST['item_id'];
    $qty = $_POST['qty'];
    $qty_devolucion=$qty;
    $qty_salida=$qty;
    $existenciasf = 0;
    $salidasf = 0;
    $motivo = $_POST['devol'];
    $alma = $_POST['alma'];
    $lote = $_POST['lote'];
    $caduca = $_POST['caduca'];
    $existe = "NO";
    $existef = "NO";
    $existec = "NO";
    $stock_finalf = 0;
    $salidas_finalf = 0;   
    $stock_finalc = 0;
    $salidas_finalc = 0;   

    date_default_timezone_set('America/Guatemala');
    $fecha_actual = date("Y-m-d H:i:s");
  
    $sql1 = mysqli_query($conexion_almacen, 'insert into devolucion_almacen 
        (item_id,stock_qty,almacen,stock_added,motivo,stock_lote,stock_expiry,id_usua) values
        ('.$item_id.','. $qty.',"'.$alma.'","'.$fecha_actual.'","'.$motivo.'","'.$lote.'","'.$caduca.'","'.$id_usu.'")') or die('<p>Error al registrar devolución</p><br>' . mysqli_error($conexion));

    $sql2 = $conexion_almacen->query("SELECT * FROM stock_almacen where stock_almacen.item_id=$item_id and stock_almacen.stock_lote like '%$lote%' ") or die('<p>Error al encontrar stock</p><br>' . mysqli_error($conexion));
     
    while ($row_stock = $sql2->fetch_assoc()) {
          $existencias = $row_stock['stock_qty'];
          $devoluciones = $row_stock['stock_devoluciones'];
          $existe = "SI";
    }
    
    if ($existe === "SI") {
        $stock_final = $existencias + $qty;
        $devol_final = $devoluciones + $qty_devolucion;
        $sql3 = "UPDATE stock_almacen set stock_qty=$stock_final, stock_devoluciones=$devol_final, stock_added='$fecha_actual',id_usua=$id_usu where stock_almacen.item_id = $item_id and stock_almacen.stock_lote like '%$lote%'";
        $result3 = $conexion_almacen->query($sql3);
    }
    else {
        $ingresar = mysqli_query($conexion_almacen, 'insert into stock_almacen (item_id,stock_qty,stock_devoluciones,stock_expiry,stock_added,stock_lote,id_usua) values(' . $item_id . ',' . $qty . ',' . $qty_devolucion . ',"' . $caduca . '","' . $fecha_actual . '","' . $lote . '",' . $id_usu . ')') or die('<p>Error al registrar stock</p><br>' . mysqli_error($conexion));
    }

    if ($alma == 'FARMACIA') {
        $sql2f = $conexion->query("SELECT * FROM stock where stock.item_id=$item_id") or die('<p>Error al encontrar stock FARMACIA</p><br>' . mysqli_error($conexion));
        while ($row_stockf = $sql2f->fetch_assoc()) {
          $existenciasf = $row_stockf['stock_qty'];
          $salidasf = $row_stockf['stock_salidas'];
          $existef = "SI";
        }
    
        $stock_finalf = $existenciasf - $qty;
        $salidas_finalf = $salidasf + $qty_salida;   

        if ($stock_finalf < 0) {
                
                echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
                echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
                echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
                echo '<script>
                      $(document).ready(function() {
                          swal({
                              title: "Verificar existencias en FARMACIA", 
                              type: "success",
                              confirmButtonText: "ACEPTAR"
                          }, function(isConfirm) { 
                              if (isConfirm) {
                                window.location.href = "devoluciones.php";
                              }
                          });
                      });
                </script>';   
             
        }


        if ($existef === "SI") {
            $sql3f = "UPDATE stock set stock_qty=$stock_finalf, 
                                       stock_salidas=$salidas_finalf, 
                                       stock_added='$fecha_actual',
            id_usua=$id_usu where stock.item_id = $item_id";
            $result3f = $conexion->query($sql3f);
        }
        else {
            $ingresar = mysqli_query($conexion, 'insert into stock (item_id,stock_qty,stock_salidas,stock_added,id_usua) values(' . $item_id . ',' . $stock_finalf . ',' . $qty_salida . ',"' . $fecha_actual . '",' . $id_usu . ')') or die('<p>Error al registrar stock FARMACIA</p><br>' . mysqli_error($conexion));
        }

        $sqlitem = $conexion->query("SELECT * FROM item, item_type  where item.item_id = $item_id and item.item_type_id =item_type.item_type_id") or die('<p>Error al encontrar item </p><br>' . mysqli_error($conexion));;
        while ($row_item = $sqlitem->fetch_assoc()) {
            $item_code = $row_item['item_code'];
            $generic_name = $row_item['item_name'];
            $brand = $row_item['item_brand'];
            $gram = $row_item['item_grams'];
            $type = $row_item['item_type_desc'];
            $price = $row_item['item_price'];
        }

        $insertar=mysqli_query($conexion,'INSERT INTO sales(item_id,item_code,generic_name,brand,gram,type,qty,price,id_usua,date_sold) values ('.$item_id.',"'.$item_code.'","'.$generic_name.'","'.$brand.'","'.$gram.'","'.$type.'","'.$qty.'","'.$price.'",'.$id_usu.',"' . $fecha_actual . '")')  or die('<p>Error al registrar salidas FARMACIA</p><br>' . mysqli_error($conexion));

    }    

    if ($alma === 'CEYE') {
        $sql2c = $conexion->query("SELECT * FROM stock_ceye where stock_ceye.item_id=$item_id") or die('<p>Error al encontrar stock CEYE</p><br>' . mysqli_error($conexion));
        while ($row_stockc = $sql2c->fetch_assoc()) {
          $existenciasc = $row_stockc['stock_qty'];
          $salidasc = $row_stockc['stock_salidas'];
          $existec = "SI";
        }

        $stock_finalc = $existenciasc - $qty;
        $salidas_finalc = $salidasc + $qty_salida;

        if ($existec === "SI") {
            $sql3c = "UPDATE stock_ceye set stock_qty=$stock_finalc, stock_salidas=$salidas_finalc, stock_added='$fecha_actual',id_usua=$id_usu where stock_ceye.item_id = $item_id";
            $result3c = $conexion->query($sql3c);
        }
        else {
            $ingresar = mysqli_query($conexion, 'insert into stock_ceye (item_id,stock_qty,stock_salidas,stock_added,id_usua) values(' . $item_id . ',' . $qty . ',' . $qty_salida . ',"' . $fecha_actual . '",' . $id_usu . ')') or die('<p>Error al registrar stock CEYE</p><br>' . mysqli_error($conexion));
        }
        $sqlmaterial = $conexion->query("SELECT * FROM material_ceye, item_type  where item.item_id = $item_id and material_ceye.material_tipo =item_type.item_type_id") or die('<p>Error al encontrar material de ceye </p><br>' . mysqli_error($conexion));;
        while ($row_material = $sqlmaterial->fetch_assoc()) {
            $item_code = $row_item['material_codigo'];
            $generic_name = $row_item['material_nombre'];
            $brand = $row_item['material_fabricante'];
            $gram = $row_item['material_contenido'];
            $type = $row_item['item_type_desc'];
            $price = $row_item['material_precio'];
        }

        $insertar=mysqli_query($conexion,'INSERT INTO sales_ceye(item_id,item_code,generic_name,brand,gram,type,qty,price,id_usua,date_sold) values ('.$item_id.',"'.$item_code.'","'.$generic_name.'","'.$brand.'","'.$gram.'","'.$type.'","'.$qty.'","'.$price.'",'.$id_usu.',"' . $fecha_actual . '")')  or die('<p>Error al registrar salidas de ceye</p><br>' . mysqli_error($conexion));
    }

    header('location: devoluciones.php');

} else {

    header('location: devoluciones.php');
}

