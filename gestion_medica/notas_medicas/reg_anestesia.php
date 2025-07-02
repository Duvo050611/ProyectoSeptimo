<?php
session_start();
include "../../conexionbd.php";
include "../header_medico.php";

if (!isset($_SESSION['login']['id_usua']) || !isset($_SESSION['hospital'])) {
    header("Location: ../../index.php");
    exit;
}

// Fetch active anesthesiologists
$sql_anesthesiologists = "SELECT id_usua, CONCAT(nombre, ' ', papell, ' ', sapell) AS full_name 
                         FROM reg_usuarios 
                         WHERE u_activo = 'SI'
                           AND (
                             cargp COLLATE utf8mb4_general_ci = 'anestesiologo' OR
                             cargp COLLATE utf8mb4_general_ci = 'anestesiologa'
                           )
                         ORDER BY full_name";
$result_anesthesiologists = $conexion->query($sql_anesthesiologists);
$anesthesiologists = [];
if ($result_anesthesiologists) {
    while ($row = $result_anesthesiologists->fetch_assoc()) {
        $anesthesiologists[] = $row;
    }
}

// Fetch active surgeons
$sql_surgeons = "SELECT id_usua, CONCAT(nombre, ' ', papell, ' ', sapell) AS full_name 
                 FROM reg_usuarios 
                 WHERE u_activo = 'SI' 
                  AND UPPER(cargp) IN ('CIRUJANO') 
                 ORDER BY full_name";
$result_surgeons = $conexion->query($sql_surgeons);
$surgeons = [];
if ($result_surgeons) {
    while ($row = $result_surgeons->fetch_assoc()) {
        $surgeons[] = $row;
    }
}

// Fetch active assistants (enfermero/enfermera)
$sql_assistants = "SELECT id_usua, CONCAT(nombre, ' ', papell, ' ', sapell) AS full_name, cargp 
                   FROM reg_usuarios 
                   WHERE u_activo = 'SI' 
                     AND UPPER(cargp) IN ('ENFERMERO', 'ENFERMERA')
                   ORDER BY full_name";
$result_assistants = $conexion->query($sql_assistants);
$assistants = [];
if ($result_assistants) {
    while ($row = $result_assistants->fetch_assoc()) {
        $assistants[] = $row;
    }
}

// Fetch patient data
if (isset($_SESSION['hospital'])) {
    $id_atencion = filter_var($_SESSION['hospital'], FILTER_VALIDATE_INT);
    if (!$id_atencion) {
        header("Location: ../../reg_anestesia.php");
        exit;
    }
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
    $area = "No asignada";
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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="Content-Security-Policy"
        content="default-src 'self'; script-src 'self' 'unsafe-inline' https://code.jquery.com https://cdn.jsdelivr.net https://stackpath.bootstrapcdn.com; style-src 'self' 'unsafe-inline' https://use.fontawesome.com https://stackpath.bootstrapcdn.com;">
    <title>Registro Anestésico - Instituto de Enfermedades Oculares</title>
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
    <style>
    .thead {
        background-color: #2b2d7f;
        color: white;
        font-size: 22px;
        padding: 10px;
        text-align: center;
    }
    .botones {
        margin-bottom: 5px;
    }
    .form-check-inline {
        margin-right: 20px;
    }
    .form-section {
        margin-bottom: 20px;
        padding: 15px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
    }
    .hidden {
        display: none;
    }
    .data-display {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .nav-tabs {
        border-bottom: 2px solid #2b2d7f;
        background-color: #f8f9fa;
    }
    .nav-tabs .nav-link {
        color: #2b2d7f;
        font-weight: 600;
        padding: 10px 20px;
        border: none;
        border-bottom: 3px solid transparent;
    }
    .nav-tabs .nav-link:hover {
        border-bottom: 3px solid #2b2d7f;
        background-color: #e9ecef;
    }
    .nav-tabs .nav-link.active {
        color: #2b2d7f;
        border-bottom: 3px solid #2b2d7f;
        background-color: #fff;
    }
    .tab-content {
        padding: 20px;
        border: 1px solid #dee2e6;
        border-top: none;
        border-radius: 0 0 5px 5px;
        background-color: #fff;
    }
    .form-group label {
        font-weight: 600;
    }
    .invalid-feedback {
        display: none;
    }
    .is-invalid~.invalid-feedback {
        display: block;
    }
    .tooltip-icon {
        margin-left: 5px;
        cursor: help;
    }
    .vital-signs-table th,
    .vital-signs-table td {
        padding: 5px;
        border: 1px solid #dee2e6;
        text-align: center;
    }
    .graph-section {
        margin-top: 20px;
    }
    .aldrete-table th,
    .aldrete-table td {
        padding: 5px;
        border: 1px solid #dee2e6;
    }
    .select2-assistants {
        width: 100%;
    }
    .selected-assistants {
        margin-top: 10px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .selected-assistant {
        display: inline-flex;
        align-items: center;
        background-color: #e9ecef;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 14px;
    }
    .selected-assistant .remove-assistant {
        margin-left: 8px;
        cursor: pointer;
        color: #dc3545;
        font-weight: bold;
        font-size: 16px;
    }
    .selected-assistant .remove-assistant:hover {
        color: #a71d2a;
    }
    </style>
</head>
<body>
    <div class="container mt-4">
        <?php if (isset($_SESSION['message']) && isset($_SESSION['message_type'])): ?>
        <div class="alert alert-<?php echo htmlspecialchars($_SESSION['message_type']); ?> alert-dismissible fade show"
            role="alert">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
        <?php endif; ?>

        <div class="row">
            <div class="col">
                <div class="thead"><strong><center>DATOS DEL PACIENTE</center></strong></div>
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
                    <div class="col-sm-4">Área: <strong><?php echo $area; ?> </strong></div>
                </div>
            </div>
        </div>
        <br>

        <div class="thead"><strong>REGISTRO ANESTÉSICO</strong></div>
        <br>
        <form action="insertar_registro_anestesico.php" method="POST" onsubmit="return checkSubmit();" id="anestheticRecordForm">
            <input type="hidden" name="Id_exp" value="<?php echo htmlspecialchars($id_exp); ?>">
            <input type="hidden" name="id_usua" value="<?php echo htmlspecialchars($_SESSION['login']['id_usua']); ?>">
            <input type="hidden" name="id_atencion" value="<?php echo htmlspecialchars($_SESSION['hospital']); ?>">

            <!-- Horizontal Tabs Navigation -->
            <ul class="nav nav-tabs" id="anestheticTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="generalInfo-tab" data-toggle="tab" href="#generalInfo" role="tab"
                        aria-controls="generalInfo" aria-selected="true">Información General</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="anesthesiaDetails-tab" data-toggle="tab" href="#anesthesiaDetails"
                        role="tab" aria-controls="anesthesiaDetails" aria-selected="false">Detalles de Anestesia</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="intubationVentilation-tab" data-toggle="tab" href="#intubationVentilation"
                        role="tab" aria-controls="intubationVentilation" aria-selected="false">Intubación y Ventilación</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="fluidsBalance-tab" data-toggle="tab" href="#fluidsBalance" role="tab"
                        aria-controls="fluidsBalance" aria-selected="false">Líquidos y Balance</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="aldreteScore-tab" data-toggle="tab" href="#aldreteScore" role="tab"
                        aria-controls="aldreteScore" aria-selected="false">Puntuación Aldrete</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="regionalAnesthesia-tab" data-toggle="tab" href="#regionalAnesthesia"
                        role="tab" aria-controls="regionalAnesthesia" aria-selected="false">Anestesia Regional</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="timings-tab" data-toggle="tab" href="#timings" role="tab"
                        aria-controls="timings" aria-selected="false">Tiempos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="vitalSigns-tab" data-toggle="tab" href="#vitalSigns" role="tab"
                        aria-controls="vitalSigns" aria-selected="false">Signos Vitales</a>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="anestheticTabContent">
                <!-- Información General -->
                <div class="tab-pane fade show active" id="generalInfo" role="tabpanel" aria-labelledby="generalInfo-tab">
                    <div class="form-section">
                        <div class="form-group">
                            <label for="anestesiologo_id">Anestesiólogo:</label>
                            <select class="form-control select2" name="anestesiologo_id" id="anestesiologo_id" required>
                                <option value="">Seleccione un anestesiólogo</option>
                                <?php foreach ($anesthesiologists as $anesthesiologist): ?>
                                <option value="<?php echo htmlspecialchars($anesthesiologist['id_usua']); ?>">
                                    <?php echo htmlspecialchars($anesthesiologist['full_name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Seleccione un anestesiólogo válido.</div>
                        </div>
                        <div class="form-group">
                            <label for="tipo_anestesia">Tipo de Anestesia:</label>
                            <select class="form-control" name="tipo_anestesia" id="tipo_anestesia" required>
                                <option value="">Seleccione</option>
                                <option value="General">General</option>
                                <option value="Regional">Regional</option>
                                <option value="Local">Local</option>
                                <option value="Sedación">Sedación</option>
                            </select>
                            <div class="invalid-feedback">Seleccione un tipo de anestesia.</div>
                        </div>
                        <div class="form-group">
                            <label for="diagnostico_preoperatorio">Diagnóstico Preoperatorio:</label>
                            <div class="botones">
                                <button type="button" class="btn btn-danger btn-sm voice-btn" data-target="diagnostico_preoperatorio"><i class="fas fa-microphone"></i></button>
                                <button type="button" class="btn btn-primary btn-sm mute-btn" data-target="diagnostico_preoperatorio"><i class="fas fa-volume-mute"></i></button>
                                <button type="button" class="btn btn-success btn-sm play-btn" data-target="diagnostico_preoperatorio"><i class="fas fa-play"></i></button>
                            </div>
                            <textarea class="form-control" name="diagnostico_preoperatorio" id="diagnostico_preoperatorio" rows="4" placeholder="Ejemplo: Catarata en ojo derecho (OD)"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="cirugia_programada">Cirugía Programada:</label>
                            <div class="botones">
                                <button type="button" class="btn btn-danger btn-sm voice-btn" data-target="cirugia_programada"><i class="fas fa-microphone"></i></button>
                                <button type="button" class="btn btn-primary btn-sm mute-btn" data-target="cirugia_programada"><i class="fas fa-volume-mute"></i></button>
                                <button type="button" class="btn btn-success btn-sm play-btn" data-target="cirugia_programada"><i class="fas fa-play"></i></button>
                            </div>
                            <textarea class="form-control" name="cirugia_programada" id="cirugia_programada" rows="4" placeholder="Ejemplo: Facoemulsificación con implante de LIO en OD"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="diagnostico_postoperatorio">Diagnóstico Postoperatorio:</label>
                            <div class="botones">
                                <button type="button" class="btn btn-danger btn-sm voice-btn" data-target="diagnostico_postoperatorio"><i class="fas fa-microphone"></i></button>
                                <button type="button" class="btn btn-primary btn-sm mute-btn" data-target="diagnostico_postoperatorio"><i class="fas fa-volume-mute"></i></button>
                                <button type="button" class="btn btn-success btn-sm play-btn" data-target="diagnostico_postoperatorio"><i class="fas fa-play"></i></button>
                            </div>
                            <textarea class="form-control" name="diagnostico_postoperatorio" id="diagnostico_postoperatorio" rows="4" placeholder="Ejemplo: Catarata resuelta, LIO implantado en OD"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="cirugia_realizada">Cirugía Realizada:</label>
                            <div class="botones">
                                <button type="button" class="btn btn-danger btn-sm voice-btn" data-target="cirugia_realizada"><i class="fas fa-microphone"></i></button>
                                <button type="button" class="btn btn-primary btn-sm mute-btn" data-target="cirugia_realizada"><i class="fas fa-volume-mute"></i></button>
                                <button type="button" class="btn btn-success btn-sm play-btn" data-target="cirugia_realizada"><i class="fas fa-play"></i></button>
                            </div>
                            <textarea class="form-control" name="cirugia_realizada" id="cirugia_realizada" rows="4" placeholder="Ejemplo: Facoemulsificación exitosa con LIO en OD"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="cirujano_id">Cirujano:</label>
                            <select class="form-control select2" name="cirujano_id" id="cirujano_id" required>
                                <option value="">Seleccione un cirujano</option>
                                <?php foreach ($surgeons as $surgeon): ?>
                                <option value="<?php echo htmlspecialchars($surgeon['id_usua']); ?>">
                                    <?php echo htmlspecialchars($surgeon['full_name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Seleccione un cirujano.</div>
                        </div>
                        <div class="form-group">
                            <label for="ayudantes">Ayudantes:</label>
                            <select class="form-control select2-assistants" name="ayudantes_ids[]" id="ayudantes" multiple>
                                <option value="">Seleccione ayudantes</option>
                                <?php foreach ($assistants as $assistant): ?>
                                <option value="<?php echo htmlspecialchars($assistant['id_usua']); ?>">
                                    <?php echo htmlspecialchars($assistant['full_name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="selected-assistants" id="selectedAssistants"></div>
                        </div>
                    </div>
                </div>

                <!-- Signos Vitales -->
                <div class="tab-pane fade" id="vitalSigns" role="tabpanel" aria-labelledby="vitalSigns-tab">
                    <div class="form-section">
                        <div class="thead"><strong><center>SIGNOS VITALES</center></strong></div>
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-2">
                                    <center><strong>Presión arterial:</strong></center>
                                    <div class="row">
                                        <div class="col losInputTAM">
                                            <input type="text" class="form-control" id="sistg" name="sistg" placeholder="SISTÓLICA" pattern="\d{2,3}" title="Ingrese presión sistólica (ej. 120)">
                                        </div>
                                        <div class="col-auto">/</div>
                                        <div class="col losInputTAM">
                                            <input type="text" class="form-control" id="diastg" name="diastg" placeholder="DIASTÓLICA" pattern="\d{2,3}" title="Ingrese presión diastólica (ej. 80)">
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">Ingrese presión arterial en formato correcto (ej. 120 para sistólica, 80 para diastólica).</div>
                                </div>
                                <div class="col-sm-2">
                                    <strong>Frecuencia cardiaca:</strong>
                                    <input type="text" class="form-control" name="fcardg" id="fcardg" placeholder="PULSO" pattern="\d{1,3}" title="Ingrese frecuencia cardíaca entre 0 y 300">
                                    <div class="invalid-feedback">La frecuencia cardíaca debe estar entre 0 y 300 por minuto.</div>
                                </div>
                                <div class="col-sm-3"><br>
                                    <strong>Frecuencia respiratoria:</strong>
                                    <input type="text" class="form-control" name="frespg" id="frespg" placeholder="FREC. RESP." pattern="\d{1,3}" title="Ingrese frecuencia respiratoria entre 0 y 100">
                                    <div class="invalid-feedback">La frecuencia respiratoria debe estar entre 0 y 100 rpm.</div>
                                </div>
                                <div class="col-sm-3"><br>
                                    <strong>Saturación oxígeno:</strong>
                                    <input type="text" class="form-control" name="satg" id="satg" placeholder="SAT. O2" pattern="\d{1,3}" title="Ingrese saturación de oxígeno entre 0 y 100">
                                    <div class="invalid-feedback">La SpO2 debe estar entre 0 y 100 %.</div>
                                </div>
                                <div class="col-sm-2"><br>
                                    <strong>Temperatura:</strong>
                                    <input type="text" class="form-control" name="tempg" id="tempg" placeholder="TEMP." pattern="\d{1,2}(\.\d)?$" title="Ingrese temperatura entre 0 y 45 °C (ej. 36.5)">
                                    <div class="invalid-feedback">La temperatura debe estar entre 0 y 45 °C.</div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <center>
                            <?php
                            $id_atencion = $_SESSION['hospital'];
                            $resultado = $conexion->query("SELECT *, id_registro_anestesico FROM registro_anestesico WHERE id_atencion=$id_atencion ORDER BY fecha_registro ASC") or die($conexion->error);
                            ?>
                            <div class="table-responsive"><br>
                                <table class="table table-bordered table-striped vital-signs-table" id="mytable">
                                    <thead class="thead bg-navy">
                                        <tr>
                                            <th scope="col">No.</th>
                                            <th scope="col">Fecha de nota</th>
                                            <th scope="col">Presión arterial</th>
                                            <th scope="col">Frecuencia cardiaca</th>
                                            <th scope="col">Frecuencia respiratoria</th>
                                            <th scope="col">Temperatura</th>
                                            <th scope="col">Sat. Oxígeno</th>
                                            <th scope="col">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; while ($f = mysqli_fetch_array($resultado)) { ?>
                                        <tr>
                                            <td><strong><?php echo $no; ?></strong></td>
                                            <td><strong><?php $date = date_create($f['fecha_registro']); echo date_format($date, "d/m/Y H:i:s"); ?></strong></td>
                                            <td><strong><?php echo $f['ta']; ?></strong></td>
                                            <td><strong><?php echo $f['fc']; ?></strong></td>
                                            <td><strong><?php echo $f['fr']; ?></strong></td>
                                            <td><strong><?php echo $f['temp']; ?> °C</strong></td>
                                            <td><strong><?php echo $f['spo2']; ?> %</strong></td>
                                            <td>
                                                <a href="../notas_medicas/ver_grafica.php?id_registro_anestesico=<?php echo htmlspecialchars($f['id_registro_anestesico']); ?>" role="button" class="btn btn-info btn-sm">Ver Gráfica</a>
                                            </td>
                                        </tr>
                                        <?php $no++; } ?>
                                    </tbody>
                                </table>
                            </div>
                        </center>
                    </div>
                </div>

                <!-- Detalles de Anestesia -->
                <div class="tab-pane fade" id="anesthesiaDetails" role="tabpanel" aria-labelledby="anesthesiaDetails-tab">
                    <div class="form-section">
                        <div class="form-group">
                            <label for="revision_equipo">Revisión del Equipo Anestésico:</label>
                            <select class="form-control" name="revision_equipo" id="revision_equipo">
                                <option value="">Seleccione</option>
                                <option value="OK">OK</option>
                                <option value="No OK">No OK</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="o2_hora">O2 (Hora):</label>
                            <input type="time" class="form-control" name="o2_hora" id="o2_hora" placeholder="Ejemplo: 08:00">
                        </div>
                        <div class="form-group">
                            <label for="agente_inhalado">Agente Inhalado:</label>
                            <input type="text" class="form-control" name="agente_inhalado" id="agente_inhalado" placeholder="Ejemplo: Sevoflurano">
                        </div>
                        <div class="form-group">
                            <label>Fármacos y Dosis Total:</label>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Fármaco</th>
                                        <th>Dosis Total</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="farmacosTable">
                                    <tr>
                                        <td><input type="text" class="form-control" name="farmacos[]" placeholder="Ejemplo: Lidocaína"></td>
                                        <td><input type="text" class="form-control" name="dosis_total[]" placeholder="Ejemplo: 2 mL"></td>
                                        <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3">
                                            <button type="button" class="btn btn-primary btn-sm" id="addFarmaco">Agregar Fármaco</button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="form-group">
                            <label>Monitoreo Continuo:</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="ecg_continua" id="ecg_continua" value="1">
                                <label class="form-check-label" for="ecg_continua">ECG Continua</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="pulsoximetria" id="pulsoximetria" value="1">
                                <label class="form-check-label" for="pulsoximetria">Pulsoximetría</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="capnografia" id="capnografia" value="1">
                                <label class="form-check-label" for="capnografia">Capnografía</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Intubación y Ventilación -->
                <div class="tab-pane fade" id="intubationVentilation" role="tabpanel" aria-labelledby="intubationVentilation-tab">
                    <div class="form-section">
                        <div class="form-group">
                            <label for="intubacion">Intubación:</label>
                            <input type="text" class="form-control" name="intubacion" id="intubacion" placeholder="Ejemplo: No requerida">
                        </div>
                        <div class="form-group">
                            <label for="incidentes">Incidentes:</label>
                            <div class="botones">
                                <button type="button" class="btn btn-danger btn-sm voice-btn" data-target="incidentes"><i class="fas fa-microphone"></i></button>
                                <button type="button" class="btn btn-primary btn-sm mute-btn" data-target="incidentes"><i class="fas fa-volume-mute"></i></button>
                                <button type="button" class="btn btn-success btn-sm play-btn" data-target="incidentes"><i class="fas fa-play"></i></button>
                            </div>
                            <textarea class="form-control" name="incidentes" id="incidentes" rows="4" placeholder="Ejemplo: Ningún incidente reportado"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="canula">Cánula:</label>
                            <input type="text" class="form-control" name="canula" id="canula" placeholder="Ejemplo: Cánula nasal 2 L/min">
                        </div>
                        <div class="form-group">
                            <label for="dificultad_tecnica">Dificultad Técnica:</label>
                            <select class="form-control" name="dificultad_tecnica" id="dificultad_tecnica">
                                <option value="">Seleccione</option>
                                <option value="No">No</option>
                                <option value="Sí">Sí</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ventilacion">Ventilación:</label>
                            <input type="text" class="form-control" name="ventilacion" id="ventilacion" placeholder="Ejemplo: Espontánea">
                        </div>
                    </div>
                </div>

                <!-- Líquidos y Balance -->
                <div class="tab-pane fade" id="fluidsBalance" role="tabpanel" aria-labelledby="fluidsBalance-tab">
                    <div class="form-section">
                        <h5>Ingresos de Líquidos</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="hartmann">Hartmann (mL):</label>
                                    <input type="number" class="form-control" name="hartmann" id="hartmann" placeholder="Ejemplo: 500" step="1" min="0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="glucosa">Glucosa (mL):</label>
                                    <input type="number" class="form-control" name="glucosa" id="glucosa" placeholder="Ejemplo: 250" step="1" min="0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nacl">NaCl (mL):</label>
                                    <input type="number" class="form-control" name="nacl" id="nacl" placeholder="Ejemplo: 1000" step="1" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="total_ingresos">Total Ingresos (mL):</label>
                            <input type="number" class="form-control" name="total_ingresos" id="total_ingresos" placeholder="Ejemplo: 1750" step="1" min="0" readonly>
                        </div>
                        <h5>Egresos</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="diuresis">Diuresis (mL):</label>
                                    <input type="number" class="form-control" name="diuresis" id="diuresis" placeholder="Ejemplo: 300" step="1" min="0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sangrado">Sangrado (mL):</label>
                                    <input type="number" class="form-control" name="sangrado" id="sangrado" placeholder="Ejemplo: 50" step="1" min="0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="perdidas_insensibles">Pérdidas Insensibles (mL):</label>
                                    <input type="number" class="form-control" name="perdidas_insensibles" id="perdidas_insensibles" placeholder="Ejemplo: 100" step="1" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="total_egresos">Total Egresos (mL):</label>
                            <input type="number" class="form-control" name="total_egresos" id="total_egresos" placeholder="Ejemplo: 450" step="1" min="0" readonly>
                        </div>
                        <div class="form-group">
                            <label for="balance">Balance (mL):</label>
                            <input type="number" class="form-control" name="balance" id="balance" placeholder="Ejemplo: 1300" step="1" readonly>
                        </div>
                    </div>
                </div>

                <!-- Puntuación Aldrete -->
                <div class="tab-pane fade" id="aldreteScore" role="tabpanel" aria-labelledby="aldreteScore-tab">
                    <div class="form-section">
                        <h5>Puntuación Aldrete al Salir del Quirófano</h5>
                        <table class="table table-bordered aldrete-table">
                            <thead>
                                <tr>
                                    <th>Criterio</th>
                                    <th>0</th>
                                    <th>1</th>
                                    <th>2</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Actividad</td>
                                    <td><input type="radio" name="aldrete_actividad" value="0"></td>
                                    <td><input type="radio" name="aldrete_actividad" value="1"></td>
                                    <td><input type="radio" name="aldrete_actividad" value="2"></td>
                                </tr>
                                <tr>
                                    <td>Respiración</td>
                                    <td><input type="radio" name="aldrete_respiracion" value="0"></td>
                                    <td><input type="radio" name="aldrete_respiracion" value="1"></td>
                                    <td><input type="radio" name="aldrete_respiracion" value="2"></td>
                                </tr>
                                <tr>
                                    <td>Circulación</td>
                                    <td><input type="radio" name="aldrete_circulacion" value="0"></td>
                                    <td><input type="radio" name="aldrete_circulacion" value="1"></td>
                                    <td><input type="radio" name="aldrete_circulacion" value="2"></td>
                                </tr>
                                <tr>
                                    <td>Conciencia</td>
                                    <td><input type="radio" name="aldrete_conciencia" value="0"></td>
                                    <td><input type="radio" name="aldrete_conciencia" value="1"></td>
                                    <td><input type="radio" name="aldrete_conciencia" value="2"></td>
                                </tr>
                                <tr>
                                    <td>Saturación</td>
                                    <td><input type="radio" name="aldrete_saturacion" value="0"></td>
                                    <td><input type="radio" name="aldrete_saturacion" value="1"></td>
                                    <td><input type="radio" name="aldrete_saturacion" value="2"></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <label for="aldrete_total">Total:</label>
                            <input type="number" class="form-control" name="aldrete_total" id="aldrete_total" placeholder="Ejemplo: 8" step="1" min="0" max="10" readonly>
                        </div>
                    </div>
                </div>

                <!-- Anestesia Regional -->
                <div class="tab-pane fade" id="regionalAnesthesia" role="tabpanel" aria-labelledby="regionalAnesthesia-tab">
                    <div class="form-section">
                        <div class="form-group">
                            <label for="anestesia_regional_tipo">Tipo de Anestesia Regional:</label>
                            <select class="form-control" name="anestesia_regional_tipo" id="anestesia_regional_tipo">
                                <option value="">Ninguna</option>
                                <option value="Peribulbar">Peribulbar</option>
                                <option value="Retrobulbar">Retrobulbar</option>
                                <option value="Subtenoniana">Subtenoniana</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="aguja">Aguja:</label>
                            <input type="text" class="form-control" name="aguja" id="aguja" placeholder="Ejemplo: 25G">
                        </div>
                        <div class="form-group">
                            <label for="nivel_puncion">Nivel de Punción:</label>
                            <input type="text" class="form-control" name="nivel_puncion" id="nivel_puncion" placeholder="Ejemplo: Inferotemporal">
                        </div>
                        <div class="form-group">
                            <label for="cateter">Catéter:</label>
                            <select class="form-control" name="cateter" id="cateter">
                                <option value="">Seleccione</option>
                                <option value="No">No</option>
                                <option value="Sí">Sí</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="agentes_administrados">Agentes Administrados:</label>
                            <div class="botones">
                                <button type="button" class="btn btn-danger btn-sm voice-btn" data-target="agentes_administrados"><i class="fas fa-microphone"></i></button>
                                <button type="button" class="btn btn-primary btn-sm mute-btn" data-target="agentes_administrados"><i class="fas fa-volume-mute"></i></button>
                                <button type="button" class="btn btn-success btn-sm play-btn" data-target="agentes_administrados"><i class="fas fa-play"></i></button>
                            </div>
                            <textarea class="form-control" name="agentes_administrados" id="agentes_administrados" rows="4" placeholder="Ejemplo: Lidocaína 2% 2 mL, Bupivacaína 0.5% 2 mL"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Tiempos -->
                <div class="tab-pane fade" id="timings" role="tabpanel" aria-labelledby="timings-tab">
                    <div class="form-section">
                        <h5>Claves de Tiempo</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="llega_quirofano">Llega a Quirófano:</label>
                                    <input type="datetime-local" class="form-control" name="llega_quirofano" id="llega_quirofano">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inicia_anestesia">Inicia Anestesia:</label>
                                    <input type="datetime-local" class="form-control" name="inicia_anestesia" id="inicia_anestesia">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inicia_cirugia">Inicia Cirugía:</label>
                                    <input type="datetime-local" class="form-control" name="inicia_cirugia" id="inicia_cirugia">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="termina_cirugia">Termina Cirugía:</label>
                                    <input type="datetime-local" class="form-control" name="termina_cirugia" id="termina_cirugia">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="termina_anestesia">Termina Anestesia:</label>
                                    <input type="datetime-local" class="form-control" name="termina_anestesia" id="termina_anestesia">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="pasa_recuperacion">Pasa a Recuperación:</label>
                                    <input type="datetime-local" class="form-control" name="pasa_recuperacion" id="pasa_recuperacion">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tiempo_anestesico">Tiempo Anestésico (min):</label>
                            <input type="number" class="form-control" name="tiempo_anestesico" id="tiempo_anestesico" placeholder="Ejemplo: 45" step="1" min="0" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary" name="guardar">Firmar</button>
                <button type="button" class="btn btn-danger" onclick="history.back()">Cancelar</button>
            </div>
        </form>
    </div>
    </div>

    <footer class="main-footer">
        <?php include("../../template/footer.php"); ?>
    </footer>

    <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
    <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
    <script>
    $(document).ready(function() {
        // Initialize Select2 for dropdowns
        $('.select2').select2({
            placeholder: "Seleccione",
            allowClear: true
        });

        $('.select2-assistants').select2({
            placeholder: "Buscar y seleccionar ayudantes",
            allowClear: true,
            minimumInputLength: 0
        });

        // Update selected assistants display
        $('#ayudantes').on('select2:select', function(e) {
            const data = e.params.data;
            const container = $('#selectedAssistants');
            const tag = `
                <span class="selected-assistant" data-id="${data.id}">
                    ${data.text}
                    <span class="remove-assistant" data-id="${data.id}" title="Eliminar">×</span>
                </span>`;
            container.append(tag);
        });

        $('#ayudantes').on('select2:unselect', function(e) {
            const id = e.params.data.id;
            $(`#selectedAssistants .selected-assistant[data-id="${id}"]`).remove();
        });

        // Remove assistant on click
        $(document).on('click', '.remove-assistant', function() {
            const id = $(this).data('id');
            $('#ayudantes').val($('#ayudantes').val().filter(val => val !== id)).trigger('change');
        });
    });

    // Initialize enviando flag
    let enviando = false;

    // Prevent double submission
    function checkSubmit() {
        if (enviando) {
            return false;
        }
        enviando = true;
        return true;
    }

    // Reset enviando on page load
    window.addEventListener('load', function() {
        enviando = false;
    });

    // Voice recognition setup
    const textareas = [
        'diagnostico_preoperatorio', 'cirugia_programada', 'diagnostico_postoperatorio', 'cirugia_realizada',
        'incidentes', 'agentes_administrados'
    ];

    textareas.forEach(id => {
        const textarea = document.getElementById(id);
        const voiceBtn = document.querySelector(`.voice-btn[data-target="${id}"]`);
        const muteBtn = document.querySelector(`.mute-btn[data-target="${id}"]`);
        const playBtn = document.querySelector(`.play-btn[data-target="${id}"]`);
        let recognition = null;
        let isRecording = false;

        if (textarea && ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window)) {
            recognition = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
            recognition.lang = 'es-ES';
            recognition.continuous = true;
            recognition.interimResults = false;

            recognition.onresult = (event) => {
                const results = event.results;
                const frase = results[results.length - 1][0].transcript;
                textarea.value += frase + ' ';
            };

            recognition.onend = () => {
                isRecording = false;
                voiceBtn.innerHTML = '<i class="fas fa-microphone"></i>';
                voiceBtn.disabled = false;
            };

            voiceBtn.addEventListener('click', () => {
                if (!isRecording) {
                    recognition.start();
                    isRecording = true;
                    voiceBtn.innerHTML = '<i class="fas fa-microphone-alt"></i>';
                } else {
                    recognition.stop();
                    isRecording = false;
                    voiceBtn.innerHTML = '<i class="fas fa-microphone"></i>';
                }
            });
        } else if (voiceBtn) {
            voiceBtn.disabled = true;
            voiceBtn.title = "Reconocimiento de voz no soportado en este navegador";
            voiceBtn.classList.add('btn-secondary');
        }

        if (playBtn && textarea) {
            playBtn.addEventListener('click', () => {
                if (textarea.value.trim() !== '') {
                    const speech = new SpeechSynthesisUtterance(textarea.value);
                    speech.lang = 'es-ES';
                    speech.volume = 1;
                    speech.rate = 1;
                    speech.pitch = 1;
                    window.speechSynthesis.speak(speech);
                }
            });
        }

        if (muteBtn) {
            muteBtn.addEventListener('click', () => {
                window.speechSynthesis.cancel();
            });
        }
    });

    // Dynamic farmaco table
    document.getElementById('addFarmaco').addEventListener('click', function() {
        const tableBody = document.getElementById('farmacosTable');
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><input type="text" class="form-control" name="farmacos[]" placeholder="Ejemplo: Lidocaína"></td>
            <td><input type="text" class="form-control" name="dosis_total[]" placeholder="Ejemplo: 2 mL"></td>
            <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button></td>
        `;
        tableBody.appendChild(row);
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
        }
    });

    // Calculate fluids balance
    function updateFluidsBalance() {
        const hartmann = parseFloat(document.getElementById('hartmann').value) || 0;
        const glucosa = parseFloat(document.getElementById('glucosa').value) || 0;
        const nacl = parseFloat(document.getElementById('nacl').value) || 0;
        const diuresis = parseFloat(document.getElementById('diuresis').value) || 0;
        const sangrado = parseFloat(document.getElementById('sangrado').value) || 0;
        const perdidas_insensibles = parseFloat(document.getElementById('perdidas_insensibles').value) || 0;

        const total_ingresos = hartmann + glucosa + nacl;
        const total_egresos = diuresis + sangrado + perdidas_insensibles;
        const balance = total_ingresos - total_egresos;

        document.getElementById('total_ingresos').value = total_ingresos;
        document.getElementById('total_egresos').value = total_egresos;
        document.getElementById('balance').value = balance;
    }

    ['hartmann', 'glucosa', 'nacl', 'diuresis', 'sangrado', 'perdidas_insensibles'].forEach(id => {
        document.getElementById(id).addEventListener('input', updateFluidsBalance);
    });

    // Calculate Aldrete score
    function updateAldreteScore() {
        const actividad = parseInt(document.querySelector('input[name="aldrete_actividad"]:checked')?.value) || 0;
        const respiracion = parseInt(document.querySelector('input[name="aldrete_respiracion"]:checked')?.value) || 0;
        const circulacion = parseInt(document.querySelector('input[name="aldrete_circulacion"]:checked')?.value) || 0;
        const conciencia = parseInt(document.querySelector('input[name="aldrete_conciencia"]:checked')?.value) || 0;
        const saturacion = parseInt(document.querySelector('input[name="aldrete_saturacion"]:checked')?.value) || 0;

        const total = actividad + respiracion + circulacion + conciencia + saturacion;
        document.getElementById('aldrete_total').value = total;
    }

    document.querySelectorAll('input[name^="aldrete_"]').forEach(input => {
        input.addEventListener('change', updateAldreteScore);
    });

    // Calculate anesthetic time
    function updateAnestheticTime() {
        const inicia = document.getElementById('inicia_anestesia').value;
        const termina = document.getElementById('termina_anestesia').value;

        if (inicia && termina) {
            const start = new Date(inicia);
            const end = new Date(termina);
            const diff = (end - start) / (1000 * 60); // Difference in minutes
            document.getElementById('tiempo_anestesico').value = Math.round(diff);
        }
    }

    ['inicia_anestesia', 'termina_anestesia'].forEach(id => {
        document.getElementById(id).addEventListener('change', updateAnestheticTime);
    });

    // Client-side form validation
    document.getElementById('anestheticRecordForm').addEventListener('submit', function(event) {
        let isValid = true;
        const errors = [];

        // Check if vital signs are provided
        const vitalSignsProvided = $('#sistg').val() && $('#diastg').val() && $('#fcardg').val() && $('#frespg').val() && $('#satg').val() && $('#tempg').val();
        if (!vitalSignsProvided) {
            isValid = false;
            errors.push('Debe registrar un conjunto completo de signos vitales.');
            $('#sistg, #diastg, #fcardg, #frespg, #satg, #tempg').addClass('is-invalid');
        } else {
            $('#sistg, #diastg, #fcardg, #frespg, #satg, #tempg').removeClass('is-invalid');
        }

        // Validate required fields
        const requiredInputs = document.querySelectorAll('#anestesiologo_id, #tipo_anestesia, #cirujano_id');
        requiredInputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                input.classList.add('is-invalid');
                errors.push(`El campo ${input.getAttribute('name')} es obligatorio.`);
            } else {
                input.classList.remove('is-invalid');
            }
        });

        // Validate numeric inputs only if provided
        const numericInputs = [
            { id: 'sistg', min: 10, max: 300, label: 'Presión Sistólica' },
            { id: 'diastg', min: 10, max: 200, label: 'Presión Diastólica' },
            { id: 'fcardg', min: 0, max: 300, label: 'Frecuencia Cardíaca' },
            { id: 'frespg', min: 0, max: 100, label: 'Frecuencia Respiratoria' },
            { id: 'satg', min: 0, max: 100, label: 'SpO2' },
            { id: 'tempg', min: 0, max: 45, label: 'Temperatura' },
            { id: 'hartmann', min: 0, max: 10000, label: 'Hartmann' },
            { id: 'glucosa', min: 0, max: 10000, label: 'Glucosa' },
            { id: 'nacl', min: 0, max: 10000, label: 'NaCl' },
            { id: 'diuresis', min: 0, max: 10000, label: 'Diuresis' },
            { id: 'sangrado', min: 0, max: 10000, label: 'Sangrado' },
            { id: 'perdidas_insensibles', min: 0, max: 10000, label: 'Pérdidas Insensibles' },
            { id: 'aldrete_total', min: 0, max: 10, label: 'Puntuación Aldrete Total' }
        ];
        numericInputs.forEach(field => {
            const input = document.getElementById(field.id);
            if (input && input.value && (isNaN(input.value) || input.value < field.min || input.value > field.max)) {
                isValid = false;
                input.classList.add('is-invalid');
                errors.push(`${field.label} debe estar entre ${field.min} y ${field.max}.`);
            } else if (input) {
                input.classList.remove('is-invalid');
            }
        });

        // Validate tipo_anestesia
        const tipoAnestesia = document.getElementById('tipo_anestesia');
        const validAnestesia = ['General', 'Regional', 'Local', 'Sedación'];
        if (!validAnestesia.includes(tipoAnestesia.value)) {
            isValid = false;
            tipoAnestesia.classList.add('is-invalid');
            errors.push('Seleccione un tipo de anestesia válido: ' + validAnestesia.join(', '));
        } else {
            tipoAnestesia.classList.remove('is-invalid');
        }

        // Validate revision_equipo
        const revisionEquipo = document.getElementById('revision_equipo');
        if (revisionEquipo.value && !['OK', 'No OK'].includes(revisionEquipo.value)) {
            isValid = false;
            revisionEquipo.classList.add('is-invalid');
            errors.push('Seleccione un estado válido para la revisión del equipo: OK o No OK');
        } else {
            revisionEquipo.classList.remove('is-invalid');
        }

        // Validate dificultad_tecnica
        const dificultadTecnica = document.getElementById('dificultad_tecnica');
        if (dificultadTecnica.value && !['Sí', 'No'].includes(dificultadTecnica.value)) {
            isValid = false;
            dificultadTecnica.classList.add('is-invalid');
            errors.push('Seleccione un valor válido para dificultad técnica: Sí o No');
        } else {
            dificultadTecnica.classList.remove('is-invalid');
        }

        // Validate cateter
        const cateter = document.getElementById('cateter');
        if (cateter.value && !['Sí', 'No'].includes(cateter.value)) {
            isValid = false;
            cateter.classList.add('is-invalid');
            errors.push('Seleccione un valor válido para catéter: Sí o No');
        } else {
            cateter.classList.remove('is-invalid');
        }

        if (!isValid) {
            event.preventDefault();
            alert(errors.join('\n'));
            enviando = false;
        }
    });
    </script>
</body>
</html>