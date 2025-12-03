<?php
include "../conexionbd.php";
$lifetime=86400;
session_set_cookie_params($lifetime);
session_start();
if (!isset($_SESSION['login'])) {
    session_unset();
    session_destroy();
    header('Location: ../index.php');
}
$usuario = $_SESSION['login'];
$ejecutivo = $usuario['id_usua'];

if (!($usuario['id_rol'] == 5)) {
    session_unset();
    session_destroy();
    echo "<div class='alert alert-danger mt-4' role='alert'>No tienes permiso para ingresar aquí!
  <p><a href='index.php'><strong>Por favor, intente de nuevo!</strong></a></p></div>";
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
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css"/>
    <link href="plugins/morris/morris.css" rel="stylesheet" type="text/css"/>
    <link href="plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css"/>
    <link href="plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css"/>
    <link href="dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css"/>
    <link href="dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css"/>

    <style>
        :root {
            --primary-blue: #2563eb;
            --primary-pink: #ec4899;
            --primary-green: #10b981;
            --primary-orange: #f59e0b;
            --primary-cyan: #06b6d4;
            --primary-purple: #8b5cf6;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.12);
            --shadow-lg: 0 8px 32px rgba(0,0,0,0.16);
        }

        body {
            background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #475569 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        .content-wrapper {
            background: transparent;
        }

        /* Breadcrumb con diseño moderno */
        .modern-breadcrumb {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-radius: 25px;
            padding: 30px 40px;
            margin-bottom: 50px;
            box-shadow: 
                0 20px 60px rgba(217, 119, 6, 0.4),
                0 10px 20px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        .modern-breadcrumb::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
            animation: pulse 8s infinite ease-in-out;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 0.8; }
        }

        .modern-breadcrumb h2 {
            color: white;
            font-weight: 700;
            font-size: 2.2rem;
            margin: 0;
            position: relative;
            z-index: 1;
            text-shadow: 
                2px 2px 4px rgba(0,0,0,0.3),
                0 0 20px rgba(255,255,255,0.2);
            letter-spacing: 1px;
        }

        /* Grid de Tarjetas Mejorado */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
            padding: 20px 0;
        }

        /* Tarjeta Moderna - Efecto Neomórfico */
        .menu-card {
            background: var(--card-bg);
            border-radius: 30px;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 
                12px 12px 24px rgba(0, 0, 0, 0.3),
                -12px -12px 24px rgba(255, 255, 255, 0.1);
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .menu-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, 
                transparent, 
                rgba(255, 255, 255, 0.3), 
                transparent);
            transform: rotate(45deg) translateX(-100%);
            transition: transform 0.6s ease;
        }

        .menu-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--card-color-1), var(--card-color-2));
            transform: scaleX(0);
            transition: transform 0.5s ease;
        }

        .menu-card:hover {
            transform: translateY(-16px) scale(1.02);
            box-shadow: 
                16px 16px 40px rgba(0, 0, 0, 0.4),
                -16px -16px 40px rgba(255, 255, 255, 0.15),
                0 0 30px rgba(var(--card-color-1), 0.3);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .menu-card:hover::before {
            transform: rotate(45deg) translateX(100%);
        }

        .menu-card:hover::after {
            transform: scaleX(1);
        }

        /* Iconos con Efecto Glassmorphism */
        .menu-icon-wrapper {
            width: 140px;
            height: 140px;
            margin: 0 auto 30px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .menu-icon-bg {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--card-color-1), var(--card-color-2));
            opacity: 0.12;
            transition: all 0.5s ease;
            transform: rotate(0deg);
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.1),
                inset 0 0 20px rgba(255, 255, 255, 0.2);
        }

        .menu-icon-bg::before {
            content: '';
            position: absolute;
            top: 10%;
            left: 10%;
            width: 30%;
            height: 30%;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            filter: blur(10px);
        }

        .menu-card:hover .menu-icon-bg {
            opacity: 0.25;
            transform: rotate(180deg) scale(1.15);
            box-shadow: 
                0 12px 48px rgba(0, 0, 0, 0.15),
                inset 0 0 30px rgba(255, 255, 255, 0.3);
        }

        .menu-icon {
            position: relative;
            z-index: 1;
            font-size: 64px;
            background: linear-gradient(135deg, var(--card-color-1), var(--card-color-2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            transition: all 0.5s ease;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
        }

        .menu-card:hover .menu-icon {
            transform: scale(1.2) rotate(-5deg);
            filter: drop-shadow(0 8px 16px rgba(0, 0, 0, 0.2));
        }

        /* Título de Tarjeta */
        .menu-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
            transition: all 0.3s ease;
        }

        .menu-card:hover .menu-title {
            background: linear-gradient(135deg, var(--card-color-1), var(--card-color-2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Descripción */
        .menu-description {
            color: #64748b;
            font-size: 0.95rem;
            margin-top: 12px;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.4s ease;
        }

        .menu-card:hover .menu-description {
            opacity: 1;
            transform: translateY(0);
        }

        /* Colores por Categoría - Paleta Vibrante */
        .card-administrativo {
            --card-color-1: #10b981;
            --card-color-2: #059669;
            --card-bg: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        }

        .card-enfermeria {
            --card-color-1: #f59e0b;
            --card-color-2: #d97706;
            --card-bg: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        }

        .card-medico {
            --card-color-1: #3b82f6;
            --card-color-2: #2563eb;
            --card-bg: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        }

        .card-estudios {
            --card-color-1: #8b5cf6;
            --card-color-2: #7c3aed;
            --card-bg: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
        }

        .card-almacenes {
            --card-color-1: #ef4444;
            --card-color-2: #dc2626;
            --card-bg: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        }

        .card-configuracion {
            --card-color-1: #06b6d4;
            --card-color-2: #0891b2;
            --card-bg: linear-gradient(135deg, #cffafe 0%, #a5f3fc 100%);
        }

        /* Animaciones de Entrada */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .menu-card {
            animation: fadeInUp 0.6s ease-out backwards;
        }

        .menu-card:nth-child(1) { animation-delay: 0.1s; }
        .menu-card:nth-child(2) { animation-delay: 0.2s; }
        .menu-card:nth-child(3) { animation-delay: 0.3s; }
        .menu-card:nth-child(4) { animation-delay: 0.4s; }
        .menu-card:nth-child(5) { animation-delay: 0.5s; }
        .menu-card:nth-child(6) { animation-delay: 0.6s; }

        /* Responsive */
        @media (max-width: 768px) {
            .menu-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .modern-breadcrumb h2 {
                font-size: 1.5rem;
            }

            .menu-icon-wrapper {
                width: 100px;
                height: 100px;
            }

            .menu-icon {
                font-size: 48px;
            }

            .menu-title {
                font-size: 1.2rem;
            }
        }

        /* Link sin decoración */
        .menu-card a {
            text-decoration: none;
            display: block;
        }

        /* Estilos del Sidebar */
        .main-sidebar {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%) !important;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
        }

        .sidebar-menu > li.active > a,
        .sidebar-menu > li:hover > a {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
            border-left: 4px solid #fbbf24;
        }

        .sidebar-menu > li > a {
            color: #e2e8f0 !important;
            transition: all 0.3s ease;
        }

        .sidebar-menu > li > a:hover {
            color: #ffffff !important;
        }

        .treeview-menu > li > a {
            color: #cbd5e1 !important;
            background: rgba(15, 23, 42, 0.5) !important;
            transition: all 0.3s ease;
        }

        .treeview-menu > li > a:hover {
            color: #ffffff !important;
            background: rgba(245, 158, 11, 0.2) !important;
            border-left: 3px solid #f59e0b;
        }

        .user-panel {
            border-bottom: 1px solid rgba(226, 232, 240, 0.1);
        }

        .user-panel .info {
            color: #e2e8f0 !important;
        }

        .user-panel .info p {
            color: #ffffff !important;
            font-weight: 600;
        }

        /* Header ajustado */
        .main-header .navbar {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%) !important;
            border-bottom: 3px solid #f59e0b;
        }

        .main-header .logo {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;
            border-right: 1px solid rgba(245, 158, 11, 0.3);
        }

        .navbar-custom-menu > .navbar-nav > li > .dropdown-menu {
            background: #1e293b;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .user-header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
        }

        .user-footer {
            background: #0f172a !important;
            border-top: 1px solid rgba(245, 158, 11, 0.3);
        }

        .user-footer .btn-default {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
            border: none;
            color: white !important;
            transition: all 0.3s ease;
        }

        .user-footer .btn-default:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
        }

        /* Sidebar toggle button */
        .sidebar-toggle {
            color: #f59e0b !important;
        }

        .sidebar-toggle:hover {
            background: rgba(245, 158, 11, 0.1) !important;
        }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <?php if ($ejecutivo != '429'){?>    
         <a href="menu_gerencia.php" class="logo">
            <span class="logo-mini"><b>SI</b>MA</span>
            <?php
            $resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
            while($f = mysqli_fetch_array($resultado)){
                $id_simg=$f['id_simg'];
            ?>
            <center><span class="fondo"><img src="../configuracion/admin/img/<?php echo $f['img_base']?>" alt="imgsistema" id="meddi" class="img-fluid" width="112"></span></center>
            <?php } ?>
        </a>
        <?php } ?> 
        
        <nav class="navbar navbar-static-top gggg" role="navigation">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="user-image" alt="User Image">
                            <span class="hidden-xs"><?php echo $usuario['papell'];?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle" alt="User Image">
                                <p><?php echo $usuario['papell'];?></p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="../gerencia/editar_perfil/editar_perfil.php?id_usua=<?php echo $usuario['id_usua'];?>" class="btn btn-default btn-flat">MIS DATOS</a>
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
                    <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle" alt="User Image"/>
                </div>
                <div class="pull-left info">
                    <p><?php echo $usuario['papell'];?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> ACTIVO</a>
                </div>
            </div>

            <ul class="sidebar-menu">
                <li class="active treeview">
                    <a href="#">
                        <i class="fa fa-folder"></i> <span><strong>MENÚ GERENTE GENERAL</strong></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="../template/menu_administrativo.php"><i class="fa fa-folder"></i> ADMINISTRATIVO</a></li>
                        <li><a href="../template/menu_enfermera.php"><i class="fa fa-heart"></i> ENFERMERÍA</a></li>
                        <li><a href="../template/menu_medico.php"><i class="fa fa-stethoscope"></i> MÉDICO</a></li>
                        <li><a href="../template/menu_laboratorio.php"><i class="fa fa-circle"></i> ESTUDIOS</a></li> 
                        <li><a href="../template/menu_sauxiliares.php"><i class="fa fa-circle"></i> ALMACENES</a></li>
                        <li><a href="../template/menu_configuracion.php"><i class="fa fa-folder"></i> CONFIGURACIÓN</a></li>
                    </ul>
                </li>
            </ul>
        </section>
    </aside>

    <div class="content-wrapper">
        <div class="modern-breadcrumb">
            <h2><i class="fa fa-dashboard"></i> Panel de Gerencia</h2>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="menu-grid">
                    <!-- MÉDICO -->
                    <div class="menu-card card-medico">
                        <a href="../template/menu_medico.php">
                            <div class="menu-icon-wrapper">
                                <div class="menu-icon-bg"></div>
                                <i class="fa fa-stethoscope menu-icon"></i>
                            </div>
                            <h3 class="menu-title">MÉDICO</h3>
                            <p class="menu-description">Consultas y diagnósticos médicos</p>
                        </a>
                    </div>

                    <!-- ENFERMERÍA -->
                    <div class="menu-card card-enfermeria">
                        <a href="../template/menu_enfermera.php">
                            <div class="menu-icon-wrapper">
                                <div class="menu-icon-bg"></div>
                                <i class="fa fa-heart menu-icon"></i>
                            </div>
                            <h3 class="menu-title">ENFERMERÍA</h3>
                            <p class="menu-description">Cuidados y atención de enfermería</p>
                        </a>
                    </div>

                    <!-- ADMINISTRATIVO -->
                    <div class="menu-card card-administrativo">
                        <a href="../template/menu_administrativo.php">
                            <div class="menu-icon-wrapper">
                                <div class="menu-icon-bg"></div>
                                <i class="fa fa-briefcase menu-icon"></i>
                            </div>
                            <h3 class="menu-title">ADMINISTRATIVO</h3>
                            <p class="menu-description">Gestión administrativa y recursos</p>
                        </a>
                    </div>

                    <!-- ALMACENES -->
                    <div class="menu-card card-almacenes">
                        <a href="../template/menu_sauxiliares.php">
                            <div class="menu-icon-wrapper">
                                <div class="menu-icon-bg"></div>
                                <i class="fa fa-cubes menu-icon"></i>
                            </div>
                            <h3 class="menu-title">ALMACENES</h3>
                            <p class="menu-description">Control de inventarios y suministros</p>
                        </a>
                    </div>

                    <!-- ESTUDIOS -->
                    <div class="menu-card card-estudios">
                        <a href="../template/menu_laboratorio.php">
                            <div class="menu-icon-wrapper">
                                <div class="menu-icon-bg"></div>
                                <i class="fa fa-flask menu-icon"></i>
                            </div>
                            <h3 class="menu-title">ESTUDIOS</h3>
                            <p class="menu-description">Análisis y estudios clínicos</p>
                        </a>
                    </div>

                    <!-- CONFIGURACIÓN -->
                    <div class="menu-card card-configuracion">
                        <a href="../template/menu_configuracion.php">
                            <div class="menu-icon-wrapper">
                                <div class="menu-icon-bg"></div>
                                <i class="fa fa-cogs menu-icon"></i>
                            </div>
                            <h3 class="menu-title">CONFIGURACIÓN</h3>
                            <p class="menu-description">Ajustes y configuración del sistema</p>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <footer class="main-footer footer">
        <?php include("footer.php"); ?>
    </footer>
</div>

<script src="plugins/jQuery/jQuery-2.1.3.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src='plugins/fastclick/fastclick.min.js'></script>
<script src="dist/js/app.min.js" type="text/javascript"></script>
<script src="plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
<script src="plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<script src="plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="plugins/chartjs/Chart.min.js" type="text/javascript"></script>
<script src="dist/js/pages/dashboard2.js" type="text/javascript"></script>
<script src="dist/js/demo.js" type="text/javascript"></script>

</body>
</html>