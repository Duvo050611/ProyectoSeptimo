<?php
session_start();
include "../../conexionbd.php";
include "../header_medico.php";
if( isset($_GET['id_atencion'])) { //si existe el id mandado por metodo GET, se hara la consulta donde el id debe ser igual al id mandado por el metodo GET
  $resultado = $conexion ->query("SELECT paciente.*, dat_ingreso.* FROM paciente
  inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp 
  WHERE id_atencion=".$_GET['id_atencion'])or die($conexion->error);
  
  if(mysqli_num_rows($resultado) > 0 ){ //se mostrara si existe mas de 1
    $f=mysqli_fetch_row($resultado);

  }else{
    header("Location: ../vista_usuario_triage.php"); //te regresa a la página principal
  }
}else{
  header("Location: ../vista_usuario_triage.php"); //te regresa a la página principal
}
$usuario = $_SESSION['login'];
//$resultado=$conexion->query("SELECT * FROM reg_usuarios WHERE id_usua='".$usuario."'") or die($conexion->error);


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <!--  Bootstrap  -->
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <script src="../../js/jquery-3.3.1.min.js"></script>
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/popper.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/main.js"></script>


    <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- FastClick -->
    <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>


</head>
<body>

  <div class="container">
<div class="thead col col-12" style="background-color: #0c675e; color: white; font-size: 26px; align-content: center;">
  <h2><center><strong>TRIAGE</strong></center></h2>
</div>
</div>
<p>
  <div class="container">
  <div class="row">
    <div class="col-sm">
     NOMBRE DEL PACIENTE: <strong><?php echo $f[2];?>
      <?php echo $f[3];?>
      <?php echo $f[4];?></strong><br>
    </div>
    
    <div class="col-sm">
    FECHA DE NACIMIENTO:<strong> <?php  $date = date_create($f[5]);
 echo date_format($date,"d/m/Y");?></strong>
    </div>
  </div>
</div>
<hr>
<?php 
$id_triage=$_GET['id_triage'];
$id_atencion=$_GET['id_atencion'];
$select="SELECT * FROM triage where id_triage=$id_triage";
$result_triage=$conexion->query($select);
while ($row_triage=$result_triage->fetch_assoc()) {
 ?>
  <form action="" method="POST">
  <div class="container">
<table class="table" align="center">
  <thead class="thead-dark">
    <tr>
      
      <th scope="col"></th>
      <th scope="col"><center>CLASIFICACIÓN DEL TRIAGE</center></th>
      <th scope="col">SELECCIONAR TIPO DE URGENCIA</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row"></th>
      <td><img src="../../imagenes/rojo.png" width="600" high="400" align="right"> <hr></td>

      <td>

<div class="form-check"><br><br>
<?php if ($row_triage['urgencia'] == "ROJO (Máxima prioridad)") {?>
  <input class="form-check-input" type="radio" name="urgencia" id="rojo" value="ROJO (Máxima prioridad)" checked>
<?php }else{?>
<input class="form-check-input" type="radio" name="urgencia" id="rojo" value="ROJO (Máxima prioridad)" >
<?php } ?>
  <label for="rojo"></label>
</div>
</td>
    </tr>
    <tr>
      <th scope="row"></th>
      <td><img src="../../imagenes/amarillo.png" width="600" high="400" align="right"> <hr></td>
      <td><div class="form-check">
        <br><br>
<?php if ($row_triage['urgencia'] == "AMARILLO (Urgencia calificada)") {?>
<input class="form-check-input" type="radio" name="urgencia" id="amarillo" value="AMARILLO (Urgencia calificada)" checked>
<?php }else{?>
<input class="form-check-input" type="radio" name="urgencia" id="amarillo" value="AMARILLO (Urgencia calificada)">
<?php } ?>
  
 <label for="amarillo"></label>
 
</div></td>
    
    </tr>
    <tr>
      <th scope="row"></th>
      <td><img src="../../imagenes/verde.png" width="600" high="400" align="right"> <hr></td>
      <td><div class="form-check">
        <br><br>
<?php if ($row_triage['urgencia'] == "VERDE (Urgencia sentida)") {?>
  <input class="form-check-input" type="radio" name="urgencia" id="verde" value="VERDE (Urgencia sentida)" checked>
<?php }else{?>
  <input class="form-check-input" type="radio" name="urgencia" id="verde" value="VERDE (Urgencia sentida)">
<?php } ?>

  
  <label for="verde"></label>  
  
  </div></td>
      
    </tr>
  </tbody>
</table>
</div>
 
<div class="container-fluid">
    <div class="row">
<div class="col-sm-1"></div>

<div class="col-sm-10" id="colocacion">
<div class="row">

  <div class="col-8"><br>
    
<div class="form-group">
            <label for="p_sistolica">PRESIÓN ARTERIAL: </label>
<input type="number" name="p_sistolica" placeholder="mmHg" id="p_sistolica" class="form-control-sm" value="<?php echo $row_triage['p_sistolica'] ?>" min="0"  max="250"  required> /
<label for="p_sistolica"><input type="number" name="p_diastolica" placeholder="mmHg" id="p_diastolica" class="form-control-sm"  value="<?php echo $row_triage['p_diastolica'] ?>" min="0" max="180" required>
<label for="p_diastolica">MMHG</label>
          </div>


<div class="form-group">
            <label for="f_card">FRECUENCIA CARDIACA:</label>
  <input type="number" name="f_card" placeholder="Frecuencia cardiaca" id="f_card" class="form-control-sm" value="<?php echo $row_triage['f_card'] ?>"min="0" max="150"  required>
<label for="f_card">LATIDOS POR MINUTO</label>
          </div>

<div class="form-group">
            <label for="f_resp">FRECUENCIA RESPIRATORIA:</label>
            <input type="number" name="f_resp" placeholder="Frecuencia respiratoria" id="f_resp" min="0" max="100" class="form-control-sm" value="<?php echo $row_triage['f_resp'] ?>" required>
<label for="f_resp">RESPIRACIONES/MIN</label>
          </div>

<div class="form-group">
            <label for="temp">TEMPERATURA:</label>
            <input type="cm-number" name="temp" min="35.0" max="46.9" value="<?php echo $row_triage['temp'] ?>" placeholder="TEMPERARTURA" id="temp" class="form-control-sm" required>
<label for="temp">°C</label>
          </div>

<div class="form-group">
            <label for="sat_oxigeno">SATURACIÓN OXIGENO: </label>
            <input type="number" name="sat_oxigeno" placeholder="Saturación oxigeno" min="0" max="100" id="sat_oxigeno" class="form-control-sm" value="<?php echo $row_triage['sat_oxigeno'] ?>" required>
<label for="sat_oxigeno">%</label>
          </div>

<div class="form-group">
            <label for="peso">PESO: </label>
            <input type="cm-number" name="peso"  placeholder="PESO" id="peso" value="<?php echo $row_triage['peso'] ?>" class="form-control-sm" required>
<label for="peso">KG</label>
          </div>

<div class="form-group">
            <label for="talla">TALLA: </label>
            <input type="cm-number" name="talla"  placeholder="TALLA" id="talla" value="<?php echo $row_triage['talla'] ?>" required>
<label for="Talla">METROS</label>
          </div>
<script type="text/javascript">
function validarRango(elemento){
  var numero = parseInt(elemento.value,10);
  //Validamos que se haya ingresado solo numeros
 
  //Validamos que se cumpla el rango
  if(numero<0 || numero>10){
    alert('SOLO SE PERMITE EL SIGUIENTE RANGO: 0 - 10');
elemento.focus();
elemento.select();


  }


}
</script>
<div class="form-group">
<strong><label for="niv_dolor">NIVEL DE DOLOR (ESCALA EVA): </label></strong>
<input type="number" name="niv_dolor" placeholder="Nivel del dolor" id="niv_dolor" class="form-control col-sm-4" value="<?php echo $row_triage['niv_dolor'] ?>" min="1" max="10"  required="" step="0.1" maxlength="2">
</div>

<img class="img-fluid" class="rounded" src="../../imagenes/caras.png" width="400">

</div>

<div class="col-sm-4">
<hr>
<h6><strong>ANTECEDENTES:</strong></h6>
  
 <div class="form-check">
<?php if($row_triage['diab'] == "SI"){ ?>
  <input class="form-check-input" type="checkbox" name="diab" value="SI" id="diab" checked>
<?php }else{?>
<input class="form-check-input" type="checkbox" name="diab" value="SI" id="diab">
<?php } ?>
  <label class="form-check-label" for="diab">
    DIABETES
  </label>
</div>

 <div class="form-check">
  <?php if($row_triage['h_arterial'] == "SI"){ ?>
<input class="form-check-input" type="checkbox" name="h_arterial" value="SI" id="h_arterial" checked>
<?php }else{?>
<input class="form-check-input" type="checkbox" name="h_arterial" value="SI" id="h_arterial">
<?php } ?>
  
  <label class="form-check-label" for="h_arterial">
    HIPERTENSIÓN ARTERIAL
  </label>
</div>

<div class="form-check">
<?php if($row_triage['enf_card_pulm'] == "SI"){ ?>
 <input class="form-check-input" type="checkbox" name="enf_card_pulm" value="SI" id="enf_card_pulm" checked>
<?php }else{?>
 <input class="form-check-input" type="checkbox" name="enf_card_pulm" value="SI" id="enf_card_pulm">
<?php } ?>
 
  <label class="form-check-label" for="enf_card_pulm">
    ENFERMEDADES CARDIACAS / PULMONARES
  </label>
</div>

<div class="form-check">
<?php if($row_triage['cancer'] == "SI") {?>
 <input class="form-check-input" type="checkbox" name="cancer" value="SI" id="cancer" checked>
<?php }else{?>
 <input class="form-check-input" type="checkbox" name="cancer" value="SI" id="cancer">
<?php } ?>
 
  <label class="form-check-label" for="cancer">
    CÁNCER
  </label>
</div>
<div class="form-check">
<?php if($row_triage['emb'] == "SI") {?>
  <input class="form-check-input" type="checkbox" name="emb" value="SI" id="emb" checked>
<?php }else{?>
  <input class="form-check-input" type="checkbox" name="emb" value="SI" id="emb">
<?php } ?>

  <label class="form-check-label" for="emb">
    EMBARAZO
  </label>
</div>
<br>
<label for="otro">OTRO:</label>
<textarea class="form-control" rows="2" name="otro"><?php echo $row_triage['otro'] ?></textarea>
</div>
 <div class="col-sm-12"><h6><strong><hr>ESCALA GLASGOW:</h6></strong>
<script type="text/javascript">
function validarRango1(elemento){
  var numero = parseInt(elemento.value,15);
  //Validamos que se haya ingresado solo numeros
 
  //Validamos que se cumpla el rango
  if(numero<3 || numero>15){
    alert('SOLO SE PERMITE EL SIGUIENTE RANGO: 3 - 15');
elemento.focus();
elemento.select();


  }


}
</script>

<div class="form-group">           
<input type="number" name="val_total" id="val_total" class="form-control col-3" value="<?php echo $row_triage['val_total'] ?>"  max="15" min="3">
</div>

<strong><label for="edo_clin">MOTIVO DE ATENCIÓN:</label></strong>
<textarea class="form-control" rows="3" cols="100" name="edo_clin"><?php echo $row_triage['edo_clin'] ?></textarea><br>
<strong><label for="imp_diag">IMPRESIÓN DIAGNÓSTICA:</label></strong>
<textarea class="form-control" rows="3" name="imp_diag" ><?php echo $row_triage['imp_diag'] ?></textarea>

        <div class="form-group"><br>
<strong><label for="destino">DESTINO DEL PACIENTE:</label></strong>
<select name="destino"class="form-control" required>
  <option value="<?php echo $row_triage['destino'] ?>"><?php echo $row_triage['destino'] ?></option>
<option></option>
<option value="">SELECCIONAR UNA OPCIÓN</option>
<option  value="QUIROFANO">QUIROFANO</option>
<option  value="HOSPITALIZACION">HOSPITALIZACIÓN</option>
<option  value="CHOQUE">CHOQUE</option>
<option  value="OBSERVACION">OBSERVACIÓN</option>
<option  value="YESOS">YESOS</option>
<option  value="CONSULTA DE URGENCIAS">CONSULTA DE URGENCIAS</option>
</select>
</div>
</div>
</div>
<hr>
        <div class="d-grid gap-2">
            <button type="submit" name="guardar" class="btn btn-primary">FIRMAR</button>
             <button type="button" class="btn btn-danger" onclick="history.back()">CANCELAR</button>
            
        </div>
        <br>
        <br>
</div>
</form>
<?php } ?>

 <?php 
  if (isset($_POST['guardar'])) {

        $p_sistolica    = mysqli_real_escape_string($conexion, (strip_tags($_POST["p_sistolica"], ENT_QUOTES)));
        $p_diastolica    = mysqli_real_escape_string($conexion, (strip_tags($_POST["p_diastolica"], ENT_QUOTES)));
        $f_card  = mysqli_real_escape_string($conexion, (strip_tags($_POST["f_card"], ENT_QUOTES)));
        $f_resp    = mysqli_real_escape_string($conexion, (strip_tags($_POST["f_resp"], ENT_QUOTES))); 
        $temp    = mysqli_real_escape_string($conexion, (strip_tags($_POST["temp"], ENT_QUOTES)));

        $sat_oxigeno    = mysqli_real_escape_string($conexion, (strip_tags($_POST["sat_oxigeno"], ENT_QUOTES)));
        $peso    = mysqli_real_escape_string($conexion, (strip_tags($_POST["peso"], ENT_QUOTES)));
        $talla  = mysqli_real_escape_string($conexion, (strip_tags($_POST["talla"], ENT_QUOTES)));
        $niv_dolor    = mysqli_real_escape_string($conexion, (strip_tags($_POST["niv_dolor"], ENT_QUOTES))); 
        $diab    = mysqli_real_escape_string($conexion, (strip_tags($_POST["diab"], ENT_QUOTES)));

        $h_arterial    = mysqli_real_escape_string($conexion, (strip_tags($_POST["h_arterial"], ENT_QUOTES)));
        $enf_card_pulm    = mysqli_real_escape_string($conexion, (strip_tags($_POST["enf_card_pulm"], ENT_QUOTES)));
        $cancer  = mysqli_real_escape_string($conexion, (strip_tags($_POST["cancer"], ENT_QUOTES)));
        $emb    = mysqli_real_escape_string($conexion, (strip_tags($_POST["emb"], ENT_QUOTES))); 
        $otro    = mysqli_real_escape_string($conexion, (strip_tags($_POST["otro"], ENT_QUOTES)));

        $val_total    = mysqli_real_escape_string($conexion, (strip_tags($_POST["val_total"], ENT_QUOTES)));
        $edo_clin    = mysqli_real_escape_string($conexion, (strip_tags($_POST["edo_clin"], ENT_QUOTES)));
        $imp_diag  = mysqli_real_escape_string($conexion, (strip_tags($_POST["imp_diag"], ENT_QUOTES)));
        $urgencia    = mysqli_real_escape_string($conexion, (strip_tags($_POST["urgencia"], ENT_QUOTES))); 
        $destino    = mysqli_real_escape_string($conexion, (strip_tags($_POST["destino"], ENT_QUOTES)));

        

date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

        $sql2 = "UPDATE triage SET p_sistolica='$p_sistolica', p_diastolica='$p_diastolica', f_card ='$f_card', f_resp='$f_resp', temp='$temp',sat_oxigeno='$sat_oxigeno', peso='$peso', talla ='$talla', niv_dolor='$niv_dolor', diab='$diab',h_arterial='$h_arterial', enf_card_pulm='$enf_card_pulm', cancer ='$cancer', emb='$emb', otro='$otro',val_total='$val_total', edo_clin='$edo_clin', imp_diag ='$imp_diag', urgencia='$urgencia', destino='$destino' WHERE id_triage= $id_triage";
        $result = $conexion->query($sql2);

$sql = "UPDATE dat_ingreso SET area = '$destino' WHERE id_atencion = $id_atencion";
      $result = $conexion->query($sql);

    if($destino== "CHOQUE"){
      
      $sql2 = "UPDATE cat_camas SET estatus = 'OCUPADA', id_atencion= $id_atencion WHERE id = 18 ";
      $result = $conexion->query($sql2);

      $sql3 = "UPDATE dat_ingreso SET cama = '1' WHERE id_atencion= $id_atencion ";
      $result = $conexion->query($sql3);
       }
       if($destino== "OBSERVACION"){
      $sql2 = "UPDATE cat_camas SET estatus = 'OCUPADA', id_atencion= $id_atencion WHERE id = 19 ";
      $result = $conexion->query($sql2);

       $sql3 = "UPDATE dat_ingreso SET cama = '1' WHERE id_atencion= $id_atencion ";
      $result = $conexion->query($sql3);
       }
       if($destino== "YESOS"){
      $sql2 = "UPDATE cat_camas SET estatus = 'OCUPADA', id_atencion= $id_atencion WHERE id = 20 ";
      $result = $conexion->query($sql2);

       $sql3 = "UPDATE dat_ingreso SET cama = '1' WHERE id_atencion= $id_atencion ";
      $result = $conexion->query($sql3);
       }


        echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> DATO EDITADO CORRECTAMENTE...</p>";
        echo '<script type="text/javascript">window.location ="../urgencias/buscar_triage.php"</script>';
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

</body>

</html>