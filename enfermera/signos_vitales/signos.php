<?php
session_start();
require_once '../../conexionbd.php';
$conexion = ConexionBD::getInstancia()->getConexion();
include "../header_enfermera.php";

// Asegurar que $id_atencion sea entero
$id_atencion = isset($_SESSION['pac']) ? intval($_SESSION['pac']) : 0;
$usuario_session = isset($_SESSION['login']) ? $_SESSION['login'] : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Signos Vitales</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
          integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <!-- Bootstrap 4.6.2 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/css/bootstrap.min.css"
          integrity="sha512-+YQ4bq5b+fZQq3uY2K0n6r8sQvV7/0f9g2m1N1lF8k9c8Qd6q5G1c6YwFzO1K9Aq6kJb5v2y1T9r2n6f0V3GA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap Select -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">

    <style>
        /* Cyberpunk / Futuristic theme */
        :root{
            --bg-dark: #0b0f1a;
            --card-dark: rgba(10,12,25,0.75);
            --neon-blue: #00d0ff;
            --neon-pink: #ff2dcb;
            --neon-purple: #8b5cff;
            --muted: #9aa4b2;
            --glass: rgba(255,255,255,0.03);
        }

        html,body{
            height:100%;
            background: radial-gradient( circle at 10% 10%, rgba(0,208,255,0.06), transparent 10%),
            radial-gradient( circle at 90% 90%, rgba(255,45,203,0.04), transparent 10%),
            var(--bg-dark);
            color: #e6eef8;
            font-family: "Helvetica Neue", Arial, sans-serif;
        }

        .container-fluid{
            padding: 18px;
        }

        .card-signos{
            background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
            border: 1px solid rgba(255,255,255,0.03);
            backdrop-filter: blur(6px);
            border-radius: 12px;
            box-shadow: 0 6px 30px rgba(13,20,40,0.6), 0 0 12px rgba(0,208,255,0.03) inset;
        }

        .card-header-custom{
            background: linear-gradient(90deg, rgba(0,208,255,0.12), rgba(139,92,255,0.12));
            border-bottom: 1px solid rgba(255,255,255,0.04);
            color: var(--neon-blue);
            font-weight: 700;
            letter-spacing: 0.6px;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .info-paciente {
            background: linear-gradient(90deg, rgba(10,12,25,0.6), rgba(20,22,40,0.6));
            border-left: 4px solid var(--neon-blue);
            padding: 16px;
            margin-bottom: 18px;
            border-radius: 8px;
            color: #dbeeff;
        }

        .info-paciente p { margin-bottom:6px; }
        .info-paciente strong { color: var(--neon-pink); }

        .table-header {
            background: linear-gradient(90deg, var(--neon-blue), var(--neon-purple));
            color: #041225 !important;
            font-weight: 700;
        }

        .table {
            background: transparent;
            color: #e6eef8;
        }

        .table thead th {
            border-bottom: 2px solid rgba(255,255,255,0.04);
        }

        .table tbody tr:nth-of-type(odd){
            background: linear-gradient(90deg, rgba(0,208,255,0.02), rgba(139,92,255,0.01));
        }

        .table tbody tr:hover{
            background: linear-gradient(90deg, rgba(255,45,203,0.04), rgba(0,208,255,0.03));
            transform: translateY(-1px);
        }

        /* Buttons modern */
        .btn-agregar {
            background: linear-gradient(90deg, var(--neon-blue), var(--neon-pink));
            color: #041225;
            padding: 10px 28px;
            font-weight: 700;
            border-radius: 999px;
            border: none;
            box-shadow: 0 6px 18px rgba(0,208,255,0.08);
            transition: transform .12s ease, box-shadow .12s ease;
        }
        .btn-agregar:hover {
            transform: translateY(-3px) scale(1.01);
            box-shadow: 0 10px 30px rgba(0,208,255,0.12);
            color: #fff;
        }

        .btn-secondary{
            background: linear-gradient(90deg,#1b2230,#0f1622);
            color: var(--neon-blue);
            border: 1px solid rgba(255,255,255,0.03);
        }

        .btn-danger{
            background: linear-gradient(90deg,#ff2dcb,#8b5cff);
            border:none;
            color:#041225;
            font-weight:700;
        }

        .input-group-text {
            background: linear-gradient(90deg, rgba(0,208,255,0.06), rgba(139,92,255,0.04));
            color: var(--neon-blue);
            border: 1px solid rgba(255,255,255,0.02);
        }

        .form-control {
            background: rgba(255,255,255,0.02);
            color: #e6eef8;
            border: 1px solid rgba(255,255,255,0.03);
        }

        .dolor-scale {
            width: 100%;
            max-width: 260px;
            display:block;
            margin: 8px 0;
            border-radius: 6px;
            border: 1px solid rgba(255,255,255,0.03);
        }

        .search-input {
            background: rgba(255,255,255,0.02);
            border-radius: 999px;
            border:1px solid rgba(255,255,255,0.03);
            color: #dbeeff;
        }

        /* small screens tweaks */
        @media (max-width:767px){
            .card-header-custom h5 { font-size: 14px; }
            .btn-agregar { padding:8px 16px; font-size:14px; }
        }
    </style>
</head>

<body>
<section class="content container-fluid">
    <?php
    if ($id_atencion > 0) {

        // Traer datos del paciente
        $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.tel, p.fecnac, p.tip_san, 
                        di.fecha, di.area, di.alta_med, p.sexo, di.alergias, p.folio 
                        FROM paciente p 
                        INNER JOIN dat_ingreso di ON p.Id_exp = di.Id_exp 
                        WHERE di.id_atencion = $id_atencion LIMIT 1";
        $result_pac = $conexion->query($sql_pac);
        $row_pac = $result_pac ? $result_pac->fetch_assoc() : null;

        if ($row_pac) {
            $pac_papell = $row_pac['papell'];
            $pac_sapell = $row_pac['sapell'];
            $pac_nom_pac = $row_pac['nom_pac'];
            $pac_dir = $row_pac['dir'];
            $pac_tel = $row_pac['tel'];
            $pac_fecnac = $row_pac['fecnac'];
            $pac_fecing = $row_pac['fecha'];
            $pac_tip_sang = $row_pac['tip_san'];
            $pac_sexo = $row_pac['sexo'];
            $area = $row_pac['area'];
            $id_exp = $row_pac['Id_exp'];
            $alergias = $row_pac['alergias'];
            $folio = $row_pac['folio'];
        } else {
            // Redirigir si no hay paciente (por seguridad)
            echo '<script>window.location.href = "../../template/select_pac_enf.php";</script>';
            exit;
        }

        // Calcular estancia
        $sql_ingreso = "SELECT fecha, fec_egreso, alta_med, alta_adm, activo, valida FROM dat_ingreso WHERE id_atencion = $id_atencion LIMIT 1";
        $result_ingreso = $conexion->query($sql_ingreso);
        $row_ingreso = $result_ingreso ? $result_ingreso->fetch_assoc() : null;

        $fingreso = $row_ingreso['fecha'] ?? null;
        $fegreso = $row_ingreso['fec_egreso'] ?? null;
        $alta_med = $row_ingreso['alta_med'] ?? null;
        $alta_adm = $row_ingreso['alta_adm'] ?? null;
        $activo = $row_ingreso['activo'] ?? null;

        if ($alta_med == 'SI' && $alta_adm == 'SI' && $activo == 'NO' && $fegreso) {
            $sql_est = "SELECT DATEDIFF('$fegreso', '$fingreso') as estancia FROM dat_ingreso WHERE id_atencion = $id_atencion LIMIT 1";
        } else {
            $sql_est = "SELECT DATEDIFF(NOW(), fecha) as estancia FROM dat_ingreso WHERE id_atencion = $id_atencion LIMIT 1";
        }

        $result_est = $conexion->query($sql_est);
        $row_est = $result_est ? $result_est->fetch_assoc() : null;
        $estancia = $row_est['estancia'] ?? 0;

        // Calcular edad
        function calcularEdad($fecha_nac) {
            try {
                $nacimiento = new DateTime($fecha_nac);
                $hoy = new DateTime();
                $diferencia = $hoy->diff($nacimiento);

                if ($diferencia->y > 0) {
                    return $diferencia->y . " años";
                } elseif ($diferencia->m > 0) {
                    return $diferencia->m . " meses";
                } else {
                    return $diferencia->d . " días";
                }
            } catch (Exception $e) {
                return "N/D";
            }
        }

        $edad_texto = calcularEdad($pac_fecnac);

        // Obtener peso y talla
        $peso = 0;
        $talla = 0;
        $id_hc = 0;

        $sql_hc = "SELECT * FROM dat_hclinica WHERE Id_exp = $id_exp ORDER BY id_hc DESC LIMIT 1";
        $result_hc = $conexion->query($sql_hc);
        if ($result_hc && $row_hc = $result_hc->fetch_assoc()) {
            $peso = $row_hc['peso'] ?? 0;
            $talla = $row_hc['talla'] ?? 0;
            $id_hc = $row_hc['id_hc'] ?? 0;
        }

        // Obtener habitación
        $cama = 'N/A';
        $sql_hab = "SELECT num_cama FROM cat_camas WHERE id_atencion = $id_atencion LIMIT 1";
        $result_hab = $conexion->query($sql_hab);
        if ($result_hab && $row_hab = $result_hab->fetch_assoc()) {
            $cama = $row_hab['num_cama'];
        }

        // Obtener estado de salud
        $edo_salud = 'N/A';
        $sql_edo = "SELECT edo_salud FROM dat_ingreso WHERE id_atencion = $id_atencion ORDER BY edo_salud DESC LIMIT 1";
        $result_edo = $conexion->query($sql_edo);
        if ($result_edo && $row_edo = $result_edo->fetch_assoc()) {
            $edo_salud = $row_edo['edo_salud'];
        }

        // Obtener aseguradora
        $aseguradora = 'N/A';
        $sql_aseg = "SELECT aseg FROM dat_financieros WHERE id_atencion = $id_atencion ORDER BY fecha DESC LIMIT 1";
        $result_aseg = $conexion->query($sql_aseg);
        if ($result_aseg && $row_aseg = $result_aseg->fetch_assoc()) {
            $aseguradora = $row_aseg['aseg'];
        }

        // Obtener diagnóstico o motivo
        $diagnostico = '';
        $sql_diag = "SELECT diagprob_i FROM dat_nevol WHERE id_atencion = $id_atencion ORDER BY diagprob_i ASC LIMIT 1";
        $result_diag = $conexion->query($sql_diag);
        if ($result_diag && $row_diag = $result_diag->fetch_assoc()) {
            $diagnostico = $row_diag['diagprob_i'];
        }

        if (empty($diagnostico)) {
            $sql_mot = "SELECT motivo_atn FROM dat_ingreso WHERE id_atencion = $id_atencion LIMIT 1";
            $result_mot = $conexion->query($sql_mot);
            if ($result_mot && $row_mot = $result_mot->fetch_assoc()) {
                $diagnostico = "Motivo: " . $row_mot['motivo_atn'];
            } else {
                $diagnostico = "Diagnóstico: N/A";
            }
        }
        ?>

        <div class="container-fluid">
            <!-- Botón regresar -->
            <div class="mb-3">
                <button type="button" class="btn btn-secondary" onclick="history.back()">
                    <i class="fa fa-arrow-left"></i> Regresar
                </button>
            </div>

            <!-- Título -->
            <div class="card card-signos">
                <div class="card-header card-header-custom text-center py-3">
                    <h4 class="mb-0">
                        <i class="fa fa-heartbeat"></i> REGISTRO Y CONSULTA DE SIGNOS VITALES
                    </h4>
                </div>

                <!-- Información del paciente -->
                <div class="card-body">
                    <div class="info-paciente">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Expediente:</strong> <?php echo htmlspecialchars($folio); ?></p>
                                <p><strong>Paciente:</strong> <?php echo htmlspecialchars(trim($pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac)); ?></p>
                                <p><strong>Fecha de nacimiento:</strong> <?php echo ($pac_fecnac) ? date('d-m-Y', strtotime($pac_fecnac)) : 'N/D'; ?></p>
                                <p><strong>Edad:</strong> <?php echo htmlspecialchars($edad_texto); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Área:</strong> <?php echo htmlspecialchars($area); ?></p>
                                <p><strong>Habitación:</strong> <?php echo htmlspecialchars($cama); ?></p>
                                <p><strong>Fecha de ingreso:</strong> <?php echo ($pac_fecing) ? date('d-m-Y', strtotime($pac_fecing)) : 'N/D'; ?></p>
                                <p><strong>Tiempo de estancia:</strong> <?php echo intval($estancia); ?> días</p>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <p><strong>Tipo de sangre:</strong> <?php echo htmlspecialchars($pac_tip_sang); ?></p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Peso:</strong> <?php echo htmlspecialchars($peso); ?> kg</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Talla:</strong> <?php echo htmlspecialchars($talla); ?> cm</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Género:</strong> <?php echo htmlspecialchars($pac_sexo); ?></p>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <p><strong>Alergias:</strong> <?php echo htmlspecialchars($alergias); ?></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Estado de salud:</strong> <?php echo htmlspecialchars($edo_salud); ?></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Aseguradora:</strong> <?php echo htmlspecialchars($aseguradora); ?></p>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <p><strong><?php echo htmlspecialchars($diagnostico); ?></strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario peso y talla (si es necesario) -->
            <?php if (empty($peso) && empty($talla)): ?>
                <div class="card card-signos mt-3">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0 px-3 py-2"><i class="fa fa-weight"></i> Registrar Peso y Talla</h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" class="form-inline">
                            <div class="form-row w-100">
                                <div class="form-group col-md-5 mb-2">
                                    <label class="sr-only">Peso (kg)</label>
                                    <input type="number" step="0.01" class="form-control w-100" name="peso" placeholder="Peso (kg)" required>
                                </div>
                                <div class="form-group col-md-5 mb-2">
                                    <label class="sr-only">Talla (cm)</label>
                                    <input type="number" step="0.01" class="form-control w-100" name="talla" placeholder="Talla (cm)" required>
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <button type="submit" name="btnpeso" class="btn btn-agregar w-100">
                                        <i class="fa fa-save"></i> Guardar
                                    </button>
                                </div>
                            </div>
                        </form>

                        <?php
                        if (isset($_POST['btnpeso'])) {
                            $peso_new = mysqli_real_escape_string($conexion, $_POST["peso"]);
                            $talla_new = mysqli_real_escape_string($conexion, $_POST["talla"]);

                            // Si no existe registro id_hc, insert o update según tu lógica; aquí hacemos update sólo si id_hc existe
                            if ($id_hc) {
                                $sqlp = "UPDATE dat_hclinica SET peso = '$peso_new', talla = '$talla_new' WHERE Id_exp = $id_exp AND id_hc = $id_hc";
                                $conexion->query($sqlp);
                            } else {
                                // Inserta nuevo registro en dat_hclinica (si tu tabla lo permite)
                                $sqlp_ins = "INSERT INTO dat_hclinica (Id_exp, peso, talla, fecha_registro) VALUES ($id_exp, '$peso_new', '$talla_new', NOW())";
                                $conexion->query($sqlp_ins);
                            }

                            echo '<script>window.location.href = "signos.php";</script>';
                        }
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Formulario de registro de signos vitales -->
            <div class="card card-signos mt-3">
                <div class="card-header card-header-custom">
                    <h5 class="mb-0 px-3 py-2"><i class="fa fa-plus-circle"></i> Registrar Signos Vitales</h5>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>Fecha de registro</label>
                                <input type="date" class="form-control" name="fecha"
                                       value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Hora</label>
                                <select class="form-control" name="hora_med" required>
                                    <option value="">Seleccionar hora</option>
                                    <?php for ($i = 0; $i <= 23; $i++): ?>
                                        <option value="<?php echo $i; ?>">
                                            <?php echo str_pad($i, 2, '0', STR_PAD_LEFT) . ':00'; ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Presión Arterial (mmHg)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="sist_mat"
                                           placeholder="Sistólica" min="0" max="300">
                                    <div class="input-group-prepend input-group-append">
                                        <span class="input-group-text">/</span>
                                    </div>
                                    <input type="number" class="form-control" name="diast_mat"
                                           placeholder="Diastólica" min="0" max="200">
                                </div>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Frecuencia Cardiaca (lpm)</label>
                                <input type="number" class="form-control" name="freccard_mat" min="0" max="300">
                            </div>
                        </div>

                        <div class="form-row mt-2">
                            <div class="form-group col-md-3">
                                <label>Frecuencia Respiratoria (rpm)</label>
                                <input type="number" class="form-control" name="frecresp_mat" min="0" max="100">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Temperatura (°C)</label>
                                <input type="number" step="0.1" class="form-control" name="temper_mat" min="30" max="45">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Saturación de Oxígeno (%)</label>
                                <input type="number" class="form-control" name="satoxi_mat" min="0" max="100">
                            </div>

                            <?php if (strtoupper($pac_sexo) == 'FEMENINO' || stripos($pac_sexo, 'mujer') !== false): ?>
                                <div class="form-group col-md-3">
                                    <label>Frecuencia Cardiaca Fetal</label>
                                    <input type="number" class="form-control" name="freccard_fet" min="0" max="200">
                                </div>
                            <?php else: ?>
                                <input type="hidden" name="freccard_fet" value="">
                            <?php endif; ?>
                        </div>

                        <div class="form-row mt-2">
                            <div class="form-group col-md-6">
                                <label>Nivel de Dolor (Escala EVA 0-10)</label>
                                <img src="../../imagenes/caras.png" class="dolor-scale img-fluid mb-2" alt="Escala de dolor">
                                <select class="form-control" name="niv_dolor">
                                    <option value="">Seleccionar nivel</option>
                                    <?php for ($i = 0; $i <= 10; $i++): ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <button type="submit" name="btnagregar" class="btn btn-agregar">
                                <i class="fa fa-plus"></i> Agregar Registro
                            </button>
                        </div>
                    </form>

                    <?php
                    if (isset($_POST['btnagregar'])) {
                        // Seguridad: asegurar valores y sanitizar
                        $id_usua = isset($usuario_session['id_usua']) ? intval($usuario_session['id_usua']) : 0;

                        $fecha = mysqli_real_escape_string($conexion, $_POST["fecha"]);
                        $hora_med = intval(mysqli_real_escape_string($conexion, $_POST["hora_med"] ?? 0));
                        $sist_mat = mysqli_real_escape_string($conexion, $_POST["sist_mat"] ?? '0');
                        $diast_mat = mysqli_real_escape_string($conexion, $_POST["diast_mat"] ?? '0');
                        $freccard_mat = mysqli_real_escape_string($conexion, $_POST["freccard_mat"] ?? '0');
                        $frecresp_mat = mysqli_real_escape_string($conexion, $_POST["frecresp_mat"] ?? '0');
                        $temper_mat = mysqli_real_escape_string($conexion, $_POST["temper_mat"] ?? '0');
                        $satoxi_mat = mysqli_real_escape_string($conexion, $_POST["satoxi_mat"] ?? '0');
                        $niv_dolor = mysqli_real_escape_string($conexion, $_POST["niv_dolor"] ?? '0');
                        $freccard_fet = mysqli_real_escape_string($conexion, $_POST["freccard_fet"] ?? '0');

                        // Determinar turno
                        if ($hora_med >= 8 && $hora_med <= 14) {
                            $turno = "MATUTINO";
                        } elseif ($hora_med >= 15 && $hora_med <= 21) {
                            $turno = "VESPERTINO";
                        } else {
                            $turno = "NOCTURNO";
                        }

                        $fecha_actual = date("Y-m-d H:i:s");
                        // Calculo TAM (promedio) si vienen válidos
                        $sist_num = is_numeric($sist_mat) ? floatval($sist_mat) : 0;
                        $diast_num = is_numeric($diast_mat) ? floatval($diast_mat) : 0;
                        $tam = ($sist_num + $diast_num) / 2;

                        // Campo neonato con valor por defecto
                        $neonato = 'NO';

                        // Insertar (manteniendo estilo de tu base)
                        $sql_insert = "INSERT INTO signos_vitales 
                        (id_atencion, id_usua, fecha, p_sistol, p_diastol, fcard, fresp, temper, satoxi, niv_dolor, hora, tipo, fecha_registro, fcardf, tam, neonato) 
                        VALUES ($id_atencion, $id_usua, '$fecha', '$sist_mat', '$diast_mat', '$freccard_mat', '$frecresp_mat', '$temper_mat', '$satoxi_mat', '$niv_dolor', $hora_med, '" . $conexion->real_escape_string($area) . "', '$fecha_actual', '$freccard_fet', '$tam', '$neonato')";

                        if ($conexion->query($sql_insert)) {
                            // Redirigir para refrescar listado
                            echo '<script>window.location.href = "signos.php";</script>';
                            exit;
                        } else {
                            echo '<div class="alert alert-danger mt-3">Error al registrar: ' . htmlspecialchars($conexion->error) . '</div>';
                        }
                    }
                    ?>
                </div>
            </div>

            <!-- Tabla de registros -->
            <div class="card card-signos mt-3">
                <div class="card-header card-header-custom">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-0"><i class="fa fa-list"></i> Historial de Signos Vitales</h5>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control search-input" id="search" placeholder="Buscar...">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered" id="mytable">
                            <thead class="table-header">
                            <tr>
                                <th class="text-center"><i class="fa fa-file-pdf"></i> PDF</th>
                                <th>Fecha de Registro</th>
                                <th>Fecha de Reporte</th>
                                <th>Tipo/Área</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            // Agrupar por fecha (tu lógica original). Asegurar id_atencion es entero.
                            $sql_registros = "SELECT 
                                                MAX(id_sig) as id_sig,
                                                fecha,
                                                id_atencion,
                                                MAX(id_usua) as id_usua,
                                                MAX(tipo) as tipo,
                                                MAX(fecha_registro) as fecha_registro
                                             FROM signos_vitales 
                                             WHERE id_atencion = $id_atencion 
                                             GROUP BY fecha, id_atencion 
                                             ORDER BY MAX(id_sig) DESC";
                            $result_registros = $conexion->query($sql_registros);

                            if ($result_registros && $result_registros->num_rows > 0):
                                while ($row_reg = $result_registros->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            <a href="../signos_vitales/signos_vitales.php?id_ord=<?php echo intval($row_reg['id_sig']); ?>&id_atencion=<?php echo intval($row_reg['id_atencion']); ?>&id_usua=<?php echo isset($usuario_session['id_usua']) ? intval($usuario_session['id_usua']) : 0; ?>&fecha=<?php echo urlencode($row_reg['fecha']); ?>&idexp=<?php echo intval($id_exp); ?>"
                                               target="_blank" class="btn btn-danger btn-sm">
                                                <i class="fa fa-file-pdf"></i> Ver PDF
                                            </a>
                                        </td>
                                        <td><?php echo ($row_reg['fecha_registro']) ? date('d-m-Y H:i', strtotime($row_reg['fecha_registro'])) : '-'; ?></td>
                                        <td><?php echo ($row_reg['fecha']) ? date('d-m-Y', strtotime($row_reg['fecha'])) : '-'; ?></td>
                                        <td><?php echo htmlspecialchars($row_reg['tipo']); ?></td>
                                    </tr>
                                <?php
                                endwhile;
                            else:
                                ?>
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <p class="text-muted mt-3">
                                            <i class="fa fa-info-circle"></i>
                                            No hay registros de signos vitales para este paciente
                                        </p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <?php
    } else {
        echo '<script>window.location.href = "../../template/select_pac_enf.php";</script>';
    }
    ?>
</section>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"
        integrity="sha256-/xUj+3OJ+Y3k9T+6vJbQxX0M+5a4e1Q4g/8bQ0C4Y4k=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"
        integrity="sha512-+q9mX8Yk7eG8z1bJ5H3y6/0w6l3c2V9a5g3z7vN2s4Y3=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/js/bootstrap.min.js"
        integrity="sha512-+q6y6+g5Y4d6s3k1Z0b9xP7F8q1J8v5E3Y6n3b1P9r2j6k3=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script>
    // Búsqueda en tabla
    $(document).ready(function() {
        $("#search").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#mytable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
</body>
</html>
