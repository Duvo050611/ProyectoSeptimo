<?php
session_start();
include "../../configuracion/header_configuracion.php";

$resultado = $conexion->query("select * from reg_usuarios") or die($conexion->error);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Servicios - Sistema Cyberpunk</title>
    <link rel="icon" href="../imagenes/SIF.PNG">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        /* ============================================
           ESTILOS BASE CYBERPUNK
        ============================================ */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #0a0a0a 100%) !important;
            font-family: 'Roboto', sans-serif !important;
            min-height: 100vh;
            padding: 20px 0;
            position: relative;
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

        /* ============================================
           CONTENEDOR PRINCIPAL
        ============================================ */
        .container-fluid {
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%);
            border: 2px solid #40E0FF;
            border-radius: 20px;
            padding: 35px;
            margin: 20px auto;
            max-width: 98%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5),
                        0 0 30px rgba(64, 224, 255, 0.2);
            position: relative;
            z-index: 1;
            overflow: visible;
            animation: fadeInUp 0.8s ease-out;
        }

        .container-fluid::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent,
                rgba(64, 224, 255, 0.05),
                transparent
            );
            transform: rotate(45deg);
            animation: shimmer 6s ease-in-out infinite;
            pointer-events: none;
            z-index: -1;
        }

        @keyframes shimmer {
            0%, 100% { transform: translateX(-100%) rotate(45deg); }
            50% { transform: translateX(100%) rotate(45deg); }
        }

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

        /* ============================================
           HEADER PRINCIPAL
        ============================================ */
        h2 {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
            border: 2px solid #40E0FF;
            border-radius: 15px;
            padding: 25px;
            margin: 0 0 30px 0;
            color: #ffffff;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            box-shadow: 0 8px 25px rgba(64, 224, 255, 0.3),
                        inset 0 0 20px rgba(64, 224, 255, 0.1);
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        h2::before {
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

        h2 font,
        h2 i {
            position: relative;
            z-index: 1;
            text-shadow: 0 0 20px rgba(64, 224, 255, 0.8);
        }

        h2 i {
            margin-right: 15px;
            font-size: 28px;
        }

        /* ============================================
           SEPARADOR HORIZONTAL
        ============================================ */
        hr {
            border: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, #40E0FF, transparent);
            margin: 30px 0;
            box-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
        }

        /* ============================================
           ÁREA DE BOTONES
        ============================================ */
        .row {
            position: relative;
            z-index: 1;
        }

        /* Botón Nuevo Servicio */
        .btn-primary {
            background: linear-gradient(135deg, rgba(64, 224, 255, 0.3) 0%, rgba(0, 217, 255, 0.4) 100%) !important;
            border: 2px solid #40E0FF !important;
            color: #ffffff !important;
            padding: 15px 35px !important;
            border-radius: 50px !important;
            font-size: 16px !important;
            font-weight: 700 !important;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-transform: uppercase;
            letter-spacing: 2px;
            box-shadow: 0 8px 25px rgba(64, 224, 255, 0.3),
                        inset 0 0 20px rgba(64, 224, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
            pointer-events: none;
        }

        .btn-primary:hover::before {
            width: 400px;
            height: 400px;
        }

        .btn-primary:hover {
            transform: translateY(-5px) scale(1.05) !important;
            border-color: #00D9FF !important;
            background: linear-gradient(135deg, rgba(64, 224, 255, 0.4) 0%, rgba(0, 217, 255, 0.5) 100%) !important;
            box-shadow: 0 15px 40px rgba(64, 224, 255, 0.5),
                        inset 0 0 30px rgba(64, 224, 255, 0.2),
                        0 0 50px rgba(64, 224, 255, 0.3) !important;
            color: #ffffff !important;
            text-shadow: 0 0 15px rgba(64, 224, 255, 0.8);
        }

        .btn-primary i,
        .btn-primary font {
            position: relative;
            z-index: 1;
        }

        /* Botón Exportar Excel */
        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%) !important;
            border: 2px solid #ffc107 !important;
            color: #0a0a0a !important;
            padding: 15px 35px !important;
            border-radius: 50px !important;
            font-size: 16px !important;
            font-weight: 700 !important;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-transform: uppercase;
            letter-spacing: 2px;
            box-shadow: 0 8px 25px rgba(255, 193, 7, 0.4);
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-warning::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
            pointer-events: none;
        }

        .btn-warning:hover::before {
            width: 400px;
            height: 400px;
        }

        .btn-warning:hover {
            transform: translateY(-5px) scale(1.05) !important;
            box-shadow: 0 15px 40px rgba(255, 193, 7, 0.6),
                        0 0 30px rgba(255, 193, 7, 0.4) !important;
            color: #0a0a0a !important;
            text-decoration: none;
        }

        .btn-warning img {
            width: 28px;
            height: 28px;
            filter: drop-shadow(0 0 5px rgba(255, 193, 7, 0.8));
        }

        .btn-warning strong {
            position: relative;
            z-index: 1;
        }

        /* ============================================
           CAMPO DE BÚSQUEDA
        ============================================ */
        .form-group {
            margin-bottom: 25px;
            position: relative;
            z-index: 1;
        }

        #search {
            border: 2px solid #40E0FF !important;
            border-radius: 50px !important;
            padding: 14px 25px 14px 50px !important;
            font-size: 16px !important;
            transition: all 0.4s ease !important;
            background: rgba(10, 10, 10, 0.5) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(64, 224, 255, 0.2),
                        inset 0 0 15px rgba(64, 224, 255, 0.05) !important;
            font-weight: 500;
        }

        #search::placeholder {
            color: rgba(64, 224, 255, 0.7);
            font-weight: 600;
        }

        #search:focus {
            border-color: #00D9FF !important;
            box-shadow: 0 0 0 0.3rem rgba(64, 224, 255, 0.25),
                        0 6px 25px rgba(64, 224, 255, 0.4),
                        inset 0 0 25px rgba(64, 224, 255, 0.1) !important;
            outline: none !important;
            background: rgba(10, 10, 10, 0.7) !important;
            transform: translateY(-2px);
        }

        /* ============================================
           TABLA CYBERPUNK
        ============================================ */
        .table-responsive {
            background: transparent;
            border-radius: 15px;
            overflow-x: auto;
            position: relative;
            z-index: 1;
            margin-top: 30px;
        }

        .table {
            background: linear-gradient(135deg, rgba(22, 33, 62, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5),
                        0 0 20px rgba(64, 224, 255, 0.2);
            border: 2px solid #40E0FF;
            margin: 0;
        }

        .table thead.thead {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        }

        .table thead th {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
            color: #40E0FF !important;
            border: none !important;
            padding: 18px 12px !important;
            font-weight: 700 !important;
            font-size: 13px !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            text-align: center !important;
            text-shadow: 0 0 15px rgba(64, 224, 255, 0.5);
            border-bottom: 2px solid #40E0FF !important;
            white-space: nowrap;
        }

        .table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(64, 224, 255, 0.1);
            background: rgba(22, 33, 62, 0.3);
            animation: slideIn 0.5s ease-in-out backwards;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .table tbody tr:nth-child(odd) {
            background: rgba(22, 33, 62, 0.4);
        }

        .table tbody tr:hover {
            background: linear-gradient(135deg, rgba(64, 224, 255, 0.15) 0%, rgba(0, 217, 255, 0.1) 100%) !important;
            transform: translateX(5px) scale(1.005);
            box-shadow: 0 4px 20px rgba(64, 224, 255, 0.3),
                        inset 0 0 15px rgba(64, 224, 255, 0.1);
        }

        .table tbody td {
            padding: 15px 12px !important;
            vertical-align: middle !important;
            border: none !important;
            font-size: 13px !important;
            color: #ffffff !important;
            text-align: center !important;
            font-weight: 500;
        }

        /* ============================================
           BOTONES EN TABLA
        ============================================ */
        .btn-sm {
            padding: 8px 16px !important;
            border-radius: 10px !important;
            font-weight: 700 !important;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid !important;
            margin: 3px;
            position: relative;
            overflow: hidden;
        }

        .btn-sm::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            top: -100%;
            left: 0;
            transition: top 0.3s;
            pointer-events: none;
        }

        .btn-sm:hover::before {
            top: 0;
        }

        /* Botón Activo (Verde) */
        .btn-success.btn-sm {
            background: linear-gradient(135deg, #00f525 0%, #00c41d 100%) !important;
            border-color: #00f525 !important;
            color: #0a0a0a !important;
            box-shadow: 0 4px 15px rgba(0, 245, 37, 0.4);
        }

        .btn-success.btn-sm:hover {
            transform: translateY(-3px) scale(1.1) !important;
            box-shadow: 0 8px 25px rgba(0, 245, 37, 0.6),
                        0 0 30px rgba(0, 245, 37, 0.4) !important;
            color: #0a0a0a !important;
        }

        /* Botón Inactivo (Rojo) */
        .btn-danger.btn-sm {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
            border-color: #dc3545 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
        }

        .btn-danger.btn-sm:hover {
            transform: translateY(-3px) scale(1.1) !important;
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.6),
                        0 0 30px rgba(220, 53, 69, 0.4) !important;
            color: #ffffff !important;
        }

        /* Botón Editar */
        .btn-warning.btn-sm {
            background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%) !important;
            border-color: #ffc107 !important;
            color: #0a0a0a !important;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.4);
        }

        .btn-warning.btn-sm:hover {
            transform: translateY(-3px) scale(1.1) !important;
            box-shadow: 0 8px 25px rgba(255, 193, 7, 0.6),
                        0 0 30px rgba(255, 193, 7, 0.4) !important;
            color: #0a0a0a !important;
            text-decoration: none;
        }

        /* ============================================
           MODAL CYBERPUNK
        ============================================ */
        .modal {
            z-index: 9999 !important;
        }

        .modal-backdrop {
            z-index: 9998 !important;
            background-color: rgba(0, 0, 0, 0.8);
        }

        .modal-content {
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%);
            border: 2px solid #40E0FF;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.9),
                        0 0 40px rgba(64, 224, 255, 0.4);
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
            border-bottom: 2px solid #40E0FF;
            padding: 25px 30px;
            position: relative;
        }

        .modal-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 50% 50%, rgba(64, 224, 255, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .modal-title {
            color: #40E0FF;
            font-weight: 700;
            font-size: 22px;
            letter-spacing: 2px;
            text-shadow: 0 0 20px rgba(64, 224, 255, 0.8);
            text-transform: uppercase;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .modal-header .close {
            color: #40E0FF;
            opacity: 1;
            text-shadow: 0 0 15px rgba(64, 224, 255, 0.8);
            font-size: 32px;
            transition: all 0.3s ease;
            background: transparent;
            border: none;
            padding: 0;
            margin: -1rem -1rem -1rem auto;
        }

        .modal-header .close:hover {
            color: #00D9FF;
            transform: rotate(90deg);
        }

        .modal-body {
            background: transparent;
            padding: 30px;
            position: relative;
            z-index: 1;
            max-height: 70vh;
            overflow-y: auto;
        }

        /* ============================================
           FORMULARIOS CYBERPUNK
        ============================================ */
        .modal-body .form-group {
            margin-bottom: 25px;
            padding: 10px;
            border-radius: 12px;
            transition: all 0.3s ease;
            position: relative;
            animation: fadeInUp 0.6s ease-out backwards;
        }

        .modal-body .form-group:nth-child(1) { animation-delay: 0.05s; }
        .modal-body .form-group:nth-child(2) { animation-delay: 0.1s; }
        .modal-body .form-group:nth-child(3) { animation-delay: 0.15s; }
        .modal-body .form-group:nth-child(4) { animation-delay: 0.2s; }
        .modal-body .form-group:nth-child(5) { animation-delay: 0.25s; }

        .modal-body .form-group:hover {
            background: rgba(64, 224, 255, 0.05);
            transform: translateX(5px);
        }

        .modal-body label {
            color: #40E0FF !important;
            font-weight: 700 !important;
            margin-bottom: 10px !important;
            font-size: 13px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.8px !important;
            text-shadow: 0 0 10px rgba(64, 224, 255, 0.3);
            display: block;
        }

        .modal-body .form-control,
        .modal-body select.form-control {
            border: 2px solid #40E0FF !important;
            border-radius: 12px !important;
            padding: 14px 18px !important;
            transition: all 0.3s ease !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            background: rgba(10, 10, 10, 0.5) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(64, 224, 255, 0.1),
                        inset 0 0 15px rgba(64, 224, 255, 0.05) !important;
        }

        .modal-body .form-control::placeholder {
            color: rgba(64, 224, 255, 0.5);
            font-style: italic;
        }

        .modal-body .form-control:focus,
        .modal-body select.form-control:focus {
            border-color: #00D9FF !important;
            box-shadow: 0 0 0 0.3rem rgba(64, 224, 255, 0.25),
                        0 6px 25px rgba(64, 224, 255, 0.3),
                        inset 0 0 20px rgba(64, 224, 255, 0.1) !important;
            transform: translateY(-2px);
            outline: none !important;
            background: rgba(10, 10, 10, 0.7) !important;
        }

        /* Select Dropdown Options */
        .modal-body select.form-control option {
            background: #0f3460;
            color: #ffffff;
            padding: 10px;
        }

        /* ============================================
           FOOTER MODAL
        ============================================ */
        .modal-footer {
            padding: 25px 30px;
            border-top: 2px solid #40E0FF;
            background: linear-gradient(135deg, rgba(15, 52, 96, 0.5) 0%, rgba(22, 33, 62, 0.5) 100%);
        }

        .modal-footer .btn {
            padding: 12px 35px !important;
            border-radius: 50px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 1.5px !important;
            border: 2px solid !important;
            margin: 0 8px !important;
            transition: all 0.3s ease !important;
        }

        .modal-footer .btn-success {
            background: linear-gradient(135deg, rgba(0, 245, 37, 0.3) 0%, rgba(0, 196, 29, 0.4) 100%) !important;
            border-color: #00f525 !important;
            color: #ffffff !important;
            box-shadow: 0 6px 20px rgba(0, 245, 37, 0.3);
        }

        .modal-footer .btn-success:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 10px 30px rgba(0, 245, 37, 0.5),
                        0 0 40px rgba(0, 245, 37, 0.3) !important;
            background: linear-gradient(135deg, rgba(0, 245, 37, 0.4) 0%, rgba(0, 196, 29, 0.5) 100%) !important;
            color: #ffffff !important;
        }

        .modal-footer .btn-danger {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.3) 0%, rgba(200, 35, 51, 0.4) 100%) !important;
            border-color: #dc3545 !important;
            color: #ffffff !important;
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.3);
        }

        .modal-footer .btn-danger:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 10px 30px rgba(220, 53, 69, 0.5),
                        0 0 40px rgba(220, 53, 69, 0.3) !important;
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.4) 0%, rgba(200, 35, 51, 0.5) 100%) !important;
            color: #ffffff !important;
        }

        /* ============================================
           SCROLLBAR CYBERPUNK
        ============================================ */
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

        /* Scrollbar del modal */
        .modal-body::-webkit-scrollbar {
            width: 8px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: rgba(10, 10, 10, 0.5);
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #40E0FF 0%, #0f3460 100%);
            border-radius: 10px;
        }

        /* ============================================
           RESPONSIVE
        ============================================ */
        @media screen and (max-width: 980px) {
            .container-fluid {
                width: 95%;
                margin: 10px auto !important;
                padding: 25px !important;
            }

            .table {
                font-size: 12px;
            }

            .table thead th,
            .table tbody td {
                padding: 10px 8px !important;
                font-size: 11px !important;
            }
        }

        @media screen and (max-width: 768px) {
            .container-fluid {
                padding: 20px !important;
            }

            h2 {
                font-size: 18px !important;
                padding: 20px !important;
                letter-spacing: 1.5px;
            }

            h2 i {
                font-size: 20px;
            }

            .btn-primary,
            .btn-warning {
                padding: 12px 25px !important;
                font-size: 14px !important;
                width: 100%;
                margin-bottom: 10px !important;
            }

            #search {
                width: 100% !important;
            }

            .table-responsive {
                overflow-x: auto;
            }

            .modal-body {
                padding: 20px;
            }

            .btn-sm {
                padding: 6px 12px !important;
                font-size: 12px !important;
            }
        }

        @media screen and (max-width: 576px) {
            .container-fluid {
                margin: 5px !important;
                padding: 15px !important;
            }

            h2 {
                font-size: 16px !important;
                padding: 15px !important;
            }

            .modal-footer .btn {
                width: 100%;
                margin: 5px 0 !important;
            }
        }

        /* ============================================
           FOOTER
        ============================================ */
        .main-footer {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            border-top: 2px solid #40E0FF !important;
            color: #ffffff !important;
            box-shadow: 0 -4px 20px rgba(64, 224, 255, 0.2);
            margin-top: 50px;
            padding: 20px;
            text-align: center;
        }

        /* ============================================
           ACCESIBILIDAD
        ============================================ */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* ============================================
           UTILIDADES
        ============================================ */
        .text-center {
            text-align: center;
        }

        center {
            position: relative;
            z-index: 1;
        }

        /* Mejora de contraste para texto */
        strong {
            font-weight: 700 !important;
            letter-spacing: 0.5px;
        }

    </style>
</head>

<body>
    <!-- Scripts deben ir aquí ANTES del contenido -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

<div class="container-fluid">
    <div class="row">
        <div class="col col-12">
            <h2>
                <i class="fa fa-plus-square"></i>
                <font id="letra">CATÁLOGO DE SERVICIOS</font>
            </h2>

            <hr>

            <div class="row">
                <div class="col-md-6 col-12 mb-3">
                    <center>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                            <i class="fa fa-plus"></i>
                            <font id="letra"> Nuevo servicio</font>
                        </button>
                    </center>
                </div>
                <div class="col-md-6 col-12 mb-3">
                    <center>
                        <a href="excel.php">
                            <button type="button" class="btn btn-warning">
                                <img src="https://img.icons8.com/color/48/000000/ms-excel.png" alt="Excel"/>
                                <strong>Exportar a Excel</strong>
                            </button>
                        </a>
                    </center>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Insertar -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="insertar_servicio.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <i class="fa fa-plus-circle"></i> Nuevo servicio
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="clave">Clave:</label>
                                    <input type="text" size="15" name="clave" id="clave" value="" required class="form-control" placeholder="Ingrese la clave">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="descripcion">Descripción:</label>
                                    <input type="text" name="descripcion" id="descripcion" class="form-control" value="" required placeholder="Descripción del servicio">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="costo">Precio 1:</label>
                                    <input type="number" step="0.01" min="0" name="costo" id="costo" class="form-control" value="" required placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="costo2">Precio 2 (AXA):</label>
                                    <input type="number" step="0.01" min="0" name="costo2" id="costo2" class="form-control" value="" placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="costo3">Precio 3 (GNP):</label>
                                    <input type="number" step="0.01" min="0" name="costo3" id="costo3" class="form-control" value="" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="costo4">Precio 4 (Rentas):</label>
                                    <input type="number" step="0.01" min="0" name="costo4" id="costo4" class="form-control" value="" placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="costo5">Precio 5:</label>
                                    <input type="number" step="0.01" min="0" name="costo5" id="costo5" class="form-control" value="" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="costo6">Precio 6:</label>
                                    <input type="number" step="0.01" min="0" name="costo6" id="costo6" class="form-control" value="" placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="costo7">Precio 7:</label>
                                    <input type="number" step="0.01" min="0" name="costo7" id="costo7" class="form-control" value="" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="costo8">Precio 8:</label>
                                    <input type="number" step="0.01" min="0" name="costo8" id="costo8" class="form-control" value="" placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="med">Unidad de medida:</label>
                                    <select name="med" class="form-control" required>
                                        <option value="">Seleccionar</option>
                                        <option value="CONSULTA">CONSULTA</option>
                                        <option value="EQUIPO">EQUIPO</option>
                                        <option value="ESTUDIO">ESTUDIO</option>
                                        <option value="HORA">HORA</option>
                                        <option value="SERVICIO">SERVICIO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipo">Tipo:</label>
                                    <select id="item-type" class="form-control" name="tipo" required>
                                        <option value="">Seleccionar</option>
                                        <?php
                                        $query = "SELECT * FROM `service_type`";
                                        $result = $conexion->query($query);
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['ser_type_id'] . "' data-desc='" . $row['ser_type_desc'] . "'>" . $row['ser_type_desc'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <input type="hidden" name="tip_insumo" id="tip_insumo" value="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="proveedor">Proveedor:</label>
                                    <select name="proveedor" id="proveedor" class="form-control" required>
                                        <option value="">Seleccionar</option>
                                        <?php
                                        $query_prov = "SELECT id_prov, nom_prov FROM proveedores";
                                        $result_prov = $conexion->query($query_prov);
                                        while ($row_prov = $result_prov->fetch_assoc()) {
                                            echo "<option value='" . $row_prov['id_prov'] . "'>" . $row_prov['nom_prov'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="grupo">Grupo:</label>
                                    <select name="grupo" id="grupo" class="form-control" required>
                                        <option value="">Seleccionar</option>
                                        <option value="SERVICIOS HOSPITALARIOS">SERVICIOS HOSPITALARIOS</option>
                                        <option value="IMAGENOLOGIA">IMAGENOLOGIA</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codigo_sat">Código SAT:</label>
                                    <input type="text" name="codigo_sat" id="codigo_sat" class="form-control" value="" required placeholder="Código SAT">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="c_cveuni">Clave Unidad:</label>
                                    <input type="text" name="c_cveuni" id="c_cveuni" class="form-control" value="" required placeholder="Clave Unidad">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save"></i> Guardar
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fa fa-times"></i> Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabla de Servicios -->
    <section class="content">
        <div class="table-responsive">
            <div class="content">
                <?php
                include "../../conexionbd.php";
                $resultado2 = $conexion->query("SELECT s.*, t.ser_type_desc as tipo, p.nom_prov as proveedor FROM cat_servicios s LEFT JOIN service_type t ON s.tipo = t.ser_type_id LEFT JOIN proveedores p ON s.proveedor = p.id_prov ORDER BY id_serv") or die($conexion->error);
                ?>
                <div class="form-group">
                    <input type="text" class="form-control pull-right" style="width:100%; max-width: 400px; margin-left: auto; display: block;" id="search" placeholder="🔍 Buscar servicio...">
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="mytable">
                        <thead class="thead">
                        <tr>
                            <th>Id</th>
                            <th>Clave</th>
                            <th>Descripción</th>
                            <th>Precio 1</th>
                            <th>Precio 2<br>AXA</th>
                            <th>Precio 3<br>GNP</th>
                            <th>Precio 4<br>Rentas</th>
                            <th>Precio 5</th>
                            <th>Precio 6</th>
                            <th>Precio 7</th>
                            <th>Precio 8</th>
                            <th>U.M.</th>
                            <th>Tipo</th>
                            <th>Activo</th>
                            <?php
                            $usuario = $_SESSION['login'];
                            $rol = $usuario['id_rol'];
                            if ($rol == 5) { ?>
                                <th>Edita</th>
                            <?php } ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 1;
                        while ($row = $resultado2->fetch_assoc()) {
                            $eid = $row['id_serv'];
                            echo '<tr>'
                                . '<td>' . $row['id_serv'] . '</td>'
                                . '<td>' . $row['serv_cve'] . '</td>'
                                . '<td>' . $row['serv_desc'] . '</td>'
                                . '<td>$' . number_format($row['serv_costo'], 2) . '</td>'
                                . '<td>$' . number_format($row['serv_costo2'], 2) . '</td>'
                                . '<td>$' . number_format($row['serv_costo3'], 2) . '</td>'
                                . '<td>$' . number_format($row['serv_costo4'], 2) . '</td>'
                                . '<td>$' . number_format($row['serv_costo5'], 2) . '</td>'
                                . '<td>$' . number_format($row['serv_costo6'], 2) . '</td>'
                                . '<td>$' . number_format($row['serv_costo7'], 2) . '</td>'
                                . '<td>$' . number_format($row['serv_costo8'], 2) . '</td>'
                                . '<td>' . $row['serv_umed'] . '</td>'
                                . '<td>' . $row['tipo'] . '</td>';
                            ?>
                            <td>
                                <?php
                                if ((strpos($row['serv_activo'], 'NO') !== false)) {
                                    echo '<a class="btn btn-danger btn-sm" href="insertar_servicio.php?q=estatus&eid=' . $eid . '&est=' . $row['serv_activo'] . '" title="Activar servicio"><span class="fa fa-power-off"></span></a>';
                                } else {
                                    echo '<a class="btn btn-success btn-sm" href="insertar_servicio.php?q=estatus&eid=' . $eid . '&est=' . $row['serv_activo'] . '" title="Desactivar servicio"><span class="fa fa-power-off"></span></a>';
                                }
                                ?>
                            </td>
                            <?php
                            if ($rol == 5) {
                                echo '<td><a href="edit_servicios.php?id=' . $row['id_serv'] . '" title="Editar datos" class="btn btn-warning btn-sm"><span class="fa fa-edit" aria-hidden="true"></span></a></td>';
                            }
                            echo '</tr>';
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

<script>
    $(document).ready(function () {
        // ======= BÚSQUEDA EN TIEMPO REAL =======
        $("#search").keyup(function () {
            _this = this;
            $.each($("#mytable tbody tr"), function () {
                if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
                    $(this).fadeOut(200);
                else
                    $(this).fadeIn(200);
            });
        });

        // ======= SINCRONIZAR TIPO E INPUT HIDDEN =======
        $('#item-type').change(function() {
            var desc = $(this).find('option:selected').data('desc');
            $('#tip_insumo').val(desc);
        });

        // ======= ANIMACIÓN DE ENTRADA SECUENCIAL PARA FILAS =======
        $('tbody tr').each(function(index) {
            $(this).css('animation-delay', (index * 0.03) + 's');
        });

        // ======= EFECTOS HOVER MEJORADOS =======
        $('.btn-sm').hover(
            function() {
                $(this).addClass('shadow-lg');
            },
            function() {
                $(this).removeClass('shadow-lg');
            }
        );

        // ======= LIMPIAR MODAL AL CERRAR =======
        $('#exampleModal').on('hidden.bs.modal', function () {
            $(this).find('form')[0].reset();
        });

        // ======= VALIDACIÓN BÁSICA DE FORMULARIO =======
        $('#exampleModal form').submit(function(e) {
            var clave = $('#clave').val().trim();
            var descripcion = $('#descripcion').val().trim();

            if (clave === '' || descripcion === '') {
                e.preventDefault();
                alert('⚠️ Por favor, complete todos los campos obligatorios.');
                return false;
            }
        });

        // ======= CONTADOR DE REGISTROS =======
        var totalRecords = $("#mytable tbody tr").length;
        console.log('📊 Total de servicios cargados: ' + totalRecords);
    });
</script>

<script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
</body>
</html>
