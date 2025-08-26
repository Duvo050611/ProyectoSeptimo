<?php
session_start();
include "../../conexionbd.php";
include ("../header_medico.php");
$resultado=$conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE dat_ingreso.area='EGRESO DE URGENCIAS'") or die($conexion->error);
$usuario=$_SESSION['login'];
?>   
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″/>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
            integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
            crossorigin="anonymous"></script>
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


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script>
        // Write on keyup event of keyword input element
        $(document).ready(function(){
            $("#search").keyup(function(){
                _this = this;
                // Show only matching TR, hide rest of them
                $.each($("#mytable tbody tr"), function() {
                    if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
                        $(this).hide();
                    else
                        $(this).show();
                });
            });
        });
    </script>
    <script>
        function habilitar(value)
        {
            if(value=="OTROS" || value==true)
            {
                // habilitamos
                document.getElementById("esp").disabled=false;
            }else if(value!="OTROS" || value==false){
                // deshabilitamos
                document.getElementById("esp").disabled=true;
            }
        }
    </script>
    
    <title>Menu Gestión Médica </title>
    <link rel="shortcut icon" href="logp.png">
</head>
<div class="container">
</div>
<body>
<div class="container-fluid">
    <div class="row">


        <div class="col col-12">
          
                 <a href="" data-target="#sidebar" data-toggle="collapse" class="d-md-none"><i class="fa fa-bars" id="side"></i></a>
                 <div class="thead col col-12" style="background-color: #2b2d7f; color: white; font-size: 22px; align-content: center;">
                <center><font id="letra"><strong> Nota de Consulta</strong></font> </center></div>

            <div class="row">
                
            </div>

            <div class="text-center">
            </div>
            <br>
            <h2>
                <a href="" data-target="#sidebar" data-toggle="collapse" class="d-md-none"><i class="fa fa-bars" id="side"></i></a>
            </h2>

            <div class="table-responsive">
<?php 
$id_receta=$_GET['id'];
$amb="SELECT * FROM receta_ambulatoria where id_rec_amb=$id_receta";
$result=$conexion->query($amb);
while ($row=$result->fetch_assoc()) {
 ?>
<div class="container box">
    <form action="" method="POST">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label><strong>FECHA :</strong></label>
                    <input type="datetime" name="fecha" value="<?php echo $row['fecha'] ?>" class="form-control" disabled>
                </div>
            </div>
        </div>
        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 19px;">
             <center><strong>DATOS DEL PACIENTE </strong></center><p>
        </div>
               <div class="row">
                   <div class="col-sm">
                       <div class="form-group">
                           <label><strong>NOMBRE(S) :</strong></label><br>
                           <input type="text" name="nombre_rec" placeholder="Nombre(s)" class="form-control" style="text-transform:uppercase;" value="<?php echo $row['nombre_rec'] ?>"  onkeyup="javascript:this.value=this.value.toUpperCase();">
                       </div>
                   </div>
                   <div class="col-sm">
                       <div class="form-group">
                           <label><strong>PRIMER APELLIDO :</strong></label><br>
                           <input type="text" name="papell_rec" placeholder="Apellido Paterno" class="form-control" style="text-transform:uppercase;" value="<?php echo $row['papell_rec'] ?>"  onkeyup="javascript:this.value=this.value.toUpperCase();">
                       </div>
                   </div>
                   <div class="col-sm ">
                       <div class="form-group">
                           <label><strong>SEGUNDO APELLIDO :</strong></label><br>
                           <input type="text" name="sapell_rec" placeholder="Apellido Materno" class="form-control" style="text-transform:uppercase;" value="<?php echo $row['sapell_rec'] ?>"  onkeyup="javascript:this.value=this.value.toUpperCase();">
                       </div>
                   </div>
               </div>
               <div class="row">
                   <div class="col-sm">
                       <div class="form-group">
                           <label><strong>FECHA DE NACIMIENTO :</strong></label>
                           <input type="date" name="fecnac_rec" value="<?php echo $row['fecnac_rec'] ?>" class="form-control">
                       </div>
                   </div>
                   <div class="col-sm">
                       <div class="form-group">
                           <label><strong>SEXO :</strong></label>
                           <select name="sexo_rec" class="form-control">
                            <option value="<?php echo $row['sexo_rec'] ?>"><?php echo $row['sexo_rec'] ?></option>
                            <option disabled=""></option>
                               <option> SELECCIONA OPCION:</option>
                               <option value="MUJER">MUJER</option>
                               <option value="HOMBRE">HOMBRE</option>
                               <option value="SIN INFORMACION">SIN INFORMACIÓN</option>
                           </select>
                       </div>
                   </div>
                   <div class="col-sm">
                                <div class="form-group">
                                    <label for="aseg"><strong>ASEGURADORA:</strong></label><br>
                                    <select name="aseg" class="form-control" required>
                                        <option value="<?php echo $row['aseguradora'] ?>"><?php echo $row['aseguradora'] ?></option>
                                        <option></option><?php } ?>
                                        <?php
                                $resultadoaseg = $conexion->query("SELECT * FROM cat_aseg WHERE aseg_activo='SI'") or die($conexion->error);
                                ?>
                                <option value="">SELECCIONAR </option>
                                <?php foreach ($resultadoaseg as $opcionesaseg): ?>


                                    <option value="<?php echo $opcionesaseg['aseg'] ?>"><?php echo $opcionesaseg['aseg'] ?></option>

                                <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
               </div>
      
<div class="row">
    <?php 
$amb="SELECT * FROM receta_ambulatoria where id_rec_amb=$id_receta";
$result=$conexion->query($amb);
while ($row=$result->fetch_assoc()) {
 ?>
    <div class="col">
        <label><strong>ESPECIALIDAD</strong></label><br>
        <select name="esp" class="form-control" required onchange="habilitar(this.value);">
            <option value="<?php echo $row['especialidad'] ?>"><?php echo $row['especialidad'] ?></option><?php } ?>
            <?php $resultado=$conexion->query("SELECT * FROM cat_espec WHERE espec_activo='SI'") ?>
            <option></option>
            <option>SELECCIONAR OPCIÓN</option>
            <option value="OTROS">OTROS</option>
            <?php foreach ($resultado as $row ):?>
                <option value=" <?php echo $row['espec'] ?>"> <?php echo $row['espec'] ?></option>
            <?php endforeach ?>
           <!-- <option>SELECCIONAR OPCIÓN</option>
            <option value="CIRUGIA GENERAL Y GASTROENTEROLOGIA"> CIRUGÍA GENERAL Y GASTROENTEROLOGÍA</option>
            <option value="MEDICINA INTERNA">MEDICINA INTERNA</option>
            <option value="PEDIATRIA">PEDIATRÍA</option>
            <option value="NEUROCIRUGIA">NEUROCIRUGÍA</option>
            <option value="MEDICINA FAMILIAR">MEDICINA FAMILIAR</option>
            <option value="OTROS">OTROS</option>-->
        </select>
    </div>
<?php 
$amb="SELECT * FROM receta_ambulatoria where id_rec_amb=$id_receta";
$result=$conexion->query($amb);
while ($row=$result->fetch_assoc()) {
 ?>
    <div class="col">
       <label>DETALLE ESPECIALIDAD (OTROS)</label><br>
        <input type="text" name="detesp" id="esp" placeholder="DETALLE ESPECIALIDAD" disabled="" value="<?php echo $row['detesp'] ?>" class="form-control" required>
    </div>
    <div class="col">
        <label><strong>ALERGIA A MEDICAMENTOS:</strong></label>
        <input type="text" name="alergia_rec" placeholder="ALERGIA A MEDICAMENTOS" value="<?php echo $row['alerg_rec'] ?>" class="form-control">
    </div>
</div>
<br>
      <div class="thead" style="background-color: #0c675e; color: white; font-size: 19px;">
  <center><strong>SIGNOS VITALES</strong></center><p>
</div>
                <div class="container"> <strong>
  <div class="row">
    <div class="col-sm-2"><br><center>PRESIÓN ARTERIAL:</center>
     <div class="row">
  <div class="col"><input type="text" class="form-control" value="<?php echo $row['p_sistolica'] ?>" name="p_sistolica" ></div> /
  <div class="col"><input type="text" class="form-control" value="<?php echo $row['p_diastolica'] ?>" name="p_diastolica"></div>
 
</div>
    </div>
    <div class="col-sm-2">
      FRECUENCIA CARDIACA:<input type="number" class="form-control" value="<?php echo $row['f_card'] ?>" name="f_card" onkeypress="return recetaamb(event);">
    </div>
    <div class="col-sm-2">
      FRECUENCIA RESPIRATORIA:<input type="number" class="form-control" value="<?php echo $row['f_resp'] ?>" name="f_resp" onkeypress="return recetaamb(event);">
    </div>
    <div class="col-sm-2">
     <br>TEMPERATURA:<input type="number" class="form-control" value="<?php echo $row['temp'] ?>"  name="temp" onkeypress="return recetaamb(event);">
    </div>
    <div class="col-sm-2">
     SATURACIÓN OXÍGENO:<input type="number"  class="form-control" value="<?php echo $row['sat_oxigeno'] ?>" name="sat_oxigeno" onkeypress="return recetaamb(event);">
    </div>
    <div class="col-sm-1">
    <br> PESO:<input type="number" class="form-control" value="<?php echo $row['peso'] ?>"  name="peso" onkeypress="return recetaamb(event);">
    </div>
    <div class="col-sm-1">
     TALLA (cm):<input type="number" class="form-control" value="<?php echo $row['talla'] ?>" name="talla" onkeypress="return recetaamb(event);">
    </div>
  </div> </strong>
</div><br>
               <div class="row">
    <div class=" col">
     <div class="form-group">
        
  </div>
    </div>
</div>
<!--
 < <div class="row">
<div class="col-1">
<center><strong><label for="exampleFormControlTextarea1"><br><font size="5" color="#407959">P</font></label></strong></center>
    </div>
   <div class=" col-10">
     <strong><font color="black">PACIENTE: DESCRIBIR AL PACIENTE (EDAD,GÉNERO, DIAGNÓSTICOS Y TRATAMIENTOS PREVIOS)</font></strong>
     <div class="form-group">
    <textarea class="form-control" id="exampleFormControlTextarea1" name="problema" rows="3" placeholder="PACIENTE: DESCRIBIR AL PACIENTE (EDAD,GÉNERO, DIAGNÓSTICOS Y TRATAMIENTOS PREVIOS)" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
  </div>
    </div>
</div>-->
 <div class="row">
<div class="col-1">
<center><strong><label for="exampleFormControlTextarea1"><br><font size="5" color="#407959">S</font></label></strong></center>
</div>
    <div class=" col-10">
      <strong><font color="black">SUBJETIVO: DESCRIBIR LA SINTOMATOLOGÍA DEL PACIENTE, HÁBITOS, A QUE ATRIBUYE EL PADECIMIENTO ACTUAL, ETC.</font></strong><br><br>
     <div class="form-group">
        <textarea name="subjetivo" class="form-control" rows="5"><?php echo $row['subjetivo'] ?></textarea>
     </div>
    </div>
</div>

<div class="row">
<div class="col-1">
<center><strong><label for="exampleFormControlTextarea1"><br><font size="5"color="#407959">O</font></label></strong></center>
    </div>
    <div class=" col-10">
      <strong><font color="black">OBJETIVO: DESCRIBIR LA EXPLORACIÓN FÍSICA</font></strong><br><br>
     <div class="form-group">
        <textarea name="objetivo" class="form-control" rows="5"><?php echo $row['objetivo'] ?></textarea>
  </div>
    </div>
</div>

<div class="row">
<div class="col-1">
<center><strong><label for="exampleFormControlTextarea1"><br><font size="5"color="#407959">A</font></label></strong></center>
    </div>
    <div class=" col-10">
          <strong><font color="black">ANÁLISIS: LOS DIAGNÓSTICOS PROBABLES Y DEFINITIVOS Y LOS ARGUMENTOS, CORRELACIÓN DE LOS ESTUDIOS DE LABORATORIO Y GABINETE CON EL PADECIMIENTO ACTUAL.</font></strong><br><br>
     <div class="form-group">
        <textarea name="analisis" class="form-control" rows="5"><?php echo $row['analisis'] ?></textarea>
  </div>
    </div>
</div>

<div class="row">
<div class="col-1">
<center><strong><label for="exampleFormControlTextarea1"><br><font size="5"color="#407959">P</font></label></strong></center>
    </div>
    <div class=" col-10">
<strong><font color="black">PLAN: DESCRIBIR TRATAMIENTO Y RECOMENDACIONES INDICADAS AL PACIENTE, ANOTAR LOS MEDICAMENTOS CON SU DOSIS DE MANERA DETALLADA.</font></strong><br><br>     
     <div class="form-group">
        <textarea name="plan" class="form-control" rows="5"><?php echo $row['plan'] ?></textarea>
  </div>
    </div>
</div>

<div class="row">
<div class="col-1">
<center><strong><label for="exampleFormControlTextarea1"><br><font size="5"color="#407959">PX</font></label></strong></center>
    </div>
    <div class=" col-10">
      <strong><font color="black">PRONÓSTICO: PARA LA VIDA Y PARA LA FUNCIÓN</font></strong><br><br>
     <div class="form-group">
        <textarea name="px" class="form-control" rows="5"><?php echo $row['px'] ?></textarea>
  </div>
    </div>
</div>

<div class="row">
    <div class=" col">
     <div class="form-group">
        <div class="thead" style="background-color: #0c675e; color: white; font-size: 19px;">
             <center><strong>M E D I C A M E N T O S</strong></center><p>
        </div>
<br><br>
<textarea name="receta_rec" class="form-control" rows="25"><?php echo $row['receta_rec'] ?></textarea>
  </div>
    </div>
</div>
<div class="row">
    <div class=" col">
     <div class="form-group">
        <label><strong>MEDIDAS HIGIÉNICAS-DIETÉTICAS : </strong></label><br><br>
      <input type="text" name="med_rec" class="form-control" value="<?php echo $row['med_rec'] ?>" style="text-transform:uppercase;">
  </div>
    </div>
</div>

<strong><label> FECHA Y HORA DE PRÓXIMA CITA</label></strong>
<div class="row">
    <div class="col-sm-4">
        <input type="date" name="fec_pcita" value="<?php echo $row['fec_pcita'] ?>" class="form-control" required="">
    </div>
    <div class="col-sm-4">
        <input type="time" name="hor_pcita" value="<?php echo $row['hor_pcita'] ?>" class="form-control">
    </div>
</div>
<hr>


               <center><div class="form-group ">
                            <button type="submit" name="guardar" class="btn btn-primary">FIRMAR Y GUARDAR</button>
                            <button type="button" class="btn btn-danger" onclick="history.back()">CANCELAR</button>
               </div></center>

       </form><hr>
    </div>
<?php 
} 

  if (isset($_POST['guardar'])) {

        $nombre_rec    = strtoupper(mysqli_real_escape_string($conexion, (strip_tags($_POST["nombre_rec"], ENT_QUOTES))));
        $papell_rec    = strtoupper(mysqli_real_escape_string($conexion, (strip_tags($_POST["papell_rec"], ENT_QUOTES))));
        $sapell_rec  = strtoupper(mysqli_real_escape_string($conexion, (strip_tags($_POST["sapell_rec"], ENT_QUOTES))));
        $fecnac_rec    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fecnac_rec"], ENT_QUOTES))); 
        $sexo_rec    = mysqli_real_escape_string($conexion, (strip_tags($_POST["sexo_rec"], ENT_QUOTES)));

        $especialidad    = mysqli_real_escape_string($conexion, (strip_tags($_POST["esp"], ENT_QUOTES)));
        $detesp    = mysqli_real_escape_string($conexion, (strip_tags($_POST["detesp"], ENT_QUOTES)));
        $alerg_rec  = strtoupper(mysqli_real_escape_string($conexion, (strip_tags($_POST["alergia_rec"], ENT_QUOTES))));
        $p_sistolica    = mysqli_real_escape_string($conexion, (strip_tags($_POST["p_sistolica"], ENT_QUOTES))); 
        $p_diastolica    = mysqli_real_escape_string($conexion, (strip_tags($_POST["p_diastolica"], ENT_QUOTES)));

        $f_card    = mysqli_real_escape_string($conexion, (strip_tags($_POST["f_card"], ENT_QUOTES)));
        $f_resp    = mysqli_real_escape_string($conexion, (strip_tags($_POST["f_resp"], ENT_QUOTES)));
        $temp  = mysqli_real_escape_string($conexion, (strip_tags($_POST["temp"], ENT_QUOTES)));
        $sat_oxigeno    = mysqli_real_escape_string($conexion, (strip_tags($_POST["sat_oxigeno"], ENT_QUOTES))); 
        $peso    = mysqli_real_escape_string($conexion, (strip_tags($_POST["peso"], ENT_QUOTES)));

        $talla    = mysqli_real_escape_string($conexion, (strip_tags($_POST["talla"], ENT_QUOTES)));
        $aseguradora    = mysqli_real_escape_string($conexion, (strip_tags($_POST["aseg"], ENT_QUOTES)));
        $fec_pcita    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fec_pcita"], ENT_QUOTES)));
        $hor_pcita    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hor_pcita"], ENT_QUOTES)));

        $receta_rec  = mysqli_real_escape_string($conexion, (strip_tags($_POST["receta_rec"], ENT_QUOTES)));
         $subjetivo  = mysqli_real_escape_string($conexion, (strip_tags($_POST["subjetivo"], ENT_QUOTES)));
         $objetivo=mysqli_real_escape_string($conexion, (strip_tags($_POST["objetivo"], ENT_QUOTES)));
        $analisis=mysqli_real_escape_string($conexion, (strip_tags($_POST["analisis"], ENT_QUOTES)));
        $plan=mysqli_real_escape_string($conexion, (strip_tags($_POST["plan"], ENT_QUOTES)));
$px=mysqli_real_escape_string($conexion, (strip_tags($_POST["px"], ENT_QUOTES)));
$med_rec  = mysqli_real_escape_string($conexion, (strip_tags($_POST["med_rec"], ENT_QUOTES)));

date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");




$sql2 = "UPDATE receta_ambulatoria SET nombre_rec='$nombre_rec', papell_rec='$papell_rec', sapell_rec ='$sapell_rec', fecnac_rec='$fecnac_rec', sexo_rec='$sexo_rec', especialidad='$especialidad', detesp='$detesp', alerg_rec ='$alerg_rec', p_sistolica='$p_sistolica', p_diastolica='$p_diastolica',f_card='$f_card', f_resp='$f_resp', temp ='$temp', sat_oxigeno='$sat_oxigeno', peso='$peso', talla='$talla', aseguradora='$aseguradora', subjetivo ='$subjetivo', objetivo='$objetivo', analisis='$analisis', plan='$plan', px='$px', receta_rec ='$receta_rec', med_rec='$med_rec', fec_pcita='$fec_pcita', hor_pcita='$hor_pcita' WHERE id_rec_amb= $id_receta";
$result = $conexion->query($sql2);

        echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> DATO EDITADO CORRECTAMENTE...</p>";
        echo '<script type="text/javascript">window.location ="buscar_receta.php"</script>';
      }
 ?>      
    </div>
    </div>
    </div>
</div>
</div>
<footer class="main-footer">
    <?php
    include("../../template/footer.php");
    ?>
</footer>

<script>
    document.oncontextmenu = function(){return false;}

</script>
<script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<!-- FastClick -->
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

</body>
</html>