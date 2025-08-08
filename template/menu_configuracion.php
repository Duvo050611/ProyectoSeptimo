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
            
            
            
            
              .footer{
                   
                 font-size:9px;
            }
            
           
            .esi{
                   
                   margin-top:-110px;
            }
            
            .confii{
                   
                   margin-top:1px;
            }
            
            #meddi{
                   width:150px;
                   height:auto;
                
            }
            
            .card-img-top{
             width:80px;
 height: 80px;
            }
            
      .img-fluid{
 width:80px;
 height: 80px;
    
      }
  h4 {
      font-size:8px;

        }
    
.patoi{
top:-82px;
left:9px;
 }   
    
    .medi{
        top:1px;
    }
    
    .inteni{
         top:-112px;
    }   
   
    .bioi{
         top:-112px;
    }   
    
    .patoi{
        top:4px;
        left:-4px;
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
        if ($usuario['id_rol'] == 1 || $usuario['id_rol'] == 12)
        {
            ?>

            <a href="menu_administrativo.php" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                
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

      <!--AQUI VA QUE PUESTO TIENE-->
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page"><STRONG>
              <h4>CONFIGURACION DEL SISTEMA</h4>
            </STRONG></li>
        </ol>
      </nav>
<?php
if ($usuario['id_rol'] == 5 || $usuario['id_rol'] == 6){
?>
      <!-- Main content -->
      <section class="responsive">
        <section class="content container-fluid">
                <div class="content box">
                    <!-- CONTENIDOO -->
                    <div class="row">
                        <?php
                         if ($usuario['id_usua'] == 1){
                            ?>

                        <div class="col-lg-3">
                            <div style="background-color: #bbdefb">
                                <center>
                                    <a title="GESTIÓN DE CAMAS" href="../configuracion/camas/cat_camas.php">
                                    <img class="card-img-top" src="../img/cama_hosp.png" alt="admision" height="80" width="100"/></a>
                                </center>
                                <center><h3>GESTIÓN DE CAMAS</h3></center>
                            </div>
                        </div>
                          <?php }?>
                        <div class="col-lg-3">
                            <div style="background-color: #ffccbc">
                                <center>
                                        <a title="GESTIÓN DE PERSONAL" 
                                        href="../configuracion/personal/alta_usuarios.php">
                                        <img class="card-img-top" src="../img/personal_hospi.png" alt="GESTIÓN DE PERSONAL" height="80" width="100" /></a>
                                </center>
                                <center><h3>GESTIÓN DE PERSONAL</h3></center>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div style="background-color: #dcedc8">
                                <center>
                                    <a title="DIAGNÓSTICOS" 
                                    href="../configuracion/diagnosticos/cat_diagnosticos.php">
                                    <img class="card-img-top" src="../img/diagnosticos.jpg" alt="DIAGNÓSTICOS" height="80" width="100" /></a>
                                </center>
                                <center><h3>DIAGNÓSTICOS</h3></center>
                            </div>
                        </div>
                        
                        <div class="col-lg-3">
                            <div style="background-color: #ffecb3">
                                <center>
                                    <a title="SERVICIOS" href="../configuracion/servicios/cat_servicios.php">
                                        <img class="card-img-top" src="../img/servicios.jpg" alt="SERVICIOS" height="80" width="100" /></a>
                                </center>
                                <center><h3>SERVICIOS</h3></center>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">    
                        <div class="col-lg-3">
                            <div style="background-color: #e1bee7">
                                <center>
                                    <a title="ASEGURADORAS" href="../configuracion/aseguradoras/aseguradora.php">
                                        <img class="card-img-top" src="../img/aseg.jpg" alt="ASEGURADORAS" height="80" width="100" /></a>
                                </center>
                                <center><h3>ASEGURADORAS</h3></center>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div style="background-color: #EDE7F6 ">
                                <center>
                                    <a title="DIETAS" href="../configuracion/dietas/cat_dietas.php">
                                    <img class="card-img-top" src="../img/dietas.jpg" alt="DIETAS" height="80" width="100" /></a>
                                </center>
                                <center><h3>DIETAS</h3></center>
                            </div>
                        </div>
                            
                        <div class="col-lg-3">
                            <div style="background-color: #d7ccc8">
                                <center>
                                    <a title="ESPECIALIDADES" href="../configuracion/especialidad/cat_espec.php">
                                    <img class="card-img-top" src="../img/especialidades.jpg" alt="ESPECIALIDADES" height="80" width="100" /></a>
                                </center>
                                <center><h3>ESPECIALIDADES</h3></center>
                            </div>
                        </div>
                                
                        <div class="col-lg-3">
                            <div style="background-color: #9999CC ">
                                <center>
                                    <a title="ESPECIALIDADES" href="../configuracion/admin/imgsistema.php">
                                    <img class="card-img-top" src="../img/config.png" alt="ESPECIALIDADES" height="80" width="100" /></a>
                                </center>
                                <center><h3>ADMIN SIMA</h3></center>
                            </div>
                        </div>
                    </div>

            </section><!-- /.content -->
      </section><!-- /.content -->
      <?php }else if ($usuario['id_rol'] == 1){?>
      
      <section class="content">
        <section class="content container-fluid">
                <div class="content box">
                    <!-- CONTENIDOO -->
                    <div class="row">
                    
                        <div class="col-lg-4 col-xs-6">
                            <div class="row">
                                <div class="col-lg-12">
                                    <center>
                                        <a title="GESTIÓN DE PERSONAL" href="../configuracion/personal/alta_usuarios.php"><img class="card-img-top" src="../img/personal_hosp.jpg" alt="GESTIÓN DE PERSONAL" height="150" width="200" /></a>
                                    </center>
                                    <center>
                                        <h3>GESTIÓN DE PERSONAL</h3>
                                    </center>

                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-4 col-xs-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <center>
                                            <a title="SERVICIOS" href="../configuracion/servicios/cat_servicios.php">
                                            <img class="card-img-top" src="../img/servicios.png" alt="SERVICIOS" height="150" width="200" /></a>
                                        </center>
                                        <center>
                                            <h3>SERVICIOS</h3>
                                        </center>

                                    </div>

                                </div>
                            </div>
                            
                            
                          
                            
<div class="col-lg-4 col-xs-6">
                                    
                                </div>
                        </div>
                    </div>

            </section><!-- /.content -->
      </section><!-- /.content -->
      
      <?php }?>
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