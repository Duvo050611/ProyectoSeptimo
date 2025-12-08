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

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

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

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #0a0a0a 100%) !important;
            font-family: 'Roboto', sans-serif !important;
            min-height: 100vh;
        }

        /* Efecto de partículas en el fondo */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(64, 224, 255, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(64, 224, 255, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(64, 224, 255, 0.02) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .container-fluid, .container {
            position: relative;
            z-index: 1;
        }

        /* Card principal */
        .modern-card {
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
            border: 2px solid #40E0FF !important;
            border-radius: 20px !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5),
                        0 0 30px rgba(64, 224, 255, 0.2) !important;
            padding: 40px 30px !important;
            margin-top: 30px;
            position: relative;
            overflow: hidden;
        }

        .modern-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent,
                rgba(64, 224, 255, 0.1),
                transparent
            );
            transform: rotate(45deg);
            pointer-events: none;
        }

        .modern-title {
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 2rem !important;
            letter-spacing: 2px;
            margin-bottom: 20px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            text-shadow: 0 0 20px rgba(64, 224, 255, 0.5);
            text-transform: uppercase;
        }

        .modern-title i {
            font-size: 2.2rem;
            color: #40E0FF !important;
            text-shadow: 0 0 20px rgba(64, 224, 255, 0.8);
        }

        hr {
            border-top: 2px solid #40E0FF !important;
            opacity: 0.5;
        }

        /* Botones */
        .modern-btn-primary {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            color: #ffffff !important;
            border-radius: 25px !important;
            font-weight: 600 !important;
            padding: 12px 35px !important;
            font-size: 1rem !important;
            box-shadow: 0 8px 25px rgba(64, 224, 255, 0.3) !important;
            border: 2px solid #40E0FF !important;
            transition: all 0.3s ease !important;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .modern-btn-primary:hover {
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
            color: #40E0FF !important;
            transform: translateY(-3px) !important;
            box-shadow: 0 12px 35px rgba(64, 224, 255, 0.5) !important;
            border-color: #00D9FF !important;
        }

        .modern-btn-success {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            color: #ffffff !important;
            border-radius: 18px !important;
            font-weight: 600 !important;
            font-size: 0.95rem !important;
            padding: 8px 20px !important;
            box-shadow: 0 4px 15px rgba(64, 224, 255, 0.2) !important;
            border: 2px solid #28a745 !important;
            transition: all 0.3s ease !important;
        }

        .modern-btn-success:hover {
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
            color: #28a745 !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4) !important;
            border-color: #20c997 !important;
        }

        .modern-btn-danger {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            color: #ffffff !important;
            border-radius: 18px !important;
            font-weight: 600 !important;
            font-size: 0.95rem !important;
            padding: 8px 20px !important;
            box-shadow: 0 4px 15px rgba(64, 224, 255, 0.2) !important;
            border: 2px solid #dc3545 !important;
            transition: all 0.3s ease !important;
        }

        .modern-btn-danger:hover {
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
            color: #dc3545 !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4) !important;
            border-color: #e74c3c !important;
        }

        .modern-btn-warning {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            color: #ffffff !important;
            border-radius: 18px !important;
            font-weight: 600 !important;
            font-size: 0.95rem !important;
            padding: 8px 20px !important;
            box-shadow: 0 4px 15px rgba(64, 224, 255, 0.2) !important;
            border: 2px solid #ffc107 !important;
            transition: all 0.3s ease !important;
        }

        .modern-btn-warning:hover {
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
            color: #ffc107 !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(255, 193, 7, 0.4) !important;
            border-color: #ff9800 !important;
        }

        /* Buscador */
        .modern-search {
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
            border: 2px solid #40E0FF !important;
            border-radius: 25px !important;
            padding: 12px 20px !important;
            font-size: 1rem !important;
            width: 100%;
            max-width: 400px;
            margin: 0 auto 30px auto !important;
            box-shadow: 0 5px 20px rgba(64, 224, 255, 0.2) !important;
            transition: all 0.3s ease !important;
            color: #ffffff !important;
        }

        .modern-search::placeholder {
            color: rgba(255, 255, 255, 0.6) !important;
        }

        .modern-search:focus {
            border-color: #00D9FF !important;
            outline: none !important;
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            box-shadow: 0 8px 30px rgba(64, 224, 255, 0.4) !important;
            color: #ffffff !important;
        }

        /* Tabla */
        .table-responsive {
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
            border: 2px solid #40E0FF !important;
            border-radius: 20px !important;
            padding: 20px !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5),
                        0 0 30px rgba(64, 224, 255, 0.2) !important;
            overflow: hidden;
        }

        .modern-table {
            margin-bottom: 0 !important;
            border-collapse: separate !important;
            border-spacing: 0 8px !important;
        }

        .modern-table thead {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            color: #ffffff !important;
            font-size: 1.1rem !important;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .modern-table thead th {
            border: none !important;
            padding: 20px 15px !important;
            font-weight: 700 !important;
            text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
            background: transparent !important;
        }

        .modern-table tbody tr {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
            transition: all 0.3s ease !important;
            border: 2px solid transparent !important;
        }

        .modern-table tbody tr:hover {
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
            transform: scale(1.01) !important;
            box-shadow: 0 5px 20px rgba(64, 224, 255, 0.3) !important;
            border: 2px solid #40E0FF !important;
        }

        .modern-table td {
            vertical-align: middle !important;
            border: none !important;
            padding: 15px !important;
            font-size: 1rem !important;
            color: #ffffff !important;
            border-top: 1px solid rgba(64, 224, 255, 0.1) !important;
        }

        .modern-table th {
            vertical-align: middle !important;
            color: #40E0FF !important;
        }

        /* Modal */
        .modal-content {
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
            border: 2px solid #40E0FF !important;
            border-radius: 20px !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.9),
                        0 0 40px rgba(64, 224, 255, 0.4) !important;
        }

        .modal-header {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            border-bottom: 2px solid #40E0FF !important;
            border-radius: 20px 20px 0 0 !important;
            color: #ffffff !important;
        }

        .modal-title {
            color: #ffffff !important;
            font-weight: 600 !important;
            text-shadow: 0 0 15px rgba(64, 224, 255, 0.5);
            letter-spacing: 1px;
        }

        .modal-title i {
            color: #40E0FF !important;
        }

        .modal-body {
            background: transparent !important;
            padding: 30px !important;
        }

        .modal-body label {
            color: #40E0FF !important;
            font-weight: 600 !important;
            letter-spacing: 0.5px;
            text-shadow: 0 0 10px rgba(64, 224, 255, 0.3);
        }

        .modal-body .form-control {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
            border: 2px solid #40E0FF !important;
            border-radius: 15px !important;
            color: #ffffff !important;
            padding: 12px 20px !important;
            box-shadow: 0 4px 15px rgba(64, 224, 255, 0.2) !important;
        }

        .modal-body .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5) !important;
        }

        .modal-body .form-control:focus {
            border-color: #00D9FF !important;
            box-shadow: 0 6px 20px rgba(64, 224, 255, 0.4) !important;
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
            color: #ffffff !important;
            outline: none !important;
        }

        .modal-footer {
            border-top: 2px solid #40E0FF !important;
            background: rgba(15, 52, 96, 0.5) !important;
            padding: 20px !important;
        }

        .close {
            color: #ffffff !important;
            opacity: 1 !important;
            text-shadow: 0 0 10px rgba(64, 224, 255, 0.8) !important;
            transition: all 0.3s ease !important;
        }

        .close:hover {
            color: #40E0FF !important;
            text-shadow: 0 0 20px rgba(64, 224, 255, 1) !important;
        }

        /* Footer */
        .main-footer {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            border-top: 2px solid #40E0FF !important;
            color: #ffffff !important;
            box-shadow: 0 -4px 20px rgba(64, 224, 255, 0.2);
            margin-top: 50px;
        }

        /* Content box */
        .box {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }

        .content {
            background: transparent !important;
        }

        /* Animaciones de entrada */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modern-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .table-responsive {
            animation: fadeInUp 0.8s ease-out;
        }

        /* Scrollbar personalizado */
        ::-webkit-scrollbar {
            width: 12px;
        }

        ::-webkit-scrollbar-track {
            background: #0a0a0a;
            border-left: 1px solid #40E0FF;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #40E0FF 0%, #0f3460 100%);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #00D9FF 0%, #40E0FF 100%);
        }

        /* Responsive */
        @media screen and (max-width: 768px) {
            .modern-title {
                font-size: 1.5rem !important;
            }

            .modern-card {
                padding: 20px 15px !important;
            }

            .modern-search {
                max-width: 100%;
            }

            .table-responsive {
                padding: 10px !important;
            }

            .modern-table {
                font-size: 0.9rem !important;
            }
        }
    </style>

</head>

<body>

<div class="container-fluid">
    <div class="modern-card">
        <div class="modern-title">
            <i class="fa fa-plus-square"></i> CATÁLOGO DE DIETAS
        </div>
        <hr>
        <div class="row mb-3">
            <div class="col-12 text-center">
                <button type="button" class="modern-btn-primary" data-toggle="modal" data-target="#exampleModal">
                    <i class="fa fa-plus"></i> Nueva dieta
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Insertar -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="insertar_dieta.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus"></i> Nueva dieta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="dieta">Nombre de la dieta:</label>
                        <input type="text" name="dieta" id="dieta" class="form-control" value="" required placeholder="Nombre de la dieta">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="modern-btn-success"><i class="fa fa-save"></i> Guardar</button>
                    <button type="button" class="modern-btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<section class="content container-fluid">

    <div class="container box">
        <div class="content">


            <?php

            include "../../conexionbd.php";


            // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios";

            //  $result = $conn->query($sql);

            $resultado2 = $conexion->query("SELECT * FROM cat_dietas") or die($conexion->error);
            ?>


            <!--Fin de los filtros-->


            <div class="form-group">
                <input type="text" class="modern-search" id="search" placeholder="Buscar dieta...">
            </div>

            <div class="table-responsive">
                <!--<table id="myTable" class="table table-striped table-hover">-->

                <table class="table modern-table table-bordered table-striped" id="mytable">

                    <thead class="thead">
                    <tr>
                        <th>Id</th>
                        <th>Nombre de la dieta</th>
                        <th>Activo</th>
                        <th>Editar</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
                    //  $result = $conn->query($sql);
                    $resultado2 = $conexion->query("SELECT * FROM cat_dietas") or die($conexion->error);

                    $no = 1;
                    while ($row = $resultado2->fetch_assoc()) {
                    $eid = $row['id_dieta'];

                    echo '<tr>'
                        . '<td>' . $row['id_dieta'] . '</td>'
                        . '<td>' . $row['dieta'] . '</td>';
                    echo '<td>';
                    if ((strpos($row['dieta_activo'], 'NO') !== false)) {
                        echo '<a type="submit" class="modern-btn-danger" href="insertar_dieta.php?q=estatus&eid=' . $eid . '&est=' . $row['dieta_activo'] . '" title="Desactivar"><span class = "fa fa-power-off"></span></a>';
                    } else {
                        echo '<a type="submit" class="modern-btn-success" href="insertar_dieta.php?q=estatus&eid=' . $eid . '&est=' . $row['dieta_activo'] . '" title="Activar"><span class = "fa fa-power-off"></span></a>';
                    }
                    echo '</td>';
                    echo '<td> <a href="edit_dietas.php?id=' . $row['id_dieta'] . '" title="Editar datos" class="modern-btn-warning"><span class="fa fa-edit" aria-hidden="true"></span></a></td>';
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
