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
  $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.tel, p.fecnac,p.tip_san, di.fecha, di.area, di.alta_med, p.sexo, di.alergias FROM paciente p, dat_ingreso di WHERE p.Id_exp=di.Id_exp and di.id_atencion =$id_atencion";

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

      function calculaedad($fechanacimiento)
      {
        list($ano, $mes, $dia) = explode("-", $fechanacimiento);
        $ano_diferencia  = date("Y") - $ano;
        $mes_diferencia = date("m") - $mes;
        $dia_diferencia   = date("d") - $dia;
        if ($dia_diferencia < 0 || $mes_diferencia < 0)
          $ano_diferencia--;
        return $ano_diferencia;
      }

      $edad = calculaedad($pac_fecnac);

?>
   <div class="container">
        <div class="thead" style="background-color: #0c675e; color: white; font-size: 25px;">
                 <tr><strong><center>REGISTRO CLÍNICO NEONATAL</center></strong>
        </div>
 <font size="2">
         <div class="container">
  <div class="row">
    <div class="col-sm">
     NOMBRE DEL PACIENTE: <strong><?php echo $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac ?></strong>
    </div>
    <div class="col-sm">
      ÁREA: <strong><?php echo $area ?></strong>
    </div>
   <?php $date = date_create($pac_fecing);
   ?>
      <div class="col-sm">
      FECHA DE INGRESO: <strong><?php echo date_format($date, "d-m-Y H:i:s") ?></strong>
    </div>
  </div>
</div></font>
<font size="2">
     <div class="container">
  <div class="row">
    <div class="col-sm">
       <?php $date1 = date_create($pac_fecnac);
   ?>
      F. DE NACIMIENTO: <strong><?php echo date_format($date1, "d-m-Y") ?></strong>
    </div>
    <div class="col-sm">
      SEXO: <strong><?php echo $pac_sexo ?></strong>
    </div>
   
      <div class="col-sm">
      HABITACIÓN: <strong><?php $sql_hab = "SELECT num_cama from cat_camas where id_atencion =$id_atencion";
$result_hab = $conexion->query($sql_hab);                                                                                    while ($row_hab = $result_hab->fetch_assoc()) {
  echo $row_hab['num_cama'];
} ?></strong>
    </div>
    <div class="col-sm">
      TIEMPO ESTANCIA: <strong><?php echo $estancia ?> Dias</strong>
    </div>
  </div>
</div>
</font>
 <font size="2">
  <div class="container">
  <div class="row">
    <div class="col-sm-3">
      EDAD: <strong><?php echo $edad ?></strong>
    </div>
   <div class="col-sm-3">

      PESO: <strong><?php $sql_vit = "SELECT * from dat_hclinica where Id_exp=$id_exp ORDER by id_hc DESC LIMIT 1";
      
$result_vit = $conexion->query($sql_vit);                                                                                    while ($row_vit = $result_vit->fetch_assoc()) {
    $peso=$row_vit['peso'];

} if (!isset($peso)){
    $peso=0;
   
}   echo $peso;?></strong>
    </div>
  
      <div class="col-sm">
      TALLA: <strong><?php $sql_vitt =" SELECT * from dat_hclinica where Id_exp=$id_exp ORDER by id_hc DESC LIMIT 1";
$result_vitt = $conexion->query($sql_vitt);                                                                                    while ($row_vitt = $result_vitt->fetch_assoc()) {
    $talla=$row_vitt['talla'];
 
} if (!isset($talla)){
    
    $talla=0;
}   echo $talla;?></strong>
    </div>


     <div class="col-sm-4">
      DIAGNÓSTICO MÉDICO: <strong><?php $sql_mot = "SELECT motivo_atn from dat_ingreso where id_atencion=$id_atencion ORDER by motivo_atn DESC LIMIT 1";
$result_mot = $conexion->query($sql_mot);                                                                                    while ($row_mot = $result_mot->fetch_assoc()) {
  echo $row_mot['motivo_atn'];
} ?></strong>
    </div>
  </div>
</div>
</font>
        <hr>
<div class="tab-content" id="nav-tabContent">
   <!--INICIO DE NOTA DE EVOLUCION-->
<div class="tab-pane fade show active" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">

<?php 
$id_rec_nac=$_GET['id_rec_nac'];
$select="SELECT * FROM iden_recnac WHERE id_rec_nac=$id_rec_nac";
$result=$conexion->query($select);
while ($row=$result->fetch_assoc()) {
  $fecha=$row['fechab'];
  $hora=$row['horab'];
 ?>
<form action="" method="POST">
<br>    
<div class="container">
  <div class="row">
    <div class="col-sm-3">
      <strong>FECHA/DIA HOSP:</strong><input type="date" class="form-control" name="fechab" value="<?php echo $row['fechab'] ?>">
    </div>
    <div class="col-sm-3">
      <strong>HORA:</strong><select class="form-control" aria-label="Default select example" name="horab">
        <option value="<?php echo $row['horab'] ?>"><?php echo $row['horab'] ?></option>
        <option></option>
  <option value="">SELECCIONAR UNA HORA</option>
  <option value="08:00 A.M.">08:00 A.M.</option>
  <option value="09:00 A.M.">09:00 A.M.</option>
  <option value="10:00 A.M.">10:00 A.M.</option>
  <option value="11:00 A.M.">11:00 A.M.</option>
  <option value="12:00 P.M.">12:00 P.M.</option>
  <option value="13:00 P.M.">13:00 P.M.</option>
  <option value="14:00 P.M.">14:00 P.M.</option>
  <option value="15:00 P.M.">15:00 P.M.</option>
  <option value="16:00 P.M.">16:00 P.M.</option>
  <option value="17:00 P.M.">17:00 P.M.</option>
  <option value="18:00 P.M.">18:00 P.M.</option>
  <option value="19:00 P.M.">19:00 P.M.</option>
  <option value="20:00 P.M.">20:00 P.M.</option>
  <option value="21:00 P.M.">21:00 P.M.</option>
  <option value="22:00 P.M.">22:00 P.M.</option>
  <option value="23:00 P.M.">23:00 P.M.</option>
  <option value="24:00 P.M.">24:00 P.M.</option>
  <option value="01:00 A.M.">01:00 A.M.</option>
  <option value="02:00 A.M.">02:00 A.M.</option>
  <option value="03:00 A.M.">03:00 A.M.</option>
  <option value="04:00 A.M.">04:00 A.M.</option>
  <option value="05:00 A.M.">05:00 A.M.</option>
  <option value="06:00 A.M.">06:00 A.M.</option>
  <option value="07:00 A.M.">07:00 A.M.</option>
</select>
    </div>
  </div>
</div>
<hr>
<div class="row">
  <div class="col-sm-2">TEMPERATURA:<input type="text" name="tempb" class="form-control" value="<?php echo $row['tempb'] ?>"></div>
  <div class="col-sm-2">PULSO:<input type="text" name="pulsob" class="form-control" value="<?php echo $row['pulsob'] ?>"></div>
  <div class="col-sm-2">RESPIRACIÓN:<input type="text" name="respb" class="form-control" value="<?php echo $row['respb'] ?>"></div>
  <div class="col">
     <center> PRESIÓN ARTERIAL:</center>  
     <div class="row">
     <div class="col-sm-6"><input type="text" name="sistb" class="form-control" value="<?php echo $row['sistb'] ?>"></div> /
     <div class="col-sm-5"><input type="text" name="diastb" class="form-control" value="<?php echo $row['diastb'] ?>"></div>   
  </div>
</div>
<div class="col-sm-2">RIESGO DE CAIDA:<input type="text" name="caidab" class="form-control" value="<?php echo $row['caidab'] ?>"></div>
<div class="col-sm-2">NIVEL DE DOLOR:<input type="text" name="dolorb" class="form-control" value="<?php echo $row['dolorb'] ?>" ></div>
</div>
<hr>
<div class="row">
  <div class="col">SONDAS/CATETERES:<input type="text" name="sondab" class="form-control" value="<?php echo $row['sondab'] ?>"></div>
  <div class="col">ESTADO DE CONCIENCIA:<input type="text" name="edoconb" class="form-control" value="<?php echo $row['edoconb'] ?>"></div>
  <div class="col">DIETA:<input type="text" name="dietab" class="form-control" value="<?php echo $row['dietab'] ?>"></div>
  <div class="col">GLUCOSA CAPILAR:<input type="text" name="glucocab" class="form-control" value="<?php echo $row['glucocab'] ?>"></div>
</div>
<hr>
<div class="row">
  <div class="col">GLUCOCETONURIA:<input type="text" name="glucob" class="form-control" value="<?php echo $row['glucob'] ?>"></div>
  <div class="col">INSULINA:<input type="text" name="insulinab" class="form-control" value="<?php echo $row['insulinab'] ?>"></div>
  <div class="col">CANALIZACIONES:<input type="text" name="canalizab" class="form-control" value="<?php echo $row['canalizab'] ?>"></div>
</div><p>
<div class="row">
<div class="col">SOLUCIONES PARENTERALES:<textarea rows="2" name="solparenb" class="form-control"><?php echo $row['solparenb'] ?></textarea></div>
</div>
<hr>
<?php 
$select="SELECT * FROM medica_recnac WHERE id_atencion=$id_atencion and horanac='$hora'";
$result_med=$conexion->query($select);
foreach ($result_med as $row_med){
 ?>
<table class="table">
  <thead>
    <tr>
      <th scope="col" class="table-warning"><center>MEDICAMENTOS</center></th>
      <th scope="col" class="table-warning"><center>DOSIS</center></th>
      <th scope="col" class="table-warning"><center>VÍA</center></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="col-sm-8"><input type="text" name="medicab" class="form-control" value="<?php echo $row_med['medicab'] ?>" disabled></td>
      <td><input type="text" name="dosisb" class="form-control" value="<?php echo $row_med['dosisb'] ?>" disabled></td>
      <td><input type="text" name="viab" class="form-control" value="<?php echo $row_med['viab'] ?>" disabled></td>
    </tr>
  </tbody>
</table>
<?php } ?>
<hr>
<div class="thead" style="background-color: #0c675e; color: white; font-size: 20px;">
         <tr><strong><center>INGRESOS</center></strong>
</div>
<div class="row">
    
  <div class="col-3">SOLUCIÓN PARENTERAL:<div class="losInput"><input type="text" name="solparb" class="form-control" placeholder="0"value="<?php echo $row['solparb'] ?>"></div></div>
  <div class="col-2">MEDICAMENTOS I.V.<div class="losInput"><input type="text" name="ingmedb" class="form-control" placeholder="0"value="<?php echo $row['ingmedb'] ?>"></div></div>
  <div class="col">VIA ORAL:<div class="losInput"><input type="text" name="viaoralb" class="form-control" placeholder="0"value="<?php echo $row['viaoralb'] ?>"></div></div>
  <div class="col">OTROS:<div class="losInput"><input type="text" name="otrosb" class="form-control" placeholder="0"value="<?php echo $row['otrosb'] ?>"></div></div>
<div class="col-3">LECHE MATERNA/FORMULA:<div class="losInput"><input type="text" name="formb" class="form-control" placeholder="0"value="<?php echo $row['formb'] ?>"></div></div>
   <div class="col">TOTAL: <div class="inputTotal"><input type="text" name="ingtotb" class="form-control" disabled="" placeholder="0"value="<?php echo $row['ingtotb'] ?>"></div></div>
</div>
<hr>
<div class="thead" style="background-color: #0c675e; color: white; font-size: 20px;">
         <tr><strong><center>EGRESOS</center></strong>
</div>
<div class="row">
  <div class="col-sm-1">DIURESIS:<div class="losInput2"><input type="text" name="diuresisb" class="form-control" placeholder="0" value="<?php echo $row['diuresisb'] ?>"></div></div>
  <div class="col">EVACUACIONES:<div class="losInput2"><input type="text" name="evacuab" class="form-control" placeholder="0" value="<?php echo $row['evacuab'] ?>"></div></div>
  <div class="col">VÓMITO_<div class="losInput2"><input type="text" name="vomitob" class="form-control" placeholder="0" value="<?php echo $row['vomitob'] ?>"></div></div>
  <div class="col">CANALIZACIONES:<div class="losInput2"><input type="text" name="canalb" class="form-control" placeholder="0" value="<?php echo $row['canalb'] ?>"></div></div>
 <div class="col">PERDIDAS INSENSIBLES:<div class="losInput2"><input type="text" name="perinsenb" class="form-control" placeholder="0" value="<?php echo $row['perinsenb'] ?>"></div></div>
   <div class="col">TOTAL:<div class="inputTotal2"><input type="text" name="egtotb" class="form-control" disabled="" placeholder="0" value="<?php echo $row['egtotb'] ?>"></div></div>
</div>
<hr>
<div class="row">
  <div class="col">CUIDADOS DE ENFERMERIA:<input type="text" name="cuideb" class="form-control" value="<?php echo $row['cuideb'] ?>"></div>
  <div class="col">NOTAS DE ENFERMERIA:<textarea rows="5" name="noteb" class="form-control"><?php echo $row['noteb'] ?></textarea>
  </div>
</div>
<hr>
<div class="form-group col-12">
<center><button type="submit" name="guardar" class="btn btn-primary">FIRMAR</button>
<button type="button" class="btn btn-danger" onclick="history.back()">CANCELAR</button></center>
</div>
<br>
<br>
</form>
</div>
<?php } ?>
   <!--TERMINO DE NOTA DE EVOLUCION-->
 <?php 
  if (isset($_POST['guardar'])) {

        $fechab    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fechab"], ENT_QUOTES)));
        $horab    = mysqli_real_escape_string($conexion, (strip_tags($_POST["horab"], ENT_QUOTES)));
        $tempb  = mysqli_real_escape_string($conexion, (strip_tags($_POST["tempb"], ENT_QUOTES)));
        $respb    = mysqli_real_escape_string($conexion, (strip_tags($_POST["respb"], ENT_QUOTES))); 

        $sistb    = mysqli_real_escape_string($conexion, (strip_tags($_POST["sistb"], ENT_QUOTES)));
        $diastb    = mysqli_real_escape_string($conexion, (strip_tags($_POST["diastb"], ENT_QUOTES)));
        $caidab  = mysqli_real_escape_string($conexion, (strip_tags($_POST["caidab"], ENT_QUOTES)));
        $dolorb    = mysqli_real_escape_string($conexion, (strip_tags($_POST["dolorb"], ENT_QUOTES)));

        $sondab    = mysqli_real_escape_string($conexion, (strip_tags($_POST["sondab"], ENT_QUOTES)));
        $edoconb    = mysqli_real_escape_string($conexion, (strip_tags($_POST["edoconb"], ENT_QUOTES)));
        $dietab  = mysqli_real_escape_string($conexion, (strip_tags($_POST["dietab"], ENT_QUOTES)));
        $glucocab    = mysqli_real_escape_string($conexion, (strip_tags($_POST["glucocab"], ENT_QUOTES)));

        $glucob    = mysqli_real_escape_string($conexion, (strip_tags($_POST["glucob"], ENT_QUOTES)));
        $insulinab    = mysqli_real_escape_string($conexion, (strip_tags($_POST["insulinab"], ENT_QUOTES)));
        $canalizab  = mysqli_real_escape_string($conexion, (strip_tags($_POST["canalizab"], ENT_QUOTES)));
        $solparenb    = mysqli_real_escape_string($conexion, (strip_tags($_POST["solparenb"], ENT_QUOTES)));

        $solparb    = mysqli_real_escape_string($conexion, (strip_tags($_POST["solparb"], ENT_QUOTES)));
        $ingmedb    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ingmedb"], ENT_QUOTES)));
        $viaoralb  = mysqli_real_escape_string($conexion, (strip_tags($_POST["viaoralb"], ENT_QUOTES)));
        $otrosb    = mysqli_real_escape_string($conexion, (strip_tags($_POST["otrosb"], ENT_QUOTES)));

        $formb    = mysqli_real_escape_string($conexion, (strip_tags($_POST["formb"], ENT_QUOTES)));
        $ingtotb    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ingtotb"], ENT_QUOTES)));
        $diuresisb  = mysqli_real_escape_string($conexion, (strip_tags($_POST["diuresisb"], ENT_QUOTES)));
        $evacuab    = mysqli_real_escape_string($conexion, (strip_tags($_POST["evacuab"], ENT_QUOTES)));

        $vomitob    = mysqli_real_escape_string($conexion, (strip_tags($_POST["vomitob"], ENT_QUOTES)));
        $canalb    = mysqli_real_escape_string($conexion, (strip_tags($_POST["canalb"], ENT_QUOTES)));
        $perinsenb  = mysqli_real_escape_string($conexion, (strip_tags($_POST["perinsenb"], ENT_QUOTES)));
        $egtotb    = mysqli_real_escape_string($conexion, (strip_tags($_POST["egtotb"], ENT_QUOTES)));

        $baltotb    = mysqli_real_escape_string($conexion, (strip_tags($_POST["baltotb"], ENT_QUOTES)));
        $cuideb    = mysqli_real_escape_string($conexion, (strip_tags($_POST["cuideb"], ENT_QUOTES)));
        $noteb  = mysqli_real_escape_string($conexion, (strip_tags($_POST["noteb"], ENT_QUOTES)));
        $fechasistb    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fechasistb"], ENT_QUOTES)));

       /* $medicab    = mysqli_real_escape_string($conexion, (strip_tags($_POST["medicab"], ENT_QUOTES)));
        $dosisb  = mysqli_real_escape_string($conexion, (strip_tags($_POST["dosisb"], ENT_QUOTES)));
        $viab    = mysqli_real_escape_string($conexion, (strip_tags($_POST["viab"], ENT_QUOTES)));*/

        

date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

        $sql2 = "UPDATE iden_recnac SET fechab='$fechab', horab='$horab', tempb ='$tempb', respb='$respb',sistb='$sistb', diastb='$diastb', caidab ='$caidab', dolorb='$dolorb',sondab='$sondab', edoconb='$edoconb', dietab ='$dietab', glucocab='$glucocab',glucob='$glucob', insulinab='$insulinab', canalizab ='$canalizab', solparenb='$solparenb',solparb='$solparb', ingmedb='$ingmedb', viaoralb ='$viaoralb', otrosb='$otrosb',formb='$formb', ingtotb='$ingtotb', diuresisb ='$diuresisb', evacuab='$evacuab',vomitob='$vomitob', canalb='$canalb', perinsenb ='$perinsenb', egtotb='$egtotb',baltotb='$baltotb', cuideb='$cuideb', noteb ='$noteb', fechasistb='$fechasistb' WHERE id_rec_nac=$id_rec_nac";
        $result = $conexion->query($sql2);
       
/*$select="SELECT * FROM medica_recnac WHERE id_atencion=$id_atencion and horanac='$hora'";
$result_med=$conexion->query($select);
foreach ($result_med as $row_medi){
  $medica=$row_medi['medicab'];
  $dosis=$row_medi['dosisb'];
  $via=$row_medi['viab'];
  if($medica !== $medicab || $dosis !== $dosisb || $via !== $viab){
    $sql2 = "UPDATE medica_recnac SET medicab='$medicab', dosisb='$dosisb', viab='$viab'";
        $result = $conexion->query($sql2);
      }
} */

        echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> DATO EDITADO CORRECTAMENTE...</p>";
        echo '<script type="text/javascript">window.location ="../pdf/vista_pdf.php"</script>';
      }
  ?>
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