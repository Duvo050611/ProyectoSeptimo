<?php
session_start();


include "../../conexionbd.php";
include("../header_medico.php");

$usuario = $_SESSION['login'];
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

    <title>NOTA ANESTESICA </title>
</head>
<body>

<div class="col-sm-12">
    <div class="container">
        <div class="row">
            <div class="col">
<div class="thead" style="background-color: #0c675e; color: white; font-size: 24px;">
  <center><strong>EVALUACIÓN, INMEDIATAMENTE ANTES DEL PROCEDIMIENTO ANESTÉSICO</strong></center><p>
</div>                

    <hr>
<?php

include "../../conexionbd.php";
$id_atencion=$_GET['id_atencion'];
$resultado1 = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp 

WHERE id_atencion=$id_atencion") or die($conexion->error);

?>
  <?php
                    while ($f1 = mysqli_fetch_array($resultado1)) {

                        ?>
<div class="container"> 
                        <div class="row">
      <div class="col-sm-6">
 NO.EXPEDIENTE : <td><strong><?php echo $f1['Id_exp']; ?></strong></td><br>
PACIENTE : <td><strong><?php echo $f1['papell']; ?></strong></td>
<td><strong><?php echo $f1['sapell']; ?></strong></td>
<td><strong><?php echo $f1['nom_pac']; ?></strong></td><br>
SEXO : <td><strong><?php echo $f1['sexo']; ?></strong></td><br>
  
    </div>

    <div class="col-sm-4">
<?php $date = date_create($f1['fecha']);
$date1 = date_create($f1['fecnac']);
                                     ?>
      FECHA DE INGRESO : <td><strong><?php echo date_format($date, "d/m/Y"); ?></strong></td><br>
      FECHA DE NACIMIENTO : <td><strong><?php echo date_format($date1, "d/m/Y"); ?></strong></td><br>
      EDAD : <td><strong><?php echo $f1['edad']; ?></strong></td><br>  

    <?php  
                    }
                    ?> 
    </div>
  
<?php
$resultado2 = $conexion->query("select * from cat_camas WHERE id_atencion=$id_atencion") or die($conexion->error);
while ($f2 = mysqli_fetch_array($resultado2)) {
?>
  <div class="col">
<?php  
 if(isset($f2)){
    $cama=$f2['num_cama'];
  }else{
    $cama='Sin Cama';
  }
 ?>
HABITACIÓN : <td><strong><?php echo $cama; ?></strong></td> 

<?php
}
?>
    </div>
    
    
  </div>
 <?php 
   $resultado12 = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion, reg_usuarios.*
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp 
inner join reg_usuarios on dat_ingreso.id_usua=reg_usuarios.id_usua 
WHERE id_atencion=$id_atencion") or die($conexion->error);

?>
  <?php
                    while ($f2 = mysqli_fetch_array($resultado12)) {

                        ?>

MEDICO TRATANTE : <td><strong><?php echo  $f2['sapell'] . ' ' . $f2['papell'] . ' ' . $f2['nombre']; ?></strong></td>    
 <?php  
                    }
                    ?>
</div>
<hr>
 </div>   
 <div class="row">
            <div class="col-sm-10">
                <?php
                date_default_timezone_set('America/Guatemala');
                $fecha_actual = date("d-m-Y H:i:s");
                ?>
                
                <div class="form-group">
                    <label for="fecha">FECHA Y HORA ACTUAL:</label>
                    <input type="datetime" name="fec_hc" value="<?= $fecha_actual ?>" class="form-control" disabled>
                </div>
            </div>
        </div>



  
      <div class="container">  <!--INICIO DE CONSULTA DE URGENCIAS-->


    
<form action="" method="POST">
<?php 
$id_seg_evol_amb=$_GET['id_seg_evol_amb'];
$alta="SELECT * FROM dat_seg_evol_amb where id_seg_evol_amb=$id_seg_evol_amb";
$result=$conexion->query($alta);
while ($row=$result->fetch_assoc()) {
?>
<div class="container">
  <div class="row">
    <div class="col-sm-6">
     FECHA: <input type="date" name="fecha2" value="<?php echo $row['fecha2'] ?>" id="fecha2" required class="form-control col-sm-5">
    </div>
    <div class="col-sm-4">
     HORA: <input type="time" name="hora2" value="<?php echo $row['hora2'] ?>" id="hora2" required class="form-control col-sm-8">
    </div>
  </div>
</div><hr>
<div class="container">
  <div class="row">
    <div class="col-sm-7">
     SE CORROBORÓ LA IDENTIFICACIÓN DEL PACIENTE, SU ESTADO ACTUAL, EL DIAGNÓSTICO Y EL PROCEDIMIENTO PROGRAMADO ANTES DEL INICIO DE LA ANESTESIA
    </div>
<?php if ($row['diaproc']=="SI") {?>
    <div class="col-sm-2">
     <div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="diaproc" id="anest" value="SI" required>
  <label class="form-check-label" for="anest">
   SI
  </label>
</div>
    </div>
    <div class="col-sm-3">
     <div class="form-check">
  <input class="form-check-input" type="radio" name="diaproc" id="anest2" value="NO" required>
  <label class="form-check-label" for="anest2">
    NO
  </label>
</div>
    </div>
<?php }else{ ?>
<div class="col-sm-2">
     <div class="form-check">
  <input class="form-check-input" type="radio" name="diaproc" id="anest" value="SI" required>
  <label class="form-check-label" for="anest">
   SI
  </label>
</div>
    </div>
    <div class="col-sm-3">
     <div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="diaproc" id="anest2" value="NO" required>
  <label class="form-check-label" for="anest2">
    NO
  </label>
</div>
    </div>
<?php } ?>
  </div>
</div>
<hr>
<div class="col">
    <br><strong>SIGNOS VITALES:</strong></div>
<div class="row">

  <div class="col col-sm-5">
    <br>
      PRESIÓN ARTERIAL:<br><input required type="number" name="sist" placeholder="mmHg"  id="p_sistolica"   value="<?php echo $row['sist'] ?>"  required> / <label for="p_sistolica"><input required type="number" name="diast"   placeholder="mmHg" id="p_diastolica"  value="<?php echo $row['diast'] ?>"  required>
   
    </div>
  <div class="col">FRECUENCIA CARDIACA: <input type="text" name="freccard" value="<?php echo $row['freccard'] ?>" class="form-control" required></div>
  <div class="col">FRECUENCIA RESPIRATORIA: <input type="text" name="frecresp" value="<?php echo $row['frecresp'] ?>" class="form-control" required></div>
    <div class="col"><br>TEMPERATURA: <input type="text" name="temp" value="<?php echo $row['temp'] ?>" class="form-control" required></div>
  <div class="col">SATURACIÓN OXÍGENO: <input type="text" name="spo2" value="<?php echo $row['spo2'] ?>" class="form-control" required></div>
</div>
<hr>
<div class="row">
  <div class="col">MEDICACIÓN PREANESTÉSICA <input required type="text" name="med_pre"value="<?php echo $row['med_pre'] ?>" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    <input  type="text" name="med_pre2" class="form-control"value="<?php echo $row['med_pre2'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>

  <div class="col"><br>DOSIS <input required type="text" name="dosis"value="<?php echo $row['dosis'] ?>" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    <input type="text" name="dosis2" class="form-control"value="<?php echo $row['dosis2'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>

  <div class="col"><br>VÍA <input required type="text" name="via"value="<?php echo $row['via'] ?>" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">

    <input type="text" name="via2" class="form-control"value="<?php echo $row['via2'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>

  <div class="col"><br>FECHA <input required type="date" value="<?php echo $row['fechamedi'] ?>" name="fechamedi" class="form-control">
    <input type="date" name="fechamedi2" value="<?php echo $row['fechamedi2'] ?>" class="form-control">
  </div>
   <div class="col"><br>HORA <input required type="time"value="<?php echo $row['horamedi'] ?>" name="horamedi" class="form-control">
    <input type="time" name="horamedi2" value="<?php echo $row['horamedi2'] ?>" class="form-control">
  </div>
  <div class="col"><br>EFECTO <input required type="text"value="<?php echo $row['efect'] ?>" name="efect" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    <input type="text" name="efect2" class="form-control"value="<?php echo $row['efect2'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
</div>
<hr>
<div class="row">
  <div class="col"><h6><br><strong>VERIFICACIÓN DEL EQUIPO Y MONITOREO ANTES DE LA ANESTESIA</strong></div>
    <div class="col"><label>HORA:</label> <input type="time" name="hora_ver"value="<?php echo $row['hora_ver'] ?>" class="form-control-small col-4"></div>
  </div>
<hr>

<div class="row">
<?php if($row['apan']=="SI"){?>
<div class="col">
  <div class="form-check">
    <input class="form-check-input" type="checkbox" checked="" name="apan" id="apar" value="SI" >
    <label class="form-check-label" for="apar">
   APARATO ANESTESIA
  </label>
</div>
</div>
<?php }else{?>
<div class="col">
  <div class="form-check">
    <input class="form-check-input" type="checkbox" name="apan" id="apar" value="SI" >
    <label class="form-check-label" for="apar">
   APARATO ANESTESIA
  </label>
</div>
</div>
<?php } ?>

<?php if($row['circ']=="SI"){?>
    <div class="col"><div class="form-check">
  <input class="form-check-input" type="checkbox" checked="" name="circ" id="circuito" value="SI">
  <label class="form-check-label" for="circuito">
   CIRCUITO
  </label>
</div>
</div>
<?php }else{?>
    <div class="col"><div class="form-check">
  <input class="form-check-input" type="checkbox" name="circ" id="circuito" value="SI">
  <label class="form-check-label" for="circuito">
   CIRCUITO
  </label>
</div>
</div>
<?php } ?>

<?php if($row['fugas']=="SI"){?>
    <div class="col"><div class="form-check">
  <input class="form-check-input" type="checkbox" checked="" name="fugas" id="fugas" value="SI">
  <label class="form-check-label" for="fugas">
   FUGAS
  </label>
</div>
</div>
<?php }else{?>
    <div class="col"><div class="form-check">
  <input class="form-check-input" type="checkbox" name="fugas" id="fugas" value="SI">
  <label class="form-check-label" for="fugas">
   FUGAS
  </label>
</div>
</div>
<?php } ?>

<?php if($row['cal']=="SI"){?>
    <div class="col"><div class="form-check">
  <input class="form-check-input" type="checkbox" checked="" name="cal" id="cal" value="SI">
  <label class="form-check-label" for="cal">
   CAL SODADA
  </label>
</div>
</div>
<?php }else{?>
    <div class="col"><div class="form-check">
  <input class="form-check-input" type="checkbox" name="cal" id="cal" value="SI">
  <label class="form-check-label" for="cal">
   CAL SODADA
  </label>
</div>
</div>
<?php } ?>
</div>

<div class="row">
<?php if($row['vent']=="SI"){?>
    <div class="col"><div class="form-check">
  <input class="form-check-input" type="checkbox" checked="" name="vent" id="venti" value="SI">
  <label class="form-check-label" for="vent">
   VENTILADOR
  </label>
</div>
</div>
<?php }else{?>
    <div class="col"><div class="form-check">
  <input class="form-check-input" type="checkbox" name="vent" id="venti" value="SI">
  <label class="form-check-label" for="vent">
   VENTILADOR
  </label>
</div>
</div>
<?php } ?>

<?php if($row['para']=="SI"){?>
 <div class="col">
      <div class="form-check">
  <input class="form-check-input" type="checkbox" checked="" name="para" id="parame" value="SI">
  <label class="form-check-label" for="parame">
   PARÁMETROS VENTILATORIOS
  </label>
</div>
</div>
<?php }else{?>
 <div class="col">
      <div class="form-check">
  <input class="form-check-input" type="checkbox" name="para" id="parame" value="SI">
  <label class="form-check-label" for="parame">
   PARÁMETROS VENTILATORIOS
  </label>
</div>
</div>
<?php } ?>

<?php if($row['flujo']=="SI"){?>
    <div class="col"><div class="form-check">
  <input class="form-check-input" type="checkbox" checked="" name="flujo" id="flujo" value="SI">
  <label class="form-check-label" for="flujo">
   FLUJÓMETROS
  </label>
</div>
</div>
<?php }else{?>
    <div class="col"><div class="form-check">
  <input class="form-check-input" type="checkbox" name="flujo" id="flujo" value="SI">
  <label class="form-check-label" for="flujo">
   FLUJÓMETROS
  </label>
</div>
</div>
<?php } ?>

<?php if($row['vapo']=="SI"){?>
    <div class="col"><div class="form-check">
  <input class="form-check-input" type="checkbox" checked="" name="vapo" id="vap" value="SI">
  <label class="form-check-label" for="vap0">
   VAPORIZADORES
  </label>
</div>
</div>
<?php }else{?>
    <div class="col"><div class="form-check">
  <input class="form-check-input" type="checkbox" name="vapo" id="vap" value="SI">
  <label class="form-check-label" for="vap0">
   VAPORIZADORES
  </label>
</div>
</div>
<?php } ?>
</div>

<div class="row">
<?php if($row['fuen']=="SI"){?>
    <div class="col"><div class="form-check">
  <input class="form-check-input" type="checkbox" checked="" name="fuen" id="fuentea" value="SI">
  <label class="form-check-label" for="fuentea">
   FUENTE DE O2 Y ALARMAS
  </label>
</div>
</div>
<?php }else{?>
    <div class="col"><div class="form-check">
  <input class="form-check-input" type="checkbox" name="fuen" id="fuentea" value="SI">
  <label class="form-check-label" for="fuentea">
   FUENTE DE O2 Y ALARMAS
  </label>
</div>
</div>
<?php } ?>

<?php if($row['fuent']=="SI"){?>
    <div class="col-9">
      <div class="form-check">
  <input class="form-check-input" type="checkbox" checked="" name="fuent" id="fuentee" value="SI">
  <label class="form-check-label" for="fuentee">
   FUENTE DE ENERGÍA Y ALARMAS
  </label>
</div>
</div>
<?php }else{?>
    <div class="col-9">
      <div class="form-check">
  <input class="form-check-input" type="checkbox" name="fuent" id="fuentee" value="SI">
  <label class="form-check-label" for="fuentee">
   FUENTE DE ENERGÍA Y ALARMAS
  </label>
</div>
</div>
<?php } ?>
</div>

<div class="row">
<?php if($row['ecg']=="SI"){?>
    <div class="col"><div class="form-check">
  <input class="form-check-input" type="checkbox" checked="" name="ecg" id="ecg" value="SI">
  <label class="form-check-label" for="ecg">
   ECG
  </label>
</div>
</div>
<?php }else{?>
    <div class="col"><div class="form-check">
  <input class="form-check-input" type="checkbox" name="ecg" id="ecg" value="SI">
  <label class="form-check-label" for="ecg">
   ECG
  </label>
</div>
</div>
<?php } ?>

<?php if($row['pani']=="SI"){?>
    <div class="col">
      <div class="form-check">
  <input class="form-check-input" type="checkbox" checked="" name="pani" id="pani" value="SI">
  <label class="form-check-label" for="pani">
   PANI
  </label>
</div>
</div>
<?php }else{?>
    <div class="col">
      <div class="form-check">
  <input class="form-check-input" type="checkbox" name="pani" id="pani" value="SI">
  <label class="form-check-label" for="pani">
   PANI
  </label>
</div>
</div>
<?php } ?>

<?php if($row['spo']=="SI"){?>
<div class="col">
      <div class="form-check">
  <input class="form-check-input" type="checkbox" checked="" name="spo" id="sp" value="SI">
  <label class="form-check-label" for="sp">
   SP O2
  </label>
</div>
</div>
<?php }else{?>
<div class="col">
      <div class="form-check">
  <input class="form-check-input" type="checkbox" name="spo" id="sp" value="SI">
  <label class="form-check-label" for="sp">
   SP O2
  </label>
</div>
</div>
<?php } ?>

<?php if($row['co2']=="SI"){?>
<div class="col">
      <div class="form-check">
  <input class="form-check-input" type="checkbox" checked="" name="co2" id="co" value="SI">
  <label class="form-check-label" for="co">
   CO2FE
  </label>
</div>
</div>
<?php }else{?>
<div class="col">
      <div class="form-check">
  <input class="form-check-input" type="checkbox" name="co2" id="co" value="SI">
  <label class="form-check-label" for="co">
   CO2FE
  </label>
</div>
</div>
<?php } ?>

</div><p></p>
<strong>OPCIONALES:</strong>
<div class="row">
<?php if($row['ana']=="SI"){?>
    <div class="col"><div class="form-check">
  <input class="form-check-input" type="checkbox" checked="" name="ana" id="ana" value="SI">
  <label class="form-check-label" for="ana">
   ANALIZADOR DE GASES RESP
  </label>
</div>
</div>
<?php }else{?>
    <div class="col"><div class="form-check">
  <input class="form-check-input" type="checkbox" name="ana" id="ana" value="SI">
  <label class="form-check-label" for="ana">
   ANALIZADOR DE GASES RESP
  </label>
</div>
</div>
<?php } ?>

<?php if($row['indice']=="SI"){?>
    <div class="col">
      <div class="form-check">
  <input class="form-check-input" type="checkbox" checked="" name="indice" id="indi" value="SI">
  <label class="form-check-label" for="indi">
   ÍNDICE BIESPECTRAL
  </label>
</div>
</div>
<?php }else{?>
    <div class="col">
      <div class="form-check">
  <input class="form-check-input" type="checkbox" name="indice" id="indi" value="SI">
  <label class="form-check-label" for="indi">
   ÍNDICE BIESPECTRAL
  </label>
</div>
</div>
<?php } ?>

<?php if($row['bomba']=="SI"){?>
<div class="col">
      <div class="form-check">
  <input class="form-check-input" type="checkbox" checked="" name="bomba" id="bomba" value="SI">
  <label class="form-check-label" for="bomba">
   BOMBA DE INFUSIÓN
  </label>
</div>
</div>
<?php }else{?>
<div class="col">
      <div class="form-check">
  <input class="form-check-input" type="checkbox" name="bomba" id="bomba" value="SI">
  <label class="form-check-label" for="bomba">
   BOMBA DE INFUSIÓN
  </label>
</div>
</div>
<?php } ?>

<?php if($row['moni']=="SI"){?>
<div class="col">
      <div class="form-check">
  <input class="form-check-input" type="checkbox" checked="" name="moni" id="mon" value="SI">
  <label class="form-check-label" for="mon">
   MONITOR DE RELAJACIÓN
  </label>
</div>
</div>
<?php }else{?>
<div class="col">
      <div class="form-check">
  <input class="form-check-input" type="checkbox" name="moni" id="mon" value="SI">
  <label class="form-check-label" for="mon">
   MONITOR DE RELAJACIÓN
  </label>
</div>
</div>
<?php } ?>

</div><br>
<label for="exampleFormControlTextarea1">OBSERVACIONES:</label>
<input type="text" name="obser" class="form-control" required="" value="<?php echo $row['obser'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    <br>
    
  <hr>

 <center>

                       <div class="form-group col-6">
                            <button type="submit" name="guardar" class="btn btn-primary">FIRMAR</button>
                            <button type="button" class="btn btn-danger" onclick="history.back()">CANCELAR</button>
                        </div>
</center>
                        <br>
                        </form>
</div> <!--TERMINO DE NOTA MEDICA div container-->
<?php } ?>
   </div>
<?php 
  if (isset($_POST['guardar'])) {

        $fecha2    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha2"], ENT_QUOTES))); 
        $hora2    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hora2"], ENT_QUOTES))); 
        $diaproc   = mysqli_real_escape_string($conexion, (strip_tags($_POST["diaproc"], ENT_QUOTES)));
        $sist    = mysqli_real_escape_string($conexion, (strip_tags($_POST["sist"], ENT_QUOTES))); 
        $diast    = mysqli_real_escape_string($conexion, (strip_tags($_POST["diast"], ENT_QUOTES)));
        $freccard    = mysqli_real_escape_string($conexion, (strip_tags($_POST["freccard"], ENT_QUOTES)));
        $frecresp    = mysqli_real_escape_string($conexion, (strip_tags($_POST["frecresp"], ENT_QUOTES)));
        $temp    = mysqli_real_escape_string($conexion, (strip_tags($_POST["temp"], ENT_QUOTES)));
        $spo2    = mysqli_real_escape_string($conexion, (strip_tags($_POST["spo2"], ENT_QUOTES)));
        $med_pre      = mysqli_real_escape_string($conexion, (strip_tags($_POST["med_pre"], ENT_QUOTES)));
        $dosis    = mysqli_real_escape_string($conexion, (strip_tags($_POST["dosis"], ENT_QUOTES))); 

        $via    = mysqli_real_escape_string($conexion, (strip_tags($_POST["via"], ENT_QUOTES))); 
        $fechamedi    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fechamedi"], ENT_QUOTES))); 
        $horamedi   = mysqli_real_escape_string($conexion, (strip_tags($_POST["horamedi"], ENT_QUOTES)));
        $efect    = mysqli_real_escape_string($conexion, (strip_tags($_POST["efect"], ENT_QUOTES))); 
        $med_pre2    = mysqli_real_escape_string($conexion, (strip_tags($_POST["med_pre2"], ENT_QUOTES)));
        $dosis2    = mysqli_real_escape_string($conexion, (strip_tags($_POST["dosis2"], ENT_QUOTES)));
        $via2    = mysqli_real_escape_string($conexion, (strip_tags($_POST["via2"], ENT_QUOTES)));
        $fechamedi2    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fechamedi2"], ENT_QUOTES)));
        $horamedi2    = mysqli_real_escape_string($conexion, (strip_tags($_POST["horamedi2"], ENT_QUOTES)));
        $efect2      = mysqli_real_escape_string($conexion, (strip_tags($_POST["efect2"], ENT_QUOTES)));
        $hora_ver    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hora_ver"], ENT_QUOTES)));

        $apan    = mysqli_real_escape_string($conexion, (strip_tags($_POST["apan"], ENT_QUOTES))); 
        $vent    = mysqli_real_escape_string($conexion, (strip_tags($_POST["vent"], ENT_QUOTES))); 
        $fuen   = mysqli_real_escape_string($conexion, (strip_tags($_POST["fuen"], ENT_QUOTES)));
        $ecg    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ecg"], ENT_QUOTES))); 
        $circ    = mysqli_real_escape_string($conexion, (strip_tags($_POST["circ"], ENT_QUOTES)));
        $para    = mysqli_real_escape_string($conexion, (strip_tags($_POST["para"], ENT_QUOTES)));
        $fuent    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fuent"], ENT_QUOTES)));
        $pani    = mysqli_real_escape_string($conexion, (strip_tags($_POST["pani"], ENT_QUOTES)));
        $fugas    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fugas"], ENT_QUOTES)));
        $flujo      = mysqli_real_escape_string($conexion, (strip_tags($_POST["flujo"], ENT_QUOTES)));
        $spo    = mysqli_real_escape_string($conexion, (strip_tags($_POST["spo"], ENT_QUOTES)));

        $cal    = mysqli_real_escape_string($conexion, (strip_tags($_POST["cal"], ENT_QUOTES))); 
        $vapo    = mysqli_real_escape_string($conexion, (strip_tags($_POST["vapo"], ENT_QUOTES))); 
        $co2   = mysqli_real_escape_string($conexion, (strip_tags($_POST["co2"], ENT_QUOTES)));
        $ana    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ana"], ENT_QUOTES))); 
        $indice    = mysqli_real_escape_string($conexion, (strip_tags($_POST["indice"], ENT_QUOTES)));
        $bomba    = mysqli_real_escape_string($conexion, (strip_tags($_POST["bomba"], ENT_QUOTES)));
        $moni    = mysqli_real_escape_string($conexion, (strip_tags($_POST["moni"], ENT_QUOTES)));
        $obser    = mysqli_real_escape_string($conexion, (strip_tags($_POST["obser"], ENT_QUOTES)));

if (isset($_POST['dosis2'])) {$dosis2=$_POST['dosis2'];}else{$dosis2='NO';}

if (isset($_POST['via2'])) {$via2=$_POST['via2'];}else{$via2='NO';}

if (isset($_POST['fechamedi2'])) {$fechamedi2=$_POST['fechamedi2'];}else{$fechamedi2='';}

if (isset($_POST['horamedi2'])) {$horamedi2=$_POST['horamedi2'];}else{$horamedi2='';}

if (isset($_POST['efect2'])) {$efect2=$_POST['efect2'];}else{$efect2='';}

if(isset($_POST['apan'])){$apan=$_POST['apan'];}else{$apan='NO';}

if(isset($_POST['vent'])){$vent=$_POST['vent'];}else{$vent='NO';}

if(isset($_POST['fuen'])){$fuen=$_POST['fuen'];}else{$fuen='NO';}

if(isset($_POST['ecg'])){$ecg=$_POST['ecg'];}else{$ecg='NO';}

if(isset($_POST['circ'])){$circ=$_POST['circ'];}else{$circ='NO';}

if(isset($_POST['para'])){$para=$_POST['para'];}else{$para='NO';}

if(isset($_POST['fuent'])){$fuent=$_POST['fuent'];}else{$fuent='NO';}

if(isset($_POST['pani'])){$pani=$_POST['pani'];}else{$pani='NO';}

if(isset($_POST['fugas'])){$fugas=$_POST['fugas'];}else{$fugas='NO';}

if(isset($_POST['flujo'])){$flujo=$_POST['flujo'];}else{$flujo='NO';}

if(isset($_POST['spo'])){$spo=$_POST['spo'];}else{$spo='NO';}

if(isset($_POST['cal'])){$cal=$_POST['cal'];}else{$cal='NO';}

if(isset($_POST['vapo'])){$vapo=$_POST['vapo'];}else{$vapo='NO';}

if(isset($_POST['co2'])){$co2=$_POST['co2'];}else{$co2='NO';}

if(isset($_POST['ana'])){$ana=$_POST['ana'];}else{$ana='NO';}

if(isset($_POST['indice'])){$indice=$_POST['indice'];}else{$indice='NO';}

if(isset($_POST['bomba'])){$bomba=$_POST['bomba'];}else{$bomba='NO';}

if(isset($_POST['moni'])){$moni=$_POST['moni'];}else{$moni='NO';}
$id_atencion    = mysqli_real_escape_string($conexion, (strip_tags($_GET["id_atencion"], ENT_QUOTES)));

        
       
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

        $sql2 = "UPDATE dat_seg_evol_amb SET fecha2='$fecha2', hora2='$hora2', diaproc='$diaproc', sist='$sist', diast='$diast', freccard='$freccard' , frecresp='$frecresp', temp='$temp', spo2='$spo2' , med_pre  ='$med_pre', dosis  ='$dosis', via='$via', fechamedi='$fechamedi', horamedi='$horamedi', efect='$efect', med_pre2='$med_pre2', dosis2='$dosis2' , via2='$via2', fechamedi2='$fechamedi2', horamedi2='$horamedi2' , efect2  ='$efect2', hora_ver  ='$hora_ver', apan='$apan', vent='$vent', fuen='$fuen', ecg='$ecg', circ='$circ', para='$para' , fuent='$fuent', pani='$pani', fugas='$fugas' , flujo  ='$flujo', spo  ='$spo', cal='$cal', vapo='$vapo', co2='$co2', ana='$ana', indice='$indice', bomba='$bomba' , moni='$moni', obser='$obser' WHERE id_seg_evol_amb=$id_seg_evol_amb";
        $result = $conexion->query($sql2);
echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> DATO EDITADO CORRECTAMENTE...</p>";
        echo '<script type="text/javascript">window.location ="../documentos_ambulatorio/consent_medico.php?id_atencion='.$id_atencion.'"</script>';
      }
  ?>
</div>
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


<script type="text/javascript">
    $(document).ready(function () {
        $('#mibuscador').select2();
    });
</script>


</body>
</html>