<?php
session_start();
require "../../estados.php";
include "../../conexionbd.php";
$conexion = ConexionBD::getInstancia()->getConexion();
include "../header_administrador.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <link href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="js/select2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldLv/Pr4nhuBviF5jGqQK/5i2Q5iZ64dxBl+zOZ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/main.js"></script>
    <script>
    $(document).ready(function() {
        $("#search").keyup(function() {
            var value = $(this).val().toLowerCase();
            $("#mytable tbody tr, #mytable-urg tbody tr, #mytable-amb tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
    </script>
    <style>
    .thead {
        background-color: #2b2d7f;
        color: white;
        font-size: 22px;
        padding: 10px;
        text-align: center;
    }
    </style>
    <title>Creación de Paciente</title>
    <link rel="shortcut icon" href="logp.png">
</head>
<body>
<div class="container">
    <div class="thead"><strong><center>ESTUDIOS OCULARES</center></strong></div>
    <br>
    <div class="row mb-4 g-3">
        <div class="col-sm-6 col-md-3">
            <a href="../gestion_pacientes/paciente.php" class="btn btn-primary btn-custom w-100">
                <i class="fas fa-user-plus"></i> Nuevo Paciente
            </a>
        </div>
        <div class="col-sm-6 col-md-3">
            <a href="../cartas_consentimientos/consent_lis_pac2.php" class="btn btn-danger btn-custom w-100">
                <i class="fas fa-file-pdf"></i> Imprimir Documentos
            </a>
        </div>
        <div class="col-sm-6 col-md-3">
            <a href="../cuenta_paciente/vista_ahosp.php" class="btn btn-warning btn-custom w-100">
                <i class="fas fa-bed"></i> Asignar Habitación
            </a>
        </div>
        <?php 
        $usuario = $_SESSION['login'];
        $rol = $usuario['id_rol'];
        if ($rol == 5 || $rol == 1) { ?>
        <div class="col-sm-6 col-md-3">
            <a href="../global_pac/pac_global.php" class="btn btn-danger btn-custom w-100"
                style="background-color: #FF5733;">
                <i class="fas fa-users"></i> Ver Expedientes
            </a>
        </div>
        <?php } ?>
        <div class="col-sm-6 col-md-3">
            <a href="vista_ine.php" class="btn btn-success btn-custom w-100">
                <i class="fas fa-id-card"></i> Subir INE
            </a>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="form-group search-bar">
        <input type="text" class="form-control" id="search" placeholder="Buscar pacientes...">
    </div>

    <!-- Hospitalized Patients Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="mytable">
            <thead>
                <tr>
                    <th>Editar</th>
                    <th>Cuenta</th>
                    <th>Expediente</th>
                    <th>Nombre</th>
                    <th>Edad</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Teléfono</th>
                    <th>Área</th>
                    <th>Fecha de Ingreso</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $resultado = $conexion->query("
                    SELECT p.*, d.fecha AS fecha_ing, d.id_atencion, d.area, c.num_cama
                    FROM paciente p
                    INNER JOIN dat_ingreso d ON p.Id_exp = d.Id_exp
                    LEFT JOIN cat_camas c ON d.id_atencion = c.id_atencion
                    WHERE d.activo = 'SI'
                    AND (TRIM(UPPER(d.area)) IN ('HOSPITALIZACION', 'HOSPITALIZACIÓN', 'TERAPIA INTENSIVA', 'OBSERVACIÓN', 'OBSERVACION', 'QUIROFANO', 'QUIRÓFANO', 'ENDOSCOPÍA', 'AMBULATORIO'))
                    ORDER BY d.fecha DESC
                ") or die($conexion->error);
                while ($f = mysqli_fetch_array($resultado)) {
                ?>
                <tr>
                    <td class="text-center">
                        <a href="edit_paciente.php?Id_exp=<?php echo $f['Id_exp']; ?>&id_atencion=<?php echo $f['id_atencion']; ?>"
                            class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                    <td class="text-center">
                        <a href="../cuenta_paciente/detalle_cuenta.php?id_at=<?php echo $f['id_atencion']; ?>&id_exp=<?php echo $f['Id_exp']; ?>&id_usua=<?php echo $usuario['id_usua']; ?>&rol=<?php echo $usuario['id_rol']; ?>"
                            class="btn btn-warning btn-sm">
                            <i class="fas fa-dollar-sign"></i>
                        </a>
                    </td>
                    <td><strong><?php echo $f['Id_exp']; ?></strong></td>
                    <td><strong><?php echo $f['papell'] . ' ' . $f['sapell'] . ' ' . $f['nom_pac']; ?></strong></td>
                    <td><strong><?php echo $f['edad']; ?></strong></td>
                    <td><strong><?php echo date_format(date_create($f['fecnac']), "d/m/Y"); ?></strong></td>
                    <td><strong><?php echo $f['tel']; ?></strong></td>
                    <td class="text-center" style="background-color: #2b2d7f; color: white;">
                        <strong><?php echo !empty($f['area']) ? $f['area'] : $f['area']; ?></strong>
                    </td>
                    <td><strong><?php echo date_format(date_create($f['fecha_ing']), "d/m/Y h:i A"); ?></strong></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Consultas/Urgencias Tab -->
    <!-- <div class="tab-content mt-5">
        <div class="tab-pane fade show active" id="nav-urg">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="mytable-urg">
                    <thead>
                        <tr>
                            <th>Editar</th>
                            <th>Cuenta</th>
                            <th>Expediente</th>
                            <th>Nombre</th>
                            <th>Edad</th>
                            <th>Fecha de Nacimiento</th>
                            <th>Teléfono</th>
                            <th>Estatus</th>
                            <th>Fecha de Ingreso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $resultado = $conexion->query("
                            SELECT p.*, d.area, d.fecha AS fecha_ing, d.id_atencion
                            FROM paciente p
                            INNER JOIN dat_ingreso d ON p.Id_exp = d.Id_exp
                            WHERE d.activo = 'SI'
                            AND TRIM(UPPER(d.area)) IN ('CONSULTA', 'ALTA')
                            ORDER BY d.fecha DESC
                        ") or die($conexion->error);
                        while ($f = mysqli_fetch_array($resultado)) {
                        ?>
                        <tr>
                            <td class="text-center">
                                <a href="edit_paciente.php?Id_exp=<?php echo $f['Id_exp']; ?>&id_atencion=<?php echo $f['id_atencion']; ?>"
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="../cuenta_paciente/detalle_cuenta.php?id_at=<?php echo $f['id_atencion']; ?>&id_exp=<?php echo $f['Id_exp']; ?>&id_usua=<?php echo $usuario['id_usua']; ?>&rol=<?php echo $usuario['id_rol']; ?>"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-dollar-sign"></i>
                                </a>
                            </td>
                            <td><strong><?php echo $f['Id_exp']; ?></strong></td>
                            <td><strong><?php echo $f['papell'] . ' ' . $f['sapell'] . ' ' . $f['nom_pac']; ?></strong></td>
                            <td><strong><?php echo $f['edad']; ?></strong></td>
                            <td><strong><?php echo date_format(date_create($f['fecnac']), "d/m/Y"); ?></strong></td>
                            <td><strong><?php echo $f['tel']; ?></strong></td>
                            <td class="text-center" style="background-color: #2b2d7f; color: white;">
                                <strong><?php echo $f['area']; ?></strong>
                            </td>
                            <td><strong><?php echo date_format(date_create($f['fecha_ing']), "d/m/Y h:i A"); ?></strong></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div> -->

        <!-- Ambulatorios Tab -->
        <!-- <div class="tab-pane fade" id="nav-profile">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="mytable-amb">
                    <thead>
                        <tr>
                            <th>Editar</th>
                            <th>Expediente</th>
                            <th>Nombre</th>
                            <th>Edad</th>
                            <th>Fecha de Nacimiento</th>
                            <th>Teléfono</th>
                            <th>Habitación</th>
                            <th>Fecha de Ingreso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $resultado = $conexion->query("
                            SELECT p.*, d.fecha AS fecha_ing, d.id_atencion, d.area
                            FROM paciente p
                            INNER JOIN dat_ingreso d ON p.Id_exp = d.Id_exp
                            WHERE d.activo = 'SI'
                            AND TRIM(UPPER(d.area)) IN ('QUIROFANO', 'QUIRÓFANO')
                            ORDER BY d.fecha DESC
                        ") or die($conexion->error);
                        while ($f = mysqli_fetch_array($resultado)) {
                        ?>
                        <tr>
                            <td class="text-center">
                                <a href="edit_paciente.php?Id_exp=<?php echo $f['Id_exp']; ?>&id_atencion=<?php echo $f['id_atencion']; ?>"
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                            <td><strong><?php echo $f['Id_exp']; ?></strong></td>
                            <td><strong><?php echo $f['papell'] . ' ' . $f['sapell'] . ' ' . $f['nom_pac']; ?></strong></td>
                            <td><strong><?php echo $f['edad']; ?></strong></td>
                            <td><strong><?php echo date_format(date_create($f['fecnac']), "d/m/Y"); ?></strong></td>
                            <td><strong><?php echo $f['tel']; ?></strong></td>
                            <td class="text-center" style="background-color: #2b2d7f; color: white;">
                                <strong><?php echo $f['area']; ?></strong>
                            </td>
                            <td><strong><?php echo date_format(date_create($f['fecha_ing']), "d/m/Y h:i A"); ?></strong></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div> -->
    </div>
</div>

<footer class="main-footer">
    <?php include "../../template/footer.php"; ?>
</footer>

<script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<script src="../../template/plugins/fastclick/fastclick.min.js"></script>
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
</body>
</html>