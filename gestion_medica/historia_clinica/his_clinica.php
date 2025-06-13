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
    <title>Historia Clinica - Instituto de Enfermedades Oculares</title>
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
<div class="container">
    <div class="thead"><strong>
            <center>HISTORIAL CLINICO</center>
        </strong></div>
    <form action="insertar_his_cli.php" method="POST" onsubmit="return checkSubmit();">
        <div class="card-header" id="headingRight">
            <div class="accordion mt-3" id="eyeAccordion">
                <div class="card" id="ojoderecho">
                    <div class="card-header">
                        <h2 class="mb-0 d-flex flex-wrap gap-2">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne"
                                aria-expanded="true" aria-controls="collapseOne" style="color:#2b2d7f;">
                                Motivo de Consulta
                            </button>
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTwo"
                                aria-expanded="false" aria-controls="collapseTwo" style="color:#2b2d7f;">
                                Sintomatología Ocular
                            </button>
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseHerFam"
                                aria-expanded="false" aria-controls="collapseHerFam" style="color:#2b2d7f;">
                                Antecedentes Heredo Familiares
                            </button>
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseNoPat"
                                aria-expanded="false" aria-controls="collapseNoPat" style="color:#2b2d7f;">
                                Antecedentes Personales no Patológicos
                            </button>
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapsePat"
                                aria-expanded="false" aria-controls="collapsePat" style="color:#2b2d7f;">
                                Antecedentes Personales Patológicos
                            </button>
                        </h2>
                    </div>

<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#eyeAccordion">
    <div class="card-body">
        <div class="form-group">
            <label for="observaciones_texto" style="color:#2b2d7f; font-weight:bold;">Motivo de Consulta</label>
            <div class="botones mb-2">
                <button type="button" class="btn btn-danger btn-sm" id="grabar_motivo"><i class="fas fa-microphone"></i></button>
                <button type="button" class="btn btn-primary btn-sm" id="detener_motivo"><i class="fas fa-microphone-slash"></i></button>
                <button type="button" class="btn btn-success btn-sm" id="reproducir_motivo"><i class="fas fa-play"></i></button>
            </div>
            <textarea class="form-control" name="observaciones" id="observaciones_texto" rows="3" placeholder="Motivo de consulta del paciente."></textarea>
            <small style="color:#2b2d7f; font-weight:bold;">Historial</small>
        </div>
    </div>
</div>

<script>
    const grabar = document.getElementById('grabar_motivo');
    const detener = document.getElementById('detener_motivo');
    const reproducir = document.getElementById('reproducir_motivo');
    const campoTexto = document.getElementById('observaciones_texto');

    reproducir.addEventListener('click', () => {
        const speech = new SpeechSynthesisUtterance(campoTexto.value);
        window.speechSynthesis.speak(speech);
    });

    const reconocimiento = new webkitSpeechRecognition();
    reconocimiento.lang = "es-ES";
    reconocimiento.continuous = true;
    reconocimiento.interimResults = false;

    reconocimiento.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length - 1][0].transcript;
        campoTexto.value += frase + ' ';
    };

    grabar.addEventListener('click', () => reconocimiento.start());
    detener.addEventListener('click', () => reconocimiento.abort());
</script>

                    <div id="collapseTwo" class="collapse" aria-labelledby="headingOne" data-parent="#eyeAccordion">
                        <div class="card-body">
                            <div class="form-group">
                                <label style="color:#2b2d7f; font-weight:bold;">Sintomatología Ocular</label>
                                <div>
                                    <label><input type="checkbox" name="sinto[]" value="Ardor ocular"> Ardor ocular</label>
                                    <label><input type="checkbox" name="sinto[]" value="Cefalea"> Cefalea</label>
                                    <label><input type="checkbox" name="sinto[]" value="Diplopía"> Diplopía</label>
                                    <label><input type="checkbox" name="sinto[]" value="Dolor"> Dolor</label>
                                    <label><input type="checkbox" name="sinto[]" value="Edema palpebral"> Edema palpebral</label>
                                    <label><input type="checkbox" name="sinto[]" value="Fotopsias"> Fotopsias</label>
                                    <label><input type="checkbox" name="sinto[]" value="Miodesopsias"> Miodesopsias</label>
                                    <label><input type="checkbox" name="sinto[]" value="Ojo Rojo"> Ojo Rojo</label>
                                    <label><input type="checkbox" name="sinto[]" value="Ojo Seco"> Ojo Seco</label>
                                    <label><input type="checkbox" name="sinto[]" value="Prurito"> Prurito</label>
                                    <label><input type="checkbox" name="sinto[]" value="Cuerpo extraño"> Cuerpo extraño</label>
                                    <label><input type="checkbox" name="sinto[]" value="Tumoración"> Tumoración</label>
                                    <label><input type="checkbox" name="sinto[]" value="Visión borrosa lejos"> Visión borrosa lejos</label>
                                    <label><input type="checkbox" name="sinto[]" value="Visión borrosa cerca"> Visión borrosa cerca</label>
                                    <label><input type="checkbox" name="sinto[]" value="Lagrimeo"> Lagrimeo</label>
                                    <label><input type="checkbox" name="sinto[]" value="Secreción"> Secreción</label>
                                </div>
                                <div class="mt-2">
    <label style="color:#2b2d7f; font-weight:bold;">Otros</label>
    <div class="botones mb-2">
        <button type="button" class="btn btn-danger btn-sm" id="grabar_otros"><i class="fas fa-microphone"></i></button>
        <button type="button" class="btn btn-primary btn-sm" id="detener_otros"><i class="fas fa-microphone-slash"></i></button>
        <button type="button" class="btn btn-success btn-sm" id="reproducir_otros"><i class="fas fa-play"></i></button>
    </div>
    <textarea class="form-control" name="sinto_otros" id="otros_texto" rows="1" placeholder="Otros síntomas o notas adicionales."></textarea>
</div>

                            </div>
                        </div>
                    </div>
                   <script>
    const grabarOtros = document.getElementById('grabar_otros');
    const detenerOtros = document.getElementById('detener_otros');
    const reproducirOtros = document.getElementById('reproducir_otros');
    const campoOtros = document.getElementById('otros_texto');

    reproducirOtros.addEventListener('click', () => {
        const speech = new SpeechSynthesisUtterance(campoOtros.value);
        window.speechSynthesis.speak(speech);
    });

    const reconocimientoOtros = new webkitSpeechRecognition();
    reconocimientoOtros.lang = "es-ES";
    reconocimientoOtros.continuous = true;
    reconocimientoOtros.interimResults = false;

    reconocimientoOtros.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length - 1][0].transcript;
        campoOtros.value += frase + ' ';
    };

    grabarOtros.addEventListener('click', () => reconocimientoOtros.start());
    detenerOtros.addEventListener('click', () => reconocimientoOtros.abort());
</script>
        
                    <div id="collapseHerFam" class="collapse" aria-labelledby="headingHerFam" data-parent="#eyeAccordion">
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" style="color:#2b2d7f; font-weight:bold;">Antecedentes Heredo Familiares</label>
                                <div class="col-sm-9" style="display:flex; align-items:center; flex-wrap:wrap;">
                                    <label style="margin-right:10px;"><input type="checkbox" name="heredo[]" value="Negados" checked> <strong>Negados</strong></label>
                                    <label style="margin-right:10px;"><input type="checkbox" name="heredo[]" value="Diabetes Mellitus"> Diabetes Mellitus</label>
                                    <label style="margin-right:10px;"><input type="checkbox" name="heredo[]" value="Hipertensión Arterial"> Hipertensión Arterial</label>
                                    <label style="margin-right:10px;"><input type="checkbox" name="heredo[]" value="Cáncer"> Cáncer</label>
                                    <label style="margin-right:10px;"><input type="checkbox" name="heredo[]" value="Glaucoma"> Glaucoma</label>
                                    <label style="margin-right:10px;"><input type="checkbox" name="heredo[]" value="Catarata"> Catarata</label>
                                    <label style="margin-right:10px;"><input type="checkbox" name="heredo[]" value="Otros"> Otros</label>
                                </div>
                            </div>
                            <div class="form-group row">
    <label class="col-sm-3 col-form-label" style="color:#2b2d7f; font-weight:bold;">Otros</label>
    <div class="col-sm-9">
        <div class="botones mb-2">
            <button type="button" class="btn btn-danger btn-sm" id="grabar_otrosh"><i class="fas fa-microphone"></i></button>
            <button type="button" class="btn btn-primary btn-sm" id="detener_otrosh"><i class="fas fa-microphone-slash"></i></button>
            <button type="button" class="btn btn-success btn-sm" id="reproducir_otrosh"><i class="fas fa-play"></i></button>
        </div>
        <textarea class="form-control" name="heredo_otros" id="otrosh_texto" rows="2" placeholder="Otros antecedentes heredofamiliares..."></textarea>
    </div>
</div>
                        </div>
                    </div>
<script>
    const grabarOtrosh = document.getElementById('grabar_otrosh');
    const detenerOtrosh = document.getElementById('detener_otrosh');
    const reproducirOtrosh = document.getElementById('reproducir_otrosh');
    const campoOtrosh = document.getElementById('otrosh_texto');

    reproducirOtrosh.addEventListener('click', () => {
        const speech = new SpeechSynthesisUtterance(campoOtrosh.value);
        window.speechSynthesis.speak(speech);
    });

    const reconocimientoOtrosh = new webkitSpeechRecognition();
    reconocimientoOtrosh.lang = "es-ES";
    reconocimientoOtrosh.continuous = true;
    reconocimientoOtrosh.interimResults = false;

    reconocimientoOtrosh.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length - 1][0].transcript;
        campoOtrosh.value += frase + ' ';
    };

    grabarOtrosh.addEventListener('click', () => reconocimientoOtrosh.start());
    detenerOtrosh.addEventListener('click', () => reconocimientoOtrosh.abort());
</script>
                    <div id="collapseNoPat" class="collapse" aria-labelledby="headingNoPat" data-parent="#eyeAccordion">
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" style="color:#2b2d7f; font-weight:bold;">Antecedentes Personales no Patológicos</label>
                                <div class="col-sm-8" style="display:flex; align-items:center; flex-wrap:wrap;">
                                    <label style="margin-right:10px;"><input type="checkbox" name="nopat[]" value="Negados" checked> <strong>Negados</strong></label>
                                    <label style="margin-right:10px;"><input type="checkbox" name="nopat[]" value="Tabaquismo"> Tabaquismo</label>
                                    <label style="margin-right:10px;"><input type="checkbox" name="nopat[]" value="Adicciones"> Adicciones</label>
                                    <label style="margin-right:10px;"><input type="checkbox" name="nopat[]" value="Sobrepeso"> Sobrepeso</label>
                                    <label style="margin-right:10px;"><input type="checkbox" name="nopat[]" value="Sedentarismo"> Sedentarismo</label>
                                    <label style="margin-right:10px;"><input type="checkbox" name="nopat[]" value="Vacuna COVID19"> Vacuna (COVID19)</label>
                                    <span style="margin-left:5px; color:#888;">Seleccionar</span>
                                </div>
                            </div>
                            <div class="form-group row">
    <label class="col-sm-3 col-form-label" style="color:#2b2d7f; font-weight:bold;">Otros</label>
    <div class="col-sm-9">
        <div class="botones mb-2">
            <button type="button" class="btn btn-danger btn-sm" id="grabar_otrosa"><i class="fas fa-microphone"></i></button>
            <button type="button" class="btn btn-primary btn-sm" id="detener_otrosa"><i class="fas fa-microphone-slash"></i></button>
            <button type="button" class="btn btn-success btn-sm" id="reproducir_otrosa"><i class="fas fa-play"></i></button>
        </div>
        <textarea class="form-control" name="personales_otros" id="otrosa_texto" rows="2" placeholder="Otros antecedentes no personales patológicos..."></textarea>
    </div>
</div>
                        </div>
                    </div>
    <script>
    const grabarOtrosa = document.getElementById('grabar_otrosa');
    const detenerOtrosa = document.getElementById('detener_otrosa');
    const reproducirOtrosa = document.getElementById('reproducir_otrosa');
    const campoOtrosa = document.getElementById('otrosa_texto');

    reproducirOtrosa.addEventListener('click', () => {
        const speech = new SpeechSynthesisUtterance(campoOtrosa.value);
        window.speechSynthesis.speak(speech);
    });

    const reconocimientoOtrosa = new webkitSpeechRecognition();
    reconocimientoOtrosa.lang = "es-ES";
    reconocimientoOtrosa.continuous = true;
    reconocimientoOtrosa.interimResults = false;

    reconocimientoOtrosa.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length - 1][0].transcript;
        campoOtrosa.value += frase + ' ';
    };

    grabarOtrosa.addEventListener('click', () => reconocimientoOtrosa.start());
    detenerOtrosa.addEventListener('click', () => reconocimientoOtrosa.abort());
</script>
                    <div id="collapsePat" class="collapse" aria-labelledby="headingPat" data-parent="#eyeAccordion">
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" style="color:#2b2d7f; font-weight:bold;">
                                    Antecedentes Personales Patológicos
                                </label>
                                <div class="col-sm-8" style="display:flex; align-items:center; flex-wrap:wrap;">
                                    <label style="margin-right:10px;">
                                        <input type="checkbox" name="pat_interrogados" value="Interrogados y negados"> <strong>Interrogados y negados</strong>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" style="font-weight:bold;">Enfermedades</label>
                                <div class="col-sm-10" style="display:flex; align-items:center; flex-wrap:wrap;">
                                    <label style="margin-right:10px;"><input type="checkbox" name="pat_enf[]" value="Diabetes Mellitus"> Diabetes Mellitus</label>
                                    <label style="margin-right:10px;"><input type="checkbox" name="pat_enf[]" value="Hipertensión Arterial"> Hipertensión Arterial</label>
                                    <label style="margin-right:10px;"><input type="checkbox" name="pat_enf[]" value="Cáncer"> Cáncer</label>
                                    <label style="margin-right:10px;"><input type="checkbox" name="pat_enf[]" value="Tiroides"> Tiroides</label>
                                    <label style="margin-right:10px;"><input type="checkbox" name="pat_enf[]" value="Hiperlipidemia"> Hiperlipidemia</label>
                                    <label style="margin-right:10px;"><input type="checkbox" name="pat_enf[]" value="IAM"> IAM</label>
                                    <label style="margin-right:10px;"><input type="checkbox" name="pat_enf[]" value="Problemas Respiratorios"> Problemas Respiratorios</label>
                                    <label style="margin-right:10px;"><input type="checkbox" name="pat_enf[]" value="COVID19"> COVID19</label>
                                    <label style="margin-right:10px;"><input type="checkbox" name="pat_enf[]" value="Transfusiones"> Transfusiones</label>
                                    <label style="margin-right:10px;"><input type="checkbox" name="pat_enf[]" value="AR"> AR</label>
                                    <label style="margin-right:10px;"><input type="checkbox" name="pat_enf[]" value="LUPUS"> LUPUS</label>
                                    <label style="margin-right:10px;"><input type="checkbox" name="pat_enf[]" value="Renal"> Renal</label>
                                    <label style="margin-right:10px;"><input type="checkbox" name="pat_enf[]" value="Otros"> Otros</label>
                                    <label style="margin-right:10px;"><input type="checkbox" name="pat_enf[]" value="Otros(1)"> Otros(1)</label>
                                    <label style="margin-right:10px;"><input type="checkbox" name="pat_enf[]" value="Otros(2)"> Otros(2)</label>
                                    <label style="margin-right:10px;"><input type="checkbox" name="pat_enf[]" value="Otros(3)"> Otros(3)</label>
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-sm-2 col-form-label" style="font-weight:bold;">Medicamentos</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="pat_medicamentos[]" placeholder="Medicamentos">
                                </div>
                                <div class="col-sm-2">
                                    <a href="#" onclick="addInput('pat_medicamentos', this); return false;">Añadir</a>
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-sm-2 col-form-label" style="font-weight:bold;">Otras alergias</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="pat_otras_alergias[]" placeholder="Otras alergias">
                                </div>
                                <div class="col-sm-2">
                                    <a href="#" onclick="addInput('pat_otras_alergias', this); return false;">Añadir</a>
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-sm-2 col-form-label" style="font-weight:bold;">Oculares</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="pat_oculares[]" placeholder="Oculares">
                                </div>
                                <div class="col-sm-2">
                                    <a href="#" onclick="addInput('pat_oculares', this); return false;">Añadir</a>
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-sm-2 col-form-label" style="font-weight:bold;">Otras cirugías</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="pat_otras_cirugias[]" placeholder="Otras cirugías">
                                </div>
                                <div class="col-sm-2">
                                    <a href="#" onclick="addInput('pat_otras_cirugias', this); return false;">Añadir</a>
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
    <script>
function addInput(name, el) {
    var div = document.createElement('div');
    div.className = "mt-2";
    div.innerHTML = '<input type="text" class="form-control" name="'+name+'[]" placeholder="'+el.parentNode.previousElementSibling.firstElementChild.placeholder+'">';
    el.parentNode.parentNode.parentNode.insertBefore(div, el.parentNode.parentNode.nextSibling);
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