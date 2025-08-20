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

        .dropdwn:hover .dropdwn-content {
            display: block;
        }

        /* Estilos modernos para el menú de servicios auxiliares */
        .content {
            padding: 20px;
        }

        .container-fluid {
            max-width: 1200px;
            margin: 0 auto;
        }

        .modern-card {
            transition: all 0.3s ease !important;
            border: none !important;
            overflow: hidden;
            border-radius: 20px !important;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
            background: white;
            height: 100%;
            margin-bottom: 30px;
        }

        .modern-card:hover {
            transform: translateY(-15px) !important;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2) !important;
        }

        .modern-card a {
            text-decoration: none !important;
            color: inherit;
        }

        .icon-circle {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .modern-card:hover .icon-circle {
            transform: scale(1.1) rotate(10deg);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2) !important;
        }

        .modern-card .fa {
            transition: all 0.3s ease;
            font-size: 60px;
        }

        .modern-card:hover .fa {
            transform: scale(1.1);
            animation: pulse 1.5s infinite;
        }

        .card-title {
            font-weight: 700;
            font-size: 1.4rem;
            margin: 0;
            text-align: center;
            padding: 20px;
        }

        /* Animación pulso */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Responsividad mejorada */
        @media (max-width: 768px) {
            .modern-card {
                margin-bottom: 25px;
            }
            
            .icon-circle {
                width: 100px !important;
                height: 100px !important;
            }
            
            .modern-card .fa {
                font-size: 40px !important;
            }
            
            .card-title {
                font-size: 1.1rem !important;
            }
        }

        @media (max-width: 576px) {
            .icon-circle {
                width: 80px !important;
                height: 80px !important;
            }
            
            .modern-card .fa {
                font-size: 30px !important;
            }
            
            .card-title {
                font-size: 1rem !important;
            }
        }

        /* Mejoras para el breadcrumb */
        .breadcrumb {
            background: linear-gradient(135deg, #2b2d7f 0%, #2b2d7f 100%);
            border: none;
            border-radius: 15px;
            padding: 20px 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .breadcrumb h4 {
            color: white !important;
            margin: 0;
            font-weight: 600;
            letter-spacing: 1px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        /* Animación de entrada */
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

        .modern-card {
            animation: fadeInUp 0.8s ease-out;
        }

        .modern-card:nth-child(1) { animation-delay: 0.1s; }
        .modern-card:nth-child(2) { animation-delay: 0.3s; }
        .modern-card:nth-child(3) { animation-delay: 0.5s; }
    </style>
</head>

<body class=" hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <!-- <img src="dist/img/logo.jpg" alt="logo">-->
            <?php
            if ($usuario['id_rol'] == 4) {
            ?>

                 <a href="menu_gerencia.php" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>SI</b>MA</span>
                    <!-- logo for regular state and mobile devices -->
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
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>SI</b>MA</span>
                    <!-- logo for regular state and mobile devices -->
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

                                <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="user-image" alt="User Image">
                                <span class="hidden-xs"> <?php echo $usuario['nombre']; ?> <?php echo $usuario['papell']; ?> <?php echo $usuario['sapell']; ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">

                                    <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle" alt="User Image">

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
                    <p>
                <?php echo $usuario['papell'];?>

                    </p>

                    <a href="#"><i class="fa fa-circle text-success"></i> ACTIVO</a>
                </div>
            </div>

            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">

                <li class="active treeview">
              
                    <a href="#">
                      <i class="fa fa-folder"></i> <span><strong>FARMACIAS</strong></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    
                 
                  <ul class="treeview-menu">
                    <li><a href="../template/menu_farmaciacentral.php"><i class="fa fa-circle"></i>FARMACIA CENTRAL</a></li>
                    <li><a href="../template/menu_farmaciahosp.php"><i class="fa fa-circle"></i>FARMACIA HOSPITALARIA</a></li>
                    <li><a href="../template/menu_farmaciaq.php"><i class="fa fa-circle"></i>FARMACIA QUIRÓFANO</a></li> 

                  </ul>
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
                            <h4>FARMACEÚTICO</h4>
                        </STRONG></li>
                </ol>
            </nav>

            <!-- Main content -->
            <section class="content">
                <section class="content container-fluid">
                    <div class="content box">
                        <!-- CONTENIDOO -->
                        <div class="row">
                            <!-- FARMACIA CENTRAL -->
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="modern-card text-center" style="background: linear-gradient(135deg, #E8F5E8 0%, #F1F8E9 100%);">
                                    <div style="padding: 40px 20px;">
                                        <a href="../template/menu_farmaciacentral.php" title="Farmacia Central" style="text-decoration: none;">
                                            <div class="icon-circle" style="background: linear-gradient(135deg, #C8E6C9 0%, #A5D6A7 100%);">
                                                <i class="fa fa-hospital-o" style="color: #2E7D32;"></i>
                                            </div>
                                            <h4 class="card-title" style="color: #2E7D32;">FARMACIA CENTRAL</h4>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- FARMACIA HOSPITALARIA -->
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="modern-card text-center" style="background: linear-gradient(135deg, #E8F4FD 0%, #E1F5FE 100%);">
                                    <div style="padding: 40px 20px;">
                                        <a href="../template/menu_farmaciahosp.php" title="Farmacia Hospitalaria" style="text-decoration: none;">
                                            <div class="icon-circle" style="background: linear-gradient(135deg, #B3E5FC 0%, #81D4FA 100%);">
                                                <i class="fa fa-medkit" style="color: #0277BD;"></i>
                                            </div>
                                            <h4 class="card-title" style="color: #0277BD;">FARMACIA HOSPITALARIA</h4>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- FARMACIA QUIRÓFANO -->
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="modern-card text-center" style="background: linear-gradient(135deg, #FFF3E0 0%, #FFECB3 100%);">
                                    <div style="padding: 40px 20px;">
                                        <a href="../template/menu_farmaciaq.php" title="Farmacia Quirófano" style="text-decoration: none;">
                                            <div class="icon-circle" style="background: linear-gradient(135deg, #FFE0B2 0%, #FFCC02 100%);">
                                                <i class="fa fa-heartbeat" style="color: #F57C00;"></i>
                                            </div>
                                            <h4 class="card-title" style="color: #F57C00;">FARMACIA QUIRÓFANO</h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->



        <div class="modal fade" id="exampleModalFar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-4 col-xs-6">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <center>
                                            <a title="Surtir Medicamentos" href="../sauxiliares/Farmacia/order.php"><img class="card-img-top" src="../img/surtir.PNG" alt="Surtir Medicamentos" height="150" width="200" /></a>
                                        </center>
                                        <center>
                                            <h3>Surtir Medicamentos</h3>
                                        </center>

                                    </div>
                                    <div class="col-lg-1"></div>
                                </div>

                            </div>
                            <div class="col-lg-4 col-xs-6">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <center>
                                            <a title="Lista Médicamentos" href="../sauxiliares/Farmacia/lista_productos.php"><img class="card-img-top" src="../img/lista.png" alt="lista de medicamnetos" height="150" width="200" /></a>
                                        </center>
                                        <center>
                                            <h3>Lista de Médicamentos</h3>
                                        </center>

                                    </div>
                                    <div class="col-lg-1"></div>
                                </div>

                            </div>
                            <div class="col-lg-4 col-xs-6">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <center>
                                            <a title="Pérfil del Médicamento" href="../sauxiliares/Farmacia/perfilmedicamento.php"><img class="card-img-top" src="../img/liberar.jpg" alt="perfil del medicamento" height="150" width="160" /></a>
                                        </center>
                                        <center>
                                            <h3>Agregar Inventario </h3>
                                        </center>

                                    </div>
                                    <div class="col-lg-1"></div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-xs-6">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <center>
                                                <a title="Existencias" href="../sauxiliares/Farmacia/inventario.php"><img class="card-img-top" src="../img/inventario.PNG" alt="Existencias" height="150" width="200" /></a>
                                            </center>
                                            <center>
                                                <h3>Existencias</h3>
                                            </center>

                                        </div>
                                        <div class="col-lg-1"></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-xs-6">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <center>
                                                <a title="Medicamentos Caducados" href="../sauxiliares/Farmacia/caducado.php"><img class="card-img-top" src="../img/caducado.webp" alt="medicamentos caducados" height="150" width="200" /></a>
                                            </center>
                                            <center>
                                                <h3>Médicamentos Caducados</h3>
                                            </center>

                                        </div>
                                        <div class="col-lg-1"></div>
                                    </div>

                                </div>

                                <div class="col-lg-4 col-xs-6">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <center>
                                                <a title="Devoluciones" href="../sauxiliares/Farmacia/devoluciones.php"><img class="card-img-top" src="../img/dev.PNG" alt="devolucion" height="150" width="160" /></a>
                                            </center>
                                            <center>
                                                <h3>Devoluciones</h3>
                                            </center>

                                        </div>
                                        <div class="col-lg-1"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

                    </div>
                </div>
            </div>
        </div>




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