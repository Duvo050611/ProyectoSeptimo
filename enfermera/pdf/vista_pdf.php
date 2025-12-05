<?php
session_start();
include "../../conexionbd.php";
$conexion = ConexionBD::getInstancia()->getConexion();
include "../header_enfermera.php";

// Datos de sesión
$usuario = $_SESSION['login'];
$id_atencion = $_SESSION['pac'];

// Traer datos del paciente una sola vez
$queryPaciente = "
    SELECT P.Id_exp, P.nom_pac, P.papell, P.sapell, DI.id_atencion
    FROM paciente P
    INNER JOIN dat_ingreso DI ON P.Id_exp = DI.Id_exp
    WHERE DI.id_atencion = $id_atencion
";
$paciente = $conexion->query($queryPaciente)->fetch_assoc();

// Validar si existe información
if (!$paciente) {
    echo "<div class='alert alert-danger'>No se encontró información del paciente.</div>";
    exit;
}

$rolPermitido = ($usuario['id_rol'] == 5 || $usuario['id_rol'] == 12);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="gb18030">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <title>DOCUMENTACIÓN</title>

    <style>
        .btn-doc { font-size: 28px; }
        .titulo-seccion { background:#2b2d7f; color:white; padding:10px; font-size:22px; }
    </style>
</head>

<body>
<section class="content container-fluid">

    <div class="container">
        <center>
            <a href="../../template/menu_enfermera.php" class="btn btn-danger btn-sm">REGRESAR...</a>
        </center>

        <div class="titulo-seccion mt-3">
            <center><strong>IMPRESIÓN DE DOCUMENTOS (NOTAS DE ENFERMERÍA)</strong></center>
        </div>

        <p><strong>EXPEDIENTE:</strong> <?= $paciente['Id_exp']; ?></p>
        <p><strong>NOMBRE:</strong> <?= $paciente['nom_pac'] . " " . $paciente['papell'] . " " . $paciente['sapell']; ?></p>

        <div class="container p-3" style="background-color:#ffd1dc; color:#2b2d7f; border-radius:10px;">

            <!-- SECCIÓN 15 - ENFERMERÍA -->
            <h4><strong>15. Enfermería</strong></h4>

            <!-- 15.00 Registro Clínico -->
            <p>
                <a class="btn btn-danger btn-sm" href="../vistas_doc/vista_regclin.php">
                    <span class="fa fa-file-pdf-o btn-doc"></span>
                </a>
                15.00 Registro clínico
            </p>

            <!-- 15.01 Tratamiento -->
            <p>
                <a class="btn btn-danger btn-sm"
                   href="../registro_quirurgico/pdf_cirugia_segura.php?id=<?= $paciente['id_atencion'] ?>&id_exp=<?= $paciente['Id_exp'] ?>"
                   target="_blank">
                    <span class="fa fa-file-pdf-o btn-doc"></span>
                </a>
                15.01 Tratamiento
            </p>

            <!-- 15.02 Cirugía Segura -->
            <p>
                <a class="btn btn-danger btn-sm" href="../vistas_doc/vista_quir_area.php">
                    <span class="fa fa-file-pdf-o btn-doc"></span>
                </a>
                15.02 Cirugía segura
            </p>

            <!-- 15.03 Quirófano -->
            <p>
                <a class="btn btn-danger btn-sm" href="../vistas_doc/vista_pediatria.php">
                    <span class="fa fa-file-pdf-o btn-doc"></span>
                </a>
                15.03 Quirófano
            </p>

            <!-- 15.06 Pediátrico / Neonatal -->
            <p>
                <a class="btn btn-danger btn-sm" href="../vistas_doc/vista_transpdf.php">
                    <span class="fa fa-file-pdf-o btn-doc"></span>
                </a>
                15.06 Pediátrico / Neonatal
            </p>

            <!-- 15.07 Transfusión sanguínea -->
            <p>
                <a class="btn btn-danger btn-sm"
                   href="../registro_quirurgico/pdf_quirpiezas.php?id_atencion=<?= $paciente['id_atencion'] ?>&id_exp=<?= $paciente['Id_exp'] ?>"
                   target="_blank">
                    <span class="fa fa-file-pdf-o btn-doc"></span>
                </a>
                15.07 Transfusión sanguínea
            </p>

        </div>
    </div>

</section>
</body>
</html>
