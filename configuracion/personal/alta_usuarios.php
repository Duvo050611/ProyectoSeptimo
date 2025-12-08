<?php
session_start();
include "../header_configuracion.php";
$resultado=$conexion->query("select * from reg_usuarios") or die($conexion->error);

$usuario=$_SESSION['login'];
$rol=$usuario['id_rol'];
$id_usu=$usuario['id_usua'];
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Lista de Personal - INEO Metepec</title>
    <link rel="icon" href="../imagenes/SIF.PNG">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
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
            padding: 0;
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
            overflow: hidden;
            max-width: 95%;
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.8s ease-out;
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
           HEADER
        ============================================ */
        .thead {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            border-bottom: 2px solid #40E0FF !important;
            color: #ffffff !important;
            font-size: 28px !important;
            padding: 30px !important;
            text-align: center !important;
            margin: 0 !important;
            position: relative;
            overflow: hidden;
        }

        .thead::before {
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

        .thead strong {
            position: relative;
            z-index: 1;
            letter-spacing: 3px;
            text-shadow: 0 0 20px rgba(64, 224, 255, 0.8),
                         0 0 30px rgba(64, 224, 255, 0.5);
        }

        /* ============================================
           ÁREA DE BOTONES
        ============================================ */
        .action-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 25px 30px;
            background: linear-gradient(135deg, rgba(15, 52, 96, 0.8) 0%, rgba(22, 33, 62, 0.8) 100%);
            border-bottom: 2px solid rgba(64, 224, 255, 0.3);
            flex-wrap: wrap;
            gap: 15px;
            position: relative;
            z-index: 1;
        }

        /* Botón Excel Cyberpunk */
        .btn-excel-modern {
            background: linear-gradient(135deg, #00f525 0%, #00c41d 100%);
            border: 2px solid #00f525;
            color: #0a0a0a;
            padding: 14px 28px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 6px 20px rgba(0, 245, 37, 0.3),
                        inset 0 0 15px rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .btn-excel-modern::before {
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
        }

        .btn-excel-modern:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-excel-modern:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 12px 35px rgba(0, 245, 37, 0.5),
                        inset 0 0 25px rgba(255, 255, 255, 0.2),
                        0 0 40px rgba(0, 245, 37, 0.4);
            color: #0a0a0a;
            text-decoration: none;
        }

        .btn-excel-modern i {
            font-size: 20px;
            position: relative;
            z-index: 1;
        }

        .btn-excel-modern span {
            position: relative;
            z-index: 1;
        }

        /* Botón Nuevo Usuario Cyberpunk */
        .btn-nuevo-modern {
            background: linear-gradient(135deg, rgba(64, 224, 255, 0.2) 0%, rgba(0, 217, 255, 0.3) 100%);
            border: 2px solid #40E0FF;
            color: #ffffff;
            padding: 16px 32px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            align-items: center;
            gap: 12px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            box-shadow: 0 8px 25px rgba(64, 224, 255, 0.3),
                        inset 0 0 20px rgba(64, 224, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .btn-nuevo-modern::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: -100%;
            background: linear-gradient(90deg, transparent, rgba(64, 224, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-nuevo-modern:hover::after {
            left: 100%;
        }

        .btn-nuevo-modern:hover {
            transform: translateY(-3px) scale(1.05);
            border-color: #00D9FF;
            background: linear-gradient(135deg, rgba(64, 224, 255, 0.3) 0%, rgba(0, 217, 255, 0.4) 100%);
            box-shadow: 0 15px 40px rgba(64, 224, 255, 0.5),
                        inset 0 0 30px rgba(64, 224, 255, 0.2),
                        0 0 50px rgba(64, 224, 255, 0.3);
            color: #ffffff;
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
           CAMPO DE BÚSQUEDA
        ============================================ */
        .search-container {
            padding: 25px 30px;
            background: linear-gradient(135deg, rgba(22, 33, 62, 0.5) 0%, rgba(15, 52, 96, 0.5) 100%);
            border-bottom: 2px solid rgba(64, 224, 255, 0.2);
            position: relative;
            z-index: 1;
        }

        .search-input {
            border: 2px solid #40E0FF;
            border-radius: 25px;
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
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
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

        .table-modern {
            background: linear-gradient(135deg, rgba(22, 33, 62, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5),
                        0 0 20px rgba(64, 224, 255, 0.2);
            border: 2px solid #40E0FF;
        }

        .table-modern thead th {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
            color: #40E0FF;
            font-weight: 700;
            padding: 20px 15px;
            border: none;
            text-align: center;
            font-size: 14px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            text-shadow: 0 0 15px rgba(64, 224, 255, 0.5);
            border-bottom: 2px solid #40E0FF;
        }

        .table-modern tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(64, 224, 255, 0.1);
            background: rgba(22, 33, 62, 0.3);
        }

        .table-modern tbody tr:hover {
            background: linear-gradient(135deg, rgba(64, 224, 255, 0.15) 0%, rgba(0, 217, 255, 0.1) 100%);
            transform: translateX(5px);
            box-shadow: 0 4px 20px rgba(64, 224, 255, 0.3),
                        inset 0 0 15px rgba(64, 224, 255, 0.1);
        }

        .table-modern tbody td {
            padding: 18px 15px;
            vertical-align: middle;
            border: none;
            font-size: 14px;
            color: #ffffff;
            text-align: center;
        }

        .table-modern tbody td strong {
            color: #40E0FF;
            text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
        }

        .table-modern tbody td span[style*="font-weight: 600"] {
            color: #40E0FF !important;
            text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
            font-weight: 700 !important;
        }

        /* ============================================
           BOTONES DE ACCIÓN CYBERPUNK
        ============================================ */
        .btn-action-modern {
            border: 2px solid transparent;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin: 3px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-action-modern::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            top: -100%;
            left: 0;
            transition: top 0.3s;
        }

        .btn-action-modern:hover::before {
            top: 0;
        }

        .btn-edit-modern {
            background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%);
            border-color: #ffc107;
            color: #0a0a0a;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.4);
        }

        .btn-edit-modern:hover {
            transform: translateY(-3px) scale(1.1);
            box-shadow: 0 8px 25px rgba(255, 193, 7, 0.6),
                        0 0 30px rgba(255, 193, 7, 0.4);
            color: #0a0a0a;
            text-decoration: none;
        }

        .btn-view-modern {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            border-color: #17a2b8;
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(23, 162, 184, 0.4);
        }

        .btn-view-modern:hover {
            transform: translateY(-3px) scale(1.1);
            box-shadow: 0 8px 25px rgba(23, 162, 184, 0.6),
                        0 0 30px rgba(23, 162, 184, 0.4);
            color: #ffffff;
            text-decoration: none;
        }

        .btn-status-active {
            background: linear-gradient(135deg, #00f525 0%, #00c41d 100%);
            border-color: #00f525;
            color: #0a0a0a;
            box-shadow: 0 4px 15px rgba(0, 245, 37, 0.4);
        }

        .btn-status-active:hover {
            transform: translateY(-3px) scale(1.1) rotate(180deg);
            box-shadow: 0 8px 25px rgba(0, 245, 37, 0.6),
                        0 0 30px rgba(0, 245, 37, 0.4);
            color: #0a0a0a;
            text-decoration: none;
        }

        .btn-status-inactive {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border-color: #dc3545;
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
        }

        .btn-status-inactive:hover {
            transform: translateY(-3px) scale(1.1) rotate(180deg);
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.6),
                        0 0 30px rgba(220, 53, 69, 0.4);
            color: #ffffff;
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
            border-radius: 20px 20px 0 0;
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
            position: relative;
            z-index: 1;
        }

        .modal-title i {
            margin-right: 12px;
            font-size: 24px;
        }

        .modal-body {
            background: transparent;
            padding: 35px;
            position: relative;
            z-index: 1;
        }

        .modal-body h6 {
            color: #40E0FF;
            font-weight: 700;
            font-size: 18px;
            border-bottom: 2px solid #40E0FF;
            padding-bottom: 12px;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            text-shadow: 0 0 15px rgba(64, 224, 255, 0.5);
        }

        .modal-body h6 i {
            margin-right: 12px;
            color: #40E0FF;
            font-size: 20px;
        }

        /* ============================================
           FORMULARIOS CYBERPUNK
        ============================================ */
        .form-group {
            margin-bottom: 28px;
            padding: 10px;
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
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .form-control {
            border: 2px solid #40E0FF;
            border-radius: 12px;
            padding: 14px 18px;
            transition: all 0.3s ease;
            font-size: 15px;
            font-weight: 500;
            background: rgba(10, 10, 10, 0.5);
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(64, 224, 255, 0.1),
                        inset 0 0 15px rgba(64, 224, 255, 0.05);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
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

        /* Select Cyberpunk */
        .select-custom {
            border: 3px solid #40E0FF !important;
            border-radius: 12px !important;
            padding: 16px 50px 16px 20px !important;
            font-size: 15px !important;
            font-weight: 700 !important;
            background: rgba(10, 10, 10, 0.6) !important;
            color: #40E0FF !important;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(64, 224, 255, 0.2),
                        inset 0 0 15px rgba(64, 224, 255, 0.05) !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2340E0FF' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right 15px center !important;
            background-size: 18px !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
        }

        .select-custom:hover {
            border-color: #00D9FF !important;
            background: rgba(10, 10, 10, 0.8) !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(64, 224, 255, 0.3),
                        inset 0 0 20px rgba(64, 224, 255, 0.1) !important;
        }

        .select-custom:focus {
            border-color: #00D9FF !important;
            box-shadow: 0 0 0 0.3rem rgba(64, 224, 255, 0.3),
                        0 8px 30px rgba(64, 224, 255, 0.4),
                        inset 0 0 25px rgba(64, 224, 255, 0.15) !important;
            transform: translateY(-3px) !important;
            background: rgba(10, 10, 10, 0.9) !important;
            outline: none !important;
        }

        .select-custom option {
            padding: 12px;
            font-weight: 600;
            color: #ffffff;
            background: #0f3460;
        }

        /* Input File Cyberpunk */
        .file-upload-container {
            position: relative;
            border: 3px dashed #40E0FF;
            border-radius: 15px;
            padding: 30px 20px;
            background: linear-gradient(135deg, rgba(64, 224, 255, 0.05) 0%, rgba(0, 217, 255, 0.05) 100%);
            text-align: center;
            transition: all 0.4s ease;
            cursor: pointer;
            overflow: hidden;
        }

        .file-upload-container::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(64, 224, 255, 0.1);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.5s, height 0.5s;
        }

        .file-upload-container:hover::before {
            width: 300px;
            height: 300px;
        }

        .file-upload-container:hover {
            border-color: #00D9FF;
            background: linear-gradient(135deg, rgba(64, 224, 255, 0.1) 0%, rgba(0, 217, 255, 0.1) 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(64, 224, 255, 0.3),
                        inset 0 0 20px rgba(64, 224, 255, 0.1);
        }

        .file-upload-container input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer;
            z-index: 2;
        }

        .file-upload-text {
            color: #40E0FF;
            font-weight: 600;
            position: relative;
            z-index: 1;
        }

        .file-upload-text i {
            color: #40E0FF;
            display: block;
            margin-bottom: 12px;
            font-size: 32px;
            text-shadow: 0 0 20px rgba(64, 224, 255, 0.6);
        }

        .file-upload-text p {
            margin: 12px 0 8px 0;
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .file-upload-text small {
            color: rgba(64, 224, 255, 0.7);
            font-size: 13px;
            font-weight: 500;
        }

        /* ============================================
           FOOTER MODAL
        ============================================ */
        .modal-footer {
            padding: 25px 30px;
            border-top: 2px solid #40E0FF;
            background: linear-gradient(135deg, rgba(15, 52, 96, 0.5) 0%, rgba(22, 33, 62, 0.5) 100%);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, rgba(64, 224, 255, 0.3) 0%, rgba(0, 217, 255, 0.4) 100%);
            border: 2px solid #40E0FF;
            color: #ffffff;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(64, 224, 255, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(64, 224, 255, 0.5),
                        0 0 40px rgba(64, 224, 255, 0.3);
            background: linear-gradient(135deg, rgba(64, 224, 255, 0.4) 0%, rgba(0, 217, 255, 0.5) 100%);
            border-color: #00D9FF;
        }

        .btn-secondary-custom {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.3) 0%, rgba(200, 35, 51, 0.4) 100%);
            border: 2px solid #dc3545;
            color: #ffffff;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.3);
        }

        .btn-secondary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(220, 53, 69, 0.5),
                        0 0 40px rgba(220, 53, 69, 0.3);
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.4) 0%, rgba(200, 35, 51, 0.5) 100%);
            border-color: #c82333;
        }

        /* ============================================
           ANIMACIONES
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
            .action-buttons {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-excel-modern,
            .btn-nuevo-modern {
                justify-content: center;
                width: 100%;
            }

            .search-input {
                max-width: 100%;
            }

            .table-container {
                padding: 15px;
                overflow-x: auto;
            }

            .thead {
                font-size: 20px !important;
                padding: 20px !important;
            }

            .thead strong {
                letter-spacing: 1.5px;
            }
        }

        @media (max-width: 576px) {
            .modal-body {
                padding: 20px;
            }

            .btn-action-modern {
                padding: 8px 10px;
                font-size: 14px;
            }
        }

        /* ============================================
           EFECTOS ADICIONALES
        ============================================ */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>

    <!-- Scripts de jQuery y Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <script>
        // Búsqueda en tiempo real
        $(document).ready(function(){
            $("#search").keyup(function(){
                _this = this;
                $.each($("#mytable tbody tr"), function() {
                    if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
                        $(this).hide();
                    else
                        $(this).show();
                });
            });

            // Asegurar que los select-custom se muestren correctamente
            $('.select-custom').each(function() {
                $(this).css({
                    'display': 'block',
                    'visibility': 'visible',
                    'opacity': '1',
                    'z-index': '999'
                });
            });
        });
    </script>
</head>

<body>
<div class="main-container">
    <!-- Header -->
    <div class="thead">
        <strong>🔐 LISTA DE PERSONAL</strong>
    </div>

    <!-- Área de botones -->
    <div class="action-buttons">
        <div>
            <?php if($id_usu == 1){?>
                <a href="excelpersonal.php" class="btn-excel-modern">
                    <i class="fas fa-file-excel"></i>
                    <span>Exportar a Excel</span>
                </a>
            <?php }?>
        </div>
        <div>
            <button type="button" class="btn-nuevo-modern" data-toggle="modal" data-target="#exampleModal">
                <i class="fas fa-plus"></i>
                <span>Nuevo usuario</span>
            </button>
        </div>
    </div>

    <!-- Campo de búsqueda -->
    <div class="search-container">
        <input type="text" class="search-input" id="search" placeholder="🔍 Buscar personal...">
    </div>

    <!-- Tabla -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-modern" id="mytable">
                <thead>
                    <tr>
                        <th>✏️ Editar</th>
                        <th>👁️ Ver datos</th>
                        <th>🆔 Id</th>
                        <th>👤 Usuario</th>
                        <th>📝 Primer apellido</th>
                        <th>📝 Segundo apellido</th>
                        <th>🏷️ Nombre(s)</th>
                        <th>🎓 Cédula profesional</th>
                        <th>💼 Función</th>
                        <th>⚡ Activo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $resultado2=$conexion->query("SELECT id_usua, curp_u, nombre,papell,sapell,fecnac,mat,cedp,cargp,tel,email,u_activo,usuario FROM reg_usuarios") or die($conexion->error);

                    $no = 1;
                    while ($row = $resultado2->fetch_assoc()) {
                        $eid = $row['id_usua'];
                        echo '<tr>'
                        . '<td><a href="editar_usuario.php?id_usua=' . $row['id_usua'] . '" title="Editar datos" class="btn-action-modern btn-edit-modern"><i class="fas fa-edit"></i></a></td>'
                        . '<td><a href="mostrar_usuario.php?id_usua=' . $row['id_usua'] . '" title="Mostrar datos" class="btn-action-modern btn-view-modern"><i class="fas fa-eye"></i></a></td>'
                        . '<td><span style="font-weight: 600; color: #40E0FF;">' . $row['id_usua'] . '</span></td>'
                        . '<td><strong>'. $row['usuario'] . '</strong></td>'
                        . '<td>' . $row['papell']. '</td>'
                        . '<td>' . $row['sapell']. '</td>'
                        . '<td>' . $row['nombre'] . '</td>'
                        . '<td><span style="color: #17a2b8; font-weight: 500;">' . $row['cedp'] . '</span></td>'
                        . '<td><span style="color: #00f525; font-weight: 500;">' . $row['cargp'] . '</span></td>';
                        ?>
                        <td>
                            <?php
                            if ((strpos($row['u_activo'], 'NO') !== false)) {
                                echo '<a class="btn-action-modern btn-status-inactive" href="activo.php?q=estatus&eid=' . $eid . '&est=' . $row['u_activo'] . '" title="Activar usuario"><i class="fas fa-power-off"></i></a>';
                            } else {
                                echo '<a class="btn-action-modern btn-status-active" href="activo.php?q=estatus&eid=' . $eid . '&est=' . $row['u_activo'] . '" title="Desactivar usuario"><i class="fas fa-power-off"></i></a>';
                            }
                            ?>
                        </td>
                        <?php
                        echo '</tr>';
                        $no++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Nuevo Usuario -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form action="insertar_usuario.php" method="POST" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fas fa-user-plus"></i> NUEVO USUARIO
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #40E0FF; opacity: 1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Columna 1: Datos personales -->
                    <div class="col-md-6">
                        <h6>
                            <i class="fas fa-user"></i> Datos Personales
                        </h6>

                        <div class="form-group">
                            <label for="curp_u"><i class="fas fa-id-card"></i> CURP:</label>
                            <input type="text" size="18" name="curp_u" id="curp_u" class="form-control" style="text-transform:uppercase;" value="" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                        </div>

                        <div class="form-group">
                            <label for="papell"><i class="fas fa-user-tag"></i> Primer Apellido:</label>
                            <input type="text" name="papell" id="papell" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="sapell"><i class="fas fa-user-tag"></i> Segundo Apellido:</label>
                            <input type="text" name="sapell" id="sapell" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="nombre"><i class="fas fa-signature"></i> Nombre(s):</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="fecha"><i class="fas fa-calendar-alt"></i> Fecha de nacimiento:</label>
                            <input type="date" name="fecnac" id="fecha" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="tel"><i class="fas fa-phone"></i> Teléfono:</label>
                            <input type="number" name="tel" id="tel" class="form-control" required>
                        </div>
                    </div>

                    <!-- Columna 2: Datos profesionales -->
                    <div class="col-md-6">
                        <h6>
                            <i class="fas fa-briefcase"></i> Datos Profesionales
                        </h6>

                        <div class="form-group">
                            <label for="cedp"><i class="fas fa-certificate"></i> Cédula profesional:</label>
                            <input type="text" name="cedp" id="cedp" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="cargp"><i class="fas fa-stethoscope"></i> Especialidad:</label>
                            <input type="text" name="cargp" id="cargp" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="email"><i class="fas fa-envelope"></i> E-mail:</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="pre"><i class="fas fa-user-md"></i> Prefijo:</label>
                            <select name="pre" class="form-control select-custom" required>
                                <option value="">🔹 Seleccionar Prefijo</option>
                                <option value="Dra">👩‍⚕️ Dra</option>
                                <option value="Dr">👨‍⚕️ Dr</option>
                                <option value="Lic">🎓 Lic</option>
                                <option value="L.N">👶 L.N.</option>
                                <option value="Psic">🧠 Psic</option>
                                <option value="Enf">💉 Enf</option>
                                <option value="I.Q">🔬 I.Q</option>
                                <option value="Rad">📡 Rad</option>
                                <option value="C">📋 C.</option>
                                <option value="Ing">⚙️ Ing.</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="usuario"><i class="fas fa-user-circle"></i> Usuario:</label>
                            <input type="text" name="usuario" id="usuario" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="pass"><i class="fas fa-lock"></i> Contraseña:</label>
                            <input type="password" name="pass" id="pass" class="form-control" required>
                        </div>
                    </div>
                </div>

                <!-- Sección de archivos y rol -->
                <div class="row">
                    <div class="col-md-6">
                        <h6>
                            <i class="fas fa-file-upload"></i> Archivos
                        </h6>

                        <div class="form-group">
                            <label for="img_perfil"><i class="fas fa-image"></i> Imagen del perfil:</label>
                            <div class="file-upload-container">
                                <input type="file" name="img_perfil" id="img_perfil" class="form-control" required accept="image/*">
                                <div class="file-upload-text">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Seleccionar imagen del perfil</p>
                                    <small>Formatos: JPG, PNG, GIF (Max: 2MB)</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="firma"><i class="fas fa-signature"></i> Firma digitalizada:</label>
                            <div class="file-upload-container">
                                <input type="file" name="firma" id="firma" class="form-control" required accept="image/*">
                                <div class="file-upload-text">
                                    <i class="fas fa-pen-fancy"></i>
                                    <p>Seleccionar firma digitalizada</p>
                                    <small>Formatos: JPG, PNG (Max: 1MB)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6>
                            <i class="fas fa-shield-alt"></i> Permisos
                        </h6>

                        <div class="form-group">
                            <label for="id_rol"><i class="fas fa-user-shield"></i> Rol de acceso:</label>
                            <?php
                            if($rol == 1 ){
                                $resultado1 = $conexion->query("SELECT * FROM rol where id_rol!=5") or die($conexion->error); ?>
                                <select name="id_rol" class="form-control select-custom" required>
                                    <option value="">🔐 Seleccionar Rol de Acceso</option>
                                    <?php foreach ($resultado1 as $opciones):?>
                                        <option value="<?php echo $opciones['id_rol']?>">👤 <?php echo $opciones['rol']?></option>
                                    <?php endforeach?>
                                </select>
                            <?php }elseif($rol == 5){
                                $resultado1 = $conexion->query("SELECT * FROM rol") or die($conexion->error); ?>
                                <select name="id_rol" class="form-control select-custom" required>
                                    <option value="">🔐 Seleccionar Rol de Acceso</option>
                                    <?php foreach ($resultado1 as $opciones):?>
                                        <option value="<?php echo $opciones['id_rol']?>">👤 <?php echo $opciones['rol']?></option>
                                    <?php endforeach?>
                                </select>
                            <?php }elseif($rol == 12){
                                $resultado1 = $conexion->query("SELECT * FROM rol where id_rol=2 or id_rol=3 or id_rol=12") or die($conexion->error); ?>
                                <select name="id_rol" class="form-control select-custom" required>
                                    <option value="">🔐 Seleccionar Rol de Acceso</option>
                                    <?php foreach ($resultado1 as $opciones):?>
                                        <option value="<?php echo $opciones['id_rol']?>">👤 <?php echo $opciones['rol']?></option>
                                    <?php endforeach?>
                                </select>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn-primary-custom">
                    <i class="fas fa-save"></i> Guardar Usuario
                </button>
                <button type="button" class="btn-secondary-custom" data-dismiss="modal">
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
