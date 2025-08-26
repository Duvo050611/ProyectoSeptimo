<?php
session_start();
include "../../conexionbd.php";
include("../header_medico.php");
if (isset($_GET['id_atencion'])) { //si existe el id mandado por metodo GET, se hara la consulta donde el id debe ser igual al id mandado por el metodo GET
    $resultado = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp  WHERE id_atencion=" . $_GET['id_atencion']) or die($conexion->error);
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


    <title>DATOS NURGEN </title>
    
<style type="text/css">
    #contenido{
        display: none;
    }
</style>

</head>
<body>

<div class="col-sm-12">
    <div class="container">
        <div class="row">
            <div class="col">
                
<div class="thead col col-12" style="background-color: #0c675e; color: white; font-size: 26px; align-content: center;"><strong>CONSULTA DE URGENCIAS</strong></div>
                <hr>
<?php

include "../../conexionbd.php";

$resultado1 = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE id_atencion=" . $_GET['id_atencion']) or die($conexion->error);

?>
  <?php
                    while ($f1 = mysqli_fetch_array($resultado1)) {
                        $sexo=$f1['sexo'];

                        ?>
                      <div class="container">
  <div class="row">
    <div class="col-sm-5">
        EXPEDIENTE: <td><strong><?php echo $f1['Id_exp']; ?></strong></td>
    </div>
    <div class="col-sm">
     Folio: <td><strong><?php echo $f1['id_atencion']; ?></strong></td>
    </div>
    <div class="col-sm">
    </div>
  </div>
</div>
<div class="container">      
                           
  <div class="row">
    <div class="col-sm-5">
       PACIENTE:
<td><strong><?php echo $f1['papell']; ?></strong></td>
                            <td><strong><?php echo $f1['sapell']; ?></strong></td>
                            <td><strong><?php echo $f1['nom_pac']; ?></strong></td>
    </div>

    <div class="col-sm-5">
     FECHA DE NACIMIENTO:<strong> <?php  $date = date_create($f[5]);
 echo date_format($date,"d/m/Y");?></strong>
    </div>
    <div class="col-sm">
       EDAD:
                            <td><strong><?php echo $f1['edad']; ?></strong></td>
    </div>
  </div>
</div>
<hr>
                        <?php  
                    }
                    ?>             
                        
            </div>
            <div class="col-sm-3">
                <?php
                date_default_timezone_set('America/Guatemala');
                $fecha_actual = date("d-m-Y H:i:s");
                ?>
                
                <div class="form-group">
                    <label for="fecha">FECHA Y HORA:</label>
                    <input type="datetime" name="fec_hc" value="<?= $fecha_actual ?>" class="form-control" disabled>
                </div>
            </div>
        </div>



  
      <div class="container">  <!--INICIO DE CONSULTA DE URGENCIAS-->


    <?php 
    $id_atencion = $_GET['id_atencion']; 

    ?>
<form action="" method="POST">


<?php

include "../../conexionbd.php";

$resultado2=$conexion->query("select * from triage WHERE id_atencion=" . $_GET['id_atencion'].' order by id_triage DESC limit 1') or die($conexion->error);
?>

<div class="thead" style="background-color: #0c675e; color: white; font-size: 20px;">
       <tr><strong><center>SIGNOS VITALES</center></strong>
  </div>
<p>

            <div class="container"> 
  <div class="row">
    
      <?php
                    while ($f2 = mysqli_fetch_array($resultado2)) {

                        ?>
    
  <div class="col-sm-2"><br><center>PRESIÓN ARTERIAL:</center>
  <div class="row">
  <div class="col"><input type="text" class="form-control" value="<?php echo $f2['p_sistolica'];?>" disabled></div> /
  <div class="col"><input type="text" class="form-control" value="<?php echo $f2['p_diastolica'];?>" disabled></div>
 
</div>
    </div>
    <div class="col-sm-2">
      FRECUENCIA CARDIACA:<input type="text" class="form-control" value="<?php echo $f2['f_card'];?>" disabled>
    </div>
    <div class="col-sm-2">
      FRECUENCIA RESPIRATORIA:<input type="text" class="form-control" value="<?php echo $f2['f_resp'];?>" disabled>
    </div>
    <div class="col-sm-1">
     <br>TEMP:<input type="text" class="form-control" value="<?php echo $f2['temp'];?>" disabled>
    </div>
    <div class="col-sm-2">
     SATURACIÓN DE OXÍGENO:<input type="text"  class="form-control" value="<?php echo $f2['sat_oxigeno'];?>" disabled>
    </div>
    <div class="col-sm-1">
     <br>PESO:<input type="text" class="form-control" value="<?php echo $f2['peso'];?>" disabled>
    </div>
    <div class="col-sm-1">
     <br>TALLA:<input type="text" class="form-control" value="<?php echo $f2['talla'];?>" disabled>
    </div>
    <div class="col-sm-1">
    ESCALA EVA:<input type="text" class="form-control" value="<?php echo $f2['niv_dolor'];?>" disabled>
    </div>
  </div>
</div>
<?php 
}
 $id_c_urgen=$_GET['id_c_urgen'];
$id_atencion=$_GET['id_atencion'];
$select="SELECT * FROM dat_c_urgen where id_c_urgen=$id_c_urgen";
$result_triage=$conexion->query($select);
while ($row=$result_triage->fetch_assoc()){
 ?>

<br>
  <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>MOTIVO DE CONSULTA:</strong></label>
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="motcon_cu" style="text-transform:uppercase;"><?php echo $row['motcon_cu'] ?></textarea>
  </div>
    

   
  <div class="thead" style="background-color: #0c675e; color: white; font-size: 20px;">
       <tr><strong><center>ANTECEDENTES HEREDO FAMILIARES</center></strong>
  </div>
<p>
  <div class="row">
    <div class="col-sm">
     <strong><p>DIABETES:</p></strong>
     <div class="form-check">
<?php if ($row['diab_pa']=="SI") { ?>
  <input class="form-check-input" type="checkbox" id="PADRE" value="SI" name="diab_pa" checked>
<?php }else{?>
  <input class="form-check-input" type="checkbox" id="PADRE" value="SI" name="diab_pa">
<?php } ?>

  <label class="form-check-label" for="PADRE">
    PADRE
  </label>
</div>

<div class="form-check">
    <?php if ($row['diab_ma']=="SI") { ?>
  <input class="form-check-input" type="checkbox" id="MADRE" value="SI" name="diab_ma" checked>
<?php }else{?>
  <input class="form-check-input" type="checkbox" id="MADRE" value="SI" name="diab_ma">
<?php } ?>

  <label class="form-check-label" for="MADRE">
    MADRE
  </label>
</div>
<div class="form-check">
    <?php if ($row['diab_ab']=="SI") { ?>
  <input class="form-check-input" type="checkbox" id="ABUELOS" value="SI" name="diab_ab" checked>
<?php }else{?>
  <input class="form-check-input" type="checkbox" id="ABUELOS" value="SI" name="diab_ab">
<?php } ?>

  <label class="form-check-label" for="ABUELOS">
    ABUELOS
  </label>
</div>

    </div>
    <div class="col-sm">
     
       <strong><p>HIPERTENSIÓN:</p></strong>
     <div class="form-check">
<?php if ($row['hip_pa']=="SI") { ?>
  <input class="form-check-input" type="checkbox" id="PADRE2" value="SI" name="hip_pa" checked>
<?php }else{?>
  <input class="form-check-input" type="checkbox" id="PADRE2" value="SI" name="hip_pa">
<?php } ?>

  <label class="form-check-label" for="PADRE2">
    PADRE
  </label>
</div>

<div class="form-check">
<?php if ($row['hip_ma']=="SI") { ?>
<input class="form-check-input" type="checkbox" id="MADRE2" value="SI" name="hip_ma" checked>
<?php }else{?>
<input class="form-check-input" type="checkbox" id="MADRE2" value="SI" name="hip_ma">
<?php } ?>
  
  <label class="form-check-label" for="MADRE2">
    MADRE
  </label>
</div>
<div class="form-check">
<?php if ($row['hip_ab']=="SI") { ?>
<input class="form-check-input" type="checkbox" id="ABUELOS2" value="SI" name="hip_ab" checked>
<?php }else{?>
<input class="form-check-input" type="checkbox" id="ABUELOS2" value="SI" name="hip_ab">
<?php } ?>
  
  <label class="form-check-label" for="ABUELOS2">
    ABUELOS
  </label>
</div>

    </div>
    <div class="col-sm">
     
  <strong><p>CÁNCER:</p></strong>
     <div class="form-check">
<?php if ($row['can_pa']=="SI") { ?>
<input class="form-check-input" type="checkbox" id="PADRE3" value="SI" name="can_pa" checked>
<?php }else{?>
<input class="form-check-input" type="checkbox" id="PADRE3" value="SI" name="can_pa">
<?php } ?>
  
  <label class="form-check-label" for="PADRE3">
    PADRE
  </label>
</div>

<div class="form-check">
<?php if ($row['can_ma']=="SI") { ?>
<input class="form-check-input" type="checkbox" id="MADRE3" value="SI" name="can_ma" checked>
<?php }else{?>
<input class="form-check-input" type="checkbox" id="MADRE3" value="SI" name="can_ma">
<?php } ?>
  
  <label class="form-check-label" for="MADRE3">
    MADRE
  </label>
</div>
<div class="form-check">
<?php if ($row['can_ab']=="SI") { ?>
<input class="form-check-input" type="checkbox" id="ABUELOS3" value="SI" name="can_ab" checked>
<?php }else{?>
<input class="form-check-input" type="checkbox" id="ABUELOS3" value="SI" name="can_ab">
<?php } ?>
  
  <label class="form-check-label" for="ABUELOS3">
    ABUELOS
  </label>
</div>

    </div>
  </div>

<hr>
  

  <div class="thead" style="background-color: #0c675e; color: white; font-size: 20px;">
       <tr><strong><center>ANTECEDENTES PERSONALES NO PATOLÓGICOS</center></strong>
  </div>
<p>
<div class="row">

   <div class="col-sm">
      <div class="form-check">
<?php if ($row['adic_cu']=="DROGAS") { ?>
<input class="form-check-input" type="checkbox" name="adic_cu" id="adic_cu" value="DROGAS" checked>
<?php }else{?>
<input class="form-check-input" type="checkbox" name="adic_cu" id="adic_cu" value="DROGAS">
<?php } ?>
        
        <label class="form-check-label" for="flexRadioDefault1">
         <h6>DROGAS</h6>
         </label>
      </div>
  </div>


   <div class="col-sm">
      <div class="form-check">
<?php if ($row['tab_cu']=="TABACO") { ?>
<input class="form-check-input" type="checkbox" name="tab_cu" id="tab_cu" value="TABACO" checked>
<?php }else{?>
<input class="form-check-input" type="checkbox" name="tab_cu" id="tab_cu" value="TABACO">
<?php } ?>
        
        <label class="form-check-label" for="tab_cu">
        <h6>TABACO</h6>
        </label>
      </div>
   </div>

    <div class="col-sm">
      <div class="form-check">
<?php if ($row['alco_cu']=="ALCOHOL") { ?>
        <input class="form-check-input" type="checkbox" name="alco_cu" id="alco_cu" value="ALCOHOL" checked>
<?php }else{?>
        <input class="form-check-input" type="checkbox" name="alco_cu" id="alco_cu" value="ALCOHOL">
<?php } ?>

        <label class="form-check-label" for="alco_cu">
        <h6>ALCOHOL</h6>
        </label>
      </div>
    </div>

    <div class="col-sm">
      <div class="form-check"> 
<?php if ($row['otro_cu']=="OTROS") { ?>
        <input class="form-check-input" type="checkbox" name="otro_cu" id="flexRadioDefault1" value="OTROS" checked>
<?php }else{?>
        <input class="form-check-input" type="checkbox" name="otro_cu" id="flexRadioDefault1" value="OTROS">
<?php } ?> 

        <label class="form-check-label" for="flexRadioDefault1">
        <h6>OTRAS</h6>
        </label>
      </div>
    </div>
</div>

<hr>
  

<div class="thead" style="background-color: #0c675e; color: white; font-size: 20px;">
       <tr><strong><center>ANTECEDENTES PERSONALES PATOLÓGICOS</center></strong>
  </div>
<p>
  <div class="row">
    <div class="col-sm">
      <div class="form-check">
<?php if ($row['quir_cu']=="SI") { ?>
<input class="form-check-input" type="checkbox" name="quir_cu" id="flexRadioDefault2" value="SI" checked>
<?php }else{?>
<input class="form-check-input" type="checkbox" name="quir_cu" id="flexRadioDefault2" value="SI">
<?php } ?>
  
  <label class="form-check-label" for="flexRadioDefault2">
    QUIRÚRGICOS
  </label>
</div>
    </div>
    <div class="col-sm">
      <div class="form-check">
<?php if ($row['trau_cu']=="SI") { ?>
  <input class="form-check-input" type="checkbox" name="trau_cu" id="flexRadioDefault3" value="SI" checked>
<?php }else{?>
  <input class="form-check-input" type="checkbox" name="trau_cu" id="flexRadioDefault3" value="SI">
<?php } ?>

  <label class="form-check-label" for="flexRadioDefault3">
    TRAUMÁTICOS
  </label>
</div>
    </div>
    <div class="col-sm">
     <div class="form-check">
<?php if ($row['trans_cu']=="SI") { ?>
  <input class="form-check-input" type="checkbox" name="trans_cu" id="flexRadioDefault4" value="SI" checked>
<?php }else{?>
  <input class="form-check-input" type="checkbox" name="trans_cu" id="flexRadioDefault4" value="SI">
<?php } ?>

  <label class="form-check-label" for="flexRadioDefault4">
    TRANSFUSIONALES
  </label>
</div>
    </div>

<div class="col-sm">
     <div class="form-check">
<?php if ($row['aler_cu']=="SI") { ?>
  <input class="form-check-input" type="checkbox" name="aler_cu" id="alergicos" value="SI" checked>
<?php }else{?>
  <input class="form-check-input" type="checkbox" name="aler_cu" id="alergicos" value="SI">
<?php } ?>

  <label class="form-check-label" for="alergicos">
    ALERGICOS
  </label>
</div>
    </div>

  </div>
  <hr>
  <div class="row">
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>PADECIMIENTO ACTUAL:</strong></label>
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="pad_cu"><?php echo $row['pad_cu'] ?></textarea>
  </div>

    </div>
    <div class="col-sm">
     <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>EXPLORACIÓN FÍSICA</strong></label>
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="exp_cu"><?php echo $row['exp_cu'] ?></textarea>
  </div>
    </div>
</div>
    
<div class="row">
 <div class="col-8">
    <div class="form-group">
    <label for="id_cie_10"><strong>DIAGNÓSTICO:</strong></label>
    <select name="diag_cu" class="form-control" data-live-search="true" id="mibuscador" style="width : 100%; heigth : 100%">
     <option value="<?php echo $row['diag_cu'] ?>"><?php echo $row['diag_cu'] ?></option>
     <option></option>
 <?php } ?> 
<?php
include "../../conexionbd.php";
$sql_diag="SELECT * FROM cat_diag ";
$result_diag=$conexion->query($sql_diag);
while($row=$result_diag->fetch_assoc()){
    echo "<option value='" . $row['diagnostico'] . "'>" . $row['diagnostico'] . "</option>";
}
 ?></select>
                                </div>
                            </div>
                        </div>

                   
                                <div class="row">
                                    <div class="col">
                                        <hr>
                                        
  <div class="thead" style="background-color: #0c675e; color: white; font-size: 20px;">
       <tr><strong><center>ANTECEDENTES GINECO-OBSTETRÍCOS </center></strong>
  </div>

                                        
                                        <p><label>

                                        <?php
                                        include "../../conexionbd.php";

$resultado1 = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE id_atencion=" . $_GET['id_atencion']) or die($conexion->error);

while ($f1 = mysqli_fetch_array($resultado1)) {

    $sexo=$f1['sexo'];
}
if($sexo=='MUJER'){ 
 $id_c_urgen=$_GET['id_c_urgen'];
$id_atencion=$_GET['id_atencion'];
$select="SELECT * FROM dat_c_urgen where id_c_urgen=$id_c_urgen";
$result_triage=$conexion->query($select);
while ($row=$result_triage->fetch_assoc()){
    ?>
                                             <div class="row">
                                                <div class="col-sm">
                             <label for="hc_men">MENARCA:</label><br>
                                <input type="text" name="hc_men" placeholder="Menarca" value="<?php echo $row['hc_men'] ?>" onkeypress="return SoloNumeros(event);" maxlength="2"  class="form-control"><br>
                        </div>
                        <div class="col-sm">
                             <label for="hc_ritmo">RITMO:</label><br>
                                <input type="text" name="hc_ritmo" value="<?php echo $row['hc_ritmo'] ?>" placeholder="Ritmo" onkeypress="return SoloNumeros(event);" maxlength="2" class="form-control"><br>
                        </div>
                                                  <div class="col-sm">
                                                        <label for="hc_ges">GESTAS:</label><br>
                            <input type="text" name="gestas_cu" placeholder="Gestas" value="<?php echo $row['gestas_cu'] ?>" onkeypress="return SoloNumeros(event);" maxlength="2" 
                                   class="form-control"><br>
                                                  </div>
                                                  <div class="col-sm">
                                                        <label for="hc_par">PARTOS:</label><br>
                                                        <input type="text" name="partos_cu" placeholder="Partos" value="<?php echo $row['partos_cu'] ?>" onkeypress="return SoloNumeros(event);"
                                                       maxlength="2" class="form-control"><br>
                                                  </div>
                                                  <div class="col-sm">
                                                        <label for="hc_ces">CESÁREAS:</label><br>
                                                        <input type="text" name="ces_cu" placeholder="Cesareas" value="<?php echo $row['ces_cu'] ?>" onkeypress="return SoloNumeros(event);"
                                                        maxlength="2" 
                                                        class="form-control"> <br>
                                                  </div>
                                                  <div class="col-sm">
                                                    <label for="hc_abo">ABORTOS:</label><br>
                                                    <input type="text" name="abo_cu" placeholder="Abortos" value="<?php echo $row['abo_cu'] ?>" onkeypress="return SoloNumeros(event);"
                                                        maxlength="2" 
                                                        class="form-control"><br> 
                                                  </div>
                                                    <div class="col-sm">
                                                    <label for="hc_abo">FECHA DE ÚLTIMA REGLA</label><br>
                                                    <input type="date" name="fecha_fur" value="<?php echo $row['fecha_fur'] ?>"
                                                        class="form-control"><br> 
                                                  </div>
                                             </div> 

                                             <?php } }else{ 
$id_c_urgen=$_GET['id_c_urgen'];
$id_atencion=$_GET['id_atencion'];
$select="SELECT * FROM dat_c_urgen where id_c_urgen=$id_c_urgen";
$result_triage=$conexion->query($select);
while ($row=$result_triage->fetch_assoc()){
                                                ?> 
                                                <div class="row">
                        <div class="col-sm">
                             <label for="hc_desc_hom">DESCRIPCIÓN:</label><br>
                                <textarea name="hc_desc_hom" value="<?php echo $row['hc_desc_hom'] ?>" class="form-control"></textarea><br>
                        </div>
                    </div>  

                                             <?php }} ?> 
                                    
                                </div>
                            </div>
<?php 
$id_c_urgen=$_GET['id_c_urgen'];
$id_atencion=$_GET['id_atencion'];
$select="SELECT * FROM dat_c_urgen where id_c_urgen=$id_c_urgen";
$result_triage=$conexion->query($select);
while ($row=$result_triage->fetch_assoc()){
 ?>                     
 <div class="row">
    
    <div class="col-sm">
     <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>PROCEDIMIENTOS:</strong></label>
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="proc_cu"><?php echo $row['proc_cu'] ?></textarea>
  </div>
    </div>
</div>

 <div class="row">
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>MEDICAMENTOS:</strong></label>
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="med_cu"><?php echo $row['med_cu'] ?></textarea>
  </div>

    </div>
    <div class="col-sm">
     <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>ANÁLISIS Y PRONÓSTICOS:</strong></label>
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="anproc_cu"><?php echo $row['anproc_cu'] ?></textarea>
  </div>
    </div>
</div>

 <div class="row">
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>TRATAMIENTO Y PLAN:</strong></label>
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="trat_cu"><?php echo $row['trat_cu'] ?></textarea>
  </div>

    </div>
    <div class="col-sm">
     <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>OBSERVACIÓNES Y ESTUDIOS:</strong></label>
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="do_cu"><?php echo $row['do_cu'] ?></textarea>
  </div>
    </div>
</div>

 <div class="row">
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>DISCAPACIDADES:</strong></label>
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="dis_cu"><?php echo $row['dis_cu'] ?></textarea>
  </div>

    </div>
    <div class="col-sm">
     <div class="col-12">
                                <div class="form-group">
                                    <label for="id_cie_10"><strong>DESTINO:</strong></label>
                                    <select name="dest_cu" class="form-control" onchange="mostrar(this.value);" required="">
                        <option value="<?php echo $row['dest_cu'] ?>"><?php echo $row['dest_cu'] ?></option>
                        <option></option>
                        <option value="">SELECCIONAR DESTINO</option>
                        <option value="QUIROFANO">QUIRÓFANO</option>
                        <option value="HOSPITALIZACION">HOSPITALIZACIÓN</option>
                        <option value="EGRESO DE URGENCIAS">EGRESO DE URGENCIAS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
    </div>
<?php } ?>
<!-- 
 <div class="row" id="contenido">

<div class="thead" style="background-color: seagreen; color: white; font-size: 20px;">
       <tr><strong><center>DATOS QUIRÚRGICOS</center></strong>
</div>




<div class="col-sm">

  
  <strong><p>TIPO DE INTERVENCIÓN:</p></strong>
     <div class="form-check">
  <input class="form-check-input" type="radio" name="tipo_intervenciony" value="URGENCIA" name="tipocir">
  <label class="form-check-label"  for="URGENCIAS">
    URGENCIA
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio"  name="tipo_intervenciony" value="ELECTIVA" name="tipocir">
  <label class="form-check-label" for="URGENCIAS">
    ELECTIVA
  </label>
</div>
    </div>

  <hr>
   <div class="row">
       <div class="col-sm">
         <label for="exampleFormControlTextarea1"><strong>MATERIALES E INSTRUMENTAL NECESARIO:</strong></label>
       <textarea class="form-control" name="inst_necesarioy" id="exampleFormControlTextarea1"  rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
       </div>
       <div class="col-sm">
         <label for="exampleFormControlTextarea5"><strong>OBSERVACIONES:</strong></label>
       <textarea class="form-control" name="medmat_necesarioy" id="exampleFormControlTextarea5"  rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
       </div>
       <div class="col-sm">
        <div class="form-group">
          <label for="exampleFormControlInput1"><strong>NOMBRE DEL CIRUJANO / JEFE DE SERVICIO:</strong></label>
          <input type="text" class="form-control" name="nom_jefe_servy" id="exampleFormControlInput1"  placeholder="Nombre" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
        </div>
       </div>
     </div>
     

  
 
<div class="form-group">
        <label for="id_cie_10"><strong> DIAGNÓSTICO PREOPERATORIO:</strong></label>
        <input type="text" class="form-control" name="diag_preopy" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                                </div>




<hr id="contenido">

<div class="row">

  <div class="col"><strong><center>SANGRE EN</center></strong><hr>
    
  <div class="row">
    <div class="col-sm">
      <label for="exampleFormControlInput1">QUIROFANO:</label>
    <input type="text" class="form-control" name="quirofanoy"  id="exampleFormControlInput1" placeholder="ml." style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    </div>
    <div class="col-sm">
      <label for="exampleFormControlInput1">RESERVA:</label>
    <input type="text" class="form-control" name="reservay" id="exampleFormControlInput1"  placeholder="ml." style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    </div>
  </div>
<hr >
  </div>
  <div class="col"><strong><center>ANESTESIA PROPUESTA</center></strong><hr>
  <div class="row">
    <div class="col-sm-4">
    <label for="exampleFormControlInput1">LOCAL:</label>
    <input type="text" class="form-control" name="localy" id="exampleFormControlInput1" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"> 
    </div>
    <div class="col-sm-4">
      <label for="exampleFormControlInput1">REGIONAL:</label>
    <input type="text" class="form-control" name="regionaly" id="exampleFormControlInput1"  style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"> 
    </div>
    <div class="col-sm-4">
      <label for="exampleFormControlInput1">GENERAL:</label>
    <input type="text" class="form-control" name="generaly" id="exampleFormControlInput1"  style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    </div>
  </div>
  <hr>
  </div>

  </div>
  <div class="col"><hr>

<div class="row">
   
    <?php
$resultado1 = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE id_atencion=" . $_GET['id_atencion']) or die($conexion->error);
?>
  <?php
                    while ($f1 = mysqli_fetch_array($resultado1)) {

                        ?>
    <div class="col-sm">
      <label for="exampleFormControlInput1">GRUPO Y FACTOR RH SANGUÍNEO:</label>
<input type="text" class="form-control" name="tipo_sangrey" id="exampleFormControlInput1" value="<?php echo $f1['tip_san']?>"disabled>

    </div>
    <?php
}
?>
  </div>
<hr>
  </div>

</div>

<br>

  

<hr id="contenido">
<strong><center id="contenido">PROGRAMACIÓN EN QUIROFANO</center></strong><br>-->


 <!--TERMINO DE NOTA MEDICA div container-->
    </div>
<hr>
                        <center><div class="form-group col-9">
                            <button type="submit" name="guardar" class="btn btn-primary">FIRMAR</button>
                            <button type="button" class="btn btn-danger" onclick="history.back()">CANCELAR</button>
                        </div></center>

                        <br>
                        </form>
</div>
 <?php 
  if (isset($_POST['guardar'])) {

        $diab_pa    = mysqli_real_escape_string($conexion, (strip_tags($_POST["diab_pa"], ENT_QUOTES)));
        $diab_ma    = mysqli_real_escape_string($conexion, (strip_tags($_POST["diab_ma"], ENT_QUOTES)));
        $diab_ab  = mysqli_real_escape_string($conexion, (strip_tags($_POST["diab_ab"], ENT_QUOTES)));
        $hip_pa    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hip_pa"], ENT_QUOTES))); 
        $hip_ma    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hip_ma"], ENT_QUOTES)));

        $hip_ab    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hip_ab"], ENT_QUOTES)));
        $can_pa    = mysqli_real_escape_string($conexion, (strip_tags($_POST["can_pa"], ENT_QUOTES)));
        $can_ma  = mysqli_real_escape_string($conexion, (strip_tags($_POST["can_ma"], ENT_QUOTES)));
        $can_ab    = mysqli_real_escape_string($conexion, (strip_tags($_POST["can_ab"], ENT_QUOTES))); 
        $motcon_cu    = mysqli_real_escape_string($conexion, (strip_tags($_POST["motcon_cu"], ENT_QUOTES)));

        $trau_cu    = mysqli_real_escape_string($conexion, (strip_tags($_POST["trau_cu"], ENT_QUOTES)));
        $trans_cu    = mysqli_real_escape_string($conexion, (strip_tags($_POST["trans_cu"], ENT_QUOTES)));
        $adic_cu  = mysqli_real_escape_string($conexion, (strip_tags($_POST["adic_cu"], ENT_QUOTES)));
        $tab_cu    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tab_cu"], ENT_QUOTES))); 
        $alco_cu    = mysqli_real_escape_string($conexion, (strip_tags($_POST["alco_cu"], ENT_QUOTES)));

        $otro_cu    = mysqli_real_escape_string($conexion, (strip_tags($_POST["otro_cu"], ENT_QUOTES)));
        $quir_cu    = mysqli_real_escape_string($conexion, (strip_tags($_POST["quir_cu"], ENT_QUOTES)));
        $aler_cu  = mysqli_real_escape_string($conexion, (strip_tags($_POST["aler_cu"], ENT_QUOTES)));
        $pad_cu    = mysqli_real_escape_string($conexion, (strip_tags($_POST["pad_cu"], ENT_QUOTES))); 
        $exp_cu    = mysqli_real_escape_string($conexion, (strip_tags($_POST["exp_cu"], ENT_QUOTES)));

        $diag_cu    = mysqli_real_escape_string($conexion, (strip_tags($_POST["diag_cu"], ENT_QUOTES)));
        $hc_men    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hc_men"], ENT_QUOTES)));
        $hc_ritmo  = mysqli_real_escape_string($conexion, (strip_tags($_POST["hc_ritmo"], ENT_QUOTES)));
        $gestas_cu    = mysqli_real_escape_string($conexion, (strip_tags($_POST["gestas_cu"], ENT_QUOTES))); 
        $partos_cu    = mysqli_real_escape_string($conexion, (strip_tags($_POST["partos_cu"], ENT_QUOTES)));

        $ces_cu    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ces_cu"], ENT_QUOTES)));
        $abo_cu    = mysqli_real_escape_string($conexion, (strip_tags($_POST["abo_cu"], ENT_QUOTES)));
        $fecha_fur  = mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha_fur"], ENT_QUOTES)));
        $hc_desc_hom    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hc_desc_hom"], ENT_QUOTES))); 
        $proc_cu    = mysqli_real_escape_string($conexion, (strip_tags($_POST["proc_cu"], ENT_QUOTES)));

        $med_cu    = mysqli_real_escape_string($conexion, (strip_tags($_POST["med_cu"], ENT_QUOTES)));
        $anproc_cu    = mysqli_real_escape_string($conexion, (strip_tags($_POST["anproc_cu"], ENT_QUOTES)));
        $trat_cu  = mysqli_real_escape_string($conexion, (strip_tags($_POST["trat_cu"], ENT_QUOTES)));
        $do_cu    = mysqli_real_escape_string($conexion, (strip_tags($_POST["do_cu"], ENT_QUOTES))); 
        $dis_cu    = mysqli_real_escape_string($conexion, (strip_tags($_POST["dis_cu"], ENT_QUOTES)));

        $dest_cu    = mysqli_real_escape_string($conexion, (strip_tags($_POST["dest_cu"], ENT_QUOTES)));

date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

        $sql2 = "UPDATE dat_c_urgen SET diab_pa='$diab_pa', diab_ma='$diab_ma', diab_ab ='$diab_ab', hip_pa='$hip_pa', hip_ma='$hip_ma',hip_ab='$hip_ab', can_pa='$can_pa', can_ma ='$can_ma', can_ab='$can_ab', motcon_cu='$motcon_cu',trau_cu='$trau_cu', trans_cu='$trans_cu', adic_cu ='$adic_cu', tab_cu='$tab_cu', alco_cu='$alco_cu',otro_cu='$otro_cu', quir_cu='$quir_cu', aler_cu ='$aler_cu', pad_cu='$pad_cu', exp_cu='$exp_cu',diag_cu='$diag_cu', hc_men='$hc_men', hc_ritmo ='$hc_ritmo', gestas_cu='$gestas_cu', partos_cu='$partos_cu',ces_cu='$ces_cu', abo_cu='$abo_cu', fecha_fur ='$fecha_fur', hc_desc_hom='$hc_desc_hom', proc_cu='$proc_cu',med_cu='$med_cu', anproc_cu='$anproc_cu', trat_cu ='$trat_cu', do_cu='$do_cu', dis_cu='$dis_cu',dest_cu='$dest_cu' WHERE id_c_urgen= $id_c_urgen";
        $result = $conexion->query($sql2);

$sql = "UPDATE dat_ingreso SET area = '$dest_cu' WHERE id_atencion = $id_atencion";
      $result = $conexion->query($sql);

$diag="SELECT * FROM dat_ingreso where id_atencion=$id_atencion";
$result=$conexion->query($diag);
while ($row=$result->fetch_assoc()) {
  $motivo_atn=$row['motivo_atn'];
}
      $sql4 = "UPDATE diag_pac SET diag_paciente ='$diag_cu' WHERE diag_paciente = '$motivo_atn' and Id_exp=$id_atencion";
      $result = $conexion->query($sql4);

      $sql3 = "UPDATE dat_ingreso SET motivo_atn='$diag_cu' WHERE id_atencion = $id_atencion";
      $result = $conexion->query($sql3);

date_default_timezone_set('America/Guatemala');
 $fecha_actual = date("Y-m-d");

date_default_timezone_set('America/Guatemala');
 $hora_actual = date("H:i:s");

      if($dest_cu="EGRESO DE URGENCIAS"){
$sql2 = "UPDATE dat_ingreso SET alta_med = 'SI', fecha_alt_med = '".$fecha_actual."', hora_alt_med = '".$hora_actual."'WHERE id_atencion = $id_atencion";
      $result = $conexion->query($sql2);
}


        echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> DATO EDITADO CORRECTAMENTE...</p>";
        echo '<script type="text/javascript">window.location ="../historia_clinica/buscar_consulta_urgencias.php"</script>';
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

<script type="text/javascript">
        function mostrar(value)
        {
            if(value=="QUIROFANO" || value==true)
            {
                // habilitamos
                document.getElementById('contenido').style.display = 'block';
            }else if(value=="HOSPITALIZACION" || value=="EGRESO DE URGENCIAS"  || value==false){
                // deshabilitamos
                document.getElementById('contenido').style.display = 'none';
            }
        }
    </script>
</body>
</html>