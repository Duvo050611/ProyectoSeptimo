<?php
session_start();
include "../../conexionbd.php";
include("../header_enfermera.php");

$usuario_actual = '';
$rol_usuario = '';
$id_usuario = '';
if (isset($_SESSION['user'])) {
    $id_usuario = $_SESSION['user'];
    $sql_usuario = "SELECT nombre, papell, sapell, id_rol FROM reg_usuarios WHERE id_usua = ?";
    $stmt_usuario = $conexion->prepare($sql_usuario);
    $stmt_usuario->bind_param("i", $id_usuario);
    $stmt_usuario->execute();
    $result_usuario = $stmt_usuario->get_result();
    if ($result_usuario && $result_usuario->num_rows > 0) {
        $row_usuario = $result_usuario->fetch_assoc();
        $usuario_actual = trim($row_usuario['nombre'] . ' ' . $row_usuario['papell'] . ' ' . $row_usuario['sapell']);
        $rol_usuario = $row_usuario['id_rol'];
    }
    $stmt_usuario->close();
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″ />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  <!--  Bootstrap  -->
  <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
 


    <title>HOJA DE PROGRAMACIÓN QUIRÚRGICA </title>
    <style type="text/css">
        .modal-lg {
            max-width: 65% !important;
        }

        /* Estilos para el formulario de procedimientos */
        .container-fluid {
            padding: 0 15px;
        }

        .card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .card-header {
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
        }

        .card-body {
            padding: 20px;
        }

        /* Estilos para la tabla de signos vitales */
        .table-responsive {
            overflow-x: auto;
            margin: 15px 0;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }

        .table-bordered th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 12px;
            color: #495057;
        }

        .table-bordered td {
            font-size: 12px;
        }

        .table-bordered input.form-control {
            padding: 6px 8px;
            font-size: 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            width: 100%;
            min-width: 80px;
            text-align: center;
        }

        .table-bordered input.form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        /* Estilos para la fila del botón agregar */
        .btn-agregar-row td {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            text-align: left !important;
            padding-left: 15px !important;
        }

        .btn-agregar-row .btn {
            font-size: 12px;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
        }

        .btn-agregar-row .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: white;
        }

        .btn-agregar-row .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
        }

        /* Estilos para campos readonly */
        input[readonly] {
            background-color: #e9ecef !important;
            cursor: not-allowed;
        }

        /* Mejora para pequeños textos */
        small.text-muted {
            font-style: italic;
            color: #6c757d !important;
        }

        /* Ajustes para inputs de tiempo */
        input[type="time"] {
            min-width: 100px;
        }

        /* Estilos para filas de signos vitales guardadas */
        .durante-cirugia-row.guardado {
            background-color: #d4edda !important;
        }

        .durante-cirugia-row.guardado td {
            background-color: #d4edda !important;
            border-color: #c3e6cb !important;
        }

        .durante-cirugia-row.guardado input[readonly] {
            background-color: #c3e6cb !important;
            color: #155724 !important;
            font-weight: 500;
        }

        .btn-guardado {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            color: white !important;
            cursor: not-allowed !important;
        }

        .btn-guardado:hover {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
        }

        /* Animación para indicar guardado exitoso */
        @keyframes guardarExito {
            0% { background-color: #ffffff; }
            50% { background-color: #d1ecf1; }
            100% { background-color: #d4edda; }
        }

        .durante-cirugia-row.guardando {
            animation: guardarExito 1s ease-in-out;
        }

        /* Estilos para formularios */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        .form-control {
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        /* Estilos para botones de voz */
        .botones {
            display: flex;
            gap: 5px;
            margin-bottom: 10px;
        }

        .botones .btn {
            padding: 5px 10px;
            font-size: 12px;
        }

        /* Estilos para el selector de tratamiento */
        #selector_tratamiento {
            font-size: 18px;
            font-weight: bold;
            padding: 12px 15px;
            border: 2px solid #2b2d7f;
            border-radius: 8px;
            background-color: #ffffff;
            color: #2b2d7f;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            min-height: 50px;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%232b2d7f" viewBox="0 0 16 16"><path d="M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
            padding-right: 40px;
        }

        #selector_tratamiento:focus {
            border-color: #1a1d5f;
            box-shadow: 0 0 0 0.2rem rgba(43, 45, 127, 0.25);
            outline: none;
        }

        #selector_tratamiento option {
            font-size: 16px;
            font-weight: normal;
            padding: 8px;
            color: #333;
            background-color: #ffffff;
        }

        #selector_tratamiento option:hover {
            background-color: #f8f9fa;
        }

        /* Estilos para las cards de formularios */
        .formulario-tratamiento {
            margin-top: 20px;
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .formulario-tratamiento .card-header {
            background-color: #2b2d7f;
            color: white;
            font-weight: bold;
            border-bottom: 2px solid #1a1d5f;
        }

        .formulario-tratamiento .card-body {
            padding: 20px;
        }

        /* Mejoras en el estilo del card del selector */
        .card-header {
            padding: 15px 20px;
        }

        .card-header label {
            margin-bottom: 10px;
            display: block;
        }

        /* Estilos para la tabla de signos vitales */
        .table-signos-vitales {
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .table-signos-vitales th {
            background-color: #2b2d7f;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 10px 8px;
            font-size: 13px;
        }

        .table-signos-vitales td {
            padding: 8px 6px;
            vertical-align: middle;
        }

        .table-signos-vitales td strong {
            color: #2b2d7f;
        }

        /* Efecto hover para las filas */
        .table-signos-vitales tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Estilos para inputs dentro de la tabla */
        .table-signos-vitales input[type="text"],
        .table-signos-vitales input[type="time"] {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 4px 8px;
            font-size: 12px;
            transition: border-color 0.15s ease-in-out;
        }

        .table-signos-vitales input[type="text"]:focus,
        .table-signos-vitales input[type="time"]:focus {
            border-color: #2b2d7f;
            box-shadow: 0 0 0 0.1rem rgba(43, 45, 127, 0.25);
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .table-bordered {
                font-size: 10px;
            }
            
            .table-bordered th,
            .table-bordered td {
                padding: 4px 2px;
            }
            
            .table-bordered input.form-control {
                min-width: 60px;
                font-size: 10px;
            }
        }
    </style>
</head>

<body>

    <?php
    if (isset($_GET['tratamiento_exito']) && !empty($_GET['tratamiento_exito'])) {
        $tratamiento_exito = htmlspecialchars($_GET['tratamiento_exito']);
        echo '<div class="alert alert-success mt-3" role="alert" style="font-size:18px; text-align:center;">';
        echo 'Formulario de <strong>' . strtoupper($tratamiento_exito) . '</strong> enviado correctamente.';
        echo '</div>';
    }
    
    if (isset($_GET['exito_multiples']) && !empty($_GET['exito_multiples'])) {
        $mensaje_exito = htmlspecialchars($_GET['exito_multiples']);
        echo '<div class="alert alert-success mt-3" role="alert" style="font-size:18px; text-align:center;">';
        echo '<i class="fas fa-check-circle"></i> ' . $mensaje_exito;
        echo '<br><a href="ver_grafica.php" class="btn btn-primary btn-sm mt-2">';
        echo '<i class="fas fa-chart-line"></i> Ver Gráficas de Signos Vitales</a>';
        echo '</div>';
    }
    
    if (isset($_GET['error']) && !empty($_GET['error'])) {
        $error = htmlspecialchars($_GET['error']);
        echo '<div class="alert alert-danger mt-3" role="alert" style="font-size:18px; text-align:center;">';
        echo '<i class="fas fa-exclamation-triangle"></i> Error: ' . $error;
        echo '</div>';
    }
    ?>
    <div class="container">
        <div class="mt-3">
            <?php if (isset($_SESSION['message']) && isset($_SESSION['message_type'])): ?>
            <div class="alert alert-<?php echo htmlspecialchars($_SESSION['message_type']); ?> alert-dismissible fade show"
                role="alert">
                <?php echo htmlspecialchars($_SESSION['message']); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php
        // Limpiar el mensaje
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        ?>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col">
                <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 24px;"><strong>
                        <center>DATOS DEL PACIENTE</center>
                    </strong></div>
                    <?php
                    include "../../conexionbd.php";
                    if (isset($_SESSION['pac'])) {
                        $id_atencion = $_SESSION['pac'];
                        $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.folio, p.tel, p.fecnac, p.tip_san, di.fecha, di.area, di.alta_med, di.activo, p.sexo, di.alergias, p.ocup FROM paciente p, dat_ingreso di WHERE p.Id_exp=di.Id_exp AND di.id_atencion = ?";
                        $stmt = $conexion->prepare($sql_pac);
                        $stmt->bind_param("i", $id_atencion);
                        $stmt->execute();
                        $result_pac = $stmt->get_result();
                        while ($row_pac = $result_pac->fetch_assoc()) {
                            $pac_papell = $row_pac['papell'];
                            $pac_sapell = $row_pac['sapell'];
                            $pac_nom_pac = $row_pac['nom_pac'];
                            $pac_dir = $row_pac['dir'];
                            $pac_id_edo = $row_pac['id_edo'];
                            $pac_id_mun = $row_pac['id_mun'];
                            $pac_tel = $row_pac['tel'];
                            $pac_fecnac = $row_pac['fecnac'];
                            $pac_fecing = $row_pac['fecha'];
                            $pac_tip_sang = $row_pac['tip_san'];
                            $pac_sexo = $row_pac['sexo'];
                            $area = $row_pac['area'];
                            $alta_med = $row_pac['alta_med'];
                            $id_exp = $row_pac['Id_exp'];
                            $folio = $row_pac['folio'];
                            $alergias = $row_pac['alergias'];
                            $ocup = $row_pac['ocup'];
                            $activo = $row_pac['activo'];
                        }
                        $stmt->close();
                        $stmt = $conexion->prepare("SELECT area FROM dat_ingreso WHERE id_atencion = ?");
                        $stmt->bind_param("i", $id_atencion);
                        $stmt->execute();
                        $resultado1 = $stmt->get_result();

                        $area = "No asignada"; // Default value
                        if ($f1 = $resultado1->fetch_assoc()) {
                            $area = $f1['area'];
                        }
                        $stmt->close();

                        if ($activo === 'SI') {
                            $sql_now = "SELECT DATE_ADD(NOW(), INTERVAL 12 HOUR) as dat_now FROM dat_ingreso WHERE id_atencion = ?";
                            $stmt = $conexion->prepare($sql_now);
                            $stmt->bind_param("i", $id_atencion);
                            $stmt->execute();
                            $result_now = $stmt->get_result();
                            while ($row_now = $result_now->fetch_assoc()) {
                                $dat_now = $row_now['dat_now'];
                            }
                            $stmt->close();
                            $sql_est = "SELECT DATEDIFF( ?, fecha) as estancia FROM dat_ingreso WHERE id_atencion = ?";
                            $stmt = $conexion->prepare($sql_est);
                            $stmt->bind_param("si", $dat_now, $id_atencion);
                            $stmt->execute();
                            $result_est = $stmt->get_result();
                            while ($row_est = $result_est->fetch_assoc()) {
                                $estancia = $row_est['estancia'];
                            }
                            $stmt->close();
                        } else {
                            $sql_est = "SELECT DATEDIFF(fec_egreso, fecha) as estancia FROM dat_ingreso WHERE id_atencion = ?";
                            $stmt = $conexion->prepare($sql_est);
                            $stmt->bind_param("i", $id_atencion);
                            $stmt->execute();
                            $result_est = $stmt->get_result();
                            while ($row_est = $result_est->fetch_assoc()) {
                                $estancia = ($row_est['estancia'] == 0) ? 1 : $row_est['estancia'];
                            }
                            $stmt->close();
                        }

                        $d = "";
                        $sql_motd = "SELECT diagprob_i FROM dat_not_ingreso WHERE id_atencion = ? ORDER BY id_not_ingreso DESC LIMIT 1";
                        $stmt = $conexion->prepare($sql_motd);
                        $stmt->bind_param("i", $id_atencion);
                        $stmt->execute();
                        $result_motd = $stmt->get_result();
                        while ($row_motd = $result_motd->fetch_assoc()) {
                            $d = $row_motd['diagprob_i'];
                        }
                        $stmt->close();

                        if (!$d) {
                            $sql_motd = "SELECT diagprob_i FROM dat_nevol WHERE id_atencion = ? ORDER BY id_ne DESC LIMIT 1";
                            $stmt = $conexion->prepare($sql_motd);
                            $stmt->bind_param("i", $id_atencion);
                            $stmt->execute();
                            $result_motd = $stmt->get_result();
                            while ($row_motd = $result_motd->fetch_assoc()) {
                                $d = $row_motd['diagprob_i'];
                            }
                            $stmt->close();
                        }

                        $sql_mot = "SELECT motivo_atn FROM dat_ingreso WHERE id_atencion = ? ORDER BY motivo_atn ASC LIMIT 1";
                        $stmt = $conexion->prepare($sql_mot);
                        $stmt->bind_param("i", $id_atencion);
                        $stmt->execute();
                        $result_mot = $stmt->get_result();
                        while ($row_mot = $result_mot->fetch_assoc()) {
                            $m = $row_mot['motivo_atn'];
                        }
                        $stmt->close();

                        $sql_edo = "SELECT edo_salud FROM dat_ingreso WHERE id_atencion = ? ORDER BY edo_salud ASC LIMIT 1";
                        $stmt = $conexion->prepare($sql_edo);
                        $stmt->bind_param("i", $id_atencion);
                        $stmt->execute();
                        $result_edo = $stmt->get_result();
                        while ($row_edo = $result_edo->fetch_assoc()) {
                            $edo_salud = $row_edo['edo_salud'];
                        }
                        $stmt->close();

                        $sql_hab = "SELECT num_cama FROM cat_camas WHERE id_atencion = ?";
                        $stmt = $conexion->prepare($sql_hab);
                        $stmt->bind_param("i", $id_atencion);
                        $stmt->execute();
                        $result_hab = $stmt->get_result();
                        $num_cama = $result_hab->fetch_assoc()['num_cama'] ?? '';
                        $stmt->close();

                        $sql_hclinica = "SELECT peso, talla FROM dat_hclinica WHERE Id_exp = ? ORDER BY id_hc DESC LIMIT 1";
                        $stmt = $conexion->prepare($sql_hclinica);
                        $stmt->bind_param("s", $id_exp);
                        $stmt->execute();
                        $result_hclinica = $stmt->get_result();
                        $peso = 0;
                        $talla = 0;
                        while ($row_hclinica = $result_hclinica->fetch_assoc()) {
                            $peso = $row_hclinica['peso'] ?? 0;
                            $talla = $row_hclinica['talla'] ?? 0;
                        }
                        $stmt->close();
                    } else {
                        echo '<script type="text/javascript">window.location.href="../lista_pacientes/lista_pacientes.php";</script>';
                    }
                    ?>
                <div class="row">
                    <div class="col-sm-4">Expediente: <strong><?php echo $folio; ?></strong></div>
                    <div class="col-sm-4">Paciente:
                        <strong><?php echo $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac; ?></strong>
                    </div>
                    <div class="col-sm-4">Fecha de atención:
                        <strong><?php echo date_format(date_create($pac_fecing), "d/m/Y H:i:s"); ?></strong>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">Fecha de nacimiento:
                        <strong><?php echo date_format(date_create($pac_fecnac), "d/m/Y"); ?></strong>
                    </div>
                    <div class="col-sm-4">Edad: <strong><?php
                        $fecha_actual = date("Y-m-d");
                        $fecha_nac = $pac_fecnac;
                        $array_nacimiento = explode("-", $fecha_nac);
                        $array_actual = explode("-", $fecha_actual);
                        $anos = $array_actual[0] - $array_nacimiento[0];
                        $meses = $array_actual[1] - $array_nacimiento[1];
                        $dias = $array_actual[2] - $array_nacimiento[2];
                        if ($dias < 0) { --$meses; $dias += ($array_actual[1] == 3 && date("L", strtotime($fecha_actual)) ? 29 : 28); }
                        if ($meses < 0) { --$anos; $meses += 12; }
                        echo ($anos > 0 ? $anos . " años" : ($meses > 0 ? $meses . " meses" : $dias . " días"));
                    ?></strong></div>
                    <div class="col-sm-4">Área: <strong><?php echo $num_cama .' - '.$area;?> </strong></div> 
                </div>
                <div class="row">
                    <div class="col-sm-8">
                        <?php echo $d ? "Diagnóstico: <strong>$d</strong>" : "Motivo de atención: <strong>$m</strong>"; 
                        ?>
                    </div>

                    <div class="col-sm-4">Alergias: <strong><?php echo $alergias; ?></strong></div>
                   
                    
                </div>

            </div>
        </div>
    </div>

            <div class="container">                    <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 24px;">
                        <strong>
                            <center>HOJA DE PROGRAMACIÓN QUIRÚRGICA</center>
                        </strong>
                    </div>
                    
                    <div class="text-center mt-3 mb-3">
                        <a href="nota_registro_grafico.php" class="btn btn-info btn-lg" 
                           title="Ver gráficas de tratamientos y signos vitales. Si no hay paciente seleccionado, podrá elegir uno de la lista.">
                            <i class="fas fa-chart-line"></i> Ver Tratamientos y Gráficas de Signos Vitales
                        </a>
                        <br>
                        <small class="text-muted mt-2">
                            <i class="fas fa-info-circle"></i>
                            <?php if (isset($_SESSION['pac'])): ?>
                                Mostrará gráficas del paciente actual
                            <?php else: ?>
                                Le permitirá seleccionar un paciente con tratamientos
                            <?php endif; ?>
                        </small>
                    </div>
                
                <div class="card mt-3">
                    <div class="card-header" style="background-color: #f8f9fa; border-bottom: 2px solid #2b2d7f;">
                        <div class="form-group mb-0">
                            <label style="font-size:20px; font-weight:bold; color:#2b2d7f; margin-bottom:15px;">
                                <i class="fa-solid fa-stethoscope"></i> Seleccione los tratamientos a realizar:
                            </label>
                            <div class="row">
                                <?php
                                $sql_trat = "SELECT * FROM tratamientos ORDER BY tipo";
                                $result_trat = $conexion->query($sql_trat);
                                $contador = 0;
                                while ($row_trat = $result_trat->fetch_assoc()) {
                                    $tipo = $row_trat['tipo'];
                                    $id = $row_trat['id'];
                                    $contador++;
                                    
                                    if ($contador % 2 == 1) {
                                        echo '<div class="col-md-6 mb-3">';
                                    }
                                    
                                    echo '<div class="form-check" style="margin-bottom: 8px;">';
                                    echo '<input class="form-check-input tratamiento-checkbox" type="checkbox" value="' . $id . '" id="trat_' . $id . '" style="transform: scale(1.3); margin-right: 10px;">';
                                    echo '<label class="form-check-label" for="trat_' . $id . '" style="font-size: 16px; font-weight: 500; color: #2b2d7f; cursor: pointer;">';
                                    echo strtoupper($tipo);
                                    echo '</label>';
                                    echo '</div>';
                                    
                                    if ($contador % 2 == 0) {
                                        echo '</div>';
                                    }
                                }
                                if ($contador % 2 == 1) {
                                    echo '</div>';
                                }
                                ?>
                            </div>
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-primary btn-lg" id="btn_cargar_tratamientos" style="display:none;">
                                    <i class="fa-solid fa-clipboard-check"></i> Cargar Formularios Seleccionados
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contenedor para formularios -->
                <div id="formulario_contenedor" style="display: none;">
                    <?php
                    // Regenerar la consulta para crear los formularios
                    $result_trat = $conexion->query($sql_trat);
                    while ($row_trat = $result_trat->fetch_assoc()) {
                        $tipo = $row_trat['tipo'];
                        $nota = $row_trat['nota'];
                        $id = $row_trat['id'];                        
                        // Determinar el action del formulario según el tipo
                        $action = '';
                        switch ($tipo) {
                            case 'BLEFAROPLASTIA':
                                $action = 'insertar_hoja_blefaro.php';
                                break;
                            case 'FACOEMULSIFICACION':
                            case 'facoemulsificacion':
                            case 'Facoemulsificacion':
                                $action = 'insertar_hoja_facoemulsificacion.php';
                                break;
                            case 'CROSSLINKING':
                            case 'crosslinking':
                            case 'Crosslinking':
                                $action = 'insertar_hoja_crosslinking.php';
                                break;
                            case 'INYECCION':
                            case 'INYECCIÓN':
                            case 'inyeccion':
                            case 'inyección':
                            case 'Inyeccion':
                            case 'Inyección':
                                $action = 'insertar_hoja_inyeccion.php';
                                break;
                            case 'CHALAZION':
                            case 'chalazion':
                            case 'Chalazion':
                                $action = 'insertar_hoja_chalazion.php';
                                break;
                            case 'PTERIGIÓN':
                            case 'PTERIGION':
                            case 'pterigion':
                            case 'pterigión':
                            case 'Pterigion':
                            case 'Pterigión':
                                $action = 'insertar_hoja_pterigion.php';
                                break;
                            case 'CIRUGÍA REFRACTIVA':
                            case 'CIRUGIA REFRACTIVA':
                            case 'cirugia refractiva':
                            case 'cirugía refractiva':
                            case 'Cirugia Refractiva':
                            case 'Cirugía Refractiva':
                                $action = 'insertar_hoja_refractiva.php';
                                break;
                            case 'TRANSPLANTE':
                            case 'transplante':
                            case 'Transplante':
                                $action = 'insertar_hoja_transplante.php';
                                break;
                            case 'VALVULA DE AHMED':
                            case 'VÁLVULA DE AHMED':
                            case 'valvula de ahmed':
                            case 'válvula de ahmed':
                            case 'Valvula de Ahmed':
                            case 'Válvula de Ahmed':
                                $action = 'insertar_hoja_valvula_ahmed.php';
                                break;
                            case 'VITRECTOMIA':
                            case 'vitrectomia':
                            case 'Vitrectomia':
                                $action = 'insertar_hoja_vitrectomia.php';
                                break;
                            case 'CIRUGÍA LASIK':
                            case 'CIRUGIA LASIK':
                            case 'cirugia lasik':
                            case 'cirugía lasik':
                            case 'Cirugia Lasik':
                            case 'Cirugía Lasik':
                                $action = 'insertar_hoja_lasik.php';
                                break;
                            default:
                                $action = '#';
                                break;
                        }
                        ?>
                        
                        <div class="card formulario-tratamiento" id="formulario_<?php echo $id; ?>" style="display: none;">
                            <div class="card-header" style="background-color: #2b2d7f; color: white;">
                                <h4 class="mb-0 text-center"><?php echo strtoupper($tipo); ?></h4>
                            </div>
                            <div class="card-body">
                                <form action="<?php echo $action; ?>" method="POST" onsubmit="return checkSubmit();">
                                    <div class="form-group">
                                        <label style="font-size:16px;">Nombre del médico tratante:</label>
                                        <select class="form-control" name="medico_tratante" required>
                                            <option value="">Seleccione un médico tratante</option>
                                            <?php
                                            $sql_med = "SELECT id_usua, nombre, papell, sapell FROM reg_usuarios WHERE id_rol ='2' AND u_activo = 'SI'";
                                            $result_med = $conexion->query($sql_med);
                                            if ($result_med && $result_med->num_rows > 0) {
                                                while ($med = $result_med->fetch_assoc()) {
                                                    $nombre_med = trim($med['nombre'] . ' ' . $med['papell'] . ' ' . $med['sapell']);
                                                    echo '<option value="' . htmlspecialchars($nombre_med) . '">' . htmlspecialchars($nombre_med) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label style="font-size:16px;">Anestesiólogo:</label>
                                        <select class="form-control" name="anestesiologo" required>
                                            <option value="">Seleccione un anestesiólogo</option>
                                            <?php
                                            $sql_anes = "SELECT id_usua, nombre, papell, sapell FROM reg_usuarios WHERE cargp LIKE '%ANESTESIOLOGO%' AND u_activo = 'SI'";
                                            $result_anes = $conexion->query($sql_anes);
                                            if ($result_anes && $result_anes->num_rows > 0) {
                                                while ($anes = $result_anes->fetch_assoc()) {
                                                    $nombre_anes = trim($anes['nombre'] . ' ' . $anes['papell'] . ' ' . $anes['sapell']);
                                                    echo '<option value="' . htmlspecialchars($nombre_anes) . '">' . htmlspecialchars($nombre_anes) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label style="font-size:16px;">Anestesia:</label>
                                        <select class="form-control" name="anestesia" required>
                                            <option value="">Seleccione tipo de anestesia</option>
                                            <option value="LOCAL">LOCAL</option>
                                            <option value="SEDACIÓN">SEDACIÓN</option>
                                        </select>
                                    </div>
                                    <?php if (strtoupper($tipo) == 'CIRUGÍA LASIK' || strtoupper($tipo) == 'CIRUGIA LASIK') { ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label style="font-size:16px;">OD</label>
                                                <input type="text" class="form-control mb-1" name="od_queratometria" placeholder="QUERATOMETRIA">
                                                <input type="text" class="form-control mb-1" name="od_microqueratomo" placeholder="MICROQUERATOMO">
                                                <input type="text" class="form-control mb-1" name="od_anillo" placeholder="ANILLO">
                                                <input type="text" class="form-control mb-1" name="od_tope" placeholder="TOPE">
                                            </div>
                                            <div class="col-md-6">
                                                <label style="font-size:16px;">OI</label>
                                                <input type="text" class="form-control mb-1" name="oi_queratometria" placeholder="QUERATOMETRIA">
                                                <input type="text" class="form-control mb-1" name="oi_microqueratomo" placeholder="MICROQUERATOMO">
                                                <input type="text" class="form-control mb-1" name="oi_anillo" placeholder="ANILLO">
                                                <input type="text" class="form-control mb-1" name="oi_tope" placeholder="TOPE">
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="form-group mt-3">
                                        <label style="font-size:16px;">Signos vitales:</label>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-signos-vitales" id="tabla-signos-<?php echo $id; ?>">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 15%;">Momento</th>
                                                        <th style="width: 12%;">Presión Sistólica</th>
                                                        <th style="width: 12%;">Presión Diastólica</th>
                                                        <th style="width: 10%;">Frecuencia Cardiaca</th>
                                                        <th style="width: 10%;">Frecuencia Respiratoria</th>
                                                        <th style="width: 11%;">Saturación O2</th>
                                                        <th style="width: 12%;">Temperatura</th>
                                                        <th style="width: 8%;">Hora</th>
                                                        <th style="width: 10%;">Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><strong>Signos Vitales</strong><br><small>registro actual</small></td>
                                                        <td><input type="text" class="form-control" name="sistg" placeholder="ej: 120"></td>
                                                        <td><input type="text" class="form-control" name="diastg" placeholder="ej: 80"></td>
                                                        <td><input type="text" class="form-control" name="fcardg" placeholder="ej: 75"></td>
                                                        <td><input type="text" class="form-control" name="frespg" placeholder="ej: 20"></td>
                                                        <td><input type="text" class="form-control" name="satg" placeholder="ej: 98%"></td>
                                                        <td><input type="text" class="form-control" name="tempg" placeholder="ej: 36.5"></td>
                                                        <td><input type="time" class="form-control" name="hora_signos"></td>
                                                        <td>-</td>
                                                    </tr>
                                                    <!-- Contenedor para el botón agregar signos -->
                                                    <tr class="btn-agregar-row">
                                                        <td colspan="9" class="p-2">
                                                            <button type="button" class="btn btn-info btn-sm agregar-signos">
                                                                <i class="fa-solid fa-heart-circle-plus"></i> Agregar signos vitales adicionales
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label style="font-size:16px;">Nota de enfermería:</label>
                                        <div class="botones mb-2">
                                            <button type="button" class="btn btn-danger btn-sm grabar-nota"><i class="fas fa-microphone"></i></button>
                                            <button type="button" class="btn btn-primary btn-sm detener-nota"><i class="fas fa-microphone-slash"></i></button>
                                            <button type="button" class="btn btn-success btn-sm reproducir-nota"><i class="fas fa-play"></i></button>
                                        </div>
                                        <textarea class="form-control nota-enfermeria" rows="5" name="nota_enfermeria"><?php echo ($nota !== null ? htmlspecialchars($nota) : ''); ?></textarea>
                                    </div>
                                    <?php if (strtoupper($tipo) == 'CIRUGÍA LASIK' || strtoupper($tipo) == 'CIRUGIA LASIK') { ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label style="font-size:16px;">ENFERMERA RESPONSABLE:</label>
                                                <?php if ($rol_usuario == 3) { // Si es enfermera ?>
                                                    <input type="text" class="form-control" name="enfermera_responsable" value="<?php echo htmlspecialchars($usuario_actual); ?>" readonly style="background-color: #e9ecef;">
                                                    <small class="text-muted">Usuario actual (enfermera)</small>
                                                <?php } else { // Si es médico o admin ?>
                                                    <select class="form-control" name="enfermera_responsable" required>
                                                        <option value="">Seleccione una enfermera</option>
                                                        <?php
                                                        $sql_enf = "SELECT id_usua, nombre, papell, sapell FROM reg_usuarios WHERE id_rol = 3 AND u_activo = 'SI'";
                                                        $result_enf = $conexion->query($sql_enf);
                                                        if ($result_enf && $result_enf->num_rows > 0) {
                                                            while ($enf = $result_enf->fetch_assoc()) {
                                                                $nombre_completo = trim($enf['nombre'] . ' ' . $enf['papell'] . ' ' . $enf['sapell']);
                                                                echo '<option value="' . htmlspecialchars($nombre_completo) . '">' . htmlspecialchars($nombre_completo) . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                            <div class="col-md-6">
                                                <label style="font-size:16px;">MÉDICO RESPONSABLE:</label>
                                                <select class="form-control" name="medico_responsable" required>
                                                    <option value="">Seleccione un médico responsable</option>
                                                    <?php
                                                    $sql_med2 = "SELECT id_usua, nombre, papell, sapell FROM reg_usuarios WHERE id_rol = 2 AND u_activo = 'SI'";
                                                    $result_med2 = $conexion->query($sql_med2);
                                                    if ($result_med2 && $result_med2->num_rows > 0) {
                                                        while ($med2 = $result_med2->fetch_assoc()) {
                                                            $nombre_med2 = trim($med2['nombre'] . ' ' . $med2['papell'] . ' ' . $med2['sapell']);
                                                            echo '<option value="' . htmlspecialchars($nombre_med2) . '">' . htmlspecialchars($nombre_med2) . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="form-group">
                                            <label style="font-size:16px;">ENFERMERA RESPONSABLE:</label>
                                            <?php if ($rol_usuario == 3) { // Si es enfermera ?>
                                                <input type="text" class="form-control" name="enfermera_responsable" value="<?php echo htmlspecialchars($usuario_actual); ?>" readonly style="background-color: #e9ecef;">
                                                <small class="text-muted">Usuario actual (enfermera)</small>
                                            <?php } else { // Si es médico o admin ?>
                                                <select class="form-control" name="enfermera_responsable" required>
                                                    <option value="">Seleccione una enfermera</option>
                                                    <?php
                                                    $sql_enf2 = "SELECT id_usua, nombre, papell, sapell FROM reg_usuarios WHERE id_rol = 3 AND u_activo = 'SI'";
                                                    $result_enf2 = $conexion->query($sql_enf2);
                                                    if ($result_enf2 && $result_enf2->num_rows > 0) {
                                                        while ($enf2 = $result_enf2->fetch_assoc()) {
                                                            $nombre_completo2 = trim($enf2['nombre'] . ' ' . $enf2['papell'] . ' ' . $enf2['sapell']);
                                                            echo '<option value="' . htmlspecialchars($nombre_completo2) . '">' . htmlspecialchars($nombre_completo2) . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                    <!-- Campo hidden para asociar signos vitales con el tratamiento -->
                                    <input type="hidden" name="id_tratamiento" value="<?php echo $id; ?>">
                                    <center class="mt-3">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-save"></i> Firmar Tratamientos 
                                        </button>
                                        <button type="button" class="btn btn-danger" onclick="history.back()">
                                            <i class="fas fa-times"></i> Cancelar
                                        </button>
                                    </center>
                                </form>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>    <script>
        // Variable global para controlar el envío del formulario
        let enviandoFormulario = false;
        
        function checkSubmit() {
            if (!enviandoFormulario) {
                enviandoFormulario = true;
                return true;
            } else {
                alert("El formulario ya se está enviando");
                return false;
            }
        }

        function initVoiceRecognition() {
            document.querySelectorAll('.botones').forEach(function(botonesDiv) {
                const grabarBtn = botonesDiv.querySelector('.grabar-nota');
                const detenerBtn = botonesDiv.querySelector('.detener-nota');
                const reproducirBtn = botonesDiv.querySelector('.reproducir-nota');
                const campoNota = botonesDiv.parentElement.querySelector('.nota-enfermeria');

                if (!grabarBtn || !detenerBtn || !reproducirBtn || !campoNota) return;

                const newGrabarBtn = grabarBtn.cloneNode(true);
                const newDetenerBtn = detenerBtn.cloneNode(true);
                const newReproducirBtn = reproducirBtn.cloneNode(true);
                
                grabarBtn.parentNode.replaceChild(newGrabarBtn, grabarBtn);
                detenerBtn.parentNode.replaceChild(newDetenerBtn, detenerBtn);
                reproducirBtn.parentNode.replaceChild(newReproducirBtn, reproducirBtn);

                newReproducirBtn.addEventListener('click', () => {
                    const speech = new SpeechSynthesisUtterance(campoNota.value);
                    window.speechSynthesis.speak(speech);
                });

                let reconocimiento;
                if (window.webkitSpeechRecognition) {
                    reconocimiento = new webkitSpeechRecognition();
                    reconocimiento.lang = "es-ES";
                    reconocimiento.continuous = true;
                    reconocimiento.interimResults = false;

                    reconocimiento.onresult = (event) => {
                        const results = event.results;
                        const frase = results[results.length - 1][0].transcript;
                        campoNota.value += frase + ' ';
                    };
                }

                newGrabarBtn.addEventListener('click', () => {
                    if (reconocimiento) reconocimiento.start();
                });
                newDetenerBtn.addEventListener('click', () => {
                    if (reconocimiento) reconocimiento.abort();
                });
            });
        }

        function agregarFilaSignosVitales(btnAgregar, idTratamiento) {
            console.log('Agregando fila de signos vitales para tratamiento:', idTratamiento);
            
            // Buscar la tabla de signos vitales más cercana al botón
            var tabla = btnAgregar.closest('table');
            if (!tabla) {
                console.error('No se encontró la tabla');
                return;
            }
            var tbody = tabla.getElementsByTagName('tbody')[0];
            
            // Buscar la fila del botón agregar para insertar antes de ella
            var btnRow = btnAgregar.closest('.btn-agregar-row');
            if (!btnRow) {
                console.error('No se encontró la fila del botón');
                return;
            }
            
            // Crear nueva fila de signos durante la cirugía
            var nuevaFila = document.createElement('tr');
            nuevaFila.className = 'durante-cirugia-row';
            nuevaFila.innerHTML = `
                <td><strong>Signos Vitales</strong><br><small class="text-muted">Registro adicional #${tbody.querySelectorAll('.durante-cirugia-row').length + 1}</small></td>
                <td><input type="text" class="form-control" name="sistg" placeholder="ej: 120" required></td>
                <td><input type="text" class="form-control" name="diastg" placeholder="ej: 80" required></td>
                <td><input type="text" class="form-control" name="fcardg" placeholder="ej: 75" required></td>
                <td><input type="text" class="form-control" name="frespg" placeholder="ej: 20" required></td>
                <td><input type="text" class="form-control" name="satg" placeholder="ej: 98" required></td>
                <td><input type="text" class="form-control" name="tempg" placeholder="ej: 36.5" required></td>
                <td><input type="time" class="form-control" name="hora_signos" required></td>
                <td><button type="button" class="btn btn-primary btn-sm enviar-signos" data-tratamiento="${idTratamiento}">
                    <i class="fas fa-save"></i> Guardar
                </button></td>
            `;
            
            // Insertar la nueva fila antes de la fila del botón
            tbody.insertBefore(nuevaFila, btnRow);
            console.log('Fila agregada exitosamente');
            
            // Agregar evento al botón de guardar signos
            var btnGuardar = nuevaFila.querySelector('.enviar-signos');
            btnGuardar.addEventListener('click', function() {
                enviarSignosVitales(nuevaFila, idTratamiento);
            });
        }

        function initializarFormulario() {
            console.log('Inicializando formulario...');
            initVoiceRecognition();
            
            // Limpiar eventos existentes y agregar nuevos
            const botonesAgregar = document.querySelectorAll('.agregar-signos');
            console.log('Botones de agregar signos encontrados:', botonesAgregar.length);
            
            botonesAgregar.forEach(function(btn, index) {
                console.log('Procesando botón', index + 1);
                
                // Solo agregar evento si no está ya inicializado
                if (!btn.hasAttribute('data-initialized')) {
                    btn.setAttribute('data-initialized', 'true');
                    console.log('Botón no estaba inicializado, agregando evento');
                    
                    // Buscar el formulario más cercano para obtener el ID del tratamiento
                    var formulario = btn.closest('.formulario-tratamiento');
                    if (formulario) {
                        var idTratamiento = formulario.id.replace('formulario_', '');
                        console.log('ID del tratamiento:', idTratamiento);
                        
                        btn.addEventListener('click', function() {
                            console.log('Click en botón agregar signos para tratamiento:', idTratamiento);
                            agregarFilaSignosVitales(btn, idTratamiento);
                        });
                    } else {
                        console.error('No se encontró el formulario para el botón');
                    }
                } else {
                    console.log('Botón ya estaba inicializado');
                }
            });
            
            console.log('Inicialización completada');
        }

        function enviarSignosVitales(fila, idTratamiento) {
            const inputs = fila.querySelectorAll('input[required]');
            let datosCompletos = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    datosCompletos = false;
                    input.style.borderColor = 'red';
                } else {
                    input.style.borderColor = '';
                }
            });
            
            if (!datosCompletos) {
                alert('Por favor complete todos los campos de signos vitales');
                return;
            }
            
            // Deshabilitar el botón para evitar doble envío
            const btnGuardar = fila.querySelector('.enviar-signos');
            const textoOriginal = btnGuardar.innerHTML;
            btnGuardar.disabled = true;
            btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
            
            // Recopilar datos de la fila
            const datos = new FormData();
            inputs.forEach(input => {
                datos.append(input.name, input.value);
            });
            datos.append('id_tratamiento', idTratamiento);
            
            fetch('insertar_trans_grafico.php', {
                method: 'POST',
                body: datos
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Agregar clase de animación
                    fila.classList.add('guardando');
                    
                    // Mostrar mensaje de éxito
                    alert('Signos vitales guardados correctamente');
                    
                    // Cambiar el botón a estado guardado
                    btnGuardar.innerHTML = '<i class="fas fa-check"></i> Guardado';
                    btnGuardar.className = 'btn btn-success btn-sm btn-guardado';
                    btnGuardar.disabled = true;
                    
                    // Hacer que los inputs sean de solo lectura
                    inputs.forEach(input => {
                        input.readOnly = true;
                        input.style.backgroundColor = '#c3e6cb';
                        input.style.color = '#155724';
                        input.style.fontWeight = '500';
                    });
                    
                    // Aplicar estilo de fila guardada después de la animación
                    setTimeout(() => {
                        fila.classList.remove('guardando');
                        fila.classList.add('guardado');
                    }, 1000);
                    
                } else {
                    throw new Error(data.message || 'Error desconocido');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al guardar los signos vitales: ' + error.message);
                
                // Restaurar el botón
                btnGuardar.disabled = false;
                btnGuardar.innerHTML = textoOriginal;
            });
        }

        function recopilarDatosFormulario(formulario) {
            const datos = {};
            const inputs = formulario.querySelectorAll('input, select, textarea');
            
            // Arrays para almacenar múltiples signos vitales
            const signosVitales = [];
            
            inputs.forEach(input => {
                if (input.name) {
                    // Si es un campo de signos vitales, recopilar todos los valores
                    if (['sistg', 'diastg', 'fcardg', 'frespg', 'satg', 'tempg', 'hora_signos'].includes(input.name)) {
                        // Buscar la fila más cercana para asociar todos los signos vitales de esa fila
                        const fila = input.closest('tr');
                        if (fila && !fila.classList.contains('btn-agregar-row')) {
                            let signosActuales = signosVitales.find(signos => signos.fila === fila);
                            if (!signosActuales) {
                                signosActuales = { fila: fila };
                                signosVitales.push(signosActuales);
                            }
                            signosActuales[input.name] = input.value;
                        }
                    } else if (input.name.endsWith('[]')) {
                        const baseName = input.name.slice(0, -2);
                        if (!datos[baseName]) {
                            datos[baseName] = [];
                        }
                        datos[baseName].push(input.value);
                    } else {
                        datos[input.name] = input.value;
                    }
                }
            });
            
            // Agregar todos los signos vitales al objeto de datos
            if (signosVitales.length > 0) {
                datos.signos_vitales_multiples = signosVitales.map(signos => {
                    const { fila, ...signosLimpios } = signos;
                    return signosLimpios;
                });
                console.log('Signos vitales múltiples recopilados:', datos.signos_vitales_multiples);
            }
            
            return datos;
        }

        // Función para enviar múltiples tratamientos
        function enviarTratamientosMultiples() {
            const checkboxes = document.querySelectorAll('.tratamiento-checkbox:checked');
            
            if (checkboxes.length === 0) {
                alert('Por favor seleccione al menos un tratamiento');
                return;
            }
            
            const tratamientosSeleccionados = [];
            const datosFormularios = {};
            let formulariosIncompletos = [];
            
            checkboxes.forEach(checkbox => {
                const idTratamiento = checkbox.value;
                const formulario = document.getElementById('formulario_' + idTratamiento);
                
                if (formulario && formulario.style.display !== 'none') {
                    // Validar campos requeridos
                    const camposRequeridos = formulario.querySelectorAll('input[required], select[required]');
                    let formularioCompleto = true;
                    
                    camposRequeridos.forEach(campo => {
                        if (!campo.value.trim()) {
                            formularioCompleto = false;
                        }
                    });
                    
                    if (!formularioCompleto) {
                        const tipoTratamiento = formulario.querySelector('.card-header h4').textContent;
                        formulariosIncompletos.push(tipoTratamiento);
                    } else {
                        tratamientosSeleccionados.push(idTratamiento);
                        datosFormularios[idTratamiento] = recopilarDatosFormulario(formulario);
                    }
                }
            });
            
            if (formulariosIncompletos.length > 0) {
                alert('Por favor complete los campos requeridos en: ' + formulariosIncompletos.join(', '));
                return;
            }
            
            if (tratamientosSeleccionados.length === 0) {
                alert('No hay formularios válidos para enviar');
                return;
            }
            
            // Crear formulario oculto para enviar datos
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'insertar_tratamientos_multiples.php';
            form.style.display = 'none';
            
            // Agregar datos al formulario
            const inputTratamientos = document.createElement('input');
            inputTratamientos.type = 'hidden';
            inputTratamientos.name = 'tratamientos_seleccionados';
            inputTratamientos.value = JSON.stringify(tratamientosSeleccionados);
            form.appendChild(inputTratamientos);
            
            const inputDatos = document.createElement('input');
            inputDatos.type = 'hidden';
            inputDatos.name = 'datos_formularios';
            inputDatos.value = JSON.stringify(datosFormularios);
            form.appendChild(inputDatos);
            
            // Agregar formulario al DOM y enviarlo
            document.body.appendChild(form);
            form.submit();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.tratamiento-checkbox');
            const btnCargar = document.getElementById('btn_cargar_tratamientos');
            const contenedor = document.getElementById('formulario_contenedor');
            
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const haySeleccionados = document.querySelectorAll('.tratamiento-checkbox:checked').length > 0;
                    btnCargar.style.display = haySeleccionados ? 'block' : 'none';
                });
            });
            
            btnCargar.addEventListener('click', function() {
                const checkboxesSeleccionados = document.querySelectorAll('.tratamiento-checkbox:checked');
                const formularios = document.querySelectorAll('.formulario-tratamiento');
                
                enviandoFormulario = false;
                
                formularios.forEach(function(formulario) {
                    formulario.style.display = 'none';
                });
                
                if (checkboxesSeleccionados.length > 0) {
                    contenedor.style.display = 'block';
                    
                    checkboxesSeleccionados.forEach(checkbox => {
                        const idTratamiento = checkbox.value;
                        const formulario = document.getElementById('formulario_' + idTratamiento);
                        if (formulario) {
                            formulario.style.display = 'block';
                        }
                    });
                    
                    setTimeout(function() {
                        // Reinicializar todas las funcionalidades para los formularios recién mostrados
                        initializarFormulario();
                        
                        // Específicamente agregar eventos a los botones de agregar signos que acabamos de mostrar
                        checkboxesSeleccionados.forEach(checkbox => {
                            const idTratamiento = checkbox.value;
                            const formulario = document.getElementById('formulario_' + idTratamiento);
                            if (formulario && formulario.style.display !== 'none') {
                                const btnAgregar = formulario.querySelector('.agregar-signos');
                                if (btnAgregar && !btnAgregar.hasAttribute('data-initialized')) {
                                    btnAgregar.setAttribute('data-initialized', 'true');
                                    btnAgregar.addEventListener('click', function() {
                                        agregarFilaSignosVitales(btnAgregar, idTratamiento);
                                    });
                                }
                            }
                        });
                    }, 200);
                    
                    contenedor.scrollIntoView({ behavior: 'smooth' });
                } else {
                    contenedor.style.display = 'none';
                }
            });
            
            document.querySelectorAll('.formulario-tratamiento form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    enviarTratamientosMultiples();
                });
            });
            
            // Inicializar funcionalidades al cargar la página
            initializarFormulario();
        });

        // Prevenir menú contextual
        document.oncontextmenu = function() {
            return false;
        }
    </script>

    <footer class="main-footer">
        <div style="font-size:16px;">
            <?php include("../../template/footer.php"); ?>
        </div>
    </footer>

    <!-- FastClick -->
    <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

</body>
</html>
