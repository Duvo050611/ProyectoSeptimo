<?php
session_start();
include "../../conexionbd.php";
if (!isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}
include("../header_medico.php");

// Fetch previous treatments for the dropdown
$previous_treatments = [];
if ($conexion) {
    $id_atencion = $_SESSION['hospital'];
    $stmt = $conexion->prepare("SELECT Id_exp FROM dat_ingreso WHERE id_atencion = ?");
    $stmt->bind_param("i", $id_atencion);
    $stmt->execute();
    $result = $stmt->get_result();
    $id_exp = $result->fetch_assoc()['Id_exp'];
    $stmt->close();

    if ($id_exp) {
        $stmt = $conexion->prepare("SELECT tratamiento_previo_derecho, tratamiento_previo_izquierdo FROM ocular_tratamiento WHERE Id_exp = ? AND id_atencion != ?");
        $stmt->bind_param("si", $id_exp, $id_atencion);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            if (!empty($row['tratamiento_previo_derecho'])) {
                $previous_treatments[] = $row['tratamiento_previo_derecho'];
            }
            if (!empty($row['tratamiento_previo_izquierdo'])) {
                $previous_treatments[] = $row['tratamiento_previo_izquierdo'];
            }
        }
        $stmt->close();
    }
    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
    <title>REGISTRO DE TRATAMIENTO</title>
    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <link href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="js/select2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldLv/Pr4nhuBviF5jGqQK/5i2Q5iZ64dxBl+zOZ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/main.js"></script>
    <style>
    .modal-lg { max-width: 70% !important; }
    .botones { margin-bottom: 5px; }
    .thead { background-color: #2b2d7f; color: white; font-size: 22px; padding: 10px; text-align: center; }
    .table-tratamientos { margin-top: 10px; }
    .table-tratamientos th, .table-tratamientos td { vertical-align: middle; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="thead"><strong><center>HISTORIA CLÍNICA</center></strong></div>
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
                    <div class="col-sm-2">Expediente: <strong><?php echo $folio; ?></strong></div>
                    <div class="col-sm-6">Paciente: <strong><?php echo $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac; ?></strong></div>
                    <div class="col-sm-4">Fecha de ingreso: <strong><?php echo date_format(date_create($pac_fecing), "d/m/Y H:i:s"); ?></strong></div>
                </div>
                <div class="row">
                    <div class="col-sm-4">Fecha de nacimiento: <strong><?php echo date_format(date_create($pac_fecnac), "d/m/Y"); ?></strong></div>
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
                    <div class="col-sm-8"><?php echo $d ? "Diagnóstico: <strong>$d</strong>" : "Motivo de atención: <strong>$m</strong>"; ?></div>
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
    </div>
    <br><br>
    <div class="container">
        <div class="thead"><strong><center>TRATAMIENTO</center></strong></div>
        <form action="insertar_tratamiento.php" method="POST" onsubmit="return checkSubmit();">
            <div class="form-group mt-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="oftalmologicamente_sano" id="oftalmologicamente_sano" value="1">
                    <label class="form-check-label" for="oftalmologicamente_sano">Oftalmológicamente Sano</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="sin_tratamiento" id="sin_tratamiento" value="1">
                    <label class="form-check-label" for="sin_tratamiento">Sin Tratamiento</label>
                </div>
            </div>
            <div class="form-group" id="usar_tratamientos_previos_section">
                <label for="tratamiento_previo"><strong>Usar Tratamientos Previos:</strong></label>
                <select class="form-control" name="tratamiento_previo_derecho" id="tratamiento_previo_derecho">
                    <option value="">Seleccionar</option>
                    <?php
                    $unique_treatments = array_unique($previous_treatments);
                    foreach ($unique_treatments as $treat) {
                        echo "<option value=\"" . htmlspecialchars($treat) . "\">" . htmlspecialchars($treat) . "</option>";
                    }
                    ?>
                </select>
                <select class="form-control mt-2" name="tratamiento_previo_izquierdo" id="tratamiento_previo_izquierdo">
                    <option value="">Seleccionar</option>
                    <?php
                    foreach ($unique_treatments as $treat) {
                        echo "<option value=\"" . htmlspecialchars($treat) . "\">" . htmlspecialchars($treat) . "</option>";
                    }
                    ?>
                </select>
                <script>
                $(document).ready(function() {
                    $('#tratamiento_previo_derecho, #tratamiento_previo_izquierdo').select2();
                });
                </script>
            </div>
            <div class="accordion mt-3" id="eyeAccordion">
                <div class="card">
                    <div class="card-header" id="headingRight">
                        <h2 class="mb-0">
                            <button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#collapseRight" aria-expanded="true" aria-controls="collapseRight">
                                Ojo Derecho
                            </button>
                        </h2>
                    </div>
                    <div id="collapseRight" class="collapse show" aria-labelledby="headingRight" data-parent="#eyeAccordion">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="medicamento_derecho"><strong>Medicamento:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="med_derecho_grabar"><i class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="med_derecho_detener"><i class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_med_derecho"><i class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" id="medicamento_derecho_input" rows="4" placeholder="Ej. Gotas de timolol 0.5%"></textarea>
                                <button type="button" class="btn btn-secondary btn-sm mt-2" id="add_medicamento_derecho">Añadir</button>
                                <table class="table table-bordered table-medicamentos" id="medicamento_derecho_table" style="display: none;">
                                    <thead>
                                        <tr>
                                            <th>Medicamento</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <script>
                                let med_derecho_count = 0;
                                $('#add_medicamento_derecho').click(function() {
                                    const medicamento = $('#medicamento_derecho_input').val().trim();
                                    if (!medicamento) {
                                        alert('Por favor, ingrese un medicamento antes de añadir.');
                                        return;
                                    }
                                    const newRow = `
                                        <tr data-index="${med_derecho_count}">
                                            <td>${medicamento}</td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm edit-med-derecho">Editar</button>
                                                <button type="button" class="btn btn-danger btn-sm delete-med-derecho">Eliminar</button>
                                            </td>
                                        </tr>
                                    `;
                                    $('#medicamento_derecho_table tbody').append(newRow);
                                    $('#medicamento_derecho_table').show();
                                    $('#medicamento_derecho_input').val('');
                                    med_derecho_count++;
                                });

                                $(document).on('click', '.edit-med-derecho', function() {
                                    const row = $(this).closest('tr');
                                    const medicamento = row.find('td:first').text();
                                    $('#medicamento_derecho_input').val(medicamento);
                                    row.remove();
                                    med_derecho_count--;
                                    if (med_derecho_count === 0) {
                                        $('#medicamento_derecho_table').hide();
                                    }
                                });

                                $(document).on('click', '.delete-med-derecho', function() {
                                    $(this).closest('tr').remove();
                                    med_derecho_count--;
                                    if (med_derecho_count === 0) {
                                        $('#medicamento_derecho_table').hide();
                                    }
                                });

                                const med_derecho_grabar = document.getElementById('med_derecho_grabar');
                                const med_derecho_detener = document.getElementById('med_derecho_detener');
                                const medicamento_derecho_input = document.getElementById('medicamento_derecho_input');
                                const btn_med_derecho = document.getElementById('play_med_derecho');
                                btn_med_derecho.addEventListener('click', () => {
                                    leerTexto(medicamento_derecho_input.value);
                                });
                                let recognition_med_derecho = new webkitSpeechRecognition();
                                recognition_med_derecho.lang = "es-ES";
                                recognition_med_derecho.continuous = true;
                                recognition_med_derecho.interimResults = false;
                                recognition_med_derecho.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    medicamento_derecho_input.value += frase;
                                };
                                med_derecho_grabar.addEventListener('click', () => {
                                    recognition_med_derecho.start();
                                });
                                med_derecho_detener.addEventListener('click', () => {
                                    recognition_med_derecho.abort();
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
                                <label><strong>Código Tratamiento:</strong></label>
                                <input type="text" class="form-control" name="codigo_tratamiento_derecho" id="codigo_tratamiento_derecho" placeholder="Ej. T123">
                                <div class="botones mt-2">
                                    <button type="button" class="btn btn-danger btn-sm" id="desc_trat_derecho_grabar"><i class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="desc_trat_derecho_detener"><i class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_desc_trat_derecho"><i class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control mt-2" name="desc_tratamiento_derecho" id="desc_tratamiento_derecho" rows="2" placeholder="Ej. Timolol 0.5%, 1 gota cada 12 horas"></textarea>
                                <script>
                                const desc_trat_derecho_grabar = document.getElementById('desc_trat_derecho_grabar');
                                const desc_trat_derecho_detener = document.getElementById('desc_trat_derecho_detener');
                                const desc_tratamiento_derecho = document.getElementById('desc_tratamiento_derecho');
                                const btn_desc_trat_derecho = document.getElementById('play_desc_trat_derecho');
                                btn_desc_trat_derecho.addEventListener('click', () => {
                                    leerTexto(desc_tratamiento_derecho.value);
                                });
                                let recognition_desc_trat_derecho = new webkitSpeechRecognition();
                                recognition_desc_trat_derecho.lang = "es-ES";
                                recognition_desc_trat_derecho.continuous = true;
                                recognition_desc_trat_derecho.interimResults = false;
                                recognition_desc_trat_derecho.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    desc_tratamiento_derecho.value += frase;
                                };
                                desc_trat_derecho_grabar.addEventListener('click', () => {
                                    recognition_desc_trat_derecho.start();
                                });
                                desc_trat_derecho_detener.addEventListener('click', () => {
                                    recognition_desc_trat_derecho.abort();
                                });
                                </script>
                            </div>
                            <div class="form-group">
                                <label><strong>Primera Vez / Subsecuente:</strong></label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipo_tratamiento_derecho" id="primera_vez_derecho" value="Primera Vez">
                                    <label class="form-check-label" for="primera_vez_derecho">Primera Vez</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipo_tratamiento_derecho" id="subsecuente_derecho" value="Subsecuente">
                                    <label class="form-check-label" for="subsecuente_derecho">Subsecuente</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="procedimientos_derecho_input"><strong>Procedimientos:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="proc_derecho_grabar"><i class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="proc_derecho_detener"><i class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_proc_derecho"><i class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" id="procedimientos_derecho_input" rows="2" placeholder="Ej. Tonometría"></textarea>
                                <button type="button" class="btn btn-secondary btn-sm mt-2" id="add_procedimiento_derecho">Añadir</button>
                                <table class="table table-bordered table-procedimientos" id="procedimientos_derecho_table" style="display: none;">
                                    <thead>
                                        <tr>
                                            <th>Procedimiento</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <script>
                                let proc_derecho_count = 0;
                                $('#add_procedimiento_derecho').click(function() {
                                    const procedimiento = $('#procedimientos_derecho_input').val().trim();
                                    if (!procedimiento) {
                                        alert('Por favor, ingrese un procedimiento antes de añadir.');
                                        return;
                                    }
                                    const newRow = `
                                        <tr data-index="${proc_derecho_count}">
                                            <td>${procedimiento}</td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm edit-proc-derecho">Editar</button>
                                                <button type="button" class="btn btn-danger btn-sm delete-proc-derecho">Eliminar</button>
                                            </td>
                                        </tr>
                                    `;
                                    $('#procedimientos_derecho_table tbody').append(newRow);
                                    $('#procedimientos_derecho_table').show();
                                    $('#procedimientos_derecho_input').val('');
                                    proc_derecho_count++;
                                });

                                $(document).on('click', '.edit-proc-derecho', function() {
                                    const row = $(this).closest('tr');
                                    const procedimiento = row.find('td:first').text();
                                    $('#procedimientos_derecho_input').val(procedimiento);
                                    row.remove();
                                    proc_derecho_count--;
                                    if (proc_derecho_count === 0) {
                                        $('#procedimientos_derecho_table').hide();
                                    }
                                });

                                $(document).on('click', '.delete-proc-derecho', function() {
                                    $(this).closest('tr').remove();
                                    proc_derecho_count--;
                                    if (proc_derecho_count === 0) {
                                        $('#procedimientos_derecho_table').hide();
                                    }
                                });

                                const proc_derecho_grabar = document.getElementById('proc_derecho_grabar');
                                const proc_derecho_detener = document.getElementById('proc_derecho_detener');
                                const procedimientos_derecho_input = document.getElementById('procedimientos_derecho_input');
                                const btn_proc_derecho = document.getElementById('play_proc_derecho');
                                btn_proc_derecho.addEventListener('click', () => {
                                    leerTexto(procedimientos_derecho_input.value);
                                });
                                let recognition_proc_derecho = new webkitSpeechRecognition();
                                recognition_proc_derecho.lang = "es-ES";
                                recognition_proc_derecho.continuous = true;
                                recognition_proc_derecho.interimResults = false;
                                recognition_proc_derecho.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    procedimientos_derecho_input.value += frase;
                                };
                                proc_derecho_grabar.addEventListener('click', () => {
                                    recognition_proc_derecho.start();
                                });
                                proc_derecho_detener.addEventListener('click', () => {
                                    recognition_proc_derecho.abort();
                                });
                                </script>
                            </div>
                            <div class="form-group">
                                <label for="quirurgico_derecho"><strong>Quirúrgico:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="quir_derecho_grabar"><i class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="quir_derecho_detener"><i class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_quir_derecho"><i class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" name="quirurgico_derecho" id="quirurgico_derecho" rows="4" placeholder="Ej. Trabeculectomía programada"></textarea>
                                <script>
                                const quir_derecho_grabar = document.getElementById('quir_derecho_grabar');
                                const quir_derecho_detener = document.getElementById('quir_derecho_detener');
                                const quirurgico_derecho = document.getElementById('quirurgico_derecho');
                                const btn_quir_derecho = document.getElementById('play_quir_derecho');
                                btn_quir_derecho.addEventListener('click', () => {
                                    leerTexto(quirurgico_derecho.value);
                                });
                                let recognition_quir_derecho = new webkitSpeechRecognition();
                                recognition_quir_derecho.lang = "es-ES";
                                recognition_quir_derecho.continuous = true;
                                recognition_quir_derecho.interimResults = false;
                                recognition_quir_derecho.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    quirurgico_derecho.value += frase;
                                };
                                quir_derecho_grabar.addEventListener('click', () => {
                                    recognition_quir_derecho.start();
                                });
                                quir_derecho_detener.addEventListener('click', () => {
                                    recognition_quir_derecho.abort();
                                });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingLeft">
                        <h2 class="mb-0">
                            <button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#collapseLeft" aria-expanded="false" aria-controls="collapseLeft">
                                Ojo Izquierdo
                            </button>
                        </h2>
                    </div>
                    <div id="collapseLeft" class="collapse" aria-labelledby="headingLeft" data-parent="#eyeAccordion">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="medicamento_izquierdo"><strong>Medicamento:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="med_izquierdo_grabar"><i class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="med_izquierdo_detener"><i class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_med_izquierdo"><i class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" id="medicamento_izquierdo_input" rows="4" placeholder="Ej. Gotas de timolol 0.5%"></textarea>
                                <button type="button" class="btn btn-secondary btn-sm mt-2" id="add_medicamento_izquierdo">Añadir</button>
                                <table class="table table-bordered table-medicamentos" id="medicamento_izquierdo_table" style="display: none;">
                                    <thead>
                                        <tr>
                                            <th>Medicamento</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <script>
                                let med_izquierdo_count = 0;
                                $('#add_medicamento_izquierdo').click(function() {
                                    const medicamento = $('#medicamento_izquierdo_input').val().trim();
                                    if (!medicamento) {
                                        alert('Por favor, ingrese un medicamento antes de añadir.');
                                        return;
                                    }
                                    const newRow = `
                                        <tr data-index="${med_izquierdo_count}">
                                            <td>${medicamento}</td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm edit-med-izquierdo">Editar</button>
                                                <button type="button" class="btn btn-danger btn-sm delete-med-izquierdo">Eliminar</button>
                                            </td>
                                        </tr>
                                    `;
                                    $('#medicamento_izquierdo_table tbody').append(newRow);
                                    $('#medicamento_izquierdo_table').show();
                                    $('#medicamento_izquierdo_input').val('');
                                    med_izquierdo_count++;
                                });

                                $(document).on('click', '.edit-med-izquierdo', function() {
                                    const row = $(this).closest('tr');
                                    const medicamento = row.find('td:first').text();
                                    $('#medicamento_izquierdo_input').val(medicamento);
                                    row.remove();
                                    med_izquierdo_count--;
                                    if (med_izquierdo_count === 0) {
                                        $('#medicamento_izquierdo_table').hide();
                                    }
                                });

                                $(document).on('click', '.delete-med-izquierdo', function() {
                                    $(this).closest('tr').remove();
                                    med_izquierdo_count--;
                                    if (med_izquierdo_count === 0) {
                                        $('#medicamento_izquierdo_table').hide();
                                    }
                                });

                                const med_izquierdo_grabar = document.getElementById('med_izquierdo_grabar');
                                const med_izquierdo_detener = document.getElementById('med_izquierdo_detener');
                                const medicamento_izquierdo_input = document.getElementById('medicamento_izquierdo_input');
                                const btn_med_izquierdo = document.getElementById('play_med_izquierdo');
                                btn_med_izquierdo.addEventListener('click', () => {
                                    leerTexto(medicamento_izquierdo_input.value);
                                });
                                let recognition_med_izquierdo = new webkitSpeechRecognition();
                                recognition_med_izquierdo.lang = "es-ES";
                                recognition_med_izquierdo.continuous = true;
                                recognition_med_izquierdo.interimResults = false;
                                recognition_med_izquierdo.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    medicamento_izquierdo_input.value += frase;
                                };
                                med_izquierdo_grabar.addEventListener('click', () => {
                                    recognition_med_izquierdo.start();
                                });
                                med_izquierdo_detener.addEventListener('click', () => {
                                    recognition_med_izquierdo.abort();
                                });
                                </script>
                            </div>
                            <div class="form-group">
                                <label><strong>Código Tratamiento:</strong></label>
                                <input type="text" class="form-control" name="codigo_tratamiento_izquierdo" id="codigo_tratamiento_izquierdo" placeholder="Ej. T123">
                                <div class="botones mt-2">
                                    <button type="button" class="btn btn-danger btn-sm" id="desc_trat_izquierdo_grabar"><i class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="desc_trat_izquierdo_detener"><i class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_desc_trat_izquierdo"><i class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control mt-2" name="desc_tratamiento_izquierdo" id="desc_tratamiento_izquierdo" rows="2" placeholder="Ej. Timolol 0.5%, 1 gota cada 12 horas"></textarea>
                                <script>
                                const desc_trat_izquierdo_grabar = document.getElementById('desc_trat_izquierdo_grabar');
                                const desc_trat_izquierdo_detener = document.getElementById('desc_trat_izquierdo_detener');
                                const desc_tratamiento_izquierdo = document.getElementById('desc_tratamiento_izquierdo');
                                const btn_desc_trat_izquierdo = document.getElementById('play_desc_trat_izquierdo');
                                btn_desc_trat_izquierdo.addEventListener('click', () => {
                                    leerTexto(desc_tratamiento_izquierdo.value);
                                });
                                let recognition_desc_trat_izquierdo = new webkitSpeechRecognition();
                                recognition_desc_trat_izquierdo.lang = "es-ES";
                                recognition_desc_trat_izquierdo.continuous = true;
                                recognition_desc_trat_izquierdo.interimResults = false;
                                recognition_desc_trat_izquierdo.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    desc_tratamiento_izquierdo.value += frase;
                                };
                                desc_trat_izquierdo_grabar.addEventListener('click', () => {
                                    recognition_desc_trat_izquierdo.start();
                                });
                                desc_trat_izquierdo_detener.addEventListener('click', () => {
                                    recognition_desc_trat_izquierdo.abort();
                                });
                                </script>
                            </div>
                            <div class="form-group">
                                <label><strong>Primera Vez / Subsecuente:</strong></label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipo_tratamiento_izquierdo" id="primera_vez_izquierdo" value="Primera Vez">
                                    <label class="form-check-label" for="primera_vez_izquierdo">Primera Vez</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipo_tratamiento_izquierdo" id="subsecuente_izquierdo" value="Subsecuente">
                                    <label class="form-check-label" for="subsecuente_izquierdo">Subsecuente</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="procedimientos_izquierdo_input"><strong>Procedimientos:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="proc_izquierdo_grabar"><i class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="proc_izquierdo_detener"><i class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_proc_izquierdo"><i class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" id="procedimientos_izquierdo_input" rows="2" placeholder="Ej. Tonometría"></textarea>
                                <button type="button" class="btn btn-secondary btn-sm mt-2" id="add_procedimiento_izquierdo">Añadir</button>
                                <table class="table table-bordered table-procedimientos" id="procedimientos_izquierdo_table" style="display: none;">
                                    <thead>
                                        <tr>
                                            <th>Procedimiento</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <script>
                                let proc_izquierdo_count = 0;
                                $('#add_procedimiento_izquierdo').click(function() {
                                    const procedimiento = $('#procedimientos_izquierdo_input').val().trim();
                                    if (!procedimiento) {
                                        alert('Por favor, ingrese un procedimiento antes de añadir.');
                                        return;
                                    }
                                    const newRow = `
                                        <tr data-index="${proc_izquierdo_count}">
                                            <td>${procedimiento}</td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm edit-proc-izquierdo">Editar</button>
                                                <button type="button" class="btn btn-danger btn-sm delete-proc-izquierdo">Eliminar</button>
                                            </td>
                                        </tr>
                                    `;
                                    $('#procedimientos_izquierdo_table tbody').append(newRow);
                                    $('#procedimientos_izquierdo_table').show();
                                    $('#procedimientos_izquierdo_input').val('');
                                    proc_izquierdo_count++;
                                });

                                $(document).on('click', '.edit-proc-izquierdo', function() {
                                    const row = $(this).closest('tr');
                                    const procedimiento = row.find('td:first').text();
                                    $('#procedimientos_izquierdo_input').val(procedimiento);
                                    row.remove();
                                    proc_izquierdo_count--;
                                    if (proc_izquierdo_count === 0) {
                                        $('#procedimientos_izquierdo_table').hide();
                                    }
                                });

                                $(document).on('click', '.delete-proc-izquierdo', function() {
                                    $(this).closest('tr').remove();
                                    proc_izquierdo_count--;
                                    if (proc_izquierdo_count === 0) {
                                        $('#procedimientos_izquierdo_table').hide();
                                    }
                                });

                                const proc_izquierdo_grabar = document.getElementById('proc_izquierdo_grabar');
                                const proc_izquierdo_detener = document.getElementById('proc_izquierdo_detener');
                                const procedimientos_izquierdo_input = document.getElementById('procedimientos_izquierdo_input');
                                const btn_proc_izquierdo = document.getElementById('play_proc_izquierdo');
                                btn_proc_izquierdo.addEventListener('click', () => {
                                    leerTexto(procedimientos_izquierdo_input.value);
                                });
                                let recognition_proc_izquierdo = new webkitSpeechRecognition();
                                recognition_proc_izquierdo.lang = "es-ES";
                                recognition_proc_izquierdo.continuous = true;
                                recognition_proc_izquierdo.interimResults = false;
                                recognition_proc_izquierdo.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    procedimientos_izquierdo_input.value += frase;
                                };
                                proc_izquierdo_grabar.addEventListener('click', () => {
                                    recognition_proc_izquierdo.start();
                                });
                                proc_izquierdo_detener.addEventListener('click', () => {
                                    recognition_proc_izquierdo.abort();
                                });
                                </script>
                            </div>
                            <div class="form-group">
                                <label for="quirurgico_izquierdo"><strong>Quirúrgico:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="quir_izquierdo_grabar"><i class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="quir_izquierdo_detener"><i class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_quir_izquierdo"><i class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" name="quirurgico_izquierdo" id="quirurgico_izquierdo" rows="4" placeholder="Ej. Trabeculectomía programada"></textarea>
                                <script>
                                const quir_izquierdo_grabar = document.getElementById('quir_izquierdo_grabar');
                                const quir_izquierdo_detener = document.getElementById('quir_izquierdo_detener');
                                const quirurgico_izquierdo = document.getElementById('quirurgico_izquierdo');
                                const btn_quir_izquierdo = document.getElementById('play_quir_izquierdo');
                                btn_quir_izquierdo.addEventListener('click', () => {
                                    leerTexto(quirurgico_izquierdo.value);
                                });
                                let recognition_quir_izquierdo = new webkitSpeechRecognition();
                                recognition_quir_izquierdo.lang = "es-ES";
                                recognition_quir_izquierdo.continuous = true;
                                recognition_quir_izquierdo.interimResults = false;
                                recognition_quir_izquierdo.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    quirurgico_izquierdo.value += frase;
                                };
                                quir_izquierdo_grabar.addEventListener('click', () => {
                                    recognition_quir_izquierdo.start();
                                });
                                quir_izquierdo_detener.addEventListener('click', () => {
                                    recognition_quir_izquierdo.abort();
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
    <footer class="main-footer">
        <?php include("../../template/footer.php"); ?>
    </footer>
    <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
    <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
    <script>
    document.oncontextmenu = function() { return false; }
    </script>
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
    $(document).ready(function() {
        $('#oftalmologicamente_sano, #sin_tratamiento').change(function() {
            if ($('#oftalmologicamente_sano').is(':checked') || $('#sin_tratamiento').is(':checked')) {
                $('#usar_tratamientos_previos_section, #eyeAccordion').hide();
                $('#tratamiento_previo_derecho, #tratamiento_previo_izquierdo').val('');
                $('#tratamiento_principal_derecho, #codigo_tratamiento_derecho, #desc_tratamiento_derecho, #otros_tratamientos_derecho_input, #tratamiento_principal_izquierdo, #codigo_tratamiento_izquierdo, #desc_tratamiento_izquierdo, #otros_tratamientos_izquierdo_input').val('');
                $('input[name="tipo_tratamiento_derecho"], input[name="tipo_tratamiento_izquierdo"]').prop('checked', false);
                $('#otros_tratamientos_derecho_table tbody, #otros_tratamientos_izquierdo_table tbody').empty();
                $('#otros_tratamientos_derecho_table, #otros_tratamientos_izquierdo_table').hide();
            } else {
                $('#usar_tratamientos_previos_section, #eyeAccordion').show();
            }
        });
        $('#tratamiento_previo_derecho').change(function() {
            if ($(this).val()) {
                $('#tratamiento_principal_derecho').val($(this).val());
            }
        });
        $('#tratamiento_previo_izquierdo').change(function() {
            if ($(this).val()) {
                $('#tratamiento_principal_izquierdo').val($(this).val());
            }
        });
    });
    $('form').submit(function() {
        let otros_derecho = '';
        $('#otros_tratamientos_derecho_table tbody tr').each(function() {
            const treatment = $(this).find('td:first').text();
            if (treatment) {
                otros_derecho += (otros_derecho ? '; ' : '') + treatment;
            }
        });
        $('<input>').attr({
            type: 'hidden',
            name: 'otros_tratamientos_derecho',
            value: otros_derecho
        }).appendTo('form');
        let otros_izquierdo = '';
        $('#otros_tratamientos_izquierdo_table tbody tr').each(function() {
            const treatment = $(this).find('td:first').text();
            if (treatment) {
                otros_izquierdo += (otros_izquierdo ? '; ' : '') + treatment;
            }
        });
        $('<input>').attr({
            type: 'hidden',
            name: 'otros_tratamientos_izquierdo',
            value: otros_izquierdo
        }).appendTo('form');
    });
    </script>
</body>
</html>