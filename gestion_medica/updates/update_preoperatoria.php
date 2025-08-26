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
                    <center><strong>NOTA PREOPERATORIA</strong></center><p>
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
            <?php  } ?> 
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
          <?php  } ?>
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
            
</div>



  
<div class="container">  <!--INICIO DE CONSULTA DE URGENCIAS-->
<form action="" method="POST">
      <?php 
$id_not_preop=$_GET['id_not_preop'];
$alta="SELECT * FROM dat_not_preop where id_not_preop=$id_not_preop";
$result=$conexion->query($alta);
while ($row=$result->fetch_assoc()) {
  $id_usua=$row['id_usua'];
  if($row['tipo_cirugia_preop'] == "URGENCIA"){
 ?> 
  <div class="row">
    <div class="col-sm">
      <strong><p>TIPO DE CIRUGÍA:</p></strong>
      <div class="form-check">
        <input class="form-check-input" type="radio" checked="" id="URGENCIAS" value="URGENCIA" name="tipo_cirugia_preop" required="">
        <label class="form-check-label" for="URGENCIAS">URGENCIA</label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" id="MADRE" value="ELECTIVA" name="tipo_cirugia_preop">
        <label class="form-check-label" for="ELECTIVA">ELECTIVA</label>
      </div>
    </div>
  </div>
  <hr>
<?php }elseif($row['tipo_cirugia_preop'] == "ELECTIVA"){?>
<div class="row">
    <div class="col-sm">
      <strong><p>TIPO DE CIRUGÍA:</p></strong>
      <div class="form-check">
        <input class="form-check-input" type="radio"  id="URGENCIAS" value="URGENCIA" name="tipo_cirugia_preop" required="">
        <label class="form-check-label" for="URGENCIAS">URGENCIA</label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" id="MADRE" checked="" value="ELECTIVA" name="tipo_cirugia_preop">
        <label class="form-check-label" for="ELECTIVA">ELECTIVA</label>
      </div>
    </div>
  </div>
  <hr>
<?php }
} ?>
     
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
      <?php 
$id_not_preop=$_GET['id_not_preop'];
$alta="SELECT * FROM dat_not_preop where id_not_preop=$id_not_preop";
$result=$conexion->query($alta);
while ($row=$result->fetch_assoc()) {
 ?>    
<hr>
<!--
<div class="container">
  <div class="row">
    <div class="col-sm">
    <label for="exampleFormControlTextarea1"><strong>1. RESUMEN DE INTERROGATORIO:</strong></label>
    <textarea name="resumen_clin" class="form-control" rows="5"><?php echo $row['resumen_clin'] ?></textarea>
    </div><p>

   <div class="col-sm">
    <label for="exampleFormControlTextarea1"><strong>2. EXPLORACIÓN FÍSICA:</strong></label>
    <textarea name="beneficios" class="form-control" rows="5"><?php echo $row['beneficios'] ?></textarea>
  </div>
  </div>
</div>  -->

<div class="container">
  <div class="row">
 <!--   <div class="col-sm">
       <label for="exampleFormControlTextarea1"><strong>3.RESULTADO DE LABORATORIO Y GABINETE:</strong></label>
       <textarea name="result_lab_gab" class="form-control" rows="5"><?php echo $row['result_lab_gab'] ?></textarea>
    </div> -->
    <div class="col-sm">
        <label for="id_cie_10"><strong>4. DIAGNÓSTICO PREOPERATORIO:</strong></label>
        <select name="diag_preop" class="form-control" data-live-search="true" id="mibuscador" style="width : 100%; heigth : 100%">
          <option value="<?php echo $row['diag_preop'] ?>"><?php echo $row['diag_preop'] ?></option>
           <option></option>
            <?php
            include "../../conexionbd.php";
            $sql_diag="SELECT * FROM cat_diag ";
            $result_diag=$conexion->query($sql_diag);
            while($row=$result_diag->fetch_assoc()){
                echo "<option value='" . $row['diagnostico'] . "'>" . $row['diagnostico'] . "</option>";}
          ?>
        </select>
    </div>
<?php } ?>
    <!--
    
     -->
  </div>
</div>
<br>
<?php 
$id_not_preop=$_GET['id_not_preop'];
$alta="SELECT * FROM dat_not_preop where id_not_preop=$id_not_preop";
$result=$conexion->query($alta);
while ($row=$result->fetch_assoc()) {
 ?>   
<div class="container">
  <div class="row">
    <div class="col-sm">
       <label for="exampleFormControlTextarea1"><strong>5. PRONÓSTICO PARA LA VIDA Y LA FUNCIÓN</strong></label>
       <textarea name="pronostico" class="form-control" rows="5"><?php echo $row['pronostico'] ?></textarea>
    </div>
    <div class="col-sm">
       <label for="exampleFormControlTextarea1"><strong>6.RIESGOS QUIRÚRGICOS</strong></label>
       <textarea name="riesgos" class="form-control" rows="5"><?php echo $row['riesgos'] ?></textarea>
    </div>  
  </div>
</div>
<br>

<div class="container">
  <div class="row">
    <div class="col-sm">
       <label for="exampleFormControlTextarea1"><strong>7.CUIDADOS Y PLAN TERAPÉUTICO PREOPERATORIO</strong></label>
       <textarea name="cuidados" class="form-control" rows="5"><?php echo $row['cuidados'] ?></textarea>
    </div>
    <div class="col-sm">
      <label for="exampleFormControlTextarea1"><strong>8.CIRUGÍA PROGRAMADA:</strong></label>
      <textarea name="tipo_inter_plan" class="form-control" rows="5"><?php echo $row['tipo_inter_plan'] ?></textarea>
    </div>
  </div>
</div>  
<br>

<div class="container">
  <div class="row">
    <div class="col-sm-3">
      <div class="form-group">
       <label for="exampleFormControlInput1"><strong>8.1 FECHA Y HORA DE CIRUGÍA:</strong></label>
       <input type="date" name="fecha_cir" value="<?php echo $row['fecha_cir'] ?>" class="form-control" id="exampleFormControlInput1"><input type="time" name="hora_cir" value="<?php echo $row['hora_cir'] ?>" class="form-control" id="exampleFormControlInput1">
     </div>
    </div>
    <div class="col-sm-3">
      <div class="form-group">
       <label for="exampleFormControlInput1"><strong>8.2 TIEMPO Qx ESTIMADO:</strong></label>
       <input type="text" class="form-control" value="<?php echo $row['tiempo_estimado'] ?>" name="tiempo_estimado" required id="exampleFormControlInput1" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
      </div>
    </div>
    <div class="col-sm">
      <div class="form-group">
        <label for="exampleFormControlInput1"><strong>8.3 NOMBRE DEL MÉDICO CIRUJANO:</strong></label>
         <input type="text" class="form-control" name="nom_medi_cir" value="<?php echo $row['nom_medi_cir'] ?>" required id="exampleFormControlInput1" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
      </div>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-sm">
        <label for="exampleFormControlTextarea1"><strong>8.4 ANESTESIA SUGERIDA</strong></label>
        <input type="text" name="anestesia_sug" class="form-control" value="<?php echo $row['anestesia_sug'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    </div>
      <div class="col-sm">
     <div class="form-group">
    <label for="exampleFormControlInput1"><strong>8.5 SANGRADO ESPERADO:</strong></label>
    <input type="text" class="form-control" name="sangrado_esp" value="<?php echo $row['sangrado_esp'] ?>" required id="exampleFormControlInput1" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
    </div>
  </div>
</div> 

<div class="container">
<div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>9. OBSERVACIONES:</strong></label>
    <textarea name="observ" class="form-control" rows="5"><?php echo $row['observ'] ?></textarea>
  </div>
</div>

<hr>
<div class="col-sm">
    <label for="exampleFormControlTextarea1"><strong>MARCAJE QUIRÚRGICO:</strong></label>
    <center><img src="../../img/marcaje_qx.jpg" height="600"></center>
</div><hr>

    <?php 
$date=date_create($row['fecha_preop']);
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
        $tipo_cirugia_preop    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tipo_cirugia_preop"], ENT_QUOTES))); //Escanpando caracteres
        $observ    = mysqli_real_escape_string($conexion, (strip_tags($_POST["observ"], ENT_QUOTES))); //Escanpando caracteres
        $diag_preop    = mysqli_real_escape_string($conexion, (strip_tags($_POST["diag_preop"], ENT_QUOTES))); //Escanpando caracteres
        $fecha_cir  = mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha_cir"], ENT_QUOTES)));
        $hora_cir    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hora_cir"], ENT_QUOTES))); //Escanpando caracteres
        $tipo_inter_plan    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tipo_inter_plan"], ENT_QUOTES)));
        $riesgos    = mysqli_real_escape_string($conexion, (strip_tags($_POST["riesgos"], ENT_QUOTES)));
        $cuidados    = mysqli_real_escape_string($conexion, (strip_tags($_POST["cuidados"], ENT_QUOTES)));
        $pronostico    = mysqli_real_escape_string($conexion, (strip_tags($_POST["pronostico"], ENT_QUOTES)));
        $anestesia_sug    = mysqli_real_escape_string($conexion, (strip_tags($_POST["anestesia_sug"], ENT_QUOTES)));
        $nom_medi_cir    = mysqli_real_escape_string($conexion, (strip_tags($_POST["nom_medi_cir"], ENT_QUOTES)));
        $tiempo_estimado    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tiempo_estimado"], ENT_QUOTES)));
        $sangrado_esp    = mysqli_real_escape_string($conexion, (strip_tags($_POST["sangrado_esp"], ENT_QUOTES)));
        $resumen_clin    = mysqli_real_escape_string($conexion, (strip_tags($_POST["resumen_clin"], ENT_QUOTES)));
        $result_lab_gab    = mysqli_real_escape_string($conexion, (strip_tags($_POST["result_lab_gab"], ENT_QUOTES)));
        $fecha= mysqli_real_escape_string($conexion,(strip_tags($_POST["fecha"], ENT_QUOTES)));
        $hora= mysqli_real_escape_string($conexion,(strip_tags($_POST["hora"], ENT_QUOTES)));
        $medico= mysqli_real_escape_string($conexion,(strip_tags($_POST["medico"], ENT_QUOTES)));

        $merge = $fecha.' '.$hora;

date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

        $sql2 = "UPDATE dat_not_preop SET id_usua='$medico',fecha_preop='$merge',tipo_cirugia_preop='$tipo_cirugia_preop', observ='$observ', diag_preop='$diag_preop', fecha_cir ='$fecha_cir', hora_cir='$hora_cir', tipo_inter_plan='$tipo_inter_plan', beneficios='$beneficios', riesgos='$riesgos', cuidados='$cuidados', pronostico='$pronostico', anestesia_sug='$anestesia_sug', nom_medi_cir='$nom_medi_cir', tiempo_estimado='$tiempo_estimado', resumen_clin='$resumen_clin', result_lab_gab='$result_lab_gab' WHERE id_not_preop= $id_not_preop";
        $result = $conexion->query($sql2);



        echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> DATO EDITADO CORRECTAMENTE...</p>";
        echo '<script type="text/javascript">window.location ="../cartas_consentimientos/consent_medico.php"</script>';
      }
  ?>
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