<?php
include "../../conexionbd.php";
$lifetime = 100000;
session_set_cookie_params($lifetime);
session_start();

if (!isset($_SESSION['login'])) {
    session_unset();
    session_destroy();
    header('Location: ../../index.php');
    exit;
}

$usuario = $_SESSION['login'];

if (!($usuario['id_rol'] == 2 || $usuario['id_rol'] == 5 || $usuario['id_rol'] == 12)) {
    session_unset();
    session_destroy();
    header('Location: ../../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Médica del Ángel Custodio</title>
    <link rel="icon" href="../../imagenes/SIF.PNG">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- Ionicons -->
    <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet">

    <!-- AdminLTE -->
    <link href="../../template/dist/css/AdminLTE.min.css" rel="stylesheet">
    <link href="../../template/dist/css/skins/_all-skins.min.css" rel="stylesheet">

    <!-- Plugins -->
    <link href="../../template/plugins/morris/morris.css" rel="stylesheet">
    <link href="../../template/plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet">
    <link href="../../template/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>

    <!-- AdminLTE Scripts -->
    <script src="../../template/dist/js/pages/dashboard2.js"></script>
    <script src="../../template/dist/js/demo.js"></script>

    <!-- Plugins JS -->
    <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
    <script src="../../template/plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="../../template/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="../../template/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="../../template/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="../../template/plugins/datepicker/bootstrap-datepicker.js"></script>
    <script src="../../template/plugins/iCheck/icheck.min.js"></script>
    <script src="../../template/plugins/slimScroll/jquery.slimscroll.min.js"></script>

</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <header class="main-header">
            <?php if ($usuario['id_rol'] == 2): ?>
                <a href="../../template/menu_medico.php" class="logo">
                    <span class="logo-lg"><img src="../../imagenes/SI.PNG" height="30" width="120"></span>
                </a>
            <?php elseif ($usuario['id_rol'] == 5): ?>
                <a href="../../template/menu_gerencia.php" class="logo">
                    <span class="logo-lg"><img src="../../imagenes/SI.PNG" height="30" width="120"></span>
                </a>
            <?php elseif ($usuario['id_rol'] == 12): ?>
                <a href="../../template/menu_residente.php" class="logo">
                    <span class="logo-lg"><img src="../../imagenes/SI.PNG" height="30" width="120"></span>
                </a>
            <?php endif; ?>

            <nav class="navbar navbar-static-top" role="navigation">
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                </a> <!-- cierre del sidebar-toggle -->

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- Mensajes, notificaciones, tareas, usuario, etc. -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="../../imagenes/user.jpg" class="user-image" alt="User Image">
                                <span class="hidden-xs"><?php echo $usuario['nombre']; ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="../../imagenes/user.jpg" class="img-circle" alt="User Image">
                                    <p>
                                        <?php echo $usuario['nombre']; ?> - Rol: <?php echo $usuario['rol_nombre']; ?>
                                        <small>Miembro desde <?php echo date("d M. Y", strtotime($usuario['fecha_registro'])); ?></small>
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="../../perfil.php" class="btn btn-default btn-flat">Perfil</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="../../logout.php" class="btn btn-default btn-flat">Cerrar sesión</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- Control Sidebar Toggle Button -->
                        <li>
                            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Aquí generalmente comienza el contenido principal -->
