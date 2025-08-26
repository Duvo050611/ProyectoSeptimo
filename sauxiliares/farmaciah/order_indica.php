<?php
session_start();
include "../../conexionbd.php";
include "../header_farmaciah.php";
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
    <!-- Estilos modernos -->
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
            max-width: 98%;
            box-shadow: var(--sombra);
            border: 2px solid var(--color-borde);
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
    <div class="container-moderno">
        <div class="text-center mb-4">
            <a class="btn-moderno btn-regresar" href="../../template/menu_farmaciahosp.php">
                ⬅ Regresar
            </a>
        </div>

        <div class="header-principal">
            <span class="icono-principal">💊</span>
            <h1>CONSULTAR INDICACIONES MÉDICAS</h1>
        </div>

        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Seleccionar Paciente:</label>
                <select class="form-control col-sm-4" data-live-search="true" name="paciente" required>
                    <option value="">SELECCIONAR</option>
                    <?php
                    $query = "SELECT * from paciente p, dat_ingreso di, cat_camas ca 
                        where p.Id_exp = di.Id_exp and di.activo='SI' and ca.id_atencion = di.id_atencion";
                    $result = $conexion->query($query);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id_atencion'] . "'> " . $row['num_cama'] . " - " . $row['papell'] . " " . $row['sapell'] . " " . $row['nom_pac'] ." </option>";
                        $cama = $row['num_cama'];
                    }
                    ?>
                </select>
            </div>
            <button type="submit" name="btnpaciente" class="btn-moderno btn-filtrar">Seleccionar</button>
        </form>
    </div>

    <?php
    if (isset($_POST['btnpaciente'])) {
        $paciente = mysqli_real_escape_string($conexion, (strip_tags($_POST["paciente"], ENT_QUOTES)));
        echo '<script type="text/javascript"> window.location.href="order_indica.php?paciente=' . $paciente . '";</script>';
    }

    if ((isset($_GET['paciente']))) {
        $paciente1 = $_GET['paciente'];
        $usuario = $_SESSION['login'];
        $usuario2 = $usuario['id_usua'];

        $sql_paciente = "SELECT p.nom_pac, p.papell, p.sapell 
                       FROM paciente p, dat_ingreso di 
                       WHERE p.Id_exp = di.Id_exp and di.id_atencion = $paciente1";
        $result_pac = $conexion->query($sql_paciente);
        while ($row_pac = $result_pac->fetch_assoc()) {
            $pac = $row_pac['papell'] . ' ' . $row_pac['sapell'] . ' ' . $row_pac['nom_pac'];
        }
        ?>
        <div class="container-moderno">
            <h3 class="text-center">Habitación: <?php echo $cama .' -  Paciente: '.  $pac ?></h3>
        </div>
        <?php
        $resultado5=$conexion->query("select * from dat_ordenes_med WHERE id_atencion=" . $paciente1." ORDER BY id_ord_med DESC") or die($conexion->error);

        while ($f5 = mysqli_fetch_array($resultado5)) {
            ?>
            <div class="container-moderno">
                <div class="row mb-3">
                    <div class="col">
                        Tipo: <strong><?php echo $f5['tipo']; ?></strong>
                    </div>
                    <div class="col-sm-3">
                        Fecha: <strong><?php echo date('d-m-Y', strtotime($f5['fecha_ord'])); ?></strong>
                    </div>
                    <div class="col-sm-3">
                        Hora: <strong><?php echo $f5['hora_ord']; ?></strong>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-2">
                        <label class="form-label">Medicamentos:</label>
                    </div>
                    <div class="col-10">
                        <textarea class="form-control" rows="10" disabled><?php echo $f5['med_med']; ?></textarea>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-2">
                        <label class="form-label">Soluciones:</label>
                    </div>
                    <div class="col-10">
                        <textarea class="form-control" rows="5" disabled><?php echo $f5['soluciones']; ?></textarea>
                    </div>
                </div>
            </div>
            <?php
        }}
    ?>
</section>

<footer class="main-footer">
    <?php include("../../template/footer.php"); ?>
</footer>
</body>
</html>