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
  <center><strong>NOTA DE RECUPERACIÓN</strong></center><p>
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



  
      <div class="container">  <!--INICIO DE CONSULTA DE URGENCIAS-->
<form action="" method="POST">
<?php 
$id_unid_cuid=$_GET['id_unid_cuid'];
$alta="SELECT * FROM dat_unid_cuid where id_unid_cuid=$id_unid_cuid";
$result=$conexion->query($alta);
while ($row=$result->fetch_assoc()) {
?>
<div class="container">
  <div class="row">
    <div class="col-sm"><br>
     <strong>SIGNOS VITALES</strong>
    </div>
    <div class="col-sm">TA:
      <div class="row">
  <div class="col"><input type="text" name="t_sisto" value="<?php echo $row['t_sisto'] ?>" class="form-control"></div> /
  <div class="col"><input type="text" name="t_diasto" value="<?php echo $row['t_diasto'] ?>" class="form-control"></div>
 
</div>
    </div>
    <div class="col-sm">
      FC:<input type="text" name="fc_un" value="<?php echo $row['fc_un'] ?>" class="form-control">
    </div>
    <div class="col-sm">
     FR:<input type="text" name="fr_un" value="<?php echo $row['fr_un'] ?>" class="form-control">
    </div>
    <div class="col-sm">
      TEMP:<input type="text" name="temp_un" value="<?php echo $row['temp_un'] ?>" class="form-control">
    </div>
    <div class="col-sm">
      PULSO:<input type="text" name="pul_un" value="<?php echo $row['pul_un'] ?>" class="form-control">
    </div>
    <div class="col-sm">
      SAT OXÍGENO:<input type="text" name="sp_un" value="<?php echo $row['sp_un'] ?>" class="form-control">
    </div>
  </div>
</div>
  <hr>
  <div class="row">
  <div class="col"> ESTADO GENERAL:<input type="text" name="est_un" class="form-control" value="<?php echo $row['est_un'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
  <div class="col">ESTADO DE CONCIENCIA:<input type="text" name="con_un" class="form-control" value="<?php echo $row['con_un'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
</div><hr>
<div class="row">
  <div class="col">PERMEABILIDAD DE LA VÍA ÁREA:<input type="text" name="via_un" class="form-control" value="<?php echo $row['via_un'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
  <div class="col">PERMEBAILIDAD DE VENICLISIS:<input type="text" name="veni_un" class="form-control" value="<?php echo $row['veni_un'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
  <div class="col">PERMEABILIDAD DE SONDAS Y DRENAJES:<input type="text" name="son_un" class="form-control" value="<?php echo $row['son_un'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
</div><hr>
<div class="row">
  <div class="col">CONTINGENCIAS Y MANEJO:<input type="text" name="man_un" class="form-control" value="<?php echo $row['man_un'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
  <div class="col">RESUMEN DEL TRATAMIENTO:<input type="text" name="trat_un" class="form-control" value="<?php echo $row['trat_un'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
  <div class="col">DIAGNÓSTICOS FINALES Y SU FUNDAMENTO:<input type="text" name="fun_un" class="form-control" value="<?php echo $row['fun_un'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
</div><hr>
<div class="row">
  <div class="col">MOTIVO DEL EGRESO: (INCLUIR ALDRETE)<input type="text" name="mot_un" class="form-control" value="<?php echo $row['mot_un'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
  <div class="col">PROBLEMAS CLÍNICOS PENDIENTES Y EL PLAN TERAPÉUTICO DETALLADO:
    <textarea name="tera_un" class="form-control" rows="3"><?php echo $row['tera_un'] ?></textarea>
  </div>
</div>
<hr>
<div class="row">
  <div class="col-5"><strong>EGRESO DE LA UNIDAD DE RECUPERACIÓN:</strong></div>
  FECHA:<div class="col"><input type="date" name="fecha_un" value="<?php echo $row['fecha_un'] ?>" class="form-control col-9"></div>
  HORA:<div class="col"><input type="time" name="hora_un" value="<?php echo $row['hora_un'] ?>" class="form-control col-7"></div>
</div><br>
<div class="row">
  <div class="col">  <div class="form-check">
<?php if ($row['rec_un']=="HABITACION") { ?>
  <input class="form-check-input" type="radio" checked="" name="rec_un" id="hab" value="HABITACION">
<?php }else{?>
<input class="form-check-input" type="radio" name="rec_un" id="hab" value="HABITACION">
<?php } ?>
  <label class="form-check-label" for="hab">
    A: HABITACIÓN
  </label>
</div></div>
  <div class="col">  <div class="form-check">
<?php if ($row['rec_un']=="DOMICILIO") { ?>
  <input class="form-check-input" type="radio" checked="" name="rec_un" id="dom" value="DOMICILIO">
<?php }else{?>
  <input class="form-check-input" type="radio" name="rec_un" id="dom" value="DOMICILIO">
<?php } ?>
  <label class="form-check-label" for="dom">
    DOMICILIO
  </label>
</div></div>
  <div class="col">  <div class="form-check">
<?php if ($row['rec_un']=="OTRO") { ?>
  <input class="form-check-input" type="radio" checked="" name="rec_un" id="otro" value="OTRO">
<?php }else{?>
  <input class="form-check-input" type="radio" name="rec_un" id="otro" value="OTRO">
<?php } ?>
  <label class="form-check-label" for="otro">
    OTRO
  </label>
</div></div>
  (ESPECIFICAR):<div class="col-5"><input type="text" name="esp_un" value="<?php echo $row['esp_un'] ?>" class="form-control"style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
</div>
<hr>

<div class="form-group">
<center><h6><strong>NOTA DE EVOLUCIÓN POST-ANESTÉSICA DE 24 HRS. Y 48 HRS. (SI ES NECESARIO)</strong></h6></center>
<textarea name="notevo_un" class="form-control" rows="3"><?php echo $row['notevo_un'] ?></textarea>
  </div><hr>
<div class="thead" style="background-color: #0c675e; color: white; font-size: 19px;">
  <center><strong>ALDRETE</strong></center><p>
</div> 
<div class="container">
  <div class="row">
    <div class="col-sm">
     <br><hr><br><strong>ACTIVIDAD MUSCULAR</strong>
     <br>
     
     <hr>
     <strong>RESPIRACIÓN</strong>
     <br><br>
     <hr><br>
     <strong>CIRCULACIÓN</strong>
     <br><br>
     <hr>
     <strong>ESTADO DE CONCIENCIA</strong>
     <hr>
     <strong>SATURACIÓN DE OXÍGENO</strong>
     <hr>
    </div>
    <div class="col-3"><strong><center>TIEMPO (MINUTOS)</center></strong><hr><br>
      <font size="1"><p>MOVS. VOLUNTARIOS AL LLAMADO (4 EXTREMIDADES)
      MOVS. VOLUNTARIOS AL LLAMADO (2 EXTREMIDADES)
      COMPLETAMENTE INMÓVIL</p></font>
      <hr>
      <font size="1"><p>RESPIRACIONES AMPLIAS Y CAPAZ DE TOSER
      RESPIRACIONES LIMITADAS<br>
    APNEA</p></font><hr>
 <br>
 <font size="1"><p>PRESIÓN ARTERIAL + -20% DEL NIVEL BASAL
 PRESIÓN ARTERIAL + -21 - 49% DEL NIVEL BASAL
PRESIÓN ARTERIAL + -50% DEL NIVEL BASAL</p></font><hr>

 <font size="1"><p>COMPLETAMENTE DESPIERTO<br>
 RESPONDE AL SER LLAMADO<br>
NO RESPONDE</p></font><hr>


<font size="1"><p>MANTIENE SAT. DE O2 > 92% CON AIRE AMBIENTE
NECESITA O2 PARA MANTENER LA SAT DE O2 > 90%
SATURACIÓN DE O2 < 90% CON SUPLEMENTO DE OXÍGENO</p></font><hr>

<font size="5"><p><strong>TOTAL DEL ALDRETE</strong></p></font><hr>

    </div>
    <div class="col-sm col-1">
      <br>
      <hr><br>
     <font size="1"><strong><p>= 2<br>
     = 1<br>
   = 0</p></strong></font>
   <hr>
     <font size="1"><strong><p>= 2<br>
     = 1<br>
   = 0</p></strong></font>
 <hr>
      <br>
     <font size="1"><strong><p>= 2<br>
     = 1<br>
   = 0</p></strong></font>
<hr>
      
     <font size="1"><strong><p>= 2<br>
     = 1<br>
   = 0</p></strong></font>
<hr>
    
     <font size="1"><strong><p>= 2<br>
     = 1<br>
   = 0</p></strong></font>
    </div>
   <div class="col-sm">
    <center>0</center><hr><br><div class="losInput">
      <input  type="text" name="nt" id="num1" class="form-control" maxlength="1" value="<?php echo $row['01t'] ?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
      <input type="text" name="dt" class="form-control" maxlength="1" value="<?php echo $row['02t'] ?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
      <input type="text" name="tt" class="form-control" maxlength="1" value="<?php echo $row['03t'] ?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
      <input type="text" name="ct" class="form-control" maxlength="1" value="<?php echo $row['04t'] ?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
      <input type="text" name="st" class="form-control" maxlength="1" value="<?php echo $row['05t'] ?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br></div>
      <div class="inputTotal"><input type="text" name="t" value="<?php echo $row['0t'] ?>" class="form-control" disabled="">
    </div>
  </div>
     <div class="col-sm">
    <center>5</center><hr><br><div class="losInput2">
      <input type="text" name="nt2" class="form-control"maxlength="1" value="<?php echo $row['51t'] ?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
      <input type="text" name="dt2" class="form-control"maxlength="1" value="<?php echo $row['52t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
      <input type="text" name="tt2" class="form-control"maxlength="1"value="<?php echo $row['53t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
      <input type="text" name="ct2" class="form-control"maxlength="1"value="<?php echo $row['54t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
      <input type="text" name="st2" class="form-control"maxlength="1"value="<?php echo $row['55t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br></div>
<div class="inputTotal2">
    <input type="text" name="t2" class="form-control" value="<?php echo $row['5t'] ?>" disabled="">
  </div>
    </div>
     <div class="col-sm">
    <center>10</center><hr><br>
<div class="losInput3">
    <input type="text" name="nt3" class="form-control" maxlength="1"value="<?php echo $row['101t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
    <input type="text" name="dt3" class="form-control"maxlength="1"value="<?php echo $row['102t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
    <input type="text" name="tt3" class="form-control"maxlength="1"value="<?php echo $row['103t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
    <input type="text" name="ct3" class="form-control"maxlength="1"value="<?php echo $row['104t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
    <input type="text" name="st3" class="form-control"maxlength="1"value="<?php echo $row['105t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br></div>
<div class="inputTotal3">
    <input type="text" name="t3" class="form-control" value="<?php echo $row['10t'] ?>" disabled=""></div>
    </div>
    <div class="col-sm">
    <center>15</center><hr><br><div class="losInput4">
      <input type="text" name="nt4" class="form-control"maxlength="1"value="<?php echo $row['151t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
      <input type="text" name="dt4" class="form-control"maxlength="1"value="<?php echo $row['152t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
      <input type="text" name="tt4" class="form-control"maxlength="1"value="<?php echo $row['153t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
      <input type="text" name="ct4" class="form-control"maxlength="1"value="<?php echo $row['154t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
      <input type="text" name="st4" class="form-control"maxlength="1"value="<?php echo $row['155t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br></div>
      <div class="inputTotal4">
        <input type="text" name="t4" class="form-control" value="<?php echo $row['15t'] ?>" disabled=""></div>
    </div>
    <div class="col-sm">
    <center>20</center><hr><br><div class="losInput5">
      <input type="text" name="nt5" class="form-control"maxlength="1"value="<?php echo $row['201t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
      <input type="text" name="dt5" class="form-control"maxlength="1"value="<?php echo $row['202t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
      <input type="text" name="tt5" class="form-control"maxlength="1"value="<?php echo $row['203t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
      <input type="text" name="ct5" class="form-control"maxlength="1"value="<?php echo $row['204t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
      <input type="text" name="st5" class="form-control"maxlength="1"value="<?php echo $row['205t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br></div>
      <div class="inputTotal5"><input type="text" name="t5" value="<?php echo $row['20t'] ?>" class="form-control" disabled=""></div>
    </div>
    <div class="col-sm">
    <center>25</center><hr><br><div class="losInput6">
      <input type="text" name="nt6" class="form-control"maxlength="1"value="<?php echo $row['251t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
      <input type="text" name="dt6" class="form-control"maxlength="1"value="<?php echo $row['252t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
      <input type="text" name="tt6" class="form-control"maxlength="1"value="<?php echo $row['253t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
      <input type="text" name="ct6" class="form-control"maxlength="1"value="<?php echo $row['254t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
      <input type="text" name="st6" class="form-control"maxlength="1"value="<?php echo $row['255t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br></div>
      <div class="inputTotal6"><input type="text" name="t6" value="<?php echo $row['25t'] ?>" class="form-control" disabled=""></div>
    </div>
    <div class="col-sm">
    <center>30</center><hr><br><div class="losInput7">
      <input type="text" name="nt7" class="form-control"maxlength="1"value="<?php echo $row['301t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
      <input type="text" name="dt7" class="form-control"maxlength="1"value="<?php echo $row['302t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
      <input type="text" name="tt7" class="form-control"maxlength="1"value="<?php echo $row['303t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
      <input type="text" name="ct7" class="form-control"maxlength="1"value="<?php echo $row['304t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br>
      <input type="text" name="st7" class="form-control"maxlength="1"value="<?php echo $row['305t'] ?>"onkeypress='return event.charCode >= 48 && event.charCode <= 57'/><br><br></div>
      <div class="inputTotal7"><input type="text" name="t7" value="<?php echo $row['30t'] ?>" class="form-control" disabled=""></div>
    </div>
  </div>
</div>
    <?php 
$date=date_create($row['fecha_nota']);
$id_usua=$row['id_usua'];
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


<center>
                       <div class="form-group col-6">
                            <button type="submit" name="guardar" class="btn btn-primary">FIRMAR</button>
                            <button type="button" class="btn btn-danger" onclick="history.back()">CANCELAR</button>
                        </div>
</center>
                        <br>
                        </form>
</div> <!--TERMINO DE NOTA MEDICA div container-->
         
  </div> 
<?php 
  if (isset($_POST['guardar'])) {

        $t_sisto    = mysqli_real_escape_string($conexion, (strip_tags($_POST["t_sisto"], ENT_QUOTES))); 
        $t_diasto    = mysqli_real_escape_string($conexion, (strip_tags($_POST["t_diasto"], ENT_QUOTES))); 
        $fc_un   = mysqli_real_escape_string($conexion, (strip_tags($_POST["fc_un"], ENT_QUOTES)));
        $fr_un    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fr_un"], ENT_QUOTES))); 
        $temp_un    = mysqli_real_escape_string($conexion, (strip_tags($_POST["temp_un"], ENT_QUOTES)));
        $pul_un    = mysqli_real_escape_string($conexion, (strip_tags($_POST["pul_un"], ENT_QUOTES)));
        $sp_un    = mysqli_real_escape_string($conexion, (strip_tags($_POST["sp_un"], ENT_QUOTES)));
        $est_un    = mysqli_real_escape_string($conexion, (strip_tags($_POST["est_un"], ENT_QUOTES)));
        $con_un    = mysqli_real_escape_string($conexion, (strip_tags($_POST["con_un"], ENT_QUOTES)));
        $via_un      = mysqli_real_escape_string($conexion, (strip_tags($_POST["via_un"], ENT_QUOTES)));
        $veni_un    = mysqli_real_escape_string($conexion, (strip_tags($_POST["veni_un"], ENT_QUOTES)));

        $son_un    = mysqli_real_escape_string($conexion, (strip_tags($_POST["son_un"], ENT_QUOTES))); 
        $man_un    = mysqli_real_escape_string($conexion, (strip_tags($_POST["man_un"], ENT_QUOTES))); 
        $trat_un   = mysqli_real_escape_string($conexion, (strip_tags($_POST["trat_un"], ENT_QUOTES)));
        $fun_un    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fun_un"], ENT_QUOTES))); 
        $mot_un    = mysqli_real_escape_string($conexion, (strip_tags($_POST["mot_un"], ENT_QUOTES)));
        $tera_un    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tera_un"], ENT_QUOTES)));
        $fecha_un    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha_un"], ENT_QUOTES)));
        $hora_un    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hora_un"], ENT_QUOTES)));
        $rec_un    = mysqli_real_escape_string($conexion, (strip_tags($_POST["rec_un"], ENT_QUOTES)));
        $esp_un      = mysqli_real_escape_string($conexion, (strip_tags($_POST["esp_un"], ENT_QUOTES)));
        $notevo_un    = mysqli_real_escape_string($conexion, (strip_tags($_POST["notevo_un"], ENT_QUOTES)));

        $nt    = mysqli_real_escape_string($conexion, (strip_tags($_POST["nt"], ENT_QUOTES))); 
        $dt    = mysqli_real_escape_string($conexion, (strip_tags($_POST["dt"], ENT_QUOTES))); 
        $tt   = mysqli_real_escape_string($conexion, (strip_tags($_POST["tt"], ENT_QUOTES)));
        $ct    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ct"], ENT_QUOTES))); 
        $st    = mysqli_real_escape_string($conexion, (strip_tags($_POST["st"], ENT_QUOTES)));
        $t=$nt+$dt+$tt+$ct+$st;

        $nt2    = mysqli_real_escape_string($conexion, (strip_tags($_POST["nt2"], ENT_QUOTES)));
        $dt2    = mysqli_real_escape_string($conexion, (strip_tags($_POST["dt2"], ENT_QUOTES)));
        $tt2    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tt2"], ENT_QUOTES)));
        $ct2      = mysqli_real_escape_string($conexion, (strip_tags($_POST["ct2"], ENT_QUOTES)));
        $st2    = mysqli_real_escape_string($conexion, (strip_tags($_POST["st2"], ENT_QUOTES)));
        $t2=$nt2+$dt2+$tt2+$ct2+$st2;

        $nt3    = mysqli_real_escape_string($conexion, (strip_tags($_POST["nt3"], ENT_QUOTES))); 
        $dt3    = mysqli_real_escape_string($conexion, (strip_tags($_POST["dt3"], ENT_QUOTES))); 
        $tt3   = mysqli_real_escape_string($conexion, (strip_tags($_POST["tt3"], ENT_QUOTES)));
        $ct3    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ct3"], ENT_QUOTES))); 
        $st3    = mysqli_real_escape_string($conexion, (strip_tags($_POST["st3"], ENT_QUOTES)));
        $t3=$nt3+$dt3+$tt3+$ct3+$st3;

        $nt4    = mysqli_real_escape_string($conexion, (strip_tags($_POST["nt4"], ENT_QUOTES)));
        $dt4    = mysqli_real_escape_string($conexion, (strip_tags($_POST["dt4"], ENT_QUOTES)));
        $tt4    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tt4"], ENT_QUOTES)));
        $ct4      = mysqli_real_escape_string($conexion, (strip_tags($_POST["ct4"], ENT_QUOTES)));
        $st4    = mysqli_real_escape_string($conexion, (strip_tags($_POST["st4"], ENT_QUOTES)));
        $t4=$nt4+$dt4+$tt4+$ct4+$st4;

        $nt5    = mysqli_real_escape_string($conexion, (strip_tags($_POST["nt5"], ENT_QUOTES))); 
        $dt5    = mysqli_real_escape_string($conexion, (strip_tags($_POST["dt5"], ENT_QUOTES))); 
        $tt5   = mysqli_real_escape_string($conexion, (strip_tags($_POST["tt5"], ENT_QUOTES)));
        $ct5    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ct5"], ENT_QUOTES))); 
        $st5    = mysqli_real_escape_string($conexion, (strip_tags($_POST["st5"], ENT_QUOTES)));
        $t5=$nt5+$dt5+$tt5+$ct5+$st5;

        $nt6    = mysqli_real_escape_string($conexion, (strip_tags($_POST["nt6"], ENT_QUOTES)));
        $dt6    = mysqli_real_escape_string($conexion, (strip_tags($_POST["dt6"], ENT_QUOTES)));
        $tt6    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tt6"], ENT_QUOTES)));
        $ct6      = mysqli_real_escape_string($conexion, (strip_tags($_POST["ct6"], ENT_QUOTES)));
        $st6    = mysqli_real_escape_string($conexion, (strip_tags($_POST["st6"], ENT_QUOTES)));
        $t6=$nt6+$dt6+$tt6+$ct6+$st6;

        $nt7    = mysqli_real_escape_string($conexion, (strip_tags($_POST["nt7"], ENT_QUOTES))); 
        $dt7    = mysqli_real_escape_string($conexion, (strip_tags($_POST["dt7"], ENT_QUOTES))); 
        $tt7   = mysqli_real_escape_string($conexion, (strip_tags($_POST["tt7"], ENT_QUOTES)));
        $ct7    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ct7"], ENT_QUOTES))); 
        $st7    = mysqli_real_escape_string($conexion, (strip_tags($_POST["st7"], ENT_QUOTES)));
        $t7=$nt7+$dt7+$tt7+$ct7+$st7;


        $fecha= mysqli_real_escape_string($conexion,(strip_tags($_POST["fecha"], ENT_QUOTES)));
        $hora= mysqli_real_escape_string($conexion,(strip_tags($_POST["hora"], ENT_QUOTES)));
        $medico= mysqli_real_escape_string($conexion,(strip_tags($_POST["medico"], ENT_QUOTES)));

        $merge = $fecha.' '.$hora;

date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

        $sql2 = "UPDATE dat_unid_cuid SET id_usua='$medico',fecha_nota='$merge',t_sisto='$t_sisto', t_diasto='$t_diasto', fc_un='$fc_un', fr_un='$fr_un', temp_un='$temp_un', pul_un='$pul_un' , sp_un='$sp_un', est_un='$est_un', con_un='$con_un' , via_un='$via_un', veni_un='$veni_un', son_un='$son_un', man_un='$man_un', trat_un='$trat_un', fun_un='$fun_un', mot_un='$mot_un', tera_un='$tera_un' , fecha_un='$fecha_un', hora_un='$hora_un', rec_un='$rec_un' , esp_un='$esp_un', notevo_un='$notevo_un', 01t='$nt', 02t='$dt', 03t='$tt', 04t='$ct', 05t='$st', 0t='$t', 51t='$nt2', 52t='$dt2', 53t='$tt2', 54t='$ct2', 55t='$st2', 5t='$t2', 101t='$nt3', 102t='$dt3', 103t='$tt3', 104t='$ct3', 105t='$st3', 10t='$t3', 151t='$nt4', 152t='$dt4', 153t='$tt4' , 154t='$ct4', 155t='$st4', 15t='$t4', 201t='$nt5', 202t='$dt5', 203t='$tt5', 204t='$ct5', 205t='$st5', 20t='$t5', 251t='$nt6', 252t='$dt6', 253t='$tt6' , 254t='$ct6', 255t='$st6', 25t='$t6', 301t='$nt7', 302t='$dt7', 303t='$tt7', 304t='$ct7', 305t='$st7', 30t='$t7' WHERE id_unid_cuid=$id_unid_cuid";
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
<script>
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

$('.losInput3 input').on('change', function(){
  var total = 0;
  $('.losInput3 input').each(function() {
    if($( this ).val() != "")
    {
      total = total + parseFloat($( this ).val());
    }
  });
  $('.inputTotal3 input').val(total.toFixed());
});

$('.losInput4 input').on('change', function(){
  var total = 0;
  $('.losInput4 input').each(function() {
    if($( this ).val() != "")
    {
      total = total + parseFloat($( this ).val());
    }
  });
  $('.inputTotal4 input').val(total.toFixed());
});
$('.losInput5 input').on('change', function(){
  var total = 0;
  $('.losInput5 input').each(function() {
    if($( this ).val() != "")
    {
      total = total + parseFloat($( this ).val());
    }
  });
  $('.inputTotal5 input').val(total.toFixed());
});
$('.losInput6 input').on('change', function(){
  var total = 0;
  $('.losInput6 input').each(function() {
    if($( this ).val() != "")
    {
      total = total + parseFloat($( this ).val());
    }
  });
  $('.inputTotal6 input').val(total.toFixed());
});
$('.losInput7 input').on('change', function(){
  var total = 0;
  $('.losInput7 input').each(function() {
    if($( this ).val() != "")
    {
      total = total + parseFloat($( this ).val());
    }
  });
  $('.inputTotal7 input').val(total.toFixed());
});
function validaNumericos(event) {
    if(event.charCode >= 48 && event.charCode <= 57){
      return true;
     }
     return false;        
}
</script>


</body>
</html>