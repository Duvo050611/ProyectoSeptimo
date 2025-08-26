<?php
session_start();


include "../../conexionbd.php";
include("../header_enfermera.php");


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

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css"
          type="text/css"/>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js"></script>


    <title>Ordenes verbales</title>
    <style type="text/css">
/*#play{
  padding: 4px;
    padding-top: 0.1px;
    padding-bottom: 0.1px;
    }*/
</style>
</head>
<body>


  <section class="content container-fluid">

      <?php

    include "../../conexionbd.php";

    if (isset($_SESSION['pac'])) {
      $id_atencion = $_SESSION['pac'];

      $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.tel, p.fecnac,p.tip_san, di.fecha, di.area, di.alta_med, p.sexo, di.alergias, p.folio FROM paciente p, dat_ingreso di WHERE p.Id_exp=di.Id_exp and di.id_atencion =$id_atencion";

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
        $folio = $row_pac['folio'];
      }
$sql_pac = "SELECT * FROM  dat_ingreso WHERE id_atencion =$id_atencion";

          $result_pac = $conexion->query($sql_pac);

          while ($row_pac = $result_pac->fetch_assoc()) {
            $fingreso = $row_pac['fecha'];
             $fegreso = $row_pac['fec_egreso'];
             $alta_med = $row_pac['alta_med'];
             $alta_adm = $row_pac['alta_adm'];
             $activo = $row_pac['activo'];
             $valida = $row_pac['valida'];
          }

if($alta_med=='SI' && $alta_adm=='SI' && $activo=='NO' && $valida=='SI'){
    
    $sql_est = "SELECT DATEDIFF('$fegreso', '$fingreso') as estancia FROM dat_ingreso where id_atencion = $id_atencion";

      $result_est = $conexion->query($sql_est);

      while ($row_est = $result_est->fetch_assoc()) {
           $estancia = $row_est['estancia'];
       
      }
}else{
    
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
}
     


function bisiesto($anio_actual){
    $bisiesto=false;
    //probamos si el mes de febrero del año actual tiene 29 días
      if (checkdate(2,29,$anio_actual))
      {
        $bisiesto=true;
    }
    return $bisiesto;
}

//date_default_timezone_set('America/Guatemala');
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

    ?>
<div class="container ">
        <button type="button" class="btn btn-danger btn-sm" onclick="history.back()">Regresar...</button>
     <P>
        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
              <strong><tr><center>REGISTRO DE CUIDADOS QUIRÚRGICOS</center></strong>
        </div>

 <font size="2">    

 <div class="container">
  <div class="row">
    <div class="col-sm-6">
     Expediente: <strong><?php echo $folio ?></strong>
       Paciente: <strong><?php echo $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac ?></strong>
    </div>
    <div class="col-sm">
      Área: <strong><?php echo $area ?></strong>
    </div>
   <?php $date = date_create($pac_fecing);
   ?>
      <div class="col-sm">
      Fecha de Ingreso: <strong><?php echo date_format($date, "d/m/Y") ?></strong>
    </div>
  </div>
</div>
     <div class="container">
  <div class="row">
    <div class="col-sm">
       <?php $date1 = date_create($pac_fecnac);
   ?>
      Fecha de nacimiento: <strong><?php echo date_format($date1, "d/m/Y") ?></strong>
    </div>
    <div class="col-sm">
      Tipo de sangre: <strong><?php echo $pac_tip_sang ?></strong>
    </div>
   
      <div class="col-sm">
      Habitación: <strong><?php $sql_hab = "SELECT num_cama from cat_camas where id_atencion =$id_atencion";
$result_hab = $conexion->query($sql_hab);                                                                                    while ($row_hab = $result_hab->fetch_assoc()) {
  echo $row_hab['num_cama'];
} ?></strong>
    </div>
    <div class="col-sm">
      Tiempo estancia: <strong><?php echo $estancia ?> Dias</strong>
    </div>
  </div>
</div>

  <div class="container">
  <div class="row">
   <div class="col-sm-3">
      Edad: <strong><?php if($anos > "0" ){
   echo $anos." Años";
}elseif($anos <="0" && $meses>"0"){
    echo $meses." Meses";
}elseif($anos <="0" && $meses<="0" && $dias>"0"){
    echo $dias." Días";
}
?></strong>
  </div>
  <div class="col-sm-3">
      Peso: <strong><?php $sql_vit = "SELECT * from dat_hclinica where Id_exp=$id_exp ORDER by id_hc DESC LIMIT 1";
      
      $result_vit = $conexion->query($sql_vit);                                                                                    while ($row_vit = $result_vit->fetch_assoc()) {
      $peso=$row_vit['peso'];

      } if (!isset($peso)){
        $peso=0;
   
      }   
      echo $peso;?></strong>
  </div>
  
  <div class="col-sm">
      Talla: <strong><?php $sql_vitt =" SELECT * from dat_hclinica where Id_exp=$id_exp ORDER by id_hc DESC LIMIT 1";
      $result_vitt = $conexion->query($sql_vitt); 
      while ($row_vitt = $result_vitt->fetch_assoc()) 
      {
        $talla=$row_vitt['talla'];
      }
      if(!isset($talla)){
        $talla=0;
      } echo $talla;?></strong>
 
  </div> 
  <div class="col-sm">
      Género: <strong><?php echo $pac_sexo ?></strong>
  </div>
  </div>
</div>

  <div class="container">
    <div class="row">
   
      <div class="col-sm-3">
          Alergias: <strong><?php echo $alergias ?></strong>
      </div>
      
      <div class="col-sm-6">
        Estado de Salud: <strong><?php $sql_edo = "SELECT edo_salud from dat_ingreso where id_atencion=$id_atencion ORDER by edo_salud ASC LIMIT 1";
        $result_edo = $conexion->query($sql_edo);
        while ($row_edo = $result_edo->fetch_assoc()) {
          echo $row_edo['edo_salud'];
        } ?></strong>
      </div>
      
      <div class="col-sm">
          <label class="control-label">Aseguradora: </label><strong>  &nbsp; 
            <?php   $sql_aseg = "SELECT aseg from dat_ingreso where id_atencion =$id_atencion";
                    $result_aseg = $conexion->query($sql_aseg);
                    while ($row_aseg = $result_aseg->fetch_assoc()) {
                          echo $row_aseg['aseg'];
                          $at=$row_aseg['aseg'];
                    }
                    
                    $resultadot = $conexion ->query("SELECT tip_precio FROM cat_aseg WHERE aseg='$at'")or die($conexion->error);
                    while($filat = mysqli_fetch_array($resultadot)){ 
                        $tr=$filat["tip_precio"];
                    echo ' '.$tr;
                    } ?></strong>
      </div>
    </div>
  </div>


        <div class="col-sm-4">
     <?php 
$d="";
      $sql_motd = "SELECT diagprob_i from dat_nevol where id_atencion=$id_atencion ORDER by diagprob_i ASC LIMIT 1";
    $result_motd = $conexion->query($sql_motd);
    while ($row_motd = $result_motd->fetch_assoc()) {
        $d=$row_motd['diagprob_i'];
    } ?>
    <?php $sql_mot = "SELECT motivo_atn from dat_ingreso where id_atencion=$id_atencion ORDER by motivo_atn ASC LIMIT 1";
    $result_mot = $conexion->query($sql_mot);
    while ($row_mot = $result_mot->fetch_assoc()) {
    $m=$row_mot['motivo_atn'];
    } ?>

    <?php if ($d!=null) {
       echo '<td> Diagnóstico: <strong>' . $d .'</strong></td>';
    } else{
          echo '<td"> Motivo de atención: <strong>' . $m .'</strong></td>';
    }?>
    </div>
</font>
<div class="container">
  
</div>

        <?php
      } else {
        echo '<script type="text/javascript"> window.location.href="../../template/select_pac_enf.php";</script>';
      }
        ?>
        </div>
<body>
<div class="container">
  <br>


<form action="" method="POST">


<hr>
<!--<?php
                            //date_default_timezone_set('America/Guatemala');
                            //$fecha_actual2 = date("d-m-Y H:i:s");
                            ?>

            <div class="row">
                <div class="col-sm-3">
                    FECHA Y HORA: <input class="form-control" disabled value="<?php// $fecha_actual2 ?>*/" >
                </div>
                <div class="col-sm-3">
                    HORA: <input type="time" name="hora_ord" class="form-control">
                </div>
            </div>-->
            <div class="container-fluid">
<div class="container">
  <div class="row">
    <div>
    <a class="btn btn-outline-primary btn-sm" href="../registro_quirurgico/vista_enf_quirurgico.php">NOTA</a>
    </div>
    <div>&nbsp;
    <a class="btn btn-outline-primary btn-sm" href="../registro_quirurgico/nav_signos.php">SIGNOS VITALES</a>
    </div>
    <div>
    &nbsp;
    <a class="btn btn-outline-primary  btn-sm" href="../registro_quirurgico/nav_textiles.php">CONTROL DE TEXTILES</a>
    </div>
    <div>
        &nbsp;
    <a class="btn btn-outline-primary   btn-sm" href="../registro_quirurgico/nav_cate.php">CATÉTERES</a>
    </div>
    <div>
        &nbsp;
      <a href="../registro_quirurgico/nav_med.php" class="btn btn-outline-primary  btn-sm">MEDICAMENTOS/EQUIPOS</a>
    </div>

     <div>
        &nbsp;
      <a href="../registro_quirurgico/nav_rec.php" class="btn btn-outline-primary  btn-sm">NOTA DE RECUPERACIÓN</a>
    </div><p>
    <div>
         &nbsp;
      <a href="../ordenes_medico/ordenes_quir.php" class="btn btn-outline-primary  btn-sm active">CUIDADOS </a>
    </div>
    <div>
         &nbsp;
      <a href="../registro_quirurgico/nav_med_rojo.php" class="btn btn-outline-danger  btn-sm">CARRO ROJO </a>
    </div>
  </div>
</div>
</div><hr>
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 18px;">
  <center><strong>PRESCRIPCIÓN Y ÓRDENES: PREPARACIÓN DE CASOS QUIRÚRGICOS, DIETAS, ETC.</strong></center><p>
</div>

   
 
<div class="col-sm-4">
                    <div class="form-group">
                        <br>
<font color="#2b2d7f"><strong>Dieta</strong> </font>
                        <select class="form-control" name="dieta" required id="dieta">
                                 <option value="">Seleccionar dieta</option>
                            
                            <?php
                            $sql_d = "SELECT DISTINCT id_dieta,dieta FROM cat_dietas WHERE dieta_activo='SI' order by dieta ASC";
                            $result_d = $conexion->query($sql_d);
                            while ($row_d = $result_d->fetch_assoc()) {
                                echo "<option value='" . $row_d['dieta'] . "'>" . $row_d['dieta'] . "</option>";
                            }
                            ?>
                    </select>
                 </div>
               </div>
<div class="container">
                <div class="row">
                    
                    <div class="col-sm-2">
                        <strong><font color="#2b2d7f">Drenaje<div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="btnStartRecord"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="btnStopRecord"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="pla4"><i class="fas fa-play"></button></i>
</div></font></strong>
                    </div>
                  
                      <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gca" value="SI">
                            <label class="form-check-label" for="son">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gca" value="NO" checked>
                            <label class="form-check-label" for="son">
                                NO
                            </label>
                        </div>
                    </div>
              
                    <div class="col-sm-7">
     <input type="text" name="gcat" class="form-control" id="texto">
    </div>
    <script type="text/javascript">
const btnStartRecord = document.getElementById('btnStartRecord');
const btnStopRecord = document.getElementById('btnStopRecord');
const texto = document.getElementById('texto');

const btnPlayTex4 = document.getElementById('pla4');
btnPlayTex4.addEventListener('click', () => {
        leerTexto(texto.value);
});

function leerTexto(texto){
    const speech = new SpeechSynthesisUtterance();
    speech.text= texto;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
} 

     let recognition = new webkitSpeechRecognition();
      recognition.lang = "es-ES";
      recognition.continuous = true;
      recognition.interimResults = false;

      recognition.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        texto.value += frase;
      }

      btnStartRecord.addEventListener('click', () => {
        recognition.start();
      });

      btnStopRecord.addEventListener('click', () => {
        recognition.abort();
      });
</script>
                </div>
                <p>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-2">
                        <strong><font color="#2b2d7f">Sonda<div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="medg"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="toss"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="pla5"><i class="fas fa-play"></button></i>
</div></font></strong>
                    </div>
                  
                      <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="son" value="SI">
                            <label class="form-check-label" for="son">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="son" value="NO" checked>
                            <label class="form-check-label" for="son">
                                NO
                            </label>
                        </div>
                    </div>
              
                    <div class="col-sm-7">
     <input type="text" name="sont" class="form-control" id="txt">
    </div>
    <script type="text/javascript">
const medg = document.getElementById('medg');
const toss = document.getElementById('toss');
const txt = document.getElementById('txt');

const btnPlayTex5 = document.getElementById('pla5');
btnPlayTex5.addEventListener('click', () => {
        leerTexto(txt.value);
});

function leerTexto(txt){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txt;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
} 


     let rem = new webkitSpeechRecognition();
      rem.lang = "es-ES";
      rem.continuous = true;
      rem.interimResults = false;

      rem.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txt.value += frase;
      }

      medg.addEventListener('click', () => {
        rem.start();
      });

      toss.addEventListener('click', () => {
        rem.abort();
      });
</script>
                </div>
            </div>
     <P>
<div class="container">
                <div class="row">
                    <div class="col-sm-3">
                        <strong><label for="exampleFormControlTextarea1"><font size="3" color="#2b2d7f">
                                    Observaciones(Otros): <button type="button" class="btn btn-success btn-sm" id="plobo"><i class="fas fa-play"></button></i></font></label></strong>
                    </div>
             
                    <div class="col-sm-9">
      <input type="text" name="observ_be" class="form-control"  id="txtobotr">
    </div>
                </div>
            </div>
              <script type="text/javascript">

const txtobotr = document.getElementById('txtobotr');
const btnPlayTextm = document.getElementById('plobo');

btnPlayTextm.addEventListener('click', () => {
        leerTexto(txtobotr.value);
});

function leerTexto(txtobotr){
    const sd = new SpeechSynthesisUtterance();
    sd.text= txtobotr;
    sd.volume=1;
    sd.rate=1;
    sd.pitch=0;
    window.speechSynthesis.speak(sd);
}
</script>

  
            
            <hr>
            <div class="container">
                <div class="row">
                    <div class="col align-self-start">
                    </div>
                    <button type="submit" class="btn btn-primary" name="guar">Firmar</button> &nbsp;
                    <button type="button" class="btn btn-danger" onclick="history.back()">Cancelar</button>
                    <div class="col align-self-end">
                    </div>
                </div>
            </div>


            <br>
            <br>
        </form>


        <!--TERMINO DE NOTA DE EVOLUCION-->


    </div>

</div>
<?php 
if (isset($_POST['guar'])) {

$observ_be = mysqli_real_escape_string($conexion, (strip_tags($_POST['observ_be'], ENT_QUOTES)));
$gca = mysqli_real_escape_string($conexion, (strip_tags($_POST['gca'], ENT_QUOTES)));
$gcat = mysqli_real_escape_string($conexion, (strip_tags($_POST['gcat'], ENT_QUOTES)));
$son = mysqli_real_escape_string($conexion, (strip_tags($_POST['son'], ENT_QUOTES)));
$sont = mysqli_real_escape_string($conexion, (strip_tags($_POST['sont'], ENT_QUOTES)));
$dieta = mysqli_real_escape_string($conexion, (strip_tags($_POST['dieta'], ENT_QUOTES)));
   $usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];
$id_atencion = $_SESSION['pac'];

//date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

$resultado=$conexion->query("SELECT * from reg_usuarios WHERE id_usua= $id_usua") or die($conexion->error);
    while ($r = mysqli_fetch_array($resultado)) {
 $nombre=$r['nombre'];
 $papell=$r['papell'];
 $sapell=$r['sapell'];
}

$nombre_medico=$nombre.' '.$papell.' '.$sapell;
//date_default_timezone_set('America/Guatemala');
$hora_ord = date("H:i:s");
$insertar = mysqli_query($conexion, 'INSERT INTO dat_ordenes_med(id_atencion,id_usua,fecha_ord,hora_ord,dieta,observ_be,medico,tipo,gca,gcat,son,sont) values (' . $id_atencion . ',' . $id_usua . ',"' . $fecha_actual . '","' . $hora_ord . '","'.$dieta.'","' . $observ_be . '","'.$nombre_medico.'","QUIRURGICO","'.$gca.'","'.$gcat.'","'.$son.'","'.$sont.'")') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

        echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> DATO INSERTADO CORRECTAMENTE...</p>";
        echo '<script type="text/javascript">window.location ="../lista_pacientes/vista_pac_enf.php"</script>';
      }

 ?>
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