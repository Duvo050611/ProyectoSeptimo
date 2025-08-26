<?php
session_start();
include "../../conexionbd.php";
$resultado = $conexion->query("select * from reg_usuarios") or die($conexion->error);
$usuario = $_SESSION['login'];

$id_usua= $usuario['id_usua'];
$id_atencion = $_SESSION['pac'];

$resultado3 = $conexion->query("SELECT * from dat_quir_grafico WHERE id_atencion=$id_atencion ORDER BY id_quir_graf Desc") or die($conexion->error);

while($f3 = mysqli_fetch_array($resultado3)){
    $id_quir_graff=$f3['id_quir_graf'];
}
    if($id_quir_graff==null){

        $usuario = $_SESSION['login'];
        $id_usua= $usuario['id_usua'];
        $id_atencion = $_SESSION['pac'];

        $fechare =  mysqli_real_escape_string($conexion, (strip_tags($_POST["fechare"], ENT_QUOTES)));
        $horar =  mysqli_real_escape_string($conexion, (strip_tags($_POST["horar"], ENT_QUOTES)));
        $sistg =  mysqli_real_escape_string($conexion, (strip_tags($_POST["sistg"], ENT_QUOTES)));
        $diastg =  mysqli_real_escape_string($conexion, (strip_tags($_POST["diastg"], ENT_QUOTES)));
        $fcardg =  mysqli_real_escape_string($conexion, (strip_tags($_POST["fcardg"], ENT_QUOTES)));
        $satg =  mysqli_real_escape_string($conexion, (strip_tags($_POST["satg"], ENT_QUOTES)));
        $glic =  mysqli_real_escape_string($conexion, (strip_tags($_POST["glic"], ENT_QUOTES)));

//date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");
$ingresar2 = mysqli_query($conexion, 'INSERT INTO dat_quir_grafico (id_atencion,id_usua,hora,sistg,diastg,fcardg,satg,fecha_g,cuenta,fechare,glic) values (' . $id_atencion . ',' . $id_usua .',"'.$horar.'","' . $sistg . '","' . $diastg . '","' . $fcardg . '","' . $satg . '","'.$fecha_actual.'",1,"'.$fechare.'","'.$glic.'") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

             
echo mysqli_query($conexion,$ingresar2);

}else if($id_quir_graff>0){
    $fechare =  mysqli_real_escape_string($conexion, (strip_tags($_POST["fechare"], ENT_QUOTES)));
    $horar =  mysqli_real_escape_string($conexion, (strip_tags($_POST["horar"], ENT_QUOTES)));
    $sistg =  mysqli_real_escape_string($conexion, (strip_tags($_POST["sistg"], ENT_QUOTES)));
    $diastg =  mysqli_real_escape_string($conexion, (strip_tags($_POST["diastg"], ENT_QUOTES)));
    $fcardg =  mysqli_real_escape_string($conexion, (strip_tags($_POST["fcardg"], ENT_QUOTES)));
    $satg =  mysqli_real_escape_string($conexion, (strip_tags($_POST["satg"], ENT_QUOTES)));
    $glic =  mysqli_real_escape_string($conexion, (strip_tags($_POST["glic"], ENT_QUOTES)));
    $resultado3 = $conexion->query("SELECT * from dat_quir_grafico WHERE id_atencion=$id_atencion ORDER BY cuenta Desc limit 1") or die($conexion->error);

    while($f3 = mysqli_fetch_array($resultado3)){
        $cuenta=$f3['cuenta'];
    }
    $cuenta++;
    //date_default_timezone_set('America/Guatemala');
    $fecha_actual = date("Y-m-d H:i:s");
    $ingresar2 = mysqli_query($conexion, 'INSERT INTO dat_quir_grafico (id_atencion,id_usua,hora,sistg,diastg,fcardg,satg,fecha_g,cuenta,fechare,glic) values (' . $id_atencion . ',' . $id_usua .',"'.$horar.'","' . $sistg . '","' . $diastg . '","' . $fcardg . '","' . $satg . '","'.$fecha_actual.'","'.$cuenta.'","'.$fechare.'","'.$glic.'") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));
    echo mysqli_query($conexion,$ingresar2);
}

          ?>