<?php
include "../../conexionbd.php";
session_start();
ob_start();

$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];

date_default_timezone_set('America/Guatemala');

// Incluye el encabezado correspondiente según el rol del usuario
if ($usuario['id_rol'] == 7) {
    include "../header_farmaciaq.php";
} else if ($usuario['id_rol'] == 3) {
    include "../../enfermera/header_enfermera.php";
} else if ($usuario['id_rol'] == 4 || $usuario['id_rol'] == 5) {
    include "../header_farmaciaq.php";
} else {
    echo "<script>window.Location='../../index.php';</script>";
    exit;
}

// Obtener los pacientes con su atención más reciente
// Consulta optimizada para tus datos específicos
$sqlPac = "
    SELECT 
        di.id_atencion, 
        CONCAT(p.nom_pac, ' ', p.papell, ' ', p.sapell) AS nombre_paciente,
        p.Id_exp,
        di.fecha
    FROM 
        dat_ingreso di
    JOIN 
        paciente p ON di.Id_exp = p.Id_exp
    WHERE 
        di.activo = 'SI'
        AND di.fecha != '0000-00-00 00:00:00'
        AND di.fecha IS NOT NULL
        AND di.fecha = (
            SELECT MAX(di2.fecha) 
            FROM dat_ingreso di2 
            WHERE di2.Id_exp = di.Id_exp 
            AND di2.activo = 'SI'
            AND di2.fecha != '0000-00-00 00:00:00'
            AND di2.fecha IS NOT NULL
        )
        AND di.id_atencion = (
            SELECT MAX(di3.id_atencion)
            FROM dat_ingreso di3
            WHERE di3.Id_exp = di.Id_exp 
            AND di3.activo = 'SI'
            AND di3.fecha = di.fecha
        )
    ORDER BY p.nom_pac, p.papell, p.sapell
";
$resultPac = $conexion->query($sqlPac);

// Agregar manejo de errores para la consulta de pacientes
if (!$resultPac) {
    error_log("Error en consulta de pacientes: " . $conexion->error);
    echo "<div style='color: red; padding: 10px; background: #ffebee; border-radius: 5px; margin: 10px;'>";
    echo "<strong>Error en consulta de pacientes:</strong> " . htmlspecialchars($conexion->error);
    echo "</div>";
}

$pacientesOptions = '';
if ($resultPac && $resultPac->num_rows > 0) {
    while ($paciente = $resultPac->fetch_assoc()) {
        // Verificar si el paciente está seleccionado (por POST, GET, o sesión)
        $selectedPaciente = '';
        if (isset($_POST['paciente']) && $_POST['paciente'] == $paciente['id_atencion']) {
            $selectedPaciente = 'selected';
        } elseif (isset($_GET['paciente']) && $_GET['paciente'] == $paciente['id_atencion']) {
            $selectedPaciente = 'selected';
        } elseif (isset($_SESSION['paciente_seleccionado']) && $_SESSION['paciente_seleccionado'] == $paciente['id_atencion']) {
            $selectedPaciente = 'selected';
        }
        $pacientesOptions .= "<option value='{$paciente['id_atencion']}' $selectedPaciente>{$paciente['nombre_paciente']}</option>";
    }
} else {
    error_log("No se encontraron pacientes en la consulta");
}

// Guardar el id_atencion en la sesión cuando se seleccione el paciente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['paciente'])) {
    $_SESSION['paciente_seleccionado'] = $_POST['paciente'];
} elseif (isset($_GET['paciente'])) {
    $_SESSION['paciente_seleccionado'] = $_GET['paciente'];
}



$queryMedicamentos = "SELECT DISTINCT 
    ia.item_id, 
    CONCAT(ia.item_name, ', ', ia.item_grams) AS item_name
    FROM item_almacen ia 
    WHERE EXISTS (
        SELECT 1 FROM existencias_almacenq ea 
        WHERE ea.item_id = ia.item_id AND ea.existe_qty > 0
    )
    ORDER BY ia.item_name
";

$resultMedicamentos = $conexion->query($queryMedicamentos);

$medicamentosOptions = '';
$medicamentoSeleccionado = '';

// Determinar qué medicamento está seleccionado
if (isset($_POST['medicamento']) && !empty($_POST['medicamento'])) {
    $medicamentoSeleccionado = $_POST['medicamento'];
} elseif (isset($_GET['medicamento']) && !empty($_GET['medicamento'])) {
    $medicamentoSeleccionado = $_GET['medicamento'];
}

if ($resultMedicamentos && $resultMedicamentos->num_rows > 0) {
    while ($medicamento = $resultMedicamentos->fetch_assoc()) {
        // Verificar si este medicamento es el seleccionado
        $selectedMedicamento = '';
        if ($medicamentoSeleccionado == $medicamento['item_id']) {
            $selectedMedicamento = 'selected';
        }
        $medicamentosOptions .= "<option value='{$medicamento['item_id']}' $selectedMedicamento>{$medicamento['item_name']}</option>";
    }
}

// Obtener los lotes y la suma total de existencias para el medicamento seleccionado
$lotesOptions = '';
$totalExistencias = 0; // Variable para el total de existencias
$itemIdSeleccionado = null;

// Determinar qué medicamento está seleccionado
if (isset($_POST['medicamento']) && !empty($_POST['medicamento'])) {
    $itemIdSeleccionado = intval($_POST['medicamento']);
} elseif (isset($_GET['medicamento']) && !empty($_GET['medicamento'])) {
    $itemIdSeleccionado = intval($_GET['medicamento']);
}

if ($itemIdSeleccionado) {
    // Primero, obtener el total de existencias de este medicamento
    $sqlTotalExistencias = "
        SELECT SUM(ea.existe_qty) AS total_existencias
        FROM existencias_almacenq ea
        WHERE ea.item_id = ? AND ea.existe_qty > 0
    ";
    $stmtTotal = $conexion->prepare($sqlTotalExistencias);
    $stmtTotal->bind_param('i', $itemIdSeleccionado);
    $stmtTotal->execute();
    $resultTotalExistencias = $stmtTotal->get_result();
    if ($resultTotalExistencias && $resultTotalExistencias->num_rows > 0) {
        $row = $resultTotalExistencias->fetch_assoc();
        $totalExistencias = $row['total_existencias'] ? $row['total_existencias'] : 0;
    }
    $stmtTotal->close();

    // Ahora obtenemos los lotes disponibles para este medicamento
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
    $stmt->bind_param('i', $itemIdSeleccionado);
    $stmt->execute();
    $resultLotes = $stmt->get_result();

    // Comprobar si hay resultados
    if ($resultLotes && $resultLotes->num_rows > 0) {
        while ($lote = $resultLotes->fetch_assoc()) {
            $lotesOptions .= "<option value='{$lote['existe_id']}|{$lote['existe_lote']}|$itemIdSeleccionado' data-caducidad='{$lote['existe_caducidad']}' data-cantidad='{$lote['existe_qty']}' data-stock='{$lote['existe_qty']}'>
    {$lote['existe_lote']} / {$lote['existe_caducidad']} / Stock: {$lote['existe_qty']}
    </option>";
        }
    } else {
        $lotesOptions .= "<option value='' disabled>No hay lotes disponibles</option>";
    }

    // Cerrar la declaración
    $stmt->close();
}

// Captura del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['paciente']) && isset($_POST['medicamento']) && isset($_POST['lote']) && isset($_POST['cantidad'])) {
    list($existeId, $nombreLote, $itemId) = explode('|', $_POST['lote']);
    $itemId = intval($itemId);
    
    // Usar directamente el id_atencion del formulario (ya viene de la consulta principal filtrada)
    $idAtencion = intval($_POST['paciente']);
    
    // Validar que el id_atencion es mayor que 0
    if ($idAtencion <= 0) {
        error_log("ID de atención inválido recibido: " . $_POST['paciente']);
        header("Location: surtir_pacienteq.php?envio=error&mensaje=" . urlencode("ID de atención inválido: " . $_POST['paciente']));
        exit();
    }
    
    // Validar que el id_atencion existe en la base de datos
    $sqlValidarAtencion = "SELECT id_atencion, Id_exp, fecha FROM dat_ingreso 
                          WHERE id_atencion = ? AND activo = 'SI'";
    $stmtValidar = $conexion->prepare($sqlValidarAtencion);
    if (!$stmtValidar) {
        error_log("Error al preparar validación de id_atencion: " . $conexion->error);
        header("Location: surtir_pacienteq.php?envio=error&mensaje=" . urlencode("Error al validar atención"));
        exit();
    }
    $stmtValidar->bind_param('i', $idAtencion);
    if (!$stmtValidar->execute()) {
        header("Location: surtir_pacienteq.php?envio=error&mensaje=" . urlencode("Error al validar atención"));
        exit();
    }
    
    $resultValidar = $stmtValidar->get_result();
    if ($resultValidar->num_rows == 0) {
        header("Location: surtir_pacienteq.php?envio=error&mensaje=" . urlencode("ID de atención no encontrado: " . $idAtencion));
        exit();
    }
    
    // Obtener datos de la atención
    $datosAtencion = $resultValidar->fetch_assoc();
    error_log("Datos de atención encontrados: " . print_r($datosAtencion, true));
    $stmtValidar->close();

    // Guardar el id_atencion en la sesión
    $_SESSION['id_atencion'] = $idAtencion;


    $sqlPacienteNombre = "SELECT CONCAT(p.nom_pac, ' ', p.papell, ' ', p.sapell) AS nombre_paciente 
                          FROM paciente p 
                          JOIN dat_ingreso di ON p.Id_exp = di.Id_exp 
                          WHERE di.id_atencion = ?";
    $stmtPaciente = $conexion->prepare($sqlPacienteNombre);
    if (!$stmtPaciente) {
        error_log("Error al preparar consulta de nombre del paciente: " . $conexion->error);
        header("Location: surtir_pacienteq.php?envio=error&mensaje=" . urlencode("Error al preparar consulta del paciente"));
        exit();
    }
    $stmtPaciente->bind_param('i', $_POST['paciente']);
    if (!$stmtPaciente->execute()) {
        error_log("Error al ejecutar consulta de nombre del paciente: " . $stmtPaciente->error);
        header("Location: surtir_pacienteq.php?envio=error&mensaje=" . urlencode("Error al ejecutar consulta del paciente"));
        exit();
    }
    $stmtPaciente->bind_result($nombrePaciente);
    if (!$stmtPaciente->fetch()) {
        $nombrePaciente = ""; // No se encontró el paciente
        error_log("No se encontró nombre del paciente para id_atencion: " . $_POST['paciente']);
    }
    $stmtPaciente->close();

    // Obtener el nombre y precio del medicamento
    $sqlMedicamentoNombrePrecio = "SELECT CONCAT(item_name, ', ', item_grams) AS item_name, item_price FROM item_almacen WHERE item_id = ?";
    $stmtMedicamento = $conexion->prepare($sqlMedicamentoNombrePrecio);
    $stmtMedicamento->bind_param('i', $_POST['medicamento']);
    $stmtMedicamento->execute();
    $stmtMedicamento->bind_result($nombreMedicamento, $precioMedicamento);
    $stmtMedicamento->fetch();
    $stmtMedicamento->close();


    // Verificar si el botón "Agregar" ha sido presionado
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar'])) {
        // Captura del formulario
        $cantidadAgregar = intval($_POST['cantidad']);

        // Validar que la cantidad sea mayor a 0
        if ($cantidadAgregar <= 0) {
            header("Location: surtir_pacienteq.php?paciente=" . $_POST['paciente'] . "&medicamento=" . $_POST['medicamento'] . "&error=cantidad_invalida");
            exit();
        }

        // Si la variable de sesión no está inicializada, la creamos como un array vacío
        if (!isset($_SESSION['medicamento_seleccionado'])) {
            $_SESSION['medicamento_seleccionado'] = [];
        }

        // Verificar stock disponible del lote
        $sqlStockLote = "SELECT existe_qty FROM existencias_almacenq WHERE existe_id = ?";
        $stmtStock = $conexion->prepare($sqlStockLote);
        $stmtStock->bind_param('i', $existeId);
        $stmtStock->execute();
        $stmtStock->bind_result($stockDisponible);
        $stmtStock->fetch();
        $stmtStock->close();

        // Calcular la cantidad ya agregada de este lote específico en el carrito
        $cantidadEnCarrito = 0;
        foreach ($_SESSION['medicamento_seleccionado'] as $item) {
            if ($item['existe_id'] == $existeId) {
                $cantidadEnCarrito += intval($item['cantidad']);
            }
        }

        // Calcular la cantidad total que se tendría después de agregar
        $cantidadTotal = $cantidadEnCarrito + $cantidadAgregar;

        // Validar si hay suficiente stock
        if ($cantidadTotal > $stockDisponible) {
            $cantidadPermitida = $stockDisponible - $cantidadEnCarrito;
            // Redirección con mensaje de error
            header("Location: surtir_pacienteq.php?paciente=" . $_POST['paciente'] . "&medicamento=" . $_POST['medicamento'] . "&error=stock&stock_disponible=$stockDisponible&en_carrito=$cantidadEnCarrito&cantidad_permitida=$cantidadPermitida&cantidad_solicitada=$cantidadAgregar");
            exit();
        }

        // Si la validación pasa, agregar el nuevo registro al array de la sesión
        $nuevoRegistro = [
            'paciente' => $nombrePaciente,
            'item_id' => $itemId,
            'medicamento' => $nombreMedicamento,
            'lote' => $nombreLote,
            'cantidad' => $cantidadAgregar,
            'existe_id' => $existeId,
            'id_atencion' => $idAtencion,
            'precio' => $precioMedicamento
        ];
        
        $_SESSION['medicamento_seleccionado'][] = $nuevoRegistro;

        // Redirección directa con PHP
        header("Location: surtir_pacienteq.php?paciente=" . $_POST['paciente'] . "&medicamento=" . $_POST['medicamento'] . "&success=1");
        exit();
    }
}

// Manejar eliminación de items del carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_index'])) {
    $index = intval($_POST['eliminar_index']); // Asegurarse de que sea un número entero
    if (isset($_SESSION['medicamento_seleccionado'][$index])) {
        unset($_SESSION['medicamento_seleccionado'][$index]); // Eliminar el registro
        $_SESSION['medicamento_seleccionado'] = array_values($_SESSION['medicamento_seleccionado']); // Reindexar array
        
        // Redireccionar con mensaje de eliminación
        header("Location: surtir_pacienteq.php?eliminated=1");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['enviar_medicamentos'])) {
    $fechaActual = date('Y-m-d H:i:s');

    if (!isset($_SESSION['medicamento_seleccionado']) || empty($_SESSION['medicamento_seleccionado'])) {
        header("Location: surtir_pacienteq.php?envio=empty");
        exit();
    }

    // Iniciar transacción
    $conexion->autocommit(FALSE);

    try {
        foreach ($_SESSION['medicamento_seleccionado'] as $index => $medicamento) {
            $paciente = isset($medicamento['paciente']) ? $medicamento['paciente'] : '';
            $nombreMedicamento = isset($medicamento['medicamento']) ? $medicamento['medicamento'] : '';
            $loteNombre = isset($medicamento['lote']) ? $medicamento['lote'] : '';
            $cantidadLote = isset($medicamento['cantidad']) ? intval($medicamento['cantidad']) : 0;
            $existeId = isset($medicamento['existe_id']) ? intval($medicamento['existe_id']) : 0;
            $Id_Atencion = isset($medicamento['id_atencion']) ? intval($medicamento['id_atencion']) : 0;
            $itemId = isset($medicamento['item_id']) ? intval($medicamento['item_id']) : 0;

            // Validación inicial: verificar que todos los datos estén presentes
            $erroresDetallados = [];
            if (empty($paciente)) $erroresDetallados[] = "nombre del paciente vacío";
            if (empty($nombreMedicamento)) $erroresDetallados[] = "nombre del medicamento vacío";
            if (empty($loteNombre)) $erroresDetallados[] = "nombre del lote vacío";
            if ($cantidadLote <= 0) $erroresDetallados[] = "cantidad inválida ($cantidadLote)";
            if ($existeId <= 0) $erroresDetallados[] = "ID de existencia inválido ($existeId)";
            if ($Id_Atencion <= 0) $erroresDetallados[] = "ID de atención inválido ($Id_Atencion)";
            if ($itemId <= 0) $erroresDetallados[] = "ID de item inválido ($itemId)";
            
            if (!empty($erroresDetallados)) {
                $detalleError = "Datos incompletos en el registro $index: " . implode(", ", $erroresDetallados);
                error_log("Error de validación: " . $detalleError);
                throw new Exception($detalleError);
            }

            $sqlMedicamentoNombre = "SELECT CONCAT(item_name, ', ', item_grams) AS item_name FROM item_almacen WHERE item_id = ?";
            $stmtMedicamento = $conexion->prepare($sqlMedicamentoNombre);
            if (!$stmtMedicamento) {
                throw new Exception("Error al preparar consulta en tabla ITEM_ALMACEN: " . $conexion->error);
            }
            $stmtMedicamento->bind_param('i', $itemId);
            if (!$stmtMedicamento->execute()) {
                throw new Exception("Error al ejecutar consulta en tabla ITEM_ALMACEN: " . $stmtMedicamento->error);
            }
            $stmtMedicamento->bind_result($nombreMedicamento);
            if (!$stmtMedicamento->fetch()) {
                throw new Exception("No se encontró el medicamento con ID: $itemId en tabla ITEM_ALMACEN");
            }
            $stmtMedicamento->close();

            $queryItemAlmacenn = "
            SELECT 
                item_name, 
                item_price 
            FROM 
                item_almacen 
           WHERE item_id = ?";

            $stmt = $conexion->prepare($queryItemAlmacenn);
            if (!$stmt) {
                throw new Exception("Error al preparar consulta de precios en tabla ITEM_ALMACEN: " . $conexion->error);
            }
            $stmt->bind_param("i", $itemId);
            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar consulta de precios en tabla ITEM_ALMACEN: " . $stmt->error);
            }
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $itemData = $result->fetch_assoc();
                $itemName = $itemData['item_name'];
                $salidaCostsu = $itemData['item_price'];
            } else {
                throw new Exception("No se encontró el ítem con ID $itemId en tabla ITEM_ALMACEN");
            }
            $stmt->close();

            // *** 2. Obtener existencias actuales del lote desde existencias_almacenq ***
            $selectExistenciasQuery = "SELECT existe_qty, existe_caducidad, existe_salidas FROM existencias_almacenq 
            WHERE existe_id = ?";
            $stmtSelect = $conexion->prepare($selectExistenciasQuery);
            if (!$stmtSelect) {
                throw new Exception("Error al preparar consulta en tabla EXISTENCIAS_ALMACENQ: " . $conexion->error);
            }
            $stmtSelect->bind_param('i', $existeId);
            if (!$stmtSelect->execute()) {
                throw new Exception("Error al ejecutar consulta en tabla EXISTENCIAS_ALMACENQ: " . $stmtSelect->error);
            }
            $stmtSelect->bind_result($existeQty, $caducidad, $existeSalidas);
            if (!$stmtSelect->fetch()) {
                throw new Exception("No se encontraron existencias para existe_id: $existeId en tabla EXISTENCIAS_ALMACENQ");
            }
            $stmtSelect->close();

            // *** 4. Calcular nuevos valores para existencias ***
            $nuevaExistenciaQty = $existeQty - $cantidadLote;
            $nuevaExistenciaSalidas = $existeSalidas + $cantidadLote;

            // Validar si hay suficiente stock
            if ($existeQty < $cantidadLote) {
                throw new Exception("STOCK INSUFICIENTE en tabla EXISTENCIAS_ALMACENQ - El lote \"$loteNombre\" no tiene suficiente stock. Disponible: $existeQty, requerido: $cantidadLote");
            }

            // Validar que la nueva cantidad no sea negativa
            if ($nuevaExistenciaQty < 0) {
                throw new Exception("ERROR DE VALIDACIÓN en tabla EXISTENCIAS_ALMACENQ - La cantidad resultante sería negativa para el lote \"$loteNombre\"");
            }



            // *** 6. Insertar en kardex_almacenq ***
            $insert_kardex = "
               INSERT INTO kardex_almacenq (
                   kardex_fecha, item_id, kardex_lote, kardex_caducidad, kardex_inicial, kardex_entradas, kardex_salidas, kardex_qty, 
                   kardex_dev_stock, kardex_dev_merma, kardex_movimiento, kardex_destino, id_surte
               ) 
               VALUES (NOW(), ?, ?, ?, 0, 0, ?, 0, 0, 0, 'Salida', 'QUIROFANO', ?)
           ";
            $stmt_kardex = $conexion->prepare($insert_kardex);
            if (!$stmt_kardex) {
                throw new Exception("Error en la preparación de consulta INSERT en tabla KARDEX_ALMACENQ: " . $conexion->error);
            }
            $stmt_kardex->bind_param('issii', $itemId, $loteNombre, $caducidad, $cantidadLote, $id_usua);
            if (!$stmt_kardex->execute()) {
                throw new Exception("Error al insertar registro en tabla KARDEX_ALMACENQ: " . $stmt_kardex->error);
            }
            $stmt_kardex->close();


            // *** 7. Insertar en salidas_almacenq ***
            $queryInsercion = "
                INSERT INTO salidas_almacenq (
                    item_id, 
                    item_name, 
                    salida_fecha, 
                    salida_lote, 
                    salida_caducidad, 
                    salida_qty, 
                    salida_costsu, 
                    id_usua, 
                    id_atencion, 
                    solicita
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)
             ";
            $stmtInsertSalida = $conexion->prepare($queryInsercion);
            if (!$stmtInsertSalida) {
                throw new Exception("Error en la preparación de consulta INSERT en tabla SALIDAS_ALMACENQ: " . $conexion->error);
            }
            $stmtInsertSalida->bind_param(
                "issssdiii",
                $itemId,
                $nombreMedicamento,
                $fechaActual,
                $loteNombre,
                $caducidad,
                $cantidadLote,
                $salidaCostsu,
                $id_usua,
                $Id_Atencion
            );

            if (!$stmtInsertSalida->execute()) {
                throw new Exception("Error al insertar registro en tabla SALIDAS_ALMACENQ: " . $stmtInsertSalida->error);
            }
            $salidaId = $stmtInsertSalida->insert_id; // Obtener el ID generado automáticamente
            $stmtInsertSalida->close();

            // *** 8. Insertar en dat_ctapac ***
            $insertDatCtapacQuery = "
              INSERT INTO dat_ctapac (
                  id_atencion, 
                  prod_serv, 
                  insumo, 
                  cta_fec, 
                  cta_cant, 
                  cta_tot, 
                  id_usua, 
                  cta_activo, 
                  salida_id, 
                  existe_lote, 
                  existe_caducidad
              ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
           ";

            $stmtInsertDatCtapac = $conexion->prepare($insertDatCtapacQuery);
            if (!$stmtInsertDatCtapac) {
                throw new Exception("Error en la preparación de consulta INSERT en tabla DAT_CTAPAC: " . $conexion->error);
            }
            $prodServ = 'PC';
            $ctaActivo = 'SI';

            $stmtInsertDatCtapac->bind_param(
                'isssddsssss',
                $Id_Atencion,
                $prodServ,
                $itemId,
                $fechaActual,
                $cantidadLote,
                $salidaCostsu,
                $id_usua,
                $ctaActivo,
                $salidaId,
                $loteNombre,
                $caducidad
            );

            if (!$stmtInsertDatCtapac->execute()) {
                throw new Exception("Error al insertar registro en tabla DAT_CTAPAC: " . $stmtInsertDatCtapac->error);
            }
            $stmtInsertDatCtapac->close();

            // *** 9. Insertar en cart_recib ***
            /*        $ingresar2 = $conexion->query("INSERT INTO cart_recib(item_id, solicita, almacen, id_usua, confirma) VALUES ($itemId, $cantidadLote, 'QUIROFANO', $id_usua, 'SI')");
            if (!$ingresar2) {
            throw new Exception("Error al insertar en cart_recib: " . $conexion->error);
            }*/



            // *** 5. Actualizar existencias en la tabla existencias_almacenq ***
            $updateExistenciasQuery = "UPDATE existencias_almacenq SET existe_qty = ?, existe_fecha = ?, existe_salidas = ? WHERE existe_id = ?";
            $stmtUpdateExistencias = $conexion->prepare($updateExistenciasQuery);
            if (!$stmtUpdateExistencias) {
                throw new Exception("Error en la preparación de consulta UPDATE en tabla EXISTENCIAS_ALMACENQ: " . $conexion->error);
            }
            $stmtUpdateExistencias->bind_param('isii', $nuevaExistenciaQty, $fechaActual, $nuevaExistenciaSalidas, $existeId);
            if (!$stmtUpdateExistencias->execute()) {
                throw new Exception("Error al actualizar registro en tabla EXISTENCIAS_ALMACENQ: " . $stmtUpdateExistencias->error);
            }
            $stmtUpdateExistencias->close();
        }

        // Si llegamos hasta aquí, todas las operaciones fueron exitosas
        $conexion->commit();

        // Limpiar la memoria
        unset($_SESSION['medicamento_seleccionado']);

        // Redirección directa con PHP
        header("Location: surtir_pacienteq.php?envio=success");
        exit();
    } catch (Exception $e) {
        // Si hay algún error, hacer rollback
        $conexion->rollback();

        // Redirección con mensaje de error
        $errorMessage = urlencode($e->getMessage());
        header("Location: surtir_pacienteq.php?envio=error&mensaje=" . $errorMessage);
        exit();
    } finally {
        // Restaurar autocommit
        $conexion->autocommit(TRUE);
    }
}





?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <!-- Botón superior con mismo margen arriba y abajo -->
    <div class="d-flex justify-content-start" style="margin: 20px 0; margin-left: 20px;">
        <div class="d-flex">
            <!-- Botón Regresar -->
            <a href="../../template/menu_farmaciaq.php"
                style="color: white; background: linear-gradient(135deg, #2b2d7f 0%, #1a1c5a 100%);
            border: none; border-radius: 8px; padding: 10px 16px; cursor: pointer; display: inline-block; 
            text-decoration: none; box-shadow: 0 2px 8px rgba(43, 45, 127, 0.3); 
            transition: all 0.3s ease; margin-right: 10px;">
                ← Regresar
            </a>
        </div>
    </div>


    <div class="form-container">
        <div class="thead" style="background: linear-gradient(135deg, #2b2d7f 0%, #1a1c5a 100%); margin: 5px auto; padding: 15px 25px; color: white; width: fit-content; text-align: center; border-radius: 15px; box-shadow: 0 4px 15px rgba(43, 45, 127, 0.3);">
            <h1 style="font-size: 28px; margin: 0; font-weight: 600; text-shadow: 0 2px 4px rgba(0,0,0,0.3);"><i class="fas fa-pills"></i> SURTIR PACIENTE</h1>
        </div>
        <br>

        <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: '<i class="fas fa-check-circle" style="color: #28a745;"></i> Agregado correctamente',
                        html: '<div style="text-align: center; font-size: 16px;">' +
                            '<i class="fas fa-pills" style="color: #2b2d7f; font-size: 24px; margin-bottom: 10px;"></i><br>' +
                            'El medicamento se ha agregado al carrito exitosamente.' +
                            '</div>',
                        icon: 'success',
                        timer: 7000,
                        showConfirmButton: false,
                        timerProgressBar: true
                    });
                });
            </script>
        <?php endif; ?>

        <?php if (isset($_GET['eliminated']) && $_GET['eliminated'] == '1'): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: '<i class="fas fa-trash-alt" style="color: #dc3545;"></i> Eliminado correctamente',
                        html: '<div style="text-align: center; font-size: 16px;">' +
                            '<i class="fas fa-trash" style="color: #dc3545; font-size: 24px; margin-bottom: 10px;"></i><br>' +
                            'El medicamento se ha eliminado del carrito exitosamente.' +
                            '</div>',
                        icon: 'success',
                        timer: 5000,
                        showConfirmButton: false,
                        timerProgressBar: true
                    });
                });
            </script>
        <?php endif; ?>

        <?php if (isset($_GET['error']) && $_GET['error'] == 'stock'): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: '⚠️ Stock insuficiente',
                        html: '<div style="text-align: left; font-size: 16px;">' +
                            '<p style="margin-bottom: 15px;">No puedes agregar <strong><?= htmlspecialchars($_GET['cantidad_solicitada'] ?? '0') ?></strong> unidades.</p>' +
                            '<div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #dc3545;">' +
                            '<p style="margin: 5px 0;"><i class="fas fa-boxes" style="color: #28a745; margin-right: 8px;"></i><strong>Stock disponible:</strong> <?= htmlspecialchars($_GET['stock_disponible'] ?? '0') ?></p>' +
                            '<p style="margin: 5px 0;"><i class="fas fa-shopping-cart" style="color: #ffc107; margin-right: 8px;"></i><strong>Ya en carrito:</strong> <?= htmlspecialchars($_GET['en_carrito'] ?? '0') ?></p>' +
                            '<p style="margin: 5px 0;"><i class="fas fa-check-circle" style="color: #17a2b8; margin-right: 8px;"></i><strong>Cantidad máxima permitida:</strong> <?= htmlspecialchars($_GET['cantidad_permitida'] ?? '0') ?></p>' +
                            '</div>' +
                            '</div>',
                        icon: 'error',
                        timer: 7000,
                        showConfirmButton: true,
                        confirmButtonText: '<i class="fas fa-check"></i> Entendido',
                        timerProgressBar: true,
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    });
                });
            </script>
        <?php endif; ?>

        <?php if (isset($_GET['error']) && $_GET['error'] == 'cantidad_invalida'): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: '<i class="fas fa-exclamation-triangle" style="color: #ffc107;"></i> Cantidad inválida',
                        html: '<div style="text-align: center; font-size: 16px;">' +
                            '<i class="fas fa-calculator" style="color: #dc3545; font-size: 24px; margin-bottom: 10px;"></i><br>' +
                            'La cantidad debe ser mayor a <strong>0</strong>.<br>' +
                            '<small style="color: #6c757d;">Por favor, ingresa una cantidad válida.</small>' +
                            '</div>',
                        icon: 'warning',
                        timer: 5000,
                        showConfirmButton: true,
                        confirmButtonText: '<i class="fas fa-check"></i> Entendido',
                        timerProgressBar: true,
                        customClass: {
                            confirmButton: 'btn btn-warning'
                        }
                    });
                });
            </script>
        <?php endif; ?>

        <?php if (isset($_GET['envio'])): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    <?php if ($_GET['envio'] == 'success'): ?>
                        Swal.fire({
                            title: '<i class="fas fa-rocket" style="color: #28a745;"></i> ¡Éxito!',
                            html: '<div style="text-align: center; font-size: 16px;">' +
                                '<i class="fas fa-check-double" style="color: #28a745; font-size: 24px; margin-bottom: 10px;"></i><br>' +
                                'Todos los medicamentos se han registrado correctamente.' +
                                '</div>',
                            icon: 'success',
                            timer: 7000,
                            showConfirmButton: true,
                            confirmButtonText: '<i class="fas fa-thumbs-up"></i> Perfecto',
                            timerProgressBar: true,
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                    <?php elseif ($_GET['envio'] == 'error'): ?>
                        <?php 
                            $mensajeError = isset($_GET['mensaje']) ? htmlspecialchars(urldecode($_GET['mensaje'])) : 'Error desconocido';
                            // Detectar y resaltar el nombre de la tabla
                            $tablas = ['ITEM_ALMACEN', 'EXISTENCIAS_ALMACENQ', 'KARDEX_ALMACENQ', 'SALIDAS_ALMACENQ', 'DAT_CTAPAC'];
                            $mensajeFormateado = $mensajeError;
                            foreach ($tablas as $tabla) {
                                $mensajeFormateado = str_replace($tabla, '<span style="background: #dc3545; color: white; padding: 2px 6px; border-radius: 4px; font-weight: bold; font-family: monospace;">' . $tabla . '</span>', $mensajeFormateado);
                            }
                        ?>
                        Swal.fire({
                            title: '<i class="fas fa-exclamation-triangle" style="color: #dc3545;"></i> Error en Base de Datos',
                            html: '<div style="text-align: left; font-size: 15px; background: #fff5f5; padding: 20px; border-radius: 8px; border: 1px solid #fed7d7;">' +
                                '<div style="margin-bottom: 15px; text-align: center;">' +
                                '<i class="fas fa-database" style="color: #dc3545; font-size: 32px; margin-bottom: 10px;"></i>' +
                                '</div>' +
                                '<?= addslashes($mensajeFormateado) ?>' +
                                '<div style="margin-top: 15px; padding: 10px; background: #f7fafc; border-radius: 6px; border-left: 4px solid #4299e1;">' +
                                '<p style="margin: 0; font-size: 13px; color: #2d3748;"><i class="fas fa-info-circle" style="color: #4299e1; margin-right: 5px;"></i><strong>Recomendación:</strong> Verifica la integridad de los datos y contacta al administrador si el problema persiste.</p>' +
                                '</div>' +
                                '</div>',
                            icon: 'error',
                            timer: 10000,
                            showConfirmButton: true,
                            confirmButtonText: '<i class="fas fa-redo"></i> Reintentar',
                            timerProgressBar: true,
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    <?php elseif ($_GET['envio'] == 'empty'): ?>
                        Swal.fire({
                            title: '<i class="fas fa-shopping-cart" style="color: #ffc107;"></i> Carrito vacío',
                            html: '<div style="text-align: center; font-size: 16px;">' +
                                '<i class="fas fa-inbox" style="color: #ffc107; font-size: 24px; margin-bottom: 10px;"></i><br>' +
                                'No hay registros en la memoria para procesar.' +
                                '</div>',
                            icon: 'warning',
                            timer: 7000,
                            showConfirmButton: true,
                            confirmButtonText: '<i class="fas fa-plus"></i> Agregar medicamentos',
                            timerProgressBar: true,
                            customClass: {
                                confirmButton: 'btn btn-warning'
                            }
                        });
                    <?php endif; ?>
                });
            </script>
        <?php endif; ?>

        <form action="" method="post">


            <label for="paciente">Paciente</label>
            <select name="paciente" id="paciente">
                <option value="" disabled selected>Seleccionar Paciente</option>
                <?= $pacientesOptions ?>
            </select>

            <label for="medicamento">Medicamento</label>
            <select name="medicamento" id="medicamento">
                <option value="" disabled selected>Seleccionar Medicamento</option>
                <?= $medicamentosOptions ?>
            </select>

            <?php if (isset($_GET['medicamento'])): ?>
                <!-- El medicamento ya está seleccionado correctamente desde el servidor -->
            <?php endif; ?>



            <label for="lote">Lote</label>
            <select name="lote" id="lote" onchange="actualizarLote()">
                <option value="" disabled selected>Lote/Caducidad/Total</option>
                <?= $lotesOptions ?>
            </select>

            <label for="cantidad">Cantidad</label>
            <input type="number" id="cantidad" name="cantidad" min="1">

            <!-- Contenedor de los botones -->
            <div class="button-container">
                <button type="submit" name="agregar" value="2" class="btn-primary">
                    <i class="fas fa-plus"></i> Agregar a Lista
                </button>
                <button type="submit" name="enviar_medicamentos" value="1" class="btn-secondary">
                    <i class="fas fa-rocket"></i> Enviar Todo
                </button>
            </div>

        </form>

        <hr style="border: none; height: 2px; background: linear-gradient(90deg, transparent, #2b2d7f, transparent); margin: 30px 0;">

        <div style="text-align: center; margin-bottom: 20px;">
            <h3 style="color: #2b2d7f; font-size: 24px; margin: 0; display: flex; align-items: center; justify-content: center; gap: 10px;">
                <span style="background: #2b2d7f; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 14px;"><i class="fas fa-clipboard-list"></i></span>
                ITEMS A SURTIR
            </h3>
        </div>

        <?php
        if (isset($_SESSION['medicamento_seleccionado']) && is_array($_SESSION['medicamento_seleccionado'])) {
            echo "<table>";
            echo "<thead>";
            echo "<tr>";
            echo "<th><i class='fas fa-user'></i> Paciente</th>";
            echo "<th><i class='fas fa-pills'></i> Medicamento</th>";
            echo "<th><i class='fas fa-tag'></i> Lote</th>";
            echo "<th style='text-align: center;'><i class='fas fa-boxes'></i> Cantidad</th>";
            echo "<th style='text-align: right;'><i class='fas fa-dollar-sign'></i> Precio</th>";
            echo "<th style='text-align: center;'><i class='fas fa-cog'></i> Acciones</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            // Iterar sobre los medicamentos
            foreach ($_SESSION['medicamento_seleccionado'] as $index => $medicamento) { // Usar $index para identificar el registro

                // Verificamos si el elemento actual es un array con las claves esperadas
                if (is_array($medicamento) && isset($medicamento['paciente'], $medicamento['medicamento'], $medicamento['lote'], $medicamento['cantidad'])) {
                    echo "<tr>";
                    echo "<td>{$medicamento['paciente']}</td>";
                    echo "<td>{$medicamento['medicamento']}</td>";
                    echo "<td style='font-family: monospace;'>{$medicamento['lote']}</td>";
                    echo "<td style='text-align: center; color: #16a085; font-weight: bold;'>{$medicamento['cantidad']}</td>";
                    echo "<td style='text-align: right; color: #e74c3c; font-weight: bold;'>$" . number_format($medicamento['precio'], 2) . "</td>";

                    // Botón para eliminar este registro (envía el índice $index por POST)
                    echo "<td style='text-align: center;'>
                    <form action='' method='post' style='display:inline;'>
                        <input type='hidden' name='eliminar_index' value='$index'>
                        <button type='submit' class='btn-sm' style='background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none;'>
                            <i class='fas fa-trash'></i> Eliminar
                        </button>
                    </form>
                  </td>";

                    echo "</tr>";
                } else {
                    echo "<tr><td colspan='6' style='text-align: center; color: #e74c3c;'>⚠️ Datos incompletos para el medicamento.</td></tr>";
                }
            }

            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<div style='text-align: center; padding: 40px; background: linear-gradient(135deg, #f8f9ff 0%, #e8ebff 100%); border-radius: 12px; margin: 20px auto; max-width: 600px; border: 2px dashed #2b2d7f;'>";
            echo "<div style='font-size: 48px; margin-bottom: 15px; color: #2b2d7f;'><i class='fas fa-clipboard-list'></i></div>";
            echo "<h4 style='color: #2b2d7f; margin: 0 0 10px 0;'>No hay medicamentos seleccionados</h4>";
            echo "<p style='color: #666; margin: 0;'>Agrega medicamentos usando el formulario de arriba</p>";
            echo "</div>";
        }
        ?>





    </div>

</body>

</html>



<script>
    function actualizarLote() {
        const loteSelect = document.getElementById('lote');
        const selectedOption = loteSelect.options[loteSelect.selectedIndex];
        const cantidadInput = document.getElementById('cantidad');

        if (selectedOption) {
            // Obtener la fecha de caducidad, la cantidad y el existe_id del lote seleccionado
            const caducidad = selectedOption.getAttribute('data-caducidad');
            const cantidad = selectedOption.getAttribute('data-cantidad');
            const stock = selectedOption.getAttribute('data-stock');
            const existeId = selectedOption.value.split('|')[0]; // Extraer solo el existe_id

            // Calcular cantidad ya en carrito para este lote específico
            const cantidadEnCarrito = calcularCantidadEnCarrito(existeId);
            const stockDisponible = parseInt(stock) - cantidadEnCarrito;

            // Actualizar el máximo del input cantidad basado en el stock disponible menos lo ya agregado
            cantidadInput.max = stockDisponible;
            cantidadInput.setAttribute('data-max-stock', stockDisponible);
            cantidadInput.setAttribute('data-existe-id', existeId);


            // Limpiar el valor de cantidad al cambiar de lote
            cantidadInput.value = '';
        }
    }

    function calcularCantidadEnCarrito(existeId) {
        // Esta función calcula cuánto ya está agregado al carrito para este lote específico
        let cantidadTotal = 0;

        // Obtener todas las filas de la tabla del carrito
        const filas = document.querySelectorAll('table tr');

        <?php
        // Generar JavaScript con los datos del carrito desde PHP
        if (isset($_SESSION['medicamento_seleccionado']) && is_array($_SESSION['medicamento_seleccionado'])) {
            echo "const carritoData = [";
            foreach ($_SESSION['medicamento_seleccionado'] as $item) {
                echo "{existe_id: '" . $item['existe_id'] . "', cantidad: " . intval($item['cantidad']) . "},";
            }
            echo "];";
        } else {
            echo "const carritoData = [];";
        }
        ?>

        // Sumar las cantidades del mismo lote
        carritoData.forEach(function(item) {
            if (item.existe_id == existeId) {
                cantidadTotal += item.cantidad;
            }
        });

        return cantidadTotal;
    }

    function validarCantidad() {
        const cantidadInput = document.getElementById('cantidad');
        const loteSelect = document.getElementById('lote');
        const maxStock = parseInt(cantidadInput.getAttribute('data-max-stock') || 0);
        const cantidadIngresada = parseInt(cantidadInput.value || 0);
        const existeId = cantidadInput.getAttribute('data-existe-id');

        if (loteSelect.value === '') {
            Swal.fire({
                title: 'Error',
                text: 'Primero debes seleccionar un lote.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            cantidadInput.value = '';
            return false;
        }

        if (cantidadIngresada > maxStock) {
            const cantidadEnCarrito = calcularCantidadEnCarrito(existeId);
            const stockOriginal = parseInt(loteSelect.options[loteSelect.selectedIndex].getAttribute('data-stock'));

            Swal.fire({
                title: 'Cantidad excede el stock disponible',
                html: `La cantidad ingresada (${cantidadIngresada}) no puede ser mayor al stock disponible.<br><br>` +
                    `<strong>Stock total:</strong> ${stockOriginal}<br>` +
                    `<strong>Ya en carrito:</strong> ${cantidadEnCarrito}<br>` +
                    `<strong>Disponible para agregar:</strong> ${maxStock}`,
                icon: 'error',
                confirmButtonText: 'OK'
            });
            cantidadInput.value = maxStock > 0 ? maxStock : '';
            return false;
        }

        if (cantidadIngresada <= 0) {
            Swal.fire({
                title: 'Cantidad inválida',
                text: 'La cantidad debe ser mayor a 0.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            cantidadInput.value = '';
            return false;
        }

        return true;
    }

    // Validar cuando el usuario cambie el valor del campo cantidad
    document.addEventListener('DOMContentLoaded', function() {
        const cantidadInput = document.getElementById('cantidad');
        const loteSelect = document.getElementById('lote');
        const medicamentoSelect = document.getElementById('medicamento');
        const pacienteSelect = document.getElementById('paciente');

        // Manejar el cambio de medicamento
        medicamentoSelect.addEventListener('change', function() {
            const pacienteSeleccionado = pacienteSelect.value;
            const medicamentoSeleccionado = this.value;

            if (pacienteSeleccionado && medicamentoSeleccionado) {
                // Enviar el formulario con los valores actuales
                window.location.href = `surtir_pacienteq.php?paciente=${pacienteSeleccionado}&medicamento=${medicamentoSeleccionado}`;
            }
        });

        // Actualizar información de stock cuando cambie el lote
        loteSelect.addEventListener('change', function() {
            actualizarLote();
        });

        // Validar antes de enviar el formulario
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            // Solo validar si es el botón "Agregar"
            const submitButton = document.activeElement;
            if (submitButton && submitButton.name === 'agregar') {
                if (!validarCantidad()) {
                    e.preventDefault();
                    return false;
                }
            }
        });

        // Si hay un lote ya seleccionado al cargar la página, actualizar la información
        if (loteSelect.value) {
            actualizarLote();
        }

        // También actualizar si hay medicamento seleccionado al cargar la página
        if (medicamentoSelect.value) {
            // Esperar un poco para que se carguen los lotes
            setTimeout(function() {
                if (loteSelect.options.length > 1) { // Más de una opción (la default)
                    // Seleccionar el primer lote disponible si no hay uno seleccionado
                    if (!loteSelect.value) {
                        loteSelect.selectedIndex = 1; // Seleccionar el primer lote real
                        actualizarLote();
                    }
                }
            }, 100);
        }
    });
</script>

<style>
    .form-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 30px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(43, 45, 127, 0.15);
        position: relative;
        overflow: hidden;
    }

    .form-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #2b2d7f, #4a4eb7, #2b2d7f);
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #2b2d7f;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    label::before {
        content: '•';
        color: #2b2d7f;
        font-weight: bold;
        font-size: 18px;
    }

    select,
    input {
        width: 100%;
        padding: 12px 16px;
        margin-bottom: 20px;
        border: 2px solid #e8ebff;
        border-radius: 10px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: white;
        box-sizing: border-box;
    }

    select:focus,
    input:focus {
        outline: none;
        border-color: #2b2d7f;
        box-shadow: 0 0 0 3px rgba(43, 45, 127, 0.1);
        transform: translateY(-1px);
    }

    select:hover,
    input:hover {
        border-color: #4a4eb7;
    }

    .button-container {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 25px;
        flex-wrap: wrap;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2b2d7f 0%, #1a1c5a 100%);
        color: white;
        border: none;
        padding: 14px 28px;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(43, 45, 127, 0.3);
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 160px;
        justify-content: center;
        flex: 1;
        max-width: 200px;
    }

    .btn-secondary {
        background: linear-gradient(135deg, #16a085 0%, #138d75 100%);
        color: white;
        border: none;
        padding: 14px 28px;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(22, 160, 133, 0.3);
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 160px;
        justify-content: center;
        flex: 1;
        max-width: 200px;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(43, 45, 127, 0.4);
        background: linear-gradient(135deg, #3a3d8f 0%, #2a2c6a 100%);
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(22, 160, 133, 0.4);
        background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%);
    }

    button:disabled {
        background: #bdc3c7 !important;
        cursor: not-allowed !important;
        transform: none !important;
        box-shadow: none !important;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 15px;
        max-width: 400px;
        margin: 0 auto;
    }

    /* Estilos para iconos */
    .fas {
        margin-right: 8px;
    }

    .btn-primary .fas,
    .btn-secondary .fas {
        margin-right: 8px;
        font-size: 14px;
    }

    /* Animaciones */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-container {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Estilos de tabla mejorados - Exactamente iguales a pedir_almacenq.php */
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(43, 45, 127, 0.15);
        animation: fadeInUp 0.6s ease-out;
    }

    table thead {
        background: linear-gradient(135deg, #2b2d7f 0%, #1a1c5a 100%);
        color: white;
    }

    table thead th {
        padding: 16px 15px;
        text-align: left;
        font-weight: 600;
        font-size: 15px;
        letter-spacing: 0.5px;
        text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        border-bottom: none;
        position: relative;
    }

    table thead th:not(:last-child)::after {
        content: '';
        position: absolute;
        right: 0;
        top: 20%;
        height: 60%;
        width: 1px;
        background: rgba(255, 255, 255, 0.2);
    }

    table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #e8ebff;
    }

    table tbody tr:nth-child(odd) {
        background: #f8f9ff;
    }

    table tbody tr:hover {
        background: #e8ebff !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(43, 45, 127, 0.1);
    }

    table tbody td {
        padding: 16px 15px;
        font-size: 15px;
        color: #333;
        vertical-align: middle;
        border-bottom: 1px solid #e8ebff;
    }

    table tbody td:first-child {
        font-weight: 600;
        color: #2b2d7f;
    }

    /* Botones dentro de tabla */
    .btn-sm {
        padding: 10px 16px !important;
        font-size: 14px !important;
        min-width: 100px !important;
        font-weight: 600 !important;
        border-radius: 8px !important;
        transition: all 0.3s ease !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        margin: 2px !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-container {
            margin: 10px;
            padding: 20px;
        }

        .button-container {
            flex-direction: column;
            align-items: center;
        }

        .btn-primary,
        .btn-secondary {
            width: 100%;
            max-width: none;
            margin-bottom: 10px;
        }

        .btn-secondary {
            margin-bottom: 0;
        }

        table thead th,
        table tbody td {
            padding: 12px 10px;
            font-size: 14px;
        }

        .btn-sm {
            padding: 8px 12px !important;
            font-size: 12px !important;
            min-width: 80px !important;
        }
    }
</style>