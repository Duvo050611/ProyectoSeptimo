<?php
session_start();
include "../../conexionbd.php"; // Assuming this file establishes $conexion
include "../header_medico.php"; // Assuming this file handles common header elements

// Redirect if user not logged in or hospital session not set
if (!isset($_SESSION['login']['id_usua']) || !isset($_SESSION['hospital'])) {
    header("Location: ../../index.php");
    exit;
}

$id_atencion = $_SESSION['hospital'];

// Ensure UTF-8 encoding for PHP output
/* header('Content-Type: text/html; charset=UTF-8'); */
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Post Anestésica - Oftalmología</title>
    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <link href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="js/select2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
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
        .form-section {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        .form-group label {
            font-weight: 600;
        }
        .aldrete-table th, .aldrete-table td {
            padding: 5px;
            border: 1px solid #dee2e6;
            text-align: center;
        }
        .vital-signs-table th, .vital-signs-table td {
            padding: 5px;
            border: 1px solid #dee2e6;
            text-align: center;
        }
        .form-group .invalid-feedback {
            display: none;
            color: #dc3545;
            margin-top: .25rem;
            font-size: 80%;
        }
        .form-control.is-invalid+.invalid-feedback,
        .form-check-input.is-invalid~.invalid-feedback {
            display: block;
        }
        .time-input {
            width: 100px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <?php if (isset($_SESSION['message']) && isset($_SESSION['message_type'])): ?>
        <div class="alert alert-<?php echo htmlspecialchars($_SESSION['message_type']); ?> alert-dismissible fade show" role="alert">
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

        <div class="thead"><strong>NOTA POST ANESTÉSICA - OFTALMOLOGÍA</strong></div>
        <br>

        <form action="insertar_reg_post_anestesia.php" method="POST" id="postAnestheticOcularForm" accept-charset="UTF-8">
            <input type="hidden" name="Id_exp" value="<?php echo htmlspecialchars($id_exp); ?>">
            <input type="hidden" name="id_usua" value="<?php echo htmlspecialchars($_SESSION['login']['id_usua']); ?>">
            <input type="hidden" name="id_atencion" value="<?php echo htmlspecialchars($_SESSION['hospital']); ?>">

            <!-- Operated Eye -->
            <div class="form-section">
                <div class="form-group">
                    <label for="ojo_operado">Ojo Operado:</label>
                    <select class="form-control" name="ojo_operado" id="ojo_operado" required>
                        <option value="">Seleccione</option>
                        <option value="OD">Ojo Derecho (OD)</option>
                        <option value="OS">Ojo Izquierdo (OI)</option>
                        <option value="OU">Ambos Ojos (OU)</option>
                    </select>
                    <div class="invalid-feedback">Seleccione el ojo operado.</div>
                </div>
            </div>

            <!-- Anesthetic Technique -->
            <div class="form-section">
                <div class="form-group">
                    <label for="tecnica_anestesica">Técnica Anestésica y Fármacos Empleados:</label>
                    <textarea class="form-control" name="tecnica_anestesica" id="tecnica_anestesica" rows="5" required
                        placeholder="Ejemplo: Anestesia peribulbar con lidocaína 2% 2 mL y bupivacaína 0.5% 2 mL"></textarea>
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>
            </div>

            <!-- Blood/Fluids Administered -->
            <div class="form-section">
                <div class="form-group">
                    <label for="sangre_liquidos">Sangre y/o Líquidos Administrados:</label>
                    <textarea class="form-control" name="sangre_liquidos" id="sangre_liquidos" rows="3"
                        placeholder="Ejemplo: Solución salina 250 mL durante el procedimiento"></textarea>
                </div>
            </div>

            <!-- Incidents -->
            <div class="form-section">
                <div class="form-group">
                    <label>Incidentes y/o Accidentes:</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="incidentes" id="incidentes_si" value="Sí" required>
                        <label class="form-check-label" for="incidentes_si">Sí</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="incidentes" id="incidentes_no" value="No" checked>
                        <label class="form-check-label" for="incidentes_no">No</label>
                    </div>
                    <div class="invalid-feedback">Seleccione una opción.</div>
                    <textarea class="form-control mt-2" name="detalle_incidentes" id="detalle_incidentes" rows="3"
                        placeholder="Ejemplo: Leve edema corneal observado, manejado con esteroides tópicos" style="display: none;"></textarea>
                </div>
            </div>

            <!-- Management Plan -->
            <div class="form-section">
                <div class="form-group">
                    <label for="plan_manejo">Plan de Manejo y Tratamiento Inmediato:</label>
                    <textarea class="form-control" name="plan_manejo" id="plan_manejo" rows="5" required
                        placeholder="Ejemplo: Monitoreo de presión intraocular, aplicación de colirios antiinflamatorios"></textarea>
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>
            </div>

            <!-- Vital Signs at Admission -->
            <div class="form-section">
                <h5>Signos Vitales al Ingreso a UCPA</h5>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ta_ingreso">T.A. (mmHg):</label>
                            <input type="text" class="form-control" name="ta_ingreso" id="ta_ingreso"
                                placeholder="Ejemplo: 120/80" pattern="\d{2,3}/\d{2,3}" required>
                            <div class="invalid-feedback">Formato: sistólica/diastólica (ej. 120/80).</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fc_ingreso">F.C. (x min):</label>
                            <input type="number" class="form-control" name="fc_ingreso" id="fc_ingreso"
                                placeholder="Ejemplo: 72" min="0" max="300" required>
                            <div class="invalid-feedback">Debe estar entre 0 y 300.</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fr_ingreso">F.R. (rpm):</label>
                            <input type="number" class="form-control" name="fr_ingreso" id="fr_ingreso"
                                placeholder="Ejemplo: 16" min="0" max="100" required>
                            <div class="invalid-feedback">Debe estar entre 0 y 100.</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sato2_ingreso">SatO2 (%):</label>
                            <input type="number" class="form-control" name="sato2_ingreso" id="sato2_ingreso"
                                placeholder="Ejemplo: 98" min="0" max="100" required>
                            <div class="invalid-feedback">Debe estar entre 0 y 100.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Intraocular Pressure at Admission -->
            <div class="form-section">
                <div class="form-group">
                    <label for="pio_ingreso">Presión Intraocular (mmHg):</label>
                    <input type="text" class="form-control" name="pio_ingreso" id="pio_ingreso"
                        placeholder="Ejemplo: OD 18, OS 16" required>
                    <div class="invalid-feedback">Especifique la presión intraocular (ej. OD mmHg, OS mmHg).</div>
                </div>
            </div>

            <!-- Aldrete Scale -->
            <div class="form-section">
                <h5>Escala de Aldrete</h5>
                <table class="table table-bordered aldrete-table">
                    <thead>
                        <tr>
                            <th>Índice</th>
                            <th>Descripción</th>
                            <th>Puntos</th>
                            <th>Ingreso</th>
                            <th>Hora Ingreso</th>
                            <th>Alta</th>
                            <th>Hora Alta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="3">Actividad</td>
                            <td>Mueve las 4 extremidades</td>
                            <td>2</td>
                            <td><input type="radio" name="actividad_ingreso" value="2" required></td>
                            <td><input type="time" class="form-control time-input" name="actividad_hora_ingreso" required></td>
                            <td><input type="radio" name="actividad_alta" value="2" required></td>
                            <td><input type="time" class="form-control time-input" name="actividad_hora_alta" required></td>
                        </tr>
                        <tr>
                            <td>Mueve solo 2 extremidades</td>
                            <td>1</td>
                            <td><input type="radio" name="actividad_ingreso" value="1"></td>
                            <td></td>
                            <td><input type="radio" name="actividad_alta" value="1"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>No mueve ninguna extremidad</td>
                            <td>0</td>
                            <td><input type="radio" name="actividad_ingreso" value="0"></td>
                            <td></td>
                            <td><input type="radio" name="actividad_alta" value="0"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td rowspan="3">Respiración</td>
                            <td>Respira profundo, tose libremente</td>
                            <td>2</td>
                            <td><input type="radio" name="respiracion_ingreso" value="2" required></td>
                            <td><input type="time" class="form-control time-input" name="respiracion_hora_ingreso" required></td>
                            <td><input type="radio" name="respiracion_alta" value="2" required></td>
                            <td><input type="time" class="form-control time-input" name="respiracion_hora_alta" required></td>
                        </tr>
                        <tr>
                            <td>Disnea o limitación para toser</td>
                            <td>1</td>
                            <td><input type="radio" name="respiracion_ingreso" value="1"></td>
                            <td></td>
                            <td><input type="radio" name="respiracion_alta" value="1"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Apnea</td>
                            <td>0</td>
                            <td><input type="radio" name="respiracion_ingreso" value="0"></td>
                            <td></td>
                            <td><input type="radio" name="respiracion_alta" value="0"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td rowspan="3">Circulación</td>
                            <td>TA sistólica ↑↓ del 20% del nivel preanéstesico</td>
                            <td>2</td>
                            <td><input type="radio" name="circulacion_ingreso" value="2" required></td>
                            <td><input type="time" class="form-control time-input" name="circulacion_hora_ingreso" required></td>
                            <td><input type="radio" name="circulacion_alta" value="2" required></td>
                            <td><input type="time" class="form-control time-input" name="circulacion_hora_alta" required></td>
                        </tr>
                        <tr>
                            <td>TA sistólica ↑↓ del 20-50% del nivel</td>
                            <td>1</td>
                            <td><input type="radio" name="circulacion_ingreso" value="1"></td>
                            <td></td>
                            <td><input type="radio" name="circulacion_alta" value="1"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>TA sistólica ↑↓ del 50%</td>
                            <td>0</td>
                            <td><input type="radio" name="circulacion_ingreso" value="0"></td>
                            <td></td>
                            <td><input type="radio" name="circulacion_alta" value="0"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td rowspan="3">Conciencia</td>
                            <td>Completamente despierto</td>
                            <td>2</td>
                            <td><input type="radio" name="conciencia_ingreso" value="2" required></td>
                            <td><input type="time" class="form-control time-input" name="conciencia_hora_ingreso" required></td>
                            <td><input type="radio" name="conciencia_alta" value="2" required></td>
                            <td><input type="time" class="form-control time-input" name="conciencia_hora_alta" required></td>
                        </tr>
                        <tr>
                            <td>Responde al ser llamado</td>
                            <td>1</td>
                            <td><input type="radio" name="conciencia_ingreso" value="1"></td>
                            <td></td>
                            <td><input type="radio" name="conciencia_alta" value="1"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>No responde</td>
                            <td>0</td>
                            <td><input type="radio" name="conciencia_ingreso" value="0"></td>
                            <td></td>
                            <td><input type="radio" name="conciencia_alta" value="0"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td rowspan="3">Saturación</td>
                            <td>Mantiene más del 92% de SpO2 en aire</td>
                            <td>2</td>
                            <td><input type="radio" name="saturacion_ingreso" value="2" required></td>
                            <td><input type="time" class="form-control time-input" name="saturacion_hora_ingreso" required></td>
                            <td><input type="radio" name="saturacion_alta" value="2" required></td>
                            <td><input type="time" class="form-control time-input" name="saturacion_hora_alta" required></td>
                        </tr>
                        <tr>
                            <td>Necesita O2 para SpO2 >90%</td>
                            <td>1</td>
                            <td><input type="radio" name="saturacion_ingreso" value="1"></td>
                            <td></td>
                            <td><input type="radio" name="saturacion_alta" value="1"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>SpO2 <90% aún con O2</td>
                            <td>0</td>
                            <td><input type="radio" name="saturacion_ingreso" value="0"></td>
                            <td></td>
                            <td><input type="radio" name="saturacion_alta" value="0"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2">Total Puntuación</td>
                            <td colspan="5">
                                <input type="number" class="form-control" name="total_ingreso" id="total_ingreso" readonly required>
                                <input type="number" class="form-control mt-2" name="total_alta" id="total_alta" readonly required>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="hora_ingreso">Hora Total Ingreso:</label>
                            <input type="time" class="form-control" name="hora_ingreso" id="hora_ingreso" required>
                            <div class="invalid-feedback">Ingrese la hora de ingreso.</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="hora_alta">Hora Total Alta:</label>
                            <input type="time" class="form-control" name="hora_alta" id="hora_alta" required>
                            <div class="invalid-feedback">Ingrese la hora de alta.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Post-Anesthetic Evolution -->
            <div class="form-section">
                <div class="form-group">
                    <label for="evolucion_alta">Evolución y Alta Postanestésica de UCPA:</label>
                    <textarea class="form-control" name="evolucion_alta" id="evolucion_alta" rows="5" required
                        placeholder="Ejemplo: Paciente estable, cámara anterior formada"></textarea>
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>
            </div>

            <!-- Vital Signs at Discharge -->
            <div class="form-section">
                <h5>Signos Vitales al Alta de UCPA</h5>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ta_alta">T.A. (mmHg):</label>
                            <input type="text" class="form-control" name="ta_alta" id="ta_alta"
                                placeholder="Ejemplo: 120/80" pattern="\d{2,3}/\d{2,3}" required>
                            <div class="invalid-feedback">Formato: sistólica/diastólica (ej. 120/80).</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fc_alta">F.C. (x min):</label>
                            <input type="number" class="form-control" name="fc_alta" id="fc_alta"
                                placeholder="Ejemplo: 72" min="0" max="300" required>
                            <div class="invalid-feedback">Debe estar entre 0 y 300.</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fr_alta">F.R. (rpm):</label>
                            <input type="number" class="form-control" name="fr_alta" id="fr_alta"
                                placeholder="Ejemplo: 16" min="0" max="100" required>
                            <div class="invalid-feedback">Debe estar entre 0 y 100.</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sato2_alta">SatO2 (%):</label>
                            <input type="number" class="form-control" name="sato2_alta" id="sato2_alta"
                                placeholder="Ejemplo: 98" min="0" max="100" required>
                            <div class="invalid-feedback">Debe estar entre 0 y 100.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Intraocular Pressure at Discharge -->
            <div class="form-section">
                <div class="form-group">
                    <label for="pio_alta">Presión Intraocular al Alta (mmHg):</label>
                    <input type="text" class="form-control" name="pio_alta" id="pio_alta"
                        placeholder="Ejemplo: OD 20, OS 15" required>
                    <div class="invalid-feedback">Especifique la presión intraocular al alta.</div>
                </div>
            </div>

            <!-- Pain Control -->
            <div class="form-section">
                <div class="form-group">
                    <label for="control_dolor">Control de Dolor Postoperatorio:</label>
                    <textarea class="form-control" name="control_dolor" id="control_dolor" rows="3" required
                        placeholder="Ejemplo: Controlado con paracetamol"></textarea>
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>
            </div>

            <!-- Anesthesiology Discharge -->
            <div class="form-section">
                <h5>Alta de Anestesiología</h5>
                <div class="form-group">
                    <label for="horas_post_anestesia">Horas Post Anestesia:</label>
                    <input type="number" class="form-control" name="horas_post_anestesia" id="horas_post_anestesia"
                        placeholder="Ejemplo: 4" min="0" required>
                    <div class="invalid-feedback">Ingrese las horas post anestesia.</div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ta_final">T.A. (mmHg):</label>
                            <input type="text" class="form-control" name="ta_final" id="ta_final"
                                placeholder="Ejemplo: 120/80" pattern="\d{2,3}/\d{2,3}" required>
                            <div class="invalid-feedback">Formato: sistólica/diastólica (ej. 120/80).</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="pulso_final">Pulso (x min):</label>
                            <input type="number" class="form-control" name="pulso_final" id="pulso_final"
                                placeholder="Ejemplo: 72" min="0" max="300" required>
                            <div class="invalid-feedback">Debe estar entre 0 y 300.</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="resp_final">Resp. (rpm):</label>
                            <input type="number" class="form-control" name="resp_final" id="resp_final"
                                placeholder="Ejemplo: 16" min="0" max="100" required>
                            <div class="invalid-feedback">Debe estar entre 0 y 100.</div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Estado de Conciencia:</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="estado_conciencia" id="consciente" value="Consciente" required>
                        <label class="form-check-label" for="consciente">Consciente</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="estado_conciencia" id="somnoliento" value="Somnoliento">
                        <label class="form-check-label" for="somnoliento">Somnoliento</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="estado_conciencia" id="inconsciente" value="Inconsciente">
                        <label class="form-check-label" for="inconsciente">Inconsciente</label>
                    </div>
                    <div class="invalid-feedback">Seleccione una opción.</div>
                </div>
                <div class="form-group">
                    <label>Síntomas Oculares y Generales:</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="nauseas" id="nauseas" value="Sí">
                        <label class="form-check-label" for="nauseas">Náuseas</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="vomito" id="vomito" value="Sí">
                        <label class="form-check-label" for="vomito">Vómito</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="cefalea" id="cefalea" value="Sí">
                        <label class="form-check-label" for="cefalea">Cefalea</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="diuresis" id="diuresis" value="Sí">
                        <label class="form-check-label" for="diuresis">Diuresis</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="dolor_ocular" id="dolor_ocular" value="Sí">
                        <label class="form-check-label" for="dolor_ocular">Dolor Ocular</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="vision_borrosa" id="vision_borrosa" value="Sí">
                        <label class="form-check-label" for="vision_borrosa">Visión Borrosa</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="evolucion_final">Evolución:</label>
                    <textarea class="form-control" name="evolucion_final" id="evolucion_final" rows="3" required
                        placeholder="Ejemplo: Paciente con visión mejorada"></textarea>
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>
                <div class="form-group">
                    <label>Deambulación:</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="deambulacion" id="deambulacion_si" value="Sí" required>
                        <label class="form-check-label" for="deambulacion_si">Sí</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="deambulacion" id="deambulacion_no" value="No">
                        <label class="form-check-label" for="deambulacion_no">No</label>
                    </div>
                    <div class="invalid-feedback">Seleccione una opción.</div>
                </div>
                <div class="form-group">
                    <label for="indicaciones_alta">Indicaciones de Alta:</label>
                    <textarea class="form-control" name="indicaciones_alta" id="indicaciones_alta" rows="5" required
                        placeholder="Ejemplo: Usar colirios, evitar frotar el ojo"></textarea>
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>
            </div>

            <!-- Form Buttons -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-danger" onclick="history.back()">Cancelar</button>
            </div>
        </form>

        <!-- Footer -->
        <footer class="main-footer">
            <?php include '../../template/footer.php'; ?>
        </footer>
    </div>

    <!-- JavaScript for form handling -->
    <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
    <script src="../../template/js/app.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle incidentes radio buttons
            $('#incidentes_si').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#detalle_incidentes').show();
                    $('#detalle_incidentes').prop('required', true);
                }
            });
            $('#incidentes_no').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#detalle_incidentes').hide();
                    $('#detalle_incidentes').val('');
                    $('#detalle_incidentes').prop('required', false);
                }
            });

            // Calculate Aldrete scores dynamically
            function updateAldreteScores() {
                const ingreso = [
                    parseInt($('input[name="actividad_ingreso"]:checked').val() || 0),
                    parseInt($('input[name="respiracion_ingreso"]:checked').val() || 0),
                    parseInt($('input[name="circulacion_ingreso"]:checked').val() || 0),
                    parseInt($('input[name="conciencia_ingreso"]:checked').val() || 0),
                    parseInt($('input[name="saturacion_ingreso"]:checked').val() || 0)
                ].reduce((sum, val) => sum + val, 0);

                const alta = [
                    parseInt($('input[name="actividad_alta"]:checked').val() || 0),
                    parseInt($('input[name="respiracion_alta"]:checked').val() || 0),
                    parseInt($('input[name="circulacion_alta"]:checked').val() || 0),
                    parseInt($('input[name="conciencia_alta"]:checked').val() || 0),
                    parseInt($('input[name="saturacion_alta"]:checked').val() || 0)
                ].reduce((sum, val) => sum + val, 0);

                $('#total_ingreso').val(ingreso);
                $('#total_alta').val(alta);
            }

            updateAldreteScores();
            $('input[name^="actividad_"], input[name^="respiracion_"], input[name^="circulacion_"], input[name^="conciencia_"], input[name^="saturacion_"]').on('change', updateAldreteScores);

            // Form validation on submit
            $('#postAnestheticOcularForm').on('submit', function(event) {
                let isValid = true;
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').hide();

                // Validate required non-radio inputs
                $('input[type="text"][required]:not([pattern]), textarea[required], select[required], input[type="number"][required], input[type="time"][required]').each(function() {
                    if (!$(this).val().trim()) {
                        isValid = false;
                        $(this).addClass('is-invalid');
                        $(this).siblings('.invalid-feedback').text('Este campo es obligatorio.').show();
                    }
                });

                // Validate blood pressure fields
                ['ta_ingreso', 'ta_alta', 'ta_final'].forEach(id => {
                    const ta = $(`#${id}`);
                    if (!/^\d{2,3}\/\d{2,3}$/.test(ta.val())) {
                        isValid = false;
                        ta.addClass('is-invalid');
                        ta.siblings('.invalid-feedback').text('Formato: sistólica/diastólica (ej. 120/80).').show();
                    }
                });

                // Validate numeric fields
                const numericFields = [
                    { id: 'fc_ingreso', min: 0, max: 300, label: 'F.C. Ingreso' },
                    { id: 'fr_ingreso', min: 0, max: 100, label: 'F.R. Ingreso' },
                    { id: 'sato2_ingreso', min: 0, max: 100, label: 'SatO2 Ingreso' },
                    { id: 'fc_alta', min: 0, max: 300, label: 'F.C. Alta' },
                    { id: 'fr_alta', min: 0, max: 100, label: 'F.R. Alta' },
                    { id: 'sato2_alta', min: 0, max: 100, label: 'SatO2 Alta' },
                    { id: 'pulso_final', min: 0, max: 300, label: 'Pulso Final' },
                    { id: 'resp_final', min: 0, max: 100, label: 'Resp. Final' },
                    { id: 'horas_post_anestesia', min: 0, max: 999, label: 'Horas Post Anestesia' }
                ];

                numericFields.forEach(field => {
                    const input = $(`#${field.id}`);
                    const value = parseFloat(input.val());
                    if (input.val().trim() === '') {
                        isValid = false;
                        input.addClass('is-invalid');
                        input.siblings('.invalid-feedback').text(`Este campo es obligatorio.`).show();
                    } else if (isNaN(value) || value < field.min || (field.max && value > field.max)) {
                        isValid = false;
                        input.addClass('is-invalid');
                        input.siblings('.invalid-feedback').text(
                            `${field.label} debe estar entre ${field.min} y ${field.max || 'infinito'}.`
                        ).show();
                    }
                });

                // Validate radio button groups
                ['incidentes', 'estado_conciencia', 'deambulacion'].forEach(field => {
                    const radios = $(`input[name="${field}"]`);
                    if (!radios.is(':checked')) {
                        isValid = false;
                        radios.addClass('is-invalid');
                        radios.closest('.form-group').find('.invalid-feedback').text('Seleccione una opción.').show();
                    } else {
                        radios.removeClass('is-invalid');
                    }
                });

                // Validate Aldrete time inputs
                $('.aldrete-table input[type="time"][required]').each(function() {
                    if (!$(this).val()) {
                        isValid = false;
                        $(this).addClass('is-invalid');
                    }
                });

                // Validate intraocular pressure
                ['pio_ingreso', 'pio_alta'].forEach(id => {
                    const pio = $(`#${id}`);
                    if (!pio.val().trim()) {
                        isValid = false;
                        pio.addClass('is-invalid');
                        pio.siblings('.invalid-feedback').text('Especifique la presión intraocular.').show();
                    }
                });

                if (!isValid) {
                    event.preventDefault();
                    $('html, body').animate({
                        scrollTop: $('.is-invalid:first').offset().top - 100
                    }, 500);
                }
            });
        });
    </script>
</body>
</html>