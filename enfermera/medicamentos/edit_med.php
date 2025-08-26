<?php

date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i a");

session_start();
if (!isset($_SESSION['login'])) {
  header("Location: ../index.php");
}

include "../header_enfermera.php";
?>

<head>
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
  
    <div class="container box">
      <div class="container">

        <div class="row">
         
          <div>
            <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
                <tr><strong><center>EDITAR MEDICAMENTOS</center></strong>
            </div>
            <br>
            <?php
            $id = $_GET['id_med_reg'];

            $sql = "SELECT * from medica_enf where id_med_reg = $id";
            $result = $conexion->query($sql);
            while ($row_datos = $result->fetch_assoc()) {
            ?>
              <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">

<div class="container">
  <div class="row">
    <div class="col-sm-3">
     <div class="form-group">
                  <label for="hor">Hora:</label>
                  <input type="time" class="form-control" value="<?php echo $row_datos['hora_mat']?>" id="hor" name="hora_mat" required="">
        
         
                </div>
    </div>
    <div class="col-sm-3">
   <div class="form-group">
                  <label for="pa">Medicamento:</label>
  <div class="row">
    <div class="col-sm-12">
   <select data-live-search="true" class="form-control" name="medicam_mat" id="pa" style="width : 100%; heigth : 100%" required="">
         <?php
         $sql = "SELECT * FROM item, stock where item.controlado = 'NO' AND item.item_id = stock.item_id and stock.stock_qty != 0 ORDER BY item.item_name ASC";
              $result = $conexion->query($sql);
                echo "<option value='" . $row_datos['medicam_mat'] . "'>" . $row_datos['medicam_mat'] . "</option>";

               while ($row_datoss = $result->fetch_assoc()) {
              
                 echo "<option value='" . $row_datoss['item_name'] . "'>" . $row_datoss['item_name'] . "</option>";
                }
          ?></select>
 
         
    </div>
    
    
  </div>


                </div>
    </div>
    <div class="col-sm-2">
      <div class="form-group">
                  <label for="dosis_mat">Dosis:</label>
                  <input type="text" size="30" name="dosis_mat"  id="dosis_mat" class="form-control" 
                  value="<?php echo $row_datos['dosis_mat']; ?>" required>
                </div>
    </div>
    <div class="col-sm-3">
      <div class="form-group">
                  <label for="unimed">Unidad de medida:</label>
                  <select name="unimed" class="form-control" id="unimed">
  <option value="<?php echo $row_datos['unimed']; ?>"><?php echo $row_datos['unimed']; ?></option>
  <option value="Gota">Gota</option>
  <option value="Microgota">Microgota</option>
  <option value="Litro">Litro</option>
  <option value="Mililitro">Mililitro</option>
 <option value="Microlitro">Microlitro</option>
  <option value="Centimetro cubico">Centímetro cúbico</option>
   <option value="Dracma liquida">Dracma líquida</option>
    <option value="Onza liquida">Onza líquida</option>
     <option value="Kilogramo">Kilogramo</option>
      <option value="Gramo">Gramo</option>
       <option value="Miligramo">Miligramo</option>
        <option value="Microgramo">Microgramo</option>
<option value="Microgramo de HA">Microgramo de HA</option>
<option value="Nanogramo">Nanogramo</option>
<option value="Libra">Libra</option>
<option value="Onza">Onza</option>
<option value="Masa molar">Masa molar</option>
<option value="Milimol">Milimol</option>
<option value="Miliequivalente">Miliequivalente</option>
<option value="Unidad">Unidad</option>
<option value="Miliunidad">Miliunidad</option>
<option value="Unidad internacional">Unidad internacional</option>
<option value="Unidad">Unidad</option>

</select>
                 
                </div>
    </div>
    <div class="col-sm-3">
      <div class="form-group">
                  <label for="via_mat">Via:</label>
                  <select name="via_mat" class="form-control">
                       <option value="<?php echo $row_datos['via_mat']; ?>"><?php echo $row_datos['via_mat']; ?></option>
  <option value="">Seleccionar vía</option>
  <option value="INTRAVENOSA">INTRAVENOSA</option>
  <option value="INTRAMUSCULAR">INTRAMUSCULAR</option>
  <option value="INTRAOSEA">INTRAOSEA</option>
  <option value="INTRADERMICA">INTRADÉRMICA</option>
  <option value="NASAL">NASAL</option>
  <option value="OTICA">ÓTICA</option>
  <option value="ORAL">ORAL</option>
  <option value="SUBLINGUAL">SUBLINGUAL</option>
  <option value="SUBTERMICA">SUBDÉRMICA</option>
  <option value="SUBCUTANEA">SUBCUTANEA</option>
  <option value="SONDA">SONDA</option>
  <option value="NEBULIZACION">NEBULIZACIÓN</option>
  <option value="RECTAL">RECTAL</option>
  <option value="TOPICO">TÓPICO</option>
</select>
                  
                
                </div>
    </div>
    <div class="col-sm-2">
      <div class="form-group">
                  <label for="otro">Otros:</label>
                  <input type="text" size="30" name="otro"  id="otro" class="form-control" 
                  value="<?php echo $row_datos['otro']; ?>">
      </div>
    </div>
    <div class="col-sm-3">
      <div class="form-group">
                  <label for="fecha">Fecha:</label>
                  <input type="date" size="30" name="fecha" id="fecha" class="form-control" 
                  value="<?php echo $row_datos['fecha_mat']; ?>" required>
                </div>
    </div>
     <div class="col-sm-3">
      <div class="form-group">
                  <label for="tipo">Tipo:</label>
                  <select name="tipo" id="tipo" class="form-control">
                      <option value="<?php echo $row_datos['tipo']; ?>"><?php echo $row_datos['tipo']; ?></option>
                      <option value="TERAPIA INTENSIVA">TERAPIA INTENSIVA</option>
                      <option value="HOSPITALIZACIÓN">HOSPITALIZACIÓN</option>
                      <option value="QUIROFANO">QUIROFANO</option>
                      <option value="OBSERVACIÓN">OBSERVACIÓN</option>
                  </select>
                  
      </div>
    </div>
  </div>
</div>
                <center><input type="submit" name="edit" class="btn btn-success btn-sm" value="Guardar">
               

<button type="button" class="btn btn-danger btn-sm" onclick="history.back()">Regresar...</button>


                </center>
<br>
          </div>
        <?php } ?>
        </form>
        </div>
        <div class="col-md-2"></div>
      </div>
      <?php

      if (isset($_POST['edit'])) {

      $hora_mat    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hora_mat"], ENT_QUOTES))); 
      $medicam_mat    = mysqli_real_escape_string($conexion, (strip_tags($_POST["medicam_mat"], ENT_QUOTES))); 
      $dosis_mat    = mysqli_real_escape_string($conexion, (strip_tags($_POST["dosis_mat"], ENT_QUOTES)));
      $unimed    = mysqli_real_escape_string($conexion, (strip_tags($_POST["unimed"], ENT_QUOTES)));
      $via_mat    = mysqli_real_escape_string($conexion, (strip_tags($_POST["via_mat"], ENT_QUOTES)));
      $otro    = mysqli_real_escape_string($conexion, (strip_tags($_POST["otro"], ENT_QUOTES)));
      $fecha_mat    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha"], ENT_QUOTES)));
      $tipo    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tipo"], ENT_QUOTES)));
      $enf_fecha    = mysqli_real_escape_string($conexion, (strip_tags($_POST["enf_fecha"], ENT_QUOTES)));

$fecha_actual = date("Y-m-d H:i");

        $sql2 = "UPDATE medica_enf SET fecha_mat = '$fecha_mat',hora_mat = '$hora_mat', medicam_mat = '$medicam_mat',dosis_mat = '$dosis_mat',unimed = '$unimed', via_mat = '$via_mat',tipo = '$tipo',otro = '$otro', enf_fecha = '$fecha_actual'  WHERE id_med_reg = $id";
        $result = $conexion->query($sql2);

        echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> Dato insertado correctamente...</p>";
        echo '<script type="text/javascript">window.location.href = "medicamentos.php";</script>';
      }
      ?>
    </div>
  </section>
  </div>
  <footer class="main-footer">
    <?php
    include("../../template/footer.php");
    ?>
  </footer>


</body>

</html>