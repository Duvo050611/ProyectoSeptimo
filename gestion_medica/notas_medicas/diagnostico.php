<?php
session_start();
include "../../conexionbd.php";
if (!isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}
include("../header_medico.php");

// Fetch previous diagnoses for the dropdown
$previous_diagnoses = [];
$error_message = '';
if ($conexion) {
    $id_atencion = $_SESSION['hospital'];
    
    // Step 1: Get Id_exp for the current id_atencion
    $stmt = $conexion->prepare("SELECT Id_exp FROM dat_ingreso WHERE id_atencion = ?");
    if (!$stmt) {
        $error_message = "Error preparing query for Id_exp: " . $conexion->error;
    } else {
        $stmt->bind_param("i", $id_atencion);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $id_exp = $row['Id_exp'] ?? null;
            $stmt->close();
            
            // Step 2: Fetch previous diagnoses if Id_exp is found
            if ($id_exp) {
                $stmt = $conexion->prepare("SELECT DISTINCT diagnostico_principal_derecho, diagnostico_principal_izquierdo 
                                            FROM ocular_diagnostico 
                                            WHERE Id_exp = ? AND id_atencion != ? 
                                            AND (diagnostico_principal_derecho IS NOT NULL OR diagnostico_principal_izquierdo IS NOT NULL)");
                if (!$stmt) {
                    $error_message = "Error preparing query for diagnoses: " . $conexion->error;
                } else {
                    $stmt->bind_param("si", $id_exp, $id_atencion);
                    if ($stmt->execute()) {
                        $result = $stmt->get_result();
                        while ($row = $result->fetch_assoc()) {
                            if (!empty($row['diagnostico_principal_derecho'])) {
                                $previous_diagnoses[] = trim($row['diagnostico_principal_derecho']);
                            }
                            if (!empty($row['diagnostico_principal_izquierdo'])) {
                                $previous_diagnoses[] = trim($row['diagnostico_principal_izquierdo']);
                            }
                        }
                        $stmt->close();
                    } else {
                        $error_message = "Error executing query for diagnoses: " . $stmt->error;
                    }
                }
            } else {
                $error_message = "No Id_exp found for id_atencion: $id_atencion";
            }
        } else {
            $error_message = "Error executing query for Id_exp: " . $stmt->error;
        }
    }
    $conexion->close();
} else {
    $error_message = "Database connection failed.";
}

// Debugging: Uncomment to inspect $previous_diagnoses
// echo '<pre>Previous Diagnoses: ' . print_r($previous_diagnoses, true) . '</pre>';
// if ($error_message) echo '<p style="color: red;">' . htmlspecialchars($error_message) . '</p>';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
    <title>AVISO DE ALTA</title>
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
    <script>
    $(document).ready(function() {
        $("#search").keyup(function() {
            var valor = $(this).val().toLowerCase();
            $("#mytable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(valor) > -1);
            });
        });
        $('#diagnostico_previo').select2({
            placeholder: "Seleccionar diagnóstico previo",
            allowClear: true
        });
    });
    </script>
    <style>
    .modal-lg { max-width: 70% !important; }
    .botones { margin-bottom: 5px; }
    .thead { background-color: #2b2d7f; color: white; font-size: 22px; padding: 10px; text-align: center; }
    .table-diagnosticos { margin-top: 10px; }
    .table-diagnosticos th, .table-diagnosticos td { vertical-align: middle; }
    .error-message { color: red; font-weight: bold; }
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
        <div class="thead"><strong><center>DIAGNÓSTICO</center></strong></div>
        <?php if ($error_message): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <form action="insertar_diagnostico.php" method="POST" onsubmit="return checkSubmit();">
            <div class="form-group mt-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="oftalmologicamente_sano" id="oftalmologicamente_sano" value="1">
                    <label class="form-check-label" for="oftalmologicamente_sano">Oftalmológicamente Sano</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="sin_diagnostico_cie10" id="sin_diagnostico_cie10" value="1">
                    <label class="form-check-label" for="sin_diagnostico_cie10">Sin Diagnóstico CIE-10</label>
                </div>
            </div>
            <div class="form-group" id="usar_diagnosticos_previos_section">
                <label for="diagnostico_previo"><strong>Usar Diagnósticos Previos:</strong></label>
                <select class="form-control" name="diagnostico_previo" id="diagnostico_previo">
                    <option value="">Seleccionar</option>
                    <?php
                    $unique_diagnoses = array_unique($previous_diagnoses);
                    if (empty($unique_diagnoses)) {
                        echo '<option value="" disabled>No hay diagnósticos previos disponibles</option>';
                    } else {
                        foreach ($unique_diagnoses as $diag) {
                            if (!empty($diag)) {
                                echo "<option value=\"" . htmlspecialchars($diag) . "\">" . htmlspecialchars($diag) . "</option>";
                            }
                        }
                    }
                    ?>
                </select>
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
                                <label for="diagnostico_principal_derecho"><strong>Diagnóstico Principal - Descripción:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="diag_prin_derecho_grabar"><i class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="diag_prin_derecho_detener"><i class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_diag_prin_derecho"><i class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" name="diagnostico_principal_derecho" id="diagnostico_principal_derecho" rows="4" placeholder="Ej. Glaucoma de ángulo abierto"></textarea>
                                <script>
                                const diag_prin_derecho_grabar = document.getElementById('diag_prin_derecho_grabar');
                                const diag_prin_derecho_detener = document.getElementById('diag_prin_derecho_detener');
                                const diagnostico_principal_derecho = document.getElementById('diagnostico_principal_derecho');
                                const btn_diag_prin_derecho = document.getElementById('play_diag_prin_derecho');
                                btn_diag_prin_derecho.addEventListener('click', () => {
                                    leerTexto(diagnostico_principal_derecho.value);
                                });
                                let recognition_diag_prin_derecho = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
                                recognition_diag_prin_derecho.lang = "es-ES";
                                recognition_diag_prin_derecho.continuous = true;
                                recognition_diag_prin_derecho.interimResults = false;
                                recognition_diag_prin_derecho.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    diagnostico_principal_derecho.value += frase;
                                };
                                diag_prin_derecho_grabar.addEventListener('click', () => {
                                    recognition_diag_prin_derecho.start();
                                });
                                diag_prin_derecho_detener.addEventListener('click', () => {
                                    recognition_diag_prin_derecho.stop();
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
                                <label><strong>Código CIE-10:</strong></label>
                                <input type="text" class="form-control" name="codigo_cie_derecho" id="codigo_cie_derecho" placeholder="Ej. H40.1">
                                <div class="botones mt-2">
                                    <button type="button" class="btn btn-danger btn-sm" id="desc_cie_derecho_grabar"><i class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="desc_cie_derecho_detener"><i class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_desc_cie_derecho"><i class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control mt-2" name="desc_cie_derecho" id="desc_cie_derecho" rows="2" placeholder="Ej. Glaucoma primario de ángulo abierto"></textarea>
                                <script>
                                const desc_cie_derecho_grabar = document.getElementById('desc_cie_derecho_grabar');
                                const desc_cie_derecho_detener = document.getElementById('desc_cie_derecho_detener');
                                const desc_cie_derecho = document.getElementById('desc_cie_derecho');
                                const btn_desc_cie_derecho = document.getElementById('play_desc_cie_derecho');
                                btn_desc_cie_derecho.addEventListener('click', () => {
                                    leerTexto(desc_cie_derecho.value);
                                });
                                let recognition_desc_cie_derecho = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
                                recognition_desc_cie_derecho.lang = "es-ES";
                                recognition_desc_cie_derecho.continuous = true;
                                recognition_desc_cie_derecho.interimResults = false;
                                recognition_desc_cie_derecho.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    desc_cie_derecho.value += frase;
                                };
                                desc_cie_derecho_grabar.addEventListener('click', () => {
                                    recognition_desc_cie_derecho.start();
                                });
                                desc_cie_derecho_detener.addEventListener('click', () => {
                                    recognition_desc_cie_derecho.stop();
                                });
                                </script>
                            </div>
                            <div class="form-group">
                                <label><strong>Primera Vez / Subsecuente:</strong></label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipo_diagnostico_derecho" id="primera_vez_derecho" value="Primera Vez">
                                    <label class="form-check-label" for="primera_vez_derecho">Primera Vez</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipo_diagnostico_derecho" id="subsecuente_derecho" value="Subsecuente">
                                    <label class="form-check-label" for="subsecuente_derecho">Subsecuente</label>
                                </div>
                            </div>
                            <div class="form-group" id="otros_diagnosticos_derecho_container">
                                <label for="otros_diagnosticos_derecho_input"><strong>Otros Diagnósticos:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="otros_diag_derecho_grabar"><i class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="otros_diag_derecho_detener"><i class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_otros_diag_derecho"><i class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" id="otros_diagnosticos_derecho_input" rows="2" placeholder="Ej. Catarata incipiente"></textarea>
                                <button type="button" class="btn btn-secondary btn-sm mt-2" id="add_otros_derecho">Añadir</button>
                                <table class="table table-bordered table-diagnosticos" id="otros_diagnosticos_derecho_table" style="display: none;">
                                    <thead>
                                        <tr>
                                            <th>Diagnóstico</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <script>
                                let otros_derecho_count = 0;
                                $('#add_otros_derecho').click(function() {
                                    const diagnosis = $('#otros_diagnosticos_derecho_input').val().trim();
                                    if (!diagnosis) {
                                        alert('Por favor, ingrese un diagnóstico antes de añadir.');
                                        return;
                                    }
                                    const newRow = `
                                        <tr data-index="${otros_derecho_count}">
                                            <td>${diagnosis}</td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm edit-otros-derecho">Editar</button>
                                                <button type="button" class="btn btn-danger btn-sm delete-otros-derecho">Eliminar</button>
                                            </td>
                                        </tr>
                                    `;
                                    $('#otros_diagnosticos_derecho_table tbody').append(newRow);
                                    $('#otros_diagnosticos_derecho_table').show();
                                    $('#otros_diagnosticos_derecho_input').val('');
                                    otros_derecho_count++;
                                });

                                $(document).on('click', '.edit-otros-derecho', function() {
                                    const row = $(this).closest('tr');
                                    const diagnosis = row.find('td:first').text();
                                    $('#otros_diagnosticos_derecho_input').val(diagnosis);
                                    row.remove();
                                    otros_derecho_count--;
                                    if (otros_derecho_count === 0) {
                                        $('#otros_diagnosticos_derecho_table').hide();
                                    }
                                });

                                $(document).on('click', '.delete-otros-derecho', function() {
                                    $(this).closest('tr').remove();
                                    otros_derecho_count--;
                                    if (otros_derecho_count === 0) {
                                        $('#otros_diagnosticos_derecho_table').hide();
                                    }
                                });

                                const otros_diag_derecho_grabar = document.getElementById('otros_diag_derecho_grabar');
                                const otros_diag_derecho_detener = document.getElementById('otros_diag_derecho_detener');
                                const otros_diagnosticos_derecho_input = document.getElementById('otros_diagnosticos_derecho_input');
                                const btn_otros_diag_derecho = document.getElementById('play_otros_diag_derecho');
                                btn_otros_diag_derecho.addEventListener('click', () => {
                                    leerTexto(otros_diagnosticos_derecho_input.value);
                                });
                                let recognition_otros_diag_derecho = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
                                recognition_otros_diag_derecho.lang = "es-ES";
                                recognition_otros_diag_derecho.continuous = true;
                                recognition_otros_diag_derecho.interimResults = false;
                                recognition_otros_diag_derecho.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    otros_diagnosticos_derecho_input.value += frase;
                                };
                                otros_diag_derecho_grabar.addEventListener('click', () => {
                                    recognition_otros_diag_derecho.start();
                                });
                                otros_diag_derecho_detener.addEventListener('click', () => {
                                    recognition_otros_diag_derecho.stop();
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
                                <label for="diagnostico_principal_izquierdo"><strong>Diagnóstico Principal - Descripción:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="diag_prin_izquierdo_grabar"><i class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="diag_prin_izquierdo_detener"><i class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_diag_prin_izquierdo"><i class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" name="diagnostico_principal_izquierdo" id="diagnostico_principal_izquierdo" rows="4" placeholder="Ej. Glaucoma de ángulo abierto"></textarea>
                                <script>
                                const diag_prin_izquierdo_grabar = document.getElementById('diag_prin_izquierdo_grabar');
                                const diag_prin_izquierdo_detener = document.getElementById('diag_prin_izquierdo_detener');
                                const diagnostico_principal_izquierdo = document.getElementById('diagnostico_principal_izquierdo');
                                const btn_diag_prin_izquierdo = document.getElementById('play_diag_prin_izquierdo');
                                btn_diag_prin_izquierdo.addEventListener('click', () => {
                                    leerTexto(diagnostico_principal_izquierdo.value);
                                });
                                let recognition_diag_prin_izquierdo = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
                                recognition_diag_prin_izquierdo.lang = "es-ES";
                                recognition_diag_prin_izquierdo.continuous = true;
                                recognition_diag_prin_izquierdo.interimResults = false;
                                recognition_diag_prin_izquierdo.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    others_diagnosticos_izquierdo_input.value += frase;
                                };
                                diag_prin_izquierdo_grabar.addEventListener('click', () => {
                                    recognition_diag_prin_izquierdo.start();
                                });
                                diag_prin_izquierdo_detener.addEventListener('click', () => {
                                    recognition_diag_prin_izquierdo.stop();
                                });
                                </script>
                            </div>
                            <div class="form-group">
                                <label><strong>Código CIE-10:</strong></label>
                                <input type="text" class="form-control" name="codigo_cie_izquierdo" id="codigo_cie_izquierdo" placeholder="Ej. H40.1">
                                <div class="botones mt-2">
                                    <button type="button" class="btn btn-danger btn-sm" id="desc_cie_izquierdo_grabar"><i class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="desc_cie_izquierdo_detener"><i class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_desc_cie_izquierdo"><i class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control mt-2" name="desc_cie_izquierdo" id="desc_cie_izquierdo" rows="2" placeholder="Ej. Glaucoma primario de ángulo abierto"></textarea>
                                <script>
                                const desc_cie_izquierdo_grabar = document.getElementById('desc_cie_izquierdo_grabar');
                                const desc_cie_izquierdo_detener = document.getElementById('desc_cie_izquierdo_detener');
                                const desc_cie_izquierdo = document.getElementById('desc_cie_izquierdo');
                                const btn_desc_cie_izquierdo = document.getElementById('play_desc_cie_izquierdo');
                                btn_desc_cie_izquierdo.addEventListener('click', () => {
                                    leerTexto(desc_cie_izquierdo.value);
                                });
                                let recognition_desc_cie_izquierdo = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
                                recognition_desc_cie_izquierdo.lang = "es-ES";
                                recognition_desc_cie_izquierdo.continuous = true;
                                recognition_desc_cie_izquierdo.interimResults = false;
                                recognition_desc_cie_izquierdo.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    desc_cie_izquierdo.value += frase;
                                };
                                desc_cie_izquierdo_grabar.addEventListener('click', () => {
                                    recognition_desc_cie_izquierdo.start();
                                });
                                desc_cie_izquierdo_detener.addEventListener('click', () => {
                                    recognition_desc_cie_izquierdo.stop();
                                });
                                </script>
                            </div>
                            <div class="form-group">
                                <label><strong>Primera Vez / Subsecuente:</strong></label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipo_diagnostico_izquierdo" id="primera_vez_izquierdo" value="Primera Vez">
                                    <label class="form-check-label" for="primera_vez_izquierdo">Primera Vez</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipo_diagnostico_izquierdo" id="subsecuente_izquierdo" value="Subsecuente">
                                    <label class="form-check-label" for="subsecuente_izquierdo">Subsecuente</label>
                                </div>
                            </div>
                            <div class="form-group" id="otros_diagnosticos_izquierdo_container">
                                <label for="otros_diagnosticos_izquierdo_input"><strong>Otros Diagnósticos:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="otros_diag_izquierdo_grabar"><i class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="otros_diag_izquierdo_detener"><i class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_otros_diag_izquierdo"><i class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" id="otros_diagnosticos_izquierdo_input" rows="2" placeholder="Ej. Catarata incipiente"></textarea>
                                <button type="button" class="btn btn-secondary btn-sm mt-2" id="add_otros_izquierdo">Añadir</button>
                                <table class="table table-bordered table-diagnosticos" id="otros_diagnosticos_izquierdo_table" style="display: none;">
                                    <thead>
                                        <tr>
                                            <th>Diagnóstico</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <script>
                                let otros_izquierdo_count = 0;
                                $('#add_otros_izquierdo').click(function() {
                                    const diagnosis = $('#otros_diagnosticos_izquierdo_input').val().trim();
                                    if (!diagnosis) {
                                        alert('Por favor, ingrese un diagnóstico antes de añadir.');
                                        return;
                                    }
                                    const newRow = `
                                        <tr data-index="${otros_izquierdo_count}">
                                            <td>${diagnosis}</td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm edit-otros-izquierdo">Editar</button>
                                                <button type="button" class="btn btn-danger btn-sm delete-otros-izquierdo">Eliminar</button>
                                            </td>
                                        </tr>
                                    `;
                                    $('#otros_diagnosticos_izquierdo_table tbody').append(newRow);
                                    $('#otros_diagnosticos_izquierdo_table').show();
                                    $('#otros_diagnosticos_izquierdo_input').val('');
                                    otros_izquierdo_count++;
                                });

                                $(document).on('click', '.edit-otros-izquierdo', function() {
                                    const row = $(this).closest('tr');
                                    const diagnosis = row.find('td:first').text();
                                    $('#otros_diagnosticos_izquierdo_input').val(diagnosis);
                                    row.remove();
                                    otros_izquierdo_count--;
                                    if (otros_izquierdo_count === 0) {
                                        $('#otros_diagnosticos_izquierdo_table').hide();
                                    }
                                });

                                $(document).on('click', '.delete-otros-izquierdo', function() {
                                    $(this).closest('tr').remove();
                                    otros_izquierdo_count--;
                                    if (otros_izquierdo_count === 0) {
                                        $('#otros_diagnosticos_izquierdo_table').hide();
                                    }
                                });

                                const otros_diag_izquierdo_grabar = document.getElementById('otros_diag_izquierdo_grabar');
                                const otros_diag_izquierdo_detener = document.getElementById('otros_diag_izquierdo_detener');
                                const otros_diagnosticos_izquierdo_input = document.getElementById('otros_diagnosticos_izquierdo_input');
                                const btn_otros_diag_izquierdo = document.getElementById('play_otros_diag_izquierdo');
                                btn_otros_diag_izquierdo.addEventListener('click', () => {
                                    leerTexto(otros_diagnosticos_izquierdo_input.value);
                                });
                                let recognition_otros_diag_izquierdo = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
                                recognition_otros_diag_izquierdo.lang = "es-ES";
                                recognition_otros_diag_izquierdo.continuous = true;
                                recognition_otros_diag_izquierdo.interimResults = false;
                                recognition_otros_diag_izquierdo.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    otros_diagnosticos_izquierdo_input.value += frase;
                                };
                                otros_diag_izquierdo_grabar.addEventListener('click', () => {
                                    recognition_otros_diag_izquierdo.start();
                                });
                                otros_diag_izquierdo_detener.addEventListener('click', () => {
                                    recognition_otros_diag_izquierdo.stop();
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
        $('#oftalmologicamente_sano, #sin_diagnostico_cie10').change(function() {
            if ($('#oftalmologicamente_sano').is(':checked') || $('#sin_diagnostico_cie10').is(':checked')) {
                $('#usar_diagnosticos_previos_section, #eyeAccordion').hide();
                $('#diagnostico_previo').val('');
                $('#diagnostico_principal_derecho, #codigo_cie_derecho, #desc_cie_derecho, #otros_diagnosticos_derecho_input, #diagnostico_principal_izquierdo, #codigo_cie_izquierdo, #desc_cie_izquierdo, #otros_diagnosticos_izquierdo_input').val('');
                $('input[name="tipo_diagnostico_derecho"], input[name="tipo_diagnostico_izquierdo"]').prop('checked', false);
                $('#otros_diagnosticos_derecho_table tbody, #otros_diagnosticos_izquierdo_table tbody').empty();
                $('#otros_diagnosticos_derecho_table, #otros_diagnosticos_izquierdo_table').hide();
            } else {
                $('#usar_diagnosticos_previos_section, #eyeAccordion').show();
            }
        });
        $('#diagnostico_previo').change(function() {
            if ($(this).val()) {
                $('#diagnostico_principal_derecho').val($(this).val());
                $('#diagnostico_principal_izquierdo').val($(this).val());
            }
        });
    });
    $('form').submit(function() {
        let otros_derecho = '';
        $('#otros_diagnosticos_derecho_table tbody tr').each(function() {
            const diagnosis = $(this).find('td:first').text();
            if (diagnosis) {
                otros_derecho += (otros_derecho ? '; ' : '') + diagnosis;
            }
        });
        $('<input>').attr({
            type: 'hidden',
            name: 'otros_diagnosticos_derecho',
            value: otros_derecho
        }).appendTo('form');
        let otros_izquierdo = '';
        $('#otros_diagnosticos_izquierdo_table tbody tr').each(function() {
            const diagnosis = $(this).find('td:first').text();
            if (diagnosis) {
                otros_izquierdo += (otros_izquierdo ? '; ' : '') + diagnosis;
            }
        });
        $('<input>').attr({
            type: 'hidden',
            name: 'otros_diagnosticos_izquierdo',
            value: otros_izquierdo
        }).appendTo('form');
    });
    </script>
</body>
</html>