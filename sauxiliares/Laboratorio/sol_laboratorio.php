<?php
require_once '../../conexionbd.php';
$conexion = ConexionBD::getInstancia()->getConexion();
session_start();

$resultado = $conexion->query("select paciente.*, dat_ingreso.id_atencion, triage.id_triage
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp
inner join triage on dat_ingreso.id_atencion=triage.id_atencion where id_triage=id_triage
") or die($conexion->error);
$usuario= $_SESSION['login'];


if ($usuario['id_rol'] == 10) {
    include "../header_labo.php";

} else if ($usuario['id_rol'] == 4 or 5) {
    include "../header_labo.php";
} else {
    //session_unset();
    // session_destroy();
    echo "<script>window.Location='../../index.php';</script>";

}


?>

<!DOCTYPE html>
<html>

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1″ />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>


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
        --azul-oscuro: #0f172a;
        --azul-medio: #1e293b;
        --azul-claro: #334155;
        --azul-neon: #06b6d4;
        --azul-cian: #22d3ee;
        --gris-oscuro: #1e1e2e;
        --gris-medio: #2d2d44;
        --blanco: #f1f5f9;
        --verde: #10b981;
        --rojo: #ef4444;
    }

    body {
        background: linear-gradient(135deg, var(--azul-oscuro) 0%, var(--gris-oscuro) 100%) !important;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        min-height: 100vh;
        padding: 20px;
        color: var(--blanco) !important;
    }

    /* ESTILOS PARA EL BREADCRUMB Y NAVEGACIÓN QUE ESTÁN BLANCOS */
    .breadcrumb {
        background: linear-gradient(135deg, var(--azul-medio) 0%, var(--azul-oscuro) 100%) !important;
        border: 2px solid var(--azul-neon) !important;
        border-radius: 15px !important;
        padding: 15px 25px !important;
        margin: 10px 0 30px 0 !important;
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.3);
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
        background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
        animation: pulse 3s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }
    
    .breadcrumb .breadcrumb-item {
        color: var(--blanco) !important;
        font-size: 1.2rem;
        font-weight: 600;
        text-shadow: 0 0 10px rgba(6, 182, 212, 0.5);
    }
    
    .breadcrumb .breadcrumb-item.active {
        color: var(--azul-neon) !important;
        font-weight: 700;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        color: var(--azul-cian) !important;
        content: ">";
    }
    
    /* Asegurar que el contenedor del título esté correcto */
    nav[aria-label="breadcrumb"] {
        background: transparent !important;
        padding: 0 !important;
        margin: 0 0 20px 0 !important;
    }
    
    .breadcrumb h4 {
        color: var(--blanco) !important;
        margin: 0 !important;
        padding: 0 !important;
        font-size: 1.3rem !important;
        font-weight: 700 !important;
        letter-spacing: 1px;
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
        background: linear-gradient(135deg, var(--azul-medio) 0%, var(--azul-oscuro) 100%) !important;
        border: 2px solid var(--rojo) !important;
        border-radius: 25px !important;
        padding: 10px 25px !important;
        font-weight: 600 !important;
        color: var(--blanco) !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease !important;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        position: relative;
        overflow: hidden;
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, var(--azul-oscuro) 0%, var(--azul-medio) 100%) !important;
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.5);
        border-color: var(--rojo) !important;
    }

    .btn-danger::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(239, 68, 68, 0.2), transparent);
        transform: rotate(45deg) translateX(-100%);
        transition: transform 0.6s ease;
    }

    .btn-danger:hover::before {
        transform: rotate(45deg) translateX(100%);
    }

    /* Encabezado principal */
    .thead {
        background: linear-gradient(135deg, var(--azul-oscuro) 0%, var(--gris-oscuro) 100%) !important;
        border: 2px solid var(--azul-neon) !important;
        border-radius: 20px !important;
        padding: 30px 40px !important;
        color: var(--blanco) !important;
        font-size: 1.8rem !important;
        font-weight: 700 !important;
        text-align: center !important;
        margin: 20px 0 40px 0 !important;
        box-shadow: 0 15px 40px rgba(6, 182, 212, 0.4);
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
        background: radial-gradient(circle, rgba(6, 182, 212, 0.15) 0%, transparent 70%);
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
        background: linear-gradient(90deg, transparent, var(--azul-neon), transparent);
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
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(30, 41, 59, 0.95) 100%) !important;
        border: 2px solid var(--azul-claro) !important;
        border-radius: 25px !important;
        color: var(--blanco) !important;
        padding: 15px 50px 15px 25px !important;
        font-size: 16px !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
        height: 60px !important;
    }

    .form-control:focus {
        background: linear-gradient(135deg, var(--azul-oscuro) 0%, var(--azul-medio) 100%) !important;
        border-color: var(--azul-neon) !important;
        box-shadow: 0 0 0 0.3rem rgba(6, 182, 212, 0.3), 
                    0 12px 35px rgba(6, 182, 212, 0.3) !important;
        transform: translateY(-3px);
        outline: none !important;
    }

    .form-control::placeholder {
        color: rgba(241, 245, 249, 0.5) !important;
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
        color: var(--azul-neon);
        font-size: 18px;
        pointer-events: none;
        transition: all 0.3s ease;
    }

    .form-control:focus + .form-group::after {
        color: var(--azul-cian);
        transform: translateY(-50%) scale(1.2);
    }

    /* Contenedor de tabla premium */
    .table-responsive {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(30, 41, 59, 0.95) 100%) !important;
        border: 2px solid var(--azul-neon) !important;
        border-radius: 20px !important;
        padding: 25px !important;
        box-shadow: 0 20px 50px rgba(6, 182, 212, 0.3);
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
        background: linear-gradient(90deg, transparent, var(--azul-neon), transparent);
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
        background: linear-gradient(135deg, var(--azul-oscuro) 0%, var(--gris-oscuro) 100%) !important;
        border-bottom: 3px solid var(--azul-neon) !important;
        border-radius: 15px 15px 0 0 !important;
    }

    #mytable thead th {
        color: var(--blanco) !important;
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
        background: var(--azul-neon);
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
        background: rgba(30, 41, 59, 0.7);
        border: 1px solid rgba(6, 182, 212, 0.2);
        position: relative;
    }

    #mytable tbody tr:hover {
        background: rgba(30, 58, 138, 0.8) !important;
        transform: translateY(-5px) scale(1.005);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5),
                    inset 0 0 20px rgba(6, 182, 212, 0.2);
        border-color: rgba(6, 182, 212, 0.4);
    }

    #mytable tbody tr::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.1), transparent);
        transition: left 0.6s ease;
    }

    #mytable tbody tr:hover::after {
        left: 100%;
    }

    #mytable tbody td {
        color: var(--blanco) !important;
        padding: 18px 15px !important;
        vertical-align: middle !important;
        border: none !important;
        border-bottom: 1px solid rgba(6, 182, 212, 0.2) !important;
        font-size: 14px !important;
        text-align: center !important;
    }

    /* Celdas con estado pendiente */
    .fondosan {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 41, 59, 0.8) 100%) !important;
        color: var(--blanco) !important;
        position: relative;
        border-left: 4px solid var(--azul-neon) !important;
        border-right: 4px solid transparent !important;
        border: 1px solid rgba(6, 182, 212, 0.3) !important;
        border-radius: 8px !important;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .fondosan ul {
        margin: 0;
        padding-left: 20px;
        text-align: left;
        list-style-type: none;
    }

    .fondosan ul li {
        position: relative;
        padding: 3px 0;
        color: var(--blanco) !important;
    }

    .fondosan ul li::before {
        content: '•';
        color: var(--azul-neon);
        font-weight: bold;
        font-size: 18px;
        position: absolute;
        left: -15px;
        top: 50%;
        transform: translateY(-50%);
    }

    .fondosan::before {
        content: 'PENDIENTE';
        position: absolute;
        top: -10px;
        right: -10px;
        background: linear-gradient(135deg, var(--azul-medio) 0%, var(--azul-neon) 100%);
        color: white;
        font-size: 10px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 10px;
        transform: rotate(15deg);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.5);
        z-index: 2;
    }

    /* Efecto hover para celdas fondosan */
    #mytable tbody tr:hover .fondosan {
        background: linear-gradient(135deg, rgba(30, 58, 138, 0.95) 0%, rgba(6, 182, 212, 0.3) 100%) !important;
        color: var(--blanco) !important;
        border-color: rgba(6, 182, 212, 0.6) !important;
        box-shadow: inset 0 2px 8px rgba(6, 182, 212, 0.2);
    }

    /* Botones de acción */
    .btn-success {
        background: linear-gradient(135deg, var(--azul-medio) 0%, var(--azul-oscuro) 100%) !important;
        border: 2px solid var(--verde) !important;
        border-radius: 20px !important;
        padding: 10px 20px !important;
        color: var(--blanco) !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
        position: relative;
        overflow: hidden;
        min-width: 120px;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, var(--azul-oscuro) 0%, var(--azul-medio) 100%) !important;
        transform: translateY(-3px) scale(1.08);
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.5);
        border-color: var(--verde) !important;
    }

    .btn-success:active {
        transform: translateY(-1px) scale(1.04);
    }

    .btn-success i {
        margin-right: 8px;
        font-size: 16px;
        transition: transform 0.3s ease;
        color: var(--verde);
    }

    .btn-success:hover i {
        transform: scale(1.3) rotate(5deg);
        color: var(--blanco);
    }

    /* Footer */
    .main-footer {
        background: linear-gradient(135deg, var(--azul-oscuro) 0%, var(--gris-oscuro) 100%) !important;
        border-top: 2px solid var(--azul-neon) !important;
        color: var(--blanco) !important;
        box-shadow: 0 -4px 20px rgba(6, 182, 212, 0.3);
        margin-top: 50px !important;
        padding: 30px 0 !important;
        border-radius: 20px 20px 0 0 !important;
    }

    /* Scrollbar personalizado */
    ::-webkit-scrollbar {
        width: 12px;
    }

    ::-webkit-scrollbar-track {
        background: var(--azul-oscuro);
        border-radius: 10px;
        box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.5);
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, var(--azul-claro) 0%, var(--azul-medio) 100%);
        border-radius: 10px;
        border: 2px solid var(--azul-oscuro);
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, var(--azul-neon) 0%, var(--azul-cian) 100%);
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
    }

    @media screen and (max-width: 768px) {
        body {
            padding: 10px;
        }
        
        .breadcrumb {
            padding: 12px 15px !important;
            margin: 5px 0 20px 0 !important;
        }
        
        .breadcrumb .breadcrumb-item {
            font-size: 1rem !important;
        }
        
        .breadcrumb h4 {
            font-size: 1.1rem !important;
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
        
        .btn-danger, .btn-success {
            padding: 8px 15px !important;
            font-size: 13px !important;
            min-width: 100px;
        }
        
        .btn-success i {
            margin-right: 5px;
            font-size: 14px;
        }
    }

    @media screen and (max-width: 576px) {
        .breadcrumb {
            padding: 10px 12px !important;
            border-radius: 12px !important;
        }
        
        .breadcrumb .breadcrumb-item {
            font-size: 0.9rem !important;
        }
        
        .breadcrumb h4 {
            font-size: 0.95rem !important;
        }
        
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
        
        .fondosan::before {
            font-size: 8px;
            padding: 2px 5px;
            top: -8px;
            right: -8px;
        }
        
        .btn-danger, .btn-success {
            padding: 6px 12px !important;
            font-size: 12px !important;
            min-width: 90px;
            border-radius: 15px !important;
        }
        
        .btn-success i {
            font-size: 12px;
            margin-right: 4px;
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
        background: linear-gradient(45deg, transparent, rgba(6, 182, 212, 0.1), transparent);
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
        background: rgba(30, 41, 59, 0.7);
        border-radius: 10px;
        padding: 5px 10px;
        margin: 3px 0;
        border-left: 3px solid var(--azul-neon);
        transition: all 0.3s ease;
        color: var(--blanco);
    }

    .estudio-item:hover {
        background: rgba(30, 58, 138, 0.9);
        transform: translateX(5px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }

    /* Separador visual */
    .content-separator {
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--azul-neon), transparent);
        margin: 30px 0;
        opacity: 0.5;
    }

    /* Mensaje cuando no hay datos */
    .no-data {
        text-align: center;
        padding: 40px;
        color: #94a3b8;
        font-size: 1.2rem;
        background: rgba(15, 23, 42, 0.7);
        border-radius: 15px;
        border: 2px dashed var(--azul-neon);
    }

    .no-data i {
        font-size: 48px;
        color: var(--azul-neon);
        margin-bottom: 20px;
        display: block;
    }

    /* Estilo para los iconos dentro de las celdas */
    .fa {
        transition: all 0.3s ease;
    }

    /* Mejor contraste para textos */
    h4, strong, th, td {
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
    }

    /* Gradiente de fondo adicional para mayor profundidad */
    body::after {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at 50% 0%, rgba(30, 58, 138, 0.05) 0%, transparent 50%),
                   radial-gradient(circle at 100% 100%, rgba(6, 182, 212, 0.05) 0%, transparent 50%);
        pointer-events: none;
        z-index: 0;
    }
    
    /* Asegurar que todos los textos sean visibles */
    .container-fluid, .content-wrapper, .content {
        color: var(--blanco) !important;
    }
    
    /* Override de estilos blancos de Bootstrap */
    .bg-white {
        background-color: var(--azul-oscuro) !important;
        color: var(--blanco) !important;
    }
    
    .text-dark {
        color: var(--blanco) !important;
    }
</style>

</head>

<body>

<div class="container-fluid">

    <?php
    if ($usuario['id_rol'] == 10) {
        ?>
        <a type="submit" class="btn btn-danger" href="../../template/menu_laboratorio.php">Regresar</a>
        <?php
    } else if ($usuario['id_rol'] == 5) {
        ?>
        <a type="submit" class="btn btn-danger" href="../../template/menu_gerencia.php">Regresar</a>
        <?php
    }
    ?>
    <br>
    <br>
    <div class="thead">
        <tr><strong>
                <center>ESTUDIOS DE LABORATORIO PENDIENTES</center>
            </strong>
    </div><br>

</div>

<section class="content">
    <section class="content container-fluid">
        <div class="content box">
            <!-- CONTENIDOO -->

            <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:25%" id="search"
                       placeholder="Buscar...">
            </div>
            <br>

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="mytable">

                    <thead class="thead">
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
                    $query = "SELECT n.*, u.papell AS solicitante_papell, u.sapell AS solicitante_sapell 
                          FROM notificaciones_labo n 
                          JOIN reg_usuarios u ON n.id_usua = u.id_usua 
                          WHERE n.realizado = 'NO' AND n.activo = 'SI' 
                          ORDER BY n.fecha_ord DESC";
                    $stmt = $conexion->prepare($query);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $no = 1;

                    while ($row = $result->fetch_assoc()) {
                        $habi = $row['habitacion'];
                        $id_atencion = $row['id_atencion'];
                        $not_id = $row['not_id'];

                        // Skip invalid rows
                        if (empty($id_atencion) || empty($not_id)) {
                            error_log("Invalid data: id_atencion=$id_atencion, not_id=$not_id");
                            continue;
                        }

                        if ($habi != 0) {
                            // Inpatient (dat_ingreso)
                            $query_pac = "SELECT p.papell, p.sapell, p.nom_pac, d.id_usua 
                                      FROM dat_ingreso d 
                                      JOIN paciente p ON d.Id_exp = p.Id_exp 
                                      WHERE d.id_atencion = ?";
                            $stmt_pac = $conexion->prepare($query_pac);
                            $stmt_pac->bind_param("i", $id_atencion);
                            $stmt_pac->execute();
                            $result_pac = $stmt_pac->get_result();

                            $pac = '';
                            $tratante = null;
                            if ($row_pac = $result_pac->fetch_assoc()) {
                                $pac = $row_pac['papell'] . ' ' . $row_pac['sapell'] . ' ' . $row_pac['nom_pac'];
                                $tratante = $row_pac['id_usua'];
                            }
                            $stmt_pac->close();

                            $prefijo = '';
                            $nom_tratante = '';
                            if ($tratante) {
                                $sql_reg_usrt = "SELECT pre, papell FROM reg_usuarios WHERE id_usua = ?";
                                $stmt_reg_usrt = $conexion->prepare($sql_reg_usrt);
                                $stmt_reg_usrt->bind_param("i", $tratante);
                                $stmt_reg_usrt->execute();
                                $result_reg_usrt = $stmt_reg_usrt->get_result();
                                if ($row_reg_usrt = $result_reg_usrt->fetch_assoc()) {
                                    $prefijo = $row_reg_usrt['pre'];
                                    $nom_tratante = $row_reg_usrt['papell'];
                                }
                                $stmt_reg_usrt->close();
                            }

                            echo '<tr>'
                                    . '<td class="fondosan">' . htmlspecialchars($row['habitacion']) . '</td>'
                                    . '<td class="fondosan">' . htmlspecialchars($pac) . '</td>'
                                    . '<td class="fondosan">' . htmlspecialchars($prefijo . '. ' . $nom_tratante) . '</td>'
                                    . '<td class="fondosan">' . date_format(date_create($row['fecha_ord']), 'd/m/Y H:i a') . '</td>'
                                    . '<td class="fondosan">' . htmlspecialchars($row['solicitante_papell'] . ' ' . $row['solicitante_sapell']) . '</td>'
                                    . '<td class="fondosan">';

                            // Display sol_estudios as a plain bulleted list
                            $estudios = preg_split('/[,;]/', $row['sol_estudios'], -1, PREG_SPLIT_NO_EMPTY);
                            if (!empty($estudios)) {
                                echo '<ul>';
                                foreach ($estudios as $estudio) {
                                    $estudio = trim($estudio);
                                    if ($estudio) {
                                        echo '<li>' . htmlspecialchars($estudio) . '</li>';
                                    }
                                }
                                echo '</ul>';
                            } else {
                                echo htmlspecialchars($row['sol_estudios']);
                            }

                            echo '</td>'
                                    . '<td class="fondosan"><center>'
                                    . '<a href="pdf_solicitud_estu.php?not_id=' . (int)$not_id . '&id_atencion=' . (int)$id_atencion . '" target="_blank">'
                                    . '<button type="button" class="btn btn-success"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>'
                                    . '</a></center></td>'
                                    . '<td class="fondosan"><center>'
                                    . '<a href="subir_resultado.php?not_id=' . (int)$not_id . '" title="Subir resultado" class="btn btn-success"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>'
                                    . '</center></td>'
                                    . '</tr>';
                            $no++;
                        } else {
                            // Outpatient (receta_ambulatoria)
                            $query_rec = "SELECT papell_rec, sapell_rec, nombre_rec FROM receta_ambulatoria WHERE id_rec_amb = ?";
                            $stmt_rec = $conexion->prepare($query_rec);
                            $stmt_rec->bind_param("i", $id_atencion);
                            $stmt_rec->execute();
                            $result_rec = $stmt_rec->get_result();

                            $pac = '';
                            $habitacion = "C.EXT";
                            if ($row_rec = $result_rec->fetch_assoc()) {
                                $pac = $row_rec['papell_rec'] . ' ' . $row_rec['sapell_rec'] . ' ' . $row_rec['nombre_rec'];
                            }
                            $stmt_rec->close();

                            echo '<tr>'
                                    . '<td class="fondosan">' . htmlspecialchars($habitacion) . '</td>'
                                    . '<td class="fondosan">' . htmlspecialchars($pac) . '</td>'
                                    . '<td class="fondosan">N/A</td>'
                                    . '<td class="fondosan">' . date_format(date_create($row['fecha_ord']), 'd/m/Y H:i a') . '</td>'
                                    . '<td class="fondosan">' . htmlspecialchars($row['solicitante_papell'] . ' ' . $row['solicitante_sapell']) . '</td>'
                                    . '<td class="fondosan">';

                            // Display sol_estudios as a plain bulleted list
                            $estudios = preg_split('/[,;]/', $row['sol_estudios'], -1, PREG_SPLIT_NO_EMPTY);
                            if (!empty($estudios)) {
                                echo '<ul>';
                                foreach ($estudios as $estudio) {
                                    $estudio = trim($estudio);
                                    if ($estudio) {
                                        echo '<li>' . htmlspecialchars($estudio) . '</li>';
                                    }
                                }
                                echo '</ul>';
                            } else {
                                echo htmlspecialchars($row['sol_estudios']);
                            }

                            echo '</td>'
                                    . '<td class="fondosan"><center>'
                                    . '<a href="pdf_solicitud_estu.php?not_id=' . (int)$not_id . '&id_atencion=' . (int)$id_atencion . '" target="_blank">'
                                    . '<button type="button" class="btn btn-success"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>'
                                    . '</a></center></td>'
                                    . '<td class="fondosan"><center>'
                                    . '<a href="subir_resultado.php?not_id=' . (int)$not_id . '" title="Subir resultado" class="btn btn-success"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>'
                                    . '</center></td>'
                                    . '</tr>';
                            $no++;
                        }
                    }
                    $stmt->close();
                    ?>
                    </tbody>
                </table>
            </div>

        </div>
    </section><!-- /.content -->
</section>
</div><!-- /.content-wrapper -->

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