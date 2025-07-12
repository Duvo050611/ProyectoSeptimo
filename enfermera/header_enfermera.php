<?php
include "../../conexionbd.php";
//session_start();
//
if (!isset($_SESSION['login'])) {
    // remove all session variables
    session_unset();
    // destroy the session
    session_destroy();
    //  header('Location: ../index.php');
}else{
    $lifetime=11000;
    session_set_cookie_params($lifetime);
}
$usuario = $_SESSION['login'];
//$resultado = $conexion->query("SELECT * FROM reg_usuarios WHERE id_usua='" . $usuario . "'") or die($conexion->error);

if (!($usuario['id_rol'] == 3 || $usuario['id_rol'] == 5 || $usuario['id_rol'] == 12 || $usuario['id_rol'] == 1)) {

    session_unset();
    session_destroy();
    // echo "<script>window.Location='../../index.php';</script>";
    header('Location: ../../index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
     <meta charset="UTF-8">
  <title>INEO Metepec</title>
  <link rel="icon" href="../imagenes/SIF.PNG">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="../../template/dist/js/pages/dashboard2.js" type="text/javascript"></script>

    <!-- AdminLTE for demo purposes -->
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
    <!-- AdminLTE Skins. Choose a skin from the css/skins
           folder instead of downloading all of them to reduce the load. -->
    <link href="../../template/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

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
    <!-- SlimScroll 1.3.0 -->
    <script src="../../template/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- ChartJS 1.0.1 -->
    <script src="../../template/plugins/chartjs/Chart.min.js" type="text/javascript"></script>

        <script src="https://kit.fontawesome.com/e547be4475.js" crossorigin="anonymous"></script>


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
                    <span class="logo-mini"><b>SI</b>MA</span>
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
                    <span class="logo-mini"><b>SI</b>MA</span>
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
                    <span class="logo-mini"><b>SI</b>MA</span>
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
              <li><a href="../registro_quirurgico/enf_cirugia_segura.php"><i class="fa-solid fa-file-waveform"></i> <span>HOJA PROGRAMACIÓN<br> QUIRÚRGICA</span></a></li>
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
                    <li class="breadcrumb-item active" aria-current="page"><STRONG>
                            <h4>ENFERMERÍA</h4>
                        </STRONG></li>
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