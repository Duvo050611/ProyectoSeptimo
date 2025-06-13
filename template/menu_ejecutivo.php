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
$usuario1 = $usuario['id_usua'];
//$resultado = $conexion->query("SELECT * FROM reg_usuarios WHERE id_usua='" . $usuario . "'") or die($conexion->error);
if (!($usuario['id_usua'] == 1 || $usuario['id_usua'] == 200 || $usuario['id_usua'] == 221 || $usuario['id_usua'] == 429 || $usuario['id_usua'] == 266 || $usuario['id_usua'] == 393 || $usuario['id_usua'] == 437)) {
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
      if ($usuario['id_rol'] == 5 and $usuario['id_usua'] != 429) {

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
      <?php }
      
      if ($usuario['id_rol'] == 5 and ($usuario['id_usua'] == 1 || $usuario['id_usua'] == 200 || $usuario['id_usua'] == 221  || $usuario['id_usua'] == 429 || $usuario['id_usua'] == 266 || $usuario['id_usua'] == 393 || $usuario['id_usua'] == 437)) {

      ?>
        <a href="menu_ejecutivo.php" class="logo">
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
      <?php }
      
      elseif($usuario['id_rol'] == 17) { ?>
         <a href="menu_calidad.php" class="logo">
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

                <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="user-image" alt="User Image">
                <span class="hidden-xs"> <?php echo $usuario['papell']; ?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">

                  <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle" alt="User Image">

                  <p>
                   <?php echo $usuario['papell']; ?>

                  </p>
                </li>

                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="../gestion_medica/editar_perfil/editar_perfil.php?id_usua=<?php echo $usuario['id_usua'];?>" class="btn btn-default btn-flat">MIS DATOS</a>
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


            <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p> <?php echo $usuario['papell']; ?></p>

            <a href="#"><i class="fa fa-circle text-success"></i> ACTIVO</a>
          </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">

        
<!--
          <li class="treeview">
               <li><a href="../gestion_medica/ambulatorio/receta_ambulatoria.php"><i class="fa fa-user-md"></i> 
               CONSULTA EXTERNA</a></li>
          </li>  -->

          
        </ul>
                

       

            </li> 

            


             <!--

            <li class="treeview">
                <a href="../gestion_medica/hospitalizacion/hoja_alta_medica.php">
                   <i class="fa fa-street-view" aria-hidden="true"></i> <span>Imprimir encuesta</span>
                </a>
            </li>-->
<ul class="sidebar-menu">
                
            <li class="">

                <a href="../calidad/estadisticas/observaciones.php">
                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i> <span><font size ="2">REPORTE DE<br> OBSERVACIONES</font></span> <i class="fa fa-angle-left pull-right"></i>

                </a>

            </li>

            <li class="">
                <a href="../calidad/estadisticas/estadisticas.php" >
                    <i class="fa fa-line-chart"></i> <span><font size ="2">ENCUESTAS DE SATISFACCIÓN</font></span> <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>
           <?php if ($usuario['id_rol'] == 5){?>
           <li class="">
                <a href="../calidad/reportes/reporte_medico.php">
                    <i class="fa fa-heart" aria-hidden="true"></i> <span><font size ="2">REPORTE MÉDICO <BR>  (INEGI) </font></span> <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>
            
            <li class="">
                <a href="../calidad/reportes_finan/reporte_operativo.php">
                    <i class="fa fa-archive" aria-hidden="true"></i> <span><font size ="2">REPORTE <br>  OPERATIVO </font></span> <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>
            <li class="">
                <a href="../calidad/reportes_finan/reportes_finan.php">
                    <i class="fa fa-archive" aria-hidden="true"></i> <span><font size ="2">REPORTE <br>  FINANCIERO </font></span> <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>
            <li class="">
                <a href="../calidad/reportes_quir/reportes_cx.php">
                    <i class="fa fa-user-md" aria-hidden="true"></i> <span><font size ="2">METRICAS <br>  QUIRÚRGICAS </font></span> <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>
            <?php }?>
            
         <!--   <li class="">
                <a href="../calidad/personal/alta_usuarios.php">
                    <i class="fa fa-user" aria-hidden="true"></i> <span><font size ="2">GESTIÓN DE PERSONAL </font></span> <i class="fa fa-angle-right pull-right"></i>
                </a>
            </li>-->
            </ul>

         
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
              <h4>EJECUTIVO</h4>
            </STRONG></li>
        </ol>
      </nav>
      
        <div class="row">
             <div class="col-sm-1">
             
            </div>
            <div class="col-sm-2" style="background-color: #2b2d7f; color: white; ">
             <?php
                $sql = 'SELECT count(*) as total from cat_camas where estatus = "OCUPADA" and tipo  = "HOSPITALIZACIÓN" ';
                $result = $conexion->query($sql);
                while ($row = $result->fetch_assoc()) { 
                    $total = $row['total'];
                    $porcentaje = ($total / 27) * 100;?>
                    <h5><?php echo 'HAB. OCUPADAS: ' . $total  .'  OCUPACIÓN: ' . number_format($porcentaje,0) .'% ' ?></h5>
            <?php } ?>
            </div>
           
            
            <div class="col-sm-3">
             <a href="../../sauxiliares/Ceye/programacion_quir.php"><button type="button" class="btn btn-danger">AGENDA QUIRÚRGICA <i class="fa-solid fa-address-book"></i></button></a>
            </div>
            
           
        </div>
  <section class="content container-fluid">
        <div class="container">
          <div class="row">


            </div>
        </div>

<hr>

 <?php 
function tiempoTranscurridoFechas($fechaInicio,$fechaFin){
$fecha1 = new DateTime($fechaInicio);
$fecha2 = new DateTime($fechaFin);
$fecha = $fecha1->diff($fecha2);
$tiempo = "";

if ($fecha->y > 0){ // si a pasado años mostrara el siguiente formato (dd/mm/aaaa) ejemplo: 26 May 2018
//años
$tiempo .= date('d M Y', strtotime($fechaInicio));
}else{
if ($fecha->m > 0){ // si a pasado meses mostrara el siguiente formato (dd/M) ejemplo: 26 May
//meses
$tiempo .= date('d M', strtotime($fechaInicio));

}else{
if ($fecha->d > 0){ // si solo a pasado dias muestra los dias que a pasado
//dias
$tiempo .= $fecha->d;

if($fecha->d == 1)
$tiempo .= " dia ";
else
$tiempo .= " dias ";

}else{
if ($fecha->h > 0){ // si solo a pasado horas muestra cuantas horas a pasado
//horas
if($fecha->h > 0)
{
$tiempo .= $fecha->h . 'hrs';
}
}else{
//minutos
if($fecha->i > 0) // si solo a pasado minutos muestra cuantos minutos a pasado
{
$tiempo .= $fecha->i . ' min';
}
else if($fecha->i == 0) // y si solo a pasado segundos muestra cuantos segundos a pasado
$tiempo .= $fecha->s." seg";
}
}
}
}

return $tiempo;

}
?>

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
          if ($estaus == "LIBRE") {
        ?>
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-success" role="alert">
               <a href="../calidad/estadisticas/edita_camad.php?id=<?php echo $row['id']?>" class="small-box-footer"><i style="font-size:23px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2"><?php echo $estaus ?></font></h7>
                <br>
                <br>
              </div>
            </div>
          <?php
          } elseif ($estaus == "MANTENIMIENTO") {
            $esta = "NO DISPONIBLE";
          ?>
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-danger" role="alert">
                <a href="../calidad/estadisticas/edita_cama.php?id=<?php echo $row['id']?>" class="small-box-footer"><i style="font-size:23px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2" class="nod"><?php echo $esta ?></font></h7>
                <br>

              </div>
            </div>
           <?php
          } elseif ($biomedica == "Liberada" and $mantenimiento == "Liberada" and $serv_generales == "Liberada"){
$pr="CAMA LISTA";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color:#00B9FF; color: white;">
                <a href="../calidad/estadisticas/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="1"><?php echo $pr ?></font></h7>
            
                <br>
              </div>
            </div>
<?php
          }elseif ($estaus == "EN PROCESO DE LIBERACION" or $estaus == "EN PROCESO DE LIBERA"){
$pr="POR LIBERAR";
$fecha_actual = date("d-m-Y H:i");
$tiempoa = 'Listo';
$tiempob = 'Listo';
$tiempom = 'Listo';
$resultador = $conexion->query("SELECT * FROM servicios_generales where cama=$num_cama and realizado ='No' ORDER BY fecha_egreso DESC") or die($conexion->error);
 while ($fr = mysqli_fetch_array($resultador)) {
$i=$fr['fecha_egreso'];
$f=$fr['fecha'];
$tiempoa=tiempoTranscurridoFechas($i,$fecha_actual);}
$resultador = $conexion->query("SELECT * FROM biomedica where cama=$num_cama and realizado ='No' ORDER BY fecha_egreso DESC") or die($conexion->error);
 while ($fr = mysqli_fetch_array($resultador)) {
$i=$fr['fecha_egreso'];
$f=$fr['fecha'];
$tiempob=tiempoTranscurridoFechas($i,$fecha_actual);}
$resultador = $conexion->query("SELECT * FROM mantenimiento where cama=$num_cama and realizado ='No' ORDER BY fecha_egreso DESC") or die($conexion->error);
 while ($fr = mysqli_fetch_array($resultador)) {
$i=$fr['fecha_egreso'];
$f=$fr['fecha'];
$tiempom=tiempoTranscurridoFechas($i,$fecha_actual);}
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../calidad/estadisticas/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer">
                <i style="font-size:25px;" class="fa fa-bed"></i></a>
                <font size="3"><?php echo $num_cama ?></font>
                <br>
                <img src="../img/biomedica.png" width="13" class="img-fluid" title="Biomedica">
                <font size="2"><?php echo $tiempob ?></font><br>
                <img src="../img/intendencia.png" width="13" class="img-fluid" title="Intendencia">
                <font size="2"><?php echo $tiempoa ?></font><br>
                <img src="../img/manten.jpg" width="13" class="img-fluid" title="Mantenimiento">
                <font size="2"><?php echo $tiempom ?></font><br>
              </div>
            </div>
<?php
          }else{
          ?>
          <?php  
$sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where  di.id_atencion=$id_atencion and di.Id_exp = p.Id_exp  order by di.fecha DESC";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac =  $row_cam['papell']. ' ' . $row_cam['nom_pac'];
                   $id_exp = $row_cam['Id_exp'];
                }
          ?>
          
         <a href="../calidad/reportes_finan/detalle_cta.php?id_at=<?php echo $id_atencion ?>&id_exp=<?php echo $id_exp ?>&id_usua<?php echo $usuario1?>" class="small-box-footer">
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert" role="alert" style="background-color: #2b2d7f; color:white;">

                <i style="font-size:25px;" class="fa fa-bed"></i>
                
                <h7><font size="3"><?php echo $num_cama ?></font></h7><br>
              <!--  <h7>Estatus: OCUPADA</h7>-->
              <p></p>
                <?php
                $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp and alta_adm='NO' order by di.fecha DESC";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac =  $row_cam['papell']. ' ' . $row_cam['nom_pac'];
                   $id_exp = $row_cam['Id_exp'];
                }
                ?>
                <font size="2" class="nompac"><?php echo $nombre_pac?></font>
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
          if ($estaus == "LIBRE") {
        ?>
         <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-success" role="alert">
               <a href="../calidad/estadisticas/edita_camad.php?id=<?php echo $row['id']?>" class="small-box-footer"><i style="font-size:23px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2"><?php echo $estaus ?></font></h7>
                <br>
                <br>
              </div>
            </div>
          <?php
          } elseif ($estaus == "MANTENIMIENTO") {
            $esta = "NO DISPONIBLE";
          ?>
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-danger" role="alert">
                <a href="../calidad/estadisticas/edita_cama.php?id=<?php echo $row['id']?>" class="small-box-footer"><i style="font-size:23px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2"><?php echo $esta ?></font></h7>
                <br>
 
              </div>
            </div>
            <?php
          } elseif ($biomedica == "Liberada" and $mantenimiento == "Liberada" and $serv_generales == "Liberada"){
$pr="CAMA LISTA";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color:#00B9FF; color: white;">
                <a href="../calidad/estadisticas/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="1"><?php echo $pr ?></font></h7>
            
                <br>
              </div>
            </div>
<?php
          }else {
          ?>
<?php  
$sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where di.id_atencion=$id_atencion and di.Id_exp = p.Id_exp and alta_adm='NO' order by di.id_atencion DESC";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                   $nombre_pac =  $row_cam['papell']. ' ' . $row_cam['nom_pac'];
                   $id_exp = $row_cam['Id_exp'];
                }
          ?>
            <a href="../calidad/reportes_finan/detalle_cta.php?id_at=<?php echo $id_atencion ?>&id_exp=<?php echo $id_exp ?>&id_usua<?php echo $usuario1?>" class="small-box-footer">
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-danger" role="alert">

                <i style="font-size:25px;" class="fa fa-bed"></i>
                
                <h7><font size="3"><?php echo $num_cama ?></font></h7><br>
              <!--  <h7>Estatus: OCUPADA</h7>-->
                <?php
                $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                   $nombre_pac =  $row_cam['papell']. ' ' . $row_cam['nom_pac'];
                }
                ?>
                <font size="2"><?php echo $nombre_pac ?></font>
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
          if ($estaus == "LIBRE") {
        ?>
             <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-success" role="alert">
               <a href="../calidad/estadisticas/edita_camad.php?id=<?php echo $row['id']?>" class="small-box-footer"><i style="font-size:23px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2"><?php echo $estaus ?></font></h7>
                <br>
                <br>
              </div>
            </div>
          <?php
          } elseif ($estaus == "MANTENIMIENTO") {
            $esta = "NO DISPONIBLE";
          ?>
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-danger" role="alert">
              <a href="../calidad/estadisticas/edita_cama.php?id=<?php echo $row['id']?>" class="small-box-footer"><i style="font-size:23px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2" class="nod"><?php echo $esta ?></font></h7>
                <br>

              </div>
            </div>
           <?php
          } elseif ($biomedica == "Liberada" and $mantenimiento == "Liberada" and $serv_generales == "Liberada"){
$pr="CAMA LISTA";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color:#00B9FF; color: white;">
                <a href="../calidad/estadisticas/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="1"><?php echo $pr ?></font></h7>
            
                <br>
              </div>
            </div>
<?php
          }elseif ($estaus == "EN PROCESO DE LIBERACION" or $estaus == "EN PROCESO DE LIBERA"){
$pr="POR LIBERAR";
$fecha_actual = date("d-m-Y H:i");
$tiempoa = 'Listo';
$tiempob = 'Listo';
$tiempom = 'Listo';
$resultador = $conexion->query("SELECT * FROM servicios_generales where cama=$num_cama and realizado ='No' ORDER BY fecha_egreso DESC") or die($conexion->error);
 while ($fr = mysqli_fetch_array($resultador)) {
$i=$fr['fecha_egreso'];
$f=$fr['fecha'];
$tiempoa=tiempoTranscurridoFechas($i,$fecha_actual);}
$resultador = $conexion->query("SELECT * FROM biomedica where cama=$num_cama and realizado ='No' ORDER BY fecha_egreso DESC") or die($conexion->error);
 while ($fr = mysqli_fetch_array($resultador)) {
$i=$fr['fecha_egreso'];
$f=$fr['fecha'];
$tiempob=tiempoTranscurridoFechas($i,$fecha_actual);}
$resultador = $conexion->query("SELECT * FROM mantenimiento where cama=$num_cama and realizado ='No' ORDER BY fecha_egreso DESC") or die($conexion->error);
 while ($fr = mysqli_fetch_array($resultador)) {
$i=$fr['fecha_egreso'];
$f=$fr['fecha'];
$tiempom=tiempoTranscurridoFechas($i,$fecha_actual);}
?>
<div class="col-lg-1.9 col-xs-1">
               <div class="alert alert-warning" role="alert">
                <a href="../calidad/estadisticas/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer">
                <i style="font-size:25px;" class="fa fa-bed"></i></a>
                <font size="3"><?php echo $num_cama ?></font>
                <br>
                <img src="../img/biomedica.png" width="13" class="img-fluid" title="Biomedica">
                <font size="2"><?php echo $tiempob ?></font><br>
                <img src="../img/intendencia.png" width="13" class="img-fluid" title="Intendencia">
                <font size="2"><?php echo $tiempoa ?></font><br>
                <img src="../img/manten.jpg" width="13" class="img-fluid" title="Mantenimiento">
                <font size="2"><?php echo $tiempom ?></font><br>
              </div>
            </div>
<?php
          }else{
          ?>
          <?php $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where di.id_atencion=$id_atencion and di.Id_exp = p.Id_exp and alta_adm='NO' order by di.id_atencion DESC";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                   $nombre_pac =  $row_cam['papell']. ' ' . $row_cam['nom_pac'];
                   $id_exp = $row_cam['Id_exp'];
                } ?>
            <a href="../calidad/reportes_finan/detalle_cta.php?id_at=<?php echo $id_atencion ?>&id_exp=<?php echo $id_exp ?>&id_usua<?php echo $usuario1?>" class="small-box-footer">
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert" role="alert" style="background-color: #2b2d7f; color:white;">

                <i style="font-size:25px;" class="fa fa-bed"></i>
                
                <h7><font size="3"><?php echo $num_cama ?></font></h7><br>
              <!--  <h7>Estatus: OCUPADA</h7>-->
                <?php
                $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                   $nombre_pac =  $row_cam['papell']. ' ' . $row_cam['nom_pac'];
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
          if ($estaus == "LIBRE") {
        ?>
             <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-success" role="alert">
                <a href="../calidad/estadisticas/edita_camad.php?id=<?php echo $row['id']?>" class="small-box-footer"><i style="font-size:23px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2"><?php echo $estaus ?></font></h7>
                <br>
                <br>
              </div>
            </div>
          <?php
          } elseif ($estaus == "MANTENIMIENTO") {
            $esta = "NO DISPONIBLE";
          ?>
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../calidad/estadisticas/edita_cama.php?id=<?php echo $row['id']?>" class="small-box-footer"><i style="font-size:23px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2" class="nod"><?php echo $esta ?></font></h7>
                <br>

              </div>
            </div>
          <?php
          }elseif ($biomedica == "Liberada" and $mantenimiento == "Liberada" and $serv_generales == "Liberada"){
$pr="CAMA LISTA";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color:#00B9FF; color: white;">
                <a href="../calidad/estadisticas/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="1"><?php echo $pr ?></font></h7>
            
                <br>
              </div>
            </div>
<?php
          } else {
          ?>
          <?php $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp cc.num_cama from dat_ingreso di, paciente p, cat_camas cc where di.id_atencion=$id_atencion and di.Id_exp = p.Id_exp and cc.id_atencion = $id_atencion and alta_adm='NO' order by di.id_atencion DESC";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                   $nombre_pac =  $row_cam['papell']. ' ' . $row_cam['nom_pac'];
                   $id_exp = $row_cam['Id_exp'];
                   $id_cama = $row_cam['num_cama'];
                } ?>
            <a href="../calidad/reportes_finan/detalle_cta.php?id_at=<?php echo $id_atencion ?>&id_exp=<?php echo $id_exp ?>&id_usua<?php echo $usuario1?>" class="small-box-footer">
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-danger" role="alert">

                <i style="font-size:25px;" class="fa fa-bed"></i>
                
                <h7><font size="3"><?php echo $num_cama ?></font></h7><br>
              <!--  <h7>Estatus: OCUPADA</h7>-->
                <?php
                $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac =  $row_cam['papell']. ' ' . $row_cam['nom_pac'];
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
          if ($estaus == "LIBRE") {
        ?>
             <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-success" role="alert">
                 <a href="../calidad/estadisticas/edita_camad.php?id=<?php echo $row['id']?>" class="small-box-footer"><i style="font-size:23px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2"><?php echo $estaus ?></font></h7>
                <br>
                <br>
              </div>
            </div>
          <?php
          } elseif ($estaus == "MANTENIMIENTO") {
            $esta = "NO DISPONIBLE";
          ?>
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-danger" role="alert">
                <a href="../calidad/estadisticas/edita_cama.php?id=<?php echo $row['id']?>" class="small-box-footer"><i style="font-size:23px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2" class="nod"><?php echo $esta ?></font></h7>
                <br>

              </div>
            </div>
          <?php
          } elseif ($biomedica == "Liberada" and $mantenimiento == "Liberada" and $serv_generales == "Liberada"){
$pr="CAMA LISTA";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color:#00B9FF; color: white;">
                <a href="../calidad/estadisticas/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="1"><?php echo $pr ?></font></h7>
            
                <br>
              </div>
            </div>
<?php
          }elseif ($estaus == "EN PROCESO DE LIBERACION" or $estaus == "EN PROCESO DE LIBERA"){
$pr="POR LIBERAR";
$fecha_actual = date("d-m-Y H:i");
$tiempoa = 'Listo';
$tiempob = 'Listo';
$tiempom = 'Listo';
$resultador = $conexion->query("SELECT * FROM servicios_generales where cama=$num_cama and realizado ='No' ORDER BY fecha_egreso DESC") or die($conexion->error);
 while ($fr = mysqli_fetch_array($resultador)) {
$i=$fr['fecha_egreso'];
$f=$fr['fecha'];
$tiempoa=tiempoTranscurridoFechas($i,$fecha_actual);}
$resultador = $conexion->query("SELECT * FROM biomedica where cama=$num_cama and realizado ='No' ORDER BY fecha_egreso DESC") or die($conexion->error);
 while ($fr = mysqli_fetch_array($resultador)) {
$i=$fr['fecha_egreso'];
$f=$fr['fecha'];
$tiempob=tiempoTranscurridoFechas($i,$fecha_actual);}
$resultador = $conexion->query("SELECT * FROM mantenimiento where cama=$num_cama and realizado ='No' ORDER BY fecha_egreso DESC") or die($conexion->error);
 while ($fr = mysqli_fetch_array($resultador)) {
$i=$fr['fecha_egreso'];
$f=$fr['fecha'];
$tiempom=tiempoTranscurridoFechas($i,$fecha_actual);}
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../calidad/estadisticas/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer">
                <i style="font-size:25px;" class="fa fa-bed"></i></a>
                <font size="3"><?php echo $num_cama ?></font>
                <br>
                <img src="../img/biomedica.png" width="13" class="img-fluid" title="Biomedica">
                <font size="2"><?php echo $tiempob ?></font><br>
                <img src="../img/intendencia.png" width="13" class="img-fluid" title="Intendencia">
                <font size="2"><?php echo $tiempoa ?></font><br>
                <img src="../img/manten.jpg" width="13" class="img-fluid" title="Mantenimiento">
                <font size="2"><?php echo $tiempom ?></font><br>
              </div>
            </div>
<?php
          }else{
          ?>
          
          
          
          
          
          <?php $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where di.id_atencion=$id_atencion and di.Id_exp = p.Id_exp and alta_adm='NO' order by di.id_atencion DESC";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac =  $row_cam['papell']. ' ' . $row_cam['nom_pac'];
                   $id_exp = $row_cam['Id_exp'];
                } ?>
           <a href="../calidad/reportes_finan/detalle_cta.php?id_at=<?php echo $id_atencion ?>&id_exp=<?php echo $id_exp ?>&id_usua<?php echo $usuario1?>" class="small-box-footer">
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert" role="alert" style="background-color: #2b2d7f; color:white;">

                <i style="font-size:25px;" class="fa fa-bed"></i>
                
                <h7><font size="3"><?php echo $num_cama ?></font></h7><br>
              <!--  <h7>Estatus: OCUPADA</h7>-->
                <?php
                $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac =  $row_cam['papell']. ' ' . $row_cam['nom_pac'];
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
          if ($estaus == "LIBRE") {
        ?>
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-success" role="alert">
             <a href="../calidad/estadisticas/edita_camad.php?id=<?php echo $row['id']?>" class="small-box-footer"><i style="font-size:23px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2"><?php echo $estaus ?></font></h7>
                <br>
                <br>
              </div>
            </div>
          <?php
          } elseif ($estaus == "MANTENIMIENTO") {
            $esta = "NO DISPONIBLE";
          ?>
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-danger" role="alert">
               <a href="../calidad/estadisticas/edita_cama.php?id=<?php echo $row['id']?>" class="small-box-footer"><i style="font-size:23px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2" class="nod"><?php echo $esta ?></font></h7>
                <br>

              </div>
            </div>
           <?php
          } elseif ($biomedica == "Liberada" and $mantenimiento == "Liberada" and $serv_generales == "Liberada"){
$pr="CAMA LISTA";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color:#00B9FF; color: white;">
                <a href="../calidad/estadisticas/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="1"><?php echo $pr ?></font></h7>
            
                <br>
              </div>
            </div>
<?php
          }elseif ($estaus == "EN PROCESO DE LIBERACION" or $estaus == "EN PROCESO DE LIBERA"){
$pr="POR LIBERAR";
$fecha_actual = date("d-m-Y H:i");
$tiempoa = 'Listo';
$tiempob = 'Listo';
$tiempom = 'Listo';
$resultador = $conexion->query("SELECT * FROM servicios_generales where cama=$num_cama and realizado ='No' ORDER BY fecha_egreso DESC") or die($conexion->error);
 while ($fr = mysqli_fetch_array($resultador)) {
$i=$fr['fecha_egreso'];
$f=$fr['fecha'];
$tiempoa=tiempoTranscurridoFechas($i,$fecha_actual);}
$resultador = $conexion->query("SELECT * FROM biomedica where cama=$num_cama and realizado ='No' ORDER BY fecha_egreso DESC") or die($conexion->error);
 while ($fr = mysqli_fetch_array($resultador)) {
$i=$fr['fecha_egreso'];
$f=$fr['fecha'];
$tiempob=tiempoTranscurridoFechas($i,$fecha_actual);}
$resultador = $conexion->query("SELECT * FROM mantenimiento where cama=$num_cama and realizado ='No' ORDER BY fecha_egreso DESC") or die($conexion->error);
 while ($fr = mysqli_fetch_array($resultador)) {
$i=$fr['fecha_egreso'];
$f=$fr['fecha'];
$tiempom=tiempoTranscurridoFechas($i,$fecha_actual);}
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="../calidad/estadisticas/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer">
                <i style="font-size:25px;" class="fa fa-bed"></i></a>
                <font size="3"><?php echo $num_cama ?></font>
                <br>
                <img src="../img/biomedica.png" width="13" class="img-fluid" title="Biomedica">
                <font size="2"><?php echo $tiempob ?></font><br>
                <img src="../img/intendencia.png" width="13" class="img-fluid" title="Intendencia">
                <font size="2"><?php echo $tiempoa ?></font><br>
                <img src="../img/manten.jpg" width="13" class="img-fluid" title="Mantenimiento">
                <font size="2"><?php echo $tiempom ?></font><br>
              </div>
            </div>
<?php
          }else{
          ?>
          <?php $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where di.id_atencion=$id_atencion and di.Id_exp = p.Id_exp and alta_adm='NO' order by di.id_atencion DESC";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac =  $row_cam['papell']. ' ' . $row_cam['nom_pac'];
                   $id_exp = $row_cam['Id_exp'];
                } ?>
           <a href="../calidad/reportes_finan/detalle_cta.php?id_at=<?php echo $id_atencion ?>&id_exp=<?php echo $id_exp ?>&id_usua<?php echo $usuario1?>" class="small-box-footer">
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert" role="alert" style="background-color: #2b2d7f; color:white;">

                <i style="font-size:25px;" class="fa fa-bed"></i>
                
                <h7><font size="3"><?php echo $num_cama ?></font></h7><br>
              <!--  <h7>Estatus: OCUPADA</h7>-->
                <?php
                $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac =  $row_cam['papell']. ' ' . $row_cam['nom_pac'];
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

 <!-- camas de ucin -->
       
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
          if ($estaus == "LIBRE") {
        ?>
             <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-success" role="alert">
                 <a href="../calidad/estadisticas/edita_camad.php?id=<?php echo $row['id']?>" class="small-box-footer"><i style="font-size:23px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2"><?php echo $estaus ?></font></h7>
                <br>
                <br>
              </div>
            </div>
          <?php
          } elseif ($estaus == "MANTENIMIENTO") {
            $esta = "NO DISPONIBLE";
          ?>
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-warning" role="alert">
               <a href="../calidad/estadisticas/edita_cama.php?id=<?php echo $row['id']?>" class="small-box-footer"><i style="font-size:23px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2"><?php echo $esta ?></font></h7>
                <br>

              </div>
            </div>
          <?php
          } elseif ($biomedica == "Liberada" and $mantenimiento == "Liberada" and $serv_generales == "Liberada"){
$pr="CAMA LISTA";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color:#00B9FF; color: white;">
                <a href="../calidad/estadisticas/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="1"><?php echo $pr ?></font></h7>
            
                <br>
              </div>
            </div>
<?php
          }else {
          ?>
          <?php $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where di.id_atencion=$id_atencion and di.Id_exp = p.Id_exp and alta_adm='NO' order by di.id_atencion DESC";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac =  $row_cam['papell']. ' ' . $row_cam['nom_pac'];
                   $id_exp = $row_cam['Id_exp'];
                } ?>
            <a href="../calidad/reportes_finan/detalle_cta.php?id_at=<?php echo $id_atencion ?>&id_exp=<?php echo $id_exp ?>&id_usua<?php echo $usuario1?>" class="small-box-footer">
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-danger" role="alert">

                <i style="font-size:25px;" class="fa fa-bed"></i>
                
                <h7><font size="3"><?php echo $num_cama ?></font></h7><br>
              <!--  <h7>Estatus: OCUPADA</h7>-->
                <?php
                $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac =  $row_cam['papell']. ' ' . $row_cam['nom_pac'];
                }
                ?>
                <font size="2"><?php echo $nombre_pac ?></font>
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
          if ($estaus == "LIBRE") {
        ?>
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-success" role="alert">
                 <a href="../calidad/estadisticas/edita_camad.php?id=<?php echo $row['id']?>" class="small-box-footer"><i style="font-size:23px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2"><?php echo $estaus ?></font></h7>
                <br>
                <br>
              </div>
            </div>
          <?php
          } elseif ($estaus == "MANTENIMIENTO") {
            $esta = "NO DISPONIBLE";
          ?>
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-danger" role="alert">
                <a href="../calidad/estadisticas/edita_cama.php?id=<?php echo $row['id']?>" class="small-box-footer"><i style="font-size:23px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2" class="nod"><?php echo $esta ?></font></h7> 
                <br>
              </div>
            </div>
          <?php
          } elseif ($biomedica == "Liberada" and $mantenimiento == "Liberada" and $serv_generales == "Liberada"){
$pr="CAMA LISTA";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color:#00B9FF; color: white;">
                <a href="../calidad/estadisticas/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="1"><?php echo $pr ?></font></h7>
            
                <br>
              </div>
            </div>
<?php
          }elseif ($estaus == "EN PROCESO DE LIBERACION" or $estaus == "EN PROCESO DE LIBERA"){
$pr="POR LIBERAR";
$fecha_actual = date("d-m-Y H:i");
$tiempoa = 'Listo';
$tiempob = 'Listo';
$tiempom = 'Listo';
$resultador = $conexion->query("SELECT * FROM servicios_generales where cama=$num_cama and realizado ='No' ORDER BY fecha_egreso DESC") or die($conexion->error);
 while ($fr = mysqli_fetch_array($resultador)) {
$i=$fr['fecha_egreso'];
$f=$fr['fecha'];
$tiempoa=tiempoTranscurridoFechas($i,$fecha_actual);}
$resultador = $conexion->query("SELECT * FROM biomedica where cama=$num_cama and realizado ='No' ORDER BY fecha_egreso DESC") or die($conexion->error);
 while ($fr = mysqli_fetch_array($resultador)) {
$i=$fr['fecha_egreso'];
$f=$fr['fecha'];
$tiempob=tiempoTranscurridoFechas($i,$fecha_actual);}
$resultador = $conexion->query("SELECT * FROM mantenimiento where cama=$num_cama and realizado ='No' ORDER BY fecha_egreso DESC") or die($conexion->error);
 while ($fr = mysqli_fetch_array($resultador)) {
$i=$fr['fecha_egreso'];
$f=$fr['fecha'];
$tiempom=tiempoTranscurridoFechas($i,$fecha_actual);}
?>
<div class="col-lg-1.9 col-xs-1">
               <div class="alert alert-warning" role="alert">
                <a href="../calidad/estadisticas/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer">
                <i style="font-size:25px;" class="fa fa-bed"></i></a>
                <font size="3"><?php echo $num_cama ?></font>
                <br>
                <img src="../img/biomedica.png" width="13" class="img-fluid" title="Biomedica">
                <font size="2"><?php echo $tiempob ?></font><br>
                <img src="../img/intendencia.png" width="13" class="img-fluid" title="Intendencia">
                <font size="2"><?php echo $tiempoa ?></font><br>
                <img src="../img/manten.jpg" width="13" class="img-fluid" title="Mantenimiento">
                <font size="2"><?php echo $tiempom ?></font><br>
              </div>
            </div>
<?php
          }else{
          ?>
          <?php $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where di.id_atencion=$id_atencion and di.Id_exp = p.Id_exp and alta_adm='NO' order by di.id_atencion DESC";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac =  $row_cam['papell']. ' ' . $row_cam['nom_pac'];
                   $id_exp = $row_cam['Id_exp'];
                } ?>
            <a href="../calidad/reportes_finan/detalle_cta.php?id_at=<?php echo $id_atencion ?>&id_exp=<?php echo $id_exp ?>&id_usua<?php echo $usuario1?>" class="small-box-footer">
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert" role="alert" style="background-color: #2b2d7f; color:white;">

                <i style="font-size:25px;" class="fa fa-bed"></i>
                
                <h7><font size="3"><?php echo $num_cama ?></font></h7><br>
              <!--  <h7>Estatus: OCUPADA</h7>-->
                <?php
                $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac =  $row_cam['papell']. ' ' . $row_cam['nom_pac'];
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
          if ($estaus == "LIBRE") {
        ?>
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-success" role="alert">
                <a href="../calidad/estadisticas/edita_camad.php?id=<?php echo $row['id']?>" class="small-box-footer"><i style="font-size:23px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2"><?php echo $estaus ?></font></h7>
                <br>
                <br>
              </div>
            </div>
          <?php
          } elseif ($estaus == "MANTENIMIENTO") {
            $esta = "NO DISPONIBLE";
          ?>
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-danger" role="alert">
              <a href="../calidad/estadisticas/edita_cama.php?id=<?php echo $row['id']?>" class="small-box-footer"><i style="font-size:23px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2" class="nod"><?php echo $esta ?></font></h7> 
                <br>
              </div>
            </div>
          <?php
          } elseif ($biomedica == "Liberada" and $mantenimiento == "Liberada" and $serv_generales == "Liberada"){
$pr="CAMA LISTA";
?>
<div class="col-lg-1.9 col-xs-1">
              <div class="alert" role="alert" style="background-color:#00B9FF; color: white;">
                <a href="../calidad/estadisticas/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer"><i style="font-size:24px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="1"><?php echo $pr ?></font></h7>
            
                <br>
              </div>
            </div>
<?php
          }elseif ($estaus == "EN PROCESO DE LIBERACION" or $estaus == "EN PROCESO DE LIBERA"){
$pr="POR LIBERAR";
$fecha_actual = date("d-m-Y H:i");
$tiempoa = 'Listo';
$tiempob = 'Listo';
$tiempom = 'Listo';
$resultador = $conexion->query("SELECT * FROM servicios_generales where cama=$num_cama and realizado ='No' ORDER BY fecha_egreso DESC") or die($conexion->error);
 while ($fr = mysqli_fetch_array($resultador)) {
$i=$fr['fecha_egreso'];
$f=$fr['fecha'];
$tiempoa=tiempoTranscurridoFechas($i,$fecha_actual);}
$resultador = $conexion->query("SELECT * FROM biomedica where cama=$num_cama and realizado ='No' ORDER BY fecha_egreso DESC") or die($conexion->error);
 while ($fr = mysqli_fetch_array($resultador)) {
$i=$fr['fecha_egreso'];
$f=$fr['fecha'];
$tiempob=tiempoTranscurridoFechas($i,$fecha_actual);}
$resultador = $conexion->query("SELECT * FROM mantenimiento where cama=$num_cama and realizado ='No' ORDER BY fecha_egreso DESC") or die($conexion->error);
 while ($fr = mysqli_fetch_array($resultador)) {
$i=$fr['fecha_egreso'];
$f=$fr['fecha'];
$tiempom=tiempoTranscurridoFechas($i,$fecha_actual);}
?>
<div class="col-lg-1.9 col-xs-1">
               <div class="alert alert-warning" role="alert">
                <a href="../calidad/estadisticas/liberacion_camas.php?num_cama=<?php echo $num_cama ?>" class="small-box-footer">
                <i style="font-size:25px;" class="fa fa-bed"></i></a>
                <font size="3"><?php echo $num_cama ?></font>
                <br>
                <img src="../img/biomedica.png" width="13" class="img-fluid" title="Biomedica">
                <font size="2"><?php echo $tiempob ?></font><br>
                <img src="../img/intendencia.png" width="13" class="img-fluid" title="Intendencia">
                <font size="2"><?php echo $tiempoa ?></font><br>
                <img src="../img/manten.jpg" width="13" class="img-fluid" title="Mantenimiento">
                <font size="2"><?php echo $tiempom ?></font><br>
              </div>
            </div>
<?php
          }else{
          ?>
          <?php $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where di.id_atencion=$id_atencion and di.Id_exp = p.Id_exp and alta_adm='NO' order by di.id_atencion DESC";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac =  $row_cam['papell']. ' ' . $row_cam['nom_pac'];
                   $id_exp = $row_cam['Id_exp'];
                } ?>
            <a href="../calidad/reportes_finan/detalle_cta.php?id_at=<?php echo $id_atencion ?>&id_exp=<?php echo $id_exp ?>&id_usua<?php echo $usuario1?>" class="small-box-footer">
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert" role="alert" style="background-color: #2b2d7f; color:white;">

                <i style="font-size:25px;" class="fa fa-bed"></i>
                
                <h7><font size="3"><?php echo $num_cama ?></font></h7><br>
              <!--  <h7>Estatus: OCUPADA</h7>-->
                <?php
                $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac =  $row_cam['papell']. ' ' . $row_cam['nom_pac'];
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
                       
</div>

</section>

   
    <footer class="main-footer">
      <?php
      include("footer.php");
      ?>
    </footer>


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