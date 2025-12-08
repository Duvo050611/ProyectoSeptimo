<?php
session_start();
include "../../conexionbd.php";
include '../../conn_almacen/Connection.php';
include "../../gestion_administrativa/header_administrador.php";
$usuario = $_SESSION['login'];
$usuario1 = $usuario['id_usua'];
$rol = $usuario['id_rol'];
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <link rel="icon" href="../../imagenes/SIF.PNG">
    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <link href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="js/select2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldLv/Pr4nhuBviF5jGqQK/5i2Q5iZ64dxBl+zOZ" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/main.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script>
    // Write on keyup event of keyword input element
    $(document).ready(function() {
        $("#search").keyup(function() {
            _this = this;
            // Show only matching TR, hide rest of them
            $.each($("#mytable tbody tr"), function() {
                if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
                    $(this).hide();
                else
                    $(this).show();
            });
        });
    });
    </script>

    <title>Menu Gestión administrativa </title>
    <link rel="shortcut icon" href="logp.png">
    
    <!-- Estilos modernos para vista_df.php -->
    <style>
    * {
        box-sizing: border-box;
    }

    html, body {
        margin: 0;
        padding: 0;
        width: 100%;
        overflow-x: hidden;
    }

    body {
        background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #0a0a0a 100%) !important;
        font-family: 'Roboto', sans-serif !important;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
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

    /* Wrapper para AdminLTE */
    .wrapper {
        position: relative;
        z-index: 1;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* Content wrapper debe crecer para empujar el footer */
    .content-wrapper {
        flex: 1;
        background: transparent !important;
        min-height: calc(100vh - 100px);
    }

    .container-fluid {
        position: relative;
        z-index: 1;
        padding-top: 30px;
        padding-bottom: 50px;
        max-width: 100%;
        margin: 0 auto;
    }

    /* Page Header mejorado */
    .page-header {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 15px !important;
        padding: 25px 30px !important;
        margin-bottom: 30px !important;
        box-shadow: 0 8px 30px rgba(64, 224, 255, 0.3);
        position: relative;
        overflow: hidden;
        color: #ffffff !important;
        text-align: center;
        text-shadow: 0 0 20px rgba(64, 224, 255, 0.5);
    }

    .page-header::before {
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

    .page-header h1 {
        position: relative;
        z-index: 1;
        margin: 0;
        font-weight: 700;
        font-size: 28px;
        letter-spacing: 1px;
    }

    /* Content Card mejorado */
    .content-card {
        background: linear-gradient(135deg, rgba(15, 52, 96, 0.8) 0%, rgba(22, 33, 62, 0.8) 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 15px !important;
        padding: 25px !important;
        margin: 20px 0 !important;
        box-shadow: 0 8px 30px rgba(64, 224, 255, 0.2);
        color: #ffffff !important;
    }

    /* Botones de acción mejorados */
    .btn {
        border-radius: 25px !important;
        padding: 12px 25px !important;
        font-weight: 600 !important;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease !important;
        border: 2px solid #40E0FF !important;
        box-shadow: 0 4px 15px rgba(64, 224, 255, 0.2);
        position: relative;
        overflow: hidden;
    }

    .btn::before {
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

    .btn:hover::before {
        left: 100%;
    }

    .btn-danger {
        background: linear-gradient(135deg, #c2185b 0%, #e91e63 100%) !important;
        color: #ffffff !important;
        border-color: #f48fb1 !important;
    }

    .btn-danger:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 10px 25px rgba(244, 143, 177, 0.4) !important;
        background: linear-gradient(135deg, #e91e63 0%, #c2185b 100%) !important;
        border-color: #f8bbd0 !important;
    }

    .btn-primary {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        color: #ffffff !important;
        border-color: #40E0FF !important;
    }

    .btn-primary:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 10px 25px rgba(64, 224, 255, 0.4) !important;
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border-color: #00D9FF !important;
        color: #40E0FF !important;
    }

    .btn-success {
        background: linear-gradient(135deg, #388e3c 0%, #4caf50 100%) !important;
        color: #ffffff !important;
        border-color: #66bb6a !important;
    }

    .btn-success:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 10px 25px rgba(102, 187, 106, 0.4) !important;
        background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%) !important;
        border-color: #a5d6a7 !important;
    }

    .btn-warning {
        background: linear-gradient(135deg, #f57c00 0%, #ff9800 100%) !important;
        color: #ffffff !important;
        border-color: #ffa726 !important;
    }

    .btn-warning:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 10px 25px rgba(255, 167, 38, 0.4) !important;
        background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%) !important;
        border-color: #ffcc80 !important;
    }

    /* Barra de búsqueda mejorada */
    .form-control {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 25px !important;
        padding: 15px 25px !important;
        color: #ffffff !important;
        font-size: 16px;
        box-shadow: 0 4px 15px rgba(64, 224, 255, 0.2);
        transition: all 0.3s ease;
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }

    .form-control:focus {
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border-color: #00D9FF !important;
        box-shadow: 0 8px 25px rgba(64, 224, 255, 0.4) !important;
        color: #ffffff !important;
        outline: none;
    }

    /* Tablas mejoradas */
    .table-responsive {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 15px !important;
        padding: 20px;
        box-shadow: 0 8px 30px rgba(64, 224, 255, 0.3);
        margin-bottom: 30px;
        overflow-x: auto;
        overflow-y: visible;
    }

    .table {
        color: #ffffff !important;
        margin-bottom: 0;
        width: 100%;
    }

    .table thead th {
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        color: #40E0FF !important;
        border: 2px solid #40E0FF !important;
        padding: 15px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
        white-space: nowrap;
        text-align: center;
        vertical-align: middle;
    }

    .table tbody tr {
        background: rgba(15, 52, 96, 0.5) !important;
        border-bottom: 1px solid rgba(64, 224, 255, 0.2);
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background: rgba(64, 224, 255, 0.1) !important;
        transform: translateX(5px);
    }

    .table tbody td {
        border: 1px solid rgba(64, 224, 255, 0.2) !important;
        padding: 12px 15px;
        color: #ffffff !important;
        vertical-align: middle;
    }

    .table tbody td strong {
        color: #ffffff !important;
    }

    /* Celdas con color especial */
    .table td[style*="background: linear-gradient(135deg, #27ae60, #2ecc71)"] {
        background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%) !important;
        color: #ffffff !important;
        font-weight: bold;
        border-radius: 6px;
    }

    .table td[style*="background: linear-gradient(135deg, #6c757d, #95a5a6)"] {
        background: linear-gradient(135deg, #6c757d 0%, #95a5a6 100%) !important;
        color: #ffffff !important;
        font-weight: bold;
        border-radius: 6px;
    }

    .table td[style*="background: linear-gradient(135deg, #e8f5e8, #f1f8e9)"] {
        background: linear-gradient(135deg, rgba(15, 52, 96, 0.8) 0%, rgba(22, 33, 62, 0.8) 100%) !important;
        border: 1px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 6px;
    }

    /* Colores para números */
    .table td span[style*="color: #e74c3c"] {
        color: #40E0FF !important;
        font-weight: 700 !important;
        font-size: 16px !important;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
    }

    /* Botones dentro de la tabla */
    .table .btn-sm {
        padding: 8px 15px !important;
        font-size: 14px;
        min-height: auto;
        margin: 2px;
    }

    .table .btn-warning.btn-sm {
        background: linear-gradient(135deg, #f57c00 0%, #ff9800 100%) !important;
    }

    .table .btn-danger.btn-sm {
        background: linear-gradient(135deg, #c2185b 0%, #e91e63 100%) !important;
    }

    /* Grupo de botones */
    .btn-group {
        display: flex;
        gap: 5px;
    }

    /* Iconos */
    .btn i, .btn span {
        transition: transform 0.3s ease;
    }

    .btn:hover i, .btn:hover span {
        transform: scale(1.2);
    }

    /* Footer corregido y centrado */
    .main-footer {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-top: 2px solid #40E0FF !important;
        color: #ffffff !important;
        box-shadow: 0 -4px 20px rgba(64, 224, 255, 0.2);
        margin-top: 50px;
        margin-left: 0 !important;
        margin-right: 0 !important;
        padding: 20px;
        text-align: center;
        width: 100%;
        position: relative;
        left: 0;
        right: 0;
    }

    /* Si el footer está dentro de .wrapper de AdminLTE */
    .wrapper > .main-footer {
        margin-left: 0 !important;
        width: 100% !important;
    }

    /* Para páginas con sidebar de AdminLTE */
    @media (min-width: 768px) {
        .sidebar-mini.sidebar-collapse .main-footer {
            margin-left: 50px !important;
        }
        
        .sidebar-mini:not(.sidebar-collapse) .main-footer {
            margin-left: 230px !important;
        }
    }

    /* Para páginas sin sidebar (como esta) */
    body:not(.sidebar-mini) .main-footer {
        margin-left: 0 !important;
    }

    .main-footer p,
    .main-footer a {
        color: #ffffff !important;
        margin: 5px 0;
    }

    .main-footer a:hover {
        color: #40E0FF !important;
        text-decoration: none;
    }

    /* Scrollbar personalizado */
    ::-webkit-scrollbar {
        width: 12px;
        height: 12px;
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

    /* Row adjustments */
    .row {
        margin-left: 0;
        margin-right: 0;
    }

    .row > [class*='col-'] {
        padding-left: 10px;
        padding-right: 10px;
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

    .container-fluid > * {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Responsive */
    @media screen and (max-width: 768px) {
        .page-header {
            padding: 15px 20px !important;
            font-size: 18px !important;
        }

        .page-header h1 {
            font-size: 22px !important;
        }

        .content-card {
            padding: 15px !important;
        }

        .btn {
            font-size: 0.85rem !important;
            padding: 10px 20px !important;
        }

        .table-responsive {
            padding: 10px;
        }

        .table thead th,
        .table tbody td {
            padding: 10px;
            font-size: 14px;
        }

        .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }

        .main-footer {
            margin-left: 0 !important;
            padding: 15px;
        }
    }

    @media screen and (max-width: 576px) {
        .page-header h1 {
            font-size: 18px !important;
        }

        .btn {
            font-size: 0.75rem !important;
            padding: 8px 15px !important;
            min-height: 40px;
        }

        .table {
            font-size: 12px;
        }
        
        .btn-group {
            flex-direction: column;
        }
    }

    /* Asegurar que no haya overflow horizontal */
    .content-wrapper,
    .container-fluid,
    .container {
        overflow-x: hidden;
    }

    /* Centrado adicional para el footer */
    footer.main-footer {
        clear: both;
        display: block;
        width: 100%;
        max-width: 100vw;
    }

    /* Mejoras para encabezados de tabla */
    .table thead th i {
        margin-right: 5px;
        font-size: 16px;
    }
</style>
</head>

<div class="container-fluid">
    <!-- Header de página moderno -->
    <div class="page-header">
        <h1><i class="fas fa-file-invoice-dollar"></i> CUENTA DE PACIENTES ACTIVOS</h1>
    </div>

    <!-- Botones de acción -->
    <div class="content-card">
        <div class="row align-items-center">
            <div class="col-md-3">
                <button type="button" class="btn btn-danger" onclick="history.back()">
                    <i class="fas fa-arrow-left"></i> Regresar
                </button>
            </div>
            <?php if ($usuario['id_usua'] == 1) { ?>
                <div class="col-md-6">
                    <a href="vista_pagadas.php" class="btn btn-primary">
                        <i class="fas fa-check-circle"></i> Cuentas Pagadas
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="excel_cuentas_activas.php" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Exportar Excel
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Buscador mejorado -->
    <div class="content-card">
        <div class="row">
            <div class="col-md-6">
                <h5><i class="fas fa-search"></i> Buscar en la tabla</h5>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <input type="text" class="form-control" id="search" placeholder="🔍 Buscar por nombre, expediente, habitación...">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="table-responsive">
        <table class="table table-striped table-hover" id="mytable">
            <thead>
                <tr>
                    <th scope="col"><i class="fas fa-cogs"></i> Acciones</th>
                    <th scope="col"><i class="fas fa-file-medical"></i> Exp</th>
                    <th scope="col"><i class="fas fa-hashtag"></i> ID Atención</th>
                    <th scope="col"><i class="fas fa-bed"></i> Hab</th>
                    <th scope="col"><i class="fas fa-user"></i> Paciente</th>
                    <th scope="col"><i class="fas fa-user-md"></i> Médico</th>
                    <th scope="col"><i class="fas fa-stethoscope"></i> Especialidad</th>
                    <th scope="col"><i class="fas fa-building"></i> Cliente</th>
                    <th scope="col"><i class="fas fa-calculator"></i> Subtotal</th>
                    <th scope="col"><i class="fas fa-percent"></i> IVA</th>
                    <th scope="col" style="background: linear-gradient(135deg, #27ae60, #2ecc71); color: white;">
                        <i class="fas fa-dollar-sign"></i> Total
                    </th>
                    <th scope="col"><i class="fas fa-money-bill"></i> Anticipos</th>
                    <th scope="col"><i class="fas fa-calendar"></i> Fecha Ingreso</th>
                </tr>
            </thead>
            <tbody>

            <?php
                        $resultado = $conexion->query("SELECT * from cat_camas c, dat_ingreso di, paciente p  WHERE c.id_atencion=di.id_atencion and p.Id_exp=di.Id_exp and di.activo='SI'  ORDER BY di.fecha DESC") or die($conexion->error);
                        while ($f = $resultado->fetch_assoc()) {
                            $nombre=$f['papell'].' '.$f['sapell'].' ' .$f['nom_pac'];
                            $id_atencion=$f['id_atencion'];
                            $id_exp = $f['Id_exp'];
                            $cama = $f['num_cama'];
                            $date = date_create($f['fecha']);
                            $fecing = date_format($date, "d/m/Y h:i A");
                            $asegura = $f['aseg'];
                            $medico = $f['id_usua'];
                            $especialidad = $f['tipo_a'];
                            $total = 0;
                            $iva = 0;
                            $totalg12 = 0;
                            $totalg12h = 0;
                            $totaliva = 0;
                            $Stotal = 0;
                            $costo = 0;
                            $subtotal_costo = 0;
                            $total_costos = 0;
                            $totallab = 0;
                            $totalimg = 0;
                            $total_gasto = 0;
                            $num_gasto=0;
                            
                            
                            $resultadom = $conexion ->query("SELECT * FROM reg_usuarios WHERE id_usua = $medico ") or die($conexion->error);
                            while($filam = mysqli_fetch_array($resultadom)){ 
                                $nom_medico=$filam["pre"].'. '.$filam["papell"];
                             }
                                                        // Initialize $tr with a default value
                            $tr = 0; // Default to 0 or another fallback value that makes sense in your context
                            $resultadot = $conexion->query("SELECT tip_precio FROM cat_aseg WHERE aseg='$asegura'") or die($conexion->error);
                            if ($resultadot && mysqli_num_rows($resultadot) > 0) {
                                while ($filat = mysqli_fetch_array($resultadot)) { 
                                    $tr = $filat["tip_precio"];
                                }
                            }
       
                            $resultado3 = $conexion->query("SELECT * FROM dat_ctapac where id_atencion = $id_atencion ORDER BY id_atencion") or die($conexion->error);
                            while ($row3 = $resultado3->fetch_assoc()) {
                                $flag = $row3['prod_serv'];
                                $insumo = $row3['insumo'];
                                $cant = $row3['cta_cant'];
                                $precioh = $row3['cta_tot'];
                                $precio = 0;
                                $iva = 0;
                                $subtottal = 0;
                                $preciog12 = 0;
                                $subtotalg12 = 0;
                                $preciolab = 0;
                                $subtotallab = 0;
                                $precioimg = 0;
                                $subtotalimg = 0;
                                $preciog12h = 0;
                                $subtotalg12h = 0;
                                $costo = 0;
                                $subtotal_costo = 0;
                                $tip_s = ' ';
                                $tip_servi=' ';
                                $Stotal=0;
                               
                             
                if ($insumo == 0 && 
                    $flag != 'S' && 
                    $flag != 'H' && 
                    $flag != 'P' && 
                    $flag != 'PC') {
                    $precio = $precioh;
                    $subtottal = $precio * $cant;
                    $iva = $subtottal * 0.16;
                } elseif ($flag == 'H') {
                    $preciog12h = $precioh;
                    $subtotalg12h = $preciog12h * $cant;
                    $iva = 0.00;
                } elseif ($flag == 'S') {
                    $resultado_serv = $conexion->query("SELECT * FROM cat_servicios WHERE id_serv = $insumo") or die($conexion->error);
                    while ($row_serv = $resultado_serv->fetch_assoc()) {
                        $tip_servi = $row_serv['tip_insumo'];
                        
                        // Use $tr with a fallback to $precioh if $tr is invalid or undefined
                        if ($tr == 1) {
                            $precio = $row_serv['serv_costo'];
                        } elseif ($tr == 2) {
                            $precio = $row_serv['serv_costo2'];
                        } elseif ($tr == 3) {
                            $precio = $row_serv['serv_costo3'];
                        } elseif ($tr == 4) {
                            $precio = $row_serv['serv_costo4'];
                        } else {
                            $precio = $precioh; // Fallback price
                        }
                        
                        if ($precio == 0) {
                            $precio = $precioh;
                        }
                        
                        $subtottal = $precio * $cant;
                        $iva = $subtottal * 0.16;
                        
                        $tip_s = $row_serv['tipo'];
                        
                        if ($insumo == 1 || $insumo == 3) {
                            $num_gasto = $num_gasto + 1;
                        }
                        
                        if ($tip_servi == "RENTA EQUIPO") {
                            $preciog12 = $precioh;
                            $subtotalg12 = ($preciog12 * $cant) * 1.16;
                        }
                    }
                } elseif ($flag == 'P' || $flag == 'PC') {
                    $costo = 0;
                    $precio = $precioh;
                    $subtottal = $precio * $cant;
                    $iva = $subtottal * 0.16;
                }
                
                $totalg12    = $totalg12 + $subtotalg12;
                $totalg12h   = $totalg12h + $subtotalg12h;
                
                $total      = $total + $subtottal;
                $totaliva   = $totaliva + $iva;
                
                if ($tip_s == '1') {$totallab = $totallab + $subtottal + $iva;}
                if ($tip_s == '2') {$totalimg = $totalimg + $subtottal + $iva;}
               
            }
            $total = $total +  + $totalg12h; 
            $Stotal = ($total + $totaliva);
         
           
                        
        $sql_tabla = "SELECT deposito FROM dat_financieros WHERE id_atencion=$id_atencion ORDER BY id_atencion";
        $result_tabla = $conexion->query($sql_tabla);
        $subtottaldep=0;
        $totaldep=0;
        while ($row_tabla = $result_tabla->fetch_assoc()) {
            $subtotaldep=$row_tabla['deposito'];
            $totaldep=$totaldep+$subtotaldep;
        }
        ?>
            <tr>
                <td style="text-align: center;">
                    <div class="btn-group" role="group">
                        <a href="detalle_cuenta.php?id_at=<?php echo $id_atencion ?>&id_exp=<?php echo $id_exp ?>&id_usua=<?php echo $usuario1; ?>&rol=<?php echo $rol ?>" 
                           class="btn btn-warning btn-sm" title="Ver Detalle">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="cuenta.php?id_atencion=<?php echo $id_atencion ?>&id_usua=<?php echo $usuario1 ?>" 
                           target="_blank" class="btn btn-danger btn-sm" title="Generar PDF">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                    </div>
                </td>
                <td><strong><?php echo $id_exp; ?></strong></td>
                <td style="background: linear-gradient(135deg, #6c757d, #95a5a6); color: white; border-radius: 6px; text-align: center;">
                    <strong><?php echo $id_atencion; ?></strong>
                </td>
                <td style="background: linear-gradient(135deg, #27ae60, #2ecc71); color: white; border-radius: 6px; text-align: center;">
                    <strong><?php echo $cama; ?></strong>
                </td>
                <td><strong><?php echo $nombre; ?></strong></td>
                <td><strong><?php echo $nom_medico; ?></strong></td>
                <td><strong><?php echo $especialidad; ?></strong></td>
                <td><strong><?php echo $asegura; ?></strong></td>
                <td style="text-align: right;"><strong>$<?php echo number_format($total, 2); ?></strong></td>
                <td style="text-align: right;"><strong>$<?php echo number_format($totaliva, 2); ?></strong></td>
                <td style="text-align: center; font-weight: bold; background: linear-gradient(135deg, #e8f5e8, #f1f8e9); border-radius: 6px;">
                    <span style="color: #e74c3c; font-size: 16px; font-weight: 700;">
                        $<?php echo number_format($Stotal, 2); ?>
                    </span>
                </td>
                <td style="text-align: right;"><strong>$<?php echo number_format($totaldep, 2); ?></strong></td>
                <td><strong><?php echo $fecing; ?></strong></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>
</div>


<script type="text/javascript">
$(document).ready(function() {
    setTimeout(function() {
        $("#alerts").hide(500);
    }, 500);
});
</script>
<footer class="main-footer">
    <?php
    include("../../template/footer.php");
    ?>
</footer>

<script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<!-- FastClick -->
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

</body>

</html>