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
    </div><br>
   <div class="container">
    <div class="thead">
        <strong>
                <center>RECETA DE LENTES DE CONTACTO</center>
            </strong>
        </div>

        <form method="POST" action="insertar_lentes_c.php">

            <h4 class="mt-2"><strong>Usuario de Lentes de Contacto Suaves</strong></h4>
            <div class="form-group">
                <select class="form-control" name="usuario_lentes_suaves">
                    <option value="">Seleccionar</option>
                    <option value="Sí">Sí</option>
                    <option value="No">No</option>
                </select>
            </div>

            <h4 class="mt-4"><strong>Lentes de Contacto Suaves</strong></h4>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Usa Lentes Suaves OD (Derecho)</label>
                    <select class="form-control" name="usa_lentes_suaves_der">
                        <option value="">Seleccionar</option>
                        <option value="Sí">Sí</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>Usa Lentes Suaves OI (Izquierdo)</label>
                    <select class="form-control" name="usa_lentes_suaves_izq">
                        <option value="">Seleccionar</option>
                        <option value="Sí">Sí</option>
                        <option value="No">No</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3"><label>Esf Der</label><input class="form-control" name="av_suaves_der_esf"></div>
                <div class="form-group col-md-3"><label>Cil Der</label><input class="form-control" name="av_suaves_der_cil"></div>
                <div class="form-group col-md-3"><label>CB Der</label><input class="form-control" name="av_suaves_der_cb"></div>
                <div class="form-group col-md-3"><label>Diam Der</label><input class="form-control" name="av_suaves_der_diam"></div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-3"><label>Esf Izq</label><input class="form-control" name="av_suaves_izq_esf"></div>
                <div class="form-group col-md-3"><label>Cil Izq</label><input class="form-control" name="av_suaves_izq_cil"></div>
                <div class="form-group col-md-3"><label>CB Izq</label><input class="form-control" name="av_suaves_izq_cb"></div>
                <div class="form-group col-md-3"><label>Diam Izq</label><input class="form-control" name="av_suaves_izq_diam"></div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Tipo Suaves Der</label>
                    <select class="form-control" name="tipo_suaves_der">
                        <option value="">Seleccionar</option>
                        <option value="Esférico">Esférico</option>
                        <option value="Tórico">Tórico</option>
                        <option value="Multifocal">Multifocal</option>
                        <option value="Cosmético">Cosmético</option>
                        <option value="Color">Color</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>Tipo Suaves Izq</label>
                    <select class="form-control" name="tipo_suaves_izq">
                        <option value="">Seleccionar</option>
                        <option value="Esférico">Esférico</option>
                        <option value="Tórico">Tórico</option>
                        <option value="Multifocal">Multifocal</option>
                        <option value="Cosmético">Cosmético</option>
                        <option value="Color">Color</option>
                    </select>
                </div>
            </div>


            <div class="container">
                <div class="thead">
                    <strong>
                        <center>RECETA DE LENTES DE CONTACTO DUROS</center>
                    </strong>
                </div>
                <br>
                <h5><strong>¿Usa Lentes de Contacto Duros?</strong></h5>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>OD (Derecho)</label>
                        <select class="form-control" name="usa_lentes_duros_der">
                            <option value="">Seleccionar</option>
                            <option value="Sí">Sí</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>OI (Izquierdo)</label>
                        <select class="form-control" name="usa_lentes_duros_izq">
                            <option value="">Seleccionar</option>
                            <option value="Sí">Sí</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>

                <h5 class="mt-4"><strong>Parámetros Básicos</strong></h5>
                <div class="form-row">
                    <div class="form-group col-md-3"><label>Esf Der</label><input class="form-control" name="av_duros_der_esf"></div>
                    <div class="form-group col-md-3"><label>Cil Der</label><input class="form-control" name="av_duros_der_cil"></div>
                    <div class="form-group col-md-3"><label>CB Der</label><input class="form-control" name="av_duros_der_cb"></div>
                    <div class="form-group col-md-3"><label>Diam Der</label><input class="form-control" name="av_duros_der_diam"></div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3"><label>Esf Izq</label><input class="form-control" name="av_duros_izq_esf"></div>
                    <div class="form-group col-md-3"><label>Cil Izq</label><input class="form-control" name="av_duros_izq_cil"></div>
                    <div class="form-group col-md-3"><label>CB Izq</label><input class="form-control" name="av_duros_izq_cb"></div>
                    <div class="form-group col-md-3"><label>Diam Izq</label><input class="form-control" name="av_duros_izq_diam"></div>
                </div>

                <h5><strong>AV con LC Híbrido</strong></h5>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>OD (Derecho)</label>
                        <select class="form-control" name="av_con_hibrido_der">
                            <option value="">Seleccionar</option>
                            <option value="20/20">20/20</option>
                            <option value="20/25">20/25</option>
                            <option value="20/30">20/30</option>
                            <option value="20/40">20/40</option>
                            <option value="20/50">20/50</option>
                            <option value="20/60">20/60</option>
                            <option value="20/70">20/70</option>
                            <option value="20/80">20/80</option>
                            <option value="20/100">20/100</option>
                            <option value="20/200">20/200</option>
                            <option value="Cuenta dedos">Cuenta dedos</option>
                            <option value="Percepción de luz">Percepción de luz</option>
                            <option value="Sin percepción de luz">Sin percepción de luz</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>OI (Izquierdo)</label>
                        <select class="form-control" name="av_con_hibrido_izq">
                            <option value="">Seleccionar</option>
                            <option value="20/20">20/20</option>
                            <option value="20/25">20/25</option>
                            <option value="20/30">20/30</option>
                            <option value="20/40">20/40</option>
                            <option value="20/50">20/50</option>
                            <option value="20/60">20/60</option>
                            <option value="20/70">20/70</option>
                            <option value="20/80">20/80</option>
                            <option value="20/100">20/100</option>
                            <option value="20/200">20/200</option>
                            <option value="Cuenta dedos">Cuenta dedos</option>
                            <option value="Percepción de luz">Percepción de luz</option>
                            <option value="Sin percepción de luz">Sin percepción de luz</option>
                        </select>
                    </div>
                </div>
                <h5 class="mt-4"><strong>Receta LC Híbrido</strong></h5>
                <div class="form-row">
                    <div class="form-group col-md-3"><label>VLT/CB Der</label><input class="form-control" name="receta_duros_der_tangente"></div>
                    <div class="form-group col-md-3"><label>Diam Der</label><input class="form-control" name="receta_duros_der_altura"></div>
                    <div class="form-group col-md-3"><label>Esf Der</label><input class="form-control" name="receta_duros_der_el"></div>
                    <div class="form-group col-md-3"><label>Faldilla Der</label><input class="form-control" name="receta_duros_der_or"></div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3"><label>VLT/CB Izq</label><input class="form-control" name="receta_duros_izq_tangente"></div>
                    <div class="form-group col-md-3"><label>Diam Izq</label><input class="form-control" name="receta_duros_izq_altura"></div>
                    <div class="form-group col-md-3"><label>Esf Izq</label><input class="form-control" name="receta_duros_izq_el"></div>
                    <div class="form-group col-md-3"><label>Faldilla Izq</label><input class="form-control" name="receta_duros_izq_or"></div>
                </div>
                <div class="thead">
                    <strong>
                        <center>TIPO DE LENTES DE CONTACTO</center>
                    </strong>
                </div>
                <h5 class="mt-4"><strong>Tipo de Lente de Contacto</strong></h5>
                <div class="form-group">
                    <label for="tipo_lente">Selecciona el tipo de lente</label>
                    <select class="form-control" id="tipo_lente" onchange="cambiarOpcionesYMarcasYDKAV()">
                        <option value="">Seleccionar</option>
                        <option value="duros">Lente de Contacto Duros</option>
                        <option value="suaves">Lente de Contacto Suaves</option>
                        <option value="hibridos">Lente Híbrido</option>
                        <option value="esclerales">Lente Escleral</option>
                    </select>
                </div>


                <h5><strong>¿Qué tipo usa en cada ojo?</strong></h5>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="opciones_od">OD (Derecho)</label>
                        <select class="form-control" id="opciones_od" name="opciones_od">
                            <option value="">Seleccionar tipo de lente primero</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="opciones_oi">OI (Izquierdo)</label>
                        <select class="form-control" id="opciones_oi" name="opciones_oi">
                            <option value="">Seleccionar tipo de lente primero</option>
                        </select>
                    </div>
                </div>

                <!-- Selección de marcas -->
                <h5><strong>Marca por Ojo</strong></h5>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="marca_od">Marca OD (Derecho)</label>
                        <select class="form-control" id="marca_od" name="marca_od">
                            <option value="">Seleccionar tipo primero</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="marca_oi">Marca OI (Izquierdo)</label>
                        <select class="form-control" id="marca_oi" name="marca_oi">
                            <option value="">Seleccionar tipo primero</option>
                        </select>
                    </div>
                </div>

                <h5 class="mt-4"><strong>DK y AV para Lente Escleral</strong></h5>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="dk_od">DK OD (Derecho)</label>
                        <select class="form-control" id="dk_od" name="dk_od" disabled>
                            <option value="">Seleccionar tipo escleral primero</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="av_od">AV OD (Derecho)</label>
                        <select class="form-control" id="av_od" name="av_od" disabled>
                            <option value="">Seleccionar tipo escleral primero</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="dk_oi">DK OI (Izquierdo)</label>
                        <select class="form-control" id="dk_oi" name="dk_oi" disabled>
                            <option value="">Seleccionar tipo escleral primero</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="av_oi">AV OI (Izquierdo)</label>
                        <select class="form-control" id="av_oi" name="av_oi" disabled>
                            <option value="">Seleccionar tipo escleral primero</option>
                        </select>
                    </div>
                </div>

                <script>
                    function cambiarOpcionesYMarcasYDKAV() {
                        const tipo = document.getElementById("tipo_lente").value;

                        const opcionesOD = document.getElementById("opciones_od");
                        const opcionesOI = document.getElementById("opciones_oi");
                        const marcaOD = document.getElementById("marca_od");
                        const marcaOI = document.getElementById("marca_oi");

                        const dkOD = document.getElementById("dk_od");
                        const avOD = document.getElementById("av_od");
                        const dkOI = document.getElementById("dk_oi");
                        const avOI = document.getElementById("av_oi");

                        const opciones = {
                            duros: ["Sí", "No"],
                            suaves: ["Diarias", "Quincenales", "Mensuales", "Tóricas", "Multifocales"],
                            hibridos: ["Alta AV", "Molestias", "Adaptación incompleta", "No disponible"],
                            esclerales: ["Grande", "Pequeña", "Alta elevación", "Con túnel de ventilación"]
                        };

                        const marcas = {
                            duros: ["Boston XO", "Paragon HDS", "Optimum Extra", "Menicon Z"],
                            suaves: ["Acuvue Oasys", "Air Optix", "Biofinity", "Dailies Total 1"],
                            hibridos: ["SynergEyes A", "UltraHealth", "Duette", "ClearKone"],
                            esclerales: ["Zenlens", "Jupiter", "Onefit", "Europa Scleral"]
                        };

                        const dkValores = ["28", "29", "30", "31"];
                        const avValores = ["8.0", "8.2", "8.4", "8.6"];

                        const valores = opciones[tipo] || [];
                        const marcasTipo = marcas[tipo] || [];

                        function rellenarSelect(select, items, habilitar = true) {
                            select.innerHTML = "";
                            const defaultOption = document.createElement("option");
                            defaultOption.value = "";
                            defaultOption.textContent = habilitar ? "Seleccionar" : "Seleccionar tipo escleral primero";
                            select.appendChild(defaultOption);

                            if (habilitar) {
                                items.forEach(item => {
                                    const opt = document.createElement("option");
                                    opt.value = item;
                                    opt.textContent = item;
                                    select.appendChild(opt);
                                });
                            }
                            select.disabled = !habilitar;
                        }

                        rellenarSelect(opcionesOD, valores);
                        rellenarSelect(opcionesOI, valores);
                        rellenarSelect(marcaOD, marcasTipo);
                        rellenarSelect(marcaOI, marcasTipo);

                        if (tipo === "esclerales") {
                            rellenarSelect(dkOD, dkValores, true);
                            rellenarSelect(avOD, avValores, true);
                            rellenarSelect(dkOI, dkValores, true);
                            rellenarSelect(avOI, avValores, true);
                        } else {
                            rellenarSelect(dkOD, [], false);
                            rellenarSelect(avOD, [], false);
                            rellenarSelect(dkOI, [], false);
                            rellenarSelect(avOI, [], false);
                        }
                    }
                </script>
                <div class="form-row">
                    <div class="form-group col-md-3"><label>Sagita Der</label><input class="form-control" name="receta_duros_der_tangente"></div>
                    <div class="form-group col-md-3"><label>Diam Der</label><input class="form-control" name="receta_duros_der_altura"></div>
                    <div class="form-group col-md-3"><label>Med Der</label><input class="form-control" name="receta_duros_der_el"></div>
                    <div class="form-group col-md-3"><label>Limbal Der</label><input class="form-control" name="receta_duros_der_or"></div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3"><label>Saguita Izq</label><input class="form-control" name="receta_duros_izq_tangente"></div>
                    <div class="form-group col-md-3"><label>Diam Izq</label><input class="form-control" name="receta_duros_izq_altura"></div>
                    <div class="form-group col-md-3"><label>Med Izq</label><input class="form-control" name="receta_duros_izq_el"></div>
                    <div class="form-group col-md-3"><label>Limbal Izq</label><input class="form-control" name="receta_duros_izq_or"></div>
                </div>


                <h5><strong>Detalles del Tratamiento</strong></h5>
<div class="form-group">
    <div class="botones mb-2">
        <button type="button" class="btn btn-danger btn-sm" id="detalle_contacto_grabar"><i class="fas fa-microphone"></i></button>
        <button type="button" class="btn btn-primary btn-sm" id="detalle_contacto_detener"><i class="fas fa-microphone-slash"></i></button>
        <button type="button" class="btn btn-success btn-sm" id="play_detalle_contacto"><i class="fas fa-play"></i></button>
    </div>
    <textarea class="form-control" name="detalle_contacto" id="detalle_contacto" rows="4" placeholder="Ej. Observación de agudeza visual, recomendación de lentes, seguimiento de tratamiento"></textarea>
</div>



         <script>
    const detalle_contacto_grabar = document.getElementById('detalle_contacto_grabar');
    const detalle_contacto_detener = document.getElementById('detalle_contacto_detener');
    const detalle_contacto = document.getElementById('detalle_contacto');
    const btn_play_detalle_contacto = document.getElementById('play_detalle_contacto');

    btn_play_detalle_contacto.addEventListener('click', () => {
        leerTexto(detalle_contacto.value);
    });

    let recognition_detalle_contacto = new webkitSpeechRecognition();
    recognition_detalle_contacto.lang = "es-ES";
    recognition_detalle_contacto.continuous = true;
    recognition_detalle_contacto.interimResults = false;
    recognition_detalle_contacto.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length - 1][0].transcript;
        detalle_contacto.value += frase;
    };

    detalle_contacto_grabar.addEventListener('click', () => {
        recognition_detalle_contacto.start();
    });

    detalle_contacto_detener.addEventListener('click', () => {
        recognition_detalle_contacto.abort();
    });

    function leerTexto(texto) {
        const speech = new SpeechSynthesisUtterance();
        speech.text = texto;
        speech.volume = 1;
        speech.rate = 1;
        speech.pitch = 0;
        window.speechSynthesis.speak(speech);
    }
</script>

    </div>


    <center class="mt-3">
        <button type="submit" class="btn btn-primary">Firmar</button>
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