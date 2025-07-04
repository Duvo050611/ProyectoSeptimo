<?php
session_start();
include "../../conexionbd.php";
include("../header_enfermera.php");
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″ />

    <link rel="stylesheet" type="text/css" href="../../gestion_medica/hospitalizacion/css/select2.css">
    <script src="jquery-3.1.1.min.js"></script>
    <script src="../../gestion_medica/hospitalizacion/js/select2.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/e547be4475.js" crossorigin="anonymous"></script>



    <!--  Bootstrap  -->
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <script src="../../js/jquery-3.3.1.min.js"></script>
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/popper.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/main.js"></script>




    <title>HOJA DE PROGRAMACIÓN QUIRÚRGICA </title>
    <style type="text/css">
        .modal-lg {
            max-width: 65% !important;
        }

        $spacing: 12px;
        $module: 48px;

        :root {
            --progressW: 50%;
        }

        label {
            display: block;
            width: 90%;
            vertical-align: baseline;
        }

        .sliderBar {
            position: relative;
            margin: 0 $spacing;
        }

        [type=range] {
            -webkit-appearance: none;
            width: calc(100% - 50px);
            vertical-align: middle;
            border: none;
            outline: none;
        }

        @mixin track() {
            -webkit-appearance: slider-horizontal;
            height: 2px;
            padding: 0;
            cursor: pointer;
            background: linear-gradient(to right, #99F 0%, #99F var(--progressW), #ccc var(--progressW), #ccc 100%);
        }

        @mixin thumb() {
            box-sizing: border-box;
            /*FF*/
            -webkit-appearance: none;
            width: $module/2;
            height: $module/2;
            margin-top: -$spacing;
            border: $spacing/2 solid #eee;
            border-radius: 50%;
            background: #999;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .5)
        }

        @mixin active() {
            width: $module*0.75;
            height: $module*0.75;
            margin-top: -$spacing*1.5;
            background: #99F;
        }

        @mixin progress() {
            background: #99F;
        }


        [type=range] {
            &::-webkit-slider-runnable-track {
                @include track
            }

            &::-moz-range-track {
                @include track
            }

            &::-ms-track {
                @include track
            }

            &::-webkit-slider-thumb {
                @include thumb
            }

            &::-moz-range-thumb {
                @include thumb
            }

            &::-ms-thumb {
                @include thumb
            }

            &:active::-webkit-slider-thumb {
                @include active
            }

            &:active::-moz-range-thumb {
                @include active
            }

            &:active::-ms-thumb {
                @include active
            }

            &::-moz-range-progress {
                @include progress
            }

            &::-ms-fill-upper {
                @include progress
            }
        }
    </style>
</head>

<body>

    <?php
    // Mostrar notificación si se acaba de enviar un formulario
    if (isset($_GET['tratamiento_exito']) && !empty($_GET['tratamiento_exito'])) {
        $tratamiento_exito = htmlspecialchars($_GET['tratamiento_exito']);
        echo '<div class="alert alert-success mt-3" role="alert" style="font-size:18px; text-align:center;">';
        echo 'Formulario de <strong>' . strtoupper($tratamiento_exito) . '</strong> enviado correctamente.';
        echo '</div>';
    }
    ?>
    <div class="col-sm-12">
        <div class="container">
            <div class="row">
                <div class="col-12">

                    <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 24px;">
                        <strong>
                            <center>DATOS DEL PACIENTE</center>
                        </strong>
                    </div>
                    <hr>
                    <?php

                    include "../../conexionbd.php";

                    if (isset($_SESSION['pac'])) {
                        $id_atencion = $_SESSION['pac'];

                        $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.tel, p.fecnac,p.tip_san, di.fecha, di.area, di.alta_med, p.sexo, di.alergias, p.folio FROM paciente p, dat_ingreso di WHERE p.Id_exp=di.Id_exp and di.id_atencion =$id_atencion";

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

                        $sql_now = "SELECT DATE_ADD(NOW(), INTERVAL 12 HOUR) as dat_now FROM dat_ingreso WHERE id_atencion = $id_atencion";

                        $result_now = $conexion->query($sql_now);

                        while ($row_now = $result_now->fetch_assoc()) {
                            $dat_now = $row_now['dat_now'];
                        }

                        $sql_est = "SELECT DATEDIFF( '$dat_now' , fecha) as estancia FROM dat_ingreso WHERE id_atencion = $id_atencion";

                        $result_est = $conexion->query($sql_est);

                        while ($row_est = $result_est->fetch_assoc()) {
                            $estancia = $row_est['estancia'];
                        }


                    ?>
                        <font size="2">

                            <div class="row">

                                <div class="col-sm-4">
                                    Paciente: <strong><?php echo $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac ?></strong>
                                </div>
                                <div class="col-sm">
                                    Expediente: <strong><?php echo $folio ?> </strong>
                                </div>
                                <?php $date = date_create($pac_fecing);
                                ?>
                                <div class="col-sm-4">
                                    Fecha de ingreso: <strong><?php echo date_format($date, "d-m-Y H:i:s") ?></strong>
                                </div>
                            </div>
                        </font>
                        <font size="2">

                            <div class="row">
                                <div class="col-sm-4">
                                    <?php $date1 = date_create($pac_fecnac);
                                    ?>
                                    <!-- INICIO DE FUNCION DE CALCULAR EDAD -->
                                    <?php

                                    $fecha_actual = date("Y-m-d");
                                    $fecha_nac = $pac_fecnac;
                                    $fecha_de_nacimiento = strval($fecha_nac);

                                    // separamos en partes las fechas
                                    $array_nacimiento = explode("-", $fecha_de_nacimiento);
                                    $array_actual = explode("-", $fecha_actual);

                                    $anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos años
                                    $meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses
                                    $dias =  $array_actual[2] - $array_nacimiento[2]; // calculamos días

                                    //ajuste de posible negativo en $días
                                    if ($dias < 0) {
                                        --$meses;

                                        //ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual
                                        switch ($array_actual[1]) {
                                            case 1:
                                                $dias_mes_anterior = 31;
                                                break;
                                            case 2:
                                                $dias_mes_anterior = 31;
                                                break;
                                            case 3:
                                                $dias_mes_anterior = 28;
                                                break;

                                            case 4:
                                                $dias_mes_anterior = 31;
                                                break;
                                            case 5:
                                                $dias_mes_anterior = 30;
                                                break;
                                            case 6:
                                                $dias_mes_anterior = 31;
                                                break;
                                            case 7:
                                                $dias_mes_anterior = 30;
                                                break;
                                            case 8:
                                                $dias_mes_anterior = 31;
                                                break;
                                            case 9:
                                                $dias_mes_anterior = 31;
                                                break;
                                            case 10:
                                                $dias_mes_anterior = 30;
                                                break;
                                            case 11:
                                                $dias_mes_anterior = 31;
                                                break;
                                            case 12:
                                                $dias_mes_anterior = 30;
                                                break;
                                        }

                                        $dias = $dias + $dias_mes_anterior;
                                    }

                                    //ajuste de posible negativo en $meses
                                    if ($meses < 0) {
                                        --$anos;
                                        $meses = $meses + 12;
                                    }

                                    //echo "<br>Tu edad es: $anos años con $meses meses y $dias días";

                                    function bisiesto($anio_actual)
                                    {
                                        $bisiesto = false;
                                        //probamos si el mes de febrero del año actual tiene 29 días
                                        if (checkdate(2, 29, $anio_actual)) {
                                            $bisiesto = true;
                                        }
                                        return $bisiesto;
                                    }

                                    ?>
                                    <!-- TERMINO DE FUNCION DE CALCULAR EDAD -->
                                    Fecha de nacimiento: <strong><?php echo date_format($date1, "d-m-Y") ?></strong>
                                </div>
                                <div class="col-sm-4">
                                    Edad: <strong><?php if ($anos > "0") {
                                                        echo $anos . " años";
                                                    } elseif ($anos <= "0" && $meses > "0") {
                                                        echo $meses . " meses";
                                                    } elseif ($anos <= "0" && $meses <= "0" && $dias > "0") {
                                                        echo $dias . " dias";
                                                    }
                                                    ?></strong>
                                </div>


                                <div class="col-sm-2">
                                    Habitación: <strong><?php $sql_hab = "SELECT num_cama from cat_camas where id_atencion =$id_atencion";
                                                        $result_hab = $conexion->query($sql_hab);
                                                        while ($row_hab = $result_hab->fetch_assoc()) {
                                                            echo $row_hab['num_cama'];
                                                        } ?></strong>
                                </div>

                            </div>

                        </font>
                        <font size="2">
                            <div class="row">
                                <?php
                                $d = "";
                                $sql_motd = "SELECT diagprob_i from dat_nevol where id_atencion=$id_atencion ORDER by diagprob_i ASC LIMIT 1";
                                $result_motd = $conexion->query($sql_motd);
                                while ($row_motd = $result_motd->fetch_assoc()) {
                                    $d = $row_motd['diagprob_i'];
                                } ?>
                                <?php $sql_mot = "SELECT motivo_atn from dat_ingreso where id_atencion=$id_atencion ORDER by motivo_atn ASC LIMIT 1";
                                $result_mot = $conexion->query($sql_mot);
                                while ($row_mot = $result_mot->fetch_assoc()) {
                                    $m = $row_mot['motivo_atn'];
                                } ?>

                                <?php if ($d != null) {
                                    echo '<div class="col-sm-8"> Diagnóstico: <strong>' . $d . '</strong></div>';
                                } else {
                                    echo '<div class="col-sm-8"> Motivo de atención: <strong>' . $m . '</strong></div>';
                                } ?>
                                <div class="col-sm">
                                    Tiempo estancia: <strong><?php echo $estancia ?> Dias</strong>
                                </div>
                            </div>

                        </font>
                        <font size="2">
                            <div class="row">
                                <div class="col-sm-4">
                                    Alergias: <strong><?php echo $alergias ?></strong>
                                </div>
                                <div class="col-sm-4">
                                    Estado de salud: <strong><?php $sql_edo = "SELECT edo_salud from dat_ingreso where id_atencion=$id_atencion ORDER by edo_salud ASC LIMIT 1";
                                                                $result_edo = $conexion->query($sql_edo);
                                                                while ($row_edo = $result_edo->fetch_assoc()) {
                                                                    echo $row_edo['edo_salud'];
                                                                } ?></strong>
                                </div>
                                <div class="col-sm-3">
                                    Tipo de sangre: <strong><?php echo $pac_tip_sang ?></strong>
                                </div>
                            </div>
                        </font>
                        <?php $sql_edo = "SELECT * from dat_hclinica where Id_exp=$id_exp ORDER by id_hc DESC LIMIT 1";
                        $result_edo = $conexion->query($sql_edo);
                        while ($row_edo = $result_edo->fetch_assoc()) {
                            $peso = $row_edo['peso'];
                            $talla = $row_edo['talla'];
                        }
                        if (!isset($peso)) {
                            $peso = 0;
                            $talla = 0;
                        } ?>
                        <font size="2">
                            <div class="row">
                                <div class="col-sm-4">
                                    Peso: <strong><?php echo $peso ?></strong>
                                </div>
                                <div class="col-sm-3">
                                    Talla: <strong><?php echo $talla ?></strong>
                                </div>
                            </div>
                        </font>
                        <hr>
                </div>
            <?php
                    } else {
                        echo '<script type="text/javascript"> window.location.href="../lista_pacientes/lista_pacientes.php";</script>';
                    }
            ?>

            <div class="container">
                <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 24px;">
                    <strong>
                        <center>HOJA DE PROGRAMACIÓN QUIRÚRGICA</center>
                    </strong>
                </div><!-- Menú tipo acordeón con BLEFAROPLASTIA, FACOEMULSIFICACION, CROSSLINKING, INYECCION, PTERIGIÓN, CIRUGÍA REFRACTIVA, TRANSPLANTE, VALVULA DE AHMED, VITRECTOMIA y CIRUGÍA LASIK -->
                <div class="card-header" id="headingRight">
                    <div class="accordion mt-3" id="surgeryAccordion">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="mb-0 d-flex flex-wrap gap-2">
                                    <?php
                                    $sql_trat = "SELECT * FROM tratamientos";
                                    $result_trat = $conexion->query($sql_trat);
                                    $first = true;
                                    $menu = '';
                                    $forms = '';
                                    while ($row_trat = $result_trat->fetch_assoc()) {
                                        $tipo = $row_trat['tipo'];
                                        $nota = $row_trat['nota'];
                                        $id = $row_trat['id'];
                                        $collapseId = 'collapseTratamiento' . $id;
                                        $menu .= '<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#' . $collapseId . '" aria-expanded="' . ($first ? 'true' : 'false') . '" aria-controls="' . $collapseId . '" style="color:#2b2d7f;">' . strtoupper($tipo) . '</button>';
                                        $first = false;
                                        // Determinar el action del formulario según el tipo
                                        $action = '';
                                        switch ($tipo) {
                                            case 'BLEFAROPLASTIA':
                                                $action = 'insertar_hoja_blefaro.php';
                                                break;
                                            case 'FACOEMULSIFICACION':
                                            case 'facoemulsificacion':
                                            case 'Facoemulsificacion':
                                                $action = 'insertar_hoja_facoemulsificacion.php';
                                                break;
                                            case 'CROSSLINKING':
                                            case 'crosslinking':
                                            case 'Crosslinking':
                                                $action = 'insertar_hoja_crosslinking.php';
                                                break;
                                            case 'INYECCION':
                                            case 'INYECCIÓN':
                                            case 'inyeccion':
                                            case 'inyección':
                                            case 'Inyeccion':
                                            case 'Inyección':
                                                $action = 'insertar_hoja_inyeccion.php';
                                                break;
                                            case 'CHALAZION':
                                            case 'chalazion':
                                            case 'Chalazion':
                                                $action = 'insertar_hoja_chalazion.php';
                                                break;
                                            case 'PTERIGIÓN':
                                            case 'PTERIGION':
                                            case 'pterigion':
                                            case 'pterigión':
                                            case 'Pterigion':
                                            case 'Pterigión':
                                                $action = 'insertar_hoja_pterigion.php';
                                                break;
                                            case 'CIRUGÍA REFRACTIVA':
                                            case 'CIRUGIA REFRACTIVA':
                                            case 'cirugia refractiva':
                                            case 'cirugía refractiva':
                                            case 'Cirugia Refractiva':
                                            case 'Cirugía Refractiva':
                                                $action = 'insertar_hoja_refractiva.php';
                                                break;
                                            case 'TRANSPLANTE':
                                            case 'transplante':
                                            case 'Transplante':
                                                $action = 'insertar_hoja_transplante.php';
                                                break;
                                            case 'VALVULA DE AHMED':
                                            case 'VÁLVULA DE AHMED':
                                            case 'valvula de ahmed':
                                            case 'válvula de ahmed':
                                            case 'Valvula de Ahmed':
                                            case 'Válvula de Ahmed':
                                                $action = 'insertar_hoja_valvula_ahmed.php';
                                                break;
                                            case 'VITRECTOMIA':
                                            case 'vitrectomia':
                                            case 'Vitrectomia':
                                                $action = 'insertar_hoja_vitrectomia.php';
                                                break;
                                            case 'CIRUGÍA LASIK':
                                            case 'CIRUGIA LASIK':
                                            case 'cirugia lasik':
                                            case 'cirugía lasik':
                                            case 'Cirugia Lasik':
                                            case 'Cirugía Lasik':
                                                $action = 'insertar_hoja_lasik.php';
                                                break;
                                            default:
                                                $action = '#';
                                                break;
                                        }
                                        $forms .= '<div id="' . $collapseId . '" class="collapse' . ($first ? ' show' : '') . '" data-parent="#surgeryAccordion">';
                                        $forms .= '<div class="card-body">';
                                        $forms .= '<div style="text-align:center; color:#2b2d7f; font-size:22px; font-weight:bold; margin-bottom:10px;">' . htmlspecialchars(strtoupper($tipo)) . '</div>';
                                        ob_start();
                                    ?>
                                        <form action="<?php echo $action; ?>" method="POST" onsubmit="return checkSubmit();">
                                            <div class="form-group">
                                                <label style="font-size:16px;">Nombre del médico tratante:</label>
                                                <select class="form-control" name="medico_tratante" required>
                                                    <option value="">Seleccione un médico tratante</option>
                                                    <?php
                                                    $sql_med = "SELECT id_usua, nombre, papell, sapell FROM reg_usuarios WHERE cargp LIKE '%MEDICO%' AND u_activo = 'SI'";
                                                    $result_med = $conexion->query($sql_med);
                                                    if ($result_med && $result_med->num_rows > 0) {
                                                        while ($med = $result_med->fetch_assoc()) {
                                                            $nombre_med = trim($med['nombre'] . ' ' . $med['papell'] . ' ' . $med['sapell']);
                                                            echo '<option value="' . htmlspecialchars($nombre_med) . '">' . htmlspecialchars($nombre_med) . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label style="font-size:16px;">Anestesiólogo:</label>
                                                <select class="form-control" name="anestesiologo" required>
                                                    <option value="">Seleccione un anestesiólogo</option>
                                                    <?php
                                                    $sql_anes = "SELECT id_usua, nombre, papell, sapell FROM reg_usuarios WHERE cargp LIKE '%ANESTESIOLOGO%' AND u_activo = 'SI'";
                                                    $result_anes = $conexion->query($sql_anes);
                                                    if ($result_anes && $result_anes->num_rows > 0) {
                                                        while ($anes = $result_anes->fetch_assoc()) {
                                                            $nombre_anes = trim($anes['nombre'] . ' ' . $anes['papell'] . ' ' . $anes['sapell']);
                                                            echo '<option value="' . htmlspecialchars($nombre_anes) . '">' . htmlspecialchars($nombre_anes) . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label style="font-size:16px;">Anestesia:</label>
                                                <select class="form-control" name="anestesia" required>
                                                    <option value="">Seleccione tipo de anestesia</option>
                                                    <option value="LOCAL">LOCAL</option>
                                                    <option value="SEDACIÓN">SEDACIÓN</option>
                                                </select>
                                            </div>
                                            <?php if (strtoupper($tipo) == 'CIRUGÍA LASIK' || strtoupper($tipo) == 'CIRUGIA LASIK') { ?>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label style="font-size:16px;">OD</label>
                                                        <input type="text" class="form-control mb-1" name="od_queratometria" placeholder="QUERATOMETRIA">
                                                        <input type="text" class="form-control mb-1" name="od_microqueratomo" placeholder="MICROQUERATOMO">
                                                        <input type="text" class="form-control mb-1" name="od_anillo" placeholder="ANILLO">
                                                        <input type="text" class="form-control mb-1" name="od_tope" placeholder="TOPE">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label style="font-size:16px;">OI</label>
                                                        <input type="text" class="form-control mb-1" name="oi_queratometria" placeholder="QUERATOMETRIA">
                                                        <input type="text" class="form-control mb-1" name="oi_microqueratomo" placeholder="MICROQUERATOMO">
                                                        <input type="text" class="form-control mb-1" name="oi_anillo" placeholder="ANILLO">
                                                        <input type="text" class="form-control mb-1" name="oi_tope" placeholder="TOPE">
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <div class="table-responsive">
                                                <table class="table table-bordered" id="tabla-signos">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>Presión arterial</th>
                                                            <th>Frecuencia respiratoria</th>
                                                            <th>Temperatura</th>
                                                            <th>Oxigenación</th>
                                                            <th>Glucometría</th>
                                                            <th>Frecuencia cardiaca</th>
                                                            <th>Hora</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Antes de la cirugía<br>preoperatoria</td>
                                                            <td><input type="text" class="form-control" name="pa_pre"></td>
                                                            <td><input type="text" class="form-control" name="fr_pre"></td>
                                                            <td><input type="text" class="form-control" name="temp_pre"></td>
                                                            <td><input type="text" class="form-control" name="oxi_pre"></td>
                                                            <td><input type="text" class="form-control" name="gluco_pre"></td>
                                                            <td><input type="text" class="form-control" name="fc_pre"></td>
                                                            <td><input type="time" class="form-control" name="hora_pre"></td>
                                                        </tr>
                                                        <tr class="durante-cirugia-row">
                                                            <td>Durante la cirugía</td>
                                                            <td><input type="text" class="form-control" name="pa_dur[]"></td>
                                                            <td><input type="text" class="form-control" name="fr_dur[]"></td>
                                                            <td><input type="text" class="form-control" name="temp_dur[]"></td>
                                                            <td><input type="text" class="form-control" name="oxi_dur[]"></td>
                                                            <td><input type="text" class="form-control" name="gluco_dur[]"></td>
                                                            <td><input type="text" class="form-control" name="fc_dur[]"></td>
                                                            <td><input type="time" class="form-control" name="hora_dur[]"></td>
                                                        </tr>
                                                        <!-- Aquí se agregarán más filas de Durante la cirugía -->
                                                        <tr>
                                                            <td>Después de la cirugía<br>postoperatoria</td>
                                                            <td><input type="text" class="form-control" name="pa_post"></td>
                                                            <td><input type="text" class="form-control" name="fr_post"></td>
                                                            <td><input type="text" class="form-control" name="temp_post"></td>
                                                            <td><input type="text" class="form-control" name="oxi_post"></td>
                                                            <td><input type="text" class="form-control" name="gluco_post"></td>
                                                            <td><input type="text" class="form-control" name="fc_post"></td>
                                                            <td><input type="time" class="form-control" name="hora_post"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <button type="button" class="btn btn-info btn-sm mt-2 agregar-signos"><i class="fa-solid fa-heart-circle-plus"></i>Agregar signos</button>
                                            </div>
                                            <div class="form-group mt-3">
                                                <label style="font-size:16px;">Nota de enfermería:</label>
                                                <div class="botones mb-2">
                                                    <button type="button" class="btn btn-danger btn-sm" id="grabar_motivo"><i class="fas fa-microphone"></i></button>
                                                    <button type="button" class="btn btn-primary btn-sm" id="detener_motivo"><i class="fas fa-microphone-slash"></i></button>
                                                    <button type="button" class="btn btn-success btn-sm" id="reproducir_motivo"><i class="fas fa-play"></i></button>
                                                </div>
                                                <textarea class="form-control" id="nota_enfermeria" rows="5" name="nota_enfermeria"><?php echo ($nota !== null ? htmlspecialchars($nota) : ''); ?></textarea>
                                            </div>
                                            <?php if (strtoupper($tipo) == 'CIRUGÍA LASIK' || strtoupper($tipo) == 'CIRUGIA LASIK') { ?>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label style="font-size:16px;">ENFERMERA RESPONSABLE:</label>
                                                        <select class="form-control" name="enfermera_responsable" required>
                                                            <option value="">Seleccione una enfermera</option>
                                                            <?php
                                                            $sql_enf = "SELECT id_usua, nombre, papell, sapell FROM reg_usuarios WHERE id_rol = 3 AND u_activo = 'SI'";
                                                            $result_enf = $conexion->query($sql_enf);
                                                            if ($result_enf && $result_enf->num_rows > 0) {
                                                                while ($enf = $result_enf->fetch_assoc()) {
                                                                    $nombre_completo = trim($enf['nombre'] . ' ' . $enf['papell'] . ' ' . $enf['sapell']);
                                                                    echo '<option value="' . htmlspecialchars($nombre_completo) . '">' . htmlspecialchars($nombre_completo) . '</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label style="font-size:16px;">MÉDICO RESPONSABLE:</label>
                                                        <select class="form-control" name="medico_responsable" required>
                                                            <option value="">Seleccione un médico responsable</option>
                                                            <?php
                                                            $sql_med = "SELECT id_usua, nombre, papell, sapell FROM reg_usuarios WHERE cargp LIKE '%MEDICO%' AND u_activo = 'SI'";
                                                            $result_med = $conexion->query($sql_med);
                                                            if ($result_med && $result_med->num_rows > 0) {
                                                                while ($med = $result_med->fetch_assoc()) {
                                                                    $nombre_med = trim($med['nombre'] . ' ' . $med['papell'] . ' ' . $med['sapell']);
                                                                    echo '<option value="' . htmlspecialchars($nombre_med) . '">' . htmlspecialchars($nombre_med) . '</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <div class="form-group">
                                                    <label style="font-size:16px;">ENFERMERA RESPONSABLE:</label>
                                                    <select class="form-control" name="enfermera_responsable" required>
                                                        <option value="">Seleccione una enfermera</option>
                                                        <?php
                                                        $sql_enf = "SELECT id_usua, nombre, papell, sapell FROM reg_usuarios WHERE id_rol = 3 AND u_activo = 'SI'";
                                                        $result_enf = $conexion->query($sql_enf);
                                                        if ($result_enf && $result_enf->num_rows > 0) {
                                                            while ($enf = $result_enf->fetch_assoc()) {
                                                                $nombre_completo = trim($enf['nombre'] . ' ' . $enf['papell'] . ' ' . $enf['sapell']);
                                                                echo '<option value="' . htmlspecialchars($nombre_completo) . '">' . htmlspecialchars($nombre_completo) . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            <?php } ?>
                                            <center class="mt-3">
                                                <button type="submit" class="btn btn-primary">Firmar</button>
                                                <button type="button" class="btn btn-danger" onclick="history.back()">Cancelar</button>
                                            </center>
                                        </form>
                                    <?php
                                        $forms .= ob_get_clean();
                                        $forms .= '</div></div>';
                                    }
                                    // Imprimir menú y formularios
                                    echo $menu;
                                    echo $forms;
                                    ?>

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
                                    <script>
                                        function addInput(name, el) {
                                            var div = document.createElement('div');
                                            div.className = "mt-2";
                                            div.innerHTML = '<input type="text" class="form-control" name="' + name + '[]" placeholder="' + el.parentNode.previousElementSibling.firstElementChild.placeholder + '">';
                                            el.parentNode.parentNode.parentNode.insertBefore(div, el.parentNode.parentNode.nextSibling);
                                        }
                                    </script>
                                    <footer class="main-footer">
                                        <div style="font-size:16px;">
                                            <?php include("../../template/footer.php"); ?>
                                        </div>
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
                                        $(document).ready(function() {
                                            // Asegura que solo un formulario se muestre a la vez
                                            $('#btnBlefaro').click(function() {
                                                $('#collapseBlefaro').collapse('toggle');
                                                // Cierra los demás formularios si están abiertos
                                                $(".collapse").not('#collapseBlefaro').collapse('hide');
                                            });
                                        });
                                    </script>
                                    <script>
                                        // Script universal para agregar filas dinámicamente en cualquier tabla de signos vitales de procedimientos
                                        document.addEventListener('DOMContentLoaded', function() {
                                            document.querySelectorAll('.agregar-signos').forEach(function(btn) {
                                                btn.addEventListener('click', function() {
                                                    // Buscar la tabla de signos vitales más cercana al botón
                                                    var cardBody = btn.closest('.card-body');
                                                    if (!cardBody) return;
                                                    var tabla = cardBody.querySelector('table[id^="tabla-signos"]');
                                                    if (!tabla) return;
                                                    var tbody = tabla.getElementsByTagName('tbody')[0];
                                                    var filas = tbody.getElementsByTagName('tr');
                                                    var indexPost = filas.length - 1; // Última fila es postoperatoria
                                                    var nuevaFila = document.createElement('tr');
                                                    nuevaFila.innerHTML = `
                <td>Durante la cirugía</td>
                <td><input type="text" class="form-control" name="pa_dur[]"></td>
                <td><input type="text" class="form-control" name="fr_dur[]"></td>
                <td><input type="text" class="form-control" name="temp_dur[]"></td>
                <td><input type="text" class="form-control" name="oxi_dur[]"></td>
                <td><input type="text" class="form-control" name="gluco_dur[]"></td>
                <td><input type="text" class="form-control" name="fc_dur[]"></td>
                <td><input type="time" class="form-control" name="hora_dur[]"></td>
            `;
                                                    tbody.insertBefore(nuevaFila, filas[indexPost]);
                                                });
                                            });
                                        });
                                    </script>
                                    <script>
                                        // Hace que los botones de grabar/detener/reproducir funcionen para todas las notas de enfermería
                                        document.querySelectorAll('.botones').forEach(function(botonesDiv) {
                                            const grabarBtn = botonesDiv.querySelector('button[id^="grabar_"]');
                                            const detenerBtn = botonesDiv.querySelector('button[id^="detener_"]');
                                            const reproducirBtn = botonesDiv.querySelector('button[id^="reproducir_"]');
                                            // Busca el textarea más cercano dentro del mismo form-group
                                            const campoNota = botonesDiv.parentElement.querySelector('textarea');

                                            if (!grabarBtn || !detenerBtn || !reproducirBtn || !campoNota) return;

                                            reproducirBtn.addEventListener('click', () => {
                                                const speech = new SpeechSynthesisUtterance(campoNota.value);
                                                window.speechSynthesis.speak(speech);
                                            });

                                            let reconocimiento;
                                            if (window.webkitSpeechRecognition) {
                                                reconocimiento = new webkitSpeechRecognition();
                                                reconocimiento.lang = "es-ES";
                                                reconocimiento.continuous = true;
                                                reconocimiento.interimResults = false;

                                                reconocimiento.onresult = (event) => {
                                                    const results = event.results;
                                                    const frase = results[results.length - 1][0].transcript;
                                                    campoNota.value += frase + ' ';
                                                };
                                            }

                                            grabarBtn.addEventListener('click', () => {
                                                if (reconocimiento) reconocimiento.start();
                                            });
                                            detenerBtn.addEventListener('click', () => {
                                                if (reconocimiento) reconocimiento.abort();
                                            });
                                        });
                                    </script>
                                    <?php
