<?php
session_start();

if (!isset($_SESSION['login'])) {
  header("Location: ../index.php");
}

include("../header_administrador.php");
?>

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
    padding: 30px;
    max-width: 100%;
}

/* Botón Regresar Mejorado */
.btn-back {
    background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
    border: 2px solid #40E0FF !important;
    border-radius: 25px !important;
    padding: 12px 25px !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 15px rgba(64, 224, 255, 0.2);
    display: inline-flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    margin-bottom: 20px;
}

.btn-back:hover {
    transform: translateY(-3px) !important;
    box-shadow: 0 10px 25px rgba(64, 224, 255, 0.4) !important;
    background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
    border-color: #00D9FF !important;
    color: #40E0FF !important;
    text-decoration: none;
}

/* Contenedor Principal Mejorado */
.main-container {
    background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
    border: 2px solid #40E0FF !important;
    border-radius: 15px !important;
    box-shadow: 0 8px 30px rgba(64, 224, 255, 0.3);
    overflow: hidden;
    margin-bottom: 30px;
}

/* Header Section Mejorado */
.header-section {
    background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
    color: #ffffff !important;
    padding: 25px;
    text-align: center;
    border-bottom: 2px solid #40E0FF !important;
    position: relative;
    overflow: hidden;
}

.header-section::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(64, 224, 255, 0.1) 0%, transparent 70%);
    animation: pulse 3s ease-in-out infinite;
    pointer-events: none;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 0.5; }
    50% { transform: scale(1.1); opacity: 0.8; }
}

.header-section h2 {
    margin: 0;
    font-size: 28px;
    font-weight: 600;
    text-shadow: 0 0 20px rgba(64, 224, 255, 0.5);
    letter-spacing: 2px;
    position: relative;
    z-index: 1;
}

.header-section i {
    font-size: 32px;
    margin-right: 15px;
    opacity: 0.9;
    text-shadow: 0 0 15px rgba(64, 224, 255, 0.8);
}

/* Leyenda de Estados Mejorada */
.legend-container {
    background: rgba(15, 52, 96, 0.5) !important;
    border: 2px solid rgba(64, 224, 255, 0.3) !important;
    border-radius: 10px;
    padding: 20px;
    margin: 20px;
    color: #ffffff;
}

.legend-container strong {
    color: #40E0FF;
    font-size: 16px;
    text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
}

.legend-item {
    display: inline-flex;
    align-items: center;
    margin: 5px 15px 5px 0;
    font-size: 13px;
    font-weight: 500;
    color: #ffffff;
}

.status-indicator {
    display: inline-block;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    margin-right: 8px;
    box-shadow: 0 0 10px currentColor;
}

.status-occupied { 
    background: #40E0FF;
    box-shadow: 0 0 15px #40E0FF;
}

.status-discharged { 
    background: #4caf50;
    box-shadow: 0 0 15px #4caf50;
}

.status-maintenance { 
    background: #e91e63;
    box-shadow: 0 0 15px #e91e63;
}

.status-releasing { 
    background: #ff9800;
    box-shadow: 0 0 15px #ff9800;
}

.status-available { 
    background: #9e9e9e;
    box-shadow: 0 0 15px #9e9e9e;
}

/* Botón Imprimir Mejorado */
.btn-print {
    background: linear-gradient(135deg, #388e3c 0%, #4caf50 100%) !important;
    border: 2px solid #66bb6a !important;
    border-radius: 25px !important;
    padding: 12px 25px !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 15px rgba(102, 187, 106, 0.3);
    display: inline-flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
}

.btn-print:hover {
    transform: translateY(-3px) !important;
    box-shadow: 0 10px 25px rgba(102, 187, 106, 0.4) !important;
    background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%) !important;
    border-color: #a5d6a7 !important;
    color: #ffffff !important;
    text-decoration: none;
}

/* Section Header Mejorado */
.census-section {
    background: transparent;
    margin: 20px;
    overflow: hidden;
}

.section-header {
    background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
    color: #40E0FF !important;
    padding: 15px 20px;
    margin: 0;
    border: 2px solid #40E0FF !important;
    border-radius: 10px 10px 0 0;
    font-size: 18px;
    font-weight: 600;
    text-align: center;
    text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
    letter-spacing: 1px;
}

/* Search Container Mejorado */
.search-container {
    padding: 20px;
    background: rgba(15, 52, 96, 0.3);
    border: 2px solid rgba(64, 224, 255, 0.2);
    border-top: none;
}

.form-control {
    background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
    border: 2px solid #40E0FF !important;
    border-radius: 25px !important;
    padding: 12px 20px !important;
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

/* Table Container Mejorado */
.table-container {
    background: rgba(15, 52, 96, 0.3);
    border: 2px solid rgba(64, 224, 255, 0.2);
    border-top: none;
    border-radius: 0 0 10px 10px;
    overflow-x: auto;
    padding: 20px;
}

/* Tabla Mejorada */
.table {
    color: #ffffff !important;
    margin: 0;
    width: 100%;
}

.table thead th {
    background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
    color: #40E0FF !important;
    border: 2px solid #40E0FF !important;
    padding: 15px 8px;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 1px;
    text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
    white-space: nowrap;
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    transform: translateX(5px);
}

.table tbody td {
    border: 1px solid rgba(64, 224, 255, 0.2) !important;
    padding: 10px 6px;
    vertical-align: middle;
    text-align: center;
    font-size: 12px;
    color: #ffffff !important;
}

.table-striped tbody tr:nth-of-type(odd) {
    background: rgba(15, 52, 96, 0.5) !important;
}

.table-striped tbody tr:nth-of-type(even) {
    background: rgba(22, 33, 62, 0.5) !important;
}

/* Estados de Celdas Mejorados */
td.fondo,
.table tbody td.fondo {
    background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
    color: #40E0FF !important;
    font-weight: 600;
    text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
    border: 1px solid #40E0FF !important;
}

td.fondo2,
.table tbody td.fondo2 {
    background: linear-gradient(135deg, #388e3c 0%, #4caf50 100%) !important;
    color: #ffffff !important;
    font-weight: 600;
    text-shadow: 0 0 10px rgba(76, 175, 80, 0.5);
    border: 1px solid #66bb6a !important;
}

td.fondo3,
.table tbody td.fondo3 {
    background: linear-gradient(135deg, #f57c00 0%, #ff9800 100%) !important;
    color: #ffffff !important;
    font-weight: 600;
    text-shadow: 0 0 10px rgba(255, 152, 0, 0.5);
    border: 1px solid #ffa726 !important;
}

td.cuenta,
.table tbody td.cuenta {
    background: linear-gradient(135deg, #c2185b 0%, #e91e63 100%) !important;
    color: #ffffff !important;
    font-weight: 600;
    text-shadow: 0 0 10px rgba(233, 30, 99, 0.5);
    border: 1px solid #f48fb1 !important;
}

/* Botones de Acción Mejorados */
.action-btn {
    border-radius: 20px !important;
    padding: 8px 12px !important;
    margin: 2px !important;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease !important;
    border: 2px solid transparent !important;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 35px;
    min-height: 35px;
}

.action-btn:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3) !important;
}

.btn-warning.action-btn {
    background: linear-gradient(135deg, #f57c00 0%, #ff9800 100%) !important;
    border-color: #ffa726 !important;
    color: #ffffff !important;
}

.btn-warning.action-btn:hover {
    background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%) !important;
    border-color: #ffcc80 !important;
}

.btn-success.action-btn {
    background: linear-gradient(135deg, #388e3c 0%, #4caf50 100%) !important;
    border-color: #66bb6a !important;
    color: #ffffff !important;
}

.btn-success.action-btn:hover {
    background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%) !important;
    border-color: #a5d6a7 !important;
}

.btn-danger.action-btn {
    background: linear-gradient(135deg, #c2185b 0%, #e91e63 100%) !important;
    border-color: #f48fb1 !important;
    color: #ffffff !important;
}

.btn-danger.action-btn:hover {
    background: linear-gradient(135deg, #e91e63 0%, #c2185b 100%) !important;
    border-color: #f8bbd0 !important;
}

/* Footer Mejorado y Centrado */
.main-footer {
    background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
    border-top: 2px solid #40E0FF !important;
    color: #ffffff !important;
    box-shadow: 0 -4px 20px rgba(64, 224, 255, 0.2);
    margin-top: 50px;
    margin-left: 0 !important;
    margin-right: 0 !important;
    padding: 25px 20px !important;
    text-align: center;
    width: 100%;
    position: relative;
    left: 0;
    right: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    min-height: 80px;
}

.wrapper > .main-footer {
    margin-left: 0 !important;
    width: 100% !important;
}

@media (min-width: 768px) {
    .sidebar-mini.sidebar-collapse .main-footer {
        margin-left: 50px !important;
    }
    
    .sidebar-mini:not(.sidebar-collapse) .main-footer {
        margin-left: 230px !important;
    }
}

body:not(.sidebar-mini) .main-footer {
    margin-left: 0 !important;
}

.main-footer p,
.main-footer strong {
    color: #ffffff !important;
    margin: 5px 0;
    font-size: 14px;
    line-height: 1.6;
    text-align: center;
    width: 100%;
    display: block;
}

.main-footer a {
    color: #ffffff !important;
    text-decoration: none;
    transition: color 0.3s ease;
}

.main-footer a:hover {
    color: #40E0FF !important;
    text-decoration: none;
}

footer.main-footer {
    clear: both;
    display: flex;
    width: 100%;
    max-width: 100vw;
    overflow-x: hidden;
}

.main-footer br {
    line-height: 1.4;
}

.main-footer > * {
    max-width: 100%;
    word-wrap: break-word;
}

/* Scrollbar Personalizado */
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

/* Animaciones de Entrada */
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

.main-container,
.legend-container,
.census-section {
    animation: fadeInUp 0.6s ease-out;
}

/* Responsive Design */
@media screen and (max-width: 768px) {
    .container-fluid {
        padding: 15px;
    }

    .main-container {
        margin: 10px 0;
        border-radius: 10px;
    }

    .census-section {
        margin: 10px;
    }

    .header-section {
        padding: 20px 15px;
    }

    .header-section h2 {
        font-size: 22px;
    }

    .legend-container {
        margin: 10px;
        padding: 15px;
    }

    .legend-item {
        font-size: 11px;
        margin: 5px 10px 5px 0;
    }

    .table thead th,
    .table tbody td {
        padding: 8px 4px;
        font-size: 11px;
    }

    .action-btn {
        padding: 6px 8px !important;
        margin: 1px !important;
        font-size: 12px;
        min-width: 30px;
        min-height: 30px;
    }

    .btn-back,
    .btn-print {
        font-size: 14px !important;
        padding: 10px 20px !important;
    }

    .main-footer {
        margin-left: 0 !important;
        padding: 20px 15px !important;
        font-size: 13px;
    }

    .main-footer p,
    .main-footer strong {
        font-size: 13px;
    }
}

@media screen and (max-width: 576px) {
    .header-section h2 {
        font-size: 18px;
    }

    .header-section i {
        font-size: 24px;
        margin-right: 10px;
    }

    .section-header {
        font-size: 16px;
        padding: 12px 15px;
    }

    .table {
        font-size: 10px;
    }

    .table thead th,
    .table tbody td {
        padding: 6px 3px;
        font-size: 10px;
    }

    .action-btn {
        padding: 5px 6px !important;
        font-size: 11px;
        min-width: 28px;
        min-height: 28px;
    }

    .legend-container {
        padding: 10px;
    }

    .legend-item {
        font-size: 10px;
        margin: 3px 8px 3px 0;
    }

    .status-indicator {
        width: 10px;
        height: 10px;
    }

    .main-footer {
        padding: 15px 10px !important;
        font-size: 12px;
    }

    .main-footer p,
    .main-footer strong {
        font-size: 11px;
    }
}

@media screen and (max-width: 400px) {
    .main-footer {
        padding: 12px 8px !important;
        font-size: 11px;
    }

    .main-footer p,
    .main-footer strong {
        font-size: 10px;
        line-height: 1.3;
    }
}
</style>

  <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
  <!-- FastClick -->
  <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
  <!-- AdminLTE App -->
  <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>


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

  <style>
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
    }

    .main-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin: 20px auto;
        padding: 0;
        overflow: hidden;
    }

    .header-section {
        background: linear-gradient(135deg, #2b2d7f 0%, #3949ab 100%);
        color: white;
        padding: 25px;
        text-align: center;
        margin-bottom: 0;
    }

    .header-section h2 {
        margin: 0;
        font-size: 28px;
        font-weight: 600;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .header-section i {
        font-size: 32px;
        margin-right: 15px;
        opacity: 0.9;
    }

    .section-header {
        background: linear-gradient(135deg, #2b2d7f 0%, #3949ab 100%);
        color: white;
        padding: 15px 20px;
        margin: 20px 0 0 0;
        border-radius: 10px 10px 0 0;
        font-size: 18px;
        font-weight: 600;
        text-align: center;
    }

    .census-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        margin: 20px;
        overflow: hidden;
    }

    .search-container {
        padding: 20px;
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }

    .form-control {
        border-radius: 8px;
        border: 2px solid #e9ecef;
        padding: 12px 15px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #2b2d7f;
        box-shadow: 0 0 0 0.2rem rgba(43, 45, 127, 0.25);
    }

    .btn-back {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        color: white;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .btn-back:hover {
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
    }

    .btn-print {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        border-radius: 8px;
        padding: 12px 25px;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        text-decoration: none;
        display: inline-block;
    }

    .btn-print:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        color: white;
        text-decoration: none;
    }

    .table-container {
        background: white;
        overflow-x: auto;
    }

    .table {
        margin: 0;
    }

    .table thead th {
        background: #2b2d7f;
        color: white;
        border: none;
        padding: 15px 8px;
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
        font-size: 13px;
    }

    .table tbody td {
        padding: 10px 6px;
        vertical-align: middle;
        text-align: center;
        border-color: #e9ecef;
        font-size: 12px;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f8f9fa;
    }

    /* Estados de camas mejorados */
    td.fondo {
        background: linear-gradient(135deg, #2b2d7f 0%, #3949ab 100%) !important;
        color: white !important;
        font-weight: 500;
    }

    td.fondo2 {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
        color: white !important;
        font-weight: 500;
    }

    td.fondo3 {
        background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%) !important;
        color: white !important;
        font-weight: 500;
    }

    td.cuenta {
        background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%) !important;
        color: white !important;
        font-weight: 500;
    }

    .action-btn {
        margin: 2px;
        border-radius: 6px;
        padding: 8px 12px;
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .btn-warning {
        background: linear-gradient(135deg, #ffc107 0%, #ffb302 100%);
        border: none;
    }

    .btn-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
    }

    .btn-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        border: none;
    }

    .status-indicator {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 8px;
    }

    .status-occupied { background: #2b2d7f; }
    .status-discharged { background: #28a745; }
    .status-maintenance { background: #dc3545; }
    .status-releasing { background: #fd7e14; }
    .status-available { background: #6c757d; }

    .legend-container {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin: 20px;
        border: 1px solid #e9ecef;
    }

    .legend-item {
        display: inline-flex;
        align-items: center;
        margin: 5px 15px 5px 0;
        font-size: 12px;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .main-container {
            margin: 10px;
            border-radius: 10px;
        }
        
        .census-section {
            margin: 10px;
        }
        
        .header-section {
            padding: 20px 15px;
        }
        
        .header-section h2 {
            font-size: 22px;
        }
        
        .table thead th,
        .table tbody td {
            padding: 6px 3px;
            font-size: 10px;
        }
        
        .action-btn {
            padding: 6px 8px;
            margin: 1px;
        }
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    <a class="btn-back" onclick="history.back()">
        <i class="fas fa-arrow-left"></i> Regresar
    </a>
    
    <div class="main-container">
        <div class="header-section">
            <h2>
                <i class="fas fa-hospital-user"></i>
                CENSO ADMINISTRATIVO DE PACIENTES
            </h2>
        </div>

        <!-- Leyenda de estados -->
        <div class="legend-container">
            <strong>Leyenda de Estados:</strong>
            <div class="mt-2">
                <span class="legend-item">
                    <span class="status-indicator status-occupied"></span>
                    Ocupado - Sin Alta
                </span>
                <span class="legend-item">
                    <span class="status-indicator status-discharged"></span>
                    Ocupado - Con Alta
                </span>
                <span class="legend-item">
                    <span class="status-indicator status-maintenance"></span>
                    Mantenimiento
                </span>
                <span class="legend-item">
                    <span class="status-indicator status-releasing"></span>
                    Por Liberar
                </span>
                <span class="legend-item">
                    <span class="status-indicator status-available"></span>
                    Disponible
                </span>
            </div>
        </div>

        <!-- Botón de impresión general -->
        <div style="padding: 0 20px 20px 20px;">
            <a href="../../gestion_administrativa/censo/pdf_censo_comp.php" class="btn-print" target="_blank">
                <i class="fas fa-print"></i> Imprimir Censo Completo
            </a>
        </div>

        <!-- Sección Hospitalización -->
        <div class="census-section">
            <div class="section-header">
                <i class="fas fa-hospital"></i> HOSPITALIZACIÓN - GESTIÓN ADMINISTRATIVA
            </div>

            <div class="search-container">
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="search" placeholder="🔍 Buscar paciente, habitación o médico...">
                    </div>
                </div>
            </div>

            <div class="table-container">
                <table class="table table-striped table-hover" id="mytable">
                    <thead>
                        <tr>
                            <th>Cuenta</th>
                            <th>Cambiar</th>
                            <th>Hab</th>
                            <th>Fecha Ingreso</th>
                            <th>Paciente</th>
                            <th>Edad</th>
                            <th>Motivo Ingreso</th>
                            <th>Exp</th>
                            <th>Médico Tratante</th>
                            <th>Alta Médica</th>
                            <th>Aviso de Alta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                            $sql = "SELECT * from cat_camas where TIPO ='HOSPITALIZACION' ORDER BY num_cama ASC ";
                            $result = $conexion->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                $id_at_cam = $row['id_atencion'];
                                $estatus = $row['estatus'];
                                $usuario = $_SESSION['login'];
                                $id_usua= $usuario['id_usua'];
                                $rol= $usuario['id_rol'];
                                $sql_tabla = "SELECT p.fecnac,p.Id_exp,p.folio, p.papell, p.sapell,p.nom_pac, di.fecha, di.motivo_recepcion, di.alta_med,ru.pre, ru.papell as nom_doc from dat_ingreso di, paciente p, reg_usuarios ru WHERE p.Id_exp = di.Id_exp and di.id_usua = ru.id_usua and di.id_atencion = $id_at_cam LIMIT 1";
                                $result_tabla = $conexion->query($sql_tabla);
                                $rowcount = mysqli_num_rows($result_tabla);
                                if ($rowcount != 0) {
                                    while ($row_tabla = $result_tabla->fetch_assoc()) {
                                        $alta=$row_tabla['alta_med'];
                                        if($alta=='SI'){
                                            echo '<td><a class="btn btn-warning action-btn" href="../cuenta_paciente/detalle_cuenta.php?id_at='.$id_at_cam.'&id_exp='. $row_tabla['folio'].'&id_usua='.$id_usua.'&rol='.$rol.'" title="Ver Cuenta"><i class="fas fa-dollar-sign"></i></a></td>';
                                            echo '<td><a class="btn btn-success action-btn" href="cambio.php?id_cama='.$row['num_cama'] .'&id_atencion='.$row['id_atencion'].'&tipo='.$row['tipo'].'&hab='.$row['habitacion'].'" title="Cambiar Cama"><i class="fas fa-bed"></i></a></td>';
                                            echo '<td class="fondo2">' . $row['num_cama'] . '</td>';
                                            echo '<td class="fondo2">' . date('d/m/Y H:i', strtotime($row_tabla['fecha'])) . '</td>';
                                            echo '<td class="fondo2">' . $row_tabla['papell'] . ' ' . $row_tabla['sapell'] . ' ' .$row_tabla['nom_pac'] . '</td>';
                                            echo '<td class="fondo2">' . calculaedad($row_tabla['fecnac']) .'</td>';
                                            echo '<td class="fondo2">' . $row_tabla['motivo_recepcion'] . '</td>';
                                            echo '<td class="fondo2">' . $row_tabla['folio'] . '</td>';
                                            echo '<td class="fondo2">' . $row_tabla['pre'] . ' ' . $row_tabla['nom_doc'] . '</td>';
                                            echo '<td class="fondo2">✅ ' . $row_tabla['alta_med'] . '</td>';
                                            echo '<td>-</td>';
                                            echo '</tr>';
                                        } else {
                                            echo '<td><a class="btn btn-warning action-btn" href="../cuenta_paciente/detalle_cuenta.php?id_at='.$id_at_cam.'&id_exp='. $row_tabla['folio'].'&id_usua='.$id_usua.'&rol='.$rol.'" title="Ver Cuenta"><i class="fas fa-dollar-sign"></i></a></td>';
                                            echo '<td><a class="btn btn-success action-btn" href="cambio.php?id_cama='.$row['num_cama'] .'&id_atencion='.$row['id_atencion'].'&tipo='.$row['tipo'].'&hab='.$row['habitacion'].'" title="Cambiar Cama"><i class="fas fa-bed"></i></a></td>';
                                            echo '<td class="fondo">' . $row['num_cama'] . '</td>';
                                            echo '<td class="fondo">' . date('d/m/Y H:i', strtotime($row_tabla['fecha'])) . '</td>';
                                            echo '<td class="fondo">' . $row_tabla['papell'] . ' ' . $row_tabla['sapell'] . ' ' .$row_tabla['nom_pac']  . '</td>';
                                            echo '<td class="fondo">' . calculaedad($row_tabla['fecnac']) . '</td>';
                                            echo '<td class="fondo">' . $row_tabla['motivo_recepcion'] . '</td>';
                                            echo '<td class="fondo">' . $row_tabla['folio'] . '</td>';
                                            echo '<td class="fondo">' . $row_tabla['pre'] . ' ' . $row_tabla['nom_doc'] . '</td>';
                                            echo '<td class="fondo">❌ ' . $row_tabla['alta_med'] . '</td>';
                                            echo '<td><a class="btn btn-danger action-btn" href="aviso_alta.php?id_atencion='.$id_at_cam.'" title="Aviso de Alta"><i class="fa fa-plus-square"></i></a></td>';
                                            echo '</tr>';
                                        }
                                    }
                                } elseif($estatus=="MANTENIMIENTO"){
                                    echo '<td class="cuenta">-</td>';
                                    echo '<td class="cuenta">-</td>';
                                    echo '<td class="cuenta">' . $row['num_cama'] . '</td>';
                                    echo '<td class="cuenta">🔧</td>';
                                    echo '<td class="cuenta">HABITACIÓN EN</td>';
                                    echo '<td class="cuenta">MANTENIMIENTO</td>';
                                    echo '<td class="cuenta">NO DISPONIBLE</td>';
                                    echo '<td class="cuenta">-</td>';
                                    echo '<td class="cuenta">-</td>';
                                    echo '<td class="cuenta">-</td>';
                                    echo '<td class="cuenta">-</td></tr>';
                                } elseif($estatus=="EN PROCESO DE LIBERA"){
                                    echo '<td class="fondo3">-</td>';
                                    echo '<td class="fondo3">-</td>';
                                    echo '<td class="fondo3">' . $row['num_cama'] . '</td>';
                                    echo '<td class="fondo3">⏳</td>';
                                    echo '<td class="fondo3">HABITACIÓN</td>';
                                    echo '<td class="fondo3">POR LIBERAR</td>';
                                    echo '<td class="fondo3">-</td>';
                                    echo '<td class="fondo3">-</td>';
                                    echo '<td class="fondo3">-</td>';
                                    echo '<td class="fondo3">-</td>';
                                    echo '<td class="fondo3">-</td></tr>';
                                } else {
                                    echo '<td>-</td>';
                                    echo '<td>-</td>';
                                    echo '<td>' . $row['num_cama'] . '</td>';
                                    echo '<td>✅</td>';
                                    echo '<td colspan="7" style="text-align: center; color: #28a745; font-weight: 600;">HABITACIÓN DISPONIBLE</td>';
                                    echo '</tr>';
                                }
                            }
                            ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Agregar tooltips a los botones
            $('[title]').tooltip();
        });
    </script>

</section>

<footer class="main-footer">
    <?php
   
 function bisiesto($anio_actual){
    $bisiesto=false;
    //probamos si el mes de febrero del año actual tiene 29 días
      if (checkdate(2,29,$anio_actual))
        $bisiesto=true;
        return $bisiesto;
 }

 function calculaedad($fecnac)
 {

$fecha_actual = date("Y-m-d");
$fecha_nac=$fecnac;
$fecha_de_nacimiento =strval($fecha_nac);

// separamos en partes las fechas
$array_nacimiento = explode ( "-", $fecha_de_nacimiento );
$array_actual = explode ( "-", $fecha_actual );

$anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos años
$meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses
$dias =  $array_actual[2] - $array_nacimiento[2]; // calculamos días

//ajuste de posible negativo en $días
if ($dias < 0)
{
    --$meses;

    //ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual
    switch ($array_actual[1]) {
           case 1:     $dias_mes_anterior=31; break;
           case 2:     $dias_mes_anterior=31; break;
           case 3:     
               if (bisiesto($array_actual[0]))
                {
                    $dias_mes_anterior=29; break;
                } else {
                    $dias_mes_anterior=28; break;
                }
               
           case 4:     $dias_mes_anterior=31; break;
           case 5:     $dias_mes_anterior=30; break;
           case 6:     $dias_mes_anterior=31; break;
           case 7:     $dias_mes_anterior=30; break;
           case 8:     $dias_mes_anterior=31; break;
           case 9:     $dias_mes_anterior=31; break;
           case 10:    $dias_mes_anterior=30; break;
           case 11:    $dias_mes_anterior=31; break;
           case 12:    $dias_mes_anterior=30; break;
    }

    $dias=$dias + $dias_mes_anterior;
}

//ajuste de posible negativo en $meses
if ($meses < 0)
{
    --$anos;
    $meses=$meses + 12;
}

 if($anos > "0" ){
   $edad = $anos." años";
}elseif($anos <="0" && $meses>"0"){
   $edad = $meses." meses";
    
}elseif($anos <="0" && $meses<="0" && $dias>"0"){
    $edad = $dias." días";
}

 return $edad;
}

    include("../../template/footer.php");
    ?>
  </footer>

  <script>
    document.oncontextmenu = function() {
      return false;
    }
  </script>
  <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
  <!-- FastClick -->
  <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
  <!-- AdminLTE App -->
  <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

</body>

</html>