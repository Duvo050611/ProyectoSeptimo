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

$cart_uniquid = uniqid();
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

$sqlr = "SELECT aseg FROM dat_ingreso WHERE id_atencion='$id_atencion'";
$queryr = mysqli_query($conexion,$sqlr);
while($rowr = mysqli_fetch_assoc($queryr)){
  $at = $rowr['aseg'];
}

$sqlrc = "SELECT tip_precio FROM cat_aseg WHERE aseg='$at'";
$queryrc = mysqli_query($conexion,$sqlrc);
while($rowrc = mysqli_fetch_assoc($queryrc)){
  $tr = $rowrc['tip_precio'];
}

$sqlrs = "SELECT * FROM stock s WHERE s.item_id='$medicam_mat'";
$queryrs = mysqli_query($conexion,$sqlrs);
while($rowrs = mysqli_fetch_assoc($queryrs)){
  $stock_id = $rowrs['stock_id'];
   $stock_qty = $rowrs['stock_qty'];
}

$sqlrsi = "SELECT * FROM item WHERE item_id='$medicam_mat'";
$queryrsi = mysqli_query($conexion,$sqlrsi);
while($rowrsi = mysqli_fetch_assoc($queryrsi)){
 $med_nom = $rowrsi['item_name'].', '.$rowrsi['item_grams'];

               if($tr==1){
            $precio=$rowrsi['item_price'];
        }else if ($tr==2){
            $precio=$rowrsi['item_price2'];
        }else if ($tr==3){
            $precio=$rowrsi['item_price3'];
        }
}
       
        
$sql = "INSERT INTO `cart_almacen` (item_id,cart_qty,cart_price,cart_stock_id,id_usua,cart_uniqid,paciente,cart_fecha,enf_fecha,cart_hora,turno,medicam_mat,dosis_mat,unimed,via_mat,tipo,otro) values ('$medicam_mat', '$cart_qty', '$precio', '$stock_id','$id_usua', '$cart_uniquid', '$id_atencion', '$fr', '$enf_fecha', '$cart_hora', '$turno','$med_nom','$dosis_mat', '$unimed', '$via_mat','QUIROFANO','$otro')";
$query= mysqli_query($conexion,$sql);



/*
$sqlr = "SELECT * FROM cat_servicios WHERE id_serv='$serv'";
$queryr = mysqli_query($con,$sqlr);
while($rowr = mysqli_fetch_assoc($queryr)){
  $serv_desc = $rowr['serv_desc'];
}

$sqlrc = "SELECT * FROM cart_serv WHERE paciente='$id_atencion' ORDER BY id DESC LIMIT 1 ";
$queryrc = mysqli_query($con,$sqlrc);
while($rowrc = mysqli_fetch_assoc($queryrc)){
  $cart_id = $rowrc['id'];
}

$fecha_actual = date("Y-m-d H:i:s");
$fecha_registro = date("Y-m-d");

$sqlC = "INSERT INTO `equipos_ceye` (cart_id,id_atencion,id_usua,serv_id,nombre,tiempo,fecha,fecha_registro) values ('$cart_id', '$id_atencion', '$id_usua', '$serv','$serv_desc', '$qty_serv','$fecha_actual','$fecha_registro')";

*/

//$query= mysqli_query($con,$sqlC);
$lastId = mysqli_insert_id($conexion);
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