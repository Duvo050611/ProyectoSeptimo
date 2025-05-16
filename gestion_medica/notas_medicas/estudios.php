<?php
session_start();
include "../../conexionbd.php";
include("../header_medico.php");
$usuario = $_SESSION['login'];
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />

    <!-- Select2 CSS -->
    <link rel="stylesheet" type="text/css" href="css/select2.css">

    <!-- FontAwesome -->
    <link href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <!-- Bootstrap 4.5 CSS (usar sólo uno, aquí no incluiste el CSS pero lo ideal sería incluirlo) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">

    <!-- jQuery (usar solo una versión) -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <!-- Select2 JS -->
    <script src="js/select2.js"></script>

    <!-- Popper.js necesario para Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldLv/Pr4nhuBviF5jGqQK/5i2Q5iZ64dxBl+zOZ" crossorigin="anonymous">
    </script>

    <!-- Bootstrap 4.5 JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>

    <!-- Tus scripts adicionales -->
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/main.js"></script>

    <script>
    // Filtro de búsqueda en tabla
    $(document).ready(function() {
        $("#search").keyup(function() {
            var valor = $(this).val().toLowerCase();
            $("#mytable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(valor) > -1)
            });
        });
    });
    </script>

    <title>Estudios - Instituto de Enfermedades Oculares</title>
    <style>
    .modal-lg {
        max-width: 70% !important;
    }

    .botones {
        margin-bottom: 5px;
    }

    .thead {
        background-color: #2b2d7f;
        color: white;
        font-size: 22px;
        padding: 10px;
        text-align: center;
    }
    </style>
</head>


<body>
    <div class="container">
        <div class="row">
            <div class="col">

                <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 22px;"><strong>
                        <center>HISTORIA CLÍNICA </center>
                    </strong></div>

                <?php
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

    <br><br>
        <div class="container">
            <div class="thead">
                <strong>
                    <center>ESTUDIOS OCULARES</center>
                </strong>
            </div>
            <form action="insertar_estudios.php" method="POST" onsubmit="return checkSubmit();">
                <!-- General Fields -->
                <div class="form-group mt-3">
                    <label><strong>Valoración de Riesgo Quirúrgico (ASA):</strong></label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="riesgo_quirurgico" id="asa_i" value="ASA I"
                            required>
                        <label class="form-check-label" for="asa_i">ASA I</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="riesgo_quirurgico" id="asa_ii"
                            value="ASA II">
                        <label class="form-check-label" for="asa_ii">ASA II</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="riesgo_quirurgico" id="asa_iii"
                            value="ASA III">
                        <label class="form-check-label" for="asa_iii">ASA III</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="riesgo_quirurgico" id="asa_iv"
                            value="ASA IV">
                        <label class="form-check-label" for="asa_iv">ASA IV</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="riesgo_quirurgico" id="asa_v" value="ASA V">
                        <label class="form-check-label" for="asa_v">ASA V</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="riesgo_quirurgico" id="asa_vi"
                            value="ASA VI">
                        <label class="form-check-label" for="asa_vi">ASA VI</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="info_riesgo"><strong>Información Adicional:</strong></label>
                    <div class="botones">
                        <button type="button" class="btn btn-danger btn-sm" id="info_grabar"><i
                                class="fas fa-microphone"></i></button>
                        <button type="button" class="btn btn-primary btn-sm" id="info_detener"><i
                                class="fas fa-microphone-slash"></i></button>
                        <button type="button" class="btn btn-success btn-sm" id="play_info"><i
                                class="fas fa-play"></i></button>
                    </div>
                    <textarea class="form-control" name="info_riesgo" id="info_riesgo" rows="4"
                        placeholder="Ej. Paciente con hipertensión controlada"></textarea>
                    <script>
                    const info_grabar = document.getElementById('info_grabar');
                    const info_detener = document.getElementById('info_detener');
                    const info_riesgo = document.getElementById('info_riesgo');
                    const btn_info = document.getElementById('play_info');
                    btn_info.addEventListener('click', () => {
                        leerTexto(info_riesgo.value);
                    });
                    let recognition_info = new webkitSpeechRecognition();
                    recognition_info.lang = "es-ES";
                    recognition_info.continuous = true;
                    recognition_info.interimResults = false;
                    recognition_info.onresult = (event) => {
                        const results = event.results;
                        const frase = results[results.length - 1][0].transcript;
                        info_riesgo.value += frase;
                    };
                    info_grabar.addEventListener('click', () => {
                        recognition_info.start();
                    });
                    info_detener.addEventListener('click', () => {
                        recognition_info.abort();
                    });

                    function leerTexto(texto) {
                        const speech = new SpeechSynthesisUtterance();
                        speech.text = texto;
                        speech.volume = 1;
                        speech.rate = 1;
                        speech.pitch = 0;
                        window.speechSynthesis.speak(speech);
                    }
                    </script>
                </div>
                <div class="form-group">
                    <label for="analisis_sangre"><strong>Análisis de Sangre:</strong></label>
                    <div class="botones">
                        <button type="button" class="btn btn-danger btn-sm" id="as_grabar"><i
                                class="fas fa-microphone"></i></button>
                        <button type="button" class="btn btn-primary btn-sm" id="as_detener"><i
                                class="fas fa-microphone-slash"></i></button>
                        <button type="button" class="btn btn-success btn-sm" id="play_as"><i
                                class="fas fa-play"></i></button>
                    </div>
                    <textarea class="form-control" name="analisis_sangre" id="analisis_sangre" rows="4"
                        placeholder="Ej. Hemoglobina 14 g/dL, glucosa 90 mg/dL"></textarea>
                    <script>
                    const as_grabar = document.getElementById('as_grabar');
                    const as_detener = document.getElementById('as_detener');
                    const analisis_sangre = document.getElementById('analisis_sangre');
                    const btn_as = document.getElementById('play_as');
                    btn_as.addEventListener('click', () => {
                        leerTexto(analisis_sangre.value);
                    });
                    let recognition_as = new webkitSpeechRecognition();
                    recognition_as.lang = "es-ES";
                    recognition_as.continuous = true;
                    recognition_as.interimResults = false;
                    recognition_as.onresult = (event) => {
                        const results = event.results;
                        const frase = results[results.length - 1][0].transcript;
                        analisis_sangre.value += frase;
                    };
                    as_grabar.addEventListener('click', () => {
                        recognition_as.start();
                    });
                    as_detener.addEventListener('click', () => {
                        recognition_as.abort();
                    });
                    </script>
                </div>
                <div class="form-group">
                    <label for="cv"><strong>CV:</strong></label>
                    <div class="botones">
                        <button type="button" class="btn btn-danger btn-sm" id="cv_grabar"><i
                                class="fas fa-microphone"></i></button>
                        <button type="button" class="btn btn-primary btn-sm" id="cv_detener"><i
                                class="fas fa-microphone-slash"></i></button>
                        <button type="button" class="btn btn-success btn-sm" id="play_cv"><i
                                class="fas fa-play"></i></button>
                    </div>
                    <textarea class="form-control" name="cv" id="cv" rows="4"
                        placeholder="Ej. Historia cardiovascular o notas clínicas"></textarea>
                    <script>
                    const cv_grabar = document.getElementById('cv_grabar');
                    const cv_detener = document.getElementById('cv_detener');
                    const cv = document.getElementById('cv');
                    const btn_cv = document.getElementById('play_cv');
                    btn_cv.addEventListener('click', () => {
                        leerTexto(cv.value);
                    });
                    let recognition_cv = new webkitSpeechRecognition();
                    recognition_cv.lang = "es-ES";
                    recognition_cv.continuous = true;
                    recognition_cv.interimResults = false;
                    recognition_cv.onresult = (event) => {
                        const results = event.results;
                        const frase = results[results.length - 1][0].transcript;
                        cv.value += frase;
                    };
                    cv_grabar.addEventListener('click', () => {
                        recognition_cv.start();
                    });
                    cv_detener.addEventListener('click', () => {
                        recognition_cv.abort();
                    });
                    </script>
                </div>
                <div class="form-group">
                    <label for="ecografia"><strong>Ecografía:</strong></label>
                    <div class="botones">
                        <button type="button" class="btn btn-danger btn-sm" id="eco_grabar"><i
                                class="fas fa-microphone"></i></button>
                        <button type="button" class="btn btn-primary btn-sm" id="eco_detener"><i
                                class="fas fa-microphone-slash"></i></button>
                        <button type="button" class="btn btn-success btn-sm" id="play_eco"><i
                                class="fas fa-play"></i></button>
                    </div>
                    <textarea class="form-control" name="ecografia" id="ecografia" rows="4"
                        placeholder="Ej. Espesor retinal normal, sin desprendimiento"></textarea>
                    <script>
                    const eco_grabar = document.getElementById('eco_grabar');
                    const eco_detener = document.getElementById('eco_detener');
                    const ecografia = document.getElementById('ecografia');
                    const btn_eco = document.getElementById('play_eco');
                    btn_eco.addEventListener('click', () => {
                        leerTexto(ecografia.value);
                    });
                    let recognition_eco = new webkitSpeechRecognition();
                    recognition_eco.lang = "es-ES";
                    recognition_eco.continuous = true;
                    recognition_eco.interimResults = false;
                    recognition_eco.onresult = (event) => {
                        const results = event.results;
                        const frase = results[results.length - 1][0].transcript;
                        ecografia.value += frase;
                    };
                    eco_grabar.addEventListener('click', () => {
                        recognition_eco.start();
                    });
                    eco_detener.addEventListener('click', () => {
                        recognition_eco.abort();
                    });
                    </script>
                </div>
                <div class="form-group">
                    <label for="oct_hrt"><strong>OCT HRT:</strong></label>
                    <div class="botones">
                        <button type="button" class="btn btn-danger btn-sm" id="oct_grabar"><i
                                class="fas fa-microphone"></i></button>
                        <button type="button" class="btn btn-primary btn-sm" id="oct_detener"><i
                                class="fas fa-microphone-slash"></i></button>
                        <button type="button" class="btn btn-success btn-sm" id="play_oct"><i
                                class="fas fa-play"></i></button>
                    </div>
                    <textarea class="form-control" name="oct_hrt" id="oct_hrt" rows="4"
                        placeholder="Ej. Espesor de la capa de fibras nerviosas 90 µm"></textarea>
                    <script>
                    const oct_grabar = document.getElementById('oct_grabar');
                    const oct_detener = document.getElementById('oct_detener');
                    const oct_hrt = document.getElementById('oct_hrt');
                    const btn_oct = document.getElementById('play_oct');
                    btn_oct.addEventListener('click', () => {
                        leerTexto(oct_hrt.value);
                    });
                    let recognition_oct = new webkitSpeechRecognition();
                    recognition_oct.lang = "es-ES";
                    recognition_oct.continuous = true;
                    recognition_oct.interimResults = false;
                    recognition_oct.onresult = (event) => {
                        const results = event.results;
                        const frase = results[results.length - 1][0].transcript;
                        oct_hrt.value += frase;
                    };
                    oct_grabar.addEventListener('click', () => {
                        recognition_oct.start();
                    });
                    oct_detener.addEventListener('click', () => {
                        recognition_oct.abort();
                    });
                    </script>
                </div>
                <div class="form-group">
                    <label for="fag"><strong>FAG:</strong></label>
                    <div class="botones">
                        <button type="button" class="btn btn-danger btn-sm" id="fag_grabar"><i
                                class="fas fa-microphone"></i></button>
                        <button type="button" class="btn btn-primary btn-sm" id="fag_detener"><i
                                class="fas fa-microphone-slash"></i></button>
                        <button type="button" class="btn btn-success btn-sm" id="play_fag"><i
                                class="fas fa-play"></i></button>
                    </div>
                    <textarea class="form-control" name="fag" id="fag" rows="4"
                        placeholder="Ej. Presión intraocular 15 mmHg"></textarea>
                    <script>
                    const fag_grabar = document.getElementById('fag_grabar');
                    const fag_detener = document.getElementById('fag_detener');
                    const fag = document.getElementById('fag');
                    const btn_fag = document.getElementById('play_fag');
                    btn_fag.addEventListener('click', () => {
                        leerTexto(fag.value);
                    });
                    let recognition_fag = new webkitSpeechRecognition();
                    recognition_fag.lang = "es-ES";
                    recognition_fag.continuous = true;
                    recognition_fag.interimResults = false;
                    recognition_fag.onresult = (event) => {
                        const results = event.results;
                        const frase = results[results.length - 1][0].transcript;
                        fag.value += frase;
                    };
                    fag_grabar.addEventListener('click', () => {
                        recognition_fag.start();
                    });
                    fag_detener.addEventListener('click', () => {
                        recognition_fag.abort();
                    });
                    </script>
                </div>
                <div class="form-group">
                    <label for="ubm"><strong>UBM:</strong></label>
                    <div class="botones">
                        <button type="button" class="btn btn-danger btn-sm" id="ubm_grabar"><i
                                class="fas fa-microphone"></i></button>
                        <button type="button" class="btn btn-primary btn-sm" id="ubm_detener"><i
                                class="fas fa-microphone-slash"></i></button>
                        <button type="button" class="btn btn-success btn-sm" id="play_ubm"><i
                                class="fas fa-play"></i></button>
                    </div>
                    <textarea class="form-control" name="ubm" id="ubm" rows="4"
                        placeholder="Ej. Ángulo de drenaje abierto, sin anomalías"></textarea>
                    <script>
                    const ubm_grabar = document.getElementById('ubm_grabar');
                    const ubm_detener = document.getElementById('ubm_detener');
                    const ubm = document.getElementById('ubm');
                    const btn_ubm = document.getElementById('play_ubm');
                    btn_ubm.addEventListener('click', () => {
                        leerTexto(ubm.value);
                    });
                    let recognition_ubm = new webkitSpeechRecognition();
                    recognition_ubm.lang = "es-ES";
                    recognition_ubm.continuous = true;
                    recognition_ubm.interimResults = false;
                    recognition_ubm.onresult = (event) => {
                        const results = event.results;
                        const frase = results[results.length - 1][0].transcript;
                        ubm.value += frase;
                    };
                    ubm_grabar.addEventListener('click', () => {
                        recognition_ubm.start();
                    });
                    ubm_detener.addEventListener('click', () => {
                        recognition_ubm.abort();
                    });
                    </script>
                </div>
                <div class="form-group">
                    <label for="constante"><strong>Constante:</strong></label>
                    <input type="text" class="form-control" name="constante" id="constante" placeholder="Ej. 15 mmHg">
                </div>

                <!-- Eye-Specific Sections -->
                <div class="accordion mt-3" id="eyeAccordion">
                    <!-- Right Eye -->
                    <div class="card">
                        <div class="card-header" id="headingRight">
                            <h2 class="mb-0">
                                <button class="btn btn-link text-dark" type="button" data-toggle="collapse"
                                    data-target="#collapseRight" aria-expanded="true" aria-controls="collapseRight">
                                    Ojo Derecho
                                </button>
                            </h2>
                        </div>
                        <div id="collapseRight" class="collapse show" aria-labelledby="headingRight"
                            data-parent="#eyeAccordion">
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="estudio_derecho"
                                            id="estudio_derecho" value="1">
                                        <label class="form-check-label" for="estudio_derecho">Estudio Realizado en Ojo
                                            Derecho</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="hallazgos_derecho"><strong>Hallazgos Específicos:</strong></label>
                                    <div class="botones">
                                        <button type="button" class="btn btn-danger btn-sm" id="hall_derecho_grabar"><i
                                                class="fas fa-microphone"></i></button>
                                        <button type="button" class="btn btn-primary btn-sm"
                                            id="hall_derecho_detener"><i class="fas fa-microphone-slash"></i></button>
                                        <button type="button" class="btn btn-success btn-sm" id="play_hall_derecho"><i
                                                class="fas fa-play"></i></button>
                                    </div>
                                    <textarea class="form-control" name="hallazgos_derecho" id="hallazgos_derecho"
                                        rows="4" placeholder="Ej. Presión intraocular 18 mmHg"></textarea>
                                    <script>
                                    const hall_derecho_grabar = document.getElementById('hall_derecho_grabar');
                                    const hall_derecho_detener = document.getElementById('hall_derecho_detener');
                                    const hallazgos_derecho = document.getElementById('hallazgos_derecho');
                                    const btn_hall_derecho = document.getElementById('play_hall_derecho');
                                    btn_hall_derecho.addEventListener('click', () => {
                                        leerTexto(hallazgos_derecho.value);
                                    });
                                    let recognition_hall_derecho = new webkitSpeechRecognition();
                                    recognition_hall_derecho.lang = "es-ES";
                                    recognition_hall_derecho.continuous = true;
                                    recognition_hall_derecho.interimResults = false;
                                    recognition_hall_derecho.onresult = (event) => {
                                        const results = event.results;
                                        const frase = results[results.length - 1][0].transcript;
                                        hallazgos_derecho.value += frase;
                                    };
                                    hall_derecho_grabar.addEventListener('click', () => {
                                        recognition_hall_derecho.start();
                                    });
                                    hall_derecho_detener.addEventListener('click', () => {
                                        recognition_hall_derecho.abort();
                                    });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="constante_derecho"><strong>Constante:</strong></label>
                                    <input type="text" class="form-control" name="constante_derecho"
                                        id="constante_derecho" placeholder="Ej. 18 mmHg">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Left Eye -->
                    <div class="card">
                        <div class="card-header" id="headingLeft">
                            <h2 class="mb-0">
                                <button class="btn btn-link text-dark" type="button" data-toggle="collapse"
                                    data-target="#collapseLeft" aria-expanded="false" aria-controls="collapseLeft">
                                    Ojo Izquierdo
                                </button>
                            </h2>
                        </div>
                        <div id="collapseLeft" class="collapse" aria-labelledby="headingLeft"
                            data-parent="#eyeAccordion">
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="estudio_izquierdo"
                                            id="estudio_izquierdo" value="1">
                                        <label class="form-check-label" for="estudio_izquierdo">Estudio Realizado en Ojo
                                            Izquierdo</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="hallazgos_izquierdo"><strong>Hallazgos Específicos:</strong></label>
                                    <div class="botones">
                                        <button type="button" class="btn btn-danger btn-sm"
                                            id="hall_izquierdo_grabar"><i class="fas fa-microphone"></i></button>
                                        <button type="button" class="btn btn-primary btn-sm"
                                            id="hall_izquierdo_detener"><i class="fas fa-microphone-slash"></i></button>
                                        <button type="button" class="btn btn-success btn-sm" id="play_hall_izquierdo"><i
                                                class="fas fa-play"></i></button>
                                    </div>
                                    <textarea class="form-control" name="hallazgos_izquierdo" id="hallazgos_izquierdo"
                                        rows="4" placeholder="Ej. Presión intraocular 16 mmHg"></textarea>
                                    <script>
                                    const hall_izquierdo_grabar = document.getElementById('hall_izquierdo_grabar');
                                    const hall_izquierdo_detener = document.getElementById('hall_izquierdo_detener');
                                    const hallazgos_izquierdo = document.getElementById('hallazgos_izquierdo');
                                    const btn_hall_izquierdo = document.getElementById('play_hall_izquierdo');
                                    btn_hall_izquierdo.addEventListener('click', () => {
                                        leerTexto(hallazgos_izquierdo.value);
                                    });
                                    let recognition_hall_izquierdo = new webkitSpeechRecognition();
                                    recognition_hall_izquierdo.lang = "es-ES";
                                    recognition_hall_izquierdo.continuous = true;
                                    recognition_hall_izquierdo.interimResults = false;
                                    recognition_hall_izquierdo.onresult = (event) => {
                                        const results = event.results;
                                        const frase = results[results.length - 1][0].transcript;
                                        hallazgos_izquierdo.value += frase;
                                    };
                                    hall_izquierdo_grabar.addEventListener('click', () => {
                                        recognition_hall_izquierdo.start();
                                    });
                                    hall_izquierdo_detener.addEventListener('click', () => {
                                        recognition_hall_izquierdo.abort();
                                    });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="constante_izquierdo"><strong>Constante:</strong></label>
                                    <input type="text" class="form-control" name="constante_izquierdo"
                                        id="constante_izquierdo" placeholder="Ej. 16 mmHg">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <center class="mt-3">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-danger" onclick="history.back()">Cancelar</button>
                </center>
            </form>
        </div>
        <script>
        let enviando = false;

        function checkSubmit() {
            if (!enviando) {
                enviando = true;
                return true;
            } else {
                alert("El formulario ya se está enviando");
                return false;
            }
        }
        </script>
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
        document.oncontextmenu = function() {
            return false;
        }
        </script>



</body>

</html>