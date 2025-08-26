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
    <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
            integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
            crossorigin="anonymous"></script>
    <!--  Bootstrap  -->
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
          integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <!---
    <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css'>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
  -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

    <script>
        // Write on keyup event of keyword input element
        $(document).ready(function () {
            $("#search").keyup(function () {
                _this = this;
                // Show only matching TR, hide rest of them
                $.each($("#mytable tbody tr"), function () {
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
        $(document).ready(function () {
            $("#search_dep").keyup(function () {
                _this = this;
                // Show only matching TR, hide rest of them
                $.each($("#mytable tbody tr"), function () {
                    if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
                        $(this).hide();
                    else
                        $(this).show();
                });
            });
        });
    </script>
    <title>DETALLE DE LA CUENTA</title>
    <style>
        hr.new4 {
            border: 1px solid red;
        }
    </style>
</head>

<body>
<section class="content container-fluid">

    <!--------------------------
    | Your Page Content Here |
    -------------------------->

    <?php

    include "../../conexionbd.php";

    if (isset($_SESSION['pac'])) {
      $id_atencion = $_SESSION['pac'];

      $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.tel, p.fecnac,p.tip_san, di.fecha, di.area, di.alta_med, p.sexo, di.alergias FROM paciente p, dat_ingreso di WHERE p.Id_exp=di.Id_exp and di.id_atencion =$id_atencion";

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
      }

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

      function calculaedad($fechanacimiento)
      {
        list($ano, $mes, $dia) = explode("-", $fechanacimiento);
        $ano_diferencia  = date("Y") - $ano;
        $mes_diferencia = date("m") - $mes;
        $dia_diferencia   = date("d") - $dia;
        if ($dia_diferencia < 0 || $mes_diferencia < 0)
          $ano_diferencia--;
        return $ano_diferencia;
      }

      $edad = calculaedad($pac_fecnac);

    ?>
      <div class="container">
        <div class="content">
          
            <div class="thead" style="background-color: #0c675e; color: white; font-size: 25px;">
                 <tr><strong><center>REGISTRO CLÍNICO DE ENFERMERÍA QUIRÚRGICO<br>INSULINOTERAPIA, GLICEMIA, CISTOCLISIS</center></strong>
        </div>
         <hr>
 <font size="2">
         <div class="container">
  <div class="row">
    <div class="col-sm">
     NOMBRE DEL PACIENTE: <strong><?php echo $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac ?></strong>
    </div>
    <div class="col-sm">
      ÁREA: <strong><?php echo $area ?></strong>
    </div>
   <?php $date = date_create($pac_fecing);
   ?>
      <div class="col-sm">
      FECHA DE INGRESO: <strong><?php echo date_format($date, "d-m-Y H:i:s") ?></strong>
    </div>
  </div>
</div></font>

 <font size="2">
     <div class="container">
  <div class="row">
    <div class="col-sm">
       <?php $date1 = date_create($pac_fecnac);
   ?>
      FECHA DE NACIMIENTO: <strong><?php echo date_format($date, "d-m-Y") ?></strong>
    </div>
    <div class="col-sm">
      GRUPO Y RH: <strong><?php echo $pac_tip_sang ?></strong>
    </div>
   
      <div class="col-sm">
      HABITACIÓN: <strong><?php $sql_hab = "SELECT num_cama from cat_camas where id_atencion =$id_atencion";
$result_hab = $conexion->query($sql_hab);                                                                                    while ($row_hab = $result_hab->fetch_assoc()) {
  echo $row_hab['num_cama'];
} ?></strong>
    </div>
    <div class="col-sm">
      TIEMPO ESTANCIA: <strong><?php echo $estancia ?> Dias</strong>
    </div>
  </div>
</div>
</font>
 <font size="2">
  <div class="container">
  <div class="row">
    <div class="col-sm-3">
      EDAD: <strong><?php echo $edad ?></strong>
    </div>
    <div class="col-sm-3">

      PESO: <strong><?php $sql_vit = "SELECT peso from signos_vitales where id_atencion=$id_atencion ORDER by peso ASC LIMIT 1";
$result_vit = $conexion->query($sql_vit);                                                                                    while ($row_vit = $result_vit->fetch_assoc()) {
  echo $row_vit['peso'];
} ?></strong>
    </div>
  
      <div class="col-sm">
      TALLA: <strong><?php $sql_vitt = "SELECT talla from signos_vitales where id_atencion=$id_atencion ORDER by talla ASC LIMIT 1";
$result_vitt = $conexion->query($sql_vitt);                                                                                    while ($row_vitt = $result_vitt->fetch_assoc()) {
  echo $row_vitt['talla'];
} ?></strong>
    </div>

     <div class="col-sm-4">
      DIAGNÓSTICO MÉDICO: <strong><?php $sql_mot = "SELECT motivo_atn from dat_ingreso where id_atencion=$id_atencion ORDER by motivo_atn ASC LIMIT 1";
$result_mot = $conexion->query($sql_mot);                                                                                    while ($row_mot = $result_mot->fetch_assoc()) {
  echo $row_mot['motivo_atn'];
} ?></strong>
    </div>
  </div>
</div>
</font>
 <font size="2">
  <div class="container">
  <div class="row">
    <div class="col-sm">
      GÉNERO: <strong><?php echo $pac_sexo ?></strong>
    </div>
    <div class="col-sm">
      EDO DE CONCIENCIA: <strong><?php $sql_edo = "SELECT edo_salud from dat_ingreso where id_atencion=$id_atencion ORDER by edo_salud ASC LIMIT 1";
$result_edo = $conexion->query($sql_edo);                                                                                    while ($row_edo = $result_edo->fetch_assoc()) {
  echo $row_edo['edo_salud'];
} ?></strong>
    </div>
      <div class="col-sm">
      EXPEDIENTE: <strong><?php echo $id_exp?> </strong>
    </div>
     <div class="col-sm">
    SEGURO: <strong><?php $sql_aseg = "SELECT aseg from dat_financieros where id_atencion =$id_atencion ORDER BY fecha DESC limit 1";
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
  <div class="row">
    <div class="col-sm">
      ALERGIAS: <strong><?php echo $alergias ?></strong>
    </div>
   
  </div>
</div></font>
<hr>
<div class="container">
    <a href="vista_enf_quirurgico.php" class="btn btn-outline-success">NOTA</a>
    <a href="nav_med.php" class="btn btn-outline-success">MEDICAMENTOS Y EQUIPOS</a>
    <a href="nav_disp.php" class="btn btn-outline-success">DISPOSITIVOS INVASIVOS, INGRESOS</a>
    <a href="nav_ins.php" class="btn btn-outline-success" >INSULINOTERAPIA, GLICEMIA, CISTOCLISIS</a>
  
</div><br>

<!--INICIO EGRESOS-->
    <!-- inicio glicemia -->
<hr>
<form action="" method="POST">
   <div class="row">
          <table class="table table-bordered table-striped" id="mytable">
       <thead class="thead">
         <tr class="table-warning">
            <th colspan="5"><center><h5><strong>GLICEMIA CAP.Mg/dl</strong></h5></center></th>
         </tr>
    <tr class="table-active">
      <th scope="col"><center>Mg/Dl</center></th>
      <th scope="col"><center>HORA</center></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      
      <td><input type="text" name="mg" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></td>
      <td><input type="time" name="hora" class="form-control"></td>
      <td><input type="submit" name="btnglic" class="btn btn-block btn-success" value="AGREGAR"></td>
    </tr>
  </tbody>
</table>
     </div><hr>
</form>
<!-- termino glicemia -->
<!-- inicio insulino terapia-->
<form action="" method="POST">
  <hr>
     <div class="row">
         <table class="table table-bordered table-striped" id="mytable">
       <thead class="thead">
         <tr class="table-warning">
            <th colspan="5"><center><h5><strong>INSULINOTERAPIA</strong></h5></center></th>
         </tr>
    <tr class="table-active">
      <th scope="col"><center>DOSIS</center></th>
      <th scope="col"><center>VIA</center></th>
      <th scope="col"><center>HORA</center></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><input type="text" name="dosis" class="form-control col-sm-12" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></td>
      <td><input type="text" name="via" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></td>
      <td><input type="time" name="hora" class="form-control"></td>
      <td><input type="submit" name="btnins" class="btn btn-block btn-success" value="AGREGAR"></td>
    </tr>
  </tbody>
</table>
     </div>
</form>
<!--termino de insulinoterapia-->
   <hr> 
  <!--inicio de cistoclisis--> 
<form action="" method="POST">
   <div class="row">
        <table class="table table-bordered table-striped" id="mytable">
       <thead class="thead">
         <tr class="table-warning">
            <th colspan="5"><center><h5><strong>CISTOCLISIS CON SOLUCIÓN FISIÓLOGICA 1000ML</strong></h5></center></th>
         </tr>
    <tr class="table-active">
      <th scope="col"><center>INGRESOS</center></th>
      <th scope="col"><center>EGRESOS</center></th>
      <th scope="col"><center>CARACTERÍSTICAS</center></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><input type="text" name="ing" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></td>
      <td><input type="text" name="eg" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></td>
      <td><input type="text" name="car" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></td>
      <td><input type="submit" name="btncis" class="btn btn-block btn-success" value="AGREGAR"></td>
    </tr>
  </tbody>
</table> 
     </div><hr>
</form>
<!--termino de cistoclisis-->
<!--TERMINO EGRESOS-->
<?php
if(isset($_POST['btnglic'])){
include "../../conexionbd.php";
            $usuario = $_SESSION['login'];
            $id_usua= $usuario['id_usua'];
            $id_atencion = $_SESSION['pac'];
            $mg =  mysqli_real_escape_string($conexion, (strip_tags($_POST["mg"], ENT_QUOTES)));
            $hora =  mysqli_real_escape_string($conexion, (strip_tags($_POST["hora"], ENT_QUOTES)));
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("d-m-Y H:i:s");
            $ingresar2 = mysqli_query($conexion, 'INSERT INTO glic_enf_quir (id_atencion,id_usua,mg,hora,fecha) values (' . $id_atencion . ',' . $id_usua .',"' . $mg . '","' . $hora . '","'.$fecha_actual.'") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

           echo '<script type="text/javascript">window.location.href = "nav_ins.php";</script>';
}

if(isset($_POST['btnins'])){
   include "../../conexionbd.php";
            $usuario = $_SESSION['login'];
            $id_usua= $usuario['id_usua'];
            $id_atencion = $_SESSION['pac'];
            $dosis =  mysqli_real_escape_string($conexion, (strip_tags($_POST["dosis"], ENT_QUOTES)));
            $via =  mysqli_real_escape_string($conexion, (strip_tags($_POST["via"], ENT_QUOTES)));
            $hora =  mysqli_real_escape_string($conexion, (strip_tags($_POST["hora"], ENT_QUOTES)));
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");
            $ingresar2 = mysqli_query($conexion, 'INSERT INTO ins_enf_quir (id_atencion,id_usua,dosis,via,hora,fecha) values (' . $id_atencion . ',' . $id_usua .',"' . $dosis . '","' . $via . '","' . $hora . '","'.$fecha_actual.'") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

           echo '<script type="text/javascript">window.location.href = "nav_ins.php";</script>'; 
}

if(isset($_POST['btncis'])){
    include "../../conexionbd.php";
            $usuario = $_SESSION['login'];
            $id_usua= $usuario['id_usua'];
            $id_atencion = $_SESSION['pac'];
            $ing =  mysqli_real_escape_string($conexion, (strip_tags($_POST["ing"], ENT_QUOTES)));
            $eg =  mysqli_real_escape_string($conexion, (strip_tags($_POST["eg"], ENT_QUOTES)));
            $car =  mysqli_real_escape_string($conexion, (strip_tags($_POST["car"], ENT_QUOTES)));
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");
$ingresar2 = mysqli_query($conexion, 'INSERT INTO cis_enf_quir (id_atencion,id_usua,ing,eg,caract,fecha) values (' . $id_atencion . ',' . $id_usua .',"' . $ing . '","' . $eg . '","' . $car . '","'.$fecha_actual.'") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

           echo '<script type="text/javascript">window.location.href = "nav_ins.php";</script>'; 
}
?>
            <?php
            } else {
                echo '<script type="text/javascript"> window.location.href="../../template/select_pac_enf.php";</script>';
            }
            ?>
        </div>
    </div>
</section>
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