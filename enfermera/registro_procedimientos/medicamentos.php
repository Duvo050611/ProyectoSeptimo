<?php
session_start();
require_once '../../conexionbd.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'data' => []];

// Validar sesión
if (!isset($_SESSION['login']) || !is_array($_SESSION['login']) || !isset($_SESSION['login']['id_usua']) || !isset($_SESSION['pac'])) {
    $response['message'] = 'Sesión no válida';
    echo json_encode($response);
    exit;
}

$usuario = $_SESSION['login'];
$id_usua = (int)$_SESSION['login']['id_usua'];
$id_atencion = (int)$_SESSION['pac'];

// Validar CSRF token
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $response['message'] = 'Token CSRF inválido';
    echo json_encode($response);
    exit;
}

// Fetch patient data
$sql_pac = "SELECT p.Id_exp, CONCAT(p.nom_pac, ' ', p.papell, ' ', p.sapell) AS nombre_paciente 
            FROM paciente p 
            INNER JOIN dat_ingreso di ON p.Id_exp = di.Id_exp 
            WHERE di.id_atencion = ?";
$stmt_pac = $conexion->prepare($sql_pac);
if (!$stmt_pac) {
    $response['message'] = 'Error preparando consulta de paciente: ' . $conexion->error;
    echo json_encode($response);
    exit;
}
$stmt_pac->bind_param('i', $id_atencion);
$stmt_pac->execute();
$result_pac = $stmt_pac->get_result();
$pac_data = $result_pac->fetch_assoc();
$stmt_pac->close();

if (!$pac_data) {
    $response['message'] = 'Paciente no encontrado';
    echo json_encode($response);
    exit;
}
$nombre_paciente = $pac_data['nombre_paciente'];
$id_exp = $pac_data['Id_exp'];

// Fetch medication options for dropdown
$sql_medicamentos = "SELECT item_id, CONCAT(item_name, ', ', item_comercial, ', ', item_grams, ', ', contenido) AS item_name, contenido 
                     FROM item_almacen 
                     WHERE activo = 'SI' 
                     ORDER BY item_name";
$result_medicamentos = $conexion->query($sql_medicamentos);
$medicamentosOptions = '';
if ($result_medicamentos && $result_medicamentos->num_rows > 0) {
    while ($row = $result_medicamentos->fetch_assoc()) {
        $medicamentosOptions .= "<option value='{$row['item_id']}' data-unidad='{$row['contenido']}'>" . htmlspecialchars($row['item_name']) . "</option>";
    }
} else {
    $medicamentosOptions = "<option value='' disabled>No hay medicamentos disponibles</option>";
}

// Handle medication selection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'select_medicamento') {
    if (!isset($_POST['medicamento']) || !is_numeric($_POST['medicamento'])) {
        $response['message'] = 'ID de medicamento inválido';
        echo json_encode($response);
        exit;
    }

    $itemId = intval($_POST['medicamento']);
    $sqlLotes = "
        SELECT 
            ea.existe_id, ea.existe_lote, ea.existe_caducidad, ea.existe_qty
        FROM 
            existencias_almacen ea
        WHERE 
            ea.item_id = ? AND ea.existe_qty > 0
        ORDER BY 
            ea.existe_caducidad ASC
    ";
    $stmt = $conexion->prepare($sqlLotes);
    if (!$stmt) {
        $response['message'] = 'Error en la preparación de la consulta: ' . $conexion->error;
        echo json_encode($response);
        exit;
    }

    $stmt->bind_param('i', $itemId);
    if (!$stmt->execute()) {
        $response['message'] = 'Error al ejecutar la consulta: ' . $stmt->error;
        $stmt->close();
        echo json_encode($response);
        exit;
    }

    $resultLotes = $stmt->get_result();
    $lotesOptions = '';
    if ($resultLotes && $resultLotes->num_rows > 0) {
        while ($lote = $resultLotes->fetch_assoc()) {
            $lotesOptions .= "<option value='{$lote['existe_id']}|{$lote['existe_lote']}|$itemId' data-caducidad='{$lote['existe_caducidad']}' data-cantidad='{$lote['existe_qty']}'>
                {$lote['existe_lote']} / {$lote['existe_caducidad']} / {$lote['existe_qty']}
            </option>";
        }
    } else {
        $lotesOptions = "<option value='' disabled>No hay lotes disponibles</option>";
    }
    $stmt->close();

    $sqlTotalExistencias = "
        SELECT SUM(ea.existe_qty) AS total_existencias
        FROM existencias_almacen ea
        WHERE ea.item_id = ?
    ";
    $stmt = $conexion->prepare($sqlTotalExistencias);
    if (!$stmt) {
        $response['message'] = 'Error en la preparación de la consulta de existencias: ' . $conexion->error;
        echo json_encode($response);
        exit;
    }

    $stmt->bind_param('i', $itemId);
    $stmt->execute();
    $resultTotalExistencias = $stmt->get_result();
    $totalExistencias = 0;
    if ($resultTotalExistencias && $resultTotalExistencias->num_rows > 0) {
        $row = $resultTotalExistencias->fetch_assoc();
        $totalExistencias = $row['total_existencias'] ?? 0;
    }
    $stmt->close();

    // Fetch unit for the selected medication
    $sqlUnidad = "SELECT contenido FROM item_almacen WHERE item_id = ?";
    $stmtUnidad = $conexion->prepare($sqlUnidad);
    if (!$stmtUnidad) {
        $response['message'] = 'Error en la preparación de la consulta de unidad: ' . $conexion->error;
        echo json_encode($response);
        exit;
    }
    $stmtUnidad->bind_param('i', $itemId);
    $stmtUnidad->execute();
    $resultUnidad = $stmtUnidad->get_result();
    $unidad = $resultUnidad->fetch_assoc()['contenido'] ?? 'Unidad';
    $stmtUnidad->close();

    $response['success'] = true;
    $response['data'] = [
        'lotesOptions' => $lotesOptions,
        'totalExistencias' => $totalExistencias,
        'unidad' => $unidad
    ];
    echo json_encode($response);
    exit;
}

// Handle patient selection for dispensed medications
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'select_paciente') {
    $sqlSurtidos = "
        SELECT 
            dc.id_ctapac,
            CONCAT(p.nom_pac, ' ', p.papell, ' ', p.sapell) AS nombre_paciente,
            CONCAT(ia.item_name, ', ', ia.item_comercial, ', ', ia.item_grams, ', ', ia.contenido) AS item_name,
            dc.existe_lote,
            dc.existe_caducidad,
            dc.cta_cant,
            dc.cta_tot,
            ia.contenido
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
    if (!$stmtSurtidos) {
        $response['message'] = 'Error preparando consulta dat_ctapac: ' . $conexion->error;
        echo json_encode($response);
        exit;
    }
    $stmtSurtidos->bind_param('i', $id_atencion);
    $stmtSurtidos->execute();
    $resultSurtidos = $stmtSurtidos->get_result();

    $tablaSurtidos = '';
    if ($resultSurtidos && $resultSurtidos->num_rows > 0) {
        $tablaSurtidos .= "<table class='table table-bordered table-striped'>";
        $tablaSurtidos .= "<thead class='thead-dark'>
            <tr>
                <th>Paciente</th>
                <th>Medicamento</th>
                <th>Lote</th>
                <th>Caducidad</th>
                <th>Cantidad</th>
                <th>Unidad</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead><tbody>";
        while ($row = $resultSurtidos->fetch_assoc()) {
            $tablaSurtidos .= "<tr>";
            $tablaSurtidos .= "<td>" . htmlspecialchars($row['nombre_paciente']) . "</td>";
            $tablaSurtidos .= "<td>" . htmlspecialchars($row['item_name']) . "</td>";
            $tablaSurtidos .= "<td>" . htmlspecialchars($row['existe_lote']) . "</td>";
            $tablaSurtidos .= "<td>" . htmlspecialchars($row['existe_caducidad']) . "</td>";
            $tablaSurtidos .= "<td>" . htmlspecialchars($row['cta_cant']) . "</td>";
            $tablaSurtidos .= "<td>" . htmlspecialchars($row['contenido']) . "</td>";
            $tablaSurtidos .= "<td>" . htmlspecialchars($row['cta_tot']) . "</td>";
            $tablaSurtidos .= "<td>
                <form action='' method='post' class='eliminar-surtido-form' data-id-ctapac='{$row['id_ctapac']}'>
                    <input type='hidden' name='action' value='eliminar_surtido'>
                    <input type='hidden' name='id_ctapac' value='{$row['id_ctapac']}'>
                    <input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>
                    <button type='submit' class='btn btn-danger'>Eliminar</button>
                </form>
            </td>";
            $tablaSurtidos .= "</tr>";
        }
        $tablaSurtidos .= "</tbody></table>";
    } else {
        $tablaSurtidos = "<p class='text-center'>No hay medicamentos surtidos para el paciente seleccionado.</p>";
    }
    $stmtSurtidos->close();

    $response['success'] = true;
    $response['data'] = ['tablaSurtidos' => $tablaSurtidos];
    echo json_encode($response);
    exit;
}

// Handle adding a medication
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'agregar_medicamento') {
    if (!isset($_POST['medicamento']) || !isset($_POST['lote']) || !isset($_POST['cantidad']) || !isset($_POST['unidad'])) {
        $response['message'] = 'Datos incompletos';
        echo json_encode($response);
        exit;
    }

    list($existeId, $nombreLote, $itemId) = explode('|', $_POST['lote']);
    $itemId = intval($itemId);
    $cantidad = intval($_POST['cantidad']);
    $unidad = $_POST['unidad'];

    $sqlMedicamentoNombrePrecio = "SELECT CONCAT(item_name, ', ', item_comercial, ', ', item_grams, ', ', contenido) AS item_name, item_price, contenido 
                                  FROM item_almacen 
                                  WHERE item_id = ?";
    $stmtMedicamento = $conexion->prepare($sqlMedicamentoNombrePrecio);
    if (!$stmtMedicamento) {
        $response['message'] = 'Error en la preparación de la consulta de medicamento: ' . $conexion->error;
        echo json_encode($response);
        exit;
    }
    $stmtMedicamento->bind_param('i', $itemId);
    $stmtMedicamento->execute();
    $stmtMedicamento->bind_result($nombreMedicamento, $precioMedicamento, $contenido);
    $stmtMedicamento->fetch();
    $stmtMedicamento->close();

    if (!$nombreMedicamento) {
        $response['message'] = 'Medicamento no encontrado';
        echo json_encode($response);
        exit;
    }

    if ($unidad !== $contenido) {
        $response['message'] = 'Unidad inválida para el medicamento seleccionado';
        echo json_encode($response);
        exit;
    }

    if (!isset($_SESSION['medicamento_seleccionado'])) {
        $_SESSION['medicamento_seleccionado'] = [];
    }
    $_SESSION['medicamento_seleccionado'][] = [
        'paciente' => $nombre_paciente,
        'item_id' => $itemId,
        'medicamento' => $nombreMedicamento,
        'lote' => $nombreLote,
        'cantidad' => $cantidad,
        'existe_id' => $existeId,
        'id_atencion' => $id_atencion,
        'precio' => $precioMedicamento,
        'unidad' => $unidad
    ];

    $tabla = '';
    if (!empty($_SESSION['medicamento_seleccionado'])) {
        $tabla .= "<table class='table table-bordered table-striped'>";
        $tabla .= "<thead class='thead-dark'>
                    <tr>
                        <th>Paciente</th>
                        <th>Medicamento</th>
                        <th>Lote</th>
                        <th>Cantidad</th>
                        <th>Unidad</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead><tbody>";
        foreach ($_SESSION['medicamento_seleccionado'] as $index => $medicamento) {
            $tabla .= "<tr>";
            $tabla .= "<td>" . htmlspecialchars($medicamento['paciente']) . "</td>";
            $tabla .= "<td>" . htmlspecialchars($medicamento['medicamento']) . "</td>";
            $tabla .= "<td>" . htmlspecialchars($medicamento['lote']) . "</td>";
            $tabla .= "<td>" . htmlspecialchars($medicamento['cantidad']) . "</td>";
            $tabla .= "<td>" . htmlspecialchars($medicamento['unidad']) . "</td>";
            $tabla .= "<td>" . htmlspecialchars($medicamento['precio']) . "</td>";
            $tabla .= "<td>
                        <form action='' method='post' class='eliminar-form' data-index='$index'>
                            <input type='hidden' name='action' value='eliminar_medicamento'>
                            <input type='hidden' name='eliminar_index' value='$index'>
                            <input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>
                            <button type='submit' class='btn btn-danger'>Eliminar</button>
                        </form>
                    </td>";
            $tabla .= "</tr>";
        }
        $tabla .= "</tbody></table>";
    } else {
        $tabla = "<p class='text-center'>No hay medicamentos seleccionados.</p>";
    }

    $response['success'] = true;
    $response['data'] = ['tabla' => $tabla];
    echo json_encode($response);
    exit;
}

// Handle deletion of a selected medication
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'eliminar_medicamento') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $response['message'] = 'Token CSRF inválido';
        echo json_encode($response);
        exit;
    }

    if (!isset($_POST['eliminar_index']) || !is_numeric($_POST['eliminar_index'])) {
        $response['message'] = 'Índice inválido';
        echo json_encode($response);
        exit;
    }

    $index = intval($_POST['eliminar_index']);
    if (isset($_SESSION['medicamento_seleccionado'][$index])) {
        unset($_SESSION['medicamento_seleccionado'][$index]);
        $_SESSION['medicamento_seleccionado'] = array_values($_SESSION['medicamento_seleccionado']);
    } else {
        $response['message'] = 'Medicamento no encontrado en la lista';
        echo json_encode($response);
        exit;
    }

    $tabla = '';
    if (!empty($_SESSION['medicamento_seleccionado'])) {
        $tabla .= "<table class='table table-bordered table-striped'>";
        $tabla .= "<thead class='thead-dark'>
                    <tr>
                        <th>Paciente</th>
                        <th>Medicamento</th>
                        <th>Lote</th>
                        <th>Cantidad</th>
                        <th>Unidad</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead><tbody>";
        foreach ($_SESSION['medicamento_seleccionado'] as $index => $medicamento) {
            $tabla .= "<tr>";
            $tabla .= "<td>" . htmlspecialchars($medicamento['paciente']) . "</td>";
            $tabla .= "<td>" . htmlspecialchars($medicamento['medicamento']) . "</td>";
            $tabla .= "<td>" . htmlspecialchars($medicamento['lote']) . "</td>";
            $tabla .= "<td>" . htmlspecialchars($medicamento['cantidad']) . "</td>";
            $tabla .= "<td>" . htmlspecialchars($medicamento['unidad']) . "</td>";
            $tabla .= "<td>" . htmlspecialchars($medicamento['precio']) . "</td>";
            $tabla .= "<td>
                        <form action='' method='post' class='eliminar-form' data-index='$index'>
                            <input type='hidden' name='action' value='eliminar_medicamento'>
                            <input type='hidden' name='eliminar_index' value='$index'>
                            <input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>
                            <button type='submit' class='btn btn-danger'>Eliminar</button>
                        </form>
                    </td>";
            $tabla .= "</tr>";
        }
        $tabla .= "</tbody></table>";
    } else {
        $tabla = "<p class='text-center'>No hay medicamentos seleccionados.</p>";
    }

    $response['success'] = true;
    $response['message'] = 'Medicamento eliminado con éxito';
    $response['data'] = ['tabla' => $tabla];
    echo json_encode($response);
    exit;
}

// Handle deletion of a dispensed medication
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'eliminar_surtido') {
    if (!isset($_POST['id_ctapac']) || !is_numeric($_POST['id_ctapac'])) {
        $response['message'] = 'ID de cuenta inválido';
        echo json_encode($response);
        exit;
    }

    $idCtapac = intval($_POST['id_ctapac']);

    $conexion->begin_transaction();

    try {
        // Fetch dat_ctapac record
        $sqlCtapac = "
            SELECT 
                dc.insumo, dc.existe_lote, dc.existe_caducidad, dc.cta_cant, dc.id_atencion
            FROM 
                dat_ctapac dc
            WHERE 
                dc.id_ctapac = ? AND dc.cta_activo = 'SI'
        ";
        $stmtCtapac = $conexion->prepare($sqlCtapac);
        if (!$stmtCtapac) {
            throw new Exception("Error preparando consulta dat_ctapac: " . $conexion->error);
        }
        $stmtCtapac->bind_param('i', $idCtapac);
        $stmtCtapac->execute();
        $resultCtapac = $stmtCtapac->get_result();
        if ($resultCtapac->num_rows === 0) {
            throw new Exception("No se encontró el registro con id_ctapac $idCtapac.");
        }
        $ctapacData = $resultCtapac->fetch_assoc();
        $stmtCtapac->close();

        $itemId = $ctapacData['insumo'];
        $loteNombre = $ctapacData['existe_lote'];
        $caducidad = $ctapacData['existe_caducidad'];
        $cantidadLote = $ctapacData['cta_cant'];

        // Find existe_id in existencias_almacen
        $sqlExistencias = "
            SELECT existe_id, existe_qty, existe_salidas
            FROM existencias_almacen
            WHERE item_id = ? AND existe_lote = ? AND existe_caducidad = ?
        ";
        $stmtExistencias = $conexion->prepare($sqlExistencias);
        if (!$stmtExistencias) {
            throw new Exception("Error preparando consulta existencias_almacen: " . $conexion->error);
        }
        $stmtExistencias->bind_param('iss', $itemId, $loteNombre, $caducidad);
        $stmtExistencias->execute();
        $stmtExistencias->bind_result($existeId, $existeQty, $existeSalidas);
        if (!$stmtExistencias->fetch()) {
            throw new Exception("No se encontró el lote en existencias_almacen.");
        }
        $stmtExistencias->close();

        // Update stock
        $nuevaExistenciaQty = $existeQty + $cantidadLote;
        $nuevaExistenciaSalidas = max(0, $existeSalidas - $cantidadLote);
        $fechaActual = date('Y-m-d H:i:s');

        $updateExistenciasQuery = "
            UPDATE existencias_almacen 
            SET existe_qty = ?, existe_fecha = ?, existe_salidas = ? 
            WHERE existe_id = ?
        ";
        $stmtUpdateExistencias = $conexion->prepare($updateExistenciasQuery);
        if (!$stmtUpdateExistencias) {
            throw new Exception("Error preparando consulta existencias_almacen: " . $conexion->error);
        }
        $stmtUpdateExistencias->bind_param('isii', $nuevaExistenciaQty, $fechaActual, $nuevaExistenciaSalidas, $existeId);
        if (!$stmtUpdateExistencias->execute()) {
            throw new Exception("Error actualizando existencias_almacen: " . $stmtUpdateExistencias->error);
        }
        $stmtUpdateExistencias->close();

        // Log deletion in kardex_almacen
        $insertKardex = "
            INSERT INTO kardex_almacen (
                kardex_fecha, item_id, kardex_lote, kardex_caducidad, kardex_inicial, kardex_entradas, kardex_salidas, kardex_qty, 
                kardex_dev_stock, kardex_dev_merma, kardex_movimiento, kardex_destino, id_surte, motivo
            ) 
            VALUES (NOW(), ?, ?, ?, 0, ?, 0, 0, 0, 0, 'Devolución', 'QUIROFANO', ?, 'Eliminación de medicamento surtido')
        ";
        $stmtKardex = $conexion->prepare($insertKardex);
        if (!$stmtKardex) {
            throw new Exception("Error preparando consulta kardex_almacen: " . $conexion->error);
        }
        $stmtKardex->bind_param('issii', $itemId, $loteNombre, $caducidad, $cantidadLote, $id_usua);
        if (!$stmtKardex->execute()) {
            throw new Exception("Error insertando en kardex_almacen: " . $stmtKardex->error);
        }
        $stmtKardex->close();

        // Soft delete dat_ctapac record
        $updateCtapacQuery = "UPDATE dat_ctapac SET cta_activo = 'NO' WHERE id_ctapac = ?";
        $stmtUpdateCtapac = $conexion->prepare($updateCtapacQuery);
        if (!$stmtUpdateCtapac) {
            throw new Exception("Error preparando consulta dat_ctapac: " . $conexion->error);
        }
        $stmtUpdateCtapac->bind_param('i', $idCtapac);
        if (!$stmtUpdateCtapac->execute()) {
            throw new Exception("Error actualizando dat_ctapac: " . $stmtUpdateCtapac->error);
        }
        $stmtUpdateCtapac->close();

        // Fetch updated dispensed items
        $sqlSurtidos = "
            SELECT 
                dc.id_ctapac,
                CONCAT(p.nom_pac, ' ', p.papell, ' ', p.sapell) AS nombre_paciente,
                CONCAT(ia.item_name, ', ', ia.item_comercial, ', ', ia.item_grams, ', ', ia.contenido) AS item_name,
                dc.existe_lote,
                dc.existe_caducidad,
                dc.cta_cant,
                dc.cta_tot,
                ia.contenido
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
        if (!$stmtSurtidos) {
            throw new Exception("Error preparando consulta dat_ctapac: " . $conexion->error);
        }
        $stmtSurtidos->bind_param('i', $id_atencion);
        $stmtSurtidos->execute();
        $resultSurtidos = $stmtSurtidos->get_result();

        $tablaSurtidos = '';
        if ($resultSurtidos && $resultSurtidos->num_rows > 0) {
            $tablaSurtidos .= "<table class='table table-bordered table-striped'>";
            $tablaSurtidos .= "<thead class='thead-dark'>
                <tr>
                    <th>Paciente</th>
                    <th>Medicamento</th>
                    <th>Lote</th>
                    <th>Caducidad</th>
                    <th>Cantidad</th>
                    <th>Unidad</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead><tbody>";
            while ($row = $resultSurtidos->fetch_assoc()) {
                $tablaSurtidos .= "<tr>";
                $tablaSurtidos .= "<td>" . htmlspecialchars($row['nombre_paciente']) . "</td>";
                $tablaSurtidos .= "<td>" . htmlspecialchars($row['item_name']) . "</td>";
                $tablaSurtidos .= "<td>" . htmlspecialchars($row['existe_lote']) . "</td>";
                $tablaSurtidos .= "<td>" . htmlspecialchars($row['existe_caducidad']) . "</td>";
                $tablaSurtidos .= "<td>" . htmlspecialchars($row['cta_cant']) . "</td>";
                $tablaSurtidos .= "<td>" . htmlspecialchars($row['contenido']) . "</td>";
                $tablaSurtidos .= "<td>" . htmlspecialchars($row['cta_tot']) . "</td>";
                $tablaSurtidos .= "<td>
                    <form action='' method='post' class='eliminar-surtido-form' data-id-ctapac='{$row['id_ctapac']}'>
                        <input type='hidden' name='action' value='eliminar_surtido'>
                        <input type='hidden' name='id_ctapac' value='{$row['id_ctapac']}'>
                        <input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>
                        <button type='submit' class='btn btn-danger'>Eliminar</button>
                    </form>
                </td>";
                $tablaSurtidos .= "</tr>";
            }
            $tablaSurtidos .= "</tbody></table>";
        } else {
            $tablaSurtidos = "<p class='text-center'>No hay medicamentos surtidos para el paciente seleccionado.</p>";
        }
        $stmtSurtidos->close();

        $conexion->commit();
        $response['success'] = true;
        $response['data'] = ['tablaSurtidos' => $tablaSurtidos];
        $response['message'] = 'Medicamento surtido eliminado con éxito';
        echo json_encode($response);
    } catch (Exception $e) {
        $conexion->rollback();
        $response['message'] = 'Error: ' . $e->getMessage();
        file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - eliminar_surtido: ' . $e->getMessage() . "\n", FILE_APPEND);
        echo json_encode($response);
    }
    exit;
}

// Handle submission of medications
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_medicamentos']) && $_POST['enviar_medicamentos'] === '1') {
    if (!isset($_SESSION['medicamento_seleccionado']) || empty($_SESSION['medicamento_seleccionado'])) {
        $response['message'] = 'No hay medicamentos seleccionados para enviar';
        echo json_encode($response);
        exit;
    }

    $conexion->begin_transaction();

    try {
        $area = '';
        $sql_area = "SELECT area FROM dat_ingreso WHERE id_atencion = ?";
        $stmt_area = $conexion->prepare($sql_area);
        if (!$stmt_area) {
            throw new Exception("Error preparando consulta de área: " . $conexion->error);
        }
        $stmt_area->bind_param('i', $id_atencion);
        $stmt_area->execute();
        $result_area = $stmt_area->get_result();
        if ($row_area = $result_area->fetch_assoc()) {
            $area = $row_area['area'];
        }
        $stmt_area->close();

        foreach ($_SESSION['medicamento_seleccionado'] as $medicamento) {
            $itemId = $medicamento['item_id'];
            $cantidad = $medicamento['cantidad'];
            $existeId = $medicamento['existe_id'];
            $nombreLote = $medicamento['lote'];
            $precio = $medicamento['precio'];
            $unidad = $medicamento['unidad'];
            $total = $precio * $cantidad;

            // Verify stock
            $sql_existencias = "SELECT existe_qty, existe_caducidad, existe_salidas 
                               FROM existencias_almacen 
                               WHERE existe_id = ?";
            $stmt_existencias = $conexion->prepare($sql_existencias);
            if (!$stmt_existencias) {
                throw new Exception("Error preparando consulta existencias_almacen: " . $conexion->error);
            }
            $stmt_existencias->bind_param('i', $existeId);
            $stmt_existencias->execute();
            $result_existencias = $stmt_existencias->get_result();
            if (!$result_existencias->num_rows) {
                throw new Exception("Lote no encontrado para existe_id: $existeId");
            }
            $existencias = $result_existencias->fetch_assoc();
            $stmt_existencias->close();

            if ($existencias['existe_qty'] < $cantidad) {
                throw new Exception("Stock insuficiente para el lote $nombreLote");
            }

            $caducidad = $existencias['existe_caducidad'];
            $nuevaExistenciaQty = $existencias['existe_qty'] - $cantidad;
            $nuevaExistenciaSalidas = ($existencias['existe_salidas'] ?? 0) + $cantidad;
            $fechaActual = date('Y-m-d H:i:s');

            // Update stock
            $updateExistenciasQuery = "
                UPDATE existencias_almacen 
                SET existe_qty = ?, existe_fecha = ?, existe_salidas = ? 
                WHERE existe_id = ?
            ";
            $stmtUpdateExistencias = $conexion->prepare($updateExistenciasQuery);
            if (!$stmtUpdateExistencias) {
                throw new Exception("Error preparando consulta existencias_almacen: " . $conexion->error);
            }
            $stmtUpdateExistencias->bind_param('isii', $nuevaExistenciaQty, $fechaActual, $nuevaExistenciaSalidas, $existeId);
            if (!$stmtUpdateExistencias->execute()) {
                throw new Exception("Error actualizando existencias_almacen: " . $stmtUpdateExistencias->error);
            }
            $stmtUpdateExistencias->close();

            // Insert into dat_ctapac
            $sqlCtapac = "
                INSERT INTO dat_ctapac (
                    id_atencion, prod_serv, insumo, cta_fec, cta_cant, cta_tot, id_usua, cta_activo, centro_cto, existe_lote, existe_caducidad
                ) VALUES (?, 'M', ?, ?, ?, ?, ?, 'SI', ?, ?, ?)
            ";
            $stmtCtapac = $conexion->prepare($sqlCtapac);
            if (!$stmtCtapac) {
                throw new Exception("Error preparando consulta dat_ctapac: " . $conexion->error);
            }
            $stmtCtapac->bind_param('iisidisss', $id_atencion, $itemId, $fechaActual, $cantidad, $total, $id_usua, $area, $nombreLote, $caducidad);
            if (!$stmtCtapac->execute()) {
                throw new Exception("Error insertando en dat_ctapac: " . $stmtCtapac->error);
            }
            $stmtCtapac->close();

            // Log in kardex_almacen
            $insertKardex = "
                INSERT INTO kardex_almacen (
                    kardex_fecha, item_id, kardex_lote, kardex_caducidad, kardex_inicial, kardex_entradas, kardex_salidas, kardex_qty, 
                    kardex_dev_stock, kardex_dev_merma, kardex_movimiento, kardex_destino, id_surte, motivo
                ) 
                VALUES (NOW(), ?, ?, ?, 0, 0, ?, 0, 0, 0, 'Salida', 'QUIROFANO', ?, 'Surtido a paciente ($unidad)')
            ";
            $stmtKardex = $conexion->prepare($insertKardex);
            if (!$stmtKardex) {
                throw new Exception("Error preparando consulta kardex_almacen: " . $conexion->error);
            }
            $stmtKardex->bind_param('issii', $itemId, $nombreLote, $caducidad, $cantidad, $id_usua);
            if (!$stmtKardex->execute()) {
                throw new Exception("Error insertando en kardex_almacen: " . $stmtKardex->error);
            }
            $stmtKardex->close();
        }

        // Clear selected medications
        $_SESSION['medicamento_seleccionado'] = [];

        // Fetch updated dispensed items
        $sqlSurtidos = "
            SELECT 
                dc.id_ctapac,
                CONCAT(p.nom_pac, ' ', p.papell, ' ', p.sapell) AS nombre_paciente,
                CONCAT(ia.item_name, ', ', ia.item_comercial, ', ', ia.item_grams, ', ', ia.contenido) AS item_name,
                dc.existe_lote,
                dc.existe_caducidad,
                dc.cta_cant,
                dc.cta_tot,
                ia.contenido
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
        if (!$stmtSurtidos) {
            throw new Exception("Error preparando consulta dat_ctapac: " . $conexion->error);
        }
        $stmtSurtidos->bind_param('i', $id_atencion);
        $stmtSurtidos->execute();
        $resultSurtidos = $stmtSurtidos->get_result();

        $tablaSurtidos = '';
        if ($resultSurtidos && $resultSurtidos->num_rows > 0) {
            $tablaSurtidos .= "<table class='table table-bordered table-striped'>";
            $tablaSurtidos .= "<thead class='thead-dark'>
                <tr>
                    <th>Paciente</th>
                    <th>Medicamento</th>
                    <th>Lote</th>
                    <th>Caducidad</th>
                    <th>Cantidad</th>
                    <th>Unidad</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead><tbody>";
            while ($row = $resultSurtidos->fetch_assoc()) {
                $tablaSurtidos .= "<tr>";
                $tablaSurtidos .= "<td>" . htmlspecialchars($row['nombre_paciente']) . "</td>";
                $tablaSurtidos .= "<td>" . htmlspecialchars($row['item_name']) . "</td>";
                $tablaSurtidos .= "<td>" . htmlspecialchars($row['existe_lote']) . "</td>";
                $tablaSurtidos .= "<td>" . htmlspecialchars($row['existe_caducidad']) . "</td>";
                $tablaSurtidos .= "<td>" . htmlspecialchars($row['cta_cant']) . "</td>";
                $tablaSurtidos .= "<td>" . htmlspecialchars($row['contenido']) . "</td>";
                $tablaSurtidos .= "<td>" . htmlspecialchars($row['cta_tot']) . "</td>";
                $tablaSurtidos .= "<td>
                    <form action='' method='post' class='eliminar-surtido-form' data-id-ctapac='{$row['id_ctapac']}'>
                        <input type='hidden' name='action' value='eliminar_surtido'>
                        <input type='hidden' name='id_ctapac' value='{$row['id_ctapac']}'>
                        <input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>
                        <button type='submit' class='btn btn-danger'>Eliminar</button>
                    </form>
                </td>";
                $tablaSurtidos .= "</tr>";
            }
            $tablaSurtidos .= "</tbody></table>";
        } else {
            $tablaSurtidos = "<p class='text-center'>No hay medicamentos surtidos para el paciente seleccionado.</p>";
        }
        $stmtSurtidos->close();

        $conexion->commit();
        $response['success'] = true;
        $response['data'] = ['tablaSurtidos' => $tablaSurtidos];
        $response['message'] = 'Medicamentos registrados con éxito';
        echo json_encode($response);
    } catch (Exception $e) {
        $conexion->rollback();
        $response['message'] = 'Error: ' . $e->getMessage();
        file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - enviar_medicamentos: ' . $e->getMessage() . "\n", FILE_APPEND);
        echo json_encode($response);
    }
    exit;
}

$response['message'] = 'Acción no válida';
echo json_encode($response);
$conexion->close();
?>