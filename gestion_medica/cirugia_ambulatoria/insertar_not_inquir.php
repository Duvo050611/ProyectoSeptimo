<?php
session_start();
include '../../conexionbd.php';
$usuario = $_SESSION['login'];
$id_usua= $usuario['id_usua'];
$id_atencion = $_GET['id_atencion'];


if (isset($_POST['tipo_intervencion'])) {
    if($tipo_intervencion="URGENCIAS"){
$tipo_intervencion= $_POST['tipo_intervencion'];
    }

    if($tipo_intervencion="ELECTIVA"){
$tipo_intervencion= $_POST['tipo_intervencion'];
    }

}

//$cama=$_POST['cama'];
//$fecha=$_POST['fecha'];

//$hora_solicitud=$_POST['hora_solicitud'];
//$intervencion_sol=$_POST['intervencion_sol'];

$diag_preop=$_POST['diag_preop'];
$quirofano=$_POST['quirofano'];
$reserva=$_POST['reserva'];
$local=$_POST['local'];
$regional=$_POST['regional'];
$general=$_POST['general'];
//$hb=$_POST['hb'];
//$hto=$_POST['hto'];
//$peso=$_POST['peso'];
//$tipo_sangre=$_POST['tipo_sangre'];
$inst_necesario=$_POST['inst_necesario'];
$medmat_necesario=$_POST['medmat_necesario'];

//$nom_jefe_serv=$_POST['nom_jefe_serv'];
//$fecha_progra=$_POST['fecha_progra'];
//$hora_progra=$_POST['hora_progra'];
//$sala=$_POST['sala'];
//$jefe_cirugia=$_POST['jefe_cirugia'];
$intervencion_quir=$_POST['intervencion_quir'];
$inicio=$_POST['inicio'];
$termino=$_POST['termino'];
$diag_postop=$_POST['diag_postop'];
$cir_realizada=$_POST['cir_realizada'];
$trans=$_POST['trans'];
$posto=$_POST['posto'];
$perd_hema=$_POST['perd_hema'];
//$gasas=$_POST['gasas'];
//$compresas=$_POST['compresas'];
$accident_incident=$_POST['accident_incident'];
$anestesia_admin=$_POST['anestesia_admin'];
$anestesia_dur=$_POST['anestesia_dur'];
$realizada_por  =$_POST['realizada_por'];
$cirujano=$_POST['cirujano'];
$prim_ayudante=$_POST['prim_ayudante'];
$seg_ayudante=$_POST['seg_ayudante'];
$ter_ayudante=$_POST['ter_ayudante'];
$anestesiologo=$_POST['anestesiologo'];
//$resid_anest=$_POST['resid_anest'];
$circulante=$_POST['circulante'];
$instrumentista=$_POST['instrumentista'];
$quir=$_POST['quir'];
$hora_llegada_quir=$_POST['hora_llegada_quir'];
$hora_salida_quir=$_POST['hora_salida_quir'];
$hora_entrada_recup=$_POST['hora_entrada_recup'];
$hora_salida_recup=$_POST['hora_salida_recup'];
$nota_preop=$_POST['nota_preop'];

if(isset($_POST['estado_postop'])){
   if($estado_postop="BUENO"){
       $estado_postop=$_POST['estado_postop'];
   }
   
   if($estado_postop="DELICADO"){
       $estado_postop=$_POST['estado_postop'];
   }

   if($estado_postop="GRAVE"){
       $estado_postop=$_POST['estado_postop'];
   }

   if($estado_postop="MUY GRAVE"){
       $estado_postop=$_POST['estado_postop'];
   }


}

$estado_postop=$_POST['estado_postop'];
$comentario_final=$_POST['comentario_final'];
$plan_ter=$_POST['plan_tera'];

$descripcion_op=$_POST['descripcion_op'];
//$cirugia_prog=$_POST['cirugia_prog'];


$nombre_med_cir=$_POST['nombre_med_cir'];

$resultado1 = $conexion->query("select paciente.*, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE id_atencion=id_atencion" ) or die($conexion->error);
   while ($f1 = mysqli_fetch_array($resultado1)) {
    $tipo_sangre=$f1['tip_san'];
   }
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d ");
date_default_timezone_set('America/Guatemala');
$hora_actual = date("H:i:s");

$result=mysqli_query($conexion,'INSERT INTO dat_not_inquir_amb(id_atencion,id_usua,tipo_intervencion,fecha,hora_solicitud,diag_preop,quirofano,reserva,local,regional,general,tipo_sangre,inst_necesario,medmat_necesario,jefe_cirugia,intervencion_quir,inicio,termino,diag_postop,cir_realizada,trans,posto,perd_hema,accident_incident,anestesia_admin,anestesia_dur,realizada_por,cirujano,prim_ayudante,seg_ayudante,ter_ayudante,anestesiologo,circulante,instrumentista,quir,hora_llegada_quir,hora_salida_quir,hora_entrada_recup,hora_salida_recup,nota_preop,estado_postop,comentario_final,plan_ter,descripcion_op,nombre_med_cir) 
    VALUES('.$id_atencion.','.$id_usua.',"'.$tipo_intervencion.'","'.$fecha_actual.'","'.$hora_actual.'","'.$diag_preop.'","'.$quirofano.'","'.$reserva.'","'.$local.'","'.$regional.'","'.$general.'","'.$tipo_sangre.'","'.$inst_necesario.'","'.$medmat_necesario.'","'.$jefe_cirugia.'","'.$intervencion_quir.'","'.$inicio.'","'.$termino.'","'.$diag_postop.'","'.$cir_realizada.'","'.$trans.'","'.$posto.'","'.$perd_hema.'","'.$accident_incident.'","'.$anestesia_admin.'","'.$anestesia_dur.'","'.$realizada_por.'","'.$cirujano.'","'.$prim_ayudante.'","'.$seg_ayudante.'","'.$ter_ayudante.'","'.$anestesiologo.'","'.$circulante.'","'.$instrumentista.'","'.$quir.'","'.$hora_llegada_quir.'","'.$hora_salida_quir.'","'.$hora_entrada_recup.'","'.$hora_salida_recup.'","'.$nota_preop.'","'.$estado_postop.'","'.$comentario_final.'","'.$plan_ter.'","'.$descripcion_op.'","'.$nombre_med_cir.'")')or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

 $resultado1 = $conexion ->query("SELECT TIMESTAMPDIFF(MINUTE,hora_llegada_quir,hora_salida_quir ) as MINUTEs FROM `dat_not_inquir_amb` WHERE id_atencion=$id_atencion")or die($conexion->error);
  
  if(mysqli_num_rows($resultado1) > 0 ){ //se mostrara si existe mas de 1
    $fila=mysqli_fetch_row($resultado1);
     $resultado=$fila[0];
  }
$res=$resultado/60;
$decimales=explode(".", $res);

if(isset($decimales[1])){

  if($decimales[1] >= 0){
  $horas=$decimales[0]+1;
  //echo "<br>el resultado final es:".$horas."horas";
  }
}else{
      if($res%2 <= 0)
  
  $decimales[0];
   $horas=$decimales[0];
   // echo "<br> el resultado es exacto:".$horas."horas";
}

 if($quir=='QUIROFANO'){ 
   $ingresar2=mysqli_query($conexion,'insert into dat_ctapac(id_atencion,prod_serv,insumo,cta_cant,id_usua) values ('.$id_atencion.',"S","5",'.$horas.','.$id_usua.')') or die ('<p>Error al registrar</p><br>'.mysqli_error($conexion));

 }
  if($quir=='SALA DE PARTO'){
   $ingresar3=mysqli_query($conexion,'insert into dat_ctapac(id_atencion,prod_serv,insumo,cta_cant,id_usua) values ('.$id_atencion.',"S","6",'.$horas.','.$id_usua.')') or die ('<p>Error al registrar</p><br>'.mysqli_error($conexion));

 }


  
header('location: ../../template/menu_medico.php');


?>