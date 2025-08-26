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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">



    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>


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
  <title>Datos Neonatal</title>
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

<div class="col-sm-12">
    <div class="container">
       
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
    $sql_pac = "SELECT * FROM  dat_ingreso WHERE id_atencion =$id_atencion";

          $result_pac = $conexion->query($sql_pac);

          while ($row_pac = $result_pac->fetch_assoc()) {
            $fingreso = $row_pac['fecha'];
             $fegreso = $row_pac['fec_egreso'];
             $alta_med = $row_pac['alta_med'];
             $alta_adm = $row_pac['alta_adm'];
             $activo = $row_pac['activo'];
             $valida = $row_pac['valida'];
          }

if($alta_med=='SI' && $alta_adm=='SI' && $activo=='NO' && $valida=='SI'){
    
    $sql_est = "SELECT DATEDIFF('$fegreso', '$fingreso') as estancia FROM dat_ingreso where id_atencion = $id_atencion";

      $result_est = $conexion->query($sql_est);

      while ($row_est = $result_est->fetch_assoc()) {
          $estancia = $row_est['estancia'];
       
      }
}else{
    
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

//date_default_timezone_set('America/Guatemala');
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
        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 25px;">
                 <tr><strong><center>REGISTRO CLÍNICO PEDIÁTRICO/NEONATAL</center></strong>
        </div>
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
$result_vit = $conexion->query($sql_vit);
while ($row_vit = $result_vit->fetch_assoc()) {
$peso=$row_vit['peso'];
}if(!isset($peso)){
    $peso=0;
}   echo $peso;?></strong>
    </div>
   <div class="col-sm">
      Talla: <strong><?php $sql_vitt =" SELECT * from dat_hclinica where Id_exp=$id_exp ORDER by id_hc DESC LIMIT 1";
$result_vitt = $conexion->query($sql_vitt); 
while ($row_vitt = $result_vitt->fetch_assoc()) {
$talla=$row_vitt['talla'];
}
if(!isset($talla)){
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
      Estado de salud: <strong><?php $sql_edo = "SELECT edo_salud from dat_ingreso where id_atencion=$id_atencion ORDER by edo_salud DESC LIMIT 1";
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
<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Signos vitales</a>
    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Ingresos</a>
    <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Egresos</a>
    <a class="nav-item nav-link" id="nav-med-tab" data-toggle="tab" href="#nav-med" role="tab" aria-controls="nav-med" aria-selected="false">Medicamentos</a>
    <a class="nav-item nav-link" id="nav-nota-tab" data-toggle="tab" href="#nav-nota" role="tab" aria-controls="nav-nota" aria-selected="false">Valoraciones y escalas</a>
     <a class="nav-item nav-link" id="nav-contactn-tab" data-toggle="tab" href="#nav-contactn" role="tab" aria-controls="nav-contactn" aria-selected="false"><h6>Notas de enfermeria</h6></a>
  </div>
</nav>
<div class="tab-content" id="nav-tabContent">
   <div class="tab-pane fade" id="nav-contactn" role="tabpanel" aria-labelledby="nav-contactn-tab">
<form action="" method="POST">
<p></p>
  <div class="row">
    <div class="col-sm-2">
     <strong>Fecha de reporte:</strong>
    </div>
    <div class="col-sm-3">
      <input type="date" value="<?php echo $fecha_actual = date("Y-m-d") ?>" name="fecha" class="form-control" required>
    </div>
    
    <div class="col-sm-1">
          <strong>Hora:</strong>
</div>
     <div class="col-sm-3">
            <select class="form-control" aria-label="Default select example" name="hora" required="">
  <option value="">Seleccionar hora</option>
  <option value="8">8:00 A.M.</option>
  <option value="9">9:00 A.M.</option>
  <option value="10">10:00 A.M.</option>
  <option value="11">11:00 A.M.</option>
  <option value="12">12:00 A.M.</option>
  <option value="13">13:00 P.M.</option>
  <option value="14">14:00 P.M.</option>
  <option value="15">15:00 P.M.</option>
  <option value="16">16:00 P.M.</option>
  <option value="17">17:00 P.M.</option>
  <option value="18">18:00 P.M.</option>
  <option value="19">19:00 P.M.</option>
  <option value="20">20:00 P.M.</option>
  <option value="21">21:00 P.M.</option>
  <option value="22">22:00 P.M.</option>
  <option value="23">23:00 P.M.</option>
  <option value="24">24:00 P.M.</option>
  <option value="1">1:00 A.M.</option>
  <option value="2">2:00 A.M.</option>
  <option value="3">3:00 A.M.</option>
  <option value="4">4:00 A.M.</option>
  <option value="5">5:00 A.M.</option>
  <option value="6">6:00 A.M.</option>
  <option value="7">7:00 A.M.</option>

</select>
</div>
  </div>
  <hr>
    <th><h5><strong>Cuidados de enfermería</strong></h5></th>
    <textarea rows="3" class="form-control" name="cuidenf" id="cuidenf"></textarea>
    <p>


<th><h5><strong>Nota de enfermería</strong></h5></th>
<div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="ferg"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="stoprial"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playnoc"><i class="fas fa-play"></button></i>
</div>
<textarea rows="3" class="form-control" name="notaenf" id="txtuee"></textarea>
<script type="text/javascript">
const ferg = document.getElementById('ferg');
const stoprial = document.getElementById('stoprial');
const txtuee = document.getElementById('txtuee');

const btnPlaynotno = document.getElementById('playnoc');
btnPlaynotno.addEventListener('click', () => {
        leerTexto(txtuee.value);
});

function leerTexto(txtuee){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtuee;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let notar = new webkitSpeechRecognition();
      notar.lang = "es-ES";
      notar.continuous = true;
      notar.interimResults = false;

      notar.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtuee.value += frase;
      }

      ferg.addEventListener('click', () => {
        notar.start();
      });

      stoprial.addEventListener('click', () => {
        notar.abort();
      });
</script>
<br>
  <div class="form-group col-12">
<center><button type="submit" name="btnnotaenf" class="btn btn-primary">Firmar</button>
<button type="button" class="btn btn-danger" onclick="history.back()">Cancelar</button></center>
</div>
</form>
<?php

include "../../conexionbd.php";
$id_atencion=$_SESSION['pac'];
$resultadon = $conexion->query("SELECT * from nota_enf_obs WHERE id_atencion=$id_atencion ORDER BY id_enf_obs DESC") or die($conexion->error);
$usuario = $_SESSION['login'];
?>
<table class="table table-bordered table-striped" id="mytable3">
       <thead class="thead">
         <tr class="table-primary">
            <th colspan="8"><center><h5><strong>Notas de enfermería</strong></h5></center></th>
         </tr>

    <tr class="table-success">
      <th scope="col"><center>Fecha de registro</center></th>
      <th scope="col"><center>Fecha reporte</center></th>
      <th scope="col"><center>Hora</center></th>
      <th scope="col"><center>Nota</center></th>
      <th scope="col"><center>Cuidados</center></th>
      <th scope="col"><center>Editar</center></th>
      <th scope="col"><center>Eliminar</center></th>
    </tr>
  </thead>
  <tbody>

      <?php
                while($f = mysqli_fetch_array($resultadon)){
                    $registro = $f['id_usua'];
                    ?>
    <tr>
      <td><center><strong> <?php $fech=date_create($f['fecha_registro']); echo date_format($fech,"d-m-Y H:i a");?></center></strong></td>
<td><center><strong> <?php $fechr=date_create($f['fecha']); echo date_format($fechr,"d-m-Y");?></center></strong></td>
      <td><center><strong> <?php echo $f['hora'];?> </strong></center></td>
      <td><center><strong>
        <?php echo $f['notaenf'];?></strong></center>
       </td>
    <td><center><strong>
        <?php echo $f['cuidenf'];?></strong></center>
       </td>

 <td><center><a href="editar_notaenf.php?id=<?php echo $f['id_enf_obs'];?>" title="Editar datos" class="btn btn-warning btn-sm "><span class="fa fa-edit" aria-hidden="true"></span></a></td></center>

 <td><center><a href="el_notanef.php?id=<?php echo $f['id_enf_obs'];?>" title="Eliminar dato" class="btn btn-danger btn-sm "><span class="fa fa-trash" aria-hidden="true"></span></a></td>

    </tr>


       <?php
                }
                ?>
  </tbody>
</table>
<?php

          if (isset($_POST['btnnotaenf'])) {
            include "../../conexionbd.php";
            $usuario = $_SESSION['login'];
            $id_usua= $usuario['id_usua'];
            $id_atencion = $_SESSION['pac'];

$hora =  mysqli_real_escape_string($conexion, (strip_tags($_POST["hora"], ENT_QUOTES)));
$notaenf =  mysqli_real_escape_string($conexion, (strip_tags($_POST["notaenf"], ENT_QUOTES)));
$fecha =  mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha"], ENT_QUOTES)));
$cuidenf =  mysqli_real_escape_string($conexion, (strip_tags($_POST["cuidenf"], ENT_QUOTES)));

$hora_med = strval($hora);
if($hora_med=='8' || $hora_med=='9' || $hora_med=='10' ||$hora_med=='11' ||$hora_med=='12' ||$hora_med=='13'){
        $turno="MATUTINO";
    } else if ($hora_med=='14' || $hora_med=='15' || $hora_med=='16' ||$hora_med=='17' ||$hora_med=='18' ||$hora_med=='19'||$hora_med=='20'){
        $turno="VESPERTINO";
    }else if ($hora_med=='21' || $hora_med=='22' || $hora_med=='23' ||$hora_med=='24' ||$hora_med=='1' ||$hora_med=='2'||$hora_med=='3'||$hora_med=='4'||$hora_med=='5'||$hora_med=='6'||$hora_med=='7'){
        $turno="NOCTURNO";
    }

$fecha_actual = date("Y-m-d H:i:s");
$ingresarnot = mysqli_query($conexion, 'INSERT INTO nota_enf_obs (
  id_atencion,fecha,hora,turno,notaenf,cuidenf,id_usua,fecha_registro) values (' . $id_atencion . ' , "' . $fecha . '" , "' . $hora . '" , "' . $turno . '" , "' . $notaenf . '" , "' . $cuidenf . '",' . $id_usua . ', "' . $fecha_actual . '") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));


           echo '<script type="text/javascript">window.location.href = "nota_bebes.php";</script>';
          }
          ?>
<br>
    </div>
    
    <!--termino nota enf-->
     <div class="tab-pane fade show" id="nav-med" role="tabpanel" aria-labelledby="nav-med-tab">
         <form action="" method="POST" id="medicamentos">
      <div class="row">
        <table class="table table-bordered table-striped" id="mytable">
       <thead class="thead">
         <tr class="table-primary">
            <th colspan="7"><center><h5><strong>REGISTRAR MEDICAMENTOS ADMINISTRADOS AL NEONATO</strong></h5></center></th>
         </tr>
    <tr class="table-active">
      <!--<th scope="col-4"><center>Tipo</center></th>-->
      <th scope="col-4"><center>Hora</center></th>
      <th scope="col-1"><center>Medicamento</center></th>
      <th scope="col"><center>Dósis</center></th>
      <th scope="col"><center>Unidad de medida</center></th>
      <th scope="col"><center>Vía</center></th>
      <th scope="col"><center>Otros</center></th>
    
      <th scope="col"><center></center></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
        <input type="time" class="form-control" name="hora_med" required>
      
      </td>
      <td><select data-live-search="true" class="selectpicker form-control" name="med" id="mibuscadormed" style="width : 100%; heigth : 100%" required="">
         <?php
         $sql = "SELECT * FROM item, stock where item.controlado = 'NO' AND item.item_id = stock.item_id and stock.stock_qty != 0 ORDER BY item.item_name ASC";
              $result = $conexion->query($sql);
                echo"<option value=''>Seleccionar medicamento</option>";
               while ($row_datos = $result->fetch_assoc()) {
              
                 echo "<option value='" . $row_datos['item_name'] . "'>" . $row_datos['item_name'] . "</option>";
                }
          ?></select></td>
      <td><input type="text" name="dosis" class="form-control"></td>

 <td>

<select name="unimed" class="form-control">
  <option value="">Seleccionar unidad de medida</option>
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

</td>

      <td>
<select name="via" class="form-control">
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
      </td>
      <td>
<textarea name="otro" class="form-control" rows="1">
</textarea> </td>
      
      <td><input type="submit" name="btnagregarmn" class="btn btn-block btn-success" value="Agregar"></td>
    </tr>
  </tbody>
</table>
<?php $fecha_actual = date("Y-m-d"); ?>
<div class="container">
  <div class="row">
    <div class="col-sm-2">
      <strong>Fecha de reporte:</strong>
    </div>
    <div class="col-sm-3">
      <input type="date" name="fecha_mat" value="<?php echo $fecha_actual ?>" class="form-control">
    </div>
    
  </div>
</div>
     </div>
    </form>
    <?php
          if (isset($_POST['btnagregarmn'])) {
            include "../../conexionbd.php";
            $usuario = $_SESSION['login'];
            $id_usua= $usuario['id_usua'];
            $id_atencion = $_SESSION['pac'];

           $fecha_mat =  mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha_mat"], ENT_QUOTES)));
            $hora_med =  mysqli_real_escape_string($conexion, (strip_tags($_POST["hora_med"], ENT_QUOTES)));
            $med =  mysqli_real_escape_string($conexion, (strip_tags($_POST["med"], ENT_QUOTES)));
            $dosis =  mysqli_real_escape_string($conexion, (strip_tags($_POST["dosis"], ENT_QUOTES)));
            $via =  mysqli_real_escape_string($conexion, (strip_tags($_POST["via"], ENT_QUOTES)));
            $otro =  mysqli_real_escape_string($conexion, (strip_tags($_POST["otro"], ENT_QUOTES)));
           $unimed =  mysqli_real_escape_string($conexion, (strip_tags($_POST["unimed"], ENT_QUOTES)));

if($hora_med=='08:00' || $hora_med=='09:00' || $hora_med=='10:00'|| $hora_med=='11:00'|| $hora_med=='12:00' || $hora_med=='13:00' ){
$turno="MATUTINO";
} else if ($hora_med=='14:00' || $hora_med=='15:00' || $hora_med=='16:00'|| $hora_med=='17:00'|| $hora_med=='18:00' || $hora_med=='19:00' || $hora_med=='20:00') {
  $turno="VESPERTINO";
}else if ($hora_med=='21:00' || $hora_med=='22:00' || $hora_med=='23:00'|| $hora_med=='00:00'|| $hora_med=='01:00' || $hora_med=='02:00' || $hora_med=='03:00' || $hora_med=='04:00' || $hora_med=='05:00' || $hora_med=='06:00' || $hora_med=='07:00') {
    $turno="NOCTURNO";
}

$fecha_actual = date("Y-m-d");

/*if ($hora_med == '24' || $hora_med == '1' || $hora_med == '2' || $hora_med == '3' || $hora_med == '4' || $hora_med == '5' || $hora_med == '6' || $hora_med == '7') {
   // Restamos un día a la fecha actual
   $fecha_actual = date('Y-m-d', strtotime('-1 day')) ; 
} else { 
   $fecha_actual = date("Y-m-d"); 
}*/

$fechahora = date("Y-m-d H:i");
       $ingresar9 = mysqli_query($conexion, 'INSERT INTO medica_enf (id_atencion,fecha_mat,hora_mat,turno,medicam_mat,dosis_mat,unimed,via_mat,id_usua,enf_fecha,tipo,otro,neonato) values (' . $id_atencion . ' , "' . $fecha_mat . '","' . $hora_med . '","' . $turno . '","' . $med . '","' . $dosis . '","' . $unimed . '","' . $via . '",' . $id_usua .',"'.$fechahora.'","'.$area.'","'.$otro.'","Si") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

           echo '<script type="text/javascript">window.location.href = "nota_bebes.php";</script>';
          }
          ?>
    
    <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:20%" id="search" placeholder="BUSCAR...">
            </div>
    
    <div class="table-responsive">

            <table class="table table-bordered table-striped" id="mytable">
                <thead class="thead bg-navy">
              
                    <th scope="col">Pdf</th>
                    <th scope="col">Medicamento</th>
                    <th scope="col">Dósis</th>
                    <th scope="col">Unidad de medida</th>
                    <th scope="col">Vía</th>
                     <th scope="col">Fecha de registro</th>
                    <th scope="col">Fecha de reporte</th>
                    <th scope="col">Hora</th> 
                    <th scope="col">Tipo</th>
                    <th scope="col">Otro</th>
                    <th scope="col">Registró</th>
                    <th scope="col">Editar</th>
                    <th scope="col">Eliminar</th>
                </tr>
                </thead>
                <tbody>

<?php
include "../../conexionbd.php";
$id_atencion=$_SESSION['pac'];
$resultado = $conexion->query("SELECT * from medica_enf m , reg_usuarios r WHERE m.id_atencion=$id_atencion and m.id_usua=r.id_usua and m.neonato='Si' ORDER BY id_med_reg DESC") or die($conexion->error);
$usuario = $_SESSION['login'];
while($f = mysqli_fetch_array($resultado)){   

    $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE P.Id_exp=DI.Id_exp and DI.id_atencion=$id_atencion") or die($conexion->error);

            
              while ($row = $resultado2->fetch_assoc()) {             
    ?>
                    <tr>
                        <td class="fondo"><a href="../pdf/pdf_medicamentos.php?id_ord=<?php echo $f['id_med_reg'];?>&id_atencion=<?php echo $f['id_atencion'];?>&id_usua=<?php echo $usuario['id_usua']?>&fecha=<?php echo $f['fecha_mat'];?>&id_exp=<?php echo $row['Id_exp'];?>" target="_blank"><button type="button" class="btn btn-danger"> <i class="fa fa-file-pdf-o" style="font-size:28px"  aria-hidden="true"></i> </button></a></td>
                        <td class="fondo"><strong><?php echo $f['medicam_mat'];?></strong></td>
                         <td class="fondo"><strong><?php echo $f['dosis_mat'];?></strong></td>
                          <td class="fondo"><strong><?php echo $f['unimed'];?></strong></td>
                         <td class="fondo"><strong><?php echo $f['via_mat'];?></strong></td>
                         <td class="fondo"><strong><?php $dater=date_create($f['enf_fecha']); echo date_format($dater,"d/m/Y H:i a");?></strong></td>
                        <td class="fondo"><strong><?php $date=date_create($f['fecha_mat']); echo date_format($date,"d/m/Y");?></strong></td>
                        <td class="fondo"><strong><?php echo $f['hora_mat'];?></strong></td>
                    
                        <td class="fondo"><strong><?php echo $f['tipo'];?></strong></td>
                        <td class="fondo"><strong><?php echo $f['otro'];?></strong></td>
                        <td class="fondo"><strong><?php echo $f['papell'].' '.$f['sapell']?></strong></td>

<td><a href="edit_med.php?id_med_reg=<?php echo $f['id_med_reg'];?>&id_atencion=<?php echo $f['id_atencion'];?>"><button type="button" class="btn btn-warning"><i class="fas fa-edit"></i></button></td>

<td><a href="el_med.php?id_med_reg=<?php echo $f['id_med_reg'];?>&id_atencion=<?php echo $f['id_atencion'];?>"><button type="button" class="btn btn-danger">X </button></td>

                    </tr>
                    <?php
                }
                 }
                ?>
                
                </tbody>
              
            </table>
            </div>
    
         </div>
    
    
    
  <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
      <form action="" method="POST">
 <div class="container-fluid">
<div class="container">
  
      <div class="row">
        <table class="table table-bordered table-striped" id="mytable">
       <thead class="thead">
         <tr class="table-primary">
            <th colspan="9"><center><h5><strong>REGISTRAR SIGNOS VITALES</strong></h5></center></th>
                
         </tr>
    <tr class="table-active">
      <!--<th scope="col" class="col-sm-1"><center>Tipo</center></th>-->
      <th scope="col" class="col-sm-1"><center>Hora</center></th>
      <th scope="col" class="col-sm-2"><center>Presón arterial</center></th>
      <th scope="col" class="col-sm-1"><center>Frecuencia cardiaca</center></th>
      <th scope="col" class="col-sm-1"><center>Frecuencia respiratoria</center></th>
     <th scope="col" class="col-sm-1"><center>Temperatura</center></th>
     <th scope="col" class="col-sm-1"><center>Saturación oxígeno</center></th>
        <th scope="col" class="col-sm-1"><center><img src="../../imagenes/caras.png" width="250"> Nivel de dolor</center></th>
     
    </tr>
  </thead>
  <tbody>
    <tr>


      <td>
        <select class="form-control" name="hora_med" style="width : 100%; heigth : 100%" required="">
        <option value="">Seleccionar hora</option>
         <option value="8">8:00 A.M.</option>
         <option value="9">9:00 A.M.</option>
  <option value="10">10:00 A.M.</option>
  <option value="11">11:00 A.M.</option>
  <option value="12">12:00 A.M.</option>
  <option value="13">13:00 P.M.</option>
  <option value="14">14:00 P.M.</option>
  <option value="15">15:00 P.M.</option>
  <option value="16">16:00 P.M.</option>
  <option value="17">17:00 P.M.</option>
  <option value="18">18:00 P.M.</option>
  <option value="19">19:00 P.M.</option>
  <option value="20">20:00 P.M.</option>
  <option value="21">21:00 P.M.</option>
  <option value="22">22:00 P.M.</option>
  <option value="23">23:00 P.M.</option>
  <option value="24">24:00 A.M.</option>
  <option value="1">1:00 A.M.</option>
  <option value="2">2:00 A.M.</option>
  <option value="3">3:00 A.M.</option>
  <option value="4">4:00 A.M.</option>
  <option value="5">5:00 A.M.</option>
  <option value="6">6:00 A.M.</option>
  <option value="7">7:00 A.M.</option>
 

        </select>
      </td>
      <td>
     <div class="row">
  <div class="col losInputTAM"><input type="number" class="form-control" id="sist" name="sist_mat" =""></div> /
  <div class="col losInputTAM"><input type="number" class="form-control" id="diast" name="diast_mat" =""></div>
 
</div></td>
      <td><input type="number" class="form-control" name="freccard_mat">
    </div></td>
      <td><input type="number" class="form-control" name="frecresp_mat">
    </div></td>
<td><input type="cm-number" class="form-control" name="temper_mat" onkeypress='return event.charCode != 44'>
    </div></td>
<td><input type="number"  class="form-control col-sm-12" name="satoxi_mat" min="1" pattern="^[0-9]+" onkeypress='return event.charCode != 45'>
    </div></td>
    <td>
        <select class="form-control col-sm-12" name="niv_dolor" ="">
            <option value="">Seleccionar nivel de dolor</option>
             <option value="10">10</option>
             <option value="9">9</option>
             <option value="8">8</option>
             <option value="7">7</option>
             <option value="6">6</option>
             <option value="5">5</option>
             <option value="4">4</option>
             <option value="3">3</option>
             <option value="2">2</option>
             <option value="1" >1</option>
         
              <option value="0">0</option>
             
        </select>
   
        
    </div></td>
     
    </tr>
  </tbody>
</table>

<div class="container">
  <div class="row">
    <div class="col-sm-2">
     <strong>Fecha de reporte:</strong>
    </div>
    <div class="col-sm-3">
      <input type="date" value="<?php echo $fecha_actual = date("Y-m-d") ?>" name="fecha" class="form-control" required>
    </div>
   
  </div>
</div>



     </div><p></p>
     <center>
     <input type="submit" name="btnagregar" class="btn btn-block btn-success col-3" value="Agregar">
    </center>
    </form>
    
    <?php

          if (isset($_POST['btnagregar'])) {
            include "../../conexionbd.php";
            $usuario = $_SESSION['login'];
            $id_usua= $usuario['id_usua'];
            $id_atencion = $_SESSION['pac'];

$fecha =  mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha"], ENT_QUOTES)));
$hora_med =  mysqli_real_escape_string($conexion, (strip_tags($_POST["hora_med"], ENT_QUOTES)));
$sist_mat =  mysqli_real_escape_string($conexion, (strip_tags($_POST["sist_mat"], ENT_QUOTES)));
//$diast_mat =  mysqli_real_escape_string($conexion, (strip_tags($_POST["diast_mat"], ENT_QUOTES)));
$freccard_mat =  mysqli_real_escape_string($conexion, (strip_tags($_POST["freccard_mat"], ENT_QUOTES)));
$frecresp_mat =  mysqli_real_escape_string($conexion, (strip_tags($_POST["frecresp_mat"], ENT_QUOTES)));
$temper_mat =  mysqli_real_escape_string($conexion, (strip_tags($_POST["temper_mat"], ENT_QUOTES)));
$satoxi_mat =  mysqli_real_escape_string($conexion, (strip_tags($_POST["satoxi_mat"], ENT_QUOTES)));
$niv_dolor =  mysqli_real_escape_string($conexion, (strip_tags($_POST["niv_dolor"], ENT_QUOTES)));

if (isset($_POST['diast_mat'])){$diast_mat=$_POST['diast_mat'];}else{$diast_mat='';}


if($hora_med=='8' ||$hora_med=='9' || $hora_med=='10' || $hora_med=='11'|| $hora_med=='12'|| $hora_med=='13' || $hora_med=='14'){
$turno="MATUTINO";
} else if ($hora_med=='15' || $hora_med=='16' || $hora_med=='17'|| $hora_med=='18'|| $hora_med=='19' || $hora_med=='20' || $hora_med=='21') {
  $turno="VESPERTINO";
}else if ($hora_med=='22' || $hora_med=='23' || $hora_med=='24'|| $hora_med=='1'|| $hora_med=='2' || $hora_med=='3' || $hora_med=='4' || $hora_med=='5' || $hora_med=='6' || $hora_med=='7') {
    $turno="NOCTURNO";
}

$fecha_actual = date("Y-m-d H:i a");

if ($hora_med == '1' || $hora_med == '2' || $hora_med == '3' || $hora_med == '4' || $hora_med == '5' || $hora_med == '6' || $hora_med == '7') {
   // Restamos un día a la fecha actual
   $yesterday = date('Y-m-d', strtotime('-1 day')) ; 
} else { 
   $yesterday = date("Y-m-d"); 
}


$ingresarsignos = mysqli_query($conexion, 'INSERT INTO signos_vitales (
  id_atencion,id_usua,fecha,p_sistol,p_diastol,fcard,fresp,temper,satoxi,niv_dolor,hora,tipo,fecha_registro,neonato) values (' . $id_atencion . ' , ' . $id_usua . ' ,"' . $fecha. '", "' . $sist_mat . '" , "' . $diast_mat . '" , "' . $freccard_mat . '" , "' . $frecresp_mat . '" , "' . $temper_mat . '", "' . $satoxi_mat . '","' . $niv_dolor . '",' . $hora_med . ',"' . $area . '","' . $fecha_actual . '","Si") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));


           echo '<script type="text/javascript">window.location.href = "nota_bebes.php";</script>';
          }
          ?> 
          
    </div>
    
    </div>
     <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:20%" id="search" placeholder="BUSCAR...">
            </div>
     <div class="table-responsive">

            <table class="table table-bordered table-striped" id="mytable">
                <thead class="thead bg-navy">
              
                    <th scope="col">Pdf</th>
                     <th scope="col">Fecha de registro</th>
                    <th scope="col">Fecha de reporte</th>
                    
                    <th scope="col">Tipo</th>
                    
                </tr>
                </thead>
                <tbody>

<?php
include "../../conexionbd.php";
$usuario = $_SESSION['login'];
$usua=$usuario['id_usua'];
$id_atencion=$_SESSION['pac'];
$resultado = $conexion->query("SELECT fecha,id_atencion,id_usua,hora,id_sig,p_sistol,p_diastol,fcard,fresp,temper,satoxi,niv_dolor, tipo,fecha_registro from signos_vitales s WHERE s.id_atencion=$id_atencion AND s.neonato='Si' group by fecha ORDER BY id_sig DESC") or die($conexion->error);
while($f = mysqli_fetch_array($resultado)){
       $id_usua=$f['id_usua'];
      $resultado_usua = $conexion->query("SELECT * FROM reg_usuarios WHERE id_usua=$id_usua") or die($conexion->error);
        while($row = mysqli_fetch_array($resultado_usua)){

$sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.tel, p.fecnac,p.tip_san, di.fecha, di.area, di.alta_med, p.sexo, di.alergias,p.folio FROM paciente p, dat_ingreso di WHERE p.Id_exp=di.Id_exp and di.id_atencion =$id_atencion";

      $result_pac = $conexion->query($sql_pac);

      while ($row_pac = $result_pac->fetch_assoc()) {
        
      


?>          
                    <tr>
<td class="fondo"><a href="../signos_vitales/signos_vitales_neonato.php?id_ord=<?php echo $f['id_sig'];?>&id_atencion=<?php echo $f['id_atencion'];?>&id_usua=<?php echo $usua?>&fecha=<?php echo $f['fecha']?>&idexp=<?php echo $row_pac['Id_exp']?>"><button type="button" class="btn btn-danger"> <i class="fa fa-file-pdf-o" style="font-size:28px"  aria-hidden="true"></i> </button></td>

         <td class="fondo"><strong><?php $daterr=date_create($f['fecha_registro']); echo date_format($daterr,"d-m-Y H:i a");?></strong></td>             
                       
<td class="fondo"><strong><?php $date=date_create($f['fecha']); echo date_format($date,"d-m-Y");?></strong></td>

<td class="fondo"><strong><?php echo $f['tipo'];?></strong></td>
<!--<td class="fondo"><strong><?php echo $row['nombre'].' '.$row['papell'].' '.$row['sapell']?></strong></td>-->
                    </tr>
                    <?php
}
    }
        }
                ?>
                
                </tbody>
              
            </table>
            </div>
  </div>
  
  <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
  <form action="" method="POST">
 <div class="container">
    <div class="row">
           <table class="table table-bordered table-striped" id="mytable">
       <thead class="thead">
         <tr class="table-primary">
            <th colspan="5"><center><h5><strong>INGRESOS</strong></h5></center></th>
         </tr>
    <tr class="table-active">
      <th scope="col"><center>Hora</center></th>
      <th scope="col"><center>Descripción</center></th>
      <th scope="col"><center>Cantidad</center></th>
      <th scope="col"><center></center></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td> <select class="form-control" name="hora_med" style="width : 100%; heigth : 100%" required="">
        <option value="">Seleccionar hora</option>
        <option value="8">8:00 A.M.</option>
         <option value="9">9:00 A.M.</option>
  <option value="10">10:00 A.M.</option>
  <option value="11">11:00 A.M.</option>
  <option value="12">12:00 A.M.</option>
  <option value="13">13:00 P.M.</option>
  <option value="14">14:00 P.M.</option>
  <option value="15">15:00 P.M.</option>
  <option value="16">16:00 P.M.</option>
  <option value="17">17:00 P.M.</option>
  <option value="18">18:00 P.M.</option>
  <option value="19">19:00 P.M.</option>
  <option value="20">20:00 P.M.</option>
  <option value="21">21:00 P.M.</option>
  <option value="22">22:00 P.M.</option>
  <option value="23">23:00 P.M.</option>
  <option value="24">24:00 A.M.</option>
  <option value="1">1:00 A.M.</option>
  <option value="2">2:00 A.M.</option>
  <option value="3">3:00 A.M.</option>
  <option value="4">4:00 A.M.</option>
  <option value="5">5:00 A.M.</option>
  <option value="6">6:00 A.M.</option>
  <option value="7">7:00 A.M.</option>
  

        </select></td>
      <td><select class="form-control" name="desc" style="width : 100%; heigth : 100%" required="">
        <option value="">Seleccionar descripción</option>
        <option value="Solución parental">Solución parental</option>
        <option value="Nutrición parenteral">Nutrición parenteral</option>
        <option value="Nutrición enteral">Nutrición enteral</option>
         <option value="Medicamentos">Medicamentos I.V</option>
  <option value="Vía oral">Vía oral</option>
  <option value="Otros">Otros</option>
  <option value="Leche materna formula">Leche materna/formula</option>
        </select></td>
      <td><input type="cm-number" name="cantidad" class="form-control"></td>
      
      <td><input type="submit" name="btning" class="btn btn-block btn-success" value="Agregar"></td>
    </tr>
    
  </tbody>
</table>

     </div>
 </div> 
 <div class="container">
  <div class="row">
    <div class="col-sm-2">
     <strong>Fecha de reporte:</strong>
    </div>
    <div class="col-sm-3">
      <input type="date" value="<?php echo $fecha_actual = date("Y-m-d") ?>" name="fecha" class="form-control" required>
    </div>
  </div>
</div>
</form><hr>  
<?php
if(isset($_POST['btning'])){
     include "../../conexionbd.php";
            $usuario = $_SESSION['login'];
            $id_usua= $usuario['id_usua'];
            $id_atencion = $_SESSION['pac'];
            $hora_med =  mysqli_real_escape_string($conexion, (strip_tags($_POST["hora_med"], ENT_QUOTES)));
            $desc =  mysqli_real_escape_string($conexion, (strip_tags($_POST["desc"], ENT_QUOTES)));
            $cantidad =  mysqli_real_escape_string($conexion, (strip_tags($_POST["cantidad"], ENT_QUOTES)));
            $fecha =  mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha"], ENT_QUOTES)));
            
if ($hora_med == '1' || $hora_med == '2' || $hora_med == '3' || $hora_med == '4' || $hora_med == '5' || $hora_med == '6' || $hora_med == '7') {
   // Restamos un día a la fecha actual
   $yesterday = date('Y-m-d H:i', strtotime('-1 day')) ; 
} else { 
   $yesterday = date("Y-m-d H:i"); 
}
            

$fe = date("Y-m-d");
   $ingresar2 = mysqli_query($conexion, 'INSERT INTO ing_enf_quir (id_atencion,id_usua,hora,des,cantidad,fecha,fecha_registro,neonato) values ('.$id_atencion.',' . $id_usua . ',"' . $hora_med .'","' . $desc . '","'.$cantidad.'","'.$fecha.'","'.$yesterday.'","Si") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

      echo '<script type="text/javascript">window.location.href = "nota_bebes.php";</script>';
    }
    ?>
    
    <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:19%" id="search" placeholder="Buscar...">
            </div>
            
             <?php

include "../../conexionbd.php";
$id_atencion=$_SESSION['pac'];
$resultado = $conexion->query("SELECT * from ing_enf_quir WHERE id_atencion=$id_atencion and neonato='Si' ORDER BY id_ing DESC") or die($conexion->error);
$usuario = $_SESSION['login'];
?>

<table class="table table-bordered table-striped" id="mytable3">
       <thead class="thead">
         <tr class="table-primary">
            <th colspan="8"><center><h5><strong>INGRESOS</strong></h5></center></th>
         </tr>

    <tr class="table-success">
      <th scope="col"><center>Fecha de registro</center></th>
      <th scope="col"><center>Fecha reporte</center></th>
      <th scope="col"><center>Hora</center></th>
      <th scope="col"><center>Descripción</center></th>
      <th scope="col"><center>Cantidad Ml</center></th>
      <th scope="col"><center>Registró</center></th>
      <th scope="col"><center>Editar</center></th>
      <th scope="col"><center>Eliminar</center></th>

    </tr>


  </thead>
  <tbody>

      <?php
                while($f = mysqli_fetch_array($resultado)){
                    $registro = $f['id_usua'];
                    
                    ?>
    <tr>
      <td><center><strong> <?php $fech=date_create($f['fecha_registro']); echo date_format($fech,"d-m-Y H:i a");?></center></strong></td>
<td><center><strong> <?php $fechr=date_create($f['fecha']); echo date_format($fechr,"d-m-Y");?></center></strong></td>
      <td><center><strong> <?php echo $f['hora'];?> </strong></center></td>
      <td><center><strong>
        <?php echo $f['des'];?></strong></center>
       </td>
      <td><center><strong><?php echo $f['cantidad'];?></strong></center></td>
      <?php $resultado_usua = $conexion->query("SELECT * FROM reg_usuarios WHERE id_usua=$registro") or die($conexion->error);
        while($row = mysqli_fetch_array($resultado_usua)) {$registro = $row[papell]; }?>
        
        <td><center><strong><?php echo $registro;?></strong></center></td>

 <td><center><a href="editar_ingresos.php?id=<?php echo $f['id_ing'];?>" title="Editar datos" class="btn btn-warning btn-sm "><span class="fa fa-edit" aria-hidden="true"></span></a></td></center>

 <td><center><a href="el_ingresos.php?id=<?php echo $f['id_ing'];?>" title="Eliminar dato" class="btn btn-danger btn-sm "><span class="fa fa-trash" aria-hidden="true"></span></a></td>

    </tr>


       <?php
                }
                ?>
  </tbody>
</table>
    
  </div>
  
  
  
  
  <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
      
      <form action="" method="POST">
 <div class="container">
    <div class="row">
           <table class="table table-bordered table-striped" id="mytable">
       <thead class="thead">
         <tr class="table-primary">
            <th colspan="5"><center><h5><strong>EGRESOS</strong></h5></center></th>
         </tr>
    <tr class="table-active">
      <th scope="col"><center>Hora</center></th>
      <th scope="col"><center>Descripción</center></th>
      <th scope="col"><center>Cantidad</center></th>
      <th scope="col"><center>Características</center></th>
      <th scope="col"><center></center></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td> <select class="form-control" name="hora_eg" style="width : 100%; heigth : 100%" required="">
        <option value="">Seleccionar hora</option>
        <option value="8">8:00 A.M.</option>
         <option value="9">9:00 A.M.</option>
  <option value="10">10:00 A.M.</option>
  <option value="11">11:00 A.M.</option>
  <option value="12">12:00 A.M.</option>
  <option value="13">13:00 P.M.</option>
  <option value="14">14:00 P.M.</option>
  <option value="15">15:00 P.M.</option>
  <option value="16">16:00 P.M.</option>
  <option value="17">17:00 P.M.</option>
  <option value="18">18:00 P.M.</option>
  <option value="19">19:00 P.M.</option>
  <option value="20">20:00 P.M.</option>
  <option value="21">21:00 P.M.</option>
  <option value="22">22:00 P.M.</option>
  <option value="23">23:00 P.M.</option>
  <option value="24">24:00 A.M.</option>
  <option value="1">1:00 A.M.</option>
  <option value="2">2:00 A.M.</option>
  <option value="3">3:00 A.M.</option>
  <option value="4">4:00 A.M.</option>
  <option value="5">5:00 A.M.</option>
  <option value="6">6:00 A.M.</option>
  <option value="7">7:00 A.M.</option>
  

        </select></td>
      <td><select class="form-control" name="des_eg" style="width : 100%; heigth : 100%" required="">
        <option value="">Seleccionar descripción</option>
         <option value="Diuresis">Diuresis</option>
  <option value="Vomito">Vomito</option>
  <option value="Canalizaciones">Canalizaciones</option>
  <option value="Evacuaciones">Evacuaciones</option>
  <option value="Perdidas insensibles">Perdidas insensibles</option>
 
  <!--<option value="OTROS">OTROS</option>-->


        </select></td>
      <td><input type="cm-number" name="cant_eg" class="form-control"></td>
       <td><textarea name="carac" class="form-control" rows="1"></textarea></td>
      <td><input type="submit" name="btneg" class="btn btn-block btn-success" value="Agregar"></td>
    </tr>
  </tbody>
</table>
     </div>
 </div> 
 <div class="container">
  <div class="row">
    <div class="col-sm-2">
     <strong>Fecha de reporte:</strong>
    </div>
    <div class="col-sm-3">
      <input type="date" value="<?php echo $fecha_actual = date("Y-m-d") ?>" name="fecha" class="form-control" required>
    </div>
  </div>
</div>
</form><hr>
      
<?php
if(isset($_POST['btneg'])){
     include "../../conexionbd.php";
            $usuario = $_SESSION['login'];
            $id_usua= $usuario['id_usua'];
            $id_atencion = $_SESSION['pac'];
            $hora_eg =  mysqli_real_escape_string($conexion, (strip_tags($_POST["hora_eg"], ENT_QUOTES)));
            $des_eg =  mysqli_real_escape_string($conexion, (strip_tags($_POST["des_eg"], ENT_QUOTES)));
            $cant_eg =  mysqli_real_escape_string($conexion, (strip_tags($_POST["cant_eg"], ENT_QUOTES)));
            $carac =  mysqli_real_escape_string($conexion, (strip_tags($_POST["carac"], ENT_QUOTES)));
            $fecha =  mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha"], ENT_QUOTES)));

$feceg = date("Y-m-d");

if ($hora_eg == '1' || $hora_eg == '2' || $hora_eg == '3' || $hora_eg == '4' || $hora_eg == '5' || $hora_eg == '6' || $hora_eg == '7') {
   // Restamos un día a la fecha actual
   $yesterday = date('Y-m-d H:i', strtotime('-1 day')) ; 
} else { 
   $yesterday = date("Y-m-d H:i"); 
}
   $ingresar2 = mysqli_query($conexion, 'INSERT INTO eg_enf_quir (id_atencion,id_usua,hora_eg,des_eg,cant_eg,carac,fecha_eg,fecha_registro,neonato) values ('.$id_atencion.',' . $id_usua . ',"' . $hora_eg .'","' . $des_eg . '","'.$cant_eg.'","'.$carac.'","'.$fecha.'","'.$yesterday.'","Si") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

      echo '<script type="text/javascript">window.location.href = "nota_bebes.php";</script>';
    }
    ?>
    <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:19%" id="searche" placeholder="Buscar...">
            </div>      
      
      <?php

include "../../conexionbd.php";
$id_atencion=$_SESSION['pac'];
$resultado = $conexion->query("SELECT * from eg_enf_quir WHERE id_atencion=$id_atencion and neonato='Si' ORDER BY id_eg DESC") or die($conexion->error);
$usuario = $_SESSION['login'];
?>

<table class="table table-bordered table-striped" id="mytable4">
       <thead class="thead">
         <tr class="table-primary">
            <th colspan="9"><center><h5><strong>EGRESOS</strong></h5></center></th>
         </tr>
    <tr class="table-success">
      <th scope="col"><center>Fecha de registro</center></th>
       <th scope="col"><center>Fecha reporte</center></th>
      <th scope="col"><center>Hora</center></th>
      <th scope="col"><center>Descripción</center></th>
      <th scope="col"><center>Cantidad Ml</center></th>
      <th scope="col"><center>Características</center></th>
      <th scope="col"><center>Registró</center></th>
      <th scope="col"><center>Editar</center></th>
      <th scope="col"><center>Eliminar</center></th>
    </tr>
  </thead>
  <tbody>
      <?
                while($f = mysqli_fetch_array($resultado)){
                    $registro=$f['id_usua'];
                    
                    ?>
    <tr>
      <td><center><strong> <?php $fech_e=date_create($f['fecha_registro']); echo date_format($fech_e,"d-m-Y H:i");?></center></strong></td>
<td><center><strong> <?php $fech_er=date_create($f['fecha_eg']); echo date_format($fech_er,"d-m-Y");?></center></strong></td>
      <td><center><strong> <?php echo $f['hora_eg'];?> </strong></center></td>
      <td><center><strong>
        <?php echo $f['des_eg'];?></strong></center>
       </td>
      <td><center><strong><?php echo $f['cant_eg'];?></strong></center></td>
  <td><center><strong><?php echo $f['carac'];?></strong></center></td>
  
   <?php $resultado_usua = $conexion->query("SELECT * FROM reg_usuarios WHERE id_usua=$registro") or die($conexion->error);
        while($row = mysqli_fetch_array($resultado_usua)) {$registro = $row[papell]; }?>
        
        <td><center><strong><?php echo $registro;?></strong></center></td>
        
      <td><center><a href="editar_egresos.php?id=<?php echo $f['id_eg'];?>" title="Editar datos" class="btn btn-warning btn-sm "><span class="fa fa-edit" aria-hidden="true"></span></a></td></center>

      <td><center><a href="el_egresos.php?id=<?php echo $f['id_eg'];?>" title="Eliminar dato" class="btn btn-danger btn-sm "><span class="fa fa-trash" aria-hidden="true"></span></a></td>
    </tr>
       <?php
                }
                 
                ?>
  </tbody>
</table>
      
  </div>
  
  
  
  
  <div class="tab-pane fade" id="nav-nota" role="tabpanel" aria-labelledby="nav-nota-tab">   <form action="insertar_recien_nac.php" method="POST">
<br>
<div class="container -12">
                <div class="row col-6">
                    <div class="col-sm">
                      
                        <!--<strong>No. Admisión:</strong>-->
                        
                        <input type="hidden" name="id_atencion" class="form-control" value="<?php echo $_SESSION['hospital'] ?>"
                               readonly placeholder="No. De expediente">
                    </div>
                </div>
            </div>
        
<div class="container">
  <div class="row">
    <div class="col-sm-3">
      <strong>Fecha/dia Hosp:</strong><input type="date" class="form-control" name="fechab" required>
    </div>
    <div class="col-sm-3">
      <strong>Hora:</strong><input type="time" name="horab" class="form-control" required>
    </div>
  </div>
</div>
<p></p>

<?php 
$sql_b = "SELECT * from iden_recnac where id_atencion=$id_atencion ORDER by id_rec_nac DESC LIMIT 1";
$result_b= $conexion->query($sql_b); 
while ($row_b = $result_b->fetch_assoc()) {
    $id_rec=$row_b['id_rec_nac'];
    $id_at=$row_b['id_atencion'];
    
    $apell=$row_b['apellidos'];
    $nmadre=$row_b['nombremadre'];
    $nacfecha=$row_b['fecnac'];
    $sexbebe=$row_b['sexo'];
    
}

if(isset($id_rec) and isset($id_at) and $apell!=null  and $nmadre!=null  and $nacfecha!='0000-00-00' and $sexbebe!=null){
    
    $sql_br = "SELECT * from iden_recnac where id_atencion=$id_atencion ORDER by id_rec_nac DESC LIMIT 1";
$result_br= $conexion->query($sql_br); 
while ($row_br = $result_br->fetch_assoc()) {


    
?>

<div class="container">
 <div class="row">
  <div class="col-sm-3">
     <strong>Alojamiento conjunto:</strong>
  </div>
   <div class="col-sm-3">
  <div class="alert alert-success alert-sm" role="alert">
  Identificación realizada
</div> 
  </div>
</div>
</div>


    <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
<strong><center>Hoja identificación del recien nacido</center></strong>
</div><p></p>

<strong>APELLIDOS DEL RECIÉN NACIDO:</strong>
<div class="row">
    <div class="col-sm-6">
<input type="text" name="apellidos" class="form-control" disabled value="<?php echo $row_br['apellidos']; ?>"> 
</div>
<div >
<img src="che.png" class="img-fluid" width="35">
</div>
</div>

<p></p>
<strong>NOMBRE DE LA MADRE:</strong>
<input type="text" name="nombremadre" class="form-control col-sm-6" disabled value="<?php echo $row_br['nombremadre']; ?>">
<p></p>
    <div class="row">
        <div class="col-sm-3">
            <strong>FECHA DE NACIMIENTO:</strong>
<input type="date" name="fecnac" class="form-control" disabled value="<?php echo $row_br['fecnac']; ?>">
        </div>
        <div class="col-sm-3">
            <strong>HORA DE NACIMIENTO:</strong>
<input type="time" name="horanac" class="form-control" disabled value="<?php echo $row_br['horanac']; ?>">
        </div>
        </div>

<p></p>
    <div class="row">
        <div class="col-sm-3">
            <strong>SEXO:</strong>
            <input type="text" class="form-control" disabled value="<?php echo $row_br['sexo']; ?>">
        </div>
        <div class="col-sm-3">
            <strong>PESO: (kg)</strong>
<input type="text" name="peso" class="form-control" disabled value="<?php echo $row_br['peso']; ?>">
        </div>
        <div class="col-sm-3">
            <strong>TALLA: (cm)</strong>
<input type="text" name="talla" class="form-control" disabled value="<?php echo $row_br['talla']; ?>">
        </div>
          <div class="col-sm-3">
            <strong>PIE: (cm)</strong>
<input type="text" name="pie" class="form-control" disabled value="<?php echo $row_br['pie']; ?>">
        </div>
        </div>

<p></p>
    <div class="row">
        <div class="col-sm-3">
            <strong>APGAR:</strong>
<input type="text" name="apgar" class="form-control" disabled value="<?php echo $row_br['apgar']; ?>">
        </div>
        <div class="col-sm-3">
            <strong>SILVERMAN:</strong>
<input type="text" name="silverman" class="form-control" disabled value="<?php echo $row_br['silverman']; ?>">
        </div>
        <div class="col-sm-3">
            <strong>CAPURRO:</strong>
<input type="text" name="capurro" class="form-control" disabled value="<?php echo $row_br['capurro']; ?>">
        </div>
        </div>

    



<?php } }else{ ?>
<div class="container">
 <div class="row">
  <div class="col">
     <strong>Alojamiento conjunto:</strong>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SI &nbsp;<input type="radio" value="SI" name="pipat" class="alojamiento">&nbsp;&nbsp;&nbsp;&nbsp;
  &nbsp;&nbsp;&nbsp;&nbsp;NO &nbsp;<input type="radio" value="NO" name="pipat" checked="" class="alojamiento">&nbsp;&nbsp;&nbsp;&nbsp;
  </div>
</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
        $(".alojamiento").click(function(evento){
          
            var valor = $(this).val();
          
            if(valor == 'SI'){
                $("#div1").css("display", "block");
                $("#div2").css("display", "none");
            }else{
                $("#div1").css("display", "none");
                $("#div2").css("display", "block");
            }
    });
});

</script>


<div class="collapse" id="div1" style="display:none;">
    <div class="card card-body">
    <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
<strong><center>Hoja identificación del recien nacido</center></strong>
</div><p></p>

<strong>APELLIDOS DEL RECIÉN NACIDO:</strong>
<input type="text" name="apellidos" class="form-control col-sm-6">
<p></p>
<strong>NOMBRE DE LA MADRE:</strong>
<input type="text" name="nombremadre" class="form-control col-sm-6">
<p></p>
    <div class="row">
        <div class="col-sm-3">
            <strong>FECHA DE NACIMIENTO:</strong>
<input type="date" name="fecnac" class="form-control">
        </div>
        <div class="col-sm-3">
            <strong>HORA DE NACIMIENTO:</strong>
<input type="time" name="horanac" class="form-control">
        </div>
        </div>

<p></p>
    <div class="row">
        <div class="col-sm-3">
            <strong>SEXO:</strong>
            <select name="sexo" class="form-control">
                <option value="">Seleccionar Sexo</option>
                <option value="Hombre">Hombre</option>
                <option value="Mujer">Mujer</option>
            </select>
        </div>
        <div class="col-sm-3">
            <strong>PESO: (kg)</strong>
<input type="text" name="peso" class="form-control">
        </div>
        <div class="col-sm-3">
            <strong>TALLA: (cm)</strong>
<input type="text" name="talla" class="form-control">
        </div>
          <div class="col-sm-3">
            <strong>PIE: (cm)</strong>
<input type="text" name="pie" class="form-control">
        </div>
        </div>

<p></p>
    <div class="row">
        <div class="col-sm-3">
            <strong>APGAR:</strong>
<input type="text" name="apgar" class="form-control">
        </div>
        <div class="col-sm-3">
            <strong>SILVERMAN:</strong>
<input type="text" name="silverman" class="form-control">
        </div>
        <div class="col-sm-3">
            <strong>CAPURRO:</strong>
<input type="text" name="capurro" class="form-control">
        </div>
        </div>

    </div>
</div>
<div id="div2" class="div2" style="display:none;">
    
</div>
<?php }?>




<script type="text/javascript">
const txtdolo = document.getElementById('txtdolo');
const btnPlayTextordo = document.getElementById('playniv');

btnPlayTextordo.addEventListener('click', () => {
        leerTexto(txtdolo.value);
});

function leerTexto(txtdolo){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtdolo;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
<hr>
<div class="row">
  <div class="col"><button type="button" class="btn btn-success btn-sm" id="playsond"><i class="fas fa-play"></button></i> Sondas/catéteres:<input type="text" name="sondab" class="form-control" id="txtcat"></div>
  <script type="text/javascript">
const txtcat = document.getElementById('txtcat');
const btnPlayTextonda = document.getElementById('playsond');

btnPlayTextonda.addEventListener('click', () => {
        leerTexto(txtcat.value);
});

function leerTexto(txtcat){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtcat;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>

  <div class="col"><button type="button" class="btn btn-success btn-sm" id="playdietas"><i class="fas fa-play"></button></i> Dieta:<input type="text" name="dietab" class="form-control" id="txtddie"></div>
  <script type="text/javascript">
const txtddie = document.getElementById('txtddie');
const btnPlayTexd = document.getElementById('playdietas');

btnPlayTexd.addEventListener('click', () => {
        leerTexto(txtddie.value);
});

function leerTexto(txtddie){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtddie;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
  <div class="col"><button type="button" class="btn btn-success btn-sm" id="playglc"><i class="fas fa-play"></button></i> Glucosa capilar:<input type="text" name="glucocab" class="form-control" id="txtcagl"></div>
</div>
<script type="text/javascript">
const txtcagl = document.getElementById('txtcagl');
const btnPlayTextgosa = document.getElementById('playglc');

btnPlayTextgosa.addEventListener('click', () => {
        leerTexto(txtcagl.value);
});

function leerTexto(txtcagl){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtcagl;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
<hr>
<div class="row">
  <div class="col"><button type="button" class="btn btn-success btn-sm" id="playria"><i class="fas fa-play"></button></i> Glucocetonuria:<input type="text" name="glucob" class="form-control" id="txtnur"></div>
  <script type="text/javascript">
const txtnur = document.getElementById('txtnur');
const btnPlayTextglucoer = document.getElementById('playria');

btnPlayTextglucoer.addEventListener('click', () => {
        leerTexto(txtnur.value);
});

function leerTexto(txtnur){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtnur;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
  <div class="col"><button type="button" class="btn btn-success btn-sm" id="playinsuu"><i class="fas fa-play"></button></i> Insulina:<input type="text" name="insulinab" class="form-control" id="txtlinai"></div>
  <script type="text/javascript">
const txtlinai = document.getElementById('txtlinai');
const btnPlayTextsuia = document.getElementById('playinsuu');

btnPlayTextsuia.addEventListener('click', () => {
        leerTexto(txtlinai.value);
});

function leerTexto(txtlinai){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtlinai;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
  <div class="col"><button type="button" class="btn btn-success btn-sm" id="playcana"><i class="fas fa-play"></button></i> Canalizaciones:<input type="text" name="canalizab" class="form-control" id="txtzac"></div>
</div><p>
  <script type="text/javascript">
const txtzac = document.getElementById('txtzac');
const btnPlayTextnesca = document.getElementById('playcana');

btnPlayTextnesca.addEventListener('click', () => {
        leerTexto(txtzac.value);
});

function leerTexto(txtzac){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtzac;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
<div class="row">
<div class="col">Soluciones parenterales:<div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="btnStartRecord"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="btnStopRecord"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playsolparenter"><i class="fas fa-play"></button></i>
</div>
<textarea rows="2" name="solparenb" class="form-control" id="texto"></textarea>
<script type="text/javascript">
const btnStartRecord = document.getElementById('btnStartRecord');
const btnStopRecord = document.getElementById('btnStopRecord');
const texto = document.getElementById('texto');

const btnPlayTextlesso = document.getElementById('playsolparenter');

btnPlayTextlesso.addEventListener('click', () => {
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

     let recognition = new webkitSpeechRecognition();
      recognition.lang = "es-ES";
      recognition.continuous = true;
      recognition.interimResults = false;

      recognition.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        texto.value += frase;
      }

      btnStartRecord.addEventListener('click', () => {
        recognition.start();
      });

      btnStopRecord.addEventListener('click', () => {
        recognition.abort();
      });
</script>

</div>
</div>


<script type="text/javascript">
const txtm1 = document.getElementById('txtm1');
const btnPlayTextm1 = document.getElementById('playmedicamentos');

btnPlayTextm1.addEventListener('click', () => {
        leerTexto(txtm1.value);
});

function leerTexto(txtm1){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtm1;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

const txtdosi1 = document.getElementById('txtdosi1');
const btnPlayTextm2 = document.getElementById('playmedicamentos');

btnPlayTextm2.addEventListener('click', () => {
        leerTexto(txtdosi1.value);
});

function leerTexto(txtdosi1){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtdosi1;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

const txtvia1 = document.getElementById('txtvia1');
const btnPlayTextv2 = document.getElementById('playmedicamentos');

btnPlayTextv2.addEventListener('click', () => {
        leerTexto(txtvia1.value);
});

function leerTexto(txtvia1){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtvia1;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

//med 2



const txtm2 = document.getElementById('txtm2');
const btnPlayTextmatm = document.getElementById('playmedicamentos');

btnPlayTextmatm.addEventListener('click', () => {
        leerTexto(txtm2.value);
});

function leerTexto(txtm2){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtm2;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
const txtdosi2 = document.getElementById('txtdosi2');
const btnPlayTextosis2 = document.getElementById('playmedicamentos');

btnPlayTextosis2.addEventListener('click', () => {
        leerTexto(txtdosi2.value);
});

function leerTexto(txtdosi2){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtdosi2;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
const txtvia2 = document.getElementById('txtvia2');
const btnPlayTextvia2v = document.getElementById('playmedicamentos');

btnPlayTextvia2v.addEventListener('click', () => {
        leerTexto(txtvia2.value);
});

function leerTexto(txtvia2){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtvia2;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
//med 3

const txtm3 = document.getElementById('txtm3');
const btnPlayTextmediii = document.getElementById('playmedicamentos');

btnPlayTextmediii.addEventListener('click', () => {
        leerTexto(txtm3.value);
});

function leerTexto(txtm3){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtm3;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
const txtdosi3 = document.getElementById('txtdosi3');
const btnPlayTextdoosis = document.getElementById('playmedicamentos');

btnPlayTextdoosis.addEventListener('click', () => {
        leerTexto(txtdosi3.value);
});

function leerTexto(txtdosi3){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtdosi3;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
const txtvia3 = document.getElementById('txtvia3');
const btnPlayTextviaia = document.getElementById('playmedicamentos');

btnPlayTextviaia.addEventListener('click', () => {
        leerTexto(txtvia3.value);
});

function leerTexto(txtvia3){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtvia3;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>


<script type="text/javascript">
const txttotegre = document.getElementById('txttotegre');
const btnPlayTextegresototall = document.getElementById('playegrt');
btnPlayTextegresototall.addEventListener('click', () => {
        leerTexto(txttotegre.value);
});

function leerTexto(txttotegre){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txttotegre;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>


<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
         <tr><strong><center>RIESGO DE CAIDAS
Escala Humpty Dumpty- Paciente hospitalizado</center></strong>
</div>

<table class="table">
  <thead style="background-color: #2b2d7f; color: white; font-size: 16px;">
    <tr>
      <th scope="col">Parámetros</th>
      <th scope="col">Criterios</th>
      <th scope="col">Puntos</th>
      <th scope="col"><center>Valor</center></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row"><p></p><br>Edad</th>
      <td>Menos de 3 años<br>
        De 3- 7 años <br>
        De 7-13 años<br>
        Mas de 13 años
    </td>
      <td><center>4<br>3<br>2<br>1</center></td>
      <td><div class="losInput"><input type="text" max="4" maxlength="1" name="edad" class="form-control" required onkeypress='return event.charCode >= 49 && event.charCode <= 52'></div></td>
    </tr>
    <tr>
      <th scope="row">Género</th>
      <td>Hombre<br>Mujer</td>
      <td><center>2<br>1</center></td>
      <td><div class="losInput"><input type="text" name="gen" maxlength="1" class="form-control" required onkeypress='return event.charCode >= 49 && event.charCode <= 50'></div></td>
    </tr>
    <tr>
      <th scope="row">Diagnóstico</th>
      <td>Problemas neurológicos.<br>Alteraciones de oxigenación:
(problemas respiratorios,anemia)
deshidratación, anorexia, vértigo.<br>Trastornos psíquicos o de
conducta.<br>Otro diagnostico.</td>
      <td><center>4<br>3<br><br>2<br>1</center></td>
      <td><br><div class="losInput"><input type="text" name="dico" maxlength="1" class="form-control" required onkeypress='return event.charCode >= 49 && event.charCode <= 52'></div></td>
    </tr>
    <tr>
      <th scope="row">Deterioro
cognitivo</th>
      <td>No conoce sus limitaciones<br>Se le olvida sus limitaciones<br>Orientado en sus propias
capacidades</td>
      <td><center>3<br>2<br>1</center></td>
      <td><br><div class="losInput"><input type="text" name="deter" maxlength="1" class="form-control" required onkeypress='return event.charCode >= 49 && event.charCode <= 51'></div></td>
    </tr>
    <tr>
      <th scope="row">Factores
Ambientales</th>
      <td>Historia de caída de bebes o
niños pequeños desde la cama.<br>Utiliza dispositivos de ayuda en
la cuna, iluminación, muebles. <br>Paciente en la cama. <br> Paciente que deambula.</td>
      <td><center>4<br>3<br>2<br>1</center></td>
      <td><br><div class="losInput"><input type="text" name="facam" maxlength="1" class="form-control" required onkeypress='return event.charCode >= 49 && event.charCode <= 52'></div></td>
    </tr>
    <tr>
      <th scope="row">Cirugía o
sedación
anestésica</th>
      <td>Dentro de las 24 horas<br>Dentro de 48 horas<br>Mas de 48 horas /ninguna</td>
      <td><center>3<br>2<br>1</center></td>
      <td><br><div class="losInput"><input type="text" name="cirose" maxlength="1" class="form-control" required onkeypress='return event.charCode >= 49 && event.charCode <= 51'></div></td>
    </tr>
    <tr>
      <th scope="row">Medicación</th>
      <td>Uso de múltiples medicamentos
sedantes (Excluyen pacientes de
UCIP con sedantes o relajantes)
Hipnóticos,
Barbitúricos
Fenotiazinas,
Antidepresivos,
Laxantes/diuréticos
 narcóticos.<br>Uno de los medicamentos antes
mencionados.<br>Ninguno.</td>
      <td><center>3<br><br><br>2<br>1</center></td>
      <td><br><div class="losInput"><input type="text" name="medicac" maxlength="1" class="form-control" required onkeypress='return event.charCode >= 49 && event.charCode <= 51'></div></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
        <th scope="row"><center>Total</center></th>  
        <td><div class="inputTotal"><input type="number" name="tot" class="form-control" disabled></div></td>
    </tr>
  </tbody>
</table>
<hr>
<div class="form-group col-12">
<center><button type="submit" class="btn btn-primary">Firmar</button>
<button type="button" class="btn btn-danger" onclick="history.back()">Cancelar</button></center>
</div>


<br>
<br>
</form></div>
</div>


       
</div>

   


<?php
      } else {
        echo '<script type="text/javascript"> window.location.href="../../template/select_pac_enf.php";</script>';
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
$(document).ready(function () {
        $('#mibuscadormed').select2();
    });

  $('.losInput input').on('change', function(){
  var total = 0;
  $('.losInput input').each(function() {
    if($( this ).val() != "")
    {
      total = total + parseFloat($( this ).val());
    }
  });
  $('.inputTotal input').val(total.toFixed());
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