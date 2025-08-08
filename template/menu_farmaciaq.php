<?php
include "../conexionbd.php";
session_start();
//
if (!isset($_SESSION['login'])) {
    // remove all session variables
    session_unset();
    // destroy the session
    session_destroy();
    header('Location: ../index.php');
}
$usuario = $_SESSION['login'];
//$resultado = $conexion->query("SELECT * FROM reg_usuarios WHERE id_usua='" . $usuario . "'") or die($conexion->error);
if (!($usuario['id_rol'] == 11 || $usuario['id_rol'] == 5 || $usuario['id_rol'] == 4)) {
    session_unset();
    session_destroy();
    // echo "<script>window.Location='../index.php';</script>";
    header('Location: ../index.php');
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>INEO Metepec</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons 6.0.0 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"
        type="text/css" />
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
    <!-- AdminLTE Skins. Choose a skin from the css/skins
           folder instead of downloading all of them to reduce the load. -->
    <link href="dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <style>
        /* Estilos para las tarjetas del menú principal */
        .menu-card {
            background: var(--card-color);
            border-radius: 15px;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            text-decoration: none;
            color: white;
            border: none;
            position: relative;
            overflow: hidden;
            margin-bottom: 20px;
            height: 220px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .menu-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.3);
            text-decoration: none;
            color: white;
        }

        .menu-icon {
            width: 90px;
            height: 90px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.3);
        }

        .menu-icon i {
            font-size: 2.8rem;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .menu-title {
            font-size: 1rem;
            font-weight: 700;
            color: white;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            line-height: 1.2;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }

        /* Colores específicos para cada módulo */
        .card-surtir { --card-color: linear-gradient(135deg, #28a745, #20c997); }
        .card-existencias { --card-color: linear-gradient(135deg, #17a2b8, #6610f2); }
        .card-kardex { --card-color: linear-gradient(135deg, #6f42c1, #e83e8c); }
        .card-caducados { --card-color: linear-gradient(135deg, #ffc107, #fd7e14); }
        .card-devoluciones { --card-color: linear-gradient(135deg, #dc3545, #fd7e14); }
        .card-confirmar { --card-color: linear-gradient(135deg, #007bff, #6610f2); }
        .card-pedir { --card-color: linear-gradient(135deg, #fd7e14, #ffc107); }
        .card-salidas { --card-color: linear-gradient(135deg, #e83e8c, #dc3545); }
        .card-inventario { --card-color: linear-gradient(135deg, #6c757d, #495057); }

        /* Mantener estilos originales del AdminLTE */
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
    </style>
</head>

<body class=" hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <!-- <img src="dist/img/logo.jpg" alt="logo">-->
            <?php
            if ($usuario['id_rol'] == 11) {
            ?>

                <a href="menu_farmaciacentral.php" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b><img src="../imagenes/SI.PNG" height="30" width="120"></b></span>
                </a>
            <?php
            } else if ($usuario['id_rol'] == 5) {

            ?>
                <a href="menu_gerencia.php" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b><img src="../imagenes/SI.PNG" height="30" width="120"></b></span>
                </a>
            <?php
            } else {
                //session_unset();
                session_destroy();
                echo "<script>window.Location='../index.php';</script>";
            }
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

                                <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="user-image"
                                    alt="User Image">
                                <span class="hidden-xs"> <?php echo $usuario['nombre']; ?> <?php echo $usuario['papell']; ?> <?php echo $usuario['sapell']; ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">

                                    <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle"
                                        alt="User Image">

                                    <p>
                                        <?php echo $usuario['nombre']; ?> <?php echo $usuario['papell']; ?> <?php echo $usuario['sapell']; ?>

                                    </p>
                                </li>

                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                                    </div>
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
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle" alt="User Image" />
                    </div>
                    <div class="pull-left info">
                        <p><?php echo $usuario['nombre']; ?> <?php echo $usuario['papell']; ?></p>

                        <a href="#"><i class="fas fa-circle text-success"></i> Online</a>
                    </div>
                </div>

                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu">
                    <li class=" treeview">
                        <a href="../sauxiliares/farmaciaq/surtir_pacienteq.php">
                            <i class="fas fa-hand-holding-medical"></i> <span>SURTIR MÉDICAMENTOS</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>

                    </li>
                    <li class=" treeview">
                        <a href="../sauxiliares/farmaciaq/existenciasq.php">
                            <i class="fas fa-boxes"></i> <span>EXISTENCIAS</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>

                    </li>
                    <li class="treeview">
                        <a href="">
                            <i class="fas fa-clipboard-list"></i> <span>TOMA DE INVENTARIO</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>

                    </li>
                    <li class=" treeview">
                        <a href="../sauxiliares/farmaciaq/kardexq.php">
                            <i class="fas fa-chart-line"></i> <span>KARDEX</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>

                    </li>
                    <li class=" treeview">
                        <a href="../sauxiliares/farmaciaq/caducadoq.php">
                            <i class="fas fa-calendar-times"></i> <span>CONTROL DE <br> CADUCIDADES</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>

                    </li>
                    <li class=" treeview">
                        <a href="../sauxiliares/farmaciaq/devolucionesq.php">
                            <i class="fas fa-undo"></i> <span>DEVOLUCIONES</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>

                    </li>

                    <li class="treeview">
                        <a href="../sauxiliares/farmaciaq/confirmar_envioq.php">
                            <i class="fas fa-check-circle"></i> <span>CONFIRMAR DE RECIBIDO</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>

                    </li>
                    <li class="treeview">
                        <a href="../sauxiliares/farmaciaq/pedir_almacenq.php">
                            <i class="fas fa-shopping-cart"></i> <span>PEDIR A ALMACEN</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>

                    </li>
                    <li class="treeview">
                        <a href="../sauxiliares/farmaciaq/salidasq.php">
                            <i class="fas fa-file-export"></i> <span>SALIDAS MEDICAMENTO</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>

                    </li>

                </ul>
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
                            <h4>FARMACIA QUIROFANO</h4>
                        </STRONG></li>
                </ol>
            </nav>

            <!-- Main content -->
            <section class="content">
                <section class="content container-fluid">
                    <div class="content box">
                        <!-- CONTENIDOO -->
                        <div class="row">
                            <div class="col-lg-4 col-xs-6">
                                <a href="../sauxiliares/farmaciaq/surtir_pacienteq.php" class="menu-card card-surtir">
                                    <div class="menu-icon">
                                        <i class="fas fa-hand-holding-medical"></i>
                                    </div>
                                    <h3 class="menu-title">Surtir Medicamentos</h3>
                                </a>
                            </div>
                            <div class="col-lg-4 col-xs-6">
                                <a href="../sauxiliares/farmaciaq/existenciasq.php" class="menu-card card-existencias">
                                    <div class="menu-icon">
                                        <i class="fas fa-boxes"></i>
                                    </div>
                                    <h3 class="menu-title">Existencias</h3>
                                </a>
                            </div>
                            <div class="col-lg-4 col-xs-6">
                                <a href="../sauxiliares/farmaciaq/kardexq.php" class="menu-card card-kardex">
                                    <div class="menu-icon">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <h3 class="menu-title">Kardex</h3>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-xs-6">
                                <a href="../sauxiliares/farmaciaq/caducadoq.php" class="menu-card card-caducados">
                                    <div class="menu-icon">
                                        <i class="fas fa-calendar-times"></i>
                                    </div>
                                    <h3 class="menu-title">Control de Caducidades</h3>
                                </a>
                            </div>
                            <div class="col-lg-4 col-xs-6">
                                <a href="../sauxiliares/farmaciaq/devolucionesq.php" class="menu-card card-devoluciones">
                                    <div class="menu-icon">
                                        <i class="fas fa-undo"></i>
                                    </div>
                                    <h3 class="menu-title">Devoluciones</h3>
                                </a>
                            </div>
                            <div class="col-lg-4 col-xs-6">
                                <a href="../sauxiliares/farmaciaq/confirmar_envioq.php" class="menu-card card-confirmar">
                                    <div class="menu-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <h3 class="menu-title">Confirmar de Recibido</h3>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-xs-6">
                                <a href="../sauxiliares/farmaciaq/pedir_almacenq.php" class="menu-card card-pedir">
                                    <div class="menu-icon">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                    <h3 class="menu-title">Pedir Almacén</h3>
                                </a>
                            </div>
                            <div class="col-lg-4 col-xs-6">
                                <a href="../sauxiliares/farmaciaq/salidasq.php" class="menu-card card-salidas">
                                    <div class="menu-icon">
                                        <i class="fas fa-file-export"></i>
                                    </div>
                                    <h3 class="menu-title">Salidas Medicamentos</h3>
                                </a>
                            </div>
                            <div class="col-lg-4 col-xs-6">
                                <a href="#" class="menu-card card-inventario">
                                    <div class="menu-icon">
                                        <i class="fas fa-clipboard-list"></i>
                                    </div>
                                    <h3 class="menu-title">Toma de Inventario</h3>
                                </a>
                            </div>
                        </div>
                    </div>


                </section><!-- /.content -->
        </div><!-- /.content-wrapper -->

        <footer class="main-footer">
            <?php
            include("footer.php");
            ?>
        </footer>

    </div><!-- ./wrapper -->

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

    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="dist/js/pages/dashboard2.js" type="text/javascript"></script>

    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js" type="text/javascript"></script>


</body>

</html>