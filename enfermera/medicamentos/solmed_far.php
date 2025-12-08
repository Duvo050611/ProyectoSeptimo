<?php
session_start();
include "../../conexionbd.php";
$conexion = ConexionBD::getInstancia()->getConexion();
include "../header_enfermera.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- bootstrap-select CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">

    <!-- jQuery (single include) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
            integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

    <!-- Popper.js and Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <!-- bootstrap-select JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

    <script>
        // Live search for the table
        $(document).ready(function() {
            $("#search").keyup(function() {
                var _this = this;
                $.each($("#mytable tbody tr"), function() {
                    if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
                        $(this).hide();
                    else
                        $(this).show();
                });
            });

            // initialize bootstrap-select (if used)
            try {
                $('.selectpicker').selectpicker();
            } catch (e) {
                // bootstrap-select not available or init failed
                console.warn('selectpicker init error', e);
            }
        });
    </script>

        <style>
             /* Pequeños ajustes visuales */
         .patient-info {
             font-size: 0.9rem;
         }

        /* Estilo Cyberpunk para encabezados */
        .thead-custom {
            background: linear-gradient(135deg, #0f0f23 0%, #1a1a3e 50%, #0f0f23 100%);
            color: #00ffff;
            text-shadow: 0 0 10px #00ffff, 0 0 20px #00ffff, 0 0 30px #00ffff;
            border: 2px solid #00ffff;
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.5), inset 0 0 20px rgba(0, 255, 255, 0.1);
            font-weight: bold;
            letter-spacing: 1px;
        }

        /* Tablas con estilo cyberpunk */
        .table {
            background-color: #0a0a1a;
            border: 2px solid #0004ff;
            box-shadow: 0 0 30px rgba(255, 0, 255, 0.4);
        }

        .table thead th {
            background: linear-gradient(135deg, #1a0033 0%, #330066 100%);
            color: #00ff80;
            border: 1px solid #ff00ff;
            text-shadow: 0 0 8px #ff00ff;
            font-weight: bold;
            padding: 15px;
        }

        .table tbody tr {
            background: linear-gradient(90deg, #0d0d1f 0%, #1a1a2e 100%);
            border-bottom: 1px solid rgba(0, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: linear-gradient(90deg, #16213e 0%, #1f4068 100%);
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.6);
            transform: translateX(5px);
        }

        .table tbody td {
            color: #00ffff;
            border: 1px solid rgba(0, 255, 255, 0.2);
            padding: 12px;
            vertical-align: middle;
        }

        /* Efecto neon para textos importantes */
        .table tbody td strong,
        .patient-info strong {
            color: white;
            text-shadow: 0 0 5px #00ffd0;
        }

        /* Estilo para el contenedor de la tabla */
        .table-responsive {
            background: #000;
            border: 2px solid #00ffff;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 0 40px rgba(0, 255, 255, 0.3), inset 0 0 40px rgba(255, 0, 255, 0.1);
        }

        /* Botones con estilo cyberpunk */
        .btn-danger {
            background: linear-gradient(135deg, #8b0000 0%, #ff0055 100%);
            border: 2px solid #ff0055;
            box-shadow: 0 0 15px rgba(255, 0, 85, 0.5);
            color: #fff;
            text-shadow: 0 0 5px #fff;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #ff0055 0%, #ff3377 100%);
            box-shadow: 0 0 25px rgba(255, 0, 85, 0.8);
            transform: scale(1.05);
        }

        .btn-success {
            background: linear-gradient(135deg, #00ff88 0%, #00cc66 100%);
            border: 2px solid #00ff88;
            box-shadow: 0 0 15px rgba(0, 255, 136, 0.5);
            color: #000;
            font-weight: bold;
            text-shadow: 0 0 5px rgba(0, 255, 136, 0.8);
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #00ffaa 0%, #00ff88 100%);
            box-shadow: 0 0 25px rgba(0, 255, 136, 0.8);
            transform: scale(1.05);
            color: #000;
        }

        /* Container boxes */
        .container.box {
            background: linear-gradient(135deg, #0a0a1a 0%, #1a1a2e 100%);
            border: 2px solid #00ffff;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 0 30px rgba(0, 255, 255, 0.3);
        }

        /* Headers de sección */
        .container.box h4,
        .container.box h5 {
            color: #00fff7;
            text-shadow: 0 0 10px #00eaff, 0 0 20px #ff00ff;
            font-weight: bold;
            letter-spacing: 2px;
        }

        /* Inputs con estilo cyberpunk */
        .form-control {
            background: #0a0a1a;
            border: 2px solid #00ffff;
            color: #00ffff;
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: #0f0f1f;
            border-color: #0080ff;
            box-shadow: 0 0 20px rgba(255, 0, 255, 0.5);
            color: #ff00ff;
        }

        .form-control:disabled {
            background: #1a1a2e;
            border-color: #555;
            color: #888;
        }

        /* Select picker */
        .bootstrap-select .dropdown-toggle {
            background: #0a0a1a !important;
            border: 2px solid #00ffff !important;
            color: #00ffff !important;
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.2);
        }

        .bootstrap-select .dropdown-menu {
            background: #0a0a1a;
            border: 2px solid #00ffff;
        }

        .bootstrap-select .dropdown-menu li a {
            color: #00ffff;
        }

        .bootstrap-select .dropdown-menu li a:hover {
            background: #1a1a3e;
            color: #000dff;
        }

        /* Labels */
        label {
            color: #00ffff;
            font-weight: 500;
            text-shadow: 0 0 5px rgba(0, 255, 255, 0.5);
        }

        /* Animación de brillo para los bordes */
        @keyframes glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(0, 255, 255, 0.4);
            }
            50% {
                box-shadow: 0 0 40px rgba(17, 0, 255, 0.6);
            }
        }

        .table-responsive,
        .container.box {
            animation: glow 3s ease-in-out infinite;
        }
    </style>
</head>
<body>
<?php
if (isset($_SESSION['pac'])) {
    $id_atencion = $_SESSION['pac'];
    $usuario = $_SESSION['login'];
    $usuario2 = $usuario['id_usua'];

    // Inicializar $tr por si acaso
    $tr = 1;

    // Consulta paciente
    $sql_pac = "
      SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.tel, p.fecnac, p.tip_san,
             di.fecha, di.area, di.alta_med, p.sexo, di.alergias, di.activo, p.folio
      FROM paciente p
      INNER JOIN dat_ingreso di ON p.Id_exp = di.Id_exp
      WHERE di.id_atencion = $id_atencion
    ";
    $result_pac = $conexion->query($sql_pac) or die($conexion->error);
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
        $activo = $row_pac['activo'];
        $id_exp = $row_pac['Id_exp'];
        $alergias = $row_pac['alergias'];
        $folio = $row_pac['folio'];
    }

    // Datos adicionales dat_ingreso
    $sql_pac2 = "SELECT * FROM dat_ingreso WHERE id_atencion = $id_atencion";
    $result_pac2 = $conexion->query($sql_pac2) or die($conexion->error);
    while ($row_pac2 = $result_pac2->fetch_assoc()) {
        $fingreso = $row_pac2['fecha'];
        $fegreso = $row_pac2['fec_egreso'];
        $alta_med = $row_pac2['alta_med'];
        $alta_adm = $row_pac2['alta_adm'];
        $activo = $row_pac2['activo'];
        $valida = $row_pac2['valida'];
    }

    // Estancia
    if ($alta_med == 'SI' && $alta_adm == 'SI' && $activo == 'NO' && $valida == 'SI') {
        $sql_est = "SELECT DATEDIFF('$fegreso', '$fingreso') as estancia FROM dat_ingreso WHERE id_atencion = $id_atencion";
        $result_est = $conexion->query($sql_est) or die($conexion->error);
        while ($row_est = $result_est->fetch_assoc()) {
            $estancia = $row_est['estancia'];
        }
    } else {
        $sql_now = "SELECT DATE_ADD(NOW(), INTERVAL 12 HOUR) as dat_now FROM dat_ingreso WHERE id_atencion = $id_atencion";
        $result_now = $conexion->query($sql_now) or die($conexion->error);
        while ($row_now = $result_now->fetch_assoc()) {
            $dat_now = $row_now['dat_now'];
        }
        $sql_est = "SELECT DATEDIFF('$dat_now', fecha) as estancia FROM dat_ingreso WHERE id_atencion = $id_atencion";
        $result_est = $conexion->query($sql_est) or die($conexion->error);
        while ($row_est = $result_est->fetch_assoc()) {
            $estancia = $row_est['estancia'];
        }
    }

    function bisiesto($anio_actual) {
        return checkdate(2, 29, $anio_actual);
    }

    date_default_timezone_set('America/Guatemala');
    $fecha_actual = date("Y-m-d");
    $fecha_nac = $pac_fecnac ?? '';
    $fecha_de_nacimiento = strval($fecha_nac);
    $array_nacimiento = explode("-", $fecha_de_nacimiento);
    $array_actual = explode("-", $fecha_actual);

    $anos = isset($array_nacimiento[0]) ? ($array_actual[0] - $array_nacimiento[0]) : 0;
    $meses = isset($array_nacimiento[1]) ? ($array_actual[1] - $array_nacimiento[1]) : 0;
    $dias = isset($array_nacimiento[2]) ? ($array_actual[2] - $array_nacimiento[2]) : 0;

    if ($dias < 0) {
        --$meses;
        switch ($array_actual[1]) {
            case 1:  $dias_mes_anterior = 31; break;
            case 2:  $dias_mes_anterior = 31; break;
            case 3:  $dias_mes_anterior = bisiesto($array_actual[0]) ? 29 : 28; break;
            case 4:  $dias_mes_anterior = 31; break;
            case 5:  $dias_mes_anterior = 30; break;
            case 6:  $dias_mes_anterior = 31; break;
            case 7:  $dias_mes_anterior = 30; break;
            case 8:  $dias_mes_anterior = 31; break;
            case 9:  $dias_mes_anterior = 31; break;
            case 10: $dias_mes_anterior = 30; break;
            case 11: $dias_mes_anterior = 31; break;
            case 12: $dias_mes_anterior = 30; break;
            default: $dias_mes_anterior = 30; break;
        }
        $dias = $dias + $dias_mes_anterior;
    }
    if ($meses < 0) {
        --$anos;
        $meses = $meses + 12;
    }
    // fin calculo edad
    ?>
    <div class="container mt-3">
        <div class="row">
            <div class="col-sm-4">
                <a class="btn btn-danger" href="../../template/menu_enfermera.php">Regresar</a>
            </div>
        </div>
    </div>

    <br>

    <div class="container">
        <div class="thead-custom p-2 mb-2"><strong class="d-block text-center">SOLICITAR MEDICAMENTOS A FARMACIA</strong></div>

        <div class="patient-info mb-2">
            <div class="row">
                <div class="col-sm-6">
                    Expediente: <strong><?php echo htmlspecialchars($folio ?? '') ?></strong><br>
                    Paciente: <strong><?php echo htmlspecialchars(($pac_papell ?? '') . ' ' . ($pac_sapell ?? '') . ' ' . ($pac_nom_pac ?? '')) ?></strong>
                </div>
                <div class="col-sm">
                    Área: <strong><?php echo htmlspecialchars($area ?? '') ?></strong>
                </div>
                <?php $date = isset($pac_fecing) ? date_create($pac_fecing) : null; ?>
                <div class="col-sm">
                    Fecha de ingreso: <strong><?php echo $date ? date_format($date, "d/m/Y") : '' ?></strong>
                </div>
            </div>
        </div>

        <div class="patient-info mb-2">
            <div class="row">
                <?php $date1 = isset($pac_fecnac) ? date_create($pac_fecnac) : null; ?>
                <div class="col-sm">
                    Fecha de nacimiento: <strong><?php echo $date1 ? date_format($date1, "d/m/Y") : '' ?></strong>
                </div>
                <div class="col-sm">
                    Tipo de sangre: <strong><?php echo htmlspecialchars($pac_tip_sang ?? '') ?></strong>
                </div>
                <div class="col-sm">
                    Habitación: <strong>
                        <?php
                        $sql_hab = "SELECT num_cama FROM cat_camas WHERE id_atencion = $id_atencion";
                        $result_hab = $conexion->query($sql_hab);
                        while ($row_hab = $result_hab->fetch_assoc()) {
                            echo htmlspecialchars($row_hab['num_cama']);
                        }
                        ?>
                    </strong>
                </div>
                <div class="col-sm">
                    Tiempo estancia: <strong><?php echo htmlspecialchars($estancia ?? '') ?> Dias</strong>
                </div>
            </div>
        </div>

        <div class="patient-info mb-2">
            <div class="row">
                <div class="col-sm-3">
                    Edad: <strong>
                        <?php
                        if ($anos > 0) {
                            echo $anos . " años";
                        } elseif ($anos <= 0 && $meses > 0) {
                            echo $meses . " meses";
                        } elseif ($anos <= 0 && $meses <= 0 && $dias > 0) {
                            echo $dias . " días";
                        } else {
                            echo "0 días";
                        }
                        ?>
                    </strong>
                </div>
                <div class="col-sm-3">
                    Peso: <strong>
                        <?php
                        $sql_vit = "SELECT * FROM dat_hclinica WHERE Id_exp = $id_exp ORDER BY id_hc DESC LIMIT 1";
                        $result_vit = $conexion->query($sql_vit);
                        $peso = 0;
                        while ($row_vit = $result_vit->fetch_assoc()) {
                            $peso = $row_vit['peso'];
                        }
                        echo htmlspecialchars($peso);
                        ?>
                    </strong>
                </div>
                <div class="col-sm">
                    Talla: <strong>
                        <?php
                        $sql_vitt = "SELECT * FROM dat_hclinica WHERE Id_exp = $id_exp ORDER BY id_hc DESC LIMIT 1";
                        $result_vitt = $conexion->query($sql_vitt);
                        $talla = 0;
                        while ($row_vitt = $result_vitt->fetch_assoc()) {
                            $talla = $row_vitt['talla'];
                        }
                        echo htmlspecialchars($talla);
                        ?>
                    </strong>
                </div>
                <div class="col-sm">
                    Género: <strong><?php echo htmlspecialchars($pac_sexo ?? '') ?></strong>
                </div>
            </div>
        </div>

        <div class="patient-info mb-3">
            <div class="row">
                <div class="col-sm-3">
                    Alergias: <strong><?php echo htmlspecialchars($alergias ?? '') ?></strong>
                </div>
                <div class="col-sm-6">
                    Estado de salud: <strong>
                        <?php
                        $sql_edo = "SELECT edo_salud FROM dat_ingreso WHERE id_atencion = $id_atencion ORDER BY edo_salud DESC LIMIT 1";
                        $result_edo = $conexion->query($sql_edo);
                        while ($row_edo = $result_edo->fetch_assoc()) {
                            echo htmlspecialchars($row_edo['edo_salud']);
                        }
                        ?>
                    </strong>
                </div>
                <div class="col-sm">
                    <label class="control-label">Aseguradora: </label>
                    <strong>&nbsp;
                        <?php
                        $sql_aseg = "SELECT aseg FROM dat_ingreso WHERE id_atencion = $id_atencion";
                        $result_aseg = $conexion->query($sql_aseg);
                        $at = '';
                        while ($row_aseg = $result_aseg->fetch_assoc()) {
                            echo htmlspecialchars($row_aseg['aseg']);
                            $at = $row_aseg['aseg'];
                        }
                        if ($at !== '') {
                            $resultadot = $conexion->query("SELECT tip_precio FROM cat_aseg WHERE aseg = '" . $conexion->real_escape_string($at) . "'") or die($conexion->error);
                            while ($filat = mysqli_fetch_array($resultadot)) {
                                $tr = $filat["tip_precio"];
                                echo ' ' . htmlspecialchars($tr);
                            }
                        }
                        ?>
                    </strong>
                </div>
            </div>
        </div>

        <div class="patient-info mb-3">
            <div class="row">
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
                        echo 'Diagnóstico: <strong>' . htmlspecialchars($d) . '</strong>';
                    } else {
                        echo 'Motivo de atención: <strong>' . htmlspecialchars($m) . '</strong>';
                    }
                    ?>
                </div>
            </div>
        </div>

    </div> <!-- container paciente -->

    <?php
} else {
    echo '<script type="text/javascript"> window.location.href="../../template/select_pac_enf.php";</script>';
    exit;
}
?>

<?php
if ($activo != 'SI') {
    // Alerta si cuenta cerrada
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
        $(document).ready(function() {
            swal({
                title: "Cuenta del paciente cerrada, No es posible solicitar medicamentos a farmacia",
                type: "error",
                confirmButtonText: "ACEPTAR"
            }, function(isConfirm) {
                if (isConfirm) {
                    window.location.href = "../selectpac_sincama/select_pac.php";
                }
            });
        });
    </script>';
}
?>

<div class="container box mt-3">
    <div class="content">
        <center><h4>Paciente: <?php echo htmlspecialchars(($pac_papell ?? '') . ' ' . ($pac_sapell ?? '') . ' ' . ($pac_nom_pac ?? '')) ?></h4></center>
        <center><h5>Agregar medicamentos</h5></center>

        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
            <div class="form-group row">
                <label class="control-label col-sm-3 col-form-label" for="">Medicamento / Material:</label>
                <div class="col-md-9">
                    <select class="selectpicker form-control" data-live-search="true" name="med" required>
                        <option value="">Seleccionar</option>
                        <?php
                        // Query corregida para listar medicamentos disponibles
                        $sql = "
              SELECT s.stock_id, i.item_name, i.item_grams, s.stock_qty
              FROM stock s
              INNER JOIN item i ON i.item_id = s.item_id
              INNER JOIN item_type it ON it.item_type_id = i.item_type_id
              WHERE UPPER(i.controlado) = 'NO'
                AND s.stock_qty IS NOT NULL
                AND s.stock_qty > 0
                AND i.activo = 'SI'
              ORDER BY i.item_name ASC
            ";
                        $result = $conexion->query($sql) or die($conexion->error);
                        while ($row_datos = $result->fetch_assoc()) {
                            $label = $row_datos['item_name'];
                            if (!empty($row_datos['item_grams'])) $label .= ', ' . $row_datos['item_grams'];
                            // mostrar cantidad disponible (opcional)
                            $label .= ' (disponible: ' . intval($row_datos['stock_qty']) . ')';
                            echo "<option value='" . intval($row_datos['stock_id']) . "'>" . htmlspecialchars($label) . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-sm-3 col-form-label" for="">Cantidad:</label>
                <div class="col-sm-3">
                    <input type="number" min="1" step="1" class="form-control" name="qty" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <input type="submit" name="btnserv" class="btn btn-block btn-success" value="Agregar">
                </div>
            </div>
        </form>

    </div>
</div>

<?php
// Procesar inserción al carrito
if (isset($_POST['btnserv'])) {
    $stock_id = intval($_POST['med']);
    $qty = intval(mysqli_real_escape_string($conexion, (strip_tags($_POST["qty"], ENT_QUOTES))));

    // obtener tip_precio según aseguradora
    $sql_aseg = "SELECT aseg FROM dat_ingreso WHERE id_atencion = $id_atencion";
    $result_aseg = $conexion->query($sql_aseg);
    $at = '';
    while ($row_aseg = $result_aseg->fetch_assoc()) {
        $at = $row_aseg['aseg'];
    }
    if ($at !== '') {
        $resultadot = $conexion->query("SELECT tip_precio FROM cat_aseg WHERE aseg = '" . $conexion->real_escape_string($at) . "'") or die($conexion->error);
        while ($filat = mysqli_fetch_array($resultadot)) {
            $tr = $filat["tip_precio"];
        }
    }

    // obtener datos del stock seleccionado
    $sql = "
      SELECT i.item_id, s.stock_qty, i.item_min, i.item_price, i.item_price2, i.item_price3, i.item_price4
      FROM stock s
      INNER JOIN item i ON i.item_id = s.item_id
      WHERE s.stock_id = $stock_id
      LIMIT 1
    ";
    $result = $conexion->query($sql) or die($conexion->error);
    if ($row_medicamentos = $result->fetch_assoc()) {
        $stock_qty = intval($row_medicamentos['stock_qty']);
        $stock_min = intval($row_medicamentos['item_min']);
        $item_id = intval($row_medicamentos['item_id']);
        // seleccionar precio según tipo
        if ($tr == 1) $precio = $row_medicamentos['item_price'];
        elseif ($tr == 2) $precio = $row_medicamentos['item_price2'];
        elseif ($tr == 3) $precio = $row_medicamentos['item_price3'];
        elseif ($tr == 4) $precio = $row_medicamentos['item_price4'];
        else $precio = $row_medicamentos['item_price'];
    } else {
        $stock_qty = 0;
        $stock_min = 0;
        $item_id = 0;
        $precio = 0;
    }

    $cart_uniquid = uniqid();
    $stock_after = $stock_qty - $qty;

    if (!($stock_after < $stock_min)) {
        $sql2 = "
          INSERT INTO cart_enf (item_id, cart_qty, cart_price, cart_stock_id, id_usua, cart_uniqid, paciente, tipo)
          VALUES ($item_id, $qty, $precio, $stock_id, $usuario2, '" . $conexion->real_escape_string($cart_uniquid) . "', $id_atencion, '" . $conexion->real_escape_string($area) . "')
        ";
        $result_insert = $conexion->query($sql2) or die($conexion->error);

        // Nota: originalmente tenías comentado el update de stock; lo dejé comentado para no cambiar tu lógica.
        /*
        $sql_update_stock = "UPDATE stock SET stock_qty = $stock_after WHERE stock_id = $stock_id";
        $conexion->query($sql_update_stock);
        */

        echo '<script>window.location.href = "solmed_far.php?paciente=' . $id_atencion . '";</script>';
        exit;
    } else {
        // alerta existencia insuficiente
        echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
        echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
        echo '<script>
            $(document).ready(function() {
                swal({
                    title: "Favor de verificar existencias con farmacia",
                    type: "error",
                    confirmButtonText: "ACEPTAR"
                }, function(isConfirm) {
                    if (isConfirm) {
                        window.location.href = "solmed_far.php?paciente=' . $id_atencion . '";
                    }
                });
            });
        </script>';
    }
}
?>
<?php
// Reemplaza la sección de la tabla del carrito (línea ~450 aproximadamente)

// ANTES DE LA TABLA, obtén el tipo de precio una sola vez:
$tr = 1; // valor por defecto
$sql_aseg = "SELECT aseg FROM dat_ingreso WHERE id_atencion = $id_atencion";
$result_aseg = $conexion->query($sql_aseg);
$at = '';
while ($row_aseg = $result_aseg->fetch_assoc()) {
    $at = $row_aseg['aseg'];
}
if ($at !== '') {
    $resultadot = $conexion->query("SELECT tip_precio FROM cat_aseg WHERE aseg = '" . $conexion->real_escape_string($at) . "'") or die($conexion->error);
    while ($filat = mysqli_fetch_array($resultadot)) {
        $tr = $filat["tip_precio"];
    }
}
?>

<div class="table-responsive">
    <table class="table table-bordered table-striped" id="mytable">
        <thead class="thead-custom">
        <tr>
            <th>Descripción</th>
            <th>Cantidad</th>
            <?php
            $usuario = $_SESSION['login'];
            $rol = $usuario['id_rol'];
            if ($rol == 5) { ?>
                <th>Sub. total</th>
                <th>Total</th>
            <?php } ?>
            <th>Solicitante</th>
            <th>Paciente</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        // CONSULTA CORREGIDA: Incluye los precios del item
        $resultado2 = $conexion->query("
            SELECT c.*, 
                   p.papell, p.sapell, p.nom_pac, 
                   i.item_name, i.item_grams,
                   i.item_price, i.item_price2, i.item_price3, i.item_price4
            FROM cart_enf c
            INNER JOIN dat_ingreso di ON di.id_atencion = c.paciente
            INNER JOIN paciente p ON di.Id_exp = p.Id_exp
            INNER JOIN item i ON i.item_id = c.item_id
            WHERE di.id_atencion = $id_atencion
        ") or die($conexion->error);

        $no = 1;
        $total = 0;
        while ($row = $resultado2->fetch_assoc()) {
            $id_cart_enf = $row['cart_id'];
            $id_usua = $row['id_usua'];

            // Obtener solicitante
            $sql4 = "SELECT papell FROM reg_usuarios WHERE id_usua = $id_usua";
            $result4 = $conexion->query($sql4);
            $solicitante = '';
            while ($row_usua = $result4->fetch_assoc()) {
                $solicitante = $row_usua['papell'];
            }

            // Seleccionar el precio según el tipo
            $precio = 0;
            switch($tr) {
                case 1:
                    $precio = floatval($row['item_price'] ?? 0);
                    break;
                case 2:
                    $precio = floatval($row['item_price2'] ?? 0);
                    break;
                case 3:
                    $precio = floatval($row['item_price3'] ?? 0);
                    break;
                case 4:
                    $precio = floatval($row['item_price4'] ?? 0);
                    break;
                default:
                    $precio = floatval($row['item_price'] ?? 0);
            }

            $subtotal = $precio * intval($row['cart_qty']);
            $total += $subtotal;

            // Construir descripción del item
            $descripcion = htmlspecialchars($row['item_name']);
            if (!empty($row['item_grams'])) {
                $descripcion .= ', ' . htmlspecialchars($row['item_grams']);
            }

            $nombre_completo = htmlspecialchars($row['nom_pac'] . " " . $row['papell'] . " " . $row['sapell']);

            echo '<tr>';
            echo '<td>' . $descripcion . '</td>';
            echo '<td>' . intval($row['cart_qty']) . '</td>';

            if ($rol == 5) {
                echo '<td>$' . number_format($precio, 2) . '</td>';
                echo '<td>$' . number_format($subtotal, 2) . '</td>';
            }

            echo '<td>' . htmlspecialchars($solicitante) . '</td>';
            echo '<td>' . $nombre_completo . '</td>';
            echo '<td><a class="btn btn-danger btn-sm" href="manipulacar.php?q=del_car&cart_stock_id=' . intval($row['cart_stock_id']) . '&cart_qty=' . intval($row['cart_qty']) . '&paciente=' . $id_atencion . '&cart_id=' . intval($row['cart_id']) . '"><span class="fa fa-trash"></span></a></td>';
            echo '</tr>';

            $no++;
        }
        ?>
        </tbody>
    </table>

    <?php
    if ($rol == 5) { ?>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-sm-3 control-label">Total: </label>
                <div class="col-md-6">
                    <center>
                        <input type="text" class="form-control pull-right" value="$ <?php echo number_format($total, 2) ?>" disabled>
                    </center>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="col-md-12 mt-3">
        <center>
            <?php
            echo '<a class="btn btn-success col-3 btn-block" href="manipulacar.php?q=comf_cart&paciente=' . $id_atencion . '&id_usua=' . $usuario2 . '"><span>Confirmar</span></a>';
            ?>
        </center>
    </div>
</div>

<footer class="main-footer mt-4">
    <?php include("../../template/footer.php"); ?>
</footer>

<!-- scripts adicionales del template (si se necesitan) -->
<script src="../../template/plugins/fastclick/fastclick.min.js"></script>
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

</body>
</html>
