<?php
session_start();
include "../../conexionbd.php";
include "../header_medico.php";

if (!isset($_SESSION['hospital'])) {
    echo '<script type="text/javascript">window.location.href="../../index.php";</script>';
    exit;
}

$id_atencion = $_SESSION['hospital'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>REGISTRO GRÁFICO TRANS-ANESTÉSICO</title>
    <style>
        #contenido, #contenido3, #contenido4 { display: none; }
    </style>
</head>
<body>
    <div class="container">
    <a href="javascript:window.print()"><i class="fa fa-print" aria-hidden="true" alt="IMPRIMIR"></i></a>
    <div class="row" style="background-color:white">
        <?php
        include "../../conexionbd.php";
        $resultado = $conexion->query("SELECT * FROM img_sistema ORDER BY id_simg DESC LIMIT 1") or die($conexion->error);
        while ($f = mysqli_fetch_assoc($resultado)) {
            $img_ipdf = "../../configuracion/admin/img2/{$f['img_ipdf']}";
            $img_cpdf = "../../configuracion/admin/img3/{$f['img_cpdf']}";
            $img_dpdf = "../../configuracion/admin/img4/{$f['img_dpdf']}";
        ?>
            <div class="col-sm-2">
                <br>
                <img src="<?php echo $img_ipdf; ?>" height="110" style="margin-left: 7px;">
            </div>
            <div class="col-sm-4">
                <center>
                    <img src="<?php echo $img_cpdf; ?>" height="100"  style="margin-top: 20px; margin-left: 110px">
                </center>
            </div>
            <div class="col-sm-2">
                <br>
                <img src="<?php echo $img_dpdf; ?>" height="80" style="margin-left: 190px;">
            </div>
        <?php
        }
        $resultado->free();
        ?>
    </div>
    <div style="text-align: center; margin-top: 10px;">
        <span style="font-family: Arial, sans-serif; font-weight: bold; font-size: 15px; color: #282828;">
            GRÁFICO ANESTÉSICO
        </span>
    </div>
</div><p>

    <font size="2">
        <div class="container">
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
                        echo '<script type="text/javascript">window.location.href="../../index.php";</script>';
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
        
    </font>
    <hr>

    <?php
    // Fetch anesthesia record
    $sql_anest = "SELECT tipo_anestesia, llega_quirofano, inicia_anestesia, inicia_cirugia, termina_cirugia, termina_anestesia, pasa_recuperacion, ta, fc, fr, spo2, temp 
                  FROM registro_anestesico 
                  WHERE id_atencion = ? 
                  ORDER BY id_registro_anestesico DESC LIMIT 1";
    $stmt_anest = $conexion->prepare($sql_anest);
    $stmt_anest->bind_param("i", $id_atencion);
    $stmt_anest->execute();
    $result_anest = $stmt_anest->get_result();
    $anest_data = $result_anest->fetch_assoc();
    $stmt_anest->close();
    ?>

    <div class="container">
        <div class="row">
            <div class="col-sm"><br><strong>Tipo de anestesia:</strong></div>
            <div class="col-sm">
                <input value="<?php echo htmlspecialchars($anest_data['tipo_anestesia'] ?? ''); ?>" class="form-control" disabled>
            </div>
            <div class="col-sm">
                Llega quirófano: <input value="<?php echo $anest_data['llega_quirofano'] ? date_format(date_create($anest_data['llega_quirofano']), "d-m-Y H:i:s") : ''; ?>" class="form-control" disabled>
            </div>
            <div class="col-sm">
                Pasa a recuperación: <input value="<?php echo $anest_data['pasa_recuperacion'] ? date_format(date_create($anest_data['pasa_recuperacion']), "d-m-Y H:i:s") : ''; ?>" class="form-control" disabled>
            </div>
        </div>
    </div>
    <hr>
    <div class="container">
        <div class="row">
            <div class="col-sm">
                <strong>Inicio anestesia</strong>
                <input value="<?php echo $anest_data['inicia_anestesia'] ? date_format(date_create($anest_data['inicia_anestesia']), "d-m-Y H:i:s") : ''; ?>" class="form-control" disabled>
            </div>
            <div class="col-sm">
                <strong>Inicio operación</strong>
                <input value="<?php echo $anest_data['inicia_cirugia'] ? date_format(date_create($anest_data['inicia_cirugia']), "d-m-Y H:i:s") : ''; ?>" class="form-control" disabled>
            </div>
            <div class="col-sm">
                <strong>Término operación</strong>
                <input value="<?php echo $anest_data['termina_cirugia'] ? date_format(date_create($anest_data['termina_cirugia']), "d-m-Y H:i:s") : ''; ?>" class="form-control" disabled>
            </div>
            <div class="col-sm">
                <strong>Término anestesia</strong>
                <input value="<?php echo $anest_data['termina_anestesia'] ? date_format(date_create($anest_data['termina_anestesia']), "d-m-Y H:i:s") : ''; ?>" class="form-control" disabled>
            </div>
        </div>
    </div>
    <hr>
    <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
        <center><strong>GRÁFICO / SIGNOS VITALES</strong></center>
    </div>
    <canvas id="grafica"></canvas>
    <script>
        const $grafica = document.querySelector("#grafica");
        const etiquetas = ["1"];
        const presion = {
            label: "Presión arterial",
            data: ["<?php echo htmlspecialchars($anest_data['ta'] ?? ''); ?>"],
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        };
        const frec = {
            label: "Frecuencia cardíaca",
            data: [<?php echo $anest_data['fc'] ?? 0; ?>],
            backgroundColor: 'rgba(255, 159, 64, 0.2)',
            borderColor: 'rgba(255, 159, 64, 1)',
            borderWidth: 1
        };
        const fresp = {
            label: "Frecuencia respiratoria",
            data: [<?php echo $anest_data['fr'] ?? 0; ?>],
            backgroundColor: 'rgba(25, 159, 64, 0.2)',
            borderColor: 'rgba(25, 159, 64, 1)',
            borderWidth: 1
        };
        const sat = {
            label: "Saturación oxígeno",
            data: [<?php echo $anest_data['spo2'] ?? 0; ?>],
            backgroundColor: 'rgba(255, 5, 4, 0.2)',
            borderColor: 'rgba(255, 5, 4, 1)',
            borderWidth: 1
        };
        const temp = {
            label: "Temperatura",
            data: [<?php echo $anest_data['temp'] ?? 0; ?>],
            backgroundColor: 'rgba(155, 125, 224, 0.2)',
            borderColor: 'rgba(155, 125, 224, 1)',
            borderWidth: 1
        };
        new Chart($grafica, {
            type: 'line',
            data: {
                labels: etiquetas,
                datasets: [presion, frec, fresp, sat, temp]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: { beginAtZero: true }
                    }]
                }
            }
        });
    </script>
    <div class="container">
        <div class="row">
            <div class="col-sm-9"></div>
            <div class="col-sm"></div>
            <div class="col-sm"><small><strong>CMSI-7.04</strong></small></div>
        </div>
    </div>
    <hr>
    <center><a href="../notas_medicas/reg_anestesia.php" role="button" class="btn btn-danger">Regresar</a></center><p>
    </div>

    <footer class="main-footer">
        <?php include "../../template/footer.php"; ?>
    </footer>
</body>
</html>
<?php $conexion->close(); ?>
