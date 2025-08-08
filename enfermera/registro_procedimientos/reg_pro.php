<?php
ob_start(); // Start output buffering
session_start();
require_once '../../conexionbd.php';
include '../header_enfermera.php';

$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];

// Security: Validate session
if (!isset($_SESSION['login']) || !is_array($_SESSION['login']) || !isset($_SESSION['login']['id_usua'])) {
    header('Location: ../../../../index.php');
    exit;
}

// Check session timeout
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header('Location: ../../../../index.php');
    exit;
}
$_SESSION['last_activity'] = time();

// Generate CSRF token only if not already set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

date_default_timezone_set('America/Mexico_City');

// Procesar medicamentos (moved to top to avoid headers already sent)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['enviar_medicamentos'])) {
    // Validar CSRF token
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
        ob_end_clean(); // Clear any output
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Token CSRF inválido. Recargue la página e intente de nuevo.']);
        file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - CSRF Error: Token recibido: ' . ($_POST['csrf_token'] ?? 'Ninguno') . ', Token esperado: ' . ($_SESSION['csrf_token'] ?? 'Ninguno') . "\n", FILE_APPEND);
        exit;
    }

    $fechaActual = date('Y-m-d H:i:s');
    ob_end_clean(); // Clear any output before sending JSON
    header('Content-Type: application/json');

    if (!isset($_SESSION['medicamento_seleccionado']) || empty($_SESSION['medicamento_seleccionado']) || !is_array($_SESSION['medicamento_seleccionado'])) {
        echo json_encode(['success' => false, 'message' => 'No hay registros en la memoria para procesar.']);
        exit;
    }

    $conexion->begin_transaction();

    try {
        foreach ($_SESSION['medicamento_seleccionado'] as $index => $medicamento) {
            if (!isset($medicamento['paciente'], $medicamento['medicamento'], $medicamento['lote'], $medicamento['cantidad'], $medicamento['existe_id'], $medicamento['id_atencion'], $medicamento['item_id'], $medicamento['precio'])) {
                throw new Exception("Datos incompletos en la sesión para el medicamento en el índice $index.");
            }

            $paciente = $medicamento['paciente'];
            $nombreMedicamento = $medicamento['medicamento'];
            $loteNombre = $medicamento['lote'];
            $cantidadLote = intval($medicamento['cantidad']);
            $existeId = intval($medicamento['existe_id']);
            $Id_Atencion = intval($medicamento['id_atencion']);
            $itemId = intval($medicamento['item_id']);
            $salidaCostsu = floatval($medicamento['precio']);

            $queryItemAlmacen = "SELECT item_name, item_price FROM item_almacen WHERE item_id = ?";
            $stmt = $conexion->prepare($queryItemAlmacen);
            if (!$stmt) {
                throw new Exception("Error preparando consulta item_almacen: " . $conexion->error);
            }
            $stmt->bind_param("i", $itemId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 0) {
                throw new Exception("No se encontró el ítem con ID $itemId.");
            }
            $itemData = $result->fetch_assoc();
            $salidaCostsu = $itemData['item_price'];
            $stmt->close();

            $selectExistenciasQuery = "SELECT existe_qty, existe_caducidad, existe_salidas FROM existencias_almacenh WHERE existe_id = ?";
            $stmtSelect = $conexion->prepare($selectExistenciasQuery);
            if (!$stmtSelect) {
                throw new Exception("Error preparando consulta existencias_almacenh: " . $conexion->error);
            }
            $stmtSelect->bind_param('i', $existeId);
            $stmtSelect->execute();
            $stmtSelect->bind_result($existeQty, $caducidad, $existeSalidas);
            if (!$stmtSelect->fetch()) {
                throw new Exception("No se encontró el lote con existe_id $existeId.");
            }
            $stmtSelect->close();

            if ($existeQty < $cantidadLote) {
                throw new Exception("El lote \"$loteNombre\" no tiene suficiente stock. Disponible: $existeQty, requerido: $cantidadLote.");
            }

            $nuevaExistenciaQty = $existeQty - $cantidadLote;
            $nuevaExistenciaSalidas = $existeSalidas + $cantidadLote;

            $insert_kardex = "
                INSERT INTO kardex_almacenh (
                    kardex_fecha, item_id, kardex_lote, kardex_caducidad, kardex_inicial, kardex_entradas, kardex_salidas, kardex_qty, 
                    kardex_dev_stock, kardex_dev_merma, kardex_movimiento, kardex_destino, id_surte
                ) 
                VALUES (NOW(), ?, ?, ?, 0, 0, ?, 0, 0, 0, 'Salida', 'QUIROFANO', ?)
            ";
            $stmt_kardex = $conexion->prepare($insert_kardex);
            if (!$stmt_kardex) {
                throw new Exception("Error preparando consulta kardex_almacenh: " . $conexion->error);
            }
            $stmt_kardex->bind_param('issii', $itemId, $loteNombre, $caducidad, $cantidadLote, $id_usua);
            if (!$stmt_kardex->execute()) {
                throw new Exception("Error insertando en kardex_almacenh: " . $stmt_kardex->error);
            }
            $stmt_kardex->close();

            $salio = "QUIROFANO";
            $queryInsercion = "
                INSERT INTO salidas_almacenh (
                    item_id, item_name, salida_fecha, salida_lote, salida_caducidad, salida_qty, salida_costsu, id_usua, id_atencion, solicita, fecha_solicitud, salio
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ";
            $stmtInsertSalida = $conexion->prepare($queryInsercion);
            if (!$stmtInsertSalida) {
                throw new Exception("Error preparando consulta salidas_almacenh: " . $conexion->error);
            }
            $solicita = 0;
            $stmtInsertSalida->bind_param(
                "issssdiisiss",
                $itemId,
                $nombreMedicamento,
                $fechaActual,
                $loteNombre,
                $caducidad,
                $cantidadLote,
                $salidaCostsu,
                $id_usua,
                $Id_Atencion,
                $solicita,
                $fechaActual,
                $salio
            );
            if (!$stmtInsertSalida->execute()) {
                throw new Exception("Error insertando en salidas_almacenh: " . $stmtInsertSalida->error);
            }
            $stmtInsertSalida->close();

            $insertDatCtapacQuery = "
                INSERT INTO dat_ctapac (
                    id_atencion, prod_serv, insumo, cta_fec, cta_cant, cta_tot, id_usua, cta_activo, existe_lote, existe_caducidad, centro_cto
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ";
            $stmtInsertDatCtapac = $conexion->prepare($insertDatCtapacQuery);
            if (!$stmtInsertDatCtapac) {
                throw new Exception("Error preparando consulta dat_ctapac: " . $conexion->error);
            }
            $prodServ = 'PC';
            $ctaActivo = 'SI';
            $centroCto = 'QUIROFANO';
            $stmtInsertDatCtapac->bind_param(
                'isssddissss',
                $Id_Atencion,
                $prodServ,
                $itemId,
                $fechaActual,
                $cantidadLote,
                $salidaCostsu,
                $id_usua,
                $ctaActivo,
                $loteNombre,
                $caducidad,
                $centroCto
            );
            if (!$stmtInsertDatCtapac->execute()) {
                throw new Exception("Error insertando en dat_ctapac: " . $stmtInsertDatCtapac->error);
            }
            $stmtInsertDatCtapac->close();

            $updateExistenciasQuery = "UPDATE existencias_almacenh SET existe_qty = ?, existe_fecha = ?, existe_salidas = ? WHERE existe_id = ?";
            $stmtUpdateExistencias = $conexion->prepare($updateExistenciasQuery);
            if (!$stmtUpdateExistencias) {
                throw new Exception("Error preparando consulta existencias_almacenh: " . $conexion->error);
            }
            $stmtUpdateExistencias->bind_param('isii', $nuevaExistenciaQty, $fechaActual, $nuevaExistenciaSalidas, $existeId);
            if (!$stmtUpdateExistencias->execute()) {
                throw new Exception("Error actualizando existencias_almacenh: " . $stmtUpdateExistencias->error);
            }
            $stmtUpdateExistencias->close();
        }

        $conexion->commit();
        unset($_SESSION['medicamento_seleccionado']);
        echo json_encode(['success' => true, 'message' => 'Registro agregado correctamente']);
    } catch (Exception $e) {
        $conexion->rollback();
        file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - Procesar medicamentos: ' . $e->getMessage() . "\n", FILE_APPEND);
        echo json_encode(['success' => false, 'message' => 'Error al agregar el registro: ' . $e->getMessage()]);
    }
    exit;
}

// Obtener los pacientes
$sqlPac = "
    SELECT 
        di.id_atencion, 
        CONCAT(p.nom_pac, ' ', p.papell, ' ', p.sapell) AS nombre_paciente
    FROM 
        dat_ingreso di
    JOIN 
        paciente p ON di.Id_exp = p.Id_exp
    WHERE 
        di.activo = 'SI'
";
$resultPac = $conexion->query($sqlPac);

$pacientesOptions = '';
if ($resultPac && $resultPac->num_rows > 0) {
    while ($paciente = $resultPac->fetch_assoc()) {
        $selected = (isset($_SESSION['paciente_seleccionado']) && $_SESSION['paciente_seleccionado'] == $paciente['id_atencion']) ? 'selected' : '';
        $pacientesOptions .= "<option value='{$paciente['id_atencion']}' $selected>{$paciente['nombre_paciente']}</option>";
    }
}

// Guardar el id_atencion en la sesión cuando se seleccione el paciente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['paciente']) && !isset($_POST['ajax'])) {
    $_SESSION['paciente_seleccionado'] = $_POST['paciente'];
}

// Obtener los medicamentos
$queryMedicamentos = "SELECT DISTINCT 
    ea.item_id, 
    CONCAT(ia.item_name, ', ', ia.item_grams) AS item_name
    FROM existencias_almacenh ea 
    JOIN item_almacen ia ON ea.item_id = ia.item_id 
    ORDER BY ia.item_name
";
$resultMedicamentos = $conexion->query($queryMedicamentos);

$medicamentosOptions = '';
if ($resultMedicamentos && $resultMedicamentos->num_rows > 0) {
    while ($medicamento = $resultMedicamentos->fetch_assoc()) {
        $selected = (isset($_POST['medicamento']) && $_POST['medicamento'] == $medicamento['item_id']) ? 'selected' : '';
        $medicamentosOptions .= "<option value='{$medicamento['item_id']}' $selected>{$medicamento['item_name']}</option>";
    }
}

// Obtener los lotes y la suma total de existencias para el medicamento seleccionado
$lotesOptions = '';
$totalExistencias = 0;
if (isset($_POST['medicamento']) && !isset($_POST['ajax'])) {
    $itemId = intval($_POST['medicamento']);
    $sqlTotalExistencias = "
        SELECT SUM(ea.existe_qty) AS total_existencias
        FROM existencias_almacenh ea
        WHERE ea.item_id = $itemId
    ";
    $resultTotalExistencias = $conexion->query($sqlTotalExistencias);
    if ($resultTotalExistencias && $resultTotalExistencias->num_rows > 0) {
        $row = $resultTotalExistencias->fetch_assoc();
        $totalExistencias = $row['total_existencias'];
    }

    $sqlLotes = "
        SELECT 
            ea.existe_lote, ea.existe_caducidad, ea.existe_qty, ea.existe_id
        FROM 
            existencias_almacenh ea
        WHERE 
            ea.item_id = ? AND ea.existe_qty > 0
        ORDER BY 
            ea.existe_caducidad ASC
    ";
    $stmt = $conexion->prepare($sqlLotes);
    $stmt->bind_param('i', $itemId);
    $stmt->execute();
    $resultLotes = $stmt->get_result();

    $lotesOptions = '';
    if ($resultLotes && $resultLotes->num_rows > 0) {
        while ($lote = $resultLotes->fetch_assoc()) {
            $lotesOptions .= "<option value='{$lote['existe_id']}|{$lote['existe_lote']}|$itemId' data-caducidad='{$lote['existe_caducidad']}' data-cantidad='{$lote['existe_qty']}'>
                {$lote['existe_lote']} / {$lote['existe_caducidad']} / {$lote['existe_qty']}
            </option>";
        }
    } else {
        $lotesOptions .= "<option value='' disabled>No hay lotes disponibles</option>";
    }
    $stmt->close();
}

// Fetch items surtidos from dat_ctapac
$itemsSurtidos = '';
if (isset($_SESSION['paciente_seleccionado']) && !empty($_SESSION['paciente_seleccionado'])) {
    $idAtencion = intval($_SESSION['paciente_seleccionado']);
    $sqlSurtidos = "
        SELECT 
            dc.id_ctapac,
            CONCAT(p.nom_pac, ' ', p.papell, ' ', p.sapell) AS nombre_paciente,
            CONCAT(ia.item_name, ', ', ia.item_grams) AS item_name,
            dc.existe_lote,
            dc.existe_caducidad,
            dc.cta_cant,
            dc.cta_tot
        FROM 
            dat_ctapac dc
        INNER JOIN 
            dat_ingreso di ON dc.id_atencion = di.id_atencion AND di.id_atencion = ?
        INNER JOIN 
            paciente p ON p.Id_exp = di.Id_exp
        INNER JOIN 
            item_almacen ia ON dc.insumo = ia.item_id
        WHERE 
            dc.cta_activo = 'SI'
            AND dc.existe_lote IS NOT NULL AND dc.existe_lote != ''
            AND dc.existe_caducidad IS NOT NULL AND dc.existe_caducidad != ''
        ORDER BY 
            dc.cta_fec DESC
    ";
    $stmtSurtidos = $conexion->prepare($sqlSurtidos);
    $stmtSurtidos->bind_param('i', $idAtencion);
    $stmtSurtidos->execute();
    $resultSurtidos = $stmtSurtidos->get_result();

    if ($resultSurtidos && $resultSurtidos->num_rows > 0) {
        $itemsSurtidos .= "<table class='table table-bordered table-striped'>";
        $itemsSurtidos .= "<thead class='thead-dark'>
            <tr>
                <th>Paciente</th>
                <th>Medicamento</th>
                <th>Lote</th>
                <th>Caducidad</th>
                <th>Cantidad</th>
                <th>Total</th>
            </tr>
        </thead><tbody>";
        while ($row = $resultSurtidos->fetch_assoc()) {
            $itemsSurtidos .= "<tr>";
            $itemsSurtidos .= "<td>" . htmlspecialchars($row['nombre_paciente']) . "</td>";
            $itemsSurtidos .= "<td>" . htmlspecialchars($row['item_name']) . "</td>";
            $itemsSurtidos .= "<td>" . htmlspecialchars($row['existe_lote']) . "</td>";
            $itemsSurtidos .= "<td>" . htmlspecialchars($row['existe_caducidad']) . "</td>";
            $itemsSurtidos .= "<td>" . htmlspecialchars($row['cta_cant']) . "</td>";
            $itemsSurtidos .= "<td>" . htmlspecialchars($row['cta_tot']) . "</td>";
            $itemsSurtidos .= "</tr>";
        }
        $itemsSurtidos .= "</tbody></table>";
    } else {
        $itemsSurtidos = "<p class='text-center'>No hay ítems surtidos para el paciente seleccionado.</p>";
    }
    $stmtSurtidos->close();
} else {
    $itemsSurtidos = "<p class='text-center'>Seleccione un paciente para ver los ítems surtidos.</p>";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoja de Programación Quirúrgica</title>
    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <link rel="stylesheet" href="notificaciones-mejoradas.css" />
    <link href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
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
        .thead {
            background-color: #2b2d7f;
            color: white;
            font-size: 20px;
            padding: 10px;
            text-align: center;
        }

        .card-container {
            display: flex;
            gap: 25px;
            margin: 20px 0;
        }

        .card {
            flex: 1;
            padding: 20px;
            border: 2px solid #e3e6f0;
            border-radius: 15px;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%);
            box-shadow: 0 4px 15px rgba(43, 45, 127, 0.1);
        }

        .checkbox-group {
            margin-bottom: 15px;
        }

        .nav-tabs .nav-link {
            background: #2b2d7f;
            color: #fff;
            border: none;
        }

        .nav-tabs .nav-link.active {
            background: #4a4ed1;
            color: #fff;
        }

        .tab-content {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 0 0 15px 15px;
        }

        .table-signos-vitales th,
        .table-signos-vitales td {
            vertical-align: middle;
            text-align: center;
        }

        /* Estilos mejorados para signos vitales */
        .signos-container {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%);
            border-radius: 15px;
            padding: 25px;
            border: 2px solid #e3e6f0;
            box-shadow: 0 4px 20px rgba(43, 45, 127, 0.1);
            margin: 20px 0;
        }

        .signos-header {
            background: linear-gradient(135deg, #2b2d7f 0%, #4a4ed1 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            text-align: center;
            box-shadow: 0 6px 15px rgba(43, 45, 127, 0.3);
        }

        .signos-header i {
            font-size: 1.5em;
            margin-right: 10px;
        }

        .signos-subtitle {
            font-size: 0.9em;
            opacity: 0.9;
            margin-top: 5px;
        }

        /* Panel de acciones */
        .signos-actions-panel {
            margin-bottom: 25px;
        }

        .action-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e3e6f0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .action-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            color: white;
            font-size: 1.2em;
        }

        .action-content h6 {
            color: #2b2d7f;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .action-content p {
            color: #6c757d;
            font-size: 0.9em;
            margin-bottom: 15px;
        }

        /* Resumen de últimos valores */
        .signos-summary {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid #e3e6f0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .summary-title {
            color: #2b2d7f;
            font-weight: bold;
            margin-bottom: 15px;
            font-size: 1.1em;
        }

        .summary-title i {
            margin-right: 8px;
        }

        .valor-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            border: 1px solid #e3e6f0;
            transition: all 0.3s ease;
            height: 100%;
        }

        .valor-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .valor-icon {
            font-size: 1.5em;
            margin-bottom: 8px;
            color: #2b2d7f;
        }

        .valor-label {
            display: block;
            font-size: 0.8em;
            color: #6c757d;
            font-weight: 500;
        }

        .valor-number {
            display: block;
            font-size: 1.3em;
            font-weight: bold;
            color: #2b2d7f;
            margin: 5px 0;
        }

        .valor-unit {
            font-size: 0.7em;
            color: #6c757d;
        }

        /* Colores específicos para cada tipo de valor */
        .valor-card.presion .valor-icon {
            color: #dc3545;
        }

        .valor-card.frecuencia .valor-icon {
            color: #e74c3c;
        }

        .valor-card.respiracion .valor-icon {
            color: #17a2b8;
        }

        .valor-card.saturacion .valor-icon {
            color: #007bff;
        }

        .valor-card.temperatura .valor-icon {
            color: #ffc107;
        }

        .valor-card.tiempo .valor-icon {
            color: #6c757d;
        }

        /* Tabla mejorada */
        .signos-table-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e3e6f0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e3e6f0;
        }

        .table-header h5 {
            color: #2b2d7f;
            margin: 0;
            font-weight: bold;
        }

        .table-header i {
            margin-right: 8px;
        }

        .table-controls .badge {
            font-size: 0.9em;
        }

        .table-signos-vitales {
            border: 2px solid #2b2d7f;
            border-radius: 8px;
            overflow: hidden;
        }

        .table-signos-vitales thead th {
            background: linear-gradient(135deg, #2b2d7f 0%, #4a4ed1 100%) !important;
            color: white !important;
            font-weight: bold;
            border: none;
            padding: 15px 10px;
            text-align: center;
            font-size: 0.9em;
        }

        .table-signos-vitales thead th i {
            margin-right: 5px;
        }

        .table-signos-vitales tbody tr {
            border: none;
            transition: all 0.3s ease;
        }

        .table-signos-vitales tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .table-signos-vitales tbody tr:hover {
            background-color: #e3f2fd;
            transform: scale(1.01);
        }

        .table-signos-vitales tbody td {
            padding: 12px 10px;
            text-align: center;
            vertical-align: middle;
            border: 1px solid #dee2e6;
        }

        /* Modales mejorados */
        .signos-modal .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .signos-modal-header {
            background: linear-gradient(135deg, #2b2d7f 0%, #4a4ed1 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
        }

        .signos-modal-header h5 {
            margin: 0;
            font-weight: bold;
        }

        .signos-modal-header i {
            margin-right: 10px;
        }

        .signos-modal-body {
            padding: 30px;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%);
        }

        /* Formularios mejorados */
        .signos-form-group {
            margin-bottom: 25px;
        }

        .signos-label {
            display: block;
            font-weight: 600;
            color: #2b2d7f;
            margin-bottom: 8px;
            font-size: 1em;
        }

        .signos-label i {
            margin-right: 8px;
        }

        .form-control-signos {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e3e6f0;
            border-radius: 10px;
            font-size: 1em;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control-signos:focus {
            border-color: #4a4ed1;
            box-shadow: 0 0 0 0.2rem rgba(74, 78, 209, 0.25);
            outline: none;
            transform: scale(1.02);
        }

        /* Inputs de presión arterial */
        .presion-inputs {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .presion-group {
            flex: 1;
            text-align: center;
        }

        .presion-input {
            text-align: center;
            font-weight: bold;
            font-size: 1.1em;
        }

        .presion-group label {
            display: block;
            font-size: 0.8em;
            color: #6c757d;
            margin-top: 5px;
        }

        .presion-separator {
            font-size: 1.5em;
            font-weight: bold;
            color: #2b2d7f;
        }

        /* Botones mejorados */
        .signos-form-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        .btn-signos-primary {
            background: linear-gradient(135deg, #2b2d7f 0%, #4a4ed1 100%);
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(43, 45, 127, 0.3);
        }

        .btn-signos-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(43, 45, 127, 0.4);
            background: linear-gradient(135deg, #1a1d5f 0%, #2b2d7f 100%);
            color: white;
        }

        .btn-signos-secondary {
            background: #6c757d;
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-signos-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
            color: white;
        }

        /* Texto de ayuda */
        .form-text {
            font-size: 0.8em;
            margin-top: 5px;
            color: #6c757d;
        }

        /* Animaciones */
        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .valor-card.actualizado {
            animation: pulse 0.6s ease-in-out;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .signos-container {
                padding: 15px;
            }

            .presion-inputs {
                flex-direction: column;
                gap: 10px;
            }

            .presion-separator {
                display: none;
            }

            .signos-form-actions {
                flex-direction: column;
            }
        }

        .form-check-label {
            font-size: 16px;
            font-weight: 500;
            color: #2b2d7f;
        }

        /* Estilos para la tabla de signos vitales */
        .table-signos-vitales {
            border: 2px solid #2b2d7f;
        }

        .table-signos-vitales thead th {
            background-color: #2b2d7f !important;
            color: white !important;
            font-weight: bold;
            border: 1px solid #1a1d5f;
        }

        .table-signos-vitales tbody tr {
            border: 1px solid #dee2e6;
        }

        .table-signos-vitales tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .table-signos-vitales tbody tr:hover {
            background-color: #e3f2fd;
        }

        .signos-input {
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 5px 8px;
            font-size: 14px;
        }

        .signos-input:focus {
            border-color: #4a4ed1;
            box-shadow: 0 0 0 0.2rem rgba(74, 78, 209, 0.25);
        }

        .signos-input.bg-light {
            background-color: #e9ecef !important;
            color: #6c757d;
        }

        .fila-signos-vitales td {
            padding: 8px;
            vertical-align: middle;
        }

        #agregar-signos-adicionales {
            background: linear-gradient(45deg, #2b2d7f, #2b2d7f);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        #agregar-signos-adicionales:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        /* Estilos para formulario de nota de enfermería */
        .nota-enfermeria-section {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%);
            border-radius: 15px;
            padding: 20px;
            border: 2px solid #e3e6f0;
            box-shadow: 0 4px 15px rgba(43, 45, 127, 0.1);
        }

        .form-group label {
            font-weight: 600;
            color: #2b2d7f;
            margin-bottom: 5px;
        }

        .form-control:focus {
            border-color: #4a4ed1;
            box-shadow: 0 0 0 0.2rem rgba(74, 78, 209, 0.25);
        }

        .btn-outline-primary {
            border-color: #2b2d7f;
            color: #2b2d7f;
        }

        .btn-outline-primary:hover {
            background-color: #2b2d7f;
            border-color: #2b2d7f;
        }

        .btn-primary {
            background-color: #2b2d7f;
            border-color: #2b2d7f;
        }

        .btn-primary:hover {
            background-color: #1e2070;
            border-color: #1e2070;
        }

        /* Animación para elementos que se muestran/ocultan */
        #select_medico_responsable_wrap {
            transition: all 0.3s ease;
        }

        /* Estilo para campos requeridos */
        .form-control:invalid {
            border-color: #e74c3c;
        }

        .form-control:valid {
            border-color: #27ae60;
        }

        /* Estilos para los botones de grabación de audio */
        .btn-group .btn {
            margin-right: 5px;
        }

        .grabar-nota {
            background-color: #e74c3c;
            border-color: #e74c3c;
        }

        .detener-nota {
            background-color: #3498db;
            border-color: #3498db;
        }

        .reproducir-nota {
            background-color: #27ae60;
            border-color: #27ae60;
        }

        /* Estilos para alerta de hora original */
        #horaOriginalAlert {
            border-radius: 10px;
            border: 1px solid #b3d7ff;
            background: linear-gradient(135deg, #e3f2fd 0%, #f0f8ff 100%);
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.1);
            animation: slideInDown 0.3s ease-out;
        }

        #horaOriginalAlert .btn-outline-primary {
            border-radius: 20px;
            font-size: 0.85rem;
            padding: 4px 12px;
            transition: all 0.3s ease;
        }

        #horaOriginalAlert .btn-outline-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Estilos mejorados para botones de acción */
        .action-buttons-container {
            display: flex;
            gap: 8px;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn-action {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.8rem;
            padding: 6px 12px;
            min-width: 80px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            text-decoration: none;
        }

        .btn-action:active {
            transform: translateY(0);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Botón de editar mejorado */
        .btn-edit-modern {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            border-color: #4f46e5;
        }

        .btn-edit-modern:hover {
            background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
            color: white;
            border-color: #4338ca;
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
        }

        .btn-edit-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-edit-modern:hover::before {
            left: 100%;
        }

        /* Botón de eliminar mejorado */
        .btn-delete-modern {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border-color: #ef4444;
        }

        .btn-delete-modern:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            border-color: #dc2626;
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
        }

        .btn-delete-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-delete-modern:hover::before {
            left: 100%;
        }

        /* Iconos de botones */
        .btn-action i {
            font-size: 0.85rem;
        }

        /* Responsividad para botones */
        @media (max-width: 768px) {
            .action-buttons-container {
                flex-direction: column;
                gap: 4px;
            }

            .btn-action {
                width: 100%;
                min-width: auto;
                font-size: 0.75rem;
                padding: 4px 8px;
            }
        }

        /* Estados de carga para botones */
        .btn-action.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-action.loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            margin: auto;
            border: 2px solid transparent;
            border-top-color: currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Estilos para confirmaciones mejoradas */
        .alertify .ajs-dialog {
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .alertify .ajs-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px 12px 0 0;
            padding: 20px;
            font-weight: 600;
        }

        .alertify .ajs-body {
            padding: 25px;
            font-size: 16px;
            line-height: 1.5;
            color: #4a5568;
        }

        .alertify .ajs-footer {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 0 0 12px 12px;
        }

        .alertify .ajs-button {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            margin: 0 5px;
            transition: all 0.3s ease;
            border: none;
        }

        .alertify .ajs-button.ajs-ok {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .alertify .ajs-button.ajs-ok:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-1px);
        }

        .alertify .ajs-button.ajs-cancel {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: white;
        }

        .alertify .ajs-button.ajs-cancel:hover {
            background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
            transform: translateY(-1px);
        }

        /* Efectos para carga de tabla */
        .dataTables_processing {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            border-radius: 8px !important;
            border: none !important;
            font-weight: 600 !important;
        }

        /* Tooltips mejorados */
        [title]:hover::after {
            content: attr(title);
            position: absolute;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 1000;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            margin-bottom: 5px;
        }

        /* Estilos adicionales para botones de voz */
        .btn-group .btn {
            position: relative;
            overflow: hidden;
        }

        .btn-group .btn:active {
            transform: scale(0.95);
        }

        .recording-indicator {
            font-size: 12px;
            font-weight: bold;
            color: #dc3545;
            vertical-align: middle;
            margin-left: 10px;
        }

        /* Animación de pulso para el indicador de grabación */
        @keyframes pulse-recording {
            0% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.6;
                transform: scale(1.1);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .pulse-animation {
            animation: pulse-recording 1.5s infinite;
        }

        /* Mejorar el aspecto del textarea durante dictado */
        .nota-enfermeria.dictating {
            border-color: #dc3545;
            box-shadow: 0 0 10px rgba(220, 53, 69, 0.3);
            background-color: #fff5f5;
        }

        /* Tooltips mejorados para botones de voz */
        .btn-group .btn[title] {
            position: relative;
        }

        /* Estados hover para botones de voz */
        .grabar-nota:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }

        .detener-nota:hover {
            background-color: #2980b9;
            transform: scale(1.05);
        }

        .reproducir-nota:hover {
            background-color: #219a52;
            transform: scale(1.05);
        }

        /* Estilos Insumos */
        .insumos-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .insumos-container .form-group label {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .insumos-container .form-group select,
        .insumos-container .form-group input {
            font-size: 14px;
            padding: 5px;
        }

        .insumos-container .btn-custom {
            background-color: #0a4d44;
            color: white;
            font-size: 16px;
        }

        .insumos-container .btn-custom:hover {
            background-color: #085c52;
        }

        .insumos-container .btn-danger {
            font-size: 12px;
            padding: 3px 8px;
        }

        .insumos-container .table {
            font-size: 18px;
            width: 90%;
            margin: 0 auto;
        }

        .insumos-container h3 {
            text-align: center;
            margin-top: 20px;
        }

        .insumos-container .thead-custom {
            background-color: #0c675e;
            color: white;
            text-align: center;
            padding: 5px;
            border-radius: 5px;
            margin: 5px auto;
            width: fit-content;
        }
    </style>
</head>

<body>
    <?php
    // Display messages
    $messages = [
        'tratamiento_exito' => ['type' => 'success', 'format' => 'Formulario de <strong>%s</strong> enviado correctamente.'],
        'exito_multiples' => ['type' => 'success', 'format' => '<i class="fas fa-check-circle"></i> %s<br><a href="ver_grafica.php" class="btn btn-primary btn-sm mt-2"><i class="fas fa-chart-line"></i> Ver Gráficas de Signos Vitales</a>'],
        'error' => ['type' => 'danger', 'format' => '<i class="fas fa-exclamation-triangle"></i> Error: %s']
    ];

    foreach ($messages as $key => $config) {
        if (isset($_GET[$key]) && !empty($_GET[$key])) {
            printf(
                '<div class="alert alert-%s mt-3" role="alert" style="font-size:18px; text-align:center;">%s</div>',
                $config['type'],
                sprintf($config['format'], htmlspecialchars($_GET[$key], ENT_QUOTES, 'UTF-8'))
            );
        }
    }

    if (isset($_SESSION['message']) && isset($_SESSION['message_type'])) {
        printf(
            '<div class="alert alert-%s alert-dismissible fade show" role="alert">%s<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>',
            htmlspecialchars($_SESSION['message_type'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8')
        );
        unset($_SESSION['message'], $_SESSION['message_type']);
    }
    ?>

    <div class="container">
        <div class="thead"><strong>DATOS DEL PACIENTE</strong></div>
        <?php
        // Validate patient session
        if (!isset($_SESSION['pac']) || !is_numeric($_SESSION['pac'])) {
            header('Location: ../../../../index.php');
            exit;
        }

        $id_atencion = $_SESSION['pac'];

        // Fetch patient data
        $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.folio, p.tel,
                           p.fecnac, p.tip_san, di.fecha, di.area, di.alta_med, di.activo, p.sexo, di.alergias, p.ocup
                    FROM paciente p
                    INNER JOIN dat_ingreso di ON p.Id_exp = di.Id_exp
                    WHERE di.id_atencion = ?";

        $stmt = $conexion->prepare($sql_pac);
        if (!$stmt) {
            die("Prepare failed: " . $conexion->error);
        }

        $stmt->bind_param("i", $id_atencion);
        $stmt->execute();
        $result_pac = $stmt->get_result();
        $pac_data = $result_pac->fetch_assoc();
        $stmt->close();

        if (!$pac_data) {
            header('Location: ../../../../index.php');
            exit;
        }

        // Sanitize patient data
        $pac_papell = htmlspecialchars($pac_data['papell'], ENT_QUOTES, 'UTF-8');
        $pac_sapell = htmlspecialchars($pac_data['sapell'], ENT_QUOTES, 'UTF-8');
        $pac_nom_pac = htmlspecialchars($pac_data['nom_pac'], ENT_QUOTES, 'UTF-8');
        $pac_dir = htmlspecialchars($pac_data['dir'], ENT_QUOTES, 'UTF-8');
        $pac_id_edo = htmlspecialchars($pac_data['id_edo'], ENT_QUOTES, 'UTF-8');
        $pac_id_mun = htmlspecialchars($pac_data['id_mun'], ENT_QUOTES, 'UTF-8');
        $pac_tel = htmlspecialchars($pac_data['tel'], ENT_QUOTES, 'UTF-8');
        $pac_fecnac = htmlspecialchars($pac_data['fecnac'], ENT_QUOTES, 'UTF-8');
        $pac_fecing = htmlspecialchars($pac_data['fecha'], ENT_QUOTES, 'UTF-8');
        $pac_tip_sang = htmlspecialchars($pac_data['tip_san'], ENT_QUOTES, 'UTF-8');
        $pac_sexo = htmlspecialchars($pac_data['sexo'], ENT_QUOTES, 'UTF-8');
        $area = htmlspecialchars($pac_data['area'], ENT_QUOTES, 'UTF-8');
        $alta_med = htmlspecialchars($pac_data['alta_med'], ENT_QUOTES, 'UTF-8');
        $id_exp = htmlspecialchars($pac_data['Id_exp'], ENT_QUOTES, 'UTF-8');
        $folio = htmlspecialchars($pac_data['folio'], ENT_QUOTES, 'UTF-8');
        $alergias = htmlspecialchars($pac_data['alergias'], ENT_QUOTES, 'UTF-8');
        $ocup = htmlspecialchars($pac_data['ocup'], ENT_QUOTES, 'UTF-8');
        $activo = htmlspecialchars($pac_data['activo'], ENT_QUOTES, 'UTF-8');

        // Calculate hospital stay
        $estancia = 0;
        if ($activo === 'SI') {
            $sql_now = "SELECT DATE_ADD(NOW(), INTERVAL 12 HOUR) as dat_now FROM dat_ingreso WHERE id_atencion = ?";
            $stmt = $conexion->prepare($sql_now);
            $stmt->bind_param("i", $id_atencion);
            $stmt->execute();
            $dat_now = $stmt->get_result()->fetch_assoc()['dat_now'] ?? date('Y-m-d H:i:s');
            $stmt->close();

            $sql_est = "SELECT DATEDIFF(?, fecha) as estancia FROM dat_ingreso WHERE id_atencion = ?";
            $stmt = $conexion->prepare($sql_est);
            $stmt->bind_param("si", $dat_now, $id_atencion);
            $stmt->execute();
            $estancia = $stmt->get_result()->fetch_assoc()['estancia'] ?? 0;
            $stmt->close();
        } else {
            $sql_est = "SELECT DATEDIFF(fec_egreso, fecha) as estancia FROM dat_ingreso WHERE id_atencion = ?";
            $stmt = $conexion->prepare($sql_est);
            $stmt->bind_param("i", $id_atencion);
            $stmt->execute();
            $estancia = ($stmt->get_result()->fetch_assoc()['estancia'] ?? 0) ?: 1;
            $stmt->close();
        }

        // Get diagnosis or motive
        $d = '';
        $sql_motd = "SELECT diagprob_i FROM dat_not_ingreso WHERE id_atencion = ? ORDER BY id_not_ingreso DESC LIMIT 1";
        $stmt = $conexion->prepare($sql_motd);
        $stmt->bind_param("i", $id_atencion);
        $stmt->execute();
        if ($row_motd = $stmt->get_result()->fetch_assoc()) {
            $d = htmlspecialchars($row_motd['diagprob_i'], ENT_QUOTES, 'UTF-8');
        }
        $stmt->close();

        if (!$d) {
            $sql_motd = "SELECT diagprob_i FROM dat_nevol WHERE id_atencion = ? ORDER BY id_ne DESC LIMIT 1";
            $stmt = $conexion->prepare($sql_motd);
            $stmt->bind_param("i", $id_atencion);
            $stmt->execute();
            if ($row_motd = $stmt->get_result()->fetch_assoc()) {
                $d = htmlspecialchars($row_motd['diagprob_i'], ENT_QUOTES, 'UTF-8');
            }
            $stmt->close();
        }

        $m = '';
        $sql_mot = "SELECT motivo_atn FROM dat_ingreso WHERE id_atencion = ? LIMIT 1";
        $stmt = $conexion->prepare($sql_mot);
        $stmt->bind_param("i", $id_atencion);
        $stmt->execute();
        if ($row_mot = $stmt->get_result()->fetch_assoc()) {
            $m = htmlspecialchars($row_mot['motivo_atn'], ENT_QUOTES, 'UTF-8');
        }
        $stmt->close();

        $edo_salud = '';
        $sql_edo = "SELECT edo_salud FROM dat_ingreso WHERE id_atencion = ? LIMIT 1";
        $stmt = $conexion->prepare($sql_edo);
        $stmt->bind_param("i", $id_atencion);
        $stmt->execute();
        if ($row_edo = $stmt->get_result()->fetch_assoc()) {
            $edo_salud = htmlspecialchars($row_edo['edo_salud'], ENT_QUOTES, 'UTF-8');
        }
        $stmt->close();

        $num_cama = '';
        $sql_hab = "SELECT num_cama FROM cat_camas WHERE id_atencion = ?";
        $stmt = $conexion->prepare($sql_hab);
        $stmt->bind_param("i", $id_atencion);
        $stmt->execute();
        $num_cama = htmlspecialchars($stmt->get_result()->fetch_assoc()['num_cama'] ?? '', ENT_QUOTES, 'UTF-8');
        $stmt->close();

        $peso = 0;
        $talla = 0;
        $sql_hclinica = "SELECT peso, talla FROM dat_hclinica WHERE Id_exp = ? ORDER BY id_hc DESC LIMIT 1";
        $stmt = $conexion->prepare($sql_hclinica);
        $stmt->bind_param("s", $id_exp);
        $stmt->execute();
        if ($row_hclinica = $stmt->get_result()->fetch_assoc()) {
            $peso = htmlspecialchars($row_hclinica['peso'] ?? 0, ENT_QUOTES, 'UTF-8');
            $talla = htmlspecialchars($row_hclinica['talla'] ?? 0, ENT_QUOTES, 'UTF-8');
        }
        $stmt->close();

        // Calculate age
        $fecha_nac = new DateTime($pac_fecnac);
        $fecha_actual = new DateTime();
        $edad = $fecha_nac->diff($fecha_actual);
        $edad_text = $edad->y > 0 ? $edad->y . " Años" : ($edad->m > 0 ? $edad->m . " Meses" : $edad->d . " Días");
        ?>
        <div class="row fs-6">
            <div class="col-md-6">
                Expediente: <strong><?php echo $folio; ?></strong><br>
                Paciente: <strong><?php echo $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac; ?></strong>
            </div>
            <div class="col-md-3">
                Área: <strong><?php echo $area; ?></strong>
            </div>
            <div class="col-md-3">
                Fecha de Ingreso: <strong><?php echo date_format(date_create($pac_fecing), "d/m/Y"); ?></strong>
            </div>
        </div>
        <div class="row fs-6">
            <div class="col-md-3">
                Fecha de nacimiento: <strong><?php echo date_format(date_create($pac_fecnac), "d/m/Y"); ?></strong>
            </div>
            <div class="col-md-3">
                Tipo de sangre: <strong><?php echo $pac_tip_sang; ?></strong>
            </div>
            <div class="col-md-3">
                Habitación: <strong><?php echo $num_cama; ?></strong>
            </div>
            <div class="col-md-3">
                Tiempo estancia: <strong><?php echo $estancia; ?> Días</strong>
            </div>
        </div>
        <div class="row fs-6">
            <div class="col-md-3">
                Edad: <strong><?php echo $edad_text; ?></strong>
            </div>
            <div class="col-md-3">
                Peso: <strong><?php echo $peso; ?></strong>
            </div>
            <div class="col-md-3">
                Talla: <strong><?php echo $talla; ?></strong>
            </div>
            <div class="col-md-3">
                Género: <strong><?php echo $pac_sexo; ?></strong>
            </div>
        </div>
        <div class="row fs-6">
            <div class="col-md-3">
                Alergias: <strong><?php echo $alergias; ?></strong>
            </div>
            <div class="col-md-6">
                Estado de Salud: <strong><?php echo $edo_salud; ?></strong>
            </div>
            <div class="col-md-3">
                <?php echo $d ? "Diagnóstico: <strong>$d</strong>" : "Motivo de atención: <strong>$m</strong>"; ?>
            </div>
        </div>
        <hr>
        <div class="thead"><strong>HOJA DE PROGRAMACIÓN QUIRÚRGICA</strong></div>
        <div class="card mt-3">
            <ul class="nav nav-tabs nav-fill" id="menuRegistroTabs">
                <li class="nav-item">
                    <a class="nav-link active" id="cirugia-tab" data-bs-toggle="tab" href="#cirugia" role="tab">Cirugía Segura</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="signos-tab" data-bs-toggle="tab" href="#signos" role="tab">Signos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="nota-tab" data-bs-toggle="tab" href="#nota" role="tab">Nota Enfermería</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="ingresos-tab" data-bs-toggle="tab" href="#ingresos" role="tab">Ingresos / Egresos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="insumos-tab" data-bs-toggle="tab" href="#insumos" role="tab">Insumos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="equipos-tab" data-bs-toggle="tab" href="#equipos" role="tab">Equipos</a>
                </li>
            </ul>
            <div class="tab-content" id="menuRegistroTabsContent">
                <div class="tab-pane fade show active" id="cirugia" role="tabpanel">
                    <div class="thead"><strong>HOJA DE CIRUGÍA SEGURA</strong></div>
                    <hr>
                    <form action="../../enfermera/registro_quirurgico/insertar_cir_seg.php" method="POST">
                        <input type="hidden" name="id_exp" value="<?php echo htmlspecialchars($id_exp); ?>">
                        <input type="hidden" name="id_usua" value="<?php echo htmlspecialchars($id_usuario); ?>">
                        <input type="hidden" name="id_atencion" value="<?php echo htmlspecialchars($id_atencion); ?>">
                        <div class="card-container" style="display: flex; gap: 25px; margin: 20px 0;">
                            <!-- Sección 1 -->
                            <div class="card" style="flex: 1; padding: 20px; border: 2px solid #e3e6f0; border-radius: 15px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%); box-shadow: 0 4px 15px rgba(43, 45, 127, 0.1); transition: all 0.3s ease; position: relative; overflow: hidden;">
                                <h4 style="margin-bottom: 20px; color: #2b2d7f; font-weight: 600; font-size: 18px; padding-bottom: 10px; border-bottom: 2px solid #e3e6f0;">Con el enfermero y el anestesista</h4>
                                <div class="checkbox-group">
                                    <strong>¿Ha confirmado el paciente su identidad, el sitio quirúrgico, el procedimiento y su consentimiento?</strong><br>
                                    <input type="checkbox" name="confirmacion_identidad" value="Sí"> Sí
                                </div>
                                <hr>
                                <div class="checkbox-group">
                                    <strong>¿Se ha marcado el sitio quirúrgico?</strong><br>
                                    <input type="checkbox" name="sitio_marcado[]" value="Sí"> Sí<br>
                                    <input type="checkbox" name="sitio_marcado[]" value="No procede"> No procede
                                </div>
                                <hr>
                                <div class="checkbox-group">
                                    <strong>¿Se ha completado la comprobación de los aparatos de anestesia y la medicación anestésica?</strong><br>
                                    <input type="checkbox" name="verificacion_anestesia" value="Sí"> Sí
                                </div>
                                <hr>
                                <div class="checkbox-group">
                                    <strong>¿Se ha colocado el pulsioximetro al paciente y funciona?</strong><br>
                                    <input type="checkbox" name="pulsioximetro" value="Sí"> Sí
                                </div>
                                <hr>
                                <div class="checkbox-group">
                                    <strong>¿Tiene el paciente alergias conocidas?</strong><br>
                                    <input type="checkbox" name="alergias[]" value="No"> No<br>
                                    <input type="checkbox" name="alergias[]" value="Sí"> Sí
                                </div>
                                <div class="checkbox-group">
                                    <strong>¿Tiene el paciente vía aérea difícil / riesgo de aspiración?</strong><br>
                                    <input type="checkbox" name="via_aerea_dificil[]" value="No"> No<br>
                                    <input type="checkbox" name="via_aerea_dificil[]" value="Sí, y hay materiales y equipos / ayuda disponible"> Sí, y hay materiales y equipos / ayuda disponible
                                </div>
                                <div class="checkbox-group">
                                    <strong>¿Riesgo de hemorragia &gt; 500 ml (7 ml/kg en niños)?</strong><br>
                                    <input type="checkbox" name="riesgo_hemorragia[]" value="No"> No<br>
                                    <input type="checkbox" name="riesgo_hemorragia[]" value="Sí, y se ha previsto la disponibilidad de líquidos y dos vías IV o centrales"> Sí, y se ha previsto la disponibilidad de líquidos y dos vías IV o centrales
                                </div>
                            </div>
                            <!-- Sección 2 -->
                            <div class="card" style="flex: 1; padding: 20px; border: 2px solid #e3e6f0; border-radius: 15px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%); box-shadow: 0 4px 15px rgba(43, 45, 127, 0.1); transition: all 0.3s ease; position: relative; overflow: hidden;">
                                <h4 style="margin-bottom: 20px; color: #2b2d7f; font-weight: 600; font-size: 18px; padding-bottom: 10px; border-bottom: 2px solid #e3e6f0;">Con el enfermero, el anestesista y el cirujano</h4>
                                <div class="checkbox-group">
                                    <label>
                                        <input type="checkbox" name="miembros_presentados" value="1">
                                        <strong>Confirmar que todos los miembros del equipo se hayan presentado por su nombre</strong>
                                    </label>
                                </div>
                                <hr>
                                <div class="checkbox-group">
                                    <label>
                                        <input type="checkbox" name="confirmacion_identidad_equipo" value="1">
                                        <strong>Confirmar la identidad del paciente, el sitio quirúrgico y el procedimiento</strong>
                                    </label>
                                </div>
                                <hr>
                                <div class="checkbox-group">
                                    <strong>¿Se ha administrado profilaxis antibiótica en los últimos 60 minutos?</strong><br>
                                    <input type="checkbox" name="profilaxis_antibiotica_si" value="1"> Sí<br>
                                    <input type="checkbox" name="profilaxis_antibiotica_np" value="1"> No procede
                                </div>
                                <hr>
                                <strong>Previsión de eventos críticos</strong>
                                <div class="checkbox-group">
                                    <label>
                                        <input type="checkbox" name="problemas_instrumental" value="1">
                                        <strong>¿Hay dudas o problemas relacionados con el instrumental y los equipos?</strong>
                                    </label>
                                </div>
                                <div class="checkbox-group">
                                    <label>
                                        <input type="checkbox" name="duracion_operacion" value="1">
                                        <strong>Cirujano: ¿Cuánto durará la operación?</strong>
                                    </label>
                                </div>
                                <div class="checkbox-group">
                                    <label>
                                        <input type="checkbox" name="perdida_sangre" value="1">
                                        <strong>Cirujano: ¿Cuál es la pérdida de sangre prevista?</strong>
                                    </label>
                                </div>
                                <div class="checkbox-group">
                                    <label>
                                        <input type="checkbox" name="problemas_paciente" value="1">
                                        <strong>Anestesista: ¿Presenta el paciente algún problema específico?</strong>
                                    </label>
                                </div>
                                <div class="checkbox-group">
                                    <label>
                                        <input type="checkbox" name="esterilidad_confirmada" value="1">
                                        <strong>¿Se ha confirmado la esterilidad (con resultados de los indicadores)?</strong>
                                    </label>
                                </div>
                                <div class="checkbox-group">
                                    <strong>¿Pueden visualizarse las imágenes diagnósticas esenciales?</strong><br>
                                    <input type="checkbox" name="imagenes_visibles_si" value="1"> Sí<br>
                                    <input type="checkbox" name="imagenes_visibles_np" value="1"> No procede
                                </div>
                            </div>
                            <!-- Sección 3 -->
                            <div class="card" style="flex: 1; padding: 20px; border: 2px solid #e3e6f0; border-radius: 15px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%); box-shadow: 0 4px 15px rgba(43, 45, 127, 0.1); transition: all 0.3s ease; position: relative; overflow: hidden;">
                                <h4 style="margin-bottom: 20px; color: #2b2d7f; font-weight: 600; font-size: 18px; padding-bottom: 10px; border-bottom: 2px solid #e3e6f0;">Antes de salir del quirófano</h4>
                                <div class="checkbox-group">
                                    <label>
                                        <input type="checkbox" name="nombre_procedimiento" value="1">
                                        <strong>El enfermero confirma verbalmente: El nombre del procedimiento</strong>
                                    </label>
                                </div>
                                <div class="checkbox-group">
                                    <label>
                                        <input type="checkbox" name="recuento_instrumental" value="1">
                                        <strong>El recuento de instrumentos, gasas y agujas</strong>
                                    </label>
                                </div>
                                <div class="checkbox-group">
                                    <label>
                                        <input type="checkbox" name="etiquetado_muestras" value="1">
                                        <strong>El etiquetado de las muestras (lectura de la etiqueta en voz alta, incluido el nombre del paciente)</strong>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <strong>Cirujano, anestesista y enfermero:</strong><br>
                                        <input type="checkbox" name="aspectos_recuperacion" value="1">
                                        ¿Cuáles son los aspectos críticos de la recuperación y el tratamiento del paciente?
                                    </label>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">FIRMAR</button>
                            <a href="../../template/select_pac_enf.php" class="btn btn-danger">Cancelar</a>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="signos" role="tabpanel">
                    <!-- Header con estadísticas -->
                    <div class="signos-container">
                        <div class="signos-header">
                            <i class="fas fa-heartbeat"></i> Registro de Signos Vitales
                            <div class="signos-subtitle">Monitoreo continuo del estado vital del paciente</div>
                        </div>

                        <!-- Panel de acciones rápidas -->
                        <div class="signos-actions-panel">
                            <div class="row g-3">
                                <div class="col-lg-4">
                                    <div class="action-card">
                                        <div class="action-icon bg-success">
                                            <i class="fas fa-plus"></i>
                                        </div>
                                        <div class="action-content">
                                            <h6>Nuevo Registro</h6>
                                            <p>Agregar signos vitales</p>
                                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModalS">
                                                <i class="fas fa-plus-circle"></i> Registrar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="action-card">
                                        <div class="action-icon bg-primary">
                                            <i class="fas fa-chart-line"></i>
                                        </div>
                                        <div class="action-content">
                                            <h6>Gráficas</h6>
                                            <p>Visualizar tendencias</p>
                                            <a href="ver_grafica.php" target="_blank" class="btn btn-primary btn-sm">
                                                <i class="fas fa-external-link-alt"></i> Ver Gráficas
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="action-card">
                                        <div class="action-icon bg-info">
                                            <i class="fas fa-search"></i>
                                        </div>
                                        <div class="action-content">
                                            <h6>Buscar</h6>
                                            <p>Filtrar registros</p>
                                            <input type="text" class="form-control form-control-sm" id="search_nuevoS" placeholder="Buscar registros...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Últimos valores registrados -->
                        <div class="signos-summary">
                            <div class="summary-title">
                                <i class="fas fa-clock"></i> Últimos Valores Registrados
                            </div>
                            <div class="row g-3" id="ultimosValoresSignos">
                                <div class="col-md-2">
                                    <div class="valor-card presion">
                                        <div class="valor-icon">
                                            <i class="fas fa-tachometer-alt"></i>
                                        </div>
                                        <div class="valor-info">
                                            <span class="valor-label">Presión</span>
                                            <span class="valor-number" id="ultimaPresion">--/--</span>
                                            <span class="valor-unit">mmHg</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="valor-card frecuencia">
                                        <div class="valor-icon">
                                            <i class="fas fa-heartbeat"></i>
                                        </div>
                                        <div class="valor-info">
                                            <span class="valor-label">FC</span>
                                            <span class="valor-number" id="ultimaFC">--</span>
                                            <span class="valor-unit">lpm</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="valor-card respiracion">
                                        <div class="valor-icon">
                                            <i class="fas fa-lungs"></i>
                                        </div>
                                        <div class="valor-info">
                                            <span class="valor-label">FR</span>
                                            <span class="valor-number" id="ultimaFR">--</span>
                                            <span class="valor-unit">rpm</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="valor-card saturacion">
                                        <div class="valor-icon">
                                            <i class="fas fa-percentage"></i>
                                        </div>
                                        <div class="valor-info">
                                            <span class="valor-label">SpO₂</span>
                                            <span class="valor-number" id="ultimaSat">--</span>
                                            <span class="valor-unit">%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="valor-card temperatura">
                                        <div class="valor-icon">
                                            <i class="fas fa-thermometer-half"></i>
                                        </div>
                                        <div class="valor-info">
                                            <span class="valor-label">Temp</span>
                                            <span class="valor-number" id="ultimaTemp">--</span>
                                            <span class="valor-unit">°C</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="valor-card tiempo">
                                        <div class="valor-icon">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div class="valor-info">
                                            <span class="valor-label">Último</span>
                                            <span class="valor-number" id="ultimaHora">--:--</span>
                                            <span class="valor-unit">hrs</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de registros -->
                        <div class="signos-table-container">
                            <div class="table-header">
                                <h5><i class="fas fa-table"></i> Historial de Registros</h5>
                                <div class="table-controls">
                                    <span class="badge bg-secondary" id="totalRegistros">0 registros</span>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="exampleS" class="table table-signos-vitales" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th><i class="fas fa-hashtag"></i> ID</th>
                                            <th><i class="fas fa-calendar"></i> Fecha</th>
                                            <th><i class="fas fa-clock"></i> Hora</th>
                                            <th><i class="fas fa-tachometer-alt"></i> Presión</th>
                                            <th><i class="fas fa-heartbeat"></i> FC</th>
                                            <th><i class="fas fa-lungs"></i> FR</th>
                                            <th><i class="fas fa-percentage"></i> SpO₂</th>
                                            <th><i class="fas fa-thermometer-half"></i> Temp</th>
                                            <th><i class="fas fa-cogs"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Modal para agregar signos vitales -->
                    <div class="modal fade" id="addUserModalS" tabindex="-1" aria-labelledby="addUserModalLabelS" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content signos-modal">
                                <div class="modal-header signos-modal-header">
                                    <h5 class="modal-title" id="addUserModalLabelS">
                                        <i class="fas fa-plus-circle"></i> Nuevo Registro de Signos Vitales
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body signos-modal-body">
                                    <form id="addUserS">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="id_usua" value="<?php echo $id_usuario; ?>">
                                        <input type="hidden" name="id_atencion" value="<?php echo $id_atencion; ?>">

                                        <!-- Hora -->
                                        <div class="signos-form-group">
                                            <label for="addhoraField" class="signos-label">
                                                <i class="fas fa-clock"></i> Hora del Registro
                                            </label>
                                            <input type="time" name="hora" id="addhoraField" class="form-control-signos" required>
                                            <small class="form-text text-muted">Hora en que se tomaron los signos vitales</small>
                                        </div>

                                        <!-- Presión Arterial -->
                                        <div class="signos-form-group">
                                            <label class="signos-label">
                                                <i class="fas fa-tachometer-alt"></i> Presión Arterial (mmHg)
                                            </label>
                                            <div class="presion-inputs">
                                                <div class="presion-group">
                                                    <input type="number" class="form-control-signos presion-input" id="addsistgField" name="sistg" placeholder="120" min="50" max="250" required>
                                                    <label>Sistólica</label>
                                                </div>
                                                <div class="presion-separator">/</div>
                                                <div class="presion-group">
                                                    <input type="number" class="form-control-signos presion-input" id="adddiastgField" name="diastg" placeholder="80" min="30" max="150" required>
                                                    <label>Diastólica</label>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">Rango normal: 90/60 - 140/90 mmHg</small>
                                        </div>

                                        <!-- Frecuencias y Saturación -->
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="signos-form-group">
                                                    <label for="addfcardgField" class="signos-label">
                                                        <i class="fas fa-heartbeat text-danger"></i> FC (lpm)
                                                    </label>
                                                    <input type="number" class="form-control-signos" id="addfcardgField" name="fcardg" placeholder="72" min="30" max="250" required>
                                                    <small class="form-text text-muted">60-100 lpm</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="signos-form-group">
                                                    <label for="addfrespgField" class="signos-label">
                                                        <i class="fas fa-lungs text-info"></i> FR (rpm)
                                                    </label>
                                                    <input type="number" class="form-control-signos" id="addfrespgField" name="frespg" placeholder="18" min="8" max="50" required>
                                                    <small class="form-text text-muted">12-20 rpm</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="signos-form-group">
                                                    <label for="addsatgField" class="signos-label">
                                                        <i class="fas fa-percentage text-primary"></i> SpO₂ (%)
                                                    </label>
                                                    <input type="number" class="form-control-signos" id="addsatgField" name="satg" placeholder="98" min="50" max="100" required>
                                                    <small class="form-text text-muted">95-100%</small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Temperatura -->
                                        <div class="signos-form-group">
                                            <label for="addtempgField" class="signos-label">
                                                <i class="fas fa-thermometer-half text-warning"></i> Temperatura (°C)
                                            </label>
                                            <input type="number" step="0.1" class="form-control-signos" id="addtempgField" name="tempg" placeholder="36.5" min="34" max="44" required>
                                            <small class="form-text text-muted">Rango normal: 36.1 - 37.2°C</small>
                                        </div>

                                        <div class="signos-form-actions">
                                            <button type="submit" class="btn btn-signos-primary">
                                                <i class="fas fa-save"></i> Guardar Registro
                                            </button>
                                            <button type="button" class="btn btn-signos-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times"></i> Cancelar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal para editar signos vitales -->
                    <div class="modal fade" id="exampleModalS" tabindex="-1" aria-labelledby="exampleModalLabelS" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content signos-modal">
                                <div class="modal-header signos-modal-header">
                                    <h5 class="modal-title" id="exampleModalLabelS">
                                        <i class="fas fa-edit"></i> Editar Registro de Signos Vitales
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body signos-modal-body">
                                    <form id="updateUserS">
                                        <input type="hidden" name="id" id="id" value="">
                                        <input type="hidden" name="trid" id="trid" value="">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="fecha_g" id="fecha_gField">

                                        <!-- Hora -->
                                        <div class="signos-form-group">
                                            <label for="horaField" class="signos-label">
                                                <i class="fas fa-clock"></i> Hora del Registro
                                            </label>
                                            <input type="time" name="hora" id="horaField" class="form-control-signos" required>
                                            <small class="form-text text-muted">Hora en que se tomaron los signos vitales</small>
                                        </div>

                                        <!-- Presión Arterial -->
                                        <div class="signos-form-group">
                                            <label class="signos-label">
                                                <i class="fas fa-tachometer-alt"></i> Presión Arterial (mmHg)
                                            </label>
                                            <div class="presion-inputs">
                                                <div class="presion-group">
                                                    <input type="number" class="form-control-signos presion-input" id="sistgField" name="sistg" placeholder="120" min="50" max="250" required>
                                                    <label>Sistólica</label>
                                                </div>
                                                <div class="presion-separator">/</div>
                                                <div class="presion-group">
                                                    <input type="number" class="form-control-signos presion-input" id="diastgField" name="diastg" placeholder="80" min="30" max="150" required>
                                                    <label>Diastólica</label>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">Rango normal: 90/60 - 140/90 mmHg</small>
                                        </div>

                                        <!-- Frecuencias y Saturación -->
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="signos-form-group">
                                                    <label for="fcardgField" class="signos-label">
                                                        <i class="fas fa-heartbeat text-danger"></i> FC (lpm)
                                                    </label>
                                                    <input type="number" class="form-control-signos" id="fcardgField" name="fcardg" placeholder="72" min="30" max="250" required>
                                                    <small class="form-text text-muted">60-100 lpm</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="signos-form-group">
                                                    <label for="frespgField" class="signos-label">
                                                        <i class="fas fa-lungs text-info"></i> FR (rpm)
                                                    </label>
                                                    <input type="number" class="form-control-signos" id="frespgField" name="frespg" placeholder="18" min="8" max="50" required>
                                                    <small class="form-text text-muted">12-20 rpm</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="signos-form-group">
                                                    <label for="satgField" class="signos-label">
                                                        <i class="fas fa-percentage text-primary"></i> SpO₂ (%)
                                                    </label>
                                                    <input type="number" class="form-control-signos" id="satgField" name="satg" placeholder="98" min="50" max="100" required>
                                                    <small class="form-text text-muted">95-100%</small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Temperatura -->
                                        <div class="signos-form-group">
                                            <label for="tempgField" class="signos-label">
                                                <i class="fas fa-thermometer-half text-warning"></i> Temperatura (°C)
                                            </label>
                                            <input type="number" step="0.1" class="form-control-signos" id="tempgField" name="tempg" placeholder="36.5" min="34" max="44" required>
                                            <small class="form-text text-muted">Rango normal: 36.1 - 37.2°C</small>
                                        </div>

                                        <div class="signos-form-actions">
                                            <button type="submit" class="btn btn-signos-primary">
                                                <i class="fas fa-save"></i> Actualizar Registro
                                            </button>
                                            <button type="button" class="btn btn-signos-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times"></i> Cancelar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nota" role="tabpanel">
                    <!-- Sección de Tratamientos -->
                    <div class="card mt-3">
                        <div class="thead">Seleccione los tratamientos a realizar:</div>
                        <div class="card-body">
                            <div class="form-group mb-0">
                                <div class="row">
                                    <?php
                                    $sql_trat = "SELECT id, tipo FROM tratamientos ORDER BY tipo";
                                    $stmt = $conexion->prepare($sql_trat);
                                    if (!$stmt) {
                                        die("Prepare failed: " . $conexion->error);
                                    }
                                    $stmt->execute();
                                    $result_trat = $stmt->get_result();
                                    $contador = 0;
                                    while ($row_trat = $result_trat->fetch_assoc()) {
                                        $tipo = htmlspecialchars($row_trat['tipo'], ENT_QUOTES, 'UTF-8');
                                        $id = htmlspecialchars($row_trat['id'], ENT_QUOTES, 'UTF-8');
                                        $contador++;
                                        if ($contador % 2 == 1) {
                                            echo '<div class="col-md-6 mb-3">';
                                        }
                                        $es_lasik = (strtoupper($tipo) === 'CIRUGÍA LASIK' || strtoupper($tipo) === 'CIRUGIA LASIK');
                                        $clase_adicional = $es_lasik ? ' lasik-checkbox' : ' general-checkbox';
                                        echo '<div class="form-check" style="margin-bottom: 8px;">';
                                        echo '<input class="form-check-input tratamiento-checkbox' . $clase_adicional . '" type="checkbox" value="' . $id . '" id="trat_nota_' . $id . '" data-tipo="' . $tipo . '" style="transform: scale(1.3); margin-right: 10px;">';
                                        echo '<label class="form-check-label" for="trat_nota_' . $id . '">' . strtoupper($tipo) . '</label>';
                                        echo '</div>';
                                        if ($contador % 2 == 0) {
                                            echo '</div>';
                                        }
                                    }
                                    if ($contador % 2 == 1) {
                                        echo '</div>';
                                    }
                                    $stmt->close();
                                    ?>
                                </div>
                            </div>
                            <div id="formulario_contenedor_nota" style="display: none;">
                                <div class="card formulario-tratamiento" id="formulario_general_nota" style="display: none;">
                                    <div class="thead" id="titulo_tratamientos_dinamico_nota">FORMULARIO DE TRATAMIENTOS SELECCIONADOS</div>
                                    <div class="card-body">
                                        <form action="insertar_tratamientos_multiples.php" method="POST" id="formulario_unificado_nota">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <input type="hidden" name="id_exp" value="<?php echo $id_exp; ?>">
                                            <input type="hidden" name="id_usua" value="<?php echo $id_usuario; ?>">
                                            <input type="hidden" name="id_atencion" value="<?php echo $id_atencion; ?>">
                                            <input type="hidden" name="tratamientos_seleccionados" id="tratamientos_seleccionados_input_nota">
                                            <div class="form-group">
                                                <label>Nombre del médico tratante:</label>
                                                <select class="form-control" name="medico_tratante" required>
                                                    <option value="">Seleccione un médico tratante</option>
                                                    <?php
                                                    $sql_med_form = "SELECT id_usua, nombre, papell, sapell FROM reg_usuarios WHERE id_rol = '2' AND u_activo = 'SI'";
                                                    $stmt_form = $conexion->prepare($sql_med_form);
                                                    $stmt_form->execute();
                                                    $result_med_form = $stmt_form->get_result();
                                                    while ($med_form = $result_med_form->fetch_assoc()) {
                                                        $nombre_med_form = trim($med_form['nombre'] . ' ' . $med_form['papell'] . ' ' . $med_form['sapell']);
                                                        echo '<option value="' . htmlspecialchars($nombre_med_form, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($nombre_med_form, ENT_QUOTES, 'UTF-8') . '</option>';
                                                    }
                                                    $stmt_form->close();
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Anestesiólogo:</label>
                                                <select class="form-control" name="anestesiologo" required>
                                                    <option value="">Seleccione un anestesiólogo</option>
                                                    <?php
                                                    $sql_anes_form = "SELECT id_usua, nombre, papell, sapell FROM reg_usuarios WHERE cargp LIKE '%ANESTESIOLOGO%' AND u_activo = 'SI'";
                                                    $stmt_anes_form = $conexion->prepare($sql_anes_form);
                                                    $stmt_anes_form->execute();
                                                    $result_anes_form = $stmt_anes_form->get_result();
                                                    while ($anes_form = $result_anes_form->fetch_assoc()) {
                                                        $nombre_anes_form = trim($anes_form['nombre'] . ' ' . $anes_form['papell'] . ' ' . $anes_form['sapell']);
                                                        echo '<option value="' . htmlspecialchars($nombre_anes_form, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($nombre_anes_form, ENT_QUOTES, 'UTF-8') . '</option>';
                                                    }
                                                    $stmt_anes_form->close();
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Anestesia:</label>
                                                <select class="form-control" name="anestesia" required>
                                                    <option value="">Seleccione tipo de anestesia</option>
                                                    <option value="LOCAL">LOCAL</option>
                                                    <option value="SEDACIÓN">SEDACIÓN</option>
                                                    <option value="GENERAL">GENERAL</option>
                                                </select>
                                            </div>
                                            <div class="row" id="campos_lasik_nota" style="display:none;">
                                                <div class="col-md-6">
                                                    <label>OD</label>
                                                    <input type="text" class="form-control mb-1" name="od_queratometria" placeholder="QUERATOMETRIA">
                                                    <input type="text" class="form-control mb-1" name="od_microqueratomo" placeholder="MICROQUERATOMO">
                                                    <input type="text" class="form-control mb-1" name="od_anillo" placeholder="ANILLO">
                                                    <input type="text" class="form-control mb-1" name="od_tope" placeholder="TOPE">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>OI</label>
                                                    <input type="text" class="form-control mb-1" name="oi_queratometria" placeholder="QUERATOMETRIA">
                                                    <input type="text" class="form-control mb-1" name="oi_microqueratomo" placeholder="MICROQUERATOMO">
                                                    <input type="text" class="form-control mb-1" name="oi_anillo" placeholder="ANILLO">
                                                    <input type="text" class="form-control mb-1" name="oi_tope" placeholder="TOPE">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Nota de Enfermería -->
                    <div class="card mt-3">
                        <div class="thead">Nota de Enfermería</div>
                        <div class="card-body">
                            <form action="insertar_nota_enfermeria.php" method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="id_exp" value="<?php echo $id_exp; ?>">
                                <input type="hidden" name="id_usua" value="<?php echo $id_usuario; ?>">
                                <input type="hidden" name="id_atencion" value="<?php echo $id_atencion; ?>">

                                <!-- Campos ocultos para capturar datos de tratamientos desde el formulario principal -->
                                <input type="hidden" name="tratamientos_seleccionados" id="tratamientos_seleccionados_nota" value="">
                                <input type="hidden" name="medico_tratante" id="medico_tratante_nota" value="">
                                <input type="hidden" name="anestesiologo" id="anestesiologo_nota" value="">
                                <input type="hidden" name="anestesia" id="anestesia_nota" value="">

                                <div class="form-group mt-3">
                                    <label>Nota de enfermería:</label>
                                    <div class="btn-group mb-2">
                                        <button type="button" class="btn btn-danger btn-sm grabar-nota" title="Iniciar dictado por voz (Ctrl+Shift+R)">
                                            <i class="fas fa-microphone"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm detener-nota" title="Detener dictado por voz (Ctrl+Shift+S)" disabled>
                                            <i class="fas fa-microphone-slash"></i>
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm reproducir-nota" title="Reproducir texto escrito (Ctrl+Shift+P)">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    </div>
                                    <textarea class="form-control nota-enfermeria" rows="5" name="nota_enfermeria" required placeholder="Escriba aquí la nota de enfermería o use el dictado por voz..."></textarea>
                                    <small class="form-text text-muted">
                                        💡 <strong>Tip:</strong> Use los botones de arriba para dictar por voz o reproducir el texto. Funciona mejor en navegadores Chrome, Edge o Safari.
                                    </small>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        <label>ENFERMERA RESPONSABLE:</label>
                                        <input type="text" class="form-control" name="enfermera_responsable" value="<?php echo $usuario_actual; ?>" readonly style="background-color: #e9ecef;">
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save"></i> Guardar Nota
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="ingresos" role="tabpanel">
                    <div class="thead"><strong>INGRESOS</strong></div>
                    <div class="container mt-3">
                        <div class="text-center mb-3">
                            <a href="#!" data-bs-toggle="modal" data-bs-target="#addUserModalI" class="btn btn-success">Agregar nuevos ingresos</a>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" style="width:25%" id="search_nuevoI" placeholder="Buscar...">
                        </div>
                        <table id="exampleI" class="table table-striped" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Fecha de registro</th>
                                    <th>Fecha de reporte</th>
                                    <th>Hora</th>
                                    <th>Soluciones</th>
                                    <th>Volumen</th>
                                    <th>Registró</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <!-- Ingresos Modal -->
                    <div class="modal fade" id="addUserModalI" tabindex="-1" aria-labelledby="addUserModalLabelI" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addUserModalLabelI">Agregar nuevos ingresos</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="addUserI">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="id_usua" value="<?php echo $id_usuario; ?>">
                                        <input type="hidden" name="id_atencion" value="<?php echo $id_atencion; ?>">
                                        <div class="mb-3 row">
                                            <label for="addfechaiField" class="col-md-3 form-label">Fecha de reporte</label>
                                            <div class="col-md-9">
                                                <input type="date" class="form-control" id="addfechaiField" name="fechai" value="<?php echo date("Y-m-d"); ?>">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="addhoraiField" class="col-md-3 form-label">Hora</label>
                                            <div class="col-md-9">
                                                <input type="time" class="form-control" id="addhoraiField" name="horai">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="addsolucionesField" class="col-md-3 form-label">Describir ingresos</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="addsolucionesField" name="soluciones">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="addvolumenField" class="col-md-3 form-label">Volumen</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="addvolumenField" name="volumen">
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="exampleModalI" tabindex="-1" aria-labelledby="exampleModalLabelI" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabelI">Editar registro</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="updateUserI">
                                        <input type="hidden" name="id" id="id">
                                        <input type="hidden" name="trid" id="trid">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="fecha_registro" id="fecha_registroField">
                                        <input type="hidden" name="id_usua" id="id_usuaField" value="<?php echo $id_usuario; ?>">
                                        <div class="mb-3 row">
                                            <label for="fechaiField" class="col-md-3 form-label">Fecha de reporte</label>
                                            <div class="col-md-9">
                                                <input type="date" class="form-control" id="fechaiField" name="fechai">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="horaiField" class="col-md-3 form-label">Hora</label>
                                            <div class="col-md-9">
                                                <input type="time" class="form-control" id="horaiField" name="horai">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="solucionesField" class="col-md-3 form-label">Describir ingresos</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="solucionesField" name="soluciones">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="volumenField" class="col-md-3 form-label">Volumen</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="volumenField" name="volumen">
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">Editar</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="thead mt-4"><strong>EGRESOS</strong></div>
                    <div class="container mt-3">
                        <div class="text-center mb-3">
                            <a href="#!" data-bs-toggle="modal" data-bs-target="#addUserModalE" class="btn btn-success">Agregar nuevos egresos</a>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" style="width:25%" id="search_nuevoE" placeholder="Buscar...">
                        </div>
                        <table id="exampleE" class="table table-striped" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Fecha de registro</th>
                                    <th>Fecha de reporte</th>
                                    <th>Hora</th>
                                    <th>Soluciones</th>
                                    <th>Volumen</th>
                                    <th>Registró</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <!-- Egresos Modal -->
                    <div class="modal fade" id="addUserModalE" tabindex="-1" aria-labelledby="addUserModalLabelE" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addUserModalLabelE">Agregar nuevos egresos</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="addUserE">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="id_usua" value="<?php echo $id_usuario; ?>">
                                        <input type="hidden" name="id_atencion" value="<?php echo $id_atencion; ?>">
                                        <div class="mb-3 row">
                                            <label for="addfechaeField" class="col-md-3 form-label">Fecha de reporte</label>
                                            <div class="col-md-9">
                                                <input type="date" class="form-control" id="addfechaeField" name="fechae" value="<?php echo date("Y-m-d"); ?>">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="addhoraeField" class="col-md-3 form-label">Hora</label>
                                            <div class="col-md-9">
                                                <input type="time" class="form-control" id="addhoraeField" name="horae">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="addsolucioneseField" class="col-md-3 form-label">Describir egresos</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="addsolucioneseField" name="solucionese">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="addvolumeneField" class="col-md-3 form-label">Volumen</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="addvolumeneField" name="volumene">
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="exampleModalE" tabindex="-1" aria-labelledby="exampleModalLabelE" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabelE">Editar registro</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="updateUserE">
                                        <input type="hidden" name="id" id="id">
                                        <input type="hidden" name="trid" id="trid">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="fecha_registro" id="fecha_registroeField">
                                        <input type="hidden" name="id_usua" id="id_usuaeField" value="<?php echo $id_usuario; ?>">
                                        <div class="mb-3 row">
                                            <label for="fechaeField" class="col-md-3 form-label">Fecha de reporte</label>
                                            <div class="col-md-9">
                                                <input type="date" class="form-control" id="fechaeField" name="fechae">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="horaeField" class="col-md-3 form-label">Hora</label>
                                            <div class="col-md-9">
                                                <input type="time" class="form-control" id="horaeField" name="horae">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="solucioneseField" class="col-md-3 form-label">Describir egresos</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="solucioneseField" name="solucionese">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="volumeneField" class="col-md-3 form-label">Volumen</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="volumeneField" name="volumene">
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">Editar</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="insumos" role="tabpanel">
                    <div class="thead">
                        <strong>SURTIR PACIENTE</strong>
                    </div>

                    <br><br>

                    <form id="insumos-form" method="post">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <div class="form-group">
                            <label for="paciente">Paciente</label>
                            <select class="form-control" name="paciente" id="paciente">
                                <option value="" disabled selected>Seleccionar Paciente</option>
                                <?= $pacientesOptions ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="medicamento">Medicamento</label>
                            <select class="form-control" name="medicamento" id="medicamento">
                                <option value="" disabled selected>Seleccionar Medicamento</option>
                                <?= $medicamentosOptions ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="lote">Lote</label>
                            <select class="form-control" name="lote" id="lote">
                                <option value="" disabled selected>Lote/Caducidad/Total</option>
                                <?= $lotesOptions ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="cantidad">Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" min="1">
                        </div>

                        <div id="existe_id_display" class="font-weight-bold mt-2"></div>

                        <div class="d-flex justify-content-center">
                            <button type="button" id="agregar-btn" class="btn btn-success mr-2">Agregar</button>
                    <button type="button" id="enviar-btn" class="btn btn-primary">Enviar</button>
                </div>
                <input type="hidden" name="enviar_medicamentos" value="1">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                    </form>

                    <hr>

                    <div class="thead">
                        <strong>ITEMS A SURTIR</strong>
                    </div>
                    <div id="tabla-medicamentos">
                        <?php
                        if (isset($_SESSION['medicamento_seleccionado']) && is_array($_SESSION['medicamento_seleccionado'])) {
                            echo "<table class='table table-bordered table-striped'>";
                            echo "<thead class='thead-dark'>
                        <tr>
                            <th>Paciente</th>
                            <th>Medicamento</th>
                            <th>Lote</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead><tbody>";
                            foreach ($_SESSION['medicamento_seleccionado'] as $index => $medicamento) {
                                if (is_array($medicamento) && isset($medicamento['paciente'], $medicamento['medicamento'], $medicamento['lote'], $medicamento['cantidad'])) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($medicamento['paciente']) . "</td>";
                                    echo "<td>" . htmlspecialchars($medicamento['medicamento']) . "</td>";
                                    echo "<td>" . htmlspecialchars($medicamento['lote']) . "</td>";
                                    echo "<td>" . htmlspecialchars($medicamento['cantidad']) . "</td>";
                                    echo "<td>" . htmlspecialchars($medicamento['precio']) . "</td>";
                                    echo "<td>
                                <form action='' method='post' class='eliminar-form' data-index='$index'>
                                    <input type='hidden' name='eliminar_index' value='$index'>
                                    <button type='submit' class='btn btn-danger'>Eliminar</button>
                                </form>
                            </td>";
                                    echo "</tr>";
                                } else {
                                    echo "<tr><td colspan='6'>Datos incompletos para el medicamento.</td></tr>";
                                }
                            }
                            echo "</tbody></table>";
                        } else {
                            echo "<p class='text-center'>No hay medicamentos seleccionados.</p>";
                        }
                        ?>
                    </div>

                    <hr>

                    <div class="thead">
                        <strong>ITEMS SURTIDOS</strong>
                    </div>
                    <div id="tabla-surtidos">
                        <?= $itemsSurtidos ?>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="equipos" role="tabpanel">
                <div class="thead"><strong>REGISTRAR EQUIPOS</strong></div>
                <hr>
                <!-- Dropdown Menu -->
                <div class="mb-3">
                    <label for="serviceSelect" class="form-label">Seleccionar Equipo:</label>
                    <select class="custom-select" id="serviceSelect" onchange="addService()">
                        <option value="">Seleccione un equipo</option>
                        <?php
                        $sql = "SELECT id_serv, serv_desc, serv_costo FROM cat_servicios WHERE grupo = 'CEYE' AND serv_activo = 'SI'";
                        $result = $conexion->query($sql);
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['id_serv']}' data-desc='" . htmlspecialchars($row['serv_desc'], ENT_QUOTES, 'UTF-8') . "' data-cost='{$row['serv_costo']}'>" . htmlspecialchars($row['serv_desc'], ENT_QUOTES, 'UTF-8') . "</option>";
                            }
                            $result->free();
                        } else {
                            echo "<option value=''>Error al cargar servicios</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Table for Selected Services -->
                <table class="table table-bordered table-hover" id="selectedServicesTable">
                    <thead class="table-light">
                        <tr>
                            <th>Descripción</th>
                            <th>Costo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="selectedServicesBody"></tbody>
                </table>

                <!-- Submit Button -->
                <button class="btn btn-primary mt-3" onclick="submitServices()">Registrar Equipos</button>
                <BR></BR>
                <!-- Table for Registered Services -->
                <div class="thead"><strong>EQUIPOS REGISTRADOS</strong></div>
                <hr>
                <table class="table table-bordered table-hover" id="registeredServicesTable">
                    <thead class="table-light">
                        <tr>
                            <th>Descripción</th>
                            <th>Costo</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="registeredServicesBody"></tbody>
                </table>
            </div>
        </div>
    </div>
    <footer class="main-footer mt-4">
        <div class="fs-6">
            <?php include '../../template/footer.php'; ?>
        </div>
    </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
    <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            // Initialize tabs
            $('#menuRegistroTabs a').on('click', function(e) {
                e.preventDefault();
                $(this).tab('show');
            });

            // Initialize DataTables for Ingresos
            $('#exampleI').DataTable({
                language: {
                    decimal: "",
                    emptyTable: "No hay información",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    infoEmpty: "Mostrando 0 to 0 of 0 Entradas",
                    infoFiltered: "(Filtrado de _MAX_ total entradas)",
                    infoPostFix: "",
                    thousands: ",",
                    lengthMenu: "Mostrar _MENU_ Entradas",
                    loadingRecords: "Cargando...",
                    processing: "Procesando...",
                    search: "Buscar:",
                    zeroRecords: "Sin resultados encontrados",
                    paginate: {
                        first: "Primero",
                        last: "Ultimo",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                },
                fnCreatedRow: function(nRow, aData) {
                    $(nRow).attr('id', aData[0]);
                },
                serverSide: true,
                processing: true,
                paging: true,
                searching: false,
                order: [],
                ajax: {
                    url: 'fetch_dataI.php',
                    type: 'POST',
                    data: function(d) {
                        d.id_atencion = '<?php echo $id_atencion; ?>';
                        d.csrf_token = '<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>';
                    }
                }
            });

            // Initialize DataTables for Egresos
            $('#exampleE').DataTable({
                language: {
                    decimal: "",
                    emptyTable: "No hay información",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    infoEmpty: "Mostrando 0 to 0 of 0 Entradas",
                    infoFiltered: "(Filtrado de _MAX_ total entradas)",
                    infoPostFix: "",
                    thousands: ",",
                    lengthMenu: "Mostrar _MENU_ Entradas",
                    loadingRecords: "Cargando...",
                    processing: "Procesando...",
                    search: "Buscar:",
                    zeroRecords: "Sin resultados encontrados",
                    paginate: {
                        first: "Primero",
                        last: "Ultimo",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                },
                fnCreatedRow: function(nRow, aData) {
                    $(nRow).attr('id', aData[0]);
                },
                serverSide: true,
                processing: true,
                paging: true,
                searching: false,
                order: [],
                ajax: {
                    url: 'fetch_dataE.php',
                    type: 'POST',
                    data: {
                        id_atencion: '<?php echo $id_atencion; ?>'
                    }
                },
                columnDefs: [{
                    targets: [7],
                    orderable: false
                }]
            });

            // Cargar signos vitales existentes cuando se abra la pestaña de signos
            $('#signos-tab').on('shown.bs.tab', function(e) {
                cargarSignosVitalesExistentes();
            });

            // Variable global para tracking de signos vitales cargados
            let signosVitalesCargados = false;

            // Handle Ingresos form submission
            $('#addUserI').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                if ($('#addhoraiField').val() && $('#addfechaiField').val() &&
                    $('#addsolucionesField').val() && $('#addvolumenField').val()) {
                    $.ajax({
                        url: "add_userI.php",
                        type: "POST",
                        data: formData,
                        success: function(data) {
                            try {
                                const json = JSON.parse(data);
                                if (json.status === 'true') {
                                    $('#exampleI').DataTable().draw();
                                    $('#addUserI')[0].reset();
                                    $('#addUserModalI').modal('hide');
                                    alertify.success("Registro agregado correctamente");
                                } else {
                                    alertify.error("Error al agregar el registro");
                                }
                            } catch (e) {
                                alertify.error("Error en la respuesta del servidor");
                            }
                        },
                        error: function() {
                            alertify.error("Error en la comunicación con el servidor");
                        }
                    });
                } else {
                    alertify.warning("Completa todos los campos por favor!");
                }
            });

            // Handle Egresos form submission
            $('#addUserE').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                if ($('#addhoraeField').val() && $('#addfechaeField').val() &&
                    $('#addsolucioneseField').val() && $('#addvolumeneField').val()) {
                    $.ajax({
                        url: "add_userE.php",
                        type: "POST",
                        data: formData,
                        success: function(data) {
                            try {
                                const json = JSON.parse(data);
                                if (json.status === 'true') {
                                    $('#exampleE').DataTable().draw();
                                    $('#addUserE')[0].reset();
                                    $('#addUserModalE').modal('hide');
                                    alertify.success("Registro agregado correctamente");
                                } else {
                                    alertify.error("Error al agregar el registro");
                                }
                            } catch (e) {
                                alertify.error("Error en la respuesta del servidor");
                            }
                        },
                        error: function() {
                            alertify.error("Error en la comunicación con el servidor");
                        }
                    });
                } else {
                    alertify.warning("Completa todos los campos por favor!");
                }
            });

            // Handle Ingresos edit
            $('#exampleI').on('click', '.editbtnI', function() {
                const id = $(this).data('id');
                const trid = $(this).closest('tr').attr('id');
                $.ajax({
                    url: "get_single_dataI.php",
                    type: 'POST',
                    data: {
                        id: id
                    },
                    success: function(data) {
                        try {
                            const json = JSON.parse(data);
                            $('#horaiField').val(json.hora);
                            $('#fechaiField').val(json.fecha);
                            $('#solucionesField').val(json.soluciones);
                            $('#volumenField').val(json.volumen);
                            $('#id_usuaField').val(json.id_usua);
                            $('#fecha_registroField').val(json.fecha_registro);
                            $('#id').val(id);
                            $('#trid').val(trid);
                            $('#exampleModalI').modal('show');
                        } catch (e) {
                            alertify.error("Error en la respuesta del servidor");
                        }
                    }
                });
            });

            // Handle Egresos edit
            $('#exampleE').on('click', '.editbtnE', function() {
                const id = $(this).data('id');
                const trid = $(this).closest('tr').attr('id');
                $.ajax({
                    url: "get_single_dataE.php",
                    type: 'POST',
                    data: {
                        id: id
                    },
                    success: function(data) {
                        try {
                            const json = JSON.parse(data);
                            $('#horaeField').val(json.hora);
                            $('#fechaeField').val(json.fecha);
                            $('#solucioneseField').val(json.soluciones);
                            $('#volumeneField').val(json.volumen);
                            $('#id_usuaeField').val(json.id_usua);
                            $('#fecha_registroeField').val(json.fecha_registro);
                            $('#id').val(id);
                            $('#trid').val(trid);
                            $('#exampleModalE').modal('show');
                        } catch (e) {
                            alertify.error("Error en la respuesta del servidor");
                        }
                    }
                });
            });

            // Handle Ingresos update
            $('#updateUserI').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                if ($('#horaiField').val() && $('#fechaiField').val() &&
                    $('#solucionesField').val() && $('#volumenField').val()) {
                    $.ajax({
                        url: "update_userI.php",
                        type: "POST",
                        data: formData,
                        success: function(data) {
                            try {
                                const json = JSON.parse(data);
                                if (json.status === 'true') {
                                    const table = $('#exampleI').DataTable();
                                    const id = $('#id').val();
                                    const trid = $('#trid').val();
                                    const button = `
                                        <div class="action-buttons-container">
                                            <a href="javascript:void(0);"
                                               data-id="${id}"
                                               class="btn btn-action btn-edit-modern editbtnI"
                                               title="Editar registro">
                                                <i class="fas fa-edit"></i>
                                                <span>Editar</span>
                                            </a>
                                            <a href="javascript:void(0);"
                                               data-id="${id}"
                                               class="btn btn-action btn-delete-modern deleteBtnI"
                                               title="Eliminar registro">
                                                <i class="fas fa-trash-alt"></i>
                                                <span>Eliminar</span>
                                            </a>
                                        </div>
                                    `;
                                    const rowData = [
                                        id,
                                        $('#fecha_registroField').val(),
                                        $('#fechaiField').val(),
                                        $('#horaiField').val(),
                                        $('#solucionesField').val(),
                                        $('#volumenField').val(),
                                        $('#id_usuaField').val(),
                                        button
                                    ];
                                    table.row(`[id='${trid}']`).data(rowData);
                                    $('#exampleModalI').modal('hide');
                                    alertify.success("Registro editado correctamente");
                                } else {
                                    alertify.error("Error al editar el registro");
                                }
                            } catch (e) {
                                alertify.error("Error en la respuesta del servidor");
                            }
                        }
                    });
                } else {
                    alertify.warning("Completa todos los campos por favor!");
                }
            });

            // Handle Egresos update
            $('#updateUserE').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                if ($('#horaeField').val() && $('#fechaeField').val() &&
                    $('#solucioneseField').val() && $('#volumeneField').val()) {
                    $.ajax({
                        url: "update_userE.php",
                        type: "POST",
                        data: formData,
                        success: function(data) {
                            try {
                                const json = JSON.parse(data);
                                if (json.status === 'true') {
                                    const table = $('#exampleE').DataTable();
                                    const id = $('#id').val();
                                    const trid = $('#trid').val();
                                    const button = `
                                        <div class="action-buttons-container">
                                            <a href="javascript:void(0);"
                                               data-id="${id}"
                                               class="btn btn-action btn-edit-modern editbtnE"
                                               title="Editar registro">
                                                <i class="fas fa-edit"></i>
                                                <span>Editar</span>
                                            </a>
                                            <a href="javascript:void(0);"
                                               data-id="${id}"
                                               class="btn btn-action btn-delete-modern deleteBtnE"
                                               title="Eliminar registro">
                                                <i class="fas fa-trash-alt"></i>
                                                <span>Eliminar</span>
                                            </a>
                                        </div>
                                    `;
                                    const rowData = [
                                        id,
                                        $('#fecha_registroeField').val(),
                                        $('#fechaeField').val(),
                                        $('#horaeField').val(),
                                        $('#solucioneseField').val(),
                                        $('#volumeneField').val(),
                                        $('#id_usuaeField').val(),
                                        button
                                    ];
                                    table.row(`[id='${trid}']`).data(rowData);
                                    $('#exampleModalE').modal('hide');
                                    alertify.success("Registro editado correctamente");
                                } else {
                                    alertify.error("Error al editar el registro");
                                }
                            } catch (e) {
                                alertify.error("Error en la respuesta del servidor");
                            }
                        }
                    });
                } else {
                    alertify.warning("Completa todos los campos por favor!");
                }
            });

            // Handle Ingresos delete
            $('#exampleI').on('click', '.deleteBtnI', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                if (confirm("¿Estás seguro de eliminar este registro?")) {
                    $.ajax({
                        url: "delete_userI.php",
                        type: "POST",
                        data: {
                            id: id,
                            csrf_token: '<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>'
                        },
                        success: function(data) {
                            try {
                                const json = JSON.parse(data);
                                if (json.status === 'success') {
                                    $("#" + id).closest('tr').remove();
                                    alertify.success("Registro eliminado correctamente");
                                } else {
                                    alertify.error("Error al eliminar el registro");
                                }
                            } catch (e) {
                                alertify.error("Error en la respuesta del servidor");
                            }
                        }
                    });
                }
            });

            // Handle Egresos delete
            $('#exampleE').on('click', '.deleteBtnE', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                if (confirm("¿Estás seguro de eliminar este registro?")) {
                    $.ajax({
                        url: "delete_userE.php",
                        type: "POST",
                        data: {
                            id: id,
                            csrf_token: '<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>'
                        },
                        success: function(data) {
                            try {
                                const json = JSON.parse(data);
                                if (json.status === 'success') {
                                    $("#" + id).closest('tr').remove();
                                    alertify.success("Registro eliminado correctamente");
                                } else {
                                    alertify.error("Error al eliminar el registro");
                                }
                            } catch (e) {
                                alertify.error("Error en la respuesta del servidor");
                            }
                        }
                    });
                }
            });

            // Search functionality
            $('#search_nuevoI').on('keyup', function() {
                $('#exampleI').DataTable().search(this.value).draw();
            });

            $('#search_nuevoE').on('keyup', function() {
                $('#exampleE').DataTable().search(this.value).draw();
            });

            // Toggle medico responsable
            $('#btn_toggle_medico_responsable').on('click', function() {
                $('#select_medico_responsable_wrap').toggle();
            });

            // ========== FUNCIONALIDAD DE SIGNOS VITALES ==========

            /**
             * Configuración del idioma para DataTables
             */
            const dataTableLanguage = {
                decimal: "",
                emptyTable: "No hay información",
                info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                infoEmpty: "Mostrando 0 to 0 of 0 Entradas",
                infoFiltered: "(Filtrado de _MAX_ total entradas)",
                infoPostFix: "",
                thousands: ",",
                lengthMenu: "Mostrar _MENU_ Entradas",
                loadingRecords: "Cargando...",
                processing: "Procesando...",
                search: "Buscar:",
                zeroRecords: "Sin resultados encontrados",
                paginate: {
                    first: "Primero",
                    last: "Ultimo",
                    next: "Siguiente",
                    previous: "Anterior"
                }
            };

            /**
             * Rangos de validación para signos vitales
             */
            const rangoSignosVitales = {
                sistolica: {
                    min: 50,
                    max: 250,
                    unidad: 'mmHg'
                },
                diastolica: {
                    min: 30,
                    max: 150,
                    unidad: 'mmHg'
                },
                frecuenciaCardiaca: {
                    min: 30,
                    max: 250,
                    unidad: 'lpm'
                },
                frecuenciaRespiratoria: {
                    min: 8,
                    max: 50,
                    unidad: 'rpm'
                },
                saturacion: {
                    min: 50,
                    max: 100,
                    unidad: '%'
                },
                temperatura: {
                    min: 34,
                    max: 44,
                    unidad: '°C'
                }
            };

            /**
             * Inicializar DataTable para Signos Vitales
             */
            $('#exampleS').DataTable({
                language: dataTableLanguage,
                fnCreatedRow: function(nRow, aData) {
                    $(nRow).attr('id', aData[0]);
                },
                serverSide: true,
                processing: true,
                paging: true,
                searching: false,
                order: [],
                ajax: {
                    url: 'fetch_dataS.php',
                    type: 'POST',
                    data: {
                        id_atencion: '<?php echo $id_atencion; ?>'
                    }
                },
                columnDefs: [{
                    targets: [8],
                    orderable: false
                }],
                drawCallback: function(settings) {
                    // Actualizar contador de registros
                    const info = this.api().page.info();
                    $('#totalRegistros').text(`${info.recordsTotal} registros`);

                    // Cargar últimos valores
                    cargarUltimosValoresSignos();
                }
            });

            /**
             * Función para cargar y mostrar los últimos valores de signos vitales
             */
            function cargarUltimosValoresSignos() {
                $.ajax({
                    url: 'get_ultimo_signos.php',
                    type: 'POST',
                    data: {
                        id_atencion: '<?php echo $id_atencion; ?>'
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data && !data.error) {
                            // Actualizar valores con animación
                            actualizarValorConAnimacion('#ultimaPresion', `${data.sistg}/${data.diastg}`);
                            actualizarValorConAnimacion('#ultimaFC', data.fcardg);
                            actualizarValorConAnimacion('#ultimaFR', data.frespg);
                            actualizarValorConAnimacion('#ultimaSat', data.satg);
                            actualizarValorConAnimacion('#ultimaTemp', data.tempg);
                            actualizarValorConAnimacion('#ultimaHora', data.hora);

                            // Aplicar códigos de color según valores
                            aplicarCodigosColor(data);
                        }
                    },
                    error: function() {
                        console.log('No se pudieron cargar los últimos valores');
                    }
                });
            }

            /**
             * Función para actualizar un valor con animación
             */
            function actualizarValorConAnimacion(selector, valor) {
                const elemento = $(selector);
                if (elemento.text() !== valor && valor !== '--') {
                    elemento.closest('.valor-card').addClass('actualizado');
                    elemento.text(valor);
                    setTimeout(() => {
                        elemento.closest('.valor-card').removeClass('actualizado');
                    }, 600);
                }
            }

            /**
             * Función para aplicar códigos de color según rangos normales
             */
            function aplicarCodigosColor(data) {
                // Resetear clases
                $('.valor-number').removeClass('text-success text-warning text-danger');

                // Presión arterial
                const sistolica = parseInt(data.sistg);
                const diastolica = parseInt(data.diastg);
                if (sistolica >= 90 && sistolica <= 140 && diastolica >= 60 && diastolica <= 90) {
                    $('#ultimaPresion').addClass('text-success');
                } else if (sistolica > 140 || diastolica > 90) {
                    $('#ultimaPresion').addClass('text-danger');
                } else {
                    $('#ultimaPresion').addClass('text-warning');
                }

                // Frecuencia cardíaca
                const fc = parseInt(data.fcardg);
                if (fc >= 60 && fc <= 100) {
                    $('#ultimaFC').addClass('text-success');
                } else {
                    $('#ultimaFC').addClass('text-warning');
                }

                // Frecuencia respiratoria
                const fr = parseInt(data.frespg);
                if (fr >= 12 && fr <= 20) {
                    $('#ultimaFR').addClass('text-success');
                } else {
                    $('#ultimaFR').addClass('text-warning');
                }

                // Saturación
                const sat = parseInt(data.satg);
                if (sat >= 95) {
                    $('#ultimaSat').addClass('text-success');
                } else if (sat >= 90) {
                    $('#ultimaSat').addClass('text-warning');
                } else {
                    $('#ultimaSat').addClass('text-danger');
                }

                // Temperatura
                const temp = parseFloat(data.tempg);
                if (temp >= 36.1 && temp <= 37.2) {
                    $('#ultimaTemp').addClass('text-success');
                } else {
                    $('#ultimaTemp').addClass('text-warning');
                }
            }

            /**
             * Cargar últimos valores al abrir la pestaña
             */
            $('#signos-tab').on('shown.bs.tab', function() {
                cargarUltimosValoresSignos();
            });

            /**
             * Función para validar rangos de signos vitales
             * @param {string} tipo - Tipo de signo vital a validar
             * @param {string} valor - Valor a validar
             * @returns {object} - {valido: boolean, mensaje: string}
             */
            function validarSignoVital(tipo, valor) {
                if (!valor) return {
                    valido: true,
                    mensaje: ''
                };

                let valorNumerico;
                let rango;

                switch (tipo) {
                    case 'sistolica':
                        valorNumerico = parseInt(valor);
                        rango = rangoSignosVitales.sistolica;
                        break;
                    case 'diastolica':
                        valorNumerico = parseInt(valor);
                        rango = rangoSignosVitales.diastolica;
                        break;
                    case 'frecuenciaCardiaca':
                        valorNumerico = parseInt(valor);
                        rango = rangoSignosVitales.frecuenciaCardiaca;
                        break;
                    case 'frecuenciaRespiratoria':
                        valorNumerico = parseInt(valor);
                        rango = rangoSignosVitales.frecuenciaRespiratoria;
                        break;
                    case 'saturacion':
                        valorNumerico = parseInt(valor.replace('%', ''));
                        rango = rangoSignosVitales.saturacion;
                        break;
                    case 'temperatura':
                        valorNumerico = parseFloat(valor);
                        rango = rangoSignosVitales.temperatura;
                        break;
                    default:
                        return {
                            valido: false, mensaje: 'Tipo de signo vital no reconocido'
                        };
                }

                if (valorNumerico < rango.min || valorNumerico > rango.max) {
                    return {
                        valido: false,
                        mensaje: `${tipo.charAt(0).toUpperCase() + tipo.slice(1)} debe estar entre ${rango.min} y ${rango.max} ${rango.unidad}`
                    };
                }

                return {
                    valido: true,
                    mensaje: ''
                };
            }

            /**
             * Función para validar todos los campos de signos vitales
             * @param {object} campos - Objeto con los valores de los campos
             * @returns {object} - {valido: boolean, errores: array}
             */
            function validarTodosSignosVitales(campos) {
                const errores = [];

                const validaciones = [{
                        tipo: 'sistolica',
                        valor: campos.sistg
                    },
                    {
                        tipo: 'diastolica',
                        valor: campos.diastg
                    },
                    {
                        tipo: 'frecuenciaCardiaca',
                        valor: campos.fcardg
                    },
                    {
                        tipo: 'frecuenciaRespiratoria',
                        valor: campos.frespg
                    },
                    {
                        tipo: 'saturacion',
                        valor: campos.satg
                    },
                    {
                        tipo: 'temperatura',
                        valor: campos.tempg
                    }
                ];

                validaciones.forEach(validacion => {
                    const resultado = validarSignoVital(validacion.tipo, validacion.valor);
                    if (!resultado.valido) {
                        errores.push(resultado.mensaje);
                    }
                });

                return {
                    valido: errores.length === 0,
                    errores: errores
                };
            }

            /**
             * Función para mostrar errores de validación
             * @param {array} errores - Array de mensajes de error
             */
            function mostrarErroresValidacion(errores) {
                errores.forEach(error => {
                    alertify.error(error);
                });
            }

            /**
             * Establecer hora actual al abrir modal de agregar
             */
            $('#addUserModalS').on('show.bs.modal', function() {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                $('#addhoraField').val(`${hours}:${minutes}`);

                // Agregar efecto de entrada suave
                $(this).find('.modal-content').css('transform', 'scale(0.7)');
                setTimeout(() => {
                    $(this).find('.modal-content').css({
                        'transform': 'scale(1)',
                        'transition': 'all 0.3s ease'
                    });
                }, 50);
            });

            /**
             * Efecto para modal de edición
             */
            $('#exampleModalS').on('show.bs.modal', function() {
                // Agregar efecto de entrada suave
                $(this).find('.modal-content').css('transform', 'scale(0.7)');
                setTimeout(() => {
                    $(this).find('.modal-content').css({
                        'transform': 'scale(1)',
                        'transition': 'all 0.3s ease'
                    });
                }, 50);
            });

            /**
             * Validación en tiempo real para inputs numéricos
             */
            $('.form-control-signos[type="number"]').on('input', function() {
                const valor = $(this).val();
                const min = parseInt($(this).attr('min'));
                const max = parseInt($(this).attr('max'));

                // Remover clases previas
                $(this).removeClass('border-success border-warning border-danger');

                if (valor) {
                    const valorNum = parseFloat(valor);
                    if (valorNum < min || valorNum > max) {
                        $(this).addClass('border-danger');
                    } else {
                        $(this).addClass('border-success');
                    }
                }
            });

            /**
             * Auto-refresh de últimos valores cada 30 segundos
             */
            setInterval(function() {
                if ($('#signos').hasClass('active')) {
                    cargarUltimosValoresSignos();
                }
            }, 30000);

            /**
             * Cargar valores iniciales cuando se carga la página
             */
            $(document).ready(function() {
                // Cargar valores después de un breve delay
                setTimeout(() => {
                    cargarUltimosValoresSignos();
                }, 1000);
            });

            /**
             * Manejo del formulario para agregar Signos Vitales
             */
            $('#addUserS').on('submit', function(e) {
                e.preventDefault();

                // Recopilar datos del formulario
                const datosFormulario = {
                    hora: $('#addhoraField').val(),
                    sistg: $('#addsistgField').val(),
                    diastg: $('#adddiastgField').val(),
                    fcardg: $('#addfcardgField').val(),
                    frespg: $('#addfrespgField').val(),
                    satg: $('#addsatgField').val(),
                    tempg: $('#addtempgField').val()
                };

                // Validar campos obligatorios
                const camposObligatorios = ['hora', 'sistg', 'diastg', 'fcardg', 'frespg', 'satg', 'tempg'];
                const camposFaltantes = camposObligatorios.filter(campo => !datosFormulario[campo]);

                if (camposFaltantes.length > 0) {
                    alertify.warning("Completa todos los campos requeridos por favor!");
                    return;
                }

                // Validar rangos de valores
                const validacion = validarTodosSignosVitales(datosFormulario);
                if (!validacion.valido) {
                    mostrarErroresValidacion(validacion.errores);
                    return;
                }

                // Preparar datos para envío
                const datosEnvio = {
                    csrf_token: $('input[name="csrf_token"]').val(),
                    id_usua: $('input[name="id_usua"]').val(),
                    id_atencion: $('input[name="id_atencion"]').val(),
                    ...datosFormulario
                };

                console.log('📤 Enviando datos:', datosEnvio);

                // Envío AJAX
                $.ajax({
                    url: "add_userS.php",
                    type: "POST",
                    data: datosEnvio,
                    dataType: 'json',
                    success: function(data) {
                        console.log('📥 Respuesta recibida:', data);

                        if (data.status === 'true') {
                            // Éxito: cerrar modal, limpiar formulario y recargar tabla
                            $('#addUserModalS').modal('hide');
                            $('#addUserS')[0].reset();
                            $('#exampleS').DataTable().draw();

                            // Actualizar últimos valores inmediatamente
                            setTimeout(() => {
                                cargarUltimosValoresSignos();
                            }, 500);

                            // Mostrar notificación de éxito mejorada
                            alertify.success("✅ Registro de signos vitales agregado correctamente");
                        } else {
                            // Error del servidor
                            console.error('❌ Error del servidor:', data);
                            if (data.debug) {
                                console.log('🔍 Información de debug:', data.debug);
                            }
                            alertify.error(data.message || "Error al agregar el registro");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('❌ Error AJAX completo:', {
                            xhr: xhr,
                            status: status,
                            error: error,
                            responseText: xhr.responseText
                        });
                        alertify.error("Error de conexión al servidor: " + error);
                    }
                });
            });

            /**
             * Manejo de edición de Signos Vitales
             */
            $('#exampleS').on('click', '.editbtnS', function() {
                const id = $(this).data('id');
                const trid = $(this).closest('tr').attr('id');

                console.log('📝 Abriendo modal de edición para ID:', id);

                $.ajax({
                    url: "get_single_dataS.php",
                    data: {
                        id: id
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(data) {
                        console.log('📥 Datos cargados para edición:', data);

                        if (data.error) {
                            alertify.error("Error: " + data.error);
                            return;
                        }

                        // Establecer hora actual automáticamente
                        const now = new Date();
                        const hours = String(now.getHours()).padStart(2, '0');
                        const minutes = String(now.getMinutes()).padStart(2, '0');
                        const horaActual = `${hours}:${minutes}`;

                        // Llenar el formulario con los datos
                        $('#horaField').val(horaActual); // Hora actual automática
                        $('#sistgField').val(data.sistg);
                        $('#diastgField').val(data.diastg);
                        $('#fcardgField').val(data.fcardg);
                        $('#frespgField').val(data.frespg);
                        $('#satgField').val(data.satg);
                        $('#tempgField').val(data.tempg);
                        $('#fecha_gField').val(data.fecha_g);
                        $('#id').val(id);
                        $('#trid').val(trid);

                        // Agregar información sobre la hora original
                        if (data.hora && data.hora !== horaActual) {
                            // Mostrar un pequeño indicador de la hora original
                            const horaOriginalInfo = `
                                <div class="alert alert-info alert-dismissible fade show mt-2" role="alert" id="horaOriginalAlert">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Nota:</strong> La hora original era <strong>${data.hora}</strong>.
                                    Se ha establecido la hora actual <strong>${horaActual}</strong> automáticamente.
                                    <button type="button" class="btn btn-sm btn-outline-primary ms-2" onclick="$('#horaField').val('${data.hora}'); $('#horaOriginalAlert').remove();">
                                        <i class="fas fa-undo"></i> Usar hora original
                                    </button>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `;
                            $('#horaField').parent().append(horaOriginalInfo);
                        }

                        // Mostrar el modal
                        $('#exampleModalS').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error('❌ Error al cargar datos:', {
                            xhr: xhr,
                            status: status,
                            error: error,
                            responseText: xhr.responseText
                        });
                        alertify.error("Error al cargar los datos: " + error);
                    }
                });
            });

            /**
             * Manejo de actualización de Signos Vitales
             */
            $('#updateUserS').on('submit', function(e) {
                e.preventDefault();

                // Recopilar datos del formulario
                const datosFormulario = {
                    hora: $('#horaField').val(),
                    sistg: $('#sistgField').val(),
                    diastg: $('#diastgField').val(),
                    fcardg: $('#fcardgField').val(),
                    frespg: $('#frespgField').val(),
                    satg: $('#satgField').val(),
                    tempg: $('#tempgField').val()
                };

                // Validar campos obligatorios
                const camposObligatorios = ['hora', 'sistg', 'diastg', 'fcardg', 'frespg', 'satg', 'tempg'];
                const camposFaltantes = camposObligatorios.filter(campo => !datosFormulario[campo]);

                if (camposFaltantes.length > 0) {
                    alertify.warning("Completa todos los campos requeridos por favor!");
                    return;
                }

                // Validar rangos de valores
                const validacion = validarTodosSignosVitales(datosFormulario);
                if (!validacion.valido) {
                    mostrarErroresValidacion(validacion.errores);
                    return;
                }

                console.log('📤 Enviando datos de actualización:', {
                    id: $('#id').val(),
                    ...datosFormulario
                });

                // Envío AJAX para actualización
                $.ajax({
                    url: "update_userS.php",
                    type: "POST",
                    data: $(this).serialize(), // Mantener serialize para incluir todos los campos del form
                    dataType: 'json',
                    success: function(data) {
                        console.log('📥 Respuesta de actualización:', data);

                        if (data.status === 'true') {
                            // Éxito: cerrar modal, limpiar formulario y recargar tabla
                            $('#exampleModalS').modal('hide');
                            $('#updateUserS')[0].reset();
                            $('#exampleS').DataTable().draw();

                            // Actualizar últimos valores inmediatamente
                            setTimeout(() => {
                                cargarUltimosValoresSignos();
                            }, 500);

                            // Mostrar notificación de éxito mejorada
                            alertify.success("✅ Registro actualizado correctamente");
                        } else {
                            // Error del servidor
                            console.error('❌ Error del servidor:', data);
                            alertify.error(data.message || "Error al editar el registro");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('❌ Error AJAX en actualización:', {
                            xhr: xhr,
                            status: status,
                            error: error,
                            responseText: xhr.responseText
                        });
                        alertify.error("Error de conexión al servidor: " + error);
                    }
                });
            });

            /**
             * Manejo de eliminación de Signos Vitales con confirmación mejorada
             */
            $('#exampleS').on('click', '.deleteBtnS', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                const $btn = $(this);

                // Mostrar confirmación moderna
                alertify.confirm('Confirmar Eliminación',
                    '¿Estás seguro de eliminar este registro de signos vitales? Esta acción no se puede deshacer.',
                    function() {
                        // Usuario confirmó - proceder con eliminación
                        console.log('🗑️ Eliminando registro ID:', id);

                        // Agregar estado de carga al botón
                        $btn.addClass('loading').prop('disabled', true);
                        $btn.find('span').text('Eliminando...');

                        $.ajax({
                            url: "delete_userS.php",
                            data: {
                                id: id,
                                csrf_token: '<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>'
                            },
                            type: "POST",
                            dataType: 'json',
                            success: function(data) {
                                console.log('📥 Respuesta de eliminación:', data);

                                if (data.status === 'success' || data.status === 'true') {
                                    // Recargar la tabla
                                    $('#exampleS').DataTable().draw();

                                    // Actualizar últimos valores
                                    setTimeout(() => {
                                        cargarUltimosValoresSignos();
                                    }, 500);

                                    alertify.success("✅ Registro eliminado correctamente");
                                } else {
                                    console.error('❌ Error al eliminar:', data);
                                    alertify.error(data.message || "Error al eliminar el registro");
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('❌ Error AJAX en eliminación:', {
                                    xhr: xhr,
                                    status: status,
                                    error: error,
                                    responseText: xhr.responseText
                                });

                                // Intento de fallback parseando texto
                                try {
                                    const json = JSON.parse(xhr.responseText);
                                    if (json.status === 'success') {
                                        $('#exampleS').DataTable().draw();
                                        setTimeout(() => {
                                            cargarUltimosValoresSignos();
                                        }, 500);
                                        alertify.success("✅ Registro eliminado correctamente");
                                    } else {
                                        alertify.error("Error al eliminar el registro");
                                    }
                                } catch (e) {
                                    alertify.error("Error de conexión al servidor: " + error);
                                }
                            },
                            complete: function() {
                                // Remover estado de carga
                                $btn.removeClass('loading').prop('disabled', false);
                                $btn.find('span').text('Eliminar');
                            }
                        });
                    },
                    function() {
                        // Usuario canceló
                        console.log('❌ Eliminación cancelada por el usuario');
                    }
                ).set({
                    labels: {
                        ok: 'Eliminar',
                        cancel: 'Cancelar'
                    },
                    title: 'Confirmar Eliminación'
                });
            });

            /**
             * Funcionalidad de búsqueda para Signos Vitales
             */
            $('#search_nuevoS').on('keyup', function() {
                $('#exampleS').DataTable().search(this.value).draw();
            });

            /**
             * Limpieza automática de formularios al cerrar modales
             */
            // Limpiar formulario al cerrar modal de edición
            $('#exampleModalS').on('hidden.bs.modal', function() {
                $('#updateUserS')[0].reset();
                $('#id').val('');
                $('#trid').val('');
                $('#fecha_gField').val('');
                // Limpiar alertas de hora original
                $('#horaOriginalAlert').remove();
                console.log('🧹 Formulario de edición limpiado');
            });

            // Limpiar formulario al cerrar modal de agregar
            $('#addUserModalS').on('hidden.bs.modal', function() {
                $('#addUserS')[0].reset();
                console.log('🧹 Formulario de agregar limpiado');
            });

            // ========== FIN FUNCIONALIDAD DE SIGNOS VITALES ==========
        });
    </script>

    <script>
        // Equipos
        let selectedServices = [];

        // Load registered services on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOMContentLoaded: Attempting to load registered services');
            loadRegisteredServices();
        });

        function addService() {
            const select = document.getElementById('serviceSelect');
            const selectedOption = select.options[select.selectedIndex];
            if (selectedOption.value) {
                const service = {
                    id: selectedOption.value,
                    desc: selectedOption.getAttribute('data-desc'),
                    cost: parseFloat(selectedOption.getAttribute('data-cost'))
                };

                // Avoid duplicates
                if (!selectedServices.some(s => s.id === service.id)) {
                    selectedServices.push(service);
                    updateTable();
                } else {
                    alertify.warning('Este equipo ya está seleccionado.');
                }
                select.value = ''; // Reset dropdown
            }
        }

        function updateTable() {
            const tbody = document.getElementById('selectedServicesBody');
            tbody.innerHTML = '';
            selectedServices.forEach((service, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
            <td>${service.desc}</td>
            <td>$${service.cost.toFixed(2)}</td>
            <td>
                <button class="btn btn-sm btn-danger" onclick="deleteServices(${index})">Eliminar</button>
            </td>
        `;
                tbody.appendChild(row);
            });
        }

        function deleteServices(index) {
            if (confirm('¿Está seguro de eliminar este equipo?')) {
                selectedServices.splice(index, 1);
                updateTable();
            }
        }

        function submitServices() {
            if (selectedServices.length === 0) {
                alertify.warning('Por favor, seleccione al menos un equipo.');
                return;
            }

            const formData = new FormData();
            formData.append('id_usua', <?php echo json_encode($id_usuario); ?>);
            formData.append('id_atencion', <?php echo json_encode($id_atencion); ?>);
            formData.append('csrf_token', '<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>');
            selectedServices.forEach((service) => {
                formData.append(`services[${service.id}]`, service.cost);
            });

            // Log FormData for debugging
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }

            fetch('process_services.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('Submit Services Response Status:', response.status);
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(`HTTP error! Status: ${response.status}, Response: ${text}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Submit Services Response Data:', data);
                    if (data.success) {
                        alertify.success(data.message);
                        selectedServices = [];
                        updateTable();
                        loadRegisteredServices(); // Refresh registered services table
                    } else {
                        alertify.error(`Error al registrar equipos: ${data.message}`);
                    }
                })
                .catch(error => {
                    console.error('Submit Services Fetch Error:', error);
                    alertify.error('Error al registrar equipos: ' + error.message);
                });
        }

        function loadRegisteredServices() {
            console.log('Loading registered services...');
            fetch('services_handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'get',
                        id_usua: <?php echo json_encode($_SESSION['login']['id_usua'] ?? 0); ?>,
                        id_atencion: <?php echo json_encode($_SESSION['pac'] ?? 0); ?>,
                        csrf_token: '<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>'
                    })
                })
                .then(response => {
                    console.log('Load Services Response Status:', response.status);
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(`HTTP error! Status: ${response.status}, Response: ${text}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Load Services Response Data:', data);
                    const tbody = document.getElementById('registeredServicesBody');
                    tbody.innerHTML = '';
                    if (data.success && data.services && data.services.length > 0) {
                        data.services.forEach(service => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                    <td>${service.serv_desc}</td>
                    <td>$${parseFloat(service.cta_tot).toFixed(2)}</td>
                    <td>${service.cta_fec}</td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="deleteService(${service.id_ctapac})">Eliminar</button>
                    </td>
                `;
                            tbody.appendChild(row);
                        });
                    } else {
                        tbody.innerHTML = '<tr><td colspan="4">No hay servicios registrados.</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Load Services Fetch Error:', error);
                    document.getElementById('registeredServicesBody').innerHTML = '<tr><td colspan="4">Error al cargar servicios: ' + error.message + '</td></tr>';
                });
        }

        // Manejar selección de tratamientos en pestaña Nota de Enfermería
        $(document).ready(function() {
            // Función común para manejar tratamientos
            function manejarTratamientos(contexto) {
                var tratamientosSeleccionados = $('.tratamiento-checkbox:checked');

                // Elementos específicos del contexto (nota)
                var formularioContenedor = $('#formulario_contenedor_nota');
                var formularioGeneral = $('#formulario_general_nota');
                var tituloTratamientos = $('#titulo_tratamientos_dinamico_nota');
                var camposLasik = $('#campos_lasik_nota');
                var inputTratamientos = $('#tratamientos_seleccionados_input_nota');

                if (tratamientosSeleccionados.length > 0) {
                    // Obtener nombres de tratamientos seleccionados
                    var nombresTratamientos = [];
                    tratamientosSeleccionados.each(function() {
                        var nombreTratamiento = $(this).data('tipo');
                        nombresTratamientos.push(nombreTratamiento.toUpperCase());
                    });

                    // Actualizar título dinámicamente
                    var tituloCompleto = 'FORMULARIO DE TRATAMIENTOS SELECCIONADOS<br><span style="color: #4a4ed1; font-weight: bold; font-size: 16px;">' + nombresTratamientos.join(' - ') + '</span>';
                    tituloTratamientos.html(tituloCompleto);

                    // Mostrar formulario inmediatamente
                    formularioContenedor.show();
                    formularioGeneral.show();

                    // Verificar si hay tratamientos LASIK seleccionados
                    var hayLasik = false;
                    tratamientosSeleccionados.each(function() {
                        if ($(this).hasClass('lasik-checkbox')) {
                            hayLasik = true;
                            return false;
                        }
                    });

                    // Mostrar/ocultar campos específicos de LASIK
                    if (hayLasik) {
                        camposLasik.show();
                    } else {
                        camposLasik.hide();
                    }

                    // Actualizar input hidden con tratamientos seleccionados
                    var tratamientosIds = [];
                    tratamientosSeleccionados.each(function() {
                        tratamientosIds.push($(this).val());
                    });
                    inputTratamientos.val(tratamientosIds.join(','));

                } else {
                    // Restaurar título original y ocultar formulario
                    tituloTratamientos.html('FORMULARIO DE TRATAMIENTOS SELECCIONADOS');
                    formularioContenedor.hide();
                    formularioGeneral.hide();
                    camposLasik.hide();
                }
            }

            // Event listener para checkboxes de tratamiento en pestaña nota
            $(document).on('change', '.tratamiento-checkbox', function() {
                manejarTratamientos('nota');
            });
        });

        // Configuración mejorada de Alertify
        $(document).ready(function() {
            // Configurar Alertify con opciones mejoradas
            alertify.set('notifier', 'position', 'top-right');
            alertify.set('notifier', 'delay', 5);

            // Configurar diálogos
            alertify.defaults.theme.ok = "btn btn-primary";
            alertify.defaults.theme.cancel = "btn btn-secondary";
            alertify.defaults.theme.input = "form-control";

            // Personalizar textos
            alertify.defaults.glossary.title = 'Sistema de Signos Vitales';
            alertify.defaults.glossary.ok = 'Aceptar';
            alertify.defaults.glossary.cancel = 'Cancelar';

            console.log('🎨 Sistema de notificaciones mejoradas inicializado');
        });

        // Funcionalidad para signos vitales múltiples
        let contadorFilasSignos = 1; // Solo tenemos 1 fila por defecto

        // Agregar nueva fila de signos vitales
        $('#agregar-signos-adicionales').on('click', function() {
            contadorFilasSignos++;
            const nuevaFila = `
                <tr class="fila-signos-vitales">
                    <td><strong>Signos Vitales</strong><br><small class="text-muted">registro actual #${contadorFilasSignos}</small></td>
                    <td><input type="text" class="form-control signos-input" name="sistg[]" placeholder="ej: 120"></td>
                    <td><input type="text" class="form-control signos-input" name="diastg[]" placeholder="ej: 80"></td>
                    <td><input type="text" class="form-control signos-input" name="fcardg[]" placeholder="ej: 75"></td>
                    <td><input type="text" class="form-control signos-input" name="frespg[]" placeholder="ej: 20"></td>
                    <td><input type="text" class="form-control signos-input" name="satg[]" placeholder="ej: 98%"></td>
                    <td><input type="text" class="form-control signos-input" name="tempg[]" placeholder="ej: 36.5"></td>
                    <td><input type="time" class="form-control signos-input" name="hora_signos[]"></td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm guardar-signos" data-fila="${contadorFilasSignos - 1}">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <button type="button" class="btn btn-danger btn-sm ml-1 eliminar-fila">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#signos-vitales-tbody').append(nuevaFila);
        });

        // Eliminar fila de signos vitales
        $(document).on('click', '.eliminar-fila', function() {
            if (confirm('¿Estás seguro de eliminar esta fila?')) {
                $(this).closest('tr').remove();
            }
        });

        // Función para cargar signos vitales existentes
        function cargarSignosVitalesExistentes() {
            if (signosVitalesCargados) {
                console.log('Signos vitales ya cargados, omitiendo recarga...');
                return;
            }

            console.log('Cargando signos vitales existentes...');

            fetch(`obtener_signos_vitales.php?id_atencion=<?php echo $id_atencion; ?>`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Respuesta de signos vitales:', data);

                    if (data.success && data.data && data.data.length > 0) {
                        // Limpiar tabla actual (mantener solo la fila base)
                        $('#signos-vitales-tbody tr.fila-signos-existente').remove();

                        // Agregar signos vitales existentes
                        data.data.forEach((signo, index) => {
                            contadorFilasSignos++;
                            const filaExistente = `
                            <tr class="fila-signos-vitales fila-signos-existente" data-id-trans-graf="${signo.id_trans_graf}">
                                <td><strong>Registro Guardado</strong><br><small class="text-muted">${signo.fecha} - ${signo.tratamientos}</small></td>
                                <td><input type="text" class="form-control signos-input bg-light" name="sistg[]" value="${signo.sistg}" readonly></td>
                                <td><input type="text" class="form-control signos-input bg-light" name="diastg[]" value="${signo.diastg}" readonly></td>
                                <td><input type="text" class="form-control signos-input bg-light" name="fcardg[]" value="${signo.fcardg}" readonly></td>
                                <td><input type="text" class="form-control signos-input bg-light" name="frespg[]" value="${signo.frespg}" readonly></td>
                                <td><input type="text" class="form-control signos-input bg-light" name="satg[]" value="${signo.satg}" readonly></td>
                                <td><input type="text" class="form-control signos-input bg-light" name="tempg[]" value="${signo.tempg}" readonly></td>
                                <td><input type="time" class="form-control signos-input bg-light" name="hora_signos[]" value="${signo.hora}" readonly></td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm editar-signos" data-id-trans-graf="${signo.id_trans_graf}">
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                    <button type="button" class="btn btn-primary btn-sm guardar-signos" data-fila="${contadorFilasSignos - 1}" data-id-trans-graf="${signo.id_trans_graf}" style="display: none;">
                                        <i class="fas fa-save"></i> Actualizar
                                    </button>
                                </td>
                            </tr>
                        `;
                            $('#signos-vitales-tbody').append(filaExistente);
                        });

                        alertify.success(`📊 Se cargaron ${data.data.length} registro(s) de signos vitales existentes.`);
                        signosVitalesCargados = true;
                    } else {
                        console.log('No hay signos vitales guardados para hoy');
                        alertify.message('ℹ️ No hay signos vitales registrados para el día de hoy.');
                    }
                })
                .catch(error => {
                    console.error('Error al cargar signos vitales:', error);
                    alertify.error('❌ Error al cargar signos vitales existentes: ' + error.message);
                });
        }

        // Editar signos vitales guardados
        $(document).on('click', '.editar-signos', function() {
            const fila = $(this).closest('tr');
            const inputs = fila.find('input');
            const btnEditar = $(this);
            const btnGuardar = fila.find('.guardar-signos');

            // Habilitar inputs para edición
            inputs.prop('readonly', false).removeClass('bg-light');

            // Ocultar botón editar y mostrar botón guardar
            btnEditar.hide();
            btnGuardar.show().removeClass('btn-success').addClass('btn-primary');
            btnGuardar.html('<i class="fas fa-save"></i> Actualizar');

            alertify.success('Modo de edición activado. Modifique los valores y presione "Actualizar".');
        });

        // Guardar signos vitales individuales
        $(document).on('click', '.guardar-signos', function() {
            const fila = $(this).closest('tr');
            const inputs = fila.find('input');
            const btn = $(this);
            const btnEditar = fila.find('.editar-signos');

            // Verificar si estamos en modo de actualización - mejora de detección
            const esActualizacion = btn.html().includes('Actualizar') ||
                fila.hasClass('fila-signos-existente') ||
                btn.data('id-trans-graf');
            const idTransGraf = btn.data('id-trans-graf') || fila.data('id-trans-graf');

            console.log('Modo de operación:', esActualizacion ? 'ACTUALIZACIÓN' : 'NUEVO REGISTRO');
            console.log('ID Trans Graf:', idTransGraf);
            console.log('Es fila existente:', fila.hasClass('fila-signos-existente'));

            // Validar que todos los campos estén llenos
            let todosLlenos = true;
            inputs.each(function() {
                if ($(this).val().trim() === '') {
                    todosLlenos = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            if (!todosLlenos) {
                alertify.warning('Por favor, complete todos los campos de signos vitales.');
                return;
            }

            // Validar rangos normales
            const sistolica = parseInt(fila.find('input[name="sistg[]"]').val());
            const diastolica = parseInt(fila.find('input[name="diastg[]"]').val());
            const frecCardiaca = parseInt(fila.find('input[name="fcardg[]"]').val());
            const frecRespiratoria = parseInt(fila.find('input[name="frespg[]"]').val());
            const saturacion = parseInt(fila.find('input[name="satg[]"]').val().replace('%', ''));
            const temperatura = parseFloat(fila.find('input[name="tempg[]"]').val());

            if (sistolica < 50 || sistolica > 250) {
                alertify.error('La presión sistólica debe estar entre 50 y 250 mmHg');
                return;
            }
            if (diastolica < 30 || diastolica > 150) {
                alertify.error('La presión diastólica debe estar entre 30 y 150 mmHg');
                return;
            }
            if (frecCardiaca < 30 || frecCardiaca > 250) {
                alertify.error('La frecuencia cardíaca debe estar entre 30 y 250 lpm');
                return;
            }
            if (frecRespiratoria < 8 || frecRespiratoria > 50) {
                alertify.error('La frecuencia respiratoria debe estar entre 8 y 50 rpm');
                return;
            }
            if (saturacion < 50 || saturacion > 100) {
                alertify.error('La saturación de oxígeno debe estar entre 50% y 100%');
                return;
            }
            if (temperatura < 34 || temperatura > 44) {
                alertify.error('La temperatura debe estar entre 34°C y 44°C');
                return;
            }

            // Cambiar estado del botón
            btn.prop('disabled', true);
            btn.html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

            // Preparar datos para envío
            const formData = new FormData();
            formData.append('sistg', sistolica);
            formData.append('diastg', diastolica);
            formData.append('fcardg', frecCardiaca);
            formData.append('frespg', frecRespiratoria);
            formData.append('satg', saturacion);
            formData.append('tempg', temperatura);
            formData.append('hora_signos', fila.find('input[name="hora_signos[]"]').val());
            formData.append('id_usua', '<?php echo $id_usuario; ?>');
            formData.append('id_atencion', '<?php echo $id_atencion; ?>');

            // Agregar ID del registro si es una actualización
            if (esActualizacion && idTransGraf) {
                formData.append('id_trans_graf', idTransGraf);
                formData.append('es_actualizacion', '1');
            }

            // Obtener tratamientos seleccionados
            const tratamientosSeleccionados = $('.tratamiento-checkbox:checked');
            let tratamientosIds = [];
            tratamientosSeleccionados.each(function() {
                tratamientosIds.push($(this).val());
            });

            // Si no hay tratamientos seleccionados, usar ID por defecto
            if (tratamientosIds.length === 0) {
                tratamientosIds = ['1']; // ID por defecto para signos vitales
            }

            formData.append('tratamientos_ids', tratamientosIds.join(','));
            formData.append('csrf_token', '<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>');

            // Debug: Log what we're sending
            console.log('Enviando datos:', {
                modo: esActualizacion ? 'ACTUALIZACIÓN' : 'NUEVO',
                id_trans_graf: idTransGraf,
                es_actualizacion: esActualizacion ? '1' : '0',
                sistg: sistolica,
                diastg: diastolica,
                fcardg: frecCardiaca,
                frespg: frecRespiratoria,
                satg: saturacion,
                tempg: temperatura,
                hora_signos: fila.find('input[name="hora_signos[]"]').val(),
                tratamientos_ids: tratamientosIds.join(','),
                button_text: btn.html(),
                id_atencion: '<?php echo $id_atencion; ?>',
                id_usua: '<?php echo $id_usuario; ?>'
            });

            // Enviar por AJAX
            fetch('insertar_signos_vitales.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Respuesta del servidor:', data);

                    if (data.success) {
                        btn.removeClass('btn-primary').addClass('btn-success');
                        btn.html('<i class="fas fa-check"></i> Guardado');
                        btn.hide(); // Ocultar botón guardar

                        // Crear y mostrar botón editar
                        if (btnEditar.length === 0) {
                            const botonEditar = $('<button type="button" class="btn btn-warning btn-sm ml-1 editar-signos"><i class="fas fa-edit"></i> Editar</button>');
                            btn.parent().append(botonEditar);
                        } else {
                            btnEditar.show();
                        }

                        // Deshabilitar inputs de la fila
                        inputs.prop('readonly', true).addClass('bg-light');

                        // Mensajes específicos según el tipo de operación
                        let mensaje = data.message;
                        if (data.details) {
                            mensaje += ' ' + data.details;
                        }

                        if (data.registros_actualizados > 0) {
                            alertify.success(`🔄 ${mensaje}`);
                            console.log(`✅ Registros actualizados: ${data.registros_actualizados}`);
                        } else if (data.registros_insertados > 0) {
                            alertify.success(`💾 ${mensaje}`);
                            console.log(`✅ Registros insertados: ${data.registros_insertados}`);
                        } else {
                            alertify.success(`✅ ${mensaje}`);
                        }

                        // Log adicional para debugging
                        if (data.summary) {
                            console.log('Resumen de la operación:', data.summary);
                        }

                        // Preguntar si quiere ir a nota de registro gráfico
                        setTimeout(() => {
                            alertify.confirm('Signos Vitales Guardados',
                                `✅ Los signos vitales se han guardado correctamente.<br><br>¿Desea ir a la página de Nota de Registro Gráfico?`,
                                function() {
                                    // Sí, ir a la página
                                    window.open('nota_registro_grafico.php', '_blank');
                                },
                                function() {
                                    // No, permanecer en la página actual
                                    console.log('Usuario decidió permanecer en la página actual');
                                }
                            );
                        }, 1000);

                    } else {
                        throw new Error(data.message || 'Error desconocido');
                    }
                })
                .catch(error => {
                    console.error('Error completo:', error);
                    console.error('Modo era:', esActualizacion ? 'ACTUALIZACIÓN' : 'NUEVO REGISTRO');

                    // Mostrar mensaje de error más informativo
                    let mensajeError = 'Error al ' + (esActualizacion ? 'actualizar' : 'guardar') + ' signos vitales';

                    // Si hay información adicional en el error, mostrarla
                    if (error.message) {
                        mensajeError += ': ' + error.message;
                    }

                    alertify.error(mensajeError);

                    // Restaurar estado del botón
                    btn.prop('disabled', false);
                    if (esActualizacion) {
                        btn.html('<i class="fas fa-save"></i> Actualizar');
                    } else {
                        btn.html('<i class="fas fa-save"></i> Guardar');
                    }
                });
        });

        // Guardar todos los signos vitales de una vez
        $(document).on('click', '#guardar-todos-signos', function() {
            const btn = $(this);
            const todasLasFilas = $('#signos-vitales-tbody tr.fila-signos-vitales');
            let signosParaGuardar = [];
            let erroresValidacion = [];

            console.log('🔄 Iniciando validación de todos los signos vitales...');

            // Validar todas las filas y recopilar datos
            todasLasFilas.each(function(index) {
                const fila = $(this);
                const sistg = fila.find('input[name="sistg[]"]').val();
                const diastg = fila.find('input[name="diastg[]"]').val();
                const fcardg = fila.find('input[name="fcardg[]"]').val();
                const frespg = fila.find('input[name="frespg[]"]').val();
                const satg = fila.find('input[name="satg[]"]').val();
                const tempg = fila.find('input[name="tempg[]"]').val();
                const hora_signos = fila.find('input[name="hora_signos[]"]').val();

                // Verificar si la fila tiene datos
                const tieneAlgunDato = sistg || diastg || fcardg || frespg || satg || tempg || hora_signos;

                if (tieneAlgunDato) {
                    // Validar que todos los campos estén completos
                    if (!sistg || !diastg || !fcardg || !frespg || !satg || !tempg || !hora_signos) {
                        erroresValidacion.push(`Fila ${index + 1}: Todos los campos son obligatorios`);
                        fila.find('input').each(function() {
                            if (!$(this).val()) {
                                $(this).addClass('is-invalid');
                            } else {
                                $(this).removeClass('is-invalid');
                            }
                        });
                    } else {
                        // Validar rangos
                        const sistolica = parseInt(sistg);
                        const diastolica = parseInt(diastg);
                        const frecCardiaca = parseInt(fcardg);
                        const frecRespiratoria = parseInt(frespg);
                        const saturacion = parseInt(satg.replace('%', ''));
                        const temperatura = parseFloat(tempg);

                        if (sistolica < 50 || sistolica > 250) {
                            erroresValidacion.push(`Fila ${index + 1}: Presión sistólica debe estar entre 50 y 250 mmHg`);
                        }
                        if (diastolica < 30 || diastolica > 150) {
                            erroresValidacion.push(`Fila ${index + 1}: Presión diastólica debe estar entre 30 y 150 mmHg`);
                        }
                        if (frecCardiaca < 30 || frecCardiaca > 250) {
                            erroresValidacion.push(`Fila ${index + 1}: Frecuencia cardíaca debe estar entre 30 y 250 lpm`);
                        }
                        if (frecRespiratoria < 8 || frecRespiratoria > 50) {
                            erroresValidacion.push(`Fila ${index + 1}: Frecuencia respiratoria debe estar entre 8 y 50 rpm`);
                        }
                        if (saturacion < 50 || saturacion > 100) {
                            erroresValidacion.push(`Fila ${index + 1}: Saturación debe estar entre 50% y 100%`);
                        }
                        if (temperatura < 34 || temperatura > 44) {
                            erroresValidacion.push(`Fila ${index + 1}: Temperatura debe estar entre 34°C y 44°C`);
                        }

                        if (erroresValidacion.length === 0 || !erroresValidacion.some(error => error.includes(`Fila ${index + 1}`))) {
                            // Solo agregar si no hay errores para esta fila
                            signosParaGuardar.push({
                                sistg: sistolica,
                                diastg: diastolica,
                                fcardg: frecCardiaca,
                                frespg: frecRespiratoria,
                                satg: saturacion,
                                tempg: temperatura,
                                hora_signos: hora_signos,
                                fila_index: index,
                                es_actualizacion: fila.hasClass('fila-signos-existente'),
                                id_trans_graf: fila.data('id-trans-graf') || null
                            });

                            // Remover clases de error
                            fila.find('input').removeClass('is-invalid');
                        }
                    }
                }
            });

            console.log(`📊 Signos para guardar: ${signosParaGuardar.length}`);
            console.log(`⚠️ Errores de validación: ${erroresValidacion.length}`);

            if (erroresValidacion.length > 0) {
                alertify.alert('Errores de Validación',
                    '⚠️ Se encontraron los siguientes errores:<br><br>• ' + erroresValidacion.join('<br>• '),
                    function() {
                        // Focus en el primer campo con error
                        $('#signos-vitales-tbody .is-invalid').first().focus();
                    }
                );
                return;
            }

            if (signosParaGuardar.length === 0) {
                alertify.warning('📝 No hay signos vitales para guardar. Complete al menos una fila con todos los datos.');
                return;
            }

            // Confirmar antes de proceder
            alertify.confirm('Guardar Múltiples Registros',
                `🔄 ¿Está seguro de guardar ${signosParaGuardar.length} registro(s) de signos vitales?<br><br>Esta operación puede tardar unos momentos...`,
                function() {
                    procesarSignosMultiples(signosParaGuardar, btn);
                },
                function() {
                    alertify.message('❌ Operación cancelada.');
                }
            );
        });

        // Función para procesar múltiples signos vitales
        function procesarSignosMultiples(signosParaGuardar, btn) {
            const totalSignos = signosParaGuardar.length;

            // Cambiar estado del botón
            btn.prop('disabled', true);
            btn.html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

            console.log(`🚀 Iniciando procesamiento de ${totalSignos} signos vitales...`);

            // Obtener tratamientos seleccionados
            const tratamientosSeleccionados = $('.tratamiento-checkbox:checked');
            let tratamientosIds = [];
            tratamientosSeleccionados.each(function() {
                tratamientosIds.push($(this).val());
            });

            // Si no hay tratamientos seleccionados, usar ID por defecto
            if (tratamientosIds.length === 0) {
                tratamientosIds = ['1']; // ID por defecto para signos vitales
            }

            // Preparar datos para envío múltiple
            const formData = new FormData();
            formData.append('id_usua', '<?php echo $id_usuario; ?>');
            formData.append('id_atencion', '<?php echo $id_atencion; ?>');
            formData.append('tratamientos_ids', tratamientosIds.join(','));
            formData.append('csrf_token', '<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>');

            // Agregar cada signo vital como array estructurado
            signosParaGuardar.forEach((signo, index) => {
                formData.append(`signos_vitales[${index}][sistg]`, signo.sistg);
                formData.append(`signos_vitales[${index}][diastg]`, signo.diastg);
                formData.append(`signos_vitales[${index}][fcardg]`, signo.fcardg);
                formData.append(`signos_vitales[${index}][frespg]`, signo.frespg);
                formData.append(`signos_vitales[${index}][satg]`, signo.satg);
                formData.append(`signos_vitales[${index}][tempg]`, signo.tempg);
                formData.append(`signos_vitales[${index}][hora_signos]`, signo.hora_signos);
            });

            // Enviar todo de una vez
            fetch('guardar_signos_multiples.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log('📊 Respuesta del procesamiento múltiple:', data);

                    if (data.success) {
                        // Marcar todas las filas como guardadas
                        signosParaGuardar.forEach(signo => {
                            const fila = $(`#signos-vitales-tbody tr.fila-signos-vitales:eq(${signo.fila_index})`);
                            fila.find('.guardar-signos').hide();
                            fila.find('input').prop('readonly', true).addClass('bg-light');

                            // Agregar botón de editar si no existe
                            if (fila.find('.editar-signos').length === 0) {
                                const botonEditar = $('<button type="button" class="btn btn-warning btn-sm ml-1 editar-signos"><i class="fas fa-edit"></i> Editar</button>');
                                fila.find('td:last').append(botonEditar);
                            }
                        });

                        // Mensaje de éxito detallado
                        let mensajeCompleto = data.message;
                        if (data.details) {
                            mensajeCompleto += `<br><small>${data.details}</small>`;
                        }

                        alertify.success(`🎉 ${mensajeCompleto}`);

                        // Log detallado
                        console.log(`✅ Procesamiento exitoso:`);
                        console.log(`   - Total procesados: ${data.registros_procesados}`);
                        console.log(`   - Exitosos: ${data.registros_exitosos}`);
                        console.log(`   - Actualizados: ${data.registros_actualizados}`);
                        console.log(`   - Insertados: ${data.registros_insertados}`);

                        // Preguntar si quiere ir a nota de registro gráfico
                        alertify.confirm('Signos Vitales Guardados',
                            `🎉 Se han guardado exitosamente ${data.registros_procesados} registro(s) de signos vitales.<br><br>¿Desea ir a la página de Nota de Registro Gráfico?`,
                            function() {
                                // Sí, ir a la página
                                window.open('http://127.0.0.1:8000/enfermera/registro_procedimientos/nota_registro_grafico.php', '_blank');
                                // También recargar después de abrir la nueva página
                                setTimeout(() => {
                                    if (typeof cargarSignosVitalesExistentes === 'function') {
                                        cargarSignosVitalesExistentes();
                                    }
                                }, 1000);
                            },
                            function() {
                                // No, solo recargar
                                setTimeout(() => {
                                    if (typeof cargarSignosVitalesExistentes === 'function') {
                                        cargarSignosVitalesExistentes();
                                    }
                                }, 1000);
                            }
                        );

                    } else {
                        throw new Error(data.message || 'Error desconocido en el procesamiento múltiple');
                    }
                })
                .catch(error => {
                    console.error('❌ Error en procesamiento múltiple:', error);
                    alertify.alert('Error de Procesamiento',
                        `❌ Error al guardar los signos vitales:<br><br>${error.message}`,
                        function() {
                            // Permitir al usuario intentar nuevamente
                            console.log('🔄 Usuario puede intentar nuevamente');
                        }
                    );
                })
                .finally(() => {
                    // Restaurar estado del botón
                    btn.prop('disabled', false);
                    btn.html('<i class="fas fa-save"></i> Guardar todos los signos vitales');
                });
        }

        function deleteService(id_ctapac) {
            if (confirm('¿Estás seguro de eliminar este equipo?')) {
                fetch('services_handler.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            action: 'delete',
                            id_ctapac: id_ctapac,
                            csrf_token: '<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>'
                        })
                    })
                    .then(response => {
                        console.log('Delete Service Response Status:', response.status);
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(`HTTP error! Status: ${response.status}, Response: ${text}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Delete Service Response Data:', data);
                        if (data.success) {
                            alertify.success('Equipo eliminado correctamente.');
                            loadRegisteredServices();
                        } else {
                            alertify.error('Error al eliminar equipo: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Delete Service Fetch Error:', error);
                        alertify.error('Error al eliminar equipo: ' + error.message);
                    });
            }
        }

        // Enviar formulario completo
        $(document).on('click', '#enviar-formulario-completo', function() {
            const btn = $(this);

            // Validar que haya tratamientos seleccionados
            const tratamientosSeleccionados = $('.tratamiento-checkbox:checked');
            if (tratamientosSeleccionados.length === 0) {
                alertify.warning('⚠️ Por favor, seleccione al menos un tratamiento para proceder.');
                return;
            }

            // Validar que haya datos del formulario de tratamientos
            const medicoTratante = $('select[name="medico_tratante"]').val();
            const anestesiologo = $('select[name="anestesiologo"]').val();
            const anestesia = $('select[name="anestesia"]').val();

            if (!medicoTratante || !anestesiologo || !anestesia) {
                alertify.warning('📝 Por favor, complete todos los campos del formulario de tratamientos (médico tratante, anestesiólogo, anestesia).');
                return;
            }

            // Recopilar signos vitales de todas las filas
            const signosVitales = [];
            $('#signos-vitales-tbody tr.fila-signos-vitales').each(function() {
                const fila = $(this);
                const sistg = fila.find('input[name="sistg[]"]').val();
                const diastg = fila.find('input[name="diastg[]"]').val();
                const fcardg = fila.find('input[name="fcardg[]"]').val();
                const frespg = fila.find('input[name="frespg[]"]').val();
                const satg = fila.find('input[name="satg[]"]').val();
                const tempg = fila.find('input[name="tempg[]"]').val();
                const hora_signos = fila.find('input[name="hora_signos[]"]').val();

                // Solo agregar si todos los campos están llenos
                if (sistg && diastg && fcardg && frespg && satg && tempg && hora_signos) {
                    signosVitales.push({
                        sistg: sistg,
                        diastg: diastg,
                        fcardg: fcardg,
                        frespg: frespg,
                        satg: satg,
                        tempg: tempg,
                        hora_signos: hora_signos
                    });
                }
            });

            if (signosVitales.length === 0) {
                // Si no hay signos vitales en el formulario, verificar si el usuario quiere usar los guardados previamente
                alertify.confirm('📊 Signos Vitales',
                    'No hay signos vitales completados en el formulario actual.<br><br>¿Desea enviar el formulario utilizando los signos vitales guardados previamente?',
                    function() {
                        // Usuario acepta usar signos vitales previos
                        procederConEnvio();
                    },
                    function() {
                        // Usuario cancela
                        alertify.message('📝 Por favor, complete los signos vitales antes de continuar.');
                    }
                );
                return;
            }

            procederConEnvio();

            function procederConEnvio() {
                // Confirmar envío
                let mensajeConfirmacion;
                if (signosVitales.length > 0) {
                    mensajeConfirmacion = '📤 ¿Está seguro de enviar el formulario completo?<br><br>Se procesarán <strong>' + (signosVitales.length * tratamientosSeleccionados.length) + ' registros</strong><br>(' + signosVitales.length + ' signos vitales × ' + tratamientosSeleccionados.length + ' tratamientos)';
                } else {
                    mensajeConfirmacion = '📤 ¿Está seguro de enviar el formulario completo?<br><br>Se utilizarán los signos vitales guardados previamente para los <strong>' + tratamientosSeleccionados.length + ' tratamiento(s)</strong> seleccionado(s).';
                }

                alertify.confirm('🚀 Confirmación de Envío', mensajeConfirmacion,
                    function() {
                        // Usuario confirma el envío
                        ejecutarEnvio();
                    },
                    function() {
                        // Usuario cancela
                        alertify.message('📋 Envío cancelado.');
                    }
                );
            }

            function ejecutarEnvio() {
                // Cambiar estado del botón
                btn.prop('disabled', true);
                btn.html('<i class="fas fa-spinner fa-spin"></i> Enviando...');

                // Preparar datos para envío
                const formData = new FormData();
                formData.append('id_usua', '<?php echo $id_usuario; ?>');
                formData.append('id_atencion', '<?php echo $id_atencion; ?>');
                formData.append('medico_tratante', medicoTratante);
                formData.append('anestesiologo', anestesiologo);
                formData.append('anestesia', anestesia);

                // Agregar tratamientos seleccionados
                let tratamientosIds = [];
                tratamientosSeleccionados.each(function() {
                    tratamientosIds.push($(this).val());
                });
                formData.append('tratamientos_seleccionados', tratamientosIds.join(','));

                // Agregar signos vitales solo si los hay en el formulario
                if (signosVitales.length > 0) {
                    signosVitales.forEach(function(signo, index) {
                        formData.append(`signos_vitales[${index}][sistg]`, signo.sistg);
                        formData.append(`signos_vitales[${index}][diastg]`, signo.diastg);
                        formData.append(`signos_vitales[${index}][fcardg]`, signo.fcardg);
                        formData.append(`signos_vitales[${index}][frespg]`, signo.frespg);
                        formData.append(`signos_vitales[${index}][satg]`, signo.satg);
                        formData.append(`signos_vitales[${index}][tempg]`, signo.tempg);
                        formData.append(`signos_vitales[${index}][hora_signos]`, signo.hora_signos);
                    });
                }
                // Si no hay signos vitales en el formulario, el backend buscará los guardados previamente

                // Agregar campos LASIK si están presentes
                if ($('#campos_lasik').is(':visible')) {
                    formData.append('od_queratometria', $('input[name="od_queratometria"]').val());
                    formData.append('od_microqueratomo', $('input[name="od_microqueratomo"]').val());
                    formData.append('od_anillo', $('input[name="od_anillo"]').val());
                    formData.append('od_tope', $('input[name="od_tope"]').val());
                    formData.append('oi_queratometria', $('input[name="oi_queratometria"]').val());
                    formData.append('oi_microqueratomo', $('input[name="oi_microqueratomo"]').val());
                    formData.append('oi_anillo', $('input[name="oi_anillo"]').val());
                    formData.append('oi_tope', $('input[name="oi_tope"]').val());
                }

                formData.append('csrf_token', '<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>');

                // Enviar por AJAX
                fetch('enviar_formulario_completo.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Respuesta completa del servidor:', data);

                        if (data.success) {
                            btn.removeClass('btn-success').addClass('btn-primary');
                            btn.html('<i class="fas fa-check"></i> Enviado Correctamente');

                            // Mostrar mensaje de éxito con detalles
                            let mensaje = data.message;
                            if (data.details) {
                                mensaje += '<br><small>' + data.details + '</small>';
                            }

                            alertify.alert('✅ Formulario Enviado', mensaje, function() {
                                // Preguntar sobre próximos pasos
                                alertify.confirm('📊 Opciones',
                                    '¿Qué desea hacer a continuación?<br><br>• <strong>Ver Gráficas:</strong> Consultar los signos vitales registrados<br>• <strong>Nuevo Registro:</strong> Recargar para registrar otro caso',
                                    function() {
                                        // Ir a ver gráficas
                                        window.location.href = 'ver_grafica.php';
                                    },
                                    function() {
                                        // Recargar página
                                        window.location.reload();
                                    }
                                ).set('labels', {
                                    ok: '📊 Ver Gráficas',
                                    cancel: '🔄 Nuevo Registro'
                                });
                            });

                            // Log detallado para debugging
                            if (data.summary) {
                                console.log('📊 Resumen de la operación:', data.summary);
                            }

                        } else {
                            throw new Error(data.message || 'Error desconocido');
                        }
                    })
                    .catch(error => {
                        console.error('❌ Error completo:', error);

                        let mensajeError = '❌ Error al enviar formulario';
                        if (error.message) {
                            mensajeError += ':<br>' + error.message;
                        }

                        alertify.alert('Error', mensajeError, function() {
                            btn.prop('disabled', false);
                            btn.html('<i class="fas fa-paper-plane"></i> Enviar Formulario Completo');
                        });
                    });
            }
        });

        // Manejo del formulario de nota de enfermería
        $('form[action="insertar_nota_enfermeria.php"]').on('submit', function(e) {
            console.log('🚀 Formulario de nota de enfermería enviado');
            e.preventDefault();

            const form = $(this);
            const btn = form.find('button[type="submit"]');
            const originalHtml = btn.html();

            console.log('📋 Datos del formulario:', {
                nota: $('textarea[name="nota_enfermeria"]').val(),
                enfermera: $('input[name="enfermera_responsable"]').val(),
                id_exp: $('input[name="id_exp"]').val(),
                id_usua: $('input[name="id_usua"]').val(),
                id_atencion: $('input[name="id_atencion"]').val()
            });

            // Validar campos requeridos
            let errores = [];

            if (!$('textarea[name="nota_enfermeria"]').val().trim()) {
                errores.push('Nota de enfermería es requerida');
            }

            console.log('⚠️ Errores encontrados:', errores);

            if (errores.length > 0) {
                alertify.alert('Campos Requeridos', '⚠️ ' + errores.join('<br>• '), function() {
                    // Focus en el primer campo con error
                    if (!$('textarea[name="nota_enfermeria"]').val().trim()) {
                        $('textarea[name="nota_enfermeria"]').focus();
                    }
                });
                return;
            }

            // Capturar datos de tratamientos del formulario principal antes de enviar
            capturarDatosTratamientos();

            // Deshabilitar botón y mostrar loading
            btn.prop('disabled', true);
            btn.html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

            console.log('🔄 Enviando formulario...');

            // Preparar datos del formulario
            const formData = new FormData(this);

            // Debug: Mostrar todos los datos que se envían
            console.log('📤 Datos a enviar:');
            for (let [key, value] of formData.entries()) {
                console.log(`  ${key}: ${value}`);
            }

            // Enviar datos por AJAX
            $.ajax({
                    url: 'insertar_nota_enfermeria.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    timeout: 30000
                })
                .done(function(response) {
                    console.log('✅ Respuesta recibida:', response);

                    if (response.success) {
                        // Mostrar notificación de éxito
                        alertify.success(response.message || '✅ Nota de enfermería guardada exitosamente');

                        // Limpiar formulario excepto campos que deben mantenerse
                        $('textarea[name="nota_enfermeria"]').val('');

                        // Opcional: mostrar detalles adicionales si están disponibles
                        if (response.data) {
                            console.log('📋 Datos guardados:', response.data);
                        }

                    } else {
                        console.error('❌ Error del servidor:', response);
                        // Mostrar error
                        alertify.alert('Error', response.message || '❌ Error al guardar nota de enfermería');
                    }
                })
                .fail(function(xhr, status, error) {
                    console.error('❌ Error AJAX completo:', {
                        xhr: xhr,
                        status: status,
                        error: error,
                        responseText: xhr.responseText,
                        responseJSON: xhr.responseJSON
                    });

                    let mensajeError = '❌ Error de conexión al guardar la nota de enfermería';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        mensajeError = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        console.error('Respuesta del servidor:', xhr.responseText);
                        mensajeError = '❌ Error del servidor: ' + xhr.responseText.substring(0, 200);
                    } else if (status === 'timeout') {
                        mensajeError = '⏱️ Tiempo de espera agotado. Verifique su conexión.';
                    } else if (status === 'error') {
                        mensajeError = '🔌 Error de conexión con el servidor.';
                    }

                    alertify.alert('Error de Conexión', mensajeError);
                })
                .always(function() {
                    // Rehabilitar botón
                    btn.prop('disabled', false);
                    btn.html(originalHtml);
                });
        });

        // Función para capturar datos de tratamientos del formulario principal
        function capturarDatosTratamientos() {
            console.log('📋 Capturando datos de tratamientos...');

            // Capturar tratamientos seleccionados
            const tratamientosSeleccionados = $('.tratamiento-checkbox:checked');
            let tratamientosIds = [];
            let nombresTratamientos = [];

            tratamientosSeleccionados.each(function() {
                tratamientosIds.push($(this).val());
                nombresTratamientos.push($(this).data('tipo'));
            });

            // Capturar datos del formulario de tratamientos (si está visible)
            const medicoTratante = $('select[name="medico_tratante"]').val() || 'Sin asignar';
            const anestesiologo = $('select[name="anestesiologo"]').val() || 'Sin asignar';
            const anestesia = $('select[name="anestesia"]').val() || 'Sin asignar';

            // Actualizar campos ocultos en el formulario de nota
            $('#tratamientos_seleccionados_nota').val(nombresTratamientos.join(', '));
            $('#medico_tratante_nota').val(medicoTratante);
            $('#anestesiologo_nota').val(anestesiologo);
            $('#anestesia_nota').val(anestesia);

            console.log('✅ Datos capturados:', {
                tratamientos: nombresTratamientos.join(', '),
                medico_tratante: medicoTratante,
                anestesiologo: anestesiologo,
                anestesia: anestesia
            });
        }

        // Event listener adicional para el botón de guardar nota (por si el submit falla)
        $(document).on('click', 'form[action="insertar_nota_enfermeria.php"] button[type="submit"]', function(e) {
            console.log('🖱️ Clic en botón Guardar Nota detectado');
            console.log('📋 Estado del formulario:', {
                form_exists: $('form[action="insertar_nota_enfermeria.php"]').length,
                button_exists: $('form[action="insertar_nota_enfermeria.php"] button[type="submit"]').length,
                nota_value: $('textarea[name="nota_enfermeria"]').val(),
                medico_value: $('select[name="medico_responsable"]').val()
            });
            // El evento submit se encargará del resto
        });

        // Verificar que el formulario esté disponible al cargar la página
        $(document).ready(function() {
            console.log('🚀 Verificando elementos del formulario de nota de enfermería...');
            console.log('📋 Elementos encontrados:', {
                form: $('form[action="insertar_nota_enfermeria.php"]').length,
                submit_button: $('form[action="insertar_nota_enfermeria.php"] button[type="submit"]').length,
                textarea: $('textarea[name="nota_enfermeria"]').length,
                select_medico: $('select[name="medico_responsable"]').length
            });

            if ($('form[action="insertar_nota_enfermeria.php"]').length === 0) {
                console.error('❌ PROBLEMA: No se encontró el formulario de nota de enfermería');
            } else {
                console.log('✅ Formulario de nota de enfermería encontrado correctamente');
            }
        });



        // =========================================
        // FUNCIONALIDAD DE DICTADO POR VOZ
        // =========================================

        // Variables globales para el reconocimiento de voz
        let recognition = null;
        let isRecording = false;
        let recordedText = '';
        let speechSynthesis = window.speechSynthesis;

        // Verificar soporte para Web Speech API
        const initializeSpeechRecognition = () => {
            if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                recognition = new SpeechRecognition();

                // Configuración del reconocimiento
                recognition.continuous = true;
                recognition.interimResults = true;
                recognition.lang = 'es-ES'; // Español
                recognition.maxAlternatives = 1;

                // Eventos del reconocimiento
                recognition.onstart = function() {
                    console.log('🎤 Dictado iniciado');
                    isRecording = true;
                    updateRecordingUI(true);
                };

                recognition.onresult = function(event) {
                    let finalTranscript = '';
                    let interimTranscript = '';

                    for (let i = event.resultIndex; i < event.results.length; i++) {
                        const transcript = event.results[i][0].transcript;
                        if (event.results[i].isFinal) {
                            finalTranscript += transcript + ' ';
                        } else {
                            interimTranscript += transcript;
                        }
                    }

                    // Solo agregar el texto final nuevo al texto base
                    if (finalTranscript) {
                        recordedText += finalTranscript;
                    }

                    // Mostrar texto base + texto final + texto temporal
                    $('.nota-enfermeria').val(recordedText + interimTranscript);

                    // Auto-scroll del textarea
                    const textarea = $('.nota-enfermeria')[0];
                    textarea.scrollTop = textarea.scrollHeight;
                };

                recognition.onerror = function(event) {
                    console.error('❌ Error en el reconocimiento de voz:', event.error);
                    let errorMessage = 'Error en el reconocimiento de voz';

                    switch (event.error) {
                        case 'network':
                            errorMessage = 'Error de conexión. Verifique su conexión a internet.';
                            break;
                        case 'not-allowed':
                            errorMessage = 'Micrófono bloqueado. Por favor, permita el acceso al micrófono.';
                            break;
                        case 'no-speech':
                            errorMessage = 'No se detectó voz. Intente hablar más cerca del micrófono.';
                            break;
                        case 'audio-capture':
                            errorMessage = 'No se puede acceder al micrófono.';
                            break;
                        case 'service-not-allowed':
                            errorMessage = 'Servicio de reconocimiento de voz no disponible.';
                            break;
                    }

                    alertify.alert('Error de Dictado', '🎤 ' + errorMessage);
                    stopRecording();
                };

                recognition.onend = function() {
                    console.log('🛑 Dictado finalizado');
                    isRecording = false;
                    updateRecordingUI(false);

                    // Asegurar que el texto final se guarde sin duplicaciones
                    $('.nota-enfermeria').val(recordedText);
                };

            } else {
                console.warn('⚠️ Web Speech API no soportada en este navegador');
                $('.grabar-nota, .detener-nota').prop('disabled', true).attr('title', 'Dictado no soportado en este navegador');
            }
        };

        // Actualizar interfaz durante grabación
        const updateRecordingUI = (recording) => {
            if (recording) {
                $('.grabar-nota')
                    .removeClass('btn-danger')
                    .addClass('btn-warning')
                    .html('<i class="fas fa-microphone-slash"></i>')
                    .attr('title', 'Detener grabación');

                $('.detener-nota').prop('disabled', false);

                // Agregar indicador visual de grabación
                if (!$('.recording-indicator').length) {
                    $('.btn-group').append('<span class="recording-indicator ml-2 text-danger"><i class="fas fa-circle" style="animation: pulse 1s infinite;"></i> Grabando...</span>');
                }
            } else {
                $('.grabar-nota')
                    .removeClass('btn-warning')
                    .addClass('btn-danger')
                    .html('<i class="fas fa-microphone"></i>')
                    .attr('title', 'Iniciar dictado');

                $('.detener-nota').prop('disabled', true);
                $('.recording-indicator').remove();
            }
        };

        // Función para iniciar grabación
        const startRecording = () => {
            if (!recognition) {
                alertify.alert('No Soportado', '🎤 El dictado por voz no está disponible en este navegador.<br><br>💡 <strong>Navegadores compatibles:</strong><br>• Google Chrome<br>• Microsoft Edge<br>• Safari (macOS/iOS)');
                return;
            }

            if (isRecording) {
                stopRecording();
                return;
            }

            try {
                // Guardar el texto actual como base (sin duplicar)
                recordedText = $('.nota-enfermeria').val();
                recognition.start();

                alertify.success('🎤 Dictado iniciado. Hable claramente hacia el micrófono.');

            } catch (error) {
                console.error('Error al iniciar grabación:', error);
                alertify.alert('Error', '🎤 No se pudo iniciar el dictado. Verifique los permisos del micrófono.');
            }
        };

        // Función para detener grabación
        const stopRecording = () => {
            if (recognition && isRecording) {
                recognition.stop();
                alertify.success('🛑 Dictado finalizado.');
            }
        };

        // Función para reproducir texto
        const playText = () => {
            const text = $('.nota-enfermeria').val().trim();

            if (!text) {
                alertify.alert('Sin Texto', '📝 No hay texto para reproducir en la nota de enfermería.');
                return;
            }

            if (!speechSynthesis) {
                alertify.alert('No Soportado', '🔊 La síntesis de voz no está disponible en este navegador.');
                return;
            }

            // Detener cualquier reproducción anterior
            speechSynthesis.cancel();

            // Crear nueva utterance
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'es-ES';
            utterance.rate = 0.9;
            utterance.pitch = 1;
            utterance.volume = 0.8;

            // Eventos de la síntesis
            utterance.onstart = function() {
                $('.reproducir-nota')
                    .removeClass('btn-success')
                    .addClass('btn-warning')
                    .html('<i class="fas fa-stop"></i>')
                    .attr('title', 'Detener reproducción');

                alertify.success('🔊 Reproduciendo nota de enfermería...');
            };

            utterance.onend = function() {
                $('.reproducir-nota')
                    .removeClass('btn-warning')
                    .addClass('btn-success')
                    .html('<i class="fas fa-play"></i>')
                    .attr('title', 'Reproducir texto');

                alertify.success('✅ Reproducción finalizada.');
            };

            utterance.onerror = function(event) {
                console.error('Error en síntesis de voz:', event.error);
                alertify.alert('Error de Reproducción', '🔊 Error al reproducir el texto: ' + event.error);

                $('.reproducir-nota')
                    .removeClass('btn-warning')
                    .addClass('btn-success')
                    .html('<i class="fas fa-play"></i>')
                    .attr('title', 'Reproducir texto');
            };

            // Iniciar síntesis
            speechSynthesis.speak(utterance);
        };

        // Event listeners para los botones de voz
        $('.grabar-nota').on('click', function(e) {
            e.preventDefault();
            startRecording();
        });

        $('.detener-nota').on('click', function(e) {
            e.preventDefault();
            stopRecording();
        });

        $('.reproducir-nota').on('click', function(e) {
            e.preventDefault();

            // Si ya está reproduciendo, detener
            if (speechSynthesis.speaking) {
                speechSynthesis.cancel();
                $(this)
                    .removeClass('btn-warning')
                    .addClass('btn-success')
                    .html('<i class="fas fa-play"></i>')
                    .attr('title', 'Reproducir texto');

                alertify.success('🛑 Reproducción detenida.');
            } else {
                playText();
            }
        });

        // Inicializar reconocimiento de voz al cargar la página
        initializeSpeechRecognition();

        // Atajos de teclado para funcionalidad de voz
        $(document).on('keydown', function(e) {
            // Ctrl+Shift+R para iniciar/detener grabación
            if (e.ctrlKey && e.shiftKey && e.key === 'R') {
                e.preventDefault();
                if (isRecording) {
                    stopRecording();
                } else {
                    startRecording();
                }
            }

            // Ctrl+Shift+S para detener grabación
            if (e.ctrlKey && e.shiftKey && e.key === 'S') {
                e.preventDefault();
                stopRecording();
            }

            // Ctrl+Shift+P para reproducir/detener reproducción
            if (e.ctrlKey && e.shiftKey && e.key === 'P') {
                e.preventDefault();
                if (speechSynthesis.speaking) {
                    speechSynthesis.cancel();
                    $('.reproducir-nota')
                        .removeClass('btn-warning')
                        .addClass('btn-success')
                        .html('<i class="fas fa-play"></i>')
                        .attr('title', 'Reproducir texto escrito (Ctrl+Shift+P)');
                    alertify.success('🛑 Reproducción detenida.');
                } else {
                    playText();
                }
            }
        });

        // Mejorar la experiencia visual durante el dictado
        const enhanceRecordingExperience = (isRecording) => {
            const textarea = $('.nota-enfermeria');
            if (isRecording) {
                textarea.addClass('dictating');
                textarea.attr('placeholder', '🎤 Dictando... Hable claramente hacia el micrófono');
            } else {
                textarea.removeClass('dictating');
                textarea.attr('placeholder', 'Escriba aquí la nota de enfermería o use el dictado por voz...');
            }
        };

        // Actualizar la función updateRecordingUI para incluir mejoras visuales
        const originalUpdateRecordingUI = updateRecordingUI;
        updateRecordingUI = function(recording) {
            originalUpdateRecordingUI(recording);
            enhanceRecordingExperience(recording);
        };

        // Agregar estilos CSS para la animación de pulso
        const style = document.createElement('style');
        style.textContent = `
            @keyframes pulse {
                0% { opacity: 1; }
                50% { opacity: 0.5; }
                100% { opacity: 1; }
            }
            .recording-indicator {
                font-size: 14px;
                font-weight: bold;
                color: #dc3545 !important;
            }
            .btn-group .btn {
                transition: all 0.3s ease;
            }
            .btn-group .btn:hover {
                transform: scale(1.05);
            }
        `;
        document.head.appendChild(style);

        console.log('🎤 Sistema de dictado por voz iniciado');
        console.log('💡 Funciones disponibles:');
        console.log('   • Botón rojo: Iniciar/Detener dictado');
        console.log('   • Botón azul: Detener dictado manualmente');
        console.log('   • Botón verde: Reproducir texto escrito');
    </script>

    <script>
        //insumos
        document.addEventListener('DOMContentLoaded', function () {
            const medicamentoSelect = document.getElementById('medicamento');
            const loteSelect = document.getElementById('lote');
            const agregarBtn = document.getElementById('agregar-btn');
            const enviarBtn = document.getElementById('enviar-btn');
            const insumosForm = document.getElementById('insumos-form');
            const tablaMedicamentos = document.getElementById('tabla-medicamentos');
            const tablaSurtidos = document.getElementById('tabla-surtidos');
            const pacienteSelect = document.getElementById('paciente');

            // Initialize Alertify
            alertify.set('notifier', 'position', 'top-right');

            // Validate form fields before sending
            function validateForm() {
                const paciente = pacienteSelect.value;
                const medicamento = medicamentoSelect.value;
                const lote = loteSelect.value;
                const cantidad = document.getElementById('cantidad').value;
                if (!paciente || !medicamento || !lote || !cantidad) {
                    alertify.warning('Completa todos los campos por favor!');
                    return false;
                }
                return true;
            }

            // Validate medicamentos before enviar
            function validateMedicamentos() {
                if (!tablaMedicamentos.querySelector('tbody') || tablaMedicamentos.querySelector('tbody').children.length === 0) {
                    alertify.warning('No hay medicamentos para enviar');
                    return false;
                }
                return true;
            }

            // Actualizar lotes al seleccionar un medicamento
            medicamentoSelect.addEventListener('change', function () {
                const medicamentoId = this.value;
                if (medicamentoId) {
                    fetch('ajax_insumos.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `action=select_medicamento&medicamento=${medicamentoId}&csrf_token=<?= $_SESSION['csrf_token'] ?>`
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                loteSelect.innerHTML = '<option value="" disabled selected>Lote/Caducidad/Total</option>' + data.data.lotesOptions;
                                document.getElementById('existe_id_display').textContent = '';
                            } else {
                                console.error('Error:', data.message);
                                alertify.error('Error al cargar los lotes: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alertify.error('Error en la comunicación con el servidor');
                        });
                }
            });

            // Actualizar información del lote seleccionado
            loteSelect.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption) {
                    const existeId = selectedOption.value.split('|')[0];
                    document.getElementById('existe_id_display').textContent = `Existe ID del lote seleccionado: ${existeId}`;
                }
            });

            // Agregar medicamento
            agregarBtn.addEventListener('click', function () {
                if (!validateForm()) return;

                const formData = new FormData(insumosForm);
                formData.append('action', 'agregar_medicamento');
                console.log('FormData being sent:', [...formData.entries()]);

                fetch('ajax_insumos.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            tablaMedicamentos.innerHTML = data.data.tabla;
                            asignarEventosEliminar();
                            insumosForm.reset();
                            loteSelect.innerHTML = '<option value="" disabled selected>Lote/Caducidad/Total</option>';
                            document.getElementById('existe_id_display').textContent = '';
                            alertify.success('Medicamento agregado a la lista');
                        } else {
                            alertify.error('Error al agregar el medicamento: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        alertify.error('Error en la comunicación con el servidor');
                    });
            });

            // Enviar medicamentos
            enviarBtn.addEventListener('click', function () {
                if (!validateMedicamentos()) return;

                const formData = new FormData(insumosForm);
                formData.append('enviar_medicamentos', '1');

                fetch('reg_pro.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            tablaMedicamentos.innerHTML = '<p class="text-center">No hay medicamentos seleccionados.</p>';
                            tablaSurtidos.innerHTML = ''; // Clear surtidos table, will be updated by select_paciente
                            pacienteSelect.dispatchEvent(new Event('change')); // Refresh surtidos
                            alertify.success('Registro agregado correctamente');
                        } else {
                            alertify.error('Error al agregar el registro: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        alertify.error('Error en la comunicación con el servidor');
                    });
            });

            // Actualizar ítems surtidos al seleccionar un paciente
            pacienteSelect.addEventListener('change', function () {
                const pacienteId = this.value;
                if (pacienteId) {
                    fetch('ajax_insumos.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `action=select_paciente&paciente=${pacienteId}&csrf_token=<?= $_SESSION['csrf_token'] ?>`
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error en la respuesta del servidor');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                tablaSurtidos.innerHTML = data.data.tablaSurtidos;
                                asignarEventosEliminarSurtidos();
                            } else {
                                alertify.error('Error al cargar los ítems surtidos: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alertify.error('Error en la comunicación con el servidor');
                        });
                    }
            });

            // Manejar eliminación de medicamentos (pendientes)
            function asignarEventosEliminar() {
                const eliminarForms = document.querySelectorAll('.eliminar-form');
                eliminarForms.forEach(form => {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        const index = this.getAttribute('data-index');
                        const formData = new FormData(this);
                        formData.append('action', 'eliminar_medicamento');

                        fetch('ajax_insumos.php', {
                            method: 'POST',
                            body: formData
                        })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Error en la respuesta del servidor');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    tablaMedicamentos.innerHTML = data.data.tabla;
                                    asignarEventosEliminar();
                                    alertify.success('Medicamento eliminado de la lista');
                                } else {
                                    alertify.error('Error al eliminar el medicamento: ' + data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alertify.error('Error en la comunicación con el servidor');
                            });
                    });
                });
            }

            // Manejar eliminación de ítems surtidos
            function asignarEventosEliminarSurtidos() {
                const eliminarSurtidoForms = document.querySelectorAll('.eliminar-surtido-form');
                eliminarSurtidoForms.forEach(form => {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        const idCtapac = this.getAttribute('data-id-ctapac');
                        const formData = new FormData(this);
                        formData.append('action', 'eliminar_surtido');

                        fetch('ajax_insumos.php', {
                            method: 'POST',
                            body: formData
                        })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Error en la respuesta del servidor');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    tablaSurtidos.innerHTML = data.data.tablaSurtidos;
                                    asignarEventosEliminarSurtidos();
                                    alertify.success('Ítem surtido eliminado correctamente');
                                } else {
                                    alertify.error('Error al eliminar el ítem surtido: ' + data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Fetch error:', error);
                                alertify.error('Error en la comunicación con el servidor');
                            });
                    });
                });
            }

            // Asignar eventos a los botones de eliminar al cargar la página
            asignarEventosEliminar();
            asignarEventosEliminarSurtidos();
        });
    </script>
</body>

</html>
<?php
$conexion->close();
?>