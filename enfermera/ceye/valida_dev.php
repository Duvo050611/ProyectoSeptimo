<?php
include "../../conexionbd.php";
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

$tip = $_POST['tip'];

if ($tip == 'inventario'){
    $id_devi = $_POST['id_dev'];
    $item_idi = $_POST['item_id'];
    $dev_qtyi = $_POST['dev_qty'];
    $pacientei = $_POST['paciente'];
    $id_usuai = $_POST['id_usua'];
    $cant_invi = $_POST['cant_inv'];
    $motivoi = $_POST['motivoi'];   
    $existe = "NO";
    $total = 0;
    $totdevol = 0;

    //saber cuantos hay en stock
    $sql = "SELECT * FROM stock_ceye WHERE item_id=$item_idi";
    $result_stock = $conexion->query($sql);
    while ($row_stock = $result_stock->fetch_assoc()) {
        $stock_id=$row_stock['stock_id'];
        $stock_qty=$row_stock['stock_qty'];
        $stock_devoluciones=$row_stock['stock_devoluciones'];
        $existe="SI";
    }

    if ($existe === "SI") {
        $total=$stock_qty+$cant_invi;
        $totdevol=$stock_devoluciones+$cant_invi;
        $sql1="UPDATE stock_ceye SET stock_qty=$total,stock_devoluciones=$totdevol where stock_id=$stock_id";
        $result_stock=$conexion->query($sql1);
    }else{
        $stock= mysqli_query($conexion, 'INSERT INTO stock_ceye(item_id,stock_qty,stock_devoluciones,stock_added,id_usua) 
        values ('.$item_idi.','.$dev_qtyi.','.$cant_invi.',"'.$fecha_actual.'",'.$id_usuai.') ') or die('<p>Error al registrar stock de quirófano</p><br>' . mysqli_error($conexion));
      }

    $sql2 = "UPDATE devolucion_ceye SET cant_inv = '$cant_invi', motivoi='$motivoi', tipo ='$tip' WHERE id_dev_ceye = $id_devi";
    $result2 = $conexion->query($sql2);

    $canti = 0;
    $cantm = 0;
    $cantd = 0;
    $cantidad = 0;
    $sqldev = "SELECT * FROM devolucion_ceye WHERE id_dev_ceye = $id_devi";
    $result_dev = $conexion->query($sqldev);
    while ($row_dev = $result_dev->fetch_assoc()) {
        $canti = $row_dev['cant_inv'];
        $cantm = $row_dev['cant_mer'];
        $cantd = $row_dev['dev_cantidad'];
    }
    $cantidad = $cantd - $canti - $cantm;
    
    if ($cantidad === 0) {
        $sql22 = "UPDATE devolucion_ceye SET dev_estatus = 'NO' WHERE id_dev_ceye = $id_devi";
        $result22 = $conexion->query($sql22);
    }

    echo '<script type="text/javascript"> window.location.href="devoluciones_ceye.php";</script>';

}
elseif ($tip === 'merma'){
//merma

    $id_devm = $_POST['id_dev'];
    $item_idm = $_POST['item_id'];
    $dev_qtym = $_POST['dev_qty'];
    $cant_merm = $_POST['cant_mer'];
    $pacientem = $_POST['paciente'];
    $id_usuam = $_POST['id_usua'];
    $motivom = $_POST['motivom'];

    $ingresar="insert into merma_ceye (item_id,merma_qty,id_usua,motivom,paciente) values
        ($item_idm,$cant_merm,$id_usuam,'$motivom','$pacientem')";
    $result = $conexion->query($ingresar);
     
    $sql2 = "UPDATE devolucion_ceye SET cant_mer = '$cant_merm', motivom='$motivom', tipo ='$tip' WHERE id_dev_ceye = $id_devm";
    $result2 = $conexion->query($sql2);

    $canti = 0;
    $cantm = 0;
    $cantd = 0;
    $cantidad = 0;
    $sqldev = "SELECT * FROM devolucion_ceye WHERE id_dev_ceye = $id_devm";
    $result_dev = $conexion->query($sqldev);
    while ($row_dev = $result_dev->fetch_assoc()) {
        $canti = $row_dev['cant_inv'];
        $cantm = $row_dev['cant_mer'];
        $cantd = $row_dev['dev_cantidad'];
    }
    $cantidad = $cantd - $canti - $cantm;
    
    if ($cantidad === 0) {
        $sql22 = "UPDATE devolucion_ceye SET dev_estatus = 'NO' WHERE id_dev_ceye = $id_devm";
        $result22 = $conexion->query($sql22);
    }

    echo '<script type="text/javascript"> window.location.href="devoluciones_ceye.php";</script>';


}