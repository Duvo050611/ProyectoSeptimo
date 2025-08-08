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
if (!($usuario['id_rol'] == 10 || $usuario['id_rol'] == 5 || $usuario['id_rol'] == 12 || $usuario['id_rol'] == 1)) {
    session_unset();
    session_destroy();
    // echo "<script>window.Location='../index.php';</script>";
    header('Location: ../index.php');
}



$resultado1 = $conexion->query("SELECT * FROM notificaciones_labo where realizado = 'NO' AND activo = 'SI' order by fecha_ord DESC limit 1" ) or die($conexion->error);
        while ($f1 = mysqli_fetch_array($resultado1)) {
         $cart_id=$f1['not_id'];
        }
            if(isset($cart_id)&& ($usuario['id_rol']==10 || $usuario['id_rol'] == 5)){
 ?>

<!--<audio >
    <source src="alerta.mp3" type="audio/mp3" autoplay>
</audio>-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
<script>
$(document).ready(function() {
    var myAudio = document.createElement('audio');
    var myMessageAlert = "";
    myAudio.src = 'alerta.mp3';
    myAudio.addEventListener('ended', function() {
        alert(myMessageAlert);
    });

    function Myalert(message) {
        myAudio.play();
        myMessageAlert = message;
    }
    Myalert("Mensaje");

    function alert(message) {
        myAudio.play();
        myMessageAlert = message;
    }
    alert("Mensaje");

    swal({
        title: "HAY SOLICITUDES DE LABORATORIO PENDIENTES",
        type: "error",
        confirmButtonText: "ACEPTAR"
    }, function(isConfirm) {
        if (isConfirm) {
            window.location.href = "../sauxiliares/Laboratorio/sol_laboratorio.php";
        }
    });
});
</script>


<?php } ?>


<?php
$resultado1 = $conexion->query("SELECT * FROM notificaciones_labo where realizado = 'NO' AND activo = 'SI' order by fecha_ord DESC limit 1" ) or die($conexion->error);
        while ($f1 = mysqli_fetch_array($resultado1)) {
         $not_id=$f1['not_id'];
        }

if(isset($not_id)&& $usuario['id_rol']==10){
 ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>

<script>
$(document).ready(function() {
    var myAudio = document.createElement('audio');
    var myMessageAlert = "";
    myAudio.src = 'alerta.mp3';
    myAudio.addEventListener('ended', function() {
        alert(myMessageAlert);
    });

    function Myalert(message) {
        myAudio.play();
        myMessageAlert = message;
    }
    Myalert("Mensaje");

    function alert(message) {
        myAudio.play();
        myMessageAlert = message;
    }
    alert("Mensaje");
    swal({
        title: "NUEVA SOLICITUD DE LABORATORIO",
        type: "error",
        confirmButtonText: "ACEPTAR"
    }, function(isConfirm) {
        if (isConfirm) {
            window.location.href = "../sauxiliares/Laboratorio/sol_laboratorio.php";
        }
    });
});
</script>
<?php } ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>INEO Metepec</title>
    <link rel="icon" href="../imagenes/SIF.PNG">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet"
        type="text/css" />
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

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <?php 

$resultado = $conexion->query("select paciente.*, dat_ingreso.id_atencion, triage.id_triage
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp
inner join triage on dat_ingreso.id_atencion=triage.id_atencion where id_triage=id_triage
") or die($conexion->error);

$usuario = $_SESSION['login'];

$rol=$usuario['id_rol'];

$resultado1 = $conexion->query("SELECT * FROM notificaciones_labo where realizado = 'NO' AND activo = 'SI' order by fecha_ord DESC limit 1" ) or die($conexion->error);
        while ($f1 = mysqli_fetch_array($resultado1)) {
         $not_id=$f1['not_id'];
        }
            if(isset($not_id)&& $usuario['id_rol']==10){
 ?>

    <!--<audio >
    <source src="alerta.mp3" type="audio/mp3" autoplay>
</audio>-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <script>
    $(document).ready(function() {
        var myAudio = document.createElement('audio');
        var myMessageAlert = "";
        myAudio.src = 'alerta.mp3';
        myAudio.addEventListener('ended', function() {
            alert(myMessageAlert);
        });

        function Myalert(message) {
            myAudio.play();
            myMessageAlert = message;
        }
        Myalert("Mensaje");

        function alert(message) {
            myAudio.play();
            myMessageAlert = message;
        }
        alert("Mensaje");
        swal({
            title: "NUEVA SOLICITUD DE LABORATORIO",
            type: "error",
            confirmButtonText: "ACEPTAR"
        }, function(isConfirm) {
            if (isConfirm) {
                window.location.href = "../sauxiliares/Laboratorio/sol_laboratorio.php";
            }
        });
    });
    </script>

    <?php } ?>
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
            <!-- <img src="dist/img/logo.jpg" alt="logo">-->
            <?php
        if ($usuario['id_rol'] == 10 || $usuario['id_rol'] == 12) {
            ?>

            <a href="menu_laboratorio.php" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                
                <!-- logo for regular state and mobile devices -->
                <?php
$resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
while($f = mysqli_fetch_array($resultado)){
       $id_simg=$f['id_simg'];
?>
                <center><span class="fondo"><img src="../configuracion/admin/img/<?php echo $f['img_base']?>"
                            alt="imgsistema" class="img-fluid" width="112"></span></center>
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
                <center><span class="fondo"><img src="../configuracion/admin/img/<?php echo $f['img_base']?>"
                            alt="imgsistema" class="img-fluid" width="112"></span></center>
                <?php
}
?>
            </a>
            <?php
        } else if ($usuario['id_rol'] == 1) {

            ?>
            <a href="menu_administrativo.php" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                
                <!-- logo for regular state and mobile devices -->
                <?php
$resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
while($f = mysqli_fetch_array($resultado)){
       $id_simg=$f['id_simg'];
?>
                <center><span class="fondo"><img src="../configuracion/admin/img/<?php echo $f['img_base']?>"
                            alt="imgsistema" class="img-fluid" width="112"></span></center>
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
                                    alt="User Image" />

                                <span class="hidden-xs"> <?php echo $usuario['papell']; ?>
                                    <?php echo $usuario['sapell']; ?></span>

                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle"
                                        alt="User Image" />
                                    <p>
                                        <?php echo $usuario['papell']; ?> <?php echo $usuario['sapell']; ?>

                                    </p>
                                </li>

                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-right">
                                        <a href="../cerrar_sesion.php" class="btn btn-default btn-flat">Cerrar
                                            sesión</a>
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

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <?php if ($usuario['id_rol'] != 12) { ?>
        <ul class="sidebar-menu">
            <li class="treeview">
                <a href="../sauxiliares/Laboratorio/sol_laboratorio.php">
                    <i class="fa fa-folder"></i> <font size="2">SOLICITUD DE ESTUDIOS<br>DE LABORATORIO</font>
                </a>
            </li>
            <li class="treeview">
                <a href="../sauxiliares/Laboratorio/sol_gabinete.php">
                    <i class="fa fa-folder"></i> <font size="2">SOLICITUD DE ESTUDIOS<br>DE GABINETE</font>
                </a>
            </li>
            <li class="treeview">
                <a href="../sauxiliares/Laboratorio/sol_patologia.php">
                    <i class="fa fa-folder"></i> <font size="2">SOLICITUD DE ESTUDIOS<br>DE PATOLOGÍA</font>
                </a>
            </li>
            <li class="treeview">
                <a href="../sauxiliares/Laboratorio/resultados_labo.php">
                    <i class="fa fa-folder"></i> <font size="2">CONSULTAR RESULTADOS<br>ESTUDIOS DE LABORATORIO</font>
                </a>
            </li>
            <li class="treeview">
                <a href="../sauxiliares/Laboratorio/resultados_gab.php">
                    <i class="fa fa-folder"></i> <font size="2">CONSULTAR RESULTADOS<br>ESTUDIOS DE GABINETE</font>
                </a>
            </li>
            <li class="treeview">
                <a href="../sauxiliares/Laboratorio/resultados_pato.php">
                    <i class="fa fa-folder"></i> <font size="2">CONSULTAR RESULTADOS<br>ESTUDIOS DE PATOLOGÍA</font>
                </a>
            </li>
            <?php if ($usuario['id_usua'] == 516 || $usuario['id_usua'] == 266 || $usuario['id_usua'] == 1) { ?>
            <li class="treeview">
                <a href="../sauxiliares/Laboratorio/cat_servicios.php">
                    <i class="fa fa-folder"></i> <font size="2">CATALOGO DE LABORATORIO</font>
                </a>
            </li>
            <?php } ?>
        </ul>
        <?php } ?>
    </section>
    <!-- /.sidebar -->
</aside>

        <!-- Right side column. Contains the navbar and content of the page -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            </ul>
            <li class="breadcrumb-item active" aria-current="page"><STRONG>
                    <h4>LABORATORIO</h4>
                </STRONG></li>
            </ul>
            </nav>

            <!-- Main content -->
            <section class="content">


                <section class="content container-fluid">
                    <div class="content box">
                        <!-- CONTENIDOO -->

                        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 25px;">
                            <tr><strong>
                                    <center>ESTUDIOS DE LABORATORIO PENDIENTES </center>
                                </strong>
                        </div><br>

                        <div class="form-group">
                            <input type="text" class="form-control pull-right" style="width:25%" id="search"
                                placeholder="Buscar...">
                        </div>
                        <br></br>

                        <div class="table-responsive">
                            <!--<table id="myTable" class="table table-striped table-hover">-->

                            <table class="table table-bordered table-striped" id="mytable">

                                <thead class="thead" style="background-color: #2b2d7f; color:white;">
                                    <tr>
                                        <th>Habitación</th>
                                        <th>Paciente</th>
                                        <th>Médico tratante</th>
                                        <th>Fecha solicitud</th>
                                        <th>Solicitante</th>
                                        <th>Estudio(s)</th>
                                        <th>Solicitud de estudio</th>
                                        <th>Subir Resultado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php   

                            include "../conexionbd.php";

                            $query = "SELECT * FROM notificaciones_labo n, reg_usuarios u where n.realizado = 'NO' and n.id_usua = u.id_usua AND activo = 'SI' order by fecha_ord DESC ";
                            $result = $conexion->query($query);
                            $no = 1;

                            while ($row = $result->fetch_assoc()) {
                                $habi = $row['habitacion'];
                                $id_atencion = $row['id_atencion'];
                                 
                            if ($habi <> 0)  {
                                $query_pac = "SELECT * FROM dat_ingreso d, paciente p where d.id_atencion = $id_atencion and d.Id_exp = p.Id_exp";
                                $result_pac = $conexion->query($query_pac);

                                while ($row_pac = $result_pac->fetch_assoc()) {
                                    $pac = $row_pac['papell'] . ' ' . $row_pac['sapell'] . ' ' . $row_pac['nom_pac'];
                                    $fecha_orden = date_create($row['fecha_ord']);
                                    $tratante = $row_pac['id_usua'];
                                }
                                $sql_reg_usrt = "SELECT * from reg_usuarios where id_usua=$tratante";
                                $result_reg_usrt = $conexion->query($sql_reg_usrt);
                                while ($row_reg_usrt = $result_reg_usrt->fetch_assoc()) {
                                    $prefijo = $row_reg_usrt['pre'];
                                    $nom_tratante = $row_reg_usrt['papell'];
                                    $cedula = $row_reg_usrt['cedp'];
                                }
                                echo '<tr>'  
                                    . '<td class="fondosan" style="background-color: #FF0000; color: white;">' . $row['habitacion'] . '</td>'
                                    . '<td class="fondosan" style="background-color: #FF0000; color: white;">' . $pac . '</td>'
                                    . '<td class="fondosan" style="background-color: #FF0000; color: white;">' . $prefijo . '. ' . $nom_tratante . '   </td>'
                                    . '<td class="fondosan" style="background-color: #FF0000; color: white;">' . $row['fecha_ord'] . '</td>'
                                    . '<td class="fondosan" style="background-color: #FF0000; color: white;">' . $row['papell'] . ' ' . $row['sapell'] . ' ' . $row['nombre'] . '</td>'
                                    . '<td class="fondosan" style="background-color: #FF0000; color: white;">' . $row['sol_estudios'].'/ '. $row['det_labo'] . '</td>'; 
                                    
                                echo '<td class="fondosan" style="background-color: #FF0000; color: white;"    
                                <center><a href="../sauxiliares/Laboratorio/pdf_solicitud_estu.php?id_atencion='.$row['id_atencion'].'&notid='.$row['not_id'].'&medico='.$row['papell'] . ' ' . $row['sapell'] . ' ' . $row['nombre'].'&paciente='.$pac.'&tipo='.$row['sol_estudios'].'" target="_blank" ><button type="button" class="btn btn-success"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button></td></center>'
                                    . '</td>'
                                    ;

                                echo '<td class="fondosan" style="background-color: #FF0000; color: white;"><center>'
                                    . ' <a href="../sauxiliares/Laboratorio/subir_resultado.php?not_id=' . $row['not_id'] . '" title="Editar datos" class="btn btn-success "><span class="fa fa-cloud-upload" aria-hidden="true"></span></a>';
                                echo '</center></td></tr>';
                                $no++;
                            }   
                    }
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