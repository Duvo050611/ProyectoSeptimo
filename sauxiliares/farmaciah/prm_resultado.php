<?php
session_start();
include "../../conexionbd.php";
include "../../sauxiliares/header_farmaciah.php";

$id_atencion = $_SESSION['id_atencion'];

// Obtener información del paciente, la fecha de ingreso, alergias y diagnóstico
$resultado_paciente = $conexion->query("SELECT pac.sapell, pac.papell, pac.nom_pac, pac.fecnac, pac.edad, pac.sexo, di.area, di.fecha, di.alergias, di.motivo_atn 
    FROM paciente pac 
    JOIN dat_ingreso di ON pac.Id_exp = di.Id_exp 
    WHERE di.id_atencion = $id_atencion") or die($conexion->error);

$paciente = $resultado_paciente->fetch_assoc();

// Asegúrate de que la consulta devolvió resultados
if (!$paciente) {
    die("No se encontró información del paciente.");
}

// Obtener la fecha de ingreso, fecha de nacimiento y diagnóstico del paciente
$fecha_ingreso = $paciente['fecha'];
$fecha_nacimiento = $paciente['fecnac'];
$diagnostico = $paciente['motivo_atn'];
$sexo = $paciente['sexo'];
$edad = $paciente['edad'];
$motivo_ingreso = $paciente['area'];

// Obtener enfermedades concomitantes del paciente
$resultado_enfermedades = $conexion->query("SELECT diabetes_tipo, diabetes_detalle, hipertension, hipotiroidismo, insuficiencia_renal, 
    depresion_ansiedad, enfermedad_prostata, epoc, insuficiencia_cardiaca, obesidad, 
    artritis, cancer, otro_enfermedad FROM enf_concomitantes WHERE id_atencion = (SELECT Id_atencion FROM dat_ingreso WHERE id_atencion = $id_atencion)") or die($conexion->error);

$enfermedades = $resultado_enfermedades->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $fecha = $_POST['fecha'];
    $intervencion = $_POST['intervencion'];
    $problema = $_POST['problema'];
    $resultado = $_POST['resultado'];
    $salud_resuelto = $_POST['salud_resuelto'];
    $salud_no_resuelto = $_POST['salud_no_resuelto'];

    // Preparar la consulta SQL
    $query = "INSERT INTO prm_resultado (fecha, intervencion, problema, resultado, salud_resuelto, salud_no_resuelto) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);

    if ($stmt) {
        // Vincular los parámetros
        $stmt->bind_param("ssssss", $fecha, $intervencion, $problema, $resultado, $salud_resuelto, $salud_no_resuelto);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "<script>alert('Registro guardado exitosamente.'); window.location.href='prm_identificacion.php';</script>";
            exit;
        } else {
            echo "<script>alert('Error al guardar el registro: " . $stmt->error . "');</script>";
            exit;
        }

        $stmt->close();
    } else {
        echo "<script>alert('Error en la preparación de la consulta: " . $conexion->error . "');</script>";
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

        .btn-especial {
            background: linear-gradient(135deg, #2B2D7FFF 0%, #2B2D7FFF 100%);
            color: white !important;
        }

        .btn-moderno:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            text-decoration: none;
        }

        /* ===== HEADER SECTION ===== */
        .header-principal {
            text-align: center;
            margin-bottom: 40px;
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

        /* ===== INFORMACIÓN DEL CÓDIGO ===== */
        .code-info {
            text-align: right;
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 20px;
            padding: 15px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            border: 1px solid var(--color-borde);
        }

        /* ===== TARJETAS MODERNIZADAS ===== */
        .card-moderna {
            border: none;
            border-radius: 15px;
            box-shadow: var(--sombra);
            margin-bottom: 25px;
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out 0.1s both;
        }

        .card-header-moderna {
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            color: white;
            padding: 20px 25px;
            border: none;
            font-size: 18px;
            font-weight: 600;
        }

        .card-body-moderna {
            background: #f9f9f9;
            padding: 25px;
        }

        .card-body-moderna p {
            margin-bottom: 10px;
            font-size: 15px;
        }

        .card-body-moderna strong {
            color: var(--color-primario);
        }

        /* ===== FORMULARIO MODERNIZADO ===== */
        .form-control {
            border: 2px solid var(--color-borde);
            border-radius: 10px;
            transition: all 0.3s ease;
            font-size: 15px;
        }

        .form-control:focus {
            border-color: var(--color-primario);
            box-shadow: 0 0 0 3px rgba(12, 103, 94, 0.1);
            outline: none;
        }

        .form-group label {
            font-weight: 600;
            color: var(--color-primario);
            margin-bottom: 8px;
            font-size: 15px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        /* ===== CONTENEDOR DE BOTONES DE NAVEGACIÓN ===== */
        .navegacion-prm {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin: 25px 0;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            border: 1px solid var(--color-borde);
        }

        /* ===== CONTENEDOR DE FORMULARIO ===== */
        .contenedor-formulario {
            background: white;
            border: 2px solid var(--color-borde);
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            box-shadow: var(--sombra);
            animation: fadeInUp 0.6s ease-out 0.2s both;
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

            .btn-moderno {
                padding: 10px 16px;
                font-size: 14px;
                margin: 3px;
            }

            .navegacion-prm {
                flex-direction: column;
                align-items: center;
            }

            .card-header-moderna {
                font-size: 16px;
                padding: 15px 20px;
            }

            .card-body-moderna {
                padding: 20px;
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

        /* ===== SELECT PERSONALIZADO ===== */
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 40px;
        }

        /* ===== EFECTO HOVER PARA TARJETAS ===== */
        .card-moderna:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>

<body>
<div class="container-moderno">
    <div class="code-info">
        <p><i class="fas fa-code"></i> Código: FO-VEN20-FAR-001</p>
        <p><i class="fas fa-tag"></i> VERSIÓN: NUEVO</p>
    </div>
    <a href="deteccion_de_prm.php" class="btn-moderno btn-regresar">
        <i class="fas fa-arrow-left"></i>
        Regresar
    </a>

    <div class="header-principal">
        <i class="fas fa-pills icono-principal"></i>
        <h1>PERFIL FARMACOTERAPÉUTICO</h1>
    </div>

    <div class="card-moderna">
        <div class="card-header-moderna">
            <i class="fas fa-user-md"></i>
            Datos del Paciente
        </div>
        <div class="card-body-moderna">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nombre:</strong> <?php echo $paciente['papell'] . ' ' . $paciente['sapell'] . ' ' . $paciente['nom_pac']; ?></p>
                    <p><strong>Fecha de Nacimiento:</strong> <?php echo $fecha_nacimiento; ?></p>
                    <p><strong>Edad:</strong> <?php echo $edad; ?></p>
                    <p><strong>Sexo:</strong> <?php echo $sexo; ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Fecha de Ingreso:</strong> <?php echo $fecha_ingreso; ?></p>
                    <p><strong>Motivo de Ingreso:</strong> <?php echo $motivo_ingreso; ?></p>
                    <p><strong>Alergias:</strong> <?php echo $paciente['alergias']; ?></p>
                    <p><strong>Diagnóstico:</strong> <?php echo $diagnostico; ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="navegacion-prm">
        <a href="prm_identificacion.php" class="btn-moderno btn-custom">
            <i class="fas fa-search"></i>
            Detección de PRM (Identificación)
        </a>
        <a href="prm_accion.php" class="btn-moderno btn-custom">
            <i class="fas fa-cogs"></i>
            Detección de PRM (Acción)
        </a>
        <a href="prm_resultado.php" class="btn-moderno btn-especial">
            <i class="fas fa-chart-line"></i>
            Detección de PRM (Resultado)
        </a>
    </div>

    <div class="contenedor-formulario">
        <div class="card-moderna">
            <div class="card-header-moderna">
                <i class="fas fa-clipboard-check"></i>
                Registro de PRM (RESULTADO)
            </div>
            <div class="card-body-moderna">
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha">
                                    <i class="fas fa-calendar-alt"></i>
                                    Fecha fin de la intervención:
                                </label>
                                <input type="date" class="form-control" id="fecha" name="fecha" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="resultado">
                                    <i class="fas fa-check-circle"></i>
                                    Resultado:
                                </label>
                                <select class="form-control" id="resultado" name="resultado" required>
                                    <option value="">Seleccione una opción</option>
                                    <option value="I Aceptado">I Aceptado</option>
                                    <option value="II No Aceptado">II No Aceptado</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            <i class="fas fa-clipboard-list"></i>
                            ¿Qué ocurrió con la intervención?:
                        </label>
                        <textarea class="form-control" name="intervencion" rows="3" placeholder="Describa qué ocurrió con la intervención..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label>
                            <i class="fas fa-heartbeat"></i>
                            ¿Qué ocurrió con el problema de salud?:
                        </label>
                        <textarea class="form-control" name="problema" rows="3" placeholder="Describa qué ocurrió con el problema de salud..." required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    <i class="fas fa-check"></i>
                                    P. Salud resuelto:
                                </label>
                                <textarea class="form-control" name="salud_resuelto" rows="3" placeholder="Describa el problema de salud resuelto..." required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    <i class="fas fa-exclamation-triangle"></i>
                                    P. Salud No resuelto:
                                </label>
                                <textarea class="form-control" name="salud_no_resuelto" rows="3" placeholder="Describa el problema de salud no resuelto..." required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn-moderno btn-custom">
                            <i class="fas fa-save"></i>
                            Guardar Registro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>

</html>