<?php
session_start();
require "../../estados.php";
include "../../conexionbd.php";
include "../../gestion_administrativa/header_administrador.php";
$resultado = $conexion->query("select paciente.*, estados.nombre, estado_nac.nom_est_nac, municipios.nombre_m
from paciente inner join estados 
on paciente.id_edo=estados.id_edo
inner join estado_nac on paciente.id_edo_nac=estado_nac.id_edo_nac
inner join municipios on paciente.id_mun=municipios.id_mun") or die($conexion->error);
$usuario = $_SESSION['login'];

$sql_diag = "SELECT pa.*, da.* FROM paciente pa LEFT JOIN dat_ingreso da ON da.Id_exp = pa.Id_exp ORDER BY pa.Id_exp DESC";
$result_diag = $conexion->query($sql_diag);
$patients = [];
while ($row = mysqli_fetch_array($result_diag)) {
    $patients[] = [
        'Id_exp' => $row['Id_exp'],
        'nom_pac' => $row['nom_pac'],
        'papell' => $row['papell'],
        'sapell' => $row['sapell']
    ];
}
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
    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <script src="js/select2.js"></script>
    <link rel="stylesheet" href="../global_pac/css_busc/estilos2.css">
    <script src="../global_pac/js_busc/jquery.dataTables.min.js"></script>
    <title>NUEVO PACIENTE</title>

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
        padding-top: 20px;
        padding-bottom: 40px;
        max-width: 100%;
        margin: 0 auto;
        background: rgba(15, 52, 96, 0.1);
        border-radius: 15px;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    /* Header mejorado */
    .thead {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 15px !important;
        padding: 20px 30px !important;
        margin: 20px 0 !important;
        box-shadow: 0 8px 30px rgba(64, 224, 255, 0.3);
        position: relative;
        overflow: hidden;
        color: #ffffff !important;
        font-size: 22px !important;
        text-align: center;
        letter-spacing: 1px;
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

    /* Botones mejorados */
    .btn {
        border-radius: 25px !important;
        padding: 12px 25px !important;
        font-weight: 600 !important;
        letter-spacing: 0.5px;
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

    .btn-sm {
        padding: 8px 20px !important;
        font-size: 14px !important;
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

    .btn-custom {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 45px;
        margin: 5px;
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

    /* Formulario mejorado */
    .form-group {
        margin-bottom: 20px;
    }

    .form-control {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 8px !important;
        padding: 12px 15px !important;
        color: #ffffff !important;
        font-size: 14px;
        box-shadow: 0 2px 10px rgba(64, 224, 255, 0.1) !important;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #00D9FF !important;
        box-shadow: 0 0 0 0.2rem rgba(64, 224, 255, 0.25) !important;
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        color: #ffffff !important;
        outline: none;
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.6) !important;
    }

    .form-control:disabled {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
        border-color: rgba(64, 224, 255, 0.3) !important;
        color: rgba(255, 255, 255, 0.7) !important;
    }

    label {
        color: #ffffff !important;
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
        text-shadow: 0 0 5px rgba(64, 224, 255, 0.5);
    }

    /* Search Bar mejorado */
    .search-bar {
        margin-bottom: 25px;
    }

    .search-bar .form-control {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 25px !important;
        padding: 15px 25px !important;
        color: #ffffff !important;
        font-size: 16px;
        box-shadow: 0 4px 15px rgba(64, 224, 255, 0.2);
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
        line-height: 30px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 44px !important;
    }

    .select2-dropdown {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border: 2px solid #40E0FF !important;
        color: #ffffff !important;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border: 2px solid #40E0FF !important;
        color: #ffffff !important;
        border-radius: 8px;
    }

    .select2-container--default .select2-results__option[aria-selected=true] {
        background: linear-gradient(135deg, #40E0FF 0%, #00D9FF 100%) !important;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        color: #40E0FF !important;
    }

    /* Table para búsqueda */
    .content-table {
        background: linear-gradient(135deg, rgba(15, 52, 96, 0.8) 0%, rgba(22, 33, 62, 0.8) 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 12px !important;
        padding: 15px;
        box-shadow: 0 8px 25px rgba(64, 224, 255, 0.2);
        margin-top: 15px;
        overflow-x: auto;
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
        padding: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
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
        padding: 10px 12px;
        color: #ffffff !important;
        vertical-align: middle;
    }

    /* Alertas mejoradas */
    .alert {
        border-radius: 10px !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        box-shadow: 0 4px 15px rgba(64, 224, 255, 0.2) !important;
        position: relative;
        z-index: 1;
    }

    .alert-danger {
        background: linear-gradient(135deg, rgba(194, 24, 91, 0.9) 0%, rgba(233, 30, 99, 0.9) 100%) !important;
        color: #ffffff !important;
        border-color: #f48fb1 !important;
    }

    /* Divider mejorado */
    hr {
        border: 0;
        height: 2px;
        background: linear-gradient(135deg, #40E0FF 0%, #00D9FF 100%) !important;
        margin: 25px 0 !important;
        border-radius: 1px;
        box-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
    }

    /* Sección de contenido adicional */
    #contenido {
        background: linear-gradient(135deg, rgba(15, 52, 96, 0.6) 0%, rgba(22, 33, 62, 0.6) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 12px !important;
        padding: 20px;
        margin: 20px 0;
        box-shadow: 0 4px 15px rgba(64, 224, 255, 0.1);
    }

    #contenido h5 {
        color: #40E0FF !important;
        font-weight: 600;
        margin-bottom: 20px;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
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

    /* Centrado de texto */
    .text-center {
        text-align: center !important;
        margin: 25px 0;
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

        .btn-custom {
            min-height: 40px;
        }

        .form-control {
            padding: 10px 12px !important;
            font-size: 13px;
        }

        .container {
            padding-left: 15px;
            padding-right: 15px;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .main-footer {
            padding: 15px;
            margin-top: 30px;
        }

        .content-table {
            padding: 10px;
        }

        .table thead th,
        .table tbody td {
            padding: 8px 10px;
            font-size: 13px;
        }
    }

    @media screen and (max-width: 576px) {
        .thead {
            font-size: 16px !important;
            padding: 12px 15px !important;
        }

        .btn-custom {
            font-size: 0.75rem !important;
            padding: 8px 15px !important;
            min-height: 35px;
        }

        .btn-sm {
            padding: 6px 15px !important;
            font-size: 12px !important;
        }

        .form-control {
            font-size: 12px !important;
            padding: 8px 10px !important;
        }

        label {
            font-size: 13px;
        }

        .table {
            font-size: 12px;
        }

        #contenido {
            padding: 15px;
        }
    }

    /* Estilos para inputs de fecha */
    input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1) brightness(2);
        cursor: pointer;
    }

    input[type="date"]::-webkit-calendar-picker-indicator:hover {
        opacity: 0.8;
    }
</style>
</head>

<body>
    <div class="container">
        <a href="../gestion_pacientes/registro_pac.php" class="btn btn-danger btn-sm">Regresar</a>
        <hr>
        <?php if (isset($_GET['error'])) { ?>
        <div class="alert alert-danger alert-dismissible fade show col-sm-4" role="alert">
            <?php echo htmlspecialchars($_GET['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php } ?>
        <center>
            <div class="thead">
                <strong>DATOS DEL PACIENTE</strong>
            </div>
        </center>
        <br>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="fecha">Fecha y hora de registro:</label>
                        <input type="datetime" name="fecha" value="<?php echo date('d-m-Y H:i:s'); ?>"
                            class="form-control" disabled>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="form-group search-bar">
                        <label for="input-search">Buscar paciente:</label>
                        <select id="input-search" class="form-control select2" style="width: 100%;">
                            <option value="" disabled selected>Buscar paciente por nombre</option>
                            <?php foreach ($patients as $patient) {
                                $nombre_rec = htmlspecialchars($patient['nom_pac'] . ' ' . $patient['papell'] . ' ' . $patient['sapell']);
                                $url = "../gestion_pacientes/vista_pacientet.php?id=" . $patient['Id_exp'] .
                                    "&nombre=" . urlencode($patient['nom_pac']) .
                                    "&papell=" . urlencode($patient['papell']) .
                                    "&sapell=" . urlencode($patient['sapell']);
                                echo "<option value='$url'>$nombre_rec</option>";
                            } ?>
                        </select>
                    </div>
                    <div class="content-search">
                        <div class="content-table">
                            <table id="table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($patients as $patient) {
                                        $nombre_rec = htmlspecialchars($patient['nom_pac'] . ' ' . $patient['papell'] . ' ' . $patient['sapell']);
                                        $url = "../gestion_pacientes/vista_pacientet.php?id=" . $patient['Id_exp'] .
                                            "&nombre=" . urlencode($patient['nom_pac']) .
                                            "&papell=" . urlencode($patient['papell']) .
                                            "&sapell=" . urlencode($patient['sapell']);
                                    ?>
                                    <tr>
                                        <td><a href="<?php echo $url; ?>"
                                                class="btn btn-primary btn-sm"><?php echo $nombre_rec; ?></a></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <form action="insertar_paciente.php?id_usua=<?php echo $usuario['id_usua']; ?>" method="POST"
                onsubmit="return checkSubmit();">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="papell">Primer apellido:</label>
                            <input type="text" name="papell" id="papell" class="form-control"
                                placeholder="Apellido Paterno" onkeypress="return SoloLetras(event);"
                                style="text-transform:capitalize;"
                                onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();"
                                maxlength="50" required>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="sapell">Segundo apellido:</label>
                            <input type="text" name="sapell" id="sapell" class="form-control"
                                placeholder="Apellido Materno" onkeypress="return SoloLetras(event);"
                                style="text-transform:capitalize;"
                                onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();"
                                maxlength="50" required>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="nom_pac">Nombre(s):</label>
                            <input type="text" name="nom_pac" id="nom_pac" class="form-control"
                                placeholder="Nombre del Paciente" onkeypress="return SoloLetras(event);"
                                style="text-transform:capitalize;"
                                onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();"
                                maxlength="50" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="fecnac">Fecha de nacimiento:</label>
                            <input type="date" name="fecnac" id="fecnac" class="form-control" placeholder="dd/mm/aaaa" required>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="estado_nac">Estado de nacimiento:</label>
                            <select id="estado_nac" name="estado_nac" class="form-control" required>
                                <option value="" disabled selected>Selecciona el estado</option>
                                <?php
                                $resultadoEstados = $conexion->query("SELECT id_edo, nombre FROM estados WHERE activo=1 ORDER BY nombre ASC") or die($conexion->error);
                                while ($row = mysqli_fetch_assoc($resultadoEstados)) {
                                    echo "<option value='{$row['id_edo']}'>{$row['nombre']}</option>";
                                }
                                ?>
                                <option value="OT">Otros</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="sexo">Género:</label>
                            <select name="sexo" class="form-control" required>
                                <option value="" disabled selected>Seleccionar</option>
                                <option value="H">Hombre</option>
                                <option value="M">Mujer</option>
                                <option value="Se desconoce">Se desconoce</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="estado_res">Estado de residencia:</label>
                            <select id="estado_res" name="estado_res" class="form-control" required>
                                <option value="" disabled selected>Seleccionar</option>
                                <?php
                                $resultadoEstados = $conexion->query("SELECT id_edo, nombre FROM estados WHERE activo=1 ORDER BY nombre ASC") or die($conexion->error);
                                while ($row = mysqli_fetch_assoc($resultadoEstados)) {
                                    echo "<option value='{$row['id_edo']}'>{$row['nombre']}</option>";
                                }
                                ?>
                                <option value="OT">Otros</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="municipios">Municipio:</label>
                            <select id="municipios" name="municipios" class="form-control" required>
                                <option value="" disabled selected>Seleccionar</option>
                            </select>
                            <input type="text" id="municipio_manual" name="municipio_manual" class="form-control"
                                placeholder="Escriba el municipio" style="display: none;" />
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="localidad">Localidad:</label>
                            <input type="text" name="localidad" id="localidad" class="form-control"
                                placeholder="Localidad de residencia" style="text-transform:capitalize;"
                                onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();"
                                maxlength="100" required>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="dir">Dirección:</label>
                            <input type="text" name="dir" id="dir" class="form-control"
                                placeholder="Domicilio del Paciente" style="text-transform:capitalize;"
                                onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();"
                                maxlength="100" required>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="tel">Teléfono del paciente:</label>
                            <input type="text" name="tel" id="tel" placeholder="Teléfono a 10 dígitos"
                                class="form-control" onkeypress="return SoloNumeros(event);" maxlength="10" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="tipo_sangre">Tipo de sangre:</label>
                            <select name="tipo_sangre" id="tipo_sangre" class="form-control" required>
                                <option value="" disabled selected>Seleccionar</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="religion">Religión:</label>
                            <select name="religion" class="form-control">
                                <option value="" disabled selected>Seleccionar</option>
                                <option value="Católica">Católica</option>
                                <option value="Cristiana">Cristiana</option>
                                <option value="Protestante">Protestante</option>
                                <option value="Testigo de Jehová">Testigo de Jehová</option>
                                <option value="Otra">Otra</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="edociv">Estado civil:</label>
                            <select name="edociv" class="form-control" required>
                                <option value="" disabled selected>Seleccionar</option>
                                <option value="Soltero">Soltero</option>
                                <option value="Casado">Casado</option>
                                <option value="Viudo">Viudo</option>
                                <option value="Divorciado">Divorciado</option>
                                <option value="Unión libre">Unión libre</option>
                            </select>
                        </div>
                    </div>
                </div>
                <center>
                    <div class="thead">
                        <strong>DATOS DEL RESPONSABLE</strong>
                    </div>
                </center>
                <br>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="resp">Nombre completo:</label>
                            <input type="text" name="resp" id="resp" class="form-control"
                                placeholder="Nombre completo del responsable" onkeypress="return SoloLetras(event);"
                                style="text-transform:capitalize;"
                                onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();"
                                maxlength="40" required>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="paren">Parentesco:</label>
                            <select name="paren" class="form-control" required>
                                <option value="">Seleccionar</option>
                                <option value="Abuelo">Abuelo</option>
                                <option value="Padre">Padre</option>
                                <option value="Madre">Madre</option>
                                <option value="Tío">Tío</option>
                                <option value="Esposo">Esposo</option>
                                <option value="Esposa">Esposa</option>
                                <option value="Hijo">Hijo</option>
                                <option value="Hermano">Hermano</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="tel_resp">Teléfono:</label>
                            <input type="text" name="tel_resp" id="tel_resp"
                                placeholder="Teléfono del responsable a 10 dígitos" class="form-control"
                                onkeypress="return SoloNumeros(event);" maxlength="10" required>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="dom_resp">Dirección del responsable:</label>
                            <input type="text" name="dom_resp" id="dom_resp" placeholder="Domicilio del responsable"
                                class="form-control" style="text-transform:capitalize;"
                                onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();"
                                required>
                        </div>
                    </div>
                </div>
                <center>
                    <div class="thead">
                        <strong>HOJA FRONTAL</strong>
                    </div>
                </center>
                <br>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="id_usua1">Médico tratante:</label>
                            <select name="id_usua1" id="id_usua1" class="form-control select2" required>
                                <option value="" disabled selected>Seleccionar</option>
                                <?php
                                $resultado1 = $conexion->query("SELECT * FROM reg_usuarios WHERE u_activo='SI' AND id_rol=2 ORDER BY nombre ASC") or die($conexion->error);
                                while ($opciones = mysqli_fetch_assoc($resultado1)) {
                                    echo "<option value='{$opciones['id_usua']}'>{$opciones['nombre']} {$opciones['papell']} {$opciones['sapell']}</option>";
                                }
                                ?>
                                <option value="OTRO">Otros</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="id_usua2">Médico tratante 2:</label>
                            <select name="id_usua2" id="id_usua2" class="form-control select2">
                                <option value="" disabled selected>Seleccionar</option>
                                <?php
                                $resultado1 = $conexion->query("SELECT * FROM reg_usuarios WHERE u_activo='SI' AND id_rol=2 ORDER BY nombre ASC") or die($conexion->error);
                                while ($opciones = mysqli_fetch_assoc($resultado1)) {
                                    echo "<option value='{$opciones['id_usua']}'>{$opciones['nombre']} {$opciones['papell']} {$opciones['sapell']}</option>";
                                }
                                ?>
                                <option value="OTRO">Otros</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="id_usua3">Médico tratante 3:</label>
                            <select name="id_usua3" id="id_usua3" class="form-control select2">
                                <option value="" disabled selected>Seleccionar</option>
                                <?php
                                $resultado1 = $conexion->query("SELECT * FROM reg_usuarios WHERE u_activo='SI' AND id_rol=2 ORDER BY nombre ASC") or die($conexion->error);
                                while ($opciones = mysqli_fetch_assoc($resultado1)) {
                                    echo "<option value='{$opciones['id_usua']}'>{$opciones['nombre']} {$opciones['papell']} {$opciones['sapell']}</option>";
                                }
                                ?>
                                <option value="OTRO">Otros</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="id_usua4">Médico tratante 4:</label>
                            <select name="id_usua4" id="id_usua4" class="form-control select2">
                                <option value="" disabled selected>Seleccionar</option>
                                <?php
                                $resultado1 = $conexion->query("SELECT * FROM reg_usuarios WHERE u_activo='SI' AND id_rol=2 ORDER BY nombre ASC") or die($conexion->error);
                                while ($opciones = mysqli_fetch_assoc($resultado1)) {
                                    echo "<option value='{$opciones['id_usua']}'>{$opciones['nombre']} {$opciones['papell']} {$opciones['sapell']}</option>";
                                }
                                ?>
                                <option value="OTRO">Otros</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="id_usua5">Médico tratante 5:</label>
                            <select name="id_usua5" id="id_usua5" class="form-control select2">
                                <option value="" disabled selected>Seleccionar</option>
                                <?php
                                $resultado1 = $conexion->query("SELECT * FROM reg_usuarios WHERE u_activo='SI' AND id_rol=2 ORDER BY nombre ASC") or die($conexion->error);
                                while ($opciones = mysqli_fetch_assoc($resultado1)) {
                                    echo "<option value='{$opciones['id_usua']}'>{$opciones['nombre']} {$opciones['papell']} {$opciones['sapell']}</option>";
                                }
                                ?>
                                <option value="OTRO">Otros</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="habitacion">Seleccionar habitación:</label>
                            <select id="cama" name="habitacion" class="form-control" required>
                                <option value="" disabled selected>Seleccionar</option>
                                <?php
                                $resultado1 = $conexion->query("SELECT * FROM cat_camas WHERE estatus='LIBRE' ORDER BY num_cama ASC") or die($conexion->error);
                                while ($opciones = mysqli_fetch_assoc($resultado1)) {
                                    echo "<option value='{$opciones['id']}'>{$opciones['num_cama']} {$opciones['tipo']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="container" id="contenido">
                    <h5>Registro de nuevo médico</h5>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Nombre completo:</label>
                                <input type="text" name="papell_med" class="form-control" placeholder="Nombre completo">
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class грамотность="form-group">
                            <label for="motivo_atn">Motivo de atención:</label>
                            <select name="motivo_atn" class="form-control" required>
                                <option value="" disabled selected>Seleccionar</option>
                                <option value="Consulta oftalmológica">Consulta oftalmológica</option>
                                <!-- <option value="Cirugía">Cirugía</option> -->
                                <option value="Cirugía programada">Cirugía programada</option>
                                <option value="Cirugía de urgencia">Cirugía de urgencia</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="alergias">Alergias:</label>
                            <input type="text" name="alergias" class="form-control" placeholder="Alergias del paciente"
                                onkeypress="return SoloLetras(event);" style="text-transform:capitalize;"
                                onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();"
                                required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="tipo_a">Especialidad:</label>
                            <select name="tipo_a" class="form-control select2" required>
                                <option value="" disabled selected>Seleccionar</option>
                                <?php
                                $resultadoaseg = $conexion->query("SELECT * FROM cat_espec WHERE espec_activo='SI' ORDER BY espec ASC") or die($conexion->error);
                                while ($opcionesaseg = mysqli_fetch_assoc($resultadoaseg)) {
                                    echo "<option value='{$opcionesaseg['espec']}'>{$opcionesaseg['espec']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <center>
                    <div class="thead">
                        <strong>DATOS FINANCIEROS</strong>
                    </div>
                </center>
                <br>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="aseg">Aseguradora:</label>
                            <select name="aseg" class="form-control" required>
                                <option value="">Seleccionar</option>
                                <?php
                                $resultadoaseg = $conexion->query("SELECT * FROM cat_aseg WHERE aseg_activo='SI' ORDER BY aseg ASC") or die($conexion->error);
                                while ($opcionesaseg = mysqli_fetch_assoc($resultadoaseg)) {
                                    echo "<option value='{$opcionesaseg['id_aseg']}'>{$opcionesaseg['aseg']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="banco">Forma de pago:</label>
                            <select name="banco" class="form-control" required>
                                <option value="">Seleccionar</option>
                                <option value="EFECTIVO">EFECTIVO</option>
                                <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                                <option value="DEPOSITO">DEPOSITO</option>
                                <option value="TARJETA">TARJETA</option>
                                <option value="ASEGURADORA">ASEGURADORA</option>
                                <option value="OTROS">OTROS</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="aval">Detalle:</label>
                            <input type="text" name="aval" id="aval" placeholder="Banco, No. de tarjeta, etc."
                                class="form-control" style="text-transform:capitalize;"
                                onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();"
                                maxlength="60">
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="fec_deposito">Fecha:</label>
                            <input type="text" name="fec_deposito" id="fec_deposito" class="form-control"
                                value="<?php echo date('d-m-Y'); ?>" disabled>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="deposito">Cantidad $ (Número):</label>
                            <input type="text" name="deposito" id="deposito" class="form-control number"
                                onkeypress="return SoloNumeros(event);" maxlength="13" required>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <button type="submit" class="btn btn-success btn-custom"><i class="fas fa-save"></i>
                        Guardar</button>
                    <a href="../gestion_pacientes/registro_pac.php" class="btn btn-danger btn-custom"><i
                            class="fas fa-times"></i> Cancelar</a>
                </div>
            </form>
        </div>
    </div>
    </div>
    <footer class="main-footer">
        <?php include "../../template/footer.php"; ?>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- FastClick -->
    <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
    <script src="../global_pac/js_busc/jquery.dataTables.min.js"></script>
    <script>
    $(document).ready(function() {
        // Initialize DataTable for the table
        $('#table').DataTable({
            "language": {
                "search": "Filtrar en tabla:",
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                "zeroRecords": "No se encontraron registros coincidentes",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            }
        });

        // Initialize Select2 for the search dropdown
        $('#input-search').select2({
            placeholder: "Buscar paciente por nombre",
            allowClear: true,
            minimumInputLength: 2,
            matcher: function(params, data) {
                if (!params.term) return data;
                const term = params.term.toLowerCase();
                const text = $(data.element).text().toLowerCase();
                return text.includes(term) ? data : null;
            }
        });

        // Redirect when an option is selected
        $('#input-search').on('select2:select', function(e) {
            window.location.href = e.target.value;
        });

        // Sync Select2 search with DataTable search
        $('#input-search').on('select2:open', function() {
            $('.select2-search__field').on('input', function() {
                const searchTerm = $(this).val();
                $('#table').DataTable().search(searchTerm).draw();
            });
        });

        // Initialize Select2 for the Médico tratante dropdown
        $('select[name="id_usua"]').select2();

        // Handle Médico tratante selection change
        $('select[name="id_usua"]').on('select2:select', function(e) {
            const value = $(this).val();
            console.log('Médico tratante selected:', value); // Debugging
            mostrar(value);
        });

        // Handle estado_nac and estado_res selection change
        const $municipios = $('#municipios');
        const $municipioManual = $('#municipio_manual');

        // Function to handle estado selection change
        function handleEstadoChange(estadoSelector, municipiosSelector, municipioManualSelector) {
            $(estadoSelector).on('change', function() {
                const idEstado = $(this).val();
                console.log(`Selected estado (${estadoSelector}):`, idEstado);

                municipiosSelector.prop('disabled', true).html(
                    '<option value="" disabled selected>Cargando municipios...</option>');
                municipioManualSelector.hide().prop('required', false);

                if (idEstado === 'OT') {
                    municipiosSelector.hide();
                    municipioManualSelector.show().prop('required', true);
                    municipiosSelector.html(
                            '<option value="" disabled selected>Seleccionar municipio</option>')
                        .select2();
                    municipiosSelector.prop('disabled', true);
                } else if (idEstado) {
                    municipiosSelector.show();
                    $.ajax({
                        url: 'municipios.php',
                        type: 'GET',
                        data: {
                            estado_id: idEstado
                        },
                        dataType: 'json',
                        success: function(datos) {
                            console.log('Response data:', datos);
                            let html =
                                '<option value="" disabled selected>Seleccionar municipio</option>';
                            if (datos && datos.length > 0) {
                                datos.forEach(mun => {
                                    html +=
                                        `<option value="${mun.id_mun}">${mun.nombre_m}</option>`;
                                });
                                municipiosSelector.prop('disabled', false);
                            } else {
                                html =
                                    '<option value="">No hay municipios disponibles</option>';
                            }
                            municipiosSelector.html(html).select2();
                        },
                        error: function(xhr, status, error) {
                            console.error('Fetch error:', error, xhr.status, xhr
                            .statusText);
                            municipiosSelector.html(
                                    '<option value="">Error al cargar municipios</option>')
                                .select2();
                            municipiosSelector.prop('disabled', true);
                        }
                    });
                } else {
                    municipiosSelector.html(
                            '<option value="" disabled selected>Seleccionar municipio</option>')
                        .select2();
                    municipiosSelector.prop('disabled', true);
                }
            });
        }

        // Initialize handlers for both estado_nac and estado_res
        /* handleEstadoChange('#estado_nac', $municipios, $municipioManual); */
        handleEstadoChange('#estado_res', $municipios, $municipioManual);

        // Function to toggle visibility of new doctor section
        function mostrar(value) {
            const contenido = document.getElementById('contenido');
            if (value === 'OTRO') {
                contenido.style.display = 'block';
                console.log('Showing new doctor section');
            } else {
                contenido.style.display = 'none';
                console.log('Hiding new doctor section');
            }
        }

        // Initialize visibility based on default selection
        mostrar($('select[name="id_usua"]').val());

        let enviando = false;

        function checkSubmit() {
            if (!enviando) {
                enviando = true;
                return true;
            } else {
                alert("Guardando Paciente... Por favor espere...");
                return false;
            }
        }

        function SoloLetras(e) {
            const key = e.keyCode || e.which;
            const tecla = String.fromCharCode(key).toLowerCase();
            const letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
            return letras.indexOf(tecla) !== -1;
        }

        function SoloNumeros(e) {
            const key = e.keyCode || e.which;
            const tecla = String.fromCharCode(key);
            const numeros = "0123456789";
            return numeros.indexOf(tecla) !== -1;
        }
    });
    </script>
</body>

</html>