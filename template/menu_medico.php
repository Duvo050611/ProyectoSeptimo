<?php
include '../conexionbd.php';
$conexion = ConexionBD::getInstancia()->getConexion();
$lifetime = 100000;
session_set_cookie_params($lifetime);
session_start();

if (!isset($_SESSION['login'])) {
    session_unset();
    session_destroy();
    header('Location: ../index.php');
}
$usuario = $_SESSION['login'];

if (!($usuario['id_rol'] == 2 || $usuario['id_rol'] == 5 || $usuario['id_rol'] == 12)) {
    session_unset();
    session_destroy();
    header('Location: ../index.php');
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>INEO Metepec</title>
    <link rel="icon" href="../imagenes/SIF.PNG">
    <script src="https://kit.fontawesome.com/e547be4475.js" crossorigin="anonymous"></script>

    <!-- Bootstrap 3.3.2 -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Morris chart -->
    <link href="plugins/morris/morris.css" rel="stylesheet" type="text/css" />
    <!-- jvectormap -->
    <link href="plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <!-- Daterange picker -->
    <link href="plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins -->
    <link href="dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #0a0a0a 100%) !important;
            font-family: 'Roboto', sans-serif !important;
            min-height: 100vh;
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

        /* Header personalizado */
        .main-header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
            border-bottom: 2px solid #40E0FF !important;
            box-shadow: 0 4px 20px rgba(64, 224, 255, 0.2);
        }

        .main-header .logo {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            border-right: 2px solid #40E0FF !important;
            color: #40E0FF !important;
        }

        .main-header .navbar {
            background: transparent !important;
        }

        /* Sidebar personalizado */
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

        .treeview-menu > li > a {
            color: #ffffff !important;
        }

        .treeview-menu > li > a:hover {
            background: rgba(64, 224, 255, 0.1) !important;
            color: #40E0FF !important;
        }

        .treeview-menu-separator {
            padding: 10px 15px;
            font-weight: bold;
            color: #40E0FF !important;
            text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
            cursor: default;
            background: rgba(64, 224, 255, 0.05) !important;
            border-top: 1px solid rgba(64, 224, 255, 0.3);
            border-bottom: 1px solid rgba(64, 224, 255, 0.3);
        }

        .user-panel {
            border-bottom: 1px solid rgba(64, 224, 255, 0.2);
        }

        .user-panel .info {
            color: #ffffff !important;
        }

        /* Content wrapper */
        .content-wrapper {
            background: transparent !important;
            min-height: 100vh;
        }

        /* Breadcrumb mejorado */
        .breadcrumb {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            border: 2px solid #40E0FF !important;
            border-radius: 15px !important;
            padding: 20px 30px !important;
            margin-bottom: 40px !important;
            box-shadow: 0 8px 30px rgba(64, 224, 255, 0.3);
            position: relative;
            overflow: hidden;
        }

        .breadcrumb::before {
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

        .breadcrumb h4 {
            color: #ffffff !important;
            margin: 0;
            font-weight: 600 !important;
            letter-spacing: 2px;
            text-shadow: 0 0 20px rgba(64, 224, 255, 0.5);
            position: relative;
            z-index: 1;
        }

        /* Botones */
        .btn {
            border-radius: 25px !important;
            padding: 10px 25px !important;
            font-weight: 600 !important;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease !important;
            border: 2px solid #40E0FF !important;
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(64, 224, 255, 0.2);
        }

        .btn:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 10px 25px rgba(64, 224, 255, 0.4) !important;
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
            border-color: #00D9FF !important;
            color: #40E0FF !important;
        }

        .btn-default {
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
            border-color: #40E0FF !important;
        }

        /* Títulos de sección */
        .thead {
            color: #40E0FF !important;
            font-size: 20px !important;
            text-shadow: 0 0 15px rgba(64, 224, 255, 0.5);
            padding: 20px 0 10px 0;
            margin-top: 30px;
        }

        .thead strong {
            color: #ffffff !important;
            letter-spacing: 2px;
        }

        /* Contenedor de camas */
        .box {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            padding: 20px 0;
        }

        /* Tarjetas de camas mejoradas */
        .ancholi {
            margin-top: 1px;
            margin-bottom: 10px;
            width: 175px;
            height: 100px;
            display: inline-block;
        }

        .ancholi2 {
            width: 170px;
            height: 97px;
            display: inline-block;
            border: 2px solid #40E0FF !important;
            border-radius: 10px !important;
            box-shadow: 0 4px 15px rgba(64, 224, 255, 0.3) !important;
            transition: all 0.3s ease !important;
            position: relative;
            overflow: hidden;
        }

        .ancholi2::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                    45deg,
                    transparent,
                    rgba(64, 224, 255, 0.1),
                    transparent
            );
            transform: rotate(45deg);
            transition: all 0.6s ease;
        }

        .ancholi2:hover {
            transform: translateY(-5px) scale(1.05) !important;
            box-shadow: 0 8px 25px rgba(64, 224, 255, 0.5) !important;
            border-color: #00D9FF !important;
        }

        .ancholi2:hover::before {
            left: 100%;
        }

        /* Cama LIBRE */
        .alert[style*="lightgrey"] {
            background: linear-gradient(135deg, #2a2a2a 0%, #1a1a1a 100%) !important;
            color: #888888 !important;
            border: 2px solid #444444 !important;
        }

        /* Cama NO DISPONIBLE */
        .alert[style*="#ECBC8C"] {
            background: linear-gradient(135deg, #f57c00 0%, #ff9800 100%) !important;
            color: #ffffff !important;
            border: 2px solid #ffa726 !important;
        }

        /* Cama OCUPADA */
        .alert[style*="#D4F0FC"] {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            color: #ffffff !important;
            border: 2px solid #40E0FF !important;
        }

        /* Cama POR LIBERAR */
        .alert[style*="#F3D3B3"] {
            background: linear-gradient(135deg, #ffa726 0%, #ffb74d 100%) !important;
            color: #ffffff !important;
            border: 2px solid #ffcc80 !important;
        }

        .alert-danger {
            background: linear-gradient(135deg, #c2185b 0%, #e91e63 100%) !important;
            color: #ffffff !important;
            border: 2px solid #f48fb1 !important;
        }

        /* Textos en las tarjetas */
        .nompac {
            font-size: 11.5px;
            position: relative;
            color: #ffffff !important;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
        }

        .alert h7 {
            color: #ffffff !important;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
        }

        /* Iconos */
        .fa-eye {
            color: #40E0FF !important;
            text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
            transition: all 0.3s ease;
        }

        .fa-eye:hover {
            transform: scale(1.2);
            color: #00D9FF !important;
        }

        /* Footer */
        .main-footer {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            border-top: 2px solid #40E0FF !important;
            color: #ffffff !important;
            box-shadow: 0 -4px 20px rgba(64, 224, 255, 0.2);
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

        /* Separador HR */
        hr {
            border-top: 2px solid rgba(64, 224, 255, 0.3) !important;
            margin: 30px 0;
            box-shadow: 0 1px 10px rgba(64, 224, 255, 0.2);
        }

        /* Dropdown menu */
        .dropdown-menu {
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
            border: 2px solid #40E0FF !important;
            border-radius: 10px;
        }

        .dropdown-menu > li > a {
            color: #ffffff !important;
        }

        .dropdown-menu > li > a:hover {
            background: rgba(64, 224, 255, 0.1) !important;
            color: #40E0FF !important;
        }

        .user-header {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        }

        /* Enlaces */
        .small-box-footer {
            color: #40E0FF !important;
            transition: all 0.3s ease;
        }

        .small-box-footer:hover {
            color: #00D9FF !important;
        }

        /* Animaciones de entrada */
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

        .ancholi {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Responsive */
        @media screen and (max-width: 980px) {
            .alert {
                padding-right: 38px;
                padding-left: 10px;
            }

            .nompac {
                margin-left: -3px;
                font-size: 10px;
            }

            .nod {
                font-size: 7px;
            }

            .breadcrumb {
                padding: 15px 20px !important;
            }

            .breadcrumb h4 {
                font-size: 1.1rem;
                letter-spacing: 1px;
            }

            .btn {
                font-size: 0.85rem !important;
                padding: 8px 20px !important;
            }
        }

        @media screen and (max-width: 576px) {
            .thead {
                font-size: 16px !important;
            }

            .ancholi {
                width: 150px;
                height: 90px;
            }

            .ancholi2 {
                width: 145px;
                height: 87px;
            }
        }

        /* Contenedor principal */
        .container {
            position: relative;
            z-index: 1;
        }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <?php
        if ($usuario['id_rol'] == 2) {
            ?>
            <a href="menu_medico.php" class="logo">
                <?php
                $resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
                while ($f = mysqli_fetch_array($resultado)) {
                    $id_simg = $f['id_simg'];
                    ?>
                    <center><span class="fondo"><img src="../configuracion/admin/img/<?php echo $f['img_base'] ?>" alt="imgsistema" class="img-fluid" width="112"></span></center>
                    <?php
                }
                ?>
            </a>
            <?php
        } else if ($usuario['id_rol'] == 5) {
            ?>
            <a href="menu_gerencia.php" class="logo">
                <?php
                $resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
                while ($f = mysqli_fetch_array($resultado)) {
                    $id_simg = $f['id_simg'];
                    ?>
                    <center><span class="fondo"><img src="../configuracion/admin/img/<?php echo $f['img_base'] ?>" alt="imgsistema" class="img-fluid" width="112"></span></center>
                    <?php
                }
                ?>
            </a>
        <?php } elseif ($usuario['id_rol'] == 12) { ?>
            <a href="menu_residente.php" class="logo">
                <?php
                $resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
                while ($f = mysqli_fetch_array($resultado)) {
                    $id_simg = $f['id_simg'];
                    ?>
                    <center><span class="fondo"><img src="../configuracion/admin/img/<?php echo $f['img_base'] ?>" alt="imgsistema" class="img-fluid" width="112"></span></center>
                    <?php
                }
                ?>
            </a>
            <?php
        } else {
            session_destroy();
            echo "<script>window.Location='../index.php';</script>";
        }
        ?>

        <nav class="navbar navbar-static-top" role="navigation">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="user-image" alt="User Image">
                            <span class="hidden-xs"> <?php echo $usuario['papell']; ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle" alt="User Image">
                                <p>
                                    <?php echo $usuario['papell']; ?>
                                </p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="../gestion_medica/editar_perfil/editar_perfil.php?id_usua=<?php echo $usuario['id_usua']; ?>" class="btn btn-default btn-flat">MIS DATOS</a>
                                </div>
                                <div class="pull-right">
                                    <a href="../cerrar_sesion.php" class="btn btn-default btn-flat">CERRAR SESIÓN</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <aside class="main-sidebar">
        <section class="sidebar">
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p> <?php echo $usuario['papell']; ?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> ACTIVO</a>
                </div>
            </div>

            <ul class="sidebar-menu">
                <li class="treeview">
                    <?php
                    if (isset($_SESSION['hospital'])) {
                    ?>
                <li><a href="../gestion_medica/cartas_consentimientos/consent_lista.php"><i class="fa fa-print"></i>IMPRIMIR DOCUMENTOS</a></li>
                <?php
                } else {
                    ?>
                    <li><a href="select_pac_hosp.php"><i class="fa fa-print"></i>IMPRIMIR DOCUMENTOS</a></li>
                    <?php
                }
                ?>
                </li>

                <li class="treeview">
                    <?php
                    if (isset($_SESSION['hospital'])) {
                    ?>
                <li class="treeview">
                    <a href="../gestion_medica/historia_clinica/his_clinica.php">
                        <i class="fa fa-folder" aria-hidden="true"></i>
                        <span>HISTORIA CLÍNICA</span>
                    </a>
                </li>

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-stethoscope"></i> <span>NOTAS MÉDICAS</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="/gestion_medica/notas_medicas/exploracion_fisica.php">
                                <i class="fa fa-magnifying-glass-arrow-right" aria-hidden="true"></i> EXPLORACION FISICA
                            </a></li>
                        <li><a href="/gestion_medica/notas_medicas/refraccion_antiguas.php">
                                <i class="fa fa-arrows-to-eye" aria-hidden="true"></i> REFRACCIONES ANTIGUAS
                            </a></li>
                        <li><a href="/gestion_medica/notas_medicas/autorefractor.php">
                                <i class="fa fa-eye" aria-hidden="true"></i> AUTOREFRACTOR / QUERATOCONO
                            </a></li>
                        <li><a href="/gestion_medica/notas_medicas/refraccion_actual.php">
                                <i class="fa fa-glasses" aria-hidden="true"></i> REFRACCION ACTUAL
                            </a></li>
                        <li><a href="/gestion_medica/notas_medicas/receta_lentes.php">
                                <i class="fa fa-file-waveform" aria-hidden="true"></i> RECETA ANTEOJOS
                            </a></li>
                        <li><a href="/gestion_medica/notas_medicas/receta_lentes_c.php">
                                <i class="fa fa-file-waveform" aria-hidden="true"></i> RECETA LENTES DE CONTACTO
                            </a></li>
                        <li><a href="/gestion_medica/notas_medicas/pruebas.php">
                                <i class="fa fa-hourglass-end" aria-hidden="true"></i> PRUEBAS
                            </a></li>
                        <li><a href="/gestion_medica/notas_medicas/formulario_nino_bebe.php">
                                <i class="fa fa-baby" aria-hidden="true"></i> NIÑO/BEBE
                            </a></li>
                        <li><a href="/gestion_medica/notas_medicas/formulario_mediciones_cornea.php">
                                <i class="fa fa-arrows-to-eye" aria-hidden="true"></i> MEDICIONES DE LA CORNEA
                            </a></li>
                        <li><a href="/gestion_medica/notas_medicas/formulario_exploracion.php">
                                <i class="fa fa-file-prescription" aria-hidden="true"></i> PRESION, PARPADOS Y VIAS
                            </a></li>
                        <li><a href="/gestion_medica/notas_medicas/formulario_PIO.php">
                                <i class="fa fa-file-prescription"></i> PRESION INTRAOCULAR
                            </a></li>
                        <li><a href="/gestion_medica/notas_medicas/formulario_seg_ant.php">
                                <i class="fa fa-backward" aria-hidden="true"></i> SEGMENTO ANTERIOR
                            </a></li>
                        <li><a href="/gestion_medica/notas_medicas/formulario_segmento_posterior.php">
                                <i class="fa fa-forward" aria-hidden="true"></i> SEGMENTO POSTERIOR
                            </a></li>
                        <li>
                            <a href="/gestion_medica/notas_medicas/estudios.php">
                                <i class="fa fa-folder" aria-hidden="true"></i> ESTUDIOS
                            </a>
                        </li>
                        <li>
                            <a href="/gestion_medica/notas_medicas/lente_intraocular.php">
                                <i class="fa fa-eye" aria-hidden="true"></i> LENTE INTRAOCULAR
                            </a>
                        </li>
                        <li>
                            <a href="/gestion_medica/notas_medicas/diagnostico.php">
                                <i class="fa fa-clipboard" aria-hidden="true"></i> DIAGNÓSTICO
                            </a>
                        </li>
                        <li>
                            <a href="/gestion_medica/notas_medicas/examenes_lab.php">
                                <i class="fa fa-vials" aria-hidden="true"></i> EXÁMENES DE LABORATORIO
                            </a>
                        </li>
                        <li>
                            <a href="/gestion_medica/notas_medicas/examenes_gab.php">
                                <i class="fa fa-vials" aria-hidden="true"></i> EXÁMENES DE GABINETE
                            </a>
                        </li>
                        <li>
                            <a href="/gestion_medica/notas_medicas/recomendaciones.php">
                                <i class="fa fa-child"></i> RECOMENDACIONES
                            </a>
                        </li>

                        <li class="treeview-menu-separator">
                            <span><i aria-hidden="true"></i> REGISTROS</span>
                        </li>

                        <li>
                            <a href="/gestion_medica/notas_medicas/reg_preanestesico.php">
                                <i class="fa fa-prescription-bottle-medical" aria-hidden="true"></i> PREANESTESICO
                            </a>
                        </li>
                        <li>
                            <a href="/gestion_medica/notas_medicas/reg_anestesia.php">
                                <i class="fa fa-kit-medical" aria-hidden="true"></i> ANESTESIA
                            </a>
                        </li>
                        <li>
                            <a href="/gestion_medica/notas_medicas/reg_post_anestesia.php">
                                <i class="fa fa-signs-post" aria-hidden="true"></i> POST ANESTESIA
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="../gestion_medica/estudios/estudios.php">
                        <i class="fa fa-flask" aria-hidden="true"></i> <span>RESULTADO DE ESTUDIOS</span>
                    </a>
                </li>

            <?php if ($usuario['id_rol'] == 12 || $usuario['id_rol'] == 5) { ?>
                <li class="treeview">
                    <a href="../gestion_medica/hospitalizacion/hoja_alta_medica.php">
                        <i class="fa fa-street-view" aria-hidden="true"></i> <span>AVISO DE ALTA MÉDICA</span>
                    </a>
                </li>
            <?php } ?>

            <?php if ($usuario['id_rol'] == 12 || $usuario['id_rol'] == 5 || $usuario['id_rol'] == 2) { ?>
                <li class="treeview">
                    <a href="../gestion_medica/selectpac_sincama/select_pac.php">
                        <i class="fa fa-print" aria-hidden="true"></i> <span>PACIENTES ANTENDIDOS</span>
                    </a>
                </li>
            <?php } ?>

            <?php } else { ?>
                <li class="treeview">
                    <a href="select_pac_hosp.php">
                        <i class="fa fa-folder" aria-hidden="true"></i> <span>HISTORIA CLÍNICA</span>
                    </a>
                </li>

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-stethoscope"></i> <span>NOTAS MÉDICAS</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="select_pac_hosp.php"><i class="fa fa-magnifying-glass-arrow-right"></i> EXPLORACION FISICA</a></li>
                        <li><a href="select_pac_hosp.php"><i class="fa fa-arrows-to-eye"></i> REFRACCIONES ANTIGUAS</a></li>
                        <li><a href="select_pac_hosp.php"><i class="fa fa-eye"></i> AUTOREFRACTOR / QUERATOCONO</a></li>
                        <li><a href="select_pac_hosp.php"><i class="fa fa-glasses"></i> REFRACCION ACTUAL</a></li>
                        <li><a href="select_pac_hosp.php"><i class="fa fa-file-waveform"></i> RECETA ANTEOJOS</a></li>
                        <li><a href="select_pac_hosp.php"><i class="fa fa-file-waveform"></i> RECETA LENTES DE CONTACTO</a></li>
                        <li><a href="select_pac_hosp.php"><i class="fa fa-hourglass-end"></i> PRUEBAS</a></li>
                        <li><a href="select_pac_hosp.php"><i class="fa fa-baby"></i> NIÑO/BEBE</a></li>
                        <li><a href="select_pac_hosp.php"><i class="fa fa-arrows-to-eye"></i> MEDICIONES DE LA CORNEA</a></li>
                        <li><a href="select_pac_hosp.php"><i class="fa fa-file-prescription"></i> PRESION, PARPADOS Y VIAS</a></li>
                        <li><a href="select_pac_hosp.php"><i class="fa fa-file-prescription"></i> PRESION INTRAOCULAR</a></li>
                        <li><a href="select_pac_hosp.php"><i class="fa fa-backward"></i> SEGMENTO ANTERIOR</a></li>
                        <li><a href="select_pac_hosp.php"><i class="fa fa-forward"></i> SEGMENTO POSTERIOR</a></li>
                        <li><a href="select_pac_hosp.php"><i class="fa fa-folder"></i> ESTUDIOS</a></li>
                        <li><a href="select_pac_hosp.php"><i class="fa fa-eye"></i> LENTE INTRAOCULAR</a></li>
                        <li><a href="select_pac_hosp.php"><i class="fa fa-clipboard"></i> DIAGNÓSTICO</a></li>
                        <li><a href="select_pac_hosp.php"><i class="fa fa-vials"></i> EXÁMENES DE LABORATORIO</a></li>
                        <li><a href="select_pac_hosp.php"><i class="fa fa-vials"></i> EXÁMENES DE GABINETE</a></li>
                        <li><a href="select_pac_hosp.php"><i class="fa fa-child"></i> RECOMENDACIONES</a></li>

                        <li class="treeview-menu-separator">
                            <span>REGISTROS</span>
                        </li>

                        <li><a href="select_pac_hosp.php"><i class="fa fa-prescription-bottle-medical"></i> PREANESTESICO</a></li>
                        <li><a href="select_pac_hosp.php"><i class="fa fa-kit-medical"></i> ANESTESIA</a></li>
                        <li><a href="select_pac_hosp.php"><i class="fa fa-signs-post"></i> POST ANESTESIA</a></li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="select_pac_hosp.php">
                        <i class="fa fa-flask" aria-hidden="true"></i> <span>RESULTADO DE ESTUDIOS</span>
                    </a>
                </li>

                <?php if ($usuario['id_rol'] == 12 || $usuario['id_rol'] == 5) { ?>
                    <li class="treeview">
                        <a href="select_pac_hosp.php">
                            <i class="fa fa-street-view" aria-hidden="true"></i> <span>AVISO DE ALTA MÉDICA</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($usuario['id_rol'] == 12 || $usuario['id_rol'] == 5 || $usuario['id_rol'] == 2) { ?>
                    <li class="treeview">
                        <a href="../gestion_medica/selectpac_sincama/select_pac.php">
                            <i class="fa fa-print" aria-hidden="true"></i> <span>PACIENTES ANTENDIDOS</span>
                        </a>
                    </li>
                <?php } ?>
            <?php } ?>
            </ul>
        </section>
    </aside>

    <div class="content-wrapper">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <h4><i class="fa fa-user-md"></i> MÉDICO</h4>
                </li>
            </ol>
        </nav>

        <section class="content container-fluid">
            <div class="container">
                <div class="row">
                    <?php
                    if ($usuario['id_rol'] == 5 || $usuario['id_rol'] == 2 || $usuario['id_rol'] == 12) {
                        ?>
                        <div class="col-sm-3">
                            <a href="../gestion_medica/selectpac_sincama/select_pac.php"><button type="button" class="btn btn-default">PACIENTES ANTENDIDOS <i class="fa fa-user"></i></button></a>
                        </div>
                        <?php
                    } else {
                        session_destroy();
                        echo "<script>window.Location='../index.php';</script>";
                    }
                    ?>
                </div>
            </div>

            <hr>

            <div class="thead">
                <strong>PACIENTES EN CONSULTA EXTERNA</strong>
            </div>

            <div class="container box">
                <div class="row">
                    <?php
                    $sql = 'SELECT id,estatus,tipo,num_cama, id_atencion from cat_camas where piso=1 and seccion=1 ORDER BY num_cama ASC';
                    $result = $conexion->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        $num_cama = $row['num_cama'];
                        $id_atencion = $row['id_atencion'];
                        $estaus = $row['estatus'];
                        if ($estaus == "LIBRE") {
                            ?>
                            <div class="ancholi">
                                <div class="alert alert ancholi2" role="alert" style="background-color: lightgrey; color:black;">
                                    <div><a href="#" class="small-box-footer"><i style="font-size:18px;" class="fa fa-eye"></i></a>
                                        <h7>
                                            <font size="2"><?php echo $num_cama ?> </font>
                                        </h7>
                                    </div>
                                    <div>
                                        <h7>
                                            <font size="2"><?php echo $estaus ?></font>
                                        </h7>
                                    </div><br><br>
                                </div>
                            </div>
                            <?php
                        } elseif ($estaus == "MANTENIMIENTO") {
                            $esta= "NO DISPONIBLE";
                            ?>
                            <div class="ancholi">
                                <div class="alert alert ancholi2" role="alert" style="background-color: #ECBC8C; color:black;">
                                    <div><a href="#" class="small-box-footer"><i style="font-size:18px;" class="fa fa-eye"></i></a>
                                        <h7>
                                            <font size="2"><?php echo $num_cama ?> </font>
                                        </h7>
                                    </div>
                                    <div>
                                        <h7>
                                            <font size="2"><?php echo $esta ?></font>
                                        </h7>
                                    </div><br><br>
                                </div>
                            </div>
                            <?php
                        } elseif ($estaus == "EN PROCESO DE LIBERACION" or $estaus == "EN PROCESO DE LIBERA") {
                            $pr = "POR LIBERAR";
                            ?>
                            <div class="ancholi">
                                <div class="alert alert ancholi2" role="alert" style="background-color: #F3D3B3; color:black;">
                                    <div><a href="#" class="small-box-footer"><i style="font-size:18px;" class="fa fa-eye"></i></a>
                                        <h7>
                                            <font size="2"><?php echo $num_cama ?></font>
                                        </h7>
                                    </div>
                                    <div>
                                        <h7>
                                            <font size="2"><?php echo $pr ?></font>
                                        </h7>
                                    </div><br><br>
                                </div>
                            </div>
                            <?php
                        } else {
                            $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp,di.id_usua,di.id_usua2,di.id_usua3,di.id_usua4,di.id_usua5 from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp  order by di.fecha DESC";
                            $result_pac = $conexion->query($sql_pac);
                            while ($row_cam = $result_pac->fetch_assoc()) {
                                $nombre_pac = $row_cam['papell'] . ' ' . $row_cam['nom_pac'];
                                $id_exp = $row_cam['Id_exp'];
                                $id_usua1 = $row_cam['id_usua'];
                                $id_usua2 = $row_cam['id_usua2'];
                                $id_usua3 = $row_cam['id_usua3'];
                                $id_usua4 = $row_cam['id_usua4'];
                                $id_usua5 = $row_cam['id_usua5'];
                            }
                            ?>
                            <a href="../gestion_medica/hospitalizacion/select_pac.php?id_atencion=<?php echo $id_atencion ?>&id_exp=<?php echo $id_exp ?>&usuareg=<?php echo $usuario['id_usua'] ?>&usuapac=<?php echo $id_usua1 ?>&usuareg2=<?php echo $id_usua2 ?>&usuareg3=<?php echo $id_usua3 ?>&usuareg4=<?php echo $id_usua4 ?>&usuareg5=<?php echo $id_usua5 ?>" class="small-box-footer">
                                <div class="ancholi">
                                    <div class="alert alert ancholi2" role="alert" style="background-color: #D4F0FC; color:black;">
                                        <i style="font-size:18px;" class="fa fa-eye"></i>
                                        <h7>
                                            <font size="2"><?php echo $num_cama ?></font>
                                        </h7>
                                        <?php
                                        $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp,di.id_usua,di.id_usua2,di.id_usua3,di.id_usua4,di.id_usua5 from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp and alta_adm='NO' order by di.fecha DESC";
                                        $result_pac = $conexion->query($sql_pac);
                                        while ($row_cam = $result_pac->fetch_assoc()) {
                                            $nombre_pac = $row_cam['nom_pac'];
                                            $papell = $row_cam['papell'];
                                            $id_exp = $row_cam['Id_exp'];
                                            $id_usua1 = $row_cam['id_usua'];
                                            $id_usua2 = $row_cam['id_usua2'];
                                            $id_usua3 = $row_cam['id_usua3'];
                                            $id_usua4 = $row_cam['id_usua4'];
                                            $id_usua5 = $row_cam['id_usua5'];
                                        }
                                        ?>
                                        <br>
                                        <h7 class="nompac"><?php echo $papell ?></h7><br>
                                        <h7 class="nompac"><?php echo $nombre_pac ?></h7>
                                        <br><br>
                                    </div>
                                </div>
                            </a>
                            <?php
                        }
                    }
                    ?>

                    <?php
                    // Sección 2 - Consulta Externa (código similar, solo consulta diferente)
                    $sql = 'SELECT id,estatus,tipo,num_cama, id_atencion from cat_camas where piso=1 and seccion=2 ORDER BY num_cama ASC';
                    $result = $conexion->query($sql);
                    // [Código similar al anterior para sección 2]
                    ?>
                </div>
            </div>

            <div class="thead">
                <strong>PACIENTES EN PREPARACIÓN</strong>
            </div>

            <div class="container box">
                <div class="row">
                    <?php
                    // Sección de preparación - piso 2
                    $sql = 'SELECT id,estatus,tipo,num_cama, id_atencion from cat_camas where piso=2 and seccion=1 ORDER BY num_cama ASC';
                    $result = $conexion->query($sql);
                    // [Código similar para visualización de camas]
                    ?>
                </div>
            </div>

            <div class="thead">
                <strong>PACIENTES EN RECUPERACIÓN</strong>
            </div>

            <div class="container box">
                <div class="row">
                    <?php
                    // Sección de recuperación - piso 3
                    $sql = 'SELECT id,estatus,tipo,num_cama, id_atencion from cat_camas where piso=3 and seccion=1 ORDER BY num_cama ASC';
                    $result = $conexion->query($sql);
                    // [Código similar para visualización de camas]
                    ?>
                </div>
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <?php include("footer.php"); ?>
    </footer>

</div>

<!-- jQuery 2.1.3 -->
<script src="plugins/jQuery/jQuery-2.1.3.min.js"></script>
<!-- Bootstrap 3.3.2 JS -->
<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<!-- FastClick -->
<script src='plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js" type="text/javascript"></script>
<!-- Sparkline -->
<script src="plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
<!-- jvectormap -->
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
<!-- daterangepicker -->
<script src="plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<!-- datepicker -->
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<!-- SlimScroll 1.3.0 -->
<script src="plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<!-- ChartJS 1.0.1 -->
<script src="plugins/chartjs/Chart.min.js" type="text/javascript"></script>
<!-- AdminLTE dashboard demo -->
<script src="dist/js/pages/dashboard2.js" type="text/javascript"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js" type="text/javascript"></script>

</body>
</html>
