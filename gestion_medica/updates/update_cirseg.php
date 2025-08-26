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


    <title>NOTA CIRUGIA SEGURA </title>
</head>
<body>

<div class="col-sm-12">
    <div class="container">
        <div class="row">
            <div class="col">
<div class="thead" style="background-color: #0c675e; color: white; font-size: 24px;">
  <center><strong>NOTA CIRUGIA SEGURA (LISTADO DE VERIFICACIÓN DE SEGURIDAD QUIRÚRGICA)</strong></center><p>
</div>                 
               
<?php

include "../../conexionbd.php";

$resultado1 = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE id_atencion=" . $_SESSION['hospital']) or die($conexion->error);

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
$resultado2 = $conexion->query("select * from cat_camas WHERE id_atencion=" .$_SESSION['hospital']) or die($conexion->error);
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



<?php 
$id_cir_seg=$_GET['id_cir_seg'];
$alta="SELECT * FROM dat_cir_seg where id_cir_seg=$id_cir_seg";
$result=$conexion->query($alta);
while ($row=$result->fetch_assoc()) {
  $id_usua=$row['id_usua'];
 ?>    
<div class="container">  <!--INICIO DE CONSULTA DE URGENCIAS-->
<form action="" method="POST">
<div class="container">
  <div class="row">
    <div class="col-sm">
     <!--INICIO DE CARD-->
<div class="card" style="width: 19rem;">
  <div class="card-body">
    <center><h6 class="card-title"><strong>ANTES DE LA ADMINISTRACIÓN DE LA ANESTESIA</strong></h6></center><hr>
    <h7 class="card-subtitle text-muted"><strong>ENTRADA</strong> (ENFERMERA Y ANESTESIÓLOGO)</h7><hr>
     <div class="form-check">
      <?php if ($row['entrada_pac_confirm']=="SI") {?>
  <input class="form-check-input" type="radio" id="URGENCIAS" checked="" value="SI" name="entrada_pac_confirm">
<?php }else{ ?>
  <input class="form-check-input" type="radio" id="URGENCIAS" value="SI" name="entrada_pac_confirm">
<?php } ?>
  <label class="form-check-label" for="URGENCIAS">
    EL PACIENTE HA CONFIRMADO:
    <ul>
  <li>SU IDENTIDAD</li>
  <li>LOCALIZACIÓN QUIRÚRGICA</li>
  <li>LA OPERACIÓN</li>
  <li>CONSENTIMIENTO INFORMADO</li>
    <ul>
  </label>
</div>
<hr>
<div class="container">
  <div class="row">
<?php if ($row['lug_noproc']=="LUGAR DEL CUERPO MARCADO") { ?>
      <div class="form-check">
  <input class="form-check-input" type="radio" id="lugar" checked="" value="LUGAR DEL CUERPO MARCADO" name="lug_noproc">
  <label class="form-check-label" for="lugar">
   LUGAR DEL CUERPO MARCADO
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" id="noprocede" value="NO PROCEDE" name="lug_noproc">
  <label class="form-check-label" for="noprocede">
    NO PROCEDE
  </label>
</div>
<?php }else{ ?>
  <div class="form-check">
  <input class="form-check-input" type="radio" id="lugar" value="LUGAR DEL CUERPO MARCADO" name="lug_noproc">
  <label class="form-check-label" for="lugar">
   LUGAR DEL CUERPO MARCADO
  </label>
</div>
  <div class="form-check">
  <input class="form-check-input" type="radio" checked="" id="noprocede" value="NO PROCEDE" name="lug_noproc">
  <label class="form-check-label" for="noprocede">
    NO PROCEDE
  </label>
</div>
<?php } ?>
    </div>
  </div>
  <hr>
  <?php if ($row['verificado']=="SI") { ?>
   <div class="form-check">
  <input class="form-check-input" type="radio" checked="" id="verificado" value="SI" name="verificado">
  <label class="form-check-label" for="verificado">
    VERIFICADO EQUIPO Y MEDICACIÓN DE LA ANESTESIA
  </label>
</div>
<?php }else{ ?>
  <div class="form-check">
  <input class="form-check-input" type="radio"  id="verificado" value="SI" name="verificado">
  <label class="form-check-label" for="verificado">
    VERIFICADO EQUIPO Y MEDICACIÓN DE LA ANESTESIA
  </label>
</div>
<?php } ?>
<hr>
<?php if ($row['pulsioximetro']=="SI") { ?>
 <div class="form-check"> 
  <input class="form-check-input" type="radio" checked="" id="pulsioximetro" value="SI" name="pulsioximetro">
  <label class="form-check-label" for="pulsioximetro">
   PULSIOXIMETRO FUNCIONANDO EN EL PACIENTE
  </label>
</div>
<?php }else{ ?>
<div class="form-check">
  <input class="form-check-input" type="radio" id="pulsioximetro" value="SI" name="pulsioximetro">
  <label class="form-check-label" for="pulsioximetro">
   PULSIOXIMETRO FUNCIONANDO EN EL PACIENTE
  </label>
</div>
<?php } ?>
<hr>
<?php if ($row['ver_inst']=="SI") { ?>
 <div class="form-check">
  <input class="form-check-input" checked="" type="radio" id="ver_inst" value="SI" name="ver_inst">
  <label class="form-check-label" for="ver_insepro">
   VERIFICADO INSTRUMENTAL/EQUIPO QUIRÚRGICO/PROTÉSIS
  </label>
</div>
<?php }else{ ?>
<div class="form-check">
  <input class="form-check-input" type="radio" id="ver_inst" value="SI" name="ver_inst">
  <label class="form-check-label" for="ver_insepro">
   VERIFICADO INSTRUMENTAL/EQUIPO QUIRÚRGICO/PROTÉSIS
  </label>
</div>
<?php } ?>
   <hr>
    <strong>¿TIENE EL PACIENTE?</strong><br>
    <p>¿ALERGIAS CONOCIDAS?</p>
<?php if ($row['alerg_con']=="NO") {?>
  <div class="row">
    <div class="col-sm">
      <div class="form-check">
  <input class="form-check-input" type="radio" checked="" id="aler_conno" value="NO" name="alerg_con">
  <label class="form-check-label" for="aler_conno">
 NO
  </label>
</div>
    </div>
    <div class="col-sm-9">
      <div class="form-check">
  <input class="form-check-input" type="radio" id="aler_consi" value="SI" name="alerg_con">
  <label class="form-check-label" for="aler_consi">
   SI
  </label>
</div>
    </div>
  </div>
<?php }else{ ?>
<div class="row">
    <div class="col-sm">
      <div class="form-check">
  <input class="form-check-input" type="radio" id="aler_conno" value="NO" name="alerg_con">
  <label class="form-check-label" for="aler_conno">
 NO
  </label>
</div>
    </div>
    <div class="col-sm-9">
      <div class="form-check">
  <input class="form-check-input" checked="" type="radio" id="aler_consi" value="SI" name="alerg_con">
  <label class="form-check-label" for="aler_consi">
   SI
  </label>
</div>
    </div>
  </div>
<?php } ?>
  <hr>
    <p><strong>¿PROFILAXIS ANTIBIÓTICA EN LOS ÚLTIMOS 60 MINUTOS?</strong></p>
<?php if ($row['profilaxis']=="SI") {?>
  <div class="row">
    <div class="col-sm">
      <div class="form-check">
  <input class="form-check-input" type="radio" checked="" id="profisi" value="SI" name="profilaxis">
  <label class="form-check-label" for="profisi">
 SI
  </label>
</div>
    </div>
    <div class="col-sm-9">
      <div class="form-check">
  <input class="form-check-input" type="radio" id="profino" value="NO PROCEDE" name="profilaxis">
  <label class="form-check-label" for="profino">
   NO PROCEDE
  </label>
</div>
    </div>
  </div>
<?php }else{ ?>
  <div class="row">
    <div class="col-sm">
      <div class="form-check">
  <input class="form-check-input" type="radio" id="profisi" value="SI" name="profilaxis">
  <label class="form-check-label" for="profisi">
 SI
  </label>
</div>
    </div>
    <div class="col-sm-9">
      <div class="form-check">
  <input class="form-check-input" type="radio" checked="" id="profino" value="NO PROCEDE" name="profilaxis">
  <label class="form-check-label" for="profino">
   NO PROCEDE
  </label>
</div>
    </div>
  </div>
<?php } ?>
  <hr>
    <p><strong>¿DIFICULTAD EN LA VÍA AÉREA/RIESGO DE ASPIRACIÓN?</strong></p>
<?php if($row['dif_via_aerea']=="NO"){ ?>
  <div class="row">
    <div class="col-sm">
      <div class="form-check">
  <input class="form-check-input" type="radio" checked="" id="difviano" value="NO" name="dif_via_aerea">
  <label class="form-check-label" for="difviano">
 NO
  </label>
</div>
    </div>
    <div class="col-sm-9">
      <div class="form-check">
  <input class="form-check-input" type="radio" id="difviasi" value="SI" name="dif_via_aerea">
  <label class="form-check-label" for="difviasi">
   SI, Y EL EQUIPO Y LA ASISTENCIA ESTÁN DISPONIBLES
  </label>
</div>
    </div>
  </div>
<?php }else{ ?>
<div class="row">
    <div class="col-sm">
      <div class="form-check">
  <input class="form-check-input" type="radio" id="difviano" value="NO" name="dif_via_aerea">
  <label class="form-check-label" for="difviano">
 NO
  </label>
</div>
    </div>
    <div class="col-sm-9">
      <div class="form-check">
  <input class="form-check-input" type="radio" checked="" id="difviasi" value="SI" name="dif_via_aerea">
  <label class="form-check-label" for="difviasi">
   SI, Y EL EQUIPO Y LA ASISTENCIA ESTÁN DISPONIBLES
  </label>
</div>
    </div>
  </div>
<?php } ?>
<hr>
   <p><strong>¿PUEDE PRECISAR DE CONCENTRACIÓN DE HEMATÍES? ¿MENOS DE 500 ML DE SANGRE (7ML/KG EN NIÑOS)?</strong></p>
<?php if($row['con_hematies']=="NO"){ ?>
  <div class="row">
    <div class="col-sm">
      <div class="form-check">
  <input class="form-check-input" type="radio" checked="" id="hematiesno" value="NO" name="con_hematies">
  <label class="form-check-label" for="hematiesno">
 NO
  </label>
</div>
    </div>
    <div class="col-sm-9">
      <div class="form-check">
  <input class="form-check-input" type="radio" id="hematiessi" value="SI" name="con_hematies">
  <label class="form-check-label" for="hematiessi">
   SI, Y DISPONE DE UNA VÍA DE ACCESO IV ADECUADA/FLUIDOS NECESARIOS.
  </label>
</div>
    </div>
  </div>
 <?php }else{ ?>
  <div class="row">
    <div class="col-sm">
      <div class="form-check">
  <input class="form-check-input" type="radio" id="hematiesno" value="NO" name="con_hematies">
  <label class="form-check-label" for="hematiesno">
 NO
  </label>
</div>
    </div>
    <div class="col-sm-9">
      <div class="form-check">
  <input class="form-check-input" type="radio" checked="" id="hematiessi" value="SI" name="con_hematies">
  <label class="form-check-label" for="hematiessi">
   SI, Y DISPONE DE UNA VÍA DE ACCESO IV ADECUADA/FLUIDOS NECESARIOS.
  </label>
</div>
    </div>
  </div>
 <?php } ?> 
  </div>
</div>
</div>
     <!--FIN DE CARD-->

     <!--INICIO DE SEGUNDA CARD-->
<div class="col-sm"> 
<div class="card" style="width: 19rem;">
  <div class="card-body">
    <h6 class="card-title"><strong>ANTES DE LA INSICIÓN EN LA PIEL</strong></h6><hr>
    <h7 class="card-subtitle mb-2 text-muted"><strong>PAUSA</strong> (Enfermera, Anestesiologo y Cirujano)</h7><hr>
   <?php if ($row['confirm_presentes']=="SI") {?>
      <div class="form-check">
  <input class="form-check-input" type="radio" checked="" id="confmp" value="SI" name="confirm_presentes">
  <label class="form-check-label" for="confmp">
   CONFIRMADO QUE TODOS LO MIEMBROS DEL EQUIPO ESTÁN PRESENTES Y PREPARADOS.
  </label>
</div>
<?php }else{ ?>
<div class="form-check">
  <input class="form-check-input" type="radio" id="confmp" value="SI" name="confirm_presentes">
  <label class="form-check-label" for="confmp">
   CONFIRMADO QUE TODOS LO MIEMBROS DEL EQUIPO ESTÁN PRESENTES Y PREPARADOS.
  </label>
</div>
<?php } ?>
<hr>
 <div class="form-check">
  <?php if ($row['confirm_verb']=="SI") { ?>
  <input class="form-check-input" type="radio" checked="" id="ciranes" value="SI" name="confirm_verb">
<?php }else{ ?>
 <input class="form-check-input" type="radio" id="ciranes" value="SI" name="confirm_verb">
<?php } ?>
  <label class="form-check-label" for="ciranes">
    CIRUJANO, ANESTESIOLÓGO Y ENFERMERA HAN CONFIRMADO VERBALMENTE:
    <ul>
  <li>PACIENTE</li>
  <li>SITIO QUIRÚRGICO</li>
  <li>PROCEDIMIENTO</li>
  <li>POSICIÓN</li>
   <li>SONDAJE</li>
    <ul>
  </label>
</div>
<hr>
<strong><p>ANTICIPACIÓN DE SUCESOS CRITICOS</p></strong>
  <div class="form-check">
    <?php if ($row['cir_rep']=="SI") { ?>
  <input class="form-check-input" checked="
  " type="radio" id="cirresi" value="SI" name="cir_rep">
<?php }else{ ?>
<input class="form-check-input" type="radio" id="cirresi" value="SI" name="cir_rep">
<?php } ?>
  <label class="form-check-label" for="cirresi">
  CIRUJANO REPASA: ¿CUALES SON LOS PASOS CRÍTICOS O INESPERADOS, LA DURACIÓN DE LA INTERVENCIÓN, LA PÉRDIDA DE SANGRE ESPERADA?
  </label>
</div>
<hr>
     <div class="form-check">
<?php if ($row['anest_resp']=="SI") { ?>
  <input class="form-check-input" type="radio" checked="" id="anesresi" value="SI" name="anest_resp">
<?php }else{ ?>
  <input class="form-check-input" type="radio" id="anesresi" value="SI" name="anest_resp">
<?php } ?>
  <label class="form-check-label" for="anesresi">
  ANESTESIÓLOGO REPASA: ¿PRESENTA EL PACIENTE ALGUNA PECULIARIDAD QUE SUSCITE PREOCUPACIÓN?
  </label>
</div>
<hr>
 <div class="form-check">
<?php if ($row['enf_rep']=="SI") { ?>
  <input class="form-check-input" type="radio" checked="" id="enfresi" value="SI" name="enf_rep">
<?php }else{ ?>
  <input class="form-check-input" type="radio" id="enfresi" value="SI" name="enf_rep">
<?php } ?>
  <label class="form-check-label" for="enfresi">
  EL EQUIPO DE ENFERMERIA REVISA: SI SE HA CONFIRMADO LA ESTERILIDAD(CON RESULTADOS DE LOS INDICADORES) Y SI EXISTEM DUDAS O PROBLEMAS RELACIONADOS CON EL INSTRUMENTAL Y LOS EQUIPOS
  </label>
</div>
<hr>

<p><strong>¿SE MUESTRAN LAS IMÁGENES DIAGNOSTICAS ESENCIALES?</strong></p>
<?php if($row['img_diag']=="SI"){ ?>
  <div class="row">
    <div class="col-sm">
      <div class="form-check">
  <input class="form-check-input" type="radio" checked="" id="diag_esesi" value="SI" name="img_diag">
  <label class="form-check-label" for="diag_esesi">
 SI
  </label>
</div>
    </div>
    <div class="col-sm-9">
      <div class="form-check">
  <input class="form-check-input" type="radio" id="diag_eseno" value="NO PROCEDE" name="img_diag">
  <label class="form-check-label" for="diag_eseno">
  NO PROCEDE
  </label>
</div>
    </div>
  </div>
<?php }else{ ?>
  <div class="row">
    <div class="col-sm">
      <div class="form-check">
  <input class="form-check-input" type="radio" id="diag_esesi" value="SI" name="img_diag">
  <label class="form-check-label" for="diag_esesi">
 SI
  </label>
</div>
    </div>
    <div class="col-sm-9">
      <div class="form-check">
  <input class="form-check-input" type="radio" checked="" id="diag_eseno" value="NO PROCEDE" name="img_diag">
  <label class="form-check-label" for="diag_eseno">
  NO PROCEDE
  </label>
</div>
    </div>
  </div>
<?php } ?>
<hr>
  <div class="form-group">
    <label for="exampleFormControlInput1">PROCEDIMIENTO:</label>
    <input type="text" class="form-control" id="exampleFormControlInput1" value="<?php echo $row['proced'] ?>" placeholder="Procedimiento" name="proced">
  </div><div class="form-group">
                        <label for="dieta"><strong>ESPECIALIDAD:</strong></label>
                        <select class="form-control" name="especialidad" required id="dieta">
                          <option value="<?php echo $row['especialidad'] ?>"><?php echo $row['especialidad'] ?></option>
                          <option></option>
                            <option disabled="">SELECCIONAR ESPECIALIDAD</option>
                            <option value="NINGUNO">NINGUNO</option>
                            <?php
                            $sql_d = "SELECT * FROM cat_espec WHERE espec_activo='SI'";
                            $result_d = $conexion->query($sql_d);
                            while ($row_d = $result_d->fetch_assoc()) {
                                echo "<option value='" . $row_d['espec'] . "'>" . $row_d['espec'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
  
                    
    
  </div>
</div>
      <!--TERMINO DE SEGUNDA CAR-->

    </div>
    <div class="col-sm">
     <!--INICIO DE TERCER CAR-->
<div class="card" style="width: 19rem;">
  <div class="card-body">
    <h6 class="card-title"><strong>ANTES DE QUE EL PACIENTE ABANDONE EL QUIROFANO</strong></h6><hr>
    <h7 class="card-subtitle mb-2 text-muted"><strong>SALIDA</strong> (ENFERMERA, ANESTESIOLOGO Y CIRUJANO)</h7><hr>
   <div class="form-check">
<?php if ($row['enf_confirm']=="SI") { ?>
  <input class="form-check-input" type="radio" checked="" id="enfconsi" value="SI" name="enf_confirm">
<?php }else{ ?> 
 <input class="form-check-input" type="radio" id="enfconsi" value="SI" name="enf_confirm">
<?php } ?>
  <label class="form-check-label" for="enfconsi">
  LA ENFERMERA CONFIRMA VERBALMENTE CON EL EQUIPO REGISTRADO EL NOMBRE DEL PROCEDIMIENTO
  </label>
</div><hr>
<div class="form-check">
<?php if ($row['cont_comp_inst']=="SI") { ?>
  <input class="form-check-input" type="radio" checked="" id="concomsi" value="SI" name="cont_comp_inst">
<?php }else{ ?> 
  <input class="form-check-input" type="radio" id="concomsi" value="SI" name="cont_comp_inst">
<?php } ?>
  <label class="form-check-label" for="concomsi">
CONTAJE DE COMPRESAS, AGUJAS E INSTRUMENTAL CORRECTO
  </label>
</div><hr>
<div class="container">
  <?php if($row['ident_gest']=="SI"){ ?>
  <div class="row">
   <div class="form-check">
  <input class="form-check-input" type="radio" id="idegessi" checked="" value="SI" name="ident_gest">
  <label class="form-check-label" for="idegessi">
IDENTIFICACIÓN Y GESTIÓN DE LAS MUESTRAS BIÓLOGICAS (NOMBRE, NO, HO, FECHA DE NACIMIENTO)
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" id="idegesno" value="NO PROCEDE" name="ident_gest">
  <label class="form-check-label" for="idegesno">
NO PROCEDE
  </label>
</div> 
  </div>
<?php }else{ ?>
  <div class="row">
   <div class="form-check">
  <input class="form-check-input" type="radio" id="idegessi" value="SI" name="ident_gest">
  <label class="form-check-label" for="idegessi">
IDENTIFICACIÓN Y GESTIÓN DE LAS MUESTRAS BIÓLOGICAS (NOMBRE, NO, HO, FECHA DE NACIMIENTO)
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" checked="" id="idegesno" value="NO PROCEDE" name="ident_gest">
  <label class="form-check-label" for="idegesno">
NO PROCEDE
  </label>
</div> 
  </div>
<?php } ?>
</div>
<hr>
<p><strong>¿HAY ALGÚN PROBLEMA EN RELACIÓN CON EL MATERIAL O LOS EQUIPOS?</strong></p>
<?php if ($row['problema']=="SI") { ?>
  <div class="row">
    <div class="col-sm">
   <div class="form-check">
  <input class="form-check-input" type="radio" checked="" id="probmatsi" value="SI" name="problema">
  <label class="form-check-label" for="probmatsi">
SI
  </label>
</div> 
    </div>
    <div class="col-sm-9">
     <div class="form-check">
  <input class="form-check-input" type="radio" id="probmatno" value="NO" name="problema">
  <label class="form-check-label" for="probmatno">
NO
  </label>
</div> 
    </div>
  </div>
<?php }else{ ?>
  <div class="row">
    <div class="col-sm">
   <div class="form-check">
  <input class="form-check-input" type="radio" id="probmatsi" value="SI" name="problema">
  <label class="form-check-label" for="probmatsi">
SI
  </label>
</div> 
    </div>
    <div class="col-sm-9">
     <div class="form-check">
  <input class="form-check-input" type="radio" checked="" id="probmatno" value="NO" name="problema">
  <label class="form-check-label" for="probmatno">
NO
  </label>
</div> 
    </div>
  </div>
<?php } ?>
<hr>
<div class="form-check">
<?php if ($row['rev_cir_enf_anest']=="SI") { ?>
  <input class="form-check-input" type="radio" checked="" id="ciranessi" value="SI" name="rev_cir_enf_anest">
<?php }else{ ?> 
  <input class="form-check-input" type="radio" id="ciranessi" value="SI" name="rev_cir_enf_anest">
<?php } ?>
  <label class="form-check-label" for="ciranessi">
CIRUJANO, ANESTESIÓLOGO Y ENFERMERA REVISARON LAS PREOCUPACIONES CLAVES EN LA RECUPERACIÓN Y ATENCIÓN DEL PACIENTE.
  </label>
</div> 
<hr>

<p><strong>¿NECESITA PROFILAXIS TROMBOEMBÓLICA?</strong></p>
<?php if ($row['prof_trombo']=="SI") {?>
  <div class="row">
    <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" type="radio" checked="" id="probmatno" value="SI" name="prof_trombo">
  <label class="form-check-label" for="probmatno">
SI
  </label>
</div> 
    </div>
    <div class="col-sm-9">
      <div class="form-check">
  <input class="form-check-input" type="radio" id="probmatno" value="NO" name="prof_trombo">
  <label class="form-check-label" for="probmatno">
 NO
  </label>
</div> 
    </div>
  </div>
<?php }else{?>
  <div class="row">
    <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" type="radio" id="probmatno" value="SI" name="prof_trombo">
  <label class="form-check-label" for="probmatno">
SI
  </label>
</div> 
    </div>
    <div class="col-sm-9">
      <div class="form-check">
  <input class="form-check-input" type="radio" checked="" id="probmatno" value="NO" name="prof_trombo">
  <label class="form-check-label" for="probmatno">
 NO
  </label>
</div> 
    </div>
  </div>
<?php } ?>
  <hr>
<div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>ETIQUETA IDENTIFICATIVA DEL PACIENTE</strong></label>
<textarea name="ident_pac" class="form-control" rows="3"><?php echo $row['ident_pac'] ?></textarea>
  </div>
    <div class="form-group">
    <label for="exampleFormControlInput1">CIRUJANO</label>
    <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="NOMBRE" value="<?php echo $row['fir_cir'] ?>" name="fir_cir">
  </div>
    <div class="form-group">
    <label for="exampleFormControlInput1">ANESTESIÓLOGO</label>
    <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="NOMBRE" value="<?php echo $row['fir_anest'] ?>" name="fir_anest">
  </div>
    <div class="form-group">
    <label for="exampleFormControlInput1">ENFERMERA</label>
    <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="NOMBRE" value="<?php echo $row['fir_enf'] ?>" name="fir_enf">
  </div>
    
  </div>
</div>
     <!--TERMINO DE CAR-->
    </div>
  </div>
</div>
    <?php 
$date=date_create($row['fecha']);
?>
      <div class="row">
          <div class="col-sm-4">
              <div class="form-group">
                 <strong> <label>FECHA REGISTRO : </label></strong><br>
                 <input type="date" name="fecha" value="<?php echo date_format($date,"Y-m-d") ?>" class="form-control">
             </div>
           </div>
           <div class="col-sm-4">
              <div class="form-group">
                 <strong> <label>HORA REGISTRO :</label></strong><br>
                 <input type="time" name="hora" value="<?php echo date_format($date,"H:i:s") ?>" class="form-control">
             </div>
           </div>
       <?php 
   }
$select="SELECT * FROM reg_usuarios where id_usua=$id_usua";
$resultado=$conexion->query($select);
while ($row_doc=$resultado->fetch_assoc()) {
    $doctor=$row_doc['nombre'].' '.$row_doc['papell'].' '.$row_doc['sapell'];
    $id_doc=$row_doc['id_usua'];
}

        ?>
          <div class="col-sm-4">
              <div class="form-group">
                 <strong> <label>MÉDICO QUE REGISTRÓ: </label></strong><br>
                 <select name="medico" class="form-control" >
                     <option value="<?php echo $id_doc ?>"><?php echo $doctor ?></option>
                     <option></option>
                     <option value=" ">SELECCIONAR MEDICO :</option>
                     <?php 
                      $select="SELECT * FROM reg_usuarios where id_rol=2 || id_rol=12";
                      $resultado=$conexion->query($select);
                      foreach ($resultado as $row ) {
                      ?>
                      <option value="<?php echo $row['id_usua'] ?>"><?php echo $row['nombre'].' '.$row['papell'].' '.$row['sapell']; ?></option>
                  <?php } ?>
                 </select>
             </div>
           </div>
       </div>
 <hr>
 <center><div class="form-group col-6">
    <button type="submit" name="guardar" class="btn btn-primary">FIRMAR</button>
    <button type="button" class="btn btn-danger" onclick="history.back()">CANCELAR</button>
</div></center>

<br>
 </form>
</div> <!--TERMINO DE NOTA MEDICA div container-->

</div>
  <?php 
  if (isset($_POST['guardar'])) {

        $entrada_pac_confirm    = mysqli_real_escape_string($conexion, (strip_tags($_POST["entrada_pac_confirm"], ENT_QUOTES))); //Escanpando caracteres
        $lug_noproc    = mysqli_real_escape_string($conexion, (strip_tags($_POST["lug_noproc"], ENT_QUOTES))); //Escanpando caracteres
        $verificado   = mysqli_real_escape_string($conexion, (strip_tags($_POST["verificado"], ENT_QUOTES)));
        $pulsioximetro    = mysqli_real_escape_string($conexion, (strip_tags($_POST["pulsioximetro"], ENT_QUOTES)));
        $ver_inst    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ver_inst"], ENT_QUOTES)));
        $alerg_con    = mysqli_real_escape_string($conexion, (strip_tags($_POST["alerg_con"], ENT_QUOTES)));

        $profilaxis    = mysqli_real_escape_string($conexion, (strip_tags($_POST["profilaxis"], ENT_QUOTES))); //Escanpando caracteres
        $dif_via_aerea    = mysqli_real_escape_string($conexion, (strip_tags($_POST["dif_via_aerea"], ENT_QUOTES))); //Escanpando caracteres
        $con_hematies   = mysqli_real_escape_string($conexion, (strip_tags($_POST["con_hematies"], ENT_QUOTES)));
        $confirm_presentes    = mysqli_real_escape_string($conexion, (strip_tags($_POST["confirm_presentes"], ENT_QUOTES))); //Escanpando caracteres
        $confirm_verb    = mysqli_real_escape_string($conexion, (strip_tags($_POST["confirm_verb"], ENT_QUOTES)));
        $cir_rep    = mysqli_real_escape_string($conexion, (strip_tags($_POST["cir_rep"], ENT_QUOTES)));
        $anest_resp    = mysqli_real_escape_string($conexion, (strip_tags($_POST["anest_resp"], ENT_QUOTES)));
        $enf_rep    = mysqli_real_escape_string($conexion, (strip_tags($_POST["enf_rep"], ENT_QUOTES)));
        $img_diag    = mysqli_real_escape_string($conexion, (strip_tags($_POST["img_diag"], ENT_QUOTES)));

        $proced    = mysqli_real_escape_string($conexion, (strip_tags($_POST["proced"], ENT_QUOTES))); //Escanpando caracteres
        $especialidad    = mysqli_real_escape_string($conexion, (strip_tags($_POST["especialidad"], ENT_QUOTES))); //Escanpando caracteres
        $fecha   = mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha"], ENT_QUOTES)));
        $enf_confirm    = mysqli_real_escape_string($conexion, (strip_tags($_POST["enf_confirm"], ENT_QUOTES))); //Escanpando caracteres
        $cont_comp_inst    = mysqli_real_escape_string($conexion, (strip_tags($_POST["cont_comp_inst"], ENT_QUOTES)));
        $ident_gest    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ident_gest"], ENT_QUOTES)));
        $problema    = mysqli_real_escape_string($conexion, (strip_tags($_POST["problema"], ENT_QUOTES)));
        $rev_cir_enf_anest    = mysqli_real_escape_string($conexion, (strip_tags($_POST["rev_cir_enf_anest"], ENT_QUOTES)));
        $prof_trombo      = mysqli_real_escape_string($conexion, (strip_tags($_POST["prof_trombo  "], ENT_QUOTES)));

        $ident_pac    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ident_pac"], ENT_QUOTES))); //Escanpando caracteres
        $fir_cir    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fir_cir"], ENT_QUOTES))); //Escanpando caracteres
        $fir_anest   = mysqli_real_escape_string($conexion, (strip_tags($_POST["fir_anest"], ENT_QUOTES)));
        $fir_enf    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fir_enf"], ENT_QUOTES)));

        $fecha= mysqli_real_escape_string($conexion,(strip_tags($_POST["fecha"], ENT_QUOTES)));
        $hora= mysqli_real_escape_string($conexion,(strip_tags($_POST["hora"], ENT_QUOTES)));
        $medico= mysqli_real_escape_string($conexion,(strip_tags($_POST["medico"], ENT_QUOTES)));

        $merge = $fecha.' '.$hora;
       
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

        $sql2 = "UPDATE dat_cir_seg SET id_usua='$medico',fecha='$merge',entrada_pac_confirm='$entrada_pac_confirm', lug_noproc='$lug_noproc', verificado='$verificado',pulsioximetro='$pulsioximetro', ver_inst='$ver_inst', alerg_con='$alerg_con',profilaxis='$profilaxis', dif_via_aerea='$dif_via_aerea', con_hematies='$con_hematies', confirm_presentes='$confirm_presentes', confirm_verb='$confirm_verb', cir_rep='$cir_rep' , anest_resp='$anest_resp', enf_rep='$enf_rep', img_diag='$img_diag',proced='$proced', especialidad='$especialidad', enf_confirm='$enf_confirm', cont_comp_inst='$cont_comp_inst', ident_gest='$ident_gest' , problema='$problema', rev_cir_enf_anest='$rev_cir_enf_anest', prof_trombo ='$prof_trombo ',ident_pac='$ident_pac', fir_cir='$fir_cir', fir_anest='$fir_anest', fir_enf='$fir_enf' WHERE id_cir_seg= $id_cir_seg";
        $result = $conexion->query($sql2);

        echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> DATO EDITADO CORRECTAMENTE...</p>";
        echo '<script type="text/javascript">window.location ="../cartas_consentimientos/consent_medico.php"</script>';
      }
  ?>
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