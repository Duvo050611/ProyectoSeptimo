<?php
// Start session and ensure no output before this
session_start();

// Check if session is properly initialized
if (session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION['login'])) {
    header("Location: ../../index.php"); // Redirect to login page if not logged in
    exit();
}

include "../../conexionbd.php";
include "../header_medico.php";

// Validate user role
if (!in_array($_SESSION['login']['id_rol'], [4, 5, 10])) {
    header("Location: ../../index.php");
    exit();
}

// Validate hospital session variable
if (!isset($_SESSION['hospital']) || !is_numeric($_SESSION['hospital'])) {
    header("Location: ./consulta_urgencias.php");
    exit();
}

$id_atencion = (int)$_SESSION['hospital'];
$usuario = $_SESSION['login'];

// Fetch patient data
$resultado = $conexion->query("SELECT paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
    FROM paciente 
    INNER JOIN dat_ingreso ON paciente.Id_exp = dat_ingreso.Id_exp 
    WHERE id_atencion = $id_atencion") or die($conexion->error);

if (mysqli_num_rows($resultado) > 0) {
    $f = mysqli_fetch_row($resultado);
} else {
    header("Location: ./consulta_urgencias.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <!-- <title>Exámenes de Laboratorio y Gabinete - Instituto de Enfermedades Oculares</title> -->
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

    <?php
    function calculaedad($fechanacimiento) {
        list($ano, $mes, $dia) = explode("-", $fechanacimiento);
        $ano_diferencia = date("Y") - $ano;
        $mes_diferencia = date("m") - $mes;
        $dia_diferencia = date("d") - $dia;
        if ($ano_diferencia > 0)
            return $ano_diferencia . ' AÑOS';
        elseif ($mes_diferencia > 0 || $ano_diferencia < 0)
            return $mes_diferencia . ' MESES';
        elseif ($dia_diferencia > 0 || $mes_diferencia < 0 || $ano_diferencia < 0)
            return $dia_diferencia . ' DÍAS';
    }
    ?>

    <title>NOTA DE EGRESO</title>
</head>

<body>
    <div class="col-sm-12">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 24px;">
                        <center><strong>RESULTADOS DE ESTUDIOS</strong></center>
                    </div>

                    <?php
                // Fetch patient details
                $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.folio, p.tel, p.fecnac, p.tip_san, di.fecha, di.area, di.alta_med, p.sexo, di.alergias 
                            FROM paciente p 
                            INNER JOIN dat_ingreso di ON p.Id_exp = di.Id_exp 
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
                    header("Location: ./consulta_urgencias.php");
                    exit();
                }
                $stmt_pac->close();

                // Fetch current date + 12 hours
                $sql_now = "SELECT DATE_ADD(NOW(), INTERVAL 12 HOUR) as dat_now FROM dat_ingreso WHERE id_atencion = ? LIMIT 1";
                $stmt_now = $conexion->prepare($sql_now);
                $stmt_now->bind_param("i", $id_atencion);
                $stmt_now->execute();
                $result_now = $stmt_now->get_result();
                $dat_now = ($row_now = $result_now->fetch_assoc()) ? $row_now['dat_now'] : date('Y-m-d H:i:s');
                $stmt_now->close();

                // Calculate estancia
                $sql_est = "SELECT DATEDIFF(?, fecha) as estancia FROM dat_ingreso WHERE id_atencion = ? LIMIT 1";
                $stmt_est = $conexion->prepare($sql_est);
                $stmt_est->bind_param("si", $dat_now, $id_atencion);
                $stmt_est->execute();
                $result_est = $stmt_est->get_result();
                $estancia = ($row_est = $result_est->fetch_assoc()) ? $row_est['estancia'] : 0;
                $stmt_est->close();
                ?>

                    <div class="row">
                        <div class="col-sm-2">
                            Expediente: <strong><?php echo htmlspecialchars($folio); ?></strong>
                        </div>
                        <div class="col-sm-6">
                            Paciente:
                            <strong><?php echo htmlspecialchars($pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac); ?></strong>
                        </div>
                        <?php $date = date_create($pac_fecing); ?>
                        <div class="col-sm-4">
                            Fecha de ingreso: <strong><?php echo date_format($date, "d/m/Y H:i:s"); ?></strong>
                        </div>
                    </div>

                    <div class="row">
                        <?php $date1 = date_create($pac_fecnac); ?>
                        <div class="col-sm-4">
                            Fecha de nacimiento: <strong><?php echo date_format($date1, "d/m/Y"); ?></strong>
                        </div>
                        <div class="col-sm-4">
                            Edad: <strong><?php echo calculaedad($pac_fecnac); ?></strong>
                        </div>
                        <div class="col-sm-2">
                            Habitación: <strong>
                                <?php
                            $sql_hab = "SELECT num_cama FROM cat_camas WHERE id_atencion = ?";
                            $stmt_hab = $conexion->prepare($sql_hab);
                            $stmt_hab->bind_param("i", $id_atencion);
                            $stmt_hab->execute();
                            $result_hab = $stmt_hab->get_result();
                            echo ($row_hab = $result_hab->fetch_assoc()) ? htmlspecialchars($row_hab['num_cama']) : 'N/A';
                            $stmt_hab->close();
                            ?>
                            </strong>
                        </div>
                    </div>

                    <div class="row">
                        <?php
                    $d = '';
                    $sql_motd = "SELECT diagprob_i FROM dat_nevol WHERE id_atencion = ? ORDER BY diagprob_i ASC LIMIT 1";
                    $stmt_motd = $conexion->prepare($sql_motd);
                    $stmt_motd->bind_param("i", $id_atencion);
                    $stmt_motd->execute();
                    $result_motd = $stmt_motd->get_result();
                    if ($row_motd = $result_motd->fetch_assoc()) {
                        $d = $row_motd['diagprob_i'];
                    }
                    $stmt_motd->close();

                    $m = '';
                    $sql_mot = "SELECT motivo_atn FROM dat_ingreso WHERE id_atencion = ? ORDER BY motivo_atn ASC LIMIT 1";
                    $stmt_mot = $conexion->prepare($sql_mot);
                    $stmt_mot->bind_param("i", $id_atencion);
                    $stmt_mot->execute();
                    $result_mot = $stmt_mot->get_result();
                    if ($row_mot = $result_mot->fetch_assoc()) {
                        $m = $row_mot['motivo_atn'];
                    }
                    $stmt_mot->close();

                    if ($d) {
                        echo '<div class="col-sm-8"> Diagnóstico: <strong>' . htmlspecialchars($d) . '</strong></div>';
                    } else {
                        echo '<div class="col-sm-8"> Motivo de atención: <strong>' . htmlspecialchars($m) . '</strong></div>';
                    }
                    ?>
                        <div class="col-sm">
                            Días estancia: <strong><?php echo htmlspecialchars($estancia); ?> Dias</strong>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            Alergias: <strong><?php echo htmlspecialchars($alergias); ?></strong>
                        </div>
                        <div class="col-sm-4">
                            Estado de salud: <strong>
                                <?php
                            $sql_edo = "SELECT edo_salud FROM dat_ingreso WHERE id_atencion = ? LIMIT 1";
                            $stmt_edo = $conexion->prepare($sql_edo);
                            $stmt_edo->bind_param("i", $id_atencion);
                            $stmt_edo->execute();
                            $result_edo = $stmt_edo->get_result();
                            echo ($row_edo = $result_edo->fetch_assoc()) ? htmlspecialchars($row_edo['edo_salud']) : 'N/A';
                            $stmt_edo->close();
                            ?>
                            </strong>
                        </div>
                        <div class="col-sm-3">
                            Tipo de sangre: <strong><?php echo htmlspecialchars($pac_tip_sang); ?></strong>
                        </div>
                    </div>

                    <?php
                $sql_edo = "SELECT peso, talla FROM dat_hclinica WHERE Id_exp = ? ORDER BY id_hc DESC LIMIT 1";
                $stmt_edo = $conexion->prepare($sql_edo);
                $stmt_edo->bind_param("i", $id_exp);
                $stmt_edo->execute();
                $result_edo = $stmt_edo->get_result();
                $peso = 0;
                $talla = 0;
                if ($row_edo = $result_edo->fetch_assoc()) {
                    $peso = $row_edo['peso'];
                    $talla = $row_edo['talla'];
                }
                $stmt_edo->close();
                ?>

                    <div class="row">
                        <div class="col-sm-4">
                            Peso: <strong><?php echo htmlspecialchars($peso); ?></strong>
                        </div>
                        <div class="col-sm-3">
                            Talla: <strong><?php echo htmlspecialchars($talla); ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="container">
            <!-- Estudios de Laboratorio -->
            <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 24px;">
                <center><strong>ESTUDIOS DE LABORATORIO</strong></center>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="mytable">
                    <thead class="thead" style="background-color: #2b2d7f; color:white;">
                        <tr>
                            <th>Fecha de solicitud</th>
                            <th>Solicitante</th>
                            <th>Estudio(s)</th>
                            <th>Resultado(s)</th>
                            <th>Fecha de resultado(s)</th>
                            <th>Atendió solicitud</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                $id_usua_log = $usuario['id_usua'];

                $query = "SELECT n.*, u.papell AS medico_papell, u.sapell AS medico_sapell, r.papell AS resp_papell, r.sapell AS resp_sapell 
                          FROM notificaciones_labo n 
                          JOIN reg_usuarios u ON n.id_usua = u.id_usua 
                          JOIN reg_usuarios r ON n.id_usua_resul = r.id_usua 
                          WHERE n.realizado = 'SI' AND n.id_atencion = ? 
                          ORDER BY n.fecha_resul DESC";
                $stmt = $conexion->prepare($query);
                $stmt->bind_param("i", $id_atencion);
                $stmt->execute();
                $result = $stmt->get_result();
                $no = 1;

                while ($row = $result->fetch_assoc()) {
                    $query_pac = "SELECT p.papell, p.sapell, p.nom_pac 
                                  FROM dat_ingreso d 
                                  JOIN paciente p ON d.Id_exp = p.Id_exp 
                                  WHERE d.id_atencion = ?";
                    $stmt_pac = $conexion->prepare($query_pac);
                    $stmt_pac->bind_param("i", $id_atencion);
                    $stmt_pac->execute();
                    $result_pac = $stmt_pac->get_result();
                    $pac = '';
                    if ($row_pac = $result_pac->fetch_assoc()) {
                        $pac = $row_pac['papell'] . ' ' . $row_pac['sapell'] . ' ' . $row_pac['nom_pac'];
                    }
                    $stmt_pac->close();

                    $medico = $row['medico_papell'] . ' ' . $row['medico_sapell'];
                    $resp = $row['resp_papell'] . ' ' . $row['resp_sapell'];
                    $fecha_ord = date_create($row['fecha_ord']);
                    $fecha_res = date_create($row['fecha_resul']);

                    echo '<tr>'
                        . '<td>' . date_format($fecha_ord, 'd/m/Y H:i a') . '</td>'
                        . '<td>' . htmlspecialchars($medico) . '</td>'
                        . '<td>';

                    // Split sol_estudios by comma or semicolon
                    $estudios = preg_split('/[,;]/', $row['sol_estudios'], -1, PREG_SPLIT_NO_EMPTY);
                    if (!empty($estudios)) {
                        echo '<ul style="margin: 0; padding-left: 20px;">';
                        foreach ($estudios as $estudio) {
                            $estudio = trim($estudio);
                            if ($estudio) {
                                echo '<li><a style="color: black;><i aria-hidden="true"></i> ' . htmlspecialchars($estudio) . '</a></li>';
                            }
                        }
                        echo '</ul>';
                    } else {
                        echo '<a style="color: deepskyblue;" href="ver_pdf.php?not_id=' . (int)$row['not_id'] . '"><i class="fa fa-eye" aria-hidden="true"></i> ' . htmlspecialchars($row['sol_estudios']) . '</a>';
                    }

                    // Display det_labo separately if not empty
                    if (!empty($row['det_labo'])) {
                        echo '<br><strong>Anotaciones:</strong><br>' . nl2br(htmlspecialchars($row['det_labo']));
                    }

                    echo '</td>'
                        . '<td class="fondo" style="color:white;"><center>'
                        . '<a href="ver_pdf.php?not_id=' . (int)$row['not_id'] . '" title="Ver resultados" class="btn btn-danger"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>'
                        . '</center></td>'
                        . '<td>' . date_format($fecha_res, 'd/m/Y H:i a') . '</td>'
                        . '<td>' . htmlspecialchars($resp) . '</td>'
                        . '</tr>';
                    $no++;
                }
                $stmt->close();
                ?>
                    </tbody>
                </table>
            </div>

            <!-- Estudios de Gabinete -->
            <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 24px;">
                <center><strong>ESTUDIOS DE GABINETE</strong></center>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="mytable">
                    <thead class="thead" style="background-color: #2b2d7f; color:white;">
                        <tr>
                            <th>Fecha de solicitud</th>
                            <th>Solicitante</th>
                            <th>Estudio(s)</th>
                            <th>Resultado(s)</th>
                            <th>Fecha de resultado(s)</th>
                            <th>Atendió solicitud</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT n.*, u.papell AS medico_papell, u.sapell AS medico_sapell, r.papell AS resp_papell, r.sapell AS resp_sapell 
                                FROM notificaciones_gabinete n 
                                JOIN reg_usuarios u ON n.id_usua = u.id_usua 
                                JOIN reg_usuarios r ON n.id_usua_resul = r.id_usua 
                                WHERE n.realizado = 'SI' AND n.id_atencion = ? 
                                ORDER BY n.fecha_resultado DESC";
                        $stmt = $conexion->prepare($query);
                        $stmt->bind_param("i", $id_atencion);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $no = 1;

                        while ($row = $result->fetch_assoc()) {
                            $query_pac = "SELECT p.papell, p.sapell, p.nom_pac 
                                        FROM dat_ingreso d 
                                        JOIN paciente p ON d.Id_exp = p.Id_exp 
                                        WHERE d.id_atencion = ?";
                            $stmt_pac = $conexion->prepare($query_pac);
                            $stmt_pac->bind_param("i", $id_atencion);
                            $stmt_pac->execute();
                            $result_pac = $stmt_pac->get_result();
                            $pac = '';
                            if ($row_pac = $result_pac->fetch_assoc()) {
                                $pac = $row_pac['papell'] . ' ' . $row_pac['sapell'] . ' ' . $row_pac['nom_pac'];
                            }
                            $stmt_pac->close();

                            $medico = $row['medico_papell'] . ' ' . $row['medico_sapell'];
                            $resp = $row['resp_papell'] . ' ' . $row['resp_sapell'];
                            $fecha_ord = date_create($row['fecha_ord']);
                            $fecha_res = date_create($row['fecha_resultado']);

                            echo '<tr>'
                                . '<td>' . date_format($fecha_ord, 'd/m/Y H:i a') . '</td>'
                                . '<td>' . htmlspecialchars($medico) . '</td>'
                                . '<td>';

                            // Split sol_estudios by comma or semicolon
                            $estudios = preg_split('/[,;]/', $row['sol_estudios'], -1, PREG_SPLIT_NO_EMPTY);
                            if (!empty($estudios)) {
                                echo '<ul style="margin: 0; padding-left: 20px;">';
                                foreach ($estudios as $estudio) {
                                    $estudio = trim($estudio);
                                    if ($estudio) {
                                        echo '<li><a style="color: black;"><i aria-hidden="true"></i> ' . htmlspecialchars($estudio) . '</a></li>';
                                    }
                                }
                                echo '</ul>';
                            } else {
                                echo '<a style="color: deepskyblue;" href="ver_pdf_gab.php?not_id=' . (int)$row['id_not_gabinete'] . '"><i class="fa fa-eye" aria-hidden="true"></i> ' . htmlspecialchars($row['sol_estudios']) . '</a>';
                            }

                            // Display det_gab separately if not empty
                            if (!empty($row['det_gab'])) {
                                echo '<br><strong>Anotaciones:</strong><br>' . nl2br(htmlspecialchars($row['det_gab']));
                            }

                            echo '</td>'
                                . '<td class="fondo" style="color:white;"><center>'
                                . '<a href="ver_pdf_gab.php?not_id=' . (int)$row['id_not_gabinete'] . '" title="Ver resultados" class="btn btn-danger"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>'
                                . '</center></td>'
                                . '<td>' . date_format($fecha_res, 'd/m/Y H:i a') . '</td>'
                                . '<td>' . htmlspecialchars($resp) . '</td>'
                                . '</tr>';
                            $no++;
                        }
                        $stmt->close();
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Estudios de Patología -->
            <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 24px;">
                <center><strong>ESTUDIOS DE PATOLOGÍA</strong></center>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="mytable">
                    <thead class="thead" style="background-color: #2b2d7f; color:white;">
                        <tr>
                            <th>Fecha de solicitud</th>
                            <th>Solicitante</th>
                            <th>Estudio(s)</th>
                            <th>Resultado(s)</th>
                            <th>Fecha de resultado(s)</th>
                            <th>Atendió solicitud</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                $query = "SELECT n.*, u.papell AS medico_papell, u.sapell AS medico_sapell, r.papell AS resp_papell, r.sapell AS resp_sapell, c.serv_desc 
                          FROM notificaciones_pato n 
                          JOIN reg_usuarios u ON n.id_usua = u.id_usua 
                          JOIN reg_usuarios r ON n.id_usua_resul = r.id_usua 
                          JOIN cat_servicios c ON c.serv_desc = n.dispo_p 
                          WHERE n.realizado = 'SI' AND n.id_atencion = ? 
                          ORDER BY n.fecha_resul DESC";
                $stmt = $conexion->prepare($query);
                $stmt->bind_param("i", $id_atencion);
                $stmt->execute();
                $result = $stmt->get_result();
                $no = 1;

                while ($row = $result->fetch_assoc()) {
                    $id_notp = $row['id_notp'];
                    $medico = $row['medico_papell'] . ' ' . $row['medico_sapell'];
                    $resp = $row['resp_papell'] . ' ' . $row['resp_sapell'];
                    $pac_imagen = $row['serv_desc'];
                    $fecha_ord = date_create($row['fecha_ord']);
                    $fecha_res = date_create($row['fecha_resul']);

                    $query_pac = "SELECT p.papell, p.sapell, p.nom_pac 
                                  FROM dat_ingreso d 
                                  JOIN paciente p ON d.Id_exp = p.Id_exp 
                                  WHERE d.id_atencion = ?";
                    $stmt_pac = $conexion->prepare($query_pac);
                    $stmt_pac->bind_param("i", $id_atencion);
                    $stmt_pac->execute();
                    $result_pac = $stmt_pac->get_result();
                    $pac = '';
                    if ($row_pac = $result_pac->fetch_assoc()) {
                        $pac = $row_pac['papell'] . ' ' . $row_pac['sapell'] . ' ' . $row_pac['nom_pac'];
                    }
                    $stmt_pac->close();

                    echo '<tr>'
                        . '<td>' . date_format($fecha_ord, 'd/m/Y H:i a') . '</td>'
                        . '<td>' . htmlspecialchars($medico) . '</td>'
                        . '<td>' . htmlspecialchars($pac_imagen) . '</td>'
                        . '<td>' . htmlspecialchars($row['realizado']) . '</td>'
                        . '<td class="fondo" style="color:white;"><center>'
                        . '<a href="../../sauxiliares/Patologia/verpdf.php?id_notp=' . (int)$id_notp . '&id_atencion=' . (int)$row['id_atencion'] . '" title="Ver resultado" class="btn btn-danger"><i class="fa fa-eye" aria-hidden="true"></i></a>'
                        . '</center></td>'
                        . '<td>' . date_format($fecha_res, 'd/m/Y H:i a') . '</td>'
                        . '<td>' . htmlspecialchars($resp) . '</td>'
                        . '</tr>';
                    $no++;
                }
                $stmt->close();
                ?>
                    </tbody>
                </table>
            </div>
            </div>
            </div>
        </div>

        <footer class="main-footer">
            <?php include "../../template/footer.php"; ?>
        </footer>

        <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
        <script src="../../template/dist/js/app.min.js"></script>
        <script>
        document.oncontextmenu = function() {
            return false;
        }
        $(document).ready(function() {
            $('#mibuscador').select2();
        });
        </script>
</body>

</html>
<?php $conexion->close(); ?>