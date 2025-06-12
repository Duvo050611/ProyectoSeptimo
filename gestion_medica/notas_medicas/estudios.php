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
    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <link href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="js/select2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldLv/Pr4nhuBviF5jGqQK/5i2Q5iZ64dxBl+zOZ" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/main.js"></script>
    <script>
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
        <div class="mt-3">
            <?php if (isset($_SESSION['message']) && isset($_SESSION['message_type'])): ?>
            <div class="alert alert-<?php echo htmlspecialchars($_SESSION['message_type']); ?> alert-dismissible fade show"
                role="alert">
                <?php echo htmlspecialchars($_SESSION['message']); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php
        // Limpiar el mensaje
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        ?>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col">
                <div class="thead"><strong>
                        <center>DATOS DEL PACIENTE</center>
                    </strong></div>
                <?php
                    include "../../conexionbd.php";
                    if (isset($_SESSION['hospital'])) {
                        $id_atencion = $_SESSION['hospital'];
                        $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.folio, p.tel, p.fecnac, p.tip_san, di.fecha, di.area, di.alta_med, di.activo, p.sexo, di.alergias, p.ocup FROM paciente p, dat_ingreso di WHERE p.Id_exp=di.Id_exp AND di.id_atencion = ?";
                        $stmt = $conexion->prepare($sql_pac);
                        $stmt->bind_param("i", $id_atencion);
                        $stmt->execute();
                        $result_pac = $stmt->get_result();
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
                        $stmt->close();
                        $stmt = $conexion->prepare("SELECT area FROM dat_ingreso WHERE id_atencion = ?");
                        $stmt->bind_param("i", $id_atencion);
                        $stmt->execute();
                        $resultado1 = $stmt->get_result();

                        $area = "No asignada"; // Default value
                        if ($f1 = $resultado1->fetch_assoc()) {
                            $area = $f1['area'];
                        }
                        $stmt->close();

                        if ($activo === 'SI') {
                            $sql_now = "SELECT DATE_ADD(NOW(), INTERVAL 12 HOUR) as dat_now FROM dat_ingreso WHERE id_atencion = ?";
                            $stmt = $conexion->prepare($sql_now);
                            $stmt->bind_param("i", $id_atencion);
                            $stmt->execute();
                            $result_now = $stmt->get_result();
                            while ($row_now = $result_now->fetch_assoc()) {
                                $dat_now = $row_now['dat_now'];
                            }
                            $stmt->close();
                            $sql_est = "SELECT DATEDIFF( ?, fecha) as estancia FROM dat_ingreso WHERE id_atencion = ?";
                            $stmt = $conexion->prepare($sql_est);
                            $stmt->bind_param("si", $dat_now, $id_atencion);
                            $stmt->execute();
                            $result_est = $stmt->get_result();
                            while ($row_est = $result_est->fetch_assoc()) {
                                $estancia = $row_est['estancia'];
                            }
                            $stmt->close();
                        } else {
                            $sql_est = "SELECT DATEDIFF(fec_egreso, fecha) as estancia FROM dat_ingreso WHERE id_atencion = ?";
                            $stmt = $conexion->prepare($sql_est);
                            $stmt->bind_param("i", $id_atencion);
                            $stmt->execute();
                            $result_est = $stmt->get_result();
                            while ($row_est = $result_est->fetch_assoc()) {
                                $estancia = ($row_est['estancia'] == 0) ? 1 : $row_est['estancia'];
                            }
                            $stmt->close();
                        }

                        $d = "";
                        $sql_motd = "SELECT diagprob_i FROM dat_not_ingreso WHERE id_atencion = ? ORDER BY id_not_ingreso DESC LIMIT 1";
                        $stmt = $conexion->prepare($sql_motd);
                        $stmt->bind_param("i", $id_atencion);
                        $stmt->execute();
                        $result_motd = $stmt->get_result();
                        while ($row_motd = $result_motd->fetch_assoc()) {
                            $d = $row_motd['diagprob_i'];
                        }
                        $stmt->close();

                        if (!$d) {
                            $sql_motd = "SELECT diagprob_i FROM dat_nevol WHERE id_atencion = ? ORDER BY id_ne DESC LIMIT 1";
                            $stmt = $conexion->prepare($sql_motd);
                            $stmt->bind_param("i", $id_atencion);
                            $stmt->execute();
                            $result_motd = $stmt->get_result();
                            while ($row_motd = $result_motd->fetch_assoc()) {
                                $d = $row_motd['diagprob_i'];
                            }
                            $stmt->close();
                        }

                        $sql_mot = "SELECT motivo_atn FROM dat_ingreso WHERE id_atencion = ? ORDER BY motivo_atn ASC LIMIT 1";
                        $stmt = $conexion->prepare($sql_mot);
                        $stmt->bind_param("i", $id_atencion);
                        $stmt->execute();
                        $result_mot = $stmt->get_result();
                        while ($row_mot = $result_mot->fetch_assoc()) {
                            $m = $row_mot['motivo_atn'];
                        }
                        $stmt->close();

                        $sql_edo = "SELECT edo_salud FROM dat_ingreso WHERE id_atencion = ? ORDER BY edo_salud ASC LIMIT 1";
                        $stmt = $conexion->prepare($sql_edo);
                        $stmt->bind_param("i", $id_atencion);
                        $stmt->execute();
                        $result_edo = $stmt->get_result();
                        while ($row_edo = $result_edo->fetch_assoc()) {
                            $edo_salud = $row_edo['edo_salud'];
                        }
                        $stmt->close();

                        $sql_hab = "SELECT num_cama FROM cat_camas WHERE id_atencion = ?";
                        $stmt = $conexion->prepare($sql_hab);
                        $stmt->bind_param("i", $id_atencion);
                        $stmt->execute();
                        $result_hab = $stmt->get_result();
                        $num_cama = $result_hab->fetch_assoc()['num_cama'] ?? '';
                        $stmt->close();

                        $sql_hclinica = "SELECT peso, talla FROM dat_hclinica WHERE Id_exp = ? ORDER BY id_hc DESC LIMIT 1";
                        $stmt = $conexion->prepare($sql_hclinica);
                        $stmt->bind_param("s", $id_exp);
                        $stmt->execute();
                        $result_hclinica = $stmt->get_result();
                        $peso = 0;
                        $talla = 0;
                        while ($row_hclinica = $result_hclinica->fetch_assoc()) {
                            $peso = $row_hclinica['peso'] ?? 0;
                            $talla = $row_hclinica['talla'] ?? 0;
                        }
                        $stmt->close();
                    } else {
                        echo '<script type="text/javascript">window.location.href="../lista_pacientes/lista_pacientes.php";</script>';
                    }
                    ?>
                <div class="row">
                    <div class="col-sm-4">Expediente: <strong><?php echo $folio; ?></strong></div>
                    <div class="col-sm-4">Paciente:
                        <strong><?php echo $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac; ?></strong>
                    </div>
                    <div class="col-sm-4">Fecha de ingreso:
                        <strong><?php echo date_format(date_create($pac_fecing), "d/m/Y H:i:s"); ?></strong>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">Fecha de nacimiento:
                        <strong><?php echo date_format(date_create($pac_fecnac), "d/m/Y"); ?></strong>
                    </div>
                    <div class="col-sm-4">Edad: <strong><?php
                        $fecha_actual = date("Y-m-d");
                        $fecha_nac = $pac_fecnac;
                        $array_nacimiento = explode("-", $fecha_nac);
                        $array_actual = explode("-", $fecha_actual);
                        $anos = $array_actual[0] - $array_nacimiento[0];
                        $meses = $array_actual[1] - $array_nacimiento[1];
                        $dias = $array_actual[2] - $array_nacimiento[2];
                        if ($dias < 0) { --$meses; $dias += ($array_actual[1] == 3 && date("L", strtotime($fecha_actual)) ? 29 : 28); }
                        if ($meses < 0) { --$anos; $meses += 12; }
                        echo ($anos > 0 ? $anos . " años" : ($meses > 0 ? $meses . " meses" : $dias . " días"));
                    ?></strong></div>
                    <div class="col-sm-2">Habitación: <strong><?php echo $num_cama; ?></strong></div>
                </div>
                <div class="row">
                    <div class="col-sm-8">
                        <?php echo $d ? "Diagnóstico: <strong>$d</strong>" : "Motivo de atención: <strong>$m</strong>"; 
                        ?>
                    </div>
                    <div class="col-sm">Días estancia: <strong><?php echo $estancia; ?> días</strong></div>

                </div>
                <div class="row">
                    <div class="col-sm-4">Alergias: <strong><?php echo $alergias; ?></strong></div>
                    <div class="col-sm-4">Estado de salud: <strong><?php echo $edo_salud; ?></strong></div>
                    <div class="col-sm-3">Tipo de sangre: <strong><?php echo $pac_tip_sang; ?></strong></div>
                </div>
                <div class="row">
                    <div class="col-sm-4">Peso: <strong><?php echo $peso; ?></strong></div>
                    <div class="col-sm-4">Talla: <strong><?php echo $talla; ?></strong></div>
                    <div class="col-sm-4">Área: <strong><?php echo $area;?> </strong></div>
                </div>
            </div>
        </div>
    </div>
    <br><br>
    <div class="container">
        <div class="thead"><strong>
                <center>ESTUDIOS OCULARES</center>
            </strong></div>
        <form action="insertar_estudios.php" method="POST" onsubmit="return checkSubmit();">
            <input type="hidden" name="id_usua" value="<?php echo htmlspecialchars($_SESSION['login']['id_usua']); ?>">
            <!-- General Fields -->
            <div class="form-group mt-3">
                <label><strong>Valoración de Riesgo Quirúrgico (ASA):</strong></label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="riesgo_quirurgico" id="asa_i" value="ASA I"
                        required>
                    <label class="form-check-label" for="asa_i">ASA I</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="riesgo_quirurgico" id="asa_ii" value="ASA II">
                    <label class="form-check-label" for="asa_ii">ASA II</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="riesgo_quirurgico" id="asa_iii" value="ASA III">
                    <label class="form-check-label" for="asa_iii">ASA III</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="riesgo_quirurgico" id="asa_iv" value="ASA IV">
                    <label class="form-check-label" for="asa_iv">ASA IV</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="riesgo_quirurgico" id="asa_v" value="ASA V">
                    <label class="form-check-label" for="asa_v">ASA V</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="riesgo_quirurgico" id="asa_vi" value="ASA VI">
                    <label class="form-check-label" for="asa_vi">ASA VI</label>
                </div>
            </div>
            <div class="form-group">
                <label for="info_riesgo"><strong>Información Adicional:</strong></label>
                <div class="botones">
                    <button type="button" class="btn btn-danger btn-sm" id="info_grabar"><i
                            class="fas fa-microphone"></i></button>
                    <button type="button" class="btn btn-primary btn-sm" id="info_mute"><i
                            class="fas fa-volume-mute"></i></button>
                    <button type="button" class="btn btn-success btn-sm" id="play_info"><i
                            class="fas fa-play"></i></button>
                </div>
                <textarea class="form-control" name="info_riesgo" id="info_riesgo" rows="4"
                    placeholder="Ej. Paciente con hipertensión controlada"></textarea>
                <script>
                const info_grabar = document.getElementById('info_grabar');
                const info_mute = document.getElementById('info_mute');
                const info_riesgo = document.getElementById('info_riesgo');
                const btn_info = document.getElementById('play_info');
                let recognition_info = null;
                let isRecording_info = false;
                if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
                    recognition_info = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
                    recognition_info.lang = "es-ES";
                    recognition_info.continuous = true;
                    recognition_info.interimResults = false;
                    recognition_info.onresult = (event) => {
                        const results = event.results;
                        const frase = results[results.length - 1][0].transcript;
                        info_riesgo.value += frase + ' ';
                    };
                    recognition_info.onend = () => {
                        isRecording_info = false;
                        info_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                        info_grabar.disabled = false;
                    };
                    info_grabar.addEventListener('click', () => {
                        if (!isRecording_info) {
                            recognition_info.start();
                            isRecording_info = true;
                            info_grabar.innerHTML = '<i class="fas fa-microphone-alt"></i>';
                            info_grabar.disabled = false;
                        } else {
                            recognition_info.stop();
                            isRecording_info = false;
                            info_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                            info_grabar.disabled = false;
                        }
                    });
                } else {
                    info_grabar.disabled = true;
                    info_grabar.title = "Reconocimiento de voz no soportado en este navegador";
                }
                info_mute.addEventListener('click', () => {
                    window.speechSynthesis.cancel();
                });
                btn_info.addEventListener('click', () => {
                    if (info_riesgo.value.trim() !== '') {
                        const speech = new SpeechSynthesisUtterance(info_riesgo.value);
                        speech.lang = 'es-ES';
                        speech.volume = 1;
                        speech.rate = 1;
                        speech.pitch = 1;
                        window.speechSynthesis.speak(speech);
                    }
                });
                </script>
            </div>
            <div class="form-group">
                <label for="analisis_sangre"><strong>Análisis de Sangre:</strong></label>
                <div class="botones">
                    <button type="button" class="btn btn-danger btn-sm" id="as_grabar"><i
                            class="fas fa-microphone"></i></button>
                    <button type="button" class="btn btn-primary btn-sm" id="as_mute"><i
                            class="fas fa-volume-mute"></i></button>
                    <button type="button" class="btn btn-success btn-sm" id="play_as"><i
                            class="fas fa-play"></i></button>
                </div>
                <textarea class="form-control" name="analisis_sangre" id="analisis_sangre" rows="4"
                    placeholder="Ej. Hemoglobina 14 g/dL, glucosa 90 mg/dL"></textarea>
                <script>
                const as_grabar = document.getElementById('as_grabar');
                const as_mute = document.getElementById('as_mute');
                const analisis_sangre = document.getElementById('analisis_sangre');
                const btn_as = document.getElementById('play_as');
                let recognition_as = null;
                let isRecording_as = false;
                if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
                    recognition_as = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
                    recognition_as.lang = "es-ES";
                    recognition_as.continuous = true;
                    recognition_as.interimResults = false;
                    recognition_as.onresult = (event) => {
                        const results = event.results;
                        const frase = results[results.length - 1][0].transcript;
                        analisis_sangre.value += frase + ' ';
                    };
                    recognition_as.onend = () => {
                        isRecording_as = false;
                        as_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                        as_grabar.disabled = false;
                    };
                    as_grabar.addEventListener('click', () => {
                        if (!isRecording_as) {
                            recognition_as.start();
                            isRecording_as = true;
                            as_grabar.innerHTML = '<i class="fas fa-microphone-alt"></i>';
                            as_grabar.disabled = false;
                        } else {
                            recognition_as.stop();
                            isRecording_as = false;
                            as_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                            as_grabar.disabled = false;
                        }
                    });
                } else {
                    as_grabar.disabled = true;
                    as_grabar.title = "Reconocimiento de voz no soportado en este navegador";
                }
                as_mute.addEventListener('click', () => {
                    window.speechSynthesis.cancel();
                });
                btn_as.addEventListener('click', () => {
                    if (analisis_sangre.value.trim() !== '') {
                        const speech = new SpeechSynthesisUtterance(analisis_sangre.value);
                        speech.lang = 'es-ES';
                        speech.volume = 1;
                        speech.rate = 1;
                        speech.pitch = 1;
                        window.speechSynthesis.speak(speech);
                    }
                });
                </script>
            </div>
            <!-- CV -->
            <div class="form-group">
                <label><strong>CV:</strong></label>
                <div class="row">
                    <div class="col-md-6">
                        <label for="cv_od"><strong>Ojo Derecho (OD):</strong></label>
                        <div class="botones">
                            <button type="button" class="btn btn-danger btn-sm" id="cv_od_grabar"><i
                                    class="fas fa-microphone"></i></button>
                            <button type="button" class="btn btn-primary btn-sm" id="cv_od_mute"><i
                                    class="fas fa-volume-mute"></i></button>
                            <button type="button" class="btn btn-success btn-sm" id="play_cv_od"><i
                                    class="fas fa-play"></i></button>
                        </div>
                        <textarea class="form-control" name="cv_od" id="cv_od" rows="4"
                            placeholder="Ej. Historia cardiovascular ojo derecho"></textarea>
                        <script>
                        const cv_od_grabar = document.getElementById('cv_od_grabar');
                        const cv_od_mute = document.getElementById('cv_od_mute');
                        const cv_od = document.getElementById('cv_od');
                        const btn_cv_od = document.getElementById('play_cv_od');
                        let recognition_cv_od = null;
                        let isRecording_cv_od = false;
                        if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
                            recognition_cv_od = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
                            recognition_cv_od.lang = "es-ES";
                            recognition_cv_od.continuous = true;
                            recognition_cv_od.interimResults = false;
                            recognition_cv_od.onresult = (event) => {
                                const results = event.results;
                                const frase = results[results.length - 1][0].transcript;
                                cv_od.value += frase + ' ';
                            };
                            recognition_cv_od.onend = () => {
                                isRecording_cv_od = false;
                                cv_od_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                                cv_od_grabar.disabled = false;
                            };
                            cv_od_grabar.addEventListener('click', () => {
                                if (!isRecording_cv_od) {
                                    recognition_cv_od.start();
                                    isRecording_cv_od = true;
                                    cv_od_grabar.innerHTML = '<i class="fas fa-microphone-alt"></i>';
                                    cv_od_grabar.disabled = false;
                                } else {
                                    recognition_cv_od.stop();
                                    isRecording_cv_od = false;
                                    cv_od_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                                    cv_od_grabar.disabled = false;
                                }
                            });
                        } else {
                            cv_od_grabar.disabled = true;
                            cv_od_grabar.title = "Reconocimiento de voz no soportado en este navegador";
                        }
                        cv_od_mute.addEventListener('click', () => {
                            window.speechSynthesis.cancel();
                        });
                        btn_cv_od.addEventListener('click', () => {
                            if (cv_od.value.trim() !== '') {
                                const speech = new SpeechSynthesisUtterance(cv_od.value);
                                speech.lang = 'es-ES';
                                speech.volume = 1;
                                speech.rate = 1;
                                speech.pitch = 1;
                                window.speechSynthesis.speak(speech);
                            }
                        });
                        </script>
                    </div>
                    <div class="col-md-6">
                        <label for="cv_oi"><strong>Ojo Izquierdo (OI):</strong></label>
                        <div class="botones">
                            <button type="button" class="btn btn-danger btn-sm" id="cv_oi_grabar"><i
                                    class="fas fa-microphone"></i></button>
                            <button type="button" class="btn btn-primary btn-sm" id="cv_oi_mute"><i
                                    class="fas fa-volume-mute"></i></button>
                            <button type="button" class="btn btn-success btn-sm" id="play_cv_oi"><i
                                    class="fas fa-play"></i></button>
                        </div>
                        <textarea class="form-control" name="cv_oi" id="cv_oi" rows="4"
                            placeholder="Ej. Historia cardiovascular ojo izquierdo"></textarea>
                        <script>
                        const cv_oi_grabar = document.getElementById('cv_oi_grabar');
                        const cv_oi_mute = document.getElementById('cv_oi_mute');
                        const cv_oi = document.getElementById('cv_oi');
                        const btn_cv_oi = document.getElementById('play_cv_oi');
                        let recognition_cv_oi = null;
                        let isRecording_cv_oi = false;
                        if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
                            recognition_cv_oi = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
                            recognition_cv_oi.lang = "es-ES";
                            recognition_cv_oi.continuous = true;
                            recognition_cv_oi.interimResults = false;
                            recognition_cv_oi.onresult = (event) => {
                                const results = event.results;
                                const frase = results[results.length - 1][0].transcript;
                                cv_oi.value += frase + ' ';
                            };
                            recognition_cv_oi.onend = () => {
                                isRecording_cv_oi = false;
                                cv_oi_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                                cv_oi_grabar.disabled = false;
                            };
                            cv_oi_grabar.addEventListener('click', () => {
                                if (!isRecording_cv_oi) {
                                    recognition_cv_oi.start();
                                    isRecording_cv_oi = true;
                                    cv_oi_grabar.innerHTML = '<i class="fas fa-microphone-alt"></i>';
                                    cv_oi_grabar.disabled = false;
                                } else {
                                    recognition_cv_oi.stop();
                                    isRecording_cv_oi = false;
                                    cv_oi_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                                    cv_oi_grabar.disabled = false;
                                }
                            });
                        } else {
                            cv_oi_grabar.disabled = true;
                            cv_oi_grabar.title = "Reconocimiento de voz no soportado en este navegador";
                        }
                        cv_oi_mute.addEventListener('click', () => {
                            window.speechSynthesis.cancel();
                        });
                        btn_cv_oi.addEventListener('click', () => {
                            if (cv_oi.value.trim() !== '') {
                                const speech = new SpeechSynthesisUtterance(cv_oi.value);
                                speech.lang = 'es-ES';
                                speech.volume = 1;
                                speech.rate = 1;
                                speech.pitch = 1;
                                window.speechSynthesis.speak(speech);
                            }
                        });
                        </script>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label for="cv_general"><strong>General:</strong></label>
                    <div class="botones">
                        <button type="button" class="btn btn-danger btn-sm" id="cv_general_grabar"><i
                                class="fas fa-microphone"></i></button>
                        <button type="button" class="btn btn-primary btn-sm" id="cv_general_mute"><i
                                class="fas fa-volume-mute"></i></button>
                        <button type="button" class="btn btn-success btn-sm" id="play_cv_general"><i
                                class="fas fa-play"></i></button>
                    </div>
                    <textarea class="form-control" name="cv_general" id="cv_general" rows="4"
                        placeholder="Ej. Notas clínicas generales"></textarea>
                    <script>
                    const cv_general_grabar = document.getElementById('cv_general_grabar');
                    const cv_general_mute = document.getElementById('cv_general_mute');
                    const cv_general = document.getElementById('cv_general');
                    const btn_cv_general = document.getElementById('play_cv_general');
                    let recognition_cv_general = null;
                    let isRecording_cv_general = false;
                    if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
                        recognition_cv_general = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
                        recognition_cv_general.lang = "es-ES";
                        recognition_cv_general.continuous = true;
                        recognition_cv_general.interimResults = false;
                        recognition_cv_general.onresult = (event) => {
                            const results = event.results;
                            const frase = results[results.length - 1][0].transcript;
                            cv_general.value += frase + ' ';
                        };
                        recognition_cv_general.onend = () => {
                            isRecording_cv_general = false;
                            cv_general_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                            cv_general_grabar.disabled = false;
                        };
                        cv_general_grabar.addEventListener('click', () => {
                            if (!isRecording_cv_general) {
                                recognition_cv_general.start();
                                isRecording_cv_general = true;
                                cv_general_grabar.innerHTML = '<i class="fas fa-microphone-alt"></i>';
                                cv_general_grabar.disabled = false;
                            } else {
                                recognition_cv_general.stop();
                                isRecording_cv_general = false;
                                cv_general_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                                cv_general_grabar.disabled = false;
                            }
                        });
                    } else {
                        cv_general_grabar.disabled = true;
                        cv_general_grabar.title = "Reconocimiento de voz no soportado en este navegador";
                    }
                    cv_general_mute.addEventListener('click', () => {
                        window.speechSynthesis.cancel();
                    });
                    btn_cv_general.addEventListener('click', () => {
                        if (cv_general.value.trim() !== '') {
                            const speech = new SpeechSynthesisUtterance(cv_general.value);
                            speech.lang = 'es-ES';
                            speech.volume = 1;
                            speech.rate = 1;
                            speech.pitch = 1;
                            window.speechSynthesis.speak(speech);
                        }
                    });
                    </script>
                </div>
            </div>
            <!-- Ecografía -->
            <div class="form-group">
                <label><strong>Ecografía:</strong></label>
                <div class="row">
                    <div class="col-md-6">
                        <label for="ecografia_od"><strong>Ojo Derecho (OD):</strong></label>
                        <div class="botones">
                            <button type="button" class="btn btn-danger btn-sm" id="eco_od_grabar"><i
                                    class="fas fa-microphone"></i></button>
                            <button type="button" class="btn btn-primary btn-sm" id="eco_od_mute"><i
                                    class="fas fa-volume-mute"></i></button>
                            <button type="button" class="btn btn-success btn-sm" id="play_eco_od"><i
                                    class="fas fa-play"></i></button>
                        </div>
                        <textarea class="form-control" name="ecografia_od" id="ecografia_od" rows="4"
                            placeholder="Ej. Espesor retinal normal ojo derecho"></textarea>
                        <script>
                        const eco_od_grabar = document.getElementById('eco_od_grabar');
                        const eco_od_mute = document.getElementById('eco_od_mute');
                        const ecografia_od = document.getElementById('ecografia_od');
                        const btn_eco_od = document.getElementById('play_eco_od');
                        let recognition_eco_od = null;
                        let isRecording_eco_od = false;
                        if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
                            recognition_eco_od = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
                            recognition_eco_od.lang = "es-ES";
                            recognition_eco_od.continuous = true;
                            recognition_eco_od.interimResults = false;
                            recognition_eco_od.onresult = (event) => {
                                const results = event.results;
                                const frase = results[results.length - 1][0].transcript;
                                ecografia_od.value += frase + ' ';
                            };
                            recognition_eco_od.onend = () => {
                                isRecording_eco_od = false;
                                eco_od_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                                eco_od_grabar.disabled = false;
                            };
                            eco_od_grabar.addEventListener('click', () => {
                                if (!isRecording_eco_od) {
                                    recognition_eco_od.start();
                                    isRecording_eco_od = true;
                                    eco_od_grabar.innerHTML = '<i class="fas fa-microphone-alt"></i>';
                                    eco_od_grabar.disabled = false;
                                } else {
                                    recognition_eco_od.stop();
                                    isRecording_eco_od = false;
                                    eco_od_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                                    eco_od_grabar.disabled = false;
                                }
                            });
                        } else {
                            eco_od_grabar.disabled = true;
                            eco_od_grabar.title = "Reconocimiento de voz no soportado en este navegador";
                        }
                        eco_od_mute.addEventListener('click', () => {
                            window.speechSynthesis.cancel();
                        });
                        btn_eco_od.addEventListener('click', () => {
                            if (ecografia_od.value.trim() !== '') {
                                const speech = new SpeechSynthesisUtterance(ecografia_od.value);
                                speech.lang = 'es-ES';
                                speech.volume = 1;
                                speech.rate = 1;
                                speech.pitch = 1;
                                window.speechSynthesis.speak(speech);
                            }
                        });
                        </script>
                    </div>
                    <div class="col-md-6">
                        <label for="ecografia_oi"><strong>Ojo Izquierdo (OI):</strong></label>
                        <div class="botones">
                            <button type="button" class="btn btn-danger btn-sm" id="eco_oi_grabar"><i
                                    class="fas fa-microphone"></i></button>
                            <button type="button" class="btn btn-primary btn-sm" id="eco_oi_mute"><i
                                    class="fas fa-volume-mute"></i></button>
                            <button type="button" class="btn btn-success btn-sm" id="play_eco_oi"><i
                                    class="fas fa-play"></i></button>
                        </div>
                        <textarea class="form-control" name="ecografia_oi" id="ecografia_oi" rows="4"
                            placeholder="Ej. Espesor retinal normal ojo izquierdo"></textarea>
                        <script>
                        const eco_oi_grabar = document.getElementById('eco_oi_grabar');
                        const eco_oi_mute = document.getElementById('eco_oi_mute');
                        const ecografia_oi = document.getElementById('ecografia_oi');
                        const btn_eco_oi = document.getElementById('play_eco_oi');
                        let recognition_eco_oi = null;
                        let isRecording_eco_oi = false;
                        if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
                            recognition_eco_oi = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
                            recognition_eco_oi.lang = "es-ES";
                            recognition_eco_oi.continuous = true;
                            recognition_eco_oi.interimResults = false;
                            recognition_eco_oi.onresult = (event) => {
                                const results = event.results;
                                const frase = results[results.length - 1][0].transcript;
                                ecografia_oi.value += frase + ' ';
                            };
                            recognition_eco_oi.onend = () => {
                                isRecording_eco_oi = false;
                                eco_oi_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                                eco_oi_grabar.disabled = false;
                            };
                            eco_oi_grabar.addEventListener('click', () => {
                                if (!isRecording_eco_oi) {
                                    recognition_eco_oi.start();
                                    isRecording_eco_oi = true;
                                    eco_oi_grabar.innerHTML = '<i class="fas fa-microphone-alt"></i>';
                                    eco_oi_grabar.disabled = false;
                                } else {
                                    recognition_eco_oi.stop();
                                    isRecording_eco_oi = false;
                                    eco_oi_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                                    eco_oi_grabar.disabled = false;
                                }
                            });
                        } else {
                            eco_oi_grabar.disabled = true;
                            eco_oi_grabar.title = "Reconocimiento de voz no soportado en este navegador";
                        }
                        eco_oi_mute.addEventListener('click', () => {
                            window.speechSynthesis.cancel();
                        });
                        btn_eco_oi.addEventListener('click', () => {
                            if (ecografia_oi.value.trim() !== '') {
                                const speech = new SpeechSynthesisUtterance(ecografia_oi.value);
                                speech.lang = 'es-ES';
                                speech.volume = 1;
                                speech.rate = 1;
                                speech.pitch = 1;
                                window.speechSynthesis.speak(speech);
                            }
                        });
                        </script>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label for="ecografia_general"><strong>General:</strong></label>
                    <div class="botones">
                        <button type="button" class="btn btn-danger btn-sm" id="eco_general_grabar"><i
                                class="fas fa-microphone"></i></button>
                        <button type="button" class="btn btn-primary btn-sm" id="eco_general_mute"><i
                                class="fas fa-volume-mute"></i></button>
                        <button type="button" class="btn btn-success btn-sm" id="play_eco_general"><i
                                class="fas fa-play"></i></button>
                    </div>
                    <textarea class="form-control" name="ecografia_general" id="ecografia_general" rows="4"
                        placeholder="Ej. Notas generales de ecografía"></textarea>
                    <script>
                    const eco_general_grabar = document.getElementById('eco_general_grabar');
                    const eco_general_mute = document.getElementById('eco_general_mute');
                    const ecografia_general = document.getElementById('ecografia_general');
                    const btn_eco_general = document.getElementById('play_eco_general');
                    let recognition_eco_general = null;
                    let isRecording_eco_general = false;
                    if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
                        recognition_eco_general = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
                        recognition_eco_general.lang = "es-ES";
                        recognition_eco_general.continuous = true;
                        recognition_eco_general.interimResults = false;
                        recognition_eco_general.onresult = (event) => {
                            const results = event.results;
                            const frase = results[results.length - 1][0].transcript;
                            ecografia_general.value += frase + ' ';
                        };
                        recognition_eco_general.onend = () => {
                            isRecording_eco_general = false;
                            eco_general_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                            eco_general_grabar.disabled = false;
                        };
                        eco_general_grabar.addEventListener('click', () => {
                            if (!isRecording_eco_general) {
                                recognition_eco_general.start();
                                isRecording_eco_general = true;
                                eco_general_grabar.innerHTML = '<i class="fas fa-microphone-alt"></i>';
                                eco_general_grabar.disabled = false;
                            } else {
                                recognition_eco_general.stop();
                                isRecording_eco_general = false;
                                eco_general_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                                eco_general_grabar.disabled = false;
                            }
                        });
                    } else {
                        eco_general_grabar.disabled = true;
                        eco_general_grabar.title = "Reconocimiento de voz no soportado en este navegador";
                    }
                    eco_general_mute.addEventListener('click', () => {
                        window.speechSynthesis.cancel();
                    });
                    btn_eco_general.addEventListener('click', () => {
                        if (ecografia_general.value.trim() !== '') {
                            const speech = new SpeechSynthesisUtterance(ecografia_general.value);
                            speech.lang = 'es-ES';
                            speech.volume = 1;
                            speech.rate = 1;
                            speech.pitch = 1;
                            window.speechSynthesis.speak(speech);
                        }
                    });
                    </script>
                </div>
            </div>
            <!-- OCT HRT -->
            <div class="form-group">
                <label><strong>OCT HRT:</strong></label>
                <div class="row">
                    <div class="col-md-6">
                        <label for="oct_hrt_od"><strong>Ojo Derecho (OD):</strong></label>
                        <div class="botones">
                            <button type="button" class="btn btn-danger btn-sm" id="oct_od_grabar"><i
                                    class="fas fa-microphone"></i></button>
                            <button type="button" class="btn btn-primary btn-sm" id="oct_od_mute"><i
                                    class="fas fa-volume-mute"></i></button>
                            <button type="button" class="btn btn-success btn-sm" id="play_oct_od"><i
                                    class="fas fa-play"></i></button>
                        </div>
                        <textarea class="form-control" name="oct_hrt_od" id="oct_hrt_od" rows="4"
                            placeholder="Ej. Espesor de fibras nerviosas 90 µm ojo derecho"></textarea>
                        <script>
                        const oct_od_grabar = document.getElementById('oct_od_grabar');
                        const oct_od_mute = document.getElementById('oct_od_mute');
                        const oct_hrt_od = document.getElementById('oct_hrt_od');
                        const btn_oct_od = document.getElementById('play_oct_od');
                        let recognition_oct_od = null;
                        let isRecording_oct_od = false;
                        if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
                            recognition_oct_od = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
                            recognition_oct_od.lang = "es-ES";
                            recognition_oct_od.continuous = true;
                            recognition_oct_od.interimResults = false;
                            recognition_oct_od.onresult = (event) => {
                                const results = event.results;
                                const frase = results[results.length - 1][0].transcript;
                                oct_hrt_od.value += frase + ' ';
                            };
                            recognition_oct_od.onend = () => {
                                isRecording_oct_od = false;
                                oct_od_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                                oct_od_grabar.disabled = false;
                            };
                            oct_od_grabar.addEventListener('click', () => {
                                if (!isRecording_oct_od) {
                                    recognition_oct_od.start();
                                    isRecording_oct_od = true;
                                    oct_od_grabar.innerHTML = '<i class="fas fa-microphone-alt"></i>';
                                    oct_od_grabar.disabled = false;
                                } else {
                                    recognition_oct_od.stop();
                                    isRecording_oct_od = false;
                                    oct_od_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                                    oct_od_grabar.disabled = false;
                                }
                            });
                        } else {
                            oct_od_grabar.disabled = true;
                            oct_od_grabar.title = "Reconocimiento de voz no soportado en este navegador";
                        }
                        oct_od_mute.addEventListener('click', () => {
                            window.speechSynthesis.cancel();
                        });
                        btn_oct_od.addEventListener('click', () => {
                            if (oct_hrt_od.value.trim() !== '') {
                                const speech = new SpeechSynthesisUtterance(oct_hrt_od.value);
                                speech.lang = 'es-ES';
                                speech.volume = 1;
                                speech.rate = 1;
                                speech.pitch = 1;
                                window.speechSynthesis.speak(speech);
                            }
                        });
                        </script>
                    </div>
                    <div class="col-md-6">
                        <label for="oct_hrt_oi"><strong>Ojo Izquierdo (OI):</strong></label>
                        <div class="botones">
                            <button type="button" class="btn btn-danger btn-sm" id="oct_oi_grabar"><i
                                    class="fas fa-microphone"></i></button>
                            <button type="button" class="btn btn-primary btn-sm" id="oct_oi_mute"><i
                                    class="fas fa-volume-mute"></i></button>
                            <button type="button" class="btn btn-success btn-sm" id="play_oct_oi"><i
                                    class="fas fa-play"></i></button>
                        </div>
                        <textarea class="form-control" name="oct_hrt_oi" id="oct_hrt_oi" rows="4"
                            placeholder="Ej. Espesor de fibras nerviosas 92 µm ojo izquierdo"></textarea>
                        <script>
                        const oct_oi_grabar = document.getElementById('oct_oi_grabar');
                        const oct_oi_mute = document.getElementById('oct_oi_mute');
                        const oct_hrt_oi = document.getElementById('oct_hrt_oi');
                        const btn_oct_oi = document.getElementById('play_oct_oi');
                        let recognition_oct_oi = null;
                        let isRecording_oct_oi = false;
                        if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
                            recognition_oct_oi = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
                            recognition_oct_oi.lang = "es-ES";
                            recognition_oct_oi.continuous = true;
                            recognition_oct_oi.interimResults = false;
                            recognition_oct_oi.onresult = (event) => {
                                const results = event.results;
                                const frase = results[results.length - 1][0].transcript;
                                oct_hrt_oi.value += frase + ' ';
                            };
                            recognition_oct_oi.onend = () => {
                                isRecording_oct_oi = false;
                                oct_oi_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                                oct_oi_grabar.disabled = false;
                            };
                            oct_oi_grabar.addEventListener('click', () => {
                                if (!isRecording_oct_oi) {
                                    recognition_oct_oi.start();
                                    isRecording_oct_oi = true;
                                    oct_oi_grabar.innerHTML = '<i class="fas fa-microphone-alt"></i>';
                                    oct_oi_grabar.disabled = false;
                                } else {
                                    recognition_oct_oi.stop();
                                    isRecording_oct_oi = false;
                                    oct_oi_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                                    oct_oi_grabar.disabled = false;
                                }
                            });
                        } else {
                            oct_oi_grabar.disabled = true;
                            oct_oi_grabar.title = "Reconocimiento de voz no soportado en este navegador";
                        }
                        oct_oi_mute.addEventListener('click', () => {
                            window.speechSynthesis.cancel();
                        });
                        btn_oct_oi.addEventListener('click', () => {
                            if (oct_hrt_oi.value.trim() !== '') {
                                const speech = new SpeechSynthesisUtterance(oct_hrt_oi.value);
                                speech.lang = 'es-ES';
                                speech.volume = 1;
                                speech.rate = 1;
                                speech.pitch = 1;
                                window.speechSynthesis.speak(speech);
                            }
                        });
                        </script>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label for="oct_hrt_general"><strong>General:</strong></label>
                    <div class="botones">
                        <button type="button" class="btn btn-danger btn-sm" id="oct_general_grabar"><i
                                class="fas fa-microphone"></i></button>
                        <button type="button" class="btn btn-primary btn-sm" id="oct_general_mute"><i
                                class="fas fa-volume-mute"></i></button>
                        <button type="button" class="btn btn-success btn-sm" id="play_oct_general"><i
                                class="fas fa-play"></i></button>
                    </div>
                    <textarea class="form-control" name="oct_hrt_general" id="oct_hrt_general" rows="4"
                        placeholder="Ej. Notas generales de OCT HRT"></textarea>
                    <script>
                    const oct_general_grabar = document.getElementById('oct_general_grabar');
                    const oct_general_mute = document.getElementById('oct_general_mute');
                    const oct_hrt_general = document.getElementById('oct_hrt_general');
                    const btn_oct_general = document.getElementById('play_oct_general');
                    let recognition_oct_general = null;
                    let isRecording_oct_general = false;
                    if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
                        recognition_oct_general = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
                        recognition_oct_general.lang = "es-ES";
                        recognition_oct_general.continuous = true;
                        recognition_oct_general.interimResults = false;
                        recognition_oct_general.onresult = (event) => {
                            const results = event.results;
                            const frase = results[results.length - 1][0].transcript;
                            oct_hrt_general.value += frase + ' ';
                        };
                        recognition_oct_general.onend = () => {
                            isRecording_oct_general = false;
                            oct_general_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                            oct_general_grabar.disabled = false;
                        };
                        oct_general_grabar.addEventListener('click', () => {
                            if (!isRecording_oct_general) {
                                recognition_oct_general.start();
                                isRecording_oct_general = true;
                                oct_general_grabar.innerHTML = '<i class="fas fa-microphone-alt"></i>';
                                oct_general_grabar.disabled = false;
                            } else {
                                recognition_oct_general.stop();
                                isRecording_oct_general = false;
                                oct_general_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                                oct_general_grabar.disabled = false;
                            }
                        });
                    } else {
                        oct_general_grabar.disabled = true;
                        oct_general_grabar.title = "Reconocimiento de voz no soportado en este navegador";
                    }
                    oct_general_mute.addEventListener('click', () => {
                        window.speechSynthesis.cancel();
                    });
                    btn_oct_general.addEventListener('click', () => {
                        if (oct_hrt_general.value.trim() !== '') {
                            const speech = new SpeechSynthesisUtterance(oct_hrt_general.value);
                            speech.lang = 'es-ES';
                            speech.volume = 1;
                            speech.rate = 1;
                            speech.pitch = 1;
                            window.speechSynthesis.speak(speech);
                        }
                    });
                    </script>
                </div>
            </div>
            <!-- FAG -->
            <div class="form-group">
                <label><strong>FAG:</strong></label>
                <div class="row">
                    <div class="col-md-6">
                        <label for="fag_od"><strong>Ojo Derecho (OD):</strong></label>
                        <div class="botones">
                            <button type="button" class="btn btn-danger btn-sm" id="fag_od_grabar"><i
                                    class="fas fa-microphone"></i></button>
                            <button type="button" class="btn btn-primary btn-sm" id="fag_od_mute"><i
                                    class="fas fa-volume-mute"></i></button>
                            <button type="button" class="btn btn-success btn-sm" id="play_fag_od"><i
                                    class="fas fa-play"></i></button>
                        </div>
                        <textarea class="form-control" name="fag_od" id="fag_od" rows="4"
                            placeholder="Ej. Presión intraocular 15 mmHg ojo derecho"></textarea>
                        <script>
                        const fag_od_grabar = document.getElementById('fag_od_grabar');
                        const fag_od_mute = document.getElementById('fag_od_mute');
                        const fag_od = document.getElementById('fag_od');
                        const btn_fag_od = document.getElementById('play_fag_od');
                        let recognition_fag_od = null;
                        let isRecording_fag_od = false;
                        if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
                            recognition_fag_od = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
                            recognition_fag_od.lang = "es-ES";
                            recognition_fag_od.continuous = true;
                            recognition_fag_od.interimResults = false;
                            recognition_fag_od.onresult = (event) => {
                                const results = event.results;
                                const frase = results[results.length - 1][0].transcript;
                                fag_od.value += frase + ' ';
                            };
                            recognition_fag_od.onend = () => {
                                isRecording_fag_od = false;
                                fag_od_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                                fag_od_grabar.disabled = false;
                            };
                            fag_od_grabar.addEventListener('click', () => {
                                if (!isRecording_fag_od) {
                                    recognition_fag_od.start();
                                    isRecording_fag_od = true;
                                    fag_od_grabar.innerHTML = '<i class="fas fa-microphone-alt"></i>';
                                    fag_od_grabar.disabled = false;
                                } else {
                                    recognition_fag_od.stop();
                                    isRecording_fag_od = false;
                                    fag_od_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                                    fag_od_grabar.disabled = false;
                                }
                            });
                        } else {
                            fag_od_grabar.disabled = true;
                            fag_od_grabar.title = "Reconocimiento de voz no soportado en este navegador";
                        }
                        fag_od_mute.addEventListener('click', () => {
                            window.speechSynthesis.cancel();
                        });
                        btn_fag_od.addEventListener('click', () => {
                            if (fag_od.value.trim() !== '') {
                                const speech = new SpeechSynthesisUtterance(fag_od.value);
                                speech.lang = 'es-ES';
                                speech.volume = 1;
                                speech.rate = 1;
                                speech.pitch = 1;
                                window.speechSynthesis.speak(speech);
                            }
                        });
                        </script>
                    </div>
                    <div class="col-md-6">
                        <label for="fag_oi"><strong>Ojo Izquierdo (OI):</strong></label>
                        <div class="botones">
                            <button type="button" class="btn btn-danger btn-sm" id="fag_oi_grabar"><i
                                    class="fas fa-microphone"></i></button>
                            <button type="button" class="btn btn-primary btn-sm" id="fag_oi_mute"><i
                                    class="fas fa-volume-mute"></i></button>
                            <button type="button" class="btn btn-success btn-sm" id="play_fag_oi"><i
                                    class="fas fa-play"></i></button>
                        </div>
                        <textarea class="form-control" name="fag_oi" id="fag_oi" rows="4"
                            placeholder="Ej. Presión intraocular 14 mmHg ojo izquierdo"></textarea>
                        <script>
                        const fag_oi_grabar = document.getElementById('fag_oi_grabar');
                        const fag_oi_mute = document.getElementById('fag_oi_mute');
                        const fag_oi = document.getElementById('fag_oi');
                        const btn_fag_oi = document.getElementById('play_fag_oi');
                        let recognition_fag_oi = null;
                        let isRecording_fag_oi = false;
                        if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
                            recognition_fag_oi = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
                            recognition_fag_oi.lang = "es-ES";
                            recognition_fag_oi.continuous = true;
                            recognition_fag_oi.interimResults = false;
                            recognition_fag_oi.onresult = (event) => {
                                const results = event.results;
                                const frase = results[results.length - 1][0].transcript;
                                fag_oi.value += frase + ' ';
                            };
                            recognition_fag_oi.onend = () => {
                                isRecording_fag_oi = false;
                                fag_oi_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                                fag_oi_grabar.disabled = false;
                            };
                            fag_oi_grabar.addEventListener('click', () => {
                                if (!isRecording_fag_oi) {
                                    recognition_fag_oi.start();
                                    isRecording_fag_oi = true;
                                    fag_oi_grabar.innerHTML = '<i class="fas fa-microphone-alt"></i>';
                                    fag_oi_grabar.disabled = false;
                                } else {
                                    recognition_fag_oi.stop();
                                    isRecording_fag_oi = false;
                                    fag_oi_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                                    fag_oi_grabar.disabled = false;
                                }
                            });
                        } else {
                            fag_oi_grabar.disabled = true;
                            fag_oi_grabar.title = "Reconocimiento de voz no soportado en este navegador";
                        }
                        fag_oi_mute.addEventListener('click', () => {
                            window.speechSynthesis.cancel();
                        });
                        btn_fag_oi.addEventListener('click', () => {
                            if (fag_oi.value.trim() !== '') {
                                const speech = new SpeechSynthesisUtterance(fag_oi.value);
                                speech.lang = 'es-ES';
                                speech.volume = 1;
                                speech.rate = 1;
                                speech.pitch = 1;
                                window.speechSynthesis.speak(speech);
                            }
                        });
                        </script>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label for="fag_general"><strong>General:</strong></label>
                    <div class="botones">
                        <button type="button" class="btn btn-danger btn-sm" id="fag_general_grabar"><i
                                class="fas fa-microphone"></i></button>
                        <button type="button" class="btn btn-primary btn-sm" id="fag_general_mute"><i
                                class="fas fa-volume-mute"></i></button>
                        <button type="button" class="btn btn-success btn-sm" id="play_fag_general"><i
                                class="fas fa-play"></i></button>
                    </div>
                    <textarea class="form-control" name="fag_general" id="fag_general" rows="4"
                        placeholder="Ej. Notas generales de FAG"></textarea>
                    <script>
                    const fag_general_grabar = document.getElementById('fag_general_grabar');
                    const fag_general_mute = document.getElementById('fag_general_mute');
                    const fag_general = document.getElementById('fag_general');
                    const btn_fag_general = document.getElementById('play_fag_general');
                    let recognition_fag_general = null;
                    let isRecording_fag_general = false;
                    if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
                        recognition_fag_general = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
                        recognition_fag_general.lang = "es-ES";
                        recognition_fag_general.continuous = true;
                        recognition_fag_general.interimResults = false;
                        recognition_fag_general.onresult = (event) => {
                            const results = event.results;
                            const frase = results[results.length - 1][0].transcript;
                            fag_general.value += frase + ' ';
                        };
                        recognition_fag_general.onend = () => {
                            isRecording_fag_general = false;
                            fag_general_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                            fag_general_grabar.disabled = false;
                        };
                        fag_general_grabar.addEventListener('click', () => {
                            if (!isRecording_fag_general) {
                                recognition_fag_general.start();
                                isRecording_fag_general = true;
                                fag_general_grabar.innerHTML = '<i class="fas fa-microphone-alt"></i>';
                                fag_general_grabar.disabled = false;
                            } else {
                                recognition_fag_general.stop();
                                isRecording_fag_general = false;
                                fag_general_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                                fag_general_grabar.disabled = false;
                            }
                        });
                    } else {
                        fag_general_grabar.disabled = true;
                        fag_general_grabar.title = "Reconocimiento de voz no soportado en este navegador";
                    }
                    fag_general_mute.addEventListener('click', () => {
                        window.speechSynthesis.cancel();
                    });
                    btn_fag_general.addEventListener('click', () => {
                        if (fag_general.value.trim() !== '') {
                            const speech = new SpeechSynthesisUtterance(fag_general.value);
                            speech.lang = 'es-ES';
                            speech.volume = 1;
                            speech.rate = 1;
                            speech.pitch = 1;
                            window.speechSynthesis.speak(speech);
                        }
                    });
                    </script>
                </div>
            </div>
            <!-- UBM -->
            <div class="form-group">
                <label><strong>UBM:</strong></label>
                <div class="row">
                    <div class="col-md-6">
                        <label for="ubm_od"><strong>Ojo Derecho (OD):</strong></label>
                        <div class="botones">
                            <button type="button" class="btn btn-danger btn-sm" id="ubm_od_grabar"><i
                                    class="fas fa-microphone"></i></button>
                            <button type="button" class="btn btn-primary btn-sm" id="ubm_od_mute"><i
                                    class="fas fa-volume-mute"></i></button>
                            <button type="button" class="btn btn-success btn-sm" id="play_ubm_od"><i
                                    class="fas fa-play"></i></button>
                        </div>
                        <textarea class="form-control" name="ubm_od" id="ubm_od" rows="4"
                            placeholder="Ej. Ángulo de drenaje abierto ojo derecho"></textarea>
                        <script>
                        const ubm_od_grabar = document.getElementById('ubm_od_grabar');
                        const ubm_od_mute = document.getElementById('ubm_od_mute');
                        const ubm_od = document.getElementById('ubm_od');
                        const btn_ubm_od = document.getElementById('play_ubm_od');
                        let recognition_ubm_od = null;
                        let isRecording_ubm_od = false;
                        if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
                            recognition_ubm_od = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
                            recognition_ubm_od.lang = "es-ES";
                            recognition_ubm_od.continuous = true;
                            recognition_ubm_od.interimResults = false;
                            recognition_ubm_od.onresult = (event) => {
                                const results = event.results;
                                const frase = results[results.length - 1][0].transcript;
                                ubm_od.value += frase + ' ';
                            };
                            recognition_ubm_od.onend = () => {
                                isRecording_ubm_od = false;
                                ubm_od_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                                ubm_od_grabar.disabled = false;
                            };
                            ubm_od_grabar.addEventListener('click', () => {
                                if (!isRecording_ubm_od) {
                                    recognition_ubm_od.start();
                                    isRecording_ubm_od = true;
                                    ubm_od_grabar.innerHTML = '<i class="fas fa-microphone-alt"></i>';
                                    ubm_od_grabar.disabled = false;
                                } else {
                                    recognition_ubm_od.stop();
                                    isRecording_ubm_od = false;
                                    ubm_od_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                                    ubm_od_grabar.disabled = false;
                                }
                            });
                        } else {
                            ubm_od_grabar.disabled = true;
                            ubm_od_grabar.title = "Reconocimiento de voz no soportado en este navegador";
                        }
                        ubm_od_mute.addEventListener('click', () => {
                            window.speechSynthesis.cancel();
                        });
                        btn_ubm_od.addEventListener('click', () => {
                            if (ubm_od.value.trim() !== '') {
                                const speech = new SpeechSynthesisUtterance(ubm_od.value);
                                speech.lang = 'es-ES';
                                speech.volume = 1;
                                speech.rate = 1;
                                speech.pitch = 1;
                                window.speechSynthesis.speak(speech);
                            }
                        });
                        </script>
                    </div>
                    <div class="col-md-6">
                        <label for="ubm_oi"><strong>Ojo Izquierdo (OI):</strong></label>
                        <div class="botones">
                            <button type="button" class="btn btn-danger btn-sm" id="ubm_oi_grabar"><i
                                    class="fas fa-microphone"></i></button>
                            <button type="button" class="btn btn-primary btn-sm" id="ubm_oi_mute"><i
                                    class="fas fa-volume-mute"></i></button>
                            <button type="button" class="btn btn-success btn-sm" id="play_ubm_oi"><i
                                    class="fas fa-play"></i></button>
                        </div>
                        <textarea class="form-control" name="ubm_oi" id="ubm_oi" rows="4"
                            placeholder="Ej. Ángulo de drenaje abierto ojo izquierdo"></textarea>
                        <script>
                        const ubm_oi_grabar = document.getElementById('ubm_oi_grabar');
                        const ubm_oi_mute = document.getElementById('ubm_oi_mute');
                        const ubm_oi = document.getElementById('ubm_oi');
                        const btn_ubm_oi = document.getElementById('play_ubm_oi');
                        let recognition_ubm_oi = null;
                        let isRecording_ubm_oi = false;
                        if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
                            recognition_ubm_oi = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
                            recognition_ubm_oi.lang = "es-ES";
                            recognition_ubm_oi.continuous = true;
                            recognition_ubm_oi.interimResults = false;
                            recognition_ubm_oi.onresult = (event) => {
                                const results = event.results;
                                const frase = results[results.length - 1][0].transcript;
                                ubm_oi.value += frase + ' ';
                            };
                            recognition_ubm_oi.onend = () => {
                                isRecording_ubm_oi = false;
                                ubm_oi_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                                ubm_oi_grabar.disabled = false;
                            };
                            ubm_oi_grabar.addEventListener('click', () => {
                                if (!isRecording_ubm_oi) {
                                    recognition_ubm_oi.start();
                                    isRecording_ubm_oi = true;
                                    ubm_oi_grabar.innerHTML = '<i class="fas fa-microphone-alt"></i>';
                                    ubm_oi_grabar.disabled = false;
                                } else {
                                    recognition_ubm_oi.stop();
                                    isRecording_ubm_oi = false;
                                    ubm_oi_grabar.innerHTML = '<i class="fas fa-microphone"></i>';
                                    ubm_oi_grabar.disabled = false;
                                }
                            });
                        } else {
                            ubm_oi_grabar.disabled = true;
                            ubm_oi_grabar.title = "Reconocimiento de voz no soportado en este navegador";
                        }
                        ubm_oi_mute.addEventListener('click', () => {
                            window.speechSynthesis.cancel();
                        });
                        btn_ubm_oi.addEventListener('click', () => {
                            if (ubm_oi.value.trim() !== '') {
                                const speech = new SpeechSynthesisUtterance(ubm_oi.value);
                                speech.lang = 'es-ES';
                                speech.volume = 1;
                                speech.rate = 1;
                                speech.pitch = 1;
                                window.speechSynthesis.speak(speech);
                            }
                        });
                        </script>
                    </div>
                </div>
            </div>
            <!-- Constante Fields -->
            <div class="form-group">
                <label><strong>Constantes:</strong></label>
                <div class="row">
                    <div class="col-md-6">
                        <label for="constante_derecho"><strong>Ojo Derecho (OD):</strong></label>
                        <input type="text" class="form-control" name="constante_derecho" id="constante_derecho"
                            placeholder="Ej. 18 mmHg">
                    </div>
                    <div class="col-md-6">
                        <label for="constante_izquierdo"><strong>Ojo Izquierdo (OI):</strong></label>
                        <input type="text" class="form-control" name="constante_izquierdo" id="constante_izquierdo"
                            placeholder="Ej. 16 mmHg">
                    </div>
                </div>
            </div>

            <center class="mt-3">
                <button type="submit" class="btn btn-primary">Firmar</button>
                <button type="button" class="btn btn-danger" onclick="history.back()">Cancelar</button>
            </center>
        </form>
    </div>
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
        <?php include("../../template/footer.php"); ?>
    </footer>
    <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
    <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
    <script>
    document.oncontextmenu = function() {
        return false;
    }
    </script>
</body>

</html>