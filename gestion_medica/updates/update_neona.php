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
</head>
<body>
<div class="container">
<div class="row">
<div class="col">
    <hr>
<div class="thead" style="background-color: #0c675e; color: white; font-size: 24px;"><strong><center>NOTA NEONATOLÓGICA</center> </strong></div>
 <hr>
<?php
    include "../../conexionbd.php";
    if (isset($_SESSION['hospital'])) {
      $id_atencion = $_SESSION['hospital'];

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

      $edad = calculaedad($pac_fecnac);

    ?>
 <font size="2">
         
  <div class="row">
    <div class="col-sm-2">
      EXPEDIENTE: <strong><?php echo $id_exp?> </strong>
    </div>
    <div class="col-sm-6">
     PACIENTE: <strong><?php echo $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac ?></strong>
    </div>
    
   <?php $date = date_create($pac_fecing);
   ?>
      <div class="col-sm-4">
      FECHA DE INGRESO: <strong><?php echo date_format($date, "d-m-Y H:i:s") ?></strong>
    </div>
  </div>
</font>
 <font size="2">
     
  <div class="row">
    <div class="col-sm-4">
       <?php $date1 = date_create($pac_fecnac);
   ?>
    <!-- INICIO DE FUNCION DE CALCULAR EDAD -->
<?php 
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d");
$fecha_nac=$pac_fecnac;
$fecha_de_nacimiento =strval($fecha_nac);

// separamos en partes las fechas
$array_nacimiento = explode ( "-", $fecha_de_nacimiento );
$array_actual = explode ( "-", $fecha_actual );

$anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos años
$meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses
$dias =  $array_actual[2] - $array_nacimiento[2]; // calculamos días

//ajuste de posible negativo en $días
if ($dias < 0)
{
    --$meses;

    //ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual
    switch ($array_actual[1]) {
           case 1:     $dias_mes_anterior=31; break;
           case 2:     $dias_mes_anterior=31; break;
           case 3: 
                if (bisiesto($array_actual[0]))
                {
                    $dias_mes_anterior=29; break;
                } else {
                    $dias_mes_anterior=28; break;
                }
           case 4:     $dias_mes_anterior=31; break;
           case 5:     $dias_mes_anterior=30; break;
           case 6:     $dias_mes_anterior=31; break;
           case 7:     $dias_mes_anterior=30; break;
           case 8:     $dias_mes_anterior=31; break;
           case 9:     $dias_mes_anterior=31; break;
           case 10:     $dias_mes_anterior=30; break;
           case 11:     $dias_mes_anterior=31; break;
           case 12:     $dias_mes_anterior=30; break;
    }

    $dias=$dias + $dias_mes_anterior;
}

//ajuste de posible negativo en $meses
if ($meses < 0)
{
    --$anos;
    $meses=$meses + 12;
}

//echo "<br>Tu edad es: $anos años con $meses meses y $dias días";

function bisiesto($anio_actual){
    $bisiesto=false;
    //probamos si el mes de febrero del año actual tiene 29 días
      if (checkdate(2,29,$anio_actual))
      {
        $bisiesto=true;
    }
    return $bisiesto;
}

 ?>
 <!-- TERMINO DE FUNCION DE CALCULAR EDAD -->
      FECHA DE  NACIMIENTO: <strong><?php echo date_format($date1, "d-m-Y") ?></strong>
    </div>
    <div class="col-sm-4">
      EDAD: <strong><?php if($anos > "0" ){
   echo $anos." AÑOS";
}elseif($anos <="0" && $meses>"0"){
    echo $meses." MESES";
}elseif($anos <="0" && $meses<="0" && $dias>"0"){
    echo $dias." DIAS";
}
?></strong>
    </div>
    
   
      <div class="col-sm-2">
      HABITACIÓN: <strong><?php $sql_hab = "SELECT num_cama from cat_camas where id_atencion =$id_atencion";
$result_hab = $conexion->query($sql_hab);                                                                                    while ($row_hab = $result_hab->fetch_assoc()) {
  echo $row_hab['num_cama'];
} ?></strong>
    </div>
    
  </div>

</font>
 <font size="2">
  <div class="row">
     <div class="col-sm-8">
      DIAGNÓSTICO MÉDICO: <strong><?php $sql_mot = "SELECT motivo_atn from dat_ingreso where id_atencion=$id_atencion ORDER by motivo_atn ASC LIMIT 1";
$result_mot = $conexion->query($sql_mot);                                                                                    while ($row_mot = $result_mot->fetch_assoc()) {
  echo $row_mot['motivo_atn'];
} ?></strong>
    </div>
  </div>

</font>
 <font size="2">
  <div class="row">
    <div class="col-sm-4">
      ALERGIAS: <strong><?php echo $alergias ?></strong>
    </div>
     <div class="col-sm-4">
      ESTADO DE SALUD: <strong><?php $sql_edo = "SELECT edo_salud from dat_ingreso where id_atencion=$id_atencion ORDER by edo_salud ASC LIMIT 1";
$result_edo = $conexion->query($sql_edo);while ($row_edo = $result_edo->fetch_assoc()) {
  echo $row_edo['edo_salud'];
} ?></strong>
    </div>
    <div class="col-sm-3">
      TIPO DE SANGRE: <strong><?php echo $pac_tip_sang ?></strong>
    </div>
  </div>
</font>
<?php $sql_edo = "SELECT * from signos_vitales where id_atencion=$id_atencion ORDER by id_sig ASC LIMIT 1";
$result_edo = $conexion->query($sql_edo);
while ($row_edo = $result_edo->fetch_assoc()) {
  $peso=$row_edo['peso'];
  $talla=$row_edo['talla'];
} ?>
 <font size="2">
  <div class="row">
     <div class="col-sm-4">
      PESO: <strong><?php echo $peso ?></strong>
    </div>
    <div class="col-sm-3">
      TALLA: <strong><?php echo $talla ?></strong>
    </div>
  </div>
</font>
<hr>
</div>
        <?php
      } else {
        echo '<script type="text/javascript"> window.location.href="../lista_pacientes/lista_pacientes.php";</script>';
      }
        ?> 
</div>
</div>
<div class="col-sm-12">
    <div class="container">
<div class="tab-content" id="nav-tabContent">
   <!--INICIO DE NOTA DE EVOLUCION-->
<div class="tab-pane fade show active" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">

<?php 
$id_neona=$_GET['id_neona'];
$neo="SELECT * FROM dat_not_neona where id_neona=$id_neona";
$result=$conexion->query($neo);
while ($row=$result->fetch_assoc()) {
    $id_usua=$row['id_usua'];
 ?>
          <form action="" method="POST">
            <div class="row">
<div class="col-5">
   <strong><label for="idrecien_nacido"><font size="5" color="#407959"> IDENTIFIACIÓN DEL RECIEN NACIDO: </font></label></strong>
</div>
    <div class=" col-6">
     <div class="form-group">
       <input type="text" name="idrecien_nacido" class="form-control" value="<?php echo $row['idrecien_nacido'] ?>" placeholder="IDENTIFIACIÓN DEL RECIEN NACIDO" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" required >
       <strong><label> FECHA Y HORA DE NACIMIENTO</label></strong>
       <div class="row">
         <div class="col-sm-4">
         <input type="date" name="fecnacimiento" value="<?php echo $row['fecnacimiento'] ?>" class="form-control" required>
        </div>
        <div class="col-sm-4">
          <input type="time" name="horanac" value="<?php echo $row['horanac'] ?>" class="form-control" required>
        </div>
</div>
     </div>
    </div>
</div>
  <div class="thead" style="background-color: #0c675e; color: white; font-size: 19px;">
  <center><strong>SIGNOS VITALES DEL RECIEN NACIDO</strong></center><p>
</div>
                <div class="container"> 
  <div class="row">
    <div class="col-sm-2"><br><center>PRESIÓN ARTERIAL:</center>
     <div class="row">
  <div class="col"><input type="text" class="form-control" name="p_sistol" value="<?php echo $row['p_sistol'] ?>" ></div> /
  <div class="col"><input type="text" class="form-control" name="p_diastol" value="<?php echo $row['p_diastol'] ?>"></div>
 
</div>
    </div>
    <div class="col-sm-2">
      FRECUENCIA CARDIACA:<input type="text" class="form-control" name="fcard" value="<?php echo $row['fcard'] ?>">
    </div>
    <div class="col-sm-2">
      FRECUENCIA RESPIRATORIA:<input type="text" class="form-control" name="fresp" value="<?php echo $row['fresp'] ?>">
    </div>
    <div class="col-sm-2">
     <br>TEMPERATURA:<input type="text" class="form-control"  name="temper" value="<?php echo $row['temper'] ?>">
    </div>
    <div class="col-sm-2">
     SATURACIÓN OXÍGENO:<input type="text"  class="form-control" name="satoxi" value="<?php echo $row['satoxi'] ?>">
    </div>
    <div class="col-sm-1">
     <br>PESO:<input type="text" class="form-control"  name="peso" value="<?php echo $row['peso'] ?>">
    </div>
    <div class="col-sm-1">
     <br>TALLA:<input type="text" class="form-control" name="talla" value="<?php echo $row['talla'] ?>">
    </div>
    <div class="col-sm-2">
     <br>APGAR:<input type="text" class="form-control" name="apgar" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" value="<?php echo $row['apgar'] ?>">
    </div>
    <div class="col-sm-2">
     <br>SILVERMAN:<input type="text" class="form-control" name="silver" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" value="<?php echo $row['silver'] ?>">
    </div>
     <div class="col-sm-4">
     <br>ANOMALIAS CONGENITAS:<input type="text" class="form-control" name="an" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" value="<?php echo $row['an'] ?>">
    </div>
  </div>
</div>
<hr>

  <div class="row">
<div class="col-1">
<center><strong><label for="exampleFormControlTextarea1"><br><font size="5" color="#407959">P</font></label></strong></center>
    </div>
    <div class=" col-10">
  <strong><font color="black">PACIENTE: DESCRIBIR AL PACIENTE (EDAD,GÉNERO, DIAGNÓSTICOS Y TRATAMIENTOS PREVIOS)PRONÓSTICO: PARA LA VIDA Y PARA LA FUNCIÓN</font></strong>

     <div class="form-group">
    <textarea name="pac_neon" class="form-control" rows="5"><?php echo $row['pac_neon'] ?></textarea>
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
      <textarea name="subjetivo_neon" class="form-control" rows="5"><?php echo $row['subjetivo_neon'] ?></textarea>
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
     <textarea name="objetivo_neon" class="form-control" rows="5"><?php echo $row['objetivo_neon'] ?></textarea>
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
        <textarea name="analisis_neon" class="form-control" rows="5"><?php echo $row['analisis_neon'] ?></textarea>
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
        <textarea name="plan_neon" class="form-control" rows="5"><?php echo $row['plan_neon'] ?></textarea>
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
        <textarea name="px_neon" class="form-control" rows="5"><?php echo $row['px_neon'] ?></textarea>
  </div>
    </div>
</div>
<div class="row">
<div class=" col"><STRONG>GUIA DE PRÁCTICA CLÍNICA</STRONG>
    <div class="form-group">
        <textarea name="guia" class="form-control" rows="5"><?php echo $row['guia'] ?></textarea>
    </div>
</div>
</div>
<hr>
<div class="row">
<div class="col-3">
<center><strong><label for="edosalud_neon"><font size="5"color="#407959">ESTADO DE SALUD:</font></label></strong></center>
    </div>
<div class=" col-5">
<select class="form-control" aria-label="edosalud" name="edosalud_neon">
  <option value="<?php echo $row['edosalud_neon'] ?>"><?php echo $row['edosalud_neon'] ?></option>
  <option ></option>  
  <option >SELECCIONAR ESTADO DE SALUD</option>
  <option value="ESTABLE">ESTABLE</option>
  <option value="DELICADO">DELICADO</option>
  <option value="GRAVE">GRAVE</option>
  <option value="ALTA MEDICA">ALTA MÉDICA</option>
</select>
</div>
</div>
<?php 
$date=date_create($row['fecha_neona']);
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
<div class="form-group col-12">
<center><button type="submit" name="guardar" class="btn btn-primary">FIRMAR</button>
<button type="button" class="btn btn-danger" onclick="history.back()">CANCELAR</button></center>
</div>


<br>
<br>
</form>
</div>

   <!--TERMINO DE NOTA DE EVOLUCION-->

  <?php 
  if (isset($_POST['guardar'])) {
  
        $fecnacimiento   = mysqli_real_escape_string($conexion, (strip_tags($_POST["fecnacimiento"], ENT_QUOTES)));
        $horanac   = mysqli_real_escape_string($conexion, (strip_tags($_POST["horanac"], ENT_QUOTES)));
        $idrecien_nacido    = mysqli_real_escape_string($conexion, (strip_tags($_POST["idrecien_nacido"], ENT_QUOTES))); //Escanpando caracteres
        $pac_neon    = mysqli_real_escape_string($conexion, (strip_tags($_POST["pac_neon"], ENT_QUOTES))); //Escanpando caracteres
        $subjetivo_neon   = mysqli_real_escape_string($conexion, (strip_tags($_POST["subjetivo_neon"], ENT_QUOTES)));
        $objetivo_neon    = mysqli_real_escape_string($conexion, (strip_tags($_POST["objetivo_neon"], ENT_QUOTES))); //Escanpando caracteres
        $analisis_neon    = mysqli_real_escape_string($conexion, (strip_tags($_POST["analisis_neon"], ENT_QUOTES)));
        $plan_neon    = mysqli_real_escape_string($conexion, (strip_tags($_POST["plan_neon"], ENT_QUOTES)));
        $px_neon    = mysqli_real_escape_string($conexion, (strip_tags($_POST["px_neon"], ENT_QUOTES)));
        $guia    = mysqli_real_escape_string($conexion, (strip_tags($_POST["guia"], ENT_QUOTES)));
        $edosalud_neon    = mysqli_real_escape_string($conexion, (strip_tags($_POST["edosalud_neon"], ENT_QUOTES)));
        $p_sistol    = mysqli_real_escape_string($conexion, (strip_tags($_POST["p_sistol"], ENT_QUOTES)));
        $p_diastol    = mysqli_real_escape_string($conexion, (strip_tags($_POST["p_diastol"], ENT_QUOTES)));
        $fcard    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fcard"], ENT_QUOTES)));
        $fresp   = mysqli_real_escape_string($conexion, (strip_tags($_POST["fresp"], ENT_QUOTES)));
        $temper    = mysqli_real_escape_string($conexion, (strip_tags($_POST["temper"], ENT_QUOTES)));
        $satoxi    = mysqli_real_escape_string($conexion, (strip_tags($_POST["satoxi"], ENT_QUOTES)));
        $peso    = mysqli_real_escape_string($conexion, (strip_tags($_POST["peso"], ENT_QUOTES)));
        $talla    = mysqli_real_escape_string($conexion, (strip_tags($_POST["talla"], ENT_QUOTES)));
        $apgar    = mysqli_real_escape_string($conexion, (strip_tags($_POST["apgar"], ENT_QUOTES)));
        $silver    = mysqli_real_escape_string($conexion, (strip_tags($_POST["silver"], ENT_QUOTES)));
        $an    = mysqli_real_escape_string($conexion, (strip_tags($_POST["an"], ENT_QUOTES)));
        $fecha= mysqli_real_escape_string($conexion,(strip_tags($_POST["fecha"], ENT_QUOTES)));
        $hora= mysqli_real_escape_string($conexion,(strip_tags($_POST["hora"], ENT_QUOTES)));
        $medico= mysqli_real_escape_string($conexion,(strip_tags($_POST["medico"], ENT_QUOTES)));

        $merge = $fecha.' '.$hora;
       
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

        $sql2 = "UPDATE dat_not_neona SET id_usua='$medico',fecha_neona='$merge',idrecien_nacido='$idrecien_nacido', pac_neon='$pac_neon', subjetivo_neon='$subjetivo_neon', objetivo_neon='$objetivo_neon', analisis_neon='$analisis_neon', plan_neon='$plan_neon' , px_neon='$px_neon', guia='$guia', edosalud_neon='$edosalud_neon', p_sistol='$p_sistol', p_diastol='$p_diastol', fcard='$fcard', fresp='$fresp', temper='$temper', satoxi='$satoxi', peso='$peso', talla='$talla', apgar='$apgar', silver='$silver', an='$an', fecnacimiento='$fecnacimiento', horanac='$horanac' WHERE id_neona= $id_neona";
        $result = $conexion->query($sql2);

        echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> DATO EDITADO CORRECTAMENTE...</p>";
        echo '<script type="text/javascript">window.location ="../cartas_consentimientos/consent_medico.php"</script>';
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