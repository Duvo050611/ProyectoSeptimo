<?php
session_start();
include "../../conexionbd.php";

// Ensure session variable exists
if (!isset($_SESSION['login'])) {
    header("Location: ../../index.php");
    exit;
}

$usuario = $_SESSION['login'];

// Role-based header inclusion
if (in_array($usuario['id_rol'], [4, 5, 10, 12])) {
    include "../header_labo.php";
} else {
    echo "<script>window.location='../../index.php';</script>";
    exit;
}

// Initial query (not used in table, but kept for compatibility)
$resultado = $conexion->query("SELECT paciente.*, dat_ingreso.id_atencion, triage.id_triage
    FROM paciente 
    INNER JOIN dat_ingreso ON paciente.Id_exp=dat_ingreso.Id_exp
    INNER JOIN triage ON dat_ingreso.id_atencion=triage.id_atencion WHERE id_triage=id_triage
") or die($conexion->error);

?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Bootstrap Select CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
    <!-- jQuery, Popper.js, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <!-- Bootstrap Select JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <!-- jQuery for search -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#search").keyup(function() {
                _this = this;
                $.each($("#mytable tbody tr"), function() {
                    if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
                        $(this).hide();
                    else
                        $(this).show();
                });
            });
        });
    </script>
</head>
<body>
<div class="container-fluid">
    <!-- Regresar Button -->
    <?php if ($usuario1['id_rol'] == 4): ?>
        <a type="submit" class="btn btn-danger" href="../../template/menu_sauxiliares.php">REGRESAR</a>
    <?php elseif ($usuario1['id_rol'] == 10): ?>
        <a type="submit" class="btn btn-danger" href="../../template/menu_laboratorio.php">REGRESAR</a>
    <?php elseif ($usuario1['id_rol'] == 5): ?>
        <a type="submit" class="btn btn-danger" href="../../template/menu_gerencia.php">REGRESAR</a>
    <?php elseif ($usuario1['id_rol'] == 12): ?>
        <a type="submit" class="btn btn-danger" href="../../template/menu_laboratorio.php">REGRESAR</a>
    <?php endif; ?>
    <br><br>
    <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 25px;">
        <center><strong>RESULTADOS DE ESTUDIOS DE LABORATORIO</strong></center>
    </div><br>

    <section class="content container-fluid">
        <div class="container box col-11">
            <div class="content">
                <div class="form-group">
                    <input type="text" class="form-control pull-right" style="width:20%" id="search" placeholder="Buscar...">
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="mytable">
                        <thead class="thead" style="background-color:#2b2d7f; color:white;">
                            <tr>
                                <th>Hab</th>
                                <th>Paciente</th>
                                <th>Fecha solicitud</th>
                                <th>Solicitante</th>
                                <th>Estudio(s)</th>
                                <th>Solicitud</th>
                                <th>Editar</th>
                                <th>Ver</th>
                                <?php if ($usuario1['id_rol'] == 5): ?>
                                    <th>Eliminar</th>
                                <?php endif; ?>
                                <th>Fecha de resultados</th>
                                <th>Atendió solicitud</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Main query using prepared statements
                        $query = "SELECT n.*, u.papell AS solicitante_papell, u.sapell AS solicitante_sapell, u2.papell AS resp_papell, u2.sapell AS resp_sapell
                                  FROM notificaciones_labo n
                                  JOIN reg_usuarios u ON n.id_usua = u.id_usua
                                  LEFT JOIN reg_usuarios u2 ON n.id_usua_resul = u2.id_usua
                                  WHERE n.realizado = 'SI'
                                  ORDER BY n.fecha_resul DESC";
                        $stmt = $conexion->prepare($query);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $no = 1;

                        while ($row = $result->fetch_assoc()) {
                            $habi = $row['habitacion'];
                            $id_atencion = $row['id_atencion'];
                            $not_id = $row['not_id'];

                            // Skip invalid rows
                            if (empty($id_atencion) || empty($not_id)) {
                                error_log("Invalid data: id_atencion=$id_atencion, not_id=$not_id");
                                continue;
                            }

                            if ($habi != 0) {
                                // Inpatient
                                $query_pac = "SELECT p.papell, p.sapell, p.nom_pac
                                              FROM dat_ingreso d
                                              JOIN paciente p ON d.Id_exp = p.Id_exp
                                              WHERE d.id_atencion = ?";
                                $stmt_pac = $conexion->prepare($query_pac);
                                $stmt_pac->bind_param("i", $id_atencion);
                                $stmt_pac->execute();
                                $result_pac = $stmt_pac->get_result();
                                $pac = '';
                                if ($row_pac = $result_pac->fetch_assoc()) {
                                    $pac = $row_pac['papell'] . ' ' . $row_pac['sapell'] . ' ' . $row_pac['nom_pac'];
                                }
                                $stmt_pac->close();
                            } else {
                                // Outpatient
                                $query_rec = "SELECT papell_rec, sapell_rec, nombre_rec
                                              FROM receta_ambulatoria
                                              WHERE id_rec_amb = ?";
                                $stmt_rec = $conexion->prepare($query_rec);
                                $stmt_rec->bind_param("i", $id_atencion);
                                $stmt_rec->execute();
                                $result_rec = $stmt_rec->get_result();
                                $pac = '';
                                $habitacion = "C.EXT";
                                if ($row_rec = $result_rec->fetch_assoc()) {
                                    $pac = $row_rec['papell_rec'] . ' ' . $row_rec['sapell_rec'] . ' ' . $row_rec['nombre_rec'];
                                }
                                $stmt_rec->close();
                            }

                            echo '<tr>'
                                . '<td>' . htmlspecialchars($habi != 0 ? $habi : $habitacion) . '</td>'
                                . '<td>' . htmlspecialchars($pac) . '</td>'
                                . '<td>' . date_format(date_create($row['fecha_ord']), 'd/m/Y H:i a') . '</td>'
                                . '<td>' . htmlspecialchars($row['solicitante_papell'] . ' ' . $row['solicitante_sapell']) . '</td>'
                                . '<td>';
                            // Display studies as a bulleted list
                            $estudios = preg_split('/[,;]/', $row['sol_estudios'], -1, PREG_SPLIT_NO_EMPTY);
                            if (!empty($estudios)) {
                                echo '<ul style="margin: 0; padding-left: 10px;">';
                                foreach ($estudios as $estudio) {
                                    $estudio = trim($estudio);
                                    if ($estudio) {
                                        echo '<li>' . htmlspecialchars($estudio) . '</li>';
                                    }
                                }
                                echo '</ul>';
                            } else {
                                echo htmlspecialchars($row['sol_estudios']);
                            }
                            echo '</td>'
                                . '<td><center>'
                                . '<a href="../Laboratorio/pdf_solicitud_estu.php?not_id=' . (int)$not_id . '&id_atencion=' . (int)$id_atencion . '" target="_blank">'
                                . '<button type="button" class="btn btn-success"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>'
                                . '</a></center></td>'
                                . '<td><center>'
                                . '<a href="../Laboratorio/editar_labo.php?not_id=' . (int)$not_id . '" title="Editar resultados" class="btn btn-danger"><i class="fa fa-edit" aria-hidden="true"></i></a>'
                                . '</center></td>'
                                . '<td><center>'
                                . '<a href="../Laboratorio/verpdf.php?not_id=' . (int)$not_id . '" title="Ver resultados" class="btn btn-danger"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>'
                                . '</center></td>';

                            if ($usuario1['id_rol'] == 5) {
                                echo '<td><center>'
                                    . '<a href="el_est.php?not_id=' . (int)$not_id . '" title="Eliminar estudio" class="btn btn-warning"><i class="fa fa-trash" aria-hidden="true"></i></a>'
                                    . '</center></td>';
                            }

                            echo '<td>' . date_format(date_create($row['fecha_resul']), 'd/m/Y H:i a') . '</td>'
                                . '<td>' . htmlspecialchars($row['resp_papell'] . ' ' . $row['resp_sapell']) . '</td>'
                                . '</tr>';
                            $no++;
                        }
                        $stmt->close();
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div> 
</div> 


<footer class="main-footer">
    <?php include "../../template/footer.php"; ?>
</footer>

<script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<script src="../../template/plugins/fastclick/fastclick.min.js"></script>
<script src="../../template/dist/js/app.min.js"></script>
</body>
</html>