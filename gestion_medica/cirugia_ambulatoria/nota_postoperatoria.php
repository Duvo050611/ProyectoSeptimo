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
  <center><strong> NOTA POSTOPERATORIA</strong></center><p>
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
<?php
$resultado2 = $conexion->query("select * from dat_not_preop_amb WHERE id_atencion=". $_GET['id_atencion'] )or die($conexion->error);
?>

<?php
$resultado3 = $conexion->query("select * from dat_not_inquir_amb WHERE id_atencion=". $_GET['id_atencion'] )or die($conexion->error);
?>
   
  
<form action="insertar_not_postop.php?id_atencion= <?php echo $_GET['id_atencion'] ?>" method="POST">
               
 <div class="container">
  <div class="row">
    <div class="col-sm">
     <div class="form-group">
      <?php
                    while ($fila = mysqli_fetch_array($resultado2)) {

                        ?>
    <label for="exampleFormControlTextarea1"><strong>DIAGNÓSTICO PREOPERATORIO</strong></label>
    <input type="text" class="form-control" value="<?php echo $fila['diag_preop']; ?>"  id="exampleFormControlTextarea1" required rows="3" name="diag_preop" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" disabled>
    <?php 
  }
  ?>
  </div>
    </div>
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>DIAGNÓSTICO POSTOPERATORIO</strong></label>
    <textarea class="form-control" id="exampleFormControlTextarea1" required name="diag_postop" rows="3"style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
  </div>
    </div>
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>CIRUGÍA PROGRAMADA</strong></label>
    <textarea class="form-control" id="exampleFormControlTextarea1" required name="cir_progra" rows="3"style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
  </div>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-sm">
      <div class="form-group">
        <?php
                    while ($row = mysqli_fetch_array($resultado3)) {
                        ?>
    <label for="exampleFormControlTextarea1"><strong>CIRUGÍA REALIZADA</strong></label>
    <input type="text" class="form-control" value="<?php echo $row['cir_realizada']; ?>" id="exampleFormControlTextarea1" required name="cir_real" rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" disabled>
    <?php
}
?>
  </div>

    </div>
    <div class="col-sm">
      <div class="form-group">
        <?php
        $resultado2 = $conexion->query("select * from dat_not_preop_amb WHERE id_atencion=". $_GET['id_atencion'] )or die($conexion->error);
                    while ($fila1 = mysqli_fetch_array($resultado2)) {

                        ?>
    <label><strong>CIRUJANO</strong></label>
    <input type="text" class="form-control" id="exampleFormControlTextarea1" value="<?php echo $fila1['nom_medi_cir']; ?>" required name="cirujano" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" disabled> 
     <?php 
  }
  ?>

  </div>
    </div>
    <div class="col-sm">
              <?php
$resultado3 = $conexion->query("select * from dat_not_inquir_amb WHERE id_atencion=". $_GET['id_atencion'] )or die($conexion->error);

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
      <?php 
  }
  ?>
    </div>
  </div>
</div>
<div class="container">
  <div class="row">
    <div class="col-sm">
     <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>SANGRADO</strong></label>
    <textarea class="form-control" id="exampleFormControlTextarea1" required name="sang" rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
  </div>
    </div>
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>COMPLICACIONES</strong></label>
    <textarea class="form-control" id="exampleFormControlTextarea1" required name="complic" rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
  </div>
    </div>
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>INCIDENTES Y ACCIDENTES</strong></label>
    <textarea class="form-control" id="exampleFormControlTextarea1" required name="in_ac" rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
  </div>
    </div>
  </div>
</div>
<div class="container">
  <div class="row">
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlTextarea1"><br><strong>CUENTA DE TEXTILES Y MATERIAL</strong></label>
    <textarea class="form-control" id="exampleFormControlTextarea1" required name="cuent_tex" rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
  </div>
    </div>
      <div class="col-sm">
       <label for="exampleFormControlTextarea1"><center><strong>ESTUDIOS DE PATOLOGÍA TRANSOPERATORÍA Y POSTOPERATORÍA</strong></center></label>
      <div class="row">
                <?php
$resultado3 = $conexion->query("select * from dat_not_inquir WHERE id_atencion=". $_GET['id_atencion'] )or die($conexion->error);

while ($row = mysqli_fetch_array($resultado3)) {
        ?>
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
<div class="container">
  <div class="row">
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>HALLAZGOS</strong></label>
    <textarea class="form-control" id="exampleFormControlTextarea1" required name="hallazgos" rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
  </div>
    </div>
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>ESTADO POSTQUIRÚRGICO</strong></label>
    <textarea class="form-control" id="exampleFormControlTextarea1" required name="estado_post" rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
  </div>
    </div>
  </div>
</div>
<div class="container">
  <div class="row">
     <div class="col">
        <div class="form-group">
             <label for="ten_sist">Presión Arterial: </label>
               <input type="number" name="ten_sist" id="ten_sist" placeholder="mmHg" class="form-control-sm col-2" style="text-transform:uppercase;" value="" onkeyup="javascript:this.value=this.value.toUpperCase();" required> / <input type="number" min="" name="ten_diast" placeholder="mmHg" id="ten_diast" class="form-control-sm col-2" style="text-transform:uppercase;" value="" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                    <label for="ten_diast">mmHg</label>
                                </div>
          <div class="form-group">
              <label for="frec">Frecuencia Cardiaca:</label>
              <input type="number" name="frec" placeholder="Frecuencia cardiaca" id="frec" class="form-control-sm" style="text-transform:uppercase;" value=""                                 onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                    <label for="f_card">Latidos por minuto</label>
           </div>
      </div>
        <div class="col">
                                <div class="form-group">
                                    <label for="frecresp">Frecuencia Respiratoria:</label>
                                    <input type="number" name="frecresp" placeholder="Frecuencia respiratoria" id="frecresp"
                                           class="form-control-sm" style="text-transform:uppercase;" value=""
                                           onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                    <label for="frecresp">Respiraciones/Min</label>
                                </div>
                            
                            
                                <div class="form-group">
                                    <label for="tempera">Temperatura:</label>
                                    <input type="number" min="35" max="42" step="0.1" name="tempera"
                                           placeholder="Temperatura" id="tempera" class="form-control-sm"
                                           style="text-transform:uppercase;" value=""
                                           onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                    <label for="tempera">°C</label>
                                </div>
            </div>
         </div>
  </div>
<div class="container">
  <div class="row">
    <div class="col-sm">
    <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>TÉCNICA</strong></label>
    <textarea class="form-control" id="exampleFormControlTextarea1" name="tec" rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" required></textarea>
  </div>
    </div>
    <div class="col-sm">
     <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>PLAN TERAPÉUTICO</strong></label>
    <textarea class="form-control" id="exampleFormControlTextarea1" name="plan_tera" rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" required></textarea>
  </div>
    </div>
 
      <div class="col">
                                <div class="form-group">
                                    <label for="pron"><strong>PRONÓSTICO</strong></label>
                                    <select name="pron" class="form-control" id="mibuscador" style="width : 100%; heigth : 100%">
                                       
                        <option value="">Seleccionar pronóstico</option>
                        <option value="BUENO">BUENO</option>
                        <option value="MALO">MALO</option>
                        <option value="RESERVADO">RESERVADO</option>
                    
                                    </select>
                                </div>
                            </div>
  </div>
</div>
<div class="container">
<div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>RESUMEN CLÍNICO</strong></label>
    <textarea class="form-control" id="exampleFormControlTextarea1" name="resum_clin" rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" required></textarea>
  </div>
</div>



  <hr>
            



 
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