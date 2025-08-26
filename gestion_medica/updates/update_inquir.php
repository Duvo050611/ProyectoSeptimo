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
      <?php  }  ?> 
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
      } ?>
      HABITACIÓN : <td><strong><?php echo $cama; ?></strong></td> 
      <?php  }  ?>
    </div>
  </div>
</div>
<hr> 

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
                
        <?php 
$id_not_inquir=$_GET['id_not_inquir'];
$alta="SELECT * FROM dat_not_inquir where id_not_inquir=$id_not_inquir";
$result=$conexion->query($alta);
while ($row=$result->fetch_assoc()) {
  if($row['tipo_intervencion']=="URGENCIA"){
 ?>                  
  <div class="row">
    <div class="col-sm">
      <strong><p>TIPO DE INTERVENCIÓN:</p></strong>
     <div class="form-check">
  <input class="form-check-input" type="radio" name="tipo_intervencion" checked="" id="URGENCIAS" value="URGENCIA" name="tipocir" required="">
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
  <?php }elseif ($row['tipo_intervencion']=="ELECTIVA") {?>
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
  <input class="form-check-input" type="radio" id="electiva" checked="" name="tipo_intervencion" value="ELECTIVA" name="tipocir">
  <label class="form-check-label" for="ELECTIVA">
    ELECTIVA
  </label>
</div>
    </div>
    
  </div>
  <hr>
  <?php } 
}?> 
  



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
        <?php 
$id_not_inquir=$_GET['id_not_inquir'];
$alta="SELECT * FROM dat_not_inquir where id_not_inquir=$id_not_inquir";
$result=$conexion->query($alta);
while ($row=$result->fetch_assoc()) {
  $id_usua=$row['id_usua'];
 ?>  
<hr>
 <div class="container">
<div class="row">

  <div class="col"><strong><center>SANGRE EN</center></strong><hr>
    
  <div class="row">
    <div class="col-sm">
      <label for="exampleFormControlInput1">QUIROFANO:</label>
    <input type="number" class="form-control" name="quirofano" value="<?php echo $row['quirofano'] ?>" required id="exampleFormControlInput1" placeholder="ml." style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    </div>
    <div class="col-sm">
      <label for="exampleFormControlInput1">RESERVA:</label>
    <input type="number" class="form-control" name="reserva" value="<?php echo $row['reserva'] ?>" id="exampleFormControlInput1" required placeholder="ml." style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    </div>
  </div>

<hr>
  </div>

  <div class="col"><strong><center>ANESTESIA</center></strong><hr>
    <?php if ($row['local']!="SI" && $row['general']!="SI" && $row['regional']!="SI"){?> 
<div class="row">
    <div class="col-sm-4">
    <label for="exampleFormControlInput1">LOCAL</label>
    <input type="checkbox"  name="local"  value="SI" id="exampleFormControlInput1" > 
    </div>
    <div class="col-sm-4">
      <label for="exampleFormControlInput1">REGIONAL</label>
    <input type="checkbox"  name="regional" value="SI" id="exampleFormControlInput1"> 
    </div>
    <div class="col-sm-4">
      <label for="exampleFormControlInput1">GENERAL</label>
    <input type="checkbox" name="general"  value="SI" id="exampleFormControlInput1" >
    </div>
  </div>
  <?php }elseif ($row['local']=="SI" && $row['general']=="SI" && $row['regional']=="SI"){?> 
<div class="row">
    <div class="col-sm-4">
    <label for="exampleFormControlInput1">LOCAL</label>
    <input type="checkbox"  name="local" checked="" value="SI" id="exampleFormControlInput1" > 
    </div>
    <div class="col-sm-4">
      <label for="exampleFormControlInput1">REGIONAL</label>
    <input type="checkbox"  name="regional" checked="" value="SI" id="exampleFormControlInput1"> 
    </div>
    <div class="col-sm-4">
      <label for="exampleFormControlInput1">GENERAL</label>
    <input type="checkbox" name="general" checked="" value="SI" id="exampleFormControlInput1" >
    </div>
  </div>
<?php }elseif ($row['local']=="SI" && $row['regional']=="SI"){?>  
<div class="row">
    <div class="col-sm-4">
    <label for="exampleFormControlInput1">LOCAL</label>
    <input type="checkbox"  name="local" checked="" value="SI" id="exampleFormControlInput1" > 
    </div>
    <div class="col-sm-4">
      <label for="exampleFormControlInput1">REGIONAL</label>
    <input type="checkbox"  name="regional" checked="" value="SI" id="exampleFormControlInput1"> 
    </div>
    <div class="col-sm-4">
      <label for="exampleFormControlInput1">GENERAL</label>
    <input type="checkbox" name="general" value="SI" id="exampleFormControlInput1" >
    </div>
  </div>
<?php }elseif ($row['local']=="SI" && $row['general']=="SI"){?>  
<div class="row">
    <div class="col-sm-4">
    <label for="exampleFormControlInput1">LOCAL</label>
    <input type="checkbox"  name="local" checked="" value="SI" id="exampleFormControlInput1" > 
    </div>
    <div class="col-sm-4">
      <label for="exampleFormControlInput1">REGIONAL</label>
    <input type="checkbox"  name="regional"  value="SI" id="exampleFormControlInput1"> 
    </div>
    <div class="col-sm-4">
      <label for="exampleFormControlInput1">GENERAL</label>
    <input type="checkbox" name="general" checked="" value="SI" id="exampleFormControlInput1" >
    </div>
  </div>
<?php }elseif ($row['general']=="SI" && $row['regional']=="SI"){?>  
<div class="row">
    <div class="col-sm-4">
    <label for="exampleFormControlInput1">LOCAL</label>
    <input type="checkbox"  name="local"  value="SI" id="exampleFormControlInput1" > 
    </div>
    <div class="col-sm-4">
      <label for="exampleFormControlInput1">REGIONAL</label>
    <input type="checkbox"  name="regional" checked="" value="SI" id="exampleFormControlInput1"> 
    </div>
    <div class="col-sm-4">
      <label for="exampleFormControlInput1">GENERAL</label>
    <input type="checkbox" name="general" checked="" value="SI" id="exampleFormControlInput1" >
    </div>
  </div>
<?php }elseif($row['local']=="SI"){ ?>
  <div class="row">
    <div class="col-sm-4">
    <label for="exampleFormControlInput1">LOCAL</label>
    <input type="checkbox"  name="local" value="SI" checked="" id="exampleFormControlInput1" > 
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
<?php }elseif ($row['regional']=="SI"){?>
<div class="row">
    <div class="col-sm-4">
    <label for="exampleFormControlInput1">LOCAL</label>
    <input type="checkbox"  name="local" value="SI" id="exampleFormControlInput1" > 
    </div>
    <div class="col-sm-4">
      <label for="exampleFormControlInput1">REGIONAL</label>
    <input type="checkbox"  name="regional" checked="" value="SI" id="exampleFormControlInput1"> 
    </div>
    <div class="col-sm-4">
      <label for="exampleFormControlInput1">GENERAL</label>
    <input type="checkbox" name="general" value="SI" id="exampleFormControlInput1" >
    </div>
  </div>
<?php }elseif ($row['general']=="SI") {?>
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
    <input type="checkbox" name="general" checked="" value="SI" id="exampleFormControlInput1" >
    </div>
  </div>    
  <hr>
  </div>
  <?php } 
}?>
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
<?php 
$id_not_inquir=$_GET['id_not_inquir'];
$alta="SELECT * FROM dat_not_inquir where id_not_inquir=$id_not_inquir";
$result=$conexion->query($alta);
while ($row=$result->fetch_assoc()) {
 ?>  
<div class="container">
  <div class="row">
    <div class="col-sm">
      <label for="exampleFormControlTextarea1"><strong>CUENTA DE GASAS Y COMPRESAS</strong></label>
      <select class="form-control" aria-label="CUENTA DE GASAS Y COMPRESAS" name="inst_necesario">
        <option value="<?php echo $row['inst_necesario'] ?>"><?php echo $row['inst_necesario'] ?></option>
        <option></option>
       <option disabled="">CUENTA DE GASAS Y COMPRESAS</option>
       <option value="COMPLETA">COMPLETA</option>
       <option value="INCOMPLETA">INCOMPLETA</option>
      </select>
    </div>
    <div class="col-sm">
      <label for="exampleFormControlTextarea5"><strong>OBSERVACIONES</strong></label>
      <textarea name="medmat_necesario" class="form-control" rows="5"><?php echo $row['medmat_necesario'] ?></textarea>
    </div>
  </div>
</div>
<?php } ?>
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
      <label for="exampleFormControlInput1"><strong>INTERVENCIÓN</strong></label>
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
<?php 
$id_not_inquir=$_GET['id_not_inquir'];
$alta="SELECT * FROM dat_not_inquir where id_not_inquir=$id_not_inquir";
$result=$conexion->query($alta);
while ($row=$result->fetch_assoc()) {
 ?>  
<hr>
<div class="container">
  <div class="row">
    <div class="col-sm-5">
      <label for="exampleFormControlTextarea1"><strong>DIAGNÓSTICO POSTOPERATORIO:</strong></label>
      <input type="text" name="diag_postop" class="form-control" value="<?php echo $row['diag_postop'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    </div>
    <div class="col-sm-5">
      <label for="exampleFormControlTextarea1"><strong>CIRUGÍA REALIZADA:</strong></label>
      <textarea name="cir_realizada" class="form-control" rows="5"><?php echo $row['cir_realizada'] ?></textarea>
    </div>
    <div class="col-2">
      <div class="form-group">
        <label for="exampleFormControlInput1"><strong>INICIO</strong></label>
        <input type="time" class="form-control" name="inicio"   value="<?php echo $row['inicio'] ?>" id="exampleFormControlInput1" required>
      </div>
      <div class="form-group">
        <label for="exampleFormControlInput1"><strong>TÉRMINO</strong></label>
        <input type="time" class="form-control" name="termino" value="<?php echo $row['termino'] ?>"  id="exampleFormControlInput1" required >
      </div>
    </div>
 </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-sm">
       <label for="exampleFormControlTextarea1"><center><strong>ESTUDIOS DE PATOLOGÍA TRANSOPERATORÍA Y POSTOPERATORÍA</strong></center></label>
      <div class="row">
               <div class="col-sm-6">
                <div class="form-group">
                  <label>TRANSOPERATORIOS</label>
                <textarea name="trans" class="form-control" rows="5"><?php echo $row['trans'] ?></textarea>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label>POSTOPERATORIOS</label>
                  <textarea name="posto" class="form-control" rows="5"><?php echo $row['posto'] ?></textarea>
                     </div>
                </div>
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
    <input type="text" class="form-control" name="perd_hema" value="<?php echo $row['perd_hema'] ?>" id="exampleFormControlInput1" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
    </div>
    <div class="col-sm">
     <div class="form-group">
    <label for="exampleFormControlInput1"><strong>ACCIDENTES O INCIDENTES</strong></label>
    <input type="text" class="form-control" name="accident_incident" value="<?php echo $row['accident_incident'] ?>" id="exampleFormControlInput1" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
    </div>
     <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlInput1"><strong>ANESTESIA ADMINISTRADA</strong></label>
    <input type="text" class="form-control" name="anestesia_admin" value="<?php echo $row['anestesia_admin'] ?>" id="exampleFormControlInput1" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
    </div>
     <div class="col-sm">
       <div class="form-group">
    <label for="exampleFormControlInput1"><strong>DURACIÓN DE LA ANESTESIA</strong></label>
    <input type="text" class="form-control" name="anestesia_dur" value="<?php echo $row['anestesia_dur'] ?>" id="exampleFormControlInput1" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
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
    <input type="text" class="form-control" name="cirujano" value="<?php echo $row['cirujano'] ?>" id="exampleFormControlInput1" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
  </div>
  <div class="col-sm">
    <div class="form-group">
    <label for="exampleFormControlInput1"><strong>PRIMER AYUDANTE</strong></label>
    <input type="text" class="form-control" name="prim_ayudante" value="<?php echo $row['prim_ayudante'] ?>" id="exampleFormControlInput1" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
  </div>
  <div class="col-sm">
    <div class="form-group">
    <label for="exampleFormControlInput1"><strong>SEGUNDO AYUDANTE</strong></label>
    <input type="text" class="form-control" name="seg_ayudante" value="<?php echo $row['seg_ayudante'] ?>" id="exampleFormControlInput1" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
  </div>
  <div class="col-sm">
    <div class="form-group">
    <label for="exampleFormControlInput1"><strong>TERCER AYUDANTE</strong></label>
    <input type="text" class="form-control" name="ter_ayudante" value="<?php echo $row['ter_ayudante'] ?>" id="exampleFormControlInput1" required style="text-transform:uppercase;"onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
  </div>
</div>
</div>

<div class="container">
  <div class="row">
  <div class="col">
    <div class="form-group">
    <label for="exampleFormControlInput1"><strong>ANESTESIÓLOGO</strong></label>
    <input type="text" class="form-control" name="anestesiologo" value="<?php echo $row['anestesiologo'] ?>" id="exampleFormControlInput1" required style="text-transform:uppercase;"onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
  </div>

  <div class="col">
    <div class="form-group">
    <label for="exampleFormControlInput1"><strong>CIRCULANTE</strong></label>
    <input type="text" class="form-control" name="circulante" value="<?php echo $row['circulante'] ?>" id="exampleFormControlInput1" required style="text-transform:uppercase;"onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
  </div>
  <div class="col">
    <div class="form-group">
    <label for="exampleFormControlInput1"><strong>INSTRUMENTISTA</strong></label>
    <input type="text" class="form-control" name="instrumentista" value="<?php echo $row['instrumentista'] ?>" id="exampleFormControlInput1" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
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
       <label>SALA</label>
       <select id="quir" name="quir" class="form-control">
        <option value="<?php echo $row['quir'] ?>"><?php echo $row['quir'] ?></option>
        <option></option>
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
    <input type="time" class="form-control" name="hora_llegada_quir" value="<?php echo $row['hora_llegada_quir'] ?>" id="exampleFormControlInput1" required>
  </div>
    </div>
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlInput1"><br>HORA SALIDA</label>
    <input type="time" class="form-control" name="hora_salida_quir" value="<?php echo $row['hora_salida_quir'] ?>" id="exampleFormControlInput1" required >
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
    <input type="time" class="form-control" name="hora_entrada_recup" value="<?php echo $row['hora_entrada_recup'] ?>" id="exampleFormControlInput1" required>
  </div>
    </div>
    <div class="col-sm">
     <div class="form-group">
    <label for="exampleFormControlInput1"><br>HORA SALIDA</label>
    <input type="time" class="form-control" name="hora_salida_recup" value="<?php echo $row['hora_salida_recup'] ?>" id="exampleFormControlInput1" required >
  </div>
    </div>
  </div>
</div>
  </div>
 
</div>
<hr>
<div class="container">
<div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>NOTA OPERATORIA:</strong> (HALLAZGOS-TÉCNICA-COMPLICACIONES Y OBSERVACIONES)</label>
    <textarea name="nota_preop" class="form-control" rows="5"><?php echo $row['nota_preop'] ?></textarea>
  </div>
</div>

<hr>
<div class="container">
<?php if ($row['estado_postop']=="BUENO") {?>
  <div class="row">
   <div class="col"><strong>ESTADO POSTOPERATORIO INMEDIATO</strong></div>
  <div class="col">
      <div class="form-check"><br>
  <input class="form-check-input" type="radio" id="URGENCIAS" checked="" name="estado_postop" value="BUENO" name="estado_postop">
  <label class="form-check-label" for="URGENCIAS">
    BUENO
  </label>
</div>
  </div>
  <div class="col">
      <div class="form-check"><br>
  <input class="form-check-input" type="radio" id="URGENCIAS"   name="estado_postop" value="DELICADO" name="estado_postop">
  <label class="form-check-label" for="URGENCIAS">
    DELICADO
  </label>
</div>
  </div>
  <div class="col">
      <div class="form-check"><br>
  <input class="form-check-input" type="radio" name="estado_postop"  id="URGENCIAS" value="GRAVE" name="estado_postop">
  <label class="form-check-label" for="URGENCIAS">
    <label>GRAVE</label>
</div>
  </div>
  <div class="col">
      <div class="form-check"><br>
  <input class="form-check-input" type="radio" name="estado_postop"  id="URGENCIAS" value="MUY GRAVE" name="estado_postop">
  <label class="form-check-label" for="URGENCIAS">
    MUY GRAVE
  </label>
</div>
  </div>
</div>
<?php }elseif ($row['estado_postop']=="DELICADO") {?>
<div class="row">
   <div class="col"><strong>ESTADO POSTOPERATORIO INMEDIATO</strong></div>
  <div class="col">
      <div class="form-check"><br>
  <input class="form-check-input" type="radio" id="URGENCIAS"   name="estado_postop" value="BUENO" name="estado_postop">
  <label class="form-check-label" for="URGENCIAS">
    BUENO
  </label>
</div>
  </div>
  <div class="col">
      <div class="form-check"><br>
  <input class="form-check-input" type="radio" id="URGENCIAS" checked="" name="estado_postop" value="DELICADO" name="estado_postop">
  <label class="form-check-label" for="URGENCIAS">
    DELICADO
  </label>
</div>
  </div>
  <div class="col">
      <div class="form-check"><br>
  <input class="form-check-input" type="radio" name="estado_postop"  id="URGENCIAS" value="GRAVE" name="estado_postop">
  <label class="form-check-label" for="URGENCIAS">
    <label>GRAVE</label>
</div>
  </div>
  <div class="col">
      <div class="form-check"><br>
  <input class="form-check-input" type="radio" name="estado_postop"  id="URGENCIAS" value="MUY GRAVE" name="estado_postop">
  <label class="form-check-label" for="URGENCIAS">
    MUY GRAVE
  </label>
</div>
  </div>
</div>
<?php }elseif ($row['estado_postop']=="GRAVE") {?>
<div class="row">
   <div class="col"><strong>ESTADO POSTOPERATORIO INMEDIATO</strong></div>
  <div class="col">
      <div class="form-check"><br>
  <input class="form-check-input" type="radio" id="URGENCIAS"   name="estado_postop" value="BUENO" name="estado_postop">
  <label class="form-check-label" for="URGENCIAS">
    BUENO
  </label>
</div>
  </div>
  <div class="col">
      <div class="form-check"><br>
  <input class="form-check-input" type="radio" id="URGENCIAS"   name="estado_postop" value="DELICADO" name="estado_postop">
  <label class="form-check-label" for="URGENCIAS">
    DELICADO
  </label>
</div>
  </div>
  <div class="col">
      <div class="form-check"><br>
  <input class="form-check-input" type="radio" name="estado_postop" checked="" id="URGENCIAS" value="GRAVE" name="estado_postop">
  <label class="form-check-label" for="URGENCIAS">
    <label>GRAVE</label>
</div>
  </div>
  <div class="col">
      <div class="form-check"><br>
  <input class="form-check-input" type="radio" name="estado_postop"  id="URGENCIAS" value="MUY GRAVE" name="estado_postop">
  <label class="form-check-label" for="URGENCIAS">
    MUY GRAVE
  </label>
</div>
  </div>
</div>
<?php }elseif ($row['estado_postop']=="MUY GRAVE") {?>
<div class="row">
   <div class="col"><strong>ESTADO POSTOPERATORIO INMEDIATO</strong></div>
  <div class="col">
      <div class="form-check"><br>
  <input class="form-check-input" type="radio" id="URGENCIAS"   name="estado_postop" value="BUENO" name="estado_postop">
  <label class="form-check-label" for="URGENCIAS">
    BUENO
  </label>
</div>
  </div>
  <div class="col">
      <div class="form-check"><br>
  <input class="form-check-input" type="radio" id="URGENCIAS"   name="estado_postop" value="DELICADO" name="estado_postop">
  <label class="form-check-label" for="URGENCIAS">
    DELICADO
  </label>
</div>
  </div>
  <div class="col">
      <div class="form-check"><br>
  <input class="form-check-input" type="radio" name="estado_postop"  id="URGENCIAS" value="GRAVE" name="estado_postop">
  <label class="form-check-label" for="URGENCIAS">
    <label>GRAVE</label>
</div>
  </div>
  <div class="col">
      <div class="form-check"><br>
  <input class="form-check-input" type="radio" name="estado_postop" checked="" id="URGENCIAS" value="MUY GRAVE" name="estado_postop">
  <label class="form-check-label" for="URGENCIAS">
    MUY GRAVE
  </label>
</div>
  </div>
</div>
<?php }?>
</div>
<hr>

<div class="container">
<div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>COMENTARIO FINAL Y PRONÓSTICO:</strong></label>
    <textarea name="comentario_final" class="form-control" rows="5"><?php echo $row['comentario_final'] ?></textarea>
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>PLAN TEREPEÚTICO (POST OPERATORIO):</strong></label>
    <textarea name="plan_tera" class="form-control" rows="5"><?php echo $row['plan_ter'] ?></textarea>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col">
       <div class="form-group">
    <label for="exampleFormControlInput1"><strong>DESCRIBIÓ LA OPERACIÓN</strong></label>
    <input type="text" class="form-control" name="descripcion_op" value="<?php echo $row['descripcion_op'] ?>" id="exampleFormControlInput1" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
    </div>
    <div class="col">
      <?php 
$usuario = $_SESSION['login'];
//$resultado = $conexion->query("SELECT * FROM reg_usuarios WHERE id_usua='" . $usuario . "'") or die($conexion->error);
 ?>

<div class="form-group">
    <label for="exampleFormControlInput1"><strong>NOMBRE DEL MÉDICO CIRUJANO:</strong></label>
    <input type="text" class="form-control" name="nombre_med_cir" value="<?php echo $row['nombre_med_cir'] ?>"  id="exampleFormControlInput1" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" >

  </div>
  </div>
  </div>
</div>

    <?php 
$date=date_create($row['fecha']);
$time=date_create($row['hora_solicitud']);
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
                 <input type="time" name="hora" value="<?php echo date_format($time,"H:i:s") ?>" class="form-control">
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


<hr>

<center>
  <div class="form-group">
      <button type="submit" name="guardar" class="btn btn-primary">FIRMAR</button>
      <button type="button" class="btn btn-danger" onclick="history.back()">CANCELAR</button>
  </div>
</center>

<br>
</form>
</div> <!--TERMINO DE NOTA MEDICA div container-->
                  
</div>
</div>
    <?php 
  if (isset($_POST['guardar'])) {

        $tipo_intervencion    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tipo_intervencion"], ENT_QUOTES))); //Escanpando caracteres
        $quirofano    = mysqli_real_escape_string($conexion, (strip_tags($_POST["quirofano"], ENT_QUOTES))); //Escanpando caracteres
        $reserva   = mysqli_real_escape_string($conexion, (strip_tags($_POST["reserva"], ENT_QUOTES)));

        
        //$regional    = mysqli_real_escape_string($conexion, (strip_tags($_POST["regional"], ENT_QUOTES)));
        //$general    = mysqli_real_escape_string($conexion, (strip_tags($_POST["general"], ENT_QUOTES)));

        if(isset($_POST["local"])){
               $local    = mysqli_real_escape_string($conexion, (strip_tags($_POST["local"], ENT_QUOTES))); //Escanpando caracteres
        }else{
        $local="";
        } 

        if(isset($_POST["regional"])){
               $regional    = mysqli_real_escape_string($conexion, (strip_tags($_POST["regional"], ENT_QUOTES))); //Escanpando caracteres
        }else{
        $regional="";
        } 

         if(isset($_POST["general"])){
               $general    = mysqli_real_escape_string($conexion, (strip_tags($_POST["general"], ENT_QUOTES))); //Escanpando caracteres
        }else{
        $general="";
        } 

        $inst_necesario    = mysqli_real_escape_string($conexion, (strip_tags($_POST["inst_necesario"], ENT_QUOTES)));
        $medmat_necesario    = mysqli_real_escape_string($conexion, (strip_tags($_POST["medmat_necesario"], ENT_QUOTES)));
        $inicio    = mysqli_real_escape_string($conexion, (strip_tags($_POST["inicio"], ENT_QUOTES)));

        $termino    = mysqli_real_escape_string($conexion, (strip_tags($_POST["termino"], ENT_QUOTES))); //Escanpando caracteres
        $diag_postop    = mysqli_real_escape_string($conexion, (strip_tags($_POST["diag_postop"], ENT_QUOTES))); //Escanpando caracteres
        $cir_realizada   = mysqli_real_escape_string($conexion, (strip_tags($_POST["cir_realizada"], ENT_QUOTES)));
        $trans    = mysqli_real_escape_string($conexion, (strip_tags($_POST["trans"], ENT_QUOTES))); //Escanpando caracteres
        $posto    = mysqli_real_escape_string($conexion, (strip_tags($_POST["posto"], ENT_QUOTES)));
        $perd_hema    = mysqli_real_escape_string($conexion, (strip_tags($_POST["perd_hema"], ENT_QUOTES)));
        $accident_incident    = mysqli_real_escape_string($conexion, (strip_tags($_POST["accident_incident"], ENT_QUOTES)));
        $anestesia_admin    = mysqli_real_escape_string($conexion, (strip_tags($_POST["anestesia_admin"], ENT_QUOTES)));
        $anestesia_dur    = mysqli_real_escape_string($conexion, (strip_tags($_POST["anestesia_dur"], ENT_QUOTES)));

        $cirujano    = mysqli_real_escape_string($conexion, (strip_tags($_POST["cirujano"], ENT_QUOTES))); //Escanpando caracteres
        $prim_ayudante    = mysqli_real_escape_string($conexion, (strip_tags($_POST["prim_ayudante"], ENT_QUOTES))); //Escanpando caracteres
        $seg_ayudante   = mysqli_real_escape_string($conexion, (strip_tags($_POST["seg_ayudante"], ENT_QUOTES)));
        $ter_ayudante    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ter_ayudante"], ENT_QUOTES))); //Escanpando caracteres
        $anestesiologo    = mysqli_real_escape_string($conexion, (strip_tags($_POST["anestesiologo"], ENT_QUOTES)));
        $circulante    = mysqli_real_escape_string($conexion, (strip_tags($_POST["circulante"], ENT_QUOTES)));
        $instrumentista    = mysqli_real_escape_string($conexion, (strip_tags($_POST["instrumentista"], ENT_QUOTES)));
        $quir    = mysqli_real_escape_string($conexion, (strip_tags($_POST["quir"], ENT_QUOTES)));
        $hora_llegada_quir    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hora_llegada_quir"], ENT_QUOTES)));

        $hora_salida_quir    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hora_salida_quir"], ENT_QUOTES))); //Escanpando caracteres
        $hora_entrada_recup    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hora_entrada_recup"], ENT_QUOTES))); //Escanpando caracteres
        $hora_salida_recup   = mysqli_real_escape_string($conexion, (strip_tags($_POST["hora_salida_recup"], ENT_QUOTES)));
        $nota_preop    = mysqli_real_escape_string($conexion, (strip_tags($_POST["nota_preop"], ENT_QUOTES))); //Escanpando caracteres
        $estado_postop    = mysqli_real_escape_string($conexion, (strip_tags($_POST["estado_postop"], ENT_QUOTES)));
        $comentario_final    = mysqli_real_escape_string($conexion, (strip_tags($_POST["comentario_final"], ENT_QUOTES)));
         $plan_tera   = mysqli_real_escape_string($conexion, (strip_tags($_POST["plan_tera"], ENT_QUOTES)));
        $descripcion_op    = mysqli_real_escape_string($conexion, (strip_tags($_POST["descripcion_op"], ENT_QUOTES)));
        $nombre_med_cir    = mysqli_real_escape_string($conexion, (strip_tags($_POST["nombre_med_cir"], ENT_QUOTES)));
        $fecha= mysqli_real_escape_string($conexion,(strip_tags($_POST["fecha"], ENT_QUOTES)));
        $hora= mysqli_real_escape_string($conexion,(strip_tags($_POST["hora"], ENT_QUOTES)));
        $medico= mysqli_real_escape_string($conexion,(strip_tags($_POST["medico"], ENT_QUOTES)));
       
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

        $sql2 = "UPDATE dat_not_inquir SET id_usua='$medico',fecha='$fecha',hora_solicitud='$hora',tipo_intervencion='$tipo_intervencion', quirofano='$quirofano', reserva='$reserva', local='$local', regional='$regional', general='$general' , inst_necesario='$inst_necesario', medmat_necesario='$medmat_necesario', inicio='$inicio',termino='$termino', diag_postop='$diag_postop', cir_realizada='$cir_realizada', trans='$trans', posto='$posto', perd_hema='$perd_hema' , accident_incident='$accident_incident', anestesia_admin='$anestesia_admin', anestesia_dur='$anestesia_dur',cirujano='$cirujano', prim_ayudante='$prim_ayudante', seg_ayudante='$seg_ayudante', ter_ayudante='$ter_ayudante', anestesiologo='$anestesiologo', circulante='$circulante' , instrumentista='$instrumentista', quir='$quir', hora_llegada_quir='$hora_llegada_quir',hora_salida_quir='$hora_salida_quir', hora_entrada_recup='$hora_entrada_recup', hora_salida_recup='$hora_salida_recup', nota_preop='$nota_preop', estado_postop='$estado_postop', comentario_final='$comentario_final', plan_ter='$plan_tera'  , descripcion_op='$descripcion_op', nombre_med_cir='$nombre_med_cir' WHERE id_not_inquir= $id_not_inquir";
        $result = $conexion->query($sql2);

        echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> DATO EDITADO CORRECTAMENTE...</p>";
        echo '<script type="text/javascript">window.location ="../cartas_consentimientos/consent_medico.php"</script>';
      }
  ?>
</div></div>
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