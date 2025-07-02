<?php
session_start();
include "../../conexionbd.php";
include("../header_enfermera.php");
?>
<!DOCTYPE html>
<html>
<head>
   <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″/>

    <link rel="stylesheet" type="text/css" href="../../gestion_medica/hospitalizacion/css/select2.css">
    <script src="jquery-3.1.1.min.js"></script>
    <script src="../../gestion_medica/hospitalizacion/js/select2.js"></script>
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




    <title>HOJA DE PROGRAMACIÓN QUIRÚRGICA </title>
    <style type="text/css">
    .modal-lg { max-width: 65% !important; }
    
    $spacing: 12px;
$module: 48px;
:root {
  --progressW: 50%;
}
label {
  display: block;
  width: 90%;
  vertical-align: baseline;
}
.sliderBar {
  position: relative;
  margin: 0 $spacing;
}
[type=range] {
  -webkit-appearance: none;
  width: calc(100% - 50px);
  vertical-align: middle;
  border: none;
  outline: none;
}
@mixin track() { 
  -webkit-appearance: slider-horizontal;
  height: 2px;
  padding: 0;
  cursor: pointer;
  background: linear-gradient(to right, #99F 0%, #99F var(--progressW), #ccc var(--progressW), #ccc 100%);
}
@mixin thumb() { 
  box-sizing: border-box;/*FF*/
  -webkit-appearance: none;
  width: $module/2;
  height: $module/2;
  margin-top: -$spacing;
  border: $spacing/2 solid #eee;
  border-radius: 50%;
  background: #999;
  box-shadow: 0 1px 4px rgba(0, 0, 0, .5)
}
@mixin active() {
    width: $module*0.75;
    height: $module*0.75;
    margin-top: -$spacing*1.5;
    background: #99F;
}
@mixin progress() {
    background: #99F;
}


[type=range] {
  &::-webkit-slider-runnable-track { @include track }
  &::-moz-range-track { @include track }
  &::-ms-track { @include track }

  &::-webkit-slider-thumb { @include thumb }
  &::-moz-range-thumb { @include thumb }
  &::-ms-thumb { @include thumb }

  &:active::-webkit-slider-thumb { @include active }
  &:active::-moz-range-thumb { @include active }
  &:active::-ms-thumb { @include active }
  
  &::-moz-range-progress { @include progress }
  &::-ms-fill-upper { @include progress }
}
</style>
</head>
<body>

<div class="col-sm-12">
<div class="container">
<div class="row">
<div class="col-12">
                
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 24px;">
<strong><center>DATOS DEL PACIENTE</center></strong>
</div>
                <hr>
<?php

    include "../../conexionbd.php";

    if (isset($_SESSION['pac'])) {
      $id_atencion = $_SESSION['pac'];

      $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.tel, p.fecnac,p.tip_san, di.fecha, di.area, di.alta_med, p.sexo, di.alergias, p.folio FROM paciente p, dat_ingreso di WHERE p.Id_exp=di.Id_exp and di.id_atencion =$id_atencion";

      $result_pac = $conexion->query($sql_pac);

      while ($row_pac = $result_pac->fetch_assoc()) {
        $pac_papell = $row_pac['papell'];
        $pac_sapell = $row_pac['sapell'];
        $pac_nom_pac = $row_pac['nom_pac'];
        $pac_dir = $row_pac['dir'];
        $pac_id_edo = $row_pac['id_edo'];
        $pac_id_mun = $row_pac['id_mun'];
        $pac_tel = $row_pac['tel'];
        $pac_fecnac = $row_pac['fecnac'];
        $pac_fecing = $row_pac['fecha'];
        $pac_tip_sang = $row_pac['tip_san'];
        $pac_sexo = $row_pac['sexo'];
        $area = $row_pac['area'];
        $alta_med = $row_pac['alta_med'];
        $id_exp = $row_pac['Id_exp'];
        $alergias = $row_pac['alergias'];
        $folio = $row_pac['folio'];
      }

      $sql_now = "SELECT DATE_ADD(NOW(), INTERVAL 12 HOUR) as dat_now FROM dat_ingreso WHERE id_atencion = $id_atencion";

      $result_now = $conexion->query($sql_now);

      while ($row_now = $result_now->fetch_assoc()) {
        $dat_now = $row_now['dat_now'];
      }

      $sql_est = "SELECT DATEDIFF( '$dat_now' , fecha) as estancia FROM dat_ingreso WHERE id_atencion = $id_atencion";

      $result_est = $conexion->query($sql_est);

      while ($row_est = $result_est->fetch_assoc()) {
        $estancia = $row_est['estancia'];
      }


    ?>
 <font size="2">
         
  <div class="row">
    
    <div class="col-sm-4">
     Paciente: <strong><?php echo $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac ?></strong>
    </div>
    <div class="col-sm">
      Expediente: <strong><?php echo $folio?> </strong>
    </div>
   <?php $date = date_create($pac_fecing);
   ?>
      <div class="col-sm-4">
      Fecha de ingreso: <strong><?php echo date_format($date, "d-m-Y H:i:s") ?></strong>
    </div>
  </div>
</font>
 <font size="2">
     
  <div class="row">
    <div class="col-sm-4">
       <?php $date1 = date_create($pac_fecnac);
   ?>
    <!-- INICIO DE FUNCION DE CALCULAR EDAD -->
<?php 

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
                       $dias_mes_anterior=28; break;
                
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

function bisiesto($anio_actual){
    $bisiesto=false;
    //probamos si el mes de febrero del año actual tiene 29 días
      if (checkdate(2,29,$anio_actual))
      {
        $bisiesto=true;
    }
    return $bisiesto;
}

 ?>
 <!-- TERMINO DE FUNCION DE CALCULAR EDAD -->
      Fecha de nacimiento: <strong><?php echo date_format($date1, "d-m-Y") ?></strong>
    </div>
    <div class="col-sm-4">
      Edad: <strong><?php if($anos > "0" ){
   echo $anos." años";
}elseif($anos <="0" && $meses>"0"){
    echo $meses." meses";
}elseif($anos <="0" && $meses<="0" && $dias>"0"){
    echo $dias." dias";
}
?></strong>
    </div>
    
   
      <div class="col-sm-2">
      Habitación: <strong><?php $sql_hab = "SELECT num_cama from cat_camas where id_atencion =$id_atencion";
$result_hab = $conexion->query($sql_hab);                                                                                    while ($row_hab = $result_hab->fetch_assoc()) {
  echo $row_hab['num_cama'];
} ?></strong>
    </div>
    
  </div>

</font>
 <font size="2">
  <div class="row">
  <?php
$d="";
   $sql_motd = "SELECT diagprob_i from dat_nevol where id_atencion=$id_atencion ORDER by diagprob_i ASC LIMIT 1";
$result_motd = $conexion->query($sql_motd);
while ($row_motd = $result_motd->fetch_assoc()) {
    $d=$row_motd['diagprob_i'];
} ?>
<?php $sql_mot = "SELECT motivo_atn from dat_ingreso where id_atencion=$id_atencion ORDER by motivo_atn ASC LIMIT 1";
$result_mot = $conexion->query($sql_mot);
while ($row_mot = $result_mot->fetch_assoc()) {
$m=$row_mot['motivo_atn'];
} ?>

<?php if ($d!=null) {
   echo '<div class="col-sm-8"> Diagnóstico: <strong>' . $d .'</strong></div>';
} else{
      echo '<div class="col-sm-8"> Motivo de atención: <strong>' . $m .'</strong></div>';
}?>
    <div class="col-sm">
      Tiempo estancia: <strong><?php echo $estancia ?> Dias</strong>
    </div>
  </div>

</font>
 <font size="2">
  <div class="row">
    <div class="col-sm-4">
      Alergias: <strong><?php echo $alergias ?></strong>
    </div>
     <div class="col-sm-4">
      Estado de salud: <strong><?php $sql_edo = "SELECT edo_salud from dat_ingreso where id_atencion=$id_atencion ORDER by edo_salud ASC LIMIT 1";
$result_edo = $conexion->query($sql_edo);while ($row_edo = $result_edo->fetch_assoc()) {
  echo $row_edo['edo_salud'];
} ?></strong>
    </div>
    <div class="col-sm-3">
      Tipo de sangre: <strong><?php echo $pac_tip_sang ?></strong>
    </div>
  </div>
</font>
<?php $sql_edo = "SELECT * from dat_hclinica where Id_exp=$id_exp ORDER by id_hc DESC LIMIT 1";
$result_edo = $conexion->query($sql_edo);
while ($row_edo = $result_edo->fetch_assoc()) {
  $peso=$row_edo['peso'];
  $talla=$row_edo['talla'];
} 
if (!isset($peso)){
    $peso=0;
    $talla=0;
}?>
 <font size="2">
  <div class="row">
     <div class="col-sm-4">
      Peso: <strong><?php echo $peso ?></strong>
    </div>
    <div class="col-sm-3">
      Talla: <strong><?php echo $talla ?></strong>
    </div>
  </div>
</font>
<hr>
</div>
        <?php
      } else {
        echo '<script type="text/javascript"> window.location.href="../lista_pacientes/lista_pacientes.php";</script>';
      }
        ?>

<div class="container">
    <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 24px;">
<strong><center>HOJA DE PROGRAMACIÓN QUIRÚRGICA</center></strong>
</div><!-- Menú tipo acordeón con BLEFAROPLASTIA, FACOEMULSIFICACION, CROSSLINKING, INYECCION, PTERIGIÓN, CIRUGÍA REFRACTIVA, TRANSPLANTE, VALVULA DE AHMED, VITRECTOMIA y CIRUGÍA LASIK -->
    <form action="insertar_hoja.php" method="POST" onsubmit="return checkSubmit();">
<div class="card-header" id="headingRight">
    <div class="accordion mt-3" id="surgeryAccordion">
        <div class="card" id="blefaroCard">
            <div class="card-header">
                <h2 class="mb-0 d-flex flex-wrap gap-2">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseBlefaroMenu"
                        aria-expanded="true" aria-controls="collapseBlefaroMenu" style="color:#2b2d7f;">
                        BLEFAROPLASTIA
                    </button>
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseFacoMenu"
                        aria-expanded="false" aria-controls="collapseFacoMenu" style="color:#2b2d7f;">
                        FACOEMULSIFICACION
                    </button>
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseCrossMenu"
                        aria-expanded="false" aria-controls="collapseCrossMenu" style="color:#2b2d7f;">
                        CROSSLINKING
                    </button>
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseInyMenu"
                        aria-expanded="false" aria-controls="collapseInyMenu" style="color:#2b2d7f;">
                        INYECCION
                    </button>
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapsePterigionMenu"
                        aria-expanded="false" aria-controls="collapsePterigionMenu" style="color:#2b2d7f;">
                        PTERIGIÓN
                    </button>
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseRefractivaMenu"
                        aria-expanded="false" aria-controls="collapseRefractivaMenu" style="color:#2b2d7f;">
                        CIRUGÍA REFRACTIVA
                    </button>
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTransplanteMenu"
                        aria-expanded="false" aria-controls="collapseTransplanteMenu" style="color:#2b2d7f;">
                        TRANSPLANTE
                    </button>
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseAhmedMenu"
                        aria-expanded="false" aria-controls="collapseAhmedMenu" style="color:#2b2d7f;">
                        VALVULA DE AHMED
                    </button>
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseVitrectomiaMenu"
                        aria-expanded="false" aria-controls="collapseVitrectomiaMenu" style="color:#2b2d7f;">
                        VITRECTOMIA
                    </button>
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseLasikMenu"
                        aria-expanded="false" aria-controls="collapseLasikMenu" style="color:#2b2d7f;">
                        CIRUGÍA LASIK
                    </button>
                </h2>
            </div>
            <div id="collapseBlefaroMenu" class="collapse" data-parent="#surgeryAccordion">
                <div class="card-body">
                    <!-- Formulario BLEFAROPLASTIA -->
                    <form>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Antes de la cirugía<br>preoperatoria</th>
                                        <th>Durante la cirugía</th>
                                        <th>Después de la cirugía<br>postoperatoria</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Presión arterial</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Frecuencia respiratoria</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Temperatura</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Oxigenación</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Glucometría</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Frecuencia cardiaca</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Hora</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group mt-3">
                            <label>Nota de enfermería:</label>
                            <textarea class="form-control" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label>ENFERMERA RESPONSABLE:</label>
                            <input type="text" class="form-control">
                        </div>
                    </form>
                </div>
            </div>
            <div id="collapseFacoMenu" class="collapse" data-parent="#surgeryAccordion">
                <div class="card-body">
                    <!-- Formulario FACOEMULSIFICACION (igual a la imagen adjunta) -->
                    <form>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Antes de la cirugía<br>preoperatoria</th>
                                        <th>Durante la cirugía</th>
                                        <th>Después de la cirugía<br>postoperatoria</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Presión arterial</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Frecuencia respiratoria</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Temperatura</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Oxigenación</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Glucometría</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Frecuencia cardiaca</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group mt-3">
                            <label>Nota de enfermería:</label>
                            <textarea class="form-control" rows="5">Se recibe paciente consiente, se toman signos vitales, se aplica pontí Y TP en ojo_______ se canaliza paciente con sol ___________. Pasa a sala se monitorea paciente se abre bulto y prepara material. Empieza la cx con previa asepsia, se colocan campos y blefaros; Se realiza primera incisión con cuchilla__, se aplica azul y después visco; segundo puerto___se inicia ultrasonido, extrae cataratase coloca LIO irrigación y aspiración se coloca punto y se sella herida coloca gotas de antibiótico y parche. Sale paciente de la sala consciente y en buen estado.
__________________________________________________________________________________________________________
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label>ENFERMERA RESPONSABLE:</label>
                            <input type="text" class="form-control">
                        </div>
                    </form>
                </div>
            </div>
            <div id="collapseCrossMenu" class="collapse" data-parent="#surgeryAccordion">
                <div class="card-body">
                    <!-- Formulario CROSSLINKING (igual a la imagen adjunta) -->
                    <form>
                        <div class="form-group">
                            <label>Nota de enfermería:</label>
                            <p>Se recibe paciente en quirófano consciente con signos vitales:</p>
                            <div class="row mb-2">
                                <div class="col-12">
                                    <strong>ora:</strong>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-12">
                                    T/A <input type="text" style="width:90px;display:inline-block;">&nbsp;&nbsp;F.C <input type="text" style="width:60px;display:inline-block;">&nbsp;&nbsp;TEMP <input type="text" style="width:60px;display:inline-block;">&nbsp;&nbsp;OXIGENACION <input type="text" style="width:90px;display:inline-block;">&nbsp;&nbsp;HORA <input type="text" style="width:60px;display:inline-block;">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-12">
                                    T/A <input type="text" style="width:90px;display:inline-block;">&nbsp;&nbsp;F.C <input type="text" style="width:60px;display:inline-block;">&nbsp;&nbsp;TEMP <input type="text" style="width:60px;display:inline-block;">&nbsp;&nbsp;OXIGENACION <input type="text" style="width:90px;display:inline-block;">&nbsp;&nbsp;HORA <input type="text" style="width:60px;display:inline-block;">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label>Nota de enfermería:</label>
                            <textarea class="form-control" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label>ENFERMERA RESPONSABLE:</label>
                            <input type="text" class="form-control">
                        </div>
                    </form>
                </div>
            </div>
            <div id="collapseInyMenu" class="collapse" data-parent="#surgeryAccordion">
                <div class="card-body">
                    <!-- Formulario INYECCION (igual a la imagen adjunta) -->
                    <form>
                        <div class="form-group">
                            <label>Nota de enfermería:</label>
                            <p>Se recibe paciente en quirófano consciente con signos vitales</p>
                            <div class="row mb-2">
                                <div class="col-12">
                                    T/A <input type="text" style="width:90px;display:inline-block;">&nbsp;&nbsp;F.C <input type="text" style="width:60px;display:inline-block;">&nbsp;&nbsp;TEMP <input type="text" style="width:60px;display:inline-block;">&nbsp;&nbsp;OXIGENACION <input type="text" style="width:90px;display:inline-block;">
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <textarea class="form-control" rows="5">Se colocan gotas de ponti  en ojo ____________ se pasa a sala, se realiza asepsia, se coloca campo y blefaróstato, se aplica ponti, se mide, se aplica medicamento _____________, se colocan gotas de antibiótico, se coloca ______________ y se pasa paciente a recuperación.
Sale paciente se dan indicaciones y se da de alta.
                                </textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>ENFERMERA RESPONSABLE:</label>
                            <input type="text" class="form-control">
                        </div>
                    </form>
                </div>
            </div>
            <div id="collapsePterigionMenu" class="collapse" data-parent="#surgeryAccordion">
                <div class="card-body">
                    <!-- Formulario PTERIGIÓN (igual a la imagen adjunta) -->
                    <form>
                        <div class="form-group">
                            <label style="font-weight:bold;">Nota de enfermería: se recibe paciente en quirófano consciente con signos vitales</label>
                            <div class="row mb-2">
                                <div class="col-12">
                                    T/A <input type="text" style="width:90px;display:inline-block;">&nbsp;&nbsp;F.C <input type="text" style="width:60px;display:inline-block;">&nbsp;&nbsp;TEMP <input type="text" style="width:60px;display:inline-block;">&nbsp;&nbsp;OXIGENACION <input type="text" style="width:90px;display:inline-block;">&nbsp;&nbsp;HORA <input type="text" style="width:60px;display:inline-block;">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-12">
                                    T/A <input type="text" style="width:90px;display:inline-block;">&nbsp;&nbsp;F.C <input type="text" style="width:60px;display:inline-block;">&nbsp;&nbsp;TEMP <input type="text" style="width:60px;display:inline-block;">&nbsp;&nbsp;OXIGENACION <input type="text" style="width:90px;display:inline-block;">&nbsp;&nbsp;HORA <input type="text" style="width:60px;display:inline-block;">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label>Nota de enfermería:</label>
                            <textarea class="form-control" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label>ENFERMERA RESPONSABLE:</label>
                            <input type="text" class="form-control">
                        </div>
                    </form>
                </div>
            </div>
            <div id="collapseRefractivaMenu" class="collapse" data-parent="#surgeryAccordion">
                <div class="card-body">
                    <!-- Formulario CIRUGÍA REFRACTIVA (igual a la imagen adjunta) -->
                    <form>
                        <div class="form-group">
                            <label style="font-weight:bold;">Nota de enfermería:</label>
                            <p>Se recibe paciente en quirófano consciente con signos vitales</p>
                            <div class="row mb-2">
                                <div class="col-12">
                                    T/A <input type="text" style="width:90px;display:inline-block;">&nbsp;&nbsp;F.C <input type="text" style="width:60px;display:inline-block;">&nbsp;&nbsp;TEMP <input type="text" style="width:60px;display:inline-block;">&nbsp;&nbsp;OXIGENACION <input type="text" style="width:90px;display:inline-block;">&nbsp;&nbsp;HORA <input type="text" style="width:60px;display:inline-block;">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-12">
                                    T/A <input type="text" style="width:90px;display:inline-block;">&nbsp;&nbsp;F.C <input type="text" style="width:60px;display:inline-block;">&nbsp;&nbsp;TEMP <input type="text" style="width:60px;display:inline-block;">&nbsp;&nbsp;OXIGENACION <input type="text" style="width:90px;display:inline-block;">&nbsp;&nbsp;HORA <input type="text" style="width:60px;display:inline-block;">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <textarea class="form-control" rows="5">Se colocan gotas de ponti  en ojo ____________ se pasa a sala se realiza asepsia se coloca campo y blefaróstato  se aplica   ponti y procede a la colocación de láser, se colocan gotas de antibiótico ________________ y se pasa paciente a recuperación. Sale paciente se dan indicaciones y se da de alta.
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label>ENFERMERA RESPONSABLE:</label>
                            <input type="text" class="form-control">
                        </div>
                    </form>
                </div>
            </div>
            <div id="collapseTransplanteMenu" class="collapse" data-parent="#surgeryAccordion">
                <div class="card-body">
                    <!-- Formulario TRANSPLANTE (igual a la imagen adjunta) -->
                    <form>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Antes de la cirugía<br>preoperatoria</th>
                                        <th>Durante la cirugía</th>
                                        <th>Después de la cirugía<br>postoperatoria</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Presión arterial</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Frecuencia respiratoria</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Temperatura</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Oxigenación</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Glucometría</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Frecuencia cardiaca</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group mt-3">
                            <label>Nota de enfermería:</label>
                            <textarea class="form-control" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label>ENFERMERA RESPONSABLE:</label>
                            <input type="text" class="form-control">
                        </div>
                    </form>
                </div>
            </div>
            <div id="collapseAhmedMenu" class="collapse" data-parent="#surgeryAccordion">
                <div class="card-body">
                    <!-- Formulario VALVULA DE AHMED (igual a la imagen adjunta) -->
                    <form>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Antes de la cirugía<br>preoperatoria</th>
                                        <th>Durante la cirugía</th>
                                        <th>Después de la cirugía<br>postoperatoria</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Presión arterial</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Frecuencia respiratoria</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Temperatura</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Oxigenación</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Glucometría</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Frecuencia cardiaca</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Hora</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group mt-3">
                            <label>Nota de enfermería:</label>
                            <textarea class="form-control" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label>ENFERMERA RESPONSABLE:</label>
                            <input type="text" class="form-control">
                        </div>
                    </form>
                </div>
            </div>
            <div id="collapseVitrectomiaMenu" class="collapse" data-parent="#surgeryAccordion">
                <div class="card-body">
                    <!-- Formulario VITRECTOMIA (igual a la imagen adjunta) -->
                    <form>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Antes de la cirugía<br>preoperatoria</th>
                                        <th>Durante la cirugía</th>
                                        <th>Después de la cirugía<br>postoperatoria</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Presión arterial</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Frecuencia respiratoria</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Temperatura</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Oxigenación</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Glucometría</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Frecuencia cardiaca</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Hora</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group mt-3">
                            <label>Nota de enfermería:</label>
                            <textarea class="form-control" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label>ENFERMERA RESPONSABLE:</label>
                            <input type="text" class="form-control">
                        </div>
                    </form>
                </div>
            </div>
            <div id="collapseLasikMenu" class="collapse" data-parent="#surgeryAccordion">
                <div class="card-body">
                    <!-- Formulario CIRUGÍA LASIK editable -->
                    <form>
                        <div class="form-group">
                            <label style="font-weight:bold;">Nota de enfermería:</label>
                            <div class="row mb-2">
                                <div class="col-6">
                                    <div>OD</div>
                                    <div>
                                        <input type="text" style="width:40px;display:inline-block;"> =
                                        <input type="text" style="width:40px;display:inline-block;">*
                                        <input type="text" style="width:40px;display:inline-block;">
                                    </div>
                                    <div style="text-align:left;font-weight:bold;">QUERATOMETRIA</div>
                                    <div>
                                        <input type="text" style="width:40px;display:inline-block;"> =
                                        <input type="text" style="width:40px;display:inline-block;">*
                                        <input type="text" style="width:40px;display:inline-block;">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div>OI</div>
                                    <div>
                                        <input type="text" style="width:40px;display:inline-block;">=
                                        <input type="text" style="width:40px;display:inline-block;">*
                                        <input type="text" style="width:40px;display:inline-block;">
                                    </div>
                                    <div style="text-align:left;font-weight:bold;">QUERATOMETRIA</div>
                                    <div>
                                        <input type="text" style="width:40px;display:inline-block;">=
                                        <input type="text" style="width:40px;display:inline-block;">*
                                        <input type="text" style="width:40px;display:inline-block;">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-12">
                                <br>
                                    <input type="text" class="form-control mb-1" placeholder="MICROQUERATOMO">
                                    <br>
                                    <input type="text" class="form-control mb-1" placeholder="ANILLO">
                                    <br>
                                    <input type="text" class="form-control mb-1" placeholder="TOPE">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-12">
                                    Se recibe paciente en quirófano consciente con signos vitales
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-12">
                                    T/A <input type="text" style="width:90px;display:inline-block;">&nbsp;&nbsp;F.C <input type="text" style="width:60px;display:inline-block;">&nbsp;&nbsp;TEMP <input type="text" style="width:60px;display:inline-block;">&nbsp;&nbsp;OXIGENACION <input type="text" style="width:90px;display:inline-block;">&nbsp;&nbsp;HORA <input type="text" style="width:60px;display:inline-block;">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-12">
                                    T/A <input type="text" style="width:90px;display:inline-block;">&nbsp;&nbsp;F.C <input type="text" style="width:60px;display:inline-block;">&nbsp;&nbsp;TEMP <input type="text" style="width:60px;display:inline-block;">&nbsp;&nbsp;OXIGENACION <input type="text" style="width:90px;display:inline-block;">&nbsp;&nbsp;HORA <input type="text" style="width:60px;display:inline-block;">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <textarea class="form-control" rows="5">Se colocan gotas de ponti  en ojo ____________ se pasa a sala se realiza asepsia se coloca campo y blefaróstato, se aplica ponti, realizan corte con microqueratomo y procede a la colocación de láser, se colocan gotas de antibiótico ________________ y se pasa paciente a recuperación. Sale paciente se dan indicaciones y se da de alta.
                            </textarea>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>ENFERMERA RESPONSABLE:</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label>NOMBRE Y FIRMA DEL MEDICO</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Fin menú acordeón BLEFAROPLASTIA, FACOEMULSIFICACION, CROSSLINKING, INYECCION, PTERIGIÓN, CIRUGÍA REFRACTIVA, TRANSPLANTE, VALVULA DE AHMED, VITRECTOMIA y CIRUGÍA LASIK -->
</body>


        <center class="mt-3">
            <button type="submit" class="btn btn-primary">Firmar</button>
            <button type="button" class="btn btn-danger" onclick="history.back()">Cancelar</button>
        </center>
    </form>
</div>

    <script>
        let enviando = false;

        function checkSubmit() {
            if (!enviando) {
                enviando = true;
                return true;
            } else {
                alert("El formulario ya se esta enviando");
                return false;
            }
        }
    </script>
    <script>
function addInput(name, el) {
    var div = document.createElement('div');
    div.className = "mt-2";
    div.innerHTML = '<input type="text" class="form-control" name="'+name+'[]" placeholder="'+el.parentNode.previousElementSibling.firstElementChild.placeholder+'">';
    el.parentNode.parentNode.parentNode.insertBefore(div, el.parentNode.parentNode.nextSibling);
}
</script>
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
        document.oncontextmenu = function() {
            return false;
        }
    </script>
    <script>
$(document).ready(function() {
    // Asegura que solo un formulario se muestre a la vez
    $('#btnBlefaro').click(function() {
        $('#collapseBlefaro').collapse('toggle');
        // Cierra los demás formularios si están abiertos
        $(".collapse").not('#collapseBlefaro').collapse('hide');
    });
});
</script>


</html>