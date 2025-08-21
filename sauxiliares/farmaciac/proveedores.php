<?php
session_start();
include "../../conexionbd.php";

// Configuración de paginación
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Contar total de registros
$total_registros_query = $conexion->query("SELECT COUNT(*) as total FROM proveedores");
$total_registros = $total_registros_query->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Consulta con paginación
$resultado = $conexion->query("SELECT paciente.*, dat_ingreso.id_atencion, triage.id_triage
    FROM paciente 
    INNER JOIN dat_ingreso ON paciente.Id_exp = dat_ingreso.Id_exp
    INNER JOIN triage ON dat_ingreso.id_atencion = triage.id_atencion 
    WHERE id_triage = id_triage
") or die($conexion->error);

$usuario = $_SESSION['login'];

if ($usuario['id_rol'] == 4 || $usuario['id_rol'] == 7) {
    include "../header_farmaciac.php";
} else if ($usuario['id_rol'] == 5) {
    include "../header_farmaciac.php";
} else {
    //session_unset();
    //session_destroy();
    echo "<script>window.Location='../../index.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1″/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
    integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- jQuery y Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        .btn-paginacion.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-paginacion.disabled:hover {
            background: white;
            color: var(--color-primario);
            transform: none;
            box-shadow: none;
        }

        /* ===== INFO PAGINACIÓN ===== */
        .info-paginacion {
            text-align: center;
            margin: 15px 0;
            color: #6c757d;
            font-size: 14px;
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

            .paginacion-moderna {
                flex-wrap: wrap;
                gap: 5px;
            }

            .btn-paginacion {
                min-width: 35px;
                height: 35px;
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
    </style>
</head>

<body>
<div class="container-fluid">
    <div class="container-moderno">
        <!-- Contenedor para los dos botones en la misma fila -->
        <div class="d-flex justify-content-between mb-4">
            <!-- Botón de regreso modernizado -->
            <a href="../../template/menu_farmaciacentral.php" class="btn-moderno btn-regresar">
                <i class="fas fa-arrow-left"></i> Regresar
            </a>

            <!-- Botón registrar proveedor -->
            <button type="button" class="btn-moderno" data-toggle="modal" data-target="#exampleModal">
                <i class="fa fa-plus"></i> Registrar Proveedor
            </button>
        </div>

        <div class="header-principal">
            <div class="contenido-header">
                <div class="icono-header">
                    <i class="fas fa-user-tie icono-principal"></i>
                </div>
                <center>CATÁLOGO DE PROVEEDORES</center>
            </div>
        </div>

        <br>
        <div class="form-group">
            <input type="text" class="form-control pull-right" style="width:20%" id="search" placeholder="Buscar...">
        </div>
        <br>

        <!-- Info de paginación -->
        <div class="info-paginacion">
            Mostrando <?php echo (($pagina_actual - 1) * $registros_por_pagina) + 1; ?> -
            <?php echo min($pagina_actual * $registros_por_pagina, $total_registros); ?>
            de <?php echo $total_registros; ?> proveedores
        </div>

        <div class="tabla-contenedor">
            <div class="table-responsive">
                <table class="table table-moderna" id="mytable">
                    <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> Id</th>
                        <th><i class="fas fa-user-tie"></i> Nombre Proveedor</th>
                        <th><i class="fas fa-map-marker-alt"></i> Dirección</th>
                        <th><i class="fas fa-phone"></i> Teléfono</th>
                        <th><i class="fas fa-envelope"></i> Email</th>
                        <th><i class="fas fa-id-card"></i> Licencia</th>
                        <th><i class="fas fa-user"></i> Contacto</th>
                        <th><i class="fas fa-check-circle"></i> Activo</th>
                        <th><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $resultado2 = $conexion->query("SELECT * FROM proveedores LIMIT $offset, $registros_por_pagina")
                    or die($conexion->error);

                    if ($resultado2->num_rows > 0) {
                        while ($row = $resultado2->fetch_assoc()) {
                            echo '<tr>'
                                    . '<td>' . htmlspecialchars($row['id_prov']) . '</td>'
                                    . '<td>' . htmlspecialchars($row['nom_prov']) . '</td>'
                                    . '<td>' . htmlspecialchars($row['dir_prov']) . '</td>'
                                    . '<td>' . htmlspecialchars($row['tel_prov']) . '</td>'
                                    . '<td>' . htmlspecialchars($row['email_prov']) . '</td>'
                                    . '<td>' . htmlspecialchars($row['lic_prov']) . '</td>'
                                    . '<td>' . htmlspecialchars($row['cont_prov']) . '</td>'
                                    . '<td>' . ($row['activo'] == 'SI'
                                            ? '<span style="color: green; font-weight: bold;">' . htmlspecialchars($row['activo']) . '</span>'
                                            : '<span style="color: red; font-weight: bold;">' . htmlspecialchars($row['activo']) . '</span>') . '</td>'
                                    . '<td><a href="edit_proveedor.php?id=' . $row['id_prov'] . '" title="Editar datos" class="btn btn-warning btn-sm"><span class="fa fa-edit" aria-hidden="true"></span></a></td>'
                                    . '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="9" class="mensaje-sin-resultados"><i class="fas fa-info-circle"></i><br>No se encontraron proveedores</td></tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginación -->
        <?php if ($total_paginas > 1): ?>
            <div class="contenedor-paginacion">
                <div class="paginacion-moderna">
                    <!-- Botón Anterior -->
                    <?php if ($pagina_actual > 1): ?>
                        <a href="?pagina=<?php echo $pagina_actual - 1; ?>" class="btn-paginacion">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php else: ?>
                        <span class="btn-paginacion disabled">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                    <?php endif; ?>

                    <!-- Números de página -->
                    <?php
                    // Calcular rango de páginas a mostrar
                    $inicio_rango = max(1, $pagina_actual - 2);
                    $fin_rango = min($total_paginas, $pagina_actual + 2);

                    // Mostrar primera página si no está en el rango
                    if ($inicio_rango > 1): ?>
                        <a href="?pagina=1" class="btn-paginacion">1</a>
                        <?php if ($inicio_rango > 2): ?>
                            <span class="btn-paginacion disabled">...</span>
                        <?php endif;
                    endif;

                    // Mostrar páginas del rango
                    for ($i = $inicio_rango; $i <= $fin_rango; $i++): ?>
                        <a href="?pagina=<?php echo $i; ?>"
                           class="btn-paginacion <?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor;

                    // Mostrar última página si no está en el rango
                    if ($fin_rango < $total_paginas): ?>
                        <?php if ($fin_rango < $total_paginas - 1): ?>
                            <span class="btn-paginacion disabled">...</span>
                        <?php endif; ?>
                        <a href="?pagina=<?php echo $total_paginas; ?>" class="btn-paginacion"><?php echo $total_paginas; ?></a>
                    <?php endif; ?>

                    <!-- Botón Siguiente -->
                    <?php if ($pagina_actual < $total_paginas): ?>
                        <a href="?pagina=<?php echo $pagina_actual + 1; ?>" class="btn-paginacion">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php else: ?>
                        <span class="btn-paginacion disabled">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">NUEVO PROVEEDOR</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <form action="insertar_proveedor.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="id_prov">

                    <div class="form-group">
                        <label class="control-label" for="nom_prov">Nombre:</label>
                        <input onkeyup="javascript:this.value=this.value.toUpperCase();"
                               type="text"
                               maxlength="50"
                               name="nom_prov"
                               class="form-control"
                               id="nom_prov"
                               placeholder="Ingresa el nombre del proveedor"
                               required
                               autofocus>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="dir_prov">Dirección:</label>
                        <input onkeyup="javascript:this.value=this.value.toUpperCase();"
                               type="text"
                               maxlength="50"
                               name="dir_prov"
                               class="form-control"
                               id="dir_prov"
                               placeholder="Ingresa la dirección"
                               required>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="tel_prov">Teléfono:</label>
                        <input type="number"
                               min="0"
                               name="tel_prov"
                               class="form-control"
                               id="tel_prov"
                               placeholder="Ingresa número telefónico"
                               required>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="correo">E-mail:</label>
                        <input type="text"
                               maxlength="50"
                               name="email_prov"
                               class="form-control"
                               id="email_prov"
                               placeholder="Ingresa el correo electrónico"
                               required>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="licencia">Licencia:</label>
                        <input onkeyup="javascript:this.value=this.value.toUpperCase();"
                               type="text"
                               maxlength="50"
                               name="lic_prov"
                               class="form-control"
                               id="lic_prov"
                               placeholder="Ingresa la licencia sanitaria"
                               required>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="contacto">Contacto:</label>
                        <input onkeyup="javascript:this.value=this.value.toUpperCase();"
                               type="text"
                               maxlength="50"
                               name="cont_prov"
                               class="form-control"
                               id="cont_prov"
                               placeholder="Ingresa el nombre del contacto"
                               required>
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