<?php
include "../../conexionbd.php";
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
$resultado=$conexion->query("SELECT * FROM reg_usuarios WHERE id_usua='".$usuario."'") or die($conexion->error);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Venecia</title>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <!-- Bootstrap 3.3.2 -->
  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
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








  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->


</head>

<body class=" hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <header class="main-header">
      <!-- Logo -->
      <!-- <img src="dist/img/logo.jpg" alt="logo">-->

      <a href="../../template/menu_administrativo.php" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b><img src="../../imagenes/logo.jpg" height="30" width="120"></b> SIMA</span>
      </a>
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

                <span class="hidden-xs"><?php echo $usuario['nombre'];?> <?php echo $usuario['papell'];?> <?php echo $usuario['sapell'];?></span>

              </a>
              <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">
                  <img src="../../imagenes/<?php echo $usuario['img_perfil'];?>" class="img-circle" alt="User Image" />
                  <p>
                    <?php echo $usuario['nombre'];?> <?php echo $usuario['papell'];?> <?php echo $usuario['sapell'];?>
                  </p>
                </li>

                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="#" class="btn btn-default btn-flat">Profile</a>
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
            <img src="../../imagenes/<?php echo $usuario['img_perfil'];?>" class="img-circle" alt="User Image" />
          </div>
          <div class="pull-left info">
            <p><?php echo $usuario['nombre'];?> <?php echo $usuario['papell'];?></p>

            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
          </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">

          <li class="active treeview">
            <a href="#">
              <i class="fa fa-user-friends"></i> <span>Gestión de Pacientes</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
              <ul class="treeview-menu">
                  <li><a href="menu_rp.php"><i class="fa fa-user-plus"></i> Creación de Pacientes</a></li>
                  <li><a href="../cuenta_paciente/vista_ahosp.php"><i class="fa fa-ambulance"></i> Admisión Hospitalaría</a></li>

              </ul>
          </li>

          <li class="treeview">
            <a href="#">
              <i class="fa fa-donate"></i> <span>Gestión Financiera</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="../cuenta_paciente/vista_df.php"><i class="fa fa-file-invoice-dollar"></i> Datos Financieros</a></li>
              <li><a href="pages/examples/login.html"><i class="fa fa-hand-holding-usd"></i> Cuenta del Paciente</a></li>

            </ul>
          </li>

          <li class="treeview">
            <a href="#">
              <i class="fa fa-running"></i> <span>Egreso del Paciente</span>
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
              <h4>ADMINISTRATIVO</h4>
            </STRONG></li>
        </ol>
      </nav>

      <!-- Main content -->
      <section class="content">
        <!-- CONTENIDOO -->
          <?php
          include("dat_ingreso.php");
          ?>

      </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    <footer class="main-footer">
      <?php
      include("../../template/footer.php");
      ?>
    </footer>

  </div><!-- ./wrapper -->
  <!-- jQuery 2.1.3 -->
  <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
  <!-- FastClick -->
  <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
  <!-- AdminLTE App -->
  <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

</body>

</html>