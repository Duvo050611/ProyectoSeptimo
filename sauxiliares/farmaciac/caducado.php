<?php
session_start();
include "../../conexionbd.php";

$resultado = $conexion->query("SELECT paciente.*, dat_ingreso.id_atencion, triage.id_triage
FROM paciente 
INNER JOIN dat_ingreso ON paciente.Id_exp = dat_ingreso.Id_exp
INNER JOIN triage ON dat_ingreso.id_atencion = triage.id_atencion WHERE id_triage = id_triage") 
or die($conexion->error);

$usuario = $_SESSION['login'];

if ($usuario['id_rol'] == 11 || $usuario['id_rol'] == 4 || $usuario['id_rol'] == 5) {
    include "../header_farmaciac.php";
} else {
    echo "<script>window.location='../../index.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />

    <!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- jQuery y Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }

        .thead {
            background-color: #2b2d7f;
            color: white;
            font-size: 20px;
            text-align: center;
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .table th, .table td {
            vertical-align: middle;
            text-align: center;
            border: 1px solid #ddd;
            padding: 12px;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }

        .danger { background-color: #ff6b6b !important; }
        .warning { background-color: #ffe066 !important; }
        .orange { background-color: #ffb74d !important; }
        .success { background-color: #81c784 !important; }

        /* Cambiar el fondo al pasar el mouse sobre una fila */
        tbody tr:hover {
            background-color: #d1e7dd;
        }

        /* Input de búsqueda estilizado */
        .search-box {
            margin-bottom: 20px;
        }

        .search-box input {
            width: 300px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
        }

        .btn-danger {
            margin-bottom: 20px;
        }
        /* ===== VARIABLES CSS ===== */
        :root {
            --color-primario: #2b2d7f;
            --color-secundario: #1a1c5a;
            --color-fondo: #f8f9ff;
            --color-borde: #e8ebff;
            --sombra: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* ===== ESTILOS GENERALES ===== */
        body {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8ebff 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .container-moderno {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin: 20px auto;
            max-width: 98%;
            box-shadow: var(--sombra);
            border: 2px solid var(--color-borde);
        }

        /* ===== BOTONES MODERNOS ===== */
        .btn-moderno {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: var(--sombra);
        }

        .btn-regresar {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white !important;
        }

        .btn-filtrar {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white !important;
        }

        .btn-borrar {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white !important;
        }

        .btn-especial {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white !important;
        }

        .btn-moderno:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            text-decoration: none;
        }

        /* ===== HEADER SECTION ===== */
        .header-principal {
            text-align: center;
            margin-bottom: 40px;
            padding: 30px 0;
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            border-radius: 20px;
            color: white;
            box-shadow: var(--sombra);
            position: relative;
        }

        .header-principal .icono-principal {
            font-size: 48px;
            margin-bottom: 15px;
            display: block;
        }

        .header-principal h1 {
            font-size: 32px;
            font-weight: 700;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .btn-ajuste {
            position: absolute;
            top: 50%;
            right: 30px;
            transform: translateY(-50%);
        }

        /* ===== FORMULARIO DE FILTROS ===== */
        .contenedor-filtros {
            background: white;
            border: 2px solid var(--color-borde);
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            box-shadow: var(--sombra);
        }

        .form-control {
            border: 2px solid var(--color-borde);
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--color-primario);
            box-shadow: 0 0 0 3px rgba(43, 45, 127, 0.1);
            outline: none;
        }

        .form-label {
            font-weight: 600;
            color: var(--color-primario);
            margin-bottom: 8px;
        }

        /* ===== TABLA MODERNIZADA ===== */
        .tabla-contenedor {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--sombra);
            border: 2px solid var(--color-borde);
            max-height: 80vh;
            overflow-y: auto;
        }

        .table-moderna {
            margin: 0;
            font-size: 12px;
            min-width: 100%;
        }

        .table-moderna thead th {
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            color: white;
            border: none;
            padding: 15px 10px;
            font-weight: 600;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 10;
            font-size: 11px;
        }

        .table-moderna thead th i {
            margin-right: 5px;
        }

        .table-moderna tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f1f3f4;
        }

        .table-moderna tbody tr:hover {
            background-color: var(--color-fondo);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .table-moderna tbody td {
            padding: 10px 8px;
            vertical-align: middle;
            border: none;
            text-align: center;
            white-space: nowrap;
        }

        /* ===== MENSAJE SIN RESULTADOS ===== */
        .mensaje-sin-resultados {
            text-align: center;
            padding: 50px 20px;
            color: var(--color-primario);
            font-size: 18px;
            font-weight: 600;
        }

        .mensaje-sin-resultados i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        /* ===== PAGINACIÓN MODERNA ===== */
        .contenedor-paginacion {
            display: flex;
            justify-content: center;
            margin: 20px 0 10px 0;
            padding-bottom: 0;
        }

        .paginacion-moderna {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-paginacion {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 45px;
            height: 45px;
            border: 2px solid var(--color-borde);
            background: white;
            color: var(--color-primario);
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 8px 12px;
        }

        .btn-paginacion:hover {
            background: var(--color-primario);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(43, 45, 127, 0.3);
            text-decoration: none;
        }

        .btn-paginacion.active {
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(43, 45, 127, 0.4);
        }

        /* ===== SELECT2 CUSTOM ===== */
        .select2-container--default .select2-selection--single {
            border: 2px solid var(--color-borde) !important;
            border-radius: 10px !important;
            height: 48px !important;
            line-height: 48px !important;
        }

        .select2-container--default .select2-selection--single:focus {
            border-color: var(--color-primario) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 15px !important;
            padding-top: 8px !important;
        }

        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 768px) {
            .container-moderno {
                margin: 10px;
                padding: 20px;
                border-radius: 15px;
            }

            .header-principal h1 {
                font-size: 24px;
            }

            .btn-moderno {
                padding: 10px 16px;
                font-size: 14px;
            }

            .table-moderna {
                font-size: 10px;
            }

            .table-moderna thead th,
            .table-moderna tbody td {
                padding: 8px 6px;
            }

            .btn-ajuste {
                position: relative;
                top: auto;
                right: auto;
                transform: none;
                margin-top: 15px;
            }
        }

        /* ===== ANIMACIONES ===== */
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

        .container-moderno {
            animation: fadeInUp 0.6s ease-out;
        }

        .contenedor-filtros,
        .tabla-contenedor {
            animation: fadeInUp 0.6s ease-out 0.1s both;
        }
    </style>

    <script>
        $(document).ready(function() {
            $("#search").keyup(function() {
                var _this = this;
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
<meta charset="UTF-8">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
      integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<body>
<div class="container-fluid">

    <div class="container-moderno">

        <!-- Botón de regreso modernizado -->
        <div class="d-flex justify-content-start mb-4">
            <a href="../../template/menu_farmaciacentral.php" class="btn-moderno btn-regresar">
                <i class="fas fa-arrow-left"></i> Regresar
            </a>
        </div>
        <div class="header-principal">
            <div class="contenido-header">
                <div class="icono-header">
                    <i class="fas fa-arrow-up icono-principal"></i>
                </div>
                <strong><center>CONTROL DE CADUCIDADES</center></strong>
            </div>
        </div>
        <div class="search-box">
            <input type="text" class="form-control" id="search" placeholder="Buscar...">
        </div>
    </div>

    <div class="tabla-contenedor">
        <div class="table-responsive">
            <table class="table table-moderna">
                <thead>
                <tr>
                    <th><i class="fa fa-barcode"></i> Código</th>
                    <th><i class="fa fa-file-alt"></i> Descripción</th>
                    <th><i class="fa fa-cubes"></i> Presentación</th>
                    <th><i class="fa fa-hashtag"></i> Lote</th>
                    <th><i class="fa fa-calendar-alt"></i> Caducidad</th>
                    <th><i class="fa fa-arrow-down"></i> Entradas</th>
                    <th><i class="fa fa-arrow-up"></i> Salidas</th>
                    <th><i class="fa fa-undo"></i> Devoluciones</th>
                    <th><i class="fa fa-box"></i> Existencias</th>
                    <th><i class="fa fa-arrow-circle-up"></i> Máximo</th>
                    <th><i class="fa fa-exclamation-triangle"></i> Punto de reorden</th>
                    <th><i class="fa fa-arrow-circle-down"></i> Mínimo</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $resultado2 = $conexion->query("SELECT * FROM item_almacen, existencias_almacen, item_type WHERE item_type.item_type_id=item_almacen.item_type_id AND item_almacen.item_id = existencias_almacen.item_id ORDER BY item_almacen.item_id") or die($conexion->error);

                while ($row = $resultado2->fetch_assoc()) {
                    $caducidad = date_create($row['existe_caducidad']);
                    $hoy = new DateTime();
                    $diferencia = $hoy->diff($caducidad);
                    $diasRestantes = $diferencia->days;

                    // Asignar clase de color según los días restantes
                    $colorClase = '';
                    if ($diasRestantes < 30) {
                        $colorClase = 'danger'; // Rojo
                    } elseif ($diasRestantes >= 31 && $diasRestantes <= 60) {
                        $colorClase = 'warning'; // Amarillo
                    } elseif ($diasRestantes >= 61 && $diasRestantes <= 90) {
                        $colorClase = 'orange'; // Naranja
                    } else {
                        continue; // No mostrar elementos con más de 90 días
                    }

                    // Generar la fila de la tabla
                    echo '<tr class="' . $colorClase . '">'
                        . '<td>' . $row['item_code'] . '</td>'
                        . '<td>' . $row['item_name'] . ', ' . $row['item_grams'] . '</td>'
                        . '<td>' . $row['item_type_desc'] . '</td>'
                        . '<td>' . $row['existe_lote'] . '</td>'
                        . '<td>' . date_format($caducidad, "d/m/Y") . '</td>'
                        . '<td>' . $row['existe_entradas'] . '</td>'
                        . '<td>' . $row['existe_salidas'] . '</td>'
                        . '<td>' . $row['existe_devoluciones'] . '</td>'
                        . '<td>' . $row['existe_qty'] . '</td>'
                        . '<td>' . $row['item_max'] . '</td>'
                        . '<td>' . $row['reorden'] . '</td>'
                        . '<td>' . $row['item_min'] . '</td>'
                        . '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<footer class="main-footer">
    <?php include("../../template/footer.php"); ?>
</footer>

<script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

</body>
</html>
