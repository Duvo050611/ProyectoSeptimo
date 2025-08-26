<?php
include '../../conexionbd.php';
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");
if (
    isset($_POST['item-id']) and
    isset($_POST['qty']) and
    isset($_POST['stock_min']) and
    isset($_POST['xDate']) and
    isset($_POST['manu'])
) {

    $item_id = $_POST['item-id'];;
    $qty = $_POST['qty'];
    $stock_min = $_POST['stock_min'];
    $xDate = $_POST['xDate'];
    $manu = $_POST['manu'];
    // echo 'insert into stock (item_id,stock_qty,stock_expiry,stock_added,stock_manufactured, stock_purchased) values(' . $item_id . ',' . $qty.','. $xDate . ',SYSDATE(),SYSDATE(),' . $manu . ')';
    //return 'fifkyfyf';
    $ingresar = mysqli_query($conexion, 'insert into stock (item_id,stock_qty,stock_min,stock_expiry,stock_added,stock_manufactured, stock_purchased) values(' . $item_id . ',' . $qty . ',' . $stock_min . ',"' . $xDate . '","' . $fecha_actual . '","' . $fecha_actual . '","' . $manu . '")') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));
    $sql4 = 'insert into entradas (entrada_item,entrada_purchased,entrada_qty,fecha)values('.$item_id.',"' . $manu . '",'.$qty.',"' . $fecha_actual . '")';
    $result4 = $conexion->query($sql4);

    header('location: perfilmedicamento.php');

    //  }//si no se enviaron datos


} else {

    header('location: lista_productos.php');
}


if (@$_GET['q'] == 'estatus') {
    $id = $_GET['eid'];
    $est = $_GET['est'];
    if ($est == 'NO') {
        $sql = "UPDATE `cat_servicios` SET `serv_activo`='SI' WHERE `id_serv` = '$id'";
    } else {
        $sql = "UPDATE `cat_servicios` SET `serv_activo`='NO' WHERE `id_serv` = '$id'";
    }
    $result = $conexion->query($sql);
    if ($result) {
        echo '<script type="text/javascript">
					alert("Estatus guardado exitosamente");
					window.location.href="cat_servicios.php";
					</script>';
    } else {
        echo '<script type="text/javascript">
					alert("Error volver a intentar por favor");
					window.location.href="cat_servicios.php";
					</script>';
    }
}
