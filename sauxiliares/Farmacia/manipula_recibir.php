<?php 
session_start();
include "../../conn_almacen/Connection.php";
include '../../conexionbd.php';

$usuario = $_SESSION['login'];
$id_usu= $usuario['id_usua'];

include "../header_farmacia.php";
 ?>



<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
   <title></title>
</head>
<body>
<?php
 

if(@$_GET['q']== 'conf'){
  $id_recib=$_GET['cart_id'];
  $lote=$_GET['elote'];
  $qty=$_GET['qty'];
  $entradas=$qty;

echo $lote;

//date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

$sql1 = "SELECT * FROM cart_recib WHERE id_recib=$id_recib";
$result_cart = $conexion_almacen->query($sql1);
while ($row_cart = $result_cart->fetch_assoc()) {
   $cart_qty=$row_cart['cart_qty'];
   $item_id=$row_cart['item_id'];
}

 $stock_qty=0;
 $stock_entradas=0;
 $existe="NO";

//saber cuantos hay en stock
$sql2 = "SELECT * FROM stock WHERE item_id=$item_id";
$result_stock = $conexion->query($sql2);
while ($row_stock = $result_stock->fetch_assoc()) {
   $stock_id=$row_stock['stock_id'];
   $stock_qty=$row_stock['stock_qty'];
   $stock_entradas=$row_stock['stock_entradas'];
   $existe="SI";
}

$sql3 = "SELECT * FROM item i, item_type it WHERE item_id=$item_id and i.item_type_id=it.item_type_id";
$result_item = $conexion->query($sql3);
while ($row_item = $result_item->fetch_assoc()) {
   $item_id=$row_item['item_id'];
   $item_name=$row_item['item_name'];
   $item_code=$row_item['item_code'];
   $item_brand=$row_item['item_brand'];
   $item_grams=$row_item['item_grams'];
   $item_type_desc=$row_item['item_type_desc'];
}

    $entrada= mysqli_query($conexion, 'INSERT INTO entradas_farmacia(item_id,entrada_qty,entrada_added,entrada_lote,id_usua) 
   values ('.$item_id.','.$entradas.',"'.$fecha_actual.'","'.$lote.'",'.$id_usu.') ') or die('<p>Error al registrar entradas de farmacia</p><br>' . mysqli_error($conexion));

     if ($existe === "SI") {
         $total=$stock_qty+$qty;
         $totentra=$stock_entradas+$entradas;
         $sql1="UPDATE stock SET stock_qty=$total,stock_entradas=$totentra where stock_id=$stock_id";
         $result_stock=$conexion->query($sql1);
      }else{
    
         $stock= mysqli_query($conexion, 'INSERT INTO stock(item_id,stock_qty,stock_entradas,stock_added,id_usua) 
         values ('.$item_id.','.$qty.','.$entradas.',"'.$fecha_actual.'",'.$id_usu.') ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));
      }

  $borrar="DELETE FROM cart_recib where id_recib=$id_recib";
  $resultado_borrar=$conexion_almacen->query($borrar);

echo '<script type="text/javascript">window.location.href = "confirmar_envio.php";</script>';

  } else {

    header('location: confirmar_envio.php');
}
?>
</body>
</html>
