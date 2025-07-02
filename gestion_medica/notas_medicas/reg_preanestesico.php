<?php
session_start();
include "../../conexionbd.php";
include "../header_medico.php";

if (!isset($_SESSION['login']['id_usua']) || !isset($_SESSION['hospital'])) {
    header("Location: ../../index.php");
    exit;
}

$sql_anesthesiologists = "SELECT id_usua, CONCAT(nombre, ' ', papell, ' ', sapell) AS full_name 
                         FROM reg_usuarios 
                         WHERE u_activo = 'SI' AND cargp = 'Anestesiólogo' 
                         ORDER BY full_name";
$result_anesthesiologists = $conexion->query($sql_anesthesiologists);
$anesthesiologists = [];
if ($result_anesthesiologists) {
    while ($row = $result_anesthesiologists->fetch_assoc()) {
        $anesthesiologists[] = $row;
    }
}

// Fetch active surgeons from reg_usuarios
$sql_surgeons = "SELECT id_usua, CONCAT(nombre, ' ', papell, ' ', sapell) AS full_name 
                 FROM reg_usuarios 
                 WHERE u_activo = 'SI' AND cargp = 'Cirujano' 
                 ORDER BY full_name";
$result_surgeons = $conexion->query($sql_surgeons);
$surgeons = [];
if ($result_surgeons) {
    while ($row = $result_surgeons->fetch_assoc()) {
        $surgeons[] = $row;
    }
}

// Consolidate patient data query
if (isset($_SESSION['hospital'])) {
    $id_atencion = filter_var($_SESSION['hospital'], FILTER_VALIDATE_INT);
    if (!$id_atencion) {
        header("Location: ../../index.php");
        exit;
    }
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
    <title>Valoración Preanestésica - Instituto de Enfermedades Oculares</title>
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

    .accordion .card {
        border: none;
    }

    .accordion .card-header {
        background-color: #e9ecef;
        cursor: pointer;
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
        <br>

        <div class="thead"><strong>VALORACIÓN PREANESTÉSICA</strong></div>
        <form action="insertar_preanestesia.php" method="POST" onsubmit="return checkSubmit();" id="preanestesiaForm">
            <input type="hidden" name="Id_exp" value="<?php echo htmlspecialchars($id_exp); ?>">
            <input type="hidden" name="id_usua" value="<?php echo htmlspecialchars($_SESSION['login']['id_usua']); ?>">
            <input type="hidden" name="id_atencion" value="<?php echo htmlspecialchars($_SESSION['hospital']); ?>">
            <!-- Horizontal Tabs Navigation -->

            <ul class="nav nav-tabs" id="preanestesiaTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="generalInfo-tab" data-toggle="tab" href="#generalInfo" role="tab"
                        aria-controls="generalInfo" aria-selected="true">Información General</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="antecedentes-tab" data-toggle="tab" href="#antecedentes" role="tab"
                        aria-controls="antecedentes" aria-selected="false">Antecedentes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="exploracionFisica-tab" data-toggle="tab" href="#exploracionFisica"
                        role="tab" aria-controls="exploracionFisica" aria-selected="false">Exploración Física</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="laboratorio-tab" data-toggle="tab" href="#laboratorio" role="tab"
                        aria-controls="laboratorio" aria-selected="false">Laboratorio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="asaPlanIndicaciones-tab" data-toggle="tab" href="#asaPlanIndicaciones"
                        role="tab" aria-controls="asaPlanIndicaciones" aria-selected="false">Estado Físico ASA, Plan
                        Anestésico e Indicaciones</a>
                </li>
            </ul>


            <!-- Tab Content -->
            <div class="tab-content" id="preanestesiaTabContent">
                <!-- Información General -->
                <div class="tab-pane fade show active" id="generalInfo" role="tabpanel"
                    aria-labelledby="generalInfo-tab">
                    <div class="form-section">
                        <div class="form-group">
                            <label for="anestesiologo">Anestesiólogo:</label>
                            <select class="form-control" name="anestesiologo_id" id="anestesiologo">
                                <option value="">Seleccione un anestesiólogo</option>
                                <?php foreach ($anesthesiologists as $anestesiologo): ?>
                                <option value="<?php echo htmlspecialchars($anestesiologo['id_usua']); ?>">
                                    <?php echo htmlspecialchars($anestesiologo['full_name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Seleccione un anestesiólogo válido.</div>
                        </div>
                        <div class="form-group">
                            <label>Tipo de Cirugía:</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="urgencia" id="urgencia"
                                    value="Urgencia">
                                <label class="form-check-label" for="urgencia">Urgencia</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="urgencia" id="electiva"
                                    value="Electiva">
                                <label class="form-check-label" for="electiva">Electiva</label>
                            </div>
                            <div class="invalid-feedback">Seleccione el tipo de cirugía.</div>
                        </div>
                        <div class="form-group">
                            <label for="cirujano">Cirujano:</label>
                            <select class="form-control" name="cirujano_id" id="cirujano">
                                <option value="">Seleccione un cirujano</option>
                                <?php foreach ($surgeons as $cirujano): ?>
                                <option value="<?php echo htmlspecialchars($cirujano['id_usua']); ?>">
                                    <?php echo htmlspecialchars($cirujano['full_name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Seleccione un cirujano válido.</div>
                        </div>
                        <div class="form-group">
                            <label for="diagnostico_preoperatorio">Diagnóstico Preoperatorio:</label>
                            <div class="botones">
                                <button type="button" class="btn btn-danger btn-sm voice-btn"
                                    data-target="diagnostico_preoperatorio"><i class="fas fa-microphone"></i></button>
                                <button type="button" class="btn btn-primary btn-sm mute-btn"
                                    data-target="diagnostico_preoperatorio"><i
                                        class="fas fa-microphone-slash"></i></button>
                                <button type="button" class="btn btn-success btn-sm play-btn"
                                    data-target="diagnostico_preoperatorio"><i class="fas fa-play"></i></button>
                            </div>
                            <textarea class="form-control" name="diagnostico_preoperatorio"
                                id="diagnostico_preoperatorio" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="cirugia_programada">Cirugía Programada:</label>
                            <div class="botones">
                                <button type="button" class="btn btn-danger btn-sm voice-btn"
                                    data-target="cirugia_programada"><i class="fas fa-microphone"></i></button>
                                <button type="button" class="btn btn-primary btn-sm mute-btn"
                                    data-target="cirugia_programada"><i class="fas fa-microphone-slash"></i></button>
                                <button type="button" class="btn btn-success btn-sm play-btn"
                                    data-target="cirugia_programada"><i class="fas fa-play"></i></button>
                            </div>
                            <textarea class="form-control" name="cirugia_programada" id="cirugia_programada"
                                rows="4"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Antecedentes -->
                <div class="tab-pane fade" id="antecedentes" role="tabpanel" aria-labelledby="antecedentes-tab">
                    <div class="form-section">
                        <div class="card-body">
                            <div class="row">
                                <?php
                                $antecedentes = [
                                    ['name' => 'tabaquismo', 'label' => 'Tabaquismo', 'detail' => 'Tiempo y tratamiento'],
                                    ['name' => 'asma', 'label' => 'Asma', 'detail' => 'Tiempo y tratamiento'],
                                    ['name' => 'alcoholismo', 'label' => 'Alcoholismo', 'detail' => 'Tiempo y tratamiento'],
                                    ['name' => 'alergias', 'label' => 'Alergias', 'detail' => 'Detalles'],
                                    ['name' => 'toxicomanias', 'label' => 'Toxicomanías', 'detail' => 'Detalles'],
                                    ['name' => 'diabetes', 'label' => 'Diabetes', 'detail' => 'Tiempo y tratamiento'],
                                    ['name' => 'hepatopatias', 'label' => 'Hepatopatías', 'detail' => 'Detalles'],
                                    ['name' => 'enf_tiroideas', 'label' => 'Enfermedades Tiroideas', 'detail' => 'Detalles'],
                                    ['name' => 'neumopatias', 'label' => 'Neumopatías', 'detail' => 'Detalles'],
                                    ['name' => 'hipertension', 'label' => 'Hipertensión', 'detail' => 'Tiempo y tratamiento'],
                                    ['name' => 'nefropatias', 'label' => 'Nefropatías', 'detail' => 'Detalles'],
                                    ['name' => 'cancer', 'label' => 'Cáncer', 'detail' => 'Detalles'],
                                    ['name' => 'transfusiones', 'label' => 'Transfusiones', 'detail' => 'Detalles'],
                                    ['name' => 'artritis', 'label' => 'Artritis', 'detail' => 'Detalles'],
                                    ['name' => 'cardiopatias', 'label' => 'Cardiopatías', 'detail' => 'Detalles'],
                                ];
                                $half = ceil(count($antecedentes) / 2);
                                $left_antecedentes = array_slice($antecedentes, 0, $half);
                                $right_antecedentes = array_slice($antecedentes, $half);
                                ?>
                                <div class="col-md-6">
                                    <?php foreach ($left_antecedentes as $antecedente): ?>
                                    <div class="form-group">
                                        <label><?php echo htmlspecialchars($antecedente['label']); ?>:</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio"
                                                name="<?php echo $antecedente['name']; ?>"
                                                id="<?php echo $antecedente['name']; ?>_no" value="No" checked>
                                            <label class="form-check-label"
                                                for="<?php echo $antecedente['name']; ?>_no">No</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio"
                                                name="<?php echo $antecedente['name']; ?>"
                                                id="<?php echo $antecedente['name']; ?>_si" value="Sí">
                                            <label class="form-check-label"
                                                for="<?php echo $antecedente['name']; ?>_si">Sí</label>
                                        </div>
                                        <input type="text" class="form-control mt-2 hidden"
                                            name="<?php echo $antecedente['name']; ?>_detalle"
                                            id="<?php echo $antecedente['name']; ?>_detalle"
                                            placeholder="<?php echo htmlspecialchars($antecedente['detail']); ?>">
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="col-md-6">
                                    <?php foreach ($right_antecedentes as $antecedente): ?>
                                    <div class="form-group">
                                        <label><?php echo htmlspecialchars($antecedente['label']); ?>:</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio"
                                                name="<?php echo $antecedente['name']; ?>"
                                                id="<?php echo $antecedente['name']; ?>_no" value="No" checked>
                                            <label class="form-check-label"
                                                for="<?php echo $antecedente['name']; ?>_no">No</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio"
                                                name="<?php echo $antecedente['name']; ?>"
                                                id="<?php echo $antecedente['name']; ?>_si" value="Sí">
                                            <label class="form-check-label"
                                                for="<?php echo $antecedente['name']; ?>_si">Sí</label>
                                        </div>
                                        <input type="text" class="form-control mt-2 hidden"
                                            name="<?php echo $antecedente['name']; ?>_detalle"
                                            id="<?php echo $antecedente['name']; ?>_detalle"
                                            placeholder="<?php echo htmlspecialchars($antecedente['detail']); ?>">
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label for="medicamentos_actuales">Medicamentos Actuales:</label>
                                    <div class="botones">
                                        <button type="button" class="btn btn-danger btn-sm voice-btn"
                                            data-target="medicamentos_actuales"><i
                                                class="fas fa-microphone"></i></button>
                                        <button type="button" class="btn btn-primary btn-sm mute-btn"
                                            data-target="medicamentos_actuales"><i
                                                class="fas fa-microphone-slash"></i></button>
                                        <button type="button" class="btn btn-success btn-sm play-btn"
                                            data-target="medicamentos_actuales"><i class="fas fa-play"></i></button>
                                    </div>
                                    <textarea class="form-control" name="medicamentos_actuales"
                                        id="medicamentos_actuales" rows="4"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="anestesias_previas">Anestesias Previas:</label>
                                    <div class="botones">
                                        <button type="button" class="btn btn-danger btn-sm voice-btn"
                                            data-target="anestesias_previas"><i class="fas fa-microphone"></i></button>
                                        <button type="button" class="btn btn-primary btn-sm mute-btn"
                                            data-target="anestesias_previas"><i
                                                class="fas fa-microphone-slash"></i></button>
                                        <button type="button" class="btn btn-success btn-sm play-btn"
                                            data-target="anestesias_previas"><i class="fas fa-play"></i></button>
                                    </div>
                                    <textarea class="form-control" name="anestesias_previas" id="anestesias_previas"
                                        rows="4"></textarea>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <label for="otros_antecedentes">Otros:</label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm voice-btn"
                                        data-target="otros_antecedentes"><i class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm mute-btn"
                                        data-target="otros_antecedentes"><i
                                            class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm play-btn"
                                        data-target="otros_antecedentes"><i class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" name="otros_antecedentes" id="otros_antecedentes"
                                    rows="4"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="padecimiento_actual">Padecimiento Actual:</label>
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm voice-btn"
                                        data-target="padecimiento_actual"><i class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm mute-btn"
                                        data-target="padecimiento_actual"><i
                                            class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm play-btn"
                                        data-target="padecimiento_actual"><i class="fas fa-play"></i></button>
                                </div>
                                <textarea class="form-control" name="padecimiento_actual" id="padecimiento_actual"
                                    rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Exploración Física -->
                <div class="tab-pane fade" id="exploracionFisica" role="tabpanel"
                    aria-labelledby="exploracionFisica-tab">
                    <div class="form-section">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="peso">Peso (kg):</label>
                                    <input type="number" class="form-control" name="peso" id="peso" step="0.1" min="0"
                                        max="500" aria-describedby="pesoFeedback">
                                    <div class="invalid-feedback" id="pesoFeedback">El peso debe estar entre 0 y 500 kg.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="talla">Talla (m):</label>
                                    <input type="number" class="form-control" name="talla" id="talla" step="0.01"
                                        min="0" max="3" aria-describedby="tallaFeedback">
                                    <div class="invalid-feedback" id="tallaFeedback">La talla debe estar entre 0 y 3
                                        metros.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ta_sistolica">T.A. Sistólica (mmHg):</label>
                                    <input type="number" class="form-control" name="ta_sistolica" id="ta_sistolica"
                                        step="1" min="0" max="300" aria-describedby="taSistolicaFeedback">
                                    <div class="invalid-feedback" id="taSistolicaFeedback">La presión sistólica debe
                                        estar entre
                                        0 y 300 mmHg.</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ta_diastolica">T.A. Diastólica (mmHg):</label>
                                    <input type="number" class="form-control" name="ta_diastolica" id="ta_diastolica"
                                        step="1" min="0" max="200" aria-describedby="taDiastolicaFeedback">
                                    <div class="invalid-feedback" id="taDiastolicaFeedback">La presión diastólica
                                        debe estar
                                        entre 0 y 200 mmHg.</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fc">F.C. (x min):</label>
                                    <input type="number" class="form-control" name="fc" id="fc" step="1" min="0"
                                        max="300" aria-describedby="fcFeedback">
                                    <div class="invalid-feedback" id="fcFeedback">La frecuencia cardíaca debe estar
                                        entre 0 y
                                        300 por minuto.</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fr">F.R. (rpm):</label>
                                    <input type="number" class="form-control" name="fr" id="fr" step="1" min="0"
                                        max="100" aria-describedby="frFeedback">
                                    <div class="invalid-feedback" id="frFeedback">La frecuencia respiratoria debe
                                        estar entre 0
                                        y 100 rpm.</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="temperatura">Temperatura (°C):</label>
                                    <input type="number" class="form-control" name="temperatura" id="temperatura"
                                        step="0.1" min="0" max="45" aria-describedby="temperaturaFeedback">
                                    <div class="invalid-feedback" id="temperaturaFeedback">La temperatura debe estar
                                        entre 0 y
                                        45 °C.</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Estado de Conciencia:</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="edo_conciencia" id="consciente"
                                    value="Consciente">
                                <label class="form-check-label" for="consciente">Consciente</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="edo_conciencia" id="inconsciente"
                                    value="Inconsciente">
                                <label class="form-check-label" for="inconsciente">Inconsciente</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="edo_conciencia" id="desorientado"
                                    value="Desorientado">
                                <label class="form-check-label" for="desorientado">Desorientado</label>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                                $exploracion_fields = [
                                    ['id' => 'cabeza_cuello', 'label' => 'Cabeza y Cuello'],
                                    ['id' => 'via_aerea', 'label' => 'Vía Aérea'],
                                    ['id' => 'cardiopulmonar', 'label' => 'Cardiopulmonar'],
                                    ['id' => 'abdomen', 'label' => 'Abdomen'],
                                    ['id' => 'columna', 'label' => 'Columna'],
                                    ['id' => 'extremidades', 'label' => 'Extremidades'],
                                    ['id' => 'otros_exploracion', 'label' => 'Otros']
                                ];
                                $half_exp = ceil(count($exploracion_fields) / 2);
                                $left_exp = array_slice($exploracion_fields, 0, $half_exp);
                                $right_exp = array_slice($exploracion_fields, $half_exp);
                                ?>
                            <div class="col-md-6">
                                <?php foreach ($left_exp as $field): ?>
                                <div class="form-group">
                                    <label
                                        for="<?php echo $field['id']; ?>"><?php echo htmlspecialchars($field['label']); ?>:</label>
                                    <div class="botones">
                                        <button type="button" class="btn btn-danger btn-sm voice-btn"
                                            data-target="<?php echo $field['id']; ?>"><i
                                                class="fas fa-microphone"></i></button>
                                        <button type="button" class="btn btn-primary btn-sm mute-btn"
                                            data-target="<?php echo $field['id']; ?>"><i
                                                class="fas fa-microphone-slash"></i></button>
                                        <button type="button" class="btn btn-success btn-sm play-btn"
                                            data-target="<?php echo $field['id']; ?>"><i
                                                class="fas fa-play"></i></button>
                                    </div>
                                    <textarea class="form-control" name="<?php echo $field['id']; ?>"
                                        id="<?php echo $field['id']; ?>" rows="3"></textarea>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="col-md-6">
                                <?php foreach ($right_exp as $field): ?>
                                <div class="form-group">
                                    <label
                                        for="<?php echo $field['id']; ?>"><?php echo htmlspecialchars($field['label']); ?>:</label>
                                    <div class="botones">
                                        <button type="button" class="btn btn-danger btn-sm voice-btn"
                                            data-target="<?php echo $field['id']; ?>"><i
                                                class="fas fa-microphone"></i></button>
                                        <button type="button" class="btn btn-primary btn-sm mute-btn"
                                            data-target="<?php echo $field['id']; ?>"><i
                                                class="fas fa-microphone-slash"></i></button>
                                        <button type="button" class="btn btn-success btn-sm play-btn"
                                            data-target="<?php echo $field['id']; ?>"><i
                                                class="fas fa-play"></i></button>
                                    </div>
                                    <textarea class="form-control" name="<?php echo $field['id']; ?>"
                                        id="<?php echo $field['id']; ?>" rows="3"></textarea>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Laboratorio -->
                <div class="tab-pane fade" id="laboratorio" role="tabpanel" aria-labelledby="laboratorio-tab">
                    <div class="form-section">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="hb">Hemoglobina:</label>
                                <input type="number" class="form-control" name="hb" id="hb" step="0.1" min="0" max="50"
                                    aria-describedby="hbFeedback">
                                <div class="invalid-feedback" id="hbFeedback">Valor entre 0 y 50 g/dL.</div>
                            </div>
                            <div class="col-md-3">
                                <label for="hto">Hematocrito:</label>
                                <input type="number" class="form-control" name="hto" id="hto" step="0.1" min="0"
                                    max="100" aria-describedby="htoFeedback">
                                <div class="invalid-feedback" id="htoFeedback">Valor entre 0 y 100 %.</div>
                            </div>
                            <div class="col-md-3">
                                <label for="tp">T.P.:</label>
                                <input type="number" class="form-control" name="tp" id="tp" step="0.1" min="0" max="100"
                                    aria-describedby="tpFeedback">
                                <div class="invalid-feedback" id="tpFeedback">Valor entre 0 y 100 segundos.</div>
                            </div>
                            <div class="col-md-3">
                                <label for="tpt">T.P.T.:</label>
                                <input type="number" class="form-control" name="tpt" id="tpt" step="0.1" min="0"
                                    max="100" aria-describedby="tptFeedback">
                                <div class="invalid-feedback" id="tptFeedback">Valor entre 0 y 100 segundos.</div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="tipo_rh">Tipo y Rh:</label>
                                <select class="form-control" name="tipo_rh" id="tipo_rh"
                                    aria-describedby="tipoRhFeedback">
                                    <option value="">Seleccione</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                                <div class="invalid-feedback" id="tipoRhFeedback">Seleccione un tipo válido (A+, A-,
                                    B+, B-,
                                    AB+, AB-, O+, O-).</div>
                            </div>
                            <div class="col-md-3">
                                <label for="glucosa">Glucosa:</label>
                                <input type="number" class="form-control" name="glucosa" id="glucosa" step="0.1" min="0"
                                    max="1000" aria-describedby="glucosaFeedback">
                                <div class="invalid-feedback" id="glucosaFeedback">Valor entre 0 y 1000 mg/dL.</div>
                            </div>
                            <div class="col-md-3">
                                <label for="urea">Urea:</label>
                                <input type="number" class="form-control" name="urea" id="urea" step="0.1" min="0"
                                    max="1000" aria-describedby="ureaFeedback">
                                <div class="invalid-feedback" id="ureaFeedback">Valor entre 0 y 1000 mg/dL.</div>
                            </div>
                            <div class="col-md-3">
                                <label for="creatinina">Creatinina:</label>
                                <input type="number" class="form-control" name="creatinina" id="creatinina" step="0.1"
                                    min="0" max="100" aria-describedby="creatininaFeedback">
                                <div class="invalid-feedback" id="creatininaFeedback">Valor entre 0 y 100 mg/dL.
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="otros_laboratorio">Otros Laboratorio:</label>
                            <div class="botones">
                                <button type="button" class="btn btn-danger btn-sm voice-btn"
                                    data-target="otros_laboratorio"><i class="fas fa-microphone"></i></button>
                                <button type="button" class="btn btn-primary btn-sm mute-btn"
                                    data-target="otros_laboratorio"><i class="fas fa-microphone-slash"></i></button>
                                <button type="button" class="btn btn-success btn-sm play-btn"
                                    data-target="otros_laboratorio"><i class="fas fa-play"></i></button>
                            </div>
                            <textarea class="form-control" name="otros_laboratorio" id="otros_laboratorio"
                                rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="gabinete">Gabinete:</label>
                            <div class="botones">
                                <button type="button" class="btn btn-danger btn-sm voice-btn" data-target="gabinete"><i
                                        class="fas fa-microphone"></i></button>
                                <button type="button" class="btn btn-primary btn-sm mute-btn" data-target="gabinete"><i
                                        class="fas fa-microphone-slash"></i></button>
                                <button type="button" class="btn btn-success btn-sm play-btn" data-target="gabinete"><i
                                        class="fas fa-play"></i></button>
                            </div>
                            <textarea class="form-control" name="gabinete" id="gabinete" rows="4"></textarea>
                        </div>
                    </div>
                </div>


                <!-- Estado Físico ASA, Plan Anestésico, Indicaciones Preanestésicas -->
                <div class="tab-pane fade" id="asaPlanIndicaciones" role="tabpanel"
                    aria-labelledby="asaPlanIndicaciones-tab">
                    <div class="form-section">
                        <div class="form-group">
                            <label>Estado Físico ASA:</label><br>
                            <?php foreach (['I', 'II', 'III', 'IV', 'V'] as $asa): ?>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="asa" id="asa_<?php echo $asa; ?>"
                                    value="<?php echo $asa; ?>">
                                <label class="form-check-label"
                                    for="asa_<?php echo $asa; ?>"><?php echo $asa; ?></label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="form-group">
                            <label for="plan_anestesico">Plan Anestésico:</label>
                            <div class="botones">
                                <button type="button" class="btn btn-danger btn-sm voice-btn"
                                    data-target="plan_anestesico"><i class="fas fa-microphone"></i></button>
                                <button type="button" class="btn btn-primary btn-sm mute-btn"
                                    data-target="plan_anestesico"><i class="fas fa-microphone-slash"></i></button>
                                <button type="button" class="btn btn-success btn-sm play-btn"
                                    data-target="plan_anestesico"><i class="fas fa-play"></i></button>
                            </div>
                            <textarea class="form-control" name="plan_anestesico" id="plan_anestesico"
                                rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="indicaciones_preanestesicas">Indicaciones Preanestésicas:</label>
                            <div class="botones">
                                <button type="button" class="btn btn-danger btn-sm voice-btn"
                                    data-target="indicaciones_preanestesicas"><i class="fas fa-microphone"></i></button>
                                <button type="button" class="btn btn-primary btn-sm mute-btn"
                                    data-target="indicaciones_preanestesicas"><i
                                        class="fas fa-microphone-slash"></i></button>
                                <button type="button" class="btn btn-success btn-sm play-btn"
                                    data-target="indicaciones_preanestesicas"><i class="fas fa-play"></i></button>
                            </div>
                            <textarea class="form-control" name="indicaciones_preanestesicas"
                                id="indicaciones_preanestesicas" rows="4"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Firmar</button>
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
    // Initialize enviando flag
    let enviando = false;

    // Reset enviando on page load
    window.addEventListener('load', function() {
        enviando = false;
    });

    // Voice recognition setup
    const textareas = [
        'diagnostico_preoperatorio', 'cirugia_programada', 'medicamentos_actuales', 'anestesias_previas',
        'otros_antecedentes', 'padecimiento_actual', 'cabeza_cuello', 'via_aerea', 'cardiopulmonar',
        'abdomen', 'columna', 'extremidades', 'otros_exploracion', 'otros_laboratorio', 'gabinete',
        'plan_anestesico', 'indicaciones_preanestesicas'
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

    const antecedentes = <?php echo json_encode(array_column($antecedentes, 'name')); ?>;
    antecedentes.forEach(name => {
        const siRadio = document.getElementById(`${name}_si`);
        const noRadio = document.getElementById(`${name}_no`);
        const detailField = document.getElementById(`${name}_detalle`);

        function toggleDetailField() {
            if (siRadio.checked) {
                detailField.classList.remove('hidden');
            } else {
                detailField.classList.add('hidden');
                detailField.value = '';
            }
        }

        siRadio.addEventListener('change', toggleDetailField);
        noRadio.addEventListener('change', toggleDetailField);
        toggleDetailField();
    });

    document.getElementById('preanestesiaForm').addEventListener('submit', function(event) {
        let isValid = true;
        const errors = [];

        const numericInputs = [{
                id: 'peso',
                min: 0,
                max: 500,
                label: 'Peso'
            },
            {
                id: 'talla',
                min: 0,
                max: 3,
                label: 'Talla'
            },
            {
                id: 'ta_sistolica',
                min: 0,
                max: 300,
                label: 'T.A. Sistólica'
            },
            {
                id: 'ta_diastolica',
                min: 0,
                max: 200,
                label: 'T.A. Diastólica'
            },
            {
                id: 'fc',
                min: 0,
                max: 300,
                label: 'Frecuencia Cardíaca'
            },
            {
                id: 'fr',
                min: 0,
                max: 100,
                label: 'Frecuencia Respiratoria'
            },
            {
                id: 'temperatura',
                min: 0,
                max: 45,
                label: 'Temperatura'
            },
            {
                id: 'hb',
                min: 0,
                max: 50,
                label: 'Hemoglobina'
            },
            {
                id: 'hto',
                min: 0,
                max: 100,
                label: 'Hematocrito'
            },
            {
                id: 'tp',
                min: 0,
                max: 100,
                label: 'T.P.'
            },
            {
                id: 'tpt',
                min: 0,
                max: 100,
                label: 'T.P.T.'
            },
            {
                id: 'glucosa',
                min: 0,
                max: 1000,
                label: 'Glucosa'
            },
            {
                id: 'urea',
                min: 0,
                max: 1000,
                label: 'Urea'
            },
            {
                id: 'creatinina',
                min: 0,
                max: 100,
                label: 'Creatinina'
            }
        ];
        numericInputs.forEach(field => {
            const input = document.getElementById(field.id);
            if (input && input.value && (isNaN(input.value) || input.value < field.min || input.value >
                    field.max)) {
                isValid = false;
                input.classList.add('is-invalid');
                errors.push(`${field.label} debe estar entre ${field.min} y ${field.max}.`);
            } else if (input) {
                input.classList.remove('is-invalid');
            }
        });

        const tipoRh = document.getElementById('tipo_rh');
        if (tipoRh.value && !/^(A|B|AB|O)[+-]$/.test(tipoRh.value)) {
            isValid = false;
            tipoRh.classList.add('is-invalid');
            errors.push('El tipo y Rh debe ser A+, A-, B+, B-, AB+, AB-, O+, o O-.');
        } else {
            tipoRh.classList.remove('is-invalid');
        }

        if (!isValid) {
            event.preventDefault();
            alert(errors.join('\n'));
            enviando = false;
        }
    });

    function checkSubmit() {
        if (!enviando) {
            enviando = true;
            return true;
        } else {
            alert("El formulario ya se está enviando");
            return false;
        }
    }

    $(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });

    $(document).ready(function() {
        $('#anestesiologo').select2({
            placeholder: "Seleccione un anestesiólogo",
            allowClear: true
        });
        $('#cirujano').select2({
            placeholder: "Seleccione un cirujano",
            allowClear: true
        });
    });
    </script>
</body>

</html>