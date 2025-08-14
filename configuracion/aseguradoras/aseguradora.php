<?php
session_start();
//include "../../conexionbd.php";
include "../../configuracion/header_configuracion.php";
$resultado = $conexion->query("select * from reg_usuarios") or die($conexion->error);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="gb18030">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
            integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
            crossorigin="anonymous"></script>
    <!--  Bootstrap  -->
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
          integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <script src="../../js/jquery-3.3.1.min.js"></script>
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/popper.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/main.js"></script>


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


<style>
    .modern-card {
        background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
        border-radius: 18px;
        box-shadow: 0 8px 25px rgba(43,45,127,0.12);
        padding: 30px 30px 10px 30px;
        margin-top: 30px;
        border: none;
    }
    .modern-title {
        color: #2b2d7f;
        font-weight: bold;
        font-size: 2rem;
        letter-spacing: 1px;
        margin-bottom: 10px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .modern-title i {
        font-size: 2.2rem;
        color: #2b2d7f;
    }
    .modern-btn-primary {
        background: linear-gradient(135deg, #2b2d7f 0%, #4e54c8 100%);
        color: #fff;
        border-radius: 25px;
        font-weight: 600;
        padding: 12px 28px;
        font-size: 1rem;
        box-shadow: 0 4px 15px rgba(43,45,127,0.12);
        border: none;
        transition: all 0.3s;
    }
    .modern-btn-primary:hover {
        background: linear-gradient(135deg, #23247a 0%, #2b2d7f 100%);
        color: #fff;
        transform: translateY(-2px);
    }
    .modern-table thead {
        background: #2b2d7f;
        color: #fff;
        font-size: 1.1rem;
        letter-spacing: 1px;
    }
    .modern-table tbody tr {
        background: #fff;
        transition: background 0.2s;
    }
    .modern-table tbody tr:hover {
        background: #e9ecef;
    }
    .modern-table td, .modern-table th {
        vertical-align: middle !important;
        border: 2px solid #e9ecef;
        font-size: 1rem;
    }
    .modern-btn-success, .modern-btn-danger, .modern-btn-warning {
        border-radius: 18px !important;
        font-weight: 600;
        font-size: 0.95rem;
        padding: 8px 18px;
        box-shadow: 0 2px 8px rgba(43,45,127,0.08);
        border: none;
        transition: all 0.3s;
    }
    .modern-btn-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: #fff;
    }
    .modern-btn-success:hover {
        background: linear-gradient(135deg, #218838 0%, #1e9f8a 100%);
        color: #fff;
        transform: translateY(-2px);
    }
    .modern-btn-danger {
        background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%);
        color: #fff;
    }
    .modern-btn-danger:hover {
        background: linear-gradient(135deg, #c82333 0%, #d63384 100%);
        color: #fff;
        transform: translateY(-2px);
    }
    .modern-btn-warning {
        background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
        color: #fff;
    }
    .modern-btn-warning:hover {
        background: linear-gradient(135deg, #e0a800 0%, #ff9800 100%);
        color: #fff;
        transform: translateY(-2px);
    }
    .modern-search {
        border: 4px solid #2b2d7f;
        border-radius: 12px;
        padding: 12px;
        font-size: 1rem;
        width: 100%;
        max-width: 350px;
        margin: 0 auto 20px auto;
        box-shadow: 0 2px 8px rgba(43,45,127,0.08);
        transition: border 0.3s;
    }
    .modern-search:focus {
        border-color: #4e54c8;
        outline: none;
        background: #f8f9ff;
    }
</style>

<div class="container-fluid">
    <div class="modern-card">
        <div class="modern-title">
            <i class="fa fa-plus-square"></i> CATÁLOGO DE ASEGURADORAS
        </div>
        <hr>
        <div class="row mb-3">
            <div class="col-12 text-center">
                <button type="button" class="modern-btn-primary" data-toggle="modal" data-target="#exampleModal">
                    <i class="fa fa-plus"></i> Nueva aseguradora
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Insertar -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="insertar_aseg.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header" style="background: #2b2d7f; color: #fff;">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus"></i> Nueva aseguradora</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="aseg" style="color: #2b2d7f; font-weight:600;">Aseguradora:</label>
                        <input type="text" name="aseg" id="aseg" class="form-control modern-search" value="" required placeholder="Nombre de la aseguradora">
                    </div>
                    <div class="form-group">
                        <label for="tip_precio" style="color: #2b2d7f; font-weight:600;">Tipo de precio:</label>
                        <input type="text" name="tip_precio" id="tip_precio" class="form-control modern-search" value="" required placeholder="Tipo de precio">
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8f9fa;">
                    <button type="submit" class="modern-btn-success"><i class="fa fa-save"></i> Guardar</button>
                    <button type="button" class="modern-btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<section class="content container-fluid">

    <!--------------------------
    | Your Page Content Here |
    -------------------------->


    <div class="container box">
        <div class="content">


            <?php

            include "../../conexionbd.php";

            $resultado2 = $conexion->query("SELECT * FROM cat_aseg") or die($conexion->error);
            ?>


            <!--Fin de los filtros-->


            <div class="form-group">
                <input type="text" class="modern-search" id="search" placeholder="Buscar aseguradora...">
            </div>

            <div class="table-responsive">
                <!--<table id="myTable" class="table table-striped table-hover">-->

                <table class="table modern-table table-bordered table-striped" id="mytable">

                    <thead class="thead" style="background-color: #2b2d7f;color:white;">
                    <tr>
                        <th>Id</th>
                        <th>Aseguradora</th>
                        <th>Tipo Precio</th>
                        <th>Activo</th>
                        <th>Editar</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                   
                    $resultado2 = $conexion->query("SELECT * FROM cat_aseg") or die($conexion->error);

                    $no = 1;
                    while ($row = $resultado2->fetch_assoc()) {
                    $eid = $row['id_aseg'];

                    echo '<tr>'
                        . '<td>' . $row['id_aseg'] . '</td>'
                        . '<td>' . $row['aseg'] . '</td>'
                        . '<td>' . $row['tip_precio'] . '</td>';
                    echo '<td>';
                    if ((strpos($row['aseg_activo'], 'NO') !== false)) {
                        echo '<a type="submit" class="modern-btn-danger" href="insertar_aseg.php?q=estatus&eid=' . $eid . '&est=' . $row['aseg_activo'] . '" title="Desactivar"><span class = "fa fa-power-off"></span></a>';
                    } else {
                        echo '<a type="submit" class="modern-btn-success" href="insertar_aseg.php?q=estatus&eid=' . $eid . '&est=' . $row['aseg_activo'] . '" title="Activar"><span class = "fa fa-power-off"></span></a>';
                    }
                    echo '</td>';
                    echo '<td> <a href="edit_aseguradora.php?id=' . $row['id_aseg'] . '" title="Editar datos" class="modern-btn-warning"><span class="fa fa-edit" aria-hidden="true"></span></a></td>';
                    echo '</tr>';
                    $no++;
                    }
                    ?>



                    </tbody>
                </table>

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