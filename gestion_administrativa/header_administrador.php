<?php
require_once "../../conexionbd.php";
$conexion = ConexionBD::getInstancia()->getConexion();

if (!isset($_SESSION['login'])) {
  session_unset();
  session_destroy();
}
$usuario = $_SESSION['login'];

if (!($usuario['id_rol'] == 1 || $usuario['id_rol'] == 5 || $usuario['id_rol'] == 12 || $usuario['id_rol'] == 9)) {
  session_unset();
  session_destroy();
  header('Location: ../../index.php');
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>INEO Metepec</title>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  
  <!-- AdminLTE dashboard demo -->
  <script src="../../template/dist/js/pages/dashboard2.js" type="text/javascript"></script>
  <script src="../../template/dist/js/demo.js" type="text/javascript"></script>

  <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
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
  <!-- AdminLTE Skins -->
  <link href="../../template/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

  <!-- jQuery 2.1.3 -->
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
  <!-- SlimScroll -->
  <script src="../../template/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
  <!-- ChartJS -->
  <script src="../../template/plugins/chartjs/Chart.min.js" type="text/javascript"></script>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

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

    /* Enlaces principales del sidebar */
    .sidebar-menu > li > a {
      color: #ffffff !important;
      border-left: 3px solid transparent;
      transition: all 0.3s ease;
      background: transparent !important;
    }

    .sidebar-menu > li > a:hover,
    .sidebar-menu > li.active > a {
      background: rgba(64, 224, 255, 0.1) !important;
      border-left: 3px solid #40E0FF !important;
      color: #40E0FF !important;
    }

    /* Enlaces del submenu (treeview) */
    .treeview-menu > li > a {
      color: #e0e0e0 !important;
      background: rgba(10, 10, 10, 0.3) !important;
      padding-left: 25px !important;
      transition: all 0.3s ease;
      border-left: 3px solid transparent;
    }

    .treeview-menu > li > a:hover {
      background: rgba(64, 224, 255, 0.15) !important;
      color: #40E0FF !important;
      border-left: 3px solid #40E0FF !important;
      padding-left: 28px !important;
    }

    .treeview-menu > li.active > a {
      background: rgba(64, 224, 255, 0.2) !important;
      color: #40E0FF !important;
      border-left: 3px solid #40E0FF !important;
      font-weight: 600;
    }

    /* Iconos en el submenu con mejor visibilidad */
    .treeview-menu > li > a > .fa {
      color: #40E0FF !important;
      margin-right: 8px;
      font-size: 14px;
    }

    .treeview-menu > li > a:hover > .fa {
      color: #00D9FF !important;
      transform: scale(1.1);
    }

    /* User panel */
    .user-panel {
      border-bottom: 1px solid rgba(64, 224, 255, 0.2);
      padding: 10px;
    }

    .user-panel .info {
      color: #ffffff !important;
    }

    .user-panel .info p,
    .user-panel .info a {
      color: #ffffff !important;
    }

    .user-panel .info a:hover {
      color: #40E0FF !important;
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

    .breadcrumb-item.active {
      color: #ffffff !important;
    }

    /* Dropdown menu */
    .dropdown-menu {
      background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
      border: 2px solid #40E0FF !important;
      border-radius: 10px;
    }

    .dropdown-menu > li > a {
      color: #ffffff !important;
    }

    .dropdown-menu > li > a:hover {
      background: rgba(64, 224, 255, 0.1) !important;
      color: #40E0FF !important;
    }

    .user-header {
      background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
    }

    .user-header p {
      color: #ffffff !important;
    }

    .user-footer {
      background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
      border-top: 1px solid rgba(64, 224, 255, 0.2);
    }

    .user-footer .btn {
      border-radius: 25px !important;
      padding: 10px 20px !important;
      font-weight: 600 !important;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: all 0.3s ease !important;
      border: 2px solid #40E0FF !important;
      background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
      color: #ffffff !important;
      box-shadow: 0 4px 15px rgba(64, 224, 255, 0.2);
    }

    .user-footer .btn:hover {
      transform: translateY(-3px) !important;
      box-shadow: 0 10px 25px rgba(64, 224, 255, 0.4) !important;
      background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
      border-color: #00D9FF !important;
      color: #40E0FF !important;
    }

    /* Navbar custom menu */
    .navbar-custom-menu .navbar-nav > li > a {
      color: #ffffff !important;
    }

    .navbar-custom-menu .navbar-nav > li > a:hover {
      background: rgba(64, 224, 255, 0.1) !important;
      color: #40E0FF !important;
    }

    /* Sidebar toggle */
    .sidebar-toggle {
      color: #40E0FF !important;
    }

    .sidebar-toggle:hover {
      background: rgba(64, 224, 255, 0.1) !important;
    }

    /* Dropdown personalizado */
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
      transition: all 0.3s ease;
    }

    .navbar a:hover,
    .dropdwn:hover .dropbtn,
    .dropbtn:focus {
      background: rgba(64, 224, 255, 0.1) !important;
      color: #40E0FF !important;
    }

    .dropdwn-content {
      display: none;
      position: absolute;
      background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
      min-width: 160px;
      box-shadow: 0px 8px 16px 0px rgba(64, 224, 255, 0.3);
      z-index: 1;
      border: 2px solid #40E0FF;
      border-radius: 10px;
    }

    .dropdwn-content a {
      float: none;
      color: #ffffff !important;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
      text-align: left;
      transition: all 0.3s ease;
    }

    .dropdwn-content a:hover {
      background: rgba(64, 224, 255, 0.1) !important;
      color: #40E0FF !important;
    }

    .show {
      display: block;
    }

    /* Todo container */
    .todo-container {
      max-width: 15000px;
      height: auto;
      display: flex;
      overflow-y: scroll;
      column-gap: 0.5em;
      column-rule: 1px solid rgba(64, 224, 255, 0.3);
      column-width: 140px;
      column-count: 7;
    }

    .status {
      width: 25%;
      background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
      border: 2px solid #40E0FF;
      border-radius: 10px;
      position: relative;
      padding: 60px 1rem 0.5rem;
      height: 100%;
      box-shadow: 0 4px 15px rgba(64, 224, 255, 0.2);
    }

    .status h4 {
      position: absolute;
      top: 0;
      left: 0;
      background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
      color: #40E0FF !important;
      margin: 0;
      width: 100%;
      padding: 0.5rem 1rem;
      border-radius: 8px 8px 0 0;
      text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
      font-weight: 600;
      letter-spacing: 1px;
    }

    /* Scrollbar personalizado */
    ::-webkit-scrollbar {
      width: 12px;
      height: 12px;
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

    /* Iconos FontAwesome con efecto */
    .fa {
      transition: all 0.3s ease;
    }

    .sidebar-menu > li > a > .fa {
      color: #ffffff !important;
      margin-right: 8px;
    }

    .sidebar-menu li:hover .fa {
      transform: scale(1.2);
      color: #40E0FF !important;
    }

    /* Logo en el header */
    .logo img {
      filter: brightness(1.2) drop-shadow(0 0 10px rgba(64, 224, 255, 0.3));
    }

    /* User image con efecto */
    .user-image,
    .img-circle {
      border: 2px solid #40E0FF !important;
      box-shadow: 0 0 15px rgba(64, 224, 255, 0.3);
    }

    /* Mejora de contraste para texto en font tags */
    .treeview-menu font {
      color: #e0e0e0 !important;
      font-weight: 500;
    }

    .treeview-menu > li > a:hover font {
      color: #40E0FF !important;
      font-weight: 600;
    }

    /* Active state para treeview abierto */
    .sidebar-menu .treeview.active {
      background: rgba(10, 10, 10, 0.2) !important;
    }

    .sidebar-menu .treeview.active > a {
      background: rgba(64, 224, 255, 0.15) !important;
      border-left: 3px solid #40E0FF !important;
    }

    /* Mejora visual para el ángulo de expansión */
    .sidebar-menu .treeview > a > .pull-right {
      color: #40E0FF !important;
      transition: transform 0.3s ease;
    }

    .sidebar-menu .treeview.active > a > .pull-right {
      transform: rotate(-90deg);
    }

    /* Separador visual entre elementos del menu */
    .sidebar-menu > li {
      border-bottom: 1px solid rgba(64, 224, 255, 0.1);
    }

    /* Efecto de resaltado en hover para todo el item */
    .sidebar-menu > li:hover {
      background: rgba(64, 224, 255, 0.05) !important;
    }

    /* Responsive */
    @media screen and (max-width: 768px) {
      .breadcrumb {
        padding: 15px 20px !important;
      }

      .breadcrumb h4 {
        font-size: 1.1rem;
        letter-spacing: 1px;
      }

      .sidebar-menu > li > a {
        font-size: 14px;
      }

      .treeview-menu > li > a {
        font-size: 13px;
        padding-left: 20px !important;
      }
    }

    /* Animaciones de entrada */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .content-wrapper > * {
      animation: fadeInUp 0.6s ease-out;
    }

    /* Efectos hover suaves */
    a {
      transition: all 0.3s ease;
    }

    /* Active state para items del sidebar */
    .sidebar-menu .treeview.active > a {
      background: rgba(64, 224, 255, 0.15) !important;
      border-left: 3px solid #40E0FF !important;
    }

    /* Animación de entrada para submenu */
    @keyframes slideDown {
      from {
        opacity: 0;
        max-height: 0;
      }
      to {
        opacity: 1;
        max-height: 500px;
      }
    }

    .treeview-menu {
      animation: slideDown 0.3s ease-out;
    }

    /* Font size adjustments */
    font {
      color: inherit;
    }

    /* Color de texto mejorado para mejor legibilidad */
    .sidebar-menu a {
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    }

    /* Indicador visual de item activo */
    .treeview-menu > li.active::before {
      content: '';
      position: absolute;
      left: 0;
      top: 50%;
      transform: translateY(-50%);
      width: 3px;
      height: 20px;
      background: #40E0FF;
      box-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
    }
  </style>

  <link rel="icon" type="image/png" href="../../imagenes/SIF.PNG">

</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <header class="main-header">
      <?php
      if ($usuario['id_rol'] == 1) {
      ?>
        <a href="../../template/menu_administrativo.php" class="logo">
          <span class="logo-lg"><b><img src="../../imagenes/SI.PNG" height="45" width="115"></b></span>
        </a>
      <?php
      } else if ($usuario['id_rol'] == 5) {
      ?>
        <a href="../../template/menu_gerencia.php" class="logo">
          <span class="logo-lg"><b><img src="../../imagenes/SI.PNG" height="45" width="115"></b></span>
        </a>
      <?php
      } else if ($usuario['id_rol'] == 12) {
      ?>
        <a href="../../template/menu_residente.php" class="logo">
          <span class="logo-lg"><b><img src="../../imagenes/SI.PNG" height="45" width="115"></b> SIMA</span>
        </a>
      <?php
      } else if ($usuario['id_rol'] == 9) {
      ?>
        <a href="../../template/menu_imagenologia.php" class="logo">
          <span class="logo-lg"><b><img src="../../imagenes/SI.PNG" height="45" width="115"></b> SIMA</span>
        </a>
      <?php
      } else
        session_destroy();
      echo "<script>window.Location='../../index.php';</script>";
      ?>
      
      <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="../../imagenes/<?php echo $usuario['img_perfil']; ?>" class="user-image" alt="User Image">
                <span class="hidden-xs"><font size="2"> <?php echo $usuario['papell']; ?> </font></span>
              </a>
              <ul class="dropdown-menu">
                <li class="user-header">
                  <img src="../../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle" alt="User Image">
                  <p><font size="2"><?php echo $usuario['papell']; ?></font></p>
                </li>
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="../../gestion_administrativa/editar_perfil/editar_perfil.php?id_usua=<?php echo $usuario['id_usua'];?>" class="btn btn-default btn-flat">MIS DATOS</a>
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

    <aside class="main-sidebar">
      <section class="sidebar">
        <div class="user-panel">
          <div class="pull-left image">
            <img src="../../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle" alt="User Image" />
          </div>
          <div class="pull-left info">
            <p><font size="2"> <?php echo $usuario['papell']; ?></font></p>
            <a href="#"><i class="fa fa-circle text-success"></i> ACTIVO</a>
          </div>
        </div>

        <ul class="sidebar-menu">
          <li class="active treeview">
            <a href="#">
              <i class="fa fa-user-friends"></i> <span><font size="2">ADMINISTRATIVO</font></span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="../gestion_pacientes/registro_pac.php"><i class="fa fa-user-plus"></i><font size="2"> GESTIÓN DE PACIENTE</font></a></li>
              <li><a href="../../gestion_administrativa/censo/tabla_censo.php"> <i class="fa fa-list-alt" aria-hidden="true"></i> <font size="2">CENSO</font></a></li>
              <?php 
              if ($usuario['id_rol']== 5) {
              ?>
              <!--<li><a href="../cuenta_paciente/valida_cta.php"><i class="fa fa-usd" aria-hidden="true"></i> <font size="2">VALIDACIÓN CUENTAS</font></a></li>
              <li><a href="../cuenta_paciente/valida_cta_serv.php"><i class="fa fa-usd" aria-hidden="true"></i> <font size="2">VALIDACIÓN SERVICIOS</font></a></li>-->
              <?php } ?>
            </ul>
          </li>
          <li class="treeview">
            <a href="#">
              <i class="fa fa-donate"></i> <span><font size="2">GESTIÓN DE CUENTAS</font></span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="../cuenta_paciente/vista_df.php"><i class="fa fa-donate"></i> CUENTA DE PACIENTES</a></li>
              <li><a href="../presupuesto/presupuesto.php"><i class="fa fa-file-invoice-dollar"></i> PRESUPUESTOS</a></li>
              <li><a href="../cuenta_paciente/corte_caja.php"><i class="fa fa-file-invoice-dollar"></i> CORTE DE CAJA</a></li>
            </ul>
          </li>
        </ul>
      </section>
    </aside>

    <div class="content-wrapper">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">
            <STRONG><h4>ADMINISTRATIVO</h4></STRONG>
          </li>
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