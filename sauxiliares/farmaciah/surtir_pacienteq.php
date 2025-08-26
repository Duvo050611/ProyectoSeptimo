<?php
include "../../conexionbd.php";
session_start();
ob_start();

$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];

date_default_timezone_set('America/Guatemala');

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



$queryMedicamentos = "
    SELECT DISTINCT 
        ea.item_id, 
        CONCAT(ia.item_name, ', ', ia.item_grams) AS item_name
    FROM existencias_almacenh AS ea
    INNER JOIN item_almacen AS ia 
        ON ea.item_id = ia.item_id
    ORDER BY item_name
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
$totalExistencias = 0; // Variable para el total de existencias
if (isset($_POST['medicamento'])) {
    $itemId = intval($_POST['medicamento']);

    // Primero, obtener el total de existencias de este medicamento
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
    $stmt->bind_param('i', $itemId);
    $stmt->execute();
    $resultLotes = $stmt->get_result();

    // Comprobar si hay resultados
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

    // Cerrar la declaración
    $stmt->close();
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
        $_SESSION['medicamento_seleccionado'][] = [
            'paciente' => $nombrePaciente,
            'item_id' => $itemId,
            'medicamento' => $nombreMedicamento,
            'lote' => $nombreLote,
            'cantidad' => $_POST['cantidad'],
            'existe_id' => $existeId,
            'id_atencion' => $idAtencion,
            'precio' => $precioMedicamento
        ];

        echo "<script>
      window.location.href = 'surtir_pacienteq.php';
    </script>";
        exit();
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_index'])) {
    $index = intval($_POST['eliminar_index']); // Asegurarse de que sea un número entero
    if (isset($_SESSION['medicamento_seleccionado'][$index])) {
        unset($_SESSION['medicamento_seleccionado'][$index]); // Eliminar el registro
        $_SESSION['medicamento_seleccionado'] = array_values($_SESSION['medicamento_seleccionado']); // Reindexar array
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['enviar_medicamentos'])) {
    $fechaActual = date('Y-m-d H:i:s');

    if (!isset($_SESSION['medicamento_seleccionado']) || empty($_SESSION['medicamento_seleccionado'])) {
        echo "<script>alert('No hay registros en la memoria para procesar.'); 
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
                item_price,
                item_grams
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
    <title>INEO Metepec</title>
    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <link href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet"
          integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
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
    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

</head>

<body>
<!-- Botón regresar -->
<a href="../../template/menu_farmaciahosp.php" class="btn-moderno btn-regresar" style="margin:20px;">
    <i class="fa-solid fa-arrow-left"></i> Regresar
</a>

<!-- Contenedor principal -->
<div class="container-moderno">
    <!-- Header -->
    <div class="header-principal">
        <span class="icono-principal"><i class="fa-solid fa-prescription-bottle-medical"></i></span>
        <h1><i class="fa-solid fa-user-injured"></i> SURTIR PACIENTE</h1>
    </div>

    <!-- Formulario -->
    <form action="" method="post" class="contenedor-filtros">

        <!-- Paciente -->
        <label for="paciente" class="form-label">
            <i class="fa-solid fa-user"></i> Paciente
        </label>
        <select name="paciente" id="paciente" class="form-control">
            <option value="" disabled selected>Seleccionar Paciente</option>
            <?= $pacientesOptions ?>
        </select>

        <!-- Medicamento -->
        <label for="medicamento" class="form-label">
            <i class="fa-solid fa-pills"></i> Medicamento
        </label>
        <select name="medicamento" id="medicamento" class="form-control" onchange="this.form.submit()">
            <option value="" disabled selected>Seleccionar Medicamento</option>
            <?= $medicamentosOptions ?>
        </select>

        <!-- Lote -->
        <label for="lote" class="form-label">
            <i class="fa-solid fa-barcode"></i> Lote
        </label>
        <select name="lote" id="lote" class="form-control" onchange="actualizarLote()">
            <option value="" disabled selected>Lote/Caducidad/Total</option>
            <?= $lotesOptions ?>
        </select>

        <!-- Cantidad -->
        <label for="cantidad" class="form-label">
            <i class="fa-solid fa-hashtag"></i> Cantidad
        </label>
        <input type="number" id="cantidad" name="cantidad" min="1" class="form-control">

        <!-- Existe ID -->
        <div id="existe_id_display" style="margin-top: 10px; font-weight: bold; color: var(--color-primario);"></div>

        <!-- Botones -->
        <div style="margin-top:20px; display:flex; gap:10px; justify-content:center;">
            <button type="submit" name="agregar" value="2" class="btn-moderno btn-especial">
                <i class="fa-solid fa-plus"></i> Agregar
            </button>
            <button type="submit" name="enviar_medicamentos" value="1" class="btn-moderno btn-filtrar">
                <i class="fa-solid fa-paper-plane"></i> Enviar
            </button>
        </div>
    </form>

    <hr>

    <!-- Tabla -->
    <h3 style="text-align:center; color: var(--color-primario);">
        <i class="fa-solid fa-list"></i> ITEMS A SURTIR
    </h3>

    <div class="tabla-contenedor">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_index'])) {
            $index = intval($_POST['eliminar_index']);
            if (isset($_SESSION['medicamento_seleccionado'][$index])) {
                unset($_SESSION['medicamento_seleccionado'][$index]);
                $_SESSION['medicamento_seleccionado'] = array_values($_SESSION['medicamento_seleccionado']);
            }
        }

        if (isset($_SESSION['medicamento_seleccionado']) && is_array($_SESSION['medicamento_seleccionado'])) {
            echo "<table class='table-moderna'>";
            echo "<thead><tr>
                        <th><i class='fa-solid fa-user'></i> Paciente</th>
                        <th><i class='fa-solid fa-capsules'></i> Medicamento</th>
                        <th><i class='fa-solid fa-boxes-stacked'></i> Lote</th>
                        <th><i class='fa-solid fa-hashtag'></i> Cantidad</th>
                        <th><i class='fa-solid fa-dollar-sign'></i> Precio</th>
                        <th><i class='fa-solid fa-gears'></i> Acciones</th>
                      </tr></thead><tbody>";

            foreach ($_SESSION['medicamento_seleccionado'] as $index => $medicamento) {
                if (is_array($medicamento) && isset($medicamento['paciente'], $medicamento['medicamento'], $medicamento['lote'], $medicamento['cantidad'])) {
                    echo "<tr>";
                    echo "<td>{$medicamento['paciente']}</td>";
                    echo "<td>{$medicamento['medicamento']}</td>";
                    echo "<td>{$medicamento['lote']}</td>";
                    echo "<td>{$medicamento['cantidad']}</td>";
                    echo "<td>{$medicamento['precio']}</td>";
                    echo "<td>
                                <form action='' method='post' style='display:inline;'>
                                    <input type='hidden' name='eliminar_index' value='$index'>
                                    <button type='submit' class='btn-moderno btn-borrar' style='font-size:12px;padding:6px 12px;'>
                                        <i class='fa-solid fa-trash'></i> Eliminar
                                    </button>
                                </form>
                              </td>";
                    echo "</tr>";
                } else {
                    echo "<tr><td colspan='6'>Datos incompletos para el medicamento.</td></tr>";
                }
            }

            echo "</tbody></table>";
        } else {
            echo "<div class='mensaje-sin-resultados'>
                        <i class='fa-solid fa-box-open'></i><br>
                        No hay medicamentos seleccionados.
                      </div>";
        }
        ?>
    </div>
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
        padding: 5px 15px;
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

    /* Ajuste de tabla */
    .table-moderna {
        margin: 0;
        font-size: 12px;
        width: 100%;
        table-layout: auto; /* evita que las columnas se expandan de más */
        border-collapse: collapse;
    }

    /* Encabezados */
    .table-moderna thead th {
        background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
        color: white;
        border: none;
        padding: 12px 8px;
        font-weight: 600;
        text-align: center;
        position: sticky;
        top: 0;
        z-index: 10;
        font-size: 11px;
        white-space: nowrap;
    }

    /* Filas */
    .table-moderna tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f1f3f4;
    }

    .table-moderna tbody tr:hover {
        background-color: var(--color-fondo);
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Celdas */
    .table-moderna tbody td {
        padding: 8px 6px;
        vertical-align: middle;
        border: none;
        text-align: center;
        font-size: 12px;
        white-space: normal;
        word-wrap: break-word;
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
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