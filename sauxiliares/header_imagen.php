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
}
$usuario1 = $_SESSION['login'];
$resultado = $conexion->query("SELECT * FROM reg_usuarios WHERE id_usua='" . $usuario . "'") or die($conexion->error);

if (!($usuario1['id_rol'] == 9 || $usuario1['id_rol'] == 5 || $usuario1['id_rol'] == 2 || $usuario1['id_rol'] == 12 || $usuario1['id_rol'] == 10)) {
    session_unset();
    session_destroy();
    // echo "<script>window.Location='../../index.php';</script>";
    header('Location: ../../index.php');
}
?>
<!DOCTYPE html>
<html>

<head>
     <title>INEO Metepec</title>
    <link rel="icon" href="../img/icono.png">
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


 <style>
    * {
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #0a0a0a 100%) !important;
        font-family: 'Roboto', sans-serif !important;
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

    /* Header table */
    .headt {
        width: 100%;
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
    }

    .sidebar-menu > li > a:hover,
    .sidebar-menu > li.active > a {
        background: rgba(64, 224, 255, 0.1) !important;
        border-left: 3px solid #40E0FF !important;
        color: #40E0FF !important;
    }

    /* Treeview - tamaño de fuente */
    .treeview {
        font-size: 13.3px;
    }

    .treeview-menu > li > a {
        color: rgba(255, 255, 255, 0.9) !important;
        transition: all 0.3s ease;
    }

    .treeview-menu > li > a:hover {
        color: #40E0FF !important;
        background: rgba(64, 224, 255, 0.05) !important;
    }

    /* Separador del menú treeview */
    .treeview-menu-separator {
        padding: 10px 15px;
        font-weight: bold;
        color: #40E0FF !important;
        cursor: default;
        background: linear-gradient(135deg, rgba(64, 224, 255, 0.1) 0%, rgba(64, 224, 255, 0.05) 100%) !important;
        border-top: 1px solid rgba(64, 224, 255, 0.3);
        border-bottom: 1px solid rgba(64, 224, 255, 0.3);
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
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

    /* Dropdown menu */
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
        transition: all 0.3s ease;
    }

    .navbar a:hover,
    .dropdwn:hover .dropbtn,
    .dropbtn:focus {
        background-color: rgba(64, 224, 255, 0.2);
    }

    .dropdwn-content {
        display: none;
        position: absolute;
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(64, 224, 255, 0.3);
        z-index: 1;
        border: 1px solid #40E0FF;
        border-radius: 10px;
    }

    .dropdwn-content a {
        float: none;
        color: #ffffff !important;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        text-align: left;
        transition: all 0.3s ease;
    }

    .dropdwn-content a:hover {
        background: rgba(64, 224, 255, 0.2) !important;
        color: #40E0FF !important;
    }

    .dropdwn:hover .dropdwn-content {
        display: block;
    }

    .show {
        display: block;
    }

    /* Breadcrumb mejorado */
    .breadcrumb {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 20px !important;
        padding: 25px !important;
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
        font-weight: 700 !important;
        margin: 0;
        font-size: 28px !important;
        text-transform: uppercase;
        letter-spacing: 2px;
        text-shadow: 0 0 20px rgba(64, 224, 255, 0.5);
        position: relative;
        z-index: 1;
    }

    /* Contenedor principal */
    .content {
        padding: 20px;
    }

    .container-fluid {
        max-width: 1200px;
        margin: 0 auto;
    }

    .container {
        max-width: 1140px;
        margin: 0 auto;
        padding: 0 15px;
    }

    /* Contenedor de farmacia */
    .farmacia-container {
        padding: 30px;
        background: transparent !important;
        min-height: 100vh;
        margin: 0;
    }

    /* Row y columnas */
    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }

    .col, .col-1, .col-2, .col-3, .col-4, .col-5, .col-6,
    .col-7, .col-8, .col-9, .col-10, .col-11, .col-12,
    .col-sm, .col-md, .col-lg, .col-xl {
        position: relative;
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
    }

    /* TABLAS CYBERPUNK */
    table {
        width: 100%;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%);
        border-collapse: separate;
        border-spacing: 0;
        border: 2px solid rgba(64, 224, 255, 0.3);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
    }

    .table {
        width: 100%;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%);
        border-collapse: separate;
        border-spacing: 0;
        border: 2px solid rgba(64, 224, 255, 0.3);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
    }

    .table-bordered {
        border: 2px solid rgba(64, 224, 255, 0.4);
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background: rgba(64, 224, 255, 0.05);
    }

    .table-hover tbody tr:hover {
        background: rgba(64, 224, 255, 0.1);
        transform: scale(1.01);
        transition: all 0.3s ease;
    }

    thead {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
        border-bottom: 2px solid #40E0FF;
    }

    thead th {
        color: #40E0FF !important;
        font-weight: 700;
        text-transform: uppercase;
        padding: 15px !important;
        border: none !important;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
        font-size: 14px;
        letter-spacing: 1px;
    }

    tbody {
        color: #ffffff !important;
    }

    tbody td {
        padding: 12px 15px !important;
        border: 1px solid rgba(64, 224, 255, 0.2) !important;
        vertical-align: middle;
    }

    tbody tr {
        transition: all 0.3s ease;
    }

    th, td {
        padding: 12px 15px !important;
        text-align: left;
        border: 1px solid rgba(64, 224, 255, 0.2) !important;
    }

    /* Celdas especiales */
    td.fondosan {
        background: linear-gradient(135deg, #5c1a1a 0%, #3a0f0f 100%) !important;
        color: #ffffff !important;
        border: 1px solid rgba(239, 68, 68, 0.5) !important;
        font-weight: 600;
        text-shadow: 0 0 10px rgba(239, 68, 68, 0.5);
    }

    /* FORMULARIOS CYBERPUNK */
    .form-control {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 10px !important;
        color: #ffffff !important;
        padding: 10px 15px !important;
        transition: all 0.3s ease !important;
    }

    .form-control:focus {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border-color: #40E0FF !important;
        box-shadow: 0 0 20px rgba(64, 224, 255, 0.4) !important;
        color: #ffffff !important;
        outline: none !important;
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.5) !important;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        color: #ffffff !important;
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="number"],
    input[type="tel"],
    input[type="date"],
    input[type="time"],
    input[type="datetime-local"],
    textarea,
    select {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 10px !important;
        color: #ffffff !important;
        padding: 10px 15px !important;
        transition: all 0.3s ease !important;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus,
    input[type="number"]:focus,
    input[type="tel"]:focus,
    input[type="date"]:focus,
    input[type="time"]:focus,
    input[type="datetime-local"]:focus,
    textarea:focus,
    select:focus {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border-color: #40E0FF !important;
        box-shadow: 0 0 20px rgba(64, 224, 255, 0.4) !important;
        outline: none !important;
    }

    select option {
        background: #16213e !important;
        color: #ffffff !important;
    }

    /* Checkbox y Radio */
    input[type="checkbox"],
    input[type="radio"] {
        width: 18px;
        height: 18px;
        border: 2px solid rgba(64, 224, 255, 0.5);
        accent-color: #40E0FF;
    }

    /* BOXES Y PANELS */
    .box {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 15px !important;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .box-header {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-bottom: 2px solid #40E0FF !important;
        padding: 15px !important;
    }

    .box-header h3,
    .box-header .box-title {
        color: #40E0FF !important;
        font-weight: 700;
        margin: 0;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
    }

    .box-body {
        padding: 20px !important;
        color: #ffffff !important;
    }

    .box-footer {
        background: rgba(15, 52, 96, 0.5) !important;
        border-top: 1px solid rgba(64, 224, 255, 0.3) !important;
        padding: 15px !important;
    }

    /* Panel similar a box */
    .panel {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 15px !important;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
        margin-bottom: 20px;
    }

    .panel-heading {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-bottom: 2px solid #40E0FF !important;
        padding: 15px !important;
        color: #40E0FF !important;
        font-weight: 700;
    }

    .panel-body {
        padding: 20px !important;
        color: #ffffff !important;
    }

    .panel-footer {
        background: rgba(15, 52, 96, 0.5) !important;
        border-top: 1px solid rgba(64, 224, 255, 0.3) !important;
        padding: 15px !important;
    }

    /* WELL */
    .well {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 15px !important;
        padding: 20px !important;
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    /* BADGES Y LABELS */
    .badge,
    .label {
        background: linear-gradient(135deg, #40E0FF 0%, #0f3460 100%) !important;
        color: #ffffff !important;
        padding: 5px 10px;
        border-radius: 12px;
        font-weight: 600;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
        box-shadow: 0 2px 8px rgba(64, 224, 255, 0.3);
    }

    .badge-primary,
    .label-primary {
        background: linear-gradient(135deg, #40E0FF 0%, #0f3460 100%) !important;
    }

    .badge-success,
    .label-success {
        background: linear-gradient(135deg, #4ade80 0%, #1a4a2e 100%) !important;
    }

    .badge-warning,
    .label-warning {
        background: linear-gradient(135deg, #fbbf24 0%, #5c4a1a 100%) !important;
    }

    .badge-danger,
    .label-danger {
        background: linear-gradient(135deg, #ef4444 0%, #5c1a1a 100%) !important;
    }

    .badge-info,
    .label-info {
        background: linear-gradient(135deg, #818cf8 0%, #2e2e5c 100%) !important;
    }

    /* PROGRESS BARS */
    .progress {
        background: rgba(22, 33, 62, 0.8) !important;
        border: 1px solid rgba(64, 224, 255, 0.3);
        border-radius: 10px;
        height: 25px;
        overflow: hidden;
        box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.5);
    }

    .progress-bar {
        background: linear-gradient(135deg, #40E0FF 0%, #0f3460 100%) !important;
        box-shadow: 0 0 15px rgba(64, 224, 255, 0.6);
        transition: width 0.6s ease;
        line-height: 25px;
        color: #ffffff;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
    }

    .progress-bar-success {
        background: linear-gradient(135deg, #4ade80 0%, #1a4a2e 100%) !important;
        box-shadow: 0 0 15px rgba(74, 222, 128, 0.6);
    }

    .progress-bar-warning {
        background: linear-gradient(135deg, #fbbf24 0%, #5c4a1a 100%) !important;
        box-shadow: 0 0 15px rgba(251, 191, 36, 0.6);
    }

    .progress-bar-danger {
        background: linear-gradient(135deg, #ef4444 0%, #5c1a1a 100%) !important;
        box-shadow: 0 0 15px rgba(239, 68, 68, 0.6);
    }

    /* PAGINATION */
    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 20px 0;
    }

    .pagination li {
        margin: 0 3px;
    }

    .pagination li a,
    .pagination li span {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        color: #ffffff !important;
        padding: 8px 15px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: block;
    }

    .pagination li a:hover {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-color: #40E0FF !important;
        box-shadow: 0 0 15px rgba(64, 224, 255, 0.5);
        transform: translateY(-2px);
    }

    .pagination li.active a,
    .pagination li.active span {
        background: linear-gradient(135deg, #40E0FF 0%, #0f3460 100%) !important;
        border-color: #40E0FF !important;
        box-shadow: 0 0 20px rgba(64, 224, 255, 0.6);
    }

    /* TABS */
    .nav-tabs {
        border-bottom: 2px solid rgba(64, 224, 255, 0.3);
    }

    .nav-tabs > li > a {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-bottom: none !important;
        color: #ffffff !important;
        border-radius: 10px 10px 0 0 !important;
        margin-right: 5px;
        transition: all 0.3s ease;
    }

    .nav-tabs > li > a:hover {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-color: #40E0FF !important;
    }

    .nav-tabs > li.active > a {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-color: #40E0FF !important;
        color: #40E0FF !important;
        box-shadow: 0 -3px 15px rgba(64, 224, 255, 0.3);
    }

    .tab-content {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-top: none !important;
        padding: 20px;
        border-radius: 0 0 10px 10px;
        color: #ffffff !important;
    }

    /* TOOLTIPS */
    .tooltip-inner {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border: 1px solid #40E0FF;
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(64, 224, 255, 0.5);
        padding: 8px 12px;
        border-radius: 8px;
    }

    /* POPOVERS */
    .popover {
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border: 2px solid #40E0FF !important;
        box-shadow: 0 8px 30px rgba(64, 224, 255, 0.4);
        border-radius: 10px;
    }

    .popover-title {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        color: #40E0FF !important;
        border-bottom: 1px solid #40E0FF !important;
    }

    .popover-content {
        color: #ffffff !important;
    }

    /* CARDS PEQUEÑAS INFO */
    .info-box {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 15px;
        padding: 15px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        min-height: 90px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }

    .info-box:hover {
        border-color: #40E0FF !important;
        box-shadow: 0 10px 40px rgba(64, 224, 255, 0.4);
        transform: translateY(-5px);
    }

    .info-box-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 70px;
        height: 70px;
        border-radius: 10px;
        background: rgba(64, 224, 255, 0.2);
        border: 2px solid #40E0FF;
        margin-right: 15px;
    }

    .info-box-icon i {
        font-size: 35px;
        color: #40E0FF;
    }

    .info-box-content {
        flex: 1;
        color: #ffffff;
    }

    .info-box-text {
        text-transform: uppercase;
        font-weight: 600;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.8);
    }

    .info-box-number {
        font-size: 24px;
        font-weight: 700;
        color: #40E0FF;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
    }

    /* SMALL BOX */
    .small-box {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
        position: relative;
        margin-bottom: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .small-box:hover {
        border-color: #40E0FF !important;
        box-shadow: 0 10px 40px rgba(64, 224, 255, 0.4);
        transform: translateY(-5px);
    }

    .small-box h3 {
        color: #40E0FF !important;
        font-size: 38px;
        font-weight: 700;
        margin: 0 0 10px 0;
        text-shadow: 0 0 15px rgba(64, 224, 255, 0.6);
    }

    .small-box p {
        color: #ffffff;
        font-size: 14px;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .small-box .icon {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 70px;
        color: rgba(64, 224, 255, 0.3);
    }

    .small-box .small-box-footer {
        display: block;
        padding: 10px 0;
        margin-top: 10px;
        text-align: center;
        border-top: 1px solid rgba(64, 224, 255, 0.3);
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .small-box .small-box-footer:hover {
        color: #40E0FF;
        background: rgba(64, 224, 255, 0.1);
    }

    /* LISTA DE GRUPOS */
    .list-group {
        border-radius: 10px;
        overflow: hidden;
    }

    .list-group-item {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border: 1px solid rgba(64, 224, 255, 0.3) !important;
        color: #ffffff !important;
        padding: 12px 20px;
        transition: all 0.3s ease;
    }

    .list-group-item:hover {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-color: #40E0FF !important;
        transform: translateX(5px);
    }

    .list-group-item.active {
        background: linear-gradient(135deg, #40E0FF 0%, #0f3460 100%) !important;
        border-color: #40E0FF !important;
        box-shadow: 0 0 20px rgba(64, 224, 255, 0.5);
    }

    /* Todo Container - Estilo Kanban cyberpunk */
    .todo-container {
        max-width: 15000px;
        height: auto;
        display: flex;
        overflow-y: scroll;
        overflow-x: auto;
        column-gap: 0.5em;
        column-rule: 2px solid rgba(64, 224, 255, 0.3);
        column-width: 140px;
        column-count: 7;
        padding: 10px;
    }

    /* Scrollbar para todo-container */
    .todo-container::-webkit-scrollbar {
        height: 12px;
        width: 12px;
    }

    .todo-container::-webkit-scrollbar-track {
        background: rgba(10, 10, 10, 0.5);
        border-radius: 10px;
    }

    .todo-container::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #40E0FF 0%, #0f3460 100%);
        border-radius: 10px;
    }

    .todo-container::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #00D9FF 0%, #40E0FF 100%);
    }

    .status {
        width: 25%;
        min-width: 250px;
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 15px;
        position: relative;
        padding: 60px 1rem 0.5rem;
        height: 100%;
        box-shadow: 0 8px 30px rgba(64, 224, 255, 0.2);
        margin-right: 10px;
    }

    .status h4 {
        position: absolute;
        top: 0;
        left: 0;
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        color: #ffffff !important;
        margin: 0;
        width: 100%;
        padding: 0.5rem 1rem;
        border-radius: 13px 13px 0 0;
        border-bottom: 2px solid #40E0FF;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
        font-weight: 600;
        font-size: 16px;
        text-align: center;
    }

    /* Estilos para alertas/tarjetas de pacientes */
    .alert {
        padding: 15px 40px 15px 15px;
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
        border: 1px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 10px;
        margin-bottom: 10px;
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
        position: relative;
    }

    .alert:hover {
        border-color: #40E0FF !important;
        box-shadow: 0 6px 20px rgba(64, 224, 255, 0.4);
        transform: translateX(5px);
    }

    .alert-success {
        border-color: rgba(74, 222, 128, 0.5) !important;
        background: linear-gradient(135deg, rgba(26, 74, 46, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
    }

    .alert-warning {
        border-color: rgba(251, 191, 36, 0.5) !important;
        background: linear-gradient(135deg, rgba(92, 74, 26, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
    }

    .alert-danger {
        border-color: rgba(239, 68, 68, 0.5) !important;
        background: linear-gradient(135deg, rgba(92, 26, 26, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
    }

    .alert-info {
        border-color: rgba(129, 140, 248, 0.5) !important;
        background: linear-gradient(135deg, rgba(46, 46, 92, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
    }

    /* Botón de cerrar alert */
    .alert .close {
        color: #40E0FF !important;
        opacity: 1;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.8);
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
    }

    /* Nombre del paciente */
    .nompac {
        font-size: 11.5px;
        position: absolute;
        color: #ffffff !important;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
    }

    .nod {
        font-size: 10.3px;
        color: rgba(255, 255, 255, 0.9) !important;
    }

    /* Tarjetas de contenido */
    .ancholi {
        margin-top: 1px;
        margin-bottom: 10px;
        width: 175px;
        height: 100px;
        display: inline-block;
    }

    .ancholi2 {
        width: 170px;
        height: 97px;
        display: inline-block;
        box-shadow: 0 5px 15px rgba(64, 224, 255, 0.3);
        border: 1px solid rgba(64, 224, 255, 0.2);
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%);
        transition: all 0.3s ease;
    }

    .ancholi2:hover {
        box-shadow: 0 8px 25px rgba(64, 224, 255, 0.5);
        border-color: #40E0FF;
        transform: scale(1.05);
    }

    /* Tarjetas modernas cyberpunk - Estilo base */
    .modern-card,
    .farmacia-card {
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 25px !important;
        padding: 40px 20px;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5),
                    0 0 30px rgba(64, 224, 255, 0.2) !important;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        position: relative;
        overflow: hidden;
        min-height: 280px;
        margin: 20px 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-decoration: none;
    }

    .modern-card::before,
    .farmacia-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            45deg,
            transparent,
            rgba(64, 224, 255, 0.1),
            transparent
        );
        transform: rotate(45deg);
        transition: all 0.6s ease;
    }

    .modern-card:hover::before,
    .farmacia-card:hover::before {
        left: 100%;
    }

    .modern-card:hover,
    .farmacia-card:hover {
        transform: translateY(-15px) scale(1.05) !important;
        border-color: #00D9FF !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7),
                    0 0 50px rgba(64, 224, 255, 0.5),
                    inset 0 0 20px rgba(64, 224, 255, 0.1) !important;
        text-decoration: none;
    }

    .modern-card a,
    .farmacia-card a {
        text-decoration: none !important;
        color: inherit;
        display: block;
    }

    /* Variaciones de color para tarjetas de farmacia */
    .farmacia-card.surtir {
        background: linear-gradient(135deg, #16213e 0%, #1a3a5c 100%) !important;
        border-color: #40E0FF !important;
    }

    .farmacia-card.existencias {
        background: linear-gradient(135deg, #16213e 0%, #2e1a4a 100%) !important;
        border-color: #c084fc !important;
    }

    .farmacia-card.kardex {
        background: linear-gradient(135deg, #16213e 0%, #1a4a2e 100%) !important;
        border-color: #4ade80 !important;
    }

    .farmacia-card.caducidades {
        background: linear-gradient(135deg, #16213e 0%, #5c3a1a 100%) !important;
        border-color: #fb923c !important;
    }

    .farmacia-card.devoluciones {
        background: linear-gradient(135deg, #16213e 0%, #4a1a2e 100%) !important;
        border-color: #f472b6 !important;
    }

    .farmacia-card.confirmar {
        background: linear-gradient(135deg, #16213e 0%, #5c1a1a 100%) !important;
        border-color: #ef4444 !important;
    }

    .farmacia-card.pedir {
        background: linear-gradient(135deg, #16213e 0%, #1a5c5c 100%) !important;
        border-color: #2dd4bf !important;
    }

    .farmacia-card.salidas {
        background: linear-gradient(135deg, #16213e 0%, #2e2e5c 100%) !important;
        border-color: #818cf8 !important;
    }

    .farmacia-card.inventario {
        background: linear-gradient(135deg, #16213e 0%, #5c4a1a 100%) !important;
        border-color: #fbbf24 !important;
    }

    /* Hover para variaciones de color */
    .farmacia-card:hover.surtir {
        border-color: #00D9FF !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(64, 224, 255, 0.6) !important;
    }

    .farmacia-card:hover.existencias {
        border-color: #a855f7 !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(192, 132, 252, 0.6) !important;
    }

    .farmacia-card:hover.kardex {
        border-color: #22c55e !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(74, 222, 128, 0.6) !important;
    }

    .farmacia-card:hover.caducidades {
        border-color: #f97316 !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(251, 146, 60, 0.6) !important;
    }

    .farmacia-card:hover.devoluciones {
        border-color: #ec4899 !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(244, 114, 182, 0.6) !important;
    }

    .farmacia-card:hover.confirmar {
        border-color: #dc2626 !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(239, 68, 68, 0.6) !important;
    }

    .farmacia-card:hover.pedir {
        border-color: #14b8a6 !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(45, 212, 191, 0.6) !important;
    }

    .farmacia-card:hover.salidas {
        border-color: #6366f1 !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(129, 140, 248, 0.6) !important;
    }

    .farmacia-card:hover.inventario {
        border-color: #f59e0b !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(251, 191, 36, 0.6) !important;
    }

    /* Círculo de icono */
    .icon-circle,
    .farmacia-icon-circle {
        background: linear-gradient(135deg, rgba(64, 224, 255, 0.2) 0%, rgba(0, 217, 255, 0.3) 100%) !important;
        width: 140px !important;
        height: 140px !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        margin: 0 auto 20px !important;
        border: 3px solid #40E0FF !important;
        box-shadow: 0 10px 30px rgba(64, 224, 255, 0.3),
                    inset 0 0 20px rgba(64, 224, 255, 0.1) !important;
        transition: all 0.4s ease !important;
        position: relative;
    }

    .icon-circle::after,
    .farmacia-icon-circle::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 2px solid #40E0FF;
        opacity: 0;
        animation: ripple 2s ease-out infinite;
    }

    @keyframes ripple {
        0% {
            transform: scale(1);
            opacity: 0.8;
        }
        100% {
            transform: scale(1.3);
            opacity: 0;
        }
    }

    .modern-card:hover .icon-circle,
    .farmacia-card:hover .farmacia-icon-circle,
    .modern-card:hover .farmacia-icon-circle,
    .farmacia-card:hover .icon-circle {
        transform: scale(1.15) rotate(360deg) !important;
        box-shadow: 0 15px 40px rgba(64, 224, 255, 0.5),
                    inset 0 0 30px rgba(64, 224, 255, 0.2) !important;
        background: linear-gradient(135deg, rgba(64, 224, 255, 0.3) 0%, rgba(0, 217, 255, 0.4) 100%) !important;
    }

    .modern-card .fa,
    .farmacia-card i,
    .modern-card i,
    .farmacia-card .fa {
        font-size: 60px !important;
        color: #40E0FF !important;
        text-shadow: 0 0 20px rgba(64, 224, 255, 0.8);
        transition: all 0.4s ease !important;
    }

    .modern-card:hover .fa,
    .farmacia-card:hover i,
    .modern-card:hover i,
    .farmacia-card:hover .fa {
        transform: scale(1.2) !important;
        text-shadow: 0 0 30px rgba(64, 224, 255, 1),
                     0 0 40px rgba(64, 224, 255, 0.8);
        animation: pulse-icon 1.5s infinite;
    }

    @keyframes pulse-icon {
        0% { transform: scale(1.2); }
        50% { transform: scale(1.25); }
        100% { transform: scale(1.2); }
    }

    /* Títulos */
    .card-title,
    .farmacia-card h4,
    .modern-card h4 {
        color: #ffffff !important;
        font-weight: 700 !important;
        font-size: 1.4rem !important;
        margin: 0 !important;
        text-align: center;
        padding: 20px;
        letter-spacing: 1px;
        text-transform: uppercase;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5),
                     0 0 20px rgba(64, 224, 255, 0.3);
        transition: all 0.3s ease;
        line-height: 1.3;
    }

    .modern-card:hover .card-title,
    .farmacia-card:hover h4,
    .modern-card:hover h4 {
        color: #40E0FF !important;
        text-shadow: 0 0 20px rgba(64, 224, 255, 0.8),
                     0 0 30px rgba(64, 224, 255, 0.5);
    }

    /* Animaciones de entrada */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modern-card,
    .farmacia-card {
        animation: fadeInUp 0.8s ease-out backwards;
    }

    .modern-card:nth-child(1),
    .farmacia-card:nth-child(1) { animation-delay: 0.1s; }
    .modern-card:nth-child(2),
    .farmacia-card:nth-child(2) { animation-delay: 0.2s; }
    .modern-card:nth-child(3),
    .farmacia-card:nth-child(3) { animation-delay: 0.3s; }
    .modern-card:nth-child(4),
    .farmacia-card:nth-child(4) { animation-delay: 0.4s; }
    .modern-card:nth-child(5),
    .farmacia-card:nth-child(5) { animation-delay: 0.5s; }
    .modern-card:nth-child(6),
    .farmacia-card:nth-child(6) { animation-delay: 0.6s; }
    .modern-card:nth-child(7),
    .farmacia-card:nth-child(7) { animation-delay: 0.7s; }
    .modern-card:nth-child(8),
    .farmacia-card:nth-child(8) { animation-delay: 0.8s; }
    .modern-card:nth-child(9),
    .farmacia-card:nth-child(9) { animation-delay: 0.9s; }

    /* Efecto de brillo en hover */
    @keyframes glow {
        0%, 100% {
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5),
                        0 0 30px rgba(64, 224, 255, 0.2);
        }
        50% {
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7),
                        0 0 50px rgba(64, 224, 255, 0.6);
        }
    }

    .modern-card:hover,
    .farmacia-card:hover {
        animation: glow 2s ease-in-out infinite;
    }

    /* Modal */
    .modal-content {
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 20px !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.9),
                    0 0 40px rgba(64, 224, 255, 0.4);
    }

    .modal-header {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-bottom: 2px solid #40E0FF !important;
        border-radius: 20px 20px 0 0 !important;
    }

    .modal-header .close {
        color: #40E0FF !important;
        opacity: 1;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.8);
    }

    .modal-body {
        color: #ffffff !important;
    }

    .modal-footer {
        border-top: 2px solid #40E0FF !important;
        background: rgba(15, 52, 96, 0.5) !important;
    }

    /* Botones */
    .btn {
        border-radius: 25px !important;
        padding: 12px 30px !important;
        font-weight: 600 !important;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease !important;
        border: 2px solid #40E0FF !important;
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        color: #ffffff !important;
    }

    .btn:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 10px 25px rgba(64, 224, 255, 0.4) !important;
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border-color: #00D9FF !important;
        color: #ffffff !important;
    }

    .btn-primary {
        background: linear-gradient(135deg, #40E0FF 0%, #0f3460 100%) !important;
        border-color: #40E0FF !important;
    }

    .btn-success {
        background: linear-gradient(135deg, #4ade80 0%, #1a4a2e 100%) !important;
        border-color: #4ade80 !important;
    }

    .btn-warning {
        background: linear-gradient(135deg, #fbbf24 0%, #5c4a1a 100%) !important;
        border-color: #fbbf24 !important;
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #5c1a1a 100%) !important;
        border-color: #ef4444 !important;
    }

    .btn-info {
        background: linear-gradient(135deg, #818cf8 0%, #2e2e5c 100%) !important;
        border-color: #818cf8 !important;
    }

    /* Dropdown menu del usuario */
    .dropdown-menu {
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 10px;
    }

    .dropdown-menu > li > a {
        color: #ffffff !important;
    }

    .dropdown-menu > li > a:hover {
        background: rgba(64, 224, 255, 0.1) !important;
        color: #40E0FF !important;
    }

    .user-header {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
    }

    /* Footer */
    .main-footer {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-top: 2px solid #40E0FF !important;
        color: #ffffff !important;
        box-shadow: 0 -4px 20px rgba(64, 224, 255, 0.2);
    }

    /* Links globales */
    a {
        color: #40E0FF;
        transition: all 0.3s ease;
    }

    a:hover {
        color: #00D9FF;
        text-decoration: none;
    }

    /* Headings globales */
    h1, h2, h3, h4, h5, h6 {
        color: #ffffff;
    }

    /* Párrafos */
    p {
        color: rgba(255, 255, 255, 0.9);
    }

    /* HR */
    hr {
        border-top: 1px solid rgba(64, 224, 255, 0.3);
    }

    /* Scrollbar personalizado */
    ::-webkit-scrollbar {
        width: 12px;
    }

    ::-webkit-scrollbar-track {
        background: #0a0a0a;
        border-left: 1px solid #40E0FF;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #40E0FF 0%, #0f3460 100%);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #00D9FF 0%, #40E0FF 100%);
    }

    /* Responsividad mejorada */
    @media (max-width: 992px) {
        .icon-circle,
        .farmacia-icon-circle {
            width: 120px !important;
            height: 120px !important;
        }

        .modern-card .fa,
        .farmacia-card i {
            font-size: 50px !important;
        }

        .card-title,
        .farmacia-card h4 {
            font-size: 1.2rem !important;
        }

        .breadcrumb h4 {
            font-size: 24px !important;
        }

        table, .table {
            font-size: 13px;
        }

        thead th {
            padding: 10px !important;
        }

        tbody td {
            padding: 8px 10px !important;
        }
    }

    @media screen and (max-width: 980px) {
        .alert {
            padding-right: 38px;
            padding-left: 10px;
        }

        .nompac {
            margin-left: -3px;
            font-size: 10px;
        }

        .nod {
            font-size: 7px;
        }
    }

    @media (max-width: 768px) {
        .farmacia-container {
            padding: 15px;
        }

        .modern-card,
        .farmacia-card {
            margin: 15px 0;
            padding: 30px 15px;
            min-height: 220px;
        }

        .icon-circle,
        .farmacia-icon-circle {
            width: 100px !important;
            height: 100px !important;
            margin-bottom: 15px !important;
        }

        .modern-card .fa,
        .farmacia-card i {
            font-size: 40px !important;
        }

        .card-title,
        .farmacia-card h4 {
            font-size: 1.1rem !important;
            padding: 15px;
        }

        .breadcrumb {
            padding: 20px !important;
            margin-bottom: 30px !important;
        }

        .breadcrumb h4 {
            font-size: 20px !important;
        }

        .status {
            min-width: 200px;
        }

        table, .table {
            font-size: 12px;
        }

        .box, .panel, .well {
            margin-bottom: 15px;
        }

        .info-box {
            flex-direction: column;
            text-align: center;
        }

        .info-box-icon {
            margin-right: 0;
            margin-bottom: 10px;
        }
    }

    @media (max-width: 576px) {
        .modern-card,
        .farmacia-card {
            min-height: 200px;
            padding: 25px 15px;
            margin: 10px 0;
        }

        .icon-circle,
        .farmacia-icon-circle {
            width: 80px !important;
            height: 80px !important;
            margin-bottom: 12px !important;
        }

        .modern-card .fa,
        .farmacia-card i {
            font-size: 32px !important;
        }

        .card-title,
        .farmacia-card h4 {
            font-size: 13px !important;
            padding: 10px;
        }

        .breadcrumb h4 {
            font-size: 18px !important;
            letter-spacing: 1px;
        }

        .status {
            min-width: 180px;
        }

        table, .table {
            font-size: 11px;
        }

        thead th {
            padding: 8px 5px !important;
            font-size: 11px;
        }

        tbody td {
            padding: 6px 5px !important;
        }

        .btn {
            padding: 10px 20px !important;
            font-size: 12px;
        }

        .small-box h3 {
            font-size: 28px;
        }

        .info-box-number {
            font-size: 20px;
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
        if ($usuario1['id_rol'] == 9) {
            ?>

            <a href="../../template/menu_imagenologia.php" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->

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
        } else if ($usuario1['id_rol'] == 5) {

            ?>
            <a href="../../template/menu_gerencia.php" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->

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
        } else if ($usuario1['id_rol'] == 2) {

            ?>
            <a href="../../template/menu_medico.php" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->

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
        } else if ($usuario1['id_rol'] == 12) {

            ?>
            <a href="../../template/menu_residente.php" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->

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
        } else if ($usuario1['id_rol'] == 10) {

            ?>
            <a href="../../template/menu_laboratorio.php" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->

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
        echo "<script>window.Location='../../index.php';</script>";
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
                            <img src="../../imagenes/<?php echo $usuario1['img_perfil']; ?>" class="user-image" alt="User Image" />
                            <span class="hidden-xs"> <?php echo $usuario1['papell']; ?> <?php echo $usuario1['sapell']; ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="../../imagenes/<?php echo $usuario1['img_perfil']; ?>" class="img-circle" alt="User Image" />
                                <p>
                                    <?php echo $usuario1['papell']; ?> <?php echo $usuario1['sapell']; ?>
                                </p>
                            </li>

                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-right">
                                    <a href="../../cerrar_sesion.php" class="btn btn-default btn-flat">Cerrar sesión</a>
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
                    <img src="../../imagenes/<?php echo $usuario1['img_perfil']; ?>" class="img-circle" alt="User Image" />
                </div>
                <div class="pull-left info">
                    <p><?php echo $usuario1['papell']; ?></p>

                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>

            <!-- sidebar menu: : style can be found in sidebar.less -->

            <ul class="sidebar-menu">

                <li class="active treeview">
                    <a href="../../template/menu_imagenologia.php">
                       <i class="fa fa-file-text"></i> <span>SOLICITUD DE ESTUDIOS<br>DE IMAGENOLOGÍA</span>

                    </a>

                </li>

                <li class="treeview">
                    <a href="../Imagenologia/resultados_imagen.php">
                       <i class="fa fa-file-image-o"></i> <span>RESULTADO DE ESTUDIOS<br>DE IMAGENOLOGÍA</span>

                    </a>

                </li>

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
                        <h4>USUARIO IMAGENOLOGIA</h4>
                    </STRONG></li>
            </ol>
        </nav>
        <script>
            function SoloLetras(e) {
                key = e.keyCode || e.which;
                tecla = String.fromCharCode(key).toString();
                letras = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚabcdefghijklmnñopqrstuvwxyzáéíóú ";

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
                    alert("Ingresar solo numeros");
                    return false;
                }
            }
        </script>
