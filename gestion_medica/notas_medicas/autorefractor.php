<?php
session_start();
include "../../conexionbd.php";
include("../header_medico.php");
$usuario = $_SESSION['login'];
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>Exámenes de Laboratorio y Gabinete - Instituto de Enfermedades Oculares</title>
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

    <script>
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

        .accordion .card {
            border: none;
        }

        .accordion .card-header {
            background-color: #e9ecef;
            cursor: pointer;
        }
    </style>
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
    </div>

<div class="container mt-4 mb-5" style="margin-bottom:60px;">

    <div class="thead mb-4">
        <strong>
            <center>AUTOREFRACTOR / QUERATOCONO</center>
        </strong>
    </div>

    <form action="insertar_autorefractor.php" method="POST" onsubmit="return checkSubmit();">

        <!-- Refacción Previa -->
<div class="card mb-4">
    <div class="card-header bg-azul">
        Refacción Previa
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm text-center align-middle">
                <thead class="thead-light">
                    <tr>
                        <th></th>
                        <th colspan="4">OD (Ojo Derecho)</th>
                        <th colspan="4">OI (Ojo Izquierdo)</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>Esf</th>
                        <th>Cil</th>
                        <th>Eje°</th>
                        <th>DIP</th>
                        <th>Esf</th>
                        <th>Cil</th>
                        <th>Eje°</th>
                        <th>DIP</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
    <td>
        <select name="previa_tipo1" id="previa_tipo1" class="form-control form-control-sm" onchange="mostrarOtro('previa_tipo1', 'previa_tipo1_otro')">
            <option value="">Seleccionar</option>
            <option value="KR">KR</option>
            <option value="AR">AR</option>
            <option value="MR">MR</option>
            <option value="SR">SR</option>
            <option value="otro">Otro</option>
        </select>
        <input type="text" name="previa_tipo1_otro" id="previa_tipo1_otro" class="form-control mt-2" style="display:none;" placeholder="Especifique otro tipo">
    </td>
    <td><input name="previa1_od_esf" class="form-control"></td>
    <td><input name="previa1_od_cil" class="form-control"></td>
    <td><input name="previa1_od_eje" class="form-control"></td>
    <td><input name="previa1_od_dip" class="form-control"></td>
    <td><input name="previa1_oi_esf" class="form-control"></td>
    <td><input name="previa1_oi_cil" class="form-control"></td>
    <td><input name="previa1_oi_eje" class="form-control"></td>
    <td><input name="previa1_oi_dip" class="form-control"></td>
</tr>
<tr>
    <td>
        <select name="previa_tipo2" id="previa_tipo2" class="form-control form-control-sm" onchange="mostrarOtro('previa_tipo2', 'previa_tipo2_otro')">
            <option value="">Seleccionar</option>
            <option value="KR">KR</option>
            <option value="AR">AR</option>
            <option value="MR">MR</option>
            <option value="SR">SR</option>
            <option value="otro">Otro</option>
        </select>
        <input type="text" name="previa_tipo2_otro" id="previa_tipo2_otro" class="form-control mt-2" style="display:none;" placeholder="Especifique otro tipo">
    </td>
    <td><input name="previa2_od_esf" class="form-control"></td>
    <td><input name="previa2_od_cil" class="form-control"></td>
    <td><input name="previa2_od_eje" class="form-control"></td>
    <td><input name="previa2_od_dip" class="form-control"></td>
    <td><input name="previa2_oi_esf" class="form-control"></td>
    <td><input name="previa2_oi_cil" class="form-control"></td>
    <td><input name="previa2_oi_eje" class="form-control"></td>
    <td><input name="previa2_oi_dip" class="form-control"></td>
</tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
function mostrarOtro(selectId, inputId) {
    var select = document.getElementById(selectId);
    var input = document.getElementById(inputId);
    if (select.value === "otro") {
        input.style.display = "block";
        input.required = true;
    } else {
        input.style.display = "none";
        input.required = false;
        input.value = "";
    }
}
</script>
<!-- Refacción Nueva -->
<div class="card mb-4">
    <div class="card-header bg-azul">
        Refacción Nueva
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm text-center align-middle">
                <thead class="thead-light">
                    <tr>
                        <th></th>
                        <th colspan="3" class="text-primary">Sin Ciclopléjia</th>
                        <th colspan="4" class="text-primary">Con Ciclopléjia</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th class=".text-primary">Esf</th>
                        <th class="text-primary">Cil</th>
                        <th class="text-primary">Eje°</th>
                        <th>
                            <select id="ciclo_tipo" name="ciclo_tipo" class="form-control form-control-sm" style="color:#2b2d7f;">
                                <option value="">Seleccionar</option>
                                <option value="MR">MR</option>
                                <option value="SR">SR</option>
                                <option value="AR">AR</option>
                            </select>
                        </th>
                        <th class="text-primary">Esf</th>
                        <th class="text-primary">Cil</th>
                        <th class="text-primary">Eje°</th>
                        <th>DIP</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>OD (Ojo Derecho)</strong></td>
                        <td><input name="nueva_sin_od_esf" class="form-control"></td>
                        <td><input name="nueva_sin_od_cil" class="form-control"></td>
                        <td><input name="nueva_sin_od_eje" class="form-control"></td>
                        <td></td>
                        <td><input name="nueva_con_od_esf" class="form-control"></td>
                        <td><input name="nueva_con_od_cil" class="form-control"></td>
                        <td><input name="nueva_con_od_eje" class="form-control"></td>
                        <td><input name="nueva_con_od_dip" class="form-control"></td>
                    </tr>
                    <tr>
                        <td><strong>OI (Ojo Izquierdo)</strong></td>
                        <td><input name="nueva_sin_oi_esf" class="form-control"></td>
                        <td><input name="nueva_sin_oi_cil" class="form-control"></td>
                        <td><input name="nueva_sin_oi_eje" class="form-control"></td>
                        <td></td>
                        <td><input name="nueva_con_oi_esf" class="form-control"></td>
                        <td><input name="nueva_con_oi_cil" class="form-control"></td>
                        <td><input name="nueva_con_oi_eje" class="form-control"></td>
                        <td><input name="nueva_con_oi_dip" class="form-control"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

        <!-- Retinoscopía -->
        <div class="card mb-4">
            <div class="card-header bg-azul">
                Retinoscopía
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm text-center">
                        <thead class="thead-light">
                            <tr>
                                <th></th>
                                <th>Ref H</th>
                                <th>Eje H</th>
                                <th>Ref V</th>
                                <th>Eje V</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>OD (Ojo Derecho)</strong></td>
                                <td><input name="ret_od_refh" class="form-control"></td>
                                <td><input name="ret_od_ejeh" class="form-control"></td>
                                <td><input name="ret_od_refv" class="form-control"></td>
                                <td><input name="ret_od_ejev" class="form-control"></td>
                            </tr>
                            <tr>
                                <td><strong>OI (Ojo Izquierdo)</strong></td>
                                <td><input name="ret_oi_refh" class="form-control"></td>
                                <td><input name="ret_oi_ejeh" class="form-control"></td>
                                <td><input name="ret_oi_refv" class="form-control"></td>
                                <td><input name="ret_oi_ejev" class="form-control"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Refracción de Retinoscopía -->
<div class="card mb-4">
    <div class="card-header bg-azul">
        Refracción de Retinoscopía
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm text-center align-middle">
                <thead class="thead-light">
                    <tr>
                        <th></th>
                        <th colspan="3">OD (Ojo Derecho)</th>
                        <th colspan="3">OI (Ojo Izquierdo)</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>Esf</th>
                        <th>Cil</th>
                        <th>Eje°</th>
                        <th>Esf</th>
                        <th>Cil</th>
                        <th>Eje°</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td><input name="ret_ref_od_esf" class="form-control"></td>
                        <td><input name="ret_ref_od_cil" class="form-control"></td>
                        <td><input name="ret_ref_od_eje" class="form-control"></td>
                        <td><input name="ret_ref_oi_esf" class="form-control"></td>
                        <td><input name="ret_ref_oi_cil" class="form-control"></td>
                        <td><input name="ret_ref_oi_eje" class="form-control"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

        <!-- Queratometría -->
<div class="card mb-4">
    <div class="card-header bg-azul">
        Queratometría
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm text-center align-middle">
                <thead class="thead-light">
                    <tr>
                        <th rowspan="2" class="align-middle"> </th>
                        <th colspan="2">OD (Ojo Derecho)</th>
                        <th colspan="2">OI (Ojo Izquierdo)</th>
                    </tr>
                    <tr>
                        <th>Valor</th>
                        <th>Eje°</th>
                        <th>Valor</th>
                        <th>Eje°</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>K1</strong></td>
                        <td><input name="q_od_k1" class="form-control"></td>
                        <td><input name="q_od_k1_eje" class="form-control"></td>
                        <td><input name="q_oi_k1" class="form-control"></td>
                        <td><input name="q_oi_k1_eje" class="form-control"></td>
                    </tr>
                    <tr>
                        <td><strong>K2</strong></td>
                        <td><input name="q_od_k2" class="form-control"></td>
                        <td><input name="q_od_k2_eje" class="form-control"></td>
                        <td><input name="q_oi_k2" class="form-control"></td>
                        <td><input name="q_oi_k2_eje" class="form-control"></td>
                    </tr>
                    <tr>
                        <td><strong>CYL</strong></td>
                        <td><input name="q_od_cyl" class="form-control"></td>
                        <td><input name="q_od_cyl_eje" class="form-control"></td>
                        <td><input name="q_oi_cyl" class="form-control"></td>
                        <td><input name="q_oi_cyl_eje" class="form-control"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
            <center class="mt-3">
                <button type="submit" class="btn btn-primary">Firmar</button>
                <button type="button" class="btn btn-danger" onclick="history.back()">Cancelar</button>
            </center>

        </form>
    </div>
</div><br>
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