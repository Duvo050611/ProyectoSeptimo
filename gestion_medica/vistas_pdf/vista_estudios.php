<?php
session_start();
include "../../conexionbd.php";
include "../header_medico.php";

if (!isset($_SESSION['hospital']) || !isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Vista Estudios Clínicos</title>
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
        #contenido, #contenido3, #contenido4 {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <button type="button" class="btn btn-danger btn-sm" onclick="history.back()">Regresar...</button>
        <hr>
        <div class="thead text-center" style="background-color: #2b2d7f; color: white; font-size: 22px;">
            <strong>IMPRESIÓN DE ESTUDIOS CLÍNICOS</strong>
        </div>
        <section class="content container-fluid">
            <?php
            $id_atencion = $_SESSION['hospital'];

            // Fetch patient data
            $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.folio, p.tel, p.fecnac, p.tip_san, di.fecha, di.area, di.alta_med, p.sexo, di.alergias 
                        FROM paciente p 
                        JOIN dat_ingreso di ON p.Id_exp = di.Id_exp 
                        WHERE di.id_atencion = ?";
            $stmt_pac = $conexion->prepare($sql_pac);
            $stmt_pac->bind_param("i", $id_atencion);
            $stmt_pac->execute();
            $result_pac = $stmt_pac->get_result();
            if ($row_pac = $result_pac->fetch_assoc()) {
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
            } else {
                echo '<script>window.location.href="../lista_pacientes/lista_pacientes.php";</script>';
                exit();
            }
            $stmt_pac->close();

            // Calculate current date + 12 hours
            $sql_now = "SELECT DATE_ADD(NOW(), INTERVAL 12 HOUR) as dat_now FROM dat_ingreso WHERE id_atencion = ?";
            $stmt_now = $conexion->prepare($sql_now);
            $stmt_now->bind_param("i", $id_atencion);
            $stmt_now->execute();
            $result_now = $stmt_now->get_result();
            $dat_now = $result_now->fetch_assoc()['dat_now'];
            $stmt_now->close();

            // Calculate hospital stay
            $sql_est = "SELECT DATEDIFF(?, fecha) as estancia FROM dat_ingreso WHERE id_atencion = ?";
            $stmt_est = $conexion->prepare($sql_est);
            $stmt_est->bind_param("si", $dat_now, $id_atencion);
            $stmt_est->execute();
            $result_est = $stmt_est->get_result();
            $estancia = $result_est->fetch_assoc()['estancia'];
            $stmt_est->close();

            // Calculate age
            function calculaedad($fechanacimiento) {
                if (!$fechanacimiento || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechanacimiento)) {
                    return 'Edad no disponible';
                }
                $fecha_actual = date("Y-m-d");
                $array_nacimiento = explode("-", $fechanacimiento);
                $array_actual = explode("-", $fecha_actual);
                $anos = $array_actual[0] - $array_nacimiento[0];
                $meses = $array_actual[1] - $array_nacimiento[1];
                $dias = $array_actual[2] - $array_nacimiento[2];

                if ($dias < 0) {
                    $meses--;
                    $dias += date("t", strtotime("$array_actual[0]-$array_actual[1]-01"));
                }
                if ($meses < 0) {
                    $anos--;
                    $meses += 12;
                }

                if ($anos > 0) {
                    return $anos . " años";
                } elseif ($meses > 0) {
                    return $meses . " meses";
                } else {
                    return $dias . " días";
                }
            }
            $edad = calculaedad($pac_fecnac);
            ?>
            <div class="row">
                <div class="col-sm-2">Expediente: <strong><?php echo htmlspecialchars($folio); ?></strong></div>
                <div class="col-sm-6">Paciente: <strong><?php echo htmlspecialchars($pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac); ?></strong></div>
                <div class="col-sm-4">Fecha de ingreso: <strong><?php echo date_format(date_create($pac_fecing), "d/m/Y H:i:s"); ?></strong></div>
            </div>
            <div class="row">
                <div class="col-sm-4">Fecha de nacimiento: <strong><?php echo date_format(date_create($pac_fecnac), "d/m/Y"); ?></strong></div>
                <div class="col-sm-4">Edad: <strong><?php echo htmlspecialchars($edad); ?></strong></div>
                <div class="col-sm-2">Habitación: <strong>
                    <?php
                    $sql_hab = "SELECT num_cama FROM cat_camas WHERE id_atencion = ?";
                    $stmt_hab = $conexion->prepare($sql_hab);
                    $stmt_hab->bind_param("i", $id_atencion);
                    $stmt_hab->execute();
                    $result_hab = $stmt_hab->get_result();
                    echo htmlspecialchars($result_hab->fetch_assoc()['num_cama'] ?? 'N/A');
                    $stmt_hab->close();
                    ?>
                </strong></div>
            </div>
            <div class="row">
                <?php
                $sql_motd = "SELECT diagprob_i FROM dat_nevol WHERE id_atencion = ? ORDER BY diagprob_i ASC LIMIT 1";
                $stmt_motd = $conexion->prepare($sql_motd);
                $stmt_motd->bind_param("i", $id_atencion);
                $stmt_motd->execute();
                $result_motd = $stmt_motd->get_result();
                $d = $result_motd->fetch_assoc()['diagprob_i'] ?? null;
                $stmt_motd->close();

                $sql_mot = "SELECT motivo_atn FROM dat_ingreso WHERE id_atencion = ? ORDER BY motivo_atn ASC LIMIT 1";
                $stmt_mot = $conexion->prepare($sql_mot);
                $stmt_mot->bind_param("i", $id_atencion);
                $stmt_mot->execute();
                $result_mot = $stmt_mot->get_result();
                $m = $result_mot->fetch_assoc()['motivo_atn'] ?? null;
                $stmt_mot->close();

                if ($d) {
                    echo '<div class="col-sm-8">Diagnóstico: <strong>' . htmlspecialchars($d) . '</strong></div>';
                } else {
                    echo '<div class="col-sm-8">Motivo de atención: <strong>' . htmlspecialchars($m) . '</strong></div>';
                }
                ?>
                <div class="col-sm">Días estancia: <strong><?php echo htmlspecialchars($estancia); ?> Días</strong></div>
            </div>
            <div class="row">
                <div class="col-sm-4">Alergias: <strong><?php echo htmlspecialchars($alergias); ?></strong></div>
                <div class="col-sm-4">Estado de salud: <strong>
                    <?php
                    $sql_edo = "SELECT edo_salud FROM dat_ingreso WHERE id_atencion = ? LIMIT 1";
                    $stmt_edo = $conexion->prepare($sql_edo);
                    $stmt_edo->bind_param("i", $id_atencion);
                    $stmt_edo->execute();
                    $result_edo = $stmt_edo->get_result();
                    echo htmlspecialchars($result_edo->fetch_assoc()['edo_salud'] ?? 'N/A');
                    $stmt_edo->close();
                    ?>
                </strong></div>
                <div class="col-sm-3">Tipo de sangre: <strong><?php echo htmlspecialchars($pac_tip_sang); ?></strong></div>
            </div>
            <?php
            $sql_hc = "SELECT peso, talla FROM dat_hclinica WHERE Id_exp = ? ORDER BY id_hc DESC LIMIT 1";
            $stmt_hc = $conexion->prepare($sql_hc);
            $stmt_hc->bind_param("i", $id_exp);
            $stmt_hc->execute();
            $result_hc = $stmt_hc->get_result();
            $peso = 0;
            $talla = 0;
            if ($row_hc = $result_hc->fetch_assoc()) {
                $peso = $row_hc['peso'];
                $talla = $row_hc['talla'];
            }
            $stmt_hc->close();
            ?>
            <div class="row">
                <div class="col-sm-4">Peso: <strong><?php echo htmlspecialchars($peso); ?></strong></div>
                <div class="col-sm-3">Talla: <strong><?php echo htmlspecialchars($talla); ?></strong></div>
            </div>
            <hr>

            <!-- Success or Error Alert -->
            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    ¡Estudio clínico registrado correctamente!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['message']; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
            <?php endif; ?>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <input type="text" class="form-control pull-right" style="width:20%" id="search" placeholder="Buscar...">
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="mytable">
                                <thead class="thead" style="background-color: #2b2d7f; color: white;">
                                    <tr>
                                        <th scope="col">PDF</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Hora</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql_estudios = "SELECT * FROM ocular_estudios WHERE id_atencion = ? ORDER BY fecha_registro DESC";
                                    $stmt_estudios = $conexion->prepare($sql_estudios);
                                    $stmt_estudios->bind_param("i", $id_atencion);
                                    $stmt_estudios->execute();
                                    $result_estudios = $stmt_estudios->get_result();
                                    while ($f = $result_estudios->fetch_assoc()) {
                                        $id_estudio = $f['id_estudio'];
                                        ?>
                                        <tr>
                                            <td>
                                                <a href="../pdf/pdf_estudios.php?id_estudio=<?php echo (int)$id_estudio; ?>&id_exp=<?php echo (int)$id_exp; ?>&id_atencion=<?php echo (int)$id_atencion; ?>" target="_blank">
                                                    <button type="button" class="btn btn-danger"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
                                                </a>
                                            </td>
                                            <td><strong><?php echo date_format(date_create($f['fecha_registro']), "d/m/Y"); ?></strong></td>
                                            <td><strong><?php echo date_format(date_create($f['fecha_registro']), "H:i"); ?> horas</strong></td>
                                        </tr>
                                        <?php
                                    }
                                    $stmt_estudios->close();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
                                </div>
    <footer class="main-footer">
        <?php include "../../template/footer.php"; ?>
    </footer>

    <!-- JavaScript -->
    <script src="../../template/plugins/fastclick/fastclick.min.js"></script>
    <script src="../../template/dist/js/app.min.js"></script>
    <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
    <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $("#search").keyup(function() {
                var value = $(this).val().toLowerCase();
                $("#mytable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>
</body>
</html>