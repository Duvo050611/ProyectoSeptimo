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

    <!-- Bootstrap 4.5 CSS -->
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

    <!-- Scripts adicionales -->
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

    <title>AVISO DE ALTA</title>
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
        <div class="thead">
            <strong>
                <center>RECOMENDACIONES OCULARES</center>
            </strong>
        </div>
        <form action="insertar_recomendaciones.php" method="POST" onsubmit="return checkSubmit();">
            <!-- General Fields -->
            <div class="form-group mt-3">
                <label for="tipo_recomendacion"><strong>Tipo de Recomendación:</strong></label>
                <select name="tipo_recomendacion" class="form-control" id="tipo_recomendacion" required>
                    <option value="">Seleccionar</option>
                    <option value="Medicación">Medicación</option>
                    <option value="Seguimiento">Seguimiento</option>
                    <option value="Cambios en Estilo de Vida">Cambios en Estilo de Vida</option>
                    <option value="Procedimiento Adicional">Procedimiento Adicional</option>
                    <option value="Educación al Paciente">Educación al Paciente</option>
                </select>
                <script>
                $(document).ready(function() {
                    $('#tipo_recomendacion').select2();
                });
                </script>
            </div>
            <div class="form-group">
                <label for="diagnostico_relacionado"><strong>Diagnóstico Relacionado (CIE-10):</strong></label>
                <select name="diagnostico_relacionado" class="form-control" id="diagnostico_relacionado" required>
                    <option value="">Seleccionar</option>
                    <?php
                $sql_diag = "SELECT * FROM cat_diag WHERE diagnostico LIKE '%ojos%' OR diagnostico LIKE '%vista%' ORDER BY id_cie10";
                $result_diag = $conexion->query($sql_diag);
                while ($row = $result_diag->fetch_assoc()) {
                    echo "<option value='" . $row['diagnostico'] . "'>" . $row['id_cie10'] . " - " . $row['diagnostico'] . "</option>";
                }
                ?>
                </select>
                <script>
                $(document).ready(function() {
                    $('#diagnostico_relacionado').select2();
                });
                </script>
            </div>
            <div class="form-group">
                <label for="recomendaciones_generales"><strong>Recomendaciones Generales:</strong></label>
                <div class="botones">
                    <button type="button" class="btn btn-danger btn-sm" id="gen_grabar"><i
                            class="fas fa-microphone"></i></button>
                    <button type="button" class="btn btn-primary btn-sm" id="gen_detener"><i
                            class="fas fa-microphone-slash"></i></button>
                    <button type="button" class="btn btn-success btn-sm" id="play_gen"><i
                            class="fas fa-play"></i></button>
                </div>
                <textarea class="form-control" name="recomendaciones_generales" id="recomendaciones_generales" rows="4"
                    placeholder="Ej. Usar gafas de protección solar, evitar frotar los ojos"></textarea>
                <script>
                const gen_grabar = document.getElementById('gen_grabar');
                const gen_detener = document.getElementById('gen_detener');
                const recomendaciones_generales = document.getElementById('recomendaciones_generales');
                const btn_gen = document.getElementById('play_gen');
                btn_gen.addEventListener('click', () => {
                    leerTexto(recomendaciones_generales.value);
                });
                let recognition_gen = new webkitSpeechRecognition();
                recognition_gen.lang = "es-ES";
                recognition_gen.continuous = true;
                recognition_gen.interimResults = false;
                recognition_gen.onresult = (event) => {
                    const results = event.results;
                    const frase = results[results.length - 1][0].transcript;
                    recomendaciones_generales.value += frase;
                };
                gen_grabar.addEventListener('click', () => {
                    recognition_gen.start();
                });
                gen_detener.addEventListener('click', () => {
                    recognition_gen.abort();
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
                <label for="plan_seguimiento"><strong>Plan de Seguimiento:</strong></label>
                <div class="botones">
                    <button type="button" class="btn btn-danger btn-sm" id="seg_grabar"><i
                            class="fas fa-microphone"></i></button>
                    <button type="button" class="btn btn-primary btn-sm" id="seg_detener"><i
                            class="fas fa-microphone-slash"></i></button>
                    <button type="button" class="btn btn-success btn-sm" id="play_seg"><i
                            class="fas fa-play"></i></button>
                </div>
                <textarea class="form-control" name="plan_seguimiento" id="plan_seguimiento" rows="4"
                    placeholder="Ej. Cita de control en 3 meses, repetir tonometría"></textarea>
                <script>
                const seg_grabar = document.getElementById('seg_grabar');
                const seg_detener = document.getElementById('seg_detener');
                const plan_seguimiento = document.getElementById('plan_seguimiento');
                const btn_seg = document.getElementById('play_seg');
                btn_seg.addEventListener('click', () => {
                    leerTexto(plan_seguimiento.value);
                });
                let recognition_seg = new webkitSpeechRecognition();
                recognition_seg.lang = "es-ES";
                recognition_seg.continuous = true;
                recognition_seg.interimResults = false;
                recognition_seg.onresult = (event) => {
                    const results = event.results;
                    const frase = results[results.length - 1][0].transcript;
                    plan_seguimiento.value += frase;
                };
                seg_grabar.addEventListener('click', () => {
                    recognition_seg.start();
                });
                seg_detener.addEventListener('click', () => {
                    recognition_seg.abort();
                });
                </script>
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
                                    <input class="form-check-input" type="checkbox" name="recomendacion_derecho"
                                        id="recomendacion_derecho" value="1">
                                    <label class="form-check-label" for="recomendacion_derecho">Recomendación Específica
                                        para Ojo Derecho</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="detalles_derecho"><strong>Detalles:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="det_derecho_grabar"><i
                                            class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="det_derecho_detener"><i
                                            class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_det_derecho"><i
                                            class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" name="detalles_derecho" id="detalles_derecho" rows="4"
                                    placeholder="Ej. Aplicar gotas de timolol 0.5% dos veces al día en ojo derecho"></textarea>
                                <script>
                                const det_derecho_grabar = document.getElementById('det_derecho_grabar');
                                const det_derecho_detener = document.getElementById('det_derecho_detener');
                                const detalles_derecho = document.getElementById('detalles_derecho');
                                const btn_det_derecho = document.getElementById('play_det_derecho');
                                btn_det_derecho.addEventListener('click', () => {
                                    leerTexto(detalles_derecho.value);
                                });
                                let recognition_det_derecho = new webkitSpeechRecognition();
                                recognition_det_derecho.lang = "es-ES";
                                recognition_det_derecho.continuous = true;
                                recognition_det_derecho.interimResults = false;
                                recognition_det_derecho.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    detalles_derecho.value += frase;
                                };
                                det_derecho_grabar.addEventListener('click', () => {
                                    recognition_det_derecho.start();
                                });
                                det_derecho_detener.addEventListener('click', () => {
                                    recognition_det_derecho.abort();
                                });
                                </script>
                            </div>
                            <div class="form-group">
                                <label for="notas_derecho"><strong>Notas:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="notas_derecho_grabar"><i
                                            class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="notas_derecho_detener"><i
                                            class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_notas_derecho"><i
                                            class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" name="notas_derecho" id="notas_derecho" rows="4"
                                    placeholder="Ej. Monitorear presión intraocular semanalmente"></textarea>
                                <script>
                                const notas_derecho_grabar = document.getElementById('notas_derecho_grabar');
                                const notas_derecho_detener = document.getElementById('notas_derecho_detener');
                                const notas_derecho = document.getElementById('notas_derecho');
                                const btn_notas_derecho = document.getElementById('play_notas_derecho');
                                btn_notas_derecho.addEventListener('click', () => {
                                    leerTexto(notas_derecho.value);
                                });
                                let recognition_notas_derecho = new webkitSpeechRecognition();
                                recognition_notas_derecho.lang = "es-ES";
                                recognition_notas_derecho.continuous = true;
                                recognition_notas_derecho.interimResults = false;
                                recognition_notas_derecho.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    notas_derecho.value += frase;
                                };
                                notas_derecho_grabar.addEventListener('click', () => {
                                    recognition_notas_derecho.start();
                                });
                                notas_derecho_detener.addEventListener('click', () => {
                                    recognition_notas_derecho.abort();
                                });
                                </script>
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
                                    <input class="form-check-input" type="checkbox" name="recomendacion_izquierdo"
                                        id="recomendacion_izquierdo" value="1">
                                    <label class="form-check-label" for="recomendacion_izquierdo">Recomendación
                                        Específica para Ojo Izquierdo</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="detalles_izquierdo"><strong>Detalles:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="det_izquierdo_grabar"><i
                                            class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="det_izquierdo_detener"><i
                                            class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_det_izquierdo"><i
                                            class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" name="detalles_izquierdo" id="detalles_izquierdo"
                                    rows="4"
                                    placeholder="Ej. Aplicar gotas de prednisolona 1% tres veces al día en ojo izquierdo"></textarea>
                                <script>
                                const det_izquierdo_grabar = document.getElementById('det_izquierdo_grabar');
                                const det_izquierdo_detener = document.getElementById('det_izquierdo_detener');
                                const detalles_izquierdo = document.getElementById('detalles_izquierdo');
                                const btn_det_izquierdo = document.getElementById('play_det_izquierdo');
                                btn_det_izquierdo.addEventListener('click', () => {
                                    leerTexto(detalles_izquierdo.value);
                                });
                                let recognition_det_izquierdo = new webkitSpeechRecognition();
                                recognition_det_izquierdo.lang = "es-ES";
                                recognition_det_izquierdo.continuous = true;
                                recognition_det_izquierdo.interimResults = false;
                                recognition_det_izquierdo.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    detalles_izquierdo.value += frase;
                                };
                                det_izquierdo_grabar.addEventListener('click', () => {
                                    recognition_det_izquierdo.start();
                                });
                                det_izquierdo_detener.addEventListener('click', () => {
                                    recognition_det_izquierdo.abort();
                                });
                                </script>
                            </div>
                            <div class="form-group">
                                <label for="notas_izquierdo"><strong>Notas:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="notas_izquierdo_grabar"><i
                                            class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="notas_izquierdo_detener"><i
                                            class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_notas_izquierdo"><i
                                            class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" name="notas_izquierdo" id="notas_izquierdo" rows="4"
                                    placeholder="Ej. Observar signos de inflamación en ojo izquierdo"></textarea>
                                <script>
                                const notas_izquierdo_grabar = document.getElementById('notas_izquierdo_grabar');
                                const notas_izquierdo_detener = document.getElementById('notas_izquierdo_detener');
                                const notas_izquierdo = document.getElementById('notas_izquierdo');
                                const btn_notas_izquierdo = document.getElementById('play_notas_izquierdo');
                                btn_notas_izquierdo.addEventListener('click', () => {
                                    leerTexto(notas_izquierdo.value);
                                });
                                let recognition_notas_izquierdo = new webkitSpeechRecognition();
                                recognition_notas_izquierdo.lang = "es-ES";
                                recognition_notas_izquierdo.continuous = true;
                                recognition_notas_izquierdo.interimResults = false;
                                recognition_notas_izquierdo.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    notas_izquierdo.value += frase;
                                };
                                notas_izquierdo_grabar.addEventListener('click', () => {
                                    recognition_notas_izquierdo.start();
                                });
                                notas_izquierdo_detener.addEventListener('click', () => {
                                    recognition_notas_izquierdo.abort();
                                });
                                </script>
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