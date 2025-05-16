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
                <center>LENTE INTRAOCULAR (LIO)</center>
            </strong>
        </div>
        <form action="insertar_lente_intraocular.php" method="POST" onsubmit="return checkSubmit();">
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
                                    <input class="form-check-input" type="checkbox" name="lente_derecho"
                                        id="lente_derecho" value="1">
                                    <label class="form-check-label" for="lente_derecho">Lente Intraocular para Ojo
                                        Derecho</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="marca_derecho"><strong>Marca (LIO):</strong></label>
                                <input type="text" class="form-control" name="marca_derecho" id="marca_derecho"
                                    placeholder="Ej. Alcon">
                            </div>
                            <div class="form-group">
                                <label for="modelo_derecho"><strong>Modelo (LIO):</strong></label>
                                <input type="text" class="form-control" name="modelo_derecho" id="modelo_derecho"
                                    placeholder="Ej. AcrySof IQ">
                            </div>
                            <div class="form-group">
                                <label for="otros_derecho"><strong>Otros:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="otros_derecho_grabar"><i
                                            class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="otros_derecho_detener"><i
                                            class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_otros_derecho"><i
                                            class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" name="otros_derecho" id="otros_derecho" rows="4"
                                    placeholder="Ej. Lente monofocal recomendado"></textarea>
                                <script>
                                const otros_derecho_grabar = document.getElementById('otros_derecho_grabar');
                                const otros_derecho_detener = document.getElementById('otros_derecho_detener');
                                const otros_derecho = document.getElementById('otros_derecho');
                                const btn_otros_derecho = document.getElementById('play_otros_derecho');
                                btn_otros_derecho.addEventListener('click', () => {
                                    leerTexto(otros_derecho.value);
                                });
                                let recognition_otros_derecho = new webkitSpeechRecognition();
                                recognition_otros_derecho.lang = "es-ES";
                                recognition_otros_derecho.continuous = true;
                                recognition_otros_derecho.interimResults = false;
                                recognition_otros_derecho.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    otros_derecho.value += frase;
                                };
                                otros_derecho_grabar.addEventListener('click', () => {
                                    recognition_otros_derecho.start();
                                });
                                otros_derecho_detener.addEventListener('click', () => {
                                    recognition_otros_derecho.abort();
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
                                <label for="dioptrias_derecho"><strong>Dioptrias:</strong></label>
                                <input type="text" class="form-control" name="dioptrias_derecho" id="dioptrias_derecho"
                                    placeholder="Ej. 22.5 D">
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
                    <div id="collapseLeft" class="collapse" aria-labelledby="headingLeft" data-parent="#eyeAccordion">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="lente_izquierdo"
                                        id="lente_izquierdo" value="1">
                                    <label class="form-check-label" for="lente_izquierdo">Lente Intraocular para Ojo
                                        Izquierdo</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="marca_izquierdo"><strong>Marca (LIO):</strong></label>
                                <input type="text" class="form-control" name="marca_izquierdo" id="marca_izquierdo"
                                    placeholder="Ej. Alcon">
                            </div>
                            <div class="form-group">
                                <label for="modelo_izquierdo"><strong>Modelo (LIO):</strong></label>
                                <input type="text" class="form-control" name="modelo_izquierdo" id="modelo_izquierdo"
                                    placeholder="Ej. AcrySof IQ">
                            </div>
                            <div class="form-group">
                                <label for="otros_izquierdo"><strong>Otros:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="otros_izquierdo_grabar"><i
                                            class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="otros_izquierdo_detener"><i
                                            class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_otros_izquierdo"><i
                                            class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" name="otros_izquierdo" id="otros_izquierdo" rows="4"
                                    placeholder="Ej. Lente multifocal considerado"></textarea>
                                <script>
                                const otros_izquierdo_grabar = document.getElementById('otros_izquierdo_grabar');
                                const otros_izquierdo_detener = document.getElementById('otros_izquierdo_detener');
                                const otros_izquierdo = document.getElementById('otros_izquierdo');
                                const btn_otros_izquierdo = document.getElementById('play_otros_izquierdo');
                                btn_otros_izquierdo.addEventListener('click', () => {
                                    leerTexto(otros_izquierdo.value);
                                });
                                let recognition_otros_izquierdo = new webkitSpeechRecognition();
                                recognition_otros_izquierdo.lang = "es-ES";
                                recognition_otros_izquierdo.continuous = true;
                                recognition_otros_izquierdo.interimResults = false;
                                recognition_otros_izquierdo.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    otros_izquierdo.value += frase;
                                };
                                otros_izquierdo_grabar.addEventListener('click', () => {
                                    recognition_otros_izquierdo.start();
                                });
                                otros_izquierdo_detener.addEventListener('click', () => {
                                    recognition_otros_izquierdo.abort();
                                });
                                </script>
                            </div>
                            <div class="form-group">
                                <label for="dioptrias_izquierdo"><strong>Dioptrias:</strong></label>
                                <input type="text" class="form-control" name="dioptrias_izquierdo"
                                    id="dioptrias_izquierdo" placeholder="Ej. 21.0 D">
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