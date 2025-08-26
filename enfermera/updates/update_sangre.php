<?php
session_start();
include "../../conexionbd.php";
include("../header_enfermera.php");
 
if (isset($_SESSION['pac'])) { //si existe el id mandado por metodo GET, se hara la consulta donde el id debe ser igual al id mandado por el metodo GET
    $resultado = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp  WHERE id_atencion=" . $_SESSION['pac']) or die($conexion->error);
    if (mysqli_num_rows($resultado) > 0) { //se mostrara si existe mas de 1
        $f = mysqli_fetch_row($resultado);

    } else {
        header("Location: ./consulta_urgencias.php"); //te regresa a la página principal
    }
}

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

  <!---
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
  <title>BANCO DE SANGRE</title>
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
</style>
  
</head>
<body>

<div class="col-sm-12">
    <div class="container">
        <div class="row">
            <div class="col">
                
<div class="container">
        <div class="thead" style="background-color: #0c675e; color: white; font-size: 20px;">
                 <tr><strong><center>REGISTRO DE TRANSFUSIONES EN EL EXPEDIENTE CLÍNICO</center></strong>
</div>
    <hr>
<?php

include "../../conexionbd.php";
if (isset($_SESSION['pac'])) {
$resultado1 = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE id_atencion=" . $_SESSION['pac']) or die($conexion->error);

?>
  <?php
                    while ($f1 = mysqli_fetch_array($resultado1)) {
                           $sangre=$f1['tip_san'];

                        ?>
 
                      <div class="container"> 
                        <div class="row">
      <div class="col-sm-6">
EXPEDIENTE : <td><strong><?php echo $f1['Id_exp']; ?></strong></td><br>
PACIENTE : <td><strong><?php echo $f1['papell']; ?></strong></td>
<td><strong><?php echo $f1['sapell']; ?></strong></td>
<td><strong><?php echo $f1['nom_pac']; ?></strong></td><br>
SEXO : <td><strong><?php echo $f1['sexo']; ?></strong></td><br>
   
    </div>

    <div class="col-sm-4">
<?php $date = date_create($f1['fecha']);
$date1 = date_create($f1['fecnac']);
                                     ?>
      FECHA DE INGRESO : <td><strong><?php echo date_format($date, "d/m/Y"); ?></strong></td><br>
      FECHA DE NACIMIENTO : <td><strong><?php echo date_format($date1, "d/m/Y"); ?></strong></td><br>
      EDAD : <td><strong><?php echo $f1['edad']; ?></strong></td><br>  
    <?php  
                    }
                    ?> 
    </div>

<?php
$resultado2 = $conexion->query("select * from cat_camas WHERE id_atencion=" .$_SESSION['pac']) or die($conexion->error);
while ($f2 = mysqli_fetch_array($resultado2)) {
?>
  <div class="col">
<?php  
 if(isset($f2)){
    $cama=$f2['num_cama'];
  }else{
    $cama='Sin Cama';
  }
 ?>
HABITACIÓN : <td><strong><?php echo $cama; ?></strong></td> 

<?php
}
?>
    </div>
    
    
  </div>

</div>
<hr>
                                
                        
            </div>
            <div class="row">
              <div class="col-sm-10">
                <?php
                date_default_timezone_set('America/Guatemala');
                $fecha_actual = date("d-m-Y H:i:s");
                ?>
                
  <div class="form-group">
    <label for="fecha">FECHA Y HORA ACTUAL:</label>
    <input type="datetime" value="<?= $fecha_actual ?>" class="form-control" disabled>
    </div>
    </div>
    </div>
    <div class="row">
      <div class="col">
        <strong><label>INGRESAR SIGNOS VITALES</label><br></strong>
      <a href="../transfucion_de_sangre/signos_vitales_transf.php"><button type="button" class="btn btn-danger">SIGNOS VITALES</button></a>
      </div>
    </div>
    <hr>
  <div class="row">
    <h4><strong>TIPO DE SANGRE : <?php echo $sangre; ?></h4></strong>
  </div>   
    </div>
<?php 
$id_sangre=$_GET['id_sangre'];
$select="SELECT * FROM dat_trans_sangre where id_sangre=$id_sangre";
$result=$conexion->query($select);
while ($row=$result->fetch_assoc()) {
 ?>
 <div class="container">  <!--INICIO -->
<form action="" method="POST">
  
 <div class="table-responsive"> 
<table class="table table-bordered table-striped" id="mytable">
                <thead class="thead">
    <tr>

      <th colspan="1"> FECHA DE LA TRANSFUNSIÓN</th>

      <th colspan="1">NUM. DE LA UNIDAD</th>

      <th colspan="1">CONTENIDO</th>
      <th colspan="1">HORA INICIO</th>
      <th colspan="5"><center>SIGNOS VITALES</center></th>
      <th colspan="1">VOLUMEN TRANS.</th>
      <th colspan="1">NOMBRE Y FIRMA DE QUIEN APLICO LA TRANSFUSIÓN</th>
      <th colspan="1">ESTADO GENERAL DEL PACIENTE Y OBSERVACIONES</th>

    </tr>

    <tr>

      <th></th>

      <th></th>

      <th></th>

      <th></th>

      <th>SECUENCIA</th>
      <th>PRESIÓN. SIST  </th>
      <th>PRESIÓN. DIAST </th>
      <th>FREC. CARDIACA  </th>
      <th>TEMPERATURA  </th>


    </tr>

  </thead>

  <tbody>

    <tr>

      <th><input type="date" class="form-control" name="fecht" value="<?php echo $row['fecht'] ?>"></th>

      <td><input type="number" class="form-control" name="numt" value="<?php echo $row['numt'] ?>"></td>

      <td><input type="text" class="form-control" name="cont" value="<?php echo $row['cont'] ?>"></td>

      <td><input type="time" class="form-control" name="hor_in" value="<?php echo $row['hor_in'] ?>"></td>

      <td>INICIO</td>

      <td><input type="text" class="form-control" name="t" value="<?php echo $row['t'] ?>"><br>
       <!-- <input type="text" class="form-control" value="" name="td"><br>
        <input type="text" class="form-control" value="" name="tde"><br>--></td>


      <td><input type="text" class="form-control" name="a" value="<?php echo $row['a'] ?>"><br>
       <!-- <input type="text" value="" class="form-control" name="ad"><br>
        <input type="text" class="form-control" name="ade"><br>--></td>

      <td><input type="text" class="form-control" name="fc" value="<?php echo $row['fc'] ?>"><br>
      <!--  <input type="text" class="form-control" value="" name="fcd"><br>
        <input type="text" class="form-control" name="fcde"><br>--></td>

      <td><input type="text" class="form-control" name="temp_t" value="<?php echo $row['temp_t'] ?>"><br>
     <!--   <input type="text" value="" class="form-control" name="temp_td"><br>
        <input type="text" class="form-control" name="temp_tde"><br>--></td>

    <!--  <td><input type="time" class="form-control" name="hor_t"></td>-->

      <td><textarea class="form-control" name="vol"><?php echo $row['vol'] ?></textarea></td>

      <td><textarea class="form-control" name="nom"><?php echo $row['nom'] ?></textarea></td>

      <td><textarea class="form-control" name="estgen"><?php echo $row['estgen'] ?></textarea></td>

    </tr>

    
  </tbody>

</table></div>
<div class="container">
  <div class="row">
    <div class="col-sm">  
    <p style = "font-family:arial;">
       <strong>RECOMENDACIONES :</strong><br>
       1.-EL SERVICIO CLINICO DEBERA MANTENER LA UNIDAD EN TEMPERATURAS Y CONDICIONES ADECUADAS QUE ASEGUREN SU VIABILIDAD.<br>
       2.-ANTES DE CADA TRANSFUSIÓN DEBERA VERIFICARSE LA IDENTIDAD DEL RECEPTOR Y DE LA UNIDAD DESIGNADA PARA ESTE.<br>
       3.-NO SE DEBERA AGREGAR A LA UNIDAD NINGUN MEDICAMENTO O SOLUCIÓN, INCLUSO LAS DESTINADAS PARA USO INTRAVENOSO, CON EXCEPCIÓN DE SOLUCIÓN SALINA AL 0.9% CUANDO ASI SEA NECESARIO.<br>
       4.-LA TRANSFUSIÓN DE CADA UNIDAD NO DEBERA EXCEDER DE 4 HORAS.<br>
       5.-LOS FILTROS DEBERÁN CAMBIARSE CADA 6 HRS. O CUANDO HUBIESEN TRANSFUNDIDO 4 UNIDADES.<br>
       6.-DE PRESENTARSE UNA REACCIÓN TRANSFUCIONAL,SUSPENDER INMEDIATAMENTE LA TRANSFUCIÓN, NOTIFICAR AL MEDICO ENCARGADO Y REPORTAR AL BANCO DE SANGRE, SIGUIENDO LAS INSTRUCCIONES SEÑALADAS EN EL FORMATO DE REPORTE QUE ACOMPAÑA A LA UNIDAD.<br>
       7.-EN CASO DE NO TRANSFUNDIR LA UNIDAD, REGRESARLA AL BANCO DE SANGRE O SERVICIO DE TRANSFUSIÓN PREFERENTEMENTE ANTES DE TRANSCURRIDAS 2 HORAS A PARTIR DE QUE LA UNIDAD SALIO DEL BANCO DE SANGRE O DEL SERVICIO DE TRANSFUSIÓN.  
    </p>  
    </div>
  </div> 
</div>

</div>


<hr>



                       <div class="form-group col-6">
                            <button type="submit" name="guardar" class="btn btn-primary">FIRMAR</button>
                            <button type="button" class="btn btn-danger" onclick="history.back()">CANCELAR</button>
                        </div>

                        <br>
                        </form>
</div> <!--TERMINO DE div container-->
<?php } ?>
 <?php 
  if (isset($_POST['guardar'])) {

        $fecht    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fecht"], ENT_QUOTES)));
        $numt    = mysqli_real_escape_string($conexion, (strip_tags($_POST["numt"], ENT_QUOTES)));
        $cont  = mysqli_real_escape_string($conexion, (strip_tags($_POST["cont"], ENT_QUOTES)));
        $hor_in    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hor_in"], ENT_QUOTES))); 

        $t    = mysqli_real_escape_string($conexion, (strip_tags($_POST["t"], ENT_QUOTES)));
        $a    = mysqli_real_escape_string($conexion, (strip_tags($_POST["a"], ENT_QUOTES)));
        $fc  = mysqli_real_escape_string($conexion, (strip_tags($_POST["fc"], ENT_QUOTES)));
        $temp_t    = mysqli_real_escape_string($conexion, (strip_tags($_POST["temp_t"], ENT_QUOTES)));

        $vol    = mysqli_real_escape_string($conexion, (strip_tags($_POST["vol"], ENT_QUOTES)));
        $nom  = mysqli_real_escape_string($conexion, (strip_tags($_POST["nom"], ENT_QUOTES)));
        $estgen    = mysqli_real_escape_string($conexion, (strip_tags($_POST["estgen"], ENT_QUOTES)));

        

date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

$sql2 = "UPDATE dat_trans_sangre SET fecht='$fecht', numt='$numt', cont ='$cont', hor_in='$hor_in',t='$t', a='$a', fc ='$fc', temp_t='$temp_t', vol='$vol', nom ='$nom', estgen='$estgen' WHERE id_sangre=$id_sangre";
$result = $conexion->query($sql2);
       

        echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> DATO EDITADO CORRECTAMENTE...</p>";
        echo '<script type="text/javascript">window.location ="../pdf/vista_pdf.php"</script>';
      }
  ?>
</div>
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

<!-- FastClick -->
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>


<script>
    document.oncontextmenu = function () {
        return false;
    }
</script>



</body>
</html>