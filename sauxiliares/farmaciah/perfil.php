<?php
session_start();
include "../../conexionbd.php";
include "../../sauxiliares/header_farmaciah.php";

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

if (!isset($_SESSION['id_atencion'])) {
    echo "Error: No se ha encontrado 'id_atencion'.";
    exit;
}

$id_atencion = $_SESSION['id_atencion'];

$resultado_paciente = $conexion->query("
    SELECT pac.sapell, pac.papell, pac.nom_pac, pac.fecnac, pac.edad, pac.sexo, di.area, di.fecha, di.alergias, di.motivo_atn 
    FROM paciente pac 
    JOIN dat_ingreso di ON pac.Id_exp = di.Id_exp 
    WHERE di.id_atencion = '$id_atencion'
") or die($conexion->error);

$paciente = $resultado_paciente->fetch_assoc();

$fecha_ingreso   = $paciente['fecha'];
$fecha_nacimiento= $paciente['fecnac'];
$diagnostico     = $paciente['motivo_atn'];
$sexo            = $paciente['sexo'];
$edad            = $paciente['edad'];
$motivo_ingreso  = $paciente['area'];

$enfermedades=NULL;
$resultado_enfermedades = $conexion->query("
    SELECT diabetes_tipo, diabetes_detalle, hipertension, hipotiroidismo, insuficiencia_renal, 
           depresion_ansiedad, enfermedad_prostata, epoc, insuficiencia_cardiaca, obesidad, 
           artritis, cancer, otro_enfermedad 
    FROM enf_concomitantes 
    WHERE id_atencion = '$id_atencion' 
") or die($conexion->error);

$enfermedades = $resultado_enfermedades->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INEO Metepec</title>
    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <link href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet"
          integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="js/select2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldLv/Pr4nhuBviF5jGqQK/5i2Q5iZ64dxBl+zOZ" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/main.js"></script>

    <style>
        :root {
            --color-primario: #2b2d7f;
            --color-secundario: #1a1c5a;
            --color-fondo: #f8f9ff;
            --sombra: 0 4px 15px rgba(0,0,0,0.1);
            --borde: #e8ebff;
        }

        body {
            background: linear-gradient(135deg, #f8f9ff, #eef0ff);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container-moderno {
            background: #fff;
            border-radius: 20px;
            padding: 20px;
            margin: 20px auto;
            max-width: 1100px;
            box-shadow: var(--sombra);
            border: 2px solid var(--borde);
        }

        .header-section {
            background: linear-gradient(135deg, var(--color-primario), var(--color-secundario));
            color: white;
            padding: 1.8rem;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 25px;
        }

        .header-section h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }

        .info-item {
            background: #fff;
            border: 1px solid var(--borde);
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        .info-item:hover { transform: translateY(-3px); }
        .info-icon { color: var(--color-primario); margin-right: 6px; }

        .diseases-section {
            margin-top: 30px;
        }
        .diseases-title {
            color: var(--color-primario);
            font-weight: 600;
            border-bottom: 2px solid var(--color-primario);
            margin-bottom: 15px;
            padding-bottom: 5px;
        }

        .diseases-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 12px;
        }

        .disease-item {
            background: var(--color-fondo);
            border-left: 4px solid var(--color-primario);
            padding: 12px;
            border-radius: 10px;
            font-size: 14px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .disease-item:hover {
            transform: translateY(-2px);
            box-shadow: var(--sombra);
        }

        .btn-moderno {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: var(--sombra);
            text-align: center;
        }
        .btn-regresar {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: #fff !important;
        }
        .btn-continuar {
            background: linear-gradient(135deg, var(--color-primario), var(--color-secundario));
            color: #fff !important;
            display: block;
            max-width: 280px;
            margin: 25px auto 0;
        }
        .btn-moderno:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.2);
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container-moderno">
    <div class="d-flex justify-content-between mb-3">
        <a href="select_pac.php" class="btn-moderno btn-regresar">⬅ Regresar</a>
    </div>

    <div class="header-section">
        <h1>Perfil del Paciente</h1>
    </div>

    <div class="info-grid">
        <div class="info-item"><i class="fas fa-user info-icon"></i><strong>Nombre:</strong> <?php echo $paciente['nom_pac'].' '.$paciente['papell'].' '.$paciente['sapell']; ?></div>
        <div class="info-item"><i class="fas fa-calendar-alt info-icon"></i><strong>Nacimiento:</strong> <?php echo $fecha_nacimiento; ?></div>
        <div class="info-item"><i class="fas fa-venus-mars info-icon"></i><strong>Sexo:</strong> <?php echo $sexo; ?></div>
        <div class="info-item"><i class="fas fa-birthday-cake info-icon"></i><strong>Edad:</strong> <?php echo $edad; ?></div>
        <div class="info-item"><i class="fas fa-hospital info-icon"></i><strong>Área ingreso:</strong> <?php echo $motivo_ingreso; ?></div>
        <div class="info-item"><i class="fas fa-calendar-day info-icon"></i><strong>Fecha ingreso:</strong> <?php echo $fecha_ingreso; ?></div>
        <div class="info-item"><i class="fas fa-info-circle info-icon"></i><strong>Diagnóstico:</strong> <?php echo $diagnostico; ?></div>
    </div>

    <div class="diseases-section">
        <h2 class="diseases-title">Enfermedades Concomitantes</h2>
        <div class="diseases-grid">
            <?php if ($enfermedades): ?>
                <div class="disease-item"><strong>Diabetes:</strong> <?php echo $enfermedades['diabetes_tipo'] ? 'Sí' : 'No'; ?></div>
                <div class="disease-item"><strong>Hipertensión:</strong> <?php echo $enfermedades['hipertension'] ? 'Sí' : 'No'; ?></div>
                <div class="disease-item"><strong>Hipotiroidismo:</strong> <?php echo $enfermedades['hipotiroidismo'] ? 'Sí' : 'No'; ?></div>
                <div class="disease-item"><strong>Insuficiencia Renal:</strong> <?php echo $enfermedades['insuficiencia_renal'] ? 'Sí' : 'No'; ?></div>
                <div class="disease-item"><strong>Depresión/Ansiedad:</strong> <?php echo $enfermedades['depresion_ansiedad'] ? 'Sí' : 'No'; ?></div>
                <div class="disease-item"><strong>Enfermedad Prostática:</strong> <?php echo $enfermedades['enfermedad_prostata'] ? 'Sí' : 'No'; ?></div>
                <div class="disease-item"><strong>EPOC:</strong> <?php echo $enfermedades['epoc'] ? 'Sí' : 'No'; ?></div>
                <div class="disease-item"><strong>Insuficiencia Cardíaca:</strong> <?php echo $enfermedades['insuficiencia_cardiaca'] ? 'Sí' : 'No'; ?></div>
                <div class="disease-item"><strong>Obesidad:</strong> <?php echo $enfermedades['obesidad'] ? 'Sí' : 'No'; ?></div>
                <div class="disease-item"><strong>Artritis:</strong> <?php echo $enfermedades['artritis'] ? 'Sí' : 'No'; ?></div>
                <div class="disease-item"><strong>Cáncer:</strong> <?php echo $enfermedades['cancer'] ? 'Sí' : 'No'; ?></div>
                <div class="disease-item"><strong>Otra:</strong> <?php echo $enfermedades['otro_enfermedad']; ?></div>
            <?php else: ?>
                <p>No se encontraron enfermedades concomitantes.</p>
            <?php endif; ?>
        </div>
    </div>

    <a href="deteccion_de_prm.php" class="btn-moderno btn-continuar">Continuar ➡</a>
</div>
</body>
</html>
