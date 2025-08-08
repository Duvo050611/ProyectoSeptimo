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
if (!($usuario['id_rol'] == 9 || $usuario['id_rol'] == 5 || $usuario['id_rol'] == 2 || $usuario['id_rol'] == 12 || $usuario['id_rol'] == 10 || $usuario['id_rol'] == 1)) {
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
        td.fondo {
            background-color: red !important;
        }

    </style>
</head>

<body class=" hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <!-- <img src="dist/img/logo.jpg" alt="logo">-->
        <?php
        if ($usuario['id_rol'] == 9 ) {
            ?>

            <a href="menu_imagenologia.php" class="logo">
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
             <?php
        } else if ($usuario['id_rol'] == 2) {

            ?>
            <a href="menu_medico.php" class="logo">
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
        } else if ($usuario['id_rol'] == 12) {

            ?>
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
             <?php
        } else if ($usuario['id_rol'] == 10) {

            ?>
            <a href="menu_laboratorio.php" class="logo">
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
                            <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="user-image"
                                 alt="User Image"/>

                            <span class="hidden-xs"><?php echo $usuario['papell'];?> <?php echo $usuario['sapell']; ?></span>

                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle"
                                     alt="User Image"/>
                                <p>
                                    <?php echo $usuario['papell'];?> <?php echo $usuario['sapell'];?>
                                </p>
                            </li>

                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat">Profile</a>
                                </div>
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
                    <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle" alt="User Image"/>
                </div>
                <div class="pull-left info">
                    <p><?php echo $usuario['papell']; ?></p>

                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>

            
            <ul class="sidebar-menu">
               
                <li class="treeview">
                    <a href="../sauxiliares/Imagenologia/sol_imagen.php">
                        <i class="fa fa-file-text"></i> <span>SOLICITUD DE ESTUDIOS <br> DE IMAGENOLOGÍA</span>

                    </a>    

                </li>
                <li class="treeview">
                    <a href="../sauxiliares/Imagenologia/resultados_imagen.php">
                        <i class="fa fa-file-image-o"></i> <span>RESULTADOS DE ESTUDIOS <br>DE IMAGENOLOGÍA</span>

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
                        <h4>IMAGENOLOGIA</h4>
                    </STRONG></li>
            </ol>
        </nav>

        <!-- Main content -->
        <section class="content">
            <br>
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
         <tr><strong><center>ESTUDIOS DE IMAGENOLOGÍA PENDIENTES</center></strong>
      </div><br>
      
            <section class="content container-fluid">
                <div class="content box">
                    <!-- CONTENIDOO -->

                    <div class="form-group">
                        <input type="text" class="form-control pull-right" style="width:20%" id="search"
                               placeholder="Buscar...">
                    </div>
                    <br>

                    <div class="table-responsive">
                        <!--<table id="myTable" class="table table-striped table-hover">-->

                        <table class="table table-bordered table-striped" id="mytable">

                            <thead class="thead" style="background-color: #2b2d7f; color:white;">
                            <tr>
                                <th><font color="white">Habitación</th>
                                <th><font color="white">Paciente</th>
                                <th><font color="white">Fecha de solicitud</th>
                                <th><font color="white">Solicitante</th>
                                <th><font color="white">Estudio(s)</th>
                                <th><font color="white">Realizado</th>
                                <th><font color="white">Interpretado</th>
                                <th><font color="white">Solicitud de estudio</th> 
                                <th><font color="white">Subir Qr</th>
                                <th><font color="white">Ver estudio</th>
                                <th><font color="white">Subir interpretación</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            include "../conexionbd.php";



                            $query = " SELECT * FROM notificaciones_imagen n, reg_usuarios u where (n.interpretado = 'NO'|| n.realizado = 'NO') and n.id_usua = u.id_usua and ACTIVO = 'SI' order by fecha_ord DESC ";
                            $result = $conexion->query($query);
                            $no = 1;

                            while ($row = $result->fetch_assoc()) {

                                $habi = $row['habitacion'];
                                $id_atencion = $row['id_atencion'];
                                $es=$row['sol_estudios'].'/ '.$row['det_imagen'] ;
$sql_dat_ingi = "SELECT * from cat_servicios where serv_desc='$es'";
                     $result_dat_inga = $conexion->query($sql_dat_ingi);

                     while ($row_dat_ingu = $result_dat_inga->fetch_assoc()) {
                    //$desc = $row_dat_ingu['serv_desc'];
                    $tipins = $row_dat_ingu['tip_insumo'];
  
                    }


                            if ($habi <> 0)  {
                                $query_pac = "SELECT * FROM dat_ingreso d, paciente p where d.id_atencion = $id_atencion and d.Id_exp = p.Id_exp";
                                $result_pac = $conexion->query($query_pac);

                                while ($row_pac = $result_pac->fetch_assoc()) {
                                    $pac = $row_pac['papell'] . ' ' . $row_pac['sapell'] . ' ' . $row_pac['nom_pac'];
                                }
                                $query_pac = "SELECT * FROM dat_ingreso d, paciente p where d.id_atencion = $id_atencion and d.Id_exp = p.Id_exp";
                                $result_pac = $conexion->query($query_pac);

                                echo '<tr>'
                                    . '<td class="fondo" style="color:white;">' . $row['habitacion'] . '</td>'
                                    . '<td class="fondo" style="color:white;"> ' . $pac . '</td>'
                                    . '<td class="fondo" style="color:white;">' . $row['fecha_ord'] . '</td>'
                                    . '<td class="fondo" style="color:white;">' . $row['papell'] . ' ' . $row['sapell'] . '</td>'
                                    . '<td class="fondo" style="color:white;">' . $es. '</a></td>'

    . '<td class="fondo" style="color:white;"><center>' . $row['realizado'] . '</td></center>'
    . '<td class="fondo" style="color:white;"><center>' . $row['interpretado'] . '</center></td>'
    .' <td class="fondo" style="color:white;"><center><a href="../sauxiliares/Imagenologia/pdf_solicitud_estu.php?id_atencion='.$row['id_atencion'].'&notid='.$row['not_id'].'&medico='.$row['papell'] . ' ' . $row['sapell'] . ' ' . $row['nombre'].'&paciente='.$pac.'&tipo='.$row['sol_estudios'].'" target="_blank" ><button type="button" class="btn btn-success"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button></td></center>';
                               

                                echo '<td class="fondo" style="color:white;"><center>'
                                    . ' <a href="../sauxiliares/Imagenologia/subir_resultado.php?not_id=' . $row['not_id'] . '&id_usua=' . $row['id_usua'] . '" title="Subir Qr" class="btn btn-success "><span class="fa fa-cloud-upload" aria-hidden="true"></span></a>'

                                    . '<td class="fondo" style="color:white; font-size:30px;" ><a href="' . $row['link']  . '" target="_blank" title="Ver estudio"><center><i class="fa fa-eye" aria-hidden="true" ></i></center></a></td>'

                                . '<td class="fondo" style="color:white;"><center>'
                                    . ' <a href="../sauxiliares/Imagenologia/subir_interpretacion.php?not_id=' . $row['not_id'] . '&id_usua=' . $row['id_usua'] . '" title="Subir Qr" class="btn btn-success "><i class="fa fa-upload" aria-hidden="true"></i></a>';

                                echo '</center></td></tr>';
                                $no++;
                            
                             } else  {
                                $query_rec = "SELECT * FROM receta_ambulatoria where id_rec_amb = $id_atencion";
                                $result_rec = $conexion->query($query_rec);

                                while ($row_rec = $result_rec->fetch_assoc()) {
                                    $pac = $row_rec['papell_rec'] . ' ' . $row_rec['sapell_rec'] . ' ' . $row_rec['nombre_rec'];
                                    $habitacion = "C.EXT";
                                }
                               echo '<tr>'

                                    . '<td class="fondo" style="color:white;">' . $row['id_atencion'] . ' ' . $pac . '</td>'
                                    . '<td class="fondo" style="color:white;">' . $habitacion . '</td>'
                                    . '<td class="fondo" style="color:white;">' . $row['fecha_ord'] . '</td>'
                                    . '<td class="fondo" style="color:white;">' . $row['papell'] . ' ' . $row['sapell'] . ' ' . $row['nombre'] . '</td>'
                                    . '<td class="fondo" style="color:white;">' . $row['sol_estudios'] . '</a></td>'
                                    . '<td class="fondo" style="color:white;">' . $row['realizado'] . '</td>';

                               

                                echo '<td class="fondo" style="color:white;"><center>'
                                    . ' <a href="../sauxiliares/Imagenologia/subir_resultado.php?not_id=' . $row['not_id'] . '" title="Editar datos" class="btn btn-success "><span class="fa fa-cloud-upload" aria-hidden="true"></span></a>';
                                echo '</center></td></tr>';
                                $no++;

                             } }
                            ?>
                            </tbody>
                        </table>
                    </div>


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