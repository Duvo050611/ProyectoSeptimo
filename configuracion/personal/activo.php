<?php
include '../../conexionbd.php';
if (@$_GET['q'] == 'estatus') {
    $id = $_GET['eid'];
    $est = $_GET['est'];
    if ($est == 'NO') {
        $sql = "UPDATE `reg_usuarios` SET `u_activo`='SI' WHERE `id_usua` = '$id'";
    } else {
        $sql = "UPDATE `reg_usuarios` SET `u_activo`='NO' WHERE `id_usua` = '$id'";
    }
    $result = $conexion->query($sql);
    if ($result) {
        echo '<script type="text/javascript">
					
					window.location.href="alta_usuarios.php";
					</script>';
    }else{
        echo '<script type="text/javascript">
					
					window.location.href="alta_usuarios.php";
					</script>';
    }
}