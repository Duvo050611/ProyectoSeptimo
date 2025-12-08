<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}

include '../../conexionbd.php';
$conexion = ConexionBD::getInstancia()->getConexion();
include("../header_enfermera.php");

$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];
$rol = $usuario['id_rol'];

function calculaedad($fechanacimiento) {
    list($ano, $mes, $dia) = explode("-", $fechanacimiento);
    $ano_diferencia = date("Y") - $ano;
    $mes_diferencia = date("m") - $mes;
    $dia_diferencia = date("d") - $dia;
    if ($dia_diferencia < 0 || $mes_diferencia < 0)
        $ano_diferencia--;
    return $ano_diferencia;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INEO Metepec - Cambio de Habitación</title>
    <link rel="icon" href="../../imagenes/SIF.PNG">

    <!-- Bootstrap & FontAwesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
          integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #0a0a0a 100%) !important;
            font-family: 'Roboto', sans-serif !important;
            min-height: 100vh;
            color: #ffffff !important;
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

        .wrapper {
            position: relative;
            z-index: 1;
        }

        /* Content wrapper */
        .content-wrapper {
            background: transparent !important;
            min-height: 100vh;
        }

        section.content {
            background: transparent !important;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        /* Contenedor principal */
        .container {
            position: relative;
            z-index: 1;
        }

        /* Título principal */
        .titulo-principal {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            color: #ffffff !important;
            padding: 25px 30px !important;
            font-size: 26px !important;
            font-weight: 700 !important;
            border-radius: 15px !important;
            margin: 20px 0 30px 0 !important;
            border: 2px solid #40E0FF !important;
            box-shadow: 0 8px 30px rgba(64, 224, 255, 0.3);
            text-align: center;
            letter-spacing: 2px;
            text-shadow: 0 0 20px rgba(64, 224, 255, 0.5);
            position: relative;
            overflow: hidden;
        }

        .titulo-principal::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(64, 224, 255, 0.1) 0%, transparent 70%);
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        /* Título sección */
        .titulo-seccion {
            background: linear-gradient(135deg, rgba(15, 52, 96, 0.9), rgba(22, 33, 62, 0.9));
            color: #40E0FF !important;
            padding: 15px 25px !important;
            font-size: 20px !important;
            font-weight: 600 !important;
            border-radius: 10px !important;
            margin: 20px 0 !important;
            border: 2px solid rgba(64, 224, 255, 0.4);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            text-align: center;
            letter-spacing: 1.5px;
            text-shadow: 0 0 15px rgba(64, 224, 255, 0.5);
        }

        /* Buscador */
        .search-container {
            margin: 20px 0;
            display: flex;
            justify-content: flex-end;
        }

        #search {
            background: linear-gradient(135deg, rgba(15, 52, 96, 0.6), rgba(22, 33, 62, 0.6)) !important;
            border: 2px solid rgba(64, 224, 255, 0.4) !important;
            color: #ffffff !important;
            padding: 12px 20px;
            border-radius: 25px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        #search::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        #search:focus {
            outline: none;
            border-color: #40E0FF !important;
            box-shadow: 0 0 20px rgba(64, 224, 255, 0.5);
            background: linear-gradient(135deg, rgba(22, 33, 62, 0.8), rgba(15, 52, 96, 0.8)) !important;
        }

        /* Tabla */
        .table-responsive {
            background: linear-gradient(135deg, rgba(15, 52, 96, 0.4), rgba(22, 33, 62, 0.4));
            padding: 20px;
            border-radius: 15px;
            border: 2px solid rgba(64, 224, 255, 0.3);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.5);
            margin-top: 20px;
            backdrop-filter: blur(10px);
        }

        .table {
            color: #ffffff !important;
            margin-bottom: 0;
        }

        .table thead th {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            color: #40E0FF !important;
            border: 2px solid rgba(64, 224, 255, 0.4) !important;
            padding: 15px 10px !important;
            font-weight: 600 !important;
            text-align: center !important;
            font-size: 14px !important;
            letter-spacing: 0.5px;
            text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
        }

        .table tbody td {
            border: 1px solid rgba(64, 224, 255, 0.2) !important;
            padding: 12px 10px !important;
            vertical-align: middle !important;
            text-align: center !important;
            font-size: 13px;
        }

        /* Filas según estado */
        td.fondo,
        tr.fila-ocupada td {
            background: linear-gradient(135deg, rgba(43, 45, 127, 0.8), rgba(36, 38, 100, 0.8)) !important;
            color: #ffffff !important;
        }

        td.fondo2,
        tr.fila-alta td {
            background: linear-gradient(135deg, rgba(46, 125, 50, 0.8), rgba(27, 94, 32, 0.8)) !important;
            color: #ffffff !important;
        }

        td.cuenta,
        tr.fila-mantenimiento td {
            background: linear-gradient(135deg, rgba(198, 40, 40, 0.8), rgba(183, 28, 28, 0.8)) !important;
            color: #ffffff !important;
        }

        td.fondo3,
        tr.fila-proceso td {
            background: linear-gradient(135deg, rgba(255, 152, 0, 0.8), rgba(245, 124, 0, 0.8)) !important;
            color: #ffffff !important;
        }

        tr.fila-libre td {
            background: linear-gradient(135deg, rgba(66, 66, 66, 0.6), rgba(33, 33, 33, 0.6)) !important;
            color: rgba(255, 255, 255, 0.5) !important;
        }

        /* Hover en filas */
        .table tbody tr:hover td {
            transform: scale(1.01);
            box-shadow: 0 4px 15px rgba(64, 224, 255, 0.2);
            transition: all 0.3s ease;
        }

        /* Botones */
        .btn-cambio {
            background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%) !important;
            border: 2px solid #4caf50 !important;
            color: #ffffff !important;
            padding: 8px 15px !important;
            border-radius: 8px !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
            font-size: 14px;
            font-weight: 600;
        }

        .btn-cambio:hover {
            transform: scale(1.1) !important;
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.5) !important;
            background: linear-gradient(135deg, #388e3c 0%, #2e7d32 100%) !important;
            border-color: #66bb6a !important;
        }

        .btn-cambio img {
            filter: brightness(1.2);
        }

        /* Scrollbar personalizado */
        ::-webkit-scrollbar {
            width: 12px;
            height: 12px;
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
        @media (max-width: 768px) {
            .titulo-principal {
                font-size: 20px !important;
                padding: 20px 15px !important;
                letter-spacing: 1px;
            }

            .titulo-seccion {
                font-size: 16px !important;
                padding: 12px 15px !important;
            }

            .table thead th,
            .table tbody td {
                font-size: 11px !important;
                padding: 8px 5px !important;
            }

            .search-container {
                justify-content: center;
            }

            #search {
                width: 100%;
                max-width: 400px;
            }
        }

        /* Animaciones de entrada */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .table-responsive {
            animation: fadeIn 0.6s ease-out;
        }

        /* Badge de estado */
        .badge-estado {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .badge-ocupado {
            background: linear-gradient(135deg, #2b2d7f, #1e1f5a);
            border: 1px solid #40E0FF;
        }

        .badge-alta {
            background: linear-gradient(135deg, #2e7d32, #1b5e20);
            border: 1px solid #4caf50;
        }

        .badge-mantenimiento {
            background: linear-gradient(135deg, #c62828, #b71c1c);
            border: 1px solid #ef5350;
        }

        .badge-proceso {
            background: linear-gradient(135deg, #f57c00, #e65100);
            border: 1px solid #ffa726;
        }

        /* Sidebar consistencia */
        .main-sidebar {
            background: linear-gradient(180deg, #16213e 0%, #0f3460 100%) !important;
            border-right: 2px solid #40E0FF !important;
            box-shadow: 4px 0 20px rgba(64, 224, 255, 0.15);
        }

        .sidebar-menu > li > a {
            color: #ffffff !important;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .sidebar-menu > li > a:hover,
        .sidebar-menu > li.active > a {
            background: rgba(64, 224, 255, 0.1) !important;
            border-left: 3px solid #40E0FF !important;
            color: #40E0FF !important;
        }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">

<section class="content container-fluid">
    <div class="container">

        <!-- Título principal -->
        <div class="titulo-principal">
            <i class="fas fa-exchange-alt"></i> CAMBIO DE HABITACIÓN
        </div>

        <!-- Sección Hospitalización -->
        <div class="titulo-seccion">
            <i class="fas fa-hospital"></i> HOSPITALIZACIÓN
        </div>

        <!-- Buscador -->
        <div class="search-container">
            <input type="text" class="form-control" id="search" placeholder="🔍 Buscar paciente, habitación o expediente...">
        </div>

        <!-- Tabla -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="mytable">
                <thead>
                <tr>
                    <th><i class="fas fa-exchange-alt"></i> Cambiar</th>
                    <th><i class="fas fa-bed"></i> Habitación</th>
                    <th><i class="far fa-calendar-alt"></i> Fecha Ingreso</th>
                    <th><i class="fas fa-user"></i> Paciente</th>
                    <th><i class="fas fa-birthday-cake"></i> Edad</th>
                    <th><i class="fas fa-notes-medical"></i> Motivo</th>
                    <th><i class="fas fa-folder"></i> Exp.</th>
                    <th><i class="fas fa-user-md"></i> Médico</th>
                    <th><i class="fas fa-check-circle"></i> Alta</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT * FROM cat_camas WHERE TIPO='HOSPITALIZACIÓN' ORDER BY num_cama ASC";
                $result = $conexion->query($sql);

                while ($row = $result->fetch_assoc()) {
                    $id_at_cam = $row['id_atencion'];
                    $estatus = $row['estatus'];
                    $num_cama = htmlspecialchars($row['num_cama']);

                    // Verificar si hay paciente asignado
                    if (!empty($id_at_cam) && $id_at_cam > 0) {
                        $sql_tabla = "SELECT p.fecnac, p.Id_exp, p.papell, p.sapell, p.nom_pac, 
                                                 di.fecha, di.motivo_atn, di.alta_med,
                                                 ru.pre, ru.papell as nom_doc 
                                          FROM dat_ingreso di
                                          INNER JOIN paciente p ON p.Id_exp = di.Id_exp
                                          LEFT JOIN dat_financieros df ON di.id_atencion = df.id_atencion
                                          LEFT JOIN reg_usuarios ru ON di.id_usua = ru.id_usua
                                          WHERE di.id_atencion = $id_at_cam 
                                          LIMIT 1";

                        $result_tabla = $conexion->query($sql_tabla);

                        if (!$result_tabla) {
                            // Error en la consulta - mostrar cama con error
                            echo '<tr class="fila-ocupada">';
                            echo '<td></td>';
                            echo '<td><strong>' . $num_cama . '</strong></td>';
                            echo '<td colspan="7">ERROR EN CONSULTA: ' . htmlspecialchars($conexion->error) . '</td>';
                            echo '</tr>';
                            continue;
                        }

                        $rowcount = mysqli_num_rows($result_tabla);

                        if ($rowcount != 0) {
                            $row_tabla = $result_tabla->fetch_assoc();
                            $alta = $row_tabla['alta_med'];

                            $date1 = date_create($row_tabla['fecha']);
                            $fecingr = date_format($date1, "d/m/Y H:i");

                            $nombre_completo = htmlspecialchars($row_tabla['nom_pac'] . ' ' . $row_tabla['papell'] . ' ' . $row_tabla['sapell']);
                            $edad = calculaedad($row_tabla['fecnac']);
                            $motivo = htmlspecialchars($row_tabla['motivo_atn']);
                            $expediente = htmlspecialchars($row_tabla['Id_exp']);
                            $medico = htmlspecialchars($row_tabla['pre'] . ' ' . $row_tabla['nom_doc']);

                            if ($alta == 'SI') {
                                // Fila con alta médica (verde)
                                echo '<tr class="fila-alta">';
                                echo '<td></td>';
                                echo '<td><strong>' . $num_cama . '</strong></td>';
                                echo '<td>' . $fecingr . '</td>';
                                echo '<td>' . $nombre_completo . '</td>';
                                echo '<td>' . $edad . ' años</td>';
                                echo '<td>' . $motivo . '</td>';
                                echo '<td>' . $expediente . '</td>';
                                echo '<td>' . $medico . '</td>';
                                echo '<td><span class="badge badge-alta">ALTA</span></td>';
                                echo '</tr>';
                            } else {
                                // Fila ocupada (azul)
                                echo '<tr class="fila-ocupada">';
                                echo '<td><a href="realiza_cambio_hab.php?id_cama=' . urlencode($row['num_cama']) .
                                        '&id_atencion=' . urlencode($row['id_atencion']) .
                                        '&tipo=' . urlencode($row['tipo']) .
                                        '&hab=' . urlencode($row['habitacion']) . '" class="btn btn-cambio btn-sm">';
                                echo '<i class="fas fa-bed"></i> Cambiar</a></td>';
                                echo '<td><strong>' . $num_cama . '</strong></td>';
                                echo '<td>' . $fecingr . '</td>';
                                echo '<td>' . $nombre_completo . '</td>';
                                echo '<td>' . $edad . ' años</td>';
                                echo '<td>' . $motivo . '</td>';
                                echo '<td>' . $expediente . '</td>';
                                echo '<td>' . $medico . '</td>';
                                echo '<td><span class="badge badge-ocupado">OCUPADO</span></td>';
                                echo '</tr>';
                            }
                        }
                    } elseif ($estatus == "MANTENIMIENTO") {
                        // Fila en mantenimiento (rojo)
                        echo '<tr class="fila-mantenimiento">';
                        echo '<td></td>';
                        echo '<td><strong>' . $num_cama . '</strong></td>';
                        echo '<td colspan="6">NO DISPONIBLE - MANTENIMIENTO</td>';
                        echo '<td><span class="badge badge-mantenimiento">MANTENIMIENTO</span></td>';
                        echo '</tr>';
                    } elseif ($estatus == "EN PROCESO DE LIBERA") {
                        // Fila en proceso (naranja)
                        echo '<tr class="fila-proceso">';
                        echo '<td></td>';
                        echo '<td><strong>' . $num_cama . '</strong></td>';
                        echo '<td colspan="6">CAMA EN PROCESO DE LIBERACIÓN</td>';
                        echo '<td><span class="badge badge-proceso">POR LIBERAR</span></td>';
                        echo '</tr>';
                    } else {
                        // Fila libre (gris)
                        echo '<tr class="fila-libre">';
                        echo '<td></td>';
                        echo '<td><strong>' . $num_cama . '</strong></td>';
                        echo '<td colspan="7">CAMA DISPONIBLE</td>';
                        echo '</tr>';
                    }
                }
                ?>
                </tbody>
            </table>
        </div>

    </div>
</section>

<footer class="main-footer">
    <?php include("../../template/footer.php"); ?>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

<script>
    // Buscador en tabla
    $(document).ready(function() {
        $("#search").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#mytable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        // Treeview del sidebar
        $('.treeview > a').on('click', function (e) {
            e.preventDefault();
            let parent = $(this).parent();
            let submenu = parent.find('.treeview-menu').first();

            parent.toggleClass('menu-open');
            submenu.slideToggle(200);
        });

        // Cerrar otros submenús cuando se abre uno nuevo
        $('.sidebar-menu .treeview > a').on('click', function() {
            var clickedMenu = $(this).parent();
            $('.sidebar-menu .treeview').not(clickedMenu).removeClass('menu-open');
            $('.sidebar-menu .treeview').not(clickedMenu).find('.treeview-menu').slideUp(200);
        });
    });

    // Deshabilitar clic derecho
    document.oncontextmenu = function() {
        return false;
    }
</script>

</body>
</html>