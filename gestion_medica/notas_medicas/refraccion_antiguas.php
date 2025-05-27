<?php
session_start();
include "../../conexionbd.php";
include("../header_medico.php");
$usuario = $_SESSION['login'];
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>Exámenes de Laboratorio y Gabinete - Instituto de Enfermedades Oculares</title>
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
    <script src="https://kit.fontawesome.com/e547be4475.js" crossorigin="anonymous"></script>


    <script>
    $($document).ready(function() {
        $("#search").keyup(function() {
            var valor = $(this).val().toLowerCase();
            $("#mytable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(valor) > -1)
            });
        });
    });
    </script>
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

    .accordion .card {
        border: none;
    }

    .accordion .card-header {
        background-color: #e9ecef;
        cursor: pointer;
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
                        <center>HISTORIA CLÍNICA</center>
                    </strong></div>
                <?php
                include "../../conexionbd.php";
                if (isset($_SESSION['hospital'])) {
                    $id_atencion = $_SESSION['hospital'];
                    $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.folio, p.tel, p.fecnac, p.tip_san, di.fecha, di.area, di.alta_med, di.activo, p.sexo, di.alergias, p.ocup 
                                FROM paciente p, dat_ingreso di 
                                WHERE p.Id_exp=di.Id_exp AND di.id_atencion = ?";
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
                        $sql_est = "SELECT DATEDIFF(?, fecha) as estancia FROM dat_ingreso WHERE id_atencion = ?";
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
                    <div class="col-sm-2">Expediente: <strong><?php echo $folio; ?></strong></div>
                    <div class="col-sm-6">Paciente:
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
                        <?php echo $d ? "Diagnóstico: <strong>$d</strong>" : "Motivo de atención: <strong>$m</strong>"; ?>
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
                    <div class="col-sm-3">Talla: <strong><?php echo $talla; ?></strong></div>
                </div>
            </div>
        </div>
        <br><br>
        <div class="thead"><strong>
                <center>REFRACCION VISUAL ANTIGUAS</center>
            </strong></div>
        <form action="insertar_refraccion.php" method="POST" onsubmit="return checkSubmit();">
            <div class="card-header" id="headingRight">
                <div class="accordion mt-3" id="eyeAccordion">
                    <div class="card" id="ojoDerecho">
                        <div class="card-header">
                            <h2 class="mb-0">
                                <button class="btn btn-link text-dark" type="button" data-toggle="collapse"
                                    data-target="#collapseRight" aria-expanded="false" aria-controls="collapseRight"><i
                                        class="fa-solid fa-eye"></i>
                                    Ojo Derecho
                                </button>
                            </h2>
                        </div>

                        <div id="collapseRight" class="collapse" aria-labelledby="headingRight"
                            data-parent="#eyeAccordion">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="tipo_derecho"><strong>Tipo de Examen</strong></label>
                                    <select class="form-control" name="tipo_derecho" id="tipo_derecho">
                                        <option value="Selecciona">Selecciona</option>
                                        <option value="Esferómetro">Esferómetro</option>
                                        <option value="Autorrefractómetro">Autorrefractómetro</option>
                                        <option value="Retinoscopía">Retinoscopía</option>
                                        <option value="Queratometría">Queratometría</option>
                                        <option value="Foróptero">Foróptero</option>
                                        <option value="Cartilla de Snellen">Cartilla de Snellen</option>
                                        <option value="Lentes de prueba">Lentes de prueba</option>
                                        <option value="Refracción Subjetiva">Refracción Subjetiva</option>
                                        <option value="Refracción Objetiva">Refracción Objetiva</option>
                                        <option value="Topografía Corneal">Topografía Corneal</option>
                                        <option value="Otros">Otros</option>
                                    </select>
                                </div>

                                <h5 class="mt-3"><strong>Refracción Lejana - Ojo Derecho</strong></h5>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="av_lejana_Derecho">AV Lejana</label>
                                        <select class="form-control" name="av_lejana_Derecho" id="av_lejana_Derecho">
                                            <option value="">Seleccionar</option>
                                            <option value="20/20">20/20</option>
                                            <option value="20/25">20/25</option>
                                            <option value="20/30">20/30</option>
                                            <option value="20/40">20/40</option>
                                            <option value="20/50">20/50</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="av_lejana_lentes_Derecho">AV con Lentes</label>
                                        <select class="form-control" name="av_lejana_lentes_Derecho"
                                            id="av_lejana_lentes_Derecho">
                                            <option value="">Seleccionar</option>
                                            <option value="20/20">20/20</option>
                                            <option value="20/25">20/25</option>
                                            <option value="20/30">20/30</option>
                                            <option value="20/40">20/40</option>
                                            <option value="20/50">20/50</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2 form-group">
                                        <label for="esf_lejana_Derecho">Esf</label>
                                        <input type="text" class="form-control" name="esf_lejana_Derecho"
                                            id="esf_lejana_Derecho">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label for="cil_lejana_Derecho">Cil</label>
                                        <input type="text" class="form-control" name="cil_lejana_Derecho"
                                            id="cil_lejana_Derecho">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label for="eje_lejana_Derecho">Eje</label>
                                        <input type="text" class="form-control" name="eje_lejana_Derecho"
                                            id="eje_lejana_Derecho">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label for="add_lejana_Derecho">Add</label>
                                        <input type="text" class="form-control" name="add_lejana_Derecho"
                                            id="add_lejana_Derecho">
                                    </div>
                                    <div class="col-md-2 form-group d-flex align-items-center">
                                        <input type="checkbox" name="prisma_lejana_Derecho" id="prisma_lejana_Derecho">
                                        <label for="prisma_lejana_Derecho" class="ml-2 mb-0">Prisma</label>
                                    </div>
                                </div>

                                <h5 class="mt-4"><strong>Refracción Cercana - Ojo Derecho</strong></h5>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="av_cercana_Derecho">AV Cercana</label>
                                        <select class="form-control" name="av_cercana_Derecho" id="av_cercana_Derecho">
                                            <option value="">Seleccionar</option>
                                            <option value="20/20">20/20</option>
                                            <option value="20/25">20/25</option>
                                            <option value="20/30">20/30</option>
                                            <option value="20/40">20/40</option>
                                            <option value="20/50">20/50</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2 form-group">
                                        <label for="esf_cercana_Derecho">Esf</label>
                                        <input type="text" class="form-control" name="esf_cercana_Derecho"
                                            id="esf_cercana_Derecho">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label for="cil_cercana_Derecho">Cil</label>
                                        <input type="text" class="form-control" name="cil_cercana_Derecho"
                                            id="cil_cercana_Derecho">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label for="eje_cercana_Derecho">Eje</label>
                                        <input type="text" class="form-control" name="eje_cercana_Derecho"
                                            id="eje_cercana_Derecho">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label for="add_cercana_Derecho">Add</label>
                                        <input type="text" class="form-control" name="add_cercana_Derecho"
                                            id="add_cercana_Derecho">
                                    </div>
                                    <div class="col-md-2 form-group d-flex align-items-center pt-4">
                                        <input type="checkbox" name="prisma_cercana_Derecho"
                                            id="prisma_cercana_Derecho">
                                        <label for="prisma_cercana_Derecho" class="ml-2 mb-0">Prisma</label>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="detalles_laser_derecho"><strong>Detalles del
                                            Tratamiento:</strong></label>
                                    <div class="botones mb-2">
                                        <button type="button" class="btn btn-danger btn-sm" id="re_derecho_grabar"><i
                                                class="fas fa-microphone"></i></button>
                                        <button type="button" class="btn btn-primary btn-sm" id="re_derecho_detener"><i
                                                class="fas fa-microphone-slash"></i></button>
                                        <button type="button" class="btn btn-success btn-sm" id="play_re_derecho"><i
                                                class="fas fa-play"></i></button>
                                    </div>
                                    <textarea class="form-control" name="detalles_refraccion_visual"
                                        id="detalles_refraccion_visual" rows="4"
                                        placeholder="Ej. Observación de agudeza visual, recomendación de lentes, seguimiento de tratamiento"></textarea>
                                </div>
                            </div>
                        </div>
        </form>
    </div>


    <script>
    const det_re_derecho_grabar = document.getElementById('re_derecho_grabar');
    const det_re_derecho_detener = document.getElementById('re_derecho_detener');
    const detalles_re_derecho = document.getElementById('detalles_laser_derecho');
    const btn_det_derecho = document.getElementById('play_re_derecho');

    btn_det_derecho.addEventListener('click', () => {
        leerTexto(detalles_re_derecho.value);
    });

    let recognition_det_laser_derecho = new webkitSpeechRecognition();
    recognition_det_laser_derecho.lang = "es-ES";
    recognition_det_laser_derecho.continuous = true;
    recognition_det_laser_derecho.interimResults = false;
    recognition_det_laser_derecho.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length - 1][0].transcript;
        detalles_re_derecho.value += frase;
    };

    det_re_derecho_grabar.addEventListener('click', () => {
        recognition_det_laser_derecho.start();
    });

    det_re_derecho_detener.addEventListener('click', () => {
        recognition_det_laser_derecho.abort();
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

    <div class="card" id="ojoIzquierdo">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#collapseLeft"
                    aria-expanded="false" aria-controls="collapseLeft"><i class="fa-solid fa-eye"></i>
                    Ojo Izquierdo
                </button>
            </h2>
        </div>
        <div id="collapseLeft" class="collapse" data-parent="#eyeAccordion">
            <div class="card-body">
                <div class="form-group">
                    <label for="tipo_izquierdo"><strong>Tipo de Examen</strong></label>
                    <select class="form-control" name="tipo_izquierdo" id="tipo_izquierdo">
                        <option value="Selecciona">Selecciona</option>
                        <option value="Esferómetro">Esferómetro</option>
                        <option value="Autorrefractómetro">Autorrefractómetro</option>
                        <option value="Retinoscopía">Retinoscopía</option>
                        <option value="Queratometría">Queratometría</option>
                        <option value="Foróptero">Foróptero</option>
                        <option value="Cartilla de Snellen">Cartilla de Snellen</option>
                        <option value="Lentes de prueba">Lentes de prueba</option>
                        <option value="Refracción Subjetiva">Refracción Subjetiva</option>
                        <option value="Refracción Objetiva">Refracción Objetiva</option>
                        <option value="Topografía Corneal">Topografía Corneal</option>
                        <option value="Otros">Otros</option>
                    </select>
                </div>

                <h5 class="mt-4"><strong>Refracción Lejana - Ojo Izquierdo</strong></h5>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="av_lejana_Izquierdo">AV Lejana</label>
                        <select id="av_lejana_Izquierdo" name="av_lejana_Izquierdo" class="form-control">
                            <option value="">Seleccionar</option>
                            <option value="20/20">20/20</option>
                            <option value="20/25">20/25</option>
                            <option value="20/30">20/30</option>
                            <option value="20/40">20/40</option>
                            <option value="20/50">20/50</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="av_lejana_lentes_Izquierdo">AV con Lentes</label>
                        <select id="av_lejana_lentes_Izquierdo" name="av_lejana_lentes_Izquierdo" class="form-control">
                            <option value="">Seleccionar</option>
                            <option value="20/20">20/20</option>
                            <option value="20/25">20/25</option>
                            <option value="20/30">20/30</option>
                            <option value="20/40">20/40</option>
                            <option value="20/50">20/50</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2 form-group">
                        <label for="esf_lejana_Izquierdo">Esf</label>
                        <input type="text" class="form-control" name="esf_lejana_Izquierdo" id="esf_lejana_Izquierdo">
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="cil_lejana_Izquierdo">Cil</label>
                        <input type="text" class="form-control" name="cil_lejana_Izquierdo" id="cil_lejana_Izquierdo">
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="eje_lejana_Izquierdo">Eje</label>
                        <input type="text" class="form-control" name="eje_lejana_Izquierdo" id="eje_lejana_Izquierdo">
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="add_lejana_Izquierdo">Add</label>
                        <input type="text" class="form-control" name="add_lejana_Izquierdo" id="add_lejana_Izquierdo">
                    </div>
                    <div class="col-md-2 form-group d-flex align-items-center">
                        <input type="checkbox" name="prisma_lejana_Izquierdo" id="prisma_lejana_Izquierdo">
                        <label for="prisma_lejana_Izquierdo" class="ml-2 mb-0">Prisma</label>
                    </div>
                </div>

                <h5 class="mt-4"><strong>Refracción Cercana - Ojo Izquierdo</strong></h5>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="av_cercana_Izquierdo">AV Cercana</label>
                        <select id="av_cercana_Izquierdo" name="av_cercana_Izquierdo" class="form-control">
                            <option value="">Seleccionar</option>
                            <option value="20/20">20/20</option>
                            <option value="20/25">20/25</option>
                            <option value="20/30">20/30</option>
                            <option value="20/40">20/40</option>
                            <option value="20/50">20/50</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2 form-group">
                        <label for="esf_cercana_Izquierdo">Esf</label>
                        <input type="text" class="form-control" name="esf_cercana_Izquierdo" id="esf_cercana_Izquierdo">
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="cil_cercana_Izquierdo">Cil</label>
                        <input type="text" class="form-control" name="cil_cercana_Izquierdo" id="cil_cercana_Izquierdo">
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="eje_cercana_Izquierdo">Eje</label>
                        <input type="text" class="form-control" name="eje_cercana_Izquierdo" id="eje_cercana_Izquierdo">
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="add_cercana_Izquierdo">Add</label>
                        <input type="text" class="form-control" name="add_cercana_Izquierdo" id="add_cercana_Izquierdo">
                    </div>
                    <div class="col-md-2 form-group d-flex align-items-center">
                        <input type="checkbox" name="prisma_cercana_Izquierdo" id="prisma_cercana_Izquierdo">
                        <label for="prisma_cercana_Izquierdo" class="ml-2 mb-0">Prisma</label>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label for="detalles_laser_izquierdo"><strong>Detalles del Tratamiento:</strong></label>
                    <div class="botones mb-2">
                        <button type="button" class="btn btn-danger btn-sm" id="re_izquierdo_grabar"><i
                                class="fas fa-microphone"></i></button>
                        <button type="button" class="btn btn-primary btn-sm" id="re_izquierdo_detener"><i
                                class="fas fa-microphone-slash"></i></button>
                        <button type="button" class="btn btn-success btn-sm" id="play_re_izquierdo"><i
                                class="fas fa-play"></i></button>
                    </div>
                    <textarea class="form-control" name="detalles_refraccion_visual" id="detalles_refraccion_visual"
                        rows="4"
                        placeholder="Ej. Observación de agudeza visual, recomendación de lentes, seguimiento de tratamiento"></textarea>
                </div>

                <script>
                const det_re_izquierdo_grabar = document.getElementById('re_izquierdo_grabar');
                const det_re_izquierdo_detener = document.getElementById('re_izquierdo_detener');
                const detalles_re_izquierdo = document.getElementById('detalles_laser_izquierdo');
                const btn_det_izquierdo = document.getElementById('play_re_izquierdo');

                btn_det_izquierdo.addEventListener('click', () => {
                    leerTextoIzquierdo(detalles_re_izquierdo.value);
                });

                let recognition_det_laser_izquierdo = new webkitSpeechRecognition();
                recognition_det_laser_izquierdo.lang = "es-ES";
                recognition_det_laser_izquierdo.continuous = true;
                recognition_det_laser_izquierdo.interimResults = false;
                recognition_det_laser_izquierdo.onresult = (event) => {
                    const results = event.results;
                    const frase = results[results.length - 1][0].transcript;
                    detalles_re_izquierdo.value += frase;
                };

                det_re_izquierdo_grabar.addEventListener('click', () => {
                    recognition_det_laser_izquierdo.start();
                });

                det_re_izquierdo_detener.addEventListener('click', () => {
                    recognition_det_laser_izquierdo.abort();
                });

                function leerTextoIzquierdo(texto) {
                    const speech = new SpeechSynthesisUtterance();
                    speech.text = texto;
                    speech.volume = 1;
                    speech.rate = 1;
                    speech.pitch = 0;
                    window.speechSynthesis.speak(speech);
                }
                </script>
            </div>
        </div>
    </div>

    <!--<div class="accordion mt-3" id="refraccionAccordion">
        <div class="card" id="refraccionActualCard">
            <div class="card-header" id="headingRefraccionActual">
                <h2 class="mb-0">
                    <button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#collapseRefraccionActual" aria-expanded="false" aria-controls="collapseRefraccionActual">
                        Refracción Actual
                    </button>
                </h2>
            </div>

            <div id="collapseRefraccionActual" class="collapse" aria-labelledby="headingRefraccionActual" data-parent="#refraccionAccordion">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="av_binocular">AV Binocular</label>
                            <input type="text" class="form-control" name="av_binocular" id="av_binocular">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="av_sin_correccion">AV sin Corrección</label>
                            <input type="text" class="form-control" name="av_sin_correccion" id="av_sin_correccion">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="av_estenopeico">AV Estenopeico</label>
                            <input type="text" class="form-control" name="av_estenopeico" id="av_estenopeico">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="av_corr_propia">AV Corrección Propia</label>
                            <input type="text" class="form-control" name="av_corr_propia" id="av_corr_propia">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="av_mejor_corr">AV Mejor Corregida</label>
                            <input type="text" class="form-control" name="av_mejor_corr" id="av_mejor_corr">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="av_potencial">AV Potencial</label>
                            <input type="text" class="form-control" name="av_potencial" id="av_potencial">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div> -->
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
            alert("El formulario ya se esta enviando");
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