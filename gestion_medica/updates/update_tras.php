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


        <title>MEDICO </title>
    </head>


    <div class="col-sm">
        
            <div class="container">
                <div class="row">
                    <div class="col">
                      
                      <div class="thead" style="background-color: #0c675e; color: white; font-size: 24px;">
  <center><strong>NOTA DE TRASLADO</strong></center><p>
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
                                   disabled>
                        </div>
                    </div>
                </div>


                

                    <div class="row">
                        <hr>
                        <div class="col">
                            <div class="col-12">
                                 
                                <?php

include "../../conexionbd.php";

$resultado1 = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE id_atencion=" . $_SESSION['hospital']) or die($conexion->error);



?>
  <?php
                    while ($f1 = mysqli_fetch_array($resultado1)) {
                           

                        ?>

<div class="container">      
                           
  <div class="row">
    <div class="col">
 NO.EXPEDIENTE : <td><strong><?php echo $f1['Id_exp']; ?></strong></td><br>
PACIENTE : <td><strong><?php echo $f1['papell']; ?></strong></td>
<td><strong><?php echo $f1['sapell']; ?></strong></td>
<td><strong><?php echo $f1['nom_pac']; ?></strong></td><br>
SEXO : <td><strong><?php echo $f1['sexo']; ?></strong></td><br>
   
    </div>

    <div class="col">
<?php $date = date_create($f1['fecha']);
$date1 = date_create($f1['fecnac']);
                                     ?>
      FECHA DE INGRESO : <td><strong><?php echo date_format($date, "d/m/Y"); ?></strong></td> <br>
      FECHA DE NACIMIENTO : <td><strong><?php echo date_format($date1, "d/m/Y"); ?></strong></td><br>
      EDAD :<td><strong><?php echo $f1['edad']; ?></strong></td><br>  
    <?php  
                    }
                    ?> 
    </div>

<?php
$resultado2 = $conexion->query("select * from cat_camas WHERE id_atencion=" .$_SESSION['hospital']) or die($conexion->error);
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


  </div><hr>

  <form action="" method="POST">
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
 <div class="thead" style="background-color: #0c675e; color: white; font-size: 19px;">
  <center><strong>SIGNOS VITALES</strong></center><p>
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
     <div class="thead" style="background-color: #0c675e; color: white; font-size: 19px;">
  <center><strong>SIGNOS VITALES</strong></center><p>
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
<hr>
 <?php 
$id_traslado=$_GET['id_traslado'];
$tras="SELECT * FROM dat_traslado where id_traslado=$id_traslado";
$result=$conexion->query($tras);
while ($row=$result->fetch_assoc()) {
    $id_usua=$row['id_usua'];
 ?>
  <div class="container">
       <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label>ESTABLECIMIENTO QUE ENVÍA:</label><br>
                <input type="text" name="env" class="form-control" value="<?php echo $row['env'] ?>" onkeyup="javascript:this.value=this.value.toUpperCase();" style="text-transform:uppercase;">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">

                <label>ESTABLECIMIENTO RECEPTOR:</label><br>
                <input type="text" name="rec" class="form-control" value="<?php echo $row['rec'] ?>" onkeyup="javascript:this.value=this.value.toUpperCase();" style="text-transform:uppercase;">
            </div>
        </div>
    </div><hr>
    <strong><label>RESUMEN CLÍNICO</label></strong> <hr>
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label>Doctor que Envía</label>
                <input type="text" name="docenv" class="form-control" value="<?php echo $row['docenv'] ?>" placeholder="Nombre completo del Doctor que Envia" onkeyup="javascript:this.value=this.value.toUpperCase();" style="text-transform:uppercase;">
            </div>
        </div>
        <div class="col">
            <div class="form-group">
               <label>Doctor que Recibe</label> 
               <input type="text" name="docrec" class="form-control" value="<?php echo $row['docrec'] ?>" placeholder="Nombre completo del Doctor que Recibe" onkeyup="javascript:this.value=this.value.toUpperCase();" style="text-transform:uppercase;">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <strong><label>Resumen Clínico</label></strong>
                <textarea name="resumclin" class="form-control" rows="5"><?php echo $row['resumclin'] ?></textarea>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-6 ">
            <div class="form-group">
                <strong><label> MOTIVO DE ENVÍO:</label></strong><br>
                <textarea name="motenv" class="form-control" rows="5"><?php echo $row['motenv'] ?></textarea>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <strong><label> IMPRESIÓN DIAGNÓSTICA:</label></strong><br>
                <textarea name="imdiagn" class="form-control" rows="5"><?php echo $row['imdiagn'] ?></textarea>

            </div>
        </div>

    </div> 
    <div class="row">
        <div class="col-sm-12 ">
            <div class="form-group">

                <strong><label> TERAPÉUTICA EMPLEADA:</label></strong><br>
                <textarea name="ter" class="form-control" rows="5"><?php echo $row['ter'] ?></textarea>
            </div>
        </div>
    </div> 
    <?php 
$date=date_create($row['horenv']);
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
  </div>
     
                    <hr>
                   

                     <center><button type="submit" name="guardar" class="btn btn-primary"><font size="3">FIRMAR</font></button>
                            <button type="button" class="btn btn-danger" onclick="history.back()">CANCELAR</button></center><br>
                </div>    
        </form>
    </div>

</div>
 <?php 
  if (isset($_POST['guardar'])) {

        $env    = mysqli_real_escape_string($conexion, (strip_tags($_POST["env"], ENT_QUOTES))); //Escanpando caracteres
        $rec    = mysqli_real_escape_string($conexion, (strip_tags($_POST["rec"], ENT_QUOTES))); //Escanpando caracteres
        $docenv   = mysqli_real_escape_string($conexion, (strip_tags($_POST["docenv"], ENT_QUOTES)));
        $docrec    = mysqli_real_escape_string($conexion, (strip_tags($_POST["docrec"], ENT_QUOTES))); //Escanpando caracteres
        $resumclin    = mysqli_real_escape_string($conexion, (strip_tags($_POST["resumclin"], ENT_QUOTES)));
        $motenv    = mysqli_real_escape_string($conexion, (strip_tags($_POST["motenv"], ENT_QUOTES)));
        $imdiagn    = mysqli_real_escape_string($conexion, (strip_tags($_POST["imdiagn"], ENT_QUOTES)));
        $ter    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ter"], ENT_QUOTES)));
        $fecha= mysqli_real_escape_string($conexion,(strip_tags($_POST["fecha"], ENT_QUOTES)));
        $hora= mysqli_real_escape_string($conexion,(strip_tags($_POST["hora"], ENT_QUOTES)));
        $medico= mysqli_real_escape_string($conexion,(strip_tags($_POST["medico"], ENT_QUOTES)));

        $merge = $fecha.' '.$hora;
       
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

        $sql2 = "UPDATE dat_traslado SET id_usua='$medico',horenv='$merge',env='$env', rec='$rec', docenv='$docenv', docrec='$docrec', resumclin='$resumclin', motenv='$motenv' , imdiagn='$imdiagn', ter='$ter' WHERE id_traslado= $id_traslado";
        $result = $conexion->query($sql2);

        echo "<p class='alert alert-success' id='mensaje'> <i class='fa fa-check'></i> DATO EDITADO CORRECTAMENTE...</p>";
        echo '<script type="text/javascript">window.location ="../cartas_consentimientos/consent_medico.php"</script>';
      }
  ?>
</div>
</div>
</div>
</div>
</div>
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