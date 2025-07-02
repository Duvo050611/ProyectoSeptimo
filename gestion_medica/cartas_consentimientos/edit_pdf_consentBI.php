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


        <title>CARTA DE CONSENTIMIENTO INFORMADO</title>
    </head>


    <div class="col-sm">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <hr>
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;"><strong><center>CARTA DE CONSENTIMIENTO INFORMADO</center> </strong></div>
                        <hr>
                    </div>


                    <div class="col-3">
                        <?php
                        
                        $fecha_actual = date_create();                 
                        $fecha_actual = date_format($fecha_actual,"d/m/Y H:i a");
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

$resultado1 = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE id_atencion=" . $_SESSION['hospital']) or die($conexion->error);
                    while ($f1 = mysqli_fetch_array($resultado1)) {
                        $ocup=$f1['ocup'];
                        $resp=$f1['resp'];
                        $paren=$f1['paren'];
                        $id_exp=$f1['Id_exp'];
                        $pac_fecnac = $f1['fecnac'];

                        ?>

<div class="container">      
                           
  <div class="row">
    <div class="col">
Paciente : <td><strong><?php echo $f1['nom_pac']; ?></strong></td>
<td><strong><?php echo $f1['papell']; ?></strong></td>
<td><strong><?php echo $f1['sapell']; ?></strong></td><br>
Expediente : <td><strong><?php echo $f1['folio']; ?></strong></td><br>
Género : <td><strong><?php echo $f1['sexo']; ?></strong></td><br>
   
    </div>

    <div class="col">
<?php $date = date_create($f1['fecha']);
$date1 = date_create($f1['fecnac']);

function bisiesto($anio_actual){
    $bisiesto=false;
    //probamos si el mes de febrero del año actual tiene 29 días
      if (checkdate(2,29,$anio_actual))
      {
        $bisiesto=true;
    }
    return $bisiesto;
}

$fecha_actual = date("Y-m-d");
$fecha_nac=$pac_fecnac;
$fecha_de_nacimiento =strval($fecha_nac);

// separamos en partes las fechas
$array_nacimiento = explode ( "-", $fecha_de_nacimiento );
$array_actual = explode ( "-", $fecha_actual );

$anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos años
$meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses
$dias =  $array_actual[2] - $array_nacimiento[2]; // calculamos días

//ajuste de posible negativo en $días
if ($dias < 0)
{
    --$meses;

    //ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual
    switch ($array_actual[1]) {
           case 1:     $dias_mes_anterior=31; break;
           case 2:     $dias_mes_anterior=31; break;
           case 3: 
               if (bisiesto($array_actual[0]))
                {
                    $dias_mes_anterior=29; break;
                } else {
                    $dias_mes_anterior=28; break;
                }
           case 4:     $dias_mes_anterior=31; break;
           case 5:     $dias_mes_anterior=30; break;
           case 6:     $dias_mes_anterior=31; break;
           case 7:     $dias_mes_anterior=30; break;
           case 8:     $dias_mes_anterior=31; break;
           case 9:     $dias_mes_anterior=31; break;
           case 10:     $dias_mes_anterior=30; break;
           case 11:     $dias_mes_anterior=31; break;
           case 12:     $dias_mes_anterior=30; break;
    }

    $dias=$dias + $dias_mes_anterior;
}

//ajuste de posible negativo en $meses
if ($meses < 0)
{
    --$anos;
    $meses=$meses + 12;
}

//echo "<br>Tu edad es: $anos años con $meses meses y $dias días";



 ?>
 <!-- TERMINO DE FUNCION DE CALCULAR EDAD -->



      Fecha de admisión : <td><strong><?php echo date_format($date, "d/m/Y"); ?></strong></td> <br>
     Edad: <td><strong><?php if($anos > "0" ){
   echo $anos." años";
}elseif($anos <="0" && $meses>"0"){
    echo $meses." meses";
}elseif($anos <="0" && $meses<="0" && $dias>"0"){
    echo $dias." dias";
}
?></td></strong><br>
    Fecha de nacimiento : <td><strong><?php echo date_format($date1, "d/m/Y"); ?></strong></td><br>
      
    <?php  
                    }
                    ?> 
    </div>

<?php
$resultado2 = $conexion->query("select * from cat_camas WHERE id_atencion=" .$_SESSION['hospital']) or die($conexion->error);
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
Ocupación : <td><strong><?php echo $ocup ?></strong></td><br> 

    </div>
</div>
  <div class="row">
      <div class="col-sm-4">
          <label>Responsable :</label><strong> <?php echo $resp ?> </strong>
      </div>
      <div class="col-sm">
      <label>Parentesco : </label><strong> <?php echo $paren ?></strong>
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
<form action="pdf_consent_BI_medico.php?id_atencion=<?= $id_atencion ?>&id_exp=<?= $id_exp ?>" method="POST" target="_blank">
  <div class="row">
    <div class="col">
      <hr>

      <label><strong>Nombre del Servicio o Especialidad Médica:</strong></label>
      <input type="text" name="medico_servicio" class="form-control" required>

      <label class="mt-3">
        Los médicos del servicio arriba mencionado me han informado sobre mi(s) padecimiento(s), por lo que necesito someterme a estudios, procedimientos y tratamientos médicos considerados como necesarios para recuperar mi salud.
      </label>

      <label class="mt-3"><strong>Diagnóstico clínico principal (formato libre o CIE-10):</strong></label>
      <input type="text" name="diagnostico_pdf" class="form-control" required>

      <label class="mt-3"><strong>Estudios de laboratorio, gabinete e histopatológicos indicados:</strong></label>
      <input type="text" name="estudios" class="form-control"
        placeholder="Ejemplo: biometría hemática, química sanguínea, radiografías..." required>

      <label class="mt-3"><strong>Actos anestésicos requeridos:</strong></label>
      <input type="text" name="actos" class="form-control"
        placeholder="Ejemplo: anestesia local/regional/general, previamente indicada por su médico." required>

      <label class="mt-3"><strong>Tratamientos médicos:</strong></label>
      <input type="text" name="trat" class="form-control"
        placeholder="Ejemplo: medicamentos, sondas, soluciones intravenosas..." required>

      <label class="mt-3"><strong>Tratamientos quirúrgicos:</strong></label>
      <input type="text" name="tratquir" class="form-control"
        placeholder="Ejemplo: cirugía oftálmica, indicada por su médico tratante." required>

      <label class="mt-3"><strong>Riesgos y complicaciones informadas:</strong></label>
      <input type="text" name="ries" class="form-control"
        placeholder="Ejemplo: infección, hemorragia, reacciones adversas, riesgo vital..." required>

      <hr class="my-4">
      <p>He sido informado(a) de los riesgos que entrañan los procedimientos, por lo que acepto voluntariamente someterme a los mismos.</p>
      <p>Autorizo a los médicos del hospital a realizar los estudios, tratamientos y procedimientos necesarios, incluyendo aquellos ante cualquier complicación o urgencia médica.</p>
      <p>Declaro que tengo plena libertad de revocar esta autorización en cualquier momento antes del procedimiento.</p>
      <p>En caso de ser menor de edad o tener capacidades diferentes, se informa y autoriza al responsable del paciente.</p>

      <center class="mt-4">
        <button type="submit" name="btn" class="btn btn-primary">
          <strong>Generar PDF</strong>
        </button>
        <button type="button" class="btn btn-danger" onclick="history.back()">Cancelar</button>
      </center>

    </div>
  </div>
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