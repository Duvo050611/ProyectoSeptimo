<?php
session_start();
include "../../conexionbd.php";
include "../header_enfermera.php";

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
            hr.new4 {
            border: 1px solid red;
        }
        .card-container {
    display: flex;
    gap: 20px;
}
.card {
    flex: 1;
    padding: 15px;
    border: 1px solid #ccc;
    border-radius: 10px;
    background: #f9f9f9;
}
.card h4 {
    margin-bottom: 15px;
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
 
<form action="insertar_cir_seg.php" method="POST">
<input type="hidden" name="id_exp" value="<?= htmlspecialchars($id_exp) ?>">
    <input type="hidden" name="id_usua" value="<?= htmlspecialchars($pac_id_usua) ?>">
    <input type="hidden" name="id_atencion" value="<?= htmlspecialchars($id_atencion) ?>">

    <div class="card-container">
        <!-- Sección 1 -->
        <div class="card">
            <h4>Con el enfermero y el anestesista</h4>

            <div class="checkbox-group">
                <strong>¿Ha confirmado el paciente su identidad, el sitio quirúrgico, el procedimiento y su consentimiento?</strong><br>
                <input type="checkbox" name="confirmacion_identidad" value="Sí"> Sí
            </div>
    <hr>

            <div class="checkbox-group">
                <strong>¿Se ha marcado el sitio quirúrgico?</strong><br>
                <input type="checkbox" name="sitio_marcado[]" value="Sí"> Sí<br>
                <input type="checkbox" name="sitio_marcado[]" value="No procede"> No procede
            </div>
    <hr>

            <div class="checkbox-group">
                <strong>¿Se ha completado la comprobación de los aparatos de anestesia y la medicación anestésica?</strong><br>
                <input type="checkbox" name="verificacion_anestesia" value="Sí"> Sí
            </div>
    <hr>

            <div class="checkbox-group">
                <strong>¿Se ha colocado el pulsioximetro al paciente y funciona?</strong><br>
                <input type="checkbox" name="pulsioximetro" value="Sí"> Sí
            </div>
    <hr>

            <div class="checkbox-group">
                <strong>¿Tiene el paciente alergias conocidas?</strong><br>
                <input type="checkbox" name="alergias[]" value="No"> No<br>
                <input type="checkbox" name="alergias[]" value="Sí"> Sí
            </div>

            <div class="checkbox-group">
                <strong>¿Tiene el paciente vía aérea difícil / riesgo de aspiración?</strong><br>
                <input type="checkbox" name="via_aerea_dificil[]" value="No"> No<br>
                <input type="checkbox" name="via_aerea_dificil[]" value="Sí, y hay materiales y equipos / ayuda disponible"> Sí, y hay materiales y equipos / ayuda disponible
            </div>

            <div class="checkbox-group">
                <strong>¿Riesgo de hemorragia &gt; 500 ml (7 ml/kg en niños)?</strong><br>
                <input type="checkbox" name="riesgo_hemorragia[]" value="No"> No<br>
                <input type="checkbox" name="riesgo_hemorragia[]" value="Sí, y se ha previsto la disponibilidad de líquidos y dos vías IV o centrales"> Sí, y se ha previsto la disponibilidad de líquidos y dos vías IV o centrales
            </div>
        </div>

        <!-- Sección 2 -->
        <div class="card">
    <h4>Con el enfermero, el anestesista y el cirujano</h4>

    <div class="checkbox-group">
        <input type="hidden" name="miembros_presentados" value="0">
        <label>
            <input type="checkbox" name="miembros_presentados" value="1">
            <strong>Confirmar que todos los miembros del equipo se hayan presentado por su nombre</strong>
        </label>
    </div>
    <hr>

    <div class="checkbox-group">
        <input type="hidden" name="confirmacion_identidad_equipo" value="0">
        <label>
            <input type="checkbox" name="confirmacion_identidad_equipo" value="1">
            <strong>Confirmar la identidad del paciente, el sitio quirúrgico y el procedimiento</strong>
        </label>
    </div>
    <hr>

    <div class="checkbox-group">
        <strong>¿Se ha administrado profilaxis antibiótica en los últimos 60 minutos?</strong><br>
        <input type="hidden" name="profilaxis_antibiotica_si" value="0">
        <input type="checkbox" name="profilaxis_antibiotica_si" value="1"> Sí<br>

        <input type="hidden" name="profilaxis_antibiotica_np" value="0">
        <input type="checkbox" name="profilaxis_antibiotica_np" value="1"> No procede
    </div>

    <hr>
    <strong>Previsión de eventos críticos</strong>

    <div class="checkbox-group">
      <input type="hidden" name="problemas_instrumental" value="0">
        <label>
                <input type="checkbox" name="pasos_criticos" value="1">
        <strong>Cirujano: ¿Cuáles serán los pasos críticos o no sistematizados?</strong> 
      </label>
    </div>

    <div class="checkbox-group">
      <input type="hidden" name="duracion_operacion" value="0">
        <label>
          <input type="checkbox" name="duracion_operacion" value="1">
          <strong>Cirujano: ¿Cuánto durará la operación?</strong>
        </label>
    </div>

    <div class="checkbox-group">
            <input type="hidden" name="perdida_sangre" value="0">
        <label>
          <input type="checkbox" name="perdida_sangre" value="1">
            <strong>Cirujano: ¿Cuál es la pérdida de sangre prevista?</strong>        
      </label>
    </div>

    <div class="checkbox-group">
            <input type="hidden" name="problemas_paciente" value="0">
        <label>
          <input type="checkbox" name="problemas_paciente" value="1">
              <strong>Anestesista: ¿Presenta el paciente algún problema específico?</strong>
          </label>
    </div>

    <div class="checkbox-group">
        <input type="hidden" name="esterilidad_confirmada" value="0">
        <label>
            <input type="checkbox" name="esterilidad_confirmada" value="1">
            <strong>¿Se ha confirmado la esterilidad (con resultados de los indicadores)?</strong>
        </label>
    </div>

    <div class="checkbox-group">
      <input type="hidden" name="problemas_instrumental" value="0">
        <label>
                <input type="checkbox" name="problemas_instrumental" value="1">
          <strong>¿Hay dudas o problemas relacionados con el instrumental y los equipos?</strong>
        </label>
    </div>

    <div class="checkbox-group">
        <strong>¿Pueden visualizarse las imágenes diagnósticas esenciales?</strong><br>
        <input type="hidden" name="imagenes_visibles_si" value="0">
        <input type="checkbox" name="imagenes_visibles_si" value="1"> Sí<br>

        <input type="hidden" name="imagenes_visibles_np" value="0">
        <input type="checkbox" name="imagenes_visibles_np" value="1"> No procede
    </div>
</div>


        <!-- Sección 3 -->
        <div class="card">
            <h4>Antes de salir del quirófano</h4>

            <div class="checkbox-group">
                    <input type="hidden" name="nombre_procedimiento" value="0">
                <label>
                  <input type="checkbox" name="nombre_procedimiento" value="1">
                  <strong>El enfermero confirma verbalmente: El nombre del procedimiento</strong>
                </label>
            </div>

            <div class="checkbox-group">
                    <input type="hidden" name="recuento_instrumental" value="0">
                    <label>
                      <input type="checkbox" name="recuento_instrumental" value="1">
                      <strong>El recuento de instrumentos, gasas y agujas</strong>
                    </label>
            </div>

            <div class="checkbox-group">
              <input type="hidden" name="etiquetado_muestras" value="0">
                    <label>
                    <input type="checkbox" name="etiquetado_muestras" value="1">
                <strong>El etiquetado de las muestras (lectura de la etiqueta en voz alta, incluido el nombre del paciente)</strong>
                    </label>
            </div>

            <div class="form-group">
              <input type="hidden" name="problemas_instrumental_final" value="0">
                <label>
                <input type="checkbox" name="problemas_instrumental_final" value="1">
                  <strong>Si hay problemas que resolver relacionados con el instrumental y los equipos</strong>
                </label>
            </div>

            <div class="form-group">
              <input type="hidden" name="aspectos_recuperacion" value="0">
                <label>
                  <strong>Cirujano, anestesista y enfermero:</strong><br>
                  <input type="checkbox" name="aspectos_recuperacion" value="1">
                ¿Cuáles son los aspectos críticos de la recuperación y el tratamiento del paciente?
              </label>
            </div>
        </div>
    </div>

    <br>
            <button type="submit" class="btn btn-primary">FIRMAR</button>
        <a href="../../template/select_pac_enf.php" class="btn btn-secondary">Cancelar</a>
</form>
