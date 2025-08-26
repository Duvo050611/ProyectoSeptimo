<?php
session_start();
include "../../conexionbd.php";
include "../../sauxiliares/header_farmaciah.php";

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Verifica si existe id_atencion en la sesión
if (!isset($_SESSION['id_atencion'])) {
    echo "Error: No se ha encontrado 'id_atencion'.";
    exit;
}

$id_atencion = $_SESSION['id_atencion'];

// Obtener información del paciente
$resultado_paciente = $conexion->query("
    SELECT pac.sapell, pac.papell, pac.nom_pac, pac.fecnac, di.fecha, di.alergias, di.motivo_atn, di.Id_exp 
    FROM paciente pac 
    JOIN dat_ingreso di ON pac.Id_exp = di.Id_exp 
    WHERE di.id_atencion = $id_atencion
") or die($conexion->error);

$paciente = $resultado_paciente->fetch_assoc();

if (!$paciente) {
    echo "Error: Paciente no encontrado.";
    exit;
}

$fecha_ingreso = $paciente['fecha'];
$fecha_nacimiento = $paciente['fecnac'];
$diagnostico = $paciente['motivo_atn'];
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
            --color-borde: #e8ebff;
            --sombra: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        body {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8ebff 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .container-moderno {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin: 20px auto;
            max-width: 900px;
            box-shadow: var(--sombra);
            border: 2px solid var(--color-borde);
        }

        h2 {
            color: var(--color-primario);
            font-weight: 700;
            text-align: center;
            margin-bottom: 20px;
        }

        h4 {
            color: var(--color-secundario);
            font-weight: 600;
            margin-top: 15px;
        }

        .info-bloque p {
            margin: 5px 0;
            font-size: 15px;
        }

        .btn-moderno {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: var(--sombra);
        }

        .btn-regresar {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white !important;
        }

        .btn-pdf {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white !important;
        }

        .btn-opcion {
            background: linear-gradient(135deg, #2b2d7f 0%, #1a1c5a 100%);
            color: white !important;
        }

        .btn-moderno:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            text-decoration: none;
        }

        .code-info {
            font-size: 14px;
            color: gray;
            margin-bottom: 15px;
            text-align: right;
        }

        @media (max-width: 768px) {
            .container-moderno {
                margin: 10px;
                padding: 20px;
            }
            h2 {
                font-size: 22px;
            }
            .btn-moderno {
                font-size: 14px;
                padding: 8px 16px;
            }
        }
    </style>
</head>

<body>
<div class="container-moderno">
    <div class="code-info">
        <p>Código: FO-VEN20-FAR-001 | VERSIÓN: NUEVO</p>
    </div>

    <div class="d-flex justify-content-between mb-3">
        <a href="conc_de_ingreso.php" class="btn-moderno btn-regresar">⬅ Regresar</a>
        <a href="generear_conc_pdf.php" class="btn-moderno btn-pdf" target="_blank">⬇ Descargar PDF</a>
    </div>

    <h2>CONCILIACIÓN DE INGRESO</h2>

    <h4>Paciente: <?php echo $paciente['papell'].' '.$paciente['sapell'].' '.$paciente['nom_pac']; ?></h4>

    <div class="info-bloque">
        <p><strong>Fecha de Nacimiento:</strong> <?php echo $fecha_nacimiento; ?></p>
        <p><strong>ID Atención:</strong> <?php echo $id_atencion; ?></p>
        <p><strong>ID Expediente:</strong> <?php echo $paciente['Id_exp']; ?></p>
        <p><strong>Fecha de Ingreso:</strong> <?php echo $fecha_ingreso; ?></p>
        <p><strong>Alergias:</strong> <?php echo $paciente['alergias']; ?></p>
        <p><strong>Diagnóstico:</strong> <?php echo $diagnostico; ?></p>
    </div>

    <div class="mt-4 d-flex flex-wrap gap-2">
        <a href="enf_concomitantes.php" class="btn-moderno btn-opcion">Enfermedades Concomitantes</a>
        <a href="trat_farmacologico.php" class="btn-moderno btn-opcion">Tratamiento Farmacológico</a>
        <!-- <a href="cont_tratamiento.php" class="btn-moderno btn-opcion">Continuidad de Tratamiento Crónico</a> -->
    </div>
</div>
</body>
</html>
