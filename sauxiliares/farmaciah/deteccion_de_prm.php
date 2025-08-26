<?php
session_start();
include "../../conexionbd.php";
include "../header_farmaciah.php";

$id_atencion = $_SESSION['id_atencion'];

$resultado_paciente = $conexion->query("SELECT pac.sapell, pac.papell, pac.nom_pac, pac.fecnac, pac.edad, pac.sexo, di.area, di.fecha, di.alergias, di.motivo_atn 
    FROM paciente pac 
    JOIN dat_ingreso di ON pac.Id_exp = di.Id_exp 
    WHERE di.id_atencion = $id_atencion") or die($conexion->error);

$paciente = $resultado_paciente->fetch_assoc();

if (!$paciente) {
    die("No se encontró información del paciente.");
}

$fecha_ingreso = $paciente['fecha'];
$fecha_nacimiento = $paciente['fecnac'];
$diagnostico = $paciente['motivo_atn'];
$sexo = $paciente['sexo'];
$edad = $paciente['edad'];
$motivo_ingreso = $paciente['area'];

$resultado_enfermedades = $conexion->query("SELECT diabetes_tipo, diabetes_detalle, hipertension, hipotiroidismo, insuficiencia_renal, 
    depresion_ansiedad, enfermedad_prostata, epoc, insuficiencia_cardiaca, obesidad, 
    artritis, cancer, otro_enfermedad FROM enf_concomitantes WHERE id_atencion = (SELECT Id_atencion FROM dat_ingreso WHERE id_atencion = $id_atencion)") or die($conexion->error);

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
            --color-borde: #e8ebff;
            --sombra: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* ===== ESTILOS GENERALES ===== */
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
            max-width: 98%;
            box-shadow: var(--sombra);
            border: 2px solid var(--color-borde);
            animation: fadeInUp 0.6s ease-out;
        }

        /* ===== CÓDIGO INFO ===== */
        .code-info {
            text-align: right;
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 20px;
            padding: 10px 0;
            border-bottom: 2px solid var(--color-borde);
        }

        .code-info p {
            margin: 0;
            font-weight: 500;
        }

        /* ===== BOTONES MODERNOS ===== */
        .btn-moderno {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: var(--sombra);
            margin: 0 5px;
        }

        .btn-regresar {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white !important;
        }

        .btn-descargar {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white !important;
        }

        .btn-custom {
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            color: white !important;
        }

        .btn-moderno:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            text-decoration: none;
            color: white;
        }

        /* ===== HEADER PRINCIPAL ===== */
        .header-principal {
            text-align: center;
            margin-bottom: 30px;
            padding: 30px 0;
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            border-radius: 20px;
            color: white;
            box-shadow: var(--sombra);
            position: relative;
        }

        .header-principal .icono-principal {
            font-size: 48px;
            margin-bottom: 15px;
            display: block;
        }

        .header-principal h1 {
            font-size: 32px;
            font-weight: 700;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* ===== TARJETAS MODERNAS ===== */
        .card-moderna {
            background: white;
            border: 2px solid var(--color-borde);
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: var(--sombra);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card-moderna:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .card-header-moderna {
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            color: white;
            padding: 20px;
            border: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-header-moderna h4 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
        }

        .card-header-moderna i {
            font-size: 28px;
        }

        .card-body-moderna {
            background: #fafbff;
            padding: 25px;
        }

        .info-row {
            margin-bottom: 15px;
            padding: 10px 0;
            border-bottom: 1px solid #f1f3f4;
        }

        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .info-label {
            font-weight: 700;
            color: var(--color-primario);
            margin-bottom: 5px;
            display: block;
        }

        .info-value {
            color: #495057;
            font-size: 16px;
        }

        /* ===== SECCIÓN DE BOTONES ===== */
        .botones-navegacion {
            text-align: center;
            margin-top: 40px;
            padding: 30px 0;
            background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
            border-radius: 15px;
            border: 2px solid var(--color-borde);
        }

        .botones-navegacion .btn-moderno {
            margin: 10px;
            min-width: 200px;
        }

        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 768px) {
            .container-moderno {
                margin: 10px;
                padding: 20px;
                border-radius: 15px;
            }

            .header-principal h1 {
                font-size: 24px;
            }

            .header-principal .icono-principal {
                font-size: 36px;
            }

            .btn-moderno {
                padding: 10px 16px;
                font-size: 14px;
                margin: 5px 2px;
                min-width: auto;
            }

            .card-header-moderna h4 {
                font-size: 18px;
            }

            .botones-navegacion .btn-moderno {
                display: block;
                margin: 10px auto;
                max-width: 280px;
            }

            .info-label {
                font-size: 14px;
            }

            .info-value {
                font-size: 15px;
            }
        }

        /* ===== ANIMACIONES ===== */
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

        .card-moderna {
            animation: fadeInUp 0.6s ease-out 0.1s both;
        }

        .botones-navegacion {
            animation: fadeInUp 0.6s ease-out 0.2s both;
        }
    </style>
</head>

<body>
<div class="container-moderno">
    <div class="code-info">
        <p><i class="fas fa-code"></i> Código: FO-VEN20-FAR-001</p>
        <p><i class="fas fa-tag"></i> VERSIÓN: NUEVO</p>
    </div>

    <div class="mb-4">
        <a href="perfil.php" class="btn-moderno btn-regresar">
            <i class="fas fa-arrow-left"></i> Regresar
        </a>
        <a href="pdf.php" class="btn-moderno btn-descargar">
            <i class="fas fa-file-pdf"></i> Descargar PDF
        </a>
    </div>

    <div class="header-principal">
        <i class="fas fa-user-md icono-principal"></i>
        <h1>PERFIL FARMACOTERAPÉUTICO</h1>
    </div>

    <div class="card-moderna">
        <div class="card-header-moderna">
            <i class="fas fa-user-circle"></i>
            <h4>Datos del Paciente</h4>
        </div>
        <div class="card-body-moderna">
            <div class="row">
                <div class="col-md-6">
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-user"></i> Nombre:</span>
                        <span class="info-value"><?php echo $paciente['papell'] . ' ' . $paciente['sapell'] . ' ' . $paciente['nom_pac']; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-calendar-alt"></i> Fecha de Nacimiento:</span>
                        <span class="info-value"><?php echo $fecha_nacimiento; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-birthday-cake"></i> Edad:</span>
                        <span class="info-value"><?php echo $edad; ?> años</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-venus-mars"></i> Sexo:</span>
                        <span class="info-value"><?php echo $sexo; ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-calendar-check"></i> Fecha de Ingreso:</span>
                        <span class="info-value"><?php echo $fecha_ingreso; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-hospital"></i> Motivo de Ingreso:</span>
                        <span class="info-value"><?php echo $motivo_ingreso; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-exclamation-triangle"></i> Alergias:</span>
                        <span class="info-value"><?php echo $paciente['alergias']; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-stethoscope"></i> Diagnóstico:</span>
                        <span class="info-value"><?php echo $diagnostico; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="botones-navegacion">
        <a href="prm_identificacion.php" class="btn-moderno btn-custom">
            <i class="fas fa-search"></i> Detección de PRM
        </a>
        <a href="soluciones.php" class="btn-moderno btn-custom">
            <i class="fas fa-lightbulb"></i> Soluciones
        </a>
        <a href="medicamentos.php" class="btn-moderno btn-custom">
            <i class="fas fa-pills"></i> Medicamentos
        </a>
    </div>

</div>
</body>
</html>