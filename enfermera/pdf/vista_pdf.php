<?php
session_start();
include "../../conexionbd.php";
$conexion = ConexionBD::getInstancia()->getConexion();
include "../header_enfermera.php";

// Datos de sesión
$usuario = $_SESSION['login'];
$id_atencion = $_SESSION['pac'];

// Traer datos del paciente
$queryPaciente = "
    SELECT P.Id_exp, P.nom_pac, P.papell, P.sapell, DI.id_atencion
    FROM paciente P
    INNER JOIN dat_ingreso DI ON P.Id_exp = DI.Id_exp
    WHERE DI.id_atencion = $id_atencion
";
$paciente = $conexion->query($queryPaciente)->fetch_assoc();

if (!$paciente) {
    echo "<div class='alert alert-danger'>No se encontró información del paciente.</div>";
    exit;
}

$rolPermitido = ($usuario['id_rol'] == 5 || $usuario['id_rol'] == 12);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INEO Metepec - Imprimir Documentos</title>
    <link rel="icon" href="../../imagenes/SIF.PNG">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <link href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet"
          integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #0a0a0a 100%) !important;
            font-family: 'Roboto', sans-serif !important;
            min-height: 100vh;
            color: #ffffff !important;
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

        .wrapper {
            position: relative;
            z-index: 1;
        }

        /* Content wrapper */
        .content-wrapper {
            background: transparent !important;
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }

        section.content {
            background: transparent !important;
            padding: 20px;
        }

        /* Contenedor principal */
        .container {
            position: relative;
            z-index: 1;
            max-width: 1200px;
        }

        /* Botón regresar */
        .btn-regresar {
            background: linear-gradient(135deg, #c62828 0%, #b71c1c 100%) !important;
            border: 2px solid #ef5350 !important;
            color: #ffffff !important;
            padding: 12px 30px !important;
            border-radius: 25px !important;
            font-weight: 600 !important;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 15px rgba(198, 40, 40, 0.3);
            text-decoration: none !important;
            display: inline-block;
        }

        .btn-regresar:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 8px 25px rgba(239, 83, 80, 0.5) !important;
            background: linear-gradient(135deg, #b71c1c 0%, #c62828 100%) !important;
            border-color: #ff6659 !important;
        }

        /* Título principal */
        .titulo-seccion {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            color: #ffffff !important;
            padding: 25px 30px !important;
            font-size: 24px !important;
            font-weight: 700 !important;
            border-radius: 15px !important;
            margin: 20px 0 !important;
            border: 2px solid #40E0FF !important;
            box-shadow: 0 8px 30px rgba(64, 224, 255, 0.3);
            text-align: center;
            letter-spacing: 1.5px;
            text-shadow: 0 0 20px rgba(64, 224, 255, 0.5);
            position: relative;
            overflow: hidden;
        }

        .titulo-seccion::before {
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

        /* Información del paciente */
        .info-paciente {
            background: linear-gradient(135deg, rgba(15, 52, 96, 0.6), rgba(22, 33, 62, 0.6));
            padding: 20px 30px;
            border-radius: 12px;
            border: 2px solid rgba(64, 224, 255, 0.3);
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .info-paciente p {
            color: #ffffff !important;
            font-size: 1.1rem !important;
            margin: 8px 0 !important;
            letter-spacing: 0.5px;
        }

        .info-paciente strong {
            color: #40E0FF !important;
            text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
        }

        /* Tarjeta de documentos */
        .doc-card {
            background: linear-gradient(135deg, rgba(15, 52, 96, 0.8), rgba(22, 33, 62, 0.8));
            padding: 30px;
            border-radius: 15px;
            border: 2px solid rgba(64, 224, 255, 0.4);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.5);
            margin: 30px 0;
            backdrop-filter: blur(10px);
        }

        .doc-card h4 {
            color: #40E0FF !important;
            font-weight: 700 !important;
            margin-bottom: 25px !important;
            font-size: 1.8rem !important;
            text-shadow: 0 0 15px rgba(64, 224, 255, 0.5);
            letter-spacing: 1px;
            border-bottom: 2px solid rgba(64, 224, 255, 0.3);
            padding-bottom: 15px;
        }

        /* Items de documentos */
        .doc-item {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            margin: 12px 0;
            background: linear-gradient(135deg, rgba(26, 26, 46, 0.6), rgba(22, 33, 62, 0.6));
            border-radius: 10px;
            border: 1px solid rgba(64, 224, 255, 0.2);
            transition: all 0.3s ease;
        }

        .doc-item:hover {
            transform: translateX(8px);
            background: linear-gradient(135deg, rgba(15, 52, 96, 0.7), rgba(26, 26, 46, 0.7));
            border-color: #40E0FF;
            box-shadow: 0 4px 15px rgba(64, 224, 255, 0.3);
        }

        .doc-item p {
            color: #ffffff !important;
            font-size: 1.1rem !important;
            margin: 0 !important;
            flex: 1;
            padding-left: 15px;
        }

        /* Botones PDF */
        .btn-pdf {
            background: linear-gradient(135deg, #c62828 0%, #b71c1c 100%) !important;
            border: 2px solid #ef5350 !important;
            color: #ffffff !important;
            padding: 10px 18px !important;
            border-radius: 8px !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 12px rgba(198, 40, 40, 0.3);
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 50px;
        }

        .btn-pdf:hover {
            transform: scale(1.15) !important;
            box-shadow: 0 6px 20px rgba(239, 83, 80, 0.5) !important;
            background: linear-gradient(135deg, #d32f2f 0%, #c62828 100%) !important;
            border-color: #ff6659 !important;
        }

        .btn-pdf .fa-file-pdf-o {
            font-size: 28px;
        }

        /* Scrollbar personalizado */
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

        /* Responsive */
        @media (max-width: 768px) {
            .titulo-seccion {
                font-size: 18px !important;
                padding: 20px 15px !important;
                letter-spacing: 1px;
            }

            .doc-card {
                padding: 20px 15px;
            }

            .doc-card h4 {
                font-size: 1.4rem !important;
            }

            .doc-item {
                flex-direction: column;
                align-items: flex-start;
                padding: 15px;
            }

            .doc-item p {
                padding-left: 0;
                margin-top: 10px !important;
            }

            .btn-pdf {
                margin-bottom: 10px;
            }

            .info-paciente {
                padding: 15px 20px;
            }

            .info-paciente p {
                font-size: 1rem !important;
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

        .doc-item {
            animation: fadeInUp 0.5s ease-out;
            animation-fill-mode: both;
        }

        .doc-item:nth-child(1) { animation-delay: 0.1s; }
        .doc-item:nth-child(2) { animation-delay: 0.2s; }
        .doc-item:nth-child(3) { animation-delay: 0.3s; }
        .doc-item:nth-child(4) { animation-delay: 0.4s; }
        .doc-item:nth-child(5) { animation-delay: 0.5s; }
        .doc-item:nth-child(6) { animation-delay: 0.6s; }

        /* Sidebar (mantener consistencia) */
        .main-sidebar {
            background: linear-gradient(180deg, #16213e 0%, #0f3460 100%) !important;
            border-right: 2px solid #40E0FF !important;
            box-shadow: 4px 0 20px rgba(64, 224, 255, 0.15);
        }

        .sidebar-menu > li > a {
            color: #ffffff !important;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .sidebar-menu > li > a:hover,
        .sidebar-menu > li.active > a {
            background: rgba(64, 224, 255, 0.1) !important;
            border-left: 3px solid #40E0FF !important;
            color: #40E0FF !important;
        }

        .treeview-menu {
            background: rgba(15, 52, 96, 0.5) !important;
            border-left: 2px solid #40E0FF;
        }

        .treeview-menu > li > a {
            color: #ffffff !important;
            padding: 10px 20px !important;
        }

        .treeview-menu > li > a:hover {
            background: rgba(64, 224, 255, 0.1) !important;
            color: #40E0FF !important;
        }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">

<section class="content container-fluid">
    <div class="container mt-4">

        <!-- Botón regresar -->
        <div class="text-center mb-4">
            <a href="../../template/menu_enfermera.php" class="btn-regresar">
                <i class="fa fa-arrow-left"></i> REGRESAR
            </a>
        </div>

        <!-- Título principal -->
        <div class="titulo-seccion">
            <strong><i class="fa fa-file-pdf-o"></i> IMPRESIÓN DE DOCUMENTOS (NOTAS DE ENFERMERÍA)</strong>
        </div>

        <!-- Información del paciente -->
        <div class="info-paciente">
            <p><strong><i class="fa fa-folder"></i> EXPEDIENTE:</strong> <?= htmlspecialchars($paciente['Id_exp']); ?></p>
            <p><strong><i class="fa fa-user"></i> NOMBRE:</strong> <?= htmlspecialchars($paciente['nom_pac']." ".$paciente['papell']." ".$paciente['sapell']); ?></p>
        </div>

        <!-- Caja de documentos -->
        <div class="doc-card">
            <h4><i class="fa fa-notes-medical"></i> 15. Enfermería</h4>

            <div class="doc-item">
                <a class="btn-pdf" href="../vistas_doc/vista_regclin.php">
                    <span class="fa fa-file-pdf-o"></span>
                </a>
                <p><strong>15.00</strong> Registro clínico</p>
            </div>

            <div class="doc-item">
                <a class="btn-pdf"
                   href="../registro_quirurgico/pdf_cirugia_segura.php?id=<?= $paciente['id_atencion'] ?>&id_exp=<?= $paciente['Id_exp'] ?>"
                   target="_blank">
                    <span class="fa fa-file-pdf-o"></span>
                </a>
                <p><strong>15.01</strong> Tratamiento</p>
            </div>

            <div class="doc-item">
                <a class="btn-pdf" href="../vistas_doc/vista_quir_area.php">
                    <span class="fa fa-file-pdf-o"></span>
                </a>
                <p><strong>15.02</strong> Cirugía segura</p>
            </div>

            <div class="doc-item">
                <a class="btn-pdf" href="../vistas_doc/vista_pediatria.php">
                    <span class="fa fa-file-pdf-o"></span>
                </a>
                <p><strong>15.03</strong> Quirófano</p>
            </div>

            <div class="doc-item">
                <a class="btn-pdf" href="../vistas_doc/vista_transpdf.php">
                    <span class="fa fa-file-pdf-o"></span>
                </a>
                <p><strong>15.06</strong> Pediátrico / Neonatal</p>
            </div>

            <div class="doc-item">
                <a class="btn-pdf"
                   href="../registro_quirurgico/pdf_quirpiezas.php?id_atencion=<?= $paciente['id_atencion'] ?>&id_exp=<?= $paciente['Id_exp'] ?>"
                   target="_blank">
                    <span class="fa fa-file-pdf-o"></span>
                </a>
                <p><strong>15.07</strong> Transfusión sanguínea</p>
            </div>

        </div>

    </div>
</section>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="js/select2.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldLv/Pr4nhuBviF5jGqQK/5i2Q5iZ64dxBl+zOZ" crossorigin="anonymous">
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
</script>
<script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

<!-- Script para treeview del sidebar -->
<script>
    $(document).ready(function () {
        $('.treeview > a').on('click', function (e) {
            e.preventDefault();
            let parent = $(this).parent();
            let submenu = parent.find('.treeview-menu').first();

            parent.toggleClass('menu-open');
            submenu.slideToggle(200);
        });

        // Cerrar otros submenús cuando se abre uno nuevo
        $('.sidebar-menu .treeview > a').on('click', function() {
            var clickedMenu = $(this).parent();
            $('.sidebar-menu .treeview').not(clickedMenu).removeClass('menu-open');
            $('.sidebar-menu .treeview').not(clickedMenu).find('.treeview-menu').slideUp(200);
        });
    });
</script>

</body>
</html>