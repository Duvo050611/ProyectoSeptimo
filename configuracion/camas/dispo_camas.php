<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../index.php");
}

include("../header_configuracion.php");
?>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">

    <script src="../../js/jquery-3.3.1.min.js"></script>
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/popper.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/main.js"></script>

    <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
    <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #0a0a0a 100%) !important;
            font-family: 'Roboto', sans-serif !important;
            min-height: 100vh;
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

        /* Header de sección */
        .section-header {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
            border: 2px solid #40E0FF;
            border-radius: 15px;
            color: #ffffff;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
            font-weight: 700;
            font-size: 22px;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 20px rgba(64, 224, 255, 0.8);
            box-shadow: 0 8px 30px rgba(64, 224, 255, 0.3);
            position: relative;
            overflow: hidden;
            animation: fadeInDown 0.6s ease-out;
        }

        .section-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(64, 224, 255, 0.1) 0%, transparent 70%);
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Container de camas */
        .container.box {
            background: linear-gradient(135deg, rgba(22, 33, 62, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%);
            border: 2px solid rgba(64, 224, 255, 0.3);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5),
                        0 0 20px rgba(64, 224, 255, 0.2);
            position: relative;
            z-index: 1;
        }

        /* Tarjetas de cama - LIBRE (Verde Cyberpunk) */
        .alert-success {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.2) 0%, rgba(32, 201, 151, 0.3) 100%) !important;
            border: 2px solid #28a745 !important;
            border-radius: 15px !important;
            color: #ffffff !important;
            padding: 15px !important;
            text-align: center;
            font-weight: 700;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3),
                        inset 0 0 15px rgba(40, 167, 69, 0.1);
            min-height: 130px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-bottom: 15px;
            position: relative;
            overflow: hidden;
        }

        .alert-success::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(40, 167, 69, 0.1), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s;
        }

        .alert-success:hover::before {
            transform: translateX(100%);
        }

        .alert-success:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.5),
                        inset 0 0 20px rgba(40, 167, 69, 0.15);
            border-color: #20c997;
        }

        .alert-success i {
            color: #28a745;
            text-shadow: 0 0 15px rgba(40, 167, 69, 0.8);
            transition: all 0.3s ease;
        }

        .alert-success:hover i {
            transform: scale(1.2);
            color: #20c997;
        }

        /* Tarjetas de cama - OCUPADA (Rojo Cyberpunk) */
        .alert-danger {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.2) 0%, rgba(200, 35, 51, 0.3) 100%) !important;
            border: 2px solid #dc3545 !important;
            border-radius: 15px !important;
            color: #ffffff !important;
            padding: 15px !important;
            text-align: center;
            font-weight: 700;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3),
                        inset 0 0 15px rgba(220, 53, 69, 0.1);
            min-height: 130px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-bottom: 15px;
            position: relative;
            overflow: hidden;
        }

        .alert-danger::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(220, 53, 69, 0.1), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s;
        }

        .alert-danger:hover::before {
            transform: translateX(100%);
        }

        .alert-danger:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.5),
                        inset 0 0 20px rgba(220, 53, 69, 0.15);
            border-color: #c82333;
        }

        .alert-danger i {
            color: #dc3545;
            text-shadow: 0 0 15px rgba(220, 53, 69, 0.8);
            transition: all 0.3s ease;
        }

        .alert-danger:hover i {
            transform: scale(1.2);
            color: #c82333;
        }

        /* Tarjetas de cama - MANTENIMIENTO (Amarillo Cyberpunk) */
        .alert-warning {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.2) 0%, rgba(255, 140, 0, 0.3) 100%) !important;
            border: 2px solid #ffc107 !important;
            border-radius: 15px !important;
            color: #ffffff !important;
            padding: 15px !important;
            text-align: center;
            font-weight: 700;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3),
                        inset 0 0 15px rgba(255, 193, 7, 0.1);
            min-height: 130px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-bottom: 15px;
            position: relative;
            overflow: hidden;
        }

        .alert-warning::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(255, 193, 7, 0.1), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s;
        }

        .alert-warning:hover::before {
            transform: translateX(100%);
        }

        .alert-warning:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 8px 25px rgba(255, 193, 7, 0.5),
                        inset 0 0 20px rgba(255, 193, 7, 0.15);
            border-color: #ff8c00;
        }

        .alert-warning i {
            color: #ffc107;
            text-shadow: 0 0 15px rgba(255, 193, 7, 0.8);
            transition: all 0.3s ease;
        }

        .alert-warning:hover i {
            transform: scale(1.2);
            color: #ff8c00;
        }

        /* Enlaces en las tarjetas */
        .small-box-footer {
            color: inherit !important;
            text-decoration: none;
            display: block;
            margin-top: 5px;
        }

        .small-box-footer:hover {
            color: #40E0FF !important;
            text-decoration: none;
        }

        /* Texto en las tarjetas */
        .alert h7, .alert h8 {
            font-size: 14px;
            line-height: 1.4;
            display: block;
            margin: 5px 0;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        .alert font {
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        /* Grid de camas */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: -7.5px;
        }

        .col-lg-1\.9, .col-lg-1\.5 {
            padding: 7.5px;
        }

        /* Columnas personalizadas */
        @media (min-width: 992px) {
            .col-lg-1\.9 {
                flex: 0 0 16.66%;
                max-width: 16.66%;
            }
            .col-lg-1\.5 {
                flex: 0 0 12.5%;
                max-width: 12.5%;
            }
        }

        /* Footer */
        .main-footer {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            border-top: 2px solid #40E0FF !important;
            color: #ffffff !important;
            box-shadow: 0 -4px 20px rgba(64, 224, 255, 0.2);
            margin-top: 30px;
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

        /* Animaciones de entrada */
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .alert {
            animation: fadeIn 0.5s ease-out backwards;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .col-lg-1\.9, .col-lg-1\.5 {
                flex: 0 0 50%;
                max-width: 50%;
            }

            .section-header {
                font-size: 18px;
                padding: 15px;
            }

            .alert {
                min-height: 110px;
            }
        }

        @media (max-width: 576px) {
            .col-lg-1\.9, .col-lg-1\.5 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="section-header">
    <i class="fas fa-hospital"></i> HABITACIONES EN HOSPITALIZACIÓN
</div>

<section class="content container-fluid col-12">
    <div class="container box col-12">
        <div class="row">
            <?php
            $sql = 'SELECT id,estatus,tipo,num_cama, id_atencion from cat_camas where tipo="HOSPITALIZACION" and piso=1 and seccion=1 ORDER BY num_cama ASC';
            $result = $conexion->query($sql);
            while ($row = $result->fetch_assoc()) {
                $num_cama = $row['num_cama'];
                $id_atencion = $row['id_atencion'];
                $estaus = $row['estatus'];
                if ($estaus == "LIBRE") {
            ?>
                <div class="col-lg-1.9 col-xs-4">
                    <div class="alert alert-success" role="alert">
                        <h7><font size="2"><?php echo $num_cama ?></font></h7>
                        <br>
                        <h7><font size="2"><?php echo $estaus ?></font></h7><br>
                        <a href="#" class="small-box-footer"><i style="font-size:20px;" class="fa fa-bed"></i></a>
                    </div>
                </div>
            <?php
                } elseif ($estaus == "MANTENIMIENTO") {
                    $esta = "NO DISPONIBLE";
            ?>
                <div class="col-lg-1.9 col-xs-4">
                    <div class="alert alert-warning" role="alert">
                        <h7><font size="2"><?php echo $num_cama ?></font></h7>
                        <br>
                        <h7><font size="1"><?php echo $esta ?></font></h7><br>
                        <a href="#" class="small-box-footer"><i style="font-size:20px;" class="fa fa-bed"></i></a>
                    </div>
                </div>
            <?php
                } else {
            ?>
                <div class="col-lg-1.9 col-xs-4">
                    <div class="alert alert-danger" role="alert">
                        <h8><font size="2"><?php echo $num_cama ?></font></h8><br>
                        <?php
                        $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                        $result_pac = $conexion->query($sql_pac);
                        while ($row_cam = $result_pac->fetch_assoc()) {
                            $nombre_pac = $row_cam['nom_pac'] . ' ' . $row_cam['papell'];
                        }
                        ?>
                        <font size="1"><?php echo $nombre_pac ?></font>
                        <br/>
                        <a href="../../gestion_medica/hospitalizacion/select_pac.php?id_atencion=<?php echo $id_atencion ?>" class="small-box-footer"><i style="font-size:20px;" class="fa fa-bed"></i></a>
                    </div>
                </div>
            <?php
                }
            }

            // Sección 2
            $sql = 'SELECT id,estatus,tipo,num_cama, id_atencion from cat_camas where tipo="HOSPITALIZACION" and piso=1 and seccion=2 ORDER BY num_cama ASC';
            $result = $conexion->query($sql);
            while ($row = $result->fetch_assoc()) {
                $num_cama = $row['num_cama'];
                $id_atencion = $row['id_atencion'];
                $estaus = $row['estatus'];
                if ($estaus == "LIBRE") {
            ?>
                <div class="col-lg-1.9 col-xs-4">
                    <div class="alert alert-success" role="alert">
                        <h7><font size="2"><?php echo $num_cama ?></font></h7>
                        <br>
                        <h7><font size="2"><?php echo $estaus ?></font></h7><br>
                        <a href="#" class="small-box-footer"><i style="font-size:20px;" class="fa fa-bed"></i></a>
                    </div>
                </div>
            <?php
                } elseif ($estaus == "MANTENIMIENTO") {
                    $esta = "NO DISPONIBLE";
            ?>
                <div class="col-lg-1.9 col-xs-4">
                    <div class="alert alert-warning" role="alert">
                        <h7><font size="2"><?php echo $num_cama ?></font></h7>
                        <br>
                        <h7><font size="1"><?php echo $esta ?></font></h7><br>
                        <a href="#" class="small-box-footer"><i style="font-size:20px;" class="fa fa-bed"></i></a>
                    </div>
                </div>
            <?php
                } else {
            ?>
                <div class="col-lg-1.9 col-xs-4">
                    <div class="alert alert-danger" role="alert">
                        <h8><font size="2"><?php echo $num_cama ?></font></h8><br>
                        <?php
                        $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                        $result_pac = $conexion->query($sql_pac);
                        while ($row_cam = $result_pac->fetch_assoc()) {
                            $nombre_pac = $row_cam['nom_pac'] . ' ' . $row_cam['papell'];
                        }
                        ?>
                        <font size="1"><?php echo $nombre_pac ?></font>
                        <br/>
                        <a href="../../gestion_medica/hospitalizacion/select_pac.php?id_atencion=<?php echo $id_atencion ?>" class="small-box-footer"><i style="font-size:20px;" class="fa fa-bed"></i></a>
                    </div>
                </div>
            <?php
                }
            }
            ?>
        </div>
    </div>

    <!-- PISO 2 - Similar structure -->
    <div class="container box col-12">
        <div class="row">
            <?php
            // Piso 2, Sección 1
            $sql = 'SELECT id,estatus,tipo,num_cama, id_atencion from cat_camas where tipo="HOSPITALIZACION" and piso=2 and seccion=1 ORDER BY num_cama ASC';
            $result = $conexion->query($sql);
            while ($row = $result->fetch_assoc()) {
                $num_cama = $row['num_cama'];
                $id_atencion = $row['id_atencion'];
                $estaus = $row['estatus'];
                // Same structure as above...
                if ($estaus == "LIBRE") {
            ?>
                <div class="col-lg-1.9 col-xs-4">
                    <div class="alert alert-success" role="alert">
                        <h7><font size="2"><?php echo $num_cama ?></font></h7>
                        <br>
                        <h7><font size="2"><?php echo $estaus ?></font></h7><br>
                        <a href="#" class="small-box-footer"><i style="font-size:20px;" class="fa fa-bed"></i></a>
                    </div>
                </div>
            <?php
                } elseif ($estaus == "MANTENIMIENTO") {
                    $esta = "NO DISPONIBLE";
            ?>
                <div class="col-lg-1.9 col-xs-4">
                    <div class="alert alert-warning" role="alert">
                        <h7><font size="2"><?php echo $num_cama ?></font></h7>
                        <br>
                        <h7><font size="1"><?php echo $esta ?></font></h7><br>
                        <a href="#" class="small-box-footer"><i style="font-size:20px;" class="fa fa-bed"></i></a>
                    </div>
                </div>
            <?php
                } else {
            ?>
                <div class="col-lg-1.9 col-xs-4">
                    <div class="alert alert-danger" role="alert">
                        <h8><font size="2"><?php echo $num_cama ?></font></h8><br>
                        <?php
                        $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                        $result_pac = $conexion->query($sql_pac);
                        while ($row_cam = $result_pac->fetch_assoc()) {
                            $nombre_pac = $row_cam['nom_pac'] . ' ' . $row_cam['papell'];
                        }
                        ?>
                        <font size="1"><?php echo $nombre_pac ?></font>
                        <br/>
                        <a href="../../gestion_medica/hospitalizacion/select_pac.php?id_atencion=<?php echo $id_atencion ?>" class="small-box-footer"><i style="font-size:20px;" class="fa fa-bed"></i></a>
                    </div>
                </div>
            <?php
                }
            }
            // Continue with sections 2, 3, etc. following same pattern...
            ?>
        </div>
    </div>

</section>

<footer class="main-footer">
    <?php include("../../template/footer.php"); ?>
</footer>

<script>
document.oncontextmenu = function() {
    return false;
}
</script>
</body>
</html>
