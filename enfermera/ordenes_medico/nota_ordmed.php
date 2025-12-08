<?php
session_start();

include '../../conexionbd.php';
$conexion = ConexionBD::getInstancia()->getConexion();
include("../header_enfermera.php");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordenes verbales</title>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
          integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" type="text/css"/>

    <!-- JavaScript -->
    <script src="jquery-3.1.1.min.js"></script>
    <script src="js/select2.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script src="../../js/jquery-3.3.1.min.js"></script>
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/popper.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js"></script>
</head>
<body>

<section class="content container-fluid">

    <?php
    if (isset($_SESSION['pac'])) {
        $id_atencion = $_SESSION['pac'];

        $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.tel, p.fecnac, p.tip_san, di.fecha, di.area, di.alta_med, p.sexo, di.alergias, p.folio 
                    FROM paciente p, dat_ingreso di 
                    WHERE p.Id_exp=di.Id_exp AND di.id_atencion = $id_atencion";

        $result_pac = $conexion->query($sql_pac);

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
            $alergias = $row_pac['alergias'];
            $folio = $row_pac['folio'];
        }

        $sql_pac = "SELECT * FROM dat_ingreso WHERE id_atencion = $id_atencion";
        $result_pac = $conexion->query($sql_pac);

        while ($row_pac = $result_pac->fetch_assoc()) {
            $fingreso = $row_pac['fecha'];
            $fegreso = $row_pac['fec_egreso'];
            $alta_med = $row_pac['alta_med'];
            $alta_adm = $row_pac['alta_adm'];
            $activo = $row_pac['activo'];
            $valida = $row_pac['valida'];
        }

        if ($alta_med == 'SI' && $alta_adm == 'SI' && $activo == 'NO' && $valida == 'SI') {
            $sql_est = "SELECT DATEDIFF('$fegreso', '$fingreso') as estancia FROM dat_ingreso WHERE id_atencion = $id_atencion";
            $result_est = $conexion->query($sql_est);
            while ($row_est = $result_est->fetch_assoc()) {
                $estancia = $row_est['estancia'];
            }
        } else {
            $sql_now = "SELECT DATE_ADD(NOW(), INTERVAL 12 HOUR) as dat_now FROM dat_ingreso WHERE id_atencion = $id_atencion";
            $result_now = $conexion->query($sql_now);
            while ($row_now = $result_now->fetch_assoc()) {
                $dat_now = $row_now['dat_now'];
            }

            $sql_est = "SELECT DATEDIFF('$dat_now', fecha) as estancia FROM dat_ingreso WHERE id_atencion = $id_atencion";
            $result_est = $conexion->query($sql_est);
            while ($row_est = $result_est->fetch_assoc()) {
                $estancia = $row_est['estancia'];
            }
        }

        function bisiesto($anio_actual) {
            return checkdate(2, 29, $anio_actual);
        }

        $fecha_actual = date("Y-m-d");
        $fecha_nac = $pac_fecnac;
        $array_nacimiento = explode("-", $fecha_nac);
        $array_actual = explode("-", $fecha_actual);

        $anos = $array_actual[0] - $array_nacimiento[0];
        $meses = $array_actual[1] - $array_nacimiento[1];
        $dias = $array_actual[2] - $array_nacimiento[2];

        if ($dias < 0) {
            --$meses;
            $dias_mes_anterior = 31;
            switch ($array_actual[1]) {
                case 2: $dias_mes_anterior = 31; break;
                case 3: $dias_mes_anterior = bisiesto($array_actual[0]) ? 29 : 28; break;
                case 4: $dias_mes_anterior = 31; break;
                case 5: $dias_mes_anterior = 30; break;
                case 6: $dias_mes_anterior = 31; break;
                case 7: $dias_mes_anterior = 30; break;
                case 8: $dias_mes_anterior = 31; break;
                case 9: $dias_mes_anterior = 31; break;
                case 10: $dias_mes_anterior = 30; break;
                case 11: $dias_mes_anterior = 31; break;
                case 12: $dias_mes_anterior = 30; break;
            }
            $dias = $dias + $dias_mes_anterior;
        }

        if ($meses < 0) {
            --$anos;
            $meses = $meses + 12;
        }
        ?>

        <div class="container">
            <button type="button" class="btn btn-danger" onclick="history.back()">Regresar...</button>
            <hr>
            <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
                <strong><center>REGISTRO DE INDICACIONES MÉDICAS VERBALES</center></strong>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        Expediente: <strong><?php echo $folio ?></strong>
                        Paciente: <strong><?php echo $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac ?></strong>
                    </div>
                    <div class="col-sm">
                        Área: <strong><?php echo $area ?></strong>
                    </div>
                    <div class="col-sm">
                        Fecha de Ingreso: <strong><?php echo date_format(date_create($pac_fecing), "d/m/Y") ?></strong>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm">
                        Fecha de nacimiento: <strong><?php echo date_format(date_create($pac_fecnac), "d/m/Y") ?></strong>
                    </div>
                    <div class="col-sm">
                        Tipo de sangre: <strong><?php echo $pac_tip_sang ?></strong>
                    </div>
                    <div class="col-sm">
                        Habitación: <strong>
                            <?php
                            $sql_hab = "SELECT num_cama FROM cat_camas WHERE id_atencion = $id_atencion";
                            $result_hab = $conexion->query($sql_hab);
                            while ($row_hab = $result_hab->fetch_assoc()) {
                                echo $row_hab['num_cama'];
                            }
                            ?>
                        </strong>
                    </div>
                    <div class="col-sm">
                        Tiempo estancia: <strong><?php echo $estancia ?> Días</strong>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        Edad: <strong>
                            <?php
                            if ($anos > 0) {
                                echo $anos . " Años";
                            } elseif ($anos <= 0 && $meses > 0) {
                                echo $meses . " Meses";
                            } elseif ($anos <= 0 && $meses <= 0 && $dias > 0) {
                                echo $dias . " Días";
                            }
                            ?>
                        </strong>
                    </div>
                    <div class="col-sm-3">
                        Peso: <strong>
                            <?php
                            $sql_vit = "SELECT peso FROM dat_hclinica WHERE Id_exp = $id_exp ORDER BY id_hc DESC LIMIT 1";
                            $result_vit = $conexion->query($sql_vit);
                            $peso = 0;
                            while ($row_vit = $result_vit->fetch_assoc()) {
                                $peso = $row_vit['peso'];
                            }
                            echo $peso;
                            ?>
                        </strong>
                    </div>
                    <div class="col-sm">
                        Talla: <strong>
                            <?php
                            $sql_vitt = "SELECT talla FROM dat_hclinica WHERE Id_exp = $id_exp ORDER BY id_hc DESC LIMIT 1";
                            $result_vitt = $conexion->query($sql_vitt);
                            $talla = 0;
                            while ($row_vitt = $result_vitt->fetch_assoc()) {
                                $talla = $row_vitt['talla'];
                            }
                            echo $talla;
                            ?>
                        </strong>
                    </div>
                    <div class="col-sm">
                        Género: <strong><?php echo $pac_sexo ?></strong>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        Alergias: <strong><?php echo $alergias ?></strong>
                    </div>
                    <div class="col-sm-6">
                        Estado de Salud: <strong>
                            <?php
                            $sql_edo = "SELECT edo_salud FROM dat_ingreso WHERE id_atencion = $id_atencion ORDER BY edo_salud ASC LIMIT 1";
                            $result_edo = $conexion->query($sql_edo);
                            while ($row_edo = $result_edo->fetch_assoc()) {
                                echo $row_edo['edo_salud'];
                            }
                            ?>
                        </strong>
                    </div>
                    <div class="col-sm">
                        Aseguradora: <strong></strong>
                    </div>
                </div>

                <div class="col-sm-4">
                    <?php
                    $d = "";
                    $sql_motd = "SELECT diagprob_i FROM dat_nevol WHERE id_atencion = $id_atencion ORDER BY diagprob_i ASC LIMIT 1";
                    $result_motd = $conexion->query($sql_motd);
                    while ($row_motd = $result_motd->fetch_assoc()) {
                        $d = $row_motd['diagprob_i'];
                    }

                    $m = "";
                    $sql_mot = "SELECT motivo_atn FROM dat_ingreso WHERE id_atencion = $id_atencion ORDER BY motivo_atn ASC LIMIT 1";
                    $result_mot = $conexion->query($sql_mot);
                    while ($row_mot = $result_mot->fetch_assoc()) {
                        $m = $row_mot['motivo_atn'];
                    }

                    if ($d != null) {
                        echo 'Diagnóstico: <strong>' . $d . '</strong>';
                    } else {
                        echo 'Motivo de atención: <strong>' . $m . '</strong>';
                    }
                    ?>
                </div>
            </div>

            <br>
            <form action="insertar_ordenes_med.php" method="POST">
                <?php
                $resultado5 = $conexion->query("SELECT * FROM signos_vitales WHERE id_atencion = " . $_SESSION['pac'] . " ORDER BY id_sig DESC LIMIT 1") or die($conexion->error);
                $atencion = null;
                while ($f5 = mysqli_fetch_array($resultado5)) {
                    $atencion = $f5['id_sig'];
                }
                ?>

                <?php if (isset($atencion)) : ?>
                    <?php
                    $resultado5 = $conexion->query("SELECT * FROM signos_vitales WHERE id_atencion = " . $_SESSION['pac'] . " ORDER BY id_sig DESC LIMIT 1") or die($conexion->error);
                    while ($f5 = mysqli_fetch_array($resultado5)) :
                        ?>
                        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 18px;">
                            <center><strong>SIGNOS VITALES</strong></center>
                        </div>

                        <div class="container">
                            <div class="row">
                                <div class="col-sm-2">
                                    <center>Presión arterial:</center>
                                    <div class="row">
                                        <div class="col">
                                            <input type="text" class="form-control" value="<?php echo $f5['p_sistol']; ?>" disabled>
                                        </div> /
                                        <div class="col">
                                            <input type="text" class="form-control" value="<?php echo $f5['p_diastol']; ?>" disabled>
                                        </div>
                                    </div> mmHG / mmHG
                                </div>
                                <div class="col-sm-2">
                                    Frecuencia cardiaca:<input type="text" class="form-control" value="<?php echo $f5['fcard']; ?>" disabled> Latidos por minuto
                                </div>
                                <div class="col-sm-3">
                                    Frecuencia respiratoria:<input type="text" class="form-control" value="<?php echo $f5['fresp']; ?>" disabled> Respiraciones por minuto
                                </div>
                                <div class="col-sm-2">
                                    Temperatura:<input type="text" class="form-control" value="<?php echo $f5['temper']; ?>" disabled>°C
                                </div>
                                <div class="col-sm-3">
                                    Saturación de oxígeno:<input type="text" class="form-control" value="<?php echo $f5['satoxi']; ?>" disabled>%
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else : ?>
                    <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 18px;">
                        <center><strong>SIGNOS VITALES</strong></center>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-2">
                                <center>Presión arterial:</center>
                                <div class="row">
                                    <div class="col"><input type="text" class="form-control" name="p_sistol"></div> /
                                    <div class="col"><input type="text" class="form-control" name="p_diastol"></div>
                                </div>mmHG / mmHG
                            </div>
                            <div class="col-sm-2">
                                Frecuencia cardiaca:<input type="text" class="form-control" name="fcard"> Latidos por minuto
                            </div>
                            <div class="col-sm-3">
                                Frecuencia respiratoria:<input type="text" class="form-control" name="fresp"> Respiraciones por minuto
                            </div>
                            <div class="col-sm-2">
                                Temperatura:<input type="text" class="form-control" name="temper">°C
                            </div>
                            <div class="col-sm-2">
                                Saturación oxígeno:<input type="text" class="form-control" name="satoxi">%
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <hr>

                <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 18px;">
                    <center><strong>PRESCRIPCIÓN Y ÓRDENES: PREPARACIÓN DE CASOS QUIRÚRGICOS, DIETAS, ETC.</strong></center>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <strong><label><br>1.- Dieta: <button type="button" class="btn btn-success btn-sm" id="play"><i class="fas fa-play"></i></button></label></strong>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group"><br>
                            <select class="form-control" name="dieta" required id="dieta">
                                <option value="">Seleccionar dieta</option>
                                <?php
                                $sql_d = "SELECT DISTINCT id_dieta, dieta FROM cat_dietas WHERE dieta_activo='SI' ORDER BY dieta ASC";
                                $result_d = $conexion->query($sql_d);
                                while ($row_d = $result_d->fetch_assoc()) {
                                    echo "<option value='" . $row_d['dieta'] . "'>" . $row_d['dieta'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            Detalle de la Dieta: <button type="button" class="btn btn-success btn-sm" id="playdd"><i class="fas fa-play"></i></button>
                            <input type="text" name="det_dieta" class="form-control" id="txtdetdieta">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-3">
                        <strong><label><br>2.- Cuidados generales:
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="btnStartRecord"><i class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="btnStopRecord"><i class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="playcuid"><i class="fas fa-play"></i></button>
                                </div>
                            </label></strong>
                    </div>
                    <div class="col-9"><br>
                        <div class="form-group">
                            <textarea class="form-control" id="texto" name="observ_be" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-3">
                        <strong><label>4.- Medicamentos:
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="medg"><i class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="toss"><i class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="playmed"><i class="fas fa-play"></i></button>
                                </div>
                            </label></strong>
                    </div>
                    <div class="col-9">
                        <div class="form-group">
                            <textarea class="form-control" id="txtcae" name="med_med" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-3">
                        <strong><label>5.- Soluciones:
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="slg"><i class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="ucs"><i class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="playsoluciones"><i class="fas fa-play"></i></button>
                                </div>
                            </label></strong>
                    </div>
                    <div class="col-9">
                        <div class="form-group">
                            <textarea class="form-control" id="txtsn" name="soluciones" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-2">
                        <center><strong><label><br>6.- Estudios Laboratorio: <button type="button" class="btn btn-success btn-sm" id="pla7"><i class="fas fa-play"></i></button></label></strong></center>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group"><br><br>
                            <select id="l1" name="l1[]" multiple="multiple" class="form-control" required>
                                <option value="NINGUNO" selected>NINGUNO</option>
                                <?php
                                $sql = "SELECT * FROM cat_servicios WHERE tipo = 1 AND serv_activo = 'SI'";
                                $result_serv = $conexion->query($sql);
                                while ($row_serv = $result_serv->fetch_assoc()) {
                                    echo "<option value='" . $row_serv['serv_desc'] . "'>" . $row_serv['serv_desc'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        Detalle estudios laboratorio
                        <div class="botones">
                            <button type="button" class="btn btn-danger btn-sm" id="detalleg"><i class="fas fa-microphone"></i></button>
                            <button type="button" class="btn btn-primary btn-sm" id="labos"><i class="fas fa-microphone-slash"></i></button>
                            <button type="button" class="btn btn-success btn-sm" id="pla8"><i class="fas fa-play"></i></button>
                        </div><br>
                        <textarea class="form-control" name="detalle_lab" id="txtl" rows="5"></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm">
                        <center><strong><label><br>7.- Estudios imagenología: <button type="button" class="btn btn-success btn-sm" id="pla9"><i class="fas fa-play"></i></button></label></strong></center>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group"><br>
                            <select id="a1" name="a1[]" multiple="multiple" class="form-control" required>
                                <option value="NINGUNO" selected>NINGUNO</option>
                                <?php
                                $sql_serv = "SELECT * FROM cat_servicios WHERE tipo = 2 AND serv_activo = 'SI'";
                                $result_serv = $conexion->query($sql_serv);
                                while ($row_serv = $result_serv->fetch_assoc()) {
                                    echo "<option value='" . $row_serv['serv_desc'] . "'>" . $row_serv['serv_desc'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-5"><br><br>
                        Detalle estudios imagenología:
                        <input type="text" class="form-control" name="det_imagen">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-2">
                        <center><strong><label><br>8.- Estudios patología: <button type="button" class="btn btn-success btn-sm" id="pla10"><i class="fas fa-play"></i></button></label></strong></center>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group"><br>
                            <select id="p1" name="p1[]" multiple="multiple" class="form-control" required>
                                <option value="NINGUNO" selected>NINGUNO</option>
                                <?php
                                $sql_serv = "SELECT * FROM cat_servicios WHERE tipo = 6 AND serv_activo = 'SI'";
                                $result_serv = $conexion->query($sql_serv);
                                while ($row_serv = $result_serv->fetch_assoc()) {
                                    echo "<option value='" . $row_serv['serv_desc'] . "'>" . $row_serv['serv_desc'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-5"><br>
                        Detalle estudios patología: <button type="button" class="btn btn-success btn-sm" id="pla11"><i class="fas fa-play"></i></button><br>
                        <input type="text" class="form-control" name="det_pato" id="tt1">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-2">
                        <center><strong><label><br>9.- Solicitud transfusión sanguínea: <button type="button" class="btn btn-success btn-sm" id="pla12"><i class="fas fa-play"></i></button></label></strong></center>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group"><br><br>
                            <select id="s1" name="s1[]" multiple="multiple" class="form-control" required>
                                <option value="NINGUNO" selected>NINGUNO</option>
                                <?php
                                $sql_serv = "SELECT * FROM cat_servicios WHERE tipo = 5 AND serv_activo = 'SI'";
                                $result_serv = $conexion->query($sql_serv);
                                while ($row_serv = $result_serv->fetch_assoc()) {
                                    echo "<option value='" . $row_serv['serv_desc'] . "'>" . $row_serv['serv_desc'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        Detalle de transfusión sanguínea
                        <div class="botones">
                            <button type="button" class="btn btn-danger btn-sm" id="sang"><i class="fas fa-microphone"></i></button>
                            <button type="button" class="btn btn-primary btn-sm" id="stopsa"><i class="fas fa-microphone-slash"></i></button>
                            <button type="button" class="btn btn-success btn-sm" id="pla13"><i class="fas fa-play"></i></button>
                        </div><br>
                        <textarea class="form-control" id="txtstr" name="det_sang" rows="5"></textarea>
                    </div>
                </div>

                <hr>

                <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 18px;">
                    <center><strong>PROCEDIMIENTOS EN MEDICINA DE TRATAMIENTO</strong></center>
                </div>

                <div class="row">
                    <div class="col-3">
                        <strong><label>Diálisis:</label></strong>
                    </div>
                    <div class="col-9">
                        <div class="form-group">
                            <textarea class="form-control" name="dialisis" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-3">
                        <strong><label>Fisioterapía:</label></strong>
                    </div>
                    <div class="col-9">
                        <div class="form-group">
                            <textarea class="form-control" name="fisio" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-3">
                        <strong><label>Inhaloterapia:
                                <div class="botones">
                                    <button type="button" class="btn btn-danger btn-sm" id="inhg"><i class="fas fa-microphone"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" id="iast"><i class="fas fa-microphone-slash"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" id="playinha"><i class="fas fa-play"></i></button>
                                </div>
                            </label></strong>
                    </div>
                    <div class="col-9">
                        <div class="form-group">
                            <textarea class="form-control" id="txti" name="cuid_gen" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-3">
                        <strong><label>Rehabilitación:</label></strong>
                    </div>
                    <div class="col-9">
                        <div class="form-group">
                            <textarea class="form-control" name="reha" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col align-self-start">
                        <strong>Nombre del Médico que ordena: <button type="button" class="btn btn-success btn-sm" id="playnommed"><i class="fas fa-play"></i></button></strong>
                        <input type="text" name="med" id="txtmm" class="form-control" required>
                    </div>
                    <div class="col align-self-start">
                        <strong>Nombre de la Enfermera Testigo: <button type="button" class="btn btn-success btn-sm" id="playenf"><i class="fas fa-play"></i></button></strong>
                        <input type="text" id="txtenff" name="enf" class="form-control" required>
                    </div>
                </div>

                <hr>

                <div class="container">
                    <div class="row">
                        <div class="col align-self-start"></div>
                        <button type="submit" class="btn btn-primary">Firmar</button> &nbsp;
                        <button type="button" class="btn btn-danger" onclick="history.back()">Cancelar</button>
                        <div class="col align-self-end"></div>
                    </div>
                </div>

                <br><br>
            </form>

        </div>

        <?php
    } else {
        echo '<script type="text/javascript">window.location.href="../../template/select_pac_enf.php";</script>';
    }
    ?>

</section>

<footer class="main-footer">
    <?php include("../../template/footer.php"); ?>
</footer>

<!-- Scripts de reconocimiento de voz y text-to-speech -->
<script type="text/javascript">
    // Función genérica para lectura de texto
    function leerTexto(texto) {
        const speech = new SpeechSynthesisUtterance();
        speech.text = texto;
        speech.volume = 1;
        speech.rate = 1;
        speech.pitch = 0;
        speech.lang = 'es-ES';
        window.speechSynthesis.speak(speech);
    }

    // Verificar compatibilidad del navegador
    if (!('webkitSpeechRecognition' in window)) {
        console.warn('El reconocimiento de voz no está disponible en este navegador');
    }

    // Script para Dieta
    const dietaSelect = document.getElementById('dieta');
    const btnPlayDieta = document.getElementById('play');
    if (btnPlayDieta) {
        btnPlayDieta.addEventListener('click', () => {
            leerTexto(dietaSelect.value);
        });
    }

    // Script para Detalle de Dieta
    const txtdetdieta = document.getElementById('txtdetdieta');
    const btnPlayDietaDet = document.getElementById('playdd');
    if (btnPlayDietaDet) {
        btnPlayDietaDet.addEventListener('click', () => {
            leerTexto(txtdetdieta.value);
        });
    }

    // Script para Cuidados Generales (con reconocimiento de voz)
    const btnStartRecord = document.getElementById('btnStartRecord');
    const btnStopRecord = document.getElementById('btnStopRecord');
    const texto = document.getElementById('texto');
    const btnPlayCuid = document.getElementById('playcuid');

    if ('webkitSpeechRecognition' in window) {
        let recognition = new webkitSpeechRecognition();
        recognition.lang = "es-ES";
        recognition.continuous = true;
        recognition.interimResults = false;

        recognition.onresult = (event) => {
            const results = event.results;
            const frase = results[results.length - 1][0].transcript;
            texto.value += frase + ' ';
        };

        if (btnStartRecord) {
            btnStartRecord.addEventListener('click', () => {
                recognition.start();
            });
        }

        if (btnStopRecord) {
            btnStopRecord.addEventListener('click', () => {
                recognition.abort();
            });
        }
    }

    if (btnPlayCuid) {
        btnPlayCuid.addEventListener('click', () => {
            leerTexto(texto.value);
        });
    }

    // Script para Medicamentos (con reconocimiento de voz)
    const medg = document.getElementById('medg');
    const toss = document.getElementById('toss');
    const txtcae = document.getElementById('txtcae');
    const btnPlayMed = document.getElementById('playmed');

    if ('webkitSpeechRecognition' in window) {
        let rm = new webkitSpeechRecognition();
        rm.lang = "es-ES";
        rm.continuous = true;
        rm.interimResults = false;

        rm.onresult = (event) => {
            const results = event.results;
            const frase = results[results.length - 1][0].transcript;
            txtcae.value += frase + ' ';
        };

        if (medg) {
            medg.addEventListener('click', () => {
                rm.start();
            });
        }

        if (toss) {
            toss.addEventListener('click', () => {
                rm.abort();
            });
        }
    }

    if (btnPlayMed) {
        btnPlayMed.addEventListener('click', () => {
            leerTexto(txtcae.value);
        });
    }

    // Script para Soluciones (con reconocimiento de voz)
    const slg = document.getElementById('slg');
    const ucs = document.getElementById('ucs');
    const txtsn = document.getElementById('txtsn');
    const btnPlaySol = document.getElementById('playsoluciones');

    if ('webkitSpeechRecognition' in window) {
        let rs = new webkitSpeechRecognition();
        rs.lang = "es-ES";
        rs.continuous = true;
        rs.interimResults = false;

        rs.onresult = (event) => {
            const results = event.results;
            const frase = results[results.length - 1][0].transcript;
            txtsn.value += frase + ' ';
        };

        if (slg) {
            slg.addEventListener('click', () => {
                rs.start();
            });
        }

        if (ucs) {
            ucs.addEventListener('click', () => {
                rs.abort();
            });
        }
    }

    if (btnPlaySol) {
        btnPlaySol.addEventListener('click', () => {
            leerTexto(txtsn.value);
        });
    }

    // Script para Estudios Laboratorio
    const l1 = document.getElementById('l1');
    const btnPlayLab = document.getElementById('pla7');
    if (btnPlayLab) {
        btnPlayLab.addEventListener('click', () => {
            const selectedOptions = Array.from(l1.selectedOptions).map(opt => opt.value).join(', ');
            leerTexto(selectedOptions);
        });
    }

    // Script para Detalle Laboratorio (con reconocimiento de voz)
    const detalleg = document.getElementById('detalleg');
    const labos = document.getElementById('labos');
    const txtl = document.getElementById('txtl');
    const btnPlayLabDet = document.getElementById('pla8');

    if ('webkitSpeechRecognition' in window) {
        let relav = new webkitSpeechRecognition();
        relav.lang = "es-ES";
        relav.continuous = true;
        relav.interimResults = false;

        relav.onresult = (event) => {
            const results = event.results;
            const frase = results[results.length - 1][0].transcript;
            txtl.value += frase + ' ';
        };

        if (detalleg) {
            detalleg.addEventListener('click', () => {
                relav.start();
            });
        }

        if (labos) {
            labos.addEventListener('click', () => {
                relav.abort();
            });
        }
    }

    if (btnPlayLabDet) {
        btnPlayLabDet.addEventListener('click', () => {
            leerTexto(txtl.value);
        });
    }

    // Script para Estudios Imagenología
    const a1 = document.getElementById('a1');
    const btnPlayImg = document.getElementById('pla9');
    if (btnPlayImg) {
        btnPlayImg.addEventListener('click', () => {
            const selectedOptions = Array.from(a1.selectedOptions).map(opt => opt.value).join(', ');
            leerTexto(selectedOptions);
        });
    }

    // Script para Estudios Patología
    const p1 = document.getElementById('p1');
    const btnPlayPat = document.getElementById('pla10');
    if (btnPlayPat) {
        btnPlayPat.addEventListener('click', () => {
            const selectedOptions = Array.from(p1.selectedOptions).map(opt => opt.value).join(', ');
            leerTexto(selectedOptions);
        });
    }

    // Script para Detalle Patología
    const tt1 = document.getElementById('tt1');
    const btnPlayPatDet = document.getElementById('pla11');
    if (btnPlayPatDet) {
        btnPlayPatDet.addEventListener('click', () => {
            leerTexto(tt1.value);
        });
    }

    // Script para Solicitud Transfusión
    const s1 = document.getElementById('s1');
    const btnPlaySang = document.getElementById('pla12');
    if (btnPlaySang) {
        btnPlaySang.addEventListener('click', () => {
            const selectedOptions = Array.from(s1.selectedOptions).map(opt => opt.value).join(', ');
            leerTexto(selectedOptions);
        });
    }

    // Script para Detalle Transfusión (con reconocimiento de voz)
    const sang = document.getElementById('sang');
    const stopsa = document.getElementById('stopsa');
    const txtstr = document.getElementById('txtstr');
    const btnPlaySangDet = document.getElementById('pla13');

    if ('webkitSpeechRecognition' in window) {
        let reddts = new webkitSpeechRecognition();
        reddts.lang = "es-ES";
        reddts.continuous = true;
        reddts.interimResults = false;

        reddts.onresult = (event) => {
            const results = event.results;
            const frase = results[results.length - 1][0].transcript;
            txtstr.value += frase + ' ';
        };

        if (sang) {
            sang.addEventListener('click', () => {
                reddts.start();
            });
        }

        if (stopsa) {
            stopsa.addEventListener('click', () => {
                reddts.abort();
            });
        }
    }

    if (btnPlaySangDet) {
        btnPlaySangDet.addEventListener('click', () => {
            leerTexto(txtstr.value);
        });
    }

    // Script para Inhaloterapia (con reconocimiento de voz)
    const inhg = document.getElementById('inhg');
    const iast = document.getElementById('iast');
    const txti = document.getElementById('txti');
    const btnPlayInha = document.getElementById('playinha');

    if ('webkitSpeechRecognition' in window) {
        let rh = new webkitSpeechRecognition();
        rh.lang = "es-ES";
        rh.continuous = true;
        rh.interimResults = false;

        rh.onresult = (event) => {
            const results = event.results;
            const frase = results[results.length - 1][0].transcript;
            txti.value += frase + ' ';
        };

        if (inhg) {
            inhg.addEventListener('click', () => {
                rh.start();
            });
        }

        if (iast) {
            iast.addEventListener('click', () => {
                rh.abort();
            });
        }
    }

    if (btnPlayInha) {
        btnPlayInha.addEventListener('click', () => {
            leerTexto(txti.value);
        });
    }

    // Script para Nombre del Médico
    const txtmm = document.getElementById('txtmm');
    const btnPlayMed2 = document.getElementById('playnommed');
    if (btnPlayMed2) {
        btnPlayMed2.addEventListener('click', () => {
            leerTexto(txtmm.value);
        });
    }

    // Script para Nombre de Enfermera
    const txtenff = document.getElementById('txtenff');
    const btnPlayEnf = document.getElementById('playenf');
    if (btnPlayEnf) {
        btnPlayEnf.addEventListener('click', () => {
            leerTexto(txtenff.value);
        });
    }

    // Inicializar multiselect
    $(document).ready(function() {
        $('#l1, #a1, #p1, #s1').multiselect({
            nonSelectedText: 'Selecciona servicio(s)',
            includeSelectAllOption: false,
            buttonWidth: 300,
            maxHeight: 250,
            enableFiltering: true,
            dropUp: false,
            enableCaseInsensitiveFiltering: true
        });

        $('#mibuscador').select2();
    });

    // Deshabilitar clic derecho
    document.oncontextmenu = function() {
        return false;
    }
</script>

<!-- AdminLTE App -->
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>

</body>
</html>