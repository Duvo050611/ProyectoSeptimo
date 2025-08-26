<?php
session_start();
include "../../conexionbd.php";
$usuario = $_SESSION['login'];
if ($usuario['id_rol'] == 11 || $usuario['id_rol'] == 4 || $usuario['id_rol'] == 5 || $usuario['id_rol'] == 1) {
    include "../header_farmaciah.php";
} else {
    echo "<script>window.location='../../index.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Caducidades</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INEO Metepec</title>
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
    <style>
        :root {
            --color-primario: #2b2d7f;
            --color-secundario: #1a1c5a;
            --color-fondo: #f8f9ff;
            --color-borde: #e8ebff;
            --color-peligro: #dc3545;
            --color-advertencia: #ffffff;
            --color-naranja: #fd7e14;
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

        .btn-moderno:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            text-decoration: none;
        }

        /* ===== HEADER SECTION ===== */
        .header-principal {
            text-align: center;
            margin-bottom: 30px;
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

        /* ===== BARRA DE BÚSQUEDA ===== */
        .contenedor-busqueda {
            background: white;
            border: 2px solid var(--color-borde);
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: var(--sombra);
        }

        .form-control {
            border: 2px solid var(--color-borde);
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .form-control:focus {
            border-color: var(--color-primario);
            box-shadow: 0 0 0 3px rgba(43, 45, 127, 0.1);
            outline: none;
        }

        /* ===== TABLA MODERNIZADA ===== */
        .tabla-contenedor {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--sombra);
            border: 2px solid var(--color-borde);
            max-height: 75vh;
            overflow-y: auto;
        }

        .table-moderna {
            margin: 0;
            font-size: 13px;
            width: 100%;
            border-collapse: collapse;
        }

        /* Encabezados */
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
            font-size: 12px;
            white-space: nowrap;
        }

        /* Filas */
        .table-moderna tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f1f3f4;
        }

        .table-moderna tbody tr:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Celdas */
        .table-moderna tbody td {
            padding: 12px 8px;
            vertical-align: middle;
            border: none;
            text-align: center;
            font-size: 13px;
            word-wrap: break-word;
        }

        /* ===== COLORES DE ESTADO ===== */
        .danger {
            background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%) !important;
            color: #c62828 !important;
            border-left: 4px solid var(--color-peligro) !important;
        }

        .warning {
            background: linear-gradient(135deg, #fffde7 0%, #fff9c4 100%) !important;
            color: #f57f17 !important;
            border-left: 4px solid var(--color-advertencia) !important;
        }

        .orange {
            background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%) !important;
            color: #e65100 !important;
            border-left: 4px solid var(--color-naranja) !important;
        }

        /* ===== LEYENDA DE COLORES ===== */
        .leyenda-colores {
            background: white;
            border: 2px solid var(--color-borde);
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: var(--sombra);
        }

        .leyenda-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-right: 25px;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: 600;
        }

        .color-indicator {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .color-danger { background: var(--color-peligro); }
        .color-warning { background: var(--color-advertencia); }
        .color-orange { background: var(--color-naranja); }

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
                font-size: 11px;
            }

            .table-moderna thead th,
            .table-moderna tbody td {
                padding: 8px 6px;
            }

            .leyenda-item {
                display: block;
                margin-bottom: 10px;
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

        .contenedor-busqueda,
        .tabla-contenedor,
        .leyenda-colores {
            animation: fadeInUp 0.6s ease-out 0.1s both;
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
    </style>

    <!-- jQuery y Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

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
<body>
<div class="container-fluid">
    <div class="container-moderno">
        <!-- Header principal -->
        <div class="header-principal">
            <i class="fas fa-calendar-times icono-principal"></i>
            <h1>CONTROL DE CADUCIDADES</h1>
        </div>

        <!-- Botón de regresar -->
        <div class="row mb-4">
            <div class="col-sm-4">
                <a class="btn-moderno btn-regresar" href="../../template/menu_farmaciahosp.php">
                    <i class="fas fa-arrow-left"></i>
                    Regresar
                </a>
            </div>
        </div>

        <!-- Leyenda de colores -->
        <div class="leyenda-colores">
            <h5 class="mb-3"><i class="fas fa-info-circle"></i> Leyenda de Estados:</h5>
            <div class="leyenda-item">
                <div class="color-indicator color-danger"></div>
                <span>Menos de 30 días (Crítico)</span>
            </div>
            <div class="leyenda-item">
                <div class="color-indicator color-warning"></div>
                <span>31 a 60 días (Precaución)</span>
            </div>
            <div class="leyenda-item">
                <div class="color-indicator color-orange"></div>
                <span>61 a 90 días (Atención)</span>
            </div>
        </div>

        <!-- Barra de búsqueda -->
        <div class="contenedor-busqueda">
            <div class="input-group">
                <div class="input-group-prepend">
                        <span class="input-group-text" style="background: var(--color-primario); color: white; border: none;">
                            <i class="fas fa-search"></i>
                        </span>
                </div>
                <input type="text" class="form-control" id="search" placeholder="Buscar por código, descripción, lote o presentación...">
            </div>
        </div>

        <!-- Tabla de datos -->
        <div class="tabla-contenedor">
            <table class="table table-moderna" id="mytable">
                <thead>
                <tr>
                    <th><i class="fas fa-barcode"></i> Código</th>
                    <th><i class="fas fa-pills"></i> Descripción</th>
                    <th><i class="fas fa-box"></i> Presentación</th>
                    <th><i class="fas fa-tag"></i> Lote</th>
                    <th><i class="fas fa-calendar-times"></i> Caducidad</th>
                    <th><i class="fas fa-arrow-up text-success"></i> Entradas</th>
                    <th><i class="fas fa-arrow-down text-danger"></i> Salidas</th>
                    <th><i class="fas fa-undo text-warning"></i> Devoluciones</th>
                    <th><i class="fas fa-warehouse"></i> Existencias</th>
                    <th><i class="fas fa-arrow-up"></i> Máximo</th>
                    <th><i class="fas fa-exclamation-triangle"></i> Punto de reorden</th>
                    <th><i class="fas fa-arrow-down"></i> Mínimo</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $resultado2 = $conexion->query("SELECT * FROM item_almacen, existencias_almacenh, item_type WHERE item_type.item_type_id=item_almacen.item_type_id AND item_almacen.item_id = existencias_almacenh.item_id ORDER BY item_almacen.item_id") or die($conexion->error);

                $hayResultados = false;
                while ($row = $resultado2->fetch_assoc()) {
                    $caducidad = date_create($row['existe_caducidad']);
                    $hoy = new DateTime();
                    $diferencia = $hoy->diff($caducidad);
                    $diasRestantes = $diferencia->days;

                    // Asignar clase de color según los días restantes
                    $colorClase = '';
                    if ($diasRestantes < 30) {
                        $colorClase = 'danger'; // Rojo
                        $hayResultados = true;
                    } elseif ($diasRestantes >= 31 && $diasRestantes <= 60) {
                        $colorClase = 'warning'; // Amarillo
                        $hayResultados = true;
                    } elseif ($diasRestantes >= 61 && $diasRestantes <= 90) {
                        $colorClase = 'orange'; // Naranja
                        $hayResultados = true;
                    } else {
                        continue; // No mostrar elementos con más de 90 días
                    }

                    // Generar la fila de la tabla
                    echo '<tr class="' . $colorClase . '">'
                            . '<td><strong>' . $row['item_code'] . '</strong></td>'
                            . '<td>' . $row['item_name'] . ', ' . $row['item_grams'] . '</td>'
                            . '<td>' . $row['item_type_desc'] . '</td>'
                            . '<td><span class="badge badge-secondary">' . $row['existe_lote'] . '</span></td>'
                            . '<td><strong>' . date_format($caducidad, "d/m/Y") . '</strong><br><small>(' . $diasRestantes . ' días)</small></td>'
                            . '<td>' . $row['existe_entradas'] . '</td>'
                            . '<td>' . $row['existe_salidas'] . '</td>'
                            . '<td>' . $row['existe_devoluciones'] . '</td>'
                            . '<td><strong>' . $row['existe_qty'] . '</strong></td>'
                            . '<td>' . $row['item_max'] . '</td>'
                            . '<td>' . $row['reorden'] . '</td>'
                            . '<td>' . $row['item_min'] . '</td>'
                            . '</tr>';
                }

                if (!$hayResultados) {
                    echo '<tr><td colspan="12" class="mensaje-sin-resultados">';
                    echo '<i class="fas fa-check-circle"></i><br>';
                    echo 'No hay productos próximos a caducar en los próximos 90 días.';
                    echo '</td></tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="main-footer">
    <?php include("../../template/footer.php"); ?>
</footer>

<!-- Scripts adicionales -->
<script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
</body>
</html>