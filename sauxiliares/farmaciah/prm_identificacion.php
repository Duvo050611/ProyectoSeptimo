<?php
session_start();
include "../../conexionbd.php";
include "../../sauxiliares/header_farmaciah.php";

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $fecha = $_POST['fecha'];
    $medicamento = $_POST['medicamento'];
    $farmaceutico = $_POST['farmaceutico'];
    $resultado = $_POST['resultado'];

    // Manejo de situación
    if ($_POST['situacion'] == 'Otro' && !empty($_POST['situacion_otro'])) {
        $situacion = $_POST['situacion_otro'];
    } else {
        $situacion = $_POST['situacion'];
    }

    // Manejo de causa
    if ($_POST['causa'] == 'Otro' && !empty($_POST['causa_otro'])) {
        $causa = $_POST['causa_otro'];
    } else {
        $causa = $_POST['causa'];
    }

    // Preparar la consulta SQL
    $query = "INSERT INTO registro_prm (fecha, medicamento, farmaceutico, resultado, situacion, causa) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);

    if ($stmt) {
        // Vincular los parámetros
        $stmt->bind_param("ssssss", $fecha, $medicamento, $farmaceutico, $resultado, $situacion, $causa);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "<script>alert('Registro guardado exitosamente.'); window.location.href='prm_identificacion.php';</script>";
            exit;
        } else {
            echo "<script>alert('Error al guardar el registro: " . $stmt->error . "'); window.history.back();</script>";
            exit;
        }

        $stmt->close();
    } else {
        echo "<script>alert('Error en la preparación de la consulta: " . $conexion->error . "'); window.history.back();</script>";
        exit;
    }
}
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
            margin: 5px;
        }

        .btn-regresar {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white !important;
        }

        .btn-custom {
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            color: white !important;
        }

        .btn-guardar {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white !important;
            min-width: 180px;
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

        /* ===== NAVEGACIÓN PRM ===== */
        .navegacion-prm {
            text-align: center;
            margin: 30px 0;
            padding: 25px;
            background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
            border-radius: 15px;
            border: 2px solid var(--color-borde);
        }

        /* ===== FORMULARIO MODERNO ===== */
        .form-group-moderno {
            margin-bottom: 25px;
        }

        .form-label-moderno {
            font-weight: 700;
            color: var(--color-primario);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
        }

        .form-control {
            border: 2px solid var(--color-borde);
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .form-control:focus {
            border-color: var(--color-primario);
            box-shadow: 0 0 0 3px rgba(12, 103, 94, 0.1);
            outline: none;
        }

        /* ===== RADIO BUTTONS MODERNOS ===== */
        .radio-group-moderna {
            background: #f8faff;
            border: 2px solid var(--color-borde);
            border-radius: 12px;
            padding: 20px;
            margin-top: 10px;
        }

        .radio-item-moderna {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 12px;
            background: white;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .radio-item-moderna:hover {
            background: var(--color-fondo);
            border-color: var(--color-primario);
        }

        .radio-item-moderna input[type="radio"] {
            margin-right: 12px;
            width: 18px;
            height: 18px;
            accent-color: var(--color-primario);
        }

        .radio-item-moderna label {
            margin: 0;
            cursor: pointer;
            font-weight: 500;
            color: #495057;
            flex: 1;
        }

        .campo-otro {
            margin-top: 15px;
            padding: 15px;
            background: white;
            border: 2px dashed var(--color-borde);
            border-radius: 10px;
            display: none;
        }

        /* ===== SECCIÓN DE BOTÓN GUARDAR ===== */
        .contenedor-guardar {
            text-align: center;
            margin-top: 40px;
            padding: 30px;
            background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
            border-radius: 15px;
            border: 2px solid var(--color-borde);
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

            .navegacion-prm .btn-moderno {
                display: block;
                margin: 10px auto;
                max-width: 280px;
            }

            .radio-item-moderna {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .radio-item-moderna input[type="radio"] {
                margin-right: 0;
                margin-bottom: 5px;
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

        .navegacion-prm {
            animation: fadeInUp 0.6s ease-out 0.2s both;
        }

        .contenedor-guardar {
            animation: fadeInUp 0.6s ease-out 0.3s both;
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
        <a href="deteccion_de_prm.php" class="btn-moderno btn-regresar">
            <i class="fas fa-arrow-left"></i> Regresar
        </a>
    </div>

    <div class="header-principal">
        <i class="fas fa-search-plus icono-principal"></i>
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

    <div class="navegacion-prm">
        <a href="prm_identificacion.php" class="btn-moderno btn-custom">
            <i class="fas fa-search"></i> Detección de PRM (Identificación)
        </a>
        <a href="prm_accion.php" class="btn-moderno btn-custom">
            <i class="fas fa-cogs"></i> Detección de PRM (Acción)
        </a>
        <a href="prm_resultado.php" class="btn-moderno btn-custom">
            <i class="fas fa-chart-line"></i> Detección de PRM (Resultado)
        </a>
    </div>

    <div class="card-moderna">
        <div class="card-header-moderna">
            <i class="fas fa-clipboard-check"></i>
            <h4>Registro de PRM (IDENTIFICACIÓN)</h4>
        </div>
        <div class="card-body-moderna">
            <form action="" method="POST">
                <div class="form-group-moderno">
                    <label class="form-label-moderno">
                        <i class="fas fa-calendar"></i> Fecha:
                    </label>
                    <input type="date" class="form-control" id="fecha" name="fecha" required>
                </div>

                <div class="form-group-moderno">
                    <label class="form-label-moderno">
                        <i class="fas fa-pills"></i> Medicamento:
                    </label>
                    <input type="text" class="form-control" name="medicamento" placeholder="Ingrese el nombre del medicamento" required>
                </div>

                <div class="form-group-moderno">
                    <label class="form-label-moderno">
                        <i class="fas fa-user-md"></i> Farmacéutico:
                    </label>
                    <input type="text" class="form-control" name="farmaceutico" placeholder="Nombre del farmacéutico responsable" required>
                </div>

                <div class="form-group-moderno">
                    <label class="form-label-moderno">
                        <i class="fas fa-clipboard-list"></i> Resultado:
                    </label>
                    <div class="radio-group-moderna">
                        <div class="radio-item-moderna">
                            <input type="radio" id="resultado1" name="resultado" value="Problema de salud" required>
                            <label for="resultado1">Problema de salud</label>
                        </div>
                        <div class="radio-item-moderna">
                            <input type="radio" id="resultado2" name="resultado" value="Efecto de medicamento">
                            <label for="resultado2">Efecto de medicamento</label>
                        </div>
                        <div class="radio-item-moderna">
                            <input type="radio" id="resultado3" name="resultado" value="Inefectividad no cuantitativa">
                            <label for="resultado3">Inefectividad no cuantitativa</label>
                        </div>
                        <div class="radio-item-moderna">
                            <input type="radio" id="resultado4" name="resultado" value="Incapacidad del sistema">
                            <label for="resultado4">Incapacidad del sistema</label>
                        </div>
                        <div class="radio-item-moderna">
                            <input type="radio" id="resultado5" name="resultado" value="Incapacidad del paciente">
                            <label for="resultado5">Incapacidad del paciente</label>
                        </div>
                    </div>
                </div>

                <div class="form-group-moderno">
                    <label class="form-label-moderno">
                        <i class="fas fa-exclamation-circle"></i> Situación:
                    </label>
                    <div class="radio-group-moderna">
                        <div class="radio-item-moderna">
                            <input type="radio" id="situacion1" name="situacion" value="Problema manisfestado" required>
                            <label for="situacion1">Problema manifestado</label>
                        </div>
                        <div class="radio-item-moderna">
                            <input type="radio" id="situacion2" name="situacion" value="Riesgo de aparicion">
                            <label for="situacion2">Riesgo de aparición</label>
                        </div>
                        <div class="radio-item-moderna">
                            <input type="radio" id="situacion3" name="situacion" value="Otro">
                            <label for="situacion3">Otro</label>
                        </div>
                    </div>
                    <div class="campo-otro" id="situacion_otro_div">
                        <input type="text" id="situacion_otro" name="situacion_otro" class="form-control"
                               placeholder="Especifica la situación">
                    </div>
                </div>

                <div class="form-group-moderno">
                    <label class="form-label-moderno">
                        <i class="fas fa-search"></i> Causa:
                    </label>
                    <div class="radio-group-moderna">
                        <div class="radio-item-moderna">
                            <input type="radio" id="causa1" name="causa" value="Interacccion" required>
                            <label for="causa1">Interacción</label>
                        </div>
                        <div class="radio-item-moderna">
                            <input type="radio" id="causa2" name="causa" value="Incumplimiento">
                            <label for="causa2">Incumplimiento</label>
                        </div>
                        <div class="radio-item-moderna">
                            <input type="radio" id="causa3" name="causa" value="Duplicidad">
                            <label for="causa3">Duplicidad</label>
                        </div>
                        <div class="radio-item-moderna">
                            <input type="radio" id="causa4" name="causa" value="Otro">
                            <label for="causa4">Otro</label>
                        </div>
                    </div>
                    <div class="campo-otro" id="causa_otro_div">
                        <input type="text" id="causa_otro" name="causa_otro" class="form-control"
                               placeholder="Especifica la causa">
                    </div>
                </div>

                <div class="contenedor-guardar">
                    <button type="submit" class="btn-moderno btn-guardar">
                        <i class="fas fa-save"></i> Guardar Registro
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Script para mostrar/ocultar campos de "Otro"
    document.getElementById('situacion3').addEventListener('click', function() {
        document.getElementById('situacion_otro_div').style.display = 'block';
    });

    document.getElementById('causa4').addEventListener('click', function() {
        document.getElementById('causa_otro_div').style.display = 'block';
    });

    // Ocultar campos de "Otro" cuando se seleccionen otras opciones
    document.querySelectorAll('input[name="situacion"]').forEach(function(radio) {
        radio.addEventListener('click', function() {
            if (this.value !== 'Otro') {
                document.getElementById('situacion_otro_div').style.display = 'none';
                document.getElementById('situacion_otro').value = '';
            }
        });
    });

    document.querySelectorAll('input[name="causa"]').forEach(function(radio) {
        radio.addEventListener('click', function() {
            if (this.value !== 'Otro') {
                document.getElementById('causa_otro_div').style.display = 'none';
                document.getElementById('causa_otro').value = '';
            }
        });
    });
</script>
</body>
</html>