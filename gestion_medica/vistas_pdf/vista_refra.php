<?php
session_start();
include "../../conexionbd.php";
include "../header_medico.php";
$resultado = $conexion->query("select * from reg_usuarios") or die($conexion->error);

?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″ />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <!--  Bootstrap  -->
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

    <script>
        // Write on keyup event of keyword input element
        $(document).ready(function() {
            $("#search").keyup(function() {
                _this = this;
                // Show only matching TR, hide rest of them
                $.each($("#mytable tbody tr"), function() {
                    if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
                        $(this).hide();
                    else
                        $(this).show();
                });
            });
        });
    </script>
    <script>
        // Write on keyup event of keyword input element
        $(document).ready(function() {
            $("#search_dep").keyup(function() {
                _this = this;
                // Show only matching TR, hide rest of them
                $.each($("#mytable tbody tr"), function() {
                    if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
                        $(this).hide();
                    else
                        $(this).show();
                });
            });
        });
    </script>
    <title>Vista Exploracion Fisica</title>
    <style type="text/css">
        #contenido {
            display: none;
        }

        #contenido3 {
            display: none;
        }

        #contenido4 {
            display: none;
        }
    </style>

</head>

<body>
    <div class="container">
        <button type="button" class="btn btn-danger btn-sm" onclick="history.back()">Regresar...</button>
        <hr>
        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 22px;"><strong>
                <center>
                    IMPRESIÓN DE NOTA DE REFRACCION ACTUAL </center>
            </strong></div>
        <section class="content container-fluid">
            <?php

            include "../../conexionbd.php";

            if (isset($_SESSION['hospital'])) {
                $id_atencion = $_SESSION['hospital'];

                $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.folio, p.tel, p.fecnac,p.tip_san, di.fecha, di.area, di.alta_med, p.sexo, di.alergias FROM paciente p, dat_ingreso di WHERE p.Id_exp=di.Id_exp and di.id_atencion =$id_atencion";

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
                    $folio = $row_pac['folio'];
                    $alergias = $row_pac['alergias'];
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

                <div class="row">
                    <div class="col-sm-2">
                        Expediente: <strong><?php echo $folio ?> </strong>
                    </div>
                    <div class="col-sm-6">
                        Paciente: <strong><?php echo $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac ?></strong>
                    </div>

                    <?php $date = date_create($pac_fecing);
                    ?>
                    <div class="col-sm-4">
                        Fecha de ingreso: <strong><?php echo date_format($date, "d/m/Y H:i:s") ?></strong>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-4">
                        <?php $date1 = date_create($pac_fecnac);
                        ?>
                        <!-- INICIO DE FUNCION DE CALCULAR EDAD -->
                        <?php

                        function bisiesto($anio_actual)
                        {
                            $bisiesto = false;
                            //probamos si el mes de febrero del año actual tiene 29 días
                            if (checkdate(2, 29, $anio_actual)) {
                                $bisiesto = true;
                            }
                            return $bisiesto;
                        }


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
                                    if (bisiesto($array_actual[0])) {
                                        $dias_mes_anterior = 29;
                                        break;
                                    } else {
                                        $dias_mes_anterior = 28;
                                        break;
                                    }

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


                        ?>
                        <!-- TERMINO DE FUNCION DE CALCULAR EDAD -->
                        Fecha de nacimiento: <strong><?php echo date_format($date1, "d/m/Y") ?></strong>
                    </div>
                    <div class="col-sm-4">
                        Edad: <strong><?php if ($anos > "0") {
                                            echo $anos . " años";
                                        } elseif ($anos <= "0" && $meses > "0") {
                                            echo $meses . " meses";
                                        } elseif ($anos <= "0" && $meses <= "0" && $dias > "0") {
                                            echo $dias . " días";
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
                        Días estancia: <strong><?php echo $estancia ?> Dias</strong>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-4">
                        Alergias: <strong><?php echo $alergias ?></strong>
                    </div>


                </div>

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

                <div class="row">
                    <div class="col-sm-4">
                        Peso: <strong><?php echo $peso ?></strong>
                    </div>
                    <div class="col-sm-3">
                        Talla: <strong><?php echo $talla ?></strong>
                    </div>
                </div>



            <?php
            } else {
                echo '<script type="text/javascript"> window.location.href="../lista_pacientes/lista_pacientes.php";</script>';
            }
            ?>
            <hr>

            <body>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col col-12">
                            <h2>
                                <a href="" data-target="#sidebar" data-toggle="collapse" class="d-md-none"><i class="fa fa-bars" id="side"></i></a>

                            </h2>

                            <div class="form-group">
                                <input type="text" class="form-control pull-right" style="width:20%" id="search" placeholder="Buscar...">
                            </div>
                            <?php

                            include "../../conexionbd.php";
                            $id_atencion = $_SESSION['hospital'];
                            $resultado = $conexion->query("SELECT * FROM refraccion_actual WHERE id_atencion=$id_atencion ORDER BY id DESC") or die($conexion->error);
                            $usuario = $_SESSION['login'];

                            ?>
                            <div class="table-containt">

                                <table class="table table-bordered table-striped" id="mytable">
                                    <thead class="thead bg-navy">
                                        <tr>
                                            <th scope="col">PDF</th>

                                            <th scope="col">Fecha</th>
                                            <th scope="col">Hora</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        while ($f = mysqli_fetch_array($resultado)) {
                                            $id = $f['id'];

                                        ?>
                                            <tr>
                                                <td>
                                                    <a href="../pdf/pdf_refra.php?id=<?php echo $id; ?>&id_exp=<?php echo $id_exp ?>&id_atencion=<?php echo $id_atencion ?>"
                                                        target="_blank">
                                                        <button type="button" class="btn btn-danger">
                                                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                        </button>
                                                    </a>
                                                </td>
                                                <td><strong><?php $date = date_create($f['fecha']);
                                                            echo date_format($date, "d/m/Y"); ?></strong></td>
                                                <td><strong><?php $date = date_create($f['fecha']);
                                                            echo date_format($date, "H:i") . ' horas'; ?></strong></td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>

                                </table>
                            </div>

                        </div>
                    </div>
                </div>
    </div>
    </div>
    <footer class="main-footer">
        <?php
        include("../../template/footer.php");
        ?>
    </footer>

    <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- FastClick -->
    <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

</body>

</html>