<?php
session_start();
include "../../conexionbd.php";
include ("../header_medico.php");
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>DocumentExámenes de Laboratorio y Gabinete - Instituto de Enfermedades Oculares</title>

    <!-- Select2 CSS -->
    <link rel="stylesheet" type="text/css" href="css/select2.css">

    <!-- FontAwesome -->
    <link href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <!-- Bootstrap 4.5 CSS (usar sólo uno, aquí no incluiste el CSS pero lo ideal sería incluirlo) -->
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

    <!-- Tus scripts adicionales -->
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/main.js"></script>

    <script>
    // Filtro de busqueda en tabla
    $($document).ready(function() {
        $("#search").keyup(function() {
            var valor = $(this).val().toLowerCase();
            $("#mytable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(valor) > -1)
            });
        });
    });
    </script>
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
    </style>
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col">
                <div class="thead"><strong><center>HISTORIA CLÍNICA</center></strong></div>
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
                    <div class="col-sm-2">Habitación: <strong><?php echo $num_cama; ?></strong></div>
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
        <div class="thead"><strong><center>EXÁMENES DE LABORATORIO Y GABINETE</center></strong></div>
        <form action="insertar_examenes_lab_gabinete.php" method="POST" onsubmit="return checkSubmit();">
            <div class="accordion mt-3" id="examAccordion">
                <div class="card">
                    <div class="card-header" id="headingLab">
                        <h2 class="mb-0">
                            <button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#collapseLab" aria-expanded="true" aria-controls="collapseLab">
                                Exámenes de Laboratorio
                            </button>
                        </h2>
                    </div>
                    <div id="collapseLab" class="collapse show" aria-labelledby="headingLab" data-parent="#examAccordion">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="biometria_hematica" id="biometria_hematica" value="1">
                                    <label class="form-check-label" for="biometria_hematica">Biometría Hemática</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="quimica_sanguinea" id="quimica_sanguinea" value="1">
                                    <label class="form-check-label" for="quimica_sanguinea">Química Sanguínea</label>
                                </div>
                                <div id="quimica_sanguinea_table" style="display: none;">
                                    <table class="table table-bordered mt-2">
                                        <thead>
                                            <tr>
                                                <th>Parámetro</th>
                                                <th>Valor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>Glucosa</td><td><input type="number" class="form-control" name="quimica_sanguinea_glucosa" step="0.01"></td></tr>
                                            <tr><td>Colesterol</td><td><input type="number" class="form-control" name="quimica_sanguinea_colesterol" step="0.01"></td></tr>
                                            <tr><td>Triglicéridos</td><td><input type="number" class="form-control" name="quimica_sanguinea_trigliceridos" step="0.01"></td></tr>
                                            <tr><td>Creatinina</td><td><input type="number" class="form-control" name="quimica_sanguinea_creatinina" step="0.01"></td></tr>
                                        </tbody>
                                    </table>
                                </div>
                                <script>
                                    document.getElementById('quimica_sanguinea').addEventListener('change', function() {
                                        document.getElementById('quimica_sanguinea_table').style.display = this.checked ? 'block' : 'none';
                                    });
                                </script>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="elementos" id="elementos" value="1">
                                    <label class="form-check-label" for="elementos">Elementos</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="tiempos_coagulacion" id="tiempos_coagulacion" value="1">
                                    <label class="form-check-label" for="tiempos_coagulacion">Tiempos de Coagulación (TP/TT)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="hemoglobina_glucosilada" id="hemoglobina_glucosilada" value="1">
                                    <label class="form-check-label" for="hemoglobina_glucosilada">Hemoglobina Glucosilada</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="examen_general_orina" id="examen_general_orina" value="1">
                                    <label class="form-check-label" for="examen_general_orina">Examen General de Orina</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="electrocardiograma" id="electrocardiograma" value="1">
                                    <label class="form-check-label" for="electrocardiograma">Electrocardiograma</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="pruebas_funcion_hepatica" id="pruebas_funcion_hepatica" value="1">
                                    <label class="form-check-label" for="pruebas_funcion_hepatica">Pruebas de Función Hepática</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="antigeno_sars_cov_2" id="antigeno_sars_cov_2" value="1">
                                    <label class="form-check-label" for="antigeno_sars_cov_2">Antígeno SARS-CoV-2</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="pcr_sars_cov_2" id="pcr_sars_cov_2" value="1">
                                    <label class="form-check-label" for="pcr_sars_cov_2">PCR SARS-CoV-2</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="electroitos_sericos" id="electroitos_sericos" value="1">
                                    <label class="form-check-label" for="electroitos_sericos">Electrolitos Séricos</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="pruebas_funcion_tiroidea" id="pruebas_funcion_tiroidea" value="1">
                                    <label class="form-check-label" for="pruebas_funcion_tiroidea">Pruebas de Función Tiroidea</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="acs_anti_tiroglubolina" id="acs_anti_tiroglubolina" value="1">
                                    <label class="form-check-label" for="acs_anti_tiroglubolina">AC'S Anti-Tiroglubolina</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="acs_antireceptores_tsh" id="acs_antireceptores_tsh" value="1">
                                    <label class="form-check-label" for="acs_antireceptores_tsh">ACS Anti-Receptores TSH</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="acs_antiperoxidasa" id="acs_antiperoxidasa" value="1">
                                    <label class="form-check-label" for="acs_antiperoxidasa">ACS Antiperoxidasa</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="velocidad_sedimentacion_globular" id="velocidad_sedimentacion_globular" value="1">
                                    <label class="form-check-label" for="velocidad_sedimentacion_globular">Velocidad de Sedimentación Globular</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="proteina_c_reactiva" id="proteina_c_reactiva" value="1">
                                    <label class="form-check-label" for="proteina_c_reactiva">Proteína C Reactiva</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="vdrl" id="vdrl" value="1">
                                    <label class="form-check-label" for="vdrl">VDRL</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fta_abs" id="fta_abs" value="1">
                                    <label class="form-check-label" for="fta_abs">FTA-ABS</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="ppd" id="ppd" value="1">
                                    <label class="form-check-label" for="ppd">PPD</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="elisa_vih_1_y_2" id="elisa_vih_1_y_2" value="1">
                                    <label class="form-check-label" for="elisa_vih_1_y_2">ELISA VIH 1 y 2</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="acs_toxoplasmosis_igg_igm" id="acs_toxoplasmosis_igg_igm" value="1">
                                    <label class="form-check-label" for="acs_toxoplasmosis_igg_igm">ACS Toxoplasmosis IgG/IgM</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="factor_reumatoide" id="factor_reumatoide" value="1">
                                    <label class="form-check-label" for="factor_reumatoide">Factor Reumatoide</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="acs_anti_ccp" id="acs_anti_ccp" value="1">
                                    <label class="form-check-label" for="acs_anti_ccp">ACS Anti-CCP</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="antigeno_hla_b27" id="antigeno_hla_b27" value="1">
                                    <label class="form-check-label" for="antigeno_hla_b27">Antígeno HLA-B27</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="acs_antinucleares" id="acs_antinucleares" value="1">
                                    <label class="form-check-label" for="acs_antinucleares">ACS Antinucleares</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="acs_anticardiolipina" id="acs_anticardiolipina" value="1">
                                    <label class="form-check-label" for="acs_anticardiolipina">ACS Anticardiolipina</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="acs_p_ancasy_c_ancas" id="acs_p_ancasy_c_ancas" value="1">
                                    <label class="form-check-label" for="acs_p_ancasy_c_ancas">ACS P-ANCAs y C-ANCAs</label>
                                </div>
                                <div class="form-group">
                                    <label for="otros_laboratorio">Otros:</label>
                                    <textarea class="form-control" name="otros_laboratorio" id="otros_laboratorio" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingGabinete">
                        <h2 class="mb-0">
                            <button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#collapseGabinete" aria-expanded="false" aria-controls="collapseGabinete">
                                Exámenes de Gabinete
                            </button>
                        </h2>
                    </div>
                    <div id="collapseGabinete" class="collapse" aria-labelledby="headingGabinete" data-parent="#examAccordion">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="calculo_lio_iol_master" id="calculo_lio_iol_master" value="1">
                                    <label class="form-check-label" for="calculo_lio_iol_master">Cálculo de LIO IOL Master</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="calculo_lio_inmersion" id="calculo_lio_inmersion" value="1">
                                    <label class="form-check-label" for="calculo_lio_inmersion">Cálculo de LIO Inmersión</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="topografia_corneal" id="topografia_corneal" value="1" data-toggle="collapse" data-target="#topografiaOptions">
                                    <label class="form-check-label" for="topografia_corneal">Topografía Corneal</label>
                                    <div id="topografiaOptions" class="collapse">
                                        <select class="form-control mt-2" name="topografia_corneal_opcion" id="topografia_corneal_opcion">
                                            <option value="">Seleccione</option>
                                            <option value="Tomografía">Tomografía</option>
                                            <option value="Mapas de Elevación">Mapas de Elevación</option>
                                            <option value="Curvas de Potencia">Curvas de Potencia</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="microscopia_especular" id="microscopia_especular" value="1">
                                    <label class="form-check-label" for="microscopia_especular">Microscopia Especular</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="paquimetria" id="paquimetria" value="1">
                                    <label class="form-check-label" for="paquimetria">Paquimetría</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="ultrabiomicroscopia" id="ultrabiomicroscopia" value="1">
                                    <label class="form-check-label" for="ultrabiomicroscopia">Ultrabiomicroscopia</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fotografia_segmento_anterior" id="fotografia_segmento_anterior" value="1">
                                    <label class="form-check-label" for="fotografia_segmento_anterior">Fotografía de Segmento Anterior</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="angiografia" id="angiografia" value="1" data-toggle="collapse" data-target="#angiografiaOptions">
                                    <label class="form-check-label" for="angiografia">Angiografía</label>
                                    <div id="angiografiaOptions" class="collapse">
                                        <select class="form-control mt-2" name="angiografia_opcion" id="angiografia_opcion">
                                            <option value="">Seleccione</option>
                                            <option value="Fluoresceína">Fluoresceína</option>
                                            <option value="ICG">ICG</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="oct_macular" id="oct_macular" value="1">
                                    <label class="form-check-label" for="oct_macular">OCT Macular</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="campos_visuales" id="campos_visuales" value="1" data-toggle="collapse" data-target="#camposVisualesOptions">
                                    <label class="form-check-label" for="campos_visuales">Campos Visuales</label>
                                    <div id="camposVisualesOptions" class="collapse">
                                        <select class="form-control mt-2" name="campos_visuales_opcion" id="campos_visuales_opcion">
                                            <option value="">Seleccione</option>
                                            <option value="Perimetría Automática">Perimetría Automática</option>
                                            <option value="Campimetría Manual">Campimetría Manual</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="oct_nervio_optico" id="oct_nervio_optico" value="1">
                                    <label class="form-check-label" for="oct_nervio_optico">OCT Nervio Óptico</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="hrt_nervio_optico" id="hrt_nervio_optico" value="1">
                                    <label class="form-check-label" for="hrt_nervio_optico">HRT Nervio Óptico</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="gdx_analisis_fibras_nerviosas" id="gdx_analisis_fibras_nerviosas" value="1">
                                    <label class="form-check-label" for="gdx_analisis_fibras_nerviosas">GDX Análisis de Fibras Nerviosas</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="curva_horaria_pio" id="curva_horaria_pio" value="1">
                                    <label class="form-check-label" for="curva_horaria_pio">Curva Horaria PIO</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="resonancia_magnetica_orbita" id="resonancia_magnetica_orbita" value="1">
                                    <label class="form-check-label" for="resonancia_magnetica_orbita">Resonancia Magnética Órbita</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="tomografia_orbita" id="tomografia_orbita" value="1">
                                    <label class="form-check-label" for="tomografia_orbita">Tomografía Órbita</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="autofluorescencia_infrarrojo" id="autofluorescencia_infrarrojo" value="1">
                                    <label class="form-check-label" for="autofluorescencia_infrarrojo">Autofluorescencia e Infrarrojo</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="ecografia" id="ecografia" value="1" data-toggle="collapse" data-target="#ecografiaOptions">
                                    <label class="form-check-label" for="ecografia">Ecografía</label>
                                    <div id="ecografiaOptions" class="collapse">
                                        <select class="form-control mt-2" name="ecografia_opcion" id="ecografia_opcion">
                                            <option value="">Seleccione</option>
                                            <option value="A-Scan">A-Scan</option>
                                            <option value="B-Scan">B-Scan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fotografia_9_campos" id="fotografia_9_campos" value="1">
                                    <label class="form-check-label" for="fotografia_9_campos">Fotografía de 9 Campos</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fotografia_fondo_ojo" id="fotografia_fondo_ojo" value="1">
                                    <label class="form-check-label" for="fotografia_fondo_ojo">Fotografía de Fondo de Ojo</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fotografia_nervio_optico" id="fotografia_nervio_optico" value="1">
                                    <label class="form-check-label" for="fotografia_nervio_optico">Fotografía de Nervio Óptico</label>
                                </div>
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
                <button type="submit" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-danger" onclick="history.back()">Cancelar</button>
            </center>
        </form>
    </div>

    <script>
    let enviando = false;

    function checkSubmit() {
        if (!enviando) {
            enviando = true;
            return true;
        } else {
            alert("El formulario ya se esta enviando");
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