<?php
require_once "../../conexionbd.php";

// Solo modificar parámetros y iniciar sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    $lifetime = 100000;
    session_set_cookie_params($lifetime);
    session_start();
}

// Verificar si está logueado
if (!isset($_SESSION['login'])) {
    session_unset();
    session_destroy();
    header('Location: ../index.php');
    exit;
}

$usuario = $_SESSION['login'];

// Verificar rol
if (!in_array($usuario['id_rol'], [3, 5, 12, 1])) {
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
  <title>INEO Metepec</title>
  <link rel="icon" type="image/png" href="../../imagenes/SIF.PNG">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="../../template/dist/js/pages/dashboard2.js" type="text/javascript"></script>
    <script src="https://kit.fontawesome.com/e547be4475.js" crossorigin="anonymous"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../../template/dist/js/demo.js" type="text/javascript"></script>
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
    <style>
        body, .wrapper, .content-wrapper {
            background: #0a0f1f !important; /* Fondo general */
            color: #ffe2e2 !important;
        }
        /* NAVBAR */
        .main-header .navbar {
            background: #0d1b3a !important;
            border-bottom: 1px solid #1a3d7c;
        }
        .navbar .sidebar-toggle {
            color: #4fc3ff !important;
        }
        .navbar a:hover {
            background: #11265c !important;
        }
        /* LOGO - HEADER */
        .logo {
            background: #0d1b3a !important;
            border-bottom: 1px solid #1a3d7c;
        }

        /* SIDEBAR */
        .main-sidebar {
            background: #0c1224 !important;
            border-right: 1px solid #1a3d7c;
        }

        .sidebar a {
            color: #a8c6ff !important;
        }

        .sidebar-menu > li > a:hover {
            background: #11265c !important;
            color: #4fc3ff !important;
            box-shadow: 0 0 8px #4fc3ff;
        }

        /* SUBMENÚS — CORRECCIÓN DEL BLANCO */
        .treeview-menu {
            background: #0f1a33 !important;
            border-left: 2px solid #020202;
        }

        .treeview-menu li a {
            color: #090909 !important;
        }

        .treeview-menu li a:hover {
            background: #12224a !important;
            color: #4fc3ff !important;
            box-shadow: inset 0 0 10px #4fc3ff;
        }

        /* ÍCONOS */
        .sidebar-menu i {
            color: #4fc3ff !important;
        }

        /* USER PANEL */
        .user-panel {
            background: #0c1224 !important;
            border-bottom: 1px solid #1a3d7c;
        }

        .user-panel p {
            color: #4fc3ff !important;
        }

        /* ⭐⭐⭐ PUESTO DEL USUARIO CON NEÓN ⭐⭐⭐ */
        .user-panel a {
            color: #00eaff !important;
            font-weight: bold;
            text-shadow: 0 0 6px #00eaff, 0 0 12px #00eaff, 0 0 20px #00eaff;
        }

        /* EFECTO GLOW SUAVE */
        .glow-text {
            color: #4fc3ff !important;
            text-shadow:
                    0 0 6px #4fc3ff,
                    0 0 10px #4fc3ff,
                    0 0 15px #4fc3ff;
        }

        /* ESTILO PARA EL NOMBRE EN EL NAVBAR */
        .navbar .user-menu .hidden-xs {
            color: #4fc3ff !important;
            text-shadow: 0 0 5px #4fc3ff;
        }

        /* DROPDOWN USER MENU */
        .dropdown-menu {
            background: #0f1a33 !important;
            border: 1px solid #1a3d7c;
        }

        .user-header {
            background: #0d1b3a !important;
            color: #4fc3ff !important;
        }

        /* BOTONES DEL MENÚ DEL USUARIO */
        .user-footer .btn {
            background: #11265c !important;
            color: white !important;
            border: 1px solid #4fc3ff;
        }

        .user-footer .btn:hover {
            background: #0a1633 !important;
            box-shadow: 0 0 10px #4fc3ff;
        }

        /* SCROLLBARS FUTURISTAS */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #0a0f1f;
        }

        ::-webkit-scrollbar-thumb {
            background: #1a3d7c;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #4fc3ff;
        }
        /* --- NEÓN PARA ENFERMERÍA --- */
        .sidebar-menu li a span.neon-enfermeria {
            color: #00eaff !important;
            text-shadow: 0 0 6px #00eaff, 0 0 12px #00eaff, 0 0 18px #00c7ff;
            font-weight: bold;
        }

        /* --- Sidebar oscuro al abrir submenús (sin blanco feo) --- */
        .skin-blue .sidebar-menu > li > .treeview-menu {
            background: #0c1b2a !important;
            border-left: 2px solid #00eaff !important;
        }

        .skin-blue .sidebar-menu .treeview-menu > li > a {
            color: #0b0a0a !important;
        }

        .skin-blue .sidebar-menu .treeview-menu > li > a:hover {
            background-color: #112b40 !important;
            color: #00eaff !important;
            text-shadow: 0 0 6px #00eaff;
        }
        .neon-enfermeria {
            color: #8a9fd3 !important;
            font-weight: 900 !important;
            text-shadow:
                    0 0 5px #00eaff,
                    0 0 10px #00eaff,
                    0 0 20px #00c7ff,
                    0 0 30px #00c7ff;
        }

        /* Evita que Bootstrap le meta color blanco */
        .breadcrumb-item.active {
            color: #00eaff !important;
        }
        .neon-title {
            color: #00eaff !important;
            font-weight: 900 !important;
            margin: 0;
            padding: 0;
            text-shadow:
                    0 0 5px #00eaff,
                    0 0 10px #00eaff,
                    0 0 20px #00c7ff,
                    0 0 30px #00c7ff,
                    0 0 40px #00c7ff;
        }

        /* Que el breadcrumb NO meta fondo blanco */
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

    </style>
</head>

<body class=" hold-transition skin-blue sidebar-mini">
   <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
             <!-- <img src="dist/img/logo.jpg" alt="logo">  -->

            <?php
            if ($usuario['id_rol'] == 3) {
            ?>

                <a href="../../template/menu_enfermera.php" class="logo">
                   
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
            } else if ($usuario['id_rol'] == 5) {

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
            } else if ($usuario['id_rol'] == 12) {

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
            } else if ($usuario['id_rol'] == 1) {

            ?>
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
            <?php
            } else
                //session_unset();
                session_destroy();
            echo "<script>window.Location='../index.php';</script>";
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
                                <img src="../../imagenes/<?php echo $usuario['img_perfil']; ?>" class="user-image" alt="User Image" />
                                <span class="hidden-xs"> <?php echo $usuario['papell']; ?> </span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="../../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle" alt="User Image" />
                                    <p>
                                     <?php echo $usuario['papell']; ?>
                                    </p>
                                </li>

                                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="../../enfermera/editar_perfil/editar_perfil.php?id_usua=<?php echo $usuario['id_usua'];?>" class="btn btn-default btn-flat">MIS DATOS</a>
                  </div>
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
                        <img src="../../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle" alt="User Image" />
                    </div>
                    <div class="pull-left info">
                        <p><?php echo $usuario['papell'];?></p>

                        <a href="#"><i class="fa fa-circle text-success"></i> ACTIVO</a>
                    </div>
                </div>

                <!-- sidebar menu: : style can be found in sidebar.less -->
                <?php if ( $usuario['id_rol'] == 3 || $usuario['id_rol'] == 5){ ?>
                <ul class="sidebar-menu">
                     <li class="treeview">
                        <a href="../pdf/vista_pdf.php">
                            <i class="fa fa-print" aria-hidden="true"></i> <span><font size ="2"> IMPRIMIR DOCUMENTOS </font></span>
                        </a>
                    </li>
               
                     <li class=" treeview">
                       <a href="#">
                           <i class="fa fa-bed"></i><font size ="2"><span> GESTIÓN DE CAMAS</span></font><i class="fa fa-angle-left pull-right"></i>
                       </a>
                       <ul class="treeview-menu">
                            <li class="treeview">
                                <a href="../censo/vista_habitacion.php">
                                <i class="fa fa-bed" aria-hidden="true"></i><font size ="2"><span> ASIGNAR HABITACIÓN</font></span>
                                </a>
                            </li>
                           <li class="treeview">
                               <a href="../censo/cambio_habitacion.php">
                                <i class="fa fa-medkit" aria-hidden="true"></i><font size ="2"><span> CAMBIO DE HABITACIÓN</font></span>
                                </a>
                            </li>
                            <li class="treeview">
                       <a href="../censo/pac_quirofano.php">
                          <i class="fa fa-medkit" aria-hidden="true"></i><span>PACIENTE A QUIROFANO</span>
                       </a>
                   </li>
                      </ul>
                   </li> 
                    <li class="treeview">
                        <a href="../ordenes_medico/vista_ordenes.php">
                            <i class="fa fa-stethoscope"></i> <span><font size ="2"> INDICACIONES DEL MÉDICO </font></span>

                        </a>

                    </li>
                    <li class=" treeview">
            <a href="#">
              <i class="fa fa-folder"></i><font size ="2"><span>REGISTRO CLÍNICO</span></font><i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                            <li><a href="../registro_procedimientos/reg_pro.php"><i class="fa-solid fa-notes-medical"></i> <span>REGISTRO DE  <br>PROCEDIMIENTOS</span></a></li>
              <!-- <li><a href="../registro_quirurgico/enf_cirugia_segura.php"><i class="fa-solid fa-file-waveform"></i> <span>HOJA PROGRAMACIÓN<br> QUIRÚRGICA</span></a></li> -->
              <li><a href="../registro_quirurgico/vista_enf_quirurgico.php"><i class="fa fa-folder"></i> <span>QUIRÓFANO</span></a></li>
              
              <li><a href="../registro_clinico_neonatal/nota_bebes.php"><i class="fa fa-folder"></i> <span>PEDIÁTRICO/NEONATAL </span></a></li>
              <li><a href="../transfucion_de_sangre/nota_trasfusion_new.php"><i class="fa fa-folder"></i> <span>TRANSFUSIONES<br>SANGUÍNEAS</span></a></li>
              
            </ul>
        </li>                         
                   <li class="treeview">
                <a href="../signos_vitales/signos.php">
                  <i class="fa fa-heartbeat" aria-hidden="true"></i> <span><font size ="2">SIGNOS VITALES</font></span>
                </a>
            </li>    
             <li class="treeview">
                <a href="../medicamentos/medicamentos.php">
                 <i class="fa fa-medkit" aria-hidden="true"></i><span><font size ="2">REGISTRO DE MEDICAMENTOS</font></span>
                </a>
            </li>
            <li class="treeview">
                <a href="../soluciones/soluciones.php">
                 <i class="fa fa-medkit" aria-hidden="true"></i><span><font size ="2">REGISTRO DE SOLUCIONES/<br>AMINAS</font></span>
                </a>
            </li>   
            <!-- <li class="treeview">
                        <a href="../medicamentos/solmed_far.php">
                            <i class="fa fa-medkit" aria-hidden="true"></i> <span><font size ="2">SOLICITAR MEDICAMENTOS<br>A FARMACIA</font></span>

                        </a>

                    </li>                  
           -->
        <!--    <li class="treeview">
                <a href="../../gestion_medica./hospitalizacion/hoja_alta_medica.php">
                   <i class="fa fa-street-view" aria-hidden="true"></i> <font size ="2"><span>AVISO DE ALTA</span></font>
                </a>
            </li>
        <li class=" treeview">
            <a href="#">
              <i class="fa fa-folder"></i><font size ="2"><span>VALES DE MEDICAMENTOS</span></font><i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li class="treeview">
                       <a href="../vales_farmacia/salidas.php">
                          <i class="fa fa-medkit" aria-hidden="true"></i><span><font size ="2">VALES DE MEDICAMENTOS<br> FARMACIA</font></span>
                       </a>
                   </li>
                    <li class="treeview">
                       <a href="../vales_ceye/salidas.php">
                          <i class="fa fa-medkit" aria-hidden="true"></i><span><font size ="2">VALES DE MEDICAMENTOS<br> CEYE (QUIRÓFANO)</font></span>
                       </a>
                   </li>
            </ul>
        </li>  -->
                    
                  
                </ul>
            <?php }elseif($usuario['id_rol'] == 12){?>
                  <ul class="sidebar-menu">
                    <li class="treeview">
                        <a href="../../template/menu_enfermera.php">
                            <i class="fa fa-bed" aria-hidden="true"></i><span><font size ="2"> SELECCIONAR PACIENTE </font></span>

                        </a>

                    </li> 
                    <li class="treeview">
                        <a href="../ordenes_medico/vista_ordenes.php">
                            <i class="fa fa-stethoscope"></i> <span><font size ="2"> INDICACIONES DEL MÉDICO </font></span>

                        </a>

                    </li>                  
                   
                </ul>
            <?php }elseif($usuario['id_rol'] == 1){?>
                  <ul class="sidebar-menu">
                    <li class="treeview">
                        <a href="../../template/menu_enfermera.php">
                            <i class="fa fa-bed" aria-hidden="true"></i><span><font size ="2"> SELECCIONAR PACIENTE </font></span>

                        </a>
                    </li> 
                    <li class="treeview">
                        <a href="../ordenes_medico/nota_ordmed.php">
                            <i class="fa fa-stethoscope"></i> <span><font size ="2"> INDICACIONES DEL MÉDICO </font></span>
                        </a>
                    </li>
                    
                   
                </ul>
            <?php } ?>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Right side column. Contains the navbar and content of the page -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->

            <!--AQUI VA QUE PUESTO TIENE-->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        <h4 class="neon-title">ENFERMERÍA</h4>
                    </li>
                </ol>
            </nav>
            <script>
                function SoloLetras(e) {
                    key = e.keyCode || e.which;
                    tecla = String.fromCharCode(key).toString();
                    letras = "ABCDEFGHIJKLMNOPQRSTUVWXYZÁÉÍÓÚabcdefghijklmnopqrstuvwxyzáéíóú ";

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
                        alert("Ingresar solo valores numericos");
                        return false;
                    }
                }
            </script>