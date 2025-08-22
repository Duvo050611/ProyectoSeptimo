<?php 

 session_start();
    $usuario = $_SESSION['login'];
    $id_usua = $usuario['id_usua'];
    $id_atencion = $_SESSION['pac'];
include('../../conexionbd.php');


$enf_fecha = $_POST['enf_fecha'];
$cart_hora= $_POST['cart_hora'];
$medicam_mat= $_POST['medicam_mat'];
$dosis_mat= $_POST['dosis_mat'];
$unimed= $_POST['unimed'];
$via_mat= $_POST['via_mat'];
$otro= $_POST['otro'];
$cart_qty= $_POST['cart_qty'];
$cart_qtyS= $_POST['cart_qtyS'];
$id = $_POST['id'];
$fr = date("Y-m-d H:i");

$hora_med = strval($cart_hora);
    
    if( ($hora_med>='08:00'and $hora_med<='08:59') || 
        ($hora_med>='09:00'and $hora_med<='09:59') || 
        ($hora_med>='10:00'and $hora_med<='10:59') || 
        ($hora_med>='11:00'and $hora_med<='11:59') || 
        ($hora_med>='12:00'and $hora_med<='12:59') || 
        ($hora_med>='13:00'and $hora_med<='13:59')){
        $turno="MATUTINO";
    } else if ( ($hora_med>='14:00'and $hora_med<='14:59') || 
        ($hora_med>='15:00'and $hora_med<='15:59') || 
        ($hora_med>='16:00'and $hora_med<='16:59') || 
        ($hora_med>='17:00'and $hora_med<='17:59') || 
        ($hora_med>='18:00'and $hora_med<='18:59') || 
        ($hora_med>='19:00'and $hora_med<='19:59') ||
        ($hora_med>='20:00'and $hora_med<='20:59') ){
        $turno="VESPERTINO";
    }else if ( ($hora_med>='21:00'and $hora_med<='21:59') || 
        ($hora_med>='22:00'and $hora_med<='22:59') || 
        ($hora_med>='23:00'and $hora_med<='23:59') || 
        ($hora_med>='24:00'and $hora_med<='24:59') || 
        ($hora_med>='01:00'and $hora_med<='01:59') || 
        ($hora_med>='02:00'and $hora_med<='02:59') ||
        ($hora_med>='03:00'and $hora_med<='03:59') ||
        ($hora_med>='04:00'and $hora_med<='04:59') ||
        ($hora_med>='05:00'and $hora_med<='5:59') || 
        ($hora_med>='06:00'and $hora_med<='006:59') ||
        ($hora_med>='07:00'and $hora_med<='07:59') ){
        $turno="NOCTURNO";
    }

$sql = "UPDATE `cart_almacen` SET  `cart_qty`='$cart_qty' ,`cart_surtido`='$cart_qtyS', `cart_fecha`= '$fr', `enf_fecha`='$enf_fecha', `cart_hora`='$cart_hora', `turno`='$turno', `dosis_mat`='$dosis_mat',`unimed`='$unimed',`via_mat`='$via_mat',`otro`='$otro' WHERE id='$id' ";
$query= mysqli_query($con,$sql);





$lastId = mysqli_insert_id($con);
if($query ==true)
{
   
    $data = array(
        'status'=>'true',
       
    );

    echo json_encode($data);
}
else
{
     $data = array(
        'status'=>'false',
      
    );

    echo json_encode($data);
} 

?>