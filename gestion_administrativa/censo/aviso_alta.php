<?php
session_start();
include "../../conexionbd.php";
include("../header_administrador.php");
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

    <title>AVISO DE ALTA </title>
</head>
<body>
<div class="col-sm-2">
            <a type="submit" class="btn btn-danger btn-sm" onclick="history.back()"><font color="white">Regresar</font></a>
        </div>
        <br>
<div class="col-sm-12">
    <div class="container">
        <div class="row">
            <div class="col">
                
<h2><strong>AVISO DE ALTA</strong></h2>
    <hr>
<?php
$id_atencion=$_GET['id_atencion'];
$resultado1 = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE id_atencion=" . $id_atencion) or die($conexion->error);

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
$resultado2 = $conexion->query("select * from cat_camas WHERE id_atencion=" .$id_atencion) or die($conexion->error);
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
            <!--<div class="col-sm-3">
                <?php
              
                $fecha_actual = date("d-m-Y H:i:s");
                ?>
                
                <div class="form-group">
                    <label for="fecha">Fecha y Hora Actual:</label>
                    <input type="datetime" value="<?= $fecha_actual ?>" class="form-control" disabled>
                </div>
            </div>-->
        </div>



  
   <!--INICIO -->
<form action="insertar_alta.php?id_atencion=<?php echo $id_atencion ?>" method="POST">

<div class="container">
    <div class="row">
     <?php

$resultado1 = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE id_atencion=" . $id_atencion) or die($conexion->error);

?>
  <?php
                    while ($f1 = mysqli_fetch_array($resultado1)) {
                           

                        ?>
     <div class="col-sm-4">
        <div class="form-group">
            <label>FECHA Y HORA DE INGRESO</label>
            <input type="datetime" value="<?php echo date_format($date, "d/m/Y H:i:s"); ?>" class="form-control" name="" onkeypress="" disabled>
        </div>
     </div>
     <div class="col-sm-4">
        <div class="form-group">
            <label>FECHA Y HORA DE EGRESO</label>
            <input type="datetime" value="<?= $fecha_actual ?>" class="form-control" name="" onkeypress="" disabled>
        </div>
        <?php } ?>
     </div>   
  <?php

$resultado3 = $conexion->query("select id_atencion,area from dat_ingreso
 WHERE id_atencion=" . $id_atencion) or die($conexion->error);

?>
  <?php
                    while ($f3 = mysqli_fetch_array($resultado3)) {
                           

                        ?>
    <div class="col-sm-3">
        <div class="form-group">
          <label>AREA DE EGRESO</label>
            <input type="datetime" value="<?php echo $f3['area']; ?>" class="form-control" name="" onkeypress="" disabled>  
        </div>
    </div>
</div>

<?php
}
?> 
<div class="row">
    <div class="col-sm-5">
        <div class="form-group">
           <center><label>ALTA POR :</label> </center>
           <div class="row">
              <div class="col">
                <input type="radio" name="alta_por" value="MEJORIA" required=""> <label>MEJORIA</label><br>
                <input type="radio" name="alta_por" value="CURACION"> <label>CURACIÓN</label><br>
                <input type="radio" name="alta_por" value="VOLUNTARIA"> <label>VOLUNTARIA</label><br>
                <input type="radio" name="alta_por" value="DEFUNCION"> <label>DEFUNCIÓN</label><br>
                <input type="radio" name="alta_por" value="TRANSLADO A OTRA INSTITUCION"> <label>  TRANSLADO A OTRA INSTITUCIÓN</label>
              </div>
            </div>    
        </div>
    </div>

    <div class="col-sm-5">
        <div class="form-group">
            <center><label>ORDEN DE SALIDA :</label> </center>
             <label>FECHA :<br></label><input type="date" value="" class="form-control" name="fech_alta"><br>
             <label>HORA :<br></label><input type="time" class="form-control" name="hor_alta"><br>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-5">
        <div class="form-group">
            <center><label>NOMBRE DEL MEDICO QUE AUTORIZA :</label> </center><input type="text" value="" placeholder="NOMBRE DEL MEDICO" class="form-control" name="nom_med" onkeyup="javascript:this.value=this.value.toUpperCase();" style="text-transform:uppercase;">
        </div>
    </div>
    <div class="col-sm-5">
        <div class="form-group">
            <center><label>MEDIO DE AVISO :</label> </center><input type="text" value="" placeholder="MEDIO DE AVISO DE ALTA" class="form-control" name="obs_med" onkeyup="javascript:this.value=this.value.toUpperCase();" style="text-transform:uppercase;">
        </div>
    </div>
</div>
</div><hr>
    <div class="form-group col-6">
      <button type="submit" class="btn btn-primary">FIRMAR</button>
      <button type="button" class="btn btn-danger" onclick="history.back()">CANCELAR</button>
    </div><br>
</form>
</div> <!--TERMINO DE div container-->             
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



</body>
</html>