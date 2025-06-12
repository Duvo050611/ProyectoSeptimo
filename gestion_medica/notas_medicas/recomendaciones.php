<?php
session_start();
include "../../conexionbd.php";

if(!isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

include("../header_medico.php");

// Fetch specialties from rol table
$specialties = $conexion->query("SELECT id_rol, rol FROM rol ORDER BY rol ASC");
$doctors = $conexion->query("SELECT id_usua, CONCAT(nombre, ' ', papell, ' ', sapell) AS full_name FROM reg_usuarios WHERE id_rol IN (SELECT id_rol FROM rol WHERE rol LIKE '%médico%') ORDER BY full_name ASC");

// Function to check for appointment conflicts
function checkAppointmentConflict($conn, $id_atencion, $date, $time) {
    $datetime = date('Y-m-d H:i:s', strtotime("$date $time"));
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM dat_ingreso WHERE id_atencion = ? AND fecha BETWEEN DATE_SUB(?, INTERVAL 1 HOUR) AND DATE_ADD(?, INTERVAL 1 HOUR)");
    $stmt->bind_param("iss", $id_atencion, $datetime, $datetime);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['count'] > 0;
}

$id_atencion = $_SESSION['hospital'];
$conflict = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proxima_cita']) && isset($_POST['intervalo'])) {
    $conflict = checkAppointmentConflict($conexion, $id_atencion, $_POST['proxima_cita'], $_POST['intervalo']);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />

    <!-- Select2 CSS -->
    <link rel="stylesheet" type="text/css" href="css/select2.css">

    <!-- FontAwesome -->
    <link href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <!-- Bootstrap 4.5 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">

    <!-- jQuery (usar solo una versión) -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <!-- Select2 JS -->
    <script src="js/select2.js"></script>

    <!-- Popper.js necesario para Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldLv/Pr4nhuBviF5jGqQK/5i2Q5iZ64dxBl+zOZ" crossorigin="anonymous">
    </script>

    <!-- Bootstrap 4.5 JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>

    <!-- Scripts adicionales -->
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/main.js"></script>

    <script>
    // Filtro de búsqueda en tabla
    $(document).ready(function() {
        $("#search").keyup(function() {
            var valor = $(this).val().toLowerCase();
            $("#mytable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(valor) > -1)
            });
        });
    });
    </script>

    <title>INEO Metepec</title>
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

    .no-print {
        display: none;
    }

    @media print {
        .no-print {
            display: block !important;
        }

        .print-hidden {
            display: none !important;
        }
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .thead {
        background-color: #2b2d7f;
        color: white;
        font-size: 22px;
        padding: 10px;
        text-align: center;
    }

    .conflict {
        border: 2px solid red !important;
        background-color: #ffebee !important;
    }
    </style>

    <script>
    $(document).ready(function() {
        $('#proxima_cita, #intervalo').on('change', function() {
            var date = $('#proxima_cita').val();
            var time = $('#intervalo').val();
            if (date && time) {
                $.ajax({
                    url: 'check_conflict.php',
                    type: 'POST',
                    data: {
                        id_atencion: '<?php echo $id_atencion; ?>',
                        date: date,
                        time: time
                    },
                    success: function(response) {
                        if (response === 'true') {
                            $('#intervalo').addClass('conflict').attr('title',
                                'Conflicto de horario detectado');
                        } else {
                            $('#intervalo').removeClass('conflict').removeAttr('title');
                        }
                    }
                });
            }
        });
    });
    </script>

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
        <div class="row">
            <div class="col">
                <div class="thead">
                    <strong>
                        <center>RECOMENDACIONES</center>
                    </strong>
                    <?php 
                if (isset($_SESSION['hospital'])) {
                    $id_atencion = $_SESSION['hospital'];
                    $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.Id_exp FROM paciente p, dat_ingreso di WHERE p.Id_exp=di.Id_exp AND di.id_atencion = ?";
                    $stmt = $conexion->prepare($sql_pac);
                    $stmt->bind_param("i", $id_atencion);
                    $stmt->execute();
                    $result_pac = $stmt->get_result();
                    $row_pac = $result_pac->fetch_assoc();
                    $pac_papell = $row_pac['papell'];
                    $pac_sapell = $row_pac['sapell'];
                    $pac_nom_pac = $row_pac['nom_pac'];
                    $id_exp = $row_pac['Id_exp'];
                    $stmt->close();
                }
                ?>
                </div>
            </div>

        </div>
        <form action="insertar_recomendaciones.php" method="POST" class="mt-4" onsubmit="return checkSubmit();">
            <input type="hidden" name="id_usua" value="<?php echo htmlspecialchars($_SESSION['login']['id_usua']); ?>">
            <div class="form-group">
                <label for="notas_internas">Notas Internas <span class="no-print">(No se imprime)</span></label>
                <div class="botones">
                    <button type="button" class="btn btn-danger btn-sm" id="notas_internas_grabar"><i
                            class="fas fa-microphone"></i></button>
                    <button type="button" class="btn btn-primary btn-sm" id="notas_internas_detener"><i
                            class="fas fa-microphone-slash"></i></button>
                    <button type="button" class="btn btn-success btn-sm" id="play_notas_internas"><i
                            class="fas fa-play"></i></button>
                </div>
                <textarea class="form-control" id="notas_internas" name="notas_internas" rows="3"
                    placeholder="Notas internas..."></textarea>
                <script>
                const notas_internas_grabar = document.getElementById('notas_internas_grabar');
                const notas_internas_detener = document.getElementById('notas_internas_detener');
                const notas_internas = document.getElementById('notas_internas');
                const btn_notas_internas = document.getElementById('play_notas_internas');
                btn_notas_internas.addEventListener('click', () => {
                    leerTexto(notas_internas.value);
                });
                let recognition_notas_internas = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
                recognition_notas_internas.lang = "es-ES";
                recognition_notas_internas.continuous = true;
                recognition_notas_internas.interimResults = false;
                recognition_notas_internas.onresult = (event) => {
                    const results = event.results;
                    const frase = results[results.length - 1][0].transcript;
                    notas_internas.value += frase;
                };
                notas_internas_grabar.addEventListener('click', () => {
                    recognition_notas_internas.start();
                });
                notas_internas_detener.addEventListener('click', () => {
                    recognition_notas_internas.stop();
                });
                </script>
            </div>
            <div class="form-group">
                <label>Observaciones</label>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="observaciones_od"><strong>Ojo Derecho (OD):</strong></label>
                        <div class="botones">
                            <button type="button" class="btn btn-danger btn-sm" id="observaciones_od_grabar"><i
                                    class="fas fa-microphone"></i></button>
                            <button type="button" class="btn btn-primary btn-sm" id="observaciones_od_detener"><i
                                    class="fas fa-microphone-slash"></i></button>
                            <button type="button" class="btn btn-success btn-sm" id="play_observaciones_od"><i
                                    class="fas fa-play"></i></button>
                        </div>
                        <textarea class="form-control" id="observaciones_od" name="observaciones_od" rows="3"
                            placeholder="Observaciones Ojo Derecho..."></textarea>
                        <script>
                        const observaciones_od_grabar = document.getElementById('observaciones_od_grabar');
                        const observaciones_od_detener = document.getElementById('observaciones_od_detener');
                        const observaciones_od = document.getElementById('observaciones_od');
                        const btn_observaciones_od = document.getElementById('play_observaciones_od');
                        btn_observaciones_od.addEventListener('click', () => {
                            leerTexto(observaciones_od.value);
                        });
                        let recognition_observaciones_od = new(window.SpeechRecognition || window
                            .webkitSpeechRecognition)();
                        recognition_observaciones_od.lang = "es-ES";
                        recognition_observaciones_od.continuous = true;
                        recognition_observaciones_od.interimResults = false;
                        recognition_observaciones_od.onresult = (event) => {
                            const results = event.results;
                            const frase = results[results.length - 1][0].transcript;
                            observaciones_od.value += frase;
                        };
                        observaciones_od_grabar.addEventListener('click', () => {
                            recognition_observaciones_od.start();
                        });
                        observaciones_od_detener.addEventListener('click', () => {
                            recognition_observaciones_od.stop();
                        });
                        </script>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="observaciones_oi"><strong>Ojo Izquierdo (OI):</strong></label>
                        <div class="botones">
                            <button type="button" class="btn btn-danger btn-sm" id="observaciones_oi_grabar"><i
                                    class="fas fa-microphone"></i></button>
                            <button type="button" class="btn btn-primary btn-sm" id="observaciones_oi_detener"><i
                                    class="fas fa-microphone-slash"></i></button>
                            <button type="button" class="btn btn-success btn-sm" id="play_observaciones_oi"><i
                                    class="fas fa-play"></i></button>
                        </div>
                        <textarea class="form-control" id="observaciones_oi" name="observaciones_oi" rows="3"
                            placeholder="Observaciones Ojo Izquierdo..."></textarea>
                        <script>
                        const observaciones_oi_grabar = document.getElementById('observaciones_oi_grabar');
                        const observaciones_oi_detener = document.getElementById('observaciones_oi_detener');
                        const observaciones_oi = document.getElementById('observaciones_oi');
                        const btn_observaciones_oi = document.getElementById('play_observaciones_oi');
                        btn_observaciones_oi.addEventListener('click', () => {
                            leerTexto(observaciones_oi.value);
                        });
                        let recognition_observaciones_oi = new(window.SpeechRecognition || window
                            .webkitSpeechRecognition)();
                        recognition_observaciones_oi.lang = "es-ES";
                        recognition_observaciones_oi.continuous = true;
                        recognition_observaciones_oi.interimResults = false;
                        recognition_observaciones_oi.onresult = (event) => {
                            const results = event.results;
                            const frase = results[results.length - 1][0].transcript;
                            observaciones_oi.value += frase;
                        };
                        observaciones_oi_grabar.addEventListener('click', () => {
                            recognition_observaciones_oi.start();
                        });
                        observaciones_oi_detener.addEventListener('click', () => {
                            recognition_observaciones_oi.stop();
                        });
                        </script>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Recomendaciones</label>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="recomendaciones_od"><strong>Ojo Derecho (OD):</strong></label>
                        <div class="botones">
                            <button type="button" class="btn btn-danger btn-sm" id="recomendaciones_od_grabar"><i
                                    class="fas fa-microphone"></i></button>
                            <button type="button" class="btn btn-primary btn-sm" id="recomendaciones_od_detener"><i
                                    class="fas fa-microphone-slash"></i></button>
                            <button type="button" class="btn btn-success btn-sm" id="play_recomendaciones_od"><i
                                    class="fas fa-play"></i></button>
                        </div>
                        <textarea class="form-control" id="recomendaciones_od" name="recomendaciones_od" rows="3"
                            placeholder="Recomendaciones Ojo Derecho..."></textarea>
                        <script>
                        const recomendaciones_od_grabar = document.getElementById('recomendaciones_od_grabar');
                        const recomendaciones_od_detener = document.getElementById('recomendaciones_od_detener');
                        const recomendaciones_od = document.getElementById('recomendaciones_od');
                        const btn_recomendaciones_od = document.getElementById('play_recomendaciones_od');
                        btn_recomendaciones_od.addEventListener('click', () => {
                            leerTexto(recomendaciones_od.value);
                        });
                        let recognition_recomendaciones_od = new(window.SpeechRecognition || window
                            .webkitSpeechRecognition)();
                        recognition_recomendaciones_od.lang = "es-ES";
                        recognition_recomendaciones_od.continuous = true;
                        recognition_recomendaciones_od.interimResults = false;
                        recognition_recomendaciones_od.onresult = (event) => {
                            const results = event.results;
                            const frase = results[results.length - 1][0].transcript;
                            recomendaciones_od.value += frase;
                        };
                        recomendaciones_od_grabar.addEventListener('click', () => {
                            recognition_recomendaciones_od.start();
                        });
                        recomendaciones_od_detener.addEventListener('click', () => {
                            recognition_recomendaciones_od.stop();
                        });
                        </script>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="recomendaciones_oi"><strong>Ojo Izquierdo (OI):</strong></label>
                        <div class="botones">
                            <button type="button" class="btn btn-danger btn-sm" id="recomendaciones_oi_grabar"><i
                                    class="fas fa-microphone"></i></button>
                            <button type="button" class="btn btn-primary btn-sm" id="recomendaciones_oi_detener"><i
                                    class="fas fa-microphone-slash"></i></button>
                            <button type="button" class="btn btn-success btn-sm" id="play_recomendaciones_oi"><i
                                    class="fas fa-play"></i></button>
                        </div>
                        <textarea class="form-control" id="recomendaciones_oi" name="recomendaciones_oi" rows="3"
                            placeholder="Recomendaciones Ojo Izquierdo..."></textarea>
                        <script>
                        const recomendaciones_oi_grabar = document.getElementById('recomendaciones_oi_grabar');
                        const recomendaciones_oi_detener = document.getElementById('recomendaciones_oi_detener');
                        const recomendaciones_oi = document.getElementById('recomendaciones_oi');
                        const btn_recomendaciones_oi = document.getElementById('play_recomendaciones_oi');
                        btn_recomendaciones_oi.addEventListener('click', () => {
                            leerTexto(recomendaciones_oi.value);
                        });
                        let recognition_recomendaciones_oi = new(window.SpeechRecognition || window
                            .webkitSpeechRecognition)();
                        recognition_recomendaciones_oi.lang = "es-ES";
                        recognition_recomendaciones_oi.continuous = true;
                        recognition_recomendaciones_oi.interimResults = false;
                        recognition_recomendaciones_oi.onresult = (event) => {
                            const results = event.results;
                            const frase = results[results.length - 1][0].transcript;
                            recomendaciones_oi.value += frase;
                        };
                        recomendaciones_oi_grabar.addEventListener('click', () => {
                            recognition_recomendaciones_oi.start();
                        });
                        recomendaciones_oi_detener.addEventListener('click', () => {
                            recognition_recomendaciones_oi.stop();
                        });
                        </script>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="recomendaciones_general"><strong>General:</strong></label>
                        <div class="botones">
                            <button type="button" class="btn btn-danger btn-sm" id="recomendaciones_general_grabar"><i
                                    class="fas fa-microphone"></i></button>
                            <button type="button" class="btn btn-primary btn-sm" id="recomendaciones_general_detener"><i
                                    class="fas fa-microphone-slash"></i></button>
                            <button type="button" class="btn btn-success btn-sm" id="play_recomendaciones_general"><i
                                    class="fas fa-play"></i></button>
                        </div>
                        <textarea class="form-control" id="recomendaciones_general" name="recomendaciones_general"
                            rows="3" placeholder="Recomendaciones Generales..."></textarea>
                        <script>
                        const recomendaciones_general_grabar = document.getElementById(
                        'recomendaciones_general_grabar');
                        const recomendaciones_general_detener = document.getElementById(
                            'recomendaciones_general_detener');
                        const recomendaciones_general = document.getElementById('recomendaciones_general');
                        const btn_recomendaciones_general = document.getElementById('play_recomendaciones_general');
                        btn_recomendaciones_general.addEventListener('click', () => {
                            leerTexto(recomendaciones_general.value);
                        });
                        let recognition_recomendaciones_general = new(window.SpeechRecognition || window
                            .webkitSpeechRecognition)();
                        recognition_recomendaciones_general.lang = "es-ES";
                        recognition_recomendaciones_general.continuous = true;
                        recognition_recomendaciones_general.interimResults = false;
                        recognition_recomendaciones_general.onresult = (event) => {
                            const results = event.results;
                            const frase = results[results.length - 1][0].transcript;
                            recomendaciones_general.value += frase;
                        };
                        recomendaciones_general_grabar.addEventListener('click', () => {
                            recognition_recomendaciones_general.start();
                        });
                        recomendaciones_general_detener.addEventListener('click', () => {
                            recognition_recomendaciones_general.stop();
                        });
                        </script>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="educacion_paciente">Educación al Paciente</label>
                <div class="botones">
                    <button type="button" class="btn btn-danger btn-sm" id="educacion_paciente_grabar"><i
                            class="fas fa-microphone"></i></button>
                    <button type="button" class="btn btn-primary btn-sm" id="educacion_paciente_detener"><i
                            class="fas fa-microphone-slash"></i></button>
                    <button type="button" class="btn btn-success btn-sm" id="play_educacion_paciente"><i
                            class="fas fa-play"></i></button>
                </div>
                <textarea class="form-control" id="educacion_paciente" name="educacion_paciente" rows="3"
                    placeholder="Educación al paciente..."></textarea>
                <script>
                const educacion_paciente_grabar = document.getElementById('educacion_paciente_grabar');
                const educacion_paciente_detener = document.getElementById('educacion_paciente_detener');
                const educacion_paciente = document.getElementById('educacion_paciente');
                const btn_educacion_paciente = document.getElementById('play_educacion_paciente');
                btn_educacion_paciente.addEventListener('click', () => {
                    leerTexto(educacion_paciente.value);
                });
                let recognition_educacion_paciente = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
                recognition_educacion_paciente.lang = "es-ES";
                recognition_educacion_paciente.continuous = true;
                recognition_educacion_paciente.interimResults = false;
                recognition_educacion_paciente.onresult = (event) => {
                    const results = event.results;
                    const frase = results[results.length - 1][0].transcript;
                    educacion_paciente.value += frase;
                };
                educacion_paciente_grabar.addEventListener('click', () => {
                    recognition_educacion_paciente.start();
                });
                educacion_paciente_detener.addEventListener('click', () => {
                    recognition_educacion_paciente.stop();
                });
                </script>
            </div>
            <div class="form-group">
                <label>Pronóstico</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="pronostico[]" id="pronostico_bueno"
                        value="Bueno">
                    <label class="form-check-label" for="pronostico_bueno">Bueno</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="pronostico[]" id="pronostico_malo"
                        value="Malo">
                    <label class="form-check-label" for="pronostico_malo">Malo</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="pronostico[]" id="pronostico_reservado"
                        value="Reservado">
                    <label class="form-check-label" for="pronostico_reservado">Reservado</label>
                </div>
                <div class="botones mt-2">
                    <button type="button" class="btn btn-danger btn-sm" id="pronostico_text_grabar"><i
                            class="fas fa-microphone"></i></button>
                    <button type="button" class="btn btn-primary btn-sm" id="pronostico_text_detener"><i
                            class="fas fa-microphone-slash"></i></button>
                    <button type="button" class="btn btn-success btn-sm" id="play_pronostico_text"><i
                            class="fas fa-play"></i></button>
                </div>
                <textarea class="form-control" id="pronostico_text" name="pronostico_text" rows="3"
                    placeholder="Detalles del pronóstico..."></textarea>
                <script>
                const pronostico_text_grabar = document.getElementById('pronostico_text_grabar');
                const pronostico_text_detener = document.getElementById('pronostico_text_detener');
                const pronostico_text = document.getElementById('pronostico_text');
                const btn_pronostico_text = document.getElementById('play_pronostico_text');
                btn_pronostico_text.addEventListener('click', () => {
                    leerTexto(pronostico_text.value);
                });
                let recognition_pronostico_text = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
                recognition_pronostico_text.lang = "es-ES";
                recognition_pronostico_text.continuous = true;
                recognition_pronostico_text.interimResults = false;
                recognition_pronostico_text.onresult = (event) => {
                    const results = event.results;
                    const frase = results[results.length - 1][0].transcript;
                    pronostico_text.value += frase;
                };
                pronostico_text_grabar.addEventListener('click', () => {
                    recognition_pronostico_text.start();
                });
                pronostico_text_detener.addEventListener('click', () => {
                    recognition_pronostico_text.stop();
                });
                </script>
            </div>
            <div class="form-group">
                <label>Próxima Cita</label>
                <div class="row align-items-center">
                    <div class="col-auto mb-3">
                        <input type="date" class="form-control" id="proxima_cita" name="proxima_cita" required>
                    </div>
                    <div class="col-auto mb-3">
                        <input type="time" class="form-control" id="intervalo" name="intervalo" required
                            <?php echo $conflict ? 'class="conflict" title="Conflicto de horario detectado"' : ''; ?>>
                    </div>
                    <div class="col-auto mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="con_resultados" id="con_resultados"
                                value="1">
                            <label class="form-check-label" for="con_resultados">Con Resultados</label>
                        </div>
                    </div>
                    <div class="col-auto mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="cirugia" id="cirugia" value="1">
                            <label class="form-check-label" for="cirugia">Cirugía</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Interconsultas</label>
                <select class="form-control" id="especialista" name="especialista">
                    <option value="">Seleccione Especialidad</option>
                    <?php while ($row = $specialties->fetch_assoc()): ?>
                    <option value="<?php echo $row['id_rol']; ?>"><?php echo $row['rol']; ?></option>
                    <?php endwhile; ?>
                </select>
                <select class="form-control mt-2" id="doctor" name="doctor">
                    <option value="">Seleccione Doctor</option>
                    <?php $doctors->data_seek(0); while ($row = $doctors->fetch_assoc()): ?>
                    <option value="<?php echo $row['id_usua']; ?>"><?php echo $row['full_name']; ?></option>
                    <?php endwhile; ?>
                </select>
                <div class="botones mt-2">
                    <button type="button" class="btn btn-danger btn-sm" id="interconsultas_text_grabar"><i
                            class="fas fa-microphone"></i></button>
                    <button type="button" class="btn btn-primary btn-sm" id="interconsultas_text_detener"><i
                            class="fas fa-microphone-slash"></i></button>
                    <button type="button" class="btn btn-success btn-sm" id="play_interconsultas_text"><i
                            class="fas fa-play"></i></button>
                </div>
                <textarea class="form-control" id="interconsultas_text" name="interconsultas_text" rows="3"
                    placeholder="Detalles de interconsulta..."></textarea>
                <script>
                const interconsultas_text_grabar = document.getElementById('interconsultas_text_grabar');
                const interconsultas_text_detener = document.getElementById('interconsultas_text_detener');
                const interconsultas_text = document.getElementById('interconsultas_text');
                const btn_interconsultas_text = document.getElementById('play_interconsultas_text');
                btn_interconsultas_text.addEventListener('click', () => {
                    leerTexto(interconsultas_text.value);
                });
                let recognition_interconsultas_text = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
                recognition_interconsultas_text.lang = "es-ES";
                recognition_interconsultas_text.continuous = true;
                recognition_interconsultas_text.interimResults = false;
                recognition_interconsultas_text.onresult = (event) => {
                    const results = event.results;
                    const frase = results[results.length - 1][0].transcript;
                    interconsultas_text.value += frase;
                };
                interconsultas_text_grabar.addEventListener('click', () => {
                    recognition_interconsultas_text.start();
                });
                interconsultas_text_detener.addEventListener('click', () => {
                    recognition_interconsultas_text.stop();
                });
                </script>
            </div>
            <div class="form-group">
                <label for="observaciones_justificante">Observaciones Justificante</label>
                <div class="botones">
                    <button type="button" class="btn btn-danger btn-sm" id="observaciones_justificante_grabar"><i
                            class="fas fa-microphone"></i></button>
                    <button type="button" class="btn btn-primary btn-sm" id="observaciones_justificante_detener"><i
                            class="fas fa-microphone-slash"></i></button>
                    <button type="button" class="btn btn-success btn-sm" id="play_observaciones_justificante"><i
                            class="fas fa-play"></i></button>
                </div>
                <textarea class="form-control" id="observaciones_justificante" name="observaciones_justificante"
                    rows="3" placeholder="Observaciones justificante..."></textarea>
                <script>
                const observaciones_justificante_grabar = document.getElementById('observaciones_justificante_grabar');
                const observaciones_justificante_detener = document.getElementById(
                'observaciones_justificante_detener');
                const observaciones_justificante = document.getElementById('observaciones_justificante');
                const btn_observaciones_justificante = document.getElementById('play_observaciones_justificante');
                btn_observaciones_justificante.addEventListener('click', () => {
                    leerTexto(observaciones_justificante.value);
                });
                let recognition_observaciones_justificante = new(window.SpeechRecognition || window
                    .webkitSpeechRecognition)();
                recognition_observaciones_justificante.lang = "es-ES";
                recognition_observaciones_justificante.continuous = true;
                recognition_observaciones_justificante.interimResults = false;
                recognition_observaciones_justificante.onresult = (event) => {
                    const results = event.results;
                    const frase = results[results.length - 1][0].transcript;
                    observaciones_justificante.value += frase;
                };
                observaciones_justificante_grabar.addEventListener('click', () => {
                    recognition_observaciones_justificante.start();
                });
                observaciones_justificante_detener.addEventListener('click', () => {
                    recognition_observaciones_justificante.stop();
                });
                </script>
            </div>
            <center class="mt-3">
                <button type="submit" class="btn btn-primary">Firmar</button>
                <button type="button" class="btn btn-danger" onclick="history.back()">Cancelar</button>
            </center>
        </form>
        <script>
        function leerTexto(texto) {
            const speech = new SpeechSynthesisUtterance();
            speech.text = texto;
            speech.volume = 1;
            speech.rate = 1;
            speech.pitch = 0;
            speech.lang = "es-ES";
            window.speechSynthesis.speak(speech);
        }
        </script>
    </div>
    </div>


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