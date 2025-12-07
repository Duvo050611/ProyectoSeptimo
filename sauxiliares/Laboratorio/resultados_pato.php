<?php
session_start();
require_once "../../conexionbd.php";  // SOLO UNA VEZ
$conexion = ConexionBD::getInstancia()->getConexion();

// Crear alias $usuario1 para compatibilidad con código existente
$usuario = $_SESSION['login'];
$usuario1 = $usuario; // Alias para mantener compatibilidad

$resultado = $conexion->query("select paciente.*, dat_ingreso.id_atencion, triage.id_triage
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp
inner join triage on dat_ingreso.id_atencion=triage.id_atencion where id_triage=id_triage
") or die($conexion->error);

if ($usuario['id_rol'] == 10) {
    include "../header_labo.php";
} else if ($usuario['id_rol'] == 4 || $usuario['id_rol'] == 5 || $usuario['id_rol'] == 12 || $usuario['id_rol'] == 13 || $usuario['id_rol'] == 2) {
    include "../header_labo.php";
} else {
    echo "<script>window.location='../../index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#search").keyup(function() {
                _this = this;
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
            padding: 20px;
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

        .container-fluid {
            position: relative;
            z-index: 1;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Botón de regreso mejorado */
        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
            border: 2px solid #ef4444 !important;
            border-radius: 25px !important;
            padding: 10px 25px !important;
            font-weight: 600 !important;
            color: #ffffff !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.2);
            position: relative;
            overflow: hidden;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
            border-color: #dc2626 !important;
        }

        .btn-danger::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: rotate(45deg) translateX(-100%);
            transition: transform 0.6s ease;
        }

        .btn-danger:hover::before {
            transform: rotate(45deg) translateX(100%);
        }

        /* Encabezado principal */
        .thead {
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
            border: 2px solid #40E0FF !important;
            border-radius: 20px !important;
            padding: 30px 40px !important;
            color: #ffffff !important;
            font-size: 1.8rem !important;
            font-weight: 700 !important;
            text-align: center !important;
            margin: 20px 0 40px 0 !important;
            box-shadow: 0 15px 40px rgba(64, 224, 255, 0.2);
            position: relative;
            overflow: hidden;
            letter-spacing: 1px;
        }

        .thead::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(64, 224, 255, 0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 0.8; }
        }

        .thead::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #40E0FF, #00D9FF, #40E0FF);
            animation: gradient-flow 3s ease infinite;
            background-size: 200% 100%;
        }

        @keyframes gradient-flow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Barra de búsqueda premium */
        .form-group {
            position: relative;
            margin-bottom: 30px !important;
        }

        .form-control {
            background: linear-gradient(135deg, rgba(22, 33, 62, 0.95) 0%, rgba(15, 52, 96, 0.95) 100%) !important;
            border: 2px solid #40E0FF !important;
            border-radius: 25px !important;
            color: #ffffff !important;
            padding: 15px 50px 15px 25px !important;
            font-size: 16px !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 8px 25px rgba(64, 224, 255, 0.15);
            height: 60px !important;
        }

        .form-control:focus {
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
            border-color: #00D9FF !important;
            box-shadow: 0 0 0 0.3rem rgba(64, 224, 255, 0.3), 
                        0 12px 35px rgba(64, 224, 255, 0.25) !important;
            transform: translateY(-3px);
            outline: none !important;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6) !important;
            font-style: italic;
        }

        /* Icono de búsqueda */
        .form-group::after {
            content: '\f002';
            font-family: 'FontAwesome';
            position: absolute;
            right: 25px;
            top: 50%;
            transform: translateY(-50%);
            color: #40E0FF;
            font-size: 18px;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .form-control:focus + .form-group::after {
            color: #00D9FF;
            transform: translateY(-50%) scale(1.2);
        }

        /* Contenedor de tabla premium */
        .table-responsive {
            background: linear-gradient(135deg, rgba(22, 33, 62, 0.95) 0%, rgba(15, 52, 96, 0.95) 100%) !important;
            border: 2px solid #40E0FF !important;
            border-radius: 20px !important;
            padding: 25px !important;
            box-shadow: 0 20px 50px rgba(64, 224, 255, 0.15);
            overflow: hidden;
            position: relative;
            backdrop-filter: blur(10px);
        }

        .table-responsive::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #40E0FF, #00D9FF, #40E0FF);
            animation: shimmer 2s ease infinite;
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        /* Tabla con diseño futurista */
        #mytable {
            margin-bottom: 0 !important;
            background: transparent !important;
            border-collapse: separate !important;
            border-spacing: 0 8px !important;
        }

        #mytable thead {
            background: linear-gradient(135deg, rgba(26, 26, 46, 0.9) 0%, rgba(22, 33, 62, 0.9) 100%) !important;
            border-bottom: 3px solid #40E0FF !important;
            border-radius: 15px 15px 0 0 !important;
        }

        #mytable thead th {
            color: #ffffff !important;
            font-weight: 700 !important;
            border: none !important;
            padding: 20px 15px !important;
            font-size: 15px !important;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
            text-align: center !important;
        }

        #mytable thead th::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 3px;
            background: #40E0FF;
            transition: width 0.4s ease;
        }

        #mytable thead th:hover::before {
            width: 70%;
        }

        #mytable tbody tr {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border-radius: 15px !important;
            overflow: hidden;
            margin-bottom: 10px !important;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(64, 224, 255, 0.1);
            position: relative;
        }

        #mytable tbody tr:hover {
            background: rgba(64, 224, 255, 0.08) !important;
            transform: translateY(-5px) scale(1.005);
            box-shadow: 0 10px 30px rgba(64, 224, 255, 0.15),
                        inset 0 0 20px rgba(64, 224, 255, 0.05);
            border-color: rgba(64, 224, 255, 0.3);
        }

        #mytable tbody tr::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(64, 224, 255, 0.08), transparent);
            transition: left 0.6s ease;
        }

        #mytable tbody tr:hover::after {
            left: 100%;
        }

        #mytable tbody td {
            color: #e2e8f0 !important;
            padding: 18px 15px !important;
            vertical-align: middle !important;
            border: none !important;
            border-bottom: 1px solid rgba(64, 224, 255, 0.1) !important;
            font-size: 14px !important;
            text-align: center !important;
        }

        /* Celdas con estado completado */
        #mytable tbody tr.completado {
            background: rgba(16, 185, 129, 0.1);
            border-left: 4px solid #10b981 !important;
        }

        #mytable tbody tr.completado:hover {
            background: rgba(16, 185, 129, 0.15) !important;
        }

        /* Botones de acción */
        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            border: 2px solid #10b981 !important;
            border-radius: 20px !important;
            padding: 10px 20px !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.2);
            position: relative;
            overflow: hidden;
            min-width: 80px;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
            transform: translateY(-3px) scale(1.08);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);
            border-color: #10b981 !important;
        }

        .btn-success:active {
            transform: translateY(-1px) scale(1.04);
        }

        .btn-success i {
            margin-right: 5px;
            font-size: 16px;
            transition: transform 0.3s ease;
        }

        .btn-success:hover i {
            transform: scale(1.3) rotate(5deg);
        }

        /* Botón de editar (rojo) */
        .btn-danger.editar-btn {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
            border: 2px solid #ef4444 !important;
            min-width: 80px;
            padding: 8px 15px !important;
        }

        .btn-danger.editar-btn:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
        }

        /* Botón de eliminar (amarillo) */
        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
            border: 2px solid #f59e0b !important;
            border-radius: 20px !important;
            padding: 8px 15px !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 5px 15px rgba(245, 158, 11, 0.2);
            min-width: 80px;
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%) !important;
            transform: translateY(-3px) scale(1.08);
            box-shadow: 0 10px 25px rgba(245, 158, 11, 0.4);
            border-color: #f59e0b !important;
        }

        /* Footer */
        .main-footer {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            border-top: 2px solid #40E0FF !important;
            color: #ffffff !important;
            box-shadow: 0 -4px 20px rgba(64, 224, 255, 0.2);
            margin-top: 50px !important;
            padding: 30px 0 !important;
            border-radius: 20px 20px 0 0 !important;
        }

        /* Scrollbar personalizado */
        ::-webkit-scrollbar {
            width: 12px;
        }

        ::-webkit-scrollbar-track {
            background: #0a0a0a;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #40E0FF 0%, #0f3460 100%);
            border-radius: 10px;
            border: 2px solid #0a0a0a;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #00D9FF 0%, #40E0FF 100%);
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

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .btn-danger {
            animation: slideInRight 0.5s ease-out;
        }

        .thead {
            animation: fadeInUp 0.6s ease-out 0.2s backwards;
        }

        .form-group {
            animation: fadeInUp 0.6s ease-out 0.3s backwards;
        }

        .table-responsive {
            animation: fadeInUp 0.6s ease-out 0.4s backwards;
        }

        /* Responsive */
        @media screen and (max-width: 1200px) {
            .container-fluid {
                padding: 0 15px;
            }
        }

        @media screen and (max-width: 992px) {
            .thead {
                padding: 25px 20px !important;
                font-size: 1.5rem !important;
            }
            
            .form-control {
                width: 100% !important;
                height: 55px !important;
            }
            
            #mytable thead th {
                padding: 15px 10px !important;
                font-size: 13px !important;
            }
            
            #mytable tbody td {
                padding: 15px 10px !important;
                font-size: 13px !important;
            }
            
            .btn-danger, .btn-success, .btn-warning {
                padding: 8px 12px !important;
                font-size: 12px !important;
                min-width: 70px;
            }
        }

        @media screen and (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .thead {
                padding: 20px 15px !important;
                font-size: 1.3rem !important;
                margin: 15px 0 30px 0 !important;
            }
            
            .table-responsive {
                padding: 15px !important;
                border-radius: 15px !important;
            }
            
            .btn-danger, .btn-success, .btn-warning {
                padding: 6px 10px !important;
                font-size: 11px !important;
                min-width: 60px;
            }
            
            .btn-success i, .btn-danger i, .btn-warning i {
                margin-right: 3px;
                font-size: 12px;
            }
        }

        @media screen and (max-width: 576px) {
            .thead {
                font-size: 1.1rem !important;
                padding: 15px 10px !important;
                letter-spacing: 0.5px;
            }
            
            #mytable thead th {
                padding: 12px 8px !important;
                font-size: 11px !important;
                letter-spacing: 0.5px;
            }
            
            #mytable tbody td {
                padding: 12px 8px !important;
                font-size: 12px !important;
            }
            
            .btn-danger, .btn-success, .btn-warning {
                padding: 5px 8px !important;
                font-size: 10px !important;
                min-width: 55px;
                border-radius: 15px !important;
            }
            
            .btn-success i, .btn-danger i, .btn-warning i {
                font-size: 10px;
                margin-right: 2px;
            }
        }

        /* Efecto de brillo para elementos importantes */
        .highlight {
            position: relative;
            overflow: hidden;
        }

        .highlight::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: rotate(45deg);
            transition: all 0.6s ease;
        }

        .highlight:hover::after {
            left: 100%;
        }

        /* Estilos para las listas de estudios */
        .estudios-container {
            text-align: left;
            padding: 0 10px;
        }

        .estudio-item {
            background: rgba(64, 224, 255, 0.1);
            border-radius: 10px;
            padding: 5px 10px;
            margin: 3px 0;
            border-left: 3px solid #40E0FF;
            transition: all 0.3s ease;
        }

        .estudio-item:hover {
            background: rgba(64, 224, 255, 0.2);
            transform: translateX(5px);
        }

        /* Separador visual */
        .content-separator {
            height: 1px;
            background: linear-gradient(90deg, transparent, #40E0FF, transparent);
            margin: 30px 0;
            opacity: 0.5;
        }

        /* Mensaje cuando no hay datos */
        .no-data {
            text-align: center;
            padding: 40px;
            color: #94a3b8;
            font-size: 1.2rem;
            background: rgba(22, 33, 62, 0.5);
            border-radius: 15px;
            border: 2px dashed #40E0FF;
        }

        .no-data i {
            font-size: 48px;
            color: #40E0FF;
            margin-bottom: 20px;
            display: block;
        }

        /* Corrección para el contenido interno */
        .content {
            position: relative;
            z-index: 1;
        }

        /* Ajustes para los enlaces dentro de las celdas */
        a {
            color: #40E0FF !important;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        a:hover {
            text-decoration: underline;
            color: #00D9FF !important;
        }

        /* Ajuste específico para botones */
        .btn a {
            color: white !important;
        }

        .btn a:hover {
            color: white !important;
            text-decoration: none;
        }

        /* Estilos para lista de estudios dentro de la tabla */
        #mytable tbody td ul {
            margin: 0;
            padding-left: 15px;
            text-align: left;
            list-style-type: none;
        }

        #mytable tbody td ul li {
            position: relative;
            padding: 3px 0;
            color: #e2e8f0 !important;
            font-size: 13px;
        }

        #mytable tbody td ul li::before {
            content: '•';
            color: #40E0FF;
            font-weight: bold;
            position: absolute;
            left: -10px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <?php
    // Usar $usuario1 (que es el alias de $usuario)
    if ($usuario1['id_rol'] == 4) {
        ?>
        <a type="submit" class="btn btn-danger" href="../../template/menu_sauxiliares.php">Regresar</a>
        <?php
    } else if ($usuario1['id_rol'] == 10) {
        ?>
        <a type="submit" class="btn btn-danger" href="../../template/menu_laboratorio.php">Regresar</a>
        <?php
    } else if ($usuario1['id_rol'] == 5) {
        ?>
        <a type="submit" class="btn btn-danger" href="../../template/menu_gerencia.php">Regresar</a>
        <?php
    } else if ($usuario1['id_rol'] == 12 || $usuario1['id_rol'] == 13) {
        ?>
        <a type="submit" class="btn btn-danger" href="../../template/menu_patologia.php">Regresar</a>
        <?php
    } else {
        // No hacer nada
    }
    ?>
    <br><br>
    <div class="thead">
        <center><strong>RESULTADOS DE ESTUDIOS DE PATOLOGÍA</strong></center>
    </div><br>

    <section class="content container-fluid">
        <div class="container box col-11">
            <div class="content">
                <div class="form-group">
                    <input type="text" class="form-control pull-right" id="search" placeholder="Buscar...">
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="mytable">
                        <thead class="thead">
                            <tr>
                                <th>Paciente</th>
                                <th>Fecha de solicitud</th>
                                <th>Hora</th>
                                <th>Solicitante</th>
                                <th>Tipo</th>
                                <th>Realizado</th>
                                <th>Editar</th>
                                <th>Ver</th>
                                <th>Fecha de resultados</th>
                                <th>Atendió solicitud</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // NO incluir conexionbd.php aquí - ya tienes $conexion
                        
                        $usuario = $_SESSION['login'];
                        $id_usua_log = $usuario['id_usua'];
                        
                        $query_log = "SELECT * FROM reg_usuarios where id_usua= $id_usua_log ";
                        $result_log = $conexion->query($query_log);
                        
                        while ($row_log = $result_log->fetch_assoc()) {
                            $pac_log = $row_log['papell'] . ' ' . $row_log['sapell'] . ' ' . $row_log['nombre'];
                        }
                        
                        $query = "SELECT * FROM notificaciones_pato n, reg_usuarios u where n.realizado = 'SI' and n.id_usua = u.id_usua  order by fecha DESC";
                        $result = $conexion->query($query);
                        $no = 1;
                        
                        while ($row = $result->fetch_assoc()) {
                            $id_atencion = $row['id_atencion'];
                            $id_medico = $row['id_usua'];
                            $id_resp = $row['id_usua_resul'];
                            
                            $query_medi = "SELECT * FROM reg_usuarios where Id_usua = $id_medico";
                            $result_medi = $conexion->query($query_medi);
                            $medico = '';
                            
                            while ($row_medi = $result_medi->fetch_assoc()) {
                                $medico = $row_medi['papell'] . ' ' . $row_medi['sapell'] . ' ' . $row_medi['nombre'];
                            }
                            
                            $query_resp = "SELECT * FROM reg_usuarios where Id_usua = $id_resp";
                            $result_resp = $conexion->query($query_resp);
                            $resp = '';
                            
                            while ($row_resp = $result_resp->fetch_assoc()) {
                                $resp = $row_resp['papell'] . ' ' . $row_resp['sapell'] . ' ' . $row_resp['nombre'];
                            }
                            
                            $query_pac = "SELECT * FROM dat_ingreso d, paciente p where d.id_atencion = $id_atencion and d.Id_exp = p.Id_exp";
                            $result_pac = $conexion->query($query_pac);
                            $pac = '';
                            
                            while ($row_pac = $result_pac->fetch_assoc()) {
                                $pac = $row_pac['papell'] . ' ' . $row_pac['sapell'] . ' ' . $row_pac['nom_pac'];
                            }
                            
                            echo '<tr class="completado">'
                                . '<td>' . htmlspecialchars($pac) . '</td>'
                                . '<td>' . htmlspecialchars($row['fecha']) . '</td>'
                                . '<td>' . htmlspecialchars($row['hora']) . '</td>'
                                . '<td>' . htmlspecialchars($medico) . '</td>'
                                . '<td><a style="color: black;"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> ' . htmlspecialchars($row['dispo_p']) . '</a></td>'
                                . '<td>' . htmlspecialchars($row['realizado']) . '</td>'
                                . '<td class="fondo" style="color:white;"><center>'
                                . ' <a href="../Laboratorio/editar_pato.php?id_notp=' . $row['id_notp'] . '" title="Editar resultados" class="btn btn-danger editar-btn"><span class="fa fa-edit" aria-hidden="true"></span></a></center></td>'
                                . '<td class="fondo" style="color:white;"><center>'
                                . ' <a href="../Laboratorio/verpdfpato.php?id_notp=' . $row['id_notp'] . '" title="Ver resultados" class="btn btn-danger"><span class="fa fa-file-pdf-o" aria-hidden="true"></span></a></center></td>'
                                . '<td>' . htmlspecialchars($row['fecha_resul']) . '</td>'
                                . '<td>' . htmlspecialchars($resp) . '</td>'
                                . '</tr>';
                            $no++;
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<footer class="main-footer">
    <?php include("../../template/footer.php"); ?>
</footer>

<script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
</body>
</html>