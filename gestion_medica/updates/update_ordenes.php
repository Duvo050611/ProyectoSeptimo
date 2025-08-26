<?php
session_start();


include "../../conexionbd.php";
include("../header_medico.php");


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

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css"
          type="text/css"/>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js"></script>


    <title>DATOS NURGEN </title>
</head>
<body>

<div class="col-sm-12">
    <div class="container">
        <div class="row">
            <div class="col">
                 <div class="thead" style="background-color: #0c675e; color: white; font-size: 24px;">
  <center><strong>ÓRDENES DEL MÉDICO (INDICACIONES)</strong></center><p>
</div>
             
                <hr>
                <?php

                include "../../conexionbd.php";

                $resultado1 = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE id_atencion=" . $_SESSION['hospital']) or die($conexion->error);

                ?>
                <?php
                while ($f1 = mysqli_fetch_array($resultado1)) {

                    ?>

                    <div class="row">


                        <div class="col-sm"> FECHA:
                            <?php
                            date_default_timezone_set('America/Guatemala');
                            $fecha_actual = date("d-m-Y");
                            ?>
                            <strong><?= $fecha_actual ?></strong>

                        </div>

                        <div class="col-sm">
                            FECHA DE NACIMIENTO:
                            <td><strong><?php echo $f1['fecnac']; ?></strong></td>
                        </div>


                    </div>


                    <!--<div class="row">
    <div class="col-sm-5">
        NO.EXPEDIENTE: <td><strong><?php //echo $f1['Id_exp']; ?></strong></td>
    </div>
    <div class="col-sm">
     ADMISIÓN: <td><strong><?php //echo $f1['id_atencion']; ?></strong></td>
    </div>

  </div>-->


                    <div class="row">
                        <div class="col-sm">
                            PACIENTE:
                            <td><strong><?php echo $f1['papell']; ?></strong></td>
                            <td><strong><?php echo $f1['sapell']; ?></strong></td>
                            <td><strong><?php echo $f1['nom_pac']; ?></strong></td>
                        </div>
                        <div class="col-sm">
                            EDAD:
                            <td><strong><?php echo $f1['edad']; ?></strong></td>
                        </div>

                        <!--<div class="col-sm">
       HABITACIÓN:
                            <td><strong><?php //echo $f1['edad']; ?></strong></td>
    </div>-->
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            DIAGNÓSTICO:
                            <td><strong><?php echo $f1['motivo_atn']; ?></strong></td>
                        </div>
                         <div class="col-sm">
                            TIPO DE SANGRE:
                            <td><strong><?php echo $f1['tip_san']; ?></strong></td>
                        </div>
                    </div>
                    <hr>
                    <?php
                }
                ?>

            </div>
            <div class="col-3">
                <?php
                date_default_timezone_set('America/Guatemala');
                $fecha_actual = date("Y-m-d H:i:s");
                ?>
                
                <div class="form-group">
                    <label for="fecha">FECHA Y HORA ACTUAL:</label>
                    <input type="datetime" name="fec_hc" value="<?= $fecha_actual ?>" class="form-control" disabled>
                </div>
            </div>
        </div>

 
 
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
    <div class="thead" style="background-color: #0c675e; color: white; font-size: 19px;">
  <center><strong>SIGNOS VITALES</strong></center><p>
</div>
                <div class="container"> 
  <div class="row">
    
      
    
    <div class="col-sm-2"><br><center>PRESIÓN ARTERIAL:</center>
     <div class="row">
  <div class="col"><input type="text" class="form-control" value="<?php echo $f5['p_sistol'];?>" disabled></div> /
  <div class="col"><input type="text" class="form-control" value="<?php echo $f5['p_diastol'];?>" disabled></div>
 
</div>
    </div>
    <div class="col-sm-2">
      FRECUENCIA CARDIACA:<input type="text" class="form-control" value="<?php echo $f5['fcard'];?>" disabled>
    </div>
    <div class="col-sm-2">
      FRECUENCIA RESPIRATORIA:<input type="text" class="form-control" value="<?php echo $f5['fresp'];?>" disabled>
    </div>
    <div class="col-sm-2">
     <br>TEMPERATURA:<input type="text" class="form-control" value="<?php echo $f5['temper'];?>" disabled>
    </div>
    <div class="col-sm-2">
     SATURACIÓN OXÍGENO:<input type="text"  class="form-control" value="<?php echo $f5['satoxi'];?>" disabled>
    </div>
    <div class="col-sm-1">
     <br>PESO:<input type="text" class="form-control" value="<?php echo $f5['peso'];?>" disabled>
    </div>
    <div class="col-sm-1">
     <br>TALLA:<input type="text" class="form-control" value="<?php echo $f5['talla'];?>" disabled>
    </div>
  </div>
</div>
<?php }
?>
<?php 
}else{
                        
  ?>
   <div class="thead" style="background-color: #0c675e; color: white; font-size: 19px;">
  <center><strong>SIGNOS VITALES</strong></center><p>
</div>
                <div class="container"> 
  <div class="row">
    <div class="col-sm-2"><br><center>PRESIÓN ARTERIAL:</center>
     <div class="row">
  <div class="col"><input type="text" class="form-control" name="p_sistol" ></div> /
  <div class="col"><input type="text" class="form-control" name="p_diastol"></div>
 
</div>
    </div>
    <div class="col-sm-2">
      FRECUENCIA CARDIACA:<input type="text" class="form-control" name="fcard">
    </div>
    <div class="col-sm-2">
      FRECUENCIA RESPIRATORIA:<input type="text" class="form-control" name="fresp">
    </div>
    <div class="col-sm-2">
     <br>TEMPERATURA:<input type="text" class="form-control"  name="temper">
    </div>
    <div class="col-sm-2">
     SATURACIÓN OXÍGENO:<input type="text"  class="form-control" name="satoxi">
    </div>
    <div class="col-sm-1">
     <br>PESO:<input type="text" class="form-control"  name="peso">
    </div>
    <div class="col-sm-1">
     <br>TALLA:<input type="text" class="form-control" name="talla" >
    </div>
  </div>
</div>

<?php } ?>
<hr>
<!--<?php
                            //date_default_timezone_set('America/Guatemala');
                            //$fecha_actual2 = date("d-m-Y H:i:s");
                            ?>

            <div class="row">
                <div class="col-sm-3">
                    FECHA Y HORA: <input class="form-control" disabled value="<?php// $fecha_actual2 ?>*/" >
                </div>
                <div class="col-sm-3">
                    HORA: <input type="time" name="hora_ord" class="form-control">
                </div>
            </div>-->
   <?php 
$id_ord=$_GET['id_ord'];
$tras="SELECT * FROM dat_ordenes_med where id_ord_med=$id_ord";
$result=$conexion->query($tras);
while ($row=$result->fetch_assoc()) {
 ?>            
<div class="thead" style="background-color: #0c675e; color: white; font-size: 24px;">
  <center><strong>PRESCRIPCIÓN Y ÓRDENES: PREPARACIÓN DE CASOS QUIRÚRGICOS, DIETAS, ETC.</strong></center><p>
</div>
            <div class="row">
                <div class="col-2">
                    <center><p><p><strong><label for="exampleFormControlTextarea1"><br><font size="3" color="#407959">1.-DIETA:</font></label></strong></center>
                </div>
                       <div class="col-sm-5">
                    <div class="form-group">
                        <label for="dieta"><strong>DIETA:</strong></label>
                        <select class="form-control" name="dieta" required id="dieta">
                            <option value="<?php echo $row['dieta'] ?>"><?php echo $row['dieta'] ?></option>
                            <option></option>
                             <option value="" disabled="">SELECCIONAR DIETA</option>
                            <option value="NINGUNO">NINGUNO</option>
                            <?php
                            $sql_d = "SELECT DISTINCT id_dieta,dieta FROM cat_dietas WHERE dieta_activo='SI'";
                            $result_d = $conexion->query($sql_d);
                            while ($row_d = $result_d->fetch_assoc()) {
                                echo "<option value='" . $row_d['dieta'] . "'>" . $row_d['dieta'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            
<strong><label for="exampleFormControlTextarea1"><br><font size="3" color="#407959">2.-
                                    CUIDADOS GENERALES:</font></label></strong>

  <div class="row">
                
                <div class=" col-12">
                    <div class="form-group">
                        <input type="text" name="observ_be" value="<?php echo $row['observ_be'] ?>" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                    </div>
                </div>

            </div>

<div class="container">
    <div class="row">
        <div class="col-sm-6">
        </div>
        <div class="col-sm-6">
        <strong><font color="#407959"><center>DETALLE DE INDICACIONES</center></font></strong><p>
        </div>
    </div>
</div>
 

            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#407959">SIGNOS VITALES POR TURNO</font></strong>
                    </div>
                    <?php if ($row['signos'] == "SI") {?>
                    <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="signos" id="signos" value="SI" checked>
                            <label class="form-check-label" for="signos">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="signos" id="signos2" value="NO" >
                            <label class="form-check-label" for="signos2">
                                NO
                            </label>
                        </div>
                    </div>
                <?php }elseif($row['signos'] == "NO") { ?>
                      <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="signos" id="signos" value="SI">
                            <label class="form-check-label" for="signos">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="signos" id="signos2" value="NO" checked>
                            <label class="form-check-label" for="signos2">
                                NO
                            </label>
                        </div>
                    </div>
                <?php } ?>
                     <div class="col-sm-6">
     <input type="text" name="detsignos" value="<?php echo $row['detsignos'] ?>" class="form-control" style="text-transform:uppercase;"
                                  onkeyup="javascript:this.value=this.value.toUpperCase();">
                </div>
            </div>
        </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#407959">MONITOREO CONSTANTE</font></strong>
                    </div>
                     <?php if ($row['monitoreo'] == "SI") {?>
                    <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="monitoreo" id="monitoreo" value="SI" checked>
                            <label class="form-check-label" for="monitoreo">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="monitoreo" id="monitoreo2" value="NO" >
                            <label class="form-check-label" for="monitoreo2">
                                NO
                            </label>
                        </div>
                    </div>
                <?php }elseif($row['monitoreo'] == "NO") { ?>
                      <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="monitoreo" value="SI">
                            <label class="form-check-label" for="monitoreo">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="monitoreo" value="NO" checked>
                            <label class="form-check-label" for="signos2">
                                NO
                            </label>
                        </div>
                    </div>
                <?php } ?>
                     <div class="col-sm-6">
     <input type="text" name="detmonitoreo" class="form-control" value="<?php echo $row['detmonitoreo'] ?>" style="text-transform:uppercase;"
                                  onkeyup="javascript:this.value=this.value.toUpperCase();">
                </div>
            </div>
        </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#407959">DIURESIS POR TURNO</font></strong>
                    </div>
                     <?php if ($row['diuresis'] == "SI") {?>
                    <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="diuresis" id="diuresis" value="SI" checked>
                            <label class="form-check-label" for="diuresis">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="diuresis" id="diuresis2" value="NO" >
                            <label class="form-check-label" for="diuresis2">
                                NO
                            </label>
                        </div>
                    </div>
                <?php }elseif($row['diuresis'] == "NO") { ?>
                      <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="diuresis" value="SI">
                            <label class="form-check-label" for="diuresis">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="diuresis" value="NO" checked>
                            <label class="form-check-label" for="diuresis">
                                NO
                            </label>
                        </div>
                    </div>
                <?php } ?>
                     <div class="col-sm-6">
     <input type="text" name="detdiuresis" class="form-control" value="<?php echo $row['detdiuresis'] ?>" style="text-transform:uppercase;"
                                  onkeyup="javascript:this.value=this.value.toUpperCase();">
                </div>
            </div>
        </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#407959"> DEXTROSTIX POR TURNO</font></strong>
                    </div>
                    <?php if ($row['dex'] == "SI") {?>
                    <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="dex" id="dex" value="SI" checked>
                            <label class="form-check-label" for="dex">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="dex" id="dex2" value="NO" >
                            <label class="form-check-label" for="dex2">
                                NO
                            </label>
                        </div>
                    </div>
                <?php }elseif($row['dex'] == "NO") { ?>
                      <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="dex" value="SI">
                            <label class="form-check-label" for="dex">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="dex" value="NO" checked>
                            <label class="form-check-label" for="dex">
                                NO
                            </label>
                        </div>
                    </div>
                <?php } ?>
                     <div class="col-sm-6">
     <input type="text" name="detdex" class="form-control" value="<?php echo $row['detdex'] ?>" style="text-transform:uppercase;"
                                  onkeyup="javascript:this.value=this.value.toUpperCase();">
                </div>
            </div>
        </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#407959">POSICIÓN SEMIFLOWER</font></strong>
                    </div>
                    <?php if ($row['semif'] == "SI") {?>
                    <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="semif" id="semif" value="SI" checked>
                            <label class="form-check-label" for="semif">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="semif" id="semif2" value="NO" >
                            <label class="form-check-label" for="semif2">
                                NO
                            </label>
                        </div>
                    </div>
                <?php }elseif($row['semif'] == "NO") { ?>
                      <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="semif" value="SI">
                            <label class="form-check-label" for="semif">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="semif" value="NO" checked>
                            <label class="form-check-label" for="semif">
                                NO
                            </label>
                        </div>
                    </div>
                <?php } ?>
                     <div class="col-sm-6">
     <input type="text" name="detsemif" class="form-control" value="<?php echo $row['detsemif'] ?>" style="text-transform:uppercase;"
                                  onkeyup="javascript:this.value=this.value.toUpperCase();">
                </div>
            </div>
        </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#407959">VIGILAR DATOS DEL PACIENTE NEUROLÓGICO</font></strong>
                    </div>
                    <?php if ($row['vigilar'] == "SI") {?>
                    <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="vigilar" id="vigilar" value="SI" checked>
                            <label class="form-check-label" for="vigilar">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="vigilar" id="vigilar2" value="NO" >
                            <label class="form-check-label" for="vigilar2">
                                NO
                            </label>
                        </div>
                    </div>
                <?php }elseif($row['vigilar'] == "NO") { ?>
                      <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="vigilar" value="SI">
                            <label class="form-check-label" for="vigilar">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="vigilar" value="NO" checked>
                            <label class="form-check-label" for="vigilar">
                                NO
                            </label>
                        </div>
                    </div>
                <?php } ?>
                     <div class="col-sm-6">
     <input type="text" name="detvigilar" class="form-control" value="<?php echo $row['detvigilar'] ?>" style="text-transform:uppercase;"
                                  onkeyup="javascript:this.value=this.value.toUpperCase();">
                </div>
            </div>
        </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#407959">OXÍGENO</font></strong>
                    </div>
                    <?php if ($row['oxigeno'] == "SI") {?>
                    <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="oxigeno" id="oxigeno" value="SI" checked>
                            <label class="form-check-label" for="oxigeno">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="oxigeno" id="oxigeno2" value="NO" >
                            <label class="form-check-label" for="oxigeno2">
                                NO
                            </label>
                        </div>
                    </div>
                <?php }elseif($row['oxigeno'] == "NO") { ?>
                      <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="oxigeno" value="SI">
                            <label class="form-check-label" for="oxigeno">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="oxigeno" value="NO" checked>
                            <label class="form-check-label" for="oxigeno">
                                NO
                            </label>
                        </div>
                    </div>
                <?php } ?>
                     <div class="col-sm-6">
                     <input type="text" name="detoxigeno" class="form-control" value="<?php echo $row['detoxigeno'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                     </div>
            </div>
        </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#407959">NEBULIZACIONES</font></strong>
                    </div>
                    <?php if ($row['nebulizacion'] == "SI") {?>
                    <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nebu" id="nebu" value="SI" checked>
                            <label class="form-check-label" for="nebu">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nebu" id="nebu2" value="NO" >
                            <label class="form-check-label" for="nebu2">
                                NO
                            </label>
                        </div>
                    </div>
                <?php }elseif($row['nebulizacion'] == "NO") { ?>
                      <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nebu" value="SI">
                            <label class="form-check-label" for="nebu">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nebu" value="NO" checked>
                            <label class="form-check-label" for="nebu">
                                NO
                            </label>
                        </div>
                    </div>
                <?php } ?>
                     <div class="col-sm-6">
     <input type="text" name="detnebu" class="form-control" value="<?php echo $row['detnebu'] ?>" style="text-transform:uppercase;"
                                  onkeyup="javascript:this.value=this.value.toUpperCase();">
                </div>
            </div>
            </div>
<div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#407959">BARANDALES EN ALTO</font></strong>
                    </div>
                    <?php if ($row['bar'] == "SI") {?>
                    <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bar" id="bar" value="SI" checked>
                            <label class="form-check-label" for="bar">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bar" id="bar2" value="NO" >
                            <label class="form-check-label" for="bar2">
                                NO
                            </label>
                        </div>
                    </div>
                <?php }elseif($row['bar'] == "NO") { ?>
                      <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bar" value="SI">
                            <label class="form-check-label" for="bar">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bar" value="NO" checked>
                            <label class="form-check-label" for="bar">
                                NO
                            </label>
                        </div>
                    </div>
                <?php } ?>
                     <div class="col-sm-6">
     <input type="text" name="detbar" class="form-control" value="<?php echo $row['detbar'] ?>" style="text-transform:uppercase;"
                                  onkeyup="javascript:this.value=this.value.toUpperCase();">
                </div>
            </div>
        </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#407959">BAÑO DIARIO Y DEAMBULACIÓN ASISTIDA</font></strong>
                    </div>
                    <?php if ($row['baño'] == "SI") {?>
                    <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="baño" id="baño" value="SI" checked>
                            <label class="form-check-label" for="baño">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="baño" id="baño2" value="NO" >
                            <label class="form-check-label" for="baño2">
                                NO
                            </label>
                        </div>
                    </div>
                <?php }elseif($row['baño'] == "NO") { ?>
                      <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="baño" value="SI">
                            <label class="form-check-label" for="baño">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="baño" value="NO" checked>
                            <label class="form-check-label" for="baño">
                                NO
                            </label>
                        </div>
                    </div>
                <?php } ?>
                     <div class="col-sm-6">
     <input type="text" name="detbaño" class="form-control" value="<?php echo $row['detbaño'] ?>" style="text-transform:uppercase;"
                                  onkeyup="javascript:this.value=this.value.toUpperCase();">
                </div>
            </div>
        </div>
<div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#407959">CUIDADOS DE SONDA FOLEY</font></strong>
                    </div>
                     <?php if ($row['foley'] == "SI") {?>
                    <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="foley" id="foley" value="SI" checked>
                            <label class="form-check-label" for="foley">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="foley" id="foley2" value="NO" >
                            <label class="form-check-label" for="foley2">
                                NO
                            </label>
                        </div>
                    </div>
                <?php }elseif($row['foley'] == "NO") { ?>
                      <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="foley" value="SI">
                            <label class="form-check-label" for="foley">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="foley" value="NO" checked>
                            <label class="form-check-label" for="foley">
                                NO
                            </label>
                        </div>
                    </div>
                <?php } ?>
                     <div class="col-sm-6">
     <input type="text" name="detfoley" class="form-control" value="<?php echo $row['detfoley'] ?>" style="text-transform:uppercase;"
                                  onkeyup="javascript:this.value=this.value.toUpperCase();">
                </div>
            </div>
        </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#407959">EJERCICIOS RESPIRATORIOS</font></strong>
                    </div>
                    <?php if ($row['ej'] == "SI") {?>
                    <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="ej" id="ej" value="SI" checked>
                            <label class="form-check-label" for="ej">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="ej" id="ej2" value="NO" >
                            <label class="form-check-label" for="ej2">
                                NO
                            </label>
                        </div>
                    </div>
                <?php }elseif($row['ej'] == "NO") { ?>
                      <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="ej" value="SI">
                            <label class="form-check-label" for="ej">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="ej" value="NO" checked>
                            <label class="form-check-label" for="ej">
                                NO
                            </label>
                        </div>
                    </div>
                <?php } ?>
                     <div class="col-sm-6">
     <input type="text" name="detej" class="form-control" value="<?php echo $row['detej'] ?>" style="text-transform:uppercase;"
                                  onkeyup="javascript:this.value=this.value.toUpperCase();">
                </div>
                  
    </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><font color="#407959">VIGILAR DATOS DE SANGRADO</font></strong>
                    </div>
                    <?php if ($row['datsan'] == "SI") {?>
                    <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="datsan" id="datsan" value="SI" checked>
                            <label class="form-check-label" for="datsan">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="datsan" id="datsan2" value="NO" >
                            <label class="form-check-label" for="datsan2">
                                NO
                            </label>
                        </div>
                    </div>
                <?php }elseif($row['datsan'] == "NO") { ?>
                      <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="datsan" value="SI">
                            <label class="form-check-label" for="datsan">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="datsan" value="NO" checked>
                            <label class="form-check-label" for="datsan">
                                NO
                            </label>
                        </div>
                    </div>
                <?php } ?>
                    <div class="col-sm-6">
     <input type="text" name="detsan" class="form-control" value="<?php echo $row['detsan'] ?>" style="text-transform:uppercase;"
                                  onkeyup="javascript:this.value=this.value.toUpperCase();">
    </div>
                </div>
            </div>
<br>


            <div class="row">
                <div class="col-2">
                    <center><strong><label for="exampleFormControlTextarea1"><br><font size="3" color="#407959">3.-
                                    INHALOTERAPIA:</font></label></strong></center>
                </div>
                <div class=" col-10">

                    <div class="form-group">
                     <textarea name="cuid_gen" class="form-control" rows="3"><?php echo $row['cuid_gen'] ?></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <center><strong><label for="exampleFormControlTextarea1"><br><font size="3" color="#407959">4.-
                                    MEDICAMENTOS:</font></label></strong></center>
                </div>
                <div class=" col-10">

                    <div class="form-group">
                        <textarea name="med_med" class="form-control" rows="3"><?php echo $row['med_med'] ?></textarea>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-2">
                    <center><strong><label for="exampleFormControlTextarea1"><br><font size="3" color="#407959">5.-
                                    SOLUCIONES:</font></label></strong></center>
                </div>
                <div class=" col-10">
                    <div class="form-group">
                        <textarea name="soluciones" class="form-control"><?php echo $row['soluciones'] ?></textarea>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-sm-2">
                    <center><strong><label for="exampleFormControlTextarea1"><br><font size="3" color="#407959">6.-
                                    SOLICITUD DE ESTUDIOS LABORATORIO:</font></label></strong></center>
                </div>
                <div class="col-sm-5">
                    <div class="form-group"><br><br>
                       <p><?php echo $row['perfillab'] ?></p>
                    </div>
                </div>
                <div class="col-sm-5">DETALLE DE ESTUDIOS DE LABORATORIO<br>
<textarea class="form-control" name="detalle_lab" rows="5"><?php echo $row['detalle_lab']?></textarea>
 </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <center><strong><label for="exampleFormControlTextarea1"><br><font size="3" color="#407959">7.-
                                    SOLICITUD DE ESTUDIOS IMAGENOLOGIA:</font></label></strong></center>
                </div>
                <div class="col-sm-5">

                    <div class="form-group">
                        <label for="sol_imagen"><strong>SOLICITUD DE ESTUDIOS IMAGENOLOGIA:</strong></label><br><br>
                        <p><?php echo $row['sol_estudios']?></p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2">
                    <center><strong><label for="exampleFormControlTextarea1"><br><font size="3" color="#407959">8.-
                                    SOLICITUD DE TRANSFUSIÓN SANGUINEA:</font></label></strong></center>
                </div>
                <div class="col-sm-5">

                    <div class="form-group">
                        <label for="sol_imagen"><strong> SOLICITUD DE TRANSFUSIÓN SANGUINEA:</strong></label>
                        <br><br>
                        <p><?php echo $row['solicitud_sang']?></p>
                    </div>
                </div>
                <div class="col-sm-5">DETALLE DE TRASFUSIÓN SANGUINEA<br>
<textarea class="form-control" name="det_sang" rows="5"><?php echo $row['det_sang']?></textarea>
 </div>
            </div>

<?php } ?>
            <?php
            $usuario = $_SESSION['login'];
            ?>
            <hr>

            <div class="row">
                <div class="col align-self-start">
                    <strong>MÉDICO:</strong> <input type="text" name="" class="form-control"
                                                    value="<?php echo $usuario['nombre']; ?> <?php echo $usuario['papell']; ?> <?php echo $usuario['sapell']; ?>"
                                                    disabled>
                </div>
                <div class="col align-self-center">
                    <strong>CED. PROF:</strong><input type="text" name="" class="form-control"
                                                      value="<?php echo $usuario['cedp']; ?>" disabled="">

                </div>

            </div>
            <hr>
            <div class="container">
                <div class="row">
                    <div class="col align-self-start">
                    </div>
                    <button type="submit" name="guardar" class="btn btn-primary">FIRMAR</button> &nbsp;
                    <button type="button" class="btn btn-danger" onclick="history.back()">CANCELAR</button>
                    <div class="col align-self-end">
                    </div>
                </div>
            </div>


            <br>
            <br>
        </form>


        <!--TERMINO DE NOTA DE EVOLUCION-->


 <?php 
  if (isset($_POST['guardar'])) {

        $dieta    = mysqli_real_escape_string($conexion, (strip_tags($_POST["dieta"], ENT_QUOTES))); //Escanpando caracteres
        $cuid_gen    = mysqli_real_escape_string($conexion, (strip_tags($_POST["cuid_gen"], ENT_QUOTES))); //Escanpando caracteres
        $signos   = mysqli_real_escape_string($conexion, (strip_tags($_POST["signos"], ENT_QUOTES)));
        $monitoreo    = mysqli_real_escape_string($conexion, (strip_tags($_POST["monitoreo"], ENT_QUOTES))); //Escanpando caracteres
        $diuresis    = mysqli_real_escape_string($conexion, (strip_tags($_POST["diuresis"], ENT_QUOTES)));
        $dex    = mysqli_real_escape_string($conexion, (strip_tags($_POST["dex"], ENT_QUOTES)));
        $semif    = mysqli_real_escape_string($conexion, (strip_tags($_POST["semif"], ENT_QUOTES)));
        $vigilar    = mysqli_real_escape_string($conexion, (strip_tags($_POST["vigilar"], ENT_QUOTES)));
        $oxigeno    = mysqli_real_escape_string($conexion, (strip_tags($_POST["oxigeno"], ENT_QUOTES)));
        $nebulizacion    = mysqli_real_escape_string($conexion, (strip_tags($_POST["nebu"], ENT_QUOTES)));
        $bar    = mysqli_real_escape_string($conexion, (strip_tags($_POST["bar"], ENT_QUOTES)));
        $baño    = mysqli_real_escape_string($conexion, (strip_tags($_POST["baño"], ENT_QUOTES)));
        $foley   = mysqli_real_escape_string($conexion, (strip_tags($_POST["foley"], ENT_QUOTES)));
        $ej    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ej"], ENT_QUOTES)));
        $datsan    = mysqli_real_escape_string($conexion, (strip_tags($_POST["datsan"], ENT_QUOTES)));
        $detsignos    = mysqli_real_escape_string($conexion, (strip_tags($_POST["detsignos"], ENT_QUOTES)));
        $detmonitoreo    = mysqli_real_escape_string($conexion, (strip_tags($_POST["detmonitoreo"], ENT_QUOTES)));
        $detdiuresis    = mysqli_real_escape_string($conexion, (strip_tags($_POST["detdiuresis"], ENT_QUOTES)));
        $detdex    = mysqli_real_escape_string($conexion, (strip_tags($_POST["detdex"], ENT_QUOTES)));
        $detsemif    = mysqli_real_escape_string($conexion, (strip_tags($_POST["detsemif"], ENT_QUOTES)));
        $detvigilar    = mysqli_real_escape_string($conexion, (strip_tags($_POST["detvigilar"], ENT_QUOTES)));
        $detoxigeno    = mysqli_real_escape_string($conexion, (strip_tags($_POST["detoxigeno"], ENT_QUOTES)));
        $detnebu    = mysqli_real_escape_string($conexion, (strip_tags($_POST["detnebu"], ENT_QUOTES)));
        $detbar    = mysqli_real_escape_string($conexion, (strip_tags($_POST["detbar"], ENT_QUOTES)));
        $detbaño    = mysqli_real_escape_string($conexion, (strip_tags($_POST["detbaño"], ENT_QUOTES)));
        $detfoley    = mysqli_real_escape_string($conexion, (strip_tags($_POST["detfoley"], ENT_QUOTES)));
        $detej    = mysqli_real_escape_string($conexion, (strip_tags($_POST["detej"], ENT_QUOTES)));
        $detsan    = mysqli_real_escape_string($conexion, (strip_tags($_POST["detsan"], ENT_QUOTES)));
        $med_med    = mysqli_real_escape_string($conexion, (strip_tags($_POST["med_med"], ENT_QUOTES)));
        $soluciones    = mysqli_real_escape_string($conexion, (strip_tags($_POST["soluciones"], ENT_QUOTES)));
        $observ_be    = mysqli_real_escape_string($conexion, (strip_tags($_POST["observ_be"], ENT_QUOTES)));


        $detalle_lab    = mysqli_real_escape_string($conexion, (strip_tags($_POST["detalle_lab"], ENT_QUOTES)));
        $det_sang    = mysqli_real_escape_string($conexion, (strip_tags($_POST["det_sang"], ENT_QUOTES)));

date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

        $sql2 = "UPDATE dat_ordenes_med SET  dieta='$dieta',observ_be='$observ_be', cuid_gen='$cuid_gen', signos='$signos', monitoreo='$monitoreo', diuresis='$diuresis', dex='$dex' , semif='$semif', vigilar='$vigilar', oxigeno='$oxigeno', nebulizacion='$nebulizacion', bar='$bar', baño='$baño', foley='$foley', ej='$ej', datsan='$datsan', detsignos='$detsignos', detmonitoreo='$detmonitoreo', detdiuresis='$detdiuresis', detdex='$detdex', detsemif='$detsemif', detvigilar='$detvigilar', detoxigeno='$detoxigeno', detnebu='$detnebu', detbar='$detbar', detbaño='$detbaño', detfoley='$detfoley', detej='$detej', detsan='$detsan', med_med='$med_med', soluciones='$soluciones', detalle_lab='$detalle_lab', det_sang='$det_sang' WHERE id_ord_med= $id_ord";
        $result = $conexion->query($sql2);

        echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> DATO EDITADO CORRECTAMENTE...</p>";
        echo '<script type="text/javascript">window.location ="../cartas_consentimientos/consent_medico.php"</script>';
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
</script>


</body>
</html>