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

    <h4 class="thead">Exploración NIÑO/BEBÉ</h4>

    <form action="guardar_ninoBebe.php" method="POST">
  <!-- Hidden Inputs -->
  <input type="hidden" name="id_exp" value="<?= htmlspecialchars($pac_id_exp) ?>">
  <input type="hidden" name="id_usua" value="<?= htmlspecialchars($pac_id_usua) ?>">
  <input type="hidden" name="id_atencion" value="<?= htmlspecialchars($id_atencion) ?>">

  <div class="row">
    <!-- Ojo Derecho -->
    <div class="col-md-6">
      <h5>Ojo Derecho (OD)</h5>

      <!-- Reflejo Fotomotor OD -->
      <div class="form-group">
        <label for="reflejo_od">Reflejo Fotomotor</label>
        <div class="input-group">
          <input type="text" class="form-control" id="reflejo_od" name="reflejo_od" placeholder="Ej: Presente, Ausente">
          <div class="input-group-append">
            <button type="button" class="btn btn-danger btn-sm btn-start-dictado" data-target="reflejo_od"><i class="fas fa-microphone"></i></button>
            <button type="button" class="btn btn-primary btn-sm btn-stop-dictado" data-target="reflejo_od"><i class="fas fa-microphone-slash"></i></button>
            <button type="button" class="btn btn-success btn-sm btn-play-dictado" data-target="reflejo_od"><i class="fas fa-play"></i></button>
          </div>
        </div>
        <small class="estado-dictado form-text text-muted" id="estado-reflejo_od">Dictado apagado</small>
      </div>

      <div class="form-group">
        <label for="eje_visual_od">Eje Visual</label>
        <input type="text" class="form-control" id="eje_visual_od" name="eje_visual_od" placeholder="Ej: 0°, 15° exo">
      </div>

      <div class="form-group">
        <label for="fijacion_od">Fijación</label>
        <select class="form-control" id="fijacion_od" name="fijacion_od">
          <option value="">Seleccione una opción</option>
          <option value="Central">Central</option>
          <option value="Excéntrica">Excéntrica</option>
          <option value="Inestable">Inestable</option>
          <option value="Ausente">Ausente</option>
        </select>
      </div>

      <div class="form-group">
        <label for="esquiascopia_od">Esquiascopia</label>
        <input type="text" class="form-control" id="esquiascopia_od" name="esquiascopia_od" placeholder="Ej: +1.00 -0.50 x 180">
      </div>

      <!-- Posición Compensadora OD -->
      <div class="form-group">
        <label for="posicion_od">Posición Compensadora</label>
        <div class="input-group">
          <input type="text" class="form-control" id="posicion_od" name="posicion_od" placeholder="Ej: Inclinación de cabeza a la derecha">
          <div class="input-group-append">
            <button type="button" class="btn btn-danger btn-sm btn-start-dictado" data-target="posicion_od"><i class="fas fa-microphone"></i></button>
            <button type="button" class="btn btn-primary btn-sm btn-stop-dictado" data-target="posicion_od"><i class="fas fa-microphone-slash"></i></button>
            <button type="button" class="btn btn-success btn-sm btn-play-dictado" data-target="posicion_od"><i class="fas fa-play"></i></button>
          </div>
        </div>
        <small class="estado-dictado form-text text-muted" id="estado-posicion_od">Dictado apagado</small>
      </div>
    </div>

    <!-- Ojo Izquierdo -->
    <div class="col-md-6">
      <h5>Ojo Izquierdo (OI)</h5>

      <!-- Reflejo Fotomotor OI -->
      <div class="form-group">
        <label for="reflejo_oi">Reflejo Fotomotor</label>
        <div class="input-group">
          <input type="text" class="form-control" id="reflejo_oi" name="reflejo_oi" placeholder="Ej: Presente, Ausente">
          <div class="input-group-append">
            <button type="button" class="btn btn-danger btn-sm btn-start-dictado" data-target="reflejo_oi"><i class="fas fa-microphone"></i></button>
            <button type="button" class="btn btn-primary btn-sm btn-stop-dictado" data-target="reflejo_oi"><i class="fas fa-microphone-slash"></i></button>
            <button type="button" class="btn btn-success btn-sm btn-play-dictado" data-target="reflejo_oi"><i class="fas fa-play"></i></button>
          </div>
        </div>
        <small class="estado-dictado form-text text-muted" id="estado-reflejo_oi">Dictado apagado</small>
      </div>

      <div class="form-group">
        <label for="eje_visual_oi">Eje Visual</label>
        <input type="text" class="form-control" id="eje_visual_oi" name="eje_visual_oi" placeholder="Ej: 0°, 10° eso">
      </div>

      <div class="form-group">
        <label for="fijacion_oi">Fijación</label>
        <select class="form-control" id="fijacion_oi" name="fijacion_oi">
          <option value="">Seleccione una opción</option>
          <option value="Central">Central</option>
          <option value="Excéntrica">Excéntrica</option>
          <option value="Inestable">Inestable</option>
          <option value="Ausente">Ausente</option>
        </select>
      </div>

      <div class="form-group">
        <label for="esquiascopia_oi">Esquiascopia</label>
        <input type="text" class="form-control" id="esquiascopia_oi" name="esquiascopia_oi" placeholder="Ej: +0.50 -1.00 x 90">
      </div>

      <!-- Posición Compensadora OI -->
      <div class="form-group">
        <label for="posicion_oi">Posición Compensadora</label>
        <div class="input-group">
          <input type="text" class="form-control" id="posicion_oi" name="posicion_oi" placeholder="Ej: Cabeza inclinada hacia izquierda">
          <div class="input-group-append">
            <button type="button" class="btn btn-danger btn-sm btn-start-dictado" data-target="posicion_oi"><i class="fas fa-microphone"></i></button>
            <button type="button" class="btn btn-primary btn-sm btn-stop-dictado" data-target="posicion_oi"><i class="fas fa-microphone-slash"></i></button>
            <button type="button" class="btn btn-success btn-sm btn-play-dictado" data-target="posicion_oi"><i class="fas fa-play"></i></button>
          </div>
        </div>
        <small class="estado-dictado form-text text-muted" id="estado-posicion_oi">Dictado apagado</small>
      </div>
    </div>
  </div>

  <div class="mt-4 text-center">
    <button type="submit" class="btn btn-primary">Firmar</button>
    <a href="../hospitalizacion/vista_pac_hosp.php" class="btn btn-danger">Cancelar</a>
  </div>
</form>

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
    <script>
  const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
  recognition.lang = 'es-ES';
  recognition.continuous = false;
  recognition.interimResults = false;

  let currentTargetInput = null;

  document.querySelectorAll('.btn-start-dictado').forEach(btn => {
    btn.addEventListener('click', () => {
      const inputId = btn.getAttribute('data-target');
      currentTargetInput = document.getElementById(inputId);
      const estado = document.getElementById('estado-' + inputId);
      recognition.start();
      estado.textContent = 'Escuchando...';
    });
  });

  document.querySelectorAll('.btn-stop-dictado').forEach(btn => {
    btn.addEventListener('click', () => {
      recognition.stop();
      const inputId = btn.getAttribute('data-target');
      const estado = document.getElementById('estado-' + inputId);
      estado.textContent = 'Dictado detenido';
    });
  });

  document.querySelectorAll('.btn-play-dictado').forEach(btn => {
    btn.addEventListener('click', () => {
      const inputId = btn.getAttribute('data-target');
      const texto = document.getElementById(inputId).value;
      const estado = document.getElementById('estado-' + inputId);
      if (texto.trim() !== '') {
        const speech = new SpeechSynthesisUtterance(texto);
        speech.lang = 'es-ES';
        window.speechSynthesis.speak(speech);
        estado.textContent = 'Reproduciendo dictado';
      }
    });
  });

  recognition.onresult = function(event) {
    const result = event.results[0][0].transcript;
    if (currentTargetInput) {
      currentTargetInput.value = result;
      document.getElementById('estado-' + currentTargetInput.id).textContent = 'Texto insertado por dictado';
    }
  };

  recognition.onerror = function(event) {
    if (currentTargetInput) {
      document.getElementById('estado-' + currentTargetInput.id).textContent = 'Error en el dictado: ' + event.error;
    }
  };
</script>

</body>
</html>
