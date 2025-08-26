<?php
session_start();
include '../../conexionbd.php';
$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];

//$id_atencion = $_SESSION['pac'];

date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

$resrep=$_POST['resrep'];
$resenf=$_POST['resenf'];
$resmed=$_POST['resmed'];
$resotro=$_POST['resotro'];
$solrec=$_POST['solrec'];
$solenf=$_POST['solenf'];
$solmed=$_POST['solmed'];
$solotro=$_POST['solotro'];
$brrec=$_POST['brrec'];
$brenf=$_POST['brenf'];
$brmed=$_POST['brmed'];
$brotro=$_POST['brotro'];
$servins=$_POST['servins'];
$servlim=$_POST['servlim'];
$servropa=$_POST['servropa'];
$servali=$_POST['servali'];
$recsan=$_POST['recsan'];
$obs=$_POST['obs'];
$lab=$_POST['lab'];
$imagen=$_POST['imagen'];
$vig=$_POST['vig'];
$id_atencion=$_POST['id_atencion'];

//insercion a encuestas
$ingresarrecnac = mysqli_query($conexion, 'INSERT INTO encuestas (id_atencion,id_usua,fecenc,resrep,resenf,resmed,resotro,solrec,solenf,solmed,solotro,brrec,brenf,brmed,brotro,servins,servlim,servropa,servali,recsan,obs,lab,imagen,vig) values (' . $id_atencion . ',' . $id_usua . ',"' . $fecha_actual . '","' . $resrep . '","' . $resenf . '","' . $resmed . '","' . $resotro . '","' . $solrec . '","' . $solenf .'","' . $solmed . '","'.$solotro.'","'.$brrec.'","'.$brenf.'","'.$brmed.'","'.$brotro.'","'.$servins.'","'.$servlim.'","'.$servropa.'","'.$servali.'","'.$recsan.'","'.$obs.'","'.$lab.'","'.$imagen.'","'.$vig.'")') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));


	echo '<script>
window.close(); //Cierra la hija.
</script>';
?>