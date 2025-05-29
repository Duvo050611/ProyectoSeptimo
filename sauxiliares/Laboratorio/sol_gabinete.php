<?php
session_start();
include "../../conexionbd.php";


$resultado = $conexion->query("select paciente.*, dat_ingreso.id_atencion, triage.id_triage
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp
inner join triage on dat_ingreso.id_atencion=triage.id_atencion where id_triage=id_triage
") or die($conexion->error);

$usuario = $_SESSION['login'];


if ($usuario['id_rol'] == 10) {
    include "../header_labo.php";

} else if ($usuario['id_rol'] == 4 or 5) {
    include "../header_labo.php";
} else {
    //session_unset();
    // session_destroy();
    echo "<script>window.Location='../../index.php';</script>";

}


?>

<!DOCTYPE html>
<html>

<head>

    <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″/>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script>
        // Write on keyup event of keyword input element
        $(document).ready(function () {
            $("#search").keyup(function () {
                _this = this;
                // Show only matching TR, hide rest of them
                $.each($("#mytable tbody tr"), function () {
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

    <?php
    if ($usuario1['id_rol'] == 10) {

        ?>
        <a type="submit" class="btn btn-danger" href="../../template/menu_laboratorio.php">Regresar</a>

        <?php
    } else if ($usuario1['id_rol'] == 5) {

        ?>
        <a type="submit" class="btn btn-danger" href="../../template/menu_gerencia.php">Regresar</a>

        <?php
    }else

    ?>
    <br>
    <br>
<div class="thead" style="background-color: #2b2d7f ; color: white; font-size: 25px;">
         <tr><strong><center>ESTUDIOS DE GABINETE PENDIENTES</center></strong>
      </div><br>

</div>

<section class="content">
        <section class="content container-fluid">
            <div class="content box">
                <div class="form-group">
                    <input type="text" class="form-control pull-right" style="width:25%" id="search" placeholder="Buscar...">
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="mytable">
                        <thead class="thead" style="background-color: #2b2d7f; color:white;">
                            <tr>
                                <th>Habitación</th>
                                <th>Paciente</th>
                                <th>Médico tratante</th>
                                <th>Fecha solicitud</th>
                                <th>Solicitante</th>
                                <th>Estudio(s)</th>
                                <th>Solicitud de estudio</th>
                                <th>Subir Resultado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT n.*, u.papell AS solicitante_papell, u.sapell AS solicitante_sapell 
                                      FROM notificaciones_gabinete n 
                                      JOIN reg_usuarios u ON n.id_usua = u.id_usua 
                                      WHERE n.realizado = 'NO' AND n.activo = 'SI' 
                                      ORDER BY n.fecha_ord DESC";
                            $stmt = $conexion->prepare($query);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $no = 1;

                            while ($row = $result->fetch_assoc()) {
                                $habi = $row['habitacion'];
                                $id_atencion = $row['id_atencion'];
                                $not_id = $row['id_not_gabinete'];

                                // Skip invalid rows
                                if (empty($id_atencion) || empty($not_id)) {
                                    error_log("Invalid data: id_atencion=$id_atencion, not_id=$not_id");
                                    continue;
                                }

                                if ($habi != 0) {
                                    // Inpatient (dat_ingreso)
                                    $query_pac = "SELECT p.papell, p.sapell, p.nom_pac, d.id_usua 
                                                  FROM dat_ingreso d 
                                                  JOIN paciente p ON d.Id_exp = p.Id_exp 
                                                  WHERE d.id_atencion = ?";
                                    $stmt_pac = $conexion->prepare($query_pac);
                                    $stmt_pac->bind_param("i", $id_atencion);
                                    $stmt_pac->execute();
                                    $result_pac = $stmt_pac->get_result();

                                    $pac = '';
                                    $tratante = null;
                                    if ($row_pac = $result_pac->fetch_assoc()) {
                                        $pac = $row_pac['papell'] . ' ' . $row_pac['sapell'] . ' ' . $row_pac['nom_pac'];
                                        $tratante = $row_pac['id_usua'];
                                    }
                                    $stmt_pac->close();

                                    $prefijo = '';
                                    $nom_tratante = '';
                                    if ($tratante) {
                                        $sql_reg_usrt = "SELECT pre, papell FROM reg_usuarios WHERE id_usua = ?";
                                        $stmt_reg_usrt = $conexion->prepare($sql_reg_usrt);
                                        $stmt_reg_usrt->bind_param("i", $tratante);
                                        $stmt_reg_usrt->execute();
                                        $result_reg_usrt = $stmt_reg_usrt->get_result();
                                        if ($row_reg_usrt = $result_reg_usrt->fetch_assoc()) {
                                            $prefijo = $row_reg_usrt['pre'];
                                            $nom_tratante = $row_reg_usrt['papell'];
                                        }
                                        $stmt_reg_usrt->close();
                                    }

                                    echo '<tr>'
                                        . '<td class="fondosan" style="color:white;">' . htmlspecialchars($row['habitacion']) . '</td>'
                                        . '<td class="fondosan" style="color:white;">' . htmlspecialchars($pac) . '</td>'
                                        . '<td class="fondosan" style="color:white;">' . htmlspecialchars($prefijo . '. ' . $nom_tratante) . '</td>'
                                        . '<td class="fondosan" style="color:white;">' . date_format(date_create($row['fecha_ord']), 'd/m/Y H:i a') . '</td>'
                                        . '<td class="fondosan" style="color:white;">' . htmlspecialchars($row['solicitante_papell'] . ' ' . $row['solicitante_sapell']) . '</td>'
                                        . '<td class="fondosan" style="color:white;">';

                                    $estudios = preg_split('/[,;]/', $row['sol_estudios'], -1, PREG_SPLIT_NO_EMPTY);
                                    if (!empty($estudios)) {
                                        echo '<ul style="margin: 0; padding-left: 10px; color: white;">';
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
                                        . '<td class="fondosan" style="color:white;"><center>'
                                        . '<a href="pdf_solicitud_gabinete.php?not_id=' . (int)$not_id . '&id_atencion=' . (int)$id_atencion . '" target="_blank">'
                                        . '<button type="button" class="btn btn-success"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>'
                                        . '</a></center></td>'
                                        . '<td class="fondosan" style="color:white;"><center>'
                                        . '<a href="subir_resultado_gabinete.php?not_id=' . (int)$not_id . '" title="Subir resultado" class="btn btn-success"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>'
                                        . '</center></td>'
                                        . '</tr>';
                                    $no++;
                                } else {
                                    // Outpatient (receta_ambulatoria)
                                    $query_rec = "SELECT papell_rec, sapell_rec, nombre_rec FROM receta_ambulatoria WHERE id_rec_amb = ?";
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

                                    echo '<tr>'
                                        . '<td class="fondosan" style="color:white;">' . htmlspecialchars($habitacion) . '</td>'
                                        . '<td class="fondosan" style="color:white;">' . htmlspecialchars($pac) . '</td>'
                                        . '<td class="fondosan" style="color:white;">N/A</td>'
                                        . '<td class="fondosan" style="color:white;">' . date_format(date_create($row['fecha_ord']), 'd/m/Y H:i a') . '</td>'
                                        . '<td class="fondosan" style="color:white;">' . htmlspecialchars($row['solicitante_papell'] . ' ' . $row['solicitante_sapell']) . '</td>'
                                        . '<td class="fondosan" style="color:white;">';

                                    $estudios = preg_split('/[,;]/', $row['sol_estudios'], -1, PREG_SPLIT_NO_EMPTY);
                                    if (!empty($estudios)) {
                                        echo '<ul style="margin: 0; padding-left: 10px; color: white;">';
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
                                        . '<td class="fondosan" style="color:white;"><center>'
                                        . '<a href="pdf_solicitud_gabinete.php?not_id=' . (int)$not_id . '&id_atencion=' . (int)$id_atencion . '" target="_blank">'
                                        . '<button type="button" class="btn btn-success"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>'
                                        . '</a></center></td>'
                                        . '<td class="fondosan" style="color:white;"><center>'
                                        . '<a href="subir_resultado_gabinete.php?not_id=' . (int)$not_id . '" title="Subir resultado" class="btn btn-success"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>'
                                        . '</center></td>'
                                        . '</tr>';
                                    $no++;
                                }
                            }
                            $stmt->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </section>
    </div><!-- /.content-wrapper -->

    <footer class="main-footer">
        <?php
        include("../../template/footer.php");
        ?>
    </footer>


    <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- FastClick -->
    <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

</body>
</html>