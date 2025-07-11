<?php
session_start();
include "../../conexionbd.php";
include "../header_medico.php";

if (!isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

if ($conexion) {
    $id_atencion = $_SESSION['hospital'];
    $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.folio, p.tel, p.fecnac, p.tip_san, di.fecha, di.area, di.alta_med, di.activo, p.sexo, di.alergias, p.ocup, di.id_usua
                FROM paciente p
                INNER JOIN dat_ingreso di ON p.Id_exp = di.Id_exp
                WHERE di.id_atencion = ?";
    $stmt = $conexion->prepare($sql_pac);
    $stmt->bind_param("i", $id_atencion);
    $stmt->execute();
    $result_pac = $stmt->get_result();
    while ($row_pac = $result_pac->fetch_assoc()) {
        $pac_papell = $row_pac['papell'];
        $pac_sapell = $row_pac['sapell'];
        $pac_nom_pac = $row_pac['nom_pac'];
        $pac_folio = $row_pac['folio'];
        $pac_fecha_ingreso = $row_pac['fecha'];
        $pac_fecnac = $row_pac['fecnac'];
        $activo = $row_pac['activo'];
        $pac_id_exp = $row_pac['Id_exp'];
        $pac_sexo = $row_pac['sexo'];
        $pac_tip_san = $row_pac['tip_san'];
        $pac_ocup = $row_pac['ocup'];
        $pac_tel = $row_pac['tel'];
        $pac_dir = $row_pac['dir'];
        $pac_area = $row_pac['area'];
        $pac_alta_med = $row_pac['alta_med'];
        $pac_alergias = $row_pac['alergias'];
        $pac_id_usua = $row_pac['id_usua'];
    }

    $stmt->close();
    $conexion->close();
} else {
    echo '<p style="color: red;">Error de conexión a la base de datos</p>';
}
?>
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
    .section-title {
      margin-top: 30px;
      margin-bottom: 20px;
      font-weight: 600;
      color: #2b2d7f;
      border-bottom: 2px solid #2b2d7f;
      padding-bottom: 5px;
    }
  </style>
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
                    <div class="col-sm-4">Fecha de atención:
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
                    <div class="col-sm-4">Área: <strong><?php echo $num_cama .' - '.$area;?> </strong></div> 
                </div>
                <div class="row">
                    <div class="col-sm-8">
                        <?php echo $d ? "Diagnóstico: <strong>$d</strong>" : "Motivo de atención: <strong>$m</strong>"; 
                        ?>
                    </div>

                    <div class="col-sm-4">Alergias: <strong><?php echo $alergias; ?></strong></div>
                   
                    
                </div>

            </div>
        </div>
    </div><br>
    <div class="container">
<h4 class="thead">Exploración PIO</h4>

<form method="POST" action="guardar_PIO.php" class="container mt-4">

    <!-- Hidden Inputs -->
    <input type="hidden" name="id_exp" value="<?= htmlspecialchars($pac_id_exp) ?>">
    <input type="hidden" name="id_usua" value="<?= htmlspecialchars($pac_id_usua) ?>">
    <input type="hidden" name="id_atencion" value="<?= htmlspecialchars($id_atencion) ?>">

    <!-- Ojo Derecho -->
    <fieldset class="border p-3 mb-4">
        <legend class="w-auto px-2">Ojo Derecho (OD)</legend>

        <div class="form-group">
            <label for="pio_aplanacion_previa_OD">PIO Aplanación Previa (mmHg)</label>
            <input type="number" step="0.1" min="0" class="form-control" name="pio_aplanacion_previa_OD" id="pio_aplanacion_previa_OD" placeholder="Ej: 14.9" required>
        </div>

        <div class="form-group">
            <label for="pio_tng_previa_OD">PIO TNG Previa (mmHg)</label>
            <input type="number" step="0.1" min="0" class="form-control" name="pio_tng_previa_OD" id="pio_tng_previa_OD" placeholder="Ej: 16.2" required>
        </div>

        <div class="form-group">
            <label for="pio_aplanacion_OD">PIO Aplanación (mmHg)</label>
            <div class="input-group">
                <input type="number" step="0.1" min="0" class="form-control" name="pio_aplanacion_OD" id="pio_aplanacion_OD" placeholder="Ej: 15.0" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <input type="checkbox" id="no_colabora_aplanacion_OD" onchange="toggleInput('no_colabora_aplanacion_OD', 'pio_aplanacion_OD')"> <label for="no_colabora_aplanacion_OD" class="ml-2 mb-0">No Colabora</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="pio_tnc_tipo_OD">Tipo PIO TNC</label>
            <select name="pio_tnc_tipo_OD" id="pio_tnc_tipo_OD" class="form-control" onchange="
                const selected = this.value;
                const input = document.getElementById('pio_tnc_OD');
                input.disabled = (selected === 'No Colabora');
                if(selected === 'No Colabora') input.value = '';
            " required>
                <option value="">Seleccione...</option>
                <option value="Normal">Normal</option>
                <option value="Elevado">Elevado</option>
                <option value="No Colabora">No Colabora</option>
            </select>
        </div>

        <div class="form-group">
            <label for="pio_tnc_OD">PIO TNC (mmHg)</label>
            <input type="number" step="0.1" min="0" class="form-control" name="pio_tnc_OD" id="pio_tnc_OD" placeholder="Ej: 12.0" required>
        </div>

        <div class="form-group">
            <label for="tratamiento_pio_OD">Tratamiento PIO</label>
            <textarea name="tratamiento_pio_OD" id="tratamiento_pio_OD" class="form-control" rows="3" placeholder="Describa tratamiento"></textarea>
             <div class="botones">
<button type="button" class="btn btn-danger btn-sm btn-start-dictado" data-target="tratamiento_pio_OD">
        <i class="fas fa-microphone"></i>
    </button>
    <button type="button" class="btn btn-primary btn-sm btn-stop-dictado" data-target="tratamiento_pio_OD">
        <i class="fas fa-microphone-slash"></i>
    </button>
    <button type="button" class="btn btn-success btn-sm btn-play-dictado" data-target="tratamiento_pio_OD">
        <i class="fas fa-play"></i>
    </button>
    
    <small class="estado-dictado form-text text-muted">Dictado apagado</small>
        </div>

        <div class="form-group">
            <label for="correlacion_paquimetrica_OD">Correlación Paquimétrica a PIO</label>
            <textarea name="correlacion_paquimetrica_OD" id="correlacion_paquimetrica_OD" class="form-control" rows="3" placeholder="Describa correlación"></textarea>
             <div class="botones">
   <button type="button" class="btn btn-danger btn-sm btn-start-dictado" data-target="correlacion_paquimetrica_OD">
        <i class="fas fa-microphone"></i>
    </button>
    <button type="button" class="btn btn-primary btn-sm btn-stop-dictado" data-target="correlacion_paquimetrica_OD">
        <i class="fas fa-microphone-slash"></i>
    </button>
    <button type="button" class="btn btn-success btn-sm btn-play-dictado" data-target="correlacion_paquimetrica_OD">
        <i class="fas fa-play"></i>
    </button>
        </div>
    </fieldset>

    <!-- Ojo Izquierdo -->
    <fieldset class="border p-3 mb-4">
        <legend class="w-auto px-2">Ojo Izquierdo (OI)</legend>

        <div class="form-group">
            <label for="pio_aplanacion_previa_OI">PIO Aplanación Previa (mmHg)</label>
            <input type="number" step="0.1" min="0" class="form-control" name="pio_aplanacion_previa_OI" id="pio_aplanacion_previa_OI" placeholder="Ej: 14.5" required>
        </div>

        <div class="form-group">
            <label for="pio_tng_previa_OI">PIO TNG Previa (mmHg)</label>
            <input type="number" step="0.1" min="0" class="form-control" name="pio_tng_previa_OI" id="pio_tng_previa_OI" placeholder="Ej: 16.0" required>
        </div>

        <div class="form-group">
            <label for="pio_aplanacion_OI">PIO Aplanación (mmHg)</label>
            <div class="input-group">
                <input type="number" step="0.1" min="0" class="form-control" name="pio_aplanacion_OI" id="pio_aplanacion_OI" placeholder="Ej: 15.2" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <input type="checkbox" id="no_colabora_aplanacion_OI" onchange="toggleInput('no_colabora_aplanacion_OI', 'pio_aplanacion_OI')"> <label for="no_colabora_aplanacion_OI" class="ml-2 mb-0">No Colabora</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="pio_tnc_tipo_OI">Tipo PIO TNC</label>
            <select name="pio_tnc_tipo_OI" id="pio_tnc_tipo_OI" class="form-control" onchange="
                const selected = this.value;
                const input = document.getElementById('pio_tnc_OI');
                input.disabled = (selected === 'No Colabora');
                if(selected === 'No Colabora') input.value = '';
            " required>
                <option value="">Seleccione...</option>
                <option value="Normal">Normal</option>
                <option value="Elevado">Elevado</option>
                <option value="No Colabora">No Colabora</option>
            </select>
        </div>

        <div class="form-group">
            <label for="pio_tnc_OI">PIO TNC (mmHg)</label>
            <input type="number" step="0.1" min="0" class="form-control" name="pio_tnc_OI" id="pio_tnc_OI" placeholder="Ej: 13.5" required>
        </div>

<div class="form-group">
    <label for="tratamiento_pio_OI">Tratamiento PIO</label>
    <textarea name="tratamiento_pio_OI" id="tratamiento_pio_OI" class="form-control" rows="3" placeholder="Describa tratamiento"></textarea>
    
    <button type="button" class="btn btn-danger btn-sm btn-start-dictado" data-target="tratamiento_pio_OI">
        <i class="fas fa-microphone"></i>
    </button>
    <button type="button" class="btn btn-primary btn-sm btn-stop-dictado" data-target="tratamiento_pio_OI">
        <i class="fas fa-microphone-slash"></i>
    </button>
    <button type="button" class="btn btn-success btn-sm btn-play-dictado" data-target="tratamiento_pio_OI">
        <i class="fas fa-play"></i>
    </button>
    
    <small class="estado-dictado form-text text-muted">Dictado apagado</small>
</div>

<div class="form-group">
    <label for="correlacion_paquimetrica_OI">Correlación Paquimétrica a PIO</label>
    <textarea name="correlacion_paquimetrica_OI" id="correlacion_paquimetrica_OI" class="form-control" rows="3" placeholder="Describa correlación"></textarea>
    
    <button type="button" class="btn btn-danger btn-sm btn-start-dictado" data-target="correlacion_paquimetrica_OI">
        <i class="fas fa-microphone"></i>
    </button>
    <button type="button" class="btn btn-primary btn-sm btn-stop-dictado" data-target="correlacion_paquimetrica_OI">
        <i class="fas fa-microphone-slash"></i>
    </button>
    <button type="button" class="btn btn-success btn-sm btn-play-dictado" data-target="correlacion_paquimetrica_OI">
        <i class="fas fa-play"></i>
    </button>
    
    <small class="estado-dictado form-text text-muted">Dictado apagado</small>
</div>

    </fieldset>

    <div class="mt-4 text-center">
    <button type="submit" class="btn btn-primary">Firmar</button>
    <a href="../hospitalizacion/vista_pac_hosp.php" class="btn btn-danger">Cancelar</a>
  </div>
</form>
</div>
</div>
</div>
</div>
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
function toggleInput(checkboxId, inputId) {
    const checkbox = document.getElementById(checkboxId);
    const input = document.getElementById(inputId);
    if (checkbox.checked) {
        input.value = '';
        input.disabled = true;
    } else {
        input.disabled = false;
    }
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

    if (!SpeechRecognition) {
        document.querySelectorAll('.estado-dictado').forEach(el => {
            el.textContent = '❌ Dictado por voz no compatible';
        });
        return;
    }

    let recognition = null;
    let currentTarget = null;
    let currentEstado = null;

    function iniciarDictado(textarea, estadoElement) {
        if (recognition) recognition.abort(); // Detener si ya hay uno activo

        recognition = new SpeechRecognition();
        recognition.lang = 'es-ES';
        recognition.interimResults = true;
        recognition.continuous = true;

        currentTarget = textarea;
        currentEstado = estadoElement;

        recognition.onstart = () => currentEstado.textContent = '🎙️ Dictado en curso...';
        recognition.onend = () => currentEstado.textContent = '⏹️ Dictado detenido';
        recognition.onerror = (e) => currentEstado.textContent = `❌ Error: ${e.error}`;

        recognition.onresult = function (event) {
            let transcript = '';
            for (let i = event.resultIndex; i < event.results.length; ++i) {
                transcript += event.results[i][0].transcript;
            }
            currentTarget.value += transcript;
        };

        recognition.start();
    }

    function detenerDictado() {
        if (recognition) recognition.stop();
    }

    document.querySelectorAll('.btn-start-dictado').forEach(btn => {
        btn.addEventListener('click', () => {
            const textarea = document.getElementById(btn.dataset.target);
            const estado = btn.closest('.form-group').querySelector('.estado-dictado');
            iniciarDictado(textarea, estado);
        });
    });

    document.querySelectorAll('.btn-stop-dictado').forEach(btn => {
        btn.addEventListener('click', () => {
            detenerDictado();
            const estado = btn.closest('.form-group').querySelector('.estado-dictado');
            estado.textContent = '⏹️ Dictado detenido';
        });
    });

    document.querySelectorAll('.btn-play-dictado').forEach(btn => {
        btn.addEventListener('click', () => {
            const textarea = document.getElementById(btn.dataset.target);
            const texto = textarea.value.trim();
            if (!texto) return;

            if (recognition) recognition.abort(); // Detener dictado si está en curso

            const utterance = new SpeechSynthesisUtterance(texto);
            utterance.lang = 'es-MX';
            speechSynthesis.speak(utterance);
        });
    });
});
</script>
