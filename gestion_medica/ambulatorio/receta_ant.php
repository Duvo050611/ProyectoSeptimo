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
S
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
  <style>
    hr.new4 {
      border: 1px solid red;
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
    <div class="row">
        <?php 
        $nombre_rec=$_GET['nombre'];
        $papell_rec=$_GET['papell'];
        $sapell_rec=$_GET['sapell'];
         ?>
        <div class="col-sm-4">
            <a href="nueva_receta.php?nombre=<?php echo $nombre_rec ?>&papell=<?php echo $papell_rec ?>&sapell=<?php echo $sapell_rec ?>"><button type="button" class="btn btn-danger" >
            <i class="fa fa-plus"></i> <font id="letra"> NUEVA RECETA MÉDICA</font></button></a>
        </div>
    </div>
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
        //$id_amb=$_GET['id'];
        $nombre_rec=$_GET['nombre'];
        $papell_rec=$_GET['papell'];
        $sapell_rec=$_GET['sapell'];

        $select="SELECT * FROM receta_ambulatoria where nombre_rec='$nombre_rec' and papell_rec='$papell_rec' and sapell_rec='$sapell_rec' order by id_rec_amb desc";
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
                           <input type="text" name="nombre_rec" placeholder="Nombre(s)" value="<?php echo $row['nombre_rec'] ?>" class="form-control" style="text-transform:uppercase;" value=""  onkeyup="javascript:this.value=this.value.toUpperCase();" disabled>
                       </div>
                   </div>
                   <div class="col-sm">
                       <div class="form-group">
                           <label><strong>PRIMER APELLIDO :</strong></label><br>
                           <input type="text" name="papell_rec" placeholder="Apellido Paterno" value="<?php echo $row['papell_rec'] ?>" class="form-control" style="text-transform:uppercase;" value=""  onkeyup="javascript:this.value=this.value.toUpperCase();"disabled>
                       </div>
                   </div>
                   <div class="col-sm ">
                       <div class="form-group">
                           <label><strong>SEGUNDO APELLIDO :</strong></label><br>
                           <input type="text" name="sapell_rec" placeholder="Apellido Materno" value="<?php echo $row['sapell_rec'] ?>" class="form-control" style="text-transform:uppercase;" value=""  onkeyup="javascript:this.value=this.value.toUpperCase();"disabled>
                       </div>
                   </div>
               </div>
               <div class="row">
                   <div class="col-sm">
                       <div class="form-group">
                           <label><strong>FECHA DE NACIMIENTO :</strong></label>
                           <input type="date" name="fecnac_rec" class="form-control" value="<?php echo $row['fecnac_rec'] ?>"disabled>
                       </div>
                   </div>
                   <div class="col-sm">
                       <div class="form-group">
                           <label><strong>SEXO :</strong></label>
                           <select name="sexo_rec" class="form-control" disabled>
                            <option value="<?php echo $row['sexo_rec'] ?>"><?php echo $row['sexo_rec'] ?></option>
                           </select>
                       </div>
                   </div>
                   <div class="col-sm">
                                <div class="form-group">
                                    <label for="aseg"><strong>ASEGURADORA:</strong></label><br>
                                    <select name="aseg" class="form-control" required disabled>
                                        <option value="<?php echo $row['aseguradora'] ?>"><?php echo $row['aseguradora'] ?></option>
                                    </select>
                                </div>
                            </div>
               </div>
      
<div class="row">
    <div class="col">
        <label><strong>ESPECIALIDAD</strong></label><br>
        <select name="esp" class="form-control" required onchange="habilitar(this.value);"disabled>
            <option value="<?php echo $row['especialidad'] ?>"><?php echo $row['especialidad'] ?></option>
        </select>
    </div>
    <div class="col">
       <label>DETALLE ESPECIALIDAD (OTROS)</label><br>
        <input type="text" name="detesp" id="esp" placeholder="DETALLE ESPECIALIDAD" value="<?php echo $row['detesp'] ?>" disabled="" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" disabled>
    </div>
    <div class="col">
        <label><strong>ALERGIA A MEDICAMENTOS:</strong></label>
        <input type="text" name="alergia_rec" placeholder="ALERGIA A MEDICAMENTOS" value="<?php echo $row['alerg_rec'] ?>" class="form-control"  style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" disabled>
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
  <div class="col"><input type="number" class="form-control" name="p_sistolica" value="<?php echo $row['p_sistolica'] ?>"></div> /
  <div class="col"><input type="number" class="form-control" name="p_diastolica" value="<?php echo $row['p_diastolica'] ?>"></div>
 
</div>
    </div>
    <div class="col-sm-2">
      FRECUENCIA CARDIACA:<input type="number" class="form-control" name="f_card" onkeypress="return recetaamb(event);"value="<?php echo $row['f_card'] ?>">
    </div>
    <div class="col-sm-2">
      FRECUENCIA RESPIRATORIA:<input type="number" class="form-control" name="f_resp" onkeypress="return recetaamb(event);"value="<?php echo $row['f_resp'] ?>">
    </div>
    <div class="col-sm-2">
     <br>TEMPERATURA:<input type="cm-number" class="form-control"  name="temp" value="<?php echo $row['temp'] ?>">
    </div>
    <div class="col-sm-2">
     SATURACIÓN OXÍGENO:<input type="number"  class="form-control" name="sat_oxigeno" onkeypress="return recetaamb(event);"value="<?php echo $row['sat_oxigeno'] ?>">
    </div>
    <div class="col-sm-1">
     PESO (KILOS): <input type="cm-number" class="form-control"  name="peso"value="<?php echo $row['peso'] ?>" >
    </div>
    <div class="col-sm-1">
     TALLA METROS:<input type="cm-number" class="form-control" name="talla"value="<?php echo $row['talla'] ?>">
    </div>
  </div> </strong>
</div><br>
               <div class="row">
    <div class=" col">
     <div class="form-group">
        
  </div>
    </div>
</div>
 <div class="row">
<div class="col-1">
<center><strong><label for="exampleFormControlTextarea1"><br><font size="5" color="#407959">S</font></label></strong></center>
    </div>
    <div class=" col-10">
      <strong><font color="black">SUBJETIVO: DESCRIBIR LA SINTOMATOLOGÍA DEL PACIENTE, HÁBITOS, A QUE ATRIBUYE EL PADECIMIENTO ACTUAL, ETC.</font></strong>
     <div class="form-group">
    <textarea class="form-control" rows="3" name="subjetivo" placeholder="SUBJETIVO: DESCRIBIR LA SINTOMATOLOGÍA DEL PACIENTE, HÁBITOS, A QUE ATRIBUYE EL PADECIMIENTO ACTUAL, ETC." class="form-control" id="exampleFormControlTextarea1" rows="3" name="subjetivo"><?php echo $row['subjetivo'] ?></textarea>
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
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="objetivo" placeholder="OBJETIVO: DESCRIBIR LA EXPLORACIÓN FÍSICA"><?php echo $row['objetivo'] ?></textarea>
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
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="analisis" placeholder="ANÁLISIS: LOS DIAGNÓSTICOS PROBABLES Y DEFINITIVOS Y LOS ARGUMENTOS, CORRELACIÓN DE LOS ESTUDIOS DE LABORATORIO Y GABINETE CON EL PADECIMIENTO ACTUAL."><?php echo $row['analisis'] ?></textarea>
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
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="plan" placeholder="PLAN: DESCRIBIR TRATAMIENTO Y RECOMENDACIONES INDICADAS AL PACIENTE, ANOTAR LOS MEDICAMENTOS CON SU DOSIS DE MANERA DETALLADA."><?php echo $row['plan'] ?></textarea>
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
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="px" placeholder="PRONÓSTICO: PARA LA VIDA Y PARA LA FUNCIÓN"><?php echo $row['px'] ?></textarea>
  </div>
    </div>
</div>

<div class="row">
    <div class=" col">
     <div class="form-group">
        <div class="thead" style="background-color: #0c675e; color: white; font-size: 19px;">
             <center><strong>M E D I C A M E N T O S</strong></center><p>
        </div>

    <textarea class="form-control" id="exampleFormControlTextarea1" rows="25" name="receta_rec" placeholder="Recetario Médico"><?php echo $row['receta_rec'] ?></textarea>
  </div>
    </div>
</div>
<div class="row">
    <div class=" col">
     <div class="form-group">
        <label><strong>MEDIDAS HIGIÉNICAS-DIETÉTICAS : </strong></label>
        <input type="text" name="med_rec" placeholder="MEDIDAS HIGIÉNICO DIETÉTICAS" class="form-control" value="<?php echo $row['med_rec'] ?>">
  </div>
    </div>
</div>
<hr class="new4"><hr class="new4"><hr class="new4"><?php } ?>
    </div>
    
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