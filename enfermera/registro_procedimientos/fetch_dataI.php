<?php 
session_start();
    $usuario = $_SESSION['login'];
    $id_usua = $usuario['id_usua'];
    $id_atencion = $_SESSION['pac'];
    include('../../conexionbd.php');
$output= array();

$sql = "SELECT * FROM ingresos_quir WHERE id_atencion=$id_atencion";

$totalQuery = mysqli_query($conexion,$sql);
$total_all_rows = mysqli_num_rows($totalQuery);

$columns = array(
	0 => 'id',
	1 => 'id_usua',
	2 => 'id_atencion',
	3 => 'fecha',
	4 => 'tipo',
	5 => 'soluciones',
	6 => 'hora',
	7 => 'volumen',
	8 => 'fecha_registro',
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
    $id_atencion = $_SESSION['hospital'];
	$column_name = $_POST['order'][0]['column'];
	$order = $_POST['order'][0]['dir'];
	$sql .=  " ORDER BY ".$columns[$column_name]." ".$order."";
}
else
{
	$sql .= " ORDER BY id desc";
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
	$sub_array[] = $row['id'];
	$sub_array[] = $row['fecha_registro'];
	$sub_array[] = $row['fecha'];
	$sub_array[] = $row['hora'];
	$sub_array[] = $row['soluciones'];
	$sub_array[] = $row['volumen'];
	$sub_array[] = $row['id_usua'];
	
	$sub_array[] = '
        <div class="action-buttons-container">
            <a href="javascript:void(0);" 
               data-id="'.$row['id'].'" 
               class="btn btn-action btn-edit-modern editbtnI" 
               title="Editar registro">
                <i class="fas fa-edit"></i>
                <span>Editar</span>
            </a>
            <a href="javascript:void(0);" 
               data-id="'.$row['id'].'" 
               class="btn btn-action btn-delete-modern deleteBtnI" 
               title="Eliminar registro">
                <i class="fas fa-trash-alt"></i>
                <span>Eliminar</span>
            </a>
        </div>
    ';
	$data[] = $sub_array;

}
$output = array(
	'draw'=> intval($_POST['draw']),
	'recordsTotal' =>$count_rows ,
	'recordsFiltered'=>   $total_all_rows,
	'data'=>$data,
);
echo  json_encode($output);
