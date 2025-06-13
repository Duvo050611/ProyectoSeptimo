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
} else{
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>




    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script>
        // Write on keyup event of keyword input element
        $(document).ready(function() {
            $("#search").keyup(function() {
                _this = this;
                // Show only matching TR, hide rest of them
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

    <?php
    if ($usuario1['id_rol'] == 4) {
        ?>

        <a type="submit" class="btn btn-danger" href="../../template/menu_sauxiliares.php">REGRESAR</a>

        <?php
    } else if ($usuario1['id_rol'] == 10) {

        ?>
        <a type="submit" class="btn btn-danger" href="../../template/menu_laboratorio.php">REGRESAR</a>

        <?php
    } else if ($usuario1['id_rol'] == 5) {

        ?>
        <a type="submit" class="btn btn-danger" href="../../template/menu_gerencia.php">REGRESAR</a>

        <?php
    }else

    ?>
    <br>
    <br>
<div class="thead" style="background-color: #2b2d7f ; color: white; font-size: 25px;">
         <tr><strong><center>ATENCIÓN DE SOLICITUDES DE BANCO DE SANGRE</center></strong>
      </div><br>
</div>

<section class="content container-fluid">

    <!--------------------------
    | Your Page Content Here |
    -------------------------->


    <div class="container-fluid">
        <div class="content">


            <?php

            include "../../conexionbd.php";


            // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios";

            //  $result = $conn->query($sql);

            //    $resultado2 = $conexion->query("SELECT id_serv, serv_cve, serv_costo, serv_umed,serv_activo FROM cat_servicios") or die($conexion->error);
            ?>


            <!--Fin de los filtros-->


            <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:20%" id="search"
                       placeholder="Buscar...">
            </div>

            <div class="table-responsive">
                <!--<table id="myTable" class="table table-striped table-hover">-->

                <table class="table table-bordered table-striped" id="mytable">

                    <thead class="thead" style="background-color: #2b2d7f ; color:white;">
                    <tr>

                        <th>Paciente</th>
                        <th>Hab</th>
                        <th>Fecha de solicitud</th>
                        <th>Médico solicitante</th>
                        <th>Tipo</th>
                        <th>Realizado</th>
                        <th>Observaciones</th>
                        <th>Fecha de atención</th>
                        <th>Atendió solicitud</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    include "../../conexionbd.php";

                    $usuario = $_SESSION['login'];
                    $id_usua_log= $usuario['id_usua'];

                    $query_log = "SELECT * FROM reg_usuarios where id_usua= $id_usua_log ";
                    $result_log = $conexion->query($query_log);

                    while ($row_log = $result_log->fetch_assoc()) {
                        $pac_log = $row_log['papell'] . ' ' . $row_log['sapell'] . ' ' . $row_log['nombre'];
                    }


                    $query = "SELECT * FROM notificaciones_sangre n, reg_usuarios u where n.realizado = 'SI' and n.id_usua = u.id_usua ";
                    $result = $conexion->query($query);
                    $no = 1;
                    while ($row = $result->fetch_assoc()) {
                    $id_atencion = $row['id_atencion'];
                    $id_medico = $row['id_usua'];
                    $id_resp = $row['id_usua_resul'];
                    $fecha_orden = date_create($row['fecha_ord']);
                    $fecha_resulta = date_create($row['fecha_resul']);

                    $query_medi = "SELECT * FROM reg_usuarios where Id_usua = $id_medico";
                    $result_medi = $conexion->query($query_medi);

                    while ($row_medi = $result_medi->fetch_assoc()) {
                        $medico = $row_medi['papell'] . ' ' . $row_medi['sapell'];
                    }

                    $query_resp = "SELECT * FROM reg_usuarios where Id_usua = $id_resp";
                    $result_resp = $conexion->query($query_resp);

                    while ($row_resp = $result_resp->fetch_assoc()) {
                        $resp = $row_resp['papell'] . ' ' . $row_resp['sapell'];
                    }

                    $query_pac = "SELECT * FROM dat_ingreso d, paciente p where d.id_atencion = $id_atencion and d.Id_exp = p.Id_exp";
                    $result_pac = $conexion->query($query_pac);

                    while ($row_pac = $result_pac->fetch_assoc()) {
                        $pac = $row_pac['papell'] . ' ' . $row_pac['sapell'] . ' ' . $row_pac['nom_pac'];
                                }

                        echo '<tr>'

                            . '<td >' . $pac . '</td>'
                            . '<td >' . $row['habitacion'] . '</td>'
                            . '<td >' . date_format($fecha_orden,"d/m/Y H:i a") . '</td>'
                            . '<td >' . $medico . '</td>'
                            . '<td ><a style="color: deepskyblue;"> ' . $row['sol_sangre'] . '</a></td>'
                            . '<td >' . $row['realizado'] . '</td>'

                        . '<td >' . $row['resultado'] . '</td>';

                        $no++;
                       echo  '<td >' . date_format($fecha_resulta,"d/m/Y H:i a") . '</td>'
                           . '<td >' . $resp .  '</td></tr>';
                    }
                    ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</section>
</div>

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