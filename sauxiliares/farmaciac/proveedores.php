<?php
session_start();
include "../../conexionbd.php";


$resultado = $conexion->query("select paciente.*, dat_ingreso.id_atencion, triage.id_triage
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp
inner join triage on dat_ingreso.id_atencion=triage.id_atencion where id_triage=id_triage
") or die($conexion->error);

$usuario = $_SESSION['login'];


if ($usuario['id_rol'] == 4 || $usuario['id_rol'] == 7) {
    include "../header_farmaciac.php";

} else if ($usuario['id_rol'] == 5) {
    include "../header_farmaciac.php";
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- jQuery y Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

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
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <a class="btn btn-danger" href="../../template/menu_farmaciacentral.php">Regresar</a>
            </div>
            <div class="col-sm-8 text-right">
                <button type="button" class="btn btn-primary btn-register" data-toggle="modal" data-target="#exampleModal">
                    <i class="fa fa-plus"></i> Registrar Proveedor
                </button>
            </div>
        </div>
    </div>

    <br>
    <div class="thead">
        <strong>
            <center>CATÁLOGO DE PROVEEDORES</center>
        </strong>
    </div>
    <br>

    <section class="content container-fluid">
        <div class="container box">
            <div class="content">
                <div class="form-group">
                    <input type="text" class="form-control pull-right" style="width:20%" id="search" placeholder="Buscar...">
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="mytable">
                        <thead class="thead">
                            <tr>
                                <th>Id</th>
                                <th>Nombre Proveedor</th>
                                <th>Dirección</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Licencia</th>
                                <th>Contacto</th>
                                <th>Activo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $resultado2 = $conexion->query("SELECT * FROM proveedores") or die($conexion->error);
                        while ($row = $resultado2->fetch_assoc()) {
                            echo '<tr>'
                                . '<td>' . htmlspecialchars($row['id_prov']) . '</td>'
                                . '<td>' . htmlspecialchars($row['nom_prov']) . '</td>'
                                . '<td>' . htmlspecialchars($row['dir_prov']) . '</td>'
                                . '<td>' . htmlspecialchars($row['tel_prov']) . '</td>'
                                . '<td>' . htmlspecialchars($row['email_prov']) . '</td>'
                                . '<td>' . htmlspecialchars($row['lic_prov']) . '</td>'
                                . '<td>' . htmlspecialchars($row['cont_prov']) . '</td>'
                                . '<td>' . ($row['activo'] == 'SI' ? '<span style="color: green; font-weight: bold;">' . htmlspecialchars($row['activo']) . '</span>' : '<span style="color: red; font-weight: bold;">' . htmlspecialchars($row['activo']) . '</span>') . '</td>'
                                . '<td><a href="edit_proveedor.php?id=' . $row['id_prov'] . '" title="Editar datos" class="btn btn-warning btn-sm"><span class="fa fa-edit" aria-hidden="true"></span></a></td>'
                                . '</tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title" id="exampleModalLabel">NUEVO PROVEEDOR</h5>
            </div>
            <div class="modal-body">
                <form action="insertar_proveedor.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="id_prov">
                    <div class="form-group">
                        <label class="control-label" for="nom_prov">Nombre:</label>
                        <input onkeyup="javascript:this.value=this.value.toUpperCase();" type="text" maxlength="50" name="nom_prov" class="form-control" id="nom_prov" placeholder="Ingresa el nombre del proveedor" required autofocus>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="dir_prov">Dirección:</label>
                        <input onkeyup="javascript:this.value=this.value.toUpperCase();" type="text" maxlength="50" name="dir_prov" class="form-control" id="dir_prov" placeholder="Ingresa la dirección" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="tel_prov">Teléfono:</label>
                        <input type="number" min="0" name="tel_prov" class="form-control" id="tel_prov" placeholder="Ingresa número telefónico" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="correo">E-mail:</label>
                        <input  type="text" maxlength="50" name="email_prov" class="form-control" id="email_prov" placeholder="Ingresa el correo electrónico" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="licencia">Licencia:</label>
                        <input onkeyup="javascript:this.value=this.value.toUpperCase();" type="text" maxlength="50" name="lic_prov" class="form-control" id="lic_prov" placeholder="Ingresa la licencia sanitaria" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="contacto">Contacto:</label>
                        <input onkeyup="javascript:this.value=this.value.toUpperCase();" type="text" maxlength="50" name="cont_prov" class="form-control" id="cont_prov placeholder="Ingresa el nombre del contacto" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">REGRESAR</button>
                        <button type="submit" class="btn btn-primary">GUARDAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
