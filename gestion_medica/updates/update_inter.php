<?php
session_start();
include "../../conexionbd.php";
include("../header_medico.php");
?>

<!DOCTYPE html>
<div>
    <head>
        <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″/>
             <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
              integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk"
              crossorigin="anonymous">
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
              integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ"
              crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <script src="jquery-3.1.1.min.js"></script>
    <script src="js/select2.js"></script>



   
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


        <title>Historia Clinica </title>
        <!--<style type="text/css">
    #contenido{
        display: none;
    }
</style>-->
    </head>


    <div class="col-sm">
        <form action=""  method="POST">
            <div class="container">
                <div class="row">
                    <div class="col">
                        
<div class="thead" style="background-color: #0c675e; color: white; font-size: 24px;">
  <center><strong>NOTA DE INTERCONSULTA</strong></center><p>
</div>
                        <hr>
                    </div>


                    <div class="col-3">
                        <?php
                        date_default_timezone_set('America/Guatemala');
                        $fecha_actual = date("Y-m-d H:i:s");
                        ?>
                        
                        <div class="form-group">
                            <label for="fecha">FECHA Y HORA ACTUAL:</label>
                            <input type="datetime" name="fec_hc" value="<?= $fecha_actual ?>" class="form-control"
                                   disabled><hr>
                        </div>
                    </div>
                </div>


                

                    <?php

    include "../../conexionbd.php";

    if (isset($_SESSION['hospital'])) {
      $id_atencion = $_SESSION['hospital'];

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
      F. DE NACIMIENTO: <strong><?php echo date_format($date, "d-m-Y") ?></strong>
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
$result_edo = $conexion->query($sql_edo);while ($row_edo = $result_edo->fetch_assoc()) {
  echo $row_edo['edo_salud'];
} ?></strong>
    </div>
      <div class="col-sm">
      NO. EXPEDIENTE: <strong><?php echo $id_exp?> </strong>
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

</div>
        <?php
      } else {
        echo '<script type="text/javascript"> window.location.href="../lista_pacientes/lista_pacientes.php";</script>';
      }
        ?><hr>
 <?php 

include "../../conexionbd.php";
$resultado5=$conexion->query("select * from signos_vitales WHERE id_atencion=" . $_SESSION ['hospital'].".ORDER by id_sig DESC LIMIT 1") or die($conexion->error);

     while ($f5 = mysqli_fetch_array($resultado5)) {
      $atencion=$f5['id_sig'];
    }
    ?>
    <?php
if (isset($atencion)) {
                        ?>
<?php 

include "../../conexionbd.php";
$resultado5=$conexion->query("select * from signos_vitales WHERE id_atencion=" . $_SESSION ['hospital'].".ORDER by id_sig DESC LIMIT 1") or die($conexion->error);

     while ($f5 = mysqli_fetch_array($resultado5)) {
     
    
    ?>
    <div class="container">
   <div class="thead" style="background-color: #0c675e; color: white; font-size: 19px;">
  <center><strong>SIGNOS VITALES</strong></center><p>
</div>
</div>
                <div class="container"> 
  <div class="row">
    
      
    
    <div class="col-sm-2"><br><center>PRESIÓN ARTERIAL:</center>
     <div class="row">
  <div class="col"><input type="text" class="form-control" value="<?php echo $f5['p_sistol'];?>" disabled></div> /
  <div class="col"><input type="text" class="form-control" value="<?php echo $f5['p_diastol'];?>" disabled></div>
 
</div>
    </div>
    <div class="col-sm-2">
      FRECUENCIA CARDIACA:<input type="text" class="form-control" value="<?php echo $f5['fcard'];?>" disabled>
    </div>
    <div class="col-sm-2">
      FRECUENCIA RESPIRATORIA:<input type="text" class="form-control" value="<?php echo $f5['fresp'];?>" disabled>
    </div>
    <div class="col-sm-2">
     <br>TEMPERATURA:<input type="text" class="form-control" value="<?php echo $f5['temper'];?>" disabled>
    </div>
    <div class="col-sm-2">
     SATURACIÓN OXÍGENO:<input type="text"  class="form-control" value="<?php echo $f5['satoxi'];?>" disabled>
    </div>
    <div class="col-sm-1">
     <br>PESO:<input type="text" class="form-control" value="<?php echo $f5['peso'];?>" disabled>
    </div>
    <div class="col-sm-1">
     <br>TALLA:<input type="text" class="form-control" value="<?php echo $f5['talla'];?>" disabled>
    </div>
  </div>
</div>
<?php }
?>
<?php 
}else{
                        
  ?>
  <div class="container">
    <div class="thead" style="background-color: #0c675e; color: white; font-size: 19px;">
  <center><strong>SIGNOS VITALES</strong></center><p>
</div>
</div>
                <div class="container"> 
  <div class="row">
    <div class="col-sm-2"><br><center>PRESIÓN ARTERIAL:</center>
     <div class="row">
  <div class="col"><input type="text" class="form-control" name="p_sistol" ></div> /
  <div class="col"><input type="text" class="form-control" name="p_diastol"></div>
 
</div>
    </div>
    <div class="col-sm-2">
      FRECUENCIA CARDIACA:<input type="text" class="form-control" name="fcard">
    </div>
    <div class="col-sm-2">
      FRECUENCIA RESPIRATORIA:<input type="text" class="form-control" name="fresp">
    </div>
    <div class="col-sm-2">
     <br>TEMPERATURA:<input type="text" class="form-control"  name="temper">
    </div>
    <div class="col-sm-2">
     SATURACIÓN OXÍGENO:<input type="text"  class="form-control" name="satoxi">
    </div>
    <div class="col-sm-1">
     <br>PESO:<input type="text" class="form-control"  name="peso">
    </div>
    <div class="col-sm-1">
     <br>TALLA:<input type="text" class="form-control" name="talla" >
    </div>
  </div>
</div>

<?php } ?>
 <br>
 <hr>
 <?php 
$id_inter=$_GET['id_inter'];
$inter="SELECT * FROM dat_not_inter where id_inter=$id_inter";
$result=$conexion->query($inter);
while ($row=$result->fetch_assoc()) {
    $id_usua=$row['id_usua'];
 ?>
 <div class="container">
 <div class="row">
    <div class=" col-12">
        <strong>MOTIVO DE LA CONSULTA:</strong>
     <div class="form-group">
        <textarea name="motinter" class="form-control" rows="5"><?php echo  $row['motinter']?></textarea>
  </div>
    </div>
</div>
</div>
 <div class="container">
 <div class="row">
    <div class=" col-12">
           <strong>RESUMEN DEL INTERROGATORIO, EXPLORACIÓN FÍSICA Y ESTADO MENTAL:</strong>
     <div class="form-group">
        <textarea name="riefeinter" class="form-control" rows="5"><?php echo  $row['riefeinter']?></textarea>
  </div>
    </div>
</div>
</div>
<div class="container">
<div class="row">
    <div class=" col-12">
        <strong>RESULTADOS RELEVANTES DE LOS ESTUDIOS DE LOS SERVICIOS AUXILIARES DE DIAGNÓSTICO Y TRATAMIENTO SOLICITADO PREVIAMENTE:</strong>
     <div class="form-group">
        <textarea name="revlinter" class="form-control" rows="5"><?php echo  $row['revlinter']?></textarea>
  </div>
    </div>
</div>
</div>
<div class="container">
<div class="row">
    <div class=" col-12"><strong>DIAGNÓSTICOS O PROBLEMAS CLÍNICOS:</strong>
     <div class="form-group">
        <textarea name="probinter" class="form-control" rows="5"><?php echo  $row['probinter']?></textarea>
  </div>
    </div>
</div>
</div>
<div class="container">
<div class="row">
    <div class=" col-12">
        <strong>TRATAMIENTO:</strong>
     <div class="form-group">
        <textarea name="tratprocinter" class="form-control" rows="5"><?php echo  $row['tratprocinter']?></textarea>
  </div>
    </div>
</div>
</div>
<div class="container">
<div class="row">
    <div class=" col-12">
        <strong>PRONÓSTICO:</strong>
     <div class="form-group">
        <textarea name="procinter" class="form-control" rows="5"><?php echo  $row['procinter']?></textarea>
  </div>
    </div>
</div>
</div>
<div class="container">
<div class="row">
    <div class=" col-12"> <strong>CRITERIOS DIAGNÓSTICOS:</strong>
     <div class="form-group">
        <textarea name="criteinter" class="form-control" rows="5"><?php echo  $row['criteinter']?></textarea>
  </div>
    </div>
</div>
</div>
<div class="container">
<div class="row">
    <div class=" col-12"> <strong>PLAN DE ESTUDIOS:</strong>
     <div class="form-group">
        <textarea name="planinter" class="form-control" rows="5"><?php echo  $row['planinter']?></textarea>
  </div>
    </div>
</div>
</div>

<div class="container">
<div class="row">
    <div class=" col-12"> <strong>SUGERENCIAS DIAGNÓSTICAS Y TRATAMIENTO:</strong>
     <div class="form-group">
        <textarea name="sugdiaginter" class="form-control" rows="5"><?php echo  $row['sugdiaginter']?></textarea>
  </div>
    </div>
</div>
</div>
<hr>
<?php 
$date=date_create($row['fecha_inter']);
?>
      <div class="row">
          <div class="col-sm-4">
              <div class="form-group">
                 <strong> <label>FECHA REGISTRO : </label></strong><br>
                 <input type="date" name="fecha" value="<?php echo date_format($date,"Y-m-d") ?>" class="form-control">
             </div>
           </div>
           <div class="col-sm-4">
              <div class="form-group">
                 <strong> <label>HORA REGISTRO :</label></strong><br>
                 <input type="time" name="hora" value="<?php echo date_format($date,"H:i:s") ?>" class="form-control">
             </div>
           </div>
       <?php 
   }
$select="SELECT * FROM reg_usuarios where id_usua=$id_usua";
$resultado=$conexion->query($select);
while ($row_doc=$resultado->fetch_assoc()) {
    $doctor=$row_doc['nombre'].' '.$row_doc['papell'].' '.$row_doc['sapell'];
    $id_doc=$row_doc['id_usua'];
}

        ?>
          <div class="col-sm-4">
              <div class="form-group">
                 <strong> <label>MÉDICO QUE REGISTRÓ: </label></strong><br>
                 <select name="medico" class="form-control" >
                     <option value="<?php echo $id_doc ?>"><?php echo $doctor ?></option>
                     <option></option>
                     <option value=" ">SELECCIONAR MEDICO :</option>
                     <?php 
                      $select="SELECT * FROM reg_usuarios where id_rol=2 || id_rol=12";
                      $resultado=$conexion->query($select);
                      foreach ($resultado as $row ) {
                      ?>
                      <option value="<?php echo $row['id_usua'] ?>"><?php echo $row['nombre'].' '.$row['papell'].' '.$row['sapell']; ?></option>
                  <?php } ?>
                 </select>
             </div>
           </div>
       </div>
                     <center><button type="submit" name="guardar" class="btn btn-primary"><font size="3">FIRMAR</font></button>
                            <button type="button" class="btn btn-danger" onclick="history.back()">CANCELAR</button></center><br>
                </div>
           
        </form>
    </div>

</div>
 <?php 
  if (isset($_POST['guardar'])) {

        $motinter    = mysqli_real_escape_string($conexion, (strip_tags($_POST["motinter"], ENT_QUOTES))); //Escanpando caracteres
        $riefeinter    = mysqli_real_escape_string($conexion, (strip_tags($_POST["riefeinter"], ENT_QUOTES))); //Escanpando caracteres
        $revlinter   = mysqli_real_escape_string($conexion, (strip_tags($_POST["revlinter"], ENT_QUOTES)));
        $probinter    = mysqli_real_escape_string($conexion, (strip_tags($_POST["probinter"], ENT_QUOTES))); //Escanpando caracteres
        $tratprocinter    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tratprocinter"], ENT_QUOTES)));
        $procinter    = mysqli_real_escape_string($conexion, (strip_tags($_POST["procinter"], ENT_QUOTES)));
        $criteinter    = mysqli_real_escape_string($conexion, (strip_tags($_POST["criteinter"], ENT_QUOTES)));
        $planinter    = mysqli_real_escape_string($conexion, (strip_tags($_POST["planinter"], ENT_QUOTES)));
        $sugdiaginter    = mysqli_real_escape_string($conexion, (strip_tags($_POST["sugdiaginter"], ENT_QUOTES)));
        $fecha= mysqli_real_escape_string($conexion,(strip_tags($_POST["fecha"], ENT_QUOTES)));
        $hora= mysqli_real_escape_string($conexion,(strip_tags($_POST["hora"], ENT_QUOTES)));
        $medico= mysqli_real_escape_string($conexion,(strip_tags($_POST["medico"], ENT_QUOTES)));

        $merge = $fecha.' '.$hora;
       
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

        $sql2 = "UPDATE dat_not_inter SET id_usua='$medico',fecha_inter='$merge',motinter='$motinter', riefeinter='$riefeinter', revlinter='$revlinter', probinter='$probinter', tratprocinter='$tratprocinter', procinter='$procinter' , criteinter='$criteinter', planinter='$planinter', sugdiaginter='$sugdiaginter' WHERE id_inter= $id_inter";
        $result = $conexion->query($sql2);

        echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> DATO EDITADO CORRECTAMENTE...</p>";
        echo '<script type="text/javascript">window.location ="../cartas_consentimientos/consent_medico.php"</script>';
      }
  ?>

<footer class="main-footer">
    <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<!-- FastClick -->
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
    <?php
    include("../../template/footer.php");
    ?>
</footer>
<!-- FastClick -->
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){
            $('#mibuscador').select2();
    });
</script>


</body>

</html>