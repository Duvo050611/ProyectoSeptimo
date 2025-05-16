<?php
session_start();
include "../../conexionbd.php";
include("../header_medico.php");
?>

<!DOCTYPE html>
<div>

    <head>
        <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″ />
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
            integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
            integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
        </script>
        <!-- menu derecho de perfil -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
        </script>
        <!--  Bootstrap  -->

        <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
            integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

        <!-- despliegue menu -->
        <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

        <script>
        // Write on keyup event of keyword input element
        $(document).ready(function() {
            $("#search").keyup(function() {
                _this = this;
                // Show only matching TR, hide rest of them
                $.each($("#mytable tbody tr"), function() {
                    if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -
                        1)
                        $(this).hide();
                    else
                        $(this).show();
                });
            });
        });
        </script>
        <title>Historia Clinica </title>
        <style type="text/css">
        .modal-lg {
            max-width: 70% !important;
        }
        </style>
    </head>

    <div class="container">
        <div class="row">
            <div class="col">

                <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 22px;"><strong>
                        <center>HISTORIA CLÍNICA </center>
                    </strong></div>

                <?php
    include "../../conexionbd.php";
    if (isset($_SESSION['hospital'])) {
      $id_atencion = $_SESSION['hospital'];

      $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.folio, p.tel, p.fecnac,p.tip_san, di.fecha, di.area, di.alta_med, di.activo, p.sexo, di.alergias, p.ocup FROM paciente p, dat_ingreso di WHERE p.Id_exp=di.Id_exp and di.id_atencion =$id_atencion";

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
        $folio = $row_pac['folio'];
        $alergias = $row_pac['alergias'];
        $ocup = $row_pac['ocup'];
        $activo = $row_pac['activo'];
      }

      if ($activo === 'SI') {
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
      else {
          $sql_est = "SELECT DATEDIFF(fec_egreso, fecha) as estancia FROM dat_ingreso WHERE id_atencion = $id_atencion";
          $result_est = $conexion->query($sql_est);
          while ($row_est = $result_est->fetch_assoc()) {
            if($row_est['estancia']==0){
               $estancia = $row_est['estancia']+1;
            }else{
              $estancia = $row_est['estancia'];
            }
          }
      }
    ?>


                <div class="row">
                    <div class="col-sm-2">
                        Expediente: <strong><?php echo $folio?> </strong>
                    </div>
                    <div class="col-sm-6">
                        Paciente: <strong><?php echo $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac ?></strong>
                    </div>

                    <?php $date = date_create($pac_fecing);
   ?>
                    <div class="col-sm-4">
                        Fecha de ingreso: <strong><?php echo date_format($date, "d/m/Y H:i:s") ?></strong>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <?php $date1 = date_create($pac_fecnac);
   ?>
                        <!-- INICIO DE FUNCION DE CALCULAR EDAD -->
                        <?php 

function bisiesto($anio_actual){
    $bisiesto=false;
    //probamos si el mes de febrero del año actual tiene 29 días
      if (checkdate(2,29,$anio_actual))
      {
        $bisiesto=true;
    }
    return $bisiesto;
}


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
                        <!-- TERMINO DE FUNCION DE CALCULAR EDAD -->
                        Fecha de nacimiento: <strong><?php echo date_format($date1, "d/m/Y") ?></strong>
                    </div>
                    <div class="col-sm-4">
                        Edad: <strong><?php if($anos > "0" ){
   echo $anos." años";
}elseif($anos <="0" && $meses>"0"){
    echo $meses." meses";
}elseif($anos <="0" && $meses<="0" && $dias>"0"){
    echo $dias." días";
}
?></strong>
                    </div>

                    <div class="col-sm-2">
                        Habitación: <strong><?php $sql_hab = "SELECT num_cama from cat_camas where id_atencion =$id_atencion";
$result_hab = $conexion->query($sql_hab);                                                                                    while ($row_hab = $result_hab->fetch_assoc()) {
  echo $row_hab['num_cama'];
} ?></strong>
                    </div>
                </div>
                <div class="row">
                    <?php 
$d="";
$sql_motd = "SELECT diagprob_i from dat_not_ingreso where id_atencion=$id_atencion ORDER by id_not_ingreso DESC LIMIT 1";
    $result_motd = $conexion->query($sql_motd);
    while ($row_motd = $result_motd->fetch_assoc()) {
        $d=$row_motd['diagprob_i'];
    } 
$sql_motd = "SELECT diagprob_i from dat_nevol where id_atencion=$id_atencion ORDER by id_ne DESC LIMIT 1";
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
   echo '<div class="col-sm-8"> Diagnóstico: <strong>' . $d .'</strong></div>';
} else{
      echo '<div class="col-sm-8"> Motivo de atención: <strong>' . $m .'</strong></div>';
}?>
                    <div class="col-sm">
                        Días estancia: <strong><?php echo $estancia ?> días</strong>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-4">
                        Alergias: <strong><?php echo $alergias ?></strong>
                    </div>
                    <div class="col-sm-4">
                        Estado de salud: <strong><?php $sql_edo = "SELECT edo_salud from dat_ingreso where id_atencion=$id_atencion ORDER by edo_salud ASC LIMIT 1";
      $result_edo = $conexion->query($sql_edo);while ($row_edo = $result_edo->fetch_assoc()) {
      echo $row_edo['edo_salud'];
} ?></strong>
                    </div>
                    <div class="col-sm-3">
                        Tipo de sangre: <strong><?php echo $pac_tip_sang ?></strong>
                    </div>
                </div>
                <?php $sql_edo = "SELECT * from dat_hclinica where Id_exp=$id_exp ORDER by id_hc DESC LIMIT 1";
$result_edo = $conexion->query($sql_edo);
while ($row_edo = $result_edo->fetch_assoc()) {
  $peso=$row_edo['peso'];
  $talla=$row_edo['talla'];
} 
if (!isset($peso)){
    $peso=0;
    $talla=0;
}?>

                <div class="row">
                    <div class="col-sm-4">
                        Peso: <strong><?php echo $peso ?></strong>
                    </div>
                    <div class="col-sm-3">
                        Talla: <strong><?php echo $talla ?></strong>
                    </div>
                </div>


            </div>
            <?php
      } else {
        echo '<script type="text/javascript"> window.location.href="../lista_pacientes/lista_pacientes.php";</script>';
      }
        ?>
        </div>
    </div>
    <form action="insertar_hclinica.php" method="POST" onsubmit="return checkSubmit();">

        <div class="container">
            <hr>
            <div class="row">
                <div class="col-sm-3">
                    <strong><label for="tip_hc"><button type="button" class="btn btn-success btn-sm" id="play"><i
                                    class="fas fa-play"></button></i> Tipo de interrogatorio:</label></strong>
                    <select class="form-control" id="tip_hc" name="tip_hc" required="">
                        <option value="">Seleccionar</option>
                        <option value="Directo">Directo</option>
                        <option value="Indirecto">Indirecto</option>
                    </select>
                </div>
                <script type="text/javascript">
                const tip_hc = document.getElementById('tip_hc');
                const btn1 = document.getElementById('play');

                btn1.addEventListener('click', () => {
                    leerTexto(tip_hc.value);
                });

                function leerTexto(tip_hc) {
                    const speech = new SpeechSynthesisUtterance();
                    speech.text = tip_hc;
                    speech.volume = 1;
                    speech.rate = 1;
                    speech.pitch = 0;
                    window.speechSynthesis.speak(speech);
                }
                </script>

                <div class="col-sm-4">
                    <strong><label for="ocupa"><button type="button" class="btn btn-success btn-sm" id="play1"><i
                                    class="fas fa-play"></button></i> Ocupación:</label></strong>
                    <input type="text" name="ocupa" class="form-control" id="ocupa" required="">
                </div>
                <script type="text/javascript">
                const ocupa = document.getElementById('ocupa');
                const btn2 = document.getElementById('play1');

                btn2.addEventListener('click', () => {
                    leerTexto(ocupa.value);
                });

                function leerTexto(ocupa) {
                    const speech = new SpeechSynthesisUtterance();
                    speech.text = ocupa;
                    speech.volume = 1;
                    speech.rate = 1;
                    speech.pitch = 0;
                    window.speechSynthesis.speak(speech);
                }
                </script>

                <div class="col-sm-4">
                    <div class="form-group">
                        <strong><label for="tip_sang"><button type="button" class="btn btn-success btn-sm"
                                    id="play111"><i class="fas fa-play"></button></i> Tipo de sangre:</label></strong>
                        <select class="form-control" id="tip_sang" name="tip_sang" required="">
                            <option value="">Seleccionar</option>
                            <option value="O Rh(-)">O Rh(-)</option>
                            <option value="O Rh(+)">O Rh(+)</option>
                            <option value="A Rh(-)">A Rh(-)</option>
                            <option value="A Rh(+)">A Rh(+)</option>
                            <option value="B Rh(-)">B Rh(-)</option>
                            <option value="B Rh(+)">B Rh(+)</option>
                            <option value="AB Rh(-)">AB Rh(-)</option>
                            <option value="AB Rh(+)">AB Rh(+)</option>
                            <option value="No especificado">No especificado</option>
                        </select>
                    </div>

                    <script type="text/javascript">
                    const tip_sang = document.getElementById('tip_sang');
                    const btn111 = document.getElementById('play111');
                    btn111.addEventListener('click', () => {
                        leerTexto(tip_sang.value);
                    });

                    function leerTexto(tip_sang) {
                        const speech = new SpeechSynthesisUtterance();
                        speech.text = tip_sang;
                        speech.volume = 1;
                        speech.rate = 1;
                        speech.pitch = 0;
                        window.speechSynthesis.speak(speech);
                    }
                    </script>
                </div>

            </div>
        </div>


        <br>

        <div class="container">

            <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 19px;">
                <strong>
                    <center> ANTECEDENTES HEREDO FAMILIARES</center>
                </strong>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="botones">
                        <button type="button" class="btn btn-danger btn-sm" id="btnStartRecord"><i
                                class="fas fa-microphone">
                        </button></i>
                        <button type="button" class="btn btn-primary btn-sm" id="btnStopRecord"><i
                                class="fas fa-microphone-slash"></button></i>
                        <button type="button" class="btn btn-success btn-sm" id="play2"><i
                                class="fas fa-play"></button></i>
                    </div>

                    <div class="form-group">
                        <textarea class="form-control" name="hc_her_o" rows="3" required id="texto"></textarea>
                        <script type="text/javascript">
                        const btnStartRecord = document.getElementById('btnStartRecord');
                        const btnStopRecord = document.getElementById('btnStopRecord');
                        const texto = document.getElementById('texto');

                        const btn3 = document.getElementById('play2');

                        btn3.addEventListener('click', () => {
                            leerTexto(texto.value);
                        });

                        function leerTexto(texto) {
                            const speech = new SpeechSynthesisUtterance();
                            speech.text = texto;
                            speech.volume = 1;
                            speech.rate = 1;
                            speech.pitch = 0;
                            window.speechSynthesis.speak(speech);
                        }

                        let recognition = new webkitSpeechRecognition();
                        recognition.lang = "es-ES";
                        recognition.continuous = true;
                        recognition.interimResults = false;

                        recognition.onresult = (event) => {
                            const results = event.results;
                            const frase = results[results.length - 1][0].transcript;
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

            <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 19px;">
                <strong>
                    <center>ANTECEDENTES PERSONALES NO PATOLÓGICOS</center>
                </strong>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <div class="botones">
                            <button type="button" class="btn btn-danger btn-sm" id="g"><i class="fas fa-microphone">
                            </button></i>
                            <button type="button" class="btn btn-primary btn-sm" id="d"><i
                                    class="fas fa-microphone-slash"></button></i>
                            <button type="button" class="btn btn-success btn-sm" id="play3"><i
                                    class="fas fa-play"></button></i>
                        </div>
                        <textarea class="form-control" id="nop" name="hc_otro" rows="3" required></textarea>
                        <script type="text/javascript">
                        const g = document.getElementById('g');
                        const d = document.getElementById('d');
                        const nop = document.getElementById('nop');

                        const btn4 = document.getElementById('play3');

                        btn4.addEventListener('click', () => {
                            leerTexto(nop.value);
                        });

                        function leerTexto(nop) {
                            const speech = new SpeechSynthesisUtterance();
                            speech.text = nop;
                            speech.volume = 1;
                            speech.rate = 1;
                            speech.pitch = 0;
                            window.speechSynthesis.speak(speech);
                        }

                        let r = new webkitSpeechRecognition();
                        r.lang = "es-ES";
                        r.continuous = true;
                        r.interimResults = false;

                        r.onresult = (event) => {
                            const results = event.results;
                            const frase = results[results.length - 1][0].transcript;
                            nop.value += frase;
                        }

                        g.addEventListener('click', () => {
                            r.start();
                        });

                        d.addEventListener('click', () => {
                            r.abort();
                        });
                        </script>
                    </div>
                </div>
            </div>

            <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 19px;">
                <strong>
                    <center>ANTECEDENTES PERSONALES PATOLÓGICOS</center>
                </strong>
            </div>
            <div class="form-group">

                <label for="exampleFormControlTextarea1"><strong>Describir:</strong></label>
                <div class="botones">
                    <button type="button" class="btn btn-danger btn-sm" id="gr"><i class="fas fa-microphone">
                    </button></i>
                    <button type="button" class="btn btn-primary btn-sm" id="de"><i
                            class="fas fa-microphone-slash"></button></i>
                    <button type="button" class="btn btn-success btn-sm" id="play4"><i class="fas fa-play"></button></i>
                </div>
                <textarea class="form-control" id="qui" rows="3" name=" hc_pato" required></textarea>
                <script type="text/javascript">
                const gr = document.getElementById('gr');
                const de = document.getElementById('de');
                const qui = document.getElementById('qui');

                const btn5 = document.getElementById('play4');

                btn5.addEventListener('click', () => {
                    leerTexto(qui.value);
                });

                function leerTexto(qui) {
                    const speech = new SpeechSynthesisUtterance();
                    speech.text = qui;
                    speech.volume = 1;
                    speech.rate = 1;
                    speech.pitch = 0;
                    window.speechSynthesis.speak(speech);
                }
                let rq = new webkitSpeechRecognition();
                rq.lang = "es-ES";
                rq.continuous = true;
                rq.interimResults = false;

                rq.onresult = (event) => {
                    const results = event.results;
                    const frase = results[results.length - 1][0].transcript;
                    qui.value += frase;
                }

                gr.addEventListener('click', () => {
                    rq.start();
                });

                de.addEventListener('click', () => {
                    rq.abort();
                });
                </script>
            </div>
            <!--   <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>Traumáticos:</strong></label>
      <div class="botones">
        <button type="button" class="btn btn-danger btn-sm" id="grabar"><i class="fas fa-microphone">
</button></i>
       <button type="button" class="btn btn-primary btn-sm" id="detener"><i class="fas fa-microphone-slash"></button></i>
       <button type="button" class="btn btn-success btn-sm" id="play5"><i class="fas fa-play"></button></i>
      </div>
<textarea class="form-control" id="tra" rows="3" name=" hc_pato_cual" required></textarea>
<script type="text/javascript">
const grabar = document.getElementById('grabar');
const detener = document.getElementById('detener');
const tra = document.getElementById('tra');

const btn6 = document.getElementById('play5');

btn6.addEventListener('click', () => {
        leerTexto(tra.value);
});

function leerTexto(tra){
    const speech = new SpeechSynthesisUtterance();
    speech.text= tra;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let rt = new webkitSpeechRecognition();
      rt.lang = "es-ES";
      rt.continuous = true;
      rt.interimResults = false;

      rt.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        tra.value += frase;
      }

      grabar.addEventListener('click', () => {
        rt.start();
      });

      detener.addEventListener('click', () => {
        rt.abort();
      });
</script>
  </div>-->

            <!-- <div class="form-group"> <label for="exampleFormControlTextarea1"><strong>Otros antecedentes personales patológicos:</strong></label>
    <div class="botones">
        <button type="button" class="btn btn-danger btn-sm" id="grabaro"><i class="fas fa-microphone">
</button></i>
       <button type="button" class="btn btn-primary btn-sm" id="detenero"><i class="fas fa-microphone-slash"></button></i>
       <button type="button" class="btn btn-success btn-sm" id="play6"><i class="fas fa-play"></button></i>
      </div>
<textarea class="form-control" id="os" rows="3" name=" hc_enf_cual" required></textarea>
    <script type="text/javascript">
const grabaro = document.getElementById('grabaro');
const detenero = document.getElementById('detenero');
const os = document.getElementById('os');

const btn7 = document.getElementById('play6');

btn7.addEventListener('click', () => {
        leerTexto(os.value);
});

function leerTexto(os){
    const speech = new SpeechSynthesisUtterance();
    speech.text= os;
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
        os.value += frase;
      }

      grabaro.addEventListener('click', () => {
        rs.start();
      });

      detenero.addEventListener('click', () => {
        rs.abort();
      });
</script>
  </div>-->



            <?php
  include "../../conexionbd.php";

$resultado1 = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE id_atencion=" . $_SESSION['hospital']) or die($conexion->error);

                    while ($f1 = mysqli_fetch_array($resultado1)) {
                    $sexo=$f1['sexo'];
                    }
                           if($sexo=='M' || $sexo=='Mujer' || $sexo=='MUJER' ){
                        ?>
            <div class="container">
                <div class="row">
                    <div class="col">

                        <div class="thead" style="background-color: #2b2d7f ; color: white; font-size: 19px;">
                            <strong>
                                <center>ANTECEDENTES GINECO / OBSTÉTRICOS </center>
                            </strong>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-2">
                                    <strong><label for="hc_men"><button type="button" class="btn btn-success btn-sm"
                                                id="play7"><i class="fas fa-play"></button></i>
                                            Menarca:</label></strong><br>
                                    <input type="text" name="hc_men" id="hc_men" value=""
                                        onkeypress="return Curp(event);" maxlength="20" class="form-control">
                                    <script type="text/javascript">
                                    const hc_men = document.getElementById('hc_men');
                                    const btn8 = document.getElementById('play7');

                                    btn8.addEventListener('click', () => {
                                        leerTexto(hc_men.value);
                                    });

                                    function leerTexto(hc_men) {
                                        const speech = new SpeechSynthesisUtterance();
                                        speech.text = hc_men;
                                        speech.volume = 1;
                                        speech.rate = 1;
                                        speech.pitch = 0;
                                        window.speechSynthesis.speak(speech);
                                    }
                                    </script>
                                </div>
                                <div class="col-sm">
                                    <strong><label for="hc_ritmo"><button type="button" class="btn btn-success btn-sm"
                                                id="play8"><i class="fas fa-play"></button></i>
                                            Ritmo:</label></strong><br>
                                    <input type="text" name="hc_ritmo" id="hc_ritmo" value=""
                                        onkeypress="return Curp(event);" maxlength="20" class="form-control">
                                </div>
                                <script type="text/javascript">
                                const hc_ritmo = document.getElementById('hc_ritmo');
                                const btn9 = document.getElementById('play8');

                                btn9.addEventListener('click', () => {
                                    leerTexto(hc_ritmo.value);
                                });

                                function leerTexto(hc_ritmo) {
                                    const speech = new SpeechSynthesisUtterance();
                                    speech.text = hc_ritmo;
                                    speech.volume = 1;
                                    speech.rate = 1;
                                    speech.pitch = 0;
                                    window.speechSynthesis.speak(speech);
                                }
                                </script>
                                <div class="col-sm">
                                    <strong><label for="hc_ges"><button type="button" class="btn btn-success btn-sm"
                                                id="play9"><i class="fas fa-play"></button></i>
                                            Gestas:</label></strong><br>
                                    <input type="text" name="hc_ges" id="hc_ges" value=""
                                        onkeypress="return SoloNumeros(event);" maxlength="2" class="form-control">
                                </div>
                                <script type="text/javascript">
                                const hc_ges = document.getElementById('hc_ges');
                                const btn10 = document.getElementById('play9');

                                btn10.addEventListener('click', () => {
                                    leerTexto(hc_ges.value);
                                });

                                function leerTexto(hc_ges) {
                                    const speech = new SpeechSynthesisUtterance();
                                    speech.text = hc_ges;
                                    speech.volume = 1;
                                    speech.rate = 1;
                                    speech.pitch = 0;
                                    window.speechSynthesis.speak(speech);
                                }
                                </script>
                                <div class="col-sm">
                                    <strong><label for="hc_par"><button type="button" class="btn btn-success btn-sm"
                                                id="play10"><i class="fas fa-play"></button></i>
                                            Partos:</label></strong><br>
                                    <input type="text" name="hc_par" id="hc_par" value=""
                                        onkeypress="return SoloNumeros(event);" nmaxlength="2" class="form-control">
                                </div>
                                <script type="text/javascript">
                                const hc_par = document.getElementById('hc_par');
                                const btn11 = document.getElementById('play10');

                                btn11.addEventListener('click', () => {
                                    leerTexto(hc_par.value);
                                });

                                function leerTexto(hc_par) {
                                    const speech = new SpeechSynthesisUtterance();
                                    speech.text = hc_par;
                                    speech.volume = 1;
                                    speech.rate = 1;
                                    speech.pitch = 0;
                                    window.speechSynthesis.speak(speech);
                                }
                                </script>
                                <div class="col-sm-2">
                                    <strong><label for="hc_ces"><button type="button" class="btn btn-success btn-sm"
                                                id="play11"><i class="fas fa-play"></button></i>
                                            Cesáreas:</label></strong><br>
                                    <input type="text" name="hc_ces" id="hc_ces" value=""
                                        onkeypress="return SoloNumeros(event);" maxlength="2" class="form-control">
                                </div>
                                <script type="text/javascript">
                                const hc_ces = document.getElementById('hc_ces');
                                const btn12 = document.getElementById('play11');

                                btn12.addEventListener('click', () => {
                                    leerTexto(hc_ces.value);
                                });

                                function leerTexto(hc_ces) {
                                    const speech = new SpeechSynthesisUtterance();
                                    speech.text = hc_ces;
                                    speech.volume = 1;
                                    speech.rate = 1;
                                    speech.pitch = 0;
                                    window.speechSynthesis.speak(speech);
                                }
                                </script>
                                <div class="col-sm-2">
                                    <strong><label for="hc_abo"><button type="button" class="btn btn-success btn-sm"
                                                id="play12"><i class="fas fa-play"></button></i>
                                            Abortos:</label></strong><br>
                                    <input type="text" name="hc_abo" id="hc_abo" value=""
                                        onkeypress="return SoloNumeros(event);" maxlength="2" class="form-control">
                                </div>
                                <script type="text/javascript">
                                const hc_abo = document.getElementById('hc_abo');
                                const btn13 = document.getElementById('play12');

                                btn13.addEventListener('click', () => {
                                    leerTexto(hc_abo.value);
                                });

                                function leerTexto(hc_abo) {
                                    const speech = new SpeechSynthesisUtterance();
                                    speech.text = hc_abo;
                                    speech.volume = 1;
                                    speech.rate = 1;
                                    speech.pitch = 0;
                                    window.speechSynthesis.speak(speech);
                                }
                                </script>
                                <div class="col-sm-2">
                                    <strong><label for="hc_fechafur">Fecha última regla:</label></strong><br>
                                    <input type="date" name="hc_fechafur" value="" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>

        </div>

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">

                        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 19px;">
                            <strong>
                                <center>PADECIMIENTO ACTUAL</center>
                            </strong>
                        </div>

                        <div class="botones">
                            <button type="button" class="btn btn-danger btn-sm" id="padeg"><i class="fas fa-microphone">
                            </button></i>
                            <button type="button" class="btn btn-primary btn-sm" id="detp"><i
                                    class="fas fa-microphone-slash"></button></i>
                            <button type="button" class="btn btn-success btn-sm" id="play13"><i
                                    class="fas fa-play"></button></i>
                        </div>
                        <textarea class="form-control" name="hc_pade" id="actual" rows="3" required
                            onkeypress="return Curp(event);"></textarea>
                        <script type="text/javascript">
                        const padeg = document.getElementById('padeg');
                        const detp = document.getElementById('detp');
                        const actual = document.getElementById('actual');

                        const btn14 = document.getElementById('play13');

                        btn14.addEventListener('click', () => {
                            leerTexto(actual.value);
                        });

                        function leerTexto(actual) {
                            const speech = new SpeechSynthesisUtterance();
                            speech.text = actual;
                            speech.volume = 1;
                            speech.rate = 1;
                            speech.pitch = 0;
                            window.speechSynthesis.speak(speech);
                        }

                        let rac = new webkitSpeechRecognition();
                        rac.lang = "es-ES";
                        rac.continuous = true;
                        rac.interimResults = false;

                        rac.onresult = (event) => {
                            const results = event.results;
                            const frase = results[results.length - 1][0].transcript;
                            actual.value += frase;
                        }

                        padeg.addEventListener('click', () => {
                            rac.start();
                        });

                        detp.addEventListener('click', () => {
                            rac.abort();
                        });
                        </script>

                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 19px;">
                            <strong>
                                <center>INTERROGATORIO POR APARATOS Y SISTEMAS</center>
                            </strong>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlTextarea1"><strong>Describir:</strong></label>
                            <div class="botones">
                                <button type="button" class="btn btn-danger btn-sm" id="carg"><i
                                        class="fas fa-microphone">
                                </button></i>
                                <button type="button" class="btn btn-primary btn-sm" id="detc"><i
                                        class="fas fa-microphone-slash"></button></i>
                                <button type="button" class="btn btn-success btn-sm" id="play14"><i
                                        class="fas fa-play"></button></i>
                            </div>
                            <textarea class="form-control" id="vas" rows="3" name="cardio" required></textarea>
                            <script type="text/javascript">
                            const carg = document.getElementById('carg');
                            const detc = document.getElementById('detc');
                            const vas = document.getElementById('vas');

                            const btn15 = document.getElementById('play14');

                            btn15.addEventListener('click', () => {
                                leerTexto(vas.value);
                            });

                            function leerTexto(vas) {
                                const speech = new SpeechSynthesisUtterance();
                                speech.text = vas;
                                speech.volume = 1;
                                speech.rate = 1;
                                speech.pitch = 0;
                                window.speechSynthesis.speak(speech);
                            }

                            let rvas = new webkitSpeechRecognition();
                            rvas.lang = "es-ES";
                            rvas.continuous = true;
                            rvas.interimResults = false;

                            rvas.onresult = (event) => {
                                const results = event.results;
                                const frase = results[results.length - 1][0].transcript;
                                vas.value += frase;
                            }

                            carg.addEventListener('click', () => {
                                rvas.start();
                            });

                            detc.addEventListener('click', () => {
                                rvas.abort();
                            });
                            </script>
                        </div>


                        <!--  <div class="form-group">    <label for="exampleFormControlTextarea1"><strong>Respiratorio:</strong></label>
      <div class="botones">
        <button type="button" class="btn btn-danger btn-sm" id="resg"><i class="fas fa-microphone">
</button></i>
       <button type="button" class="btn btn-primary btn-sm" id="detrio"><i class="fas fa-microphone-slash"></button></i>
       <button type="button" class="btn btn-success btn-sm" id="play15"><i class="fas fa-play"></button></i>
      </div>
    <textarea class="form-control" id="tpira" rows="3" name="respira" required ></textarea>
    <script type="text/javascript">
const resg = document.getElementById('resg');
const detrio = document.getElementById('detrio');
const tpira = document.getElementById('tpira');

const btn16 = document.getElementById('play15');

btn16.addEventListener('click', () => {
        leerTexto(tpira.value);
});

function leerTexto(tpira){
    const speech = new SpeechSynthesisUtterance();
    speech.text= tpira;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let rpira = new webkitSpeechRecognition();
      rpira.lang = "es-ES";
      rpira.continuous = true;
      rpira.interimResults = false;

      rpira.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        tpira.value += frase;
      }

      resg.addEventListener('click', () => {
        rpira.start();
      });

      detrio.addEventListener('click', () => {
        rpira.abort();
      });
</script>
  </div>
   <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>Gastrointestinal:</strong></label>
    <div class="botones">
        <button type="button" class="btn btn-danger btn-sm" id="gg"><i class="fas fa-microphone">
</button></i>
       <button type="button" class="btn btn-primary btn-sm" id="detgast"><i class="fas fa-microphone-slash"></button></i>
       <button type="button" class="btn btn-success btn-sm" id="play16"><i class="fas fa-play"></button></i>
      </div>
<textarea class="form-control" id="tgas" rows="3" name="gastro" required ></textarea>
<script type="text/javascript">
const gg = document.getElementById('gg');
const detgast = document.getElementById('detgast');
const tgas = document.getElementById('tgas');

const btn17 = document.getElementById('play16');

btn17.addEventListener('click', () => {
        leerTexto(tgas.value);
});

function leerTexto(tgas){
    const speech = new SpeechSynthesisUtterance();
    speech.text= tgas;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let rgas = new webkitSpeechRecognition();
      rgas.lang = "es-ES";
      rgas.continuous = true;
      rgas.interimResults = false;

      rgas.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        tgas.value += frase;
      }

      gg.addEventListener('click', () => {
        rgas.start();
      });

      detgast.addEventListener('click', () => {
        rgas.abort();
      });
</script>
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>Genitourinario:</strong></label>
    <div class="botones">
        <button type="button" class="btn btn-danger btn-sm" id="geng"><i class="fas fa-microphone">
</button></i>
       <button type="button" class="btn btn-primary btn-sm" id="detni"><i class="fas fa-microphone-slash"></button></i>
       <button type="button" class="btn btn-success btn-sm" id="play17"><i class="fas fa-play"></button></i>
      </div>
<textarea class="form-control" id="txtog" rows="3" name="genito" required ></textarea>
<script type="text/javascript">
const geng = document.getElementById('geng');
const detni = document.getElementById('detni');
const txtog = document.getElementById('txtog');

const btn18 = document.getElementById('play17');

btn18.addEventListener('click', () => {
        leerTexto(txtog.value);
});

function leerTexto(txtog){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtog;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let reco = new webkitSpeechRecognition();
      reco.lang = "es-ES";
      reco.continuous = true;
      reco.interimResults = false;

      reco.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtog.value += frase;
      }

      geng.addEventListener('click', () => {
        reco.start();
      });

      detni.addEventListener('click', () => {
        reco.abort();
      });
</script>
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>Hemático y linfático:</strong></label>
    <div class="botones">
        <button type="button" class="btn btn-danger btn-sm" id="ghema"><i class="fas fa-microphone">
</button></i>
       <button type="button" class="btn btn-primary btn-sm" id="detenerh"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="play18"><i class="fas fa-play"></button></i>
      </div>
<textarea class="form-control" id="teh" rows="3" name="hematico" required ></textarea>
<script type="text/javascript">
const ghema = document.getElementById('ghema');
const detenerh = document.getElementById('detenerh');
const teh = document.getElementById('teh');

const btn19 = document.getElementById('play18');

btn19.addEventListener('click', () => {
        leerTexto(teh.value);
});

function leerTexto(teh){
    const speech = new SpeechSynthesisUtterance();
    speech.text= teh;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let reh = new webkitSpeechRecognition();
      reh.lang = "es-ES";
      reh.continuous = true;
      reh.interimResults = false;

      reh.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        teh.value += frase;
      }

      ghema.addEventListener('click', () => {
        reh.start();
      });

      detenerh.addEventListener('click', () => {
        reh.abort();
      });
</script>
  </div>
   <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>Endócrino:</strong></label>
    <div class="botones">
        <button type="button" class="btn btn-danger btn-sm" id="endog"><i class="fas fa-microphone">
</button></i>
       <button type="button" class="btn btn-primary btn-sm" id="deteneren"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="play19"><i class="fas fa-play"></button></i>
      </div>
<textarea class="form-control" id="texe" rows="3" name="endocrino" required ></textarea>
<script type="text/javascript">
const endog = document.getElementById('endog');
const deteneren = document.getElementById('deteneren');
const texe = document.getElementById('texe');

const btn20 = document.getElementById('play19');

btn20.addEventListener('click', () => {
        leerTexto(texe.value);
});

function leerTexto(texe){
    const speech = new SpeechSynthesisUtterance();
    speech.text= texe;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let reen = new webkitSpeechRecognition();
      reen.lang = "es-ES";
      reen.continuous = true;
      reen.interimResults = false;

      reen.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        texe.value += frase;
      }

      endog.addEventListener('click', () => {
        reen.start();
      });

      deteneren.addEventListener('click', () => {
        reen.abort();
      });
</script>
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>Nervioso:</strong></label>
     <div class="botones">
        <button type="button" class="btn btn-danger btn-sm" id="nerviosog"><i class="fas fa-microphone">
</button></i>
       <button type="button" class="btn btn-primary btn-sm" id="deteneroso"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="play20"><i class="fas fa-play"></button></i>
      </div>
<textarea class="form-control" id="texn" rows="3" name="nervioso" required ></textarea>
<script type="text/javascript">
const nerviosog = document.getElementById('nerviosog');
const deteneroso = document.getElementById('deteneroso');
const texn = document.getElementById('texn');

const btn21 = document.getElementById('play20');

btn21.addEventListener('click', () => {
        leerTexto(texn.value);
});

function leerTexto(texn){
    const speech = new SpeechSynthesisUtterance();
    speech.text= texn;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
     let rner = new webkitSpeechRecognition();
      rner.lang = "es-ES";
      rner.continuous = true;
      rner.interimResults = false;

      rner.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        texn.value += frase;
      }

      nerviosog.addEventListener('click', () => {
        rner.start();
      });

      deteneroso.addEventListener('click', () => {
        rner.abort();
      });
</script>
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>Musculoesquelético:</strong></label>
    <div class="botones">
        <button type="button" class="btn btn-danger btn-sm" id="musculog"><i class="fas fa-microphone">
</button></i>
       <button type="button" class="btn btn-primary btn-sm" id="detenerico"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="play21"><i class="fas fa-play"></button></i>
      </div>
<textarea class="form-control" id="txtm" rows="3" name="musculo" required ></textarea>
<script type="text/javascript">
const musculog = document.getElementById('musculog');
const detenerico = document.getElementById('detenerico');
const txtm = document.getElementById('txtm');

const btn22 = document.getElementById('play21');

btn22.addEventListener('click', () => {
        leerTexto(txtm.value);
});

function leerTexto(txtm){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtm;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let rmus = new webkitSpeechRecognition();
      rmus.lang = "es-ES";
      rmus.continuous = true;
      rmus.interimResults = false;

      rmus.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtm.value += frase;
      }

      musculog.addEventListener('click', () => {
        rmus.start();
      });

      detenerico.addEventListener('click', () => {
        rmus.abort();
      });
</script>
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>Piel, mucosas y anexos:</strong></label>
    <div class="botones">
        <button type="button" class="btn btn-danger btn-sm" id="pielg"><i class="fas fa-microphone">
</button></i>
       <button type="button" class="btn btn-primary btn-sm" id="detenerane"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="play22"><i class="fas fa-play"></button></i>
      </div>
<textarea class="form-control" id="txtp" rows="3" name="anexos" required ></textarea>
<script type="text/javascript">
const pielg = document.getElementById('pielg');
const detenerane = document.getElementById('detenerane');
const txtp = document.getElementById('txtp');

const btn23 = document.getElementById('play22');

btn23.addEventListener('click', () => {
        leerTexto(txtp.value);
});

function leerTexto(txtp){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtp;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let rpma = new webkitSpeechRecognition();
      rpma.lang = "es-ES";
      rpma.continuous = true;
      rpma.interimResults = false;

      rpma.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtp.value += frase;
      }

      pielg.addEventListener('click', () => {
        rpma.start();
      });

      detenerane.addEventListener('click', () => {
        rpma.abort();
      });
</script>
  </div>   -->

                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 19px;">
                            <strong>
                                <center>EXPLORACIÓN FÍSICA</center>
                            </strong>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1"><strong>Describir:</strong></label>
                            <div class="botones">
                                <button type="button" class="btn btn-danger btn-sm" id="habitusg"><i
                                        class="fas fa-microphone">
                                </button></i>
                                <button type="button" class="btn btn-primary btn-sm" id="detenerext"><i
                                        class="fas fa-microphone-slash"></button></i>
                                <button type="button" class="btn btn-success btn-sm" id="play23"><i
                                        class="fas fa-play"></button></i>
                            </div>
                            <textarea class="form-control" id="txthe" rows="3" name="habitus" required></textarea>
                            <script type="text/javascript">
                            const habitusg = document.getElementById('habitusg');
                            const detenerext = document.getElementById('detenerext');
                            const txthe = document.getElementById('txthe');

                            const btn24 = document.getElementById('play23');

                            btn24.addEventListener('click', () => {
                                leerTexto(txthe.value);
                            });

                            function leerTexto(txthe) {
                                const speech = new SpeechSynthesisUtterance();
                                speech.text = txthe;
                                speech.volume = 1;
                                speech.rate = 1;
                                speech.pitch = 0;
                                window.speechSynthesis.speak(speech);
                            }
                            let rhexte = new webkitSpeechRecognition();
                            rhexte.lang = "es-ES";
                            rhexte.continuous = true;
                            rhexte.interimResults = false;

                            rhexte.onresult = (event) => {
                                const results = event.results;
                                const frase = results[results.length - 1][0].transcript;
                                txthe.value += frase;
                            }

                            habitusg.addEventListener('click', () => {
                                rhexte.start();
                            });

                            detenerext.addEventListener('click', () => {
                                rhexte.abort();
                            });
                            </script>
                        </div>

                        <!--<div class="form-group">      <label for="exampleFormControlTextarea1"><strong>Cabeza:</strong></label>
      <div class="botones">
        <button type="button" class="btn btn-danger btn-sm" id="cabezagg"><i class="fas fa-microphone">
</button></i>
       <button type="button" class="btn btn-primary btn-sm" id="detenerza"><i class="fas fa-microphone-slash"></button></i>
       <button type="button" class="btn btn-success btn-sm" id="play24"><i class="fas fa-play"></button></i>
      </div>
<textarea class="form-control" id="txtcab" rows="3" name="cabeza" required ></textarea>
      <script type="text/javascript">
const cabezagg = document.getElementById('cabezagg');
const detenerza = document.getElementById('detenerza');
const txtcab = document.getElementById('txtcab');

const btn25 = document.getElementById('play24');

btn25.addEventListener('click', () => {
        leerTexto(txtcab.value);
});

function leerTexto(txtcab){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtcab;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
     let rcaza = new webkitSpeechRecognition();
      rcaza.lang = "es-ES";
      rcaza.continuous = true;
      rcaza.interimResults = false;

      rcaza.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtcab.value += frase;
      }

      cabezagg.addEventListener('click', () => {
        rcaza.start();
      });

      detenerza.addEventListener('click', () => {
        rcaza.abort();
      });
</script>
</div>  

<div class="form-group">
      <label for="exampleFormControlTextarea1"><strong>Neurológico:</strong></label>
      <div class="botones">
        <button type="button" class="btn btn-danger btn-sm" id="neuroagg"><i class="fas fa-microphone">
</button></i>
       <button type="button" class="btn btn-primary btn-sm" id="detenerzan"><i class="fas fa-microphone-slash"></button></i>
       <button type="button" class="btn btn-success btn-sm" id="play241"><i class="fas fa-play"></button></i>
      </div>
<textarea class="form-control" id="txtneuro" rows="3" name="neuro" required ></textarea>
      <script type="text/javascript">
const neuroagg = document.getElementById('neuroagg');
const detenerzan = document.getElementById('detenerzan');
const txtneuro = document.getElementById('txtneuro');

const btn251 = document.getElementById('play241');

btn251.addEventListener('click', () => {
        leerTexto(txtneuro.value);
});

function leerTexto(txtneuro){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtneuro;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
     let rneuro = new webkitSpeechRecognition();
      rneuro.lang = "es-ES";
      rneuro.continuous = true;
      rneuro.interimResults = false;

      rneuro.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtneuro.value += frase;
      }

      neuroagg.addEventListener('click', () => {
        rneuro.start();
      });

      detenerzan.addEventListener('click', () => {
        rneuro.abort();
      });
</script>
    </div>


<div class="form-group">
      <label for="exampleFormControlTextarea1"><strong>Cuello:</strong></label>
      <div class="botones">
        <button type="button" class="btn btn-danger btn-sm" id="cuellog"><i class="fas fa-microphone">
</button></i>
       <button type="button" class="btn btn-primary btn-sm" id="detenllo"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="play25"><i class="fas fa-play"></button></i>
      </div>
<textarea class="form-control" id="txtlo" rows="3" name="cuello" required ></textarea>
<script type="text/javascript">
const cuellog = document.getElementById('cuellog');
const detenllo = document.getElementById('detenllo');
const txtlo = document.getElementById('txtlo');

const btn26 = document.getElementById('play25');

btn26.addEventListener('click', () => {
        leerTexto(txtlo.value);
});

function leerTexto(txtlo){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtlo;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
     let ruel = new webkitSpeechRecognition();
      ruel.lang = "es-ES";
      ruel.continuous = true;
      ruel.interimResults = false;

      ruel.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtlo.value += frase;
      }

      cuellog.addEventListener('click', () => {
        ruel.start();
      });

      detenllo.addEventListener('click', () => {
        ruel.abort();
      });
</script>
    </div> 
    <div class="form-group">
      <label for="exampleFormControlTextarea1"><strong>Tórax:</strong></label>
       <div class="botones">
        <button type="button" class="btn btn-danger btn-sm" id="toraxg"><i class="fas fa-microphone">
</button></i>
       <button type="button" class="btn btn-primary btn-sm" id="detnx"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="play26"><i class="fas fa-play"></button></i>
      </div>
<textarea class="form-control" id="txtx" rows="3" name="torax" required ></textarea>
<script type="text/javascript">
const toraxg = document.getElementById('toraxg');
const detnx = document.getElementById('detnx');
const txtx = document.getElementById('txtx');

const btn27 = document.getElementById('play26');

btn27.addEventListener('click', () => {
        leerTexto(txtx.value);
});

function leerTexto(txtx){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtx;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
     let recax = new webkitSpeechRecognition();
      recax.lang = "es-ES";
      recax.continuous = true;
      recax.interimResults = false;

      recax.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtx.value += frase;
      }

      toraxg.addEventListener('click', () => {
        recax.start();
      });

      detnx.addEventListener('click', () => {
        recax.abort();
      });
</script>
    </div> 
    <div class="form-group">
      <label for="exampleFormControlTextarea1"><strong>Abdomen:</strong></label>
      <div class="botones">
        <button type="button" class="btn btn-danger btn-sm" id="abdg"><i class="fas fa-microphone">
</button></i>
       <button type="button" class="btn btn-primary btn-sm" id="detmen"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="play27"><i class="fas fa-play"></button></i>
      </div>
<textarea class="form-control" id="txtdom" rows="3" name="abdomen" required ></textarea>
<script type="text/javascript">
const abdg = document.getElementById('abdg');
const detmen = document.getElementById('detmen');
const txtdom = document.getElementById('txtdom');

const btn28 = document.getElementById('play27');

btn28.addEventListener('click', () => {
        leerTexto(txtdom.value);
});

function leerTexto(txtdom){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtdom;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
     let rbd = new webkitSpeechRecognition();
      rbd.lang = "es-ES";
      rbd.continuous = true;
      rbd.interimResults = false;

      rbd.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtdom.value += frase;
      }

      abdg.addEventListener('click', () => {
        rbd.start();
      });

      detmen.addEventListener('click', () => {
        rbd.abort();
      });
</script>
    </div>
    <div class="form-group">
      <label for="exampleFormControlTextarea1"><strong>Genitales:</strong></label>
      <div class="botones">
        <button type="button" class="btn btn-danger btn-sm" id="gengrabar"><i class="fas fa-microphone">
</button></i>
       <button type="button" class="btn btn-primary btn-sm" id="stopg"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="play28"><i class="fas fa-play"></button></i>
      </div>
<textarea class="form-control" id="txtess" rows="3" name="genitales" required ></textarea>
      <script type="text/javascript">
const gengrabar = document.getElementById('gengrabar');
const stopg = document.getElementById('stopg');
const txtess = document.getElementById('txtess');

const btn29 = document.getElementById('play28');

btn29.addEventListener('click', () => {
        leerTexto(txtess.value);
});

function leerTexto(txtess){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtess;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
     let rgeni = new webkitSpeechRecognition();
      rgeni.lang = "es-ES";
      rgeni.continuous = true;
      rgeni.interimResults = false;

      rgeni.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtess.value += frase;
      }

      gengrabar.addEventListener('click', () => {
        rgeni.start();
      });

      stopg.addEventListener('click', () => {
        rgeni.abort();
      });
</script>
    </div>
    <div class="form-group">
      <label for="exampleFormControlTextarea1"><strong>Extremidades:</strong></label>
      <div class="botones">
        <button type="button" class="btn btn-danger btn-sm" id="extg"><i class="fas fa-microphone">
</button></i>
       <button type="button" class="btn btn-primary btn-sm" id="detenerexre"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="play29"><i class="fas fa-play"></button></i>
      </div>
<textarea class="form-control" id="txtextr" rows="3" name="extrem" required ></textarea>
 <script type="text/javascript">
const extg = document.getElementById('extg');
const detenerexre = document.getElementById('detenerexre');
const txtextr = document.getElementById('txtextr');

const btn30 = document.getElementById('play29');

btn30.addEventListener('click', () => {
        leerTexto(txtextr.value);
});

function leerTexto(txtextr){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtextr;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
     let rdades = new webkitSpeechRecognition();
      rdades.lang = "es-ES";
      rdades.continuous = true;
      rdades.interimResults = false;

      rdades.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtextr.value += frase;
      }

      extg.addEventListener('click', () => {
        rdades.start();
      });

      detenerexre.addEventListener('click', () => {
        rdades.abort();
      });
</script>
    </div>
    <div class="form-group">
      <label for="exampleFormControlTextarea1"><strong>Piel:</strong></label>
      <div class="botones">
        <button type="button" class="btn btn-danger btn-sm" id="plgrabar"><i class="fas fa-microphone">
</button></i>
       <button type="button" class="btn btn-primary btn-sm" id="detenerel"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="play30"><i class="fas fa-play"></button></i>
      </div>
<textarea class="form-control" id="txtpp" rows="3" name="piel" required ></textarea>
       <script type="text/javascript">
const plgrabar = document.getElementById('plgrabar');
const detenerel = document.getElementById('detenerel');
const txtpp = document.getElementById('txtpp');

const btn31 = document.getElementById('play30');

btn31.addEventListener('click', () => {
        leerTexto(txtpp.value);
});

function leerTexto(txtpp){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtpp;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let rpiell = new webkitSpeechRecognition();
      rpiell.lang = "es-ES";
      rpiell.continuous = true;
      rpiell.interimResults = false;

      rpiell.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtpp.value += frase;
      }

      plgrabar.addEventListener('click', () => {
        rpiell.start();
      });

      detenerel.addEventListener('click', () => {
        rpiell.abort();
      });
</script>
    </div>  -->
                    </div>
                </div>
            </div>

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
            <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 19px;">
                <center><strong>SIGNOS VITALES</strong></center>
                <p>
            </div>
            <div class="container">
                <div class="row">

                    <div class="col-sm-2">
                        <center>Presión arterial:</center>
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
                        Frecuencia cardiaca:<input type="text" class="form-control" value="<?php echo $f5['fcard'];?>"
                            disabled> Latidos por minuto
                    </div>
                    <div class="col-sm-3">
                        Frecuencia respiratoria:<input type="text" class="form-control"
                            value="<?php echo $f5['fresp'];?>" disabled> Respiraciones por minuto
                    </div>
                    <div class="col-sm-2">
                        Temperatura:<input type="text" class="form-control" value="<?php echo $f5['temper'];?>"
                            disabled>°C
                    </div>
                    <div class="col-sm-3">
                        Saturación de oxígeno:<input type="text" class="form-control"
                            value="<?php echo $f5['satoxi'];?>" disabled>%
                    </div>
                </div>
            </div>
            <?php }
?>
            <?php 
}else{
                        
  ?>
            <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 19px;">
                <center><strong>SIGNOS VITALES</strong></center>
                <p>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-2"><br>
                        <center>Presión Arterial:</center>
                        <div class="row">
                            <div class="col"><input type="text" class="form-control" name="p_sistol"></div> /
                            <div class="col"><input type="text" class="form-control" name="p_diastol"></div>

                        </div>
                    </div>
                    <div class="col-sm-2">
                        Frecuencia Cardiaca:<input type="text" class="form-control" name="fcard">
                    </div>
                    <div class="col-sm-2">
                        Frecuencia Respiratoria:<input type="text" class="form-control" name="fresp">
                    </div>
                    <div class="col-sm-2">
                        <br>Temperatura:<input type="text" class="form-control" name="temper">
                    </div>
                    <div class="col-sm-2">
                        Saturación de oxígeno:<input type="text" class="form-control" name="satoxi">
                    </div>

                </div>
            </div>

            <?php } ?>
            <br>

            <div class="container">
                <div class="row">
                    <div class="col-sm"><strong><button type="button" class="btn btn-success btn-sm" id="play36"><i
                                    class="fas fa-play"></button></i> Peso <br>(kilos):</strong>
                        <input type="cm-number" name="peso" placeholder="" id="peso" class="form-control" required>
                    </div>
                    <script type="text/javascript">
                    const peso = document.getElementById('peso');
                    const btn38 = document.getElementById('play36');

                    btn38.addEventListener('click', () => {
                        leerTexto(peso.value);
                    });

                    function leerTexto(peso) {
                        const speech = new SpeechSynthesisUtterance();
                        speech.text = peso;
                        speech.volume = 1;
                        speech.rate = 1;
                        speech.pitch = 0;
                        window.speechSynthesis.speak(speech);
                    }
                    </script>
                    <div class="col-sm"><strong><button type="button" class="btn btn-success btn-sm" id="play37"><i
                                    class="fas fa-play"></button></i> Talla <br> (metros): </strong>
                        <input type="cm-number" name="talla" placeholder="" id="talla" class="form-control" required>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
            const talla = document.getElementById('talla');
            const btn39 = document.getElementById('play37');

            btn39.addEventListener('click', () => {
                leerTexto(talla.value);
            });

            function leerTexto(talla) {
                const speech = new SpeechSynthesisUtterance();
                speech.text = talla;
                speech.volume = 1;
                speech.rate = 1;
                speech.pitch = 0;
                window.speechSynthesis.speak(speech);
            }
            </script>


            <br>

            <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 19px;">
                <strong>
                    <center>RESULTADOS PREVIOS Y ACTUALES</center>
                </strong>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="hc_lab"><strong>Laboratorio:</strong></label>
                        <div class="botones">
                            <button type="button" class="btn btn-danger btn-sm" id="laborg"><i
                                    class="fas fa-microphone">
                            </button></i>
                            <button type="button" class="btn btn-primary btn-sm" id="detenerrio"><i
                                    class="fas fa-microphone-slash"></button></i>
                            <button type="button" class="btn btn-success btn-sm" id="play38"><i
                                    class="fas fa-play"></button></i>
                        </div>
                        <textarea class="form-control" name="hc_lab" rows="4" required id="txtbo"
                            onkeypress="return Curp(event);"></textarea>
                        <script type="text/javascript">
                        const laborg = document.getElementById('laborg');
                        const detenerrio = document.getElementById('detenerrio');
                        const txtbo = document.getElementById('txtbo');

                        const btn40 = document.getElementById('play38');

                        btn40.addEventListener('click', () => {
                            leerTexto(txtbo.value);
                        });

                        function leerTexto(txtbo) {
                            const speech = new SpeechSynthesisUtterance();
                            speech.text = txtbo;
                            speech.volume = 1;
                            speech.rate = 1;
                            speech.pitch = 0;
                            window.speechSynthesis.speak(speech);
                        }
                        let rrato = new webkitSpeechRecognition();
                        rrato.lang = "es-ES";
                        rrato.continuous = true;
                        rrato.interimResults = false;

                        rrato.onresult = (event) => {
                            const results = event.results;
                            const frase = results[results.length - 1][0].transcript;
                            txtbo.value += frase;
                        }

                        laborg.addEventListener('click', () => {
                            rrato.start();
                        });

                        detenerrio.addEventListener('click', () => {
                            rrato.abort();
                        });
                        </script>

                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="hc_gabi"><strong> Gabinete:</strong></label>
                        <div class="botones">
                            <button type="button" class="btn btn-danger btn-sm" id="gabineteg"><i
                                    class="fas fa-microphone">
                            </button></i>
                            <button type="button" class="btn btn-primary btn-sm" id="detenernete"><i
                                    class="fas fa-microphone-slash"></button></i>
                            <button type="button" class="btn btn-success btn-sm" id="play39"><i
                                    class="fas fa-play"></button></i>
                        </div>
                        <textarea class="form-control" name="hc_gabi" rows="4" required id="txtine"
                            onkeypress="return Curp(event);"></textarea>
                        <script type="text/javascript">
                        const gabineteg = document.getElementById('gabineteg');
                        const detenernete = document.getElementById('detenernete');
                        const txtine = document.getElementById('txtine');

                        const btn41 = document.getElementById('play39');

                        btn41.addEventListener('click', () => {
                            leerTexto(txtine.value);
                        });

                        function leerTexto(txtine) {
                            const speech = new SpeechSynthesisUtterance();
                            speech.text = txtine;
                            speech.volume = 1;
                            speech.rate = 1;
                            speech.pitch = 0;
                            window.speechSynthesis.speak(speech);
                        }
                        let rabi = new webkitSpeechRecognition();
                        rabi.lang = "es-ES";
                        rabi.continuous = true;
                        rabi.interimResults = false;

                        rabi.onresult = (event) => {
                            const results = event.results;
                            const frase = results[results.length - 1][0].transcript;
                            txtine.value += frase;
                        }

                        gabineteg.addEventListener('click', () => {
                            rabi.start();
                        });

                        detenernete.addEventListener('click', () => {
                            rabi.abort();
                        });
                        </script>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="hc_res_o"><strong>Otros resultados:</strong></label>
                        <div class="botones">
                            <button type="button" class="btn btn-danger btn-sm" id="otrosrgr"><i
                                    class="fas fa-microphone">
                            </button></i>
                            <button type="button" class="btn btn-primary btn-sm" id="detenerors"><i
                                    class="fas fa-microphone-slash"></button></i>
                            <button type="button" class="btn btn-success btn-sm" id="play40"><i
                                    class="fas fa-play"></button></i>
                        </div>
                        <textarea class="form-control" name="hc_res_o" rows="4" required id="txtore"
                            onkeypress="return Curp(event);"></textarea>
                        <script type="text/javascript">
                        const otrosrgr = document.getElementById('otrosrgr');
                        const detenerors = document.getElementById('detenerors');
                        const txtore = document.getElementById('txtore');

                        const btn42 = document.getElementById('play40');

                        btn42.addEventListener('click', () => {
                            leerTexto(txtore.value);
                        });

                        function leerTexto(txtore) {
                            const speech = new SpeechSynthesisUtterance();
                            speech.text = txtore;
                            speech.volume = 1;
                            speech.rate = 1;
                            speech.pitch = 0;
                            window.speechSynthesis.speak(speech);
                        }

                        let rotres = new webkitSpeechRecognition();
                        rotres.lang = "es-ES";
                        rotres.continuous = true;
                        rotres.interimResults = false;

                        rotres.onresult = (event) => {
                            const results = event.results;
                            const frase = results[results.length - 1][0].transcript;
                            txtore.value += frase;
                        }

                        otrosrgr.addEventListener('click', () => {
                            rotres.start();
                        });

                        detenerors.addEventListener('click', () => {
                            rotres.abort();
                        });
                        </script>

                    </div>
                </div>
            </div>

            <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 19px;">
                <strong>
                    <center>TRATAMIENTO FARMACOLÓGICO</center>
                </strong>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="hc_te"><strong>Terapéutica empleada y resultados previos:</strong></label>
                        <div class="botones">
                            <button type="button" class="btn btn-danger btn-sm" id="previosg"><i
                                    class="fas fa-microphone">
                            </button></i>
                            <button type="button" class="btn btn-primary btn-sm" id="detenerterp"><i
                                    class="fas fa-microphone-slash"></button></i>
                            <button type="button" class="btn btn-success btn-sm" id="play41"><i
                                    class="fas fa-play"></button></i>
                        </div>
                        <textarea class="form-control" name="hc_te" rows="7" required id="txtaer"
                            onkeypress="return(event);"></textarea>
                        <script type="text/javascript">
                        const previosg = document.getElementById('previosg');
                        const detenerterp = document.getElementById('detenerterp');
                        const txtaer = document.getElementById('txtaer');

                        const btn43 = document.getElementById('play41');

                        btn43.addEventListener('click', () => {
                            leerTexto(txtaer.value);
                        });

                        function leerTexto(txtaer) {
                            const speech = new SpeechSynthesisUtterance();
                            speech.text = txtaer;
                            speech.volume = 1;
                            speech.rate = 1;
                            speech.pitch = 0;
                            window.speechSynthesis.speak(speech);
                        }
                        let reraere = new webkitSpeechRecognition();
                        reraere.lang = "es-ES";
                        reraere.continuous = true;
                        reraere.interimResults = false;

                        reraere.onresult = (event) => {
                            const results = event.results;
                            const frase = results[results.length - 1][0].transcript;
                            txtaer.value += frase;
                        }

                        previosg.addEventListener('click', () => {
                            reraere.start();
                        });

                        detenerterp.addEventListener('click', () => {
                            reraere.abort();
                        });
                        </script>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="hc_ta"><strong>Terapéutica actual:</strong></label>
                        <div class="botones">
                            <button type="button" class="btn btn-danger btn-sm" id="teractualg"><i
                                    class="fas fa-microphone">
                            </button></i>
                            <button type="button" class="btn btn-primary btn-sm" id="detenerual"><i
                                    class="fas fa-microphone-slash"></button></i>
                            <button type="button" class="btn btn-success btn-sm" id="play42"><i
                                    class="fas fa-play"></button></i>
                        </div>
                        <textarea class="form-control" name="hc_ta" rows="7" required id="txtpeual"
                            onkeypress="return(event);"></textarea>
                        <script type="text/javascript">
                        const teractualg = document.getElementById('teractualg');
                        const detenerual = document.getElementById('detenerual');
                        const txtpeual = document.getElementById('txtpeual');

                        const btn44 = document.getElementById('play42');

                        btn44.addEventListener('click', () => {
                            leerTexto(txtpeual.value);
                        });

                        function leerTexto(txtpeual) {
                            const speech = new SpeechSynthesisUtterance();
                            speech.text = txtpeual;
                            speech.volume = 1;
                            speech.rate = 1;
                            speech.pitch = 0;
                            window.speechSynthesis.speak(speech);
                        }

                        let reraac = new webkitSpeechRecognition();
                        reraac.lang = "es-ES";
                        reraac.continuous = true;
                        reraac.interimResults = false;

                        reraac.onresult = (event) => {
                            const results = event.results;
                            const frase = results[results.length - 1][0].transcript;
                            txtpeual.value += frase;
                        }

                        teractualg.addEventListener('click', () => {
                            reraac.start();
                        });

                        detenerual.addEventListener('click', () => {
                            reraac.abort();
                        });
                        </script>
                    </div>
                </div>
            </div>


            <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 19px;">
                <strong>
                    <center>DIAGNÓSTICOS</center>
                </strong>
            </div>
            <p>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="id_cie_10"><strong><button type="button" class="btn btn-success btn-sm"
                                    id="play43"><i class="fas fa-play"></button></i> Seleccionar diagnóstico
                                principal:</strong></label>
                        <select name="id_cie_10" class="mibuscador3" data-live-search="true" id="mibuscador3"
                            style="width : 100%; heigth : 100%">
                            <option value="">Seleccionar</option>
                            <?php
                include "../../conexionbd.php";
                $sql_diag="SELECT * FROM cat_diag order by id_cie10 ";
                $result_diag=$conexion->query($sql_diag);
                while($row=$result_diag->fetch_assoc()){
                echo "<option value='" . $row['diagnostico'] . "'>" . $row['id_cie10'] . "- " .$row['diagnostico']."</option>"; 
                } ?>


                        </select>
                    </div>
                </div>
                <script type="text/javascript">
                const mibuscador3 = document.getElementById('mibuscador3');
                const btn45 = document.getElementById('play43');

                btn45.addEventListener('click', () => {
                    leerTexto(mibuscador3.value);
                });

                function leerTexto(mibuscador3) {
                    const speech = new SpeechSynthesisUtterance();
                    speech.text = mibuscador3;
                    speech.volume = 1;
                    speech.rate = 1;
                    speech.pitch = 0;
                    window.speechSynthesis.speak(speech);
                }
                </script>
                <div class="col-6">
                    <strong>Describir: </strong><button type="button" class="btn btn-danger btn-sm" id="describirdg"><i
                            class="fas fa-microphone"></button></i>
                    <button type="button" class="btn btn-primary btn-sm" id="stopdescri"><i
                            class="fas fa-microphone-slash"></button></i>
                    <button type="button" class="btn btn-success btn-sm" id="play44"><i
                            class="fas fa-play"></button></i>
                    <textarea class="form-control" name="des_diag" id="desgiag"></textarea>
                    <script type="text/javascript">
                    const describirdg = document.getElementById('describirdg');
                    const stopdescri = document.getElementById('stopdescri');
                    const desgiag = document.getElementById('desgiag');

                    const btn46 = document.getElementById('play44');

                    btn46.addEventListener('click', () => {
                        leerTexto(desgiag.value);
                    });

                    function leerTexto(desgiag) {
                        const speech = new SpeechSynthesisUtterance();
                        speech.text = desgiag;
                        speech.volume = 1;
                        speech.rate = 1;
                        speech.pitch = 0;
                        window.speechSynthesis.speak(speech);
                    }
                    let rdesdi = new webkitSpeechRecognition();
                    rdesdi.lang = "es-ES";
                    rdesdi.continuous = true;
                    rdesdi.interimResults = false;

                    rdesdi.onresult = (event) => {
                        const results = event.results;
                        const frase = results[results.length - 1][0].transcript;
                        desgiag.value += frase;
                    }

                    describirdg.addEventListener('click', () => {
                        rdesdi.start();
                    });

                    stopdescri.addEventListener('click', () => {
                        rdesdi.abort();
                    });
                    </script>
                </div>
            </div>

            <div class="form-group">
                <label for="exampleFormControlTextarea1"><strong>Diagnósticos previos:</strong></label>
                <div class="botones">
                    <button type="button" class="btn btn-danger btn-sm" id="diagpgra"><i class="fas fa-microphone">
                    </button></i>
                    <button type="button" class="btn btn-primary btn-sm" id="detenervios"><i
                            class="fas fa-microphone-slash"></button></i>
                    <button type="button" class="btn btn-success btn-sm" id="play45"><i
                            class="fas fa-play"></button></i>
                </div>
                <textarea class="form-control" id="txtostica" rows="3" name="diag_prev" required></textarea>
                <script type="text/javascript">
                const diagpgra = document.getElementById('diagpgra');
                const detenervios = document.getElementById('detenervios');
                const txtostica = document.getElementById('txtostica');

                const btn47 = document.getElementById('play45');

                btn47.addEventListener('click', () => {
                    leerTexto(txtostica.value);
                });

                function leerTexto(txtostica) {
                    const speech = new SpeechSynthesisUtterance();
                    speech.text = txtostica;
                    speech.volume = 1;
                    speech.rate = 1;
                    speech.pitch = 0;
                    window.speechSynthesis.speak(speech);
                }

                let fre = new webkitSpeechRecognition();
                fre.lang = "es-ES";
                fre.continuous = true;
                fre.interimResults = false;

                fre.onresult = (event) => {
                    const results = event.results;
                    const frase = results[results.length - 1][0].transcript;
                    txtostica.value += frase;
                }

                diagpgra.addEventListener('click', () => {
                    fre.start();
                });

                detenervios.addEventListener('click', () => {
                    fre.abort();
                });
                </script>
            </div>

            <div class="row">
                <div class=" col-sm-9">
                    <strong><button type="button" class="btn btn-success btn-sm" id="play46"><i
                                class="fas fa-play"></button></i> Guía de práctica clínica:</strong>

                    <div class="form-group">

                        <div class="form-group">
                            <select name="guia" class="form-control" data-live-search="true" id="mibuscador11"
                                onchange="ShowSelected();" style="width : 100%; heigth : 100%">
                                <option value="">Seleccionar guía clínica</option>
                                <?php
include "../../conexionbd.php";
$sql_diag="SELECT * FROM gpclinica ORDER by id_gpc";
$result_diag=$conexion->query($sql_diag);
while($row=$result_diag->fetch_assoc()){

echo "<option value='" . $row['gpc'] . "'>" . $row['cve_gpc'] . "- " .$row['gpc']."</option>"; 
}
 ?>
                            </select>
                        </div>
                        <script type="text/javascript">
                        const mibuscador11 = document.getElementById('mibuscador11');
                        const btn48 = document.getElementById('play46');

                        btn48.addEventListener('click', () => {
                            leerTexto(mibuscador11.value);
                        });

                        function leerTexto(mibuscador11) {
                            const speech = new SpeechSynthesisUtterance();
                            speech.text = mibuscador11;
                            speech.volume = 1;
                            speech.rate = 1;
                            speech.pitch = 0;
                            window.speechSynthesis.speak(speech);
                        }
                        </script>
                    </div>
                </div>
                <div class="col-sm-3">
                    <p></p>
                    <button type="button" class="btn btn bg-navy" data-toggle="modal"
                        data-target=".bd-example-modal-lg"><strong>Links de guía de práctica clínica</strong></button>

                    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
                        aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header content-center">
                                    <h4><b>Guía de práctica clínica</b></h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <input type="text" class="form-control pull-right" style="width:20%" id="search"
                                            placeholder="BUSCAR...">
                                    </div>
                                    <div class="table-responsive">

                                        <table class="table table-bordered table-striped" id="mytable">
                                            <thead class="thead bg-navy">
                                                <th scope="col">
                                                    <center>Clave</center>
                                                </th>
                                                <th scope="col">
                                                    <center>Guía & Link</center>
                                                </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
$sql_diag="SELECT * FROM gpclinica ORDER by id_gpc";
$result_diag=$conexion->query($sql_diag);
while($row=$result_diag->fetch_assoc()){            
    ?>
                                                <tr>
                                                    <td class="fondo"><strong><?php echo $row['cve_gpc'];?></strong>
                                                    </td>
                                                    <td class="fondo"><a href="<?php echo $row['link']?>"
                                                            target=”_blank”><strong><?php echo $row['gpc'];?></strong>
                                                    </td></a>
                                                </tr>
                                                <?php
                }
                ?>
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Regresar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 19px;">
                    <strong>
                        <center>PRONÓSTICOS</center>
                    </strong>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="hc_vid"><strong><button type="button" class="btn btn-success btn-sm"
                                        id="play47"><i class="fas fa-play"></button></i> Pronóstico para la
                                    vida:</strong></label>
                            <select name="hc_vid" id="hc_vid3" class="form-control" style="width : 100%; heigth : 100%">

                                <option value="">Seleccionar</option>
                                <option value="Bueno">Bueno</option>
                                <option value="Malo">Malo</option>
                                <option value="Reservado a evolución">Reservado a evolución</option>


                            </select>
                        </div>
                    </div>
                    <script type="text/javascript">
                    const hc_vid3 = document.getElementById('hc_vid3');
                    const btn49 = document.getElementById('play47');

                    btn49.addEventListener('click', () => {
                        leerTexto(hc_vid3.value);
                    });

                    function leerTexto(hc_vid3) {
                        const speech = new SpeechSynthesisUtterance();
                        speech.text = hc_vid3;
                        speech.volume = 1;
                        speech.rate = 1;
                        speech.pitch = 0;
                        window.speechSynthesis.speak(speech);
                    }
                    </script>
                    <div class="col">
                        <div class="form-group">
                            <label for="hc_def"><strong><button type="button" class="btn btn-success btn-sm"
                                        id="play48"><i class="fas fa-play"></button></i> Pronóstico para la
                                    función:</strong></label>
                            <select name="hc_def" id="hc_def5" class="form-control" style="width : 100%; heigth : 100%">
                                <option value="">Seleccionar</option>
                                <option value="Bueno">Bueno</option>
                                <option value="Malo">Malo</option>
                                <option value="Reservado a evolución">Reservado a evolución</option>

                            </select>
                        </div>
                    </div>

                </div>
            </div>
            <script type="text/javascript">
            const hc_def5 = document.getElementById('hc_def5');
            const btn50 = document.getElementById('play48');

            btn50.addEventListener('click', () => {
                leerTexto(hc_def5.value);
            });

            function leerTexto(hc_def5) {
                const speech = new SpeechSynthesisUtterance();
                speech.text = hc_def5;
                speech.volume = 1;
                speech.rate = 1;
                speech.pitch = 0;
                window.speechSynthesis.speak(speech);
            }
            </script>



            <div class="container">


                <center>
                    <hr>
                    <!-- <input type="submit" value="FIRMAR" class="btn btn-success"  id="btn_submit" onclick="javascript:document.getElementById('btn_submit').style.visibility = 'hidden';"> -->
                    <button type="submit" class="btn btn-primary">
                        <font size="3">Firmar</font>
                    </button>
                    <button type="button" class="btn btn-danger" onclick="history.back()">Cancelar</button>
                </center>
            </div><br>

    </form>
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
<script type="text/javascript">
$(document).ready(function() {
    $('#mibuscador').select2();
});
</script>
<script type="text/javascript">
enviando = false; //Obligaremos a entrar el if en el primer submit

function checkSubmit() {
    if (!enviando) {
        enviando = true;
        return true;
    } else {
        //Si llega hasta aca significa que pulsaron 2 veces el boton submit
        alert("El formulario ya se esta enviando");
        return false;
    }
}
</script>
<script type="text/javascript">
$(document).ready(function() {
    $('#mibuscador').select2();
});
jQuery(document).ready(function($) {
    $(document).ready(function() {
        $('.mibuscador3').select2();
    });
});
$(document).ready(function() {
    $('#mibuscador11').select2();
});
</script>

</body>

</html>