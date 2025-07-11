<?php
session_start();
include "../../conexionbd.php";
include "../header_medico.php";

if (!isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

$usuario = $_SESSION['login'] ?? '';

if ($conexion) {
    $id_atencion = $_SESSION['hospital'];
    $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.folio, p.tel, p.fecnac, p.tip_san, di.fecha, di.area, di.alta_med, di.activo, p.sexo, di.alergias, p.ocup 
                FROM paciente p, dat_ingreso di
                WHERE p.Id_exp=di.Id_exp AND di.id_atencion = ?";
    $stmt = $conexion->prepare($sql_pac);
    $stmt->bind_param("i", $id_atencion);
    if (!$stmt->execute()) {
        die("Error SQL: " . $stmt->error);
    }
    $result_pac = $stmt->get_result();
    while ($row_pac = $result_pac->fetch_assoc()) {
        $pac_papell = $row_pac['papell'];
        $pac_sapell = $row_pac['sapell'];
        $pac_nom_pac = $row_pac['nom_pac'];
        $pac_folio = $row_pac['folio'];
        $pac_fecing = $row_pac['fecha'];
    }
    $stmt->close();
    $conexion->close();
} else {
    echo '<p style="color: red;">Error de conexión a la base de datos</p>';
}
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


<div class="container mt-4">
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
                    <div class="col-sm-4">Fecha de atención:
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
                    <div class="col-sm-4">Área: <strong><?php echo $num_cama .' - '.$area;?> </strong></div> 
                </div>
                <div class="row">
                    <div class="col-sm-8">
                        <?php echo $d ? "Diagnóstico: <strong>$d</strong>" : "Motivo de atención: <strong>$m</strong>"; 
                        ?>
                    </div>

                    <div class="col-sm-4">Alergias: <strong><?php echo $alergias; ?></strong></div>
                   
                    
                </div>

            </div>
        </div>
    </div><br>
    <div>
    <div class="thead"><strong>
                <center>PRUEBAS OFTALMOLÓGICAS</center>
            </strong></div>
    <form action="insertar_pruebas.php" method="POST" onsubmit="return checkSubmit();">

    <div class="form-group">
        <label><strong>Tipo de Prueba Oftalmológica</strong></label>
        <select class="form-control" name="tipo_prueba_oftalmologica" required>
            <option value="">Seleccione una prueba oftalmológica</option>
            <option value="Prueba de Agudeza Visual">Prueba de Agudeza Visual</option>
            <option value="Prueba de Ishihara">Prueba de Ishihara</option>
            <option value="Prueba de Amsler">Prueba de Amsler</option>
            <option value="Farnsworth-Munsell">Farnsworth-Munsell</option>
            <option value="TRPL">TRPL</option>
            <option value="Schirmer">Schirmer</option>
            <option value="Puntos de Worth">Puntos de Worth</option>
            <option value="Cover Test">Cover Test</option>
            <option value="Tinción con Fluoresceína">Tinción con Fluoresceína</option>
            <option value="Otras">Otras</option>
        </select>
    </div>


        <div class="form-row">
            <div class="form-group col-md-6">
                <label><strong>Resultado</strong></label>
                <input type="text" class="form-control" name="resultado" required>
            </div>
            <div class="form-group col-md-6">
                <label><strong>Fecha de la Prueba</strong></label>
                <input type="date" class="form-control" name="fecha_consulta" required>
            </div>
        </div>

        <div class="form-group">
    <label><strong>Observaciones</strong></label>
    <div class="mb-2 d-flex gap-2">
        <button type="button" class="btn btn-danger btn-sm" id="grabar_observaciones"><i class="fas fa-microphone"></i></button>
        <button type="button" class="btn btn-primary btn-sm" id="detener_observaciones"><i class="fas fa-microphone-slash"></i></button>
        <button type="button" class="btn btn-success btn-sm" id="reproducir_observaciones"><i class="fas fa-play"></i></button>
    </div>
    <textarea class="form-control" name="observaciones" id="observaciones_texto" rows="3" placeholder="Observaciones clínicas relevantes..."></textarea>
</div>

<script>
    const grabarObservaciones = document.getElementById('grabar_observaciones');
    const detenerObservaciones = document.getElementById('detener_observaciones');
    const reproducirObservaciones = document.getElementById('reproducir_observaciones');
    const textareaObservaciones = document.getElementById('observaciones_texto');

    const reconocimientoObservaciones = new webkitSpeechRecognition();
    reconocimientoObservaciones.lang = "es-ES";
    reconocimientoObservaciones.continuous = true;
    reconocimientoObservaciones.interimResults = false;

    reconocimientoObservaciones.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length - 1][0].transcript;
        textareaObservaciones.value += frase + ' ';
    };

    grabarObservaciones.addEventListener('click', () => reconocimientoObservaciones.start());
    detenerObservaciones.addEventListener('click', () => reconocimientoObservaciones.abort());

    reproducirObservaciones.addEventListener('click', () => {
        const speech = new SpeechSynthesisUtterance(textareaObservaciones.value);
        speech.volume = 1;
        speech.rate = 1;
        speech.pitch = 1;
        window.speechSynthesis.speak(speech);
    });
</script>

                    
        <!-- MENÚ TABS -->
        <ul class="nav nav-tabs" id="ojoTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="od-tab" data-toggle="tab" href="#od" role="tab" aria-controls="od" aria-selected="true">Ojo Derecho (OD)</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="oi-tab" data-toggle="tab" href="#oi" role="tab" aria-controls="oi" aria-selected="false">Ojo Izquierdo (OI)</a>
            </li>
        </ul>

        <div class="tab-content p-3 border border-top-0" id="ojoTabsContent">
            <!-- Ojo Derecho -->
            <div class="tab-pane fade show active" id="od" role="tabpanel" aria-labelledby="od-tab">
                <!-- Campos OD -->
                <div class="form-group">
                    <label><strong>Estrabismo</strong></label>
                    <input type="text" class="form-control" name="estrabismo_od">
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="radio" name="ojo_preferente" id="od_pref" value="OD">
                    <label class="form-check-label" for="od_pref">Ojo Preferente</label>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label><strong>Movimientos Oculares</strong></label>
                        <input type="text" class="form-control" name="mov_oculares_od">
                    </div>
                    <div class="form-group col-md-6">
                        <label><strong>Convergencia Ocular</strong></label>
                        <input type="text" class="form-control" name="convergencia_od">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label><strong>Prueba Cover</strong></label>
                        <input type="text" class="form-control" name="prueba_cover_od">
                    </div>
                    <div class="form-group col-md-6">
                        <label><strong>Visión Estereoscópica</strong></label>
                        <input type="text" class="form-control" name="vision_estereo_od">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label><strong>Puntos de Worth</strong></label>
                        <input type="text" class="form-control" name="worth_od">
                    </div>
                    <div class="form-group col-md-6">
                        <label><strong>Prueba de Schirmer (mm)</strong></label>
                        <input type="text" class="form-control" name="schirmer_od">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label><strong>TRPL (segundos)</strong></label>
                        <input type="text" class="form-control" name="trpl_od">
                    </div>
                    <div class="form-group col-md-6">
                        <label><strong>Tinción con Fluoresceína</strong></label>
                        <input type="text" class="form-control" name="fluoresceina_od">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label><strong>Sensibilidad al Contraste</strong></label>
                        <input type="text" class="form-control" name="contraste_od">
                    </div>
                    <div class="form-group col-md-6">
                        <label><strong>Prueba Ishihara</strong></label>
                        <input type="text" class="form-control" name="ishihara_od">
                    </div>
                </div>

                <div class="form-group">
                    <label><strong>Prueba Farnsworth-Munsell</strong></label>
                    <input type="text" class="form-control" name="farnsworth_od">
                </div>

                <div class="form-group">
                    <label><strong>Prueba Amsler</strong></label>
                    <select class="form-control" name="amsler_od">
                        <option value="">Resultado</option>
                        <option value="Normal">Normal</option>
                        <option value="Anormal">Anormal</option>
                    </select>
                </div>

            </div>

            <!-- Ojo Izquierdo -->
            <div class="tab-pane fade" id="oi" role="tabpanel" aria-labelledby="oi-tab">
                <!-- Campos OI -->
                <div class="form-group">
                    <label><strong>Estrabismo</strong></label>
                    <input type="text" class="form-control" name="estrabismo_oi">
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="radio" name="ojo_preferente" id="oi_pref" value="OI">
                    <label class="form-check-label" for="oi_pref">Ojo Preferente</label>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label><strong>Movimientos Oculares</strong></label>
                        <input type="text" class="form-control" name="mov_oculares_oi">
                    </div>
                    <div class="form-group col-md-6">
                        <label><strong>Convergencia Ocular</strong></label>
                        <input type="text" class="form-control" name="convergencia_oi">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label><strong>Prueba Cover</strong></label>
                        <input type="text" class="form-control" name="prueba_cover_oi">
                    </div>
                    <div class="form-group col-md-6">
                        <label><strong>Visión Estereoscópica</strong></label>
                        <input type="text" class="form-control" name="vision_estereo_oi">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label><strong>Puntos de Worth</strong></label>
                        <input type="text" class="form-control" name="worth_oi">
                    </div>
                    <div class="form-group col-md-6">
                        <label><strong>Prueba de Schirmer (mm)</strong></label>
                        <input type="text" class="form-control" name="schirmer_oi">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label><strong>TRPL (segundos)</strong></label>
                        <input type="text" class="form-control" name="trpl_oi">
                    </div>
                    <div class="form-group col-md-6">
                        <label><strong>Tinción con Fluoresceína</strong></label>
                        <input type="text" class="form-control" name="fluoresceina_oi">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label><strong>Sensibilidad al Contraste</strong></label>
                        <input type="text" class="form-control" name="contraste_oi">
                    </div>
                    <div class="form-group col-md-6">
                        <label><strong>Prueba Ishihara</strong></label>
                        <input type="text" class="form-control" name="ishihara_oi">
                    </div>
                </div>

                <div class="form-group">
                    <label><strong>Prueba Farnsworth-Munsell</strong></label>
                    <input type="text" class="form-control" name="farnsworth_oi">
                </div>

                <div class="form-group">
                    <label><strong>Prueba Amsler</strong></label>
                    <select class="form-control" name="amsler_oi">
                        <option value="">Resultado</option>
                        <option value="Normal">Normal</option>
                        <option value="Anormal">Anormal</option>
                    </select>
                </div>
</div>

                <div class="form-group mt-3">
    <label for="detalle_prueba"><strong>Detalles del Tratamiento:</strong></label>
    <div class="botones mb-2">
        <button type="button" class="btn btn-danger btn-sm" id="detalle_prueba_grabar"><i class="fas fa-microphone"></i></button>
        <button type="button" class="btn btn-primary btn-sm" id="detalle_prueba_detener"><i class="fas fa-microphone-slash"></i></button>
        <button type="button" class="btn btn-success btn-sm" id="play_detalle_prueba"><i class="fas fa-play"></i></button>
    </div>
    <textarea class="form-control" name="detalle_prueba" id="detalle_prueba" rows="4" placeholder="Ej. Observación de agudeza visual, recomendación de lentes, seguimiento de tratamiento"></textarea>
</div>

<script>
    const detalle_prueba_grabar = document.getElementById('detalle_prueba_grabar');
    const detalle_prueba_detener = document.getElementById('detalle_prueba_detener');
    const detalle_prueba = document.getElementById('detalle_prueba');
    const btn_play_detalle_prueba = document.getElementById('play_detalle_prueba');

    btn_play_detalle_prueba.addEventListener('click', () => {
        leerTexto(detalle_prueba.value);
    });

    let recognition_detalle_prueba = new webkitSpeechRecognition();
    recognition_detalle_prueba.lang = "es-ES";
    recognition_detalle_prueba.continuous = true;
    recognition_detalle_prueba.interimResults = false;
    recognition_detalle_prueba.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length - 1][0].transcript;
        detalle_prueba.value += frase;
    };

    detalle_prueba_grabar.addEventListener('click', () => {
        recognition_detalle_prueba.start();
    });

    detalle_prueba_detener.addEventListener('click', () => {
        recognition_detalle_prueba.abort();
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