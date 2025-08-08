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
if (!($usuario['id_rol'] == 7 || $usuario['id_rol'] == 5 || $usuario['id_rol'] == 1 || $usuario['id_rol'] == 9 || $usuario['id_rol'] == 3 || $usuario['id_rol'] == 4)) {
    session_unset();
    session_destroy();
    // echo "<script>window.Location='../index.php';</script>";
    header('Location: ../index.php');
}

//$rol=$usuario['id_rol'];

$resultado1 = $conexion->query("SELECT * FROM cart order by cart_id DESC limit 1" ) or die($conexion->error);
        while ($f1 = mysqli_fetch_array($resultado1)) {
         $cart_id=$f1['cart_id'];
        }
            if(isset($cart_id)&& $usuario['id_rol']==7){
 ?>

<!--<audio >
    <source src="alerta.mp3" type="audio/mp3" autoplay>
</audio>-->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <script>
$(document).ready(function() {
 var myAudio= document.createElement('audio');
 var myMessageAlert = "";
 myAudio.src = 'alerta.mp3';
 myAudio.addEventListener('ended', function(){
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
                            title: "SURTIR VALES DE FARMACIA", 
                            type: "error",
                            confirmButtonText: "ACEPTAR"
                        }, function(isConfirm) { 
                            if (isConfirm) {
                                window.location.href = "../sauxiliares/Farmacia/order.php";
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

$resultado1 = $conexion->query("SELECT * FROM cart order by cart_id DESC limit 1" ) or die($conexion->error);
        while ($f1 = mysqli_fetch_array($resultado1)) {
         $cart_id=$f1['cart_id'];
        }
        if(isset($cart_id)&& $rol!=5 && $rol!=12 && $rol!=7 && $rol!=3){
 ?>
<!--<audio >
    <source src="alerta.mp3" type="audio/mp3" autoplay>
</audio>-->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <script>
$(document).ready(function() {
 var myAudio= document.createElement('audio');
 var myMessageAlert = "";
 myAudio.src = 'alerta.mp3';
 myAudio.addEventListener('ended', function(){
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
                            title: "NUEVA SOLICITUD DE FARMACIA", 
                            type: "error",
                            confirmButtonText: "ACEPTAR"
                        }, function(isConfirm) { 
                            if (isConfirm) {
                                window.location.href = "../sauxiliares/Farmacia/order.php";
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
         @media screen and (min-width: 320px) and (max-width: 980px){
    .content{
        width:300px;
       margin-left:-0px;
   }
.card-img-top{
 width:70px;
 height: auto;
      }
      
      h3{
          font-size:10px;
      }

.CC{
top:-115px;    
}

.SAL{
   top:-186px;  
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
            if ($usuario['id_rol'] == 7) {
            ?>

                <a href="menu_farmacia.php" class="logo">
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
            } else if ($usuario['id_rol'] == 4) {

            ?>
                <a href="menu_sauxiliares.php" class="logo">
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
            else if ($usuario['id_rol'] == 5) {

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
            <center><span class="fondo"><img src="../configuracion/admin/img/<?php echo $f['img_base']?>" alt="imgsistema" class="img-fluid" width="112"></span></center>
          <?php
}
?>
                </a>
                <?php
            } else if ($usuario['id_rol'] == 3) {

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
                <?php } elseif($usuario['id_rol'] == 9) { ?>
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
                                <span class="hidden-xs"> <?php echo $usuario['papell']; ?> <?php echo $usuario['sapell']; ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">

                                    <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle" alt="User Image">

                                    <p>
                                        <?php echo $usuario['papell']; ?> <?php echo $usuario['sapell']; ?>

                                    </p>
                                </li>

                                <!-- Menu Footer-->
                                <li class="user-footer">    
                                    <div class="pull-left">
                                           <?php
                                    if ($usuario['id_rol'] == 4) {?>
                                        <a href="../sauxiliares/editar_perfil/editar_perfil_saux.php?id_usua=<?php echo $usuario['id_usua'];?>" class="btn btn-default btn-flat">MIS DATOS</a>
                                     <?php
                                    }else  {?>
            
                                        <a href="../sauxiliares/editar_perfil/editar_perfil_farma.php?id_usua=<?php echo $usuario['id_usua'];?>" class="btn btn-default btn-flat">MIS DATOS</a>
                                    <?php }?>
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

                <!-- sidebar menu: : style can be found in sidebar.less -->
                <?php if($usuario['id_rol']==3){ ?>
                                    <ul class="sidebar-menu">

                    <li class="treeview">
                        <a href="../sauxiliares/Farmacia/salidas.php">
                            <i class="fa fa-folder"></i> <span>SALIDAS DE FARMACIA</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>

                    </li>
                     
                </ul>
                <?php }else{ ?>
                <ul class="sidebar-menu">
                   
                    <li class=" treeview">
                        <a href="../sauxiliares/Farmacia/order.php">
                            <i class="fa fa-folder"></i> <span>SURTIR MEDICAMENTOS</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>

                    </li>
                    <li class=" treeview">
                        <a href="../sauxiliares/Farmacia/orderqx.php">
                            <i class="fa fa-folder"></i> <span>REGISTRAR MEDICAMENTOS <BR>Y MATERIALES (FARMACIA)</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>

                    </li>
                    <li class=" treeview">
                        <a href="../sauxiliares/Farmacia/lista_productos.php">
                            <i class="fa fa-folder"></i>  <span>CATÁLOGO DE MEDICAMENTOS<span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>

                    </li>
           
                    <li class=" treeview">
                        <a href="../sauxiliares/Farmacia/inventario.php">
                            <i class="fa fa-folder"></i> <span>DETALLE DE INVENTARIO</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>

                    </li>
               
                    <li class="treeview">
                        <a href="../sauxiliares/Farmacia/devoluciones.php">
                            <i class="fa fa-folder"></i> <span>CONFIRMAR <br> DEVOLUCIONES</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>

                    </li>
                    <li class="treeview">
                        <a href="../sauxiliares/Farmacia/pedir_almacen.php">
                            <i class="fa fa-folder"></i> <span>SOLICITAR AL ALMACÉN</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>

                    </li>
                    <li class="treeview">
                        <a href="../sauxiliares/Farmacia/confirmar_envio.php">
                            <i class="fa fa-folder"></i> <span>CONFIRMAR DE RECIBIDO</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>

                    </li>
                    <li class="treeview">
                        <a href="../sauxiliares/Farmacia/salidas.php">
                            <i class="fa fa-folder"></i> <span>SALIDAS DE FARMACIA</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>

                    </li>
                    <li class="treeview">
                        <a href="../sauxiliares/Farmacia/order_indica.php">
                            <i class="fa fa-folder"></i> <span>INDICACIONES MÉDICAS</span>
                            <i class="fa fa-angle-left pull-right"></i>
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
                            <h4>FARMACIA</h4>
                        </STRONG></li>
                </ol>
            </nav>

            <!-- Main content -->
            <?php if($usuario['id_rol']==3){ ?>
                 <section class="content">
                <section class="content container-fluid">
                    <div class="content box">
                        <!-- CONTENIDOO -->
                        <div class="row">
                            <div class="col-lg-4 col-xs-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <center>
                                                <a title="Confirmar Recibido" href="../sauxiliares/Farmacia/salidas.php"><img class="card-img-top" src="../img/sales.png" alt="Confirmar Recibido" height="150" width="190" /></a>
                                            </center>
                                            <center>
                                                <h3>SALIDAS DE FARMACIA FARMACIA</h3>
                                            </center>

                                        </div>

                                    </div>
                                </div>
                                <div class="col-lg-4 col-xs-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <center>
                                                <a title="Confirmar Recibido" href="../sauxiliares/Ceye/salidas.php"><img class="card-img-top" src="../img/ceye.jpg" alt="Confirmar Recibido" height="150" width="190" /></a>
                                            </center>
                                            <center>
                                                <h3>SALIDAS DE FARMACIA CEYE (QUIRÓFANO)</h3>
                                            </center>

                                        </div>

                                    </div>
                                </div>
                        </div>




                        </div>


                </section><!-- /.content -->
            <?php }else{?>
            <section class="content">
                <section class="content container-fluid">
                    <div class="content box">
                        <!-- CONTENIDOO -->
                        <div class="row">
                           
                            <div class="col-lg-4 col-xs-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <center>
                                            <a title="Surtir Medicamentos" href="../sauxiliares/Farmacia/order.php"><img class="card-img-top" src="../img/surtir.jpg" alt="Surtir Medicamentos" height="150" width="200" /></a>
                                        </center>
                                        <center>
                                            <h3>SURTIR MEDICAMENTOS</h3>
                                        </center>

                                    </div>

                                </div>

                            </div>
                            <div class="col-lg-4 col-xs-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <center>
                                            <a title="Solicitar Medicamentos" href="../sauxiliares/Farmacia/orderqx.php"><img class="card-img-top" src="../img/surtir2.jpg" alt="Solicitar Medicamentos" height="150" width="200" /></a>
                                        </center>
                                        <center>
                                            <h3>REGISTRAR MEDICAMENTOS <BR>Y MATERIALES (FARMACIA)</h3>
                                        </center>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-xs-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <center>
                                            <a title="Lista Médicamentos" href="../sauxiliares/Farmacia/lista_productos.php"><img class="card-img-top" src="../img/lista.png" alt="lista de medicamnetos" height="150" width="200" /></a>
                                        </center>
                                        <center>
                                            <h3>CATÁLOGO DE MEDICAMENTOS</h3>
                                        </center>

                                    </div>
                                </div>

                            </div>
                            </div>
                            <div class="row">
                            <div class="col-lg-4 col-xs-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                            <center>
                                                <a title="Existencias" href="../sauxiliares/Farmacia/inventario.php"><img class="card-img-top" src="../img/inventario.jpg" alt="Existencias" height="150" width="200" /></a>
                                            </center>
                                            <center>
                                                <h3>DETALLE DE INVENTARIO</h3>
                                            </center>

                                    </div>

                                </div>
                            </div>
                            

                            <div class="col-lg-4 col-xs-6 CC">
                                <div class="row">
                                    <div class="col-lg-12">
                                            <center>
                                                <a title"Devoluciones" href="../sauxiliares/Farmacia/devoluciones.php"><img class="card-img-top" src="../img/dev.jpg" alt="devolucion" height="150" width="200" /></a>
                                            </center>                                            <center>
                                                <h3>CONFIRMAR <br>DEVOLUCIONES</h3>
                                            </center>

                                     </div>

                                 </div>
                            </div>

                            <div class="col-lg-4 col-xs-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                            <center>
                                                <a title="Pedir a Almacen" href="../sauxiliares/Farmacia/pedir_almacen.php"><img class="card-img-top" src="../img/almacen_central.png" alt="Pedir a Almacen" height="150" width="200" /></a>
                                            </center>
                                            <center>
                                                <h3>SOLICITAR AL ALMACÉN</h3>
                                            </center>

                                    </div>

                                </div>
                            </div>
                            </div>
                            <div class="row">
                            <div class="col-lg-4 col-xs-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                            <center>
                                                <a title="Confirmar Recibido" href="../sauxiliares/Farmacia/confirmar_envio.php"><img class="card-img-top" src="../img/surtir_almacen.jpg" alt="Confirmar Recibido" height="150" width="200" /></a>
                                            </center>
                                            <center>
                                                <h3>CONFIRMAR DE RECIBIDO</h3>
                                            </center>

                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-4 col-xs-6 SAL">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <center>
                                                <a title="Salidas de Farmacia" href="../sauxiliares/Farmacia/salidas.php"><img class="card-img-top" src="../img/sales.png" alt="Confirmar Recibido" height="150" width="200" /></a>
                                            </center>
                                            <center>
                                                <h3>SALIDAS DE FARMACIA</h3>
                                            </center>

                                      </div>

                                </div>
                             </div>
                              <div class="col-lg-4 col-xs-6 SAL">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <center>
                                                <a title="Consultar indicaciones médicas" href="../sauxiliares/Farmacia/order_indica.php"><img class="card-img-top" src="../img/indicaciones.jpg" alt="Confirmar Recibido" height="150" width="200" /></a>
                                            </center>
                                            <center>
                                                <h3>INDICACIONES MÉDICAS</h3>
                                            </center>

                                      </div>

                                </div>
                             </div>
                        </div>    
                        </div>




                        </div>


                </section><!-- /.content -->
            <?php } ?>
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
                            <div class="col-lg-4 col-xs-5>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <center>
                                            <a title="Surtir Medicamentos" href="../sauxiliares/Farmacia/order.php"><img class="card-img-top" src="../img/surtir.png" alt="Surtir Medicamentos" height="150" width="160" /></a>
                                        </center>
                                        <center>
                                            <h3>SURTIR MEDICAMENTOS</h3>
                                        </center>

                                    </div>
                                    <div class="col-lg-1"></div>
                                </div>

                            </div>
                             <div class="col-lg-4 col-xs-5">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <center>
                                            <a title="Solicitar Medicamentos" href="../sauxiliares/Farmacia/orderqx.php"><img class="card-img-top" src="../img/surtir2.jpg" alt="Solicitar Medicamentos" height="1600" width="160" /></a>
                                        </center>
                                        <center>
                                            <h3>REGISTRAR MEDICAMENTOS <BR>Y MATERIALES (FARMACIA)</h3>
                                        </center>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-xs-5">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <center>
                                            <a title="Lista Médicamentos" href="../sauxiliares/Farmacia/lista_productos.php"><img class="card-img-top" src="../img/lista.png" alt="lista de medicamnetos" height="150" width="160" /></a>
                                        </center>
                                        <center>
                                            <h3>CATÁLOGO DE MEDICAMENTOS</h3>
                                        </center>

                                    </div>
                                    <div class="col-lg-1"></div>
                                </div>

                            </div>
                            <div class="col-lg-4 col-xs-5">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <center>
                                            <a title="Pérfil del Médicamento" href="../sauxiliares/Farmacia/perfilmedicamento.php"><img class="card-img-top" src="../img/liberar.jpg" alt="perfil del medicamento" height="150" width="160" /></a>
                                        </center>
                                        <center>
                                            <h3>DETALLE DE INVENTARIO &nbsp; </h3>
                                        </center>

                                    </div>
                                    <div class="col-lg-1"></div>
                                </div>

                            </div>
                            <div class="row">
                               
                                <div class="col-lg-4 col-xs-5">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <center>
                                                <a title="Medicamentos Caducados" href="../sauxiliares/Farmacia/caducado.php"><img class="card-img-top" src="../img/caducado.webp" alt="medicamentos caducados" height="150" width="160" /></a>
                                            </center>
                                            <center>
                                                <h3>REVISAR CADUCIDAD</h3>
                                            </center>

                                        </div>
                                        <div class="col-lg-1"></div>
                                    </div>

                                </div>

                                <div class="col-lg-4 col-xs-5">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <center>
                                                <a title="Devoluciones" href="../sauxiliares/Farmacia/devoluciones.php"><img class="card-img-top" src="../img/dev.png" alt="devolucion" height="150" width="160" /></a>
                                            </center>
                                            <center>
                                                <h3>CONFIRMAR <br>DEVOLUCIONES</h3>
                                            </center>

                                        </div>
                                        <div class="col-lg-1"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

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