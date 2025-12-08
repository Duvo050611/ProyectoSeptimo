<?php
include '../conexionbd.php';
$conexion = ConexionBD::getInstancia()->getConexion();
session_start();

if (!isset($_SESSION['login'])) {
    session_unset();
    session_destroy();
    header('Location: ../index.php');
}
$usuario = $_SESSION['login'];

if (!($usuario['id_rol'] == 6 || $usuario['id_rol'] == 5 || $usuario['id_rol'] == 1 || $usuario['id_rol'] == 12)) {
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
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- Bootstrap 3.3.2 -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" type="text/css"/>
    <!-- Ionicons -->
    <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css"/>
    <!-- Morris chart -->
    <link href="plugins/morris/morris.css" rel="stylesheet" type="text/css"/>
    <!-- jvectormap -->
    <link href="plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css"/>
    <!-- Daterange picker -->
    <link href="plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css"/>
    <!-- Theme style -->
    <link href="dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css"/>
    <!-- AdminLTE Skins -->
    <link href="dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css"/>

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

        /* Contenedor del menú de configuración */
        .config-menu-container {
            padding: 20px;
            background: transparent !important;
            min-height: 100vh;
        }

        /* Header mejorado */
        .config-header {
            text-align: center;
            margin-bottom: 50px;
            padding: 25px;
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            border: 2px solid #40E0FF !important;
            border-radius: 20px !important;
            box-shadow: 0 8px 30px rgba(64, 224, 255, 0.3);
            position: relative;
            overflow: hidden;
        }

        .config-header::before {
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

        .config-header h4 {
            color: #ffffff !important;
            font-weight: 700 !important;
            margin: 0;
            font-size: 28px !important;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 20px rgba(64, 224, 255, 0.5);
            position: relative;
            z-index: 1;
        }

        .config-header h4 i {
            margin-right: 15px;
            color: #40E0FF;
            animation: rotate 3s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Grid de tarjetas */
        .config-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            max-width: 1600px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Tarjetas modernas */
        .config-card {
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
            border: 2px solid #40E0FF !important;
            border-radius: 25px !important;
            padding: 40px 20px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5),
            0 0 30px rgba(64, 224, 255, 0.2) !important;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
            position: relative;
            overflow: hidden;
            min-height: 240px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .config-card::before {
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

        .config-card:hover::before {
            left: 100%;
        }

        .config-card:hover {
            transform: translateY(-15px) scale(1.05) !important;
            border-color: #00D9FF !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7),
            0 0 50px rgba(64, 224, 255, 0.5),
            inset 0 0 20px rgba(64, 224, 255, 0.1) !important;
        }

        .config-card a {
            text-decoration: none !important;
            color: inherit;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            width: 100%;
        }

        /* Círculo de icono */
        .icon-circle {
            background: linear-gradient(135deg, rgba(64, 224, 255, 0.2) 0%, rgba(0, 217, 255, 0.3) 100%) !important;
            width: 130px !important;
            height: 130px !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin: 0 auto 20px !important;
            border: 3px solid #40E0FF !important;
            box-shadow: 0 10px 30px rgba(64, 224, 255, 0.3),
            inset 0 0 20px rgba(64, 224, 255, 0.1) !important;
            transition: all 0.4s ease !important;
            position: relative;
        }

        .icon-circle::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 2px solid #40E0FF;
            opacity: 0;
            animation: ripple 2s ease-out infinite;
        }

        @keyframes ripple {
            0% {
                transform: scale(1);
                opacity: 0.8;
            }
            100% {
                transform: scale(1.3);
                opacity: 0;
            }
        }

        .config-card:hover .icon-circle {
            transform: scale(1.15) rotate(360deg) !important;
            box-shadow: 0 15px 40px rgba(64, 224, 255, 0.5),
            inset 0 0 30px rgba(64, 224, 255, 0.2) !important;
            background: linear-gradient(135deg, rgba(64, 224, 255, 0.3) 0%, rgba(0, 217, 255, 0.4) 100%) !important;
        }

        .config-icon {
            font-size: 55px !important;
            color: #40E0FF !important;
            text-shadow: 0 0 20px rgba(64, 224, 255, 0.8);
            transition: all 0.4s ease !important;
        }

        .config-card:hover .config-icon {
            transform: scale(1.2) !important;
            text-shadow: 0 0 30px rgba(64, 224, 255, 1),
            0 0 40px rgba(64, 224, 255, 0.8);
        }

        /* Títulos */
        .config-title {
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 15px !important;
            margin: 0 !important;
            text-align: center;
            line-height: 1.3;
            letter-spacing: 1px;
            text-transform: uppercase;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5),
            0 0 20px rgba(64, 224, 255, 0.3);
            transition: all 0.3s ease;
            padding: 0 10px;
        }

        .config-card:hover .config-title {
            color: #40E0FF !important;
            text-shadow: 0 0 20px rgba(64, 224, 255, 0.8),
            0 0 30px rgba(64, 224, 255, 0.5);
        }

        /* Animaciones de entrada */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .config-card {
            animation: fadeInUp 0.8s ease-out backwards;
        }

        .config-card:nth-child(1) { animation-delay: 0.1s; }
        .config-card:nth-child(2) { animation-delay: 0.2s; }
        .config-card:nth-child(3) { animation-delay: 0.3s; }
        .config-card:nth-child(4) { animation-delay: 0.4s; }
        .config-card:nth-child(5) { animation-delay: 0.5s; }
        .config-card:nth-child(6) { animation-delay: 0.6s; }
        .config-card:nth-child(7) { animation-delay: 0.7s; }
        .config-card:nth-child(8) { animation-delay: 0.8s; }

        /* Modal */
        .modal-content {
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
            border: 2px solid #40E0FF !important;
            border-radius: 20px !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.9),
            0 0 40px rgba(64, 224, 255, 0.4);
        }

        .modal-header {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            border-bottom: 2px solid #40E0FF !important;
            border-radius: 20px 20px 0 0 !important;
        }

        .modal-footer {
            border-top: 2px solid #40E0FF !important;
            background: rgba(15, 52, 96, 0.5) !important;
        }

        /* Botones */
        .btn {
            border-radius: 25px !important;
            padding: 12px 30px !important;
            font-weight: 600 !important;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease !important;
            border: 2px solid #40E0FF !important;
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            color: #ffffff !important;
        }

        .btn:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 10px 25px rgba(64, 224, 255, 0.4) !important;
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
            border-color: #00D9FF !important;
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

        /* Responsive */
        @media (max-width: 1200px) {
            .config-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 25px;
            }
        }

        @media (max-width: 992px) {
            .config-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
                padding: 15px;
            }

            .config-card {
                padding: 35px 15px;
                min-height: 220px;
            }

            .icon-circle {
                width: 110px !important;
                height: 110px !important;
            }

            .config-icon {
                font-size: 48px !important;
            }

            .config-title {
                font-size: 14px !important;
            }

            .config-header h4 {
                font-size: 24px !important;
            }
        }

        @media (max-width: 768px) {
            .config-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 18px;
                padding: 10px;
            }

            .config-card {
                padding: 30px 15px;
                min-height: 200px;
            }

            .icon-circle {
                width: 100px !important;
                height: 100px !important;
                margin-bottom: 15px !important;
            }

            .config-icon {
                font-size: 42px !important;
            }

            .config-title {
                font-size: 13px !important;
            }

            .config-header {
                margin-bottom: 35px;
                padding: 20px;
            }

            .config-header h4 {
                font-size: 20px !important;
                letter-spacing: 1px;
            }
        }

        @media (max-width: 576px) {
            .config-grid {
                grid-template-columns: 1fr;
                gap: 15px;
                padding: 5px;
            }

            .config-card {
                padding: 25px 15px;
                min-height: 180px;
            }

            .icon-circle {
                width: 90px !important;
                height: 90px !important;
                margin-bottom: 12px !important;
            }

            .config-icon {
                font-size: 38px !important;
            }

            .config-title {
                font-size: 12px !important;
            }

            .config-header h4 {
                font-size: 18px !important;
            }
        }

        /* Efecto de brillo en hover */
        @keyframes glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(64, 224, 255, 0.3);
            }
            50% {
                box-shadow: 0 0 40px rgba(64, 224, 255, 0.6);
            }
        }

        .config-card:hover {
            animation: glow 2s ease-in-out infinite;
        }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <?php
        if ($usuario['id_rol'] == 1 || $usuario['id_rol'] == 12) {
            ?>
            <a href="menu_administrativo.php" class="logo">
                <span class="logo-mini"><b>SI</b>MA</span>
                <?php
                $resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
                while($f = mysqli_fetch_array($resultado)){
                    $id_simg=$f['id_simg'];
                    ?>
                    <center><span class="fondo"><img src="../configuracion/admin/img/<?php echo $f['img_base']?>" alt="imgsistema" class="img-fluid" width="112"></span></center>
                    <?php
                }
                ?>
            </a>
            <?php
        } else if ($usuario['id_rol'] == 5) {
            ?>
            <a href="menu_gerencia.php" class="logo">
                <span class="logo-mini"><b>SI</b>MA</span>
                <?php
                $resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
                while($f = mysqli_fetch_array($resultado)){
                    $id_simg=$f['id_simg'];
                    ?>
                    <center><span class="fondo"><img src="../configuracion/admin/img/<?php echo $f['img_base']?>" alt="imgsistema" class="img-fluid" width="112"></span></center>
                    <?php
                }
                ?>
            </a>
            <?php
        } else if ($usuario['id_rol'] == 6) {
            ?>
            <a href="menu_configuracion.php" class="logo">
                <span class="logo-mini"><b>SI</b>MA</span>
                <?php
                $resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
                while($f = mysqli_fetch_array($resultado)){
                    $id_simg=$f['id_simg'];
                    ?>
                    <center><span class="fondo"><img src="../configuracion/admin/img/<?php echo $f['img_base']?>" alt="imgsistema" class="img-fluid" width="112"></span></center>
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
                            <img src="../imagenes/<?php echo $usuario['img_perfil'];?>" class="user-image" alt="User Image" />
                            <span class="hidden-xs"><?php echo $usuario['papell']; ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img src="../imagenes/<?php echo $usuario['img_perfil'];?>" class="img-circle" alt="User Image" />
                                <p><?php echo $usuario['papell']; ?></p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-right">
                                    <a href="../cerrar_sesion.php" class="btn btn-default btn-flat">Cerrar sesión</a>
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
                    <img src="../imagenes/<?php echo $usuario['img_perfil'];?>" class="img-circle" alt="User Image" />
                </div>
                <div class="pull-left info">
                    <p><?php echo $usuario['papell']; ?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> ACTIVO</a>
                </div>
            </div>

            <ul class="sidebar-menu">
                <?php if ($usuario['id_usua'] == 1): ?>
                    <li class="">
                        <a href="../configuracion/camas/cat_camas.php">
                            <i class="fa fa-bed"></i> <span>GESTIÓN DE CAMAS</span>
                        </a>
                    </li>
                <?php endif; ?>

                <li class="">
                    <a href="../configuracion/personal/alta_usuarios.php">
                        <i class="fa fa-users"></i> <span>GESTIÓN DE PERSONAL</span>
                    </a>
                </li>

                <li class="">
                    <a href="../configuracion/diagnosticos/cat_diagnosticos.php">
                        <i class="fa fa-stethoscope" aria-hidden="true"></i> <span>DIAGNÓSTICOS</span>
                    </a>
                </li>

                <li class="">
                    <a href="../configuracion/servicios/cat_servicios.php">
                        <i class="fa fa-plus-circle"></i> <span>SERVICIOS</span>
                    </a>
                </li>

                <li class="">
                    <a href="../configuracion/aseguradoras/aseguradora.php">
                        <i class="fa fa-medkit" aria-hidden="true"></i> <span>ASEGURADORAS</span>
                    </a>
                </li>

                <li class="">
                    <a href="../configuracion/dietas/cat_dietas.php">
                        <i class="fa fa-folder" aria-hidden="true"></i> <span>DIETAS</span>
                    </a>
                </li>

                <li class="">
                    <a href="../configuracion/especialidad/cat_espec.php">
                        <i class="fa fa-user-md" aria-hidden="true"></i> <span>ESPECIALIDADES</span>
                    </a>
                </li>
            </ul>
        </section>
    </aside>

    <div class="content-wrapper">
        <div class="config-menu-container">
            <div class="config-header">
                <h4><i class="fas fa-cogs"></i>CONFIGURACIÓN DEL SISTEMA</h4>
            </div>

            <?php if ($usuario['id_rol'] == 5 || $usuario['id_rol'] == 6): ?>
                <div class="config-grid">
                    <?php if ($usuario['id_usua'] == 1): ?>
                        <div class="config-card">
                            <a href="../configuracion/camas/cat_camas.php" title="GESTIÓN DE CAMAS">
                                <div class="icon-circle">
                                    <i class="fas fa-bed config-icon"></i>
                                </div>
                                <h3 class="config-title">GESTIÓN DE CAMAS</h3>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="config-card">
                        <a href="../configuracion/personal/alta_usuarios.php" title="GESTIÓN DE PERSONAL">
                            <div class="icon-circle">
                                <i class="fas fa-user-nurse config-icon"></i>
                            </div>
                            <h3 class="config-title">GESTIÓN DE PERSONAL</h3>
                        </a>
                    </div>

                    <div class="config-card">
                        <a href="../configuracion/diagnosticos/cat_diagnosticos.php" title="DIAGNÓSTICOS">
                            <div class="icon-circle">
                                <i class="fas fa-stethoscope config-icon"></i>
                            </div>
                            <h3 class="config-title">DIAGNÓSTICOS</h3>
                        </a>
                    </div>

                    <div class="config-card">
                        <a href="../configuracion/servicios/cat_servicios.php" title="SERVICIOS">
                            <div class="icon-circle">
                                <i class="fas fa-concierge-bell config-icon"></i>
                            </div>
                            <h3 class="config-title">SERVICIOS</h3>
                        </a>
                    </div>

                    <div class="config-card">
                        <a href="../configuracion/aseguradoras/aseguradora.php" title="ASEGURADORAS">
                            <div class="icon-circle">
                                <i class="fas fa-shield-alt config-icon"></i>
                            </div>
                            <h3 class="config-title">ASEGURADORAS</h3>
                        </a>
                    </div>

                    <div class="config-card">
                        <a href="../configuracion/dietas/cat_dietas.php" title="DIETAS">
                            <div class="icon-circle">
                                <i class="fas fa-utensils config-icon"></i>
                            </div>
                            <h3 class="config-title">DIETAS</h3>
                        </a>
                    </div>

                    <div class="config-card">
                        <a href="../configuracion/especialidad/cat_espec.php" title="ESPECIALIDADES">
                            <div class="icon-circle">
                                <i class="fas fa-user-md config-icon"></i>
                            </div>
                            <h3 class="config-title">ESPECIALIDADES</h3>
                        </a>
                    </div>

                    <div class="config-card">
                        <a href="../configuracion/admin/imgsistema.php" title="ADMIN SIMA">
                            <div class="icon-circle">
                                <i class="fas fa-cog config-icon"></i>
                            </div>
                            <h3 class="config-title">ADMIN SIMA</h3>
                        </a>
                    </div>
                </div>

            <?php elseif ($usuario['id_rol'] == 1 || $usuario['id_rol'] == 12): ?>
                <div class="config-grid">
                    <div class="config-card">
                        <a href="../configuracion/personal/alta_usuarios.php" title="GESTIÓN DE PERSONAL">
                            <div class="icon-circle">
                                <i class="fas fa-user-nurse config-icon"></i>
                            </div>
                            <h3 class="config-title">GESTIÓN DE PERSONAL</h3>
                        </a>
                    </div>

                    <div class="config-card">
                        <a href="../configuracion/servicios/cat_servicios.php" title="SERVICIOS">
                            <div class="icon-circle">
                                <i class="fas fa-concierge-bell config-icon"></i>
                            </div>
                            <h3 class="config-title">SERVICIOS</h3>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-xs-6">
                            <div class="row">
                                <div class="col-lg-1"></div>
                                <div class="col-lg-8">
                                    <center>
                                        <a href="../configuracion/camas/cat_camas.php" title="HABITACIONES"><img class="card-img-top" src="../img/habitacion.png" alt="HABITACIONES" height="150" width="200" /></a>
                                    </center>
                                    <center>
                                        <h3 style="color: #ffffff;">HABITACIONES</h3>
                                    </center>
                                </div>
                                <div class="col-lg-1"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xs-6">
                            <div class="row">
                                <div class="col-lg-1"></div>
                                <div class="col-lg-8">
                                    <center>
                                        <a href="../configuracion/camas/dispo_camas.php" title="OCUPACIÓN DE CAMAS"><img class="card-img-top" src="../img/camas_hospital.png" alt="OCUPACIÓN DE CAMAS" height="150" width="200" /></a>
                                    </center>
                                    <center>
                                        <h3 style="color: #ffffff;">OCUPACIÓN DE CAMAS</h3>
                                    </center>
                                </div>
                                <div class="col-lg-1"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">CERRAR</button>
                </div>
            </div>
        </div>
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
