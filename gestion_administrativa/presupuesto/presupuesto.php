<?php
session_start();
include "../../conexionbd.php";
include "../../gestion_administrativa/header_administrador.php";
$resultado = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE  dat_ingreso.activo='SI' AND alta_adm = 'NO'") or die($conexion->error);
?>
<!DOCTYPE html>
<html>
<head>
     <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″/>

    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <script src="jquery-3.1.1.min.js"></script>
    <script src="js/select2.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
            crossorigin="anonymous"></script>



    <!--  Bootstrap  -->
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
          integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <script src="../../js/jquery-3.3.1.min.js"></script>
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/popper.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/main.js"></script>

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

    /* Main Container mejorado */
    .main-container {
        background: linear-gradient(135deg, rgba(15, 52, 96, 0.9) 0%, rgba(22, 33, 62, 0.9) 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 15px !important;
        box-shadow: 0 12px 40px rgba(64, 224, 255, 0.25) !important;
        margin: 20px auto !important;
        padding: 0 !important;
        overflow: hidden;
        position: relative;
        z-index: 1;
    }

    .main-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at 30% 20%, rgba(64, 224, 255, 0.05) 0%, transparent 50%);
        pointer-events: none;
        z-index: 0;
    }

    /* Header Section mejorado */
    .header-section {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-bottom: 2px solid #40E0FF !important;
        color: #ffffff !important;
        padding: 30px 25px !important;
        text-align: center;
        margin-bottom: 0;
        position: relative;
        overflow: hidden;
    }

    .header-section::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(64, 224, 255, 0.1) 0%, transparent 70%);
        animation: pulse 3s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }

    .header-section h2 {
        margin: 0;
        font-size: 28px !important;
        font-weight: 700 !important;
        text-shadow: 0 0 20px rgba(64, 224, 255, 0.7) !important;
        letter-spacing: 1px;
        position: relative;
        z-index: 1;
    }

    .header-section i {
        font-size: 32px !important;
        margin-right: 15px !important;
        color: #40E0FF !important;
        text-shadow: 0 0 15px rgba(64, 224, 255, 0.9);
    }

    /* Content Section mejorado */
    .content-section {
        padding: 30px !important;
        background: rgba(10, 10, 10, 0.7) !important;
        position: relative;
        z-index: 1;
    }

    /* Form Card mejorado */
    .form-card {
        background: linear-gradient(135deg, rgba(15, 52, 96, 0.8) 0%, rgba(22, 33, 62, 0.8) 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 12px !important;
        padding: 25px !important;
        margin-bottom: 25px !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 6px 20px rgba(64, 224, 255, 0.15) !important;
        color: #ffffff !important;
    }

    .form-card:hover {
        transform: translateY(-5px) !important;
        border-color: #00D9FF !important;
        box-shadow: 0 10px 30px rgba(64, 224, 255, 0.3) !important;
    }

    .form-card h5 {
        color: #40E0FF !important;
        font-weight: 600 !important;
        margin-bottom: 20px !important;
        padding-bottom: 10px !important;
        border-bottom: 2px solid rgba(64, 224, 255, 0.3) !important;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
    }

    .form-card h5 i {
        margin-right: 10px !important;
        color: #40E0FF !important;
    }

    /* Botones mejorados */
    .btn-back {
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 25px !important;
        padding: 12px 25px !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(64, 224, 255, 0.2);
        position: relative;
        overflow: hidden;
    }

    .btn-back::before {
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

    .btn-back:hover::before {
        left: 100%;
    }

    .btn-back:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 10px 25px rgba(64, 224, 255, 0.4) !important;
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-color: #00D9FF !important;
        color: #40E0FF !important;
        text-decoration: none;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 25px !important;
        padding: 12px 25px !important;
        font-weight: 600 !important;
        color: #ffffff !important;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(64, 224, 255, 0.2);
        position: relative;
        overflow: hidden;
    }

    .btn-primary-custom::before {
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

    .btn-primary-custom:hover::before {
        left: 100%;
    }

    .btn-primary-custom:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 10px 25px rgba(64, 224, 255, 0.4) !important;
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border-color: #00D9FF !important;
        color: #40E0FF !important;
    }

    /* Form Controls mejorados */
    .form-control {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 8px !important;
        padding: 12px 15px !important;
        color: #ffffff !important;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(64, 224, 255, 0.1) !important;
    }

    .form-control:focus {
        border-color: #00D9FF !important;
        box-shadow: 0 0 0 0.2rem rgba(64, 224, 255, 0.25) !important;
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        color: #ffffff !important;
        outline: none;
    }

    .form-label {
        color: #ffffff !important;
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
        text-shadow: 0 0 5px rgba(64, 224, 255, 0.5);
    }

    /* Select2 personalizado */
    .select2-container--default .select2-selection--single {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 8px !important;
        height: 46px !important;
        padding: 8px 15px !important;
        color: #ffffff !important;
    }

    .select2-container--default .select2-selection--single:focus {
        border-color: #00D9FF !important;
        box-shadow: 0 0 0 0.2rem rgba(64, 224, 255, 0.25) !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #ffffff !important;
    }

    .select2-dropdown {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border: 2px solid #40E0FF !important;
        color: #ffffff !important;
    }

    /* Divider mejorado */
    hr.divider {
        border: 0;
        height: 2px;
        background: linear-gradient(135deg, #40E0FF 0%, #00D9FF 100%) !important;
        margin: 30px 0 !important;
        border-radius: 1px;
        box-shadow: 0 0 15px rgba(64, 224, 255, 0.5);
    }

    /* Table Container mejorado */
    .table-container {
        background: linear-gradient(135deg, rgba(15, 52, 96, 0.8) 0%, rgba(22, 33, 62, 0.8) 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 12px !important;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(64, 224, 255, 0.2) !important;
        margin-top: 25px !important;
    }

    /* Table mejorada */
    .table {
        color: #ffffff !important;
        margin-bottom: 0;
        width: 100%;
    }

    .table thead {
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
    }

    .table thead th {
        border: 2px solid #40E0FF !important;
        padding: 15px !important;
        font-weight: 600 !important;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #40E0FF !important;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
        white-space: nowrap;
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
        padding: 12px !important;
        color: #ffffff !important;
        vertical-align: middle;
    }

    .table tbody tr:last-child {
        background: linear-gradient(135deg, rgba(64, 224, 255, 0.2) 0%, rgba(0, 217, 255, 0.1) 100%) !important;
        border-top: 3px solid #40E0FF !important;
    }

    .table tbody tr:last-child td {
        font-weight: bold;
        font-size: 16px;
        color: #40E0FF !important;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
    }

    /* Botones en tabla mejorados */
    .table .btn-sm {
        padding: 8px 15px !important;
        font-size: 14px;
        min-height: auto;
        border-radius: 6px !important;
        transition: all 0.3s ease;
    }

    .table .btn-danger {
        background: linear-gradient(135deg, #c2185b 0%, #e91e63 100%) !important;
        border: 2px solid #f48fb1 !important;
        color: #ffffff !important;
    }

    .table .btn-danger:hover {
        transform: scale(1.1) !important;
        box-shadow: 0 5px 15px rgba(244, 143, 177, 0.4) !important;
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

    .main-container {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Responsive */
    @media screen and (max-width: 768px) {
        .header-section {
            padding: 20px 15px !important;
        }

        .header-section h2 {
            font-size: 22px !important;
        }

        .content-section {
            padding: 20px !important;
        }

        .form-card {
            padding: 15px !important;
        }

        .btn-back, .btn-primary-custom {
            padding: 10px 20px !important;
            font-size: 14px !important;
        }

        .table thead th,
        .table tbody td {
            padding: 10px !important;
            font-size: 14px;
        }

        .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }

        .main-footer {
            padding: 15px;
        }
    }

    @media screen and (max-width: 576px) {
        .header-section h2 {
            font-size: 18px !important;
        }

        .header-section i {
            font-size: 24px !important;
            margin-right: 10px !important;
        }

        .btn-back, .btn-primary-custom {
            font-size: 12px !important;
            padding: 8px 15px !important;
        }

        .table {
            font-size: 12px;
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
</style>

    <title>Presupuestos - Gestión Administrativa</title>
    <link rel="shortcut icon" href="logp.png">
</head>

<div class="container-fluid">
    <a class="btn-back" onclick="history.back()">
        <i class="fas fa-arrow-left"></i> Regresar
    </a>
    
    <div class="main-container">
        <div class="header-section">
            <h2>
                <i class="fas fa-calculator"></i>
                GESTIÓN DE PRESUPUESTOS
            </h2>
        </div>
        <div class="content-section">
            <?php
                $nombre='PRUEBA';
            ?>

            <!-- Formulario de Servicios -->
            <div class="form-card">
                <h5><i class="fas fa-concierge-bell"></i> Agregar Servicios</h5>
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-md-8">
                            <label for="serv" class="form-label"><strong>Servicio:</strong></label>
                            <select data-live-search="true" id="mibuscador" name="serv" class="form-control" required>
                                <?php
                                    $sql_serv = "SELECT * FROM cat_servicios where serv_activo = 'SI'";
                                    $result_serv = $conexion->query($sql_serv);
                                    while ($row_serv = $result_serv->fetch_assoc()) {
                                        echo "<option value='" . $row_serv['id_serv'] . "'>" . $row_serv['serv_desc'] . "</option>";
                                    }
                                ?>
                            </select>  
                        </div>
                        <div class="col-md-2">
                            <label for="cantidad" class="form-label"><strong>Cantidad:</strong></label>
                            <input type="number" name="cantidad" class="form-control" value="" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <input type="submit" name="btnserv" class="btn btn-primary-custom btn-block" value="Agregar">
                        </div> 
                    </div>
                </form>
            </div>

            <!-- Formulario de Medicamentos -->
            <div class="form-card">
                <h5><i class="fas fa-pills"></i> Agregar Medicamentos y Materiales</h5>
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-md-8">
                            <label for="med" class="form-label"><strong>Medicamentos y materiales:</strong></label>
                            <select data-live-search="true" id="mibuscador2" name="med" class="form-control" required>
                                <?php
                                    $sql_serv = "SELECT * FROM item ";
                                    $result_serv = $conexion->query($sql_serv);
                                    while ($row_serv = $result_serv->fetch_assoc()) {
                                        echo "<option value='" . $row_serv['item_id'] . "'>" . $row_serv['item_name'] . ', '.$row_serv['item_grams'] . "</option>";
                                    }
                                ?>
                                        </select>
                        </div>
                        <div class="col-md-2">
                            <label for="cantidad2" class="form-label"><strong>Cantidad:</strong></label>
                            <input type="number" name="cantidad" class="form-control" value="" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <input type="submit" name="btnmed" class="btn btn-primary-custom btn-block" value="Agregar">
                        </div> 
                    </div>
                </form>
            </div>
            </div>
            
            <?php 
            if (isset($_POST['btnserv'])) {
                include "../../conexionbd.php";
                $nombre='PRUEBA';
                $serv_id =  mysqli_real_escape_string($conexion, (strip_tags($_POST["serv"], ENT_QUOTES)));
                $cantidad =  mysqli_real_escape_string($conexion, (strip_tags($_POST["cantidad"], ENT_QUOTES)));

                $resultado_serv = $conexion->query("SELECT * FROM cat_servicios where id_serv = $serv_id") or die($conexion->error);
                while ($row_serv = $resultado_serv->fetch_assoc()) {
                    $descripcion = $row_serv['serv_desc'];
                }
                          
                $fecha_actual = date("Y-m-d H:i:s");
                $ingresar2 = mysqli_query($conexion, 'INSERT INTO presupuesto (fecha,id_pac,nombre,id_serv,servicio,cantidad) values ("'.$fecha_actual.'",1,"'.$nombre.'","' . $serv_id . '","' . $descripcion .'",' . $cantidad . ') ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

                echo '<script type="text/javascript">window.location.href = "presupuesto.php?id_pac=1&nombre='.$nombre.'";</script>';
            }

            if (isset($_POST['btnmed'])) {
                include "../../conexionbd.php";
                $nombre=mysqli_real_escape_string($conexion, (strip_tags($_GET["nombre"], ENT_QUOTES)));
                $item_id =  mysqli_real_escape_string($conexion, (strip_tags($_POST["med"], ENT_QUOTES)));
                $cantidad =  mysqli_real_escape_string($conexion, (strip_tags($_POST["cantidad"], ENT_QUOTES)));

                $resultado_serv = $conexion->query("SELECT * FROM item where item_id = $item_id") or die($conexion->error);
                while ($row_serv = $resultado_serv->fetch_assoc()) {
                    $item_code = $row_serv['item_code'];
                    $descripcion = $row_serv['item_name'];
                }
                          
                $fecha_actual = date("Y-m-d H:i:s");
                $ingresar2 = mysqli_query($conexion, 'INSERT INTO presupuesto (fecha,id_pac,nombre,id_serv,servicio,cantidad) values ("'.$fecha_actual.'",1,"'.$nombre.'","'. $item_code .'","' . $descripcion .'",' . $cantidad . ') ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

                echo '<script type="text/javascript">window.location.href = "presupuesto.php?id_pac=1&nombre='.$nombre.'";</script>';
            }
            ?>
            
            <hr class="divider">
            
            <!-- Tabla de Presupuesto -->
            <div class="table-container">
                <table class="table table-striped table-hover mb-0" id="mytable">
                    <thead style="background-color: #2b2d7f; color: white;">
                        <tr>
                            <th style="padding: 15px;">#</th>
                            <th style="padding: 15px;">Fecha</th>
                            <th style="padding: 15px;">Descripción</th>
                            <th style="padding: 15px;">Cantidad</th>
                            <th style="padding: 15px;">Precio</th>
                            <th style="padding: 15px; text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                       
                        include "../../conexionbd.php";
                        
                        $id_pac=1;
                        $resultado3 = $conexion->query("SELECT * from presupuesto p, cat_servicios c where $id_pac=1 and c.id_serv=p.id_serv") or die($conexion->error);

                        $no = 1;
                        while ($row_lista_serv = $resultado3->fetch_assoc()) {
                            $fecha=date_create($row_lista_serv['fecha']);
                            $precio = $row_lista_serv['serv_costo'] * 1.16;
                            $subtottal=$precio*$row_lista_serv['cantidad'];
                            echo '<tr>'
                                . '<td style="padding: 12px;">' . $no . '</td>'
                                . '<td style="padding: 12px;">' . date_format($fecha,"d-m-Y") . '</td>'
                                . '<td style="padding: 12px;">' . $row_lista_serv['servicio'] . '</td>'
                                . '<td style="padding: 12px; text-align: center;">' . $row_lista_serv['cantidad'] . '</td>'
                                . '<td style="padding: 12px; text-align: right;">$' . number_format($subtottal, 2). '</td>'
                                . '<td style="padding: 12px; text-align: center;"> <a class="btn btn-danger btn-sm" href="eliminar.php?q=eliminar_serv&id_presupuesto= ' . $row_lista_serv['id_presupuesto'] . '&id_pac='.$row_lista_serv['id_pac'].'&nombre='.$nombre.'" onclick="return confirm(\'¿Está seguro de eliminar este elemento?\')"><i class="fa fa-trash"></i></a></td>';
                            echo '</tr>';
                            $total= $subtottal + $total;
                            $no++;
                        } 
                        
                        $resultado3 = $conexion->query("SELECT * from presupuesto p, item i where id_pac=$id_pac and i.item_code=p.id_serv") or die($conexion->error);
                        while ($row_lista_serv = $resultado3->fetch_assoc()) {
                            $fecha=date_create($row_lista_serv['fecha']);
                            $precio = $row_lista_serv['item_price'] * 1.16;
                            $subtottal=$precio*$row_lista_serv['cantidad'];
                            echo '<tr>'
                                . '<td style="padding: 12px;">' . $no . '</td>'
                                . '<td style="padding: 12px;">' . date_format($fecha,"d-m-Y") . '</td>'
                                . '<td style="padding: 12px;">' . $row_lista_serv['servicio'] . '</td>'
                                . '<td style="padding: 12px; text-align: center;">' . $row_lista_serv['cantidad'] . '</td>'
                                . '<td style="padding: 12px; text-align: right;">$' . number_format($subtottal, 2). '</td>'
                                . '<td style="padding: 12px; text-align: center;"> <a class="btn btn-danger btn-sm" href="eliminar.php?q=eliminar_serv&id_presupuesto= ' . $row_lista_serv['id_presupuesto'] . '&id_pac='.$row_lista_serv['id_pac'].'&nombre='.$nombre.'" onclick="return confirm(\'¿Está seguro de eliminar este elemento?\')"><i class="fa fa-trash"></i></a></td>';
                            echo '</tr>';
                            $total= $subtottal + $total;
                            $no++;
                        } 
                        ?>
                        <tr style="background: #f8f9fa; border-top: 3px solid #2b2d7f;">
                            <td colspan="4" style="padding: 15px; text-align: right; font-weight: bold; font-size: 16px; color: #2b2d7f;">TOTAL:</td>
                            <td style="padding: 15px; text-align: right; font-weight: bold; font-size: 18px; color: #28a745;"><?php echo "$ " . number_format($total, 2); ?></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
</div>
</div>
<footer class="main-footer">
    <?php
    include("../../template/footer.php");
    ?>
</footer>

<!-- FastClick -->
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

<script>
    document.oncontextmenu = function () {
        return false;
    }
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#mibuscador').select2();
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#mibuscador2').select2();
    });
</script>
</body>

</html>