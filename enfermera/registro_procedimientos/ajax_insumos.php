<?php
session_start();
require_once '../../conexionbd.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'data' => []];

// Validar sesión
if (!isset($_SESSION['login']) || !is_array($_SESSION['login']) || !isset($_SESSION['login']['id_usua'])) {
    $response['message'] = 'Sesión no válida';
    echo json_encode($response);
    exit;
}

$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];

// Validar CSRF token
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $response['message'] = 'Token CSRF inválido';
    echo json_encode($response);
    exit;
}

// Manejar selección de medicamento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'select_medicamento') {
    if (!isset($_POST['medicamento']) || !is_numeric($_POST['medicamento'])) {
        $response['message'] = 'ID de medicamento inválido';
        echo json_encode($response);
        exit;
    }

    $itemId = intval($_POST['medicamento']);
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
        FROM existencias_almacenh ea
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
    if (!isset($_POST['paciente']) || !is_numeric($_POST['paciente'])) {
        $response['message'] = 'ID de paciente inválido';
        echo json_encode($response);
        exit;
    }

    $idAtencion = intval($_POST['paciente']);
    $_SESSION['paciente_seleccionado'] = $idAtencion; // Update session

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
    if (!$stmtSurtidos) {
        $response['message'] = 'Error preparando consulta dat_ctapac: ' . $conexion->error;
        echo json_encode($response);
        exit;
    }
    $stmtSurtidos->bind_param('i', $idAtencion);
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
                <th>Total</th>
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
            $tablaSurtidos .= "</tr>";
        }
        $tablaSurtidos .= "</tbody></table>";
    } else {
        $tablaSurtidos = "<p class='text-center'>No hay ítems surtidos para el paciente seleccionado.</p>";
    }
    $stmtSurtidos->close();

    $response['success'] = true;
    $response['data'] = ['tablaSurtidos' => $tablaSurtidos];
    echo json_encode($response);
    exit;
}

// Manejar agregado de medicamento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'agregar_medicamento') {
    if (!isset($_POST['paciente']) || !isset($_POST['medicamento']) || !isset($_POST['lote']) || !isset($_POST['cantidad'])) {
        $response['message'] = 'Datos incompletos';
        echo json_encode($response);
        exit;
    }

    list($existeId, $nombreLote, $itemId) = explode('|', $_POST['lote']);
    $itemId = intval($itemId);
    $cantidad = intval($_POST['cantidad']);
    $idAtencion = intval($_POST['paciente']);

    $sqlPacienteNombre = "SELECT CONCAT(nom_pac, ' ', papell, ' ', sapell) AS nombre_paciente FROM paciente WHERE Id_exp = (SELECT Id_exp FROM dat_ingreso WHERE id_atencion = ?)";
    $stmtPaciente = $conexion->prepare($sqlPacienteNombre);
    if (!$stmtPaciente) {
        $response['message'] = 'Error en la preparación de la consulta de paciente: ' . $conexion->error;
        echo json_encode($response);
        exit;
    }
    $stmtPaciente->bind_param('i', $idAtencion);
    $stmtPaciente->execute();
    $stmtPaciente->bind_result($nombrePaciente);
    $stmtPaciente->fetch();
    $stmtPaciente->close();

    if (!$nombrePaciente) {
        $response['message'] = 'Paciente no encontrado';
        echo json_encode($response);
        exit;
    }

    $sqlMedicamentoNombrePrecio = "SELECT CONCAT(item_name, ', ', item_grams) AS item_name, item_price FROM item_almacen WHERE item_id = ?";
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

    if (!isset($_SESSION['medicamento_seleccionado'])) {
        $_SESSION['medicamento_seleccionado'] = [];
    }
    $_SESSION['medicamento_seleccionado'][] = [
        'paciente' => $nombrePaciente,
        'item_id' => $itemId,
        'medicamento' => $nombreMedicamento,
        'lote' => $nombreLote,
        'cantidad' => $cantidad,
        'existe_id' => $existeId,
        'id_atencion' => $idAtencion,
        'precio' => $precioMedicamento
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
            $tabla .= "<td>" . htmlspecialchars($medicamento['precio']) . "</td>";
            $tabla .= "<td>
                        <form action='' method='post' class='eliminar-form' data-index='$index'>
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

// Manejar eliminación de medicamento (pendiente)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'eliminar_medicamento') {
    if (!isset($_POST['eliminar_index']) || !is_numeric($_POST['eliminar_index'])) {
        $response['message'] = 'Índice inválido';
        echo json_encode($response);
        exit;
    }

    $index = intval($_POST['eliminar_index']);
    if (isset($_SESSION['medicamento_seleccionado'][$index])) {
        unset($_SESSION['medicamento_seleccionado'][$index]);
        $_SESSION['medicamento_seleccionado'] = array_values($_SESSION['medicamento_seleccionado']);
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
            $tabla .= "<td>" . htmlspecialchars($medicamento['precio']) . "</td>";
            $tabla .= "<td>
                        <form action='' method='post' class='eliminar-form' data-index='$index'>
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

// Manejar eliminación de ítem surtido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'eliminar_surtido') {
    if (!isset($_POST['id_ctapac']) || !is_numeric($_POST['id_ctapac'])) {
        $response['message'] = 'ID de cuenta inválido';
        echo json_encode($response);
        exit;
    }

    $idCtapac = intval($_POST['id_ctapac']);
    $idAtencion = isset($_SESSION['paciente_seleccionado']) ? intval($_SESSION['paciente_seleccionado']) : 0;

    if (!$idAtencion) {
        $response['message'] = 'No se ha seleccionado un paciente';
        echo json_encode($response);
        exit;
    }

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

        // Find the corresponding existe_id in existencias_almacenh
        $sqlExistencias = "
            SELECT existe_id, existe_qty, existe_salidas
            FROM existencias_almacenh
            WHERE item_id = ? AND existe_lote = ? AND existe_caducidad = ?
        ";
        $stmtExistencias = $conexion->prepare($sqlExistencias);
        if (!$stmtExistencias) {
            throw new Exception("Error preparando consulta existencias_almacenh: " . $conexion->error);
        }
        $stmtExistencias->bind_param('iss', $itemId, $loteNombre, $caducidad);
        $stmtExistencias->execute();
        $stmtExistencias->bind_result($existeId, $existeQty, $existeSalidas);
        if (!$stmtExistencias->fetch()) {
            throw new Exception("No se encontró el lote en existencias_almacenh.");
        }
        $stmtExistencias->close();

        // Update stock in existencias_almacenh
        $nuevaExistenciaQty = $existeQty + $cantidadLote;
        $nuevaExistenciaSalidas = max(0, $existeSalidas - $cantidadLote);
        $fechaActual = date('Y-m-d H:i:s');

        $updateExistenciasQuery = "
            UPDATE existencias_almacenh 
            SET existe_qty = ?, existe_fecha = ?, existe_salidas = ? 
            WHERE existe_id = ?
        ";
        $stmtUpdateExistencias = $conexion->prepare($updateExistenciasQuery);
        if (!$stmtUpdateExistencias) {
            throw new Exception("Error preparando consulta existencias_almacenh: " . $conexion->error);
        }
        $stmtUpdateExistencias->bind_param('isii', $nuevaExistenciaQty, $fechaActual, $nuevaExistenciaSalidas, $existeId);
        if (!$stmtUpdateExistencias->execute()) {
            throw new Exception("Error actualizando existencias_almacenh: " . $stmtUpdateExistencias->error);
        }
        $stmtUpdateExistencias->close();

        // Log deletion in kardex_almacenh
        $insertKardex = "
            INSERT INTO kardex_almacenh (
                kardex_fecha, item_id, kardex_lote, kardex_caducidad, kardex_inicial, kardex_entradas, kardex_salidas, kardex_qty, 
                kardex_dev_stock, kardex_dev_merma, kardex_movimiento, kardex_destino, id_surte, motivo
            ) 
            VALUES (NOW(), ?, ?, ?, 0, ?, 0, 0, 0, 0, 'Devolución', 'QUIROFANO', ?, 'Eliminación de ítem surtido')
        ";
        $stmtKardex = $conexion->prepare($insertKardex);
        if (!$stmtKardex) {
            throw new Exception("Error preparando consulta kardex_almacenh: " . $conexion->error);
        }
        $stmtKardex->bind_param('issii', $itemId, $loteNombre, $caducidad, $cantidadLote, $id_usua);
        if (!$stmtKardex->execute()) {
            throw new Exception("Error insertando en kardex_almacenh: " . $stmtKardex->error);
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
            ORDER BY 
                dc.cta_fec DESC
        ";
        $stmtSurtidos = $conexion->prepare($sqlSurtidos);
        if (!$stmtSurtidos) {
            throw new Exception("Error preparando consulta dat_ctapac: " . $conexion->error);
        }
        $stmtSurtidos->bind_param('i', $idAtencion);
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
                        <input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>
                        <button type='submit' class='btn btn-danger'>Eliminar</button>
                    </form>
                </td>";
                $tablaSurtidos .= "</tr>";
            }
            $tablaSurtidos .= "</tbody></table>";
        } else {
            $tablaSurtidos = "<p class='text-center'>No hay ítems surtidos para el paciente seleccionado.</p>";
        }
        $stmtSurtidos->close();

        $conexion->commit();
        $response['success'] = true;
        $response['data'] = ['tablaSurtidos' => $tablaSurtidos];
        echo json_encode($response);
    } catch (Exception $e) {
        $conexion->rollback();
        $response['message'] = 'Error: ' . $e->getMessage();
        file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - eliminar_surtido: ' . $e->getMessage() . "\n", FILE_APPEND);
        echo json_encode($response);
    }
    exit;
}

$response['message'] = 'Acción no válida';
echo json_encode($response);
?>