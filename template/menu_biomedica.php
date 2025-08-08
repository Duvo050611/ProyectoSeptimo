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
if (!($usuario['id_rol'] == 5 || $usuario['id_rol'] == 15)) {
    session_unset();
    session_destroy();
    // echo "<script>window.Location='../index.php';</script>";
    header('Location: ../index.php');
}

?>

<!DOCTYPE html>
<html>

<head>
    
    <title>MÉDICA SAN ISIDRO</title>
        <link rel="icon" href="../imagenes/SIF.PNG">
    
    <!-- Bootstrap 3.3.2 -->
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
    <!-- AdminLTE Skins. Choose a skin from the css/skins
           folder instead of downloading all of them to reduce the load. -->
    <link href="dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

    <style>
        .dropdwn {
            float: left;
            overflow: hidden;
        }

        .dropdwn .dropbtn {
            cursor: pointer;
            font-size: 10px;
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
            max-width: 10000px;
            height: auto;
            display: flex;
            overflow-y: scroll;
            column-gap: 0.5em;
            column-rule: 10px solid white;
            column-width: 100px;
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

.alert{
         padding-right: 40px;
         padding-left:6px;
     }
.nod{

        font-size: 10.3px;
     }
        @media screen and (max-width: 980px){
    .container{
        width:610px;
       margin-left:-20px;
      
        
    }
     .alert{
         padding-right: 38px;
         padding-left: 10px;
     }
     .nompac{
         margin-left:-3px;
        font-size: 10px;

     }
     .nod{
        font-size: 7px;
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
            if ($usuario['id_rol'] == 3) {
            ?>

                <a href="menu_enfermera.php" class="logo">
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
            } else if ($usuario['id_rol'] == 5) {

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
            <?php }elseif($usuario['id_rol'] == 12) { ?>
         <a href="menu_residente.php" class="logo">
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
         <?php }elseif($usuario['id_rol'] == 1) { ?>
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
            }elseif($usuario['id_rol'] == 14) { ?>
         <a href="menu_mantenimiento.php" class="logo">
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
            }elseif($usuario['id_rol'] == 15) { ?>
         <a href="menu_biomedica.php" class="logo">
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
                                <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="user-image" alt="User Image" />

                                <span class="hidden-xs"> <?php echo $usuario['papell']; ?> </span>

                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle" alt="User Image" />
                                    <p>
                                    <?php echo $usuario['papell']; ?>
                                    </p>
                                </li>

                                <!-- Menu Footer-->
                                <li class="user-footer">
                  <div class="pull-left">
                    <a href="../biomedica/editar_perfil.php?id_usua=<?php echo $usuario['id_usua'];?>" class="btn btn-default btn-flat">MIS DATOS</a>
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
                        <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle" alt="User Image" />
                    </div>
                    <div class="pull-left info">
                        <p> <?php echo $usuario['papell']; ?></p>

                        <a href="#"><i class="fa fa-circle text-success"></i> ACTIVO</a>
                    </div>
                </div>

<?php  
if (isset($_SESSION['pac']) && ($usuario['id_rol']== 5 || $usuario['id_rol']== 3)) {
?>
<ul class="sidebar-menu">

            <li class="treeview">
                        <a href="../enfermera/pdf/vista_pdf.php"> 
                          <i class="fa fa-print" aria-hidden="true"></i><span>IMPRIMIR DOCUMENTOS</span>
                        </a>
            </li>   
            <li class=" treeview">
              <a href="#">
                <i class="fa fa-bed"></i><font size ="2"><span>GESTIÓN DE CAMAS</span></font><i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                   <li class="treeview">
                       <a href="../enfermera/censo/vista_habitacion.php">
                          <i class="fa fa-bed" aria-hidden="true"></i><span>ASIGNAR HABITACIÓN</span>
                       </a>
                   </li>
                    
                   <li class="treeview">
                       <a href="../enfermera/censo/cambio_habitacion.php">
                          <i class="fa fa-medkit" aria-hidden="true"></i><span>CAMBIO DE HABITACIÓN</span>
                       </a>
                   </li>
            </ul>
        </li>    
        <li class="treeview">
                        <a href="../enfermera/ordenes_medico/vista_ordenes.php">
                            <i class="fa fa-stethoscope"></i> <span>INDICACIONES DEL MÉDICO</span>
                        </a>
        </li>
        <li class=" treeview">
            <a href="#">
              <i class="fa fa-folder"></i> <span>REGISTRO CLÍNICO</span><i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="../enfermera/registro_clinico/reg_clin.php"><i class="fa fa-folder"></i> <span>HOSPITALIZACIÓN</span></a></li>
               <li><a href="../enfermera/registro_quirurgico/enf_cirugia_segura.php"><i class="fa fa-folder"></i> <span>CIRUGÍA SEGURA</span></a></li>
              <li><a href="../enfermera/registro_quirurgico/vista_enf_quirurgico.php"><i class="fa fa-folder"></i> <span>QUIRÓFANO</span></a></li>
              <li><a href="../enfermera/registro_terapeutico/reg_terapeutico.php"><i class="fa fa-folder"></i> <span>TERAPIA INTENSIVA</span></a></li>
              <li><a href="../enfermera/registro_urgencias/reg_urg.php"><i class="fa fa-folder"></i> <span>URGENCIAS</span></a></li>

              <li><a href="../enfermera/registro_clinico_neonatal/nota_bebes.php"><i class="fa fa-folder"></i> <span>NEONATAL </span></a></li>
              <li><a href="../enfermera/transfucion_de_sangre/nota_trasfusion_new.php"><i class="fa fa-folder"></i> <span>TRANSFUSIONES<br>SANGUÍNEAS</span></a></li>
            </ul>
        </li>   
                    
            <li class="treeview">
                <a href="select_pac_enf.php">
                  <i class="fa fa-heartbeat" aria-hidden="true"></i> <span>SIGNOS VITALES</span>
                </a>
            </li>
            <li class="treeview">
                <a href="select_pac_enf.php">
                  <i class="fa fa-medkit" aria-hidden="true"></i><span>REGISTRO DE MEDICAMENTOS</span>
                </a>
            </li>
            <li class="treeview">
                <a href="select_pac_enf.php">
                  <i class="fa fa-medkit" aria-hidden="true"></i><span>REGISTRO DE SOLUCIONES</span>
                </a>
            </li><!--
            <li class="treeview">
                <a href="select_pac_enf.php">
                  <i class="fa fa-medkit" aria-hidden="true"></i><span>VALORACIÓN DEL DOLOR</span>
                </a>
            </li>-->
            <li class="treeview">
                        <a href="../enfermera/medicamentos/solmed_far.php">
                            <i class="fa fa-medkit" aria-hidden="true"></i> <span>SOLICITAR MEDICAMENTOS<br>A FARMACIA</span>
                        </a>
            </li>
            <!--<li class="treeview">
                        <a href="../enfermera/medicamentos/order_copia_enf.php">
                            <i class="fa fa-medkit" aria-hidden="true"></i> <span>SOLICITAR MEDICAMENTOS<br>A FARMACIA (AMBULATORIO)</span>
                        </a>
            </li>-->
           
        <li class=" treeview">
            <a href="#">
              <i class="fa fa-folder"></i><font size ="2"><span>VALES DE MEDICAMENTOS</span></font><i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li class="treeview">
                       <a href="../enfermera/vales_farmacia/salidas.php">
                          <i class="fa fa-medkit" aria-hidden="true"></i><span>VALES DE MEDICAMENTOS<br> FARMACIA</span>
                       </a>
                   </li>
                    
                   <li class="treeview">
                       <a href="../enfermera/vales_ceye/salidas.php">
                          <i class="fa fa-medkit" aria-hidden="true"></i><span>VALES DE MEDICAMENTOS<br> CEYE (QUIRÓFANO)</span>
                       </a>
                   </li>
            </ul>
        </li> 
        
                   
                  </ul>
<?php
}elseif(!isset($_SESSION['pac']) && ($usuario['id_rol']== 5 || $usuario['id_rol']== 3)){
?>
<ul class="sidebar-menu">
     <li class="treeview">
                       <a href="select_pac_enf.php">
                        <i class="fa fa-print" aria-hidden="true"></i> <span>IMPRIMIR DOCUMENTOS</span>
                       </a>
     </li>
     <li class=" treeview">
              <a href="#">
                <i class="fa fa-bed"></i><font size ="2"><span>GESTIÓN DE CAMAS</span></font><i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                   <li class="treeview">
                       <a href="../enfermera/censo/vista_habitacion.php">
                          <i class="fa fa-bed" aria-hidden="true"></i><span>ASIGNAR HABITACIÓN</span>
                       </a>
                   </li>
                    
                   <li class="treeview">
                       <a href="../enfermera/censo/cambio_habitacion.php">
                          <i class="fa fa-medkit" aria-hidden="true"></i><span>CAMBIO DE HABITACIÓN</span>
                       </a>
                   </li>
            </ul>
        </li>                  
    <li class="treeview">
                        <a href="select_pac_enf.php">
                            <i class="fa fa-stethoscope"></i> <span>INDICACIONES DEL MÉDICO</span>
                        </a>
                    </li>
        <li class=" treeview">
            <a href="#">
              <i class="fa fa-folder"></i> <span>REGISTRO CLÍNICO</span><i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="select_pac_enf.php"><i class="fa fa-folder"></i> <span>HOSPITALIZACIÓN</span></a></li>
              <li><a href="select_pac_enf.php"><i class="fa fa-folder"></i> <span>CIRUGÍA SEGURA</span></a></li>
              <li><a href="select_pac_enf.php"><i class="fa fa-folder"></i> <span>QUIRÓFANO</span></a></li>
              <li><a href="select_pac_enf.php"><i class="fa fa-folder"></i> <span>TERAPIA INTENSIVA</span></a></li>
            <li><a href="select_pac_enf.php"><i class="fa fa-folder"></i> <span>URGENCIAS </span></a></li>

              <li><a href="select_pac_enf.php"><i class="fa fa-folder"></i> <span>NEONATAL </span></a></li>
              <li><a href="select_pac_enf.php"><i class="fa fa-folder"></i> <span>TRANSFUSIONES<br>SANGUÍNEAS</span></a></li>
            </ul>
          </li>
                
                <li class="treeview">
                <a href="../enfermera/signos_vitales/signos.php">
                <i class="fa fa-heartbeat" aria-hidden="true"></i> <span>SIGNOS VITALES</span>
                </a>
                </li>
                 <li class="treeview">
                <a href="../enfermera/medicamentos/medicamentos.php">
                 <i class="fa fa-medkit" aria-hidden="true"></i><span>REGISTRO DE MEDICAMENTOS</span>
                </a>
                </li>
                <li class="treeview">
                        <a href="../enfermera/medicamentos/solmed_far.php">
                            <i class="fa fa-medkit" aria-hidden="true"></i> <span>SOLICITAR MEDICAMENTOS<br>A FARMACIA</span>
                        </a>
                </li>
                <!--<li class="treeview">
                        <a href="../enfermera/medicamentos/order_copia_enf.php">
                            <i class="fa fa-medkit" aria-hidden="true"></i> <span>SOLICITAR MEDICAMENTOS<br>A FARMACIA (AMBULATORIO)</span>
                        </a>
                </li>  -->
                
               
                   <li class=" treeview">
            <a href="#">
              <i class="fa fa-folder"></i><font size ="2"><span>VALES DE MEDICAMENTOS</span></font><i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li class="treeview">
                       <a href="../enfermera/vales_farmacia/salidas.php">
                          <i class="fa fa-medkit" aria-hidden="true"></i><span>VALES DE MEDICAMENTOS<br> FARMACIA</span>
                       </a>
                   </li>
                    
                  <li class="treeview">
                       <a href="../enfermera/vales_ceye/salidas.php">
                          <i class="fa fa-medkit" aria-hidden="true"></i><span>VALES DE MEDICAMENTOS<br> CEYE (QUIRÓFANO)</span>
                       </a>
                   </li>
            </ul>
        </li>
                    
                   

                  </ul>
<?php
}
?>
<?php  
if (isset($_SESSION['pac']) && $usuario['id_rol']== 12) {
?>
<ul class="sidebar-menu">
                    <li class="treeview">
                        <a href="menu_enfermera.php">
                            <i class="fa fa-bed" aria-hidden="true"></i> <span><font size ="2"> SELECCIONAR PACIENTE </font></span>

                        </a>

                    </li> 
                    <li class="treeview">
                        <a href="../enfermera/ordenes_medico/vista_ordenes.php">
                            <i class="fa fa-stethoscope"></i> <span>INDICACIONES DEL MÉDICO</span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="../enfermera/medicamentos/order_copia_enf.php">
                            <i class="fa fa-medkit" aria-hidden="true"></i> <span>SOLICITAR MEDICAMENTOS<br>A FARMACIA</span>
                        </a>
                    </li>
                  </ul>
<?php
}elseif(!isset($_SESSION['pac']) && $usuario['id_rol']== 12){
?>
<ul class="sidebar-menu">
                   <li class="treeview">
                        <a href="menu_enfermera.php">
                            <i class="fa fa-bed" aria-hidden="true"></i><span><font size ="2"> SELECCIONAR PACIENTE </font></span>

                        </a>

                    </li> 
                    <li class="treeview">
                        <a href="select_pac_enf.php">
                            <i class="fa fa-stethoscope"></i> <span>INDICACIONES DEL MÉDICO</span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="../enfermera/medicamentos/order_copia_enf.php">
                           <i class="fa fa-medkit" aria-hidden="true"></i> <span>SOLICITAR MEDICAMENTOS<br>A FARMACIA</span>
                        </a>
                    </li>
                    
                  </ul>
<?php
}
?>
<?php  
if (isset($_SESSION['pac']) && $usuario['id_rol']== 1) {
?>
<ul class="sidebar-menu">
                    <li class="treeview">
                        <a href="menu_enfermera.php">
                            <i class="fa fa-bed" aria-hidden="true"></i> <span><font size ="2"> SELECCIONAR PACIENTE </font></span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="../enfermera/ordenes_medico/vista_ordenes.php">
                            <i class="fa fa-stethoscope"></i> <span>INDICACIONES DEL MÉDICO</span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="../enfermera/medicamentos/order_copia_enf.php">
                            <i class="fa fa-medkit" aria-hidden="true"></i> <span>SOLICITAR MEDICAMENTOS<br>A FARMACIA</span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="../enfermera/registro_quirurgico/nav_med.php">
                            <i class="fa fa-folder"></i><font size ="2"><span>MEDICAMENTOS CEYE / FARM<br> HOSPITALIZADOS</span></font>
                        </a>
                    </li>
                  </ul>
<?php
}elseif(!isset($_SESSION['pac']) && $usuario['id_rol']== 1){
?>
<ul class="sidebar-menu">
                   <li class="treeview">
                        <a href="menu_enfermera.php">
                            <i class="fa fa-bed" aria-hidden="true"></i><span><font size ="2"> SELECCIONAR PACIENTE </font></span>
                        </a>
                    </li> 
                    <li class="treeview">
                        <a href="../enfermera/ordenes_medico/vista_ordenes.php">
                            <i class="fa fa-stethoscope"></i> <span>INDICACIONES DEL MÉDICO</span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="../enfermera/medicamentos/order_copia_enf.php">
                            <i class="fa fa-medkit" aria-hidden="true"></i> <span>SOLICITAR MEDICAMENTOS<br>A FARMACIA</span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="select_pac_enf.php">
                            <i class="fa fa-folder"></i><font size ="2"><span>MEDICAMENTOS CEYE / FARM<br> HOSPITALIZADOS</span></font>
                        </a>
                    </li>
                  </ul>
<?php
}
?>


                <!-- sidebar menu: : style can be found in sidebar.less -->
                
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
              <h4>BIOMÉDICA</h4>
            </STRONG></li>
        </ol>
      </nav>

<section class="content container-fluid">
    <!--<div class="container">
        <div class="row">
         
            <div class="col-sm-4">
              <a href="../enfermera/censo/tabla_censo.php"><button type="button" class="btn btn-warning">VER CENSO <i class="fa fa-bed"></i></button></a>
            </div>
            <div class="col-sm-4">
                <a href="../enfermera/selectpac_sincama/select_pac.php"><button type="button" class="btn btn-success">SELECCIONAR OTROS PACIENTES <i class="fa fa-user"></i></button></a>
            </div>
        </div>
    </div>-->

<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
  <center><strong>HOSPITALIZACIÓN</strong></center><p>
</div> 
<div class="container box col-12">
        <div class= "row">
        
        <?php
        $sql = 'SELECT * from cat_camas where piso=1 and seccion=1 ORDER BY num_cama ASC';
        $result = $conexion->query($sql);
        //  $id_atencion = $_GET['id_atencion'];
        while ($row = $result->fetch_assoc()) {
          $num_cama = $row['num_cama'];
          $id_atencion = $row['id_atencion'];
          $estaus = $row['estatus'];
          $biomedica = $row['biomedica'];
          $mantenimiento = $row['mantenimiento'];
          $serv_generales = $row['serv_generales'];
            $intendencia = $row['intendencia'];
          if ($estaus == "LIBRE" or $estaus == "Libre") {
        ?>
            <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-success" role="alert">
                <a href="#" class="small-box-footer"><i style="font-size:25px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2"><?php echo $estaus ?></font></h7>
                <br>
                <br>
              </div>
            </div>
          <?php
          } elseif ($estaus == "MANTENIMIENTO" or $estaus == "Mantenimiento") {
             $esta = "NO DISPONIBLE";
          ?>
            <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-danger" role="alert">
                <a href="#" class="small-box-footer"><i style="font-size:23px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2" class="nod"><?php echo $esta ?></font></h7>
            
                <br>
              </div>
            </div>
          <?php
          }elseif ($biomedica=="No liberada" and $mantenimiento=="No liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica">
               <img src="../img/intendencia.png" width="10" class="img-fluid" title="Intendencia">
                <img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="No liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color: #9A0C71; color:white;">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
              <img src="../img/intendencia.png" width="10" class="img-fluid" title="Intendencia">
            
                <img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="Liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color: #9A0C71; color:white;">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/intendencia.png" width="16" class="img-fluid" title="Intendencia">
             
               
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="No liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="16" class="img-fluid" title="Biomedica">
                <img src="../img/manten.jpg" width="16" class="img-fluid" title="Mantenimiento">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="No liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" style="background-color: #9A0C71; color:white;" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
             
                <img src="../img/manten.jpg" width="20" class="img-fluid" title="Mantenimiento">
                <br>
                <br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="No liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica">
             
                <img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">
                <img src="../img/intendencia.png" width="16" class="img-fluid" title="Intendencia">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="No liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" style="background-color: #9A0C71; color:white;" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               
             <img src="../img/uu.png" width="16" class="img-fluid" title="Servicios generales">
                <img src="../img/intendencia.png" width="16" class="img-fluid" title="Intendencia">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="Liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" style="background-color: #9A0C71; color:white;" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
              <img src="../img/intendencia.png" width="16" class="img-fluid" title="Intendencia">
               <br><br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="Liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="19" class="img-fluid" title="Biomedica">
             <br><br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="Liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica"><img src="../img/intendencia.png" width="10" class="img-fluid" title="Intendencia">
             
               
              </div>
            </div>
<?php

          } elseif ($biomedica=="Liberada" and $mantenimiento=="No liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert " style="background-color: #9A0C71; color:white;"role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
           
             
                <img src="../img/manten.jpg" width="15" class="img-fluid" title="Mantenimiento">
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="Liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="15" class="img-fluid" title="Biomedica">
               <br><br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="Liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color: #9A0C71; color:white;">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
              <!--  <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica">--><img src="../img/intendencia.png" width="15" class="img-fluid" title="Intendencia">
             
                <!--<img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">--><br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="Liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color: #9A0C71; color:white;">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
              <!--  <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica">-->
             <!--<img src="../img/uu.png" width="15" class="img-fluid" title="Servicios generales">-->
                <!--<img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">--><br><br>
              </div>
            </div>
<?php

          }elseif ($estaus=="EN PROCESO DE LIBERA"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="1"><?php echo $pr ?></font></h7>
            
                <br>
              </div>
            </div>
<?php

          }else{
          ?>
          <a href="#" class="small-box-footer">
            <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert" role="alert" style="background-color: #2b2d7f; color:white;">

                <i style="font-size:25px;" class="fa fa-bed"></i>
                
                <h7><font size="3"><?php echo $num_cama ?></font></h7><br>
              <!--  <h7>Estatus: OCUPADA</h7>-->
                <?php
                $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac = $row_cam['nom_pac'] . ' ' . $row_cam['papell'];
                }
                ?>
                <font size="2" class="nompac"><?php echo $nombre_pac ?></font>
                <br>
                
              </div>
            </div></a>
        <?php
          }
        }
        ?>      

        <?php
        $sql = 'SELECT * from cat_camas where piso=1 and seccion=2 ORDER BY num_cama ASC';
        $result = $conexion->query($sql);
        //  $id_atencion = $_GET['id_atencion'];
        while ($row = $result->fetch_assoc()) {
          $num_cama = $row['num_cama'];
          $id_atencion = $row['id_atencion'];
          $estaus = $row['estatus'];
          $biomedica = $row['biomedica'];
          $mantenimiento = $row['mantenimiento'];
          $serv_generales = $row['serv_generales'];
            $intendencia = $row['intendencia'];
          if ($estaus == "LIBRE" or $estaus == "Libre") {
        ?>
         <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-success" role="alert">
                <a href="#" class="small-box-footer"><i style="font-size:25px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2"><?php echo $estaus ?></font></h7>
                <br>
                <br>
              </div>
            </div>
          <?php
          } elseif ($estaus == "MANTENIMIENTO" or $estaus == "Mantenimiento") {
             $esta = "NO DISPONIBLE";
          ?>
            <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-danger" role="alert">
                <a href="#" class="small-box-footer"><i style="font-size:20px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2" class="nod"><?php echo $esta ?></font></h7>
                <br>
          
              </div>
            </div>
            <?php
          } else {
          ?>
            <a href="#" class="small-box-footer">
            <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-danger" role="alert">

                <i style="font-size:25px;" class="fa fa-bed"></i>
                
                <h7><font size="3"><?php echo $num_cama ?></font></h7><br>
              <!--  <h7>Estatus: OCUPADA</h7>-->
                <?php
                $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac = $row_cam['nom_pac'] . ' ' . $row_cam['papell'];
                }
                ?>
                <font size="2" class="nompac"><?php echo $nombre_pac ?></font>
                <br />
                
              </div>
            </div></a>
        <?php
          }
        }
        ?>
</div>
</div>
       
<div class="container box col-12">
  <div class= "row">
        <?php
        $sql = 'SELECT * from cat_camas where piso=2 and seccion=1 ORDER BY num_cama ASC';
        $result = $conexion->query($sql);
        //  $id_atencion = $_GET['id_atencion'];
        while ($row = $result->fetch_assoc()) {
          $num_cama = $row['num_cama'];
          $id_atencion = $row['id_atencion'];
          $estaus = $row['estatus'];
          $biomedica = $row['biomedica'];
          $mantenimiento = $row['mantenimiento'];
          $serv_generales = $row['serv_generales'];
            $intendencia = $row['intendencia'];
          if ($estaus == "LIBRE" or $estaus == "Libre") {
        ?>
             <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-success" role="alert">
                <a href="#" class="small-box-footer"><i style="font-size:25px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2"><?php echo $estaus ?></font></h7>
                <br>
                <br>
              </div>
            </div>
          <?php
          } elseif ($estaus == "MANTENIMIENTO" or $estaus == "Mantenimiento") {
             $esta = "NO DISPONIBLE";
          ?>
            <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-danger" role="alert">
                <a href="#" class="small-box-footer"><i style="font-size:23px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2" class="nod"><?php echo $esta ?></font></h7>
                <br>
              
              </div>
            </div>
          <?php
          } elseif ($biomedica=="No liberada" and $mantenimiento=="No liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica">
               <img src="../img/intendencia.png" width="10" class="img-fluid" title="Intendencia">
                <img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="No liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color: #9A0C71; color:white;">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
              <img src="../img/intendencia.png" width="10" class="img-fluid" title="Intendencia">
            
                <img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="Liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color: #9A0C71; color:white;">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/intendencia.png" width="16" class="img-fluid" title="Intendencia">
             
               
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="No liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="16" class="img-fluid" title="Biomedica">
                <img src="../img/manten.jpg" width="16" class="img-fluid" title="Mantenimiento">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="No liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" style="background-color: #9A0C71; color:white;" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
             
                <img src="../img/manten.jpg" width="20" class="img-fluid" title="Mantenimiento">
                <br>
                <br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="No liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica">
             
                <img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">
                <img src="../img/intendencia.png" width="16" class="img-fluid" title="Intendencia">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="No liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" style="background-color: #9A0C71; color:white;" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               
             <img src="../img/uu.png" width="16" class="img-fluid" title="Servicios generales">
                <img src="../img/intendencia.png" width="16" class="img-fluid" title="Intendencia">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="Liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" style="background-color: #9A0C71; color:white;" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
              <img src="../img/intendencia.png" width="16" class="img-fluid" title="Intendencia">
               <br><br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="Liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="19" class="img-fluid" title="Biomedica">
             <br><br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="Liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica"><img src="../img/intendencia.png" width="10" class="img-fluid" title="Intendencia">
             
               
              </div>
            </div>
<?php

          } elseif ($biomedica=="Liberada" and $mantenimiento=="No liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert " style="background-color: #9A0C71; color:white;"role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
           
             
                <img src="../img/manten.jpg" width="15" class="img-fluid" title="Mantenimiento">
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="Liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="15" class="img-fluid" title="Biomedica">
               <br><br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="Liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color: #9A0C71; color:white;">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
              <!--  <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica">--><img src="../img/intendencia.png" width="15" class="img-fluid" title="Intendencia">
             
                <!--<img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">--><br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="Liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color: #9A0C71; color:white;">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
              <!--  <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica">-->
             <!--<img src="../img/uu.png" width="15" class="img-fluid" title="Servicios generales">-->
                <!--<img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">--><br><br>
              </div>
            </div>
<?php

          }elseif ($estaus == "EN PROCESO DE LIBERA"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="1"><?php echo $pr ?></font></h7>
            
                <br>
              </div>
            </div>
<?php
          }else{
          ?>
            <a href="#" class="small-box-footer">
            <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert" role="alert" style="background-color: #2b2d7f; color:white;">

                <i style="font-size:25px;" class="fa fa-bed"></i>
                
                <h7><font size="3"><?php echo $num_cama ?></font></h7><br>
               <!--<h7>Estatus: OCUPADA</h7>-->
                <?php
                $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac = $row_cam['nom_pac'] . ' ' . $row_cam['papell'];
                }
                ?>
                <font size="2" class="nompac"><?php echo $nombre_pac ?></font>
                <br />
                
              </div>
            </div></a>
        <?php
          }
        }
        ?>      

        <?php
        $sql = 'SELECT * from cat_camas where piso=2 and seccion=2 ORDER BY num_cama ASC';
        $result = $conexion->query($sql);
        //  $id_atencion = $_GET['id_atencion'];
        while ($row = $result->fetch_assoc()) {
          $num_cama = $row['num_cama'];
          $id_atencion = $row['id_atencion'];
          $estaus = $row['estatus'];
          $biomedica = $row['biomedica'];
          $mantenimiento = $row['mantenimiento'];
          $serv_generales = $row['serv_generales'];
            $intendencia = $row['intendencia'];
          if ($estaus == "LIBRE" or $estaus == "Libre") {
        ?>
             <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-success" role="alert">
                <a href="#" class="small-box-footer"><i style="font-size:25px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2"><?php echo $estaus ?></font></h7>
                <br>
                <br>
              </div>
            </div>
          <?php
          } elseif ($estaus == "MANTENIMIENTO" or $estaus == "Mantenimiento") {
             $esta = "NO DISPONIBLE";
          ?>
            <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-danger" role="alert">
                <a href="#" class="small-box-footer"><i style="font-size:23px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2" class="nod"><?php echo $esta ?></font></h7>
                <br>
           
              </div>
            </div>
          <?php
          } else {
          ?>
            <a href="#" class="small-box-footer">
            <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-danger" role="alert">

                <i style="font-size:25px;" class="fa fa-bed"></i>
                
                <h7><font size="3"><?php echo $num_cama ?></font></h7><br>
              <!--  <h7>Estatus: OCUPADA</h7>-->
                <?php
                $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac = $row_cam['nom_pac'] . ' ' . $row_cam['papell'];
                }
                ?>
                <font size="2" class="nompac"><?php echo $nombre_pac ?></font>
                <br />
                
              </div>
            </div></a>
        <?php
          }
        }
        ?>
</div>
</div>

<div class="container box col-12">
        <div class= "row">

        <?php
        $sql = 'SELECT * from cat_camas where piso=3 and seccion=1 ORDER BY num_cama ASC';
        $result = $conexion->query($sql);
        //  $id_atencion = $_GET['id_atencion'];
        while ($row = $result->fetch_assoc()) {
          $num_cama = $row['num_cama'];
          $id_atencion = $row['id_atencion'];
          $estaus = $row['estatus'];
          $biomedica = $row['biomedica'];
          $mantenimiento = $row['mantenimiento'];
          $serv_generales = $row['serv_generales'];
            $intendencia = $row['intendencia'];
          if ($estaus == "LIBRE" or $estaus == "Libre") {
        ?>
             <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-success" role="alert">
                <a href="#" class="small-box-footer"><i style="font-size:25px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2"><?php echo $estaus ?></font></h7>
                <br>
                <br>
              </div>
            </div>
          <?php
          } elseif ($estaus == "MANTENIMIENTO" or $estaus == "Mantenimiento") {
             $esta = "NO DISPONIBLE";
          ?>
            <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-danger" role="alert">
                <a href="#" class="small-box-footer"><i style="font-size:23px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2" class="nod"><?php echo $esta ?></font></h7>
                <br>
                
              </div>
            </div>
          <?php
          } elseif ($biomedica=="No liberada" and $mantenimiento=="No liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica">
               <img src="../img/intendencia.png" width="10" class="img-fluid" title="Intendencia">
                <img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="No liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color: #9A0C71; color:white;">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
              <img src="../img/intendencia.png" width="10" class="img-fluid" title="Intendencia">
            
                <img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="Liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color: #9A0C71; color:white;">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/intendencia.png" width="16" class="img-fluid" title="Intendencia">
             
               
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="No liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="16" class="img-fluid" title="Biomedica">
                <img src="../img/manten.jpg" width="16" class="img-fluid" title="Mantenimiento">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="No liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" style="background-color: #9A0C71; color:white;" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
             
                <img src="../img/manten.jpg" width="20" class="img-fluid" title="Mantenimiento">
                <br>
                <br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="No liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica">
             
                <img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">
                <img src="../img/intendencia.png" width="16" class="img-fluid" title="Intendencia">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="No liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" style="background-color: #9A0C71; color:white;" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               
             <img src="../img/uu.png" width="16" class="img-fluid" title="Servicios generales">
                <img src="../img/intendencia.png" width="16" class="img-fluid" title="Intendencia">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="Liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" style="background-color: #9A0C71; color:white;" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
              <img src="../img/intendencia.png" width="16" class="img-fluid" title="Intendencia">
               <br><br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="Liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="19" class="img-fluid" title="Biomedica">
             <br><br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="Liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica"><img src="../img/intendencia.png" width="10" class="img-fluid" title="Intendencia">
             
               
              </div>
            </div>
<?php

          } elseif ($biomedica=="Liberada" and $mantenimiento=="No liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert " style="background-color: #9A0C71; color:white;"role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
           
             
                <img src="../img/manten.jpg" width="15" class="img-fluid" title="Mantenimiento">
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="Liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="15" class="img-fluid" title="Biomedica">
               <br><br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="Liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color: #9A0C71; color:white;">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
              <!--  <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica">--><img src="../img/intendencia.png" width="15" class="img-fluid" title="Intendencia">
             
                <!--<img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">--><br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="Liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color: #9A0C71; color:white;">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
              <!--  <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica">-->
             <!--<img src="../img/uu.png" width="15" class="img-fluid" title="Servicios generales">-->
                <!--<img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">--><br><br>
              </div>
            </div>
<?php

          }elseif ($estaus == "EN PROCESO DE LIBERA"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="1"><?php echo $pr ?></font></h7>
            
                <br>
              </div>
            </div>
<?php
          }else{
          ?>
           <a href="#" class="small-box-footer">
            <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert" role="alert" style="background-color: #2b2d7f; color:white;">

                <i style="font-size:25px;" class="fa fa-bed"></i>
                
                <h7><font size="3"><?php echo $num_cama ?></font></h7><br>
              <!--  <h7>Estatus: OCUPADA</h7>-->
                <?php
                $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac = $row_cam['nom_pac'] . ' ' . $row_cam['papell'];
                }
                ?>
                <font size="2" class="nompac"><?php echo $nombre_pac ?></font>
                <br />
                
              </div>
            </div></a>
        <?php
          }
        }
        ?>      


        
 </div>
 </div>             
            

<!-- camas de Terapia -->
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
         <strong><center>TERAPIA INTENSIVA</center></strong>
</div>         
<div class="container box col-12">
        <div class= "row">
        
       <?php
        $sql = 'SELECT * from cat_camas where piso=4 and seccion=1 ORDER BY num_cama ASC';
        $result = $conexion->query($sql);
        //  $id_atencion = $_GET['id_atencion'];
        while ($row = $result->fetch_assoc()) {
          $num_cama = $row['num_cama'];
          $id_atencion = $row['id_atencion'];
          $estaus = $row['estatus'];
          $biomedica = $row['biomedica'];
          $mantenimiento = $row['mantenimiento'];
          $serv_generales = $row['serv_generales'];
            $intendencia = $row['intendencia'];
          if ($estaus == "LIBRE" or $estaus == "Libre") {
        ?>
            <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-success" role="alert">
                <a href="#" class="small-box-footer"><i style="font-size:25px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2"><?php echo $estaus ?></font></h7>
                <br>
                <br>
              </div>
            </div>
          <?php
          } elseif ($estaus == "MANTENIMIENTO" or $estaus == "Mantenimiento") {
             $esta = "NO DISPONIBLE";
          ?>
            <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-danger" role="alert">
                <a href="#" class="small-box-footer"><i style="font-size:23px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2" class="nod"><?php echo $esta ?></font></h7>
                <br>
             
              </div>
            </div>
           <?php
          } elseif ($biomedica=="No liberada" and $mantenimiento=="No liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica">
               <img src="../img/intendencia.png" width="10" class="img-fluid" title="Intendencia">
                <img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="No liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color: #9A0C71; color:white;">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
              <img src="../img/intendencia.png" width="10" class="img-fluid" title="Intendencia">
            
                <img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="Liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color: #9A0C71; color:white;">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/intendencia.png" width="16" class="img-fluid" title="Intendencia">
             
               
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="No liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="16" class="img-fluid" title="Biomedica">
                <img src="../img/manten.jpg" width="16" class="img-fluid" title="Mantenimiento">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="No liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" style="background-color: #9A0C71; color:white;" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
             
                <img src="../img/manten.jpg" width="20" class="img-fluid" title="Mantenimiento">
                <br>
                <br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="No liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica">
             
                <img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">
                <img src="../img/intendencia.png" width="16" class="img-fluid" title="Intendencia">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="No liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" style="background-color: #9A0C71; color:white;" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               
             <img src="../img/uu.png" width="16" class="img-fluid" title="Servicios generales">
                <img src="../img/intendencia.png" width="16" class="img-fluid" title="Intendencia">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="Liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" style="background-color: #9A0C71; color:white;" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
              <img src="../img/intendencia.png" width="16" class="img-fluid" title="Intendencia">
               <br><br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="Liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="19" class="img-fluid" title="Biomedica">
             <br><br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="Liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica"><img src="../img/intendencia.png" width="10" class="img-fluid" title="Intendencia">
             
               
              </div>
            </div>
<?php

          } elseif ($biomedica=="Liberada" and $mantenimiento=="No liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert " style="background-color: #9A0C71; color:white;"role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
           
             
                <img src="../img/manten.jpg" width="15" class="img-fluid" title="Mantenimiento">
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="Liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="15" class="img-fluid" title="Biomedica">
               <br><br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="Liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color: #9A0C71; color:white;">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
              <!--  <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica">--><img src="../img/intendencia.png" width="15" class="img-fluid" title="Intendencia">
             
                <!--<img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">--><br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="Liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color: #9A0C71; color:white;">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
              <!--  <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica">-->
             <!--<img src="../img/uu.png" width="15" class="img-fluid" title="Servicios generales">-->
                <!--<img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">--><br><br>
              </div>
            </div>
<?php

          }elseif ($estaus == "EN PROCESO DE LIBERA"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="1"><?php echo $pr ?></font></h7>
            
                <br>
              </div>
            </div>
<?php
          }else{
          ?>
            <a href="#" class="small-box-footer">
            <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert" role="alert" style="background-color: #2b2d7f; color:white;">

                <i style="font-size:25px;" class="fa fa-bed"></i>
                
                <h7><font size="3"><?php echo $num_cama ?></font></h7><br>
              <!--  <h7>Estatus: OCUPADA</h7>-->
                <?php
                $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac = $row_cam['nom_pac'] . ' ' . $row_cam['papell'];
                }
                ?>
                <font size="2" class="nompac"><?php echo $nombre_pac ?></font>
                <br />
                
              </div>
            </div></a>
        <?php
          }
        }
        ?>      

        <?php
        $sql = 'SELECT * from cat_camas where piso=4 and seccion=2 ORDER BY num_cama ASC';
        $result = $conexion->query($sql);
        //  $id_atencion = $_GET['id_atencion'];
        while ($row = $result->fetch_assoc()) {
          $num_cama = $row['num_cama'];
          $id_atencion = $row['id_atencion'];
          $estaus = $row['estatus'];
          $biomedica = $row['biomedica'];
          $mantenimiento = $row['mantenimiento'];
          $serv_generales = $row['serv_generales'];
            $intendencia = $row['intendencia'];
          if ($estaus == "LIBRE" or $estaus == "Libre") {
        ?>
             <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-success" role="alert">
                <a href="#" class="small-box-footer"><i style="font-size:25px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="3"><?php echo $estaus ?></font></h7>
                <br>
                <br>
              </div>
            </div>
          <?php
          } elseif ($estaus == "MANTENIMIENTO" or $estaus == "Mantenimiento") {
             $esta = "NO DISPONIBLE";
          ?>
            <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="#" class="small-box-footer"><i style="font-size:20px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2" class="nod"><?php echo $esta ?></font></h7>
                <br>
              
              </div>
            </div>
          <?php
          } else {
          ?>
            <a href="#" class="small-box-footer">
            <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-danger" role="alert">

                <i style="font-size:25px;" class="fa fa-bed"></i>
                
                <h7><font size="3"><?php echo $num_cama ?></font></h7><br>
              <!--  <h7>Estatus: OCUPADA</h7>-->
                <?php
                $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac = $row_cam['nom_pac'] . ' ' . $row_cam['papell'];
                }
                ?>
                <font size="2" class="nompac"><?php echo $nombre_pac ?></font>
                <br />
                
              </div>
            </div></a>
        <?php
          }
        }
        ?>
  </div>
</div>
<!-- camas de urgencias -->
            
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
         <strong><center>OBSERVACIÓN</center></strong>
</div>         
<div class="container box col-12">
        <div class= "row">
        
        
        <?php
        $sql = 'SELECT * from cat_camas where piso=5 and seccion = 1 ORDER BY num_cama ASC';
        $result = $conexion->query($sql);
        //  $id_atencion = $_GET['id_atencion'];
        while ($row = $result->fetch_assoc()) {
          $num_cama = $row['num_cama'];
          $id_atencion = $row['id_atencion'];
          $estaus = $row['estatus'];
          $biomedica = $row['biomedica'];
          $mantenimiento = $row['mantenimiento'];
          $serv_generales = $row['serv_generales'];
            $intendencia = $row['intendencia'];
          if ($estaus == "LIBRE" or $estaus == "Libre") {
        ?>
            <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-success" role="alert">
                <a href="#" class="small-box-footer"><i style="font-size:25px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2"><?php echo $estaus ?></font></h7>
                <br>
                <br>
              </div>
            </div>
          <?php
          } elseif ($estaus == "MANTENIMIENTO" or $estaus == "Mantenimiento") {
             $esta = "NO DISPONIBLE";
          ?>
            <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-danger" role="alert">
                <a href="#" class="small-box-footer"><i style="font-size:23px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2" class="nod"><?php echo $esta ?></font></h7>
                <br>
                
              </div>
            </div>
           <?php
          } elseif ($biomedica=="No liberada" and $mantenimiento=="No liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica">
               <img src="../img/intendencia.png" width="10" class="img-fluid" title="Intendencia">
                <img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="No liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color: #9A0C71; color:white;">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
              <img src="../img/intendencia.png" width="10" class="img-fluid" title="Intendencia">
            
                <img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="Liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color: #9A0C71; color:white;">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/intendencia.png" width="16" class="img-fluid" title="Intendencia">
             
               
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="No liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="16" class="img-fluid" title="Biomedica">
                <img src="../img/manten.jpg" width="16" class="img-fluid" title="Mantenimiento">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="No liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" style="background-color: #9A0C71; color:white;" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
             
                <img src="../img/manten.jpg" width="20" class="img-fluid" title="Mantenimiento">
                <br>
                <br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="No liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica">
             
                <img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">
                <img src="../img/intendencia.png" width="16" class="img-fluid" title="Intendencia">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="No liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" style="background-color: #9A0C71; color:white;" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               
             <img src="../img/uu.png" width="16" class="img-fluid" title="Servicios generales">
                <img src="../img/intendencia.png" width="16" class="img-fluid" title="Intendencia">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="Liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" style="background-color: #9A0C71; color:white;" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
              <img src="../img/intendencia.png" width="16" class="img-fluid" title="Intendencia">
               <br><br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="Liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="19" class="img-fluid" title="Biomedica">
             <br><br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="Liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica"><img src="../img/intendencia.png" width="10" class="img-fluid" title="Intendencia">
             
               
              </div>
            </div>
<?php

          } elseif ($biomedica=="Liberada" and $mantenimiento=="No liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert " style="background-color: #9A0C71; color:white;"role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
           
             
                <img src="../img/manten.jpg" width="15" class="img-fluid" title="Mantenimiento">
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="Liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="15" class="img-fluid" title="Biomedica">
               <br><br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="Liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color: #9A0C71; color:white;">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
              <!--  <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica">--><img src="../img/intendencia.png" width="15" class="img-fluid" title="Intendencia">
             
                <!--<img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">--><br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="Liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color: #9A0C71; color:white;">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
              <!--  <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica">-->
             <!--<img src="../img/uu.png" width="15" class="img-fluid" title="Servicios generales">-->
                <!--<img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">--><br><br>
              </div>
            </div>
<?php

          }elseif ($estaus == "EN PROCESO DE LIBERA"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="1"><?php echo $pr ?></font></h7>
            
                <br>
              </div>
            </div>
<?php
          }else{
          ?>
            <a href="#" class="small-box-footer">
            <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert" role="alert" style="background-color: #2b2d7f; color:white;">

                <i style="font-size:25px;" class="fa fa-bed"></i>
                
                <h7><font size="3"><?php echo $num_cama ?></font></h7><br>
              <!--  <h7>Estatus: OCUPADA</h7>-->
                <?php
                $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac = $row_cam['nom_pac'] . ' ' . $row_cam['papell'];
                }
                ?>
                <font size="2" class="nompac"><?php echo $nombre_pac ?></font>
                <br />
                
              </div>
            </div></a>
        <?php
          }
        }
        ?>

        <div class="col-lg-1.5 col-xs-1">
        <center><strong><div class="thead" style="background-color: white; color: BLACK; font-size: 20px;"><p> SALA <br> DE <br> CHOQUE</p></div></strong>
        </center>
        </div>
        <?php
        $sql = 'SELECT * from cat_camas where piso=5 and seccion = 2 ORDER BY num_cama ASC';
        $result = $conexion->query($sql);
        //  $id_atencion = $_GET['id_atencion'];
        while ($row = $result->fetch_assoc()) {
          $num_cama = $row['num_cama'];
          $id_atencion = $row['id_atencion'];
          $estaus = $row['estatus'];
          $biomedica = $row['biomedica'];
          $mantenimiento = $row['mantenimiento'];
          $serv_generales = $row['serv_generales'];
            $intendencia = $row['intendencia'];
          if ($estaus == "LIBRE" or $estaus == "Libre") {
        ?>
            <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-success" role="alert">
                <a href="#" class="small-box-footer"><i style="font-size:25px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2"><?php echo $estaus ?></font></h7>
                <br>
                <br>
              </div>
            </div>
          <?php
          } elseif ($estaus == "MANTENIMIENTO" or $estaus == "Mantenimiento") {
             $esta = "NO DISPONIBLE";
          ?>
            <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-danger" role="alert">
                <a href="#" class="small-box-footer"><i style="font-size:23px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2" class="nod"><?php echo $esta ?></font></h7>
                <br>
                
              </div>
            </div>
           <?php
          } elseif ($biomedica=="No liberada" and $mantenimiento=="No liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica">
               <img src="../img/intendencia.png" width="10" class="img-fluid" title="Intendencia">
                <img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="No liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color: #9A0C71; color:white;">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
              <img src="../img/intendencia.png" width="10" class="img-fluid" title="Intendencia">
            
                <img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="Liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color: #9A0C71; color:white;">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/intendencia.png" width="16" class="img-fluid" title="Intendencia">
             
               
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="No liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="16" class="img-fluid" title="Biomedica">
                <img src="../img/manten.jpg" width="16" class="img-fluid" title="Mantenimiento">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="No liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" style="background-color: #9A0C71; color:white;" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
             
                <img src="../img/manten.jpg" width="20" class="img-fluid" title="Mantenimiento">
                <br>
                <br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="No liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica">
             
                <img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">
                <img src="../img/intendencia.png" width="16" class="img-fluid" title="Intendencia">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="No liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" style="background-color: #9A0C71; color:white;" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               
             <img src="../img/uu.png" width="16" class="img-fluid" title="Servicios generales">
                <img src="../img/intendencia.png" width="16" class="img-fluid" title="Intendencia">
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="Liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" style="background-color: #9A0C71; color:white;" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
              <img src="../img/intendencia.png" width="16" class="img-fluid" title="Intendencia">
               <br><br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="Liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="19" class="img-fluid" title="Biomedica">
             <br><br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="Liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica"><img src="../img/intendencia.png" width="10" class="img-fluid" title="Intendencia">
             
               
              </div>
            </div>
<?php

          } elseif ($biomedica=="Liberada" and $mantenimiento=="No liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert " style="background-color: #9A0C71; color:white;"role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
           
             
                <img src="../img/manten.jpg" width="15" class="img-fluid" title="Mantenimiento">
              </div>
            </div>
<?php

          }elseif ($biomedica=="No liberada" and $mantenimiento=="Liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
               <img src="../img/biomedica.png" width="15" class="img-fluid" title="Biomedica">
               <br><br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="Liberada" and $serv_generales=="No liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color: #9A0C71; color:white;">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
              <!--  <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica">--><img src="../img/intendencia.png" width="15" class="img-fluid" title="Intendencia">
             
                <!--<img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">--><br>
              </div>
            </div>
<?php

          }elseif ($biomedica=="Liberada" and $mantenimiento=="Liberada" and $serv_generales=="Liberada"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color: #9A0C71; color:white;">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
            <p>
              <!--  <img src="../img/biomedica.png" width="10" class="img-fluid" title="Biomedica">-->
             <!--<img src="../img/uu.png" width="15" class="img-fluid" title="Servicios generales">-->
                <!--<img src="../img/manten.jpg" width="10" class="img-fluid" title="Mantenimiento">--><br><br>
              </div>
            </div>
<?php

          }elseif ($estaus == "EN PROCESO DE LIBERA"){
$pr="POR LIBERAR";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../biomedica/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="1"><?php echo $pr ?></font></h7>
            
                <br>
              </div>
            </div>
<?php
          }else{
          ?>
            <a href="#" class="small-box-footer">
            <div class="col-lg-1.9 col-xs-1">
              <div class="alert alert" role="alert" style="background-color: #2b2d7f; color:white;">

                <i style="font-size:25px;" class="fa fa-bed"></i>
                
                <h7><font size="3"><?php echo $num_cama ?></font></h7><br>
              <!--  <h7>Estatus: OCUPADA</h7>-->
                <?php
                $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac = $row_cam['nom_pac'] . ' ' . $row_cam['papell'];
                }
                ?>
                <font size="2" class="nompac"><?php echo $nombre_pac ?></font>
                <br />
                
              </div>
            </div></a>
        <?php
          }
        }
        ?>
        
  </div>
</div>

</section>
      <!-- Main content -->
     <!-- <section class="content">-->
        <!-- CONTENIDOO -->
       <!--<div id="myCarousel" class="carousel slide" data-ride="carousel">-->
          <!-- Indicators -->
        <!--  <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
          </ol>-->

          <!-- Wrapper for slides -->
       <!--   <div class="carousel-inner">
            <div class="item active">
              <img src="../imagenes/sanatorio.jpg" alt="Sanatorio">
            </div>

            <div class="item">
              <img src="../imagenes/sillas.jpg" alt="sala">
            </div>

            <div class="item">
              <img src="../imagenes/sin.png" alt="sin covid">
            </div>
          </div>-->

          <!-- Left and right controls -->
        <!--  <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>-->

     <!-- </section>--><!-- /.content -->
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