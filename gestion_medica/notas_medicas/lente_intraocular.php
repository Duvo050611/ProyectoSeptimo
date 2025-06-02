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
        <div class="thead">
            <strong>
                <center>LENTE INTRAOCULAR (LIO)</center>
            </strong>
        </div>
        <form action="insertar_lente_intraocular.php" method="POST" onsubmit="return checkSubmit();">
            <input type="hidden" name="id_usua" value="<?php echo htmlspecialchars($_SESSION['login']['id_usua']); ?>">
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
                <button type="submit" class="btn btn-primary">Firmar</button>
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