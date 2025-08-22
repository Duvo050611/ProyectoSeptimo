<?php include('../../conexionbd.php');
$id = $_POST['id'];
$sql = "SELECT * from cart_almacen WHERE id='$id' LIMIT 1";
$query = mysqli_query($conexion,$sql);
$row = mysqli_fetch_assoc($query);
echo json_encode($row);
?>
