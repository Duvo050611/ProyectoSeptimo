<?php
session_start();
require_once "../../conexionbd.php";
$conexion = ConexionBD::getInstancia()->getConexion();
include "../header_enfermera.php";
$usuario = $_SESSION['login'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1″/>
    <link rel="icon" href="../../imagenes/SIF.PNG">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
            integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
            crossorigin="anonymous"></script>

    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
          integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/e547be4475.js" crossorigin="anonymous"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <script src="../../js/jquery-3.3.1.min.js"></script>
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/popper.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/main.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script>
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
        });
    </script>

    <?php
    $rol=$usuario['id_rol'];
    $id_at=$_SESSION['pac'];
    $resultado1 = $conexion->query("SELECT * FROM dat_ordenes_med WHERE id_atencion =$id_at and visto='NO' order by id_ord_med desc limit 1" ) or die($conexion->error);
    while ($f1 = mysqli_fetch_array($resultado1)) {
        $id_ord_med=$f1['id_ord_med'];
    }
    if(isset($id_ord_med) && $rol != 1){
        ?>
        <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <?php } ?>

    <title>Menu Enfermería</title>
    <link rel="shortcut icon" href="logp.png">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%) !important;
            font-family: 'Roboto', sans-serif !important;
            min-height: 100vh;
            color: #e0e0e0 !important;
        }

        /* Efecto sutil en el fondo */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                    radial-gradient(circle at 20% 50%, rgba(64, 224, 255, 0.02) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(100, 181, 246, 0.02) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        /* Ocultar fondo blanco */
        .content-wrapper,
        section.content {
            background: transparent !important;
        }

        /* Sidebar con texto blanco */
        .main-sidebar {
            background: linear-gradient(180deg, #16213e 0%, #0f3460 100%) !important;
        }

        .sidebar-menu > li > a {
            color: #ffffff !important;
        }

        .sidebar-menu > li > a:hover {
            background: rgba(100, 181, 246, 0.1) !important;
            color: #64b5f6 !important;
        }

        .sidebar-menu > li.active > a {
            background: rgba(100, 181, 246, 0.2) !important;
            color: #64b5f6 !important;
        }

        .treeview-menu > li > a {
            color: #ffffff !important;
        }

        .treeview-menu > li > a:hover {
            background: rgba(100, 181, 246, 0.1) !important;
            color: #64b5f6 !important;
        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 1400px;
            padding: 20px;
        }

        /* Título principal */
        h2 {
            color: #64b5f6 !important;
            font-weight: 600 !important;
            text-shadow: 0 2px 8px rgba(100, 181, 246, 0.3);
            letter-spacing: 1px;
            margin-top: 20px;
            margin-bottom: 15px;
            font-size: 1.8rem;
        }

        h2 i {
            margin-right: 10px;
            color: #42a5f5;
        }

        /* Separador HR */
        hr {
            border: none;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(100, 181, 246, 0.4), transparent);
            margin: 20px 0;
        }

        /* Alertas de medicamentos pendientes */
        .alert-danger {
            background: linear-gradient(135deg, #d32f2f 0%, #e53935 100%) !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 15px rgba(211, 47, 47, 0.3);
            font-weight: 500;
            margin-bottom: 15px;
            padding: 12px 20px;
            transition: all 0.3s ease;
        }

        .alert-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(211, 47, 47, 0.4);
        }

        .alert-danger i {
            margin-right: 8px;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        /* Tarjetas de información del paciente */
        .info-card {
            background: linear-gradient(135deg, rgba(25, 35, 55, 0.95) 0%, rgba(22, 33, 62, 0.95) 100%) !important;
            border: 1px solid rgba(100, 181, 246, 0.3) !important;
            border-radius: 16px !important;
            padding: 0;
            margin-bottom: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(100, 181, 246, 0.1);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .info-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4), 0 0 20px rgba(100, 181, 246, 0.15);
            border-color: rgba(100, 181, 246, 0.5);
        }

        .info-content {
            padding: 35px;
        }

        /* Sección de imagen */
        .image-section {
            background: linear-gradient(135deg, rgba(15, 52, 96, 0.8) 0%, rgba(22, 33, 62, 0.8) 100%);
            padding: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 400px;
            border-right: 1px solid rgba(100, 181, 246, 0.2);
        }

        .image-section img {
            max-width: 100%;
            max-height: 400px;
            object-fit: contain;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            filter: brightness(1.05) contrast(1.02);
        }

        @media screen and (max-width: 991px) {
            .image-section {
                border-right: none;
                border-bottom: 1px solid rgba(100, 181, 246, 0.2);
                min-height: 300px;
            }
        }

        /* Etiquetas de información */
        .info-label {
            color: #90caf9 !important;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
        }

        .info-label i {
            margin-right: 8px;
            color: #64b5f6;
            font-size: 1rem;
        }

        .info-value {
            color: #f5f5f5 !important;
            font-weight: 400;
            font-size: 1.05rem;
            margin-bottom: 20px;
            padding-left: 26px;
            line-height: 1.5;
        }

        .info-value strong {
            color: #ffffff !important;
            font-weight: 500;
        }

        /* Alergias en rojo con mejor contraste */
        .alergia-text {
            color: #ff6b6b !important;
            font-weight: 600 !important;
            padding: 8px 12px;
            background: rgba(255, 107, 107, 0.15);
            border-radius: 8px;
            border-left: 3px solid #ff6b6b;
            display: inline-block;
        }

        /* Carrusel de imágenes - ya no se usa separado */
        .carousel {
            display: none;
        }

        /* Footer */
        .main-footer {
            background: linear-gradient(135deg, rgba(22, 33, 62, 0.95) 0%, rgba(15, 52, 96, 0.95) 100%) !important;
            border-top: 1px solid rgba(100, 181, 246, 0.3) !important;
            color: #e0e0e0 !important;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.3);
            margin-top: 40px;
            padding: 20px 0;
        }

        /* Scrollbar personalizado más suave */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(22, 33, 62, 0.5);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #64b5f6 0%, #42a5f5 100%);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #90caf9 0%, #64b5f6 100%);
        }

        /* Secciones de información */
        .section-divider {
            margin: 30px 0 20px 0;
            padding: 15px 0;
            border-bottom: 1px solid rgba(100, 181, 246, 0.2);
        }

        .section-title {
            color: #f66464 !important;
            font-weight: 600;
            font-size: 1.2rem;
            letter-spacing: 1px;
        }

        /* Animaciones de entrada suaves */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .info-card {
            animation: fadeInUp 0.5s ease-out;
        }

        /* Responsive */
        @media screen and (max-width: 768px) {
            .info-content {
                padding: 25px 20px;
            }

            h2 {
                font-size: 1.5rem;
            }

            .info-value {
                font-size: 1rem;
                padding-left: 20px;
            }

            .info-label {
                font-size: 0.8rem;
            }

            .container {
                padding: 15px;
            }

            .image-section {
                padding: 30px;
            }
        }

        /* Ajustes para texto en elementos TD */
        td {
            color: #f5f5f5 !important;
        }

        td strong {
            color: #ffffff !important;
        }

        /* Estilos para contenido principal */
        .content {
            padding: 0;
        }

        /* Mejora visual para las imágenes */
        .fondo img {
            filter: brightness(1.05) contrast(1.02);
        }

        /* Grid mejorado para info */
        .info-grid {
            display: grid;
            gap: 20px;
        }

        @media screen and (min-width: 768px) {
            .info-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Mejora en legibilidad */
        .col {
            color: #e0e0e0;
        }

        /* Transiciones suaves */
        * {
            transition: color 0.2s ease, background-color 0.2s ease, border-color 0.2s ease;
        }

        button, a {
            transition: all 0.3s ease !important;
        }

    </style>
</head>

<body>

<div class="container">
    <div class="row">
        <div class="col">
            <h2><i class="fas fa-user-injured"></i> INFORMACIÓN DEL PACIENTE</h2>
            <hr>

            <?php
            require_once "../../conexionbd.php";

            $bisiesto=false;
            $resultado1 = $conexion->query("SELECT paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.alergias, dat_ingreso.motivo_atn, dat_ingreso.id_usua as id_med, dat_ingreso.fecha, dat_ingreso.id_atencion
            from paciente 
            inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE id_atencion=" . $_SESSION['pac']) or die($conexion->error);
            $id_atencion = $_SESSION['pac'];
            ?>

            <?php
            while ($f1 = mysqli_fetch_array($resultado1)) {
                $id_med=$f1['id_med'];
                $area=$f1['area'];
                $religion=$f1['religion'];
                $tip_san=$f1['tip_san'];
                $sexo = $f1['sexo'];
                $alergias=$f1['alergias'];
                $id_atn=$f1['id_atencion'];
                ?>

                <?php
                /*********************** Alertas de medicamentos pendientes **********************/
                $sql_cart = "SELECT * FROM cart_enf where paciente = $id_atencion ORDER BY paciente";
                $result_cart = $conexion->query($sql_cart);

                while ($row_cart = $result_cart->fetch_assoc()) {
                    $carth_id = $row_cart['cart_id'];
                }

                if(isset($carth_id)){
                    ?>
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible fade show">
                            <center><strong><i class="fas fa-exclamation-triangle"></i> MEDICAMENTOS de HOSPITALIZACIÓN pendientes de confirmar</strong></center>
                        </div>
                    </div>
                <?php }else{
                    $carth_id ='nada';
                } ?>

                <?php
                /*********************** Quirófano sin confirmar **********************/
                $sql_cart = "SELECT * FROM cart_almacen where paciente = $id_atn ORDER BY paciente";
                $result_cart = $conexion->query($sql_cart);

                while ($row_cart = $result_cart->fetch_assoc()) {
                    $cartq_id = $row_cart['id'];
                }

                if(isset($cartq_id)){
                    ?>
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible fade show">
                            <center><strong><i class="fas fa-exclamation-triangle"></i> MEDICAMENTOS de QUIRÓFANO pendientes de confirmar</strong></center>
                        </div>
                    </div>
                <?php }else{
                    $cartq_id ='nada';
                } ?>

                <?php
                /*********************** Equipos sin confirmar **********************/
                $sql_cart = "SELECT * FROM cart_serv where paciente = $id_atn ORDER BY paciente";
                $result_cart = $conexion->query($sql_cart);

                while ($row_cart = $result_cart->fetch_assoc()) {
                    $carte_id = $row_cart['id'];
                }

                if(isset($carte_id)){
                    ?>
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible fade show">
                            <center><strong><i class="fas fa-exclamation-triangle"></i> EQUIPOS de QUIRÓFANO pendientes de confirmar</strong></center>
                        </div>
                    </div>
                <?php }else{
                    $carte_id ='nada';
                } ?>

                <?php
                /*********************** Materiales sin confirmar **********************/
                $sql_cart = "SELECT * FROM cart_mat where paciente = $id_atn ORDER BY paciente";
                $result_cart = $conexion->query($sql_cart);

                while ($row_cart = $result_cart->fetch_assoc()) {
                    $cartm_id = $row_cart['id'];
                }

                if(isset($cartm_id)){
                    ?>
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible fade show">
                            <center><strong><i class="fas fa-exclamation-triangle"></i> MATERIALES de QUIRÓFANO pendientes de confirmar</strong></center>
                        </div>
                    </div>
                <?php }else{
                    $cartm_id ='nada';
                } ?>

                <!-- Información del paciente con imagen integrada -->
                <div class="info-card">
                    <div class="row no-gutters">
                        <!-- Columna de imagen -->
                        <div class="col-lg-4">
                            <div class="image-section">
                                <?php
                                $resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC LIMIT 1") or die($conexion->error);
                                while($f = mysqli_fetch_array($resultado)){
                                    $id_simg=$f['id_simg'];
                                    ?>
                                    <img src="../../configuracion/admin/img5/<?php echo $f['img_cuerpo']?>" alt="Imagen del sistema" class="img-fluid">
                                <?php } ?>
                            </div>
                        </div>

                        <!-- Columna de información -->
                        <div class="col-lg-8">
                            <div class="info-content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-label">
                                            <i class="fas fa-id-card"></i> EXPEDIENTE
                                        </div>
                                        <div class="info-value">
                                            <strong><?php echo $f1['Id_exp']; ?></strong>
                                        </div>

                                        <div class="info-label">
                                            <i class="fas fa-user"></i> PACIENTE
                                        </div>
                                        <div class="info-value">
                                            <strong><?php echo $f1['papell'] . ' ' . $f1['sapell'] . ' ' . $f1['nom_pac']; ?></strong>
                                        </div>

                                        <div class="info-label">
                                            <i class="fas fa-venus-mars"></i> GÉNERO
                                        </div>
                                        <div class="info-value">
                                            <strong><?php echo $sexo; ?></strong>
                                        </div>

                                        <?php
                                        $d="";
                                        $sql_motd = "SELECT diagprob_i from dat_not_ingreso where id_atencion=$id_atencion ORDER by id_not_ingreso DESC LIMIT 1";
                                        $result_motd = $conexion->query($sql_motd);
                                        while ($row_motd = $result_motd->fetch_assoc()) {
                                            $d=$row_motd['diagprob_i'];
                                        }
                                        $sql_motd = "SELECT diagprob_i from dat_nevol where id_atencion=$id_atencion ORDER by id_ne DESC LIMIT 1";
                                        $result_motd = $conexion->query($sql_motd);
                                        while ($row_motd = $result_motd->fetch_assoc()) {
                                            $d=$row_motd['diagprob_i'];
                                        }
                                        ?>

                                        <div class="info-label">
                                            <i class="fas fa-file-medical"></i> DIAGNÓSTICO
                                        </div>
                                        <div class="info-value">
                                            <strong><?php echo $d; ?></strong>
                                        </div>

                                        <div class="info-label">
                                            <i class="fas fa-allergies"></i> ALERGIAS
                                        </div>
                                        <div class="info-value alergia-text">
                                            <strong><?php echo $alergias; ?></strong>
                                        </div>
                                    </div>

                                    <?php
                                    // INICIO DE FUNCION DE CALCULAR EDAD
                                    function bisiesto($anio_actual){
                                        $bisiesto=false;
                                        if (checkdate(2,29,$anio_actual))
                                        {
                                            $bisiesto=true;
                                        }
                                        return $bisiesto;
                                    }

                                    $fecha_actual = date("Y-m-d");
                                    $fecha_nac=$f1['fecnac'];
                                    $fecha_de_nacimiento =strval($fecha_nac);

                                    $array_nacimiento = explode ( "-", $fecha_de_nacimiento );
                                    $array_actual = explode ( "-", $fecha_actual );

                                    $anos =  $array_actual[0] - $array_nacimiento[0];
                                    $meses = $array_actual[1] - $array_nacimiento[1];
                                    $dias =  $array_actual[2] - $array_nacimiento[2];
                                    $ano_nac = $array_actual[0];

                                    if ($dias < 0)
                                    {
                                        --$meses;

                                        switch ($array_actual[1]) {
                                            case 1:     $dias_mes_anterior=31; break;
                                            case 2:     $dias_mes_anterior=31; break;
                                            case 3:
                                                if (bisiesto($array_actual[0]))
                                                {
                                                    $dias_mes_anterior=29; break;
                                                } else {
                                                    $dias_mes_anterior=28; break;
                                                }
                                            case 4:     $dias_mes_anterior=31; break;
                                            case 5:     $dias_mes_anterior=30; break;
                                            case 6:     $dias_mes_anterior=31; break;
                                            case 7:     $dias_mes_anterior=30; break;
                                            case 8:     $dias_mes_anterior=31; break;
                                            case 9:     $dias_mes_anterior=31; break;
                                            case 10:     $dias_mes_anterior=30; break;
                                            case 11:     $dias_mes_anterior=31; break;
                                            case 12:     $dias_mes_anterior=30; break;
                                        }

                                        $dias=$dias + $dias_mes_anterior;
                                    }

                                    if ($meses < 0)
                                    {
                                        --$anos;
                                        $meses=$meses + 12;
                                    }
                                    ?>

                                    <div class="col-md-6">
                                        <?php
                                        $date = date_create($f1['fecha']);
                                        $date1 = date_create($f1['fecnac']);
                                        ?>

                                        <div class="info-label">
                                            <i class="fas fa-calendar-check"></i> FECHA DE INGRESO
                                        </div>
                                        <div class="info-value">
                                            <strong><?php echo date_format($date, "d/m/Y"); ?></strong>
                                        </div>

                                        <div class="info-label">
                                            <i class="fas fa-birthday-cake"></i> FECHA DE NACIMIENTO
                                        </div>
                                        <div class="info-value">
                                            <strong><?php echo date_format($date1, "d/m/Y"); ?></strong>
                                        </div>

                                        <div class="info-label">
                                            <i class="fas fa-hourglass-half"></i> EDAD
                                        </div>
                                        <div class="info-value">
                                            <strong>
                                                <?php
                                                if($anos > "0" ){
                                                    echo $anos." Años";
                                                }elseif($anos <="0" && $meses>"0"){
                                                    echo $meses." Meses";
                                                }elseif($anos <="0" && $meses<="0" && $dias>"0"){
                                                    echo $dias." Días";
                                                }
                                                ?>
                                            </strong>
                                        </div>

                                        <?php
                                        $resultado2 = $conexion->query("select * from cat_camas WHERE id_atencion=" .$_SESSION['pac']) or die($conexion->error);
                                        while ($f2 = mysqli_fetch_array($resultado2)) {
                                            if(isset($f2)){
                                                $cama=$f2['num_cama'].' '.$f2['tipo'];
                                            }else{
                                                $cama='Sin Cama';
                                            }
                                            ?>

                                            <div class="info-label">
                                                <i class="fas fa-hospital"></i> ÁREA
                                            </div>
                                            <div class="info-value">
                                                <strong><?php echo $area; ?></strong>
                                            </div>

                                            <div class="info-label">
                                                <i class="fas fa-bed"></i> HABITACIÓN
                                            </div>
                                            <div class="info-value">
                                                <strong><?php echo $cama; ?></strong>
                                            </div>

                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="section-divider"></div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <?php
                                        $select_doc = $conexion->query("SELECT * from reg_usuarios WHERE id_usua=$id_med") or die($conexion->error);
                                        while ($row = mysqli_fetch_array($select_doc)) {
                                            $doctor=$row['papell'];
                                        }
                                        ?>
                                        <div class="info-label">
                                            <i class="fas fa-user-md"></i> MÉDICO TRATANTE
                                        </div>
                                        <div class="info-value">
                                            <strong><?php echo $doctor; ?></strong>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="info-label">
                                            <i class="fas fa-praying-hands"></i> RELIGIÓN
                                        </div>
                                        <div class="info-value">
                                            <strong><?php echo $religion; ?></strong>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="info-label">
                                            <i class="fas fa-tint"></i> TIPO DE SANGRE
                                        </div>
                                        <div class="info-value">
                                            <strong><?php echo $tip_san; ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<footer class="main-footer">
    <?php include("../../template/footer.php"); ?>
</footer>
<script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

</body>
</html>