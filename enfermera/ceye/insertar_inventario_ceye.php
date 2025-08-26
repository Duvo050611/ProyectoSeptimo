<?php
include '../../conexionbd.php';
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");
if (
    isset($_POST['material_id']) and
    isset($_POST['qty']) and
    isset($_POST['stock_max']) and
    isset($_POST['xDate']) and
    isset($_POST['manu'])
) {

    $material_id = $_POST['material_id'];;
    $qty = $_POST['qty'];
    $stock_max = $_POST['stock_max'];
    $xDate = $_POST['xDate'];
    $manu = $_POST['manu'];

    if ($qty <= $stock_max) {

        $ingresar = mysqli_query($conexion, 'insert into stock_ceye (material_id,stock_qty,stock_min,stock_expiry,stock_added,stock_manufactured, stock_purchased) values(' . $material_id . ',' . $qty . ',' . $stock_max . ',"' . $xDate . '","' . $fecha_actual . '","' . $fecha_actual . '","' . $manu . '")') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));
        $sql4 = "insert into entradas_ceye (entrada_material,entrada_qty,entrada_expiry,entrada_purchased,fecha)values($material_id,$qty,'$xDate','$manu',$fecha_actual)";
        $result4 = $conexion->query($sql4);

        header('location: perfilmaterial_ceye.php');
    } else {

        echo '  <div class="alert alert-danger" role="alert">
            Por favor valida la cantidad!
                 </div>';
    }


    // echo 'insert into stock (item_id,stock_qty,stock_expiry,stock_added,stock_manufactured, stock_purchased) values(' . $item_id . ',' . $qty.','. $xDate . ',SYSDATE(),SYSDATE(),' . $manu . ')';
    //return 'fifkyfyf';


    //  }//si no se enviaron datos


} else {

    header('location: lista_productos_ceye.php');
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
