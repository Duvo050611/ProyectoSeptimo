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

  <link rel="stylesheet" type="text/css" href="css/select2.css">
    <link href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="js/select2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldLv/Pr4nhuBviF5jGqQK/5i2Q5iZ64dxBl+zOZ" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
</script>
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/main.js"></script>
  <style>
    .thead {
      background-color: #2b2d7f;
      color: white;
      font-size: 22px;
      padding: 10px;
      text-align: center;
    }
    .section-title {
      margin-top: 30px;
      margin-bottom: 20px;
      font-weight: 600;
      color: #2b2d7f;
      border-bottom: 2px solid #2b2d7f;
      padding-bottom: 5px;
    }
            hr.new4 {
            border: 1px solid red;
        }
        .card-container {
    display: flex;
    gap: 25px;
    margin: 20px 0;
}
.card {
    flex: 1;
    padding: 20px;
    border: 2px solid #e3e6f0;
    border-radius: 15px;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%);
    box-shadow: 0 4px 15px rgba(43, 45, 127, 0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(43, 45, 127, 0.15);
    border-color: #2b2d7f;
}
.card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #2b2d7f, #5a67d8);
}
.card h4 {
    margin-bottom: 20px;
    color: #2b2d7f;
    font-weight: 600;
    font-size: 18px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e3e6f0;
}
.checkbox-group {
    margin-bottom: 15px;
    padding: 10px;
    background: rgba(43, 45, 127, 0.02);
    border-radius: 8px;
    border-left: 3px solid #2b2d7f;
}
.checkbox-group strong {
    color: #2d3748;
    font-size: 14px;
    line-height: 1.4;
}
.checkbox-group input[type="checkbox"] {
    margin-right: 8px;
    transform: scale(1.2);
    accent-color: #2b2d7f;
}
.checkbox-group label {
    cursor: pointer;
    display: flex;
    align-items: flex-start;
    gap: 8px;
    margin-bottom: 5px;
}
.form-group {
    margin-bottom: 15px;
    padding: 10px;
    background: rgba(43, 45, 127, 0.02);
    border-radius: 8px;
    border-left: 3px solid #2b2d7f;
}
.btn {
    padding: 12px 30px;
    border-radius: 25px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    border: none;
    margin: 0 10px;
}

  </style>
<title>HOJA DE CIRUGIA SEGURA </title>
</head>
<body>

<div class="col-sm-12">
<div class="container">
<div class="row">
<div class="col-12">
                
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 24px;">
<strong><center>DATOS DEL PACIENTE </center></strong>
</div>
                <hr>
<?php

    include "../../conexionbd.php";

    if (isset($_SESSION['pac'])) {
      $id_atencion = $_SESSION['pac'];

$sql_pac = "
SELECT 
    p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, 
    p.Id_exp, p.tel, p.fecnac, p.tip_san, di.fecha, di.area, 
    di.alta_med, p.sexo, di.alergias, p.folio, 
    di.id_usua, di.id_atencion
FROM 
    paciente p
INNER JOIN 
    dat_ingreso di ON p.Id_exp = di.Id_exp 
WHERE 
    di.id_atencion = $id_atencion
";

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
    $pac_id_usua = $row_pac['id_usua'];
    $id_atencion = $row_pac['id_atencion'];
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
    <div class="col-sm-3">
      Expediente: <strong><?php echo $folio?></strong>
    </div>
    <div class="col-sm-6">
     Paciente: <strong><?php echo $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac ?></strong>
    </div>
   <?php $date = date_create($pac_fecing);
   ?>
    <div class="col-sm-3">
      Fecha de atención: <strong><?php echo date_format($date, "d-m-Y H:i:s") ?></strong>
    </div>
  </div>
</font>
 <font size="2">
     
  <div class="row">
    <div class="col-sm-3">
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
    <div class="col-sm-6">
      Edad: <strong><?php if($anos > "0" ){
   echo $anos." años";
}elseif($anos <="0" && $meses>"0"){
    echo $meses." meses";
}elseif($anos <="0" && $meses<="0" && $dias>"0"){
    echo $dias." dias";
}
?></strong>
    </div>
    <div class="col-sm-3">
      Área: <strong><?php echo $area ?></strong>
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

    <div class="col-sm-12">
      Motivo de atención: <strong><?php 
      if ($d!=null) {
         echo $d;
      } else{
            echo $m;
      }?></strong>
    </div>
  </div>

</font>
 <font size="2">
  <div class="row">
    <div class="col-sm-3">
      <!-- Espacio vacío para alineación -->
    </div>
    <div class="col-sm-6">
      <!-- Espacio vacío para alineación -->
    </div>
    <div class="col-sm-3">
      Alergias: <strong><?php echo $alergias ?></strong>
    </div>
  </div>

</font>
<hr>

<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 24px;">
<strong><center>HOJA DE CIRUGIA SEGURA</center></strong>
</div>
                <hr>
</div>
        <?php
      } else {
        echo '<script type="text/javascript"> window.location.href="../lista_pacientes/lista_pacientes.php";</script>';
      }
        ?>
        
<form action="insertar_cir_seg.php" method="POST">
  <input type="hidden" name="id_exp" value="<?= htmlspecialchars($id_exp) ?>">
  <input type="hidden" name="id_usua" value="<?= htmlspecialchars($pac_id_usua) ?>">
  <input type="hidden" name="id_atencion" value="<?= htmlspecialchars($id_atencion) ?>">

  <div class="card-container">
    <!-- Sección 1 -->
    <div class="card">
      <h4>Con el enfermero y el anestesista</h4>

      <div class="checkbox-group">
        <strong>¿Ha confirmado el paciente su identidad, el sitio quirúrgico, el procedimiento y su consentimiento?</strong><br>
        <input type="checkbox" name="confirmacion_identidad" value="Sí"> Sí
      </div>
      <hr>

      <div class="checkbox-group">
        <strong>¿Se ha marcado el sitio quirúrgico?</strong><br>
        <input type="checkbox" name="sitio_marcado[]" value="Sí"> Sí<br>
        <input type="checkbox" name="sitio_marcado[]" value="No procede"> No procede
      </div>
      <hr>

      <div class="checkbox-group">
        <strong>¿Se ha completado la comprobación de los aparatos de anestesia y la medicación anestésica?</strong><br>
        <input type="checkbox" name="verificacion_anestesia" value="Sí"> Sí
      </div>
      <hr>

      <div class="checkbox-group">
        <strong>¿Se ha colocado el pulsioximetro al paciente y funciona?</strong><br>
        <input type="checkbox" name="pulsioximetro" value="Sí"> Sí
      </div>
      <hr>

      <div class="checkbox-group">
        <strong>¿Tiene el paciente alergias conocidas?</strong><br>
        <input type="checkbox" name="alergias[]" value="No"> No<br>
        <input type="checkbox" name="alergias[]" value="Sí"> Sí
      </div>

      <div class="checkbox-group">
        <strong>¿Tiene el paciente vía aérea difícil / riesgo de aspiración?</strong><br>
        <input type="checkbox" name="via_aerea_dificil[]" value="No"> No<br>
        <input type="checkbox" name="via_aerea_dificil[]" value="Sí, y hay materiales y equipos / ayuda disponible"> Sí, y hay materiales y equipos / ayuda disponible
      </div>

      <div class="checkbox-group">
        <strong>¿Riesgo de hemorragia &gt; 500 ml (7 ml/kg en niños)?</strong><br>
        <input type="checkbox" name="riesgo_hemorragia[]" value="No"> No<br>
        <input type="checkbox" name="riesgo_hemorragia[]" value="Sí, y se ha previsto la disponibilidad de líquidos y dos vías IV o centrales"> Sí, y se ha previsto la disponibilidad de líquidos y dos vías IV o centrales
      </div>
    </div>

    <!-- Sección 2 -->
    <div class="card">
      <h4>Con el enfermero, el anestesista y el cirujano</h4>

      <div class="checkbox-group">
        <label>
          <input type="checkbox" name="miembros_presentados" value="1">
          <strong>Confirmar que todos los miembros del equipo se hayan presentado por su nombre</strong>
        </label>
      </div>
      <hr>

      <div class="checkbox-group">
        <label>
          <input type="checkbox" name="confirmacion_identidad_equipo" value="1">
          <strong>Confirmar la identidad del paciente, el sitio quirúrgico y el procedimiento</strong>
        </label>
      </div>
      <hr>

      <div class="checkbox-group">
        <strong>¿Se ha administrado profilaxis antibiótica en los últimos 60 minutos?</strong><br>
        <input type="checkbox" name="profilaxis_antibiotica_si" value="1"> Sí<br>
        <input type="checkbox" name="profilaxis_antibiotica_np" value="1"> No procede
      </div>
      <hr>

      <strong>Previsión de eventos críticos</strong>

      <div class="checkbox-group">
        <label>
          <input type="checkbox" name="problemas_instrumental" value="1">
          <strong>¿Hay dudas o problemas relacionados con el instrumental y los equipos?</strong>
        </label>
      </div>

      <div class="checkbox-group">
        <label>
          <input type="checkbox" name="duracion_operacion" value="1">
          <strong>Cirujano: ¿Cuánto durará la operación?</strong>
        </label>
      </div>

      <div class="checkbox-group">
        <label>
          <input type="checkbox" name="perdida_sangre" value="1">
          <strong>Cirujano: ¿Cuál es la pérdida de sangre prevista?</strong>
        </label>
      </div>

      <div class="checkbox-group">
        <label>
          <input type="checkbox" name="problemas_paciente" value="1">
          <strong>Anestesista: ¿Presenta el paciente algún problema específico?</strong>
        </label>
      </div>

      <div class="checkbox-group">
        <label>
          <input type="checkbox" name="esterilidad_confirmada" value="1">
          <strong>¿Se ha confirmado la esterilidad (con resultados de los indicadores)?</strong>
        </label>
      </div>

      <div class="checkbox-group">
        <strong>¿Pueden visualizarse las imágenes diagnósticas esenciales?</strong><br>
        <input type="checkbox" name="imagenes_visibles_si" value="1"> Sí<br>
        <input type="checkbox" name="imagenes_visibles_np" value="1"> No procede
      </div>
    </div>

    <!-- Sección 3 -->
    <div class="card">
      <h4>Antes de salir del quirófano</h4>

      <div class="checkbox-group">
        <label>
          <input type="checkbox" name="nombre_procedimiento" value="1">
          <strong>El enfermero confirma verbalmente: El nombre del procedimiento</strong>
        </label>
      </div>

      <div class="checkbox-group">
        <label>
          <input type="checkbox" name="recuento_instrumental" value="1">
          <strong>El recuento de instrumentos, gasas y agujas</strong>
        </label>
      </div>

      <div class="checkbox-group">
        <label>
          <input type="checkbox" name="etiquetado_muestras" value="1">
          <strong>El etiquetado de las muestras (lectura de la etiqueta en voz alta, incluido el nombre del paciente)</strong>
        </label>
      </div>

      <div class="form-group">
        <label>
          <input type="checkbox" name="problemas_instrumental_final" value="1">
          <strong>Si hay problemas que resolver relacionados con el instrumental y los equipos</strong>
        </label>
      </div>

      <div class="form-group">
        <label>
          <strong>Cirujano, anestesista y enfermero:</strong><br>
          <input type="checkbox" name="aspectos_recuperacion" value="1">
          ¿Cuáles son los aspectos críticos de la recuperación y el tratamiento del paciente?
        </label>
      </div>
    </div>
  </div>

  <br>
  <div class="text-center">
    <button type="submit" class="btn btn-primary">FIRMAR</button>
    <a href="../../template/select_pac_enf.php" class="btn btn-danger">Cancelar</a>
  </div>
</form>

<footer class="main-footer">
        <?php
        include("../../template/footer.php");
        ?>
    </footer>

    <!-- FastClick -->
    <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>