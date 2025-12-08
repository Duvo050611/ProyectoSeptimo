<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}

include '../../conexionbd.php';
$conexion = ConexionBD::getInstancia()->getConexion();
include("../header_enfermera.php");

$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INEO Metepec - Cambio de Habitación</title>
    <link rel="icon" href="../../imagenes/SIF.PNG">

    <!-- Bootstrap & FontAwesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
          integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #0a0a0a 100%) !important;
            font-family: 'Roboto', sans-serif !important;
            min-height: 100vh;
            color: #ffffff !important;
        }

        /* Efecto de partículas en el fondo */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                    radial-gradient(circle at 20% 50%, rgba(64, 224, 255, 0.03) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(64, 224, 255, 0.03) 0%, transparent 50%),
                    radial-gradient(circle at 40% 20%, rgba(64, 224, 255, 0.02) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .wrapper {
            position: relative;
            z-index: 1;
        }

        /* Content wrapper */
        .content-wrapper {
            background: transparent !important;
            min-height: 100vh;
        }

        section.content {
            background: transparent !important;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        /* Contenedor principal */
        .container {
            position: relative;
            z-index: 1;
        }

        /* Título principal */
        .titulo-principal {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            color: #ffffff !important;
            padding: 25px 30px !important;
            font-size: 26px !important;
            font-weight: 700 !important;
            border-radius: 15px !important;
            margin: 20px 0 40px 0 !important;
            border: 2px solid #40E0FF !important;
            box-shadow: 0 8px 30px rgba(64, 224, 255, 0.3);
            text-align: center;
            letter-spacing: 2px;
            text-shadow: 0 0 20px rgba(64, 224, 255, 0.5);
            position: relative;
            overflow: hidden;
        }

        .titulo-principal::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(64, 224, 255, 0.1) 0%, transparent 70%);
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        /* Formulario */
        .form-container {
            background: linear-gradient(135deg, rgba(15, 52, 96, 0.8), rgba(22, 33, 62, 0.8));
            padding: 40px;
            border-radius: 15px;
            border: 2px solid rgba(64, 224, 255, 0.4);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
            margin: 0 auto;
            max-width: 800px;
        }

        .form-group {
            margin-bottom: 30px;
        }

        .form-group label {
            color: #40E0FF !important;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 12px;
            display: block;
            text-shadow: 0 0 10px rgba(64, 224, 255, 0.3);
            letter-spacing: 0.5px;
        }

        .form-control {
            background: linear-gradient(135deg, rgba(26, 26, 46, 0.6), rgba(22, 33, 62, 0.6)) !important;
            border: 2px solid rgba(64, 224, 255, 0.3) !important;
            color: #ffffff !important;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .form-control:focus {
            outline: none;
            border-color: #40E0FF !important;
            box-shadow: 0 0 20px rgba(64, 224, 255, 0.5);
            background: linear-gradient(135deg, rgba(22, 33, 62, 0.8), rgba(15, 52, 96, 0.8)) !important;
        }

        .form-control:disabled {
            background: linear-gradient(135deg, rgba(66, 66, 66, 0.4), rgba(33, 33, 33, 0.4)) !important;
            color: rgba(255, 255, 255, 0.6) !important;
            cursor: not-allowed;
        }

        .form-control option {
            background: #16213e;
            color: #ffffff;
            padding: 10px;
        }

        /* Select personalizado */
        select.form-control {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2340E0FF' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            padding-right: 40px;
        }

        /* Botones */
        .btn {
            border-radius: 25px !important;
            padding: 12px 35px !important;
            font-weight: 600 !important;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease !important;
            font-size: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%) !important;
            border: 2px solid #4caf50 !important;
            color: #ffffff !important;
        }

        .btn-success:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 8px 25px rgba(76, 175, 80, 0.5) !important;
            background: linear-gradient(135deg, #388e3c 0%, #2e7d32 100%) !important;
            border-color: #66bb6a !important;
        }

        .btn-danger {
            background: linear-gradient(135deg, #c62828 0%, #b71c1c 100%) !important;
            border: 2px solid #ef5350 !important;
            color: #ffffff !important;
        }

        .btn-danger:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 8px 25px rgba(239, 83, 80, 0.5) !important;
            background: linear-gradient(135deg, #d32f2f 0%, #c62828 100%) !important;
            border-color: #ff6659 !important;
        }

        .form-group:last-child {
            margin-top: 40px;
            text-align: center;
        }

        .form-group:last-child .btn {
            margin: 0 10px;
            min-width: 150px;
        }

        /* Alertas */
        .alert {
            border-radius: 12px;
            padding: 20px 25px;
            margin: 20px 0;
            font-size: 16px;
            font-weight: 500;
            border: 2px solid;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(46, 125, 50, 0.9), rgba(27, 94, 32, 0.9));
            border-color: #4caf50;
            color: #ffffff;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(198, 40, 40, 0.9), rgba(183, 28, 28, 0.9));
            border-color: #ef5350;
            color: #ffffff;
        }

        .alert i {
            margin-right: 10px;
            font-size: 20px;
        }

        /* Scrollbar personalizado */
        ::-webkit-scrollbar {
            width: 12px;
        }

        ::-webkit-scrollbar-track {
            background: #0a0a0a;
            border-left: 1px solid #40E0FF;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #40E0FF 0%, #0f3460 100%);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #00D9FF 0%, #40E0FF 100%);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .titulo-principal {
                font-size: 20px !important;
                padding: 20px 15px !important;
                letter-spacing: 1px;
            }

            .form-container {
                padding: 25px 20px;
            }

            .form-group label {
                font-size: 14px;
            }

            .form-control {
                font-size: 14px;
                padding: 10px 15px;
            }

            .btn {
                font-size: 13px !important;
                padding: 10px 25px !important;
            }

            .form-group:last-child .btn {
                display: block;
                width: 100%;
                margin: 10px 0;
            }
        }

        /* Sidebar consistencia */
        .main-sidebar {
            background: linear-gradient(180deg, #16213e 0%, #0f3460 100%) !important;
            border-right: 2px solid #40E0FF !important;
            box-shadow: 4px 0 20px rgba(64, 224, 255, 0.15);
        }

        .sidebar-menu > li > a {
            color: #ffffff !important;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .sidebar-menu > li > a:hover,
        .sidebar-menu > li.active > a {
            background: rgba(64, 224, 255, 0.1) !important;
            border-left: 3px solid #40E0FF !important;
            color: #40E0FF !important;
        }

        /* Iconos en labels */
        .form-group label i {
            margin-right: 8px;
            color: #40E0FF;
        }

        /* Animación de entrada del formulario */
        .form-container {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">

<section class="content container-fluid">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <!-- Título principal -->
                <div class="titulo-principal">
                    <i class="fas fa-exchange-alt"></i> CAMBIO DE HABITACIÓN
                </div>

                <?php
                $id = $_GET['id_cama'];
                $tipo = $_GET['tipo'];
                $hab = $_GET['hab'];
                $id_atencion = $_GET['id_atencion'];

                $sql = "SELECT * FROM cat_camas c 
                        INNER JOIN dat_ingreso d ON c.id_atencion = d.id_atencion 
                        INNER JOIN paciente p ON d.Id_exp = p.Id_exp 
                        WHERE c.num_cama = $id";
                $result = $conexion->query($sql);

                if ($result && $row_datos = $result->fetch_assoc()) {
                    $cama_ant = $row_datos['num_cama'];
                    ?>

                    <!-- Formulario -->
                    <div class="form-container">
                        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">

                            <div class="form-group">
                                <label>
                                    <i class="fas fa-bed"></i> HABITACIÓN ACTUAL:
                                </label>
                                <input type="text" name="num_cama" class="form-control"
                                       value="<?php echo htmlspecialchars($row_datos['num_cama']); ?>" disabled>
                            </div>

                            <div class="form-group">
                                <label>
                                    <i class="fas fa-user"></i> NOMBRE COMPLETO DEL PACIENTE:
                                </label>
                                <input type="text" class="form-control"
                                       value="<?php echo htmlspecialchars($row_datos['nom_pac'] . ' ' . $row_datos['papell'] . ' ' . $row_datos['sapell']); ?>"
                                       disabled>
                            </div>

                            <div class="form-group">
                                <label>
                                    <i class="fas fa-bed"></i> NUEVA HABITACIÓN:
                                </label>
                                <select name="cama" class="form-control" required>
                                    <option value="">-- SELECCIONAR HABITACIÓN --</option>
                                    <?php
                                    $resultadoaseg = $conexion->query("SELECT * FROM cat_camas WHERE estatus='LIBRE' ORDER BY num_cama ASC") or die($conexion->error);
                                    while ($opcionesaseg = $resultadoaseg->fetch_assoc()) {
                                        echo '<option value="' . htmlspecialchars($opcionesaseg['num_cama']) . '">';
                                        echo htmlspecialchars($opcionesaseg['num_cama'] . ' - ' . $opcionesaseg['tipo']);
                                        echo '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <button type="submit" name="del" class="btn btn-success">
                                    <i class="fas fa-save"></i> GUARDAR CAMBIO
                                </button>
                                <a href="cambio_habitacion.php" class="btn btn-danger">
                                    <i class="fas fa-times"></i> CANCELAR
                                </a>
                            </div>

                        </form>
                    </div>

                    <?php
                } else {
                    echo '<div class="alert alert-danger">';
                    echo '<i class="fas fa-exclamation-triangle"></i> No se encontró información de la cama seleccionada.';
                    echo '</div>';
                }
                ?>

            </div>
        </div>

        <?php
        if (isset($_POST['del'])) {
            $cama = mysqli_real_escape_string($conexion, strip_tags($_POST["cama"], ENT_QUOTES));
            $id = mysqli_real_escape_string($conexion, strip_tags($_GET["id_cama"], ENT_QUOTES));
            $id_atencion = mysqli_real_escape_string($conexion, strip_tags($_GET["id_atencion"], ENT_QUOTES));
            $hab = mysqli_real_escape_string($conexion, strip_tags($_GET["hab"], ENT_QUOTES));
            $tipo = mysqli_real_escape_string($conexion, strip_tags($_GET["tipo"], ENT_QUOTES));

            $fecha_actual = date("Y-m-d H:i:s");

            $select_cama = "SELECT * FROM cat_camas WHERE num_cama=$cama";
            $result_cama = $conexion->query($select_cama);

            if ($row_cam = $result_cama->fetch_assoc()) {
                $tipo_new = $row_cam['tipo'];
                $hab_new = $row_cam['habitacion'];
                $serv_cve = $row_cam['serv_cve'];

                if ($tipo_new != $tipo) {
                    if ($cama >= 101 && $cama <= 210) {
                        // Camas estándar
                        $sql_dia_hab = "UPDATE dat_ingreso SET fecha_cama='$fecha_actual', area='$tipo_new' WHERE id_atencion = $id_atencion";
                        $conexion->query($sql_dia_hab);

                        $sql2 = "INSERT INTO dat_ctapac(id_atencion,prod_serv,insumo,cta_fec,cta_cant,cta_tot,id_usua,cta_activo) 
                                 VALUES($id_atencion,'S',$serv_cve,'$fecha_actual',1,0,$id_usua,'SI')";
                        $conexion->query($sql2);

                        $sql2 = "UPDATE cat_camas SET estatus='LIBRE', id_atencion=0 WHERE num_cama=$id";
                        $conexion->query($sql2);

                        $sql2 = "UPDATE cat_camas SET estatus='OCUPADA', id_atencion='$id_atencion' WHERE num_cama=$cama";
                        $conexion->query($sql2);
                    } elseif ($cama >= 301 && $cama <= 304) {
                        // Suite
                        $sql_dia_hab = "UPDATE dat_ingreso SET fecha_cama='$fecha_actual', area='$tipo_new' WHERE id_atencion = $id_atencion";
                        $conexion->query($sql_dia_hab);

                        $sql2 = "INSERT INTO dat_ctapac(id_atencion,prod_serv,insumo,cta_fec,cta_cant,cta_tot,id_usua,cta_activo) 
                                 VALUES($id_atencion,'S',$serv_cve,'$fecha_actual',1,0,$id_usua,'SI')";
                        $conexion->query($sql2);

                        $sql2 = "UPDATE cat_camas SET estatus='LIBRE', id_atencion=0 WHERE num_cama=$id";
                        $conexion->query($sql2);

                        $sql2 = "UPDATE cat_camas SET estatus='OCUPADA', id_atencion='$id_atencion' WHERE num_cama=$cama";
                        $conexion->query($sql2);
                    } elseif ($cama >= 1 && $cama <= 5) {
                        // UCIN
                        $sql_dia_hab = "UPDATE dat_ingreso SET fecha_cama='$fecha_actual', area='$tipo_new' WHERE id_atencion = $id_atencion";
                        $conexion->query($sql_dia_hab);

                        $sql2 = "INSERT INTO dat_ctapac(id_atencion,prod_serv,insumo,cta_fec,cta_cant,cta_tot,id_usua,cta_activo) 
                                 VALUES($id_atencion,'S',$serv_cve,'$fecha_actual',1,0,$id_usua,'SI')";
                        $conexion->query($sql2);

                        $sql2 = "UPDATE cat_camas SET estatus='LIBRE', id_atencion=0 WHERE num_cama=$id";
                        $conexion->query($sql2);

                        $sql2 = "UPDATE cat_camas SET estatus='OCUPADA', id_atencion='$id_atencion' WHERE num_cama=$cama";
                        $conexion->query($sql2);
                    }
                } else {
                    // Mismo tipo, solo cambio de cama
                    $sql2 = "UPDATE cat_camas SET estatus='LIBRE', id_atencion=0 WHERE num_cama=$id";
                    $conexion->query($sql2);

                    $sql2 = "UPDATE cat_camas SET estatus='OCUPADA', id_atencion='$id_atencion' WHERE num_cama=$cama";
                    $conexion->query($sql2);
                }

                echo "<div class='alert alert-success' id='mensaje'>";
                echo "<i class='fas fa-check-circle'></i> Cambio de habitación realizado correctamente";
                echo "</div>";
                echo '<script type="text/javascript">
                        setTimeout(function() {
                            window.location.href = "cambio_habitacion.php";
                        }, 900);
                      </script>';
            } else {
                echo "<div class='alert alert-danger'>";
                echo "<i class='fas fa-exclamation-triangle'></i> Error: No se encontró la cama seleccionada.";
                echo "</div>";
            }
        }
        ?>

    </div>
</section>

<footer class="main-footer">
    <?php include("../../template/footer.php"); ?>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

<script>
    $(document).ready(function() {
        // Treeview del sidebar
        $('.treeview > a').on('click', function (e) {
            e.preventDefault();
            let parent = $(this).parent();
            let submenu = parent.find('.treeview-menu').first();

            parent.toggleClass('menu-open');
            submenu.slideToggle(200);
        });

        // Cerrar otros submenús
        $('.sidebar-menu .treeview > a').on('click', function() {
            var clickedMenu = $(this).parent();
            $('.sidebar-menu .treeview').not(clickedMenu).removeClass('menu-open');
            $('.sidebar-menu .treeview').not(clickedMenu).find('.treeview-menu').slideUp(200);
        });

    });
</script>

</body>
</html>