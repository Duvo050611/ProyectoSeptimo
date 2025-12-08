<?php
session_start();
include "../../configuracion/header_configuracion.php";
$resultado = $conexion->query("select * from reg_usuarios") or die($conexion->error);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Diagnósticos - Sistema Cyberpunk</title>
    <link rel="icon" href="../imagenes/SIF.PNG">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        /* ============================================
           ESTILOS BASE CYBERPUNK
        ============================================ */
        * {
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #0a0a0a 100%) !important;
            font-family: 'Roboto', sans-serif !important;
            min-height: 100vh;
            margin: 0;
            padding: 20px 0;
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
        .main-container {
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%);
            border: 2px solid #40E0FF;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5),
                        0 0 30px rgba(64, 224, 255, 0.2);
            margin: 30px auto;
            padding: 0;
            max-width: 95%;
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.8s ease-out;
            overflow: hidden;
        }

        /* Efecto de brillo en contenedor */
        .main-container::before {
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
            animation: shine 6s ease-in-out infinite;
        }

        @keyframes shine {
            0%, 100% { left: -50%; }
            50% { left: 150%; }
        }

        /* ============================================
           HEADER PRINCIPAL
        ============================================ */
        .header-section {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
            border-bottom: 2px solid #40E0FF;
            color: #ffffff;
            padding: 30px;
            border-radius: 20px 20px 0 0;
            text-align: center;
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
            background: radial-gradient(circle, rgba(64, 224, 255, 0.15) 0%, transparent 70%);
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .header-section h2 {
            margin: 0;
            font-weight: 700;
            font-size: 32px;
            text-transform: uppercase;
            letter-spacing: 3px;
            text-shadow: 0 0 20px rgba(64, 224, 255, 0.8),
                         0 0 30px rgba(64, 224, 255, 0.5);
            position: relative;
            z-index: 1;
        }

        .header-section i {
            margin-right: 15px;
            font-size: 36px;
            animation: rotateIcon 3s ease-in-out infinite;
        }

        @keyframes rotateIcon {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(10deg); }
        }

        /* ============================================
           ÁREA DE BOTONES
        ============================================ */
        .action-buttons {
            background: linear-gradient(135deg, rgba(15, 52, 96, 0.8) 0%, rgba(22, 33, 62, 0.8) 100%);
            border-bottom: 2px solid rgba(64, 224, 255, 0.3);
            padding: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        /* Botón Nuevo Diagnóstico Cyberpunk */
        .btn-nuevo-modern {
            background: linear-gradient(135deg, rgba(64, 224, 255, 0.3) 0%, rgba(0, 217, 255, 0.4) 100%);
            border: 2px solid #40E0FF;
            color: #ffffff;
            padding: 18px 40px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-transform: uppercase;
            letter-spacing: 2px;
            box-shadow: 0 8px 25px rgba(64, 224, 255, 0.3),
                        inset 0 0 20px rgba(64, 224, 255, 0.1);
            position: relative;
            overflow: hidden;
            min-width: 250px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .btn-nuevo-modern::before {
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
        }

        .btn-nuevo-modern:hover::before {
            width: 400px;
            height: 400px;
        }

        .btn-nuevo-modern:hover {
            transform: translateY(-5px) scale(1.05);
            border-color: #00D9FF;
            background: linear-gradient(135deg, rgba(64, 224, 255, 0.4) 0%, rgba(0, 217, 255, 0.5) 100%);
            box-shadow: 0 15px 40px rgba(64, 224, 255, 0.5),
                        inset 0 0 30px rgba(64, 224, 255, 0.2),
                        0 0 50px rgba(64, 224, 255, 0.3);
            text-shadow: 0 0 15px rgba(64, 224, 255, 0.8);
        }

        .btn-nuevo-modern i {
            font-size: 20px;
            position: relative;
            z-index: 1;
        }

        .btn-nuevo-modern span {
            position: relative;
            z-index: 1;
        }

        /* ============================================
           SECCIÓN DE BÚSQUEDA
        ============================================ */
        .search-section {
            background: linear-gradient(135deg, rgba(22, 33, 62, 0.5) 0%, rgba(15, 52, 96, 0.5) 100%);
            border-bottom: 2px solid rgba(64, 224, 255, 0.2);
            padding: 25px 30px;
            position: relative;
            z-index: 1;
        }

        .search-input {
            border: 2px solid #40E0FF;
            border-radius: 50px;
            padding: 14px 25px 14px 50px;
            font-size: 16px;
            transition: all 0.4s ease;
            width: 100%;
            max-width: 400px;
            margin-left: auto;
            display: block;
            background: rgba(10, 10, 10, 0.5);
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(64, 224, 255, 0.2),
                        inset 0 0 15px rgba(64, 224, 255, 0.05);
            font-weight: 500;
        }

        .search-input::placeholder {
            color: rgba(64, 224, 255, 0.7);
            font-weight: 600;
        }

        .search-input:focus {
            border-color: #00D9FF;
            box-shadow: 0 0 0 0.3rem rgba(64, 224, 255, 0.25),
                        0 6px 25px rgba(64, 224, 255, 0.4),
                        inset 0 0 25px rgba(64, 224, 255, 0.1);
            outline: none;
            background: rgba(10, 10, 10, 0.7);
            transform: translateY(-2px);
        }

        /* ============================================
           TABLA CYBERPUNK
        ============================================ */
        .table-container {
            padding: 30px;
            background: transparent;
            position: relative;
            z-index: 1;
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

        .table thead th {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
            color: #40E0FF;
            border: none;
            padding: 22px 20px;
            font-weight: 700;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            text-align: center;
            text-shadow: 0 0 15px rgba(64, 224, 255, 0.5);
            border-bottom: 2px solid #40E0FF;
            position: relative;
        }

        .table thead th i {
            margin-right: 8px;
            font-size: 16px;
        }

        .table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(64, 224, 255, 0.1);
            background: rgba(22, 33, 62, 0.3);
            animation: slideIn 0.5s ease-in-out backwards;
        }

        .table tbody tr:nth-child(1) { animation-delay: 0.05s; }
        .table tbody tr:nth-child(2) { animation-delay: 0.1s; }
        .table tbody tr:nth-child(3) { animation-delay: 0.15s; }
        .table tbody tr:nth-child(4) { animation-delay: 0.2s; }
        .table tbody tr:nth-child(5) { animation-delay: 0.25s; }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .table tbody tr:hover {
            background: linear-gradient(135deg, rgba(64, 224, 255, 0.15) 0%, rgba(0, 217, 255, 0.1) 100%);
            transform: translateX(8px) scale(1.01);
            box-shadow: 0 4px 20px rgba(64, 224, 255, 0.3),
                        inset 0 0 15px rgba(64, 224, 255, 0.1);
        }

        .table tbody td {
            padding: 20px;
            vertical-align: middle;
            border: none;
            font-size: 15px;
            color: #ffffff;
            text-align: center;
            font-weight: 500;
        }

        /* Badges Cyberpunk */
        .badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 0.5px;
            border: 2px solid;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .badge-primary {
            background: linear-gradient(135deg, #40E0FF 0%, #00D9FF 100%);
            border-color: #40E0FF;
            color: #0a0a0a;
            text-shadow: none;
        }

        .badge-info {
            background: linear-gradient(135deg, rgba(64, 224, 255, 0.3) 0%, rgba(0, 217, 255, 0.4) 100%);
            border-color: #40E0FF;
            color: #40E0FF;
            text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
        }

        /* ============================================
           BOTONES DE ACCIÓN
        ============================================ */
        .btn-action {
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 700;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid;
            margin: 3px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            position: relative;
            overflow: hidden;
        }

        .btn-action::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            top: -100%;
            left: 0;
            transition: top 0.3s;
        }

        .btn-action:hover::before {
            top: 0;
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
            border-color: #ffc107;
            color: #0a0a0a;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.4);
        }

        .btn-warning:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(255, 193, 7, 0.6),
                        0 0 30px rgba(255, 193, 7, 0.4);
            color: #0a0a0a;
            text-decoration: none;
        }

        /* ============================================
           MODAL CYBERPUNK
        ============================================ */
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

        .modal-header h5 {
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

        .modal-header h5 i {
            margin-right: 12px;
            font-size: 24px;
        }

        .modal-header .close {
            color: #40E0FF;
            opacity: 1;
            text-shadow: 0 0 15px rgba(64, 224, 255, 0.8);
            font-size: 32px;
            transition: all 0.3s ease;
        }

        .modal-header .close:hover {
            color: #00D9FF;
            transform: rotate(90deg);
        }

        .modal-body {
            background: transparent;
            padding: 35px;
            position: relative;
            z-index: 1;
        }

        /* ============================================
           FORMULARIOS CYBERPUNK
        ============================================ */
        .form-group {
            margin-bottom: 30px;
            padding: 12px;
            border-radius: 12px;
            transition: all 0.3s ease;
            position: relative;
        }

        .form-group:hover {
            background: rgba(64, 224, 255, 0.05);
            transform: translateX(5px);
        }

        .form-group label {
            color: #40E0FF;
            font-weight: 700;
            margin-bottom: 12px;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-shadow: 0 0 10px rgba(64, 224, 255, 0.3);
            display: block;
        }

        .form-group label i {
            color: #40E0FF;
            margin-right: 10px;
            font-size: 18px;
        }

        .form-control {
            border: 2px solid #40E0FF;
            border-radius: 12px;
            padding: 16px 20px;
            transition: all 0.3s ease;
            font-size: 15px;
            font-weight: 500;
            background: rgba(10, 10, 10, 0.5);
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(64, 224, 255, 0.1),
                        inset 0 0 15px rgba(64, 224, 255, 0.05);
        }

        .form-control::placeholder {
            color: rgba(64, 224, 255, 0.5);
            font-style: italic;
        }

        .form-control:focus {
            border-color: #00D9FF;
            box-shadow: 0 0 0 0.3rem rgba(64, 224, 255, 0.25),
                        0 6px 25px rgba(64, 224, 255, 0.3),
                        inset 0 0 20px rgba(64, 224, 255, 0.1);
            transform: translateY(-2px);
            outline: none;
            background: rgba(10, 10, 10, 0.7);
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
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 2px solid;
            margin: 0 5px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-success {
            background: linear-gradient(135deg, rgba(64, 224, 255, 0.3) 0%, rgba(0, 217, 255, 0.4) 100%);
            border-color: #40E0FF;
            color: #ffffff;
            box-shadow: 0 6px 20px rgba(64, 224, 255, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(64, 224, 255, 0.5),
                        0 0 40px rgba(64, 224, 255, 0.3);
            background: linear-gradient(135deg, rgba(64, 224, 255, 0.4) 0%, rgba(0, 217, 255, 0.5) 100%);
            border-color: #00D9FF;
            color: #ffffff;
        }

        .btn-danger {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.3) 0%, rgba(200, 35, 51, 0.4) 100%);
            border-color: #dc3545;
            color: #ffffff;
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(220, 53, 69, 0.5),
                        0 0 40px rgba(220, 53, 69, 0.3);
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.4) 0%, rgba(200, 35, 51, 0.5) 100%);
            border-color: #c82333;
            color: #ffffff;
        }

        /* ============================================
           MENSAJE NO RESULTADOS
        ============================================ */
        .no-results {
            background: rgba(64, 224, 255, 0.1);
            color: #40E0FF;
            font-weight: 600;
            font-size: 16px;
            padding: 30px;
            text-align: center;
            border: 2px dashed #40E0FF;
            border-radius: 15px;
        }

        .no-results i {
            font-size: 32px;
            margin-right: 10px;
            animation: searchPulse 2s ease-in-out infinite;
        }

        @keyframes searchPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }

        /* ============================================
           ANIMACIONES GLOBALES
        ============================================ */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ============================================
           SCROLLBAR CYBERPUNK
        ============================================ */
        ::-webkit-scrollbar {
            width: 12px;
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

        /* ============================================
           RESPONSIVE
        ============================================ */
        @media (max-width: 768px) {
            .main-container {
                margin: 10px;
                max-width: 98%;
            }

            .header-section {
                padding: 20px;
            }

            .header-section h2 {
                font-size: 22px;
                letter-spacing: 1.5px;
            }

            .header-section i {
                font-size: 24px;
            }

            .action-buttons {
                padding: 20px;
            }

            .btn-nuevo-modern {
                width: 100%;
                min-width: unset;
                padding: 15px 30px;
            }

            .search-input {
                max-width: 100%;
            }

            .table-container {
                padding: 15px;
                overflow-x: auto;
            }

            .modal-body {
                padding: 20px;
            }
        }

        @media (max-width: 576px) {
            .btn-action {
                padding: 8px 12px;
                font-size: 13px;
            }

            .badge {
                padding: 6px 12px;
                font-size: 11px;
            }
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
    </style>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {
            // ======= BÚSQUEDA EN TIEMPO REAL =======
            $("#search").keyup(function () {
                var searchTerm = $(this).val().toLowerCase();
                var visibleCount = 0;

                $("#mytable tbody tr").not('.no-results').each(function() {
                    var rowText = $(this).text().toLowerCase();
                    if (rowText.indexOf(searchTerm) === -1) {
                        $(this).fadeOut(200);
                    } else {
                        $(this).fadeIn(200);
                        visibleCount++;
                    }
                });

                // Mostrar mensaje de no resultados
                if (visibleCount === 0) {
                    if (!$('.no-results').length) {
                        $("#mytable tbody").append('<tr class="no-results"><td colspan="4" class="text-center"><i class="fas fa-search"></i> No se encontraron resultados</td></tr>');
                    }
                } else {
                    $('.no-results').remove();
                }
            });

            // ======= VALIDACIÓN DEL FORMULARIO =======
            $('#exampleModal form').submit(function(e) {
                var diag = $('#descripcion').val().trim();
                var cie10 = $('#id_cie10').val().trim();

                if (diag === '' || cie10 === '') {
                    e.preventDefault();
                    alert('⚠️ Por favor, complete todos los campos obligatorios.');
                    return false;
                }

                if (cie10.length < 3) {
                    e.preventDefault();
                    alert('⚠️ El código CIE-10 debe tener al menos 3 caracteres.');
                    return false;
                }

                // Animación de envío
                $(this).find('button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
            });

            // ======= LIMPIAR MODAL AL CERRAR =======
            $('#exampleModal').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
                $(this).find('button[type="submit"]').html('<i class="fas fa-save"></i> Guardar');
            });

            // ======= EFECTOS HOVER MEJORADOS =======
            $('.btn-action').hover(
                function() {
                    $(this).addClass('shadow-lg');
                },
                function() {
                    $(this).removeClass('shadow-lg');
                }
            );

            // ======= TOOLTIP PARA BADGES =======
            $('.badge').each(function() {
                $(this).attr('title', 'Código: ' + $(this).text());
            });

            // ======= ANIMACIÓN DE ENTRADA SECUENCIAL =======
            $('tbody tr').each(function(index) {
                $(this).css('animation-delay', (index * 0.05) + 's');
            });

            // ======= CONTADOR DE REGISTROS =======
            var totalRecords = $("#mytable tbody tr").not('.no-results').length;
            console.log('📊 Total de diagnósticos cargados: ' + totalRecords);
        });
    </script>
</head>

<body>

<div class="main-container">
    <!-- Header Cyberpunk -->
    <div class="header-section">
        <h2>
            <i class="fas fa-diagnoses"></i> CATÁLOGO DE DIAGNÓSTICOS
        </h2>
    </div>

    <!-- Área de botones -->
    <div class="action-buttons">
        <button type="button" class="btn-nuevo-modern" data-toggle="modal" data-target="#exampleModal">
            <i class="fas fa-plus-circle"></i>
            <span>Nuevo Diagnóstico</span>
        </button>
    </div>

    <!-- Sección de búsqueda -->
    <div class="search-section">
        <input type="text" class="search-input" id="search" placeholder="🔍 Buscar diagnóstico o código CIE-10...">
    </div>

    <!-- Tabla de diagnósticos -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="table" id="mytable">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> ID</th>
                        <th><i class="fas fa-stethoscope"></i> Diagnóstico</th>
                        <th><i class="fas fa-code"></i> CIE-10</th>
                        <th><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $resultado2 = $conexion->query("SELECT * FROM cat_diag ORDER BY id_diag") or die($conexion->error);
                    $no = 1;
                    while ($row = $resultado2->fetch_assoc()) {
                        $eid = $row['id_diag'];
                        echo '<tr>';
                        echo '<td><span class="badge badge-primary">' . $no . '</span></td>';
                        echo '<td><strong>' . htmlspecialchars($row['diagnostico']) . '</strong></td>';
                        echo '<td><span class="badge badge-info">' . htmlspecialchars($row['id_cie10']) . '</span></td>';
                        echo '<td>';
                        echo '<a href="edit_diag.php?id=' . $row['id_diag'] . '" title="Editar diagnóstico" class="btn btn-warning btn-action">';
                        echo '<i class="fas fa-edit"></i> Editar';
                        echo '</a>';
                        echo '</td>';
                        echo '</tr>';
                        $no++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Nuevo Diagnóstico -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="insertar_diag.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <i class="fas fa-plus-circle"></i> Nuevo Diagnóstico
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descripcion">
                                    <i class="fas fa-stethoscope"></i> Diagnóstico:
                                </label>
                                <input type="text"
                                       name="diag"
                                       id="descripcion"
                                       class="form-control"
                                       placeholder="Ingrese el nombre del diagnóstico"
                                       required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="id_cie10">
                                    <i class="fas fa-code"></i> Código CIE-10:
                                </label>
                                <input type="text"
                                       name="id_cie10"
                                       id="id_cie10"
                                       class="form-control"
                                       maxlength="20"
                                       placeholder="Ingrese el código CIE-10 (ej: A00.0)"
                                       required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<footer class="main-footer" style="background: linear-gradient(135deg, #0f3460 0%, #16213e 100%); border-top: 2px solid #40E0FF; color: #ffffff; padding: 20px; text-align: center; margin-top: 30px;">
    <?php include("../../template/footer.php"); ?>
</footer>

</body>
</html>
