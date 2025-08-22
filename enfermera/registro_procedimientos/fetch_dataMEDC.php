<?php 
session_start();
    $usuario = $_SESSION['login'];
    $id_usua = $usuario['id_usua'];
    $id_atencion = $_SESSION['pac'];
   include('../../conexionbd.php');
$output= array();

$sql = "SELECT * from medica_enf where id_atencion='$id_atencion' and material='No' ";
$totalQuery = mysqli_query($conexion,$sql);
$total_all_rows = mysqli_num_rows($totalQuery);

$columns = array(
	0 => 'id_med_reg',
	1 => 'id_atencion',
	2 => 'fecha_mat',
	3 => 'hora_mat',
	4 => 'turno',	
	5 => 'medicam_mat',
	6 => 'dosis_mat',
	7 => 'unimed',
	8 => 'via_mat',
	9 => 'otro',
	10 => 'cantidad',
	11 => 'cart_id',
	12 => 'material_id',
);



/*if(isset($_POST['search']['value']))
{
	$search_value = $_POST['search']['value'];
	$sql .= " WHERE dispositivos like '%".$search_value."%'";
	$sql .= " OR tipo like '%".$search_value."%'";
	$sql .= " OR fecha_inst like '%".$search_value."%'";
	$sql .= " OR instalo like '%".$search_value."%'";
	$sql .= " OR id_atencion like '%".$search_value."%'";
}*/

if(isset($_POST['order']))
{
    $usuario = $_SESSION['login'];
    $id_usua = $usuario['id_usua'];
    $id_atencion = $_SESSION['pac'];
	$column_name = $_POST['order'][0]['column'];
	$order = $_POST['order'][0]['dir'];
	$sql .=  " ORDER BY ".$columns[$column_name]." ".$order."";
}
else
{
	$sql .= " ORDER BY id_med_reg desc";
}

if($_POST['length'] != -1)
{
	$start = $_POST['start'];
	$length = $_POST['length'];
	$sql .= " LIMIT  ".$start.", ".$length;
}	

$query = mysqli_query($conexion,$sql);
$count_rows = mysqli_num_rows($query);
$data = array();
while($row = mysqli_fetch_assoc($query))
{
	$sub_array = array();
	$sub_array[] = $row['id_med_reg'];
	$sub_array[] = $row['fecha_mat'];
    $sub_array[] = $row['hora_mat'];
    $sub_array[] = $row['medicam_mat'];
    $sub_array[] = $row['dosis_mat'] . ' ' . $row['unimed'];
    $sub_array[] = $row['via_mat'];
    $sub_array[] = $row['otro'];
    $sub_array[] = $row['cantidad'];
    
	//$sub_array[] = '<a href="javascript:void();" data-id="'.$row['id'].'"  class="btn btn-warning btn-sm editbtnME" >Editar</a>  <a href="javascript:void();" data-id="'.$row['id'].'"  class="btn btn-danger btn-sm deleteBtnME" >Eliminar</a>';
	
	$data[] = $sub_array;
}

$output = array(
	'draw'=> intval($_POST['draw']),
	'recordsTotal' =>$count_rows ,
	'recordsFiltered'=>   $total_all_rows,
	'data'=>$data,
);
echo  json_encode($output);
