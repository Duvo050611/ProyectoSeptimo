<?php
include '../conexionbd.php';
$conexion = ConexionBD::getInstancia()->getConexion();
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
if (!($usuario['id_rol'] == 6 || $usuario['id_rol'] == 5 || $usuario['id_rol'] == 1 || $usuario['id_rol'] == 12)) {
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" type="text/css"/>
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
        /* Reset de estilos básicos */
        * {
            box-sizing: border-box;
        }

        /* Estilos del contenedor principal - fondo blanco */
        .config-menu-container {
            padding: 20px;
            background: white;
            min-height: 100vh;
        }

        /* Header del menú - fondo azul predeterminado */
        .config-header {
            text-align: center;
            margin-bottom: 40px;
            padding: 20px;
            background: #2b2d7f;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .config-header h4 {
            color: white;
            font-weight: 700;
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Grid de tarjetas - 4 columnas x 2 filas */
        .config-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: repeat(2, 1fr);
            gap: 30px;
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Estilos de las tarjetas - diseño con fondo de color */
        .config-card {
            border-radius: 20px;
            padding: 40px 20px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
            min-height: 220px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .config-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.25);
        }

        .config-card a {
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            width: 100%;
        }

        /* Círculo de icono - blanco sobre fondo de color */
        .icon-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        .config-card:hover .icon-circle {
            transform: scale(1.05);
        }

        /* Iconos de colores */
        .config-icon {
            font-size: 50px;
            transition: all 0.3s ease;
        }

        /* Títulos */
        .config-title {
            font-size: 16px;
            font-weight: 700;
            margin: 0;
            color: #2c3e50;
            line-height: 1.2;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Fondos de colores pasteles para las tarjetas */
        .card-beds {
            background: #E3F2FD; /* Azul pastel claro */
        }
        .card-beds .config-icon {
            color: #1976D2; /* Azul */
        }

        .card-staff {
            background: #FCE4EC; /* Rosa pastel claro */
        }
        .card-staff .config-icon {
            color: #C2185B; /* Rosa */
        }

        .card-diagnostics {
            background: #E8F5E8; /* Verde pastel claro */
        }
        .card-diagnostics .config-icon {
            color: #388E3C; /* Verde */
        }

        .card-services {
            background: #FFF8E1; /* Amarillo pastel claro */
        }
        .card-services .config-icon {
            color: #F57C00; /* Naranja */
        }

        .card-insurance {
            background: #F3E5F5; /* Morado pastel claro */
        }
        .card-insurance .config-icon {
            color: #7B1FA2; /* Morado */
        }

        .card-diets {
            background: #E8EAF6; /* Índigo pastel claro */
        }
        .card-diets .config-icon {
            color: #3F51B5; /* Índigo */
        }

        .card-specialties {
            background: #EFEBE9; /* Café pastel claro */
        }
        .card-specialties .config-icon {
            color: #5D4037; /* Café */
        }

        .card-admin {
            background: #FFF3E0; /* Naranja pastel claro */
        }
        .card-admin .config-icon {
            color: #FF9800; /* Naranja */
        }

        /* Responsive design */
        @media (max-width: 1024px) {
            .config-grid {
                grid-template-columns: repeat(3, 1fr);
                grid-template-rows: auto;
                gap: 25px;
                padding: 15px;
            }
        }

        @media (max-width: 768px) {
            .config-grid {
                grid-template-columns: repeat(2, 1fr);
                grid-template-rows: auto;
                gap: 20px;
                padding: 10px;
            }

            .config-card {
                padding: 30px 15px;
                min-height: 180px;
            }

            .icon-circle {
                width: 100px;
                height: 100px;
                margin-bottom: 15px;
            }

            .config-icon {
                font-size: 40px;
            }

            .config-title {
                font-size: 14px;
            }

            .config-header {
                margin-bottom: 30px;
                padding: 15px;
            }

            .config-header h4 {
                font-size: 20px;
            }
        }

        @media (max-width: 480px) {
            .config-grid {
                grid-template-columns: 1fr;
                grid-template-rows: auto;
                gap: 15px;
                padding: 5px;
            }

            .config-card {
                padding: 25px 15px;
                min-height: 160px;
            }

            .icon-circle {
                width: 80px;
                height: 80px;
                margin-bottom: 12px;
            }

            .config-icon {
                font-size: 32px;
            }

            .config-title {
                font-size: 13px;
            }
        }

        /* Ocultar estilos antiguos */
        .dropdwn, .dropdwn-content, .todo-container, .status {
            display: none !important;
        }
    </style>

    
    </style>
    </style>
</head>

<body class=" hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <header class="main-header">
      <!-- Logo -->
      <!-- <img src="dist/img/logo.jpg" alt="logo">-->

        <?php
        if ($usuario['id_rol'] == 1 || $usuario['id_rol'] == 12)
        {
            ?>

            <a href="menu_administrativo.php" class="logo">
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
        }
        else if ($usuario['id_rol'] == 5){

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
        }
        else if ($usuario['id_rol'] == 6){

            ?>
            <a href="menu_configuracion.php" class="logo">
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
        }else
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
                <img src="../imagenes/<?php echo $usuario['img_perfil'];?>" class="user-image" alt="User Image" />
                <span class="hidden-xs">  <?php echo $usuario['papell']; ?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">
                  <img src="../imagenes/<?php echo $usuario['img_perfil'];?>" class="img-circle" alt="User Image" />
                  <p>
                     <?php echo $usuario['papell']; ?> 

                  </p>
                </li>

                <!-- Menu Footer-->
                <li class="user-footer">
                 <!-- <div class="pull-left">
                    <a href="#" class="btn btn-default btn-flat">Profile</a>
                  </div>-->
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
            <img src="../imagenes/<?php echo $usuario['img_perfil'];?>" class="img-circle" alt="User Image" />
          </div>
          <div class="pull-left info">
            <p> <?php echo $usuario['papell']; ?></p>

            <a href="#"><i class="fa fa-circle text-success"></i> ACTIVO</a>
          </div>
        </div>
<ul class="sidebar-menu">
<?php
if ($usuario['id_usua'] == 1){
?>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        
          <li class="">
            <a href="../configuracion/camas/cat_camas.php">
              <i class="fa fa-bed"></i> <span>GESTIÓN DE CAMAS</span> 
            </a>
            
          </li>

<?php }?>         
         <li class="">
                <a href="../configuracion/personal/alta_usuarios.php">
                    <i class="fa fa-users"></i> <span>GESTIÓN DE PERSONAL</span> 
                </a>

            </li>

            <li class="">
                <a href="../configuracion/diagnosticos/cat_diagnosticos.php">
                    <i class="fa fa-stethoscope" aria-hidden="true"></i> <span>DIAGNÓSTICOS</span> 
                </a>

            </li>

            <li class="">
                <a href="../configuracion/servicios/cat_servicios.php">
                    <i class="fa fa-plus-circle"></i> <span>SERVICIOS</span> 
                </a>

            </li>
            <li class="">
                <a href="../configuracion/aseguradoras/aseguradora.php">
                    <i class="fa fa-medkit" aria-hidden="true"></i> <span>ASEGURADORAS</span> 
                </a>

            </li>
            <li class="">
                <a href="../configuracion/dietas/cat_dietas.php">
                    <i class="fa fa-folder" aria-hidden="true"></i> <span>DIETAS</span> 
                </a>

            </li>
            <li class="">
                <a href="../configuracion/especialidad/cat_espec.php"> 
                    <i class="fa fa-user-md" aria-hidden="true"></i> <span>ESPECIALIDADES</span> 
                </a>

            </li>

        </ul>
        
      </section>
      <!-- /.sidebar -->
    </aside>

    <!-- Right side column. Contains the navbar and content of the page -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->

      <!--DISEÑO MODERNO CON CÍRCULOS COMO LA SEGUNDA IMAGEN-->
      <div class="config-menu-container">
        <div class="config-header">
          <h4><i class="fas fa-cogs" style="margin-right: 10px;"></i>CONFIGURACIÓN DEL SISTEMA</h4>
        </div>

        <?php if ($usuario['id_rol'] == 5 || $usuario['id_rol'] == 6): ?>
          <div class="config-grid">
            <?php if ($usuario['id_usua'] == 1): ?>
              <div class="config-card card-beds">
                <a href="../configuracion/camas/cat_camas.php" title="GESTIÓN DE CAMAS">
                  <div class="icon-circle">
                    <i class="fas fa-bed config-icon"></i>
                  </div>
                  <h3 class="config-title">GESTIÓN DE CAMAS</h3>
                </a>
              </div>
            <?php endif; ?>
            
            <div class="config-card card-staff">
              <a href="../configuracion/personal/alta_usuarios.php" title="GESTIÓN DE PERSONAL">
                <div class="icon-circle">
                  <i class="fas fa-user-nurse config-icon"></i>
                </div>
                <h3 class="config-title">GESTIÓN DE PERSONAL</h3>
              </a>
            </div>

            <div class="config-card card-diagnostics">
              <a href="../configuracion/diagnosticos/cat_diagnosticos.php" title="DIAGNÓSTICOS">
                <div class="icon-circle">
                  <i class="fas fa-stethoscope config-icon"></i>
                </div>
                <h3 class="config-title">DIAGNÓSTICOS</h3>
              </a>
            </div>

            <div class="config-card card-services">
              <a href="../configuracion/servicios/cat_servicios.php" title="SERVICIOS">
                <div class="icon-circle">
                  <i class="fas fa-concierge-bell config-icon"></i>
                </div>
                <h3 class="config-title">SERVICIOS</h3>
              </a>
            </div>

            <div class="config-card card-insurance">
              <a href="../configuracion/aseguradoras/aseguradora.php" title="ASEGURADORAS">
                <div class="icon-circle">
                  <i class="fas fa-shield-alt config-icon"></i>
                </div>
                <h3 class="config-title">ASEGURADORAS</h3>
              </a>
            </div>

            <div class="config-card card-diets">
              <a href="../configuracion/dietas/cat_dietas.php" title="DIETAS">
                <div class="icon-circle">
                  <i class="fas fa-utensils config-icon"></i>
                </div>
                <h3 class="config-title">DIETAS</h3>
              </a>
            </div>

            <div class="config-card card-specialties">
              <a href="../configuracion/especialidad/cat_espec.php" title="ESPECIALIDADES">
                <div class="icon-circle">
                  <i class="fas fa-user-md config-icon"></i>
                </div>
                <h3 class="config-title">ESPECIALIDADES</h3>
              </a>
            </div>

            <div class="config-card card-admin">
              <a href="../configuracion/admin/imgsistema.php" title="ADMIN SIMA">
                <div class="icon-circle">
                  <i class="fas fa-cog config-icon"></i>
                </div>
                <h3 class="config-title">ADMIN SIMA</h3>
              </a>
            </div>
          </div>

        <?php elseif ($usuario['id_rol'] == 1): ?>
          <div class="config-grid">
            <div class="config-card card-staff">
              <a href="../configuracion/personal/alta_usuarios.php" title="GESTIÓN DE PERSONAL">
                <div class="icon-circle">
                  <i class="fas fa-user-nurse config-icon"></i>
                </div>
                <h3 class="config-title">GESTIÓN DE PERSONAL</h3>
              </a>
            </div>

            <div class="config-card card-services">
              <a href="../configuracion/servicios/cat_servicios.php" title="SERVICIOS">
                <div class="icon-circle">
                  <i class="fas fa-concierge-bell config-icon"></i>
                </div>
                <h3 class="config-title">SERVICIOS</h3>
              </a>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div><!-- /.content-wrapper -->

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-xs-6">
                            <div class="row">
                                <div class="col-lg-1"></div>
                                <div class="col-lg-8">
                                    <center>
                                        <a href="../configuracion/camas/cat_camas.php" title="HABITACIONES"><img class="card-img-top" src="../img/habitacion.png" alt="HABITACIONES" height="150" width="200" /></a>
                                    </center>
                                    <center>
                                        <h3>HABITACIONES</h3>
                                    </center>

                                </div>
                                <div class="col-lg-1"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xs-6">
                            <div class="row">
                                <div class="col-lg-1"></div>
                                <div class="col-lg-8">
                                    <center>
                                        <a href="../configuracion/camas/dispo_camas.php"  title="OCUPACIÓN DE CAMAS"><img class="card-img-top" src="../img/camas_hospital.png" alt="OCUPACIÓN DE CAMAS" height="150" width="200" /></a>
                                    </center>
                                    <center>
                                        <h3>OCUPACIÓN DE CAMAS</h3>
                                    </center>
                                </div>
                                <div class="col-lg-1"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">CERRAR</button>
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