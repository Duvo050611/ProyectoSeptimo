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

// Fetch patient data for consistency
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

// Manejar fetch de medicamentos desde item_almacen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'fetch_medicamentos') {
    $sqlMedicamentos = "
        SELECT item_id, item_name
        FROM item_almacen
        WHERE item_activo = 'SI'
        ORDER BY item_name ASC
    ";
    $stmt = $conexion->prepare($sqlMedicamentos);
    if (!$stmt) {
        $response['message'] = 'Error en la preparación de la consulta de medicamentos: ' . $conexion->error;
        echo json_encode($response);
        exit;
    }

    $stmt->execute();
    $resultMedicamentos = $stmt->get_result();
    $medicamentosOptions = '';
    if ($resultMedicamentos && $resultMedicamentos->num_rows > 0) {
        while ($medicamento = $resultMedicamentos->fetch_assoc()) {
            $medicamentosOptions .= "<option value='{$medicamento['item_id']}'>" . htmlspecialchars($medicamento['item_name']) . "</option>";
        }
    } else {
        $medicamentosOptions = "<option value='' disabled>No hay medicamentos disponibles</option>";
    }
    $stmt->close();

    $response['success'] = true;
    $response['data'] = [
        'medicamentosOptions' => $medicamentosOptions
    ];
    echo json_encode($response);
    exit;
}

// Manejar selección de insumo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'select_insumo') {
    if (!isset($_POST['insumo']) || !is_numeric($_POST['insumo'])) {
        $response['message'] = 'ID de insumo inválido';
        echo json_encode($response);
        exit;
    }

    $itemId = intval($_POST['insumo']);
    $sqlLotes = "
        SELECT 
            ea.existe_lote, ea.existe_caducidad, ea.existe_qty, ea.existe_id
        FROM 
            existencias_almacenq ea
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
        FROM existencias_almacenq ea
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

    $response['success'] = true;
    $response['data'] = [
        'lotesOptions' => $lotesOptions,
        'totalExistencias' => $totalExistencias
    ];
    echo json_encode($response);
    exit;
}

// Manejar selección de paciente (para ítems surtidos)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'select_paciente') {
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
            AND ia.item_type = 'INSUMO'
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
                <th>Insumo</th>
                <th>Lote</th>
                <th>Caducidad</th>
                <th>Cantidad</th>
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
            $tablaSurtidos .= "<td>" . htmlspecialchars($row['cta_tot']) . "</td>";
            $tablaSurtidos .= "<td>
                <form action='' method='post' class='eliminar-surtido-form' data-id-ctapac='{$row['id_ctapac']}'>
                    <input type='hidden' name='id_ctapac' value='{$row['id_ctapac']}'>
                    <input type='hidden' name='action' value='eliminar_surtido'>
                    <input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>
                    <button type='submit' class='btn btn-danger'>Eliminar</button>
                </form>
            </td>";
            $tablaSurtidos .= "</tr>";
        }
        $tablaSurtidos .= "</tbody></table>";
    } else {
        $tablaSurtidos = "<p class='text-center'>No hay insumos surtidos para el paciente seleccionado.</p>";
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
            AND ia.item_type = 'MEDICAMENTO'
        ORDER BY 
            dc.cta_fec DESC
    ";
    $stmtSurtidosMed = $conexion->prepare($sqlSurtidosMed);
    if (!$stmtSurtidosMed) {
        $response['message'] = 'Error preparando consulta dat_ctapac (medicamentos): ' . $conexion->error;
        echo json_encode($response);
        exit;
    }
    $stmtSurtidosMed->bind_param('i', $id_atencion);
    $stmtSurtidosMed->execute();
    $resultSurtidosMed = $stmtSurtidosMed->get_result();

    $tablaSurtidosMed = '';
    if ($resultSurtidosMed && $resultSurtidosMed->num_rows > 0) {
        $tablaSurtidosMed .= "<table class='table table-bordered table-striped'>";
        $tablaSurtidosMed .= "<thead class='thead-dark'>
            <tr>
                <th>Paciente</th>
                <th>Medicamento</th>
                <th>Cantidad</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead><tbody>";
        while ($row = $resultSurtidosMed->fetch_assoc()) {
            $tablaSurtidosMed .= "<tr>";
            $tablaSurtidosMed .= "<td>" . htmlspecialchars($row['nombre_paciente']) . "</td>";
            $tablaSurtidosMed .= "<td>" . htmlspecialchars($row['item_name']) . "</td>";
            $tablaSurtidosMed .= "<td>" . htmlspecialchars($row['cta_cant']) . "</td>";
            $tablaSurtidosMed .= "<td>" . htmlspecialchars($row['cta_tot']) . "</td>";
            $tablaSurtidosMed .= "<td>
                <form action='' method='post' class='eliminar-surtido-form' data-id-ctapac='{$row['id_ctapac']}'>
                    <input type='hidden' name='id_ctapac' value='{$row['id_ctapac']}'>
                    <input type='hidden' name='action' value='eliminar_surtido'>
                    <input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>
                    <button type='submit' class='btn btn-danger'>Eliminar</button>
                </form>
            </td>";
            $tablaSurtidosMed .= "</tr>";
        }
        $tablaSurtidosMed .= "</tbody></table>";
    } else {
        $tablaSurtidosMed = "<p class='text-center'>No hay medicamentos surtidos para el paciente seleccionado.</p>";
    }
    $stmtSurtidosMed->close();

    $response['success'] = true;
    $response['data'] = [
        'tablaSurtidos' => $tablaSurtidos,
        'tablaSurtidosMed' => $tablaSurtidosMed
    ];
    echo json_encode($response);
    exit;
}

// Manejar agregado de insumo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'agregar_insumo') {
    if (!isset($_POST['insumo']) || !isset($_POST['lote_insumo']) || !isset($_POST['cantidad_insumo'])) {
        $response['message'] = 'Datos incompletos para insumo';
        echo json_encode($response);
        exit;
    }

    list($existeId, $nombreLote, $itemId) = explode('|', $_POST['lote_insumo']);
    $itemId = intval($itemId);
    $cantidad = intval($_POST['cantidad_insumo']);

    $sqlInsumoNombrePrecio = "SELECT CONCAT(item_name, ', ', item_grams) AS item_name, item_price FROM item_almacen WHERE item_id = ? AND item_type = 'INSUMO'";
    $stmtInsumo = $conexion->prepare($sqlInsumoNombrePrecio);
    if (!$stmtInsumo) {
        $response['message'] = 'Error en la preparación de la consulta de insumo: ' . $conexion->error;
        echo json_encode($response);
        exit;
    }
    $stmtInsumo->bind_param('i', $itemId);
    $stmtInsumo->execute();
    $stmtInsumo->bind_result($nombreInsumo, $precioInsumo);
    $stmtInsumo->fetch();
    $stmtInsumo->close();

    if (!$nombreInsumo) {
        $response['message'] = 'Insumo no encontrado';
        echo json_encode($response);
        exit;
    }

    if (!isset($_SESSION['medicamento_seleccionado'])) {
        $_SESSION['medicamento_seleccionado'] = [];
    }
    $_SESSION['medicamento_seleccionado'][] = [
        'paciente' => $nombre_paciente,
        'item_id' => $itemId,
        'medicamento' => $nombreInsumo,
        'lote' => $nombreLote,
        'cantidad' => $cantidad,
        'existe_id' => $existeId,
        'id_atencion' => $id_atencion,
        'precio' => $precioInsumo
    ];

    $tabla = '';
    if (!empty($_SESSION['medicamento_seleccionado'])) {
        $tabla .= "<table class='table table-bordered table-striped'>";
        $tabla .= "<thead class='thead-dark'>
                    <tr>
                        <th>Paciente</th>
                        <th>Insumo</th>
                        <th>Lote</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead><tbody>";
        foreach ($_SESSION['medicamento_seleccionado'] as $index => $insumo) {
            $tabla .= "<tr>";
            $tabla .= "<td>" . htmlspecialchars($insumo['paciente']) . "</td>";
            $tabla .= "<td>" . htmlspecialchars($insumo['medicamento']) . "</td>";
            $tabla .= "<td>" . htmlspecialchars($insumo['lote']) . "</td>";
            $tabla .= "<td>" . htmlspecialchars($insumo['cantidad']) . "</td>";
            $tabla .= "<td>" . htmlspecialchars($insumo['precio']) . "</td>";
            $tabla .= "<td>
                        <form action='' method='post' class='eliminar-insumo-form' data-index='$index'>
                            <input type='hidden' name='action' value='eliminar_insumo'>
                            <input type='hidden' name='eliminar_index' value='$index'>
                            <input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>
                            <button type='submit' class='btn btn-danger'>Eliminar</button>
                        </form>
                    </td>";
            $tabla .= "</tr>";
        }
        $tabla .= "</tbody></table>";
    } else {
        $tabla = "<p class='text-center'>No hay insumos seleccionados.</p>";
    }

    $response['success'] = true;
    $response['data'] = ['tabla' => $tabla];
    echo json_encode($response);
    exit;
}

// Manejar agregado de medicamento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'agregar_medicamento') {
    if (!isset($_POST['medicamento']) || !isset($_POST['cantidad_medicamento'])) {
        $response['message'] = 'Datos incompletos para medicamento';
        echo json_encode($response);
        exit;
    }

    $itemId = intval($_POST['medicamento']);
    $cantidad = intval($_POST['cantidad_medicamento']);

    $sqlMedicamentoNombrePrecio = "SELECT CONCAT(item_name, ', ', item_grams) AS item_name, item_price FROM item_almacen WHERE item_id = ? AND item_type = 'MEDICAMENTO'";
    $stmtMedicamento = $conexion->prepare($sqlMedicamentoNombrePrecio);
    if (!$stmtMedicamento) {
        $response['message'] = 'Error en la preparación de la consulta de medicamento: ' . $conexion->error;
        echo json_encode($response);
        exit;
    }
    $stmtMedicamento->bind_param('i', $itemId);
    $stmtMedicamento->execute();
    $stmtMedicamento->bind_result($nombreMedicamento, $precioMedicamento);
    $stmtMedicamento->fetch();
    $stmtMedicamento->close();

    if (!$nombreMedicamento) {
        $response['message'] = 'Medicamento no encontrado';
        echo json_encode($response);
        exit;
    }

    if (!isset($_SESSION['medicamento_seleccionadoMed'])) {
        $_SESSION['medicamento_seleccionadoMed'] = [];
    }
    $_SESSION['medicamento_seleccionadoMed'][] = [
        'paciente' => $nombre_paciente,
        'item_id' => $itemId,
        'medicamento' => $nombreMedicamento,
        'cantidad' => $cantidad,
        'id_atencion' => $id_atencion,
        'precio' => $precioMedicamento
    ];

    $tablaMed = '';
    if (!empty($_SESSION['medicamento_seleccionadoMed'])) {
        $tablaMed .= "<table class='table table-bordered table-striped'>";
        $tablaMed .= "<thead class='thead-dark'>
                    <tr>
                        <th>Paciente</th>
                        <th>Medicamento</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead><tbody>";
        foreach ($_SESSION['medicamento_seleccionadoMed'] as $index => $medicamento) {
            $tablaMed .= "<tr>";
            $tablaMed .= "<td>" . htmlspecialchars($medicamento['paciente']) . "</td>";
            $tablaMed .= "<td>" . htmlspecialchars($medicamento['medicamento']) . "</td>";
            $tablaMed .= "<td>" . htmlspecialchars($medicamento['cantidad']) . "</td>";
            $tablaMed .= "<td>" . htmlspecialchars($medicamento['precio']) . "</td>";
            $tablaMed .= "<td>
                        <form action='' method='post' class='eliminar-medicamento-form' data-index='$index'>
                            <input type='hidden' name='action' value='eliminar_medicamento'>
                            <input type='hidden' name='eliminar_index' value='$index'>
                            <input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>
                            <button type='submit' class='btn btn-danger'>Eliminar</button>
                        </form>
                    </td>";
            $tablaMed .= "</tr>";
        }
        $tablaMed .= "</tbody></table>";
    } else {
        $tablaMed = "<p class='text-center'>No hay medicamentos seleccionados.</p>";
    }

    $response['success'] = true;
    $response['data'] = ['tablaMed' => $tablaMed];
    echo json_encode($response);
    exit;
}

// Manejar eliminación de insumo (pendiente)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'eliminar_insumo') {
    if (!isset($_POST['eliminar_index']) || !is_numeric($_POST['eliminar_index'])) {
        $response['message'] = 'Índice inválido para insumo';
        echo json_encode($response);
        exit;
    }

    $index = intval($_POST['eliminar_index']);
    if (isset($_SESSION['medicamento_seleccionado'][$index])) {
        unset($_SESSION['medicamento_seleccionado'][$index]);
        $_SESSION['medicamento_seleccionado'] = array_values($_SESSION['medicamento_seleccionado']);
    } else {
        $response['message'] = 'Insumo no encontrado en la lista';
        echo json_encode($response);
        exit;
    }

    $tabla = '';
    if (!empty($_SESSION['medicamento_seleccionado'])) {
        $tabla .= "<table class='table table-bordered table-striped'>";
        $tabla .= "<thead class='thead-dark'>
                    <tr>
                        <th>Paciente</th>
                        <th>Insumo</th>
                        <th>Lote</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead><tbody>";
        foreach ($_SESSION['medicamento_seleccionado'] as $index => $insumo) {
            $tabla .= "<tr>";
            $tabla .= "<td>" . htmlspecialchars($insumo['paciente']) . "</td>";
            $tabla .= "<td>" . htmlspecialchars($insumo['medicamento']) . "</td>";
            $tabla .= "<td>" . htmlspecialchars($insumo['lote']) . "</td>";
            $tabla .= "<td>" . htmlspecialchars($insumo['cantidad']) . "</td>";
            $tabla .= "<td>" . htmlspecialchars($insumo['precio']) . "</td>";
            $tabla .= "<td>
                        <form action='' method='post' class='eliminar-insumo-form' data-index='$index'>
                            <input type='hidden' name='action' value='eliminar_insumo'>
                            <input type='hidden' name='eliminar_index' value='$index'>
                            <input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>
                            <button type='submit' class='btn btn-danger'>Eliminar</button>
                        </form>
                    </td>";
            $tabla .= "</tr>";
        }
        $tabla .= "</tbody></table>";
    } else {
        $tabla = "<p class='text-center'>No hay insumos seleccionados.</p>";
    }

    $response['success'] = true;
    $response['message'] = 'Insumo eliminado con éxito';
    $response['data'] = ['tabla' => $tabla];
    echo json_encode($response);
    exit;
}

// Manejar eliminación de medicamento (pendiente)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'eliminar_medicamento') {
    if (!isset($_POST['eliminar_index']) || !is_numeric($_POST['eliminar_index'])) {
        $response['message'] = 'Índice inválido para medicamento';
        echo json_encode($response);
        exit;
    }

    $index = intval($_POST['eliminar_index']);
    if (isset($_SESSION['medicamento_seleccionadoMed'][$index])) {
        unset($_SESSION['medicamento_seleccionadoMed'][$index]);
        $_SESSION['medicamento_seleccionadoMed'] = array_values($_SESSION['medicamento_seleccionadoMed']);
    } else {
        $response['message'] = 'Medicamento no encontrado en la lista';
        echo json_encode($response);
        exit;
    }

    $tablaMed = '';
    if (!empty($_SESSION['medicamento_seleccionadoMed'])) {
        $tablaMed .= "<table class='table table-bordered table-striped'>";
        $tablaMed .= "<thead class='thead-dark'>
                    <tr>
                        <th>Paciente</th>
                        <th>Medicamento</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead><tbody>";
        foreach ($_SESSION['medicamento_seleccionadoMed'] as $index => $medicamento) {
            $tablaMed .= "<tr>";
            $tablaMed .= "<td>" . htmlspecialchars($medicamento['paciente']) . "</td>";
            $tablaMed .= "<td>" . htmlspecialchars($medicamento['medicamento']) . "</td>";
            $tablaMed .= "<td>" . htmlspecialchars($medicamento['cantidad']) . "</td>";
            $tablaMed .= "<td>" . htmlspecialchars($medicamento['precio']) . "</td>";
            $tablaMed .= "<td>
                        <form action='' method='post' class='eliminar-medicamento-form' data-index='$index'>
                            <input type='hidden' name='action' value='eliminar_medicamento'>
                            <input type='hidden' name='eliminar_index' value='$index'>
                            <input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>
                            <button type='submit' class='btn btn-danger'>Eliminar</button>
                        </form>
                    </td>";
            $tablaMed .= "</tr>";
        }
        $tablaMed .= "</tbody></table>";
    } else {
        $tablaMed = "<p class='text-center'>No hay medicamentos seleccionados.</p>";
    }

    $response['success'] = true;
    $response['message'] = 'Medicamento eliminado con éxito';
    $response['data'] = ['tablaMed' => $tablaMed];
    echo json_encode($response);
    exit;
}

// Manejar eliminación de ítem surtido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'eliminar_surtido') {
    if (!isset($_POST['id_ctapac']) || !is_numeric($_POST['id_ctapac'])) {
        $response['message'] = 'ID de cuenta inválido';
        echo json_encode($response);
        exit;
    }

    $idCtapac = intval($_POST['id_ctapac']);

    $conexion->begin_transaction();

    try {
        // Fetch the dat_ctapac record to get details for stock and kardex updates
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

        // Determine if the item is an insumo or medicamento
        $sqlItemType = "SELECT item_type FROM item_almacen WHERE item_id = ?";
        $stmtItemType = $conexion->prepare($sqlItemType);
        if (!$stmtItemType) {
            throw new Exception("Error preparando consulta item_almacen: " . $conexion->error);
        }
        $stmtItemType->bind_param('i', $itemId);
        $stmtItemType->execute();
        $stmtItemType->bind_result($itemType);
        $stmtItemType->fetch();
        $stmtItemType->close();

        $isInsumo = ($itemType === 'INSUMO');
        $tableExistencias = $isInsumo ? 'existencias_almacenq' : 'existencias_almacen';
        $tableKardex = $isInsumo ? 'kardex_almacenq' : 'kardex_almacen';

        // Fetch stock details
        $sqlExistencias = "
            SELECT existe_id, existe_qty, existe_salidas
            FROM $tableExistencias
            WHERE item_id = ? AND existe_lote = ? AND existe_caducidad = ?
        ";
        $stmtExistencias = $conexion->prepare($sqlExistencias);
        if (!$stmtExistencias) {
            throw new Exception("Error preparando consulta $tableExistencias: " . $conexion->error);
        }
        $stmtExistencias->bind_param('iss', $itemId, $loteNombre, $caducidad);
        $stmtExistencias->execute();
        $stmtExistencias->bind_result($existeId, $existeQty, $existeSalidas);
        if (!$stmtExistencias->fetch()) {
            throw new Exception("No se encontró el lote en $tableExistencias.");
        }
        $stmtExistencias->close();

        // Update stock
        $nuevaExistenciaQty = $existeQty + $cantidadLote;
        $nuevaExistenciaSalidas = max(0, $existeSalidas - $cantidadLote);
        $fechaActual = date('Y-m-d H:i:s');

        $updateExistenciasQuery = "
            UPDATE $tableExistencias 
            SET existe_qty = ?, existe_fecha = ?, existe_salidas = ? 
            WHERE existe_id = ?
        ";
        $stmtUpdateExistencias = $conexion->prepare($updateExistenciasQuery);
        if (!$stmtUpdateExistencias) {
            throw new Exception("Error preparando consulta $tableExistencias: " . $conexion->error);
        }
        $stmtUpdateExistencias->bind_param('isii', $nuevaExistenciaQty, $fechaActual, $nuevaExistenciaSalidas, $existeId);
        if (!$stmtUpdateExistencias->execute()) {
            throw new Exception("Error actualizando $tableExistencias: " . $stmtUpdateExistencias->error);
        }
        $stmtUpdateExistencias->close();

        // Log deletion in kardex
        $insertKardex = "
            INSERT INTO $tableKardex (
                kardex_fecha, item_id, kardex_lote, kardex_caducidad, kardex_inicial, kardex_entradas, kardex_salidas, kardex_qty, 
                kardex_dev_stock, kardex_dev_merma, kardex_movimiento, kardex_destino, id_surte, motivo
            ) 
            VALUES (NOW(), ?, ?, ?, 0, ?, 0, 0, 0, 0, 'Devolución', 'QUIROFANO', ?, 'Eliminación de ítem surtido')
        ";
        $stmtKardex = $conexion->prepare($insertKardex);
        if (!$stmtKardex) {
            throw new Exception("Error preparando consulta $tableKardex: " . $conexion->error);
        }
        $stmtKardex->bind_param('issii', $itemId, $loteNombre, $caducidad, $cantidadLote, $id_usua);
        if (!$stmtKardex->execute()) {
            throw new Exception("Error insertando en $tableKardex: " . $stmtKardex->error);
        }
        $stmtKardex->close();

        // Soft delete the dat_ctapac record
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

        // Fetch updated items surtidos
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
                AND ia.item_type = 'INSUMO'
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
                    <th>Insumo</th>
                    <th>Lote</th>
                    <th>Caducidad</th>
                    <th>Cantidad</th>
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
                $tablaSurtidos .= "<td>" . htmlspecialchars($row['cta_tot']) . "</td>";
                $tablaSurtidos .= "<td>
                    <form action='' method='post' class='eliminar-surtido-form' data-id-ctapac='{$row['id_ctapac']}'>
                        <input type='hidden' name='id_ctapac' value='{$row['id_ctapac']}'>
                        <input type='hidden' name='action' value='eliminar_surtido'>
                        <input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>
                        <button type='submit' class='btn btn-danger'>Eliminar</button>
                    </form>
                </td>";
                $tablaSurtidos .= "</tr>";
            }
            $tablaSurtidos .= "</tbody></table>";
        } else {
            $tablaSurtidos = "<p class='text-center'>No hay insumos surtidos para el paciente seleccionado.</p>";
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
                AND ia.item_type = 'MEDICAMENTO'
            ORDER BY 
                dc.cta_fec DESC
        ";
        $stmtSurtidosMed = $conexion->prepare($sqlSurtidosMed);
        if (!$stmtSurtidosMed) {
            throw new Exception("Error preparando consulta dat_ctapac: " . $conexion->error);
        }
        $stmtSurtidosMed->bind_param('i', $id_atencion);
        $stmtSurtidosMed->execute();
        $resultSurtidosMed = $stmtSurtidosMed->get_result();

        $tablaSurtidosMed = '';
        if ($resultSurtidosMed && $resultSurtidosMed->num_rows > 0) {
            $tablaSurtidosMed .= "<table class='table table-bordered table-striped'>";
            $tablaSurtidosMed .= "<thead class='thead-dark'>
                <tr>
                    <th>Paciente</th>
                    <th>Medicamento</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead><tbody>";
            while ($row = $resultSurtidosMed->fetch_assoc()) {
                $tablaSurtidosMed .= "<tr>";
                $tablaSurtidosMed .= "<td>" . htmlspecialchars($row['nombre_paciente']) . "</td>";
                $tablaSurtidosMed .= "<td>" . htmlspecialchars($row['item_name']) . "</td>";
                $tablaSurtidosMed .= "<td>" . htmlspecialchars($row['cta_cant']) . "</td>";
                $tablaSurtidosMed .= "<td>" . htmlspecialchars($row['cta_tot']) . "</td>";
                $tablaSurtidosMed .= "<td>
                    <form action='' method='post' class='eliminar-surtido-form' data-id-ctapac='{$row['id_ctapac']}'>
                        <input type='hidden' name='id_ctapac' value='{$row['id_ctapac']}'>
                        <input type='hidden' name='action' value='eliminar_surtido'>
                        <input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>
                        <button type='submit' class='btn btn-danger'>Eliminar</button>
                    </form>
                </td>";
                $tablaSurtidosMed .= "</tr>";
            }
            $tablaSurtidosMed .= "</tbody></table>";
        } else {
            $tablaSurtidosMed = "<p class='text-center'>No hay medicamentos surtidos para el paciente seleccionado.</p>";
        }
        $stmtSurtidosMed->close();

        $conexion->commit();
        $response['success'] = true;
        $response['data'] = [
            'tablaSurtidos' => $tablaSurtidos,
            'tablaSurtidosMed' => $tablaSurtidosMed
        ];
        echo json_encode($response);
    } catch (Exception $e) {
        $conexion->rollback();
        $response['message'] = 'Error: ' . $e->getMessage();
        file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - eliminar_surtido: ' . $e->getMessage() . "\n", FILE_APPEND);
        echo json_encode($response);
    }
    exit;
}

// Manejar envío de insumos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_medicamentos']) && $_POST['enviar_medicamentos'] === '1') {
    if (!isset($_SESSION['medicamento_seleccionado']) || empty($_SESSION['medicamento_seleccionado'])) {
        $response['message'] = 'No hay insumos seleccionados para enviar';
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

        foreach ($_SESSION['medicamento_seleccionado'] as $insumo) {
            $itemId = $insumo['item_id'];
            $cantidad = $insumo['cantidad'];
            $existeId = $insumo['existe_id'];
            $nombreLote = $insumo['lote'];
            $precio = $insumo['precio'];
            $total = $precio * $cantidad;

            // Verify stock availability
            $sql_existencias = "SELECT existe_qty, existe_caducidad, existe_salidas FROM existencias_almacenq WHERE existe_id = ?";
            $stmt_existencias = $conexion->prepare($sql_existencias);
            if (!$stmt_existencias) {
                throw new Exception("Error preparando consulta existencias_almacenq: " . $conexion->error);
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
                UPDATE existencias_almacenq 
                SET existe_qty = ?, existe_fecha = ?, existe_salidas = ? 
                WHERE existe_id = ?
            ";
            $stmtUpdateExistencias = $conexion->prepare($updateExistenciasQuery);
            if (!$stmtUpdateExistencias) {
                throw new Exception("Error preparando consulta existencias_almacenq: " . $conexion->error);
            }
            $stmtUpdateExistencias->bind_param('isii', $nuevaExistenciaQty, $fechaActual, $nuevaExistenciaSalidas, $existeId);
            if (!$stmtUpdateExistencias->execute()) {
                throw new Exception("Error actualizando existencias_almacenq: " . $stmtUpdateExistencias->error);
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

            // Log in kardex_almacenq
            $insertKardex = "
                INSERT INTO kardex_almacenq (
                    kardex_fecha, item_id, kardex_lote, kardex_caducidad, kardex_inicial, kardex_entradas, kardex_salidas, kardex_qty, 
                    kardex_dev_stock, kardex_dev_merma, kardex_movimiento, kardex_destino, id_surte, motivo
                ) 
                VALUES (NOW(), ?, ?, ?, 0, 0, ?, 0, 0, 0, 'Salida', 'QUIROFANO', ?, 'Surtido a paciente')
            ";
            $stmtKardex = $conexion->prepare($insertKardex);
            if (!$stmtKardex) {
                throw new Exception("Error preparando consulta kardex_almacenq: " . $conexion->error);
            }
            $stmtKardex->bind_param('issii', $itemId, $nombreLote, $caducidad, $cantidad, $id_usua);
            if (!$stmtKardex->execute()) {
                throw new Exception("Error insertando en kardex_almacenq: " . $stmtKardex->error);
            }
            $stmtKardex->close();
        }

        // Clear selected insumos
        $_SESSION['medicamento_seleccionado'] = [];

        // Fetch updated items surtidos
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
                AND ia.item_type = 'INSUMO'
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
                    <th>Insumo</th>
                    <th>Lote</th>
                    <th>Caducidad</th>
                    <th>Cantidad</th>
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
                $tablaSurtidos .= "<td>" . htmlspecialchars($row['cta_tot']) . "</td>";
                $tablaSurtidos .= "<td>
                    <form action='' method='post' class='eliminar-surtido-form' data-id-ctapac='{$row['id_ctapac']}'>
                        <input type='hidden' name='id_ctapac' value='{$row['id_ctapac']}'>
                        <input type='hidden' name='action' value='eliminar_surtido'>
                        <input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>
                        <button type='submit' class='btn btn-danger'>Eliminar</button>
                    </form>
                </td>";
                $tablaSurtidos .= "</tr>";
            }
            $tablaSurtidos .= "</tbody></table>";
        } else {
            $tablaSurtidos = "<p class='text-center'>No hay insumos surtidos para el paciente seleccionado.</p>";
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
                AND ia.item_type = 'MEDICAMENTO'
            ORDER BY 
                dc.cta_fec DESC
        ";
        $stmtSurtidosMed = $conexion->prepare($sqlSurtidosMed);
        if (!$stmtSurtidosMed) {
            throw new Exception("Error preparando consulta dat_ctapac: " . $conexion->error);
        }
        $stmtSurtidosMed->bind_param('i', $id_atencion);
        $stmtSurtidosMed->execute();
        $resultSurtidosMed = $stmtSurtidosMed->get_result();

        $tablaSurtidosMed = '';
        if ($resultSurtidosMed && $resultSurtidosMed->num_rows > 0) {
            $tablaSurtidosMed .= "<table class='table table-bordered table-striped'>";
            $tablaSurtidosMed .= "<thead class='thead-dark'>
                <tr>
                    <th>Paciente</th>
                    <th>Medicamento</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead><tbody>";
            while ($row = $resultSurtidosMed->fetch_assoc()) {
                $tablaSurtidosMed .= "<tr>";
                $tablaSurtidosMed .= "<td>" . htmlspecialchars($row['nombre_paciente']) . "</td>";
                $tablaSurtidosMed .= "<td>" . htmlspecialchars($row['item_name']) . "</td>";
                $tablaSurtidosMed .= "<td>" . htmlspecialchars($row['cta_cant']) . "</td>";
                $tablaSurtidosMed .= "<td>" . htmlspecialchars($row['cta_tot']) . "</td>";
                $tablaSurtidosMed .= "<td>
                    <form action='' method='post' class='eliminar-surtido-form' data-id-ctapac='{$row['id_ctapac']}'>
                        <input type='hidden' name='id_ctapac' value='{$row['id_ctapac']}'>
                        <input type='hidden' name='action' value='eliminar_surtido'>
                        <input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>
                        <button type='submit' class='btn btn-danger'>Eliminar</button>
                    </form>
                </td>";
                $tablaSurtidosMed .= "</tr>";
            }
            $tablaSurtidosMed .= "</tbody></table>";
        } else {
            $tablaSurtidosMed = "<p class='text-center'>No hay medicamentos surtidos para el paciente seleccionado.</p>";
        }
        $stmtSurtidosMed->close();

        $conexion->commit();
        $response['success'] = true;
        $response['data'] = [
            'tablaSurtidos' => $tablaSurtidos,
            'tablaSurtidosMed' => $tablaSurtidosMed
        ];
        echo json_encode($response);
    } catch (Exception $e) {
        $conexion->rollback();
        $response['message'] = 'Error: ' . $e->getMessage();
        file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - enviar_medicamentos: ' . $e->getMessage() . "\n", FILE_APPEND);
        echo json_encode($response);
    }
    exit;
}

// Manejar envío de medicamentos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_medicamentosMed']) && $_POST['enviar_medicamentosMed'] === '1') {
    if (!isset($_SESSION['medicamento_seleccionadoMed']) || empty($_SESSION['medicamento_seleccionadoMed'])) {
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

        foreach ($_SESSION['medicamento_seleccionadoMed'] as $medicamento) {
            $itemId = $medicamento['item_id'];
            $cantidad = $medicamento['cantidad'];
            $precio = $medicamento['precio'];
            $total = $precio * $cantidad;

            // Verify stock availability
            $sql_existencias = "SELECT SUM(existe_qty) AS total_qty FROM existencias_almacen WHERE item_id = ?";
            $stmt_existencias = $conexion->prepare($sql_existencias);
            if (!$stmt_existencias) {
                throw new Exception("Error preparando consulta existencias_almacen: " . $conexion->error);
            }
            $stmt_existencias->bind_param('i', $itemId);
            $stmt_existencias->execute();
            $result_existencias = $stmt_existencias->get_result();
            if (!$result_existencias->num_rows) {
                throw new Exception("Medicamento no encontrado: $itemId");
            }
            $existencias = $result_existencias->fetch_assoc();
            $stmt_existencias->close();

            if ($existencias['total_qty'] < $cantidad) {
                throw new Exception("Stock insuficiente para el medicamento ID: $itemId");
            }

            $fechaActual = date('Y-m-d H:i:s');

            // Update stock (reduce quantity from the first available batch)
            $sql_update_existencias = "
                UPDATE existencias_almacen 
                SET existe_qty = existe_qty - ?, existe_fecha = ?, existe_salidas = existe_salidas + ?
                WHERE item_id = ? AND existe_qty >= ?
                LIMIT 1
            ";
            $stmtUpdateExistencias = $conexion->prepare($sql_update_existencias);
            if (!$stmtUpdateExistencias) {
                throw new Exception("Error preparando consulta existencias_almacen: " . $conexion->error);
            }
            $stmtUpdateExistencias->bind_param('isiii', $cantidad, $fechaActual, $cantidad, $itemId, $cantidad);
            if (!$stmtUpdateExistencias->execute()) {
                throw new Exception("Error actualizando existencias_almacen: " . $stmtUpdateExistencias->error);
            }
            $stmtUpdateExistencias->close();

            // Insert into dat_ctapac
            $sqlCtapac = "
                INSERT INTO dat_ctapac (
                    id_atencion, prod_serv, insumo, cta_fec, cta_cant, cta_tot, id_usua, cta_activo, centro_cto
                ) VALUES (?, 'M', ?, ?, ?, ?, ?, 'SI', ?)
            ";
            $stmtCtapac = $conexion->prepare($sqlCtapac);
            if (!$stmtCtapac) {
                throw new Exception("Error preparando consulta dat_ctapac: " . $conexion->error);
            }
            $stmtCtapac->bind_param('iisidis', $id_atencion, $itemId, $fechaActual, $cantidad, $total, $id_usua, $area);
            if (!$stmtCtapac->execute()) {
                throw new Exception("Error insertando en dat_ctapac: " . $stmtCtapac->error);
            }
            $stmtCtapac->close();

            // Log in kardex_almacen
            $insertKardex = "
    INSERT INTO kardex_almacen (
        kardex_fecha, item_id, kardex_lote, kardex_caducidad, kardex_inicial, kardex_entradas, kardex_salidas, kardex_qty, 
        kardex_dev_stock, kardex_dev_merma, kardex_movimiento, kardex_ubicacion, kardex_destino, id_usua, id_compra, factura
    ) 
    VALUES (NOW(), ?, '', '0000-00-00', 0, 0, ?, 0, 0, 0, 'Salida', 'ALMACEN', 'QUIROFANO', ?, NULL, NULL)
";
            $stmtKardex = $conexion->prepare($insertKardex);
            if (!$stmtKardex) {
                throw new Exception("Error preparando consulta kardex_almacen: " . $conexion->error);
            }
            $stmtKardex->bind_param('iii', $itemId, $cantidad, $id_usua);
            if (!$stmtKardex->execute()) {
                throw new Exception("Error insertando en kardex_almacen: " . $stmtKardex->error);
            }
            $stmtKardex->close();
        }

        // Clear selected medicamentos
        $_SESSION['medicamento_seleccionadoMed'] = [];

        // Fetch updated items surtidos
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
                AND ia.item_type = 'INSUMO'
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
                    <th>Insumo</th>
                    <th>Lote</th>
                    <th>Caducidad</th>
                    <th>Cantidad</th>
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
                $tablaSurtidos .= "<td>" . htmlspecialchars($row['cta_tot']) . "</td>";
                $tablaSurtidos .= "<td>
                    <form action='' method='post' class='eliminar-surtido-form' data-id-ctapac='{$row['id_ctapac']}'>
                        <input type='hidden' name='id_ctapac' value='{$row['id_ctapac']}'>
                        <input type='hidden' name='action' value='eliminar_surtido'>
                        <input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>
                        <button type='submit' class='btn btn-danger'>Eliminar</button>
                    </form>
                </td>";
                $tablaSurtidos .= "</tr>";
            }
            $tablaSurtidos .= "</tbody></table>";
        } else {
            $tablaSurtidos = "<p class='text-center'>No hay insumos surtidos para el paciente seleccionado.</p>";
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
                AND ia.item_type = 'MEDICAMENTO'
            ORDER BY 
                dc.cta_fec DESC
        ";
        $stmtSurtidosMed = $conexion->prepare($sqlSurtidosMed);
        if (!$stmtSurtidosMed) {
            throw new Exception("Error preparando consulta dat_ctapac: " . $conexion->error);
        }
        $stmtSurtidosMed->bind_param('i', $id_atencion);
        $stmtSurtidosMed->execute();
        $resultSurtidosMed = $stmtSurtidosMed->get_result();

        $tablaSurtidosMed = '';
        if ($resultSurtidosMed && $resultSurtidosMed->num_rows > 0) {
            $tablaSurtidosMed .= "<table class='table table-bordered table-striped'>";
            $tablaSurtidosMed .= "<thead class='thead-dark'>
                <tr>
                    <th>Paciente</th>
                    <th>Medicamento</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead><tbody>";
            while ($row = $resultSurtidosMed->fetch_assoc()) {
                $tablaSurtidosMed .= "<tr>";
                $tablaSurtidosMed .= "<td>" . htmlspecialchars($row['nombre_paciente']) . "</td>";
                $tablaSurtidosMed .= "<td>" . htmlspecialchars($row['item_name']) . "</td>";
                $tablaSurtidosMed .= "<td>" . htmlspecialchars($row['cta_cant']) . "</td>";
                $tablaSurtidosMed .= "<td>" . htmlspecialchars($row['cta_tot']) . "</td>";
                $tablaSurtidosMed .= "<td>
                    <form action='' method='post' class='eliminar-surtido-form' data-id-ctapac='{$row['id_ctapac']}'>
                        <input type='hidden' name='id_ctapac' value='{$row['id_ctapac']}'>
                        <input type='hidden' name='action' value='eliminar_surtido'>
                        <input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>
                        <button type='submit' class='btn btn-danger'>Eliminar</button>
                    </form>
                </td>";
                $tablaSurtidosMed .= "</tr>";
            }
            $tablaSurtidosMed .= "</tbody></table>";
        } else {
            $tablaSurtidosMed = "<p class='text-center'>No hay medicamentos surtidos para el paciente seleccionado.</p>";
        }
        $stmtSurtidosMed->close();

        $conexion->commit();
        $response['success'] = true;
        $response['data'] = [
            'tablaSurtidos' => $tablaSurtidos,
            'tablaSurtidosMed' => $tablaSurtidosMed
        ];
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
