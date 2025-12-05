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

if (!($usuario['id_rol'] == 1 || $usuario['id_rol'] == 5 || $usuario['id_rol'] == 12 || $usuario['id_rol'] == 9)) {
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <?php
    $resultado = $conexion->query("select paciente.*, dat_ingreso.id_atencion, triage.id_triage
from paciente
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp
inner join triage on dat_ingreso.id_atencion=triage.id_atencion where id_triage=id_triage
") or die($conexion->error);

    $usuario = $_SESSION['login'];
    ?>

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

        /* Cards principales - Diseño premium */
        .content.box {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }

        .card {
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
            border: 2px solid #40E0FF !important;
            border-radius: 20px !important;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
            position: relative;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5),
            0 0 30px rgba(64, 224, 255, 0.2) !important;
            margin-bottom: 30px !important;
            height: 100%;
        }

        /* Efecto de brillo en las tarjetas */
        .card::before {
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

        .card:hover::before {
            left: 100%;
        }

        .card:hover {
            transform: translateY(-15px) scale(1.02) !important;
            border-color: #00D9FF !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7),
            0 0 50px rgba(64, 224, 255, 0.5),
            inset 0 0 20px rgba(64, 224, 255, 0.1) !important;
        }

        .card a {
            text-decoration: none !important;
            display: block;
        }

        .card-body {
            padding: 40px 20px !important;
            position: relative;
            z-index: 1;
        }

        /* Círculo de icono mejorado */
        .icon-circle {
            background: linear-gradient(135deg, rgba(64, 224, 255, 0.2) 0%, rgba(0, 217, 255, 0.3) 100%) !important;
            width: 140px !important;
            height: 140px !important;
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

        .card:hover .icon-circle {
            transform: scale(1.1) rotate(360deg) !important;
            box-shadow: 0 15px 40px rgba(64, 224, 255, 0.5),
            inset 0 0 30px rgba(64, 224, 255, 0.2) !important;
            background: linear-gradient(135deg, rgba(64, 224, 255, 0.3) 0%, rgba(0, 217, 255, 0.4) 100%) !important;
        }

        .card .fa {
            font-size: 56px !important;
            color: #40E0FF !important;
            text-shadow: 0 0 20px rgba(64, 224, 255, 0.8);
            transition: all 0.4s ease !important;
        }

        .card:hover .fa {
            transform: scale(1.2) !important;
            text-shadow: 0 0 30px rgba(64, 224, 255, 1),
            0 0 40px rgba(64, 224, 255, 0.8);
        }

        /* Títulos de las tarjetas */
        .card h4, .card h5 {
            color: #ffffff !important;
            font-weight: 700 !important;
            margin: 0 !important;
            font-size: 1.3rem !important;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5),
            0 0 20px rgba(64, 224, 255, 0.3);
            transition: all 0.3s ease;
        }

        .card:hover h4,
        .card:hover h5 {
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

        .card {
            animation: fadeInUp 0.8s ease-out backwards;
        }

        .card:nth-child(1) { animation-delay: 0.1s; }
        .card:nth-child(2) { animation-delay: 0.2s; }
        .card:nth-child(3) { animation-delay: 0.3s; }
        .card:nth-child(4) { animation-delay: 0.4s; }
        .card:nth-child(5) { animation-delay: 0.5s; }
        .card:nth-child(6) { animation-delay: 0.6s; }

        /* Footer */
        .main-footer {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            border-top: 2px solid #40E0FF !important;
            color: #ffffff !important;
            box-shadow: 0 -4px 20px rgba(64, 224, 255, 0.2);
        }

        /* Modal mejorado */
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

        .modal-title {
            color: #ffffff !important;
            font-weight: 600 !important;
            text-shadow: 0 0 15px rgba(64, 224, 255, 0.5);
        }

        .modal-body {
            background: transparent !important;
            padding: 30px !important;
        }

        .modal-footer {
            border-top: 2px solid #40E0FF !important;
            background: rgba(15, 52, 96, 0.5) !important;
        }

        /* Botones */
        .btn {
            border-radius: 25px !important;
            padding: 10px 30px !important;
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

        .close {
            color: #ffffff !important;
            opacity: 1;
            text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
        }

        .close:hover {
            color: #40E0FF !important;
        }

        /* Iconos en modal más pequeños */
        .modal-body .icon-circle {
            width: 100px !important;
            height: 100px !important;
        }

        .modal-body .fa {
            font-size: 40px !important;
        }

        /* Responsive */
        @media screen and (max-width: 768px) {
            .card h4, .card h5 {
                font-size: 1rem !important;
            }

            .icon-circle {
                width: 100px !important;
                height: 100px !important;
            }

            .card .fa {
                font-size: 40px !important;
            }

            .breadcrumb {
                padding: 15px 20px !important;
            }

            .breadcrumb h4 {
                font-size: 1.1rem;
                letter-spacing: 1px;
            }

            .modal-body .icon-circle {
                width: 80px !important;
                height: 80px !important;
            }

            .modal-body .fa {
                font-size: 32px !important;
            }
        }

        @media screen and (max-width: 576px) {
            .icon-circle {
                width: 80px !important;
                height: 80px !important;
            }

            .card .fa {
                font-size: 32px !important;
            }

            .card h4, .card h5 {
                font-size: 0.9rem !important;
            }
        }

        /* Efectos de luz en hover */
        @keyframes glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(64, 224, 255, 0.3);
            }
            50% {
                box-shadow: 0 0 40px rgba(64, 224, 255, 0.6);
            }
        }

        .card:hover {
            animation: glow 2s ease-in-out infinite;
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

        /* Estilos para elementos inline */
        .card div[style*="background"] {
            transition: all 0.3s ease !important;
        }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <?php
        if ($usuario['id_rol'] == 1) {
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
        <?php }elseif($usuario['id_rol'] == 12) { ?>
            <a href="menu_residente.php" class="logo">
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
        <?php }elseif($usuario['id_rol'] == 9) { ?>
            <a href="menu_imagenologia.php" class="logo">
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
        } else
            session_destroy();
        echo "<script>window.Location='../index.php';</script>";
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
                            <span class="hidden-xs">  <?php echo $usuario['papell']; ?></span>
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
                                    <a href="../gestion_administrativa/editar_perfil/editar_perfil.php?id_usua=<?php echo $usuario['id_usua'];?>" class="btn btn-default btn-flat">MIS DATOS</a>
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
                    <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle" alt="User Image" />
                </div>
                <div class="pull-left info">
                    <p> <?php echo $usuario['papell']; ?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> ACTIVO</a>
                </div>
            </div>

            <ul class="sidebar-menu">
                <li class="active treeview">
                    <a href="#">
                        <i class="fa fa-user-friends"></i> <span>ADMINISTRATIVO</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="../gestion_administrativa/gestion_pacientes/registro_pac.php"><i class="fa fa-user-plus"></i>
                                GESTIÓN DE PACIENTES</a></li>
                        <li><a href="../gestion_administrativa/censo/tabla_censo.php"><i class="fa fa-list-alt" aria-hidden="true"></i>
                                CENSO</a></li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-donate"></i> <span>GESTIÓN DE CUENTAS</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="../gestion_administrativa/cuenta_paciente/vista_df.php"><i class="fa fa-donate"></i> CUENTA DE PACIENTES</a></li>
                        <li><a href="../gestion_administrativa/presupuesto/presupuesto.php"><i class="fa fa-file-invoice-dollar"></i> PRESUPUESTOS</a></li>
                        <li><a href="../gestion_administrativa/cuenta_paciente/corte_caja.php"><i class="fa fa-file-invoice-dollar"></i> CORTE DE CAJA</a></li>
                    </ul>
                </li>
            </ul>
        </section>
    </aside>

    <div class="content-wrapper">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <h4><i class="fa fa-briefcase"></i> ADMINISTRATIVO</h4>
                </li>
            </ol>
        </nav>

        <section class="content">
            <div class="content box">
                <div class="container">
                    <div class="row">
                        <!-- GESTIÓN DE PACIENTES -->
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card text-center h-100">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    <a href="../gestion_administrativa/gestion_pacientes/registro_pac.php" title="Gestión de Pacientes">
                                        <div class="icon-circle">
                                            <i class="fa fa-users"></i>
                                        </div>
                                        <h4>GESTIÓN DE PACIENTES</h4>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- GESTIÓN DE CUENTAS -->
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card text-center h-100">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    <a href="#" data-toggle="modal" data-target="#exampleModal" title="Cuentas">
                                        <div class="icon-circle">
                                            <i class="fa fa-calculator"></i>
                                        </div>
                                        <h4>GESTIÓN DE CUENTAS</h4>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- CENSO -->
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card text-center h-100">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    <a href="../gestion_administrativa/censo/tabla_censo.php" title="Censo">
                                        <div class="icon-circle">
                                            <i class="fa fa-bed"></i>
                                        </div>
                                        <h4>CENSO</h4>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal Gestión de Cuentas -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa fa-calculator"></i> GESTIÓN DE CUENTAS
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- CUENTA DE PACIENTES -->
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card text-center h-100">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    <a href="../gestion_administrativa/cuenta_paciente/vista_df.php" title="Cuenta del Paciente">
                                        <div class="icon-circle">
                                            <i class="fa fa-file-text"></i>
                                        </div>
                                        <h5>CUENTA DE PACIENTES</h5>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- PRESUPUESTOS -->
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card text-center h-100">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    <a href="../gestion_administrativa/presupuesto/presupuesto.php" title="Presupuestos">
                                        <div class="icon-circle">
                                            <i class="fa fa-money"></i>
                                        </div>
                                        <h5>PRESUPUESTOS</h5>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- CORTE DE CAJA -->
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card text-center h-100">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    <a href="../gestion_administrativa/cuenta_paciente/corte_caja.php" title="Corte de caja">
                                        <div class="icon-circle">
                                            <i class="fa fa-cash-register"></i>
                                        </div>
                                        <h5>CORTE DE CAJA</h5>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">
                        <i class="fa fa-times"></i> CERRAR
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Servicios Auxiliares -->
    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa fa-hospital-o"></i> SERVICIOS AUXILIARES
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- FARMACIA -->
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card text-center h-100">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    <a href="../template/menu_farmacia.php" title="Farmacia">
                                        <div class="icon-circle">
                                            <i class="fa fa-medkit"></i>
                                        </div>
                                        <h5>FARMACIA</h5>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- CEYE -->
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card text-center h-100">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    <a href="../template/menu_ceye.php" title="CEYE">
                                        <div class="icon-circle">
                                            <i class="fa fa-shield"></i>
                                        </div>
                                        <h5>CEYE</h5>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- ALMACÉN CENTRAL -->
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card text-center h-100">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    <a href="../template/menu_almacencentral.php" title="Almacén Central">
                                        <div class="icon-circle">
                                            <i class="fa fa-cubes"></i>
                                        </div>
                                        <h5>ALMACÉN CENTRAL</h5>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- IMAGENOLOGÍA -->
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="card text-center h-100">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    <a href="../template/menu_imagenologia.php" title="Imagenología">
                                        <div class="icon-circle">
                                            <i class="fa fa-search"></i>
                                        </div>
                                        <h5>IMAGENOLOGÍA</h5>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- LABORATORIO -->
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="card text-center h-100">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    <a href="../template/menu_laboratorio.php" title="Laboratorio">
                                        <div class="icon-circle">
                                            <i class="fa fa-flask"></i>
                                        </div>
                                        <h5>LABORATORIO</h5>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">
                        <i class="fa fa-times"></i> CERRAR
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cuentas y Servicios Pagados -->
    <div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa fa-money"></i> CUENTAS Y SERVICIOS PAGADOS
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- CUENTAS PAGADAS -->
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="card text-center h-100">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    <a href="../gestion_administrativa/cuenta_paciente/valida_cta.php" title="Cuentas Pagadas">
                                        <div class="icon-circle">
                                            <i class="fa fa-check-circle"></i>
                                        </div>
                                        <h4>CUENTAS PAGADAS</h4>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- SERVICIOS PAGADOS -->
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="card text-center h-100">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    <a href="../gestion_administrativa/cuenta_paciente/valida_cta_serv.php" title="Servicios Pagados">
                                        <div class="icon-circle">
                                            <i class="fa fa-credit-card"></i>
                                        </div>
                                        <h4>SERVICIOS PAGADOS</h4>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">
                        <i class="fa fa-times"></i> CERRAR
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Servicios de Salida -->
    <div class="modal fade" id="exampleModal4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa fa-truck"></i> SERVICIOS DE SALIDA
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- FARMACIA -->
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="card text-center h-100">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    <a href="../sauxiliares/Farmacia/salidas.php" title="Farmacia">
                                        <div class="icon-circle">
                                            <i class="fa fa-shopping-cart"></i>
                                        </div>
                                        <h4>FARMACIA</h4>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- QUIRÓFANO -->
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="card text-center h-100">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    <a href="../sauxiliares/Ceye/salidas.php" title="Quirófano">
                                        <div class="icon-circle">
                                            <i class="fa fa-heartbeat"></i>
                                        </div>
                                        <h4>QUIRÓFANO</h4>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">
                        <i class="fa fa-times"></i> CERRAR
                    </button>
                </div>
            </div>
        </div>
    </div>

    <footer class="main-footer">
        <?php
        include("footer.php");
        ?>
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
