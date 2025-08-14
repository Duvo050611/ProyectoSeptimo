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

    <title>Catálogo de Diagnósticos</title>

    <style>
        /* =============== ESTILOS MODERNOS PARA CATÁLOGO DE DIAGNÓSTICOS =============== */
        
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #495057;
        }

        .main-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(43, 45, 127, 0.1);
            margin: 20px;
            padding: 30px;
            animation: fadeIn 0.5s ease-in-out;
        }

        /* Header principal modernizado */
        .header-section {
            background: linear-gradient(135deg, #2b2d7f 0%, #3b3f8f 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 8px 25px rgba(43, 45, 127, 0.3);
            text-align: center;
        }

        .header-section h2 {
            margin: 0;
            font-weight: 700;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-section i {
            margin-right: 15px;
            font-size: 32px;
        }

        /* Área de botones modernizada */
        .action-buttons {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .btn-nuevo-modern {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            min-width: 200px;
            justify-content: center;
        }

        .btn-nuevo-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(40, 167, 69, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-nuevo-modern i {
            margin-right: 10px;
            font-size: 18px;
        }

        /* Área de búsqueda modernizada */
        .search-section {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 2px solid #e9ecef;
        }

        .search-input {
            border: 3px solid #2b2d7f;
            border-radius: 50px;
            padding: 15px 25px;
            font-size: 16px;
            font-weight: 500;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            transition: all 0.3s ease;
            width: 300px;
            margin-left: auto;
            display: block;
        }

        .search-input:focus {
            box-shadow: 0 0 0 0.3rem rgba(43, 45, 127, 0.25);
            transform: translateY(-2px);
            border-color: #1a1d5f;
        }

        .search-input::placeholder {
            color: #2b2d7f;
            font-weight: 600;
        }

        /* Tabla modernizada */
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 2px solid #e9ecef;
            overflow: hidden;
        }

        .table {
            margin: 0;
            border-radius: 10px;
            overflow: hidden;
        }

        .table thead th {
            background: linear-gradient(135deg, #2b2d7f 0%, #3b3f8f 100%) !important;
            color: white !important;
            border: none !important;
            padding: 20px 15px !important;
            font-weight: 700 !important;
            font-size: 15px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            text-align: center !important;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(43, 45, 127, 0.1);
        }

        .table tbody td {
            padding: 18px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
            font-weight: 500;
            text-align: center;
        }

        .table tbody tr:nth-child(even) {
            background: rgba(43, 45, 127, 0.02);
        }

        /* Botones de acción en la tabla */
        .btn-action {
            padding: 8px 15px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            margin: 2px;
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
            color: white;
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
            color: white;
        }

        /* =============== Modal Styling =============== */
        .modal-content {
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(43, 45, 127, 0.2);
            border: none;
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, #2b2d7f 0%, #3b3f8f 100%);
            color: white;
            border: none;
            padding: 25px 30px;
        }

        .modal-header h5 {
            font-weight: 700;
            font-size: 20px;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .modal-body {
            padding: 30px;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }

        .modal-body .form-group {
            margin-bottom: 25px;
        }

        .modal-body label {
            color: #2b2d7f;
            font-weight: 700;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            display: block;
        }

        .modal-body input {
            border: 3px solid #e9ecef;
            border-radius: 10px;
            padding: 15px 18px;
            font-size: 15px;
            font-weight: 500;
            width: 100%;
            transition: all 0.3s ease;
        }

        .modal-body input:focus {
            border-color: #2b2d7f;
            box-shadow: 0 0 0 0.3rem rgba(43, 45, 127, 0.25);
            transform: translateY(-2px);
        }

        .modal-footer {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: none;
            padding: 25px 30px;
            border-top: 2px solid #2b2d7f;
        }

        .modal-footer .btn {
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            margin: 0 5px;
            transition: all 0.3s ease;
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                margin: 10px;
                padding: 20px;
            }
            
            .header-section h2 {
                font-size: 22px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn-nuevo-modern {
                width: 100%;
                margin: 10px 0;
            }
            
            .search-input {
                width: 100%;
            }
            
            .table-container {
                padding: 15px;
                overflow-x: auto;
            }
        }

        /* Animaciones */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .table tbody tr {
            animation: slideIn 0.5s ease-in-out;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
    </style>

</head>

<body>

<div class="main-container">
    <!-- Header modernizado -->
    <div class="header-section">
        <h2>
            <i class="fas fa-diagnoses"></i> CATÁLOGO DE DIAGNÓSTICOS
        </h2>
    </div>

    <!-- Área de botones modernizada -->
    <div class="action-buttons">
        <button type="button" class="btn-nuevo-modern" data-toggle="modal" data-target="#exampleModal">
            <i class="fas fa-plus"></i>
            <span>Nuevo Diagnóstico</span>
        </button>
    </div>

    <!-- Modal Modernizado -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="insertar_diag.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <i class="fas fa-plus-circle"></i> Nuevo Diagnóstico
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="descripcion">
                                        <i class="fas fa-stethoscope"></i> Diagnóstico:
                                    </label>
                                    <input type="text" name="diag" id="descripcion" class="form-control" 
                                           placeholder="Ingrese el nombre del diagnóstico" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="id_cie10">
                                        <i class="fas fa-code"></i> Código CIE-10:
                                    </label>
                                    <input type="text" name="id_cie10" id="id_cie10" class="form-control" 
                                           maxlength="20" placeholder="Ingrese el código CIE-10" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sección de búsqueda modernizada -->
    <div class="search-section">
        <input type="text" class="search-input" id="search" placeholder="🔍 Buscar diagnóstico...">
    </div>

    <!-- Tabla modernizada -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover" id="mytable">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> ID</th>
                        <th><i class="fas fa-stethoscope"></i> Diagnóstico</th>
                        <th><i class="fas fa-code"></i> CIE-10</th>
                        <th><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $resultado2 = $conexion->query("SELECT * FROM cat_diag ") or die($conexion->error);
                    $no = 1;
                    while ($row = $resultado2->fetch_assoc()) {
                        $eid = $row['id_diag'];
                        echo '<tr>';
                        echo '<td><span class="badge badge-primary">' . $no . '</span></td>';
                        echo '<td><strong>' . $row['diagnostico'] . '</strong></td>';
                        echo '<td><span class="badge badge-info">' . $row['id_cie10'] . '</span></td>';
                        echo '<td>';
                        echo '<a href="edit_diag.php?id=' . $row['id_diag'] . '" title="Editar diagnóstico" class="btn btn-warning btn-action">';
                        echo '<i class="fas fa-edit"></i> Editar';
                        echo '</a>';
                        echo '</td>';
                        echo '</tr>';
                        $no++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<footer class="main-footer">
    <?php include("../../template/footer.php"); ?>
</footer>

<script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

<script>
$(document).ready(function() {
    // Animación de entrada para filas de la tabla
    $('tbody tr').each(function(index) {
        $(this).delay(100 * index).animate({
            opacity: 1
        }, 300);
    });
    
    // Mejorar el search
    $("#search").keyup(function () {
        var searchTerm = $(this).val().toLowerCase();
        $("#mytable tbody tr").each(function() {
            var rowText = $(this).text().toLowerCase();
            if (rowText.indexOf(searchTerm) === -1) {
                $(this).fadeOut(200);
            } else {
                $(this).fadeIn(200);
            }
        });
    });
    
    // Contador de resultados
    $("#search").keyup(function() {
        var visibleRows = $("#mytable tbody tr:visible").length;
        if (visibleRows === 0) {
            if (!$('.no-results').length) {
                $("#mytable tbody").append('<tr class="no-results"><td colspan="4" class="text-center"><i class="fas fa-search"></i> No se encontraron resultados</td></tr>');
            }
        } else {
            $('.no-results').remove();
        }
    });
    
    // Efectos hover para botones
    $('.btn-action').hover(function() {
        $(this).addClass('shadow-lg');
    }, function() {
        $(this).removeClass('shadow-lg');
    });
    
    // Validación del modal
    $('#exampleModal form').submit(function(e) {
        var diag = $('#descripcion').val().trim();
        var cie10 = $('#id_cie10').val().trim();
        
        if (diag === '' || cie10 === '') {
            e.preventDefault();
            alert('Por favor, complete todos los campos obligatorios.');
            return false;
        }
        
        if (cie10.length < 3) {
            e.preventDefault();
            alert('El código CIE-10 debe tener al menos 3 caracteres.');
            return false;
        }
    });
    
    // Limpiar modal al cerrar
    $('#exampleModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
    });
});
</script>

</body>
</html>