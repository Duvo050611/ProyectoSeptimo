<?php
require_once '../conexionbd.php';
$conexion = ConexionBD::getInstancia()->getConexion();
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
    :root {
        --primary-blue: #2563eb;
        --primary-pink: #ec4899;
        --primary-green: #10b981;
        --primary-orange: #f59e0b;
        --primary-cyan: #06b6d4;
        --primary-purple: #8b5cf6;
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
        --shadow-md: 0 4px 16px rgba(0,0,0,0.12);
        --shadow-lg: 0 8px 32px rgba(0,0,0,0.16);
    }

    body {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%) !important;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
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

    .sidebar-menu > li > a {
        color: #ffffff !important;
        border-left: 3px solid transparent;
        transition: all 0.3s ease;
        font-size: 14px !important;
        padding: 12px 15px !important;
    }

    .sidebar-menu > li > a:hover,
    .sidebar-menu > li.active > a {
        background: rgba(64, 224, 255, 0.1) !important;
        border-left: 3px solid #40E0FF !important;
        color: #40E0FF !important;
    }

    .user-panel {
        border-bottom: 1px solid rgba(64, 224, 255, 0.2);
    }

    .user-panel .info {
        color: #ffffff !important;
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

    /* Contenedor principal */
    .content.box {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
    }

    /* Sección de alertas y encabezado */
    .thead {
        background: linear-gradient(135deg, #0a0e1a 0%, #0f172a 100%) !important;
        border: 2px solid #1e3a8a !important;
        border-radius: 20px !important;
        padding: 25px 30px !important;
        color: #e2e8f0 !important;
        font-size: 1.4rem !important;
        font-weight: 600 !important;
        text-align: center !important;
        margin-bottom: 30px !important;
        box-shadow: 0 10px 30px rgba(30, 58, 138, 0.3);
        position: relative;
        overflow: hidden;
    }

    .thead::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        100% { left: 100%; }
    }

    /* Barra de búsqueda mejorada */
    .form-control {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 25px !important;
        color: #ffffff !important;
        padding: 12px 20px !important;
        font-size: 14px !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 4px 15px rgba(64, 224, 255, 0.1);
    }

    .form-control:focus {
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border-color: #00D9FF !important;
        box-shadow: 0 0 0 0.2rem rgba(64, 224, 255, 0.25) !important;
        transform: translateY(-2px);
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.6) !important;
    }

    /* Tabla con diseño moderno */
    .table-responsive {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.95) 0%, rgba(15, 52, 96, 0.95) 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 20px !important;
        padding: 20px !important;
        box-shadow: 0 15px 40px rgba(64, 224, 255, 0.15);
        overflow: hidden;
        position: relative;
    }

    .table-responsive::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #40E0FF, #00D9FF, #40E0FF);
        animation: gradient-flow 3s ease infinite;
        background-size: 200% 100%;
    }

    @keyframes gradient-flow {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    #mytable {
        margin-bottom: 0 !important;
        background: transparent !important;
    }

    #mytable thead {
        background: linear-gradient(135deg, #0a0e1a 0%, #0f172a 100%) !important;
        border-bottom: 2px solid #1e3a8a !important;
    }

    #mytable thead th {
        color: #e2e8f0 !important;
        font-weight: 600 !important;
        border: none !important;
        padding: 15px 12px !important;
        font-size: 14px !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
    }

    #mytable thead th::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 2px;
        background: #3b82f6;
        transition: width 0.3s ease;
    }

    #mytable thead th:hover::after {
        width: 80%;
    }

    #mytable tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid rgba(64, 224, 255, 0.1) !important;
    }

    #mytable tbody tr:hover {
        background: rgba(64, 224, 255, 0.05) !important;
        transform: translateX(5px);
    }

    #mytable tbody td {
        color: #e2e8f0 !important;
        padding: 12px !important;
        vertical-align: middle !important;
        border-color: rgba(64, 224, 255, 0.1) !important;
    }

    /* Celdas especiales */
    .fondosan {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 58, 138, 0.8) 100%) !important;
        color: #e2e8f0 !important;
        border-left: 3px solid #3b82f6 !important;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(59, 130, 246, 0.3) !important;
        border-radius: 8px !important;
    }

    .fondosan::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
        animation: warning-shimmer 2s infinite;
    }

    @keyframes warning-shimmer {
        100% { left: 100%; }
    }

    /* Botones mejorados */
    .btn {
        border-radius: 25px !important;
        padding: 8px 20px !important;
        font-weight: 600 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease !important;
        border: 2px solid transparent !important;
        position: relative;
        overflow: hidden;
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        color: #ffffff !important;
        border-color: #10b981 !important;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        border-color: #10b981 !important;
    }

    .btn-success:active {
        transform: translateY(0) scale(1);
    }

    /* Íconos en botones */
    .btn i {
        margin-right: 5px;
        transition: transform 0.3s ease;
    }

    .btn:hover i {
        transform: scale(1.2);
    }

    /* Footer */
    .main-footer {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-top: 2px solid #40E0FF !important;
        color: #ffffff !important;
        box-shadow: 0 -4px 20px rgba(64, 224, 255, 0.2);
        margin-top: 40px !important;
    }

    /* Notificaciones y SweetAlert personalizado */
    .swal-modal {
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 20px !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.9),
                    0 0 40px rgba(64, 224, 255, 0.4);
    }

    .swal-title {
        color: #ffffff !important;
        font-weight: 600 !important;
        text-shadow: 0 0 15px rgba(64, 224, 255, 0.5);
    }

    .swal-text {
        color: #e2e8f0 !important;
    }

    .swal-button {
        background: linear-gradient(135deg, #40E0FF 0%, #00D9FF 100%) !important;
        border-radius: 25px !important;
        padding: 10px 30px !important;
        font-weight: 600 !important;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: none !important;
        transition: all 0.3s ease !important;
    }

    .swal-button:hover {
        background: linear-gradient(135deg, #00D9FF 0%, #40E0FF 100%) !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(64, 224, 255, 0.4);
    }

    /* Scrollbar personalizado */
    ::-webkit-scrollbar {
        width: 10px;
    }

    ::-webkit-scrollbar-track {
        background: #0a0a0a;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #40E0FF 0%, #0f3460 100%);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #00D9FF 0%, #40E0FF 100%);
    }

    /* Estilos para dropdown */
    .dropdown-menu {
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 15px !important;
        box-shadow: 0 10px 30px rgba(64, 224, 255, 0.2);
    }

    .dropdown-menu > li > a {
        color: #ffffff !important;
        transition: all 0.3s ease;
        padding: 10px 20px !important;
    }

    .dropdown-menu > li > a:hover {
        background: rgba(64, 224, 255, 0.1) !important;
        color: #40E0FF !important;
    }

    /* User panel en navbar */
    .user-image {
        border: 2px solid #40E0FF !important;
        box-shadow: 0 0 10px rgba(64, 224, 255, 0.3);
    }

    .user-header {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-bottom: 2px solid #40E0FF !important;
    }

    .user-footer {
        background: rgba(15, 52, 96, 0.5) !important;
        border-top: 1px solid rgba(64, 224, 255, 0.2) !important;
    }

    /* Responsive */
    @media screen and (max-width: 768px) {
        .breadcrumb {
            padding: 15px 20px !important;
        }
        
        .breadcrumb h4 {
            font-size: 1.1rem !important;
            letter-spacing: 1px;
        }
        
        .thead {
            padding: 20px 15px !important;
            font-size: 1.1rem !important;
        }
        
        .form-control {
            width: 100% !important;
        }
        
        .table-responsive {
            padding: 10px !important;
            border-radius: 15px !important;
        }
        
        #mytable thead th {
            padding: 10px 8px !important;
            font-size: 12px !important;
        }
        
        #mytable tbody td {
            padding: 8px !important;
            font-size: 13px !important;
        }
    }

    @media screen and (max-width: 576px) {
        .breadcrumb h4 {
            font-size: 1rem !important;
        }
        
        .thead {
            font-size: 1rem !important;
            padding: 15px 10px !important;
        }
        
        .btn {
            padding: 6px 15px !important;
            font-size: 12px !important;
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

    .table-responsive,
    .thead,
    .form-group {
        animation: fadeInUp 0.6s ease-out backwards;
    }

    .table-responsive { animation-delay: 0.2s; }
    .thead { animation-delay: 0.1s; }
    .form-group { animation-delay: 0.3s; }

    /* Efecto de brillo en hover para filas */
    #mytable tbody tr {
        position: relative;
    }

    #mytable tbody tr::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(64, 224, 255, 0.05), transparent);
        transition: left 0.5s ease;
    }

    #mytable tbody tr:hover::after {
        left: 100%;
    }

    /* Estilos para el contenedor todo-container */
    .todo-container {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%);
        border: 2px solid #40E0FF;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(64, 224, 255, 0.1);
    }

    .status {
        background: linear-gradient(135deg, rgba(26, 26, 46, 0.8) 0%, rgba(22, 33, 62, 0.8) 100%);
        border: 1px solid rgba(64, 224, 255, 0.2);
        border-radius: 15px;
    }

    .status h4 {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
        border-radius: 15px 15px 0 0;
        color: #40E0FF;
        font-weight: 600;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.3);
    }

    /* Estilos para el toggle del sidebar */
    .sidebar-toggle {
        color: #40E0FF !important;
    }

    .sidebar-toggle:hover {
        background: rgba(64, 224, 255, 0.1) !important;
    }
    
    /* Color adicional para hover en filas */
    #mytable tbody tr:hover .fondosan {
        background: linear-gradient(135deg, rgba(30, 58, 138, 0.9) 0%, rgba(59, 130, 246, 0.8) 100%) !important;
        color: #ffffff !important;
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

                    <div class="thead" style="background: linear-gradient(135deg, #0a0e1a 0%, #0f172a 100%); border: 2px solid #1e3a8a; color: #e2e8f0; font-size: 25px; box-shadow: 0 10px 30px rgba(30, 58, 138, 0.3);">
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

                            <thead class="thead" style="background: linear-gradient(135deg, #0a0e1a 0%, #0f172a 100%); border: 2px solid #1e3a8a; color: #e2e8f0; box-shadow: 0 5px 15px rgba(30, 58, 138, 0.2);">
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
                            // CORRECCIÓN: Se eliminó el include duplicado de conexionbd.php
                            // La conexión ya está disponible desde el inicio del archivo

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
                                            . '<td class="fondosan" style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 58, 138, 0.8) 100%); color: #e2e8f0; border-left: 3px solid #3b82f6 !important;">' . $row['habitacion'] . '</td>'
                                            . '<td class="fondosan" style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 58, 138, 0.8) 100%); color: #e2e8f0; border-left: 3px solid #3b82f6 !important;">' . $pac . '</td>'
                                            . '<td class="fondosan" style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 58, 138, 0.8) 100%); color: #e2e8f0; border-left: 3px solid #3b82f6 !important;">' . $prefijo . '. ' . $nom_tratante . '   </td>'
                                            . '<td class="fondosan" style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 58, 138, 0.8) 100%); color: #e2e8f0; border-left: 3px solid #3b82f6 !important;">' . $row['fecha_ord'] . '</td>'
                                            . '<td class="fondosan" style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 58, 138, 0.8) 100%); color: #e2e8f0; border-left: 3px solid #3b82f6 !important;">' . $row['papell'] . ' ' . $row['sapell'] . ' ' . $row['nombre'] . '</td>'
                                            . '<td class="fondosan" style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 58, 138, 0.8) 100%); color: #e2e8f0; border-left: 3px solid #3b82f6 !important;">' . $row['sol_estudios'].'/ '. $row['det_labo'] . '</td>';

                                    echo '<td class="fondosan" style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 58, 138, 0.8) 100%); color: #e2e8f0; border-left: 3px solid #3b82f6 !important;"
                                <center><a href="../sauxiliares/Laboratorio/pdf_solicitud_estu.php?id_atencion='.$row['id_atencion'].'&notid='.$row['not_id'].'&medico='.$row['papell'] . ' ' . $row['sapell'] . ' ' . $row['nombre'].'&paciente='.$pac.'&tipo='.$row['sol_estudios'].'" target="_blank" ><button type="button" class="btn btn-success"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button></td></center>'
                                            . '</td>'
                                    ;

                                    echo '<td class="fondosan" style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 58, 138, 0.8) 100%); color: #e2e8f0; border-left: 3px solid #3b82f6 !important;"><center>'
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