<?php
ob_start();
session_start();
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');
require_once '../../conexionbd.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['login']) || !isset($_SESSION['csrf_token'])) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$action = $_POST['action'] ?? '';

function validateCSRF($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

switch ($action) {
    case 'select_insumo':
        if (!validateCSRF($_POST['csrf_token'] ?? '')) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Token CSRF inválido']);
            exit;
        }

        $insumoId = isset($_POST['insumo']) ? intval($_POST['insumo']) : 0;
        if ($insumoId <= 0) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'ID de insumo inválido']);
            exit;
        }

        $sqlLotes = "
            SELECT existe_id, existe_lote, existe_caducidad, existe_qty
            FROM existencias_almacenq
            WHERE item_id = ? AND existe_qty > 0
            ORDER BY existe_caducidad ASC
        ";
        $stmt = $conexion->prepare($sqlLotes);
        $stmt->bind_param('i', $insumoId);
        $stmt->execute();
        $resultLotes = $stmt->get_result();

        $lotesOptions = '';
        if ($resultLotes && $resultLotes->num_rows > 0) {
            while ($lote = $resultLotes->fetch_assoc()) {
                $lotesOptions .= "<option value='{$lote['existe_id']}|{$lote['existe_lote']}|$insumoId' data-caducidad='{$lote['existe_caducidad']}' data-cantidad='{$lote['existe_qty']}'>
                    {$lote['existe_lote']} / {$lote['existe_caducidad']} / {$lote['existe_qty']}
                </option>";
            }
        } else {
            $lotesOptions = "<option value='' disabled>No hay lotes disponibles</option>";
        }
        $stmt->close();

        ob_end_clean();
        echo json_encode(['success' => true, 'data' => ['lotesOptions' => $lotesOptions]]);
        break;

    case 'fetch_medicamentos':
        if (!validateCSRF($_POST['csrf_token'] ?? '')) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Token CSRF inválido']);
            exit;
        }

        $queryMedicamentos = "
            SELECT DISTINCT ea.item_id, CONCAT(ia.item_name, ', ', ia.item_grams) AS item_name
            FROM existencias_almacen ea
            JOIN item_almacen ia ON ea.item_id = ia.item_id
            ORDER BY ia.item_name
        ";
        $resultMedicamentos = $conexion->query($queryMedicamentos);

        $medicamentosOptions = '';
        if ($resultMedicamentos && $resultMedicamentos->num_rows > 0) {
            while ($medicamento = $resultMedicamentos->fetch_assoc()) {
                $medicamentosOptions .= "<option value='{$medicamento['item_id']}'>{$medicamento['item_name']}</option>";
            }
        }
        $resultMedicamentos->free();

        ob_end_clean();
        echo json_encode(['success' => true, 'data' => ['medicamentosOptions' => $medicamentosOptions]]);
        break;

    case 'agregar_insumo':
        if (!validateCSRF($_POST['csrf_token'] ?? '')) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Token CSRF inválido']);
            exit;
        }

        $paciente = isset($_POST['paciente']) ? intval($_POST['paciente']) : 0;
        $insumo = isset($_POST['insumo']) ? intval($_POST['insumo']) : 0;
        $loteInsumo = isset($_POST['lote_insumo']) ? explode('|', $_POST['lote_insumo']) : [];
        $cantidad = isset($_POST['cantidad_insumo']) ? intval($_POST['cantidad_insumo']) : 0;

        if (!$paciente || !$insumo || count($loteInsumo) < 3 || !$cantidad) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Datos incompletos para insumo']);
            exit;
        }

        $existeId = intval($loteInsumo[0]);
        $loteNombre = $loteInsumo[1];
        $itemId = intval($loteInsumo[2]);

        $sqlPaciente = "
            SELECT CONCAT(p.nom_pac, ' ', p.papell, ' ', p.sapell) AS nombre_paciente
            FROM paciente p
            INNER JOIN dat_ingreso di ON p.Id_exp = di.Id_exp
            WHERE di.id_atencion = ?
        ";
        $stmtPaciente = $conexion->prepare($sqlPaciente);
        $stmtPaciente->bind_param('i', $paciente);
        $stmtPaciente->execute();
        $resultPaciente = $stmtPaciente->get_result();
        $pacienteData = $resultPaciente->fetch_assoc();
        $stmtPaciente->close();

        if (!$pacienteData) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Paciente no encontrado']);
            exit;
        }

        $sqlInsumo = "SELECT item_name, item_price FROM item_almacen WHERE item_id = ?";
        $stmtInsumo = $conexion->prepare($sqlInsumo);
        $stmtInsumo->bind_param('i', $insumo);
        $stmtInsumo->execute();
        $resultInsumo = $stmtInsumo->get_result();
        $insumoData = $resultInsumo->fetch_assoc();
        $stmtInsumo->close();

        if (!$insumoData) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Insumo no encontrado']);
            exit;
        }

        $sqlLote = "SELECT existe_qty, existe_caducidad FROM existencias_almacenq WHERE existe_id = ?";
        $stmtLote = $conexion->prepare($sqlLote);
        $stmtLote->bind_param('i', $existeId);
        $stmtLote->execute();
        $resultLote = $stmtLote->get_result();
        $loteData = $resultLote->fetch_assoc();
        $stmtLote->close();

        if (!$loteData || $loteData['existe_qty'] < $cantidad) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Lote no encontrado o cantidad insuficiente']);
            exit;
        }

        if (!isset($_SESSION['medicamento_seleccionado'])) {
            $_SESSION['medicamento_seleccionado'] = [];
        }

        $_SESSION['medicamento_seleccionado'][] = [
            'paciente' => $pacienteData['nombre_paciente'],
            'id_atencion' => $paciente,
            'medicamento' => $insumoData['item_name'],
            'lote' => $loteNombre,
            'cantidad' => $cantidad,
            'existe_id' => $existeId,
            'caducidad' => $loteData['existe_caducidad'],
            'item_id' => $insumo,
            'precio' => $insumoData['item_price']
        ];

        $tabla = '<table class="table table-bordered table-striped"><thead class="thead-dark"><tr><th>Paciente</th><th>Insumo</th><th>Lote</th><th>Caducidad</th><th>Cantidad</th><th>Acciones</th></tr></thead><tbody>';
        foreach ($_SESSION['medicamento_seleccionado'] as $index => $med) {
            $tabla .= "<tr><td>" . htmlspecialchars($med['paciente']) . "</td><td>" . htmlspecialchars($med['medicamento']) . "</td><td>" . htmlspecialchars($med['lote']) . "</td><td>" . htmlspecialchars($med['caducidad']) . "</td><td>" . htmlspecialchars($med['cantidad']) . "</td><td><form class='eliminar-insumo-form' method='post'><input type='hidden' name='index' value='$index'><input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'><button type='submit' class='btn btn-danger'>Eliminar</button></form></td></tr>";
        }
        $tabla .= '</tbody></table>';

        ob_end_clean();
        echo json_encode(['success' => true, 'data' => ['tabla' => $tabla]]);
        break;

    case 'agregar_medicamento':
        if (!validateCSRF($_POST['csrf_token'] ?? '')) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Token CSRF inválido']);
            exit;
        }

        // Required fields
        $paciente = isset($_POST['paciente']) ? intval($_POST['paciente']) : 0;
        $medicamento = isset($_POST['medicamento']) ? intval($_POST['medicamento']) : 0;
        $cantidad = isset($_POST['cantidad_medicamento']) ? intval($_POST['cantidad_medicamento']) : 0;
        $fecha = isset($_POST['enf_fecha']) ? trim($_POST['enf_fecha']) : '';
        $hora = isset($_POST['cart_hora']) ? trim($_POST['cart_hora']) : '';

        // Optional fields
        $dosis = isset($_POST['dosis_mat']) ? trim($_POST['dosis_mat']) : '';
        $unidad = isset($_POST['unimed']) ? trim($_POST['unimed']) : '';
        $via = isset($_POST['via_mat']) ? trim($_POST['via_mat']) : '';
        $otro = isset($_POST['otro']) ? trim($_POST['otro']) : '';

        // Validate required fields
        if (!$paciente || !$medicamento || !$cantidad || !$fecha || !$hora) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios: paciente, medicamento, cantidad, fecha u hora']);
            file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - agregar_medicamento: Datos incompletos - Paciente: ' . $paciente . ', Medicamento: ' . $medicamento . ', Cantidad: ' . $cantidad . ', Fecha: ' . $fecha . ', Hora: ' . $hora . "\n", FILE_APPEND);
            exit;
        }

        // Validate paciente
        $sqlPaciente = "
            SELECT CONCAT(p.nom_pac, ' ', p.papell, ' ', p.sapell) AS nombre_paciente
            FROM paciente p
            INNER JOIN dat_ingreso di ON p.Id_exp = di.Id_exp
            WHERE di.id_atencion = ?
        ";
        $stmtPaciente = $conexion->prepare($sqlPaciente);
        if (!$stmtPaciente) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Error preparando consulta de paciente: ' . $conexion->error]);
            file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - agregar_medicamento: Error preparando consulta de paciente: ' . $conexion->error . "\n", FILE_APPEND);
            exit;
        }
        $stmtPaciente->bind_param('i', $paciente);
        if (!$stmtPaciente->execute()) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Error ejecutando consulta de paciente: ' . $stmtPaciente->error]);
            file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - agregar_medicamento: Error ejecutando consulta de paciente: ' . $stmtPaciente->error . "\n", FILE_APPEND);
            exit;
        }
        $resultPaciente = $stmtPaciente->get_result();
        $pacienteData = $resultPaciente->fetch_assoc();
        $stmtPaciente->close();

        if (!$pacienteData) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Paciente no encontrado']);
            file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - agregar_medicamento: Paciente no encontrado (ID: ' . $paciente . ")\n", FILE_APPEND);
            exit;
        }

        // Validate medicamento
        $sqlMedicamento = "SELECT item_name, item_price FROM item_almacen WHERE item_id = ?";
        $stmtMedicamento = $conexion->prepare($sqlMedicamento);
        if (!$stmtMedicamento) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Error preparando consulta de medicamento: ' . $conexion->error]);
            file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - agregar_medicamento: Error preparando consulta de medicamento: ' . $conexion->error . "\n", FILE_APPEND);
            exit;
        }
        $stmtMedicamento->bind_param('i', $medicamento);
        if (!$stmtMedicamento->execute()) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Error ejecutando consulta de medicamento: ' . $stmtMedicamento->error]);
            file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - agregar_medicamento: Error ejecutando consulta de medicamento: ' . $stmtMedicamento->error . "\n", FILE_APPEND);
            exit;
        }
        $resultMedicamento = $stmtMedicamento->get_result();
        $medicamentoData = $resultMedicamento->fetch_assoc();
        $stmtMedicamento->close();

        if (!$medicamentoData) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Medicamento no encontrado']);
            file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - agregar_medicamento: Medicamento no encontrado (ID: ' . $medicamento . ")\n", FILE_APPEND);
            exit;
        }

        // Validate stock
        $sqlStock = "SELECT SUM(existe_qty) AS total_qty FROM existencias_almacen WHERE item_id = ?";
        $stmtStock = $conexion->prepare($sqlStock);
        if (!$stmtStock) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Error preparando consulta de stock: ' . $conexion->error]);
            file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - agregar_medicamento: Error preparando consulta de stock: ' . $conexion->error . "\n", FILE_APPEND);
            exit;
        }
        $stmtStock->bind_param('i', $medicamento);
        if (!$stmtStock->execute()) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Error ejecutando consulta de stock: ' . $stmtStock->error]);
            file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - agregar_medicamento: Error ejecutando consulta de stock: ' . $stmtStock->error . "\n", FILE_APPEND);
            exit;
        }
        $resultStock = $stmtStock->get_result();
        $stockData = $resultStock->fetch_assoc();
        $stmtStock->close();

        if (!$stockData || $stockData['total_qty'] < $cantidad) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Stock insuficiente para el medicamento. Disponible: ' . ($stockData['total_qty'] ?? 0)]);
            file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - agregar_medicamento: Stock insuficiente (ID: ' . $medicamento . ', Requerido: ' . $cantidad . ', Disponible: ' . ($stockData['total_qty'] ?? 0) . ")\n", FILE_APPEND);
            exit;
        }

        // Initialize session array if not set
        if (!isset($_SESSION['medicamento_seleccionadoMed'])) {
            $_SESSION['medicamento_seleccionadoMed'] = [];
        }

        // Generate unique ID for the entry
        $id = uniqid();

        // Store in session
        $_SESSION['medicamento_seleccionadoMed'][] = [
            'id' => $id,
            'paciente' => $pacienteData['nombre_paciente'],
            'id_atencion' => $paciente,
            'medicamento' => $medicamentoData['item_name'],
            'cantidad' => $cantidad,
            'item_id' => $medicamento,
            'precio' => $medicamentoData['item_price'],
            'fecha' => $fecha,
            'hora' => $hora,
            'dosis' => $dosis,
            'unidad' => $unidad,
            'via' => $via,
            'otro' => $otro
        ];

        // Generate table
        $tablaMed = '<table class="table table-bordered table-striped"><thead class="thead-dark"><tr><th>Id</th><th>Fecha de reporte</th><th>Hora</th><th>Medicamento</th><th>Dosis</th><th>Vía</th><th>Otros</th><th>Cantidad</th><th>Acciones</th></tr></thead><tbody>';
        foreach ($_SESSION['medicamento_seleccionadoMed'] as $index => $med) {
            $dosisDisplay = $med['dosis'] && $med['unidad'] ? htmlspecialchars($med['dosis'] . ' ' . $med['unidad']) : htmlspecialchars($med['dosis'] ?: '-');
            $viaDisplay = htmlspecialchars($med['via'] ?: '-');
            $otroDisplay = htmlspecialchars($med['otro'] ?: '-');
            $tablaMed .= "<tr><td>" . htmlspecialchars($med['id']) . "</td><td>" . htmlspecialchars($med['fecha']) . "</td><td>" . htmlspecialchars($med['hora']) . "</td><td>" . htmlspecialchars($med['medicamento']) . "</td><td>" . $dosisDisplay . "</td><td>" . $viaDisplay . "</td><td>" . $otroDisplay . "</td><td>" . htmlspecialchars($med['cantidad']) . "</td><td><form class='eliminar-medicamento-form' method='post' data-index='$index'><input type='hidden' name='index' value='$index'><input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'><button type='submit' class='btn btn-danger'>Eliminar</button></form></td></tr>";
        }
        $tablaMed .= '</tbody></table>';

        ob_end_clean();
        echo json_encode(['success' => true, 'data' => ['tablaMed' => $tablaMed]]);
        break;

    case 'eliminar_insumo':
        if (!validateCSRF($_POST['csrf_token'] ?? '')) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Token CSRF inválido']);
            exit;
        }

        $index = isset($_POST['index']) ? intval($_POST['index']) : -1;
        if ($index >= 0 && isset($_SESSION['medicamento_seleccionado'][$index])) {
            unset($_SESSION['medicamento_seleccionado'][$index]);
            $_SESSION['medicamento_seleccionado'] = array_values($_SESSION['medicamento_seleccionado']);
        }

        $tabla = '<p class="text-center">No hay insumos seleccionados.</p>';
        if (!empty($_SESSION['medicamento_seleccionado'])) {
            $tabla = '<table class="table table-bordered table-striped"><thead class="thead-dark"><tr><th>Paciente</th><th>Insumo</th><th>Lote</th><th>Caducidad</th><th>Cantidad</th><th>Acciones</th></tr></thead><tbody>';
            foreach ($_SESSION['medicamento_seleccionado'] as $index => $med) {
                $tabla .= "<tr><td>" . htmlspecialchars($med['paciente']) . "</td><td>" . htmlspecialchars($med['medicamento']) . "</td><td>" . htmlspecialchars($med['lote']) . "</td><td>" . htmlspecialchars($med['caducidad']) . "</td><td>" . htmlspecialchars($med['cantidad']) . "</td><td><form class='eliminar-insumo-form' method='post'><input type='hidden' name='index' value='$index'><input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'><button type='submit' class='btn btn-danger'>Eliminar</button></form></td></tr>";
            }
            $tabla .= '</tbody></table>';
        }

        ob_end_clean();
        echo json_encode(['success' => true, 'data' => ['tabla' => $tabla]]);
        break;

    case 'eliminar_medicamento':
        if (!validateCSRF($_POST['csrf_token'] ?? '')) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Token CSRF inválido']);
            file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - eliminar_medicamento: Token CSRF inválido' . "\n", FILE_APPEND);
            exit;
        }

        $index = isset($_POST['index']) ? intval($_POST['index']) : -1;
        if ($index < 0 || !isset($_SESSION['medicamento_seleccionadoMed'][$index])) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Índice de medicamento inválido']);
            file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - eliminar_medicamento: Índice inválido (' . $index . ")\n", FILE_APPEND);
            exit;
        }

        // Remove the medication entry
        unset($_SESSION['medicamento_seleccionadoMed'][$index]);
        $_SESSION['medicamento_seleccionadoMed'] = array_values($_SESSION['medicamento_seleccionadoMed']);

        // Generate updated table
        $tablaMed = '<p class="text-center">No hay medicamentos seleccionados.</p>';
        if (!empty($_SESSION['medicamento_seleccionadoMed'])) {
            $tablaMed = '<table class="table table-bordered table-striped"><thead class="thead-dark"><tr><th>Id</th><th>Fecha de reporte</th><th>Hora</th><th>Medicamento</th><th>Dosis</th><th>Vía</th><th>Otros</th><th>Cantidad</th><th>Acciones</th></tr></thead><tbody>';
            foreach ($_SESSION['medicamento_seleccionadoMed'] as $index => $med) {
                $dosisDisplay = $med['dosis'] && $med['unidad'] ? htmlspecialchars($med['dosis'] . ' ' . $med['unidad']) : htmlspecialchars($med['dosis'] ?: '-');
                $viaDisplay = htmlspecialchars($med['via'] ?: '-');
                $otroDisplay = htmlspecialchars($med['otro'] ?: '-');
                $tablaMed .= "<tr><td>" . htmlspecialchars($med['id']) . "</td><td>" . htmlspecialchars($med['fecha']) . "</td><td>" . htmlspecialchars($med['hora']) . "</td><td>" . htmlspecialchars($med['medicamento']) . "</td><td>" . $dosisDisplay . "</td><td>" . $viaDisplay . "</td><td>" . $otroDisplay . "</td><td>" . htmlspecialchars($med['cantidad']) . "</td><td><form class='eliminar-medicamento-form' method='post' data-index='$index'><input type='hidden' name='index' value='$index'><input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'><button type='submit' class='btn btn-danger'>Eliminar</button></form></td></tr>";
            }
            $tablaMed .= '</tbody></table>';
        }

        ob_end_clean();
        echo json_encode(['success' => true, 'data' => ['tablaMed' => $tablaMed]]);
        break;

    case 'select_paciente':
        if (!validateCSRF($_POST['csrf_token'] ?? '')) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Token CSRF inválido']);
            exit;
        }

        $paciente = isset($_POST['paciente']) ? intval($_POST['paciente']) : 0;
        if ($paciente <= 0) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'ID de paciente inválido']);
            exit;
        }

        $_SESSION['paciente_seleccionado'] = $paciente;

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
        $stmtSurtidos->bind_param('i', $paciente);
        $stmtSurtidos->execute();
        $resultSurtidos = $stmtSurtidos->get_result();

        $tablaSurtidos = '<p class="text-center">No hay insumos surtidos para el paciente seleccionado.</p>';
        if ($resultSurtidos && $resultSurtidos->num_rows > 0) {
            $tablaSurtidos = '<table class="table table-bordered table-striped"><thead class="thead-dark"><tr><th>Paciente</th><th>Insumo</th><th>Lote</th><th>Caducidad</th><th>Cantidad</th><th>Total</th><th>Acciones</th></tr></thead><tbody>';
            while ($row = $resultSurtidos->fetch_assoc()) {
                $tablaSurtidos .= "<tr><td>" . htmlspecialchars($row['nombre_paciente']) . "</td><td>" . htmlspecialchars($row['item_name']) . "</td><td>" . htmlspecialchars($row['existe_lote']) . "</td><td>" . htmlspecialchars($row['existe_caducidad']) . "</td><td>" . htmlspecialchars($row['cta_cant']) . "</td><td>" . htmlspecialchars($row['cta_tot']) . "</td><td><form class='eliminar-surtido-form' data-id-ctapac='{$row['id_ctapac']}'><input type='hidden' name='id_ctapac' value='{$row['id_ctapac']}'><input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'><button type='submit' class='btn btn-danger'>Eliminar</button></form></td></tr>";
            }
            $tablaSurtidos .= '</tbody></table>';
        }
        $stmtSurtidos->close();

        $sqlSurtidosMed = "
            SELECT
                dc.id_ctapac,
                CONCAT(p.nom_pac, ' ', p.papell, ' ', p.sapell) AS nombre_paciente,
                CONCAT(ia.item_name, ', ', ia.item_grams) AS item_name,
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
            ORDER BY
                dc.cta_fec DESC
        ";
        $stmtSurtidosMed = $conexion->prepare($sqlSurtidosMed);
        $stmtSurtidosMed->bind_param('i', $paciente);
        $stmtSurtidosMed->execute();
        $resultSurtidosMed = $stmtSurtidosMed->get_result();

        $tablaSurtidosMed = '<p class="text-center">No hay medicamentos surtidos para el paciente seleccionado.</p>';
        if ($resultSurtidosMed && $resultSurtidosMed->num_rows > 0) {
            $tablaSurtidosMed = '<table class="table table-bordered table-striped"><thead class="thead-dark"><tr><th>Paciente</th><th>Medicamento</th><th>Cantidad</th><th>Total</th><th>Acciones</th></tr></thead><tbody>';
            while ($row = $resultSurtidosMed->fetch_assoc()) {
                $tablaSurtidosMed .= "<tr><td>" . htmlspecialchars($row['nombre_paciente']) . "</td><td>" . htmlspecialchars($row['item_name']) . "</td><td>" . htmlspecialchars($row['cta_cant']) . "</td><td>" . htmlspecialchars($row['cta_tot']) . "</td><td><form class='eliminar-surtido-form' data-id-ctapac='{$row['id_ctapac']}'><input type='hidden' name='id_ctapac' value='{$row['id_ctapac']}'><input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'><button type='submit' class='btn btn-danger'>Eliminar</button></form></td></tr>";
            }
            $tablaSurtidosMed .= '</tbody></table>';
        }
        $stmtSurtidosMed->close();

        ob_end_clean();
        echo json_encode(['success' => true, 'data' => ['tablaSurtidos' => $tablaSurtidos, 'tablaSurtidosMed' => $tablaSurtidosMed]]);
        break;

    case 'eliminar_surtido':
        if (!validateCSRF($_POST['csrf_token'] ?? '')) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Token CSRF inválido']);
            exit;
        }

        $idCtapac = isset($_POST['id_ctapac']) ? intval($_POST['id_ctapac']) : 0;
        if ($idCtapac <= 0) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'ID de registro inválido']);
            exit;
        }

        $conexion->begin_transaction();

        try {
            $sqlCtapac = "SELECT id_atencion, insumo, cta_cant, existe_lote, existe_caducidad FROM dat_ctapac WHERE id_ctapac = ? AND cta_activo = 'SI'";
            $stmtCtapac = $conexion->prepare($sqlCtapac);
            $stmtCtapac->bind_param('i', $idCtapac);
            $stmtCtapac->execute();
            $resultCtapac = $stmtCtapac->get_result();
            $ctapacData = $resultCtapac->fetch_assoc();
            $stmtCtapac->close();

            if (!$ctapacData) {
                throw new Exception('Registro no encontrado o ya inactivo');
            }

            $idAtencion = $ctapacData['id_atencion'];
            $insumoId = $ctapacData['insumo'];
            $cantidad = $ctapacData['cta_cant'];
            $lote = $ctapacData['existe_lote'];
            $caducidad = $ctapacData['existe_caducidad'];

            $sqlUpdateCtapac = "UPDATE dat_ctapac SET cta_activo = 'NO' WHERE id_ctapac = ?";
            $stmtUpdateCtapac = $conexion->prepare($sqlUpdateCtapac);
            $stmtUpdateCtapac->bind_param('i', $idCtapac);
            if (!$stmtUpdateCtapac->execute()) {
                throw new Exception('Error al actualizar dat_ctapac');
            }
            $stmtUpdateCtapac->close();

            if ($lote && $caducidad) {
                $sqlExistencias = "UPDATE existencias_almacenq SET existe_qty = existe_qty + ?, existe_salidas = existe_salidas - ? WHERE item_id = ? AND existe_lote = ? AND existe_caducidad = ?";
                $stmtExistencias = $conexion->prepare($sqlExistencias);
                $stmtExistencias->bind_param('iisss', $cantidad, $cantidad, $insumoId, $lote, $caducidad);
                if (!$stmtExistencias->execute()) {
                    throw new Exception('Error al actualizar existencias');
                }
                $stmtExistencias->close();
            } else {
                $sqlExistencias = "UPDATE existencias_almacen SET existe_qty = existe_qty + ?, existe_salidas = existe_salidas - ? WHERE item_id = ?";
                $stmtExistencias = $conexion->prepare($sqlExistencias);
                $stmtExistencias->bind_param('iii', $cantidad, $cantidad, $insumoId);
                if (!$stmtExistencias->execute()) {
                    throw new Exception('Error al actualizar existencias');
                }
                $stmtExistencias->close();
            }

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

            $tablaSurtidos = '<p class="text-center">No hay insumos surtidos para el paciente seleccionado.</p>';
            if ($resultSurtidos && $resultSurtidos->num_rows > 0) {
                $tablaSurtidos = '<table class="table table-bordered table-striped"><thead class="thead-dark"><tr><th>Paciente</th><th>Insumo</th><th>Lote</th><th>Caducidad</th><th>Cantidad</th><th>Total</th><th>Acciones</th></tr></thead><tbody>';
                while ($row = $resultSurtidos->fetch_assoc()) {
                    $tablaSurtidos .= "<tr><td>" . htmlspecialchars($row['nombre_paciente']) . "</td><td>" . htmlspecialchars($row['item_name']) . "</td><td>" . htmlspecialchars($row['existe_lote']) . "</td><td>" . htmlspecialchars($row['existe_caducidad']) . "</td><td>" . htmlspecialchars($row['cta_cant']) . "</td><td>" . htmlspecialchars($row['cta_tot']) . "</td><td><form class='eliminar-surtido-form' data-id-ctapac='{$row['id_ctapac']}'><input type='hidden' name='id_ctapac' value='{$row['id_ctapac']}'><input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'><button type='submit' class='btn btn-danger'>Eliminar</button></form></td></tr>";
                }
                $tablaSurtidos .= '</tbody></table>';
            }
            $stmtSurtidos->close();

            $sqlSurtidosMed = "
                SELECT
                    dc.id_ctapac,
                    CONCAT(p.nom_pac, ' ', p.papell, ' ', p.sapell) AS nombre_paciente,
                    CONCAT(ia.item_name, ', ', ia.item_grams) AS item_name,
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
                ORDER BY
                    dc.cta_fec DESC
            ";
            $stmtSurtidosMed = $conexion->prepare($sqlSurtidosMed);
            $stmtSurtidosMed->bind_param('i', $idAtencion);
            $stmtSurtidosMed->execute();
            $resultSurtidosMed = $stmtSurtidosMed->get_result();

            $tablaSurtidosMed = '<p class="text-center">No hay medicamentos surtidos para el paciente seleccionado.</p>';
            if ($resultSurtidosMed && $resultSurtidosMed->num_rows > 0) {
                $tablaSurtidosMed = '<table class="table table-bordered table-striped"><thead class="thead-dark"><tr><th>Paciente</th><th>Medicamento</th><th>Cantidad</th><th>Total</th><th>Acciones</th></tr></thead><tbody>';
                while ($row = $resultSurtidosMed->fetch_assoc()) {
                    $tablaSurtidosMed .= "<tr><td>" . htmlspecialchars($row['nombre_paciente']) . "</td><td>" . htmlspecialchars($row['item_name']) . "</td><td>" . htmlspecialchars($row['cta_cant']) . "</td><td>" . htmlspecialchars($row['cta_tot']) . "</td><td><form class='eliminar-surtido-form' data-id-ctapac='{$row['id_ctapac']}'><input type='hidden' name='id_ctapac' value='{$row['id_ctapac']}'><input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'><button type='submit' class='btn btn-danger'>Eliminar</button></form></td></tr>";
                }
                $tablaSurtidosMed .= '</tbody></table>';
            }
            $stmtSurtidosMed->close();

            $conexion->commit();
            ob_end_clean();
            echo json_encode(['success' => true, 'data' => ['tablaSurtidos' => $tablaSurtidos, 'tablaSurtidosMed' => $tablaSurtidosMed]]);
        } catch (Exception $e) {
            $conexion->rollback();
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el ítem surtido: ' . $e->getMessage()]);
        }
        break;

    case 'enviar_medicamentosMed':
    // Log the start of the process
    file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - Iniciar enviar_medicamentosMed' . "\n", FILE_APPEND);

    // Validate CSRF token
    if (!validateCSRF($_POST['csrf_token'] ?? '')) {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Token CSRF inválido']);
        file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - enviar_medicamentosMed: Token CSRF inválido' . "\n", FILE_APPEND);
        exit;
    }

    // Log CSRF validation passed
    file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - CSRF válido' . "\n", FILE_APPEND);

    // Check session data
    if (!isset($_SESSION['medicamento_seleccionadoMed']) || empty($_SESSION['medicamento_seleccionadoMed']) || !is_array($_SESSION['medicamento_seleccionadoMed'])) {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'No hay medicamentos en la memoria para procesar.']);
        file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - enviar_medicamentosMed: No hay medicamentos seleccionados. Session data: ' . print_r($_SESSION['medicamento_seleccionadoMed'], true) . "\n", FILE_APPEND);
        exit;
    }

    // Log session data
    file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - Medicamentos en sesión: ' . print_r($_SESSION['medicamento_seleccionadoMed'], true) . "\n", FILE_APPEND);

    // Ensure $area and $id_usua are defined
    $usuario = $_SESSION['login'] ?? null;
    $id_usua = $usuario['id_usua'] ?? null;
    $area = 'QUIROFANO'; // Adjust based on your logic or POST data
    if (!$id_usua) {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
        file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - enviar_medicamentosMed: id_usua no definido' . "\n", FILE_APPEND);
        exit;
    }

    $fechaActualMed = date('Y-m-d H:i:s');
    $conexion->begin_transaction();

    try {
        foreach ($_SESSION['medicamento_seleccionadoMed'] as $index => $medicamento) {
            // Validate required fields
            if (!isset($medicamento['paciente'], $medicamento['medicamento'], $medicamento['cantidad'], $medicamento['id_atencion'], $medicamento['item_id'], $medicamento['precio'], $medicamento['fecha'], $medicamento['hora'])) {
                throw new Exception("Datos incompletos en la sesión para el medicamento en el índice $index: " . print_r($medicamento, true));
            }

            // Assign variables
            $pacienteMed = $medicamento['paciente'];
            $nombreMedicamentoMed = $medicamento['medicamento'];
            $cantidadLoteMed = intval($medicamento['cantidad']);
            $Id_AtencionMed = intval($medicamento['id_atencion']);
            $itemIdMed = intval($medicamento['item_id']);
            $salidaCostsuMed = floatval($medicamento['precio']);
            $fechaMed = $medicamento['fecha'];
            $horaMed = $medicamento['hora'];
            $dosisMed = $medicamento['dosis'] ?? '';
            $unidadMed = $medicamento['unidad'] ?? '';
            $viaMed = $medicamento['via'] ?? '';
            $otroMed = $medicamento['otro'] ?? '';

            // Log medication data
            file_put_contents('error_log.txt', date('Y-m-d H:i:s') . " - Procesando medicamento index $index: item_id=$itemIdMed, cantidad=$cantidadLoteMed, id_atencion=$Id_AtencionMed\n", FILE_APPEND);

            // Verify item_almacen
            $queryItemAlmacenMed = "SELECT item_name, item_price FROM item_almacen WHERE item_id = ?";
            $stmtMed = $conexion->prepare($queryItemAlmacenMed);
            if (!$stmtMed) {
                throw new Exception("Error preparando consulta item_almacen: " . $conexion->error);
            }
            $stmtMed->bind_param("i", $itemIdMed);
            $stmtMed->execute();
            $resultMed = $stmtMed->get_result();
            if ($resultMed->num_rows === 0) {
                throw new Exception("No se encontró el ítem con ID $itemIdMed.");
            }
            $itemDataMed = $resultMed->fetch_assoc();
            $salidaCostsuMed = $itemDataMed['item_price'];
            $stmtMed->close();

            // Verify stock
            $selectExistenciasQueryMed = "SELECT SUM(existe_qty) AS total_qty FROM existencias_almacen WHERE item_id = ?";
            $stmtSelectMed = $conexion->prepare($selectExistenciasQueryMed);
            if (!$stmtSelectMed) {
                throw new Exception("Error preparando consulta existencias_almacen: " . $conexion->error);
            }
            $stmtSelectMed->bind_param('i', $itemIdMed);
            $stmtSelectMed->execute();
            $resultStockMed = $stmtSelectMed->get_result();
            $stockDataMed = $resultStockMed->fetch_assoc();
            $stmtSelectMed->close();

            if (!$stockDataMed || $stockDataMed['total_qty'] < $cantidadLoteMed) {
                throw new Exception("Stock insuficiente para el medicamento ID $itemIdMed. Disponible: " . ($stockDataMed['total_qty'] ?? 0) . ", requerido: $cantidadLoteMed.");
            }

            // Insert into kardex_almacen
            $insertKardex = "
                INSERT INTO kardex_almacen (
                    kardex_fecha, item_id, kardex_lote, kardex_caducidad, kardex_inicial, kardex_entradas, kardex_salidas, kardex_qty, 
                    kardex_dev_stock, kardex_dev_merma, kardex_movimiento, kardex_ubicacion, kardex_destino, id_usua, id_compra, factura
                ) 
                VALUES (NOW(), ?, '', '0000-00-00', 0, 0, ?, 0, 0, 0, 'Salida', 'ALMACEN', ?, ?, NULL, NULL)
            ";
            $stmtKardex = $conexion->prepare($insertKardex);
            if (!$stmtKardex) {
                throw new Exception("Error preparando consulta kardex_almacen: " . $conexion->error);
            }
            $kardexDestino = $area;
            $stmtKardex->bind_param('iisi', $itemIdMed, $cantidadLoteMed, $kardexDestino, $id_usua);
            if (!$stmtKardex->execute()) {
                throw new Exception("Error insertando en kardex_almacen: " . $stmtKardex->error);
            }
            $stmtKardex->close();
            file_put_contents('error_log.txt', date('Y-m-d H:i:s') . " - Insertado en kardex_almacen para item_id=$itemIdMed\n", FILE_APPEND);

            // Insert into salidas_almacen (removed solicita)
            $queryInsercionMed = "
                INSERT INTO salidas_almacen (
                    item_id, salida_fecha, salida_qty, salida_costsu, id_usua, fecha_solicitud, salio
                ) VALUES (?, ?, ?, ?, ?, ?, ?)
            ";
            $stmtInsertSalidaMed = $conexion->prepare($queryInsercionMed);
            if (!$stmtInsertSalidaMed) {
                throw new Exception("Error preparando consulta salidas_almacen: " . $conexion->error);
            }
            $salioMed = $area;
            $stmtInsertSalidaMed->bind_param(
                'isdiss',
                $itemIdMed,
                $fechaActualMed,
                $cantidadLoteMed,
                $salidaCostsuMed,
                $id_usua,
                $fechaActualMed,
                $salioMed
            );
            if (!$stmtInsertSalidaMed->execute()) {
                throw new Exception("Error insertando en salidas_almacen: " . $stmtInsertSalidaMed->error);
            }
            $stmtInsertSalidaMed->close();
            file_put_contents('error_log.txt', date('Y-m-d H:i:s') . " - Insertado en salidas_almacen para item_id=$itemIdMed\n", FILE_APPEND);

            // Insert into dat_ctapac
            $insertDatCtapacQueryMed = "
                INSERT INTO dat_ctapac (
                    id_atencion, prod_serv, insumo, cta_fec, cta_cant, cta_tot, id_usua, cta_activo, centro_cto
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ";
            $stmtInsertDatCtapacMed = $conexion->prepare($insertDatCtapacQueryMed);
            if (!$stmtInsertDatCtapacMed) {
                throw new Exception("Error preparando consulta dat_ctapac: " . $conexion->error);
            }
            $prodServMed = 'M';
            $ctaActivoMed = 'SI';
            $ctaTotMed = $salidaCostsuMed * $cantidadLoteMed;
            $stmtInsertDatCtapacMed->bind_param(
                'isssddiss',
                $Id_AtencionMed,
                $prodServMed,
                $itemIdMed,
                $fechaActualMed,
                $cantidadLoteMed,
                $ctaTotMed,
                $id_usua,
                $ctaActivoMed,
                $area
            );
            if (!$stmtInsertDatCtapacMed->execute()) {
                throw new Exception("Error insertando en dat_ctapac: " . $stmtInsertDatCtapacMed->error);
            }
            $stmtInsertDatCtapacMed->close();
            file_put_contents('error_log.txt', date('Y-m-d H:i:s') . " - Insertado en dat_ctapac para item_id=$itemIdMed\n", FILE_APPEND);

            // Update existencias_almacen
            $updateExistenciasQueryMed = "UPDATE existencias_almacen SET existe_qty = existe_qty - ?, existe_fecha = ?, existe_salidas = existe_salidas + ? WHERE item_id = ?";
            $stmtUpdateExistenciasMed = $conexion->prepare($updateExistenciasQueryMed);
            if (!$stmtUpdateExistenciasMed) {
                throw new Exception("Error preparando consulta existencias_almacen: " . $conexion->error);
            }
            $stmtUpdateExistenciasMed->bind_param('isii', $cantidadLoteMed, $fechaActualMed, $cantidadLoteMed, $itemIdMed);
            if (!$stmtUpdateExistenciasMed->execute()) {
                throw new Exception("Error actualizando existencias_almacen: " . $stmtUpdateExistenciasMed->error);
            }
            $stmtUpdateExistenciasMed->close();
            file_put_contents('error_log.txt', date('Y-m-d H:i:s') . " - Actualizado existencias_almacen para item_id=$itemIdMed\n", FILE_APPEND);
        }

        // Fetch updated items surtidos (medicamentos)
        $sqlSurtidosMed = "
            SELECT
                dc.id_ctapac,
                CONCAT(p.nom_pac, ' ', p.papell, ' ', p.sapell) AS nombre_paciente,
                CONCAT(ia.item_name, ', ', ia.item_grams) AS item_name,
                dc.cta_cant,
                dc.cta_tot
            FROM
                dat_ctapac dc
            INNER JOIN
                dat_ingreso di ON dc.id_atencion = di.id_atencion
            INNER JOIN
                paciente p ON p.Id_exp = di.Id_exp
            INNER JOIN
                item_almacen ia ON dc.insumo = ia.item_id
            WHERE
                dc.cta_activo = 'SI'
                AND di.id_atencion = ?
            ORDER BY
                dc.cta_fec DESC
        ";
        $stmtSurtidosMed = $conexion->prepare($sqlSurtidosMed);
        if (!$stmtSurtidosMed) {
            throw new Exception("Error preparando consulta dat_ctapac: " . $conexion->error);
        }
        $stmtSurtidosMed->bind_param('i', $Id_AtencionMed);
        $stmtSurtidosMed->execute();
        $resultSurtidosMed = $stmtSurtidosMed->get_result();

        $tablaSurtidosMed = '<p class="text-center">No hay medicamentos surtidos para el paciente seleccionado.</p>';
        if ($resultSurtidosMed && $resultSurtidosMed->num_rows > 0) {
            $tablaSurtidosMed = '<table class="table table-bordered table-striped"><thead class="thead-dark"><tr><th>Paciente</th><th>Medicamento</th><th>Cantidad</th><th>Total</th><th>Acciones</th></tr></thead><tbody>';
            while ($row = $resultSurtidosMed->fetch_assoc()) {
                $tablaSurtidosMed .= "<tr><td>" . htmlspecialchars($row['nombre_paciente']) . "</td><td>" . htmlspecialchars($row['item_name']) . "</td><td>" . htmlspecialchars($row['cta_cant']) . "</td><td>" . htmlspecialchars($row['cta_tot']) . "</td><td><form class='eliminar-surtido-form' data-id-ctapac='{$row['id_ctapac']}'><input type='hidden' name='id_ctapac' value='{$row['id_ctapac']}'><input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'><button type='submit' class='btn btn-danger'>Eliminar</button></form></td></tr>";
            }
            $tablaSurtidosMed .= '</tbody></table>';
        }
        $stmtSurtidosMed->close();

        // Commit transaction and clear session
        $conexion->commit();
        unset($_SESSION['medicamento_seleccionadoMed']);
        file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - Medicamentos procesados correctamente' . "\n", FILE_APPEND);
        ob_end_clean();
        echo json_encode(['success' => true, 'message' => 'Medicamentos agregados correctamente', 'data' => ['tablaSurtidosMed' => $tablaSurtidosMed]]);
    } catch (Exception $e) {
        $conexion->rollback();
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Error al agregar los medicamentos: ' . $e->getMessage()]);
        file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - enviar_medicamentosMed: ' . $e->getMessage() . "\n", FILE_APPEND);
    }
    break;

    default:
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        break;
}
