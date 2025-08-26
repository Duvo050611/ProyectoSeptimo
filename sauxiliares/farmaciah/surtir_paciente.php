<?php
session_start();
include "../../conexionbd.php";

$usuario = $_SESSION['login'];

if ($usuario['id_rol'] == 7 || $usuario['id_rol'] == 4 || $usuario['id_rol'] == 5 || $usuario['id_rol'] == 1 || $usuario['id_rol'] == 9) {
    include "../header_farmaciah.php";
} else {
    echo "<script>window.Location='../../index.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicamentos Pendientes - Sistema Farmacia</title>

    <!-- CSS -->
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

        /* Ajuste de tabla */
        .table-moderna {
            margin: 0;
            font-size: 14px;
            width: 100%;
            table-layout: auto;
            border-collapse: collapse;
        }

        /* Encabezados */
        .table-moderna thead th {
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            color: white;
            border: none;
            padding: 15px 12px;
            font-weight: 600;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 10;
            font-size: 13px;
            white-space: nowrap;
        }

        /* Filas */
        .table-moderna tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f1f3f4;
        }

        .table-moderna tbody tr:hover {
            background-color: var(--color-fondo);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Celdas */
        .table-moderna tbody td {
            padding: 12px 10px;
            vertical-align: middle;
            border: none;
            text-align: center;
            font-size: 13px;
            white-space: normal;
            word-wrap: break-word;
        }

        /* ===== ELEMENTOS ESPECÍFICOS ===== */
        .bed-number {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            padding: 8px 12px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 12px;
            display: inline-block;
            min-width: 50px;
            text-align: center;
            box-shadow: var(--sombra);
        }

        .view-materials-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 12px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            transition: all 0.3s ease;
            box-shadow: var(--sombra);
        }

        .view-materials-btn:hover {
            background: linear-gradient(135deg, #20c997 0%, #17a085 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            color: white;
            text-decoration: none;
        }

        .patient-cell {
            font-weight: 500;
            color: var(--color-primario);
        }

        .date-cell {
            color: #6c757d;
            font-size: 12px;
        }

        .requester-cell {
            color: #6c757d;
            font-size: 13px;
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
                font-size: 12px;
            }

            .table-moderna thead th,
            .table-moderna tbody td {
                padding: 10px 8px;
            }

            .btn-ajuste {
                position: relative;
                top: auto;
                right: auto;
                transform: none;
                margin-top: 15px;
            }
        }

        @media (max-width: 576px) {
            .table-moderna thead {
                display: none;
            }

            .table-moderna tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 2px solid var(--color-borde);
                border-radius: 15px;
                background: white;
                box-shadow: var(--sombra);
            }

            .table-moderna tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 12px 15px;
                border-bottom: 1px solid var(--color-borde);
                text-align: left;
            }

            .table-moderna tbody td:last-child {
                border-bottom: none;
            }

            .table-moderna tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--color-primario);
                font-size: 12px;
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

        .fade-in {
            animation: fadeInUp 0.3s ease-out;
        }
    </style>
</head>

<body>
<div class="container-moderno">
    <?php if ($usuario1['id_rol'] == 4 || $usuario1['id_rol'] == 7 || $usuario1['id_rol'] == 5 || $usuario1['id_rol'] == 1 || $usuario1['id_rol'] == 9) { ?>
        <a href="../../template/menu_farmaciahosp.php" class="btn-moderno btn-regresar">
            <i class="fas fa-arrow-left"></i>
            Regresar
        </a>
        <br><br>
    <?php } ?>

    <div class="header-principal">
        <i class="fas fa-pills icono-principal"></i>
        <h1>Pacientes con Medicamentos Pendientes de Surtir</h1>
    </div>

    <div class="contenedor-filtros">
        <div class="d-flex align-items-center gap-3">
            <i class="fas fa-search" style="color: var(--color-primario); font-size: 18px;"></i>
            <input type="text"
                   class="form-control"
                   id="search"
                   placeholder="Buscar paciente, cama, solicitante..."
                   style="max-width: 400px;">
        </div>
    </div>

    <div class="tabla-contenedor">
        <div class="table-responsive">
            <table class="table-moderna" id="mytable">
                <thead>
                <tr>
                    <th>
                        <i class="fas fa-bed" style="margin-right: 0.5rem;"></i>
                        Cama
                    </th>
                    <th>
                        <i class="fas fa-eye" style="margin-right: 0.5rem;"></i>
                        Solicitud
                    </th>
                    <th>
                        <i class="fas fa-user" style="margin-right: 0.5rem;"></i>
                        Paciente
                    </th>
                    <th>
                        <i class="fas fa-calendar" style="margin-right: 0.5rem;"></i>
                        Fecha de Nacimiento
                    </th>
                    <th>
                        <i class="fas fa-user-md" style="margin-right: 0.5rem;"></i>
                        Solicitante
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php
                include "../../conexionbd.php";

                $query = "SELECT DISTINCTROW di.id_atencion, p.fecnac, p.nom_pac, p.papell, p.sapell, p.Id_exp, u.papell as papell_usua, ca.id_atencion, ca.num_cama, c.id_atencion
                            FROM cart_fh c, dat_ingreso di, paciente p, reg_usuarios u, cat_camas ca WHERE c.id_atencion = di.id_atencion and di.Id_exp = p.Id_exp AND 
                            u.id_usua = c.id_usua and ca.id_atencion=di.id_atencion ";

                $result = $conexion->query($query);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $fecnac = date_create($row['fecnac']);
                        echo '<tr>';
                        echo '<td data-label="Cama"><span class="bed-number">' . htmlspecialchars($row['num_cama']) . '</span></td>';
                        echo '<td data-label="Solicitud">
                                            <a href="surtir_med.php?id_atencion=' . htmlspecialchars($row['id_atencion']) . '" class="view-materials-btn">
                                                <i class="fas fa-prescription-bottle"></i>
                                                Ver Materiales
                                            </a>
                                          </td>';
                        echo '<td data-label="Paciente" class="patient-cell">' .
                                htmlspecialchars($row['Id_exp'] . ' ' . $row['nom_pac'] . " " . $row['papell'] . " " . $row['sapell']) .
                                '</td>';
                        echo '<td data-label="Fecha de Nacimiento" class="date-cell">' .
                                date_format($fecnac, "d-m-Y") .
                                '</td>';
                        echo '<td data-label="Solicitante" class="requester-cell">' .
                                htmlspecialchars($row['papell_usua']) .
                                '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr>';
                    echo '<td colspan="5" class="mensaje-sin-resultados">';
                    echo '<i class="fas fa-inbox"></i>';
                    echo '<div>No hay medicamentos pendientes</div>';
                    echo '<small style="font-weight: 400; opacity: 0.7;">Todos los medicamentos han sido surtidos</small>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<!-- Footer -->
<footer class="main-footer" style="margin-top: 3rem; background: var(--surface-color); padding: 2rem 0; border-top: 1px solid var(--border-color);">
    <?php include("../../template/footer.php"); ?>
</footer>

<!-- JavaScript -->
<script>
    $(document).ready(function() {
        // Enhanced search functionality
        $("#search").on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase().trim();

            $("#mytable tbody tr").each(function() {
                const rowText = $(this).text().toLowerCase();
                if (searchTerm === '' || rowText.indexOf(searchTerm) !== -1) {
                    $(this).show().addClass('fade-in');
                } else {
                    $(this).hide().removeClass('fade-in');
                }
            });

            // Show empty state if no results
            const visibleRows = $("#mytable tbody tr:visible").length;
            if (visibleRows === 0 && searchTerm !== '') {
                if ($('#no-results').length === 0) {
                    $("#mytable tbody").append(`
                            <tr id="no-results">
                                <td colspan="5" class="empty-state">
                                    <i class="fas fa-search"></i>
                                    <h5>No se encontraron resultados</h5>
                                    <p>Intenta con otros términos de búsqueda</p>
                                </td>
                            </tr>
                        `);
                }
            } else {
                $('#no-results').remove();
            }
        });

        // Add loading state to buttons
        $('.view-materials-btn').on('click', function() {
            const originalText = $(this).html();
            $(this).html('<i class="fas fa-spinner fa-spin"></i> Cargando...');

            setTimeout(() => {
                $(this).html(originalText);
            }, 1000);
        });
    });
</script>
</body>
</html>