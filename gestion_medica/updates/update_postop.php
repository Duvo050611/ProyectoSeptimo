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


    <title>NOTA POSTOPERATORIA </title>
</head>
<body>

<div class="col-sm-12">
    <div class="container">
        <div class="row">
            <div class="col">
                
               <div class="thead" style="background-color: #0c675e; color: white; font-size: 24px;">
  <center><strong>NOTA POSTOPERATORIA</strong></center><p>
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
<?php

$resultado2 = $conexion->query("select * from dat_not_preop WHERE id_atencion=" .$_SESSION['hospital']." order by id_not_preop DESC LIMIT 1") or die($conexion->error);

?>

<?php
$resultado3 = $conexion->query("select * from dat_not_inquir WHERE id_atencion=". $_SESSION['hospital']." order by id_not_inquir DESC LIMIT 1") or die($conexion->error);
?>
   

<form action="" method="POST">

   <div class="container">
  <div class="row">
    <div class="col-sm">
     <div class="form-group">
      <?php
                    while ($row = mysqli_fetch_array($resultado2)) {

                        ?>
    <label for="exampleFormControlTextarea1"><strong>DIAGNÓSTICO PREOPERATORIO</strong></label>
    <input type="text" class="form-control" value="<?php echo $row['diag_preop']; ?>"  id="exampleFormControlTextarea1" required rows="3" name="diag_preop" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" disabled>
  
  </div>
    </div>
     
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>CIRUGÍA PROGRAMADA</strong></label>
    <input type="text" class="form-control" value="<?php echo $row['tipo_inter_plan']; ?>"  id="exampleFormControlTextarea1" required rows="3" name="cir_progra" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" disabled>
  </div>
    </div>
    
  </div>
</div>
      <?php 
  }
  ?>         
 <div class="container">
  <div class="row">
    <div class="col-sm">
     <div class="form-group">
      <?php
          while ($row = mysqli_fetch_array($resultado3)) {

      ?>
    <label for="exampleFormControlTextarea1"><strong>DIAGNÓSTICO POSTOPERATORIO</strong></label>
    <input type="text" class="form-control" value="<?php echo $row['diag_postop']; ?>"  id="exampleFormControlTextarea1" required rows="3" name="diag_postop" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" disabled>
  </div>
    </div>  
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-sm">
      <div class="form-group">
     
    <label for="exampleFormControlTextarea1"><strong>CIRUGÍA REALIZADA</strong></label>
    <input type="text" class="form-control" value="<?php echo $row['cir_realizada']; ?>" id="exampleFormControlTextarea1" required name="cir_real" rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" disabled>
  
  </div>

    </div>
    <div class="col-sm">
      <div class="form-group">
      
    <label><strong>CIRUJANO</strong></label>
    <input type="text" class="form-control" id="exampleFormControlTextarea1" value="<?php echo $row['nombre_med_cir']; ?>" required name="cirujano" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" disabled> 
     <?php 
  }
  ?>

  </div>
    </div>
    <div class="col-sm">
              <?php
$resultado3 = $conexion->query("select * from dat_not_inquir WHERE id_atencion=". $_SESSION['hospital']." order by id_not_inquir DESC LIMIT 1") or die($conexion->error);

while ($row = mysqli_fetch_array($resultado3)) {
        ?>
      <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>PRIMER AYUDANTE</strong></label>
    <input type="text" class="form-control" value="<?php echo $row['prim_ayudante']?>"  id="exampleFormControlTextarea1" required name="ayud1" rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" disabled>

  </div>
    </div>
  </div>
</div>
<div class="container">
  <div class="row">
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>SEGUNDO AYUDANTE</strong></label>
    <input type="text" class="form-control" value="<?php echo $row['seg_ayudante']?>"  id="exampleFormControlTextarea1" required name="ayud2" rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" disabled>
  </div>
  </div>
  <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>TERCER AYUDANTE</strong></label>
    <input type="text" class="form-control" value="<?php echo $row['ter_ayudante']?>"  id="exampleFormControlTextarea1" required name="ayud3" rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" disabled>
  </div>
  </div>
</div>
</div>
<div class="container">
  <div class="row">
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>ANESTESIÓLOGO</strong></label>
    <input type="text" value="<?php echo $row['anestesiologo']?>" class="form-control" id="exampleFormControlTextarea1" required name="anest" rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" disabled>
  </div>
  
    </div>
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>INSTRUMENTISTA</strong></label>
    <input type="text" class="form-control" value="<?php echo $row['instrumentista']?>" id="exampleFormControlTextarea1" required name="inst" rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" disabled>
  </div>
    </div>
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>CIRCULANTE</strong></label>
    <input type="text" class="form-control" value="<?php echo $row['circulante']?>" id="exampleFormControlTextarea1" required name="circu" rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" disabled>
  </div>
      
    </div>
  </div>
</div>
<div class="container">
  <div class="row">
    <div class="col-sm">
     <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>SANGRADO</strong></label>
    <input type="text" class="form-control" value="<?php echo $row['perd_hema']?>" id="exampleFormControlTextarea1" required name="sang" rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" disabled>
  </div>
    </div>
    <?php 
$id_not_postp=$_GET['id_not_postp'];
$alta="SELECT * FROM  dat_not_postop where id_not_postp=$id_not_postp";
$result=$conexion->query($alta);
while ($row=$result->fetch_assoc()) {
 ?> 
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>HALLAZGOS Y COMPLICACIONES</strong></label>
    <input type="text" name="complic" class="form-control" value="<?php echo $row['complic'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
    </div>
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>INCIDENTES Y ACCIDENTES</strong></label>
    <input type="text" name="in_ac" class="form-control" value="<?php echo $row['in_ac'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
    </div>
  <?php } ?>
  </div>
</div>
<div class="container">

              <?php
$resultado3 = $conexion->query("select * from dat_not_inquir WHERE id_atencion=". $_SESSION['hospital']." order by id_not_inquir DESC LIMIT 1") or die($conexion->error);


while ($row = mysqli_fetch_array($resultado3)) {
        ?>
    
  <div class="row">
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlTextarea1"><br><strong>CUENTA DE TEXTILES Y MATERIAL</strong></label>
    <input type="text" class="form-control" value="<?php echo $row['inst_necesario']?>" name="Ccuenta_tex" id="exampleFormControlTextarea1" required rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" disabled></textarea></div>

   
  </div>
    </div>
      <div class="col-sm">
       <label for="exampleFormControlTextarea1"><center><strong>ESTUDIOS DE PATOLOGÍA TRANSOPERATORÍA Y POSTOPERATORÍA</strong></center></label>
      <div class="row">
               <div class="col"><label>TRANSOPERATORIOS</label>
            <input type="text" class="form-control" value="<?php echo $row['trans']?>" name="biops" id="exampleFormControlTextarea1" required rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" disabled></textarea></div>
                <div class="col"><label>POSTOPERATORIOS</label>
            <input type="text" class="form-control" value="<?php echo $row['posto']?>" name="envio" id="exampleFormControlTextarea1" required rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" disabled></div>
            <?php

}
?>
</div>
    </div>
  </div>
</div>
<?php 
  }
  ?>

<?php 
$id_not_postp=$_GET['id_not_postp'];
$alta="SELECT * FROM  dat_not_postop where id_not_postp=$id_not_postp";
$result=$conexion->query($alta);
while ($row=$result->fetch_assoc()) {
?> 
<div class="container">
  <div class="row">
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>HALLAZGOS</strong></label>
    <input type="text" name="hallazgos" class="form-control" value="<?php echo $row['hallazgos'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
    </div>
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>ESTADO POSTQUIRÚRGICO</strong></label>
    <input type="text" name="estado_post" class="form-control" value="<?php echo $row['estado_post'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
    </div>
  </div>
</div>


<div class="container">
  <div class="row">
     <div class="col">
        <div class="form-group">
             <label for="ten_sist">PRESIÓN ARTERIAL: </label>
               <input type="text" name="ten_sist" id="ten_sist" value="<?php echo $row['ten_sist'] ?>" placeholder="mmHg" class="form-control-sm col-2" style="text-transform:uppercase;" value="" onkeyup="javascript:this.value=this.value.toUpperCase();" required> 
               / 
               <input type="text" min="" name="ten_diast" value="<?php echo $row['ten_diast'] ?>" placeholder="mmHg" id="ten_diast" class="form-control-sm col-2" style="text-transform:uppercase;" value="" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                    <label for="ten_diast">MMHG</label>
                                </div>
          <div class="form-group">
              <label for="frec">FRECUENCIA CARDIACA:</label>
              <input type="text" name="frec" value="<?php echo $row['frec'] ?>" placeholder="Frecuencia cardiaca" id="frec" class="form-control-sm" style="text-transform:uppercase;" value=""                                 onkeyup="javascript:this.value=this.value.toUpperCase();" required>
               <label for="f_card">LATIDOS POR MINUTO</label>
           </div>
      </div>
        <div class="col">
          <div class="form-group">
              <label for="frecresp">FRECUENCIA RESPIRATORIO:</label>
              <input type="text" name="frecresp" value="<?php echo $row['frecresp'] ?>" placeholder="Frecuencia respiratoria" id="frecresp" class="form-control-sm" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
               <label for="frecresp">RESPIRACIONES/MIN</label>
          </div>
                            
                            
      <div class="form-group">
          <label for="tempera">TEMPERATURA:</label>
          <input type="number" min="35" max="42" step="0.1" name="tempera" value="<?php echo $row['tempera'] ?>" placeholder="Temperatura" id="tempera" class="form-control-sm" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
          <label for="tempera">°C</label>
      </div>
            </div>
         </div>
  </div>
<div class="container">
  <div class="row">
    <div class="col-sm">
    <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>TÉCNICA (DESCRIPCIÓN QUIRÚRGICA)</strong></label>
    <input type="text" name="tec" class="form-control" value="<?php echo $row['tec'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
    </div>
    <div class="col-sm">
     <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>PLAN TERAPÉUTICO</strong></label>
    <input type="text" name="plan_tera" class="form-control" value="<?php echo $row['plan_tera'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
    </div>
 
      <div class="col">
       <div class="form-group">
           <label for="pron"><strong>PRONÓSTICO</strong></label>
           <select name="pron" class="form-control" id="mibuscador" style="width : 100%; heigth : 100%">
                        <option value="<?php echo $row['pron'] ?>"><?php echo $row['pron'] ?></option>
                        <option></option>
                        <option disabled="">SELECCIONAR PRONÓSTICO</option>
                        <option value="BUENO">BUENO</option>
                        <option value="MALO">MALO</option>
                        <option value="RESERVADO">RESERVADO</option>
         </select>
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
                
</div> <!--TERMINO DE NOTA MEDICA div container-->
<?php } ?>
</div>   
    <?php 
  if (isset($_POST['guardar'])) {


        $complic    = mysqli_real_escape_string($conexion, (strip_tags($_POST["complic"], ENT_QUOTES))); //Escanpando caracteres
        $in_ac    = mysqli_real_escape_string($conexion, (strip_tags($_POST["in_ac"], ENT_QUOTES))); //Escanpando caracteres
        $hallazgos   = mysqli_real_escape_string($conexion, (strip_tags($_POST["hallazgos"], ENT_QUOTES)));
        $estado_post    = mysqli_real_escape_string($conexion, (strip_tags($_POST["estado_post"], ENT_QUOTES))); //Escanpando caracteres
        $ten_sist    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ten_sist"], ENT_QUOTES)));
        $ten_diast    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ten_diast"], ENT_QUOTES)));
         $frec   = mysqli_real_escape_string($conexion, (strip_tags($_POST["frec"], ENT_QUOTES)));
        $frecresp    = mysqli_real_escape_string($conexion, (strip_tags($_POST["frecresp"], ENT_QUOTES)));
        $tempera    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tempera"], ENT_QUOTES)));

        $tec    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tec"], ENT_QUOTES))); //Escanpando caracteres
        $plan_tera    = mysqli_real_escape_string($conexion, (strip_tags($_POST["plan_tera"], ENT_QUOTES))); //Escanpando caracteres
        $pron   = mysqli_real_escape_string($conexion, (strip_tags($_POST["pron"], ENT_QUOTES)));

        
       
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

        $sql2 = "UPDATE dat_not_postop SET complic='$complic', in_ac='$in_ac', hallazgos='$hallazgos', estado_post='$estado_post', ten_sist='$ten_sist', ten_diast='$ten_diast', frec='$frec'  , frecresp='$frecresp', tempera='$tempera',tec='$tec', plan_tera='$plan_tera', pron='$pron' WHERE id_not_postp= $id_not_postp";
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