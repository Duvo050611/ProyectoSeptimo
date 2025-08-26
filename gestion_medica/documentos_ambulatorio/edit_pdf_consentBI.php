<?php
session_start();
include "../../conexionbd.php";
include("../header_medico.php");
?>

<!DOCTYPE html>
<div>
    <head>
        <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″/>
             <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
              integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk"
              crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
                integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
                crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
                integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
                crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
                integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
                crossorigin="anonymous"></script>
        <!--  Bootstrap  -->
        <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
              integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ"
              crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <script src="jquery-3.1.1.min.js"></script>
    <script src="js/select2.js"></script>



   
        <script>
            // Write on keyup event of keyword input element
            $(document).ready(function () {
                $("#search").keyup(function () {
                    _this = this;
                    // Show only matching TR, hide rest of them
                    $.each($("#mytable tbody tr"), function () {
                        if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
                            $(this).hide();
                        else
                            $(this).show();
                    });
                });
            });
        </script>


        <title>CARTA DE CONSENTIMIENTO BAJO INFORMACIÓN</title>
    </head>


    <div class="col-sm">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <hr>
<div class="thead" style="background-color: #0c675e; color: white; font-size: 24px;"><strong><center>CARTA DE CONSENTIMIENTO BAJO INFORMACIÓN</center> </strong></div>
                        <hr>
                    </div>


                    <div class="col-3">
                        <?php
                        date_default_timezone_set('America/Guatemala');
                        $fecha_actual = date("Y-m-d H:i:s");
                        ?>
                        <hr>
                        <div class="form-group">
                            <label for="fecha">Fecha y Hora Actual:</label>
                            <input type="datetime" name="fec_hc" value="<?= $fecha_actual ?>" class="form-control"
                                   disabled>
                        </div>
                    </div>
                </div>                                 
                                <?php

include "../../conexionbd.php";
$id_atencion=$_GET['id_atencion'];
$resultado1 = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE id_atencion=$id_atencion") or die($conexion->error);
                    while ($f1 = mysqli_fetch_array($resultado1)) {
                        $ocup=$f1['ocup'];
                        $resp=$f1['resp'];
                        $paren=$f1['paren'];
                        $id_exp=$f1['Id_exp'];
                        ?>

<div class="container">      
                           
  <div class="row">
    <div class="col">
PACIENTE : <td><strong><?php echo $f1['papell']; ?></strong></td>
<td><strong><?php echo $f1['sapell']; ?></strong></td>
<td><strong><?php echo $f1['nom_pac']; ?></strong></td><br>
 NO.EXPEDIENTE : <td><strong><?php echo $f1['Id_exp']; ?></strong></td><br>
SEXO : <td><strong><?php echo $f1['sexo']; ?></strong></td><br>
   
    </div>

    <div class="col">
<?php $date = date_create($f1['fecha']);
$date1 = date_create($f1['fecnac']);
                                     ?>
      FECHA DE ADMISIÓN : <td><strong><?php echo date_format($date, "d/m/Y"); ?></strong></td> <br>
      EDAD :<td><strong><?php echo $f1['edad']; ?></strong></td><br>  
      FECHA DE NACIMIENTO : <td><strong><?php echo date_format($date1, "d/m/Y"); ?></strong></td><br>
      
    <?php  
                    }
                    ?> 
    </div>

<?php
$resultado2 = $conexion->query("select * from cat_camas WHERE id_atencion=$id_atencion") or die($conexion->error);
while ($f2 = mysqli_fetch_array($resultado2)) {

    if(isset($f2)){
    $cama=$f2['num_cama'];
  }else{
    $cama='Sin Cama';
  }
}
?>
  <div class="col">
    <br>
<!-- HABITACIÓN : <td><strong><?php echo $cama; ?></strong></td><br>-->
OCUPACIÓN : <td><strong><?php echo $ocup ?></strong></td><br> 

    </div>
</div>
  <div class="row">
      <div class="col">
          <label><strong>NOMBRE DEL REPRESENTANTE LEGAL : </strong></label>&nbsp;<?php echo $resp ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <label><strong>PARENTESCO : </strong></label>&nbsp; <?php echo $paren ?>
      </div>
  </div>
  <?php 
$id_atencion=$_SESSION['hospital'];

$sql_dat_ing = "SELECT * from dat_ingreso where id_atencion = $id_atencion";
$result_dat_ing = $conexion->query($sql_dat_ing);

while ($row_dat_ing = $result_dat_ing->fetch_assoc()) {
  $motivo_atn = $row_dat_ing['motivo_atn'];
  $especialidad = $row_dat_ing['especialidad'];
  $id_usua = $row_dat_ing['id_usua'];
  $tipo_a = $row_dat_ing['tipo_a'];
  $fecha_ing = $row_dat_ing['fecha'];
}

$sql_reg_usrs = "SELECT * from reg_usuarios where id_usua=$id_usua";
$result_reg_usrs = $conexion->query($sql_reg_usrs);

while ($row_reg_usrs = $result_reg_usrs->fetch_assoc()) {
  $user_pre = $row_reg_usrs['pre'];
  $user_papell = $row_reg_usrs['papell'];
  $user_sapell = $row_reg_usrs['sapell'];
  $user_nombre = $row_reg_usrs['nombre'];
      $ced_p = $row_reg_usrs['cedp'];
  $cargp = $row_reg_usrs['cargp'];
}
   ?>
<form action="../cartas_consentimientos/pdf_consent_BI_medico.php?id_atencion=<?php echo $id_atencion; ?>&id_exp=<?php echo $id_exp ?>" method="POST" target="_blank ">
<div class="row">
    <div class="col">
        <label>Bajo protesta de decir verdad declaro que el / la:</label> &nbsp;<strong><?php echo $user_pre . ' ' . $user_papell . ' ' . $user_sapell . ' ' . $user_nombre; ?></strong> &nbsp; <label>me ha explicado que mi diagnóstico es :</label><br> <strong><?php echo $motivo_atn ?></strong><br>y que por tal motivo debe someterme al (los) siguiente (s) procedimiento (s) con fines diagnósticos y/o terapéuticos:
        <select name="diagnostico_pdf" class="form-control">
            <option>SELECCIONAR DIAGNÓSTICO</option>
            <?php $diagpre="SELECT * FROM cat_diag";
            $result=$conexion->query($diagpre); 
             foreach ($result as $row) {
            ?>
            <option value="<?php echo $row['diagnostico'] ?>"><?php echo $row['diagnostico'] ?></option>
        <?php } ?>
        </select>
        <p>Entiendo que todo acto médico o diagnóstico de tratamiento sea quirúrgico o no quirúrgico puede ocasionar una serie de complicaciones, mayores o menores, a veces potencialmente serias que incluyen cierto riesgo de muerte y que puede requerir tratamientos complementarios médicos y/o quirúrgicos, que aumenten la estancia hospitalaria. Dichas complicaciones a veces son derivadas directamente de la propia técnica, pero otras dependerán del procedimiento, del estado del paciente, de los tratamientos que ha recibido y de las posibles anomalías anatómicas y/o de la utilización de los equipos médicos. Reconozco que entre los posibles riesgos y complicaciones que pueden surgir se encuentra (n):
        <p><input type="text" name="complica_pdf" class="form-control" style="text-transform:uppercase" onkeyup="javascript:this.value=this.value.toUpperCase();"></p>
        <p>Los probables beneficios esperados son: <strong>Esperamos Bueno.</strong></p>
        <p>El pronóstico es:</p>
        <p><input type="text" name="pronostico_pdf" class="form-control" style="text-transform:uppercase" onkeyup="javascript:this.value=this.value.toUpperCase();"></p>
        <p>Declaro que eh comprendido las explicaciones que se me han facilitado en un lenguaje claro y sencillo, el médico que me atiende me ha permitido realizar todas las observaciones y me he aclarado todas las dudas que le he planteado. También comprendo, que por escrito, en cualquier momento puedo revocar el consentimiento que ahora otorgo. Por ello manifiesto que estoy satisfecho(a) con la información recibida y que comprendo el alcance y los riesgos del procedimiento.</p>
        <p>Del mismo modo designo a: &nbsp; <strong><?php echo $resp; ?></strong> </p>
        <p>para que exclusivamente reciba información sobre mi estado de salud, diagnóstico, tratamiento y pronóstico.</p>
        <p>En tales condiciones <strong>CONSIENTO</strong> en forma libre y espontánea y sin ningún tipo de presión en que se me realice:</p>
        <p><input type="text" name="consiento" class="form-control" style="text-transform:uppercase" onkeyup="javascript:this.value=this.value.toUpperCase();"></p>
    </div>
</div>

 <center><hr>
  <button type="submit" name="btn" class="btn btn-primary"><font size="3">ACEPTAR</font></button>
 <button type="button" class="btn btn-danger" onclick="history.back()">CANCELAR</button>
 </center>
 <br>        
</form>
    </div>
</div>

</div></div></div>
<footer class="main-footer">
    <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<!-- FastClick -->
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
    <?php
    include("../../template/footer.php");
    ?>
</footer>
<!-- FastClick -->
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){
            $('#mibuscador').select2();
    });
</script>


</body>

</html>