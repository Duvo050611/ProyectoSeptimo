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
                <center>REFRACCIÓN VISUAL ACTUAL</center>
            </strong></div>
        <form action="insertar_refraccion.php" method="POST" onsubmit="return checkSubmit();">
            <div class="card-header" id="headingRight">
                <div class="accordion mt-3" id="eyeAccordion">
                    <div class="card" id="refraccionActual">
                        <div class="card-header">
                            <h2 class="mb-0">
                                <button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#collapseRefraccion" aria-expanded="false" aria-controls="collapseRefraccion">
                                    Refracción Actual
                                </button>
                            </h2>
                        </div>
                        <div id="collapseRefraccion" class="collapse" aria-labelledby="headingRefraccion" data-parent="#eyeAccordion">
                            <div class="card-body">
                                <div class="container mt-4">
                                    <h4 class="text-center mb-4">Refracción Visual Actual</h4>

                                    <div class="form-row">
                                        <div class="form-group col-md-6 offset-md-3">
                                            <label for="av_binocular">AV Binocular:</label>
                                            <select id="av_binocular" name="av_binocular" class="form-control">
                                                <option value="">Seleccionar</option>
                                                <option value="20/20">20/20</option>
                                                <option value="20/25">20/25</option>
                                                <option value="20/30">20/30</option>
                                                <option value="20/40">20/40</option>
                                                <option value="20/50">20/50</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="text-center my-4">
                                        <button class="btn btn-outline-primary mx-2" type="button" data-toggle="collapse" data-target="#ojoDerecho" aria-expanded="false" aria-controls="ojoDerecho"><i class="fa-solid fa-eye"></i>
                                            Ojo Derecho
                                        </button>
                                        <button class="btn btn-outline-primary mx-2" type="button" data-toggle="collapse" data-target="#ojoIzquierdo" aria-expanded="false" aria-controls="ojoIzquierdo"><i class="fa-solid fa-eye"></i>
                                            Ojo Izquierdo
                                        </button>
                                    </div>

                                    <!-- Panel Ojo Derecho -->
                                    <div class="collapse" id="ojoDerecho">
                                        <div class="card card-body mb-4">
                                            <h5 class="text-primary">Ojo Derecho (OD)</h5>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="av_lejana_sin_correc" class="text-primary">AV Lejana sin Corrección:</label>
                                                    <select id="av_lejana_sin_correc" name="av_lejana_sin_correc" class="form-control">
                                                        <option value="">Seleccionar</option>
                                                        <option value="20/20">20/20</option>
                                                        <option value="20/25">20/25</option>
                                                        <option value="20/30">20/30</option>
                                                        <option value="20/40">20/40</option>
                                                        <option value="20/50">20/50</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="av_estenopico">AV Estenópico:</label>
                                                    <select id="av_estenopico" name="av_estenopico" class="form-control">
                                                        <option value="">Seleccionar</option>
                                                        <option value="20/20">20/20</option>
                                                        <option value="20/25">20/25</option>
                                                        <option value="20/30">20/30</option>
                                                        <option value="20/40">20/40</option>
                                                        <option value="20/50">20/50</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="av_lejana_con_correc_prop">AV con Corrección Propia:</label>
                                                    <select id="av_lejana_con_correc_prop" name="av_lejana_con_correc_prop" class="form-control">
                                                        <option value="">Seleccionar</option>
                                                        <option value="20/20">20/20</option>
                                                        <option value="20/25">20/25</option>
                                                        <option value="20/30">20/30</option>
                                                        <option value="20/40">20/40</option>
                                                        <option value="20/50">20/50</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="av_lejana_mejor_corregida" class="text-primary">AV Mejor Corregida:</label>
                                                    <select id="av_lejana_mejor_corregida" name="av_lejana_mejor_corregida" class="form-control">
                                                        <option value="">Seleccionar</option>
                                                        <option value="20/20">20/20</option>
                                                        <option value="20/25">20/25</option>
                                                        <option value="20/30">20/30</option>
                                                        <option value="20/40">20/40</option>
                                                        <option value="20/50">20/50</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="av_potencial">AV Potencial:</label>
                                                    <select id="av_potencial" name="av_potencial" class="form-control">
                                                        <option value="">Seleccionar</option>
                                                        <option value="20/20">20/20</option>
                                                        <option value="20/25">20/25</option>
                                                        <option value="20/30">20/30</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Panel Ojo Izquierdo -->
                                    <div class="collapse" id="ojoIzquierdo">
                                        <div class="card card-body mb-4">
                                            <h5 class="text-primary">Ojo Izquierdo (OI)</h5>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="oi_lejana_sin_correc" class="text-primary">AV Lejana sin Corrección:</label>
                                                    <select id="oi_lejana_sin_correc" name="oi_lejana_sin_correc" class="form-control">
                                                        <option value="">Seleccionar</option>
                                                        <option value="20/20">20/20</option>
                                                        <option value="20/25">20/25</option>
                                                        <option value="20/30">20/30</option>
                                                        <option value="20/40">20/40</option>
                                                        <option value="20/50">20/50</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="oi_estenopico">AV Estenópico:</label>
                                                    <select id="oi_estenopico" name="oi_estenopico" class="form-control">
                                                        <option value="">Seleccionar</option>
                                                        <option value="20/20">20/20</option>
                                                        <option value="20/25">20/25</option>
                                                        <option value="20/30">20/30</option>
                                                        <option value="20/40">20/40</option>
                                                        <option value="20/50">20/50</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="oi_lejana_con_correc_prop">AV con Corrección Propia:</label>
                                                    <select id="oi_lejana_con_correc_prop" name="oi_lejana_con_correc_prop" class="form-control">
                                                        <option value="">Seleccionar</option>
                                                        <option value="20/20">20/20</option>
                                                        <option value="20/25">20/25</option>
                                                        <option value="20/30">20/30</option>
                                                        <option value="20/40">20/40</option>
                                                        <option value="20/50">20/50</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="oi_lejana_mejor_corregida" class="text-primary">AV Mejor Corregida:</label>
                                                    <select id="oi_lejana_mejor_corregida" name="oi_lejana_mejor_corregida" class="form-control">
                                                        <option value="">Seleccionar</option>
                                                        <option value="20/20">20/20</option>
                                                        <option value="20/25">20/25</option>
                                                        <option value="20/30">20/30</option>
                                                        <option value="20/40">20/40</option>
                                                        <option value="20/50">20/50</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="oi_potencial">AV Potencial:</label>
                                                    <select id="oi_potencial" name="oi_potencial" class="form-control">
                                                        <option value="">Seleccionar</option>
                                                        <option value="20/20">20/20</option>
                                                        <option value="20/25">20/25</option>
                                                        <option value="20/30">20/30</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="form-group mt-4">
    <label for="detalle_refra"><strong>Detalles de Refracción:</strong></label>
    <div class="mb-2">
        <button type="button" class="btn btn-danger btn-sm" id="refra_grabar"><i class="fas fa-microphone"></i></button>
        <button type="button" class="btn btn-primary btn-sm" id="refra_detener"><i class="fas fa-microphone-slash"></i></button>
        <button type="button" class="btn btn-success btn-sm" id="play_refra"><i class="fas fa-play"></i></button>
    </div>
    <textarea class="form-control" name="detalle_refra" id="detalle_refra" rows="4" placeholder="Ej. Observación de agudeza visual, recomendación de lentes, seguimiento de tratamiento"></textarea>
</div>

        
    </div>
    </div>
    </div>

    </div>


    <script>
    const grabarRefra = document.getElementById('refra_grabar');
    const detenerRefra = document.getElementById('refra_detener');
    const campoDetalleRefra = document.getElementById('detalle_refra');
    const reproducirRefra = document.getElementById('play_refra');

    reproducirRefra.addEventListener('click', () => {
        leerTextoRefra(campoDetalleRefra.value);
    });

    let reconocimientoRefra = new webkitSpeechRecognition();
    reconocimientoRefra.lang = "es-ES";
    reconocimientoRefra.continuous = true;
    reconocimientoRefra.interimResults = false;

    reconocimientoRefra.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length - 1][0].transcript;
        campoDetalleRefra.value += frase;
    };

    grabarRefra.addEventListener('click', () => {
        reconocimientoRefra.start();
    });

    detenerRefra.addEventListener('click', () => {
        reconocimientoRefra.abort();
    });

    function leerTextoRefra(texto) {
        const speech = new SpeechSynthesisUtterance();
        speech.text = texto;
        speech.volume = 1;
        speech.rate = 1;
        speech.pitch = 0;
        window.speechSynthesis.speak(speech);
    }
</script>

    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#collapseSinCiclo" aria-expanded="false" aria-controls="collapseSinCiclo">
                    Ref. Subjetiva Lejana Sin Cicloplejía
                </button>
            </h2>
        </div>
        <div id="collapseSinCiclo" class="collapse" data-parent="#eyeAccordion">
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-12">
                        <h5 class="text-primary fw-bold">Ojo Derecho (OD)</h5>
                    </div>

                    <div class="col-md-2 form-group">
                        <label for="esferas_sin_ciclo_od">Esf</label>
                        <input type="text" class="form-control" name="esferas_sin_ciclo_od" id="esferas_sin_ciclo_od" placeholder="Esf">
                    </div>

                    <div class="col-md-2 form-group">
                        <label for="cilindros_sin_ciclo_od">Cil</label>
                        <input type="text" class="form-control" name="cilindros_sin_ciclo_od" id="cilindros_sin_ciclo_od" placeholder="Cil">
                    </div>

                    <div class="col-md-2 form-group">
                        <label for="eje_sin_ciclo_od">Eje</label>
                        <input type="text" class="form-control" name="eje_sin_ciclo_od" id="eje_sin_ciclo_od" placeholder="Eje">
                    </div>

                    <div class="col-md-2 form-group">
                        <label for="add_sin_ciclo_od">Add</label>
                        <input type="text" class="form-control" name="add_sin_ciclo_od" id="add_sin_ciclo_od" placeholder="Add">
                    </div>

                    <div class="col-md-2 form-group">
    <label for="dip_sin_ciclo_od">DIP</label>
    <input type="text" class="form-control" name="dip_sin_ciclo_od" id="dip_sin_ciclo_od" placeholder="DIP">
</div>

                    <div class="col-md-2 form-group d-flex align-items-center">
                        <input type="checkbox" name="prisma_sin_ciclo_od" id="prisma_sin_ciclo_od" value="1">
                        <label for="prisma_sin_ciclo_od" class="ml-2 mb-0">Prisma</label>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <h5 class="text-primary fw-bold">Ojo Izquierdo (OI)</h5>
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="esferas_sin_ciclo_oi">Esf</label>
                        <input type="text" class="form-control" name="esferas_sin_ciclo_oi" id="esferas_sin_ciclo_oi" placeholder="Esf">
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="cilindros_sin_ciclo_oi">Cil</label>
                        <input type="text" class="form-control" name="cilindros_sin_ciclo_oi" id="cilindros_sin_ciclo_oi" placeholder="Cil">
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="eje_sin_ciclo_oi">Eje</label>
                        <input type="text" class="form-control" name="eje_sin_ciclo_oi" id="eje_sin_ciclo_oi" placeholder="Eje">
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="add_sin_ciclo_oi">Add</label>
                        <input type="text" class="form-control" name="add_sin_ciclo_oi" id="add_sin_ciclo_oi" placeholder="Add">
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="dip_sin_ciclo_oi">DIP</label>
                        <input type="text" class="form-control" name="dip_sin_ciclo_oi" id="dip_sin_ciclo_oi" placeholder="DIP">
                    </div>
                    <div class="col-md-2 form-group d-flex align-items-center">
                        <input type="checkbox" name="prisma_sin_ciclo_oi" id="prisma_sin_ciclo_oi" value="1">
                        <label for="prisma_sin_ciclo_oi" class="ml-2 mb-0">Prisma</label>
                    </div>
                </div>

<div class="form-group mt-4">
    <label for="detalle_ref_subjetiv_sin"><strong>Detalles del Tratamiento:</strong></label>
    <div class="botones mb-2">
        <button type="button" class="btn btn-danger btn-sm" id="detalle_ref_subjetiv_sin_grabar"><i class="fas fa-microphone"></i></button>
        <button type="button" class="btn btn-primary btn-sm" id="detalle_ref_subjetiv_sin_detener"><i class="fas fa-microphone-slash"></i></button>
        <button type="button" class="btn btn-success btn-sm" id="play_detalle_ref_subjetiv_sin"><i class="fas fa-play"></i></button>
    </div>
    <textarea class="form-control" name="detalle_ref_subjetiv_sin" id="detalle_ref_subjetiv_sin" rows="4" placeholder="Ej. Observación de agudeza visual, recomendación de lentes, seguimiento de tratamiento"></textarea>
</div>

<script>
    const grabarDetalleSubjetiv = document.getElementById('detalle_ref_subjetiv_sin_grabar');
    const detenerDetalleSubjetiv = document.getElementById('detalle_ref_subjetiv_sin_detener');
    const reproducirDetalleSubjetiv = document.getElementById('play_detalle_ref_subjetiv_sin');
    const textareaDetalleSubjetiv = document.getElementById('detalle_ref_subjetiv_sin');

    const reconocimientoDetalleSubjetiv = new webkitSpeechRecognition();
    reconocimientoDetalleSubjetiv.lang = "es-ES";
    reconocimientoDetalleSubjetiv.continuous = true;
    reconocimientoDetalleSubjetiv.interimResults = false;

    reconocimientoDetalleSubjetiv.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length - 1][0].transcript;
        textareaDetalleSubjetiv.value += frase + ' ';
    };

    grabarDetalleSubjetiv.addEventListener('click', () => reconocimientoDetalleSubjetiv.start());
    detenerDetalleSubjetiv.addEventListener('click', () => reconocimientoDetalleSubjetiv.abort());

    reproducirDetalleSubjetiv.addEventListener('click', () => {
        const speech = new SpeechSynthesisUtterance(textareaDetalleSubjetiv.value);
        speech.volume = 1;
        speech.rate = 1;
        speech.pitch = 1;
        window.speechSynthesis.speak(speech);
    });
</script>

            </div>
        </div>
    </div>

    <div class="accordion mt-3" id="refraccionAccordion">
        <!-- Ref. Subjetiva Lejana Con Cicloplejía -->
        <div class="card" id="refraccionConCicloCard">
            <div class="card-header" id="headingRefraccionConCiclo">
                <h2 class="mb-0">
                    <button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#collapseRefraccionConCiclo" aria-expanded="false" aria-controls="collapseRefraccionConCiclo">
                        Ref. Subjetiva Lejana Con Cicloplejía
                    </button>
                </h2>
            </div>

            <div id="collapseRefraccionConCiclo" class="collapse" aria-labelledby="headingRefraccionConCiclo" data-parent="#refraccionAccordion">
                <div class="card-body">

                    <div class="group mt-4">
                        <h5>Ref. Subjetiva Lejana con Cicloplejía</h5>
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-1 col-form-label fw-bold">OD:</label>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="esferas_con_ciclo_od" placeholder="Esf" />
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="cilindros_con_ciclo_od" placeholder="Cil" />
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="eje_con_ciclo_od" placeholder="Eje" />
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="add_con_ciclo_od" placeholder="Add" />
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="dip_con_ciclo_od" placeholder="DIP" />
                            </div>
                            <div class="col-md-1 d-flex align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="prisma_con_ciclo_od" id="prisma_con_ciclo_od" value="1" />
                                    <label class="form-check-label mb-0" for="prisma_con_ciclo_od">Prisma</label>
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-center">
                            <label class="col-md-1 col-form-label fw-bold">OI:</label>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="esferas_con_ciclo_oi" placeholder="Esf" />
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="cilindros_con_ciclo_oi" placeholder="Cil" />
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="eje_con_ciclo_oi" placeholder="Eje" />
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="add_con_ciclo_oi" placeholder="Add" />
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="dip_con_ciclo_oi" placeholder="DIP" />
                            </div>
                            <div class="col-md-1 d-flex align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="prisma_con_ciclo_oi" id="prisma_con_ciclo_oi" value="1" />
                                    <label class="form-check-label mb-0" for="prisma_con_ciclo_oi">Prisma</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="group mt-4">
                        <h5>Agudeza Visual</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6 class="text-primary">Ojo Derecho (OD)</h6>
                                <div class="form-group mb-2">
                                    <label for="av_intermedia_od">AV Intermedia:</label>
                                    <select id="av_intermedia_od" name="av_intermedia_od" class="form-control">
                                        <option value="">Seleccionar</option>
                                        <option value="20/20">20/20</option>
                                        <option value="20/25">20/25</option>
                                        <option value="20/30">20/30</option>
                                        <option value="20/40">20/40</option>
                                        <option value="20/50">20/50</option>
                                        <option value="20/60">20/60</option>
                                        <option value="20/70">20/70</option>
                                        <option value="20/80">20/80</option>
                                        <option value="20/100">20/100</option>
                                        <option value="20/200">20/200</option>
                                    </select>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="av_cercana_sin_corr_od">AV Cercana sin Corrección:</label>
                                    <select id="av_cercana_sin_corr_od" name="av_cercana_sin_corr_od" class="form-control">
                                        <option value="">Seleccionar</option>
                                        <option value="20/20">20/20</option>
                                        <option value="20/25">20/25</option>
                                        <option value="20/30">20/30</option>
                                        <option value="20/40">20/40</option>
                                        <option value="20/50">20/50</option>
                                        <option value="20/60">20/60</option>
                                        <option value="20/70">20/70</option>
                                        <option value="20/80">20/80</option>
                                        <option value="20/100">20/100</option>
                                        <option value="20/200">20/200</option>
                                    </select>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="av_cercana_con_corr_od">AV Cercana con Corrección:</label>
                                    <select id="av_cercana_con_corr_od" name="av_cercana_con_corr_od" class="form-control">
                                        <option value="">Seleccionar</option>
                                        <option value="20/20">20/20</option>
                                        <option value="20/25">20/25</option>
                                        <option value="20/30">20/30</option>
                                        <option value="20/40">20/40</option>
                                        <option value="20/50">20/50</option>
                                        <option value="20/60">20/60</option>
                                        <option value="20/70">20/70</option>
                                        <option value="20/80">20/80</option>
                                        <option value="20/100">20/100</option>
                                        <option value="20/200">20/200</option>
                                    </select>
                                </div>
                            </div>

                            <!-- OI -->
                            <div class="col-md-6">
                                <h6 class="text-primary">Ojo Izquierdo (OI)</h6>
                                <div class="form-group mb-2">
                                    <label for="av_intermedia_oi">AV Intermedia:</label>
                                    <select id="av_intermedia_oi" name="av_intermedia_oi" class="form-control">
                                        <option value="">Seleccionar</option>
                                        <option value="20/20">20/20</option>
                                        <option value="20/25">20/25</option>
                                        <option value="20/30">20/30</option>
                                        <option value="20/40">20/40</option>
                                        <option value="20/50">20/50</option>
                                        <option value="20/60">20/60</option>
                                        <option value="20/70">20/70</option>
                                        <option value="20/80">20/80</option>
                                        <option value="20/100">20/100</option>
                                        <option value="20/200">20/200</option>
                                    </select>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="av_cercana_sin_corr_oi">AV Cercana sin Corrección:</label>
                                    <select id="av_cercana_sin_corr_oi" name="av_cercana_sin_corr_oi" class="form-control">
                                        <option value="">Seleccionar</option>
                                        <option value="20/20">20/20</option>
                                        <option value="20/25">20/25</option>
                                        <option value="20/30">20/30</option>
                                        <option value="20/40">20/40</option>
                                        <option value="20/50">20/50</option>
                                        <option value="20/60">20/60</option>
                                        <option value="20/70">20/70</option>
                                        <option value="20/80">20/80</option>
                                        <option value="20/100">20/100</option>
                                        <option value="20/200">20/200</option>
                                    </select>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="av_cercana_con_corr_oi">AV Cercana con Corrección:</label>
                                    <select id="av_cercana_con_corr_oi" name="av_cercana_con_corr_oi" class="form-control">
                                        <option value="">Seleccionar</option>
                                        <option value="20/20">20/20</option>
                                        <option value="20/25">20/25</option>
                                        <option value="20/30">20/30</option>
                                        <option value="20/40">20/40</option>
                                        <option value="20/50">20/50</option>
                                        <option value="20/60">20/60</option>
                                        <option value="20/70">20/70</option>
                                        <option value="20/80">20/80</option>
                                        <option value="20/100">20/100</option>
                                        <option value="20/200">20/200</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4">
    <label for="detalle_ref_subjetiv"><strong>Detalles del Tratamiento:</strong></label>
    <div class="mb-2 d-flex gap-2">
        <button type="button" class="btn btn-danger btn-sm" id="re_sin_ciclo_grabar"><i class="fas fa-microphone"></i></button>
        <button type="button" class="btn btn-primary btn-sm" id="re_sin_ciclo_detener"><i class="fas fa-microphone-slash"></i></button>
        <button type="button" class="btn btn-success btn-sm" id="play_re_sin_ciclo"><i class="fas fa-play"></i></button>
    </div>
    <textarea class="form-control" name="detalle_ref_subjetiv" id="detalle_ref_subjetiv" rows="4" placeholder="Ej. Observación de agudeza visual, recomendación de lentes, seguimiento de tratamiento"></textarea>
</div>

<script>
    const grabarRefSubjetiv = document.getElementById('re_sin_ciclo_grabar');
    const detenerRefSubjetiv = document.getElementById('re_sin_ciclo_detener');
    const reproducirRefSubjetiv = document.getElementById('play_re_sin_ciclo');
    const textareaRefSubjetiv = document.getElementById('detalle_ref_subjetiv');

    const reconocimientoRefSubjetiv = new webkitSpeechRecognition();
    reconocimientoRefSubjetiv.lang = "es-ES";
    reconocimientoRefSubjetiv.continuous = true;
    reconocimientoRefSubjetiv.interimResults = false;

    reconocimientoRefSubjetiv.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length - 1][0].transcript;
        textareaRefSubjetiv.value += frase + ' ';
    };

    grabarRefSubjetiv.addEventListener('click', () => reconocimientoRefSubjetiv.start());
    detenerRefSubjetiv.addEventListener('click', () => reconocimientoRefSubjetiv.abort());

    reproducirRefSubjetiv.addEventListener('click', () => {
        const speech = new SpeechSynthesisUtterance(textareaRefSubjetiv.value);
        speech.volume = 1;
        speech.rate = 1;
        speech.pitch = 1;
        window.speechSynthesis.speak(speech);
    });
</script>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="accordion mt-3" id="refraccionCercanaAccordion">
        <div class="card" id="refraccionCercanaCard">
            <div class="card-header" id="headingRefraccionCercana">
                <h2 class="mb-0">
                    <button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#collapseRefraccionCercana" aria-expanded="false" aria-controls="collapseRefraccionCercana">
                        Ref. Subjetiva Cercana
                    </button>
                </h2>
            </div>

            <div id="collapseRefraccionCercana" class="collapse" aria-labelledby="headingRefraccionCercana" data-parent="#refraccionCercanaAccordion">
                <div class="card-body">
                    <h5>Ref. Subjetiva Cercana</h5>
                    <div class="row">
                        <!-- Ojo Derecho (OD) -->
<div class="col-md-6">
    <label class="d-block font-weight-bold mb-2">OD:</label>
    <div class="form-row">
        <div class="col-3 mb-2">
            <input type="text" class="form-control" name="esf_cerca_od" placeholder="Esf" />
        </div>
        <div class="col-3 mb-2">
            <input type="text" class="form-control" name="cil_cerca_od" placeholder="Cil" />
        </div>
        <div class="col-3 mb-2">
            <input type="text" class="form-control" name="eje_cerca_od" placeholder="Eje" />
        </div>
        <div class="col-3 mb-2">
            <input type="text" class="form-control" name="dip_cerca_od" placeholder="DIP" />
        </div>
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="prisma_cerca_od" name="prisma_cerca_od" value="1" />
        <label class="form-check-label" for="prisma_cerca_od">Prisma</label>
    </div>
</div>

                        <!-- Ojo Izquierdo (OI) -->
                        <div class="col-md-6">
                            <label class="d-block font-weight-bold mb-2">OI:</label>
                            <div class="form-row">
                                <div class="col-3 mb-2">
                                    <input type="text" class="form-control" name="esf_cerca_oi" placeholder="Esf" />
                                </div>
                                <div class="col-3 mb-2">
                                    <input type="text" class="form-control" name="cil_cerca_oi" placeholder="Cil" />
                                </div>
                                <div class="col-3 mb-2">
                                    <input type="text" class="form-control" name="eje_cerca_oi" placeholder="Eje" />
                                </div>
                                <div class="col-3 mb-2">
                                    <input type="text" class="form-control" name="dip_cerca_oi" placeholder="DIP" />
                                </div>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="prisma_cerca_oi" name="prisma_cerca_oi" value="1" />
                                <label class="form-check-label" for="prisma_cerca_oi">Prisma</label>
                            </div>
                        </div>
                    </div>

                </div>
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