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
$debug_messages = [];
$error_message = '';
if ($conexion) {
    $id_atencion = $_SESSION['hospital'];
    $debug_messages[] = "Session id_atencion: $id_atencion";
    
    // Step 1: Get Id_exp for the current id_atencion
    $stmt = $conexion->prepare("SELECT Id_exp FROM dat_ingreso WHERE id_atencion = ?");
    if (!$stmt) {
        $error_message = "Error preparing query for Id_exp: " . $conexion->error;
        $debug_messages[] = $error_message;
    } else {
        $stmt->bind_param("i", $id_atencion);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $id_exp = $row['Id_exp'] ?? null;
            $debug_messages[] = "Id_exp retrieved: " . ($id_exp ?? 'null');
            $stmt->close();
            
            // Step 2: Fetch previous treatments if Id_exp is found
            if ($id_exp) {
                $stmt = $conexion->prepare("
                    SELECT id_tratamiento, id_atencion, Id_exp, tratamiento_previo_derecho, tratamiento_previo_izquierdo,
                           medicamento_derecho, codigo_tratamiento_derecho, desc_tratamiento_derecho,
                           tipo_tratamiento_derecho, procedimientos_derecho, quirurgico_derecho,
                           medicamento_izquierdo, codigo_tratamiento_izquierdo, desc_tratamiento_izquierdo,
                           tipo_tratamiento_izquierdo, procedimientos_izquierdo, quirurgico_izquierdo,
                           fecha_registro
                    FROM ocular_tratamiento 
                    WHERE Id_exp = ?
                    ORDER BY fecha_registro DESC
                ");
                if (!$stmt) {
                    $error_message = "Error preparing query for treatments: " . $conexion->error;
                    $debug_messages[] = $error_message;
                } else {
                    $stmt->bind_param("s", $id_exp);
                    if ($stmt->execute()) {
                        $result = $stmt->get_result();
                        while ($row = $result->fetch_assoc()) {
                            // Log raw record for debugging
                            $debug_messages[] = "Raw treatment record: " . json_encode($row, JSON_UNESCAPED_UNICODE);
                            // Right eye: Prioritize medicamento_derecho if tratamiento_previo_derecho is empty
                            if (!empty(trim($row['medicamento_derecho'] ?? '')) || !empty(trim($row['tratamiento_previo_derecho'] ?? ''))) {
                                $treatment_text = trim($row['medicamento_derecho'] ?? '') ?: trim($row['tratamiento_previo_derecho'] ?? '');
                                if ($treatment_text) {
                                    $previous_treatments[] = [
                                        'id_tratamiento' => $row['id_tratamiento'],
                                        'treatment' => $treatment_text,
                                        'eye' => 'right',
                                        'details' => [
                                            'medicamento' => trim($row['medicamento_derecho'] ?? ''),
                                            'codigo_tratamiento' => trim($row['codigo_tratamiento_derecho'] ?? ''),
                                            'desc_tratamiento' => trim($row['desc_tratamiento_derecho'] ?? ''),
                                            'tipo_tratamiento' => trim($row['tipo_tratamiento_derecho'] ?? ''),
                                            'procedimientos' => trim($row['procedimientos_derecho'] ?? ''),
                                            'quirurgico' => trim($row['quirurgico_derecho'] ?? ''),
                                            'fecha_registro' => $row['fecha_registro']
                                        ]
                                    ];
                                }
                            }
                            // Left eye: Prioritize medicamento_izquierdo if tratamiento_previo_izquierdo is empty
                            if (!empty(trim($row['medicamento_izquierdo'] ?? '')) || !empty(trim($row['tratamiento_previo_izquierdo'] ?? ''))) {
                                $treatment_text = trim($row['medicamento_izquierdo'] ?? '') ?: trim($row['tratamiento_previo_izquierdo'] ?? '');
                                if ($treatment_text) {
                                    $previous_treatments[] = [
                                        'id_tratamiento' => $row['id_tratamiento'],
                                        'treatment' => $treatment_text,
                                        'eye' => 'left',
                                        'details' => [
                                            'medicamento' => trim($row['medicamento_izquierdo'] ?? ''),
                                            'codigo_tratamiento' => trim($row['codigo_tratamiento_izquierdo'] ?? ''),
                                            'desc_tratamiento' => trim($row['desc_tratamiento_izquierdo'] ?? ''),
                                            'tipo_tratamiento' => trim($row['tipo_tratamiento_izquierdo'] ?? ''),
                                            'procedimientos' => trim($row['procedimientos_izquierdo'] ?? ''),
                                            'quirurgico' => trim($row['quirurgico_izquierdo'] ?? ''),
                                            'fecha_registro' => $row['fecha_registro']
                                        ]
                                    ];
                                }
                            }
                        }
                        $debug_messages[] = "Treatments found: " . count($previous_treatments);
                        $stmt->close();
                    } else {
                        $error_message = "Error executing query for treatments: " . $stmt->error;
                        $debug_messages[] = $error_message;
                    }
                }
            } else {
                $error_message = "No Id_exp found for id_atencion: $id_atencion";
                $debug_messages[] = $error_message;
            }
        } else {
            $error_message = "Error executing query for Id_exp: " . $stmt->error;
            $debug_messages[] = $error_message;
        }
    }
    $conexion->close();
} else {
    $error_message = "Database connection failed.";
    $debug_messages[] = $error_message;
}

// Debugging: Log to file
file_put_contents('debug_log.txt', date('Y-m-d H:i:s') . "\n" . implode("\n", $debug_messages) . "\n\n", FILE_APPEND);
echo '<div style="display: none;"><pre>Debug Messages: ' . implode("\n", array_map('htmlspecialchars', $debug_messages)) . '</pre></div>';
if ($error_message) {
    echo '<p style="color: red;">' . htmlspecialchars($error_message) . '</p>';
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>REGISTRO DE TRATAMIENTO</title>
    <link rel="stylesheet" type="text/css" href="../../css/select2.css">
    <link href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="../../js/select2.js"></script>
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
                $(this).toggle($(this).text().toLowerCase().indexOf(valor) > -1);
            });
        });
        $('#tratamiento_previo_derecho, #tratamiento_previo_izquierdo').select2({
            placeholder: "Seleccionar tratamiento previo",
            allowClear: true
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

    .table-tratamientos {
        margin-top: 10px;
    }

    .table-tratamientos th,
    .table-tratamientos td {
        vertical-align: middle;
    }

    .error-message {
        color: red;
        font-weight: bold;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="thead"><strong>
                        <center>DATOS DEL PACIENTE</center>
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
    </div>
    <br><br>
    <div class="container">
        <div class="thead"><strong>
                <center>TRATAMIENTO</center>
            </strong></div>
        <?php if ($error_message): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <form action="insertar_tratamiento.php" method="POST" onsubmit="return checkSubmit();">
            <div class="form-group mt-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="oftalmologicamente_sano"
                        id="oftalmologicamente_sano" value="1">
                    <label class="form-check-label" for="oftalmologicamente_sano">Oftalmológicamente Sano</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="sin_tratamiento" id="sin_tratamiento"
                        value="1">
                    <label class="form-check-label" for="sin_tratamiento">Sin Tratamiento</label>
                </div>
            </div>
            <div class="form-group" id="usar_tratamientos_previos_section">
                <label for="tratamiento_previo"><strong>Usar Tratamientos Previos:</strong></label>
                <select class="form-control" name="tratamiento_previo" id="tratamiento_previo">
                    <option value="">Seleccionar</option>
                    <?php
        $unique_treatments = [];
        foreach ($previous_treatments as $treat) {
            $treatment_key = $treat['id_tratamiento'] . '|' . $treat['eye'] . '|' . $treat['treatment'];
            if (!in_array($treatment_key, $unique_treatments)) {
                $unique_treatments[] = $treatment_key;
                $eye_label = $treat['eye'] === 'right' ? 'Ojo Derecho' : 'Ojo Izquierdo';
                $display_text = htmlspecialchars($treat['treatment']) . " ($eye_label, " . date('d/m/Y', strtotime($treat['details']['fecha_registro'])) . ")";
                echo "<option value=\"{$treat['id_tratamiento']}|{$treat['eye']}\" data-details='" . json_encode($treat['details'], JSON_UNESCAPED_UNICODE) . "'>$display_text</option>";
            }
        }
        if (empty($unique_treatments)) {
            echo '<option value="" disabled>No hay tratamientos previos disponibles</option>';
        }
        ?>
                </select>
                <div id="previous_treatment_details" class="mt-3" style="display: none;">
                    <h5>Detalles del Tratamiento Previo</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Campo</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody id="details_table_body"></tbody>
                    </table>
                </div>
            </div>
            <div class="accordion mt-3" id="eyeAccordion">
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
                                <label for="medicamento_derecho"><strong>Medicamento:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="med_derecho_grabar"><i
                                            class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="med_derecho_detener"><i
                                            class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_med_derecho"><i
                                            class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" name="medicamento_derecho" id="medicamento_derecho_input"
                                    rows="4" placeholder="Ej. Gotas de timolol 0.5%"></textarea>
                                <button type="button" class="btn btn-secondary btn-sm mt-2"
                                    id="add_medicamento_derecho">Añadir</button>
                                <table class="table table-bordered table-tratamientos" id="medicamento_derecho_table"
                                    style="display: none;">
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
                                let recognition_med_derecho = new(window.SpeechRecognition || window
                                    .webkitSpeechRecognition)();
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
                                    recognition_med_derecho.stop();
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
                                <input type="text" class="form-control" name="codigo_tratamiento_derecho"
                                    id="codigo_tratamiento_derecho" placeholder="Ej. T123">
                                <div class="botones mt-2">
                                    <button type="button" class="btn btn-danger btn-sm" id="desc_trat_derecho_grabar"><i
                                            class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm"
                                        id="desc_trat_derecho_detener"><i class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_desc_trat_derecho"><i
                                            class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control mt-2" name="desc_tratamiento_derecho"
                                    id="desc_tratamiento_derecho" rows="2"
                                    placeholder="Ej. Timolol 0.5%, 1 gota cada 12 horas"></textarea>
                                <script>
                                const desc_trat_derecho_grabar = document.getElementById('desc_trat_derecho_grabar');
                                const desc_trat_derecho_detener = document.getElementById('desc_trat_derecho_detener');
                                const desc_tratamiento_derecho = document.getElementById('desc_tratamiento_derecho');
                                const btn_desc_trat_derecho = document.getElementById('play_desc_trat_derecho');
                                btn_desc_trat_derecho.addEventListener('click', () => {
                                    leerTexto(desc_tratamiento_derecho.value);
                                });
                                let recognition_desc_trat_derecho = new(window.SpeechRecognition || window
                                    .webkitSpeechRecognition)();
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
                                    recognition_desc_trat_derecho.stop();
                                });
                                </script>
                            </div>
                            <div class="form-group">
                                <label><strong>Primera Vez / Subsecuente:</strong></label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipo_tratamiento_derecho"
                                        id="primera_vez_derecho" value="Primera Vez">
                                    <label class="form-check-label" for="primera_vez_derecho">Primera Vez</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipo_tratamiento_derecho"
                                        id="subsecuente_derecho" value="Subsecuente">
                                    <label class="form-check-label" for="subsecuente_derecho">Subsecuente</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="procedimientos_derecho_input"><strong>Procedimientos:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="proc_derecho_grabar"><i
                                            class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="proc_derecho_detener"><i
                                            class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_proc_derecho"><i
                                            class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" name="procedimientos_derecho"
                                    id="procedimientos_derecho_input" rows="2" placeholder="Ej. Tonometría"></textarea>
                                <button type="button" class="btn btn-secondary btn-sm mt-2"
                                    id="add_procedimiento_derecho">Añadir</button>
                                <table class="table table-bordered table-tratamientos" id="procedimientos_derecho_table"
                                    style="display: none;">
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
                                const procedimientos_derecho_input = document.getElementById(
                                    'procedimientos_derecho_input');
                                const btn_proc_derecho = document.getElementById('play_proc_derecho');
                                btn_proc_derecho.addEventListener('click', () => {
                                    leerTexto(procedimientos_derecho_input.value);
                                });
                                let recognition_proc_derecho = new(window.SpeechRecognition || window
                                    .webkitSpeechRecognition)();
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
                                    recognition_proc_derecho.stop();
                                });
                                </script>
                            </div>
                            <div class="form-group">
                                <label for="quirurgico_derecho"><strong>Quirúrgico:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="quir_derecho_grabar"><i
                                            class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="quir_derecho_detener"><i
                                            class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_quir_derecho"><i
                                            class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" name="quirurgico_derecho" id="quirurgico_derecho"
                                    rows="4" placeholder="Ej. Trabeculectomía programada"></textarea>
                                <script>
                                const quir_derecho_grabar = document.getElementById('quir_derecho_grabar');
                                const quir_derecho_detener = document.getElementById('quir_derecho_detener');
                                const quirurgico_derecho = document.getElementById('quirurgico_derecho');
                                const btn_quir_derecho = document.getElementById('play_quir_derecho');
                                btn_quir_derecho.addEventListener('click', () => {
                                    leerTexto(quirurgico_derecho.value);
                                });
                                let recognition_quir_derecho = new(window.SpeechRecognition || window
                                    .webkitSpeechRecognition)();
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
                                    recognition_quir_derecho.stop();
                                });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
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
                                <label for="medicamento_izquierdo"><strong>Medicamento:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="med_izquierdo_grabar"><i
                                            class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="med_izquierdo_detener"><i
                                            class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_med_izquierdo"><i
                                            class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" name="medicamento_izquierdo"
                                    id="medicamento_izquierdo_input" rows="4"
                                    placeholder="Ej. Gotas de timolol 0.5%"></textarea>
                                <button type="button" class="btn btn-secondary btn-sm mt-2"
                                    id="add_medicamento_izquierdo">Añadir</button>
                                <table class="table table-bordered table-tratamientos" id="medicamento_izquierdo_table"
                                    style="display: none;">
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
                                const medicamento_izquierdo_input = document.getElementById(
                                    'medicamento_izquierdo_input');
                                const btn_med_izquierdo = document.getElementById('play_med_izquierdo');
                                btn_med_izquierdo.addEventListener('click', () => {
                                    leerTexto(medicamento_izquierdo_input.value);
                                });
                                let recognition_med_izquierdo = new(window.SpeechRecognition || window
                                    .webkitSpeechRecognition)();
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
                                    recognition_med_izquierdo.stop();
                                });
                                </script>
                            </div>
                            <div class="form-group">
                                <label><strong>Código Tratamiento:</strong></label>
                                <input type="text" class="form-control" name="codigo_tratamiento_izquierdo"
                                    id="codigo_tratamiento_izquierdo" placeholder="Ej. T123">
                                <div class="botones mt-2">
                                    <button type="button" class="btn btn-danger btn-sm"
                                        id="desc_trat_izquierdo_grabar"><i class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm"
                                        id="desc_trat_izquierdo_detener"><i
                                            class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm"
                                        id="play_desc_trat_izquierdo"><i class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control mt-2" name="desc_tratamiento_izquierdo"
                                    id="desc_tratamiento_izquierdo" rows="2"
                                    placeholder="Ej. Timolol 0.5%, 1 gota cada 12 horas"></textarea>
                                <script>
                                const desc_trat_izquierdo_grabar = document.getElementById(
                                    'desc_trat_izquierdo_grabar');
                                const desc_trat_izquierdo_detener = document.getElementById(
                                    'desc_trat_izquierdo_detener');
                                const desc_tratamiento_izquierdo = document.getElementById(
                                    'desc_tratamiento_izquierdo');
                                const btn_desc_trat_izquierdo = document.getElementById('play_desc_trat_izquierdo');
                                btn_desc_trat_izquierdo.addEventListener('click', () => {
                                    leerTexto(desc_tratamiento_izquierdo.value);
                                });
                                let recognition_desc_trat_izquierdo = new(window.SpeechRecognition || window
                                    .webkitSpeechRecognition)();
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
                                    recognition_desc_trat_izquierdo.stop();
                                });
                                </script>
                            </div>
                            <div class="form-group">
                                <label><strong>Primera Vez / Subsecuente:</strong></label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipo_tratamiento_izquierdo"
                                        id="primera_vez_izquierdo" value="Primera Vez">
                                    <label class="form-check-label" for="primera_vez_izquierdo">Primera Vez</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipo_tratamiento_izquierdo"
                                        id="subsecuente_izquierdo" value="Subsecuente">
                                    <label class="form-check-label" for="subsecuente_izquierdo">Subsecuente</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="procedimientos_izquierdo_input"><strong>Procedimientos:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="proc_izquierdo_grabar"><i
                                            class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="proc_izquierdo_detener"><i
                                            class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_proc_izquierdo"><i
                                            class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" name="procedimientos_izquierdo"
                                    id="procedimientos_izquierdo_input" rows="2"
                                    placeholder="Ej. Tonometría"></textarea>
                                <button type="button" class="btn btn-secondary btn-sm mt-2"
                                    id="add_procedimiento_izquierdo">Añadir</button>
                                <table class="table table-bordered table-tratamientos"
                                    id="procedimientos_izquierdo_table" style="display: none;">
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
                                const procedimientos_izquierdo_input = document.getElementById(
                                    'procedimientos_izquierdo_input');
                                const btn_proc_izquierdo = document.getElementById('play_proc_izquierdo');
                                btn_proc_izquierdo.addEventListener('click', () => {
                                    leerTexto(procedimientos_izquierdo_input.value);
                                });
                                let recognition_proc_izquierdo = new(window.SpeechRecognition || window
                                    .webkitSpeechRecognition)();
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
                                    recognition_proc_izquierdo.stop();
                                });
                                </script>
                            </div>
                            <div class="form-group">
                                <label for="quirurgico_izquierdo"><strong>Quirúrgico:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="quir_izquierdo_grabar"><i
                                            class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="quir_izquierdo_detener"><i
                                            class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_quir_izquierdo"><i
                                            class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" name="quirurgico_izquierdo" id="quirurgico_izquierdo"
                                    rows="4" placeholder="Ej. Trabeculectomía programada"></textarea>
                                <script>
                                const quir_izquierdo_grabar = document.getElementById('quir_izquierdo_grabar');
                                const quir_izquierdo_detener = document.getElementById('quir_izquierdo_detener');
                                const quirurgico_izquierdo = document.getElementById('quirurgico_izquierdo');
                                const btn_quir_izquierdo = document.getElementById('play_quir_izquierdo');
                                btn_quir_izquierdo.addEventListener('click', () => {
                                    leerTexto(quirurgico_izquierdo.value);
                                });
                                let recognition_quir_izquierdo = new(window.SpeechRecognition || window
                                    .webkitSpeechRecognition)();
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
                                    recognition_quir_izquierdo.stop();
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
    document.oncontextmenu = function() {
        return false;
    }
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
                $('#tratamiento_previo').val('');
                $('#medicamento_derecho_input, #codigo_tratamiento_derecho, #desc_tratamiento_derecho, #procedimientos_derecho_input, #quirurgico_derecho, #medicamento_izquierdo_input, #codigo_tratamiento_izquierdo, #desc_tratamiento_izquierdo, #procedimientos_izquierdo_input, #quirurgico_izquierdo')
                    .val('');
                $('input[name="tipo_tratamiento_derecho"], input[name="tipo_tratamiento_izquierdo"]')
                    .prop('checked', false);
                $('#medicamento_derecho_table tbody, #procedimientos_derecho_table tbody, #medicamento_izquierdo_table tbody, #procedimientos_izquierdo_table tbody')
                    .empty();
                $('#medicamento_derecho_table, #procedimientos_derecho_table, #medicamento_izquierdo_table, #procedimientos_izquierdo_table')
                    .hide();
                $('#previous_treatment_details').hide();
            } else {
                $('#usar_tratamientos_previos_section, #eyeAccordion').show();
            }
        });

        $('#tratamiento_previo').change(function() {
            const selectedOption = $(this).find('option:selected');
            const treatmentText = selectedOption.text().split(' (')[
            0]; // Get treatment text without eye/date
            // Add hidden input for treatment text only if a treatment is selected
            $('#tratamiento_previo_text').remove();
            if (selectedOption.val()) {
                $('<input>').attr({
                    type: 'hidden',
                    id: 'tratamiento_previo_text',
                    name: 'tratamiento_previo_text',
                    value: treatmentText
                }).appendTo('form');
            }
            // Rest of the change function
            const details = selectedOption.data('details');
            const value = $(this).val();
            console.log('Selected treatment:', {
                value,
                details,
                treatmentText
            }); // Debugging
            if (value) {
                const [id_tratamiento, eye] = value.split('|');
                $('#previous_treatment_details').show();
                $('#details_table_body').empty();

                if (eye === 'right') {
                    $('#medicamento_derecho_input').val(details.medicamento);
                    $('#codigo_tratamiento_derecho').val(details.codigo_tratamiento);
                    $('#desc_tratamiento_derecho').val(details.desc_tratamiento);
                    $(`input[name="tipo_tratamiento_derecho"][value="${details.tipo_tratamiento}"]`)
                        .prop('checked', true);
                    $('#procedimientos_derecho_input').val(details.procedimientos);
                    $('#quirurgico_derecho').val(details.quirurgico);
                    $('#details_table_body').append(`
                    <tr><td>Medicamento (Ojo Derecho)</td><td>${details.medicamento || '-'}</td></tr>
                    <tr><td>Código Tratamiento (Ojo Derecho)</td><td>${details.codigo_tratamiento || '-'}</td></tr>
                    <tr><td>Descripción Tratamiento (Ojo Derecho)</td><td>${details.desc_tratamiento || '-'}</td></tr>
                    <tr><td>Tipo Tratamiento (Ojo Derecho)</td><td>${details.tipo_tratamiento || '-'}</td></tr>
                    <tr><td>Procedimientos (Ojo Derecho)</td><td>${details.procedimientos || '-'}</td></tr>
                    <tr><td>Quirúrgico (Ojo Derecho)</td><td>${details.quirurgico || '-'}</td></tr>
                    <tr><td>Fecha de Registro</td><td>${new Date(details.fecha_registro).toLocaleDateString('es-ES')}</td></tr>
                `);
                    // Clear left eye fields
                    $('#medicamento_izquierdo_input, #codigo_tratamiento_izquierdo, #desc_tratamiento_izquierdo, #procedimientos_izquierdo_input, #quirurgico_izquierdo')
                        .val('');
                    $('input[name="tipo_tratamiento_izquierdo"]').prop('checked', false);
                } else if (eye === 'left') {
                    $('#medicamento_izquierdo_input').val(details.medicamento);
                    $('#codigo_tratamiento_izquierdo').val(details.codigo_tratamiento);
                    $('#desc_tratamiento_izquierdo').val(details.desc_tratamiento);
                    $(`input[name="tipo_tratamiento_izquierdo"][value="${details.tipo_tratamiento}"]`)
                        .prop('checked', true);
                    $('#procedimientos_izquierdo_input').val(details.procedimientos);
                    $('#quirurgico_izquierdo').val(details.quirurgico);
                    $('#details_table_body').append(`
                    <tr><td>Medicamento (Ojo Izquierdo)</td><td>${details.medicamento || '-'}</td></tr>
                    <tr><td>Código Tratamiento (Ojo Izquierdo)</td><td>${details.codigo_tratamiento || '-'}</td></tr>
                    <tr><td>Descripción Tratamiento (Ojo Izquierdo)</td><td>${details.desc_tratamiento || '-'}</td></tr>
                    <tr><td>Tipo Tratamiento (Ojo Izquierdo)</td><td>${details.tipo_tratamiento || '-'}</td></tr>
                    <tr><td>Procedimientos (Ojo Izquierdo)</td><td>${details.procedimientos || '-'}</td></tr>
                    <tr><td>Quirúrgico (Ojo Izquierdo)</td><td>${details.quirurgico || '-'}</td></tr>
                    <tr><td>Fecha de Registro</td><td>${new Date(details.fecha_registro).toLocaleDateString('es-ES')}</td></tr>
                `);
                    // Clear right eye fields
                    $('#medicamento_derecho_input, #codigo_tratamiento_derecho, #desc_tratamiento_derecho, #procedimientos_derecho_input, #quirurgico_derecho')
                        .val('');
                    $('input[name="tipo_tratamiento_derecho"]').prop('checked', false);
                }
            } else {
                $('#previous_treatment_details').hide();
                $('#medicamento_derecho_input, #codigo_tratamiento_derecho, #desc_tratamiento_derecho, #procedimientos_derecho_input, #quirurgico_derecho')
                    .val('');
                $('#medicamento_izquierdo_input, #codigo_tratamiento_izquierdo, #desc_tratamiento_izquierdo, #procedimientos_izquierdo_input, #quirurgico_izquierdo')
                    .val('');
                $('input[name="tipo_tratamiento_derecho"], input[name="tipo_tratamiento_izquierdo"]')
                    .prop('checked', false);
            }
        });

        $('form').submit(function() {
            let medicamentos_derecho = '';
            $('#medicamento_derecho_table tbody tr').each(function() {
                const medicamento = $(this).find('td:first').text().trim();
                if (medicamento) {
                    medicamentos_derecho += (medicamentos_derecho ? '; ' : '') + medicamento;
                }
            });
            $('#medicamento_derecho_input').val(medicamentos_derecho);

            let procedimientos_derecho = '';
            $('#procedimientos_derecho_table tbody tr').each(function() {
                const procedimiento = $(this).find('td:first').text().trim();
                if (procedimiento) {
                    procedimientos_derecho += (procedimientos_derecho ? '; ' : '') +
                        procedimiento;
                }
            });
            $('#procedimientos_derecho_input').val(procedimientos_derecho);

            let medicamentos_izquierdo = '';
            $('#medicamento_izquierdo_table tbody tr').each(function() {
                const medicamento = $(this).find('td:first').text().trim();
                if (medicamento) {
                    medicamentos_izquierdo += (medicamentos_izquierdo ? '; ' : '') +
                    medicamento;
                }
            });
            $('#medicamento_izquierdo_input').val(medicamentos_izquierdo);

            let procedimientos_izquierdo = '';
            $('#procedimientos_izquierdo_table tbody tr').each(function() {
                const procedimiento = $(this).find('td:first').text().trim();
                if (procedimiento) {
                    procedimientos_izquierdo += (procedimientos_izquierdo ? '; ' : '') +
                        procedimiento;
                }
            });
            $('#procedimientos_izquierdo_input').val(procedimientos_izquierdo);

            // Debugging: Log form data
            console.log('Form data:', {
                medicamento_derecho: $('#medicamento_derecho_input').val(),
                medicamento_izquierdo: $('#medicamento_izquierdo_input').val(),
                tratamiento_previo: $('#tratamiento_previo').val(),
                tratamiento_previo_text: $('#tratamiento_previo_text').val()
            });
        });
    });
    </script>
</body>

</html>