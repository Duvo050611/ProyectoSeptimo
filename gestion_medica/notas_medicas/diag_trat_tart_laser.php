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
                // Simplified query to fetch all diagnoses for the Id_exp
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
                            // Store only if at least one principal diagnosis is not empty
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
        <div class="thead"><strong><center>DIAGNÓSTICO</center></strong></div>
        <?php if ($error_message): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
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
                $('#previous_diagnosis_details').hide();
            } else {
                $('#usar_diagnosticos_previos_section, #eyeAccordion').show();
            }
        });

        $('#diagnostico_previo').change(function() {
            const selectedOption = $(this).find('option:selected');
            const details = selectedOption.data('details');
            const value = $(this).val();
            if (value) {
                const [id_diagnostico, eye] = value.split('|');
                $('#previous_diagnosis_details').show();
                $('#details_table_body').empty();

                if (eye === 'right') {
                    $('#diagnostico_principal_derecho').val(details.diagnostico_principal_derecho);
                    $('#codigo_cie_derecho').val(details.codigo_cie_derecho);
                    $('#desc_cie_derecho').val(details.desc_cie_derecho);
                    $(`input[name="tipo_diagnostico_derecho"][value="${details.tipo_diagnostico_derecho}"]`).prop('checked', true);
                    $('#otros_diagnosticos_derecho_input').val(details.otros_diagnosticos_derecho);
                    $('#details_table_body').append(`
                        <tr><td>Diagnóstico Principal (Ojo Derecho)</td><td>${details.diagnostico_principal_derecho || '-'}</td></tr>
                        <tr><td>Código CIE-10 (Ojo Derecho)</td><td>${details.codigo_cie_derecho || '-'}</td></tr>
                        <tr><td>Descripción CIE-10 (Ojo Derecho)</td><td>${details.desc_cie_derecho || '-'}</td></tr>
                        <tr><td>Tipo Diagnóstico (Ojo Derecho)</td><td>${details.tipo_diagnostico_derecho || '-'}</td></tr>
                        <tr><td>Otros Diagnósticos (Ojo Derecho)</td><td>${details.otros_diagnosticos_derecho || '-'}</td></tr>
                        <tr><td>Fecha de Registro</td><td>${new Date(details.fecha_registro).toLocaleDateString('es-ES')}</td></tr>
                    `);
                } else if (eye === 'left') {
                    $('#diagnostico_principal_izquierdo').val(details.diagnostico_principal_izquierdo);
                    $('#codigo_cie_izquierdo').val(details.codigo_cie_izquierdo);
                    $('#desc_cie_izquierdo').val(details.desc_cie_izquierdo);
                    $(`input[name="tipo_diagnostico_izquierdo"][value="${details.tipo_diagnostico_izquierdo}"]`).prop('checked', true);
                    $('#otros_diagnosticos_izquierdo_input').val(details.otros_diagnosticos_izquierdo);
                    $('#details_table_body').append(`
                        <tr><td>Diagnóstico Principal (Ojo Izquierdo)</td><td>${details.diagnostico_principal_izquierdo || '-'}</td></tr>
                        <tr><td>Código CIE-10 (Ojo Izquierdo)</td><td>${details.codigo_cie_izquierdo || '-'}</td></tr>
                        <tr><td>Descripción CIE-10 (Ojo Izquierdo)</td><td>${details.desc_cie_izquierdo || '-'}</td></tr>
                        <tr><td>Tipo Diagnóstico (Ojo Izquierdo)</td><td>${details.tipo_diagnostico_izquierdo || '-'}</td></tr>
                        <tr><td>Otros Diagnósticos (Ojo Izquierdo)</td><td>${details.otros_diagnosticos_izquierdo || '-'}</td></tr>
                        <tr><td>Fecha de Registro</td><td>${new Date(details.fecha_registro).toLocaleDateString('es-ES')}</td></tr>
                    `);
                }
            } else {
                $('#previous_diagnosis_details').hide();
                $('#diagnostico_principal_derecho, #codigo_cie_derecho, #desc_cie_derecho, #otros_diagnosticos_derecho_input').val('');
                $('#diagnostico_principal_izquierdo, #codigo_cie_izquierdo, #desc_cie_izquierdo, #otros_diagnosticos_izquierdo_input').val('');
                $('input[name="tipo_diagnostico_derecho"], input[name="tipo_diagnostico_izquierdo"]').prop('checked', false);
            }
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
    });
    </script>
</body>
</html>