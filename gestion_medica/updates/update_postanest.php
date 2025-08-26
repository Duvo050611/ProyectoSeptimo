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
  <center><strong>MANEJO POST-ANESTÉSICO</strong></center><p>
</div>     <hr>
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
<?php 
$id_post_anest=$_GET['id_post_anest'];
$alta="SELECT * FROM dat_post_anest where id_post_anest=$id_post_anest";
$result=$conexion->query($alta);
while ($row=$result->fetch_assoc()) {
?>
<div class="container">
  <div class="row"><strong>TÉCNICA ANESTÉSICA</strong>
    <div class="col-sm">
      <input type="text" name="tecan_pos" class="form-control" value="<?php echo $row['tecan_pos'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    </div><strong>TIEMPO ANESTÉSICO</strong>
    <div class="col-sm">
      <input type="text" name="tiem_pos" class="form-control" value="<?php echo $row['tiem_pos'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    </div>
  </div>
</div>
<hr>
 <div class="row">
    <div class="col-sm-2">
   <br><br><strong>MEDICAMENTOS ADMINISTRADOS</strong>
    </div>
    <div class="col-sm">
       <p><strong>ANESTÉSICOS</strong><input type="text" name="an_pos" class="form-control" value="<?php echo $row['an_pos'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"> </p>
      <strong>ADYUVANTES</strong><input type="text" name="ad_pos" class="form-control" value="<?php echo $row['ad_pos'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    </div>
  </div>
  <hr>
<div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>CONTINGENCIAS ACCIDENTALES E INCIDENTES ATRIBUIBLES A LA ANESTESIA</strong></label>
    <textarea name="con_pos" class="form-control" rows="3"><?php echo $row['con_pos'] ?></textarea>
  </div>
  <hr>
<div class="form-group">
    <label for="exampleFormControlTextarea2"><strong>BALANCE HÍDRICO</strong></label>
    <textarea name="bal_pos" class="form-control" rows="3"><?php echo $row['bal_pos'] ?></textarea>
  </div>
<hr>
<div class="thead" style="background-color: #0c675e; color: white; font-size: 19px;">
  <center><strong>ESTADO CLÍNICO DEL PACIENTE AL EGRESO DEL QUIRÓFANO</strong></center><p>
</div> 

<div class="container">
  <div class="row">
    <div class="col-sm-3">
    <br> <strong>SIGNOS VITALES</strong>
    </div>
    <div class="col-sm-2">TA:
     <div class="row">
  <div class="col"><input type="text" name="sist_pos" value="<?php echo $row['sist_pos'] ?>" class="form-control"></div> /
  <div class="col"><input type="text" name="dias_pos" value="<?php echo $row['dias_pos'] ?>" class="form-control"></div>
</div>
    </div>
    <div class="col-sm-1">
      FC:<input type="text" name="fc_pos" value="<?php echo $row['fc_pos'] ?>" class="form-control">
    </div>
    <div class="col-sm-1">
      FR:<input type="text" name="fr_pos" value="<?php echo $row['fr_pos'] ?>" class="form-control">
    </div>
    <div class="col-sm-1">
     TEMP:<input type="text" name="temp_pos" value="<?php echo $row['temp_pos'] ?>" class="form-control">
    </div>
    <div class="col-sm-1">
     PULSO:<input type="text" name="pul_pos" value="<?php echo $row['pul_pos'] ?>" class="form-control">
    </div>
    <div class="col-sm-3">
     SAT OXÍGENO<input type="text" name="so_pos" value="<?php echo $row['so_pos'] ?>" class="form-control">
    </div>
  </div>
</div>
<hr>
<div class="container">
  <div class="row">
    <div class="col-sm">
   <strong> VÍA ÁEREA:</strong><input type="text" name="ae_pos" class="form-control"value="<?php echo $row['ae_pos'] ?>"style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    </div>
    <div class="col-sm">
   <strong> SANGRADO ACTIVO ANORMAL:</strong><input type="text" name="san_pos"value="<?php echo $row['san_pos'] ?>" class="form-control"style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    </div>
    </div>
  </div><br>
  <div class="container">
  
</div>
<hr>
<strong>LUGAR Y CONDICIONES DE TRASLADO (INCLUIR ALDRETE):</strong>
<input type="text" name="tras_pos" class="form-control"value="<?php echo $row['tras_pos'] ?>"style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"><hr>
<strong>OBSERVACIONES:</strong><input type="text" name="ob_pos" class="form-control" value="<?php echo $row['ob_pos'] ?>"><hr>
<strong>PLAN DE MANEJO Y TRATAMIENTO, INCLUYENDO PROTOCOLO DE ANALGESIA:</strong>
<input type="text" name="plan_pos" class="form-control"value="<?php echo $row['plan_pos'] ?>"style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    <?php 
$date=date_create($row['fecha_pos']);
$time=date_create($row['hora_pos']);
$id_usua=$row['id_usua'];
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

        $tecan_pos    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tecan_pos"], ENT_QUOTES))); 
        $tiem_pos    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tiem_pos"], ENT_QUOTES))); 
        $an_pos   = mysqli_real_escape_string($conexion, (strip_tags($_POST["an_pos"], ENT_QUOTES)));
        $ad_pos    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ad_pos"], ENT_QUOTES))); 
        $con_pos    = mysqli_real_escape_string($conexion, (strip_tags($_POST["con_pos"], ENT_QUOTES)));
        $bal_pos    = mysqli_real_escape_string($conexion, (strip_tags($_POST["bal_pos"], ENT_QUOTES)));
        $sist_pos    = mysqli_real_escape_string($conexion, (strip_tags($_POST["sist_pos"], ENT_QUOTES)));
        $dias_pos    = mysqli_real_escape_string($conexion, (strip_tags($_POST["dias_pos"], ENT_QUOTES)));
        $fc_pos    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fc_pos"], ENT_QUOTES)));
        $fr_pos      = mysqli_real_escape_string($conexion, (strip_tags($_POST["fr_pos"], ENT_QUOTES)));
        $temp_pos    = mysqli_real_escape_string($conexion, (strip_tags($_POST["temp_pos"], ENT_QUOTES)));

        $pul_pos    = mysqli_real_escape_string($conexion, (strip_tags($_POST["pul_pos"], ENT_QUOTES))); 
        $so_pos    = mysqli_real_escape_string($conexion, (strip_tags($_POST["so_pos"], ENT_QUOTES))); 
        $ae_pos   = mysqli_real_escape_string($conexion, (strip_tags($_POST["ae_pos"], ENT_QUOTES)));
        $san_pos    = mysqli_real_escape_string($conexion, (strip_tags($_POST["san_pos"], ENT_QUOTES))); 
        $ven_pos    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ven_pos"], ENT_QUOTES)));
        $dre_pos    = mysqli_real_escape_string($conexion, (strip_tags($_POST["dre_pos"], ENT_QUOTES)));
        $tras_pos    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tras_pos"], ENT_QUOTES)));
        $ob_pos    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ob_pos"], ENT_QUOTES)));
        $plan_pos    = mysqli_real_escape_string($conexion, (strip_tags($_POST["plan_pos"], ENT_QUOTES)));

        $fecha= mysqli_real_escape_string($conexion,(strip_tags($_POST["fecha"], ENT_QUOTES)));
        $hora= mysqli_real_escape_string($conexion,(strip_tags($_POST["hora"], ENT_QUOTES)));
        $medico= mysqli_real_escape_string($conexion,(strip_tags($_POST["medico"], ENT_QUOTES)));


date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

        $sql2 = "UPDATE dat_post_anest SET id_usua='$medico',fecha_pos='$fecha',hora_pos='$hora',tecan_pos='$tecan_pos', tiem_pos='$tiem_pos', an_pos='$an_pos', ad_pos='$ad_pos', con_pos='$con_pos', bal_pos='$bal_pos' , sist_pos='$sist_pos', dias_pos='$dias_pos', fc_pos='$fc_pos' , fr_pos='$fr_pos', temp_pos='$temp_pos', pul_pos='$pul_pos', so_pos='$so_pos', ae_pos='$ae_pos', san_pos='$san_pos', ven_pos='$ven_pos', dre_pos='$dre_pos' , tras_pos='$tras_pos', ob_pos='$ob_pos', plan_pos='$plan_pos' WHERE id_post_anest=$id_post_anest";
        $result = $conexion->query($sql2);

        echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> DATO EDITADO CORRECTAMENTE...</p>";
        echo '<script type="text/javascript">window.location ="../cartas_consentimientos/consent_medico.php"</script>';
      }
  ?>
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