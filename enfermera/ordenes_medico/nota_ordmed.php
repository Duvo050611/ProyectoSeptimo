<?php
session_start();

include '../../conexionbd.php';
$conexion = ConexionBD::getInstancia()->getConexion();
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
        <button type="button" class="btn btn-danger" onclick="history.back()">Regresar...</button>
        <hr> 
        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
              <strong><tr><center>REGISTRO DE INDICACIONES MÉDICAS VERBALES</center></strong>
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
        } ?></strong>
      </div>
      <div class="col-sm-3">
        Peso: <strong><?php $sql_vit = "SELECT * from dat_hclinica where Id_exp=$id_exp ORDER by id_hc DESC LIMIT 1";
        $result_vit = $conexion->query($sql_vit);
        while ($row_vit = $result_vit->fetch_assoc()) {
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
          Aseguradora: <strong><?php $sql_aseg = "SELECT aseg from dat_ingreso where id_atencion =$id_atencion ORDER BY fecha DESC limit 1";

          $result_aseg = $conexion->query($sql_aseg);
                                                                                  
          while ($row_aseg = $result_aseg->fetch_assoc()) {
           // $tr = $row_aseg[tip_precio];
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


<form action="insertar_ordenes_med.php" method="POST">
<?php 

$resultado5=$conexion->query("select * from signos_vitales WHERE id_atencion=" . $_SESSION ['pac'].".ORDER by id_sig DESC LIMIT 1") or die($conexion->error);

     while ($f5 = mysqli_fetch_array($resultado5)) {
      $atencion=$f5['id_sig'];
    }
    ?>
    <?php
if (isset($atencion)) {
                        ?>
<?php 

$resultado5=$conexion->query("select * from signos_vitales WHERE id_atencion=" . $_SESSION ['pac'].".ORDER by id_sig DESC LIMIT 1") or die($conexion->error);

     while ($f5 = mysqli_fetch_array($resultado5)) {
     
    
    ?>
    <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 18px;">
  <center><strong>SIGNOS VITALES</strong></center><p>
</div>

<div class="container"> 
  <div class="row">
    
      
    
    <div class="col-sm-2"><center>Presión arterial:</center>
     <div class="row">
         <div class="col">
            <input type="text" class="form-control" value="<?php echo $f5['p_sistol'];?>" disabled>
         </div> /
         <div class="col">
            <input type="text" class="form-control" value="<?php echo $f5['p_diastol'];?>" disabled>
         </div>
        
    </div> mmHG / mmHG
    </div>
    <div class="col-sm-2">
      Frecuencia cardiaca:<input type="text" class="form-control" value="<?php echo $f5['fcard'];?>" disabled> Latidos por minuto
    </div>
    <div class="col-sm-3">
      Frecuencia respiratoria:<input type="text" class="form-control" value="<?php echo $f5['fresp'];?>" disabled> Respiraciones por minuto
    </div>
    <div class="col-sm-2">
     Temperatura:<input type="text" class="form-control" value="<?php echo $f5['temper'];?>" disabled>°C
    </div>
    <div class="col-sm-3">
     Saturación de oxígeno:<input type="text"  class="form-control" value="<?php echo $f5['satoxi'];?>" disabled>%
    </div>
    
  </div>
</div>
<?php }
?>
<?php 
}else{
                        
  ?>
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 18px;">
  <center><strong>SIGNOS VITALES</strong></center><p>
</div>
<div class="container"> 
  <div class="row">
    <div class="col-sm-2"><center>Presión arterial:</center>
        <div class="row">
            <div class="col"><input type="text" class="form-control" name="p_sistol" ></div> /
            <div class="col"><input type="text" class="form-control" name="p_diastol"></div>
        </div>mmHG   /   mmHG
    </div>
    <div class="col-sm-2">
      Frecuencia cardiaca:<input type="text" class="form-control" name="fcard">
      Latidos por minuto
    </div>
    <div class="col-sm-3">
      Frecuencia respiratoria:<input type="text" class="form-control" name="fresp">
      Respiraciones por minuto
    </div>
    <div class="col-sm-2">
     Temperatura:<input type="text" class="form-control"  name="temper">°C
    </div>
    <div class="col-sm-2">
     Saturación oxígeno:<input type="text"  class="form-control" name="satoxi">%
    </div>
  </div>
</div>
    
  

<?php } ?>
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
            
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 18px;">
  <center><strong>PRESCRIPCIÓN Y ÓRDENES: PREPARACIÓN DE CASOS QUIRÚRGICOS, DIETAS, ETC.</strong></center><p>
</div>

      <div class="row">
                <div class="col-sm-3">
                    <strong><label for="exampleFormControlTextarea1"><br><font size="3" color="#2b2d7f">1.- Dieta: <button type="button" class="btn btn-success btn-sm" id="play"><i class="fas fa-play"></button></i></font></label></strong>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <br>

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
<script type="text/javascript">

const dieta = document.getElementById('dieta');
const btnPlayTextdieta = document.getElementById('play');

btnPlayTextdieta.addEventListener('click', () => {
        leerTexto(dieta.value);
});

function leerTexto(dieta){
    const speech = new SpeechSynthesisUtterance();
    speech.text= dieta;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
               <div class=" col-sm-5">
                    <div class="form-group">
                       Detalle de la Dieta: <button type="button" class="btn btn-success btn-sm" id="playdd"><i class="fas fa-play"></button></i>
                       <input type="text" name="det_dieta" class="form-control" id="txtdetdieta">
                    </div>
               </div>
<script type="text/javascript">

const txtdetdieta = document.getElementById('txtdetdieta');
const btnPlayTextdietadet = document.getElementById('playdd');

btnPlayTextdietadet.addEventListener('click', () => {
        leerTexto(txtdetdieta.value);
});

function leerTexto(txtdetdieta){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtdetdieta;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>              
           
        </div>
 

            
          <div class="row">
                <div class="col-3">
                    <strong><label><br><font size="3" color="#2b2d7f">2.- Cuidados generales:<div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="btnStartRecord"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="btnStopRecord"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playcuid"><i class="fas fa-play"></button></i>
</div></font></label></strong>
                </div>
                <div class=" col-9">
                    <br>
                    <div class="form-group">
<textarea class="form-control" id="texto" name="observ_be" rows="3"></textarea>
<script type="text/javascript">
const btnStartRecord = document.getElementById('btnStartRecord');
const btnStopRecord = document.getElementById('btnStopRecord');
const texto = document.getElementById('texto');

const btnPlayTextcuid = document.getElementById('playcuid');
btnPlayTextcuid.addEventListener('click', () => {
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
                </div>

            </div>
            
            <div class="row">
                <div class="col-3">
                  <strong><label for="exampleFormControlTextarea1"><font size="3" color="#2b2d7f">4.- Medicamentos:
<div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="medg"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="toss"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playmed"><i class="fas fa-play"></button></i>
</div>
</font></label></strong>
                </div>
                <div class=" col-9">
<div class="form-group">
<textarea class="form-control" id="txtcae" name="med_med" rows="3"></textarea>
<script type="text/javascript">
const medg = document.getElementById('medg');
const toss = document.getElementById('toss');
const txtcae = document.getElementById('txtcae');

const btnPlayTextmed = document.getElementById('playmed');
btnPlayTextmed.addEventListener('click', () => {
        leerTexto(txtcae.value);
});

function leerTexto(txtcae){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtcae;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let rm = new webkitSpeechRecognition();
      rm.lang = "es-ES";
      rm.continuous = true;
      rm.interimResults = false;

      rm.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtcae.value += frase;
      }

      medg.addEventListener('click', () => {
        rm.start();
      });

      toss.addEventListener('click', () => {
        rm.abort();
      });
</script>
</div>
</div>

            </div>

            <div class="row">
                <div class="col-3">
                    <strong><label for="exampleFormControlTextarea1"><font size="3" color="#2b2d7f">5.- Soluciones:
<div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="slg"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="ucs"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playsoluciones"><i class="fas fa-play"></button></i>
</div>
</font></label></strong>
                </div>
<div class=" col-9">
<div class="form-group">
<textarea class="form-control" id="txtsn" name="soluciones" rows="3"></textarea>
<script type="text/javascript">
  const slg = document.getElementById('slg');
  const ucs = document.getElementById('ucs');
  const txtsn = document.getElementById('txtsn');
  const btnPlayTextsool = document.getElementById('playsoluciones');
  btnPlayTextsool.addEventListener('click', () => {
        leerTexto(txtsn.value);
  });

  function leerTexto(txtsn){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtsn;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
  }

     let rs = new webkitSpeechRecognition();
      rs.lang = "es-ES";
      rs.continuous = true;
      rs.interimResults = false;

      rs.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtsn.value += frase;
      }

      slg.addEventListener('click', () => {
        rs.start();
      });

      ucs.addEventListener('click', () => {
        rs.abort();
      });
  </script>
</div>
</div>
</div>

  <div class="row">
                <div class="col-sm-2">
                    <center><strong><label for="exampleFormControlTextarea1"><br><font size="3" color="#2b2d7f">6.- Estudios Laboratorio: <button type="button" class="btn btn-success btn-sm" id="pla7"><i class="fas fa-play"></button></i></font></label></strong></center>
                </div>
                <div class="col-sm-5">
                    <div class="form-group"><br><br>
                       <p><strong><?php echo $row['perfillab'] ?></strong></p>
                      
                        <select id="l1" name="l1[]" multiple="multiple" class="form-control" required="" >
                            <option value="NINGUNO" selected="NINGUNO" >NINGUNO</option>
                            <?php
                            $sql_serv = "SELECT * FROM cat_servicios c where tipo = 1 and serv_activo = 'SI'";
                            $result_serv = $conexion->query($sql_serv);
                            while ($row_serv = $result_serv->fetch_assoc()) {
                                echo "<option value='" . $row_serv['serv_desc'] . "'>" . $row_serv['serv_desc'] . "</option>";
                            }
                            ?>
                        </select>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $('#l1').multiselect({
                                    nonSelectedText: 'SELECCIONA SERVICIO(S)',
                                    includeSelectAllOption: false,
                                    buttonWidth: 300,
                                    maxHeight: 250,
                                    enableFiltering: true,
                                    dropUp: false,
                                    enableCaseInsensitiveFiltering: true
                                   
                                });
                            });
                        </script>
                    </div>

                 <!--   <div class="form-group">
                        <label for="sol_estudios"><strong>SOLICITUD DE ESTUDIOS LABORATORIO:</strong></label>
                        <select class="form-control" data-live-search="true" name="perfillab">
                            <option value="NINGUNO">NINGUNO</option>
                            <?php
                            $sql_serv = "SELECT DISTINCT nombre FROM paquetes_labo p,cat_servicios c WHERE p.estudio_id=c.id_serv";
                            $result_serv = $conexion->query($sql_serv);
                            while ($row_serv = $result_serv->fetch_assoc()) {
                                echo "<option value='" . $row_serv['nombre'] . "'>" . $row_serv['nombre'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>-->
                </div>
<script type="text/javascript">
const l1 = document.getElementById('l1');
const btnPlayTex7 = document.getElementById('pla7');
btnPlayTex7.addEventListener('click', () => {
        leerTexto(l1.value);
});

function leerTexto(l1){
    const speech = new SpeechSynthesisUtterance();
    speech.text= l1;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
} 
</script>
                 <div class="col-sm-5">Detalle estudios laboratorio<div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="detalleg"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="labos"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="pla8"><i class="fas fa-play"></button></i>
</div><br>
<textarea class="form-control" name="detalle_lab" id="txtl" rows="5"><?php echo $row['detalle_lab']?></textarea>
<script type="text/javascript">
const detalleg = document.getElementById('detalleg');
const labos = document.getElementById('labos');
const txtl = document.getElementById('txtl');

const btnPlayTex8 = document.getElementById('pla8');
btnPlayTex8.addEventListener('click', () => {
        leerTexto(txtl.value);
});

function leerTexto(txtl){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtl;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
} 

     let relav = new webkitSpeechRecognition();
      relav.lang = "es-ES";
      relav.continuous = true;
      relav.interimResults = false;

      relav.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtl.value += frase;
      }

      detalleg.addEventListener('click', () => {
        relav.start();
      });

      labos.addEventListener('click', () => {
        relav.abort();
      });
</script>
 </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <center><strong><label for="exampleFormControlTextarea1"><br><font size="3" color="#2b2d7f">7.- Estudios imagenología: <button type="button" class="btn btn-success btn-sm" id="pla9"><i class="fas fa-play"></button></i></font></label></strong></center>
                </div>
                <div class="col-sm-5">

                    <div class="form-group">
                       <!-- <label for="sol_imagen"><strong>SOLICITUD DE ESTUDIOS IMAGENOLOGIA:</strong></label><br>--><br><p>
                        <p><strong><?php echo $row['sol_estudios']?></strong></p>
                        <select id="a1" name="a1[]" multiple="multiple" class="form-control" required="">
                            <option value="NINGUNO" selected="NINGUNO">NINGUNO</option>
                            <?php
                            $sql_serv = "SELECT * FROM cat_servicios where tipo =2 and serv_activo = 'SI'";
                            $result_serv = $conexion->query($sql_serv);
                            while ($row_serv = $result_serv->fetch_assoc()) {
                                echo "<option value='" . $row_serv['serv_desc'] . "'>" . $row_serv['serv_desc'] . "</option>";
                            }
                            ?>
                        </select>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $('#a1').multiselect({
                                    nonSelectedText: 'Selecciona servicio(s)',
                                    includeSelectAllOption: false,
                                    buttonWidth: 300,
                                    maxHeight: 250,
                                    enableFiltering: true,
                                    dropUp: false,
                                    enableCaseInsensitiveFiltering: true
                                });
                            });
                        </script>
                    </div>
                </div>
                                <div class="col-sm-5"><br><br>Detalle estudios imagenología:
          <input type="text" class="form-control" name="det_imagen" value="<?php echo $row['det_imagen']?>">
        </div>
            </div>
<script type="text/javascript">
const a1 = document.getElementById('a1');

const btnPlayTex9 = document.getElementById('pla9');
btnPlayTex9.addEventListener('click', () => {
        leerTexto(a1.value);
});

function leerTexto(a1){
    const speech = new SpeechSynthesisUtterance();
    speech.text= a1;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
} 

</script>
     <div class="row">
                <div class="col-sm-2">
                    <center><strong><label for="exampleFormControlTextarea1"><br><font size="3" color="#2b2d7f">8.- Estudios patología: <button type="button" class="btn btn-success btn-sm" id="pla10"><i class="fas fa-play"></button></i> </font></label></strong></center>
                </div>
                <div class="col-sm-5">

                    <div class="form-group">
                        <br>
                       <p><strong><?php echo $row['sol_pato']?></strong></p>
                       <select id="p1" name="p1[]" multiple="multiple" class="form-control" required="">
                            <option value="NINGUNO" selected="NINGUNO">NINGUNO</option>
                            <?php
                            $sql_serv = "SELECT * FROM cat_servicios where tipo =6 and serv_activo = 'SI'";
                            $result_serv = $conexion->query($sql_serv);
                            while ($row_serv = $result_serv->fetch_assoc()) {
                                echo "<option value='" . $row_serv['serv_desc'] . "'>" . $row_serv['serv_desc'] . "</option>";
                            }
                            ?>
                        </select>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $('#p1').multiselect({
                                    nonSelectedText: 'Selecciona servicio(s)',
                                    includeSelectAllOption: false,
                                    buttonWidth: 300,
                                    maxHeight: 200,
                                    enableFiltering: true,
                                    dropUp: false,
                                    enableCaseInsensitiveFiltering: true
                                });
                            });
                        </script>
                    </div>
                </div>
<script type="text/javascript">
const p1 = document.getElementById('p1');

const btnPlayTex10 = document.getElementById('pla10');
btnPlayTex10.addEventListener('click', () => {
        leerTexto(p1.value);
});

function leerTexto(p1){
    const speech = new SpeechSynthesisUtterance();
    speech.text= p1;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
} 

</script>
        <div class="col-sm-5"><br>Detalle estudios patología: <button type="button" class="btn btn-success btn-sm" id="pla11"><i class="fas fa-play"></button></i><br>
          <input type="text" class="form-control" name="det_pato" id="tt1" value="<?php echo $row['det_pato']?>">
        </div>
    </div>
<script type="text/javascript">
const tt1 = document.getElementById('tt1');

const btnPlayTex11 = document.getElementById('pla11');
btnPlayTex11.addEventListener('click', () => {
        leerTexto(tt1.value);
});

function leerTexto(tt1){
    const speech = new SpeechSynthesisUtterance();
    speech.text= tt1;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
} 

</script>


            <div class="row">
                <div class="col-sm-2">
                    <center><strong><label for="exampleFormControlTextarea1"><br><font size="3" color="#2b2d7f">9.- Solicitud transfusión sanguínea: <button type="button" class="btn btn-success btn-sm" id="pla12"><i class="fas fa-play"></button></i></font></label></strong></center>
                </div>
                <div class="col-sm-5">

                    <div class="form-group">
                        <!--<label for="sol_imagen"><strong> SOLICITUD DE TRANSFUSIÓN SANGUINEA:</strong></label>-->
                        <br><br>
                        <p><strong><?php echo $row['solicitud_sang'] ?></strong></p>
                        <select id="s1" name="s1[]" multiple="multiple" class="form-control" required="">
                            <option value="NINGUNO" selected="NINGUNO">NINGUNO</option>
                            <?php
                            $sql_serv = "SELECT * FROM cat_servicios where tipo =5 and serv_activo = 'SI'";
                            $result_serv = $conexion->query($sql_serv);
                            while ($row_serv = $result_serv->fetch_assoc()) {
                                echo "<option value='" . $row_serv['serv_desc'] . "'>" . $row_serv['serv_desc'] . "</option>";
                            }
                            ?>
                        </select>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $('#s1').multiselect({
                                    nonSelectedText: 'Selecciona servicio(s)',
                                    includeSelectAllOption: false,
                                    buttonWidth: 300,
                                    maxHeight: 200,
                                    enableFiltering: true,
                                    dropUp: false,
                                    enableCaseInsensitiveFiltering: true
                                });
                            });
                        </script>
                    </div>
                </div>
<script type="text/javascript">
const s1 = document.getElementById('s1');

const btnPlayTex12 = document.getElementById('pla12');
btnPlayTex12.addEventListener('click', () => {
        leerTexto(s1.value);
});

function leerTexto(s1){
    const speech = new SpeechSynthesisUtterance();
    speech.text= s1;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
} 

</script>
 <div class="col-sm-5">Detalle de transfusión sanguínea<div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="sang"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="stopsa"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="pla13"><i class="fas fa-play"></button></i>
</div><br>
<textarea class="form-control" id="txtstr" name="det_sang" rows="5"><?php echo $row['det_sang']?></textarea>
<script type="text/javascript">
const sang = document.getElementById('sang');
const stopsa = document.getElementById('stopsa');
const txtstr = document.getElementById('txtstr');

const btnPlayTex13 = document.getElementById('pla13');
btnPlayTex13.addEventListener('click', () => {
        leerTexto(txtstr.value);
});

function leerTexto(txtstr){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtstr;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
} 
     let reddts = new webkitSpeechRecognition();
      reddts.lang = "es-ES";
      reddts.continuous = true;
      reddts.interimResults = false;

      reddts.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtstr.value += frase;
      }

      sang.addEventListener('click', () => {
        reddts.start();
      });

      stopsa.addEventListener('click', () => {
        reddts.abort();
      });
</script>
 </div>


              
            </div>


            <?php
            $usuario = $_SESSION['login'];
            ?>
            <hr>
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 18px;">
  <center><strong>PROCEDIMIENTOS EN MEDICINA DE TRATAMIENTO</strong></center><p>
</div>

<div class="row">
                <div class="col-3">
                   <strong><label for="exampleFormControlTextarea1"><font size="3" color="#2b2d7f">Diálisis:
</font></label></strong>
                </div>
                <div class=" col-9">
                    <div class="form-group">
<textarea class="form-control" id="txti" name="dialisis" rows="3"></textarea>
                    </div>
                </div>
            </div>

<div class="row">
                <div class="col-3">
                   <strong><label for="exampleFormControlTextarea1"><font size="3" color="#2b2d7f">Fisioterapía:
</font></label></strong>
                </div>
                <div class=" col-9">
                    <div class="form-group">
<textarea class="form-control" id="txti" name="fisio" rows="3"></textarea>
                    </div>
                </div>
            </div>
 <div class="row">
                <div class="col-3">
                   <strong><label for="exampleFormControlTextarea1"><font size="3" color="#2b2d7f">Inhaloterapia:
<div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="inhg"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="iast"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playinha"><i class="fas fa-play"></button></i>
</div>
</font></label></strong>
                </div>
                <div class=" col-9">
                    <div class="form-group">
<textarea class="form-control" id="txti" name="cuid_gen" rows="3"></textarea>
<script type="text/javascript">
const inhg = document.getElementById('inhg');
const iast = document.getElementById('iast');
const txti = document.getElementById('txti');

const btnPlayTextin = document.getElementById('playinha');
btnPlayTextin.addEventListener('click', () => {
        leerTexto(txti.value);
});

function leerTexto(txti){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txti;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let rh = new webkitSpeechRecognition();
      rh.lang = "es-ES";
      rh.continuous = true;
      rh.interimResults = false;

      rh.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txti.value += frase;
      }

      inhg.addEventListener('click', () => {
        rh.start();
      });

      iast.addEventListener('click', () => {
        rh.abort();
      });
</script>
                    </div>
                </div>
            </div>
<div class="row">
                <div class="col-3">
                   <strong><label for="exampleFormControlTextarea1"><font size="3" color="#2b2d7f">Rehabilitación:
</font></label></strong>
                </div>
                <div class=" col-9">
                    <div class="form-group">
<textarea class="form-control" id="txti" name="reha" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col align-self-start">
                    <strong> Nombre del Médico que ordena: <button type="button" class="btn btn-success btn-sm" id="playnommed"><i class="fas fa-play"></button></i></strong> <input type="text"  name="med" id="txtmm" class="form-control" value="" required>
                </div>
<script type="text/javascript">
const txtmm = document.getElementById('txtmm');
const btnPlayTextmm = document.getElementById('playnommed');
btnPlayTextmm.addEventListener('click', () => {
        leerTexto(txtmm.value);
});

function leerTexto(txtmm){
    const ss = new SpeechSynthesisUtterance();
    ss.text= txtmm;
    ss.volume=1;
    ss.rate=1;
    ss.pitch=0;
    window.speechSynthesis.speak(ss);
}

</script>
                <div class="col align-self-start">
                    <strong> Nombre de la Enfermera Testigo: <button type="button" class="btn btn-success btn-sm" id="playenf"><i class="fas fa-play"></button></i></strong> <input type="text" id="txtenff" name="enf" class="form-control" value="" required>
                </div>
<script type="text/javascript">
const txtenff = document.getElementById('txtenff');
const btnPlayTextenff = document.getElementById('playenf');
btnPlayTextenff.addEventListener('click', () => {
        leerTexto(txtenff.value);
});

function leerTexto(txtenff){
    const ss = new SpeechSynthesisUtterance();
    ss.text= txtenff;
    ss.volume=1;
    ss.rate=1;
    ss.pitch=0;
    window.speechSynthesis.speak(ss);
}

</script>
            </div>
            <hr>
            <div class="container">
                <div class="row">
                    <div class="col align-self-start">
                    </div>
                    <button type="submit" class="btn btn-primary">Firmar</button> &nbsp;
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