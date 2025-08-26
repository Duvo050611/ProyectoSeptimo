<?php
session_start();
include "../../conexionbd.php";
include("../header_medico.php");


?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″/>

    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <script src="jquery-3.1.1.min.js"></script>
    <script src="js/select2.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
            crossorigin="anonymous"></script>



    <!--  Bootstrap  -->
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
          integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <script src="../../js/jquery-3.3.1.min.js"></script>
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/popper.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/main.js"></script>
     



    <title>NOTA INTERVENCIÓN QUIRURGICA</title>
</head>
<body>

<div class="col-sm-12">
    <div class="container">
        <div class="row">
            <div class="col">
                
                  <div class="thead" style="background-color: #0c675e; color: white; font-size: 24px;">
  <center><strong>NOTA INTERVENCIÓN QUIRÚRGICA</strong></center><p>
</div> 
                <hr>
<?php

include "../../conexionbd.php";

$resultado1 = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE id_atencion=" . $_GET['id_atencion']) or die($conexion->error);

?>
  <?php
                    while ($f1 = mysqli_fetch_array($resultado1)) {

                        ?>
      <div class="container"> 
                        <div class="row">
      <div class="col-sm-6">
 NO.EXPEDIENTE : <td><strong><?php echo $f1['Id_exp']; ?></strong></td><br>
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
$resultado2 = $conexion->query("select * from cat_camas WHERE id_atencion=" .$_GET['id_atencion']) or die($conexion->error);
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
                $fecha_actual = date("Y-m-d H:i:s");
                ?>
                
                <div class="form-group">
                    <label for="fecha">FECHA Y HORA ACTUAL:</label>
                    <input type="datetime" name="fec_hc" value="<?= $fecha_actual ?>" class="form-control" disabled>
                </div>
            </div>
        </div>



  
      <div class="container">  <!--INICIO DE CONSULTA DE URGENCIAS-->



<form action="insertar_not_inquir.php?id_atencion= <?php echo $_GET['id_atencion']; ?>" method="POST">
 <div class="row">
    <div class="col-sm">
     
      <strong><p>TIPO DE INTERVENCIÓN:</p></strong>
     <div class="form-check">
  <input class="form-check-input" type="radio" name="tipo_intervencion" id="URGENCIAS" value="URGENCIA" name="tipocir" required="">
  <label class="form-check-label"  for="URGENCIAS">
    URGENCIA
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" id="electiva" name="tipo_intervencion" value="ELECTIVA" name="tipocir">
  <label class="form-check-label" for="ELECTIVA">
    ELECTIVA
  </label>
</div>
    </div>
    
  </div>
  <hr>
  



 <div class="container">
  <div class="form-group">
  <?php

$resultado2 = $conexion->query("select * from dat_not_preop WHERE id_atencion=" .$_SESSION['hospital']." order by id_not_preop DESC LIMIT 1") or die($conexion->error);

?>
  <?php
                    while ($f2 = mysqli_fetch_array($resultado2)) {

                        ?>


        <label for="id_cie_10"><strong> DIAGNÓSTICO PREOPERATORIO:</strong></label>
        <input type="text" class="form-control" name="diag_preop" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" value=" <?php echo $f2['diag_preop'];?>" disabled>
                               
<?php } ?>
                              </div>
     </div>





<hr>
 <div class="container">
<div class="row">

  <div class="col"><strong><center>SANGRE EN</center></strong><hr>
    
  <div class="row">
    <div class="col-sm">
      <label for="exampleFormControlInput1">QUIROFANO:</label>
    <input type="number" class="form-control" name="quirofano" required id="exampleFormControlInput1" placeholder="ml." style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    </div>
    <div class="col-sm">
      <label for="exampleFormControlInput1">RESERVA:</label>
    <input type="number" class="form-control" name="reserva" id="exampleFormControlInput1" required placeholder="ml." style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    </div>
  </div>

<hr>
  </div>

  <div class="col"><strong><center>ANESTESIA</center></strong><hr>
  <div class="row">
    <div class="col-sm-4">
    <label for="exampleFormControlInput1">LOCAL</label>
    <input type="checkbox"  name="local" value="SI" id="exampleFormControlInput1" > 
    </div>
    <div class="col-sm-4">
      <label for="exampleFormControlInput1">REGIONAL</label>
    <input type="checkbox"  name="regional" value="SI" id="exampleFormControlInput1"> 
    </div>
    <div class="col-sm-4">
      <label for="exampleFormControlInput1">GENERAL</label>
    <input type="checkbox" name="general" value="SI" id="exampleFormControlInput1" >
    </div>
  </div>
  <hr>
  </div>
  
  <div class="col"><hr>

<div class="row">
    
    <?php
$resultado1 = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE id_atencion=" . $_SESSION['hospital']) or die($conexion->error);
?>
  <?php
                    while ($f1 = mysqli_fetch_array($resultado1)) {

                        ?>
    <div class="col-sm">
      <label for="exampleFormControlInput1">GRUPO Y RH SANGUÍNEO:</label>
<input type="text" class="form-control" name="tipo_sangre" id="exampleFormControlInput1" value="<?php echo $f1['tip_san']?>"disabled>

    </div>
    <?php
}
?>
  </div>
<hr>
  </div>

</div>
</div>
<br>

<div class="container">
  <div class="row">
    <div class="col-sm">
      <label for="exampleFormControlTextarea1"><strong>CUENTA DE GASAS Y COMPRESAS</strong></label>
      <select class="form-control" aria-label="CUENTA DE GASAS Y COMPRESAS" name="inst_necesario">
       <option selected>CUENTA DE GASAS Y COMPRESAS</option>
       <option value="COMPLETA">COMPLETA</option>
       <option value="INCOMPLETA">INCOMPLETA</option>
      </select>
    </div>
    <div class="col-sm">
      <label for="exampleFormControlTextarea5"><strong>OBSERVACIONES</strong></label>
      <textarea class="form-control" name="medmat_necesario" id="exampleFormControlTextarea5" required rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
    </div>
  </div>
</div>

<hr>
<strong><center>PROGRAMACIÓN EN QUIROFANO</center></strong><br>

  <?php

$resultado2 = $conexion->query("select * from dat_not_preop WHERE id_atencion=" .$_SESSION['hospital']." order by id_not_preop DESC LIMIT 1") or die($conexion->error);

?>
  <?php
                    while ($f2 = mysqli_fetch_array($resultado2)) {

                        ?>
<div class="row">
  <div class="col-6">
    <div class="form-group">
      <label for="exampleFormControlInput1"><strong>CIRUGÍA PROGRAMADA</strong></label>
      <input type="text" class="form-control" name="intervencion_quir" id="exampleFormControlInput1" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" value="<?php echo $f2['tipo_inter_plan']?>" disabled>
    </div>
  </div>
  <div class="col-3">
      <label for="exampleFormControlInput1"><strong> FECHA DE CIRUGÍA:</strong></label>
      <input type="date" name="fecha_cir" class="form-control" id="exampleFormControlInput1" required="" value="<?php echo $f2['fecha_cir']?>" disabled>
  </div>
  <div class="col-3">
      <label for="exampleFormControlInput1"><strong> HORA DE CIRUGÍA:</strong></label>
      <input type="time" name="hora_cir" class="form-control" id="exampleFormControlInput1" required="" value="<?php echo $f2['hora_cir']?>" disabled>
  </div>
  
    <?php } ?>
</div>

<hr>
<div class="container">
  <div class="row">
    <div class="col-sm-5">

  <div class="form-group">
                        <label for="diag_postop"><strong>DIAGNÓSTICO POST-OPERATORIO:</strong></label>
                        <select class="form-control" name="diag_postop" required id="diag_postop">
                                 <option value="">SELECCIONAR DIAGNÓSTICO POST-OPERATORIO</option>
                          
                            <?php
                            $sql_d = "SELECT DISTINCT id_diag,diagnostico FROM cat_diag";
                            $result_d = $conexion->query($sql_d);
                            while ($row_d = $result_d->fetch_assoc()) {
                                echo "<option value='" . $row_d['diagnostico'] . "'>" . $row_d['diagnostico'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>       
    
    </div>
    <div class="col-sm-5">
      <label for="exampleFormControlTextarea1"><strong>CIRUGÍA REALIZADA:</strong></label>
      <textarea class="form-control" name="cir_realizada" id="exampleFormControlTextarea1" required rows="5" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
    </div>
    <div class="col-2">
      <div class="form-group">
        <label for="exampleFormControlInput1"><strong>INICIO</strong></label>
        <input type="time" class="form-control" name="inicio"  id="exampleFormControlInput1" required>
      </div>
      <div class="form-group">
        <label for="exampleFormControlInput1"><strong>TÉRMINO</strong></label>
        <input type="time" class="form-control" name="termino"  id="exampleFormControlInput1" required >
      </div>
    </div>
 </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-sm">
       <label for="exampleFormControlTextarea1"><center><strong>ESTUDIOS DE PATOLOGÍA TRANSOPERATORÍA Y POSTOPERATORÍA</strong></center></label>
      <div class="row">
               <div class="col"><label>TRANSOPERATORIOS</label>
            <textarea class="form-control" name="trans" id="exampleFormControlTextarea1" required rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea></div>
                <div class="col"><label>POSTOPERATORIOS</label>
            <textarea  class="form-control" name="posto" id="exampleFormControlTextarea1" required rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea></div>
      </div>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">

    <div class="col-sm">
    
    <label for="exampleFormControlInput1"><strong>ACCIDENTES O INCIDENTES</strong></label>
    <input type="text" class="form-control" name="accident_incident" id="exampleFormControlInput1" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
 
    </div>

     <div class="col-sm">
   
    <label for="exampleFormControlInput1"><strong>COMPLICACIONES</strong></label>
    <input type="text" class="form-control" name="realizada_por" id="exampleFormControlInput1" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">

    </div>
</div>
</div>

<br> 
<hr>
<div class="container">
  <div class="row">
    <div class="col-sm">
       <div class="form-group">
    <label for="exampleFormControlInput1"><strong>PÉRDIDA HEMÁTICA (ML)</strong></label>
    <input type="number" class="form-control" name="perd_hema" id="exampleFormControlInput1" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
    </div>
    
     <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlInput1"><strong>ANESTESIA ADMINISTRADA</strong></label>
    <input type="text" class="form-control" name="anestesia_admin" id="exampleFormControlInput1" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
    </div>

  
     <div class="col-sm">
       <div class="form-group">
    <label for="exampleFormControlInput1"><strong>DURACIÓN DE LA ANESTESIA</strong></label>
    <input type="text" class="form-control" name="anestesia_dur" id="exampleFormControlInput1" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
    </div>
  </div>
</div>

<hr>
<div class="container">
  <div class="row">
  <div class="col-sm">
    <div class="form-group">
    <label for="exampleFormControlInput1"><strong>CIRUJANO</strong></label>
    <input type="text" class="form-control" name="cirujano" id="exampleFormControlInput1" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
  </div>
  <div class="col-sm">
    <div class="form-group">
    <label for="exampleFormControlInput1"><strong>PRIMER AYUDANTE</strong></label>
    <input type="text" class="form-control" name="prim_ayudante" id="exampleFormControlInput1" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
  </div>
  <div class="col-sm">
    <div class="form-group">
    <label for="exampleFormControlInput1"><strong>SEGUNDO AYUDANTE</strong></label>
    <input type="text" class="form-control" name="seg_ayudante" id="exampleFormControlInput1" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
  </div>
  <div class="col-sm">
    <div class="form-group">
    <label for="exampleFormControlInput1"><strong>TERCER AYUDANTE</strong></label>
    <input type="text" class="form-control" name="ter_ayudante" id="exampleFormControlInput1" required style="text-transform:uppercase;"onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
  </div>
</div>
</div>

<div class="container">
  <div class="row">
  <div class="col">
    <div class="form-group">
    <label for="exampleFormControlInput1"><strong>ANESTESIÓLOGO</strong></label>
    <input type="text" class="form-control" name="anestesiologo" id="exampleFormControlInput1" required style="text-transform:uppercase;"onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
  </div>

  <div class="col">
    <div class="form-group">
    <label for="exampleFormControlInput1"><strong>CIRCULANTE</strong></label>
    <input type="text" class="form-control" name="circulante" id="exampleFormControlInput1" required style="text-transform:uppercase;"onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
  </div>
  <div class="col">
    <div class="form-group">
    <label for="exampleFormControlInput1"><strong>INSTRUMENTISTA</strong></label>
    <input type="text" class="form-control" name="instrumentista" id="exampleFormControlInput1" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
  </div>
</div>
</div>


<hr>
<div class="row">
  <div class="col-6">
    <div class="container"><center><strong>QUIRÓFANO</strong></center>
  <div class="row">
    <div class="col">
     <div class="form-group">
       <br><label>SALA</label>
       <select id="quir" name="quir" class="form-control">
          <option>SELECCIONAR TIPO SALA</option>
          <option value="SALA 1">SALA 1</option>
           <option value="SALA 2">SALA 2</option>
            <option value="SALA 3">SALA 3</option>
             <option value="SALA 4">SALA 4</option>
          <option value="SALA DE PARTO">SALA DE PARTO</option>
       </select>
     </div>
    </div>
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlInput1"><br>HORA LLEGADA</label>
    <input type="time" class="form-control" name="hora_llegada_quir" id="exampleFormControlInput1" required>
  </div>
    </div>
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlInput1"><br>HORA SALIDA</label>
    <input type="time" class="form-control" name="hora_salida_quir" id="exampleFormControlInput1" required >
  </div>
    </div>
  </div>
</div>
  </div>
  <div class="col-6">
    <div class="container"><center><strong>SALA DE RECUPERACIÓN</strong></center>
  <div class="row">
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlInput1"><br>HORA LLEGADA</label>
    <input type="time" class="form-control" name="hora_entrada_recup" id="exampleFormControlInput1" required>
  </div>
    </div>
    <div class="col-sm">
     <div class="form-group">
    <label for="exampleFormControlInput1"><br>HORA SALIDA</label>
    <input type="time" class="form-control" name="hora_salida_recup" id="exampleFormControlInput1" required >
  </div>
    </div>
  </div>
</div>
  </div>
 
</div>
<hr>
<div class="container">
<div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>HALLAZGOS-TÉCNICA (DESCRIPCIÓN QUIRÚRGICA)-COMPLICACIONES Y OBSERVACIONES <br><br>NOTA OPERATORIA: </strong></label>
    <textarea class="form-control" id="exampleFormControlTextarea1" name="nota_preop" rows="5" required style="text-transform:uppercase;"></textarea>
  </div>
</div>

<hr>
<div class="container">
<div class="row">
  <div class="col-sm-4"><strong>ESTADO POSTOPERATORIO INMEDIATO:</strong></div>
  <div class="col">
      <div class="form-check">
  <input class="form-check-input" type="radio" id="URGENCIAS" name="estado_postop" value="BUENO" name="estado_postop">
  <label class="form-check-label" for="URGENCIAS">
    BUENO
  </label>
</div>
  </div>
  <div class="col">
      <div class="form-check">
  <input class="form-check-input" type="radio" id="URGENCIAS" name="estado_postop" value="DELICADO" name="estado_postop">
  <label class="form-check-label" for="URGENCIAS">
    DELICADO
  </label>
</div>
  </div>
  <div class="col">
      <div class="form-check">
  <input class="form-check-input" type="radio" name="estado_postop" id="URGENCIAS" value="GRAVE" name="estado_postop">
  <label class="form-check-label" for="URGENCIAS">
    GRAVE
  </label>
</div>
  </div>
  <div class="col">
      <div class="form-check">
  <input class="form-check-input" type="radio" name="estado_postop" id="URGENCIAS" value="MUY GRAVE" name="estado_postop">
  <label class="form-check-label" for="URGENCIAS">
    MUY GRAVE
  </label>
</div>
  </div>
</div>
</div>
<hr>

<div class="container">
<div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>COMENTARIO FINAL Y PRONÓSTICO:</strong></label>
    <textarea class="form-control" name="comentario_final" id="exampleFormControlTextarea1" required rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>PLAN TERAPEÚTICO (POST OPERATORIO):</strong></label>
    <textarea class="form-control" name="plan_tera" id="exampleFormControlTextarea1" required rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col">
       <div class="form-group">
    <label for="exampleFormControlInput1"><strong>DESCRIBIÓ LA OPERACIÓN</strong></label>
    <input type="text" class="form-control" name="descripcion_op" id="exampleFormControlInput1" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
    </div>
    <div class="col">
      <?php 
$usuario = $_SESSION['login'];
//$resultado = $conexion->query("SELECT * FROM reg_usuarios WHERE id_usua='" . $usuario . "'") or die($conexion->error);
 ?>

<div class="form-group">
    <label for="exampleFormControlInput1"><strong>NOMBRE DEL MÉDICO CIRUJANO:</strong></label>
    <input type="text" class="form-control" name="nombre_med_cir"  id="exampleFormControlInput1" required style="text-transform:uppercase;" 
    onkeyup="javascript:this.value=this.value.toUpperCase();" value="" >

  </div>
  </div>
  </div>
</div>

<!--<?php //echo $usuario['nombre']; ?> <?php// echo $usuario['papell']; ?> <?php// echo $usuario['sapell']; ?>-->


<hr>

<center>
  <div class="form-group">
      <button type="submit" class="btn btn-primary">FIRMAR</button>
      <button type="button" class="btn btn-danger" onclick="history.back()">CANCELAR</button>
  </div>
</center>

<br>
                        </form>
</div> <!--TERMINO DE NOTA MEDICA div container-->

</div>
                      
      </div>                   
  
   

</div>

</div>

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


<script type="text/javascript">
    $(document).ready(function () {
        $('#mibuscador').select2();
    });
</script>


</body>
</html>