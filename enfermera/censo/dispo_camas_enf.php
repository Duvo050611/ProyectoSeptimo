<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}

require_once '../../conexionbd.php';
$conexion = ConexionBD::getInstancia()->getConexion();
include("../header_enfermera.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INEO Metepec - Disponibilidad de Habitaciones</title>
    <link rel="icon" href="../../imagenes/SIF.PNG">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
          integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

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

        .content-wrapper {
            background: transparent !important;
            min-height: 100vh;
        }

        .container-fluid {
            position: relative;
            z-index: 1;
            padding: 20px;
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
        .titulo-principal {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            color: #ffffff !important;
            padding: 25px 30px !important;
            font-size: 26px !important;
            font-weight: 700 !important;
            border-radius: 15px !important;
            margin: 20px 0 30px 0 !important;
            border: 2px solid #40E0FF !important;
            box-shadow: 0 8px 30px rgba(64, 224, 255, 0.3);
            text-align: center;
            letter-spacing: 2px;
            text-shadow: 0 0 20px rgba(64, 224, 255, 0.5);
            position: relative;
            overflow: hidden;
        }

        .titulo-principal::before {
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

        .titulo-principal h3 {
            margin: 0;
            position: relative;
            z-index: 1;
        }

        /* Estadísticas */
        .stats-card {
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            text-align: center;
            color: white;
            border: 2px solid;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                    45deg,
                    transparent,
                    rgba(255, 255, 255, 0.1),
                    transparent
            );
            transform: rotate(45deg);
            transition: all 0.6s ease;
        }

        .stats-card:hover {
            transform: translateY(-8px) scale(1.03);
        }

        .stats-card:hover::before {
            left: 100%;
        }

        .stats-libres {
            background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
            border-color: #4caf50;
        }

        .stats-ocupadas {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
            border-color: #40E0FF;
        }

        .stats-mantenimiento {
            background: linear-gradient(135deg, #c62828 0%, #b71c1c 100%);
            border-color: #ef5350;
        }

        .stats-proceso {
            background: linear-gradient(135deg, #f57c00 0%, #e65100 100%);
            border-color: #ffa726;
        }

        .stats-number {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .stats-label {
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .stats-label i {
            margin-right: 8px;
        }

        /* Leyenda */
        .leyenda {
            background: linear-gradient(135deg, rgba(15, 52, 96, 0.8), rgba(22, 33, 62, 0.8));
            color: white;
            padding: 20px 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
            margin-bottom: 30px;
            border: 2px solid rgba(64, 224, 255, 0.3);
            backdrop-filter: blur(10px);
        }

        .leyenda strong {
            color: #40E0FF;
            font-size: 18px;
            text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
        }

        .leyenda-item {
            display: inline-block;
            margin-right: 25px;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .leyenda-color {
            display: inline-block;
            width: 24px;
            height: 24px;
            border-radius: 5px;
            margin-right: 10px;
            vertical-align: middle;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        /* Tarjetas de camas */
        .cama-card {
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
            min-height: 160px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
            border: 2px solid;
            position: relative;
            overflow: hidden;
        }

        .cama-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                    45deg,
                    transparent,
                    rgba(255, 255, 255, 0.1),
                    transparent
            );
            transform: rotate(45deg);
            transition: all 0.6s ease;
        }

        .cama-card:hover {
            transform: translateY(-8px) scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.6);
        }

        .cama-card:hover::before {
            left: 100%;
        }

        .cama-libre {
            background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
            border-color: #4caf50;
            color: white;
        }

        .cama-ocupada {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
            border-color: #40E0FF;
            color: white;
        }

        .cama-mantenimiento {
            background: linear-gradient(135deg, #c62828 0%, #b71c1c 100%);
            border-color: #ef5350;
            color: white;
        }

        .cama-proceso {
            background: linear-gradient(135deg, #f57c00 0%, #e65100 100%);
            border-color: #ffa726;
            color: white;
        }

        .cama-icon {
            font-size: 42px;
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
            text-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .cama-numero {
            font-size: 24px;
            font-weight: 700;
            margin: 10px 0;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        .cama-estado {
            font-size: 14px;
            font-weight: 600;
            margin: 8px 0;
            letter-spacing: 0.5px;
            position: relative;
            z-index: 1;
            text-transform: uppercase;
        }

        .cama-paciente {
            font-size: 12px;
            margin-top: 10px;
            font-style: italic;
            position: relative;
            z-index: 1;
            padding-top: 10px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Secciones */
        .seccion-container {
            margin-bottom: 40px;
        }

        .seccion-container h4 {
            color: #40E0FF;
            font-weight: 700;
            font-size: 22px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid rgba(64, 224, 255, 0.3);
            text-shadow: 0 0 15px rgba(64, 224, 255, 0.5);
        }

        .seccion-container h4 i {
            margin-right: 10px;
        }

        /* Info card */
        .info-card {
            background: linear-gradient(135deg, rgba(15, 52, 96, 0.8), rgba(22, 33, 62, 0.8));
            border: 2px solid rgba(64, 224, 255, 0.3);
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
        }

        .info-card .card-body {
            padding: 25px;
        }

        .info-card .card-title {
            color: #40E0FF;
            font-weight: 700;
            font-size: 20px;
            margin-bottom: 20px;
            text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
        }

        .info-card ul {
            list-style: none;
            padding-left: 0;
        }

        .info-card ul li {
            color: #ffffff;
            margin-bottom: 12px;
            padding-left: 25px;
            position: relative;
        }

        .info-card ul li::before {
            content: '▸';
            position: absolute;
            left: 0;
            color: #40E0FF;
            font-size: 18px;
        }

        /* Scrollbar */
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
            .titulo-principal {
                font-size: 20px !important;
                padding: 20px 15px !important;
            }

            .titulo-principal h3 {
                font-size: 18px;
            }

            .stats-number {
                font-size: 36px;
            }

            .stats-label {
                font-size: 13px;
            }

            .cama-card {
                min-height: 140px;
                padding: 15px;
            }

            .cama-icon {
                font-size: 32px;
            }

            .cama-numero {
                font-size: 20px;
            }

            .leyenda-item {
                margin-right: 15px;
                font-size: 13px;
            }

            .seccion-container h4 {
                font-size: 18px;
            }
        }

        /* Animaciones */
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

        .cama-card {
            animation: fadeInUp 0.5s ease-out;
            animation-fill-mode: both;
        }

        /* Sidebar */
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
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="container-fluid">
    <!-- Botón regresar -->
    <div class="mb-4">
        <a href="../censo/vista_habitacion.php" class="btn-regresar">
            <i class="fa fa-arrow-left"></i> Regresar
        </a>
    </div>

    <!-- Título principal -->
    <div class="titulo-principal">
        <h3>
            <i class="fa fa-hospital"></i> DISPONIBILIDAD DE HABITACIONES - HOSPITALIZACIÓN
        </h3>
    </div>

    <?php
    // Contar camas por estado
    $sql_stats = "SELECT 
            SUM(CASE WHEN estatus = 'LIBRE' THEN 1 ELSE 0 END) as libres,
            SUM(CASE WHEN estatus = 'OCUPADA' THEN 1 ELSE 0 END) as ocupadas,
            SUM(CASE WHEN estatus = 'MANTENIMIENTO' THEN 1 ELSE 0 END) as mantenimiento,
            SUM(CASE WHEN estatus LIKE '%PROCESO%' THEN 1 ELSE 0 END) as proceso,
            COUNT(*) as total
            FROM cat_camas WHERE piso = 1";

    $result_stats = $conexion->query($sql_stats);
    $stats = $result_stats->fetch_assoc();
    ?>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card stats-libres">
                <div class="stats-number"><?php echo $stats['libres']; ?></div>
                <div class="stats-label">
                    <i class="fa fa-check-circle"></i> Disponibles
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card stats-ocupadas">
                <div class="stats-number"><?php echo $stats['ocupadas']; ?></div>
                <div class="stats-label">
                    <i class="fa fa-user"></i> Ocupadas
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card stats-proceso">
                <div class="stats-number"><?php echo $stats['proceso']; ?></div>
                <div class="stats-label">
                    <i class="fa fa-clock"></i> Por Liberar
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card stats-mantenimiento">
                <div class="stats-number"><?php echo $stats['mantenimiento']; ?></div>
                <div class="stats-label">
                    <i class="fa fa-tools"></i> Mantenimiento
                </div>
            </div>
        </div>
    </div>

    <!-- Leyenda -->
    <div class="leyenda">
        <strong><i class="fa fa-info-circle"></i> Leyenda:</strong>
        <div class="mt-3">
            <div class="leyenda-item">
                <span class="leyenda-color" style="background: linear-gradient(135deg, #2e7d32, #1b5e20);"></span>
                <span>Disponible</span>
            </div>
            <div class="leyenda-item">
                <span class="leyenda-color" style="background: linear-gradient(135deg, #0f3460, #16213e);"></span>
                <span>Ocupada</span>
            </div>
            <div class="leyenda-item">
                <span class="leyenda-color" style="background: linear-gradient(135deg, #f57c00, #e65100);"></span>
                <span>Por Liberar</span>
            </div>
            <div class="leyenda-item">
                <span class="leyenda-color" style="background: linear-gradient(135deg, #c62828, #b71c1c);"></span>
                <span>No Disponible</span>
            </div>
        </div>
    </div>

    <?php
    // Función para renderizar camas
    function renderizarCamas($conexion, $piso, $seccion) {
        $sql = "SELECT id, estatus, tipo, num_cama, id_atencion 
                FROM cat_camas 
                WHERE piso = $piso AND seccion = $seccion 
                ORDER BY num_cama ASC";
        $result = $conexion->query($sql);

        while ($row = $result->fetch_assoc()) {
            $num_cama = $row['num_cama'];
            $id_atencion = $row['id_atencion'];
            $estatus = $row['estatus'];

            // Determinar clase CSS según estado
            if ($estatus == "LIBRE") {
                $clase = "cama-libre";
                $icono = "fa-bed";
                $texto_estado = "DISPONIBLE";
                $paciente = "";
            } elseif ($estatus == "MANTENIMIENTO") {
                $clase = "cama-mantenimiento";
                $icono = "fa-tools";
                $texto_estado = "NO DISPONIBLE";
                $paciente = "";
            } elseif (strpos($estatus, "PROCESO") !== false) {
                $clase = "cama-proceso";
                $icono = "fa-clock";
                $texto_estado = "POR LIBERAR";
                $paciente = "";
            } else {
                $clase = "cama-ocupada";
                $icono = "fa-user-injured";
                $texto_estado = "OCUPADA";

                // Obtener nombre del paciente
                if ($id_atencion > 0) {
                    $sql_pac = "SELECT p.nom_pac, p.papell, p.sapell 
                               FROM dat_ingreso di 
                               INNER JOIN paciente p ON di.Id_exp = p.Id_exp 
                               WHERE di.id_atencion = $id_atencion";
                    $result_pac = $conexion->query($sql_pac);
                    if ($result_pac && $row_pac = $result_pac->fetch_assoc()) {
                        $paciente = $row_pac['nom_pac'] . ' ' . $row_pac['papell'];
                    } else {
                        $paciente = "";
                    }
                } else {
                    $paciente = "";
                }
            }
            ?>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12">
                <div class="cama-card <?php echo $clase; ?>">
                    <div class="cama-icon">
                        <i class="fa <?php echo $icono; ?>"></i>
                    </div>
                    <div class="cama-numero">
                        <?php echo htmlspecialchars($num_cama); ?>
                    </div>
                    <div class="cama-estado">
                        <?php echo htmlspecialchars($texto_estado); ?>
                    </div>
                    <?php if (!empty($paciente)): ?>
                        <div class="cama-paciente">
                            <i class="fa fa-user"></i>
                            <?php echo htmlspecialchars($paciente); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
        }
    }
    ?>

    <!-- Sección 1: Venecia 1 -->
    <div class="seccion-container">
        <h4>
            <i class="fa fa-building"></i> Área Venecia 1
        </h4>
        <div class="row">
            <?php renderizarCamas($conexion, 1, 1); ?>
        </div>
    </div>

    <!-- Sección 2: Venecia 2 -->
    <div class="seccion-container">
        <h4>
            <i class="fa fa-building"></i> Área Venecia 2
        </h4>
        <div class="row">
            <?php renderizarCamas($conexion, 1, 2); ?>
        </div>
    </div>

    <!-- Información adicional -->
    <div class="card info-card mt-4 mb-5">
        <div class="card-body">
            <h6 class="card-title">
                <i class="fa fa-info-circle"></i> Información Importante
            </h6>
            <ul class="mb-0">
                <li>Las habitaciones en verde están disponibles para asignación inmediata</li>
                <li>Las habitaciones en azul están actualmente ocupadas por pacientes</li>
                <li>Las habitaciones en naranja están en proceso de liberación</li>
                <li>Las habitaciones en rojo requieren mantenimiento técnico</li>
            </ul>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="main-footer">
    <?php include("../../template/footer.php"); ?>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

<script>
    $(document).ready(function() {
        // Treeview del sidebar
        $('.treeview > a').on('click', function (e) {
            e.preventDefault();
            let parent = $(this).parent();
            let submenu = parent.find('.treeview-menu').first();

            parent.toggleClass('menu-open');
            submenu.slideToggle(200);
        });

        // Cerrar otros submenús
        $('.sidebar-menu .treeview > a').on('click', function() {
            var clickedMenu = $(this).parent();
            $('.sidebar-menu .treeview').not(clickedMenu).removeClass('menu-open');
            $('.sidebar-menu .treeview').not(clickedMenu).find('.treeview-menu').slideUp(200);
        });

        // Animación escalonada de las camas
        $('.cama-card').each(function(index) {
            $(this).css('animation-delay', (index * 0.05) + 's');
        });
    });
</script>

</body>
</html>