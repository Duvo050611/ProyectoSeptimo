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
  <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″ />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  <!--  Bootstrap  -->
  <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

  <!---
  <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
  <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css'>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

  <script>
    // Write on keyup event of keyword input element
    $(document).ready(function() {
      $("#search").keyup(function() {
        _this = this;
        // Show only matching TR, hide rest of them
        $.each($("#mytable tbody tr"), function() {
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
    $(document).ready(function() {
      $("#search_dep").keyup(function() {
        _this = this;
        // Show only matching TR, hide rest of them
        $.each($("#mytable tbody tr"), function() {
          if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
            $(this).hide();
          else
            $(this).show();
        });
      });
    });
  </script>
  <title>INDICACIONES MÉDICAS</title>
  <style type="text/css">
    #contenido{
        display: none;
    }
     #contenido3{
        display: none;
    }
     #contenido4{
        display: none;
    }

</style>
  
</head>

<body>
  <section class="content container-fluid">

    <!--------------------------
    | Your Page Content Here |
    -------------------------->



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
 function bisiesto($anio_actual){
    $bisiesto=false;
    //probamos si el mes de febrero del año actual tiene 29 días
      if (checkdate(2,29,$anio_actual))
      {
        $bisiesto=true;
    }
    return $bisiesto;
}

date_default_timezone_set('America/Guatemala');
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
<div class="container ">
    <div class="content">

        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
                 <tr><strong><center>INDICACIONES DEL MÉDICO</center></strong>
        </div>
          
<font size="2">
         <div class="container">
  <div class="row">
    <div class="col-sm-6">
      Expediente: <strong><?php echo $folio ?></strong>
       Paciente: <strong><?php echo $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac ?></strong>
    </div>
    <div class="col-sm">
      Área: <strong><?php echo $area ?></strong>
    </div>
   <?php $date = date_create($pac_fecing);
   ?>
      <div class="col-sm">
      Fecha de Ingreso: <strong><?php echo date_format($date, "d/m/Y") ?></strong>
    </div>
  </div>
</div></font>

 <font size="2">
     <div class="container">
  <div class="row">
    <div class="col-sm">
       <?php $date1 = date_create($pac_fecnac);
   ?>
      Fecha de nacimiento: <strong><?php echo date_format($date1, "d/m/Y") ?></strong>
    </div>
    <div class="col-sm">
      Tipo de Sangre: <strong><?php echo $pac_tip_sang ?></strong>
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
   echo $anos." Años";
}elseif($anos <="0" && $meses>"0"){
    echo $meses." Meses";
}elseif($anos <="0" && $meses<="0" && $dias>"0"){
    echo $dias." Días";
}
?></strong>
  </div>
  <div class="col-sm-3">
      Peso: <strong><?php $sql_vit = "SELECT * from dat_hclinica where Id_exp=$id_exp ORDER by id_hc DESC LIMIT 1";
      
      $result_vit = $conexion->query($sql_vit);                                                                                    while ($row_vit = $result_vit->fetch_assoc()) {
      $peso=$row_vit['peso'];

      } if (!isset($peso)){
        $peso=0;
   
      }   
      echo $peso;?></strong>
  </div>
  
  <div class="col-sm">
      Talla: <strong><?php $sql_vitt =" SELECT * from dat_hclinica where Id_exp=$id_exp ORDER by id_hc DESC LIMIT 1";
      $result_vitt = $conexion->query($sql_vitt); 
      while ($row_vitt = $result_vitt->fetch_assoc()) 
      {
        $talla=$row_vitt['talla'];
      }
      if(!isset($talla)){
        $talla=0;
      } echo $talla;?></strong>
 
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
        Estado de Salud: <strong><?php $sql_edo = "SELECT edo_salud from dat_ingreso where id_atencion=$id_atencion ORDER by edo_salud ASC LIMIT 1";
        $result_edo = $conexion->query($sql_edo);
        while ($row_edo = $result_edo->fetch_assoc()) {
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
</font>

<hr>
  <?php
      } 
        ?>
      
      <?php

include "../../conexionbd.php";
$id_ord=$_GET['id_ord'];
$resultado1 = $conexion->query("SELECT * from dat_ordenes_med  WHERE id_ord_med=$id_ord ") or die($conexion->error);

  $sql_visto="UPDATE dat_ordenes_med SET visto='SI' WHERE id_ord_med=$id_ord" ;
  $result_visto=$conexion->query($sql_visto);     
?>
  <?php
                    while ($f1 = mysqli_fetch_array($resultado1)) {

                        ?>
<div class="container box">
  <div class="row">
                <div class="col-sm-3">
                    Fecha: <strong><?php echo $f1['fecha_ord']; ?></strong> 
                </div>
                <div class="col-sm-3">
                    Hora: <strong><?php echo $f1['hora_ord']; ?></strong>
                </div>
            </div>
            <hr>

            <div class="row">
                <div class="col-sm-3">
                    <strong><label for="exampleFormControlTextarea1"><br><font size="3" color="#2b2d7f ">1.- Dieta: <button type="button" class="btn btn-success btn-sm" id="play"><i class="fas fa-play"></button></i></font></label></strong>
                </div>
                <div class="col-sm-4">
                    <div class="form-group"><br>
<input type="text" name="" id="texto" class="form-control col-sm-10" value="<?php echo $f1['dieta'];?>" disabled>

<script type="text/javascript">
const texto = document.getElementById('texto');
const btnPlayText = document.getElementById('play');

btnPlayText.addEventListener('click', () => {
        leerTexto(texto.value);
});

function leerTexto(texto){
    const speech = new SpeechSynthesisUtterance();
    speech.text= texto;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
                    </div>
                </div>
                <div class="col-sm-4">Detalle: <button type="button" class="btn btn-success btn-sm" id="playd"><i class="fas fa-play"></button></i><br>
                    <textarea class="form-control" name="detalle_lab" rows="1" disabled id="txtdd"><?php 
                    echo $f1['det_dieta']?></textarea>
                </div>
            </div>
<script type="text/javascript">

const txtdd = document.getElementById('txtdd');
const btnPlayTextd = document.getElementById('playd');

btnPlayTextd.addEventListener('click', () => {
        leerTexto(txtdd.value);
});

function leerTexto(txtdd){
    const sd = new SpeechSynthesisUtterance();
    sd.text= txtdd;
    sd.volume=1;
    sd.rate=1;
    sd.pitch=0;
    window.speechSynthesis.speak(sd);
}
</script>
            <div class="row">
                <div class="col-sm-3">
                    <strong><label><font size="3" color="#2b2d7f ">
                    2.- Cuidados Generales:</font></label></strong>
                </div>
                
            </div>
            
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#2b2d7f "> Signos Vitales por turno</font></strong>
                    </div>
                    <div class="col-sm-1">
                        <?php echo $f1['signos']; ?>
                    </div>
                    <div class="col-sm-5">
                        <?php echo $f1['detsignos']; ?>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#2b2d7f ">Monitoreo constante</font></strong>
                    </div>
                    <div class="col-sm-1">
                        <?php echo $f1['monitoreo']; ?>
                    </div>
                    <div class="col-sm-5">
                        <?php echo $f1['detmonitoreo']; ?>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#2b2d7f ">Diuresis por turno</font></strong>
                    </div>
                    <div class="col-sm-1">
                        <?php echo $f1['diuresis']; ?>
                    </div>
                    <div class="col-sm-5">
                        <?php echo $f1['detdiuresis']; ?>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#2b2d7f "> Dextrostix por turno</font></strong>
                    </div>
                    <div class="col-sm-1">
                        <?php echo $f1['dex']; ?>
                    </div>
                    <div class="col-sm-6">
                        <?php echo $f1['detdex']; ?>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#2b2d7f ">Posición Semiflower</font></strong>
                    </div>
                    <div class="col-sm-1">
                       <?php echo $f1['semif']; ?>
                    </div>
                    <div class="col-sm-5">
                       <?php echo $f1['detsemif']; ?>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#2b2d7f ">Vigilar datos de irrigación peritoneal</font></strong>
                    </div>
                    <div class="col-sm-1">
                        <?php echo $f1['vigilar']; ?>
                    </div>
                    <div class="col-sm-5">
                        <?php echo $f1['detvigilar']; ?>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#2b2d7f ">Oxígeno</font></strong>
                    </div>
                    <div class="col-sm-1">
                        <?php echo $f1['oxigeno']; ?>
                    </div>
                    <div class="col-sm-5">
                        <?php echo $f1['detoxigeno']; ?>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#2b2d7f ">Nebulizaciones</font></strong>
                    </div>
                    <div class="col-sm-1">
                        <?php echo $f1['nebulizacion']; ?>
                    </div>
                    <div class="col-sm-5">
                        <?php echo $f1['detnebu']; ?>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#2b2d7f ">Barandales en alto</font></strong>
                    </div>
                    <div class="col-sm-1">
                        <?php echo $f1['bar']; ?>
                    </div>
                    <div class="col-sm-5">
                        <?php echo $f1['detbar']; ?>
                    </div>

                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#2b2d7f ">Baño diario y deambulación asistida</font></strong>
                    </div>
                    <div class="col-sm-1">
                        <?php echo $f1['baño']; ?>
                    </div>
                    <div class="col-sm-5">
                        <?php echo $f1['detbaño']; ?>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#2b2d7f ">Cuidados de sonda Foley</font></strong>
                    </div>
                    <div class="col-sm-1">
                        <?php echo $f1['foley']; ?>
                    </div>
                    <div class="col-sm-5">
                        <?php echo $f1['detfoley']; ?>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#2b2d7f ">Ejercicios Respitarorios</font></strong>
                    </div>
                    <div class="col-sm-1">
                        <?php echo $f1['ej']; ?>
                    </div>
                    <div class="col-sm-5">
                        <?php echo $f1['detej']; ?>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#2b2d7f ">Vigilar datos de sangrado</font></strong>
                    </div>
                    <div class="col-sm-1">
                        <?php echo $f1['datsan']; ?>
                    </div>
                    <div class="col-sm-5">
                        <?php echo $f1['detsan']; ?>
                    </div>
                </div>
            </div>
             
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#2b2d7f">Glucometría capilar C/6Hrs <br>
                            Reportar <80 ó >180 Mg/Dl</font></strong>
                    </div>
                    <div class="col-sm-1">
                        <?php echo $f1['gca']; ?>
                    </div>
                    <div class="col-sm-5">
                        <?php echo $f1['gcat']; ?>
                    </div>
                </div>
            </div>

<div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#2b2d7f">Sonda nasogástrica a derivación</font></strong>
                    </div>
                    <div class="col-sm-1">
                        <?php echo $f1['son']; ?>
                    </div>
                    <div class="col-sm-5">
                        <?php echo $f1['sont']; ?>
                    </div>
                </div>
            </div>

            <div class="container">
             <div class="row">
                <div class="col-sm-4">
                    <strong><label><font size="3" color="#2b2d7f">Otros:</font></label></strong>
                </div>
                <div class="col-sm-1">
                       
                </div>
                <div class="col-sm-5">
                        <?php echo $f1['observ_be']; ?>
                </div>
            </div>
        </div>
            


            <br>
           <div class="container">
              <div class="row">
                <div class="col-3">
                    <strong><label for="exampleFormControlTextarea1"><font size="3" color="#2b2d7f ">3.- Inhaloterapia: <button type="button" class="btn btn-success btn-sm" id="playin"><i class="fas fa-play"></button></i></font></label></strong>
                </div>
                <div class=" col-9">

                    <div class="form-group">
                    <input type="text" name="" disabled id="txtin" class="form-control col-sm-11" value="<?php echo $f1['cuid_gen']; ?>">
                    </div>
                </div>
            </div>
            <script type="text/javascript">

const txtin = document.getElementById('txtin');
const btnPlayTextin = document.getElementById('playin');

btnPlayTextin.addEventListener('click', () => {
        leerTexto(txtin.value);
});

function leerTexto(txtin){
    const sd = new SpeechSynthesisUtterance();
    sd.text= txtin;
    sd.volume=1;
    sd.rate=1;
    sd.pitch=0;
    window.speechSynthesis.speak(sd);
}
</script>
            <div class="row">
                <div class="col-3">
                    <strong><label for="exampleFormControlTextarea1"><br><font size="3" color="#2b2d7f ">4.- Medicamentos: <button type="button" class="btn btn-success btn-sm" id="playmed"><i class="fas fa-play"></button></i></font></label></strong>
                </div>
                <div class=" col-9">
                    <div class="form-group"><br>
<!--<input type="text" name="" disabled id="txtmed" class="form-control col-sm-11" value="<?php// echo $f1['med_med']; ?>">-->
<textarea class="form-control col-sm-11" disabled><?php echo $f1['med_med']; ?></textarea>
                    </div>
                </div>
<script type="text/javascript">

const txtmed = document.getElementById('txtmed');
const btnPlayTextm = document.getElementById('playmed');

btnPlayTextm.addEventListener('click', () => {
        leerTexto(txtmed.value);
});

function leerTexto(txtmed){
    const sd = new SpeechSynthesisUtterance();
    sd.text= txtmed;
    sd.volume=1;
    sd.rate=1;
    sd.pitch=0;
    window.speechSynthesis.speak(sd);
}
</script>
            </div>

            <div class="row">
                <div class="col-3">
                    <strong><label for="exampleFormControlTextarea1"><br><font size="3" color="#2b2d7f ">5.- Soluciones: <button type="button" class="btn btn-success btn-sm" id="playsol"><i class="fas fa-play"></button></i></font></label></strong>
                </div>
                <div class=" col-9">
                    <div class="form-group"><br>
<input type="text" name="" disabled id="txtsol" class="form-control col-sm-11" value="<?php echo $f1['soluciones']; ?>">
                    </div>
                </div>
<script type="text/javascript">

const txtsol = document.getElementById('txtsol');
const btnPlayTexts = document.getElementById('playsol');

btnPlayTexts.addEventListener('click', () => {
        leerTexto(txtsol.value);
});

function leerTexto(txtsol){
    const sd = new SpeechSynthesisUtterance();
    sd.text= txtsol;
    sd.volume=1;
    sd.rate=1;
    sd.pitch=0;
    window.speechSynthesis.speak(sd);
}
</script>
            </div>

            <div class="row">
                <div class="col-sm-3">
                    <strong><label for="exampleFormControlTextarea1"><br><font size="3" color="#2b2d7f ">6.- Estudios laboratorio: <button type="button" class="btn btn-success btn-sm" id="playlab"><i class="fas fa-play"></button></i></font></label></strong>
                </div>
                <div class="col-sm-4">
                    <div class="form-group"><br>
<input type="text" name="" disabled id="txtla" class="form-control col-sm-11" value="<?php echo $f1['perfillab']; ?>">
                    </div>
                </div>
<script type="text/javascript">
const txtla = document.getElementById('txtla');
const btnPlayTextl = document.getElementById('playlab');

btnPlayTextl.addEventListener('click', () => {
        leerTexto(txtla.value);
});

function leerTexto(txtla){
    const sd = new SpeechSynthesisUtterance();
    sd.text= txtla;
    sd.volume=1;
    sd.rate=1;
    sd.pitch=0;
    window.speechSynthesis.speak(sd);
}
</script>
                <div class="col-sm-4">Detalle: <button type="button" class="btn btn-success btn-sm" id="playdl"><i class="fas fa-play"></button></i><br>
                   <textarea class="form-control" name="detalle_lab" rows="3" disabled id="txtdl"><?php 
                    echo $f1['detalle_lab']?></textarea>
                </div>
<script type="text/javascript">
const txtdl = document.getElementById('txtdl');
const btnPlayTextld = document.getElementById('playdl');

btnPlayTextld.addEventListener('click', () => {
        leerTexto(txtdl.value);
});

function leerTexto(txtdl){
    const sd = new SpeechSynthesisUtterance();
    sd.text= txtdl;
    sd.volume=1;
    sd.rate=1;
    sd.pitch=0;
    window.speechSynthesis.speak(sd);
}
</script>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <strong><label for="exampleFormControlTextarea1"><br><font size="3" color="#2b2d7f ">7.-Estudios imagenología:<button type="button" class="btn btn-success btn-sm" id="playimagen"><i class="fas fa-play"></button></i></font></label></strong>
                </div>
                <div class="col-sm-4">
                    <div class="form-group"><br>
<input type="text" name="" disabled id="txtim" class="form-control col-sm-11" value="<?php echo $f1['sol_estudios'];?>">
                    </div>
                </div>            
<script type="text/javascript">
const txtim = document.getElementById('txtim');
const btnPlayTextima = document.getElementById('playimagen');

btnPlayTextima.addEventListener('click', () => {
        leerTexto(txtim.value);
});

function leerTexto(txtim){
    const sd = new SpeechSynthesisUtterance();
    sd.text= txtim;
    sd.volume=1;
    sd.rate=1;
    sd.pitch=0;
    window.speechSynthesis.speak(sd);
}
</script>
<div class="col-sm-4">Detalle: <button type="button" class="btn btn-success btn-sm" id="plaen"><i class="fas fa-play"></button></i><br>
                   <textarea class="form-control" name="detalle_lab" rows="3" disabled id="txtnomm"><?php 
                    echo $f1['det_imagen']?></textarea>
                </div>
<script type="text/javascript">
const txtnomm = document.getElementById('txtnomm');
const btnPlaymm = document.getElementById('plaen');
btnPlaymm.addEventListener('click', () => {
        leerTexto(txtnomm.value);
});
function leerTexto(txtnomm){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtnomm;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
            </div>

            <div class="row">
                <div class="col-sm-3">
                    <strong><label for="exampleFormControlTextarea1"><br><font size="3" color="#2b2d7f ">8.- Solicitud Banco de Sangre: <button type="button" class="btn btn-success btn-sm" id="playbanco"><i class="fas fa-play"></button></i></font></label></strong>
                </div>
                <div class="col-sm-4">

                    <div class="form-group"><br>
<input type="text" disabled id="txtban" class="form-control col-sm-11" value="<?php echo $f1['solicitud_sang'];?>"> 
                    </div>
                    <script type="text/javascript">
const txtban = document.getElementById('txtban');
const btnPlayTextb = document.getElementById('playbanco');

btnPlayTextb.addEventListener('click', () => {
        leerTexto(txtban.value);
});

function leerTexto(txtban){
    const sd = new SpeechSynthesisUtterance();
    sd.text= txtban;
    sd.volume=1;
    sd.rate=1;
    sd.pitch=0;
    window.speechSynthesis.speak(sd);
}
</script>
                </div>
                <div class="col-sm-4">Detalle: <button type="button" class="btn btn-success btn-sm" id="playdetsan"><i class="fas fa-play"></button></i>
                    <textarea class="form-control" id="txtdetsan" name="det_sang" rows="3" disabled><?php echo $f1['det_sang']?></textarea>
                </div>
            </div>
           </div>
         <script type="text/javascript">
const txtdetsan = document.getElementById('txtdetsan');
const btnPlayTextdets = document.getElementById('playdetsan');

btnPlayTextdets.addEventListener('click', () => {
        leerTexto(txtdetsan.value);
});

function leerTexto(txtdetsan){
    const sd = new SpeechSynthesisUtterance();
    sd.text= txtdetsan;
    sd.volume=1;
    sd.rate=1;
    sd.pitch=0;
    window.speechSynthesis.speak(sd);
}
</script>    

<div class="row">
                <div class="col-sm-3">
                    <strong><label for="exampleFormControlTextarea1"><br><font size="3" color="#2b2d7f ">9.-Solicitud transfusión sanguínea: <button type="button" class="btn btn-success btn-sm" id="playenf2"><i class="fas fa-play"></button></i></font></label></strong>
                </div>
                <div class="col-sm-4">

                    <div class="form-group"><br>
<input type="text" disabled id="txt2n" class="form-control col-sm-11" value="<?php echo $f1['sol_pato'];?>"> 
                    </div>
<script type="text/javascript">
const txt2n = document.getElementById('txt2n');
const btnPlayenfer = document.getElementById('playenf2');
btnPlayenfer.addEventListener('click', () => {
        leerTexto(txt2n.value);
});
function leerTexto(txt2n){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txt2n;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
                </div>
                <div class="col-sm-4">Detalle: <button type="button" class="btn btn-success btn-sm" id="playries"><i class="fas fa-play"></button></i>
                    <textarea class="form-control" id="txtinar" name="det_sang" rows="3" disabled><?php echo $f1['det_pato']?></textarea>
                </div>
            </div>
           </div>
 <script type="text/javascript">
const txtinar = document.getElementById('txtinar');
const btnPlayrai = document.getElementById('playries');
btnPlayrai.addEventListener('click', () => {
        leerTexto(txtinar.value);
});
function leerTexto(txtinar){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtinar;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>

            <?php
}
?>
      <hr>
<br><br>
<div class="form-group col-12">
  <center>
<button type="button" class="btn btn-danger" onclick="history.back()">Regresar...</button></center>
</div><br>
      
</div>

</form>   
      </div>


  </section>

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
</body>
</html>