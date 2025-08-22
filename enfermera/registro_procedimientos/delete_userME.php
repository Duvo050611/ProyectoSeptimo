<?php 
include('../../conexionbd.php');

$user_id = $_POST['id'];
$sql = "DELETE FROM cart_almacen WHERE id='$user_id'";


//$sql2E = "DELETE FROM equipos_ceye WHERE cart_id ='$user_id'";
$delQuery =mysqli_query($conexion,$sql);
//$delQuery =mysqli_query($con,$sqlE);


if($delQuery==true)
{
	 $data = array(
        'status'=>'success',
       
    );

    echo json_encode($data);
}
else
{
     $data = array(
        'status'=>'failed',
      
    );

    echo json_encode($data);
} 

?>