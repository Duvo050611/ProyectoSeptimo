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


    <title>NOTA PREOPERATORIA </title>
</head>
<body>

<div class="col-sm-12">
    <div class="container">
        <div class="row">
            <div class="col">
                
                  <div class="thead" style="background-color: #0c675e; color: white; font-size: 24px;">
  <center><strong> NOTA PREOPERATORIA</strong></center><p>
</div> 
                <hr>
<?php

include "../../conexionbd.php";
$id_atencion=$_GET['id_atencion'];
$resultado1 = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE id_atencion=$id_atencion") or die($conexion->error);

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
$resultado2 = $conexion->query("select * from cat_camas WHERE id_atencion=$id_atencion") or die($conexion->error);
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
            
        </div>



  
<div class="container">  <!--INICIO DE CONSULTA DE URGENCIAS-->
<form action="insertar_preoperatoria.php?id_atencion=<?php echo $id_atencion ?>" method="POST">

  <div class="row">
    <div class="col-sm">
     
      <strong><p>TIPO DE CIRUGÍA:</p></strong>
      <div class="form-check">
        <input class="form-check-input" type="radio" id="URGENCIAS" value="URGENCIA" name="tipo_cirugia_preop" required="">
        <label class="form-check-label" for="URGENCIAS">URGENCIA</label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" id="MADRE" value="ELECTIVA" name="tipo_cirugia_preop">
        <label class="form-check-label" for="ELECTIVA">ELECTIVA</label>
      </div>
    </div>
  </div>
  <hr>
            <?php 

include "../../conexionbd.php";
$resultado5=$conexion->query("SELECT * from signos_vitales WHERE id_atencion=$id_atencion ORDER by id_sig DESC LIMIT 1") or die($conexion->error);

     while ($f5 = mysqli_fetch_array($resultado5)) {
      $atencion=$f5['id_sig'];
    }
    ?>
    <?php
if (isset($atencion)) {
                        ?>
<?php 

include "../../conexionbd.php";
$resultado5=$conexion->query("SELECT * from signos_vitales WHERE id_atencion=$id_atencion ORDER by id_sig DESC LIMIT 1") or die($conexion->error);

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
       <div class="col"><input type="text" class="form-control" name="ta_sist" ></div> /
       <div class="col"><input type="text" class="form-control" name="ta_diast"></div>
     </div>
    </div>
    <div class="col-sm-2">
      FRECUENCIA CARDIACA:<input type="text" class="form-control" name="frec_card">
    </div>
    <div class="col-sm-2">
      FRECUENCIA RESPIRATORIA:<input type="text" class="form-control" name="frec_resp">
    </div>
    <div class="col-sm-2">
     <br>TEMPERATURA:<input type="text" class="form-control"  name="preop_temp">
    </div>
    <div class="col-sm-2">
     SATURACIÓN OXÍGENO:<input type="text"  class="form-control" name="sat_oxi">
    </div>
    <div class="col-sm-1">
     <br>PESO:<input type="text" class="form-control"  name="preop_peso">
    </div>
    <div class="col-sm-1">
     <br>TALLA:<input type="text" class="form-control" name="preop_talla" >
    </div>
  </div>
</div>

<?php } ?>
<br>
     
<hr>
<div class="container">
  <div class="row">
    <div class="col-sm">
    <label for="exampleFormControlTextarea1"><strong>RESUMEN DE INTERROGATORIO:</strong></label>
    <textarea class="form-control" name="resumen_clin" id="exampleFormControlTextarea1" required rows="4" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
    </div><p>

   <div class="col-sm">
    <label for="exampleFormControlTextarea1"><strong>EXPLORACIÓN FÍSICA:</strong></label>
    <textarea class="form-control" name="beneficios" id="exampleFormControlTextarea1" required rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
  </div>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-sm">
       <label for="exampleFormControlTextarea1"><strong>RESULTADOS DE LABORATORIO Y GABINETE:</strong></label>
       <textarea class="form-control" name="result_lab_gab" id="exampleFormControlTextarea1" required rows="4" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
    </div>
    <div class="col-sm">
        <label for="id_cie_10"><strong>DIAGNÓSTICO PREOPERATORIO:</strong></label>
        <select name="diag_preop" class="form-control" data-live-search="true" id="mibuscador" style="width : 100%; heigth : 100%">
            <?php
            include "../../conexionbd.php";
            $sql_diag="SELECT * FROM cat_diag ";
            $result_diag=$conexion->query($sql_diag);
            while($row=$result_diag->fetch_assoc()){
                echo "<option value='" . $row['diagnostico'] . "'>" . $row['diagnostico'] . "</option>";}
          ?>
        </select>
    </div>

    <!--
    
     -->
  </div>
</div>
<br>

<div class="container">
  <div class="row">
    <div class="col-sm">
       <label for="exampleFormControlTextarea1"><strong>PRONÓSTICOS PARA LA VIDA Y LA FUNCIÓN</strong></label>
       <textarea class="form-control" name="pronostico" id="exampleFormControlTextarea1" required rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
    </div>
    <div class="col-sm">
       <label for="exampleFormControlTextarea1"><strong>RIESGOS QUIRÚRGICOS</strong></label>
    <textarea class="form-control" name="riesgos" id="exampleFormControlTextarea1" required rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
    </div>  
  </div>
</div>
<br>

<div class="container">
  <div class="row">
    <div class="col-sm">
       <label for="exampleFormControlTextarea1"><strong>CUIDADOS Y PLAN TERAPÉUTICO PREOPERATORIO</strong></label>
    <textarea class="form-control" name="cuidados" id="exampleFormControlTextarea1" required rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
    </div>
    <div class="col-sm">
      <label for="exampleFormControlTextarea1"><strong>CIRUGÍA PROGRAMADA:</strong></label>
    <textarea class="form-control" name="tipo_inter_plan" id="exampleFormControlTextarea1" required rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
    </div>
  </div>
</div>  
<br>

<div class="container">
  <div class="row">
    <div class="col-sm-3">
      <div class="form-group">
       <label for="exampleFormControlInput1"><strong>FECHA Y HORA DE CIRUGÍA:</strong></label>
       <input type="date" name="fecha_cir" class="form-control" id="exampleFormControlInput1" required="">
       <input type="time" name="hora_cir" class="form-control" id="exampleFormControlInput1" required="">
     </div>
    </div>
    <div class="col-sm-3">
      <div class="form-group">
       <label for="exampleFormControlInput1"><strong>TIEMPO Qx ESTIMADO:</strong></label>
       <input type="text" class="form-control" name="tiempo_estimado" required id="exampleFormControlInput1" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
      </div>
    </div>
    <div class="col-sm">
      <div class="form-group">
        <label for="exampleFormControlInput1"><strong>NOMBRE DEL MÉDICO CIRUJANO:</strong></label>
         <input type="text" class="form-control" name="nom_medi_cir" required id="exampleFormControlInput1" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
      </div>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-sm">
        <label for="exampleFormControlTextarea1"><strong>ANESTESIA SUGERIDA</strong></label>
       <textarea class="form-control" name="anestesia_sug" id="exampleFormControlTextarea1" required rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
    </div>
      <div class="col-sm">
     <div class="form-group">
    <label for="exampleFormControlInput1"><strong>SANGRADO ESPERADO (ML):</strong></label>
    <input type="text" class="form-control" name="sangrado_esp" required id="exampleFormControlInput1" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
    </div>
  </div>
</div> 

<div class="container">
<div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>OBSERVACIONES:</strong></label>
    <textarea class="form-control" name="observ" id="exampleFormControlTextarea1" required rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
  </div>
</div>

<hr>
<div class="col-sm">
    <label for="exampleFormControlTextarea1"><strong>MARCAJE QUIRÚRGICO:</strong></label>
    <center><img src="../../img/marcaje_qx.jpg" height="600"></center>
</div>

 
<center>
    <div class="form-group col-6">
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
<script type="text/javascript">
    $(document).ready(function () {
        $('#mibuscador').select2();
    });
</script>


</body>
</html>