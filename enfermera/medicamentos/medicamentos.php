<?php
session_start();
include "../../conexionbd.php";
$conexion = ConexionBD::getInstancia()->getConexion();
include "../header_enfermera.php";

// PROCESAR REGISTRO DE MEDICAMENTO
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['guardar_medicamento'])) {
    $id_atencion = $_SESSION['pac'];
    $hora_med = $_POST['hora_med'];
    $medicamento = $_POST['medicamento'];
    $dosis = $_POST['dosis'];
    $unidad = $_POST['unidad'];
    $via = $_POST['via'];
    $frecuencia = $_POST['frecuencia'];
    $otros = $_POST['otros'];
    $fecha_registro = date('Y-m-d H:i:s');
    $usuario = $_SESSION['usuario'] ?? 'Enfermera'; // Asume que tienes el usuario en sesión

    $sql_insert = "INSERT INTO medicamentos_administrados 
                   (id_atencion, fecha_registro, hora_med, medicamento, dosis, unidad, via, frecuencia, otros, usuario) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql_insert);
    $stmt->bind_param("isssssssss", $id_atencion, $fecha_registro, $hora_med, $medicamento, $dosis, $unidad, $via, $frecuencia, $otros, $usuario);

    if ($stmt->execute()) {
        echo '<script>alert("Medicamento registrado exitosamente");</script>';
    } else {
        echo '<script>alert("Error al registrar medicamento");</script>';
    }
    $stmt->close();
}

$resultado = $conexion->query("SELECT * FROM reg_usuarios") or die($conexion->error);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro de medicamentos</title>

    <!-- ICONS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">

    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <!-- BOOTSTRAP SELECT -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">

    <!-- SCRIPTS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

    <style>
        #contenido,
        #contenido3,
        #contenido4 {
            display: none;
        }

        td.fondo {
            background-color: #17a2b8 !important;
        }

        td.fondo2 {
            background-color: green !important;
            color: white;
        }

        .thead {
            background-color: #2b2d7f;
            color: white;
        }

        .historial-table {
            margin-top: 30px;
        }

        .btn-guardar {
            margin-top: 10px;
            margin-bottom: 20px;
        }
    </style>

    <script>
        $(document).ready(function () {
            $("#search, #search_dep").keyup(function () {
                let value = $(this).val().toLowerCase();
                $("#mytable tbody tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            // Búsqueda en historial
            $("#search_historial").keyup(function () {
                let value = $(this).val().toLowerCase();
                $("#historial_table tbody tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>

</head>

<body>

<section class="content container-fluid">

    <?php
    if (isset($_SESSION['pac'])) {
        $id_atencion = $_SESSION['pac'];

        // --- DATOS PACIENTE ---
        $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, 
                       p.Id_exp, p.tel, p.fecnac, p.tip_san, di.fecha, di.area, 
                       di.alta_med, p.sexo, di.alergias, p.folio
                FROM paciente p 
                JOIN dat_ingreso di ON p.Id_exp = di.Id_exp 
                WHERE di.id_atencion = $id_atencion";

        $result_pac = $conexion->query($sql_pac);
        while ($row_pac = $result_pac->fetch_assoc()) {
            $pac_papell = $row_pac['papell'];
            $pac_sapell = $row_pac['sapell'];
            $pac_nom_pac = $row_pac['nom_pac'];
            $pac_fecnac = $row_pac['fecnac'];
            $pac_fecing = $row_pac['fecha'];
            $pac_tip_sang = $row_pac['tip_san'];
            $pac_sexo = $row_pac['sexo'];
            $area = $row_pac['area'];
            $id_exp = $row_pac['Id_exp'];
            $alergias = $row_pac['alergias'];
            $folio = $row_pac['folio'];
        }

        // --- INGRESO ---
        $sql_pac2 = "SELECT * FROM dat_ingreso WHERE id_atencion = $id_atencion";
        $result_pac2 = $conexion->query($sql_pac2);
        while ($row = $result_pac2->fetch_assoc()) {
            $fingreso = $row['fecha'];
            $fegreso = $row['fec_egreso'];
            $alta_med = $row['alta_med'];
            $alta_adm = $row['alta_adm'];
            $activo = $row['activo'];
            $valida = $row['valida'];
        }

        // --- CALCULAR ESTANCIA ---
        if ($alta_med == 'SI' && $alta_adm == 'SI' && $activo == 'NO' && $valida == 'SI') {
            $sql_est = "SELECT DATEDIFF('$fegreso', '$fingreso') AS estancia FROM dat_ingreso WHERE id_atencion=$id_atencion";
        } else {
            $sql_now = "SELECT DATE_ADD(NOW(), INTERVAL 12 HOUR) AS dat_now FROM dat_ingreso WHERE id_atencion=$id_atencion";
            $dat_now = $conexion->query($sql_now)->fetch_assoc()['dat_now'];

            $sql_est = "SELECT DATEDIFF('$dat_now', fecha) AS estancia FROM dat_ingreso WHERE id_atencion=$id_atencion";
        }

        $estancia = $conexion->query($sql_est)->fetch_assoc()['estancia'];

        // --- CALCULAR EDAD ---
        function bisiesto($y) { return checkdate(2, 29, $y); }

        $fecha_actual = date("Y-m-d");
        list($anioA, $mesA, $diaA) = explode("-", $fecha_actual);
        list($anioN, $mesN, $diaN) = explode("-", $pac_fecnac);

        $anos = $anioA - $anioN;
        $meses = $mesA - $mesN;
        $dias = $diaA - $diaN;

        if ($dias < 0) {
            $meses--;
            $dias += cal_days_in_month(CAL_GREGORIAN, $mesA - 1, $anioA);
        }

        if ($meses < 0) {
            $anos--;
            $meses += 12;
        }

        ?>

        <div class="container">
            <div class="thead text-center py-2">
                <strong>CONSULTAR MEDICAMENTOS DEL PACIENTE</strong>
            </div>

            <font size="2">
                <div class="row mt-3">
                    <div class="col-sm-6">
                        Expediente: <strong><?= $folio ?></strong><br>
                        Paciente: <strong><?= "$pac_papell $pac_sapell $pac_nom_pac" ?></strong>
                    </div>

                    <div class="col-sm-3">
                        Área: <strong><?= $area ?></strong>
                    </div>

                    <div class="col-sm-3">
                        Fecha de ingreso: <strong><?= date("d-m-Y", strtotime($pac_fecing)) ?></strong>
                    </div>
                </div>
            </font>

            <font size="2">
                <div class="row mt-2">
                    <div class="col-sm-3">
                        Fecha nacimiento: <strong><?= date("d-m-Y", strtotime($pac_fecnac)) ?></strong>
                    </div>

                    <div class="col-sm-3">
                        Tipo sangre: <strong><?= $pac_tip_sang ?></strong>
                    </div>

                    <div class="col-sm-3">
                        Habitación: <strong>
                            <?php
                            $sql_hab = "SELECT num_cama FROM cat_camas WHERE id_atencion=$id_atencion";
                            $row = $conexion->query($sql_hab)->fetch_assoc();
                            echo $row['num_cama'];
                            ?>
                        </strong>
                    </div>

                    <div class="col-sm-3">
                        Estancia: <strong><?= $estancia ?> días</strong>
                    </div>
                </div>
            </font>

            <font size="2">
                <div class="row mt-2">
                    <div class="col-sm-3">
                        Edad:
                        <strong>
                            <?php
                            if ($anos > 0) echo "$anos años";
                            elseif ($meses > 0) echo "$meses meses";
                            else echo "$dias días";
                            ?>
                        </strong>
                    </div>

                    <div class="col-sm-3">
                        Peso:
                        <strong>
                            <?php
                            $sql = "SELECT peso FROM dat_hclinica WHERE Id_exp=$id_exp ORDER BY id_hc DESC LIMIT 1";
                            $peso = $conexion->query($sql)->fetch_assoc()['peso'] ?? 0;
                            echo $peso;
                            ?>
                        </strong>
                    </div>

                    <div class="col-sm-3">
                        Talla:
                        <strong>
                            <?php
                            $sql = "SELECT talla FROM dat_hclinica WHERE Id_exp=$id_exp ORDER BY id_hc DESC LIMIT 1";
                            $talla = $conexion->query($sql)->fetch_assoc()['talla'] ?? 0;
                            echo $talla;
                            ?>
                        </strong>
                    </div>

                    <div class="col-sm-3">
                        Género: <strong><?= $pac_sexo ?></strong>
                    </div>
                </div>
            </font>

            <font size="2">
                <div class="row mt-2">
                    <div class="col-sm-3">
                        Alergias: <strong><?= $alergias ?></strong>
                    </div>

                    <div class="col-sm-6">
                        Estado de salud:
                        <strong>
                            <?php
                            $sql = "SELECT edo_salud FROM dat_ingreso WHERE id_atencion=$id_atencion ORDER BY edo_salud DESC LIMIT 1";
                            echo $conexion->query($sql)->fetch_assoc()['edo_salud'];
                            ?>
                        </strong>
                    </div>

                    <div class="col-sm-3">
                        Aseguradora:
                        <strong>
                            <?php
                            $sql = "SELECT aseg FROM dat_financieros WHERE id_atencion=$id_atencion ORDER BY fecha DESC LIMIT 1";
                            echo $conexion->query($sql)->fetch_assoc()['aseg'];
                            ?>
                        </strong>
                    </div>
                </div>
            </font>

            <font size="2">
                <div class="col-sm-12 mt-2">
                    <?php
                    $d = $conexion->query("SELECT diagprob_i FROM dat_nevol WHERE id_atencion=$id_atencion ORDER BY diagprob_i ASC LIMIT 1")->fetch_assoc()['diagprob_i'] ?? "";
                    $m = $conexion->query("SELECT motivo_atn FROM dat_ingreso WHERE id_atencion=$id_atencion ORDER BY motivo_atn ASC LIMIT 1")->fetch_assoc()['motivo_atn'] ?? "";

                    if ($d) echo "Diagnóstico: <strong>$d</strong>";
                    else echo "Motivo de atención: <strong>$m</strong>";
                    ?>
                </div>
            </font>
        </div>

        <hr>

        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <a href="solmed_far.php" class="btn btn-info btn-block">
                        Solicitar medicamentos a farmacia
                    </a>
                </div>
            </div>
        </div>

        <br>

        <!-- FORMULARIO MEDICAMENTOS -->
        <div class="container">
            <form action="" method="POST" id="medicamentos">
                <table class="table table-bordered table-striped" id="mytable">
                    <thead class="thead">
                    <tr class="table-primary">
                        <th colspan="7" class="text-center">
                            <h5><strong>REGISTRAR MEDICAMENTOS ADMINISTRADOS AL PACIENTE</strong></h5>
                        </th>
                    </tr>

                    <tr class="table-active text-center">
                        <th>Hora</th>
                        <th>Medicamento</th>
                        <th>Dósis</th>
                        <th>Unidad de medida</th>
                        <th>Vía</th>
                        <th>Frecuencia</th>
                        <th>Otros</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td><input type="time" name="hora_med" class="form-control" required></td>
                        <td><input type="text" name="medicamento" class="form-control" required></td>
                        <td><input type="text" name="dosis" class="form-control" required></td>
                        <td><input type="text" name="unidad" class="form-control" required></td>
                        <td><input type="text" name="via" class="form-control" required></td>
                        <td><input type="text" name="frecuencia" class="form-control" required></td>
                        <td><input type="text" name="otros" class="form-control"></td>
                    </tr>
                    </tbody>
                </table>

                <div class="text-center btn-guardar">
                    <button type="submit" name="guardar_medicamento" class="btn btn-success btn-lg">
                        <i class="fas fa-save"></i> Guardar Medicamento
                    </button>
                </div>
            </form>
        </div>

        <!-- HISTORIAL DE MEDICAMENTOS -->
        <div class="container historial-table">
            <div class="thead text-center py-2">
                <strong>HISTORIAL DE MEDICAMENTOS ADMINISTRADOS</strong>
            </div>

            <div class="row mt-3 mb-3">
                <div class="col-sm-4">
                    <input type="text" id="search_historial" class="form-control" placeholder="Buscar en historial...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="historial_table">
                    <thead class="thead">
                    <tr class="text-center">
                        <th>Fecha/Hora Registro</th>
                        <th>Hora Administración</th>
                        <th>Medicamento</th>
                        <th>Dósis</th>
                        <th>Unidad</th>
                        <th>Vía</th>
                        <th>Frecuencia</th>
                        <th>Otros</th>
                        <th>Usuario</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    $sql_historial = "SELECT * FROM medicamentos_administrados 
                                     WHERE id_atencion = $id_atencion 
                                     ORDER BY fecha_registro DESC, hora_med DESC";
                    $result_historial = $conexion->query($sql_historial);

                    if ($result_historial && $result_historial->num_rows > 0) {
                        while ($row_hist = $result_historial->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . date("d-m-Y H:i", strtotime($row_hist['fecha_registro'])) . "</td>";
                            echo "<td class='text-center'>" . date("H:i", strtotime($row_hist['hora_med'])) . "</td>";
                            echo "<td>" . htmlspecialchars($row_hist['medicamento']) . "</td>";
                            echo "<td class='text-center'>" . htmlspecialchars($row_hist['dosis']) . "</td>";
                            echo "<td class='text-center'>" . htmlspecialchars($row_hist['unidad']) . "</td>";
                            echo "<td class='text-center'>" . htmlspecialchars($row_hist['via']) . "</td>";
                            echo "<td class='text-center'>" . htmlspecialchars($row_hist['frecuencia']) . "</td>";
                            echo "<td>" . htmlspecialchars($row_hist['otros']) . "</td>";
                            echo "<td class='text-center'>" . htmlspecialchars($row_hist['usuario']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' class='text-center'>No hay medicamentos registrados</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
    } else {
        echo '<script>window.location.href="../../template/select_pac_enf.php";</script>';
    }
    ?>

</section>
</body>
</html>