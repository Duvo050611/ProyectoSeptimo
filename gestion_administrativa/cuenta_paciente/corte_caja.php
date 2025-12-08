<?php
session_start();
//include "../conexionbd.php";

$usuario = $_SESSION['login'];
include "../../gestion_administrativa/header_administrador.php";

$resultado=$conexion->query("select * from reg_usuarios") or die($conexion->error);
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
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
        max-width: 1000px !important;
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

    /* Report Card mejorado */
    .report-card {
        background: linear-gradient(135deg, rgba(15, 52, 96, 0.8) 0%, rgba(22, 33, 62, 0.8) 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 12px !important;
        padding: 30px !important;
        margin-bottom: 25px !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 6px 20px rgba(64, 224, 255, 0.15) !important;
        color: #ffffff !important;
    }

    .report-card:hover {
        transform: translateY(-5px) !important;
        border-color: #00D9FF !important;
        box-shadow: 0 10px 30px rgba(64, 224, 255, 0.3) !important;
    }

    .report-card h5 {
        color: #40E0FF !important;
        font-weight: 600 !important;
        margin-bottom: 25px !important;
        padding-bottom: 15px !important;
        border-bottom: 2px solid rgba(64, 224, 255, 0.3) !important;
        font-size: 20px !important;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
    }

    .report-card h5 i {
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
        cursor: pointer;
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

    .btn-excel {
        background: linear-gradient(135deg, #388e3c 0%, #4caf50 100%) !important;
        border: 2px solid #66bb6a !important;
        border-radius: 25px !important;
        padding: 12px 25px !important;
        font-weight: 600 !important;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 187, 106, 0.3);
        color: #ffffff !important;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .btn-excel::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            45deg,
            transparent,
            rgba(102, 187, 106, 0.1),
            transparent
        );
        transform: rotate(45deg);
        transition: all 0.6s ease;
    }

    .btn-excel:hover::before {
        left: 100%;
    }

    .btn-excel:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 10px 25px rgba(102, 187, 106, 0.4) !important;
        background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%) !important;
        border-color: #a5d6a7 !important;
        color: #ffffff !important;
        text-decoration: none;
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

    /* Date Container mejorado */
    .date-container {
        background: linear-gradient(135deg, rgba(15, 52, 96, 0.6) 0%, rgba(22, 33, 62, 0.6) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 10px !important;
        padding: 20px !important;
        box-shadow: 0 4px 15px rgba(64, 224, 255, 0.1) !important;
        margin-bottom: 20px !important;
    }

    /* Button Container mejorado */
    .btn-container {
        display: flex;
        gap: 15px;
        align-items: end;
    }

    .icon-excel {
        width: 24px;
        height: 24px;
        margin-right: 8px;
        filter: brightness(0) invert(1);
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

        .report-card {
            padding: 20px !important;
        }

        .btn-back, .btn-primary-custom, .btn-excel {
            padding: 10px 20px !important;
            font-size: 14px !important;
        }

        .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }

        .main-footer {
            padding: 15px;
        }

        .btn-container {
            flex-direction: column;
            gap: 10px;
            align-items: stretch !important;
        }
        
        .btn-container .btn {
            width: 100%;
            text-align: center;
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

        .btn-back, .btn-primary-custom, .btn-excel {
            font-size: 12px !important;
            padding: 8px 15px !important;
        }

        .date-container {
            padding: 15px !important;
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

    /* Estilos para inputs date */
    input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1) brightness(2);
        cursor: pointer;
    }

    input[type="date"]::-webkit-calendar-picker-indicator:hover {
        opacity: 0.8;
    }
</style>

    <script>
        // Write on keyup event of keyword input element
        $(document).ready(function () {
            $("#search").keyup(function () {
                _this = this;
                // Show only matching TR, hide rest of them
                $.each($("#mytable tbody tr"), function () {
                    if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
                        $(this).hide();
                    else
                        $(this).show();
                });
            });
        });
    </script>
    <title>Corte de Caja - Reportes Financieros</title>
</head>
<body>

<div class="container-fluid">
    <a class="btn-back" onclick="history.back()">
        <i class="fas fa-arrow-left"></i> Regresar
    </a>
    
    <div class="main-container">
        <div class="header-section">
            <h2>
                <i class="fas fa-cash-register"></i>
                CORTE DE CAJA - REPORTES FINANCIEROS
            </h2>
        </div>
        
        <div class="content-section">
            <form action="pdf_cortecaja.php" target="_blank" method="POST">
                <div class="report-card">
                    <h5><i class="fas fa-calendar-alt"></i> Reporte por Rango de Fechas</h5>
                    
                    <div class="date-container">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label for="anio" class="form-label">Fecha Inicial:</label>
                                <input type="date" class="form-control" name="anio" id="anio" required>
                            </div>
                            <div class="col-md-3">
                                <label for="aniofinal" class="form-label">Fecha Final:</label>
                                <input type="date" class="form-control" name="aniofinal" id="aniofinal" required>
                            </div>
                            <div class="col-md-6">
                                <div class="btn-container">
                                    <button type="submit" class="btn btn-primary-custom">
                                        <i class="fas fa-file-pdf"></i> Generar PDF
                                    </button>
                                    <a href="excel_corte.php" class="btn btn-excel">
                                        <img src="https://img.icons8.com/color/24/000000/ms-excel.png" class="icon-excel" alt="Excel"/>
                                        Exportar Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.magnific-popup.min.js"></script>
<script src="js/aos.js"></script>
<script src="js/main.js"></script>

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

<script>
    // Establecer fecha actual como valor por defecto
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        const fechaInicialInput = document.getElementById('anio');
        const fechaFinalInput = document.getElementById('aniofinal');
        
        if (!fechaInicialInput.value) {
            fechaInicialInput.value = today;
        }
        if (!fechaFinalInput.value) {
            fechaFinalInput.value = today;
        }
    });
</script>

</body>
</html>