<?php
require_once "../../conexionbd.php";

if (!isset($_SESSION['login'])) {
    // remove all session variables
    session_unset();
    // destroy the session
    session_destroy();
    //  header('Location: ../index.php');
}
$usuario1 = $_SESSION['login'];

if (isset($usuario1['id_usua'])) {
    $id_usuario = $usuario1['id_usua'];
    $resultado = $conexion->query("SELECT * FROM reg_usuarios WHERE id_usua='" . $id_usuario . "'") or die($conexion->error);
} else {
    // Si no hay id_usua, redirigir o manejar el error
    session_unset();
    session_destroy();
    header('Location: ../../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>INEO Metepec</title>
    <link rel="icon" type="image/png" href="../../imagenes/SIF.PNG">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="../../template/dist/js/pages/dashboard2.js" type="text/javascript"></script>

    <!-- AdminLTE for demo purposes -->
    <script src="../../template/dist/js/demo.js" type="text/javascript"></script>

    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Morris chart -->
    <link href="../../template/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
    <!-- jvectormap -->
    <link href="../../template/plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <!-- Daterange picker -->
    <link href="../../template/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="../../template/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins
           folder instead of downloading all of them to reduce the load. -->
    <link href="../../template/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

    <!-- Ionicons -->
    <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Morris chart -->
    <link href="../../template/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
    <!-- jvectormap -->
    <link href="../../template/plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <!-- Daterange picker -->
    <link href="../../template/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="../../template/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins
           folder instead of downloading all of them to reduce the load. -->
    <link href="../../template/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    <!-- jQuery 2.1.3 -->
    <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>


    <!-- FastClick -->
    <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
    <!-- Sparkline -->
    <script src="../../template/plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
    <!-- jvectormap -->
    <script src="../../template/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
    <script src="../../template/plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
    <!-- daterangepicker -->
    <script src="../../template/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
    <!-- datepicker -->
    <script src="../../template/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="../../template/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="../../template/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- ChartJS 1.0.1 -->
    <script src="../../template/plugins/chartjs/Chart.min.js" type="text/javascript"></script>

    <!-- ESTILOS PARA FONDO OSCURO CON COLORES DE LA IMAGEN -->
    <style>
        /* === PALETA DE COLORES DE LA IMAGEN === */
        :root {
            --azul-oscuro: #0f172a;
            --azul-medio: #1e293b;
            --azul-claro: #334155;
            --azul-neon: #06b6d4;
            --azul-cian: #22d3ee;
            --gris-oscuro: #1e1e2e;
            --gris-medio: #2d2d44;
            --blanco: #f1f5f9;
            --verde: #10b981;
            --rojo: #ef4444;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.3);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.4);
            --shadow-lg: 0 8px 32px rgba(0,0,0,0.5);
        }

        /* === OVERRIDE PARA FONDO OSCURO === */
        body.hold-transition.skin-blue.sidebar-mini,
        body.hold-transition.skin-blue,
        .skin-blue .wrapper,
        .skin-blue .main-header .navbar,
        .skin-blue .main-header .logo,
        .skin-blue .main-sidebar,
        .skin-blue .content-wrapper,
        .skin-blue .main-footer {
            background: linear-gradient(135deg, var(--azul-oscuro) 0%, var(--gris-oscuro) 100%) !important;
            color: var(--blanco) !important;
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
                radial-gradient(circle at 20% 50%, rgba(6, 182, 212, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(6, 182, 212, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(6, 182, 212, 0.03) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .wrapper {
            position: relative;
            z-index: 1;
        }
        
        /* Header principal - COLORES DE LA IMAGEN */
        .main-header {
            background: linear-gradient(135deg, var(--azul-oscuro) 0%, var(--gris-oscuro) 100%) !important;
            border-bottom: 2px solid var(--azul-neon) !important;
            box-shadow: 0 4px 20px rgba(6, 182, 212, 0.2);
        }
        
        .main-header .logo {
            background: linear-gradient(135deg, var(--azul-medio) 0%, var(--azul-oscuro) 100%) !important;
            border-right: 2px solid var(--azul-neon) !important;
            color: var(--azul-neon) !important;
        }
        
        .main-header .navbar {
            background: transparent !important;
        }
        
        /* Sidebar - COLORES DE LA IMAGEN */
        .main-sidebar {
            background: linear-gradient(180deg, var(--azul-oscuro) 0%, var(--gris-oscuro) 100%) !important;
            border-right: 2px solid var(--azul-neon) !important;
            box-shadow: 4px 0 20px rgba(6, 182, 212, 0.15);
        }
        
        .sidebar-menu > li > a {
            color: var(--blanco) !important;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
            font-size: 14px !important;
            padding: 12px 15px !important;
        }
        
        .sidebar-menu > li > a:hover,
        .sidebar-menu > li.active > a {
            background: rgba(6, 182, 212, 0.1) !important;
            border-left: 3px solid var(--azul-neon) !important;
            color: var(--azul-neon) !important;
        }
        
        .user-panel {
            border-bottom: 1px solid rgba(6, 182, 212, 0.2);
        }
        
        .user-panel .info {
            color: var(--blanco) !important;
        }
        
        /* Content wrapper */
        .content-wrapper {
            background: transparent !important;
            min-height: 100vh;
        }
        
        /* Breadcrumb - COLORES DE LA IMAGEN */
        .content-wrapper .breadcrumb {
            background: linear-gradient(135deg, var(--azul-medio) 0%, var(--azul-oscuro) 100%) !important;
            border: 2px solid var(--azul-neon) !important;
            border-radius: 15px !important;
            padding: 20px 30px !important;
            margin: 20px 15px 40px 15px !important;
            box-shadow: 0 8px 30px rgba(6, 182, 212, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .content-wrapper .breadcrumb::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
            animation: pulse 3s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }
        
        .content-wrapper .breadcrumb .breadcrumb-item,
        .content-wrapper .breadcrumb .breadcrumb-item.active {
            color: var(--blanco) !important;
            font-size: 1.2rem !important;
            font-weight: 600 !important;
            text-shadow: 0 0 10px rgba(6, 182, 212, 0.5);
        }
        
        .content-wrapper .breadcrumb .breadcrumb-item.active {
            color: var(--azul-neon) !important;
        }
        
        .content-wrapper .breadcrumb-item + .breadcrumb-item::before {
            color: var(--azul-cian) !important;
            content: ">";
        }
        
        .content-wrapper .breadcrumb h4 {
            color: var(--blanco) !important;
            margin: 0;
            padding: 0;
            font-weight: 700 !important;
            letter-spacing: 1px;
            text-shadow: 0 0 20px rgba(6, 182, 212, 0.5);
            position: relative;
            z-index: 1;
        }
        
        /* Navbar dropdown */
        .dropdown-menu {
            background: linear-gradient(135deg, var(--azul-oscuro) 0%, var(--gris-oscuro) 100%) !important;
            border: 2px solid var(--azul-neon) !important;
            border-radius: 15px !important;
            box-shadow: 0 10px 30px rgba(6, 182, 212, 0.2);
        }
        
        .dropdown-menu > li > a {
            color: var(--blanco) !important;
        }
        
        .dropdown-menu > li > a:hover {
            background: rgba(6, 182, 212, 0.1) !important;
            color: var(--azul-neon) !important;
        }
        
        /* User panel en navbar */
        .user-image {
            border: 2px solid var(--azul-neon) !important;
            box-shadow: 0 0 10px rgba(6, 182, 212, 0.3);
        }
        
        .user-header {
            background: linear-gradient(135deg, var(--azul-medio) 0%, var(--azul-oscuro) 100%) !important;
            border-bottom: 2px solid var(--azul-neon) !important;
        }
        
        .user-footer {
            background: rgba(30, 41, 59, 0.5) !important;
        }
        
        /* === ESTILOS ORIGINALES MODIFICADOS === */
        .dropdwn {
            float: left;
            overflow: hidden;
        }

        .dropdwn .dropbtn {
            cursor: pointer;
            font-size: 16px;
            border: none;
            outline: none;
            color: var(--blanco) !important;
            padding: 14px 16px;
            background-color: inherit;
            font-family: inherit;
            margin: 0;
        }

        .navbar a:hover,
        .dropdwn:hover .dropbtn,
        .dropbtn:focus {
            background-color: rgba(6, 182, 212, 0.3) !important;
        }

        .dropdwn-content {
            display: none;
            position: absolute;
            background: linear-gradient(135deg, var(--azul-oscuro) 0%, var(--gris-oscuro) 100%) !important;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.5);
            z-index: 1;
            border: 1px solid var(--azul-neon);
        }

        .dropdwn-content a {
            float: none;
            color: var(--blanco) !important;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }

        .dropdwn-content a:hover {
            background-color: rgba(6, 182, 212, 0.2) !important;
        }

        .show {
            display: block;
        }

        * {
            box-sizing: border-box;
        }

        .todo-container {
            max-width: 15000px;
            height: auto;
            display: flex;
            overflow-y: scroll;
            column-gap: 0.5em;
            column-rule: 1px solid var(--azul-neon);
            column-width: 140px;
            column-count: 7;
        }

        .status {
            width: 25%;
            background: linear-gradient(135deg, var(--azul-medio) 0%, var(--azul-oscuro) 100%) !important;
            position: relative;
            padding: 60px 1rem 0.5rem;
            height: 100%;
            border: 1px solid var(--azul-neon);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(6, 182, 212, 0.1);
        }

        .status h4 {
            position: absolute;
            top: 0;
            left: 0;
            background: linear-gradient(135deg, var(--azul-medio) 0%, var(--azul-oscuro) 100%) !important;
            color: var(--azul-neon) !important;
            margin: 0;
            width: 100%;
            padding: 0.5rem 1rem;
            border-bottom: 1px solid var(--azul-neon);
            border-radius: 15px 15px 0 0;
            font-weight: 600;
            text-shadow: 0 0 10px rgba(6, 182, 212, 0.3);
        }

        td.fondosan {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 41, 59, 0.8) 100%) !important;
            color: var(--blanco) !important;
            border-left: 3px solid var(--azul-neon) !important;
            border: 1px solid rgba(6, 182, 212, 0.3) !important;
            border-radius: 8px !important;
        }
        
        /* Textos en sidebar */
        .sidebar-menu font {
            color: var(--blanco) !important;
        }
        
        .sidebar-menu font:hover {
            color: var(--azul-neon) !important;
        }
        
        /* Online status */
        .fa-circle.text-success {
            color: var(--verde) !important;
        }
        
        /* Asegurar que todos los textos sean visibles */
        .main-header a,
        .main-header span,
        .navbar a,
        .navbar span,
        .sidebar a,
        .sidebar span,
        .breadcrumb a,
        .breadcrumb span {
            color: var(--blanco) !important;
        }
        
        .main-header a:hover,
        .navbar a:hover,
        .sidebar a:hover {
            color: var(--azul-neon) !important;
        }
        
        /* Botón cerrar sesión */
        .btn-default.btn-flat {
            background: linear-gradient(135deg, var(--azul-medio) 0%, var(--azul-oscuro) 100%) !important;
            color: var(--blanco) !important;
            border: 1px solid var(--azul-neon) !important;
            border-radius: 25px !important;
            padding: 8px 20px !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 15px rgba(6, 182, 212, 0.2);
        }
        
        .btn-default.btn-flat:hover {
            background: linear-gradient(135deg, var(--azul-oscuro) 0%, var(--azul-medio) 100%) !important;
            border-color: var(--azul-cian) !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(6, 182, 212, 0.4);
        }
        
        /* Botones mejorados */
        .btn-success {
            background: linear-gradient(135deg, var(--verde) 0%, #059669 100%) !important;
            color: var(--blanco) !important;
            border-color: var(--verde) !important;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
            border-radius: 25px !important;
            padding: 8px 20px !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
        }
        
        .btn-success:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
            border-color: var(--verde) !important;
        }
        
        /* Íconos en botones */
        .btn i {
            margin-right: 5px;
            transition: transform 0.3s ease;
        }
        
        .btn:hover i {
            transform: scale(1.2);
        }
        
        /* Estilos para el toggle del sidebar */
        .sidebar-toggle {
            color: var(--azul-neon) !important;
        }
        
        .sidebar-toggle:hover {
            background: rgba(6, 182, 212, 0.1) !important;
        }
        
        /* Responsive */
        @media screen and (max-width: 768px) {
            .content-wrapper .breadcrumb {
                padding: 15px 20px !important;
                margin: 15px 10px 30px 10px !important;
            }
            
            .content-wrapper .breadcrumb h4 {
                font-size: 1.1rem !important;
                letter-spacing: 1px;
            }
            
            .content-wrapper .breadcrumb .breadcrumb-item {
                font-size: 1rem !important;
            }
            
            .btn-default.btn-flat, .btn-success {
                padding: 6px 15px !important;
                font-size: 12px !important;
            }
        }
        
        @media screen and (max-width: 576px) {
            .content-wrapper .breadcrumb h4 {
                font-size: 1rem !important;
            }
            
            .content-wrapper .breadcrumb .breadcrumb-item {
                font-size: 0.9rem !important;
            }
            
            .btn-default.btn-flat, .btn-success {
                padding: 6px 12px !important;
                font-size: 11px !important;
            }
        }
        
        /* Scrollbar personalizado */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--azul-oscuro);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--azul-neon) 0%, var(--azul-medio) 100%);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, var(--azul-cian) 0%, var(--azul-neon) 100%);
        }
        
        /* Estilos específicos para el breadcrumb que venía en blanco */
        nav[aria-label="breadcrumb"] {
            background: transparent !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        
        .breadcrumb {
            background: linear-gradient(135deg, var(--azul-medio) 0%, var(--azul-oscuro) 100%) !important;
            border: 2px solid var(--azul-neon) !important;
            color: var(--blanco) !important;
        }
        
        .breadcrumb-item.active {
            color: var(--azul-neon) !important;
        }
        
        /* Override de Bootstrap que causa el color blanco */
        .bg-light {
            background-color: var(--azul-oscuro) !important;
        }
        
        .bg-white {
            background-color: var(--azul-oscuro) !important;
        }
        
        .text-dark {
            color: var(--blanco) !important;
        }

        
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <!-- <img src="dist/img/logo.jpg" alt="logo">-->

        <?php
        if ($usuario1['id_rol'] == 10 ) {
            ?>

            <a href="../../template/menu_laboratorio.php" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                
                <!-- logo for regular state and mobile devices -->
               <?php
$resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
while($f = mysqli_fetch_array($resultado)){
       $id_simg=$f['id_simg'];
?>
            <center><span class="fondo"><img src="../../configuracion/admin/img/<?php echo $f['img_base']?>" alt="imgsistema" class="img-fluid" width="112"></span></center>
          <?php
}
?>
            </a>
            <?php
        } else if ($usuario1['id_rol'] == 5) {

            ?>
            <a href="../../template/menu_gerencia.php" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                
                <!-- logo for regular state and mobile devices -->
                <?php
$resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
while($f = mysqli_fetch_array($resultado)){
       $id_simg=$f['id_simg'];
?>
            <center><span class="fondo"><img src="../../configuracion/admin/img/<?php echo $f['img_base']?>" alt="imgsistema" class="img-fluid" width="112"></span></center>
          <?php
}
?>
            </a>
            <?php
        } else if ($usuario1['id_rol'] == 12) {

            ?>
            <a href="../../template/menu_residente.php" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                
                <!-- logo for regular state and mobile devices -->
               <?php
$resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
while($f = mysqli_fetch_array($resultado)){
       $id_simg=$f['id_simg'];
?>
            <center><span class="fondo"><img src="../../configuracion/admin/img/<?php echo $f['img_base']?>" alt="imgsistema" class="img-fluid" width="112"></span></center>
          <?php
}
?>
            </a>
            <?php
        }
         else
            //session_unset();
            session_destroy();
        echo "<script>window.Location='../../index.php';</script>";
        ?>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>


            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="../../imagenes/<?php echo $usuario1['img_perfil']; ?>" class="user-image" alt="User Image" />
                            <span class="hidden-xs"> <?php echo $usuario1['papell']; ?> </span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="../../imagenes/<?php echo $usuario1['img_perfil']; ?>" class="img-circle" alt="User Image" />
                                <p>
                                     <?php echo $usuario1['papell']; ?> 
                                </p>
                            </li>

                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-right">
                                    <a href="../../cerrar_sesion.php" class="btn btn-default btn-flat">CERRAR SESIÓN</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="../../imagenes/<?php echo $usuario1['img_perfil']; ?>" class="img-circle" alt="User Image" />
                </div>
                <div class="pull-left info">
                    <p> <?php echo $usuario1['papell']; ?></p>

                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>

            <!-- sidebar menu: : style can be found in sidebar.less -->
           
             </a>
            <?php
            if ($usuario1['id_rol'] <> 12) {

            ?>
            <ul class="sidebar-menu">

                <!--<li class="treeview">
                    <a href="../Laboratorio/lista_paquetes_labo.php">
                        <i class="fa fa-folder"></i> <font size="2">PERFILES</font>
                    </a>

                </li>-->

                <li class="treeview">
                    <a href="../Laboratorio/sol_laboratorio.php">
                        <i class="fa fa-folder"></i> <font size="2">SOLICITUD DE ESTUDIOS<br>DE LABORATORIO</font>

                    </a>

                </li>
                <li class="treeview">
                    <a href="../Laboratorio/sol_gabinete.php">
                        <i class="fa fa-folder"></i> <font size="2">SOLICITUD DE ESTUDIOS <br>DE GABINETE</font>

                    </a>

                </li>
                <li class="treeview">
                    <a href="../Laboratorio/sol_patologia.php">
                        <i class="fa fa-folder"></i> <font size="2">SOLICITUD DE ESTUDIOS <br>DE PATOLOGÍA</font>
                       

                    </a>

                </li>
                <li class="treeview">
                    <a href="../Laboratorio/resultados_labo.php">
                        <i class="fa fa-folder"></i> <font size="2">CONSULTAR RESULTADOS<br>ESTUDIOS DE LABORATORIO</font>

                    </a>

                </li>
                <li class="treeview">
                    <a href="../Laboratorio/resultados_gab.php">
                        <i class="fa fa-folder"></i> <font size="2">CONSULTAR RESULTADOS<br>ESTUDIOS DE GABINETE</font>

                    </a>

                </li>
                <li class="treeview">
                    <a href="../Laboratorio/resultados_pato.php">
                        <i class="fa fa-folder"></i> <font size="2">CONSULTAR RESULTADOS <br>ESTUDIOS DE PATOLOGÍA</font>

                    </a>

                </li>
                
                 
                
                
               <br>
        </ul>
          
            
 <?php
        } 

            ?>

        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Right side column. Contains the navbar and content of the page -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <!--AQUI VA QUE PUESTO TIENE-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><STRONG>
                        <h4>USUARIO LABORATORIO</h4>
                    </STRONG></li>
            </ol>
        </nav>
        <script>
            function SoloLetras(e) {
                key = e.keyCode || e.which;
                tecla = String.fromCharCode(key).toString();
                letras = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚabcdefghijklmnñopqrstuvwxyzáéíóú ";

                especiales = [8, 13];
                tecla_especial = false
                for (var i in especiales) {
                    if (key == especiales[i]) {
                        tecla_especial = true;
                        break;
                    }
                }

                if (letras.indexOf(tecla) == -1 && !tecla_especial) {
                    alert("Ingresar solo letras");
                    return false;
                }
            }
        </script>

        <script>
            function SoloNumeros(evt) {
                if (window.event) {
                    keynum = evt.keyCode;
                } else {
                    keynum = evt.which;
                }

                if ((keynum > 47 && keynum < 58) || keynum == 8 || keynum == 13 || keynum == 46 || keynum == 44) {
                    return true;
                } else {
                    alert("Ingresar solo numeros");
                    return false;
                }
            }
        </script>