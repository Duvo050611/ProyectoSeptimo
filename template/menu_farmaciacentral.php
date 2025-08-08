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
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><STRONG>
                        <h4>FARMACIA CENTRAL</h4>
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
                                <div class="row">
                                    <div class="col-lg-12">
                                        <center>
                                            <a title="Catálogo maestro" href="../sauxiliares/farmaciac/cat_maestro.php"><img
                                                        class="card-img-top" src="../img/cat_maestro.PNG"
                                                        alt="Catálogo maestro" height="150" width="200"/></a>
                                        </center>
                                        <center>
                                            <h3>CATÁLOGO MAESTRO</h3>
                                        </center>

                                    </div>

                                </div>
                        </div>
                        <div class="col-lg-4 col-xs-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <center>
                                            <a title="Entradas" href="../sauxiliares/farmaciac/entradas.php"><img
                                                        class="card-img-top" src="../img/entradas_fc.png"
                                                        alt="Entradas" height="150" width="200"/></a>
                                        </center>
                                        <center>
                                            <h3>REGISTRAR ENTRADAS</h3>
                                        </center>

                                    </div>

                                </div>
                        </div>
                        <div class="col-lg-4 col-xs-6">
                            <div class="row">
                                <div class="col-lg-12">
                                    <center>
                                        <a title="Surtir Medicamentos" href="../sauxiliares/farmaciac/surtir_subalma.php"><img
                                                    class="card-img-top" src="../img/resurte_subalma.png"
                                                    alt="Surtir Medicamentos" height="150" width="200"/></a>
                                    </center>
                                    <center>
                                        <h3>SURTIR SUBALMACENES</h3>
                                    </center>

                                </div>

                            </div>

                        </div>
                        <div class="row">
                        <div class="col-lg-4 col-xs-6">
                            <div class="row">
                                <div class="col-lg-12">
                                    <center>
                                        <a title="Existencias"
                                           href="../sauxiliares/farmaciac/existencias.php"><img
                                                    class="card-img-top" src="../img/inventariosfc.png"
                                                    alt="Existencias" height="150" width="160"/></a>
                                    </center>
                                    <center>
                                        <h3>EXISTENCIAS</h3>
                                    </center>

                                </div>

                            </div>

                        </div>
                        
                            <div class="col-lg-4 col-xs-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <center>
                                            <a title="Kardex" href="../sauxiliares/farmaciac/kardex.php"><img
                                                        class="card-img-top" src="../img/kardex.png"
                                                        alt="Kardex" height="150" width="200"/></a>
                                        </center>
                                        <center>
                                            <h3>KARDEX</h3>
                                        </center>

                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-4 col-xs-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <center>
                                            <a title="Medicamentos Caducados"
                                               href="../sauxiliares/farmaciac/caducado.php"><img class="card-img-top"
                                               src="../img/caducado.jpg"
                                               alt="medicamentos caducados"
                                               height="150"
                                               width="200"/></a>
                                        </center>
                                        <center>
                                            <h3>CONTROL DE CADUCIDADES</h3>
                                        </center>

                                    </div>

                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-xs-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <center>
                                            <a title="Devoluciones" href="../sauxiliares/farmaciac/devoluciones.php"><img
                                                        class="card-img-top" src="../img/dev.jpg" alt="devolucion"
                                                        height="150" width="160"/></a>
                                        </center>
                                        <center>
                                            <h3>DEVOLUCIONES</h3>
                                        </center>

                                    </div>

                                </div>
                            </div>

                            
                            <div class="col-lg-4 col-xs-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <center>
                                            <a title="Existencias" href="../sauxiliares/farmaciac/proveedores.php"><img
                                                        class="card-img-top" src="../img/proveedor.PNG"
                                                        alt="Existencias" height="150" width="200"/></a>
                                        </center>
                                        <center>
                                            <h3>PROVEEDORES</h3>
                                        </center>

                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-4 col-xs-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <center>
                                            <a title="Existencias" href="../sauxiliares/farmaciac/ordenes_compra.php"><img
                                                        class="card-img-top" src="../img/compras.PNG"
                                                        alt="Existencias" height="150" width="200"/></a>
                                        </center>
                                        <center>
                                            <h3>COMPRAS</h3>
                                        </center>

                                    </div>

                                </div>
                            </div>
                    <!--
                            <div class="row">
                            <div class="col-lg-4 col-xs-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <center>
                                            <a title="Devoluciones" href=""><img
                                                        class="card-img-top" src="../img/inventario.jpg" alt="devolucion"
                                                        height="150" width="160"/></a>
                                        </center>
                                        <center>
                                            <h3>TOMA DE INVENTARIO</h3>
                                        </center>

                                    </div>

                                </div>
                            </div>

                            </div>
                    -->
                            </div>


            
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