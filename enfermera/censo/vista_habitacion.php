<?php
session_start();
require_once '../../conexionbd.php';
$conexion = ConexionBD::getInstancia()->getConexion();
include "../header_enfermera.php";

$resultado = $conexion->query("SELECT paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
FROM paciente 
INNER JOIN dat_ingreso ON paciente.Id_exp=dat_ingreso.Id_exp 
WHERE dat_ingreso.area='HOSPITALIZACION' 
AND dat_ingreso.activo='SI' 
AND dat_ingreso.cama='0'
ORDER BY dat_ingreso.fecha ASC") or die($conexion->error);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Habitación</title>
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
    <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

    <style>
        .thead-custom {
            background-color: #2b2d7f;
            color: white;
            font-size: 18px;
        }

        .table-header {
            background-color: #2b2d7f;
            color: white;
        }

        .btn-habitacion {
            transition: transform 0.2s;
        }

        .btn-habitacion:hover {
            transform: scale(1.1);
        }

        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background-color: #2b2d7f;
            border: none;
        }

        .badge-urgente {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
        }

        .no-patients {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }

        .no-patients i {
            font-size: 64px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <!-- Botón regresar y disponibilidad -->
            <div class="mb-3">
                <button type="button" class="btn btn-secondary" onclick="history.back()">
                    <i class="fa fa-arrow-left"></i> Regresar
                </button>
                <a href="dispo_camas_enf.php" class="btn btn-primary">
                    <i class="fa fa-bed"></i> Ver Disponibilidad de Camas
                </a>
            </div>

            <!-- Encabezado -->
            <div class="card mb-4">
                <div class="card-header thead-custom text-center">
                    <h4 class="mb-0">
                        <i class="fa fa-hospital"></i>
                        PACIENTES SIN HABITACIÓN ASIGNADA
                    </h4>
                </div>
                <div class="card-body">
                    <?php
                    $total_pacientes = mysqli_num_rows($resultado);
                    if ($total_pacientes > 0) {
                        ?>
                        <div class="alert alert-warning" role="alert">
                            <i class="fa fa-exclamation-triangle"></i>
                            <strong>Atención:</strong> Hay <?php echo $total_pacientes; ?> paciente(s) esperando asignación de habitación.
                        </div>
                    <?php } ?>

                    <!-- Tabla de pacientes -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-header">
                            <tr>
                                <th scope="col" class="text-center">
                                    <i class="fa fa-bed"></i> Asignar
                                </th>
                                <th scope="col">Expediente</th>
                                <th scope="col">Nombre Completo</th>
                                <th scope="col">Fecha Nacimiento</th>
                                <th scope="col" class="text-center">Edad</th>
                                <th scope="col">Motivo de Atención</th>
                                <th scope="col" class="text-center">Fecha Ingreso</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ($total_pacientes > 0) {
                                mysqli_data_seek($resultado, 0); // Reset pointer
                                while ($f = mysqli_fetch_array($resultado)) {
                                    // Calcular días de espera
                                    $fecha_ingreso = new DateTime($f['fecha']);
                                    $fecha_actual = new DateTime();
                                    $dias_espera = $fecha_actual->diff($fecha_ingreso)->days;
                                    ?>
                                    <tr>
                                        <td scope="row" class="text-center">
                                            <a href="../censo/dispo_camas_asigna.php?id_atencion=<?php echo $f['id_atencion']; ?>"
                                               class="btn btn-danger btn-sm btn-habitacion"
                                               title="Asignar habitación">
                                                <i class="fa fa-bed"></i> Asignar
                                            </a>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($f['Id_exp']); ?></strong>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($f['nom_pac'] . ' ' . $f['papell'] . ' ' . $f['sapell']); ?>
                                        </td>
                                        <td>
                                            <?php
                                            if (!empty($f['fecnac'])) {
                                                $date = date_create($f['fecnac']);
                                                echo date_format($date, "d/m/Y");
                                            } else {
                                                echo 'N/A';
                                            }
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <strong><?php echo htmlspecialchars($f['edad']); ?></strong>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($f['motivo_atn']); ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $date = date_create($f['fecha']);
                                            echo date_format($date, "d/m/Y H:i");

                                            // Mostrar alerta si tiene más de 2 horas esperando
                                            if ($dias_espera >= 1) {
                                                echo '<br><span class="badge badge-danger">' . $dias_espera . ' día(s) esperando</span>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="7">
                                        <div class="no-patients">
                                            <i class="fa fa-check-circle text-success"></i>
                                            <h5>No hay pacientes sin habitación asignada</h5>
                                            <p class="text-muted">Todos los pacientes tienen una habitación asignada.</p>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fa fa-info-circle text-primary"></i> Instrucciones
                    </h6>
                    <ul class="mb-0">
                        <li>Haga clic en el botón <span class="badge badge-danger">Asignar</span> para asignar una habitación al paciente.</li>
                        <li>Los pacientes se muestran ordenados por fecha de ingreso (más antiguos primero).</li>
                        <li>Puede ver la disponibilidad de camas haciendo clic en el botón "Ver Disponibilidad de Camas".</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

</body>
</html>