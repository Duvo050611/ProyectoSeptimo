<?php
session_start();
include "../../conexionbd.php";
include "../../sauxiliares/header_farmaciah.php";

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Consulta pacientes activos
$resultado_pacientes = $conexion->query("
    SELECT pac.Id_exp, pac.sapell, pac.papell, pac.nom_pac, di.id_atencion 
    FROM paciente pac 
    JOIN dat_ingreso di ON pac.Id_exp = di.Id_exp
    WHERE di.activo = 'SI'
") or die($conexion->error);

// Consulta pacientes históricos
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
    <script src="../../js/main.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

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
            max-width: 800px;
            box-shadow: var(--sombra);
            border: 2px solid var(--color-borde);
        }

        h2 {
            color: var(--color-primario);
            font-weight: 700;
            text-align: center;
            margin-bottom: 20px;
        }

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
        }

        .btn-regresar {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white !important;
        }

        .btn-filtrar {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white !important;
        }

        .btn-moderno:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            text-decoration: none;
        }

        .form-control {
            border: 2px solid var(--color-borde);
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--color-primario);
            box-shadow: 0 0 0 3px rgba(43, 45, 127, 0.1);
            outline: none;
        }

        .form-label {
            font-weight: 600;
            color: var(--color-primario);
            margin-bottom: 8px;
        }

        @media (max-width: 768px) {
            .container-moderno {
                margin: 10px;
                padding: 20px;
                border-radius: 15px;
            }
            .btn-moderno {
                padding: 10px 16px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
<section class="content container-fluid">
    <div class="text-center mb-4">
        <a class="btn-moderno btn-regresar" href="../../template/menu_farmaciahosp.php">
            ⬅ Regresar
        </a>
    </div>

    <!-- Pacientes Activos -->
    <div class="container-moderno">
        <h2>Seleccionar Paciente Activo</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label class="form-label" for="paciente">Seleccione un paciente:</label>
                <select class="form-control" name="paciente" id="paciente" required>
                    <option value="">-- Seleccionar Paciente --</option>
                    <?php while ($row = $resultado_pacientes->fetch_assoc()) { ?>
                        <option value="<?php echo $row['id_atencion']; ?>">
                            <?php echo $row['id_atencion']. ' - ' . $row['papell'] . ' ' . $row['sapell'] . ' ' . $row['nom_pac']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn-moderno btn-filtrar">Continuar</button>
        </form>
    </div>

    <!-- Pacientes Históricos -->
    <div class="container-moderno">
        <h2>Seleccionar Paciente Histórico</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label class="form-label" for="paciente">Seleccione un paciente:</label>
                <select class="form-control" name="paciente" id="paciente" required>
                    <option value="">-- Seleccionar Paciente --</option>
                    <?php while ($row2 = $resultado_historico->fetch_assoc()) { ?>
                        <option value="<?php echo $row2['id_atencion']; ?>">
                            <?php echo $row2['id_atencion']. ' - ' . $row2['papell'] . ' ' . $row2['sapell'] . ' ' . $row2['nom_pac']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn-moderno btn-filtrar">Continuar</button>
        </form>
    </div>

    <?php
    if (isset($_POST['paciente'])) {
        $id_atencion = $_POST['paciente'];
        $_SESSION['id_atencion'] = $id_atencion; // Guardar en sesión

        echo '<script type="text/javascript">window.location.href="ingreso.php";</script>';
    }
    ?>
</section>
</body>
</html>
