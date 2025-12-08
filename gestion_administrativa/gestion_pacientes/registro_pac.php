<?php
session_start();
require "../../estados.php";
include "../../conexionbd.php";
$conexion = ConexionBD::getInstancia()->getConexion();
include "../header_administrador.php";
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
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldLv/Pr4nhuBviF5jGqQK/5i2Q5iZ64dxBl+zOZ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/main.js"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <script>
    $(document).ready(function() {
        $("#search").keyup(function() {
            var value = $(this).val().toLowerCase();
            $("#mytable tbody tr, #mytable-urg tbody tr, #mytable-amb tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
    </script>
    
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

    .container {
        position: relative;
        z-index: 1;
        padding-top: 30px;
        padding-bottom: 50px;
        max-width: 100%;
        margin: 0 auto;
    }

    /* Título principal mejorado */
    .thead {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 15px !important;
        padding: 20px 30px !important;
        margin-bottom: 40px !important;
        box-shadow: 0 8px 30px rgba(64, 224, 255, 0.3);
        position: relative;
        overflow: hidden;
        color: #ffffff !important;
        font-size: 24px !important;
        text-align: center;
        letter-spacing: 2px;
        text-shadow: 0 0 20px rgba(64, 224, 255, 0.5);
    }

    .thead::before {
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

    .thead strong {
        position: relative;
        z-index: 1;
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

    .btn-custom {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 50px;
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

    /* Estilos especiales para botón rojo personalizado */
    .btn[style*="#FF5733"] {
        background: linear-gradient(135deg, #d32f2f 0%, #f44336 100%) !important;
        color: #ffffff !important;
        border-color: #ef5350 !important;
    }

    .btn[style*="#FF5733"]:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 10px 25px rgba(239, 83, 80, 0.4) !important;
        background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%) !important;
        border-color: #e57373 !important;
    }

    /* Barra de búsqueda mejorada */
    .search-bar {
        margin-bottom: 30px;
    }

    .search-bar .form-control {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 25px !important;
        padding: 15px 25px !important;
        color: #ffffff !important;
        font-size: 16px;
        box-shadow: 0 4px 15px rgba(64, 224, 255, 0.2);
        transition: all 0.3s ease;
    }

    .search-bar .form-control::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }

    .search-bar .form-control:focus {
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

    /* Celdas con color de área */
    .table tbody td[style*="background-color: #2b2d7f"] {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        color: #40E0FF !important;
        font-weight: 600;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
    }

    /* Botones dentro de la tabla */
    .table .btn-sm {
        padding: 8px 15px !important;
        font-size: 14px;
        min-height: auto;
    }

    .table .btn-primary.btn-sm {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
    }

    .table .btn-warning.btn-sm {
        background: linear-gradient(135deg, #f57c00 0%, #ff9800 100%) !important;
    }

    /* Tabs de navegación */
    .nav-tabs {
        border-bottom: 2px solid #40E0FF !important;
        margin-bottom: 30px;
    }

    .nav-tabs .nav-link {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border: 2px solid #40E0FF !important;
        border-bottom: none;
        color: #ffffff !important;
        margin-right: 10px;
        border-radius: 15px 15px 0 0 !important;
        padding: 12px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link:hover {
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        color: #40E0FF !important;
    }

    .nav-tabs .nav-link.active {
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        color: #40E0FF !important;
        border-color: #40E0FF !important;
        box-shadow: 0 4px 15px rgba(64, 224, 255, 0.3);
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

    /* Responsive */
    @media screen and (max-width: 768px) {
        .thead {
            padding: 15px 20px !important;
            font-size: 18px !important;
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

        .container {
            padding-left: 15px;
            padding-right: 15px;
        }

        .main-footer {
            margin-left: 0 !important;
            padding: 15px;
        }
    }

    @media screen and (max-width: 576px) {
        .btn-custom {
            font-size: 0.75rem !important;
            padding: 8px 15px !important;
            min-height: 40px;
        }

        .table {
            font-size: 12px;
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

    .container > * {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Iconos dentro de botones */
    .btn i {
        transition: transform 0.3s ease;
    }

    .btn:hover i {
        transform: scale(1.2);
    }

    /* Asegurar que no haya overflow horizontal */
    .content-wrapper,
    .container-fluid,
    .container {
        overflow-x: hidden;
    }

    /* Grid system adjustments */
    .g-3 {
        --bs-gutter-x: 1rem;
        --bs-gutter-y: 1rem;
    }

    .mb-4 {
        margin-bottom: 1.5rem !important;
    }

    /* Centrado adicional para el footer */
    footer.main-footer {
        clear: both;
        display: block;
        width: 100%;
        max-width: 100vw;
    }
</style>
    
    <title>Creación de Paciente</title>
    <link rel="shortcut icon" href="logp.png">
</head>
<body>
<div class="container">
    <div class="thead"><strong><center>ESTUDIOS OCULARES</center></strong></div>
    <br>
    <div class="row mb-4 g-3">
        <div class="col-sm-6 col-md-3">
            <a href="../gestion_pacientes/paciente.php" class="btn btn-primary btn-custom w-100">
                <i class="fas fa-user-plus"></i> Nuevo Paciente
            </a>
        </div>
        <div class="col-sm-6 col-md-3">
            <a href="../cartas_consentimientos/consent_lis_pac2.php" class="btn btn-danger btn-custom w-100">
                <i class="fas fa-file-pdf"></i> Imprimir Documentos
            </a>
        </div>
        <div class="col-sm-6 col-md-3">
            <a href="../cuenta_paciente/vista_ahosp.php" class="btn btn-warning btn-custom w-100">
                <i class="fas fa-bed"></i> Asignar Habitación
            </a>
        </div>
        <?php 
        $usuario = $_SESSION['login'];
        $rol = $usuario['id_rol'];
        if ($rol == 5 || $rol == 1) { ?>
        <div class="col-sm-6 col-md-3">
            <a href="../global_pac/pac_global.php" class="btn btn-danger btn-custom w-100"
                style="background-color: #FF5733;">
                <i class="fas fa-users"></i> Ver Expedientes
            </a>
        </div>
        <?php } ?>
        <div class="col-sm-6 col-md-3">
            <a href="vista_ine.php" class="btn btn-success btn-custom w-100">
                <i class="fas fa-id-card"></i> Subir INE
            </a>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="form-group search-bar">
        <input type="text" class="form-control" id="search" placeholder="Buscar pacientes...">
    </div>

    <!-- Hospitalized Patients Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="mytable">
            <thead>
                <tr>
                    <th>Editar</th>
                    <th>Cuenta</th>
                    <th>Expediente</th>
                    <th>Nombre</th>
                    <th>Edad</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Teléfono</th>
                    <th>Área</th>
                    <th>Fecha de Ingreso</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $resultado = $conexion->query("
                    SELECT p.*, d.fecha AS fecha_ing, d.id_atencion, d.area, c.num_cama
                    FROM paciente p
                    INNER JOIN dat_ingreso d ON p.Id_exp = d.Id_exp
                    LEFT JOIN cat_camas c ON d.id_atencion = c.id_atencion
                    WHERE d.activo = 'SI'
                    AND (TRIM(UPPER(d.area)) IN ('HOSPITALIZACION', 'HOSPITALIZACIÓN', 'TERAPIA INTENSIVA', 'OBSERVACIÓN', 'OBSERVACION', 'QUIROFANO', 'QUIRÓFANO', 'ENDOSCOPÍA', 'AMBULATORIO'))
                    ORDER BY d.fecha DESC
                ") or die($conexion->error);
                while ($f = mysqli_fetch_array($resultado)) {
                ?>
                <tr>
                    <td class="text-center">
                        <a href="edit_paciente.php?Id_exp=<?php echo $f['Id_exp']; ?>&id_atencion=<?php echo $f['id_atencion']; ?>"
                            class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                    <td class="text-center">
                        <a href="../cuenta_paciente/detalle_cuenta.php?id_at=<?php echo $f['id_atencion']; ?>&id_exp=<?php echo $f['Id_exp']; ?>&id_usua=<?php echo $usuario['id_usua']; ?>&rol=<?php echo $usuario['id_rol']; ?>"
                            class="btn btn-warning btn-sm">
                            <i class="fas fa-dollar-sign"></i>
                        </a>
                    </td>
                    <td><strong><?php echo $f['Id_exp']; ?></strong></td>
                    <td><strong><?php echo $f['papell'] . ' ' . $f['sapell'] . ' ' . $f['nom_pac']; ?></strong></td>
                    <td><strong><?php echo $f['edad']; ?></strong></td>
                    <td><strong><?php echo date_format(date_create($f['fecnac']), "d/m/Y"); ?></strong></td>
                    <td><strong><?php echo $f['tel']; ?></strong></td>
                    <td class="text-center" style="background-color: #2b2d7f; color: white;">
                        <strong><?php echo !empty($f['area']) ? $f['area'] : $f['area']; ?></strong>
                    </td>
                    <td><strong><?php echo date_format(date_create($f['fecha_ing']), "d/m/Y h:i A"); ?></strong></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<footer class="main-footer">
    <?php include "../../template/footer.php"; ?>
</footer>

<script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<script src="../../template/plugins/fastclick/fastclick.min.js"></script>
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
</body>
</html>