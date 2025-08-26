<?php
session_start();
include '../../conexionbd.php';
include '../../conn_almacen/Connection.php';
$usuario = $_SESSION['login'];
$id_usu= $usuario['id_usua'];

if (
    
    isset($_POST['item-id']) and
    isset($_POST['manu']) and
    isset($_POST['xDate']) and
    isset($_POST['factura']) and
    isset($_POST['fec_compra']) and
    isset($_POST['precio']) and
    isset($_POST['qty'])
) {
    
    $item_id = $_POST['item-id'];;
    $manu = $_POST['manu'];
    $xDate = $_POST['xDate'];
    $fec_compra = $_POST['fec_compra'];
    $factura = $_POST['factura'];
    $compra = $_POST['fec_compra'];
    $precio = $_POST['precio'];
    $qty = $_POST['qty'];
    $qty_entrada = $_POST['qty'];
    $qty_salida = $_POST['qty'];
    $existe = "NO";
    $destino = "TOLUCA";
    $almacen = "FARMACIA";
    

    date_default_timezone_set('America/Guatemala');
    $fecha_actual = date("Y-m-d H:i:s");
    
    $sqli = $conexion_almacen->query("SELECT * FROM item_almacen where item_almacen.item_id=$item_id") or die('<p>Error al encontrar catálogo</p><br>' . mysqli_error($conexion));
    while ($row_cati = $sqli->fetch_assoc()) {
        $grupo = $row_cati['grupo'];  
    }
    
    if ($grupo == 'AFECTA AUTOMATICO MEDICAMENTOS'){
        $sql4 = mysqli_query($conexion_almacen,'insert into entradas (item_id,entrada_qty,entrada_expiry,entrada_added,entrada_lote,entrada_purchased, entrada_factura,entrada_price,id_usua) values(' . $item_id . ',' . $qty . ',"' . $xDate . '","' . $fecha_actual . '","' . $manu . '" ,"' . $compra . '","' . $factura . '",' . $precio . ',' . $id_usu . ')') or die('<p>Error al registrar entradas</p><br>' . mysqli_error($conexion));
        $sql1 = mysqli_query($conexion_almacen,'insert into sales_almacen (item_id,qty,destino,date_sold,almacen,stock_lote,sales_expiry,id_usua) values
        (' . $item_id . ',' . $qty . ',"' . $destino . '","' . $fecha_actual . '","' . $almacen . '","' . $manu . '" ,"' . $xDate . '",' . $id_usu . ')') or die('<p>Error al registrar salidas</p><br>' . mysqli_error($conexion));
        $sql_insert_cart = "INSERT INTO cart_recib
        (item_id,cart_qty,envio_qty,destino,almacen,confirma,enviado,fecha,stock_lote)VALUES
        ('$item_id','$qty_entrada','$qty_salida','$destino','$almacen','SI','SI','$fecha_actual','$manu')";
        $result_insert_cart = $conexion_almacen->query($sql_insert_cart);

        $sql2 = $conexion_almacen->query("SELECT * FROM stock_almacen where stock_almacen.item_id=$item_id and stock_almacen.stock_lote = '$manu' ") or die('<p>Error al encontrar stock</p><br>' . mysqli_error($conexion));
     
        while ($row_stock = $sql2->fetch_assoc()) {
          $existencias = $row_stock['stock_qty'];
          $entradas = $row_stock['stock_entradas'];
          $salidas = $row_stock['stock_salidas'];
          $existe = "SI";
        }
        if ($existe === "SI") {
            $stock_final = $existencias;
            $entradas_final = $entradas + $qty_entrada;
            $salidas_final = $salidas + $qty_salida;
            $sql3 = "UPDATE stock_almacen set stock_qty=$stock_final, stock_entradas=$entradas_final,  stock_salidas=$salidas_final, stock_added='$fecha_actual',id_usua=$id_usu where stock_almacen.item_id = $item_id and stock_almacen.stock_lote = '$manu'";
            $result3 = $conexion_almacen->query($sql3);
        }
        else {
            $ingresar = mysqli_query($conexion_almacen, 'insert into stock_almacen 
            (item_id,stock_qty,stock_entradas,stock_salidas,stock_expiry,stock_added,stock_lote,id_usua) values
            ('.$item_id.','.$qty.','.$qty_entrada.','.$qty_salida.',"'.$xDate.'","'.$fecha_actual.'","'.$manu.'",'.$id_usu.')') or die('<p>Error al registrar stock</p><br>' . mysqli_error($conexion));
        }
    
        $sql5 = $conexion_almacen->query("SELECT * FROM item_almacen where item_almacen.item_id=$item_id") or die('<p>Error al encontrar catálogo</p><br>' . mysqli_error($conexion));
        while ($row_cat = $sql5->fetch_assoc()) {
            $valor_inv = $row_cat['item_cost'];  
        }
/*    $valor = $valor_inv + $precio;
    $costo_promedio =  $valor / $stock_final;*/
    
        $costo_promedio = number_format($precio,2);
        $sql6 = "UPDATE item_almacen SET item_cost=$costo_promedio WHERE item_almacen.item_id = $item_id";
        $result6 = $conexion_almacen->query($sql6);
        $sql7 = "UPDATE item SET item_cost=$costo_promedio WHERE item.item_id = $item_id";
        $result7 = $conexion->query($sql7);
        $sql8 = "UPDATE material_ceye SET item_cost=$costo_promedio WHERE material_ceye.material_id = $item_id";
        $result = $conexion->query($sql8);
       
        header('location: entrada_almacen.php');

    }
    
    else {
         $sql4 = mysqli_query($conexion_almacen,'insert into entradas (item_id,entrada_qty,entrada_expiry,entrada_added,entrada_lote,entrada_purchased, entrada_factura,entrada_price,id_usua) values(' . $item_id . ',' . $qty . ',"' . $xDate . '","' . $fecha_actual . '","' . $manu . '" ,"' . $compra . '","' . $factura . '",' . $precio . ',' . $id_usu . ')') or die('<p>Error al registrar entradas</p><br>' . mysqli_error($conexion));
 
        $sql2 = $conexion_almacen->query("SELECT * FROM stock_almacen where stock_almacen.item_id=$item_id and stock_almacen.stock_lote like '%$manu%' ") or die('<p>Error al encontrar stock</p><br>' . mysqli_error($conexion));
     
        while ($row_stock = $sql2->fetch_assoc()) {
          $existencias = $row_stock['stock_qty'];
          $entradas = $row_stock['stock_entradas'];
          $existe = "SI";
        }
        if ($existe === "SI") {
            $stock_final = $existencias + $qty;
            $entradas_final = $entradas + $qty_entrada;
            $sql3 = "UPDATE stock_almacen set stock_qty=$stock_final, stock_entradas=$entradas_final, stock_added='$fecha_actual',id_usua=$id_usu where stock_almacen.item_id = $item_id and stock_almacen.stock_lote like '%$manu%'";
            $result3 = $conexion_almacen->query($sql3);
        }
        else {
            $ingresar = mysqli_query($conexion_almacen, 'insert into stock_almacen (item_id,stock_qty,stock_entradas,stock_expiry,stock_added,stock_lote,id_usua) values(' . $item_id . ',' . $qty . ',' . $qty_entrada . ',"' . $xDate . '","' . $fecha_actual . '","' . $manu . '",' . $id_usu . ')') or die('<p>Error al registrar stock</p><br>' . mysqli_error($conexion));
        }
        
        $sql5 = $conexion_almacen->query("SELECT * FROM item_almacen where item_almacen.item_id=$item_id") or die('<p>Error al encontrar catálogo</p><br>' . mysqli_error($conexion));
        while ($row_cat = $sql5->fetch_assoc()) {
            $valor_inv = $row_cat['item_cost'];  
        }

    
        $costo_promedio = number_format($precio,2);
    
        $sql6 = "UPDATE item_almacen SET item_cost=$costo_promedio WHERE item_almacen.item_id = $item_id";
        $result6 = $conexion_almacen->query($sql6);
    
        $sql7 = "UPDATE item SET item_cost=$costo_promedio WHERE item.item_id = $item_id";
        $result7 = $conexion->query($sql7);
        
        $sql8 = "UPDATE material_ceye SET item_cost=$costo_promedio WHERE material_ceye.material_id = $item_id";
        $result = $conexion->query($sql8);
    
    }
    
   header('location: entrada_almacen.php');

    //  }//si no se enviaron datos


} else {

    header('location: entrada_almacen.php');
}


