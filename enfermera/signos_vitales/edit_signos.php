<?php
session_start();
include '../../conexionbd.php';
$conexion = ConexionBD::getInstancia()->getConexion();

if (!isset($_SESSION['login'])) {
    header("Location: ../index.php");
}

include "../header_enfermera.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>

    <!-- BOOTSTRAP 4.6.2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- FONTAWESOME -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">

    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- POPPER + BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS -->
    <link rel="stylesheet" href="../../js/aos.css">
    <script src="../../js/aos.js"></script>

    <!-- MAGNIFIC POPUP -->
    <link rel="stylesheet" href="../../js/magnific-popup.css">
    <script src="../../js/jquery.magnific-popup.min.js"></script>

    <!-- ESTILO FUTURISTA CIBERPUNK AZUL -->
    <style>
        body {
            background: #0a0f25; /* Fondo azul noche */
            font-family: "Segoe UI", sans-serif;
            color: #e3f2ff;
        }

        /* Contenedor azul marino futurista */
        .container-box {
            background: rgba(10, 20, 60, 0.8);
            border: 1px solid #1e3c80;
            box-shadow: 0 0 25px #1d5cff;
            padding: 25px;
            border-radius: 12px;
            margin-top: 40px;
            animation: fadeIn 1s ease;
        }

        /* Título holográfico azul */
        .titulo-ciber {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            color: #5ab8ff;
            text-shadow: 0 0 10px #5ab8ff, 0 0 20px #1d7eff;
            letter-spacing: 2px;
        }

        /* Labels azul neon */
        label {
            font-weight: bold;
            color: #79aaff;
            text-shadow: 0 0 6px #3c6eff;
        }

        /* Inputs neon */
        .form-control {
            background: #0d1533;
            color: #cfe6ff;
            border: 1px solid #2a4bff;
            border-radius: 6px;
            box-shadow: 0 0 6px #1d4dff;
        }

        .form-control:focus {
            background: #0d1533;
            color: white;
            border-color: #4ca1ff;
            box-shadow: 0 0 12px #4ca1ff;
        }

        /* Botón azul neon */
        .btn-guardar {
            background: linear-gradient(90deg, #0047ff, #00aaff);
            border: none;
            color: white;
            padding: 10px 25px;
            border-radius: 8px;
            text-shadow: 0 0 5px #bce0ff;
            box-shadow: 0 0 15px #1d8dff;
            transition: 0.3s;
        }

        .btn-guardar:hover {
            box-shadow: 0 0 25px #55caff;
            transform: translateY(-2px);
        }

        /* Botón rojo */
        .btn-volver {
            background: #aa0f30;
            border: none;
            color: white;
            padding: 10px 25px;
            border-radius: 8px;
            box-shadow: 0 0 12px #ff0033;
            transition: 0.3s;
        }

        .btn-volver:hover {
            background: #cc002f;
            box-shadow: 0 0 25px #ff3b5c;
            transform: translateY(-2px);
        }

        /* Animación */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

</head>

<body>

<div class="container container-box">
    <h2 class="titulo-ciber">EDITAR SIGNOS VITALES</h2>
    <br>

    <?php
    $id = $_GET['id_sig'];
    $sql = "SELECT * FROM signos_vitales WHERE id_sig = $id";
    $result = $conexion->query($sql);
    while ($row_datos = $result->fetch_assoc()) {
        ?>

        <form class="form-horizontal" action="" method="post">

            <div class="row">

                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Hora:</label>
                        <select class="form-control" name="hora" required>
                            <option value="<?php echo $row_datos['hora']; ?>"><?php echo $row_datos['hora']; ?></option>
                            <?php
                            for($h=1;$h<=24;$h++){
                                echo "<option value='$h'>$h:00</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Presión arterial:</label>
                        <div class="row">
                            <div class="col-sm">
                                <input type="text" name="p_sistol" class="form-control"
                                       value="<?php echo $row_datos['p_sistol']; ?>" required>
                            </div>
                            <span class="mx-1">/</span>
                            <div class="col-sm">
                                <input type="text" name="p_diastol" class="form-control"
                                       value="<?php echo $row_datos['p_diastol']; ?>" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Frecuencia cardiaca:</label>
                        <input type="text" name="fcard" class="form-control"
                               value="<?php echo $row_datos['fcard']; ?>" required>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Frecuencia respiratoria:</label>
                        <input type="text" name="fresp" class="form-control"
                               value="<?php echo $row_datos['fresp']; ?>" required>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Temperatura:</label>
                        <input type="text" name="temper" class="form-control"
                               value="<?php echo $row_datos['temper']; ?>" required>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Saturación oxígeno:</label>
                        <input type="text" name="satoxi" class="form-control"
                               value="<?php echo $row_datos['satoxi']; ?>" required>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Nivel dolor:</label>
                        <input type="text" name="niv_dolor" class="form-control"
                               value="<?php echo $row_datos['niv_dolor']; ?>" required>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Fecha:</label>
                        <input type="date" name="fecha" class="form-control"
                               value="<?php echo $row_datos['fecha']; ?>" required>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Tipo:</label>
                        <select name="tipo" class="form-control">
                            <option value="<?php echo $row_datos['tipo']; ?>"><?php echo $row_datos['tipo']; ?></option>
                            <option value="HOSPITALIZACIÓN">HOSPITALIZACIÓN</option>
                            <option value="TERAPIA INTENSIVA">TERAPIA INTENSIVA</option>
                            <option value="QUIROFANO">QUIROFANO</option>
                            <option value="RECUPERACIÓN">RECUPERACIÓN</option>
                            <option value="OBSERVACIÓN">OBSERVACIÓN</option>
                        </select>
                    </div>
                </div>

            </div>

            <center>
                <button type="submit" name="edit" class="btn-guardar">Guardar</button>
                <button type="button" class="btn-volver" onclick="history.back()">Regresar</button>
            </center>

        </form>

    <?php } ?>

    <?php
    if (isset($_POST['edit'])) {

        $hora       = $_POST["hora"];
        $p_sistol   = $_POST["p_sistol"];
        $p_diastol  = $_POST["p_diastol"];
        $fcard      = $_POST["fcard"];
        $fresp      = $_POST["fresp"];
        $temper     = $_POST["temper"];
        $satoxi     = $_POST["satoxi"];
        $niv_dolor  = $_POST["niv_dolor"];
        $fecha      = $_POST["fecha"];
        $tipo       = $_POST["tipo"];

        $tam = ($p_sistol + $p_diastol) / 2;

        $sql2 = "UPDATE signos_vitales SET 
              fecha='$fecha',
              p_sistol='$p_sistol',
              p_diastol='$p_diastol',
              fcard='$fcard',
              fresp='$fresp',
              temper='$temper',
              satoxi='$satoxi',
              niv_dolor='$niv_dolor',
              hora='$hora',
              tipo='$tipo',
              tam='$tam'
            WHERE id_sig=$id";

        $conexion->query($sql2);

        echo "<p class='alert alert-success mt-3'>
            <i class='fa fa-check'></i> Datos actualizados correctamente...
          </p>";
        echo "<script>setTimeout(()=>{ window.location='signos.php'; }, 1200);</script>";
    }
    ?>

</div>
<br><br><br><br><br><br><br><br><br><br><br><br>

<footer class="main-footer">
    <?php include("../../template/footer.php"); ?>
</footer>

</body>
</html>
