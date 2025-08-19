<?php
    // No incluir conexión ni iniciar sesión aquí - debe hacerse en el archivo que incluye este header
    // Solo verificar que la sesión ya esté iniciada y que existan las variables necesarias

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['login'])) {
        // remove all session variables
        session_unset();
        // destroy the session
        session_destroy();
        header('Location: ../../index.php');
        exit;
    }
    $usuario1 = $_SESSION['login'];
    
    // Consultar el rol del usuario en la base de datos para verificación adicional
    // Usar el usuario de la sesión o el id_usua como fallback
    $usuario_buscar = isset($usuario1['usuario']) ? $usuario1['usuario'] : $usuario1['id_usua'];
    
    // Primera búsqueda: por campo 'usuario'
    $query_usuario = "SELECT id_rol, id_usua, nombre, papell, sapell FROM reg_usuarios WHERE usuario = '" . mysqli_real_escape_string($conexion, $usuario_buscar) . "' AND u_activo = 'SI'";
    $resultado_usuario = $conexion->query($query_usuario);
    
    // Si no se encuentra por usuario, buscar por id_usua
    if (!$resultado_usuario || $resultado_usuario->num_rows == 0) {
        $query_usuario = "SELECT id_rol, id_usua, nombre, papell, sapell FROM reg_usuarios WHERE id_usua = '" . mysqli_real_escape_string($conexion, $usuario1['id_usua']) . "' AND u_activo = 'SI'";
        $resultado_usuario = $conexion->query($query_usuario);
    }
    
    if ($resultado_usuario && $resultado_usuario->num_rows > 0) {
        $datos_usuario = $resultado_usuario->fetch_assoc();
        $rol_usuario = $datos_usuario['id_rol'];
        
        // Actualizar datos del usuario con información de la BD si es necesario
        if (!isset($usuario1['id_rol']) || $usuario1['id_rol'] != $rol_usuario) {
            $usuario1['id_rol'] = $rol_usuario;
        }
    } else {
        // Usuario no encontrado en la base de datos o inactivo
        session_unset();
        session_destroy();
        header('Location: ../../index.php?error=Usuario no válido');
        exit;
    }
    
    // Validar si el rol del usuario tiene permisos para farmacia quirófano
    if (!in_array($rol_usuario, [1, 3, 4, 5, 9, 11])) {
        session_unset();
        session_destroy();
        header('Location: ../../index.php?error=Sin permisos');
        exit;
    }
    
    // Actualizar la variable $usuario1 con el rol verificado
    $usuario1['id_rol'] = $rol_usuario;
    ?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>INEO METEPEC</title>
        <link rel="icon" href="../../imagenes/SIF.PNG">
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- Bootstrap 3.3.2 -->
        <link href="../../template/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
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

            /* Mejorar la visualización del panel de usuario en el sidebar */
            .user-panel {
                min-height: 70px !important;
                padding: 10px;
            }

            .user-panel .info {
                width: calc(100% - 55px) !important;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .user-panel .info p {
                margin: 0 0 5px 0;
                font-size: 14px;
                font-weight: 600;
                line-height: 1.2;
            }

            .user-panel .info a {
                font-size: 12px;
                line-height: 1.2;
                display: block;
                color: #b8c7ce;
            }

            .user-panel .info a:hover {
                color: #fff;
                text-decoration: none;
            }

            .user-panel .pull-left.image {
                width: 45px;
                margin-right: 10px;
            }

            /* Asegurar que el sidebar tenga ancho mínimo */
            .main-sidebar {
                min-width: 230px !important;
            }

            /* Para pantallas pequeñas, asegurar que el texto no se corte */
            @media (max-width: 767px) {
                .sidebar-mini.sidebar-collapse .main-sidebar {
                    width: 230px !important;
                }
            }

            /* Animación para el indicador online */
            @keyframes pulse {
                0% {
                    opacity: 1;
                    transform: scale(1);
                }
                50% {
                    opacity: 0.7;
                    transform: scale(1.1);
                }
                100% {
                    opacity: 1;
                    transform: scale(1);
                }
            }

            /* Mejorar el color del indicador online */
            .status-online {
                color: #00a65a !important;
                text-shadow: 0 0 5px rgba(0, 166, 90, 0.5);
            }

            .status-text {
                color: #b8c7ce !important;
                font-size: 15px;
                font-weight: normal;
            }
        </style>
    </head>

    <body class=" hold-transition skin-blue sidebar-mini">
        <div class="wrapper">

            <header class="main-header">
                <!-- Logo -->
                <!-- <img src="dist/img/logo.jpg" alt="logo">-->

                <?php
                if ($usuario1['id_rol'] == 4) {
                ?>
                   <a href="../../template/menu_farmaciaq.php" class="logo">
                        <!-- mini logo for sidebar mini 50x50 pixels -->
                        <!-- logo for regular state and mobile devices -->
                        <span class="logo-lg"><b><img src="../../imagenes/SI.PNG" height="30" width="120"></b> </span>
                    </a>
                <?php
                } else if ($usuario1['id_rol'] == 5) {
                ?>
                    <a href="../../template/menu_gerencia.php" class="logo">
                        <!-- mini logo for sidebar mini 50x50 pixels -->
                        <!-- logo for regular state and mobile devices -->
                        <span class="logo-lg"><b><img src="../../imagenes/SI.PNG" height="30" width="120"></b> </span>
                    </a>
                <?php
                } else if ($usuario1['id_rol'] == 11) {
                ?>
                    <a href="../../template/menu_farmaciacentral.php" class="logo">
                        <!-- mini logo for sidebar mini 50x50 pixels -->
                        <!-- logo for regular state and mobile devices -->
                        <span class="logo-lg"><b><img src="../../imagenes/SI.PNG" height="30" width="120"></b> </span>
                    </a>
                <?php
                } else if ($usuario1['id_rol'] == 1) {
                ?>
                    <a href="../../template/menu_administrativo.php" class="logo">
                        <!-- mini logo for sidebar mini 50x50 pixels -->
                        <!-- logo for regular state and mobile devices -->
                        <span class="logo-lg"><b><img src="../../imagenes/logo.jpg" height="30" width="120"></b> SIMA</span>
                    </a>
                <?php
                } else if ($usuario1['id_rol'] == 3) {
                ?>
                    <a href="../../template/menu_enfermera.php" class="logo">
                        <!-- mini logo for sidebar mini 50x50 pixels -->
                        <!-- logo for regular state and mobile devices -->
                        <span class="logo-lg"><b><img src="../../imagenes/logo.jpg" height="30" width="120"></b> SIMA</span>
                    </a>
                <?php } elseif ($usuario1['id_rol'] == 9) { ?>
                    <a href="../../template/menu_imagenologia.php" class="logo">
                        <!-- mini logo for sidebar mini 50x50 pixels -->
                        <!-- logo for regular state and mobile devices -->
                        <span class="logo-lg"><b><img src="../../imagenes/SI.PNG" height="30" width="120"></b> </span>
                    </a>
                <?php
                } else {
                    session_destroy();
                    echo "<script>window.Location='../../index.php';</script>";
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

                                    <img src="../../imagenes/<?php echo $usuario1['img_perfil']; ?>" class="user-image" alt="User Image" />
                                    <span class="hidden-xs"> <?php echo $usuario1['nombre']; ?> <?php echo $usuario1['papell']; ?> <?php echo $usuario1['sapell']; ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header">

                                        <img src="../../imagenes/<?php echo $usuario1['img_perfil']; ?>" class="img-circle" alt="User Image" />

                                        <p>
                                            <?php echo $usuario1['nombre']; ?> <?php echo $usuario1['papell']; ?> <?php echo $usuario1['sapell']; ?>

                                        </p>
                                    </li>

                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="../../gestion_administrativa/editar_perfil/editar_perfil.php?id_usua=<?php echo $usuario1['id_usua'];?>" class="btn btn-default btn-flat">MIS DATOS</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="../../cerrar_sesion.php" class="btn btn-default btn-flat">Cerrar sesión</a>
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
                            <p><?php echo $usuario1['nombre']; ?> <?php echo $usuario1['papell']; ?></p>
                            <a href="#" style="text-decoration: none;">
                                <i class="fa fa-circle status-online" style="animation: pulse 2s infinite;"></i> 
                                <span class="status-text">Online</span>
                            </a>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <?php if ($usuario1['id_rol'] == 3) { ?>
                        <ul class="sidebar-menu">
                            <li class="treeview">
                                <a href="../../template/menu_farmaciaq.php">
                                    <i class="fa fa-folder"></i> <span>SALIDAS MEDICAMENTO</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>

                            </li>
                        </ul>
                    <?php } else { ?>
                        <ul class="sidebar-menu">


                            <li class=" treeview">
                                <a href="../farmaciaq/surtir_pacienteq.php">
                                    <i class="fas fa-hand-holding-medical"></i> <span>SURTIR MÉDICAMENTOS</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>

                            </li>
                            <li class=" treeview">
                                <a href="../farmaciaq/existenciasq.php">
                                    <i class="fas fa-boxes"></i> <span>EXISTENCIAS</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>

                            </li>
                            <li class=" treeview">
                                <a href="../farmaciaq/kardexq.php">
                                                            <i class="fas fa-chart-line"></i><span>KARDEX</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>

                            </li>
                            <li class=" treeview">
                                <a href="../farmaciaq/caducadoq.php">
                                    <i class="fas fa-calendar-times"></i> <span>CONTROL DE <br> CADUCIDADES</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>

                            </li>
                            <li class=" treeview">
                                <a href="../farmaciaq/devolucionesq.php">
                                    <i class="fas fa-undo"></i> <span>DEVOLUCIONES</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>

                            </li>

                            <li class="treeview">
                                <a href="../farmaciaq/confirmar_envioq.php">
                                    <i class="fas fa-check-circle"></i> <span>CONFIRMAR DE <br> RECIBIDO</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>

                            </li>
                            <li class="treeview">
                                <a href="../farmaciaq/pedir_almacenq.php">
                                    <i class="fas fa-shopping-cart"></i> <span>PEDIR A ALMACEN</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>

                            </li>
                            <li class="treeview">
                                <a href="../farmaciaq/salidasq.php">
                                    <i class="fas fa-file-export"></i><span>SALIDAS MEDICAMENTO</span>
                                    <i class="fa fa-angle-left pull-right"></i>
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
                        <li class="breadcrumb-item active" aria-current="page"><STRONG>
                                <h4>FARMACIA QUIROFANO</h4>
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
                            alert("INGRESA DATOS CORRECTOS");
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
                            alert("INGRESA DATOS CORRECTOS");
                            return false;
                        }
                    }
                </script>

                <script>
                    function SoloNumeroscuenta(evt) {
                        if (window.event) {
                            keynum = evt.keyCode;
                        } else {
                            keynum = evt.which;
                        }

                        if ((keynum > 47 && keynum < 58) || keynum == 8 || keynum == 13 || keynum == 45) {
                            return true;
                        } else {
                            alert("INGRESA DATOS CORRECTOS");
                            return false;
                        }
                    }
                </script>

                <script>
                    function Curp(evt) {
                        if (window.event) {
                            keynum = evt.keyCode;
                        } else {
                            keynum = evt.which;
                        }
                        if ((keynum > 47 && keynum < 58) || (keynum > 64 && keynum < 91) || (keynum > 96 && keynum < 123)) {
                            return true;
                        } else {
                            alert("INGRESA DATOS CORRECTOS");
                            return false;
                        }
                    }
                </script>

    <!-- jQuery 2.1.3 -->
    <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="../../template/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
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

    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="../../template/dist/js/pages/dashboard2.js" type="text/javascript"></script>

    <!-- AdminLTE for demo purposes -->
    <script src="../../template/dist/js/demo.js" type="text/javascript"></script>