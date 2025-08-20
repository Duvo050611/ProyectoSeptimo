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
if (!($usuario['id_rol'] == 4 || $usuario['id_rol'] == 5 || $usuario['id_rol'] == 7)) {
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
        <link rel="icon" href="../imagenes/SIF.PNG">

    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"
          type="text/css"/>
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
    <!-- AdminLTE Skins. Choose a skin from the css/skins
           folder instead of downloading all of them to reduce the load. -->
    <link href="dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css"/>


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
    </style>
</head>

<body class=" hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <!-- <img src="dist/img/logo.jpg" alt="logo">-->
        <?php
        if ($usuario['id_rol'] == 4 || $usuario['id_rol'] == 7) {
            ?>

             <a href="menu_gerencia.php" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>SI</b>MA</span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b><img src="../imagenes/SI.PNG" height="30" width="120"></b></span>
                </a>
            <?php
        } else if ($usuario['id_rol'] == 5) {

            ?>
             <a href="menu_gerencia.php" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>SI</b>MA</span>
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
                    <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle" alt="User Image"/>
                </div>
                <div class="pull-left info">
                    <p><?php echo $usuario['nombre']; ?> <?php echo $usuario['papell']; ?></p>

                    <a href="#"><i class="fas fa-circle text-success"></i> Online</a>
                </div>
            </div>

            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="treeview">
                    <a href="../sauxiliares/farmaciac/cat_maestro.php">
                        <i class="fas fa-book-medical"></i> <span>CATÁLOGO MAESTRO</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>

                </li>
                <li class="treeview">
                    <a href="../sauxiliares/farmaciac/entradas.php">
                        <i class="fas fa-arrow-down"></i> <span>REGISTRAR ENTRADAS</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>

                </li>
                <li class=" treeview">
                    <a href="../sauxiliares/farmaciac/surtir_subalma.php">
                        <i class="fas fa-shipping-fast"></i> <span>SURTIR SUBALMACENES</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>

                </li>
                <li class=" treeview">
                    <a href="../sauxiliares/farmaciac/existencias.php">
                        <i class="fas fa-boxes"></i> <span>EXISTENCIAS</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>

                </li>
                <li class=" treeview">
                    <a href="../sauxiliares/farmaciac/kardex.php">
                        <i class="fas fa-chart-line"></i> <span>KAR</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>

                </li>
                <li class="treeview">
                    <a href="../sauxiliares/farmaciac/devoluciones.php">
                        <i class="fas fa-calendar-times"></i> <span>CONTROL DE CADUCIDADES</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>

                </li>
                <li class="treeview">
                    <a href="../sauxiliares/farmaciac/devoluciones.php">
                        <i class="fas fa-undo"></i> <span>DEVOLUCIONES</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>

                </li>
                <li class="treeview">
                    <a href="../sauxiliares/farmaciac/proveedores.php">
                        <i class="fas fa-truck"></i> <span>PROVEEDORES</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>

                </li>
                
                
               
                <li class="treeview">
                    <a href="../sauxiliares/farmaciac/ordenes_compra.php">
                        <i class="fas fa-shopping-cart"></i> <span>COMPRAS</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>

                </li>
    <!--          
                <li class="treeview">
                    <a href="">
                        <i class="fa fa-folder"></i> <span>TOMA DE INVENTARIO</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>

                </li>
    -->

            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Right side column. Contains the navbar and content of the page -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <!--AQUI VA QUE PUESTO TIENE-->
        <nav aria-label="breadcrumb" style="background: #2b2d7f; padding: 15px; margin: 0;">
            <ol class="breadcrumb" style="background: none; margin: 0; padding: 0;">
                <li class="breadcrumb-item active" aria-current="page" style="color: white;">
                    <strong>
                        <h4 style="margin: 0; color: white;">
                            <i class="fas fa-pills" style="margin-right: 10px;"></i>
                            FARMACIA CENTRAL
                        </h4>
                    </strong>
                </li>
            </ol>
        </nav>

        <!-- Main content -->
        <section class="content">
            <style>
                body {
                    background: #f5f5f5;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                }

                .farmacia-container {
                    padding: 30px;
                    background: #ffffff;
                    min-height: 100vh;
                    margin: 0;
                }

                .farmacia-card {
                    border-radius: 20px;
                    padding: 40px 20px;
                    margin: 20px 0;
                    text-decoration: none;
                    transition: all 0.4s ease;
                    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
                    height: 220px;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    text-align: center;
                    position: relative;
                    overflow: hidden;
                    border: none;
                }

                .farmacia-card::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    border-radius: 20px;
                    padding: 2px;
                    background: rgba(255,255,255,0.5);
                    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                    -webkit-mask-composite: exclude;
                    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                    mask-composite: exclude;
                }

                .farmacia-card:hover {
                    transform: translateY(-8px) scale(1.02);
                    box-shadow: 0 15px 45px rgba(0, 0, 0, 0.15);
                    text-decoration: none;
                }

                .farmacia-card.catalogo {
                    background: #e3f2fd;
                    color: #1565c0;
                }

                .farmacia-card.entradas {
                    background: #f3e5f5;
                    color: #7b1fa2;
                }

                .farmacia-card.surtir {
                    background: #e8f5e8;
                    color: #388e3c;
                }

                .farmacia-card.existencias {
                    background: #fff3e0;
                    color: #f57c00;
                }

                .farmacia-card.kardex {
                    background: #fce4ec;
                    color: #c2185b;
                }

                .farmacia-card.caducidades {
                    background: #ffebee;
                    color: #d32f2f;
                }

                .farmacia-card.devoluciones {
                    background: #e0f2f1;
                    color: #00796b;
                }

                .farmacia-card.proveedores {
                    background: #e8eaf6;
                    color: #3f51b5;
                }

                .farmacia-card.compras {
                    background: #fff8e1;
                    color: #ff8f00;
                }

                .farmacia-card:hover.catalogo {
                    background: #bbdefb;
                }

                .farmacia-card:hover.entradas {
                    background: #e1bee7;
                }

                .farmacia-card:hover.surtir {
                    background: #c8e6c9;
                }

                .farmacia-card:hover.existencias {
                    background: #ffe0b2;
                }

                .farmacia-card:hover.kardex {
                    background: #f8bbd9;
                }

                .farmacia-card:hover.caducidades {
                    background: #ffcdd2;
                }

                .farmacia-card:hover.devoluciones {
                    background: #b2dfdb;
                }

                .farmacia-card:hover.proveedores {
                    background: #c5cae9;
                }

                .farmacia-card:hover.compras {
                    background: #ffecb3;
                }

                .farmacia-icon-circle {
                    width: 80px;
                    height: 80px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 15px auto;
                    background: rgba(255, 255, 255, 0.9);
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                    transition: all 0.3s ease;
                }

                .farmacia-card:hover .farmacia-icon-circle {
                    transform: scale(1.1);
                    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
                }

                .farmacia-card i {
                    font-size: 32px;
                    opacity: 0.9;
                }

                .farmacia-card h4 {
                    font-size: 16px;
                    font-weight: 600;
                    margin: 0;
                    letter-spacing: 0.5px;
                    line-height: 1.3;
                    text-transform: uppercase;
                }

                .farmacia-title {
                    text-align: center;
                    color: #2b2d7f;
                    font-weight: bold;
                    margin-bottom: 40px;
                    font-size: 32px;
                    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
                }

                @media (max-width: 768px) {
                    .farmacia-container {
                        padding: 15px;
                    }
                    .farmacia-card {
                        margin: 15px 0;
                        padding: 30px 15px;
                        height: 180px;
                    }
                    .farmacia-icon-circle {
                        width: 60px;
                        height: 60px;
                    }
                    .farmacia-card i {
                        font-size: 24px;
                    }
                    .farmacia-card h4 {
                        font-size: 14px;
                    }
                    .farmacia-title {
                        font-size: 24px;
                    }
                }
            </style>

            <div class="farmacia-container">
                <div class="row">
                    <!-- Catálogo Maestro -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <a href="../sauxiliares/farmaciac/cat_maestro.php" class="farmacia-card catalogo">
                            <div class="farmacia-icon-circle">
                                <i class="fas fa-book-medical"></i>
                            </div>
                            <h4>CATÁLOGO MAESTRO</h4>
                        </a>
                    </div>

                    <!-- Registrar Entradas -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <a href="../sauxiliares/farmaciac/entradas.php" class="farmacia-card entradas">
                            <div class="farmacia-icon-circle">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <h4>REGISTRAR ENTRADAS</h4>
                        </a>
                    </div>

                    <!-- Surtir Subalmacenes -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <a href="../sauxiliares/farmaciac/surtir_subalma.php" class="farmacia-card surtir">
                            <div class="farmacia-icon-circle">
                                <i class="fas fa-truck-loading"></i>
                            </div>
                            <h4>SURTIR SUBALMACENES</h4>
                        </a>
                    </div>

                    <!-- Existencias -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <a href="../sauxiliares/farmaciac/existencias.php" class="farmacia-card existencias">
                            <div class="farmacia-icon-circle">
                                <i class="fas fa-warehouse"></i>
                            </div>
                            <h4>EXISTENCIAS</h4>
                        </a>
                    </div>

                    <!-- Kardex -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <a href="../sauxiliares/farmaciac/kardex.php" class="farmacia-card kardex">
                            <div class="farmacia-icon-circle">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <h4>KARDEX</h4>
                        </a>
                    </div>

                    <!-- Control de Caducidades -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <a href="../sauxiliares/farmaciac/caducado.php" class="farmacia-card caducidades">
                            <div class="farmacia-icon-circle">
                                <i class="fas fa-calendar-times"></i>
                            </div>
                            <h4>CONTROL DE CADUCIDADES</h4>
                        </a>
                    </div>

                    <!-- Devoluciones -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <a href="../sauxiliares/farmaciac/devoluciones.php" class="farmacia-card devoluciones">
                            <div class="farmacia-icon-circle">
                                <i class="fas fa-undo-alt"></i>
                            </div>
                            <h4>DEVOLUCIONES</h4>
                        </a>
                    </div>

                    <!-- Proveedores -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <a href="../sauxiliares/farmaciac/proveedores.php" class="farmacia-card proveedores">
                            <div class="farmacia-icon-circle">
                                <i class="fas fa-truck"></i>
                            </div>
                            <h4>PROVEEDORES</h4>
                        </a>
                    </div>

                    <!-- Compras -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <a href="../sauxiliares/farmaciac/ordenes_compra.php" class="farmacia-card compras">
                            <div class="farmacia-icon-circle">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <h4>COMPRAS</h4>
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