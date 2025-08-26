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
             <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
              integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk"
              crossorigin="anonymous">
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
              integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ"
              crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <script src="jquery-3.1.1.min.js"></script>
    <script src="js/select2.js"></script>

    <link rel="stylesheet" href="css_busc/estilos2.css">
    
    <script src="js_busc/jquery.js"></script>
    <script src="js_busc/jquery.dataTables.min.js"></script>
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
          <style type="text/css">
    #contenido{
        display: none;
    }
</style>
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
                 <div class="thead col col-12" style="background-color: #0c675e; color: white; font-size: 25px; align-content: center;">
                <center><font id="letra"><strong> NOTA DE CONSULTA EXTERNA</strong></font> </center></div>

            <div class="row">
                
            </div>

            <div class="text-center">
            </div>
            <br>
            <h2>
                <a href="" data-target="#sidebar" data-toggle="collapse" class="d-md-none"><i class="fa fa-bars" id="side"></i></a>
            </h2>

            <div class="table-responsive">
<div class="container box">
    <form action="" method="POST">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <?php
                        date_default_timezone_set('America/Guatemala');
                        $fecha_actual = date("d-m-Y H:i:s");
                        ?>
                    <label><strong>FECHA :</strong></label>
                    <input type="datetime" name="fecha" value="<?= $fecha_actual ?>" class="form-control" disabled>
                </div>
            </div>
            <!--  
                <div class="col-sm-4">
     <label><strong>BUSCAR PACIENTE</strong></label><br>
        <select name="pac" class="form-control" data-live-search="true" id="mibuscador" style="width :200%; heigth : 80%" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
            <option value="">SELECCIONAR PACIENTE</option>
<?php
include "../../conexionbd.php";
$sql_diag="SELECT * from receta_ambulatoria order by id_rec_amb desc ";
$result_diag=$conexion->query($sql_diag);
foreach ($result_diag as $row ) {
    $nombre_rec=$row['nombre_rec'].' '.$row['papell_rec'].' '.$row['sapell_rec'];
?>
<option value="nueva_receta.php?id=<?php// echo $row['id_rec_amb'] ?>"><font size="1"><?php// echo $nombre_rec ?> -- C. EXTERNA</font></option>
<?php } ?>

<?php
include "../../conexionbd.php";
$sql_diag="SELECT p.*, d.* from paciente p, dat_ingreso d WHERE d.Id_exp=p.Id_exp and d.cama=0 order by d.id_atencion DESC";
$result_diag=$conexion->query($sql_diag);
foreach ($result_diag as $f ) {
    $nombre_H=$f['papell'].' '.$f['sapell'].' '.$f['nom_pac'];
?>
<option value="nueva_receta_hosp.php?id_atencion=<?php// echo $f['id_atencion'] ?>"><font size="1"><?php //echo $nombre_H ?> -- HOSP.</font></option>
<?php } ?>
        </select>
    </div>
            -->
            <div class="col-sm-8">
    <br><input type="search" id="input-search" class="form-control" placeholder="BUSCAR PACIENTE">
    
    <div class="content-search">
        <div class="content-table">
            <table id="table">
                <thead>
                    <tr>
                        <td></td>
                    </tr>
                </thead>
                
                <tbody>
                <?php
                $sql_diag="SELECT * from receta_ambulatoria order by id_rec_amb desc ";
                $result_diag=$conexion->query($sql_diag);
                while($row = mysqli_fetch_array($result_diag)){
                    $nombre_rec=$row['nombre_rec'].' '.$row['papell_rec'].' '.$row['sapell_rec'];
                    ?>
                    <tr>
                        <td><a href="receta_ant.php?id=<?php echo $row['id_rec_amb'] ?>&nombre=<?php echo $row['nombre_rec']; ?>&papell=<?php echo $row['papell_rec']; ?>&sapell=<?php echo $row['sapell_rec']; ?>" ><?php echo $nombre_rec ?> -- CONSULTA EXTERNA</td>
                    </tr>
                    <?php
                }

            $sql_diag="SELECT p.*, d.* from paciente p, dat_ingreso d WHERE d.Id_exp=p.Id_exp and d.cama=0 order by d.id_atencion DESC";
            $result_diag=$conexion->query($sql_diag);
                while($f = mysqli_fetch_array($result_diag)){
                    $nombre_H=$f['papell'].' '.$f['sapell'].' '.$f['nom_pac'];
                    ?>
                    <tr>
                        <!--<td><a href="nueva_receta_hosp.php?id_atencion=<?php //echo $f['id_atencion'] ?>" ><?php echo $nombre_H?>-- HOSPITALIZACIÓN.</td>-->
                            <td><a href="evolucion.php?id_atencion=<?php echo $f['id_atencion'] ?>" ><?php echo $nombre_H?>-- HOSPITALIZACIÓN.</td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
        </div>
        <?php 
        $id_atencion=$_GET['id_atencion'];
        $select="SELECT * FROM paciente p, dat_ingreso d where d.id_atencion=$id_atencion and d.Id_exp=p.Id_exp";
        $result=$conexion->query($select);
        while($row=$result->fetch_assoc()){
         ?>
        <div class="thead" style="background-color: #0c675e; color: white; font-size: 19px;">
             <center><strong>DATOS DEL PACIENTE </strong></center><p>
        </div>
               <div class="row">
                   <div class="col-sm">
                       <div class="form-group">
                           <label><strong>NOMBRE(S) :</strong></label><br>
                           <input type="text" name="nombre_rec" placeholder="Nombre(s)" value="<?php echo $row['nom_pac'] ?>" class="form-control" style="text-transform:uppercase;" value=""  onkeyup="javascript:this.value=this.value.toUpperCase();">
                       </div>
                   </div>
                   <div class="col-sm">
                       <div class="form-group">
                           <label><strong>PRIMER APELLIDO :</strong></label><br>
                           <input type="text" name="papell_rec" placeholder="Apellido Paterno" value="<?php echo $row['papell'] ?>" class="form-control" style="text-transform:uppercase;" value=""  onkeyup="javascript:this.value=this.value.toUpperCase();">
                       </div>
                   </div>
                   <div class="col-sm ">
                       <div class="form-group">
                           <label><strong>SEGUNDO APELLIDO :</strong></label><br>
                           <input type="text" name="sapell_rec" placeholder="Apellido Materno" value="<?php echo $row['sapell'] ?>" class="form-control" style="text-transform:uppercase;" value=""  onkeyup="javascript:this.value=this.value.toUpperCase();">
                       </div>
                   </div>
               </div>
               <div class="row">
                   <div class="col-sm">
                       <div class="form-group">
                           <label><strong>FECHA DE NACIMIENTO :</strong></label>
                           <input type="date" name="fecnac_rec" class="form-control" value="<?php echo $row['fecnac'] ?>">
                       </div>
                   </div>
                   <div class="col-sm">
                       <div class="form-group">
                           <label><strong>SEXO :</strong></label>
                           <select name="sexo_rec" class="form-control">
                            <option value="<?php echo $row['sexo'] ?>"><?php echo $row['sexo'] ?></option>
                            <option></option>
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
    <div class="col">
        <label><strong>ESPECIALIDAD</strong></label><br>
        <select name="esp" class="form-control" required onchange="habilitar(this.value);">
            <option value="<?php echo $row['tipo_a'] ?>"><?php echo $row['tipo_a'] ?></option>
            <option></option>
            <?php $resultado=$conexion->query("SELECT * FROM cat_espec WHERE espec_activo='SI'") ?>
            <option>SELECCIONAR OPCIÓN</option>
            <option value="OTROS">OTROS</option>
            <?php foreach ($resultado as $row_amb ):?>
                <option value=" <?php echo $row_amb['espec'] ?>"> <?php echo $row_amb['espec'] ?></option>
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
    <div class="col">
       <label>DETALLE ESPECIALIDAD (OTROS)</label><br>
        <input type="text" name="detesp" id="esp" placeholder="DETALLE ESPECIALIDAD" value="" disabled="" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
    </div>
    <div class="col">
        <label><strong>ALERGIA A MEDICAMENTOS:</strong></label>
        <input type="text" name="alergia_rec" placeholder="ALERGIA A MEDICAMENTOS" value="<?php echo $row['alergias'] ?>" class="form-control"  style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    </div>
</div>
<?php } ?>
<br>
      <div class="thead" style="background-color: #0c675e; color: white; font-size: 19px;">
  <center><strong>SIGNOS VITALES</strong></center><p>
</div>
                <div class="container"> <strong>
  <div class="row">
    <div class="col-sm-2"><br><center>PRESIÓN ARTERIAL:</center>
     <div class="row">
  <div class="col"><input type="number" class="form-control" name="p_sistolica" ></div> /
  <div class="col"><input type="number" class="form-control" name="p_diastolica"></div>
 
</div>
    </div>
    <div class="col-sm-2">
      FRECUENCIA CARDIACA:<input type="number" class="form-control" name="f_card" onkeypress="return recetaamb(event);">
    </div>
    <div class="col-sm-2">
      FRECUENCIA RESPIRATORIA:<input type="number" class="form-control" name="f_resp" onkeypress="return recetaamb(event);">
    </div>
    <div class="col-sm-2">
     <br>TEMPERATURA:<input type="cm-number" class="form-control"  name="temp" >
    </div>
    <div class="col-sm-2">
     SATURACIÓN OXÍGENO:<input type="number"  class="form-control" name="sat_oxigeno" onkeypress="return recetaamb(event);">
    </div>
    <div class="col-sm-1">
     PESO (KILOS): <input type="cm-number" class="form-control"  name="peso" >
    </div>
    <div class="col-sm-1">
     TALLA METROS:<input type="cm-number" class="form-control" name="talla" >
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
      <strong><font color="black">SUBJETIVO: DESCRIBIR LA SINTOMATOLOGÍA DEL PACIENTE, HÁBITOS, A QUE ATRIBUYE EL PADECIMIENTO ACTUAL, ETC.</font></strong>
     <div class="form-group">
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="subjetivo" placeholder="SUBJETIVO: DESCRIBIR LA SINTOMATOLOGÍA DEL PACIENTE, HÁBITOS, A QUE ATRIBUYE EL PADECIMIENTO ACTUAL, ETC." class="form-control" id="exampleFormControlTextarea1" rows="3" name="subjetivo"></textarea>
  </div>
    </div>
</div>

<div class="row">
<div class="col-1">
<center><strong><label for="exampleFormControlTextarea1"><br><font size="5"color="#407959">O</font></label></strong></center>
    </div>
    <div class=" col-10">
      <strong><font color="black">OBJETIVO: DESCRIBIR LA EXPLORACIÓN FÍSICA</font></strong>
     <div class="form-group">
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="objetivo" placeholder="OBJETIVO: DESCRIBIR LA EXPLORACIÓN FÍSICA"></textarea>
  </div>
    </div>
</div>

<div class="row">
<div class="col-1">
<center><strong><label for="exampleFormControlTextarea1"><br><font size="5"color="#407959">A</font></label></strong></center>
    </div>
    <div class=" col-10">
          <strong><font color="black">ANÁLISIS: LOS DIAGNÓSTICOS PROBABLES Y DEFINITIVOS Y LOS ARGUMENTOS, CORRELACIÓN DE LOS ESTUDIOS DE LABORATORIO Y GABINETE CON EL PADECIMIENTO ACTUAL.</font></strong>
     <div class="form-group">
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="analisis" placeholder="ANÁLISIS: LOS DIAGNÓSTICOS PROBABLES Y DEFINITIVOS Y LOS ARGUMENTOS, CORRELACIÓN DE LOS ESTUDIOS DE LABORATORIO Y GABINETE CON EL PADECIMIENTO ACTUAL."></textarea>
  </div>
    </div>
</div>

<div class="row">
<div class="col-1">
<center><strong><label for="exampleFormControlTextarea1"><br><font size="5"color="#407959">P</font></label></strong></center>
    </div>
    <div class=" col-10">
<strong><font color="black">PLAN: DESCRIBIR TRATAMIENTO Y RECOMENDACIONES INDICADAS AL PACIENTE, ANOTAR LOS MEDICAMENTOS CON SU DOSIS DE MANERA DETALLADA.</font></strong>      
     <div class="form-group">
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="plan" placeholder="PLAN: DESCRIBIR TRATAMIENTO Y RECOMENDACIONES INDICADAS AL PACIENTE, ANOTAR LOS MEDICAMENTOS CON SU DOSIS DE MANERA DETALLADA."></textarea>
  </div>
    </div>
</div>

<div class="row">
<div class="col-1">
<center><strong><label for="exampleFormControlTextarea1"><br><font size="5"color="#407959">PX</font></label></strong></center>
    </div>
    <div class=" col-10">
      <strong><font color="black">PRONÓSTICO: PARA LA VIDA Y PARA LA FUNCIÓN</font></strong>
     <div class="form-group">
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="px" placeholder="PRONÓSTICO: PARA LA VIDA Y PARA LA FUNCIÓN"></textarea>
  </div>
    </div>
</div>

<div class="row">
    <div class=" col">
     <div class="form-group">
        <div class="thead" style="background-color: #0c675e; color: white; font-size: 19px;">
             <center><strong>M E D I C A M E N T O S</strong></center><p>
        </div>

    <textarea class="form-control" id="exampleFormControlTextarea1" rows="25" name="receta_rec" placeholder="Recetario Médico"></textarea>
  </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-3"></div>
    <div class=" col">
     <div class="form-group">
    <input type="button" id="mybutton" class="btn btn-danger" onclick="showelemet();" value="RECETA MEDICAMENTOS CONTROLADOS"><font id="letra"></font>
  </div>
    </div>
</div>
<div class="row" id="receta">
    <div class=" col">
     <div class="form-group">
        <div class="thead" style="background-color: #0c675e; color: white; font-size: 19px;">
             <center><strong>M E D I C A M E N T O S     C O N T R O L A D O S</strong></center><p>
        </div>

    <textarea class="form-control" rows="15" name="med_cont" placeholder="Recetario Médico Medicamentos Controlados"></textarea>
  </div>
    </div>
</div>
<div class="row">
    <div class=" col">
     <div class="form-group">
        <label><strong>MEDIDAS HIGIÉNICAS-DIETÉTICAS : </strong></label>
        <input type="text" name="med_rec" placeholder="MEDIDAS HIGIÉNICO DIETÉTICAS" class="form-control" >
  </div>
    </div>
</div>


<div class="row">
    <div class="col-sm-4"><strong>FECHA DE PRÓXIMA CITA</strong>
        <input type="date" name="fec_pcita" class="form-control" required="">
    </div>
    <div class="col-sm-2"><strong>HORA :</strong>
        <input type="number" min="00" max="24" name="hor_pcita" class="form-control">
    </div>
    <div class="col-sm-2"><strong>MINUTOS :</strong>
        <input type="number" min="00" max="59" name="min_pcita" class="form-control">
    </div>
</div>
<hr>

<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <button type="submit" name="guardarR" class="btn btn-danger"><font size="3">FIRMAR, GUARDAR <br>E IMPRIMIR RECETA</font></button>
        </div>
        <div class="col-sm-4">
           <button type="submit" name="guardarRN" class="btn btn-danger"><font size="3">FIRMAR, GUARDAR <br>E IMPRIMIR RECETA/NOTA</font></button> 
        </div>
        <div class="col-sm-4">
           <button type="submit" name="guardarRMC" class="btn btn-danger"><font size="3">FIRMAR, GUARDAR <br>E IMPRIMIR R.CONTROLADOS</font></button> 
        </div>    
    </div><hr>
    <div class="row">
        <div class="col-sm-4">
            
        </div>
                <div class="col-sm-4">
                    <button type="button" class="btn btn-danger" onclick="history.back()">CANCELAR</button>
                </div>
                   
               </div>
</div>
       </form><hr>
    </div>
<?php 

/*
//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////
RECETA SOLITA
//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////
*/
if (isset($_POST['guardarR'])) {
      $usuario=$_SESSION['login'];
      $id_usua=$usuario['id_usua'];
        $nombre_rec    = mysqli_real_escape_string($conexion, (strip_tags($_POST["nombre_rec"], ENT_QUOTES)));
        $papell_rec    = mysqli_real_escape_string($conexion, (strip_tags($_POST["papell_rec"], ENT_QUOTES)));
        $sapell_rec  = mysqli_real_escape_string($conexion, (strip_tags($_POST["sapell_rec"], ENT_QUOTES)));
        $fecnac_rec    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fecnac_rec"], ENT_QUOTES))); 
        $sexo_rec    = mysqli_real_escape_string($conexion, (strip_tags($_POST["sexo_rec"], ENT_QUOTES)));

        $alergia_rec  = mysqli_real_escape_string($conexion, (strip_tags($_POST["alergia_rec"], ENT_QUOTES)));
        $p_sistolica    = mysqli_real_escape_string($conexion, (strip_tags($_POST["p_sistolica"], ENT_QUOTES))); 
        $p_diastolica    = mysqli_real_escape_string($conexion, (strip_tags($_POST["p_diastolica"], ENT_QUOTES)));

        $f_card    = mysqli_real_escape_string($conexion, (strip_tags($_POST["f_card"], ENT_QUOTES)));
        $f_resp    = mysqli_real_escape_string($conexion, (strip_tags($_POST["f_resp"], ENT_QUOTES)));
        $temp  = mysqli_real_escape_string($conexion, (strip_tags($_POST["temp"], ENT_QUOTES)));
        $sat_oxigeno    = mysqli_real_escape_string($conexion, (strip_tags($_POST["sat_oxigeno"], ENT_QUOTES))); 
        $peso    = mysqli_real_escape_string($conexion, (strip_tags($_POST["peso"], ENT_QUOTES)));

        $talla    = mysqli_real_escape_string($conexion, (strip_tags($_POST["talla"], ENT_QUOTES)));
        $aseg    = mysqli_real_escape_string($conexion, (strip_tags($_POST["aseg"], ENT_QUOTES)));
        $fec_pcita    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fec_pcita"], ENT_QUOTES)));
        $hor_pcita    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hor_pcita"], ENT_QUOTES)));
        $min_pcita    = mysqli_real_escape_string($conexion, (strip_tags($_POST["min_pcita"], ENT_QUOTES)));

        $hor_pcita=$hor_pcita.':'.$min_pcita.':'.'00';

        $subjetivo  = mysqli_real_escape_string($conexion, (strip_tags($_POST["subjetivo"], ENT_QUOTES)));
    $objetivo  = mysqli_real_escape_string($conexion, (strip_tags($_POST["objetivo"], ENT_QUOTES)));
    $analisis  = mysqli_real_escape_string($conexion, (strip_tags($_POST["analisis"], ENT_QUOTES)));
    $plan  = mysqli_real_escape_string($conexion, (strip_tags($_POST["plan"], ENT_QUOTES)));
    $px  = mysqli_real_escape_string($conexion, (strip_tags($_POST["px"], ENT_QUOTES)));
    $receta_rec  = mysqli_real_escape_string($conexion, (strip_tags($_POST["receta_rec"], ENT_QUOTES)));
    $med_rec  = mysqli_real_escape_string($conexion, (strip_tags($_POST["med_rec"], ENT_QUOTES)));
    $esp    = mysqli_real_escape_string($conexion, (strip_tags($_POST["esp"], ENT_QUOTES)));

        if(isset($_POST["detesp"])){
            $detesp  = mysqli_real_escape_string($conexion, (strip_tags($_POST["detesp"], ENT_QUOTES)));
        }else{
            $detesp=" ";
        }

    if(isset($_POST["med_cont"])){
             $med_cont  = mysqli_real_escape_string($conexion, (strip_tags($_POST["med_cont"], ENT_QUOTES)));
        }else{
             $med_cont=" ";
        }

function calculaedad($fechanacimiento)
{
  list($ano, $mes, $dia) = explode("-", $fechanacimiento);
  $ano_diferencia  = date("Y") - $ano;
  $mes_diferencia = date("m") - $mes;
  $dia_diferencia   = date("d") - $dia;
  if ($ano_diferencia > 0 )
      return ($ano_diferencia . ' AÑOS');
  else if ($mes_diferencia > 0 || $ano_diferencia < 0)
      return ($mes_diferencia . ' MESES');
   else if ($dia_diferencia > 0 || $mes_diferencia < 0 || $ano_diferencia < 0)
      return ($dia_diferencia . ' DÍAS');
}

        $edad = calculaedad($fecnac_rec);
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

$insert=mysqli_query($conexion,'INSERT INTO receta_ambulatoria (id_usua,fecha,nombre_rec,papell_rec,sapell_rec,fecnac_rec,edad,sexo_rec,especialidad,detesp,alerg_rec,receta_rec,med_rec,aseguradora,fec_pcita,hor_pcita,subjetivo,objetivo,analisis,plan,px,p_sistolica,p_diastolica,f_card,f_resp,temp,sat_oxigeno,peso,talla,med_cont) VALUES ('.$id_usua.',"'.$fecha_actual.'","'.$nombre_rec.'","'.$papell_rec.'","'.$sapell_rec.'","'.$fecnac_rec.'","'.$edad.'","'.$sexo_rec.'","'.$esp.'","'.$detesp.'","'.$alergia_rec.'","'.$receta_rec.'","'.$med_rec.'","'.$aseg.'","'.$fec_pcita.'","'.$hor_pcita.'","'.$subjetivo.'","'.$objetivo.'","'.$analisis.'","'.$plan.'","'.$px.'", ' . $p_sistolica . ' , ' . $p_diastolica . ' , ' . $f_card . ' , ' . $f_resp . ' , ' . $temp . ' , ' . $sat_oxigeno . ' , ' . $peso . ' , ' . $talla . ',"' . $med_cont . '")') or die ('<p>Error al registrar</p><br>'.mysqli_error($conexion));

    date_default_timezone_set('America/Guatemala');
$fecha= date("Y-m-d H:i:s");
    $select="SELECT * FROM reg_usuarios where id_usua=$id_usua";
    $result=$conexion->query($select);
    while ($row=$result->fetch_assoc()) {
    $usuario=$row['nombre'].' '.$row['papell'].' '.$row['sapell'];
    }

$nombre=$nombre_rec.' '.$papell_rec.' '.$sapell_rec;


  $insert="INSERT INTO pserv(nombre,fecha,usuario,tipo) values('$nombre','$fecha','$usuario','RECETA')";
$result_insert=$conexion->query($insert);

    $select="SELECT * FROM receta_ambulatoria order by id_rec_amb DESC LIMIT 1";
    $result=$conexion->query($select);
    while ($row=$result->fetch_assoc()) {
    $id_amb=$row['id_rec_amb'];
    }
    echo '<script >window.open("pdf_receta_only.php?id='.$id_amb.'", "RECETA AMBULATORIA", "width=1000, height=1000")</script>'.
 '<script type="text/javascript">window.location.href ="receta_ambulatoria.php" ;</script>';
}

/*
//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////
RECETA Y NOTA
//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////
*/
if (isset($_POST['guardarRN'])) {
    $usuario=$_SESSION['login'];
      $id_usua=$usuario['id_usua'];
        $nombre_rec    = mysqli_real_escape_string($conexion, (strip_tags($_POST["nombre_rec"], ENT_QUOTES)));
        $papell_rec    = mysqli_real_escape_string($conexion, (strip_tags($_POST["papell_rec"], ENT_QUOTES)));
        $sapell_rec  = mysqli_real_escape_string($conexion, (strip_tags($_POST["sapell_rec"], ENT_QUOTES)));
        $fecnac_rec    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fecnac_rec"], ENT_QUOTES))); 
        $sexo_rec    = mysqli_real_escape_string($conexion, (strip_tags($_POST["sexo_rec"], ENT_QUOTES)));

        $alergia_rec  = mysqli_real_escape_string($conexion, (strip_tags($_POST["alergia_rec"], ENT_QUOTES)));
        $p_sistolica    = mysqli_real_escape_string($conexion, (strip_tags($_POST["p_sistolica"], ENT_QUOTES))); 
        $p_diastolica    = mysqli_real_escape_string($conexion, (strip_tags($_POST["p_diastolica"], ENT_QUOTES)));

        $f_card    = mysqli_real_escape_string($conexion, (strip_tags($_POST["f_card"], ENT_QUOTES)));
        $f_resp    = mysqli_real_escape_string($conexion, (strip_tags($_POST["f_resp"], ENT_QUOTES)));
        $temp  = mysqli_real_escape_string($conexion, (strip_tags($_POST["temp"], ENT_QUOTES)));
        $sat_oxigeno    = mysqli_real_escape_string($conexion, (strip_tags($_POST["sat_oxigeno"], ENT_QUOTES))); 
        $peso    = mysqli_real_escape_string($conexion, (strip_tags($_POST["peso"], ENT_QUOTES)));

        $talla    = mysqli_real_escape_string($conexion, (strip_tags($_POST["talla"], ENT_QUOTES)));
        $aseg    = mysqli_real_escape_string($conexion, (strip_tags($_POST["aseg"], ENT_QUOTES)));
        $fec_pcita    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fec_pcita"], ENT_QUOTES)));
        $hor_pcita    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hor_pcita"], ENT_QUOTES)));
        $min_pcita    = mysqli_real_escape_string($conexion, (strip_tags($_POST["min_pcita"], ENT_QUOTES)));

        $hor_pcita=$hor_pcita.':'.$min_pcita.':'.'00';

        $subjetivo  = mysqli_real_escape_string($conexion, (strip_tags($_POST["subjetivo"], ENT_QUOTES)));
    $objetivo  = mysqli_real_escape_string($conexion, (strip_tags($_POST["objetivo"], ENT_QUOTES)));
    $analisis  = mysqli_real_escape_string($conexion, (strip_tags($_POST["analisis"], ENT_QUOTES)));
    $plan  = mysqli_real_escape_string($conexion, (strip_tags($_POST["plan"], ENT_QUOTES)));
    $px  = mysqli_real_escape_string($conexion, (strip_tags($_POST["px"], ENT_QUOTES)));
    $receta_rec  = mysqli_real_escape_string($conexion, (strip_tags($_POST["receta_rec"], ENT_QUOTES)));
    $med_rec  = mysqli_real_escape_string($conexion, (strip_tags($_POST["med_rec"], ENT_QUOTES)));

    $esp   = mysqli_real_escape_string($conexion, (strip_tags($_POST["esp"], ENT_QUOTES)));
        if(isset($_POST["detesp"])){
            $detesp  = mysqli_real_escape_string($conexion, (strip_tags($_POST["detesp"], ENT_QUOTES)));
        }else{
            $detesp=" ";
        }

    if(isset($_POST["med_cont"])){
             $med_cont  = mysqli_real_escape_string($conexion, (strip_tags($_POST["med_cont"], ENT_QUOTES)));
        }else{
             $med_cont=" ";
        }

function calculaedad($fechanacimiento)
{
  list($ano, $mes, $dia) = explode("-", $fechanacimiento);
  $ano_diferencia  = date("Y") - $ano;
  $mes_diferencia = date("m") - $mes;
  $dia_diferencia   = date("d") - $dia;
  if ($ano_diferencia > 0 )
      return ($ano_diferencia . ' AÑOS');
  else if ($mes_diferencia > 0 || $ano_diferencia < 0)
      return ($mes_diferencia . ' MESES');
   else if ($dia_diferencia > 0 || $mes_diferencia < 0 || $ano_diferencia < 0)
      return ($dia_diferencia . ' DÍAS');
}

        $edad = calculaedad($fecnac_rec);
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

$insert=mysqli_query($conexion,'INSERT INTO receta_ambulatoria (id_usua,fecha,nombre_rec,papell_rec,sapell_rec,fecnac_rec,edad,sexo_rec,especialidad,detesp,alerg_rec,receta_rec,med_rec,aseguradora,fec_pcita,hor_pcita,subjetivo,objetivo,analisis,plan,px,p_sistolica,p_diastolica,f_card,f_resp,temp,sat_oxigeno,peso,talla,med_cont) VALUES ('.$id_usua.',"'.$fecha_actual.'","'.$nombre_rec.'","'.$papell_rec.'","'.$sapell_rec.'","'.$fecnac_rec.'","'.$edad.'","'.$sexo_rec.'","'.$esp.'","'.$detesp.'","'.$alergia_rec.'","'.$receta_rec.'","'.$med_rec.'","'.$aseg.'","'.$fec_pcita.'","'.$hor_pcita.'","'.$subjetivo.'","'.$objetivo.'","'.$analisis.'","'.$plan.'","'.$px.'", ' . $p_sistolica . ' , ' . $p_diastolica . ' , ' . $f_card . ' , ' . $f_resp . ' , ' . $temp . ' , ' . $sat_oxigeno . ' , ' . $peso . ' , ' . $talla . ',"' . $med_cont . '")') or die ('<p>Error al registrar</p><br>'.mysqli_error($conexion));

    date_default_timezone_set('America/Guatemala');
$fecha= date("Y-m-d H:i:s");
    $select="SELECT * FROM reg_usuarios where id_usua=$id_usua";
    $result=$conexion->query($select);
    while ($row=$result->fetch_assoc()) {
    $usuario=$row['nombre'].' '.$row['papell'].' '.$row['sapell'];
    }

$nombre=$nombre_rec.' '.$papell_rec.' '.$sapell_rec;


  $insert="INSERT INTO pserv(nombre,fecha,usuario,tipo) values('$nombre','$fecha','$usuario','RECETA')";
$result_insert=$conexion->query($insert);

    $select="SELECT * FROM receta_ambulatoria order by id_rec_amb DESC LIMIT 1";
    $result=$conexion->query($select);
    while ($row=$result->fetch_assoc()) {
    $id_amb=$row['id_rec_amb'];
    }
    echo '<script >window.open("pdf_receta_amb.php?id='.$id_amb.'", "RECETA AMBULATORIA", "width=1000, height=1000")</script>'.
 '<script type="text/javascript">window.location.href ="receta_ambulatoria.php" ;</script>';
}

/*
//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////
RECETA Y MEDICAMENTOS CONTROLADOS
//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////
*/
if (isset($_POST['guardarRMC'])) {

    $usuario=$_SESSION['login'];
      $id_usua=$usuario['id_usua'];
        $nombre_rec    = mysqli_real_escape_string($conexion, (strip_tags($_POST["nombre_rec"], ENT_QUOTES)));
        $papell_rec    = mysqli_real_escape_string($conexion, (strip_tags($_POST["papell_rec"], ENT_QUOTES)));
        $sapell_rec  = mysqli_real_escape_string($conexion, (strip_tags($_POST["sapell_rec"], ENT_QUOTES)));
        $fecnac_rec    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fecnac_rec"], ENT_QUOTES))); 
        $sexo_rec    = mysqli_real_escape_string($conexion, (strip_tags($_POST["sexo_rec"], ENT_QUOTES)));

        $alergia_rec  = mysqli_real_escape_string($conexion, (strip_tags($_POST["alergia_rec"], ENT_QUOTES)));
        $p_sistolica    = mysqli_real_escape_string($conexion, (strip_tags($_POST["p_sistolica"], ENT_QUOTES))); 
        $p_diastolica    = mysqli_real_escape_string($conexion, (strip_tags($_POST["p_diastolica"], ENT_QUOTES)));

        $f_card    = mysqli_real_escape_string($conexion, (strip_tags($_POST["f_card"], ENT_QUOTES)));
        $f_resp    = mysqli_real_escape_string($conexion, (strip_tags($_POST["f_resp"], ENT_QUOTES)));
        $temp  = mysqli_real_escape_string($conexion, (strip_tags($_POST["temp"], ENT_QUOTES)));
        $sat_oxigeno    = mysqli_real_escape_string($conexion, (strip_tags($_POST["sat_oxigeno"], ENT_QUOTES))); 
        $peso    = mysqli_real_escape_string($conexion, (strip_tags($_POST["peso"], ENT_QUOTES)));

        $talla    = mysqli_real_escape_string($conexion, (strip_tags($_POST["talla"], ENT_QUOTES)));
        $aseg    = mysqli_real_escape_string($conexion, (strip_tags($_POST["aseg"], ENT_QUOTES)));
        $fec_pcita    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fec_pcita"], ENT_QUOTES)));
        $hor_pcita    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hor_pcita"], ENT_QUOTES)));
        $min_pcita    = mysqli_real_escape_string($conexion, (strip_tags($_POST["min_pcita"], ENT_QUOTES)));

        $hor_pcita=$hor_pcita.':'.$min_pcita.':'.'00';

        $subjetivo  = mysqli_real_escape_string($conexion, (strip_tags($_POST["subjetivo"], ENT_QUOTES)));
    $objetivo  = mysqli_real_escape_string($conexion, (strip_tags($_POST["objetivo"], ENT_QUOTES)));
    $analisis  = mysqli_real_escape_string($conexion, (strip_tags($_POST["analisis"], ENT_QUOTES)));
    $plan  = mysqli_real_escape_string($conexion, (strip_tags($_POST["plan"], ENT_QUOTES)));
    $px  = mysqli_real_escape_string($conexion, (strip_tags($_POST["px"], ENT_QUOTES)));
    $receta_rec  = mysqli_real_escape_string($conexion, (strip_tags($_POST["receta_rec"], ENT_QUOTES)));
    $med_rec  = mysqli_real_escape_string($conexion, (strip_tags($_POST["med_rec"], ENT_QUOTES)));

    $esp    = mysqli_real_escape_string($conexion, (strip_tags($_POST["esp"], ENT_QUOTES)));
        if(isset($_POST["detesp"])){
            $detesp  = mysqli_real_escape_string($conexion, (strip_tags($_POST["detesp"], ENT_QUOTES)));
        }else{
            $detesp=" ";
        }

    if(isset($_POST["med_cont"])){
             $med_cont  = mysqli_real_escape_string($conexion, (strip_tags($_POST["med_cont"], ENT_QUOTES)));
        }else{
             $med_cont=" ";
        }

function calculaedad($fechanacimiento)
{
  list($ano, $mes, $dia) = explode("-", $fechanacimiento);
  $ano_diferencia  = date("Y") - $ano;
  $mes_diferencia = date("m") - $mes;
  $dia_diferencia   = date("d") - $dia;
  if ($ano_diferencia > 0 )
      return ($ano_diferencia . ' AÑOS');
  else if ($mes_diferencia > 0 || $ano_diferencia < 0)
      return ($mes_diferencia . ' MESES');
   else if ($dia_diferencia > 0 || $mes_diferencia < 0 || $ano_diferencia < 0)
      return ($dia_diferencia . ' DÍAS');
}

        $edad = calculaedad($fecnac_rec);
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

$insert=mysqli_query($conexion,'INSERT INTO receta_ambulatoria (id_usua,fecha,nombre_rec,papell_rec,sapell_rec,fecnac_rec,edad,sexo_rec,especialidad,detesp,alerg_rec,receta_rec,med_rec,aseguradora,fec_pcita,hor_pcita,subjetivo,objetivo,analisis,plan,px,p_sistolica,p_diastolica,f_card,f_resp,temp,sat_oxigeno,peso,talla,med_cont) VALUES ('.$id_usua.',"'.$fecha_actual.'","'.$nombre_rec.'","'.$papell_rec.'","'.$sapell_rec.'","'.$fecnac_rec.'","'.$edad.'","'.$sexo_rec.'","'.$esp.'","'.$detesp.'","'.$alergia_rec.'","'.$receta_rec.'","'.$med_rec.'","'.$aseg.'","'.$fec_pcita.'","'.$hor_pcita.'","'.$subjetivo.'","'.$objetivo.'","'.$analisis.'","'.$plan.'","'.$px.'", ' . $p_sistolica . ' , ' . $p_diastolica . ' , ' . $f_card . ' , ' . $f_resp . ' , ' . $temp . ' , ' . $sat_oxigeno . ' , ' . $peso . ' , ' . $talla . ',"' . $med_cont . '")') or die ('<p>Error al registrar</p><br>'.mysqli_error($conexion));

    date_default_timezone_set('America/Guatemala');
$fecha= date("Y-m-d H:i:s");
    $select="SELECT * FROM reg_usuarios where id_usua=$id_usua";
    $result=$conexion->query($select);
    while ($row=$result->fetch_assoc()) {
    $usuario=$row['nombre'].' '.$row['papell'].' '.$row['sapell'];
    }

$nombre=$nombre_rec.' '.$papell_rec.' '.$sapell_rec;


  $insert="INSERT INTO pserv(nombre,fecha,usuario,tipo) values('$nombre','$fecha','$usuario','RECETA')";
$result_insert=$conexion->query($insert);

    $select="SELECT * FROM receta_ambulatoria order by id_rec_amb DESC LIMIT 1";
    $result=$conexion->query($select);
    while ($row=$result->fetch_assoc()) {
    $id_amb=$row['id_rec_amb'];
    }
    echo '<script >window.open("pdf_receta_only_cont.php?id='.$id_amb.'", "RECETA AMBULATORIA", "width=1000, height=1000")</script>'.
 '<script type="text/javascript">window.location.href ="receta_ambulatoria.php" ;</script>';
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
<!-- FastClick -->
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>

<!-- AdminLTE App -->
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
<script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/main.js"></script>
<script>
    document.oncontextmenu = function () {
        return false;
    }
</script>
<script src="js_busc/search.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#mibuscador').select2();
    });
</script>

<script type="text/javascript">
    $("#receta").hide();
    function showelemet(){
        let text="";
        if($("#mybutton").text()==="RECETA MEDICAMENTOS CONTROLADOS"){
            $("#receta").show();
            text="OCULTAR RECETA MEDICAMENTOS CONTROLADOS";
        } else{
            $("#receta").hide();
            text="RECETA MEDICAMENTOS CONTROLADOS";
        }
        $("#mybutton").html(text);
    }
</script>
</body>
</html>