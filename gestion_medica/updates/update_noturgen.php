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


    <title>DATOS NURGEN </title>
    <style type="text/css">
    #contenido{
        display: none;
    }
</style>

</head>
<?php if(isset($_SESSION['hospital'])){ ?>
<body>
<div class="container">
<div class="row">
<div class="col">
    <hr>
<div class="thead" style="background-color: #0c675e; color: white; font-size: 24px;"><strong><center>NOTAS DE URGENCIAS</center> </strong></div>
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
        <div class="row">
<form action="" method="POST">
<br>

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
    <center><strong>SIGNOS VITALES</strong></center>
</div>
<p>
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
     <center><strong>SIGNOS VITALES</strong></center><hr>
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
 <br>
 <hr>
<?php 
$id_ob=$_GET['id_ob'];
$not="SELECT * FROM dat_ob where id_ob=$id_ob";
$result=$conexion->query($not);
while ($row=$result->fetch_assoc()) {
    $quir=$row['dest_cu_ob'];
    $id_usua=$row['id_usua'];
 ?>
 <div class="row">
    <div class=" col-12">
        <strong>MOTIVO DE LA ATENCIÓN:</strong>
     <div class="form-group">
       <!-- <input type="text" name="problemao" class="form-control" value="<?php echo $row['problemao'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"> -->
        <textarea name="problemao" class="form-control" rows="5"><?php echo $row['problemao'] ?></textarea>
  </div>
    </div>
</div>
 <div class="row">
    <div class=" col-12">
           <strong>RESUMEN DEL INTERROGATORIO, EXPLORACIÓN FÍSICA Y ESTADO MENTAL:</strong>
        <!--   <input type="text" name="subjetivob" class="form-control" value="<?php echo $row['subjetivob'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"> -->
           <textarea name="subjetivob" class="form-control" rows="5"><?php echo $row['subjetivob'] ?></textarea>
    </div>
</div>

<div class="row">
    <div class=" col-12">
        <strong>RESULTADOS RELEVANTES DE LOS ESTUDIOS DE LOS SERVICIOS AUXILIARES DE DIAGNÓSTICO Y TRATAMIENTO SOLICITADO PREVIAMENTE:</strong>
         <textarea name="objetivob" class="form-control" rows="5"><?php echo $row['objetivob'] ?></textarea>
    </div>
</div>

<div class="row">
    <div class=" col-12"><strong>DIAGNÓSTICOS O PROBLEMAS CLÍNICOS:</strong>
         <textarea name="analisiso" class="form-control" rows="5"><?php echo $row['analisiso'] ?></textarea>
    </div>
</div>

<div class="row">
<div class=" col-12">
<strong>TRATAMIENTO Y PLAN:</strong>
 <textarea name="trat_noturgen" class="form-control" rows="5"><?php echo $row['trat_noturgen'] ?></textarea>
</div>
</div>

<div class="row">
<div class=" col-12">
<strong>PRONÓSTICO PARA LA VIDA/PARA LA FUNCIÓN:</strong>
 <textarea name="plano" class="form-control" rows="5"><?php echo $row['plano'] ?></textarea>
</div>
</div>

<div class="row">
<div class=" col-12">
<strong>GUIA DE PRÁCTICA CLÍNICA:</strong>
 <textarea name="guia" class="form-control" rows="5"><?php echo $row['guia'] ?></textarea>
</div>
</div>

<div class="row">
    <div class=" col-12"> <strong>OBSERVACIONES Y ANÁLISIS:</strong>
         <textarea name="pxo" class="form-control" rows="5"><?php echo $row['pxo'] ?></textarea>
    </div>
</div>
<hr>
<div class="col">
<div class="form-group">
 <label for="dest_cu_ob"><strong>DESTINO: </strong></label>
    <select name="dest_cu_ob" class="form-control" id="mibuscador" style="width : 50%; heigth : 50%;" onchange="mostrar(this.value);" required="">
        <option value="<?php echo $row['dest_cu_ob'] ?>"><?php echo $row['dest_cu_ob'] ?></option>
        <option value="QUIROFANO">QUIRÓFANO</option>
        <option value="HOSPITALIZACION">HOSPITALIZACIÓN</option>
        <option value="EGRESO DE URGENCIAS">EGRESO DE URGENCIAS</option>
    </select>
    </div>
</div>
<hr><?php } ?>
       <?php
       $id_ob=$_GET['id_ob'];
$not="SELECT * FROM dat_ob where id_ob=$id_ob";
$result=$conexion->query($not);
while ($row=$result->fetch_assoc()) {
    $quir=$row['dest_cu_ob'];
    $id_usua=$row['id_usua'];
       $date=date_create($row['fecha_ob']);
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

<div class="row" id="contenido">
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
     
    <label for="exampleFormControlInput1"><strong>FECHA DE SOLICITUD:</strong></label>
    <input type="date" class="form-control" name="fechay"  style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" value="">
  
    </div>
    <div class="col-sm">
     <div class="form-group">
    <label for="exampleFormControlInput1"><strong>HORA DESEADA:</strong></label>
    <input type="time" class="form-control" name="hora_solicitudy"  style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
    </div>
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlInput1"><strong>INTERVENCIÓN SOLICITADA:</strong></label>
    <input type="text" class="form-control" name="intervencion_soly"  style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
    </div>
  </div>
<div class="form-group">
        <label for="id_cie_10"><strong> DIAGNÓSTICO PREOPERATORIO:</strong></label>
        <input type="text" class="form-control" name="diag_preopy"  style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                                </div>


<div class="form-group">
        <label for="cirugia_prog"><strong>CIRUGÍA PROGRAMADA:</strong></label>
        <input type="text" class="form-control" name="cirugia_progy"  style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
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
  <div class="col"><strong><center>ANESTESIA</center></strong><hr>
  <div class="row">
    <div class="col-sm-4">
    <label for="exampleFormControlInput1">LOCAL</label>
    <input type="text" class="form-control" name="localy" id="exampleFormControlInput1" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"> 
    </div>
    <div class="col-sm-4">
      <label for="exampleFormControlInput1">REGIONAL</label>
    <input type="text" class="form-control" name="regionaly" id="exampleFormControlInput1"  style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"> 
    </div>
    <div class="col-sm-4">
      <label for="exampleFormControlInput1">GENERAL</label>
    <input type="text" class="form-control" name="generaly" id="exampleFormControlInput1"  style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    </div>
  </div>
  <hr>
  </div>
  <div class="col"><br><hr>
<div class="row">
    <div class="col-sm">
      <div class="col-sm">
    <label for="exampleFormControlInput1">Hb:</label>
    <input type="text" class="form-control" name="hby" id="exampleFormControlInput1"  style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    </div>
    </div>
    <div class="col-sm">
      <div class="col-sm">
    <label for="exampleFormControlInput1">HTO:</label>
    <input type="text" class="form-control" name="htoy" id="exampleFormControlInput1"  style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    </div>
    </div>
  </div>
<hr >
  </div>
  <div class="col"><hr>

<div class="row">
    <div class="col-sm"><br>
       <label for="exampleFormControlInput1">PESO:</label>
    <input type="text" class="form-control" name="pesoy" id="exampleFormControlInput1"  placeholder="KG" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    </div>
    <?php
$resultado1 = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE id_atencion=" . $_SESSION ['hospital']) or die($conexion->error);
?>
  <?php
                    while ($f1 = mysqli_fetch_array($resultado1)) {

                        ?>
    <div class="col-sm">
      <label for="exampleFormControlInput1">GRUPO Y RH SANGUÍNEO:</label>
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

  <div class="row">
    <div class="col-sm">
      <label for="exampleFormControlTextarea1"><strong>INSTRUMENTAL NECESARIO</strong></label>
    <textarea class="form-control" name="inst_necesarioy" id="exampleFormControlTextarea1" rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
    </div>
    <div class="col-sm">
      <label for="exampleFormControlTextarea5"><strong>MEDICAMENTOS Y MATERIAL NECESARIO</strong></label>
    <textarea class="form-control" name="medmat_necesarioy" id="exampleFormControlTextarea5" rows="3" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
    </div>
    <div class="col-sm">
      <div class="form-group">
    <label for="exampleFormControlInput1"><strong>NOMBRE DEL JEFE DE SERVICIO</strong></label>
    <input type="text" class="form-control" name="nom_jefe_servy" id="exampleFormControlInput1" placeholder="Nombre" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
    </div>
  </div>

<hr id="contenido">
<strong><center id="contenido">PROGRAMACIÓN EN QUIROFANO</center></strong><br>

<div class="row">
  <div class="col">
    <div class="form-group">
    <label for="exampleFormControlInput1"><strong>FECHA DE PROGRAMACIÓN</strong></label>
    <input type="date" class="form-control" name="fecha_progray" id="exampleFormControlInput1" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
</div>
  <div class="col">
    <div class="form-group">
    <label for="exampleFormControlInput1"><strong>HORA</strong></label>
    <input type="time" class="form-control" name="hora_progray"  id="exampleFormControlInput1" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
  </div>
  <div class="col">
    <div class="form-group">
    <label for="exampleFormControlInput1"><strong>SALA</strong></label>
    <select class="form-control" id="sala" name="salay">
                                        <option value="">Seleccionar opción</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
  </div>
  </div>
</div>

<div class="row" >
  <div class="col-6">
    <div class="form-group">
    <label for="exampleFormControlInput1"><strong>INTERVENCIÓN</strong></label>
    <input type="text" class="form-control" name="intervencion_quiry" id="exampleFormControlInput1" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
</div>
  <div class="col">
    <div class="form-group">
    <label for="exampleFormControlInput1"><strong>INICIO</strong></label>
    <input type="time" class="form-control" name="inicioy"  id="exampleFormControlInput1" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  
  </div>
</div>
  <div class="col">
    <div class="form-group">
    <label for="exampleFormControlInput1"><strong>TÉRMINO</strong></label>
    <input type="time" class="form-control" name="terminoy"  id="exampleFormControlInput1" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
</div>
  
</div>


    <hr>

    </div> 
</div> 

<!--TERMINO DE NOTA MEDICA div container-->
<div class="form-group col-12">

<center>
     <button type="submit" name="guardar" class="btn btn-success">GUARDAR</button>
     <button type="button" class="btn btn-danger" onclick="history.back()">CANCELAR</button></center>
</div>


<br>
<br>
</form>
   <!--TERMINO DE NOTA DE EVOLUCION-->
   <?php 
  if (isset($_POST['guardar'])) {

        $problemao    = mysqli_real_escape_string($conexion, (strip_tags($_POST["problemao"], ENT_QUOTES))); //Escanpando caracteres
        $subjetivob    = mysqli_real_escape_string($conexion, (strip_tags($_POST["subjetivob"], ENT_QUOTES))); //Escanpando caracteres
        $objetivob   = mysqli_real_escape_string($conexion, (strip_tags($_POST["objetivob"], ENT_QUOTES)));
        $analisiso    = mysqli_real_escape_string($conexion, (strip_tags($_POST["analisiso"], ENT_QUOTES))); //Escanpando caracteres
        $trat_noturgen    = mysqli_real_escape_string($conexion, (strip_tags($_POST["trat_noturgen"], ENT_QUOTES)));
        $plano    = mysqli_real_escape_string($conexion, (strip_tags($_POST["plano"], ENT_QUOTES)));
        $guia    = mysqli_real_escape_string($conexion, (strip_tags($_POST["guia"], ENT_QUOTES)));
        $pxo    = mysqli_real_escape_string($conexion, (strip_tags($_POST["pxo"], ENT_QUOTES)));
        $dest_cu_ob    = mysqli_real_escape_string($conexion, (strip_tags($_POST["dest_cu_ob"], ENT_QUOTES)));
        $tipo_intervenciony    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tipo_intervenciony"], ENT_QUOTES)));
        $fechay    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fechay"], ENT_QUOTES)));
        $hora_solicitudy    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hora_solicitudy"], ENT_QUOTES)));
        $intervencion_soly   = mysqli_real_escape_string($conexion, (strip_tags($_POST["intervencion_soly"], ENT_QUOTES)));
        $diag_preopy    = mysqli_real_escape_string($conexion, (strip_tags($_POST["diag_preopy"], ENT_QUOTES)));
        $cirugia_progy    = mysqli_real_escape_string($conexion, (strip_tags($_POST["cirugia_progy"], ENT_QUOTES)));
        $quirofanoy    = mysqli_real_escape_string($conexion, (strip_tags($_POST["quirofanoy"], ENT_QUOTES)));
        $reservay    = mysqli_real_escape_string($conexion, (strip_tags($_POST["reservay"], ENT_QUOTES)));
        $localy    = mysqli_real_escape_string($conexion, (strip_tags($_POST["localy"], ENT_QUOTES)));
        $regionaly    = mysqli_real_escape_string($conexion, (strip_tags($_POST["regionaly"], ENT_QUOTES)));
        $generaly    = mysqli_real_escape_string($conexion, (strip_tags($_POST["generaly"], ENT_QUOTES)));
        $hby    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hby"], ENT_QUOTES)));
        $htoy    = mysqli_real_escape_string($conexion, (strip_tags($_POST["htoy"], ENT_QUOTES)));
        $pesoy    = mysqli_real_escape_string($conexion, (strip_tags($_POST["pesoy"], ENT_QUOTES)));
        $tipo_sangrey    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tipo_sangrey"], ENT_QUOTES)));
        $inst_necesarioy    = mysqli_real_escape_string($conexion, (strip_tags($_POST["inst_necesarioy"], ENT_QUOTES)));
        $medmat_necesarioy    = mysqli_real_escape_string($conexion, (strip_tags($_POST["medmat_necesarioy"], ENT_QUOTES)));
        $nom_jefe_servy    = mysqli_real_escape_string($conexion, (strip_tags($_POST["nom_jefe_servy"], ENT_QUOTES)));
        $fecha_progray    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha_progray"], ENT_QUOTES)));
        $hora_progray    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hora_progray"], ENT_QUOTES)));
        $salay    = mysqli_real_escape_string($conexion, (strip_tags($_POST["salay"], ENT_QUOTES)));
        $intervencion_quiry    = mysqli_real_escape_string($conexion, (strip_tags($_POST["intervencion_quiry"], ENT_QUOTES)));
        $inicioy    = mysqli_real_escape_string($conexion, (strip_tags($_POST["inicioy"], ENT_QUOTES)));
        $terminoy    = mysqli_real_escape_string($conexion, (strip_tags($_POST["terminoy"], ENT_QUOTES)));
        $fecha= mysqli_real_escape_string($conexion,(strip_tags($_POST["fecha"], ENT_QUOTES)));
        $hora= mysqli_real_escape_string($conexion,(strip_tags($_POST["hora"], ENT_QUOTES)));
        $medico= mysqli_real_escape_string($conexion,(strip_tags($_POST["medico"], ENT_QUOTES)));
       
       $merge = $fecha.' '.$hora;

date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

        $sql2 = "UPDATE dat_ob SET id_usua='$medico',fecha_ob='$merge',problemao='$problemao', subjetivob='$subjetivob', objetivob='$objetivob', analisiso='$analisiso', trat_noturgen='$trat_noturgen', plano='$plano' , guia='$guia', pxo='$pxo', dest_cu_ob='$dest_cu_ob' WHERE id_ob= $id_ob";
        $result = $conexion->query($sql2);
$usuario=$_SESSION['login'];
$id_usua=$usuario['id_usua'];

$id_atencion=$_SESSION['hospital'];

    
    if($dest_cu_ob=="QUIROFANO"){
$ingresarsol = mysqli_query($conexion, 'INSERT INTO solicitud_interv_quir (id_atencion,id_usua,tipo_intervenciony,fechay,hora_solicitudy,intervencion_soly,diag_preopy,cirugia_progy,quirofanoy,reservay,localy,regionaly,generaly,hby,htoy,pesoy,tipo_sangrey,inst_necesarioy,medmat_necesarioy,nom_jefe_servy,fecha_progray,hora_progray,salay,intervencion_quiry,inicioy,terminoy) values (' . $id_atencion . ' , ' . $id_usua . ' ," ' . $tipo_intervenciony . '" ," ' . $fechay . '" , "' . $hora_solicitudy . '" , "' . $intervencion_soly . '" , "' . $diag_preopy . '" , "' . $cirugia_progy . ' ","' . $quirofanoy . ' ","' . $reservay . '","' . $localy . '","' . $regionaly . '","' . $generaly . '","' . $hby . '", " ' . $htoy . ' ","' . $pesoy . '","' . $tipo_sangrey . '","' . $inst_necesarioy . '","' . $medmat_necesarioy . '","' . $nom_jefe_servy . '","' . $fecha_progray . '","' . $hora_progray . '","' . $salay . '","' . $intervencion_quiry . '","' . $inicioy . '","' . $terminoy . '") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));
}

        echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> DATO EDITADO CORRECTAMENTE...</p>";
        echo '<script type="text/javascript">window.location ="../cartas_consentimientos/consent_medico.php"</script>';
      }
  ?>

</div>
</div>
<?php }else{
echo '<script type="text/javascript"> window.location.href="../../template/select_pac_hosp.php";</script>';

} ?>
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