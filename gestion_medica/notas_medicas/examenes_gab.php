<?php
session_start();
include "../../conexionbd.php";
if (!isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}
include("../header_medico.php");

// Fetch exams from the catalog
$sql_exams = "SELECT id_examen, nombre_examen FROM cat_examenes_gabinete WHERE activo = 'SI' ORDER BY nombre_examen";
$result_exams = $conexion->query($sql_exams);
$exams = [];
while ($row = $result_exams->fetch_assoc()) {
    $exams[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>EXÁMENES DE GABINETE</title>
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
    <style>
        .modal-lg { max-width: 70% !important; }
        .botones { margin-bottom: 5px; }
        .thead { background-color: #2b2d7f; color: white; font-size: 22px; padding: 10px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="thead"><strong><center>HISTORIA CLÍNICA</center></strong></div>
                <?php
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

                    $sql_hclinica = "SELECT peso, talla FROM dat_hclinica WHERE Id_exp = ? ORDER BY id_hc DESC LIMIT 1";
                    $stmt = $conexion->prepare($sql_hclinica);
                    $stmt->bind_param("i", $id_exp);
                    $stmt->execute();
                    $result_hclinica = $stmt->get_result();
                    $peso = 0;
                    $talla = 0;
                    while ($row_hclinica = $result_hclinica->fetch_assoc()) {
                        $peso = $row_hclinica['peso'] ?? 0;
                        $talla = $row_hclinica['talla'] ?? 0;
                    }
                    $stmt->close();

                    $sql_doc = "SELECT id_usua FROM receta WHERE id_atencion = ? ORDER BY fecha_r_hosp DESC LIMIT 1";
                    $stmt = $conexion->prepare($sql_doc);
                    $stmt->bind_param("i", $id_atencion);
                    $stmt->execute();
                    $result_doc = $stmt->get_result();
                    $id_usua = $result_doc->fetch_assoc()['id_usua'] ?? null;
                    $stmt->close();

                    if ($id_usua) {
                        $sql_med = "SELECT pre, nombre, papell, sapell FROM reg_usuarios WHERE id_usua = ?";
                        $stmt = $conexion->prepare($sql_med);
                        $stmt->bind_param("i", $id_usua);
                        $stmt->execute();
                        $result_med = $stmt->get_result();
                        $row_med = $result_med->fetch_assoc();
                        $medico = $row_med['pre'] . ". " . $row_med['papell'] . " " . $row_med['sapell'];
                        $stmt->close();
                    } else {
                        $medico = "Médico no asignado";
                    }
                } else {
                    echo '<script type="text/javascript">window.location.href="../lista_pacientes/lista_pacientes.php";</script>';
                }
                ?>
                <?php if (isset($_GET['success']) && $_GET['success'] == 1) { ?>
                <div class="alert alert-success">Exámenes guardados exitosamente.</div>
                <?php } ?>
                <?php if (isset($_GET['error'])) { ?>
                <div class="alert alert-danger">Error al guardar los exámenes: <?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php } ?>
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
        <div class="thead"><strong><center>EXÁMENES DE GABINETE</center></strong></div>
        <form action="insertar_examenes_gab.php" method="POST">
            <div class="accordion mt-3" id="examAccordion">
                <div class="card">
                    <div class="card-header" id="headingGab">
                        <h2 class="mb-0">
                            <button class="btn btn-link text-dark" type="button" data-toggle="collapse"
                                    data-target="#collapseGab" aria-expanded="true" aria-controls="collapseGab">
                                Exámenes de Gabinete
                            </button>
                        </h2>
                    </div>
                    <div id="collapseGab" class="collapse show" aria-labelledby="headingGab" data-parent="#examAccordion">
                        <div class="card-body">
                            <div class="form-group">
                                <?php foreach ($exams as $exam) { ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="examenes[<?php echo $exam['id_examen']; ?>]" id="examen_<?php echo $exam['id_examen']; ?>" value="1">
                                        <label class="form-check-label" for="examen_<?php echo $exam['id_examen']; ?>">
                                            <?php echo htmlspecialchars($exam['nombre_examen']); ?>
                                        </label>
                                    </div>
                                <?php } ?>
                                <div class="form-group">
                                    <label for="otros_gabinete">Otros:</label>
                                    <textarea class="form-control" name="otros_gabinete" id="otros_gabinete" rows="2"></textarea>
                                </div>
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
    <footer class="main-footer">
        <?php include("../../template/footer.php"); ?>
    </footer>
</body>
</html>
<?php $conexion->close(); ?>