<?php
include "../conexionbd.php";
  $lifetime=86400;
  session_set_cookie_params($lifetime);
session_start();
if (!isset($_SESSION['login'])) {
    // remove all session variables
    session_unset();
    // destroy the session
    session_destroy();
    header('Location: ../index.php');
}
$usuario = $_SESSION['login'];
$ejecutivo = $usuario['id_usua'];

//$resultado = $conexion->query("SELECT * FROM reg_usuarios WHERE id_usua='" . $usuario . "'") or die($conexion->error);

if (!($usuario['id_rol'] == 5)) {
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

    <style>
        .content {
            padding: 20px;
        }

        .card {
            transition: all 0.3s ease !important;
            border: none !important;
            overflow: hidden;
        margin-bottom: 30px !important; 
        }

        .card:hover {
            transform: translateY(-10px) !important;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15) !important;
        }

        .card a {
            text-decoration: none !important;
        }

        .card .fa {
            transition: all 0.3s ease;
        }

        .card:hover .fa {
            transform: scale(1.1);
        }

        .card:hover div[style*="background"] {
            transform: scale(1.05);
        }

        /* Responsividad mejorada */
        @media (max-width: 768px) {
            .col-lg-4 {
                margin-bottom: 20px;
            }
            
            .card h4 {
                font-size: 1.1rem !important;
            }
            
            div[style*="width: 120px"] {
                width: 100px !important;
                height: 100px !important;
            }
            
            .fa {
                font-size: 36px !important;
            }
        }

        @media (max-width: 576px) {
            .card h4 {
                font-size: 1rem !important;
            }
            
            div[style*="width: 120px"] {
                width: 80px !important;
                height: 80px !important;
            }
            
            .fa {
                font-size: 28px !important;
            }
        }

        /* Animaciones suaves */
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

        .card {
            animation: fadeInUp 0.6s ease-out;
        }

        .card:nth-child(1) { animation-delay: 0.1s; }
        .card:nth-child(2) { animation-delay: 0.2s; }
        .card:nth-child(3) { animation-delay: 0.3s; }
        .card:nth-child(4) { animation-delay: 0.4s; }
        .card:nth-child(5) { animation-delay: 0.5s; }
        .card:nth-child(6) { animation-delay: 0.6s; }

        /* Mejoras para el breadcrumb */
        .breadcrumb {
            background: linear-gradient(135deg, #2b2d7f 0%, #2b2d7f 100%);
            border: none;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 30px;
        }

        .breadcrumb li {
            color: white !important;
        }

        .breadcrumb h4 {
            color: white !important;
            margin: 0;
            font-weight: 300;
            letter-spacing: 1px;
        }
    </style>
</head>

<body class=" hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <!-- <img src="dist/img/logo.jpg" alt="logo">-->
<?php
    if ($ejecutivo != '429'){?>    
         <a href="menu_gerencia.php" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>SI</b>MA</span>
            <!-- logo for regular state and mobile devices -->
<?php
$resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
while($f = mysqli_fetch_array($resultado)){
       $id_simg=$f['id_simg'];
?>
            <center><span class="fondo"><img src="../configuracion/admin/img/<?php echo $f['img_base']?>" alt="imgsistema" id="meddi" class="img-fluid" width="112"></span></center>
          <?php
}
?>
        </a>
        <?php } ?> 
        
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top gggg" role="navigation">
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
                    <a href="../gerencia/editar_perfil/editar_perfil.php?id_usua=<?php echo $usuario['id_usua'];?>" class="btn btn-default btn-flat">MIS DATOS</a>
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
                      <i class="fa fa-folder"></i> <span><strong>MENÚ GERENTE GENERAL</strong></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
              
                        <li><a href="../template/menu_administrativo.php"><i class="fa fa-folder"></i> ADMINISTRATIVO</a></li>
                        <li><a href="../template/menu_enfermera.php"><i class="fa fa-heart"></i> ENFERMERÍA</a></li>
                        <li><a href="../template/menu_medico.php"><i class="fa fa-stethoscope"></i> MÉDICO</a></li>
                        <li><a href="../template/menu_laboratorio.php"><i class="fa fa-circle"></i> ESTUDIOS</a></li> 
                        <li><a href="../template/menu_sauxiliares.php"><i class="fa fa-circle"></i> ALMACENES</a></li>
                        <li><a href="../template/menu_configuracion.php"><i class="fa fa-folder"></i> CONFIGURACIÓN</a></li>

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
                        <h4>GERENCIA</h4>
                    </STRONG></li>
            </ol>
        </nav>

        <!-- Main content -->
        <section class="content">
            <!-- CONTENIDO -->
            <section class="content container-fluid">
                <div class="content box">
                    <!-- CONTENIDO -->
                    <div class="row">
                        <!-- ADMINISTRATIVO -->
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card text-center h-100 shadow-sm" style="border: none; border-radius: 15px; background: linear-gradient(135deg, #E8F4FD 0%, #E1F5FE 100%); transition: all 0.3s ease;">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    <div style="margin-bottom: 20px;">
                                        <a href="../template/menu_administrativo.php" title="Administrativo" style="text-decoration: none;">
                                            <div style="background: #B3E5FC; width: 120px; height: 120px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); transition: all 0.3s ease;">
                                                <i class="fa fa-briefcase" style="font-size: 48px; color: #0277BD;"></i>
                                            </div>
                                            <h4 style="color: #0277BD; font-weight: 600; margin: 0;">ADMINISTRATIVO</h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ENFERMERÍA -->
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card text-center h-100 shadow-sm" style="border: none; border-radius: 15px; background: linear-gradient(135deg, #F3E5F5 0%, #FCE4EC 100%); transition: all 0.3s ease;">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    <div style="margin-bottom: 20px;">
                                        <a href="../template/menu_enfermera.php" title="Enfermería" style="text-decoration: none;">
                                            <div style="background: #F8BBD9; width: 120px; height: 120px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); transition: all 0.3s ease;">
                                                <i class="fa fa-heart" style="font-size: 48px; color: #C2185B;"></i>
                                            </div>
                                            <h4 style="color: #C2185B; font-weight: 600; margin: 0;">ENFERMERÍA</h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- MÉDICO -->
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card text-center h-100 shadow-sm" style="border: none; border-radius: 15px; background: linear-gradient(135deg, #E8F5E8 0%, #F1F8E9 100%); transition: all 0.3s ease;">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    <div style="margin-bottom: 20px;">
                                        <a href="../template/menu_medico.php" title="Médico" style="text-decoration: none;">
                                            <div style="background: #C8E6C9; width: 120px; height: 120px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); transition: all 0.3s ease;">
                                                <i class="fa fa-stethoscope" style="font-size: 48px; color: #388E3C;"></i>
                                            </div>
                                            <h4 style="color: #388E3C; font-weight: 600; margin: 0;">MÉDICO</h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ESTUDIOS -->
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card text-center h-100 shadow-sm" style="border: none; border-radius: 15px; background: linear-gradient(135deg, #FFF3E0 0%, #FFECB3 100%); transition: all 0.3s ease;">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    <div style="margin-bottom: 20px;">
                                        <a href="../template/menu_laboratorio.php" title="Estudios" style="text-decoration: none;">
                                            <div style="background: #FFE0B2; width: 120px; height: 120px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); transition: all 0.3s ease;">
                                                <i class="fa fa-flask" style="font-size: 48px; color: #F57C00;"></i>
                                            </div>
                                            <h4 style="color: #F57C00; font-weight: 600; margin: 0;">ESTUDIOS</h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ALMACENES -->
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card text-center h-100 shadow-sm" style="border: none; border-radius: 15px; background: linear-gradient(135deg, #E3F2FD 0%, #E1F5FE 100%); transition: all 0.3s ease;">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    <div style="margin-bottom: 20px;">
                                        <a href="../template/menu_sauxiliares.php" title="Almacenes" style="text-decoration: none;">
                                            <div style="background: #BBDEFB; width: 120px; height: 120px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); transition: all 0.3s ease;">
                                                <i class="fa fa-cubes" style="font-size: 48px; color: #1976D2;"></i>
                                            </div>
                                            <h4 style="color: #1976D2; font-weight: 600; margin: 0;">ALMACENES</h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CONFIGURACIÓN -->
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card text-center h-100 shadow-sm" style="border: none; border-radius: 15px; background: linear-gradient(135deg, #F3E5F5 0%, #E8EAF6 100%); transition: all 0.3s ease;">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    <div style="margin-bottom: 20px;">
                                        <a href="../template/menu_configuracion.php" title="Configuración" style="text-decoration: none;">
                                            <div style="background: #D1C4E9; width: 120px; height: 120px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); transition: all 0.3s ease;">
                                                <i class="fa fa-cogs" style="font-size: 48px; color: #7B1FA2;"></i>
                                            </div>
                                            <h4 style="color: #7B1FA2; font-weight: 600; margin: 0;">CONFIGURACIÓN</h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    


                        
                        <?php
                        if ($usuario['id_usua'] == 1   || $usuario['id_usua'] == 200 || $usuario['id_usua'] == 221 || $usuario['id_usua'] == 429 || $usuario['id_usua'] == 266 || $usuario['id_usua'] == 393 || $usuario['id_usua'] == 437) {
                        ?>
                       
                        
                        <?php
                        }
                        ?>
                        
                        
                    </div>

            </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

         <!-- Modal Servicios Auxiliares Modernizado--> 
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="border-radius: 15px; border: none;">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title" style="color: white; font-weight: 600;">
                        <i class="fa fa-hospital-o"></i> Servicios Auxiliares
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 30px;">
                    <div class="row">
                        <!-- ALMACENES -->
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="card text-center h-100 shadow-sm" style="border: none; border-radius: 15px; background: linear-gradient(135deg, #E3F2FD 0%, #E1F5FE 100%); transition: all 0.3s ease;">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    <div style="margin-bottom: 20px;">
                                        <a href="../template/menu_sauxiliares.php" title="Almacenes" style="text-decoration: none;">
                                            <div style="background: #BBDEFB; width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); transition: all 0.3s ease;">
                                                <i class="fa fa-cubes" style="font-size: 40px; color: #1976D2;"></i>
                                            </div>
                                            <h5 style="color: #1976D2; font-weight: 600; margin: 0;">ALMACENES</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- IMAGENOLOGÍA -->
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="card text-center h-100 shadow-sm" style="border: none; border-radius: 15px; background: linear-gradient(135deg, #E8F5E8 0%, #F1F8E9 100%); transition: all 0.3s ease;">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    <div style="margin-bottom: 20px;">
                                        <a href="../template/menu_imagenologia.php" title="Imagenología" style="text-decoration: none;">
                                            <div style="background: #C8E6C9; width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); transition: all 0.3s ease;">
                                                <i class="fa fa-search" style="font-size: 40px; color: #388E3C;"></i>
                                            </div>
                                            <h5 style="color: #388E3C; font-weight: 600; margin: 0;">IMAGENOLOGÍA</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- LABORATORIO -->
                        <div class="col-lg-12 col-md-12 col-sm-12 mb-2">
                            <div class="card text-center h-100 shadow-sm" style="border: none; border-radius: 15px; background: linear-gradient(135deg, #FFF3E0 0%, #FFECB3 100%); transition: all 0.3s ease;">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    <div style="margin-bottom: 20px;">
                                        <a href="../template/menu_laboratorio.php" title="Laboratorio" style="text-decoration: none;">
                                            <div style="background: #FFE0B2; width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); transition: all 0.3s ease;">
                                                <i class="fa fa-flask" style="font-size: 40px; color: #F57C00;"></i>
                                            </div>
                                            <h5 style="color: #F57C00; font-weight: 600; margin: 0;">LABORATORIO</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border: none; padding: 20px;">
                    <button type="button" class="btn" data-dismiss="modal" style="background: #6c757d; color: white; border-radius: 25px; padding: 8px 25px;">
                        <i class="fa fa-times"></i> CERRAR
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="main-footer footer">
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