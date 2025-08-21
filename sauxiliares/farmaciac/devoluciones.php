<?php
session_start();

ob_start();
include "../../conexionbd.php";

$usuario = $_SESSION['login'];

if (isset($usuario['id_rol'])) {
    if ($usuario['id_rol'] == 11 || $usuario['id_rol'] == 4 || $usuario['id_rol'] == 5) {
        include "../header_farmaciac.php";
    } else {
        session_unset();
        session_destroy();
        echo "<script>window.location='../../index.php';</script>";
        exit();
    }
}

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta para obtener las facturas
$query_facturass = "SELECT id_compra, factura FROM ordenes_compra";
$result_facturass = mysqli_query($conexion, $query_facturass);

$fecha_movimiento = isset($_POST['fecha_movimiento']) ? $_POST['fecha_movimiento'] : null;
$factura = isset($_POST['factura']) ? $_POST['factura'] : null;
$Facturas = isset($_POST['factura']) ? $_POST['factura'] : null;
$id_compra = isset($_POST['factura']) ? $_POST['factura'] : null;

$proveedor = isset($_POST['proveedor']) ? $_POST['proveedor'] : null;
$folio = isset($_POST['folio']) ? $_POST['folio'] : null;
$movimiento = isset($_POST['Movimiento']) ? $_POST['Movimiento'] : null;
$id_usua = $_SESSION['login']['id_usua'];
$responsable = isset($_POST['responsable_movimiento']) ? $_POST['responsable_movimiento'] : null;
$resolucion = isset($_POST['resolucion_solicitud']) ? $_POST['resolucion_solicitud'] : null;
$areas = isset($_POST['area']) ? $_POST['area'] : null;

if (isset($_POST['guardar']) && isset($_POST['factura'])) {
    foreach ($_POST['seleccionar_item'] as $entrada_id => $seleccionado) {
        if ($seleccionado) {
            $motivo_seleccionado = isset($_POST['motivo_seleccionado'][$entrada_id]) ? $_POST['motivo_seleccionado'][$entrada_id] : '';
            $otro_motivo = isset($_POST['otro_motivo'][$entrada_id]) ? $_POST['otro_motivo'][$entrada_id] : '';
            $cantidad_a_devolver = isset($_POST['devolver_qty'][$entrada_id]) ? $_POST['devolver_qty'][$entrada_id] : 0;

            $query_item_info = "SELECT item_id, entrada_lote, entrada_caducidad FROM entradas_almacen WHERE entrada_id = ?";
            $stmt_item = $conexion->prepare($query_item_info);
            $stmt_item->bind_param("i", $entrada_id);
            $stmt_item->execute();
            $result_item_info = $stmt_item->get_result();

            if ($result_item_info->num_rows > 0) {
                $row_item_info = $result_item_info->fetch_assoc();
                $item_id = $row_item_info['item_id'];
                $lote = $row_item_info['entrada_lote'];
                $caducidad = $row_item_info['entrada_caducidad'];

                $motivo = ($motivo_seleccionado == "Otro") ? $otro_motivo : $motivo_seleccionado;
                if (empty($motivo)) {
                    echo "<script>alert('Por favor, ingresa un motivo para el ítem ID $item_id.');</script>";
                    continue;
                }

                if ($cantidad_a_devolver > 0) {
                    $query_insert_dev = "INSERT INTO dev_proveedor (item_id, dev_fecha, dev_lote, dev_caducidad, dev_qty, dev_motivo, id_usua, dev_factura, dev_proveedor, dev_folio, dev_movimiento, dev_responsable, dev_resolucion, ubicacion_id) 
                                         VALUES ('$item_id', NOW(), '$lote', '$caducidad', '$cantidad_a_devolver', '$motivo', '$id_usua', '$factura', '$proveedor', '$folio', '$movimiento', '$responsable', '$resolucion', '$areas')";
                    mysqli_query($conexion, $query_insert_dev);
                }
            }
        }
    }

    echo "<script>alert('Devoluciones guardadas exitosamente.'); window.location='kardex.php';</script>";
}

// Consulta para obtener los proveedores
$query = "SELECT id_prov, nom_prov FROM proveedores";
$result = mysqli_query($conexion, $query);

// Consulta para obtener las facturas
$query_facturas = "SELECT DISTINCT factura FROM entradas_almacen";
$result_facturas = mysqli_query($conexion, $query_facturas);

$result_items = $conexion->query("SELECT * FROM entradas_almacen WHERE 1=0");

if (isset($_POST['factura'])) {
    $factura = $_POST['factura'];
    $query_items = "SELECT entrada_id, item_id, entrada_qty, entrada_lote, entrada_caducidad 
                    FROM entradas_almacen 
                    WHERE factura = ?";
    $stmt = $conexion->prepare($query_items);
    $stmt->bind_param("s", $factura);
    $stmt->execute();
    $result_items = $stmt->get_result();
}

if (isset($_POST['generar_pdf'])) {
    require_once '../../vendor/autoload.php';

    ob_end_clean();

    // Crear una instancia del PDF
    $pdf = new TCPDF();

    // Configurar el PDF
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Nombre del Autor');
    $pdf->SetTitle('Formato para Devolución a Proveedor / Merma');
    $pdf->SetMargins(10, 10, 10);
    $pdf->AddPage();

    // Consulta para obtener el nombre del proveedor usando id_prov
    $query_proveedor = "SELECT nom_prov FROM proveedores WHERE id_prov = ?";
    $stmt_proveedor = $conexion->prepare($query_proveedor);
    $stmt_proveedor->bind_param("i", $proveedor);
    $stmt_proveedor->execute();
    $result_proveedor = $stmt_proveedor->get_result();
    $row_proveedor = $result_proveedor->fetch_assoc();

    $proveedor = $row_proveedor['nom_prov'] ?? '';

    if ($id_compra !== null && !empty($id_compra)) {
        $query_factura = "SELECT id_compra, factura FROM ordenes_compra WHERE id_compra = ?";
        $stmt_factura = $conexion->prepare($query_factura);
        $stmt_factura->bind_param("i", $id_compra);
        $stmt_factura->execute();
        $result_factura = $stmt_factura->get_result();
        $row_factura = $result_factura->fetch_assoc();

        $factura = htmlspecialchars($row_factura['factura'] ?? '');
    } else {
        $factura = null;
    }

    // Definir el contenido HTML con estilos CSS
    $html = '
    <div style="border: 2px solid black; padding: 10px; text-align: center; width: 300px; margin: 0 auto; margin-bottom: 20px;">
        <h2 style="font-size: 18px;">Formato para Devolución a Proveedor / Merma</h2>
        <p style="font-size: 12px;">Código: FO-VEN07-FAR-001</p>
    </div>
    <p style="text-align: center; font-size: 10px; margin-top: 20px;">
        La información contenida en este formato es de tipo confidencial y para uso exclusivo del SANATORIO VENECIA.
        Queda prohibida su reproducción parcial, total o la transmisión por cualquier sistema de recuperación y
        almacenaje de información, en ninguna forma, por ningún medio sin previa autorización de la Dirección General.
    </p>';

    $html .= '<br><table cellpadding="4">
        <tr><td>Fecha:</td><td>' . $fecha_movimiento . '</td></tr>
        <tr><td>Movimiento:</td><td>' . $movimiento . '</td></tr>
    <tr><td>Factura:</td><td>' . $factura . '</td></tr>
    <tr><td>Proveedor:</td><td>' . htmlspecialchars($proveedor) . '</td></tr>
        <tr><td>Folio:</td><td>' . $folio . '</td></tr>
        <tr><td>Responsable:</td><td>' . $responsable . '</td></tr>
        <tr><td>Resolución:</td><td>' . $resolucion . '</td></tr>
        <tr><td>Área:</td><td>' . $areas . '</td></tr>
    </table><br>';

    $html .= '<table border="1" cellpadding="5">
        <tr>
            <th>Item</th>
            <th>Motivo</th>
            <th>Datos</th>
            <th>Cantidad a Devolver</th>
        </tr>';

    if (isset($_POST['motivo_seleccionado']) && isset($_POST['devolver_qty'])) {
        foreach ($_POST['motivo_seleccionado'] as $entrada_id => $motivo) {
            $cantidadDevolver = $_POST['devolver_qty'][$entrada_id] ?? '0';

            if ($motivo === 'Otro') {
                $motivo = $_POST['otro_motivo'][$entrada_id] ?? '';
            }

            $query_lote_caducidad = "SELECT entrada_lote, item_id, entrada_caducidad FROM entradas_almacen WHERE entrada_id = ?";
            $stmt_lote_caducidad = $conexion->prepare($query_lote_caducidad);
            $stmt_lote_caducidad->bind_param("i", $entrada_id);
            $stmt_lote_caducidad->execute();
            $result_lote_caducidad = $stmt_lote_caducidad->get_result();
            $row_lote_caducidad = $result_lote_caducidad->fetch_assoc();

            $lote = htmlspecialchars($row_lote_caducidad['entrada_lote'] ?? '');
            $caducidad = htmlspecialchars($row_lote_caducidad['entrada_caducidad'] ?? '');
            $itemId = htmlspecialchars($row_lote_caducidad['item_id'] ?? '');

            $query_item_name = "SELECT item_name FROM item_almacen WHERE item_id = ?";
            $stmt_item_name = $conexion->prepare($query_item_name);
            $stmt_item_name->bind_param("i", $itemId);
            $stmt_item_name->execute();
            $result_item_name = $stmt_item_name->get_result();
            $row_item_name = $result_item_name->fetch_assoc();

            $itemName = htmlspecialchars($row_item_name['item_name'] ?? '');

            $html .= '<tr>
                    <td>' . $itemName . '</td>
                    <td>' . htmlspecialchars($motivo) . '</td>
                    <td> (Lote: ' . $lote . ', Caducidad: ' . $caducidad . ')</td>
                    <td>' . htmlspecialchars($cantidadDevolver) . '</td>
                </tr>';
        }
    }
    $html .= '</table>';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Ln(10);

    $yPos = $pdf->GetY();
    $pageWidth = $pdf->getPageWidth();
    $totalWidth = 200;
    $height = 45;
    $numColumns = 5;
    $columnWidth = $totalWidth / $numColumns;
    $xPos = ($pageWidth - $totalWidth) / 2;

    $pdf->SetFont('helvetica', '', 8);
    $pdf->Rect($xPos, $yPos, $totalWidth, $height);

    $signLabels = [
            'Nombre y firma del proveedor',
            'Nombre y firma del responsable del movimiento',
            'Nombre y firma de coordinación de farmacia',
            'Nombre y firma administración',
            'Nombre y firma responsable sanitario'
    ];

    for ($i = 0; $i < $numColumns; $i++) {
        $currentX = $xPos + ($i * $columnWidth);

        if ($i > 0) {
            $pdf->Line($currentX, $yPos, $currentX, $yPos + $height);
        }

        $pdf->MultiCell($columnWidth - 4, $height, $signLabels[$i], 0, 'C', 0, 1, $currentX + 2, $yPos + 0, true, 0, false, true, $height, 'B');
    }

    $pdf->Output('devolucion_proveedor_merma.pdf', 'I');
    exit();
}
?>

    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Formato para Devolución a Proveedor / Merma</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
              integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <style>
            /* ===== VARIABLES CSS ===== */
            :root {
                --color-primario: #2b2d7f;
                --color-secundario: #1a1c5a;
                --color-fondo: #f8f9ff;
                --color-borde: #e8ebff;
                --sombra: 0 4px 15px rgba(0, 0, 0, 0.1);
                --color-success: #28a745;
                --color-warning: #ffc107;
                --color-danger: #dc3545;
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
                margin: 5px;
            }

            .btn-regresar {
                background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
                color: white !important;
            }

            .btn-enviar {
                background: linear-gradient(135deg, var(--color-success) 0%, #20c997 100%);
                color: white !important;
            }

            .btn-pdf {
                background: linear-gradient(135deg, #007BFF 0%, #0056b3 100%);
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
                padding: 25px;
                background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
                border-radius: 20px;
                color: white;
                box-shadow: var(--sombra);
                border: 2px solid black;
            }

            .header-principal .icono-principal {
                font-size: 40px;
                margin-bottom: 10px;
                display: block;
            }

            .header-principal h2 {
                font-size: 28px;
                font-weight: 700;
                margin: 10px 0;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            }

            .header-principal p {
                font-size: 16px;
                margin: 0;
                opacity: 0.9;
            }

            .disclaimer {
                background: #fff3cd;
                border: 2px solid #ffc107;
                border-radius: 15px;
                padding: 20px;
                margin: 20px 0;
                text-align: center;
                font-size: 14px;
                color: #856404;
                box-shadow: var(--sombra);
            }

            .disclaimer i {
                font-size: 24px;
                margin-bottom: 10px;
                color: #ffc107;
            }

            /* ===== SECCIONES ===== */
            .seccion-moderna {
                background: white;
                border: 2px solid var(--color-borde);
                border-radius: 15px;
                padding: 25px;
                margin: 25px 0;
                box-shadow: var(--sombra);
            }

            .titulo-seccion {
                background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
                color: white;
                padding: 15px 20px;
                border-radius: 12px;
                margin: -10px -10px 20px -10px;
                text-align: center;
                font-size: 20px;
                font-weight: 600;
                box-shadow: var(--sombra);
            }

            .titulo-seccion i {
                margin-right: 10px;
                font-size: 22px;
            }

            /* ===== FORMULARIOS MODERNOS ===== */
            .form-group-moderno {
                margin-bottom: 20px;
            }

            .form-label-moderno {
                font-weight: 600;
                color: var(--color-primario);
                margin-bottom: 8px;
                display: block;
                font-size: 14px;
            }

            .form-control-moderno {
                width: 100%;
                padding: 12px 15px;
                border: 2px solid var(--color-borde);
                border-radius: 10px;
                font-size: 14px;
                transition: all 0.3s ease;
                background: white;
                box-sizing: border-box;
            }

            .form-control-moderno:focus {
                border-color: var(--color-primario);
                box-shadow: 0 0 0 3px rgba(43, 45, 127, 0.1);
                outline: none;
            }

            .grid-formulario {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 20px;
                margin: 20px 0;
            }

            /* ===== TABLA MODERNIZADA ===== */
            .tabla-contenedor {
                background: white;
                border-radius: 15px;
                overflow: hidden;
                box-shadow: var(--sombra);
                border: 2px solid var(--color-borde);
                margin: 20px 0;
            }

            .table-moderna {
                margin: 0;
                font-size: 13px;
                width: 100%;
                border-collapse: collapse;
            }

            .table-moderna thead th {
                background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
                color: white;
                border: none;
                padding: 15px 12px;
                font-weight: 600;
                text-align: center;
                font-size: 12px;
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
                padding: 12px 10px;
                vertical-align: middle;
                border: none;
                text-align: center;
                background-color: rgba(179, 206, 247, 0.2);
            }

            /* ===== ELEMENTOS DE FORMULARIO ESPECIALES ===== */
            .checkbox-moderno {
                width: 20px;
                height: 20px;
                accent-color: var(--color-primario);
                cursor: pointer;
            }

            .select-motivo {
                min-width: 200px;
            }

            .input-otro-motivo {
                margin-top: 8px;
                font-style: italic;
            }

            .input-cantidad {
                max-width: 100px;
                text-align: center;
                font-weight: 600;
            }

            /* ===== CONTENEDOR DE BOTONES ===== */
            .contenedor-acciones {
                text-align: center;
                margin: 30px 0;
                padding: 25px;
                background: var(--color-fondo);
                border: 2px solid var(--color-borde);
                border-radius: 15px;
                box-shadow: var(--sombra);
            }

            /* ===== SELECTOR DE FACTURA DESTACADO ===== */
            .selector-factura {
                background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
                border: 2px solid #2196f3;
                border-radius: 15px;
                padding: 20px;
                margin: 20px 0;
                box-shadow: var(--sombra);
            }

            .selector-factura select {
                font-size: 16px;
                padding: 15px;
                font-weight: 600;
            }

            /* ===== MENSAJE SIN RESULTADOS ===== */
            .mensaje-sin-resultados {
                text-align: center;
                padding: 40px 20px;
                color: var(--color-primario);
                font-size: 16px;
                font-weight: 600;
            }

            .mensaje-sin-resultados i {
                font-size: 48px;
                margin-bottom: 15px;
                opacity: 0.5;
            }

            /* ===== RESPONSIVE DESIGN ===== */
            @media (max-width: 768px) {
                .container-moderno {
                    margin: 10px;
                    padding: 20px;
                    border-radius: 15px;
                }

                .grid-formulario {
                    grid-template-columns: 1fr;
                    gap: 15px;
                }

                .btn-moderno {
                    width: 100%;
                    justify-content: center;
                    margin: 5px 0;
                }

                .table-moderna {
                    font-size: 11px;
                }

                .table-moderna thead th,
                .table-moderna tbody td {
                    padding: 8px 6px;
                }

                .header-principal h2 {
                    font-size: 22px;
                }

                .titulo-seccion {
                    font-size: 18px;
                    margin: -5px -5px 15px -5px;
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

            .seccion-moderna,
            .tabla-contenedor {
                animation: fadeInUp 0.6s ease-out 0.1s both;
            }

            /* ===== ESTADOS DE ELEMENTOS ===== */
            .form-control-moderno:disabled {
                background-color: #f8f9fa;
                opacity: 0.6;
                cursor: not-allowed;
            }

            .destacar-item {
                background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%) !important;
                border-left: 4px solid #ff9800;
            }
        </style>

        <script>
            function mostrarOtroMotivo(selectElement, itemId) {
                const otroMotivoInput = document.getElementById("otro_motivo_" + itemId);
                otroMotivoInput.disabled = selectElement.value !== "Otro";
                if (otroMotivoInput.disabled) {
                    otroMotivoInput.value = "";
                }
            }

            function highlightSelectedItems() {
                const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="seleccionar_item"]');
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const row = this.closest('tr');
                        if (this.checked) {
                            row.classList.add('destacar-item');
                        } else {
                            row.classList.remove('destacar-item');
                        }
                    });
                });
            }

            document.addEventListener('DOMContentLoaded', highlightSelectedItems);
        </script>
    </head>

    <body>
    <div class="container-fluid">
        <div class="container-moderno">
            <!-- Botón de regreso -->
            <div class="d-flex justify-content-start mb-4">
                <a href="kardex.php" class="btn-moderno btn-regresar">
                    <i class="fas fa-arrow-left"></i> Regresar
                </a>
            </div>

            <!-- Header principal -->
            <div class="header-principal">
                <i class="fas fa-undo-alt icono-principal"></i>
                <h2>Formato para Devolución a Proveedor / Merma</h2>
                <p>Código: FO-VEN07-FAR-001</p>
            </div>

            <!-- Disclaimer -->
            <div class="disclaimer">
                <i class="fas fa-shield-alt"></i>
                <p><strong>INFORMACIÓN CONFIDENCIAL</strong></p>
                <p>La información contenida en este formato es de tipo confidencial y para uso exclusivo del SANATORIO VENECIA.
                    Queda prohibida su reproducción parcial, total o la transmisión por cualquier sistema de recuperación y
                    almacenaje de información, en ninguna forma, por ningún medio sin previa autorización de la Dirección General.</p>
            </div>

            <form method="post" action="">
                <!-- Detalles del Movimiento -->
                <div class="seccion-moderna">
                    <div class="titulo-seccion">
                        <i class="fas fa-clipboard-list"></i> Detalles del Movimiento
                    </div>

                    <div class="grid-formulario">
                        <div class="form-group-moderno">
                            <label class="form-label-moderno"><i class="fas fa-calendar"></i> FECHA:</label>
                            <input type="date" name="fecha_movimiento" class="form-control-moderno" required>
                        </div>

                        <div class="form-group-moderno">
                            <label class="form-label-moderno"><i class="fas fa-map-marker-alt"></i> ÁREA:</label>
                            <select name="area" class="form-control-moderno" required>
                                <option value="">Seleccione un área</option>
                                <?php
                                $ubicaciones = $conexion->query("SELECT ubicacion_id, nombre_ubicacion FROM ubicaciones_almacen") or die("Error al obtener ubicaciones: " . $conexion->error);
                                while ($row_ubicacion = mysqli_fetch_assoc($ubicaciones)) {
                                    echo '<option value="' . $row_ubicacion['ubicacion_id'] . '">' . $row_ubicacion['nombre_ubicacion'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group-moderno">
                            <label class="form-label-moderno"><i class="fas fa-user"></i> RESPONSABLE DEL MOVIMIENTO:</label>
                            <input type="text" name="responsable_movimiento" class="form-control-moderno" placeholder="Ingrese el nombre del responsable" required>
                        </div>

                        <div class="form-group-moderno">
                            <label class="form-label-moderno"><i class="fas fa-truck"></i> PROVEEDOR:</label>
                            <select name="proveedor" class="form-control-moderno" required>
                                <option value="">Seleccione un proveedor</option>
                                <?php
                                mysqli_data_seek($result, 0);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<option value="' . $row['id_prov'] . '">' . $row['nom_prov'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group-moderno">
                            <label class="form-label-moderno"><i class="fas fa-hashtag"></i> FOLIO:</label>
                            <input type="text" name="folio" class="form-control-moderno" placeholder="Ingrese el folio" required>
                        </div>

                        <div class="form-group-moderno">
                            <label class="form-label-moderno"><i class="fas fa-clipboard-check"></i> RESOLUCIÓN DE LA SOLICITUD:</label>
                            <select name="resolucion_solicitud" class="form-control-moderno" required>
                                <option value="">Seleccione una opción</option>
                                <option value="Aprobada">Aprobada</option>
                                <option value="Rechazada">Rechazada</option>
                                <option value="Reemplazo">Reemplazo</option>
                                <option value="Pendiente">Pendiente</option>
                            </select>
                        </div>

                        <!-- Campos movidos aquí: FACTURA y MOVIMIENTO -->
                        <div class="form-group-moderno">
                            <label class="form-label-moderno"><i class="fas fa-file-invoice"></i> FACTURA:</label>
                            <select name="factura" class="form-control-moderno" required>
                                <option value="">Seleccione una factura</option>
                                <?php
                                mysqli_data_seek($result_facturass, 0);
                                while ($row_factura = mysqli_fetch_assoc($result_facturass)) {
                                    echo '<option value="' . $row_factura['id_compra'] . '">' . $row_factura['factura'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group-moderno">
                            <label class="form-label-moderno"><i class="fas fa-exchange-alt"></i> MOVIMIENTO:</label>
                            <select name="Movimiento" class="form-control-moderno" required>
                                <option value="" selected disabled>Seleccione un movimiento</option>
                                <option value="Devolucion">Devolución</option>
                                <option value="Merma">Merma</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Selector de Factura para Items -->
                <div class="selector-factura">
                    <div class="form-group-moderno mb-0">
                        <label class="form-label-moderno"><i class="fas fa-search"></i> Buscar Items por Factura:</label>
                        <select name="factura" id="factura" class="form-control-moderno" onchange="this.form.submit();">
                            <option value="">Seleccione una factura para ver sus items</option>
                            <?php if ($result_facturas->num_rows > 0) : ?>
                                <?php while ($row = $result_facturas->fetch_assoc()) : ?>
                                    <option value="<?php echo htmlspecialchars($row['factura']); ?>"
                                            <?php echo (isset($_POST['factura']) && $_POST['factura'] == $row['factura']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($row['factura']); ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <option value="">No hay facturas disponibles</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <!-- Tabla de Items -->
                <?php if (isset($_POST['factura']) && $result_items->num_rows > 0) : ?>
                    <div class="seccion-moderna">
                        <div class="titulo-seccion">
                            <i class="fas fa-boxes"></i> Items de la Factura: <?php echo htmlspecialchars($factura); ?>
                        </div>

                        <div class="tabla-contenedor">
                            <table class="table-moderna">
                                <thead>
                                <tr>
                                    <th><i class="fas fa-check-square"></i> Seleccionar</th>
                                    <th><i class="fas fa-exclamation-triangle"></i> Motivo</th>
                                    <th><i class="fas fa-tag"></i> Ítem</th>
                                    <th><i class="fas fa-sort-numeric-down"></i> Cantidad a Devolver</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php while ($row_item = $result_items->fetch_assoc()) : ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox"
                                                   name="seleccionar_item[<?php echo $row_item['entrada_id']; ?>]"
                                                   value="1"
                                                   class="checkbox-moderno">
                                        </td>
                                        <td>
                                            <select name="motivo_seleccionado[<?php echo $row_item['entrada_id']; ?>]"
                                                    class="form-control-moderno select-motivo"
                                                    onchange="mostrarOtroMotivo(this, <?php echo $row_item['entrada_id']; ?>)">
                                                <option value="" selected disabled>Seleccione un motivo</option>
                                                <option value="Ruptura del envase">Ruptura del envase</option>
                                                <option value="Cambios de coloración o apariencia">Cambios de coloración o apariencia</option>
                                                <option value="Producto decolorado">Producto decolorado</option>
                                                <option value="Tabletas rotas o pulverizadas">Tabletas rotas o pulverizadas</option>
                                                <option value="Líquidos transparentes con partículas anormales">Líquidos transparentes con partículas anormales</option>
                                                <option value="Merma">Merma</option>
                                                <option value="Producto inflado">Producto inflado</option>
                                                <option value="Producto manchado">Producto manchado</option>
                                                <option value="Producto caducado">Producto caducado</option>
                                                <option value="Ausencia lote y caducidad">Ausencia lote y caducidad</option>
                                                <option value="Caducidad menor a un año">Caducidad menor a un año</option>
                                                <option value="Producto no solicitado">Producto no solicitado</option>
                                                <option value="Otro">Otro (especifique abajo)</option>
                                            </select>
                                            <input type="text"
                                                   name="otro_motivo[<?php echo $row_item['entrada_id']; ?>]"
                                                   id="otro_motivo_<?php echo $row_item['entrada_id']; ?>"
                                                   class="form-control-moderno input-otro-motivo"
                                                   placeholder="Especifique si seleccionó 'Otro'"
                                                   disabled>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($row_item['item_id']); ?></strong><br>
                                            <small>
                                                <i class="fas fa-vial"></i> Lote: <?php echo htmlspecialchars($row_item['entrada_lote']); ?><br>
                                                <i class="fas fa-calendar-times"></i> Caducidad: <?php echo htmlspecialchars($row_item['entrada_caducidad']); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <input type="number"
                                                   name="devolver_qty[<?php echo $row_item['entrada_id']; ?>]"
                                                   class="form-control-moderno input-cantidad"
                                                   min="0"
                                                   max="<?php echo htmlspecialchars($row_item['entrada_qty']); ?>"
                                                   placeholder="0">
                                            <small class="text-muted">Máx: <?php echo htmlspecialchars($row_item['entrada_qty']); ?></small>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php elseif (isset($_POST['factura'])) : ?>
                    <div class="mensaje-sin-resultados">
                        <i class="fas fa-info-circle"></i><br>
                        No hay ítems disponibles para esta factura.
                    </div>
                <?php endif; ?>

                <!-- Botones de Acción -->
                <div class="contenedor-acciones">
                    <button type="submit" name="guardar" class="btn-moderno btn-enviar">
                        <i class="fas fa-save"></i> Enviar Devoluciones
                    </button>
                    <button type="submit" name="generar_pdf" class="btn-moderno btn-pdf">
                        <i class="fas fa-file-pdf"></i> Generar PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function mostrarOtroMotivo(selectElement, id) {
            const otroMotivoInput = document.getElementById('otro_motivo_' + id);
            if (selectElement.value === 'Otro') {
                otroMotivoInput.disabled = false;
                otroMotivoInput.style.display = 'block';
            } else {
                otroMotivoInput.disabled = true;
                otroMotivoInput.style.display = 'none';
                otroMotivoInput.value = '';
            }
        }
    </script>
    </body>
</html>