<?php
include "../../conexionbd.php";
//session_start();
//

if (!isset($_SESSION['login'])) {
    // remove all session variables
    session_unset();
    // destroy the session
    session_destroy();
    //  header('Location: ../index.php');
}
$usuario = $_SESSION['login'];
//$resultado = $conexion->query("SELECT * FROM reg_usuarios WHERE id_usua='" . $usuario . "'") or die($conexion->error);

if (!($usuario['id_rol'] == 6 || $usuario['id_rol'] == 5 || $usuario['id_rol'] == 1 || $usuario['id_rol'] == 12)) {
    session_unset();
    session_destroy();
    // echo "<script>window.Location='../../index.php';</script>";
    header('Location: ../../index.php');
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
  <title>INEO Metepec
  </title>
  <link rel="icon" href="../imagenes/SIF.PNG">
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







    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <style>

        .dropdwn {
            float: left;
            overflow: hidden;
        }

        .dropdwn .dropbtn {
            cursor: pointer;
            font-size: 16px;
            border: none;
            outline: none;
            color: white;
            padding: 14px 16px;
            background-color: inherit;
            font-family: inherit;
            margin: 0;
        }

        .navbar a:hover,
        .dropdwn:hover .dropbtn,
        .dropbtn:focus {
            background-color: #367fa9;
        }

        .dropdwn-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdwn-content a {
            float: none;
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }

        .dropdwn-content a:hover {
            background-color: #ddd;
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
            column-rule: 1px solid white;
            column-width: 140px;
            column-count: 7;
        }

        .status {
            width: 25%;
            background-color: #ecf0f5;
            position: relative;
            padding: 60px 1rem 0.5rem;
            height: 100%;

        }

        .status h4 {
            position: absolute;
            top: 0;
            left: 0;
            background-color: #0b3e6f;
            color: white;
            margin: 0;
            width: 100%;

            padding: 0.5rem 1rem;
        }
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

<body class=" hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <!-- <img src="dist/img/logo.jpg" alt="logo">-->

        <?php
        if ($usuario['id_rol'] == 1 || $usuario['id_rol'] == 12){?>
            <a href="../../template/menu_administrativo.php" class="logo">
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
            <?php }elseif ($usuario['id_rol'] == 5){?>
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
        }else
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
                            <img src="../../imagenes/<?php echo $usuario['img_perfil'];?>" class="user-image" alt="User Image" />
                            <span class="hidden-xs">  <?php echo $usuario['papell']; ?> </span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="../../imagenes/<?php echo $usuario['img_perfil'];?>" class="img-circle" alt="User Image" />
                                <p>
                                  <?php echo $usuario['papell']; ?>

                                </p>
                            </li>

                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <!--<div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat">Profile</a>
                                </div>-->
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
                    <img src="../../imagenes/<?php echo $usuario['img_perfil'];?>" class="img-circle" alt="User Image" />
                </div>
                <div class="pull-left info">
                    <p><font size ="2"> <?php echo $usuario['papell']; ?></font></p>

                    <a href="#"><i class="fa fa-circle text-success"></i> ACTIVO</a>
                </div>
            </div>

            <!-- sidebar menu: : style can be found in sidebar.less -->

            <ul class="sidebar-menu">
               <?php
        if ($usuario['id_usua'] == 1){?>
                <li class="">

                    <a href="#">
                        <i class="fa fa-bed"></i> <span><font size ="2">GESTIÓN DE CAMAS</font></span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="../camas/cat_camas.php"><i class="fa fa-list-alt"></i><font size ="2">HABITACIONES</font></a></li>
                        <li><a href="../camas/dispo_camas.php"><i class="fa fa-thumbs-up"></i><font size ="2">OCUPACIÓN DE CAMAS</font></a></li>

                    </ul>
                </li>
        <?php } ?>

                <li class="">
                    <a href="../personal/alta_usuarios.php">
                        <i class="fa fa-users"></i> <span><font size ="2">GESTIÓN DE PERSONAL</font></span> <i class="fa fa-angle-left pull-right"></i>
                    </a>

                </li>
                <li class="">
                    <a href="../diagnosticos/cat_diagnosticos.php">
                        <i class="fa fa-stethoscope" aria-hidden="true"></i> <span><font size ="2">DIAGÓSTICOS</font></span> <i class="fa fa-angle-left pull-right"></i>
                    </a>

                </li>
                <li class="">
                    <a href="../servicios/cat_servicios.php">
                        <i class="fa fa-plus-circle"></i> <span><font size ="2">SERVICIOS</font></span> <i class="fa fa-angle-left pull-right"></i>
                    </a>

                </li>
                <li class="">
                    <a href="../aseguradoras/aseguradora.php">
                        <i class="fa fa-medkit" aria-hidden="true"></i> <span><font size ="2">ASEGURADORAS</font></span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                </li>

                </li>
                <li class="">
                    <a href="../dietas/cat_dietas.php">
                        <i class="fa fa-folder" aria-hidden="true"></i> <span><font size ="2">DIETAS</font></span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                </li>
                 <li class="">
                <a href="../especialidad/cat_espec.php">
                    <i class="fa fa-user-md" aria-hidden="true"></i> <span><font size ="2">ESPECIALIDADES</font></span> <i class="fa fa-angle-left pull-right"></i>
                </a>

            </li>


            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Right side column. Contains the navbar and content of the page -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
<br>
        <!--AQUI VA QUE PUESTO TIENE-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><STRONG>
                        <h4>CONFIGURACION DEL SISTEMA</h4>
                    </STRONG></li>
            </ol>
        </nav>
