<?php
session_start();
include "../../conexionbd.php";
include("../header_medico.php");
if (isset($_SESSION['hospital'])) { //si existe el id mandado por metodo GET, se hara la consulta donde el id debe ser igual al id mandado por el metodo GET
    $resultado = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp  WHERE id_atencion=" . $_SESSION['hospital']) or die($conexion->error);
    if (mysqli_num_rows($resultado) > 0) { //se mostrara si existe mas de 1
        $f = mysqli_fetch_row($resultado);

    } else {
        header("Location: ./consulta_urgencias.php"); //te regresa a la página principal
    }
} else {
    header("Location: ./consulta_urgencias.php"); //te regresa a la página principal
}
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


    <title>NOTA DE EGRESO</title>


<style type="text/css">
   
    .modal-lg { max-width: 65% !important; }
</style>
</head>
<body>

<div class="col-sm-12">
    <div class="container">
        <div class="row">
            <div class="col-12">
                
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 24px;">
<strong><center>NOTA DE EGRESO</center></strong>
</div>
             
<?php

    include "../../conexionbd.php";

    if (isset($_SESSION['hospital'])) {
      $id_atencion = $_SESSION['hospital'];

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
    <div class="col-sm-2">
      Expediente: <strong><?php echo $folio?> </strong>
    </div>
    <div class="col-sm-6">
     Paciente: <strong><?php echo $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac ?></strong>
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
            
        </div>

<div class="tab-content" id="nav-tabContent">
   <!--INICIO-->
<div class="tab-pane fade show active" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">

<form action="" method="POST">
<?php 

include "../../conexionbd.php";
$resultado5=$conexion->query("select * from signos_vitales WHERE id_atencion=" . $_SESSION ['hospital'].".ORDER by id_sig DESC LIMIT 1") or die($conexion->error);

     while ($f5 = mysqli_fetch_array($resultado5)) {
      $atencion=$f5['id_sig'];
    }
    ?>
    <?php
if (isset($atencion)) {
                        ?>
<?php 

include "../../conexionbd.php";
$resultado5=$conexion->query("select * from signos_vitales WHERE id_atencion=" . $_SESSION ['hospital'].".ORDER by id_sig DESC LIMIT 1") or die($conexion->error);

     while ($f5 = mysqli_fetch_array($resultado5)) {
     
    
    ?>
     <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 19px;">
  <center><strong>SIGNOS VITALES</strong></center><p>
</div>
                <div class="container"> 
  <div class="row">
    
      
    
    <div class="col-sm-2"><center>Presión arterial:</center>
     <div class="row">
  <div class="col"><input type="number" class="form-control" value="<?php echo $f5['p_sistol'];?>" disabled></div> /
  <div class="col"><input type="number" class="form-control" value="<?php echo $f5['p_diastol'];?>" disabled></div>
 
</div>
    </div>
    <div class="col-sm-2">
      Frecuencia cardiaca:<input type="number" class="form-control" value="<?php echo $f5['fcard'];?>" disabled>
    </div>
    <div class="col-sm-3">
      Frecuencia respiratoria:<input type="number" class="form-control" value="<?php echo $f5['fresp'];?>" disabled>
    </div>
    <div class="col-sm-2">
     Temperatura:<input type="cm-number" class="form-control" value="<?php echo $f5['temper'];?>" disabled>
    </div>
    <div class="col-sm-2">
     Saturación oxígeno:<input type="number"  class="form-control" value="<?php echo $f5['satoxi'];?>" disabled>
    </div>
 <!--   <div class="col-sm-1">
     <br>Peso:<input type="cm-number" class="form-control" value="<?php echo $f5['peso'];?>" disabled>
    </div>
    <div class="col-sm-1">
     <br>Talla:<input type="cm-number" class="form-control" value="<?php echo $f5['talla'];?>" disabled>
    </div>-->
  </div>
</div>
<?php }
?>
<?php 
}else{
                        
  ?>
  <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 19px;">
  <center><strong>SIGNOS VITALES</strong></center><p>
</div>
                <div class="container"> 
  <div class="row">
    <div class="col-sm-2"><center>Presión arterial:</center>
     <div class="row">
  <div class="col"><input type="number" class="form-control" name="p_sistol" ></div> /
  <div class="col"><input type="number" class="form-control" name="p_diastol"></div>
 
</div>
    </div>
    <div class="col-sm-2">
      Frecuencia cardiaca:<input type="number" class="form-control" name="fcard">
    </div>
    <div class="col-sm-3">
      Frecuencia respiratoria:<input type="number" class="form-control" name="fresp">
    </div>
    <div class="col-sm-2">
     Temperatura:<input type="cm-number" class="form-control"  name="temper">
    </div>
    <div class="col-sm-2">
     Saturación oxígeno:<input type="number"  class="form-control" name="satoxi">
    </div>
    <!--<div class="col-sm-1">
     Peso kilos:<input type="cm-number" class="form-control"  name="peso">
    </div>
    <div class="col-sm-1">
     Talla metros:<input type="cm-number" class="form-control" name="talla" >
    </div>-->
  </div>
</div>

<?php } ?>
 <hr>
<div class="container">
    <div class="row">
        <div class="col-sm-3">
                
                
                <div class="form-group">
                    <label for="fecha"><strong>Fecha de ingreso:</label></strong>
                    <input type="datetime" name="fec_hc" value="<?php echo date_format($date, "d/m/Y H:i:s"); ?>" class="form-control" disabled>
                </div>
            </div>




        <div class="col-sm-3">
                <?php
                
                $fecha_actual = date("d-m-Y H:i:s");
                ?>
                
                <div class="form-group">
                    <label for="fecha"><strong>Fecha de egreso:</label></strong>
                    <input type="datetime" name="fec_hc" value="<?= $fecha_actual ?>" class="form-control" disabled>
                </div>
            </div>
            <div class="col-md">
                <div class="form-group">
                  <label class="control-label"><strong><button type="button" class="btn btn-success btn-sm" id="playa"><i class="fas fa-play"></button></i> Dias estancia en la unidad: </label></strong>
                  <div class="col-md-6">
                    <input type="text" name="dias" id="txtpa" class="form-control" value="<?php echo $estancia ?> días" disabled>
                  </div>
                </div>
              </div>
      </div>
    </div>
    <script type="text/javascript">
  const txtpa = document.getElementById('txtpa');
  const btnPlayTexta = document.getElementById('playa');

  btnPlayTexta.addEventListener('click', () => {
          leerTexto(txtpa.value);
  });

  function leerTexto(txtpa){
      const speech = new SpeechSynthesisUtterance();
      speech.text= txtpa;
      speech.volume=1;
      speech.rate=1;
      speech.pitch=0;
      window.speechSynthesis.speak(speech);
  }
  </script>
    <div class="row">
        <div class="col-sm">
            <strong><label>Diagnóstico de ingreso :</label></strong><br>
    
      <?php 
include "../../conexionbd.php";
$resultado5=$conexion->query("select * from diag_pac WHERE Id_exp=" . $_SESSION ['hospital'].".ORDER by Id_exp DESC") or die($conexion->error);

     while ($f5 = mysqli_fetch_array($resultado5)) {

    ?>
 
            <input type="text" name="diag_ingreso" class="form-control" placeholder="INGRESO" value="<?php echo $f5['diag_paciente'];?>" disabled>
        
<?php
    }
    ?>
    </div>
    </div>
    
    
    <?php
           $id_eg = $_GET['id_in'];
        $id_atencion = $_GET['id_atencion'];
            $sql = "SELECT * from dat_egreso where id_egreso = $id_eg and id_atencion=$id_atencion order by id_egreso DESC";
            $result = $conexion->query($sql);
            while ($row_datos = $result->fetch_assoc()) {
                $cond=$row_datos['cond'];
            ?> 
    <?php 
    
    if($row_datos['reingreso']=='NO'){
        
    ?>
      <div class="container">
  <div class="row">
    <div class="col-sm-5">
      <strong>Reingreso:</strong>
    </div>
    <div class="col-sm">
     <div class="form-check">
  <input class="form-check-input" type="radio" name="reingreso" id="flexRadioDisabled" value="NO" required="" checked>
  <label class="form-check-label" for="flexRadioDisabled">
    No
  </label>
</div>
    </div>
    <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="reingreso" id="flexRadioDisabled" value="SI">
  <label class="form-check-label" for="flexRadioDisabled">
    Si
  </label>
</div>
    </div>
  </div>
</div>
    <?php } else  if($row_datos['reingreso']=='SI'){?>
    <hr>
    <div class="container">
  <div class="row">
    <div class="col-sm-5">
      <strong>Reingreso:</strong>
    </div>
    <div class="col-sm">
     <div class="form-check">
  <input class="form-check-input" type="radio" name="reingreso" id="flexRadioDisabled" value="NO" required="">
  <label class="form-check-label" for="flexRadioDisabled">
    No
  </label>
</div>
    </div>
    <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="reingreso" id="flexRadioDisabled" value="SI" checked>
  <label class="form-check-label" for="flexRadioDisabled">
    Si
  </label>
</div>
    </div>
  </div>
</div>

<?php }?>


<?php 
if($row_datos['cond']=="MEJORIA"){
?>
<div class="col-sm">
            <div class="form-group">
                <strong><center><label>Condiciones de alta :</label></center></strong><br>
                <input type="radio" name="cond" value="MEJORIA" required="" checked>&nbsp; <label>  Mejoria</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="MÁXIMO BENEFICIO">&nbsp; <label>  Máximo beneficio</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="ALTA VOLUNTARIA">&nbsp; <label> Alta voluntaria</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="DEFUNCIÓN">&nbsp; <label>  Defunción</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="TRASLADO A OTRA INSTITUCIÓN">&nbsp; <label>  Traslado a otra institución</label>
            </div>        
          </div> 

<?php }else if($row_datos['cond']=="MÁXIMO BENEFICIO"){?>
<div class="col-sm">
            <div class="form-group">
                <strong><center><label>Condiciones de alta :</label></center></strong><br>
                <input type="radio" name="cond" value="MEJORIA" required="" >&nbsp; <label>  Mejoria</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="MÁXIMO BENEFICIO" checked>&nbsp; <label>  Máximo beneficio</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="ALTA VOLUNTARIA">&nbsp; <label> Alta voluntaria</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="DEFUNCIÓN">&nbsp; <label>  Defunción</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="TRASLADO A OTRA INSTITUCIÓN">&nbsp; <label>  Traslado a otra institución</label>
            </div>        
          </div> 

<?php }else if($row_datos['cond']=="ALTA VOLUNTARIA"){?>

<div class="col-sm">
            <div class="form-group">
                <strong><center><label>Condiciones de alta :</label></center></strong><br>
                <input type="radio" name="cond" value="MEJORIA" required="" >&nbsp; <label>  Mejoria</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="MÁXIMO BENEFICIO" >&nbsp; <label>  Máximo beneficio</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="ALTA VOLUNTARIA" checked>&nbsp; <label> Alta voluntaria</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="DEFUNCIÓN">&nbsp; <label>  Defunción</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="TRASLADO A OTRA INSTITUCIÓN">&nbsp; <label>  Traslado a otra institución</label>
            </div>

<?php }else if($row_datos['cond']=='DEFUNCIÓN'){?>

<div class="col-sm">
            <div class="form-group">
                <strong><center><label>Condiciones de alta :</label></center></strong><br>
                <input type="radio" name="cond" value="MEJORIA" required="" >&nbsp; <label>  Mejoria</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="MÁXIMO BENEFICIO" >&nbsp; <label>  Máximo beneficio</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="ALTA VOLUNTARIA" >&nbsp; <label> Alta voluntaria</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="DEFUNCIÓN" checked>&nbsp; <label>  Defunción</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="TRASLADO A OTRA INSTITUCIÓN">&nbsp; <label>  Traslado a otra institución</label>
            </div>

<?php }else if($row_datos['cond']=='TRASLADO A OTRA INSTITUCIÓN'){?>

<div class="col-sm">
            <div class="form-group">
                <strong><center><label>Condiciones de alta :</label></center></strong><br>
                <input type="radio" name="cond" value="MEJORIA" required="" >&nbsp; <label>  Mejoria</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="MÁXIMO BENEFICIO" >&nbsp; <label>  Máximo beneficio</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="ALTA VOLUNTARIA" >&nbsp; <label> Alta voluntaria</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="DEFUNCIÓN" >&nbsp; <label>  Defunción</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="TRASLADO A OTRA INSTITUCIÓN" checked>&nbsp; <label>  Traslado a otra institución</label>
            </div>

<?php }?>
<?php   $sql = "SELECT * from alta where id_atencion = $id_atencion order by id_alta DESC limit 1";
            $resulta = $conexion->query($sql);
            while ($row_datosa = $resulta->fetch_assoc()) {
   
            ?> 
            
<hr>

<div class="container">
<div class="row">
    <div class="col-sm">
    Observaciones para formato de alta médica:<div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="altaga"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="stopaltaa"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playobsaa"><i class="fas fa-play"></button></i>
</div>
    <textarea class="form-control" name="obs_med" id="txtoalta"><?php echo $row_datosa['obs_med'] ?></textarea>
     <script type="text/javascript">
const altaga = document.getElementById('altaga');
const stopaltaa = document.getElementById('stopaltaa');
const txtoalta = document.getElementById('txtoalta');

const btnPlaysobaa = document.getElementById('playobsaa');

  btnPlaysobaa.addEventListener('click', () => {
          leerTexto(txtoalta.value);
  });

  function leerTexto(txtoalta){
      const speecha = new SpeechSynthesisUtterance();
      speecha.text= txtoalta;
      speecha.volume=1;
      speecha.rate=1;
      speecha.pitch=0;
      window.speechSynthesis.speak(speecha);
}
     let recaltaa = new webkitSpeechRecognition();
      recaltaa.lang = "es-ES";
      recaltaa.continuous = true;
      recaltaa.interimResults = false;

      recaltaa.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtoalta.value += frase;
      }

      altaga.addEventListener('click', () => {
        recaltaa.start();
      });

      stopaltaa.addEventListener('click', () => {
        recaltaa.abort();
      });
</script>
    </div>
</div>
</div>

<hr>
<?php }?>

<!-- DIAGNÓSTICO DE EGRESO QUE SE USARÁ PARA LA ESTADÍSTICA DEL IGENI -->

<div class="row">
    <div class=" col-6">
        <STRONG> <button type="button" class="btn btn-success btn-sm" id="playdia1" ><i class="fas fa-play"></button></i>
        Diagnóstico de egreso:</STRONG>
        <div class="form-group" data-toggle="tooltip" data-placement="top" title="Cargando...">
            <select name="id_diag" class="form-control" data-live-search="true" id="mibuscador" style="width : 100%; heigth : 100%" required >
                <option value="<?php echo $row_datos['diag_eg']?>"><?php echo $row_datos['diag_eg']?></option>
                <option value="">Seleccionar</option>
            <?php
                include "../../conexionbd.php";
                $sql_diag="SELECT * FROM cat_diag ORDER by id_cie10";
                $result_diag=$conexion->query($sql_diag);
                while($row=$result_diag->fetch_assoc()){
                    echo "<option value='" . $row['id_diag'] . "'>" . $row['id_cie10'] .' - '. $row['diagnostico'] . "</option>";
                }
            ?>
            </select>
        </div>
    </div>
    <script type="text/javascript">
        const mibuscador = document.getElementById('mibuscador');
        const btnPlayTe1 = document.getElementById('playdia1');

        btnPlayTe1.addEventListener('click', () => {
          leerTexto(mibuscador.value);
        });

        function leerTexto(mibuscador){
            const speech = new SpeechSynthesisUtterance();
            speech.text= mibuscador;
            speech.volume=1;
            speech.rate=1;
            speech.pitch=0;
            window.speechSynthesis.speak(speech);
        }
    </script>
    

</div>


          <br>



    <div class="row">
        <div class="col-sm">
            <div class="form-group">
            <strong><label>Diagnóstico(s) finales(es) :</label></strong>
            <div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="btnStartRecord"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="btnStopRecord"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playdiafinn"><i class="fas fa-play"></button></i>
</div>
            <textarea class="form-control" name="diag_fin" rows="3" id="texto"><?php echo $row_datos['diagfinal']?></textarea>
            <script type="text/javascript">
const btnStartRecord = document.getElementById('btnStartRecord');
const btnStopRecord = document.getElementById('btnStopRecord');
const texto = document.getElementById('texto');

const btnPlaysobaaxx = document.getElementById('playdiafinn');

  btnPlaysobaaxx.addEventListener('click', () => {
          leerTexto(texto.value);
  });

  function leerTexto(texto){
      const speecha = new SpeechSynthesisUtterance();
      speecha.text= texto;
      speecha.volume=1;
      speecha.rate=1;
      speecha.pitch=0;
      window.speechSynthesis.speak(speecha);
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
    </div>

<div class="row">
        <div class="col-sm">
            <div class="form-group">
            <strong><label>Resumen de evolución y estado actual:</label></strong>
            <div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="resg"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="retac"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playrdeye"><i class="fas fa-play"></button></i>
</div>
            <textarea class="form-control" name="res_clin" rows="3" id="txtraea" ><?php echo $row_datos['res_clin']?></textarea>
            <script type="text/javascript">
const resg = document.getElementById('resg');
const retac = document.getElementById('retac');
const txtraea = document.getElementById('txtraea');

const btnPlayactualresnnm = document.getElementById('playrdeye');

  btnPlayactualresnnm.addEventListener('click', () => {
          leerTexto(txtraea.value);
  });

  function leerTexto(txtraea){
      const speecha = new SpeechSynthesisUtterance();
      speecha.text= txtraea;
      speecha.volume=1;
      speecha.rate=1;
      speecha.pitch=0;
      window.speechSynthesis.speak(speecha);
}

     let rse = new webkitSpeechRecognition();
      rse.lang = "es-ES";
      rse.continuous = true;
      rse.interimResults = false;

      rse.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtraea.value += frase;
      }

      resg.addEventListener('click', () => {
        rse.start();
      });

      retac.addEventListener('click', () => {
        rse.abort();
      });
</script>
             </div>
        </div>
      </div> 

  <div class="row">
          <div class="col-sm">
              <div class="form-group">
                  <strong><label>Manejo durante la estancia hospitalaria / fecha y hora de procedimientos realizados : </label></strong>
                  <div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="mang"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="deth"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playmanjeo"><i class="fas fa-play"></button></i>
</div>
                  <textarea class="form-control" name="manejodur" rows="3" id="txtfh"><?php echo $row_datos['manejodur']?></textarea>
                  <script type="text/javascript">
const mang = document.getElementById('mang');
const deth = document.getElementById('deth');
const txtfh = document.getElementById('txtfh');

const btnPlaymmm = document.getElementById('playmanjeo');

  btnPlaymmm.addEventListener('click', () => {
          leerTexto(txtfh.value);
  });

  function leerTexto(txtfh){
      const speecha = new SpeechSynthesisUtterance();
      speecha.text= txtfh;
      speecha.volume=1;
      speecha.rate=1;
      speecha.pitch=0;
      window.speechSynthesis.speak(speecha);
}
     let rma = new webkitSpeechRecognition();
      rma.lang = "es-ES";
      rma.continuous = true;
      rma.interimResults = false;

      rma.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtfh.value += frase;
      }

      mang.addEventListener('click', () => {
        rma.start();
      });

      deth.addEventListener('click', () => {
        rma.abort();
      });
</script>
              </div>
          </div>
      </div>


 <div class="row">
          <div class="col-sm">
              <div class="form-group">
                  <strong><label>Problemas clínicos pendientes : </label></strong>
                  <div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="prog"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="detcp"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playprocp"><i class="fas fa-play"></button></i>
</div>
                  <textarea class="form-control" name="probclip" rows="3" id="txtpe"><?php echo $row_datos['probclip']?></textarea>
<script type="text/javascript">
const prog = document.getElementById('prog');
const detcp = document.getElementById('detcp');
const txtpe = document.getElementById('txtpe');

const btnPlayclincc = document.getElementById('playprocp');

  btnPlayclincc.addEventListener('click', () => {
          leerTexto(txtpe.value);
  });

  function leerTexto(txtpe){
      const speecha = new SpeechSynthesisUtterance();
      speecha.text= txtpe;
      speecha.volume=1;
      speecha.rate=1;
      speecha.pitch=0;
      window.speechSynthesis.speak(speecha);
}

     let rpc = new webkitSpeechRecognition();
      rpc.lang = "es-ES";
      rpc.continuous = true;
      rpc.interimResults = false;

      rpc.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtpe.value += frase;
      }

      prog.addEventListener('click', () => {
        rpc.start();
      });

      detcp.addEventListener('click', () => {
        rpc.abort();
      });
</script>
              </div>
          </div>
       </div>

<div class="row">
          <div class="col-sm">
              <div class="form-group">
                  <strong><label>Recomendaciones para la vigilancia ambulatoria : </label></strong>
                  <div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="vig"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="detvar"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playrvig"><i class="fas fa-play"></button></i>
</div>
                  <textarea class="form-control" name="cuid" rows="3" id="txtco"><?php echo $row_datos['cuid']?></textarea>
<script type="text/javascript">
const vig = document.getElementById('vig');
const detvar = document.getElementById('detvar');
const txtco = document.getElementById('txtco');

const btnPlayclincca = document.getElementById('playrvig');

  btnPlayclincca.addEventListener('click', () => {
          leerTexto(txtco.value);
  });

  function leerTexto(txtco){
      const speecha = new SpeechSynthesisUtterance();
      speecha.text= txtco;
      speecha.volume=1;
      speecha.rate=1;
      speecha.pitch=0;
      window.speechSynthesis.speak(speecha);
}

     let rvma = new webkitSpeechRecognition();
      rvma.lang = "es-ES";
      rvma.continuous = true;
      rvma.interimResults = false;

      rvma.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtco.value += frase;
      }

      vig.addEventListener('click', () => {
        rvma.start();
      });

      detvar.addEventListener('click', () => {
        rvma.abort();
      });
</script>
              </div>
          </div>
      </div>

 <div class="row">
          <div class="col-sm">
              <div class="form-group">
                  <strong><label>Medicamentos prescritos al egreso (Receta médica): </label></strong>
                  <div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="etag"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="detecta"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playcre"><i class="fas fa-play"></button></i>
</div>
                  <textarea class="form-control" name="trat" rows="15" id="txtrct"><?php echo $row_datos['trat']?></textarea>
<script type="text/javascript">
const etag = document.getElementById('etag');
const detecta = document.getElementById('detecta');
const txtrct = document.getElementById('txtrct');

const btnPlarrr = document.getElementById('playcre');

  btnPlarrr.addEventListener('click', () => {
          leerTexto(txtrct.value);
  });

  function leerTexto(txtrct){
      const speecha = new SpeechSynthesisUtterance();
      speecha.text= txtrct;
      speecha.volume=1;
      speecha.rate=1;
      speecha.pitch=0;
      window.speechSynthesis.speak(speecha);
}

     let rcee = new webkitSpeechRecognition();
      rcee.lang = "es-ES";
      rcee.continuous = true;
      rcee.interimResults = false;

      rcee.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtrct.value += frase;
      }

      etag.addEventListener('click', () => {
        rcee.start();
      });

      detecta.addEventListener('click', () => {
        rcee.abort();
      });
</script>
              </div>
          </div>
      </div>


<div class="row">
          <div class="col-sm">
              <div class="form-group">
                  <strong><label>El estado del paciente al momento del alta: </label></strong>
                  <div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="vigedo"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="detvaredo"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playrvigedo"><i class="fas fa-play"></button></i>
</div>
<textarea class="form-control" name="cuidedo" rows="3" id="txtco"><?php echo $row_datos['edo']?></textarea>

              </div>
          </div>
      </div>

<div class="row">
          <div class="col-sm">
              <div class="form-group">
                 <strong> <label>Examenes o estudios de seguimiento : </label></strong>
                 <div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="exag"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="deteeds"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playexase"><i class="fas fa-play"></button></i>
</div>
                  <textarea class="form-control" name="exes" rows="3" id="txtosee"><?php echo $row_datos['exes']?></textarea>
<script type="text/javascript">
const exag = document.getElementById('exag');
const deteeds = document.getElementById('deteeds');
const txtosee = document.getElementById('txtosee');

const btnPlarss = document.getElementById('playexase');

  btnPlarss.addEventListener('click', () => {
          leerTexto(txtosee.value);
  });

  function leerTexto(txtosee){
      const speecha = new SpeechSynthesisUtterance();
      speecha.text= txtosee;
      speecha.volume=1;
      speecha.rate=1;
      speecha.pitch=0;
      window.speechSynthesis.speak(speecha);
}


     let roese = new webkitSpeechRecognition();
      roese.lang = "es-ES";
      roese.continuous = true;
      roese.interimResults = false;

      roese.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtosee.value += frase;
      }

      exag.addEventListener('click', () => {
        roese.start();
      });

      deteeds.addEventListener('click', () => {
        roese.abort();
      });
</script>
              </div>
          </div>
      </div>


<div class="row">

    <div class=" col-sm-9">
<strong><button type="button" class="btn btn-success btn-sm" id="playguiaa"><i class="fas fa-play"></button></i> Guía de práctica clínica:</strong>

<div class="form-group">
     <div class="form-group">
        <select name="guia" class="form-control" data-live-search="true" id="mibuscador11" onchange="ShowSelected();" style="width : 100%; heigth : 100%">
               <option value="<?php echo $row_datos['guia']?>"><?php echo $row_datos['guia']?></option>
            <option value="">Seleccionar guía clínica</option>
<?php
include "../../conexionbd.php";
$sql_diag="SELECT * FROM gpclinica ORDER by id_gpc";
$result_diag=$conexion->query($sql_diag);
while($row=$result_diag->fetch_assoc()){
echo "<option value='" . $row['gpc'] . "'>" . $row['cve_gpc'] . "- " .$row['gpc']."</option>";
}
 ?></select>
  </div>
</div>
</div>
<script type="text/javascript">
const mibuscador11 = document.getElementById('mibuscador11');
const btnPlarssag = document.getElementById('playguiaa');

  btnPlarssag.addEventListener('click', () => {
          leerTexto(mibuscador11.value);
  });

  function leerTexto(mibuscador11){
      const speecha = new SpeechSynthesisUtterance();
      speecha.text= mibuscador11;
      speecha.volume=1;
      speecha.rate=1;
      speecha.pitch=0;
      window.speechSynthesis.speak(speecha);
}

</script>
<div class="col-sm-3">
<p></p>
<button type="button" class="btn btn bg-navy" data-toggle="modal" data-target=".bd-example-modal-lg"><strong>Links de guía de práctica clínica</strong></button>

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header content-center">
        <h4><b>Guía de práctica clínica</b></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
       <div class="modal-body">
         <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:20%" id="search" placeholder="BUSCAR...">
            </div>
     <div class="table-responsive">

            <table class="table table-bordered table-striped" id="mytable">
                <thead class="thead bg-navy">
                    <th scope="col"><center>Clave</center></th>
                    <th scope="col"><center>Guía & Link</center></th>
                </tr>
                </thead>
                <tbody>
<?php
$sql_diag="SELECT * FROM gpclinica ORDER by id_gpc";
$result_diag=$conexion->query($sql_diag);
while($row=$result_diag->fetch_assoc()){            
    ?>
                    <tr> 
<td class="fondo"><strong><?php echo $row['cve_gpc'];?></strong></td>
<td class="fondo"><a href="<?php echo $row['link']?>" target=”_blank”><strong><?php echo $row['gpc'];?></strong></td></a>   
                    </tr>
                <?php
                }
                ?>
                </tbody>
              
            </table>
            </div>
  </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Regresar</button>
      </div>
    </div>
  </div>
</div>
</div>
</div>


  <div class="row">
          <div class="col-sm-3">
                <div class="form-group">
                  <strong><label>Proxima cita o consulta : </label></strong>
                  <input type="date" class="form-control" name="pcita" value="<?php echo $row_datos['pcita']?>"> 
                </div>
           </div>
           <div class="col-sm-3">
                <div class="form-group">
                  <strong><label>Hora : </label></strong>
                  <input type="time" name="hcita" class="form-control" value="<?php echo $row_datos['hcita']?>">
             
           
                </div>
          </div>
    </div>

<?php } ?> <!--fin de consulta -->


    


    

      
    
     
      
     
      







    
    </div>
<hr>
</div>

<center><div class="form-group col-6">
    <button type="submit" class="btn btn-success" name="guardar" >Editar y guardar</button>

     <a href="../vistas_pdf/vista_egreso.php" class="btn btn-danger btn-sm">Cancelar</a>
</div></center>
<br>
<br>
</form>
</div>
<!--TERMINO DE NOTA DE EGRESO-->
</div>
</div>
<?php 
/* INCIO DE IF GUARDAR */
if (isset($_POST['guardar'])) {
       $usuario = $_SESSION['login'];
$id_usua= $usuario['id_usua'];
$id_atencion = $_SESSION['hospital'];
//date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

     //$diag_ingreso= mysqli_real_escape_string($conexion, (strip_tags($_POST["diag_ingreso"], ENT_QUOTES))); 
     $diag_fin   = mysqli_real_escape_string($conexion, (strip_tags($_POST["diag_fin"], ENT_QUOTES)));
     $id_diag   = mysqli_real_escape_string($conexion, (strip_tags($_POST["id_diag"], ENT_QUOTES)));
     $res_clin    = mysqli_real_escape_string($conexion, (strip_tags($_POST["res_clin"], ENT_QUOTES)));
     $reingreso    = mysqli_real_escape_string($conexion, (strip_tags($_POST["reingreso"], ENT_QUOTES)));
     $cond    = mysqli_real_escape_string($conexion, (strip_tags($_POST["cond"], ENT_QUOTES)));
     $manejodur    = mysqli_real_escape_string($conexion, (strip_tags($_POST["manejodur"], ENT_QUOTES)));
     $probclip    = mysqli_real_escape_string($conexion, (strip_tags($_POST["probclip"], ENT_QUOTES)));
     $cuid    = mysqli_real_escape_string($conexion, (strip_tags($_POST["cuid"], ENT_QUOTES)));
     $cuidedo    = mysqli_real_escape_string($conexion, (strip_tags($_POST["cuidedo"], ENT_QUOTES)));
     $trat    = mysqli_real_escape_string($conexion, (strip_tags($_POST["trat"], ENT_QUOTES)));
     $exes    = mysqli_real_escape_string($conexion, (strip_tags($_POST["exes"], ENT_QUOTES)));
     $pcita    = mysqli_real_escape_string($conexion, (strip_tags($_POST["pcita"], ENT_QUOTES)));
     $hcita    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hcita"], ENT_QUOTES)));

     $fech_alta    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fech_alta"], ENT_QUOTES)));
     $hor_alta    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hor_alta"], ENT_QUOTES)));
     $obs_med    = mysqli_real_escape_string($conexion, (strip_tags($_POST["obs_med"], ENT_QUOTES)));
     $guia    = mysqli_real_escape_string($conexion, (strip_tags($_POST["guia"], ENT_QUOTES)));
                                                /* signos vitales   */

    $resultado4i=$conexion->query("SELECT * from cat_diag WHERE id_diag='$id_diag'") or die($conexion->error);
    while ($fd = mysqli_fetch_array($resultado4i)) {
        $id_inegi=$fd["id_inegi"];
        $diag_eg =$fd["diagnostico"];
    }


                    /*  fin de signos vitales   */

                     /*   diagnostico paciente   */
$resultado1 = $conexion ->query("SELECT diag_paciente FROM diag_pac WHERE id_exp=$id_atencion")or die($conexion->error);
if(mysqli_num_rows($resultado1) > 0 ){ 
   $fila=mysqli_fetch_row($resultado1);
   $diagfinal=$fila[0];
}

 $sql2 = "UPDATE dat_egreso SET id_inegi='$id_inegi', diag_eg = '$diag_eg',res_clin = '$res_clin',diagfinal = '$diag_fin',reingreso='$reingreso',cond='$cond',manejodur='$manejodur',probclip='$probclip',cuid='$cuid',edo='$cuidedo',trat='$trat',exes='$exes',pcita='$pcita',hcita='$hcita',guia='$guia' WHERE id_egreso = $id_eg";
        $result = $conexion->query($sql2);

 $sql3 = "UPDATE alta SET obs_med='$obs_med' WHERE id_atencion = $id_atencion";
        $result3 = $conexion->query($sql3);

 echo '<script type="text/javascript">window.location.href ="vista_egreso.php" ;</script>';
}
/* FIN DE IF GUARDAR */


 ?>
<footer class="main-footer">
    <?php
    include("../../template/footer.php");
    ?>
</footer>

<!-- FastClick -->
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>


<script type="text/javascript">
    $(document).ready(function () {
        $('#mibuscador').select2();
    });
    $(document).ready(function () {
        $('#mibuscador11').select2();
    });
</script>

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
</body>
</html>