<?php
include "../../conexionbd.php";
session_start();
ob_start();

$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];

date_default_timezone_set('America/Guatemala');

// Debug: Mostrar el estado actual de la sesión
echo "<script>console.log('Estado inicial de sesión medicamentos: " . json_encode($_SESSION['medicamento_seleccionado'] ?? []) . "');</script>";

if (isset($usuario['id_rol'])) {
    if ($usuario['id_rol'] == 11 || $usuario['id_rol'] == 4 || $usuario['id_rol'] == 5 || $usuario['id_rol'] == 1 || $usuario['id_rol'] == 9) {
        include "../header_farmaciah.php";
    } else {
        session_unset();
        session_destroy();
        echo "<script>window.location='../../index.php';</script>";
        exit();
    }
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
        // Verificar si el paciente está seleccionado
        $selected = (isset($_SESSION['paciente_seleccionado']) && $_SESSION['paciente_seleccionado'] == $paciente['id_atencion']) ? 'selected' : '';
        $pacientesOptions .= "<option value='{$paciente['id_atencion']}' $selected>{$paciente['nombre_paciente']}</option>";
    }
}

// Guardar el id_atencion en la sesión cuando se seleccione el paciente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['paciente'])) {
    $_SESSION['paciente_seleccionado'] = $_POST['paciente'];
}



$queryMedicamentos = "SELECT DISTINCT 
    ea.item_id, 
    CONCAT(ia.item_name, ', ', ia.item_grams) AS item_name
    FROM existencias_almacenh ea 
    JOIN item_almacen ia ON ea.item_id = ia.item_id 
    WHERE ea.existe_qty > 0 AND ia.activo = 'SI'
    ORDER BY ia.item_name
";

$resultMedicamentos = $conexion->query($queryMedicamentos);

// Debug para ver si hay medicamentos
if (!$resultMedicamentos) {
    echo "<script>console.log('Error en consulta medicamentos: " . addslashes($conexion->error) . "');</script>";
} else {
    echo "<script>console.log('Medicamentos encontrados: " . $resultMedicamentos->num_rows . "');</script>";
}

$medicamentosOptions = '';
if ($resultMedicamentos && $resultMedicamentos->num_rows > 0) {
    while ($medicamento = $resultMedicamentos->fetch_assoc()) {
        $selected = (isset($_POST['medicamento']) && $_POST['medicamento'] == $medicamento['item_id']) ? 'selected' : '';
        $medicamentosOptions .= "<option value='{$medicamento['item_id']}' $selected>{$medicamento['item_name']}</option>";
    }
}

// Obtener los lotes y la suma total de existencias para el medicamento seleccionado
$lotesOptions = '';
$totalExistencias = 0; // Variable para el total de existencias
if (isset($_POST['medicamento']) && !empty($_POST['medicamento'])) {
    $itemId = intval($_POST['medicamento']);

    // Debug
    echo "<script>console.log('Medicamento seleccionado ID: $itemId');</script>";

    // Primero, obtener el total de existencias de este medicamento
    $sqlTotalExistencias = "
        SELECT SUM(ea.existe_qty) AS total_existencias
        FROM existencias_almacenh ea
        WHERE ea.item_id = ? AND ea.existe_qty > 0
    ";
    $stmtTotal = $conexion->prepare($sqlTotalExistencias);
    $stmtTotal->bind_param('i', $itemId);
    $stmtTotal->execute();
    $resultTotalExistencias = $stmtTotal->get_result();
    if ($resultTotalExistencias && $resultTotalExistencias->num_rows > 0) {
        $row = $resultTotalExistencias->fetch_assoc();
        $totalExistencias = $row['total_existencias'] ?? 0;
    }
    $stmtTotal->close();

    echo "<script>console.log('Total existencias: $totalExistencias');</script>";

    // Ahora obtenemos los lotes disponibles para este medicamento
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
        echo "<script>console.log('Error preparando consulta lotes: " . $conexion->error . "');</script>";
    } else {
        $stmt->bind_param('i', $itemId);
        $stmt->execute();
        $resultLotes = $stmt->get_result();

        echo "<script>console.log('Lotes encontrados: " . $resultLotes->num_rows . "');</script>";

        // Comprobar si hay resultados
        $lotesOptions = '';
        if ($resultLotes && $resultLotes->num_rows > 0) {
            while ($lote = $resultLotes->fetch_assoc()) {
                $lotesOptions .= "<option value='{$lote['existe_id']}|{$lote['existe_lote']}|$itemId' data-caducidad='{$lote['existe_caducidad']}' data-cantidad='{$lote['existe_qty']}'>
        {$lote['existe_lote']} / {$lote['existe_caducidad']} / {$lote['existe_qty']}
        </option>";
            }
        } else {
            $lotesOptions .= "<option value='' disabled>No hay lotes disponibles para este medicamento</option>";
        }

        // Cerrar la declaración
        $stmt->close();
    }
}

// Captura del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['paciente']) && isset($_POST['medicamento']) && isset($_POST['lote']) && isset($_POST['cantidad'])) {
    list($existeId, $nombreLote, $itemId) = explode('|', $_POST['lote']);
    $itemId = intval($itemId);
    // Consulta para obtener el id_atencion
    $sqlAtencion = "SELECT id_atencion FROM dat_ingreso WHERE Id_exp = (SELECT Id_exp FROM dat_ingreso WHERE id_atencion = ?)";
    $stmtAtencion = $conexion->prepare($sqlAtencion);
    $stmtAtencion->bind_param('i', $_POST['paciente']);
    $stmtAtencion->execute();
    $stmtAtencion->bind_result($idAtencion);
    $stmtAtencion->fetch();
    $stmtAtencion->close();

    // Guardar el id_atencion en la sesión
    $_SESSION['id_atencion'] = $idAtencion;


    $sqlPacienteNombre = "SELECT CONCAT(nom_pac, ' ', papell, ' ', sapell) AS nombre_paciente FROM paciente WHERE Id_exp = (SELECT Id_exp FROM dat_ingreso WHERE id_atencion = ?)";
    $stmtPaciente = $conexion->prepare($sqlPacienteNombre);
    $stmtPaciente->bind_param('i', $_POST['paciente']);
    $stmtPaciente->execute();
    $stmtPaciente->bind_result($nombrePaciente);
    $stmtPaciente->fetch();
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

        // Si la variable de sesión no está inicializada, la creamos como un array vacío
        if (!isset($_SESSION['medicamento_seleccionado'])) {
            $_SESSION['medicamento_seleccionado'] = [];
        }

        // Agregar el nuevo registro al array de la sesión
        $nuevoMedicamento = [
            'paciente' => $nombrePaciente,
            'item_id' => $itemId,
            'medicamento' => $nombreMedicamento,
            'lote' => $nombreLote,
            'cantidad' => $_POST['cantidad'],
            'existe_id' => $existeId,
            'id_atencion' => $idAtencion,
            'precio' => $precioMedicamento
        ];
        
        $_SESSION['medicamento_seleccionado'][] = $nuevoMedicamento;

        // Debug para verificar que se guardó correctamente
        echo "<script>console.log('Medicamento agregado: " . addslashes(json_encode($nuevoMedicamento)) . "');</script>";
        echo "<script>console.log('Total medicamentos en sesión: " . count($_SESSION['medicamento_seleccionado']) . "');</script>";

        echo "<script>
      alert('Medicamento agregado correctamente a la lista.');
      window.location.href = 'surtir_pacienteq.php';
    </script>";
        exit();
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_index']) && !isset($_POST['enviar_medicamentos'])) {
    $index = intval($_POST['eliminar_index']); // Asegurarse de que sea un número entero
    if (isset($_SESSION['medicamento_seleccionado'][$index])) {
        unset($_SESSION['medicamento_seleccionado'][$index]); // Eliminar el registro
        $_SESSION['medicamento_seleccionado'] = array_values($_SESSION['medicamento_seleccionado']); // Reindexar array
        echo "<script>alert('Medicamento eliminado de la lista.');</script>";
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['enviar_medicamentos'])) {
    $fechaActual = date('Y-m-d H:i:s');

    // Debug para verificar el contenido de la sesión
    echo "<script>console.log('Enviando medicamentos...');</script>";
    echo "<script>console.log('Sesión medicamento_seleccionado: " . json_encode($_SESSION['medicamento_seleccionado'] ?? []) . "');</script>";

    if (!isset($_SESSION['medicamento_seleccionado']) || empty($_SESSION['medicamento_seleccionado'])) {
        echo "<script>alert('No hay registros en la memoria para procesar.\\nAsegúrate de agregar medicamentos a la lista antes de enviar.'); 
            window.location.href = 'surtir_pacienteq.php';</script>";
        exit();
    }
    // Iniciar transacción
    $conexion->begin_transaction();

    try {
        foreach ($_SESSION['medicamento_seleccionado'] as $index => $medicamento) {
            $paciente = $medicamento['paciente'];
            $nombreMedicamento = $medicamento['medicamento'];
            $loteNombre = $medicamento['lote'];
            $cantidadLote = $medicamento['cantidad'];
            $existeId = $medicamento['existe_id'];
            $Id_Atencion = $medicamento['id_atencion'];
            $itemId = $medicamento['item_id'];
            $sqlMedicamentoNombre = "SELECT  CONCAT(item_name, ', ', item_grams) AS item_name FROM item_almacen WHERE item_id = ?";
            $stmtMedicamento = $conexion->prepare($sqlMedicamentoNombre);
            $stmtMedicamento->bind_param('i', $itemId);
            $stmtMedicamento->execute();
            $stmtMedicamento->bind_result($nombreMedicamento);
            $stmtMedicamento->fetch();
            $stmtMedicamento->close();




            $queryItemAlmacenn = "
            SELECT 
                item_name, 
                item_grams,
                item_price 
            FROM 
                item_almacen 
           WHERE item_id = ?";

            $stmt = $conexion->prepare($queryItemAlmacenn);
            $stmt->bind_param("i", $itemId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $itemData = $result->fetch_assoc();
                $itemName = $itemData['item_name'].', '.$itemData['item_grams'];
                $salidaCostsu = $itemData['item_price'];
            } else {
                exit("Error: No se encontró el ítem con ID $itemId.");
            }


            // *** 2. Obtener existencias actuales del lote desde existencias_almacenh ***
            $selectExistenciasQuery = "SELECT existe_qty, existe_caducidad, existe_salidas FROM existencias_almacenh 
            WHERE existe_id = ?";
            $stmtSelect = $conexion->prepare($selectExistenciasQuery);
            $stmtSelect->bind_param('i', $existeId);
            $stmtSelect->execute();
            if (!$stmtSelect) {
                throw new Exception("Error en la preparación de la consulta: " . $conexion->error);
            }
            $stmtSelect->bind_result($existeQty, $caducidad, $existeSalidas);
            $stmtSelect->fetch();
            $stmtSelect->close();


            // *** 4. Calcular nuevos valores para existencias ***
            $nuevaExistenciaQty = $existeQty - $cantidadLote;
            $nuevaExistenciaSalidas = $existeSalidas + $cantidadLote;


            // Validar si hay suficiente stock
            if ($existeQty < $cantidadLote) {
                echo "<script>
                alert('Error: El lote \"$loteNombre\" no tiene suficiente stock. Disponible: $existeQty, requerido: $cantidadLote.');
                window.location.href = 'surtir_pacienteq.php';
                </script>";
                exit;
            }



            // *** 6. Insertar en kardex_almacenh ***
            $insert_kardex = "
               INSERT INTO kardex_almacenh (
                   kardex_fecha, item_id, kardex_lote, kardex_caducidad, kardex_inicial, kardex_entradas, kardex_salidas, kardex_qty, 
                   kardex_dev_stock, kardex_dev_merma, kardex_movimiento, kardex_destino, id_surte
               ) 
               VALUES (NOW(), ?, ?, ?, 0, 0, ?, 0, 0, 0, 'Salida', 'QUIROFANO', ?)
           ";
            $stmt_kardex = $conexion->prepare($insert_kardex);
            if (!$stmt_kardex) {
                throw new Exception("Error en la preparación de la consulta: " . $conexion->error);
            }
            $stmt_kardex->bind_param('issii', $itemId, $loteNombre, $caducidad, $cantidadLote, $id_usua);
            if (!$stmt_kardex->execute()) {
                throw new Exception("Error al insertar en kardex_almacenh: " . $stmt_kardex->error);
            }
            $stmt_kardex->close();

                
            // *** 7. Insertar en salidas_almacenh ***
            
             $salio = "QUIROFANO";
                
            $queryInsercion = "
                INSERT INTO salidas_almacenh (
                    item_id, 
                    item_name, 
                    salida_fecha, 
                    salida_lote, 
                    salida_caducidad, 
                    salida_qty, 
                    salida_costsu, 
                    id_usua, 
                    id_atencion, 
                    solicita, 
                    fecha_solicitud,
                    salio
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?, ?)
             ";
            $stmtInsertSalida = $conexion->prepare($queryInsercion);
            if (!$stmtInsertSalida) {
                throw new Exception("Error en la preparación de la consulta: " . $conexion->error);
            }
            $stmtInsertSalida->bind_param(
                "issssdiisss",
                $itemId,
                $nombreMedicamento,
                $fechaActual,
                $loteNombre,
                $caducidad,
                $cantidadLote,
                $salidaCostsu,
                $id_usua,
                $Id_Atencion,
                $fechaActual,
                $salio
            );

            if (!$stmtInsertSalida->execute()) {
                throw new Exception("Error al insertar en salidas_almacenh: " . $stmtInsertSalida->error);
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
                throw new Exception("Error en la preparación de la consulta: " . $conexion->error);
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
                throw new Exception("Error al insertar en dat_ctapac: " . $stmtInsertDatCtapac->error);
            }
            $stmtInsertDatCtapac->close();

            // *** 9. Insertar en cart_recib ***
            /*        $ingresar2 = $conexion->query("INSERT INTO cart_recib(item_id, solicita, almacen, id_usua, confirma) VALUES ($itemId, $cantidadLote, 'QUIROFANO', $id_usua, 'SI')");
            if (!$ingresar2) {
            throw new Exception("Error al insertar en cart_recib: " . $conexion->error);
            }*/



            // *** 5. Actualizar existencias en la tabla existencias_almacenh ***
            $updateExistenciasQuery = "UPDATE existencias_almacenh SET existe_qty = ?, existe_fecha = ?, existe_salidas = ? WHERE existe_id = ?";
            $stmtUpdateExistencias = $conexion->prepare($updateExistenciasQuery);
            if (!$stmtUpdateExistencias) {
                throw new Exception("Error en la preparación de la consulta: " . $conexion->error);
            }
            $stmtUpdateExistencias->bind_param('isii', $nuevaExistenciaQty, $fechaActual, $nuevaExistenciaSalidas, $existeId);
            if (!$stmtUpdateExistencias->execute()) {
                throw new Exception("Error al actualizar las existencias: " . $stmtUpdateExistencias->error);
            }
            $stmtUpdateExistencias->close();
        }

        // Confirmar la transacción
        $conexion->commit();


        // Limpiar la sesión (opcional)
        unset($_SESSION['medicamento_seleccionado']);


        echo "<script>alert('Los medicamentos han sido registrados correctamente.'); window.location.href = 'surtir_pacienteq.php';</script>";
    } catch (Exception $e) {
        $conexion->rollback(); // Revertir cambios
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href = 'surtir_pacienteq.php';</script>";
    }
}





?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <a href="../../template/menu_farmaciahosp.php"
        style='color: white; margin-left: 30px; margin-bottom: 20px; background: linear-gradient(135deg, #2b2d7f 0%, #1a1c5a 100%); 
          border: none; border-radius: 8px; padding: 8px 16px; cursor: pointer; display: inline-block; text-decoration: none;
          box-shadow: 0 2px 8px rgba(43, 45, 127, 0.3); transition: all 0.3s ease;'>
        ← Regresar
    </a>
    <div class="form-container">
        <div class="thead" style="background: linear-gradient(135deg, #2b2d7f 0%, #1a1c5a 100%); margin: 5px auto; padding: 15px 25px; color: white; width: fit-content; text-align: center; border-radius: 15px; box-shadow: 0 4px 15px rgba(43, 45, 127, 0.3);">
            <h1 style="font-size: 28px; margin: 0; font-weight: 600; text-shadow: 0 2px 4px rgba(0,0,0,0.3);"><i class="fas fa-pills"></i> SURTIR PACIENTE</h1>
        </div>
        <br>

        <form action="" method="post">


            <label for="paciente">Paciente</label>
            <select name="paciente" id="paciente">
                <option value="" disabled selected>Seleccionar Paciente</option>
                <?= $pacientesOptions ?>
            </select>

            <label for="medicamento">Medicamento</label>
            <select name="medicamento" id="medicamento" onchange="this.form.submit()" required>
                <option value="" disabled selected>Seleccionar Medicamento</option>
                <?= $medicamentosOptions ?>
            </select>
            
            <!-- Mostrar información del medicamento seleccionado -->
            <?php if (isset($_POST['medicamento'])): ?>
                <div style="background: linear-gradient(135deg, #e8ebff 0%, #f0f2ff 100%); padding: 15px; border-radius: 10px; margin: 15px 0; border-left: 4px solid #2b2d7f; box-shadow: 0 2px 8px rgba(43, 45, 127, 0.1);">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="background: #2b2d7f; color: white; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 12px;"><i class="fas fa-check"></i></div>
                        <div>
                            <strong style="color: #2b2d7f;">Medicamento seleccionado:</strong> ID <?= $_POST['medicamento'] ?><br>
                            <strong style="color: #2b2d7f;"><i class="fas fa-boxes"></i> Total existencias disponibles:</strong> <span style="color: #16a085; font-weight: bold;"><?= $totalExistencias ?> unidades</span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>



            <label for="lote">Lote</label>
            <select name="lote" id="lote" onchange="actualizarLote()">
                <option value="" disabled selected>Lote/Caducidad/Total</option>
                <?= $lotesOptions ?>
            </select>

            <label for="cantidad">Cantidad</label>
            <input type="number" id="cantidad" name="cantidad" min="1">
            <!-- Mostrar el existe_id del lote seleccionado -->
            <div id="existe_id_display" style="margin-top: 10px; font-weight: bold;"></div>

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



        // Eliminar el registro si se envió el índice por POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_index'])) {
            $index = intval($_POST['eliminar_index']); // Asegurar que el índice sea un entero
            if (isset($_SESSION['medicamento_seleccionado'][$index])) {
                unset($_SESSION['medicamento_seleccionado'][$index]); // Eliminar el registro
                $_SESSION['medicamento_seleccionado'] = array_values($_SESSION['medicamento_seleccionado']); // Reindexar el array
            }
        }

        if (isset($_SESSION['medicamento_seleccionado']) && is_array($_SESSION['medicamento_seleccionado'])) {
            echo "<div style='background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(43, 45, 127, 0.1); overflow: hidden; margin: 20px auto; max-width: 95%;'>";
            echo "<table style='width: 100%; border-collapse: collapse; font-size: 16px;'>";
            echo "<thead>";
            echo "<tr style='background: linear-gradient(135deg, #2b2d7f 0%, #1a1c5a 100%); color: white;'>";
            echo "<th style='padding: 15px; text-align: left; font-weight: 600;'><i class='fas fa-user'></i> Paciente</th>";
            echo "<th style='padding: 15px; text-align: left; font-weight: 600;'><i class='fas fa-pills'></i> Medicamento</th>";
            echo "<th style='padding: 15px; text-align: left; font-weight: 600;'><i class='fas fa-tag'></i> Lote</th>";
            echo "<th style='padding: 15px; text-align: center; font-weight: 600;'><i class='fas fa-boxes'></i> Cantidad</th>";
            echo "<th style='padding: 15px; text-align: right; font-weight: 600;'><i class='fas fa-dollar-sign'></i> Precio</th>";
            echo "<th style='padding: 15px; text-align: center; font-weight: 600;'><i class='fas fa-cog'></i> Acciones</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            // Iterar sobre los medicamentos
            foreach ($_SESSION['medicamento_seleccionado'] as $index => $medicamento) {
                $rowClass = $index % 2 == 0 ? "background: #f8f9ff;" : "background: white;";
                
                if (is_array($medicamento) && isset($medicamento['paciente'], $medicamento['medicamento'], $medicamento['lote'], $medicamento['cantidad'])) {
                    echo "<tr style='$rowClass border-bottom: 1px solid #e8ebff; transition: all 0.3s ease;' onmouseover='this.style.background=\"#f0f2ff\"' onmouseout='this.style.background=\"" . ($index % 2 == 0 ? "#f8f9ff" : "white") . "\"'>";
                    echo "<td style='padding: 15px; color: #2b2d7f; font-weight: 500;'>{$medicamento['paciente']}</td>";
                    echo "<td style='padding: 15px; color: #333;'>{$medicamento['medicamento']}</td>";
                    echo "<td style='padding: 15px; color: #666; font-family: monospace;'>{$medicamento['lote']}</td>";
                    echo "<td style='padding: 15px; text-align: center; color: #16a085; font-weight: bold;'>{$medicamento['cantidad']}</td>";
                    echo "<td style='padding: 15px; text-align: right; color: #e74c3c; font-weight: bold;'>$" . number_format($medicamento['precio'], 2) . "</td>";

                    echo "<td style='padding: 15px; text-align: center;'>
                    <form action='' method='post' style='display:inline;'>
                        <input type='hidden' name='eliminar_index' value='$index'>
                        <button type='submit' style='background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 6px rgba(231, 76, 60, 0.3);' onmouseover='this.style.transform=\"translateY(-1px)\"; this.style.boxShadow=\"0 4px 8px rgba(231, 76, 60, 0.4)\"' onmouseout='this.style.transform=\"none\"; this.style.boxShadow=\"0 2px 6px rgba(231, 76, 60, 0.3)\"'>
                            <i class='fas fa-trash'></i> Eliminar
                        </button>
                    </form>
                  </td>";

                    echo "</tr>";
                } else {
                    echo "<tr style='$rowClass'><td colspan='6' style='padding: 15px; text-align: center; color: #e74c3c;'>⚠️ Datos incompletos para el medicamento.</td></tr>";
                }
            }

            echo "</tbody>";
            echo "</table>";
            echo "</div>";
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

        if (selectedOption) {
            // Obtener la fecha de caducidad, la cantidad y el existe_id del lote seleccionado
            const caducidad = selectedOption.getAttribute('data-caducidad');
            const cantidad = selectedOption.getAttribute('data-cantidad');
            const existeId = selectedOption.value; // El existe_id del lote seleccionado

            // Mostrar estos valores en los inputs correspondientes
            document.getElementById('caducidad').value = caducidad;
            document.getElementById('cantidad-lote').value = cantidad;

            // Mostrar el existe_id en un lugar visible del formulario
            document.getElementById('existe_id_display').textContent = "Existe ID del lote seleccionado: " + existeId;

            // Imprimir el existe_id en la consola
            console.log("existe_id del lote seleccionado: " + existeId);
        }
    }
</script>
<style>
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 20px;
        min-height: 100vh;
    }

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

    select, input {
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

    select:focus, input:focus {
        outline: none;
        border-color: #2b2d7f;
        box-shadow: 0 0 0 3px rgba(43, 45, 127, 0.1);
        transform: translateY(-1px);
    }

    select:hover, input:hover {
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

    .btn-primary .fas, .btn-secondary .fas {
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
        
        .btn-primary, .btn-secondary {
            width: 100%;
            max-width: none;
            margin-bottom: 10px;
        }
        
        .btn-secondary {
            margin-bottom: 0;
        }
    }
</style>