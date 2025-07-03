<?php
session_start();
//include "../../conexionbd.php";
include "../header_enfermera.php";
$resultado = $conexion->query("select * from reg_usuarios") or die($conexion->error);
$usuario = $_SESSION['login'];
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
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
          integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <!---
    <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css'>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
  -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

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
    <script>
        // Write on keyup event of keyword input element
        $(document).ready(function () {
            $("#search_dep").keyup(function () {
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
    <title>REGISTRO CLINICO QUIRÚRGICO</title>
    <style>
        hr.new4 {
            border: 1px solid red;
        }
        .card-container {
    display: flex;
    gap: 20px;
}
.card {
    flex: 1;
    padding: 15px;
    border: 1px solid #ccc;
    border-radius: 10px;
    background: #f9f9f9;
}
.card h4 {
    margin-bottom: 15px;
}
    </style>

</head>

<body>
<section class="content container-fluid">

    <?php
    include "../../conexionbd.php";

    if (isset($_SESSION['pac'])) {
    $id_atencion = $_SESSION['pac'];
    
    $sql_pac = "SELECT 
    p.sapell, p.papell, p.nom_pac, p.dir, 
    p.id_edo, p.id_mun, p.Id_exp AS id_exp, p.tel, 
    p.fecnac, p.tip_san, p.sexo, p.folio,
    di.fecha, di.area, di.alta_med, di.alergias,
    di.id_usua
FROM paciente p
INNER JOIN dat_ingreso di ON p.Id_exp = di.Id_exp
WHERE di.id_atencion = $id_atencion";


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
        $id_exp = $row_pac['id_exp'];
        $alergias = $row_pac['alergias'];
        $folio = $row_pac['folio'];
        $pac_id_usua = $row_pac['id_usua']; // ✅ Aquí está el id_usua
    }

    $sql_now = "SELECT DATE_ADD(NOW(), INTERVAL 12 HOUR) as dat_now FROM dat_ingreso WHERE id_atencion = $id_atencion";
    $result_now = $conexion->query($sql_now);
    while ($row_now = $result_now->fetch_assoc()) {
        $dat_now = $row_now['dat_now'];
    }

    $sql_est = "SELECT DATEDIFF('$dat_now', fecha) as estancia FROM dat_ingreso WHERE id_atencion = $id_atencion";
    $result_est = $conexion->query($sql_est);
    while ($row_est = $result_est->fetch_assoc()) {
        $estancia = $row_est['estancia'];
    }

      ///inicio bisiesto
function bisiesto($anio_actual){
    $bisiesto=false;
    //probamos si el mes de febrero del año actual tiene 29 días
      if (checkdate(2,29,$anio_actual))
      {
        $bisiesto=true;
    }
    return $bisiesto;
}

date_default_timezone_set('America/Mexico_City');
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
      <div class="container">
        <div class="content">
         <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 25px;">
                 <tr><strong><center>NOTA CIRUGIA SEGURA (LISTADO DE VERIFICACIÓN DE SEGURIDAD QUIRÚRGICA)</center></strong>
        </div>
         <hr>
 <font size="2">
         <div class="container">
  <div class="row">
    <div class="col-sm-6">
    Expediente: <strong><?php echo $folio?> </strong>
     Paciente: <strong><?php echo $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac ?></strong>
    </div>
    <div class="col-sm">
      Área: <strong><?php echo $area ?></strong>
    </div>
   <?php $date = date_create($pac_fecing);
   ?>
      <div class="col-sm">
      Fecha de ingreso: <strong><?php echo date_format($date, "d-m-Y") ?></strong>
    </div>
  </div>
</div></font>

 <font size="2">
     <div class="container">
  <div class="row">
    <div class="col-sm">
       <?php $date1 = date_create($pac_fecnac);
   ?>
      Fecha de nacimiento: <strong><?php echo date_format($date1, "d-m-Y") ?></strong>
    </div>
    <div class="col-sm">
      Tipo de sangre: <strong><?php echo $pac_tip_sang ?></strong>
    </div>
   
      <div class="col-sm">
      Habitación: <strong><?php $sql_hab = "SELECT num_cama from cat_camas where id_atencion =$id_atencion";
$result_hab = $conexion->query($sql_hab);                                                                                    while ($row_hab = $result_hab->fetch_assoc()) {
  echo $row_hab['num_cama'];
} ?></strong>
    </div>
    <div class="col-sm">
      Tiempo estancia: <strong><?php echo $estancia ?> Dias</strong>
    </div>
  </div>
</div>
</font>
 <font size="2">
  <div class="container">
  <div class="row">
   <div class="col-sm-3">
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

      Peso: <strong><?php $sql_vit = "SELECT * from dat_hclinica where Id_exp=$id_exp ORDER by id_hc DESC LIMIT 1";
      
$result_vit = $conexion->query($sql_vit);                                                                                    while ($row_vit = $result_vit->fetch_assoc()) {
    $peso=$row_vit['peso'];

} if (!isset($peso)){
    $peso=0;
   
}   echo $peso;?></strong>
    </div>
  
      <div class="col-sm">
      Talla: <strong><?php $sql_vitt =" SELECT * from dat_hclinica where Id_exp=$id_exp ORDER by id_hc DESC LIMIT 1";
$result_vitt = $conexion->query($sql_vitt);                                                                                    while ($row_vitt = $result_vitt->fetch_assoc()) {
    $talla=$row_vitt['talla'];
 
} if (!isset($talla)){
    
    $talla=0;
}   echo $talla;?></strong>
    </div>
 <div class="col-sm">
      Género: <strong><?php echo $pac_sexo ?></strong>
    </div>
     
  </div>
</div>
</font>
 <font size="2">
  <div class="container">
  <div class="row">
    <div class="col-sm-3">
      Alergias: <strong><?php echo $alergias ?></strong>
    </div>
    <div class="col-sm-6">
      Estado de salud: <strong><?php $sql_edo = "SELECT edo_salud from dat_ingreso where id_atencion=$id_atencion ORDER by edo_salud ASC LIMIT 1";
$result_edo = $conexion->query($sql_edo);                                                                                    while ($row_edo = $result_edo->fetch_assoc()) {
  echo $row_edo['edo_salud'];
} ?></strong>
    </div>
      
     <div class="col-sm">
    Aseguradora: <strong><?php $sql_aseg = "SELECT aseg from dat_financieros where id_atencion =$id_atencion ORDER BY fecha DESC limit 1";
$result_aseg = $conexion->query($sql_aseg);
while ($row_aseg = $result_aseg->fetch_assoc()) {
echo $row_aseg['aseg'];
} ?></strong>
    </div>
  </div>
</div>
</font>
 <font size="2">
<div class="container">
  <div class="row">
    <div class="col-sm-4">
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
       echo '<td> Diagnóstico: <strong>' . $d .'</strong></td>';
    } else{
          echo '<td"> Motivo de atención: <strong>' . $m .'</strong></td>';
    }?>
    </div>
  </div>
</div></font>
<hr>
 
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
        <input type="hidden" name="miembros_presentados" value="0">
        <label>
            <input type="checkbox" name="miembros_presentados" value="1">
            <strong>Confirmar que todos los miembros del equipo se hayan presentado por su nombre</strong>
        </label>
    </div>
    <hr>

    <div class="checkbox-group">
        <input type="hidden" name="confirmacion_identidad_equipo" value="0">
        <label>
            <input type="checkbox" name="confirmacion_identidad_equipo" value="1">
            <strong>Confirmar la identidad del paciente, el sitio quirúrgico y el procedimiento</strong>
        </label>
    </div>
    <hr>

    <div class="checkbox-group">
        <strong>¿Se ha administrado profilaxis antibiótica en los últimos 60 minutos?</strong><br>
        <input type="hidden" name="profilaxis_antibiotica_si" value="0">
        <input type="checkbox" name="profilaxis_antibiotica_si" value="1"> Sí<br>

        <input type="hidden" name="profilaxis_antibiotica_np" value="0">
        <input type="checkbox" name="profilaxis_antibiotica_np" value="1"> No procede
    </div>

    <hr>
    <strong>Previsión de eventos críticos</strong>

    <div class="checkbox-group">
      <input type="hidden" name="problemas_instrumental" value="0">
        <label>
                <input type="checkbox" name="pasos_criticos" value="1">
        <strong>Cirujano: ¿Cuáles serán los pasos críticos o no sistematizados?</strong> 
      </label>
    </div>

    <div class="checkbox-group">
      <input type="hidden" name="duracion_operacion" value="0">
        <label>
          <input type="checkbox" name="duracion_operacion" value="1">
          <strong>Cirujano: ¿Cuánto durará la operación?</strong>
        </label>
    </div>

    <div class="checkbox-group">
            <input type="hidden" name="perdida_sangre" value="0">
        <label>
          <input type="checkbox" name="perdida_sangre" value="1">
            <strong>Cirujano: ¿Cuál es la pérdida de sangre prevista?</strong>        
      </label>
    </div>

    <div class="checkbox-group">
            <input type="hidden" name="problemas_paciente" value="0">
        <label>
          <input type="checkbox" name="problemas_paciente" value="1">
              <strong>Anestesista: ¿Presenta el paciente algún problema específico?</strong>
          </label>
    </div>

    <div class="checkbox-group">
        <input type="hidden" name="esterilidad_confirmada" value="0">
        <label>
            <input type="checkbox" name="esterilidad_confirmada" value="1">
            <strong>¿Se ha confirmado la esterilidad (con resultados de los indicadores)?</strong>
        </label>
    </div>

    <div class="checkbox-group">
      <input type="hidden" name="problemas_instrumental" value="0">
        <label>
                <input type="checkbox" name="problemas_instrumental" value="1">
          <strong>¿Hay dudas o problemas relacionados con el instrumental y los equipos?</strong>
        </label>
    </div>

    <div class="checkbox-group">
        <strong>¿Pueden visualizarse las imágenes diagnósticas esenciales?</strong><br>
        <input type="hidden" name="imagenes_visibles_si" value="0">
        <input type="checkbox" name="imagenes_visibles_si" value="1"> Sí<br>

        <input type="hidden" name="imagenes_visibles_np" value="0">
        <input type="checkbox" name="imagenes_visibles_np" value="1"> No procede
    </div>
</div>


        <!-- Sección 3 -->
        <div class="card">
            <h4>Antes de salir del quirófano</h4>

            <div class="checkbox-group">
                    <input type="hidden" name="nombre_procedimiento" value="0">
                <label>
                  <input type="checkbox" name="nombre_procedimiento" value="1">
                  <strong>El enfermero confirma verbalmente: El nombre del procedimiento</strong>
                </label>
            </div>

            <div class="checkbox-group">
                    <input type="hidden" name="recuento_instrumental" value="0">
                    <label>
                      <input type="checkbox" name="recuento_instrumental" value="1">
                      <strong>El recuento de instrumentos, gasas y agujas</strong>
                    </label>
            </div>

            <div class="checkbox-group">
              <input type="hidden" name="etiquetado_muestras" value="0">
                    <label>
                    <input type="checkbox" name="etiquetado_muestras" value="1">
                <strong>El etiquetado de las muestras (lectura de la etiqueta en voz alta, incluido el nombre del paciente)</strong>
                    </label>
            </div>

            <div class="form-group">
              <input type="hidden" name="problemas_instrumental_final" value="0">
                <label>
                <input type="checkbox" name="problemas_instrumental_final" value="1">
                  <strong>Si hay problemas que resolver relacionados con el instrumental y los equipos</strong>
                </label>
            </div>

            <div class="form-group">
              <input type="hidden" name="aspectos_recuperacion" value="0">
                <label>
                  <strong>Cirujano, anestesista y enfermero:</strong><br>
                  <input type="checkbox" name="aspectos_recuperacion" value="1">
                ¿Cuáles son los aspectos críticos de la recuperación y el tratamiento del paciente?
              </label>
            </div>
        </div>
    </div>

    <br>
            <button type="submit" class="btn btn-primary">FIRMAR</button>
        <a href="../../template/select_pac_enf.php" class="btn btn-secondary">Cancelar</a>
</form>

</div> <!--TERMINO DE NOTA MEDICA div container-->


            <?php
            } else {
                echo '<script type="text/javascript"> window.location.href="../../template/select_pac_enf.php";</script>';
            }
            ?>
        </div>
    </div>
</section>
</div>

<footer class="main-footer">
    <?php
    include("../../template/footer.php");
    ?>
</footer>



<script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<!-- FastClick -->
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

<script type="text/javascript">
    $('.losInput8 input').on('change', function(){
  var total = 0;
  $('.losInput8 input').each(function() {
    if($( this ).val() != "")
    {
      total = total + parseFloat($( this ).val());
    }
  });
  $('.inputTotal8 input').val(total.toFixed());
});


    $('.losInput2 input').on('change', function(){
  var total = 0;
  $('.losInput2 input').each(function() {
    if($( this ).val() != "")
    {
      total = total + parseFloat($( this ).val());
    }
  });
  $('.inputTotal2 input').val(total.toFixed());
});

</script>

</body>

</html>