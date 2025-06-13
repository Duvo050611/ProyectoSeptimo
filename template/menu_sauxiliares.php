<?php
include "../conexionbd.php";
session_start();
if (!isset($_SESSION['login'])) {
    // remove all session variables
    session_unset();
    // destroy the session
    session_destroy();
    header('Location: ../index.php');
}
$usuario = $_SESSION['login'];
//$resultado = $conexion->query("SELECT * FROM reg_usuarios WHERE id_usua='" . $usuario . "'") or die($conexion->error);

if (!($usuario['id_rol'] == 4 || $usuario['id_rol'] == 5 )) {
    // remove all session variables
    session_unset();
// destroy the session
    session_destroy();
    echo "<div class='alert alert-danger mt-4' role='alert'>No tienes permiso para ingresar aquí!
  <p><a href='index.php'><strong>Por favor, intente de nuevo!</strong></a></p></div>";
    header('Location: ../index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Médica del Ángel Custodio</title>
    <link rel="icon" href="../imagenes/SIF.PNG">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet"
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
    
        h3 {
            text-align: center;

        }
        .crc1{
          top:19px;
          left:-360px;
        }
       
.crc2{
          top:-190px;  
        }
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
        @media screen and (max-width: 980px){
    .content{
        width:300px;
       margin-left:-0px;
   }
      .card-img-top{
 width:70px;
 height: 70px;
      }
      
       h4 {
            text-align: center;
            font-size:11px;
        }
        
         .crc1{
          
            top:-3px;  
             left:7px;
        }

        .crc2{
            left:-8px;
          top:-4px;  
        }
    
    
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

            <a href="menu_almacencentral.php" class="logo">
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
        } else if ($usuario['id_rol'] == 4) {

            ?>
            <a href="menu_sauxiliares.php" class="logo">
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
        ?>     <!-- Header Navbar: style can be found in header.less -->
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
                            <span class="hidden-xs">  <?php echo $usuario['papell'];?> </span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">

                                <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle"
                                     alt="User Image">

                                <p>
                                 <?php echo $usuario['papell'];?>

                                </p>
                            </li>

                            <!-- Menu Footer-->
               <li class="user-footer">
                  <div class="pull-left">
                    <a href="../sauxiliares/editar_perfil/editar_perfil_saux.php?id_usua=<?php echo $usuario['id_usua'];?>" class="btn btn-default btn-flat">MIS DATOS</a>
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
                      <i class="fa fa-folder"></i> <span><strong>SERVICIOS AUXILIARES</strong></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    
                 
                  <ul class="treeview-menu">
                    <li><a href="../template/menu_almacencentral.php"><i class="fa fa-circle"></i>ALMACEN CENTRAL</a></li>
                    <li><a href="../template/menu_farmacia.php"><i class="fa fa-circle"></i>FARMACIA</a></li>
                    <li><a href="../template/menu_ceye.php"><i class="fa fa-circle"></i>QUIRÓFANO</a></li>
                 <!--   <li><a href="../template/menu_rojo1.php"><i class="fa fa-circle"></i>CARRO ROJO OBSERVACIÓN</a></li>
                    <li><a href="../template/menu_rojo2.php"><i class="fa fa-circle"></i>CARRO ROJO TERAPIA</a></li>
                    <li><a href="../template/menu_rojo3.php"><i class="fa fa-circle"></i>CARRO ROJO QUIRÓFANO</a></li>
                    <li><a href="../template/menu_rojo4.php"><i class="fa fa-circle"></i>CARRO ROJO CENTRAL 1</a></li>
                    <li><a href="../template/menu_rojo5.php"><i class="fa fa-circle"></i>CARRO ROJO CENTRAL 2</a></li>-->
                                         
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
                        <h4>SERVICIOS AUXILIARES</h4>
                    </STRONG></li>
            </ol>
        </nav>

        <!-- Main content -->
        <section class="content">
            <!-- CONTENIDOO -->
            <section class="content container-fluid">
                <div class="content box">
                    <!-- CONTENIDOO -->
                    <div class="row">
                        <div class="col-lg-4 col-xs-6">
                            <div class="row">
                                <div class="col-lg-1"></div>
                                <div class="col-lg-8">
                                    <center>
                                        <a title="ALMACEN CENTRAL" 
                                            href="../template/menu_almacencentral.php"  
                                            title="ALMACEN CENTRAL">
                                            <img class="card-img-top" src="../img/almacen_central.png" alt="ceye" height="150" width="200" />
                                        </a>                                      
                                    </center>
                                    <center><h4><strong> ALMACEN CENTRAL</strong></h4></center>

                                </div>
                                <div class="col-lg-1"></div>
                            </div>

                        </div>
                        <div class="col-lg-4 col-xs-6">
                            <div class="row">
                                <div class="col-lg-1"></div>
                                <div class="col-lg-8">
                                    <center>
                                        <a title="FARMACIA" 
                                           href="../template/menu_farmacia.php" 
                                           title="Farmacia">
                                           <img class="card-img-top" src="../img/farmacia_hospitalaria.png
                                           " alt="farmacia" height="150" width="200" /></a>
                                    </center>
                                    <center><h4><strong>FARMACIA</strong></h4></center>

                                </div>
                                <div class="col-lg-1"></div>
                            </div>
                        </div>
                    <div class="col-lg-4 col-xs-6">
                        <div class="row">
                            <div class="col-lg-1"></div>
                            <div class="col-lg-8">
                                <center>
                                    <a title="QUIRÓFANO" 
                                    href="../template/menu_ceye.php"  
                                    title="QUIRÓFANO">
                                    <img class="card-img-top" src="../img/farmacia_quirofano.png" alt="ceye" height="150" width="200" /></a>
                                </center>
                                <center><h4><strong>QUIRÓFANO</strong></h4></center>
                            </div>
                            <div class="col-lg-1"></div>
                        </div>
                    </div>
                    <!--
                    <div class="col-lg-4 col-xs-6 cro">
                        <div class="row">
                            <div class="col-lg-1"></div>
                            <div class="col-lg-8">
                                <center>
                                    <a title="CARRO ROJO 1" 
                                    href="../template/menu_rojo1.php"  
                                    title="OBSERVACIÓN">
                                    <img class="card-img-top" src="../img/rojo1.jpg" alt="Carro rojo 1" height="150" width="200" /></a>
                                </center>
                                <center><h4><strong>CARRO ROJO OBSERVACIÓN</strong></h4></center>
                            </div>
                            <div class="col-lg-1"></div>
                        </div>

                    </div>
                    <div class="col-lg-4 col-xs-6">
                        <div class="row">
                            <div class="col-lg-1"></div>
                            <div class="col-lg-8">
                                <center>
                                    <a title="CARRO ROJO 2" 
                                    href="../template/menu_rojo2.php"  
                                    title="CARRO ROJO TERAPIA">
                                    <img class="card-img-top" src="../img/rojo2.jpg" alt="Carro rojo 2" height="150" width="200" /></a>
                                </center>
                                <center><h4><strong>CARRO ROJO TERAPIA INTENSIVA</strong></h4></center>
                            </div>
                            <div class="col-lg-1"></div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xs-6">
                        <div class="row">
                            <div class="col-lg-1"></div>
                            <div class="col-lg-8">
                                <center>
                                    <a title="CARRO ROJO 3" 
                                    href="../template/menu_rojo3.php"  
                                    title="CARRO ROJO QUIRÓFANO">
                                    <img class="card-img-top" src="../img/rojo3.jpg" alt="Carro rojo 2" height="150" width="200" /></a>
                                </center>
                                <center><h4><strong>CARRO ROJO QUIRÓFANO</strong></h4></center>
                            </div>
                            <div class="col-lg-1"></div>
                        </div>
                    </div>
                     <div class="row">
                    <div class="col-lg-4 col-xs-6 crc1">
                       
                            <div class="col-lg-1"></div>
                            <div class="col-lg-8">
                                <center>
                                    <a title="CARRO ROJO 4" 
                                    href="../template/menu_rojo4.php"  
                                    title="CARRO ROJO CENTRAL 1">
                                    <img class="card-img-top" src="../img/rojo4.jpg" alt="Carro rojo 2" height="150" width="200" /></a>
                                </center>
                                <center><h4><strong>CARRO ROJO CENTRAL 1</strong></h4></center>
                            </div>
                            <div class="col-lg-1"></div>
                        
                    </div>
                    <div class="col-lg-4 col-xs-6 crc2">
                       
                            <div class="col-lg-1"></div>
                            <div class="col-lg-8">
                                <center>
                                    <a title="CARRO ROJO 5" 
                                    href="../template/menu_rojo5.php"  
                                    title="CARRO ROJO CENTRAL 2">
                                    <img class="card-img-top" src="../img/rojo5.jpg" alt="Carro rojo 2" height="150" width="200" /></a>
                                </center>
                                <center><h4><strong>CARRO ROJO CENTRAL 2</strong></h4></center>
                            </div>
                            <div class="col-lg-1"></div>
                        </div>-->
                  
                
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