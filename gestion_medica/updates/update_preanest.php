<?php
session_start();


include "../../conexionbd.php";
include("../header_medico.php");

$usuario = $_SESSION['login'];
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

    <title>NOTA ANESTESICA </title>
</head>
<body>

<div class="col-sm-12">
    <div class="container">
        <div class="row">
            <div class="col">
                
<div class="thead" style="background-color: #0c675e; color: white; font-size: 24px;">
  <center><strong>VALORACIÓN PRE-ANESTÉSICA</strong></center><p>
</div>
    <hr>
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
                    <input type="datetime" name="fec_hc" value="<?= $fecha_actual ?>" class="form-control" disabled>
                </div>
            </div>
        </div>
      <div class="container">  <!--INICIO DE CONSULTA DE URGENCIAS-->
<form action="" method="POST">
             

<div class="container">
  <div class="row">
   <?php

    $resultado2 = $conexion->query("select * from dat_not_preop WHERE id_atencion=" .$_SESSION['hospital']." order by id_not_preop DESC LIMIT 1") or die($conexion->error); ?>   <?php
     while ($f2 = mysqli_fetch_array($resultado2)) {   ?>

    <div class="col col-9">
     <label for="id_cie_10"><strong> DIAGNÓSTICO PREOPERATORIO:</strong></label>
     <input type="text" class="form-control" name="diag_pre" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" value=" <?php echo $f2['diag_preop'];?>" disabled>
    </div>
    <div class="col col-3">
     <label for=""><strong> TIPO (URGENCIA/ELECTIVA):</strong></label>
     <input type="text" class="form-control" name="urg" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" value= " <?php echo $f2['tipo_cirugia_preop'];?>" disabled>
      <?php } ?>
    </div>
   </div>
</div>

<div class="container">

<hr>

  <?php

$resultado2 = $conexion->query("select * from dat_not_preop WHERE id_atencion=" .$_SESSION['hospital']." order by id_not_preop DESC LIMIT 1") or die($conexion->error);
                    while ($f2 = mysqli_fetch_array($resultado2)) {

                        ?>
<div class="row">
   <div class="col col-6">
      <label for="exampleFormControlInput1"><strong>CIRUGÍA PROGRAMADA</strong></label>
      <input type="text" class="form-control"  id="exampleFormControlInput1" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" value="<?php echo $f2['tipo_inter_plan']?>" disabled>
   </div> 
  
   <div class="col col-6">
      <label for="exampleFormControlInput1"><strong>NOMBRE DEL CIRUJANO:</strong></label>
      <input type="text" class="form-control" name="med_proc" id="exampleFormControlInput1" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" value="<?php echo $f2['nom_medi_cir']?>" disabled>
   </div> 
 

     <?php } ?>
</div>

</div>
<hr>
<?php 
$id_peranest=$_GET['id_peranest'];
$tras="SELECT * FROM dat_peri_anest where id_peranest=$id_peranest";
$result=$conexion->query($tras);
while ($row=$result->fetch_assoc()) {
 ?>
<div class=container>
<div class="row">
      <div class="col "> <strong>INTERROGATORIO:</strong></div>
<?php if ($row['inter']=="DIRECTO") {?>
      <div class="col">  
        <div class="form-check">
         <input class="form-check-input" checked="" type="radio" name="inter" id="flexRadioDefault5" value="DIRECTO" required>
         <label class="form-check-label" for="flexRadioDefault5">DIRECTO</label>
        </div> 
      </div>
      <div class="col">
        <div class="form-check">
          <input class="form-check-input" type="radio" name="inter" id="flexRadioDefault6" value="INDIRECTO" required>
          <label class="form-check-label" for="flexRadioDefault6">INDIRECTO</label>
        </div>
      </div>
<?php }else{?>
<div class="col">  
        <div class="form-check">
         <input class="form-check-input" type="radio" name="inter" id="flexRadioDefault5" value="DIRECTO" required>
         <label class="form-check-label" for="flexRadioDefault5">DIRECTO</label>
        </div> 
      </div>
      <div class="col">
        <div class="form-check">
          <input class="form-check-input" checked="" type="radio" name="inter" id="flexRadioDefault6" value="INDIRECTO" required>
          <label class="form-check-label" for="flexRadioDefault6">INDIRECTO</label>
        </div>
      </div>
<?php } ?>
   <div class="col col-6">
     <label><strong>ANESTESIÓLOGO</strong></label>
     <input type="text" name="anest" class="form-control" value="<?php ECHO $row['anest'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
   </div> 
 </div>  
<div class="container">
<div class="row">
  <div class="col">
    <table class="table table-bordered">
  <thead>
    <tr color="red">
      <th scope="col">ANTECEDENTES NO PATOLOGICOS</th>
      <th scope="col">SI</th>
      <th scope="col">NO</th>
 
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>INMUNIZACIONES</td>
<?php if ($row['inmun']=="SI") {?>
      <td>
    <div class="form-check">
  <input class="form-check-input" type="radio" name="inmun" checked="" id="in" value="SI" required>
  <label class="form-check-label" for="in">
  </label>
</div>
</td>
<td>
<div class="form-check">
  <input class="form-check-input" type="radio" name="inmun" id="in2" value="NO" required>
  <label class="form-check-label" for="in2">
  </label>
</div>
</td>
<?php }else{?>
<td>
    <div class="form-check">
  <input class="form-check-input" type="radio" name="inmun" id="in" value="SI" required>
  <label class="form-check-label" for="in">
  </label>
</div>
</td>
<td>
<div class="form-check">
  <input class="form-check-input" type="radio" name="inmun" checked="" id="in2" value="NO" required>
  <label class="form-check-label" for="in2">
  </label>
</div>
</td>
<?php } ?>
    </tr>
    <tr>
      <td>TABAQUISMO</td>
<?php if ($row['tab']=="SI") {?>
<td><div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="tab" id="in2" value="SI" required>
  <label class="form-check-label" for="in2">
  </label>
</div></td>
      <td><div class="form-check">
  <input class="form-check-input" type="radio" name="tab" id="in2" value="NO" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<?php }else{?>
<td><div class="form-check">
  <input class="form-check-input" type="radio" name="tab" id="in2" value="SI" required>
  <label class="form-check-label" for="in2">
  </label>
</div></td>
      <td><div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="tab" id="in2" value="NO" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<?php } ?>    
    </tr>
    <tr>
      <td>ALCOHOLISMO</td>
<?php if ($row['alc']=="SI") {?>
      <td><div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="alc" id="in2" value="SI" required>
  <label class="form-check-label" for="in2">
   
  </label>
</div></td>
      <td><div class="form-check">
  <input class="form-check-input" type="radio" name="alc" id="in2" value="NO" required>
  <label class="form-check-label" for="in2">
   
  </label>
</div></td>
<?php }else{?>
      <td><div class="form-check">
  <input class="form-check-input" type="radio" name="alc" id="in2" value="SI" required>
  <label class="form-check-label" for="in2">
   
  </label>
</div></td>
      <td><div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="alc" id="in2" value="NO" required>
  <label class="form-check-label" for="in2">
   
  </label>
</div></td>
<?php } ?>      
    </tr>
    <tr>
      <td>TRANSFUSIONALES</td>
<?php if ($row['trans']=="SI") {?>
      <td><div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="trans" id="in2" value="SI" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
      <td><div class="form-check">
  <input class="form-check-input" type="radio" name="trans" id="in2" value="NO" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<?php }else{?>
      <td><div class="form-check">
  <input class="form-check-input" type="radio" name="trans" id="in2" value="SI" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
      <td><div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="trans" id="in2" value="NO" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<?php } ?>  
    </tr>
    <tr>
      <td>ALERGIAS</td>
<?php if ($row['alerg']=="SI") {?>
      <td><div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="alerg" id="in2" value="SI" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
      <td><div class="form-check">
  <input class="form-check-input" type="radio" name="alerg" id="in2" value="NO" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<?php }else{?>
            <td><div class="form-check">
  <input class="form-check-input" type="radio" name="alerg" id="in2" value="SI" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
      <td><div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="alerg" id="in2" value="NO" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<?php } ?>  
    </tr>
    <tr>
      <td>TOXICOMANIAS</td>
<?php if ($row['toxi']=="SI") {?>
      <td><div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="toxi" id="in2" value="SI" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
      <td><div class="form-check">
  <input class="form-check-input" type="radio" name="toxi" id="in2" value="NO" required>
  <label class="form-check-label" for="in2"> </label>
</div></td>
<?php }else{?>
      <td><div class="form-check">
  <input class="form-check-input" type="radio" name="toxi" id="in2" value="SI" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
      <td><div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="toxi" id="in2" value="NO" required>
  <label class="form-check-label" for="in2"> </label>
</div></td>
<?php } ?>  
    </tr>
<tr>
<th>ANTECEDENTES PATOLOGICOS</th>
</tr>
 <tr>
      <td>GASTRO/HEPÁTICOS</td>
<?php if ($row['gastro']=="SI") {?>
<td><div class="form-check">
<input class="form-check-input" type="radio" checked="" name="gastro" id="in2" value="SI" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
<input class="form-check-input" type="radio" name="gastro" id="in2" value="NO" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<?php }else{?>
<td><div class="form-check">
<input class="form-check-input" type="radio" name="gastro" id="in2" value="SI" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
<input class="form-check-input" type="radio" checked="" name="gastro" id="in2" value="NO" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<?php } ?> 
    </tr>
     <tr>
      <td>NEUROLÓGICOS</td>
<?php if ($row['neuro']=="SI") {?>
<td><div class="form-check">
<input class="form-check-input" type="radio" checked="" name="neuro" id="in2" value="SI" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
<input class="form-check-input" type="radio" name="neuro" id="in2" value="NO" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<?php }else{?>
<td><div class="form-check">
<input class="form-check-input" type="radio" name="neuro" id="in2" value="SI" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
<input class="form-check-input" type="radio" checked="" name="neuro" id="in2" value="NO" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<?php } ?> 
    </tr>
     <tr>
      <td>NEUMOLÓGICOS</td>
<?php if ($row['neumo']=="SI") {?>
<td><div class="form-check">
<input class="form-check-input" type="radio" checked="" name="neumo" id="in2" value="SI" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
<input class="form-check-input" type="radio" name="neumo" id="in2" value="NO" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<?php }else{?>
<td><div class="form-check">
<input class="form-check-input" type="radio" name="neumo" id="in2" value="SI" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
<input class="form-check-input" type="radio" checked="" name="neumo" id="in2" value="NO" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<?php } ?> 
    </tr>
     <tr>
      <td>RENALES</td>
<?php if ($row['ren']=="SI") {?>
<td><div class="form-check">
<input class="form-check-input" type="radio" checked="" name="ren" id="in2" value="SI" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
<input class="form-check-input" type="radio" name="ren" id="in2" value="NO" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<?php }else{?>
<td><div class="form-check">
<input class="form-check-input" type="radio" name="ren" id="in2" value="SI" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
<input class="form-check-input" type="radio" checked="" name="ren" id="in2" value="NO" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<?php } ?>
    </tr>
     <tr>
      <td>CARDIOLÓGICOS</td>
<?php if ($row['card']=="SI") {?>
<td><div class="form-check">
<input class="form-check-input" checked="" type="radio" name="card" id="in2" value="SI" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
<input class="form-check-input" type="radio" name="card" id="in2" value="NO" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<?php }else{?>
<td><div class="form-check">
<input class="form-check-input" type="radio" name="card" id="in2" value="SI" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
<input class="form-check-input" checked="" type="radio" name="card" id="in2" value="NO" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<?php } ?>
    </tr>
     <tr>
      <td>ENDÓCRINOS</td>
<?php if ($row['tend']=="SI") {?>
<td><div class="form-check">
<input class="form-check-input" checked="" type="radio" name="end" id="in2" value="SI" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
<input class="form-check-input" type="radio" name="end" id="in2" value="NO" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<?php }else{?>
<td><div class="form-check">
<input class="form-check-input" type="radio" name="end" id="in2" value="SI" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
<input class="form-check-input" checked="" type="radio" name="end" id="in2" value="NO" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<?php } ?>
    </tr>
     <tr>
      <td>REUMÁTICOS</td>
<?php if ($row['reu']=="SI") {?>
<td><div class="form-check">
<input class="form-check-input" type="radio" checked="" name="reu" id="in2" value="SI" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
<input class="form-check-input" type="radio" name="reu" id="in2" value="NO" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<?php }else{?>
<td><div class="form-check">
<input class="form-check-input" type="radio" name="reu" id="in2" value="SI" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
<input class="form-check-input" type="radio" checked="" name="reu" id="in2" value="NO" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<?php } ?>
    </tr>
   
     <tr>
      <td>NEOPLASICOS</td>
<?php if ($row['neo']=="SI") {?>
<td><div class="form-check">
<input class="form-check-input" type="radio" checked="" name="neo" id="in2" value="SI" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
<input class="form-check-input" type="radio" name="neo" id="in2" value="NO" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<?php }else{?>
<td><div class="form-check">
<input class="form-check-input" type="radio" name="neo" id="in2" value="SI" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
<input class="form-check-input" type="radio" checked="" name="neo" id="in2" value="NO" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<?php } ?>
    </tr>
     <tr>
      <td>HEMATOLOGICOS</td>
<?php if ($row['herma']=="SI") {?>
<td><div class="form-check">
<input class="form-check-input" type="radio" checked="" name="herma" id="in2" value="SI" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
<input class="form-check-input" type="radio" name="herma" id="in2" value="NO" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<?php }else{?>
<td><div class="form-check">
<input class="form-check-input" type="radio" name="herma" id="in2" value="SI" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
<input class="form-check-input" type="radio" checked="" name="herma" id="in2" value="NO" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<?php } ?>
    </tr>
     <tr>
      <td>TRAUMÁTICOS</td>
<?php if ($row['trau']=="SI") {?>
<td><div class="form-check">
<input class="form-check-input" type="radio" checked="" name="trau" id="in2" value="SI" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
<input class="form-check-input" type="radio" name="trau" id="in2" value="NO" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<?php }else{?>
<td><div class="form-check">
<input class="form-check-input" type="radio" name="trau" id="in2" value="SI" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
<input class="form-check-input" type="radio" checked="" name="trau" id="in2" value="NO" required>
<label class="form-check-label" for="in2"></label>
</div></td>
<?php } ?>
    </tr>
     <tr>
      <td>PSIQUIÁTRICOS</td>
<?php if ($row['psi']=="SI") {?>
<td><div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="psi" id="in2" value="SI" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
  <input class="form-check-input" type="radio" name="psi" id="in2" value="NO" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<?php }else{?>
<td><div class="form-check">
  <input class="form-check-input" type="radio" name="psi" id="in2" value="SI" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="psi" id="in2" value="NO" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<?php } ?>
    </tr>
      <tr>
      <td>QUIRÚRGICOS</td>
<?php if ($row['quir']=="SI") {?>
<td><div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="quir" id="in2" value="SI" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
  <input class="form-check-input" type="radio" name="quir" id="in2" value="NO" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<?php }else{?>
<td><div class="form-check">
  <input class="form-check-input" type="radio" name="quir" id="in2" value="SI" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="quir" id="in2" value="NO" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<?php } ?>
    </tr>
      <tr>
      <td>ANESTÉSICOS</td>
<?php if ($row['aneste']=="SI") {?>
<td><div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="aneste" id="in2" value="SI" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
  <input class="form-check-input" type="radio" name="aneste" id="in2" value="NO" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<?php }else{?>
<td><div class="form-check">
  <input class="form-check-input" type="radio" name="aneste" id="in2" value="SI" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="aneste" id="in2" value="NO" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<?php } ?>
    </tr>
      <tr>
      <td>GINECO-OBSTÉTRICOS</td>
<?php if ($row['gin']=="SI") {?>
<td><div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="gin" id="in2" value="SI" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
  <input class="form-check-input" type="radio" name="gin" id="in2" value="NO" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<?php }else{?>
<td><div class="form-check">
  <input class="form-check-input" type="radio" name="gin" id="in2" value="SI" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="gin" id="in2" value="NO" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<?php } ?>
    </tr>
      <tr>
      <td>PEDIÁTRICOS</td>
<?php if ($row['ped']=="SI") {?>
<td><div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="ped" id="in2" value="SI" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
  <input class="form-check-input" type="radio" name="ped" id="in2" value="NO" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<?php }else{?>
<td><div class="form-check">
  <input class="form-check-input" type="radio" name="ped" id="in2" value="SI" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="ped" id="in2" value="NO" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<?php } ?>    
    </tr>
    <th>ANTECEDENTES NEUROLÓGICOS</th>
     <tr>
      <td>CONSCIENTE</td>
<?php if ($row['cons']=="SI") {?>
<td><div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="cons" id="in2" value="SI" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
  <input class="form-check-input" type="radio" name="cons" id="in2" value="NO" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<?php }else{?>
<td><div class="form-check">
  <input class="form-check-input" type="radio" name="cons" id="in2" value="SI" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="cons" id="in2" value="NO" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<?php } ?>       
    </tr>
     <tr>
      <td>OTRO</td>
<?php if ($row['otro']=="SI") {?>
<td><div class="form-check">
  <input class="form-check-input" checked="" type="radio" name="otro" id="in2" value="SI" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
  <input class="form-check-input" type="radio" name="otro" id="in2" value="NO" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<?php }else{?>
<td><div class="form-check">
  <input class="form-check-input" type="radio" name="otro" id="in2" value="SI" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<td><div class="form-check">
  <input class="form-check-input" checked="" type="radio" name="otro" id="in2" value="NO" required>
  <label class="form-check-label" for="in2"></label>
</div></td>
<?php } ?>     
    </tr>
  </tbody>
</table>
  </div>

  <div class="col">
    <center><h6><strong>VALORACIÓN DE ANTECEDENTES:</strong></h6></center>
     <div class="form-group">
 <textarea class="form-control" name="valant" rows="7"><?php echo $row['valant'] ?></textarea>
  </div>
 <center><h6><strong>PADECIMIENTO ACTUAL:</strong></h6></center>
   <div class="form-group">
    <input type="text" name="pad_act" class="form-control" value="<?php echo $row['pad_act'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>

<center><h6><strong>MEDICACIÓN ACTUAL:</strong></h6></center>
   <div class="form-group">
   <input type="text" name="med_act" class="form-control" value="<?php echo $row['med_act'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
<div class="row">
  <div class="col-12">
    <h6><strong>AYUNO:</strong></h6>
    <div class="form-group">
   <input type="text"  class="form-control" style="text-transform:uppercase;" name="ayuno" value="<?php echo $row['ayuno'] ?>" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
 </div>
</div>
  <div class="col"><h6><strong>ESPECIFICAR (OTRO):</strong></h6>
  <div class="form-group">
   <input type="text" class="form-control" style="text-transform:uppercase;" name="esp" value="<?php echo $row['esp'] ?>" onkeyup="javascript:this.value=this.value.toUpperCase();">
 </div>
 </div>
</div>
  </div>
</div>

<hr>
<center><h6><strong>EXPLORACIÓN FÍSICA</strong></h6></center>
<div class="row">
  <div class="col"><strong><br>SIGNOS VITALES</strong></div>
    <div class="row">
      <div class="col">
      TA:<br><input type="number" name="ta_sisto" placeholder="mmHg"  id="p_sistolica"  value="<?php echo $row['ta_sisto'] ?>"   required> / <label for="p_sistolica"><input type="number" name="ta_diasto"   placeholder="mmHg" id="p_diastolica" value="<?php echo $row['ta_diasto'] ?>" required>
      </div>
    </div>
  <div class="col">FC:<input type="text" name="fc" class="form-control" placeholder="FC:" value="<?php echo $row['fc'] ?>" required></div>
  <div class="col">FR:<input type="text" name="fr" class="form-control" placeholder="FR:" value="<?php echo $row['fr'] ?>" required></div>
  <div class="col">TEMP:<input type="text" name="tempe" class="form-control" placeholder="TEMP:" value="<?php echo $row['tempe'] ?>" required></div>


<div class="col losInput">PESO:<input type="text" name="pes" class="form-control" placeholder="PESO:" value="<?php echo $row['pes'] ?>" required id="pes" minlength="2" maxlength="7"></div>
<div class="col losInput">TALLA (m):<input type="text" name="tall" class="form-control" placeholder="TALLA:" value="<?php echo $row['tall'] ?>" required id="tall" maxlength="4"></div>

<div class="col inputTotal">IMC:<input type="text" name="imc" class="form-control" placeholder="IMC:" value="<?php echo $row['imc'] ?>" required id="imc" disabled="">
</div>

</div>


            <hr>
<div class="row">
  <div class="col col-2"><strong><br><br>VÍA ÁREA</strong></div>
  <div class="col"><br>MALLAMPATI<input type="text" name="malla" class="form-control" value="<?php echo $row['malla'] ?>" required></div>
  <div class="col"><br>PATIL ALDRETI<input type="text" name="patil" class="form-control" value="<?php echo $row['patil'] ?>" required></div>
  <div class="col"><br>BELLHOUSE-DORE<input type="text" name="bell" class="form-control" value="<?php echo $row['bell'] ?>" required></div>
  <div class="col">DISTANCIA ESTEMOMENTONIANA<input type="text" name="dist" class="form-control" value="<?php echo $row['dist'] ?>" required ></div>
  <div class="col"><br>BUCO-DENTAL<input type="text" name="buco" class="form-control" value="<?php echo $row['buco'] ?>" required></div>
</div>
                    OBSERVACIONES<input type="text" name="obserb" class="form-control" value="<?php echo $row['obserb'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
        <hr>

  <div class="row">
    <div class="col-sm-3">
   <br><hr>FECHA <input type="date" name="fecha" class="form-control" value="<?php echo $row['fecha'] ?>" required>
    </div>
  <div class="col-sm-9">
   <center><strong>LABORATORIO</strong></center><hr>
    <div class="row">
  <div class="col">HB<input type="text" name="hb" class="form-control" value="<?php echo $row['hb'] ?>" required></div>
  <div class="col">HTO<input type="text" name="hto" class="form-control" value="<?php echo $row['hto'] ?>" required></div>
  <div class="col">GB<input type="text" name="gb" class="form-control" value="<?php echo $row['gb'] ?>" required></div>
  <div class="col">GR<input type="text" name="gr" class="form-control" value="<?php echo $row['gr'] ?>" required></div>
  <div class="col">PLAQ<input type="text" name="plaq" class="form-control" value="<?php echo $row['plaq'] ?>" required></div>
  <div class="col">TP<input type="text" name="tp" class="form-control" value="<?php echo $row['tp'] ?>" required></div>
  <div class="col">TPT<input type="text" name="tpt" class="form-control" value="<?php echo $row['tpt'] ?>" required></div>
  
</div>
  <div class="row">
  <div class="col">INR<input type="text" name="inr" class="form-control"  value="<?php echo $row['inr'] ?>" required></div>
  <div class="col">GLUC<input type="text" name="gluc" class="form-control"  value="<?php echo $row['gluc'] ?>" required></div>
  <div class="col">CR<input type="text" name="cr" class="form-control" value="<?php echo $row['cr'] ?>" required></div>
  <div class="col">BUN<input type="text" name="bun" class="form-control"  value="<?php echo $row['bun'] ?>" required></div>
  <div class="col">UREA<input type="text" name="urea" class="form-control "  value="<?php echo $row['urea'] ?>" required></div>
  <div class="col">E.S.<input type="text" name="es" class="form-control"  value="<?php echo $row['es'] ?>" required></div>
  
</div>
  <div class="row">
  <div class="col">OTROS<input type="text" name="otros" class="form-control" value="<?php echo $row['otros'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>

  
</div>

    </div>

</div>

<hr>
<div class="row">
  <div class="col"><strong>GABINETE: </strong><input required type="text" name="gab" value="<?php echo $row['gab'] ?>" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
  <div class="col"><strong>VALORACIÓN CARDIOVASCULAR: </strong><input required type="text" value="<?php echo $row['valcard'] ?>" name="valcard" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
</div><br>
<div class="row">
  <div class="col"><strong>CONDICIÓN FÍSICA ASA: </strong><input required type="text" value="<?php echo $row['condfis'] ?>" name="condfis" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
  <div class="col"><strong>TIPO DE ANESTESIA PROPUESTA: </strong><input required type="text" value="<?php echo $row['tipanest'] ?>" name="tipanest" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
</div><br>
<div class="row">
  <div class="col"><strong>INDICACIÓN PREANESTÉSICA: </strong><input required type="text" value="<?php echo $row['indpre'] ?>" name="indpre" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
  <div class="col"><strong>OBSERVACIONES: </strong><input required type="text" name="obs" value="<?php echo $row['obs'] ?>" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
</div>
<br>
<!--<strong>NOMBRE COMPLETO DEL RESIDENTE QUE COLABORA EN LA VALORACIÓN: </strong><input required type="text" name="nomres" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">

 <strong>NOMBRE COMPLETO DEL ANESTÉSIOLOGO: </strong><input required type="text" name="nomanest" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
 -->
    <?php 
$date=date_create($row['fecha_nota']);
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
    $id_usua=$row['id_usua'];
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
<hr>


 
<center>
                       <div class="form-group col-6">
                            <button type="submit" name="guardar" class="btn btn-primary">FIRMAR</button>
                            <button type="button" class="btn btn-danger" onclick="history.back()">CANCELAR</button>
                        </div>
</center>
                        <br>
                        </form>
</div> <!--TERMINO DE NOTA MEDICA div container-->

  </div>
 <?php 
  if (isset($_POST['guardar'])) {

        $inter    = mysqli_real_escape_string($conexion, (strip_tags($_POST["inter"], ENT_QUOTES))); //Escanpando caracteres
        $anest    = mysqli_real_escape_string($conexion, (strip_tags($_POST["anest"], ENT_QUOTES))); //Escanpando caracteres
        $inmun    = mysqli_real_escape_string($conexion, (strip_tags($_POST["inmun"], ENT_QUOTES))); //Escanpando caracteres
        $tab  = mysqli_real_escape_string($conexion, (strip_tags($_POST["tab"], ENT_QUOTES)));
        $alc    = mysqli_real_escape_string($conexion, (strip_tags($_POST["alc"], ENT_QUOTES))); //Escanpando caracteres
        $trans    = mysqli_real_escape_string($conexion, (strip_tags($_POST["trans"], ENT_QUOTES)));
        $alerg    = mysqli_real_escape_string($conexion, (strip_tags($_POST["alerg"], ENT_QUOTES)));
        $toxi    = mysqli_real_escape_string($conexion, (strip_tags($_POST["toxi"], ENT_QUOTES)));
        $gastro    = mysqli_real_escape_string($conexion, (strip_tags($_POST["gastro"], ENT_QUOTES)));
        $neuro    = mysqli_real_escape_string($conexion, (strip_tags($_POST["neuro"], ENT_QUOTES)));
        $neumo    = mysqli_real_escape_string($conexion, (strip_tags($_POST["neumo"], ENT_QUOTES)));
        $ren    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ren"], ENT_QUOTES)));
        $card    = mysqli_real_escape_string($conexion, (strip_tags($_POST["card"], ENT_QUOTES)));
        $tend    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tend"], ENT_QUOTES)));
        $reu    = mysqli_real_escape_string($conexion, (strip_tags($_POST["reu"], ENT_QUOTES)));

        $neo    = mysqli_real_escape_string($conexion, (strip_tags($_POST["neo"], ENT_QUOTES))); //Escanpando caracteres
        $herma    = mysqli_real_escape_string($conexion, (strip_tags($_POST["herma"], ENT_QUOTES))); //Escanpando caracteres
        $trau    = mysqli_real_escape_string($conexion, (strip_tags($_POST["trau"], ENT_QUOTES))); //Escanpando caracteres
        $psi  = mysqli_real_escape_string($conexion, (strip_tags($_POST["psi"], ENT_QUOTES)));
        $quir    = mysqli_real_escape_string($conexion, (strip_tags($_POST["quir"], ENT_QUOTES))); //Escanpando caracteres
        $aneste    = mysqli_real_escape_string($conexion, (strip_tags($_POST["aneste"], ENT_QUOTES)));
        $gin    = mysqli_real_escape_string($conexion, (strip_tags($_POST["gin"], ENT_QUOTES)));
        $ped    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ped"], ENT_QUOTES)));
        $valant    = mysqli_real_escape_string($conexion, (strip_tags($_POST["valant"], ENT_QUOTES)));
        $cons    = mysqli_real_escape_string($conexion, (strip_tags($_POST["cons"], ENT_QUOTES)));
        $pad_act    = mysqli_real_escape_string($conexion, (strip_tags($_POST["pad_act"], ENT_QUOTES)));
        $med_act    = mysqli_real_escape_string($conexion, (strip_tags($_POST["med_act"], ENT_QUOTES)));
        $ayuno    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ayuno"], ENT_QUOTES)));
        $otro    = mysqli_real_escape_string($conexion, (strip_tags($_POST["otro"], ENT_QUOTES)));
        $esp    = mysqli_real_escape_string($conexion, (strip_tags($_POST["esp"], ENT_QUOTES)));

        $ta_sisto    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ta_sisto"], ENT_QUOTES))); //Escanpando caracteres
        $ta_diasto    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ta_diasto"], ENT_QUOTES))); //Escanpando caracteres
        $fc    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fc"], ENT_QUOTES))); //Escanpando caracteres
        $fr  = mysqli_real_escape_string($conexion, (strip_tags($_POST["fr"], ENT_QUOTES)));
        $tempe    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tempe"], ENT_QUOTES))); //Escanpando caracteres
        $pes    = mysqli_real_escape_string($conexion, (strip_tags($_POST["pes"], ENT_QUOTES)));
        $tall    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tall"], ENT_QUOTES)));
        $imc    = mysqli_real_escape_string($conexion, (strip_tags($_POST["imc"], ENT_QUOTES)));
        $malla    = mysqli_real_escape_string($conexion, (strip_tags($_POST["malla"], ENT_QUOTES)));
        $patil    = mysqli_real_escape_string($conexion, (strip_tags($_POST["patil"], ENT_QUOTES)));
        $bell    = mysqli_real_escape_string($conexion, (strip_tags($_POST["bell"], ENT_QUOTES)));
        $dist    = mysqli_real_escape_string($conexion, (strip_tags($_POST["dist"], ENT_QUOTES)));
        $buco    = mysqli_real_escape_string($conexion, (strip_tags($_POST["buco"], ENT_QUOTES)));
        $obserb    = mysqli_real_escape_string($conexion, (strip_tags($_POST["obserb"], ENT_QUOTES)));
        $fecha    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha"], ENT_QUOTES)));

        $hb    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hb"], ENT_QUOTES))); //Escanpando caracteres
        $hto    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hto"], ENT_QUOTES))); //Escanpando caracteres
        $gb    = mysqli_real_escape_string($conexion, (strip_tags($_POST["gb"], ENT_QUOTES))); //Escanpando caracteres
        $gr  = mysqli_real_escape_string($conexion, (strip_tags($_POST["gr"], ENT_QUOTES)));
        $plaq    = mysqli_real_escape_string($conexion, (strip_tags($_POST["plaq"], ENT_QUOTES))); //Escanpando caracteres
        $tp    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tp"], ENT_QUOTES)));
        $tpt    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tpt"], ENT_QUOTES)));
        $inr    = mysqli_real_escape_string($conexion, (strip_tags($_POST["inr"], ENT_QUOTES)));
        $gluc    = mysqli_real_escape_string($conexion, (strip_tags($_POST["gluc"], ENT_QUOTES)));
        $cr    = mysqli_real_escape_string($conexion, (strip_tags($_POST["cr"], ENT_QUOTES)));
        $bun    = mysqli_real_escape_string($conexion, (strip_tags($_POST["bun"], ENT_QUOTES)));
        $urea    = mysqli_real_escape_string($conexion, (strip_tags($_POST["urea"], ENT_QUOTES)));
        $es    = mysqli_real_escape_string($conexion, (strip_tags($_POST["es"], ENT_QUOTES)));
        $otros    = mysqli_real_escape_string($conexion, (strip_tags($_POST["otros"], ENT_QUOTES)));
        $gab    = mysqli_real_escape_string($conexion, (strip_tags($_POST["gab"], ENT_QUOTES)));

        $valcard    = mysqli_real_escape_string($conexion, (strip_tags($_POST["valcard"], ENT_QUOTES))); //Escanpando caracteres
        $condfis    = mysqli_real_escape_string($conexion, (strip_tags($_POST["condfis"], ENT_QUOTES))); //Escanpando caracteres
        $tipanest    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tipanest"], ENT_QUOTES))); //Escanpando caracteres
        $indpre  = mysqli_real_escape_string($conexion, (strip_tags($_POST["indpre"], ENT_QUOTES)));
        $obs    = mysqli_real_escape_string($conexion, (strip_tags($_POST["obs"], ENT_QUOTES))); //Escanpando caracteres
        $nomanest    = mysqli_real_escape_string($conexion, (strip_tags($_POST["nomanest"], ENT_QUOTES)));

        $fecha= mysqli_real_escape_string($conexion,(strip_tags($_POST["fecha"], ENT_QUOTES)));
        $hora= mysqli_real_escape_string($conexion,(strip_tags($_POST["hora"], ENT_QUOTES)));
        $medico= mysqli_real_escape_string($conexion,(strip_tags($_POST["medico"], ENT_QUOTES)));

        $merge = $fecha.' '.$hora;

        
        

date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

        $sql2 = "UPDATE dat_peri_anest SET id_usua='$medico',fecha_nota='$merge',inter='$inter', anest='$anest', inmun='$inmun', tab ='$tab', alc='$alc', trans='$trans', alerg='$alerg', toxi='$toxi', gastro='$gastro', neuro='$neuro', neumo='$neumo', ren='$ren', card='$card', tend='$tend', reu='$reu',neo='$neo', herma='$herma', trau='$trau', psi ='$psi', quir='$quir', aneste='$aneste', gin='$gin', ped='$ped', valant='$valant', cons='$cons', pad_act='$pad_act', med_act='$med_act', ayuno='$ayuno', otro='$otro', esp='$esp',ta_sisto='$ta_sisto', ta_diasto='$ta_diasto', fc='$fc', fr ='$fr', tempe='$tempe', pes='$pes', tall='$tall', imc='$imc', malla='$malla', patil='$patil', bell='$bell', dist='$dist', buco='$buco', obserb='$obserb', fecha='$fecha',hb='$hb', hto='$hto', gb='$gb', gr ='$gr', plaq='$plaq', tp='$tp', tpt='$tpt', inr='$inr', gluc='$gluc', cr='$cr', bun='$bun', urea='$urea', es='$es', otros='$otros', gab='$gab',valcard='$valcard', condfis='$condfis', tipanest='$tipanest', indpre ='$indpre', obs='$obs', nomanest='$nomanest' WHERE id_peranest= $id_peranest";
        $result = $conexion->query($sql2);



        echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> DATO EDITADO CORRECTAMENTE...</p>";
        echo '<script type="text/javascript">window.location ="../cartas_consentimientos/consent_medico.php"</script>';
      }
  ?>
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

$('.losInput input').on('change', function(){
  var total = 0;
  var pes, tall, imc;
   pes=document.getElementById("pes").value;
  tall=document.getElementById("tall").value;
  $('.losInput input').each(function() {
    if($( this ).val() != "")
    {
        parseInt(total=pes/(tall*tall));
    }
  });
   
  $('.inputTotal input').val(total.toFixed(2));

});

</script>


</body>
</html>