<?php
session_start();
include "../../conexionbd.php";
include "../header_medico.php";

// Check if user is authenticated
if (!isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

// Get logged-in user
$usuario = $_SESSION['login'];

// Fetch previous diagnoses for the dropdown
$previous_diagnoses = [];
$previous_treatments = []; // Initialize array for treatments
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
            
            // Step 2: Fetch previous diagnoses if Id_exp is found
            if ($id_exp) {
                // Fetch diagnoses
                $stmt = $conexion->prepare("
                    SELECT id_diagnostico, diagnostico_principal_derecho, diagnostico_principal_izquierdo, 
                           codigo_cie_derecho, desc_cie_derecho, tipo_diagnostico_derecho, 
                           otros_diagnosticos_derecho, codigo_cie_izquierdo, desc_cie_izquierdo, 
                           tipo_diagnostico_izquierdo, otros_diagnosticos_izquierdo, fecha_registro
                    FROM ocular_diagnostico 
                    WHERE Id_exp = ?
                    ORDER BY fecha_registro DESC
                ");
                if (!$stmt) {
                    $error_message = "Error preparing query for diagnoses: " . $conexion->error;
                    $debug_messages[] = $error_message;
                } else {
                    $stmt->bind_param("s", $id_exp);
                    if ($stmt->execute()) {
                        $result = $stmt->get_result();
                        while ($row = $result->fetch_assoc()) {
                            if (!empty(trim($row['diagnostico_principal_derecho'] ?? '')) || !empty(trim($row['diagnostico_principal_izquierdo'] ?? ''))) {
                                $previous_diagnoses[] = [
                                    'id_diagnostico' => $row['id_diagnostico'],
                                    'diagnostico_principal_derecho' => trim($row['diagnostico_principal_derecho'] ?? ''),
                                    'diagnostico_principal_izquierdo' => trim($row['diagnostico_principal_izquierdo'] ?? ''),
                                    'codigo_cie_derecho' => trim($row['codigo_cie_derecho'] ?? ''),
                                    'desc_cie_derecho' => trim($row['desc_cie_derecho'] ?? ''),
                                    'tipo_diagnostico_derecho' => trim($row['tipo_diagnostico_derecho'] ?? ''),
                                    'otros_diagnosticos_derecho' => trim($row['otros_diagnosticos_derecho'] ?? ''),
                                    'codigo_cie_izquierdo' => trim($row['codigo_cie_izquierdo'] ?? ''),
                                    'desc_cie_izquierdo' => trim($row['desc_cie_izquierdo'] ?? ''),
                                    'tipo_diagnostico_izquierdo' => trim($row['tipo_diagnostico_izquierdo'] ?? ''),
                                    'otros_diagnosticos_izquierdo' => trim($row['otros_diagnosticos_izquierdo'] ?? ''),
                                    'fecha_registro' => $row['fecha_registro']
                                ];
                            }
                        }
                        $debug_messages[] = "Diagnoses found: " . count($previous_diagnoses);
                        $stmt->close();
                    } else {
                        $error_message = "Error executing query for diagnoses: " . $stmt->error;
                        $debug_messages[] = $error_message;
                    }
                }

                // Fetch previous treatments
                $stmt = $conexion->prepare("
                    SELECT id_tratamiento, medicamento_derecho, medicamento_izquierdo, 
                           tipo_tratamiento_derecho, procedimientos_derecho, quirurgico_derecho, 
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
                            // Store treatments for right eye
                            if (!empty(trim($row['medicamento_derecho'] ?? '')) || !empty(trim($row['desc_tratamiento_derecho'] ?? ''))) {
                                $previous_treatments[] = [
                                    'id_tratamiento' => $row['id_tratamiento'],
                                    'eye' => 'right',
                                    'treatment' => trim($row['medicamento_derecho'] ?? '') ?: trim($row['desc_tratamiento_derecho'] ?? ''),
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
                            // Store treatments for left eye
                            if (!empty(trim($row['medicamento_izquierdo'] ?? '')) || !empty(trim($row['desc_tratamiento_izquierdo'] ?? ''))) {
                                $previous_treatments[] = [
                                    'id_tratamiento' => $row['id_tratamiento'],
                                    'eye' => 'left',
                                    'treatment' => trim($row['medicamento_izquierdo'] ?? '') ?: trim($row['desc_tratamiento_izquierdo'] ?? ''),
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

// Debugging: Display debug messages (remove in production)
echo '<div style="display: none;"><pre>Debug Messages: ' . implode("\n", array_map('htmlspecialchars', $debug_messages)) . '</pre></div>';
if ($error_message) {
    echo '<p style="color: red;">' . htmlspecialchars($error_message) . '</p>';
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>AVISO DE ALTA</title>
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
        $('#diagnostico_previo').select2({
            placeholder: "Seleccionar diagnóstico previo",
            allowClear: true
        });
        $('#tratamiento_previo').select2({
            placeholder: "Seleccionar tratamiento previo",
            allowClear: true
        });
        $('#codigo_cie_derecho').select2({
            placeholder: "Selecciona un diagnóstico",
            allowClear: true
        });
        $('#codigo_cie_izquierdo').select2({
            placeholder: "Selecciona un diagnóstico",
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

    .table-diagnosticos,
    .table-tratamientos {
        margin-top: 10px;
    }

    .table-diagnosticos th,
    .table-diagnosticos td,
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
                <center>DIAGNÓSTICO</center>
            </strong></div>
        <?php if ($error_message): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <form action="insertar_diagnostico.php" method="POST" onsubmit="return checkSubmit();">
            <input type="hidden" name="id_usua" value="<?php echo htmlspecialchars($_SESSION['login']['id_usua']); ?>">
            <div class="form-group mt-3">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="oftalmologicamente_sano"
                        id="oftalmologicamente_sano" value="1">
                    <label class="form-check-label" for="oftalmologicamente_sano">Oftalmológicamente Sano</label>
                </div>
                <div class="form-check form-check-inline">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="collapse"
                        data-target="#tratamientoSection" aria-expanded="false"
                        aria-controls="tratamientoSection">Tratamiento</button>
                </div>
                <div class="form-check form-check-inline">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="collapse"
                        data-target="#tratamientoLaserSection" aria-expanded="false"
                        aria-controls="tratamientoLaserSection">Tratamiento Láser</button>
                </div>
            </div>
            <div class="form-group" id="usar_diagnosticos_previos_section">
                <label for="diagnostico_previo"><strong>Usar Diagnósticos Previos:</strong></label>
                <select class="form-control" name="diagnostico_previo" id="diagnostico_previo">
                    <option value="">Seleccionar</option>
                    <?php
                    $unique_diagnoses = [];
                    foreach ($previous_diagnoses as $diag) {
                        $right_diag = $diag['diagnostico_principal_derecho'];
                        $left_diag = $diag['diagnostico_principal_izquierdo'];
                        if (!empty($right_diag) && !in_array($right_diag, $unique_diagnoses)) {
                            $unique_diagnoses[] = $right_diag;
                            echo "<option value=\"{$diag['id_diagnostico']}|right\" data-details='" . json_encode($diag, JSON_UNESCAPED_UNICODE) . "'>" . htmlspecialchars($right_diag) . " (Ojo Derecho, " . date('d/m/Y', strtotime($diag['fecha_registro'])) . ")</option>";
                        }
                        if (!empty($left_diag) && !in_array($left_diag, $unique_diagnoses)) {
                            $unique_diagnoses[] = $left_diag;
                            echo "<option value=\"{$diag['id_diagnostico']}|left\" data-details='" . json_encode($diag, JSON_UNESCAPED_UNICODE) . "'>" . htmlspecialchars($left_diag) . " (Ojo Izquierdo, " . date('d/m/Y', strtotime($diag['fecha_registro'])) . ")</option>";
                        }
                    }
                    if (empty($unique_diagnoses)) {
                        echo '<option value="" disabled>No hay diagnósticos previos disponibles</option>';
                    }
                    ?>
                </select>
                <div id="previous_diagnosis_details" class="mt-3" style="display: none;">
                    <h5>Detalles del Diagnóstico Previo</h5>
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
                                data-target="#collapseRight" aria-expanded="true" aria-controls="collapseRight">Ojo
                                Derecho</button>
                        </h2>
                    </div>
                    <div id="collapseRight" class="collapse show" aria-labelledby="headingRight"
                        data-parent="#eyeAccordion">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="diagnostico_principal_derecho"><strong>Diagnóstico Principal -
                                        Descripción:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="diag_prin_derecho_grabar"><i
                                            class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm"
                                        id="diag_prin_derecho_detener"><i class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_diag_prin_derecho"><i
                                            class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" name="diagnostico_principal_derecho"
                                    id="diagnostico_principal_derecho" rows="4"
                                    placeholder="Ej. Glaucoma de ángulo abierto"></textarea>
                                <script>
                                const diag_prin_derecho_grabar = document.getElementById('diag_prin_derecho_grabar');
                                const diag_prin_derecho_detener = document.getElementById('diag_prin_derecho_detener');
                                const diagnostico_principal_derecho = document.getElementById(
                                    'diagnostico_principal_derecho');
                                const btn_diag_prin_derecho = document.getElementById('play_diag_prin_derecho');
                                btn_diag_prin_derecho.addEventListener('click', () => {
                                    leerTexto(diagnostico_principal_derecho.value);
                                });
                                let recognition_diag_prin_derecho = new(window.SpeechRecognition || window
                                    .webkitSpeechRecognition)();
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
                                <label for="codigo_cie_derecho"><strong>Código CIE-10:</strong></label>
                                <select class="form-control" name="codigo_cie_derecho" id="codigo_cie_derecho">
                                    <option value="">Selecciona un diagnóstico</option>
                                    <?php
                                    include "../../conexionbd.php";
                                    if ($conexion) {
                                        $query = "SELECT id_cie10, diagnostico FROM cat_diag WHERE activo = 'SI' ORDER BY diagnostico ASC";
                                        $result = $conexion->query($query);
                                        if ($result) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<option value='" . htmlspecialchars($row['id_cie10']) . "' data-diagnostico='" . htmlspecialchars($row['diagnostico']) . "'>" . htmlspecialchars($row['diagnostico']) . " (" . htmlspecialchars($row['id_cie10']) . ")</option>";
                                            }
                                            $result->free();
                                        } else {
                                            echo "<option value='' disabled>Error al cargar diagnósticos</option>";
                                        }
                                        $conexion->close();
                                    } else {
                                        echo "<option value='' disabled>Error de conexión a la base de datos</option>";
                                    }
                                    ?>
                                </select>
                                <div class="botones mt-2">
                                    <button type="button" class="btn btn-danger btn-sm" id="desc_cie_derecho_grabar"><i
                                            class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm"
                                        id="desc_cie_derecho_detener"><i class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_desc_cie_derecho"><i
                                            class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control mt-2" name="desc_cie_derecho" id="desc_cie_derecho"
                                    rows="2" placeholder="Descripción del diagnóstico seleccionado"></textarea>
                                <script>
                                const desc_cie_derecho_grabar = document.getElementById('desc_cie_derecho_grabar');
                                const desc_cie_derecho_detener = document.getElementById('desc_cie_derecho_detener');
                                const desc_cie_derecho = document.getElementById('desc_cie_derecho');
                                const btn_desc_cie_derecho = document.getElementById('play_desc_cie_derecho');
                                const codigo_cie_derecho = zor = document.getElementById('codigo_cie_derecho');

                                // Auto-populate description when a diagnosis is selected
                                codigo_cie_derecho.addEventListener('change', function() {
                                    const selectedOption = this.options[this.selectedIndex];
                                    desc_cie_derecho.value = selectedOption ? selectedOption.getAttribute(
                                        'data-diagnostico') : '';
                                });

                                btn_desc_cie_derecho.addEventListener('click', () => {
                                    leerTexto(desc_cie_derecho.value);
                                });

                                let recognition_desc_cie_derecho = new(window.SpeechRecognition || window
                                    .webkitSpeechRecognition)();
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
                                    <input class="form-check-input" type="radio" name="tipo_diagnostico_derecho"
                                        id="primera_vez_derecho" value="Primera Vez">
                                    <label class="form-check-label" for="primera_vez_derecho">Primera Vez</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipo_diagnostico_derecho"
                                        id="subsecuente_derecho" value="Subsecuente">
                                    <label class="form-check-label" for="subsecuente_derecho">Subsecuente</label>
                                </div>
                            </div>
                            <div class="form-group" id="otros_diagnosticos_derecho_container">
                                <label for="otros_diagnosticos_derecho_input"><strong>Otros
                                        Diagnósticos:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm"
                                        id="otros_diag_derecho_grabar"><i class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm"
                                        id="otros_diag_derecho_detener"><i class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_otros_diag_derecho"><i
                                            class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" id="otros_diagnosticos_derecho_input" rows="2"
                                    placeholder="Ej. Catarata incipiente"></textarea>
                                <button type="button" class="btn btn-secondary btn-sm mt-2"
                                    id="add_otros_derecho">Añadir</button>
                                <table class="table table-bordered table-diagnosticos"
                                    id="otros_diagnosticos_derecho_table" style="display: none;">
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
                                    const newRow =
                                        `<tr data-index="${otros_derecho_count}"><td>${diagnosis}</td><td><button type="button" class="btn btn-warning btn-sm edit-otros-derecho">Editar</button><button type="button" class="btn btn-danger btn-sm delete-otros-derecho">Eliminar</button></td></tr>`;
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
                                const otros_diag_derecho_detener = document.getElementById(
                                    'otros_diag_derecho_detener');
                                const otros_diagnosticos_derecho_input = document.getElementById(
                                    'otros_diagnosticos_derecho_input');
                                const btn_otros_diag_derecho = document.getElementById('play_otros_diag_derecho');
                                btn_otros_diag_derecho.addEventListener('click', () => {
                                    leerTexto(otros_diagnosticos_derecho_input.value);
                                });
                                let recognition_otros_diag_derecho = new(window.SpeechRecognition || window
                                    .webkitSpeechRecognition)();
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
                            <button class="btn btn-link text-dark" type="button" data-toggle="collapse"
                                data-target="#collapseLeft" aria-expanded="false" aria-controls="collapseLeft">Ojo
                                Izquierdo</button>
                        </h2>
                    </div>
                    <div id="collapseLeft" class="collapse" aria-labelledby="headingLeft" data-parent="#eyeAccordion">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="diagnostico_principal_izquierdo"><strong>Diagnóstico Principal -
                                        Descripción:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm"
                                        id="diag_prin_izquierdo_grabar"><i class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm"
                                        id="diag_prin_izquierdo_detener"><i
                                            class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm"
                                        id="play_diag_prin_izquierdo"><i class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" name="diagnostico_principal_izquierdo"
                                    id="diagnostico_principal_izquierdo" rows="4"
                                    placeholder="Ej. Glaucoma de ángulo abierto"></textarea>
                                <script>
                                const diag_prin_izquierdo_grabar = document.getElementById(
                                    'diag_prin_izquierdo_grabar');
                                const diag_prin_izquierdo_detener = document.getElementById(
                                    'diag_prin_izquierdo_detener');
                                const diagnostico_principal_izquierdo = document.getElementById(
                                    'diagnostico_principal_izquierdo');
                                const btn_diag_prin_izquierdo = document.getElementById('play_diag_prin_izquierdo');
                                btn_diag_prin_izquierdo.addEventListener('click', () => {
                                    leerTexto(diagnostico_principal_izquierdo.value);
                                });
                                let recognition_diag_prin_izquierdo = new(window.SpeechRecognition || window
                                    .webkitSpeechRecognition)();
                                recognition_diag_prin_izquierdo.lang = "es-ES";
                                recognition_diag_prin_izquierdo.continuous = true;
                                recognition_diag_prin_izquierdo.interimResults = false;
                                recognition_diag_prin_izquierdo.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    diagnostico_principal_izquierdo.value += frase;
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
                                <label for="codigo_cie_izquierdo"><strong>Código CIE-10:</strong></label>
                                <select class="form-control" name="codigo_cie_izquierdo" id="codigo_cie_izquierdo">
                                    <option value="">Selecciona un diagnóstico</option>
                                    <?php
                                    include "../../conexionbd.php";
                                    if ($conexion) {
                                        $query = "SELECT id_cie10, diagnostico FROM cat_diag WHERE activo = 'SI' ORDER BY diagnostico ASC";
                                        $result = $conexion->query($query);
                                        if ($result) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<option value='" . htmlspecialchars($row['id_cie10']) . "' data-diagnostico='" . htmlspecialchars($row['diagnostico']) . "'>" . htmlspecialchars($row['diagnostico']) . " (" . htmlspecialchars($row['id_cie10']) . ")</option>";
                                            }
                                            $result->free();
                                        } else {
                                            echo "<option value='' disabled>Error al cargar diagnósticos</option>";
                                        }
                                        $conexion->close();
                                    } else {
                                        echo "<option value='' disabled>Error de conexión a la base de datos</option>";
                                    }
                                    ?>
                                </select>
                                <div class="botones mt-2">
                                    <button type="button" class="btn btn-danger btn-sm"
                                        id="desc_cie_izquierdo_grabar"><i class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm"
                                        id="desc_cie_izquierdo_detener"><i class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_desc_cie_izquierdo"><i
                                            class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control mt-2" name="desc_cie_izquierdo" id="desc_cie_izquierdo"
                                    rows="2" placeholder="Descripción del diagnóstico seleccionado"></textarea>
                                <script>
                                const desc_cie_izquierdo_grabar = document.getElementById('desc_cie_izquierdo_grabar');
                                const desc_cie_izquierdo_detener = document.getElementById(
                                    'desc_cie_izquierdo_detener');
                                const desc_cie_izquierdo = document.getElementById('desc_cie_izquierdo');
                                const btn_desc_cie_izquierdo = document.getElementById('play_desc_cie_izquierdo');
                                const codigo_cie_izquierdo = document.getElementById('codigo_cie_izquierdo');

                                // Auto-populate description when a diagnosis is selected
                                codigo_cie_izquierdo.addEventListener('change', function() {
                                    const selectedOption = this.options[this.selectedIndex];
                                    desc_cie_izquierdo.value = selectedOption ? selectedOption.getAttribute(
                                        'data-diagnostico') : '';
                                });

                                btn_desc_cie_izquierdo.addEventListener('click', () => {
                                    leerTexto(desc_cie_izquierdo.value);
                                });

                                let recognition_desc_cie_izquierdo = new(window.SpeechRecognition || window
                                    .webkitSpeechRecognition)();
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
                                    <input class="form-check-input" type="radio" name="tipo_diagnostico_izquierdo"
                                        id="primera_vez_izquierdo" value="Primera Vez">
                                    <label class="form-check-label" for="primera_vez_izquierdo">Primera Vez</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipo_diagnostico_izquierdo"
                                        id="subsecuente_izquierdo" value="Subsecuente">
                                    <label class="form-check-label" for="subsecuente_izquierdo">Subsecuente</label>
                                </div>
                            </div>
                            <div class="form-group" id="otros_diagnosticos_izquierdo_container">
                                <label for="otros_diagnosticos_izquierdo_input"><strong>Otros
                                        Diagnósticos:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm"
                                        id="otros_diag_izquierdo_grabar"><i class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm"
                                        id="otros_diag_izquierdo_detener"><i
                                            class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm"
                                        id="play_otros_diag_izquierdo"><i class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" id="otros_diagnosticos_izquierdo_input" rows="2"
                                    placeholder="Ej. Catarata incipiente"></textarea>
                                <button type="button" class="btn btn-secondary btn-sm mt-2"
                                    id="add_otros_izquierdo">Añadir</button>
                                <table class="table table-bordered table-diagnosticos"
                                    id="otros_diagnosticos_izquierdo_table" style="display: none;">
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
                                    const newRow =
                                        `<tr data-index="${otros_izquierdo_count}"><td>${diagnosis}</td><td><button type="button" class="btn btn-warning btn-sm edit-otros-izquierdo">Editar</button><button type="button" class="btn btn-danger btn-sm delete-otros-izquierdo">Eliminar</button></td></tr>`;
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
                                const otros_diag_izquierdo_grabar = document.getElementById(
                                    'otros_diag_izquierdo_grabar');
                                const otros_diag_izquierdo_detener = document.getElementById(
                                    'otros_diag_izquierdo_detener');
                                const otros_diagnosticos_izquierdo_input = document.getElementById(
                                    'otros_diagnosticos_izquierdo_input');
                                const btn_otros_diag_izquierdo = document.getElementById('play_otros_diag_izquierdo');
                                btn_otros_diag_izquierdo.addEventListener('click', () => {
                                    leerTexto(otros_diagnosticos_izquierdo_input.value);
                                });
                                let recognition_otros_diag_izquierdo = new(window.SpeechRecognition || window
                                    .webkitSpeechRecognition)();
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
            <input type="hidden" name="otros_diagnosticos_derecho" id="otros_diagnosticos_derecho">
            <input type="hidden" name="otros_diagnosticos_izquierdo" id="otros_diagnosticos_izquierdo">
            <center class="mt-3">
                <button type="submit" class="btn btn-primary">Firmar</button>
                <button type="button" class="btn btn-danger" onclick="history.back()">Cancelar</button>
            </center>
        </form>
    </div>
    <br><br>
    <div class="container collapse" id="tratamientoSection">
        <div class="thead"><strong>
                <center>TRATAMIENTO</center>
            </strong></div>
        <?php if ($error_message): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <form action="insertar_tratamiento.php" method="POST" onsubmit="return checkSubmitTratamiento();">
            <input type="hidden" name="id_usua" value="<?php echo htmlspecialchars($_SESSION['login']['id_usua']); ?>">
            <div class="form-group" id="usar_tratamientos_previos_section"> <br>
                <label for="tratamiento_previo"><strong>Usar Tratamientos Previos:</strong></label> <br>
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
                        <tbody id="treatment_details_table_body"></tbody>
                    </table>
                </div>
            </div>
            <div class="accordion mt-3" id="treatmentEyeAccordion">
                <div class="card">
                    <div class="card-header" id="headingTreatmentRight">
                        <h2 class="mb-0">
                            <button class="btn btn-link text-dark" type="button" data-toggle="collapse"
                                data-target="#collapseTreatmentRight" aria-expanded="true"
                                aria-controls="collapseTreatmentRight">Ojo Derecho</button>
                        </h2>
                    </div>
                    <div id="collapseTreatmentRight" class="collapse show" aria-labelledby="headingTreatmentRight"
                        data-parent="#treatmentEyeAccordion">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="medicamento_derecho"><strong>Describir medicamento:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="med_derecho_grabar"><i
                                            class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="med_derecho_detener"><i
                                            class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_med_derecho"><i
                                            class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" name="medicamento_derecho" id="medicamento_derecho"
                                    rows="10" placeholder="Ej. Gotas de timolol 0.5%"></textarea>
                                <script>
                                const med_derecho_grabar = document.getElementById('med_derecho_grabar');
                                const med_derecho_detener = document.getElementById('med_derecho_detener');
                                const medicamento_derecho = document.getElementById('medicamento_derecho');
                                const btn_med_derecho = document.getElementById('play_med_derecho');
                                btn_med_derecho.addEventListener('click', () => {
                                    leerTexto(medicamento_derecho.value);
                                });
                                let recognition_med_derecho = new(window.SpeechRecognition || window
                                    .webkitSpeechRecognition)();
                                recognition_med_derecho.lang = "es-ES";
                                recognition_med_derecho.continuous = true;
                                recognition_med_derecho.interimResults = false;
                                recognition_med_derecho.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    medicamento_derecho.value += frase;
                                };
                                med_derecho_grabar.addEventListener('click', () => {
                                    recognition_med_derecho.start();
                                });
                                med_derecho_detener.addEventListener('click', () => {
                                    recognition_med_derecho.stop();
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
                                <label for="procedimientos_derecho"><strong>Procedimientos:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="proc_derecho_grabar"><i
                                            class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="proc_derecho_detener"><i
                                            class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_proc_derecho"><i
                                            class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" name="procedimientos_derecho" id="procedimientos_derecho"
                                    rows="10" placeholder="Ej. Tonometría"></textarea>
                                <script>
                                const proc_derecho_grabar = document.getElementById('proc_derecho_grabar');
                                const proc_derecho_detener = document.getElementById('proc_derecho_detener');
                                const procedimientos_derecho = document.getElementById('procedimientos_derecho');
                                const btn_proc_derecho = document.getElementById('play_proc_derecho');
                                btn_proc_derecho.addEventListener('click', () => {
                                    leerTexto(procedimientos_derecho.value);
                                });
                                let recognition_proc_derecho = new(window.SpeechRecognition || window
                                    .webkitSpeechRecognition)();
                                recognition_proc_derecho.lang = "es-ES";
                                recognition_proc_derecho.continuous = true;
                                recognition_proc_derecho.interimResults = false;
                                recognition_proc_derecho.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    procedimientos_derecho.value += frase;
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
                                    rows="10" placeholder="Ej. Trabeculectomía programada"></textarea>
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
                    <div class="card-header" id="headingTreatmentLeft">
                        <h2 class="mb-0">
                            <button class="btn btn-link text-dark" type="button" data-toggle="collapse"
                                data-target="#collapseTreatmentLeft" aria-expanded="false"
                                aria-controls="collapseTreatmentLeft">Ojo Izquierdo</button>
                        </h2>
                    </div>
                    <div id="collapseTreatmentLeft" class="collapse" aria-labelledby="headingTreatmentLeft"
                        data-parent="#treatmentEyeAccordion">
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
                                <textarea class="form-control" name="medicamento_izquierdo" id="medicamento_izquierdo"
                                    rows="10" placeholder="Ej. Gotas de timolol 0.5%"></textarea>
                                <script>
                                const med_izquierdo_grabar = document.getElementById('med_izquierdo_grabar');
                                const med_izquierdo_detener = document.getElementById('med_izquierdo_detener');
                                const medicamento_izquierdo = document.getElementById('medicamento_izquierdo');
                                const btn_med_izquierdo = document.getElementById('play_med_izquierdo');
                                btn_med_izquierdo.addEventListener('click', () => {
                                    leerTexto(medicamento_izquierdo.value);
                                });
                                let recognition_med_izquierdo = new(window.SpeechRecognition || window
                                    .webkitSpeechRecognition)();
                                recognition_med_izquierdo.lang = "es-ES";
                                recognition_med_izquierdo.continuous = true;
                                recognition_med_izquierdo.interimResults = false;
                                recognition_med_izquierdo.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    medicamento_izquierdo.value += frase;
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
                                <label for="procedimientos_izquierdo"><strong>Procedimientos:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="proc_izquierdo_grabar"><i
                                            class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="proc_izquierdo_detener"><i
                                            class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_proc_izquierdo"><i
                                            class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" name="procedimientos_izquierdo"
                                    id="procedimientos_izquierdo" rows="10" placeholder="Ej. Tonometría"></textarea>
                                <script>
                                const proc_izquierdo_grabar = document.getElementById('proc_izquierdo_grabar');
                                const proc_izquierdo_detener = document.getElementById('proc_izquierdo_detener');
                                const procedimientos_izquierdo = document.getElementById('procedimientos_izquierdo');
                                const btn_proc_izquierdo = document.getElementById('play_proc_izquierdo');
                                btn_proc_izquierdo.addEventListener('click', () => {
                                    leerTexto(procedimientos_izquierdo.value);
                                });
                                let recognition_proc_izquierdo = new(window.SpeechRecognition || window
                                    .webkitSpeechRecognition)();
                                recognition_proc_izquierdo.lang = "es-ES";
                                recognition_proc_izquierdo.continuous = true;
                                recognition_proc_izquierdo.interimResults = false;
                                recognition_proc_izquierdo.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    procedimientos_izquierdo.value += frase;
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
                                    rows="10" placeholder="Ej. Trabeculectomía programada"></textarea>
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
                <button type="submit" class="btn btn-primary">Firmar</button>
                <button type="button" class="btn btn-danger" onclick="history.back()">Cancelar</button>
            </center>
        </form>
    </div>
    <br><br>
    <div class="container collapse" id="tratamientoLaserSection">
        <div class="thead"><strong>
                <center>TRATAMIENTO LÁSER</center>
            </strong></div>
        <form action="insertar_tratamiento_laser.php" method="POST">
            <input type="hidden" name="id_usua" value="<?php echo htmlspecialchars($_SESSION['login']['id_usua']); ?>">
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
                                <label for="tipo_laser_derecho"><strong>Tipo de Tratamiento Láser:</strong></label>
                                <select class="form-control" name="tipo_laser_derecho" id="tipo_laser_derecho">
                                    <option value="Selecciona">Selecciona</option>
                                    <option value="YAG">YAG</option>
                                    <option value="Argon">Argon</option>
                                    <option value="Nd:YAG">Nd:YAG</option>
                                    <option value="Diode">Diode</option>
                                    <option value="Otros">Otros</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="detalles_laser_derecho"><strong>Detalles del Tratamiento:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="det_laser_derecho_grabar"><i
                                            class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm"
                                        id="det_laser_derecho_detener"><i class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="play_det_laser_derecho"><i
                                            class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" name="detalles_laser_derecho" id="detalles_laser_derecho"
                                    rows="4" placeholder="Ej. Aplicación de láser YAG, potencia 2W"></textarea>
                                <script>
                                const det_laser_derecho_grabar = document.getElementById('det_laser_derecho_grabar');
                                const det_laser_derecho_detener = document.getElementById('det_laser_derecho_detener');
                                const detalles_laser_derecho = document.getElementById('detalles_laser_derecho');
                                const btn_det_laser_derecho = document.getElementById('play_det_laser_derecho');
                                btn_det_laser_derecho.addEventListener('click', () => {
                                    leerTexto(detalles_laser_derecho.value);
                                });
                                let recognition_det_laser_derecho = new webkitSpeechRecognition();
                                recognition_det_laser_derecho.lang = "es-ES";
                                recognition_det_laser_derecho.continuous = true;
                                recognition_det_laser_derecho.interimResults = false;
                                recognition_det_laser_derecho.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    detalles_laser_derecho.value += frase;
                                };
                                det_laser_derecho_grabar.addEventListener('click', () => {
                                    recognition_det_laser_derecho.start();
                                });
                                det_laser_derecho_detener.addEventListener('click', () => {
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
                                <label for="tipo_laser_izquierdo"><strong>Tipo de Tratamiento Láser:</strong></label>
                                <select class="form-control" name="tipo_laser_izquierdo" id="tipo_laser_izquierdo">
                                    <option value="Selecciona">Selecciona</option>
                                    <option value="YAG">YAG</option>
                                    <option value="Argon">Argon</option>
                                    <option value="Nd:YAG">Nd:YAG</option>
                                    <option value="Diode">Diode</option>
                                    <option value="Otros">Otros</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="detalles_laser_izquierdo"><strong>Detalles del Tratamiento:</strong></label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm"
                                        id="det_laser_izquierdo_grabar"><i class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm"
                                        id="det_laser_izquierdo_detener"><i
                                            class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm"
                                        id="play_det_laser_izquierdo"><i class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" name="detalles_laser_izquierdo"
                                    id="detalles_laser_izquierdo" rows="4"
                                    placeholder="Ej. Aplicación de láser YAG, potencia 2W"></textarea>
                                <script>
                                const det_laser_izquierdo_grabar = document.getElementById(
                                    'det_laser_izquierdo_grabar');
                                const det_laser_izquierdo_detener = document.getElementById(
                                    'det_laser_izquierdo_detener');
                                const detalles_laser_izquierdo = document.getElementById('detalles_laser_izquierdo');
                                const btn_det_laser_izquierdo = document.getElementById('play_det_laser_izquierdo');
                                btn_det_laser_izquierdo.addEventListener('click', () => {
                                    leerTexto(detalles_laser_izquierdo.value);
                                });
                                let recognition_det_laser_izquierdo = new webkitSpeechRecognition();
                                recognition_det_laser_izquierdo.lang = "es-ES";
                                recognition_det_laser_izquierdo.continuous = true;
                                recognition_det_laser_izquierdo.interimResults = false;
                                recognition_det_laser_izquierdo.onresult = (event) => {
                                    const results = event.results;
                                    const frase = results[results.length - 1][0].transcript;
                                    detalles_laser_izquierdo.value += frase;
                                };
                                det_laser_izquierdo_grabar.addEventListener('click', () => {
                                    recognition_det_laser_izquierdo.start();
                                });
                                det_laser_izquierdo_detener.addEventListener('click', () => {
                                    recognition_det_laser_izquierdo.abort();
                                });
                                </script>
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
    function checkSubmit() {
        const sano = document.getElementById('oftalmologicamente_sano').checked;
        const sin_cie10 = document.getElementById('sin_diagnostico_cie10').checked;
        const diag_derecho = document.getElementById('diagnostico_principal_derecho').value.trim();
        const diag_izquierdo = document.getElementById('diagnostico_principal_izquierdo').value.trim();
        const codigo_derecho = document.getElementById('codigo_cie_derecho').value.trim();
        const codigo_izquierdo = document.getElementById('codigo_cie_izquierdo').value.trim();
        const otros_derecho = $('#otros_diagnosticos_derecho_table tbody tr').length;
        const otros_izquierdo = $('#otros_diagnosticos_izquierdo_table tbody tr').length;
        if (sano && (diag_derecho || diag_izquierdo || codigo_derecho || codigo_izquierdo || otros_derecho > 0 ||
                otros_izquierdo > 0)) {
            alert('No puede marcar "Oftalmológicamente Sano" y registrar diagnósticos al mismo tiempo.');
            return false;
        }
        if (!sano && !sin_cie10 && !diag_derecho && !diag_izquierdo && !otros_derecho && !otros_izquierdo) {
            alert(
                'Por favor, ingrese al menos un diagnóstico principal u otro diagnóstico, o marque "Oftalmológicamente Sano" o "Sin Diagnóstico CIE-10".'
            );
            return false;
        }
        return true;
    }

    function checkSubmitTratamiento() {
        const med_derecho = $('#medicamento_derecho_table tbody tr').length;
        const med_izquierdo = $('#medicamento_izquierdo_table tbody tr').length;
        const proc_derecho = $('#procedimientos_derecho_table tbody tr').length;
        const proc_izquierdo = $('#procedimientos_izquierdo_table tbody tr').length;
        const quir_derecho = document.getElementById('quirurgico_derecho').value.trim();
        const quir_izquierdo = document.getElementById('quirurgico_izquierdo').value.trim();
        if (!med_derecho && !med_izquierdo && !proc_derecho && !proc_izquierdo && !quir_derecho && !quir_izquierdo) {
            alert('Por favor, ingrese al menos un medicamento, procedimiento o tratamiento quirúrgico.');
            return false;
        }
        return true;
    }
    $('#diagnostico_previo').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const details = selectedOption.data('details');
        const value = $(this).val();
        const detailsTableBody = $('#details_table_body');
        detailsTableBody.empty();
        if (value && details) {
            const eye = value.split('|')[1];
            const fields = [{
                    label: 'Diagnóstico Principal',
                    value: details['diagnostico_principal_' + eye]
                },
                {
                    label: 'Código CIE-10',
                    value: details['codigo_cie_' + eye]
                },
                {
                    label: 'Descripción CIE-10',
                    value: details['desc_cie_' + eye]
                },
                {
                    label: 'Tipo Diagnóstico',
                    value: details['tipo_diagnostico_' + eye]
                },
                {
                    label: 'Otros Diagnósticos',
                    value: details['otros_diagnosticos_' + eye]
                },
                {
                    label: 'Fecha de Registro',
                    value: details['fecha_registro']
                }
            ];
            fields.forEach(field => {
                if (field.value) {
                    detailsTableBody.append(`<tr><td>${field.label}</td><td>${field.value}</td></tr>`);
                }
            });
            $('#previous_diagnosis_details').show();
            const targetSection = eye === 'right' ? '#collapseRight' : '#collapseLeft';
            $(targetSection).collapse('show');
            $('#diagnostico_principal_' + eye).val(details['diagnostico_principal_' + eye]);
            $('#codigo_cie_' + eye).val(details['codigo_cie_' + eye]);
            $('#desc_cie_' + eye).val(details['desc_cie_' + eye]);
            const tipoDiagnostico = details['tipo_diagnostico_' + eye];
            if (tipoDiagnostico) {
                $(`#${tipoDiagnostico.toLowerCase().replace(' ', '_')}_${eye}`).prop('checked', true);
            }
            const otrosDiagnosticos = details['otros_diagnosticos_' + eye];
            if (otrosDiagnosticos) {
                const table = $(`#otros_diagnosticos_${eye}_table`);
                table.find('tbody').empty();
                otrosDiagnosticos.split(',').forEach((diag, index) => {
                    diag = diag.trim();
                    if (diag) {
                        table.find('tbody').append(
                            `<tr data-index="${index}"><td>${diag}</td><td><button type="button" class="btn btn-warning btn-sm edit-otros-${eye}">Editar</button><button type="button" class="btn btn-danger btn-sm delete-otros-${eye}">Eliminar</button></td></tr>`
                        );
                        window[`otros_${eye}_count`] = index + 1;
                    }
                });
                table.show();
            }
        } else {
            $('#previous_diagnosis_details').hide();
        }
    });
    $('#tratamiento_previo').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const details = selectedOption.data('details');
        const value = $(this).val();
        const detailsTableBody = $('#treatment_details_table_body');
        detailsTableBody.empty();
        if (value && details) {
            const eye = value.split('|')[1];
            const fields = [{
                    label: 'Medicamento',
                    value: details['medicamento']
                },
                {
                    label: 'Código Tratamiento',
                    value: details['codigo_tratamiento']
                },
                {
                    label: 'Descripción Tratamiento',
                    value: details['desc_tratamiento']
                },
                {
                    label: 'Tipo Tratamiento',
                    value: details['tipo_tratamiento']
                },
                {
                    label: 'Procedimientos',
                    value: details['procedimientos']
                },
                {
                    label: 'Quirúrgico',
                    value: details['quirurgico']
                },
                {
                    label: 'Fecha de Registro',
                    value: details['fecha_registro']
                }
            ];
            fields.forEach(field => {
                if (field.value) {
                    detailsTableBody.append(`<tr><td>${field.label}</td><td>${field.value}</td></tr>`);
                }
            });
            $('#previous_treatment_details').show();
            const targetSection = eye === 'right' ? '#collapseTreatmentRight' : '#collapseTreatmentLeft';
            $(targetSection).collapse('show');
            const medTable = $(`#medicamento_${eye}_table`);
            medTable.find('tbody').empty();
            if (details['medicamento']) {
                details['medicamento'].split(',').forEach((med, index) => {
                    med = med.trim();
                    if (med) {
                        medTable.find('tbody').append(
                            `<tr data-index="${index}"><td>${med}</td><td><button type="button" class="btn btn-warning btn-sm edit-med-${eye}">Editar</button><button type="button" class="btn btn-danger btn-sm delete-med-${eye}">Eliminar</button></td></tr>`
                        );
                        window[`med_${eye}_count`] = index + 1;
                    }
                });
                medTable.show();
            }
            $(`#codigo_tratamiento_${eye}`).val(details['codigo_tratamiento']);
            $(`#desc_tratamiento_${eye}`).val(details['desc_tratamiento']);
            const tipoTratamiento = details['tipo_tratamiento'];
            if (tipoTratamiento) {
                $(`#${tipoTratamiento.toLowerCase().replace(' ', '_')}_${eye}`).prop('checked', true);
            }
            const procTable = $(`#procedimientos_${eye}_table`);
            procTable.find('tbody').empty();
            if (details['procedimientos']) {
                details['procedimientos'].split(',').forEach((proc, index) => {
                    proc = proc.trim();
                    if (proc) {
                        procTable.find('tbody').append(
                            `<tr data-index="${index}"><td>${proc}</td><td><button type="button" class="btn btn-warning btn-sm edit-proc-${eye}">Editar</button><button type="button" class="btn btn-danger btn-sm delete-proc-${eye}">Eliminar</button></td></tr>`
                        );
                        window[`proc_${eye}_count`] = index + 1;
                    }
                });
                procTable.show();
            }
            $(`#quirurgico_${eye}`).val(details['quirurgico']);
        } else {
            $('#previous_treatment_details').hide();
        }
    });
    // Collect "Otros Diagnósticos" from tables before form submission
    document.querySelector('form').addEventListener('submit', function() {
        // Right Eye
        const otrosDerechoRows = document.querySelectorAll('#otros_diagnosticos_derecho_table tbody tr');
        const otrosDerechoDiagnoses = Array.from(otrosDerechoRows).map(row => row.querySelector(
            'td:first-child').textContent);
        document.getElementById('otros_diagnosticos_derecho').value = otrosDerechoDiagnoses.join('; ');

        // Left Eye
        const otrosIzquierdoRows = document.querySelectorAll('#otros_diagnosticos_izquierdo_table tbody tr');
        const otrosIzquierdoDiagnoses = Array.from(otrosIzquierdoRows).map(row => row.querySelector(
            'td:first-child').textContent);
        document.getElementById('otros_diagnosticos_izquierdo').value = otrosIzquierdoDiagnoses.join('; ');
    });
    // Collect table data before form submission
    document.querySelector('form').addEventListener('submit', function() {
        // Right Eye - Medicamentos
        const medDerechoRows = document.querySelectorAll('#medicamento_derecho_table tbody tr');
        const medDerechoList = Array.from(medDerechoRows).map(row => row.querySelector('td:first-child')
            .textContent);
        document.getElementById('medicamento_derecho').value = medDerechoList.join('; ');

        // Left Eye - Medicamentos
        const medIzquierdoRows = document.querySelectorAll('#medicamento_izquierdo_table tbody tr');
        const medIzquierdoList = Array.from(medIzquierdoRows).map(row => row.querySelector('td:first-child')
            .textContent);
        document.getElementById('medicamento_izquierdo').value = medIzquierdoList.join('; ');

        // Right Eye - Procedimientos
        const procDerechoRows = document.querySelectorAll('#procedimientos_derecho_table tbody tr');
        const procDerechoList = Array.from(procDerechoRows).map(row => row.querySelector('td:first-child')
            .textContent);
        document.getElementById('procedimientos_derecho').value = procDerechoList.join('; ');

        // Left Eye - Procedimientos
        const procIzquierdoRows = document.querySelectorAll('#procedimientos_izquierdo_table tbody tr');
        const procIzquierdoList = Array.from(procIzquierdoRows).map(row => row.querySelector('td:first-child')
            .textContent);
        document.getElementById('procedimientos_izquierdo').value = procIzquierdoList.join('; ');
    });
    </script>
</body>

</html>