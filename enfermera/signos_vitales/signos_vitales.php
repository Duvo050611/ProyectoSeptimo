<?php
session_start();
include '../../conexionbd.php';
$conexion = ConexionBD::getInstancia()->getConexion();
include "../header_enfermera.php";
$resultado = $conexion->query("select * from reg_usuarios") or die($conexion->error);
$usuario = $_SESSION['pac'];
$fecha = @$_GET['fecha'];
$idexp = @$_GET['idexp'];

?>
<!DOCTYPE html>
<html>

<head>
  <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″ />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  <!--  Bootstrap  -->
  <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

  <!---
  <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
  <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css'>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
-->
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/jquery-ui.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.magnific-popup.min.js"></script>
    <script src="../js/aos.js"></script>
    <script src="../js/main.js"></script>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

  <script>
    // Write on keyup event of keyword input element
    $(document).ready(function() {
      $("#search").keyup(function() {
        _this = this;
        // Show only matching TR, hide rest of them
        $.each($("#mytable tbody tr"), function() {
          if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
            $(this).hide();
          else
            $(this).show();
        });
      });
    });
  </script>
  <script>
    // Write on keyup event of keyword input element
    $(document).ready(function() {
      $("#search_dep").keyup(function() {
        _this = this;
        // Show only matching TR, hide rest of them
        $.each($("#mytable tbody tr"), function() {
          if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
            $(this).hide();
          else
            $(this).show();
        });
      });
    });
  </script>
  <title>SIGNOS VITALES</title>
  <style type="text/css">
    #contenido{
        display: none;
    }
     #contenido3{
        display: none;
    }
     #contenido4{
        display: none;
    }
     td{
         color: white;
     }
</style>
  
</head>

<body>
 
    <?php
    if (isset($_SESSION['pac'])) {
      $id_atencion = $_SESSION['pac'];

      $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.tel, p.fecnac,p.tip_san, di.fecha, di.area, di.alta_med, p.sexo, di.alergias, p.folio FROM paciente p, dat_ingreso di WHERE p.Id_exp=di.Id_exp and di.id_atencion =$id_atencion";

      $result_pac = $conexion->query($sql_pac);

      while ($row_pac = $result_pac->fetch_assoc()) {
        $pac_papell = $row_pac['papell'];
        $pac_sapell = $row_pac['sapell'];
        $pac_nom_pac = $row_pac['nom_pac'];
        $pac_dir = $row_pac['dir'];
        $pac_id_edo = $row_pac['id_edo'];
        $pac_id_mun = $row_pac['id_mun'];
        $pac_tel = $row_pac['tel'];
        $pac_fecnac = $row_pac['fecnac'];
        $pac_fecing = $row_pac['fecha'];
        $pac_tip_sang = $row_pac['tip_san'];
        $pac_sexo = $row_pac['sexo'];
        $area = $row_pac['area'];
        $alta_med = $row_pac['alta_med'];
        $id_exp = $row_pac['Id_exp'];
        $alergias = $row_pac['alergias'];
         $folio = $row_pac['folio'];
      }

        $sql_pac = "SELECT * FROM  dat_ingreso WHERE id_atencion =$id_atencion";

          $result_pac = $conexion->query($sql_pac);

          while ($row_pac = $result_pac->fetch_assoc()) {
            $fingreso = $row_pac['fecha'];
             $fegreso = $row_pac['fec_egreso'];
             $alta_med = $row_pac['alta_med'];
             $alta_adm = $row_pac['alta_adm'];
             $activo = $row_pac['activo'];
             $valida = $row_pac['valida'];
          }

if($alta_med=='SI' && $alta_adm=='SI' && $activo=='NO' && $valida=='SI'){
    
    $sql_est = "SELECT DATEDIFF('$fegreso', '$fingreso') as estancia FROM dat_ingreso where id_atencion = $id_atencion";

      $result_est = $conexion->query($sql_est);

      while ($row_est = $result_est->fetch_assoc()) {
          $estancia = $row_est['estancia'];
       
      }
}else{
    
   $sql_now = "SELECT DATE_ADD(NOW(), INTERVAL 12 HOUR) as dat_now FROM dat_ingreso WHERE id_atencion = $id_atencion";

      $result_now = $conexion->query($sql_now);

      while ($row_now = $result_now->fetch_assoc()) {
        $dat_now = $row_now['dat_now'];
      }

      $sql_est = "SELECT DATEDIFF( '$dat_now' , fecha) as estancia FROM dat_ingreso WHERE id_atencion = $id_atencion";

      $result_est = $conexion->query($sql_est);

      while ($row_est = $result_est->fetch_assoc()) {
        $estancia = $row_est['estancia'];
      } 
}

      function bisiesto($anio_actual){
    $bisiesto=false;
    //probamos si el mes de febrero del año actual tiene 29 días
      if (checkdate(2,29,$anio_actual))
      {
        $bisiesto=true;
    }
    return $bisiesto;
}

date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d");
$fecha_nac=$pac_fecnac;
$fecha_de_nacimiento =strval($fecha_nac);

// separamos en partes las fechas
$array_nacimiento = explode ( "-", $fecha_de_nacimiento );
$array_actual = explode ( "-", $fecha_actual );

$anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos años
$meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses
$dias =  $array_actual[2] - $array_nacimiento[2]; // calculamos días

//ajuste de posible negativo en $días
if ($dias < 0)
{
    --$meses;

    //ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual
    switch ($array_actual[1]) {
           case 1:     $dias_mes_anterior=31; break;
           case 2:     $dias_mes_anterior=31; break;
           case 3:     
               if (bisiesto($array_actual[0]))
                {
                    $dias_mes_anterior=29; break;
                } else {
                    $dias_mes_anterior=28; break;
                }
               
           case 4:     $dias_mes_anterior=31; break;
           case 5:     $dias_mes_anterior=30; break;
           case 6:     $dias_mes_anterior=31; break;
           case 7:     $dias_mes_anterior=30; break;
           case 8:     $dias_mes_anterior=31; break;
           case 9:     $dias_mes_anterior=31; break;
           case 10:     $dias_mes_anterior=30; break;
           case 11:     $dias_mes_anterior=31; break;
           case 12:     $dias_mes_anterior=30; break;
    }

    $dias=$dias + $dias_mes_anterior;
}

//ajuste de posible negativo en $meses
if ($meses < 0)
{
    --$anos;
    $meses=$meses + 12;
}

//echo "<br>Tu edad es: $anos años con $meses meses y $dias días";

    ?>
   
        
          <div class="container">
          <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 24px;">
              
  <center><strong>SIGNOS VITALES</strong></center><p>
</div>
       </div>  
 <font size="2">
         <div class="container">
  <div class="row">
    <div class="col-sm-6">
  
      Expediente: <strong><?php echo $folio?> </strong>
   
     Paciente: <strong><?php echo $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac ?></strong>
    </div>
    <div class="col-sm">
      Área: <strong><?php echo $area ?></strong>
    </div>
   <?php $date = date_create($pac_fecing);
   ?>
      <div class="col-sm">
      Fecha de ingreso: <strong><?php echo date_format($date, "d-m-Y") ?></strong>
    </div>
  </div>
</div></font>

 <font size="2">
     <div class="container">
  <div class="row">
    <div class="col-sm">
       <?php $date1 = date_create($pac_fecnac);
   ?>
      Fecha de nacimiento: <strong><?php echo date_format($date1, "d-m-Y") ?></strong>
    </div>
    <div class="col-sm">
      Tipo de sangre: <strong><?php echo $pac_tip_sang ?></strong>
    </div>
   
      <div class="col-sm">
      Habitación: <strong><?php $sql_hab = "SELECT num_cama from cat_camas where id_atencion =$id_atencion";
$result_hab = $conexion->query($sql_hab);                                                                                    while ($row_hab = $result_hab->fetch_assoc()) {
  echo $row_hab['num_cama'];
} ?></strong>
    </div>
    <div class="col-sm">
      Tiempo estancia: <strong><?php echo $estancia ?> Dias</strong>
    </div>
  </div>
</div>
</font>
 <font size="2">
  <div class="container">
  <div class="row">
   <div class="col-sm-3">
      Edad: <strong><?php if($anos > "0" ){
   echo $anos." años";
}elseif($anos <="0" && $meses>"0"){
    echo $meses." meses";
}elseif($anos <="0" && $meses<="0" && $dias>"0"){
    echo $dias." dias";
}
?></strong>
    </div>
    <div class="col-sm-3">
Peso: <strong><?php $sql_vit = "SELECT * from dat_hclinica where Id_exp=$id_exp ORDER by id_hc DESC LIMIT 1";
$result_vit = $conexion->query($sql_vit);
while ($row_vit = $result_vit->fetch_assoc()) {
$peso=$row_vit['peso'];
}if(!isset($peso)){
    $peso=0;
}   echo $peso;?></strong>
    </div>
   <div class="col-sm">
      Talla: <strong><?php $sql_vitt =" SELECT * from dat_hclinica where Id_exp=$id_exp ORDER by id_hc DESC LIMIT 1";
$result_vitt = $conexion->query($sql_vitt); 
while ($row_vitt = $result_vitt->fetch_assoc()) {
$talla=$row_vitt['talla'];
}
if(!isset($talla)){
    $talla=0;
}   echo $talla;?></strong>
    </div>
    <div class="col-sm">
      Género: <strong><?php echo $pac_sexo ?></strong>
    </div>
  </div>
</div>
</font>
 <font size="2">
  <div class="container">
  <div class="row">
    <div class="col-sm-3">
      Alergias: <strong><?php echo $alergias ?></strong>
    </div>
    <div class="col-sm-6">
      Estado de salud: <strong><?php $sql_edo = "SELECT edo_salud from dat_ingreso where id_atencion=$id_atencion ORDER by edo_salud DESC LIMIT 1";
$result_edo = $conexion->query($sql_edo);                                                                                    while ($row_edo = $result_edo->fetch_assoc()) {
  echo $row_edo['edo_salud'];
} ?></strong>
    </div>
      
     <div class="col-sm">
    Aseguradora: <strong><?php $sql_aseg = "SELECT aseg from dat_financieros where id_atencion =$id_atencion ORDER BY fecha DESC limit 1";
                                                                                  $result_aseg = $conexion->query($sql_aseg);
                                                                                  while ($row_aseg = $result_aseg->fetch_assoc()) {
                                                                                    echo $row_aseg['aseg'];
                                                                                  } ?></strong>
    </div>
  </div>
</div>
</font>
  <font size="2">
    <div class="container">
 <div class="col-sm-4">
 <?php 
$d="";
      $sql_motd = "SELECT diagprob_i from dat_nevol where id_atencion=$id_atencion ORDER by diagprob_i ASC LIMIT 1";
    $result_motd = $conexion->query($sql_motd);
    while ($row_motd = $result_motd->fetch_assoc()) {
        $d=$row_motd['diagprob_i'];
    } ?>
    <?php $sql_mot = "SELECT motivo_atn from dat_ingreso where id_atencion=$id_atencion ORDER by motivo_atn ASC LIMIT 1";
    $result_mot = $conexion->query($sql_mot);
    while ($row_mot = $result_mot->fetch_assoc()) {
    $m=$row_mot['motivo_atn'];
    } ?>

    <?php if ($d!=null) {
       echo '<td> Diagnóstico: <strong>' . $d .'</strong></td>';
    } else{
          echo '<td"> Motivo de atención: <strong>' . $m .'</strong></td>';
    }?>
    </div>
    <div>
  </font>
<hr>

       <?php
            } else {
                echo '<script type="text/javascript"> window.location.href="../../template/select_pac_enf.php";</script>';
            }
            ?>
    
<body>
<div class="container">
    <div class="row">
        
            
            
         

            <!--<div class="form-group">
                <input type="text" class="form-control pull-right" style="width:20%" id="search" placeholder="Buscar...">
            </div>-->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="container" >
  <div class="row">
  <a href="javascript:window.print()"><i class="fa fa-print" aria-hidden="true"></i></a>
 <div class="col-sm-12">
      <h6 class="text-center"> SIGNOS VITALES</h6>
      
    
    <canvas id="grafica"></canvas>
    <script src="script.js"></script>
    </div>
    
   
  
  </div>
</div>

               <?php

$id_atencion=$_SESSION['pac'];
$fecha = @$_GET['fecha'];

$resultado = $conexion->query("SELECT * from signos_vitales WHERE id_atencion=$id_atencion AND fecha='$fecha' ORDER BY id_sig DESC") or die($conexion->error);
$usuario = $_SESSION['login'];

//$resultadost = $conexion->query("SELECT * from dat_hclinica WHERE Id_exp=$id_atencion") or die($conexion->error);
?>
            <div class="container">
            <div class="table-responsive">
<br>
            <table class="table table-bordered table-striped" id="mytable">
                <thead class="thead bg-navy">
                <tr>
                  <th scope="col">No.</th>
                    <th scope="col">Fecha</th>
                     <th scope="col">Hora</th>
                    <th scope="col">Presión arterial</th>
                     <th scope="col">Frecuencia cardiaca</th>
                     <th scope="col">Presión respiratoria</th>
                    <th scope="col">Temperatura</th>
                    <th scope="col">Saturación oxígeno</th>
                     <th scope="col">Frecuencia cardiaca fetal</th>
                    <th scope="col">Nivel dolor</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Registró</th>
                     <th scope="col">Editar</th>
                    <th scope="col">Eliminar</th>
                </tr>
                </thead>
                <tbody>

                <?php
                $no=1;
                while($f = mysqli_fetch_array($resultado)){
                    //while($f2 = mysqli_fetch_array($resultadost)){
                     $id_usua=$f['id_usua'];
$resultado_usua = $conexion->query("SELECT * FROM reg_usuarios WHERE id_usua=$id_usua") or die($conexion->error);
        while($row = mysqli_fetch_array($resultado_usua)){
                    ?>

                    <tr>
                      <td><strong><?php  echo $no?></strong></td>
                        <td><strong><?php $date=date_create($f['fecha']); echo date_format($date,"d/m/Y");?></strong></td>
                         <td><strong><?php echo $f['hora'];?></strong></td>
                        <td><strong><?php echo $f['p_sistol'];?>/<?php echo $f['p_diastol'];?></strong></td>
                        <td><strong><?php echo $f['fcard'];?></strong></td>
                        <td><strong><?php echo $f['fresp'];?></strong></td>
                        <td><strong><?php echo $f['temper'];?></strong></td>
                        <td><strong><?php echo $f['satoxi'];?></strong></td>
                         <td><strong><?php echo $f['fcardf'];?></strong></td>
                        <td><strong><?php echo $f['niv_dolor'];?></strong></td>
                        <td><strong><?php echo $f['tipo'];?></strong></td>
                        
                        <td class="fondo"><strong><?php echo $row['papell'].' '.$row['sapell']?></strong></td>

<?php if($usuario['id_usua']==$f['id_usua']||$usuario['id_usua']=1){
?>
<td><a href="edit_signos.php?id_sig=<?php echo $f['id_sig'];?>&id_atencion=<?php echo $f['id_atencion'];?>"><button type="button" class="btn btn-warning"><i class="fas fa-edit"></i></button></td>
<?php }else{
?>
<td>
<div class="alert alert-danger alert-sm" role="alert">
<i class="fa fa-power-off" aria-hidden="true" title="Sin acceso"></i>
</div></td>
<?php }?>



                  <td><a href="el_signos.php?id_sig=<?php echo $f['id_sig'];?>&id_atencion=<?php echo $f['id_atencion'];?>"><button type="button" class="btn btn-danger">X </button></td>

                       <?php $no++; ?>
                    </tr>
                    <?php
                }
            }
//}
                ?>
                </tbody>
              
            </table>
            </div>
          </div>

<div class="container">
<center>
<button type="button" class="btn btn-danger" onclick="history.back()">Regresar...</button>
</center>
</div>

        </div>
    </div>
    <br>
</div>



<footer class="main-footer">
    <?php
    include("../../template/footer.php");
    ?>
</footer>

<script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<!-- FastClick -->
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
      
 



<script>

const $grafica = document.querySelector("#grafica");
// Las etiquetas son las que van en el eje X. 
const etiquetas = ["8", "9", "10", "11","12", "13", "14", "15","16", "17", "18", "19","20", "21", "22", "23","24", "1", "2", "3","4", "5", "6", "7"]
// Podemos tener varios conjuntos de datos
const presion = {
    label: "PRESIÓN ARTERIAL",
    data: [<?php 
$resp = $conexion->query("select p_sistol from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=8") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['p_sistol'];

 } ?>, <?php 
$resp = $conexion->query("select p_sistol from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=9") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['p_sistol'];

 } ?>, <?php 
$resp = $conexion->query("select p_sistol from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=10") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['p_sistol'];

 } ?>, <?php 
$resp = $conexion->query("select p_sistol from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=11") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['p_sistol'];

 } ?>, <?php 
$resp = $conexion->query("select p_sistol from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=12") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['p_sistol'];

 } ?>,<?php 
$resp = $conexion->query("select p_sistol from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=13") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['p_sistol'];

 } ?>, <?php 
$resp = $conexion->query("select p_sistol from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=14") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['p_sistol'];

 } ?>, <?php 
$resp = $conexion->query("select p_sistol from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=15") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['p_sistol'];

 } ?>, <?php 
$resp = $conexion->query("select p_sistol from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=16") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['p_sistol'];

 } ?>, <?php 
$resp = $conexion->query("select p_sistol from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=17") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['p_sistol'];

 } ?>,<?php 
$resp = $conexion->query("select p_sistol from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=18") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['p_sistol'];

 } ?>, <?php 
$resp = $conexion->query("select p_sistol from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=19") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['p_sistol'];

 } ?>, <?php 
$resp = $conexion->query("select p_sistol from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=20") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['p_sistol'];

 } ?>, <?php 
$resp = $conexion->query("select p_sistol from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=21") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['p_sistol'];

 } ?>, <?php 
$resp = $conexion->query("select p_sistol from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=22") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['p_sistol'];

 } ?>,<?php 
$resp = $conexion->query("select p_sistol from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=23") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['p_sistol'];

 } ?>, <?php 
$resp = $conexion->query("select p_sistol from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=24") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['p_sistol'];

 } ?>, <?php 
$resp = $conexion->query("select p_sistol from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=1") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['p_sistol'];

 } ?>, <?php 
$resp = $conexion->query("select p_sistol from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=2") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['p_sistol'];

 } ?>, <?php 
$resp = $conexion->query("select p_sistol from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=3") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['p_sistol'];

 } ?>,<?php 
$resp = $conexion->query("select p_sistol from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=4") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['p_sistol'];

 } ?>, <?php 
$resp = $conexion->query("select p_sistol from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=5") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['p_sistol'];

 } ?>, <?php 
$resp = $conexion->query("select p_sistol from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=6") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['p_sistol'];

 } ?>, <?php 
$resp = $conexion->query("select p_sistol from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=7") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['p_sistol'];

 } ?>], // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
    backgroundColor: 'rgba(54, 162, 235, 0.2)', // Color de fondo
    borderColor: 'rgba(54, 162, 235, 1)', // Color del borde
    borderWidth: 1,// Ancho del borde
};
const frec = {
    label: "FRECUENCIA CARDIACA",
   data: [<?php 
$resp = $conexion->query("select fcard from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=8") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcard'];

 } ?>, <?php 
$resp = $conexion->query("select fcard from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=9") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcard'];

 } ?>, <?php 
$resp = $conexion->query("select fcard from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=10") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcard'];

 } ?>, <?php 
$resp = $conexion->query("select fcard from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=11") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcard'];

 } ?>, <?php 
$resp = $conexion->query("select fcard from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=12") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcard'];

 } ?>,<?php 
$resp = $conexion->query("select fcard from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=13") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcard'];

 } ?>, <?php 
$resp = $conexion->query("select fcard from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=14") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcard'];

 } ?>, <?php 
$resp = $conexion->query("select fcard from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=15") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcard'];

 } ?>, <?php 
$resp = $conexion->query("select fcard from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=16") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcard'];

 } ?>, <?php 
$resp = $conexion->query("select fcard from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=17") or die($conexion->error); 
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcard'];

 } ?>,<?php 
$resp = $conexion->query("select fcard from signos_vitales where id_atencion=$id_atencion and hora=18") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcard'];

 } ?>, <?php 
$resp = $conexion->query("select fcard from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=19") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcard'];

 } ?>, <?php 
$resp = $conexion->query("select fcard from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=20") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcard'];

 } ?>, <?php 
$resp = $conexion->query("select fcard from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=21") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcard'];

 } ?>, <?php 
$resp = $conexion->query("select fcard from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=22") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcard'];

 } ?>,<?php 
$resp = $conexion->query("select fcard from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=23") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcard'];

 } ?>, <?php 
$resp = $conexion->query("select fcard from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=24") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcard'];

 } ?>, <?php 
$resp = $conexion->query("select fcard from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=1") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcard'];

 } ?>, <?php 
$resp = $conexion->query("select fcard from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=2") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcard'];

 } ?>, <?php 
$resp = $conexion->query("select fcard from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=3") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcard'];

 } ?>,<?php 
$resp = $conexion->query("select fcard from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=4") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcard'];

 } ?>, <?php 
$resp = $conexion->query("select fcard from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=5") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcard'];

 } ?>, <?php 
$resp = $conexion->query("select fcard from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=6") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcard'];

 } ?>, <?php 
$resp = $conexion->query("select fcard from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=7") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcard'];

 } ?>], // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
    backgroundColor: 'rgba(255, 159, 64, 0.2)',// Color de fondo
    borderColor: 'rgba(255, 159, 64, 1)',// Color del borde
    borderWidth: 1,// Ancho del borde
};

const fresp = {
    label: "FRECUENCIA RESPIRATORIA",
    data: [<?php 
$resp = $conexion->query("select fresp from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=8") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fresp'];

 } ?>, <?php 
$resp = $conexion->query("select fresp from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=9") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fresp'];

 } ?>, <?php 
$resp = $conexion->query("select fresp from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=10") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fresp'];

 } ?>, <?php 
$resp = $conexion->query("select fresp from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=11") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fresp'];

 } ?>, <?php 
$resp = $conexion->query("select fresp from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=12") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fresp'];

 } ?>,<?php 
$resp = $conexion->query("select fresp from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=13") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fresp'];

 } ?>, <?php 
$resp = $conexion->query("select fresp from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=14") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fresp'];

 } ?>, <?php 
$resp = $conexion->query("select fresp from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=15") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fresp'];

 } ?>, <?php 
$resp = $conexion->query("select fresp from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=16") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fresp'];

 } ?>, <?php 
$resp = $conexion->query("select fresp from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=17") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fresp'];

 } ?>,<?php 
$resp = $conexion->query("select fresp from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=18") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fresp'];

 } ?>, <?php 
$resp = $conexion->query("select fresp from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=19") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fresp'];

 } ?>, <?php 
$resp = $conexion->query("select fresp from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=20") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fresp'];

 } ?>, <?php 
$resp = $conexion->query("select fresp from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=21") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fresp'];

 } ?>, <?php 
$resp = $conexion->query("select fresp from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=22") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fresp'];

 } ?>,<?php 
$resp = $conexion->query("select fresp from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=23") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fresp'];

 } ?>, <?php 
$resp = $conexion->query("select fresp from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=24") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fresp'];

 } ?>, <?php 
$resp = $conexion->query("select fresp from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=1") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fresp'];

 } ?>, <?php 
$resp = $conexion->query("select fresp from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=2") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fresp'];

 } ?>, <?php 
$resp = $conexion->query("select fresp from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=3") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fresp'];

 } ?>,<?php 
$resp = $conexion->query("select fresp from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=4") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fresp'];

 } ?>, <?php 
$resp = $conexion->query("select fresp from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=5") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fresp'];

 } ?>, <?php 
$resp = $conexion->query("select fresp from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=6") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fresp'];

 } ?>, <?php 
$resp = $conexion->query("select fresp from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=7") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fresp'];

 } ?>],// La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
    backgroundColor: 'rgba(25, 159, 64, 0.2)',// Color de fondo
    borderColor: 'rgba(25, 159, 64, 1)',// Color del borde
    borderWidth: 1,// Ancho del borde
};

const temp = {
    label: "TEMPERATURA",
    data: [<?php 
$resp = $conexion->query("select temper from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=8") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['temper'];

 } ?>, <?php 
$resp = $conexion->query("select temper from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=9") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['temper'];

 } ?>, <?php 
$resp = $conexion->query("select temper from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=10") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['temper'];

 } ?>, <?php 
$resp = $conexion->query("select temper from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=11") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['temper'];

 } ?>, <?php 
$resp = $conexion->query("select temper from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=12") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['temper'];

 } ?>,<?php 
$resp = $conexion->query("select temper from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=13") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['temper'];

 } ?>, <?php 
$resp = $conexion->query("select temper from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=14") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['temper'];

 } ?>, <?php 
$resp = $conexion->query("select temper from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=15") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['temper'];

 } ?>, <?php 
$resp = $conexion->query("select temper from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=16") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['temper'];

 } ?>, <?php 
$resp = $conexion->query("select temper from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=17") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['temper'];

 } ?>,<?php 
$resp = $conexion->query("select temper from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=18") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['temper'];

 } ?>, <?php 
$resp = $conexion->query("select temper from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=19") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['temper'];

 } ?>, <?php 
$resp = $conexion->query("select temper from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=20") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['temper'];

 } ?>, <?php 
$resp = $conexion->query("select temper from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=21") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['temper'];

 } ?>, <?php 
$resp = $conexion->query("select temper from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=22") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['temper'];

 } ?>,<?php 
$resp = $conexion->query("select temper from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=23") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['temper'];

 } ?>, <?php 
$resp = $conexion->query("select temper from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=24") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['temper'];

 } ?>, <?php 
$resp = $conexion->query("select temper from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=1") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['temper'];

 } ?>, <?php 
$resp = $conexion->query("select temper from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=2") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['temper'];

 } ?>, <?php 
$resp = $conexion->query("select temper from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=3") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['temper'];

 } ?>,<?php 
$resp = $conexion->query("select temper from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=4") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['temper'];

 } ?>, <?php 
$resp = $conexion->query("select temper from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=5") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['temper'];

 } ?>, <?php 
$resp = $conexion->query("select temper from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=6") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['temper'];

 } ?>, <?php 
$resp = $conexion->query("select temper from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=7") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['temper'];

 } ?>],// La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
    backgroundColor: 'rgba(255, 5, 164, 0.2)',// Color de fondo
    borderColor: 'rgba(255, 5, 164, 1)',// Color del borde
    borderWidth: 1,// Ancho del borde
};

const sat = {
    label: "SATURACIÓN OXÍGENO",
    data: [<?php 
$resp = $conexion->query("select satoxi from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=8") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['satoxi'];

 } ?>, <?php 
$resp = $conexion->query("select satoxi from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=9") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['satoxi'];

 } ?>, <?php 
$resp = $conexion->query("select satoxi from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=10") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['satoxi'];

 } ?>, <?php 
$resp = $conexion->query("select satoxi from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=11") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['satoxi'];

 } ?>, <?php 
$resp = $conexion->query("select satoxi from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=12") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['satoxi'];

 } ?>,<?php 
$resp = $conexion->query("select satoxi from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=13") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['satoxi'];

 } ?>, <?php 
$resp = $conexion->query("select satoxi from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=14") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['satoxi'];

 } ?>, <?php 
$resp = $conexion->query("select satoxi from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=15") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['satoxi'];

 } ?>, <?php 
$resp = $conexion->query("select satoxi from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=16") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['satoxi'];

 } ?>, <?php 
$resp = $conexion->query("select satoxi from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=17") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['satoxi'];

 } ?>,<?php 
$resp = $conexion->query("select satoxi from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=18") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['satoxi'];

 } ?>, <?php 
$resp = $conexion->query("select satoxi from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=19") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['satoxi'];

 } ?>, <?php 
$resp = $conexion->query("select satoxi from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=20") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['satoxi'];

 } ?>, <?php 
$resp = $conexion->query("select satoxi from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=21") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['satoxi'];

 } ?>, <?php 
$resp = $conexion->query("select satoxi from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=22") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['satoxi'];

 } ?>,<?php 
$resp = $conexion->query("select satoxi from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=23") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['satoxi'];

 } ?>, <?php 
$resp = $conexion->query("select satoxi from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=24") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['satoxi'];

 } ?>, <?php 
$resp = $conexion->query("select satoxi from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=1") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['satoxi'];

 } ?>, <?php 
$resp = $conexion->query("select satoxi from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=2") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['satoxi'];

 } ?>, <?php 
$resp = $conexion->query("select satoxi from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=3") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['satoxi'];

 } ?>,<?php 
$resp = $conexion->query("select satoxi from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=4") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['satoxi'];

 } ?>, <?php 
$resp = $conexion->query("select satoxi from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=5") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['satoxi'];

 } ?>, <?php 
$resp = $conexion->query("select satoxi from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=6") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['satoxi'];

 } ?>, <?php 
$resp = $conexion->query("select satoxi from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=7") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['satoxi'];

 } ?>],// La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
    backgroundColor: 'rgba(255, 5, 4, 0.2)',// Color de fondo
    borderColor: 'rgba(255, 5, 4, 1)',// Color del borde
    borderWidth: 1,// Ancho del borde
};
const frecf = {
    label: "FRECUENCIA CARDIACA FETAL",
   data: [<?php 
$resp = $conexion->query("select fcardf from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=8") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcardf'];

 } ?>, <?php 
$resp = $conexion->query("select fcardf from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=9") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcardf'];

 } ?>, <?php 
$resp = $conexion->query("select fcardf from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=10") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcardf'];

 } ?>, <?php 
$resp = $conexion->query("select fcardf from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=11") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcardf'];

 } ?>, <?php 
$resp = $conexion->query("select fcardf from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=12") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcardf'];

 } ?>,<?php 
$resp = $conexion->query("select fcardf from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=13") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcardf'];

 } ?>, <?php 
$resp = $conexion->query("select fcardf from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=14") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcardf'];

 } ?>, <?php 
$resp = $conexion->query("select fcardf from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=15") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcardf'];

 } ?>, <?php 
$resp = $conexion->query("select fcardf from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=16") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcardf'];

 } ?>, <?php 
$resp = $conexion->query("select fcardf from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=17") or die($conexion->error); 
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcardf'];

 } ?>,<?php 
$resp = $conexion->query("select fcardf from signos_vitales where id_atencion=$id_atencion and hora=18") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcardf'];

 } ?>, <?php 
$resp = $conexion->query("select fcardf from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=19") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcardf'];

 } ?>, <?php 
$resp = $conexion->query("select fcardf from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=20") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcardf'];

 } ?>, <?php 
$resp = $conexion->query("select fcardf from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=21") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcardf'];

 } ?>, <?php 
$resp = $conexion->query("select fcardf from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=22") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcardf'];

 } ?>,<?php 
$resp = $conexion->query("select fcardf from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=23") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcardf'];

 } ?>, <?php 
$resp = $conexion->query("select fcardf from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=24") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcardf'];

 } ?>, <?php 
$resp = $conexion->query("select fcardf from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=1") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcardf'];

 } ?>, <?php 
$resp = $conexion->query("select fcardf from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=2") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcardf'];

 } ?>, <?php 
$resp = $conexion->query("select fcardf from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=3") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcardf'];

 } ?>,<?php 
$resp = $conexion->query("select fcardf from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=4") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcardf'];

 } ?>, <?php 
$resp = $conexion->query("select fcardf from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=5") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcardf'];

 } ?>, <?php 
$resp = $conexion->query("select fcardf from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=6") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcardf'];

 } ?>, <?php 
$resp = $conexion->query("select fcardf from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=7") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['fcardf'];

 } ?>], // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
    backgroundColor: 'rgba(188, 98, 198, 0.2)',// Color de fondo
    borderColor: 'rgba(188, 98, 198, 1)',// Color del borde
    borderWidth: 1,// Ancho del borde
};
const niv = {
    label: "NIVEL DE DOLOR",
    data: [<?php 
$resp = $conexion->query("select niv_dolor from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=8") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['niv_dolor'];

 } ?>, <?php 
$resp = $conexion->query("select niv_dolor from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=9") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['niv_dolor'];

 } ?>, <?php 
$resp = $conexion->query("select niv_dolor from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=10") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['niv_dolor'];

 } ?>, <?php 
$resp = $conexion->query("select niv_dolor from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=11") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['niv_dolor'];

 } ?>, <?php 
$resp = $conexion->query("select niv_dolor from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=12") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['niv_dolor'];

 } ?>,<?php 
$resp = $conexion->query("select niv_dolor from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=13") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['niv_dolor'];

 } ?>, <?php 
$resp = $conexion->query("select niv_dolor from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=14") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['niv_dolor'];

 } ?>, <?php 
$resp = $conexion->query("select niv_dolor from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=15") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['niv_dolor'];

 } ?>, <?php 
$resp = $conexion->query("select niv_dolor from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=16") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['niv_dolor'];

 } ?>, <?php 
$resp = $conexion->query("select niv_dolor from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=17") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['niv_dolor'];

 } ?>,<?php 
$resp = $conexion->query("select niv_dolor from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=18") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['niv_dolor'];

 } ?>, <?php 
$resp = $conexion->query("select niv_dolor from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=19") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['niv_dolor'];

 } ?>, <?php 
$resp = $conexion->query("select niv_dolor from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=20") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['niv_dolor'];

 } ?>, <?php 
$resp = $conexion->query("select niv_dolor from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=21") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['niv_dolor'];

 } ?>, <?php 
$resp = $conexion->query("select niv_dolor from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=22") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['niv_dolor'];

 } ?>,<?php 
$resp = $conexion->query("select niv_dolor from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=23") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['niv_dolor'];

 } ?>, <?php 
$resp = $conexion->query("select niv_dolor from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=24") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['niv_dolor'];

 } ?>, <?php 
$resp = $conexion->query("select niv_dolor from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=1") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['niv_dolor'];

 } ?>, <?php 
$resp = $conexion->query("select niv_dolor from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=2") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['niv_dolor'];

 } ?>, <?php 
$resp = $conexion->query("select niv_dolor from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=3") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['niv_dolor'];

 } ?>,<?php 
$resp = $conexion->query("select niv_dolor from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=4") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['niv_dolor'];

 } ?>, <?php 
$resp = $conexion->query("select niv_dolor from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=5") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['niv_dolor'];

 } ?>, <?php 
$resp = $conexion->query("select niv_dolor from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=6") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['niv_dolor'];

 } ?>, <?php 
$resp = $conexion->query("select niv_dolor from signos_vitales where id_atencion=$id_atencion AND fecha='$fecha' and hora=7") or die($conexion->error);
while ($resp_r = mysqli_fetch_array($resp)) {

echo $resp_r['niv_dolor'];

 } ?>],// La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
    backgroundColor: 'rgba(155, 125, 224, 0.2)',// Color de fondo
    borderColor: 'rgba(155, 125, 224, 1)',// Color del borde
    borderWidth: 1,// Ancho del borde
};

new Chart($grafica, {
    type: 'line',// Tipo de gráfica
    data: {
        labels: etiquetas,
        datasets: [
            presion,
            frec,
            fresp,
            temp,
            sat,
            frecf,
            niv
            // Aquí más datos...
        ]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }],
        },
    }
});
</script>


  
</body>
</html>