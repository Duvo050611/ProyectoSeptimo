<?php
session_start();
//include "../../conexionbd.php";
include "../header_enfermera.php";
$resultado = $conexion->query("select * from reg_usuarios") or die($conexion->error);
$usuario = $_SESSION['login'];
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
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
  <!--
  <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
  <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css'>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

  <script>
    // Write on keyup event of keyword input element
    $(document).ready(function() {
      $("#search").keyup(function() {
        _this = this;
        // Show only matching TR, hide rest of them
        $.each($("#mytable3 tbody tr"), function() {
          if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
            $(this).hide();
          else
            $(this).show();
        });
      });
    });
  </script>

  <title>REGISTRO PARA DIÁLISIS PERITONEAL - ENFERMERÍA</title>
</head>
<body>

    <?php

    include "../../conexionbd.php";

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

     ///inicio bisiesto
function bisiesto($anio_actual){
    $bisiesto=false;
    //probamos si el mes de febrero del año actual tiene 29 días
      if (checkdate(2,29,$anio_actual))
      {
        $bisiesto=true;
    }
    return $bisiesto;
}

//date_default_timezone_set('America/Guatemala');
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
      
        <div class="content">
          
        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 25px;">
                 <tr><strong><center>REGISTRO PARA DIÁLISIS PERITONEAL - ENFERMERÍA</center></strong>
        </div>
         <hr>
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
  </font>
<hr>


<!--dialisis -->


  <form action="" method="POST">
<div class="table-responsive">
<table class="table table-bordered">
  <thead>
    
    <tr class="table-active">
      <th scope="col" colspan="1"><center>No. de baño</center></th>
      <th scope="col" colspan="1" >Tipo de solución</th>
      <th scope="col" colspan="9">Hrs de entrada</th>
       <th scope="col" colspan="2">Hrs de salida</th>
      <th scope="col" colspan="1">Cantidad de entrada</th>
      <th scope="col" colspan="1">Cantidad de salida</th>
      <!--<th scope="col" colspan="1">Balance parcial</th>-->
      <th scope="col" colspan="1">Balance total</th>
      <th scope="col" colspan="1">Observaciones</th>
      <!--<th scope="col" colspan="1">Enfermera</th>-->
    </tr>

  </thead>
  <tbody>
    <tr>
    
<td><input type="text" name="baño" class="form-control"></td>
<td colspan="1"><input type="text" name="tiposol" class="form-control"></td>
<td colspan="2"><input type="time" name="hrentrada" class="form-control"></td>
<td colspan="9"><input type="time" name="hrsalida" class="form-control"></td>
<td colspan="1"><input type="number" name="centrada" class="form-control" required></td>
<td colspan="1"><input type="number" name="csalida" class="form-control" required></td>
<!--<td colspan="1"><input type="text" name="balparcial" class="form-control"></td>-->
<td colspan="1"><input type="text" name="baltot" class="form-control"></td>
<td colspan="1"><textarea name="obs" class="form-control"></textarea></td>
<!--<td colspan="1"><input type="text" name="fecha_cambio" class="form-control"></td>-->
</tr>
</tbody>
</table>
</div>

<center><input type="submit" name="btndial" class="btn btn-block btn-success col-3" value="Agregar"></center>
</form >
<?php
if(isset($_POST['btndial'])){
     include "../../conexionbd.php";
$usuario = $_SESSION['login'];
$id_usua= $usuario['id_usua'];
$id_atencion = $_SESSION['pac'];

$baño =mysqli_real_escape_string($conexion, (strip_tags($_POST["baño"], ENT_QUOTES)));
$tiposol =mysqli_real_escape_string($conexion, (strip_tags($_POST["tiposol"], ENT_QUOTES)));
$hrentrada =mysqli_real_escape_string($conexion, (strip_tags($_POST["hrentrada"], ENT_QUOTES)));
$hrsalida =mysqli_real_escape_string($conexion, (strip_tags($_POST["hrsalida"], ENT_QUOTES)));
$centrada =mysqli_real_escape_string($conexion, (strip_tags($_POST["centrada"], ENT_QUOTES)));
$csalida =mysqli_real_escape_string($conexion, (strip_tags($_POST["csalida"], ENT_QUOTES)));
//$balparcial =mysqli_real_escape_string($conexion, (strip_tags($_POST["balparcial"], ENT_QUOTES)));
$baltot =mysqli_real_escape_string($conexion, (strip_tags($_POST["baltot"], ENT_QUOTES)));
$obs =mysqli_real_escape_string($conexion, (strip_tags($_POST["obs"], ENT_QUOTES)));

$balparcial=$csalida-$centrada;


//date_default_timezone_set('America/Guatemala');
$fech = date("Y-m-d");

$ingresar4 = mysqli_query($conexion, 'INSERT INTO dialisis_p (id_atencion,id_usua,fecha_registro,baño,tiposol,hrentrada,hrsalida,centrada,csalida,balparcial,baltot,obs) values ('.$id_atencion.',' . $id_usua . ',"'.$fech.'","' . $baño .'","' . $tiposol . '","'.$hrentrada.'","'.$hrsalida.'","'.$centrada.'","'.$csalida.'","'.$balparcial.'","'.$baltot.'","'.$obs.'") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

      echo '<script type="text/javascript">window.location.href = "nota_dialisis.php";</script>';
    }
    ?>

 <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:19%" id="search" placeholder="BUSCAR...">
            </div>
 <?php

include "../../conexionbd.php";
$id_atencion=$_SESSION['pac'];
$resultado = $conexion->query("SELECT * from dialisis_p WHERE id_atencion=$id_atencion ORDER BY id_dialisis asc") or die($conexion->error);
$usuario = $_SESSION['login'];

?>

<table class="table table-bordered table-striped" id="mytable3">
       <thead class="thead">
         <tr class="table-primary">
  <th colspan="13"><center><h5><strong>REGISTROS PARA DIÁLISIS PERITONEAL</strong></h5></center></th>
         </tr>
    <tr class="table-success">
      <th scope="col"><center>No. de baño</center></th>
      <th scope="col">Tipo de solución</th>
      <th scope="col">Hrs de entrada</th>
       <th scope="col">Hrs de salida</th>
      <th scope="col">Cantidad de entrada</th>
      <th scope="col">Cantidad de salida</th>
      <th scope="col">Balance parcial</th>
       <th scope="col"><center>Balance total</center></th>
       <th scope="col"><center>Observaciones</center></th>
        <th scope="col"><center>Enfermera</center></th>
          <th scope="col"><center>Fecha</center></th>
       <th scope="col"><center>Editar</center></th>
       <th scope="col"><center>Eliminar</center></th>
    </tr>
  </thead>
  <tbody>
      <?php
                while($f = mysqli_fetch_array($resultado)){
$id_usua=$f["id_usua"];
$resultado_usua = $conexion->query("SELECT * FROM reg_usuarios WHERE id_usua=$id_usua") or die($conexion->error);

        while($row = mysqli_fetch_array($resultado_usua)){
                    ?>
<tr>
<td><center><strong> <?php echo $f['baño'];?> </strong></center></td>
<td><center><strong> <?php echo $f['tiposol'];?> </strong></center></td>
<td><center><strong> <?php echo $f['hrentrada']?></strong></center></td>
<td><center><strong> <?php echo $f['hrsalida']?></strong></center></td>
<td><center><strong> <?php echo $f['centrada'];?> </strong></center></td>
<td><center><strong> <?php echo $f['csalida'];?> </strong></center></td>
<td><center><strong> <?php echo $f['balparcial'];?> </strong></center></td>
<td><center><strong> <?php echo $f['baltot'];?> </strong></center></td>
<td><center><strong> <?php echo $f['obs'];?> </strong></center></td>
<td><strong><?php echo $row['nombre'].' '.$row['papell'].' '.$row['sapell']?></strong></td>
<td><center><strong> <?php $fech_c=date_create($f['fecha_registro']); echo date_format($fech_c,"d-m-Y");?></strong></center></td>




<td><a href="edit_dialisis.php?id_dialisis=<?php echo $f['id_dialisis'];?>&id_atencion=<?php echo $f['id_atencion'];?>"><button type="button" class="btn btn-warning"><i class="fas fa-edit"></i></button></td>

<td><center><a href="el_dialisis.php?id_dialisis=<?php echo $f['id_dialisis'];?>&id_atencion=<?php echo $f['id_atencion'];?>"><button type="button" class="btn btn-danger"> X </button></center></td>
 
    </tr>
       <?php
                }
                 }
                ?>
  </tbody>
</table>

</div>
<!-- fin dialisis-->
        <?php
      } else {
        echo '<script type="text/javascript"> window.location.href="../../template/select_pac_enf.php";</script>';
      }
        ?>
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
</body>
</html>