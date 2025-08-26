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
}

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

    <title>NOTA DE TRANSFUSIÓN</title>
</head>
<body>

<div class="col-sm-12">
    <div class="container">
        <div class="row">
            <div class="col">
                
 <div class="thead" style="background-color: #0c675e; color: white; font-size: 24px;">
  <center><strong>NOTA DE TRANSFUSIÓN</strong></center><p>
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
      TIPO DE SANGRE : <td><strong><?php echo $f1['tip_san']; ?></strong></td><br>    
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
                $fecha_actual = date("Y-m-d H:i:s");
                ?>
                
  <div class="form-group">
    <label for="fecha">FECHA Y HORA ACTUAL:</label>
    <input type="datetime" value="<?= $fecha_actual ?>" class="form-control" disabled>
                </div>
            </div>
            </div>
            
        </div>
   
 
 <div class="container">  <!--INICIO -->
 <?php 
$id_tras=$_GET['id_tras'];
$tras="SELECT * FROM dat_trasfucion where id_tras=$id_tras";
$result=$conexion->query($tras);
while ($row=$result->fetch_assoc()) {
  $id_usua=$row['id_usua'];
 ?>
<form action="" method="POST">

<div class="container">
  <div class="thead" style="background-color: #0c675e; color: white; font-size: 19px;">
  <center><strong>PRE-TRANSFUSIONAL</strong></center><p>
</div>
  <div class="row">
    <div class="col-sm">
        <div class="form-group">
           <label>FECHA DE TRANSFUSIÓN:</label><br>
           <input type="date" name="fec_tras" value="<?php echo $row['fec_tras'] ?>" class="form-control">
        </div>
    </div>
    <div class="col-sm">
        <div class="form-group">
           <label>NÚMERO DE UNIDADES:</label><br>
           <input type="number" name="num_tras" value="<?php echo $row['num_tras'] ?>" placeholder="NUMERO DE UNIDADES" class="form-control">
        </div>
    </div>
    <div class="col-sm">
        <div class="form-group">
           <label>CONTENIDO (CANTIDAD):</label><br>
           <input type="text" name="cont_tras" placeholder="CONTENIDO (CANTIDAD)" value="<?php echo $row['cont_tras'] ?>" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
        </div>
    </div>


        <div class="col-sm-3">
          <div class="form-group">
             <label>FOLIO:</label><br>
             <input type="text" name="fol_tras" placeholder="FOLIO" value="<?php echo $row['fol_tras'] ?>" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
          </div>
        </div>
    </div><hr>
    <div class="row">
      <div class="container">
        <div class="row">
          <div class="col">
            <div class="thead" style="background-color: #0c675e; color: white; font-size: 19px;">
  <center><strong>COMPONENTE SANGUINEO</strong></center><p>
</div>
          </div>
        </div>
        <?php 
if ($row['glob_tras'] == "PAQUETE GLOBULAR") {
         ?>
        <div class="row">
         <div class="col-sm-4">
          <label>PAQUETE GLOBULAR:</label><br>
          <input type="radio" name="comp_sang" checked value="PAQUETE GLOBULAR" placeholder="PAQUETE GLOBULAR"  >
         </div>
         <div class="col-sm-4">
          <label>PLASMA:</label><br>
          <input type="radio" name="comp_sang"  value="PLASMA" placeholder="PLASMA"  >
         </div>
         <div class="col-sm-4">
          <label>CRIOPRECIPITADO:</label><br>
          <input type="radio" name="comp_sang" value="CRIOPRECIPITADO" placeholder="CRIOPRECIPITADO"  >
         </div>
       </div><hr>
     <?php }elseif($row['glob_tras'] == "PLASMA"){ ?>
         <div class="row">
         <div class="col-sm-4">
          <label>PAQUETE GLOBULAR:</label><br>
          <input type="radio" name="comp_sang"  value="PAQUETE GLOBULAR" placeholder="PAQUETE GLOBULAR"  >
         </div>
         <div class="col-sm-4">
          <label>PLASMA:</label><br>
          <input type="radio" name="comp_sang" checked value="PLASMA" placeholder="PLASMA"  >
         </div>
         <div class="col-sm-4">
          <label>CRIOPRECIPITADO:</label><br>
          <input type="radio" name="comp_sang" value="CRIOPRECIPITADO" placeholder="CRIOPRECIPITADO"  >
         </div>
       </div><hr>
      <?php }elseif($row['glob_tras'] == "CRIOPRECIPITADO"){ ?>
          <div class="row">
         <div class="col-sm-4">
          <label>PAQUETE GLOBULAR:</label><br>
          <input type="radio" name="comp_sang"  value="PAQUETE GLOBULAR" placeholder="PAQUETE GLOBULAR"  >
         </div>
         <div class="col-sm-4">
          <label>PLASMA:</label><br>
          <input type="radio" name="comp_sang"  value="PLASMA" placeholder="PLASMA"  >
         </div>
         <div class="col-sm-4">
          <label>CRIOPRECIPITADO:</label><br>
          <input type="radio" name="comp_sang" checked value="CRIOPRECIPITADO" placeholder="CRIOPRECIPITADO"  >
         </div>
       </div><hr>
     <?php } ?>
        </div>
    </div>
  <div class="row">
    <div class="col">
      <div class="form-group">
        <label>HB:</label>
        <input type="number" name="hb_tras" placeholder="HB" value="<?php echo $row['hb_tras'] ?>"  class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
      </div>
    </div>
    <div class="col">
      <div class="form-group">
        <label>HTO:</label>
        <input type="number" name="hto_tras" placeholder="HTO" value="<?php echo $row['hto_tras'] ?>" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
      </div>
    </div>
    <div class="col">
      <div class="form-group">
        <label>GRUPO SANGUINEO:</label>
        <input type="text" name="san_tras" placeholder="GRUPO SANGUINEO" value="<?php echo $row['san_tras'] ?>" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
      </div>
    </div>
  </div><hr>

  <div class="row">
    <div class="col-sm-4">
      <div class="form-group">
        <label>HORA DE INICIO</label><br>
        <input type="time" name="inicio_tras" value="<?php echo $row['inicio_tras'] ?>" class="form-control">
      </div>
    </div>
  </div>
    <div class="row">
    <div class="col">
      <div class="form-group">
        <div class="form-group">
        <label>NOMBRE COMPLETO DEL MEDICO QUE INDICA LA TRANSFUSIÓN :</label><br>
        <input type="text" name="med_tras" placeholder="MEDICO QUE INDICA LA TRASFUSIÓN" value="<?php echo $row['med_tras'] ?>" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
      </div>
      </div>
    </div>
  </div>
    <div class="row">
    <div class="col">
      <div class="form-group">
        <label>NOMBRE COMPLETO DEL MEDICO QUE REALIZA LA TRANSFUSIÓN :</label><br>
        <input type="text" name="medi_tras" placeholder="MEDICO QUE REALIZÓ LA TRASFUSIÓN" value="<?php echo $row['medi_tras'] ?>" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
      </div>
    </div>
  </div><hr>
  <div class="thead" style="background-color: #0c675e; color: white; font-size: 24px;">
  <center><strong>TRANFUSIONAL</strong></center><p>
</div>
 
        <div class="thead" style="background-color: #0c675e; color: white; font-size: 19px;">
  <center><strong>SIGNOS VITALES</strong></center><p>
</div>
                <div class="container"> 
  <div class="row">
    <div class="col-sm-4">
      <center>PRESIÓN ARTERIAL:</center>
     <div class="row">
  <div class="col"><input type="text" name="sisto_tras" class="form-control" value="<?php echo $row['sisto_tras'] ?>"></div> /
  <div class="col"><input type="text" name="diasto_tras" class="form-control" value="<?php echo $row['diasto_tras'] ?>"></div>
 
</div>
    </div>
    <div class="col-sm-4">
      FRECUENCIA CARDIACA:<input type="text" name="fc_tras" class="form-control" value="<?php echo $row['fc_tras'] ?>">
    </div>
   <!-- <div class="col-sm-2">
      FRECUENCIA RESPIRATORIA:<input type="text" name="fr_tras" class="form-control" value="<?php echo $row[''] ?>">
    </div>-->
    <div class="col-sm-4">
     TEMPERATURA:<input type="text" name="temp_tras" class="form-control" value="<?php echo $row['temp_tras'] ?>">
    </div>
   <!-- <div class="col-sm-2">
     SATURACIÓN OXÍGENO:<input type="text" name="sat_tras"  class="form-control" value="<?php echo $row[''] ?>">
    </div>
    <div class="col-sm-1">
     <br>PESO:<input type="text" name="peso_tras" class="form-control" value="<?php echo $row[''] ?>" >
    </div>
    <div class="col-sm-1">
     <br>TALLA:<input type="text" name="talla_tras" class="form-control" value="<?php echo $row[''] ?>" >
    </div>-->
  </div>
</div><hr>
    <div class="row">
    <div class="col">
      <div class="form-group">
        <br>EVOLUCIÓN :
        <textarea class="form-control" name="ev_tras" rows="5"><?php echo $row['ev_tras'] ?></textarea>
      </div>
    </div>
  </div>
    <div class="row">
    <div class="col">
      <div class="form-group">
        <br>COMPLICACIONES :
        <textarea class="form-control" name="com_tras" rows="5"><?php echo $row['com_tras'] ?></textarea>
      </div>
    </div>
  </div><hr>

   <div class="thead" style="background-color: #0c675e; color: white; font-size: 24px;">
  <center><strong>POST-TRANSFUSIONAL</strong></center><p>
</div>
 
 <div class="thead" style="background-color: #0c675e; color: white; font-size: 19px;">
  <center><strong>SIGNOS VITALES</strong></center><p>
</div>
              
  <div class="row">
    <div class="col-sm-4"><br><center>PRESIÓN ARTERIAL:</center>
     <div class="row">
  <div class="col"><input type="text" class="form-control" name="p_sistol"  value="<?php echo $row['p_sistol'] ?>"></div> /
  <div class="col"><input type="text" class="form-control" name="p_diastol" value="<?php echo $row['p_diastol'] ?>" ></div>
 
</div>
    </div>
    <div class="col-sm-4">
     <br> FRECUENCIA CARDIACA:<input type="text" class="form-control" name="fcard" value="<?php echo $row['fcard'] ?>">
    </div>
   <!-- <div class="col-sm-2">
      FRECUENCIA RESPIRATORIA:<input type="text" class="form-control" name="fresp">
    </div>-->
    <div class="col-sm-4">
     <br>TEMPERATURA:<input type="text" class="form-control"  name="temper" value="<?php echo $row['temper'] ?>">
    </div>
   <!-- <div class="col-sm-2">
     SATURACIÓN OXÍGENO:<input type="text"  class="form-control" name="satoxi">
    </div>
    <div class="col-sm-1">
     <br>PESO:<input type="text" class="form-control"  name="peso">
    </div>
    <div class="col-sm-1">
     <br>TALLA:<input type="text" class="form-control" name="talla" >
    </div>-->
  </div>
<hr>
    <div class="row">
    <div class="col">
      <div class="form-group">
        <br>VOLUMEN TRANSFUNDIDO:<input type="text" class="form-control" value="<?php echo $row['vol_tras'] ?>" name="vol_tras" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
      </div>
    </div>
    <div class="col">
      <div class="form-group">
        <br>HORA DE TERMINO:<input type="time" class="form-control" value="<?php echo $row['fin_tras'] ?>" name="fin_tras" >
      </div>
    </div>
    
  </div>  
  <div class="row">
    <div class="col">
      <div class="form-group">
        <br>COMPLICACIONES :
        <textarea class="form-control" name="com_traspost" rows="5"><?php echo $row['com_traspost'] ?></textarea>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <div class="form-group">
        <br>ESTADO GENERAL DEL PACIENTE:
        <textarea class="form-control" name="edo_tras" rows="5"><?php echo $row['edo_tras'] ?></textarea>
      </div>
    </div><br>
  </div>

  <div class="row">
    <div class="col">
      <div class="form-group">
        <br>EVOLUCIÓN :
        <textarea class="form-control" name="ev_traspost" rows="5"><?php echo $row['ev_traspost'] ?></textarea>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <div class="form-group">
        <br>OBSERVACIÓN / COMPLICACIONES:
        <textarea class="form-control" name="ob_tras" rows="5"><?php echo $row['ob_tras'] ?></textarea>
      </div>
    </div>
  </div>
</div>
<hr>
<div class="container">
  <div class="row">
    <div class="col-sm">  
    <p style = "font-family:arial;">
       <strong>RECOMENDACIONES :</strong><br>
       1.-EL SERVICIO CLINICO DEBERA MANTENER LA UNIDAD EN TEMPERATURAS Y CONDICIONES ADECUADAS QUE ASEGUREN SU VIABILIDAD.<br>
       2.-ANTES DE CADA TRANSFUSIÓN DEBERA VERIFICARSE LA IDENTIDAD DEL RECEPTOR Y DE LA UNIDAD DESIGNADA PARA ESTE.<br>
       3.-NO SE DEBERA AGREGAR A LA UNIDAD NINGUN MEDICAMENTO O SOLUCIÓN, INCLUSO LAS DESTINADAS PARA USO INTRAVENOSO, CON EXCEPCIÓN DE SOLUCIÓN SALINA AL 0.9% CUANDO ASI SEA NECESARIO.<br>
       4.-LA TRANSFUSIÓN DE CADA UNIDAD NO DEBERA EXCEDER DE 4 HORAS.<br>
       5.-LOS FILTROS DEBERÁN CAMBIARSE CADA 6 HRS. O CUANDO HUBIESEN TRANSFUNDIDO 4 UNIDADES.<br>
       6.-DE PRESENTARSE UNA REACCIÓN TRANSFUCIONAL,SUSPENDER INMEDIATAMENTE LA TRANSFUCIÓN, NOTIFICAR AL MEDICO ENCARGADO Y REPORTAR AL BANCO DE SANGRE, SIGUIENDO LAS INSTRUCCIONES SEÑALADAS EN EL FORMATO DE REPORTE QUE ACOMPAÑA A LA UNIDAD.<br>
       7.-EN CASO DE NO TRANSFUNDIR LA UNIDAD, REGRESARLA AL BANCO DE SANGRE O SERVICIO DE TRANSFUSIÓN PREFERENTEMENTE ANTES DE TRANSCURRIDAS 2 HORAS A PARTIR DE QUE LA UNIDAD SALIO DEL BANCO DE SANGRE O DEL SERVICIO DE TRANSFUSIÓN.  
    </p>  
    </div>
  </div> 
</div>
    <?php 
$date=date_create($row['fecha_act']);
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
</div> <!--TERMINO DE div container-->
 <?php 
  if (isset($_POST['guardar'])) {

        $fec_tras    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fec_tras"], ENT_QUOTES))); //Escanpando caracteres
        $num_tras    = mysqli_real_escape_string($conexion, (strip_tags($_POST["num_tras"], ENT_QUOTES))); //Escanpando caracteres
        $cont_tras  = mysqli_real_escape_string($conexion, (strip_tags($_POST["cont_tras"], ENT_QUOTES)));
        $fol_tras    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fol_tras"], ENT_QUOTES))); //Escanpando caracteres
        $glob_tras    = mysqli_real_escape_string($conexion, (strip_tags($_POST["glob_tras"], ENT_QUOTES)));
        $pla_tras    = mysqli_real_escape_string($conexion, (strip_tags($_POST["pla_tras"], ENT_QUOTES)));
        $crio_tras    = mysqli_real_escape_string($conexion, (strip_tags($_POST["crio_tras"], ENT_QUOTES)));
        $hb_tras    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hb_tras"], ENT_QUOTES)));
        $hto_tras    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hto_tras"], ENT_QUOTES)));
        $san_tras    = mysqli_real_escape_string($conexion, (strip_tags($_POST["san_tras"], ENT_QUOTES)));
        $inicio_tras    = mysqli_real_escape_string($conexion, (strip_tags($_POST["inicio_tras"], ENT_QUOTES)));
        $med_tras    = mysqli_real_escape_string($conexion, (strip_tags($_POST["med_tras"], ENT_QUOTES)));
        $medi_tras    = mysqli_real_escape_string($conexion, (strip_tags($_POST["medi_tras"], ENT_QUOTES)));
        $ev_tras    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ev_tras"], ENT_QUOTES)));
        $com_tras    = mysqli_real_escape_string($conexion, (strip_tags($_POST["com_tras"], ENT_QUOTES)));
        $vol_tras    = mysqli_real_escape_string($conexion, (strip_tags($_POST["vol_tras"], ENT_QUOTES)));
        $fin_tras    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fin_tras"], ENT_QUOTES)));
        $com_traspost    = mysqli_real_escape_string($conexion, (strip_tags($_POST["com_traspost"], ENT_QUOTES)));
        $ev_traspost    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ev_traspost"], ENT_QUOTES)));
        $edo_tras    = mysqli_real_escape_string($conexion, (strip_tags($_POST["edo_tras"], ENT_QUOTES)));
        $ob_tras    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ob_tras"], ENT_QUOTES)));
        $sisto_tras    = mysqli_real_escape_string($conexion, (strip_tags($_POST["sisto_tras"], ENT_QUOTES)));
        $diasto_tras    = mysqli_real_escape_string($conexion, (strip_tags($_POST["diasto_tras"], ENT_QUOTES)));
        $fc_tras    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fc_tras"], ENT_QUOTES)));
        $temp_tras    = mysqli_real_escape_string($conexion, (strip_tags($_POST["temp_tras"], ENT_QUOTES)));
        $p_sistol    = mysqli_real_escape_string($conexion, (strip_tags($_POST["p_sistol"], ENT_QUOTES)));
        $p_diastol    = mysqli_real_escape_string($conexion, (strip_tags($_POST["p_diastol"], ENT_QUOTES)));
        $fcard    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fcard"], ENT_QUOTES)));
        $temper    = mysqli_real_escape_string($conexion, (strip_tags($_POST["temper"], ENT_QUOTES)));
        $fecha= mysqli_real_escape_string($conexion,(strip_tags($_POST["fecha"], ENT_QUOTES)));
        $hora= mysqli_real_escape_string($conexion,(strip_tags($_POST["hora"], ENT_QUOTES)));
        $medico= mysqli_real_escape_string($conexion,(strip_tags($_POST["medico"], ENT_QUOTES)));

        $merge = $fecha.' '.$hora;

date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

        $sql2 = "UPDATE dat_trasfucion SET id_usua='$medico',fecha_act='$merge',fec_tras='$fec_tras', num_tras='$num_tras', cont_tras ='$cont_tras', fol_tras='$fol_tras', glob_tras='$glob_tras', pla_tras='$pla_tras' , crio_tras='$crio_tras', med_tras='$med_tras', hto_tras='$hto_tras', san_tras='$san_tras', inicio_tras='$inicio_tras', medi_tras='$medi_tras', ev_tras='$ev_tras', com_tras='$com_tras', vol_tras='$vol_tras', fin_tras='$fin_tras', com_traspost='$com_traspost', ev_traspost='$ev_traspost', edo_tras='$edo_tras', ob_tras='$ob_tras', sisto_tras='$sisto_tras', diasto_tras='$diasto_tras', fc_tras='$fc_tras', temp_tras='$temp_tras', p_sistol='$p_sistol', p_diastol='$p_diastol', fcard='$fcard', temper='$temper' WHERE id_tras= $id_tras";
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



</body>
</html>