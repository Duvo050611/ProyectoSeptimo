<?php
session_start();
include "../../conexionbd.php";
include "../../sauxiliares/header_farmaciah.php";

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Consulta para obtener pacientes que están dados de alta en alta_adm junto con el id_atencion
$resultado_pacientes = $conexion->query("
    SELECT pac.Id_exp, pac.sapell, pac.papell, pac.nom_pac, di.id_atencion 
    FROM paciente pac 
    JOIN dat_ingreso di ON pac.Id_exp = di.Id_exp
    WHERE di.activo = 'SI'
") or die($conexion->error);

// Consulta para obtener pacientes que están dados de alta en alta_adm junto con el id_atencion
$resultado_historico = $conexion->query("
    SELECT pac.Id_exp, pac.sapell, pac.papell, pac.nom_pac, di.id_atencion 
    FROM paciente pac 
    JOIN dat_ingreso di ON pac.Id_exp = di.Id_exp
    WHERE di.activo = 'NO'
") or die($conexion->error);
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
    <script src="../../js/main.js"></script
    <style>
        :root {
            --color-primario: #0c675e;
            --color-secundario: #0b5e51;
            --color-fondo: #f8f9ff;
            --color-borde: #e8ebff;
            --sombra: 0 4px 15px rgba(0, 0, 0, 0.1);
            --color-activo: #28a745;
            --color-historico: #6c757d;
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

        .btn-continuar {
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            color: white !important;
            min-width: 150px;
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

        /* ===== SECCIONES DE PACIENTES ===== */
        .seccion-pacientes {
            background: white;
            border: 2px solid var(--color-borde);
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            box-shadow: var(--sombra);
            animation: fadeInUp 0.6s ease-out 0.1s both;
        }

        .seccion-pacientes.activos {
            border-left: 5px solid var(--color-activo);
        }

        .seccion-pacientes.historicos {
            border-left: 5px solid var(--color-historico);
        }

        .titulo-seccion {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 25px;
            color: var(--color-primario);
        }

        .titulo-seccion.activos {
            color: var(--color-activo);
        }

        .titulo-seccion.historicos {
            color: var(--color-historico);
        }

        .icono-estado {
            font-size: 28px;
            padding: 10px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        .icono-estado.activos {
            background: rgba(40, 167, 69, 0.1);
            color: var(--color-activo);
        }

        .icono-estado.historicos {
            background: rgba(108, 117, 125, 0.1);
            color: var(--color-historico);
        }

        /* ===== FORMULARIO MODERNIZADO ===== */
        .form-control {
            border: 2px solid var(--color-borde);
            border-radius: 10px;
            padding: 15px 20px;
            transition: all 0.3s ease;
            font-size: 16px;
            min-height: 50px;
        }

        .form-control:focus {
            border-color: var(--color-primario);
            box-shadow: 0 0 0 3px rgba(12, 103, 94, 0.1);
            outline: none;
        }

        .form-group label {
            font-weight: 600;
            color: var(--color-primario);
            margin-bottom: 10px;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        /* ===== SELECT PERSONALIZADO ===== */
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 15px center;
            background-repeat: no-repeat;
            background-size: 20px;
            padding-right: 50px;
        }

        /* ===== INFORMACIÓN ADICIONAL ===== */
        .info-complementaria {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border: 1px solid #2196f3;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .info-complementaria .icono-info {
            font-size: 24px;
            color: #1976d2;
        }

        .info-complementaria .texto-info {
            color: #1565c0;
            font-weight: 500;
            margin: 0;
        }

        /* ===== SEPARADOR VISUAL ===== */
        .separador-secciones {
            height: 3px;
            background: linear-gradient(90deg, transparent 0%, var(--color-primario) 50%, transparent 100%);
            margin: 40px 0;
            border-radius: 2px;
        }

        /* ===== ESTADÍSTICAS RÁPIDAS ===== */
        .estadisticas-rapidas {
            display: flex;
            justify-content: space-around;
            gap: 20px;
            margin: 30px 0;
        }

        .stat-card {
            flex: 1;
            background: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: var(--sombra);
            border: 1px solid var(--color-borde);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #6c757d;
            font-weight: 500;
        }

        .stat-card.activos .stat-number {
            color: var(--color-activo);
        }

        .stat-card.historicos .stat-number {
            color: var(--color-historico);
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

            .titulo-seccion {
                font-size: 20px;
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .estadisticas-rapidas {
                flex-direction: column;
            }

            .info-complementaria {
                flex-direction: column;
                text-align: center;
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

        .seccion-pacientes:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* ===== EFECTOS DE CARGA ===== */
        .loading-spinner {
            display: none;
            margin-left: 10px;
        }

        .btn-continuar:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .form-enviado .loading-spinner {
            display: inline-block;
        }
    </style>
</head>

<body>
<div class="container-moderno">
    <a href="../../template/menu_farmaciahosp.php" class="btn-moderno btn-regresar">
        <i class="fas fa-arrow-left"></i>
        Regresar
    </a>

    <div class="header-principal">
        <i class="fas fa-user-friends icono-principal"></i>
        <h1>SELECCIONAR PACIENTE</h1>
    </div>

    <div class="info-complementaria">
        <i class="fas fa-info-circle icono-info"></i>
        <p class="texto-info">
            Seleccione un paciente para acceder a su perfil farmacoterapéutico.
            Los pacientes activos están hospitalizados actualmente, mientras que los históricos han sido dados de alta.
        </p>
    </div>

    <?php
    // Contar pacientes para estadísticas
    $count_activos = $resultado_pacientes->num_rows;

    // Resetear el puntero para usar los resultados después
    $resultado_pacientes->data_seek(0);

    $count_historicos = $resultado_historico->num_rows;
    $resultado_historico->data_seek(0);
    ?>

    <div class="estadisticas-rapidas">
        <div class="stat-card activos">
            <div class="stat-number"><?php echo $count_activos; ?></div>
            <div class="stat-label">Pacientes Activos</div>
        </div>
        <div class="stat-card historicos">
            <div class="stat-number"><?php echo $count_historicos; ?></div>
            <div class="stat-label">Pacientes Históricos</div>
        </div>
    </div>

    <!-- SECCIÓN PACIENTES ACTIVOS -->
    <div class="seccion-pacientes activos">
        <div class="titulo-seccion activos">
            <div class="icono-estado activos">
                <i class="fas fa-hospital-user"></i>
            </div>
            Pacientes Activos (Hospitalizados)
        </div>

        <form action="" method="POST" class="form-paciente-activo">
            <div class="form-group">
                <label for="paciente_activo">
                    <i class="fas fa-search"></i>
                    Seleccione un paciente activo:
                </label>
                <select class="form-control" name="paciente" id="paciente_activo" required>
                    <option value="">-- Seleccionar Paciente Activo --</option>
                    <?php while ($row = $resultado_pacientes->fetch_assoc()) { ?>
                        <option value="<?php echo $row['id_atencion']; ?>">
                            ID: <?php echo $row['id_atencion']; ?> - <?php echo $row['papell'] . ' ' . $row['sapell'] . ' ' . $row['nom_pac']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="text-center">
                <button type="submit" class="btn-moderno btn-continuar">
                    <i class="fas fa-arrow-right"></i>
                    Continuar
                    <i class="fas fa-spinner fa-spin loading-spinner"></i>
                </button>
            </div>
        </form>
    </div>

    <div class="separador-secciones"></div>

    <!-- SECCIÓN PACIENTES HISTÓRICOS -->
    <div class="seccion-pacientes historicos">
        <div class="titulo-seccion historicos">
            <div class="icono-estado historicos">
                <i class="fas fa-history"></i>
            </div>
            Pacientes Históricos (Dados de Alta)
        </div>

        <form action="" method="POST" class="form-paciente-historico">
            <div class="form-group">
                <label for="paciente_historico">
                    <i class="fas fa-search"></i>
                    Seleccione un paciente histórico:
                </label>
                <select class="form-control" name="paciente" id="paciente_historico" required>
                    <option value="">-- Seleccionar Paciente Histórico --</option>
                    <?php while ($row2 = $resultado_historico->fetch_assoc()) { ?>
                        <option value="<?php echo $row2['id_atencion']; ?>">
                            ID: <?php echo $row2['id_atencion']; ?> - <?php echo $row2['papell'] . ' ' . $row2['sapell'] . ' ' . $row2['nom_pac']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="text-center">
                <button type="submit" class="btn-moderno btn-continuar">
                    <i class="fas fa-arrow-right"></i>
                    Continuar
                    <i class="fas fa-spinner fa-spin loading-spinner"></i>
                </button>
            </div>
        </form>
    </div>

    <?php
    if (isset($_POST['paciente'])) {
        $id_atencion = $_POST['paciente'];
        $_SESSION['id_atencion'] = $id_atencion; // Guardar el ID de atención en la sesión con el nombre correcto

        // Redirigir a la página principal con el paciente seleccionado
        echo '<script type="text/javascript">
                document.querySelector(".btn-continuar").disabled = true;
                document.querySelector("form").classList.add("form-enviado");
                setTimeout(function() {
                    window.location.href="perfil.php";
                }, 500);
            </script>';
    }
    ?>
</div>

<script>
    // Mejorar la experiencia del usuario con efectos de carga
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const btn = this.querySelector('.btn-continuar');
            btn.disabled = true;
            this.classList.add('form-enviado');
        });
    });

    // Efecto de búsqueda en los selects
    document.querySelectorAll('select').forEach(select => {
        select.addEventListener('change', function() {
            if (this.value) {
                this.style.borderColor = 'var(--color-primario)';
                this.style.boxShadow = '0 0 0 3px rgba(12, 103, 94, 0.1)';
            }
        });
    });
</script>
</body>

</html>