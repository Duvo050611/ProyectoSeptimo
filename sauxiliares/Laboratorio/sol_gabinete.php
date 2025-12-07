<?php
session_start();
include "../../conexionbd.php";

$conexion = ConexionBD::getInstancia()->getConexion();

$resultado = $conexion->query("select paciente.*, dat_ingreso.id_atencion, triage.id_triage
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp
inner join triage on dat_ingreso.id_atencion=triage.id_atencion where id_triage=id_triage
") or die($conexion->error);

$usuario = $_SESSION['login'];


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

    <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″/>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
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
        margin: 0;
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
            radial-gradient(circle at 20% 50%, rgba(100, 220, 255, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(100, 220, 255, 0.03) 0%, transparent 50%),
            radial-gradient(circle at 40% 80%, rgba(100, 220, 255, 0.04) 0%, transparent 50%);
        pointer-events: none;
        z-index: 0;
        animation: particles-float 20s ease-in-out infinite;
    }

    @keyframes particles-float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        25% { transform: translateY(-20px) rotate(1deg); }
        50% { transform: translateY(0) rotate(0deg); }
        75% { transform: translateY(20px) rotate(-1deg); }
    }

    .container-fluid {
        position: relative;
        z-index: 1;
        max-width: 1400px;
        margin: 0 auto;
        background: rgba(22, 33, 62, 0.7);
        backdrop-filter: blur(10px);
        border-radius: 25px;
        padding: 25px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3),
                    inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }

    /* Botón de regreso premium */
    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        border: 2px solid transparent !important;
        border-radius: 30px !important;
        padding: 12px 30px !important;
        font-weight: 700 !important;
        color: #ffffff !important;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3),
                    inset 0 1px 1px rgba(255, 255, 255, 0.2);
        position: relative;
        overflow: hidden;
        font-size: 14px;
    }

    .btn-danger::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.6s ease;
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
        transform: translateY(-4px) scale(1.05);
        box-shadow: 0 12px 30px rgba(239, 68, 68, 0.5),
                    0 0 20px rgba(239, 68, 68, 0.3),
                    inset 0 1px 1px rgba(255, 255, 255, 0.3);
        border-color: #f87171 !important;
    }

    .btn-danger:hover::before {
        left: 100%;
    }

    .btn-danger:active {
        transform: translateY(-1px) scale(1.02);
        transition: all 0.1s ease;
    }

    /* Encabezado principal con diseño gabinete */
    .thead {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.95) 0%, rgba(15, 52, 96, 0.95) 100%) !important;
        border: 2px solid #7dd3fc !important;
        border-radius: 25px !important;
        padding: 35px 50px !important;
        color: #ffffff !important;
        font-size: 2rem !important;
        font-weight: 800 !important;
        text-align: center !important;
        margin: 25px 0 45px 0 !important;
        box-shadow: 0 20px 50px rgba(125, 211, 252, 0.25),
                    0 0 40px rgba(125, 211, 252, 0.15);
        position: relative;
        overflow: hidden;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3),
                     0 0 20px rgba(125, 211, 252, 0.5);
    }

    .thead::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, 
            transparent 0%, 
            rgba(125, 211, 252, 0.1) 25%, 
            transparent 50%, 
            rgba(125, 211, 252, 0.1) 75%, 
            transparent 100%);
        background-size: 200% 200%;
        animation: scan-effect 4s linear infinite;
    }

    @keyframes scan-effect {
        0% { background-position: 0% 0%; }
        100% { background-position: 200% 200%; }
    }

    .thead::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80%;
        height: 4px;
        background: linear-gradient(90deg, 
            transparent, 
            #7dd3fc, 
            #38bdf8, 
            #0ea5e9, 
            #38bdf8, 
            #7dd3fc, 
            transparent);
        border-radius: 2px;
        animation: pulse-beam 2s ease-in-out infinite;
    }

    @keyframes pulse-beam {
        0%, 100% { opacity: 0.8; width: 80%; }
        50% { opacity: 1; width: 90%; }
    }

    /* Barra de búsqueda con estilo médico */
    .form-group {
        position: relative;
        margin-bottom: 40px !important;
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%);
        border-radius: 30px;
        padding: 5px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2),
                    inset 0 1px 1px rgba(255, 255, 255, 0.1);
    }

    .form-control {
        background: linear-gradient(135deg, rgba(26, 26, 46, 0.95) 0%, rgba(22, 33, 62, 0.95) 100%) !important;
        border: 2px solid #7dd3fc !important;
        border-radius: 25px !important;
        color: #ffffff !important;
        padding: 16px 60px 16px 30px !important;
        font-size: 16px !important;
        transition: all 0.4s ease !important;
        box-shadow: 0 5px 20px rgba(125, 211, 252, 0.2),
                    inset 0 0 15px rgba(125, 211, 252, 0.1);
        height: 65px !important;
        font-weight: 500;
    }

    .form-control:focus {
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.95) 0%, rgba(22, 33, 62, 0.95) 100%) !important;
        border-color: #38bdf8 !important;
        box-shadow: 0 0 0 0.4rem rgba(125, 211, 252, 0.25),
                    0 15px 40px rgba(125, 211, 252, 0.3) !important;
        transform: translateY(-2px);
        outline: none !important;
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.7) !important;
        font-weight: 400;
        letter-spacing: 0.5px;
    }

    /* Icono de búsqueda con efecto */
    .form-group::after {
        content: '\f002';
        font-family: 'FontAwesome';
        position: absolute;
        right: 30px;
        top: 50%;
        transform: translateY(-50%);
        color: #7dd3fc;
        font-size: 22px;
        pointer-events: none;
        transition: all 0.4s ease;
        text-shadow: 0 0 10px rgba(125, 211, 252, 0.5);
        z-index: 2;
    }

    .form-control:focus ~ .form-group::after {
        color: #38bdf8;
        transform: translateY(-50%) scale(1.3);
        text-shadow: 0 0 20px rgba(56, 189, 248, 0.8);
        animation: search-pulse 1s ease infinite;
    }

    @keyframes search-pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    /* Contenedor de tabla estilo gabinete */
    .table-responsive {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.98) 0%, rgba(15, 52, 96, 0.98) 100%) !important;
        border: 2px solid #7dd3fc !important;
        border-radius: 25px !important;
        padding: 30px !important;
        box-shadow: 0 25px 60px rgba(125, 211, 252, 0.2),
                    inset 0 0 30px rgba(125, 211, 252, 0.1);
        overflow: hidden;
        position: relative;
        backdrop-filter: blur(15px);
    }

    .table-responsive::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, 
            #7dd3fc, #38bdf8, #0ea5e9, #0284c7, 
            #0ea5e9, #38bdf8, #7dd3fc);
        background-size: 200% 100%;
        animation: gradient-slide 3s linear infinite;
    }

    @keyframes gradient-slide {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Tabla con diseño de rayos X */
    #mytable {
        margin-bottom: 0 !important;
        background: transparent !important;
        border-collapse: separate !important;
        border-spacing: 0 12px !important;
    }

    #mytable thead {
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.95) 0%, rgba(22, 33, 62, 0.95) 100%) !important;
        border-bottom: 4px solid #7dd3fc !important;
        border-radius: 20px 20px 0 0 !important;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    #mytable thead th {
        color: #ffffff !important;
        font-weight: 800 !important;
        border: none !important;
        padding: 22px 18px !important;
        font-size: 16px !important;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        position: relative;
        overflow: hidden;
        text-align: center !important;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
    }

    #mytable thead th::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 10%;
        width: 80%;
        height: 3px;
        background: linear-gradient(90deg, transparent, #7dd3fc, transparent);
        transform: scaleX(0);
        transform-origin: center;
        transition: transform 0.5s ease;
    }

    #mytable thead th:hover::after {
        transform: scaleX(1);
    }

    #mytable tbody tr {
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border-radius: 20px !important;
        overflow: hidden;
        margin-bottom: 15px !important;
        background: linear-gradient(135deg, 
            rgba(255, 255, 255, 0.07) 0%, 
            rgba(255, 255, 255, 0.04) 100%);
        border: 1px solid rgba(125, 211, 252, 0.15);
        position: relative;
        backdrop-filter: blur(5px);
    }

    #mytable tbody tr:hover {
        background: linear-gradient(135deg, 
            rgba(125, 211, 252, 0.15) 0%, 
            rgba(56, 189, 248, 0.12) 100%) !important;
        transform: translateY(-8px) scale(1.01);
        box-shadow: 0 15px 40px rgba(125, 211, 252, 0.25),
                    0 0 30px rgba(125, 211, 252, 0.15),
                    inset 0 0 25px rgba(125, 211, 252, 0.08);
        border-color: rgba(125, 211, 252, 0.4);
    }

    #mytable tbody tr::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, 
            transparent, 
            rgba(125, 211, 252, 0.1), 
            rgba(125, 211, 252, 0.2), 
            rgba(125, 211, 252, 0.1), 
            transparent);
        transition: left 0.7s ease;
    }

    #mytable tbody tr:hover::before {
        left: 100%;
    }

    #mytable tbody td {
        color: #f1f5f9 !important;
        padding: 20px 18px !important;
        vertical-align: middle !important;
        border: none !important;
        border-bottom: 1px solid rgba(125, 211, 252, 0.15) !important;
        font-size: 15px !important;
        text-align: center !important;
        font-weight: 500;
    }

    /* Celdas con estado pendiente - tema gabinete */
    .fondosan {
        background: linear-gradient(135deg, 
            rgba(239, 68, 68, 0.18) 0%, 
            rgba(220, 38, 38, 0.15) 100%) !important;
        color: #ffffff !important;
        position: relative;
        border-left: 5px solid #f59e0b !important;
        border-right: 5px solid transparent !important;
    }

    .fondosan ul {
        margin: 0;
        padding-left: 25px;
        text-align: left;
        list-style-type: none;
    }

    .fondosan ul li {
        position: relative;
        padding: 5px 0;
        color: #ffffff !important;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .fondosan ul li:hover {
        color: #fef3c7 !important;
        transform: translateX(5px);
    }

    .fondosan ul li::before {
        content: '🩻';
        position: absolute;
        left: -25px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 14px;
        color: #7dd3fc;
        filter: drop-shadow(0 0 3px rgba(125, 211, 252, 0.5));
    }

    .fondosan::before {
        content: 'GABINETE';
        position: absolute;
        top: -12px;
        right: -12px;
        background: linear-gradient(135deg, #7dd3fc 0%, #0ea5e9 100%);
        color: #1e293b;
        font-size: 11px;
        font-weight: 900;
        padding: 5px 12px;
        border-radius: 15px;
        transform: rotate(15deg);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        z-index: 2;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .fondosan::after {
        content: 'PENDIENTE';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%) rotate(-5deg);
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        font-size: 10px;
        font-weight: 900;
        padding: 3px 10px;
        border-radius: 12px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);
        z-index: 2;
        opacity: 0.9;
    }

    /* Botones de acción - estilo médico */
    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        border: 2px solid transparent !important;
        border-radius: 25px !important;
        padding: 12px 25px !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3),
                    inset 0 1px 1px rgba(255, 255, 255, 0.2);
        position: relative;
        overflow: hidden;
        min-width: 140px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .btn-success::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.6s ease;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
        transform: translateY(-4px) scale(1.08);
        box-shadow: 0 15px 30px rgba(16, 185, 129, 0.4),
                    0 0 25px rgba(16, 185, 129, 0.3),
                    inset 0 1px 1px rgba(255, 255, 255, 0.3);
        border-color: #34d399 !important;
    }

    .btn-success:hover::before {
        left: 100%;
    }

    .btn-success:active {
        transform: translateY(-1px) scale(1.04);
        transition: all 0.1s ease;
    }

    .btn-success i {
        margin-right: 10px;
        font-size: 18px;
        transition: all 0.4s ease;
        text-shadow: 0 0 5px rgba(255, 255, 255, 0.3);
    }

    .btn-success:hover i {
        transform: scale(1.4) rotate(10deg);
        text-shadow: 0 0 10px rgba(255, 255, 255, 0.6);
    }

    /* Footer */
    .main-footer {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-top: 3px solid #7dd3fc !important;
        color: #ffffff !important;
        box-shadow: 0 -10px 30px rgba(125, 211, 252, 0.25);
        margin-top: 60px !important;
        padding: 35px 0 !important;
        border-radius: 25px 25px 0 0 !important;
        position: relative;
        overflow: hidden;
    }

    .main-footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, 
            #7dd3fc, #38bdf8, #0ea5e9, 
            #38bdf8, #7dd3fc);
        animation: footer-glow 3s ease-in-out infinite;
    }

    @keyframes footer-glow {
        0%, 100% { opacity: 0.7; }
        50% { opacity: 1; }
    }

    /* Scrollbar personalizado */
    ::-webkit-scrollbar {
        width: 14px;
    }

    ::-webkit-scrollbar-track {
        background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        border-radius: 10px;
        border: 1px solid rgba(125, 211, 252, 0.2);
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #7dd3fc 0%, #0ea5e9 50%, #0284c7 100%);
        border-radius: 10px;
        border: 3px solid #0f172a;
        box-shadow: inset 0 0 6px rgba(255, 255, 255, 0.1);
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #bae6fd 0%, #7dd3fc 50%, #38bdf8 100%);
        box-shadow: inset 0 0 10px rgba(255, 255, 255, 0.2);
    }

    /* Animaciones de entrada secuencial */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(40px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes pulseIn {
        0% {
            opacity: 0;
            transform: scale(0.9);
        }
        70% {
            opacity: 1;
            transform: scale(1.05);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    .btn-danger {
        animation: slideInRight 0.6s ease-out 0.1s backwards;
    }

    .thead {
        animation: pulseIn 0.8s ease-out 0.2s backwards;
    }

    .form-group {
        animation: fadeInUp 0.7s ease-out 0.3s backwards;
    }

    .table-responsive {
        animation: fadeInUp 0.8s ease-out 0.4s backwards;
    }

    /* Efecto de carga para las filas */
    #mytable tbody tr {
        animation: rowAppear 0.5s ease-out backwards;
        animation-fill-mode: both;
    }

    @keyframes rowAppear {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Aplicar delays escalonados para las filas */
    #mytable tbody tr:nth-child(1) { animation-delay: 0.5s; }
    #mytable tbody tr:nth-child(2) { animation-delay: 0.6s; }
    #mytable tbody tr:nth-child(3) { animation-delay: 0.7s; }
    #mytable tbody tr:nth-child(4) { animation-delay: 0.8s; }
    #mytable tbody tr:nth-child(5) { animation-delay: 0.9s; }
    #mytable tbody tr:nth-child(6) { animation-delay: 1.0s; }
    #mytable tbody tr:nth-child(7) { animation-delay: 1.1s; }
    #mytable tbody tr:nth-child(8) { animation-delay: 1.2s; }
    #mytable tbody tr:nth-child(9) { animation-delay: 1.3s; }
    #mytable tbody tr:nth-child(10) { animation-delay: 1.4s; }

    /* Responsive */
    @media screen and (max-width: 1200px) {
        .container-fluid {
            padding: 20px;
            border-radius: 20px;
        }
    }

    @media screen and (max-width: 992px) {
        .thead {
            padding: 30px 25px !important;
            font-size: 1.8rem !important;
            border-radius: 20px !important;
        }
        
        .form-control {
            height: 60px !important;
            font-size: 15px !important;
            padding: 14px 55px 14px 25px !important;
        }
        
        #mytable thead th {
            padding: 18px 12px !important;
            font-size: 14px !important;
            letter-spacing: 1px;
        }
        
        #mytable tbody td {
            padding: 18px 12px !important;
            font-size: 14px !important;
        }
        
        .btn-success, .btn-danger {
            padding: 10px 20px !important;
            min-width: 120px;
            font-size: 13px;
        }
    }

    @media screen and (max-width: 768px) {
        body {
            padding: 15px;
        }
        
        .thead {
            padding: 25px 20px !important;
            font-size: 1.5rem !important;
            margin: 20px 0 35px 0 !important;
            letter-spacing: 1px;
        }
        
        .container-fluid {
            padding: 15px;
            border-radius: 18px;
        }
        
        .table-responsive {
            padding: 20px !important;
            border-radius: 20px !important;
        }
        
        #mytable {
            border-spacing: 0 8px !important;
        }
        
        .btn-danger, .btn-success {
            padding: 9px 18px !important;
            min-width: 110px;
            font-size: 12px;
            border-radius: 20px !important;
        }
        
        .btn-success i {
            margin-right: 8px;
            font-size: 16px;
        }
        
        .fondosan::before {
            font-size: 10px;
            padding: 4px 9px;
            top: -10px;
            right: -10px;
        }
        
        .fondosan::after {
            font-size: 9px;
            padding: 2px 8px;
            bottom: -8px;
        }
    }

    @media screen and (max-width: 576px) {
        .thead {
            font-size: 1.3rem !important;
            padding: 20px 15px !important;
            letter-spacing: 0.8px;
            border-radius: 18px !important;
        }
        
        #mytable thead th {
            padding: 15px 8px !important;
            font-size: 12px !important;
            letter-spacing: 0.8px;
        }
        
        #mytable tbody td {
            padding: 15px 8px !important;
            font-size: 13px !important;
        }
        
        #mytable tbody tr {
            border-radius: 15px !important;
        }
        
        .fondosan ul {
            padding-left: 20px;
        }
        
        .fondosan ul li::before {
            left: -20px;
            font-size: 12px;
        }
        
        .fondosan::before {
            font-size: 9px;
            padding: 3px 7px;
            top: -8px;
            right: -8px;
            border-width: 1px;
        }
        
        .fondosan::after {
            font-size: 8px;
            padding: 2px 6px;
            bottom: -6px;
            border-radius: 10px;
        }
        
        .btn-danger, .btn-success {
            padding: 8px 15px !important;
            min-width: 100px;
            font-size: 11px;
            border-radius: 18px !important;
            letter-spacing: 0.5px;
        }
        
        .btn-success i {
            font-size: 14px;
            margin-right: 6px;
        }
        
        .form-control {
            height: 55px !important;
            padding: 12px 50px 12px 20px !important;
            font-size: 14px !important;
            border-radius: 20px !important;
        }
        
        .form-group::after {
            right: 25px;
            font-size: 18px;
        }
    }

    /* Estilos especiales para el tema gabinete */
    .gabinete-glow {
        position: fixed;
        top: 50%;
        left: 50%;
        width: 100vw;
        height: 100vh;
        background: radial-gradient(circle at center, 
            rgba(125, 211, 252, 0.1) 0%, 
            transparent 70%);
        pointer-events: none;
        z-index: 0;
        transform: translate(-50%, -50%);
        animation: gabinete-pulse 8s ease-in-out infinite;
    }

    @keyframes gabinete-pulse {
        0%, 100% { opacity: 0.3; transform: translate(-50%, -50%) scale(1); }
        50% { opacity: 0.6; transform: translate(-50%, -50%) scale(1.1); }
    }

    /* Efecto de escaneo para la tabla */
    .scan-line {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, 
            transparent, 
            rgba(125, 211, 252, 0.8), 
            transparent);
        box-shadow: 0 0 15px 2px rgba(125, 211, 252, 0.6);
        animation: scan-animation 3s linear infinite;
        z-index: 1;
        pointer-events: none;
    }

    @keyframes scan-animation {
        0% { top: 0; opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { top: 100%; opacity: 0; }
    }

    /* Mensaje cuando no hay estudios */
    .empty-state {
        text-align: center;
        padding: 60px 40px;
        color: #94a3b8;
        font-size: 1.4rem;
        background: rgba(22, 33, 62, 0.6);
        border-radius: 20px;
        border: 3px dashed #7dd3fc;
        margin: 30px 0;
        position: relative;
        overflow: hidden;
    }

    .empty-state i {
        font-size: 64px;
        color: #7dd3fc;
        margin-bottom: 25px;
        display: block;
        filter: drop-shadow(0 0 10px rgba(125, 211, 252, 0.5));
        animation: empty-icon-float 3s ease-in-out infinite;
    }

    @keyframes empty-icon-float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .empty-state h3 {
        color: #7dd3fc;
        font-weight: 700;
        margin-bottom: 15px;
        text-shadow: 0 0 10px rgba(125, 211, 252, 0.3);
    }

    .empty-state p {
        color: #cbd5e1;
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }
</style>
</head>

<body>

<div class="container-fluid">

    <?php
    if ($usuario1['id_rol'] == 10) {

        ?>
        <a type="submit" class="btn btn-danger" href="../../template/menu_laboratorio.php">Regresar</a>

        <?php
    } else if ($usuario1['id_rol'] == 5) {

        ?>
        <a type="submit" class="btn btn-danger" href="../../template/menu_gerencia.php">Regresar</a>

        <?php
    }else

    ?>
    <br>
    <br>
<div class="thead" style="background-color: #2b2d7f ; color: white; font-size: 25px;">
         <tr><strong><center>ESTUDIOS DE GABINETE PENDIENTES</center></strong>
      </div><br>

</div>

<section class="content">
        <section class="content container-fluid">
            <div class="content box">
                <div class="form-group">
                    <input type="text" class="form-control pull-right" style="width:25%" id="search" placeholder="Buscar...">
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="mytable">
                        <thead class="thead" style="background-color: #2b2d7f; color:white;">
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
                                      FROM notificaciones_gabinete n 
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
                                $not_id = $row['id_not_gabinete'];

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
                                        . '<td class="fondosan" style="color:white;">' . htmlspecialchars($row['habitacion']) . '</td>'
                                        . '<td class="fondosan" style="color:white;">' . htmlspecialchars($pac) . '</td>'
                                        . '<td class="fondosan" style="color:white;">' . htmlspecialchars($prefijo . '. ' . $nom_tratante) . '</td>'
                                        . '<td class="fondosan" style="color:white;">' . date_format(date_create($row['fecha_ord']), 'd/m/Y H:i a') . '</td>'
                                        . '<td class="fondosan" style="color:white;">' . htmlspecialchars($row['solicitante_papell'] . ' ' . $row['solicitante_sapell']) . '</td>'
                                        . '<td class="fondosan" style="color:white;">';

                                    $estudios = preg_split('/[,;]/', $row['sol_estudios'], -1, PREG_SPLIT_NO_EMPTY);
                                    if (!empty($estudios)) {
                                        echo '<ul style="margin: 0; padding-left: 10px; color: white;">';
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
                                        . '<td class="fondosan" style="color:white;"><center>'
                                        . '<a href="pdf_solicitud_gabinete.php?not_id=' . (int)$not_id . '&id_atencion=' . (int)$id_atencion . '" target="_blank">'
                                        . '<button type="button" class="btn btn-success"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>'
                                        . '</a></center></td>'
                                        . '<td class="fondosan" style="color:white;"><center>'
                                        . '<a href="subir_resultado_gabinete.php?not_id=' . (int)$not_id . '" title="Subir resultado" class="btn btn-success"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>'
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
                                        . '<td class="fondosan" style="color:white;">' . htmlspecialchars($habitacion) . '</td>'
                                        . '<td class="fondosan" style="color:white;">' . htmlspecialchars($pac) . '</td>'
                                        . '<td class="fondosan" style="color:white;">N/A</td>'
                                        . '<td class="fondosan" style="color:white;">' . date_format(date_create($row['fecha_ord']), 'd/m/Y H:i a') . '</td>'
                                        . '<td class="fondosan" style="color:white;">' . htmlspecialchars($row['solicitante_papell'] . ' ' . $row['solicitante_sapell']) . '</td>'
                                        . '<td class="fondosan" style="color:white;">';

                                    $estudios = preg_split('/[,;]/', $row['sol_estudios'], -1, PREG_SPLIT_NO_EMPTY);
                                    if (!empty($estudios)) {
                                        echo '<ul style="margin: 0; padding-left: 10px; color: white;">';
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
                                        . '<td class="fondosan" style="color:white;"><center>'
                                        . '<a href="pdf_solicitud_gabinete.php?not_id=' . (int)$not_id . '&id_atencion=' . (int)$id_atencion . '" target="_blank">'
                                        . '<button type="button" class="btn btn-success"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>'
                                        . '</a></center></td>'
                                        . '<td class="fondosan" style="color:white;"><center>'
                                        . '<a href="subir_resultado_gabinete.php?not_id=' . (int)$not_id . '" title="Subir resultado" class="btn btn-success"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>'
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
        </section>
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